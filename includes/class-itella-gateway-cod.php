<?php
/**
 * Class Itella_Gateway_COD file.
 *
 * @package    Itella_COD_Plugin
 * @subpackage Itella_COD_Plugin/includes
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

/**
 * Itella Cash on Delivery Gateway.
 *
 * Provides a Cash on Delivery Payment Gateway.
 *
 * @class       Itella_Gateway_COD
 * @extends     WC_Payment_Gateway
 * @version     1.0.0
 * @package     WooCommerce/Classes/Payment
 */
class Itella_Gateway_COD extends WC_Gateway_COD
{

  public $countries;

  /**
   * Constructor for the gateway.
   */
  public function __construct()
  {

    parent::__construct();
  }

  /**
   * Setup general properties for the gateway.
   */
  protected function setup_properties()
  {
    parent::setup_properties();
    $this->id = 'itella_cod';
    $this->icon = apply_filters('woocommerce_cod_icon', '');
    $this->method_title = __('Itella Cash on Delivery', 'itella_cod');
    $this->method_description = __('Setup Itella\'s Cash on Delivery.', 'itella_cod');
    $this->countries = new WC_Countries();
  }

  /**
   * Initialise Gateway Settings Form Fields.
   */
  public function init_form_fields()
  {
    $this->form_fields = array(
        'enabled' => array(
            'title' => __('Enable/Disable', 'itella_cod'),
            'label' => __('Enable cash on delivery', 'itella_cod'),
            'type' => 'checkbox',
            'description' => '',
            'default' => 'no',
        ),
        'title' => array(
            'title' => __('Title', 'itella_cod'),
            'type' => 'text',
            'description' => __('Payment method description that the customer will see on your checkout.', 'itella_cod'),
            'default' => __('Cash on delivery', 'itella_cod'),
            'desc_tip' => true,
        ),
        'description' => array(
            'title' => __('Description', 'itella_cod'),
            'type' => 'textarea',
            'description' => __('Payment method description that the customer will see on your website.', 'itella_cod'),
            'default' => __('Pay with cash upon delivery.', 'itella_cod'),
            'desc_tip' => true,
        ),
        'instructions' => array(
            'title' => __('Instructions', 'itella_cod'),
            'type' => 'textarea',
            'description' => __('Instructions that will be added to the thank you page.', 'itella_cod'),
            'default' => __('Pay with cash upon delivery.', 'itella_cod'),
            'desc_tip' => true,
        ),
        'enable_for_methods' => array(
            'title' => __('Enable for shipping methods', 'itella_cod'),
            'type' => 'multiselect',
            'class' => 'wc-enhanced-select',
            'css' => 'width: 400px;',
            'default' => '',
            'description' => __('Select Itella COD methods.', 'itella_cod'),
            'options' => $this->load_shipping_method_options(),
            'desc_tip' => true,
            'custom_attributes' => array(
                'data-placeholder' => __('Select shipping methods', 'itella_cod'),
            ),
        ),
        'enable_for_virtual' => array(
            'title' => __('Accept for virtual orders', 'itella_cod'),
            'label' => __('Accept COD if the order is virtual', 'itella_cod'),
            'type' => 'checkbox',
            'default' => 'yes',
        ),
        'enabled_countries' => array(
            'title' => __('Enable on specific countries', 'itella_cod'),
            'type' => 'multiselect',
            'class' => 'wc-enhanced-select wc-smart-cod-group wc-smart-cod-restriction',
            'description' => __('Select the countries you want to enable the Itella COD method', 'itella_cod'),
            'options' => $this->countries->get_allowed_countries(),
            'desc_tip' => true,
            'custom_attributes' => array(
                'data-placeholder' => __('Select Countries', 'itella_cod'),
                'data-name' => 'enabled_countries'
            )
        ),
        'extra_fee' => array(
            'title' => __('Extra Fee', 'itella-cod'),
            'type' => 'price',
            'class' => 'wc-smart-cod-group wc-smart-cod-percentage',
            'description' => __('The extra amount you charging for cash on delivery (leave blank or zero if you don\'t charge extra)', 'itella-cod'),
            'desc_tip' => true,
            'placeholder' => __('Enter Amount', 'itella-cod')
        ),
        'extra_fee_tax' => array(
            'title' => __('Extra Fee Tax', 'itella-cod'),
            'type' => 'radio',
            'parent_class' => '',
            'class' => '',
            'options' => array(
                'enable' => __('Enable', 'itella-cod'),
                'disable' => __('Disable', 'itella-cod')
            ),
            'default' => 'disable',
            'description' => __('Is extra fee taxable? Use this option if you have taxes enabled in your shop and you want to include tax to COD method.', 'itella-cod'),
            'desc_tip' => false
        ),
        'nocharge_amount' => array(
            'title' => __('Disable extra fee if cart amount is greater or equal than this limit.', 'itella-cod'),
            'type' => 'price',
            'class' => '',
            'description' => __('Leave blank or zero if you want to charge for any amount', 'itella-cod'),
            'desc_tip' => true,
            'placeholder' => __('Enter Amount', 'itella-cod'),
            'custom_attributes' => array(
                'data-name' => 'nocharge_amount'
            )
        )
    );
  }

  public function generate_radio_html( $key, $data ) {

    $field_key = $this->get_field_key( $key );

    $defaults  = array(
        'title'             => '',
        'disabled'          => false,
        'class'             => '',
        'css'               => '',
        'placeholder'       => '',
        'type'              => 'text',
        'desc_tip'          => false,
        'description'       => '',
        'custom_attributes' => array(),
        'options'           => array(),
        'parent_class'		=> '',
        'default' => ''
    );

    $data = wp_parse_args( $data, $defaults );
    $value = esc_attr( $this->get_option( $key ) );

    if( ! $value && ! array_key_exists( $key, $this->settings ) ) {
      if( $data[ 'default' ] ) {
        $value = $data[ 'default' ];
      }
    }

    ob_start(); ?>
    <tr valign="top">
      <th scope="row" class="titledesc">
        <?php echo $this->get_tooltip_html( $data ); ?>
        <label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data[ 'title' ] ); ?></label>
      </th>
      <td class="forminp forminp-<?php echo sanitize_title( $data['type'] ); ?><?php echo $data[ 'parent_class' ] ? ' ' . esc_attr( $data[ 'parent_class' ] ) : ''; ?>">
        <fieldset>
          <ul>
            <?php
            foreach ( (array) $data['options'] as $option_key => $option_value ) {
              ?>
              <li>
                <label><input
                      name="<?php echo esc_attr( $field_key ); ?>"
                      value="<?php echo $option_key; ?>"
                      type="radio"
                      style="<?php echo esc_attr( $data['css'] ); ?>"
                      class="<?php echo esc_attr( $data['class'] ); ?>"
                      <?php echo $this->get_custom_attribute_html( $data ); ?>
                      <?php checked( $option_key, $value ); ?>
                  /> <?php echo esc_attr( $option_value ); ?></label>
              </li>
              <?php
            }
            ?>
          </ul>
          <?php echo $this->get_description_html( $data ); ?>
        </fieldset>
      </td>
    </tr>
    <?php
    return ob_get_clean();
  }

//  public function validate_radio_field( $key, $value ) {
//    $value = is_null( $value ) ? '' : $value;
//    return wc_clean( stripslashes( $value ) );
//  }

//  public function validate_checkboxes_field( $key, $value ) {
//    $_value = array();
//    if( ! $value ) {
//      return array();
//    }
//    foreach( $value as $v ) {
//      array_push( $_value, wc_clean( stripslashes( $v ) ) );
//    }
//    return $_value;
//  }

  /**
   * Get countries that the store sells to.
   *
   * @return array
   */
  public function get_allowed_countries()
  {
    if ('all' === get_option('woocommerce_allowed_countries')) {
      return apply_filters('woocommerce_countries_allowed_countries', $this->countries);
    }

    if ('all_except' === get_option('woocommerce_allowed_countries')) {
      $except_countries = get_option('woocommerce_all_except_countries', array());

      if (!$except_countries) {
        return $this->countries;
      } else {
        $all_except_countries = $this->countries;
        foreach ($except_countries as $country) {
          unset($all_except_countries[$country]);
        }
        return apply_filters('woocommerce_countries_allowed_countries', $all_except_countries);
      }
    }

    $countries = array();

    $raw_countries = get_option('woocommerce_specific_allowed_countries', array());

    if ($raw_countries) {
      foreach ($raw_countries as $country) {
        $countries[$country] = $this->countries[$country];
      }
    }

    return apply_filters('woocommerce_countries_allowed_countries', $countries);
  }

  /**
   * Check If The Gateway Is Available For Use.
   *
   * @return bool
   */
  public function is_available()
  {
    $order = null;
    $needs_shipping = false;

    // Test if shipping is needed first.
    if (WC()->cart && WC()->cart->needs_shipping()) {
      $needs_shipping = true;
    } elseif (is_page(wc_get_page_id('checkout')) && 0 < get_query_var('order-pay')) {
      $order_id = absint(get_query_var('order-pay'));
      $order = wc_get_order($order_id);

      // Test if order needs shipping.
      if (0 < count($order->get_items())) {
        foreach ($order->get_items() as $item) {
          $_product = $item->get_product();
          if ($_product && $_product->needs_shipping()) {
            $needs_shipping = true;
            break;
          }
        }
      }
    }

    $needs_shipping = apply_filters('woocommerce_cart_needs_shipping', $needs_shipping);

    // Virtual order, with virtual disabled.
    if (!$this->enable_for_virtual && !$needs_shipping) {
      return false;
    }

    // Only apply if all packages are being shipped via chosen method, or order is virtual.
    if (!empty($this->enable_for_methods) && $needs_shipping) {
      $order_shipping_items = is_object($order) ? $order->get_shipping_methods() : false;
      $chosen_shipping_methods_session = WC()->session->get('chosen_shipping_methods');

      if ($order_shipping_items) {
        $canonical_rate_ids = $this->get_canonical_order_shipping_item_rate_ids($order_shipping_items);
      } else {
        $canonical_rate_ids = $this->get_canonical_package_rate_ids($chosen_shipping_methods_session);
      }

      if (!count($this->get_matching_rates($canonical_rate_ids))) {
        return false;
      }
    }

    return parent::is_available();
  }

  /**
   * Checks to see whether or not the admin settings are being accessed by the current request.
   *
   * @return bool
   */
  private function is_accessing_settings()
  {
    if (is_admin()) {
      // phpcs:disable WordPress.Security.NonceVerification
      if (!isset($_REQUEST['page']) || 'wc-settings' !== $_REQUEST['page']) {
        return false;
      }
      if (!isset($_REQUEST['tab']) || 'checkout' !== $_REQUEST['tab']) {
        return false;
      }
      if (!isset($_REQUEST['section']) || 'itella_cod' !== $_REQUEST['section']) {
        return false;
      }
      // phpcs:enable WordPress.Security.NonceVerification

      return true;
    }

    if (Constants::is_true('REST_REQUEST')) {
      global $wp;
      if (isset($wp->query_vars['rest_route']) && false !== strpos($wp->query_vars['rest_route'], '/payment_gateways')) {
        return true;
      }
    }

    return false;
  }

  /**
   * Loads all of the shipping method options for the enable_for_methods field.
   *
   * @return array
   */
  private function load_shipping_method_options()
  {

    // Since this is expensive, we only want to do it if we're actually on the settings page.
    if (!$this->is_accessing_settings()) {
      return array();
    }

    $data_store = WC_Data_Store::load('shipping-zone');
    $raw_zones = $data_store->get_zones();

    foreach ($raw_zones as $raw_zone) {
      $zones[] = new WC_Shipping_Zone($raw_zone);
    }

    $zones[] = new WC_Shipping_Zone(0);

    $options = array();
    foreach (WC()->shipping()->load_shipping_methods() as $method) {

      if (stripos($method->get_method_title(), 'itella') !== false) { //show only itella shipping methods
        $options[$method->get_method_title()] = array();

        // Translators: %1$s shipping method name.
        $options[$method->get_method_title()][$method->id] = sprintf(__('Any &quot;%1$s&quot; method', 'itella_cod'), $method->get_method_title());

        foreach ($zones as $zone) {

          $shipping_method_instances = $zone->get_shipping_methods();

          foreach ($shipping_method_instances as $shipping_method_instance_id => $shipping_method_instance) {

            if ($shipping_method_instance->id !== $method->id) {
              continue;
            }

            $option_id = $shipping_method_instance->get_rate_id();

            // Translators: %1$s shipping method title, %2$s shipping method id.
            $option_instance_title = sprintf(__('%1$s (#%2$s)', 'itella_cod'), $shipping_method_instance->get_title(), $shipping_method_instance_id);

            // Translators: %1$s zone name, %2$s shipping method instance name.
            $option_title = sprintf(__('%1$s &ndash; %2$s', 'itella_cod'), $zone->get_id() ? $zone->get_zone_name() : __('Other locations', 'itella_cod'), $option_instance_title);

            $options[$method->get_method_title()][$option_id] = $option_title;
          }
        }
      }
    }
    if (empty($options)) {
      $options['no_data'] = "Couldn't find any Itella shipping methods. Check if Itella shipping plugin is installed";
    }
//    var_dump($options);
//    die;
    return $options;
  }

  /**
   * Converts the chosen rate IDs generated by Shipping Methods to a canonical 'method_id:instance_id' format.
   *
   * @param array $order_shipping_items Array of WC_Order_Item_Shipping objects.
   * @return array $canonical_rate_ids    Rate IDs in a canonical format.
   * @since  3.4.0
   *
   */
  private function get_canonical_order_shipping_item_rate_ids($order_shipping_items)
  {

    $canonical_rate_ids = array();

    foreach ($order_shipping_items as $order_shipping_item) {
      $canonical_rate_ids[] = $order_shipping_item->get_method_id() . ':' . $order_shipping_item->get_instance_id();
    }

    return $canonical_rate_ids;
  }

  /**
   * Converts the chosen rate IDs generated by Shipping Methods to a canonical 'method_id:instance_id' format.
   *
   * @param array $chosen_package_rate_ids Rate IDs as generated by shipping methods. Can be anything if a shipping method doesn't honor WC conventions.
   * @return array $canonical_rate_ids  Rate IDs in a canonical format.
   * @since  3.4.0
   *
   */
  private function get_canonical_package_rate_ids($chosen_package_rate_ids)
  {

    $shipping_packages = WC()->shipping()->get_packages();
    $canonical_rate_ids = array();

    if (!empty($chosen_package_rate_ids) && is_array($chosen_package_rate_ids)) {
      foreach ($chosen_package_rate_ids as $package_key => $chosen_package_rate_id) {
        if (!empty($shipping_packages[$package_key]['rates'][$chosen_package_rate_id])) {
          $chosen_rate = $shipping_packages[$package_key]['rates'][$chosen_package_rate_id];
          $canonical_rate_ids[] = $chosen_rate->get_method_id() . ':' . $chosen_rate->get_instance_id();
        }
      }
    }

    return $canonical_rate_ids;
  }

  /**
   * Indicates whether a rate exists in an array of canonically-formatted rate IDs that activates this gateway.
   *
   * @param array $rate_ids Rate ids to check.
   * @return boolean
   * @since  3.4.0
   *
   */
  private function get_matching_rates($rate_ids)
  {
    // First, match entries in 'method_id:instance_id' format. Then, match entries in 'method_id' format by stripping off the instance ID from the candidates.
    return array_unique(array_merge(array_intersect($this->enable_for_methods, $rate_ids), array_intersect($this->enable_for_methods, array_unique(array_map('wc_get_string_before_colon', $rate_ids)))));
  }

  /**
   * Process the payment and return the result.
   *
   * @param int $order_id Order ID.
   * @return array
   */
  public function process_payment($order_id)
  {
    $order = wc_get_order($order_id);

    if ($order->get_total() > 0) {
      // Mark as processing or on-hold (payment won't be taken until delivery).
      $order->update_status(apply_filters('woocommerce_cod_process_payment_order_status', $order->has_downloadable_item() ? 'on-hold' : 'processing', $order), __('Payment to be made upon delivery.', 'itella_cod'));
    } else {
      $order->payment_complete();
    }

    // Remove cart.
    WC()->cart->empty_cart();

    // Return thankyou redirect.
    return array(
        'result' => 'success',
        'redirect' => $this->get_return_url($order),
    );
  }

  /**
   * Output for the order received page.
   */
  public function thankyou_page()
  {
    if ($this->instructions) {
      echo wp_kses_post(wpautop(wptexturize($this->instructions)));
    }
  }

  /**
   * Change payment complete order status to completed for COD orders.
   *
   * @param string $status Current order status.
   * @param int $order_id Order ID.
   * @param WC_Order|false $order Order object.
   * @return string
   * @since  3.1.0
   */
  public function change_payment_complete_order_status($status, $order_id = 0, $order = false)
  {
    if ($order && 'cod' === $order->get_payment_method()) {
      $status = 'completed';
    }
    return $status;
  }

  /**
   * Add content to the WC emails.
   *
   * @param WC_Order $order Order object.
   * @param bool $sent_to_admin Sent to admin.
   * @param bool $plain_text Email format: plain text or HTML.
   */
  public function email_instructions($order, $sent_to_admin, $plain_text = false)
  {
    if ($this->instructions && !$sent_to_admin && $this->id === $order->get_payment_method()) {
      echo wp_kses_post(wpautop(wptexturize($this->instructions)) . PHP_EOL);
    }
  }
}
