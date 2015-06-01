<?php

/**
 * BIG FISH Payment Gateway PayU Cash provider
 * 
 */
class BigFishPaymentGatewayPayUCash extends BigFishPaymentGatewayProvider {

	protected $providerName = 'PayUCash';
	
	protected $providerLongName = 'PayU Cash';
	
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
