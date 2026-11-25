<?php
/** Reusable derivatives: source files and attachment identities remain unchanged. */
final class Fisar_CDJ_Media_Variants {
	private const META = '_fisar_media_variants';
	private const WEB_WIDTHS = array( 480, 768, 1024, 1536, 2048 );
	public const SOCIAL_SIZE = 1080;

	public static function init(): void {
		add_action( 'delete_attachment', array( self::class, 'delete_files' ) );
		add_action( 'fisar_cdj_prepare_web_image', array( self::class, 'web' ) );
		add_filter( 'wp_update_attachment_metadata', static function ( mixed $metadata, int $id ): mixed {
			if ( is_array( $metadata ) ) { self::schedule_web( $id ); }
			return $metadata;
		}, 20, 2 );
	}

	public static function social( int $id, string $service = 'instagram' ): array|WP_Error {
		if ( ! in_array( $service, array( 'facebook', 'instagram' ), true ) ) { return new WP_Error( 'social_network', 'Canale social non valido.' ); }
		$variants = self::variants( $id, 'facebook' === $service ? 'facebook' : 'social' );
		return is_wp_error( $variants ) ? $variants : $variants[0];
	}

	public static function web( int $id, bool $generate = true ): array|WP_Error {
		return self::variants( $id, 'web', $generate );
	}

	private static function schedule_web( int $id ): void {
		if ( ! in_array( get_post_mime_type( $id ), array( 'image/jpeg', 'image/png', 'image/webp', 'image/avif' ), true ) ) { return; }
		if ( ! wp_next_scheduled( 'fisar_cdj_prepare_web_image', array( $id ) ) ) { wp_schedule_single_event( time() + 10, 'fisar_cdj_prepare_web_image', array( $id ) ); }
	}

	/** Cache by the actual file, including edits/replacements, rather than original-image metadata. */
	private static function variants( int $id, string $profile, bool $generate = true ): array|WP_Error {
		$file = get_attached_file( $id );
		$url = wp_get_attachment_url( $id );
		if ( ! $file || ! $url || ! is_file( $file ) ) { return new WP_Error( 'social_media_missing', 'Il file della foto «' . get_the_title( $id ) . '» non è disponibile.' ); }
		$signature = substr( hash( 'sha256', 'v1|' . $file . '|' . filemtime( $file ) . '|' . filesize( $file ) ), 0, 12 );
		$cache = get_post_meta( $id, self::META, true );
		$cached = is_array( $cache ) ? ( $cache[ $profile ] ?? array() ) : array();
		if ( ( $cached['signature'] ?? '' ) === $signature && self::files_exist( $cached['files'] ?? array() ) ) {
			return self::public_files( $cached['files'], $url );
		}
		if ( ! $generate ) {
			self::schedule_web( $id );
			return new WP_Error( 'media_web_pending', 'Le copie web sono in preparazione. Sono disponibili le varianti native WordPress.' );
		}
		$lock = fopen( $file . '.fisar-lock', 'c' );
		if ( ! $lock || ! flock( $lock, LOCK_EX | LOCK_NB ) ) {
			if ( $lock ) { fclose( $lock ); }
			return new WP_Error( 'social_media_busy', 'Preparazione delle immagini in corso. Rileggi l’articolo fra qualche istante.' );
		}
		try {
			$files = match ( $profile ) {
				'social' => self::make_social( $id, $file, $signature ),
				'facebook' => self::make_facebook( $id, $file, $signature ),
				default => self::make_web( $id, $file, $signature ),
			};
			if ( is_wp_error( $files ) ) { return $files; }
			// Reload after taking the lock so concurrent profiles cannot overwrite each other's cache.
			$cache = get_post_meta( $id, self::META, true );
			$cache = is_array( $cache ) ? $cache : array();
			$cache[ $profile ] = array( 'signature' => $signature, 'files' => $files );
			// Keep previous derivatives available to posts already scheduled in Buffer.
			$cache['history'] = array_values( array_unique( array_merge( $cache['history'] ?? array(), array_column( $files, 'path' ) ) ) );
			update_post_meta( $id, self::META, $cache );
			return self::public_files( $files, $url );
		} finally { flock( $lock, LOCK_UN ); fclose( $lock ); }
	}

	private static function files_exist( array $files ): bool {
		if ( ! $files ) { return false; }
		foreach ( $files as $file ) { if ( ! is_file( $file['path'] ) || ! filesize( $file['path'] ) ) { return false; } }
		return true;
	}

	private static function public_files( array $files, string $source_url ): array {
		return array_map( static function ( array $file ) use ( $source_url ): array {
			$file['url'] = trailingslashit( dirname( $source_url ) ) . wp_basename( $file['path'] );
			unset( $file['path'] );
			return $file;
		}, $files );
	}

	private static function describe( string $path ): array|WP_Error {
		$size = wp_getimagesize( $path );
		if ( ! $size || ! is_file( $path ) || ! filesize( $path ) ) { return new WP_Error( 'social_media_output', 'Non è stato possibile verificare la copia ottimizzata.' ); }
		return array( 'path' => $path, 'width' => $size[0], 'height' => $size[1], 'mime' => $size['mime'], 'bytes' => filesize( $path ) );
	}

	private static function make_web( int $id, string $source, string $signature ): array|WP_Error {
		$editor = wp_get_image_editor( $source );
		if ( is_wp_error( $editor ) ) { return $editor; }
		$size = $editor->get_size();
		$max_width = max( 1, min( 2048, $size['width'], (int) floor( $size['width'] * 2560 / $size['height'] ) ) );
		$widths = array_unique( array_merge( array_filter( self::WEB_WIDTHS, static fn( $width ) => $width < $max_width ), array( $max_width ) ) );
		$mime = wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) ) ? 'image/webp' : 'image/jpeg';
		$extension = 'image/webp' === $mime ? 'webp' : 'jpg';
		$files = array();
		foreach ( $widths as $width ) {
			$path = dirname( $source ) . '/fisar-' . $id . '-' . $signature . '-web-' . $width . '.' . $extension;
			if ( ! is_file( $path ) ) {
				$editor = wp_get_image_editor( $source );
				if ( is_wp_error( $editor ) ) { return $editor; }
				$editor->set_quality( 78 );
				if ( $width < $size['width'] || $size['height'] > 2560 ) {
					$resized = $editor->resize( $width, 2560, false );
					if ( is_wp_error( $resized ) ) { return $resized; }
				}
				$saved = $editor->save( $path, $mime );
				if ( is_wp_error( $saved ) ) { return $saved; }
			}
			$info = self::describe( $path );
			if ( is_wp_error( $info ) ) { return $info; }
			$files[] = $info;
		}
		return $files;
	}

	/** Facebook keeps the photograph's proportions, with no added canvas or crop. */
	private static function make_facebook( int $id, string $source, string $signature ): array|WP_Error {
		$path = dirname( $source ) . '/fisar-' . $id . '-' . $signature . '-facebook.jpg';
		if ( ! is_file( $path ) ) {
			$editor = wp_get_image_editor( $source );
			if ( is_wp_error( $editor ) ) { return $editor; }
			$size = $editor->get_size();
			if ( max( $size['width'], $size['height'] ) > 1440 ) {
				$resized = $editor->resize( 1440, 1440, false );
				if ( is_wp_error( $resized ) ) { return $resized; }
			}
			$editor->set_quality( 85 );
			$saved = $editor->save( $path, 'image/jpeg' );
			if ( is_wp_error( $saved ) ) { return $saved; }
			$path = $saved['path'];
		}
		$info = self::describe( $path );
		if ( is_wp_error( $info ) ) { return $info; }
		if ( max( $info['width'], $info['height'] ) > 1440 || 'image/jpeg' !== $info['mime'] || $info['bytes'] > 8 * MB_IN_BYTES ) {
			return new WP_Error( 'social_image_size', 'La copia Facebook non rispetta i limiti di formato, dimensioni o peso.' );
		}
		return array( $info );
	}

	/** A common white square prevents Instagram from cropping mixed-orientation carousels. */
	private static function make_social( int $id, string $source, string $signature ): array|WP_Error {
		$path = dirname( $source ) . '/fisar-' . $id . '-' . $signature . '-social.jpg';
		if ( ! is_file( $path ) ) {
			try {
				if ( class_exists( 'Imagick' ) && Imagick::queryFormats( 'JPEG' ) ) {
					try { self::social_imagick( $source, $path ); }
					catch ( Throwable $error ) {
						if ( ! function_exists( 'imagecreatetruecolor' ) ) { throw $error; }
						self::social_gd( $source, $path );
					}
				}
				elseif ( function_exists( 'imagecreatetruecolor' ) ) { self::social_gd( $source, $path ); }
				else { return new WP_Error( 'social_media_editor', 'Il server non dispone di un motore per preparare le copie social.' ); }
			} catch ( Throwable $error ) {
				if ( is_file( $path ) ) { wp_delete_file( $path ); }
				return new WP_Error( 'social_media_editor', 'Impossibile preparare la foto «' . get_the_title( $id ) . '». Rileggi l’articolo e riprova.' );
			}
		}
		$info = self::describe( $path );
		if ( is_wp_error( $info ) ) { return $info; }
		if ( self::SOCIAL_SIZE !== $info['width'] || self::SOCIAL_SIZE !== $info['height'] || 'image/jpeg' !== $info['mime'] || $info['bytes'] > 8 * MB_IN_BYTES ) {
			return new WP_Error( 'social_image_size', 'La copia social non rispetta i limiti di formato, dimensioni o peso.' );
		}
		return array( $info );
	}

	private static function social_imagick( string $source, string $path ): void {
		$image = new Imagick( $source . '[0]' );
		try {
			$image->autoOrient();
			$image->transformImageColorspace( Imagick::COLORSPACE_SRGB );
			$image->thumbnailImage( min( self::SOCIAL_SIZE, $image->getImageWidth() ), min( self::SOCIAL_SIZE, $image->getImageHeight() ), true );
			$image->setImageBackgroundColor( 'white' );
			$image->setImageAlphaChannel( Imagick::ALPHACHANNEL_REMOVE );
			$image->extentImage( self::SOCIAL_SIZE, self::SOCIAL_SIZE, -(int) floor( ( self::SOCIAL_SIZE - $image->getImageWidth() ) / 2 ), -(int) floor( ( self::SOCIAL_SIZE - $image->getImageHeight() ) / 2 ) );
			$image->setImagePage( 0, 0, 0, 0 );
			$image->stripImage();
			$image->setImageFormat( 'jpeg' );
			$image->setImageCompressionQuality( 85 );
			if ( ! $image->writeImage( $path ) ) { throw new RuntimeException( 'Cannot save image.' ); }
		} finally { $image->clear(); }
	}

	private static function social_gd( string $source, string $path ): void {
		// WordPress bakes EXIF orientation into its display file; let its editor normalize other formats.
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$editor = wp_get_image_editor( $source );
		if ( is_wp_error( $editor ) ) { throw new RuntimeException( 'Cannot read image.' ); }
		$size = $editor->get_size();
		if ( $size['width'] > self::SOCIAL_SIZE || $size['height'] > self::SOCIAL_SIZE ) {
			$resized = $editor->resize( self::SOCIAL_SIZE, self::SOCIAL_SIZE, false );
			if ( is_wp_error( $resized ) ) { throw new RuntimeException( 'Cannot resize image.' ); }
		}
		$temp = wp_tempnam( 'fisar-social.png' );
		$initial_temp = $temp;
		try {
			$saved = $editor->save( $temp, 'image/png' );
			if ( is_wp_error( $saved ) ) { throw new RuntimeException( 'Cannot resize image.' ); }
			$temp = $saved['path'];
			$image = imagecreatefrompng( $temp );
			$canvas = imagecreatetruecolor( self::SOCIAL_SIZE, self::SOCIAL_SIZE );
			try {
				imagefill( $canvas, 0, 0, imagecolorallocate( $canvas, 255, 255, 255 ) );
				imagecopy( $canvas, $image, (int) floor( ( self::SOCIAL_SIZE - imagesx( $image ) ) / 2 ), (int) floor( ( self::SOCIAL_SIZE - imagesy( $image ) ) / 2 ), 0, 0, imagesx( $image ), imagesy( $image ) );
				if ( ! imagejpeg( $canvas, $path, 85 ) ) { throw new RuntimeException( 'Cannot save image.' ); }
			} finally { imagedestroy( $image ); imagedestroy( $canvas ); }
		} finally {
			if ( $temp ) { wp_delete_file( $temp ); }
			if ( $initial_temp !== $temp ) { wp_delete_file( $initial_temp ); }
		}
	}

	public static function delete_files( int $id ): void {
		wp_clear_scheduled_hook( 'fisar_cdj_prepare_web_image', array( $id ) );
		$cache = get_post_meta( $id, self::META, true );
		foreach ( is_array( $cache ) ? ( $cache['history'] ?? array() ) : array() as $path ) {
			if ( preg_match( '/^fisar-' . $id . '-[a-f0-9]{12}-(?:web-\d+|social|facebook)\.(?:webp|jpg)$/', wp_basename( $path ) ) ) { wp_delete_file( $path ); }
		}
		$source = get_attached_file( $id );
		if ( $source && is_file( $source . '.fisar-lock' ) ) { wp_delete_file( $source . '.fisar-lock' ); }
	}
}
