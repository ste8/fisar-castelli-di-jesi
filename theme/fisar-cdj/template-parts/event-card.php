<?php
/**
 * Card evento; usa il post globale.
 */
$event_id = get_the_ID();
$is_home_card = isset( $args['context'] ) && 'home' === $args['context'];
$has_compact_date = $is_home_card || ( isset( $args['context'] ) && 'archive' === $args['context'] );
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
		<?php if ( $has_compact_date ) : ?>
			<p class="event-card__date event-card__date--compact">
				<?php echo fisar_cdj_theme_icon( 'calendar', 'event-card__date-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<time datetime="<?php echo esc_attr( $date ); ?>">
					<span class="event-card__date-label" data-date-short="<?php echo esc_attr( fisar_cdj_theme_format_date_compact( $date ) ); ?>" aria-hidden="true"><?php echo esc_html( fisar_cdj_theme_format_date_compact( $date, false ) ); ?></span>
					<span class="screen-reader-text"><?php echo esc_html( fisar_cdj_theme_format_date_with_day( $date ) ); ?></span>
				</time>
			</p>
		<?php else : ?>
			<p class="event-card__date"><time datetime="<?php echo esc_attr( $date ); ?>"><?php echo esc_html( fisar_cdj_theme_format_date_with_day( $date ) ); ?></time></p>
		<?php endif; ?>
		<h3 class="event-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ( '' !== $place ) : ?><p class="event-card__place"><?php echo esc_html( $place ); ?></p><?php endif; ?>
		<?php if ( ! $is_home_card && has_excerpt() ) : ?><p class="event-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
		<a class="text-link" href="<?php the_permalink(); ?>">Dettagli evento<span class="screen-reader-text">: <?php echo esc_html( get_the_title() ); ?></span> <span aria-hidden="true">→</span></a>
	</div>
</article>
