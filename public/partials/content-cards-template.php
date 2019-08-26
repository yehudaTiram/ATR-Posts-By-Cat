<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://atarimtr.co.il/
 * @since      1.0.0
 * 
 * Template loaded by  [posts-list template="cards" ... ]
 * 
 * @package    Atr_Posts_By_Cat
 * @subpackage Atr_Posts_By_Cat/public/partials
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
?>
<?php
	/**
	 * $cards_values array is Passed from public\class-atr-posts-by-cat-public.php
	 * Made of the template file name, removed "content", removed "template" and replace "-" with "_"  
	 * content-cards-template.php -> cards
	 */
	
	$loop 				= $cards_values->loop;
	$full_content 		= $cards_values->full_content;
	$excerpt 			= $cards_values->excerpt;
	$more_info 			= $cards_values->more_info;
	$show_title 		= $cards_values->show_title;
	$link_title 		= $cards_values->link_title;
	$show_thumbnail 	= $cards_values->show_thumbnail;
	$link_thumbnail 	= $cards_values->link_thumbnail;
	$pager				= $cards_values->pager;
	$show_date			= $cards_values->show_date;

/********** */	 

$card_posts = '';

?>
<div class="col-md-4">
	<div class="card mb-4 box-shadow" > 
	<a class="atr-posts-by-cat-post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $id, 'thumbnail' ); ?></a>
	<div class="card-body">
	<h4 class="blog-page-post-title"><a class="blog-page-post-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
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
</div>

<?php
	 
/*************** */

            





		
