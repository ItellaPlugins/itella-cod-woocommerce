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
    $this->name = 'itella-cod';
    $this->version = '1.0.0';

    add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    add_action('woocommerce_settings_api_form_fields_cod', array($this, 'extend_cod'));


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

    wp_enqueue_script($this->name, plugin_dir_url(__FILE__) . 'js/itella-cod-admin.js', array('jquery'), $this->version, FALSE);

  }

  public function extend_cod($form_fields)
  {

    $form_fields['extra_fee'] = array(
        'title' => __('Extra Fee', 'itella-cod'),
        'type' => 'price',
        'class' => '',
        'description' => __('The extra amount you charging for cash on delivery (leave blank or zero if you don\'t charge extra)', 'itella-cod'),
        'desc_tip' => true,
        'placeholder' => __('Enter Amount', 'itella-cod')
    );

    $form_fields['nocharge_amount'] = array(
        'title' => __('Disable extra fee if cart amount is greater or equal than this limit.', 'itella-cod'),
        'type' => 'price',
        'class' => '',
        'description' => __('Leave blank or zero if you want to charge for any amount', 'itella-cod'),
        'desc_tip' => true,
        'placeholder' => __('Enter Amount', 'itella-cod'),
        'custom_attributes' => array(
            'data-name' => 'nocharge_amount'
        )
    );

    var_dump($form_fields);
    die;

    return $form_fields;
  }
}