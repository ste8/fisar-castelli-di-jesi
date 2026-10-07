/* Keep the main details field connected to WordPress's native excerpt. */
(function () {
	'use strict';

	wp.domReady(function () {
		var textarea = document.getElementById('excerpt');
		if (!textarea) {
			return;
		}

		var editor = wp.data.select('core/editor');
		var actions = wp.data.dispatch('core/editor');

		function syncFromEditor() {
			var excerpt = editor.getEditedPostAttribute('excerpt');
			// The post may not be initialized yet; never replace existing text with undefined.
			if (typeof excerpt === 'string' && textarea.value !== excerpt) {
				textarea.value = excerpt;
			}
		}

		actions.removeEditorPanel('post-excerpt');
		textarea.addEventListener('input', function () {
			actions.editPost({ excerpt: textarea.value });
		});
		var unsubscribe = wp.data.subscribe(syncFromEditor);
		syncFromEditor();
		window.addEventListener('pagehide', unsubscribe, { once: true });
	});
}());
