<?php
/*
 
 Copyright 2007 Info Edge India Ltd
 
 $Rev::            $:  Revision of last commit
 $Author: shivam $:  Author of last commit
 $Date: 2009-03-06 12:03:48 $:  Date of last commit
 
 Subscription.php : Controller file: Makes call to SUMS server using XML RPC calls.
 
 $Id: Subscription.php,v 1.53 2009-03-06 12:03:48 shivam Exp $:
 
 */


include('Sums_Common.php');
/**
 * Controller Class for Sums Subscriptions
 */
class Subscription extends Sums_Common
{
	
	function init()
		{
		$this->load->helper(array('form', 'url','date','image'));
		$this->load->library(array('miscelleneous','message_board_client','blog_client','event_cal_client','ajax','category_list_client','listing_client','register_client','enterprise_client','Subscription_client','Sums_product_client'));
		}
	
	//Test Controller
	function myProducts()
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		$request['userId'] = 2;
		$prodObj = new Sums_Product_client();
		$response =  $prodObj->getProductsForUser(1,$request);
		error_log_shiksha(print_r($response,true));
		echo print_r($response,true);
		}
	function paymentMigration(){
		$this->load->library('Subscription_client');
		$data['sumsUserInfo'] = $this->sumsUserValidation();	
		$objSubs = new Subscription_client();
		$res = $objSubs->paymentMigration(1);
		echo "The result of the script is :: ".$res;
	} 	
	function paymentDetails($transactionId,$prodId=16)
		{
		$this->init();
		$objSubs = new Subscription_client();
		$response = $objSubs->getPaymentInfo(1,$transactionId);
		$response['TotalPrice'] = $response['Transaction']['TotalTransactionPrice'];
		//echo "<pre>";print_r($response);echo "</pre>";
		$response['prodId'] = $prodId;
		$this->load->view('sums/paymentDetails',$response);
		}
	
	function setKeyword()
		{
		$validity = $this->checkUserValidation();
		$usergroup = $validity[0]['usergroup'];
		$request['keywords'] = $this->input->post('keywords',true);
		$request['listingId'] = $this->input->post('listingTypeId',true);
		$request['type'] = $this->input->post('listingType',true);
		$request['startDate'] = $this->input->post('subsStartDate',true);
		$request['endDate'] = $this->input->post('subsEndDate',true);
		$request['subscriptionId'] =$this->input->post('subscriptionId',true);
		$request['sponserType'] = $this->input->post('sponsorType',true);
		$request['clientUserId'] = $this->input->post('clientUserId',true);
		$request['sumsUserId'] = $this->input->post('cmsUserId',true);
		$request['location'] = $this->input->post('location',true);
		$appId='1';
		$this->init();
		$keywordArray=explode(",",$request['keywords']);
		$ListingClientObj= new LISTING_CLIENT();
		$objSubs = new Subscription_client();
		if ($request['sponserType']=="featured") {
			if($request['type'] !='institute')
			{
				$resultArray=array('error'=> "Featured Panel can only be set for Institute!");
				echo json_encode($resultArray);
				return;
			}
			$response = $ListingClientObj->getFeaturedPanelLogo($appId,array($typeId));
			if (!(isset($response[$typeId]) && $response[$typeId]!="")) {
				$resultArray=array('error'=> "Featured Panel Logo is not uploaded for this College/Institute.");
				echo json_encode($resultArray);
				return;
			}
		}     
		$subDetails=$objSubs->getSubscriptionDetails($appId,$request['subscriptionId']);
		$request['baseProdId'] = $subDetails[0]['BaseProductId'];
		$remainingQuant = $subDetails[0]['BaseProdRemainingQuantity'];
		if(count($keywordArray)==0 || $request['keywords']=='')
		{
			$resultArray=array('error'=>'Please Fill in the Keywords');		
			print_r($resultArray);
			return;
		}
		if($remainingQuant<count($keywordArray))
		{
			$resultArray=array('error'=>'You have exceeded your subscription limit. You have only'.$remainingQuant.' subcriptions left in SubscriptionId'.$request['subscriptionId'].'!!');		
		}
		else
		{
			$errorArray=array();
			$result=$ListingClientObj->checkValidKeywordForListing($appId,$request['listingId'],$request['type'],$keywordArray);
			foreach($result['result'] as $value)
			{
				if($value['isRelevant']==0)
				{
					$errorArray[]=$value['keyword'];
				}
			}
			if((count($errorArray)>0)&&($usergroup=="enterprise"))
			{
				$resultArray=array('error'=>implode(',',$errorArray)." Irrelevant Keywords");
			}
			else
			{
				$tmpArray=array();
				foreach($keywordArray as $keyword)
				{
					$result=$ListingClientObj->addSponsorListing($appID,$keyword,$request['listingId'],$request['type'],$request['startDate'],$request['endDate'],$request['subscriptionId'],$request['sponserType'],$request['location']);
					if($result['result']!=-1)
					{
						$tmpArray[]=$keyword;
						$consumeResult=$objSubs->consumeSubscription($appID,$request['subscriptionId'],$remainingQuant,$request['clientUserId'],$request['sumsUserId'],$request['baseProdId'],$result['result'],$request['sponserType'],$request['startDate'],$request['endDate']);
						$listingExtensionResponse = $ListingClientObj->extendExpiryDate($appId,$request['listingId'],$request['type'],$request['endDate']);
						//error_log_shiksha(print_r($listingExtensionResponse),true);
						$remainingQuant--;
					}
					else
					{
						$errorArray[]=$result['error'];
					}
				}
				if(count($errorArray)>0)
				{
					$resultArray['error']=implode(',',$errorArray). " Keywords are already set !";
				}
				if(count($tmpArray)>0)
				{
					$resultArray['result']=implode(',',$tmpArray)." Keywords are set !!";
				}
				//$resultArray['extResp']=$listingExtensionResponse; 
			}
		}
		echo json_encode($resultArray);
		return;
		}
	
	
	function setMainCollegeLink()
		{
		$this->init();
		$request['listingTypeId'] = $this->input->post('listingTypeId',true);
		$request['type'] = $this->input->post('listingType',true);
		$request['startDate'] = $this->input->post('subsStartDate',true);
		$request['endDate'] = $this->input->post('subsEndDate',true);
		$request['subscriptionId'] =$this->input->post('subscriptionId',true);
		$request['clientUserId'] = $this->input->post('clientUserId',true);
		$request['sumsUserId'] = $this->input->post('cmsUserId',true);
		
		$request['TestPrepCat'] =$this->input->post('testPrepId',true);
		$request['CountryId'] =$this->input->post('countryId',true);
		$request['CityId'] =$this->input->post('cityListId',true);
		$request['CategoryId'] =$this->input->post('categoryId',true);
		$request['SubcategoryId'] =$this->input->post('subcategoryId',true);
		$request['stateId'] =$this->input->post('stateId',true);
		
		if($request['type'] !='institute' && $request['type'] !='university')
		{
			$resultArray=array('error'=> "Main Institute Link can not be set for a non-institute/non-university listing");
			echo json_encode($resultArray);
			return;
			
		}
		$this->load->model('SumsModel');
		$result=$this->SumsModel->getMainCollegeLinkSubscriptionProperties($request['subscriptionId'],$request['type'],$request['listingTypeId']);
		if(count($result['error'])>0)
		{
			$resultArray=array('error'=> implode(",",$result['error']));
			echo json_encode($resultArray);
			return;
		}
		if($request['TestPrepCat']!=0) 
		{
			$present=0;
			for($i=0;$i<count($result['result']['TestCategory']);$i++)
			{
				if($request['TestPrepCat']==$result['result']['TestCategory'][$i]['categoryId'])
				{
					$present=1;
				}
			}
			if($present==0)
			{
				$resultArray=array('error'=> "Please come with Valid Test Cat for this listing");
				echo json_encode($resultArray);
				return;
			}
		}
		if($request['CountryId']!=2)
		{
			$present=0;
			for($i=0;$i<count($result['result']['CountryList']);$i++)
			{
				if($request['CountryId']==$result['result']['CountryList'][$i]['countryId'])
				{
					$present=1;
				}
			}
			if($present==0)
			{
				$resultArray=array('error'=> "Please come with Valid Country for this listing");
				echo json_encode($resultArray);
				return;
			}
		}
		
		if($request['CityId']!=0)
		{
			error_log("Shivam".$request['CityId']."  array ".print_r($result['result']['cityList'],true));
			$present=0;
			for($i=0;$i<count($result['result']['cityList']);$i++)
			{
				if($request['CityId']==$result['result']['cityList'][$i]['cityId'])
				{
					$present=1;
				}
			}
			if($present==0)
			{
				$resultArray=array('error'=> "Please come with Valid City for this listing");
				echo json_encode($resultArray);
				return;
			}
		}
		
		
		if($request['CategoryId']!=0)
		{
			$present=0;
			for($i=0;$i<count($result['result']['categoryList']);$i++)
			{
				if($request['CategoryId']==$result['result']['categoryList'][$i]['categoryId'])
				{
					$present=1;
				}
			}
			if($present==0)
			{
				$resultArray=array('error'=> "Please come with Valid Category for this listing");
				echo json_encode($resultArray);
				return;
			}
		}
		
		if($request['SubcategoryId']!=0)
		{
			$present=0;
			for($i=0;$i<count($result['result']['subcategoryList']);$i++)
			{
				if($request['SubcategoryId']==$result['result']['subcategoryList'][$i]['SubcategoryId'])
				{
					$present=1;
				}
			}
			if($present==0)
			{
				$resultArray=array('error'=> "Please come with Valid Sub Category for this listing");
				echo json_encode($resultArray);
				return;
			}
		}
		
		if($request['stateId']!=0)
		{
			$present=0;
			for($i=0;$i<count($result['result']['stateList']);$i++)
			{
				if($request['stateId']==$result['result']['stateList'][$i]['StateId'])
				{
					$present=1;
				}
			}
			if($present==0)
			{
				$resultArray=array('error'=> "Please come with Valid State for this listing");
				echo json_encode($resultArray);
				return;
			}
		}
		
		$objSubs = new Subscription_client();
		$EntClient = new Enterprise_client();
		$appId='1';
		$subDetails=$objSubs->getSubscriptionDetails($appId,$request['subscriptionId']);
		$request['baseProdId'] = $subDetails[0]['BaseProductId'];
		$remainingQuant = $subDetails[0]['BaseProdRemainingQuantity'];
		if($remainingQuant==0)
		{
			$result=array('error'=>'You have exceeded your subscription limit. You have only'.$remainingQuant.' subcriptions left in SubscriptionId'.$request['subscriptionId'].'!!');		
		}
		else
		{
			$result=$EntClient->addMainCollegeLink(1,$request['TestPrepCat'],$request['CountryId'],$request['CityId'],$request['CategoryId'],$request['listingTypeId'],$request['type'],$request['startDate'],$request['endDate'],$request['subscriptionId'],$request['SubcategoryId'],$request['stateId']);
			if($request['type'] =='university')
			{
				// add this university's courses to abroadIndexLog
				$abroadPostingLib = $this->load->library('listingPosting/AbroadPostingLib');
				$abroadPostingLib->addUnivCoursesToAbroadIndexLog($arr['listingid']);
			}
			if($result['result']!=-1)
			{ 
				$consumeResult=$objSubs->consumeSubscription($appID,$request['subscriptionId'],$remainingQuant,$request['clientUserId'],$request['sumsUserId'],$request['baseProdId'],$result['result'],'MainCollegeLink',$request['startDate'],$request['endDate']);
				$ListingClientObj= new LISTING_CLIENT();
				$listingExtensionResponse = $ListingClientObj->extendExpiryDate($appId,$request['listingTypeId'],$request['type'],$request['endDate']);
				//$result['extResp'] = $listingExtensionResponse;
				$result['result']='Success';
			}
			else
			{
				$result['result']='Failure';
			}
		}
		echo json_encode($result);
		return;
		}
	
	
	
	function subscriptionsForTrans($prodId=21)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		if(!(array_key_exists(43,$data['sumsUserInfo']['sumsuseracl']) || array_key_exists(44,$data['sumsUserInfo']['sumsuseracl']))){
			header('location:/sums/Sums_Common/permissionDenied/'.$prodId);
			exit();
		}
		$data['editingUserId'] = $data['sumsUserInfo']['userid'];
		$data['transactionId'] = $this->input->post('TransactionId',true);
		$data['prodId'] = $prodId;
		//echo '<pre>';print_r($data);echo '</pre>';
		$this->load->view('sums/editSubscriptions',$data);
		}
	
	function fetchSubsForTrans($prodId=21)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		if(!(array_key_exists(43,$data['sumsUserInfo']['sumsuseracl']) || array_key_exists(44,$data['sumsUserInfo']['sumsuseracl']))){
			header('location:/sums/Sums_Common/permissionDenied/'.$prodId);
			exit();
		}
		$request['editingUserId'] = $data['sumsUserInfo']['userid'];
		$request['transactionId'] = $this->input->post('TransactionId',true);
		$request['flowType'] = $this->input->post('flowType',true);
		error_log(print_r($request,true));
		$objSubs = new Subscription_client();
		$response['result'] =  $objSubs->subscriptionsForTrans($this->appId,$request);
		$transactionInfo = $objSubs->getTransactionInfo($this->appId,$request['transactionId']); 
		$data['transactionInfo'] = $transactionInfo;
		$data['transactionId'] = $request['transactionId'];
		$data['result'] = $response['result'];
		$data['prodId'] = $prodId;
		$data['flowType'] = $request['flowType'];
		//echo '<pre>';print_r($data);echo '</pre>';
		$this->load->view('sums/editSubsAjax',$data);
		}
	
	function disableSubscriptions($prodId=21)
		{
		//echo '<pre>';print_r($_POST);echo '</pre>';
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation(44,$prodId);
		$totalSelectCount = $this->input->post('totalUserCount');
		$request['cancelComments'] = $this->input->post('CancelComments');
		$request['editingUserId'] = $data['sumsUserInfo']['userid'];
		$request['subscriptionId'] = array(array(),'struct');
		for($i=1;$i<=$totalSelectCount;$i++){
			$indexName = 'SubscriptionIds'.$i;
			if(isset($_POST[$indexName])){
				$subscriptionData = $this->input->post($indexName);
				array_push($request['subscriptionId'][0], $subscriptionData);
			}
		}
		//echo "<pre>";print_r($request);echo "</pre>";
		$this->load->library(array('subscription_client'));
		$objSubs = new Subscription_client();
		$response =  $objSubs->disableSubscriptions($this->appId,$request);
		error_log_shiksha("SS >> ".print_r($response,true)); 
		$data['result'] = $response;
		$data['prodId'] = $prodId;
		$data['type'] = 'Disabl';
		//echo "<pre>";print_r($data);echo "</pre>";
		$this->load->view('sums/disableSubs',$data);
		
		}
	
	function changeSubsDates($prodId=21)
		{
		//echo '<pre>';print_r($_POST);echo '</pre>';
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation(44,$prodId);
		$request['editingUserId'] = $data['sumsUserInfo']['userid'];
		$request['subscriptionId'] = $this->input->post('SubscriptionId',true);
		$request['startDate'] = $this->input->post('startDate',true);
		$request['endDate'] = $this->input->post('endDate',true);
		$request['status'] = $this->input->post('status',true);
		
		$this->load->library(array('subscription_client'));
		$objSubs = new Subscription_client();
		$response =  $objSubs->changeSubsDates($this->appId,$request);
		error_log_shiksha("SS >> ".print_r($response,true)); 
		echo json_encode($response);
		}
	
	function changeSubsStatus($prodId=21)
		{
		//echo '<pre>';print_r($_POST);echo '</pre>';
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation(44,$prodId);
		$request['editingUserId'] = $data['sumsUserInfo']['userid'];
		$request['subscriptionId'] = $this->input->post('SubscriptionId',true);
		$request['status'] = $this->input->post('status',true);
		
		$this->load->library(array('subscription_client'));
		$objSubs = new Subscription_client();
		$response =  $objSubs->changeSubsStatus($this->appId,$request);
		error_log_shiksha("SS >> ".print_r($response,true)); 
		echo json_encode($response);
		}
	
	function subscriptionsForConsumptions($prodId=21)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		if(!(array_key_exists(43,$data['sumsUserInfo']['sumsuseracl']) || array_key_exists(44,$data['sumsUserInfo']['sumsuseracl']))){
			header('location:/sums/Sums_Common/permissionDenied/'.$prodId);
			exit();
		}
		$data['editingUserId'] = $data['sumsUserInfo']['userid'];
		$data['transactionId'] = $this->input->post('TransactionId',true);
		$data['prodId'] = $prodId;
		$data['flowType'] = 'Consumption';
		//echo '<pre>';print_r($data);echo '</pre>';
		$this->load->view('sums/editSubscriptions',$data);
		}
	
	function consumedForSubs($subscriptionId,$prodId=21)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		if(!(array_key_exists(43,$data['sumsUserInfo']['sumsuseracl']) || array_key_exists(44,$data['sumsUserInfo']['sumsuseracl']))){
			header('location:/sums/Sums_Common/permissionDenied/'.$prodId);
			exit();
		}
		$data['editingUserId'] = $data['sumsUserInfo']['userid'];
		$data['subscriptionId'] = $subscriptionId;
		$data['prodId'] = $prodId;
		//echo '<pre>';print_r($data);echo '</pre>';
		$this->load->view('sums/editConsumptions',$data);
		}
	
	function fetchConsumedIdsForSubs($prodId=21)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		if(!(array_key_exists(43,$data['sumsUserInfo']['sumsuseracl']) || array_key_exists(44,$data['sumsUserInfo']['sumsuseracl']))){
			header('location:/sums/Sums_Common/permissionDenied/'.$prodId);
			exit();
		}
		$request['editingUserId'] = $data['sumsUserInfo']['userid'];
		$request['subscriptionId'] = $this->input->post('SubscriptionId',true);
		error_log(print_r($request,true));
		$objSubs = new Subscription_client();
		$response['result'] =  $objSubs->fetchConsumedIdsForSubs($this->appId,$request);
		$data['subscriptionId'] = $request['subscriptionId'];
		$data['result'] = $response['result'];
		$data['prodId'] = $prodId;
		//echo '<pre>';print_r($data);echo '</pre>';
		$this->load->view('sums/editConsumedAjax',$data);
		}
	
	function changeConsumedIdDates($prodId=21)
		{
		//echo '<pre>';print_r($_POST);echo '</pre>';
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation(44,$prodId);
		$request['editingUserId'] = $data['sumsUserInfo']['userid'];
		$request['consumedId'] = $this->input->post('ConsumedId',true);
		$request['consumedIdType'] = $this->input->post('ConsumedIdType',true);
		$request['startDate'] = $this->input->post('startDate',true);
		$request['endDate'] = $this->input->post('endDate',true);
		$request['status'] = $this->input->post('status',true);
		$request['subscriptionId'] = $this->input->post('SubscriptionId',true);
		
		$this->load->library(array('subscription_client'));
		$objSubs = new Subscription_client();
		$response =  $objSubs->changeConsumedIdDates($this->appId,$request);
		error_log_shiksha("SS >> ".print_r($response,true)); 
		echo json_encode($response);
		}
	
	function getMainCollegeLinkSubscriptionDetails()
		{
		$validity = $this->checkUserValidation();
		$usergroup = $validity[0]['usergroup'];
		$this->init();
		$subscriptionId = $this->input->post('subscriptionId');
		$typeId = $this->input->post('listingTypeId');
		$type = $this->input->post('listingType');
		
		$this->load->model('SumsModel');
		$result=$this->SumsModel->getMainCollegeLinkSubscriptionProperties($subscriptionId,$type,$typeId);
		echo json_encode($result);
		}			
}
?>
