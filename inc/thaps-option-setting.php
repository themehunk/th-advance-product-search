<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'TH_Advancde_Product_Search_Options' ) ):

	class TH_Advancde_Product_Search_Options {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
        /**
		 * Constructor
		 */
		public function __construct(){
           add_action( 'init', array( $this,'thaps_option_settings'), 2 );
		}

		public function thaps_option_settings(){

            th_advance_product_search()->add_setting(
			'integration', esc_html__( 'Inegration', 'th-advance-product-search' ), apply_filters(
			'thaps_integration_settings_section', array(
				array(
					'title'  => esc_html__( 'How to add search bar in your theme?', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_integration_setting_fields', array(
							array(
								'id'      => 'how-to-integrate',
								'type'    => 'html',
								'title'   => esc_html__( 'How To A dd', 'th-advance-product-search' ),
							),	
						)
					)
				 )
			  )
		    ),apply_filters( 'thaps_integration_settings_default_active', true )
		  );
          th_advance_product_search()->add_setting(
			'search-bar', esc_html__( 'Search Bar', 'th-advance-product-search' ), apply_filters(
			'thaps_search_bar_settings_section', array(
				array(
					'title'  => esc_html__( 'Basic', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_search_bar_setting_fields', array(
							array(
								'id'      => 'set_autocomplete_length',
								'type'    => 'number',
								'title'   => esc_html__( 'Minimum Cahracter', 'th-advance-product-search' ),
								
								'desc'    => esc_html__( 'Min characters to show autocomplete', 'th-advance-product-search' ),
								'default' => 3,
								'min'     => 1,
								'max'     => 10,
							),
							array(
								'id'      => 'set_form_width',
								'type'    => 'number',
								'title'   => esc_html__( 'Max Width', 'th-advance-product-search' ),
								
								'desc'    => esc_html__( 'To set 100% width leave blank', 'th-advance-product-search' ),
								'default' => 550,
								'min'     => 1,
								'max'     => 2400,
								'suffix'  => 'px'
							),	
							array(
								'id'      => 'show_submit',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Submit Button', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => true
							),

							array(
								'id'      => 'level_submit',
								'type'    => 'text',
								'title'   => esc_html__( 'Submit Button Level', 'th-advance-product-search' ),
								'default' => esc_html__( 'Submit', 'th-advance-product-search' ),
								
							),
							array(
								'id'      => 'placeholder_text',
								'type'    => 'text',
								'title'   => esc_html__( 'Placeholder Text', 'th-advance-product-search' ),
								'default' => esc_html__('Product Search','th-advance-product-search'),
								
							),
						)
					)
				 ),
				array(
					'title'  => esc_html__( 'Loader', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_search_bar_setting_fields', array(
							array(
								'id'      => 'show_loader',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Disable loader', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => false
							    ),	
						
						)
					)
				 ),

			   )
		     ),
		   );
          th_advance_product_search()->add_setting(
			'autosetting', esc_html__( 'Search Setting', 'th-advance-product-search' ), apply_filters(
			'thaps_autosetting_section', array(
				array(
					'title'  => esc_html__( 'Search Autocomplete Settings', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_autosetting_fields', array(
							array(
								'id'      => 'select_srch_type',
								'type'    => 'select',
								'title'   => esc_html__( 'Select Search Type', 'th-advance-product-search' ),
								'default' => 'product_srch',
								'options' => array(
									'post_srch'   => esc_html__( 'Post', 'th-advance-product-search-pro' ),
									'product_srch' => esc_html__( 'Product', 'th-advance-product-search-pro' ),
									'page_srch'  => esc_html__( 'Page', 'th-advance-product-search-pro' )
								)
								
							),
							array(
								'id'      => 'result_length',
								'type'    => 'number',
								'title'   => esc_html__( 'Limit', 'th-advance-product-search' ),
								
								'desc'    => esc_html__( 'Show Search Result', 'th-advance-product-search' ),
								'default' => 5,
								'min'     => 1,
								'max'     => 100,
							),
							array(
								'id'      => 'no_reult_label',
								'type'    => 'text',
								'title'   => esc_html__( 'No Result label', 'th-advance-product-search' ),
								'default' => esc_html__( 'No reult found', 'th-advance-product-search' ),
								
							),
							array(
								'id'      => 'more_reult_label',
								'type'    => 'text',
								'title'   => esc_html__( 'More Result label', 'th-advance-product-search' ),
								'default' => esc_html__( 'See more product..', 'th-advance-product-search' ),
								
							),
						  array(
								'id'      => 'enable_group_heading',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Group Heading', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => true
							    ),	
						   array(
								'id'      => 'show_category_in',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Show Category', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => false
							    ),
						   array(
								'id'      => 'desc_excpt_length',
								'type'    => 'number',
								'title'   => esc_html__( 'Description Length', 'th-advance-product-search' ),
								
								'desc'    => esc_html__( 'Given a Excerpt length', 'th-advance-product-search' ),
								'default' => 60,
								'min'     => 1,
								'max'     => 500,
							),
							
						)
					)
				 ),array(
					'title'  => esc_html__( 'Product', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_product_setting_fields', array(
								array(
								'id'      => 'enable_product_image',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Product Image', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => true
							    ),
							array(
								'id'      => 'enable_product_price',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Product price', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => true
							    ),
							array(
								'id'      => 'enable_product_desc',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Product Description', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => false
							    ),
							array(
								'id'      => 'enable_product_sku',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Product SKU', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => false
							    ),
							array(
								'id'      => 'exclude_product_sku',
								'type'    => 'text',
								'title'   => esc_html__( 'Exclude Product', 'th-advance-product-search' ),
								'desc'    => esc_html__( 'Exclude Product by SKU ID seperated by " , "', 'th-advance-product-search' ),
								'default' =>  false,
								
							),
							
						)
					)
				 ),
				 array(
					'title'  => esc_html__( 'Post', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_post_setting_fields', array(
								array(
								'id'      => 'enable_post_image',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Post Image', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => true
							    ),
							
							array(
								'id'      => 'enable_post_desc',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Post Description', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => false
							    ),
							
							
						)
					)
				 ),
				 array(
					'title'  => esc_html__( 'Pages', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_pages_setting_fields', array(
								array(
								'id'      => 'enable_page_image',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Page Image', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => true
							    ),
							
							array(
								'id'      => 'enable_page_desc',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Page Description', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => false
							    ),
							
							
						)
					)
				 ),
			  )
		    )
		  );

		 th_advance_product_search()->add_setting(
					'style', esc_html__( 'Style', 'th-advance-product-search' ), apply_filters(
					'thaps_style_settings_section', array(
						array(
							'title'  => esc_html__( 'Search Bar', 'th-advance-product-search' ),
							'fields' => apply_filters(
								'thaps_style_settings_fields', array(
									array(
										'id'      => 'bar_bg_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Background Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'bar_brdr_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Border Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'bar_text_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Text Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'bar_button_bg_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Button BG Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'bar_button_txt_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Button Text Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'bar_button_hvr_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Button Hover Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
								)
							)
						 ),
						array(
							'title'  => esc_html__( 'Suggestion Box', 'th-advance-product-search' ),
							'fields' => apply_filters(
								'thaps_style_settings_fields', array(
									array(
										'id'      => 'sus_bg_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Background Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'sus_hglt_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Highlight Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'sus_slect_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Selected Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'sus_brdr_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Border Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'sus_grphd_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Group Title Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'sus_title_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Title Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),

									array(
										'id'      => 'sus_text_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Text Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									
								)
							)
						 ),
					  )
				),
			);

		}

	}
endif;	
TH_Advancde_Product_Search_Options::get_instance();