(function () {
	'use strict';

	var dates = [];
	var observer;
	function collectDates() {
		if (observer) observer.disconnect();
		dates = Array.from(document.querySelectorAll('.event-card__date--compact')).map(function (row) {
			var label = row.querySelector('.event-card__date-label');
			label.dataset.dateFull = label.dataset.dateFull || label.textContent;
			if (observer) observer.observe(row);
			return { row: row, time: row.querySelector('.event-card__date-value') || row.querySelector('time'), label: label, full: label.dataset.dateFull, short: label.dataset.dateShort };
		});
	}

	function fitDate(date) {
		// Measure the full month in the actual font, including the calendar's space.
		date.row.classList.add('has-responsive-date');
		date.label.textContent = date.full;
		var availableWidth = date.time.getBoundingClientRect().width;
		if (!availableWidth) {
			date.row.classList.remove('has-responsive-date');
			return;
		}
		if (date.label.getBoundingClientRect().width > availableWidth) {
			date.label.textContent = date.short;
			// At extreme zoom, allow wrapping instead of clipping even the short date.
			if (date.label.getBoundingClientRect().width > availableWidth) {
				date.row.classList.remove('has-responsive-date');
			}
		}
	}

	var updateScheduled = false;
	function scheduleUpdate() {
		if (updateScheduled) {
			return;
		}
		updateScheduled = true;
		window.requestAnimationFrame(function () {
			updateScheduled = false;
			dates.forEach(fitDate);
		});
	}

	if ('ResizeObserver' in window) {
		observer = new ResizeObserver(scheduleUpdate);
	}
	collectDates();
	window.addEventListener('fisar:archive-updated', function () { collectDates(); scheduleUpdate(); });
	window.addEventListener('resize', scheduleUpdate);
	if (document.fonts) {
		document.fonts.ready.then(scheduleUpdate);
		document.fonts.addEventListener('loadingdone', scheduleUpdate);
	}
	scheduleUpdate();
}());
