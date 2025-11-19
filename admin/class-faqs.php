<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NVB_FAQs {
	public static function init() {
		add_action( 'admin_post_nvb_save_faq', array( __CLASS__, 'save_faq' ) );
	}
	public static function faqs_page() {
		include NVB_PLUGIN_DIR . 'templates/admin/faqs-list.php';
	}

	public static function save_faq() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'nvb' ) );
		}
		if ( empty( $_POST['nvb_faq_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nvb_faq_nonce'] ) ), 'nvb_save_faq' ) ) {
			wp_die( esc_html__( 'Invalid nonce', 'nvb' ) );
		}
		global $wpdb;
		$prefix = $wpdb->prefix;

		$country_id = intval( $_POST['country_id'] ?? 0 );
		$visa_program_id = intval( $_POST['visa_program_id'] ?? 0 );
		$question = sanitize_text_field( $_POST['question'] ?? '' );
		$answer = wp_kses_post( $_POST['answer'] ?? '' );

		if ( empty( $question ) || ! $country_id ) {
			wp_redirect( admin_url( 'admin.php?page=nvb_faqs&message=missing' ) );
			exit;
		}

		$wpdb->insert(
			"{$prefix}nvb_faqs",
			array(
				'country_id' => $country_id,
				'visa_program_id' => $visa_program_id,
				'question' => $question,
				'answer' => $answer,
				'created_at' => current_time( 'mysql' ),
				'updated_at' => current_time( 'mysql' ),
			),
			array( '%d', '%d', '%s', '%s', '%s', '%s' )
		);

		wp_redirect( admin_url( 'admin.php?page=nvb_faqs&message=success' ) );
		exit;
	}
}
