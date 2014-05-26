<?php

/**
* Add numbered pagination to blog or archive list 
* @example <?php pagination(); ?>
* Must have in query: $paged  = (get_query_var('paged')) ? get_query_var('paged') : 1;
*/

function pagination() {
    global $wp_query;
    $big = 999999999; // need an unlikely integer

    echo paginate_links( array(
    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
    'format' => '?paged=%#%',
    'current' => max( 1, get_query_var('paged') ),
    'total' => $wp_query->max_num_pages
    ) );
}

/**
* Add class to next and previous post_link
* @example <div id="pagination"><?php previous_post_link( '%link', '%title' );?><?php next_post_link( '%link', '%title' );?></div>
*/

function add_class_next_post_link($html){
    $html = str_replace('<a','<a class="older"',$html);
    return $html;
}
add_filter('next_post_link','add_class_next_post_link',10,1);
function add_class_previous_post_link($html){
    $html = str_replace('<a','<a class="newer"',$html);
    return $html;
}

add_filter('previous_post_link','add_class_previous_post_link',10,1);
?>