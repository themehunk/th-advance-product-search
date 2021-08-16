<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if(th_advance_product_search()->get_option( 'show_submit' )=='0'){
$barClass='submit-no-active';
}else{
$barClass='submit-active';
}
?>
<div id='thaps-search-box' class="thaps-search-box  <?php echo esc_attr($barClass);?>">
<form class="thaps-search-form" action='<?php echo esc_url( home_url( '/'  ) ); ?>' id='thaps-search-form'  method='get'>
<div class="thaps-from-wrap">
<?php if(th_advance_product_search()->get_option( 'show_submit' )=='0'){?>
  <svg xmlns="http://www.w3.org/2000/svg" fill="#111" viewBox="0 0 30 30" width="23px" height="23px"><path d="M 13 3 C 7.4889971 3 3 7.4889971 3 13 C 3 18.511003 7.4889971 23 13 23 C 15.396508 23 17.597385 22.148986 19.322266 20.736328 L 25.292969 26.707031 A 1.0001 1.0001 0 1 0 26.707031 25.292969 L 20.736328 19.322266 C 22.148986 17.597385 23 15.396508 23 13 C 23 7.4889971 18.511003 3 13 3 z M 13 5 C 17.430123 5 21 8.5698774 21 13 C 21 17.430123 17.430123 21 13 21 C 8.5698774 21 5 17.430123 5 13 C 5 8.5698774 8.5698774 5 13 5 z"/></svg>
<?php } ?>
   <input id='thaps-search-autocomplete' name='s' placeholder='<?php echo esc_attr(th_advance_product_search()->get_option( 'placeholder_text' ));?>' class="thaps-form-control" value='<?php echo get_search_query(); ?>' type='text' title='<?php echo esc_attr_x( 'Search for:', 'label', 'th-advance-product-search' ); ?>' />
  <?php if(th_advance_product_search()->get_option( 'show_loader' )=='0'){ ?> 
   <div class="thaps-preloader"></div>
  <?php } ?>
  <?php
  if(th_advance_product_search()->get_option( 'show_submit' )=='1'){?>
    <button id='thaps-search-button' value="<?php echo esc_attr_x( 'Submit','submit button', 'th-advance-product-search' ); ?>" type='submit'>  
   <?php if(th_advance_product_search()->get_option( 'level_submit' )!==''){
        echo esc_html__(th_advance_product_search()->get_option( 'level_submit' ));
   }else{ ?>
     <svg xmlns="http://www.w3.org/2000/svg" fill="#fff" viewBox="0 0 30 30" width="23px" height="23px"><path d="M 13 3 C 7.4889971 3 3 7.4889971 3 13 C 3 18.511003 7.4889971 23 13 23 C 15.396508 23 17.597385 22.148986 19.322266 20.736328 L 25.292969 26.707031 A 1.0001 1.0001 0 1 0 26.707031 25.292969 L 20.736328 19.322266 C 22.148986 17.597385 23 15.396508 23 13 C 23 7.4889971 18.511003 3 13 3 z M 13 5 C 17.430123 5 21 8.5698774 21 13 C 21 17.430123 17.430123 21 13 21 C 8.5698774 21 5 17.430123 5 13 C 5 8.5698774 8.5698774 5 13 5 z"/></svg>
  <?php  }?></button> <?php }
    ?>
        <input type="hidden" name="post_type" value="product" />
        <span class="label label-default" id="selected_option"></span>
      </div>
    </form>
 </div> 