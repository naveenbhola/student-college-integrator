<?php

/**
 * Class NationalUpgradeCourses
 * @author Aman Varshney <aman.varshney@shiksha.com>
 * @date   2015-05-20
 */
class NationalUpgradeCourses extends MX_Controller {

	private $upgradeCourseModel;
	private $coursepostModel;

	public function __construct()
    {
		// load the config
		parent::__construct();
		$this->upgradeCourseModel = $this->load->model('listing/upgradecoursemodel');
		$this->coursepostModel    = $this->load->model('listing/posting/coursepostmodel');
	}

	/**
	 * Method to view National Upgrade Downgrade Screen
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-05-20
	 * @return
	 */
	public function index(){
		$userStatus           = $this->cmsUserValidation();
		
		if(($userStatus      == "false" ) || ($userStatus == "")) {
			header('location:/enterprise/Enterprise/loginEnterprise');
			exit();
		}
		$data['headerTabs']   = $userStatus['headerTabs'];
		$data['validateuser'] = $userStatus['validity'];
		$data['editedBy']     = $userStatus['userid'];

		$data['formName'] 	= "addPaidClient";

		$this->load->view('listing/upgradeCourses',$data);
	}

	/**
	 * Ajax call to get course data
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-06-18
	 * @param  Integer     $courseId Course Id
	 * @return        
	 */
	public function getNationalCourseWithClientData($courseId){		
		$data 	= $this->upgradeCourseModel->getCourseWithClientData($courseId );
		echo json_encode($data);
	}

	public function getClientSubscriptionData($clientId)
    {
		$userRepository = \user\Builders\UserBuilder::getUserRepository();
		$userObj = $userRepository->find($clientId);
		if(empty($userObj)){
			echo "-1";
			return false;
		}
		$this->load->library('sums_product_client');
		$objSumsProduct =  new Sums_Product_client();
		$finalSubscriptionDetails = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$clientId));
		//_p($finalSubscriptionDetails);
		$optionsHtml = '';
			$goldFlag = false;                    
			foreach($finalSubscriptionDetails as $key=>$vals){
				if( ($vals['BaseProdCategory']=='Listing')){
					if($vals['BaseProdSubCategory']=='Gold' || strtolower($vals['BaseProdSubCategory']) == 'gold sl' || strtolower($vals['BaseProdSubCategory']) == 'gold ml') {
						$goldFlag = true;
					}
			if($vals['BaseProdSubCategory']=='Bronze'){
						$bronzeFlag = true;
					}
			if($vals['BaseProdSubCategory']=='Silver'){
						$silverFlag = true;
					}
				}
			}
			
			if($goldFlag){
				$i = 1;
				foreach($finalSubscriptionDetails as $key=>$vals){
					if($vals['BaseProdSubCategory']=='Gold' || strtolower($vals['BaseProdSubCategory']) == 'gold sl' || strtolower($vals['BaseProdSubCategory']) == 'gold ml'){
					
					$optionsHtml .= '<option value="'.$key.'" '.($goldMLFlag==false && $i==1?'selected="selected"':'').'>'.$vals['BaseProdCategory']."-".$vals['BaseProdSubCategory']." : ".$key.($i==1?" (Recommended Gold)":"").'</option>';
					$i++; 
					}else{
						continue;
					}
				}
			}
		if($bronzeFlag){
				foreach($finalSubscriptionDetails as $key=>$vals){
					if($vals['BaseProdSubCategory']=='Bronze'){
	
					$optionsHtml .= '<option value="'.$key.'" '.($goldFlag==false && $silverFlag==false?'selected="selected"':'').'>'.$vals['BaseProdCategory']."-".$vals['BaseProdSubCategory']." : ".$key.'</option>';
					$i++; 
					}else{
						continue;
					}
				}
			}
		echo json_encode(array("name"=>$userObj->getDisplayName(),"html"=>$optionsHtml));
    }

    /**
     * Method to save course upgrade/downgrade data
     * @author Aman Varshney <aman.varshney@shiksha.com>
     * @date   2015-06-18
     * @return
     */
    public function savePaidClient()
    {
    	
    	$cmsUserInfo 	= 	$this->cmsUserValidation();

    	if($cmsUserInfo['usergroup'] != 'cms'){
			echo json_encode(array("Success" => "/enterprise/Enterprise/disallowedAccess"));
			exit();
		}

		// gather post data
		$paidClientFormData = $this->postDataForPaidClientForm();
		
		if($paidClientFormData['course_id'] == ''){
			echo json_encode(array("Success" => "/enterprise/Enterprise/disallowedAccess"));
			exit();
		}

		$clientId  = (int) $paidClientFormData['client_id'];
		$courseId  = (int) $paidClientFormData['course_id'];
 		// $value[$paidClientFormData['course_id']] = $paidClientFormData['client_id'];
		modules::run('response/ResponseIndexer/updatedClientIdCache', $clientId,$courseId); 

		$rdcLib = $this->load->library('enterprise/ResponseDeliveryCriteriaLib');      
		$oldcourseDetails = $rdcLib->getPaidCourses($courseId); 
		$oldcourseDetails = $oldcourseDetails[0];

 		// send post data to posting lib for saving/updating
		$subscriptionClient = $this->load->library('Subscription_client');
		$courseIds          = $this->coursepostModel->courseUpgradeDowngrade($subscriptionClient,$paidClientFormData);
		// error_log("AMAN courseIds".print_r($courseIds,true));
		// solr and refresh cache
		
		$disableCourses = array();
        $courseDetails = $rdcLib->getPaidCourses($courseId, 'write'); 
        $courseDetails = $courseDetails[0];

		if((!empty($oldcourseDetails)) && (empty($courseDetails))) {			
			if(($oldcourseDetails['username'] != $clientId) && (!empty($courseIds))) {
				$disableCourses = $courseIds;
			} else {
				$disableCourses = $courseId;
			}
		} else if((!empty($oldcourseDetails)) && (!empty($courseDetails)) && ($oldcourseDetails['username'] != $courseDetails['username']) && (!empty($courseIds))) {
			$disableCourses = $courseIds;
		}

		if(!empty($disableCourses)) {
			$rdcLib->disableResponseCriteria($disableCourses);
		}

		if(is_array($courseIds) && !empty($courseIds)){
		    $this->coursecache = $this->load->library('nationalCourse/cache/NationalCourseCache');
		    $this->coursecache->removeCoursesCache($courseIds);// delete the courses from cache

			$this->load->library('indexer/NationalIndexingLibrary');
			foreach ($courseIds as $value) {
		        $this->NationalIndexingLibraryObj = new NationalIndexingLibrary;
		        $this->NationalIndexingLibraryObj->addToNationalIndexQueue($value,'course','index',array('courseBasicSectionData')); //adding solr log table to reindex
				// error_log("AMAN VARSHNEY ".$value);
				// modules::run('search/Indexer/addToQueue', $value,"course","index","true"); 
			}			  
		}
		if($courseIds == false){
			echo json_encode("Something went wrong");
			exit();
		}

		$this->load->view('listing/upgradeCoursesComplete');

    }

    private function postDataForPaidClientForm()
    {
		$paidClientFormData                      = array();
		$paidClientFormData['course_id']         = $this->input->post('course_id');
		$paidClientFormData['client_id']         = $this->input->post('client_id');
		$paidClientFormData['subscription']      = $this->input->post('subscription');
		$paidClientFormData['editedBy']          = $this->input->post('editedBy');
		return $paidClientFormData;
    }


    public function validateSubscriptionInfo($userGroup, $onBehalfOf, $subscriptionId, $clientId)
	{
		$responseArray[0] = 1;
		if(!($userGroup == 'cms' && $onBehalfOf == "false" ) && $subscriptionId != "") {
			$objSumsProduct = $this->load->library('sums_product_client');
			$subscriptions = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$clientId));
			if(is_array($subscriptions[$subscriptionId])) {
				$chosenSubsArray = $subscriptions[$subscriptionId];
			} else {
				$chosenSubsArray = "";
			}
			
			if(!(is_array($chosenSubsArray) && $chosenSubsArray['BaseProdPseudoRemainingQuantity'] > 0)) {
                $responseArray[0] = 0;
			    $responseArray[1] = 'Your chosen subscription has been consumed with other listings. Please select some other subscription to proceed.';
			    $responseArray[2] = $subscriptionId;				
			}
		}

		echo json_encode($responseArray);
		exit;
	}
}
