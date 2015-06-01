<?php

/**
 * BIG FISH Payment Gateway PayU Wire provider
 * 
 */
class BigFishPaymentGatewayPayUWire extends BigFishPaymentGatewayProvider {

	protected $providerName = 'PayUWire';
	
	protected $providerLongName = 'PayU Wire';
	
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
