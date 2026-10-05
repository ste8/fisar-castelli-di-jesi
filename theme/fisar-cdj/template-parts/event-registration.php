<?php
/** The single booking destination, reached from the hero or after reading. */
$registration = $args['registration'];
$details      = $registration['details'];
?>
<section id="event-registration" class="registration-box" aria-labelledby="registration-title" tabindex="-1">
	<h2 id="registration-title"><?php echo $registration['past'] ? 'Evento concluso' : ( $registration['required'] ? 'Iscrizione' : 'Partecipazione' ); ?></h2>
	<?php if ( $registration['past'] ) : ?>
		<p>Questo evento si è già svolto. Scopri le prossime occasioni per partecipare.</p>
		<a class="button" href="<?php echo esc_url( fisar_cdj_theme_archive_url( Fisar_CDJ_Post_Types::EVENT, 'eventi' ) ); ?>">Vedi i prossimi eventi</a>
	<?php else : ?>
		<?php get_template_part( 'template-parts/event-deadline', null, array( 'details' => $details ) ); ?>
		<?php if ( $registration['limited'] ) : ?><p class="alert"><strong>Posti limitati.</strong> Prenota appena possibile.</p><?php endif; ?>
		<?php if ( $registration['has_fees'] ) : ?>
			<div class="registration-fees">
				<h3>Quota di partecipazione</h3>
				<?php get_template_part( 'template-parts/event-fees', null, array( 'fees' => $registration['fees'] ) ); ?>
			</div>
		<?php endif; ?>
		<?php foreach ( $details['lines'] as $line ) : ?><p class="registration-intro"><strong><?php echo esc_html( $line ); ?></strong></p><?php endforeach; ?>
		<?php if ( $registration['required'] && $registration['channels'] ) : ?>
			<section class="registration-methods" aria-labelledby="registration-methods-title">
				<h3 id="registration-methods-title">Come prenotare</h3>
				<ul class="registration-channels">
					<?php foreach ( $registration['channels'] as $channel ) : ?>
						<li>
							<h4 class="registration-contact__label"><?php echo esc_html( $channel['reference_label'] ); ?></h4>
							<p class="registration-contact"><strong><?php echo esc_html( $channel['reference'] ); ?></strong></p>
							<?php if ( $channel['url'] ) : ?><a class="button" href="<?php echo esc_url( $channel['url'] ); ?>"><?php echo esc_html( $channel['label'] ); ?></a><?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>
		<?php if ( $registration['notes'] ) : ?><div class="registration-notes"><?php echo wp_kses_post( $registration['notes'] ); ?></div><?php endif; ?>
	<?php endif; ?>
</section>
