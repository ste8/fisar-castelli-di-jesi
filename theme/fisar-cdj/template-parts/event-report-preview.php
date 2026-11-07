<?php
$reports = $args['reports'] ?? array();
if ( ! $reports ) {
	return;
}

$single_report = 1 === count( $reports );
$report        = $reports[0];
$has_image     = has_post_thumbnail( $report );
$url           = $single_report ? get_permalink( $report ) : '#event-reports';
?>
<a class="event-report-preview<?php echo $has_image ? '' : ' event-report-preview--without-image'; ?>" href="<?php echo esc_url( $url ); ?>">
	<?php if ( $has_image ) : ?>
		<?php echo get_the_post_thumbnail( $report, 'medium_large', array( 'class' => 'event-report-preview__image', 'alt' => '', 'loading' => 'eager' ) ); ?>
	<?php endif; ?>
	<span class="event-report-preview__body">
		<span class="event-report-preview__label">Rivivi l’evento</span>
		<span class="event-report-preview__cta"><?php echo $single_report ? 'leggendo il racconto' : 'leggendo i racconti'; ?> <span class="event-report-preview__arrow" aria-hidden="true">→</span></span>
	</span>
</a>
