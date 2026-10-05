<?php
get_header();
the_post();
$is_values_page = is_page( 'carta-dei-valori' );
$values_parts   = $is_values_page ? fisar_cdj_theme_values_content_parts( get_the_content() ) : array();
?>
<main id="main-content">
	<article <?php post_class( $is_values_page ? 'standard-page values-page' : 'standard-page' ); ?>>
		<?php if ( $is_values_page ) : ?>
			<?php get_template_part( 'template-parts/values-hero', null, array( 'note' => $values_parts['note'] ) ); ?>
		<?php else : ?>
		<header class="page-hero">
			<div class="container page-hero__inner">
				<p class="eyebrow">FISAR Castelli di Jesi</p>
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
			</div>
		</header>
		<?php endif; ?>
		<div class="container prose prose--page">
			<?php
			if ( $is_values_page ) {
				echo apply_filters( 'the_content', $values_parts['body'] );
			} else {
				the_content();
			}
			?>
		</div>
	</article>
</main>
<?php get_footer(); ?>
