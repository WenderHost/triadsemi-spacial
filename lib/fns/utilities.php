<?php

function is_blog_page(){
  if ( is_front_page() && is_home() ) {
    // Default homepage
    return true;

  } elseif ( is_front_page() ) {
    // static homepage
    return false;

  } elseif ( is_home() ) {
    // blog page
    return true;

  } else {
    if( is_product() )
      return false;

    if( is_post_type_archive( 'userproject' ) )
      return true;

    //everything else
    if( is_single() )
      return true;

    return false;

  }
}
add_filter( 'timber/twig', function( \Twig_Environment $twig ) {
    $twig->addFunction( new \Timber\Twig_Function( 'is_blog_page', 'is_blog_page' ) );
    return $twig;
} );

function reading_time( $content ) {
    $word_count = str_word_count( strip_tags( $content ) );
    $readingtime = ceil($word_count / 200);

    if ($readingtime == 1) {
      $timer = " minute";
    } else {
      $timer = " minutes";
    }
    $timer = " minute";
    $totalreadingtime = $readingtime . $timer;

    return $totalreadingtime;
}
add_filter( 'timber/twig', function( \Twig_Environment $twig ) {
    $twig->addFunction( new \Timber\Twig_Function( 'reading_time', 'reading_time' ) );
    return $twig;
} );