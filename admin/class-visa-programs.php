<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Visa_Programs {

	public static function init() {
		// save (create / update)
		add_action( 'admin_post_nvb_save_visa', array( __CLASS__, 'save_visa' ) );

		// soft delete
		add_action( 'admin_post_nvb_delete_visa', array( __CLASS__, 'delete_visa' ) );
	}

	/**
	 * Render admin page (list + form)
	 */
	public static function visa_programs_page() {
		// یہ صرف template include کر رہا ہے
		include NVB_PLUGIN_DIR . 'templates/admin/visa-programs-list.php';
	}

	/**
	 * Create / Update visa program
	 */
	public static function save_visa() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}

		if (
			empty( $_POST['nvb_visa_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['nvb_visa_nonce'] ) ),
				'nvb_save_visa'
			)
		) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$id                 = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$country_id         = intval( $_POST['country_id'] ?? 0 );
		$title              = sanitize_text_field( $_POST['title'] ?? '' );
		$duration           = sanitize_text_field( $_POST['duration'] ?? '' );
		$income_requirement = sanitize_text_field( $_POST['income_requirement'] ?? '' );
		$description        = wp_kses_post( $_POST['description'] ?? '' );
		$official_link      = esc_url_raw( $_POST['official_link'] ?? '' );

		if ( empty( $title ) || ! $country_id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_visa_programs&message=missing' ) );
			exit;
		}

		if ( $id ) {
			// update
			$wpdb->update(
				"{$prefix}nvb_visa_programs",
				array(
					'country_id'         => $country_id,
					'title'              => $title,
					'duration'           => $duration,
					'income_requirement' => $income_requirement,
					'description'        => $description,
					'official_link'      => $official_link,
					'updated_at'         => current_time( 'mysql' ),
				),
				array( 'id' => $id ),
				array( '%d', '%s', '%s', '%s', '%s', '%s', '%s' ),
				array( '%d' )
			);
			$message = 'updated';
		} else {
			// insert
			$wpdb->insert(
				"{$prefix}nvb_visa_programs",
				array(
					'country_id'         => $country_id,
					'title'              => $title,
					'duration'           => $duration,
					'income_requirement' => $income_requirement,
					'description'        => $description,
					'official_link'      => $official_link,
					'is_deleted'         => 0,
					'created_at'         => current_time( 'mysql' ),
					'updated_at'         => current_time( 'mysql' ),
				),
				array( '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s' )
			);
			$message = 'created';
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_visa_programs&message=' . $message ) );
		exit;
	}

	/**
	 * Soft delete visa program (set is_deleted = 1)
	 */
	public static function delete_visa() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}

		if (
			empty( $_GET['_wpnonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ),
				'nvb_delete_visa'
			)
		) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}

		$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
		if ( ! $id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_visa_programs&message=missing' ) );
			exit;
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$wpdb->update(
			"{$prefix}nvb_visa_programs",
			array(
				'is_deleted' => 1,
				'updated_at' => current_time( 'mysql' ),
			),
			array( 'id' => $id ),
			array( '%d', '%s' ),
			array( '%d' )
		);

		wp_redirect( admin_url( 'admin.php?page=nvb_visa_programs&message=deleted' ) );
		exit;
	}
}
