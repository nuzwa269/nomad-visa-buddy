```php
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap nvb-admin-wrap">
	<h1>Nomad Visa Hub Settings</h1>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'nvb_save_settings', 'nvb_settings_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_save_settings" />
		<table class="form-table">
			<tr>
				<th><label for="items_per_page">Items per page (admin)</label></th>
				<td><input type="number" name="items_per_page" id="items_per_page" value="<?php echo esc_attr( $options['items_per_page'] ); ?>" /></td>
			</tr>
		</table>
		<p><button class="button button-primary">Save Settings</button></p>
	</form>
</div>
```
