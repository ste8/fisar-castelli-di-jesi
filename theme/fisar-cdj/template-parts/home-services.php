<?php
/** Show the services introduction only when its native page is publicly available. */
$services_page = get_page_by_path( 'servizi' );
if ( ! $services_page || ! is_post_publicly_viewable( $services_page ) || $services_page->post_password ) {
	return;
}
?>
<section class="home-services" aria-labelledby="home-services-title">
	<div class="container home-services__inner">
		<?php if ( has_post_thumbnail( $services_page ) ) : ?>
			<div class="home-services__image"><?php echo get_the_post_thumbnail( $services_page, 'large', array( 'alt' => '', 'loading' => 'lazy' ) ); ?></div>
		<?php endif; ?>
		<div class="home-services__content">
			<p class="eyebrow">La squadra al tuo fianco</p>
			<h2 id="home-services-title">Sommelier per il tuo evento</h2>
			<p>Feste, sagre, degustazioni ed eventi aziendali: scopri come possiamo affiancarti nel servizio dei vini.</p>
			<a class="button" href="<?php echo esc_url( get_permalink( $services_page ) ); ?>">Scopri i servizi</a>
		</div>
	</div>
</section>
