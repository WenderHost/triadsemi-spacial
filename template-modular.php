<?php
/**
 * Template Name: Modular
 * Description: A page with modular content.
 */
$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

if( have_rows('modules') ):
  $modules = [];
  while( have_rows('modules') ): the_row();
    $module = get_sub_field('module');
    $modules[] = [
      'class'   => $module['class'],
      'style'   => $module['style'],
      'content' => $module['content']
    ];
  endwhile;
  $context['modules'] = $modules;
endif;

Timber::render( ['modular.twig', 'page.twig'], $context );