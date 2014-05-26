<?php

/**
* Use style.css for tinymce
*/

function my_theme_add_editor_styles() {
    add_editor_style( 'style.css' );
}

add_action( 'init', 'my_theme_add_editor_styles' );
?>