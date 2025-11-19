```php
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap nvb-admin-wrap">
	<h1>Bulk Import / Export</h1>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
		<?php wp_nonce_field( 'nvb_bulk_action', 'nvb_bulk_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_bulk_import" />
		<p><label>Upload CSV (slug,name,continent,currency,flag_url,description):</label><br/>
		<input type="file" name="nvb_csv" accept=".csv" /></p>
		<p><button class="button button-primary">Import</button></p>
	</form>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'nvb_bulk_action', 'nvb_bulk_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_bulk_export" />
		<p><button class="button">Export Countries CSV</button></p>
	</form>
</div>
```
