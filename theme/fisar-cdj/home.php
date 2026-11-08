<?php
get_header();
global $wp_query;
$total_pages  = max( 1, (int) $wp_query->max_num_pages );
$filters = Fisar_CDJ_Archive_Filters::current( 'blog' );
?>
<main id="main-content" class="news-archive">
	<header class="page-hero page-hero--archive">
		<div class="container page-hero__inner">
			<h1>Blog</h1>
			<p>Approfondimenti, racconti e novità dalla nostra Delegazione.</p>
		</div>
	</header>
	<section class="section" aria-labelledby="latest-news-title">
		<div class="container">
			<div class="section-heading"><p class="eyebrow">Dal nostro blog</p><h2 id="latest-news-title">Ultimi articoli</h2></div>
			<?php get_template_part( 'template-parts/archive-filters', null, array( 'scope' => 'blog', 'count' => $wp_query->found_posts ) ); ?>
			<div data-archive-results>
			<?php if ( have_posts() ) : ?>
				<div class="news-grid news-grid--archive">
					<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/news-card' ); endwhile; ?>
				</div>
				<?php if ( $total_pages > 1 ) : ?>
					<div class="pagination pagination--archive">
						<?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '← Articoli più recenti', 'next_text' => 'Altri articoli →', 'aria_label' => 'Paginazione del blog', 'screen_reader_text' => 'Paginazione del blog', 'add_args' => Fisar_CDJ_Archive_Filters::url_args( $filters ) ) ); ?>
					</div>
				<?php endif; ?>
			<?php elseif ( Fisar_CDJ_Archive_Filters::has_criteria( $filters ) ) : ?>
				<p class="archive-no-results">Nessun articolo corrisponde alla ricerca. Prova a cambiare o azzerare i filtri.</p>
			<?php else : get_template_part( 'template-parts/empty-state' ); endif; ?>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
