<?php 
defined( 'ABSPATH' ) || exit;

if( !class_exists('WADAP_FRONTEND') ){
    class WADAP_FRONTEND 
    {

        public function __construct() {
            //Frontend scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
            add_action( "wp_footer", array($this, "wadap_header_sticky_message") );
            add_filter('body_class', array($this, "wadap_body_class"), 10, 1);
            add_action("wp_head", array($this, "wadap_message_css"));

            add_action( 'woocommerce_cart_calculate_fees', array($this, "wadap_apply_discout_cart") );
        }

        public function frontend_scripts()
        {
            wp_enqueue_style( 'frontend-css', WADAP_PLUGIN_FILE_URI . 'assets/css/frontend.css', array(), WADAP_VERSION );
        }
        public function is_discount_in_valid_period()
        {
            $wadap_date_range = explode("-",get_option('wadap_date_range', true));

            $start_date = trim($wadap_date_range[0]);
            $end_date = trim($wadap_date_range[1]);
            $start_date = date("d-m-Y", strtotime($start_date));
            $end_date = date("d-m-Y", strtotime($end_date));
        
            $today = date("d-m-Y");
            if($today >= $start_date && $today <= $end_date)
            {
                return true;
            }
            return false;
        }
        public function wadap_header_sticky_message(){
            if($this->is_discount_in_valid_period()) :
                $wadap_header_message = get_option('wadap_header_message', true);
                echo '<div class="wadap-discoup-popup">'.apply_filters("the_content", $wadap_header_message).'</div>';
            endif;
        }
        public function wadap_body_class($classes)
        {
            if($this->is_discount_in_valid_period()) :
                $classes[] = 'wadap_discout_active';
            endif;
            return $classes;
        }

        public function wadap_message_css()
        {
            if($this->is_discount_in_valid_period()) :
                $wadap_background = get_option('wadap_background', true);
                $wadap_text_color = get_option('wadap_text_color', true);
                echo '<style>
                body.wadap_discout_active .wadap-discoup-popup { background: '.$wadap_background.'; color: '.$wadap_text_color.' }
                </style>';
            endif;
        }
        public function wadap_apply_discout_cart() {
            if($this->is_discount_in_valid_period()) :
                $wadap_discount = get_option('wadap_discount', true);
                if(!empty($wadap_discount)) :
                    $discountAmout = WC()->cart->subtotal * $wadap_discount /100;
                    WC()->cart->add_fee( 'Flat '.$wadap_discount.'% Off', -$discountAmout );
                endif;
            endif;
        }
    }
}