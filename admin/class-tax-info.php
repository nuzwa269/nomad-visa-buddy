<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Tax_Info {
	public static function init() {
		add_action( 'admin_post_nvb_save_tax', array( __CLASS__, 'save_tax' ) );
	}
	public static function tax_page() {
		include NVB_PLUGIN_DIR . 'templates/admin/tax-info-list.php';
	}

	public static function save_tax() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}
		if ( empty( $_POST['nvb_tax_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nvb_tax_nonce'] ) ), 'nvb_save_tax' ) ) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}
		global $wpdb;
		$prefix = $wpdb->prefix;

		$country_id = intval( $_POST['country_id'] ?? 0 );
		$info_title = sanitize_text_field( $_POST['info_title'] ?? '' );
		$description = wp_kses_post( $_POST['description'] ?? '' );
		$tax_rate = sanitize_text_field( $_POST['tax_rate'] ?? '' );

		if ( ! $country_id || empty( $info_title ) ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_tax_info&message=missing' ) );
			exit;
		}

		$wpdb->insert(
			"{$prefix}nvb_tax_info",
			array(
				'country_id' => $country_id,
				'info_title' => $info_title,
				'description' => $description,
				'tax_rate' => $tax_rate,
				'created_at' => current_time( 'mysql' ),
				'updated_at' => current_time( 'mysql' ),
			),
			array( '%d', '%s', '%s', '%s', '%s', '%s' )
		);

		wp_redirect( admin_url( 'admin.php?page=nvb_tax_info&message=success' ) );
		exit;
	}
}
