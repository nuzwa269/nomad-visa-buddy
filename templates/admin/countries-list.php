<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap nvb-admin-wrap">
	<h1>
		Countries
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=nvb_countries&action=add' ) ); ?>" class="page-title-action">Add New</a>
	</h1>

	<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
		<input type="hidden" name="page" value="nvb_countries" />
		<p class="search-box">
			<label class="screen-reader-text" for="nvb-search-input">Search Countries:</label>
			<input
				type="search"
				id="nvb-search-input"
				name="s"
				value="<?php echo isset( $_GET['s'] ) ? esc_attr( wp_unslash( $_GET['s'] ) ) : ''; ?>"
			/>
			<input type="submit" class="button" value="Search" />
		</p>
	</form>

	<table class="nvb-list-table">
		<thead>
			<tr>
				<th>ID</th>
				<th>Flag</th>
				<th>Name</th>
				<th>Slug</th>
				<th>Continent</th>
				<th>Currency</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( $countries ) : ?>
				<?php foreach ( $countries as $c ) : ?>
					<tr>
						<td><?php echo esc_html( $c->id ); ?></td>
						<td>
							<?php if ( $c->flag_url ) : ?>
								<img
									src="<?php echo esc_url( $c->flag_url ); ?>"
									class="nvb-flag"
									alt="<?php echo esc_attr( $c->name ); ?>"
								/>
							<?php endif; ?>
						</td>
						<td><?php echo esc_html( $c->name ); ?></td>
						<td><?php echo esc_html( $c->slug ); ?></td>
						<td><?php echo esc_html( $c->continent ); ?></td>
						<td><?php echo esc_html( $c->currency ); ?></td>
						<td>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=nvb_countries&action=edit&id=' . intval( $c->id ) ) ); ?>">
								Edit
							</a>
							|
							<a
								class="nvb-confirm-delete"
								href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=nvb_delete_country&id=' . intval( $c->id ) ), 'nvb_delete_country' ) ); ?>"
							>
								Delete
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr><td colspan="7">No countries found.</td></tr>
			<?php endif; ?>
		</tbody>
	</table>

	<?php // Simple pagination (not fully implemented). ?>
</div>

<?php
// Add/Edit form handling.
$action = isset( $_GET['action'] ) ? sanitize_key( wp_unslash( $_GET['action'] ) ) : '';

if ( 'add' === $action || 'edit' === $action ) {
	$edit = null;

	if ( 'edit' === $action && ! empty( $_GET['id'] ) ) {
		global $wpdb;

		$prefix = $wpdb->prefix;
		$id     = absint( $_GET['id'] );

		$edit = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$prefix}nvb_countries WHERE id = %d",
				$id
			)
		);
	}
	?>
	<div class="wrap nvb-admin-wrap">
		<h2><?php echo 'add' === $action ? 'Add Country' : 'Edit Country'; ?></h2>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="nvb-form">
			<input type="hidden" name="action" value="nvb_save_country" />
			<?php wp_nonce_field( 'nvb_save_country', 'nvb_country_nonce' ); ?>

			<?php if ( $edit ) : ?>
				<input type="hidden" name="id" value="<?php echo intval( $edit->id ); ?>" />
			<?php endif; ?>

			<table class="form-table">
				<tr>
					<th scope="row"><label for="name">Name</label></th>
					<td>
						<input
							type="text"
							name="name"
							id="name"
							value="<?php echo $edit ? esc_attr( $edit->name ) : ''; ?>"
							required
						/>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="slug">Slug</label></th>
					<td>
						<input
							type="text"
							name="slug"
							id="slug"
							value="<?php echo $edit ? esc_attr( $edit->slug ) : ''; ?>"
							required
						/>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="continent">Continent</label></th>
					<td>
						<input
							type="text"
							name="continent"
							id="continent"
							value="<?php echo $edit ? esc_attr( $edit->continent ) : ''; ?>"
						/>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="currency">Currency</label></th>
					<td>
						<input
							type="text"
							name="currency"
							id="currency"
							value="<?php echo $edit ? esc_attr( $edit->currency ) : ''; ?>"
						/>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="flag_url">Flag URL</label></th>
					<td>
						<input
							type="text"
							name="flag_url"
							id="flag_url"
							value="<?php echo $edit ? esc_attr( $edit->flag_url ) : ''; ?>"
						/>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="description">Description</label></th>
					<td>
						<textarea name="description" id="description" rows="6"><?php echo $edit ? esc_textarea( $edit->description ) : ''; ?></textarea>
					</td>
				</tr>
			</table>

			<p>
				<button class="button button-primary" type="submit">Save</button>
			</p>
		</form>
	</div>
	<?php
}
?>
