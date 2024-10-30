<?php

// Avoid direct calls to this file where wp core files not present
if (!function_exists ('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

if ( !class_exists( 'HABFUR_Admin' ) ) {

  /**
	 * Hide Admin Bar For User Roles Admin class
	 *
	 * @package Hide Admin Bar For User Roles
	 * @since 1.0.0
	 */
	class HABFUR_Admin {

    /**
		 * Instance of HABFUR_Admin class
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object
		 */
		private static $instance = false;

		/**
		 * Return unique instance of this class
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

    public function __construct() {
			if (is_admin()) {
				add_action( 'admin_menu', array( &$this, 'register_admin_menu' ) );
			}
      add_action( 'admin_enqueue_scripts', array( &$this,'admin_scripts' ) );
			add_action( 'wp_ajax_habfur_save_settings', array( &$this,'save_settings' ) );
		}

    /**
		 * Include style in WordPress admin
		 * 
		 * @since 1.0.0
		 */
		function admin_scripts($hook) {
			if ( 'settings_page_habfur_options' != $hook ) {
        return;
    	}
			wp_enqueue_style('habfur-admin-style', HABFUR_ASSETS_URL.'admin.css');
			wp_enqueue_script('habfur-admin-script', HABFUR_ASSETS_URL.'admin.js', array('jquery'));
		}

    /**
		 * Add submenu in WordPress admin settings menu
		 * 
		 * @since 1.0.0
		 */
		public function register_admin_menu() {
			add_options_page( HABFUR_PLUGIN_NAME.' Settings', __( 'Hide Admin Bar', 'hide-admin-bar-for-user-roles' ), 'manage_options', 'habfur_options', array( &$this, 'options' ) );
		}

		/**
		 * Load the setting page
		 * 
		 * @since 1.0.0
		 */
		public function options() {
			include_once('pages/options.php');
		}

    /**
		 * Save plugin settings
		 * 
		 * @since 1.0.0
		 * @return string (json)
		 */
		public function save_settings() {
      $response = array();
			$error = "";

			// Check user capabilities
			if (! current_user_can( 'manage_options' ))
				return;

			// Check for request security
			if (!check_ajax_referer( 'habfur-save-settings', 'security' )) {
				$error = __( "Error! Security Check Failed! Please refresh page and save settings again.", 'hide-admin-bar-for-user-roles' );
			}

			// Sanitize inputs
			$habfur_settings = isset($_POST['habfur_settings']) ? (array) array_map('sanitize_text_field', $_POST['habfur_settings']) : array();

			// Save setting in WordPress options
			if (empty($error)) {
				update_option('habfur_settings', $habfur_settings);
				$response['status'] = 'success';
				$response['message'] = __( 'Settings saved successfully.', 'hide-admin-bar-for-user-roles' );
				
			} else {
				$response['status'] = 'error';
				$response['message'] = $error;
			}
	  
			echo json_encode($response);
			exit();
    }

  } // end class HABFUR_Admin

	add_action( 'plugins_loaded', array( 'HABFUR_Admin', 'get_instance' ) );

} // end class_exists
