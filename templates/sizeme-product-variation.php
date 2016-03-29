<?php
/**
 * SizeMe Measurements product variation.
 *
 * Adds the list of SizeMe attributes on the product variation tab.
 *
 * @package SizeMe Measurements
 * @since   1.0.0
 *
 * @var array $attribute_data The data to render.
 */

/**
 * SizeMe Measurements is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * SizeMe Measurements is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with SizeMe Measurements. If not, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<hr/>
<h2><?php echo esc_html__( 'SizeMe Attributes', 'sizeme' ); ?></h2>
<div>
	<?php foreach ( $attribute_data as $attribute_name => $data ) : ?>
		<p class="form-row" style="width: 20%; margin-right: 10px; float: left;">
			<label><?php echo esc_attr( $data['label'] ); ?></label>
			<input name="<?php echo esc_attr( $data['name'] ); ?>" style="width:100%;"
			       value="<?php echo esc_attr( $data['value'] ); ?>"/>
		</p>
	<?php endforeach; ?>
</div>
