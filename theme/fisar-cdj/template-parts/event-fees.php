<?php
/** Fees in the booking panel, or the practical summary of a past event. */
$fees = $args['fees'];
?>
<span class="event-fees">
	<?php if ( $fees['free'] ) : ?>
		<strong>Gratuito</strong>
	<?php else : ?>
		<?php if ( '' !== $fees['member'] ) : ?><span><span>Soci</span> <strong><?php echo esc_html( fisar_cdj_theme_format_event_fee( $fees['member'] ) ); ?></strong></span><?php endif; ?>
		<?php if ( '' !== $fees['nonmember'] ) : ?><span><span>Non soci</span> <strong><?php echo esc_html( fisar_cdj_theme_format_event_fee( $fees['nonmember'] ) ); ?></strong></span><?php endif; ?>
	<?php endif; ?>
</span>
