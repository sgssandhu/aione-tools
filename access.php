<?php

/* Add the [is_user_logged_in] shortcode. */
add_shortcode( 'is_user_logged_in', 'aione_is_user_logged_in_shortcode' );
function aione_is_user_logged_in_shortcode( $attr, $content = null ) {

	/* If it is a feed or the user is not logged in, return nothing. */
	if ( is_feed() || !is_user_logged_in() || is_null( $content ) )
		return '';

	/* Return the content. */
	return do_shortcode( $content );
}

/* Add the [user_not_logged_in] shortcode. */
add_shortcode( 'user_not_logged_in', 'aione_user_not_logged_in_shortcode' );
function aione_user_not_logged_in_shortcode( $attr, $content = null ) {

	/* If it is a feed or the user is logged in, return nothing. */
	if ( is_user_logged_in() ){	
		return '';
	}
	/* Return the content. */
	return do_shortcode( $content );
}

add_shortcode( 'user_not_logged_in_error', 'aione_user_not_logged_in_error_shortcode' );
function aione_user_not_logged_in_error_shortcode( $attr, $content = null ) {

	/* If it is a feed or the user is logged in, return nothing. */
	if ( is_user_logged_in() ){	
		return '';
	}
	$content = '[fullwidth backgroundcolor="" backgroundimage="" backgroundrepeat="no-repeat" backgroundposition="left top" backgroundattachment="scroll" bordersize="1px" bordercolor="" borderstyle="solid" paddingtop="15px" paddingbottom="15px" paddingleft="15px" paddingright="15px" menu_anchor="" class="border" id=""][title size="3" content_align="center" style_type="underline" sep_color="#ee7214" class="themecolor2 " id=""] Not Authorised[/title][title size="4" content_align="center" style_type="none" sep_color="" class="" id=""] You are not authorized to access this page[/title][/fullwidth]';

	/* Return the content. */
	return do_shortcode($content);
}


/* Add the [access] shortcode. */
add_shortcode( 'access', 'aione_access_check_shortcode' );
function aione_access_check_shortcode( $attr, $content = null ) {

	/* Set up the default attributes. */
	$defaults = array(
		'capability' => '',	// Single capability or comma-separated multiple capabilities
		'role' => '',	// Single role or comma-separated multiple roles
	);

	/* Merge the input attributes and the defaults. */
	extract( shortcode_atts( $defaults, $attr ) );

	/* If there's no content or if viewing a feed, return an empty string. */
	if ( is_null( $content ) || is_feed() )
		return '';

	/* If the current user has the capability, show the content. */
	if ( !empty( $capability ) ) {

		/* Get the capabilities. */
		$caps = explode( ',', $capability );

		/* Loop through each capability. */
		foreach ( $caps as $cap ) {

			/* If the current user can perform the capability, return the content. */
			if ( current_user_can( trim( $cap ) ) )
				return do_shortcode( $content );
		}
	}

	/* If the current user has the role, show the content. */
	if ( !empty( $role ) ) {

		/* Get the roles. */
		$roles = explode( ',', $role );

		/* Loop through each of the roles. */
		foreach ( $roles as $role ) {

			/* If the current user has the role, return the content. */
			if ( current_user_can( trim( $role ) ) )
				return do_shortcode( $content );
		}
	}

	/* Return an empty string if we've made it to this point. */
	return '';
}



?>