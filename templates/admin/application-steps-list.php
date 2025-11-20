<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb;
$prefix = $wpdb->prefix;

// Countries dropdown
$countries = $wpdb->get_results(
	"SELECT id, name FROM {$prefix}nvb_countries WHERE is_deleted = 0 ORDER BY name ASC"
);

// Edit mode?
$edit_id   = isset( $_GET['edit'] ) ? intval( $_GET['edit'] ) : 0;
$edit_item = null;

if ( $edit_id ) {
	$edit_item = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$prefix}nvb_application_steps WHERE id = %d AND is_deleted = 0",
			$edit_id
		)
	);
}

// List all steps
$steps = $wpdb->get_results(
	"SELECT s.*, c.name AS country_name
	 FROM {$prefix}nvb_application_steps s
	 LEFT JOIN {$prefix}nvb_countries c ON s.country_id = c.id
	 WHERE s.is_deleted = 0
	 ORDER BY c.name ASC, s.step_number ASC"
);

$message = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';
?>

<div class="wrap nvb-admin nvb-steps">
	<h1><?php esc_html_e( 'Application Steps', 'nvb' ); ?></h1>

	<?php if ( $message ) : ?>
		<?php if ( 'created' === $message ) : ?>
			<div class="notice notice-success"><p>Step added.</p></div>
		<?php elseif ( 'updated' === $message ) : ?>
			<div class="notice notice-success"><p>Step updated.</p></div>
		<?php elseif ( 'deleted' === $message ) : ?>
			<div class="notice notice-success"><p>Step deleted.</p></div>
		<?php elseif ( 'missing' === $message ) : ?>
			<div class="notice notice-error"><p>Please fill all required fields.</p></div>
		<?php endif; ?>
	<?php endif; ?>

	<hr />

	<h2><?php echo $edit_item ? 'Edit Application Step' : 'Add New Application Step'; ?></h2>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'nvb_save_step', 'nvb_step_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_save_step">

		<?php if ( $edit_item ) : ?>
			<input type="hidden" name="id" value="<?php echo esc_attr( $edit_item->id ); ?>">
		<?php endif; ?>

		<table class="form-table">
			<tbody>

				<tr>
					<th><label for="nvb_country_id">Country</label></th>
					<td>
						<select name="country_id" id="nvb_country_id" required>
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
					<th><label for="nvb_step_number">Step Number</label></th>
					<td>
						<input type="number" name="step_number" id="nvb_step_number" class="small-text"
							value="<?php echo esc_attr( $edit_item ? $edit_item->step_number : 1 ); ?>">
						<p class="description">Lower numbers appear first.</p>
					</td>
				</tr>


				<tr>
					<th><label for="nvb_title">Step Title</label></th>
					<td>
						<input type="text" name="title" id="nvb_title" class="regular-text"
							value="<?php echo esc_attr( $edit_item ? $edit_item->title : '' ); ?>" required>
					</td>
				</tr>


				<tr>
					<th><label for="nvb_description">Description</label></th>
					<td>
						<?php
						$desc_value = $edit_item ? $edit_item->description : '';
						wp_editor(
							$desc_value,
							'nvb_description',
							array(
								'textarea_name' => 'description',
								'media_buttons' => false,
								'textarea_rows' => 4,
							)
						);
						?>
					</td>
				</tr>

			</tbody>
		</table>

		<?php submit_button( $edit_item ? 'Update Step' : 'Add Step' ); ?>
	</form>

	<hr />

	<h2>Existing Steps</h2>

	<?php if ( $steps ) : ?>
		<table class="widefat striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Country</th>
					<th>Step #</th>
					<th>Title</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>

				<?php foreach ( $steps as $s ) : ?>
					<tr>
						<td><?php echo esc_html( $s->id ); ?></td>
						<td><?php echo esc_html( $s->country_name ); ?></td>
						<td><?php echo esc_html( $s->step_number ); ?></td>
						<td><?php echo esc_html( $s->title ); ?></td>
						<td>
							<?php
							$edit_url   = admin_url( 'admin.php?page=nvb_application_steps&edit=' . absint( $s->id ) );
							$delete_url = wp_nonce_url(
								admin_url( 'admin-post.php?action=nvb_delete_step&id=' . absint( $s->id ) ),
								'nvb_delete_step'
							);
							?>
							<a href="<?php echo esc_url( $edit_url ); ?>">Edit</a> |
							<a href="<?php echo esc_url( $delete_url ); ?>" onclick="return confirm('Delete this step?');">Delete</a>
						</td>
					</tr>
				<?php endforeach; ?>

			</tbody>
		</table>

	<?php else : ?>
		<p>No application steps found.</p>
	<?php endif; ?>
</div>
