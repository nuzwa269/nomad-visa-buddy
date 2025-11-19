<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_REST_API {
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
	}

	public static function register_routes() {
		register_rest_route( 'nvb/v1', '/countries', array(
			'methods'             => 'GET',
			'callback'            => array( __CLASS__, 'rest_countries' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'nvb/v1', '/country/(?P<id>[0-9a-zA-Z-_]+)', array(
			'methods'             => 'GET',
			'callback'            => array( __CLASS__, 'rest_country' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'nvb/v1', '/visa/(?P<id>\d+)', array(
			'methods'             => 'GET',
			'callback'            => array( __CLASS__, 'rest_visa' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'nvb/v1', '/checklist/(?P<id>[0-9a-zA-Z-_]+)', array(
			'methods'             => 'GET',
			'callback'            => array( __CLASS__, 'rest_checklist' ),
			'permission_callback' => '__return_true',
		) );
	}

	public static function rest_countries( $request ) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$rows = $wpdb->get_results( "SELECT id, slug, name, continent, currency, flag_url FROM {$prefix}nvb_countries WHERE is_deleted = 0" );
		return rest_ensure_response( $rows );
	}

	public static function rest_country( $request ) {
		$id = $request['id'];
		$country = nvb_get_country( $id );
		if ( ! $country ) {
			return new WP_Error( 'not_found', 'Country not found', array( 'status' => 404 ) );
		}
		global $wpdb;
		$prefix = $wpdb->prefix;
		$visa_programs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_visa_programs WHERE country_id = %d AND is_deleted = 0", $country->id ) );
		$documents = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_documents WHERE country_id = %d AND is_deleted = 0", $country->id ) );
		$steps = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_application_steps WHERE country_id = %d AND is_deleted = 0 ORDER BY step_number ASC", $country->id ) );
		$data = array(
			'country' => $country,
			'visa_programs' => $visa_programs,
			'documents' => $documents,
			'application_steps' => $steps,
		);
		return rest_ensure_response( $data );
	}

	public static function rest_visa( $request ) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$id = intval( $request['id'] );
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_visa_programs WHERE id = %d AND is_deleted = 0", $id ) );
		if ( ! $row ) {
			return new WP_Error( 'not_found', 'Visa program not found', array( 'status' => 404 ) );
		}
		return rest_ensure_response( $row );
	}

	public static function rest_checklist( $request ) {
		$id = $request['id'];
		$country = nvb_get_country( $id );
		if ( ! $country ) {
			return new WP_Error( 'not_found', 'Country not found', array( 'status' => 404 ) );
		}
		global $wpdb;
		$prefix = $wpdb->prefix;
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT title, is_required, note FROM {$prefix}nvb_documents WHERE country_id = %d AND is_deleted = 0", $country->id ), ARRAY_A );
		return rest_ensure_response( $rows );
	}
}
