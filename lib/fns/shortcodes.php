<?php
/**
 * Shortcodes defined below:
 *
 * - [icon] - Returns HTML markup for icons
 * - [include] - Includes files for display
 * - [listchildren] - Returns a list of child pages
 * - [productline] - Displays products from a WooCommerce category
 * - [wcmsg] - Displays conditional messages in the WooCommerce shopping cart
 */

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

function list_children( $atts ){
  $args = shortcode_atts( [
    'foo' => 'bar',
  ], $atts );

  global $post;

  $children = get_pages([
    'sort_order'    => 'ASC',
    'sort_column'   => 'menu_order',
    'parent'        => $post->ID,
    'hierarchical'  => false,
  ]);

  if( $children ){
    foreach ($children as $page ) {
      $child_list[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
    }
    return '<ul><li>' . implode( '</li><li>', $child_list ) . '</li></ul>';
  } else {
    return '<ul><li>No children found for this page.</li></ul>';
  }
}
add_shortcode( 'listchildren', __NAMESPACE__ . '\\list_children' );

/**
 * [productline] shortcode
 *
 * @param      <type>  $atts   The atts
 */
function product_line( $atts ){
  $args = shortcode_atts([
    'foo'       => 'bar',
    'category'  => null,
    'tag'       => null,
    'title'     => null,
    'include'   => null,
  ], $atts );

  if( stristr( $args['category'], ',' ) )
    $args['category'] = explode( ',', $args['category'] );

  if( ! is_array( $args['category'] ) )
    $args['category'] = [$args['category']];

  if( stristr( $args['tag'], ',' ) )
    $args['tag'] = explode( ',', $args['tag'] );

  if( ! is_array( $args['tag'] ) )
    $args['tag'] = [$args['tag']];

  $title = ( is_null( $args['title'] ) )? implode( ', ', $args['category'] ) . ' Products' : $args['title'] ;

  $get_products_args = [
    'limit'     => -1,
    'return'    => 'ids',
    'category'  => $args['category'],
    'tag'       => $args['tag'],
    'orderby'   => 'title',
    'order'     => 'ASC',
  ];
  if( ! is_null( $args['include'] ) ){
    if( stristr($args['include'], ',' ) ){
      $include = explode(',', $args['include'] );
    } else if( ! is_array( $args['include'] ) && is_numeric( $args['include'] ) ){
      $include = [ $args['include'] ];
    }
  }
  if( is_array( $include ) && 0 < count( $include ) ){
    $product_ids = $include;
  } else {
    $product_ids = wc_get_products( $get_products_args );
  }

  if( $product_ids ){
    foreach ( $product_ids as $id ) {
      $products[] = [
        'permalink' => get_permalink( $id ),
        'title'     => get_the_title( $id ),
        'thumbnail' => get_the_post_thumbnail_url( $id, 'thumbnail' ),
      ];
    }
  } else {
    $products[] = [
      'permalink' => '#',
      'title'     => 'No products found',
      'thumbnail' => 'https://via.placeholder.com/600&text=Placeholder',
    ];
  }

  $context = \Timber::get_context();
  $context['title'] = $title;
  $context['products'] = $products;

  $html = \Timber::compile( ['partial/product-line.twig'], $context );
  return $html;
}
add_shortcode( 'productline', __NAMESPACE__ . '\\product_line' );

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