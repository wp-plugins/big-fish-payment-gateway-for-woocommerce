<?php

/**
 * Plugin Name: BIG FISH Payment Gateway for WooCommerce
 * Plugin URI: https://www.paymentgateway.hu/
 * Description: A BIG FISH Payment Gateway egy olyan szolgáltatás, amely segítségével Ön többféle elektronikus fizetési megoldást kínálhat webáruházában.
 * Version: 1.0.0
 * Author: BIG FISH Internet-technológiai Kft.
 * Author URI: http://bigfish.hu
 */

/**
 * Include autoloader to BIG FISH Payment Gateway
 * 
 */
require(realpath(dirname(__FILE__)) . '/api/BigFish/PaymentGateway/Autoload.php');

add_action('plugins_loaded', 'BigFishPaymentGatewayWoo');

function BigFishPaymentGatewayWoo() {
	if (!class_exists('WC_Payment_Gateway')) {
		return;
	}
	
	/**
	 * Register BIG FISH Payment Gateway autoloader to use namespace
	 * 
	 */
	BigFish\PaymentGateway\Autoload::register();
	
	/**
	 * BIG FISH Payment Gateway WooCommerce ID
	 * 
	 */
	define('BF_PMGW_ID' , 'BigFishPaymentGateway');
	
	/**
	 * BIG FISH Payment Gateway title
	 * 
	 */
	define('BF_PMGW_TITLE' , 'BIG FISH Payment Gateway');
	
	/**
	 * BIG FISH Payment Gateway WooCommerce plugin
	 * 
	 */
	define('BF_PMGW_PLUGIN' , 'big_fish_payment_gateway');

	/**
	 * BIG FISH Payment Gateway for WooCommerce
	 * 
	 */
	class BigFishPaymentGateway extends WC_Payment_Gateway {
		
		/**
		 * Contructor
		 * 
		 * @access public
		 * @return void
		 */		
		public function __construct() {
			/**
			 *  Load BIG FISH Payment Gateway translations
			 * 
			 */
			load_plugin_textdomain(BF_PMGW_PLUGIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');
			
			$this->id = BF_PMGW_ID;
			$this->title = BF_PMGW_TITLE;
			$this->method_title = $this->title;

			/**
			 * Set BIG FISH Payment Gateway plugin admin form fields
			 * 
			 */
			$this->set_form();

			$this->init_settings();
			
			if (is_admin()) {
				/**
				 * Check installed option
				 * 
				 */
				if (!get_option(BF_PMGW_ID.'_Installed', false)) {
					/**
					 * Set BIG FISH Payment Gateway tables to database
					 * 
					 */
					$this->install();
				}
			}

			/**
			 * Add provider's classes
			 * 
			 */
			add_filter('woocommerce_payment_gateways', array($this, 'add_providers'));
			
			/**
			 * Process transaction result
			 * 
			 */
			add_action('woocommerce_api_'.strtolower($this->id), array($this, 'result'));
			
			add_action('woocommerce_update_options_payment_gateways_'.$this->id, array($this, 'process_admin_options'));
			
			/**
			 * Close transaction functions
			 * 
			 */
			add_action( 'woocommerce_order_status_completed', array($this, 'close_transaction_approved'));
			
			add_action( 'woocommerce_order_status_cancelled', array($this, 'close_transaction_declined'));
		}

		/**
		 * set form
		 *
		 * @access private
		 * @return void
		 */
		private function set_form() {
			$this->form_fields = array(
				'configuration' => array(
					'title' => __('Settings', BF_PMGW_PLUGIN),
					'type' => 'title'
				),
				'storeName' => array(
					'title' => __('Store name', BF_PMGW_PLUGIN),
					'type' => 'text',
					'default' => 'sdk_test',
					'desc_tip' => false
				),
				'apiKey' => array(
					'title' => __('API key', BF_PMGW_PLUGIN),
					'type' => 'text',
					'default' => '86af3-80e4f-f8228-9498f-910ad',
					'desc_tip' => false
				),
				'testMode' => array(
					'title' => __('Test mode', BF_PMGW_PLUGIN),
					'type' => 'select',
					'options' => array(
						'1' => __('Yes', BF_PMGW_PLUGIN),
						'0' => __('No', BF_PMGW_PLUGIN),
					),
					'default' => '1',
				),
				'providers' => array(
					'title' => __('Available providers', BF_PMGW_PLUGIN),
					'type' => 'multiselect',
					'options' => array(
						'CIB' => __('CIB Bank', BF_PMGW_PLUGIN),
						'KHB' => __('K&H Bank', BF_PMGW_PLUGIN),
						'OTP' => __('OTP Bank', BF_PMGW_PLUGIN),
						'OTPSZEP' => __('OTP SZÉP Card', BF_PMGW_PLUGIN),
						'PayPal' => __('PayPal', BF_PMGW_PLUGIN),
						'SMS' => __('SMS', BF_PMGW_PLUGIN),
						'OTP2' => __('OTP Bank (two participants)', BF_PMGW_PLUGIN),
						'Escalion' => __('Escalion', BF_PMGW_PLUGIN),
						'MPP2' => __('MasterCard® Mobile', BF_PMGW_PLUGIN),
						'PayU' => __('PayU', BF_PMGW_PLUGIN),
						'UniCredit' => __('UniCredit Bank', BF_PMGW_PLUGIN),
						'QPAY' => __('Wirecard QPAY', BF_PMGW_PLUGIN),
						'FHB' => __('FHB Bank', BF_PMGW_PLUGIN),
						'Barion' => __('Barion', BF_PMGW_PLUGIN),
						'OTPay' => __('OTPay', BF_PMGW_PLUGIN),
						'PayUWire' => __('PayU Wire', BF_PMGW_PLUGIN),
						'PayUCash' => __('PayU Cash', BF_PMGW_PLUGIN),
						'Sofort' => __('Sofort Banking', BF_PMGW_PLUGIN),
						'Borgun' => __('Borgun', BF_PMGW_PLUGIN),
						'OTPayMP' => __('OTPay MasterPass', BF_PMGW_PLUGIN),
						'KHBSZEP' => __('K&H SZÉP Card', BF_PMGW_PLUGIN),
						'OTPMultipont' => __('OTP Multipont', BF_PMGW_PLUGIN),
						'MKBSZEP' => __('MKB SZÉP Card', BF_PMGW_PLUGIN),
					),
				),				
			);
		}
		
		/**
		 * install database tables
		 *
		 * @access private
		 * @return void
		 */		
		private function install() {
			global $wpdb;

			$wpdb->query("CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_transactions` (
				`bigfishpaymentgateway_id` int(11) NOT NULL AUTO_INCREMENT,
				`order_id` int(11) NOT NULL,
				`transaction_id` varchar(255) DEFAULT NULL,
				`provider_name` varchar(255) NOT NULL,
				`auto_commit` tinyint(1) NOT NULL DEFAULT '1',
				`response_message` text,
				PRIMARY KEY (`bigfishpaymentgateway_id`),
				UNIQUE KEY `transaction_id` (`transaction_id`)
			)");
			
			$wpdb->query("CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_logs` (
				`bigfishpaymentgateway_log_id` int(11) NOT NULL AUTO_INCREMENT,
				`bigfishpaymentgateway_id` int(11) NOT NULL,
				`status` varchar(255) NOT NULL,
				`message` text NOT NULL,
				`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`bigfishpaymentgateway_log_id`)
			)");			

			/**
			 * Set Installed option
			 * 
			 */
			update_option(BF_PMGW_ID.'_Installed', true);
		}		
		
		/**
		 * add providers
		 *
		 * @param array $methods
		 * @access public
		 * @return array
		 */
		public function add_providers($methods) {
			$methods[] = BF_PMGW_ID;
			
			/**
			 * Add providers which were selected in BIG FISH Payment Gateway checkout admin
			 * 
			 */
			if (is_array($this->settings['providers']) && !empty($this->settings['providers'])) {
				foreach ($this->settings['providers'] as $providers) {
					$methods[] = $this->id.$providers;
				}
			}
			
			return $methods;
		}
		
		/**
		 * set provider settings
		 *
		 * @param string $providerName
		 * @access public
		 * @return void
		 */
		public function set_provider_settings($providerName) {
			$this->BF_PMGW_Settings = get_option($this->plugin_id.BF_PMGW_ID.'_settings', null);
			$providerSettings = get_option($this->plugin_id.BF_PMGW_ID.$providerName.'_settings', null);
			
			$this->BF_PMGW_Settings = array_merge($this->BF_PMGW_Settings, $providerSettings);
		}		
		
		/**
		 * process result
		 *
		 * @access public
		 * @return void
		 */		
		public function result() {
			global $wpdb, $wp_version, $woocommerce;

			try {
				if (!array_key_exists("TransactionId", $_GET)) {
					throw new Exception(__('No transaction ID!', BF_PMGW_PLUGIN));
				}
				
				/**
				 * Get transaction's data
				 * 
				 */
				$transaction = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_transactions WHERE transaction_id='".$_GET["TransactionId"]."'");
				
				$order = new WC_Order($transaction->order_id);

				/**
				 * Set BIG FISH Payment Gateway config
				 * 
				 */				
				$this->set_provider_settings($transaction->provider_name);
				
				\BigFish\PaymentGateway::setConfig(new BigFish\PaymentGateway\Config($this->BF_PMGW_Settings));				

				/**
				 * Get result from BIG FISH Payment Gateway server
				 * 
				 */
				$response = \BigFish\PaymentGateway::result(new BigFish\PaymentGateway\Request\Result($_GET["TransactionId"]));

				if ($response->ResultCode == "SUCCESSFUL") {
					$responseMessage = "<br/><b>".$response->ResultMessage."</b>";
					$responseMessage.= "<br/><b>".__('Transaction ID', BF_PMGW_PLUGIN).":</b> ".$response->ProviderTransactionId;
					
					if (!empty($response->Anum)) {
						$responseMessage.= "<br/><b>".__('Authorization number', BF_PMGW_PLUGIN).":</b> ".$response->Anum;
					}

					/**
					 * Set transaction result in email
					 * 
					 */
					$this->email = $responseMessage;
					
					add_action('woocommerce_email_after_order_table', array($this, 'email_content'));
					
					$order->add_order_note($responseMessage);
					
					/**
					 * Set order status
					 * 
					 */
					$order->payment_complete();
					
					/**
					 * Log result response
					 * 
					 */
					$wpdb->insert($wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_logs", array('bigfishpaymentgateway_id' => $transaction->bigfishpaymentgateway_id, 'status' => $order->get_status(), 'message' => 'Result: '.print_r($response, true)));

					/**
					 * Save response message
					 * 
					 */
					$wpdb->update($wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_transactions", array('response_message' => $responseMessage), array('bigfishpaymentgateway_id' => $transaction->bigfishpaymentgateway_id));
					
					$woocommerce->cart->empty_cart();

					$location = add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('thanks'))."&order-received=".$order->id);

					wp_safe_redirect($location);
				} else {
					throw new Exception($response->ResultMessage);
				}
			} catch (Exception $e) {
				if (isset($transaction)) {
					/**
					 * Log result response
					 * 
					 */
					$wpdb->insert($wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_logs", array('bigfishpaymentgateway_id' => $transaction->bigfishpaymentgateway_id, 'status' => $order->get_status(), 'message' => 'Result: '.$e->getMessage()));
				}
				
				/**
				 * Show error on site
				 * 
				 */
				wc_add_notice($e->getMessage(), 'error');

				$order->add_order_note($e->getMessage());
				
				/**
				 * Go to cancel url
				 * 
				 */
				$location = $order->get_cancel_order_url();
				wp_safe_redirect($location);
			}
			
			exit;
		}
		
		/**
		 * close transaction approved
		 *
		 * @param integer $order_id
		 * @access public
		 * @return void
		 */
		public function close_transaction_approved($order_id) {
			$this->close_transaction($order_id, true);
		}
		
		/**
		 * close transaction declined
		 *
		 * @param integer $order_id
		 * @access public
		 * @return void
		 */		
		public function close_transaction_declined($order_id) {
			$this->close_transaction($order_id, false);
		}		
		
		/**
		 * close transaction
		 *
		 * @param integer $order_id
		 * @param boolean $approved
		 * @access public
		 * @return void
		 */		
		public function close_transaction($order_id, $approved) {
			global $wpdb;

			$order = new WC_Order($order_id);

			/**
			 * Get transaction's data
			 * 
			 */
			$transaction = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_transactions WHERE order_id=".(int)$order_id." ORDER BY bigfishpaymentgateway_id DESC LIMIT 1");

			if (!empty($transaction->transaction_id) && !(int)$transaction->auto_commit) {
				
				/**
				 * Set BIG FISH Payment Gateway config
				 * 
				 */
				$this->set_provider_settings($transaction->provider_name);
				
				\BigFish\PaymentGateway::setConfig(new BigFish\PaymentGateway\Config($this->BF_PMGW_Settings));
				
				/**
				 * Close transaction request from BIG FISH Payment Gateway server
				 * 
				 */
				$response = \BigFish\PaymentGateway::close(new \BigFish\PaymentGateway\Request\Close($transaction->transaction_id, $approved));

				/**
				 * Log close response
				 * 
				 */
				$wpdb->insert($wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_logs", array('bigfishpaymentgateway_id' => $transaction->bigfishpaymentgateway_id, 'status' => $order->get_status(), 'message' => 'Close: '.print_r($response, true)));
				
				if ($response->ResultCode == "SUCCESSFUL") {
					$order->add_order_note(__('Transaction closed', BF_PMGW_PLUGIN).': '.__('SUCCESSFUL', BF_PMGW_PLUGIN).' ('.__(($approved ? 'Approved' : 'Declined'), BF_PMGW_PLUGIN).')');
				} else {
					$order->add_order_note(__('Transaction closed', BF_PMGW_PLUGIN).': '.__('FAILED', BF_PMGW_PLUGIN).'<br />'.$response->ResultMessage);
				}
			}
		}		
		
		/**
		 * Add content to the WC emails
		 *
		 * @access public
		 * @return void
		 */
		public function email_content() {
			echo wpautop(wptexturize($this->email)).PHP_EOL;
		}
	}
	
	/**
	 * BIG FISH Payment Gateway Provider
	 * 
	 */	
	class BigFishPaymentGatewayProvider extends WC_Payment_Gateway {
		
		/**
		 * Contructor
		 * 
		 * @access public
		 * @return void
		 */		
		public function __construct() {
			$this->id = BF_PMGW_ID.(isset($this->className) ? $this->className : $this->providerName);
			$this->method_title = BF_PMGW_TITLE.' '.$this->providerLongName;
			
			add_action('woocommerce_update_options_payment_gateways_'.$this->id, array($this, 'process_admin_options'));
			
			/**
			 * Set BIG FISH Payment Gateway provider admin form fields
			 * 
			 */			
			$this->set_form();

			$this->init_settings();
			
			$this->received_page();
			
			$this->title = $this->get_option('displayname');
			
			$this->BF_PMGW_Settings = get_option($this->plugin_id.BF_PMGW_ID.'_settings', null);
		}
		
		/**
		 * Process payment
		 * 
		 * @param integer $order_id
		 * @access public
		 * @return array|boolean
		 */			
		public function process_payment($order_id) {
			global $wpdb, $woocommerce;

			$order = new WC_Order($order_id);

			try {
				/**
				 * Set BIG FISH Payment Gateway config
				 * 
				 */
				\BigFish\PaymentGateway::setConfig(new BigFish\PaymentGateway\Config($this->BF_PMGW_Settings));

				/**
				 * Init BIG FISH Payment Gateway
				 * 
				 */
				$request = new \BigFish\PaymentGateway\Request\Init();
				
				$request->setProviderName($this->providerName)
						->setResponseUrl(site_url().'?wc-api='.strtolower(BF_PMGW_ID))
						->setAmount($order->order_total)
						->setCurrency(get_woocommerce_currency())
						->setOrderId($order->id)
						->setUserId(($order->user_id == null) ? 'Guest' : $order->user_id)
						->setLanguage(strtoupper(substr(get_bloginfo('language'), 0, 2)))
						->setAutoCommit($this->data['autoCommit']);

				/**
				 * Set OTP2 data
				 * 
				 */
				if ($this->providerName == 'OTP2') {
					$request->setOtpCardNumber($_POST["OtpCardNumber"])
							->setOtpExpiration($_POST["OtpExpiration"])
							->setOtpCvc($_POST["OtpCvc"]);
				}
				
				/**
				 * Set OTP SZÉP Card data
				 * 
				 */
				if (isset($this->settings['OtpCardPocketId']) && !empty($this->settings['OtpCardPocketId'])) {
					$request->setOtpCardPocketId($this->settings['OtpCardPocketId']);
				}
				
				/**
				 * Set MKB SZÉP Card data
				 * 
				 */
				if ($this->providerName == 'MKBSZEP') {
					$request->setMkbSzepCafeteriaId($this->settings['MkbSzepCafeteriaId'])
							->setMkbSzepCardNumber($_POST['MkbSzepCardNumber'])
							->setMkbSzepCvv($_POST['MkbSzepCardCvv']);
				}
				
				/**
				 * Set Extra
				 * 
				 */
				if (isset($this->data['extra']) && is_array($this->data['extra'])) {
					$request->setExtra($this->data['extra']);
				}				

				$response = \BigFish\PaymentGateway::init($request);

				if ((int)$wpdb->insert($wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_transactions", array('order_id' => $order->id, 'provider_name' => (isset($this->className) ? $this->className : $this->providerName), 'auto_commit' => (int)$this->data['autoCommit']))) {
				
					$bigfishpaymentgateway_id = $wpdb->insert_id;
				
					$wpdb->insert($wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_logs", array('bigfishpaymentgateway_id' => $bigfishpaymentgateway_id, 'status' => $order->get_status(), 'message' => 'Init: '.print_r($response, true)));
				} else {
					throw new Exception(__('Database insert error!', BF_PMGW_PLUGIN));
				}
				
				if ($response->ResultCode == "SUCCESSFUL" && $response->TransactionId) {
					$wpdb->update($wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_transactions", array('transaction_id' => $response->TransactionId), array('bigfishpaymentgateway_id' => $bigfishpaymentgateway_id));
					
					/**
					 * Start BIG FISH Payment Gateway
					 * 
					 */
					$url = \BigFish\PaymentGateway::getStartUrl(new BigFish\PaymentGateway\Request\Start($response->TransactionId));
					
					return array(
						'result' => 'success',
						'redirect' => $url
					);
					
				} else {
					throw new Exception($response->ResultMessage);
				}
			} catch(Exception $e) {
				/**
				 * Show error on site
				 * 
				 */
				wc_add_notice($e->getMessage(), 'error');

				$order->add_order_note($e->getMessage());

				return false;
			}
		}
		
		/**
		 * Set received page content
		 * 
		 * @access public
		 * @return void
		 */
		public function received_page() {
			global $wp, $wpdb;
			
			if (is_order_received_page()) {
				/**
				 * Set $order_id by order-received
				 */
				$order_id = apply_filters('woocommerce_thankyou_order_id', absint($wp->query_vars['order-received']));
				
				/**
				 * Get response message
				 * 
				 */
				$this->transaction = $wpdb->get_row("SELECT response_message FROM ".$wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_transactions WHERE order_id=".(int)$order_id." ORDER BY bigfishpaymentgateway_id DESC LIMIT 1");

				add_action('woocommerce_thankyou_'.$this->id, array($this, 'thankyou_content'));
			}
		}
		
		/**
		 * Output for the order received page
		 * 
		 * @access public
		 * @return void
		 */
		public function thankyou_content() {
			echo wpautop(wptexturize(wp_kses_post($this->transaction->response_message)));
		}		

		/**
		 * Process refund
		 * 
		 * @param integer $order_id
		 * @param float $amount
		 * @param string $reason
		 * @access public
		 * @return boolean
		 */		
		public function process_refund($order_id, $amount = null, $reason = '') {
			global $wpdb;

			$transaction = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_transactions WHERE order_id=".(int)$order_id." ORDER BY bigfishpaymentgateway_id DESC LIMIT 1");

			if (!empty($transaction->transaction_id)) {
				$order = new WC_Order($order_id);
				
				/**
				 * Set BIG FISH Payment Gateway config
				 * 
				 */
				\BigFish\PaymentGateway::setConfig(new BigFish\PaymentGateway\Config($this->BF_PMGW_Settings));				

				/**
				 * Refund transaction request from BIG FISH Payment Gateway server
				 * 
				 */				
				$response = \BigFish\PaymentGateway::refund(new \BigFish\PaymentGateway\Request\Refund($transaction->transaction_id, $amount));
				
				$wpdb->insert($wpdb->prefix."woocommerce_".strtolower(BF_PMGW_ID)."_logs", array('bigfishpaymentgateway_id' => $transaction->bigfishpaymentgateway_id, 'status' => $order->get_status(), 'message' => 'Refund: '.print_r($response, true)));
				
				if ($response->ResultCode == "SUCCESSFUL") {
					$order->add_order_note(__('Refund', BF_PMGW_PLUGIN).': '.__('SUCCESSFUL', BF_PMGW_PLUGIN).': '.$amount.' '.get_woocommerce_currency().'<br />'.$reason);
					return true;
				} else {
					$order->add_order_note(__('Refund', BF_PMGW_PLUGIN).': '.__('FAILED', BF_PMGW_PLUGIN).': '.$amount.' '.get_woocommerce_currency().'<br />'.$response->ResultMessage);
					return new WP_Error('invalid_post', $response->ResultMessage);
				}
			}

			return false;
		}		
	}
	
	/**
	 * Include providers classes
	 * 
	 */
	$files = scandir(realpath(dirname(__FILE__))."/classes/providers/");
	
	if (is_array($files) && !empty($files)) {
		foreach ($files as $file) {
			if (!in_array($file, array('.', '..'))) {
				require(realpath(dirname(__FILE__)) . "/classes/providers/".$file);
			}
		}
	}

	new BigFishPaymentGateway();
}
