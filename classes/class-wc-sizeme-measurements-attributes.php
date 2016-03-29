<?php
/**
 * SizeMe Measurements attributes.
 *
 * Handles SizeMe measurements attributes.
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
 * Class WC_SizeMe_Measurements_Attributes.
 *
 * Adds functionality to add and get SizeMe Measurements attributes.
 *
 * @package SizeMe Measurements
 * @since   1.0.0
 */
class WC_SizeMe_Measurements_Attributes {

	/**
	 * The item type attribute name.
	 *
	 * @since 1.0.0
	 *
	 * @var string ITEM_TYPE
	 */
	const ITEM_TYPE = 'smi_item_type';

	/**
	 * The item layer attribute name.
	 *
	 * @since 1.0.0
	 *
	 * @var string ITEM_LAYER
	 */
	const ITEM_LAYER = 'smi_item_layer';

	/**
	 * The item thickness attribute name.
	 *
	 * @since 1.0.0
	 *
	 * @var string ITEM_THICKNESS
	 */
	const ITEM_THICKNESS = 'smi_item_thickness';

	/**
	 * The item stretch attribute name.
	 *
	 * @var string ITEM_STRETCH
	 */
	const ITEM_STRETCH = 'smi_item_stretch';

	/**
	 * Attributes used for SizeMe Measurements.
	 *
	 * @since 1.0.0
	 *
	 * @var array List of attributes.
	 */
	protected static $attributes = array(
		'smi_item_type',
		'smi_item_layer',
		'smi_item_thickness',
		'smi_item_stretch',
		'sm_chest',
		'sm_waist',
		'sm_sleeve',
		'sm_sleeve_top_width',
		'sm_wrist_width',
		'sm_underbust',
		'sm_neck_opening_width',
		'sm_shoulder_width',
		'sm_front_height',
		'sm_pant_waist',
		'sm_hips',
		'sm_inseam',
		'sm_outseam',
		'sm_thigh_width',
		'sm_knee_width',
		'sm_calf_width',
		'sm_pant_sleeve_width',
		'sm_shoe_inside_length',
		'sm_shoe_inside_width',
		'sm_hat_width',
		'sm_hood_height',
	);

	/**
	 * Get attribute names.
	 *
	 * Returns the SizeMe Measurement attribute names.
	 *
	 * @since 1.0.0
	 *
	 * @return array The attribute names, e.g. sm_chest, sm_waist.
	 */
	public static function get_attribute_names() {
		return self::$attributes;
	}
}
