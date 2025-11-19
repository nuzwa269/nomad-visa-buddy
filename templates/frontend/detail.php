```php
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="nvb-country-detail">
	<h1><?php echo esc_html( $country->name ); ?></h1>
	<?php if ( $country->flag_url ) : ?><img src="<?php echo esc_url( $country->flag_url ); ?>" alt="<?php echo esc_attr( $country->name ); ?>" /><?php endif; ?>
	<div class="nvb-description"><?php echo wp_kses_post( $country->description ); ?></div>

	<h2>Visa Programs</h2>
	<?php if ( $visa_programs ) : foreach ( $visa_programs as $v ) : ?>
		<div class="nvb-visa">
			<h3><?php echo esc_html( $v->title ); ?></h3>
			<p><strong>Duration:</strong> <?php echo esc_html( $v->duration ); ?></p>
			<p><?php echo wp_kses_post( $v->description ); ?></p>
			<?php if ( $v->official_link ) : ?><p><a href="<?php echo esc_url( $v->official_link ); ?>" target="_blank">Official Link</a></p><?php endif; ?>
		</div>
	<?php endforeach; else: ?>
		<p>No visa programs available.</p>
	<?php endif; ?>

	<h2>Eligibility</h2>
	<?php if ( $eligibility ) : foreach ( $eligibility as $e ) : ?>
		<p><strong><?php echo esc_html( $e->question ); ?></strong><br/><?php echo wp_kses_post( $e->answer ); ?></p>
	<?php endforeach; endif; ?>

	<h2>Documents</h2>
	<?php if ( $documents ) : ?>
		<ul>
			<?php foreach ( $documents as $d ) : ?>
				<li><?php echo esc_html( $d->title ); ?> <?php echo $d->is_required ? '<strong>(Required)</strong>' : '(Optional)'; ?><?php if ( $d->note ) : ?><div class="nvb-note"><?php echo wp_kses_post( $d->note ); ?></div><?php endif; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p>No documents listed.</p>
	<?php endif; ?>

	<h2>Application Guide</h2>
	<?php if ( $steps ) : ?>
		<ol class="nvb-steps">
			<?php foreach ( $steps as $s ) : ?>
				<li class="nvb-step">
					<h4><?php echo esc_html( $s->title ); ?></h4>
					<p><?php echo wp_kses_post( $s->description ); ?></p>
					<?php if ( $s->external_link ) : ?><p><a href="<?php echo esc_url( $s->external_link ); ?>" target="_blank">Official page</a></p><?php endif; ?>
					<?php if ( $s->screenshot_url ) : ?><img src="<?php echo esc_url( $s->screenshot_url ); ?>" alt="" style="max-width:100%;height:auto;"/><?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	<?php else: ?>
		<p>No guide available.</p>
	<?php endif; ?>

	<h2>Tax Information</h2>
	<?php if ( $tax ) : foreach ( $tax as $t ) : ?>
		<h4><?php echo esc_html( $t->info_title ); ?></h4>
		<p><?php echo wp_kses_post( $t->description ); ?></p>
	<?php endforeach; else: ?>
		<p>No tax data available.</p>
	<?php endif; ?>

	<h2>Cost of Living</h2>
	<?php if ( $col ) : include NVB_PLUGIN_DIR . 'templates/frontend/cost-of-living.php'; else: ?>
		<p>No cost of living data.</p>
	<?php endif; ?>

	<h2>FAQs</h2>
	<?php if ( $faqs ) : foreach ( $faqs as $f ) : ?>
		<p><strong><?php echo esc_html( $f->question ); ?></strong><br/><?php echo wp_kses_post( $f->answer ); ?></p>
	<?php endforeach; else: ?>
		<p>No FAQs.</p>
	<?php endif; ?>

</div>
```
