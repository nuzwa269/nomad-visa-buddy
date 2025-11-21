<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="nvb-checklist" data-country="<?php echo esc_attr( $country->id ); ?>">

	<h2>Document Checklist for <?php echo esc_html( $country->name ); ?></h2>

	<form>
		<?php if ( ! empty( $documents ) ) : ?>
			<?php foreach ( $documents as $idx => $d ) : ?>
				<div class="nvb-checklist-item">
					<label>
						<input
							type="checkbox"
							name="doc_<?php echo esc_attr( $d->id ); ?>"
						/>
						<?php echo esc_html( $d->title ); ?>

						<?php if ( ! empty( $d->is_required ) ) : ?>
							<strong>(Required)</strong>
						<?php endif; ?>
					</label>

					<?php if ( ! empty( $d->note ) ) : ?>
						<div class="nvb-note">
							<?php echo wp_kses_post( $d->note ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<p>No documents found.</p>
		<?php endif; ?>
	</form>

	<p class="nvb-export-buttons">
		<button type="button" class="button nvb-export-pdf">
			Export to PDF
		</button>

		<button
			type="button"
			class="button nvb-export-csv"
			data-country="<?php echo esc_attr( $country->id ); ?>"
		>
			Export to CSV
		</button>
	</p>
</div>
