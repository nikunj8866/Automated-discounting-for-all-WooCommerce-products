<?php
/*
  Plugin Name: Automated discounting for all WooCommerce products
  Plugin URI: https://nikunj-hatkar.business.site/
  description: Automated discounting for all WooCommerce products
  Version: 1.0
  Author: Nikunj Hatkar
  textdomain : wadap
*/

defined( 'ABSPATH' ) || exit;

define( 'WADAP_PLUGIN_FILE', __FILE__ );
define( 'WADAP_PLUGIN_FILE_URI', plugin_dir_url( __FILE__ ));
define( 'WADAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WADAP_VERSION', 1.0 );


require WADAP_PLUGIN_DIR . 'includes/class-wadap.php';
require WADAP_PLUGIN_DIR . 'includes/class-wadap-frontend.php';

add_action( 'woocommerce_loaded',  'wadap_woo_loaded' ); 

function wadap_woo_loaded() 
{ 
    new WADAP();
    new WADAP_FRONTEND();
}

add_action( 'plugins_loaded', 'wadap_on_plugins_loaded'  );
function wadap_on_plugins_loaded()
{
    if ( ! class_exists( 'WooCommerce' ) ) {
      add_action( 'admin_notices', function() {
        /* translators: %s WC download URL link. */
        echo '<div class="error"><p><strong>' . esc_html__( 'Automated discounting for all WooCommerce products requires the WooCommerce plugin to be installed and active.', 'wadap' ) . '</strong></p></div>';
      } );
    }
}

?>