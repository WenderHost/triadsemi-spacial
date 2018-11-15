<?php

if ( ! class_exists( 'Timber' ) ){
    echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';

    return;
}

$context            = Timber::get_context();
$context['sidebar'] = Timber::get_widgets( 'shop-sidebar' );

if ( is_singular( 'product' ) ) {
  $context['post']      = Timber::get_post();
  $product              = wc_get_product( $context['post']->ID );
  $context['product']   = $product;
  $context['baseprice'] = $product->get_price();
  error_log('$product->get_price() = ' . $product->get_price() );

  // Get related products
  $related_limit               = wc_get_loop_prop( 'columns' );
  $related_ids                 = wc_get_related_products( $context['post']->id, $related_limit );
  $context['related_products'] =  Timber::get_posts( $related_ids );

  // Get the user's location (i.e. country code).
  //$test_ips = [ 'china' => '27.106.191.255', 'taiwan' => '27.147.63.255', 'southkorea' => '14.63.255.255' ];
  $country = '';
  if( class_exists( 'WC_Geolocation' ) ){
    $geolocate = new WC_Geolocation();
    $user_ip = $geolocate->get_ip_address();
    $location = $geolocate->geolocate_ip( $user_ip );
    $country = $location['country'];
  }

  // Don't show price breaks for China (CN), Taiwan (TW), and South Korea (KR)
  $countries = [ 'CN', 'TW', 'KR' ];
  $max_price_breaks = ( in_array( $country, $countries ) )? 1 : 10;

  // Get discounts
  if( 'yes' == get_post_meta( $post->ID, '_bulkdiscount_enabled', true ) && class_exists( 'Woo_Bulk_Discount_Plugin_t4m' ) ){
    error_log("\n\n" . 'Calculating bulk discounts...');

    if( 'fixed' != get_option( 'woocommerce_t4m_discount_type' ) )
      return;

    $bulk_discounts = [];
    $default_price = $product->get_price();
    for( $x = 1; $x <= $max_price_breaks; $x++ ){
      $quantity = get_post_meta( $post->ID, '_bulkdiscount_quantity_' . $x, true );
      $discount = get_post_meta( $post->ID, '_bulkdiscount_discount_fixed_' . $x, true );
      // If not quantity or discount, break the loop
      if( empty( $quantity ) || empty( $discount ) )
        break;
      //$bulk_discounts[$x] = ['quantity' => $quantity, 'discount' => get_woocommerce_currency_symbol() . number_format( $default_price - $discount, 2 ) ];
      $bulk_discounts[$x] = ['quantity' => $quantity, 'discount' => $discount ];
    }
    $context['bulk_discounts'] = $bulk_discounts;

    /*
    $row_format = '<tr><td>%1$s</td><td></td><td>%2$s</td></tr>';
    $rows = [];
    // Add default pricing
    $minimum = get_post_meta( $post->ID, '_wpbo_minimum', true );
    $default_quantity = ( ! empty( $minimum ) )? $minimum : '1';
    $minimum_increment = ( $wpbo_step = get_post_meta( $post->ID, '_wpbo_step', true ) )? $wpbo_step : 1;
    if( $minimum != ( $bulk_discounts[1]['quantity'] - $minimum_increment ) ){
      $default_quantity.= ' - ' . ( $bulk_discounts[1]['quantity'] - $minimum_increment );
    }

    $bulk_discounts[0] = ['quantity' => $default_quantity, 'discount' => get_woocommerce_currency_symbol() . $default_price ];
    ksort( $bulk_discounts );
    $count_discounts = count( $bulk_discounts );
    $bulk_discounts_table = '';
    foreach( $bulk_discounts as $key => $data ){
      if( $key > 0 ){
        $next_key = $key + 1;
        if( $key < ( $count_discounts - 1 ) ){
          $quantity = $data['quantity'] . ' - ' . ( $bulk_discounts[$next_key]['quantity'] - $minimum_increment );
        } else if( $key == ( $count_discounts - 1 ) ){
          $quantity = $data['quantity']. '+';
        } else {
          $quantity = $data['quantity'];
        }
      } else {
        $quantity = $data['quantity'];
      }
      $rows[] = sprintf( $row_format, $quantity, $data['discount'] );
    }
    $rows = implode( '', $rows );
    $bulk_discounts_table.= '<table class="bulk-discounts-table table"><thead><tr><th>Price Break</th><th>Unit Price</th><th>Extended Price</th></tr></thead><tbody>' . $rows . '</tbody></table>';
    $context['bulk_discounts_table'] = $bulk_discounts_table;
    /**/
  }

  // Restore the context and loop back to the main query loop.
  wp_reset_postdata();

  Timber::render( ['templates/woo/single-product.twig'], $context );
} else {
    $posts = Timber::get_posts();
    $context['products'] = $posts;

    if ( is_product_category() ) {
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id;
        $context['category'] = get_term( $term_id, 'product_cat' );
        $context['title'] = single_term_title( '', false );
    }

    Timber::render( ['templates/woo/archive.twig'], $context );
}