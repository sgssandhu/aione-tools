<?php


// Login Link Shortcode
function aione_login_link_shortcode( $atts ) {
    // Attributes
    extract( shortcode_atts(
            array(
            	'class'           => '',
				'text'           => 'Login'
            ), $atts )
    );
    $output = "";

    if ( !is_user_logged_in() ) {
        $output .= '<div id="login_link" class="user-links login-link '.$class.'">';
        $output .= '<a href="'.wp_login_url().'" title="' . $text . '">' . $text . '</a>';
        $output .= '</div>';
    } 
	return $output;
}
add_shortcode( 'aione-login-link', 'aione_login_link_shortcode' );



// Register Link Shortcode
function aione_register_link_shortcode( $atts ) {
    // Attributes
    extract( shortcode_atts(
            array(
            	'class'           => '',
				'text'           => 'Sign up'
            ), $atts )
    );
    $output = "";
    
    if ( !is_user_logged_in() ) {
        $output .= '<div id="login_link" class="user-links login-link '.$class.'">';
        $output .= '<a href="'.wp_registration_url().'" title="' . $text . '">' . $text . '</a>';
        $output .= '</div>';
    } 
	return $output;
}
add_shortcode( 'aione-register-link', 'aione_register_link_shortcode' );

// Logout Link Shortcode
function aione_logout_link_shortcode( $atts ) {
    // Attributes
    extract( shortcode_atts(
            array(
            	'class'           => '',
				'text'           => 'Logout'
            ), $atts )
    );
    $output = "";
    
    if ( is_user_logged_in() ) {
        $output .= '<div id="logout_link" class="user-links logout-link '.$class.'">';
        $output .= '<a href="'.wp_logout_url().'" title="' . $text . '">' . $text . '</a>';
        $output .= '</div>';
    } 
	return $output;
}
add_shortcode( 'aione-logout-link', 'aione_logout_link_shortcode' );


// Logout Link Shortcode
function aione_user_welcome_shortcode( $atts ) {
    // Attributes
    extract( shortcode_atts(
            array(
            	'class'           => '',
				'text'           => 'Welcome'
            ), $atts )
    );
    $output = "";
    
    if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		
		if( $current_user->display_name != ""){
			$user_welcome = $current_user->display_name;
		} elseif($current_user->user_firstname != '' && $current_user->user_lastname != ''){
			$user_welcome = $current_user->user_firstname . ' ' . $current_user->user_lastname;
		} else {
			$user_welcome = $current_user->user_login;
		}
		
        $output .= '<div id="user_welcome" class="user-links user-welcome '.$class.'">';
        $output .= $text . ' <a href="' . get_edit_user_link( $current_user->ID ) . '" title="' . $text . ' ' . $user_welcome . '">' . $user_welcome . '</a>';
        $output .= '</div>';
    } 
	return $output;
}
add_shortcode( 'aione-user-welcome', 'aione_user_welcome_shortcode' );


// Logout Link Shortcode
function aione_login_form_shortcode( $attr, $content = null ) {
	// Attributes
    extract( shortcode_atts(
		array(
		'echo'           => false,
		'redirect'       => get_option('admin_login_redirect_page'), 
		'form_id'        => 'loginform',
		'label_username' => __( 'Username' ),
		'label_password' => __( 'Password' ),
		'label_remember' => __( 'Remember Me' ),
		'label_log_in'   => __( 'Login' ),
		'id_username'    => 'user_login',
		'id_password'    => 'user_pass',
		'id_remember'    => 'rememberme',
		'id_submit'      => 'wp-submit',
		), $atts )
    );
    $output = "";
	
	$login = $_GET['login'];
	$errors = array();
	if(isset($login) && $login == 'failed' ){
		$errors[] = 'Invalid username or password';
		$output .= aione_show_errors($errors);
	}
	
	//print_r($_POST);
	//print_r($_GET);
	
	
	$args = array(
		'echo'           => $echo,
		'redirect'       => $redirect, 
		'form_id'        => $form_id,
		'label_username' => $label_username,
		'label_password' => $label_password,
		'label_remember' => $label_remember,
		'label_log_in'   => $label_log_in,
		'id_username'    => $id_username,
		'id_password'    => $id_password,
		'id_remember'    => $id_remember,
		'id_submit'      => $id_submit,
		'remember'	 => empty( $instance['remember'] ) ? true : false,
		'value_username' => esc_attr( $instance['value_username'] ),
		'value_remember' => !empty( $instance['value_remember'] ) ? true : false,
	);
	
	$output .= wp_login_form( $args );

	return $output;
}
add_shortcode( 'aione-login-form', 'aione_login_form_shortcode' );

//
add_action( 'wp_login_failed', 'aione_login_fail_redirect' );  // hook failed login
function aione_login_fail_redirect( $username ) {
   $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
   //$post = serialize($_POST);
   // if there's a valid referrer, and it's not the default log-in screen
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
      wp_redirect( $referrer . '?login=failed' );  // let's append some information (login=failed) to the URL for the theme to use
      exit;
   }
}

?>