<?php
/**
 * Template della pagina "Come seguirci".
 */
get_header();
the_post();
?>
<main id="main-content">
	<article <?php post_class( 'follow-page' ); ?>>
		<header class="page-hero">
			<div class="container page-hero__inner">
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
			</div>
		</header>

		<section class="section follow-page__channels" aria-label="Canali per seguirci">
			<div class="container">
				<?php if ( '' !== trim( get_the_content() ) ) : ?><div class="follow-page__intro"><?php the_content(); ?></div><?php endif; ?>
				<?php get_template_part( 'template-parts/follow-channels', null, array( 'newsletter_url' => '#newsletter-page', 'heading_level' => 2 ) ); ?>
			</div>
		</section>

		<section class="newsletter-section" id="newsletter-page" aria-labelledby="newsletter-page-title">
			<div class="container newsletter-panel">
				<?php get_template_part( 'template-parts/newsletter-signup', null, array( 'context' => 'page', 'heading_level' => 2 ) ); ?>
			</div>
		</section>
	</article>
</main>
<?php get_footer(); ?>
