<?php
// Home URL Shortcode
function home_url_shortcode( $attr, $content = null ) {

	return home_url();
}

add_shortcode( 'home_url', 'home_url_shortcode' );


// URL Shortcode
function url_shortcode( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'type' => '',
			'id' => '0',
			'path' => '',
			'title' => '',
			'action' => '',
			'class' => '',
		), $atts )
	);
	
	if(!$id && !$path && !$title && !$action){
		return home_url();
	} else {
		$page_id = 0;
		if( $id && is_numeric ($id) ){
			$page_id = $id;
		}
		
		if($path != ''){
			$page_id = get_page_by_path($path);
		}
		
		if($title != ''){
			$page_id = get_page_by_title($title);
		}
		
		if($action != ''){
			if($action == 'logout'){
				return wp_logout_url();
			}	
		}
		
		if ($page_id) {
			return get_page_link($page_id);
		} else {
			return null;
		}	
	}
}
add_shortcode( 'url', 'url_shortcode' );

?>