<?php

if (!defined('ABSPATH')){
    exit;
}

if ( ! class_exists( 'TH_Advance_Product_Search_Notice' ) ){

class TH_Advance_Product_Search_Notice{

    function __construct(){

        add_action( 'admin_enqueue_scripts', array($this,'th_advance_product_search_admin_enqueue_style') );
        add_action( 'admin_notices', array($this,'th_advance_product_search_admin_notice' ));

        
    }

    function th_advance_product_search_admin_enqueue_style(){

         wp_enqueue_style( 'th-advance-product-search-notice-style', TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI.'notice/css/th-notice.css', array(), '1.0.0' );

    } 

    function th_advance_product_search_admin_notice() { ?>

    <div class="th-advance-product-search-notice notice notice-success is-dismissible">
        <div class="th-advance-product-search-notice-wrap">
            <div class="th-advance-product-search-notice-image"><img src="<?php echo esc_url( TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI.'notice/img/search-pro.png' );?>" alt="<?php _e('TH Advance Product Search Pro','th-advance-product-search'); ?>"></div>
            <div class="th-advance-product-search-notice-content-wrap">
                <div class="th-advance-product-search-notice-content">
                <p class="th-advance-product-search-heading"><?php _e('Get Effective and Proffessional Search Engine that quickly Leads Your Customer Towards Products they have searched.','th-advance-product-search'); ?></p>
                <p><?php _e('Allow user to search by Category, Tags , Attribute, SKU. Fast and Mobile Responsive Search Engine. You can also Highlight Sale Products or Featured Product in Search Result.','th-advance-product-search'); ?></p>
                </div>
                <a target="_blank" href="<?php echo esc_url('https://themehunk.com/advanced-product-search/');?>" class="upgradetopro-btn"><?php _e('Upgrade To Pro','th-advance-product-search');?> </a>
            </div>
        </div>
    </div>

    <?php }

    
}

$obj = New TH_Advance_Product_Search_Notice();

 }
