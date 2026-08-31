<?php get_header(); ?>
<main id="main-content">
	<header class="page-hero page-hero--archive">
		<div class="container page-hero__inner">
			<p class="eyebrow">Vita di Delegazione</p>
			<h1>News</h1>
			<p>Racconti, aggiornamenti, persone e territori: ciò che succede prima, durante e dopo le nostre attività.</p>
		</div>
	</header>
	<section class="section">
		<div class="container">
			<div class="section-heading"><p class="eyebrow">Dal territorio e dalla comunità</p><h2>Ultime notizie dalla Delegazione</h2></div>
			<?php if ( have_posts() ) : ?>
				<div class="news-grid news-grid--archive">
					<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/news-card' ); endwhile; ?>
				</div>
				<nav class="pagination" aria-label="Paginazione delle news"><?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '← News più recenti', 'next_text' => 'News precedenti →' ) ); ?></nav>
			<?php else : get_template_part( 'template-parts/empty-state' ); endif; ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>
