<?php
/** The same place description in the hero and practical information. */
$location = $args['location'];
?>
<span class="event-location">
	<?php if ( 'online' === $location['mode'] ) : ?>
		<strong class="event-location__name">Online</strong>
	<?php else : ?>
		<?php if ( 'hybrid' === $location['mode'] ) : ?>
			<span class="event-location__hybrid">
				<strong>Evento ibrido</strong>
				<span>In presenza e online</span>
			</span>
		<?php endif; ?>
		<?php if ( $location['venue'] ) : ?><strong class="event-location__name"><?php echo esc_html( $location['venue'] ); ?></strong><?php endif; ?>
		<?php if ( $location['address'] ) : ?><span><?php echo esc_html( $location['address'] ); ?></span><?php endif; ?>
		<?php if ( $location['city'] ) : ?><span><?php echo esc_html( $location['city'] ); ?></span><?php endif; ?>
		<?php get_template_part( 'template-parts/event-map-link', null, array( 'url' => $location['maps_url'] ) ); ?>
	<?php endif; ?>
</span>
