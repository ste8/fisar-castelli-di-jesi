<?php
/**
 * Card evento; usa il post globale.
 */
$event_id = get_the_ID();
$date     = (string) get_post_meta( $event_id, '_fisar_event_date', true );
$venue    = (string) get_post_meta( $event_id, '_fisar_event_venue', true );
$city     = (string) get_post_meta( $event_id, '_fisar_event_city', true );
$mode     = (string) get_post_meta( $event_id, '_fisar_event_mode', true );
$place    = implode( ', ', array_filter( array( $venue, $city ) ) );
if ( 'online' === $mode ) {
	$place = 'Online';
} elseif ( 'hybrid' === $mode && '' !== $place ) {
	$place .= ' + online';
}
?>
<article <?php post_class( 'event-card' ); ?>>
	<a class="event-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php fisar_cdj_theme_post_image( $event_id, 'event-card__image' ); ?>
	</a>
	<div class="event-card__body">
		<p class="event-card__date"><time datetime="<?php echo esc_attr( $date ); ?>"><?php echo esc_html( fisar_cdj_theme_format_date_with_day( $date ) ); ?></time></p>
		<h3 class="event-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ( '' !== $place ) : ?><p class="event-card__place"><?php echo esc_html( $place ); ?></p><?php endif; ?>
		<?php if ( has_excerpt() ) : ?><p class="event-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
		<a class="text-link" href="<?php the_permalink(); ?>">Dettagli dell’evento <span aria-hidden="true">→</span></a>
	</div>
</article>

