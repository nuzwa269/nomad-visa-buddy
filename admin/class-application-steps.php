<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Application_Steps {
	public static function init() {
		add_action( 'admin_post_nvb_save_step', array( __CLASS__, 'save_step' ) );
	}
	public static function steps_page() {
		include NVB_PLUGIN_DIR . 'templates/admin/application-steps-list.php';
	}

	public static function save_step() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}
		if ( empty( $_POST['nvb_step_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nvb_step_nonce'] ) ), 'nvb_save_step' ) ) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}
		global $wpdb;
		$prefix = $wpdb->prefix;

		$country_id = intval( $_POST['country_id'] ?? 0 );
		$visa_program_id = intval( $_POST['visa_program_id'] ?? 0 );
		$step_number = intval( $_POST['step_number'] ?? 0 );
		$title = sanitize_text_field( $_POST['title'] ?? '' );
		$description = wp_kses_post( $_POST['description'] ?? '' );
		$external_link = esc_url_raw( $_POST['external_link'] ?? '' );
		$screenshot_url = esc_url_raw( $_POST['screenshot_url'] ?? '' );

		if ( ! $country_id || empty( $title ) ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_application_steps&message=missing' ) );
			exit;
		}

		$wpdb->insert(
			"{$prefix}nvb_application_steps",
			array(
				'country_id'      => $country_id,
				'visa_program_id' => $visa_program_id,
				'step_number'     => $step_number,
				'title'           => $title,
				'description'     => $description,
				'external_link'   => $external_link,
				'screenshot_url'  => $screenshot_url,
				'created_at'      => current_time( 'mysql' ),
				'updated_at'      => current_time( 'mysql' ),
			),
			array( '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s' )
		);

		wp_redirect( admin_url( 'admin.php?page=nvb_application_steps&message=success' ) );
		exit;
	}
}
