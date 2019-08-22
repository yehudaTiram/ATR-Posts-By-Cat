<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://atarimtr.co.il/
 * @since      1.0.0
 *
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/includes
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
class Atr_Posts_By_Cat_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'atr-posts-by-cat',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
