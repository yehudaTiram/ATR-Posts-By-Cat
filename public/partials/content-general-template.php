<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://atarimtr.co.il/
 * @since      1.0.0
 * 
 * Template loaded by  [posts-list template="general" ... ]
 * 
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/public/partials
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
?>
<?php
	/**
	 * $general_values array is Passed from public\class-atr-posts-by-cat-public.php
	 * Made of the template file name, removed "content", removed "template" and replace "-" with "_"  
	 * content-general-template.php -> general
	 */
	
	$loop 				= $general_values->loop;
	$full_content 		= $general_values->full_content;
	$excerpt 			= $general_values->excerpt;
	$more_info 			= $general_values->more_info;
	$show_title 		= $general_values->show_title;
	$link_title 		= $general_values->link_title;
	$show_thumbnail 	= $general_values->show_thumbnail;
	$link_thumbnail 	= $general_values->link_thumbnail;
	$pager				= $general_values->pager;
	$show_date			= $general_values->show_date;

/********** */	 

$card_posts = '';

?>


	<li>
		<div class="atr-posts-by-cat-general-post-thumbnail-wrap">
			<a class="atr-posts-by-cat-post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
		</div>
		<div class="atr-posts-by-cat-general-post-content-wrap">
			<h2 class="blog-page-post-title"><a class="blog-page-post-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>		
			<div class="atr-posts-by-cat-post-content-wrap">
			<?php
			if( $show_date == 1 ){ 
				?>
					<h6>
					<?php the_date(); ?>
					</h6>
			<?php
			}
			if ( $full_content == 1) {
				the_content();
			} else {
				( $excerpt == 1) ? the_excerpt() : '';
			}
			if ( $more_info == '1') { 
				?>
				<a href="<?php  the_permalink(); ?>" class="btn btn-outline-secondary btn-sm"><?php  echo __('Read more...', 'atr-posts-by-cat'); ?></a>
				<?php 
			} 		
			?>
			</div>			
		</div>

	</li>


<?php
	 
/*************** */

            





		
