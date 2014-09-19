<?php

/**
 * Plugin Name: TestPlugin
 * Plugin URI: 
 * Description:
 * Version: 1.0
 * Author: Enrique Pennimpede
 * Author URI: http://
 * License: GPL2
 */

// Define class only if not defined already
if ( !class_exists ( 'TestPlugin' ) ) {

	class TestPlugin {
		
		static $debug = true;
		
		/**
		 * Adds TestPlugin menus and submenus to admin dashboard
		 */
		static function testplugin_plugin_menu () {
			// Log caller debug info
			TestPlugin::log ();
		
			// Add top level TestPlugin menu to WP admin dashboard
			add_menu_page ( 'TestPlugin', 'TestPlugin', 'manage_options', 'testplugin', array( 'TestPlugin', 'dispatcher' ), "", 11 );
		
			// Add Dashboard submenu
			add_submenu_page ( 'testplugin', 'Dashboard', 'Dashboard', 'publish_posts', 'tp-dashboard', array( 'TestPlugin', 'dispatcher' ) );
		

		}
		
		/**
		 * Add a TestPlugin submenu item under the general settings
		 */
		static function testplugin_add_general_settings_sub_menu () {
		
			add_options_page ( 'TestPlugin', 'TestPlugin', 'publish_posts', 'test-plugin-general-setting', array( 'TestPlugin', 'dispatcher' ) );
		
			// Render TestPlugin general settings template
		}		
		
		/**
		 * Loads required assets and scripts based on the requested action and page
		 */
		static function dispatcher () {
			// Log caller debug info
			Grabpress::log ();
		
			// If request does not contain an action
			if ( !isset ( $_REQUEST['action'] ) ) {
				// Set as default
				$_REQUEST['action'] = 'default';
			}
			
			// Create shorter reference to action
			$action = $_REQUEST['action'];
			
			Grabpress::log ($action);
			
			// Get page
			$page = $_GET['page'];
			
			// Recursively stripslahses
			$params = self::strip_deep ( $_REQUEST );			
		}
		
		/**
		 * Logs error messages and other debug info
		 * @param  boolean|string $message Message to be logged, if false will log
		 * default caller debug info
		 */
		static function log ( $message = false ) {
			// GrabPress debug flag is enabled
			if ( TestPlugin::$debug ) {
				// If no message provided
				if ( !$message ) {
					// Get backtrace
					$stack = debug_backtrace ();
		
					// Get line number of caller
					$caller = $stack[1];
		
					// Build formatted debug message, @ suppresses error messages
					@$message = 'TestPlugin:<line ' . $caller['line'] . '>' . $caller['class'] . $caller['type'] . $caller['function'] . '(' . implode ( ', ', $caller['args'] ) . ')';
				}
		
				// Log debug info
				error_log ( $message );
			}
		}

		/**
		 * Recursively strips slashes
		 * @param  mixed $data String or array that should be processed
		 * @return string       String with slashes stripped
		 */
		static function strip_deep ( &$data ) {
			// If array, recursively strip slashes, else strip slashes
			$data = is_array ( $data ) ? array_map ( array( 'GrabPress', 'strip_deep' ), $data ) : stripslashes ( $data );
			return $data;
		}		
		
	}

}

// If user is an admin
if ( is_admin () ) {
	// Add divider in log
	TestPlugin::log ( '-------------------------------------------------------' );

	// Add hooks for admin dashboard
	//register_activation_hook ( __FILE__, array( 'TestPlugin', 'setup' ) );
	//register_uninstall_hook ( __FILE__, array( 'TestPlugin', 'delete_connector' ) );
	add_action ( 'admin_menu', array( 'TestPlugin', 'testplugin_plugin_menu' ) );
	add_action ( 'admin_menu', array( 'TestPlugin', 'testplugin_add_general_settings_sub_menu' ) );
	//add_action ( 'admin_notices', array( 'TestPlugin', 'show_message' ) );
	//add_action ( 'wp_loaded', array( 'TestPlugin', 'plugin_messages' ) );
	/*
	add_action ( 'wp_ajax_tp_delete_alert', array( 'Testplugin_Views', 'delete-alert_callback' ) );
	add_action ( 'wp_ajax_tp_insert_video', array( 'Testplugin_Views', 'insert_video_callback' ) );
	add_action ( 'wp_ajax_tp_get_preview', array( 'Testplugin_Views', 'get_preview_callback' ) );
	add_action ( 'wp_ajax_tp_toggle_watchlist', array( 'Testplugin_Views', 'toggle_watchlist_callback' ) );
	add_filter ( 'default_content', array( 'TestPlugin', 'content_by_request' ), 10, 2 );
	add_filter ( 'default_title', array( 'TestPlugin', 'modified_post_title' ) );
	*/
}