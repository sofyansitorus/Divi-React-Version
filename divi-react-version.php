<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/sofyansitorus
 * @since             1.0.0
 * @package           Divi_React_Version
 *
 * @wordpress-plugin
 * Plugin Name:       Divi React Version
 * Plugin URI:        https://github.com/sofyansitorus/Divi-React-Version
 * Description:       Set custom React version to be used in Divi and Divi Extension development environment.
 * Version:           1.0.0
 * Author:            Sofyan Sitorus
 * Author URI:        https://github.com/sofyansitorus
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       divirv
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Load the setting page library.
require_once 'includes/class-wpyes.php';

// Define the react version that will be used.
if ( ! defined( 'DIVI_REACT_VERSION' ) ) {
	define( 'DIVI_REACT_VERSION', '16.7.0' );
}

/**
 * Check if divi is in debug mode.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function divirv_is_divi_debug_mode() {
	return defined( 'ET_DEBUG' ) && ET_DEBUG;
}

/**
 * Check if divi extension is in debug mode.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function divirv_is_divi_extension_debug_mode() {
	return class_exists( 'DiviExtensions' ) && method_exists( 'DiviExtensions', 'is_debugging_extension' ) && DiviExtensions::is_debugging_extension();
}

/**
 * Check if divi or divi extension is in debug mode.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function divirv_is_debug_mode() {
	return divirv_is_divi_debug_mode() || divirv_is_divi_extension_debug_mode();
}

/**
 * Filters the script loader source.
 *
 * @since 1.0.0
 *
 * @param string $src    Script loader source path.
 * @param string $handle Script handle.
 *
 * @return string Filtered script loader source path.
 */
function divirv_script_loader_src( $src, $handle ) {
	$react_version = get_option( 'divirv_react_version', DIVI_REACT_VERSION );

	// React source path.
	if ( divirv_is_debug_mode() && 'react' === $handle ) {
		return 'https://cdn.jsdelivr.net/npm/react@' . $react_version . '/umd/react.development.js';
	}

	// React DOM source path.
	if ( divirv_is_debug_mode() && 'react-dom' === $handle ) {
		return 'https://cdn.jsdelivr.net/npm/react-dom@' . $react_version . '/umd/react-dom.development.js';
	}

	return $src;
}
add_filter( 'script_loader_src', 'divirv_script_loader_src', 10, 2 );

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function divirv_load_textdomain() {
	load_plugin_textdomain( 'divirv', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'divirv_load_textdomain' );

if ( ! function_exists( 'divirv_admin_setting' ) ) :
	/**
	 * Create the admin setting page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function divirv_admin_setting() {
		// Bail early when not in debug mode.
		if ( ! divirv_is_debug_mode() ) {
			return;
		}

		$settings = new Wpyes(
			'divirv_setting',
			array(
				'menu_title'  => __( 'React Version', 'divirv' ),
				'page_title'  => __( 'Divi React Version', 'divirv' ),
				'method'      => 'add_submenu_page',
				'parent_slug' => 'et_divi_options',
			),
			'divirv'
		);

		$settings->add_field(
			array(
				'id'          => 'react_version',
				'label'       => __( 'React Version', 'divirv' ),
				'required'    => true,
				'description' => __( 'Visit https://reactjs.org/versions for complete release history of React.', 'divirv' ),
				'default'     => DIVI_REACT_VERSION,
			)
		);

		$settings->init(); // Run the Wpyes class.
	}
endif;
add_action( 'init', 'divirv_admin_setting' );
