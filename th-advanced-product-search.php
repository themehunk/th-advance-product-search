<?php
/**
 * Plugin Name:             TH Advance Product Search
 * Plugin URI:              https://themehunk.com
 * Description:             Beautiful Colors, Images and Buttons Variation Swatches For WooCommerce Product Attributes
 * Version:                 1.0.0
 * Author:                  ThemeHunk
 * Author URI:              https://themehunk.com
 * Requires at least:       4.8
 * Tested up to:            5.7
 * WC requires at least:    3.2
 * WC tested up to:         5.1
 * Domain Path:             /languages
 * Text Domain:             th-advance-product-search
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if (!defined('TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_FILE')) {
    define('TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_FILE', __FILE__);
}

if (!defined('TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI')) {
    define( 'TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
}

if (!defined('TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_PATH')) {
    define( 'TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if (!defined('TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_DIRNAME')) {
    define( 'TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
}

if (!defined('TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_BASENAME')) {
    define( 'TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

if (!defined('TH_ADVANCE_PRODUCT_SEARCH_IMAGES_URI')) {
define( 'TH_ADVANCE_PRODUCT_SEARCH_IMAGES_URI', trailingslashit( plugin_dir_url( __FILE__ ) . 'images' ) );
}

if (!defined('TH_ADVANCE_PRODUCT_SEARCH_VERSION')) {
    $plugin_data = get_file_data(__FILE__, array('version' => 'Version'), false);
    define('TH_ADVANCE_PRODUCT_SEARCH_VERSION', $plugin_data['version']);
} 

if (!class_exists('TH_Advance_Product_Search')) {
require_once("inc/thaps.php");
} 

        /**
         * Add the settings link to the Lead Form Plugin plugin row
         *
         * @param array $links - Links for the plugin
         * @return array - Links
         */
function thaps_plugin_action_links($links) {
          $settings_page = add_query_arg(array('page' => 'th-advance-product-search'), admin_url('admin.php'));
          $settings_link = '<a href="'.esc_url($settings_page).'">'.__('Settings', 'th-advance-product-search' ).'</a>';
          array_unshift($links, $settings_link);

           $links['thaps_pro'] = sprintf( '<a style="color: #39b54a; font-weight:600;" href="%s" target="_blank" class="thvs-plugins-gopro">%s</a>', '#', __( 'Go Pro', 'th-advance-product-search' ) );
          return $links;
        }
add_filter('plugin_action_links_'.TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_BASENAME, 'thaps_plugin_action_links', 10, 1);