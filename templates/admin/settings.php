<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure option is set before using it.
$items_per_page = '';
if ( isset( $options['items_per_page'] ) ) {
	$items_per_page = absint( $options['items_per_page'] );
}
?>
<div class="wrap nvb-admin-wrap">
	<h1>Nomad Visa Hub Settings</h1>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'nvb_save_settings', 'nvb_settings_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_save_settings" />

		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="items_per_page">Items per page (admin)</label>
				</th>
				<td>
					<input
						type="number"
						name="items_per_page"
						id="items_per_page"
						value="<?php echo esc_attr( $items_per_page ); ?>"
						min="1"
					/>
				</td>
			</tr>
		</table>

		<p>
			<button class="button button-primary" type="submit">Save Settings</button>
		</p>
	</form>
</div>
