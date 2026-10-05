<?php
get_header();

$front_id  = (int) get_option( 'page_on_front' );
$hero_id   = $front_id ?: get_the_ID();
$hero_image_id = get_post_thumbnail_id( $hero_id );
$hero_url  = $hero_image_id ? wp_get_attachment_url( $hero_image_id ) : get_template_directory_uri() . '/assets/images/hero-fallback.svg';
$events_url = class_exists( 'Fisar_CDJ_Post_Types' ) ? fisar_cdj_theme_archive_url( Fisar_CDJ_Post_Types::EVENT, 'eventi' ) : home_url( '/eventi/' );
$courses_url = class_exists( 'Fisar_CDJ_Post_Types' ) ? fisar_cdj_theme_archive_url( Fisar_CDJ_Post_Types::COURSE, 'corsi' ) : home_url( '/corsi/' );
$news_page = (int) get_option( 'page_for_posts' );
$news_url  = $news_page ? get_permalink( $news_page ) : home_url( '/news/' );
$follow_url = fisar_cdj_theme_page_url( 'seguici' );
?>
<main id="main-content">
	<section class="hero" aria-labelledby="hero-title">
		<img class="hero__media" src="<?php echo esc_url( $hero_url ); ?>" alt="" fetchpriority="high" decoding="async">
		<div class="hero__overlay"></div>
		<div class="container hero__content">
			<h1 id="hero-title"><span>FISAR</span> <span>Delegazione</span> <span>Castelli di Jesi</span></h1>
			<p class="hero__payoff"><span>Il vino come punto di partenza,</span> <span>le persone al centro.</span></p>
			<p class="hero__description">Corsi per sommelier, degustazioni e incontri per conoscere il mondo del vino.</p>
			<div class="hero__actions">
				<a class="button" href="<?php echo esc_url( $courses_url ); ?>">Scopri i corsi</a>
				<a class="button button--outline-light" href="<?php echo esc_url( $events_url ); ?>">Scopri gli eventi</a>
			</div>
		</div>
	</section>

	<div class="home-programs section">
		<div class="container home-programs__grid">
			<section class="home-programs__events" aria-labelledby="upcoming-events-title">
				<div class="section-heading section-heading--compact section-heading--with-link">
					<h2 id="upcoming-events-title">Prossimi eventi</h2>
					<a class="text-link text-link--desktop" href="<?php echo esc_url( $events_url ); ?>">Tutti gli eventi <span aria-hidden="true">→</span></a>
				</div>
				<div class="event-grid event-grid--home">
					<?php if ( function_exists( 'fisar_cdj_get_upcoming_events' ) ) : ?>
						<?php $events = fisar_cdj_get_upcoming_events( 2 ); ?>
						<?php while ( $events->have_posts() ) : $events->the_post(); ?>
							<?php get_template_part( 'template-parts/event-card', null, array( 'context' => 'home' ) ); ?>
						<?php endwhile; wp_reset_postdata(); ?>
					<?php else : ?>
						<?php get_template_part( 'template-parts/empty-state' ); ?>
					<?php endif; ?>
				</div>
				<a class="text-link text-link--mobile" href="<?php echo esc_url( $events_url ); ?>">Tutti gli eventi <span aria-hidden="true">→</span></a>
			</section>

			<section class="home-programs__courses" aria-labelledby="courses-title">
				<div class="section-heading section-heading--compact section-heading--with-link">
					<h2 id="courses-title">I nostri corsi</h2>
					<a class="text-link text-link--desktop" href="<?php echo esc_url( $courses_url ); ?>">Tutti i corsi <span aria-hidden="true">→</span></a>
				</div>
				<div class="course-grid course-grid--home">
					<?php if ( function_exists( 'fisar_cdj_get_active_courses' ) ) : ?>
						<?php $courses = fisar_cdj_get_active_courses( 3 ); ?>
						<?php while ( $courses->have_posts() ) : $courses->the_post(); ?>
							<?php get_template_part( 'template-parts/course-card', null, array( 'context' => 'home' ) ); ?>
						<?php endwhile; wp_reset_postdata(); ?>
					<?php else : ?>
						<?php get_template_part( 'template-parts/empty-state' ); ?>
					<?php endif; ?>
				</div>
				<a class="text-link text-link--mobile" href="<?php echo esc_url( $courses_url ); ?>">Tutti i corsi <span aria-hidden="true">→</span></a>
			</section>
		</div>
	</div>

	<section class="section section--home-news" aria-labelledby="news-title">
		<div class="container">
			<div class="section-heading section-heading--compact section-heading--with-link">
				<h2 id="news-title">Ultime notizie dalla Delegazione</h2>
				<a class="text-link text-link--desktop" href="<?php echo esc_url( $news_url ); ?>">Leggi tutte le news <span aria-hidden="true">→</span></a>
			</div>
			<div class="news-grid news-grid--home">
				<?php
				$news = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 4, 'ignore_sticky_posts' => true ) );
				while ( $news->have_posts() ) :
					$news->the_post();
					get_template_part( 'template-parts/news-card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			<a class="text-link text-link--mobile" href="<?php echo esc_url( $news_url ); ?>">Leggi tutte le news <span aria-hidden="true">→</span></a>
		</div>
	</section>

	<section class="values-banner" aria-labelledby="values-title">
		<div class="container values-banner__grid">
			<div class="values-banner__intro">
				<h2 id="values-title">La nostra Carta dei Valori</h2>
				<p>Ci uniscono la passione per il vino, la voglia di conoscerlo e il piacere di condividerlo.</p>
				<p>Coltiviamo competenza e professionalità attraverso corsi e incontri, e viviamo il vino come occasione per creare relazioni.</p>
				<a class="button" href="<?php echo esc_url( fisar_cdj_theme_page_url( 'carta-dei-valori' ) ); ?>">Leggi la Carta dei Valori</a>
			</div>
			<ul class="values-list">
				<li>
					<h3>Il modo di lavorare dei produttori</h3>
					<p>Privilegiamo chi ha cura della terra, dell’ambiente e delle persone, e cerca la qualità in vigna.</p>
				</li>
				<li>
					<h3>Curiosità e apertura</h3>
					<p>Dal vino ci piace allargare lo sguardo a birra, distillati, tè, caffè e cibo.</p>
				</li>
				<li>
					<h3>Il vino con consapevolezza</h3>
					<p>Conoscere ciò che beviamo e scegliere con moderazione.</p>
				</li>
				<li>
					<h3>Semplicità e informalità</h3>
					<p>Competenza e professionalità, in un ambiente in cui sentirsi a proprio agio.</p>
				</li>
				<li>
					<h3>Inclusione e accoglienza</h3>
					<p>Attività aperte a tutti, anche a chi inizia e a chi fa parte di altre associazioni.</p>
				</li>
				<li>
					<h3>Ognuno può contribuire</h3>
					<p>Idee, proposte e iniziative possono arrivare da tutti, non solo dal Consiglio.</p>
				</li>
			</ul>
		</div>
	</section>

	<section class="follow-section" aria-labelledby="follow-title">
		<div class="container">
			<header class="follow-section__header">
				<div>
					<p class="eyebrow">Non perdere le prossime iniziative</p>
					<h2 id="follow-title">Come seguirci</h2>
				</div>
				<div class="follow-section__lead">
					<p>Eventi, corsi e vita della Delegazione: scegli il canale che preferisci per seguirci nel modo più comodo per te.</p>
					<a class="text-link" href="<?php echo esc_url( $follow_url ); ?>">Scopri tutti i canali <span aria-hidden="true">→</span></a>
				</div>
			</header>

			<?php get_template_part( 'template-parts/follow-channels', null, array( 'newsletter_url' => '#newsletter-home', 'heading_level' => 3 ) ); ?>

			<div class="newsletter-panel" id="newsletter-home">
				<div class="newsletter-panel__intro">
					<p class="eyebrow">Direttamente nella tua casella email</p>
					<h3>Iscriviti alla newsletter</h3>
					<p>Ricevi un riepilogo delle iniziative più importanti della Delegazione.</p>
				</div>
				<?php get_template_part( 'template-parts/newsletter-form', null, array( 'context' => 'home' ) ); ?>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
