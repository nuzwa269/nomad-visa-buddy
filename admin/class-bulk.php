<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Bulk {

	public static function init() {

		// Countries
		add_action( 'admin_post_nvb_bulk_import', array( __CLASS__, 'bulk_import' ) );
		add_action( 'admin_post_nvb_bulk_export', array( __CLASS__, 'bulk_export' ) );

		// Visa Programs
		add_action( 'admin_post_nvb_bulk_import_visa_programs', array( __CLASS__, 'bulk_import_visa_programs' ) );

		// Eligibility
		add_action( 'admin_post_nvb_bulk_import_eligibility', array( __CLASS__, 'bulk_import_eligibility' ) );

		// Documents Checklist
		add_action( 'admin_post_nvb_bulk_import_documents', array( __CLASS__, 'bulk_import_documents' ) );

		// ⭐ Application Steps
		add_action( 'admin_post_nvb_bulk_import_steps', array( __CLASS__, 'bulk_import_steps' ) );
	}

	public static function bulk_page() {
		include NVB_PLUGIN_DIR . 'templates/admin/bulk.php';
	}

	/**
	 * Bulk import: COUNTRIES
	 */
	public static function bulk_import() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Unauthorized' );
		}

		if (
			empty( $_POST['nvb_bulk_nonce'] ) ||
			! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nvb_bulk_nonce'] ) ), 'nvb_bulk_action' )
		) {
			wp_die( 'Invalid nonce' );
		}

		if ( ! empty( $_FILES['nvb_csv']['tmp_name'] ) ) {

			$csv    = array_map( 'str_getcsv', file( $_FILES['nvb_csv']['tmp_name'] ) );
			$header = array_shift( $csv );

			global $wpdb;
			$prefix = $wpdb->prefix;

			foreach ( $csv as $row ) {
				if ( empty( $row ) ) continue;

				$data = @array_combine( $header, $row );
				if ( empty( $data['slug'] ) || empty( $data['name'] ) ) continue;

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
	 * Bulk import: VISA PROGRAMS
	 */
	public static function bulk_import_visa_programs() {

		if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

		if (
			empty( $_POST['nvb_bulk_visa_nonce'] ) ||
			! wp_verify_nonce( $_POST['nvb_bulk_visa_nonce'], 'nvb_bulk_visa_action' )
		) {
			wp_die( 'Invalid nonce' );
		}

		if ( ! file_exists( $_FILES['nvb_visa_csv']['tmp_name'] ) ) wp_die( 'No CSV uploaded' );

		$csv    = array_map( 'str_getcsv', file( $_FILES['nvb_visa_csv']['tmp_name'] ) );
		$header = array_shift( $csv );

		global $wpdb;
		$prefix = $wpdb->prefix;

		foreach ( $csv as $row ) {
			if ( empty( $row ) ) continue;
			$data = @array_combine( $header, $row );

			$country_slug = sanitize_title( $data['country_slug'] ?? '' );
			$title        = sanitize_text_field( $data['program_title'] ?? '' );
			if ( ! $country_slug || ! $title ) continue;

			$country = $wpdb->get_row(
				$wpdb->prepare( "SELECT id FROM {$prefix}nvb_countries WHERE slug=%s AND is_deleted=0", $country_slug )
			);
			if ( ! $country ) continue;

			$wpdb->insert(
				"{$prefix}nvb_visa_programs",
				array(
					'country_id'         => $country->id,
					'title'              => $title,
					'duration'           => sanitize_text_field( $data['duration'] ?? '' ),
					'income_requirement' => sanitize_text_field( $data['income_requirement'] ?? '' ),
					'official_link'      => esc_url_raw( $data['official_link'] ?? '' ),
					'description'        => wp_kses_post( $data['description'] ?? '' ),
					'created_at'         => current_time( 'mysql' ),
					'updated_at'         => current_time( 'mysql' ),
				)
			);
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_bulk&message=visa_imported' ) );
		exit;
	}

	/**
	 * Bulk import: ELIGIBILITY
	 */
	public static function bulk_import_eligibility() {

		if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

		if (
			empty( $_POST['nvb_bulk_eligibility_nonce'] ) ||
			! wp_verify_nonce( $_POST['nvb_bulk_eligibility_nonce'], 'nvb_bulk_eligibility_action' )
		) {
			wp_die( 'Invalid nonce' );
		}

		if ( ! file_exists( $_FILES['nvb_eligibility_csv']['tmp_name'] ) ) wp_die( 'No CSV uploaded' );

		$csv    = array_map( 'str_getcsv', file( $_FILES['nvb_eligibility_csv']['tmp_name'] ) );
		$header = array_shift( $csv );

		global $wpdb;
		$prefix = $wpdb->prefix;

		foreach ( $csv as $row ) {

			if ( empty( $row ) ) continue;

			$data = @array_combine( $header, $row );

			$country_slug = sanitize_title( $data['country_slug'] ?? '' );
			$question     = sanitize_text_field( $data['question'] ?? '' );
			if ( ! $country_slug || ! $question ) continue;

			$country = $wpdb->get_row(
				$wpdb->prepare( "SELECT id FROM {$prefix}nvb_countries WHERE slug=%s AND is_deleted=0", $country_slug )
			);
			if ( ! $country ) continue;

			$wpdb->insert(
				"{$prefix}nvb_eligibility",
				array(
					'country_id' => $country->id,
					'question'   => $question,
					'answer'     => wp_kses_post( $data['answer'] ?? '' ),
					'created_at' => current_time( 'mysql' ),
					'updated_at' => current_time( 'mysql' ),
				)
			);
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_bulk&message=eligibility_imported' ) );
		exit;
	}

	/**
	 * Bulk import: DOCUMENTS
	 */
	public static function bulk_import_documents() {

		if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

		if (
			empty( $_POST['nvb_bulk_documents_nonce'] ) ||
			! wp_verify_nonce( $_POST['nvb_bulk_documents_nonce'], 'nvb_bulk_documents_action' )
		) {
			wp_die( 'Invalid nonce' );
		}

		if ( ! file_exists( $_FILES['nvb_documents_csv']['tmp_name'] ) ) wp_die( 'No CSV uploaded' );

		$csv    = array_map( 'str_getcsv', file( $_FILES['nvb_documents_csv']['tmp_name'] ) );
		$header = array_shift( $csv );

		global $wpdb;
		$prefix = $wpdb->prefix;

		foreach ( $csv as $row ) {

			if ( empty( $row ) ) continue;

			$data = @array_combine( $header, $row );

			$country_slug = sanitize_title( $data['country_slug'] ?? '' );
			$title        = sanitize_text_field( $data['title'] ?? '' );
			if ( ! $country_slug || ! $title ) continue;

			$country = $wpdb->get_row(
				$wpdb->prepare( "SELECT id FROM {$prefix}nvb_countries WHERE slug=%s AND is_deleted=0", $country_slug )
			);
			if ( ! $country ) continue;

			$is_required = strtolower( trim( $data['is_required'] ?? '' ) );
			$is_required = in_array( $is_required, ['1','yes','y','true','required'], true ) ? 1 : 0;

			$wpdb->insert(
				"{$prefix}nvb_documents",
				array(
					'country_id'  => $country->id,
					'title'       => $title,
					'is_required' => $is_required,
					'note'        => wp_kses_post( $data['notes'] ?? '' ),
					'created_at'  => current_time( 'mysql' ),
					'updated_at'  => current_time( 'mysql' ),
				)
			);
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_bulk&message=documents_imported' ) );
		exit;
	}

	/**
	 * ⭐⭐ NEW — Bulk import: APPLICATION STEPS
	 * CSV: country_slug,step_number,title,description,external_link,screenshot_url
	 */
	public static function bulk_import_steps() {

		if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

		if (
			empty( $_POST['nvb_bulk_steps_nonce'] ) ||
			! wp_verify_nonce( $_POST['nvb_bulk_steps_nonce'], 'nvb_bulk_steps_action' )
		) {
			wp_die( 'Invalid nonce' );
		}

		if ( ! file_exists( $_FILES['nvb_steps_csv']['tmp_name'] ) ) wp_die( 'No CSV uploaded' );

		$csv    = array_map( 'str_getcsv', file( $_FILES['nvb_steps_csv']['tmp_name'] ) );
		$header = array_shift( $csv );

		global $wpdb;
		$prefix = $wpdb->prefix;

		foreach ( $csv as $row ) {
			if ( empty( $row ) ) continue;

			$data = @array_combine( $header, $row );

			$country_slug = sanitize_title( $data['country_slug'] ?? '' );
			$title        = sanitize_text_field( $data['title'] ?? '' );

			if ( ! $country_slug || ! $title ) continue;

			$country = $wpdb->get_row(
				$wpdb->prepare( "SELECT id FROM {$prefix}nvb_countries WHERE slug=%s AND is_deleted=0", $country_slug )
			);
			if ( ! $country ) continue;

			$wpdb->insert(
				"{$prefix}nvb_application_steps",
				array(
					'country_id'     => $country->id,
					'visa_program_id'=> 0,
					'step_number'    => intval( $data['step_number'] ?? 1 ),
					'title'          => $title,
					'description'    => wp_kses_post( $data['description'] ?? '' ),
					'external_link'  => esc_url_raw( $data['external_link'] ?? '' ),
					'screenshot_url' => esc_url_raw( $data['screenshot_url'] ?? '' ),
					'created_at'     => current_time( 'mysql' ),
					'updated_at'     => current_time( 'mysql' ),
					'is_deleted'     => 0,
				)
			);
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_bulk&message=steps_imported' ) );
		exit;
	}

	/**
	 * Export countries CSV
	 */
	public static function bulk_export() {
		global $wpdb;
		$prefix = $wpdb->prefix;

		$rows = $wpdb->get_results(
			"SELECT slug,name,continent,currency,flag_url,description 
			 FROM {$prefix}nvb_countries 
			 WHERE is_deleted=0",
			ARRAY_A
		);

		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment; filename=countries_export.csv' );

		$out = fopen( 'php://output', 'w' );

		if ( $rows ) {
			fputcsv( $out, array_keys( $rows[0] ) );
			foreach ( $rows as $r ) fputcsv( $out, $r );
		}

		fclose( $out );
		exit;
	}
}
