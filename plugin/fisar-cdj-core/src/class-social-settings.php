<?php
/** Account configuration is private to administrators; keys never enter HTML/JS. */
final class Fisar_CDJ_Social_Settings {
	public const OPTION = 'fisar_cdj_social';
	private const KEY_OPTION = 'fisar_cdj_buffer_key';

	public static function init(): void {
		add_action( 'admin_menu', static function (): void { add_options_page( 'Pubblicazione social', 'Pubblicazione social', 'manage_options', 'fisar-social', array( self::class, 'render' ) ); } );
		add_action( 'admin_post_fisar_social_settings', array( self::class, 'save' ) );
	}

	public static function values(): array { return (array) get_option( self::OPTION, array() ); }

	public static function key(): string {
		if ( defined( 'FISAR_CDJ_BUFFER_API_KEY' ) ) { return (string) FISAR_CDJ_BUFFER_API_KEY; }
		$stored = get_option( self::KEY_OPTION, '' );
		if ( ! $stored || ! function_exists( 'openssl_decrypt' ) ) { return ''; }
		$bytes = base64_decode( $stored, true );
		if ( ! $bytes || strlen( $bytes ) < 29 ) { return ''; }
		return openssl_decrypt( substr( $bytes, 28 ), 'aes-256-gcm', hash( 'sha256', wp_salt( 'auth' ), true ), OPENSSL_RAW_DATA, substr( $bytes, 0, 12 ), substr( $bytes, 12, 16 ) ) ?: '';
	}

	private static function store_key( string $key ): bool {
		if ( ! function_exists( 'openssl_encrypt' ) ) { return false; }
		$iv = random_bytes( 12 );
		$encrypted = openssl_encrypt( $key, 'aes-256-gcm', hash( 'sha256', wp_salt( 'auth' ), true ), OPENSSL_RAW_DATA, $iv, $tag );
		if ( false === $encrypted ) { return false; }
		update_option( self::KEY_OPTION, base64_encode( $iv . $tag . $encrypted ), false );
		return true;
	}

	public static function save(): void {
		if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Accesso non consentito.', 403 ); }
		check_admin_referer( 'fisar_social_settings' );
		$settings = self::values();
		$message = 'Impostazioni salvate.';
		if ( isset( $_POST['disconnect'] ) ) {
			delete_option( self::KEY_OPTION ); delete_option( self::OPTION );
			$message = defined( 'FISAR_CDJ_BUFFER_API_KEY' ) ? 'Rimuovi anche la costante FISAR_CDJ_BUFFER_API_KEY da wp-config.php per scollegare la chiave.' : 'Buffer scollegato.';
		} else {
			$key = isset( $_POST['api_key'] ) && is_string( $_POST['api_key'] ) ? trim( wp_unslash( $_POST['api_key'] ) ) : '';
			if ( $key && ! defined( 'FISAR_CDJ_BUFFER_API_KEY' ) ) {
				if ( preg_match( '/\s/', $key ) || ! self::store_key( $key ) ) { wp_die( 'Impossibile salvare la chiave API in modo sicuro.' ); }
				$settings = array();
			}
			if ( isset( $_POST['discover'] ) || $key ) {
				$channels = Fisar_CDJ_Buffer_Client::discover_channels();
				if ( is_wp_error( $channels ) ) { $message = $channels->get_error_message(); }
				else { $settings['channels'] = $channels; $message = $channels ? 'Collegamenti verificati. Scegli gli account da usare e salva.' : 'Nessuna pagina Facebook o profilo Instagram trovato. Collega prima i social in Buffer, poi verifica di nuovo.'; }
			} else {
				foreach ( array( 'facebook', 'instagram' ) as $service ) {
					$id = isset( $_POST[ $service ] ) && is_string( $_POST[ $service ] ) ? wp_unslash( $_POST[ $service ] ) : '';
					$settings[ $service ] = '';
					foreach ( $settings['channels'] ?? array() as $channel ) {
						if ( $channel['service'] === $service && $channel['id'] === $id && $channel['available'] ) { $settings[ $service ] = $id; }
					}
				}
				$settings['live'] = isset( $_POST['live'] );
			}
			update_option( self::OPTION, $settings, false );
		}
		set_transient( 'fisar_social_notice_' . get_current_user_id(), $message, 60 );
		wp_safe_redirect( admin_url( 'options-general.php?page=fisar-social' ) ); exit;
	}

	public static function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) { return; }
		$settings = self::values();
		$message = get_transient( 'fisar_social_notice_' . get_current_user_id() );
		?>
		<div class="wrap"><h1>Pubblicazione social</h1>
		<?php if ( $message ) { echo '<div class="notice notice-info"><p>' . esc_html( $message ) . '</p></div>'; delete_transient( 'fisar_social_notice_' . get_current_user_id() ); } ?>
		<p>Collega Facebook e Instagram al tuo account Buffer. Poi crea una chiave API nelle <a href="https://publish.buffer.com/settings/api" target="_blank" rel="noopener noreferrer">impostazioni Buffer</a> e inseriscila qui.</p>
		<p>Le foto restano nella libreria WordPress. Nessun articolo viene condiviso automaticamente: la pubblicazione parte solo dal pannello dell’articolo.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<input type="hidden" name="action" value="fisar_social_settings">
			<?php wp_nonce_field( 'fisar_social_settings' ); ?>
			<p><strong>Stato:</strong> <?php echo self::key() ? 'Chiave configurata' : 'Buffer non collegato'; ?></p>
			<?php if ( ! defined( 'FISAR_CDJ_BUFFER_API_KEY' ) ) : ?>
			<p><label for="fisar-buffer-key"><strong>Chiave API Buffer</strong></label><br><input id="fisar-buffer-key" type="password" name="api_key" class="large-text" autocomplete="new-password" value="" aria-describedby="fisar-buffer-key-help"></p>
			<p id="fisar-buffer-key-help">Lascia vuoto per conservare la chiave attuale. La chiave è cifrata nel database e non viene mostrata nell’editor.</p>
			<?php else : ?><p>Chiave configurata tramite wp-config.php.</p><?php endif; ?>
			<p><button class="button" name="discover" value="1">Salva chiave e verifica collegamenti</button></p>
			<p><label><input type="checkbox" name="live" value="1"<?php checked( ! empty( $settings['live'] ) ); ?>> <strong>Abilita pubblicazione reale sui social</strong></label></p>
			<p>Se disattivato, il pulsante nell’articolo esegue solo una simulazione: nessun contenuto viene inviato a Buffer. La simulazione funziona anche senza un account collegato.</p>
			<table class="form-table"><tbody>
			<?php foreach ( Fisar_CDJ_Social_Publishing::NETWORKS as $service => $network ) : ?>
			<tr><th><label for="fisar-buffer-<?php echo esc_attr( $service ); ?>"><?php echo esc_html( $network['label'] ); ?></label></th><td><select id="fisar-buffer-<?php echo esc_attr( $service ); ?>" name="<?php echo esc_attr( $service ); ?>"><option value="">Non utilizzare</option>
			<?php foreach ( $settings['channels'] ?? array() as $channel ) : if ( $service !== $channel['service'] ) { continue; } ?>
			<option value="<?php echo esc_attr( $channel['id'] ); ?>"<?php selected( $settings[ $service ] ?? '', $channel['id'] ); disabled( ! $channel['available'] ); ?>><?php echo esc_html( $channel['name'] . ( $channel['available'] ? '' : ' — da ricollegare in Buffer' ) ); ?></option>
			<?php endforeach; ?></select></td></tr>
			<?php endforeach; ?></tbody></table>
			<p><button class="button button-primary">Salva account selezionati</button> <button class="button" name="disconnect" value="1">Scollega Buffer</button></p>
		</form></div>
		<?php
	}
}
