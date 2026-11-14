<?php
$asset_url = get_template_directory_uri() . '/assets/';
?>
<figure class="training-journey" data-document="<?php echo esc_url( $asset_url . 'animations/training-journey.json?ver=' . FISAR_CDJ_THEME_VERSION ); ?>" data-player="<?php echo esc_url( $asset_url . 'js/vendor/clayzo/index.js?ver=0.3.21' ); ?>">
	<div class="training-journey__drawing" aria-hidden="true">
		<img class="training-journey__fallback" src="<?php echo esc_url( $asset_url . 'images/training-journey.svg' ); ?>" width="900" height="180" alt="" loading="lazy">
		<canvas class="training-journey__canvas" width="900" height="180" hidden></canvas>
	</div>
	<figcaption>
		<div class="training-journey__labels"><span>Degustare</span><span>Esplorare i territori</span><span>Abbinare</span></div>
		<button class="training-journey__replay" type="button" aria-label="Rivedi l’animazione del percorso sommelier" hidden><span aria-hidden="true">↻</span> Rivedi il percorso</button>
	</figcaption>
</figure>
