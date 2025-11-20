<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class NVB_Documents {

	public static function init() {
		add_action( 'admin_post_nvb_save_document', array( __CLASS__, 'save_document' ) );
		add_action( 'admin_post_nvb_delete_document', array( __CLASS__, 'delete_document' ) );
	}

	public static function documents_page() {
		include NVB_PLUGIN_DIR . 'templates/admin/documents-list.php';
	}

	/**
	 * Create / Update document record
	 */
	public static function save_document() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Unauthorized.', 'nvb' ) );
		}

		if ( empty( $_POST['nvb_document_nonce'] ) ||
		     ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nvb_document_nonce'] ) ), 'nvb_save_document' ) ) {
			wp_die( __( 'Invalid nonce.', 'nvb' ) );
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$id          = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$country_id  = intval( $_POST['country_id'] ?? 0 );
		$title       = sanitize_text_field( $_POST['title'] ?? '' );
		$is_required = isset( $_POST['is_required'] ) ? 1 : 0;
		$note        = wp_kses_post( $_POST['note'] ?? '' );

		if ( empty( $title ) || ! $country_id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_documents&message=missing' ) );
			exit;
		}

		if ( $id ) {
			/** update */
			$wpdb->update(
				"{$prefix}nvb_documents",
				array(
					'country_id'  => $country_id,
					'title'       => $title,
					'is_required' => $is_required,
					'note'        => $note,
					'updated_at'  => current_time( 'mysql' ),
				),
				array( 'id' => $id ),
				array( '%d', '%s', '%d', '%s', '%s' ),
				array( '%d' )
			);

			$message = 'updated';
		} else {
			/** insert */
			$wpdb->insert(
				"{$prefix}nvb_documents",
				array(
					'country_id'  => $country_id,
					'title'       => $title,
					'is_required' => $is_required,
					'note'        => $note,
					'is_deleted'  => 0,
					'created_at'  => current_time( 'mysql' ),
					'updated_at'  => current_time( 'mysql' ),
				),
				array( '%d', '%s', '%d', '%s', '%d', '%s', '%s' )
			);

			$message = 'created';
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_documents&message=' . $message ) );
		exit;
	}

	/**
	 * Soft delete (is_deleted = 1)
	 */
	public static function delete_document() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Unauthorized.', 'nvb' ) );
		}

		if ( empty( $_GET['_wpnonce'] )
		     || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'nvb_delete_document' ) ) {
			wp_die( __( 'Nonce error.', 'nvb' ) );
		}

		$id = intval( $_GET['id'] ?? 0 );
		if ( ! $id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_documents&message=missing' ) );
			exit;
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$wpdb->update(
			"{$prefix}nvb_documents",
			array(
				'is_deleted' => 1,
				'updated_at' => current_time( 'mysql' ),
			),
			array( 'id' => $id ),
			array( '%d', '%s' ),
			array( '%d' )
		);

		wp_redirect( admin_url( 'admin.php?page=nvb_documents&message=deleted' ) );
		exit;
	}
}
