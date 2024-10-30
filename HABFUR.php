<?php
/**
 * Plugin Name: Hide Admin Bar For User Roles
 * Plugin URI: https://wordpress.org/plugins/hide-admin-bar-for-user-roles/
 * Text Domain: hide-admin-bar-for-user-roles
 * Domain Path: /languages
 * Description: Easy to use WordPress hide admin bar plugin, allows you to hide admin bar for specific user roles. Using this plugin you can show the admin bar for administrators and remove admin bar for all users except administrators.
 * Version: 1.1.2
 * Author: Subodh Ghulaxe
 * Author URI: http://www.subodhghulaxe.com
 */

// Avoid direct calls to this file where wp core files not present
if (!function_exists ('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

if ( !class_exists( 'HABFUR' ) ) {

  /**
	 * Hide Admin Bar For User class
	 *
	 * @package Hide Admin Bar For User
	 * @since 1.0.0
	 */
	class HABFUR {

		/**
		 * Instance of HABFUR class
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object
		 */
		private static $instance = false;

		/**
		 * Plugin settings
		 *
		 * @since 1.0.0
		 * @access public
		 * @var array
		 */
		public $habfur_settings = array();

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
		
		function __construct() {
			$this->constants();
			$this->text_domain();
			$this->habfur_settings = get_option('habfur_settings');

			add_action( 'init', array( &$this, 'init' ));
		}

    /**
		 * Define plugin constants
		 *
		 * @since 1.0.0
		 */
		public function constants() {
			defined("HABFUR_PLUGIN_NAME") || define( 'HABFUR_PLUGIN_NAME', 'Hide Admin Bar For User Roles' );
			defined("HABFUR_BASEDIR") || define( 'HABFUR_BASEDIR', dirname( plugin_basename(__FILE__) ) );
			defined("HABFUR_ASSETS_URL") || define( 'HABFUR_ASSETS_URL', plugins_url('assets/',__FILE__) );
		}

    /**
		 * Load plugin text domain
		 *
		 * @since 1.0.0
		 */
		public function text_domain() {
			load_plugin_textdomain( 'hide-admin-bar-for-user-roles', false, HABFUR_BASEDIR . '/languages' );
		}

    /**
		 * Runs after WordPress has finished loading but before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		public function init() {
      add_filter( 'show_admin_bar', array(&$this, 'show_admin_bar') );

			// Add settings link in plugin listing page.
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( &$this, 'add_action_links' ) );

			// Add donate link in plugin listing page.
			add_filter( 'plugin_row_meta', array( &$this, 'donate_link' ), 10, 2 );
		}

		/**
		 * Show admin bar based on selected user roles
		 *
		 * @since 1.0.0
		 */
    function show_admin_bar($show_admin_bar) {
			if (is_array($this->habfur_settings)) {
				$hide_for_roles = array_keys($this->habfur_settings);
				$user = wp_get_current_user();
 				$roles = ( array ) $user->roles;
				foreach($roles as $role) {
					if (in_array($role, $hide_for_roles)) {
						return false;
					}
				}
			}		
			
			return $show_admin_bar;
    }

    /**
		 * Add settings link to plugin action links in /wp-admin/plugins.php
		 * 
		 * @since 1.0.0
		 * @param  array $links
		 * @return array
		 */
		public function add_action_links ( $links ) {
			$mylinks = array(
				'<a href="' . admin_url( 'options-general.php?page=habfur_options' ) . '">'.__( 'Settings', 'hide-admin-bar-for-user-roles' ).'</a>',
			);

			return array_merge( $mylinks, $links );
		}

		/**
		 * Add donate link to plugin description in /wp-admin/plugins.php
		 * 
		 * @since 1.0.0
		 * @param  array $plugin_meta
		 * @param  string $plugin_file
		 * @return array
		 */
		public function donate_link( $plugin_meta, $plugin_file ) {
			if ( plugin_basename( __FILE__ ) == $plugin_file )
				$plugin_meta[] = sprintf(
					'&hearts; <a href="%s" target="_blank">%s</a>',
					'https://www.patreon.com/subodhghulaxe',
					__( 'Donate', 'hide-admin-bar-for-user-roles' )
			);
			
			return $plugin_meta;
		}

  } // end class HABFUR
	
	add_action( 'plugins_loaded', array( 'HABFUR', 'get_instance' ) );

	include_once('admin/HABFUR_Admin.php');
}