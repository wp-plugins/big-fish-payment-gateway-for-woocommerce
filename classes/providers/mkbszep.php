<?php

/**
 * BIG FISH Payment Gateway MKB SZÉP Card provider
 * 
 */
class BigFishPaymentGatewayMKBSZEP extends BigFishPaymentGatewayProvider {

	protected $providerName = 'MKBSZEP';
	
	protected $providerLongName = 'MKB SZÉP Card';

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
		
		$this->BF_PMGW_Settings['encryptPublicKey'] = $this->settings['encryptPublicKey'];

		$this->description = __('Card number', BF_PMGW_PLUGIN).':<br /><input type="text" name="MkbSzepCardNumber" value=""><br />';
		$this->description.= __('CVV', BF_PMGW_PLUGIN).':<br /><input type="text" name="MkbSzepCardCvv" value="" style="width: 60px">';
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
			'MkbSzepCafeteriaId' => array(
				'title' => __('Available pocket', BF_PMGW_PLUGIN),
				'type' => 'select',
				'options' => array(
					'' => __('Please, select a pocket', BF_PMGW_PLUGIN),
					'1111' => __('Accommodation', BF_PMGW_PLUGIN),
					'2222' => __('Hospitality', BF_PMGW_PLUGIN),
					'3333' => __('Leisure', BF_PMGW_PLUGIN),
				),
			),
			'encryptPublicKey' => array(
				'title' => __('Encrypt public key', BF_PMGW_PLUGIN),
				'type' => 'textarea',
				'css' => 'min-height: 200px;',
				'default' => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0tDZrRNNcde2H0Si9Q0J
V7UnAPeWmWrj/RXHSB8vkNOWbC9Df3+mDVNXTN4xPsCh6H/HWIX6fKiWIDy6uerl
wSgbXKdmqFW42bFkzd/6W1kcqJibDmqmQbCQjLCVp35GcIhcQHAYcq359CBIS0RR
ZiWxhCWEGhjHqcjX9qKK/ApOmkc0wr82mUVcr1g3zkW5LFu2vKSalrLlaV064FlF
kVE+dwhO5Q78b/IcaVavIghSjkd76+l5SS5tcHk4/J5/KKayjcYpzxwSLzU+OOQ7
3SHB3aShQtzIWhdMbx2YuyB38hORUK/lAqqHp0lCgWA4x1y1WRB87lSva/uQDEWu
rwIDAQAB
-----END PUBLIC KEY-----',
			),			
		);
	}	
}
