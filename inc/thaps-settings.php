<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'TH_Advancde_Product_Search_Set' ) ):

	class TH_Advancde_Product_Search_Set {

        private $setting_name = 'th_advance_product_search';
		private $setting_reset_name = 'reset';
		private $theme_feature_name = 'th-advance-product-search';
		private $slug;
		private $plugin_class;
		private $defaults = array();
		private $fields = array();
		private $reserved_key = '';
		private $reserved_fields = array();
		
             public function __construct() {
             $this->settings_name   = apply_filters( 'thaps_settings_name', $this->setting_name );
             $this->fields          = apply_filters( 'thaps_settings', $this->fields );
             $this->reserved_key    = sprintf( '%s_reserved', $this->settings_name );
		     $this->reserved_fields = apply_filters( 'thaps_reserved_fields', array() );
 
             add_action( 'admin_menu', array( $this, 'add_menu' ) );
             add_action( 'init', array( $this, 'set_defaults' ), 8 );
             add_action( 'admin_init', array( $this, 'settings_init' ), 90 );
             add_action( 'admin_enqueue_scripts', array( $this, 'script_enqueue' ) );

             add_action('wp_ajax_thaps_form_setting', array($this, 'thaps_form_setting'));
			 add_action( 'wp_ajax_nopriv_thaps_form_setting', array($this, 'thaps_form_setting'));

            }
         public function script_enqueue(){
			
			wp_enqueue_media();
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script('imagesloaded');
			wp_enqueue_style( 'th-advance-product-search-admin', TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI. '/assets/css/admin.css', array(), TH_ADVANCE_PRODUCT_SEARCH_VERSION );
            
			wp_enqueue_script( 'wp-color-picker-alpha', TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI. '/assets/js/wp-color-picker-alpha.js', array('wp-color-picker'),true);
			wp_enqueue_script( 'wp-color-picker-alpha' );
            wp_enqueue_script( 'thaps-setting-script', TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI. '/assets/js/thaps-setting.js', array('jquery'),true);
			wp_localize_script(
				'thaps-setting-script', 'THAPSPluginObject', array(
					'media_title'   => esc_html__( 'Choose an Image', 'th-advance-product-search' ),
					'button_title'  => esc_html__( 'Use Image', 'th-advance-product-search' ),
					'add_media'     => esc_html__( 'Add Media', 'th-advance-product-search' ),
					'ajaxurl'       => esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
					'nonce'         => wp_create_nonce( 'thaps_plugin_nonce' ),
				)
			);
		}

        public function add_menu(){
						$page_title = esc_html__( 'Th Advance Product Search', 'th-advance-product-search' );
						$menu_title = esc_html__( 'TH Search', 'th-advance-product-search' );
						add_menu_page( $page_title, $menu_title, 'edit_theme_options', 'th-advance-product-search', array(
							$this,
							'settings_form'
						),  esc_url(TH_ADVANCE_PRODUCT_SEARCH_IMAGES_URI.'/icon.png'), 31 );



		 }
		 
		public function settings_form() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
		
			?>
			<div id="thaps" class="settings-wrap">
                
				<div class="top-wrap"><div id="logo"></div>
				  <h1><?php echo get_admin_page_title() ?></h1>
			     </div>
				<form method="post" action="" enctype="multipart/form-data" class="thaps-setting-form">
                 <input type="hidden" name="action" value="thaps_form_setting">
					<?php $this->options_tabs(); ?>
                     <div class="setting-wrap">
					 <div id="settings-tabs">
						<?php foreach ( $this->fields as $tab ):

							if ( ! isset( $tab['active'] ) ) {
								$tab['active'] = false;
							}
							$is_active = ( $this->get_last_active_tab() == $tab['id'] );

							?>

							<div id="<?php echo esc_attr($tab['id']); ?>"
								 class="settings-tab thaps-setting-tab"
								 style="<?php echo ! esc_attr($is_active) ? 'display: none' : '' ?>">
								 
								<?php foreach ( $tab['sections'] as $section ):

					        	$this->do_settings_sections( $tab['id'] . $section['id'] );

								endforeach; ?>
							</div>

						<?php endforeach; ?>
					</div>
					<?php
					$this->last_tab_input();
					
					?>
					<p class="submit thaps-button-wrapper">
						
						 <a onclick="return confirm('<?php esc_attr_e( 'Are you sure to reset current settings?', 'th-advance-product-search' ) ?>')" class="reset" href="<?php echo esc_url($this->reset_url()); ?>"><?php esc_html_e( 'Reset all', 'th-advance-product-search' ); ?>
						</a>
						 <button  disabled id="submit" class="button button-primary" value="<?php esc_html_e( 'Save Changes', 'th-advance-product-search' ) ?>"><span class="dashicons dashicons-image-rotate spin"></span><span><?php esc_html_e( 'Save Changes', 'th-advance-product-search' ) ?></span>
						 </button>
					</p> 

            </div>
				</form>
			</div>
			<?php
			
		}
	    public function thaps_form_setting(){  
	             if( isset($_POST['th_advance_product_search']) ){
	             	        $th_advance_product_search =  $_POST['th_advance_product_search']; 
	                      $sanitize_data_array = $this->thaps_form_sanitize($th_advance_product_search);
	                      update_option('th_advance_product_search',$sanitize_data_array);         
		            }
		            die();  
	    }
        
	    public function thaps_form_sanitize( $input ){
				$new_input = array();
				foreach ( $input as $key => $val ){
					$new_input[ $key ] = ( isset( $input[ $key ] ) ) ? sanitize_text_field( $val ) :'';
		   }
		   return $new_input;

	    }
		public function options_tabs() {
			?>
			<div class="nav-tab-wrapper wp-clearfix">
				<?php foreach ( $this->fields as $tabs ): ?>
					<a data-target="<?php echo esc_attr($tabs['id']); ?>"  class="thaps-setting-nav-tab nav-tab <?php echo esc_html($this->get_options_tab_css_classes( $tabs )); ?> " href="#<?php echo esc_attr($tabs['id']); ?>"><?php echo esc_html($tabs['title']); ?></a>
				<?php endforeach; ?>
			</div>
			<?php
		}

		private function get_last_active_tab() {
			$last_option_tab = '';
			$last_tab        = $last_option_tab;

			if ( isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ) {
				$last_tab = trim( sanitize_key($_GET['tab']) );
			}

			if ( $last_option_tab ) {
				$last_tab = $last_option_tab;
			}

			$default_tab = '';
			foreach ( $this->fields as $tabs ) {
				if ( isset( $tabs['active'] ) && $tabs['active'] ) {
					$default_tab = sanitize_key($tabs['id']);
					break;
				}
			}

			return ! empty( $last_tab ) ? esc_html( $last_tab ) : esc_html( $default_tab );

		}

		private function do_settings_sections( $page ) {
			global $wp_settings_sections, $wp_settings_fields;

			if ( ! isset( $wp_settings_sections[ $page ] ) ) {
				return;
			}

			foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
				if ( $section['title'] ) {
					echo "<h2>".esc_html($section['title'])."</h2>";	
				}

				if ( $section['callback'] ) {
					call_user_func( $section['callback'], $section );
				}

				if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
					continue;
				}

				echo '<table class="form-table">';
				$this->do_settings_fields( $page, $section['id'] );
				echo '</table>';
			}
		}

		private function last_tab_input() {
			printf( '<input type="hidden" id="_last_active_tab" name="%s[_last_active_tab]" value="%s">', $this->settings_name, $this->get_last_active_tab() );
		}

		private function get_options_tab_css_classes( $tabs ) {
			$classes = array();

			$classes[] = ( $this->get_last_active_tab() == $tabs['id'] ) ? 'nav-tab-active' : '';

			return implode( ' ', array_unique( apply_filters( 'get_options_tab_css_classes', $classes ) ) );
		}

		private function do_settings_fields( $page, $section ) {
			global $wp_settings_fields;

			if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
				return;
			}

			foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
				
				$custom_attributes = $this->array2html_attr( isset( $field['args']['attributes'] ) ? $field['args']['attributes'] : array() );

				$wrapper_id = ! empty( $field['args']['id'] ) ? esc_attr( $field['args']['id'] ) . '-wrapper' : '';
				$dependency = ! empty( $field['args']['require'] ) ? $this->build_dependency( $field['args']['require'] ) : '';

				printf( '<tr id="%s" %s %s>', $wrapper_id, $custom_attributes, $dependency );

				
					echo '<th scope="row" class="thaps-settings-label">';
					if ( ! empty( $field['args']['label_for'] ) ) {
						echo '<label for="' . esc_attr( $field['args']['label_for'] ) . '">' . esc_html($field['title']). '</label>';
					} else {
						echo esc_html($field['title']);
					}

					echo '</th>';
					echo '<td class="thaps-settings-field-content">';
					call_user_func( $field['callback'], $field['args'] );
					echo '</td>';
				
				   echo '</tr>';
			}
		}

        public function array2html_attr( $attributes, $do_not_add = array() ) {

			$attributes = wp_parse_args( $attributes, array() );

			if ( ! empty( $do_not_add ) and is_array( $do_not_add ) ) {
				foreach ( $do_not_add as $att_name ) {
					unset( $attributes[ $att_name ] );
				}
			}


			$attributes_array = array();

			foreach ( $attributes as $key => $value ) {

				if ( is_bool( $attributes[ $key ] ) and $attributes[ $key ] === true ) {
					return $attributes[ $key ] ? $key : '';
				} elseif ( is_bool( $attributes[ $key ] ) and $attributes[ $key ] === false ) {
					$attributes_array[] = '';
				} else {
					$attributes_array[] = $key . '="' . esc_attr($value) . '"';
				}
			}

			return implode( ' ', $attributes_array );
		}

		 private function build_dependency( $require_array ) {
			$b_array = array();
			foreach ( $require_array as $k => $v ) {
				$b_array[ '#' . $k . '-field' ] = $v;
			}

			return 'data-thapsdepends="[' . esc_attr( wp_json_encode( $b_array ) ) . ']"';
		}

		 public function make_implode_html_attributes( $attributes, $except = array( 'type', 'id', 'name', 'value' ) ) {
			$attrs = array();
			foreach ( $attributes as $name => $value ) {
				if ( in_array( $name, $except, true ) ) {
					continue;
				}
				$attrs[] = esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
			}

			return implode( ' ', array_unique( $attrs ) );
		}

		/***************/
		// Field call back function
		/***************/

		public function field_callback( $field ) {

			switch ( $field['type'] ) {
				case 'radio':
					$this->radio_field_callback( $field );
					break;

				case 'checkbox':
					$this->checkbox_field_callback( $field );
					break;

				case 'select':
					$this->select_field_callback( $field );
					break;

				case 'number':
					$this->number_field_callback( $field );
					break;

				case 'color':
					$this->color_field_callback( $field );
					break;

				case 'post_select':
					$this->post_select_field_callback( $field );
					break;

				case 'iframe':
					$this->iframe_field_callback( $field );
					break;

				case 'html':
					$this->html_field_callback( $field );
					break;
			    case 'file':
					$this->file_field_callback( $field );
					break;			

				default:
					$this->text_field_callback( $field );
					break;
			}
			do_action( 'thaps_settings_field_callback', $field );
		}

     
      public function checkbox_field_callback( $args ) {
               
			$value = wc_string_to_bool( $this->get_option( $args['id'] ) );

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';

			$html = sprintf( '<fieldset><label><input %1$s type="checkbox" id="%2$s-field" name="%4$s[%2$s]" value="%3$s" %5$s/> %6$s</label> %7$s</fieldset>', $attrs, $args['id'], true, $this->settings_name, checked( $value, true, false ), esc_attr( $args['desc'] ), $this->get_field_description( $args ) );

			echo $html;
		}
			public function radio_field_callback( $args ) {
		
			$options = apply_filters( "thaps_settings_{$args[ 'id' ]}_radio_options", $args['options'] );
			$value   = esc_attr( $this->get_option( $args['id'] ) );

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';


			$html = '<fieldset>';
			$html .= implode( '<br />', array_map( function ( $key, $option ) use ( $attrs, $args, $value ) {
				return sprintf( '<label><input %1$s type="radio"  name="%4$s[%2$s]" value="%3$s" %5$s/> %6$s</label>', $attrs, $args['id'], $key, $this->settings_name, checked( $value, $key, false ), $option );
			}, array_keys( $options ), $options ) );
			$html .= $this->get_field_description( $args );
			$html .= '</fieldset>';

			echo $html;
		}
		public function select_field_callback( $args ) {
			$options = apply_filters( "thaps_settings_{$args[ 'id' ]}_select_options", $args['options'] );
			$value   = esc_attr( $this->get_option( $args['id'] ) );
			$options = array_map( function ( $key, $option ) use ( $value ) {
				return "<option value='{$key}'" . selected( $key, $value, false ) . ">{$option}</option>";
			}, array_keys( $options ), $options );
			$size    = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';

			$html = sprintf( '<select %5$s class="%1$s-text" id="%2$s-field" name="%4$s[%2$s]">%3$s</select>', $size, $args['id'], implode( '', $options ), $this->settings_name, $attrs );
			$html .= $this->get_field_description( $args );

			echo $html;
		}
		public function get_field_description( $args ) {

			$desc = '';
			

			if ( ! empty( $args['desc'] ) ) {
				$desc .= sprintf( '<p class="description">%s</p>', $args['desc'] );
			} else {
				$desc .= '';
			}

			return ( ( $args['type'] === 'checkbox' ) ) ? '' : $desc;
		}
		public function post_select_field_callback( $args ) {

			$options = apply_filters( "thaps_settings_{$args[ 'id' ]}_post_select_options", $args['options'] );

			$value = esc_attr( $this->get_option( $args['id'] ) );

			$options = array_map( function ( $option ) use ( $value ) {
				return "<option value='{$option->ID}'" . selected( $option->ID, $value, false ) . ">$option->post_title</option>";
			}, $options );

			$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$html = sprintf( '<select class="%1$s-text" id="%2$s-field" name="%4$s[%2$s]">%3$s</select>', $size, $args['id'], implode( '', $options ), $this->settings_name );
			$html .= $this->get_field_description( $args );
			echo $html;
		}

		public function text_field_callback( $args ) {
			$value = esc_attr( $this->get_option( $args['id'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';

			$html = sprintf( '<input %5$s type="text" class="%1$s-text" id="%2$s-field" name="%4$s[%2$s]" value="%3$s"/>', $size, $args['id'], $value, $this->settings_name, $attrs );
			$html .= $this->get_field_description( $args );

			echo $html;
		}
		
        public function file_field_callback( $args ) {
        $value = esc_attr( $this->get_option( $args['id'] ) );
        $size = ( isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular' );
        $attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';
        $label = ( isset( $args['options']['button_label'] ) ? $args['options']['button_label'] : __( 'Choose File' ) );
        $html = sprintf( '<input %5$s type="text" class="%1$s-text" id="%2$s-field" name="%4$s[%2$s]" value="%3$s"/>', $size, $args['id'], $value, $this->settings_name, $attrs );
        $html .= '<input type="button" class="button thaps_upload_image_button ' . $this->prefix . 'browse" value="' . $label . '" />';
        $html .= $this->get_field_description( $args );
        echo  $html ;
      }



		public function iframe_field_callback( $args ) {
			$is_html = isset( $args['html'] );
			if ( $is_html ){
				$html = $args['html'];
			  } else {
				$screen_frame = esc_url( $args['screen_frame'] );
        $doc_link     = esc_url( $args['doc_link'] );
        $doc_text     = esc_html($args['doc-texti']);
				$width        = isset( $args['width'] ) ? $args['width'] : '100%';
				$height       = isset( $args['height'] ) ? $args['height'] : '100%';

        $html = sprintf( '<iframe width="%1s" height="%2s" src="%3s" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe><a target="_blank" href="%4s">%5s</a>',  $width, $height, $screen_frame ,$doc_link, $doc_text);

				$html .= $this->get_field_description( $args );
			}
			echo $html;
		}

		public function html_field_callback( $args ) {
         if($args[ 'id' ]=='how-to-integrate'):

			?>
			
		   <h4><?php _e( 'There are 3 easy ways to display the search bar in your theme', 'ajax-search-for-woocommerce' ); ?>: </h4>
			<ol>
				
				<li><?php printf( __( 'Using a shortcode - %s', 'th-advance-product-search' ), '<code>[th-aps]</code>' ); ?></li>
				<li><?php printf( __( 'As a widget - go to the %s and choose "TH Advance Product Search"', 'th-advance-product-search' ), '<a href="' . admin_url( 'widgets.php' ) . '" target="_blank">' . __( 'Widgets Screen', 'th-advance-product-search' ) . '</a>' ) ?>
				<li><?php printf( __( 'Using PHP - %s', 'th-advance-product-search' ), '<code>&lt;?php echo do_shortcode(\'[th-aps]\'); ?&gt;</code>' ); ?></li>
			</ol>

		<?php 		
			endif;
		}

		public function color_field_callback( $args ){
			$value = esc_attr( $this->get_option( $args['id'] ) );
			
			$alpha = isset( $args['alpha'] ) && $args['alpha'] === true ? ' data-alpha="true"' : '';
			$html  = sprintf( '<input type="text" %1$s class="thaps-color-picker" id="%2$s-field" name="%4$s[%2$s]" value="%3$s"  data-default-color="%3$s" />', $alpha, $args['id'], $value, $this->settings_name );
			$html  .= $this->get_field_description( $args );

			echo $html;
		}
		public function number_field_callback( $args ) {
			$value = esc_attr( $this->get_option( $args['id'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'small';

			$min    = isset( $args['min'] ) && ! is_null( $args['min'] ) ? 'min="' . $args['min'] . '"' : '';
			$max    = isset( $args['max'] ) && ! is_null( $args['max'] ) ? 'max="' . $args['max'] . '"' : '';
			$step   = isset( $args['step'] ) && ! is_null( $args['step'] ) ? 'step="' . $args['step'] . '"' : '';
			$suffix = isset( $args['suffix'] ) && ! is_null( $args['suffix'] ) ? ' <span>' . $args['suffix'] . '</span>' : '';

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';


			$html = sprintf( '<input %9$s type="number" class="%1$s-text" id="%2$s-field" name="%4$s[%2$s]" value="%3$s" %5$s %6$s %7$s /> %8$s', $size, $args['id'], $value, $this->settings_name, $min, $max, $step, $suffix, $attrs );
			$html .= $this->get_field_description( $args );

			echo $html;
		}


	//*********************************/	
    // add ,delete ,get , reset, option
    /**********************************/

    public function set_defaults() {
			foreach ( $this->fields as $tab_key => $tab ) {
				$tab = apply_filters( 'thaps_settings_tab', $tab );

				foreach ( $tab['sections'] as $section_key => $section ) {

					$section = apply_filters( 'thaps_settings_section', $section, $tab );

					$section['id'] = ! isset( $section['id'] ) ? $tab['id'] . '-section' : $section['id'];

					$section['fields'] = apply_filters( 'thaps_settings_fields', $section['fields'], $section, $tab );

					foreach ( $section['fields'] as $field ) {
						if ( isset( $field['pro'] ) ) {
							continue;
						}
						$field['default'] = isset( $field['default'] ) ? $field['default'] : null;
						$this->set_default( $field['id'], $field['type'], $field['default'] );
					}
				}
			}
		}


		public function sanitize_callback( $options ) {

			foreach ( $this->get_defaults() as $opt ) {
				if ( $opt['type'] === 'checkbox' && ! isset( $options[ $opt['id'] ] ) ){
					$options[ $opt['id'] ] = 0;
				}
			}

			return $options;
		}

		public function settings_init() {

			if ( $this->is_reset_all() ) {
				 $this->delete_settings();
				 wp_redirect(esc_url($this->settings_url()));
			}
              
		  register_setting( $this->settings_name, $this->settings_name, array( $this, 'sanitize_callback' ) );

			foreach ( $this->fields as $tab_key => $tab ) {

				$tab = apply_filters( 'thaps_settings_tab', $tab );

				foreach ( $tab['sections'] as $section_key => $section ) {

					$section = apply_filters( 'thaps_settings_section', $section, $tab );

					$section['id'] = ! isset( $section['id'] ) ? $tab['id'] . '-section-' . $section_key : $section['id'];

					// Adding Settings section id
					$this->fields[ $tab_key ]['sections'][ $section_key ]['id'] = $section['id'];

					add_settings_section( $tab['id'] . $section['id'], $section['title'], function () use ( $section ) {
						if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
							echo '<div class="inside">' . esc_html($section['desc']) . '</div>';
						}
					}, $tab['id'] . $section['id'] );

					$section['fields'] = apply_filters( 'thaps_settings_fields', $section['fields'], $section, $tab );

					foreach ( $section['fields'] as $field ) {

						$field['label_for'] = $field['id'] . '-field';
						$field['default']   = isset( $field['default'] ) ? $field['default'] : null;

						if ( $field['type'] == 'checkbox' || $field['type'] == 'radio' ) {
							unset( $field['label_for'] );
						}

						add_settings_field( $this->settings_name . '[' . $field['id'] . ']', $field['title'], array(
							$this,
							'field_callback'
						), $tab['id'] . $section['id'], $tab['id'] . $section['id'], $field );

					}
				}
			}
		}

		public function reset_url() {
			return add_query_arg( array( 'page' => 'th-advance-product-search', 'reset' => '' ), admin_url( 'admin.php' ) );
		}

		public function settings_url(){
			return add_query_arg( array( 'page' => 'th-advance-product-search' ), admin_url( 'admin.php' ) );
		}
        private function set_default( $key, $type, $value ) {
		$this->defaults[ $key ] = array( 'id' => $key, 'type' => $type, 'value' => $value );
		}

		private function get_default( $key ) {
			return isset( $this->defaults[ $key ] ) ? $this->defaults[ $key ] : null;
		}

		public function get_defaults() {
			return $this->defaults;
		}


        public function is_reset_all() {
			return isset( $_GET['page'] ) && ( $_GET['page'] == 'th-advance-product-search' ) && isset( $_GET[ $this->setting_reset_name ] );
		}  

        public function delete_settings() {

			do_action( sprintf( 'delete_%s_settings', $this->settings_name ), $this );

			// license_key should not updated

			return delete_option( $this->settings_name );
		}

		public function get_option( $option ) {
			$default = $this->get_default( $option );
			$options = get_option( $this->settings_name );
			$is_new = ( ! is_array( $options ) && is_bool( $options ) );

			// Theme Support
			if ( current_theme_supports( $this->theme_feature_name ) ) {
				$theme_support    = get_theme_support( $this->theme_feature_name );
				$default['value'] = isset( $theme_support[0][ $option ] ) ? $theme_support[0][ $option ] : $default['value'];
			}

			$default_value = isset( $default['value'] ) ? $default['value'] : null;

			if ( ! is_null( $this->get_reserved( $option ) ) ) {
				$default_value = $this->get_reserved( $option );
			}

			if ( $is_new ) {
			
				return $default_value;
			} else {
			
				return isset( $options[ $option ] ) ? $options[ $option ] : $default_value;
			}
		}

		public function get_options(){
			return get_option( $this->settings_name );
		}

		public function get_reserved( $key = false ){

			$data = (array) get_option( $this->reserved_key );
			if ( $key ) {
				return isset( $data[ $key ] ) ? $data[ $key ] : null;
			} else {
				return $data;
			}
		}


}

endif;