<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;
$prefix = $wpdb->prefix;

// تمام countries dropdown کیلئے
$countries = $wpdb->get_results(
	"SELECT id, name FROM {$prefix}nvb_countries WHERE is_deleted = 0 ORDER BY name ASC"
);

// اگر edit mode ہے تو وہ ریکارڈ لوڈ کریں
$edit_id   = isset( $_GET['edit'] ) ? intval( $_GET['edit'] ) : 0;
$edit_visa = null;

if ( $edit_id ) {
	$edit_visa = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$prefix}nvb_visa_programs WHERE id = %d AND is_deleted = 0",
			$edit_id
		)
	);
}

// لسٹ کیلئے تمام visa programs (soft-deleted کے علاوہ)
$visa_programs = $wpdb->get_results(
	"SELECT v.*, c.name AS country_name
	 FROM {$prefix}nvb_visa_programs v
	 LEFT JOIN {$prefix}nvb_countries c ON v.country_id = c.id
	 WHERE v.is_deleted = 0
	 ORDER BY c.name ASC, v.title ASC"
);

// Notice message
$message = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';
?>

<div class="wrap nvb-admin nvb-visa-programs">
	<h1><?php esc_html_e( 'Visa Programs', 'nvb' ); ?></h1>

	<?php if ( $message ) : ?>
		<?php if ( 'created' === $message ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Visa program created.', 'nvb' ); ?></p></div>
		<?php elseif ( 'updated' === $message ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Visa program updated.', 'nvb' ); ?></p></div>
		<?php elseif ( 'deleted' === $message ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Visa program deleted.', 'nvb' ); ?></p></div>
		<?php elseif ( 'missing' === $message ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'Please select a country and enter a title.', 'nvb' ); ?></p></div>
		<?php endif; ?>
	<?php endif; ?>

	<hr />

	<h2>
		<?php echo $edit_visa ? esc_html__( 'Edit Visa Program', 'nvb' ) : esc_html__( 'Add New Visa Program', 'nvb' ); ?>
	</h2>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'nvb_save_visa', 'nvb_visa_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_save_visa" />
		<?php if ( $edit_visa ) : ?>
			<input type="hidden" name="id" value="<?php echo esc_attr( $edit_visa->id ); ?>" />
		<?php endif; ?>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="nvb_country_id"><?php esc_html_e( 'Country', 'nvb' ); ?></label></th>
					<td>
						<select name="country_id" id="nvb_country_id" class="regular-text">
							<option value=""><?php esc_html_e( 'Select a country', 'nvb' ); ?></option>
							<?php foreach ( $countries as $country ) : ?>
								<option value="<?php echo esc_attr( $country->id ); ?>" <?php selected( $edit_visa ? $edit_visa->country_id : '', $country->id ); ?>>
									<?php echo esc_html( $country->name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="nvb_title"><?php esc_html_e( 'Program Name', 'nvb' ); ?></label></th>
					<td>
						<input type="text" name="title" id="nvb_title" class="regular-text"
							value="<?php echo esc_attr( $edit_visa ? $edit_visa->title : '' ); ?>" />
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="nvb_duration"><?php esc_html_e( 'Duration / Validity', 'nvb' ); ?></label></th>
					<td>
						<input type="text" name="duration" id="nvb_duration" class="regular-text"
							placeholder="<?php esc_attr_e( 'e.g. 1 year, renewable', 'nvb' ); ?>"
							value="<?php echo esc_attr( $edit_visa ? $edit_visa->duration : '' ); ?>" />
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="nvb_income_requirement"><?php esc_html_e( 'Income Requirement', 'nvb' ); ?></label></th>
					<td>
						<input type="text" name="income_requirement" id="nvb_income_requirement" class="regular-text"
							placeholder="<?php esc_attr_e( 'e.g. $3,000/month', 'nvb' ); ?>"
							value="<?php echo esc_attr( $edit_visa ? $edit_visa->income_requirement : '' ); ?>" />
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="nvb_official_link"><?php esc_html_e( 'Official Link', 'nvb' ); ?></label></th>
					<td>
						<input type="url" name="official_link" id="nvb_official_link" class="regular-text"
							placeholder="<?php esc_attr_e( 'https://', 'nvb' ); ?>"
							value="<?php echo esc_url( $edit_visa ? $edit_visa->official_link : '' ); ?>" />
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="nvb_description"><?php esc_html_e( 'Description', 'nvb' ); ?></label></th>
					<td>
						<?php
						$desc_value = $edit_visa ? $edit_visa->description : '';
						wp_editor(
							$desc_value,
							'nvb_description',
							array(
								'textarea_name' => 'description',
								'textarea_rows' => 5,
								'media_buttons' => false,
							)
						);
						?>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button( $edit_visa ? __( 'Update Visa Program', 'nvb' ) : __( 'Add Visa Program', 'nvb' ) ); ?>
	</form>

	<hr />

	<h2><?php esc_html_e( 'Existing Visa Programs', 'nvb' ); ?></h2>

	<?php if ( $visa_programs ) : ?>
		<table class="widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'ID', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Country', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Program Name', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Income Requirement', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Duration', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Official Link', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'nvb' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $visa_programs as $visa ) : ?>
					<tr>
						<td><?php echo esc_html( $visa->id ); ?></td>
						<td><?php echo esc_html( $visa->country_name ); ?></td>
						<td><?php echo esc_html( $visa->title ); ?></td>
						<td><?php echo esc_html( $visa->income_requirement ); ?></td>
						<td><?php echo esc_html( $visa->duration ); ?></td>
						<td>
							<?php if ( ! empty( $visa->official_link ) ) : ?>
								<a href="<?php echo esc_url( $visa->official_link ); ?>" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'View', 'nvb' ); ?>
								</a>
							<?php else : ?>
								&mdash;
							<?php endif; ?>
						</td>
						<td>
							<?php
							$edit_url   = admin_url( 'admin.php?page=nvb_visa_programs&edit=' . absint( $visa->id ) );
							$delete_url = wp_nonce_url(
								admin_url( 'admin-post.php?action=nvb_delete_visa&id=' . absint( $visa->id ) ),
								'nvb_delete_visa'
							);
							?>
							<a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Edit', 'nvb' ); ?></a> |
							<a href="<?php echo esc_url( $delete_url ); ?>" onclick="return confirm('<?php echo esc_js( __( 'Are you sure you want to delete this visa program?', 'nvb' ) ); ?>');">
								<?php esc_html_e( 'Delete', 'nvb' ); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		<p><?php esc_html_e( 'No visa programs found.', 'nvb' ); ?></p>
	<?php endif; ?>
</div>
