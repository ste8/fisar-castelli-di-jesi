<?php
/** One presentation for the side panel and the call to action at the end. */
$registration = $args['registration'];
$bottom       = ! empty( $args['bottom'] );
$tag          = $bottom ? 'section' : 'aside';
$heading_id   = $bottom ? 'registration-title-bottom' : 'registration-title';
$details      = $registration['details'];
?>
<<?php echo $tag; ?> class="registration-box<?php echo $bottom ? ' registration-box--bottom' : ' registration-box--side'; ?>" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>">
	<h2 id="<?php echo esc_attr( $heading_id ); ?>"><?php echo $registration['past'] ? 'Evento concluso' : ( $registration['required'] ? 'Iscriviti all’evento' : 'Partecipa' ); ?></h2>
	<?php if ( $registration['past'] ) : ?>
		<p>Questo evento si è già svolto. Scopri le prossime occasioni per partecipare.</p>
		<a class="button" href="<?php echo esc_url( fisar_cdj_theme_archive_url( Fisar_CDJ_Post_Types::EVENT, 'eventi' ) ); ?>">Vedi i prossimi eventi</a>
	<?php else : ?>
		<?php if ( $details['deadline'] ) : ?>
			<div class="registration-deadline">
				<?php echo fisar_cdj_theme_icon( 'calendar' ); ?>
				<p><span>Prenotazioni entro il</span><time datetime="<?php echo esc_attr( $details['deadline'] ); ?>"><?php echo esc_html( $details['deadline_label'] ); ?></time></p>
			</div>
			<?php if ( $details['deadline_notice'] ) : ?><p class="registration-deadline-note"><?php echo esc_html( $details['deadline_notice'] ); ?></p><?php endif; ?>
		<?php endif; ?>
		<?php if ( $registration['limited'] ) : ?><p class="alert"><strong>Posti limitati.</strong> Prenota appena possibile.</p><?php endif; ?>
		<?php if ( $registration['has_fees'] ) : ?>
			<div class="registration-fees">
				<h3>Quota di partecipazione</h3>
				<?php get_template_part( 'template-parts/event-fees', null, array( 'fees' => $registration['fees'] ) ); ?>
			</div>
		<?php endif; ?>
		<?php foreach ( $details['lines'] as $line ) : ?><p><?php echo esc_html( $line ); ?></p><?php endforeach; ?>
		<?php if ( $registration['required'] && $registration['channels'] ) : ?>
			<ul class="registration-channels">
				<?php foreach ( $registration['channels'] as $channel ) : ?>
					<li>
						<p class="registration-contact"><span><?php echo esc_html( $channel['reference_label'] ); ?></span><strong><?php echo esc_html( $channel['reference'] ); ?></strong></p>
						<?php if ( $channel['url'] ) : ?><a class="button" href="<?php echo esc_url( $channel['url'] ); ?>"><?php echo esc_html( $channel['label'] ); ?></a><?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if ( $registration['notes'] ) : ?><div class="registration-notes"><?php echo wp_kses_post( $registration['notes'] ); ?></div><?php endif; ?>
	<?php endif; ?>
</<?php echo $tag; ?>>
