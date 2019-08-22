<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes the custom category walker
 *
 * @link       http://atarimtr.co.il/
 * @since      1.0.0
 *
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/includes
 */

/**
 * The plugin Widget for cats list selection in frontend class.
 *
 *
 *
 * @since      1.0.0
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/includes
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
class Atr_Posts_By_Cat_Select_widget  extends WP_Widget {

	function __construct() {
		parent::__construct(
		// Base ID of your widget
		'Atr_Posts_By_Cat_Select_widget', 

		// Widget name will appear in UI
		__('ATR Posts By Cat Select Widget', 'atr-posts-by-cat'), 

		// Widget description
		array( 'description' => __( 'Widget for cats list selection', 'atr-posts-by-cat' ), ) 
		);
	}

	// Creating widget front-end
	// This is where the action happens
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];

		// This is where you run the code and display the WIDGET output
		$output = '';
		$output .=  "\n<ul class=\"product_cats\">\n";  	
		$wplist =   wp_list_categories( array(
			'walker'=> new Atr_Posts_By_Cat_list_Walker,
			'orderby' => 'name',
			'include'=> array( 21,18,29,25,15,13,4,1,11 ),
			'title_li' => '',
			'hide_empty' => 0,
			'hierarchical' => 0,	
			'echo'       => false	// Important in order not to output the list as is but as part ot $output		
			));	
		$output .= $wplist;	
		$output .=  "</ul>\n";			
		echo $output;
		echo $args['after_widget'];
	}
		
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
		}
		else {
		$title = __( 'ATR Categories selection', 'atr-posts-by-cat' );
		}
		// Widget admin form
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
        $defaults = array( 'widget_categories' => array() );
        $instance = wp_parse_args( (array) $instance, $defaults );    
        // Instantiate the walker passing name and id as arguments to constructor
        $walker = new Atr_Posts_By_Cat_walker(
            $this->get_field_name( 'widget_categories' ), 
            $this->get_field_id( 'widget_categories' )
        );
        echo '<ul class="categorychecklist">';
        wp_category_checklist( 0, 0, $instance['widget_categories'], FALSE, $walker, FALSE );
        echo '</ul>';?>		
		<?php 
	}
	
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	return $instance;
	}
}
/**
 * The widget custom category walker class.
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

