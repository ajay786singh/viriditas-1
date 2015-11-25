<?php
/**
 * Modify the message sent to a user after being approved.
 * 
 * @param $message The default message.
 * @param $user The user who will receive the message.
 * @return string the updated message.
 */
 
function user_email_headers( $headers ) {
	$admin_email = get_option( 'admin_email' );
	if ( empty( $admin_email ) ) {
		$admin_email = 'support@' . $_SERVER['SERVER_NAME'];
	}
	$from_name = get_option( 'blogname' );
	$headers = "From: \"{$from_name}\" <{$admin_email}>\n\r\n";
	$headers.= "MIME-Version: 1.0\r\n";
	$headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    
    return $headers;
}

add_filter( 'new_user_approve_email_header', 'user_email_headers', 10, 2 );
function user_deny_message( $message, $user ) {
	$user_login = ucfirst(stripslashes( $user->data->user_login ));
    
	$message= "<p>Hello, {$user_login}</p>";
	$message.= "<p>We welcome you to Viriditas and are glad that you have tried to register with us.</p>";
	$message.= "<p>At this time, your application has been denied because you may not have supplied one of the following requirements.</p>";
	$message.= "<ol>";
	$message.= "<li>What is your health practitioner title?</li>";
	$message.= "<li>What educational institute did you attend?</li>";
	$message.= "<li>What is your license number?</li>";
	$message.= "</ol>";
	$message.= "<p>To re-submit your application go to: https://viriditasherbalproducts.com/register/</p>";
	$message.= "</p>If you have any questions, you may contact us at: info@viriditasherbalproducts.com</p>";
    $message.= "<br /><br />";
	$message.= "Best Regards, <br />";
	$message.= "Viriditas";
    return $message;
}
// add a new custom denial message
add_filter( 'new_user_approve_deny_user_message', 'user_deny_message', 10, 2 );

// add a new custom approval message
//add_filter( 'new_user_approve_approve_user_message', 'user_deny_message', 10, 2 );
?>