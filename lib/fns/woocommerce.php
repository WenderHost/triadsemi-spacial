<?php

namespace TriadSemiSpacial\woocommerce;

/**
 * Adds content to the WooCommerce Account Dashboard
 */
function account_dashboard(){
?>
    <div class="alert alert-info">
    <h3>TriadSemi Help</h3>
    <p><em>Do you need help, have a question, or want to chat with Triad Staff?</em> If you are seeing this message, you already have access to <a href="https://help.triadsemi.com">help.triadsemi.com</a>.</p>
    <a href="https://help.triadsemi.com" target="_blank"><img src="<?php echo get_stylesheet_directory_uri() ?>/lib/img/forum-screenshot.1600x1166.png" style="width: 500px; height: 364px;"/></a>
    </div>
<?php
}
//add_action( 'woocommerce_account_dashboard', __NAMESPACE__ . '\\account_dashboard' );

/**
 * Configures WooCommerce product display setup.
 */
function product_display() {
  global $product;

  remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );

  remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
  remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
  remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

  remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
  remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
  //remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
  //remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
  //add_action('woocommerce_after_single_product_summary', __NAMESPACE__ . '\\product_content', 10);
}
add_action('wp_head', __NAMESPACE__ . '\\product_display');

/**
 * Redirects users to the WooCommerce registration URL
 */
function registration_redirect( $registraion_url ){
    return home_url( '/my-account/' );
}
add_filter( 'register_url', __NAMESPACE__ . '\\registration_redirect' );

//* Remove Related Products
//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

//* Remove Default Product Image
