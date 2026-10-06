<?php
/** A text badge below the poster, never an overlay on the image. */
$details = $args['details'];
if ( ! in_array( $details['status'] ?? '', array( 'sold_out', 'waitlist' ), true ) ) {
	return;
}
?>
<p class="event-sold-out">
	<strong>SOLD-OUT</strong>
	<?php if ( $details['waiting_list'] ) : ?><span>Lista d’attesa disponibile</span><?php endif; ?>
</p>
