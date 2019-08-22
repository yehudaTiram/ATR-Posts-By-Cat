<?php 
/**
 * The public-facing categories walker.
 *
 * @link       http://www.atarimtr.com/
 * @since      1.0.0
 *
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/public
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */

class Atr_Posts_By_Cat_list_Walker extends Walker_Category {

    function start_lvl(&$output, $depth=0, $args=array()) {  
        //$output .= "\n<ul class=\"product_cats\">\n";  
    }  

    function end_lvl(&$output, $depth=0, $args=array()) {  
        //$output .= "</ul>\n";  
    } 

	// Configure the start of each element
	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0) {
		// Set the category name and slug as a variables for later use
		$cat_name = esc_attr( $category->name );
		$cat_id = $category->term_id;
		$cat_name = apply_filters( 'list_cats', $cat_name, $category );
		$cat_slug = esc_attr( $category->slug );
		$n_depth = $depth + 1;
		$termchildren = get_term_children( $category->term_id, $category->taxonomy );
		$class = '';
		$cats_posts_checks_selected = array();
		if(count($termchildren)===0){
			$class .=  'blog-cat-list-li cat-item ' . 'cat-' . $cat_id .' no-sub';
		}

		// Configure the output for the top list element and its URL
		if ( count($termchildren)>0) {
			$link = '<a class="parent-category-dropdown" href="#"' . '>' . $cat_name . '</a>';
			$indent = str_repeat("\t", $depth);
			$output .= "\t<li class='parent-category $class " . $cat_slug . "' data-level='$n_depth'>$link\n$indent<div class='children'><ul>\n<li class='parent-name'><input type='checkbox' name='catsposts[]' id='" . $cat_id . "' value='" . $cat_slug ."'>" . $cat_name . "</li>";
		}

		// Configure the output for lower level list elements and their URL's
		if ( count($termchildren)===0) {
			$link = '<a href="#">' . $cat_name . '</a>';
			//$output .= "\t<li class='sub-category $class' data-level='$n_depth'><input type='checkbox' name='catsposts[]' id='" . $cat_id . "' value='" . $cat_slug . "'>$link\n";
			
			if(!empty($_POST['catsposts'])){
				$cats_posts_checks = $_POST['catsposts'];
				  if(empty($cats_posts_checks))
				  {
					//$output .= "\t" . $cat_slug . "\n";
					$output .= "\t<li class='sub-category $class' data-level='$n_depth'><input type='checkbox' name='catsposts[]' id='" . $cat_id . "' value='" . $cat_slug . "'>$link\n";
				  }
				  else
				  {
					$i = count($cats_posts_checks); 
					for($j=0; $j < $i; $j++)
					{
					  array_push($cats_posts_checks_selected, $cats_posts_checks[$j]);
					}	
					if (in_array($cat_slug, $cats_posts_checks)) {
						//$output .= "\t checked='checked'" . $cat_slug . "\n";	
						$output .= "\t<li class='sub-category $class' data-level='$n_depth'><input checked='checked' type='checkbox' name='catsposts[]' id='" . $cat_id . "' value='" . $cat_slug . "'>$link\n";
					}
					else {
						$output .= "\t<li class='sub-category $class' data-level='$n_depth'><input type='checkbox' name='catsposts[]' id='" . $cat_id . "' value='" . $cat_slug . "'>$link\n";
					}
				  }			
			}
			else {
				$output .= "\t<li class='sub-category $class' data-level='$n_depth'><input type='checkbox' name='catsposts[]' id='" . $cat_id . "' value='" . $cat_slug . "'>$link\n";
			}	
		} 
		// if( $depth > 1) {
		//   $link = '<a href="#">' . $cat_name . '</a>';
		//    $output .= "\n<li class='sub-category $class' data-level='$n_depth'>$link\n";
		// }
	}

	// Configure the end of each element
	function end_el(&$output, $category, $depth = 0,$args = array()) {
	  $termchildren = get_term_children( $category->term_id, $category->taxonomy );
		if (count($termchildren)>0) {
			$indent = str_repeat("\t", $depth);
			$output .= "$indent</ul>\n</div>\n";
		}
		if (count($termchildren)===0 ) {
			$output .= "</li>";
		}

	}

} 
