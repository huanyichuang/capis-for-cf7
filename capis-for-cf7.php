<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://huanyichuang.com/
 * @since             1.0.0
 * @package           Capis_For_Cf7
 *
 * @wordpress-plugin
 * Plugin Name:       Conversions API for Contact Form 7
 * Plugin URI:        https://huanyichuang.com/
 * Description:       This is an extension for Contact Form 7 to activate the integration with Meta's conversions API.
 * Version:           1.0.0
 * Author:            Huanyi Chuang
 * Author URI:        https://huanyichuang.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       capis-for-cf7
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CAPIS_FOR_CF7_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-capis-for-cf7-activator.php
 */
function activate_capis_for_cf7() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-capis-for-cf7-activator.php';
	Capis_For_Cf7_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-capis-for-cf7-deactivator.php
 */
function deactivate_capis_for_cf7() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-capis-for-cf7-deactivator.php';
	Capis_For_Cf7_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_capis_for_cf7' );
register_deactivation_hook( __FILE__, 'deactivate_capis_for_cf7' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-capis-for-cf7.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_capis_for_cf7() {

	$plugin = new Capis_For_Cf7();
	$plugin->run();

}
run_capis_for_cf7();
