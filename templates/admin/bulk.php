<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap nvb-admin-wrap">
	<h1>Bulk Import / Export</h1>

	<h2>Countries</h2>
	<form
		method="post"
		action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
		enctype="multipart/form-data"
	>
		<?php wp_nonce_field( 'nvb_bulk_action', 'nvb_bulk_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_bulk_import" />

		<p>
			<label for="nvb_csv">
				Upload CSV (slug,name,continent,currency,flag_url,description):
			</label>
			<br />
			<input type="file" name="nvb_csv" id="nvb_csv" accept=".csv" />
		</p>

		<p>
			<button type="submit" class="button button-primary">
				Import Countries
			</button>
		</p>
	</form>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'nvb_bulk_action', 'nvb_bulk_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_bulk_export" />

		<p>
			<button type="submit" class="button">
				Export Countries CSV
			</button>
		</p>
	</form>

	<hr />

	<h2>Visa Programs</h2>
	<form
		method="post"
		action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
		enctype="multipart/form-data"
	>
		<?php wp_nonce_field( 'nvb_bulk_visa_action', 'nvb_bulk_visa_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_bulk_import_visa_programs" />

		<p>
			<label for="nvb_visa_csv">
				Upload Visa Programs CSV
				(<code>country_slug,program_title,duration,income_requirement,official_link,description</code>):
			</label>
			<br />
			<input type="file" name="nvb_visa_csv" id="nvb_visa_csv" accept=".csv" />
		</p>

		<p>
			<button type="submit" class="button button-primary">
				Import Visa Programs
			</button>
		</p>
	</form>

	<hr />

	<h2>Eligibility Q&amp;A</h2>
	<form
		method="post"
		action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
		enctype="multipart/form-data"
	>
		<?php wp_nonce_field( 'nvb_bulk_eligibility_action', 'nvb_bulk_eligibility_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_bulk_import_eligibility" />

		<p>
			<label for="nvb_eligibility_csv">
				Upload Eligibility CSV
				(<code>country_slug,question,answer</code> — ہر ملک کیلئے جتنی لائنیں چاہیں):
			</label>
			<br />
			<input type="file" name="nvb_eligibility_csv" id="nvb_eligibility_csv" accept=".csv" />
		</p>

		<p>
			<button type="submit" class="button button-primary">
				Import Eligibility Items
			</button>
		</p>
	</form>

	<hr />

	<h2>Documents Checklist</h2>
	<form
		method="post"
		action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
		enctype="multipart/form-data"
	>
		<?php wp_nonce_field( 'nvb_bulk_documents_action', 'nvb_bulk_documents_nonce' ); ?>
		<input type="hidden" name="action" value="nvb_bulk_import_documents" />

		<p>
			<label for="nvb_documents_csv">
				Upload Documents CSV
				(<code>country_slug,title,is_required,notes</code> — ہر ملک کیلئے جتنے ڈاکومنٹس چاہیں):
			</label>
			<br />
			<input type="file" name="nvb_documents_csv" id="nvb_documents_csv" accept=".csv" />
		</p>

		<p>
			<button type="submit" class="button button-primary">
				Import Documents
			</button>
		</p>
	</form>
</div>
