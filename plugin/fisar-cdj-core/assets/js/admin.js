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

	function looksLikeHeader(columns) {
		var first = (columns[0] || '').trim().toLowerCase();
		return ['data', 'data lezione', 'giorno'].indexOf(first) !== -1;
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
}());

