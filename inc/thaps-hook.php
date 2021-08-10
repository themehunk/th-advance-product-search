 <?php 
add_action( 'wp_ajax_thaps_ajax_get_search_value', 'thaps_ajax_get_search_value' );
add_action( 'wp_ajax_nopriv_thaps_ajax_get_search_value','thaps_ajax_get_search_value' );
/*************************/
// search result function
/*************************/
function thaps_ajax_get_search_value(){
         $limit = 5;
         if (isset($_REQUEST['match']) && $_REQUEST['match'] != '') {
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
             if (!empty($results->posts)) {
              foreach (array_slice($results->posts,0,$limit) as $result) {
                $product = wc_get_product($result->ID);
                $items['suggestions'][] = array(
                  'value'  => $result->post_title,
                  'title'   => $result->post_title,
                  'id'      => $result->ID,
                  'url'    => get_permalink($result->ID),
                  'imgsrc' => wp_get_attachment_url($product->get_image_id()),
                  'price'    => $product->get_price_html(),
                );
              }

              if($limit < $count){
                $moreproduct = array(
                    'id' => 'more-result',
                    'value' => '',
                    'text'  => esc_html__('See more product...','thaps'),
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
                  'value'  => esc_html__('No Result','thaps'),
              );
            }
            echo json_encode($items);
            die();
         }

}