<?php
/**
 * Card corso; usa il post globale.
 */
$course_id = get_the_ID();
$is_home_card = isset( $args['context'] ) && 'home' === $args['context'];
$level     = (string) get_post_meta( $course_id, '_fisar_course_level', true );
$start     = (string) get_post_meta( $course_id, '_fisar_course_start_date', true );
$end       = (string) get_post_meta( $course_id, '_fisar_course_end_date', true );
$city      = (string) get_post_meta( $course_id, '_fisar_course_city', true );
$active    = function_exists( 'fisar_cdj_is_course_active' ) && fisar_cdj_is_course_active( $course_id );
$details = fisar_cdj_get_course_registration_details( $course_id );
?>
<article <?php post_class( 'course-card' ); ?>>
	<div class="course-card__visual">
		<a class="course-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php fisar_cdj_theme_post_image( $course_id, 'course-card__image' ); ?>
		</a>
		<?php if ( $active ) : ?><?php get_template_part( 'template-parts/event-sold-out', null, array( 'details' => $details ) ); ?><?php endif; ?>
	</div>
	<div class="course-card__body">
		<div class="course-card__meta">
			<?php if ( '' !== $level ) : ?><span class="eyebrow"><?php echo esc_html( $level ); ?>° livello</span><?php endif; ?>
			<span class="status <?php echo $active ? 'status--active' : 'status--past'; ?>"><?php echo $active ? 'Attivo' : 'Concluso'; ?></span>
		</div>
		<?php if ( 'closed' === $details['status'] ) : ?><p class="event-card__booking-status"><?php echo esc_html( $details['status_notice'] ); ?></p><?php endif; ?>
		<h3 class="course-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ( $start ) : ?>
			<p class="course-card__dates event-card__date--compact">
				<?php echo fisar_cdj_theme_icon( 'calendar', 'event-card__date-icon' ); ?>
				<time datetime="<?php echo esc_attr( $start ); ?>">
					<span class="event-card__date-label" data-date-short="<?php echo esc_attr( fisar_cdj_theme_format_date_compact( $start ) ); ?>" aria-hidden="true"><?php echo esc_html( fisar_cdj_theme_format_date_compact( $start, false ) ); ?></span>
					<span class="screen-reader-text">Inizio: <?php echo esc_html( fisar_cdj_theme_format_date_with_day( $start ) ); ?></span>
				</time>
			</p>
		<?php endif; ?>
		<?php if ( ! $is_home_card && $end ) : ?><p class="course-card__end">Fino al <?php echo esc_html( fisar_cdj_theme_format_date( $end ) ); ?></p><?php endif; ?>
		<?php if ( '' !== $city ) : ?><p class="course-card__place"><?php echo esc_html( $city ); ?></p><?php endif; ?>
		<?php if ( ! $is_home_card ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
		<a class="text-link" href="<?php the_permalink(); ?>">Dettagli corso<span class="screen-reader-text">: <?php echo esc_html( get_the_title() ); ?></span> <span aria-hidden="true">→</span></a>
	</div>
</article>
