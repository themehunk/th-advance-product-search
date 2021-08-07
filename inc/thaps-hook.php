 <?php 
add_action( 'wp_ajax_thaps_ajax_get_search_value', 'thaps_ajax_get_search_value' );
add_action( 'wp_ajax_nopriv_thaps_ajax_get_search_value','thaps_ajax_get_search_value' );
/*************************/
// search result function
/*************************/
function thaps_ajax_get_search_value(){
         if (isset($_REQUEST['match']) && $_REQUEST['match'] != '') {
             $match_ = sanitize_text_field($_REQUEST['match']);
              $results = new WP_Query(array(
              'post_type'     => 'product',
              'post_status'   => 'publish',
              'nopaging'      => true,
              'posts_per_page' => 100,
              's'             => $match_,
            ));
             $items = array();
             if (!empty($results->posts)) {
              foreach ($results->posts as $result) {
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
            }else{
                $items['suggestions'][] = array(
                  'value'  => esc_html__('No Result','thaps'),
              );
            }
            echo json_encode($items);
            die();
         }

}