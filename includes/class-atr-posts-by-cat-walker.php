<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes the custom category walker
 *
 * @link       http://www.atarimtr.com/
 * @since      1.0.0
 *
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/includes
 */

/**
 * The plugin custom category walker class.
 *
 *
 *
 * @since      1.0.0
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/includes
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
class Atr_Posts_By_Cat_walker  extends Walker_Category_Checklist {

    private $name;
    private $id;

    function __construct( $name = '', $id = '' ) {
        $this->name = $name;
        $this->id = $id;
    }

    function start_el( &$output, $cat, $depth = 0, $args = array(), $id = 0 ) {
        extract( $args );
        if ( empty( $taxonomy ) ) $taxonomy = 'category';
        $class = in_array( $cat->term_id, $popular_cats ) ? ' class="popular-category"' : '';
        $id = $this->id . '-' . $cat->term_id;
        $checked = checked( in_array( $cat->term_id, $selected_cats ), true, false );
        $output .= "\n<li id='{$taxonomy}-{$cat->term_id}'$class>" 
            . '<label class="selectit"><input value="' 
            . $cat->term_id . '" type="checkbox" name="' . $this->name 
            . '[]" id="in-'. $id . '"' . $checked 
            . disabled( empty( $args['disabled'] ), false, false ) . ' /> ' 
            . esc_html( apply_filters( 'the_category', $cat->name ) ) 
            . '</label>';
      }
}

/**
 * An example of widget using wp_category_checklist on form
 */
class TestCategoryWidget extends WP_Widget {

    function __construct(){
        parent::__construct( false, 'TestWidget');
    }

    function widget( $args, $instance ) { 
        // Displays the widget on frontend 
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['widget_categories'] = $new_instance['widget_categories'];
        return $instance;
    }

    function form( $instance ) {
        $defaults = array( 'widget_categories' => array() );
        $instance = wp_parse_args( (array) $instance, $defaults );    
        // Instantiate the walker passing name and id as arguments to constructor
        $walker = new Atr_Posts_By_Cat_walker(
            $this->get_field_name( 'widget_categories' ), 
            $this->get_field_id( 'widget_categories' )
        );
        echo '<ul class="categorychecklist">';
        wp_category_checklist( 0, 0, $instance['widget_categories'], FALSE, $walker, FALSE );
        echo '</ul>';
    }

}
