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
 * Description: SizeMe is a service where you can store your physical measurements and use them at clothes retailers to
 * get size recommendations and personalized information on how the item will fit you.
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
	 * UI option, append content to element, used in settings.
	 *
	 * @since 1.0.0
	 *
	 * @var string APPEND_CONTENT_TO The key for UI option.
	 */
	const APPEND_CONTENT_TO = 'append_content_to';

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
	 * UI option, size selection container, used in settings.
	 *
	 * @since 1.0.0
	 *
	 * @var string SIZE_SELECTION_CONTAINER_ELEMENT The key for UI option.
	 */
	const SIZE_SELECTION_CONTAINER_ELEMENT = 'size_selection_container_element';

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
			wp_enqueue_script( 'sizeme_js_manifest', '//sizeme.com/3.0/sizeme-manifest.js' );
			wp_enqueue_script( 'sizeme_js_vendor', '//sizeme.com/3.0/sizeme-vendor.js' );
			wp_enqueue_script( 'sizeme_js', '//sizeme.com/3.0/sizeme.js' );
		}
	}

	/**
	 * Check if the given attribute is a SizeMe Measurement attribute.
	 *
	 * Checks against the pre-configured SizeMe attributes, if the given attribute is one of them.
	 *
	 * @since  1.0.0
	 *
	 * @param string $attribute_name The attribute name to check.
	 *
	 * @return bool True if it is a SizeMe attribute, false otherwise.
	 */
	public function is_sizeme_attribute( $attribute_name ) {
		$this->load_class( 'WC_SizeMe_Measurements_Attributes' );

		if ( empty( $attribute_name ) || substr( $attribute_name, 0, strlen( '_sm_' ) ) !== '_sm_' ) {
			return false;
		}

		// Remove the underscore from the attribute name, e.g. _sm_waist => sm_waist.
		$attribute = substr( $attribute_name, 1 );

		return in_array( $attribute, WC_SizeMe_Measurements_Attributes::get_attribute_names(), true );
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
	 * Returns a map of SizeMe attribute names with their corresponding values.
	 *
	 * The map can be directly converted into and JS SizeMe product item in the
	 * view file.
	 *
	 * Format:
	 *
	 *      array(
	 *          "chest"              => 530,
	 *          "waist"              => 510,
	 *          "sleeve"             => 220,
	 *          "sleeve_top_width"   => 208,
	 *          "wrist_width"        => 175,
	 *          "underbust"          => 0,
	 *          "neck_opening_width" => 0,
	 *          "shoulder_width"     => 126,
	 *          "front_height"       => 720,
	 *          "pant_waist"         => 0,
	 *          "hips"               => 510,
	 *          "inseam"             => 0,
	 *          "outseam"            => 0,
	 *          "thigh_width"        => 0,
	 *          "knee_width"         => 0,
	 *          "calf_width"         => 0,
	 *          "pant_sleeve_width"  => 0,
	 *          "shoe_inside_length" => 0,
	 *          "shoe_inside_width"  => 0,
	 *          "hat_width"          => 0,
	 *          "hood_height"        => 0,
	 *      )
	 *
	 * @since  1.0.0
	 *
	 * @param WC_Product_Variable $product The product.
	 *
	 * @return array The attribute map, or empty array if not correct product.
	 */
	public function get_variation_sizeme_attributes( WC_Product_Variable $product ) {

		if ( is_product() ) {
			// Only for variable products.
			if ( $product instanceof WC_Product_Variable ) {
				return $this->load_attributes( $product );
			}
		}

		return array();
	}

	/**
	 * Load the SizeMe Measurements attributes.
	 *
	 * Loads the SizeMe attributes in the attributes array.
	 *
	 * @since  1.0.0
	 * @access protected
	 *
	 * @param WC_Product_Variable $product The product.
	 *
	 * @return array The attributes.
	 */
	protected function load_attributes( WC_Product_Variable $product ) {
		if ( empty( self::$attributes[ $product->id ] ) ) {
			$variations = $product->get_available_variations();
			foreach ( $variations as $variation ) {
				$variation_meta = get_post_meta( $variation['variation_id'] );
				if ( is_array( $variation_meta ) && count( $variation_meta ) > 0 ) {
					$size_attribute = $this->get_size_attribute( $product );
					foreach ( $variation_meta as $attribute => $value ) {
						if ( ! is_array( $value ) || ! isset( $value[0] ) ) {
							continue;
						}

						if ( $this->is_sizeme_attribute( $attribute ) ) {
							// Remove '_sm_' from the attribute, as we only want "chest", "waist" etc.
							$attribute = substr( $attribute, strlen( '_sm_' ), strlen( $attribute ) );
							if ( isset( $variation['attributes'][ 'attribute_pa_' . $size_attribute ] ) ) {
								// The attribute code value here is the attribute_pa_size, which is "small","extra-small","large", or whatever the slug is.
								$attribute_code = $variation['attributes'][ 'attribute_pa_' . $size_attribute ];
								if ( ! isset( self::$attributes[ $product->id ][ $attribute_code ][ $attribute ] ) ) {
									self::$attributes[ $product->id ][ $attribute_code ][ $attribute ] = $value[0];
								}
							}
						}
					}
				}
			}
		}

		return self::$attributes[ $product->id ];
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
	 * Get the smi_item_* attribute for the product.
	 *
	 * Returns the set value for the smi_item_* attributes for the product.
	 *
	 * @param WC_Product_Variable $product The product variable.
	 * @param string              $type    The type to get.
	 *
	 * @return string|null The value for the smi_item_* or null if not found.
	 */
	public function get_smi_item( WC_Product_Variable $product, $type ) {
		$post_meta = get_post_meta( $product->id );

		return isset( $post_meta[ '_' . $type ][0] ) ? $post_meta[ '_' . $type ][0] : null;
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
				$this->load_class( 'WC_SizeMe_Measurements_Attributes' );
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
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'product_data_tabs' ) );

		add_action( 'woocommerce_save_product_variation', array( $this, 'save_product_variation' ), 10, 2 );
		add_action( 'woocommerce_product_after_variable_attributes',
		array( $this, 'product_after_variable_attributes' ), 10, 3 );
		add_action( 'woocommerce_product_data_panels', array( $this, 'product_data_panels' ) );
		add_action( 'woocommerce_process_product_meta_variable', array( $this, 'process_product_meta_variable' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Save product variation.
	 *
	 * Saves the SizeMe attributes for the given variation.
	 *
	 * @param int $variation_id The variation id.
	 * @param int $i            The "loop", e.g. current variation.
	 *
	 * @since 1.0.0
	 *
	 * @return void If nonce verification fails.
	 */
	public function save_product_variation( $variation_id, $i ) {
		if ( empty( $_POST['sizeme_product_nonce'] ) || ( ! wp_verify_nonce( wp_unslash( $_POST['sizeme_product_nonce'] ), // Input var okay.
		'sizeme_save_product_variation' ) )
		) {
			return;
		}
		$this->load_class( 'WC_SizeMe_Measurements_Attributes' );
		foreach ( WC_SizeMe_Measurements_Attributes::get_attribute_names() as $attribute_name ) {
			if ( isset( $_POST[ 'variable_' . $attribute_name ][ $i ] ) ) { // Input var okay.
				$value = sanitize_text_field( wp_unslash( $_POST[ 'variable_' . $attribute_name ][ $i ] ) ); // Input var okay.
				// Allow value to be reset to empty.
				if ( '' === $value || intval( $value ) > 0 ) {
					update_post_meta( $variation_id, '_' . $attribute_name, $value );
				}
			}
		}
	}

	/**
	 * Render SizeMe attributes.
	 *
	 * Renders the SizeMe attributes on the product data section in the variations tab.
	 *
	 * @param int     $loop           The "loop" in which we are, e.g. current variation.
	 * @param array   $variation_data The variation data.
	 * @param WP_Post $variation      The current variation.
	 *
	 * @since 1.0.0
	 */
	public function product_after_variable_attributes( $loop, $variation_data, $variation ) {
		$this->load_class( 'WC_SizeMe_Measurements_Attributes' );

		$variation_meta = get_post_meta( $variation->ID );
		$data           = array();

		// Build the data for the view.
		foreach ( WC_SizeMe_Measurements_Attributes::get_attribute_names() as $attribute_name ) {
			// Skip smi_* attributes.
			if ( substr( $attribute_name, 0, strlen( 'smi_' ) ) === 'smi_' ) {
				continue;
			}

			$value = null;
			if ( isset( $variation_meta[ '_' . $attribute_name ][0] ) ) {
				$value = $variation_meta[ '_' . $attribute_name ][0];
			}

			$label = ucfirst( str_replace( '_', ' ', substr( $attribute_name, strlen( 'sm_' ) ) ) );

			$data[ '_' . $attribute_name ] = array(
				'label' => __( $label, 'sizeme' ),
				'name'  => 'variable_' . $attribute_name . '[' . $loop . ']',
				'value' => $value,
			);
		}

		// Print out the nonce field.
		wp_nonce_field( 'sizeme_save_product_variation', 'sizeme_product_nonce' );

		$this->render( 'sizeme-product-variation', array( 'attribute_data' => $data ) );
	}

	/**
	 * Add product data tab.
	 *
	 * Adds a new SizeMe tab to the product data.
	 *
	 * @param array $tabs The current tabs.
	 *
	 * @since 1.0.0
	 *
	 * @return array The tabs.
	 */
	public function product_data_tabs( $tabs ) {
		$tabs['sizeme'] = array(
			'label'  => __( 'SizeMe', 'sizeme' ),
			'target' => 'sizeme_product_data',
			'class'  => array( 'hide_if_grouped', 'show_if_variable' ),
		);

		return $tabs;
	}

	/**
	 * Process product meta.
	 *
	 * Handles saving of the SizeMe smi_item_* attributes.
	 *
	 * @param int $post_id The post id.
	 *
	 * @since 1.0.0
	 */
	public function process_product_meta_variable( $post_id ) {
		if ( empty( $_POST['sizeme_data_nonce'] ) || ( ! wp_verify_nonce( wp_unslash( $_POST['sizeme_data_nonce'] ), // Input var okay.
		'sizeme_product_data_panels' ) )
		) {
			return;
		}
		$attributes = array(
			'_smi_item_type',
			'_smi_item_layer',
			'_smi_item_thickness',
			'_smi_item_stretch',
		);
		foreach ( $attributes as $attribute ) {
			if ( isset( $_POST[ $attribute ] ) ) { // Input var okay.
				$value = sanitize_text_field( wp_unslash( $_POST[ $attribute ] ) ); // Input var okay.
				if ( '' === $value || intval( $value ) > 0 ) {
					update_post_meta( $post_id, $attribute, $value );
				}
			}
		}
	}

	/**
	 * Render data panels
	 *
	 * Renders the content of the SizeMe panel in the product administration.
	 *
	 * @since 1.0.0
	 */
	public function product_data_panels() {
		wp_nonce_field( 'sizeme_product_data_panels', 'sizeme_data_nonce' );
		$this->render( 'sizeme-data-panels' );
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
