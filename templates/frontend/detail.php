<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="nvb-modern-detail">
	<!-- Country Hero Section -->
	<div class="country-hero">
		<div class="container">
			<?php if ( ! empty( $country->flag_url ) ) : ?>
				<img src="<?php echo esc_url( $country->flag_url ); ?>" alt="<?php echo esc_attr( $country->name ); ?> Flag" class="country-flag">
			<?php else : ?>
				<img src="<?php echo esc_url( NVB_PLUGIN_URL . 'assets/img/placeholder.png' ); ?>" alt="<?php echo esc_attr( $country->name ); ?> Flag" class="country-flag">
			<?php endif; ?>
			<h1><?php echo esc_html( $country->name ); ?></h1>
			<div class="meta">
				<?php if ( ! empty( $country->continent ) ) : ?>
					<span><?php echo esc_html( $country->continent ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $country->currency ) ) : ?>
					<span><?php echo esc_html( $country->currency ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $country->timezone ) ) : ?>
					<span><?php echo esc_html( $country->timezone ); ?></span>
				<?php endif; ?>
			</div>
			<div style="margin-top: 2rem;">
				<button class="btn btn-outline" style="color: white; border-color: white;" id="print-country-detail">
					<svg class="icon" viewBox="0 0 24 24" fill="currentColor">
						<path d="M17 8h1a4 4 0 010 8h-1m2-8h1M3 19h18m-2-8h2m-7-3v6h4v-6H5m0 8h4v6H5v-6zm12 0h4v6h-4v-6z"/>
					</svg>
					<?php esc_html_e( 'Print Guide', 'nvb' ); ?>
				</button>
			</div>
		</div>
	</div>

	<div class="container">
		<!-- Main Content -->
		<div class="detail-content">
			
			<!-- Quick Overview Section -->
			<section class="detail-section">
				<h2>
					<svg class="section-icon" viewBox="0 0 24 24" fill="currentColor">
						<path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
					</svg>
					<?php esc_html_e( 'Quick Overview', 'nvb' ); ?>
				</h2>
				<div style="background: var(--bg-light); padding: 2rem; border-radius: 0.75rem;">
					<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
						<div>
							<h4 style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.5rem;"><?php esc_html_e( 'Visa Programs', 'nvb' ); ?></h4>
							<p style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary);"><?php echo count( $visa_programs ); ?></p>
						</div>
						<div>
							<h4 style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.5rem;"><?php esc_html_e( 'Processing Time', 'nvb' ); ?></h4>
							<p style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary);">15-30 <?php esc_html_e( 'days', 'nvb' ); ?></p>
						</div>
						<div>
							<h4 style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.5rem;"><?php esc_html_e( 'Cost Range', 'nvb' ); ?></h4>
							<p style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary);">$50-500</p>
						</div>
						<div>
							<h4 style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.5rem;"><?php esc_html_e( 'Renewable', 'nvb' ); ?></h4>
							<p style="font-size: 1.25rem; font-weight: 600; color: var(--secondary-color);"><?php esc_html_e( 'Yes', 'nvb' ); ?></p>
						</div>
					</div>
				</div>
			</section>

			<!-- Visa Programs Section -->
			<?php if ( ! empty( $visa_programs ) ) : ?>
				<section class="detail-section">
					<h2>
						<svg class="section-icon" viewBox="0 0 24 24" fill="currentColor">
							<path d="M12 14l9-5-9-5-9 5 9 5z"/>
							<path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
							<path d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
						</svg>
						<?php esc_html_e( 'Available Visa Programs', 'nvb' ); ?>
					</h2>
					<div class="visa-programs">
						<?php foreach ( $visa_programs as $program ) : ?>
							<div class="visa-program-card">
								<h3><?php echo esc_html( $program->title ); ?></h3>
								<?php if ( ! empty( $program->duration ) ) : ?>
									<div class="duration"><?php echo esc_html( $program->duration ); ?></div>
								<?php endif; ?>
								<?php if ( ! empty( $program->description ) ) : ?>
									<p style="margin-bottom: 1rem;"><?php echo esc_html( $program->description ); ?></p>
								<?php endif; ?>
								<?php if ( ! empty( $program->requirements ) ) : ?>
									<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; font-size: 0.875rem; color: var(--text-secondary);">
										<?php
										$requirements = maybe_unserialize( $program->requirements );
										if ( is_array( $requirements ) ) {
											echo '<div><strong>' . esc_html__( 'Income Required:', 'nvb' ) . '</strong> ' . esc_html( $requirements['income'] ?? '' ) . '</div>';
											echo '<div><strong>' . esc_html__( 'Processing:', 'nvb' ) . '</strong> ' . esc_html( $requirements['processing_time'] ?? '' ) . '</div>';
											echo '<div><strong>' . esc_html__( 'Cost:', 'nvb' ) . '</strong> ' . esc_html( $requirements['cost'] ?? '' ) . '</div>';
										}
										?>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>

			<!-- Eligibility Section -->
			<?php if ( ! empty( $eligibility ) ) : ?>
				<section class="detail-section">
					<h2>
						<svg class="section-icon" viewBox="0 0 24 24" fill="currentColor">
							<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
						</svg>
						<?php esc_html_e( 'Eligibility Requirements', 'nvb' ); ?>
					</h2>
					<div style="display: grid; gap: 1rem;">
						<?php foreach ( $eligibility as $req ) : ?>
							<div class="eligibility-card" style="border-left: 4px solid var(--primary-color); background: white; padding: 1.5rem; border-radius: 0.75rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 1rem;">
								<h4 style="color: var(--text-primary); margin-bottom: 0.75rem; font-size: 1.1rem; font-weight: 600;"><?php echo esc_html( $req->question ); ?></h4>
								<?php if ( ! empty( $req->answer ) ) : ?>
									<div style="color: var(--text-secondary); line-height: 1.7; font-size: 0.95rem;">
										<?php echo wp_kses_post( $req->answer ); ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>

			<!-- Document Checklist Section -->
			<?php if ( ! empty( $documents ) ) : ?>
				<section class="detail-section">
					<h2>
						<svg class="section-icon" viewBox="0 0 24 24" fill="currentColor">
							<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8m-6-6l6 6m-6-6l6 6"/>
						</svg>
						<?php esc_html_e( 'Document Checklist', 'nvb' ); ?>
						<span style="font-size: 0.875rem; font-weight: 400; color: var(--text-secondary);">
							• <?php esc_html_e( 'Progress:', 'nvb' ); ?> <span class="checklist-progress">0/<?php echo count( $documents ); ?> (0%)</span>
							<div style="background: var(--border-color); height: 4px; border-radius: 2px; margin-top: 0.5rem;">
								<div class="progress-bar" style="background: var(--secondary-color); height: 100%; border-radius: 2px; width: 0%; transition: width 0.3s ease;"></div>
							</div>
						</span>
					</h2>
					<div class="nvb-checklist">
						<?php foreach ( $documents as $index => $doc ) : ?>
							<div class="checklist-item" data-item-id="<?php echo esc_attr( $doc->id ); ?>">
								<div class="checkbox"></div>
								<div class="checklist-content">
									<h4><?php echo esc_html( $doc->title ); ?></h4>
									<?php if ( $doc->is_required ) : ?>
										<p class="required"><?php esc_html_e( 'Required', 'nvb' ); ?></p>
									<?php else : ?>
										<p class="required"><?php esc_html_e( 'Optional', 'nvb' ); ?></p>
									<?php endif; ?>
									<?php if ( ! empty( $doc->description ) ) : ?>
										<p><?php echo esc_html( $doc->description ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="text-center mt-4">
						<button class="btn btn-outline" onclick="window.NomadVisaFrontend.exportChecklist()">
							<svg class="icon" viewBox="0 0 24 24" fill="currentColor">
								<path d="M12 16V4m0 12l-4-4m4 4l4-4m4 4H8"/>
							</svg>
							<?php esc_html_e( 'Export Checklist', 'nvb' ); ?>
						</button>
					</div>
				</section>
			<?php endif; ?>

			<!-- Application Steps Section -->
			<?php if ( ! empty( $steps ) ) : ?>
				<section class="detail-section">
					<h2>
						<svg class="section-icon" viewBox="0 0 24 24" fill="currentColor">
							<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
						</svg>
						<?php esc_html_e( 'Application Process', 'nvb' ); ?>
					</h2>
					<ul class="nvb-steps">
						<?php foreach ( $steps as $step ) : ?>
							<li class="nvb-step">
								<h4><?php echo esc_html( $step->title ); ?></h4>
								<?php if ( ! empty( $step->description ) ) : ?>
									<p><?php echo esc_html( $step->description ); ?></p>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<!-- Cost of Living Section -->
			<?php if ( ! empty( $col ) ) : ?>
				<section class="detail-section">
					<h2>
						<svg class="section-icon" viewBox="0 0 24 24" fill="currentColor">
							<path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
						</svg>
						<?php esc_html_e( 'Cost of Living', 'nvb' ); ?>
					</h2>
					<table class="nvb-col-table">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Category', 'nvb' ); ?></th>
								<th><?php esc_html_e( 'Monthly Cost', 'nvb' ); ?></th>
								<th><?php esc_html_e( 'Notes', 'nvb' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$cost_data = maybe_unserialize( $col->data );
							if ( is_array( $cost_data ) ) :
								$items = array(
									'accommodation_city' => array( esc_html__( 'Apartment (1BR in city center)', 'nvb' ), esc_html__( 'Utilities included', 'nvb' ) ),
									'accommodation_outside' => array( esc_html__( 'Apartment (1BR outside center)', 'nvb' ), esc_html__( 'Utilities separate', 'nvb' ) ),
									'groceries' => array( esc_html__( 'Grocery Shopping', 'nvb' ), esc_html__( 'Per person, moderate eating', 'nvb' ) ),
									'restaurants' => array( esc_html__( 'Restaurant Meal', 'nvb' ), esc_html__( 'Mid-range restaurant', 'nvb' ) ),
									'transportation' => array( esc_html__( 'Transportation', 'nvb' ), esc_html__( 'Monthly public transport pass', 'nvb' ) ),
									'internet_mobile' => array( esc_html__( 'Internet & Mobile', 'nvb' ), esc_html__( 'Unlimited data + home internet', 'nvb' ) ),
									'coworking' => array( esc_html__( 'Co-working Space', 'nvb' ), esc_html__( 'Monthly membership', 'nvb' ) ),
									'healthcare' => array( esc_html__( 'Healthcare (Private)', 'nvb' ), esc_html__( 'Monthly insurance premium', 'nvb' ) ),
									'entertainment' => array( esc_html__( 'Entertainment & Leisure', 'nvb' ), esc_html__( 'Movies, gyms, activities', 'nvb' ) )
								);

								foreach ( $items as $key => $item_data ) :
									if ( isset( $cost_data[ $key ] ) ) :
										?>
										<tr>
											<td><strong><?php echo esc_html( $item_data[0] ); ?></strong></td>
											<td><?php echo esc_html( $cost_data[ $key ] ); ?></td>
											<td><?php echo esc_html( $item_data[1] ); ?></td>
										</tr>
										<?php
									endif;
								endforeach;
							endif;
							?>
						</tbody>
						<tfoot>
							<tr style="background: var(--bg-light); font-weight: 600;">
								<td><strong><?php esc_html_e( 'Total Monthly Budget', 'nvb' ); ?></strong></td>
								<td><strong><?php echo esc_html( $cost_data['total_budget'] ?? '' ); ?></strong></td>
								<td><?php esc_html_e( 'Including apartment rent', 'nvb' ); ?></td>
							</tr>
						</tfoot>
					</table>
				</section>
			<?php endif; ?>

			<!-- FAQ Section -->
			<?php if ( ! empty( $faqs ) ) : ?>
				<section class="detail-section">
					<h2>
						<svg class="section-icon" viewBox="0 0 24 24" fill="currentColor">
							<path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
						</svg>
						<?php esc_html_e( 'Frequently Asked Questions', 'nvb' ); ?>
					</h2>
					<div style="display: grid; gap: 1rem;">
						<?php foreach ( $faqs as $faq ) : ?>
							<div class="faq-item">
								<div class="faq-question">
									<h4><?php echo esc_html( $faq->question ); ?></h4>
									<svg class="faq-toggle" viewBox="0 0 24 24" fill="currentColor">
										<path d="M19 9l-7 7-7-7"/>
									</svg>
								</div>
								<div class="faq-answer">
									<?php if ( ! empty( $faq->answer ) ) : ?>
										<p><?php echo esc_html( $faq->answer ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>

			<!-- Tax Information Section -->
			<?php if ( ! empty( $tax ) ) : ?>
				<section class="detail-section">
					<h2>
						<svg class="section-icon" viewBox="0 0 24 24" fill="currentColor">
							<path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
						</svg>
						<?php esc_html_e( 'Tax Information', 'nvb' ); ?>
					</h2>
					<div style="background: var(--bg-light); padding: 2rem; border-radius: 0.75rem;">
						<?php if ( ! empty( $tax[0]->info ) ) : ?>
							<p><strong><?php esc_html_e( 'Tax Residency:', 'nvb' ); ?></strong> <?php echo esc_html( maybe_unserialize( $tax[0]->info )['residency'] ?? '' ); ?></p>
							<p><strong><?php esc_html_e( 'Foreign Income:', 'nvb' ); ?></strong> <?php echo esc_html( maybe_unserialize( $tax[0]->info )['foreign_income'] ?? '' ); ?></p>
							<p><strong><?php esc_html_e( 'Tax Treaty:', 'nvb' ); ?></strong> <?php echo esc_html( maybe_unserialize( $tax[0]->info )['tax_treaty'] ?? '' ); ?></p>
							<p><strong><?php esc_html_e( 'VAT Rate:', 'nvb' ); ?></strong> <?php echo esc_html( maybe_unserialize( $tax[0]->info )['vat'] ?? '' ); ?></p>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>

			<!-- Action Buttons -->
			<section class="text-center" style="margin-top: 3rem;">
				<button class="btn btn-primary" style="margin-right: 1rem;" onclick="window.NomadVisaFrontend.copyToClipboard(window.location.href)">
					<svg class="icon" viewBox="0 0 24 24" fill="currentColor">
						<path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
					</svg>
					<?php esc_html_e( 'Share This Guide', 'nvb' ); ?>
				</button>
				<a href="#" class="btn btn-outline">
					<svg class="icon" viewBox="0 0 24 24" fill="currentColor">
						<path d="M12 6v6l4 2"/>
					</svg>
					<?php esc_html_e( 'Download as PDF', 'nvb' ); ?>
				</a>
			</section>
		</div>
	</div>

	<!-- Styles and Scripts -->
	<style>
	<?php include NVB_PLUGIN_DIR . 'assets/css/modern-style.css'; ?>

	/* Additional styles for detail page */
	.nvb-modern-detail {
		font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
		line-height: 1.6;
		color: var(--text-primary);
		background-color: var(--bg-light);
	}

	.mt-4 {
		margin-top: 2rem;
	}
	</style>

	<script>
	<?php include NVB_PLUGIN_DIR . 'assets/js/modern-script.js'; ?>
	</script>
</div>
