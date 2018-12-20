<?php
/**
 * SizeMe Measurements
 *
 * @package     SizeMe Measurements
 * @copyright   Copyright (c) 2018 SizeMe Ltd (https://www.sizeme.com/)
 * @since       2.0.0
 *
 * @wordpress-plugin
 * Plugin Name: SizeMe Measurements
 * Description: SizeMe is a web store plugin that enables your consumers to input their measurements and get personalised fit recommendations based on actual product data.
 * Version:     2.0.0
 * Author:      SizeMe Ltd
 * Author URI:  https://www.sizeme.com/
 * Text Domain: sizeme
 * License:     GPLv2 or later
 *
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

/**
 * Class WC_SizeMe_Measurements.
 *
 * Handles registering of CSS and JavaScript, initialization of the plugin.
 * Adds the settings page, checks for dependencies and handles installing, activating and uninstalling the plugin.
 *
 * @since 1.0.0
 */
class WC_SizeMe_Measurements {

	/**
	 * Plugin version, used for dependency checks.
	 *
	 * @since 1.0.0
	 *
	 * @var string VERSION The plugin version.
	 */
	const VERSION = '2.0.0';

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
	 * @var WC_SizeMe_Measurements $instance The plugin instance.
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
	 * Service status option key, used when saving settings and retrieving them.
	 *
	 * @since 1.0.0
	 *
	 * @var string SERVICE_STATUS_ID The key for the service status.
	 */
	const SERVICE_STATUS_ID = 'service_status';

	/**
	 * UI option, API key, used in conversations with the SizeMe Shop API
	 *
	 * @since 2.0.0
	 *
	 * @var string API_KEY The key for the Key!
	 */
	const API_KEY = 'api_key';

	/**
	 * UI option, append content to element, used in settings.
	 *
	 * @since 1.0.0
	 *
	 * @var string APPEND_CONTENT_TO The key for UI option.
	 */
	const APPEND_CONTENT_TO = 'append_content_to';

	/**
	 * UI option, invoke element, used in settings.
	 *
	 * @since 2.0.0
	 *
	 * @var string INVOKE_ELEMENT The key for UI option.
	 */
	const INVOKE_ELEMENT = 'invoke_element';

	/**
	 * UI option, size selector type, used in settings.
	 *
	 * @since 2.0.0
	 *
	 * @var string SIZE_SELECTION_TYPE The key for UI option.
	 */
	const SIZE_SELECTION_TYPE = 'size_selection_type';

	/**
	 * UI option, add to cart element, used in settings.
	 *
	 * @since 1.0.0
	 *
	 * @var string ADD_TO_CART_ELEMENT The key for UI option.
	 */
	const ADD_TO_CART_ELEMENT = 'add_to_cart_element';

	/**
	 * UI option, add to cart event, used in settings.
	 *
	 * @since 1.0.0
	 *
	 * @var string ADD_TO_CART_EVENT The key for UI option.
	 */
	const ADD_TO_CART_EVENT = 'add_to_cart_event';

	/**
	 * UI option, add toggler
	 *
	 * @since 2.0.0
	 *
	 * @var boolean ADD_TOGGLER The key for UI option.
	 */
	const ADD_TOGGLER = 'add_toggler';

	/**
	 * UI option, lang override, used in settings.
	 *
	 * @since 2.0.0
	 *
	 * @var string LANG_OVERRIDE The key for UI option.
	 */
	const LANG_OVERRIDE = 'lang_override';

	/**
	 * UI option, custom css, used in settings.
	 *
	 * @since 2.0.0
	 *
	 * @var string CUSTOM_CSS The key for UI option.
	 */
	const CUSTOM_CSS = 'custom_css';

	/**
	 * UI option, additional translations, used in settings.
	 *
	 * @since 2.0.0
	 *
	 * @var string ADDITIONAL_TRANSLATIONS The key for UI option.
	 */
	const ADDITIONAL_TRANSLATIONS = 'additional_translations';

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
    const COOKIE_SESSION        = 'wcsid';       // WC specific
    const COOKIE_ACTION         = 'sm_action';

	/**
	 * Get the plugin instance.
	 *
	 * Gets the singleton of the plugin.
	 *
	 * @since  1.0.0
	 *
	 * @return WC_SizeMe_Measurements The plugin instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new WC_SizeMe_Measurements();
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
	 * @return WC_SizeMe_Measurements The plugin instance.
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
		$product = wc_get_product( $post );
		if ( $product instanceof WC_Product_Variable ) {
			wp_enqueue_style( 'sizeme_css', '//sizeme.com/3.0/sizeme-styles.css' );
			wp_enqueue_script( 'sizeme_js_manifest', '//sizeme.com/3.0/sizeme-manifest.js', '', '', true );
			wp_enqueue_script( 'sizeme_js_vendor', '//sizeme.com/3.0/sizeme-vendor.js', '', '', true );
			wp_enqueue_script( 'sizeme_js', '//sizeme.com/3.0/sizeme.js', '', '', true );
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
	 * Get the toggler boolean state.
	 *
	 * Gets the toggler boolean state from the configuration.
	 * Either 'no' or 'yes'
	 *
	 * @since  2.0.0
	 *
	 * @return string The toggler status as a string.
	 */
	public function is_toggler_yes() {
		return ( get_option( self::ADD_TOGGLER ) == 'yes' );
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
	 * Load the SizeMe Measurements skus.
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
							if ( ! isset( self::$attributes[ $product->get_id() ][ $attribute_code ] ) ) {
								self::$attributes[ $product->get_id() ][ $attribute_code ] = (string)$variation[ 'sku' ];
							}
						}

					}
				}
			}
		}

		return self::$attributes[ $product->get_id() ];
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
		$size_attributes    = get_option( 'size_attributes', array() );
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

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($dataString),
            'X-Sizeme-Apikey: ' . $apiKey)
        );

        $result = curl_exec($ch);

		if ( $this->is_service_test() ) error_log( sprintf( 'API message sent to %s, response %s', $address, print_r($result) ) );

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
		$parent_product = New WC_Product( $product_id );
		$child_product = New WC_Product_Variation( $variation_id );

        $arr = array(
            'SKU' => $child_product->get_sku(),
            'quantity' => (int)$quantity,
            'name' => $parent_product->get_name(),
            'orderIdentifier' => $_COOKIE[ self::COOKIE_SESSION ],
            'actionIdentifier' => $_COOKIE[ self::COOKIE_ACTION ]
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
            'orderIdentifier' => $_COOKIE[ self::COOKIE_SESSION ],
            'orderStatusCode' => (int)200,
            'orderStatusLabel' => $order->get_status(),
            'buyer' => array(
                'emailHash' => md5( strtolower( $order->get_billing_email() ) ),
            ),
            'createdAt' => $order->get_date_created()->date('Y-m-d H:i:s'),
            'purchasedItems' => array(),
        );

        foreach ($order->get_items() as $item) {
			$product = $item->get_product();
            $arr['purchasedItems'][] = array(
                'SKU' => $product->get_sku(),
                'quantity' => (int)$item->get_quantity(),
                'name' => $item->get_name(),
                'unitPriceInclTax' => round( wc_get_price_including_tax( $product ), 2 ),
                'finalPriceExclTax' => round( $order->get_line_total( $item, false ), 2),
                'priceCurrencyCode' => strtoupper( get_woocommerce_currency() ),
            );
        }

		if ( $this->is_service_test() ) $address = self::API_CONTEXT_ADDRESS_TEST . self::API_SEND_ADD_TO_CART;

		if ( $this->send( $address, json_encode($arr) ) ) {
			update_post_meta( $order_id, 'delivery_order_id', esc_attr( $order_id ) );
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
		$settings[] = require_once( 'classes/class-wc-settings-sizeme-measurements.php' );

		return $settings;
	}

	/**
	 * Initializes the plugin frontend part.
	 *
	 * Adds all hooks needed by the plugin in the frontend.
	 *
	 * @since 1.0.0
	 */
	protected function init_frontend() {

		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'woocommerce_before_single_product', array( $this, 'add_sizeme_scripts' ), 20, 0 );

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
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Show admin notice.
	 *
	 * Shows a notice if the SizeMe size attribute is not defined and the status of the service is ON.
	 *
	 * @since 1.0.0
	 */
	public function admin_notices() {
		$size_attributes = get_option( 'size_attributes', array() );
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
					WordPress %1$s to use WooCommerce SizeMe Measurements %2$s.' ),
				self::MIN_WP_VERSION,
				self::VERSION
			);
		}

		if ( ! defined( 'WOOCOMMERCE_VERSION' ) ) {
			$error = sprintf(
				__( 'Looks like you\'re not running any version of WooCommerce, you need to be running at least
					WooCommerce %1$s to use WooCommerce SizeMe Measurements %2$s.' ),
				self::MIN_WC_VERSION,
				self::VERSION
			);
		} else if ( version_compare( WOOCOMMERCE_VERSION, self::MIN_WC_VERSION, '<' ) ) {
			$error = sprintf(
				__( 'Looks like you\'re running an older version of WooCommerce, you need to be running at least
					WooCommerce %1$s to use WooCommerce SizeMe Measurements %2$s.' ),
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

add_action( 'plugins_loaded', array( WC_SizeMe_Measurements::get_instance(), 'init' ) );
