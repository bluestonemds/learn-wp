<?php
class wpad_enqueue{

	function __construct(){

		add_action( 'admin_menu' , array( $this , 'remove_comment_menu' ) );
		add_action( 'admin_menu', array( $this, 'wpad_enqueue_scripts') );
		add_action( 'wp_enqueue_scripts', array( $this , 'wpad_enqueue_front' ) );

	}

	function remove_comment_menu(){

		global $menu;

		foreach( $menu as $key => $value ){

			if( $value[2] == 'edit-comments.php' ){

				unset( $menu[$key] );

			}

		}

	}

	function wpad_enqueue_front(){

		/*
		** This will be enqueue when shortcode is enable	
		*/

		wp_register_style( 'wpad_style_front', plugin_dir_url( dirname( __FILE__ ) ) . 'css/frontend-style.css' );

		wp_register_script( 'wpad_validate_jquery', plugin_dir_url( dirname( __FILE__ ) ) . 'js/jquery.validate.min.js' , array(), '1.0.0', false );	

		wp_register_script( 'wpad_validate_additional_method', plugin_dir_url( dirname( __FILE__ ) ) . 'js/methods.min.js' , array(), '1.0.0', false );

		$this->check_jquery_settings();

		wp_register_script( 'wpad_front_scripts', plugin_dir_url( dirname( __FILE__ ) ) . 'js/frontend-script.js' , array(), '1.0.0', false );

		wp_enqueue_style( 'wpad-widget', plugin_dir_url( dirname( __FILE__ ) ) . 'css/widget.css' , array(), '1.0.0', false );

		$this->localize_scripts();

	}

	function localize_scripts(){

		$scroll_top = get_option( 'wpad_scroll_to_top' );
		$translation_array = array(

			'admin_ajax' => admin_url( 'admin-ajax.php' ),
			'plugin_url' => plugin_dir_url( dirname( __FILE__ ) ),
			'report_textarea' => get_option( 'wpad_required_user_message' ),
			'wpad_scroll_to_top' => !empty($scroll_top) ? $scroll_top : 50,

		);

		wp_localize_script( 'wpad_front_scripts', 'translate', $translation_array );

		wp_enqueue_script( 'wpad_front_scripts' );

	}

	function check_jquery_settings(){

		if( get_option( 'disable_jquery' ) != 'disable' ){

			wp_enqueue_script( 'jquery' );

		}

		if( get_option( 'disable_jquery_validation' ) != 'disable' ){

			wp_enqueue_script( 'wpad_validate_jquery' );

			wp_enqueue_script( 'wpad_validate_additional_method' );

		}

	}

	function wpad_enqueue_scripts(){

		if( empty($_GET['page']) ){

			return;

		}

		$link = array('wpad_addons','wpad_comment_form_edit','wp_advance_comment', 'wpad_comment_form_list' , 'wpad_reply_form' , 'wpad_saved_edit_form' , 'wpad_help' , 'wpad_comment_setting_page' , 'wpad_reported_comment' );

		/*
		// Include Styles and Scripts for only Wp advance comment pages
		*/

		if( in_array($_GET['page'], $link) ){

			wp_register_script( 'wpad_script', plugin_dir_url( dirname( __FILE__ ) ) . 'js/scripts.js' );

			$id = !empty( $_GET['comment_id'] ) ? $_GET['comment_id'] : '';

			$translation_array = array(

				'admin_ajax' => admin_url( 'admin-ajax.php' ),

				'plugin_dir_path' => plugin_dir_url( dirname( __FILE__ ) ),

				'comment_id' => $id,

				'admin_url' => admin_url()

			);

			wp_localize_script( 'wpad_script', 'translate', $translation_array );

			/*
			// Enqueued script with localized data.
			*/

			wp_enqueue_script( 'wpad_script' );

			wp_enqueue_style( 'wpad-style', plugin_dir_url( dirname( __FILE__ ) ) . 'css/style.css' , array(), '1.0.0', false );

			wp_enqueue_script( 'jquery-ui-sortable' );

			wp_enqueue_script( 'jquery-ui-dialog' );

			wp_enqueue_style( 'wp-jquery-ui-dialog' );

			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );

			// Get Wordpress defaults icons

			wp_enqueue_style( 'dashicons' );

		}		

	}

}

$wpad_enqueue = new wpad_enqueue();