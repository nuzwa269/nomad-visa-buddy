```php
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="nvb-directory">
	<?php if ( $countries ) : foreach ( $countries as $c ) : ?>
		<div class="nvb-country-card">
			<?php if ( $c->flag_url ) : ?><img src="<?php echo esc_url( $c->flag_url ); ?>" alt="<?php echo esc_attr( $c->name ); ?>" /><?php endif; ?>
			<h3><?php echo esc_html( $c->name ); ?></h3>
			<p><?php echo esc_html( $c->continent ); ?> — <?php echo esc_html( $c->currency ); ?></p>
			<p><a href="<?php echo esc_url( add_query_arg( array( 'country' => $c->slug ), site_url() ) ); ?>"><?php esc_html_e( 'View details', 'nvb' ); ?></a></p>
		</div>
	<?php endforeach; else: ?>
		<p><?php esc_html_e( 'No countries.', 'nvb' ); ?></p>
	<?php endif; ?>
</div>
```
