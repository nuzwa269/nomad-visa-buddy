<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
			"SELECT * FROM {$prefix}nvb_eligibility WHERE id = %d AND is_deleted = 0",
			$edit_id
		)
	);
}

// List all eligibility Q&A
$eligibility_qs = $wpdb->get_results(
	"SELECT e.*, c.name AS country_name
	 FROM {$prefix}nvb_eligibility e
	 LEFT JOIN {$prefix}nvb_countries c ON e.country_id = c.id
	 WHERE e.is_deleted = 0
	 ORDER BY c.name ASC, e.id ASC"
);

// Message
$message = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';
?>

<div class="wrap nvb-admin nvb-eligibility">
	<h1><?php esc_html_e( 'Eligibility Q&A', 'nvb' ); ?></h1>

	<?php if ( $message ) : ?>
		<?php if ( 'created' === $message ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Eligibility item created.', 'nvb' ); ?></p></div>
		<?php elseif ( 'updated' === $message ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Eligibility item updated.', 'nvb' ); ?></p></div>
		<?php elseif ( 'deleted' === $message ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Eligibility item deleted.', 'nvb' ); ?></p></div>
		<?php elseif ( 'missing' === $message ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'Please select a country and enter a question.', 'nvb' ); ?></p></div>
		<?php endif; ?>
	<?php endif; ?>

	<hr />

	<h2>
		<?php echo $edit_item ? esc_html__( 'Edit Eligibility Item', 'nvb' ) : esc_html__( 'Add New Eligibility Item', 'nvb' ); ?>
	</h2>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'nvb_save_eligibility', 'nvb_eligibility_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_save_eligibility" />
		<?php if ( $edit_item ) : ?>
			<input type="hidden" name="id" value="<?php echo esc_attr( $edit_item->id ); ?>" />
		<?php endif; ?>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row">
						<label for="nvb_country_id"><?php esc_html_e( 'Country', 'nvb' ); ?></label>
					</th>
					<td>
						<select name="country_id" id="nvb_country_id" class="regular-text">
							<option value=""><?php esc_html_e( 'Select a country', 'nvb' ); ?></option>
							<?php foreach ( $countries as $country ) : ?>
								<option value="<?php echo esc_attr( $country->id ); ?>" <?php selected( $edit_item ? $edit_item->country_id : '', $country->id ); ?>>
									<?php echo esc_html( $country->name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="nvb_question"><?php esc_html_e( 'Question', 'nvb' ); ?></label>
					</th>
					<td>
						<input type="text" name="question" id="nvb_question" class="regular-text"
							value="<?php echo esc_attr( $edit_item ? $edit_item->question : '' ); ?>" />
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="nvb_answer"><?php esc_html_e( 'Answer', 'nvb' ); ?></label>
					</th>
					<td>
						<?php
						$answer_value = $edit_item ? $edit_item->answer : '';
						wp_editor(
							$answer_value,
							'nvb_answer',
							array(
								'textarea_name' => 'answer',
								'textarea_rows' => 4,
								'media_buttons' => false,
							)
						);
						?>
					</td>
				</tr>
			</tbody>
		</table>

		<?php
		submit_button(
			$edit_item
				? __( 'Update Eligibility Item', 'nvb' )
				: __( 'Add Eligibility Item', 'nvb' )
		);
		?>
	</form>

	<hr />

	<h2><?php esc_html_e( 'Existing Eligibility Items', 'nvb' ); ?></h2>

	<?php if ( $eligibility_qs ) : ?>
		<table class="widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'ID', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Country', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Question', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'nvb' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $eligibility_qs as $item ) : ?>
					<tr>
						<td><?php echo esc_html( $item->id ); ?></td>
						<td><?php echo esc_html( $item->country_name ); ?></td>
						<td><?php echo esc_html( $item->question ); ?></td>
						<td>
							<?php
							$edit_url   = admin_url( 'admin.php?page=nvb_eligibility&edit=' . absint( $item->id ) );
							$delete_url = wp_nonce_url(
								admin_url( 'admin-post.php?action=nvb_delete_eligibility&id=' . absint( $item->id ) ),
								'nvb_delete_eligibility'
							);
							?>
							<a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Edit', 'nvb' ); ?></a> |
							<a href="<?php echo esc_url( $delete_url ); ?>"
							   onclick="return confirm('<?php echo esc_js( __( 'Are you sure you want to delete this item?', 'nvb' ) ); ?>');">
								<?php esc_html_e( 'Delete', 'nvb' ); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		<p><?php esc_html_e( 'No eligibility items found.', 'nvb' ); ?></p>
	<?php endif; ?>
</div>
