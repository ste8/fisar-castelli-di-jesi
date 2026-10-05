(function () {
	'use strict';

	var dates = Array.from(document.querySelectorAll('.event-card__date--compact')).map(function (row) {
		var label = row.querySelector('.event-card__date-label');
		return {
			row: row,
			time: row.querySelector('time'),
			label: label,
			full: label.textContent,
			short: label.dataset.dateShort
		};
	});
	if (!dates.length) {
		return;
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
		var observer = new ResizeObserver(scheduleUpdate);
		dates.forEach(function (date) {
			observer.observe(date.row);
		});
	}
	window.addEventListener('resize', scheduleUpdate);
	if (document.fonts) {
		document.fonts.ready.then(scheduleUpdate);
		document.fonts.addEventListener('loadingdone', scheduleUpdate);
	}
	scheduleUpdate();
}());
