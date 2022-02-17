<?php

/**
 * Description of CRM
 * @author ashish mishra
 */
define('USER', 'user');
define('PASSWORD', 'password');

class CRM extends MX_Controller {

    function init() {
        $this->load->library(array('LDB_Client','LmsLib','CRMFeedback_Client'));
    }

    // this api usunally called frm snapdragon
    function submitOfflineResponse() {
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            error_log("if");
            header('WWW-Authenticate: Basic realm="MyRealm"');
            header('HTTP/1.0 401 Unauthorized');
            exit;
        } elseif (isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] == USER && isset($_SERVER['PHP_AUTH_PW'])
                && $_SERVER['PHP_AUTH_PW'] == PASSWORD) {
            error_log("else if");
            $this->_submitOfflineResponse();
        } else {
            error_log("else");
            header('HTTP/1.0 400 Bad Request');
            exit;
        }
    }

    function exportByCron($userid = "") {
        $this->load->library('CRM_Client');
        $this->crm_client->exportByCron();
    }

    function getCounsellorList() {
        $this->load->library('CRM_Client');
        print_r($this->crm_client->getCounsellorList());
    }

   
    function getrecommendations($userid = '-1',$counsellorid = '-1',$listingtypeid = '0',$setcheck = 'failed')
    {

	    $this->init();
	    $this->load->library('recommendation_front_lib');
	    $this->load->library('CRMFeedback_Client');
	    
	    if(isset($_POST['userid']))
	    {
		    $userid = $this->input->post('userid');
		    $counsellorid = $this->input->post('counsellorid');
	    }
	    /* This is for show failre msg on page*/
	    $successmessageflag = 'unset';
	    $recommendations = array();
	    $data = array();
	    $users = (array)$userid;
	    
	    if(isset($_POST['textdata']))
	    {
		    $recievedarray = $this->getdataforlistingtype($_POST['textdata']);
		    $data = $recievedarray['arr'];
		    $successmessageflag = $recievedarray['flag'];
	    }
	    
	    /*  This is for showing Default recommendation count on page*/
	    $recommendationscount = 20;
	    $recommendations = $this->recommendation_front_lib->getRecommendations($users,$recommendationscount);


	    $recommendation_data = json_decode(gzuncompress(base64_decode($recommendations)),true);
	    $LmsClientObj = new LmsLib();
	    $crmClientobj = new CRMFeedback_Client();


	    error_log("IN get listing details");
	    $institutesbeforejsoned = $crmClientobj->getlistingdetails($appid,$userid);

	    /* Institute list after json*/

	    $institutesafterjsondecode = json_decode($institutesbeforejsoned,true);
	    error_log("before loop");
	    /* Profile based recomendation */
	    foreach($recommendation_data['recommendations'][$userid]['recommendations'] as $arr)
	    {
		    $profile_based = $arr['recommendations']['profile_based'];
	    }
	    error_log("after loop");



	    // merging responses boith from sugarDB and shiksha
        $this->load->library('CRM_Client');
        $responsesalreadymade = $this->crm_client->responsesalreadymadeforuser($userid);
       
       
           error_log("afterresponsesalreadymadeforuser ");
        $listingtypeidafterjson = json_decode($responsesalreadymade ,true);
        $course = $crmClientobj->getcourselist(1,$listingtypeidafterjson[0]['list']);
        $institutes = array();
/*
	error_log("before loop");
	    foreach($course as $coursetraversal)
	    {
		    if(!empty($coursetraversal) && isset($coursetraversal))
		    {
			    $institutesbeforejson = json_decode($crmClientobj->getlistingdetailsforlistingtypeid($coursetraversal['course_id'],$coursetraversal['institute_id']),true);
			    $institutes[] = $institutesbeforejson[0];
		    }
	    }
	error_log("after getlistingdetaisl loop");
*/
	    $institutesafterjson =  array_merge((array)$institutesafterjsondecode, (array)$institutes);
	    /* Array to be sent to template*/
	    $final = array(
			    'userid' => $userid,
			    'counsellorid' => $counsellorid,
			    'profilebased' =>$profile_based,
			    'institutes' => $institutesafterjson,
			    'arr' =>$data,
			    'listingid' => $listingtypeid,
			    'check' => $setcheck,
			    'Resultflag' => $successmessageflag
			  );

	     error_log("after loading template");
	    $this->load->view('enterprise/shikshacrm',$final);

			  
 
    }


	function getdataforlistingtype($listingtypeids)
	{

		$this->load->library('recommendation_front_lib');

error_log("GETTING DATA FOR LISTING TYPE");
		$this->init();
		$appId = 1;
		$LmsClientObj = new LmsLib();
		$object = new CRMFeedback_Client();

		$course = array();

		// Get courses if listingtypeid is set
		if(isset($listingtypeids) && !empty($listingtypeids))
		{
			/* This is for show failre msg on page*/
			$flag = 'set';
			$listingid = (int)$listingtypeids;  
			$course = $object->getcourselist(1,$listingid);

			$institutes = array();


			foreach($course as $coursetraversal)
			{
				if(!empty($coursetraversal) && isset($coursetraversal))
				{
					$institutes[] = $object->getlistingdetailsforlistingtypeid($coursetraversal['course_id'],$coursetraversal['institute_id']);
				}
			}

			//Json decoded the insstitutes data
			foreach($institutes as $institetraversal)
			{
				$arr[] = json_decode($institetraversal,true);
			}

			$final = array(
					'userid' => $userid,
					'arr' => $arr,
					'flag' => $flag
				      );
		}
		return $final;

	}


    
    /**
     * API for generating User Data Array
     */
    function createUserDataArray($UserDetailsArray) {
        error_log("inside createUserDataArray " . print_r($UserDetailsArray, true));
        $LocalCourseArray = array();
        foreach ($UserDetailsArray as $userDetails) {
            $formattedUserDetails = array();
            $formattedUserDetails['Name'] = $userDetails['displayname'];
            $formattedUserDetails['Email'] = $userDetails['email'];
            $formattedUserDetails['Mobile'] = $userDetails['mobile'];
            $LocalCourseArray[] = $formattedUserDetails;
        }
        return $LocalCourseArray;
    }

    function EnterpriseUserRegisterFeedback() {

        $this->init();

        $addReqInfo = array();
        $addReqInfo['crm_clientid'] = $_POST['crm_clientid'];
        $addReqInfo['comments'] = $_POST['comments'];
        $addReqInfo['userid'] = $_POST['crm_lead_id'];
        $addReqInfo['crm_counslarid'] = $_POST['crm_counslarid'];
        $addReqInfo['score'] = $_POST['score'];


        $crmClientobj = new CRMFeedback_Client();
	$addLeadStatus = $crmClientobj->EnterpriseUserRegisterFeedback(1,$addReqInfo);


        echo $addLeadStatus;
    }


    function crmresponses() { 
	  $this->load->library('CRM_Client'); 
   
	  $listingtypeids = $this->input->post('listingtypeid'); 
   
	  $this->init();

	  $appId = 1; 

	  $LmsClientObj = new LmsLib(); 

	 $userid = $this->input->post('userid'); 
	  $counsellorid = $this->input->post('counsellorid'); 
   
	  $ldbObj = new LDB_client(); 
   
	  $UserDetailsArray = $ldbObj->sgetUserDetails($appId, $userid); 
   
	  $UserDataArray = $this->createUserDataArray(json_decode($UserDetailsArray, true)); 
   
	  $addReqInfo = array(); 
	  $addReqInfo['displayName'] = $UserDataArray[0]['Name']; 
	  $addReqInfo['contact_cell'] = $UserDataArray[0]['Mobile']; 
	  $addReqInfo['userId'] = $userid; 
	  $addReqInfo['contact_email'] = $UserDataArray[0]['Email']; 
	  $addReqInfo['action'] = "Offline CRM Response"; 
	  $addReqInfo['listing_type'] = 'course'; 
	  print_r($addReqInfo); 
	  //$LmsClientObj = new LmsLib(); 
	  foreach ($listingtypeids as $listingtypeid) { 
	      $this->crm_client->dumpCrmResponseData($userid, $listingtypeid, $addReqInfo['contact_email'], $addReqInfo['contact_cell'], $addReqInfo['displayName'], 'to_be_processed',$counsellorid); 
	  } 
	  $url = "/crm/CRM/getRecommendations/" . $userid . "/" . $counsellorid . "/-1/success"; 
	  header("Location: $url"); 
      } 

   function sendRequestCreateResponse() {
        // read data
        // pick latest 50
        // make curl to shiksha.com from snapdragon
        $this->load->library('CRM_Client');
        $arr = $this->crm_client->getPendingLeadsForResponse();
        $url = SHIKSHA_LIVE_SERVER_URL;
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, USER . ":" . PASSWORD);

        foreach ($arr as $key => $val) {
            error_log($val['userid'] . "--" . $val['listing_type_id'] . "\n");
            $fields = array(
                'userid' => urlencode($val['userid']),
                'listing_type_id' => urlencode($val['listing_type_id']),
                'contact_cell' => urlencode($val['contact_cell']),
                'contact_email' => urlencode($val['contact_email']),
                'displayName' => urlencode($val['displayName']),
                'counsellorId' => urlencode($val['counsellorId']),
            );
            //url-ify the data for the POST
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

	    curl_setopt($ch,CURLOPT_TIMEOUT,59);
            //execute post
            $result = curl_exec($ch);
            if ($result == "success") {
                $this->crm_client->responseSentSuccess($val['id'],'processed');
            }
        }
        //close connection
        curl_close($ch);
    }

    function _submitOfflineResponse() {
        error_log("ashish was here, so was red.");
        $userid = $this->input->post("userid");
        $contact_cell = $this->input->post("contact_cell");
        $contact_email = $this->input->post("contact_email");
        $displayName = $this->input->post("displayName");
        $listingtypeid = $this->input->post("listing_type_id");
        $counsellorId = $this->input->post("counsellorId");
        
        $appId = 1;
        $this->load->library(array('LmsLib','Listing_client','LDB_Client'));
        $LmsClientObj = new LmsLib();
        $ListingClientObj = new Listing_client();
        $addReqInfo = array();
        $addReqInfo['displayName'] = $displayName;
        $addReqInfo['contact_cell'] = $contact_cell;
        $addReqInfo['userId'] = $userid;
        $addReqInfo['contact_email'] = $contact_email;
        $addReqInfo['action'] = "Offline CRM Response";
        $addReqInfo['listing_type'] = 'course';
        $addReqInfo['listing_type_id'] = $listingtypeid;
        $addReqInfo['CounsellorId'] = $counsellorId;
        

        $addLeadStatus = $LmsClientObj->insertTempLead($appId, $addReqInfo);
        $ldbObj = new LDB_client();
        $signedInUser = $ldbObj->sgetUserDetails($appId, $userid);
        $addReqInfo['userInfo'] = $signedInUser;
        $addReqInfo['sendMail'] = false;
        $addLeadStatus = $LmsClientObj->insertLead($appId, $addReqInfo);
        echo "success";
    }

}

