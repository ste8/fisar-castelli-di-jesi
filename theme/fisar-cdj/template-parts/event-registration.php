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
		<?php get_template_part( 'template-parts/event-limited-seats', null, array( 'details' => $details ) ); ?>
		<?php if ( $registration['has_fees'] ) : ?>
			<div class="registration-fees">
				<h3>Quota di partecipazione</h3>
				<?php get_template_part( 'template-parts/event-fees', null, array( 'fees' => $registration['fees'] ) ); ?>
			</div>
		<?php endif; ?>
		<?php foreach ( $details['lines'] as $line ) : ?>
			<p class="registration-intro<?php echo $line === ( $details['open_participation_notice'] ?? '' ) ? ' registration-intro--open' : ''; ?>"><strong><?php echo esc_html( $line ); ?></strong></p>
		<?php endforeach; ?>
		<?php if ( empty( $details['closed'] ) && $registration['required'] && $registration['channels'] ) : ?>
			<?php get_template_part( 'template-parts/registration-channels', null, array( 'channels' => $registration['channels'], 'title' => ! empty( $details['waiting_list'] ) ? 'Richiedi la lista d’attesa' : 'Come prenotare' ) ); ?>
		<?php endif; ?>
		<?php if ( empty( $details['closed'] ) && empty( $details['waiting_list'] ) && $registration['notes'] ) : ?><div class="registration-notes"><?php echo wp_kses_post( $registration['notes'] ); ?></div><?php endif; ?>
	<?php endif; ?>
</section>
