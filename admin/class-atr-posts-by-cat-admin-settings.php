<?php

/**
 * The admin-facing settings of the plugin.
 *
 * @link       http://atarimtr.com
 * @since      1.0.0
 *
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/admin
 */

class Atr_Posts_By_Cat_Admin_Settings {

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
     * The text domain of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $textdomain    The current version of this plugin.
     */
    private $textdomain;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->textdomain = 'atr-posts-by-cat';

    }
    /*
     * Loads both the general and advanced settings from
     * the database into their respective arrays. Uses
     * array_merge to merge with default values if they're
     * missing.
     */
    /**
     * Initialize settings
     * @return void
     */
    public function init()
    {
        $this->settings = $this->settings_fields();
        $this->options = $this->get_options();
        $this->register_settings();

    }

    /**
     * Build settings fields
     * @return array Fields to be displayed on settings page
     */
    private function settings_fields()
    {


		$settings['atr_posts_by_cat_docs'] = array(
            'title' => __('<span style="color:red;">How to use</span>', $this->textdomain),
            'description' => __('', $this->textdomain),
            'fields' => array(
                array(
                    'id'             => 'how_to_use',
                    'label'            => __( 'How to use', $this->textdomain ),
                    'description'    => __( 'You can select multiple items and they will be stored as an array.', $this->textdomain ),
                    'type'            => 'documentation',
					'default'        => '',
					'placeholder'    => __( 'Placeholder text', $this->textdomain )
				),  
           
                                                      

            ),
		);
		
        $settings = apply_filters('plugin_settings_fields', $settings);

        return $settings;
    }

    /**
     * Options getter
     * @return array Options, either saved or default ones.
     */
    public function get_options()
    {
        $options = get_option($this->plugin_name);

        if (!$options && is_array($this->settings)) {
            $options = array();
            foreach ($this->settings as $section => $data) {
                foreach ($data['fields'] as $field) {
                    $options[$field['id']] = $field['default'];
                }
            }

            add_option($this->plugin_name, $options);
        }

        return $options;
    }

    /**
     * Register plugin settings
     * @return void
     */
    public function register_settings()
    {
        if (is_array($this->settings)) {

            register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate_fields'));

            foreach ($this->settings as $section => $data) {

                // Add section to page
                add_settings_section($section, $data['title'], array($this, 'settings_section'), $this->plugin_name);

                foreach ($data['fields'] as $field) {

                    // Add field to page
                    add_settings_field($field['id'], $field['label'], array($this, 'display_field'), $this->plugin_name, $section, array('field' => $field));
                }
            }
        }
    }

    public function settings_section($section)
    {
        $html = '<p> ' . $this->settings[$section['id']]['description'] . '</p>' . "\n";
        echo $html;
    }

    /**
     * Generate HTML for displaying fields
     * @param  array $args Field data
     * @return void
     */
    public function display_field($args)
    {

        $field = $args['field'];

        $html = '';

        $option_name = $this->plugin_name . "[" . $field['id'] . "]";

        $data = (isset($this->options[$field['id']])) ? $this->options[$field['id']] : '';
        
        switch ($field['type']) {
            case 'text':
            case 'password':
            case 'number':
                $html .= '<input id="' . esc_attr($field['id']) . '" type="' . $field['type'] . '" name="' . esc_attr($option_name) . '" placeholder="' . esc_attr($field['placeholder']) . '" value="' . $data . '"/>' . "\n";
                break;

            case 'text_secret':
                $html .= '<input id="' . esc_attr($field['id']) . '" type="text" name="' . esc_attr($option_name) . '" placeholder="' . esc_attr($field['placeholder']) . '" value=""/>' . "\n";
                break;

            case 'textarea':
                $html .= '<textarea id="' . esc_attr($field['id']) . '" rows="5" cols="50" name="' . esc_attr($option_name) . '" placeholder="' . esc_attr($field['placeholder']) . '">' . $data . '</textarea><br/>' . "\n";
                break;

            case 'checkbox':
                $checked = '';
                if ($data && 'on' == $data) {
                    $checked = 'checked="checked"';
                }
                $html .= '<input id="' . esc_attr($field['id']) . '" type="' . $field['type'] . '" name="' . esc_attr($option_name) . '" ' . $checked . '/>' . "\n";
                break;

            case 'checkbox_multi':
                foreach ($field['options'] as $k => $v) {
                    $checked = false;
                    if (is_array($data) && in_array($k, $data)) {
                        $checked = true;
                    }
                    $html .= '<label for="' . esc_attr($field['id'] . '_' . $k) . '"><input type="checkbox" ' . checked($checked, true, false) . ' name="' . esc_attr($option_name) . '[]" value="' . esc_attr($k) . '" id="' . esc_attr($field['id'] . '_' . $k) . '" /> ' . $v . '</label> ';
                }
                break;

            case 'radio':
                foreach ($field['options'] as $k => $v) {
                    $checked = false;
                    if ($k == $data) {
                        $checked = true;
                    }
                    $html .= '<label for="' . esc_attr($field['id'] . '_' . $k) . '"><input type="radio" ' . checked($checked, true, false) . ' name="' . esc_attr($option_name) . '" value="' . esc_attr($k) . '" id="' . esc_attr($field['id'] . '_' . $k) . '" /> ' . $v . '</label> ';
                }
                break;

            case 'select':
                $html .= '<select name="' . esc_attr($option_name) . '" id="' . esc_attr($field['id']) . '">';
                foreach ($field['options'] as $k => $v) {
                    $selected = false;
                    if ($k == $data) {
                        $selected = true;
                    }
                    $html .= '<option ' . selected($selected, true, false) . ' value="' . esc_attr($k) . '">' . $v . '</option>';
                }
                $html .= '</select> ';
                break;

            case 'select_multi':
                $html .= '<select name="' . esc_attr($option_name) . '[]" id="' . esc_attr($field['id']) . '" multiple="multiple">';
                foreach ($field['options'] as $k => $v) {
                    $selected = false;
                    if (is_array($data) && in_array($k, $data)) {
                        $selected = true;
                    }
                    $html .= '<option ' . selected($selected, true, false) . ' value="' . esc_attr($k) . '" />' . $v . '</label> ';
                }
                $html .= '</select> ';
				break;
			case 'documentation':
             
                ob_start();
                
				?>
				<div class="atr-posts-by-cat-docs" style="background: #fff;padding: 30px;">
					<div>
					<h1><?php _e('Posts by categories shortcode options', $this->textdomain);?></h1>
                        <p><?php _e('To list posts by category. Use ( || means OR (select one))', $this->textdomain);?> <br />
                        [posts-list posts_per_page="2" terms='4,15,11,21,18,29,25' excerpt=0 more_info=0 pager='next_prev_arr' || 'next_prev_numbered' || 'next_prev' ]<br />
                        [posts-list posts_per_page="2" terms='117' full_content=1  more_info=0]<br />
                        [posts-list posts_per_page="2" terms='117' excerpt=0 more_info=0 pager='next_prev_arr' ]<br />
                        See more in the wiki<br />
                        You can use custom templates to display posts.  The option atr-posts-cat-template="cards" will use the file content-cards-template.php (a bootstrap card layout). <br /> 		
                        [posts-list posts_per_page="10" terms="11" pager="next_prev_numbered"  atr-posts-cat-template="cards" more_info=1 show-date=1 ]<br />
                        
                        You can select another post type to show, like products<br />
                        [posts-list post_type="product " posts_per_page="10" terms="11" pager="next_prev_numbered"  atr-posts-cat-template="cards" more_info=1 show-date=1 ]	<br />
                        [posts-list  wrapper-id="ma-kore" make-ticker="yes"]  makes the list a ticker. must define both to work    <br />
                        [cats-list] is not for use at the moment.</p>
                        <p><br />
                        <strong><underline>shortcode atts</underline></strong><br />
                        post-type (default post)<br />
                        taxonomy (default category)<br />
                        posts_per_page (default  -1)<br />
                        terms (default -1)<br />
                        full_content (default  -1)<br />
                        excerpt (default  1)<br />
                        more_info (default 1)<br />
                        pager (default next_prev)<br />
                        atr-posts-cat-template (default )<br />
                        show-title (default 1)<br />
                        link-title (default 1)<br /> 
                        show-thumbnail (default 1)<br />
                        link-thumbnail (default 1)<br />
                        show-date (default0)<br />
                        wrapper-id (default empty)<br />
                        make-ticker  (default 'no')<br />
                        </p>
					</div>					
				 </div>
				 <?php
				 $html .= ob_get_clean();
				break;

        }

        switch ($field['type']) {

            case 'checkbox_multi':
            case 'radio':
            case 'select_multi':
                $html .= '<br/><span class="description">' . $field['description'] . '</span>';
                break;

            default:
                $html .= '<label for="' . esc_attr($field['id']) . '"><span class="description">' . $field['description'] . '</span></label>' . "\n";
                break;
        }
        
        echo $html;
    }

    /**
     * Validate individual settings field
     * @param  array $data Inputted value
     * @return array       Validated value
     */
    public function validate_fields($data)
    {
        // $data array contains values to be saved:
        // either sanitize/modify $data or return false
        // to prevent the new options to be saved

        // Sanitize fields, eg. cast number field to integer
        // $data['number_field'] = (int) $data['number_field'];

        // Validate fields, eg. don't save options if the password field is empty
        // if ( $data['password_field'] == '' ) {
        //     add_settings_error( $this->plugin_name, 'no-password', __('A password is required.', $this->textdomain), 'error' );
        //     return false;
        // }

        foreach ($_POST as $key => $val) {
            $data['row_height'] = (int) $data['row_height'];
            $data['max_row'] = (int) $data['max_row']; 
            $data['speed'] = (int) $data['speed'];
            $data['duration'] = (int) $data['duration'];
            $data['direction'] = sanitize_text_field($data['direction']);
            $data['autostart'] = sanitize_text_field($data['autostart']);
            $data['pauseOnHover'] = sanitize_text_field($data['pauseOnHover']);
            $data['nextButton'] = sanitize_text_field($data['nextButton']);
            $data['prevButton'] = sanitize_text_field($data['prevButton']);
            $data['startButton'] = sanitize_text_field($data['startButton']);
            $data['stopButton'] = sanitize_text_field($data['stopButton']);
            $data['hasMoved'] = sanitize_text_field($data['hasMoved']);
            $data['movingUp'] = sanitize_text_field($data['movingUp']);
            $data['movingDown'] = sanitize_text_field($data['movingDown']);
            $data['start'] = sanitize_text_field($data['start']);
            $data['stop'] = sanitize_text_field($data['stop']);
            $data['pause'] = sanitize_text_field($data['pause']);
            $data['unpause'] = sanitize_text_field($data['unpause']);



            if ($data['drawer_post_id']) {
                $data['drawer_post_id'] = intval($data['drawer_post_id']);
            }
            if ($data['drawer_width']) {
                $data['drawer_width'] = sanitize_text_field($data['drawer_width']);
            }
            if ($data['popup_post_id']) {

                $data['popup_post_id'] = intval($data['popup_post_id']);
            }
            if ($data['drawer_handle_bg_field']) {
                $data['drawer_handle_bg_field'] = sanitize_hex_color($data['drawer_handle_bg_field']);
            }
        }

        return $data;
    }

    /**
     * Load settings page content
     * @return void
     */
    public function settings_page()
    {
        // Build page HTML output
        // If you don't need tabbed navigation just strip out everything between the <!-- Tab navigation --> tags.
        ?>
	  <div class="wrap" id="<?php echo $this->plugin_name; ?>">
	  	<h2><?php _e('ATR Posts by Cat', $this->textdomain);?></h2>
	  	<p><?php _e('The plugin options.', $this->textdomain);?></p>

		<!-- Tab navigation starts -->
		<h2 class="nav-tab-wrapper settings-tabs hide-if-no-js">
			<?php 
    // var_dump($this->get_options()['show_reset_popup']);        
foreach ($this->settings as $section => $data) {
            echo '<a href="#' . $section . '" class="nav-tab">' . $data['title'] . '</a>'; 
            // if ( $data['title'] == 'JQuery Advanced News Ticker<br />' ) {
            //     var_dump( $data['fields'][0]['id']);
            // }
            
        }
        ?>
		</h2>
		<?php $this->do_script_for_tabbed_nav();?>
		<!-- Tab navigation ends -->

		<form action="options.php" method="POST">
	        <?php settings_fields($this->plugin_name);?>
	        <div class="settings-container"> 
	        <?php do_settings_sections($this->plugin_name);?>
	    	</div>
	        <?php submit_button();?>
		</form>
	</div>
	<?php
}

    /**
     * Print jQuery script for tabbed navigation
     * @return void
     */
    private function do_script_for_tabbed_nav()
    {
        // Very simple jQuery logic for the tabbed navigation.
        // Delete this function if you don't need it.
        // If you have other JS assets you may merge this there.
        ?>
		<script>
		jQuery(document).ready(function($) {
			var headings = jQuery('.settings-container > h2, .settings-container > h3');
			var paragraphs  = jQuery('.settings-container > p');
			var tables = jQuery('.settings-container > table');
			var triggers = jQuery('.settings-tabs a');

			triggers.each(function(i){
				triggers.eq(i).on('click', function(e){
					e.preventDefault();
					triggers.removeClass('nav-tab-active');
					headings.hide();
					paragraphs.hide();
					tables.hide();

					triggers.eq(i).addClass('nav-tab-active');
					headings.eq(i).show();
					paragraphs.eq(i).show();
					tables.eq(i).show();
				});
			})

			triggers.eq(0).click();
            $('#drawer_handle_bg_field').wpColorPicker();
		});
		</script>
	<?php
}

    public function add_menu_item()
    {
        $page = add_options_page('ATR Posts By Cat', 'ATR Posts By Cat', 'manage_options', $this->plugin_name, array($this, 'settings_page'));

    }
    /**
     * Add settings link to plugin list table
     * @param  array $links Existing links
     * @return array         Modified links
     */
    public function add_action_links($links)
    {

        $links[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=' . $this->plugin_name)) . '">' . __('Settings & how to use', $this->textdomain) . '</a>';
        $links[] = '<a href="https://atarimtr.co.il" target="_blank">More plugins by Yehuda Tiram</a>';
        return $links;
    }
}
	