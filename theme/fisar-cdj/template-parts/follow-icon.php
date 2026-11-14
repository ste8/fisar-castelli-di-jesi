<?php
/** Native SVG icons with decorative accents, used only by the Home animation trial. */
$key = $args['key'];
?>
<span class="follow-icon" aria-hidden="true">
	<?php if ( 'newsletter' === $key ) : ?>
		<svg class="site-icon follow-card__icon" viewBox="0 0 24 24" focusable="false">
			<g class="follow-icon__paper"><rect x="6" y="7" width="12" height="10" rx="1"/><path d="M8 10h8M8 13h6"/></g>
			<rect class="follow-icon__envelope" x="3" y="5" width="18" height="14" rx="2"/>
			<path class="follow-icon__flap" d="m4 7 8 6 8-6"/>
		</svg>
	<?php else : ?>
		<?php echo fisar_cdj_theme_icon( $args['icon'], 'follow-card__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php endif; ?>
	<?php if ( 'whatsapp' === $key ) : ?>
		<span class="follow-icon__dots"><i></i><i></i><i></i></span>
	<?php elseif ( 'instagram' === $key ) : ?>
		<svg class="follow-icon__shine" viewBox="0 0 16 16" focusable="false"><path d="M8 1c0 5-2 7-7 7 5 0 7 2 7 7 0-5 2-7 7-7-5 0-7-2-7-7Z"/></svg>
	<?php elseif ( 'facebook' === $key ) : ?>
		<span class="follow-icon__ring"></span>
	<?php endif; ?>
</span>
