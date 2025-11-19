<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Simple helper to fetch country by slug or id
 */
function nvb_get_country( $identifier ) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	if ( is_numeric( $identifier ) ) {
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_countries WHERE id = %d AND is_deleted = 0", $identifier ) );
	} else {
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$prefix}nvb_countries WHERE slug = %s AND is_deleted = 0", sanitize_text_field( $identifier ) ) );
	}
	return $row;
}
