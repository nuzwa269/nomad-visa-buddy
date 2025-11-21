<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;
$prefix = $wpdb->prefix;

// Countries for dropdown
$countries = $wpdb->get_results(
	"SELECT id, name FROM {$prefix}nvb_countries WHERE is_deleted = 0 ORDER BY name ASC"
);

// Edit mode?
$edit_id   = isset( $_GET['edit'] ) ? intval( $_GET['edit'] ) : 0;
$edit_item = null;

if ( $edit_id ) {
	$edit_item = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$prefix}nvb_faqs WHERE id = %d AND is_deleted = 0",
			$edit_id
		)
	);
}

// List all FAQs
$faqs = $wpdb->get_results(
	"SELECT f.*, c.name AS country_name
	 FROM {$prefix}nvb_faqs f
	 LEFT JOIN {$prefix}nvb_countries c ON f.country_id = c.id
	 WHERE f.is_deleted = 0
	 ORDER BY c.name ASC, f.sort_order ASC, f.id ASC"
);

$message = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';
?>

<div class="wrap nvb-admin nvb-faqs">
	<h1><?php esc_html_e( 'FAQs', 'nvb' ); ?></h1>

	<?php if ( $message ) : ?>
		<?php if ( 'created' === $message ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'FAQ created.', 'nvb' ); ?></p></div>
		<?php elseif ( 'updated' === $message ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'FAQ updated.', 'nvb' ); ?></p></div>
		<?php elseif ( 'deleted' === $message ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'FAQ deleted.', 'nvb' ); ?></p></div>
		<?php elseif ( 'missing' === $message ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'Please select a country and enter a question.', 'nvb' ); ?></p></div>
		<?php endif; ?>
	<?php endif; ?>

	<hr />

	<h2>
		<?php echo $edit_item ? esc_html__( 'Edit FAQ', 'nvb' ) : esc_html__( 'Add New FAQ', 'nvb' ); ?>
	</h2>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'nvb_save_faq', 'nvb_faq_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_save_faq" />

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
								<option value="<?php echo esc_attr( $country->id ); ?>"
									<?php selected( $edit_item ? $edit_item->country_id : '', $country->id ); ?>>
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

				<tr>
					<th scope="row">
						<label for="nvb_sort_order"><?php esc_html_e( 'Sort Order', 'nvb' ); ?></label>
					</th>
					<td>
						<input type="number" name="sort_order" id="nvb_sort_order" class="small-text"
							value="<?php echo esc_attr( $edit_item ? $edit_item->sort_order : 0 ); ?>" />
						<p class="description">
							<?php esc_html_e( 'Lower numbers appear first.', 'nvb' ); ?>
						</p>
					</td>
				</tr>

			</tbody>
		</table>

		<?php
		submit_button(
			$edit_item
				? __( 'Update FAQ', 'nvb' )
				: __( 'Add FAQ', 'nvb' )
		);
		?>
	</form>

	<hr />

	<h2><?php esc_html_e( 'Existing FAQs', 'nvb' ); ?></h2>

	<?php if ( $faqs ) : ?>
		<table class="widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'ID', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Country', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Question', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Sort Order', 'nvb' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'nvb' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $faqs as $item ) : ?>
					<tr>
						<td><?php echo esc_html( $item->id ); ?></td>
						<td><?php echo esc_html( $item->country_name ); ?></td>
						<td><?php echo esc_html( $item->question ); ?></td>
						<td><?php echo esc_html( $item->sort_order ); ?></td>
						<td>
							<?php
							$edit_url   = admin_url( 'admin.php?page=nvb_faqs&edit=' . absint( $item->id ) );
							$delete_url = wp_nonce_url(
								admin_url( 'admin-post.php?action=nvb_delete_faq&id=' . absint( $item->id ) ),
								'nvb_delete_faq'
							);
							?>
							<a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Edit', 'nvb' ); ?></a> |
							<a href="<?php echo esc_url( $delete_url ); ?>"
							   onclick="return confirm('<?php echo esc_js( __( 'Are you sure you want to delete this FAQ?', 'nvb' ) ); ?>');">
								<?php esc_html_e( 'Delete', 'nvb' ); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		<p><?php esc_html_e( 'No FAQs found.', 'nvb' ); ?></p>
	<?php endif; ?>
</div>
