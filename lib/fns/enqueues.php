<?php

namespace TriadSemiSpacial\enqueues;

function enqueue_scripts(){
  wp_enqueue_style( 'spacial', get_stylesheet_directory_uri() . '/dist/theme.min.css', null, filemtime( get_stylesheet_directory() . '/dist/theme.min.css' ) );
  wp_enqueue_script('spacial', get_stylesheet_directory_uri() . '/dist/theme.min.js', ['jquery'], filemtime( get_stylesheet_directory() . '/dist/theme.min.js' ), true );
  wp_enqueue_script( 'instant-page', '//instant.page/1.1.0', null, '', true );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );

function add_script_tag_attributes( $tag, $handle, $source ){
  switch( $handle ){
    case 'instant-page':
      $tag = '<script src="//instant.page/1.1.0" type="module" integrity="sha384-EwBObn5QAxP8f09iemwAJljc+sU+eUXeL9vSBw1eNmVarwhKk2F9vBEpaN9rsrtp"></script>';
      break;
  }

  return $tag;
}
add_filter( 'script_loader_tag', __NAMESPACE__ . '\\add_script_tag_attributes', 10, 3 );