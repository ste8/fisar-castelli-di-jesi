(function () {
	'use strict';

	function field(name) {
		return document.getElementById(name);
	}

	function updateConditionalFields() {
		var modeField = field('_fisar_event_mode');
		document.querySelectorAll('[data-show-modes]').forEach(function (section) {
			var modes = section.dataset.showModes.split(',');
			section.hidden = !modeField || modes.indexOf(modeField.value) === -1;
		});

		document.querySelectorAll('[data-hide-when-checked]').forEach(function (section) {
			var controller = field(section.dataset.hideWhenChecked);
			section.hidden = Boolean(controller && controller.checked);
		});

		document.querySelectorAll('[data-show-when-checked]').forEach(function (section) {
			var controller = field(section.dataset.showWhenChecked);
			section.hidden = !controller || !controller.checked;
		});
	}

	function normaliseDate(value) {
		var match = value.trim().match(/^(\d{1,2})[./-](\d{1,2})[./-](\d{4})$/);
		if (match) {
			return match[3] + '-' + match[2].padStart(2, '0') + '-' + match[1].padStart(2, '0');
		}
		return /^\d{4}-\d{2}-\d{2}$/.test(value.trim()) ? value.trim() : '';
	}

	function announceFeeChange(message) {
		var status = field('fisar-event-fee-status');
		if (status) {
			status.textContent = message;
		}
	}

	function addFeeOption() {
		var container = field('fisar-event-fee-options');
		var template = field('fisar-event-fee-option-template');
		if (!container || !template) {
			return;
		}

		var index = Number(container.dataset.nextIndex);
		var fragment = template.content.cloneNode(true);
		fragment.querySelectorAll('[id], [name], [for]').forEach(function (element) {
			['id', 'name', 'for'].forEach(function (attribute) {
				if (element.hasAttribute(attribute)) {
					element.setAttribute(attribute, element.getAttribute(attribute).replace('__INDEX__', String(index)));
				}
			});
		});
		var input = fragment.querySelector('input');
		fragment.querySelector('.fisar-event-fee-remove').hidden = false;
		container.appendChild(fragment);
		container.dataset.nextIndex = String(index + 1);
		input.focus();
		announceFeeChange('Quota aggiunta. Compila etichetta e importo.');
	}

	function removeFeeOption(button) {
		var option = button.closest('.fisar-event-fee-option');
		var neighbour = option.nextElementSibling || option.previousElementSibling;
		var target = neighbour ? neighbour.querySelector('input') : field('fisar-event-fee-add');
		var label = option.querySelector('input').value.trim();
		option.remove();
		target.focus();
		announceFeeChange(label ? 'Quota rimossa: ' + label + '.' : 'Quota rimossa.');
	}

	function initFeeOptions() {
		var addButton = field('fisar-event-fee-add');
		if (!addButton) {
			return;
		}
		addButton.hidden = false;
		field('fisar-event-fee-noscript').hidden = true;
		document.querySelectorAll('.fisar-event-fee-remove').forEach(function (button) {
			button.hidden = false;
		});
	}

	function looksLikeHeader(columns) {
		var first = (columns[0] || '').trim().toLowerCase();
		return ['data', 'data lezione', 'giorno'].indexOf(first) !== -1;
	}

	function announceWhatsAppChange(message) {
		field('fisar-whatsapp-status').textContent = message;
	}

	function addWhatsAppContact() {
		var container = field('fisar-whatsapp-contacts');
		var fragment = field('fisar-whatsapp-template').content.cloneNode(true);
		var index = Number(container.dataset.nextIndex);
		fragment.querySelectorAll('[id], [name], [for]').forEach(function (element) {
			['id', 'name', 'for'].forEach(function (attribute) {
				if (element.hasAttribute(attribute)) {
					element.setAttribute(attribute, element.getAttribute(attribute).replace('__INDEX__', String(index)));
				}
			});
		});
		var input = fragment.querySelector('input');
		fragment.querySelector('.fisar-whatsapp-remove').hidden = false;
		container.appendChild(fragment);
		container.dataset.nextIndex = String(index + 1);
		input.focus();
		announceWhatsAppChange('Contatto WhatsApp aggiunto. Compila il numero o il link; il nominativo è facoltativo.');
	}

	function removeWhatsAppContact(button) {
		var contact = button.closest('.fisar-whatsapp-contact');
		var neighbour = contact.nextElementSibling || contact.previousElementSibling;
		var target = neighbour ? neighbour.querySelector('input') : field('fisar-whatsapp-add');
		var name = contact.querySelector('input').value.trim();
		contact.remove();
		target.focus();
		announceWhatsAppChange(name ? 'Contatto rimosso: ' + name + '.' : 'Contatto WhatsApp rimosso.');
	}

	function initWhatsAppContacts() {
		var addButton = field('fisar-whatsapp-add');
		if (!addButton) {
			return;
		}
		addButton.hidden = false;
		field('fisar-whatsapp-noscript').hidden = true;
		document.querySelectorAll('.fisar-whatsapp-remove').forEach(function (button) {
			button.hidden = false;
		});
	}

	function addCalendarRow(values) {
		var body = document.querySelector('#fisar-calendar-table tbody');
		if (!body) {
			return;
		}

		var index = body.querySelectorAll('tr').length;
		var row = document.createElement('tr');
		['date', 'time', 'title', 'speaker', 'notes'].forEach(function (key) {
			var cell = document.createElement('td');
			var input = document.createElement('input');
			input.type = key === 'date' ? 'date' : 'text';
			input.name = 'fisar_course_calendar[' + index + '][' + key + ']';
			input.value = values && values[key] ? values[key] : '';
			input.setAttribute('aria-label', key);
			cell.appendChild(input);
			row.appendChild(cell);
		});

		var actionCell = document.createElement('td');
		var removeButton = document.createElement('button');
		removeButton.type = 'button';
		removeButton.className = 'button-link-delete fisar-calendar-remove';
		removeButton.textContent = 'Rimuovi';
		actionCell.appendChild(removeButton);
		row.appendChild(actionCell);
		body.appendChild(row);
	}

	function previewCalendar() {
		var source = field('fisar_course_calendar_import');
		var body = document.querySelector('#fisar-calendar-table tbody');
		if (!source || !body || !source.value.trim()) {
			return;
		}

		var parsedRows = [];
		source.value.trim().split(/\r?\n/).forEach(function (line) {
			var columns = line.split('\t');
			if (looksLikeHeader(columns) || columns.length < 3) {
				return;
			}
			var date = normaliseDate(columns[0]);
			if (!date) {
				return;
			}
			parsedRows.push({
				date: date,
				time: (columns[1] || '').trim(),
				title: (columns[2] || '').trim(),
				speaker: (columns[3] || '').trim(),
				notes: columns.slice(4).join(' — ').trim()
			});
		});

		if (!parsedRows.length) {
			return;
		}
		body.innerHTML = '';
		parsedRows.forEach(addCalendarRow);
	}

	document.addEventListener('change', function (event) {
		if (event.target.matches('#_fisar_event_mode, #_fisar_event_is_free, #_fisar_event_registration_required')) {
			updateConditionalFields();
		}
	});

	document.addEventListener('click', function (event) {
		if (event.target.matches('#fisar-whatsapp-add')) {
			addWhatsAppContact();
		}
		if (event.target.matches('.fisar-whatsapp-remove')) {
			removeWhatsAppContact(event.target);
		}
		if (event.target.matches('#fisar-event-fee-add')) {
			addFeeOption();
		}
		if (event.target.matches('.fisar-event-fee-remove')) {
			removeFeeOption(event.target);
		}
		if (event.target.matches('#fisar-calendar-add-row')) {
			addCalendarRow();
		}
		if (event.target.matches('#fisar-calendar-preview')) {
			previewCalendar();
		}
		if (event.target.matches('.fisar-calendar-remove')) {
			event.target.closest('tr').remove();
		}
	});

	updateConditionalFields();
	initFeeOptions();
	initWhatsAppContacts();
}());
