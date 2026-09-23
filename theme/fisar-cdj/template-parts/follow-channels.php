<?php
/**
 * Griglia dei canali con cui seguire la Delegazione.
 */
$newsletter_url = isset( $args['newsletter_url'] ) ? (string) $args['newsletter_url'] : '#newsletter-signup';
$heading_level  = isset( $args['heading_level'] ) ? (int) $args['heading_level'] : 3;
$heading_level  = in_array( $heading_level, array( 2, 3 ), true ) ? $heading_level : 3;
$channels       = fisar_cdj_theme_follow_channels( $newsletter_url );
?>
<div class="follow-channels">
	<?php foreach ( $channels as $channel ) : ?>
		<article class="follow-card follow-card--<?php echo esc_attr( $channel['key'] ); ?>">
			<?php echo fisar_cdj_theme_icon( $channel['icon'], 'follow-card__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<<?php echo tag_escape( "h{$heading_level}" ); ?> class="follow-card__title"><?php echo esc_html( $channel['title'] ); ?></<?php echo tag_escape( "h{$heading_level}" ); ?>>
			<p><?php echo esc_html( $channel['description'] ); ?></p>
			<a class="text-link" href="<?php echo esc_url( $channel['url'] ); ?>">
				<?php echo esc_html( $channel['cta'] ); ?> <span aria-hidden="true">→</span>
			</a>
		</article>
	<?php endforeach; ?>
</div>
