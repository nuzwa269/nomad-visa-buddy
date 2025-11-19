<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_Activator {
	public static function activate() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$prefix = $wpdb->prefix;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$tables = array();

		$tables[] = "CREATE TABLE {$prefix}nvb_countries (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			slug VARCHAR(200) NOT NULL,
			name VARCHAR(200) NOT NULL,
			continent VARCHAR(100) DEFAULT '',
			currency VARCHAR(50) DEFAULT '',
			flag_url VARCHAR(255) DEFAULT '',
			description TEXT,
			created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			is_deleted TINYINT(1) DEFAULT 0,
			PRIMARY KEY  (id),
			UNIQUE KEY slug (slug)
		) {$charset_collate};";

		$tables[] = "CREATE TABLE {$prefix}nvb_visa_programs (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			country_id BIGINT(20) UNSIGNED NOT NULL,
			title VARCHAR(200) NOT NULL,
			duration VARCHAR(100) DEFAULT '',
			income_requirement VARCHAR(255) DEFAULT '',
			description TEXT,
			official_link VARCHAR(255) DEFAULT '',
			created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			is_deleted TINYINT(1) DEFAULT 0,
			PRIMARY KEY (id),
			KEY country_id (country_id)
		) {$charset_collate};";

		$tables[] = "CREATE TABLE {$prefix}nvb_eligibility (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			country_id BIGINT(20) UNSIGNED NOT NULL,
			visa_program_id BIGINT(20) UNSIGNED DEFAULT 0,
			question VARCHAR(255) DEFAULT '',
			answer TEXT,
			created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			is_deleted TINYINT(1) DEFAULT 0,
			PRIMARY KEY (id),
			KEY country_id (country_id),
			KEY visa_program_id (visa_program_id)
		) {$charset_collate};";

		$tables[] = "CREATE TABLE {$prefix}nvb_documents (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			country_id BIGINT(20) UNSIGNED NOT NULL,
			visa_program_id BIGINT(20) UNSIGNED DEFAULT 0,
			title VARCHAR(255) DEFAULT '',
			is_required TINYINT(1) DEFAULT 1,
			note TEXT,
			created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			is_deleted TINYINT(1) DEFAULT 0,
			PRIMARY KEY (id),
			KEY country_id (country_id),
			KEY visa_program_id (visa_program_id)
		) {$charset_collate};";

		$tables[] = "CREATE TABLE {$prefix}nvb_application_steps (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			country_id BIGINT(20) UNSIGNED NOT NULL,
			visa_program_id BIGINT(20) UNSIGNED DEFAULT 0,
			step_number INT(11) DEFAULT 0,
			title VARCHAR(255) DEFAULT '',
			description TEXT,
			external_link VARCHAR(255) DEFAULT '',
			screenshot_url VARCHAR(255) DEFAULT '',
			created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			is_deleted TINYINT(1) DEFAULT 0,
			PRIMARY KEY (id),
			KEY country_id (country_id),
			KEY visa_program_id (visa_program_id)
		) {$charset_collate};";

		$tables[] = "CREATE TABLE {$prefix}nvb_tax_info (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			country_id BIGINT(20) UNSIGNED NOT NULL,
			info_title VARCHAR(255) DEFAULT '',
			description TEXT,
			tax_rate VARCHAR(100) DEFAULT '',
			created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			is_deleted TINYINT(1) DEFAULT 0,
			PRIMARY KEY (id),
			KEY country_id (country_id)
		) {$charset_collate};";

		$tables[] = "CREATE TABLE {$prefix}nvb_cost_of_living (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			country_id BIGINT(20) UNSIGNED NOT NULL,
			rent DECIMAL(10,2) DEFAULT 0,
			food DECIMAL(10,2) DEFAULT 0,
			transport DECIMAL(10,2) DEFAULT 0,
			internet DECIMAL(10,2) DEFAULT 0,
			healthcare DECIMAL(10,2) DEFAULT 0,
			lifestyle_score INT(3) DEFAULT 50,
			notes TEXT,
			created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			is_deleted TINYINT(1) DEFAULT 0,
			PRIMARY KEY (id),
			KEY country_id (country_id)
		) {$charset_collate};";

		$tables[] = "CREATE TABLE {$prefix}nvb_faqs (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			country_id BIGINT(20) UNSIGNED NOT NULL,
			visa_program_id BIGINT(20) UNSIGNED DEFAULT 0,
			question VARCHAR(255) DEFAULT '',
			answer TEXT,
			created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			is_deleted TINYINT(1) DEFAULT 0,
			PRIMARY KEY (id),
			KEY country_id (country_id),
			KEY visa_program_id (visa_program_id)
		) {$charset_collate};";

		$tables[] = "CREATE TABLE {$prefix}nvb_verification (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			country_id BIGINT(20) UNSIGNED DEFAULT 0,
			entity VARCHAR(200) DEFAULT '',
			status VARCHAR(50) DEFAULT 'pending',
			details TEXT,
			created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			is_deleted TINYINT(1) DEFAULT 0,
			PRIMARY KEY (id),
			KEY country_id (country_id)
		) {$charset_collate};";

		foreach ( $tables as $sql ) {
			dbDelta( $sql );
		}

		// Option defaults
		if ( ! get_option( 'nvb_settings' ) ) {
			$defaults = array(
				'items_per_page' => 20,
			);
			add_option( 'nvb_settings', $defaults );
		}
	}
}
