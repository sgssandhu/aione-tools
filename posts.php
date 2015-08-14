<?php 

// Post List Shortcode
function aione_list_post_shortcode( $atts ) {
    // Attributes
    extract( shortcode_atts(
            array(
            	'cat'           => '',
				'cat_id'           => '',
				'author'           => '',
				'author_id'           => '',
				'count'           => '',
				'id'           => '',
				'class'           => ''
				
            ), $atts )
    );
	
	global $theme_options, $post;
	
    $output = "";
	
	// WP_Query arguments
	$args = array (
		'post_type'              => 'post',
		'post_status'            => 'publish',
		'cat'                    => $cat_id,
		'category_name'          => $cat,
		'author'                 => $author_id,
		'author_name'            => $author,
		'pagination'             => false,
		//'paged'                  => '',
		'posts_per_page'         => $count,
		'ignore_sticky_posts'    => false,
		'order'                  => 'DESC',
		'orderby'                => 'date',
		'cache_results'          => true,
		'update_post_meta_cache' => true,
		'update_post_term_cache' => true,
	);

	$resent_posts = new WP_Query($args);
    
    if($resent_posts->have_posts()){
		$output .= '<ul class="list-posts">';
			while($resent_posts->have_posts()){
				$resent_posts->the_post(); 
				$output .= '<li>';
					if(has_post_thumbnail()){
						$output .= '<div class="post-image">';
						$output .= '<a href="'.get_permalink().'">';
						$output .= 	get_the_post_thumbnail($post->ID,'tabs-img');
						$output .= '</a>';
						$output .= '</div>';
					} else {
						$output .= '<div class="post-image">';
						$output .= '<a href="'.get_permalink().'">';
						$output .= 	'<img width="52" height="50" src="'.plugin_dir_url( __FILE__ ).'/assets/images/placeholder_grey_52x50.png" class="attachment-tabs-img wp-post-image" alt="'.get_the_title().'" >';
						$output .= '</a>';
						$output .= '</div>';						
					}
					$output .= '<div class="post-holder">';
						$output .= '<a href="'.get_permalink().'" class="post-title">'.get_the_title().'</a>';
						$output .= '<div class="post-meta">';
						$output .= 	get_the_time($theme_options['date_format']);
						$output .= '</div>';
					$output .= '</div>';
					$output .= '<div class="aione-clearfix"></div>';
				$output .= '</li>';
				
			}
		$output .= '</ul>';
	}  else {
		$output .= '<h5 class="font-size-16 aligncenter">No Posts Available.</h5>';
	}
	return $output;
}
add_shortcode( 'aione-list-posts', 'aione_list_post_shortcode' );




// Comments List Shortcode
function aione_list_comments_shortcode( $atts ) {
    // Attributes
    extract( shortcode_atts(
            array(
				'count'           => '',
				'id'           => '',
				'class'           => ''
				
            ), $atts )
    );
	
	global $theme_options, $post;
	
    $output = "";


	$number = $count;
	global $wpdb;
	$recent_comments = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved, comment_type, comment_author_url, SUBSTRING(comment_content,1,110) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $number";
	$the_comments = $wpdb->get_results($recent_comments);
	if($the_comments){
		$output .= '<ul class="list-posts resent-comments">';
		foreach($the_comments as $comment) {
			$output .= '<li>';
			$output .= '<div class="post-image">';
			$output .= '<a>';
			$output .= 	get_avatar($comment, '52');
			$output .= '</a>';
			$output .= '</div>';
			
			$output .= '<div class="post-holder">';
			$output .= strip_tags($comment->comment_author) . ' says:';
			$output .= '<div class="post-meta">';
			$output .= '<a class="comment-text-side" href="' . get_permalink($comment->ID).'#comment-' .$comment->comment_ID . '" title="'.strip_tags($comment->comment_author) .' on '.$comment->post_title .'">';
			$output .= string_limit_words(strip_tags($comment->com_excerpt), 12);
			$output .= '...</a>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '<div class="aione-clearfix"></div>';
			$output .= '</li>';
		}
		$output .= '</ul>';
	} else {
		$output .= '<h5 class="font-size-16 aligncenter">No Comments Available.</h5>';
	}
	return $output;
}
add_shortcode( 'aione-list-comments', 'aione_list_comments_shortcode' );


// Add Shortcode
function aione_faq_shortcode($atts) {

	// Attributes
	extract(shortcode_atts(
		array(
			'category' => '',
			'id' => '',
			'class' => '',
		), $atts)
	);
	
	$output = '';
	if (!$category){
	return '';
		$portfolio_category = get_terms('faq_category');
		if ($portfolio_category){
		$output .= '<ul class="faq-tabs clearfix">';
		$output .= '<li class="active"><a data-filter="*" href="#">' . __('All', 'Aione') . '</a></li>';
		foreach ($portfolio_category as $portfolio_cat){
			$output .= '<li><a data-filter=" ' . urldecode($portfolio_cat->slug) .'"href="#">' . $portfolio_cat->name . '</a></li>';
		}
		$output .= '</ul>';
		}
	} 
    
    $output .= '<div class="portfolio-wrapper">';
    $output .= '<div class="accordian aione-accordian">';
    $output .= '<div class="panel-group" id="accordian-one">';
    
    $args = array(
        'post_type' => 'aione_faq',
        'tax_query' => array(
		array(
			'taxonomy' => 'faq_category',
			'field'    => 'slug',
			'terms'    => $category,
		),
	),
        'nopaging' => true
    );
    $gallery = new WP_Query($args);
    $count = 0;
    while($gallery->have_posts()): $gallery->the_post();
  
    
	    $count++;
	    $item_classes = '';
	    $item_cats = get_the_terms($post->ID, 'faq_category');
	    if ($item_cats):
	        foreach ($item_cats as $item_cat) {
	            $item_classes .= urldecode($item_cat->slug) . ' ';
	        }
	    endif;
	    $output .= '<div class="aione-panel panel-default faq-item '. $item_classes .'">';
	    $output .= aione_render_rich_snippets_for_pages();
	    $output .= '<div class="panel-heading">';
	    $output .= '<h4 class="panel-title toggle"><a data-toggle="collapse" class="collapsed" data-parent="#accordian-one" href="#collapse-'.get_the_ID().'"><i class="fa-aione-box"></i>';
	    $output .= get_the_title();
	    $output .= '</a></h4>';
	    $output .= '</div>';
	    $output .= '<div id="collapse-'.get_the_ID().'" class="panel-collapse collapse">';
	    $output .= '<div class="panel-body toggle-content post-content">';
	    $output .= get_the_content();
	    $output .= '</div>';
	    $output .= '</div>';
	    $output .= '</div>';
    endwhile;
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    return $output;

}
add_shortcode( 'faq', 'aione_faq_shortcode' );
?>