(function () {
	'use strict';
	wp.domReady(function () {
		const root = document.getElementById('fisar-event-calendar');
		if (!root) return;
		const mode = root.querySelector('[name="_fisar_event_date_mode"]');
		const first = root.querySelector('[name="_fisar_event_date"]');
		const end = root.querySelector('[name="_fisar_event_end_date"]');
		const dates = document.getElementById('fisar-event-additional-dates');
		const error = document.getElementById('fisar-event-date-error');
		const status = document.getElementById('fisar-event-date-status');
		const add = document.getElementById('fisar-event-date-add');
		let locked = false;
		function update() {
			root.querySelectorAll('[data-event-date-modes]').forEach(section => { section.hidden = section.dataset.eventDateModes !== mode.value; });
			end.min = first.value;
			end.required = mode.value === 'range';
			let message = '';
			if (mode.value === 'range' && (!first.value || !end.value || end.value < first.value)) {
				message = 'Inserisci una data finale uguale o successiva alla data iniziale.';
			} else if (mode.value === 'multiple') {
				const dateValues = [first.value, ...Array.from(dates.querySelectorAll('input[type="date"]')).map(input => input.value)].filter(Boolean);
				const values = new Set(dateValues);
				if (!first.value || values.size < 2) message = 'Inserisci almeno due date distinte.';
				else if (values.size !== dateValues.length) message = 'Ogni data può comparire una sola volta.';
				dates.querySelectorAll('.fisar-event-date-row').forEach(row => {
					if (!row.querySelector('input[type="date"]').value && row.querySelector('input[type="text"]').value.trim()) message = 'Completa la data della riga con la nota, oppure svuota la nota.';
				});
			}
			error.textContent = message;
			error.hidden = !message;
			end.setCustomValidity(mode.value === 'range' ? message : '');
			if (root.dataset.editor === 'block' && Boolean(message) !== locked) {
				locked = Boolean(message);
				wp.data.dispatch('core/editor')[locked ? 'lockPostSaving' : 'unlockPostSaving']('fisar-event-calendar');
			}
			return !message;
		}
		root.addEventListener('input', update);
		root.addEventListener('change', update);
		root.addEventListener('click', event => {
			const remove = event.target.closest('.fisar-event-date-remove');
			if (!remove) return;
			const row = remove.closest('.fisar-event-date-row');
			const input = row.querySelector('input');
			input.value = '';
			input.dispatchEvent(new Event('change', { bubbles: true }));
			row.remove();
			add.focus();
			status.textContent = 'Data rimossa.';
			update();
		});
		add.hidden = false;
		root.querySelectorAll('.fisar-event-date-remove').forEach(button => { button.hidden = false; });
		add.addEventListener('click', () => {
			const fragment = document.getElementById('fisar-event-date-template').content.cloneNode(true);
			const index = dates.dataset.nextIndex;
			fragment.querySelectorAll('[id], [name], [for]').forEach(element => {
				['id', 'name', 'for'].forEach(attribute => {
					if (element.hasAttribute(attribute)) element.setAttribute(attribute, element.getAttribute(attribute).replace('__INDEX__', index));
				});
			});
			fragment.querySelector('button').hidden = false;
			const input = fragment.querySelector('input');
			dates.appendChild(fragment);
			dates.dataset.nextIndex = String(Number(index) + 1);
			input.focus();
			status.textContent = 'Data aggiunta.';
			update();
		});
		const form = root.closest('form');
		if (form) form.addEventListener('submit', event => { if (!update()) { event.preventDefault(); mode.focus(); } });
		update();
	});
}());
