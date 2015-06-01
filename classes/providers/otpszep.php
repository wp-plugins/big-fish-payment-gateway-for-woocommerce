<?php

/**
 * BIG FISH Payment Gateway OTP SZÉP Card provider
 * 
 */
class BigFishPaymentGatewayOTPSZEP extends BigFishPaymentGatewayProvider {

	protected $className = 'OTPSZEP';
	
	protected $providerName = 'OTP';
	
	protected $providerLongName = 'OTP SZÉP Card';
	
	protected $data = array(
		'autoCommit' => true,
	);
	
	/**
	 * Contructor
	 * 
	 * @access public
	 * @return void
	 */		
	public function __construct() {
		parent::__construct();
		
		$this->BF_PMGW_Settings['storeName'] = $this->settings['storeName'];
		$this->BF_PMGW_Settings['apiKey'] = $this->settings['apiKey'];		
	}
	
	/**
	 * set form
	 *
	 * @access protected
	 * @return void
	 */
	protected function set_form() {
		$this->form_fields = array(
			'enabled' => array(
				'title' => __('Active', BF_PMGW_PLUGIN),
				'type' => 'checkbox',
			),
			'displayname' => array(
				'title' => __('Display name', BF_PMGW_PLUGIN),
				'type' => 'text',
				'default' => $this->providerLongName,
			),
			'storeName' => array(
					'title' => __('SZÉP Store name', BF_PMGW_PLUGIN),
					'type' => 'text',
					'default' => 'sdk_test',
					'desc_tip' => false
			),
			'apiKey' => array(
				'title' => __('SZÉP API key', BF_PMGW_PLUGIN),
				'type' => 'text',
				'default' => '86af3-80e4f-f8228-9498f-910ad',
				'desc_tip' => false
			),
			'OtpCardPocketId' => array(
				'title' => __('Available pocket', BF_PMGW_PLUGIN),
				'type' => 'select',
				'options' => array(
					'' => __('Please, select a pocket', BF_PMGW_PLUGIN),
					'09' => __('Accommodation', BF_PMGW_PLUGIN),
					'07' => __('Hospitality', BF_PMGW_PLUGIN),
					'08' => __('Leisure', BF_PMGW_PLUGIN),
				),
			),			
		);
	}	
}
