<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Cost_Of_Living {
	public static function init() {
		add_action( 'admin_post_nvb_save_col', array( __CLASS__, 'save_col' ) );
	}
	public static function col_page() {
		include NVB_PLUGIN_DIR . 'templates/admin/cost-of-living-list.php';
	}

	public static function save_col() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}
		if ( empty( $_POST['nvb_col_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nvb_col_nonce'] ) ), 'nvb_save_col' ) ) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}
		global $wpdb;
		$prefix = $wpdb->prefix;

		$country_id = intval( $_POST['country_id'] ?? 0 );
		$rent = floatval( $_POST['rent'] ?? 0 );
		$food = floatval( $_POST['food'] ?? 0 );
		$transport = floatval( $_POST['transport'] ?? 0 );
		$internet = floatval( $_POST['internet'] ?? 0 );
		$healthcare = floatval( $_POST['healthcare'] ?? 0 );
		$lifestyle_score = intval( $_POST['lifestyle_score'] ?? 50 );
		$notes = wp_kses_post( $_POST['notes'] ?? '' );

		if ( ! $country_id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_cost_of_living&message=missing' ) );
			exit;
		}

		$wpdb->insert(
			"{$prefix}nvb_cost_of_living",
			array(
				'country_id' => $country_id,
				'rent' => $rent,
				'food' => $food,
				'transport' => $transport,
				'internet' => $internet,
				'healthcare' => $healthcare,
				'lifestyle_score' => $lifestyle_score,
				'notes' => $notes,
				'created_at' => current_time( 'mysql' ),
				'updated_at' => current_time( 'mysql' ),
			),
			array( '%d', '%f', '%f', '%f', '%f', '%f', '%d', '%s', '%s', '%s' )
		);

		wp_redirect( admin_url( 'admin.php?page=nvb_cost_of_living&message=success' ) );
		exit;
	}
}
