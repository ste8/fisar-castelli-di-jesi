<?php
/** The native Home gallery supplies selection/order; six photographs form a slide. */
$images = array_values( array_filter( $args['gallery']['innerBlocks'] ?? array(), static function ( array $block ): bool {
	return 'core/image' === $block['blockName'] && wp_attachment_is_image( (int) ( $block['attrs']['id'] ?? 0 ) );
} ) );
if ( ! $images ) {
	return;
}
$slides = array_chunk( $images, 6 );
?>
<div class="hero-collage" data-hero-carousel role="group" aria-label="Momenti della Delegazione">
	<?php foreach ( $slides as $position => $slide ) : ?>
		<div class="hero-collage__slide<?php echo 0 === $position ? ' is-active' : ''; ?>" aria-hidden="<?php echo 0 === $position ? 'false' : 'true'; ?>">
			<div class="hero-collage__panel">
				<?php foreach ( $slide as $block ) : ?>
					<?php
					$focus = '50% 50%';
					if ( preg_match( '/(?:^|\s)hero-focus-(\d{1,3})-(\d{1,3})(?:\s|$)/', $block['attrs']['className'] ?? '', $matches ) ) {
						$focus = min( 100, (int) $matches[1] ) . '% ' . min( 100, (int) $matches[2] ) . '%';
					}
					?>
					<figure class="wp-block-image" style="--photo-focus:<?php echo esc_attr( $focus ); ?>">
						<?php echo wp_get_attachment_image( (int) $block['attrs']['id'], 'full', false, array( 'sizes' => '(min-width: 81rem) 25rem, 30vw', 'loading' => 0 === $position ? 'eager' : 'lazy', 'decoding' => 'async' ) ); ?>
					</figure>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
<?php if ( count( $slides ) > 1 ) : ?>
	<div class="hero-collage__controls" hidden role="group" aria-label="Controlli dell’album">
		<div class="hero-collage__dots" role="group" aria-label="Scegli il collage">
			<?php foreach ( $slides as $position => $slide ) : ?>
				<button type="button" data-collage="<?php echo (int) $position; ?>" aria-label="<?php echo esc_attr( sprintf( 'Mostra collage %1$d di %2$d', $position + 1, count( $slides ) ) ); ?>" aria-current="<?php echo 0 === $position ? 'true' : 'false'; ?>" title="<?php echo esc_attr( sprintf( 'Collage %1$d di %2$d', $position + 1, count( $slides ) ) ); ?>"></button>
			<?php endforeach; ?>
		</div>
		<button type="button" data-pause aria-label="Metti in pausa il cambio dei collage"></button>
		<span class="screen-reader-text" role="status"></span>
	</div>
<?php endif; ?>
