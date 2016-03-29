<?php
/**
 * SizeMe Measurements data panels
 *
 * Adds the content to the product administration SizeMe tab panel.
 *
 * @package SizeMe Measurements
 * @since   1.0.0
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
<div id="sizeme_product_data" class="panel woocommerce_options_panel">
	<h2><?php echo esc_html__( 'These attributes are general for the whole product.', 'sizeme' ); ?></h2>
	<div class="options_group hide_if_grouped">
		<?php
		woocommerce_wp_text_input( array(
			'id'          => '_smi_item_type',
			'label'       => '<abbr title="' . __( 'SizeMe Item Type', 'sizeme' ) . '">' . __( 'SizeMe Item Type',
			'sizeme' ) . '</abbr>',
			'desc_tip'    => 'true',
			'description' => __( 'Item type', 'sizeme' ),
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_smi_item_layer',
			'label'       => '<abbr title="' . __( 'SizeMe Item Layer',
			'sizeme' ) . '">' . __( 'SizeMe Item Layer', 'sizeme' ) . '</abbr>',
			'desc_tip'    => 'true',
			'description' => __( 'Item layer', 'sizeme' ),
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_smi_item_thickness',
			'label'       => '<abbr title="' . __( 'SizeMe Item Thickness',
			'sizeme' ) . '">' . __( 'SizeMe Item Thickness', 'sizeme' ) . '</abbr>',
			'desc_tip'    => 'true',
			'description' => __( 'Item thickness', 'sizeme' ),
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_smi_item_stretch',
			'label'       => '<abbr title="' . __( 'SizeMe Item Stretch',
			'sizeme' ) . '">' . __( 'SizeMe Item Stretch', 'sizeme' ) . '</abbr>',
			'desc_tip'    => 'true',
			'description' => __( 'Item stretch', 'sizeme' ),
		) );
		?>

	</div>

</div>
