<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Shortcodes {
	public static function init() {
		add_shortcode( 'nvb_country_directory', array( __CLASS__, 'country_directory' ) );
		add_shortcode( 'nvb_country_detail', array( __CLASS__, 'country_detail' ) );
		add_shortcode( 'nvb_application_guide', array( __CLASS__, 'application_guide' ) );
		add_shortcode( 'nvb_document_checklist', array( __CLASS__, 'document_checklist' ) );
		add_shortcode( 'nvb_cost_of_living', array( __CLASS__, 'cost_of_living' ) );
		// AJAX actions for checklist exports
		add_action( 'wp_ajax_nvb_export_checklist_csv', array( __CLASS__, 'export_checklist_csv' ) );
		add_action( 'wp_ajax_nopriv_nvb_export_checklist_csv', array( __CLASS__, 'export_checklist_csv' ) );
	}

        public static function country_directory( $atts = array() ) {
                $atts = shortcode_atts(
                        array(
                                // Detailing page slug or ID; default remains the original slug-based approach.
                                'detail_page' => 'country-detail',
                        ),
                        $atts,
                        'nvb_country_directory'
                );

                // Resolve the detail page URL whether the user supplies a slug or an ID.
                $detail_url = '';
                if ( is_numeric( $atts['detail_page'] ) ) {
                        $detail_url = get_permalink( intval( $atts['detail_page'] ) );
                } elseif ( ! empty( $atts['detail_page'] ) ) {
                        $page = get_page_by_path( sanitize_title( $atts['detail_page'] ) );
                        $detail_url = $page ? get_permalink( $page ) : '';
                }

                global $wpdb;
                $prefix = $wpdb->prefix;
                $countries = $wpdb->get_results( "SELECT * FROM {$prefix}nvb_countries WHERE is_deleted = 0 ORDER BY name ASC" );
                ob_start();
                include NVB_PLUGIN_DIR . 'templates/frontend/directory.php';
                return ob_get_clean();
        }

	public static function country_detail( $atts = array() ) {
		// پہلے جیسا: shortcode attributes
		$atts = shortcode_atts(
			array(
				'id' => '',
			),
			$atts,
			'nvb_country_detail'
		);

		// ✨ نیا logic: اگر shortcode میں id خالی ہو تو URL سے ?country=slug پکڑیں
		if ( empty( $atts['id'] ) && isset( $_GET['country'] ) ) {
			// wp_unslash تاکہ magic quotes وغیرہ ہٹ جائیں، پھر sanitize
			$atts['id'] = sanitize_text_field( wp_unslash( $_GET['country'] ) );
		}

		// اگر پھر بھی id خالی ہو تو error دکھائیں
		if ( empty( $atts['id'] ) ) {
			return '<p>' . esc_html__( 'No country selected.', 'nvb' ) . '</p>';
		}

		// باقی سب پہلے جیسا ہی: nvb_get_country id یا slug سے ریکارڈ نکالے گا
		$country = nvb_get_country( $atts['id'] );
		if ( ! $country ) {
			return '<p>' . esc_html__( 'Country not found.', 'nvb' ) . '</p>';
		}

		global $wpdb;
		$prefix = $wpdb->prefix;
		$visa_programs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_visa_programs WHERE country_id = %d AND is_deleted = 0", $country->id ) );
		$eligibility   = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_eligibility WHERE country_id = %d AND is_deleted = 0", $country->id ) );
		$documents     = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_documents WHERE country_id = %d AND is_deleted = 0", $country->id ) );
		$steps         = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_application_steps WHERE country_id = %d AND is_deleted = 0 ORDER BY step_number ASC", $country->id ) );
		$tax           = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_tax_info WHERE country_id = %d AND is_deleted = 0", $country->id ) );
		$col           = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_cost_of_living WHERE country_id = %d AND is_deleted = 0", $country->id ) );
		$faqs          = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_faqs WHERE country_id = %d AND is_deleted = 0", $country->id ) );

		ob_start();
		include NVB_PLUGIN_DIR . 'templates/frontend/detail.php';
		return ob_get_clean();
	}

	public static function application_guide( $atts = array() ) {
		$atts = shortcode_atts( array( 'id' => '' ), $atts, 'nvb_application_guide' );
		$country = nvb_get_country( $atts['id'] );
		if ( ! $country ) {
			return '<p>' . esc_html__( 'Country not found.', 'nvb' ) . '</p>';
		}
		global $wpdb;
		$prefix = $wpdb->prefix;
		$steps = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_application_steps WHERE country_id = %d AND is_deleted = 0 ORDER BY step_number ASC", $country->id ) );
		ob_start();
		include NVB_PLUGIN_DIR . 'templates/frontend/application-guide.php';
		return ob_get_clean();
	}

	public static function document_checklist( $atts = array() ) {
		$atts = shortcode_atts( array( 'id' => '' ), $atts, 'nvb_document_checklist' );
		$country = nvb_get_country( $atts['id'] );
		if ( ! $country ) {
			return '<p>' . esc_html__( 'Country not found.', 'nvb' ) . '</p>';
		}
		global $wpdb;
		$prefix = $wpdb->prefix;
		$documents = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_documents WHERE country_id = %d AND is_deleted = 0 ORDER BY is_required DESC, title ASC", $country->id ) );
		ob_start();
		include NVB_PLUGIN_DIR . 'templates/frontend/document-checklist.php';
		return ob_get_clean();
	}

	public static function cost_of_living( $atts = array() ) {
		$atts = shortcode_atts( array( 'id' => '' ), $atts, 'nvb_cost_of_living' );
		$country = nvb_get_country( $atts['id'] );
		if ( ! $country ) {
			return '<p>' . esc_html__( 'Country not found.', 'nvb' ) . '</p>';
		}
		global $wpdb;
		$prefix = $wpdb->prefix;
		$col = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_cost_of_living WHERE country_id = %d AND is_deleted = 0", $country->id ) );
		ob_start();
		include NVB_PLUGIN_DIR . 'templates/frontend/cost-of-living.php';
		return ob_get_clean();
	}

	public static function export_checklist_csv() {
		// Exports checklist via AJAX for both logged and guest users
		if ( empty( $_POST['country_id'] ) ) {
			wp_send_json_error( 'Missing country_id' );
		}
		$country_id = intval( $_POST['country_id'] );
		global $wpdb;
		$prefix = $wpdb->prefix;
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT title, is_required, note FROM {$prefix}nvb_documents WHERE country_id = %d AND is_deleted = 0", $country_id ), ARRAY_A );
		if ( ! $rows ) {
			wp_send_json_error( 'No documents' );
		}
		$csv = fopen( 'php://temp', 'r+' );
		fputcsv( $csv, array( 'Title', 'Is Required', 'Note' ) );
		foreach ( $rows as $r ) {
			fputcsv( $csv, array( $r['title'], $r['is_required'] ? 'Required' : 'Optional', strip_tags( $r['note'] ) ) );
		}
		rewind( $csv );
		$data = stream_get_contents( $csv );
		fclose( $csv );
		wp_send_json_success( array( 'csv' => base64_encode( $data ) ) );
	}
}
