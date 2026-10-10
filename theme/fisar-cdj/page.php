<?php
get_header();
the_post();
$is_values_page = is_page( 'carta-dei-valori' );
$is_privacy_page = ! $is_values_page && is_privacy_policy();
$page_classes   = array( 'standard-page' );
if ( $is_values_page ) {
	$page_classes[] = 'values-page';
} elseif ( $is_privacy_page ) {
	$page_classes[] = 'privacy-page';
}
$values_parts   = $is_values_page ? fisar_cdj_theme_values_content_parts( get_the_content() ) : array();
?>
<main id="main-content">
	<article <?php post_class( $page_classes ); ?>>
		<?php if ( $is_values_page ) : ?>
			<?php get_template_part( 'template-parts/values-hero' ); ?>
		<?php else : ?>
		<header class="page-hero">
			<div class="container page-hero__inner">
				<?php if ( ! $is_privacy_page ) : ?><p class="eyebrow">FISAR Castelli di Jesi</p><?php endif; ?>
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
			</div>
		</header>
		<?php endif; ?>
		<div class="container prose prose--page">
			<?php if ( $is_values_page && $values_parts['note'] ) : ?>
				<aside class="values-note" aria-label="Nota sulla Carta dei Valori">
					<?php echo fisar_cdj_theme_icon( 'info', 'values-note__icon' ); ?>
					<div class="values-note__content"><?php echo $values_parts['note']; ?></div>
				</aside>
			<?php endif; ?>
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
