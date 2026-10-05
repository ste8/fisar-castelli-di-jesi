<?php
/** Fees in the booking panel, or the practical summary of a past event. */
$fees = $args['fees'];
?>
<span class="event-fees">
	<?php if ( $fees['free'] ) : ?>
		<strong>Gratuito</strong>
	<?php else : ?>
		<?php if ( '' !== ( $fees['note'] ?? '' ) ) : ?><span class="event-fees__note"><?php echo esc_html( $fees['note'] ); ?></span><?php endif; ?>
		<?php if ( '' !== $fees['member'] ) : ?><span class="event-fees__row"><span>Soci</span> <strong><?php echo esc_html( fisar_cdj_theme_format_event_fee( $fees['member'] ) ); ?></strong></span><?php endif; ?>
		<?php if ( '' !== $fees['nonmember'] ) : ?><span class="event-fees__row"><span>Non soci</span> <strong><?php echo esc_html( fisar_cdj_theme_format_event_fee( $fees['nonmember'] ) ); ?></strong></span><?php endif; ?>
		<?php foreach ( $fees['options'] ?? array() as $option ) : ?>
			<span class="event-fees__row event-fees__row--custom">
				<span><?php echo esc_html( $option['label'] ); ?></span> <strong><?php echo esc_html( fisar_cdj_theme_format_event_fee( $option['amount'] ) ); ?></strong>
				<?php if ( '' !== $option['note'] ) : ?><span class="event-fees__note"><?php echo esc_html( $option['note'] ); ?></span><?php endif; ?>
			</span>
		<?php endforeach; ?>
	<?php endif; ?>
</span>
