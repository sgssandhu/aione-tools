<?php

//  Shortcode
function aione_register_shortcode( $atts ) {

    // Attributes
    extract( shortcode_atts(
            array(
            ), $atts )
    );
	$output = "";
	
	// only show the registration form to non-logged-in members
    if(!is_user_logged_in()) {

        global $aione_load_css;

        // set this to true so the CSS is loaded
        $aione_load_css = true;

        // check to make sure user registration is enabled
        $registration_enabled = get_option('users_can_register');

        // only show the registration form if allowed
        if($registration_enabled) {
			
			$errors = array();
			// load from post
			if( isset($_POST['action']) && $_POST['action'] == 'add_new' && !empty($_POST['fields'])){	
				
				$user_login		= $_POST["aione_user_login"];
				$user_email		= $_POST["aione_user_email"];
				$user_pass		= $_POST["aione_user_pass"];
				$pass_confirm 	= $_POST["aione_user_pass_confirm"];
				
				// this is required for username checks
				require_once(ABSPATH . WPINC . '/registration.php');
				
				if($user_email == '') {
					//empty email
					$errors[] = 'Please enter email address';
				} else {
					if(!is_email($user_email)) {
						//invalid email
						$errors[] = 'Invalid email';
					}
					if(email_exists($user_email)) {
						//Email address already registered
						$errors[] = 'Email already registered';
					}
				}
			
				if($user_login == '') {
					// empty username
				   $errors[] = 'Please enter a username';
				} else {
					if(!validate_username($user_login)) {
						// invalid username
						$errors[] = 'Invalid username';
					}

					if(username_exists($user_login)) {
						// Username already registered
						$errors[] = 'Username already taken';
					}				
				}

				if($user_pass == '') {
					//Empty password
					$errors[] = 'Please enter a password';
				}

				if($user_pass != $pass_confirm) {
					// passwords do not match
					$errors[] = 'Passwords do not match';
				}
				if(!empty($user_first) && !preg_match('/^[a-zA-Z\s]+$/', $user_first) ) {
					//Invalid Mobile
					$errors[] = 'Invalid first name. Numbers not allowed.';
				}
				
				if(!empty($user_last) && !preg_match('/^[a-zA-Z\s]+$/', $user_last) ) {
					//Invalid Mobile
					$errors[] = 'Invalid last name. Numbers not allowed.';
				}
			
				// only create the user in if there are no errors
				if(empty($errors)) {
					$user_role = get_option('default_role');
					$new_user_id = wp_insert_user(array(
							'user_login'		=> $user_login,
							'user_pass'	 		=> $user_pass,
							'user_email'		=> $user_email,
							'first_name'		=> $user_first,
							'last_name'			=> $user_last,
							'user_registered'		=> date('Y-m-d H:i:s'),
							'role'			=> $user_role
						)
					);
					if(is_int($new_user_id)) {
						
						// loop through and save $_POST data
						foreach( $_POST['fields'] as $k => $v ){
							// get field
							$f = apply_filters('acf/load_field', false, $k );
							// update field
							do_action('acf/update_value', $v, $post_id, $f );
						}
						// foreach
						
						$output .= 'You have been successfully registered.';
				
						//wp_redirect($redirect_url);
						/*
						$output .= '<script type="text/javascript">
						<!--
						   window.location="'.$redirect_url.'";
						//-->
						</script>';
						*/
					} else {
						$errors[] = 'Some error occurred. Please contact Administrator.';
					}
				} else {
					$output .= aione_show_errors($errors);
				}
			}
            $output .= aione_user_registration_form();
        } else {
            $output .= __('User registration is not enabled');
        }
    } else {
			$output .= __('You are already logged in');
	}

	
	return $output;
}
add_shortcode( 'aione-register', 'aione_register_shortcode' );


function aione_user_registration_form( ) {
    $html_before_fields = '<form id="aione-registration-form" class="aione-form register form acf-form" action="'.get_permalink().'" method="post">
		<div class="postbox acf_postbox no_box">
		
		<div class="aione_form_field field field_type-text">
			<p class="label"><label for="aione_user_login">Enter Username<span class="required">*</span></label></p>
			<div class="acf-input-wrap"><input name="aione_user_login" id="aione_user_login" class="textbox large required" type="text" placeholder="Username" value=""/></div>
		</div>
		<div class="aione_form_field field field_type-text">
			<p class="label"><label for="aione_user_email">Your Email Address<span class="required">*</span></label></p>
			<div class="acf-input-wrap"><input name="aione_user_email" id="aione_user_email" class="textbox large required" type="email" placeholder="Your Email Address" value=""/></div>
		</div>

		<div class="aione_form_field field field_type-text">
			<p class="label"><label for="password">Enter Password<span class="required">*</span></label></p>
			<div class="acf-input-wrap"><input name="aione_user_pass" id="password" class="textbox large required" type="password"/></div>
		</div>

		<div class="aione_form_field field field_type-text">
			<p class="label"><label for="password_again">Enter Password Again<span class="required">*</span></label></p>
			<div class="acf-input-wrap"><input name="aione_user_pass_confirm" id="password_again" class="textbox large required" type="password"/></div>
		</div>
		</div>
	
	';
	$html_after_fields = '<div class="field">
		<input type="hidden" name="action" value="add_new">
		<input type="submit" value="Submit">
	</div>
	';
	
	$field_groups = get_option('aione_registration_custom_field_groups');
	if(!is_array($field_groups)){
		$field_groups = array($field_groups);
	}
	$options = array(
		'post_id'		=> 'new_post',
		'form' => false,
		'field_groups' => $field_groups,
		'post_title'	=> false,
		'post_content'	=> false,
		'html_before_fields' => $html_before_fields,
		'html_after_fields' => $html_after_fields,
        'instruction_placement' => 'field',
        'submit_value'	=> 'Submit',
        'updated_message'	=> 'Registered Successfully',
    );

    ob_start();
    acf_form($options);
	$output .= ob_get_contents();
	ob_end_clean();
	
	return $output;
}


?>