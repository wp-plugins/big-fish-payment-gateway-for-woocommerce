<?php

/**
 * BIG FISH Payment Gateway QPAY provider
 * 
 */
class BigFishPaymentGatewayQPAY extends BigFishPaymentGatewayProvider {

	protected $providerName = 'QPAY';
	
	protected $providerLongName = 'Wirecard QPAY';
	
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
			'autoCommit' => array(
				'title' => __('Authorization', BF_PMGW_PLUGIN),
				'type' => 'select',
				'options' => array(
					'1' => __('Immediate', BF_PMGW_PLUGIN),
					'0' => __('Later', BF_PMGW_PLUGIN),
				),
				'default' => '1',
			),
		);
	}

	/**
	 * Process payment
	 * 
	 * @param integer $order_id
	 * @access public
	 * @return array|boolean
	 */			
	public function process_payment($order_id) {
		$this->data['autoCommit'] = !isset($this->settings['autoCommit']) ? true : (boolean)$this->get_option('autoCommit');
		
		return parent::process_payment($order_id);
	}
}
