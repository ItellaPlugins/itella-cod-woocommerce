<?php

/**
 * Fired during plugin activation
 * @since      1.0.0
 *
 * @package    Itella_COD_Plugin
 * @subpackage Itella_COD_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Itella_COD_Plugin
 * @subpackage Itella_COD_Plugin/includes
 */
class Itella_Cod_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        set_transient( 'itella-cod-activated', true, 3 );

	}
}
