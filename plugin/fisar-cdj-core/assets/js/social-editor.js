/* One shared social post; native article saves never trigger publication. */
(function () {
	'use strict';
	const labels = { draft: 'Bozza in Buffer', scheduled: 'Programmato', sending: 'Pubblicazione in corso', sent: 'Pubblicato', error: 'Pubblicazione non riuscita', rejected: 'Invio rifiutato', unknown: 'Esito da verificare', needs_approval: 'In attesa di approvazione' };
	function issues(post, network) {
		const errors = [];
		if (post.images.length > network.images) errors.push(`${network.label}: hai selezionato ${post.images.length} foto, ma il limite è ${network.images}. Scegli quali mantenere: nessuna foto verrà esclusa automaticamente.`);
		if (!post.text.trim()) errors.push('Inserisci il testo del post.');
		if (Array.from(post.text).length > network.characters) errors.push(`${network.label}: il testo supera il limite di ${network.characters.toLocaleString('it-IT')} caratteri. Accorcialo prima dell’invio.`);
		if (network.label === 'Instagram' && !post.images.length) errors.push('Instagram richiede almeno una foto.');
		return errors;
	}
	function selectedNetworks(post, networks) { return Object.keys(networks).filter((service) => post.networks[service]); }
	function sharedIssues(post, networks, articleImages) {
		const selected = selectedNetworks(post, networks);
		const errors = selected.length ? [...new Set(selected.flatMap((service) => issues(post, networks[service])))] : ['Seleziona almeno un social.'];
		if (articleImages && post.images.some((id) => !articleImages.includes(id))) errors.push('Alcune foto non sono più nell’articolo. Rimuovile dal post social oppure aggiungile all’articolo e salvalo.');
		return errors;
	}
	function deliveryLocked(entry) { return Boolean(entry && (entry.id || entry.status === 'unknown')); }
	function operationStatus(intent, live, mode) {
		if (intent === 'send') {
			const label = !live ? 'Simulazione in corso…' : (mode === 'scheduled' ? 'Programmazione in corso…' : 'Pubblicazione in corso…');
			return { label, message: `${label} Attendi l’esito.` };
		}
		const label = { save: 'Salvataggio in corso…', refresh: 'Aggiornamento esiti…', reread: 'Lettura articolo…' }[intent] || '';
		return { label, message: label };
	}
	function mediaIssues(post, networks, rows) {
		return post.images.flatMap((id) => selectedNetworks(post, networks).map((service) => {
			const variant = rows.find((row) => row.id === id)?.variants[service];
			return !variant ? `${networks[service].label}: una copia della foto non è disponibile. Rileggi l’articolo.` : (variant.error ? `${networks[service].label}: ${variant.error}` : '');
		})).filter(Boolean);
	}
	function syncSocialUrls(urls, doc) {
		Object.entries(urls || {}).forEach(([key, url]) => {
			if (!['_fisar_report_facebook_url', '_fisar_report_instagram_url'].includes(key)) return;
			const field = doc.getElementById(key);
			const original = doc.querySelector(`input[name="_fisar_report_social_original[${key}]"]`);
			if (field && original && field.value === original.value) field.value = original.value = url;
		});
	}
	/** Native modal semantics with focus return and protection during an active request. */
	function bindDialog({ dialog, opener, closer, title, isBusy, onOpen, onClose }) {
		opener.addEventListener('click', () => {
			if (dialog.open) return;
			dialog.showModal(); title.focus(); onOpen();
		});
		closer.addEventListener('click', () => { if (!isBusy()) dialog.close(); });
		dialog.addEventListener('cancel', (event) => { if (isBusy()) event.preventDefault(); });
		dialog.addEventListener('close', () => {
			if (!dialog.open) { opener.focus(); onClose(); }
		});
	}
	if (typeof module !== 'undefined') module.exports = { issues, sharedIssues, mediaIssues, deliveryLocked, operationStatus, bindDialog, syncSocialUrls };
	if (typeof window === 'undefined' || !window.wp) return;
	const wp = window.wp;
	wp.domReady(function () {
		const root = document.getElementById('fisar-social-composer');
		if (!root) return;
		const container = root.querySelector('[data-social-content]');
		const dialog = document.getElementById('fisar-social-dialog');
		const footer = dialog.querySelector('[data-social-footer]');
		const opener = document.getElementById('fisar-social-open');
		const closer = document.getElementById('fisar-social-close');
		const launcherStatus = document.getElementById('fisar-social-launcher-status');
		// Gutenberg's scrollable metabox/form must not constrain the dialog or own its controls.
		document.body.append(dialog);
		const path = `/fisar-cdj/v1/social/${root.dataset.postId}`;
		let state, busy = false, activeIntent = '', dirty = false, needsReread = false, initialized = false, initializing = false;
		const media = new Map();
		let sendButton, saveButton, refreshButton, rereadButton, resultBox, messageBox, messageText, progressSpinner, dirtyBox, modeSelect, dateInput;
		let fields, channelFields, textInput, textCount, photoCount, warning, photoGrid, availablePhotos, photoAnnouncement, previewSelect, formatNote;
		let previewService = 'instagram';
		bindDialog({
			dialog, opener, closer, title: document.getElementById('fisar-social-dialog-title'),
			isBusy: () => busy,
			onOpen: () => { if (initialized) updateControls(); else init(); },
			onClose: () => { photoGrid?.cancelDrag(); launcherStatus.textContent = dirty ? 'Preparazione modificata, non ancora salvata. Riapri la finestra per continuare.' : (state?.live ? 'Riapri per preparare il post o controllare gli esiti.' : 'Modalità prova: nessun post viene pubblicato sui social.'); },
		});
		function element(tag, text, className) {
			const node = document.createElement(tag);
			if (text !== undefined) node.textContent = text;
			if (className) node.className = className;
			return node;
		}
		function button(text, action, className = 'button') {
			const node = element('button', text, className);
			node.type = 'button'; node.addEventListener('click', action);
			return node;
		}
		function classicValue() { return `${document.getElementById('title')?.value || ''}\n${window.tinymce?.get('content')?.getContent() || document.getElementById('content')?.value || ''}`; }
		const classicInitial = classicValue();
		function nativeDirty() {
			const editor = wp.data.select('core/editor');
			return editor?.getCurrentPostId?.() ? editor.isEditedPostDirty() || editor.isSavingPost() : classicValue() !== classicInitial;
		}
		function notify(text, error = false) {
			messageText.textContent = text;
			messageBox.className = `fisar-social-notice${error ? ' fisar-social-error' : ''}`;
			messageBox.hidden = !text;
		}
		function updateControls() {
			const selected = selectedNetworks(state.composer, state.networks);
			const remaining = selected.filter((service) => !state.live || !deliveryLocked(state.delivery[service]));
			const mediaErrors = mediaIssues(state.composer, state.networks, state.media).length > 0;
			sendButton.disabled = busy || !state.canPublish || !remaining.length || mediaErrors || sharedIssues(state.composer, state.networks, state.articleImages).length > 0 || nativeDirty();
			const progress = operationStatus(activeIntent, state.live, state.composer.mode);
			sendButton.textContent = activeIntent === 'send' ? progress.label : (state.live ? (state.composer.mode === 'scheduled' ? 'Programma sui social' : 'Pubblica sui social') : 'Simula invio');
			sendButton.classList.toggle('is-publishing', activeIntent === 'send');
			sendButton.setAttribute('aria-busy', String(activeIntent === 'send'));
			saveButton.textContent = activeIntent === 'save' ? progress.label : 'Salva preparazione';
			refreshButton.textContent = activeIntent === 'refresh' ? progress.label : 'Aggiorna esiti';
			rereadButton.textContent = activeIntent === 'reread' ? progress.label : 'Aggiorna dati articolo';
			rereadButton.hidden = !needsReread;
			progressSpinner.hidden = !busy;
			saveButton.disabled = refreshButton.disabled = rereadButton.disabled = modeSelect.disabled = dateInput.disabled = busy;
			fields.disabled = channelFields.disabled = busy;
			previewSelect.disabled = busy;
			photoGrid.setDisabled(busy);
			closer.disabled = busy;
			dirtyBox.hidden = !nativeDirty();
			container.setAttribute('aria-busy', String(busy));
		}
		function startOperation(intent) {
			activeIntent = intent; busy = true; updateControls();
			notify(operationStatus(intent, state.live, state.composer.mode).message);
		}
		function finishOperation() { activeIntent = ''; busy = false; updateControls(); }
		function markDirty() { dirty = true; updateControls(); }
		function setPreview(service) {
			previewService = service; media.clear();
			state.media.forEach((row) => media.set(row.id, row.variants[service]));
			if (previewSelect) previewSelect.value = service;
			if (photoGrid) { renderAvailablePhotos(); photoGrid.render(); updatePreview(); }
		}
		function updatePreview() {
			const post = state.composer;
			fields.dataset.previewNetwork = previewService;
			const selected = selectedNetworks(post, state.networks);
			const maxImages = selected.length ? Math.min(...selected.map((service) => state.networks[service].images)) : null;
			const maxCharacters = selected.length ? Math.min(...selected.map((service) => state.networks[service].characters)) : null;
			photoCount.textContent = `${post.images.length} foto selezionate${maxImages ? ` · massimo ${maxImages}` : ''}`;
			formatNote.textContent = previewService === 'facebook' ? 'Facebook: foto intere nelle proporzioni originali, senza margini aggiunti.' : 'Instagram: copie 1080 × 1080 px con foto intere e margini bianchi dove servono, per conservare tutte le inquadrature nel carosello.';
			textCount.textContent = `${Array.from(post.text).length.toLocaleString('it-IT')}${maxCharacters ? ` / ${maxCharacters.toLocaleString('it-IT')}` : ''} caratteri`;
			const mediaErrors = mediaIssues(post, state.networks, state.media);
			warning.textContent = [...new Set([...sharedIssues(post, state.networks, state.articleImages), ...mediaErrors])].join(' '); warning.hidden = !warning.textContent;
			updateControls();
		}
		function availableImageIds() { return state.articleImages.filter((id) => !state.composer.images.includes(id)); }
		function renderAvailablePhotos() {
			availablePhotos.replaceChildren();
			const heading = element('h4', 'Foto dell’articolo disponibili');
			const close = button('Chiudi selezione', () => { availablePhotos.hidden = true; (photoGrid.root.querySelector('.fisar-social-photo-add-button:enabled') || photoGrid.root.querySelector('select'))?.focus(); });
			const header = element('div', undefined, 'fisar-social-actions'); header.append(heading, close); availablePhotos.append(header);
			const available = availableImageIds();
			if (!available.length) { availablePhotos.append(element('p', 'Tutte le foto dell’articolo sono già selezionate.')); return; }
			const choices = element('div', undefined, 'fisar-social-available-grid');
			available.forEach((id) => {
				const image = media.get(id), position = state.articleImages.indexOf(id) + 1;
				const add = button('', () => {
					state.composer.images.push(id); photoGrid.render(); renderAvailablePhotos(); updatePreview(); markDirty();
					photoAnnouncement.textContent = `Foto aggiunta in posizione ${state.composer.images.length}.`;
					(availablePhotos.querySelector('button[data-add-photo]') || availablePhotos.querySelector('button'))?.focus();
				}, 'fisar-social-available-photo');
				add.dataset.addPhoto = id; add.setAttribute('aria-label', `Aggiungi foto ${position} dell’articolo`);
				const img = element('img'); img.src = image?.preview || image?.url || image?.thumbnail || ''; img.alt = image?.alt || image?.title || '';
				add.append(img, element('span', `+ Foto ${position}`)); choices.append(add);
			});
			availablePhotos.append(choices);
		}
		async function reread() {
			if (busy) return false;
			if (nativeDirty()) { notify('Salva prima le modifiche dell’articolo.', true); return false; }
			startOperation('reread');
			try {
				const fresh = await wp.apiFetch({ path });
				state.version = fresh.version; state.articleImages = fresh.articleImages; state.suggestion = fresh.suggestion;
				state.media = fresh.media; needsReread = false; setPreview(previewService);
				notify('Dati dell’articolo riletti.');
				return true;
			} finally { finishOperation(); }
		}
		function renderComposer() {
			channelFields = element('fieldset', undefined, 'fisar-social-channels'); channelFields.append(element('legend', 'Dove pubblicare'));
			Object.entries(state.networks).forEach(([service, network]) => {
				const enabled = element('input'); enabled.type = 'checkbox'; enabled.checked = state.composer.networks[service];
				const label = element('label'); label.append(enabled, document.createTextNode(` ${network.label}`));
				enabled.addEventListener('change', () => {
					state.composer.networks[service] = enabled.checked;
					const selected = selectedNetworks(state.composer, state.networks);
					if (selected.length === 1) setPreview(selected[0]); else updatePreview();
					markDirty();
				});
				const account = state.accounts[service] || (state.live ? 'Account da collegare' : 'Account non collegato · modalità prova');
				const group = element('div'); group.append(label, element('p', account, 'description')); channelFields.append(group);
			});
			container.append(channelFields);
			fields = element('fieldset', undefined, 'fisar-social-post'); fields.append(element('legend', 'Post per i canali selezionati'));
			const textLabel = element('label', 'Testo del post'); textLabel.htmlFor = 'fisar-social-text';
			textInput = element('textarea'); textInput.id = textLabel.htmlFor; textInput.value = state.composer.text; textInput.rows = 7;
			textInput.setAttribute('aria-describedby', 'fisar-social-text-count');
			textCount = element('p', '', 'description'); textCount.id = 'fisar-social-text-count';
			textInput.addEventListener('input', () => { state.composer.text = textInput.value; updatePreview(); markDirty(); });
			const useText = button('Usa testo dell’articolo', async () => {
				try { if (await reread()) { state.composer.text = state.suggestion; textInput.value = state.composer.text; updatePreview(); markDirty(); } } catch (error) { notify(error.message, true); }
			});
			photoCount = element('p'); warning = element('p', '', 'fisar-social-warning'); warning.setAttribute('role', 'status'); warning.setAttribute('aria-live', 'polite');
			previewSelect = element('select'); previewSelect.id = 'fisar-social-preview-network';
			Object.entries(state.networks).forEach(([service, network]) => { const option = element('option', network.label); option.value = service; previewSelect.append(option); });
			previewSelect.value = previewService; previewSelect.addEventListener('change', () => setPreview(previewSelect.value));
			const previewLabel = element('label', 'Anteprima foto per'); previewLabel.htmlFor = previewSelect.id;
			const previewControl = element('div', undefined, 'fisar-social-actions'); previewControl.append(previewLabel, previewSelect);
			formatNote = element('p', '', 'description');
			const photos = element('ol', undefined, 'fisar-social-photo-grid'); photos.setAttribute('aria-label', 'Anteprima e ordine delle foto');
			availablePhotos = element('div', undefined, 'fisar-social-available'); availablePhotos.hidden = true;
			photoAnnouncement = element('p', '', 'screen-reader-text'); photoAnnouncement.setAttribute('role', 'status'); photoAnnouncement.setAttribute('aria-live', 'polite');
			photoGrid = new window.FisarSocialPhotoGrid({
				root: photos, getIds: () => state.composer.images, getMedia: (id) => media.get(id),
				onChange: (ids) => { state.composer.images = ids; renderAvailablePhotos(); updatePreview(); markDirty(); },
				onAdd: () => { renderAvailablePhotos(); availablePhotos.hidden = false; availablePhotos.querySelector('button[data-add-photo]')?.focus(); },
				canAdd: () => availableImageIds().length > 0,
				announce: (text) => { photoAnnouncement.textContent = text; },
			});
			const photoActions = element('div', undefined, 'fisar-social-actions');
			photoActions.append(button('Usa foto dell’articolo', async () => {
				try { if (await reread()) { state.composer.images = [...state.articleImages]; photoGrid.render(); renderAvailablePhotos(); updatePreview(); markDirty(); } } catch (error) { notify(error.message, true); }
			}));
			fields.append(textLabel, textInput, textCount, useText,
				element('h3', 'Anteprima delle foto'),
				element('p', 'La selezione e l’ordine delle foto valgono per entrambi i social. Il menu cambia solo l’anteprima del formato.', 'description'),
				previewControl, formatNote,
				photoCount, warning, photos, availablePhotos, photoAnnouncement, photoActions);
			container.append(fields);
		}
		function renderResults() {
			resultBox.replaceChildren();
			if (Object.keys(state.delivery).length) resultBox.append(element('h3', 'Esiti degli invii'), element('p', 'Questi sono gli esiti degli ultimi tentativi. Le modifiche alla preparazione non vengono inviate finché non premi “Pubblica sui social”.', 'description'));
			Object.entries(state.delivery).forEach(([service, entry]) => {
				const item = element('div', undefined, 'fisar-social-result'); item.append(element('strong', `${state.networks[service]?.label || service}: ${labels[entry.status] || entry.status}`));
				if (entry.dueAt) item.append(element('p', `Data prevista: ${new Date(entry.dueAt).toLocaleString('it-IT', { timeZone: state.timezone })}`));
				if (entry.message) item.append(element('p', entry.message));
				if (entry.url && /^https:\/\//.test(entry.url)) { const link = element('a', 'Apri il post pubblicato'); link.href = entry.url; link.target = '_blank'; link.rel = 'noopener noreferrer'; item.append(link); }
				if (entry.id) item.append(element('p', 'Post già creato: questo pannello non lo invia una seconda volta. Aggiorna gli esiti per verificare la pubblicazione.', 'description'));
				if (entry.status === 'unknown') item.append(element('p', 'Reinvio bloccato per evitare duplicati. Controlla la presenza del post in Buffer.', 'description'));
				resultBox.append(item);
			});
		}
		async function action(intent) {
			if (busy) return;
			if (intent === 'send' && nativeDirty()) { notify('Salva prima le modifiche dell’articolo.', true); return; }
			startOperation(intent);
			try {
				const response = await wp.apiFetch({ path, method: 'POST', data: { intent, composer: state.composer, version: state.version } });
				syncSocialUrls(response.socialUrls, document);
				if (response.delivery) { state.delivery = response.delivery; renderResults(); }
				if (intent !== 'refresh') dirty = false;
				notify(response.message || 'Esiti aggiornati. Un post accettato da Buffer può essere ancora in pubblicazione.');
			} catch (error) {
				if (error.code === 'social_changed') needsReread = true;
				notify(error.message || 'Richiesta interrotta. Aggiorna gli esiti prima di riprovare.', true);
				try { const fresh = await wp.apiFetch({ path }); state.delivery = fresh.delivery; syncSocialUrls(fresh.socialUrls, document); renderResults(); } catch (_) { /* No automatic retry after an ambiguous outcome. */ }
			} finally { finishOperation(); }
		}
		async function init() {
			if (initializing) return;
			initializing = true;
			try {
				state = await wp.apiFetch({ path }); syncSocialUrls(state.socialUrls, document); setPreview(state.composer.networks.instagram ? 'instagram' : 'facebook'); container.replaceChildren();
				dirtyBox = element('p', 'Ci sono modifiche dell’articolo non salvate. Salvale prima dell’invio.', 'fisar-social-warning'); dirtyBox.hidden = true; container.append(dirtyBox);
				footer.replaceChildren();
				messageBox = element('div', undefined, 'fisar-social-notice'); messageBox.hidden = true; messageBox.setAttribute('role', 'status'); messageBox.setAttribute('aria-live', 'polite'); messageBox.setAttribute('aria-atomic', 'true');
				progressSpinner = element('span', undefined, 'spinner is-active'); progressSpinner.hidden = true; progressSpinner.setAttribute('aria-hidden', 'true');
				messageText = element('span'); messageBox.append(progressSpinner, messageText);
				renderComposer();
				modeSelect = element('select'); modeSelect.id = 'fisar-social-mode';
				[['now', 'Pubblica adesso'], ['scheduled', 'Programma data e ora']].forEach(([value, label]) => { const option = element('option', label); option.value = value; modeSelect.append(option); }); modeSelect.value = state.composer.mode;
				const modeLabel = element('label', 'Quando pubblicare'); modeLabel.htmlFor = modeSelect.id;
				const dateLabel = element('label', `Data e ora (${state.timezone})`); dateLabel.htmlFor = 'fisar-social-date';
				dateInput = element('input'); dateInput.type = 'datetime-local'; dateInput.id = dateLabel.htmlFor; dateInput.value = state.composer.scheduled_at; dateInput.hidden = dateLabel.hidden = state.composer.mode !== 'scheduled';
				modeSelect.addEventListener('change', () => { state.composer.mode = modeSelect.value; dateInput.hidden = dateLabel.hidden = modeSelect.value !== 'scheduled'; markDirty(); });
				dateInput.addEventListener('input', () => { state.composer.scheduled_at = dateInput.value; markDirty(); });
				const scheduling = element('div', undefined, 'fisar-social-actions'); scheduling.append(modeLabel, modeSelect, dateLabel, dateInput); container.append(scheduling);
				const actions = element('div', undefined, 'fisar-social-actions'); saveButton = button('Salva preparazione', () => action('save'));
				sendButton = button(state.live ? 'Pubblica sui social' : 'Simula invio', () => action('send'), 'button button-primary'); refreshButton = button('Aggiorna esiti', () => action('refresh'));
				rereadButton = button('Aggiorna dati articolo', async () => {
					try {
						if (await reread()) {
							notify('Dati dell’articolo aggiornati. Testo, selezione e ordine del post preparato restano invariati.');
							(sendButton.disabled ? saveButton : sendButton).focus();
						}
					} catch (error) { notify(error.message, true); }
				});
				actions.append(saveButton, sendButton, refreshButton, rereadButton); footer.append(actions, messageBox);
				resultBox = element('div'); resultBox.setAttribute('aria-live', 'polite'); container.append(resultBox); photoGrid.render(); renderAvailablePhotos(); updatePreview(); renderResults();
				footer.hidden = false;
				initialized = true;
				const unsubscribe = wp.data.subscribe(updateControls); window.addEventListener('pagehide', unsubscribe, { once: true });
				window.addEventListener('beforeunload', (event) => { if (dirty || busy) { event.preventDefault(); event.returnValue = ''; } });
			} catch (error) { footer.hidden = true; container.replaceChildren(element('p', error.message || 'Impossibile aprire la preparazione social.', 'fisar-social-warning fisar-social-error'), button('Riprova', init)); }
			finally { initializing = false; }
		}
	});
})();
