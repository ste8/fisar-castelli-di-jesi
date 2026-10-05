<?php get_header(); the_post(); ?>
<main id="main-content">
	<article <?php post_class( is_page( 'carta-dei-valori' ) ? 'standard-page values-page' : 'standard-page' ); ?>>
		<header class="page-hero">
			<div class="container page-hero__inner">
				<p class="eyebrow">FISAR Castelli di Jesi</p>
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
			</div>
		</header>
		<div class="container prose prose--page"><?php the_content(); ?></div>
	</article>
</main>
<?php get_footer(); ?>
