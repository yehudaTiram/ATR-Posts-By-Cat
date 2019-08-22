<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://atarimtr.co.il/
 * @since      1.0.0
 *
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/admin
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
class Atr_Posts_By_Cat_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/atr-posts-by-cat-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/atr-posts-by-cat-admin.js', array( 'jquery' ), $this->version, false );
		// Load ajax for frontend
		wp_register_script( 'admin_ajax_front', plugin_dir_url(__FILE__) . 'js/atr-posts-by-cat-admin.js', array( 'jquery' ), $this->version, false );
		$translation_array = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		);
		wp_localize_script( 'admin_ajax_front', 'front', $translation_array );
		wp_enqueue_script( 'admin_ajax_front', false, array(), false, true ); // last param set to true will enqueue script on footer			
		

	}

}
