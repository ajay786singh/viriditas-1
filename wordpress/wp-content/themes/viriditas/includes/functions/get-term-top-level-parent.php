<?php
// Returns top level term id

function get_term_top_most_parent($term_id, $taxonomy){
    $parent  = get_term_by( 'id', $term_id, $taxonomy);
    while ($parent->parent != 0){
        $parent  = get_term_by( 'id', $parent->parent, $taxonomy);
    }
    return $parent;
}
?>