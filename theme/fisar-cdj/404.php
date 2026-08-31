<?php get_header(); ?>
<main id="main-content" class="section">
	<div class="container empty-state empty-state--large">
		<p class="eyebrow">Errore 404</p>
		<h1>Questa pagina non si trova.</h1>
		<p>Il contenuto potrebbe essere stato spostato. Torna alla homepage o consulta le prossime attività.</p>
		<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>">Torna alla homepage</a>
	</div>
</main>
<?php get_footer(); ?>

