<?php
$policy = $args['policy'] ?? array();
if ( empty( $policy['lines'] ) && empty( $policy['text'] ) ) { return; }
?>
<section class="event-cancellation" aria-labelledby="event-cancellation-title">
	<h3 id="event-cancellation-title">Cancellazioni e rimborsi</h3>
	<?php if ( ! empty( $policy['lines'] ) ) : ?>
		<ul><?php foreach ( $policy['lines'] as $line ) : ?><li><?php echo esc_html( $line ); ?></li><?php endforeach; ?></ul>
	<?php endif; ?>
	<?php if ( ! empty( $policy['text'] ) ) : ?><div><?php echo wp_kses_post( $policy['text'] ); ?></div><?php endif; ?>
	<?php if ( ! empty( $policy['notes'] ) ) : ?><div><?php echo wp_kses_post( $policy['notes'] ); ?></div><?php endif; ?>
</section>
