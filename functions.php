<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 * @version 1.0.0
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
	});

	add_filter('template_include', function( $template ) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
include_once('lib/classes/mysite.php');

add_action( 'after_setup_theme', function(){
  add_theme_support( 'woocommerce' );
  //include_once('woocommerce.php');
} );

function timber_set_product( $post ) {
    global $product;

    if ( is_woocommerce() ) {
        $product = wc_get_product( $post->ID );
    }
}

add_editor_style( 'dist/theme.min.css' );

//* Include functions
include_once('lib/fns/acf.php');
include_once('lib/fns/enqueues.php');
include_once('lib/fns/gravityforms.php');
include_once('lib/fns/images.php');
include_once('lib/fns/shortcodes.php');
include_once('lib/fns/sidebars.php');
include_once('lib/fns/user-projects-cpt.php');
include_once('lib/fns/utilities.php');
include_once('lib/fns/woocommerce.php');
