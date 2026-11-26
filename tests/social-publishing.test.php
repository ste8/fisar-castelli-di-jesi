<?php
/** Local integration only: all HTTP is intercepted, no social posts are ever sent. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) { throw new RuntimeException( 'Run only in local WordPress with WP-CLI.' ); }
$created = array(); $images = array(); $calls = array(); $behavior = 'success';
$original_user = get_current_user_id(); $original_settings = get_option( Fisar_CDJ_Social_Settings::OPTION, null ); $original_key = get_option( 'fisar_cdj_buffer_key', null );
$check = static function ( bool $ok, string $message ): void { if ( ! $ok ) { throw new RuntimeException( $message ); } };
$mock = static function ( $preempt, $args, $url ) use ( &$calls, &$behavior ): array|WP_Error {
	if ( 'https://api.buffer.com' !== $url ) { return new WP_Error( 'test_block', 'External HTTP blocked by test.' ); }
	$payload = json_decode( $args['body'], true ); $calls[] = $payload;
	$query = $payload['query']; $data = array();
	if ( str_contains( $query, 'account {' ) ) { $data = array( 'account' => array( 'organizations' => array( array( 'id' => 'org', 'name' => 'Associazione' ) ) ) ); }
	elseif ( str_contains( $query, 'channels(' ) ) { $data = array( 'channels' => array(
		array( 'id' => 'fb', 'name' => 'Pagina FISAR', 'displayName' => 'Pagina FISAR', 'service' => 'facebook', 'type' => 'page', 'isDisconnected' => false, 'isLocked' => false ),
		array( 'id' => 'ig', 'name' => 'fisar', 'displayName' => 'fisar', 'service' => 'instagram', 'type' => 'business', 'isDisconnected' => false, 'isLocked' => false ),
		array( 'id' => 'private-profile', 'name' => 'Profilo', 'displayName' => 'Profilo', 'service' => 'facebook', 'type' => 'profile', 'isDisconnected' => false, 'isLocked' => false ),
	) ); }
	elseif ( str_contains( $query, 'createPost(' ) ) {
		if ( 'timeout' === $behavior ) { return new WP_Error( 'timeout', 'No response.' ); }
		$id = $payload['variables']['input']['channelId'];
		if ( 'reject_ig' === $behavior && 'ig' === $id ) { $data = array( 'createPost' => array( '__typename' => 'InvalidInputError', 'message' => 'Account Instagram da ricollegare.' ) ); }
		elseif ( 'http500' === $behavior ) { return array( 'response' => array( 'code' => 500 ), 'body' => 'bad', 'headers' => array() ); }
		else { $data = array( 'createPost' => array( '__typename' => 'PostActionSuccess', 'post' => array( 'id' => 'remote-' . $id, 'status' => 'scheduled', 'dueAt' => '2027-04-01T10:00:00Z', 'externalLink' => null, 'error' => null ) ) ); }
	} elseif ( str_contains( $query, 'post(input:' ) ) {
		$id = $payload['variables']['input']['id'];
		$data = array( 'post' => array( 'id' => $id, 'status' => 'sent', 'dueAt' => null, 'externalLink' => str_ends_with( $id, 'fb' ) ? 'https://www.facebook.com/posts/test-fixture' : 'https://www.instagram.com/p/test-fixture/', 'error' => null ) );
	} else { throw new RuntimeException( 'Unexpected API operation.' ); }
	return array( 'response' => array( 'code' => 200 ), 'body' => wp_json_encode( array( 'data' => $data ) ), 'headers' => array() );
};
$public_home = static fn() => 'https://www.fisarcastellidijesi.it';
$media_urls = static fn( $url, $id ) => in_array( $id, $GLOBALS['fisar_social_test_images'] ?? array(), true ) ? 'https://www.fisarcastellidijesi.it/anteprima/wp-content/uploads/social-fixture-' . $id . '.jpg' : $url;
$api = static function ( int $id, array $data ) {
	$request = new WP_REST_Request( 'POST', '/fisar-cdj/v1/social/' . $id ); $request->set_header( 'content-type', 'application/json' ); $request->set_body( wp_json_encode( $data ) ); return rest_do_request( $request );
};
try {
	$admin = get_users( array( 'role' => 'administrator', 'number' => 1 ) )[0]; wp_set_current_user( $admin->ID );
	add_filter( 'pre_http_request', $mock, 10, 3 );
	$store_key = new ReflectionMethod( Fisar_CDJ_Social_Settings::class, 'store_key' );
	$check( $store_key->invoke( null, 'fixture-secret-do-not-display' ) && 'fixture-secret-do-not-display' === Fisar_CDJ_Social_Settings::key(), 'Key encrypted at rest and usable only on server.' );
	$check( ! str_contains( get_option( 'fisar_cdj_buffer_key' ), 'fixture-secret' ), 'No plaintext key stored.' );
	$channels = Fisar_CDJ_Buffer_Client::discover_channels(); $check( 2 === count( $channels ), 'Discovery includes Facebook Pages and Instagram, not personal Facebook profiles.' );
	update_option( Fisar_CDJ_Social_Settings::OPTION, array( 'facebook' => 'fb', 'instagram' => 'ig', 'channels' => $channels, 'live' => false ), false );
	foreach ( range( 1, 21 ) as $i ) {
		$picture = imagecreatetruecolor( 90, 120 ); imagefill( $picture, 0, 0, imagecolorallocate( $picture, 120, 30, 50 ) );
		ob_start(); imagejpeg( $picture ); $jpg = ob_get_clean(); imagedestroy( $picture );
		$upload = wp_upload_bits( 'fisar-social-test-' . $i . '-' . wp_generate_uuid4() . '.jpg', null, $jpg );
		$id = wp_insert_attachment( array( 'post_title' => 'FisarSocialFixture foto ' . $i, 'post_mime_type' => 'image/jpeg', 'post_status' => 'inherit' ) );
		$images[] = $id; update_attached_file( $id, $upload['file'] ); wp_update_attachment_metadata( $id, array( 'width' => 90, 'height' => 120 ) );
	}
	$GLOBALS['fisar_social_test_images'] = $images;
	add_filter( 'wp_get_attachment_url', $media_urls, 10, 2 ); add_filter( 'option_home', $public_home );
	$blocks = implode( '', array_map( static fn( $id ) => '<!-- wp:image {"id":' . $id . '} --><figure><img class="wp-image-' . $id . '"/></figure><!-- /wp:image -->', array_slice( $images, 0, 12 ) ) );
	$id = wp_insert_post( array( 'post_type' => 'post', 'post_status' => 'draft', 'post_title' => 'FisarSocialFixture articolo', 'post_content' => '<p>Testo originale.</p><ul><li>Una voce</li></ul>' . $blocks ), true ); $created[] = $id;
	$post = get_post( $id ); $before = get_post( $id, ARRAY_A );
	ob_start(); Fisar_CDJ_Social_Editor::render( $post ); $markup = ob_get_clean();
	$check( str_contains( $markup, 'id="fisar-social-open"' ) && str_contains( $markup, '<dialog id="fisar-social-dialog"' ) && strpos( $markup, 'data-social-content' ) > strpos( $markup, '<dialog' ), 'The editor contains a compact launcher; all preparation controls belong to the labelled dialog.' );
	$check( array() === Fisar_CDJ_Social_Publishing::deliveries( $id ), 'No phantom delivery row before the first send.' );
	$check( 12 === count( Fisar_CDJ_Social_Publishing::article_images( $post ) ), 'Gallery collected without silently discarding images or duplicating IDs.' );
	$composer = Fisar_CDJ_Social_Publishing::composer( $post );
	$check( 12 === count( $composer['images'] ) && str_contains( $composer['text'], '• Una voce' ), 'One shared selection keeps all images, text preserves semantic lists.' );
	$check( ! str_contains( $composer['text'], '<p>' ), 'Caption is plain text.' );
	$check( ! str_contains( $composer['text'], get_permalink( $post ) ), 'Suggested caption does not append the article permalink.' );
	$legacy = array( 'networks' => array( 'facebook' => array( 'enabled' => true, 'text' => 'Preparazione già salvata', 'images' => array_slice( $images, 0, 11 ) ), 'instagram' => array( 'enabled' => true, 'text' => 'Vecchia variante', 'images' => array_slice( $images, 0, 12 ) ) ), 'mode' => 'now', 'scheduled_at' => '' );
	update_post_meta( $id, Fisar_CDJ_Social_Publishing::COMPOSER_META, $legacy );
	$migrated = Fisar_CDJ_Social_Publishing::composer( $post );
	$check( 'Preparazione già salvata' === $migrated['text'] && 11 === count( $migrated['images'] ) && true === $migrated['networks']['instagram'], 'First local prototype preparation remains available as one shared post without truncation.' );
	delete_post_meta( $id, Fisar_CDJ_Social_Publishing::COMPOSER_META );
	$payload = array( 'intent' => 'save', 'version' => Fisar_CDJ_Social_Editor::version( $post ), 'composer' => $composer );
	$count = count( $calls );
	$check( 200 === $api( $id, $payload )->get_status() && $count === count( $calls ), 'Preparation with 12 photos can be saved, with no API call.' );
	$payload['intent'] = 'send'; $response = $api( $id, $payload );
	$check( 'social_image_limit' === $response->get_data()['code'] && $count === count( $calls ), 'Facebook over limit blocks send, not article or preparation save.' );
	$composer['images'] = array_slice( $images, 0, 10 );
	$validation = Fisar_CDJ_Social_Publishing::validate_send( $post, $composer, false );
	$check( ! is_wp_error( $validation ), 'A shared selection of 10 photos works for both destinations. ' . ( is_wp_error( $validation ) ? $validation->get_error_message() : '' ) );
	$tagged = $composer; $tagged['instagram_tags'] = array(
		array( 'imageId' => $images[0], 'handle' => ' @Example.Person ', 'x' => 0.25, 'y' => 0.75 ),
		array( 'imageId' => $images[9], 'handle' => 'second_person', 'x' => 1, 'y' => 0 ),
	);
	$tagged = Fisar_CDJ_Social_Publishing::validate_composer( $tagged, $post );
	$check( ! is_wp_error( $tagged ) && 'example.person' === $tagged['instagram_tags'][0]['handle'], 'Normalize Instagram handles while preserving photo IDs and normalized positions.' );
	$tag_save = $payload; $tag_save['intent'] = 'save'; $tag_save['composer'] = $tagged;
	$check( 200 === $api( $id, $tag_save )->get_status() && $count === count( $calls ), 'Saving photo tags never sends a post.' );
	$check( $tagged['instagram_tags'] === Fisar_CDJ_Social_Publishing::composer( $post )['instagram_tags'], 'Saved tags survive loading an existing preparation.' );
	$tag_state = rest_do_request( new WP_REST_Request( 'GET', '/fisar-cdj/v1/social/' . $id ) )->get_data();
	$check( $tagged['instagram_tags'] === $tag_state['composer']['instagram_tags'] && 20 === $tag_state['instagramTagLimit'], 'The authenticated editor can reload tags and the common post limit.' );
	$reordered = $tagged; $reordered['images'] = array_reverse( $tagged['images'] );
	$ig_tags = Fisar_CDJ_Social_Publishing::payload( 'instagram', 'ig', $reordered );
	$check( 'second_person' === $ig_tags['assets'][0]['image']['metadata']['userTags'][0]['handle'] && 'example.person' === $ig_tags['assets'][9]['image']['metadata']['userTags'][0]['handle'], 'Tags follow the image ID after reordering, not its former position.' );
	$check( 0.25 === $ig_tags['assets'][9]['image']['metadata']['userTags'][0]['x'] && 0.0 === $ig_tags['assets'][0]['image']['metadata']['userTags'][0]['y'] && isset( $ig_tags['assets'][9]['image']['metadata']['altText'] ), 'Instagram receives numeric normalized coordinates alongside alt text.' );
	$check( ! isset( $ig_tags['assets'][1]['image']['metadata']['userTags'] ) && $composer['text'] === $ig_tags['text'], 'Tags do not spread to other photos or change the shared caption.' );
	$fb_tags = Fisar_CDJ_Social_Publishing::payload( 'facebook', 'fb', $tagged );
	$check( ! str_contains( wp_json_encode( $fb_tags ), 'userTags' ) && $composer['text'] === $fb_tags['text'], 'Facebook payloads and captions never contain Instagram photo tags.' );
	foreach ( array(
		array( 'imageId' => $images[10] ), array( 'imageId' => $id ), array( 'handle' => 'bad name' ), array( 'handle' => 'https://instagram.com/name' ), array( 'handle' => 'bad..name' ), array( 'handle' => str_repeat( 'a', 31 ) ),
		array( 'x' => '0.5' ), array( 'x' => -0.1 ), array( 'y' => 1.01 ), array( 'x' => NAN ), array( 'y' => INF ), array( 'x' => true ),
	) as $change ) {
		$bad_tag = $tagged; $bad_tag['instagram_tags'][0] = array_replace( $bad_tag['instagram_tags'][0], $change );
		$check( is_wp_error( Fisar_CDJ_Social_Publishing::validate_composer( $bad_tag, $post ) ), 'Reject invalid handles, positions and tags on unselected/foreign photos.' );
	}
	$bad_tag = $tagged; $bad_tag['instagram_tags'][] = $tagged['instagram_tags'][0];
	$check( is_wp_error( Fisar_CDJ_Social_Publishing::validate_composer( $bad_tag, $post ) ), 'Reject duplicate accounts on the same photo.' );
	$bad_tag['instagram_tags'] = array( 'unexpected' => $tagged['instagram_tags'][0] );
	$check( is_wp_error( Fisar_CDJ_Social_Publishing::validate_composer( $bad_tag, $post ) ), 'Reject malformed tag lists.' );
	$many_tags = $composer; $many_tags['instagram_tags'] = array_map( static fn( $n ) => array( 'imageId' => $images[0], 'handle' => 'person_' . $n, 'x' => 0.5, 'y' => 0.5 ), range( 1, 21 ) );
	$check( ! is_wp_error( Fisar_CDJ_Social_Publishing::validate_composer( $many_tags, $post ) ) && 'social_instagram_tag_limit' === Fisar_CDJ_Social_Publishing::validate_send( $post, $many_tags, false )->get_error_code(), 'An over-limit preparation can be saved but Instagram cannot be sent.' );
	$many_tags['networks']['instagram'] = false;
	$check( ! is_wp_error( Fisar_CDJ_Social_Publishing::validate_send( $post, $many_tags, false ) ), 'Inactive Instagram tags cannot block a Facebook-only send.' );
	$many_tags['instagram_tags'] = array_slice( $many_tags['instagram_tags'], 0, 20 ); $many_tags['networks']['instagram'] = true;
	$check( ! is_wp_error( Fisar_CDJ_Social_Publishing::validate_send( $post, $many_tags, false ) ), 'Exactly 20 tags are accepted.' );
	$legacy_tags = $composer; unset( $legacy_tags['instagram_tags'] );
	$check( array() === Fisar_CDJ_Social_Publishing::validate_composer( $legacy_tags, $post )['instagram_tags'], 'Existing preparations without tags remain valid.' );
	$first_media = Fisar_CDJ_Social_Publishing::media( $images[0] );
	$check( 1080 === $first_media['width'] && 1080 === $first_media['height'] && str_contains( $first_media['url'], '-social.jpg' ), 'The real generated JPEG, not the uploaded original, supplies validation dimensions.' );
	$source_file = get_attached_file( $images[0] ); rename( $source_file, $source_file . '.fixture-away' );
	try {
		$check( is_wp_error( Fisar_CDJ_Social_Publishing::validate_send( $post, $composer, false ) ) && is_wp_error( Fisar_CDJ_Social_Publishing::payload( 'instagram', 'ig', $composer ) ), 'A missing source blocks preparation and payload instead of falling back to the original URL.' );
	} finally { rename( $source_file . '.fixture-away', $source_file ); }
	$payload['composer'] = $composer;
	$check( ! empty( $api( $id, $payload )->get_data()['simulation'] ) && $count === count( $calls ) && ! get_post_meta( $id, Fisar_CDJ_Social_Publishing::DELIVERY_META, true ), 'Simulation needs no account and never sends or writes a delivery.' );
	$instagram_only = $composer; $instagram_only['networks']['facebook'] = false;
	$check( ! is_wp_error( Fisar_CDJ_Social_Publishing::validate_send( $post, $instagram_only, false ) ), 'Instagram alone accepts the common maximum of 10 photos.' );
	$bad = $instagram_only; $bad['images'] = array_slice( $images, 0, 11 );
	$check( 'social_image_limit' === Fisar_CDJ_Social_Publishing::validate_send( $post, $bad, false )->get_error_code(), 'Instagram alone rejects 11 photos.' );
	$over_limit_payload = $payload; $over_limit_payload['composer'] = $bad;
	$check( 'social_image_limit' === $api( $id, $over_limit_payload )->get_data()['code'] && $count === count( $calls ), 'Direct REST sends cannot bypass the 10-photo limit for Instagram alone.' );
	foreach ( array( '2201 chars' => str_repeat( 'è', 2201 ), 'empty' => '' ) as $text ) { $bad = $composer; $bad['text'] = $text; $check( is_wp_error( Fisar_CDJ_Social_Publishing::validate_send( $post, $bad, false ) ), 'The shared caption respects every selected channel limit.' ); }
	foreach ( array( null, 'bad', array( 'networks' => array() ), array_replace_recursive( $composer, array( 'networks' => array( 'facebook' => array( 'images' => array( '<script>' ) ) ) ) ) ) as $bad ) { $check( is_wp_error( Fisar_CDJ_Social_Publishing::validate_composer( $bad, $post ) ), 'Malformed input rejected.' ); }
	$bad = $composer; $bad['images'] = array( $id ); $check( is_wp_error( Fisar_CDJ_Social_Publishing::validate_composer( $bad, $post ) ), 'Non-attachment image ID rejected.' );
	$bad = $composer; $bad['images'] = array( $images[12] );
	$check( 'social_article_images' === Fisar_CDJ_Social_Publishing::validate_composer( $bad, $post )->get_error_code(), 'A valid library image outside this article cannot be selected.' );
	$check( 'social_article_images' === Fisar_CDJ_Social_Publishing::validate_send( $post, $bad, false )->get_error_code(), 'Direct sends enforce the same article-only image restriction.' );
	$bad_payload = $payload; $bad_payload['composer'] = $bad; $bad_payload['intent'] = 'save';
	$check( 'social_article_images' === $api( $id, $bad_payload )->get_data()['code'] && $count === count( $calls ), 'REST saves cannot bypass the article-only picker.' );
	$reordered = $composer; $reordered['images'] = array_reverse( $composer['images'] );
	$check( $reordered['images'] === Fisar_CDJ_Social_Publishing::validate_composer( $reordered, $post )['images'], 'Article photos retain the chosen social ordering.' );
	$check( $before === get_post( $id, ARRAY_A ), 'Social saves do not alter title, body, dates, status or native excerpt.' );
	$payload['version'] = 'stale'; $check( 409 === $api( $id, $payload )->get_status(), 'Stale article version cannot send.' );
	$payload['version'] = Fisar_CDJ_Social_Editor::version( get_post( $id ) );
	wp_set_current_user( 0 ); $check( $api( $id, $payload )->get_status() >= 400, 'Anonymous requests cannot prepare or publish.' ); wp_set_current_user( $admin->ID );
	// Production flow is exercised only against the intercept above, never the real API.
	$settings = Fisar_CDJ_Social_Settings::values(); $settings['live'] = true; update_option( Fisar_CDJ_Social_Settings::OPTION, $settings, false );
	$check( is_wp_error( Fisar_CDJ_Social_Publishing::send( get_post( $id ), $composer ) ), 'Live publication refuses a draft.' );
	wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) );
	update_post_meta( $id, '_fisar_post_unlisted', 1 ); $check( is_wp_error( Fisar_CDJ_Social_Publishing::send( get_post( $id ), $composer ) ), 'Unlisted posts cannot be sent live.' ); delete_post_meta( $id, '_fisar_post_unlisted' );
	$scheduled = $composer; $scheduled['mode'] = 'scheduled'; $scheduled['scheduled_at'] = '2030-07-01T21:15';
	$input = Fisar_CDJ_Social_Publishing::payload( 'instagram', 'ig', $scheduled );
	$check( '2030-07-01T19:15:00Z' === $input['dueAt'] && 'customScheduled' === $input['mode'] && 10 === count( $input['assets'] ) && true === $input['metadata']['instagram']['shouldShareToFeed'], 'Scheduling converts site timezone to UTC, preserves image order and uses automatic feed publishing.' );
	$facebook_input = Fisar_CDJ_Social_Publishing::payload( 'facebook', 'fb', $scheduled );
	$check( $facebook_input['text'] === $input['text'] && $facebook_input['assets'] !== $input['assets'], 'Facebook and Instagram keep one caption while receiving different derivative files.' );
	foreach ( array( 'facebook' => $facebook_input, 'social' => $input ) as $suffix => $network_input ) {
		$asset_ids = array();
		foreach ( $network_input['assets'] as $asset ) {
			$url = $asset['image']['url']; preg_match( '/fisar-(\d+)-/', $url, $match ); $asset_ids[] = (int) ( $match[1] ?? 0 );
			$check( str_ends_with( $url, '-' . $suffix . '.jpg' ), 'Each payload uses only its own network derivative, never the other preview or original.' );
		}
		$check( $scheduled['images'] === $asset_ids, 'The two network-specific file sets preserve the same selected image order.' );
	}
	$check( 'shareNow' === Fisar_CDJ_Social_Publishing::payload( 'facebook', 'fb', $composer )['mode'], 'Now publishes directly, not via a draft or notification.' );
	add_option( 'fisar_social_lock_' . $id, time() . ':other-request', '', false );
	$check( is_wp_error( Fisar_CDJ_Social_Publishing::send( get_post( $id ), $composer ) ) && is_wp_error( Fisar_CDJ_Social_Publishing::refresh( $id ) ), 'Publication and refresh share a lock so neither can overwrite a simultaneous delivery.' );
	delete_option( 'fisar_social_lock_' . $id );
	$behavior = 'reject_ig'; $result = Fisar_CDJ_Social_Publishing::send( get_post( $id ), $composer );
	$check( 'remote-fb' === $result['delivery']['facebook']['id'] && 'rejected' === $result['delivery']['instagram']['status'], 'Partial success is stored per network.' );
	$mutations = count( array_filter( $calls, static fn( $call ) => str_contains( $call['query'], 'createPost(' ) ) );
	$behavior = 'success'; Fisar_CDJ_Social_Publishing::send( get_post( $id ), $composer );
	$check( $mutations + 1 === count( array_filter( $calls, static fn( $call ) => str_contains( $call['query'], 'createPost(' ) ) ), 'Retry sends only rejected destination, never the successful Facebook post.' );
	$refreshed = Fisar_CDJ_Social_Publishing::refresh( $id ); $check( 'sent' === $refreshed['instagram']['status'] && 'https://www.instagram.com/p/test-fixture/' === get_post_meta( $id, '_fisar_report_instagram_url', true ), 'Refresh distinguishes accepted from published, and fills social metadata only after successful publication.' );
	update_post_meta( $id, '_fisar_report_facebook_url', 'https://www.facebook.com/posts/original-source' ); Fisar_CDJ_Social_Publishing::refresh( $id ); $check( str_ends_with( get_post_meta( $id, '_fisar_report_facebook_url', true ), 'original-source' ), 'Existing source URLs are preserved.' );
	delete_post_meta( $id, Fisar_CDJ_Social_Publishing::DELIVERY_META ); $behavior = 'timeout';
	$result = Fisar_CDJ_Social_Publishing::send( get_post( $id ), $composer ); $check( 'unknown' === $result['delivery']['facebook']['status'], 'Timeout is uncertain, not safely failed.' );
	$mutations = count( array_filter( $calls, static fn( $call ) => str_contains( $call['query'], 'createPost(' ) ) ); Fisar_CDJ_Social_Publishing::send( get_post( $id ), $composer );
	$check( $mutations === count( array_filter( $calls, static fn( $call ) => str_contains( $call['query'], 'createPost(' ) ) ), 'Ambiguous requests cannot duplicate on retry.' );
	delete_post_meta( $id, Fisar_CDJ_Social_Publishing::DELIVERY_META ); $behavior = 'http500';
	$result = Fisar_CDJ_Social_Publishing::send( get_post( $id ), $composer );
	$check( 'unknown' === $result['delivery']['instagram']['status'], 'HTTP 500 is ambiguous and cannot silently retry.' );
	$state_request = new WP_REST_Request( 'GET', '/fisar-cdj/v1/social/' . $id ); $state = rest_do_request( $state_request );
	$check( ! str_contains( wp_json_encode( $state->get_data() ), 'fixture-secret' ), 'Editor never receives API key.' );
	$check( count( $calls ) > 0, 'API mock was used.' );
	WP_CLI::success( 'Social: galleries/limits, simulation, private posts, authorization, encrypted key, stale data, API payloads, timezone, partial failures, duplicate prevention, ambiguous timeouts and original content preserved. No real API calls.' );
} finally {
	remove_filter( 'pre_http_request', $mock, 10 ); remove_filter( 'option_home', $public_home ); remove_filter( 'wp_get_attachment_url', $media_urls, 10 );
	foreach ( $created as $id ) { delete_option( 'fisar_social_lock_' . $id ); wp_delete_post( $id, true ); }
	foreach ( $images as $id ) { wp_delete_attachment( $id, true ); }
	foreach ( array( Fisar_CDJ_Social_Settings::OPTION => $original_settings, 'fisar_cdj_buffer_key' => $original_key ) as $key => $value ) { if ( null === $value ) { delete_option( $key ); } else { update_option( $key, $value, false ); } }
	wp_set_current_user( $original_user ); unset( $GLOBALS['fisar_social_test_images'] );
}
