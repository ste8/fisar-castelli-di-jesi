<?php
get_header();
$events_url = get_post_type_archive_link( Fisar_CDJ_Post_Types::EVENT );
?>
<main id="main-content">
	<header class="page-hero page-hero--archive">
		<div class="container page-hero__inner">
			<p class="eyebrow">Incontri e territorio</p>
			<h1>Eventi</h1>
			<p>Degustazioni, visite in cantina, serate a tema e occasioni per stare insieme. Ogni evento è un modo diverso per conoscere il vino e le persone.</p>
		</div>
	</header>
	<section class="section" aria-labelledby="future-events">
		<div class="container">
			<div class="section-heading"><p class="eyebrow">In programma</p><h2 id="future-events">Prossimi eventi</h2></div>
			<?php $upcoming = fisar_cdj_get_upcoming_events(); ?>
			<?php if ( $upcoming->have_posts() ) : ?>
				<div class="event-grid">
					<?php while ( $upcoming->have_posts() ) : $upcoming->the_post(); get_template_part( 'template-parts/event-card' ); endwhile; ?>
				</div>
			<?php else : get_template_part( 'template-parts/empty-state' ); endif; wp_reset_postdata(); ?>
		</div>
	</section>
	<section class="section section--tinted" aria-labelledby="past-events">
		<div class="container">
			<div class="section-heading"><p class="eyebrow">Il nostro percorso</p><h2 id="past-events">Eventi conclusi</h2></div>
			<?php $past = fisar_cdj_get_past_events(); ?>
			<?php if ( $past->have_posts() ) : ?>
				<div class="event-grid event-grid--past">
					<?php while ( $past->have_posts() ) : $past->the_post(); get_template_part( 'template-parts/event-card' ); endwhile; ?>
				</div>
			<?php else : ?><p>Gli eventi conclusi compariranno qui.</p><?php endif; wp_reset_postdata(); ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>

