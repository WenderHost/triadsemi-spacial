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
    // START Flexible Content
    $attributes = [];
    $css_classes = get_sub_field('css_classes');
    if( $css_classes )
      $attributes['classes'] = implode(' ', $css_classes);
    $layout = get_row_layout();
    switch ($layout) {

      case 'cta':
        $cta = get_sub_field('details');
        if( empty( $cta['title'] ) || empty( $cta['text'] ) || empty( $cta['link'] ) ){
          $html = '<div class="alert alert-danger">One or more details for your CTA is empty.</div>';
          break;
        }

        $event_tracking = get_sub_field('event_tracking');
        $onclick = '';
        if( $event_tracking )
          $onclick = "__gaTracker('send','event','{$event_tracking['category']}','{$event_tracking['action']}','{$event_tracking['label']}')";

        $attributes['classes'] = 'modular-callout';
        //' . $cta['link']['url'] . '
        $html =
        '<div class="row">
          <div class="col-lg-8">
            <h3>' . $cta['title'] . '</h3>
            <p>' . $cta['text'] . '</p>
          </div>
          <div class="col-lg-4 text-right">
            <a href="#" class="btn btn-triadyellow btn-lg btn-block" target="' . $cta['link']['target'] . '" onclick="' . $onclick . '">' . $cta['link']['title'] . '</a>
          </div>
        </div>';
        break;

      case 'dev_content_block':
        $content_block = get_sub_field('content_block');
        $raw_html = do_shortcode( '[dcb id=' . $content_block->ID . ']' );
        $search = array( '{themedir}','{check}' );
        $replace = [ \trailingslashit( get_stylesheet_directory_uri() ), '<i class=\"medium fa-check-circle-o fa-2x fa\"></i>' ];
        $html = str_replace( $search, $replace, $raw_html );
        break;

      case 'product_line':

        $shortcode_atts = ['category' => null, 'include' => null];

        $title = get_sub_field('title');

        $category = get_sub_field('category');
        $category_term = get_term_by( 'id', $category, 'product_cat' );
        if( $category_term )
          $shortcode_atts['category'] = ' category="' . $category_term->slug . '"';

        $include = get_sub_field('product_ids');
        //error_log('$include = ' . print_r($include,true));
        if( is_array( $include ) )
          $shortcode_atts['include'] = ' include="' . implode( ',', $include ) . '"';

        $html = do_shortcode( '[productline title="' . attribute_escape( $title ) . '"' . $shortcode_atts['category'] . $shortcode_atts['include'] . ']' );
        break;

      default:
        $html = '<div class="alert alert-danger">No layout defined for <code>' . $layout . '</code>.</div>';
        break;
    }

    $modules[] = [
      'attributes'    => $attributes,
      'content'       => $html,
    ];

    // END Flexible Content
    /*
    $module = get_sub_field('module');
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
    /**/
  endwhile;
  $context['modules'] = $modules;
endif;

Timber::render( ['modular.twig', 'page.twig'], $context );