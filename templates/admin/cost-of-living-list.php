<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb;
$prefix = $wpdb->prefix;

// Countries dropdown
$countries = $wpdb->get_results(
	"SELECT id, name FROM {$prefix}nvb_countries WHERE is_deleted = 0 ORDER BY name ASC"
);

$edit_id   = isset( $_GET['edit'] ) ? intval( $_GET['edit'] ) : 0;
$edit_item = null;

if ( $edit_id ) {
	$edit_item = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$prefix}nvb_cost_of_living WHERE id = %d AND is_deleted = 0",
			$edit_id
		)
	);
}

// list of all records
$records = $wpdb->get_results(
	"SELECT c.*, co.name AS country_name
	 FROM {$prefix}nvb_cost_of_living c
	 LEFT JOIN {$prefix}nvb_countries co ON c.country_id = co.id
	 WHERE c.is_deleted = 0
	 ORDER BY co.name ASC"
);

$message = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';
?>

<div class="wrap nvb-admin nvb-col">
	<h1>Cost of Living</h1>

	<?php if ( $message ) : ?>
		<?php if ( 'created' === $message ) : ?>
			<div class="notice notice-success"><p>Entry created.</p></div>
		<?php elseif ( 'updated' === $message ) : ?>
			<div class="notice notice-success"><p>Entry updated.</p></div>
		<?php elseif ( 'deleted' === $message ) : ?>
			<div class="notice notice-success"><p>Entry deleted.</p></div>
		<?php elseif ( 'missing' === $message ) : ?>
			<div class="notice notice-error"><p>Please fill all required fields.</p></div>
		<?php endif; ?>
	<?php endif; ?>

	<hr>

	<h2><?php echo $edit_item ? "Edit Cost Entry" : "Add New Cost Entry"; ?></h2>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'nvb_save_col', 'nvb_col_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_save_col">

		<?php if ( $edit_item ) : ?>
			<input type="hidden" name="id" value="<?php echo esc_attr( $edit_item->id ); ?>">
		<?php endif; ?>

		<table class="form-table">
			<tbody>

				<tr>
					<th><label for="country_id">Country</label></th>
					<td>
						<select name="country_id" id="country_id" required>
							<option value="">Select country</option>
							<?php foreach ( $countries as $c ) : ?>
								<option value="<?php echo esc_attr( $c->id ); ?>"
									<?php selected( $edit_item ? $edit_item->country_id : '', $c->id ); ?>>
									<?php echo esc_html( $c->name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<tr><th>Rent</th><td><input type="text" name="rent" class="regular-text" value="<?php echo esc_attr( $edit_item->rent ?? '' ); ?>"></td></tr>
				<tr><th>Food</th><td><input type="text" name="food" class="regular-text" value="<?php echo esc_attr( $edit_item->food ?? '' ); ?>"></td></tr>
				<tr><th>Transport</th><td><input type="text" name="transport" class="regular-text" value="<?php echo esc_attr( $edit_item->transport ?? '' ); ?>"></td></tr>
				<tr><th>Internet</th><td><input type="text" name="internet" class="regular-text" value="<?php echo esc_attr( $edit_item->internet ?? '' ); ?>"></td></tr>
				<tr><th>Coworking</th><td><input type="text" name="coworking" class="regular-text" value="<?php echo esc_attr( $edit_item->coworking ?? '' ); ?>"></td></tr>

				<tr>
					<th><label for="monthly_estimate">Monthly Estimate *</label></th>
					<td><input type="text" required name="monthly_estimate" class="regular-text" value="<?php echo esc_attr( $edit_item->monthly_estimate ?? '' ); ?>"></td>
				</tr>

				<tr>
					<th>Notes</th>
					<td>
						<?php
						wp_editor(
							$edit_item->notes ?? '',
							'nvb_col_notes',
							array(
								'textarea_name' => 'notes',
								'textarea_rows' => 4,
								'media_buttons' => false,
							)
						);
						?>
					</td>
				</tr>

			</tbody>
		</table>

		<?php submit_button( $edit_item ? "Update Entry" : "Add Entry" ); ?>
	</form>

	<hr>

	<h2>Existing Entries</h2>

	<?php if ( $records ) : ?>
		<table class="widefat striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Country</th>
					<th>Monthly Cost</th>
					<th>Actions</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ( $records as $r ) : ?>
					<tr>
						<td><?php echo esc_html( $r->id ); ?></td>
						<td><?php echo esc_html( $r->country_name ); ?></td>
						<td><?php echo esc_html( $r->monthly_estimate ); ?></td>
						<td>
							<?php
							$edit_url   = admin_url( 'admin.php?page=nvb_cost_of_living&edit=' . absint( $r->id ) );
							$delete_url = wp_nonce_url(
								admin_url( 'admin-post.php?action=nvb_delete_col&id=' . absint( $r->id ) ),
								'nvb_delete_col'
							);
							?>
							<a href="<?php echo esc_url( $edit_url ); ?>">Edit</a> |
							<a href="<?php echo esc_url( $delete_url ); ?>" onclick="return confirm('Delete this entry?');">Delete</a>
						</td>
				 </tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	<?php else : ?>
		<p>No cost of living entries found.</p>
	<?php endif; ?>

</div>
