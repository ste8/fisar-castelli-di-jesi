<?php
get_header();
global $wp_query;
$search_state = Fisar_CDJ_Global_Search::state( $wp_query );
$search_hint = match ( $search_state['type'] ) {
	'eventi' => 'Cerca negli eventi.',
	'blog' => 'Cerca negli articoli del blog.',
	'corsi' => 'Cerca nei corsi.',
	'pagine' => 'Cerca nelle pagine del sito.',
	default => 'Cerca in tutti i contenuti del sito.',
};
?>
<main id="main-content" class="global-search">
	<header class="page-hero page-hero--archive">
		<div class="container page-hero__inner">
			<h1>Cerca nel sito</h1>
			<p>Eventi, racconti, corsi e informazioni sulla Delegazione: trova quello che ti interessa.</p>
		</div>
	</header>
	<section class="section" aria-label="Ricerca e risultati">
		<div class="container global-search__body">
			<div class="archive-tools">
				<form class="archive-filters global-search__form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Cerca nel sito" aria-describedby="archive-auto-hint" data-archive-scope="global">
					<div class="archive-filters__field archive-filters__search">
						<label for="global-search-text">Che cosa cerchi?</label>
						<input id="global-search-text" type="search" name="s" value="<?php echo esc_attr( $search_state['text'] ); ?>" maxlength="200" placeholder="Es. Verdicchio, Varnelli, Falconara…">
					</div>
					<div class="archive-filters__field">
						<label for="global-search-type">Contenuti</label>
						<select id="global-search-type" name="tipo">
							<?php foreach ( Fisar_CDJ_Global_Search::types() as $value => $label ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $search_state['type'], $value ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="archive-filters__actions"><button type="submit" class="button">Cerca</button></div>
				</form>
				<p id="archive-auto-hint" class="screen-reader-text">Cerca nei titoli e nei testi, anche per città e luogo di eventi e corsi.</p>
				<div class="archive-summary" data-archive-summary>
					<?php if ( $search_state['text'] || $search_state['type'] ) : ?><a data-archive-reset href="<?php echo esc_url( Fisar_CDJ_Global_Search::url() ); ?>">Azzera ricerca <span aria-hidden="true">×</span></a><?php endif; ?>
					<?php if ( '' !== $search_state['text'] ) : ?>
						<?php get_template_part( 'template-parts/archive-results', null, array( 'count' => $wp_query->found_posts ) ); ?>
					<?php else : ?><p class="archive-results" role="status" aria-live="polite" aria-atomic="true"><?php echo esc_html( $search_hint ); ?></p><?php endif; ?>
				</div>
			</div>
			<div data-archive-results aria-label="Risultati della ricerca">
				<?php if ( '' !== $search_state['text'] && have_posts() ) : ?>
					<h2 class="screen-reader-text">Risultati della ricerca</h2>
					<div class="search-results-list">
						<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/search-result' ); endwhile; ?>
					</div>
					<?php if ( $wp_query->max_num_pages > 1 ) : ?>
						<div class="pagination pagination--archive">
							<?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '← Precedenti', 'next_text' => 'Altri risultati →', 'screen_reader_text' => 'Pagine dei risultati', 'add_args' => array( 's' => $search_state['text'], 'tipo' => $search_state['type'] ) ) ); ?>
						</div>
					<?php endif; ?>
				<?php elseif ( '' !== $search_state['text'] ) : ?>
					<div class="global-search__empty"><h2>Nessun risultato</h2><p>Prova con un altro nome o con meno parole, oppure scegli Tutti nel filtro Contenuti.</p></div>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
