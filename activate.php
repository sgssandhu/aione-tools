<?php
// Add Shortcode
function aione_activate_user_shortcode( $atts ) {

    // Attributes
    extract( shortcode_atts(
            array(
            ), $atts )
    );
    $output = '';
	/*
    $registered = $_REQUEST['registered'];
    $success = $_REQUEST['success'];
    $notification = $_REQUEST['notification'];

    $action = $_REQUEST['action'];



	if(isset($registered) && isset($success) && isset($notification)){
		if($success){
			$output .= '<div class="aione-alert alert success alert-success alert-shadow">';
			$output .= '<span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>';
			$output .= 'Your account has been registered successfully. ';
			$output .= '</div>';
		}
		if($registered == 'email'){
			if(!$notification){
				$output .= '<p>Unable to send activation email. Please contact administrator</p>';
			} else{
				$output .= '<p>Activation email has been sent to your registered email ID. Please check spam folder . Sometimes It may take few minutes.</p>';
			}
		}
		if($registered == 'mobile'){
			if(!$notification){
				$output .= 'Unable to send OTP(One Time Password) to your mobile number. Please contact administrator';
			} else{
				$output .= 'OTP(One Time Password) has ben sent to your registered mobile number.';
			}
		}

	}
	*/
	$action = $_REQUEST['action'];
	if(isset($action) && $action=='activate'){


		// this is required for username checks
		require_once(ABSPATH . WPINC . '/registration.php');

		$user_email = $_REQUEST['email'];
		$user_key = $_REQUEST['key'];

		//echo '</br>'.$registered;
		//echo '</br>'.$user_email;
		//echo '</br>'.$user_mobile;
		//echo '</br>'.$user_key;

		$errors = '';


		if(empty($user_email)) {
			$errors .= '<li class="error">Please enter email address.</li>';
		} else{
			if(!email_exists($user_email)) {
				$errors .= '<li class="error">Email address does not exist.</li>';
			}
		}



		if(empty($user_key)) {
			$errors .= '<li class="error">Please enter activation key.</li>';
		}

		if(empty( $errors )) {


			$user = get_user_by( 'email', $user_email );
			$is_user_active = get_user_meta($user->ID,'wp-approve-user',true);
			if($is_user_active){
				$errors .= '<li class="error">User already activated.</li>';
			}elseif( $user_key != $user->user_activation_key ){
				$errors .= '<li class="error">Invalid activated key.</li>';
			}else {
				$user_activated = update_user_meta($user->ID,"wp-approve-user",'1');
			}



			if($user_activated){
				$output .= '<div class="aione-alert alert success alert-success alert-shadow">';
				$output .= '<span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>';
				$output .= 'Your account successfully activated. Please login with your username and password. ';
				$output .= '</div>';
			}
		}
		if($errors != ''){
			$output .= '<div class="aione_errors"><ul>';
			$output .= $errors;
			$output .= '</ul></div>';
		}
	}

	$output .= '<form method="post" class="form aione-form" id="activate-user" action="'.get_permalink().'">
		<div class="form-field aione-form-field text-field">
			<p class="label"><label for="user_email">Your Email Address<span class="required">*</span></label></p>
			<div class="acf-input-wrap"><input class="text-input field-long" name="email" type="text" placeholder="Your Email Address" id="user_email"></div>
		</div>
		<div class="form-field aione-form-field text-field">
			<p class="label"><label for="user_key">Activation Key <span class="required">*</span></label></p>
			<div class="acf-input-wrap"><input class="text-input field-long" name="key" type="text" placeholder="Your Activation Key" id="user_key"></div>
		</div>
		<div class="form-field aione-form-field text-field">
		<input name="activate" type="submit" id="activate" class="field-long submit btn button-primary button application-button" value="Activate">
		<input name="action" type="hidden" value="activate">
		</div>
		</form>
	';
    return $output;
}
add_shortcode( 'aione-activate', 'aione_activate_user_shortcode' );

?>