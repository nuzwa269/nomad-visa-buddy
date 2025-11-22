<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb;
$prefix = $wpdb->prefix;

// Countries dropdown
$countries = $wpdb->get_results(
	"SELECT id, name FROM {$prefix}nvb_countries WHERE is_deleted = 0 ORDER BY name ASC"
);

// Edit mode
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

// List records
$records = $wpdb->get_results(
	"SELECT col.*, c.name AS country_name
	 FROM {$prefix}nvb_cost_of_living col
	 LEFT JOIN {$prefix}nvb_countries c ON col.country_id = c.id
	 WHERE col.is_deleted = 0
	 ORDER BY c.name ASC"
);

$message = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';
?>

<div class="wrap nvb-admin nvb-col">
	<h1><?php esc_html_e( 'Cost of Living', 'nvb' ); ?></h1>

	<?php if ( $message ) : ?>
		<?php if ( 'created' === $message ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Entry created.', 'nvb' ); ?></p></div>
		<?php elseif ( 'updated' === $message ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Entry updated.', 'nvb' ); ?></p></div>
		<?php elseif ( 'deleted' === $message ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Entry deleted.', 'nvb' ); ?></p></div>
		<?php elseif ( 'missing' === $message ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'Please select a country.', 'nvb' ); ?></p></div>
		<?php endif; ?>
	<?php endif; ?>

	<hr />

	<h2><?php echo $edit_item ? esc_html__( 'Edit Cost of Living Entry', 'nvb' ) : esc_html__( 'Add New Cost of Living Entry', 'nvb' ); ?></h2>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'nvb_save_col', 'nvb_col_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_save_col" />

		<?php if ( $edit_item ) : ?>
			<input type="hidden" name="id" value="<?php echo esc_attr( $edit_item->id ); ?>" />
		<?php endif; ?>

		<table class="form-table">
			<tbody>

				<tr>
					<th><label for="country_id"><?php esc_html_e( 'Country', 'nvb' ); ?></label></th>
					<td>
						<select name="country_id" id="country_id" required>
							<option value=""><?php esc_html_e( 'Select country', 'nvb' ); ?></option>
							<?php foreach ( $countries as $c ) : ?>
								<option value="<?php echo esc_attr( $c->id ); ?>"
									<?php selected( $edit_item ? $edit_item->country_id : '', $c->id ); ?>>
									<?php echo esc_html( $c->name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<tr>
					<th><label for="rent"><?php esc_html_e( 'Rent (monthly)', 'nvb' ); ?></label></th>
					<td>
						<input type="text" name="rent" id="rent" class="regular-text"
							value="<?php echo esc_attr( $edit_item->rent ?? '' ); ?>" />
					</td>
				</tr>

				<tr>
					<th><label for="food"><?php esc_html_e( 'Food (monthly)', 'nvb' ); ?></label></th>
					<td>
						<input type="text" name="food" id="food" class="regular-text"
							value="<?php echo esc_attr( $edit_item->food ?? '' ); ?>" />
					</td>
				</tr>

				<tr>
					<th><label for="transport"><?php esc_html_e( 'Transport (monthly)', 'nvb' ); ?></label></th>
					<td>
						<input type="text" name="transport" id="transport" class="regular-text"
							value="<?php echo esc_attr( $edit_item->transport ?? '' ); ?>" />
					</td>
				</tr>

				<tr>
					<th><label for="internet"><?php esc_html_e( 'Internet (monthly)', 'nvb' ); ?></label></th>
					<td>
						<input type="text" name="internet" id="internet" class="regular-text"
							value="<?php echo esc_attr( $edit_item->internet ?? '' ); ?>" />
					</td>
				</tr>

				<tr>
					<th><label for="healthcare"><?php esc_html_e( 'Healthcare (monthly)', 'nvb' ); ?></label></th>
					<td>
						<input type="text" name="healthcare" id="healthcare" class="regular-text"
							value="<?php echo esc_attr( $edit_item->healthcare ?? '' ); ?>" />
					</td>
				</tr>

				<tr>
					<th><label for="lifestyle_score"><?php esc_html_e( 'Lifestyle Score (0–100)', 'nvb' ); ?></label></th>
					<td>
						<input type="number" name="lifestyle_score" id="lifestyle_score" class="small-text"
							min="0" max="100"
							value="<?php echo esc_attr( $edit_item->lifestyle_score ?? 50 ); ?>" />
						<p class="description">
							<?php esc_html_e( 'Rough score for overall comfort / lifestyle.', 'nvb' ); ?>
						</p>
					</td>
				</tr>

				<tr>
					<th><label for="notes"><?php esc_html_e( 'Notes', 'nvb' ); ?></label></th>
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

		<?php submit_button( $edit_item ? __( 'Update Entry', 'nvb' ) : __( 'Add Entry', 'nvb' ) ); ?>
	</form>

	<hr />

	<h2><?php esc_html_e( 'Existing Cost of Living Entries', 'nvb' ); ?></h2>

	<?php if ( $records ) : ?>
		<table class="widefat striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'ID', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Country', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Rent', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Food', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Lifestyle Score', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'nvb' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $records as $r ) : ?>
					<tr>
						<td><?php echo esc_html( $r->id ); ?></td>
						<td><?php echo esc_html( $r->country_name ); ?></td>
						<td><?php echo esc_html( $r->rent ); ?></td>
						<td><?php echo esc_html( $r->food ); ?></td>
						<td><?php echo esc_html( $r->lifestyle_score ); ?></td>
						<td>
							<?php
							$edit_url   = admin_url( 'admin.php?page=nvb_cost_of_living&edit=' . absint( $r->id ) );
							$delete_url = wp_nonce_url(
								admin_url( 'admin-post.php?action=nvb_delete_col&id=' . absint( $r->id ) ),
								'nvb_delete_col'
							);
							?>
							<a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Edit', 'nvb' ); ?></a> |
							<a href="<?php echo esc_url( $delete_url ); ?>"
							   onclick="return confirm('<?php echo esc_js( __( 'Delete this entry?', 'nvb' ) ); ?>');">
								<?php esc_html_e( 'Delete', 'nvb' ); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		<p><?php esc_html_e( 'No cost of living entries found.', 'nvb' ); ?></p>
	<?php endif; ?>

</div>
