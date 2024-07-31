<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

/****************/
//Block registered
/****************/

function register_blocks() {
    $blocks = array(
        array(
            'name'           => 'th-advance-product-search/th-advance-product-search',
            'script_handle'  => 'th-advance-product-search',
            'editor_style'   => 'th-advance-product-search-editor-style',
            'frontend_style' => 'th-advance-product-search-frontend-style',
            'render_callback' => 'th_advance_product_search_blocks_render_callback',
            'localize_data'  => array(
                'adminUrlsearch' => admin_url('admin.php?page=th-advance-product-search'),
            ),
        ),
    );

    

    foreach ( $blocks as $block ) {

        
        // Register JavaScript file
        wp_register_script(
            $block['script_handle'],
            TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI . 'build/' . $block['script_handle'] . '.js',
            array( 'wp-blocks', 'wp-element', 'wp-editor' ),
            filemtime( TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_PATH . '/build/' . $block['script_handle'] . '.js' )
        );

        // Register editor style
        wp_register_style(
            $block['editor_style'],
            TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI . 'build/' . $block['script_handle'] . '.css',
            array( 'wp-edit-blocks' ),
            filemtime( TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_PATH . '/build/' . $block['script_handle'] . '.css' )
        );

        // Register front end block style
        wp_register_style(
            $block['frontend_style'],
            TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI . 'build/style-' . $block['script_handle'] . '.css',
            array(),
            filemtime( TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_PATH . '/build/style-' . $block['script_handle'] . '.css' )
        );

        // Localize the script with data
        if ( isset( $block['localize_data'] ) && ! is_null( $block['localize_data'] ) ) {
            wp_localize_script(
                $block['script_handle'],
                'ThBlockDataSearch',
                $block['localize_data']
            );
        }

        // Prepare the arguments for registering the block
        $block_args = array(
            'editor_script'   => $block['script_handle'],
            'editor_style'    => $block['editor_style'],
            'style'           => $block['frontend_style'],
        );

        // Check if the render callback is set and not null
        if ( isset( $block['render_callback'] ) && ! is_null( $block['render_callback'] ) ) {
            $block_args['render_callback'] = $block['render_callback'];
           
        }

        // Register each block
        register_block_type( $block['name'], $block_args );
    }
}

add_action( 'init', 'register_blocks' );

function th_advance_product_search_blocks_categories( $categories ) {
    return array_merge(
        $categories,
        [
            [
                'slug'  => 'th-advance-product-search',
                'title' => __( 'TH advance product search', 'th-advance-product-search' ),
            ],
        ]
    );
}
add_filter( 'block_categories_all', 'th_advance_product_search_blocks_categories', 11, 2);

function th_advance_product_search_blocks_editor_assets(){

	wp_enqueue_script(
		'data-block',
		TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI . 'build/th-advance-product-search-data.js',
		array(),
		TH_ADVANCE_PRODUCT_SEARCH_VERSION,
		true
	);
    wp_localize_script(
        'data-block',
        'thnkblock',
         array(
            'homeUrl' => plugins_url( '/', __FILE__ ),
            'showOnboarding' => '',
        )
    );

    wp_enqueue_style(
        'thaps-component-editor-css',
        TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI . 'build/thaps-component-editor.css',
         array(),
         TH_ADVANCE_PRODUCT_SEARCH_VERSION,
    );

    wp_enqueue_style(
        'thaps-th-icon-css',
        TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI . 'th-icon/style.css',
        TH_ADVANCE_PRODUCT_SEARCH_VERSION,
    );
    wp_enqueue_style(
        'thaps-block-style-css',
        TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI . 'assets/css/thaps-front-style.css',
        TH_ADVANCE_PRODUCT_SEARCH_VERSION,
    );
      
}
add_action( 'enqueue_block_assets', 'th_advance_product_search_blocks_editor_assets' );

function th_advance_product_search_blocks_render_callback( $attr ) {

    $block_content = '<div id="wp-block-th-advance-product-search-' . esc_attr($attr['uniqueID']) . '"  class="wp-block-th-advance-product-search" style="width:100%";>';
    
    $searchStyle = isset($attr['searchStyle']) ? $attr['searchStyle'] : '[th-aps]';

    $block_content .= ''.do_shortcode($searchStyle).'</div>';
    
    return $block_content;
}