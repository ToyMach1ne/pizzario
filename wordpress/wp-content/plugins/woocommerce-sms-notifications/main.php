<?php
class Clockwork_WooCommerce_Plugin extends Clockwork_Plugin {

  protected $plugin_name = 'WooCommerce';
  protected $language_string = 'clockwork_woocommerce';
  protected $prefix = 'clockwork_woocommerce';
  protected $folder = '';

  protected $statuses = array();

  /**
   * Constructor: setup callbacks and plugin-specific options
   *
   * @author James Inman
   */
  public function __construct() {
    parent::__construct();

    // Set the plugin's Clockwork SMS menu to load the contact forms
    $this->plugin_callback = array( $this, 'clockwork_woocommerce' );
    $this->plugin_dir = basename( dirname( __FILE__ ) );

		$this->statuses = array(
			'pending' 		=> __( 'Pending', 'woothemes' ),
			'on-hold' 		=> __( 'On-Hold', 'woothemes' ),
			'processing' 	=> __( 'Processing', 'woothemes' ),
			'completed' 	=> __( 'Completed', 'woothemes' ),
			'cancelled' 	=> __( 'Cancelled', 'woothemes' ),
			'refunded' 		=> __( 'Refunded', 'woothemes' ),
			'failed' 		=> __( 'Failed', 'woothemes' ),
		);
  }

  /**
   * Setup the admin navigation
   *
   * @return void
   * @author James Inman
   */
  public function setup_admin_navigation() {
    parent::setup_admin_navigation();
  }

  /**
  * Register settings and callbacks for this plugin
  *
  * @return void
  * @author James Inman
   */
  public function setup_admin_init() {
    parent::setup_admin_init();

    // Register general settings
    register_setting( 'clockwork_woocommerce', 'clockwork_woocommerce', array( $this, 'main_options_validate' ) );
    add_settings_section( 'clockwork_woocommerce', 'General Settings', array( $this, 'general_settings_text' ), 'clockwork_woocommerce' );
    add_settings_field( 'from', 'Store Name', array( $this, 'store_name_input' ), 'clockwork_woocommerce', 'clockwork_woocommerce' );

    // Register admin SMS settings
    register_setting( 'clockwork_woocommerce_admin_sms', 'clockwork_woocommerce_admin_sms', array( $this, 'admin_sms_options_validate' ) );
    add_settings_section( 'clockwork_woocommerce_admin_sms', 'Admin SMS Notifications', array( $this, 'admin_settings_text' ), 'clockwork_woocommerce_admin_sms' );
    add_settings_field( 'enabled', 'Enabled', array( $this, 'admin_enabled_input' ), 'clockwork_woocommerce_admin_sms', 'clockwork_woocommerce_admin_sms' );
    add_settings_field( 'mobile', 'Mobile Number', array( $this, 'admin_mobile_number_input' ), 'clockwork_woocommerce_admin_sms', 'clockwork_woocommerce_admin_sms' );
    add_settings_field( 'message', 'Message', array( $this, 'admin_message_input' ), 'clockwork_woocommerce_admin_sms', 'clockwork_woocommerce_admin_sms' );

    // Register customer SMS settings
    register_setting( 'clockwork_woocommerce_customer_sms', 'clockwork_woocommerce_customer_sms', array( $this, 'admin_sms_options_validate' ) );
    add_settings_section( 'clockwork_woocommerce_customer_sms', 'Customer SMS Notifications', array( $this, 'customer_settings_text' ), 'clockwork_woocommerce_customer_sms' );
    add_settings_field( 'enabled', 'Send notifications on these statuses', array( $this, 'customer_enabled_input' ), 'clockwork_woocommerce_customer_sms', 'clockwork_woocommerce_customer_sms' );

    foreach( $this->statuses as $status => $status_name ) {
      add_settings_field( str_replace( '-', '', $status ) . "_message", $status_name . ' Message', array( $this, 'customer_' . str_replace( '-', '', $status ) . '_message' ), 'clockwork_woocommerce_customer_sms', 'clockwork_woocommerce_customer_sms' );
    }

    // Add actions
    add_action( 'woocommerce_order_status_pending_to_processing', array( $this, 'admin_new_order_notification' ) );
    add_action( 'woocommerce_order_status_pending_to_completed', array( $this, 'admin_new_order_notification' ) );
    add_action( 'woocommerce_order_status_pending_to_on-hold', array( $this, 'admin_new_order_notification' ) );
    add_action( 'woocommerce_order_status_failed_to_processing', array( $this, 'admin_new_order_notification' ) );
    add_action( 'woocommerce_order_status_failed_to_completed', array( $this, 'admin_new_order_notification' ) );

    foreach( $this->statuses as $slug => $status ) {
  		add_action( "woocommerce_order_status_{$slug}", array( $this, 'order_status_change_notification' ) );
    }
  }

  /**
   * Setup HTML for the admin <head>
   *
   * @return void
   * @author James Inman
   */
  public function setup_admin_head() {
    echo '<link rel="stylesheet" type="text/css" href="' . plugins_url( 'css/clockwork.css', __FILE__ ) . '">';
  }

  /**
   * Function to provide a callback for the main plugin action page
   *
   * @return void
   * @author James Inman
   */
  public function clockwork_woocommerce() {
    $this->render_template( 'form-options' );
  }

  /**
   * Check if username and password have been entered
   *
   * @return void
   * @author James Inman
   */
  public function get_existing_username_and_password() {
    $options = get_option( 'mbesms' );

    if( is_array( $options ) && isset( $options['username'] ) && isset( $options['password'] ) ) {
      return array( 'username' => $options['username'], 'password' => $options['password'] );
    }

    return false;
  }

  /**
   * Main text for the admin settings
   *
   * @return void
   * @author James Inman
   */
  public function admin_settings_text() {
    echo '<p>You can choose to send a nominated mobile phone an SMS notification whenever a new order is placed on your store.</p>';
  }

  /**
   * Main text for the customer settings
   *
   * @return void
   * @author James Inman
   */
  public function customer_settings_text() {
    // echo '<p>You can choose to send a nominated mobile phone an SMS notification whenever a new order is placed on your store.</p>';
    echo __( 'The following tags can be used in your SMS messages, though please bear in mind that they may take you over your character limits (for example if your shop name is very long): <kbd>%purchase_id%</kbd>, <kbd>%shop_name%</kbd>, <kbd>%total_price%</kbd>', 'woocommercesms' );
  }

  /**
   * Input box for the mobile number
   *
   * @return void
   * @author James Inman
   */
  public function admin_mobile_number_input() {
    $options = get_option( 'clockwork_woocommerce_admin_sms' );
    if( isset( $options['mobile'] ) ) {
      echo '<input id="clockwork_woocommerce_admin_sms_admin_mobile" name="clockwork_woocommerce_admin_sms[mobile]" size="40" type="text" value="' . $options['mobile'] . '" />';
    } else {
      echo '<input id="clockwork_woocommerce_admin_sms_admin_mobile" name="clockwork_woocommerce_admin_sms[mobile]" size="40" type="text" value="" />';
    }
		echo ' <p class="description">' . __('International format, starting with a country code e.g. 447123456789. You can enter multiple mobile numbers seperated by a comma.', 'clockwork_woocommerce') . '</p>';
  }

  /**
   * Whether admin settings are enabled
   *
   * @return void
   * @author James Inman
   */
  public function admin_enabled_input() {
    $options = get_option( 'clockwork_woocommerce_admin_sms' );
    if( isset( $options['enabled'] ) && ( $options['enabled'] == true ) ) {
      echo '<input id="clockwork_woocommerce_admin_sms_enabled" name="clockwork_woocommerce_admin_sms[enabled]" type="checkbox" checked="checked" value="1" />';
    } else {
      echo '<input id="clockwork_woocommerce_admin_sms_enabled" name="clockwork_woocommerce_admin_sms[enabled]" type="checkbox" value="1" />';
    }
		echo ' <p class="description">' . __('If this option is checked, your nominated mobile number will be sent a new SMS when a new order is placed.', 'clockwork_woocommerce') . '</p>';
  }

  /**
   * Input box for the mobile number
   *
   * @return void
   * @author James Inman
   */
  public function customer_enabled_input() {
    $options = get_option( 'clockwork_woocommerce_customer_sms' );
    foreach( $this->statuses as $status => $status_name ) {
      $status = str_replace( '-', '', $status );
      if( isset( $options[ $status ] ) ) {
        echo '<label><input id="clockwork_woocommerce_customer_sms_' . $status . '" name="clockwork_woocommerce_customer_sms[' . $status . ']" type="checkbox" checked="checked" value="1" />&nbsp;&nbsp;&nbsp;' . $status_name . '</label><br />';
      } else {
        echo '<label><input id="clockwork_woocommerce_customer_sms_' . $status . '" name="clockwork_woocommerce_customer_sms[' . $status . ']" type="checkbox" value="1" />&nbsp;&nbsp;&nbsp;' . $status_name . '</label><br />';
      }
    }
  }

  /**
   * Validation for main SMS options
   *
   * @param string $val
   * @return void
   * @author James Inman
   */
  public function main_options_validate( $val ) {
    // 11 characters for 'from'
    $val['from'] = substr( $val['from'], 0, 11 );
    return $val;
  }

  /**
   * Validation for admin SMS options
   *
   * @param string $val
   * @return void
   * @author James Inman
   */
  public function admin_sms_options_validate( $val ) {
    // First, switch enabled
    if( !isset( $val['enabled'] ) || !$val['enabled'] || empty( $val['enabled'] ) ) {
      $val['enabled'] = false;
    } else {
      $val['enabled'] = true;
    }

    // Then, check mobile number
    if( $val['enabled'] == true ) {
        $msisdns = explode( ',', $val['mobile'] );
        foreach( $msisdns as $msisdn ) {
            if( !Clockwork::is_valid_msisdn( trim( $msisdn ) ) ) {
                add_settings_error( 'clockwork_options', 'clockwork_options', 'You must enter a valid mobile number in international MSISDN format, starting with a country code, e.g. 447123456789.', 'error' );
                $val['mobile'] = '';
            }
        }
    }

    return $val;
  }

  /**
   * Form field for the message to send to administrators
   *
   * @return void
   * @author James Inman
   */
  public function admin_message_input() {
    $options = get_option( 'clockwork_woocommerce_admin_sms' );

    if( isset( $options['message'] ) ) {
      echo '<textarea id="clockwork_woocommerce_admin_sms_message" name="clockwork_woocommerce_admin_sms[message]" rows="3" cols="45">' . $options['message'] . '</textarea>';
    } else {
      echo '<textarea id="clockwork_woocommerce_admin_sms_message" name="clockwork_woocommerce_admin_sms[message]" rows="3" cols="45"></textarea>';
    }
    echo ' <p class="description">' . __( 'The following tags can be used in your SMS messages, though please bear in mind that they may take you over your character limits (for example if your shop name is very long): <kbd>%purchase_id%</kbd>, <kbd>%shop_name%</kbd>, <kbd>%total_price%</kbd>', 'woocommercesms' ) . '</p>';
  }

  /**
   * Send an admin notification on new orders being placed
   *
   * @param string $order_id WooCommerce order ID
   * @return void
   * @author James Inman
   */
  public function admin_new_order_notification( $order_id ) {
    $order = $this->get_order( $order_id );
    $options = array_merge( get_option( 'clockwork_options' ), get_option( 'clockwork_woocommerce' ), get_option( 'clockwork_woocommerce_admin_sms' ) );

    if( $options['enabled'] == true ) {
      // Setup message
      $message = $options['message'];
      $message = str_replace( '%shop_name%', $options['from'], $message );
      $message = str_replace( '%purchase_id%', $order->id, $message );
      $message = str_replace( '%total_price%', $this->format_price( $order->order_total ), $message );
      $message = utf8_encode( $message );
      $mobile = explode( ',', $options['mobile'] );

      // Send the message
      try {
        $clockwork = new WordPressClockwork( $options['api_key'] );
        $messages = array();
        foreach( $mobile as $to ) {
          $messages[] = array( 'from' => $options['from'], 'to' => $to, 'message' => $message );
        }
        $result = $clockwork->send( $messages );
      } catch( ClockworkException $e ) {
        $result = "Error: " . $e->getMessage();
      } catch( Exception $e ) {
        $result = "Error: " . $e->getMessage();
      }
    }
  }

  /**
   * Send the customer a notification when the order status changes
   *
   * @param string $order_id WooCommerce order ID
   * @return void
   * @author James Inman
   */
  public function order_status_change_notification( $order_id ) {
		$order = $this->get_order( $order_id );
    $options = array_merge( get_option( 'clockwork_options' ), get_option( 'clockwork_woocommerce' ), get_option( 'clockwork_woocommerce_customer_sms' ) );
		$mobile = $this->format_mobile_number( $order->billing_phone, $order->billing_country );

    // Don't send order status change notification if the option's not set
    if( !isset( $options[ str_replace( '-', '', $order->status ) ] ) ) {
      return;
    }

  	$tracking_provider 	= get_post_meta( $order_id, '_tracking_provider', true );
  	$tracking_number 	= get_post_meta( $order_id, '_tracking_number', true );
  	$date_shipped		= get_post_meta( $order_id, '_date_shipped', true );

    $message = $options[ str_replace( '-', '', $order->status ) . '_message' ];
    $message = str_replace( '%shop_name%', $options['from'], $message );
    $message = str_replace( '%purchase_id%', $order->get_order_number(), $message );
    $message = str_replace( '%total_price%', $this->format_price( $order->order_total ), $message );
    $message = str_replace( '%order_status%', $this->statuses[ $order->status ], $message );

    if( isset( $tracking_provider ) ) {
      $message = str_replace( '%tracking_provider%', $tracking_provider, $message );
    }

    if( isset( $tracking_number ) ) {
      $message = str_replace( '%tracking_number%', $tracking_number, $message );
    }

    $message = utf8_encode( $message );

    try {
      $clockwork = new WordPressClockwork( $options['api_key'] );
      $messages = array();
      $messages[] = array( 'from' => $options['from'], 'to' => $mobile, 'message' => $message );
      $result = $clockwork->send( $messages );
    } catch( ClockworkException $e ) {
      $result = "Error: " . $e->getMessage();
    } catch( Exception $e ) {
      $result = "Error: " . $e->getMessage();
    }
  }

  /**
   * Text for general settings
   *
   * @return void
   * @author James Inman
   */
  public function general_settings_text() {
    echo '<p>General settings that apply to all notifications sent from your store.</p>';
  }

  /**
   * Input for the store name
   *
   * @return void
   * @author James Inman
   */
  public function store_name_input() {
    $options = get_option( 'clockwork_woocommerce' );

    if( isset( $options['from'] ) ) {
      echo '<input type="text" id="clockwork_woocommerce_from" name="clockwork_woocommerce[from]" size="40" value="' . $options['from'] . '" />';
    } else {
      echo '<input type="text" id="clockwork_woocommerce_from" name="clockwork_woocommerce[from]" size="40" value="" />';
    }
    echo ' <p class="description">' . __( 'The name of your store, replaced from <kbd>%shop_name%</kbd> in messages. 11 characters or less.', 'woocommercesms' ) . '</p>';
  }

  /**
   * Form field for the message to send to customers on pending status
   *
   * @return void
   * @author James Inman
   */
  public function customer_pending_message() {
    $options = get_option( 'clockwork_woocommerce_customer_sms' );

    if( isset( $options['pending_message'] ) ) {
      echo '<textarea id="clockwork_woocommerce_customer_sms_pending_message" name="clockwork_woocommerce_customer_sms[pending_message]" rows="3" cols="45">' . $options['pending_message'] . '</textarea>';
    } else {
      echo '<textarea id="clockwork_woocommerce_customer_sms_pending_message" name="clockwork_woocommerce_customer_sms[pending_message]" rows="3" cols="45"></textarea>';
    }
  }

  /**
   * Form field for the message to send to customers on onhold status
   *
   * @return void
   * @author James Inman
   */
  public function customer_onhold_message() {
    $options = get_option( 'clockwork_woocommerce_customer_sms' );

    if( isset( $options['onhold_message'] ) ) {
      echo '<textarea id="clockwork_woocommerce_customer_sms_onhold_message" name="clockwork_woocommerce_customer_sms[onhold_message]" rows="3" cols="45">' . $options['onhold_message'] . '</textarea>';
    } else {
      echo '<textarea id="clockwork_woocommerce_customer_sms_onhold_message" name="clockwork_woocommerce_customer_sms[onhold_message]" rows="3" cols="45"></textarea>';
    }
  }

  /**
   * Form field for the message to send to customers on processing status
   *
   * @return void
   * @author James Inman
   */
  public function customer_processing_message() {
    $options = get_option( 'clockwork_woocommerce_customer_sms' );

    if( isset( $options['processing_message'] ) ) {
      echo '<textarea id="clockwork_woocommerce_customer_sms_processing_message" name="clockwork_woocommerce_customer_sms[processing_message]" rows="3" cols="45">' . $options['processing_message'] . '</textarea>';
    } else {
      echo '<textarea id="clockwork_woocommerce_customer_sms_processing_message" name="clockwork_woocommerce_customer_sms[processing_message]" rows="3" cols="45"></textarea>';
    }
  }

  /**
   * Form field for the message to send to customers on completed status
   *
   * @return void
   * @author James Inman
   */
  public function customer_completed_message() {
    $options = get_option( 'clockwork_woocommerce_customer_sms' );

    if( isset( $options['completed_message'] ) ) {
      echo '<textarea id="clockwork_woocommerce_customer_sms_completed_message" name="clockwork_woocommerce_customer_sms[completed_message]" rows="3" cols="45">' . $options['completed_message'] . '</textarea>';
    } else {
      echo '<textarea id="clockwork_woocommerce_customer_sms_completed_message" name="clockwork_woocommerce_customer_sms[completed_message]" rows="3" cols="45"></textarea>';
    }
  }

  /**
   * Form field for the message to send to customers on cancelled status
   *
   * @return void
   * @author James Inman
   */
  public function customer_cancelled_message() {
    $options = get_option( 'clockwork_woocommerce_customer_sms' );

    if( isset( $options['cancelled_message'] ) ) {
      echo '<textarea id="clockwork_woocommerce_customer_sms_cancelled_message" name="clockwork_woocommerce_customer_sms[cancelled_message]" rows="3" cols="45">' . $options['cancelled_message'] . '</textarea>';
    } else {
      echo '<textarea id="clockwork_woocommerce_customer_sms_cancelled_message" name="clockwork_woocommerce_customer_sms[cancelled_message]" rows="3" cols="45"></textarea>';
    }
  }

  /**
   * Form field for the message to send to customers on refunded status
   *
   * @return void
   * @author James Inman
   */
  public function customer_refunded_message() {
    $options = get_option( 'clockwork_woocommerce_customer_sms' );

    if( isset( $options['refunded_message'] ) ) {
      echo '<textarea id="clockwork_woocommerce_customer_sms_refunded_message" name="clockwork_woocommerce_customer_sms[refunded_message]" rows="3" cols="45">' . $options['refunded_message'] . '</textarea>';
    } else {
      echo '<textarea id="clockwork_woocommerce_customer_sms_refunded_message" name="clockwork_woocommerce_customer_sms[refunded_message]" rows="3" cols="45"></textarea>';
    }
  }

  /**
   * Form field for the message to send to customers on failed status
   *
   * @return void
   * @author James Inman
   */
  public function customer_failed_message() {
    $options = get_option( 'clockwork_woocommerce_customer_sms' );

    if( isset( $options['failed_message'] ) ) {
      echo '<textarea id="clockwork_woocommerce_customer_sms_failed_message" name="clockwork_woocommerce_customer_sms[failed_message]" rows="3" cols="45">' . $options['failed_message'] . '</textarea>';
    } else {
      echo '<textarea id="clockwork_woocommerce_customer_sms_failed_message" name="clockwork_woocommerce_customer_sms[failed_message]" rows="3" cols="45"></textarea>';
    }
  }

	/**
	 * Gets the Order object.
	 *
	 * @param int $order_id The ID of the order to retrieve
	 * @return Order object
   * @author Simon Wheatley
	 **/
	protected function get_order( $order_id ) {
		$order = new WC_Order();
		$order->get_order( $order_id );
		return $order;
	}

  /**
   * Format a price according to the WooCommerce currency
   *
   * @param string $price Price to format
   * @return string Price
   * @author James Inman
   */
  public function format_price( $price ) {
  	$num_decimals = (int) get_option( 'woocommerce_price_num_decimals' );
  	$currency_pos = get_option( 'woocommerce_currency_pos' );
  	$currency_symbol = html_entity_decode( get_woocommerce_currency_symbol() );

  	$price = apply_filters( 'raw_woocommerce_price', (double) $price );

  	$price = number_format( $price, $num_decimals, stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ), stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ) );

  	if ( get_option( 'woocommerce_price_trim_zeros' ) == 'yes' && $num_decimals > 0 ) {
  		$price = woocommerce_trim_zeros( $price );
    }

  	switch ( $currency_pos ) {
  		case 'left' :
  			$return = $currency_symbol . $price;
  		break;
  		case 'right' :
  			$return = $price . $currency_symbol;
  		break;
  		case 'left_space' :
  			$return = $currency_symbol . $price;
  		break;
  		case 'right_space' :
  			$return = $price . $currency_symbol;
  		break;
  	}

    return $return;
  }

  /**
   * Takes a mobile number and a country and makes it all nice
   * and ready for the API:
   * * Remove leading '0'
   * * Detect or add country code
   * * Strip any weird characters and spaces
   *
   * @param string $mobile_number The mobile number to fixup
   * @param string $country_isocode The two letter ISO country code to get the dialling prefix for
   * @return string A formatted mobile phone number
   * @author Simon Wheatley
   **/
  protected function format_mobile_number( $mobile_number, $country_isocode ) {
  	$seq = $mobile_number;

  	// First remove any whitespace
  	$start = $mobile_number;
  	$mobile_number = preg_replace( '/\s/', '', $mobile_number );

  	$country_dial = WordPressClockwork::$country_codes[$country_isocode];

  	// Attempt to detect a country prefix by looking for:
  	// * "+" at the start of the mobile number
  	// * Any digits preceding a parentheses, e.g. "44(0)797…"
  	if ( '+' != substr( $mobile_number, 0, 1 ) && ! preg_match( '/^\d+\(/', $mobile_number ) ) {
  		// Strip any leading zero
  		$mobile_number = preg_replace( '/^0/', '', $mobile_number );

  		// No country code detected, so add one
  		if ( $country_dial != substr( $mobile_number, 0, strlen( $country_dial ) ) ) {
  			// The number doesn't start with the country code, so add it now
  			$mobile_number = $country_dial . $mobile_number;
  		}

  	}

  	// Remove parentheses and anything betwixt them
  	$mobile_number = preg_replace( '/\(\d+\)/', '', $mobile_number );

  	// Remove anything that isn't a number
  	$mobile_number = preg_replace( '/[^0-9]/', '', $mobile_number );

  	// The number starts with the expected country code, remove any zero which
  	// immediately follows the country code.
  	if ( $country_dial == substr( $mobile_number, 0, strlen( $country_dial ) ) ) {
  		$mobile_number = preg_replace( "/^{$country_dial}(\s*)?0/", $country_dial, $mobile_number );
    }

  	return $mobile_number;
  }


}

$cp = new Clockwork_WooCommerce_Plugin();
