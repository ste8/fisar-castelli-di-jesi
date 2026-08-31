<?php
/**
 * Card corso; usa il post globale.
 */
$course_id = get_the_ID();
$level     = (string) get_post_meta( $course_id, '_fisar_course_level', true );
$start     = (string) get_post_meta( $course_id, '_fisar_course_start_date', true );
$end       = (string) get_post_meta( $course_id, '_fisar_course_end_date', true );
$city      = (string) get_post_meta( $course_id, '_fisar_course_city', true );
$active    = function_exists( 'fisar_cdj_is_course_active' ) && fisar_cdj_is_course_active( $course_id );
?>
<article <?php post_class( 'course-card' ); ?>>
	<a class="course-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php fisar_cdj_theme_post_image( $course_id, 'course-card__image' ); ?>
	</a>
	<div class="course-card__body">
		<div class="course-card__meta">
			<?php if ( '' !== $level ) : ?><span class="eyebrow"><?php echo esc_html( $level ); ?>° livello</span><?php endif; ?>
			<span class="status <?php echo $active ? 'status--active' : 'status--past'; ?>"><?php echo $active ? 'Attivo' : 'Concluso'; ?></span>
		</div>
		<h3 class="course-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ( $start && $end ) : ?><p class="course-card__dates"><?php echo esc_html( fisar_cdj_theme_format_date( $start ) ); ?> – <?php echo esc_html( fisar_cdj_theme_format_date( $end ) ); ?></p><?php endif; ?>
		<?php if ( '' !== $city ) : ?><p class="course-card__place"><?php echo esc_html( $city ); ?></p><?php endif; ?>
		<p><?php echo esc_html( get_the_excerpt() ); ?></p>
		<a class="text-link" href="<?php the_permalink(); ?>">Dettagli del corso <span aria-hidden="true">→</span></a>
	</div>
</article>

