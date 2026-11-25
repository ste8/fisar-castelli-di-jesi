<?php
/** Actual pixel tests and rendered markup; no external requests or production fixtures. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) { throw new RuntimeException( 'Local WP-CLI only.' ); }
$ids = array();
$check = static function ( bool $ok, string $message ): void { if ( ! $ok ) { throw new RuntimeException( $message ); } };
$near = static function ( array $color, int $r, int $g, int $b ): bool { return abs( $color['red'] - $r ) < 15 && abs( $color['green'] - $g ) < 15 && abs( $color['blue'] - $b ) < 15; };
try {
	foreach ( array( array( 5712, 4284 ), array( 1200, 1600 ), array( 2000, 300 ), array( 90, 120 ) ) as $dimensions ) {
		list( $width, $height ) = $dimensions;
		$picture = imagecreatetruecolor( $width, $height );
		imagefill( $picture, 0, 0, imagecolorallocate( $picture, 30, 150, 60 ) );
		$edge = max( 10, (int) ( $width / 10 ) );
		imagefilledrectangle( $picture, 0, 0, $edge, $height, imagecolorallocate( $picture, 240, 30, 30 ) );
		imagefilledrectangle( $picture, $width - $edge, 0, $width - 1, $height - 1, imagecolorallocate( $picture, 30, 30, 240 ) );
		ob_start(); imagejpeg( $picture, null, 90 ); $jpg = ob_get_clean(); imagedestroy( $picture );
		$upload = wp_upload_bits( 'fisar-media-test-' . wp_generate_uuid4() . '.jpg', null, $jpg );
		$id = wp_insert_attachment( array( 'post_title' => 'FisarMediaFixture', 'post_mime_type' => 'image/jpeg' ), $upload['file'] ); $ids[] = $id;
		// Deliberately mismatched metadata reproduces the original-file/scaled-file regression.
		wp_update_attachment_metadata( $id, array( 'width' => 2560, 'height' => 1920, 'original_image' => 'other-original.jpg' ) );
		$hash = hash_file( 'sha256', $upload['file'] );
		$pending = Fisar_CDJ_Media_Variants::web( $id, false );
		$check( is_wp_error( $pending ) && wp_next_scheduled( 'fisar_cdj_prepare_web_image', array( $id ) ) && ! get_post_meta( $id, '_fisar_media_variants', true ), 'A first public visit queues web preparation without processing images or blocking the page.' );
		$social = Fisar_CDJ_Media_Variants::social( $id );
		$check( ! is_wp_error( $social ) && 1080 === $social['width'] && 1080 === $social['height'] && $social['bytes'] < MB_IN_BYTES, 'Social JPEG is bounded and lightweight regardless of source dimensions/metadata. ' . ( is_wp_error( $social ) ? $social->get_error_message() : wp_json_encode( $social ) ) );
		$path = dirname( $upload['file'] ) . '/' . wp_basename( $social['url'] );
		$decoded = imagecreatefromjpeg( $path );
		$scale = min( 1, 1080 / $width, 1080 / $height );
		$left = (int) floor( ( 1080 - round( $width * $scale ) ) / 2 );
		$sample = max( 2, (int) round( $edge * $scale / 2 ) );
		$check( $near( imagecolorsforindex( $decoded, imagecolorat( $decoded, $left + $sample, 540 ) ), 240, 30, 30 ), 'Left edge of photograph survives without cropping.' );
		$check( $near( imagecolorsforindex( $decoded, imagecolorat( $decoded, 1080 - $left - $sample - 1, 540 ) ), 30, 30, 240 ), 'Right edge of photograph survives without cropping.' );
		$check( $near( imagecolorsforindex( $decoded, imagecolorat( $decoded, 0, 0 ) ), 255, 255, 255 ), 'Padding is white, never black or stretched image content.' ); imagedestroy( $decoded );
		$check( $social === Fisar_CDJ_Media_Variants::social( $id ), 'Repeated preparation reuses the same derivative URL.' );
		$facebook = Fisar_CDJ_Media_Variants::social( $id, 'facebook' );
		$check( ! is_wp_error( $facebook ) && max( $facebook['width'], $facebook['height'] ) <= 1440 && abs( $facebook['width'] / $facebook['height'] - $width / $height ) < 0.02 && $facebook['url'] !== $social['url'], 'Facebook uses a separate bounded file with original proportions and no square canvas.' );
		$facebook_path = dirname( $upload['file'] ) . '/' . wp_basename( $facebook['url'] );
		$facebook_pixels = imagecreatefromjpeg( $facebook_path );
		$check( $near( imagecolorsforindex( $facebook_pixels, imagecolorat( $facebook_pixels, 0, 0 ) ), 240, 30, 30 ), 'Facebook photograph reaches the canvas corner without added white margins.' ); imagedestroy( $facebook_pixels );
		$check( $social === Fisar_CDJ_Media_Variants::social( $id, 'instagram' ), 'Preparing Facebook cannot change the cached Instagram square or URL.' );
		$web = Fisar_CDJ_Media_Variants::web( $id );
		$check( ! is_wp_error( $web ) && count( $web ) > 0, 'Responsive web derivatives generated. ' . ( is_wp_error( $web ) ? $web->get_error_message() : '' ) );
		foreach ( $web as $variant ) { $check( $variant['width'] <= 2048 && $variant['height'] <= 2560 && abs( $variant['width'] / $variant['height'] - $width / $height ) < 0.02, 'Web derivatives keep original proportions and bounded dimensions.' ); }
		$media = Fisar_CDJ_Social_Publishing::media( $id );
		$check( $social['url'] === $media['url'] && $social['url'] === $media['preview'] && 1080 === $media['width'], 'Preview, validation metadata and payload use the actual social file.' );
		$check( $hash === hash_file( 'sha256', $upload['file'] ), 'Original attachment file remains byte-identical.' );
		$attributes = fisar_cdj_theme_optimized_image_attributes( $id, '246px' );
		$check( str_contains( $attributes['src'], '-web-' ) && str_contains( $attributes['srcset'], 'w' ) && '246px' === $attributes['sizes'], 'Website uses responsive derivatives with supplied display width.' );
		// Exercise the GD fallback even when Imagick is installed.
		$gd_path = dirname( $path ) . '/fisar-gd-test-' . $id . '.jpg';
		try {
			$gd = new ReflectionMethod( Fisar_CDJ_Media_Variants::class, 'social_gd' ); $gd->invoke( null, $upload['file'], $gd_path );
			$size = wp_getimagesize( $gd_path ); $check( 1080 === $size[0] && 1080 === $size[1], 'GD fallback produces the same canvas size.' );
		} finally { wp_delete_file( $gd_path ); }
		$files = get_post_meta( $id, '_fisar_media_variants', true )['history'];
		wp_delete_attachment( $id, true );
		$check( ! array_filter( $files, 'file_exists' ), 'Attachment deletion cleans its derivative files.' );
	}
	WP_CLI::success( 'Media: 5712px, portrait, panorama and small sources; uncropped colored edges, white padding, responsive dimensions, preview/payload consistency, caching, source preservation, cleanup and GD fallback passed.' );
} finally { foreach ( $ids as $id ) { wp_delete_attachment( $id, true ); } }
