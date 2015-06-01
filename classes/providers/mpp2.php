<?php

/**
 * BIG FISH Payment Gateway MPP2 provider
 * 
 */
class BigFishPaymentGatewayMPP2 extends BigFishPaymentGatewayProvider {

	protected $providerName = 'MPP2';
	
	protected $providerLongName = 'MasterCard® Mobile';
	
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
			)
		);
	}
}
