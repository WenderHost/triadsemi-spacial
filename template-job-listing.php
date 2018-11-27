<?php
/**
 * Template Name: Job Listing
 * Description: Template for job posts.
 */
$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

$context['contact_info'] = apply_filters( 'the_content', '<div class="alert alert-info">Apply for the position listed on this page by emailing careers@triadsemi.com with the following information: Name, Email, Phone, Position Applying For, and a Copy of your Resume.</div>' );

$context['listing_details'] = get_field('listing_details');

Timber::render( ['job-listing.twig', 'page.twig'], $context );