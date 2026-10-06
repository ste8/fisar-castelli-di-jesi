<?php
/** One static availability notice shared by the hero and registration panel. */
$notice = $args['details']['limited_seats_notice'] ?? '';
if ( ! $notice ) {
	return;
}
?>
<p class="alert event-limited-seats"><strong>Posti limitati.</strong> <?php echo esc_html( $notice ); ?></p>
