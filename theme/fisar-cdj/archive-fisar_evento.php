<?php
get_header();
?>
<main id="main-content" class="events-archive">
	<header class="page-hero page-hero--archive">
		<div class="container page-hero__inner">
			<h1>Eventi</h1>
			<p>Degustazioni, visite e incontri per scoprire e condividere.</p>
		</div>
	</header>
	<section class="section" aria-labelledby="future-events">
		<div class="container">
			<div class="section-heading"><p class="eyebrow">In programma</p><h2 id="future-events">Prossimi eventi</h2></div>
			<?php $upcoming = fisar_cdj_get_upcoming_events( 12, max( 1, (int) get_query_var( 'eventi_futuri', 1 ) ) ); ?>
			<?php if ( $upcoming->have_posts() ) : ?>
				<div class="event-grid">
					<?php while ( $upcoming->have_posts() ) : $upcoming->the_post(); get_template_part( 'template-parts/event-card', null, array( 'context' => 'archive' ) ); endwhile; ?>
				</div>
				<?php get_template_part( 'template-parts/event-pagination', null, array( 'query' => $upcoming, 'query_var' => 'eventi_futuri', 'anchor' => 'future-events', 'label' => 'Paginazione dei prossimi eventi', 'prev_text' => '← Eventi precedenti' ) ); ?>
			<?php else : get_template_part( 'template-parts/empty-state' ); endif; wp_reset_postdata(); ?>
		</div>
	</section>
	<section class="section section--tinted" aria-labelledby="past-events">
		<div class="container">
			<div class="section-heading"><p class="eyebrow">Archivio</p><h2 id="past-events">Eventi conclusi</h2></div>
			<?php $past = fisar_cdj_get_past_events( 12, max( 1, (int) get_query_var( 'eventi_conclusi', 1 ) ) ); ?>
			<?php if ( $past->have_posts() ) : ?>
				<div class="event-grid event-grid--past">
					<?php while ( $past->have_posts() ) : $past->the_post(); get_template_part( 'template-parts/event-card', null, array( 'context' => 'archive' ) ); endwhile; ?>
				</div>
				<?php get_template_part( 'template-parts/event-pagination', null, array( 'query' => $past, 'query_var' => 'eventi_conclusi', 'anchor' => 'past-events', 'label' => 'Paginazione degli eventi conclusi', 'prev_text' => '← Eventi più recenti' ) ); ?>
			<?php else : ?><p>Gli eventi conclusi compariranno qui.</p><?php endif; wp_reset_postdata(); ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>
