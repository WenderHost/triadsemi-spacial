<?php
/**
 * Template Name: Accordion
 * Description: A page with accordion content.
 */
$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

if( have_rows('accordion') ):
  $accordion = [];
  while( have_rows('accordion') ): the_row();
    $card = get_sub_field('card');
    $accordion[] = [
      'title'   => $card['title'],
      'content' => $card['content']
    ];
  endwhile;
  $context['accordion'] = $accordion;
endif;

Timber::render( ['accordion.twig', 'page.twig'], $context );