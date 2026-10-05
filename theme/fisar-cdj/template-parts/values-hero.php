<?php
$image_id = get_post_thumbnail_id();
$note = $args['note'] ?? '';
?>
<header class="page-hero values-hero">
	<?php if ( $image_id ) : ?>
		<?php echo wp_get_attachment_image( $image_id, 'full', false, array( 'class' => 'values-hero__image', 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high', 'decoding' => 'async' ) ); ?>
	<?php else : ?>
		<img class="values-hero__image" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/valori-vigneti-demo.webp' ); ?>" alt="" fetchpriority="high" decoding="async">
	<?php endif; ?>
	<div class="values-hero__overlay" aria-hidden="true"></div>
	<div class="container page-hero__inner values-hero__content">
		<p class="eyebrow">FISAR · Delegazione Castelli di Jesi</p>
		<h1><?php the_title(); ?></h1>
		<?php if ( has_excerpt() ) : ?><p class="values-hero__description"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
		<?php if ( $note ) : ?>
			<div class="values-hero__note"><?php echo $note; ?></div>
		<?php endif; ?>
	</div>
</header>
