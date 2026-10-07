<?php
$registration = $args['registration'];
$details = $registration['details'];
?>
<section id="course-registration" class="registration-box" aria-labelledby="course-registration-title" tabindex="-1">
	<h2 id="course-registration-title"><?php echo $registration['active'] ? 'Informazioni e iscrizioni' : 'Corso concluso'; ?></h2>
	<?php if ( ! $details['closed'] && ! $details['waiting_list'] && $registration['channels'] ) : ?><p>Contattaci per conoscere meglio il corso e ricevere tutte le informazioni per iscriverti.</p><?php endif; ?>
	<?php if ( $registration['active'] ) : ?>
		<?php get_template_part( 'template-parts/event-deadline', null, array( 'details' => $details, 'label' => 'Iscrizioni entro', 'past_label' => 'Termine iscrizioni' ) ); ?>
		<?php get_template_part( 'template-parts/event-limited-seats', null, array( 'details' => $details ) ); ?>
	<?php else : ?>
		<p>Questa edizione è terminata. Consulta i corsi attivi per trovare il prossimo percorso.</p>
		<a class="button" href="<?php echo esc_url( fisar_cdj_theme_archive_url( Fisar_CDJ_Post_Types::COURSE, 'corsi' ) ); ?>">Vedi i corsi attivi</a>
	<?php endif; ?>
	<?php foreach ( array( 'fee' => 'Quota di partecipazione', 'includes' => 'Cosa comprende il corso', 'membership' => 'Tesseramento FISAR' ) as $key => $title ) : ?>
		<?php if ( '' !== $registration[ $key ] ) : ?>
			<div class="registration-section<?php echo 'fee' === $key ? ' registration-fees' : ''; ?>">
				<h3><?php echo esc_html( $title ); ?></h3>
				<?php echo wp_kses_post( 'fee' === $key ? fisar_cdj_theme_format_course_fee( $registration[ $key ] ) : $registration[ $key ] ); ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
	<?php if ( ! $details['closed'] ) : ?>
		<?php get_template_part( 'template-parts/registration-channels', null, array( 'channels' => $registration['channels'], 'title' => $details['waiting_list'] ? 'Richiedi la lista d’attesa' : 'Contattaci', 'title_id' => 'course-registration-methods-title' ) ); ?>
		<?php if ( ! $details['waiting_list'] && $registration['notes'] ) : ?><div class="registration-notes"><?php echo wp_kses_post( $registration['notes'] ); ?></div><?php endif; ?>
	<?php endif; ?>
</section>
