<?php
$reports = $args['reports'] ?? array();
if ( ! $reports ) {
	return;
}
?>
<section class="event-reports" id="event-reports" aria-labelledby="event-reports-title">
	<p class="eyebrow">Dopo l’evento</p>
	<h2 id="event-reports-title"><?php echo 1 === count( $reports ) ? 'Il racconto dell’evento' : 'I racconti dell’evento'; ?></h2>
	<ul class="event-reports__list">
		<?php foreach ( $reports as $report ) : ?>
			<li><a class="text-link" href="<?php echo esc_url( get_permalink( $report ) ); ?>"><?php echo esc_html( get_the_title( $report ) ); ?> <span aria-hidden="true">→</span></a></li>
		<?php endforeach; ?>
	</ul>
</section>
