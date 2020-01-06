<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://atarimtr.co.il/
 * @since      1.0.0
 *
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/includes
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
class Atr_Posts_By_Cat {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Atr_Posts_By_Cat_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'atr-posts-by-cat';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
		// add_action('widgets_init', array($this, 'TestCategoryWidgetInit'));
		add_action('widgets_init', array($this, 'init_Cat_Select_widget'));
		

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Atr_Posts_By_Cat_Loader. Orchestrates the hooks of the plugin.
	 * - Atr_Posts_By_Cat_i18n. Defines internationalization functionality.
	 * - Atr_Posts_By_Cat_Admin. Defines all hooks for the admin area.
	 * - Atr_Posts_By_Cat_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-atr-posts-by-cat-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-atr-posts-by-cat-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-atr-posts-by-cat-admin.php';

		/**
		 * The class responsible for settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-atr-posts-by-cat-admin-settings.php';		

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-atr-posts-by-cat-public.php';
		
		require_once( ABSPATH . 'wp-admin/includes/class-walker-category-checklist.php' );
		
		/**
		 * The Atr_Posts_By_Cat_walker for admin selection in widget admin area 
		 */
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-atr-posts-by-cat-walker.php';
		
		/**
		 * The Atr_Posts_By_Cat_list_Walker for user categoriesd selection in the public-facing
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-atr-posts-by-cat-list-walker.php';		
		
		/**
		 * The Atr_Posts_By_Cat_list_Walker for user categoriesd selection in the public-facing
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-atr-posts-by-cat-select-list-widget.php';		

		/**
		 * Class that loads plugin template loader. Allows loading template parts with fallback. 
		 * Used here to switch the front end view and loading different js code as necessary.
		 */
		if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {	
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gamajo-template-loader.php'; 
		}
		
				
		/**
		 * Custom class to utilize class-gamajo-template-loader.php
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-atr-posts-by-cat-template-loader.php'; 
		
		$this->loader = new Atr_Posts_By_Cat_Loader();

	}

	function init_Cat_Select_widget() {
    register_widget( 'Atr_Posts_By_Cat_Select_widget' );
	}

	
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Atr_Posts_By_Cat_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Atr_Posts_By_Cat_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Atr_Posts_By_Cat_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		$plugin_settings = new Atr_Posts_By_Cat_Admin_Settings( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action('admin_init', $plugin_settings, 'init');
        $this->loader->add_action('admin_menu', $plugin_settings, 'add_menu_item');
        $plugin_basename = $this->plugin_name . '/' . 'wp-ticker-content-and-products.php';
        $this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_settings, 'add_action_links');	
		

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Atr_Posts_By_Cat_Public( $this->get_plugin_name(), $this->get_version() );

		/**
		 * Next to loads cancelled. CSS loads from theme. JS is empty anyway
		 */
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		add_shortcode( 'cats-list', array( $plugin_public, 'list_categories_sc' ) );
		add_shortcode( 'posts-list', array( $plugin_public, 'load_posts_by_selected_categories_sc' ) );
		add_shortcode('list', array( $plugin_public, 'sc_liste' ));	
			
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Atr_Posts_By_Cat_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
