<?php
$reports = $args['reports'] ?? array();
if ( ! $reports ) {
	return;
}
?>
<section class="event-reports" id="course-reports" aria-labelledby="course-reports-title">
	<h2 id="course-reports-title"><?php echo 1 === count( $reports ) ? 'Il racconto del corso' : 'I racconti del corso'; ?></h2>
	<ul class="event-reports__list">
		<?php foreach ( $reports as $report ) : ?>
			<li>
				<a class="text-link" href="<?php echo esc_url( get_permalink( $report ) ); ?>"><?php echo esc_html( get_the_title( $report ) ); ?> <span aria-hidden="true">→</span></a>
				<small><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $report ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y', $report ) ); ?></time></small>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
