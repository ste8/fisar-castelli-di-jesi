<?php
/**
 * Modulo di iscrizione Mailchimp senza dipendenze JavaScript esterne.
 */
$context     = isset( $args['context'] ) ? sanitize_html_class( (string) $args['context'] ) : 'default';
$form_id     = "newsletter-form-{$context}";
$email_id    = "newsletter-email-{$context}";
$name_id     = "newsletter-name-{$context}";
$surname_id  = "newsletter-surname-{$context}";
$consent_id  = "newsletter-consent-{$context}";
$note_id     = "newsletter-note-{$context}";
$config      = function_exists( 'fisar_cdj_get_newsletter_signup_config' ) ? fisar_cdj_get_newsletter_signup_config() : array();
$action      = isset( $config['action'] ) ? (string) $config['action'] : '';
$honeypot    = isset( $config['honeypot_name'] ) ? (string) $config['honeypot_name'] : '';
$privacy_url = get_privacy_policy_url() ?: fisar_cdj_theme_page_url( 'privacy-policy' );

if ( '' === $action || '' === $honeypot ) :
	?>
	<p class="newsletter-form__unavailable">Il modulo newsletter non è ancora configurato. <a href="<?php echo esc_url( fisar_cdj_theme_page_url( 'contatti' ) ); ?>">Contatta la Delegazione</a> per ricevere aggiornamenti.</p>
	<?php
	return;
endif;
?>
<form class="newsletter-form" action="<?php echo esc_url( $action ); ?>" method="post" id="<?php echo esc_attr( $form_id ); ?>" target="_blank" rel="noopener" aria-describedby="<?php echo esc_attr( $note_id ); ?>">
	<p class="newsletter-form__required"><span aria-hidden="true">*</span> Campi obbligatori</p>
	<div class="newsletter-form__fields">
		<div class="newsletter-form__field">
			<label for="<?php echo esc_attr( $email_id ); ?>">Email <span aria-hidden="true">*</span></label>
			<input type="email" name="EMAIL" id="<?php echo esc_attr( $email_id ); ?>" autocomplete="email" required>
		</div>
		<div class="newsletter-form__field">
			<label for="<?php echo esc_attr( $name_id ); ?>">Nome <span aria-hidden="true">*</span></label>
			<input type="text" name="FNAME" id="<?php echo esc_attr( $name_id ); ?>" autocomplete="given-name" required>
		</div>
		<div class="newsletter-form__field">
			<label for="<?php echo esc_attr( $surname_id ); ?>">Cognome <span aria-hidden="true">*</span></label>
			<input type="text" name="LNAME" id="<?php echo esc_attr( $surname_id ); ?>" autocomplete="family-name" required>
		</div>
	</div>
	<div class="newsletter-form__honeypot" aria-hidden="true">
		<label for="newsletter-check-<?php echo esc_attr( $context ); ?>">Non compilare questo campo</label>
		<input type="text" name="<?php echo esc_attr( $honeypot ); ?>" id="newsletter-check-<?php echo esc_attr( $context ); ?>" tabindex="-1" autocomplete="off">
	</div>
	<label class="newsletter-form__consent" for="<?php echo esc_attr( $consent_id ); ?>">
		<input type="checkbox" id="<?php echo esc_attr( $consent_id ); ?>" required>
		<span>Ho letto l’<a href="<?php echo esc_url( $privacy_url ); ?>">informativa privacy</a> e chiedo di ricevere la newsletter.</span>
	</label>
	<div class="newsletter-form__submit">
		<button class="button button--light" type="submit">Iscriviti alla newsletter <span class="screen-reader-text">su Mailchimp, si apre una nuova scheda</span></button>
		<p id="<?php echo esc_attr( $note_id ); ?>">L’iscrizione sarà completata su Mailchimp; se richiesto, confermala dall’email che riceverai.</p>
	</div>
</form>
