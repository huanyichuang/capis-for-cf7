<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://huanyichuang.com/
 * @since      1.0.0
 *
 * @package    Capis_For_Cf7
 * @subpackage Capis_For_Cf7/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Capis_For_Cf7
 * @subpackage Capis_For_Cf7/includes
 * @author     Huanyi Chuang <huanyi.chuang@gmail.com>
 */
class Capis_For_Cf7_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'capis-for-cf7',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
