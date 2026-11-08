<?php
$periods = Fisar_CDJ_Event_Calendar::for_event( (int) $args['event_id'] );
$next_date = Fisar_CDJ_Event_Calendar::next_date( $periods, fisar_cdj_today() );
if ( count( $periods ) > 1 ) : ?>
	<ul class="event-calendar-dates">
		<?php foreach ( $periods as $period ) : ?>
			<li><?php if ( $next_date === $period['start'] && $periods[0]['start'] < fisar_cdj_today() ) : ?><span class="event-calendar-dates__next">Prossima data</span><?php endif; ?><time datetime="<?php echo esc_attr( $period['start'] ); ?>"><?php echo esc_html( Fisar_CDJ_Event_Calendar::format_date( $period['start'] ) ); ?></time><?php if ( $period['note'] ) : ?><span class="event-calendar-dates__note"><?php echo esc_html( $period['note'] ); ?></span><?php endif; ?></li>
		<?php endforeach; ?>
	</ul>
<?php elseif ( $periods ) : ?>
	<?php $period = $periods[0]; ?>
	<?php if ( $period['start'] !== $period['end'] ) : ?>Dal <?php endif; ?><time datetime="<?php echo esc_attr( $period['start'] ); ?>"><?php echo esc_html( Fisar_CDJ_Event_Calendar::format_date( $period['start'] ) ); ?></time><?php if ( $period['start'] !== $period['end'] ) : ?> al <time datetime="<?php echo esc_attr( $period['end'] ); ?>"><?php echo esc_html( Fisar_CDJ_Event_Calendar::format_date( $period['end'] ) ); ?></time><?php endif; ?>
<?php endif; ?>
