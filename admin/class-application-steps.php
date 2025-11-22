<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Application_Steps {

	public static function init() {

		// Save / delete actions (admin-post.php)
		add_action( 'admin_post_nvb_save_application_step', array( __CLASS__, 'save_step' ) );
		add_action( 'admin_post_nvb_delete_application_step', array( __CLASS__, 'delete_step' ) );

		// NOTE: menu / submenu "nvb_application_steps" پہلے ہی NVB_Admin_Menu کے ذریعے بن رہا ہے۔
		// یہاں admin_menu دوبارہ add نہیں کر رہے، تاکہ کوئی conflict / fatal error نہ آئے۔
	}

	/**
	 * Main admin page callback for "Application Steps"
	 *
	 * URL examples:
	 * - admin.php?page=nvb_application_steps            (list)
	 * - admin.php?page=nvb_application_steps&action=add (add form)
	 * - admin.php?page=nvb_application_steps&action=edit&id=123 (edit form)
	 */
	public static function application_steps_page() {
<|diff_marker|> ADD A1000
		global $wpdb;

		$prefix = $wpdb->prefix;

		$action = isset( $_GET['action'] )
			? sanitize_text_field( wp_unslash( $_GET['action'] ) )
			: '';

		/*
		 * ------------------------
		 * ADD / EDIT PAGE
		 * ------------------------
		 */
		if ( 'add' === $action || 'edit' === $action ) {

			// Countries dropdown کیلئے ڈیٹا
			$countries = $wpdb->get_results(
				"SELECT id, name 
				 FROM {$prefix}nvb_countries 
				 WHERE is_deleted = 0 
				 ORDER BY name ASC"
<|diff_marker|> ADD A1020
			);

			$step = null;

			if ( 'edit' === $action && ! empty( $_GET['id'] ) ) {
				$id = (int) $_GET['id'];

				$step = $wpdb->get_row(
					$wpdb->prepare(
						"SELECT * 
						 FROM {$prefix}nvb_application_steps 
						 WHERE id = %d 
						   AND is_deleted = 0",
						$id
					)
				);
			}

			// 👇 یہ وہ نئی فائل ہے جو فارم دکھائے گی:
			// wp-content/plugins/nomad-visa-hub/templates/admin/application-step-edit.php
<|diff_marker|> ADD A1040
			include NVB_PLUGIN_DIR . 'templates/admin/application-step-edit.php';
			return;
		}

		/*
		 * ------------------------
		 * LIST PAGE
		 * ------------------------
		 */
		$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

		if ( $search ) {
			$like = '%' . $wpdb->esc_like( $search ) . '%';

			$steps = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT s.*, c.name AS country_name 
					 FROM {$prefix}nvb_application_steps s
					 LEFT JOIN {$prefix}nvb_countries c ON c.id = s.country_id
					 WHERE s.is_deleted = 0
<|diff_marker|> ADD A1060
					   AND ( s.title LIKE %s OR c.name LIKE %s )
					 ORDER BY c.name ASC, s.step_number ASC",
					$like,
					$like
				)
			);
		} else {
			$steps = $wpdb->get_results(
				"SELECT s.*, c.name AS country_name 
				 FROM {$prefix}nvb_application_steps s
				 LEFT JOIN {$prefix}nvb_countries c ON c.id = s.country_id
				 WHERE s.is_deleted = 0
				 ORDER BY c.name ASC, s.step_number ASC"
			);
		}

		// 👇 لسٹ والی template:
		// wp-content/plugins/nomad-visa-hub/templates/admin/application-steps-list.php
		include NVB_PLUGIN_DIR . 'templates/admin/application-steps-list.php';
	}

<|diff_marker|> ADD A1080
	/**
	 * SAVE (Insert / Update)
	 */
	public static function save_step() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Unauthorized' );
		}

		if (
			empty( $_POST['nvb_application_step_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['nvb_application_step_nonce'] ) ),
				'nvb_save_application_step'
			)
		) {
			wp_die( 'Invalid nonce' );
		}

		global $wpdb;
<|diff_marker|> ADD A1100
		$prefix = $wpdb->prefix;

		$id            = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$country_id    = isset( $_POST['country_id'] ) ? (int) $_POST['country_id'] : 0;
		$step_number   = isset( $_POST['step_number'] ) ? (int) $_POST['step_number'] : 1;
		$title         = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
		$description   = isset( $_POST['description'] ) ? wp_kses_post( wp_unslash( $_POST['description'] ) ) : '';
		$external_link = isset( $_POST['external_link'] ) ? esc_url_raw( wp_unslash( $_POST['external_link'] ) ) : '';
		$screenshot    = isset( $_POST['screenshot_url'] ) ? esc_url_raw( wp_unslash( $_POST['screenshot_url'] ) ) : '';

		if ( ! $country_id || '' === $title ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_application_steps&message=missing' ) );
			exit;
		}

		if ( $step_number <= 0 ) {
			$step_number = 1;
		}

		if ( $id ) {
<|diff_marker|> ADD A1120
			$wpdb->update(
				"{$prefix}nvb_application_steps",
				array(
					'country_id'     => $country_id,
					'step_number'    => $step_number,
					'title'          => $title,
					'description'    => $description,
					'external_link'  => $external_link,
					'screenshot_url' => $screenshot,
					'updated_at'     => current_time( 'mysql' ),
				),
				array( 'id' => $id )
			);

			$msg = 'updated';

		} else {
			$wpdb->insert(
				"{$prefix}nvb_application_steps",
				array(
<|diff_marker|> ADD A1140
					'country_id'     => $country_id,
					'step_number'    => $step_number,
					'title'          => $title,
					'description'    => $description,
					'external_link'  => $external_link,
					'screenshot_url' => $screenshot,
					'is_deleted'     => 0,
					'created_at'     => current_time( 'mysql' ),
					'updated_at'     => current_time( 'mysql' ),
				)
			);

			$msg = 'created';
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_application_steps&message=' . $msg ) );
		exit;
	}

	/**
<|diff_marker|> ADD A1160
	 * DELETE (soft delete)
	 */
	public static function delete_step() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Unauthorized' );
		}

		if (
			empty( $_GET['_wpnonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ),
				'nvb_delete_application_step'
			)
		) {
			wp_die( 'Invalid nonce' );
		}

		$id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;

<|diff_marker|> ADD A1180
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
			array( 'id' => $id )
		);

		wp_redirect( admin_url( 'admin.php?page=nvb_application_steps&message=deleted' ) );
		exit;
	}
<|diff_marker|> ADD A1200
}
