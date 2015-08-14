<?php
/*
Plugin Name: Aione Tools
Plugin URI: http://oxosolutions.com/
Description: Aione Tools
Version: 1.0.0
Author: OXO Solutions
Author URI: http://oxosolutions.com/
*/

if (file_exists(dirname( __FILE__ ) .'/includes/functions.php')){
    require_once( dirname( __FILE__ ) .'/includes/functions.php' );
}

if (file_exists(dirname( __FILE__ ) .'/includes/custom_functions.php')){
    require_once( dirname( __FILE__ ) .'/includes/custom_functions.php' );
}

if (file_exists(dirname( __FILE__ ) .'/general.php')){
    require_once( dirname( __FILE__ ) .'/general.php' );
}

if (file_exists(dirname( __FILE__ ) .'/access.php')){
    require_once( dirname( __FILE__ ) .'/access.php' );
}

if (file_exists(dirname( __FILE__ ) .'/posts.php')){
    require_once( dirname( __FILE__ ) .'/posts.php' );
}

if (file_exists(dirname( __FILE__ ) .'/vcarousel.php')){
    require_once( dirname( __FILE__ ) .'/vcarousel.php' );
}

if (file_exists(dirname( __FILE__ ) .'/login.php')){
    require_once( dirname( __FILE__ ) .'/login.php' );
}

if (file_exists(dirname( __FILE__ ) .'/register.php')){
    require_once( dirname( __FILE__ ) .'/register.php' );
}

if (file_exists(dirname( __FILE__ ) .'/activate.php')){
    require_once( dirname( __FILE__ ) .'/activate.php' );
}

if (file_exists(dirname( __FILE__ ) .'/reset-password.php')){
   require_once( dirname( __FILE__ ) .'/reset-password.php' );
}

if (file_exists(dirname( __FILE__ ) .'/profile.php')){
    require_once( dirname( __FILE__ ) .'/profile.php' );
}



add_action( 'admin_menu', 'aione_page_target_menu' );
function aione_page_target_menu() {
	add_menu_page( 'Aione Tools ', 'Aione Tools', 'read', 'aione-tools', 'aione_tools_output', 'dashicons-arrow-right-alt', 29 );
	//add_submenu_page( 'settings.php', 'Aione Tools ', 'Tools', 'manage_options', 'aione-tools', 'aione_tools_output');
	
}




	function plugin_options_page() {
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_settings_key;
		?>
		<div class="wrap">
			<?php $this->plugin_options_tabs(); ?>
			<form method="post" action="options.php">
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( $tab ); ?>
				<?php do_settings_sections( $tab ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
	
	/*
	 * Renders our tabs in the plugin options page,
	 * walks through the object's tabs array and prints
	 * them one by one. Provides the heading for the
	 * plugin_options_page method.
	 */
	function plugin_options_tabs() {
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $general_settings_key;
		
		$plugin_settings_tabs = array('Special Pages', 'Login', 'Register');

		//screen_icon();
		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=' . $plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
		}
		echo '</h2>';
	}








//change default Login url
add_filter( 'login_url', 'aione_login_url', 10, 2 );
function aione_login_url( $login_url, $redirect ) {
	
    $aione_login_page = get_option('aione_login_page');
	if(isset($aione_login_page)){
		return get_permalink($aione_login_page);
	}	
}

//change default Register url
add_filter( 'register_url', 'aione_register_url' );
function aione_register_url( $register_url ) {
   $aione_register_page = get_option('aione_register_page');
	if(isset($aione_register_page)){
		return get_permalink($aione_register_page);
	}
}

//change default lost password url
add_filter( 'lostpassword_url',  'aione_lostpassword_url', 10, 0 );
function aione_lostpassword_url() {
	$aione_forgot_password_page = get_option('aione_forgot_password_page');
	if(isset($aione_forgot_password_page)){
		return get_permalink($aione_forgot_password_page);
	}
}

//change default redirect url after Login
add_filter("login_redirect", "admin_login_redirect", 10, 3);
function admin_login_redirect( $redirect_to, $request, $user ){
	
	$admin_login_redirect_page = get_option('admin_login_redirect_page');
	if(isset($admin_login_redirect_page)){
		return get_permalink($admin_login_redirect_page);
	}
}

add_action('wp_logout','go_home');
function go_home(){
	$logout_redirect_page = get_option('logout_redirect_page');
	if(isset($logout_redirect_page)){
		wp_redirect( get_permalink($logout_redirect_page) );
		exit();
	}
}


add_action("init", "bsb_register_redirect") ;
function bsb_register_redirect() {
	if(strpos($_SERVER['REQUEST_URI'], 'wp-login.php?action=lostpassword')){
		$aione_forgot_password_page = get_option('aione_forgot_password_page');
		if(isset($aione_forgot_password_page)){
			echo "<script>";
			echo 'window.location.assign("'.get_permalink($aione_forgot_password_page).'");';
			echo "</script>";
		}
	}
	if(strpos($_SERVER['REQUEST_URI'], 'wp-login.php?action=register')){
		$aione_register_page = get_option('aione_register_page');
		if(isset($aione_register_page)){
			echo "<script>";
			echo 'window.location.assign("'.get_permalink($aione_register_page).'");';
			echo "</script>";
		}
	}
}



function aione_tools_output(){

	if ( isset( $_POST['action'] ) && $_POST['action'] == "save" ){
		$validation_key = $_POST['save_settings'];
		if ( ! isset( $validation_key )  || ! wp_verify_nonce( $validation_key, 'validation_key' ) ) {
			echo "Access denied.";
			exit;

		} else {
		
			$aione_login_page = $_POST['aione_login_page'];
			$aione_register_page = $_POST['aione_register_page'];
			$aione_forgot_password_page = $_POST['aione_forgot_password_page'];
			$admin_login_redirect_page = $_POST['admin_login_redirect_page'];
			$logout_redirect_page = $_POST['logout_redirect_page'];
			
			$aione_registration_custom_field_groups = $_POST['aione_registration_custom_field_groups'];
			
			//echo "</br>Login page id : ".$aione_login_page;
			//echo "</br>Register page id : ".$aione_register_page;
			//echo "</br>Forgot Password page id : ".$aione_forgot_password_page;
			
			update_option( 'aione_login_page', $aione_login_page );
			update_option( 'aione_register_page', $aione_register_page );
			update_option( 'aione_forgot_password_page', $aione_forgot_password_page );
			update_option( 'admin_login_redirect_page', $admin_login_redirect_page );
			update_option( 'logout_redirect_page', $logout_redirect_page );
			
			update_option( 'aione_registration_custom_field_groups', $aione_registration_custom_field_groups );
		}
	}

	
	?>
	<div id="aione_page_target">
	<?php plugin_options_tabs(); ?>
		<div class="wrap welcome-panel">
			
			
			<h1>Special Pages</h1>
			
			<form action="" method="post">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="aione_login_page">Login Page</label></th>
						<td><?php wp_dropdown_pages(array('name' => 'aione_login_page','show_option_none' => 'Select Login Page','id' => 'aione_login_page','selected'=> get_option('aione_login_page'))); ?></td>
					</tr>
					<tr>
						<th scope="row"><label for="aione_register_page">Register Page</label></th>
						<td><?php wp_dropdown_pages(array('name' => 'aione_register_page','show_option_none' => 'Select Register Page','id' => 'aione_register_page','selected'=> get_option('aione_register_page'))); ?></td>
					</tr>
					<tr>
						<th scope="row"><label for="aione_forgot_password_page">Forgot Password Page</label></th>
						<td><?php wp_dropdown_pages(array('name' => 'aione_forgot_password_page','show_option_none' => 'Select Forgot Password Page','id' => 'aione_forgot_password_page','selected'=> get_option('aione_forgot_password_page'))); ?></td>
					</tr>
					<tr>
						<th scope="row"><label for="admin_login_redirect_page">Login Redirect Page</label></th>
						<td><?php wp_dropdown_pages(array('name' => 'admin_login_redirect_page','show_option_none' => 'Select Login Redirect Page','id' => 'admin_login_redirect_page','selected'=> get_option('admin_login_redirect_page'))); ?></td>
					</tr>
					<tr>
						<th scope="row"><label for="logout_redirect_page">Logout Redirect Page</label></th>
						<td><?php wp_dropdown_pages(array('name' => 'logout_redirect_page','show_option_none' => 'Select Logout Redirect Page','id' => 'logout_redirect_page','selected'=> get_option('logout_redirect_page'))); ?></td>
					</tr>
				</tbody>
			</table>
			<h2>Register Settings</h2>
			
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="aione_login_page">Registration Custom Fields Groups( ACF )</label></th>
						<td><?php wp_dropdown_pages(array('name' => 'aione_registration_custom_field_groups','show_option_none' => 'Select Custom Fields Groups','id' => 'aione_registration_custom_field_groups','post_type' => 'acf', 'selected'=> get_option('aione_registration_custom_field_groups'))); ?></td>
					</tr>
				</tbody>
			</table>
	
			<p class="submit">
				<?php wp_nonce_field( 'validation_key', 'save_settings' ); ?>
				<input type="hidden" name="action" value="save" />
				<input type="submit" name="submit" id="submit" class="button button-primary " value="Save Settings">
			</p>
			</form>
		</div>	
		
	</div>
	
	<style>
	h2.nav-tab-wrapper, h3.nav-tab-wrapper{
		  margin-bottom: 0;
		  margin-top: 25px;
		  padding-left: 0;
		  width: 98.1%;
		  border-bottom: 1px solid #1570a6;
	}
	h2 .nav-tab{
		padding: 6px 20px;
	}
	.nav-tab{
		border: 1px solid #1570a6;
		margin: 0px 6px -1px 0;
		background: #138dc5;
		color: #ffffff;
	}
	.nav-tab:hover {
		border: 1px solid #e5e5e5;
		border-bottom: 1px solid #FFFFFF;
	}
  
	.nav-tab-active, .nav-tab-active:hover {
	  border: 1px solid #e5e5e5;
	  border-bottom: 1px solid #FFFFFF;
	  background: #FFFFFF;
	  color:#666666;
	}
	#aione_page_target .welcome-panel {
		padding: 20px 1.5% 30px 1.5%;
		width: 95%;
		float: left;
		margin-top: 0px;
		border-top:none;
	}
	#aione_page_target .welcome-panel.right {
		margin-left:1.6%;
	}
	#aione_page_target .welcome-panel h1{
		font-weight: 100;
		border-bottom: 1px solid #b8b8b8;
		margin: 0 0 10px 0;
		padding: 0 0 10px 0;
		text-align:center;
	}
	#aione_page_target .welcome-panel p{
		text-align:center;
	}
	</style>

<?php
}
?>