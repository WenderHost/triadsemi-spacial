<?php

/**
 * Adding template column to post_type views in admin
 *
 * @param      array  $defaults  The default columns
 *
 * @return     array  Modified array of columns
 */
function page_column_views( $defaults )
{
   $defaults['page-layout'] = __('Template');
   return $defaults;
}
add_filter( 'manage_pages_columns', 'page_column_views' );

/**
 * Add template to column value
 *
 * @param      string  $column_name  The column name
 * @param      <type>  $id           The identifier
 */
function page_custom_column_views( $column_name, $id )
{
   if ( $column_name === 'page-layout' ) {
       $set_template = get_post_meta( get_the_ID(), '_wp_page_template', true );
       if ( $set_template == 'default' ) {
           echo 'Default';
       }
       $templates = get_page_templates();
       ksort( $templates );
       foreach ( array_keys( $templates ) as $template ) :
           if ( $set_template == $templates[$template] ) echo $template;
       endforeach;
   }
}
add_action( 'manage_pages_custom_column', 'page_custom_column_views', 5, 2 );

/**
 * Returns true if page is a `blog` page
 *
 * @return     boolean  True if blog page, false otherwise.
 */
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

    if( is_post_type_archive( 'product' ) )
      return false;

    if( is_archive() )
      return true;

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

/**
 * Returns the reading time given a string of text.
 *
 * @param      string          $content  The content
 *
 * @return     string  String with how many minutes it will take to read the input text
 */
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