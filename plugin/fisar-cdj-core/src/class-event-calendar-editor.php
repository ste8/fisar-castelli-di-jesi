<?php
/** The calendar uses the event metabox's existing authorization and save lifecycle. */
final class Fisar_CDJ_Event_Calendar_Editor {
	public static function render( WP_Post $post ): void {
		$values = Fisar_CDJ_Event_Calendar::values( $post->ID );
		$mode = Fisar_CDJ_Event_Calendar::mode( $values['date_mode'] );
		$rows = Fisar_CDJ_Event_Calendar::additional_dates( $values['additional_dates'] );
		$error_key = 'fisar_event_dates_error_' . get_current_user_id() . '_' . $post->ID;
		$error = get_transient( $error_key );
		if ( $error ) { echo '<p class="notice notice-error">' . esc_html( $error ) . '</p>'; delete_transient( $error_key ); }
		?>
		<div id="fisar-event-calendar" data-editor="<?php echo get_current_screen()?->is_block_editor() ? 'block' : 'classic'; ?>">
			<input type="hidden" name="_fisar_event_calendar_present" value="1">
			<div class="fisar-admin-grid fisar-event-calendar-main">
				<?php self::input( '_fisar_event_date', 'Data evento', 'date', $values['date'] ); ?>
				<div class="fisar-field"><label for="_fisar_event_date_mode">Svolgimento</label>
					<select id="_fisar_event_date_mode" name="_fisar_event_date_mode">
						<?php foreach ( array( 'single' => 'Una sola giornata', 'range' => 'Periodo continuativo', 'multiple' => 'Più date distinte' ) as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $mode, $key ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div data-event-date-modes="range">
				<?php self::input( '_fisar_event_end_date', 'Data finale (inclusa)', 'date', $values['end_date'] ); ?>
				<p class="description">Per un evento che prosegue nelle giornate comprese tra la data iniziale e quella finale.</p>
			</div>
			<div data-event-date-modes="multiple">
				<?php self::input( '_fisar_event_date_note', 'Nota per la prima data (facoltativa)', 'text', $values['date_note'] ); ?>
				<p class="description">Per appuntamenti distinti, anche non consecutivi. Le date vengono mostrate in ordine cronologico. Usa le note per indicazioni specifiche, per esempio “Uscita in giornata con Francesco”.</p>
				<div id="fisar-event-additional-dates" data-next-index="<?php echo count( $rows ) + 1; ?>">
					<?php foreach ( array_merge( $rows, array( array( 'date' => '', 'note' => '' ) ) ) as $index => $row ) { self::row( $index, $row ); } ?>
				</div>
				<template id="fisar-event-date-template"><?php self::row( '__INDEX__', array( 'date' => '', 'note' => '' ) ); ?></template>
				<p><button type="button" class="button" id="fisar-event-date-add" hidden>Aggiungi data</button></p>
				<noscript><p>Per rimuovere una data, svuota sia la data sia la nota. Salva per ottenere un’altra riga vuota.</p></noscript>
				<p id="fisar-event-date-status" role="status" aria-live="polite"></p>
			</div>
			<p id="fisar-event-date-error" class="fisar-event-date-error" role="alert" hidden></p>
			<p class="description">L’evento è concluso solo dopo l’ultima giornata. Luogo e orari dell’evento valgono per tutte le date; eventuali differenze possono essere indicate nelle note.</p>
		</div>
		<?php
	}

	private static function input( string $key, string $label, string $type, mixed $value ): void {
		echo '<div class="fisar-field"><label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label><input class="regular-text" type="' . esc_attr( $type ) . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( is_string( $value ) ? $value : '' ) . '"' . ( '_fisar_event_date' === $key ? ' required aria-required="true"' : '' ) . '></div>';
	}

	private static function row( int|string $index, array $row ): void {
		$prefix = '_fisar_event_additional_dates[' . $index . ']';
		echo '<div class="fisar-event-date-row">';
		self::input( $prefix . '[date]', 'Altra data', 'date', $row['date'] );
		self::input( $prefix . '[note]', 'Nota (facoltativa)', 'text', $row['note'] );
		echo '<button type="button" class="button-link-delete fisar-event-date-remove" hidden>Rimuovi<span class="screen-reader-text"> questa data</span></button></div>';
	}
}
