<?php

/**
 * The plugin bootstrap file
 *
 * 
 * @link              http://atarimtr.co.il/
 * @since             1.0.0
 * @package           Atr_Posts_By_Cat
 *
 * @wordpress-plugin
 * Plugin Name:       ATR Posts by Cat
 * Plugin URI:        http://atarimtr.co.il
 * Description:       List posts by category. Use 
 * 					  [posts-list posts_per_page="2" terms='4,15,11,21,18,29,25' excerpt=0 more_info=0 pager='next_prev_arr' || 'next_prev_numbered' || 'next_prev' ]
 * 					  [posts-list posts_per_page="2" terms='117' full_content=1  more_info=0]
 *     				  [posts-list posts_per_page="2" terms='117' excerpt=0 more_info=0 pager='next_prev_arr' ]
 * 					  See more in the wiki
 * 					  You can use custom templates to display posts.  The option atr-posts-cat-template="cards" will use the file content-cards-template.php (a bootstrap card layout).  					  			  
 * 					  [posts-list posts_per_page="10" terms="11" pager="next_prev_numbered"  atr-posts-cat-template="cards" more_info=1 show-date=1 ]
 * 					  [posts-list  wrapper-id="ma-kore" make-ticker="yes"]  makes the list a ticker. must define both to work 
 * 					  You can select another post type to show, like products
 * 					  [posts-list post_type="product " posts_per_page="10" terms="11" pager="next_prev_numbered"  atr-posts-cat-template="cards" more_info=1 show-date=1 ]	
 * 						
 * 					  [cats-list] is not for use at the moment.
 * 
 * Version:           1.0.0
 * Author:            Yehuda Tiram
 * Author URI:        http://www.atarimtr.co.il/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       atr-posts-by-cat
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-atr-posts-by-cat-activator.php
 */
function activate_atr_posts_by_cat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-atr-posts-by-cat-activator.php';
	Atr_Posts_By_Cat_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-atr-posts-by-cat-deactivator.php
 */
function deactivate_atr_posts_by_cat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-atr-posts-by-cat-deactivator.php';
	Atr_Posts_By_Cat_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_atr_posts_by_cat' );
register_deactivation_hook( __FILE__, 'deactivate_atr_posts_by_cat' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-atr-posts-by-cat.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_atr_posts_by_cat() {

	$plugin = new Atr_Posts_By_Cat();
	$plugin->run();

}
run_atr_posts_by_cat();
