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
		pluginVersion: "WC-<?php echo WC_VERSION; ?>",
		shopType: "woocommerce",
		uiOptions: {}
	};

	sizeme_options.uiOptions.appendContentTo = "<?php echo esc_js( $sizeme->get_ui_option( WC_SizeMe_Measurements::APPEND_CONTENT_TO, '' ) ); ?>";
	sizeme_options.uiOptions.invokeElement = "<?php echo esc_js( $sizeme->get_ui_option( WC_SizeMe_Measurements::INVOKE_ELEMENT, '' ) ); ?>";
	sizeme_options.uiOptions.sizeSelectorType = "<?php echo esc_js( $sizeme->get_ui_option( WC_SizeMe_Measurements::SIZE_SELECTION_TYPE, '' ) ); ?>";
	sizeme_options.uiOptions.addToCartElement = "<?php echo esc_js( $sizeme->get_ui_option( WC_SizeMe_Measurements::ADD_TO_CART_ELEMENT, '' ) ); ?>";
	sizeme_options.uiOptions.addToCartEvent = "<?php echo esc_js( $sizeme->get_ui_option( WC_SizeMe_Measurements::ADD_TO_CART_EVENT, '' ) ); ?>";
	sizeme_options.uiOptions.lang = "<?php echo esc_js( $sizeme->get_ui_option( WC_SizeMe_Measurements::LANG_OVERRIDE, '' ) ); ?>";
	
	<?php
	// ADDITIONAL TRANSLATIONS (from the UI OPTIONS array)
	$trans = trim( $sizeme->get_ui_option( WC_SizeMe_Measurements::ADDITIONAL_TRANSLATIONS, '' ) );
	if ( !empty($trans) ) echo 'sizeme_options.additionalTranslations = "' . esc_js( $trans ) . '";'.PHP_EOL;
	?>

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

<?php
// write possible custom css (placement questionable)
$css = trim( $sizeme->get_ui_option( WC_SizeMe_Measurements::CUSTOM_CSS, '' ) );
if ( !empty($css) ) echo '<style type="text/css">' . esc_js( $css ) . '</style>'.PHP_EOL;

