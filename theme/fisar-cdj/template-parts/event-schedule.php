<?php
/**
 * One shared presentation of event times for the summary and practical details.
 * Missing optional times are omitted by the calling template.
 */
$schedule = $args['schedule'] ?? array();
?>
<div class="event-schedule">
	<?php foreach ( $schedule as $label => $time ) : ?>
		<div class="event-schedule__item">
			<span class="event-schedule__label"><?php echo esc_html( $label ); ?></span>
			<time class="event-schedule__time" datetime="<?php echo esc_attr( $time ); ?>"><?php echo esc_html( $time ); ?></time>
		</div>
	<?php endforeach; ?>
</div>
