<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Eligibility {

	public static function init() {
		// Create / Update
		add_action( 'admin_post_nvb_save_eligibility', array( __CLASS__, 'save_eligibility' ) );

		// Soft delete
		add_action( 'admin_post_nvb_delete_eligibility', array( __CLASS__, 'delete_eligibility' ) );
	}

	/**
	 * Render admin page (form + list)
	 */
	public static function eligibility_page() {
		include NVB_PLUGIN_DIR . 'templates/admin/eligibility-list.php';
	}

	/**
	 * Create / Update eligibility Q&A
	 */
	public static function save_eligibility() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}

		if (
			empty( $_POST['nvb_eligibility_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['nvb_eligibility_nonce'] ) ),
				'nvb_save_eligibility'
			)
		) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$id         = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$country_id = intval( $_POST['country_id'] ?? 0 );
		$question   = sanitize_text_field( $_POST['question'] ?? '' );
		$answer     = wp_kses_post( $_POST['answer'] ?? '' );
		$sort_order = isset( $_POST['sort_order'] ) ? intval( $_POST['sort_order'] ) : 0;

		if ( empty( $question ) || ! $country_id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_eligibility&message=missing' ) );
			exit;
		}

		if ( $id ) {
			// Update
			$wpdb->update(
				"{$prefix}nvb_eligibility",
				array(
					'country_id' => $country_id,
					'question'   => $question,
					'answer'     => $answer,
					'sort_order' => $sort_order,
					'updated_at' => current_time( 'mysql' ),
				),
				array( 'id' => $id ),
				array( '%d', '%s', '%s', '%d', '%s' ),
				array( '%d' )
			);
			$message = 'updated';
		} else {
			// Insert
			$wpdb->insert(
				"{$prefix}nvb_eligibility",
				array(
					'country_id' => $country_id,
					'question'   => $question,
					'answer'     => $answer,
					'sort_order' => $sort_order,
					'is_deleted' => 0,
					'created_at' => current_time( 'mysql' ),
					'updated_at' => current_time( 'mysql' ),
				),
				array( '%d', '%s', '%s', '%d', '%d', '%s', '%s' )
			);
			$message = 'created';
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_eligibility&message=' . $message ) );
		exit;
	}

	/**
	 * Soft delete (is_deleted = 1)
	 */
	public static function delete_eligibility() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}

		if (
			empty( $_GET['_wpnonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ),
				'nvb_delete_eligibility'
			)
		) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}

		$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

		if ( ! $id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_eligibility&message=missing' ) );
			exit;
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$wpdb->update(
			"{$prefix}nvb_eligibility",
			array(
				'is_deleted' => 1,
				'updated_at' => current_time( 'mysql' ),
			),
			array( 'id' => $id ),
			array( '%d', '%s' ),
			array( '%d' )
		);

		wp_redirect( admin_url( 'admin.php?page=nvb_eligibility&message=deleted' ) );
		exit;
	}
}
