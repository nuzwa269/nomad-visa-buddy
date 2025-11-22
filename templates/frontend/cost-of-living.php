<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<table class="nvb-col-table">
	<thead>
		<tr>
			<th>Category</th>
			<th>Monthly Cost (USD)</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>Rent</td>
			<td><?php echo esc_html( number_format( (float) $col->rent, 2 ) ); ?></td>
		</tr>

		<tr>
			<td>Food</td>
			<td><?php echo esc_html( number_format( (float) $col->food, 2 ) ); ?></td>
		</tr>

		<tr>
			<td>Transport</td>
			<td><?php echo esc_html( number_format( (float) $col->transport, 2 ) ); ?></td>
		</tr>

		<tr>
			<td>Internet</td>
			<td><?php echo esc_html( number_format( (float) $col->internet, 2 ) ); ?></td>
		</tr>

		<tr>
			<td>Healthcare</td>
			<td><?php echo esc_html( number_format( (float) $col->healthcare, 2 ) ); ?></td>
		</tr>

		<tr>
			<td>Lifestyle Score</td>
			<td><?php echo esc_html( intval( $col->lifestyle_score ) ); ?>/100</td>
		</tr>
	</tbody>
</table>

<?php if ( ! empty( $col->notes ) ) : ?>
	<div class="nvb-col-notes">
		<?php echo wp_kses_post( $col->notes ); ?>
	</div>
<?php endif; ?>
