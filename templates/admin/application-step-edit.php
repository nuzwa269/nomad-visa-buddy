<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** 
 * Expected variables:
 * - $countries : list of countries (id, name)
 * - $step      : null for "add", object for "edit"
 */
?>
<div class="wrap nvb-admin-wrap">
	<h1>
		<?php echo $step ? esc_html__( 'Edit Application Step', 'nvb' ) : esc_html__( 'Add Application Step', 'nvb' ); ?>
	</h1>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="nvb_save_application_step" />
		<?php wp_nonce_field( 'nvb_save_application_step', 'nvb_application_step_nonce' ); ?>

		<?php if ( $step ) : ?>
			<input type="hidden" name="id" value="<?php echo intval( $step->id ); ?>">
		<?php endif; ?>

		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="country_id"><?php esc_html_e( 'Country', 'nvb' ); ?></label>
				</th>
				<td>
					<select name="country_id" id="country_id" required>
						<option value=""><?php esc_html_e( 'Select a country', 'nvb' ); ?></option>
						<?php if ( ! empty( $countries ) ) : ?>
							<?php foreach ( $countries as $c ) : ?>
								<option value="<?php echo esc_attr( $c->id ); ?>"
									<?php
									if ( $step && intval( $step->country_id ) === intval( $c->id ) ) {
										echo ' selected';
									}
									?>
								>
									<?php echo esc_html( $c->name ); ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="step_number"><?php esc_html_e( 'Step number', 'nvb' ); ?></label>
				</th>
				<td>
					<input
						type="number"
						name="step_number"
						id="step_number"
						min="1"
						value="<?php echo $step ? intval( $step->step_number ) : 1; ?>"
					/>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="title"><?php esc_html_e( 'Title', 'nvb' ); ?></label>
				</th>
				<td>
					<input
						type="text"
						name="title"
						id="title"
						class="regular-text"
						value="<?php echo $step ? esc_attr( $step->title ) : ''; ?>"
						required
					/>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="external_link"><?php esc_html_e( 'External link', 'nvb' ); ?></label>
				</th>
				<td>
					<input
						type="url"
						name="external_link"
						id="external_link"
						class="regular-text"
						value="<?php echo $step ? esc_attr( $step->external_link ) : ''; ?>"
					/>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="screenshot_url"><?php esc_html_e( 'Screenshot URL', 'nvb' ); ?></label>
				</th>
				<td>
					<input
						type="url"
						name="screenshot_url"
						id="screenshot_url"
						class="regular-text"
						value="<?php echo $step ? esc_attr( $step->screenshot_url ) : ''; ?>"
					/>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="nvb_step_description"><?php esc_html_e( 'Description', 'nvb' ); ?></label>
				</th>
				<td>
					<?php
					$content  = $step ? $step->description : '';
					$settings = array(
						'textarea_name' => 'description',
						'textarea_rows' => 6,
					);
					wp_editor( $content, 'nvb_step_description', $settings );
					?>
				</td>
			</tr>
		</table>

		<p>
			<button type="submit" class="button button-primary">
				<?php esc_html_e( 'Save Step', 'nvb' ); ?>
			</button>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=nvb_application_steps' ) ); ?>" class="button">
				<?php esc_html_e( 'Cancel', 'nvb' ); ?>
			</a>
		</p>
	</form>
</div>
