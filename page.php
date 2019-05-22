<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/views/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::get_context();
$post = new TimberPost();

$body_classes = get_body_class();
$elementor_page = ( in_array( 'elementor-page elementor-page-' . $post->ID, $body_classes ) )? true : false ;
$context['elementor_page'] = $elementor_page;

$header = get_field('header');
if( $header ):
  $type = $header['type'];
  $height = $header['height'];
  $background = ( $header['background']['url'] )? $header['background']['url'] : get_stylesheet_directory_uri() . '/images/unsplash/michael-benz-189971.jpg' ;

  if( $header['content_block'] ){
    $content_block = do_shortcode( '[dcb id=' . $header['content_block']->ID . ']' );
    $search = array( '{themedir}','{check}' );
    $replace = [ \trailingslashit( get_stylesheet_directory_uri() ), '<i class=\"medium fa-check-circle-o fa-2x fa\"></i>' ];
    $content = str_replace( $search, $replace, $content_block );
  } else {
    $content = do_shortcode( $header['content'] );
  }

  $context['page_header'] = [
    'type'        => $type,
    'background'  => $background,
    'content'     => $content,
  ];

  if( $header['set_header_height'][0] )
    $context['page_header']['height'] = $height;
endif;
//error_log('$body_classes = ' . print_r($body_classes,true) );
//error_log('$context[elementor_page] = ' . $context['elementor_page'] );
//error_log('$post->ID = ' . $post->ID);

$context['post'] = $post;
Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context );
