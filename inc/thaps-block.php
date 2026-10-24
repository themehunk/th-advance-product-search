<?php 

if ( ! defined( 'ABSPATH' ) ) exit;
require_once(ABSPATH . 'wp-admin/includes/plugin.php');
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
            array( 'wp-blocks', 'wp-element', 'wp-editor','customize-controls' ),
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
        [
            [
                'slug'  => 'vayu-blocks',
                'title' => __( 'ThemeHunk', 'th-advance-product-search' ),
            ],
        ],
        $categories
    );
}

if ( !is_plugin_active( 'vayu-blocks/vayu-blocks.php' ) ) {
add_filter( 'block_categories_all', 'th_advance_product_search_blocks_categories', 11, 2);
}

function th_advance_product_search_blocks_editor_assets(){
    if ( is_admin() ) {
    wp_enqueue_style(
        'thaps-component-editor-css',
        TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI . 'build/thaps-component-editor.css',
         array(),
         TH_ADVANCE_PRODUCT_SEARCH_VERSION,
    );
    wp_enqueue_style(
        'thaps-block-style-css',
        TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI . 'assets/css/thaps-front-style.css',
        TH_ADVANCE_PRODUCT_SEARCH_VERSION,
    );
   }
    wp_enqueue_style(
        'th-icon-css',
        TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI . 'th-icon/style.css',
        TH_ADVANCE_PRODUCT_SEARCH_VERSION,
    );
    
      
}
add_action( 'enqueue_block_assets', 'th_advance_product_search_blocks_editor_assets' );

function th_advance_product_search_blocks_render_callback( $attr ) {

    $thapsBlockStyle = '';

    // Colors: only allow safe color values.
    $color_vars = array(
        'searchBtnBgClr'      => '--tapsp-searchBtnBgClr',
        'searchBtnTextClr'    => '--tapsp-searchBtnTextClr',
        'searchBtnHvrTextClr' => '--tapsp-searchBtnHvrTextClr',
        'searchBtnHvrBgClr'   => '--tapsp-searchBtnHvrBgClr',
        'searchTextClr'       => '--tapsp-searchTextClr',
        'searchBarClr'        => '--tapsp-searchBarClr',
        'searchIconClr'       => '--tapsp-searchIconClr',
        'searchborder'        => '--tapsp-searchborderClr',
    );
    foreach ( $color_vars as $key => $var ) {
        if ( isset( $attr[ $key ] ) ) {
            $color = thaps_safe_color( $attr[ $key ] );
            if ( '' !== $color ) {
                $thapsBlockStyle .= "{$var}:{$color};";
            }
        }
    }

    // Sizes: numeric value + whitelisted unit.
    $size_vars = array(
        'searchWidth'           => array( '--tapsp-searchWidth', 'searchWidthUnit' ),
        'searchWidthTablet'     => array( '--tapsp-searchWidthTablet', 'searchWidthUnitTablet' ),
        'searchWidthMobile'     => array( '--tapsp-searchWidthMobile', 'searchWidthUnitMobile' ),
        'barborderRadius'       => array( '--tapsp-barborderRadius', 'barborderRadiusUnit' ),
        'barborderRadiusTablet' => array( '--tapsp-barborderRadiusTablet', 'barborderRadiusUnit' ),
        'barborderRadiusMobile' => array( '--tapsp-barborderRadiusMobile', 'barborderRadiusUnit' ),
    );
    foreach ( $size_vars as $key => $size ) {
        if ( isset( $attr[ $key ] ) && is_numeric( $attr[ $key ] ) ) {
            $unit = isset( $attr[ $size[1] ] ) ? $attr[ $size[1] ] : 'px';
            $unit = in_array( $unit, array( 'px', '%', 'em', 'rem', 'vw', 'vh' ), true ) ? $unit : 'px';
            $thapsBlockStyle .= "{$size[0]}:" . (float) $attr[ $key ] . "{$unit};";
        }
    }

    $unique_id = isset($attr['uniqueID']) ? sanitize_html_class($attr['uniqueID']) : '';
    $block_content = '<div id="wp-block-th-advance-product-search-' . esc_attr($unique_id) . '"  class="wp-block wp-block-th-advance-product-search" style="' . esc_attr($thapsBlockStyle) . '">';
    $block_content .= th_advance_product_search_block($attr);
    $block_content .= '</div>';
    return $block_content;
}

function th_advance_product_search_block($attr){
    ob_start();
    $unique_id = isset($attr['uniqueID']) ? esc_attr($attr['uniqueID']) : '';
    $search_style      = isset($attr['searchStyle']) ? esc_attr($attr['searchStyle']) : 'default';
    // Unknown style: show the default layout (same as the editor) instead of an empty block.
    if ( ! in_array( $search_style, array( 'default', 'bar', 'icon', 'flexi' ), true ) ) {
        $search_style = 'default';
    }
    $select_srch_type = esc_html(th_advance_product_search()->get_option( 'select_srch_type' ));
    $type = 'product';
    if($select_srch_type == 'product_srch'){
        $type = 'product';
        }elseif($select_srch_type == 'post_srch'){
        $type = 'post';
        }elseif($select_srch_type == 'page_srch'){
        $type = 'page';
     }
    if(th_advance_product_search()->get_option( 'show_submit' )=='0'){
    $barClass='submit-no-active'; 
    }else{
    $barClass='submit-active';
    }
    if($search_style == 'default'){ ?>
    <div id='thaps-search-box' class="thaps-search-box  default <?php echo esc_attr($barClass);?> ">
    <form class="thaps-search-form" action='<?php echo esc_url( home_url( '/'  ) ); ?>' id='thaps-search-form'  method='get'>
    <div class="thaps-from-wrap">
    <?php
    if(th_advance_product_search()->get_option('show_submit' )=='0'){
    th_advance_product_search_icon_style_svg('icon-style','');
    } ?>
     <input id='thaps-search-autocomplete-<?php echo esc_attr($unique_id); ?>' name='s' placeholder='<?php echo esc_attr(th_advance_product_search()->get_option( 'placeholder_text' ));?>' class="thaps-search-autocomplete thaps-form-control" value='<?php echo esc_attr(get_search_query()); ?>' type='text' title='<?php echo esc_attr_x( 'Search', 'label', 'th-advance-product-search' ); ?>' />
    <?php if(th_advance_product_search()->get_option( 'show_loader' )=='0'){ ?>     
    <div class="thaps-preloader"></div> 
    <?php } ?>
    <?php
    if(th_advance_product_search()->get_option( 'show_submit' )=='1'){?>
    <button id='thaps-search-button' value="<?php echo esc_attr_x( 'Submit','submit button', 'th-advance-product-search' ); ?>" type='submit'>  
    <?php if(th_advance_product_search()->get_option( 'level_submit' )!==''){
        echo esc_html(th_advance_product_search()->get_option( 'level_submit' ));
    }else{ 
        th_advance_product_search_icon_style_svg('icon-style', '');
    }?>
    </button> <?php }
    ?>
    <input type="hidden" name="post_type" value="<?php echo esc_attr($type);?>" />
    <span class="label label-default" id="selected_option"></span>
    </div>    
    </form>
    </div>
    <?php 

}elseif($search_style == 'bar'){ ?>
<div id='thaps-search-box' class="thaps-search-box bar_style">
<form class="thaps-search-form" action='<?php echo esc_url( home_url( '/'  ) ); ?>' id='thaps-search-form'  method='get'>
<div class="thaps-from-wrap">
   <button id='thaps-search-button' value="<?php echo esc_attr_x( 'Submit','submit button', 'th-advance-product-search' ); ?>" type='submit'>  
  <?php th_advance_product_search_icon_style_svg('icon-style','');?>
</button>
   <input id='thaps-search-autocomplete-<?php echo esc_attr($unique_id); ?>' name='s' placeholder='<?php echo esc_attr(th_advance_product_search()->get_option( 'placeholder_text' ));?>' class="thaps-search-autocomplete thaps-form-control" value='<?php echo esc_attr(get_search_query()); ?>' type='text' title='<?php echo esc_attr_x( 'Search', 'label', 'th-advance-product-search' ); ?>' />
   <?php if(th_advance_product_search()->get_option( 'show_loader' )=='0'){ ?> 
   <div class="thaps-preloader"></div>
   <?php } ?>
        <input type="hidden" name="post_type" value="<?php echo esc_attr($type);?>" />
        <span class="label label-default" id="selected_option"></span>
      </div>
 </form>
 </div>
<?php }elseif($search_style == 'icon'){ ?>
<div id='thaps-search-box' class="thaps-search-box icon_style">
<?php th_advance_product_search_icon_style_svg('click-icon','');?>
<div class="thaps-icon-arrow"></div>
<form class="thaps-search-form" action='<?php echo esc_url( home_url( '/'  ) ); ?>' id='thaps-search-form'  method='get'>
<div class="thaps-from-wrap">
  <?php th_advance_product_search_icon_style_svg('icon-style','');?>
  <input id='thaps-search-autocomplete-<?php echo esc_attr($unique_id); ?>' name='s' placeholder='<?php echo esc_attr(th_advance_product_search()->get_option( 'placeholder_text' ));?>' class="thaps-search-autocomplete thaps-form-control" value='<?php echo esc_attr(get_search_query()); ?>' type='text' title='<?php echo esc_attr_x( 'Search', 'label', 'th-advance-product-search' ); ?>' />
  <?php if(th_advance_product_search()->get_option( 'show_loader' )=='0'){ ?> 
  <div class="thaps-preloader"></div>
  <?php } ?>
        <input type="hidden" name="post_type" value="<?php echo esc_attr($type);?>" />
        <span class="label label-default" id="selected_option"></span>
      </div>
 </form> 
</div>
<?php }elseif($search_style == 'flexi'){ 
if(wp_is_mobile()){    
?>
<div id='thaps-search-box' class="thaps-search-box icon_style flexible-style">
    <button id='thaps-search-button' value="<?php echo esc_attr_x( 'Submit','submit button', 'th-advance-product-search' ); ?>" type='submit'>  
<?php th_advance_product_search_icon_style_svg('click-icon','');?>
</button>
<div class="thaps-icon-arrow" style=""></div>
<form class="thaps-search-form" action='<?php echo esc_url( home_url( '/'  ) ); ?>' id='thaps-search-form'  method='get'>
<div class="thaps-from-wrap">
  <?php th_advance_product_search_icon_style_svg('icon-style','');?>
  <input id='thaps-search-autocomplete-<?php echo esc_attr($unique_id); ?>' name='s' placeholder='<?php echo esc_attr(th_advance_product_search()->get_option( 'placeholder_text' ));?>' class="thaps-search-autocomplete thaps-form-control" value='<?php echo esc_attr(get_search_query()); ?>' type='text' title='<?php echo esc_attr_x( 'Search', 'label', 'th-advance-product-search' ); ?>' />
  <?php if(th_advance_product_search()->get_option( 'show_loader' )=='0'){ ?>
  <div class="thaps-preloader"></div>
  <?php } ?>
  <input type="hidden" name="post_type" value="<?php echo esc_attr($type);?>" />
  <span class="label label-default" id="selected_option"></span>
 </div>
 </form> 
 </div>
 <?php } else { ?>
<div id='thaps-search-box' class="thaps-search-box bar_style flexible-style">
<form class="thaps-search-form" action='<?php echo esc_url( home_url( '/'  ) ); ?>' id='thaps-search-form'  method='get'>
<div class="thaps-from-wrap">
  <button id='thaps-search-button' value="<?php echo esc_attr_x( 'Submit','submit button', 'th-advance-product-search' ); ?>" type='submit'>  
<?php th_advance_product_search_icon_style_svg('click-icon','');?>
</button>
  <input id='thaps-search-autocomplete-<?php echo esc_attr($unique_id); ?>' name='s' placeholder='<?php echo esc_attr(th_advance_product_search()->get_option( 'placeholder_text' ));?>' class="thaps-search-autocomplete thaps-form-control" value='<?php echo esc_attr(get_search_query()); ?>' type='text' title='<?php echo esc_attr_x( 'Search', 'label', 'th-advance-product-search' ); ?>' />
  <?php if(th_advance_product_search()->get_option( 'show_loader' )=='0'){ ?>
  <div class="thaps-preloader"></div>
  <?php } ?>
        <input type="hidden" name="post_type" value="<?php echo esc_attr($type);?>" />
        <span class="label label-default" id="selected_option"></span>
      </div>
 </form>
 </div>
<?php }}    
return ob_get_clean();  

}