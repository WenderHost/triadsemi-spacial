<?php
/**
 * Template Name: Series Parent
 * Description: The parent page for a series of pages.
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

// Build sidebar submen
$children = get_children( [
  'post_parent' => $post->ID,
  'order' => 'ASC',
  'orderby' => 'menu_order'
]);
if( $children ){
  foreach ( $children as $child ) {
    $submenu[] = '<li><a href="' . get_the_permalink( $child->ID ) .'">' . get_the_title( $child->ID ) . '</a></li>';
  }
  $context['sidebar'] = '<h3>Series Links</h3><ol>' . implode( "\n", $submenu ) . '</ol>';
}

Timber::render( ['templates/page-series-parent.twig', 'page.twig'], $context );