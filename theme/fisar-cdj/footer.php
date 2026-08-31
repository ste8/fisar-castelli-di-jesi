<?php
/**
 * Footer del sito.
 */
?>
<footer class="site-footer">
	<div class="container footer-grid">
		<div class="footer-brand">
			<?php fisar_cdj_theme_logo(); ?>
			<p>Federazione Italiana Sommelier Albergatori Ristoratori</p>
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
			<ul class="footer-links">
				<li><a href="mailto:info@fisarcastellidijesi.it">info@fisarcastellidijesi.it</a></li>
				<li><a href="tel:+393331234567">+39 333 123 4567</a></li>
				<li><a href="<?php echo esc_url( fisar_cdj_theme_page_url( 'contatti' ) ); ?>">Tutti i contatti</a></li>
			</ul>
			<p class="footer-demo-note">Recapiti dimostrativi da sostituire.</p>
		</div>
		<div class="footer-newsletter">
			<h2 class="footer-title">Resta aggiornato</h2>
			<p>Eventi, corsi e racconti della Delegazione, senza rumore.</p>
			<a class="button" href="<?php echo esc_url( fisar_cdj_theme_page_url( 'contatti' ) ); ?>">Richiedi gli aggiornamenti</a>
			<?php if ( has_nav_menu( 'social' ) ) : ?>
				<nav class="footer-social" aria-label="Canali social nel footer">
					<?php wp_nav_menu( array( 'theme_location' => 'social', 'container' => false, 'depth' => 1, 'fallback_cb' => false ) ); ?>
				</nav>
			<?php endif; ?>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="container footer-bottom__inner">
			<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> FISAR Castelli di Jesi</p>
			<p>Il vino come punto di partenza, le persone al centro.</p>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
