<?php

namespace TriadSemiSpacial\userprojects_cpt;

// Register Custom Post Type User Project
// Post Type Key: userproject
function create_userproject_cpt() {

  $labels = array(
    'name' => __( 'User Projects', 'Post Type General Name', 'triadsemi' ),
    'singular_name' => __( 'User Project', 'Post Type Singular Name', 'triadsemi' ),
    'menu_name' => __( 'User Projects', 'triadsemi' ),
    'name_admin_bar' => __( 'User Project', 'triadsemi' ),
    'archives' => __( 'User Project Archives', 'triadsemi' ),
    'attributes' => __( 'User Project Attributes', 'triadsemi' ),
    'parent_item_colon' => __( 'Parent User Project:', 'triadsemi' ),
    'all_items' => __( 'All User Projects', 'triadsemi' ),
    'add_new_item' => __( 'Add New User Project', 'triadsemi' ),
    'add_new' => __( 'Add New', 'triadsemi' ),
    'new_item' => __( 'New User Project', 'triadsemi' ),
    'edit_item' => __( 'Edit User Project', 'triadsemi' ),
    'update_item' => __( 'Update User Project', 'triadsemi' ),
    'view_item' => __( 'View User Project', 'triadsemi' ),
    'view_items' => __( 'View User Projects', 'triadsemi' ),
    'search_items' => __( 'Search User Project', 'triadsemi' ),
    'not_found' => __( 'Not found', 'triadsemi' ),
    'not_found_in_trash' => __( 'Not found in Trash', 'triadsemi' ),
    'featured_image' => __( 'Featured Image', 'triadsemi' ),
    'set_featured_image' => __( 'Set featured image', 'triadsemi' ),
    'remove_featured_image' => __( 'Remove featured image', 'triadsemi' ),
    'use_featured_image' => __( 'Use as featured image', 'triadsemi' ),
    'insert_into_item' => __( 'Insert into User Project', 'triadsemi' ),
    'uploaded_to_this_item' => __( 'Uploaded to this User Project', 'triadsemi' ),
    'items_list' => __( 'User Projects list', 'triadsemi' ),
    'items_list_navigation' => __( 'User Projects list navigation', 'triadsemi' ),
    'filter_items_list' => __( 'Filter User Projects list', 'triadsemi' ),
  );
  $args = array(
    'label' => __( 'User Project', 'triadsemi' ),
    'description' => __( 'Projects by Triad Semiconductor users', 'triadsemi' ),
    'labels' => $labels,
    'menu_icon' => 'dashicons-media-code',
    'supports' => array('title', 'editor', 'thumbnail', ),
    'taxonomies' => array(),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 25,
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => false,
    'can_export' => true,
    'has_archive' => true,
    'hierarchical' => false,
    'exclude_from_search' => false,
    'show_in_rest' => true,
    'publicly_queryable' => true,
    'capability_type' => 'post',
  );
  register_post_type( 'userproject', $args );

}
add_action( 'init', __NAMESPACE__ . '\\create_userproject_cpt', 0 );