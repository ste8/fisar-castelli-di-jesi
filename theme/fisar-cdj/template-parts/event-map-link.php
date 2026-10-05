<?php
/** Optional location link, shared by the event summary and practical details. */
$url = $args['url'] ?? '';
if ( ! $url ) {
	return;
}
?>
<a class="text-link event-location__map" href="<?php echo esc_url( $url ); ?>">Apri in Google Maps <span aria-hidden="true">↗</span></a>
