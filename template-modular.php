<?php
/**
 * Template Name: Modular
 * Description: A page with modular content.
 */
$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

$header = get_field('header');
if( $header ):
  $type = $header['type'];
  $background = ( $header['background']['url'] )? $header['background']['url'] : get_stylesheet_directory_uri() . '/images/unsplash/michael-benz-189971.jpg' ;

  if( $header['content_block'] ){
    $content_block = do_shortcode( '[dcb id=' . $header['content_block']->ID . ']' );
    $search = array( '{themedir}','{check}' );
    $replace = [ \trailingslashit( get_stylesheet_directory_uri() ), '<i class=\"medium fa-check-circle-o fa-2x fa\"></i>' ];
    $content = str_replace( $search, $replace, $content_block );
  } else {
    $content = $header['content'];
  }

  $context['page_header'] = [
    'type'              => $type,
    'background'  => $background,
    'content'           => $content,
  ];
endif;

if( have_rows('modules') ):
  $modules = [];
  while( have_rows('modules') ): the_row();
    $module = get_sub_field('module');
    //error_log( '$module = ' . print_r($module,true) );
    if( $module['content_block'] ){
      $content_block = do_shortcode( '[dcb id=' . $module['content_block']->ID . ']' );
      $search = array( '{themedir}','{check}' );
      $replace = [ \trailingslashit( get_stylesheet_directory_uri() ), '<i class=\"medium fa-check-circle-o fa-2x fa\"></i>' ];
      $content_block = str_replace( $search, $replace, $content_block );
    }
    $modules[] = [
      'class'         => $module['class'],
      'style'         => $module['style'],
      'content'       => $module['content'],
      'content_block' => $content_block,
    ];
  endwhile;
  $context['modules'] = $modules;
endif;

Timber::render( ['modular.twig', 'page.twig'], $context );