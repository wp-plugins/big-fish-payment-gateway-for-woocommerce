<?php

/**
 * BIG FISH Payment Gateway K&H SZÉP Card provider
 * 
 */
class BigFishPaymentGatewayKHBSZEP extends BigFishPaymentGatewayProvider {

	protected $providerName = 'KHBSZEP';
	
	protected $providerLongName = 'K&H SZÉP Card';

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
			'KhbCardPocketId' => array(
				'title' => __('Available pocket', BF_PMGW_PLUGIN),
				'type' => 'select',
				'options' => array(
					'' => __('Please, select a pocket', BF_PMGW_PLUGIN),
					'1' => __('Accommodation', BF_PMGW_PLUGIN),
					'2' => __('Hospitality', BF_PMGW_PLUGIN),
					'3' => __('Leisure', BF_PMGW_PLUGIN),
				),
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
		if (isset($this->settings['KhbCardPocketId']) && !empty($this->settings['KhbCardPocketId'])) {
			$this->data['extra']['KhbCardPocketId'] = $this->settings['KhbCardPocketId'];
		}
		
		return parent::process_payment($order_id);
	}	
}
