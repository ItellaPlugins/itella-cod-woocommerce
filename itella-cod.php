<?php

/**
 * @link              
 * @since             1.0.0
 * @package           Itella_COD_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Smartpost Itella COD
 * Description:       Card on delivery payment method for Smartpost Itella shipping
 * Version:           1.0.0
 * Author:            Itella Team
 * Author URI:        https://itella.lt/en/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       itella-cod
 * Domain Path:       /languages
 * 
 * Tested up to:      6.1.1
 * WC tested up to:   7.3.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

// activation
require_once plugin_dir_path( __FILE__ ) . 'includes/class-itella-cod-activator.php';

// deactivation
require_once plugin_dir_path( __FILE__ ) . 'includes/class-itella-cod-deactivator.php';

register_activation_hook( __FILE__, array('Itella_Cod_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array('Itella_Cod_Deactivator', 'deactivate' ) );

// main class
require_once plugin_dir_path( __FILE__ ) . 'includes/class-itella-cod.php';

function run_itella_cod() {

	$plugin = new Itella_Cod();
}
run_itella_cod();
