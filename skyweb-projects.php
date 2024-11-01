<?php
/**
 * Plugin Name: SkyWeb Projects
 * Plugin URI: https://skyweb.site/projects/skyweb-projects/
 * Description: Simple project catalog with AJAX filter.
 * Version: 1.0.2
 * Author: SkyWeb
 * Author URI: https://skyweb.site
 * Text Domain: skyweb-projects
 * Domain Path: /languages
 *
 * Requires at least: 5.2
 * Requires PHP: 7.0
 *
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

defined( 'ABSPATH' ) || exit;

// Check if required functions are exist.
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

// Define main plugin file constant for activation and deactivation plugin hooks.
if ( ! defined( 'SKYWEB_PROJECTS_FILE' ) ) {
	define( 'SKYWEB_PROJECTS_FILE', __FILE__ );
}

// Define a constant for assets handle.
if ( ! defined( 'SKYWEB_PROJECTS_SLUG' ) ) {
	define( 'SKYWEB_PROJECTS_SLUG', dirname( plugin_basename( __FILE__ ) ) );
}

// Define a constant for assets version.
if ( ! defined( 'SKYWEB_PROJECTS_VERSION' ) ) {
	define( 'SKYWEB_PROJECTS_VERSION', get_plugin_data( __FILE__ )['Version'] );
}

// Register theme text domain.
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'skyweb-projects', false, __DIR__ . '/languages' );
} );

// Required classes.
// TODO - separate main class to several classes.
if ( ! class_exists( 'SkyWeb_Projects_Post_Type', false ) ) {
	require_once __DIR__ . '/includes/class-post-type.php';
}
/*if ( ! class_exists( 'SkyWeb_Projects_Meta_Boxes', false ) ) {
	require_once __DIR__ . '/includes/class-meta-boxes.php';
}*/
if ( ! class_exists( 'SkyWeb_Projects', false ) ) {
	require_once __DIR__ . '/includes/class-main.php';
}
/*if ( ! class_exists( 'SkyWeb_Projects_Shortcodes', false ) ) {
	require_once __DIR__ . '/includes/class-shortcodes.php';
}
if ( ! class_exists( 'SkyWeb_Projects_Widget', false ) ) {
	require_once __DIR__ . '/includes/class-widget.php';
}
if ( ! class_exists( 'SkyWeb_Projects_Output', false ) ) {
	require_once __DIR__ . '/includes/class-output.php';
}
if ( ! class_exists( 'SkyWeb_Projects_Weather', false ) ) {
	require_once __DIR__ . '/includes/class-weather.php';
}
if ( ! class_exists( 'SkyWeb_Projects_AJAX', false ) ) {
	require_once __DIR__ . '/includes/class-ajax-handler.php';
}
if ( ! class_exists( 'SkyWeb_Projects_Options', false ) ) {
	require_once __DIR__ . '/includes/class-options.php';
}*/
if ( ! class_exists( 'SkyWeb_Projects_Navigation', false ) ) {
	require_once __DIR__ . '/includes/class-navigation.php';
}