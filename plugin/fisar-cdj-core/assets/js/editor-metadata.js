/* Reuse WordPress controls and its edited-post store; no duplicate metadata or save requests. */
(function () {
	'use strict';
	wp.domReady(function () {
		var roots = [];
		var actions = wp.data.dispatch('core/editor');
		var cover = document.querySelector('[data-fisar-editor-cover]');
		var categories = document.querySelector('[data-fisar-editor-categories]');
		if (cover && wp.editor.PostFeaturedImage) {
			var coverRoot = wp.element.createRoot(cover);
			coverRoot.render(wp.element.createElement(wp.editor.PostFeaturedImage));
			roots.push(coverRoot);
			actions.removeEditorPanel('featured-image');
		}
		if (categories && wp.editor.PostTaxonomies) {
			var categoryRoot = wp.element.createRoot(categories);
			categoryRoot.render(wp.element.createElement(wp.editor.PostTaxonomies, {
				taxonomyWrapper: function (content, taxonomy) {
					return taxonomy.slug === 'category' ? content : null;
				}
			}));
			roots.push(categoryRoot);
			actions.removeEditorPanel('taxonomy-panel-category');
		}
		window.addEventListener('pagehide', function () {
			roots.forEach(function (root) { root.unmount(); });
		}, { once: true });
	});
}());
