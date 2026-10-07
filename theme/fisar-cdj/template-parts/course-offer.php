<?php
/** Promotion below the course image, never an overlay or registration deadline. */
$offer = $args['offer'];
if ( ! $offer['active'] ) {
	return;
}
?>
<p class="course-offer">
	<strong>In offerta</strong>
	<?php if ( $offer['end_date'] ) : ?><span>Fino al <time datetime="<?php echo esc_attr( $offer['end_date'] ); ?>"><?php echo esc_html( fisar_cdj_theme_format_date( $offer['end_date'] ) ); ?></time></span><?php endif; ?>
</p>
