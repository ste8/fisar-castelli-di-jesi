<?php get_header(); ?>
<main id="main-content" class="news-archive">
	<header class="page-hero page-hero--archive">
		<div class="container page-hero__inner">
			<p class="eyebrow">Vita di Delegazione</p>
			<h1>Blog</h1>
			<p>Approfondimenti, racconti e novità dalla nostra Delegazione.</p>
		</div>
	</header>
	<section class="section" aria-labelledby="latest-news-title">
		<div class="container">
			<div class="section-heading"><p class="eyebrow">Dal nostro blog</p><h2 id="latest-news-title">Ultimi articoli</h2></div>
			<?php if ( have_posts() ) : ?>
				<div class="news-grid news-grid--archive">
					<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/news-card' ); endwhile; ?>
				</div>
				<div class="pagination"><?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '← Articoli più recenti', 'next_text' => 'Articoli precedenti →', 'aria_label' => 'Paginazione del blog', 'screen_reader_text' => 'Paginazione del blog' ) ); ?></div>
			<?php else : get_template_part( 'template-parts/empty-state' ); endif; ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>
