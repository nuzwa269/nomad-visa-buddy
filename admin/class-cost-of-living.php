<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class NVB_Cost_Of_Living {

	public static function init() {

		// save (create / update)
		add_action( 'admin_post_nvb_save_col', array( __CLASS__, 'save_col' ) );

		// delete (soft delete)
		add_action( 'admin_post_nvb_delete_col', array( __CLASS__, 'delete_col' ) );
	}

	public static function col_page() {
		include NVB_PLUGIN_DIR . 'templates/admin/cost-of-living-list.php';
	}

	/**
	 * Insert / Update Cost of Living record
	 */
	public static function save_col() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Unauthorized', 'nvb' ) );
		}

		if ( empty( $_POST['nvb_col_nonce'] ) ||
		     ! wp_verify_nonce(
			     sanitize_text_field( wp_unslash( $_POST['nvb_col_nonce'] ) ),
			     'nvb_save_col'
		     ) ) {
			wp_die( __( 'Invalid nonce', 'nvb' ) );
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$id             = intval( $_POST['id'] ?? 0 );
		$country_id     = intval( $_POST['country_id'] ?? 0 );

		// decimal fields as strings, DB خود cast کر لے گا
		$rent           = isset( $_POST['rent'] ) ? sanitize_text_field( $_POST['rent'] ) : '';
		$food           = isset( $_POST['food'] ) ? sanitize_text_field( $_POST['food'] ) : '';
		$transport      = isset( $_POST['transport'] ) ? sanitize_text_field( $_POST['transport'] ) : '';
		$internet       = isset( $_POST['internet'] ) ? sanitize_text_field( $_POST['internet'] ) : '';
		$healthcare     = isset( $_POST['healthcare'] ) ? sanitize_text_field( $_POST['healthcare'] ) : '';
		$lifestyle_score= isset( $_POST['lifestyle_score'] ) ? intval( $_POST['lifestyle_score'] ) : 50;

		$notes          = wp_kses_post( $_POST['notes'] ?? '' );

		if ( ! $country_id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_cost_of_living&message=missing' ) );
			exit;
		}

		if ( $id ) {

			$wpdb->update(
				"{$prefix}nvb_cost_of_living",
				array(
					'country_id'      => $country_id,
					'rent'            => $rent,
					'food'            => $food,
					'transport'       => $transport,
					'internet'        => $internet,
					'healthcare'      => $healthcare,
					'lifestyle_score' => $lifestyle_score,
					'notes'           => $notes,
					'updated_at'      => current_time( 'mysql' ),
				),
				array( 'id' => $id ),
				array( '%d','%f','%f','%f','%f','%f','%d','%s','%s' ),
				array( '%d' )
			);

			$message = 'updated';

		} else {

			$wpdb->insert(
				"{$prefix}nvb_cost_of_living",
				array(
					'country_id'      => $country_id,
					'rent'            => $rent,
					'food'            => $food,
					'transport'       => $transport,
					'internet'        => $internet,
					'healthcare'      => $healthcare,
					'lifestyle_score' => $lifestyle_score,
					'notes'           => $notes,
					'is_deleted'      => 0,
					'created_at'      => current_time( 'mysql' ),
					'updated_at'      => current_time( 'mysql' ),
				),
				array( '%d','%f','%f','%f','%f','%f','%d','%s','%d','%s','%s' )
			);

			$message = 'created';
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_cost_of_living&message=' . $message ) );
		exit;
	}

	/**
	 * Soft delete
	 */
	public static function delete_col() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Unauthorized', 'nvb' ) );
		}

		if ( empty( $_GET['_wpnonce'] ) ||
		     ! wp_verify_nonce(
			     sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ),
			     'nvb_delete_col'
		     ) ) {
			wp_die( __( 'Invalid nonce', 'nvb' ) );
		}

		$id = intval( $_GET['id'] ?? 0 );
		if ( ! $id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_cost_of_living&message=missing' ) );
			exit;
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$wpdb->update(
			"{$prefix}nvb_cost_of_living",
			array(
				'is_deleted' => 1,
				'updated_at' => current_time( 'mysql' ),
			),
			array( 'id' => $id ),
			array( '%d', '%s' ),
			array( '%d' )
		);

		wp_redirect( admin_url( 'admin.php?page=nvb_cost_of_living&message=deleted' ) );
		exit;
	}
}
