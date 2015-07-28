<?php
function form_login() {
	$logo=get_bloginfo('template_url')."/dist/images/logo.png";
	$register=get_bloginfo('url')."/register";
	$html='<div class="login-box">';
	$html.='<form id="login-form" action="login" method="post">';
	$html.='<img src="'.$logo.'">';
	$html.='<h5>Login to order <br> Herbal Products</h5>';
	$html.='<div class="status"></div>';
	//$html.='<label for="username">Username</label>';
	$html.='<input id="username" type="text" name="username" placeholder="Username" />';
	//$html.='<label for="password">Password</label>';
	$html.='<input id="password" type="password" name="password" placeholder="Password" />';
	$html.='<input class="submit_button" type="submit" value="Login" name="submit" />';
	$html.= wp_nonce_field( 'ajax-login-nonce', 'security' );
	$html.='</form>';
	$html.='<a class="lost" href="">Forgot your password?</a>';
	$html.='<a class="register" href="'.$register.'">Register New?</a>';
	$html.='</div>';
	echo $html;
}
// Execute the action only if the user isn't logged in
add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
add_action( 'wp_ajax_ajaxlogin', 'ajax_login' );
function ajax_login(){
    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-login-nonce', 'security' );

    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;
	
    $user_signon = wp_signon( $info, false );
	if ( is_wp_error($user_signon) ){
		echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
	} else {
		echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
	}
    die();
}
?>