<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Itella_COD_Plugin
 * @subpackage Itella_COD_Plugin/includes
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Itella_COD_Plugin
 * @subpackage Itella_COD_Plugin/admin
 */
class Itella_Cod_Admin extends Itella_Gateway_COD
{

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string $name The ID of this plugin.
   */
  private $name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string $version The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @var      string $name The name of this plugin.
   * @var      string $version The version of this plugin.
   */
  public function __construct()
  {

    parent::__construct();
    $this->name = 'itella_cod';
    $this->version = '1.0.5';

  }

  /**
   * Register the stylesheets for the Dashboard.
   *
   * @since    1.0.0
   */
  public function enqueue_styles()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Plugin_Name_Admin_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Plugin_Name_Admin_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style($this->name, plugin_dir_url(__FILE__) . 'css/itella-cod-admin.css', array(), $this->version, 'all');

  }

  /**
   * Register the JavaScript for the dashboard.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Plugin_Name_Admin_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Plugin_Name_Admin_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_script($this->name, plugin_dir_url(__FILE__) . 'js/itella-cod-admin.js', array('jquery'), $this->version, TRUE);

  }
}


