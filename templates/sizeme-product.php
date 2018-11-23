<?php
/**
 * SizeMe Measurements JavaScript
 *
 * Adds the necessary JavaScript for creating the product and UI options.
 *
 * @package SizeMe Measurements
 * @since   1.0.0
 *
 * @var WC_SizeMe_Measurements $sizeme  The SizeMe Measurements object.
 * @var WC_Product_Variable    $product The variable product object.
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

<script type="text/javascript">
	//<![CDATA[
	var sizeme_options = {
		service_status: "<?php echo esc_js( $sizeme->get_service_status() ); ?>",
	};

	if (typeof sizeme_UI_options === 'undefined') {
		var sizeme_UI_options = {};
	sizeme_options.uiOptions.lang = "<?php echo esc_js( $sizeme->get_ui_option( WC_SizeMe_Measurements::LANG_OVERRIDE, '' ) ); ?>";
	}

	sizeme_UI_options[ 'appendContentTo' ]  = "<?php echo esc_js( $sizeme->get_ui_option( WC_SizeMe_Measurements::APPEND_CONTENT_TO, '' ) ); ?>";
	sizeme_UI_options[ 'invokeElement' ]  = "<?php echo esc_js( $sizeme->get_ui_option( WC_SizeMe_Measurements::INVOKE_ELEMENT, '' ) ); ?>";
	sizeme_UI_options[ 'sizeSelectorType' ]  = "<?php echo esc_js( $sizeme->get_ui_option( WC_SizeMe_Measurements::SIZE_SELECTION_TYPE, '' ) ); ?>";
	sizeme_UI_options[ 'addToCartElement' ] = "<?php echo esc_js( $sizeme->get_ui_option( WC_SizeMe_Measurements::ADD_TO_CART_ELEMENT, '' ) ); ?>";
	sizeme_UI_options[ 'addToCartEvent' ]   = "<?php echo esc_js( $sizeme->get_ui_option( WC_SizeMe_Measurements::ADD_TO_CART_EVENT, '' ) ); ?>";

	var sizeme_product = {
		name: "<?php echo esc_js( $product->get_formatted_name() ); ?>",
		item: new SizeMe.Item(<?php echo '"' . esc_js( $sizeme->get_smi_item( $product, WC_SizeMe_Measurements_Attributes::ITEM_TYPE ) ) . '", '
			. esc_js( $sizeme->get_smi_item( $product, WC_SizeMe_Measurements_Attributes::ITEM_LAYER ) ) . ', '
			. esc_js( $sizeme->get_smi_item( $product, WC_SizeMe_Measurements_Attributes::ITEM_THICKNESS ) ) . ', '
		. esc_js( $sizeme->get_smi_item( $product, WC_SizeMe_Measurements_Attributes::ITEM_STRETCH ) ); ?>)
		<?php foreach ( $sizeme->get_variation_sizeme_attributes( $product ) as $size_attribute => $attributes ) : ?>
			.addSize("<?php echo esc_js( $size_attribute ); ?>", new SizeMe.Map()
				<?php foreach ( $attributes as $name => $value ) : ?>
				.addItem("<?php echo esc_js( $name ); ?>", <?php echo (int) $value; ?>)
				<?php endforeach; ?>
			)
		<?php endforeach; ?>
	};
	//]]>
</script>
