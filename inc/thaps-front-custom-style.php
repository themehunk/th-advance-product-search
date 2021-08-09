<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

/***Front Custom Style********/

function th_advance_product_search_style($thaps_frnt_custom_css=''){
// max width search bar
$max_width_bar = esc_html(th_advance_product_search()->get_option( 'set_form_width' ));
if($max_width_bar!==''){
$thaps_frnt_custom_css.=".thaps-search-box{max-width:{$max_width_bar}px;}";
}else{
$thaps_frnt_custom_css.=".thaps-search-box{max-width:100%}";
}


if(th_advance_product_search()->get_option( 'level_submit' )!==''){
$thaps_frnt_custom_css.="
	#thaps-search-button {
       width: auto;
    font-size: 16px;
    padding: 0px 1rem;
}";
}



return $thaps_frnt_custom_css;
}