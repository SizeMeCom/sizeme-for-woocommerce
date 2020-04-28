<?php
/**
 * SizeMe for WooCommerce settings.
 *
 * Adds a SizeMe tab in the WooCommerce settings page.
 *
 * @package SizeMe for WooCommerce
 * @since   1.0.0
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

/**
 * Class WC_Settings_SizeMe_for_WooCommerce.
 *
 * Adds a SizeMe tab in the WooCommerce settings page.
 *
 * @since 1.0.0
 */
class WC_Settings_SizeMe_for_WooCommerce extends WC_Settings_Page {

	/**
	 * Service is on.
	 *
	 * @since 1.0.0
	 *
	 * @var string SERVICE_STATUS_ON
	 */
	const SERVICE_STATUS_ON = 'on';

	/**
	 * Service is off.
	 *
	 * @since 1.0.0
	 *
	 * @var string SERVICE_STATUS_OFF
	 */
	const SERVICE_STATUS_OFF = 'off';

	/**
	 * Service is in test mode.
	 *
	 * @since 1.0.0
	 *
	 * @var string SERVICE_STATUS_TEST
	 */
	const SERVICE_STATUS_TEST = 'test';

	/**
	 * Toggler is a no-no.
	 *
	 * @since 2.0.0
	 *
	 * @var string ADD_TOGGLER_NO
	 */
	const ADD_TOGGLER_NO = 'no';

	/**
	 * Toggler is good.
	 *
	 * @since 2.0.0
	 *
	 * @var string ADD_TOGGLER_YES
	 */
	const ADD_TOGGLER_YES = 'yes';


	/**
	 * Class constructor.
	 *
	 * Initializes the settings.
	 *
	 * @since  1.0.0
	 *
	 * @return WC_Settings_SizeMe_Measurements
	 */
	public function __construct() {
		$this->id    = 'sizeme-for-woocommerce';
		$this->label = __( 'SizeMe', 'sizeme-for-woocommerce' );

		parent::__construct();
	}

	/**
	 * Get sections.
	 *
	 * Returns the sections for the settings page.
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			'' => __( 'Settings', 'sizeme-for-woocommerce' ),
		);

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	/**
	 * Get settings array.
	 *
	 * Returns the settings form.
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = array(
			array(
				'title' => __( 'General settings', 'sizeme-for-woocommerce' ),
				'type'  => 'title',
				'id'    => 'general_settings',
			),
			array(
				'title'   => __( 'Service status', 'sizeme-for-woocommerce' ),
				'type'    => 'select',
				'options' => array(
					''                        => __( 'Select service status', 'sizeme-for-woocommerce' ),
					self::SERVICE_STATUS_TEST => 'Test',
					self::SERVICE_STATUS_ON   => 'On',
					self::SERVICE_STATUS_OFF  => 'Off',
				),
				'default' => get_option( WC_SizeMe_for_WooCommerce::SERVICE_STATUS_ID, self::SERVICE_STATUS_OFF ),
				'id'      => WC_SizeMe_for_WooCommerce::SERVICE_STATUS_ID,
			),
			array(
				'title'   => __( 'API key', 'sizeme-for-woocommerce' ),
				'type'    => 'text',
				'default' => get_option( WC_SizeMe_for_WooCommerce::API_KEY, '' ),
				'id'      => WC_SizeMe_for_WooCommerce::API_KEY,
			),
			array(
				'type' => 'sectionend',
				'id'   => 'general_settings',
			),
			array(
				'title' => __( 'Attribute settings', 'sizeme-for-woocommerce' ),
				'type'  => 'title',
				'id'    => 'attribute_settings',
			),
			array(
				'title'   => __( 'Product Size Attributes', 'sizeme-for-woocommerce' ),
				'desc'    => __( 'Select the attributes for sizes', 'sizeme-for-woocommerce' ),
				'type'    => 'multiselect',
				'options' => self::load_size_attribute_options(),
				'css'     => 'width: 150px; height: 150px;',
				'id'      => 'size_attributes',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'attribute_settings',
			),
			array(
				'title' => __( 'UI options', 'sizeme-for-woocommerce' ),
				'type'  => 'title',
				'id'    => 'ui_options',
			),
			array(
				'title'   => __( 'Append content to element', 'sizeme-for-woocommerce' ),
				'type'    => 'text',
				'default' => get_option( WC_SizeMe_for_WooCommerce::APPEND_CONTENT_TO, '' ),
				'id'      => WC_SizeMe_for_WooCommerce::APPEND_CONTENT_TO,
			),
			array(
				'title'   => __( 'Invoke element', 'sizeme-for-woocommerce' ),
				'type'    => 'text',
				'default' => get_option( WC_SizeMe_for_WooCommerce::INVOKE_ELEMENT, '' ),
				'id'      => WC_SizeMe_for_WooCommerce::INVOKE_ELEMENT,
			),
			array(
				'title'   => __( 'Size selection type', 'sizeme-for-woocommerce' ),
				'type'    => 'text',
				'default' => get_option( WC_SizeMe_for_WooCommerce::SIZE_SELECTION_TYPE, '' ),
				'id'      => WC_SizeMe_for_WooCommerce::SIZE_SELECTION_TYPE,
			),
			array(
				'title'   => __( 'Add to cart element', 'sizeme-for-woocommerce' ),
				'type'    => 'text',
				'default' => get_option( WC_SizeMe_for_WooCommerce::ADD_TO_CART_ELEMENT, '' ),
				'id'      => WC_SizeMe_for_WooCommerce::ADD_TO_CART_ELEMENT,
			),
			array(
				'title'   => __( 'Add to cart event', 'sizeme-for-woocommerce' ),
				'type'    => 'text',
				'default' => get_option( WC_SizeMe_for_WooCommerce::ADD_TO_CART_EVENT, '' ),
				'id'      => WC_SizeMe_for_WooCommerce::ADD_TO_CART_EVENT,
			),
			array(
				'title'   => __( 'Add toggler', 'sizeme-for-woocommerce' ),
				'type'    => 'select',
				'options' => array(
					self::ADD_TOGGLER_NO => 'No',
					self::ADD_TOGGLER_YES   => 'Yes',
				),
				'id'      => WC_SizeMe_for_WooCommerce::ADD_TOGGLER,
			),
			array(
				'title'   => __( 'Language code override', 'sizeme-for-woocommerce' ),
				'type'    => 'text',
				'default' => get_option( WC_SizeMe_for_WooCommerce::LANG_OVERRIDE, '' ),
				'id'      => WC_SizeMe_for_WooCommerce::LANG_OVERRIDE,
			),
			array(
				'title'   => __( 'Maximum recommendation distance', 'sizeme-for-woocommerce' ),
				'type'    => 'text',
				'default' => get_option( WC_SizeMe_for_WooCommerce::MAX_RECOMMENDATION_DISTANCE, '' ),
				'id'      => WC_SizeMe_for_WooCommerce::MAX_RECOMMENDATION_DISTANCE,
			),
			array(
				'title'   => __( 'Custom styles', 'sizeme-for-woocommerce' ),
				'type'    => 'textarea',
				'default' => get_option( WC_SizeMe_for_WooCommerce::CUSTOM_CSS, '' ),
				'id'      => WC_SizeMe_for_WooCommerce::CUSTOM_CSS,
			),
			array(
				'title'   => __( 'Additional translations', 'sizeme-for-woocommerce' ),
				'type'    => 'textarea',
				'default' => get_option( WC_SizeMe_for_WooCommerce::ADDITIONAL_TRANSLATIONS, '' ),
				'id'      => WC_SizeMe_for_WooCommerce::ADDITIONAL_TRANSLATIONS,
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ui_options',
			),

		);

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
	}

	/**
	 * Output the settings.
	 *
	 * Outputs the settings form.
	 *
	 * @since  1.0.0
	 */
	public function output() {
		$settings = $this->get_settings();
		WC_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings.
	 *
	 * Saves the settings form in the wp_options table.
	 *
	 * @since  1.0.0
	 */
	public function save() {
		$settings = $this->get_settings();
		WC_Admin_Settings::save_fields( $settings );
	}

	/**
	 * Load the size attribute options.
	 *
	 * Return a list of attribute_name => attribute_label.
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function load_size_attribute_options() {
		$taxonomies = wc_get_attribute_taxonomies();
		$result     = array();
		foreach ( $taxonomies as $taxonomy ) {
			$result[ $taxonomy->attribute_name ] = $taxonomy->attribute_label;
		}

		return $result;
	}
}

return new WC_Settings_SizeMe_for_WooCommerce();
