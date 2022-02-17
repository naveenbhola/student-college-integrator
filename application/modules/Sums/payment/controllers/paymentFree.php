<?php 
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: amitj $:  Author of last commit
$Date: 2008-09-09 06:12:09 $:  Date of last commit

Events.php controller for events.

$Id: paymentFree.php,v 1.9 2008-09-09 06:12:09 amitj Exp $: 

*/

class paymentFree extends MX_Controller {
	function init() {
		$this->load->helper(array('form', 'url'));
		$this->load->library('ajax');
        $validity = $this->checkUserValidation();
        global $logged;
        if($validity == "false" ) {
            $logged = "No";
        }else {
            $logged = "Yes";
            //error_log_shiksha(print_r($validity,true));
            $userid = $validity[0]['userid'];
            $this->load->library('paymentClient');
            $paymentClientObj = new PaymentClient();
            //error_log_shiksha($userid);
            $productDataList =  $paymentClientObj->getProductForUser(1,$userid);
            //FIXME Temp change
            $transactHistory = $paymentClientObj->getTransactionHistory(1,$userid);
            if(sizeof($transactHistory) == 0){
                $productDataList =  $paymentClientObj->addTransaction(1,11,$userid,"PAID");
            }
            /*
            if(isset($productDataList[0])) {
                header("location:".base_url() ."/enterprise/Enterprise");
            }*/
        }

    }

    function index($appID = 1, $productId = 0) {
        global $logged;
	    $this->init();
		$this->load->library('paymentClient');
		$catList = array();
		$paymentClientObj = new PaymentClient();
        $productDataList =  $paymentClientObj->getProductData($appID);
//        error_log_shiksha(print_r($productDataList,true));

        $validateuser = $this->checkUserValidation();
        $addListType = $this->uri->segment(4);
        switch($addListType){
            case "course":
            header('location:/enterprise/Enterprise/addCourseCMS');
            exit();
            case "scholarship":
            header('location:/enterprise/Enterprise/addScholarshipCMS');
            exit();
            case "notification":
            header('location:/enterprise/Enterprise/addAdmissionCMS');
            exit();
            default:
            break;
        }
		$this->load->view('payment/productDetails',array("productDataList"=>$productDataList,"logged"=>$logged,"validateuser"=>$validateuser));		  
//		$this->printData($productDataList);
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
