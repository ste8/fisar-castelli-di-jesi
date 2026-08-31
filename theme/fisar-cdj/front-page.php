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
?>
<main id="main-content">
	<section class="hero" aria-labelledby="hero-title">
		<img class="hero__media" src="<?php echo esc_url( $hero_url ); ?>" alt="" fetchpriority="high" decoding="async">
		<div class="hero__overlay"></div>
		<div class="container hero__content">
			<p class="hero__eyebrow">FISAR Castelli di Jesi</p>
			<h1 id="hero-title">Il vino come punto di partenza, le persone al centro.</h1>
			<p>Eventi, corsi e incontri per conoscere, condividere e vivere insieme la cultura del vino.</p>
			<div class="hero__actions">
				<a class="button" href="<?php echo esc_url( $events_url ); ?>">Scopri gli eventi</a>
				<a class="button button--outline-light" href="<?php echo esc_url( $courses_url ); ?>">Scopri i corsi</a>
			</div>
		</div>
	</section>

	<nav class="doors" aria-label="Quattro modi per conoscere la Delegazione">
		<div class="container doors__grid">
			<article class="door">
				<span class="door__number" aria-hidden="true">01</span>
				<h2>Eventi</h2>
				<p>Degustazioni, visite in cantina, serate a tema e molto altro.</p>
				<a class="text-link" href="<?php echo esc_url( $events_url ); ?>">Scopri gli eventi <span aria-hidden="true">→</span></a>
			</article>
			<article class="door">
				<span class="door__number" aria-hidden="true">02</span>
				<h2>Corsi</h2>
				<p>Percorsi di formazione per appassionati e futuri sommelier.</p>
				<a class="text-link" href="<?php echo esc_url( $courses_url ); ?>">Scopri i corsi <span aria-hidden="true">→</span></a>
			</article>
			<article class="door">
				<span class="door__number" aria-hidden="true">03</span>
				<h2>Unisciti a noi</h2>
				<p>Entra a far parte della nostra comunità e condividi la passione.</p>
				<a class="text-link" href="<?php echo esc_url( fisar_cdj_theme_page_url( 'unisciti-a-noi' ) ); ?>">Scopri come unirti a noi <span aria-hidden="true">→</span></a>
			</article>
			<article class="door">
				<span class="door__number" aria-hidden="true">04</span>
				<h2>Carta dei Valori</h2>
				<p>I principi che guidano ogni nostra attività e scelta.</p>
				<a class="text-link" href="<?php echo esc_url( fisar_cdj_theme_page_url( 'carta-dei-valori' ) ); ?>">Leggi la Carta dei Valori <span aria-hidden="true">→</span></a>
			</article>
		</div>
	</nav>

	<section class="section section--events" aria-labelledby="upcoming-events-title">
		<div class="container">
			<div class="section-heading section-heading--with-link">
				<div><p class="eyebrow">Da vivere insieme</p><h2 id="upcoming-events-title">Prossimi eventi</h2></div>
				<a class="text-link text-link--desktop" href="<?php echo esc_url( $events_url ); ?>">Vedi tutti gli eventi <span aria-hidden="true">→</span></a>
			</div>
			<div class="event-grid">
				<?php if ( function_exists( 'fisar_cdj_get_upcoming_events' ) ) : ?>
					<?php $events = fisar_cdj_get_upcoming_events( 3 ); ?>
					<?php while ( $events->have_posts() ) : $events->the_post(); ?>
						<?php get_template_part( 'template-parts/event-card' ); ?>
					<?php endwhile; wp_reset_postdata(); ?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/empty-state' ); ?>
				<?php endif; ?>
			</div>
			<a class="text-link text-link--mobile" href="<?php echo esc_url( $events_url ); ?>">Vedi tutti gli eventi <span aria-hidden="true">→</span></a>
		</div>
	</section>

	<section class="section section--tinted" aria-labelledby="courses-title">
		<div class="container">
			<div class="section-heading section-heading--with-link">
				<div><p class="eyebrow">Imparare e condividere</p><h2 id="courses-title">I nostri corsi</h2></div>
				<a class="text-link text-link--desktop" href="<?php echo esc_url( $courses_url ); ?>">Vedi tutti i corsi <span aria-hidden="true">→</span></a>
			</div>
			<div class="course-grid">
				<?php if ( function_exists( 'fisar_cdj_get_active_courses' ) ) : ?>
					<?php $courses = fisar_cdj_get_active_courses( 3 ); ?>
					<?php while ( $courses->have_posts() ) : $courses->the_post(); ?>
						<?php get_template_part( 'template-parts/course-card' ); ?>
					<?php endwhile; wp_reset_postdata(); ?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/empty-state' ); ?>
				<?php endif; ?>
			</div>
			<a class="text-link text-link--mobile" href="<?php echo esc_url( $courses_url ); ?>">Vedi tutti i corsi <span aria-hidden="true">→</span></a>
		</div>
	</section>

	<section class="section" aria-labelledby="news-title">
		<div class="container">
			<div class="section-heading section-heading--with-link">
				<div><p class="eyebrow">Vita di Delegazione</p><h2 id="news-title">Ultime notizie</h2></div>
				<a class="text-link text-link--desktop" href="<?php echo esc_url( $news_url ); ?>">Leggi tutte le news <span aria-hidden="true">→</span></a>
			</div>
			<div class="news-grid">
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
				<p class="eyebrow">Valori e impegno</p>
				<h2 id="values-title">I nostri valori guidano ciò che facciamo.</h2>
				<p>Crediamo nella cultura del vino, nella formazione continua e nella condivisione autentica.</p>
				<a class="button" href="<?php echo esc_url( fisar_cdj_theme_page_url( 'carta-dei-valori' ) ); ?>">Leggi la Carta dei Valori</a>
			</div>
			<ul class="values-list">
				<li><strong>Cultura</strong><span>Conoscenza del vino e del territorio.</span></li>
				<li><strong>Formazione</strong><span>Percorsi seri, comprensibili e coinvolgenti.</span></li>
				<li><strong>Condivisione</strong><span>Il vino è incontro, dialogo e convivialità.</span></li>
				<li><strong>Responsabilità</strong><span>Passione, rispetto e consapevolezza.</span></li>
			</ul>
		</div>
	</section>

	<section class="newsletter-cta" aria-labelledby="newsletter-title">
		<div class="container newsletter-cta__inner">
			<div><p class="eyebrow">Resta in contatto</p><h2 id="newsletter-title">Non perdere il prossimo brindisi.</h2><p>Scrivici per ricevere eventi, corsi e notizie dalla Delegazione.</p></div>
			<a class="button" href="<?php echo esc_url( fisar_cdj_theme_page_url( 'contatti' ) ); ?>">Richiedi gli aggiornamenti</a>
		</div>
	</section>
</main>
<?php get_footer(); ?>

