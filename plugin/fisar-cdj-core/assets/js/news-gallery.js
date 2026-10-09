/* The standard News inserter creates a native gallery with editable lightbox links. */
(function () {
	'use strict';

	wp.domReady(function () {
		var gallery = wp.blocks.getBlockType('core/gallery');
		if (!gallery) {
			return;
		}

		wp.blocks.registerBlockVariation('core/gallery', {
			name: 'fisar-cdj-news-gallery',
			title: gallery.title,
			description: wp.i18n.__('Galleria fotografica con ingrandimento al clic, disattivabile nelle opzioni del blocco.', 'fisar-cdj-core'),
			attributes: { linkTo: 'lightbox' },
			isDefault: true,
			isActive: ['linkTo'],
			scope: ['inserter']
		});
	});
}());
