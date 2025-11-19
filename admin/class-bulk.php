<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Bulk {
	public static function init() {
		add_action( 'admin_post_nvb_bulk_import', array( __CLASS__, 'bulk_import' ) );
		add_action( 'admin_post_nvb_bulk_export', array( __CLASS__, 'bulk_export' ) );
	}
	public static function bulk_page() {
		include NVB_PLUGIN_DIR . 'templates/admin/bulk.php';
	}

	public static function bulk_import() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}
		if ( empty( $_POST['nvb_bulk_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nvb_bulk_nonce'] ) ), 'nvb_bulk_action' ) ) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}
		// Basic CSV import for countries only as example
		if ( ! empty( $_FILES['nvb_csv']['tmp_name'] ) ) {
			$csv = array_map( 'str_getcsv', file( $_FILES['nvb_csv']['tmp_name'] ) );
			$header = array_shift( $csv );
			global $wpdb;
			$prefix = $wpdb->prefix;
			foreach ( $csv as $row ) {
				$data = array_combine( $header, $row );
				if ( empty( $data['slug'] ) || empty( $data['name'] ) ) {
					continue;
				}
				$wpdb->insert(
					"{$prefix}nvb_countries",
					array(
						'slug' => sanitize_text_field( $data['slug'] ),
						'name' => sanitize_text_field( $data['name'] ),
						'continent' => sanitize_text_field( $data['continent'] ?? '' ),
						'currency' => sanitize_text_field( $data['currency'] ?? '' ),
						'flag_url' => esc_url_raw( $data['flag_url'] ?? '' ),
						'description' => sanitize_text_field( $data['description'] ?? '' ),
						'created_at' => current_time( 'mysql' ),
						'updated_at' => current_time( 'mysql' ),
					)
				);
			}
		}
		wp_redirect( admin_url( 'admin.php?page=nvb_bulk&message=imported' ) );
		exit;
	}

	public static function bulk_export() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}
		// Export countries CSV as example
		global $wpdb;
		$prefix = $wpdb->prefix;
		$rows = $wpdb->get_results( "SELECT slug, name, continent, currency, flag_url, description FROM {$prefix}nvb_countries WHERE is_deleted = 0", ARRAY_A );
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment; filename=nvb_countries_export.csv' );
		$out = fopen( 'php://output', 'w' );
		if ( $rows ) {
			fputcsv( $out, array_keys( $rows[0] ) );
			foreach ( $rows as $r ) {
				fputcsv( $out, $r );
			}
		}
		fclose( $out );
		exit;
	}
}
