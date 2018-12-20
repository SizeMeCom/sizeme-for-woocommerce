<?php
/**
 * Variable product add to cart
 *
 * This template is overridden from the template woocommerce/single-product/add-to-cart/variable.php.
 *
 * On occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility.
 *
 * Last checked against WC version 3.4.1
 *
 * @see     WC_SizeMe_for_WooCommerce::locate_template
 * @package SizeMe for WooCommerce
 * @since   1.0.0
 *
 * @var array               $available_variations The available variations.
 * @var array               $attributes           The SizeMe attributes.
 * @var WC_Product_Variable $product              The product.
 */

/**
 * SizeMe for WooCommerce is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * SizeMe for WooCommerce is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with SizeMe for WooCommerce. If not, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

$sizeme = WC_SizeMe_for_WooCommerce::get_instance();

$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ); // WPCS: XSS ok. ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php esc_attr_e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0">
			<tbody>
			<?php
			$size_set = false;
			foreach ( $attributes as $attribute_name => $options ) :
				$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ?
				wc_clean( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) :
				$product->get_variation_default_attribute( $attribute_name ); // Input var okay.

				if ( $sizeme->is_size_attribute( $product, $attribute_name ) && ! $size_set ) {
					$class = 'sizeme-selection-container';
					$size_set = true;
				} else {
					$class = '';
				} ?>
				<tr>
					<td class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label></td>
					<td class="value<?php echo $class !== '' ? ' ' . esc_attr( $class ) : ''; ?>">
						<?php
							wc_dropdown_variation_attribute_options( array(
								'options'   => $options,
								'attribute' => $attribute_name,
								'product'   => $product,
							) );
							echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : '';
						?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		
		<div class="sizeme-container"></div>

		<div class="single_variation_wrap">
			<?php
			/**
			 * Hook woocommerce_before_single_variation.
			 */
			do_action( 'woocommerce_before_single_variation' );

			/**
			 * Hook woocommerce_single_variation.
			 *
			 * Used to output the cart button and placeholder for variation data.
			 *
			 * @since  2.4.0
			 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
			 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
			 */
			do_action( 'woocommerce_single_variation' );

			/**
			 * Hook woocommerce_after_single_variation.
			 */
			do_action( 'woocommerce_after_single_variation' );
			?>
		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
