<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb;
$prefix = $wpdb->prefix;

// Countries dropdown
$countries = $wpdb->get_results(
	"SELECT id, name FROM {$prefix}nvb_countries WHERE is_deleted = 0 ORDER BY name ASC"
);

// edit item
$edit_id    = isset( $_GET['edit'] ) ? intval( $_GET['edit'] ) : 0;
$edit_item  = null;

if ( $edit_id ) {
	$edit_item = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$prefix}nvb_documents WHERE id = %d AND is_deleted = 0",
			$edit_id
		)
	);
}

// list of all documents
$documents = $wpdb->get_results(
	"SELECT d.*, c.name AS country_name
	 FROM {$prefix}nvb_documents d
	 LEFT JOIN {$prefix}nvb_countries c ON d.country_id = c.id
	 WHERE d.is_deleted = 0
	 ORDER BY c.name ASC, d.is_required DESC, d.title ASC"
);

$message = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';
?>

<div class="wrap nvb-admin nvb-documents">
	<h1><?php esc_html_e( 'Documents Checklist', 'nvb' ); ?></h1>

	<?php if ( $message ) : ?>
		<?php if ( 'created' === $message ) : ?>
			<div class="notice notice-success"><p>Document added.</p></div>
		<?php elseif ( 'updated' === $message ) : ?>
			<div class="notice notice-success"><p>Document updated.</p></div>
		<?php elseif ( 'deleted' === $message ) : ?>
			<div class="notice notice-success"><p>Document deleted.</p></div>
		<?php elseif ( 'missing' === $message ) : ?>
			<div class="notice notice-error"><p>Please select a country and enter a title.</p></div>
		<?php endif; ?>
	<?php endif; ?>

	<hr />

	<h2><?php echo $edit_item ? 'Edit Document' : 'Add New Document'; ?></h2>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'nvb_save_document', 'nvb_document_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_save_document">
		<?php if ( $edit_item ) : ?>
			<input type="hidden" name="id" value="<?php echo esc_attr( $edit_item->id ); ?>">
		<?php endif; ?>

		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="nvb_country_id">Country</label></th>
					<td>
						<select name="country_id" id="nvb_country_id">
							<option value="">Select country</option>
							<?php foreach ( $countries as $country ) : ?>
								<option value="<?php echo esc_attr( $country->id ); ?>" <?php selected( $edit_item ? $edit_item->country_id : '', $country->id ); ?>>
									<?php echo esc_html( $country->name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<tr>
					<th><label for="nvb_title">Document Title</label></th>
					<td>
						<input type="text" name="title" id="nvb_title" class="regular-text"
							value="<?php echo esc_attr( $edit_item ? $edit_item->title : '' ); ?>">
					</td>
				</tr>

				<tr>
					<th>Required?</th>
					<td>
						<label>
							<input type="checkbox" name="is_required" value="1" <?php checked( $edit_item ? $edit_item->is_required : 0, 1 ); ?>>
							Required Document
						</label>
					</td>
				</tr>

				<tr>
					<th><label for="nvb_note">Notes</label></th>
					<td>
						<?php
						$note_value = $edit_item ? $edit_item->note : '';
						wp_editor(
							$note_value,
							'nvb_note',
							array(
								'textarea_name' => 'note',
								'media_buttons' => false,
								'textarea_rows' => 4,
							)
						);
						?>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button( $edit_item ? 'Update Document' : 'Add Document' ); ?>
	</form>

	<hr />

	<h2>Existing Documents</h2>

	<?php if ( $documents ) : ?>
		<table class="widefat striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Country</th>
					<th>Title</th>
					<th>Required</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $documents as $doc ) : ?>
					<tr>
						<td><?php echo esc_html( $doc->id ); ?></td>
						<td><?php echo esc_html( $doc->country_name ); ?></td>
						<td><?php echo esc_html( $doc->title ); ?></td>
						<td><?php echo $doc->is_required ? 'Yes' : 'No'; ?></td>
						<td>
							<?php
							$edit_url   = admin_url( 'admin.php?page=nvb_documents&edit=' . absint( $doc->id ) );
							$delete_url = wp_nonce_url(
								admin_url( 'admin-post.php?action=nvb_delete_document&id=' . absint( $doc->id ) ),
								'nvb_delete_document'
							);
							?>
							<a href="<?php echo esc_url( $edit_url ); ?>">Edit</a> |
							<a href="<?php echo esc_url( $delete_url ); ?>" onclick="return confirm('Delete this document?');">Delete</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	<?php else : ?>
		<p>No documents found.</p>
	<?php endif; ?>
</div>
