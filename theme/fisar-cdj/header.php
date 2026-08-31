<?php
/**
 * Header del sito.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script>document.documentElement.classList.add('js');</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content">Vai al contenuto principale</a>
<header class="site-header">
	<div class="topbar">
		<div class="container topbar__inner">
			<p class="topbar__payoff">Il vino come punto di partenza, le persone al centro.</p>
			<?php if ( has_nav_menu( 'social' ) ) : ?>
				<nav class="topbar__social" aria-label="Canali social">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'social',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>
		</div>
	</div>
	<div class="main-header">
		<div class="container main-header__inner">
			<?php fisar_cdj_theme_logo(); ?>
			<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation">
				<span class="menu-toggle__icon" aria-hidden="true"><span></span><span></span><span></span></span>
				<span class="menu-toggle__label">Menu</span>
			</button>
			<div class="main-header__navigation" id="primary-navigation">
				<nav class="primary-nav" aria-label="Navigazione principale">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'depth'          => 2,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
				<a class="button button--join" href="<?php echo esc_url( fisar_cdj_theme_page_url( 'unisciti-a-noi' ) ); ?>">Unisciti a noi</a>
			</div>
		</div>
	</div>
</header>

