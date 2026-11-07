<?php
$query = $args['query'];
if ( $query->max_num_pages <= 1 ) {
	return;
}
$base = str_replace( 'fisar-page-number', '%#%', add_query_arg( $args['query_var'], 'fisar-page-number', get_post_type_archive_link( Fisar_CDJ_Post_Types::EVENT ) ) );
$links = paginate_links(
	array(
		'base'         => $base,
		'format'       => '',
		'current'      => max( 1, (int) $query->get( 'paged' ) ),
		'total'        => $query->max_num_pages,
		'mid_size'     => 1,
		'prev_text'    => $args['prev_text'],
		'next_text'    => 'Altri eventi →',
		'add_fragment' => '#' . $args['anchor'],
	)
);
?>
<div class="pagination pagination--archive">
	<nav aria-label="<?php echo esc_attr( $args['label'] ); ?>">
		<div class="nav-links"><?php echo wp_kses_post( $links ); ?></div>
	</nav>
</div>
