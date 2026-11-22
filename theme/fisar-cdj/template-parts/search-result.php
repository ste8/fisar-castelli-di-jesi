<?php
global $wp_query;
$id = get_the_ID();
$type = get_post_type();
$label = match ( $type ) { 'fisar_evento' => 'Evento', 'fisar_corso' => 'Corso', 'page' => 'Pagina', default => 'Blog' };
$date = '';
$place = '';
if ( 'fisar_evento' === $type ) {
	$date = Fisar_CDJ_Event_Calendar::card_label( $id );
	$place = trim( (string) get_post_meta( $id, '_fisar_event_city', true ) );
	$venue = trim( (string) get_post_meta( $id, '_fisar_event_venue', true ) );
	$place = implode( ' · ', array_unique( array_filter( array( $venue, $place ) ) ) );
} elseif ( 'fisar_corso' === $type ) {
	$start = (string) get_post_meta( $id, '_fisar_course_start_date', true );
	$date = $start ? 'Inizio: ' . fisar_cdj_theme_format_date( $start ) : (string) Fisar_CDJ_Course_Filters::year( $id );
	if ( '0' === $date ) { $date = ''; }
	$place = implode( ' · ', array_unique( array_filter( array(
		trim( (string) get_post_meta( $id, '_fisar_course_venue', true ) ),
		trim( (string) get_post_meta( $id, '_fisar_course_city', true ) ),
	) ) ) );
} elseif ( 'post' === $type ) {
	$date = get_the_date( 'j F Y' );
}
$body = 'page' === $type ? Fisar_CDJ_Global_Search::page_text( get_post( $id ) ) : strip_shortcodes( get_post_field( 'post_content', $id ) );
$snippet = Fisar_CDJ_Search_Preview::excerpt( $body, $wp_query );
$title = Fisar_CDJ_Search_Preview::plain_text( get_the_title() );
?>
<article <?php post_class( 'search-result' ); ?>>
	<div class="search-result__meta">
		<span class="search-result__type"><?php echo esc_html( $label ); ?></span>
		<?php if ( $date ) : ?><span><?php echo esc_html( $date ); ?></span><?php endif; ?>
	</div>
	<h2 class="search-result__title"><a href="<?php the_permalink(); ?>"><?php echo Fisar_CDJ_Search_Preview::highlight( $title, $wp_query ); ?><span aria-hidden="true"> →</span></a></h2>
	<?php if ( $place ) : ?><p class="search-result__place"><?php echo fisar_cdj_theme_icon( 'building' ); ?><span><?php echo Fisar_CDJ_Search_Preview::highlight( $place, $wp_query ); ?></span></p><?php endif; ?>
	<?php if ( $snippet ) : ?><p class="search-result__excerpt"><?php echo $snippet; ?></p><?php endif; ?>
</article>
