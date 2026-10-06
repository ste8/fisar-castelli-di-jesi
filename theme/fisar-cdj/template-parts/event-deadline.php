<?php
/** The same deadline and flexible-term notice in the hero and booking panel. */
$details = $args['details'];
if ( ! $details['deadline'] ) {
	return;
}
$summary = ! empty( $args['summary'] );
?>
<div class="registration-deadline<?php echo $summary ? ' registration-deadline--summary' : ''; ?>">
	<div class="registration-deadline__heading">
		<?php echo fisar_cdj_theme_icon( 'calendar' ); ?>
		<?php if ( ! empty( $details['closed'] ) ) : ?>
			<p><span><?php echo esc_html( $details['closed_notice'] ); ?></span></p>
		<?php else : ?>
		<p><span>Prenotazioni entro</span> <time datetime="<?php echo esc_attr( $details['deadline'] ); ?>"><?php echo esc_html( fisar_cdj_theme_format_date_with_day( $details['deadline'] ) ); ?></time></p>
		<?php endif; ?>
	</div>
	<?php if ( ! empty( $details['closed'] ) ) : ?><p class="registration-deadline-note">Termine prenotazioni: <time datetime="<?php echo esc_attr( $details['deadline'] ); ?>"><?php echo esc_html( fisar_cdj_theme_format_date_with_day( $details['deadline'] ) ); ?></time>.</p><?php endif; ?>
	<?php if ( $details['deadline_notice'] ) : ?><p class="registration-deadline-note"><?php echo esc_html( $details['deadline_notice'] ); ?></p><?php endif; ?>
</div>
