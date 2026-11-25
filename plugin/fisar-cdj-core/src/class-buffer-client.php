<?php
/** Small server-side client for Buffer's current GraphQL API. */
final class Fisar_CDJ_Buffer_Client {
	private const ENDPOINT = 'https://api.buffer.com';
	public const POST_FIELDS = 'id status dueAt externalLink error { message }';

	/** Never expose the key, request headers or raw response bodies to the editor. */
	public static function request( string $query, array $variables = array() ): array|WP_Error {
		$key = Fisar_CDJ_Social_Settings::key();
		if ( ! $key ) { return new WP_Error( 'buffer_not_connected', 'Collega prima Buffer nelle impostazioni Pubblicazione social.' ); }
		$response = wp_remote_post( self::ENDPOINT, array(
			'timeout' => 35,
			'redirection' => 0,
			'headers' => array( 'Authorization' => 'Bearer ' . $key, 'Content-Type' => 'application/json' ),
			'body' => wp_json_encode( array( 'query' => $query, 'variables' => (object) $variables ) ),
		) );
		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'buffer_uncertain', 'Buffer non ha risposto. L’operazione potrebbe essere stata ricevuta: verifica l’esito prima di riprovare.' );
		}
		$code = wp_remote_retrieve_response_code( $response );
		if ( in_array( $code, array( 401, 403 ), true ) ) { return new WP_Error( 'buffer_access', 'Buffer ha rifiutato l’accesso. Verifica la chiave API e le autorizzazioni.' ); }
		if ( 429 === $code ) { return new WP_Error( 'buffer_limit', 'Hai raggiunto un limite di Buffer. Attendi prima di riprovare.' ); }
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( $code < 200 || $code >= 300 || ! is_array( $data ) || ! empty( $data['errors'] ) || ! isset( $data['data'] ) ) {
			return new WP_Error( 'buffer_uncertain', 'Buffer ha restituito una risposta non utilizzabile. Controlla l’esito in Buffer prima di riprovare.' );
		}
		return $data['data'];
	}

	public static function discover_channels(): array|WP_Error {
		$result = self::request( 'query { account { organizations { id name } } }' );
		if ( is_wp_error( $result ) ) { return $result; }
		$channels = array();
		foreach ( $result['account']['organizations'] ?? array() as $organization ) {
			$result = self::request( 'query($input: ChannelsInput!) { channels(input: $input) { id name displayName service type isDisconnected isLocked } }', array( 'input' => array( 'organizationId' => $organization['id'] ) ) );
			if ( is_wp_error( $result ) ) { return $result; }
			foreach ( $result['channels'] ?? array() as $channel ) {
				if ( ( 'facebook' === $channel['service'] && 'page' === $channel['type'] ) || 'instagram' === $channel['service'] ) {
					$channels[] = array(
						'id' => (string) $channel['id'], 'service' => $channel['service'],
						'name' => sanitize_text_field( ( $channel['displayName'] ?: $channel['name'] ) . ' — ' . $organization['name'] ),
						'available' => ! $channel['isDisconnected'] && ! $channel['isLocked'],
					);
				}
			}
		}
		return $channels;
	}

	public static function create_post( array $input ): array|WP_Error {
		$result = self::request( 'mutation($input: CreatePostInput!) { createPost(input: $input) { __typename ... on PostActionSuccess { post { ' . self::POST_FIELDS . ' } } ... on MutationError { message } } }', array( 'input' => $input ) );
		if ( is_wp_error( $result ) ) { return $result; }
		$action = $result['createPost'] ?? array();
		if ( 'PostActionSuccess' === ( $action['__typename'] ?? '' ) && ! empty( $action['post']['id'] ) ) { return $action['post']; }
		if ( ! empty( $action['message'] ) ) {
			return new WP_Error( 'buffer_rejected', 'Buffer non ha creato il post: ' . self::safe_message( $action['message'] ) );
		}
		return new WP_Error( 'buffer_uncertain', 'Esito non riconosciuto: verifica in Buffer prima di riprovare.' );
	}

	public static function post( string $id ): array|WP_Error {
		$result = self::request( 'query($input: PostInput!) { post(input: $input) { ' . self::POST_FIELDS . ' } }', array( 'input' => array( 'id' => $id ) ) );
		return is_wp_error( $result ) ? $result : ( $result['post'] ?? new WP_Error( 'buffer_missing', 'Post non trovato in Buffer.' ) );
	}

	public static function safe_message( string $message ): string {
		$key = Fisar_CDJ_Social_Settings::key();
		return mb_substr( sanitize_text_field( $key ? str_replace( $key, '[chiave riservata]', $message ) : $message ), 0, 600 );
	}
}
