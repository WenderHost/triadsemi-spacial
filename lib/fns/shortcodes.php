<?php

namespace TriadSemiSpacial\shortcodes;

/**
 * Displays icons
 *
 * @param      array  $atts   The atts
 *
 * @return     string  Icon HTML
 */
function icon( $atts ){
  $args = \shortcode_atts([
    'type' => 'check',
  ], $atts );

  $html = '';
  switch ( $args['type'] ) {
    default:
      $html = '<i class="medium fa-check-circle-o fa-2x fa"></i>';
      break;
  }

  return $html;
}
add_shortcode( 'icon', __NAMESPACE__ . '\\icon' );

/**
 * Returns HTML stored in lib/html/
 *
 * @since 1.0.0
 *
 * @return string Specify the HTML to retrieve.
 */
function include_file( $atts ){
  $args = \shortcode_atts([
    'file'          => 'name-of-your-html-file',
    'doshortcodes'  => true,
    'caption'       => '',
  ], $atts );

  $filetype = wp_check_filetype( $args['file'], ['html' => 'text/html', 'htm' => 'text/html', 'json' => 'application/json'] );

  if( empty( $filetype['ext'] ) ){
    $file = dirname( __FILE__ ) . '/../html/' . $args['file'] . '.html';
  } else {
    $file = dirname( __FILE__ ) . '/../' . $filetype['ext'] . '/' . $args['file'];
  }

  $return = ( file_exists( $file ) )? file_get_contents( $file ) : '<div class="alert alert-danger"><strong>Shortcode Error:</strong><br/>I could not find <code>' . basename( $file ) . '</code>.</div>' ;

  $search = array( '{themedir}','{check}' );
  $replace = [ \trailingslashit( get_stylesheet_directory_uri() ), '<i class=\"medium fa-check-circle-o fa-2x fa\"></i>' ];
  $return = str_replace( $search, $replace, $return );

  if( true == $args['doshortcodes'] )
    $return = \do_shortcode( $return );

  switch ( $filetype['type']) {
    case 'application/json':
      $json = json_decode( $return );
      foreach( $json as $key => $row ){
        if( $key === 0 ){
          $skiphead = true;
          foreach( $row as $cell ){
            if( ! empty( $cell ) )
              $skiphead = false;
          }
          if( $skiphead )
            continue;
          $thead = '<thead><tr><th>'. implode( '</th><th>', $row ) .'</th></tr></thead>';
        } else {
          $tbody[] = '<tr><td>' . implode( '</td><td>', $row ) . '</td></tr>';
        }
      }
      $caption = ( ! empty( $args['caption'] ) )? '<caption>' . $args['caption'] . '</caption>' : '' ;
      $return = '<table class="fancy table table-striped">' . $caption . $thead .'<tbody>' . implode( '', $tbody ) . '</tbody></table>';
      if( true == $args['doshortcodes'] )
        $return = \do_shortcode( $return );
      break;

    default:
      // nothing
      break;
  }

  return $return;
}
add_shortcode( 'include', __NAMESPACE__ . '\\include_file' );

/**
 * Displays an info box, but WooCommerce must be installed and
 * activated in order for the box to show.
 *
 * @param      array    $atts     The attributes, `product` can be a comma separated list of product IDs and/or titles, `style` is for CSS styles.
 * @param      string   $content  The content
 *
 * @return     string  A message inside an alert box.
 */
function woocommerce_conditional_message( $atts, $content ){
  $args = shortcode_atts( [
      'product' => null,
      'style' => null,
    ], $atts );

  if( ! function_exists( 'WC') || is_admin() )
    return;

  if( is_null( $args['product'] ) || empty( $args['product'] ) )
    return $content;

  if( stristr( $args['product'], ',') ){
    $args['product'] = explode( ',', $args['product'] );
  } else {
    $args['product'] = [ $args['product'] ];
  }

  if( ! empty( $args['style'] ) )
    $style = ' style="' . $args['style'] . '"';

  $html = '';
  foreach( WC()->cart->get_cart() as $cart_item_key => $values ){
    $product = $values['data'];

    $match = false;

    if(
      in_array( $product->id, $args['product'] ) ||
      in_array( $product->post_title, $args['product'] )
    ){
      $match = true;
    }

    if( $match )
      return '<div class="alert alert-info"' . $style . '>' . $content . '</div>';
  }
}
add_shortcode( 'wcmsg', __NAMESPACE__ . '\\woocommerce_conditional_message' );