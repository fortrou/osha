<?php
	/**
	 * - class Billing 
	 * - project Online-shkola 
	 * - dev by @fortrou
	 **/

	require_once("classDatabase.php");
	require_once("classLiqpay.php");

	class Billing{
		private $merchant   = '';
		private $signature  = '';
		private $liqpay_obj;
		private $params_arr = array();
		private $hash_data  = '';
		private $liqpay_signature = '';


		function __construct($merchant = '', $signature = '') {
			if($merchant == '' || $signature == '') throw new Exception("Не установлены ключи аутентификации, обратитесь к администрации");
			$this->merchant   = $merchant;
			$this->signature  = $signature;
			$this->liqpay_obj = new Liqpay($merchant,$signature);
		}
		public function complete_params($params = array()) {
			if(count($params) == 0) return false;
			foreach ($params as $key => $value) {
				$params_arr[$key] = $value;
			}
		}
		public function get_form($params = array()) {
			$this->hash_data = $this->liqpay_obj->cnb_params($params);
			$this->hash_data = base64_encode( json_encode($this->hash_data));
			$this->liqpay_signature = $this->liqpay_obj->cnb_signature($params);
			//var_dump($this->liqpay_signature);
			$answer = sprintf('<input type="hidden" name="data" value="%s">
							   <input type="hidden" name="signature" value="%s">'
							   , $this->hash_data, $this->liqpay_signature );
			return $answer;
		}
	}
?>