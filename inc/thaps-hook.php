 <?php 
add_action( 'wp_ajax_thaps_ajax_get_search_value', 'thaps_ajax_get_search_value' );
add_action( 'wp_ajax_nopriv_thaps_ajax_get_search_value','thaps_ajax_get_search_value' );
/*************************/
// search result function
/*************************/
function thaps_ajax_get_search_value(){
        //setting value
        $limit =  esc_html(th_advance_product_search()->get_option( 'result_length' ));
        $no_reult_label =  esc_html(th_advance_product_search()->get_option( 'no_reult_label' ));
        $more_reult_label =  esc_html(th_advance_product_search()->get_option( 'more_reult_label' ));
        $enable_product_image =  esc_html(th_advance_product_search()->get_option( 'enable_product_image' ));
        $enable_product_price =  esc_html(th_advance_product_search()->get_option( 'enable_product_price' ));
        $enable_product_desc =  esc_html(th_advance_product_search()->get_option( 'enable_product_desc' ));
        $enable_product_sku =  esc_html(th_advance_product_search()->get_option( 'enable_product_sku' ));

        /*********************/
        //fetch product result
        /*********************/
         if (isset($_REQUEST['match']) && $_REQUEST['match'] != ''){
              $match_ = sanitize_text_field($_REQUEST['match']);
              $results = new WP_Query(array(
              'post_type'     => 'product',
              'post_status'   => 'publish',
              'nopaging'      => true,
              'posts_per_page' => 100,
              's'             => $match_,
            ));

             $count = ( isset( $results->posts ) ? count( $results->posts ) : 0 );
             $items = array();

            

             if (!empty($results->posts)){
              foreach (array_slice($results->posts,0,$limit) as $result){
                $product = wc_get_product($result->ID);
                $r = array(
                  'value'   => $result->post_title,
                  'title'   => $result->post_title,
                  'id'      => $result->ID,
                  'url'     => get_permalink($result->ID), 
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
                 $moreproduct = array(
                    'id'    => 'more-result',
                    'value' => '',
                    'text'  => $more_reult_label,
                    'total' => $count,
                    'url'   => add_query_arg( array(
                    's'         => $match_,
                    'post_type' => 'product',
                ), home_url() ),
                    'type'  => 'more_products',
                );


                 $items['suggestions'][] = $moreproduct; 
              }
             
            }else{
                $items['suggestions'][] = array(
                   'value'  => $no_reult_label,
                );
            }
            echo json_encode($items);
            die();
         }

}