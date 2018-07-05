<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.grocrm.com/
 * @since             1.0.0
 * @package           Grocrm
 *
 * @wordpress-plugin
 * Plugin Name:       Gro CRM
 * Plugin URI:        https://www.grocrm.com/developer/api/
 * Description:       The Mac CRM sales platform designed for iOS and the Apple Small Business market. Use our Gro CRM Contact-to-Leads WordPress Plug-in for your website.
 * Version:           1.0.2
 * Author:            Gro CRM
 * Author URI:        https://www.grocrm.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       grocrm
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-grocrm-activator.php
 */
function activate_grocrm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-grocrm-activator.php';
	Grocrm_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-grocrm-deactivator.php
 */
function deactivate_grocrm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-grocrm-deactivator.php';
	Grocrm_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_grocrm' );
register_deactivation_hook( __FILE__, 'deactivate_grocrm' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-grocrm.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_grocrm() {

	$plugin = new Grocrm();
	$plugin->run();

}
run_grocrm();