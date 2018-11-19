<?php

namespace TriadSemiSpacial\sidebars;

function widgets_init(){
  $args = array(
    'name'          => __( 'Footer 1', 'triadsemi' ),
    'id'            => 'footer_1',
    'description'   => '',
    'class'         => '',
    'before_widget' => '<div class="widget" id="">',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="title">',
    'after_title'   => '</div>',
  );
  register_sidebar( $args );
  $args = array(
    'name'          => __( 'Footer 2', 'triadsemi' ),
    'id'            => 'footer_2',
    'description'   => '',
    'class'         => '',
    'before_widget' => '<div class="widget" id="">',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="title">',
    'after_title'   => '</div>',
  );
  register_sidebar( $args );
  $args = array(
    'name'          => __( 'Footer 3', 'triadsemi' ),
    'id'            => 'footer_3',
    'description'   => '',
    'class'         => '',
    'before_widget' => '<div class="widget" id="">',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="title">',
    'after_title'   => '</div>',
  );
  register_sidebar( $args );
  $args = array(
    'name'          => __( 'Footer 4', 'triadsemi' ),
    'id'            => 'footer_4',
    'description'   => '',
    'class'         => '',
    'before_widget' => '<div class="widget" id="">',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="title">',
    'after_title'   => '</div>',
  );
  register_sidebar( $args );
}
add_action( 'widgets_init', __NAMESPACE__ . '\\widgets_init' );