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
   * Itella settings defined from admin panel
   *
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

    if (is_admin())
      return;

    add_filter('woocommerce_available_payment_gateways', array($this, 'apply_itella_cod_settings'));
    add_action('woocommerce_cart_calculate_fees', array($this, 'apply_itella_cod_fee'));

  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles()
  {

//    wp_enqueue_style($this->name, plugin_dir_url(__FILE__) . 'css/itella-cod-public.css', array(), $this->version, 'all');

  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts()
  {


    wp_enqueue_script($this->name, plugin_dir_url(__FILE__) . 'js/itella-cod-public.js', array('jquery'), $this->version, TRUE);

  }

  /**
   * Apply Itella Fee to cart
   * @param WC_Cart $cart
   */
  public function apply_itella_cod_fee(WC_Cart $cart)
  {

    if ($this->is_itella_payment_method_selected()) {
      $extra_fee_type = $this->itella_cod_settings['extra_fee_type'];
      $is_taxable = $this->itella_cod_settings['extra_fee_tax'] === 'enable' ? true : false;
      $extra_fee_amount = $this->itella_cod_settings['extra_fee'];

      if ($this->itella_cod_settings['extra_fee_type'] != 'disabled') {
        $title = __('Smartposti COD', 'itella-cod');
        if ($extra_fee_type === 'fixed' && $is_taxable) {
          $cart->add_fee($title, $extra_fee_amount, true);
        }
        if ($extra_fee_type === 'fixed' && !$is_taxable) {
          $cart->add_fee($title, $extra_fee_amount);
        }
        if ($extra_fee_type === 'percentage' && $is_taxable) {
          $cart->add_fee($title, $this->calc_extra_fee_percentage($extra_fee_amount, $cart), true);
        }
        if ($extra_fee_type === 'percentage' && !$is_taxable) {
          $cart->add_fee($title, $this->calc_extra_fee_percentage($extra_fee_amount, $cart));
        }
      }
    }

  }

  /**
   * Calculate fee according to percentage
   *
   * @param $percentage
   * @param $cart
   * @return float|int
   */
  public function calc_extra_fee_percentage($percentage, $cart)
  {

    $total = $cart->total;

    if (!$total) {
      $total = $cart->cart_contents_total;
    }

    return $extra_fee = ($percentage * $total) / 100;

  }

  /**
   * Check if current country is in enabled country array
   * @return bool
   */
  public function check_enabled_countries()
  {

    global $woocommerce;
    $current_country = $woocommerce->customer->get_shipping_country();
    $enabledCountries = $this->itella_cod_settings['enabled_countries'];
    $enabledCountries = $enabledCountries ? $enabledCountries : array();

    return in_array($current_country, $enabledCountries) ? true : false;

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
   * Show Itella as a payment gateway if available
   *
   */
  public function apply_itella_cod_settings($available_gateways)
  {

    if (!function_exists('is_checkout') || !is_checkout() && !is_wc_endpoint_url('order-pay'))
      return $available_gateways;

    if (!$this->check_enabled_countries()) {
      unset($available_gateways['itella_cod']);
    }

    return $available_gateways;
  }

}
