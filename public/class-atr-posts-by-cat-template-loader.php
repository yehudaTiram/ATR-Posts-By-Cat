<?php
/**
 *  The plugin template loader.
 *	extends Gamajo_Template_Loader ( From https://github.com/GaryJones/Gamajo-Template-Loader/blob/develop/class-gamajo-template-loader.php)
 * Based on https://pippinsplugins.com/template-file-loaders-plugins/
 *
 * @link       http://www.atarimtr.com/
 * @since      1.0.0
 *
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/includes
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
 
/**
 * Template loader for Atr_Posts_By_Cat Plugin.
 *
 * Only need to specify class properties here.
 *
 */
class Atr_Posts_By_Cat_Template_Loader extends Gamajo_Template_Loader {	

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Prefix for filter names.
	 *
	 * @since 1.0.0
	 * @type string
	 */
	protected $filter_prefix = 'atr-posts-by-cat';
 
	/**
	 * Directory name where custom templates for this plugin should be found in the theme.
	 *
	 * @since 1.0.0
	 * @type string
	 */
	protected $theme_template_directory = 'atr-posts-by-cat-templates';
 
	/**
	 * Reference to the root directory path of this plugin.
	 *
	 * @since 1.0.0
	 * @type string
	 */
	protected $plugin_directory = 'atr-posts-by-cat';

	/**
	 * Directory name where templates are found in this plugin.
	 *
	 * Can either be a defined constant, or a relative reference from where the subclass lives.
	 *
	 * e.g. 'templates' or 'includes/templates', etc.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	protected $plugin_template_directory = 'public/partials';
		
	public function __construct( ) {

		$this->plugin_directory = plugin_dir_path( dirname( __FILE__ ) );
		$this->plugin_name = 'atr-posts-by-cat';

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


} 