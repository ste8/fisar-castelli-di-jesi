<?php
/**
 * Footer del sito.
 */
$delegation = fisar_cdj_get_delegation_details();
?>
<footer class="site-footer">
	<div class="container footer-grid">
		<div class="footer-brand">
			<?php fisar_cdj_theme_logo(); ?>
			<p class="footer-brand__name"><?php echo esc_html( $delegation['short_name'] ); ?></p>
			<address><?php echo esc_html( $delegation['street'] ); ?><br><?php echo esc_html( $delegation['locality'] ); ?></address>
			<p class="footer-tax-details">C.F. <?php echo esc_html( $delegation['tax_code'] ); ?><br>P.IVA <?php echo esc_html( $delegation['vat_number'] ); ?></p>
		</div>
		<div>
			<h2 class="footer-title">Navigazione</h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => 'nav',
					'container_aria_label' => 'Navigazione nel footer',
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</div>
		<div>
			<h2 class="footer-title">Contatti</h2>
			<?php get_template_part( 'template-parts/contact-emails', null, array( 'emails' => $delegation['emails'] ) ); ?>
			<ul class="footer-links">
				<li><a href="<?php echo esc_url( fisar_cdj_theme_page_url( 'contatti' ) ); ?>">Tutti i contatti</a></li>
			</ul>
		</div>
		<div class="footer-newsletter">
			<h2 class="footer-title">Seguici</h2>
			<a class="button" href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>">Iscriviti alla newsletter</a>
			<a class="footer-newsletter__all" href="<?php echo esc_url( fisar_cdj_theme_page_url( 'seguici' ) ); ?>">Scopri tutti i canali <span aria-hidden="true">→</span></a>
			<?php if ( has_nav_menu( 'social' ) ) : ?>
				<nav class="footer-social" aria-label="Canali social nel footer">
					<?php wp_nav_menu( array( 'theme_location' => 'social', 'container' => false, 'depth' => 1, 'fallback_cb' => false ) ); ?>
				</nav>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
