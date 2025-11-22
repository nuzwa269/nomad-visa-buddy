<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class NVB_Tax_Info {

	public static function init() {

		// Create / Update handler
		add_action( 'admin_post_nvb_save_tax', array( __CLASS__, 'save_tax' ) );

		// Delete handler
		add_action( 'admin_post_nvb_delete_tax', array( __CLASS__, 'delete_tax' ) );
	}

	public static function tax_info_page() {
		include NVB_PLUGIN_DIR . 'templates/admin/tax-info-list.php';
	}

	/**
	 * Create or Update tax info record
	 */
	public static function save_tax() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Unauthorized', 'nvb' ) );
		}

		if ( empty( $_POST['nvb_tax_nonce'] ) ||
		     ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nvb_tax_nonce'] ) ), 'nvb_save_tax' ) ) {
			wp_die( __( 'Invalid nonce', 'nvb' ) );
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$id         = intval( $_POST['id'] ?? 0 );
		$country_id = intval( $_POST['country_id'] ?? 0 );
		$info_title = sanitize_text_field( $_POST['info_title'] ?? '' );
		$tax_rate   = sanitize_text_field( $_POST['tax_rate'] ?? '' );
		$description = wp_kses_post( $_POST['description'] ?? '' );

		// Validation – country اور title دونوں ہونے چاہئیں
		if ( empty( $info_title ) || ! $country_id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_tax_info&message=missing' ) );
			exit;
		}

		if ( $id ) {

			$wpdb->update(
				"{$prefix}nvb_tax_info",
				array(
					'country_id'  => $country_id,
					'info_title'  => $info_title,
					'tax_rate'    => $tax_rate,
					'description' => $description,
					'updated_at'  => current_time( 'mysql' ),
				),
				array( 'id' => $id ),
				array( '%d', '%s', '%s', '%s', '%s' ),
				array( '%d' )
			);

			$message = 'updated';

		} else {

			$wpdb->insert(
				"{$prefix}nvb_tax_info",
				array(
					'country_id'  => $country_id,
					'info_title'  => $info_title,
					'tax_rate'    => $tax_rate,
					'description' => $description,
					'is_deleted'  => 0,
					'created_at'  => current_time( 'mysql' ),
					'updated_at'  => current_time( 'mysql' ),
				),
				array( '%d', '%s', '%s', '%s', '%d', '%s', '%s' )
			);

			$message = 'created';
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_tax_info&message=' . $message ) );
		exit;
	}

	/**
	 * Soft delete
	 */
	public static function delete_tax() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Unauthorized', 'nvb' ) );
		}

		if ( empty( $_GET['_wpnonce'] ) ||
		     ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'nvb_delete_tax' ) ) {
			wp_die( __( 'Invalid nonce', 'nvb' ) );
		}

		$id = intval( $_GET['id'] ?? 0 );

		if ( ! $id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_tax_info&message=missing' ) );
			exit;
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$wpdb->update(
			"{$prefix}nvb_tax_info",
			array(
				'is_deleted' => 1,
				'updated_at' => current_time( 'mysql' ),
			),
			array( 'id' => $id ),
			array( '%d', '%s' ),
			array( '%d' )
		);

		wp_redirect( admin_url( 'admin.php?page=nvb_tax_info&message=deleted' ) );
		exit;
	}
}
