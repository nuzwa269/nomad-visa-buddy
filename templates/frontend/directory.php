<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="nvb-directory">
        <?php
        // If no detail page is resolved, show a gentle notice so links don’t silently break.
        if ( empty( $detail_url ) ) {
                echo '<div class="nvb-notice">' . esc_html__( 'Set the detail_page attribute on the shortcode so country links know where to go.', 'nvb' ) . '</div>';
                // Fall back to home URL to keep anchors valid, but encourage configuration above.
                $detail_url = home_url( '/' );
        }
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
