<?php
$scope = $args['scope'];
$filters = Fisar_CDJ_Archive_Filters::current( $scope );
$active = Fisar_CDJ_Archive_Filters::has_criteria( $filters );
$years = Fisar_CDJ_Archive_Filters::available_years( $scope, 'events' === $scope );
if ( $filters['year'] && ! in_array( $filters['year'], $years, true ) ) {
	$years[] = $filters['year'];
	rsort( $years );
}
$action = 'blog' === $scope ? get_permalink( (int) get_option( 'page_for_posts' ) ) : get_post_type_archive_link( Fisar_CDJ_Post_Types::EVENT ) . '#past-events';
$reset = 12 === $filters['per_page'] ? $action : add_query_arg( 'per_pagina', $filters['per_page'], $action );
global $wp_locale;
?>
<div class="archive-tools">
	<form class="archive-filters<?php echo 'blog' === $scope ? ' archive-filters--blog' : ''; ?>" method="get" action="<?php echo esc_url( $action ); ?>" aria-label="<?php echo 'blog' === $scope ? 'Filtra gli articoli' : 'Filtra gli eventi conclusi'; ?>" data-archive-scope="<?php echo esc_attr( $scope ); ?>" aria-describedby="archive-auto-hint">
		<div class="archive-filters__field archive-filters__search">
			<label for="archive-text">Cerca nel titolo e nel testo</label>
			<input id="archive-text" type="search" name="search" value="<?php echo esc_attr( $filters['text'] ); ?>" maxlength="200" placeholder="Che cosa cerchi?">
		</div>
		<div class="archive-filters__field">
			<label for="archive-year">Anno</label>
			<select id="archive-year" name="anno">
				<option value="">Tutti</option>
				<?php foreach ( $years as $year ) : ?><option value="<?php echo esc_attr( $year ); ?>" <?php selected( $filters['year'], $year ); ?>><?php echo esc_html( $year ); ?></option><?php endforeach; ?>
			</select>
		</div>
		<div class="archive-filters__field">
			<label for="archive-month">Mese</label>
			<select id="archive-month" name="mese">
				<option value="">Tutti</option>
				<?php for ( $month = 1; $month <= 12; $month++ ) : ?><option value="<?php echo esc_attr( $month ); ?>" <?php selected( $filters['month'], $month ); ?>><?php echo esc_html( ucfirst( $wp_locale->get_month( $month ) ) ); ?></option><?php endfor; ?>
			</select>
		</div>
		<?php if ( 'blog' === $scope ) : ?>
			<div class="archive-filters__field archive-filters__category">
				<label for="archive-category">Categoria</label>
				<select id="archive-category" name="categoria">
					<option value="">Tutte le categorie</option>
					<?php foreach ( Fisar_CDJ_Archive_Filters::categories() as $slug => $label ) : ?><option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $filters['category'], $slug ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?>
					<option value="altro" <?php selected( $filters['category'], 'altro' ); ?>>Altro</option>
				</select>
			</div>
		<?php endif; ?>
		<div class="archive-filters__field archive-filters__page-size">
			<label for="archive-page-size">Risultati per pagina</label>
			<select id="archive-page-size" name="per_pagina">
				<?php foreach ( Fisar_CDJ_Archive_Filters::page_sizes() as $size ) : ?><option value="<?php echo esc_attr( $size ); ?>" <?php selected( $filters['per_page'], $size ); ?>><?php echo esc_html( $size ); ?></option><?php endforeach; ?>
			</select>
		</div>
		<div class="archive-filters__actions">
			<button type="submit" class="button">Cerca</button>
		</div>
	</form>
	<p id="archive-auto-hint" class="screen-reader-text"></p>
	<div class="archive-summary" data-archive-summary>
		<?php if ( $active ) : ?><a data-archive-reset href="<?php echo esc_url( $reset ); ?>">Azzera filtri <span aria-hidden="true">×</span></a><?php endif; ?>
		<?php get_template_part( 'template-parts/archive-results', null, array( 'count' => $args['count'] ) ); ?>
	</div>
</div>
