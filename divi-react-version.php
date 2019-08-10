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
 * Description:       Set custom React version to bu used in Divi and Divi Extension.
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
	// React source path.
	if ( divirv_is_debug_mode() && 'react' === $handle ) {
		return 'https://cdn.jsdelivr.net/npm/react@' . DIVI_REACT_VERSION . '/umd/react.development.js';
	}

	// React DOM source path.
	if ( divirv_is_debug_mode() && 'react-dom' === $handle ) {
		return 'https://cdn.jsdelivr.net/npm/react-dom@' . DIVI_REACT_VERSION . '/umd/react-dom.development.js';
	}

	return $src;
}
add_filter( 'script_loader_src', 'divirv_script_loader_src', 10, 2 );
