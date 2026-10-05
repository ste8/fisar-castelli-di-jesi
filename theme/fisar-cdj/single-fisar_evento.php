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
$platform      = (string) get_post_meta( $event_id, '_fisar_event_platform', true );
$online_url    = (string) get_post_meta( $event_id, '_fisar_event_online_url', true );
$participation = (string) get_post_meta( $event_id, '_fisar_event_participation', true );
$is_free       = (bool) get_post_meta( $event_id, '_fisar_event_is_free', true );
$required      = (bool) get_post_meta( $event_id, '_fisar_event_registration_required', true );
$limited       = (bool) get_post_meta( $event_id, '_fisar_event_limited_seats', true );
$member_price  = (string) get_post_meta( $event_id, '_fisar_event_member_price', true );
$nonmember_price = (string) get_post_meta( $event_id, '_fisar_event_non_member_price', true );
$course_id     = absint( get_post_meta( $event_id, '_fisar_event_course_id', true ) );
$past          = fisar_cdj_is_event_past( $event_id );

$mode_labels = array( 'presence' => 'In presenza', 'online' => 'Online', 'hybrid' => 'In presenza e online' );
$participation_labels = array( 'all' => 'Aperto a tutti', 'members' => 'Riservato ai soci', 'members_and_companions' => 'Soci e accompagnatori' );
$schedule = array_filter( array( 'Accoglienza' => $welcome_time, 'Inizio' => $start_time, 'Fine' => $end_time ) );
$city_label = $city ? trim( $city . ( $province ? ' (' . $province . ')' : '' ) ) : '';
$has_location = $venue || $address || $city_label;
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
							<li class="event-schedule-row"><strong>Orari</strong><?php get_template_part( 'template-parts/event-schedule', null, array( 'schedule' => $schedule ) ); ?></li>
						<?php endif; ?>
						<?php if ( 'online' === $mode ) : ?>
							<li><strong>Modalità</strong><span>Online</span></li>
						<?php elseif ( $has_location ) : ?>
							<li>
								<strong>Luogo</strong>
								<span class="event-location">
									<?php if ( $venue ) : ?><strong class="event-location__name"><?php echo esc_html( $venue ); ?></strong><?php endif; ?>
									<?php if ( $address ) : ?><span><?php echo esc_html( $address ); ?></span><?php endif; ?>
									<?php if ( $city_label ) : ?><span><?php echo esc_html( $city_label ); ?></span><?php endif; ?>
									<?php if ( 'hybrid' === $mode ) : ?><span class="event-location__online">Anche online</span><?php endif; ?>
								</span>
							</li>
						<?php elseif ( 'hybrid' === $mode ) : ?>
							<li><strong>Modalità</strong><span>In presenza e online</span></li>
						<?php endif; ?>
					</ul>
				</div>
				<div class="content-hero__media content-hero__media--poster">
					<?php fisar_cdj_theme_post_image( $event_id, 'single-poster', true, 'eager' ); ?>
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
						<?php if ( $schedule ) : ?><div><dt>Orari</dt><dd><?php get_template_part( 'template-parts/event-schedule', null, array( 'schedule' => $schedule ) ); ?></dd></div><?php endif; ?>
						<?php if ( isset( $mode_labels[ $mode ] ) ) : ?><div><dt>Modalità</dt><dd><?php echo esc_html( $mode_labels[ $mode ] ); ?></dd></div><?php endif; ?>
						<?php if ( $has_location ) : ?>
							<div>
								<dt>Luogo</dt>
								<dd class="event-location">
									<?php if ( $venue ) : ?><strong class="event-location__name"><?php echo esc_html( $venue ); ?></strong><?php endif; ?>
									<?php if ( $address ) : ?><span><?php echo esc_html( $address ); ?></span><?php endif; ?>
									<?php if ( $city_label ) : ?><span><?php echo esc_html( $city_label ); ?></span><?php endif; ?>
								</dd>
							</div>
						<?php endif; ?>
						<?php if ( $platform ) : ?><div><dt>Piattaforma</dt><dd><?php echo esc_html( $platform ); ?><?php if ( $online_url && ! $past ) : ?> · <a href="<?php echo esc_url( $online_url ); ?>">Accedi alla piattaforma</a><?php endif; ?></dd></div><?php endif; ?>
						<?php if ( isset( $participation_labels[ $participation ] ) ) : ?><div><dt>Partecipazione</dt><dd><?php echo esc_html( $participation_labels[ $participation ] ); ?></dd></div><?php endif; ?>
						<div><dt>Quota</dt><dd><?php if ( $is_free ) : ?>Gratuito<?php else : ?><?php echo $member_price ? 'Soci: ' . esc_html( $member_price ) : ''; ?><?php echo $nonmember_price ? '<br>Non soci: ' . esc_html( $nonmember_price ) : ''; ?><?php endif; ?></dd></div>
					</dl>
				</section>

				<?php if ( $course_id && 'publish' === get_post_status( $course_id ) ) : ?>
					<section class="related-course" aria-labelledby="related-course-title">
						<p class="eyebrow">Percorso collegato</p>
						<h2 id="related-course-title"><?php echo esc_html( get_the_title( $course_id ) ); ?></h2>
						<p>Questo evento fa parte del percorso del corso.</p>
						<a class="text-link" href="<?php echo esc_url( get_permalink( $course_id ) ); ?>">Vai al corso collegato <span aria-hidden="true">→</span></a>
					</section>
				<?php endif; ?>
			</div>

			<aside class="registration-box" aria-labelledby="registration-title">
				<h2 id="registration-title"><?php echo $past ? 'Evento concluso' : ( $required ? 'Iscriviti all’evento' : 'Partecipa' ); ?></h2>
				<?php if ( $limited && ! $past ) : ?><p class="alert"><strong>Posti limitati.</strong> Prenota appena possibile.</p><?php endif; ?>
				<?php if ( $past ) : ?>
					<p>Questo evento si è già svolto. Scopri le prossime occasioni per partecipare.</p>
					<a class="button" href="<?php echo esc_url( fisar_cdj_theme_archive_url( Fisar_CDJ_Post_Types::EVENT, 'eventi' ) ); ?>">Vedi i prossimi eventi</a>
				<?php else : ?>
					<?php foreach ( fisar_cdj_get_event_registration_copy( $event_id ) as $line ) : ?><p><?php echo esc_html( $line ); ?></p><?php endforeach; ?>
					<?php if ( $required ) : ?>
						<ul class="registration-channels">
							<?php foreach ( fisar_cdj_get_registration_channels( $event_id, 'event' ) as $channel ) : ?>
								<li><?php if ( $channel['url'] ) : ?><a class="button" href="<?php echo esc_url( $channel['url'] ); ?>"><?php echo esc_html( $channel['label'] ); ?></a><?php else : ?><strong><?php echo esc_html( $channel['label'] ); ?>:</strong> <?php echo esc_html( $channel['value'] ); ?><?php endif; ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php $notes = (string) get_post_meta( $event_id, '_fisar_event_registration_notes', true ); if ( $notes ) : ?><div class="registration-notes"><?php echo wp_kses_post( $notes ); ?></div><?php endif; ?>
				<?php endif; ?>
			</aside>
		</div>
	</article>
</main>
<?php get_footer(); ?>
