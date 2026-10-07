<?php
/** Shared contacts: readable references remain available without using buttons. */
$channels = $args['channels'];
$title_id = $args['title_id'] ?? 'registration-methods-title';
if ( ! $channels ) {
	return;
}
?>
<section class="registration-methods" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
	<h3 id="<?php echo esc_attr( $title_id ); ?>"><?php echo esc_html( $args['title'] ); ?></h3>
	<ul class="registration-channels">
		<?php foreach ( $channels as $channel ) : ?>
			<li>
				<h4 class="registration-contact__label"><?php echo esc_html( $channel['reference_label'] ); ?></h4>
				<p class="registration-contact"><strong>
					<?php if ( ! empty( $channel['name'] ) ) : ?><span class="registration-contact__name"><?php echo esc_html( $channel['name'] ); ?></span> · <?php endif; ?>
					<span class="registration-contact__reference"><?php echo esc_html( isset( $channel['name'] ) ? fisar_cdj_theme_format_whatsapp_reference( $channel['reference'] ) : $channel['reference'] ); ?></span>
				</strong></p>
				<?php if ( $channel['url'] ) : ?><a class="button" href="<?php echo esc_url( $channel['url'] ); ?>"><?php echo esc_html( $channel['label'] ); ?><?php if ( isset( $channel['name'] ) ) : ?><span class="screen-reader-text">: <?php echo esc_html( $channel['name'] ?: $channel['reference'] ); ?></span><?php endif; ?></a><?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
