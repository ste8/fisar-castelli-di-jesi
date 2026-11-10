<?php
get_header();
$filters = Fisar_CDJ_Archive_Filters::current( 'courses' );
$filtered = Fisar_CDJ_Archive_Filters::has_criteria( $filters );
$past = fisar_cdj_get_past_courses( $filters['per_page'], max( 1, (int) get_query_var( 'corsi_conclusi', 1 ) ), $filters );
?>
<main id="main-content" class="courses-archive">
	<header class="page-hero page-hero--archive">
		<div class="container page-hero__inner">
			<h1>Corsi</h1>
			<p>Corsi per sommelier per conoscere e approfondire il mondo del vino.</p>
		</div>
	</header>
	<section class="section" aria-labelledby="active-courses">
		<div class="container">
			<div class="section-heading"><p class="eyebrow">In programma</p><h2 id="active-courses">Corsi attivi</h2></div>
			<?php $active = fisar_cdj_get_active_courses(); ?>
			<?php if ( $active->have_posts() ) : ?>
				<div class="course-grid">
					<?php while ( $active->have_posts() ) : $active->the_post(); get_template_part( 'template-parts/course-card' ); endwhile; ?>
				</div>
			<?php else : get_template_part( 'template-parts/empty-state' ); endif; wp_reset_postdata(); ?>
		</div>
	</section>
	<section class="section section--tinted" aria-labelledby="past-courses">
		<div class="container">
			<div class="section-heading"><p class="eyebrow">Archivio</p><h2 id="past-courses">Corsi conclusi</h2></div>
			<?php get_template_part( 'template-parts/archive-filters', null, array( 'scope' => 'courses', 'count' => $past->found_posts ) ); ?>
			<div data-archive-results>
			<?php if ( $past->have_posts() ) : ?>
				<div class="course-grid course-grid--past">
					<?php while ( $past->have_posts() ) : $past->the_post(); get_template_part( 'template-parts/course-card' ); endwhile; ?>
				</div>
			<?php get_template_part( 'template-parts/course-pagination', null, array( 'query' => $past, 'filters' => $filters ) ); ?>
			<?php elseif ( $filtered ) : ?><p class="archive-no-results">Nessun corso concluso corrisponde alla ricerca. Prova a cambiare o azzerare i filtri.</p>
			<?php else : ?><p>I corsi conclusi compariranno qui.</p><?php endif; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
