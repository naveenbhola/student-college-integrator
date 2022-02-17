<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of index
 *
 * @author nawal
 */
class index extends MX_Controller {
    
    protected $_usergroupAllowed;
    
    function __construct() {
        $this->load->library('DbLibCommon');
        $this->_usergroupAllowed = array('lead_operator');
        $this->dbLibObj = DbLibCommon::getInstance('LMS');
		$this->load->builder('LocationBuilder','location');
    }
    
    function index()
    {
	    error_log_shiksha("IN LMS SERVER ");
	    $this->load->library('xmlrpc');
	    $this->load->library('xmlrpcs');
	    $this->load->library('userconfig');
	    $this->load->library('cacheLib');
	    $this->load->helper('url');
	    
	    $config['functions']['updateOfflineOpsTable'] = array('function' => 'index.updateOfflineOpsTable');
	    
	    $args = func_get_args();		
	    $method = $this->getMethod($config,$args);
	    return $this->$method($args[1]);
    }

    public function dashboard($searchCriteria = 'india', $timeInterval = '7', $start = '0', $count = '10', $endDate = '0', $cat = '0', $subCat = '0', $ldbCourse = '0', $preferredCity = '0', $email = '0') {
        
        // get the user data
	$displayData = $this->_userValidation();
        if($endDate === 0){
            $endDate = date("Y-m-d");
        }
        $responses = $this->_getAllResponsesForFreeCourses($searchCriteria, $timeInterval, $start, $count, $endDate, $cat, $subCat, $ldbCourse, $preferredCity, $email);
        $displayData['resultResponse'] = $responses;
        $displayData['resultResponse']['numrows'] = $responses['totalResponses'];
        $displayData['searchCriteria'] = $searchCriteria;
        $displayData['startOffset'] = $start;
        $displayData['countOffset'] = $count;
        $displayData['timeInterval'] = $timeInterval;
        $displayData['endDate'] = $endDate;
        $displayData['cat'] = $cat;
        $displayData['subCat'] = $subCat;
        $displayData['ldbCourse'] = $ldbCourse;
        $displayData['preferredCity'] = $preferredCity;
        $displayData['email'] = $email;
            
	$this->load->library(array('LDB_Client','category_list_client','listingPosting/AbroadCommonLib'));
	$categoryClient = new Category_list_client();
	$abroadCommonLib = new AbroadCommonLib();
	if($searchCriteria == 'abroad'){
	    $catSubcatList = $abroadCommonLib->getAbroadCategories();
	    $displayData['categoryList'] = $catSubcatList;
	}
	else {
	    $catSubcatList = $categoryClient->getCatSubcatList(2,1);
	    $subCategories = array();
	    foreach($catSubcatList as $value){
			foreach($value['subcategories'] as $value2){
			    $subCategories[] = $value2['catId'];
			}
	    }
	    $courseList = $categoryClient->getSubCategoryCourses(implode(",",$subCategories));
	    $catSubcatCourseList = array();
	    foreach($catSubcatList as $key=>$value){
	    	$catSubcatCourseList[$key] = $value;
			foreach($value['subcategories'] as $value2){
			    $catSubcatCourseList[$key]['subcategories'][$value2['catId']]['courses'] =  $courseList[$value2['catId']];
	    	}
	    }
	    $displayData['categoryList'] = $catSubcatCourseList;
	}
	$cityListTier1 = $categoryClient->getCitiesInTier($this->appId, 1, 2);
	$cityListTier2 = $categoryClient->getCitiesInTier($this->appId, 2, 2);
	$displayData['cityList'] = array_merge($cityListTier1, $cityListTier2);
	$displayData['cityList_tier1'] = $cityListTier1;
	$displayData['cityList_tier2'] = $cityListTier2;
	$displayData['country_list'] = $categoryClient->getCountries($this->appId);
	$displayData['regions'] = json_decode($categoryClient->getCountriesWithRegions($this->appId), true);
	$ldbObj = new LDB_client();
	$displayData['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList($this->appId), true);
	error_log("####cities ".print_r($displayData['country_state_city_list'],true));
	error_log("####displayData ".print_r($displayData,true));
	$this->load->view('offlineOps/dashboard',$displayData);
	
    }
    
    public function myleads($searchCriteria = 'india', $timeInterval = '7', $start = '0', $count = '10'){
        // get the user data
	$displayData = $this->_userValidation();
        $responses = $this->_getAllLeadsForOperator($searchCriteria, $timeInterval, $start, $count);
        $userIdList = $responses['userIds'];
        if($userIdList != ''){
            $this->load->library('LDB_Client');
            $ldbObj = new LDB_client();
            if(is_array($userIdList)) {
                $UserDetailsArray = $ldbObj->sgetUserDetails(1, implode(",",$userIdList));
            } else {
                $UserDetailsArray = $ldbObj->sgetUserDetails(1, $userIdList);
            }
            $UserDataArray = json_decode($UserDetailsArray, true);
        }
        $data = array();
        $data['displayname'] = $displayData['displayname'];
        $data['resultResponse']['result'] = $UserDataArray;
        $data['numrows'] = $responses['totalResponses'];
        $data['searchCriteria'] = $searchCriteria;
        $data['startOffset'] = $start;
        $data['countOffset'] = $count;
        $data['timeInterval'] = $timeInterval;
        $this->load->view('offlineOps/myLeads',$data);
    }
    
    private function _userValidation() {
        $usergroupAllowed = $this->_usergroupAllowed;
        $validity = $this->checkUserValidation();	    
        global $logged;
        global $userid;
        global $usergroup;
        $thisUrl = $_SERVER['REQUEST_URI'];
        $errorType = "";
        if(($validity == "false" )||($validity == ""))
        {
            $logged = "No";
            $errorType = "notloggedin";
            header("location:/enterprise/Enterprise/loginEnterprise");
            exit();
        }
        else{
            $logged = "Yes";
            $userid = $validity[0]['userid'];
            $usergroup = $validity[0]['usergroup'];
            $displayname = $validity[0]['displayname'];
            if(!in_array($usergroup,$usergroupAllowed)){
                $errorType = "disallowedaccess";
                header("location:/enterprise/Enterprise/unauthorizedEnt");
                exit();
            }
        }
        $returnArr['userid']		= $userid;
        $returnArr['usergroup']		= $usergroup;
        $returnArr['logged'] 		= $logged;
        $returnArr['thisUrl'] 		= $thisUrl;
        $returnArr['validity'] 		= $validity;
        $returnArr['displayname'] 	= $displayname;
		
        if(!empty($errorType)){
            $returnArr['error']	= "true";
            $returnArr['error_type'] 	= $errorType;
        }
        return $returnArr;
    }
    
    private function _getAllResponsesForFreeCourses($searchCriteria, $timeInterval, $start, $count, $endDate, $cat, $subCat, $ldbCourse, $preferredCity, $email) {
        $this->load->library('lmsLib');
        $LmsClientObj = new LmsLib();
        $responses = $LmsClientObj->getAllResponsesForFreeCourses($searchCriteria, $timeInterval, $start, $count, $endDate, $cat, $subCat, $ldbCourse, $preferredCity, $email);
        $responses = json_decode($responses, true);
        return $responses;
        
    }
    
    private function _getAllLeadsForOperator($searchCriteria, $timeInterval, $start, $count) {
        $this->load->library('lmsLib');
        $LmsClientObj = new LmsLib();
        $responses = $LmsClientObj->getAllLeadsForOperator($searchCriteria, $timeInterval, $start, $count);
        $responses = json_decode($responses, true);
        return $responses;
    }


    public function addDetailsLayer() {
        
        $this->_userValidation();
        $loggedInUserData = $this->getLoggedInUserData();
        $operatorId = $loggedInUserData['userId'];
        $userId = (int)$this->input->post('userid');
        $userRepository = \user\Builders\UserBuilder::getUserRepository();
//        $usermodel = $this->load->model('user/usermodel');
        
        $userInfoArray = array();
        
        if(!empty($userId)) {
            $assignmentCheck = $this->_checkUserIfAssigned($userId);
            if($assignmentCheck['isAssigned']){
                if($assignmentCheck['operatorId'] === $operatorId){
                    
                    $userInfo = $userRepository->find($userId);
                    $userInfoArray['email'] = $userInfo->getEmail();
                    $userInfoArray['firstName'] = $userInfo->getFirstName();
                    $userInfoArray['lastName'] = $userInfo->getLastName();
                    $userInfoArray['mobile'] = $userInfo->getMobile();
                    $userInfoArray['userId'] = $userId;
                    $data['userData'] = $userInfoArray;
//                    $userPreference = $userInfo->getPreference();
                    
                }else{
                    $data['errorMsg'] = 'Already assigned';
                    //return;
                }
            }else{
                $data['errorMsg'] = 'Not assigned';
                //return;
            }
        }
        
        $data['registrationDomain'] = $this->input->post('registrationDomain');
        
//        $extraFlag = $userPreference->getExtraFlag();
        
        $this->load->view('offlineRegister',$data);
    }
    
    public function getContact(){
        
        $this->_userValidation();
        $loggedInUserData = $this->getLoggedInUserData();
        $operatorId = $loggedInUserData['userId'];
//                _p($loggedInUserData);exit;
        $userId = (int)$this->input->post('userid');
        $userRepository = \user\Builders\UserBuilder::getUserRepository();
        $usermodel = $this->load->model('user/usermodel');
        
        $userInfoArray = array();
        
        if(!empty($userId)) {
            $assignmentCheck = $this->_checkUserIfAssigned($userId);
            if($assignmentCheck['isAssigned']){
                if($assignmentCheck['operatorId'] === $operatorId){
                    $userInfo = $userRepository->find($userId);
                    echo  $userInfo->getMobile();
                }else{
                    echo 'Already assigned';
                }
            }else{
                $userInfo = $userRepository->find($userId);
                $this->_assignUser($operatorId, $userId);
                echo  $userInfo->getMobile();
            }
        }
        return;
    }
    
    protected function _assignUser($operatorId, $userId){
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        $table_data = array(
                'operatorId' => $operatorId,
                'userId' => $userId,
                'created_at'=> date("Y-m-d H:i:s", time()),
        );
                
        $queryCmd = $dbHandle->insert_string('offlineOperatorTracking',$table_data);		
        $query = $dbHandle->query($queryCmd);
        if (!$query) 
        { 
            error_log('Database Error:' . mysqli_error());
            echo ('Database Error:' . mysqli_error());
        }
        else 
        {
            $recent_id = $dbHandle->insert_id();
            return $recent_id;
        }
    }
    
    protected function _checkUserIfAssigned($userId){
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $msgArray = array();
        
        $query = "select * from offlineOperatorTracking where userId = ?";
        $result = $dbHandle->query($query, array($userId));
        $num_rows = $result->num_rows;//error_log('##nks count##'.$num_rows);
        if($num_rows){
            $msgArray['isAssigned'] = true;
            $msgArray['numRows'] = $num_rows;
            foreach ($result->result_array() as $row){
                $msgArray['operatorId'] = $row['operatorId'];
                $msgArray['userId'] = $row['userId'];
                $msgArray['status'] = $row['status'];
                $msgArray['id'] = $row['id'];
            }
        }
        else{
            $msgArray['isAssigned'] = false;
        }
        return $msgArray;
    }
    
    public function updateOfflineOpsTable($request){
        $parameters = $request->output_parameters();
        $userId = $parameters['0'];
        $loggedInUserData = $this->getLoggedInUserData();
        $operatorId = $loggedInUserData['userId'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        /**
        * Update status
        */
        $sql = "UPDATE offlineOperatorTracking SET status = '1' WHERE userId = ? and operatorId = ?";
        $query = $dbHandle->query($sql,array($userId, $operatorId));
        if (!$query) 
        { 
            error_log('Database Error:' . mysqli_error());
        }
        /**
        * Update tuserflag
        */
        $sql = "UPDATE tuserflag SET isResponseLead = 'NO' WHERE userid = ?";
        $query = $dbHandle->query($sql,array($userId));
        if (!$query) 
        { 
            error_log('Database Error:' . mysqli_error());
        }
        $response = base64_encode(true);
        return $this->xmlrpc->send_response($response);
    }
    
    public function getCategories($searchCriteria){
	$this->load->library(array('category_list_client','listingPosting/AbroadCommonLib'));
	$categoryClient = new Category_list_client();
	$abroadCommonLib = new AbroadCommonLib();
	
	if($searchCriteria == 'india') {
	    $catSubcatList = $categoryClient->getCatSubcatList(2,1);
	    $subCategories = array();
	    foreach($catSubcatList as $value){
		foreach($value['subcategories'] as $value2){
		    $subCategories[] = $value2['catId'];
		}
	    }
	    $courseList = $categoryClient->getSubCategoryCourses(implode(",",$subCategories));
	    $catSubcatCourseList = array();
	    foreach($catSubcatList as $key=>$value){
		$catSubcatCourseList[$key] = $value;
		foreach($value['subcategories'] as $value2){
		    $catSubcatCourseList[$key]['subcategories'][$value2['catId']]['courses'] =  $courseList[$value2['catId']];
		}
	    }
	    $responses = $catSubcatCourseList;
	}
	else {
	    $responses = $abroadCommonLib->getAbroadCategories();
	}
	$responses = json_encode($responses, true);
	error_log("####categoryList".print_r($responses,true));
	echo $responses;
	return;
    }
    
}
