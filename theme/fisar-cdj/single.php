<?php
get_header();
the_post();
$excerpt = has_excerpt() ? get_the_excerpt() : '';
$has_image = has_post_thumbnail();
?>
<main id="main-content">
	<article <?php post_class( 'editorial-single single-news' ); ?>>
		<header class="editorial-hero">
			<div class="container editorial-hero__inner<?php echo $has_image ? ' editorial-hero__inner--with-image content-hero__grid' : ''; ?>">
				<div class="editorial-hero__copy">
					<a class="back-link" href="<?php echo esc_url( get_permalink( (int) get_option( 'page_for_posts' ) ) ); ?>"><span aria-hidden="true">←</span> Tutti gli articoli</a>
					<p class="eyebrow">Dal nostro blog</p>
					<h1><?php the_title(); ?></h1>
					<p class="editorial-meta">
						<span class="editorial-meta__date"><?php echo fisar_cdj_theme_icon( 'calendar' ); ?><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time></span>
						<span>· <?php echo esc_html( fisar_cdj_theme_reading_time( get_the_ID() ) ); ?> min di lettura</span>
					</p>
					<?php if ( '' !== trim( $excerpt ) ) : ?><p class="content-hero__lead"><?php echo esc_html( $excerpt ); ?></p><?php endif; ?>
				</div>
				<?php if ( $has_image ) : ?>
					<div class="editorial-image"><?php fisar_cdj_theme_post_image( get_the_ID(), 'editorial-image__asset', true, 'eager' ); ?></div>
				<?php endif; ?>
			</div>
		</header>
		<div class="container prose prose--narrow"><?php the_content(); ?></div>
		<div class="container news-navigation">
			<?php
			the_post_navigation( array(
				'prev_text'          => '<span>Articolo precedente</span> <strong>%title</strong>',
				'next_text'          => '<span>Articolo successivo</span> <strong>%title</strong>',
				'aria_label'         => 'Altri articoli',
				'screen_reader_text' => 'Altri articoli',
			) );
			?>
		</div>
	</article>
</main>
<?php get_footer(); ?>
