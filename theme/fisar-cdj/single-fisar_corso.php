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
$deadline   = (string) get_post_meta( $course_id, '_fisar_course_deadline', true );
$deadline_type = (string) get_post_meta( $course_id, '_fisar_course_deadline_type', true );
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
						<?php if ( $start && $end ) : ?><li><strong>Periodo</strong><span><?php echo esc_html( fisar_cdj_theme_format_date( $start ) ); ?> – <?php echo esc_html( fisar_cdj_theme_format_date( $end ) ); ?></span></li><?php endif; ?>
						<?php if ( $city ) : ?><li><strong>Città</strong><span><?php echo esc_html( $city ); ?><?php echo $province ? ' (' . esc_html( $province ) . ')' : ''; ?></span></li><?php endif; ?>
						<?php if ( $director ) : ?><li><strong>Direttore</strong><span><?php echo esc_html( $director ); ?></span></li><?php endif; ?>
					</ul>
				</div>
				<div class="content-hero__media"><?php fisar_cdj_theme_post_image( $course_id, 'single-course-image', true, 'eager' ); ?></div>
			</div>
		</header>

		<div class="container content-layout">
			<div class="prose">
				<?php the_content(); ?>
				<section aria-labelledby="course-details-title">
					<h2 id="course-details-title">Informazioni pratiche</h2>
					<dl class="details-list">
						<?php if ( $level ) : ?><div><dt>Livello</dt><dd><?php echo esc_html( $level ); ?>° livello</dd></div><?php endif; ?>
						<?php if ( $director ) : ?><div><dt>Direttore del Corso</dt><dd><?php echo esc_html( $director ); ?></dd></div><?php endif; ?>
						<?php if ( $start ) : ?><div><dt>Inizio</dt><dd><?php echo esc_html( fisar_cdj_theme_format_date_with_day( $start ) ); ?></dd></div><?php endif; ?>
						<?php if ( $end ) : ?><div><dt>Fine</dt><dd><?php echo esc_html( fisar_cdj_theme_format_date_with_day( $end ) ); ?></dd></div><?php endif; ?>
						<?php if ( $city ) : ?><div><dt>Sede</dt><dd><?php echo esc_html( implode( ', ', array_filter( array( $venue, $address, trim( $city . ( $province ? ' (' . $province . ')' : '' ) ) ) ) ) ); ?></dd></div><?php endif; ?>
					</dl>
				</section>

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
			</div>

			<aside class="registration-box" aria-labelledby="course-registration-title">
				<h2 id="course-registration-title"><?php echo $active ? 'Iscrizioni e quota' : 'Corso concluso'; ?></h2>
				<?php if ( $active ) : ?>
					<?php if ( $deadline ) : ?><p><strong>Iscrizioni entro il <?php echo esc_html( fisar_cdj_theme_format_date( $deadline ) ); ?>.</strong></p><?php endif; ?>
					<?php if ( 'flexible' === $deadline_type ) : ?><p>Dopo tale termine sarà comunque possibile contattarci, ma non potremo garantire la disponibilità.</p><?php endif; ?>
					<div class="registration-section"><h3>Quota di partecipazione</h3><?php echo wp_kses_post( (string) get_post_meta( $course_id, '_fisar_course_fee', true ) ); ?></div>
					<div class="registration-section"><h3>Tesseramento FISAR</h3><?php echo wp_kses_post( (string) get_post_meta( $course_id, '_fisar_course_membership', true ) ); ?></div>
					<div class="registration-section"><h3>Cosa comprende</h3><?php echo wp_kses_post( (string) get_post_meta( $course_id, '_fisar_course_includes', true ) ); ?></div>
					<ul class="registration-channels">
						<?php foreach ( fisar_cdj_get_registration_channels( $course_id, 'course' ) as $channel ) : ?>
							<li><?php if ( $channel['url'] ) : ?><a class="button" href="<?php echo esc_url( $channel['url'] ); ?>"><?php echo esc_html( $channel['label'] ); ?></a><?php else : ?><strong><?php echo esc_html( $channel['label'] ); ?>:</strong> <?php echo esc_html( $channel['value'] ); ?><?php endif; ?></li>
						<?php endforeach; ?>
					</ul>
					<?php echo wp_kses_post( (string) get_post_meta( $course_id, '_fisar_course_registration_notes', true ) ); ?>
				<?php else : ?>
					<p>Questa edizione è terminata. Consulta i corsi attivi per trovare il prossimo percorso.</p>
					<a class="button" href="<?php echo esc_url( fisar_cdj_theme_archive_url( Fisar_CDJ_Post_Types::COURSE, 'corsi' ) ); ?>">Vedi i corsi attivi</a>
				<?php endif; ?>
			</aside>
		</div>
	</article>
</main>
<?php get_footer(); ?>

