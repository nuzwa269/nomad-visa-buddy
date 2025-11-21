<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Application_Steps {

	public static function init() {

		// Correct hooks for saving and deleting
		add_action( 'admin_post_nvb_save_application_step', array( __CLASS__, 'save_step' ) );
		add_action( 'admin_post_nvb_delete_application_step', array( __CLASS__, 'delete_step' ) );
	}

	public static function application_steps_page() {
		include NVB_PLUGIN_DIR . 'templates/admin/application-steps-list.php';
	}

	/**
	 * Insert / Update Step
	 */
	public static function save_step() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Unauthorized', 'nvb' ) );
		}

		// Correct nonce name
		if (
			empty( $_POST['nvb_application_step_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['nvb_application_step_nonce'] ) ),
				'nvb_save_application_step'
			)
		) {
			wp_die( __( 'Invalid nonce', 'nvb' ) );
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$id          = intval( $_POST['id'] ?? 0 );
		$country_id  = intval( $_POST['country_id'] ?? 0 );
		$step_number = intval( $_POST['step_number'] ?? 0 );
		$title       = sanitize_text_field( $_POST['title'] ?? '' );
		$description = wp_kses_post( $_POST['description'] ?? '' );
		$external_link  = esc_url_raw( $_POST['external_link'] ?? '' );
		$screenshot_url = esc_url_raw( $_POST['screenshot_url'] ?? '' );

		if ( empty( $title ) || ! $country_id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_application_steps&message=missing' ) );
			exit;
		}

		if ( $id ) {

			$wpdb->update(
				"{$prefix}nvb_application_steps",
				array(
					'country_id'     => $country_id,
					'step_number'    => $step_number,
					'title'          => $title,
					'description'    => $description,
					'external_link'  => $external_link,
					'screenshot_url' => $screenshot_url,
					'updated_at'     => current_time( 'mysql' ),
				),
				array( 'id' => $id ),
				array( '%d', '%d', '%s', '%s', '%s', '%s', '%s' ),
				array( '%d' )
			);

			$message = 'updated';

		} else {

			$wpdb->insert(
				"{$prefix}nvb_application_steps",
				array(
					'country_id'     => $country_id,
					'step_number'    => $step_number,
					'title'          => $title,
					'description'    => $description,
					'external_link'  => $external_link,
					'screenshot_url' => $screenshot_url,
					'is_deleted'     => 0,
					'created_at'     => current_time( 'mysql' ),
					'updated_at'     => current_time( 'mysql' ),
				),
				array( '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s' )
			);

			$message = 'created';
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_application_steps&message=' . $message ) );
		exit;
	}

	/**
	 * Soft delete step
	 */
	public static function delete_step() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Unauthorized', 'nvb' ) );
		}

		// Correct nonce
		if (
			empty( $_GET['_wpnonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ),
				'nvb_delete_application_step'
			)
		) {
			wp_die( __( 'Invalid nonce', 'nvb' ) );
		}

		$id = intval( $_GET['id'] ?? 0 );

		if ( ! $id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_application_steps&message=missing' ) );
			exit;
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$wpdb->update(
			"{$prefix}nvb_application_steps",
			array(
				'is_deleted' => 1,
				'updated_at' => current_time( 'mysql' ),
			),
			array( 'id' => $id ),
			array( '%d', '%s' ),
			array( '%d' )
		);

		wp_redirect( admin_url( 'admin.php?page=nvb_application_steps&message=deleted' ) );
		exit;
	}
}
