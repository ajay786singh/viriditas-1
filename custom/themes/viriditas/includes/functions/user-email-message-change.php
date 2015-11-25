<?php
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
?>