<?php
/** Decorative progression, inserted beside the existing editable training content. */
function fisar_cdj_theme_training_journey( string $content, array $block ): string {
	$classes = preg_split( '/\s+/', $block['attrs']['className'] ?? '' );
	if ( ! is_page( 'diventare-sommelier' ) || ! in_the_loop() || ! is_main_query() || ! in_array( 'training-levels', $classes, true ) ) {
		return $content;
	}

	ob_start();
	get_template_part( 'template-parts/training-journey' );
	return ob_get_clean() . $content;
}
add_filter( 'render_block_core/list', 'fisar_cdj_theme_training_journey', 10, 2 );
