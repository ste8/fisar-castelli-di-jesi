<?php
/** Same fee values in the practical summary and both registration panels. */
$fees = $args['fees'];
?>
<span class="event-fees">
	<?php if ( $fees['free'] ) : ?>
		<strong>Gratuito</strong>
	<?php else : ?>
		<?php if ( '' !== $fees['member'] ) : ?><span><span>Soci</span> <strong><?php echo esc_html( $fees['member'] ); ?></strong></span><?php endif; ?>
		<?php if ( '' !== $fees['nonmember'] ) : ?><span><span>Non soci</span> <strong><?php echo esc_html( $fees['nonmember'] ); ?></strong></span><?php endif; ?>
	<?php endif; ?>
</span>
