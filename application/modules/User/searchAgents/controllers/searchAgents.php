<?php

/**
 * SearchAgents Class
 * @package Package SearchAgents
 * @subpackage SearchAgents Front-end
 * @category LDB
 * @author Shiksha Team
 * @link https://www.shiksha.com
 */

class SearchAgents extends MX_Controller {

	private $userStatus = 'false';

	/**
	* init() API
	* Load all required libraries & helper
	* @param NULL
	* @return Void
	*/

	function init()
	{
		$this->load->helper(array('url','form','shikshautility'));
		$this->load->library(array('SearchAgents_client','ajax','enterprise_client','LDB_Client','ajax','category_list_client'));
		$this->userStatus = $this->checkUserValidation();
		if(($this->userStatus == "false" )||($this->userStatus == ""))
		{
			header('location:/enterprise/Enterprise/loginEnterprise');
			exit();
		}
		if($this->userStatus[0]['usergroup'] != 'enterprise')
		{
			header("location:/enterprise/Enterprise/unauthorizedEnt");
		}
		$validity = $this->userStatus;
		$entObj = new Enterprise_client();
		$headerTabs = $entObj->getHeaderTabs(1,$this->userStatus[0]['usergroup'],$this->userStatus[0]['userid']);
		$headerTabs[0]['selectedTab'] = 31;
		$this->userStatus[0]['headerTabs'] = $headerTabs;
	}

	/**
	* get Existing SearchAgents
	* @param int $clientId logged-in user's id
	* @return array return all existing search Agents info.
	*/

	function getAllExistingSearchAgents()
	{
		$this->init();
		$Validity = $this->checkUserValidation();
		$clientId = $Validity[0]['userid'];
		$categoryClient = new SearchAgents_client();
		$appId = 1;
                $arr = $categoryClient->getAllSearchAgents($appId,$clientId);
		return $arr;
	}
        function remove_array_empty_values($array, $remove_null_number = false)
    {
            $new_array = array();

            $null_exceptions = array();

            foreach ($array as $key => $value)
            {
                    $value = trim($value);

                if($remove_null_number)
                {
                    $null_exceptions[] = '';
                }

                if(!in_array($value, $null_exceptions) && $value != "")
                {
                    $new_array[] = $value;
                }
            }
        return $new_array;
    }
	/**
	* Add Search Agent
	* @param array search form's post array
	* @return void
	*/

	function addSearchAgent()
	{
		error_log("***** inside addSearchAgent ******");
		$this->init();
		$Validity = $this->checkUserValidation();
		$clientId = $Validity[0]['userid'];
		$finalArray = array();
		$search_agent_data = array();
		$leadDeliveryMethod = $_POST['leadDeliveryMethod'];
		$inputData_Search_Agent = $_POST['inputData_Search_Agent'];
		$inputArray = json_decode(base64_decode($inputData_Search_Agent),true);
		$displayData_Search_Agent = $_POST['displayData_Search_Agent'];
		$inputHTMLData_Search_Agent = $_POST['inputHTMLData_Search_Agent'];
		$search_agent_data['SA_displayData_Search_Agent']= $displayData_Search_Agent;
		$search_agent_data['SA_inputHTMLData_Search_Agent']= $inputHTMLData_Search_Agent;
		$search_agent_data['SA_inputData_Search_Agent']= $inputData_Search_Agent;
		/* Main Search Agent */
		$search_agent_name = $this->input->post('searchagent_name',TRUE);
                $finalArray['searchagentName'] = $search_agent_name;
		$group_id = $this->input->post('group_id',TRUE);
		if(!empty($group_id)) {
			$finalArray['credit_group'] = $group_id;
		}
		$type = $this->input->post('type',TRUE);
		$finalArray['type'] = $type;			
		$finalArray['clientid'] = $clientId;
		/* [searchagent_name] & [lead_type] => shared */
		$pref_lead_type = $this->input->post('lead_type',TRUE);
		if ($pref_lead_type == 'shared')
		{
			$finalArray['pref_lead_type'] =  'shared';
		}
		if ($pref_lead_type == 'premium')
		{
			$finalArray['pref_lead_type'] =  'premium';
		}
		/*auto_download START */
		$flag_auto_download = $this->input->post('checkbox_flag_auto_download',TRUE);
		if ($flag_auto_download == 'on')
		{
			$finalArray['leads_daily_limit']  = $this->input->post('leadsperday',TRUE);
			$finalArray['price_per_lead'] = $this->input->post('set_price_per_lead',TRUE);
			$email_freq = $this->input->post('send_lead_option',TRUE);
			if ($email_freq == 'asap')
			{
				$finalArray['email_freq'] = 'asap';
			}
			if ($email_freq == 'everyhour')
			{
				$finalArray['email_freq'] = 'everyhour';
			}
			$sms_freq = $this->input->post('send_lead_option_sms',TRUE);
                        if ($sms_freq == 'asap')
                        {
                                $finalArray['sms_freq'] = 'asap';
                        }
                        if ($sms_freq == 'everyhour')
                        {
                                $finalArray['sms_freq'] = 'everyhour';
                        }
			$finalArray['is_active'] = 'live';
		}
		/*auto_download END */
		$checkbox_auto_responder = $this->input->post('checkbox_auto_responder',TRUE);
		/* auto_responder START */
		if ($checkbox_auto_responder == 'on')
		{
			$autoResponder_email = array();
			$email_behalf = $this->input->post('emailmybehalf',TRUE);
			if ($email_behalf == 'on')
			{
				$from_emailid = $this->input->post('from_emailid',TRUE);
				if (!empty($from_emailid))
                                {
                                        $autoResponder_email['from_emailid'] = $from_emailid;
                                }
                                $from_name = $this->input->post('from_name',TRUE);
                                if (!empty($from_name))
                                {
                                        $autoResponder_email['from_name'] = $from_name;
                                }
				$subject = $this->input->post('subjectforautoresponder',TRUE);
				if (!empty($subject))
				{
					$autoResponder_email['subject'] = $subject;
				}
				$msg = $this->input->post('auto_responder_email_textarea_msg',TRUE);
				if (!empty($msg))
				{
					$autoResponder_email['msg'] = $msg;
				}
				$daily_limit = $this->input->post('daily_limt_auto_email',TRUE);
				if (!empty($daily_limit))
				{
					$autoResponder_email['daily_limit'] = $daily_limit;
				}
				$autoResponder_email['is_active'] = 'live';
			}

			$autoResponder_sms = array();
			$checkbox_sendsms = $this->input->post('checkbox_sendsms',TRUE);
			if ($checkbox_sendsms == 'on')
			{
				$msg = $this->input->post('auto_responder_sms_textarea_msg',TRUE);
				if (!empty($msg))
				{
					$autoResponder_sms['msg'] = $msg;
				}
				$daily_limit = $this->input->post('daily_limt_auto_sms',TRUE);
				if (!empty($daily_limit))
				{
					$autoResponder_sms['daily_limit'] = $daily_limit;
				}
				$autoResponder_sms['is_active'] = 'live';
			}
		}
		/* auto_responder END */
		/* Save Emails , Mobile Nos START */
		if ($flag_auto_download == 'on')
		{
			$contactDetails = array();
			$email_ids = $this->input->post('auto_download_email',TRUE);
			$mobile_nos = $this->input->post('mobileno',TRUE);
			if (isset($email_ids) && is_array($email_ids))
			{
				$contactDetails['email_ids'] =  array_unique($this->remove_array_empty_values($email_ids));
			}
			if (isset($mobile_nos) && is_array($mobile_nos))
			{
				$contactDetails['mobile_nos'] =  array_unique($this->remove_array_empty_values($mobile_nos));
			}
		}
		if ($flag_auto_download == 'on') {
			$finalArray['flag_auto_download'] = 'live';
		} else {
			$finalArray['flag_auto_download'] = NULL;
		}
		if ($email_behalf == 'on')
		{
			$finalArray['flag_auto_responder_email'] = 'live';
		} else {
			$finalArray['flag_auto_responder_email'] = NULL;
		}
		if ($checkbox_sendsms == 'on')
		{
			$finalArray['flag_auto_responder_sms'] = 'live';
		} else {
			$finalArray['flag_auto_responder_sms'] = NULL;
		}
		$finalArray['deliveryMethod'] = $leadDeliveryMethod;
		
		/* Save Emails , Mobile Nos END */
		$data = array('search_agent'=>$finalArray,'auto_responder_email'=>$autoResponder_email,'auto_responder_sms'=>$autoResponder_sms,'contact_details'=>$contactDetails,'inputArray'=>$inputArray,'search_agent_data'=>$search_agent_data);
		$SAClientObj = new SearchAgents_client();
		$appId = 1;
		//sdump($inputArray);exit;
		$response = $SAClientObj->addSearchAgent($appId,$data);
		
		echo json_encode($response);
	}

	/**
	* AJAX api to get HTML of search Agent Form/overlay
	*/
	function displayStaticContent($html_type='form', $actual_course_id = '',$groupId)
	{

		require_once APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
		$data = array();
		$data['Search_Agent_Update_flag']=false;
		$data['Search_Agent_Form_Url']='/searchAgents/searchAgents/addSearchAgent/';
		$data['actual_course_id'] = $actual_course_id;
		// $data['group_id'] = $groupId;
		$data['coursesList'] = $coursesList;
		if ($html_type == 'form')
		{
			$this->init();
			$ldbObj = new LDB_client();
			if(isset($groupId) && $groupId != 0 && $groupId != ''){
				$data['credit_consumption'] = $ldbObj->getCreditConsumedByGroup($groupId);
			}
			
			echo $this->load->view("searchAgents/searchAgentForm",$data);
		}
		else
		{
			echo $this->load->view("searchAgents/search_agent_edit_email_overlay",$data);
		}
	}

	function getCtab_name(){

		return array(
            array('ctab_name' =>'Study Abroad','course_name' => 'Study Abroad', 'tab_type' => 'studyAbroad'),
            array('ctab_name' =>'Domestic Leads','course_name' => 'National Courses', 'tab_type' => 'national'),
            array('ctab_name' =>'Domestic MR','course_name' => 'MBA (Full Time)', 'tab_type' => 'national'),
            array('ctab_name' =>'Domestic MR','course_name' => 'B.E./B.Tech (Full Time)', 'tab_type' => 'national')
        );
		// return array(
		// 			array('ctab_name' =>'Study Abroad','course_name' => 'Study Abroad', 'tab_type' => 'studyAbroad'),
		// 			array('ctab_name' =>'Management','course_name' => 'BBA/BBM', 'tab_type' => 'national'),
		// 			array('ctab_name' =>'Management','course_name' => 'Full Time MBA/PGDM', 'tab_type' => 'national'),
		// 			array('ctab_name' =>'Management','course_name' => 'Distance/Correspondence MBA', 'tab_type' =>
		// 			'local'),
		// 			array('ctab_name' =>'Management','course_name' => 'Executive MBA', 'tab_type' => 'national'),
		// 			array('ctab_name' =>'Management','course_name' => 'Full-time MBA', 'tab_type' => 'national'),
		// 			array('ctab_name' =>'Management','course_name' => 'Integrated MBA Courses', 'tab_type' =>
		// 			'national'),
		// 			array('ctab_name' =>'Management','course_name' => 'Management Certifications', 'tab_type' =>
		// 			'local'),
		// 			array('ctab_name' =>'Management','course_name' => 'Online MBA', 'tab_type' => 'national'),
		// 			array('ctab_name' =>'Management','course_name' => 'Other Management Degrees', 'tab_type' =>
		// 			'national'),
		// 			array('ctab_name' =>'Management','course_name' => 'Part-time MBA', 'tab_type' => 'local'),

		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'Aircraft Maintenance Engineering',
		// 			'tab_type' => 'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'Advanced Technical Courses',
		// 			'tab_type' => 'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'B.E./B.Tech', 'tab_type' =>
		// 			'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'B.Sc', 'tab_type' =>
		// 			'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'Distance B.Sc', 'tab_type' =>
		// 			'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'Distance B.Tech', 'tab_type' =>
		// 			'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'Distance M.Sc', 'tab_type' =>
		// 			'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'Engineering Diploma', 'tab_type'
		// 			=>
		// 			'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'Engineering Distance Diploma',
		// 			'tab_type' =>
		// 			'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'Integrated MBA Courses',
		// 			'tab_type' =>
		// 			'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'M.E./M.Tech', 'tab_type' =>
		// 			'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'M.Sc', 'tab_type' =>
		// 			'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'Marine Engineering', 'tab_type'
		// 			=>
		// 			'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'Medicine, Beauty & Health Care Degrees','tab_type' =>
		// 			'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'Science & Engineering Degrees',
		// 			'tab_type' => 'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'Science & Engineering PHD',
		// 			'tab_type' => 'national'),
		// 			array('ctab_name' =>'Science & Engineering','course_name' => 'Integrated MBA Courses',
		// 			'tab_type' => 'national'),
		// 			array('ctab_name' =>'IT','course_name' => 'Distance BCA/MCA','tab_type' =>'local'),
		// 			array('ctab_name' =>'IT','course_name' => 'IT Courses','tab_type' =>'local'),
		// 			array('ctab_name' =>'IT','course_name' => 'IT Degrees','tab_type' =>'national'),
		// 			array('ctab_name' =>'Animation','course_name' => 'Animation Courses','tab_type' =>'local'),
		// 			array('ctab_name' =>'Animation','course_name' => 'Animation Degrees','tab_type' =>'national'),
		// 			array('ctab_name' =>'Hospitality','course_name' => 'Hospitality, Aviation & Tourism Courses','tab_type'=>'local'),
		// 			array('ctab_name' =>'Hospitality','course_name' => 'Hospitality, Aviation & Tourism Degrees','tab_type'=>'national'),
		// 			array('ctab_name' =>'Media','course_name' => 'Media, Films & Mass Communication Courses','tab_type'=>'local'),
		// 			array('ctab_name' =>'Media','course_name' => 'Media, Films & Mass Communication Degrees','tab_type'=>'national'),
		// 			array('ctab_name' =>'Test Preparation','course_name' => 'Test Prep','tab_type' =>'local'),
		// 			array('ctab_name' =>'Test Preparation','course_name' => 'Test Prep (International Exams)','tab_type'=>'local'),
		// 			array('ctab_name' =>'Others','course_name' => 'Arts, Law, Languages and Teaching Courses','tab_type'=>'local'),
		// 			array('ctab_name' =>'Others','course_name' => 'Arts, Law, Languages and Teaching Degrees','tab_type'=>'national'),
		// 			array('ctab_name' =>'Others','course_name' => 'Banking & Finance Courses','tab_type'
		// 			=>'local'),
		// 			array('ctab_name' =>'Others','course_name' => 'Banking & Finance Degrees','tab_type'
		// 			=>'national'),
		// 			array('ctab_name' =>'Others','course_name' => 'Design Courses', 'tab_type' =>'local'),
		// 			array('ctab_name' =>'Others','course_name' => 'Design Degrees', 'tab_type' =>'national'),
		// 			array('ctab_name' =>'Others','course_name' => 'Distance B.A./M.A.', 'tab_type'=>'local'),
		// 			array('ctab_name' =>'Others','course_name' => 'Foreign Language Courses','tab_type'=>'national'),
		// 			array('ctab_name' =>'Others','course_name' => 'Medicine, Beauty & Health Care Courses','tab_type'=>'local'),
		// 			array('ctab_name' =>'Others','course_name' => 'Medicine, Beauty & Health Care Degrees','tab_type'=>'national'),
		// 			array('ctab_name' =>'Others','course_name' => 'Retail Degrees','tab_type'=>'national'),
		// 		);
	}
	
	function openUpdateSearchAgent($start,$count,$deliveryMethod = 'normal', $type, $searchCriteria='created_on')
	{
		$this->init();
		$configFile = APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
		require $configFile;
	
		$course_name = isset($_REQUEST['course_name']) ? $_REQUEST['course_name'] : 'Full Time MBA/PGDM';
		/*$category_id = isset($_REQUEST['categoryId']) ? $_REQUEST['categoryId'] : 3;
		$course_id = isset($_REQUEST['courseId']) ? $_REQUEST['courseId'] : 2;*/
		
		$data['validateuser'] = $this->userStatus;
		$data['headerTabs'] = $this->userStatus[0]['headerTabs'];

		//Made initialization from function calling
		$data['searchTabs'] = $this->getCtab_name();

		$data['prodId'] = 31;
		$Validity = $this->checkUserValidation();
		$clientId = $Validity[0]['userid'];
		unset($Validity);
		
		$appId = 1;
		$URL='/searchAgents/searchAgents/openUpdateSearchAgent/@start@/@count@/'.$deliveryMethod.'/'.$type.'/'.$searchCriteria;
		
		if($deliveryMethod == 'porting'){
			$type = 'porting';
		}

		$this->load->model('search_agent_main_model');
		$saMainModel = new search_agent_main_model;

		switch($type){
			case 'deactive':
				$search_agents_array = $saMainModel->getDeactivatedGenies($clientId,$deliveryMethod);
				$data['search_agents_array'] = $search_agents_array;
				// Gets the data of the search agent
				$search_agents_all_array = $saMainModel->SearchAgentsAllDetails('',$clientId,$start,$count,$deliveryMethod, 'deactivated', $searchCriteria);
				$genieType = 'deactive';

			break;

			case 'deleted':
				$search_agents_array = $saMainModel->getDeletedGenies($clientId,$deliveryMethod);
				$data['search_agents_array'] = $search_agents_array;
				//	Gets the data of the search agent
				$search_agents_all_array = $saMainModel->SearchAgentsAllDetails('',$clientId,$start,$count,$deliveryMethod, 'deleted', $searchCriteria);

				$genieType = 'deleted';
			break;

			case 'porting':
				if($search_agents_all_array['totalRows']<=$start && $search_agents_all_array['totalRows']>=10){
					$start=$start-$count;
					$search_agents_all_array = $saMainModel->SearchAgentsAllDetails('',$appId,$clientId,$start,$count,$deliveryMethod);
				}

				$this->load->library('SearchAgents_client');
				$categoryClient = new SearchAgents_client();
				$search_agents_array = $categoryClient->getAllSearchAgents($appId,$clientId,$deliveryMethod);
				$data['search_agents_array'] = $search_agents_array;
				$search_agents_all_array = $categoryClient->SearchAgentsAllDetails($appId,$clientId,$start,$count,$deliveryMethod);
			break;

			default:

				$search_agents_array = $saMainModel->getActivatedGenies($clientId,$deliveryMethod);
				$data['search_agents_array'] = $search_agents_array;
				// Gets the data of the search agent
				$search_agents_all_array = $saMainModel->SearchAgentsAllDetails('',$clientId,$start,$count,$deliveryMethod, 'activated', $searchCriteria);
				$genieType = 'activated';
			break;
		}

		unset($search_agents_array);
		if($search_agents_all_array['totalRows']<=$start && $search_agents_all_array['totalRows']>=10){
			$start=$start-$count;
			$search_agents_all_array = $saMainModel->SearchAgentsAllDetails('',$appId,$clientId,$start,$count,$deliveryMethod);
		}

		$leftOverData = (array)$search_agents_all_array['query'];
		$leftOverData = $leftOverData['result_array'];
		$leftOverLeadStatus = $this->getLeftOverLeadStatus($leftOverData);

		/* Pagination Code start here */
        $paginationHTML = doPagination($search_agents_all_array['totalRows'],$URL,$start,$count,4);
        
        $data['paginationHTML'] = $paginationHTML;
		$data['start']=$start;
		$data['count']=$count;

		$data['genieType'] = $genieType;

	    /* Pagination Code Ends here */
		$search_agents_all_display_array=array();
		$testdata = array();
		
		$searchAgentIds = array();
		foreach($data['search_agents_array'] as $temp){
			$searchAgentIds[] = $temp[0];
		}

		$data['allocatedLeadsCountForGenie'] = $saMainModel->getAllocatedLeadCountForGenie($searchAgentIds);
		$search_agents_all_display_array= $saMainModel->getAllSADisplayData($appId,$searchAgentIds); 
		
		$activeUserFlagForSA = $saMainModel->getActiveUserFlagForSA($searchAgentIds);
		$activeUserFlagForSA = array_column($activeUserFlagForSA,'includeActiveUsers','searchagentid');
		unset($searchAgentIds);

		$this->load->library('SearchAgents_client');
		$categoryClient = new SearchAgents_client();

		if($deliveryMethod == 'normal') {
			$portingAgents = $categoryClient->getAllSearchAgents($appId,$clientId,'porting');
			if(count($portingAgents) > 0) {
				$data['hasPortingAgents'] = TRUE;
			}
			else {
				$data['hasPortingAgents'] = FALSE;
			}
		}
		unset($categoryClient);

		if(array_key_exists($_REQUEST['course_name'],$coursesList) || array_key_exists($course_name,$coursesList)){
			$data['flag_matched_responses'] = 'true';
		}
		else {
			$data['flag_matched_responses'] = 'false';
		}
		
		$data['search_agents_all_display_array']=$search_agents_all_display_array;
		$data['search_agents_all_array'] = $search_agents_all_array;
		$data['activeUserMapping'] = $activeUserFlagForSA;
		unset($search_agents_all_display_array);
		unset($search_agents_all_array);

		$cmsUserInfo 			= $this->cmsUserValidation(); 
		$data['myProducts'] 	=$cmsUserInfo['myProducts']; 
		$data['usergroup'] 		= 'enterprise';
		$data['deliveryMethod'] = $deliveryMethod; 
		$data['searchCriteria'] = $searchCriteria;
		$data['leftOver']		= $leftOverLeadStatus;
		
		unset($cmsUserInfo);

		echo $this->load->view("searchAgents/updateSearchAgent",$data);
	}

	function getAllDataSearchAgents()
	{
		$this->init();
		$Validity = $this->checkUserValidation();
		$clientId = $Validity[0]['userid'];
		$categoryClient = new SearchAgents_client();
		$appId = 1;
                $arr = $categoryClient->getAllDataSearchAgents($appId,$clientId);
		return $arr;
	}

	public function SADeliveryOption($emailFreq){
		$this->validateCron();
		$this->load->library('SearchAgents_client');
		$SAClientObj = new SearchAgents_client();
		$SAClientObj->SADeliveryOptions($appId=1,$emailFreq);
        }

	public function SAAutoResponder(){
		error_log("inside SAAutoResponder..........");
                $this->load->library('SearchAgents_client');
                $SAClientObj = new SearchAgents_client();
                $SAClientObj->SAAutoResponder($appId=1);
        }

		function displayStaticContentSAform($html_type='form',$searchAgentId,$groupId)
        {
                $data = array();
                $this->init();
                $this->load->library('SearchAgents_client');
                $SAClientObj = new SearchAgents_client();
                $appId = 1;
				$Validity = $this->checkUserValidation();
                $clientId = $Validity[0]['userid'];
                $res = $SAClientObj->SearchAgentDetail($appId,$searchAgentId);
                $data['res']=$res;
                $data['Search_Agent_Update_flag']=true;
                $data['Search_Agent_Form_Url']='/searchAgents/searchAgents/updateSearchAgent/'.$searchAgentId;

                if($groupId>0){
                	$data['isAbroadAgent'] =true;
                }else{
                	$data['isAbroadAgent'] =false;
                }
                $html_type='form';
                if ($html_type == 'form')
                {
                	
                	// $this->init();
                	$ldbObj = new LDB_client();
                	$data['credit_consumption'] = $ldbObj->getCreditConsumedByGroup($groupId);
                	echo $this->load->view("searchAgents/searchAgentForm",$data);
                }
                else
                {
                        echo $this->load->view("searchAgents/search_agent_edit_email_overlay",$data);
                }
        }

	function updateSearchAgent($sa_id)
	{
		$Validity = $this->checkUserValidation();
                $clientId = $Validity[0]['userid'];
		$finalArray = array();
			$search_agent_name = $this->input->post('searchagent_name',TRUE);
	                $finalArray['searchagentName'] = $search_agent_name;

	        if(!is_numeric($sa_id)){
	        	return false;
	        }

			$finalArray['searchagentid'] = $sa_id;
			$finalArray['clientid'] = $clientId;
			$pref_lead_type = $this->input->post('lead_type',TRUE);
			$group_id = $this->input->post('group_id',TRUE);
			$finalArray['credit_group'] = $group_id;
			$type = $this->input->post('type',TRUE);
			$finalArray['type'] = $type;
			if ($pref_lead_type == 'shared')
			{
				$finalArray['pref_lead_type'] =  'shared';
			}
			if ($pref_lead_type == 'premium')
			{
				$finalArray['pref_lead_type'] =  'premium';
			}

			$flag_auto_download = $this->input->post('checkbox_flag_auto_download',TRUE);
			if ($flag_auto_download == 'on')
			{
				$finalArray['leads_daily_limit']  = $this->input->post('leadsperday',TRUE);
				$finalArray['price_per_lead'] = $this->input->post('set_price_per_lead',TRUE);
				$email_freq = $this->input->post('send_lead_option',TRUE);
				if ($email_freq == 'asap')
				{
					$finalArray['email_freq'] = 'asap';
				}
				if ($email_freq == 'everyhour')
				{
					$finalArray['email_freq'] = 'everyhour';
				}
				$sms_freq = $this->input->post('send_lead_option_sms',TRUE);
                                if ($sms_freq == 'asap')
                                {
                                        $finalArray['sms_freq'] = 'asap';
                                }
                                if ($sms_freq == 'everyhour')
                                {
                                        $finalArray['sms_freq'] = 'everyhour';
                                }
				$finalArray['is_active'] = 'live';
			}
			$checkbox_auto_responder = $this->input->post('checkbox_auto_responder',TRUE);
			if ($checkbox_auto_responder == 'on')
			{
				$autoResponder_email = array();
				$email_behalf = $this->input->post('emailmybehalf',TRUE);
				if ($email_behalf == 'on')
				{
					$from_name = $this->input->post('from_name',TRUE);
                                        if (!empty($from_name))
                                        {
                                                $autoResponder_email['from_name'] = $from_name;
                                        }
                                        $from_emailid = $this->input->post('from_emailid',TRUE);
                                        if (!empty($from_emailid))
                                        {
                                                $autoResponder_email['from_emailid'] = $from_emailid;
                                        }
					$subject = $this->input->post('subjectforautoresponder',TRUE);
					if (!empty($subject))
					{
						$autoResponder_email['subject'] = $subject;
					}
					$msg = $this->input->post('auto_responder_email_textarea_msg',TRUE);
					if (!empty($msg))
					{
						$autoResponder_email['msg'] = $msg;
					}
					$daily_limit = $this->input->post('daily_limt_auto_email',TRUE);
					if (!empty($daily_limit))
					{
						$autoResponder_email['daily_limit'] = $daily_limit;
					}
					$autoResponder_email['is_active'] = 'live';
				}

				$autoResponder_sms = array();
				$checkbox_sendsms = $this->input->post('checkbox_sendsms',TRUE);
				if ($checkbox_sendsms == 'on')
				{
					$msg = $this->input->post('auto_responder_sms_textarea_msg',TRUE);
					if (!empty($msg))
					{
						$autoResponder_sms['msg'] = $msg;
					}
					$daily_limit = $this->input->post('daily_limt_auto_sms',TRUE);
					if (!empty($daily_limit))
					{
						$autoResponder_sms['daily_limit'] = $daily_limit;
					}
					$autoResponder_sms['is_active'] = 'live';
				}
			}
			if ($flag_auto_download == 'on')
			{
				$contactDetails = array();
				$email_ids = $this->input->post('auto_download_email',TRUE);
				$mobile_nos = $this->input->post('mobileno',TRUE);
				if (isset($email_ids) && is_array($email_ids))
				{
					$contactDetails['email_ids'] =  array_unique($this->remove_array_empty_values($email_ids));
				}
				if (isset($mobile_nos) && is_array($mobile_nos))
				{
					$contactDetails['mobile_nos'] =  array_unique($this->remove_array_empty_values($mobile_nos));
				}
			}
			if ($flag_auto_download == 'on') {
				$finalArray['flag_auto_download'] = 'live';
			} else {
				$finalArray['flag_auto_download'] = NULL;
			}
			if ($email_behalf == 'on')
			{
				$finalArray['flag_auto_responder_email'] = 'live';
			} else {
				$finalArray['flag_auto_responder_email'] = NULL;
			}
			if ($checkbox_sendsms == 'on')
			{
				$finalArray['flag_auto_responder_sms'] = 'live';
			} else {
				$finalArray['flag_auto_responder_sms'] = NULL;
			}
			$data = array('search_agent'=>$finalArray,'auto_responder_email'=>$autoResponder_email,'auto_responder_sms'=>$autoResponder_sms,'contact_details'=>$contactDetails);
			$this->load->library('SearchAgents_client');
			$SAClientObj = new SearchAgents_client();
			$appId = 1;
			//echo "<pre>";print_r($data);echo "</pre>";exit;
			$arr = $SAClientObj->UpdateSearchAgent($appId,$sa_id,$data);
			echo json_encode($arr);

			modules::run('searchAgents/searchAgents_Server/addSearchAgentToQueue', $sa_id);
	}

	function displayOverlay($str)
	{
		$data = array();
		$data['str'] = $str;
		echo $this->load->view("searchAgents/cms_login",$data);
	}

	function validatecmsAdminLogin()
	{
		$this->init();
                $this->load->library('SearchAgents_client');
		$password = sha256($_POST['password']);
		$email = $_POST['email'];
		$SAClientObj = new SearchAgents_client();
		$appId = 1;
		echo $SAClientObj->validatecmsAdminLogin($appId,$password,$email);
	}
	function testUpdateSearchAgentField($appId="1",$sa_id,$fieldName,$value,$actiontype="update",$fieldtype="string")
	{
		$this->init();

                $this->load->library('SearchAgents_client');

		$SAClientObj = new SearchAgents_client();

		$Validity = $this->checkUserValidation();
                $clientId = $Validity[0]['userid'];

		if ( $value == 'live')
		{
			$status = 'history';
		}
		if ( $value == 'history')
		{
			$status = 'live';
		}
		if ( $value == 'asap')
                {
                        $status = 'everyhour';
                }
                if ( $value == 'everyhour')
                {
                        $status = 'asap';
                }

		if ($fieldName == 'is_active')
		{
			$result = $SAClientObj->changeStatusSearchAgent($appId,$sa_id,$status);
		}
		else if ($fieldName == 'flag_auto_download')
		{
			$result = $SAClientObj->changeStatusAutoDownload($appId,$sa_id,$status);
			$this->emptyGenieLeftOverStatus($sa_id);
		}
		else if ($fieldName == 'flag_auto_responder_email')
		{
			$result = $SAClientObj->changeStatusAutoResponderEmail($appId,$sa_id,$status);
		}
		else if ($fieldName == 'flag_auto_responder_sms')
		{
			$result = $SAClientObj->changeStatusAutoResponderSMS($appId,$sa_id,$status);

		}else if($fieldName=='email_freq'){

			$result = $SAClientObj->changeEmailFrequencySearchAgent($appId,$sa_id,$status);
			echo json_encode($result['status']);
		}else if($fieldName=='sms_freq'){
                        $result = $SAClientObj->changeSmsFrequencySearchAgent($appId,$sa_id,$status);
                        echo json_encode($result['status']);
                }
		if ($result['status'] == 'true')
		{
			echo json_encode($status);
		}
		elseif($result['status'] == 'false')
		{
			echo json_encode($status);
		}
		
		modules::run('searchAgents/searchAgents_Server/addSearchAgentToQueue', $sa_id);
	}

	function testValidateAgentName()
	{
		$this->init();
		$this->load->library('SearchAgents_client');
		$SAClientObj = new SearchAgents_client();
		$appId = 1;
		$searchagentName = 'testbydadsasdsadsadsadasdasdsdravi';
		$Validity = $this->checkUserValidation();
		$clientId = $Validity[0]['userid'];
		echo $SAClientObj->validateSearchAgentName($appId,$clientId,$searchagentName);
	}

	function testGetSearchAgent()
	{
		$this->init();
		$this->load->library('SearchAgents_client');
		$SAClientObj = new SearchAgents_client();
		$appId = 1;
		$Validity = $this->checkUserValidation();
		$clientId = $Validity[0]['userid'];
		$res = $SAClientObj->getSearchAgent($appId,$clientId,'18');
		dump($res);
	}

	function testgetAllCredits($clientId)
	{

		$this->load->library('sums_product_client');
		$objSumsProduct =  new Sums_Product_client();
		$SubscriptionArray = $objSumsProduct->getAllSubscriptionsForUserLDB(1,array('userId'=>$clientId));
		sdump($SubscriptionArray);
	}

	function testgetCreditConsumed()
	{
		$this->init();
		$this->load->library('SearchAgents_client');
		$SAClientObj = new SearchAgents_client();
		$appId = 1;
		$clientId = '1029';
		$userid = '519610';
		$credit = '30';
		sdump($SAClientObj->getCreditConsumed($appId,$clientId,$userid,$credit,'view','true'));
	}

	function testfilterDefaulterSearchAgents()
	{
		$this->init();
		$this->load->library('SearchAgents_client');
		$SAClientObj = new SearchAgents_client();
		$appId = 1;
		$searchAgentIds = array('10','11','12','13','14','15');
		sdump($SAClientObj->filterDefaulterSearchAgents($appId,$searchAgentIds));
	}

	function testgetAllCreditToConsumedDataForSearchAgents()
	{
		$this->init();
		$this->load->library('SearchAgents_client');
		$SAClientObj = new SearchAgents_client();
		$appId = 1;
		$leadid = '519610';
		$searchAgentIds = array('20','21','22','12','11','18');
		sdump($SAClientObj->getAllCreditToConsumedDataForSearchAgents($appId,$searchAgentIds,$leadid,'true'));
	}
	function testchangeStatusAutoDownload()
	{
		$this->init();
		$this->load->library('SearchAgents_client');
		$SAClientObj = new SearchAgents_client();
		$appId = 1;
		sdump($SAClientObj->changeStatusAutoDownload($appId,'20','live'));
	}
	function testchangeStatusSearchAgent()
	{
		$this->init();
		$this->load->library('SearchAgents_client');
		$SAClientObj = new SearchAgents_client();
		$appId = 1;
		print_r($SAClientObj->changeStatusSearchAgent($appId,'147','live'));
	}
	function testModal()
	{
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle();
		if(!$dbHandle){
			log_message('error','Can not create db handle');
		}
		$this->init();
		$this->load->library('SearchAgents_client');
		$SAClientObj = new SearchAgents_client();
		sdump($SAClientObj->SearchAgentsAllDetails(1,1029,0,13));
	}
	function getuserData($id)
	{
		$this->load->library('LDB_Client');
		$ldbClientObj = new LDB_Client();
		sdump(json_decode($ldbClientObj->sgetUserDetails(1,$id),true));
	}

	function markGenieWithFullQuota(){
		$this->validateCron();
		$this->load->model('search_agent_main_model');
		$saModel = new Search_agent_main_model;

		$saModel->markAllGenieQuotaHistory();
		
		$genieIds = $saModel->getGenieWithFullQuota();

		if(count($genieIds)>0){
			foreach ($genieIds as $genieId) {
				$saModel->markGenieWithFullQuota($genieId);
			}
		}

	}

	function emptyGenieLeftOverStatus($sa_id){
		if($sa_id<1){
			return;
		}

		$this->load->model('search_agent_main_model');
		$saModel = new Search_agent_main_model;

		$saModel->emptyGenieLeftOverStatus($sa_id);

		return;
	}

	function addDailyPortingNumber()
	{
		$sa_id = $this->input->post('sa_id');
		$daily_limit = $this->input->post('daily_limit');
		$saModel = $this->load->model('search_agent_main_model');
		if($saModel->addDailyPortingData($sa_id,$daily_limit))
		{
			echo "successfully saved";
		}
		else
		{
			echo "error in updating value";
		}
	}

	/*function to add new recommendation courses for new MR genies to Redis*/
	function addNewGenieMRCoursesInCache(){
		$this->validateCron();
		$sa_model 			 = $this->load->model('search_agent_main_model');
		$search_agents_array = $sa_model->getNewSearchAgents();

		$this->redisLib = PredisLibrary::getInstance();
				
		foreach ($search_agents_array as $agent_id) {
			$client_course_ids = $sa_model->getClientMRCourses($agent_id['searchagentid']);

			$client_course_ids['course_ids'] = explode(',', $client_course_ids['course_ids']);
			
			$collab_also_viewed_courses = $sa_model->getCollabAlsoViewedCourses($client_course_ids['course_ids']);
			$redis_data = array();

			foreach ($collab_also_viewed_courses as $data) {
				$redis_data_set = json_encode(array('sa_id'=>$agent_id['searchagentid'],'c_id'=>$data['course_id']));
				$redis_key 	  = 'sa_mr_courses_'.$data['recommended_course_id'];
				$response = $this->redisLib->addMembersToSet($redis_key,array($redis_data_set));
			}
			
			/*$set_data = $this->redisLib->getMembersOfSet($redis_key);*/

			$sa_model->storeClientMRCourses($collab_also_viewed_courses);

		}

		$this->removeDeletedCoursesFromMRCache();
	}

	/*function to add new recommendation courses from collaborative db table to Redis*/
	function addAllNewMRCoursesInCache(){
		$this->validateCron();
		$sa_model 			 = $this->load->model('search_agent_main_model');
		$all_mr_course_data  = $sa_model->getAllMRSearchAgent();
		$today = date('Y-m-d').' 00:00:00';
		$this->redisLib = PredisLibrary::getInstance();
		
		foreach ($all_mr_course_data as $row) {
					
			$sub_result = $sa_model->getRecentMRAlsoViewed($row['courseid'],$today);
		
			if(count($sub_result) <1){
				continue;
			}

			foreach ($sub_result as $data) {
				$redis_data_set = json_encode(array('sa_id'=>$row['searchagentid'],'c_id'=>$row['courseid']));
				$redis_key 	  = 'sa_mr_courses_'.$data['recommended_course_id'];
				$response = $this->redisLib->addMembersToSet($redis_key,array($redis_data_set));
			}

			$sa_model->storeClientMRCourses($sub_result);
		}

	}

	/*Function to remove deleted courses and genies cache from redis*/
	private function removeDeletedCoursesFromMRCache(){
		$sa_model 			 = $this->load->model('search_agent_main_model');
		$this->redisLib = PredisLibrary::getInstance();

		$today = date('Y-m-d H:i:s');
		$yesterday = date('Y-m-d',strtotime('-1 day')).' 00:00:00';
		

		$deleted_courses = $sa_model->getMRDeletedCourses($yesterday, $today);
		$deleted_mr_genies = $sa_model->getMRDeletedGenies($yesterday, $today);
		$merged_deleted_array = array_merge($deleted_courses,$deleted_mr_genies);
		
		$remove_keys_map = array();

		foreach ($deleted_mr_genies as $courses) {
			$recommended_course_id = $sa_model->getCollabAlsoViewedCourses($courses['course_id']);
			foreach ($recommended_course_id as $reco_id) {
				$redis_data_set = json_encode(array('sa_id'=>$courses['searchagentid'],'c_id'=>$courses['course_id']));
				$redis_key 	  = 'sa_mr_courses_'.$reco_id['recommended_course_id'];

				$remove_keys_map[$redis_key][] = $redis_data_set;
			}

		}
		
		foreach ($remove_keys_map as $redis_key => $redis_value) {
			$response = $this->redisLib->removeMembersOfSet($redis_key,$redis_value);
		}

	}

	private function getLeftOverLeadStatus($search_agents_all_array){
		foreach ($search_agents_all_array as $saArray) {
			$allSearchAgents[] = $saArray['searchagentid'];
		}

		if(count($allSearchAgents)<1){
			return;
		}

		$this->load->model('search_agent_main_model');
		$saMainModel = new search_agent_main_model;

		$leftOverCount = $saMainModel->getLeftOverLeadStatus($allSearchAgents);
		
		foreach ($leftOverCount as $leftOver) {
			$returnLeftOver[$leftOver['searchagentid']] = $leftOver['leftover'];
		}
		return $returnLeftOver;
	}

	public function resetLeftOverStatus($searchAgentId){
		$this->init();

		$this->load->model('search_agent_main_model');
		$saMainModel = new search_agent_main_model;		

		if($searchAgentId ==''){
			$searchAgentId = $this->input->post('sa_id');
		}

		if($searchAgentId<=0){
			echo 'Failure';
			return;
		}


		$leftOverCount = $saMainModel->getLeftOverLeadStatus(array($searchAgentId));
		$leftOverCount = $leftOverCount[0]['leftover'];

		$saMainModel->resetLeftOverStatus($searchAgentId);

		$this->trackGenieReset($searchAgentId, $leftOverCount);
		
		echo 'success';
		return;
    }

    private function trackGenieReset($searchAgentId, $leftOverCount){
    	$loggedInUserData = $this->userStatus;
		$loggedInDetails = explode('|', $loggedInUserData[0]['cookiestr']);
    	$clientEmail = $loggedInDetails[0];
	

		$data = array();
    	$data['product'] 				= 'ResetGenie';
    	$data['page_tab'] 				= 'ManageGenie';
    	$data['cta'] 					= 'ResetGenie';
    	$data['entity_id'] 				= $searchAgentId;
    	$data['search_criteria'] 		= json_encode(array('searchAgentId'=>$searchAgentId,'leftOverCount'=>$leftOverCount));
    	$data['account_used_by'] 		= 'Client';
    	$data['account_used_by_email'] 	= $clientEmail;
    	$data['client_email'] 			= $clientEmail;
    	$data['IP_address']           	= S_REMOTE_ADDR;

    	
    	$model = $this->load->model('enterprise/enterprisedatatrackingmodel');
    	$model->saveEnterpriseTrackingData($data);
    }

}

/* End of file searchAgents.php */
/* Location: ./system/application/controllers/searchAgents/searchAgents.php */

?>


