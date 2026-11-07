<?php
if ( ! class_exists( 'Fisar_CDJ_Event_Reports' ) ) {
	return;
}
$post_id = (int) ( $args['post_id'] ?? get_the_ID() );
$event_id = Fisar_CDJ_Event_Reports::event_for_report( $post_id );
$social_links = Fisar_CDJ_Event_Reports::social_links( $post_id );
if ( ! $event_id && ! $social_links ) {
	return;
}
?>
<aside class="container report-links" aria-label="Collegamenti al racconto">
	<?php if ( $event_id ) : ?>
		<div class="report-links__event">
			<p class="eyebrow">L’evento raccontato</p>
			<a class="text-link" href="<?php echo esc_url( get_permalink( $event_id ) ); ?>"><?php echo esc_html( get_the_title( $event_id ) ); ?> <span aria-hidden="true">→</span></a>
		</div>
	<?php endif; ?>
	<?php if ( $social_links ) : ?>
		<ul class="report-links__social">
			<?php foreach ( $social_links as $link ) : ?>
				<li><a class="text-link" href="<?php echo esc_url( $link['url'] ); ?>" rel="external"><?php echo esc_html( $link['label'] ); ?> <span aria-hidden="true">↗</span></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</aside>
