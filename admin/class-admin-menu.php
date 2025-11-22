<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Admin_Menu {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
	}

	public static function register_menu() {

		add_menu_page(
			'Nomad Visa Hub',
			'Nomad Visa Hub',
			'manage_options',
			'nvb_dashboard',
			array( __CLASS__, 'dashboard_page' ),
			'dashicons-palmtree',
			56
		);

		add_submenu_page(
			'nvb_dashboard',
			'Countries',
			'Countries',
			'manage_options',
			'nvb_countries',
			array( 'NVB_Countries', 'countries_page' )
		);

		add_submenu_page(
			'nvb_dashboard',
			'Visa Programs',
			'Visa Programs',
			'manage_options',
			'nvb_visa_programs',
			array( 'NVB_Visa_Programs', 'visa_programs_page' )
		);

		add_submenu_page(
			'nvb_dashboard',
			'Eligibility',
			'Eligibility',
			'manage_options',
			'nvb_eligibility',
			array( 'NVB_Eligibility', 'eligibility_page' )
		);

		add_submenu_page(
			'nvb_dashboard',
			'Documents',
			'Documents',
			'manage_options',
			'nvb_documents',
			array( 'NVB_Documents', 'documents_page' )
		);

		// Updated callback for Application Steps (per patch)
		add_submenu_page(
			'nvb_dashboard',
			'Application Steps',
			'Application Steps',
			'manage_options',
			'nvb_application_steps',
			array( 'NVB_Application_Steps', 'application_steps_page' )
		);

		// Updated callback for Tax Info (per patch)
		add_submenu_page(
			'nvb_dashboard',
			'Tax Info',
			'Tax Info',
			'manage_options',
			'nvb_tax_info',
			array( 'NVB_Tax_Info', 'tax_info_page' )
		);

		add_submenu_page(
			'nvb_dashboard',
			'Cost of Living',
			'Cost of Living',
			'manage_options',
			'nvb_cost_of_living',
			array( 'NVB_Cost_Of_Living', 'col_page' )
		);

		add_submenu_page(
			'nvb_dashboard',
			'FAQs',
			'FAQs',
			'manage_options',
			'nvb_faqs',
			array( 'NVB_FAQs', 'faqs_page' )
		);

		add_submenu_page(
			'nvb_dashboard',
			'Bulk Import/Export',
			'Bulk Import/Export',
			'manage_options',
			'nvb_bulk',
			array( 'NVB_Bulk', 'bulk_page' )
		);

		add_submenu_page(
			'nvb_dashboard',
			'Settings',
			'Settings',
			'manage_options',
			'nvb_settings',
			array( 'NVB_Settings', 'settings_page' )
		);
	}

	public static function dashboard_page() {
		?>
		<div class="wrap">
			<h1>Nomad Visa Hub</h1>
			<p>Welcome to Nomad Visa Hub admin. Use the left menu to manage countries, programs, eligibility, documents and more.</p>
		</div>
		<?php
	}
}
