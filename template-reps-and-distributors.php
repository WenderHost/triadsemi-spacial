<?php
/**
 * Template Name: Reps and Distributors
 * Description: A page with accordion content.
 */
$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

function template_enqueue_scripts(){
  wp_enqueue_style( 'datatables', 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css', null, '1.10.19' );
  wp_enqueue_script( 'datatables', 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js', ['jquery'], '1.10.19', true );
  wp_enqueue_script( 'site', get_stylesheet_directory_uri() . '/static/site.js', 'datatables' );
}
add_action( 'wp_enqueue_scripts', 'template_enqueue_scripts', 20 );

$context['reps_and_distributors'] = json_decode( file_get_contents( dirname( __FILE__ ) . '/lib/json/reps-and-distributors.json') );

Timber::render( ['reps-and-distributors.twig', 'page.twig'], $context );