<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;
$prefix = $wpdb->prefix;

// Only attempt to drop tables if option is present OR always drop for cleanup
$tables = array(
	"{$prefix}nvb_countries",
	"{$prefix}nvb_visa_programs",
	"{$prefix}nvb_eligibility",
	"{$prefix}nvb_documents",
	"{$prefix}nvb_application_steps",
	"{$prefix}nvb_tax_info",
	"{$prefix}nvb_cost_of_living",
	"{$prefix}nvb_faqs",
	"{$prefix}nvb_verification",
);

foreach ( $tables as $table ) {
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
}

// Remove options
delete_option( 'nvb_settings' );