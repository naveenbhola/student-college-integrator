<?php 
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: amitj $:  Author of last commit
$Date: 2008-09-09 06:12:09 $:  Date of last commit

Events.php controller for events.

$Id: payment.php,v 1.17 2008-09-09 06:12:09 amitj Exp $: 

*/

class payment extends MX_Controller {
	function init() {
		$this->load->helper(array('form', 'url'));
		$this->load->library('ajax');
        $validity = $this->checkUserValidation();
        global $logged;
        if($validity == "false" ) {
            $logged = "No";
            //header("location:".base_url() ."");
        }else {
            $logged = "Yes";
            //error_log_shiksha(print_r($validity,true));
            $userid = $validity[0]['userid'];
            $this->load->library('paymentClient');
            $paymentClientObj = new PaymentClient();
            //error_log_shiksha($userid);
//            $productDataList =  $paymentClientObj->getProductForUser(1,$userid);
//            if(isset($productDataList[0])) {
//                header("location:".base_url() ."/listing/Listing/addCourse");
//            }
        }

    }

    function cmsUserValidation() {
        $validity = $this->checkUserValidation();
        error_log_shiksha("VAlidity: ".$validity);
        global $logged;
        global $userid;
        global $usergroup;
        $thisUrl = $_SERVER['REQUEST_URI'];
        if(($validity == "false" )||($validity == "")) {
            $logged = "No";
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }else {
            $logged = "Yes";
            $userid = $validity[0]['userid'];
            $usergroup = $validity[0]['usergroup'];
            if ($usergroup=="user" || $usergroup=="fbuser") {
				header("location:/enterprise/Enterprise/migrateUser");
				exit;
            }
            if( !(($usergroup == "cms")||($usergroup == "enterprise")) ){
                header("location:/enterprise/Enterprise/unauthorizedEnt");
                exit();
            }
	 }
	 $this->load->library('enterprise_client');
	 $entObj = new Enterprise_client();
	 $headerTabs = $entObj->getHeaderTabs(1,$validity[0]['usergroup']);


            $this->load->library('paymentClient');
            $paymentClientObj = new paymentClient();
            error_log_shiksha($userid);
            $productDataList = $paymentClientObj->getProductForUser(1,$userid);
            $transactHistory = $paymentClientObj->getTransactionHistory(1,$userid);
            /*echo "<pre>";
              print_r($productDataList);
              echo "</pre>";*/
            $productDetails = array();

            $keyArray = array();
            foreach($productDataList as $products){
                $keyArray[$products['productId']]['productId']=$products['productId'];
                $keyArray[$products['productId']]['productName']=$products['productName'];
                $keyArray[$products['productId']]['property']=$products['property'];

                if(!isset($keyArray[$products['productId']])){
                    $keyArray[$products['productId']]['remaining']=$products['remaining'];
                }else{
                    $keyArray[$products['productId']]['remaining'] +=$products['remaining'];
                }
            }

            $myProdArr = array();
            $myProdArr['Gold']['productName'] = "Gold";
            $myProdArr['Gold']['remaining'] = 0;
            $myProdArr['Silver']['productName'] = "Silver";
            $myProdArr['Silver']['remaining'] = 0;
            $myProdArr['Bronze']['productName'] = "Bronze";
            $myProdArr['Bronze']['remaining'] = 0;
            $goldUnlimitedFlag = false;

            foreach($keyArray as $products){
                if($products['productId'] <= 8){
                    if(!$goldUnlimitFlag){
                        $myProdArr['Gold']['remaining'] += $products['remaining'];
                    }
                }else if($products['productId'] == 9){
                    $myProdArr['Gold']['remaining'] = "Unlimited";
                    $goldUnlimitedFlag = True;
                }else if($products['productId'] == 10){
                    $myProdArr['Silver']['remaining'] += $products['remaining'];
                }else if($products['productId'] == 11){
                    $myProdArr['Bronze']['remaining'] += $products['remaining'];
                }
            }

            $myProductDetails  = $myProdArr;
            //print_r($productDetails);
            error_log_shiksha("My Product Details: ".print_r($myProductDetails,TRUE));

        $returnArr['userid']=$userid;
        $returnArr['usergroup']=$usergroup;
        $returnArr['logged'] = $logged;
        $returnArr['thisUrl'] = $thisUrl;
        $returnArr['validity'] = $validity;
	$returnArr['headerTabs'] = $headerTabs;
        $returnArr['myProducts'] = $myProductDetails;
        $returnArr['transactHistory']=$transactHistory;

        return $returnArr;
    }

    function index($appID = 1, $productId = 0) {
        global $logged;
        $this->init();
        $cmsUserInfo = $this->cmsUserValidation();
        
        $this->load->library('paymentClient');
        $catList = array();
        $paymentClientObj = new PaymentClient();
        $productDataList =  $paymentClientObj->getProductData($appID);
        $validity = $this->checkUserValidation();
       /* $cmsUserInfo;
        if(!(($validity == "false" )||($validity == ""))) {
            error_log_shiksha("HERE");
            $cmsUserInfo = $this->cmsUserValidation();
        }*/
        $validateuser = $cmsUserInfo['validity'];
        //        error_log_shiksha("GOT the response");
        //        error_log_shiksha(print_r($productDataList,true));
        $displayData = array();
        $displayData['productDataList'] = $productDataList;
        $displayData['logged'] = $logged;
        $displayData['validateuser'] = $validateuser;
        $displayData['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $displayData['myProducts'] = $cmsUserInfo['myProducts'];
        $displayData['prodId'] = 10;
        $displayData['usergroup'] = $cmsUserInfo['usergroup'];
        $displayData['transactHistory'] = $cmsUserInfo['transactHistory'];

        $this->load->view('payment/productDetails',$displayData);		  
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
