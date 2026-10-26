<?php
/** Meeting time, place and travel instructions shared by both event summaries. */
$meeting = $args['meeting'];
?>
<div class="event-meeting">
	<?php if ( $meeting['time'] ) : ?>
		<span class="event-meeting__time">Ore <time datetime="<?php echo esc_attr( $meeting['time'] ); ?>"><?php echo esc_html( $meeting['time'] ); ?></time></span>
	<?php endif; ?>
	<?php if ( $meeting['has_location'] ) : ?>
		<?php get_template_part( 'template-parts/event-location', null, array( 'location' => array( 'mode' => 'presence', 'venue' => $meeting['venue'], 'address' => $meeting['address'], 'city' => $meeting['city'], 'maps_url' => $meeting['maps_url'] ) ) ); ?>
	<?php endif; ?>
	<?php if ( $meeting['notes'] ) : ?>
		<p class="event-meeting__notes"><?php echo nl2br( esc_html( $meeting['notes'] ) ); ?></p>
	<?php endif; ?>
</div>
