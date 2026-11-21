(function () {
	'use strict';
	wp.domReady(function () {
		const root = document.getElementById('fisar-event-cancellation-fields');
		if (!root) return;
		const mode = root.querySelector('[name$="[mode]"]');
		const after = root.querySelector('[name$="[after]"]');
		const rows = document.getElementById('fisar-cancellation-rules');
		const add = document.getElementById('fisar-cancellation-add');
		const error = document.getElementById('fisar-cancellation-error');
		const status = document.getElementById('fisar-cancellation-status');
		let locked = false;
		function update() {
			root.querySelectorAll('[data-cancellation-mode]').forEach(section => {
				section.hidden = section.dataset.cancellationMode !== mode.value;
				section.querySelectorAll('input, select, textarea').forEach(field => { field.disabled = section.hidden; });
			});
			const percentageSection = root.querySelector('[data-cancellation-after-percent]');
			percentageSection.hidden = after.value !== 'percent';
			percentageSection.querySelector('input').disabled = mode.value !== 'rules' || after.value !== 'percent';
			let message = '';
			if (mode.value === 'rules') {
				const deadlines = [];
				rows.querySelectorAll('.fisar-cancellation-row').forEach(row => {
					const date = row.querySelector('[type="date"]');
					const time = row.querySelector('[type="time"]');
					const percent = row.querySelector('[type="number"]');
					if (!date.value && !time.value && !percent.value && date.validity.valid && time.validity.valid && percent.validity.valid) return;
					if (!date.value || !percent.value || !date.validity.valid || !time.validity.valid || !percent.validity.valid) message = 'Completa data e rimborso di ogni scadenza. La percentuale deve essere compresa tra 0 e 100.';
					deadlines.push(date.value + ' ' + (time.value || '23:59'));
				});
				if (!deadlines.length) message = 'Inserisci almeno una scadenza di rimborso.';
				else if (deadlines.length > Number(root.dataset.maxRules)) message = 'Puoi inserire al massimo 20 scadenze.';
				else if (new Set(deadlines).size !== deadlines.length) message = 'Due scadenze non possono coincidere.';
				const afterPercent = percentageSection.querySelector('input');
				if (!after.value || (after.value === 'percent' && (!afterPercent.value || !afterPercent.validity.valid))) message = 'Specifica il rimborso dopo l’ultima scadenza, oppure scegli Da verificare con la Delegazione.';
			} else if (mode.value === 'text') {
				const textarea = document.getElementById('fisar_cancellation_text');
				const editor = window.tinymce && window.tinymce.get(textarea.id);
				const html = editor && !editor.isHidden() ? editor.getContent() : textarea.value;
				if (!new DOMParser().parseFromString(html, 'text/html').body.textContent.trim()) message = 'Compila il testo delle condizioni oppure scegli Non specificata.';
			}
			error.textContent = message;
			error.hidden = !message;
			mode.setCustomValidity(message);
			if (root.dataset.editor === 'block' && Boolean(message) !== locked) {
				locked = Boolean(message);
				wp.data.dispatch('core/editor')[locked ? 'lockPostSaving' : 'unlockPostSaving']('fisar-event-cancellation');
			}
			add.disabled = rows.querySelectorAll('.fisar-cancellation-row').length >= Number(root.dataset.maxRules);
			return !message;
		}
		root.addEventListener('input', update);
		root.addEventListener('change', update);
		root.addEventListener('click', event => {
			const button = event.target.closest('.fisar-cancellation-remove');
			if (!button) return;
			const row = button.closest('.fisar-cancellation-row');
			const input = row.querySelector('input');
			input.value = '';
			input.dispatchEvent(new Event('change', { bubbles: true }));
			row.remove(); add.focus(); status.textContent = 'Scadenza rimossa.'; update();
		});
		add.hidden = false;
		root.querySelectorAll('.fisar-cancellation-remove').forEach(button => { button.hidden = false; });
		add.addEventListener('click', () => {
			const fragment = document.getElementById('fisar-cancellation-template').content.cloneNode(true);
			const index = rows.dataset.nextIndex;
			fragment.querySelectorAll('[id], [name], [for]').forEach(element => {
				['id', 'name', 'for'].forEach(attribute => {
					if (element.hasAttribute(attribute)) element.setAttribute(attribute, element.getAttribute(attribute).replace('__INDEX__', index));
				});
			});
			fragment.querySelector('button').hidden = false;
			const input = fragment.querySelector('input'); rows.appendChild(fragment);
			rows.dataset.nextIndex = String(Number(index) + 1);
			input.dispatchEvent(new Event('change', { bubbles: true })); input.focus();
			status.textContent = 'Scadenza aggiunta.'; update();
		});
		function watchEditor(editor) {
			if (editor.id === 'fisar_cancellation_text') editor.on('init input change undo redo SetContent', update);
		}
		if (window.tinymce) {
			window.tinymce.on('AddEditor', event => watchEditor(event.editor));
			Array.from(window.tinymce.editors || []).forEach(watchEditor);
		}
		const form = root.closest('form');
		if (form) form.addEventListener('submit', event => { if (!update()) { event.preventDefault(); mode.focus(); } });
		update();
	});
}());
