<?php get_header(); the_post(); ?>
<main id="main-content">
	<article <?php post_class( 'editorial-single' ); ?>>
		<header class="editorial-hero">
			<div class="container editorial-hero__inner">
				<a class="back-link" href="<?php echo esc_url( get_permalink( (int) get_option( 'page_for_posts' ) ) ); ?>"><span aria-hidden="true">←</span> Tutte le news</a>
				<p class="eyebrow">News dalla Delegazione</p>
				<h1><?php the_title(); ?></h1>
				<p class="editorial-meta"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time> · <?php echo esc_html( fisar_cdj_theme_reading_time( get_the_ID() ) ); ?> min di lettura</p>
				<?php if ( has_excerpt() ) : ?><p class="content-hero__lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
			</div>
		</header>
		<?php if ( has_post_thumbnail() ) : ?><div class="container editorial-image"><?php fisar_cdj_theme_post_image( get_the_ID(), 'editorial-image__asset', true, 'eager' ); ?></div><?php endif; ?>
		<div class="container prose prose--narrow"><?php the_content(); ?></div>
		<nav class="container post-navigation" aria-label="Altre news"><?php the_post_navigation( array( 'prev_text' => '<span>News precedente</span><strong>%title</strong>', 'next_text' => '<span>News successiva</span><strong>%title</strong>' ) ); ?></nav>
	</article>
</main>
<?php get_footer(); ?>

