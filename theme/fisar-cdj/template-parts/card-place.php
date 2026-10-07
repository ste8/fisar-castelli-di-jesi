<?php
/** Shared card location; the pin is decorative, not an accessible name. */
$place = trim( (string) ( $args['place'] ?? '' ) );
if ( '' === $place ) {
	return;
}
$show_pin = $args['show_pin'] ?? true;
?>
<p class="card-place <?php echo esc_attr( $args['class'] ?? '' ); ?>">
	<?php if ( $show_pin ) : ?><span class="card-place__pin" aria-hidden="true">📍</span><?php endif; ?>
	<span><?php echo esc_html( $place ); ?></span>
</p>
