<?php
/**
 * Template Name: Tabs
 * Description: A page with tabbed content.
 */
$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

if( have_rows('tabs') ):
  $tabs = [];
  while( have_rows('tabs') ): the_row();
    $tab = get_sub_field('tab');
    $tabs[] = [
      'title' => $tab['tab_title'],
      'id' => sanitize_title_with_dashes( $tab['tab_title'] ),
      'content' => $tab['tab_content']
    ];
  endwhile;
  $context['tabs'] = $tabs;
endif;

Timber::render( ['tabs.twig', 'page.twig'], $context );