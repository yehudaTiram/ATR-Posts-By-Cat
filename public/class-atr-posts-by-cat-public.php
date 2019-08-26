<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://atarimtr.co.il/
 * @since      1.0.0
 *
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/public
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
class Atr_Posts_By_Cat_Public
{

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
     * Check if the main load_posts_by_selected_categories_sc shortcode is active in page.
     *
     * @since    1.0.0
     * @access   private
     * @var      bool    $load_posts_by_selected_categories_sc_active.
     */
    public $load_posts_by_selected_categories_sc_active = false; // Not used

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */

    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    // Add actions only if the short
    public function add_shortcode_scripts() // Not used
    {
        if (!$this->addScript) {
            return false;
        }

        wp_enqueue_script('shortcode-js', get_stylesheet_directory_uri() . '/js/shortcode.js', false);
    }

    public function my_post_queries($query) // Not used
    {
        // do not alter the query on wp-admin pages and only alter it if it's the main query

        if (!is_admin() && $query->is_main_query()) {

            // alter the query for the home and category pages

            if (is_home()) {
                $query->set('posts_per_page', 3);
            }

            if (is_category()) {
                $query->set('posts_per_page', 3);
            }

        }
    }
    public function load_posts_by_selected_categories()
    {
        $cats_posts_selected = array();
        if (!empty($_POST['catsposts'])) {
            $cats_posts = $_POST['catsposts'];
            if (empty($cats_posts)) {
                echo ("You didn't select any boxes.");
            } else {
                $i = count($cats_posts);
                for ($j = 0; $j < $i; $j++) {
                    array_push($cats_posts_selected, $cats_posts[$j]);
                    echo $cats_posts[$j];
                    echo '<br />';
                }
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 2,
                    'paged' => (get_query_var('paged')),
                    'cache_results' => false,
                    'tax_query' => array(
                        'relation' => 'OR',
                        array(
                            'taxonomy' => 'category',
                            'field' => 'slug',
                            'terms' => $cats_posts_selected,
                        ),
                    ),
                );
            }
        } else {
            // get all terms in the taxonomy
            $terms = get_terms('category');
            // convert array of term objects to array of term IDs
            $term_ids = wp_list_pluck($terms, 'term_id');
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 1,
                'paged' => (get_query_var('paged')),
                'cache_results' => false,
            );
        }

        // The Query
        //$wp_query = new WP_Query( $args );

        if (get_query_var('paged')) {$paged = get_query_var('paged');} elseif (get_query_var('page')) {$paged = get_query_var('page');} else { $paged = 1;}

        $the_query = new WP_Query('posts_per_page=1&paged=' . $paged);

        if (is_main_query()) {
            echo '<h2>is_main_query</h2>';
        }

        if (get_query_var('paged') > 0) {
            echo '<nav id="nav-posts" class="blog-pages-nav">';
            echo '<div class="next">';
            previous_posts_link('<i class="fa fa-arrow-circle-left" aria-hidden="true"></i>');
            echo '</div>';
            echo '<div class="prev">';
            next_posts_link('<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>');
            echo '</div>';
            echo '</nav>';
        } else {
            echo '<nav id="nav-posts" class="blog-pages-nav">';
            echo '<div class="prev">';
            next_posts_link('<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>');
            echo '</div>';
            echo '</nav>';
        }
        //pagenavi();
        // The Loop
        if ($the_query->have_posts()) {
            echo '<ul>';
            while ($the_query->have_posts()) {
                $the_query->the_post();
                //echo '<li>' . get_the_title() . '</li>';
                echo '<h2 class="blog-page-post-title"><a class="blog-page-post-title" href="';
                the_permalink();
                echo '">';
                the_title();
                echo '</a> </h2>';
                the_excerpt();
                echo '<br /><a class="blog-page-more-info" href="';
                the_permalink();
                echo '">' . __('Read more...', 'atr-posts-by-cat') . '</a>';
            }
            echo '</ul>';
            /* Restore original Post Data */
            wp_reset_postdata();
        } else {
            // no posts found
        }
        // if ((get_query_var('paged') ? get_query_var('paged') : 1) > 1) {
        // echo '<nav id="nav-posts" class="blog-pages-nav">';
        // echo '<div class="next">';
        // echo previous_posts_link('<i class="fa fa-arrow-circle-left" aria-hidden="true"></i>');
        // echo ';</div>';
        // echo '<div class="prev">';
        // next_posts_link('<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>');
        // echo '</div>';
        // echo '</nav>';
        // } else {
        // echo '<nav id="nav-posts" class="blog-pages-nav">';
        // echo '<div class="prev">';
        // next_posts_link('<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>');
        // echo '</div>';
        // echo '</nav>';
        // }
    }

    /**
     * Generate the loop args.
     *
     * @since    1.0.0
     */
    public function loop_args() // Not used
    {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        global $cats_posts_selected;
        $cats_posts_selected = array();
        if (!empty($_POST['catsposts'])) {
            $cats_posts = $_POST['catsposts'];
            if (empty($cats_posts)) {
                echo ("You didn't select any boxes.");
            } else {
                $i = count($cats_posts);
                for ($j = 0; $j < $i; $j++) {
                    array_push($cats_posts_selected, $cats_posts[$j]);
                    echo $cats_posts[$j];
                    echo '<br />';
                }
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 2,
                    'paged' => $paged,
                    'cache_results' => false,
                    'tax_query' => array(
                        'relation' => 'OR',
                        array(
                            'taxonomy' => 'category',
                            'field' => 'slug',
                            'terms' => $cats_posts_selected,
                        ),
                    ),
                );
            }
        } else {
            // get all terms in the taxonomy
            $terms = get_terms('category');
            // convert array of term objects to array of term IDs
            $terms_ids = wp_list_pluck($terms, 'name', 'term_id');
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 1,
                'paged' => $paged,
                'cache_results' => false,
                'tax_query' => array(
                    'relation' => 'OR',
                    array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        //'terms'    => $cats_posts_selected,
                        //'terms'    => array( 'news-events','ip-cloud-ip-core' ),
                        'terms' => $terms_ids,
                    ),
                ),
            );
        }

        return $args;
    }

    /**
     * Generate the Loop.
     *
     * @since    1.0.0
     */
    public function set_the_loop($wp_query_passed) // Not used
    {
        $returned_posts = '';
        if ($wp_query_passed->have_posts()) {
            $returned_posts .= '<div class="posts-by-cats-wrapper"><ul>';
            while ($wp_query_passed->have_posts()) {
                $wp_query_passed->the_post();
                $returned_posts .= '<li>';
                //. get_the_title() .
                $returned_posts .= '<h2 class="blog-page-post-title"><a class="blog-page-post-title" href="';
                $returned_posts .= get_permalink();
                $returned_posts .= '">';
                $returned_posts .= get_the_title();
                $returned_posts .= '</a> </h2>';
                //if ($use_full) the_content();
                //$returned_posts .= the_content();
                $returned_posts .= apply_filters('the_content', get_the_content());
                //if ($use_excerpt)the_excerpt();
                $returned_posts .= '<a class="blog-page-more-info" href="';
                $returned_posts .= get_permalink();
                $returned_posts .= '">MORE INFO &gt;</a>';
                $returned_posts .= '</li>';
            }
            $returned_posts .= '</ul></div>';

            if ($wp_query_passed->max_num_pages > 1) {
                $returned_posts .= '<nav id="nav-posts" class="blog-pages-nav">';
                $returned_posts .= '<div class="next">';
                $returned_posts .= previous_posts_link('<i id="prev-posts" class="fa fa-arrow-circle-left" aria-hidden="true"></i>', $wp_query_passed->max_num_pages);
                $returned_posts .= '</div>';
                $returned_posts .= '<div class="prev">';
                $returned_posts .= get_next_posts_link('<i id="next-posts" class="fa fa-arrow-circle-right" aria-hidden="true"></i>', $wp_query_passed->max_num_pages);

                $returned_posts .= '</div>';
                $returned_posts .= '</nav>';

            } else {
                $returned_posts .= '<nav id="nav-posts" class="blog-pages-nav">';
                $returned_posts .= '<div class="prev">';
                $returned_posts .= next_posts_link('<i id="next-posts" class="fa fa-arrow-circle-right" aria-hidden="true"></i>', $wp_query_passed->max_num_pages);
                $returned_posts .= '</div>';
                $returned_posts .= '</nav>';
            }

            /* Restore original Post Data */
            wp_reset_postdata();
        } else {
            $returned_posts .= wpautop('Sorry, no posts were found');
        }

        return $returned_posts;
    }

    /**
     * Generate the pager.
     *
     * @since    1.0.0
     */
    public function set_pagination($wp_query_passed, $next_html = 'Next', $prev_html = 'Previous')
    {
        $pagination_output = '';
        if ($wp_query_passed->max_num_pages > 1) {
            $pagination_output .= '<nav id="nav-posts" class="blog-pages-nav">';
            $pagination_output .= '<div class="prev">';
            $pagination_output .= get_previous_posts_link('<i id="prev-posts" class="fa fa-arrow-circle-left" aria-hidden="true"></i> ' . $prev_html, $wp_query_passed->max_num_pages);
            $pagination_output .= '</div>';

            $pagination_output .= '<div class="next">';
            $pagination_output .= get_next_posts_link($next_html . ' <i id="next-posts" class="fa fa-arrow-circle-right" aria-hidden="true"></i>', $wp_query_passed->max_num_pages);
            $pagination_output .= '</div>';
            $pagination_output .= '</nav>';

        } else {
            $pagination_output .= '<nav id="nav-posts" class="blog-pages-nav">';
            $pagination_output .= '<div class="prev">';
            $pagination_output .= next_posts_link($next_html, $wp_query_passed->max_num_pages);
            $pagination_output .= '</div>';
            $pagination_output .= '</nav>';
        }
        return $pagination_output;
    }

    public function load_posts_by_selected_categories_sc($atts, $content = null)
    {

        $pull_cats_atts = shortcode_atts(array(
            'post-type' => 'post',
            'taxonomy' => 'category',
            'posts_per_page' => -1,
            'terms' => '-1',
            'full_content' => -1,
            'excerpt' => 1,
            'more_info' => 1,
            'pager' => 'next_prev',
            'atr-posts-cat-template' => '',
            'show-title' => '1',
            'link-title' => '1', 
            'show-thumbnail' => '1',
            'link-thumbnail' => '1',
            'show-date'     => '0',


        ), $atts);

        if (get_query_var('paged')) {$paged = get_query_var('paged');} elseif (get_query_var('page')) {$paged = get_query_var('page');} else { $paged = 1;}

        $termsIDs = [];
        
        if ( wp_kses_post($pull_cats_atts['terms']) == -1 ){
            $terms = get_terms( array( 'taxonomy' => wp_kses_post($pull_cats_atts['taxonomy']) ) );

            if ( ! empty($terms)){
                foreach ( $terms as $term ) {                              
                    foreach ( $term as $key => $value ) {
                        if ( $key == 'term_id'){
                            array_push($termsIDs, $value );
                        }
                    }
                }            
            }
        }else{
            $termsIDs = explode(',', wp_kses_post($pull_cats_atts['terms']));
        }

        $args = array(
            'post_type' => wp_kses_post($pull_cats_atts['post-type']),
            'posts_per_page' => wp_kses_post($pull_cats_atts['posts_per_page']),
            'paged' => $paged,
            'tax_query' => array(
                array(
                    'taxonomy' => wp_kses_post($pull_cats_atts['taxonomy']),
                    'field' => 'term_id',
                    'terms' => $termsIDs,
                ),
            ),
        );

        $the_query = new WP_Query($args);
        
        $my_posts = '';

        if ($the_query->have_posts()) {            

            if ( empty(wp_kses_post($pull_cats_atts['atr-posts-cat-template']) ) ){
               ob_start(); 
                $my_posts .= '<div class="atr-posts-by-cat-list-wrap"><!-- 393 -->';
                $my_posts .= '<ul class="atr-posts-by-cat-list">';
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    $id = get_the_ID();
                    
                    $my_posts .= '<li><h2 class="blog-page-post-title"><a class="blog-page-post-title" href="';
                    $my_posts .= get_the_permalink();
                    $my_posts .= '">';
                    $my_posts .= get_the_title();
                    $my_posts .= '</a> </h2>';



                    $my_posts .= '<a class="atr-posts-by-cat-post-thumbnail" href="';
                    $my_posts .= get_the_permalink();
                    $my_posts .= '">';
                    $my_posts .= get_the_post_thumbnail( $id, 'thumbnail' );
                    $my_posts .= '</a><div class="atr-posts-by-cat-post-content-wrap"><!-- 411 -->';

                    if (wp_kses_post($pull_cats_atts['full_content']) == 1) {
                        $my_posts .= get_the_content();
                    } else {
                        (wp_kses_post($pull_cats_atts['excerpt']) == 1) ? $my_posts .= get_the_excerpt() : $my_posts .= '';
                    }
                    if (wp_kses_post($pull_cats_atts['more_info']) == 1) {
                        $my_posts .= '<a class="blog-page-more-info" href="';
                        $my_posts .= get_the_permalink();
                        $my_posts .= '">';
                        $my_posts .= __('Read more...', 'atr-posts-by-cat');
                        $my_posts .= '</a>';
                    }                
                    $my_posts .= '</div><!-- 425 -->';

                    $my_posts .= '</li>';
                }
                $my_posts .= '</ul>';
                if (wp_kses_post($pull_cats_atts['pager']) == 'next_prev_numbered') {
                    $my_posts .= '<nav id="nav-posts" class="blog-pages-nav">';
                    $big = 999999999; // need an unlikely integer
                    $my_posts .= paginate_links(array(
                        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                        'before_page_number' => '<span class="single-page-link">',
                        'format' => '?paged=%#%',
                        'after_page_number' => '</span>',
                        'current' => max(1, get_query_var('paged')),
                        'total' => $the_query->max_num_pages,                     
                    ));
                    $my_posts .= '</nav><!-- 441 -->';
                } elseif (wp_kses_post($pull_cats_atts['pager']) == 'next_prev_arr') {
                    $GLOBALS['wp_query'] = $the_query;
                    $pagination = get_the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => __('<i id="prev-posts" class="fa fa-arrow-circle-right" aria-hidden="true"></i> Previous', 'atr-posts-by-cat'),
                        'next_text' => __('<i id="prev-posts" class="fa fa-arrow-circle-left" aria-hidden="true"></i> Next', 'atr-posts-by-cat'),
                    ));
                    $my_posts .= '<nav id="nav-posts" class="blog-pages-nav">';
                    $my_posts .= $pagination;
                    $my_posts .= '</nav><!-- 451 -->';
                } else {
                    $my_posts .= $this->set_pagination($the_query, 'Next', 'Previous');
                }

                $my_posts .= '</div><!-- 456 -->';
                ob_end_clean();
             /* Restore original Post Data */
             wp_reset_postdata();               
            }

            else{ // We load a template file for the posts display

                ob_start();
                $templates = new Atr_Posts_By_Cat_Template_Loader();

                $data = array(
                    'loop'              => $the_query,
                    'atr_posts_cat_template'    => wp_kses_post($pull_cats_atts['atr-posts-cat-template']),
                    'full_content'              => wp_kses_post($pull_cats_atts['full_content']),
                    'excerpt'                   => wp_kses_post($pull_cats_atts['excerpt']),
                    'more_info'                 => wp_kses_post($pull_cats_atts['more_info']),
                    'show_title'                => wp_kses_post($pull_cats_atts['show-title']),
                    'link_title'                => wp_kses_post($pull_cats_atts['link-title']),
                    'show_thumbnail'            => wp_kses_post($pull_cats_atts['show-thumbnail']),
                    'link_thumbnail'            => wp_kses_post($pull_cats_atts['link-thumbnail']),
                    'pager'                     => wp_kses_post($pull_cats_atts['pager']),
                    'show_date'                 => wp_kses_post($pull_cats_atts['show-date']),
        
        
                ); // Pass this variable to the template

                echo '<div class="row atr-posts-by-cat-list-cards">';
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    $id = get_the_ID();
                    $passed_data_values_arr = wp_kses_post($pull_cats_atts['atr-posts-cat-template']) . '_values';
                    $passed_data_values_arr = preg_replace("/[\-]/", "_", $passed_data_values_arr );
                    $templates
                        ->set_template_data($data, $passed_data_values_arr)
                        ->get_template_part('content', wp_kses_post($pull_cats_atts['atr-posts-cat-template']) . '-template');    
                }
                echo '</div>';

                    $output = ob_get_clean();
                    return $output;        
            }


            

        } else {
            $my_posts .= 'no posts found';
        }

        //return $this->load_posts_by_selected_categories($the_query);

        return $my_posts;
    }

    public function sc_liste($atts, $content = null)
    {
        extract(shortcode_atts(array(
            "num" => '500',
            "cat" => '',
        ), $atts));
        global $post;
        $myposts = get_posts('numberposts=' . $num . '&order=DESC&orderby=post_date&category=' . $cat);
        $my_posts = '<ul>';
        foreach ($myposts as $post):
            setup_postdata($post);
            $my_posts .= '<li><h2 class="blog-page-post-title"><a class="blog-page-post-title" href="';
            $my_posts .= get_the_permalink();
            $my_posts .= '">';
            $my_posts .= get_the_title();
            $my_posts .= '</a> </h2>';
            $my_posts .= get_the_excerpt();
            $my_posts .= '<a class="blog-page-more-info" href="';
            $my_posts .= get_the_permalink();
            $my_posts .= '">MORE INFO &gt;</a></li>';
        endforeach;
        $my_posts .= '</ul> ';
        return $my_posts;
    }

    /**
     * Generate the shortcode for categories selector element.
     *
     * @since    1.0.0
     */
    public function list_categories_sc($atts)
    {
        $pull_cats_atts = shortcode_atts(array(
            'include' => array(21, 18, 29, 25, 15, 13, 4, 1, 11),
            'orderby' => 'name',
            'title_li' => '',
            'hide_empty' => 0,
            'hierarchical' => 0,

        ), $atts);
        $output = '<form  method="post">';
        $output .= "\n<ul class=\"product_cats\">\n";
        $wplist = wp_list_categories(array(
            'walker' => new Atr_Posts_By_Cat_list_Walker,
            'orderby' => wp_kses_post($pull_cats_atts['orderby']),
            'include' => wp_kses_post($pull_cats_atts['include']),
            'title_li' => wp_kses_post($pull_cats_atts['title_li']),
            'hide_empty' => wp_kses_post($pull_cats_atts['hide_empty']),
            'hierarchical' => wp_kses_post($pull_cats_atts['hierarchical']),
            'echo' => false, // Important in order not to output the list as is but as part ot $output
        ));
        $output .= $wplist;
        $output .= "</ul>\n";
        $output .= '<input type="submit" value="APPLY">';
        $output .= '<p><label><input type="checkbox" id="checkAll"/> Check all</label></p>';
        $output .= '</form>';
        return $output;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/atr-posts-by-cat-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        // Next line removed since we want to load js with ajax. See next
        //wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/atr-posts-by-cat-public.js', array( 'jquery' ), $this->version, false );

        // Load ajax for frontend
        wp_register_script('admin_ajax_front', plugin_dir_url(__FILE__) . 'js/atr-posts-by-cat-public.js', array('jquery'), $this->version, false);
        $translation_array = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        );
        wp_localize_script('admin_ajax_front', 'front', $translation_array);
        wp_enqueue_script('admin_ajax_front', false, array(), false, true); // last param set to true will enqueue script on footer

    }

    // function front_ajaxurl() {

    // }

    // function TestCategoryWidgetInit() {
    // register_widget( 'TestCategoryWidget' );
    // }

}
