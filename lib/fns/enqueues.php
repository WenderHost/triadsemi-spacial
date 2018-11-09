<?php

namespace TriadSemiCart\enqueues;

function enqueue_scripts(){
  wp_enqueue_style( 'spacial', get_stylesheet_directory_uri() . '/dist/theme.min.css', null, filemtime( get_stylesheet_directory() . '/dist/theme.min.css' ) );
  wp_enqueue_script('spacial', get_stylesheet_directory_uri() . '/dist/theme.min.js', ['jquery'], filemtime( get_stylesheet_directory() . '/dist/theme.min.js' ), true );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );