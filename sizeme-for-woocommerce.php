<?php
/**
 * SizeMe for WooCommerce
 *
 * @package     SizeMe for WooCommerce
 * @copyright   Copyright (c) SizeMe Ltd (https://www.sizeme.com/)
 * @since       2.0.0
 *
 * @wordpress-plugin
 * Plugin Name: SizeMe for WooCommerce
 * Description: SizeMe is a web store plugin that enables your consumers to input their measurements and get personalised fit recommendations based on actual product data.
 * Version:     2.3.4
 * Author:      SizeMe Ltd
 * Author URI:  https://www.sizeme.com/
 * Text Domain: sizeme
 * License:     GPLv2 or later
 *
 * WC requires at least: 2.5
 * WC tested up to: 8.1.0
 *
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

/**
 * Class WC_SizeMe_for_WooCommerce.
 *
 * Handles registering of CSS and JavaScript, initialization of the plugin.
 * Adds the settings page, checks for dependencies and handles installing, activating and uninstalling the plugin.
 *
 * @since 1.0.0
 */
class WC_SizeMe_for_WooCommerce {

	/**
	 * Plugin version, used for dependency checks.
	 *
	 * @since 1.0.0
	 *
	 * @var string VERSION The plugin version.
	 */
	const VERSION = '2.3.4';

	/**
	 * Minimum WordPress version this plugin works with, used for dependency checks.
	 *
	 * @since 1.0.0
	 *
	 * @var string MIN_WP_VERSION The minimum version.
	 */
	const MIN_WP_VERSION = '3.5';

	/**
	 * Minimum WooCommerce plugin version this plugin works with, used for dependency checks.
	 *
	 * @since 1.0.0
	 *
	 * @var string MIN_WC_VERSION The minimum version.
	 */
	const MIN_WC_VERSION = '2.0.0';

	/**
	 * The working instance of the plugin, singleton.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @var WC_SizeMe_for_WooCommerce $instance The plugin instance.
	 */
	private static $instance = null;

	/**
	 * Full path to the plugin directory.
	 *
	 * @since  1.0.0
	 *
	 * @var string $plugin_dir The directory path to the plugin.
	 */
	protected $plugin_dir = '';

	/**
	 * Plugin URL.
	 *
	 * @since  1.0.0
	 *
	 * @var string $plugin_url The URL to the plugin.
	 */
	protected $plugin_url = '';

	/**
	 * Plugin base name.
	 *
	 * @since  1.0.0
	 *
	 * @var string $plugin_name The name of the plugin.
	 */
	protected $plugin_name = '';

	/**
	 * SizeMe attributes.
	 *
	 * @since  1.0.0
	 *
	 * @var array $attributes The list of SizeMe attributes.
	 */
	protected static $attributes = array();

	/**
	 * Plugin version option key.
	 *
	 * @since 2.2.0
	 *
	 * @var string SERVICE_STATUS_ID The key for the service status.
	 */
	const PLUGIN_VERSION_KEY = 'sizeme_version';

	/**
	 * Service status option key, used when saving settings and retrieving them.
	 *
	 * @since 1.0.0
	 *
	 * @var string SERVICE_STATUS_ID The key for the service status.
	 */
	const SERVICE_STATUS_ID = 'sizeme_service_status';

	/**
	 * UI option, API key, used in conversations with the SizeMe Shop API
	 *
	 * @since 2.0.0
	 *
	 * @var string API_KEY The key for the Key!
	 */
	const API_KEY = 'sizeme_api_key';

	/**
	 * UI option, append content to element, used in settings.
	 *
	 * @since 1.0.0
	 *
	 * @var string APPEND_CONTENT_TO The key for UI option.
	 */
	const APPEND_CONTENT_TO = 'sizeme_append_content_to';

	/**
	 * UI option, invoke element, used in settings.
	 *
	 * @since 2.0.0
	 *
	 * @var string INVOKE_ELEMENT The key for UI option.
	 */
	const INVOKE_ELEMENT = 'sizeme_invoke_element';

	/**
	 * UI option, size selector type, used in settings.
	 *
	 * @since 2.0.0
	 *
	 * @var string SIZE_SELECTION_TYPE The key for UI option.
	 */
	const SIZE_SELECTION_TYPE = 'sizeme_size_selection_type';

	/**
	 * UI option, add to cart element, used in settings.
	 *
	 * @since 1.0.0
	 *
	 * @var string ADD_TO_CART_ELEMENT The key for UI option.
	 */
	const ADD_TO_CART_ELEMENT = 'sizeme_add_to_cart_element';

	/**
	 * UI option, add to cart event, used in settings.
	 *
	 * @since 1.0.0
	 *
	 * @var string ADD_TO_CART_EVENT The key for UI option.
	 */
	const ADD_TO_CART_EVENT = 'sizeme_add_to_cart_event';

	/**
	 * UI option, add toggler
	 *
	 * @since 2.0.0
	 *
	 * @var boolean ADD_TOGGLER The key for UI option.
	 */
	const ADD_TOGGLER = 'sizeme_add_toggler';

	/**
	 * UI option, lang override, used in settings.
	 *
	 * @since 2.0.0
	 *
	 * @var string LANG_OVERRIDE The key for UI option.
	 */
	const LANG_OVERRIDE = 'sizeme_lang_override';

	/**
	 * UI option, custom css, used in settings.
	 *
	 * @since 2.0.0
	 *
	 * @var string CUSTOM_CSS The key for UI option.
	 */
	const CUSTOM_CSS = 'sizeme_custom_css';

	/**
	 * UI option, additional translations, used in settings.
	 *
	 * @since 2.0.0
	 *
	 * @var string ADDITIONAL_TRANSLATIONS The key for UI option.
	 */
	const ADDITIONAL_TRANSLATIONS = 'sizeme_additional_translations';

	/**
	 * UI option, max recommendation distance, used in settings.
	 *
	 * @since 2.1.0
	 *
	 * @var string MAX_RECOMMENDATION_DISTANCE The key for UI option.
	 */
	const MAX_RECOMMENDATION_DISTANCE = 'sizeme_max_recommendation_distance';

	/**
	 * UI option, multiselect, size attributes, used to write correct variations to sizeme_product object.
	 *
	 * @since 2.1.0
	 *
	 * @var string SIZE_ATTRIBUTES_KEY The key to store size attributes in wp_options.
	 */
	const SIZE_ATTRIBUTES_KEY = 'sizeme_size_attributes';

	/**
	 * UI option, reg exp string to identify the male gender from product name and/or SKU.
	 *
	 * @since 2.3.0
	 *
	 * @var string MATCH_GENDER_FROM_NAME_MALE Optional string.
	 */
	const MATCH_GENDER_FROM_NAME_MALE = 'sizeme_match_gender_from_name_male';

	/**
	 * UI option, string for default measurement unit: supports "cm" and "in" currently
	 *
	 * @since 2.3.0
	 *
	 * @var string MEASUREMENT_UNIT string.
	 */
	const MEASUREMENT_UNIT = 'sizeme_measurement_unit';

	/**
	 * UI option, select to disallow user from selecting measurement unit
	 *
	 * @since 2.3.0
	 *
	 * @var boolean MEASUREMENT_UNIT_CHOICE_DISALLOWED.
	 */
	const MEASUREMENT_UNIT_CHOICE_DISALLOWED = 'sizeme_measurement_unit_choice_disallowed';

	/**
	 * UI option, whether to show flat or circular measurements in size guide
	 *
	 * @since 2.3.4
	 *
	 * @var boolean FLAT_MEASUREMENTS.
	 */
	const FLAT_MEASUREMENTS = 'sizeme_flat_measurements';

	/**
	 * Info related to SizeMe API requests
	 *
	 * @since 2.0.0
	 *
	 * @var string API_CONTEXT_ADDRESS Where to send API stuff
	 * @var string API_CONTEXT_ADDRESS_TEST Where to send API stuff if in test mode
	 * @var string API_SEND_ORDER_INFO Address for orders
	 * @var string API_SEND_ADD_TO_CART Address for add to carts
	 * @var string COOKIE_SESSION Session cookie
	 * @var string COOKIE_ACTION SizeMe action jackson cookie

     */
    const API_CONTEXT_ADDRESS   = 'https://sizeme.com';
    const API_CONTEXT_ADDRESS_TEST   = 'https://test.sizeme.com';
    const API_SEND_ORDER_INFO   = '/shop-api/sendOrderComplete';
    const API_SEND_ADD_TO_CART  = '/shop-api/sendAddToCart';
    const COOKIE_SESSION        = 'sm_cart';
    const COOKIE_ACTION         = 'sm_action';

	/**
	 * Get the plugin instance.
	 *
	 * Gets the singleton of the plugin.
	 *
	 * @since  1.0.0
	 *
	 * @return WC_SizeMe_for_WooCommerce The plugin instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new WC_SizeMe_for_WooCommerce();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * Plugin uses Singleton pattern, hence the constructor is private.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @return WC_SizeMe_for_WooCommerce The plugin instance.
	 */
	private function __construct() {
		$this->plugin_dir  = untrailingslashit( plugin_dir_path( __FILE__ ) );
		$this->plugin_url  = plugin_dir_url( __FILE__ );
		$this->plugin_name = plugin_basename( __FILE__ );

		register_activation_hook( $this->plugin_name, array( $this, 'activate' ) );
		// The uninstall hook callback needs to be a static class method or function.
		register_uninstall_hook( $this->plugin_name, array( __CLASS__, 'uninstall' ) );
	}

	/**
	 * Initializes the plugin.
	 *
	 * Register hooks outputting SizeMe block in frontend.
	 * Handles the backend admin page integration.
	 *
	 * @since  1.0.0
	 */
	public function init() {
		if ( is_admin() ) {
			$this->init_admin();
		} else {
			$this->init_frontend();
		}
	}

	/**
	 * Register scripts.
	 *
	 * Registers the necessary JavaScript and stylesheets.
	 *
	 * @since  1.0.0
	 */
	public function register_scripts() {
		global $post;

		// Get the product object, and make sure it is a variable product.
		// If not a variable product, clear the action jackson cookie for perfect tracking.
		if ( is_product() ) {
			$product = wc_get_product( $post );
			if ( $product instanceof WC_Product_Variable ) {
				wp_enqueue_script( 'sizeme_store_cdn-defer', '//cdn.sizeme.com/store/sizeme.js', '', '', false );
			} else {
				$this->clear_sm_cookie( self::COOKIE_ACTION );
			}
		}
	}

	/**
	 * Get the service status.
	 *
	 * Gets the service status from the configuration.
	 * One of 'test', 'on', 'off'
	 *
	 * @since  1.0.0
	 *
	 * @return string The service status.
	 */
	public function get_service_status() {
		return get_option( self::SERVICE_STATUS_ID );
	}

	/**
	 * Get the the client key which is a hash of the super-secret api key
	 *
	 * Gets the hashed key.
	 *
	 * @since  2.0.1
	 *
	 * @return string The hashed key.
	 */
	public function get_client_key() {
		return hash( 'sha256', get_option( self::API_KEY ) );
	}


	/**
	 * Return WooCommerce session cookie
	 *
	 * Gets the value
	 *
	 * @since  2.0.2
	 *
	 * @return string Cookie value
	 */
	public function get_sm_session_cookie() {
		// the WC session cookie isn't (necessarily) in place yet, so we have to use our own
		$val = '';
		if (!isset($_COOKIE[ self::COOKIE_SESSION ])) {
			$val = md5(rand().microtime());
			$_COOKIE[ self::COOKIE_SESSION ] = $val;
			if ( !headers_sent() ) setcookie( self::COOKIE_SESSION , $val, strtotime( '+30 days' ), '/' );
		} else {
			$val = $_COOKIE[ self::COOKIE_SESSION ];
		}
		return $val;
	}

	/**
	 * Return SizeMe action cookie
	 *
	 * Gets the value
	 *
	 * @since  2.0.2
	 *
	 * @return string Cookie value
	 */
	public function get_sm_action_cookie() {
		return ( isset( $_COOKIE[ self::COOKIE_ACTION ] ) ? $_COOKIE[ self::COOKIE_ACTION ] : 'false' );
	}

	/**
	 * Clear SizeMe cookie
	 *
	 * @since  2.2.1
	 *
	 * @return true
	 */
	public function clear_sm_cookie($cookie_name) {
		unset( $_COOKIE[ $cookie_name ] );
		if ( !headers_sent() ) setcookie( $cookie_name , '', time() - 3600 , '/' );
		return;
	}


	/**
	 * Get a boolean state.
	 *
	 * Gets the a boolean state from the configuration.
	 * Either true of false
	 *
	 * @since  2.0.0
	 * @changed  2.3.4
	 *
	 * @return boolean The toggler yes status as a boolean.
	 */
	public function is_toggler_yes( $option ) {
		return ( get_option( $option ) == 'yes' );
	}

	/**
	 * Get a boolean state.
	 *
	 * Gets the a boolean state from the configuration.
	 * Either true of false
	 *
	 * @since  2.3.4
	 * @changed  2.3.4
	 *
	 * @return boolean The toggler no status as a boolean.
	 */
	public function is_toggler_no( $option ) {
		return ( get_option( $option ) == 'no' );
	}

	/**
	 * Get a configured UI option.
	 *
	 * Gets the value of the given option from the configuration.
	 *
	 * @since  1.0.0
	 *
	 * @param string      $option  The UI option to get.
	 * @param mixed|false $default The default if option not found. Defaults to false.
	 *
	 * @return string|mixed The option value.
	 */
	public function get_ui_option( $option, $default = false ) {
		return get_option( $option, $default );
	}

	/**
	 * Check if service is in TEST mode
	 *
	 * Reads value and returns if true or false
	 *
	 * @since  2.0.0
	 *
	 *
	 * @return bool Test status
	 */
	public function is_service_test() {
		return ( $this->get_service_status() == 'test' );
	}

	/**
	 * Returns a list of variation product skus along with the size attribute value.
	 *
	 * @since  2.0.0
	 *
	 * @param WC_Product_Variable $product The product.
	 *
	 * @return array attribute as key and sku as value
	 */
	public function get_variation_sizeme_skus( WC_Product_Variable $product ) {

		if ( is_product() ) {
			// Only for variable products.
			if ( $product instanceof WC_Product_Variable ) {
				return $this->load_skus( $product );
			}
		}

		return array();
	}

	/**
	 * Load the SizeMe for WooCommerce skus.
	 *
	 * @since  2.0.0
	 * @access protected
	 *
	 * @param WC_Product_Variable $product The product.
	 *
	 * @return array The skus.
	 */
	protected function load_skus( WC_Product_Variable $product ) {
		if ( empty( self::$attributes[ $product->get_id() ] ) ) {

			$variations = $product->get_available_variations();
			$parent_sku = $product->get_sku();

			foreach ( $variations as $variation ) {

				$variation_meta = get_post_meta( $variation['variation_id'] );

				if ( is_array( $variation_meta ) && count( $variation_meta ) > 0 ) {
					$size_attribute = $this->get_size_attribute( $product );

					foreach ( $variation_meta as $attribute => $value ) {
						if ( ! is_array( $value ) || ! isset( $value[0] ) ) {
							continue;
						}

						if ( isset( $variation['attributes'][ 'attribute_pa_' . $size_attribute ] ) ) {
							// The attribute code value here is the attribute_pa_size, which is "small","extra-small","large", or whatever the slug is.
							$attribute_code = $variation['attributes'][ 'attribute_pa_' . $size_attribute ];
						} else {
							// the SizeMe global size attribute is not present for this product, try something else
							$first_variation = reset( $variations );
							if ( isset( $first_variation['attributes'] ) ) {
								$attribute_key = key( $first_variation['attributes'] );
								$attribute_code = $variation['attributes'][ $attribute_key ];
							} else {
								continue;
							}
						}
						if ( ! isset( self::$attributes[ $product->get_id() ][ $attribute_code ] ) ) {
							$attribute_value = (string)$variation[ 'sku' ];
							if ( (!$variation[ 'sku' ]) || ( $variation[ 'sku' ] === $parent_sku ) ) $attribute_value = substr($this->get_client_key(), 0, 16).'-'.$variation[ 'variation_id' ];
							self::$attributes[ $product->get_id() ][ $attribute_code ] = $attribute_value;
						}
					}
				}
			}
		}

		return ( isset( self::$attributes[$product->get_id()] ) ? self::$attributes[$product->get_id()] : NULL );
	}


	/**
	 * Get the configured size attribute(s).
	 *
	 * Gets the name(s) of the configured size attributes. Can be 'size', 'shoe_size' etc.
	 *
	 * @since  1.0.0
	 * @access protected
	 *
	 * @param WC_Product_Variable $product The product.
	 * @param bool|true           $one     Whether to get all size attributes, or just one.
	 *
	 * @return array|string If parameter $one is true, returns a string of attribute name, otherwise an array of names.
	 */
	protected function get_size_attribute( WC_Product_Variable $product, $one = true ) {
		$size_attributes    = get_option( self::SIZE_ATTRIBUTES_KEY, array() );
		$product_attributes = $product->get_attributes();
		$attribute_names    = array();
		foreach ( $product_attributes as $attribute_name => $attribute_data ) {
			$attribute = substr( $attribute_name, strlen( 'pa_' ) );
			if ( in_array( $attribute, $size_attributes, true ) ) {
				$attribute_names[] = $attribute;
			}
		}

		return $one ? array_pop( $attribute_names ) : $attribute_names;
	}

	/**
	 * Check if given attribute is a size attribute.
	 *
	 * Checks given attribute against configured size attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param WC_Product_Variable $product   The product.
	 * @param string              $attribute The attribute name to check.
	 *
	 * @return bool True if the attribute is a size attribute, false otherwise.
	 */
	public function is_size_attribute( WC_Product_Variable $product, $attribute ) {
		$size_attributes = $this->get_size_attribute( $product, false );

		return in_array( substr( $attribute, strlen( 'pa_' ) ), $size_attributes, true );
	}

    /**
     * Sends the some data to SizeMe
	 *
	 * @since 2.0.0
	 *
	 * @param string $address 		where to send the stuff
	 * @param string $dataString	the json encoded data to send
     *
     * @return boolean success
     */
    public function send($address, $dataString)
    {
        $apiKey = get_option( self::API_KEY );

		if ( !$apiKey ) return false;	// might as well fail if the key is missing

        $ch = curl_init( $address );

        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $dataString );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_USERAGENT, 'WC-' . self::VERSION );
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($dataString),
            'X-Sizeme-Apikey: ' . $apiKey)
        );

        $result = curl_exec($ch);

        return ($result !== false);
    }

    /**
	 * Hook callback function for add to cart events
	 *
	 * Gathers necessary data and sends the info to SizeMe
	 *
	 * @since 2.0.0
	 *
     * @return boolean success
     */
    public function send_add_to_cart_info($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
    {
		$parent_product = New WC_Product( absint($product_id) );
		$child_product = New WC_Product_Variation( absint($variation_id) );

		if ($variation_id > 0) {
			$SKU = ($child_product->get_sku() ? $child_product->get_sku() : substr($this->get_client_key(), 0, 16).'-'.$variation_id);
		} else {
			$SKU = ($parent_product->get_sku() ? $parent_product->get_sku() : substr($this->get_client_key(), 0, 16).'-'.$product_id);
		}

        $arr = array(
            'SKU' => (string)$SKU,
            'quantity' => (int)$quantity,
            'name' => $parent_product->get_name(),
            'orderIdentifier' => $this->get_sm_session_cookie(),
            'actionIdentifier' => $this->get_sm_action_cookie()
        );

		$address = self::API_CONTEXT_ADDRESS . self::API_SEND_ADD_TO_CART;
		if ( $this->is_service_test() ) $address = self::API_CONTEXT_ADDRESS_TEST . self::API_SEND_ADD_TO_CART;

		return $this->send(
			$address,
			json_encode($arr)
		);

    }

    /**
	 * Hook callback function for order events
	 *
	 * Gathers necessary data and sends the info to SizeMe
	 *
	 * @since 2.0.0
	 *
     * @return boolean success
     */
    public function send_order_info($order_id)
    {
		$order = New WC_Order( $order_id );

		if (!$order) return false;

		// check if this has already been sent to SizeMe
		if( get_post_meta( $order_id, 'delivery_order_id', true ) ) {
			return false;
		}

        $arr = array(
            'orderNumber' => $order_id,
            'orderIdentifier' => $this->get_sm_session_cookie(),
            'orderStatusCode' => (int)200,
            'orderStatusLabel' => $order->get_status(),
            'buyer' => array(
                'emailHash' => md5( strtolower( 'Discontinued_2023-01-30' ) ),
            ),
            'createdAt' => $order->get_date_created()->date('Y-m-d H:i:s'),
            'purchasedItems' => array(),
        );

        foreach ($order->get_items() as $item) {
			$product = $item->get_product();
            $arr['purchasedItems'][] = array(
                'SKU' => ($product->get_sku() ? $product->get_sku() : substr($this->get_client_key(), 0, 16).'-'.$product->get_id()),
                'quantity' => (int)$item->get_quantity(),
                'name' => $item->get_name(),
                'unitPriceInclTax' => round( wc_get_price_including_tax( $product ), 2 ),
                'finalPriceExclTax' => round( $order->get_line_total( $item, false ), 2),
                'priceCurrencyCode' => strtoupper( get_woocommerce_currency() ),
            );
        }

		$address = self::API_CONTEXT_ADDRESS . self::API_SEND_ORDER_INFO;
		if ( $this->is_service_test() ) $address = self::API_CONTEXT_ADDRESS_TEST . self::API_SEND_ORDER_INFO;

		if ( $this->send( $address, json_encode($arr) ) ) {
			update_post_meta( $order_id, 'delivery_order_id', esc_attr( $order_id ) );
			// clear sm_cart cookie
			$this->clear_sm_cookie( self::COOKIE_SESSION );
			return true;
		}

		return false;

    }

	/**
	 * Add the SizeMe Measurement scripts to the product page.
	 *
	 * Renders the template that contains the JavaScript.
	 *
	 * @since  1.0.0
	 */
	public function add_sizeme_scripts() {
		if ( is_product() ) {
			global $product;
			// Make sure we only render for variable products.
			if ( $product instanceof WC_Product_Variable ) {
				$this->render( 'sizeme-product', array( 'product' => $product, 'sizeme' => $this ) );
			}
		}
	}

	/**
	 * Renders a template file.
	 *
	 * The file is expected to be located in the plugin "templates" directory.
	 *
	 * @since  1.0.0
	 * @access protected
	 *
	 * @param string $template The name of the template.
	 * @param array  $data     The data to pass to the template file.
	 */
	protected function render( $template, array $data = array() ) {
		if ( is_array( $data ) ) {
			// Instead of using extract() here (discouraged), make variable variables.
			foreach ( $data as $key => $value ) {
				${$key} = $value;
			}
		}
		$file = $template . '.php';
		if ( file_exists( $this->plugin_dir . '/templates/' . $file ) ) {
			require( $this->plugin_dir . '/templates/' . $file );
		}
	}

	/**
	 * Hook callback function for activating the plugin.
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		// Check dependencies and die.
		$this->check_dependencies();
	}

	/**
	 * Hook callback function for uninstalling the plugin.
	 *
	 * @since 1.0.0
	 */
	public static function uninstall() {
		// Todo: remove product attributes and everything else related to this plugin.
	}

	/**
	 * Getter for the plugin base name.
	 *
	 * Gets the plugin name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Adds the settings page to WooCommerce settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings List of settings.
	 *
	 * @return array The updated list of settings.
	 */
	public function add_setting_page( $settings ) {
		$settings[] = require_once( 'classes/class-wc-settings-sizeme-for-woocommerce.php' );
		return $settings;
	}

	/**
	 * Adds the async or defer attributes to script tags
	 *
	 * @since 2.0.6
	 *
	 * @param string $tag script tag
	 * @param string $handle script handle
	 *
	 * @return string The updated script tag.
	 */
	public function add_asyncdefer_attribute($tag, $handle) {
		// if the unique handle/name of the registered script has 'async' in it
		if (strpos($handle, 'async') !== false) {
			// return the tag with the async attribute
			return str_replace( '<script ', '<script async ', $tag );
		}
		// if the unique handle/name of the registered script has 'defer' in it
		else if (strpos($handle, 'defer') !== false) {
			// return the tag with the defer attribute
			return str_replace( '<script ', '<script defer ', $tag );
		}
		// otherwise skip
		else {
			return $tag;
		}
	}


	/**
	 * Initializes the plugin frontend part.
	 *
	 * Adds all hooks needed by the plugin in the frontend.
	 *
	 * @since 1.0.0
	 */
	protected function init_frontend() {

		add_filter( 'script_loader_tag', array( $this, 'add_asyncdefer_attribute' ), 10, 2 );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'woocommerce_before_single_product', array( $this, 'add_sizeme_scripts' ), 20, 0 );
		add_shortcode( 'sizeme_write_scripts', array( $this, 'add_sizeme_scripts' ) );

		add_action( 'woocommerce_add_to_cart', array( $this, 'send_add_to_cart_info' ), 10, 6 );
		add_action( 'woocommerce_thankyou', array( $this, 'send_order_info' ), 10, 1 );

		add_filter( 'woocommerce_locate_template', array( $this, 'locate_template' ), 10, 3 );
	}

	/**
	 * Initializes the plugin admin part.
	 *
	 * Adds a new integration into the WooCommerce settings structure.
	 *
	 * @since 1.0.0
	 */
	protected function init_admin() {
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_setting_page' ) );
		// ajax based add to carts come this way
		if ( wp_doing_ajax() ) {
			add_action( 'woocommerce_add_to_cart', array( $this, 'send_add_to_cart_info' ), 10, 6 );
		} else {
			// Migrate dumb old option system to new if applicable
			$this->migrate_possible_options();
		}
	}

	/**
	 * Show admin notice.
	 *
	 * Shows a notice if the SizeMe size attribute is not defined and the status of the service is ON.
	 *
	 * @since 1.0.0
	 */
	public function admin_notices() {
		$size_attributes = get_option( self::SIZE_ATTRIBUTES_KEY, array() );
		if ( empty( $size_attributes ) ) {
			$this->render( 'admin-notice' );
		}
	}

	/**
	 * Load class file based on class name.
	 *
	 * The file are expected to be located in the plugin "classes" directory.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class_name The name of the class to load.
	 */
	protected function load_class( $class_name = '' ) {
		$file = 'class-' . strtolower( str_replace( '_', '-', $class_name ) ) . '.php';
		if ( file_exists( $this->plugin_dir . '/classes/' . $file ) ) {
			require_once( $this->plugin_dir . '/classes/' . $file );
		}
	}

	/**
	 * Checks plugin dependencies.
	 *
	 * Mainly that the WordPress and WooCommerce versions are equal to or greater than
	 * the defined minimums.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if dependency check OK, false otherwise.
	 */
	protected function check_dependencies() {
		global $wp_version;

		$title = sprintf( __( 'WooCommerce SizeMe %s not compatible.' ), self::VERSION );
		$error = '';
		$args  = array(
			'back_link' => true,
		);

		if ( version_compare( $wp_version, self::MIN_WP_VERSION, '<' ) ) {
			$error = sprintf(
				__( 'Looks like you\'re running an older version of WordPress, you need to be running at least
					WordPress %1$s to use WooCommerce SizeMe for WooCommerce %2$s.' ),
				self::MIN_WP_VERSION,
				self::VERSION
			);
		}

		if ( ! defined( 'WOOCOMMERCE_VERSION' ) ) {
			$error = sprintf(
				__( 'Looks like you\'re not running any version of WooCommerce, you need to be running at least
					WooCommerce %1$s to use WooCommerce SizeMe for WooCommerce %2$s.' ),
				self::MIN_WC_VERSION,
				self::VERSION
			);
		} else if ( version_compare( WOOCOMMERCE_VERSION, self::MIN_WC_VERSION, '<' ) ) {
			$error = sprintf(
				__( 'Looks like you\'re running an older version of WooCommerce, you need to be running at least
					WooCommerce %1$s to use WooCommerce SizeMe for WooCommerce %2$s.' ),
				self::MIN_WC_VERSION,
				self::VERSION
			);
		}

		if ( ! empty( $error ) ) {
			deactivate_plugins( $this->plugin_name );
			wp_die( $error, $title, $args ); // WPCS: XSS ok.

			return false;
		}

		return true;
	}

	/**
	 * Migrate old options.
	 *
	 * Versions before 2.2.0 used non-prefixed names for options and that is very bad.
	 *
	 * @since 2.2.0
	 *
	 * @return bool True always
	 */
	protected function migrate_possible_options() {
		// Check if the sizeme_version option has been set.  If it's not present, previous (non-prefixed) option keys are likely to been set.
		$current_sizeme_version = get_option('sizeme_version', NULL);
		if ( $current_sizeme_version === NULL ) {
			// Check if there actually are old options present
			if ( get_option('service_status', NULL) !== NULL ) {
				require_once( WP_PLUGIN_DIR . '/woocommerce/includes/admin/settings/class-wc-settings-page.php' );
				require_once( 'classes/class-wc-settings-sizeme-for-woocommerce.php' );
				$setting_page = New WC_Settings_SizeMe_for_WooCommerce;
				$settings = $setting_page->get_settings();
				foreach( $settings as $s ) {
					// check if old style option exists and update if so
					if ( isset( $s['id'] ) ) {
						$current_id = $s['id'];
						if ( strpos( $current_id, 'sizeme_' ) !== false ) {
							$old_id = str_replace( 'sizeme_','',$current_id );
							if ( get_option($old_id, NULL) !== NULL ) {
								update_option( $current_id, get_option( $old_id ) );
								delete_option( $old_id );
							}
						}
					}
				}
			}
			add_option( 'sizeme_version', self::VERSION );
		}

		return true;
	}

	/**
	 * Override the locate_template function.
	 *
	 * Adds support for overriding a WooCommerce template in our plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param string $template      The template to override.
	 * @param string $template_name The template name.
	 * @param string $template_path The template path.
	 *
	 * @return string The full path to the template.
	 */
	public function locate_template( $template, $template_name, $template_path ) {
		global $woocommerce;

		$_template = $template;

		if ( ! $template_path ) {
			$template_path = $woocommerce->template_url;
		}

		$plugin_path = $this->plugin_dir . '/woocommerce/';

		// Look within passed path within the theme - this is priority.
		$template = locate_template( array( $template_path . $template_name, $template_name ) );

		// Modification: Get the template from this plugin, if it exists.
		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		// Use default template.
		if ( ! $template ) {
			$template = $_template;
		}

		// Return what we found.
		return $template;
	}
}

add_action( 'plugins_loaded', array( WC_SizeMe_for_WooCommerce::get_instance(), 'init' ) );
