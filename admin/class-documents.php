<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Documents {
	public static function init() {
		add_action( 'admin_post_nvb_save_document', array( __CLASS__, 'save_document' ) );
	}
	public static function documents_page() {
		include NVB_PLUGIN_DIR . 'templates/admin/documents-list.php';
	}

	public static function save_document() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}
		if ( empty( $_POST['nvb_document_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nvb_document_nonce'] ) ), 'nvb_save_document' ) ) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}
		global $wpdb;
		$prefix = $wpdb->prefix;

		$country_id = intval( $_POST['country_id'] ?? 0 );
		$visa_program_id = intval( $_POST['visa_program_id'] ?? 0 );
		$title = sanitize_text_field( $_POST['title'] ?? '' );
		$is_required = isset( $_POST['is_required'] ) ? 1 : 0;
		$note = wp_kses_post( $_POST['note'] ?? '' );

		if ( empty( $title ) || ! $country_id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_documents&message=missing' ) );
			exit;
		}

		$wpdb->insert(
			"{$prefix}nvb_documents",
			array(
				'country_id'      => $country_id,
				'visa_program_id' => $visa_program_id,
				'title'           => $title,
				'is_required'     => $is_required,
				'note'            => $note,
				'created_at'      => current_time( 'mysql' ),
				'updated_at'      => current_time( 'mysql' ),
			),
			array( '%d', '%d', '%s', '%d', '%s', '%s', '%s' )
		);

		wp_redirect( admin_url( 'admin.php?page=nvb_documents&message=success' ) );
		exit;
	}
}
