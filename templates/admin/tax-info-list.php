<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb;
$prefix = $wpdb->prefix;

// Countries dropdown
$countries = $wpdb->get_results(
	"SELECT id, name FROM {$prefix}nvb_countries WHERE is_deleted = 0 ORDER BY name ASC"
);

// Edit item
$edit_id   = isset( $_GET['edit'] ) ? intval( $_GET['edit'] ) : 0;
$edit_item = null;

if ( $edit_id ) {
	$edit_item = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$prefix}nvb_tax_info WHERE id = %d AND is_deleted = 0",
			$edit_id
		)
	);
}

// List of all tax info records
$records = $wpdb->get_results(
	"SELECT t.*, c.name AS country_name
	 FROM {$prefix}nvb_tax_info t
	 LEFT JOIN {$prefix}nvb_countries c ON t.country_id = c.id
	 WHERE t.is_deleted = 0
	 ORDER BY c.name ASC"
);

$message = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';
?>

<div class="wrap nvb-admin nvb-tax-info">

	<h1><?php esc_html_e( 'Tax Information', 'nvb' ); ?></h1>

	<?php if ( $message ) : ?>
		<?php if ( 'created' === $message ) : ?>
			<div class="notice notice-success"><p>Tax info added.</p></div>
		<?php elseif ( 'updated' === $message ) : ?>
			<div class="notice notice-success"><p>Tax info updated.</p></div>
		<?php elseif ( 'deleted' === $message ) : ?>
			<div class="notice notice-success"><p>Tax info deleted.</p></div>
		<?php elseif ( 'missing' === $message ) : ?>
			<div class="notice notice-error"><p>Please fill required fields.</p></div>
		<?php endif; ?>
	<?php endif; ?>

	<hr />

	<h2><?php echo $edit_item ? 'Edit Tax Entry' : 'Add New Tax Entry'; ?></h2>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'nvb_save_tax', 'nvb_tax_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_save_tax">

		<?php if ( $edit_item ) : ?>
			<input type="hidden" name="id" value="<?php echo esc_attr( $edit_item->id ); ?>">
		<?php endif; ?>

		<table class="form-table">

			<tr>
				<th><label for="country">Country</label></th>
				<td>
					<select name="country_id" id="country">
						<option value="">Select country</option>
						<?php foreach ( $countries as $country ) : ?>
							<option value="<?php echo esc_attr( $country->id ); ?>"
								<?php selected( $edit_item ? $edit_item->country_id : '', $country->id ); ?>>
								<?php echo esc_html( $country->name ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>

			<tr>
				<th><label for="info_title">Title</label></th>
				<td>
					<input type="text" name="info_title" id="info_title" class="regular-text"
						placeholder="e.g., Income tax notes"
						value="<?php echo esc_attr( $edit_item ? $edit_item->info_title : '' ); ?>">
				</td>
			</tr>

			<tr>
				<th><label for="tax_rate">Tax Rate / Summary</label></th>
				<td>
					<input type="text" name="tax_rate" id="tax_rate" class="regular-text"
						placeholder="e.g., 20% income tax, 0% remote worker tax"
						value="<?php echo esc_attr( $edit_item ? $edit_item->tax_rate : '' ); ?>">
				</td>
			</tr>

			<tr>
				<th><label for="description">Detailed Notes</label></th>
				<td>
					<?php
					wp_editor(
						$edit_item ? $edit_item->description : '',
						'nvb_tax_details',
						array(
							'textarea_name' => 'description',
							'textarea_rows' => 5,
							'media_buttons' => false,
						)
					);
					?>
				</td>
			</tr>

		</table>

		<?php submit_button( $edit_item ? 'Update Tax Info' : 'Add Tax Info' ); ?>
	</form>

	<hr>

	<h2>Existing Tax Entries</h2>

	<?php if ( $records ) : ?>
		<table class="widefat striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Country</th>
					<th>Tax Rate</th>
					<th>Actions</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ( $records as $t ) : ?>
					<tr>
						<td><?php echo esc_html( $t->id ); ?></td>
						<td><?php echo esc_html( $t->country_name ); ?></td>
						<td><?php echo esc_html( $t->tax_rate ); ?></td>
						<td>
							<?php
							$edit_url   = admin_url( 'admin.php?page=nvb_tax_info&edit=' . absint( $t->id ) );
							$delete_url = wp_nonce_url(
								admin_url( 'admin-post.php?action=nvb_delete_tax&id=' . absint( $t->id ) ),
								'nvb_delete_tax'
							);
							?>
							<a href="<?php echo esc_url( $edit_url ); ?>">Edit</a> |
							<a href="<?php echo esc_url( $delete_url ); ?>" onclick="return confirm('Delete this entry?');">
								Delete
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	<?php else : ?>
		<p>No tax information found.</p>
	<?php endif; ?>

</div>
