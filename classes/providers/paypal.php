<?php

/**
 * BIG FISH Payment Gateway PayPal provider
 * 
 */
class BigFishPaymentGatewayPayPal extends BigFishPaymentGatewayProvider {

	protected $providerName = 'PayPal';
	
	protected $providerLongName = 'PayPal';
	
	public $supports = array('products', 'refunds');
	
	protected $data = array(
		'autoCommit' => true,
	);
	
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
		);
	}
}
