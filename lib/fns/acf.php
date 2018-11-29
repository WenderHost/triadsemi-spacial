<?php

namespace TriadSemiSpacial\acf;

function acf_json_save_point( $path ) {
    // update path
    $path = get_stylesheet_directory() . '/lib/acf-json';

    // return
    return $path;
}
add_filter('acf/settings/save_json', __NAMESPACE__ . '\\acf_json_save_point');


//* Speed up backend load times by removing the Meta Fields box
add_filter('acf/settings/remove_wp_meta_box', '__return_true');