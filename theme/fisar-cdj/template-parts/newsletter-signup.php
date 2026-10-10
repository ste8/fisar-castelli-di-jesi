<?php
/**
 * Contenuto condiviso del pannello newsletter, dentro il layout del chiamante.
 */
$context       = isset( $args['context'] ) ? sanitize_html_class( (string) $args['context'] ) : 'default';
$heading_level = isset( $args['heading_level'] ) && 2 === (int) $args['heading_level'] ? 2 : 3;
$heading_id    = "newsletter-{$context}-title";
?>
<div class="newsletter-panel__intro">
	<p class="eyebrow">Direttamente nella tua casella email</p>
	<<?php echo tag_escape( "h{$heading_level}" ); ?> id="<?php echo esc_attr( $heading_id ); ?>">Iscriviti alla newsletter</<?php echo tag_escape( "h{$heading_level}" ); ?>>
	<p>Ricevi un riepilogo delle iniziative più importanti della Delegazione.</p>
</div>
<?php get_template_part( 'template-parts/newsletter-form', null, array( 'context' => $context ) ); ?>
