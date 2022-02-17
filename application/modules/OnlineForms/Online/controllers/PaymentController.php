<?php 

/*

   Copyright 2007 Info Edge India Ltd

   $Rev::               $:  Revision of last commit
   $Author: pragya gupta $:  Author of last commit
   $Date: 2012/07/11 09:29:49 $:  Date of last commit


 */
class PaymentController extends MX_Controller {

	var $validUserIds = array('1121','2386848', '5907664');
	var $userStatus = '';
	function init($library=array('Online_form_client','Alerts_client'),$helper=array('url','image','shikshautility','validate','utility_helper')){

		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}	//error_reporting(E_ALL);ini_set("display_errors",'on');
		$this->load->library('Online_form_mail_client');
		$this->load->model('onlineparentmodel');
		$this->load->model('onlinepaymentmodel');
		$this->load->model('paymodel');
		$this->load->model('onlinemodel');

		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();

	}


	//this function searches the results depending on the fields which are set

	function searchpaymentdetails($start=0,$count=20){
		$this->init();
		$appId = 12;

		if($this->checkIfValidUser()){

			$orderId = (isset($_REQUEST['orderid']))?$_REQUEST['orderid']:'';
			$startDate = (isset($_REQUEST['startDate']))?$_REQUEST['startDate']:'dd/mm/yyyy';
			$endDate =(isset($_REQUEST['endDate']))?$_REQUEST['endDate']:'dd/mm/yyyy';
			$instituteName =(isset($_REQUEST['instituteName']))? $_REQUEST['instituteName']:'';
			$mode = (isset($_REQUEST['mode']))?$_REQUEST['mode']:'';
			$gateway = (isset($_REQUEST['gateway']))?$_REQUEST['gateway']:'';
			$status = (isset($_REQUEST['status']))?$_REQUEST['status']:'';
			$candidateName = (isset($_REQUEST['candidateName']))?$_REQUEST['candidateName']:'';
			$orderby =(isset($_REQUEST['orderby']))? $_REQUEST['orderby']:'';

			$data=array('orderid'=>$orderId,'startDate'=>$startDate,'endDate'=>$endDate,'instituteName'=>$instituteName,'mode'=>$mode,'gateway'=>$gateway,'status'=>$status,'candidateName'=>$candidateName,'orderby'=>$orderby);       
			$data['validateuser'] = $this->userStatus;			
			$data['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

			$moduleName = trim($this->input->post('moduleName'));
			$filter = trim($this->input->post('Filter'));
			$parameterObj = array('form' => array('offset'=>-1,'totalCount'=>0,'countOffset'=>20));
			$parameterObj['form']['offset'] = 0;
			$parameterObj['form']['totalCount'] = $totalFormNumber;
			$data['parameterObj'] = json_encode($parameterObj);
			$data['totalForm'] = $totalFormNumber;
			$data['filterSel'] = $filter;
			$data['moduleName'] = $moduleName;
			$data['start'] = $start;
			$data['count'] = $count;
			if($orderId!='' || ($startDate!='' && $startDate!='dd/mm/yyyy') || ($endDate!='' && $endDate!='dd/mm/yyyy') || $instituteName!='' || $status!='' || $candidateName!='' ){
			$resultSet = $this->paymodel->getPaymentInformation($orderId,$startDate,$endDate,$instituteName,$mode,$status,$candidateName,$orderby,$start,$count);
			}
			else{
				$data['message'] = "Please enter your Search above.";
			}
			$data['resultSet'] = $resultSet; 

			echo $this->load->view('Online/PaymentTool/paymentrecords', $data);
		}

	}





	//This function will check if the user is allowed to view this page. If not, he will be redirected to Login page
	//Else, the normal operation will continue


	function checkIfValidUser(){

		$this->init();
		$appId = 12;
		$data['validateuser'] = $this->userStatus;			
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		if(in_array($userId,$this->validUserIds)){
			return true;
		}
		else{
			//Redirect the user to login page
			$this->showLoginPage();
		}
		return false;
	}



	//this function shows the login page

	function showLoginPage(){
		$this->init();
		$appId = 12;
		$data['validateuser'] = $this->userStatus;			
		$data['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		echo $this->load->view('Online/PaymentTool/showLoginForm', $data);
	}




	//this function updates the payment records in the database 

	function updatePaymentInfo(){
		$this->init();
		$transactionjson = json_decode($this->input->post('transactionData'),true);
		
		echo "Status of the following orders have been modified \n\n";
		$changed_ids = array();
		foreach ($transactionjson as $array) { 
			$orderId=$array['orderId'];
			$status= $array['status'];
			$financeStatus = $status;
			if($status == 'Refunded' || $status == 'Chargeback'){
				$status = 'Cancelled';
			}
		
			$status1= $status;
			$paymentId=$array['paymentId'];
			$date=$array['date'];
			$receiptDate=$array['receiptDate'];
			$log="updated by finance team";
			$currentdate= date('Y-m-d H:i:s');

			if($status=='Success'){
				$statusOfUserForm='Paid';
			}
			else{
				$statusOfUserForm='Completed';
			}
                        //payment log table status will be not be updated if the status for any payment id is changed to cancelled
			
			//Convert receipt date to MySQL Timestamp
			$parts = explode('/', $receiptDate);
			$receiptDate = $parts[1]."/".$parts[0]."/".$parts[2];
			$receiptDate1 = date('Y-m-d',strtotime($receiptDate));			
			$this->onlinepaymentmodel->updatePaymentLog($orderId,$paymentId,$date,$log,$status,$receiptDate1,$financeStatus);
			$result=$this->onlinepaymentmodel->addPaymentActivityLog($orderId,$paymentId,$currentdate,$status);
			$this->emailClient = new Online_form_mail_client;
			$this->client = new Online_form_client;
			
			//make an array with key as receipt date as this is the only unique element for all the orderids whose statuses have been changed.
			$changed_ids[$date] = $financeStatus.'.'.$orderId.'.'.$receiptDate;
			$flag='false';
			$set=0;
			$statusforid=$this->paymodel->getPaymentLog($paymentId);
			foreach($statusforid as $array){
				$x=$array['status'];
				if ($x=='Success'){
					$status='Success';
					$statusOfUserForm='Paid';
					$set=1;
					$flag='false';
					
				}
				if($set!=1){
					$status=$array['status'];
					$flag='true';
				}

			}
	
			$this->onlinepaymentmodel->updatePaymentInformation($paymentId,$status);	
			$this->paymodel->updateFormStatus($orderId,$paymentId,$log,$currentdate,$status,$statusOfUserForm,$date);
			
			
						
			$msgId = array();
			
			$getOnlineFormDetails=$this->paymodel->getOnlineFormDetails($paymentId);
			$instituteId = 0;
			foreach($getOnlineFormDetails as $getOnlineFormDetail){
				
				$userId=$getOnlineFormDetail['userId'];
				$onlineFormId=$getOnlineFormDetail['onlineFormId'];
				$instituteId=$getOnlineFormDetail['instituteId'];
				$this->load->library('Online_form_client');
				$onlineClient = new Online_form_client();
				$onlineClient->setInstituteSpecId($onlineFormId,$userId,$instituteId);
			}
			
			if(($status == 'Success' && $flag!= 'false') || ($flag == 'false' && $status1 == 'Success')){
				$msgId[] = 20;
				$msgId[] = 28;
				//sends mail
				
				$this->emailClient->run($userId,$onlineFormId,'form_successfully_submitted');
				$this->emailClient->run($userId,$onlineFormId,'form_successfully_submitted_institute');

	                 	if($instituteId=='33857'){
		                        $this->emailClient->run($userId,$onlineFormId,'liba_payment_advice');
                		}
				
			}
			
			if(count($msgId)>0 && ($status == 'Success')){
				foreach($msgId as $messageId) {
				$this->client->addNotification($onlineFormId,$userId,$instituteId,$messageId);
				}
			}
		}
		$str = '';
		foreach ($changed_ids as $id => $val) {
			$val=explode('.',$val);
			if ($str != '') {
				$str .= "\n\n ";
			}
			$str .= 'OrderId '.$val[1].' with transaction date '.$id.' changed to status '.$val[0].' and receipt date '.$val[2].' successfully.';
			
		}
		echo $str ;
	}

		



	// this function downloads the payment records in an excel file.
	function downloadPaymentLogInformation($params){
		
		$file_name = 'Payment-Report.xls';
		header("Content-type: text/xls");
		header("Content-Disposition: attachment; filename=$file_name");

		$temp = explode(":", $params);

		//this case is used when the user clicks on the download button directly without submitting the form

		//if(count($temp)==2){
		//	error_log('ggggggg1');
		//	$count=$temp[1];
		//	$params=":::::::::";
		//	$temp = explode(":", $params);
		//}

		$data=array('orderid'=>$temp[0],'date1'=>$temp[1],'date2'=>$temp[2],'instname'=>$temp[3],'mode'=>$temp[4],'gateway'=>$temp[5],'status'=>$temp[6],'cname'=>$temp[7],'orderby'=>$temp[8]);

		if($data['date1']){
			$startdate_array = explode("-", $data['date1']);
			$data['date1'] = $startdate_array[2]."/".$startdate_array[1]."/".$startdate_array[0];
		}
		if($data['date2']){
			$enddate_array = explode("-", $data['date2']);
			$data['date2'] = $enddate_array[2]."/".$enddate_array[1]."/".$enddate_array[0];
		}

		$this->init();
		$start = 0 ;
		$count = isset($count)?$count:$temp[10];
		$resultSet = $this->paymodel->getDownloadPaymentInformation($data['orderid'],$data['date1'],$data['date2'],$data['instname'],$data['mode'],$data['status'],$data['cname'],$data['orderby'],$start,$count,'false');

		$outstream = fopen("php://output", "w");
		
		$columnListArray = array();
		$columnListArray[]='FormId';
		$columnListArray[]='Receipt Date';
		$columnListArray[]='Transaction id';
                $columnListArray[]='Transaction Date';
		$columnListArray[]='Client/Student';
		$columnListArray[]='University';
		$columnListArray[]='Course Form';
		$columnListArray[]='Sale Amount';
		$columnListArray[]='Transaction date';
		$columnListArray[]='Sale Executive';
		$columnListArray[]='Invoice no.';
		$columnListArray[]='Sale Type';
		$columnListArray[]='Cheque No';
		$columnListArray[]='Cheque City';
		$columnListArray[]='Cheque Bank';
		$columnListArray[]='Cheque Date';
		$columnListArray[]='Cheque Amount';
		$columnListArray[]='TDS_Amount';
		$columnListArray[]='Payment_Mode';
		$columnListArray[]='Payment Gateway';
		$columnListArray[]='Deposited_By';
		$columnListArray[]='Deposited_Branch';
		$columnListArray[]='Payment Status';
		$columnListArray[]='Sale Branch';
		$columnListArray[]='Currency Code';
		$ColumnList = $columnListArray;
		fputcsv($outstream,$ColumnList,'|',' ');
		
		foreach ($resultSet as $info){
			$csv = array($info['onlineFormId'],$info['receiptDate'],$info['orderId'],$info['date'],$info['firstName']." ".$info['middleName']." ".$info['lastName'],$info['institute_name'],$info['courseTitle'],$info['amount'],$info['date'],"NA",$info['paymentId'],$info['mode'],"NA","NA","NA","NA",$info['amount'],"NA",$info['mode'],"CCAvenue","NA","NA",$info['financeStatus'],"NA","INR");
			fputcsv($outstream,$csv,'|',' ');
		}
		
		fclose($outstream);
	}	

	function addEntriesInFinanceModule(){
		$this->init();
		$this->paymodel->addEntriesInFinanceModule();
	}

}

