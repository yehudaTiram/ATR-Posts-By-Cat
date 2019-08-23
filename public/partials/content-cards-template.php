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
?>
	<div class="row atr-posts-by-cat-list-cards">
<?php
while ($loop->have_posts()) {
	$loop->the_post();
	$id = get_the_ID($loop->the_post());
	?>
	<div class="card">
	<a class="atr-posts-by-cat-post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $id, 'thumbnail' ); ?></a>
	<div class="card-body">
	<h2 class="blog-page-post-title"><a class="blog-page-post-title" href="<?php get_the_permalink(); ?>"><?php the_title(); ?></a></h2>
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
			<a href="<?php  the_permalink(); ?>" class="btn btn-primary"><?php  echo __('Read more...', 'atr-posts-by-cat'); ?></a>
			<?php 
		} 		
		?>
		</div>
		</div>
		<?php 
	} 
?>
	</div> 
<?php
	 
/*************** */
if ( $pager == 'next_prev_numbered') {
	
	$my_cards_posts = '<nav id="nav-posts" class="blog-pages-nav">';
	$big = 999999999; // need an unlikely integer
	$my_cards_posts .= paginate_links(array(
		'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
		'before_page_number' => '<span class="single-page-link">',
		'format' => '?paged=%#%',
		'after_page_number' => '</span>',
		'current' => max(1, get_query_var('paged')),
		'total' => $loop->max_num_pages,                     
	));
	$my_cards_posts .= '</nav><!-- 441 -->';
} elseif ($pager == 'next_prev_arr') {
	$GLOBALS['wp_query'] = $loop;
	$pagination = get_the_posts_pagination(array(
		'mid_size' => 2,
		'prev_text' => __('<i id="prev-posts" class="fa fa-arrow-circle-right" aria-hidden="true"></i> Previous', 'atr-posts-by-cat'),
		'next_text' => __('<i id="prev-posts" class="fa fa-arrow-circle-left" aria-hidden="true"></i> Next', 'atr-posts-by-cat'),
	));
	$my_cards_posts .= '<nav id="nav-posts" class="blog-pages-nav">';
	$my_cards_posts .= $pagination;
	$my_cards_posts .= '</nav><!-- 451 -->';
} else {
	$my_cards_posts .= $this->set_pagination($loop, 'Next', 'Previous');
}
$my_cards_posts .= '</div>';
echo $my_cards_posts;
            





		
