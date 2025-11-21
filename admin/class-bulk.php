<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Bulk {

	public static function init() {
		// Countries.
		add_action( 'admin_post_nvb_bulk_import', array( __CLASS__, 'bulk_import' ) );
		add_action( 'admin_post_nvb_bulk_export', array( __CLASS__, 'bulk_export' ) );

		// Visa programs bulk import.
		add_action( 'admin_post_nvb_bulk_import_visa_programs', array( __CLASS__, 'bulk_import_visa_programs' ) );

		// Eligibility bulk import.
		add_action( 'admin_post_nvb_bulk_import_eligibility', array( __CLASS__, 'bulk_import_eligibility' ) );
	}

	public static function bulk_page() {
		include NVB_PLUGIN_DIR . 'templates/admin/bulk.php';
	}

	/**
	 * Bulk import: Countries.
	 * CSV: slug,name,continent,currency,flag_url,description
	 */
	public static function bulk_import() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}

		if (
			empty( $_POST['nvb_bulk_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['nvb_bulk_nonce'] ) ),
				'nvb_bulk_action'
			)
		) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}

		// Basic CSV import for countries.
		if ( ! empty( $_FILES['nvb_csv']['tmp_name'] ) ) {
			$csv    = array_map( 'str_getcsv', file( $_FILES['nvb_csv']['tmp_name'] ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file
			$header = array_shift( $csv );

			global $wpdb;
			$prefix = $wpdb->prefix;

			foreach ( $csv as $row ) {
				if ( empty( $row ) ) {
					continue;
				}

				$data = array_combine( $header, $row );
				if ( false === $data ) {
					continue;
				}

				if ( empty( $data['slug'] ) || empty( $data['name'] ) ) {
					continue;
				}

				$wpdb->insert(
					"{$prefix}nvb_countries",
					array(
						'slug'        => sanitize_text_field( $data['slug'] ),
						'name'        => sanitize_text_field( $data['name'] ),
						'continent'   => sanitize_text_field( $data['continent'] ?? '' ),
						'currency'    => sanitize_text_field( $data['currency'] ?? '' ),
						'flag_url'    => esc_url_raw( $data['flag_url'] ?? '' ),
						'description' => sanitize_text_field( $data['description'] ?? '' ),
						'created_at'  => current_time( 'mysql' ),
						'updated_at'  => current_time( 'mysql' ),
					)
				);
			}
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_bulk&message=imported' ) );
		exit;
	}

	/**
	 * Bulk import: Visa Programs.
	 *
	 * CSV header:
	 * country_slug,program_title,duration,income_requirement,official_link,description
	 *
	 * ایک country کیلئے جتنی لائنیں ہوں گی اتنے ہی visa programs بنیں گے۔
	 */
	public static function bulk_import_visa_programs() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}

		if (
			empty( $_POST['nvb_bulk_visa_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['nvb_bulk_visa_nonce'] ) ),
				'nvb_bulk_visa_action'
			)
		) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}

		if ( empty( $_FILES['nvb_visa_csv']['tmp_name'] ) ) {
			wp_die( esc_html__( 'No CSV file uploaded.', 'nvb' ) );
		}

		$csv    = array_map( 'str_getcsv', file( $_FILES['nvb_visa_csv']['tmp_name'] ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file
		$header = array_shift( $csv );

		if ( empty( $header ) ) {
			wp_die( esc_html__( 'Empty CSV file.', 'nvb' ) );
		}

		global $wpdb;
		$prefix          = $wpdb->prefix;
		$countries_table = "{$prefix}nvb_countries";
		$visa_table      = "{$prefix}nvb_visa_programs";

		foreach ( $csv as $row ) {
			if ( empty( $row ) ) {
				continue;
			}

			$data = array_combine( $header, $row );
			if ( false === $data ) {
				continue;
			}

			$country_slug = isset( $data['country_slug'] ) ? sanitize_title( $data['country_slug'] ) : '';
			$title        = isset( $data['program_title'] ) ? sanitize_text_field( $data['program_title'] ) : '';

			if ( '' === $country_slug || '' === $title ) {
				continue;
			}

			// Country slug سے ID نکالیں۔
			$country = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT id FROM {$countries_table} WHERE slug = %s AND is_deleted = 0",
					$country_slug
				)
			);

			if ( ! $country ) {
				// اگر country نہ ملے تو row skip کریں۔
				continue;
			}

			$duration           = isset( $data['duration'] ) ? sanitize_text_field( $data['duration'] ) : '';
			$income_requirement = isset( $data['income_requirement'] ) ? sanitize_text_field( $data['income_requirement'] ) : '';
			$official_link      = isset( $data['official_link'] ) ? esc_url_raw( $data['official_link'] ) : '';
			$description        = isset( $data['description'] ) ? wp_kses_post( $data['description'] ) : '';

			$wpdb->insert(
				$visa_table,
				array(
					'country_id'         => (int) $country->id,
					'title'              => $title,
					'duration'           => $duration,
					'income_requirement' => $income_requirement,
					'official_link'      => $official_link,
					'description'        => $description,
					'created_at'         => current_time( 'mysql' ),
					'updated_at'         => current_time( 'mysql' ),
				)
			);
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_bulk&message=visa_imported' ) );
		exit;
	}

	/**
	 * Bulk import: Eligibility Q&A.
	 *
	 * CSV header:
	 * country_slug,question,answer
	 *
	 * ایک ملک کے لیے جتنی لائنیں ہوں گی اتنے ہی eligibility items بنیں گے۔
	 */
	public static function bulk_import_eligibility() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}

		if (
			empty( $_POST['nvb_bulk_eligibility_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['nvb_bulk_eligibility_nonce'] ) ),
				'nvb_bulk_eligibility_action'
			)
		) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}

		if ( empty( $_FILES['nvb_eligibility_csv']['tmp_name'] ) ) {
			wp_die( esc_html__( 'No CSV file uploaded.', 'nvb' ) );
		}

		$csv    = array_map( 'str_getcsv', file( $_FILES['nvb_eligibility_csv']['tmp_name'] ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file
		$header = array_shift( $csv );

		if ( empty( $header ) ) {
			wp_die( esc_html__( 'Empty CSV file.', 'nvb' ) );
		}

		global $wpdb;
		$prefix            = $wpdb->prefix;
		$countries_table   = "{$prefix}nvb_countries";
		$eligibility_table = "{$prefix}nvb_eligibility";

		foreach ( $csv as $row ) {
			if ( empty( $row ) ) {
				continue;
			}

			$data = array_combine( $header, $row );
			if ( false === $data ) {
				continue;
			}

			$country_slug = isset( $data['country_slug'] ) ? sanitize_title( $data['country_slug'] ) : '';
			$question     = isset( $data['question'] ) ? sanitize_text_field( $data['question'] ) : '';
			$answer_raw   = isset( $data['answer'] ) ? $data['answer'] : '';

			if ( '' === $country_slug || '' === $question ) {
				continue;
			}

			// Country slug سے country_id نکالیں۔
			$country = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT id FROM {$countries_table} WHERE slug = %s AND is_deleted = 0",
					$country_slug
				)
			);

			if ( ! $country ) {
				continue;
			}

			$answer = wp_kses_post( $answer_raw );

			$wpdb->insert(
				$eligibility_table,
				array(
					'country_id' => (int) $country->id,
					'question'   => $question,
					'answer'     => $answer,
					'created_at' => current_time( 'mysql' ),
					'updated_at' => current_time( 'mysql' ),
				)
			);
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_bulk&message=eligibility_imported' ) );
		exit;
	}

	/**
	 * Bulk export: Countries.
	 */
	public static function bulk_export() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}

		global $wpdb;
		$prefix = $wpdb->prefix;
		$rows   = $wpdb->get_results(
			"SELECT slug, name, continent, currency, flag_url, description
			 FROM {$prefix}nvb_countries
			 WHERE is_deleted = 0",
			ARRAY_A
		);

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
