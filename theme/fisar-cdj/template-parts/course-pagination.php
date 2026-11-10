<?php
$query = $args['query'];
if ( $query->max_num_pages <= 1 ) {
	return;
}
$base = str_replace( 'fisar-page-number', '%#%', add_query_arg( 'corsi_conclusi', 'fisar-page-number', get_post_type_archive_link( Fisar_CDJ_Post_Types::COURSE ) ) );
$links = paginate_links(
	array(
		'base'         => $base,
		'format'       => '',
		'current'      => max( 1, (int) $query->get( 'paged' ) ),
		'total'        => $query->max_num_pages,
		'mid_size'     => 1,
		'prev_text'    => '← Corsi più recenti',
		'next_text'    => 'Altri corsi →',
		'add_fragment' => '#past-courses',
		'add_args'     => Fisar_CDJ_Archive_Filters::url_args( $args['filters'] ),
	)
);
?>
<div class="pagination pagination--archive">
	<nav aria-label="Paginazione dei corsi conclusi">
		<div class="nav-links"><?php echo wp_kses_post( $links ); ?></div>
	</nav>
</div>
