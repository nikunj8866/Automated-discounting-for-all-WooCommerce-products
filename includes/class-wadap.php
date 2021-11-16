<?php 
defined( 'ABSPATH' ) || exit;

if( !class_exists('WADAP') ){
    class WADAP 
    {
        public function __construct() {

            add_action( 'plugins_loaded', array( $this, 'wadap_on_plugins_loaded' ) );
            //Discount setting admin page
            add_action('admin_menu', array ( $this, 'wadap_create_discount_settting_page') );
            //Admin scripts
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
            //Ajax call for save option
            add_action('wp_ajax_wadap_save_setting', array( $this, 'wadap_save_setting'));
            //get discount date
            add_shortcode('wadap-date', array( $this, 'func_wadap_date'));
            //get percentage
            add_shortcode('wadap-discount', array( $this, 'func_wadap_discount'));

        }

        public function wadap_on_plugins_loaded()
        {
            if ( ! class_exists( 'WooCommerce' ) ) {
				add_action( 'admin_notices', function() {
					/* translators: %s WC download URL link. */
					echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Automated discounting for all WooCommerce products requires the WooCommerce plugin to be installed and active. You can download %s here.', 'wadap' ), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
				} );
			}
        }
        public function wadap_create_discount_settting_page(){
            add_menu_page(__('Discount Setting', 'wadap'), __('Discount Setting', 'wadap'), 'manage_options', 'wadap-discount-setting', array( $this, 'wadap_discount_page') );
        }

        public function wadap_discount_page(){
            include_once( WADAP_PLUGIN_DIR . '/templates/admin-discount-setting.php' );
        }

        public function admin_scripts()
        {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style( 'daterangepicker-css', WADAP_PLUGIN_FILE_URI . 'assets/css/daterangepicker.css', array(), WADAP_VERSION );
            wp_enqueue_style( 'admin-css', WADAP_PLUGIN_FILE_URI . 'assets/css/admin-css.css', array(), WADAP_VERSION );
            wp_enqueue_script( 'moment-js', WADAP_PLUGIN_FILE_URI . 'assets/js/moment.min.js', array('jquery'), WADAP_VERSION);
            wp_enqueue_script( 'daterangepicker-js', WADAP_PLUGIN_FILE_URI . 'assets/js/daterangepicker.min.js', array('jquery'), WADAP_VERSION );
            wp_enqueue_script( 'wadap-admin-js', WADAP_PLUGIN_FILE_URI . 'assets/js/wadap-admin.js', array('jquery'), WADAP_VERSION );
            wp_localize_script('wadap-admin-js', 'localize_object', array("ajax_url" => admin_url('admin-ajax.php')));
        }
        public function wadap_save_setting()
        {
            $nonce = $_POST['wadap-settings-save'];
            $response = array();
            if ( ! wp_verify_nonce( $nonce, 'wadap-settings-save' ) ){
                $response['status'] = 'fail';
                $response['msg'] = 'Security fail. Please try again';
                wp_send_json($response);
            }
            $wadap_date_range = $_POST['wadap_date_range'];
            $wadap_discount = $_POST['wadap_discount'];
            $wadap_header_message = $_POST['wadap_header_message'];
            $wadap_background = $_POST['wadap_background'];
            $wadap_text_color = $_POST['wadap_text_color'];

            update_option('wadap_date_range', $wadap_date_range);
            update_option('wadap_discount', $wadap_discount);
            update_option('wadap_header_message', $wadap_header_message);
            update_option('wadap_background', $wadap_background);
            update_option('wadap_text_color', $wadap_text_color);

            $response['status'] = 'success';
            $response['msg'] = 'Setting updated successfully';
            wp_send_json($response);
        }
        
        public function func_wadap_date()
        {
            ob_start();
                $wadap_date_range = explode("-",get_option('wadap_date_range', true));
                $start_date =  trim($wadap_date_range[0]);
                $end_date = trim($wadap_date_range[1]);
                $start_date = apply_filters("wadap_start_date", date("d/m/Y", strtotime($start_date)));
                $end_date = apply_filters( "wadap_end_date", date("d/m/Y", strtotime($end_date)));
                $sep = apply_filters( "wadap_date_seprator", " - ");
                if(!empty($wadap_date_range)) :
                    echo $start_date.$sep.$end_date;
                endif;
            return ob_get_clean();
        }

        public function func_wadap_discount()
        {
            ob_start();
            $wadap_discount = get_option('wadap_discount');
            if(!empty($wadap_discount)) :
                echo $wadap_discount;
            endif;
            return ob_get_clean();
        }
    }

}