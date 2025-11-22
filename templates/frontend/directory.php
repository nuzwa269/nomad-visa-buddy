<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="nvb-directory">
	<?php
	// وہ پیج جس پر [nvb_country_detail] لگا ہے – slug: country-detail
	$detail_page = get_page_by_path( 'country-detail' );
	$detail_url  = $detail_page ? get_permalink( $detail_page ) : site_url();
	?>

	<?php if ( ! empty( $countries ) ) : ?>
		<?php foreach ( $countries as $c ) : ?>
			<div class="nvb-country-card">
				<?php if ( ! empty( $c->flag_url ) ) : ?>
					<img
						src="<?php echo esc_url( $c->flag_url ); ?>"
						alt="<?php echo esc_attr( $c->name ); ?>"
					/>
				<?php endif; ?>

				<h3><?php echo esc_html( $c->name ); ?></h3>

				<p>
					<?php echo esc_html( $c->continent ); ?>
					—
					<?php echo esc_html( $c->currency ); ?>
				</p>

				<p>
					<a href="<?php echo esc_url( add_query_arg( array( 'country' => $c->slug ), $detail_url ) ); ?>">
						<?php esc_html_e( 'View details', 'nvb' ); ?>
					</a>
				</p>
			</div>
		<?php endforeach; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No countries.', 'nvb' ); ?></p>
	<?php endif; ?>
</div>
