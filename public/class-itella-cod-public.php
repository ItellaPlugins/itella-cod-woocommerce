<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Itella_COD_Plugin
 * @subpackage Itella_COD_Plugin/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Itella_COD_Plugin
 * @subpackage Itella_COD_Plugin/admin
 */
class Itella_Cod_Public
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
   * @var array
   */

  public $itella_cod_settings;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @var      string $name The name of the plugin.
   * @var      string $version The version of this plugin.
   */
  public function __construct($name, $version)
  {

    $this->name = $name;
    $this->version = $version;
    $this->itella_cod_settings = get_option('woocommerce_itella_cod_settings');
//        var_dump(get_option('woocommerce_itella_cod_settings'));
//    die;

    if (is_admin())
      return;

    add_filter( 'woocommerce_available_payment_gateways', array( $this, 'apply_itella_cod_settings' ) );
    add_action('woocommerce_cart_calculate_fees', array($this, 'apply_itella_cod_fee'));
//    add_action( 'woocommerce_update_order_review_fragments', array( $this, 'apply_custom_message' ) );

  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Plugin_Name_Public_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Plugin_Name_Public_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style($this->name, plugin_dir_url(__FILE__) . 'css/itella-cod-public.css', array(), $this->version, 'all');

  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Plugin_Name_Public_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Plugin_Name_Public_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_script($this->name, plugin_dir_url(__FILE__) . 'js/itella-cod-public.js', array('jquery'), $this->version, TRUE);

  }

  public function apply_itella_cod_fee(WC_Cart $cart)
  {

    if ($this->is_itella_payment_method_selected()) {
      $extra_fee_type = $this->itella_cod_settings['extra_fee_type'];
      $is_taxable = $this->itella_cod_settings['extra_fee_tax'] === 'enable' ? true : false;
      $extra_fee_amount = $this->itella_cod_settings['extra_fee'];

      if ($this->itella_cod_settings['extra_fee_type'] != 'disabled') {
        if ($extra_fee_type === 'fixed' && $is_taxable) {
          $cart->add_fee('Itella COD', $extra_fee_amount, true);
        }
        if ($extra_fee_type === 'fixed' && !$is_taxable) {
          $cart->add_fee('Itella COD', $extra_fee_amount);
        }
        if ($extra_fee_type === 'percentage' && $is_taxable) {

        }
        if ($extra_fee_type === 'percentage' && !$is_taxable) {

        }

      }
    }

  }

  public function calc_extra_fee_tax()
  {

    global $woocommerce;


  }

  public function check_enabled_countries()
  {
    global $woocommerce;
    $current_country = $woocommerce->customer->get_shipping_country();

    return in_array($current_country, $this->itella_cod_settings['enabled_countries']) ? true : false;
  }

  /**
   * Check if Itella COD is selected as a shipping method
   */
  public function is_itella_shipping_method_selected()
  {
    global $woocommerce;
    $selected_shipping_method = $woocommerce->session->get('chosen_shipping_methods');

    return stripos($selected_shipping_method[0], 'itella') !== false ? true : false;
  }

  /**
   *  Check if Itella payment method is selected
   */

  public function is_itella_payment_method_selected()
  {
    global $woocommerce;
    $selected_payment_method = $woocommerce->session->get('chosen_payment_method');

    return $selected_payment_method === 'itella_cod' ? true : false;
  }

  /**
   * Check cod availability
   *
   */

  public function apply_itella_cod_settings( $available_gateways ) {

    if( ! function_exists( 'is_checkout' ) || ! is_checkout() && ! is_wc_endpoint_url( 'order-pay' ) )
      return $available_gateways;

    if (!$this->check_enabled_countries()) {
      unset( $available_gateways[ 'itella_cod' ] );
    }

    // disable default cod if itella is selected as shipping method
    if ($this->is_itella_shipping_method_selected()) {
      unset( $available_gateways[ 'cod' ] );
    }

    return $available_gateways;
  }

}
