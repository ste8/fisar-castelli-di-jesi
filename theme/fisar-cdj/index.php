<?php get_header(); ?>
<main id="main-content" class="section">
	<div class="container">
		<header class="section-heading"><h1><?php echo esc_html( is_search() ? 'Risultati di ricerca' : 'Contenuti' ); ?></h1></header>
		<?php if ( have_posts() ) : ?>
			<h2 class="screen-reader-text">Elenco dei contenuti</h2>
			<div class="news-grid">
				<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/news-card' ); endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : get_template_part( 'template-parts/empty-state' ); endif; ?>
	</div>
</main>
<?php get_footer(); ?>
