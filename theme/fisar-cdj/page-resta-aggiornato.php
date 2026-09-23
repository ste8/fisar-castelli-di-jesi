<?php
get_header();
the_post();
?>
<main id="main-content">
	<article <?php post_class( 'follow-page' ); ?>>
		<header class="page-hero">
			<div class="container page-hero__inner">
				<p class="eyebrow">FISAR Castelli di Jesi</p>
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
			</div>
		</header>

		<section class="section follow-page__channels" aria-labelledby="follow-page-channels-title">
			<div class="container">
				<div class="section-heading">
					<p class="eyebrow">Scegli uno o più canali</p>
					<h2 id="follow-page-channels-title">Segui la vita della Delegazione</h2>
				</div>
				<div class="follow-page__intro"><?php the_content(); ?></div>
				<?php get_template_part( 'template-parts/follow-channels', null, array( 'newsletter_url' => '#newsletter-page', 'heading_level' => 3 ) ); ?>
			</div>
		</section>

		<section class="newsletter-section" id="newsletter-page" aria-labelledby="newsletter-page-title">
			<div class="container newsletter-panel">
				<div class="newsletter-panel__intro">
					<p class="eyebrow">Direttamente nella tua casella email</p>
					<h2 id="newsletter-page-title">Iscriviti alla newsletter</h2>
					<p>Ricevi le iniziative più importanti della Delegazione e scegli con calma a quali partecipare.</p>
				</div>
				<?php get_template_part( 'template-parts/newsletter-form', null, array( 'context' => 'page' ) ); ?>
			</div>
		</section>
	</article>
</main>
<?php get_footer(); ?>
