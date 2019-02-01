<?php

namespace TriadSemiSpacial\acf;

/**
 * Saves changes to ACF field groups to keep different
 * environments in sync. `Sync available` will show
 * above the Field Groups under Custom Fields > Field
 * Groups whenever an update is available. This comes
 * in handy whenever in situations when you add a
 * field in development and upload a new version of
 * the theme on production.
 */
function acf_json_save_point( $path ) {
    // update path
    $path = get_stylesheet_directory() . '/lib/acf-json';

    // return
    return $path;
}
add_filter('acf/settings/save_json', __NAMESPACE__ . '\\acf_json_save_point');

/**
 * Loads our ACF Field Group settings saved by
 * `acf_json_save_point`.
 */
function acf_json_load_point( $paths ) {
    // remove original path (optional)
    unset($paths[0]);

    // append path
    $paths[] = get_stylesheet_directory() . '/lib/acf-json';

    // return
    return $paths;
}
add_filter('acf/settings/load_json', __NAMESPACE__ . '\\acf_json_load_point');

/**
 * Adds a `Theme Settings` page to the admin.
 */
if( function_exists( '\acf_add_options_page' ) ){
  \acf_add_options_page([
    'page_title' => 'Theme Settings',
    'parent_slug' => 'themes.php'
  ]);
}

//* Speed up backend load times by removing the Meta Fields box
add_filter('acf/settings/remove_wp_meta_box', '__return_true');