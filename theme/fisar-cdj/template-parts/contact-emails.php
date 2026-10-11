<?php
/** Indirizzi alternativi: basta scrivere a uno dei due recapiti. */
$emails = $args['emails'] ?? array();
?>
<div class="contact-emails">
	<?php foreach ( $emails as $index => $email ) : ?>
		<?php if ( $index > 0 ) : ?><span class="contact-emails__alternative">oppure</span><?php endif; ?>
		<a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a>
	<?php endforeach; ?>
</div>
