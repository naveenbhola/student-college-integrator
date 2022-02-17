<?php 
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: amitj $:  Author of last commit
$Date: 2008-09-09 06:12:09 $:  Date of last commit

Events.php controller for events.

$Id: paymentFirst.php,v 1.7 2008-09-09 06:12:09 amitj Exp $: 

*/

class paymentFirst extends MX_Controller {
	function init() {
		$this->load->helper(array('form', 'url'));
		$this->load->library('ajax');
        $validity = $this->checkUserValidation();
        global $logged;
        header("location:".base_url() ."/payment/paymentFree");
        if($validity == "false" ) {
            $logged = "No";
            header("location:".base_url() ."");
        }else {
            $logged = "Yes";
            //error_log_shiksha(print_r($validity,true));
            $userid = $validity[0]['userid'];
            $this->load->library('paymentClient');
            $paymentClientObj = new PaymentClient();
            //error_log_shiksha($userid);
            $productDataList =  $paymentClientObj->getProductForUser(1,$userid);
            if(isset($productDataList[0])) {
                header("location:".base_url() ."/listing/Listing/addCourse");
            }
        }

    }

    function index($appID = 1, $productId = 0) {
        global $logged;
	    $this->init();
		$this->load->library('paymentClient');
        $validateuser = $this->checkUserValidation();
        if($validateuser == "false") {
            header("location:".base_url() ."");
        }
		$this->load->view('payment/listingIntermediate',array("validateuser"=>$validateuser,"displayname"=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), "callShiksha"=>1));		  
    }
    function printData($dataList){
        $i = 0;
        if(is_array($dataList)){
//        error_log_shiksha("in if");
            foreach($dataList as $data) {
                echo $data['ProductId']." ".$data['ProductName'].$data['Type'].$data['Price'];
                print_r($data['Property']);
//                error_log_shiksha($data['ProductId']." ".$data['ProductName'].$data['Type'].$data['Price']);
            }
        }

    }
}
