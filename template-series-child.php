<?php
/**
 * Template Name: Series Child
 * Description: A page that is part of a series of pages.
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

$next_page_id = get_field('next_page');
$prev_page_id = get_field('previous_page');

if( ! empty( $next_page_id ) || ! empty( $prev_page_id ) )
  $context['page_nav'] = [];

if( ! empty( $next_page_id ) )
  $context['page_nav'] = [
    'next_page_link' => get_permalink( $next_page_id ),
    'next_page_title' => get_the_title( $next_page_id ),
  ];

if( ! empty( $prev_page_id ) )
  $context['page_nav'] = [
    'prev_page_link' => get_permalink( $prev_page_id ),
    'prev_page_title' => get_the_title( $prev_page_id ),
  ];

Timber::render( ['page.twig'], $context );