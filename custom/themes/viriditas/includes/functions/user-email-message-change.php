<?php
/**
 * Modify the message sent to a user after being approved.
 * 
 * @param $message The default message.
 * @param $user The user who will receive the message.
 * @return string the updated message.
 */
function my_custom_message( $message, $user ) {
    $message = 'Custom message here';
    
    return $message;
}
// add a new custom approval message
add_filter( 'new_user_approve_approve_user_message', 'my_custom_message', 10, 2 );

// add a new custom denial message
add_filter( 'new_user_approve_deny_user_message', 'my_custom_message', 10, 2 );

?>