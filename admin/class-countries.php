<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Countries {
	public static function init() {
		add_action( 'admin_post_nvb_save_country', array( __CLASS__, 'save_country' ) );
		add_action( 'admin_post_nvb_delete_country', array( __CLASS__, 'delete_country' ) );
	}

	public static function countries_page() {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$paged = isset( $_GET['paged'] ) ? intval( $_GET['paged'] ) : 1;
		$per_page = isset( $_GET['per_page'] ) ? intval( $_GET['per_page'] ) : get_option( 'nvb_settings' )['items_per_page'] ?? 20;
		$search = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

		$offset = ( $paged - 1 ) * $per_page;
		$where = "WHERE is_deleted = 0";
		$params = array();

		if ( $search ) {
			$where .= " AND (name LIKE %s OR slug LIKE %s)";
			$like = '%' . $wpdb->esc_like( $search ) . '%';
			$params[] = $like;
			$params[] = $like;
		}

		$total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$prefix}nvb_countries {$where}", $params ) );
		$countries = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_countries {$where} ORDER BY name ASC LIMIT %d OFFSET %d", array_merge( $params, array( $per_page, $offset ) ) ) );

		// Include templates
		include NVB_PLUGIN_DIR . 'templates/admin/countries-list.php';
	}

	public static function save_country() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}
		if ( empty( $_POST['nvb_country_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nvb_country_nonce'] ) ), 'nvb_save_country' ) ) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}
		global $wpdb;
		$prefix = $wpdb->prefix;

		$id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$slug = sanitize_text_field( $_POST['slug'] ?? '' );
		$name = sanitize_text_field( $_POST['name'] ?? '' );
		$continent = sanitize_text_field( $_POST['continent'] ?? '' );
		$currency = sanitize_text_field( $_POST['currency'] ?? '' );
		$flag_url = esc_url_raw( $_POST['flag_url'] ?? '' );
		$description = wp_kses_post( $_POST['description'] ?? '' );

		if ( empty( $name ) || empty( $slug ) ) {
			wp_redirect( add_query_arg( array( 'page' => 'nvb_countries', 'message' => 'missing' ), admin_url( 'admin.php' ) ) );
			exit;
		}

		if ( $id ) {
			$wpdb->update(
				"{$prefix}nvb_countries",
				array(
					'slug'        => $slug,
					'name'        => $name,
					'continent'   => $continent,
					'currency'    => $currency,
					'flag_url'    => $flag_url,
					'description' => $description,
					'updated_at'  => current_time( 'mysql' ),
				),
				array( 'id' => $id ),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
				),
				array( '%d' )
			);
		} else {
			$wpdb->insert(
				"{$prefix}nvb_countries",
				array(
					'slug'        => $slug,
					'name'        => $name,
					'continent'   => $continent,
					'currency'    => $currency,
					'flag_url'    => $flag_url,
					'description' => $description,
					'created_at'  => current_time( 'mysql' ),
					'updated_at'  => current_time( 'mysql' ),
				),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
				)
			);
		}

		wp_redirect( admin_url( 'admin.php?page=nvb_countries&message=success' ) );
		exit;
	}

	public static function delete_country() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}
		if ( empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'nvb_delete_country' ) ) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}
		$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
		if ( ! $id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_countries&message=invalid' ) );
		}

		global $wpdb;
		$prefix = $wpdb->prefix;
		$wpdb->update(
			"{$prefix}nvb_countries",
			array( 'is_deleted' => 1, 'updated_at' => current_time( 'mysql' ) ),
			array( 'id' => $id ),
			array( '%d', '%s' ),
			array( '%d' )
		);

		wp_redirect( admin_url( 'admin.php?page=nvb_countries&message=deleted' ) );
		exit;
	}
}
