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
		<p><span>Prenotazioni entro</span><time datetime="<?php echo esc_attr( $details['deadline'] ); ?>"><?php echo esc_html( fisar_cdj_theme_format_date_with_day( $details['deadline'] ) ); ?></time></p>
	</div>
	<?php if ( $details['deadline_notice'] ) : ?><p class="registration-deadline-note"><?php echo esc_html( $details['deadline_notice'] ); ?></p><?php endif; ?>
</div>
