<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'TH_Advancde_Product_Search_Functions' ) ):

	class TH_Advancde_Product_Search_Functions {

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
								'title'   => esc_html__( 'Enable Submit Button', 'th-variation-swatches' ),
								'desc'    => '',
								'default' => true
							),

							array(
								'id'      => 'level_submit',
								'type'    => 'text',
								'title'   => esc_html__( 'Submit Button Level', 'th-variation-swatches' ),
								
							),
							array(
								'id'      => 'placeholder_text',
								'type'    => 'text',
								'title'   => esc_html__( 'Placeholder Text', 'th-variation-swatches' ),
								
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
								'title'   => esc_html__( 'Enable loader', 'th-variation-swatches' ),
								'desc'    => '',
								'default' => false
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
TH_Advancde_Product_Search_Functions::get_instance();