<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class NVB_Application_Steps {

	public static function init() {

		add_action( 'admin_post_nvb_save_application_step', array( __CLASS__, 'save_step' ) );
		add_action( 'admin_post_nvb_delete_application_step', array( __CLASS__, 'delete_step' ) );

		// list page
		add_action( 'admin_menu', function() {
			add_submenu_page(
				'nvb_main',
				'Application Steps',
				'Application Steps',
				'manage_options',
				'nvb_application_steps',
				array( __CLASS__, 'application_steps_page' )
			);
		});
	}

	public static function application_steps_page() {
		global $wpdb;
		$prefix = $wpdb->prefix;

		$search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

		if ( $search ) {
			$steps = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT s.*, c.name AS country_name 
					FROM {$prefix}nvb_application_steps s
					LEFT JOIN {$prefix}nvb_countries c ON c.id = s.country_id
					WHERE s.is_deleted = 0
					AND (s.title LIKE %s OR c.name LIKE %s)
					ORDER BY s.country_id, s.step_number ASC",
					"%{$search}%", "%{$search}%"
				)
			);
		} else {
			$steps = $wpdb->get_results(
				"SELECT s.*, c.name AS country_name 
				FROM {$prefix}nvb_application_steps s
				LEFT JOIN {$prefix}nvb_countries c ON c.id = s.country_id
				WHERE s.is_deleted = 0
				ORDER BY s.country_id, s.step_number ASC"
			);
		}

		include NVB_PLUGIN_DIR . 'templates/admin/application-steps-list.php';
	}

	/**
	 * SAVE
	 */
	public static function save_step() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Unauthorized' );
		}

		if (
			empty($_POST['nvb_application_step_nonce']) ||
			! wp_verify_nonce($_POST['nvb_application_step_nonce'], 'nvb_save_application_step')
		) {
			wp_die('Invalid nonce');
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$id            = intval($_POST['id'] ?? 0);
		$country_id    = intval($_POST['country_id'] ?? 0);
		$step_number   = intval($_POST['step_number'] ?? 1);
		$title         = sanitize_text_field($_POST['title'] ?? '');
		$description   = wp_kses_post($_POST['description'] ?? '');
		$external_link = esc_url_raw($_POST['external_link'] ?? '');
		$screenshot    = esc_url_raw($_POST['screenshot_url'] ?? '');

		if (!$country_id || empty($title)) {
			wp_redirect(admin_url('admin.php?page=nvb_application_steps&message=missing'));
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
					'screenshot_url' => $screenshot,
					'updated_at'     => current_time('mysql'),
				),
				array('id' => $id)
			);

			$msg = 'updated';

		} else {
			$wpdb->insert(
				"{$prefix}nvb_application_steps",
				array(
					'country_id'     => $country_id,
					'step_number'    => $step_number,
					'title'          => $title,
					'description'    => $description,
					'external_link'  => $external_link,
					'screenshot_url' => $screenshot,
					'is_deleted'     => 0,
					'created_at'     => current_time('mysql'),
					'updated_at'     => current_time('mysql'),
				)
			);

			$msg = 'created';
		}

		wp_redirect(admin_url('admin.php?page=nvb_application_steps&message=' . $msg));
		exit;
	}

	/**
	 * DELETE
	 */
	public static function delete_step() {

		if (!current_user_can('manage_options')) wp_die('Unauthorized');

		if (
			empty($_GET['_wpnonce']) ||
			! wp_verify_nonce($_GET['_wpnonce'], 'nvb_delete_application_step')
		) {
			wp_die('Invalid nonce');
		}

		$id = intval($_GET['id'] ?? 0);

		if (!$id) {
			wp_redirect(admin_url('admin.php?page=nvb_application_steps&message=missing'));
			exit;
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$wpdb->update(
			"{$prefix}nvb_application_steps",
			array(
				'is_deleted' => 1,
				'updated_at' => current_time('mysql'),
			),
			array('id' => $id)
		);

		wp_redirect(admin_url('admin.php?page=nvb_application_steps&message=deleted'));
		exit;
	}
}
