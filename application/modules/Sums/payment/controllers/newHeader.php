<?php
class NewHeader extends MX_Controller {
	function init() {
		$this->load->helper(array('form', 'url'));
		$this->load->library('ajax');
    }

   function index($pageId = 1, $productId = 0) {
		$validateuser = $this->checkUserValidation();
		$headerComponents = array(
							'displayname'=>$validateuser[0]['displayname'],
							'taburl'=>site_url("payment/payment"),
							'callShiksha'=>1,
							);
		$this->load->view('payment/paymentHeader.php', $headerComponents);

    }
   }
   ?>
