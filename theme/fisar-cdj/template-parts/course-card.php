<?php
/**
 * Card corso; usa il post globale.
 */
$course_id = get_the_ID();
$is_home_card = isset( $args['context'] ) && 'home' === $args['context'];
$level     = (string) get_post_meta( $course_id, '_fisar_course_level', true );
$start     = (string) get_post_meta( $course_id, '_fisar_course_start_date', true );
$venue     = (string) get_post_meta( $course_id, '_fisar_course_venue', true );
$identity  = fisar_cdj_get_course_identity( $course_id );
$structured = ! $is_home_card && $identity['automatic'];
$card_title = $structured ? $identity['title'] : get_the_title();
$place     = $is_home_card || '' === trim( $venue ) ? $identity['city'] : $venue;
// In the structured card the city is already next to the level.
if ( $structured && '' === trim( $venue ) ) {
	$place = '';
}
$active    = function_exists( 'fisar_cdj_is_course_active' ) && fisar_cdj_is_course_active( $course_id );
$details = fisar_cdj_get_course_registration_details( $course_id );
$offer = fisar_cdj_get_course_offer( $course_id );
?>
<article <?php post_class( 'course-card' ); ?>>
	<div class="course-card__visual<?php echo $offer['active'] ? ' course-promotion' : ''; ?>">
		<a class="course-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php fisar_cdj_theme_post_image( $course_id, 'course-card__image' ); ?>
		</a>
		<?php if ( $active ) : ?><?php get_template_part( 'template-parts/event-sold-out', null, array( 'details' => $details ) ); ?><?php endif; ?>
		<?php get_template_part( 'template-parts/course-offer', null, array( 'offer' => $offer ) ); ?>
	</div>
	<div class="course-card__body">
		<div class="course-card__meta<?php echo $structured ? ' course-card__meta--structured' : ''; ?>">
			<?php if ( ! $structured && '' !== $level ) : ?><span class="eyebrow"><?php echo esc_html( $level ); ?>° livello</span><?php endif; ?>
			<span class="status <?php echo $active ? 'status--active' : 'status--past'; ?>"><?php echo $active ? 'Attivo' : 'Concluso'; ?></span>
		</div>
		<?php if ( 'closed' === $details['status'] ) : ?><p class="event-card__booking-status"><?php echo esc_html( $details['status_notice'] ); ?></p><?php endif; ?>
		<h3 class="course-card__title<?php echo $structured ? ' course-card__title--intro' : ''; ?>"><a href="<?php the_permalink(); ?>"<?php if ( $structured ) : ?> aria-label="<?php echo esc_attr( $card_title ); ?>"<?php endif; ?>><?php echo esc_html( $structured ? $identity['name'] : $card_title ); ?></a></h3>
		<?php if ( $structured && ( $identity['level'] || $identity['city'] ) ) : ?>
			<p class="course-card__identity">
				<?php if ( $identity['level'] ) : ?><strong class="course-card__level"><?php echo esc_html( $identity['level'] ); ?></strong><?php endif; ?>
				<?php if ( $identity['level'] && $identity['city'] ) : ?><span aria-hidden="true">·</span><?php endif; ?>
				<?php if ( $identity['city'] ) : ?><span class="course-card__locality"><?php echo esc_html( $identity['city_name'] ); ?><?php if ( $identity['province'] ) : ?> <span class="course-card__province">(<?php echo esc_html( $identity['province'] ); ?>)</span><?php endif; ?></span><?php endif; ?>
			</p>
		<?php endif; ?>
		<?php if ( $start ) : ?>
			<p class="course-card__dates event-card__date--compact">
				<?php echo fisar_cdj_theme_icon( 'calendar', 'event-card__date-icon' ); ?>
				<time datetime="<?php echo esc_attr( $start ); ?>">
					<span class="event-card__date-label" data-date-short="<?php echo esc_attr( fisar_cdj_theme_format_date_compact( $start ) ); ?>" aria-hidden="true"><?php echo esc_html( fisar_cdj_theme_format_date_compact( $start, false ) ); ?></span>
					<span class="screen-reader-text">Inizio: <?php echo esc_html( fisar_cdj_theme_format_date_with_day( $start ) ); ?></span>
				</time>
			</p>
		<?php endif; ?>
		<?php get_template_part( 'template-parts/card-place', null, array( 'place' => $place, 'class' => 'course-card__place' ) ); ?>
		<?php if ( ! $is_home_card ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
		<a class="text-link" href="<?php the_permalink(); ?>">Dettagli corso<span class="screen-reader-text">: <?php echo esc_html( $card_title ); ?></span> <span aria-hidden="true">→</span></a>
	</div>
</article>
