/* Saved-data export only: no publishing, saving or sending to WhatsApp. */
(function () {
	'use strict';

	wp.domReady(function () {
		var openButton = document.getElementById('fisar-whatsapp-message-open');
		if (!openButton) { return; }
		var dialog = document.getElementById('fisar-whatsapp-message-dialog');
		var title = document.getElementById('fisar-message-title');
		var text = document.getElementById('fisar-message-text');
		var preview = document.getElementById('fisar-message-preview');
		var status = document.getElementById('fisar-message-status');
		var help = document.getElementById('fisar-message-help');
		var warnings = document.getElementById('fisar-message-warnings');
		var copyButton = document.getElementById('fisar-message-copy');
		var request;
		var savingMetaBoxes = 0;

		function readEventFields() {
			var selector = 'input[name^="_fisar_event_"], select[name^="_fisar_event_"], textarea[name^="_fisar_event_"], textarea#excerpt, input#title, textarea#content';
			return JSON.stringify(Array.from(document.querySelectorAll(selector)).map(function (field) {
				var value = field.value;
				var richEditor = window.tinymce && window.tinymce.get(field.id);
				// A clean TinyMCE instance may normalize the HTML on initialization.
				// Only prefer its content after an actual edit; otherwise keep the original textarea.
				if (richEditor && !richEditor.isHidden() && richEditor.isDirty()) { value = richEditor.getContent(); }
				if (field.type === 'checkbox' || field.type === 'radio') { value = field.checked ? field.value : '0'; }
				return [field.name, value];
			}).sort(function (a, b) { return a[0].localeCompare(b[0]); }));
		}
		var savedFields = readEventFields();

		// Observe the native metabox save without changing its request or response.
		// Commit the submitted snapshot only after HTTP success, never after autosave/failure.
		if (wp.apiFetch && wp.apiFetch.use) {
			wp.apiFetch.use(function (options, next) {
				var body = options.body;
				var isEventSave = /[?&]meta-box-loader=1(?:&|$)/.test(options.url || '') && body && typeof body.get === 'function' && String(body.get('post_ID')) === openButton.dataset.postId;
				if (!isEventSave) { return next(options); }
				var submittedFields = readEventFields();
				savingMetaBoxes++;
				return next(options).then(function (response) {
					if (response.ok) { savedFields = submittedFields; }
					return response;
				}).finally(function () { savingMetaBoxes--; });
			});
		}

		function hasUnsavedChanges() {
			var editor = wp.data && wp.data.select('core/editor');
			if (editor) {
				if (editor.isEditedPostNew() || editor.isEditedPostDirty() || editor.isSavingPost()) { return true; }
			} else if (openButton.dataset.saved === '0') {
				return true;
			}
			return savingMetaBoxes > 0 || readEventFields() !== savedFields;
		}

		// Render only text and fixed formatting elements, never HTML from the message.
		function appendFormatted(parent, value, depth) {
			if (depth > 2) { parent.appendChild(document.createTextNode(value)); return; }
			var pattern = /(^|[\s('’])([*_])(?=\S)([^\n]*?\S)\2(?=$|[\s.,!?;:)'’])/g;
			var cursor = 0;
			var match;
			while ((match = pattern.exec(value))) {
				parent.appendChild(document.createTextNode(value.slice(cursor, match.index) + match[1]));
				var element = document.createElement(match[2] === '*' ? 'strong' : 'em');
				appendFormatted(element, match[3], depth + 1);
				parent.appendChild(element);
				cursor = pattern.lastIndex;
			}
			parent.appendChild(document.createTextNode(value.slice(cursor)));
		}

		function updatePreview() {
			preview.replaceChildren();
			appendFormatted(preview, text.value, 0);
			copyButton.disabled = !text.value.trim();
		}

		async function generate() {
			if (request) { request.abort(); }
			text.disabled = true;
			text.value = '';
			preview.replaceChildren();
			warnings.replaceChildren();
			warnings.hidden = true;
			copyButton.disabled = true;
			if (hasUnsavedChanges()) {
				dialog.dataset.state = 'blocked';
				help.hidden = true;
				dialog.removeAttribute('aria-describedby');
				status.textContent = 'L’evento ha modifiche non salvate oppure il salvataggio è ancora in corso. Chiudi questa finestra, salva l’evento e poi riaprila per generare il messaggio aggiornato.';
				return;
			}
			help.hidden = false;
			dialog.setAttribute('aria-describedby', help.id);
			var controller = new AbortController();
			var errorMessage = 'Non è stato possibile generare il messaggio. Controlla la connessione; se il problema continua, salva le modifiche e ricarica l’editor. Poi chiudi e riapri questa finestra per riprovare.';
			request = controller;
			dialog.dataset.state = 'loading';
			status.textContent = 'Generazione dai dati salvati in corso…';
			try {
				var body = new URLSearchParams({ action: 'fisar_cdj_event_whatsapp_message', post_id: openButton.dataset.postId, nonce: openButton.dataset.nonce });
				var response = await fetch(openButton.dataset.url, { method: 'POST', credentials: 'same-origin', body: body, signal: controller.signal });
				var result = await response.json();
				if (!response.ok || !result || !result.success || !result.data || typeof result.data.message !== 'string') {
					if (result && result.data && typeof result.data.message === 'string') { errorMessage = result.data.message; }
					throw new Error(errorMessage);
				}
				if (controller.signal.aborted) { return; }
				text.value = result.data.message;
				text.disabled = false;
				(result.data.warnings || []).forEach(function (message) {
					var item = document.createElement('li');
					item.textContent = message;
					warnings.appendChild(item);
				});
				warnings.hidden = !warnings.childElementCount;
				updatePreview();
				dialog.dataset.state = 'ready';
				status.textContent = '';
			} catch (error) {
				if (!controller.signal.aborted) { dialog.dataset.state = 'error'; status.textContent = errorMessage; }
			} finally {
				if (request === controller) { request = null; }
			}
		}

		openButton.addEventListener('click', function () {
			dialog.showModal();
			title.focus();
			generate();
		});
		document.getElementById('fisar-message-close').addEventListener('click', function () { dialog.close(); });
		dialog.addEventListener('close', function () {
			if (request) { request.abort(); }
			openButton.focus();
		});
		text.addEventListener('input', function () { updatePreview(); status.textContent = 'Anteprima modificata: l’evento non è stato cambiato.'; });
		copyButton.addEventListener('click', async function () {
			var message = text.value;
			try {
				if (!navigator.clipboard || !navigator.clipboard.writeText) { throw new Error('Clipboard unavailable'); }
				await navigator.clipboard.writeText(message);
				status.textContent = 'Messaggio copiato. Ora puoi incollarlo in WhatsApp.';
			} catch (error) {
				text.focus();
				text.select();
				status.textContent = 'La copia automatica non è disponibile. Il messaggio è selezionato: premi Ctrl+C o ⌘C per copiarlo.';
			}
		});
	});
}());
