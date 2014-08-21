<?php include '/Users/shalinga/workspace/php/msl/wp-content/plugins/msl-LGPSClient-payment-gateway-woocommerce/lib/LGPSClient.php'; ?>
<?php include '/Users/shalinga/workspace/php/msl/wp-content/plugins/msl-LGPSClient-payment-gateway-woocommerce/lib/lgps_config.php'; ?>

<?php
/*
Plugin Name: WooCommerce LGPSClient Payment Gateway
Plugin URI: http://www.venturit.com
Description: LGPSClient Payment gateway for woocommerce
Version: 1.2
Author: Sunny Luthra
Author URI: http://www.venturit.com
*/
add_action('plugins_loaded', 'woocommerce_lgps_client_init', 0);
/* Runs when plugin is activated */
register_activation_hook(__FILE__,'my_plugin_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'my_plugin_remove' );

if ( ! defined( 'WPINC' ) ) {
	die;
} // end if

require_once( plugin_dir_path( __FILE__ ) . 'class-page-template-example.php' );
add_action( 'plugins_loaded', array( 'Page_Template_Plugin', 'get_instance' ) );

function my_plugin_install() {

    global $wpdb;

    $the_page_title = 'Thank You';
    $the_page_name = 'thank-you';

    // the menu entry...
    delete_option("my_plugin_page_title");
    add_option("my_plugin_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("my_plugin_page_name");
    add_option("my_plugin_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("my_plugin_page_id");
    add_option("my_plugin_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "This text may be overridden by the plugin. You shouldn't edit it.";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );

    }
    else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );

    }

    delete_option( 'my_plugin_page_id' );
    add_option( 'my_plugin_page_id', $the_page_id );

}

function my_plugin_remove() {

    global $wpdb;

    $the_page_title = get_option( "my_plugin_page_title" );
    $the_page_name = get_option( "my_plugin_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'my_plugin_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    delete_option("my_plugin_page_title");
    delete_option("my_plugin_page_name");
    delete_option("my_plugin_page_id");

}

function woocommerce_lgps_client_init(){
  if(!class_exists('WC_Payment_Gateway')) return;

  class Random {

      private static $RSeed = 0;

      public static function seed($s = 0) {
          self::$RSeed = abs(intval($s)) % 9999999 + 1;
          self::num();
      }

      public static function num($min = 0, $max = 9999999) {
          if (self::$RSeed == 0)
              self::seed(mt_rand());
          self::$RSeed = (self::$RSeed * 125) % 2796203;
          return self::$RSeed % ($max - $min + 1) + $min;
      }

  }

  class WC_LGPS_Client extends WC_Payment_Gateway{
    public function __construct(){
	
			$this->id                = 'lgpsclient';
			$this->icon              = apply_filters( 'woocommerce_lgpsclient_icon', WC()->plugin_url() . '/assets/images/icons/paypal.png' );
			$this->has_fields        = false;
			$this->order_button_text = __( 'Proceed to LGPS Client', 'woocommerce' );
			$this->liveurl           = 'https://testlgps.lankagate.gov.lk:9443/lgps/accesslgps';
			$this->testurl           = 'https://testlgps.lankagate.gov.lk:9443/lgps/accesslgps';
			$this->method_title      = __( 'LGPS Client', 'woocommerce' );
			$this->notify_url        = WC()->api_request_url( 'WC_LGPS_Client' );

			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();

			// Define user set variables
			$this->title 			= $this->get_option( 'title' );
			$this->description 		= $this->get_option( 'description' );
			$this->email 			= $this->get_option( 'email' );
			$this->receiver_email   = $this->get_option( 'receiver_email', $this->email );
			$this->testmode			= $this->get_option( 'testmode' );
			$this->send_shipping	= $this->get_option( 'send_shipping' );
			$this->address_override	= $this->get_option( 'address_override' );
			$this->debug			= $this->get_option( 'debug' );
			$this->form_submission_method = $this->get_option( 'form_submission_method' ) == 'yes' ? true : false;
			$this->page_style 		= $this->get_option( 'page_style' );
			$this->invoice_prefix	= $this->get_option( 'invoice_prefix', 'WC-' );
			$this->paymentaction    = $this->get_option( 'paymentaction', 'sale' );
			$this->identity_token   = $this->get_option( 'identity_token', '' );
      $this->redirect_page_id = $this ->get_option('redirect_page_id');

			// Logs
			if ( 'yes' == $this->debug ) {
				$this->log = new WC_Logger();
			}

			// Actions
			add_action( 'valid-lgpsclient-standard-ipn-request', array( $this, 'successful_request' ) );
			add_action( 'woocommerce_receipt_lgpsclient', array( $this, 'receipt_page' ) );
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_lgpsclient', array( $this, 'pdt_return_handler' ) );
      // add_action('init', array(&$this, 'check_lgpsclient_response'));

			// Payment listener/API hook
			add_action( 'woocommerce_api_wc_gateway_lgpsclient', array( $this, 'check_ipn_response' ) );
   }

    function init_form_fields(){

			$this->form_fields = array(
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'woocommerce' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable LGPS Client standard', 'woocommerce' ),
					'default' => 'yes'
				),
				'title' => array(
					'title'       => __( 'Title', 'woocommerce' ),
					'type'        => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
					'default'     => __( 'LGPS Client', 'woocommerce' ),
					'desc_tip'    => true,
				),
				'description' => array(
					'title'       => __( 'Description', 'woocommerce' ),
					'type'        => 'textarea',
					'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ),
					'default'     => __( 'Pay via LGPS Client; you can pay with your credit card if you don\'t have a LGPS Client account', 'woocommerce' )
				),
				'email' => array(
					'title'       => __( 'LGPS Client Email', 'woocommerce' ),
					'type'        => 'email',
					'description' => __( 'Please enter your LGPS Client email address; this is needed in order to take payment.', 'woocommerce' ),
					'default'     => '',
					'desc_tip'    => true,
					'placeholder' => 'you@youremail.com'
				),
				'receiver_email' => array(
					'title'       => __( 'Receiver Email', 'woocommerce' ),
					'type'        => 'email',
					'description' => __( 'If this differs from the email entered above, input your main receiver email for your LGPS Client account. This is used to validate IPN requests.', 'woocommerce' ),
					'default'     => '',
					'desc_tip'    => true,
					'placeholder' => 'you@youremail.com'
				),
				'identity_token' => array(
					'title'       => __( 'LGPS Client Identity Token', 'woocommerce' ),
					'type'        => 'text',
					'description' => __( 'Optionally enable "Payment Data Transfer" (Profile > Website Payment Preferences) and then copy your identity token here. This will allow payments to be verified without the need for LGPS Client IPN.', 'woocommerce' ),
					'default'     => '',
					'desc_tip'    => true,
					'placeholder' => __( 'Optional', 'woocommerce' )
				),
				'invoice_prefix' => array(
					'title'       => __( 'Invoice Prefix', 'woocommerce' ),
					'type'        => 'text',
					'description' => __( 'Please enter a prefix for your invoice numbers. If you use your LGPS Client account for multiple stores ensure this prefix is unique as LGPS Client will not allow orders with the same invoice number.', 'woocommerce' ),
					'default'     => 'WC-',
					'desc_tip'    => true,
				),
				'paymentaction' => array(
					'title'       => __( 'Payment Action', 'woocommerce' ),
					'type'        => 'select',
					'description' => __( 'Choose whether you wish to capture funds immediately or authorize payment only.', 'woocommerce' ),
					'default'     => 'sale',
					'desc_tip'    => true,
					'options'     => array(
						'sale'          => __( 'Capture', 'woocommerce' ),
						'authorization' => __( 'Authorize', 'woocommerce' )
					)
				),
				'form_submission_method' => array(
					'title'       => __( 'Submission method', 'woocommerce' ),
					'type'        => 'checkbox',
					'label'       => __( 'Use form submission method.', 'woocommerce' ),
					'description' => __( 'Enable this to post order data to LGPS Client via a form instead of using a redirect/querystring.', 'woocommerce' ),
					'default'     => 'no'
				),
				'page_style' => array(
					'title'       => __( 'Page Style', 'woocommerce' ),
					'type'        => 'text',
					'description' => __( 'Optionally enter the name of the page style you wish to use. These are defined within your LGPS Client account.', 'woocommerce' ),
					'default'     => '',
					'desc_tip'    => true,
					'placeholder' => __( 'Optional', 'woocommerce' )
				),
				'shipping' => array(
					'title'       => __( 'Shipping options', 'woocommerce' ),
					'type'        => 'title',
					'description' => '',
				),
				'send_shipping' => array(
					'title'       => __( 'Shipping details', 'woocommerce' ),
					'type'        => 'checkbox',
					'label'       => __( 'Send shipping details to LGPS Client instead of billing.', 'woocommerce' ),
					'description' => __( 'LGPS Client allows us to send 1 address. If you are using LGPS Client for shipping labels you may prefer to send the shipping address rather than billing.', 'woocommerce' ),
					'default'     => 'no'
				),
				'address_override' => array(
					'title'       => __( 'Address override', 'woocommerce' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable "address_override" to prevent address information from being changed.', 'woocommerce' ),
					'description' => __( 'LGPS Client verifies addresses therefore this setting can cause errors (we recommend keeping it disabled).', 'woocommerce' ),
					'default'     => 'no'
				),
				'testing' => array(
					'title'       => __( 'Gateway Testing', 'woocommerce' ),
					'type'        => 'title',
					'description' => '',
				),
				'testmode' => array(
					'title'       => __( 'LGPS Client sandbox', 'woocommerce' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable LGPS Client sandbox', 'woocommerce' ),
					'default'     => 'no',
					'description' => sprintf( __( 'LGPS Client sandbox can be used to test payments. Sign up for a developer account <a href="%s">here</a>.', 'woocommerce' ), 'https://developer.lgpsclient.com/' ),
				),
				'debug' => array(
					'title'       => __( 'Debug Log', 'woocommerce' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable logging', 'woocommerce' ),
					'default'     => 'no',
					'description' => sprintf( __( 'Log LGPS Client events, such as IPN requests, inside <code>woocommerce/logs/lgpsclient-%s.txt</code>', 'woocommerce' ), sanitize_file_name( wp_hash( 'lgpsclient' ) ) ),
				),
         'redirect_page_id' => array(
             'title' => __('Return Page'),
             'type' => 'select',
             'options' => $this -> get_pages('Select Page'),
             'description' => "URL of success page"
         )
			);

    }

		/**
		 * Limit the length of item names
		 * @param  string $item_name
		 * @return string
		 */
		public function lgpsclient_item_name( $item_name ) {
			if ( strlen( $item_name ) > 127 ) {
				$item_name = substr( $item_name, 0, 124 ) . '...';
			}
			return html_entity_decode( $item_name, ENT_NOQUOTES, 'UTF-8' );
		}

		/**
		 * Get LGPS Client Args for passing to PP
		 *
		 * @access public
		 * @param mixed $order
		 * @return array
		 */
		function get_lgpsclient_args( $order ) {

			$timestamp = time();
	 //   $timestamp = $date->getTimestamp();
	    Random::seed($timestamp);
	//Generate Radom Transaction No
	    $rand_tx_no = Random::num(1, 1000000);
	
			$order_id = $order->id;

			if ( 'yes' == $this->debug ) {
				$this->log->add( 'lgpsclient', 'Generating payment form for order ' . $order->get_order_number() . '. Notify URL: ' . $this->notify_url );
			}

			if ( in_array( $order->billing_country, array( 'US','CA' ) ) ) {
				$order->billing_phone = str_replace( array( '(', '-', ' ', ')', '.' ), '', $order->billing_phone );
				$phone_args = array(
					'night_phone_a' => substr( $order->billing_phone, 0, 3 ),
					'night_phone_b' => substr( $order->billing_phone, 3, 3 ),
					'night_phone_c' => substr( $order->billing_phone, 6, 4 ),
					'day_phone_a' 	=> substr( $order->billing_phone, 0, 3 ),
					'day_phone_b' 	=> substr( $order->billing_phone, 3, 3 ),
					'day_phone_c' 	=> substr( $order->billing_phone, 6, 4 )
				);
			} else {
				$phone_args = array(
					'night_phone_b' => $order->billing_phone,
					'day_phone_b' 	=> $order->billing_phone
				);
			}
			
				$service_code ="TEST20001";
				
				$tx_amount = $order->order_total;
				
				$tx_ref_no = $order->id;
				
				// $return_url = "http://www.msl.lk/thank-you"; // /order-received/".$order->id.""; ?key=wc_order_53f3abfa2ad5c";
				$return_url = get_permalink($this -> redirect_page_id);
				
				$cipher_message = null;

				try {

				    $lgpsClientReq = new LGPSClient(); //Create LGPSClient Object to handle Payment Request   

				    $lgpsClientReq->setLogs(LOG_DIRECTORY_PATH,ENABLE_LOGS);

				    $lgpsClientReq->setClientPublicKey(CLIENT_PUBLIC_KEY); //Set Client Public Key
				    $lgpsClientReq->setClientPrivateKey(CLIENT_PRIVATE_KEY, CLIENT_KEY_PASSWORD); //Set Client Private Key
				    $lgpsClientReq->setLgpsPublicKey(LGPS_PUBLIC_KEY); //Set LGPS public Key

				    $lgpsClientReq->setServiceCode($service_code); //Set Service code to Payment request
				    $lgpsClientReq->setTransactionRefNo($tx_ref_no); //Set Transaction Ref No. to Payment request
				    $lgpsClientReq->setTransactionAmount($tx_amount); //Set Transaction Amount to Payment request
				    $lgpsClientReq->setReturnURL($return_url); //Set Return URL to Payment request

				    $cipher_message = $lgpsClientReq->getPaymentRequest(); //Generate Encrypted Payment Request  

				} catch (LGPSException $e) {

				    Logger::getInstance()->error($e->getMessage());
				}

			// ==
			$lgpsclient_args = array_merge(
				array(
					'clientPaymentRequest'=> $cipher_message
					)
				);
			return $lgpsclient_args;
		}


		/**
		 * Check if this gateway is enabled and available in the user's country
		 *
		 * @access public
		 * @return bool
		 */
		function is_valid_for_use() {
			if ( ! in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_lgpsclient_supported_currencies', array( 'AUD', 'BRL', 'CAD', 'MXN', 'NZD', 'HKD', 'SGD', 'USD', 'EUR', 'JPY', 'TRY', 'NOK', 'CZK', 'DKK', 'HUF', 'ILS', 'MYR', 'PHP', 'PLN', 'SEK', 'CHF', 'TWD', 'THB', 'GBP', 'RMB', 'RUB' ) ) ) ) {
				return false;
			}

			return true;
		}
		
		public function admin_options() {

			?>
			<h3><?php _e( 'LGPS Client standard', 'woocommerce' ); ?></h3>
			<p><?php _e( 'LGPS Client standard works by sending the user to LGPS Client to enter their payment information.', 'woocommerce' ); ?></p>

			<?php if ( $this->is_valid_for_use() ) : ?>

				<table class="form-table">
				<?php
					// Generate the HTML For the settings form.
					$this->generate_settings_html();
				?>
				</table><!--/.form-table-->

			<?php else : ?>
				<div class="inline error"><p><strong><?php _e( 'Gateway Disabled', 'woocommerce' ); ?></strong>: <?php _e( 'LGPS Client does not support your store currency.', 'woocommerce' ); ?></p></div>
			<?php
				endif;
		}

    /**
     *  There are no payment fields for lgps, but we want to show the description if set.
     **/
    function payment_fields(){
        if($this -> description) echo wpautop(wptexturize($this -> description));
    }
    /**
     * Receipt Page
     **/
		function receipt_page( $order ) {
			echo '<p>' . __( 'Thank you - your order is now pending payment. You should be automatically redirected to LGPS Client to make payment.', 'woocommerce' ) . '</p>';

			echo $this->generate_lgpsclient_form( $order );
		}
    /**
     * Generate lgps button link
     **/
		function generate_lgpsclient_form( $order_id ) {

			$order = new WC_Order( $order_id );

			if ( 'yes' == $this->testmode ) {
				$lgpsclient_adr = $this->testurl . '?test_ipn=1&';
			} else {
				$lgpsclient_adr = $this->liveurl . '?';
			}

			$lgpsclient_args = $this->get_lgpsclient_args( $order );

			$lgpsclient_args_array = array();

			foreach ( $lgpsclient_args as $key => $value ) {
				$lgpsclient_args_array[] = '<input type="hidden" name="'.esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" />';
			}

			wc_enqueue_js( '
				$.blockUI({
						message: "' . esc_js( __( 'Thank you for your order. We are now redirecting you to LGPS Client to make payment.', 'woocommerce' ) ) . '",
						baseZ: 99999,
						overlayCSS:
						{
							background: "#fff",
							opacity: 0.6
						},
						css: {
							padding:        "20px",
							zindex:         "9999999",
							textAlign:      "center",
							color:          "#555",
							border:         "3px solid #aaa",
							backgroundColor:"#fff",
							cursor:         "wait",
							lineHeight:		"24px",
						}
					});
				jQuery("#submit_lgpsclient_payment_form").click();
			' );

			return '<form action="' . esc_url( $lgpsclient_adr ) . '" method="post" id="lgpsclient_payment_form" target="_top">
					' . implode( '',$lgpsclient_args_array ) . '
					<!-- Button Fallback -->
					<div class="payment_buttons">
						<input type="submit" class="button alt" id="submit_lgpsclient_payment_form" value="' . __( 'Pay via LGPS Client', 'woocommerce' ) . '" /> <a class="button cancel" href="' . esc_url( $order->get_cancel_order_url() ) . '">' . __( 'Cancel order &amp; restore cart', 'woocommerce' ) . '</a>
					</div>
					<script type="text/javascript">
						jQuery(".payment_buttons").hide();
					</script>
				</form>';

		}
    /**
     * Process the payment and return the result
     **/
		function process_payment( $order_id ) {

			$order = new WC_Order( $order_id );

			if ( ! $this->form_submission_method ) {

				$lgpsclient_args = $this->get_lgpsclient_args( $order );

				$lgpsclient_args = http_build_query( $lgpsclient_args, '', '&' );

				if ( 'yes' == $this->testmode ) {
					$lgpsclient_adr = $this->testurl . '?test_ipn=1&';
				} else {
					$lgpsclient_adr = $this->liveurl;
				}
				return array(
					'result' 	=> 'success',
					'redirect'	=> $lgpsclient_adr . $lgpsclient_args
				);

			} else {

				return array(
					'result' 	=> 'success',
					'redirect'	=> $order->get_checkout_payment_url( true )
				);

			}

		}

		/**
		 * Check lgpsclient IPN validity
		 **/
		function check_ipn_request_is_valid( $ipn_response ) {

			// Get url
			if ( 'yes' == $this->testmode ) {
				$lgpsclient_adr = $this->testurl;
			} else {
				$lgpsclient_adr = $this->liveurl;
			}

			if ( 'yes' == $this->debug ) {
				$this->log->add( 'lgpsclient', 'Checking IPN response is valid via ' . $lgpsclient_adr . '...' );
			}

			// Get received values from post data
			$validate_ipn = array( 'cmd' => '_notify-validate' );
			$validate_ipn += stripslashes_deep( $ipn_response );

			// Send back post vars to lgpsclient
			$params = array(
				'body' 			=> $validate_ipn,
				'sslverify' 	=> false,
				'timeout' 		=> 60,
				'httpversion'   => '1.1',
				'compress'      => false,
				'decompress'    => false,
				'user-agent'	=> 'WooCommerce/' . WC()->version
			);

			if ( 'yes' == $this->debug ) {
				$this->log->add( 'lgpsclient', 'IPN Request: ' . print_r( $params, true ) );
			}

			// Post back to get a response
			$response = wp_remote_post( $lgpsclient_adr, $params );

			if ( 'yes' == $this->debug ) {
				$this->log->add( 'lgpsclient', 'IPN Response: ' . print_r( $response, true ) );
			}

			// check to see if the request was valid
			if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && ( strcmp( $response['body'], "VERIFIED" ) == 0 ) ) {
				if ( 'yes' == $this->debug ) {
					$this->log->add( 'lgpsclient', 'Received valid response from lgpsclient' );
				}

				return true;
			}

			if ( 'yes' == $this->debug ) {
				$this->log->add( 'lgpsclient', 'Received invalid response from lgpsclient' );
				if ( is_wp_error( $response ) ) {
					$this->log->add( 'lgpsclient', 'Error response: ' . $response->get_error_message() );
				}
			}

			return false;
		}

		/**
		 * Check for lgpsclient IPN Response
		 *
		 * @access public
		 * @return void
		 */
		function check_ipn_response() {

			@ob_clean();

			$ipn_response = ! empty( $_POST ) ? $_POST : false;

			if ( $ipn_response && $this->check_ipn_request_is_valid( $ipn_response ) ) {

				header( 'HTTP/1.1 200 OK' );

				do_action( "valid-lgpsclient-standard-ipn-request", $ipn_response );

			} else {

				wp_die( "lgpsclient IPN Request Failure", "lgpsclient IPN", array( 'response' => 200 ) );

			}

		}

		/**
		 * Successful Payment!
		 *
		 * @access public
		 * @param array $posted
		 * @return void
		 */
		function successful_request( $posted ) {
			$order->update_status( 'on-hold', sprintf( __( 'Validation error: lgpsclient currencies do not match (code %s).', 'woocommerce' ), $posted['mc_currency'] ) );
			$posted = stripslashes_deep( $posted );

			// Custom holds post ID
			if ( ! empty( $posted['invoice'] ) && ! empty( $posted['custom'] ) ) {

				$order = $this->get_lgpsclient_order( $posted['custom'], $posted['invoice'] );

				if ( 'yes' == $this->debug ) {
					$this->log->add( 'lgpsclient', 'Found order #' . $order->id );
				}

				// Lowercase returned variables
				$posted['payment_status'] 	= strtolower( $posted['payment_status'] );
				$posted['txn_type'] 		= strtolower( $posted['txn_type'] );

				// Sandbox fix
				if ( 1 == $posted['test_ipn'] && 'pending' == $posted['payment_status'] ) {
					$posted['payment_status'] = 'completed';
				}

				if ( 'yes' == $this->debug ) {
					$this->log->add( 'lgpsclient', 'Payment status: ' . $posted['payment_status'] );
				}

				// We are here so lets check status and do actions
				switch ( $posted['payment_status'] ) {
					case 'completed' :
					case 'pending' :

						// Check order not already completed
						if ( $order->status == 'completed' ) {
							if ( 'yes' == $this->debug ) {
								$this->log->add( 'lgpsclient', 'Aborting, Order #' . $order->id . ' is already complete.' );
							}
							exit;
						}

						// Check valid txn_type
						$accepted_types = array( 'cart', 'instant', 'express_checkout', 'web_accept', 'masspay', 'send_money' );

						if ( ! in_array( $posted['txn_type'], $accepted_types ) ) {
							if ( 'yes' == $this->debug ) {
								$this->log->add( 'lgpsclient', 'Aborting, Invalid type:' . $posted['txn_type'] );
							}
							exit;
						}

						// Validate currency
						if ( $order->get_order_currency() != $posted['mc_currency'] ) {
							if ( 'yes' == $this->debug ) {
								$this->log->add( 'lgpsclient', 'Payment error: Currencies do not match (sent "' . $order->get_order_currency() . '" | returned "' . $posted['mc_currency'] . '")' );
							}

							// Put this order on-hold for manual checking
							$order->update_status( 'on-hold', sprintf( __( 'Validation error: lgpsclient currencies do not match (code %s).', 'woocommerce' ), $posted['mc_currency'] ) );
							exit;
						}

						// Validate amount
						if ( $order->get_total() != $posted['mc_gross'] ) {
							if ( 'yes' == $this->debug ) {
								$this->log->add( 'lgpsclient', 'Payment error: Amounts do not match (gross ' . $posted['mc_gross'] . ')' );
							}

							// Put this order on-hold for manual checking
							$order->update_status( 'on-hold', sprintf( __( 'Validation error: lgpsclient amounts do not match (gross %s).', 'woocommerce' ), $posted['mc_gross'] ) );
							exit;
						}

						// Validate Email Address
						if ( strcasecmp( trim( $posted['receiver_email'] ), trim( $this->receiver_email ) ) != 0 ) {
							if ( 'yes' == $this->debug ) {
								$this->log->add( 'lgpsclient', "IPN Response is for another one: {$posted['receiver_email']} our email is {$this->receiver_email}" );
							}

							// Put this order on-hold for manual checking
							$order->update_status( 'on-hold', sprintf( __( 'Validation error: lgpsclient IPN response from a different email address (%s).', 'woocommerce' ), $posted['receiver_email'] ) );

							exit;
						}

						 // Store PP Details
						if ( ! empty( $posted['payer_email'] ) ) {
							update_post_meta( $order->id, 'Payer lgpsclient address', wc_clean( $posted['payer_email'] ) );
						}
						if ( ! empty( $posted['txn_id'] ) ) {
							update_post_meta( $order->id, 'Transaction ID', wc_clean( $posted['txn_id'] ) );
						}
						if ( ! empty( $posted['first_name'] ) ) {
							update_post_meta( $order->id, 'Payer first name', wc_clean( $posted['first_name'] ) );
						}
						if ( ! empty( $posted['last_name'] ) ) {
							update_post_meta( $order->id, 'Payer last name', wc_clean( $posted['last_name'] ) );
						}
						if ( ! empty( $posted['payment_type'] ) ) {
							update_post_meta( $order->id, 'Payment type', wc_clean( $posted['payment_type'] ) );
						}

						if ( $posted['payment_status'] == 'completed' ) {
							$order->add_order_note( __( 'IPN payment completed', 'woocommerce' ) );
							$order->payment_complete();
						} else {
							$order->update_status( 'on-hold', sprintf( __( 'Payment pending: %s', 'woocommerce' ), $posted['pending_reason'] ) );
						}

						if ( 'yes' == $this->debug ) {
							$this->log->add( 'lgpsclient', 'Payment complete.' );
						}

					break;
					case 'denied' :
					case 'expired' :
					case 'failed' :
					case 'voided' :
						// Order failed
						$order->update_status( 'failed', sprintf( __( 'Payment %s via IPN.', 'woocommerce' ), strtolower( $posted['payment_status'] ) ) );
					break;
					case 'refunded' :

						// Only handle full refunds, not partial
						if ( $order->get_total() == ( $posted['mc_gross'] * -1 ) ) {

							// Mark order as refunded
							$order->update_status( 'refunded', sprintf( __( 'Payment %s via IPN.', 'woocommerce' ), strtolower( $posted['payment_status'] ) ) );

							$mailer = WC()->mailer();

							$message = $mailer->wrap_message(
								__( 'Order refunded/reversed', 'woocommerce' ),
								sprintf( __( 'Order %s has been marked as refunded - lgpsclient reason code: %s', 'woocommerce' ), $order->get_order_number(), $posted['reason_code'] )
							);

							$mailer->send( get_option( 'admin_email' ), sprintf( __( 'Payment for order %s refunded/reversed', 'woocommerce' ), $order->get_order_number() ), $message );

						}

					break;
					case 'reversed' :

						// Mark order as refunded
						$order->update_status( 'on-hold', sprintf( __( 'Payment %s via IPN.', 'woocommerce' ), strtolower( $posted['payment_status'] ) ) );

						$mailer = WC()->mailer();

						$message = $mailer->wrap_message(
							__( 'Order reversed', 'woocommerce' ),
							sprintf(__( 'Order %s has been marked on-hold due to a reversal - lgpsclient reason code: %s', 'woocommerce' ), $order->get_order_number(), $posted['reason_code'] )
						);

						$mailer->send( get_option( 'admin_email' ), sprintf( __( 'Payment for order %s reversed', 'woocommerce' ), $order->get_order_number() ), $message );

					break;
					case 'canceled_reversal' :

						$mailer = WC()->mailer();

						$message = $mailer->wrap_message(
							__( 'Reversal Cancelled', 'woocommerce' ),
							sprintf( __( 'Order %s has had a reversal cancelled. Please check the status of payment and update the order status accordingly.', 'woocommerce' ), $order->get_order_number() )
						);

						$mailer->send( get_option( 'admin_email' ), sprintf( __( 'Reversal cancelled for order %s', 'woocommerce' ), $order->get_order_number() ), $message );

					break;
					default :
						// No action
					break;
				}

				exit;
			}

		}

    function showMessage($content){
            return '<div class="box '.$this -> msg['class'].'-box">'.$this -> msg['message'].'</div>'.$content;
    }
     // get all pages
    function get_pages($title = false, $indent = true) {
        $wp_pages = get_pages('sort_column=menu_order');
        $page_list = array();
        if ($title) $page_list[] = $title;
        foreach ($wp_pages as $page) {
            $prefix = '';
            // show indented child pages?
            if ($indent) {
                $has_parent = $page->post_parent;
                while($has_parent) {
                    $prefix .=  ' - ';
                    $next_page = get_page($has_parent);
                    $has_parent = $next_page->post_parent;
                }
            }
            // add to page list array array
            $page_list[$page->ID] = $prefix . $page->post_title;
        }
        return $page_list;
    }
}
   /**
     * Add the Gateway to WooCommerce
     **/
    function woocommerce_add_lgps_client_gateway($methods) {
        $methods[] = 'WC_LGPS_Client';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_lgps_client_gateway' );
}
