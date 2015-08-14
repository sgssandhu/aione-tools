<?php


// Change Password Shortcode

function aione_change_password_shortcode( $atts ) {

    // Attributes
    extract( shortcode_atts(
            array(
            ), $atts )
    );

    $output = "";
    $errors = array();

    $current_user = wp_get_current_user();

    require_once( ABSPATH . WPINC . '/registration.php' );
    if ( !empty($_POST) && !empty( $_POST['action'] ) && $_POST['action'] == 'changepassword' ) {
        /* Update user password */
        if ( !empty($_POST['current_pass']) && !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
            if ( !wp_check_password( $_POST['current_pass'], $current_user->user_pass, $current_user->ID) ) {
                $errors[] = 'Your current password does not match. Please retry.';
            } elseif ( $_POST['pass1'] != $_POST['pass2'] ) {
                $errors[] = 'The passwords do not match. Please retry.';
            } elseif ( strlen($_POST['pass1']) < 5 ) {
                $errors[] = 'New Password is too small. Minimum five characters required.';
            } elseif ( false !== strpos( wp_unslash($_POST['pass1']), "\\" ) ) {
                $errors[] = 'Password may not contain the character "\\" (backslash).';
            } else {
                $update_error = wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
                if ( !is_int($update_error) ) {
                    $errors[] = 'An error occurred while updating your profile. Please retry.';
                } 
            }
            if ( empty($errors) ) {
                $output .= '<div class="aione-alert alert success alert-dismissable alert-success alert-shadow">';
                $output .= '<button aria-hidden="true" data-dismiss="alert" class="close toggle-alert" type="button">×</button>';
                $output .= '<span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>';
                $output .= 'Password Changed successfully!';
                $output .= '</div>';

                $output .= '<div class="aione-alert alert general alert-dismissable alert-info alert-shadow">';
                $output .= 'You will be redirected to login page in <span id="redirectcountdown">10</span> seconds.';
                $output .= '</div>';

               $output .= "<script type='text/javascript'>jQuery(window).ready( function() { var time = 10; setInterval( function() { time--; if (time >= 0){ jQuery('#redirectcountdown').html(time);} if (time === 0) { window.location = '".home_url( '/' )."';} }, 1000 );});</script>";



            }
        } else {
        	if ( empty($_POST['current_pass']) ) {
        		$errors[] = 'Current Password can not be empty';
        	}
        	if ( empty($_POST['pass1'] ) ) {
        		$errors[] = 'New Password can not be empty';
        	}
        	if ( empty( $_POST['pass2'] ) ) {
        		$errors[] = 'Confirm Password can not be empty';
        	}
    	}
    } 

    if ( !empty($errors) ) {
        $output .= '<div class="aione_errors"><ul>';
        foreach($errors as $error){
            $output .= '<li class="error"><strong>' . __('Error') . '</strong>: ' . $error . '</li>';
        }
        $output .= '</ul></div>';
    }

    $output .= '<form method="post" class="login-signup" id="login-signup" action="">';
    $output .= '<ul class="form-style-1">';
    $output .= '<li>';
    $output .= '<label for="current_pass">Current Password <span class="required">*</span></label>';
    $output .= '<input class="text-input field-long" name="current_pass" type="password" id="current_pass">';
    $output .= '</li>';
    $output .= '<li>';
    $output .= '<label for="pass1">New Password <span class="required">*</span></label>';
    $output .= '<input class="text-input field-long" name="pass1" type="password" id="pass1">';
    $output .= '</li>';
    $output .= '<li>';
    $output .= '<label for="pass2">Confirm Password <span class="required">*</span></label>';
    $output .= '<input class="text-input field-long" name="pass2" type="password" id="pass2">';
    $output .= '</li>';
    $output .= '<li>';
    $output .= '<input name="updateuser" type="submit" id="updateuser" class="field-long submit btn button-primary button application-button" value="Change Password">';
    $output .= '<input name="action" type="hidden" id="action" value="changepassword">';
    $output .= '</li>';
    $output .= '</ul>';
    $output .= '</form>';

    return $output;
}
add_shortcode( 'aione-change-password', 'aione_change_password_shortcode' );


?>