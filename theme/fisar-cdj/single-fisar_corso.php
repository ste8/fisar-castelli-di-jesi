<?php
get_header();
the_post();

$course_id  = get_the_ID();
$level      = (string) get_post_meta( $course_id, '_fisar_course_level', true );
$director   = (string) get_post_meta( $course_id, '_fisar_course_director', true );
$start      = (string) get_post_meta( $course_id, '_fisar_course_start_date', true );
$end        = (string) get_post_meta( $course_id, '_fisar_course_end_date', true );
$venue      = (string) get_post_meta( $course_id, '_fisar_course_venue', true );
$address    = (string) get_post_meta( $course_id, '_fisar_course_address', true );
$city       = (string) get_post_meta( $course_id, '_fisar_course_city', true );
$province   = (string) get_post_meta( $course_id, '_fisar_course_province', true );
$active     = fisar_cdj_is_course_active( $course_id );
$calendar   = fisar_cdj_get_course_calendar( $course_id );
$events     = fisar_cdj_get_course_events( $course_id );
$details    = fisar_cdj_get_course_registration_details( $course_id );
$location   = array(
	'mode'     => 'presence',
	'venue'    => $venue,
	'address'  => $address,
	'city'     => $city ? trim( $city . ( $province ? ' (' . $province . ')' : '' ) ) : '',
	'maps_url' => esc_url_raw( (string) get_post_meta( $course_id, '_fisar_course_maps_url', true ), array( 'http', 'https' ) ),
);
$has_location = $venue || $address || $location['city'] || $location['maps_url'];
$registration = array(
	'active'     => $active,
	'details'    => $details,
	'channels'   => fisar_cdj_get_registration_channels( $course_id, 'course' ),
	'fee'        => (string) get_post_meta( $course_id, '_fisar_course_fee', true ),
	'membership' => (string) get_post_meta( $course_id, '_fisar_course_membership', true ),
	'includes'   => (string) get_post_meta( $course_id, '_fisar_course_includes', true ),
	'notes'      => (string) get_post_meta( $course_id, '_fisar_course_registration_notes', true ),
);
$registration_link_label = $details['closed'] ? 'Informazioni sulle iscrizioni' : ( $details['waiting_list'] ? 'Lista d’attesa' : 'Informazioni e iscrizioni' );
?>
<main id="main-content">
	<article <?php post_class( 'single-course' ); ?>>
		<header class="content-hero">
			<div class="container content-hero__grid">
				<div class="content-hero__copy">
					<a class="back-link" href="<?php echo esc_url( fisar_cdj_theme_archive_url( Fisar_CDJ_Post_Types::COURSE, 'corsi' ) ); ?>"><span aria-hidden="true">←</span> Tutti i corsi</a>
					<p class="eyebrow"><?php echo $level ? esc_html( $level ) . '° livello' : 'Formazione FISAR'; ?> · <?php echo $active ? 'Corso attivo' : 'Corso concluso'; ?></p>
					<h1><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?><p class="content-hero__lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
					<ul class="hero-facts">
						<?php if ( $start ) : ?><li><strong>Inizio</strong><span><?php echo esc_html( fisar_cdj_theme_format_date_with_day( $start ) ); ?></span></li><?php endif; ?>
						<?php if ( $end ) : ?><li><strong>Fine</strong><span><?php echo esc_html( fisar_cdj_theme_format_date_with_day( $end ) ); ?></span></li><?php endif; ?>
						<?php if ( $has_location ) : ?><li><strong>Luogo</strong><?php get_template_part( 'template-parts/event-location', null, array( 'location' => $location ) ); ?></li><?php endif; ?>
						<?php if ( $director ) : ?><li><strong>Direttore</strong><span><?php echo esc_html( $director ); ?></span></li><?php endif; ?>
						<?php if ( $active && ( $details['deadline'] || $details['status_notice'] || $details['limited_seats_notice'] ) ) : ?>
							<li class="event-deadline-row">
								<?php get_template_part( 'template-parts/event-deadline', null, array( 'details' => $details, 'summary' => true, 'label' => 'Iscrizioni entro', 'past_label' => 'Termine iscrizioni' ) ); ?>
								<?php get_template_part( 'template-parts/event-limited-seats', null, array( 'details' => $details ) ); ?>
								<a class="button event-registration-link" href="#course-registration"><?php echo esc_html( $registration_link_label ); ?> <span aria-hidden="true">↓</span></a>
							</li>
						<?php endif; ?>
					</ul>
					<?php if ( $active && ! $details['deadline'] && ! $details['status_notice'] && ! $details['limited_seats_notice'] ) : ?><a class="button event-registration-link" href="#course-registration"><?php echo esc_html( $registration_link_label ); ?> <span aria-hidden="true">↓</span></a><?php endif; ?>
				</div>
				<div class="event-poster">
					<div class="content-hero__media content-hero__media--poster"><?php fisar_cdj_theme_post_image( $course_id, 'single-course-image', true, 'eager' ); ?></div>
					<?php if ( $active ) : ?><?php get_template_part( 'template-parts/event-sold-out', null, array( 'details' => $details ) ); ?><?php endif; ?>
				</div>
			</div>
		</header>

		<div class="container content-layout">
			<div class="prose">
				<?php the_content(); ?>

				<?php if ( $calendar ) : ?>
					<section aria-labelledby="calendar-title">
						<h2 id="calendar-title">Calendario lezioni</h2>
						<div class="table-scroll" tabindex="0" role="region" aria-label="Calendario lezioni, scorrimento orizzontale su schermi piccoli">
							<table class="lesson-calendar">
								<thead><tr><th scope="col">Giorno</th><th scope="col">Data</th><th scope="col">Orario</th><th scope="col">Lezione</th><th scope="col">Relatore</th><th scope="col">Note</th></tr></thead>
								<tbody>
								<?php foreach ( $calendar as $lesson ) : ?>
									<tr><th scope="row"><?php echo esc_html( wp_date( 'l', strtotime( $lesson['date'] ) ) ); ?></th><td><time datetime="<?php echo esc_attr( $lesson['date'] ); ?>"><?php echo esc_html( fisar_cdj_theme_format_date( $lesson['date'] ) ); ?></time></td><td><?php echo esc_html( $lesson['time'] ); ?></td><td><?php echo esc_html( $lesson['title'] ); ?></td><td><?php echo esc_html( $lesson['speaker'] ?: 'Da definire' ); ?></td><td><?php echo esc_html( $lesson['notes'] ); ?></td></tr>
								<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						<p class="calendar-note"><?php echo esc_html( Fisar_CDJ_Calendar_Importer::NOTICE ); ?></p>
					</section>
				<?php endif; ?>

				<?php if ( $events->have_posts() ) : ?>
					<section aria-labelledby="course-events-title">
						<h2 id="course-events-title">Eventi collegati al corso</h2>
						<ul class="related-events">
							<?php while ( $events->have_posts() ) : $events->the_post(); $event_date = (string) get_post_meta( get_the_ID(), '_fisar_event_date', true ); ?>
								<li><time datetime="<?php echo esc_attr( $event_date ); ?>"><?php echo esc_html( fisar_cdj_theme_format_date( $event_date ) ); ?></time><a href="<?php the_permalink(); ?>"><?php the_title(); ?><?php echo fisar_cdj_is_event_past( get_the_ID() ) ? ' (concluso)' : ''; ?></a></li>
							<?php endwhile; wp_reset_postdata(); ?>
						</ul>
					</section>
				<?php endif; ?>
				<?php get_template_part( 'template-parts/course-registration', null, array( 'registration' => $registration ) ); ?>
			</div>
		</div>
	</article>
</main>
<?php get_footer(); ?>
