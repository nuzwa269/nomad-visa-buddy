<?php
/*
Plugin Name: Nomad Visa Hub
Description: A complete Digital Nomad Visa directory with admin CMS, frontend views, shortcodes, and custom WP database tables.
Version: 1.0.0
Author: NomadVisaHub.com
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NVB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'NVB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'NVB_VERSION', '1.0.0' );

require_once NVB_PLUGIN_DIR . 'includes/class-activator.php';
require_once NVB_PLUGIN_DIR . 'includes/class-deactivator.php';
require_once NVB_PLUGIN_DIR . 'includes/helpers.php';

require_once NVB_PLUGIN_DIR . 'admin/class-admin-menu.php';
require_once NVB_PLUGIN_DIR . 'admin/class-countries.php';
require_once NVB_PLUGIN_DIR . 'admin/class-visa-programs.php';
require_once NVB_PLUGIN_DIR . 'admin/class-eligibility.php';
require_once NVB_PLUGIN_DIR . 'admin/class-documents.php';
require_once NVB_PLUGIN_DIR . 'admin/class-application-steps.php';
require_once NVB_PLUGIN_DIR . 'admin/class-tax-info.php';
require_once NVB_PLUGIN_DIR . 'admin/class-cost-of-living.php';
require_once NVB_PLUGIN_DIR . 'admin/class-faqs.php';
require_once NVB_PLUGIN_DIR . 'admin/class-bulk.php';
require_once NVB_PLUGIN_DIR . 'admin/class-settings.php';

require_once NVB_PLUGIN_DIR . 'public/class-shortcodes.php';
require_once NVB_PLUGIN_DIR . 'public/class-rest-api.php';

// Activation & deactivation hooks
register_activation_hook( __FILE__, array( 'NVB_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'NVB_Deactivator', 'deactivate' ) );

// Init plugin
add_action( 'plugins_loaded', 'nvb_init' );

function nvb_init() {
	// Load admin menu if in admin
	if ( is_admin() ) {
		NVB_Admin_Menu::init();
		NVB_Countries::init();
		NVB_Visa_Programs::init();
		NVB_Eligibility::init();
		NVB_Documents::init();
		NVB_Application_Steps::init();
		NVB_Tax_Info::init();
		NVB_Cost_Of_Living::init();
		NVB_FAQs::init();
		NVB_Bulk::init();
		NVB_Settings::init();
		// Admin assets
		add_action( 'admin_enqueue_scripts', 'nvb_enqueue_admin_assets' );
	}
	// Public
	NVB_Shortcodes::init();
	NVB_REST_API::init();
	add_action( 'wp_enqueue_scripts', 'nvb_enqueue_public_assets' );
}

// Enqueue admin CSS/JS
function nvb_enqueue_admin_assets() {
	wp_enqueue_style( 'nvb-admin-css', NVB_PLUGIN_URL . 'assets/css/admin.css', array(), NVB_VERSION );
	wp_enqueue_script( 'nvb-admin-js', NVB_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), NVB_VERSION, true );
	wp_localize_script( 'nvb-admin-js', 'nvb_admin', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'nvb_admin_nonce' ),
	) );
}

// Enqueue public CSS/JS
function nvb_enqueue_public_assets() {
	wp_enqueue_style( 'nvb-public-css', NVB_PLUGIN_URL . 'assets/css/public.css', array(), NVB_VERSION );
	wp_enqueue_script( 'nvb-public-js', NVB_PLUGIN_URL . 'assets/js/public.js', array( 'jquery' ), NVB_VERSION, true );
	wp_enqueue_script( 'nvb-checklist-js', NVB_PLUGIN_URL . 'assets/js/checklist.js', array( 'jquery' ), NVB_VERSION, true );
	wp_localize_script( 'nvb-checklist-js', 'nvb_public', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'nvb_public_nonce' ),
	) );
}

// Uninstall file will be executed when plugin is deleted via WP admin. See uninstall.php
