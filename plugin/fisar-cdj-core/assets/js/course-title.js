/* Keep automatic titles in the native WordPress title, not a second content field. */
(function () {
	'use strict';
	wp.domReady(function () {
		var settings = document.getElementById('fisar-course-title-settings');
		if (!settings) { return; }
		var mode = document.getElementById('_fisar_course_title_mode');
		var preview = document.getElementById('fisar-course-title-preview');
		var fields = ['level', 'city', 'province'].map(function (name) {
			return document.getElementById('_fisar_course_' + name);
		});
		if (!mode || !preview || fields.some(function (field) { return !field; })) { return; }
		function compose() {
			var level = fields[0].value;
			var city = fields[1].value.trim().replace(/\s+/g, ' ');
			var province = fields[2].value.replace(/[^a-z]/gi, '').slice(0, 2).toUpperCase();
			return 'Corso Sommelier' + (/^[123]$/.test(level) ? ' ' + level + '° livello' : '') +
				(city ? ' – ' + city + (province ? ' (' + province + ')' : '') : '');
		}
		function update(syncTitle) {
			var title = compose();
			preview.textContent = title;
			if (!syncTitle || mode.value !== 'automatic') { return; }
			if (settings.dataset.editor === 'block') {
				var editor = wp.data.select('core/editor');
				if (editor.getEditedPostAttribute('title') !== title) {
					wp.data.dispatch('core/editor').editPost({ title: title });
				}
			} else {
				var input = document.getElementById('title');
				if (input) {
					input.value = title;
					input.dispatchEvent(new Event('input', { bubbles: true }));
				}
			}
		}
		mode.addEventListener('change', function () { update(true); });
		fields.forEach(function (field) {
			field.addEventListener('input', function () { update(true); });
			field.addEventListener('change', function () { update(true); });
		});
		// Visiting an existing editor must not dirty or autosave its original title.
		update(false);
	});
}());
