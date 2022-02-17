<?php
class AddListing extends MX_Controller {
	function init() {
		$this->load->helper(array('form', 'url'));
		$this->load->library('ajax');
        $this->load->library('login_client');
        $this->load->library('paymentClient');
    }

    function index() {
        $this->init();
        $validity = $this->checkUserValidation();
        if($validity == "" ) {
           header("location:".base_url()."/payment/Payment"); 
        }else {
            $userid = $validity[0]['userid'];
            $paymentClientObj = new PaymentClient();
            //error_log_shiksha($userid);
            $productDataList =  $paymentClientObj->getProductForUser(1,$userid);
            if(isset($productDataList[0])) {
                 header("location:".base_url()."/listing/Listing/addCourse");
            }else {
                header("location:".base_url()."/payment/Payment");
            }
        }
            
            
    }
}
?>
