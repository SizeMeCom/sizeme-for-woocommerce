<?php
/**
 * SizeMe Measurements settings.
 *
 * Adds a SizeMe measurements tab in the WooCommerce settings page.
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

/**
 * Class WC_Settings_SizeMe_Measurements.
 *
 * Adds a SizeMe Measurements tab in the WooCommerce settings page.
 *
 * @since 1.0.0
 */
class WC_Settings_SizeMe_Measurements extends WC_Settings_Page {

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
	 * Class constructor.
	 *
	 * Initializes the settings.
	 *
	 * @since  1.0.0
	 *
	 * @return WC_Settings_SizeMe_Measurements
	 */
	public function __construct() {
		$this->id    = 'sizeme_measurements';
		$this->label = __( 'SizeMe Measurements', 'sizeme' );

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
			'' => __( 'Settings', 'sizeme' ),
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
				'title' => __( 'General settings', 'sizeme' ),
				'type'  => 'title',
				'id'    => 'general_settings',
			),
			array(
				'title'   => __( 'Custom size selection', 'sizeme' ),
				'desc'    => __( 'Use custom size selection ', 'sizeme' ),
				'type'    => 'checkbox',
				'default' => 'no',
				'id'      => WC_SizeMe_Measurements::CUSTOM_SIZE_SELECTION_ID,
			),
			array(
				'title'   => __( 'Service status', 'sizeme' ),
				'type'    => 'select',
				'options' => array(
					''                        => __( 'Select service status', 'sizeme' ),
					self::SERVICE_STATUS_TEST => 'Test',
					self::SERVICE_STATUS_ON   => 'On',
					self::SERVICE_STATUS_OFF  => 'Off',
				),
				'id'      => WC_SizeMe_Measurements::SERVICE_STATUS_ID,
			),
			array(
				'type' => 'sectionend',
				'id'   => 'general_settings',
			),
			array(
				'title' => __( 'Attribute settings', 'sizeme' ),
				'type'  => 'title',
				'id'    => 'attribute_settings',
			),
			array(
				'title'   => __( 'Product Size Attributes', 'sizeme' ),
				'desc'    => __( 'Select the attributes for sizes', 'sizeme' ),
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
				'title' => __( 'UI options', 'sizeme' ),
				'type'  => 'title',
				'id'    => 'ui_options',
			),
			array(
				'title'   => __( 'Append content to element', 'sizeme' ),
				'type'    => 'text',
				'default' => get_option( WC_SizeMe_Measurements::APPEND_CONTENT_TO, '' ),
				'id'      => WC_SizeMe_Measurements::APPEND_CONTENT_TO,
			),
			array(
				'title'   => __( 'Append splash to element', 'sizeme' ),
				'type'    => 'text',
				'default' => get_option( WC_SizeMe_Measurements::APPEND_SPLASH_TO, '' ),
				'id'      => WC_SizeMe_Measurements::APPEND_SPLASH_TO,
			),
			array(
				'title'   => __( 'Add to cart element', 'sizeme' ),
				'type'    => 'text',
				'default' => get_option( WC_SizeMe_Measurements::ADD_TO_CART_ELEMENT, '' ),
				'id'      => WC_SizeMe_Measurements::ADD_TO_CART_ELEMENT,
			),
			array(
				'title'   => __( 'Add to cart event', 'sizeme' ),
				'type'    => 'text',
				'default' => get_option( WC_SizeMe_Measurements::ADD_TO_CART_EVENT, '' ),
				'id'      => WC_SizeMe_Measurements::ADD_TO_CART_EVENT,
			),
			array(
				'title'   => __( 'Size selection container element', 'sizeme' ),
				'type'    => 'text',
				'default' => get_option( WC_SizeMe_Measurements::SIZE_SELECTION_CONTAINER_ELEMENT, '' ),
				'id'      => WC_SizeMe_Measurements::SIZE_SELECTION_CONTAINER_ELEMENT,
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
			// Skip the SizeMe attributes in the list.
			if ( strpos( $taxonomy->attribute_name, 'sm_' ) === 0
			     || strpos( $taxonomy->attribute_name, 'smi_' ) === 0
			) {
				continue;
			}
			$result[ $taxonomy->attribute_name ] = $taxonomy->attribute_label;
		}

		return $result;
	}
}

return new WC_Settings_SizeMe_Measurements();
