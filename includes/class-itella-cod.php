<?php

/**
 * @package    Itella_COD_Plugin
 * @subpackage Itella_COD_Plugin/includes
 */

/**
 * @package    Itella_COD_Plugin
 * @subpackage Itella_COD_Plugin/includes
 */
class Itella_Cod
{

  /**
   * @var      Itella_Cod_Loader $loader
   */
  protected $loader;

  /**
   * @var      string $plugin_name
   */
  protected $plugin_name;

  /**
   * @var      string $version
   */
  protected $version;

  /**
   * @since    1.0.0
   */
  public function __construct()
  {

    $this->plugin_name = 'itella-cod';
    $this->version = '1.0.4';

    add_action('plugins_loaded', array($this, 'load_dependencies'));
    add_action('admin_notices', array($this, 'notify_on_activation'));
    add_filter('woocommerce_payment_gateways', array($this, 'load_itella_cod'));

  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Itella_Cod_Loader. Orchestrates the hooks of the plugin.
   * - Itella_Cod_i18n. Defines internationalization functionality.
   * - Itella_Cod_Admin. Defines all hooks for the dashboard.
   * - Itella_Cod_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  public function load_dependencies()
  {

    if (!class_exists('WooCommerce')) {
      return;
    }

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-itella-cod-loader.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-itella-cod-i18n.php';

    /**
     * Itella COD payment gateway
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-itella-gateway-cod.php';

    /**
     * The class responsible for defining all actions that occur in the Dashboard.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-itella-cod-admin.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-itella-cod-public.php';

    $this->loader = new Itella_Cod_Loader();

    $this->set_locale();
    $this->define_admin_hooks();
    $this->define_public_hooks();
    $this->loader->run();

  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Itella_Cod_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale()
  {

    $plugin_i18n = new Itella_Cod_i18n();
    $plugin_i18n->set_domain($this->get_plugin_name());

    $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    add_filter('woocommerce_payment_gateways', array($this, 'load_Itella_cod'));


  }

  /**
   * Register all of the hooks related to the dashboard functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks()
  {

    $plugin_admin = new Itella_Cod_Admin($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_public_hooks()
  {

    $plugin_public = new Itella_Cod_Public($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run()
  {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @return    string    The name of the plugin.
   * @since     1.0.0
   */
  public function get_plugin_name()
  {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @return    Itella_Cod_Loader    Orchestrates the hooks of the plugin.
   * @since     1.0.0
   */
  public function get_loader()
  {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @return    string    The version number of the plugin.
   * @since     1.0.0
   */
  public function get_version()
  {
    return $this->version;
  }

  public function notify_on_activation()
  {
    $msg = sprintf(
      /* translators: %1$s - plugin name, %2$s - word "here" with link to settings page */
      __('Setup %1$s %2$s', 'itella-cod'),
      __('Smartposti COD', 'itella-cod'),
      '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=itella_cod') . '">' . __('here', 'itella-cod') . '</a>'
    );

    if (get_transient('itella-cod-activated')) : ?>
        <div class="updated notice is-dismissible">
            <p><?php echo $msg; ?></p>
        </div>
      <?php
      delete_transient('itella-cod-activated');
    endif;
  }

  public function load_itella_cod($gateways)
  {
    $gateways[] = 'Itella_Gateway_COD';
    $key = array_search('Itella_Gateway_COD', $gateways);
    if ($key) {
      $gateways[$key] = 'Itella_Cod_Admin';
    }

    return $gateways;

  }

}
