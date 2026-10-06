<?php
get_header();
the_post();

$event_id      = get_the_ID();
$date          = (string) get_post_meta( $event_id, '_fisar_event_date', true );
$welcome_time  = (string) get_post_meta( $event_id, '_fisar_event_welcome_time', true );
$start_time    = (string) get_post_meta( $event_id, '_fisar_event_start_time', true );
$end_time      = (string) get_post_meta( $event_id, '_fisar_event_end_time', true );
$mode          = (string) get_post_meta( $event_id, '_fisar_event_mode', true );
$venue         = (string) get_post_meta( $event_id, '_fisar_event_venue', true );
$address       = (string) get_post_meta( $event_id, '_fisar_event_address', true );
$city          = (string) get_post_meta( $event_id, '_fisar_event_city', true );
$province      = (string) get_post_meta( $event_id, '_fisar_event_province', true );
$maps_url      = 'online' !== $mode ? esc_url_raw( (string) get_post_meta( $event_id, '_fisar_event_maps_url', true ), array( 'http', 'https' ) ) : '';
$online_access = fisar_cdj_get_event_online_access( $event_id );
$participation = (string) get_post_meta( $event_id, '_fisar_event_participation', true );
$required      = (bool) get_post_meta( $event_id, '_fisar_event_registration_required', true );
$course_id     = absint( get_post_meta( $event_id, '_fisar_event_course_id', true ) );
$past          = fisar_cdj_is_event_past( $event_id );

$participation_labels = array( 'all' => 'Aperto a tutti', 'members' => 'Riservato ai soci', 'members_and_companions' => 'Soci e accompagnatori' );
$schedule = array_filter( array( 'Accoglienza' => $welcome_time, 'Inizio' => $start_time, 'Fine' => $end_time ) );
$city_label = $city ? trim( $city . ( $province ? ' (' . $province . ')' : '' ) ) : '';
$has_location = $venue || $address || $city_label || $maps_url || in_array( $mode, array( 'online', 'hybrid' ), true );
$location = array( 'mode' => $mode, 'venue' => $venue, 'address' => $address, 'city' => $city_label, 'maps_url' => $maps_url );
$fees = fisar_cdj_get_event_fees( $event_id );
$registration = array(
	'past'     => $past,
	'required' => $required,
	'fees'     => $fees,
	'has_fees' => $fees['has_fees'],
	'details'  => fisar_cdj_get_event_registration_details( $event_id ),
	'channels' => ! $past && $required ? fisar_cdj_get_registration_channels( $event_id, 'event' ) : array(),
	'notes'    => (string) get_post_meta( $event_id, '_fisar_event_registration_notes', true ),
);
$deadline = $registration['details'];
$registration_link_label = $deadline['closed'] ? 'Informazioni sulle iscrizioni' : ( $required ? 'Come prenotare' : 'Come partecipare' );
if ( $deadline['waiting_list'] ) {
	$registration_link_label = 'Lista d’attesa';
}
?>
<main id="main-content">
	<article <?php post_class( 'single-event' ); ?>>
		<header class="content-hero">
			<div class="container content-hero__grid">
				<div class="content-hero__copy">
					<a class="back-link" href="<?php echo esc_url( fisar_cdj_theme_archive_url( Fisar_CDJ_Post_Types::EVENT, 'eventi' ) ); ?>"><span aria-hidden="true">←</span> Tutti gli eventi</a>
					<p class="eyebrow"><?php echo $past ? 'Evento concluso' : 'Evento in programma'; ?></p>
					<h1><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?><p class="content-hero__lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
					<ul class="hero-facts">
						<li><strong>Data</strong><span><?php echo esc_html( fisar_cdj_theme_format_date_with_day( $date ) ); ?></span></li>
						<?php if ( $schedule ) : ?>
							<li class="event-schedule-row"><strong>Orario</strong><?php get_template_part( 'template-parts/event-schedule', null, array( 'schedule' => $schedule ) ); ?></li>
						<?php endif; ?>
						<?php if ( $has_location ) : ?>
							<li>
								<strong>Luogo</strong>
								<?php get_template_part( 'template-parts/event-location', null, array( 'location' => $location ) ); ?>
							</li>
						<?php endif; ?>
						<?php if ( ! $past && ( $deadline['deadline'] || $deadline['limited_seats_notice'] || $deadline['status_notice'] ) ) : ?>
							<li class="event-deadline-row">
								<?php get_template_part( 'template-parts/event-deadline', null, array( 'details' => $deadline, 'summary' => true ) ); ?>
								<?php get_template_part( 'template-parts/event-limited-seats', null, array( 'details' => $deadline ) ); ?>
								<a class="button event-registration-link" href="#event-registration"><?php echo esc_html( $registration_link_label ); ?> <span aria-hidden="true">↓</span></a>
							</li>
						<?php endif; ?>
					</ul>
					<?php if ( ! $past && ! $deadline['deadline'] && ! $deadline['limited_seats_notice'] && ! $deadline['status_notice'] ) : ?>
						<a class="button event-registration-link" href="#event-registration"><?php echo esc_html( $registration_link_label ); ?> <span aria-hidden="true">↓</span></a>
					<?php endif; ?>
				</div>
				<div class="event-poster">
					<div class="content-hero__media content-hero__media--poster">
						<?php fisar_cdj_theme_post_image( $event_id, 'single-poster', true, 'eager' ); ?>
					</div>
					<?php if ( ! $past ) : ?>
						<?php get_template_part( 'template-parts/event-sold-out', null, array( 'details' => $deadline ) ); ?>
					<?php endif; ?>
				</div>
			</div>
		</header>

		<div class="container content-layout">
			<div class="prose">
				<?php the_content(); ?>

				<section aria-labelledby="event-details-title">
					<h2 id="event-details-title">Informazioni pratiche</h2>
					<dl class="details-list">
						<div><dt>Data</dt><dd><?php echo esc_html( fisar_cdj_theme_format_date_with_day( $date ) ); ?></dd></div>
						<?php if ( $schedule ) : ?><div><dt>Orario</dt><dd><?php get_template_part( 'template-parts/event-schedule', null, array( 'schedule' => $schedule ) ); ?></dd></div><?php endif; ?>
						<?php if ( $has_location ) : ?>
							<div>
								<dt>Luogo</dt>
								<dd>
									<?php get_template_part( 'template-parts/event-location', null, array( 'location' => $location ) ); ?>
								</dd>
							</div>
						<?php endif; ?>
						<?php if ( $online_access['platform'] || $online_access['url'] ) : ?>
							<div>
								<dt><?php echo $online_access['platform'] ? 'Piattaforma' : 'Partecipazione online'; ?></dt>
								<dd>
									<?php echo esc_html( $online_access['platform'] ); ?>
									<?php if ( $online_access['url'] ) : ?>
										<?php if ( $online_access['platform'] ) : ?> · <?php endif; ?>
										<a href="<?php echo esc_url( $online_access['url'] ); ?>">Accedi alla piattaforma</a>
									<?php endif; ?>
								</dd>
							</div>
						<?php endif; ?>
						<?php if ( isset( $participation_labels[ $participation ] ) ) : ?><div><dt>Partecipazione</dt><dd><?php echo esc_html( $participation_labels[ $participation ] ); ?></dd></div><?php endif; ?>
						<?php if ( $past && $registration['has_fees'] ) : ?><div><dt>Quota</dt><dd><?php get_template_part( 'template-parts/event-fees', null, array( 'fees' => $fees ) ); ?></dd></div><?php endif; ?>
					</dl>
				</section>
				<?php get_template_part( 'template-parts/event-registration', null, array( 'registration' => $registration ) ); ?>

				<?php if ( $course_id && 'publish' === get_post_status( $course_id ) ) : ?>
					<section class="related-course" aria-labelledby="related-course-title">
						<p class="eyebrow">Percorso collegato</p>
						<h2 id="related-course-title"><?php echo esc_html( get_the_title( $course_id ) ); ?></h2>
						<p>Questo evento fa parte del percorso del corso.</p>
						<a class="text-link" href="<?php echo esc_url( get_permalink( $course_id ) ); ?>">Vai al corso collegato <span aria-hidden="true">→</span></a>
					</section>
				<?php endif; ?>
			</div>
		</div>
	</article>
</main>
<?php get_footer(); ?>
