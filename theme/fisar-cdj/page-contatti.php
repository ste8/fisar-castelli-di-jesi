<?php
get_header();
the_post();
$delegation = fisar_cdj_get_delegation_details();
?>
<main id="main-content">
	<article <?php post_class( 'contacts-page' ); ?>>
		<header class="page-hero">
			<div class="container page-hero__inner"><h1><?php the_title(); ?></h1></div>
		</header>
		<div class="container contacts-page__body">
			<div class="prose contacts-page__intro"><?php the_content(); ?></div>
			<div class="contacts-grid">
				<section class="contact-card contact-card--email" aria-labelledby="contact-email-title">
					<?php echo fisar_cdj_theme_icon( 'mail', 'contact-card__icon' ); ?>
					<h2 id="contact-email-title">Scrivici</h2>
					<p>Puoi scriverci a uno dei due indirizzi:</p>
					<?php get_template_part( 'template-parts/contact-emails', null, array( 'emails' => $delegation['emails'] ) ); ?>
				</section>
				<section class="contact-card" aria-labelledby="contact-delegate-title">
					<h2 id="contact-delegate-title">Il Delegato</h2>
					<p class="contact-card__name"><?php echo esc_html( $delegation['delegate_name'] ); ?></p>
					<p>Cellulare: <a href="<?php echo esc_url( 'tel:+39' . $delegation['delegate_phone'] ); ?>"><?php echo esc_html( $delegation['delegate_phone'] ); ?></a></p>
				</section>
				<section class="contact-card" aria-labelledby="contact-location-title">
					<h2 id="contact-location-title">La sede</h2>
					<address><?php echo esc_html( $delegation['street'] ); ?><br><?php echo esc_html( $delegation['locality'] ); ?></address>
					<p class="contact-card__note">Le sedi delle attività possono essere diverse: trovi il luogo nella pagina di ciascun evento o corso.</p>
				</section>
			</div>
			<section class="contact-details" aria-labelledby="contact-details-title">
				<h2 id="contact-details-title">Dati dell’associazione</h2>
				<p class="contact-details__name"><?php echo esc_html( $delegation['name'] ); ?></p>
				<p>Denominazione abbreviata: <strong><?php echo esc_html( $delegation['short_name'] ); ?></strong></p>
				<dl class="contact-details__list">
					<div><dt>Sede</dt><dd><?php echo esc_html( $delegation['street'] . ' — ' . $delegation['locality'] ); ?></dd></div>
					<div><dt>Codice fiscale</dt><dd><?php echo esc_html( $delegation['tax_code'] ); ?></dd></div>
					<div><dt>Partita IVA</dt><dd><?php echo esc_html( $delegation['vat_number'] ); ?></dd></div>
					<div><dt>Iscrizione RUNTS</dt><dd>N. <?php echo esc_html( $delegation['runts_number'] ); ?></dd></div>
				</dl>
				<div class="contact-details__billing">
					<h3>Dati per la fatturazione</h3>
					<p>Per la fatturazione utilizza denominazione, sede, codice fiscale e partita IVA riportati sopra.</p>
					<p>Codice SDI: <strong><?php echo esc_html( $delegation['sdi_code'] ); ?></strong></p>
					<p class="contact-card__note">Il numero di iscrizione RUNTS non è un dato per la fatturazione.</p>
				</div>
			</section>
		</div>
	</article>
</main>
<?php get_footer(); ?>
