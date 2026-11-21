<?php
/** Main-panel editor for the optional refund conditions. */
final class Fisar_CDJ_Event_Cancellation_Editor {
	public static function init(): void {
		add_action( 'admin_enqueue_scripts', array( self::class, 'enqueue' ) );
	}

	public static function enqueue( string $hook ): void {
		$screen = get_current_screen();
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || ! $screen || 'fisar_evento' !== $screen->post_type ) { return; }
		$dependencies = $screen->is_block_editor() ? array( 'wp-dom-ready', 'wp-data', 'wp-editor' ) : array( 'wp-dom-ready' );
		wp_enqueue_script( 'fisar-event-cancellation', FISAR_CDJ_CORE_URL . 'assets/js/event-cancellation.js', $dependencies, FISAR_CDJ_CORE_VERSION, true );
	}

	public static function render( WP_Post $post ): void {
		$value = Fisar_CDJ_Event_Cancellation::values( $post->ID );
		$error = get_transient( Fisar_CDJ_Event_Cancellation::error_key( $post->ID ) );
		if ( $error ) { echo '<p class="notice notice-error">' . esc_html( $error ) . '</p>'; delete_transient( Fisar_CDJ_Event_Cancellation::error_key( $post->ID ) ); }
		wp_nonce_field( Fisar_CDJ_Event_Cancellation::NONCE_ACTION, Fisar_CDJ_Event_Cancellation::NONCE_NAME );
		?>
		<div id="fisar-event-cancellation-fields" data-editor="<?php echo get_current_screen()?->is_block_editor() ? 'block' : 'classic'; ?>" data-max-rules="<?php echo Fisar_CDJ_Event_Cancellation::MAX_RULES; ?>">
			<input type="hidden" name="_fisar_event_cancellation_present" value="1">
			<?php self::select( 'mode', 'Modalità delle condizioni di cancellazione', array( 'none' => 'Non specificata', 'rules' => 'Regole per scadenza', 'text' => 'Solo testo' ), $value['mode'] ); ?>
			<p class="description">Indica il rimborso della quota in caso di rinuncia del partecipante. Se non specifichi le condizioni, sul sito non compare alcuna policy.</p>
			<div data-cancellation-mode="rules">
				<p class="description">Scadenze inclusive, mostrate in ordine cronologico. L’ora è facoltativa: senza ora il termine comprende l’intera data. Gli orari seguono il fuso del sito (<?php echo esc_html( wp_timezone_string() ); ?>).</p>
				<div id="fisar-cancellation-rules" data-next-index="<?php echo count( $value['rules'] ) + 1; ?>">
					<?php foreach ( array_merge( $value['rules'], array( array( 'date' => '', 'time' => '', 'percent' => '' ) ) ) as $index => $row ) { self::row( $index, $row ); } ?>
				</div>
				<template id="fisar-cancellation-template"><?php self::row( '__INDEX__', array( 'date' => '', 'time' => '', 'percent' => '' ) ); ?></template>
				<p><button type="button" class="button" id="fisar-cancellation-add" hidden>Aggiungi scadenza</button></p>
				<noscript><p>Per rimuovere una scadenza, svuota tutti i suoi campi. Salva per ottenere un’altra riga vuota.</p></noscript>
				<?php self::select( 'after', 'Dopo l’ultima scadenza', array( '' => 'Seleziona…', 'verify' => 'Rimborso da verificare con la Delegazione', 'none' => 'Nessun rimborso', 'percent' => 'Rimborso di una percentuale specifica' ), $value['after'] ); ?>
				<div data-cancellation-after-percent><?php self::input( 'after_percent', 'Percentuale di rimborso dopo l’ultima scadenza', 'number', $value['after_percent'] ); ?></div>
				<?php self::editor( 'notes', 'Note facoltative', $value['notes'] ); ?>
			</div>
			<div data-cancellation-mode="text"><?php self::editor( 'text', 'Testo delle condizioni di cancellazione', $value['text'] ); ?></div>
			<p id="fisar-cancellation-error" class="fisar-event-date-error" role="alert" hidden></p>
			<p id="fisar-cancellation-status" role="status" aria-live="polite"></p>
		</div>
		<?php
	}

	private static function field_name( string $field ): string { return Fisar_CDJ_Event_Cancellation::META . '[' . $field . ']'; }

	private static function input( string $field, string $label, string $type, string $value ): void {
		$name = self::field_name( $field );
		echo '<div class="fisar-field"><label for="' . esc_attr( $name ) . '">' . esc_html( $label ) . '</label><input type="' . esc_attr( $type ) . '" class="regular-text" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '"' . ( 'number' === $type ? ' min="0" max="100" step="0.01"' : '' ) . '></div>';
	}

	private static function select( string $field, string $label, array $options, string $value ): void {
		$name = self::field_name( $field );
		echo '<div class="fisar-field"><label for="' . esc_attr( $name ) . '"><strong>' . esc_html( $label ) . '</strong></label><select id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '">';
		foreach ( $options as $key => $text ) { echo '<option value="' . esc_attr( $key ) . '"' . selected( $value, $key, false ) . '>' . esc_html( $text ) . '</option>'; }
		echo '</select></div>';
	}

	private static function row( int|string $index, array $row ): void {
		echo '<fieldset class="fisar-cancellation-row"><legend>Scadenza di rimborso</legend><div class="fisar-admin-grid">';
		self::input( 'rules][' . $index . '][date', 'Termine', 'date', $row['date'] );
		self::input( 'rules][' . $index . '][time', 'Ora (facoltativa)', 'time', $row['time'] );
		self::input( 'rules][' . $index . '][percent', 'Rimborso entro il termine (%)', 'number', $row['percent'] );
		echo '</div><button type="button" class="button-link-delete fisar-cancellation-remove" hidden>Rimuovi scadenza</button></fieldset>';
	}

	private static function editor( string $field, string $label, string $value ): void {
		echo '<div class="fisar-field"><p><strong>' . esc_html( $label ) . '</strong></p>';
		wp_editor( $value, 'fisar_cancellation_' . $field, array( 'textarea_name' => self::field_name( $field ), 'textarea_rows' => 4, 'teeny' => true, 'media_buttons' => false ) );
		echo '</div>';
	}
}
