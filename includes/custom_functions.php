<?php

function aione_custom_content_append_filter($content) {
    
	$append = '<div class="epedemic-page" style="text-align:center;">Additional information about data sources can be found on our <a href="'.do_shortcode('[url id="1042"]').'">Data Methods page</a></div>';	
	$measures = get_page_by_title( 'Measures' );
	$visualizations = get_page_by_title( 'Visualizations' );
	
	
	global $post; 	
	$anc = get_post_ancestors( $post->ID );
	foreach($anc as $ancestor) {
		if(is_page() && ($ancestor == $measures->ID || $ancestor == $visualizations->ID )) {
			$content = $content . " " . $append;	
		}
	}
	if($post->ID == $visualizations->ID){
		$content = $content . " " . $append;
	}
	
	/*
	if(is_page($page->ID)) {
		$append = ' TESTING Filter';
		$content = $content . " " . $append;	
	}
	*/
    return $content;
}

add_filter('the_content', 'aione_custom_content_append_filter');
	
	
?>