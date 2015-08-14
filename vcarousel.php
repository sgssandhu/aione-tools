<?php
/*
Plugin Name: Aione Vcarousel
Plugin URI: http://oxosolutions.com/products/wordpress-plugins/aione_vcarousel
Description: Aione Vcarousel
Author: SGS Sandhu
Version: 1.0
Author URI: http://sgssandhu.com/
*/



// Aione Vcarousel Shortcode
function aione_vcarousel_shortcode(  $attr, $content = null, $code) {
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
	
	$output .= '<link href="http://174.129.11.69/epidemic/wp-content/plugins/aione-tools/assets/css/jquery.bxslider.css" rel="stylesheet" type="text/css">';
	$output .= '<script src="http://174.129.11.69/epidemic/wp-content/plugins/aione-tools/assets/js/jquery.min.js"></script>';
	$output .= '<script src="http://174.129.11.69/epidemic/wp-content/plugins/aione-tools/assets/js/jquery.bxslider.js"></script>';

	
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
	$vcarousel_id = mt_rand(1111111111, 9999999999);
    
    if($resent_posts->have_posts()){
		$output .= '<ul id="'.$code.'-'.$vcarousel_id.'" class="list-posts '.$class.'">';
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
	} else {
		$output .= '<h5 class="font-size-16 aligncenter">No Posts Available.</h5>';
	}

	
	
	
	/*
	$output = "";

$output .= '<div class="slider8">';
$posts = query_posts('category_name=blog&showposts=10');
while (have_posts()) : the_post();

$post_id = get_the_ID();
$thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id($post_id) );

$output .= '<div class="slide">';
//$output .= '<img src="'.$thumbnail_url.'"  width="40" height="40"  />';
$output .= '<a href="' . get_permalink() . '">'.get_the_title().'</a><br/>Posted by '.get_the_author().'<hr></div>';

endwhile;

$output .= '</div>';
*/

$output .= "<script>
        jQuery(document).ready(function(){
            jQuery('#".$code."-".$vcarousel_id."').bxSlider({
                mode: 'vertical',
                speed:1000,
                slideMargin:5,

                minSlides: ".$count.",
                maxSlides: ".$count.",
                moveSlides:1,
                slideWidth: 400,

                //startSlide:0,
                //randomStart:false,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
                //slideSelector:'div.slide',
                //infiniteLoop:true,
                hideControlOnEnd:true,
                //easing:null,
                //captions:false,
                //ticker:true,
                //tickerHover:true,
                adaptiveHeight:true,
                adaptiveHeightSpeed:500,
                //video:false,
                responsive:true,
                useCSS:true,
                preloadImages:true,
                touchEnabled:true,
                oneToOneTouch:true,
                preventDefaultSwipeY:true,
				
				nextText:'&#xf054;',
				prevText:'&#xf053;',

                auto:true

            });
        });
    </script>	";
	
	
	
	
	
	return $output;
}
add_shortcode( 'aione-vcarousel', 'aione_vcarousel_shortcode' );

?>