<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Settings {
	public static function init() {
		add_action( 'admin_post_nvb_save_settings', array( __CLASS__, 'save_settings' ) );
	}
	public static function settings_page() {
		$options = get_option( 'nvb_settings', array( 'items_per_page' => 20 ) );
		include NVB_PLUGIN_DIR . 'templates/admin/settings.php';
	}

	public static function save_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}
		if ( empty( $_POST['nvb_settings_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nvb_settings_nonce'] ) ), 'nvb_save_settings' ) ) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}
		$items_per_page = intval( $_POST['items_per_page'] ?? 20 );
		update_option( 'nvb_settings', array( 'items_per_page' => $items_per_page ) );
		wp_redirect( admin_url( 'admin.php?page=nvb_settings&message=updated' ) );
		exit;
	}
}
