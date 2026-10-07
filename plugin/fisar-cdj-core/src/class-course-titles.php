<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Fisar_CDJ_Course_Titles {
	public static function init(): void {
		add_filter( 'wp_insert_post_data', array( self::class, 'prepare_title' ), 10, 2 );
	}

	/** Compose before the classic save, so WordPress can create a normal slug. */
	public static function prepare_title( array $data, array $postarr ): array {
		$id = (int) ( $postarr['ID'] ?? 0 );
		if ( Fisar_CDJ_Post_Types::COURSE !== $data['post_type'] || 'auto-draft' === $data['post_status'] || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return $data;
		}
		if ( $id && isset( $_POST['post_ID'] ) && $id !== absint( $_POST['post_ID'] ) ) {
			return $data;
		}
		if ( ! isset( $_POST['_fisar_course_title_mode'], $_POST['fisar_cdj_meta_nonce'] ) || ! is_string( $_POST['fisar_cdj_meta_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['fisar_cdj_meta_nonce'] ), 'fisar_cdj_save_meta' ) || ! current_user_can( $id ? 'edit_post' : 'edit_posts', $id ) ) {
			return $data;
		}
		if ( 'automatic' !== fisar_cdj_sanitize_course_title_mode( wp_unslash( $_POST['_fisar_course_title_mode'] ) ) ) {
			return $data;
		}
		$values = array();
		foreach ( array( 'level', 'city', 'province' ) as $field ) {
			$value = wp_unslash( $_POST[ '_fisar_course_' . $field ] ?? get_post_meta( $id, '_fisar_course_' . $field, true ) );
			$values[] = is_string( $value ) ? $value : '';
		}
		$data['post_title'] = wp_slash( fisar_cdj_compose_course_title( ...$values ) );
		return $data;
	}

	/** Called after the authorized metabox save, when all identity fields are current. */
	public static function save( int $course_id ): void {
		if ( ! isset( $_POST['_fisar_course_title_mode'] ) ) {
			return; // An editor opened before this feature must not overwrite the title.
		}
		$mode = fisar_cdj_sanitize_course_title_mode( wp_unslash( $_POST['_fisar_course_title_mode'] ) );
		update_post_meta( $course_id, '_fisar_course_title_mode', $mode );
		if ( 'automatic' !== $mode ) {
			return;
		}
		$identity = fisar_cdj_get_course_identity( $course_id );
		if ( $identity['title'] === get_post_field( 'post_title', $course_id, 'raw' ) ) {
			return;
		}
		// Gutenberg saves native content and legacy metaboxes in separate requests.
		// Sync after the metabox request without re-running its calendar/meta writes.
		remove_action( 'save_post_' . Fisar_CDJ_Post_Types::COURSE, array( Fisar_CDJ_Meta_Boxes::class, 'save_course' ) );
		try {
			$result = wp_update_post( wp_slash( array( 'ID' => $course_id, 'post_title' => $identity['title'] ) ), true );
			if ( is_wp_error( $result ) ) {
				error_log( 'FISAR course title: ' . $result->get_error_message() );
			}
		} finally {
			add_action( 'save_post_' . Fisar_CDJ_Post_Types::COURSE, array( Fisar_CDJ_Meta_Boxes::class, 'save_course' ) );
		}
	}
}
