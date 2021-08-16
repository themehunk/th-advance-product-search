 <?php 
add_action( 'wp_ajax_thaps_ajax_get_search_value', 'thaps_ajax_get_search_value' );
add_action( 'wp_ajax_nopriv_thaps_ajax_get_search_value','thaps_ajax_get_search_value' );
/*************************/
// search result function
/*************************/
function thaps_ajax_get_search_value(){
        //setting value
        $select_srch_type = esc_html(th_advance_product_search()->get_option( 'select_srch_type' ));

        $limit =  esc_html(th_advance_product_search()->get_option( 'result_length' ));
        $no_reult_label =  esc_html(th_advance_product_search()->get_option( 'no_reult_label' ));
        $more_reult_label =  esc_html(th_advance_product_search()->get_option( 'more_reult_label' ));

        $enable_group_heading =  esc_html(th_advance_product_search()->get_option( 'enable_group_heading' ));

        $enable_product_image =  esc_html(th_advance_product_search()->get_option( 'enable_product_image' ));
        $enable_product_price =  esc_html(th_advance_product_search()->get_option( 'enable_product_price' ));
        $enable_product_desc =  esc_html(th_advance_product_search()->get_option( 'enable_product_desc' ));
        $enable_product_sku =  esc_html(th_advance_product_search()->get_option( 'enable_product_sku' ));

        $show_category_in =  esc_html(th_advance_product_search()->get_option( 'show_category_in' ));

        $category_hd = __('Category','th-advance-product-search');

        $product_hd  = __('Product','th-advance-product-search');
        
        

        // Post
        $post_hd  = __('Post','th-advance-product-search');
        $enable_post_image =  esc_html(th_advance_product_search()->get_option( 'enable_post_image' ));
        $enable_post_desc =  esc_html(th_advance_product_search()->get_option( 'enable_post_desc' ));
        // Page
        $page_hd  = __('Pages','th-advance-product-search');
        $enable_page_image =  esc_html(th_advance_product_search()->get_option( 'enable_page_image' ));
        $enable_page_desc =  esc_html(th_advance_product_search()->get_option( 'enable_page_desc' ));
   

        /*********************/
        //fetch product result
        /*********************/
         if (isset($_REQUEST['match']) && $_REQUEST['match'] != ''){
              $match_ = sanitize_text_field($_REQUEST['match']);

            if ($select_srch_type=='product_srch'){ 
              $results = new WP_Query(array(
              'post_type'     => 'product',
              'post_status'   => array('publish'),
              'nopaging'      => true,
              'posts_per_page' => 100,
              's'             => $match_,
             ));
             $count = ( isset( $results->posts ) ? count( $results->posts ) : 0 );
             $items = array();
             // category show 
             if($show_category_in == true){
                if($enable_group_heading == true){
                    $items['suggestions'][] = thaps_show_heading($category_hd);
               }
               $categories = thaps_ajax_product_getCategories( $match_, $limit );
               if(!empty($categories)){
               foreach ( $categories as $result ){
                    $items['suggestions'][] = $result;
                }
              }else{
                    $items['suggestions'][] = thaps_show_no_result($no_reult_label);
                }
             
              }

              if (!empty($results->posts)){
                  if($enable_group_heading == true){
                  $items['suggestions'][] = thaps_show_heading($product_hd);
                  }

              foreach (array_slice($results->posts,0,$limit) as $result){
                $product = wc_get_product($result->ID);
                $r = array(
                  'value'   => $result->post_title,
                  'title'   => $result->post_title,
                  'id'      => $result->ID,
                  'url'     => get_permalink($result->ID), 
                  'type'    => 'product', 
                );
                if ( $enable_product_image == true) {
                        $r['imgsrc'] = wp_get_attachment_url($product->get_image_id());
                }
                if ( $enable_product_price == true) {
                        $r['price'] = $product->get_price_html();
                }
                if ( $enable_product_sku == true) {
                        $r['sku'] = $product->get_sku();
                }
                if ( $enable_product_desc == true) {
                        $r['desc'] = $product->get_short_description();
                }

                $items['suggestions'][] = $r;
              }

                if($limit < $count){
                    $items['suggestions'][] = thaps_show_more($count, $more_reult_label, $match_, $select_srch_type);
                }
                 
               
             
            }else{
                $items['suggestions'][] = thaps_show_no_result($no_reult_label);
            }
        //search type product close 
        }elseif($select_srch_type=='post_srch'){
            $results = new WP_Query(array(
              'post_type'     => 'post',
              'post_status'   => array('publish'),
              'nopaging'      => true,
              'posts_per_page' => 100,
              's'             => $match_,
             ));  
             $count = ( isset( $results->posts ) ? count( $results->posts ) : 0 );
             $items = array();   

             // category show 
             if($show_category_in == true){
                if($enable_group_heading == true){
                  $items['suggestions'][] = thaps_show_heading($category_hd);
               }
               $categories = thaps_ajax_post_getCategories( $match_, $limit );
               if(!empty($categories)){
               foreach ( $categories as $result ){
                    $items['suggestions'][] = $result;
                }
              }else{
                    $items['suggestions'][] = thaps_show_no_result($no_reult_label);
                }
             
              }        
             if (!empty($results->posts)){
             if($enable_group_heading == true){    
             $items['suggestions'][] = thaps_show_heading($post_hd);
             }
             foreach (array_slice($results->posts,0,$limit) as $result){
                $r = array(
                  'value'   => $result->post_title,
                  'title'   => $result->post_title,
                  'id'      => $result->ID,
                  'url'     => get_permalink($result->ID), 
                  'type'    => 'post', 
                ); 
              if ( $enable_post_image == true) {
               $post_imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($result->ID),'thaps-thumb-img');
                if ( is_array( $post_imgsrc ) && ! empty( $post_imgsrc[0] ) ) {
                    $src = $post_imgsrc[0];
                    $r['imgsrc'] = $src;
                 }
                }
              if ( $enable_post_desc == true) {  
                $r['desc'] = thaps_excerpt_shw($result->ID);
              }

                $items['suggestions'][] = $r;
                
               
             }
             if($limit < $count){
                    $items['suggestions'][] = thaps_show_more($count, $more_reult_label, $match_, $select_srch_type);
                }
             }else{
                $items['suggestions'][] = thaps_show_no_result($no_reult_label);
             }
          //search type product close 
         }elseif($select_srch_type=='page_srch'){
             $results = new WP_Query(array(
              'post_type'     => 'page',
              'post_status'   => array('publish'),
              'nopaging'      => true,
              'posts_per_page' => 100,
              's'             => $match_,
             ));  
             $count = ( isset( $results->posts ) ? count( $results->posts ) : 0 );
             $items = array();   

                   
             if (!empty($results->posts)){
             if($enable_group_heading == true){   
               $items['suggestions'][] = thaps_show_heading($page_hd);
             }
             foreach (array_slice($results->posts,0,$limit) as $result){
                $r = array(
                  'value'   => $result->post_title,
                  'title'   => $result->post_title,
                  'id'      => $result->ID,
                  'url'     => get_permalink($result->ID), 
                  'type'    => 'page', 
                ); 
                if ( $enable_page_image == true) { 
               $post_imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($result->ID),'thaps-thumb-img');

                if ( is_array( $post_imgsrc ) && ! empty( $post_imgsrc[0] ) ) {
                    $src = $post_imgsrc[0];
                    $r['imgsrc'] = $src;
                }
               }
               if ( $enable_page_desc == true) {  
                $r['desc'] = thaps_excerpt_shw($result->ID);
               }


                $items['suggestions'][] = $r;
                
               
             }
             if($limit < $count){
                    $items['suggestions'][] = thaps_show_more($count, $more_reult_label, $match_, $select_srch_type);
                }
             }else{
                $items['suggestions'][] = thaps_show_no_result($no_reult_label);
             }


          //search type product close 
         }
            
        echo json_encode($items);

            die();
         }
    }
/**************/
//Heading Show
/**************/
function thaps_show_heading($heading_label){
      return  array(
                    'value'  => $heading_label,
                    'type'   => 'heading',
                );
}
/**************/
//No Reult Show
/**************/
function thaps_show_no_result($no_reult_label){
      return  array(
                   'value'  => $no_reult_label,
                   'type'   => 'no-result',
                );
}
/**********************/
//Get Product Category
/*********************/
function thaps_ajax_product_getCategories( $keyword, $limit){
        $results = array();
        $args = array(
            'taxonomy' => 'product_cat',
        );
        $productCategories = get_terms( 'product_cat', $args );
        $keywordUnslashed = wp_unslash( $keyword );
  
        $i = 0;
        foreach ( $productCategories as $cat ) {

            if ( $i < $limit ) {
                $catName = html_entity_decode( $cat->name );
                $pos = strpos( mb_strtolower( $catName ), mb_strtolower( $keywordUnslashed ) );
                if ( $pos !== false ) {
                    $results[$i] = array(
                        'term_id'     => $cat->term_id,
                        'taxonomy'    => 'product_cat',
                        'value'       => $catName,
                        'url'         => get_term_link( $cat, 'product_cat' ),
                        'type'        => 'taxonomy-product-cat',
                    );
                    $i++;
                }
            
            }
        
        }

        return $results;
}

/**********************/
//Get Post Category
/*********************/
function thaps_ajax_post_getCategories( $keyword, $limit){
        $results = array();
        $args = array(
            'taxonomy' => 'category',
        );
        $productCategories = get_terms( 'category', $args );
        $keywordUnslashed = wp_unslash( $keyword );
  
        $i = 0;
        foreach ( $productCategories as $cat ) {

            if ( $i < $limit ) {
                $catName = html_entity_decode( $cat->name );
                $pos = strpos( mb_strtolower( $catName ), mb_strtolower( $keywordUnslashed ) );
                if ( $pos !== false ) {
                    $results[$i] = array(
                        'term_id'     => $cat->term_id,
                        'taxonomy'    => 'category',
                        'value'       => $catName,
                        'url'         => get_term_link( $cat, 'category' ),
                        'type'        => 'taxonomy-post-cat',
                    );
                    $i++;
                }
            
            }
        
        }

        return $results;
}
/*************/
//Show More
/*************/
function thaps_show_more( $count, $more_reult_label, $match, $select_srch_type){
            
                $moreproduct = array(
                    'id'    => 'more-result',
                    'value' => '',
                    'text'  => $more_reult_label,
                    'total' => $count,
                    'type'  => 'more_item',
                );

                if($select_srch_type == 'product_srch'){
                   $moreproduct['url'] = add_query_arg( array(
                    's'         => $match,
                    'post_type' => 'product',
                ), home_url() );
                }elseif($select_srch_type == 'post_srch'){
                     $moreproduct['url'] = add_query_arg( array(
                    's'         => $match,
                    'post_type' => 'post',
                ), home_url() );
                }elseif($select_srch_type == 'page_srch'){
                    $moreproduct['url'] = add_query_arg( array(
                    's'         => $match,
                    'post_type' => 'page',
                ), home_url() );
                }

                return $moreproduct; 
         
}

function thaps_excerpt_shw( $id ){
$excerpt = get_the_excerpt($id);
$excerpt = substr($excerpt, 0, 60);
$result  = substr($excerpt, 0, strrpos($excerpt, ' '));
return $result . '&nbsp;...';
}
