<?php

/**
 *  Class
 *
 * @author
 * @package SMART
 *
 */

class SmartMis extends MX_Controller
{
	private function _validateUser() {
		$this->_validateuser = $this->cmsUserValidation();
	}

	private function _loadHeaderContent($tabId) {
		$headerComponents = array(
				'css' => array('fixedTableHeader','headerCms','raised_all','mainStyle','footer','cal_style','lms_porting','smart','common_new'),
				'js' => array('homepage_redesign','common','header','enterprise','home','CalendarPopup','discussion','events','listing','blog','smart'),
				'displayname' => (isset($this->_validateuser['validity'][0]['displayname'])?$this->_validateuser['validity'][0]['displayname']:""),
				'prodId' => $tabId,
				'title' => "SMART Interface"
				);
		$headerComponents['headerTabs'] = $this->_validateuser['headerTabs'];
		$this->load->library('OnlineFormEnterprise_client');
		$onlineFormObj = new OnlineFormEnterprise_client();
		$headerComponents['showOnlineFormEnterpriseTab'] = $onlineFormObj->checkOnlineFormEnterpriseTabStatus($this->_validateuser['userid']);
		$headerComponents['validateuser'] = $this->_validateuser['validity'];
		$headerCMSHTML = $this->load->view('enterprise/headerCMS', $headerComponents,true);
		$headerTABSHTML = $this->load->view('enterprise/cmsTabs',$headerComponents,true);
		return array($headerCMSHTML,$headerTABSHTML);
	}

	private function _loadfooterContent() {
		$footer = array();
		$footer[] = $this->load->view('common/leanFooter',array(),true);
		$footer[] = $this->load->view('common/calendardiv',array(),true);
		
		$data = array();
		$data['userId'] = $this->_validateuser['userid'];
		$data['salesUser'] = $this->salesUser;
		$data['executiveHierarchy'] = $this->executiveHierarchy;
		$data['executiveClientMapping'] = $this->executiveClientMapping;
		$data['clientInstituteMapping'] = $this->clientInstituteMapping;
		$data['institutePaid'] = $this->institutePaid;
		$data['clientLeadGenieMapping'] = $this->clientLeadGenieMapping;
		$data['clientLeadPortingMapping'] = $this->clientLeadPortingMapping;
		$data['clientResponsePortingMapping'] = $this->clientResponsePortingMapping;		

		$data['names'] = $this->executiveNames + $this->clientNames;
		$footer[] = $this->load->view('smart/UserMappingJSData',$data,true);
		return $footer;
	}

	private function _getUserMappings()
	{
		$userId = $this->_validateuser['userid'];
		$smartModel = $this->load->model('smartmodel');
		$this->salesUser = $smartModel->getSalesUserType($userId);
		$this->isNewUser = false;
		$this->executiveNames = array();
		$this->clientNames = array();
		$this->executiveHierarchy = array();
		$this->executiveClientMapping = array();
		$this->clientInstituteMapping = array();
		$this->instituteList = array();
		$this->institutePaid = array();
		$this->clientLeadGenieMapping = array();
		$this->clientLeadPortingMapping = array();
		$this->clientResponsePortingMapping = array();
		
		if($smartModel->canUserAccessSmartInterface($userId)) {
			$this->executiveHierarchy = $smartModel->getSubordinatesForExecutive(array($userId), False);
			$executives = $smartModel->getSubordinatesForExecutive(array($userId), True);
			array_push($executives, $userId);
			// $this->executiveClientMapping = $smartModel->getClientsForSalesPerson($executives, False);
			// $clients = $smartModel->getClientsForSalesPerson($executives, True);
			$clientsSalesMapping = array();
			$clientsSalesMapping = $smartModel->getClientsForSalesPerson($executives);
			$this->executiveClientMapping = $clientsSalesMapping['executiveClientMapping'];
			$clients = $clientsSalesMapping['clientsArray'];
			unset($clientsSalesMapping);
			$this->executiveNames = $smartModel->getUserName($executives);
			$this->userTitle = $this->executiveNames[$userId];
		}
		else {
			$clients = array($userId);
		}
		
		$clientNames = $smartModel->getEnterpriseClientDisplayname($clients);
		foreach ($clientNames as $clientId => $clientDisplayname) {
			if(empty($clientDisplayname)) {
				$userNames[] = $clientId;
			}
		}
		
		if(!empty($userNames)) {
			$clientDisplaynames = $smartModel->getUserName($userNames);
			$clientNames = $clientDisplaynames + $clientNames;
		}
		
		$this->clientNames = $clientNames;
		
		if(empty($this->userTitle)) {
			$this->userTitle = $this->clientNames[$userId];
		}
		
		$this->load->model('listing/institutefindermodel');
		
		if($_COOKIE['SMARTInterfaceMode'] == 'Abroad') {
			// $this->clientInstituteMapping = $this->institutefindermodel->getActiveAndDeletedUniversitiesForOwners($clients, '', False);
			// $this->instituteList = $this->institutefindermodel->getActiveAndDeletedUniversitiesForOwners($clients, '', True);

			$ownersUniversitiesArray 	  = array();
			$ownersUniversitiesArray 	  = $this->institutefindermodel->getActiveAndDeletedUniversitiesForOwners($clients);
			$this->clientInstituteMapping = $ownersUniversitiesArray['clientUniversityMapping'];
			$this->instituteList 		  = $ownersUniversitiesArray['universityList'];
			unset($ownersUniversitiesArray);
			
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$universityRepository = $listingBuilder->getUniversityRepository();
			$instituteRepository = $listingBuilder->getInstituteRepository();
			
			if(!empty($this->instituteList)) {	
				
				$universities = $universityRepository->findMultiple($this->instituteList);
				$courses = $universityRepository->getCoursesOfUniversities($this->instituteList, 'PAID');
				
				$instituteLocationArray = array();
				
				foreach($universities as $university) {
					if(!empty($courses[$university->getId()])){
						$this->institutePaid[$university->getId()] = true;
					}
					
					//$flagShipCourse = $instituteRepository->getFlagshipCourseOfInstitute($this->instituteList[0]);
					
					$universityCourseList = explode(',',$courses[$university->getId()]['courseList']);
					$flagShipCourse = $universityCourseList[0];
					
					//$instituteLocation = $institute->getLocation()->getCity()->getName();
					//$instituteLocationArray[$institute->getId()] = $instituteLocation;
				}
	
				$this->CategoryId = $instituteRepository->getMainCategoryIdsOfListing($flagShipCourse,'course');
			}
					
			//$this->appendInstituteLocations($instituteLocationArray);
			
			$this->clientLeadGenieMapping = $smartModel->getLeadGenieForClient($clients, False, False);
			
			$this->clientLeadPortingMapping = $smartModel->getLeadPortingsForClient($clients, False, False);
			
			$this->executiveHierarchy = $this->_sortArray($this->executiveHierarchy);
			
			uksort($this->executiveClientMapping, array($this, 'compareNames'));
			foreach($this->executiveClientMapping as $executive => $client) {
				usort($client, array($this, 'compareNames'));
				$this->executiveClientMapping[$executive] = $client;
			}
			
			uksort($this->clientInstituteMapping, array($this, 'compareNames'));
			foreach($this->clientInstituteMapping as $client => $institute) {
				asort($institute, SORT_STRING | SORT_FLAG_CASE);
				$this->clientInstituteMapping[$client] = $institute;
			}
			
			if(empty($this->clientInstituteMapping)) {
				$this->isNewUser = true;
			}			
		}
		else {
			// $this->clientInstituteMapping = $this->institutefindermodel->getActiveAndDeletedInstitutesForOwners($clients, '', False,TRUE);
			// $this->instituteList = $this->institutefindermodel->getActiveAndDeletedInstitutesForOwners($clients, '', True);

			$ownersInstitutesArray = array();
			$ownersInstitutesArray = $this->institutefindermodel->getActiveAndDeletedInstitutesForOwners($clients);
			$this->clientInstituteMapping = $ownersInstitutesArray['clientInstituteMapping'];
			$this->instituteList = $ownersInstitutesArray['instituteList'];
			unset($ownersInstitutesArray);
			
			if(!empty($this->instituteList)) {	
				$this->load->builder("nationalInstitute/InstituteBuilder");
				$listingBuilder = new InstituteBuilder;
				$instituteRepository = $listingBuilder->getInstituteRepository();
				
				// removing this code-Redis cache optimization
				// $institutes = $instituteRepository->findMultiple($this->instituteList);

				$courses = $instituteRepository->getCoursesListForInstitutes($this->instituteList);
				
				$final_courses = array();				
				foreach($courses as $key=>$val) {
					foreach($val as $final_value) {
						$final_courses[] = $final_value;
					}	
				}
				unset($courses);
				if(count($final_courses) >0) {
					$this->load->builder("nationalCourse/CourseBuilder");
					$courseBuilder = new CourseBuilder();			
					$courseRepository = $courseBuilder->getCourseRepository();    	

					$finalCoursesChunk = array();
					$finalCoursesChunk = array_chunk($final_courses, 500);

					foreach ($finalCoursesChunk as $courseArray) {
								
						if(count($courseArray)>0)	{

					 		$paid_institutes = $this->getPaidInstitutes($courseArray,$paid_institutes);
						
							}

						/* below lines taking heavy cache
						
						$courseRepository->disableCaching();
						$coursesObjcs = $courseRepository->findMultiple($courseArray);
						
						foreach($coursesObjcs as $course_obj) {
							if($course_obj->isPaid()) {
								$paid_institutes[] = $course_obj->getInstituteId();	
							}	
						}*/

					}
				}
				$instituteLocationArray = array();
				$instituteLocationArray = $this->getInstituteCityById($this->instituteList);

				foreach($this->instituteList as $institute) {

					if (in_array($institute, $paid_institutes))
					{
						$this->institutePaid[$institute] = true;
					}
				}
	
				//$this->CategoryId = $instituteRepository->getMainCategoryIdsOfListing($flagShipCourse,'course');
			}
					
			unset($paid_institutes);

			$this->appendInstituteLocations($instituteLocationArray);
			
			$this->clientLeadGenieMapping = $smartModel->getLeadGenieForClient($clients, False, False);
			
			$this->clientLeadPortingMapping = $smartModel->getLeadPortingsForClient($clients, False, False);

			//change
			$this->clientResponsePortingMapping = $smartModel->getResponsePortingsForClient($clients, False, False);

			$this->executiveHierarchy = $this->_sortArray($this->executiveHierarchy);
			
			uksort($this->executiveClientMapping, array($this, 'compareNames'));
			foreach($this->executiveClientMapping as $executive => $client) {
				usort($client, array($this, 'compareNames'));
				$this->executiveClientMapping[$executive] = $client;
			}
			
			uksort($this->clientInstituteMapping, array($this, 'compareNames'));
			foreach($this->clientInstituteMapping as $client => $institute) {
				asort($institute, SORT_STRING | SORT_FLAG_CASE);
				$this->clientInstituteMapping[$client] = $institute;
			}
			
			if(empty($this->clientInstituteMapping)) {
				$this->isNewUser = true;
			}	
		}
	}

    private function getInstituteCityById($institute_id)
    {
    	$smartModel = $this->load->model('smartmodel');
		$getCityResult = $smartModel->getInstituteCityById($institute_id);
		$returnArray = array();
		foreach ($getCityResult as $result) {
				$returnArray[$result["listing_id"]] = $result["city_name"];
		}
		return $returnArray;
    }

    private function getPaidInstitutes($courseArray,$paid_institutes)
	{
		$smartModel = $this->load->model('smartmodel');
		$paid_institute_result = $smartModel->getPaidInstitutes($courseArray);
		foreach ($paid_institute_result as $result) {
				
				array_push($paid_institutes,$result['primary_id']);
		}
		return $paid_institutes;
	}

    private function compareNames($id1, $id2) {
		$name1 = empty($this->executiveNames[$id1]) ? $this->clientNames[$id1] : $this->executiveNames[$id1];
		$name2 = empty($this->executiveNames[$id2]) ? $this->clientNames[$id2] : $this->executiveNames[$id2];
		return strcasecmp($name1, $name2);
	}
	
	private function _sortArray($hierarchy) {
		if(empty($hierarchy)){
			return array();
		}
		else {
			foreach($hierarchy as $key => $value) {
				uksort($value, array($this, 'compareNames'));
				$hierarchy[$key] = $value;
				$hierarchy[$key] = $this->_sortArray($value);
			}
			return $hierarchy;
		}
	}
	
	private function appendInstituteLocations($instituteLocationArray) {
		foreach($this->clientInstituteMapping as $clientId=>$institutes){
			foreach($institutes as $institute_id=>$instituteName){
				if(!empty($instituteLocationArray[$institute_id])){
					$this->clientInstituteMapping[$clientId][$institute_id] = $instituteName.", ".$instituteLocationArray[$institute_id];
				}
			}
		}
	}
	
	private function _displayHierarchy($hierarchy, $manager, $widgetType, $level) {
		$margin = 20 * $level;
		$text = '';
		foreach($hierarchy as $executive=>$subordinates) {
			if(empty($subordinates)) {
				$text .= '<li style="padding-left:'.$margin.'px;"><input type="checkbox" name="executive_id[]" onClick="uncheckElement(this, \''.$widgetType.'_parent-'.$manager.'-checker\', \''.$widgetType.'_parent-'.$manager.'-holder\');populateClientList(\''.$widgetType.'\');" value="'.$executive.'" />'.$this->executiveNames[$executive].'</li>';
			}
			else {
				$text .= '<ol id="'.$widgetType.'_parent-'.$executive.'-holder">';
				$text .= '<li style="padding-left:'.$margin.'px;"><input type="checkbox" parent="'.$manager.'" id="'.$widgetType.'_parent-'.$executive.'-checker" onClick="checkUncheckChilds(this, \''.$widgetType.'_parent-'.$executive.'-holder\');uncheckElement(this, \''.$widgetType.'_parent-'.$manager.'-checker\', \''.$widgetType.'_parent-'.$manager.'-holder\');populateClientList(\''.$widgetType.'\');" /><strong>'.$this->executiveNames[$executive].'</strong></li>';
				if($this->executiveClientMapping[$executive]) {
					$text .= '<li style="padding-left:'.($margin+20).'px;"><input type="checkbox" name="executive_id[]" onClick="uncheckElement(this, \''.$widgetType.'_parent-'.$executive.'-checker\', \''.$widgetType.'_parent-'.$executive.'-holder\');populateClientList(\''.$widgetType.'\');" value="'.$executive.'" />'.$this->executiveNames[$executive].'</li>';
				}
				$text .= $this->_displayHierarchy($subordinates, $executive, $widgetType, $level + 1);
				$text .= '</ol>';
			}
		}
		return $text;
	}

	private function _getDropDowns($viewType='report',$widgetType='report') {
		$userId = $this->_validateuser['userid'];
		$data = array();
		$data['widgetType'] = $widgetType;
		$data['userId'] = $userId;
		$data['salesUser'] = $this->salesUser;
		$data['executiveHierarchy'] = $this->executiveHierarchy;
		$data['executiveClientMapping'] = $this->executiveClientMapping;
		$data['hierarchyHtml'] = $this->_displayHierarchy($this->executiveHierarchy, $userId, $widgetType, 0);
		$data['isSmartAbroad'] = $_COOKIE['SMARTInterfaceMode'] == 'Abroad' ? 1 : 0;
		if($viewType == 'report') {
			return $this->load->view('smart/ReportDropdowns',$data,true);
		}
		else {
			return $this->load->view('smart/DashboardDropdowns',$data,true);
		}
	}

	private function _getGraphBox($widget, $salesUser, $isNewUser) {
		$data = array();
		$data['widget'] = $widget;
		$data['salesUser'] = $salesUser;
		$data['isNewUser'] = $isNewUser;
		return $this->load->view('smart/DashboardChartBox', $data, true);
	}
	
	private function _getClientExpectationHTML() {
		$smartModel = $this->load->model('smartmodel');
		$clientExpectation = $smartModel->getClientExpectations($this->instituteList, false);
		
		$text = '';
		if(!empty($this->clientInstituteMapping)) {
			foreach($this->clientInstituteMapping as $client=>$institutes) {
			    
				$text .= '<tr>
						<td rowspan="'.count($institutes).'">'.$this->clientNames[$client].'</td>';
				if(!empty($institutes)) {
					foreach($institutes as $instituteID=>$instituteName) {
						$clientExpectationOfResponses = empty($clientExpectation[$instituteID]['clientExpectationOfResponses']) ? '' : $clientExpectation[$instituteID]['clientExpectationOfResponses'];
						$fromDate = empty($clientExpectation[$instituteID]['fromDate']) ? 'yyyy-mm-dd' : date('Y-m-d', strtotime($clientExpectation[$instituteID]['fromDate']));
						$toDate = empty($clientExpectation[$instituteID]['toDate']) ? 'yyyy-mm-dd' : date('Y-m-d', strtotime($clientExpectation[$instituteID]['toDate']));
						$clientsExpectedRunRateRound = empty($clientExpectation[$instituteID]['clientsExpectedRunRate']) ? '' : round($clientExpectation[$instituteID]['clientsExpectedRunRate']);
						$clientsExpectedRunRate = empty($clientExpectation[$instituteID]['clientsExpectedRunRate']) ? '' : $clientExpectation[$instituteID]['clientsExpectedRunRate'];
						$style = empty($this->institutePaid[$instituteID]) ? 'style="color:#777;font-style:italic;"' : '';
						$color = empty($this->institutePaid[$instituteID]) ? 'readonly="true" style="background-color:#ECEAE9;color:#84888B;"' : 'onblur="showError('.$instituteID.', \'expectation\', validateExpectation('.$instituteID.'));"';
						$onClickFrom = empty($this->institutePaid[$instituteID]) ? '' : 'onclick="timerangeFrom('.$instituteID.');"';
						$onClickTo = empty($this->institutePaid[$instituteID]) ? '' : 'onclick="timerangeTo('.$instituteID.');"';
						
						$text .=     '<td '.$style.'>'.$instituteName.'</td>
							      <td>
								    <input type="hidden" class="institute" name="institute[]" value="'.$instituteID.'"/>
								    <input id="expectation_field_'.$instituteID.'" class="expec-field" type="text" name="expectation[]" value="'.$clientExpectationOfResponses.'" maxlength="7" '.$color.' onkeyup="showRunRate('.$instituteID.');"/>
								    <div id="expectation_error_'.$instituteID.'" class="errorMsg" style="display:none;"></div>
							      </td>
							      <td>
								    <div class="date-range" style="padding-left:0">
									<input id="timerange_from_'.$instituteID.'" class="timerange_from" name="timefilterfrom[]" type="text" value="'.$fromDate.'" readonly="true" onchange="showError('.$instituteID.', \'from_date\', validateFromDate('.$instituteID.')); showRunRate('.$instituteID.');"/>
									<span class="icon-cal" id="timerange_from_img_'.$instituteID.'" '.$onClickFrom.'></span>
								    </div>
								    <div class="clearFix"></div>
								    <div id="from_date_error_'.$instituteID.'" class="errorMsg" style="display:none;"></div>
							      </td>
							      <td>
								    <div class="date-range" style="padding-left:0">
									<input id="timerange_to_'.$instituteID.'" class="timerange_to" name="timefilterto[]" type="text" value="'.$toDate.'" readonly="true" onchange="showError('.$instituteID.', \'to_date\', validateToDate('.$instituteID.')); showRunRate('.$instituteID.');"/>
									<span class="icon-cal" id="timerange_to_img_'.$instituteID.'" '.$onClickTo.'></span>
								    </div>
								    <div class="clearFix"></div>
								    <div id="to_date_error_'.$instituteID.'" class="errorMsg" style="display:none;"></div>
							      </td>
							      <td>
								    <label id="run_rate_'.$instituteID.'">'.$clientsExpectedRunRateRound.'</label>
								    <input id="run_rate_field_'.$instituteID.'" type="hidden" name="runrate[]" value="'.$clientsExpectedRunRate.'"/>
							      </td>
							  </tr>
							  <tr>';
					}
				}
				$text = substr($text, 0, -4);
			}
		}
		else {
		    $text = '';
		}
		
		return $text;
	}

	public function viewDashboard() {
		ini_set('memory_limit','-1');
		$this->_checkSMARTInterfaceMode('dashboard');
		$this->_validateuser();
		$this->_getUserMappings();
        $clientId = $this->_validateuser['userid'];
		$smartModel = $this->load->model('smartmodel');
		$data = array();
		// $executiveId = $smartModel->getSalesPersonForClients(array($clientId));
		$clientExecutiveMapping = array();
        $clientExecutiveMapping = $smartModel->getSalesPersonForClients(array($clientId));
        $executiveId 			= $clientExecutiveMapping['executivesArray'];
        unset($clientExecutiveMapping);
		
		$data['accountManagerDetails'] = $smartModel->getAccountManagerDetails($executiveId[0]);
		//$data['flavoredArticles'] =$this->renderFlavouredArticles();
		$data['headerContentaarray'] = $this->_loadHeaderContent(SMART_DASHBOARD_TAB_ID);
		$data['responseDropdowns'] = $this->_getDropDowns('dashboard','response');
		$data['activityDropdowns'] = $this->_getDropDowns('dashboard','activity');
		$data['creditDropdowns'] = $this->_getDropDowns('dashboard','credit');
		$data['leadsDropdowns'] = $this->_getDropDowns('dashboard','leads');
        $data['responseGraphBox'] = $this->_getGraphBox('response', $this->salesUser, $this->isNewUser);
		$data['activityGraphBox'] = $this->_getGraphBox('activity', $this->salesUser, $this->isNewUser);
		$data['creditDateBox'] = $this->_getGraphBox('credit', $this->salesUser, $this->isNewUser);
		$data['leadsDateBox'] = $this->_getGraphBox('leads', $this->salesUser, $this->isNewUser);
		$data['dynamicTitle'] = $this->userTitle;
		$data['footerContentaarray'] = $this->_loadfooterContent();
		$this->load->view('smart/Dashboard',$data);
	}
        
    public function viewClientLogin() {
		ini_set('memory_limit','-1');
		$this->_checkSMARTInterfaceMode('clientLogin');
		$this->_validateuser();
		$this->_getUserMappings();
		
		$data = array();
		$userId = $this->_validateuser['userid'];
		$usergroup = $this->_validateuser['validity'][0]['usergroup'];
		$data['userId'] = $userId;
		$data['usergroup'] = $usergroup;
		$data['salesUser'] = $this->salesUser;
		$data['executiveHierarchy'] = $this->executiveHierarchy;
		$data['executiveClientMapping'] = $this->executiveClientMapping;
		$data['executiveNames'] = $this->executiveNames;
		$data['clientNames'] = $this->clientNames;
		$data['headerContentaarray'] = $this->_loadHeaderContent(SMART_CLIENT_LOGIN_TAB_ID);
		$data['dynamicTitle'] = $this->userTitle;
		$data['footerContentaarray'] = $this->_loadfooterContent();
		if($data['usergroup'] != 'sums') {

			header('location:/enterprise/Enterprise/loginEnterprise');
        	exit();
		
		}

		$this->load->view('smart/ClientLogin',$data);
	}

	function getLoginDetails() {
		
		$cookie = $_COOKIE['user'];
		$loginByDetails = explode('|', $cookie);
		$loginBy['email'] = $loginByDetails[0];
		
		$smartModel = $this->load->model('smartmodel');
		
		$this->_validateuser();
		
		$loggedInUserId = $this->_validateuser['userid'];
		$loggedInCookieStr = $this->_validateuser['validity'][0]['cookiestr'];
		$loggedInDetails = explode('|', $loggedInCookieStr);
		$loggedInEmail = $loggedInDetails[0];
		$loggedInUsergroup = $this->_validateuser['validity'][0]['usergroup'];

		if(($loggedInUsergroup == 'sums') && ($loginBy['email'] == $loggedInEmail)) {

			$clientDetails = $smartModel->getClientLoginDetails($_POST['userId']);
			
			$strcookie = $clientDetails[0]['email'].'|'.$clientDetails[0]['ePassword'];
			
			setcookie('user',$strcookie,time() + 2592000,'/',COOKIEDOMAIN);
			
			$this->load->library('Login_client');
			$login_client = new Login_client();
			$Validate = $login_client->validateuser($strcookie,'login');
			
			if(($Validate != "false") && (is_array($Validate)) && (!empty($Validate[0]['userid']))) {
				
				$value = $Validate[0]['cookiestr'];
				$status = $Validate[0]['status'];
				$pendingverification = $Validate[0]['pendingverification'];
				$hardbounce = $Validate[0]['hardbounce'];
				$ownershipchallenged = $Validate[0]['ownershipchallenged'];
				$softbounce = $Validate[0]['softbounce'];
				$abused = $Validate[0]['abused'];
				$emailsentcount = $Validate[0]['emailsentcount'];

				if($abused == 1 || $ownershipchallenged == 1) {
					setcookie('user',$loggedInCookieStr,time() + 2592000,'/',COOKIEDOMAIN);
					echo 'fail';
					return;
				} else {
					if($Validate[0]['emailverified'] == 1) {
						$value .= "|verified";
					} else {
							if($hardbounce == 1)
								$value .= "|hardbounce";
							if($softbounce == 1)
								$value .= "|softbounce";
							if($pendingverification == 1)
								$value .= '|pendingverification';
					}
					
					setcookie('user',$value,time() + 2592000,'/',COOKIEDOMAIN);
					
					if(isset($_COOKIE['SMARTInterfaceMode'])){ 
						unset($_COOKIE['SMARTInterfaceMode']);
						setcookie('SMARTInterfaceMode',null,0,'/',COOKIEDOMAIN);
					}

					/**
					 * Track user logout/login time
					 */ 	
					$this->load->model('user/usermodel');
					$this->usermodel->trackUserLogout($loggedInUserId);
					$this->usermodel->trackUserLogin($Validate[0]['userid']);

					/**
					 * Put client login data into table
					 * 
					 */
					$data = array();
					$data['loginById'] = $loggedInUserId;
					$data['loginToId'] = $Validate[0]['userid'];
					$smartModel->insertClientLoginDetails($data);
					setcookie('clientAutoLogin',$loginBy['email'],time() + 2592000,'/',COOKIEDOMAIN);
					echo 'success';
					return;
				}
				

			} else {
				setcookie('user',$loggedInCookieStr,time() + 2592000,'/',COOKIEDOMAIN);
				echo 'fail';
				return;
			}
			
		} else {
			echo 'fail';
			return;
		}
		
	}

	/*function renderFlavouredArticles($categoryId) {
		if(isset($categoryId)){
			$this->CategoryId = array();
			$this->CategoryId[0] = $categoryId;
		}

		if(!is_array($this->CategoryId)){
			$this->CategoryId = array();
                        $this->CategoryId[0] = 3;
		}
                
                $this->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$categoryRepository = $categoryBuilder->getCategoryRepository();
		$subCategory = $categoryRepository->find($this->CategoryId[0]);		                
                $categoryName = $subCategory->getName();
                
		$this->load->helper('shikshautility_helper');
		$smartModel = $this->load->model('smartmodel');
		$criteria = array('startDate'=> date('Y-m-d'));
		$criteria = array();
		$orderBy = 'startDate desc';
		$countOffset = 10;
		$startOffset = 0;
                $subCategories = $smartModel->getSubCategories($this->CategoryId[0]);
                
		$flavoredArticles = $smartModel->getFlavorArticlesByCategory(json_encode($criteria), $orderBy, $startOffset, $countOffset,$subCategories);
		if(is_array($flavoredArticles)) {
			$flavoredArticles = $flavoredArticles['articles'];
		} else {
			$flavoredArticles = array();
		}
		$latestUpdates = $smartModel->getLatestUpdatesByCategory(40,$subCategories);

		$displayData['latestUpdates'] = $latestUpdates;
		$displayData['flavoredArticle'] = $flavoredArticles;
		$string1 = $this->load->view('IndustryNewsFlavorpanel',$displayData,true);
		$string2 = $this->load->view('IndustryNewsWidget',$displayData,true);
                
		$latestUpdatesCheck = json_decode($latestUpdates,true);
		if(empty($latestUpdatesCheck['articles']) && empty($flavoredArticles)){
			$string1 = "</br></br></br><h3>No articles found for this category..</h3>";
		}
		$returnArray['categoryName'] = '<a id="newsCategoryName" uniqueattr="Dashboard/CategoryName/'.$categoryName.'" href="javascript:void(0);" onclick="hideLayer();">'.$categoryName.'</a> <a id="newsCategoryOpen" href="javascript:void(0);" id="openlayer" onclick="hideLayer();" class="arrow-d">  </a>';
		$returnArray['widgetHtml'] = $string1.$string2;
		$returnArray['countArticles'] = count($flavoredArticles);
		$returnArray['countLatestUpdates'] = count($latestUpdatesCheck['articles']);
                
		if(!isset($categoryId)){
			return $returnArray;
		}else{
			echo json_encode($returnArray);
		}
	}*/

        /*
         * Provide Industry News Articles
         */
        
        public function getIndustryNewsArticles(){
                $this->_validateuser();
                $clientId = $this->_validateuser['userid'];
		$smartModel = $this->load->model('smartmodel');
                $data = array();
                
                $this->load->library('blogs/blog_client');
		$appId = 1;
		$blogs = array();
		$blog_client = new Blog_client();
		$criteria = array('startDate'=> date('Y-m-d'));
		$criteria = array();
		$orderBy = 'startDate desc';
		$countOffset = 10;
		$startOffset = 0;
		$flavoredArticles = $blog_client->getFlavorArticles($appId, json_encode($criteria), $orderBy, $startOffset, $countOffset,'homepage');
		$flavoredArticles = json_decode($flavoredArticles, true);
		if(is_array($flavoredArticles)) {
			$flavoredArticles = $flavoredArticles['articles'];
		} else {
			$flavoredArticles = array();
		}
		$latestUpdates = $blog_client->getLatestUpdatesForHomePage($appId,40,$dbHitVar,'homepage');
		$displayData['latestUpdates'] = $latestUpdates;
		$displayData['flavoredArticle'] = $flavoredArticles;
		return $displayData;
            
            
            
        }

    function convertArrayTocsv($inputData,$flag)
    {
    	$columnListArray = array();
		$columnListArray[]='SalesOrderNumber';
		$columnListArray[]='ClientUserId';
		$columnListArray[]='SubcriptionId';
		$columnListArray[]='TransactionTime';
		$columnListArray[]='BaseProdCategory';
		$columnListArray[]='BaseProdSubCategory';
		$columnListArray[]='TotalQuantity';
		$columnListArray[]='RemainingQuantity';
		$columnListArray[]='SubscriptionStartDate';
		$columnListArray[]='SubscriptionEndDate';
		$columnListArray[]='LineItemNumber';
		$columnListArray[]='SubscriptionStatus';
		$columnListArray[]='EmployeeId';

		$ColumnList = $columnListArray;

    	foreach ($ColumnList as $ColumnName)
    	{
			$csv .= '"'.$ColumnName.'",';
		}
		$csv.="ClientLoginEmailAddress";
		$csv .= "\n";
		$smartModel = $this->load->model('smartmodel');
    	$dataArray = explode(",",$inputData);
    	$len = sizeof($dataArray);

    	for($i=0;$i<$len;$i++)
    	{
    		$dataArray[$i] = trim($dataArray[$i]);
    	}

    	if($flag=="emailQuery")
		{
			$result_ids = array();
			$result = $smartModel->getSOUserId($dataArray);
			foreach($result as $lead)
			{
				$result_ids[] = $lead['userid'];
				$user_email_map[$lead['userid']] = $lead['email'];
			}
			if(!empty($result_ids))
			{
				$result = $smartModel->getSODataByUserId($result_ids);
			}
		}
		else
		{
			$userData = $smartModel->getUserData($dataArray);
			$result_ids = array();
			foreach($userData as $lead)
			{
				$result_ids[] = $lead['clientuserid'];
			}

			if(!empty($result_ids))
			{
				$userData = $smartModel->getClientEmailIds($result_ids);

				foreach($userData as $lead)
				{
					$user_email_map[$lead['userid']] = $lead['email'];
				}

				$result = $smartModel->getSOData($dataArray);
			}

		}
		
		foreach ($result as $lead)
    	{

    		foreach ($ColumnList as $ColumnName)
    		{
    			$csv .= '"'.$lead[$ColumnName].'",';

    		}

    		$csv.= '"'.$user_email_map[$lead['ClientUserId']].'",';
    		$csv .= "\n";
    	}

    	return $csv;
    }

    public function getSOUser()
    {
    	$email = $this->input->post('loginEmail');
    	$SalesOrderNumber = $this->input->post('salesOrder');
    	if($SalesOrderNumber !=="" || $SalesOrderNumber!=null)
    	{
    		$this->downloadSODetails($SalesOrderNumber,"salesQuery");
    	}
    	if($email!=="" || $email!=null)
    	{
    		$this->downloadSODetails($email,"emailQuery");
    	}
    }

    public function downloadSODetails($data,$flag)
    {
    	$data  = $this->convertArrayTocsv($data,$flag);
    	$filename = "SubscriptionDetails.csv";
       	$mime = 'text/x-csv';

       	header('Content-Type: "'.$mime.'"');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header('Expires: 0');
        header('Pragma: no-cache');
        header("Content-Length: ".strlen($data));
        echo $data;
    }

    public function viewSODetails()
    {
		$this->_checkSMARTInterfaceMode('SOConsumptionDetails');
		$this->_validateuser();
		$this->_getUserMappings();

		$data = array();
		$data['headerContentaarray'] = $this->_loadHeaderContent(SMART_SODETAILS_TAB_ID);
		$data['footerContentaarray'] = $this->_loadfooterContent();
		$this->load->view('smart/SOConsumptionDetails',$data);
    }

	public function changeSMARTInterfaceMode($mode,$redirection,$dualInterface = 'Yes')
	{
		setcookie('SMARTInterfaceMode',$mode,time() + 2592000 ,'/',COOKIEDOMAIN);
		$_COOKIE['SMARTInterfaceMode'] = $mode;
		
		setcookie('SMARTDualInterface',$dualInterface,time() + 2592000 ,'/',COOKIEDOMAIN);
		$_COOKIE['SMARTDualInterface'] = $dualInterface;
			
		if($redirection == 'report') {
			header('Location: /smart/SmartMis/viewReport');
		} 
		else if($redirection == 'clientLogin') {
			header('Location: /smart/SmartMis/viewClientLogin');
		}
		else if($redirection == 'SOConsumptionDetails'){
			header('Location: /smart/SmartMis/viewSODetails');
		}
		else {
			header('Location: /smart/SmartMis/viewDashboard');
		}
		exit();
	}
	
	private function _checkSMARTInterfaceMode($redirection)
	{
		$SMARTInterfaceMode = $_COOKIE['SMARTInterfaceMode'];
		if(!$SMARTInterfaceMode) {
			
			$loggedInUser = $this->cmsUserValidation();
			$loggedInUserId = $loggedInUser['userid'];
		
			$smartModel = $this->load->model('smartmodel');
			$userType = $smartModel->getSalesUserType($loggedInUserId);
			
			if($userType) {
				$this->changeSMARTInterfaceMode('National',$redirection);
			}
			/**
			 * If logged in user is client, check whether national, abroad or both
			 */ 
			else {
				$clientType = $smartModel->getClientType($loggedInUserId);
				if($clientType == 'Both') {
					$this->changeSMARTInterfaceMode('National',$redirection);
				}
				else if($clientType == 'National') {
					$this->changeSMARTInterfaceMode('National',$redirection,'No');
				}
				else {
					$this->changeSMARTInterfaceMode('Abroad',$redirection,'No');
				}
			}	
		}
	}
	
	public function viewReport()
	{
		$this->_checkSMARTInterfaceMode('report');
		$this->_validateuser();
		$this->_getUserMappings();
		$data = array();
		$data['headerContentaarray'] = $this->_loadHeaderContent(SMART_REPORT_TAB_ID);
		$data['reportDropdowns'] = $this->_getDropDowns('report');
		$data['dynamicTitle'] = $this->userTitle;
		$data['footerContentaarray'] = $this->_loadfooterContent();
		$this->load->view('smart/Report',$data);
	}
	
	public function viewClientExpectation() {
		$this->_validateuser();
		$this->_getUserMappings();
		
		if(empty($this->salesUser)) {
			show_404();
		}
		
		$data = array();
		$data['headerContentaarray'] = $this->_loadHeaderContent(SMART_CLIENT_EXPECTATION_TAB_ID);
		$data['footerContentaarray'] = $this->_loadfooterContent();
		$data['ClientExpectationTable'] = $this->_getClientExpectationHTML();
		$this->load->view('smart/ClientExpectation',$data);
	}
	
	public function setExpectation() {
		$institutes = $_POST['institute'];
		$expectations = $_POST['expectation'];
		$runRates = $_POST['runrate'];
		$fromDates = $_POST['timefilterfrom'];
		$toDates = $_POST['timefilterto'];
		
		$smartModel = $this->load->model('smartmodel');
		
		for ($index = 0; $index < count($institutes); $index++) {
			if(!empty($expectations[$index])) {
				$smartModel->setExpectationForInstitutes($institutes[$index], $expectations[$index], $runRates[$index], $fromDates[$index], $toDates[$index]);
			}
		}
	}
	
	public function getGraphData() {
		$reportType = $_POST['report_type'];
		$institutes = $_POST['institute'];
		$smartModel = $this->load->model('smartmodel');
		// $currentYear = date("Y");
		// $previousYear = $currentYear -1;
		// $startDate = $previousYear.'-01-01 00:00:00';
		$startDate = date("Y-m-d H:i:s", strtotime("-6 month"));
		$endDate = date("Y-m-d H:i:s");
		
		if($_COOKIE['SMARTInterfaceMode'] == 'Abroad') {
			$courseList = $smartModel->getCoursesForUniversities($institutes);
		}
		else {
			$courseList = $smartModel->getCoursesForInstitutes($institutes);
		}
		
		if($reportType == 'response'){
			$responses = $smartModel->getTotalResponses($courseList,$startDate,$endDate, false, 'periodwise');
		}
		else if($reportType == 'activity') {
			$dates = array();
			$questions = $smartModel->getQuestionsForInstitues($institutes, $startDate, $endDate);
			$questionsByDate = $smartModel->getQuestionsByDate(array_keys($questions));
			$answersByOther = $smartModel->getAnswersByOther($questions, $startDate, $endDate);
			$answersByInstitute = $smartModel->getAnswersByInstitute($questions, $startDate, $endDate);
			$dates = array_keys($questionsByDate) + array_keys($answersByInstitute) + array_keys($answersByOther);
			$responses = array_fill_keys($dates, array(0,0,0));
			
			foreach($dates AS $date) {
				if(array_key_exists($date, $questionsByDate)) {
					$responses[$date][0] += $questionsByDate[$date];
				}
				if(array_key_exists($date, $answersByOther)) {
					$responses[$date][1] += $answersByOther[$date];
				}
				if(array_key_exists($date, $answersByInstitute)) {
					$responses[$date][2] += $answersByInstitute[$date];
				}
			}
		}
		else if($reportType == 'credit') {
			$clients = $this->input->post('client_id');

			$creditsConsumed = $smartModel->getCreditsConsumedPeriodWise($clients,$startDate,$endDate, false, true);
			$creditsLeft = $smartModel->getCreditsLeft($clients,$startDate,$endDate,false);
			$goldlistingsLeft = $smartModel->getCreditsLeft($clients,$startDate,$endDate,false,'GOLD');
			foreach($creditsConsumed as $period => $values) {
				$responses[$period]['0'] = $values;
				$responses[$period]['1'] = (empty($creditsLeft[$clients[0]]) ? '0' : $creditsLeft[$clients[0]]);
				$responses[$period]['2'] = (empty($goldlistingsLeft[$clients[0]]) ? '0' : $goldlistingsLeft[$clients[0]]);
			}
			if(empty($responses)) {
				$responses[0][0] = 0;
				$responses[0][1] = (empty($creditsLeft[$clients[0]]) ? '0' : $creditsLeft[$clients[0]]);
				$responses[0][2] = (empty($goldlistingsLeft[$clients[0]]) ? '0' : $goldlistingsLeft[$clients[0]]);
			}
		}
		else if($reportType == 'leads') {
			$clients = $this->input->post('client_id');
			$timePeriod = $this->input->post('timePeriod');
			$status = $this->input->post('status');
			$timeFrom = $_POST['timefilter']['from'];
			$timeTill = $_POST['timefilter']['to'];

			if(!is_array($clients) || count($clients) == 0) {
				return;
			}
			
			$searchAgents = $smartModel->getLeadGenieForClient($clients, $status, True);
			$leadPortings = $smartModel->getLeadPortingsForClient($clients, $status, True);
			$data['leadsAllocated'] = $smartModel->getLeadGenieData(array_keys($searchAgents),$timeFrom,$timeTill,$timePeriod,$clients);
			$data['portingsData'] = $smartModel->getPortingsData(array_keys($leadPortings),$timeFrom,$timeTill,$timePeriod, True);
			$data['studentSearch'] = $smartModel->getStudentSearchData($clients,$timeFrom,$timeTill,$timePeriod);
			$data['searchAgents'] = $searchAgents;
			$data['leadPortings'] = $leadPortings;
			$this->load->view('smart/leadAllocationTable',$data);
			return;
		}
		echo json_encode($responses);
	}

	public function renderReport() {
		try {		
		$this->_validateuser();
		$userId = $this->_validateuser['userid'];
		$smartModel = $this->load->model('smartmodel');
		$salesUser = $smartModel->getSalesUserType($userId);
		$data = array();
	        // Load Header Content
		$data['headerContentaarray'] = $this->_loadHeaderContent(SMART_REPORT_TAB_ID);
		$data['userId'] = $userId;
		$data['salesUser'] = $salesUser;
		
		$reportType = $_POST['reporttype'];
		$clients = $_POST['client_id'];
		$institutes = $_POST['institute'];
		$timePeriod = $_POST['timePeriod'];
		$duration = $_POST['duration'];
		$leadGenie = $_POST['lead_genie'];
		$lead_porting = $_POST['lead_porting'];
		$response_porting = $_POST['response_porting'];
        $reportFormat = $_POST['reportFormat'];
		$isSmartAbroad = $_POST['isSmartAbroad'];
                
		if($duration == 'fixed') {
			$interval = $_POST['fixedduration'];
			
			if($interval == 'thisMonth') {
				$timeFrom = date('Y-m-1');
				$timeTill = date('Y-m-d');
			}
			else if($interval == 'lastMonth') {
				$timeFrom = date('Y-m-1',strtotime('-1 month'));
				$timeTill = date('Y-m-t',strtotime($timeFrom));
			}
			else {
				$interval = intval($interval) - 1;
				$timeTill = date('Y-m-d',strtotime('-1 day'));
				$timeFrom = date('Y-m-d',strtotime('-'.$interval.' day',strtotime($timeTill)));
			}
		}
		else {
			$timeFrom = $_POST['timefilter']['from'];
			$timeTill = $_POST['timefilter']['to'];
		}
		
                // Case handled For empty clients array
		if(count($clients) == 0){
			$clients = array($userId);
		}

		// if(!empty($clients) && $clients[0] > 0) {
		// 	if($userId != $clients[0]) {
		// 		return;
		// 	}
		// }
		
		/*
		*  Logic For Calling the required Mis Report Rendering API
		*/
		switch($reportType) {
			case 'response':
				$this->renderResponseReport($institutes,$timeFrom,$timeTill,$timePeriod,$clients,$reportFormat,$isSmartAbroad);
				break;
			case 'leads':
				$this->renderLeadsReport($institutes,$timeFrom,$timeTill,$timePeriod,$clients,$leadGenie);
				break;
			case 'credit':
				$this->renderCreditReport($institutes,$timeFrom,$timeTill,$timePeriod,$clients);
				break;
			case 'login':
				$this->renderLoginReport($institutes,$timeFrom,$timeTill,$timePeriod,$clients);
				break;
			case 'porting':
				$this->renderPortingReport($institutes,$timeFrom,$timeTill,$timePeriod,$clients,$lead_porting,'lead');
				break;
			case 'response_porting':
				$this->renderPortingReport($institutes,$timeFrom,$timeTill,$timePeriod,$clients,$response_porting,'response');
				break;
			case 'responselocation':
				$this->renderResponseLocationReport($institutes,$timeFrom,$timeTill,$timePeriod,$clients,$reportFormat,$isSmartAbroad);
				break;
		}
		
	 } catch(Exception $e) {
			//
	 }
	}

	/*
	 *  Render Response & Listing Mis Report
	 */ 

	public function renderResponseReport($institutes,$timeFrom,$timeTill,$timePeriod,$clients,$reportFormat,$isSmartAbroad) {
		$this->_validateuser();
		$userId = $this->_validateuser['userid'];
		$smartModel = $this->load->model('smartmodel');
		$salesUser = $smartModel->getSalesUserType($userId);
		$data = array();
		$data['userId'] = $userId;
		$data['salesUser'] = $salesUser;
		$data['reportType'] = $timePeriod;
		
		if($isSmartAbroad) {
			// Getting courses for all institutes
			$courseIdsMapping = $smartModel->getCoursesForUniversities($institutes,false);
		}
		else {
			// Getting courses for all institutes
			$courseIdsMapping = $smartModel->getCoursesForInstitutes($institutes,false);
		}
                
		foreach($courseIdsMapping as $institute=>$coursesIds){
			foreach($coursesIds as $key=>$value){
				$courses[] = $value;                        
			}
		}
              	  
		$totalResponses = $smartModel->getTotalResponses($courses,$timeFrom,$timeTill,$timePeriod);
		
		if($isSmartAbroad) {
			$courseInstituteMap = $smartModel->getCourseAndUniversityName($courses);
			$instituteTitleMap = $smartModel->getCourseAndUniversityName($courses,false);	
		}
		else {
			$courseInstituteMap = $smartModel->getCourseAndInstituteName($courses);
			$instituteTitleMap = $smartModel->getCourseAndInstituteName($courses,false);	
		}
		
		$data['instituteTitleMap'] = $instituteTitleMap;
		$data['courseInstituteMap'] = $courseInstituteMap;                
		$clientNames = $smartModel->getEnterpriseClientDisplayname($clients);
		
		

        /*
		 *  Logic for getting clients name
		 */

		foreach ($clientNames as $clientId => $clientDisplayname) {
			if(empty($clientDisplayname)) {
				$userNames[] = $clientId;
			}
		}

		if(!empty($userNames)) {
			$clientDisplaynames = $smartModel->getUserName($userNames);
			$clientNames = $clientDisplaynames + $clientNames;
		}
		$data['clientNames'] = $clientNames;

		/*
		 *  Logic for getting clients to sales person map
		 */

		// $data['clientExecutiveMapping'] = $smartModel->getSalesPersonForClients($clients, False);
		// $executives = $smartModel->getSalesPersonForClients($clients);
		$clientExecutiveMapping 		= array();
        $clientExecutiveMapping 		= $smartModel->getSalesPersonForClients($clients);
        $data['clientExecutiveMapping'] = $clientExecutiveMapping['clientExecutiveMapping'];
        $executives 					= $clientExecutiveMapping['executivesArray'];
        unset($clientExecutiveMapping);

		$data['executiveNames'] = $smartModel->getUserName($executives);

		/*
		 *  Logic for Getting difference of dates for getting day wise run rate
		 */

		$datetime1 = new DateTime($timeFrom);
		$datetime2 = new DateTime($timeTill);
		$interval = $datetime1->diff($datetime2);
		$intervaldays = ($interval->days + 1);

		/*
		 *  Summary For Institute MIS Report having different report format case has been handled seperately
		 */
                
		$data['merged'] = array();
		if($timePeriod == 'summarybyinstitute'){
			$period = ';'.$timeFrom.';'.$timeTill;
			$quesPosted = $smartModel->getQuestionsPosted($institutes,$timeFrom,$timeTill,$timePeriod); 
			$questions = $smartModel->getQuestions($institutes, $timeFrom, $timeTill);
			$clientExpectations = $smartModel->getClientExpectations($institutes);
			
            foreach($institutes as $instituteId) {
				unset($totalResponsesPerInstitute[0]);
									
				$coursesPerInstitute = $courseIdsMapping[$instituteId];                                
				$instituteTitle = $courseInstituteMap[$coursesPerInstitute[0]]['instituteTitle'];
				$clientId = $courseInstituteMap[$coursesPerInstitute[0]]['clientId'];
				$clientName = $clientNames[$clientId];
								   
				foreach($coursesPerInstitute as $key=>$courseId){
					if(in_array($courseId,array_keys($totalResponses))){
					   foreach($totalResponses[$courseId] as $key=>$value){
							$totalResponsesPerInstitute[0] += $value;
					   }
					}
				}
                               			        
				if(!empty($totalResponsesPerInstitute) || $quesPosted[$instituteId][$period]){
					$data['merged'][$clientId][$instituteId]['totalResponses'] = $totalResponsesPerInstitute[0]; 
					$data['merged'][$clientId][$instituteId]['QuestionsPosted'] = $quesPosted[$instituteId][$period];
					if($questions[$instituteId]){
						$answersByOwners[$instituteId] = $smartModel->getQuestionsAnsweredByInstituteOwners($questions[$instituteId], $timeFrom, $timeTill); 
						$answersByOther[$instituteId] = $smartModel->getQuestionsAnsweredByOthers($questions[$instituteId], $timeFrom, $timeTill);
					} 
					$data['merged'][$clientId][$instituteId]['QuestionsAnsweredByOwners'] = $answersByOwners[$instituteId]['QuestionsAnsweredByOwners'];
					$data['merged'][$clientId][$instituteId]['QuestionsAnsweredByOthers'] = $answersByOther[$instituteId]['QuestionsAnsweredByOthers'];
					$data['merged'][$clientId][$instituteId]['ExpectedResponses'] = $clientExpectations[$instituteId]['clientExpectationOfResponses'];
					$data['merged'][$clientId][$instituteId]['DateRange'] = $clientExpectations[$instituteId]['DateRange'];
					$data['merged'][$clientId][$instituteId]['SuggestedDailyRunRateofResponses'] = $clientExpectations[$instituteId]['clientsExpectedRunRate'];
					$achivedRunRate = ($totalResponsesPerInstitute[0]/$intervaldays);
					$data['merged'][$clientId][$instituteId]['AcheivededDailyRunRateofResponses'] = sprintf ("%.2f", $achivedRunRate);
					
                    if (!array_key_exists($instituteId,$clientExpectations)) {		
                        $data['merged'][$clientId][$instituteId]['Shortfall'] = 0;
					}
					else{
						$data['merged'][$clientId][$instituteId]['Shortfall'] = $data['merged'][$clientId][$instituteId]['SuggestedDailyRunRateofResponses'] - $data['merged'][$clientId][$instituteId]['AcheivededDailyRunRateofResponses'];
					}
				}
			}
			// Key Wise Sorting Of Data
			ksort($data['merged']);

			if($reportFormat == 'csv'){
				$arr = $this->formatToCSV($data,'ResponseListing',$timePeriod);
				$finalOutput = $this->createCSVForResponseListingReportSummaryByInstitute($arr,$timeFrom,$timeTill);

				$filename = 'ResponseListing'.date('Y-m-d h-i-s').'.csv';
				$this->createCSV($filename,$finalOutput);

			}else{
                $this->load->view('smart/MisReports/responseReportSummaryByInstitute',$data);
			}
		}                
		else{                
			foreach($courses as $courseId) {
				if($totalResponses[$courseId]) {
					foreach($totalResponses[$courseId] as $period => $values) {
						if($values) {
							$instituteTitle = $courseInstituteMap[$courseId]['instituteTitle'];
							$courseTitle = $courseInstituteMap[$courseId]['courseTitle'];
							$clientId = $courseInstituteMap[$courseId]['clientId']; 							
							$instituteId = $courseInstituteMap[$courseId]['instituteId'];							
							$data['merged'][$clientId][$instituteId][$courseId][$period] = $values;                                                        
						}
					}
				}
			}
                        
			if($reportFormat == 'csv'){
				$arr = $this->formatToCSV($data,'ResponseListing',$timePeriod,$courseInstituteMap);
				$finalOutput = $this->createCSVForResponseListingReport($arr,$timePeriod,$timeFrom,$timeTill);
				$filename = 'ResponseListing'.date('Y-m-d h-i-s').'.csv';
				$this->createCSV($filename,$finalOutput);
			}
			else{
				$this->load->view('smart/MisReports/responseMisReport',$data);
			}
		}
	}

        function createCSV($filename,$csv){
            
                            $mime = 'text/x-csv';                        
                            if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
                                    header('Content-Type: "'.$mime.'"');
                                    header('Content-Disposition: attachment; filename="'.$filename.'"');
                                    header('Expires: 0');
                                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                                    header("Content-Transfer-Encoding: binary");
                                    header('Pragma: public');
                                    header("Content-Length: ".strlen($csv));
                            }
                            else {
                                    header('Content-Type: "'.$mime.'"');
                                    header('Content-Disposition: attachment; filename="'.$filename.'"');
                                    header("Content-Transfer-Encoding: binary");
                                    header('Expires: 0');
                                    header('Pragma: no-cache');
                                    header("Content-Length: ".strlen($csv));
                            }
                             echo $csv;
                             exit;
            
        }
        
	
        function formatToCSV($initialBuildArray,$reportType,$timePeriod,$courseInstituteMap = array()){
		$returnArray = array();
                $key = 0;
		if($timePeriod == 'daily'){  
			$timeDuration= 'Day';
		}  
		if($timePeriod == 'weekly'){  
			$timeDuration= 'Week';
		}  
		if($timePeriod == 'monthly'){  
			$timeDuration= 'Month';
		}  
		if($timePeriod == 'quarterly'){  
			$timeDuration= 'Quarter';
		}  
		if($timePeriod == 'yearly'){  
			$timeDuration= 'Year';
		}
		if( $reportType == 'ResponseLocation' && $timePeriod == 'summarybyinstitute' ){
			foreach($initialBuildArray['merged'] as $clientId=>$clientData){
				foreach($clientData as $instituteId=>$locationWisedata){
					foreach($locationWisedata as $Location=>$traversal){
						list($periodType,$periodStartDate,$periodEndDate) = explode(';',$traversal['DateRange']);                                
						$returnArray[$key]['Client Name'] = (empty($clientId) ? '' : $initialBuildArray['clientNames'][$clientId]);
						$returnArray[$key]['Institute Name'] = (empty($instituteId) ? '' : str_replace(str_split(",'-"), ' ', $initialBuildArray['instituteTitleMap'][$instituteId]['instituteTitle']));
						$returnArray[$key]['Response Location'] = (empty($Location) ? 'Others' : $Location);
						$returnArray[$key]['Response Counts'] = (empty($traversal['totalResponses']) ? '0' : $traversal['totalResponses']);
						$returnArray[$key]['Expected Responses'] = (empty($traversal['ExpectedResponses']) ? '0' : $traversal['ExpectedResponses']);                                     
						$returnArray[$key]['Date Range in which to achieve expected responses'] = (empty($traversal['DateRange']) ? '0' : date("M j Y",strtotime($periodStartDate))." - ".date("M j Y",strtotime($periodEndDate)));
                         $returnArray[$key]['Suggested Daily Run Rate of Responses'] = (empty($traversal['SuggestedDailyRunRateofResponses']) ? '0' : $traversal['SuggestedDailyRunRateofResponses']);
						$returnArray[$key]['Achieved Run Rate in Selected Duration'] = (empty($traversal['AcheivededDailyRunRateofResponses']) ? '0' : $traversal['AcheivededDailyRunRateofResponses']);
						$returnArray[$key]['Shortfall'] = (empty($traversal['SuggestedDailyRunRateofResponses']) ? '0' : ($returnArray[$key]['Suggested Daily Run Rate of Responses'] - $returnArray[$key]['Achieved Run Rate in Selected Duration'])); 
                                                $key++;
                        		}
				}   
			}
			return $returnArray;
		}elseif($reportType == 'ResponseLocation' && $timePeriod != 'summarybyinstitute' ){
			foreach($initialBuildArray['merged'] as $clientId=>$clientData){
				foreach($clientData as $instituteId=>$courses){
					foreach($courses as $courseTitle=>$courseData){
						foreach($courseData as $period=>$responses){
							list($periodType,$periodStartDate,$periodEndDate) = explode(';',$period);
							arsort($responses);
							foreach($responses as $Location=>$value){
								$returnArray[$key][$timeDuration] = date("M j Y",strtotime($periodStartDate))." - ".date("M j Y",strtotime($periodEndDate));
								$returnArray[$key]['Client Name'] = (empty($clientId) ? '' : $initialBuildArray['clientNames'][$clientId]);
								$returnArray[$key]['Institute Name'] = str_replace(str_split(",'-"), ' ', $initialBuildArray['instituteTitleMap'][$instituteId]['instituteTitle']);
								$returnArray[$key]['Course Name'] = str_replace(str_split(",'-"), ' ', $courseInstituteMap[$courseTitle]['courseTitle']);
								$returnArray[$key]['Location'] = (empty($Location) ? 'Others' : $Location);
								$returnArray[$key]['Response Count'] = (empty($value) ? '0' : $value);
                                                                $key++;
                					}
						}
					}
				}   
			}
			return $returnArray;
		}elseif($reportType == 'ResponseListing' && $timePeriod == 'summarybyinstitute' ){
			foreach($initialBuildArray['merged'] as $clientId=>$clientData){
				foreach($clientData as $instituteId=>$traversal){
					list($periodType,$periodStartDate,$periodEndDate) = explode(';',$traversal['DateRange']);                                
					$returnArray[$key]['Client Name'] = (empty($clientId) ? '' : $initialBuildArray['clientNames'][$clientId]);
					$returnArray[$key]['Institute Name'] = str_replace(str_split(",'-"), ' ', $initialBuildArray['instituteTitleMap'][$instituteId]['instituteTitle']);
					$returnArray[$key]['Response Counts'] = (empty($traversal['totalResponses']) ? '0' : $traversal['totalResponses']);
					$returnArray[$key]['Expected Responses'] = (empty($traversal['ExpectedResponses']) ? '0' : $traversal['ExpectedResponses']);                                     
					$returnArray[$key]['Date Range in which to achieve expected responses'] = (empty($traversal['DateRange']) ? '0' : date("M j Y",strtotime($periodStartDate))." - ".date("M j Y",strtotime($periodEndDate)));
					$returnArray[$key]['Suggested Daily Run Rate of Responses'] = (empty($traversal['SuggestedDailyRunRateofResponses']) ? '0' : $traversal['SuggestedDailyRunRateofResponses']);
					$returnArray[$key]['Achieved Run Rate in Selected Duration'] = (empty($traversal['AcheivededDailyRunRateofResponses']) ? '0' : $traversal['AcheivededDailyRunRateofResponses']);
					$returnArray[$key]['Shortfall'] = (empty($traversal['SuggestedDailyRunRateofResponses']) ? '0' : ($returnArray[$key]['Suggested Daily Run Rate of Responses'] - $returnArray[$key]['Achieved Run Rate in Selected Duration']));                                                 
                                        $returnArray[$key]['Questions Posted for Institute'] = (empty($traversal['QuestionsPosted']) ? '0' : $traversal['QuestionsPosted']);
					$returnArray[$key]['Questions Answered by Institute'] = (empty($traversal['QuestionsAnsweredByOwners']) ? '0' : $traversal['QuestionsAnsweredByOwners']);
					$returnArray[$key]['Questions Answered by Site Users'] = (empty($traversal['QuestionsAnsweredByOthers']) ? '0' : $traversal['QuestionsAnsweredByOthers']);
                                        $key++;
				}
			}   

		return $returnArray;
		}elseif($reportType == 'ResponseListing' && $timePeriod != 'summarybyinstitute' ){
                        foreach($initialBuildArray['merged'] as $clientId=>$clientData){
				foreach($clientData as $instituteId=>$courses){
					foreach($courses as $courseTitle=>$courseData){
						foreach($courseData as $period=>$responses){
							list($periodType,$periodStartDate,$periodEndDate) = explode(';',$period);
							$returnArray[$key][$timeDuration] = date("M j Y",strtotime($periodStartDate))." - ".date("M j Y",strtotime($periodEndDate));            
							$returnArray[$key]['Client Name'] = (empty($clientId) ? '' : $initialBuildArray['clientNames'][$clientId]);
							$returnArray[$key]['Institute Name'] = str_replace(str_split(",'-"), ' ', $initialBuildArray['instituteTitleMap'][$instituteId]['instituteTitle']);
							$returnArray[$key]['Course Name'] = str_replace(str_split(",'-"), ' ', $courseInstituteMap[$courseTitle]['courseTitle']);
							$returnArray[$key]['Response Counts'] = (empty($responses) ? '0' : $responses);
                                                        $key++;
						}
					}   
				}
			}
		return $returnArray;
		}
	}
	
	/*
	 *  Render Response Location Report
	 */ 

	public function renderResponseLocationReport($institutes,$timeFrom,$timeTill,$timePeriod,$clients,$reportFormat,$isSmartAbroad) {
		$this->_validateuser();
		$userId = $this->_validateuser['userid'];
		$smartModel = $this->load->model('smartmodel');
		$salesUser = $smartModel->getSalesUserType($userId);
		$data = array();
		$data['userId'] = $userId;
		$data['salesUser'] = $salesUser;
		$data['reportType'] = $timePeriod;
              
        
		if($isSmartAbroad) {
			// Getting courses for all universities
			$courseIdsMapping = $smartModel->getCoursesForUniversities($institutes,false);		
		}
		else {
			// Getting courses for all institutes
			$courseIdsMapping = $smartModel->getCoursesForInstitutes($institutes,false);		
		}
        
		foreach($courseIdsMapping as $institute=>$courseIds){
			foreach($courseIds as $key=>$value){
				$courses[] = $value;                        
			}
		}

		$totalResponsesLocationWise = $smartModel->getResponseByLocation($courses,$timeFrom,$timeTill,$timePeriod);
		
		$clientNames = $smartModel->getEnterpriseClientDisplayname($clients);
		foreach ($clientNames as $clientId => $clientDisplayname) {
			if(empty($clientDisplayname)) {
				$userNames[] = $clientId;
			}
		}
		if(!empty($userNames)) {
			$clientDisplaynames = $smartModel->getUserName($userNames);
			$clientNames = $clientDisplaynames + $clientNames;
		}
		$data['clientNames'] = $clientNames;

		/*
		 *  for getting clients to sales person map
		 */

		// $data['clientExecutiveMapping'] = $smartModel->getSalesPersonForClients($clients, False);
		// $executives = $smartModel->getSalesPersonForClients($clients);
		$clientExecutiveMapping 		= array();
        $clientExecutiveMapping 		= $smartModel->getSalesPersonForClients($clients);
        $data['clientExecutiveMapping'] = $clientExecutiveMapping['clientExecutiveMapping'];
        $executives 					= $clientExecutiveMapping['executivesArray'];
        unset($clientExecutiveMapping);

		$data['executiveNames'] = $smartModel->getUserName($executives);
		/*
		 *  for getting course to institute map
		 */
		
		if($isSmartAbroad) {
			$courseInstituteMap = $smartModel->getCourseAndUniversityName($courses);
			$instituteTitleMap = $smartModel->getCourseAndUniversityName($courses,false);	
		}
		else {
			$courseInstituteMap = $smartModel->getCourseAndInstituteName($courses);
			$instituteTitleMap = $smartModel->getCourseAndInstituteName($courses,false);	
		}
		
		$data['courseInstituteMap'] = $courseInstituteMap;
		$data['instituteTitleMap'] = $instituteTitleMap;

		/*
		 *  Logic for Getting difference of dates for getting day wise run rate
		 */

		$datetime1 = new DateTime($timeFrom);
		$datetime2 = new DateTime($timeTill);
		$interval = $datetime1->diff($datetime2);
		$intervaldays = ($interval->days + 1);
 
		$data['merged'] = array();

		/*
		 *  Summary For Institute MIS Report having different report format case has been handled seperately
		 */

		if($timePeriod == 'summarybyinstitute'){
			$clientExpectations = $smartModel->getClientExpectations($institutes);
			$period = ';'.$timeFrom.';'.$timeTill;
			foreach($institutes as $instituteId) {
			unset($responses);	
			unset($totalResponsesByLocationPerInstitute);
			$coursesPerInstitute = $courseIdsMapping[$instituteId];
				$instituteTitle = $courseInstituteMap[$coursesPerInstitute[0]]['instituteTitle'];
				$clientId = $courseInstituteMap[$coursesPerInstitute[0]]['clientId'];				
				
				foreach($coursesPerInstitute as $key=>$courseId){
					if(in_array($courseId,array_keys($totalResponsesLocationWise))){
						foreach($totalResponsesLocationWise[$courseId] as $period=>$values){
							foreach($values as $location=>$locationWiseResponses){
								$totalResponsesByLocationPerInstitute[$location] +=  $locationWiseResponses;
							}
						}
					}
				}
				$totalResponsesByInstitutes = 0;
				foreach($totalResponsesByLocationPerInstitute as $location=>$responses){
					$totalResponsesByInstitutes += $responses; 					
				}
				
				foreach($totalResponsesByLocationPerInstitute as $location=>$responsesPerLocation){
					$data['merged'][$clientId][$instituteId][$location]['totalResponses'] = $responsesPerLocation; 
					$data['merged'][$clientId][$instituteId][$location]['ExpectedResponses'] = $clientExpectations[$instituteId]['clientExpectationOfResponses'];
					$data['merged'][$clientId][$instituteId][$location]['DateRange'] = $clientExpectations[$instituteId]['DateRange'];
					$data['merged'][$clientId][$instituteId][$location]['SuggestedDailyRunRateofResponses'] = $clientExpectations[$instituteId]['clientsExpectedRunRate'];
					
					$achivedRunRate = ($totalResponsesByInstitutes/$intervaldays);
					$data['merged'][$clientId][$instituteId][$location]['AcheivededDailyRunRateofResponses'] = sprintf ("%.2f", $achivedRunRate);
					if (!array_key_exists($instituteId,$clientExpectations)) {
						$data['merged'][$clientId][$instituteId][$location]['Shortfall'] = '';
					}else{
						$data['merged'][$clientId][$instituteId][$location]['Shortfall'] = (float)$data['merged'][$clientId][$instituteId][$location]['SuggestedDailyRunRateofResponses'] - $data['merged'][$clientId][$instituteId][$location]['AcheivededDailyRunRateofResponses'];
					}
					
				}
			}

			if($reportFormat == 'csv'){
				$arr = $this->formatToCSV($data,'ResponseLocation',$timePeriod);
				$finalOutput = $this->createCSVForResponseLocationReportSummaryByInstitute($arr,$timeFrom,$timeTill);

				$filename = 'ResponseLocation'.date('Y-m-d h-i-s').'.csv';
				$this->createCSV($filename,$finalOutput);
			}
			else{
				$this->load->view('smart/MisReports/responseLocationReportSummaryByInstitute',$data);
			}		
		}
		else{
			foreach($courses as $courseId) {
				if($totalResponsesLocationWise[$courseId]) {
					foreach($totalResponsesLocationWise[$courseId] as $period => $values) {
						if($values) {
							$instituteTitle = $courseInstituteMap[$courseId]['instituteTitle'];
							$courseTitle = $courseInstituteMap[$courseId]['courseTitle'];
							$clientId = $courseInstituteMap[$courseId]['clientId']; 							
							$instituteId = $courseInstituteMap[$courseId]['instituteId'];
							$data['merged'][$clientId][$instituteId][$courseId][$period] = $values;
						}
					}
				}
			}

			if($reportFormat == 'csv'){
				$arr = $this->formatToCSV($data,'ResponseLocation',$timePeriod,$courseInstituteMap);
				$finalOutput = $this->createCSVForResponseLocationReport($arr,$timePeriod,$timeFrom,$timeTill);
				$filename = 'ResponseLocation'.date('Y-m-d h-i-s').'.csv';
				$this->createCSV($filename,$finalOutput);
			}
			else{
				$this->load->view('smart/MisReports/responseLocationReport',$data);
			}

		}
	}

	public function renderLoginReport($institutes,$timeFrom,$timeTill,$timePeriod,$clients) {
		$this->_validateuser();
		$userId = $this->_validateuser['userid'];
		$smartModel = $this->load->model('smartmodel');
		$salesUser = $smartModel->getSalesUserType($userId);
		$data = array();
		$data['userId'] = $userId;
		$data['salesUser'] = $salesUser;
		$data['reportType'] = $timePeriod;

		$loginDetails = $smartModel->getLoginAndSessionDetails($clients,$timeFrom,$timeTill,$timePeriod);
		$loginIdkeys = "0";
		foreach($loginDetails as $loginDetailsByUserId) {
			foreach($loginDetailsByUserId as $period => $values) {
				$loginIdkeys .= ",".implode(',',array_keys($values));
			}
		}
		$loginAndSessionDetails = $smartModel->getLoginDetails($loginIdkeys);
		$this->load->helper('date');

		//array_push($clients,0);
		foreach($clients as $userId) {
			foreach($loginDetails[$userId] as $period => $values) {
				$previousLoginId = 0;
				$loginIds = array_keys($values);
				foreach($loginIds as $loginId){
					if($loginAndSessionDetails[$userId][$loginId]['activity'] == 'Login'){
						$previousLoginId = $loginId;
						$data['merged'][$userId][$period][$loginId]['activityTime'] = $loginAndSessionDetails[$userId][$loginId]['activityTime'];
						$data['merged'][$userId][$period][$loginId]['activity'] = $loginAndSessionDetails[$userId][$loginId]['activity'];
						$data['merged'][$userId][$period][$loginId]['sessionTime'] = 'NA';
						$data['merged'][$userId][$period][$loginId]['ipAddress'] = $loginAndSessionDetails[$userId][$loginId]['ipAddress'];
						$data['merged'][$userId][$period][$loginId]['location'] = $this->geoCheckIPLocationDetails($loginAndSessionDetails[$userId][$loginId]['ipAddress']);

					}
					elseif($loginAndSessionDetails[$userId][$loginId]['activity'] == 'Logout' && $data['merged'][$userId][$period][$previousLoginId]['activity'] == 'Login'){
						$previousLoginTime = strtotime($loginAndSessionDetails[$userId][$previousLoginId]['activityTime']);
						$currentLoginTime = strtotime($loginAndSessionDetails[$userId][$loginId]['activityTime']);
						$sessionTime = timespan($previousLoginTime, $currentLoginTime);
						$data['merged'][$userId][$period][$previousLoginId]['sessionTime'] = $sessionTime;
					}
				}
			}
		}

		$clientNames = $smartModel->getEnterpriseClientDisplayname($clients);
		foreach ($clientNames as $clientId => $clientDisplayname) {
			if(empty($clientDisplayname)) {
				$userNames[] = $clientId;
			}
		}

		if(!empty($userNames)) {
			$clientDisplaynames = $smartModel->getUserName($userNames);
			$clientNames = $clientDisplaynames + $clientNames;
		}
        /*
		 *  for getting clients to sales person map
		 */
		// $data['clientExecutiveMapping'] = $smartModel->getSalesPersonForClients($clients, False);
		// $executives = $smartModel->getSalesPersonForClients($clients);
        $clientExecutiveMapping 		= array();
        $clientExecutiveMapping 		= $smartModel->getSalesPersonForClients($clients);
        $data['clientExecutiveMapping'] = $clientExecutiveMapping['clientExecutiveMapping'];
        $executives 					= $clientExecutiveMapping['executivesArray'];
        unset($clientExecutiveMapping);

		$data['executiveNames'] = $smartModel->getUserName($executives);
		$data['clientNames'] = $clientNames;
		$this->load->view('smart/MisReports/loginReport',$data);
	}

	public function renderCreditReport($institutes,$timeFrom,$timeTill,$timePeriod,$clients) {
		$this->_validateuser();
		$userId = $this->_validateuser['userid'];
		$smartModel = $this->load->model('smartmodel');
		$salesUser = $smartModel->getSalesUserType($userId);
		$data = array();
		$data['userId'] = $userId;
		$data['salesUser'] = $salesUser;
		$data['reportType'] = $timePeriod;
		
		$creditsConsumed = $smartModel->getCreditsConsumedPeriodWise($clients,$timeFrom,$timeTill,$timePeriod);
		$creditsLeft = $smartModel->getCreditsLeft($clients,$timeFrom,$timeTill,$timePeriod);
		
		array_push($clients,0);
		foreach($clients as $userId) {
			foreach($creditsConsumed[$userId] as $period => $values) {
				$data['merged'][$userId][$period]['creditsConsumed'] = $values;
				$data['merged'][$userId][$period]['creditsLeft'] = $creditsLeft[$userId];
			}
		}
		
		$clientNames = $smartModel->getEnterpriseClientDisplayname($clients);
		foreach ($clientNames as $clientId => $clientDisplayname) {
			if(empty($clientDisplayname)) {
				$userNames[] = $clientId;
			}
		}
		
		if(!empty($userNames)) {
			$clientDisplaynames = $smartModel->getUserName($userNames);
			$clientNames = $clientDisplaynames + $clientNames;
		}
                
		/*
		 *  for getting clients to sales person map
		 */
		
  //       $data['clientExecutiveMapping'] = $smartModel->getSalesPersonForClients($clients, False);
		// $executives = $smartModel->getSalesPersonForClients($clients);
		$clientExecutiveMapping 		= array();
        $clientExecutiveMapping 		= $smartModel->getSalesPersonForClients($clients);
        $data['clientExecutiveMapping'] = $clientExecutiveMapping['clientExecutiveMapping'];
        $executives 					= $clientExecutiveMapping['executivesArray'];
        unset($clientExecutiveMapping);

		$data['executiveNames'] = $smartModel->getUserName($executives);
		$data['clientNames'] = $clientNames;
		
		$this->load->view('smart/MisReports/creditMisReport',$data);
	}

	public function renderPortingReport($institutes,$timeFrom,$timeTill,$timePeriod,$clients,$porting,$type='lead') {
		$this->_validateuser();
		$userId = $this->_validateuser['userid'];
		$smartModel = $this->load->model('smartmodel');
		$salesUser = $smartModel->getSalesUserType($userId);
		$data = array();
		$data['userId'] = $userId;
		$data['salesUser'] = $salesUser;
		$data['reportType'] = $timePeriod;
		$data['portingType'] = $type;
		
		$portings = $smartModel->getPortingsData($porting,$timeFrom,$timeTill,$timePeriod,'',$type);
		$clientNames = $smartModel->getEnterpriseClientDisplayname($clients);
		foreach ($clientNames as $clientId => $clientDisplayname) {
			if(empty($clientDisplayname)) {
				$userNames[] = $clientId;
			}
		}
		
		if(!empty($userNames)) {
			$clientDisplaynames = $smartModel->getUserName($userNames);
			$clientNames = $clientDisplaynames + $clientNames;
		}
		$data['clientNames'] = $clientNames;
		
		foreach($portings as $userId=>$content) {
			foreach($content as $period => $values) {
				$data['merged'][$userId][$period]['portings'] = $values;
			}
		}
		
		$this->load->view('smart/MisReports/portingReport',$data);
	}

	public function renderLeadsReport($institutes,$timeFrom,$timeTill,$timePeriod,$clients,$searchagents){
		$this->_validateuser();
		$userId = $this->_validateuser['userid'];
		$smartModel = $this->load->model('smartmodel');
		$salesUser = $smartModel->getSalesUserType($userId);
		$data = array();
		$data['userId'] = $userId;
		$data['salesUser'] = $salesUser;
		$data['reportType'] = $timePeriod;
		
		$searchAgentsMap = $smartModel->getLeadGenieForClient($clients, False, False);
		$leadsAllocated = $smartModel->getLeadGenieData($searchagents,$timeFrom,$timeTill,$timePeriod,$clients);
		$leadsMatched = $smartModel->getSAMatchingLog($searchagents,$timeFrom,$timeTill,$timePeriod);
		
		foreach($clients as $userId) {
			if($salesUser == 'Admin'){
                            foreach($searchAgentsMap[$userId] as $searchagentId => $searchagentData) {
                                    foreach($leadsMatched[$searchagentId] as $period=>$value) {
                                            $data['merged'][$userId][$period][$searchagentId]['leadsMatched'] = $value['leadsMatched'];
                                            if(isset($leadsAllocated[$searchagentId][$period])){
                                                    $data['merged'][$userId][$period][$searchagentId]['leadsAllocated'] = $leadsAllocated[$searchagentId][$period]['leads'];
                                                    $data['merged'][$userId][$period][$searchagentId]['credits'] = $leadsAllocated[$searchagentId][$period]['credits'];
                                            }else{
                                                    $data['merged'][$userId][$period][$searchagentId]['leadsAllocated'] = 0;
                                                    $data['merged'][$userId][$period][$searchagentId]['credits'] = 0;
                                            }
                                    }
                            }
                        }
                        else{
                            foreach($searchAgentsMap[$userId] as $searchagentId => $searchagentData) {
                                    foreach($leadsAllocated[$searchagentId] as $period=>$value) {
                                            $data['merged'][$userId][$period][$searchagentId]['leadsAllocated'] = $leadsAllocated[$searchagentId][$period]['leads'];
                                            $data['merged'][$userId][$period][$searchagentId]['credits'] = $leadsAllocated[$searchagentId][$period]['credits'];
                                    }
                            }
                            
                        }
		}
		$data['searchAgentsMap'] = $searchAgentsMap;
		
		$clientNames = $smartModel->getEnterpriseClientDisplayname($clients);
		foreach ($clientNames as $clientId => $clientDisplayname) {
			if(empty($clientDisplayname)) {
				$userNames[] = $clientId;
			}
		}
		
		if(!empty($userNames)) {
			$clientDisplaynames = $smartModel->getUserName($userNames);
			$clientNames = $clientDisplaynames + $clientNames;
		}
		$data['clientNames'] = $clientNames;
		
		$this->load->view('smart/MisReports/leadGenieReport',$data);

	}


	function createCSVForResponseListingReportSummaryByInstitute($array,$timeFrom,$timeTill)
	{
		$filename = date(Ymdhis).' data.csv';
		$mime = 'text/x-csv';

		$csv = 'Listing & Reponse Report';
		$csv .= "\n";

		$t=time();                
		$csv .= "Report Generated on : ".date("Y-m-d H:i:s",$t);
		$csv .= "\n";

		$csv .= "REPORT DURATION,";
		$csv .= "FROM $timeFrom TO $timeTill";
		$csv .= "\n";

		$csv .= "Site,Shiksha.com";
		$csv .= "\n\n\n\n\n";

		$columnListArray = array();
		$columnListArray[]='Client Name';
		$columnListArray[]='Institute Name';
		$columnListArray[]='Response Counts';
		$columnListArray[]='Expected Responses';
		$columnListArray[]='Date Range in which to achieve expected responses';
		$columnListArray[]='Suggested Daily Run Rate of Responses';
		$columnListArray[]='Achieved Run Rate in Selected Duration';
		$columnListArray[]='Shortfall';
		$columnListArray[]='Questions Posted for Institute';
		$columnListArray[]='Questions Answered by Institute';
		$columnListArray[]='Questions Answered by Site Users';

		$ColumnList = $columnListArray;

		foreach ($ColumnList as $ColumnName){
			$csv .= '"'.$ColumnName.'",';
		}
		$csv .= "\n";
		foreach ($array as $lead){
			foreach ($ColumnList as $ColumnName){
				$csv .= '"'.$lead[$ColumnName].'",';
			}
			$csv .= "\n";
		}
		$data = $csv;
		return ($data);
	}


	function createCSVForResponseListingReport($array,$timePeriod,$timeFrom,$timeTill)
	{
		$filename = date(Ymdhis).' data.csv';
		$mime = 'text/x-csv';

		$csv = 'Listing & Reponse Report';
		$csv .= "\n";

		$t=time();                
		$csv .= "Report Generated on : ".date("Y-m-d H:i:s",$t);
		$csv .= "\n";

		$csv .= "REPORT DURATION,";
		$csv .= "FROM $timeFrom TO $timeTill";
		$csv .= "\n";

		$csv .= "Site,Shiksha.com";
		$csv .= "\n\n\n\n\n";


		$columnListArray = array();
		if($timePeriod == 'daily'){  
			$columnListArray[]= 'Day';
		}  
		if($timePeriod == 'weekly'){  
			$columnListArray[]= 'Week';
		}  
		if($timePeriod == 'monthly'){  
			$columnListArray[]= 'Month';
		}  
		if($timePeriod == 'quarterly'){  
			$columnListArray[]= 'Quarter';
		}  
		if($timePeriod == 'yearly'){  
			$columnListArray[]= 'Year';
		}

		$columnListArray[]='Client Name';
		$columnListArray[]='Institute Name';
		$columnListArray[]='Course Name';
		$columnListArray[]='Response Counts';


		$ColumnList = $columnListArray;

		foreach ($ColumnList as $ColumnName){
			$csv .= '"'.$ColumnName.'",';
		}
		$csv .= "\n";
		foreach ($array as $lead){
			foreach ($ColumnList as $ColumnName){
				$csv .= '"'.$lead[$ColumnName].'",';
			}
			$csv .= "\n";
		}
		$data = $csv;
		return ($data);
	}



	function createCSVForResponseLocationReportSummaryByInstitute($array,$timeFrom,$timeTill)
	{
		$filename = date(Ymdhis).' data.csv';
		$mime = 'text/x-csv';

		$csv = 'Reponse Location Report';
		$csv .= "\n";

		$t=time();                
		$csv .= "Report Generated on : ".date("Y-m-d H:i:s",$t);
		$csv .= "\n";

		$csv .= "REPORT DURATION,";
		$csv .= "FROM $timeFrom TO $timeTill";
		$csv .= "\n";

		$csv .= "Site,Shiksha.com";
		$csv .= "\n\n\n\n\n"; 

		$columnListArray = array();
		$columnListArray[]='Client Name';
		$columnListArray[]='Institute Name';
		$columnListArray[]='Response Location';
		$columnListArray[]='Response Counts';
		$columnListArray[]='Expected Responses';
		$columnListArray[]='Date Range in which to achieve expected responses';
		$columnListArray[]='Suggested Daily Run Rate of Responses';
		$columnListArray[]='Achieved Run Rate in Selected Duration';
		$columnListArray[]='Shortfall';

		$ColumnList = $columnListArray;

		foreach ($ColumnList as $ColumnName){
			$csv .= '"'.$ColumnName.'",';
		}
		$csv .= "\n";
		foreach ($array as $lead){
			foreach ($ColumnList as $ColumnName){
				$csv .= '"'.$lead[$ColumnName].'",';
			}
			$csv .= "\n";
		}
		$data = $csv;
		return ($data);
	}

	function createCSVForResponseLocationReport($array,$timePeriod,$timeFrom,$timeTill)
	{
		$filename = date(Ymdhis).' data.csv';
		$mime = 'text/x-csv';

		$csv = 'Reponse Location Report';
		$csv .= "\n";

		$t=time();                
		$csv .= "Report Generated on : ".date("Y-m-d H:i:s",$t);
		$csv .= "\n";

		$csv .= "REPORT DURATION,";
		$csv .= "FROM $timeFrom TO $timeTill";
		$csv .= "\n";

		$csv .= "Site,Shiksha.com";
		$csv .= "\n\n\n\n\n"; 



		$columnListArray = array();

		if($timePeriod == 'daily'){  
			$columnListArray[]= 'Day';
		}  
		if($timePeriod == 'weekly'){  
			$columnListArray[]= 'Week';
		}  
		if($timePeriod == 'monthly'){  
			$columnListArray[]= 'Month';
		}  
		if($timePeriod == 'quarterly'){  
			$columnListArray[]= 'Quarter';
		}  
		if($timePeriod == 'yearly'){  
			$columnListArray[]= 'Year';
		}

		$columnListArray[]='Client Name';
		$columnListArray[]='Institute Name';
		$columnListArray[]='Course Name';		
		$columnListArray[]='Location';
		$columnListArray[]='Response Count';

		$ColumnList = $columnListArray;

		foreach ($ColumnList as $ColumnName){
			$csv .= '"'.$ColumnName.'",';
		}
		$csv .= "\n";
		foreach ($array as $lead){
			foreach ($ColumnList as $ColumnName){
				$csv .= '"'.$lead[$ColumnName].'",';
			}
			$csv .= "\n";
		}
		$data = $csv;
		return ($data);
	}


	function geoCheckIPLocationDetails($ip){
		//check, if the provided ip is valid
		if(!filter_var($ip, FILTER_VALIDATE_IP))
		{
			return true;
		}
		//contact ip-server
		$response=@file_get_contents('http://www.netip.de/search?query='.$ip);
		if (empty($response))
		{
			throw new InvalidArgumentException("Error contacting Geo-IP-Server");
		}

		//Array containing all regex-patterns necessary to extract ip-geoinfo from page
		$patterns=array();
		$patterns["domain"] = '#Domain: (.*?)&nbsp;#i';
		$patterns["country"] = '#Country: (.*?)&nbsp;#i';
		$patterns["state"] = '#State/Region: (.*?)<br#i';
		$patterns["town"] = '#City: (.*?)<br#i';
		//Array where results will be stored
		$ipInfo=array();
		//check response from ipserver for above patterns
		foreach ($patterns as $key => $pattern)
		{
			//store the result in array
			if($key == "town" || $key == "state"){
				$ipInfo[$key] = preg_match($pattern,$response,$value) && !empty($value[1]) ? $value[1].", " : '';
			}else{
				$ipInfo[$key] = preg_match($pattern,$response,$value) && !empty($value[1]) ? $value[1] : '';
			}
		}
		/*I've included the substr function for Country to exclude the abbreviation (UK, US, etc..)
		  To use the country abbreviation, simply modify the substr statement to:
		  substr($ipInfo["country"], 0, 3)
		 */
		$ipdata = $ipInfo["town"].$ipInfo["state"].substr($ipInfo["country"], 4);
		return $ipdata;

	}
	
	function sendWeeklyResponseReport() {
		$this->validateCron();
		$smartModel = $this->load->model('smartmodel');
		
		$startDate = date('Y-m-d', strtotime("-7 day"));
		$endDate = date('Y-m-d', strtotime("-1 day"));
		
		$instituteWiseExpectations = $smartModel->getActiveClientExpections($startDate, $endDate);
		$activeInstitutes = array_keys($instituteWiseExpectations);
		$activeCourses = $smartModel->getCoursesForInstitutes($activeInstitutes,false);
		foreach($activeCourses as $instituteId => $courses) {
			$responses = $smartModel->getTotalResponses($courses, $startDate, $endDate, null, 'institutewise');
			$responses = empty($responses) ? 0 : $responses[0];
			$instituteWiseWeeklyResponses[$instituteId] = $responses;
			
			$responses = $smartModel->getTotalResponses($courses, $instituteWiseExpectations[$instituteId]['StartDate'], $instituteWiseExpectations[$instituteId]['EndDate'], null, 'institutewise');
			$responses = empty($responses) ? 0 : $responses[0];
			$instituteWiseTotalResponses[$instituteId] = $responses;
		}
		
		$salesUsers = $smartModel->getAllSalesUsers();
		$salesPersonToEmailMapping = $smartModel->getUserEmail($salesUsers);
		$salesPersonToNameMapping = $smartModel->getUserName($salesUsers);
		// $salesPersonToClientMapping = $smartModel->getClientsForSalesPerson($salesUsers, false);
		// $clients = $smartModel->getClientsForSalesPerson($salesUsers, true);
		$clientsSalesMapping = array();
		$clientsSalesMapping = $smartModel->getClientsForSalesPerson($salesUsers);
		$salesPersonToClientMapping = $clientsSalesMapping['executiveClientMapping'];
		$clients = $clientsSalesMapping['clientsArray'];
		unset($clientsSalesMapping);
		$clientToInstituteMapping = $smartModel->getInstitutesForClients($clients, false);
		$clientToNameMapping = $smartModel->getEnterpriseClientDisplayname($clients);
		
		foreach($salesUsers as $user) {
			$subordinates = array();
			$clientToSubordinateMapping = array();
			$clientList = array();
			$instituteList = array();
			$subordinateClientList = array();
			$clientInstituteList = array();
			$dataForCSV = array();
			$fileData = '';
			$fileName = '';
			$emailId = '';
			$subject = '';
			$message = '';
			$content = '';
			
			$subordinates = $smartModel->getSubordinatesForExecutive(array($user), true);
			$clientList[$user] = empty($salesPersonToClientMapping[$user]) ? array() : $salesPersonToClientMapping[$user];
			
			foreach($subordinates as $subordinate) {
				$subordinateClientList = empty($salesPersonToClientMapping[$subordinate]) ? array() : $salesPersonToClientMapping[$subordinate];
				$clientList[$subordinate] = $subordinateClientList;
			}
			
			foreach($clientList as $salesUser => $clients) {
				foreach($clients as $client) {
					$clientInstituteList = empty($clientToInstituteMapping[$client]) ? array() : $clientToInstituteMapping[$client];
					$instituteList[$client] = $clientInstituteList;
				}
			}
			
			foreach($clientList as $salesUser => $clients) {
				if(!empty($clients)) {
					foreach($clients as $client) {
						$institutes = $instituteList[$client];
						if(!empty($institutes)) {
							foreach($institutes as $instituteId => $instituteName) {
								if(isset($instituteWiseExpectations[$instituteId])) {
									$clientData = array();
									$clientData['Client Name'] = $clientToNameMapping[$client];
									$clientData['Institute Name'] = $instituteName;
									$clientData['Responses in Last 7 Days'] = isset($instituteWiseWeeklyResponses[$instituteId]) ? $instituteWiseWeeklyResponses[$instituteId] : 0;
									$clientData['Client Expectation in Last 7 Days'] = $instituteWiseExpectations[$instituteId]['WeeklyExpectation'];
									$clientData['Shortfall in Last 7 Days'] = $clientData['Client Expectation in Last 7 Days'] - $clientData['Responses in Last 7 Days'];
									$clientData['Responses Till Date'] = isset($instituteWiseTotalResponses[$instituteId]) ? $instituteWiseTotalResponses[$instituteId] : 0;
									$clientData['Client Expectation Till Date'] = $instituteWiseExpectations[$instituteId]['TotalExpectation'];
									$clientData['Shortfall Till Date'] = $clientData['Client Expectation Till Date'] - $clientData['Responses Till Date'];
									$clientData['Client Expectation Start Date'] = $instituteWiseExpectations[$instituteId]['StartDate'];
									$clientData['Client Expectation End Date'] = $instituteWiseExpectations[$instituteId]['EndDate'];
									$dataForCSV[$salesUser][] = $clientData;
								}
							}
						}
					}
				}
			}
			
			$emailId = $salesPersonToEmailMapping[$user];
			$from_name = 'Shiksha Info';
			$from_mail = 'info@shiksha.com';
			
			if(!empty($dataForCSV)) {
				$fileData = $this->createCSVForWeeklyResponseReport($dataForCSV, $salesPersonToNameMapping, $startDate, $endDate);
				$fileName = 'WeeklyResponseReportforExpectationShortfall'.date('Y-m-d').'.csv';
				
				$subject = 'Weekly Response Report for Expectation Shortfall';
				$message = 'Your weekly response report for expectation shortfall from '.$startDate.' to '.$endDate.' is attached.';
				$content = chunk_split(base64_encode($fileData));
				$uid = md5(uniqid(time()));
				
				$header = "From: ".$from_name." <".$from_mail.">\r\n";
				$header .= "MIME-Version: 1.0\r\n";
				$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
				$header .= "This is a multi-part message in MIME format.\r\n";
				$header .= "--".$uid."\r\n";
				$header .= "Content-type:text/html; charset=iso-8859-1\r\n";
				$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
				$header .= $message."\r\n\r\n";
				$header .= "--".$uid."\r\n";
				$header .= "Content-Type: text/csv; name=\"".$fileName."\"\r\n";
				$header .= "Content-Transfer-Encoding: base64\r\n";
				$header .= "Content-Disposition: attachment; filename=\"".$fileName."\"\r\n\r\n";
				$header .= $content."\r\n\r\n";
				$header .= "--".$uid."--";
				
				mail($emailId, $subject, $message, $header);
			}
			else {
				$subject = 'Weekly Response Report for Expectation Shortfall';
				$message = 'You have no active client expectations from '.$startDate.' to '.$endDate.'.';
				$header = "From: ".$from_name." <".$from_mail.">\r\n";
				mail($emailId, $subject, $message, $header);
			}
		}
	}
	
	function createCSVForWeeklyResponseReport($dataForCSV, $salesPersonToNameMapping, $startDate, $endDate)
	{		
		$csv = 'Weekly Response Report for Expectation Shortfall';
		$csv .= "\n";
		
		$time = time();
		$csv .= 'Report Generated on : '.date("Y-m-d H:i:s",$time);
		$csv .= "\n";
		
		$csv .= 'Report Duration : From '.$startDate.' To '.$endDate;
		$csv .= "\n";
		
		$csv .= 'Site : Shiksha.com';
		$csv .= "\n\n\n\n\n";
		
		$columnListArray = array();
		$columnListArray[] = 'Client Name';
		$columnListArray[] = 'Institute Name';
		$columnListArray[] = 'Responses in Last 7 Days';
		$columnListArray[] = 'Client Expectation in Last 7 Days';
		$columnListArray[] = 'Shortfall in Last 7 Days';
		$columnListArray[] = 'Responses Till Date';
		$columnListArray[] = 'Client Expectation Till Date';
		$columnListArray[] = 'Shortfall Till Date';
		$columnListArray[] = 'Client Expectation Start Date';
		$columnListArray[] = 'Client Expectation End Date';
		
		foreach($dataForCSV as $salesUser => $rows) {
			$csv .= 'Sales Executive : '.$salesPersonToNameMapping[$salesUser];
			$csv .= "\n\n";
			
			foreach ($columnListArray as $columnName){
				$csv .= '"'.$columnName.'",';
			}
			$csv .= "\n";
			
			foreach($rows as $row) {
				foreach($columnListArray as $columnName) {
					$csv .= '"'.$row[$columnName].'",';
				}
				$csv .= "\n";
			}
			
			$csv .= "\n\n\n";
		}
		
		return $csv;
	}
	
	function generateWeeklyResponseReportForClients($startDate, $endDate) {
		$smartModel = $this->load->model('smartmodel');
		
		$clientIds = array(1897984, 339953, 786593, 381241, 1656401, 1379482, 1589299, 1600710, 1085066, 1297802, 405922,
				   941180, 369652, 732002, 2388526, 1063394, 233612, 59000, 142401, 2470541, 2252347, 1837903,
				   1681164, 1077996, 1864459, 1856106, 964660, 1437920, 1313234, 1639579, 2436598, 898273, 2274743,
				   937303, 500918, 834292, 889951, 586440, 1757185, 2499487, 1322310, 1731453, 588099, 2022040,
				   1682628, 1610478, 823908, 3103, 1039421, 1296721, 1780766, 643152, 542335, 663241, 1827846,
				   832322, 1738609, 682016, 1599933, 459122, 1632920, 1729979, 549467, 141034, 419082, 668964,
				   1034302, 187454, 1334606, 365701);
		
		if(!isset($startDate)) {
			$startDate = date('Y-m-d', strtotime("-7 day"));
		}
		if(!isset($endDate)) {
			$endDate = date('Y-m-d', strtotime("-1 day"));
		}
		
		$clientToInstituteMapping = $smartModel->getInstitutesForClients($clientIds, false);
		$clientToNameMapping = $smartModel->getEnterpriseClientDisplayname($clientIds);
		
		$clientInstitutes = array();
		foreach($clientToInstituteMapping as $clientId => $institutes) {
			$clientInstitutes = array_merge($clientInstitutes, array_keys($institutes));
		}
		
		$activeCourses = $smartModel->getCoursesForInstitutes($clientInstitutes,false);
		
		$totalResponses = array();
		foreach($activeCourses as $instituteId => $courses) {
			$responses = $smartModel->getTotalResponses($courses, $startDate, $endDate, null, 'institutewise');
			$totalResponses[$instituteId] = $responses;
		}
		
		echo '<table border="1"><tr><th>Client ID</th><th>Client Name</th><th>Institute Name</th><th>Responses</th></tr>';
		foreach($clientIds as $clientId) {
			$institutes = $clientToInstituteMapping[$clientId];
			foreach($institutes as $instituteId => $instituteName) {
				$clientName = $clientToNameMapping[$clientId];
				$instituteResponses = empty($totalResponses[$instituteId]) ? 0 : $totalResponses[$instituteId][0];
				
				echo '<tr><td>'.$clientId.'</td><td>'.$clientName.'</td><td>'.$instituteName.'</td><td>'.$instituteResponses.'</td></tr>';
			}
		}
		echo '</table>';
	}
	
	public function generateClientDeliveryReport(){
		
		$this->load->library(array('category_list_client'));
		
		$userStatus = $this->cmsUserValidation();
		
		if (($userStatus == "false" ) || ($userStatus == "")) {
			header('location:/enterprise/Enterprise/loginEnterprise');
			exit();
		}
		$data['headerTabs'] = $userStatus['headerTabs'];
		$data['validateuser'] = $userStatus['validity'];
		
		$categoryClient = new Category_list_client();
		$catSubcatList = $categoryClient->getCatSubcatList(2,1);
		
	    $data['catSubcatList'] = $catSubcatList;
		
		$this->load->view("smart/showClientDeliveryData", $data);
	}
	
	public function getDataForReport() {
		return false;
		$starttime = microtime(true);
		
		$instituteIds = $this->input->post('instituteId',true);

		$smartModel = $this->load->model('smartmodel');
		$clientIds = $smartModel->getClientIdsFromInstituteIds($instituteIds);
		
		$categoryId = $this->input->post('categoriesHolder',true);
		$subCategoryId = $this->input->post('subCategoriesHolder',true);
		$from = $this->input->post('timerangeFrom',true);
		$to = $this->input->post('timerangeTill',true);
		$this->load->helper("string_helper");
        $search_key = random_string("alnum", 32).time();
        
		storeTempUserData('search_key',$search_key);
        $mime = 'text/x-csv';
        $filename = "Client_Delivery_Report_".time().".csv";
        
        if($clientIds != ''){
        	$data = $this->downloadCSVForReport($from,$to,$clientIds,$categoryId,$subCategoryId);
        } else {
        	echo "<script>alert('No Subscriptions exist.');</script>";
            exit;
        }
     	
        
		if (strlen($data) < 200) {
            echo "<script>alert('An error occurred,please try again later.');</script>";
            exit;
        }
        
		if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: " . strlen($data));
        } else {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: " . strlen($data));
        }
        
		$endtime = microtime(true);
		$timediff = $endtime - $starttime;
		error_log("Total Time Taken For Client Delivery Report : ".print_r($timediff,true));
		
		echo ($data);
	}
	
	public function clientDeliveryReportCron($categoryId) {
		return false;
		$startDate = date('Y-m-d 00:00:00',strtotime(date('Y-m-d')." -1 Year"));
		$endDate = date('Y-m-d 23:59:59');
		
		$dataCSV = $this->downloadCSVForReport($startDate,$endDate,'',$categoryId,'');
		
		if(!empty($dataCSV)) {
    		
			$attachmentPath = $this->createZip($dataCSV);			
			
			$from = 'info@shiksha.com';
			$to = 'mohul.mukherjee@shiksha.com , soumendu.g@naukri.com ';
			$cc = 'aditya.roshan@shiksha.com';
			
			$subject = 'Category Wise Client Delivery Report';
			$message = 'Hi,<br><br>
					Please find attached the Client Delivery Report.
					<br><br>
					Regards,<br>
					Shiksha.com';
			
			$fileName = 'Client_Delivery_Report_'.date('Y-m-d').".zip";
			
			$this->load->library("common/Util");
			$utilObj = new Util();
			
			$utilObj -> sendEmailWithAttachement($from,$to,$cc,$subject,$message,$attachmentPath,$fileName);
			
		}
		
	}
	
	public function createZip($csvData) {
		
    	$this->load->library('Zip');
    	$this->zip = new CI_Zip();
		
    	$fileName = "Client_Delivery_Report_".time().".csv";
    	$this->zip->add_data($fileName, $csvData);
		
    	$return_path = '/tmp/Client_Delivery_Report_'.time().'.zip';
    	$this->zip->archive($return_path);
		
    	return $return_path;
    }
	
	public function downloadCSVForReport($from,$to,$clientIds = '',$categoryId,$subCategoryId = '') {
		
		$smartModel = $this->load->model('smartmodel');
		
		$data = $smartModel->getClientDetails($clientIds, $categoryId, $subCategoryId, $from, $to);
		
		$finalData = $this->createClientDataArray($data);

		$ColumnList = $this->getCSVColumnList();
        $csv = '';
        foreach ($ColumnList as $ColumnName) {
            $csv .= '"' . $ColumnName . '",';
        }
		
		$csv .= "\n";
        foreach ($finalData as $dataArray) {
            foreach ($ColumnList as $ColumnName) {
            	$csv .= '"' . trim($dataArray[$ColumnName]) . '",';
            }
           	$csv .= "\n";
        }
        
        return $csv;
	}
	
	public function getCSVColumnList() {
		$columnListArray = array();
		$columnListArray[] = 'Client ID';
		$columnListArray[] = 'Transaction ID';
		$columnListArray[] = 'Client Name';
		$columnListArray[] = 'Client City';
		$columnListArray[] = 'Institute ID';
		$columnListArray[] = 'Institute Name';
		$columnListArray[] = 'Institute City';
		$columnListArray[] = 'Sales Representative Name';
		$columnListArray[] = 'Sales Representative Branch';
		$columnListArray[] = 'Sub Category';
		$columnListArray[] = 'Total Transaction Price';
		$columnListArray[] = 'Listing Revenue';
		$columnListArray[] = 'Sticky Revenue';
		$columnListArray[] = 'Category Sponsor Banner Revenue';
		$columnListArray[] = 'Other Banners Revenue';
		$columnListArray[] = 'Mailer Revenue';
		$columnListArray[] = 'MR Consumed Revenue';
		$columnListArray[] = 'Subscription Start Date';
		$columnListArray[] = 'Subscription End Date';
		$columnListArray[] = 'Days Remaining From the Date of Generating Report Till End Date';
		$columnListArray[] = 'Last Year Mailer Responses';
		$columnListArray[] = 'Last Year Non-Mailer Responses';
		$columnListArray[] = 'Last Year Total Responses';
		$columnListArray[] = 'This Year Mailer Responses';
		$columnListArray[] = 'This Year Non-Mailer Responses';
		$columnListArray[] = 'This Year Total Responses';
		$columnListArray[] = 'Last 30 Days Mailer Responses';
		$columnListArray[] = 'Last 30 Days Non-Mailer Responses';
		$columnListArray[] = 'Last 30 Days Total Responses';
		$columnListArray[] = 'MR Delivered Till Date';
		$columnListArray[] = 'Projected Responses';
		
		return $columnListArray;
	}
	
	public function createClientDataArray($data = array()){
		
		$returnDataArray = array();
		
		foreach ($data as $clientId => $clientData){
			
			if(empty($clientData['Product Details']))
				continue;
			
			foreach ($clientData['Product Details'] as $productData){
				
				$formattedClientDetails = array();
				$formattedClientDetails['Client ID'] = $clientData['Client ID'];
				$formattedClientDetails['Transaction ID'] = $productData['Transaction ID'];
				$formattedClientDetails['Client Name'] = $clientData['Client Name'];
				$formattedClientDetails['Client City'] = ($clientData['Client City']) ? ($clientData['Client City']) : 'NA';

				$formattedClientDetails['Institute ID'] = ($clientData['Institute ID']) ? ($clientData['Institute ID']) : 'NA';
				$formattedClientDetails['Institute Name'] = ($clientData['Institute Name']) ? ($clientData['Institute Name']) : 'NA';
				$formattedClientDetails['Institute City'] = ($clientData['Institute City']) ? ($clientData['Institute City']) : 'NA';

				$formattedClientDetails['Sales Representative Name'] = ($productData['SR Name']) ? ($productData['SR Name']) : 'NA';
				$formattedClientDetails['Sales Representative Branch'] = ($productData['SR Branch']) ? ($productData['SR Branch']) : 'NA';
				$formattedClientDetails['Sub Category'] = ($productData['Course Sub Category']) ? ($productData['Course Sub Category']) : 'NA';
				
				$formattedClientDetails['Listing Revenue'] = $productData['Listing Revenue'];
				$formattedClientDetails['Sticky Revenue'] = $productData['Sticky Revenue'];
				$formattedClientDetails['Category Sponsor Banner Revenue'] = $productData['Banner Revenue'];
				$formattedClientDetails['Other Banners Revenue'] = $productData['Other Banners Revenue'];
				$formattedClientDetails['Mailer Revenue'] = $productData['Mailer Revenue'];
				
				$formattedClientDetails['Total Transaction Price'] = $productData['Total Transaction Price'];

				$formattedClientDetails['MR Consumed Revenue'] = $productData['Credit Consumed'];
				$formattedClientDetails['MR Delivered Till Date'] = $productData['View Count'];

				$formattedClientDetails['Subscription Start Date'] = min($productData['Start Date']);
				$formattedClientDetails['Subscription End Date'] = max($productData['End Date']);
				
				$dateDiff = date_diff(date_create(date('Y-m-d')),date_create($formattedClientDetails['Subscription End Date']));
				if($formattedClientDetails['Subscription End Date'] <= date('Y-m-d')) {
					$formattedClientDetails['Days Remaining From the Date of Generating Report Till End Date'] = 0;
				} else {
					$formattedClientDetails['Days Remaining From the Date of Generating Report Till End Date'] = $dateDiff->format('%a');
				}				
				
				foreach ($clientData['Mailer Responses'] as $listingId => $MailerResponsesData){
					
					$courseIds = explode(',',$productData['Course ID']);
					
					if(in_array($listingId,$courseIds)){
						$formattedClientDetails['Last Year Mailer Responses'] += $MailerResponsesData['Last Year Count'];
						$formattedClientDetails['Last Year Total Responses'] = $formattedClientDetails['Last Year Mailer Responses'];
						$formattedClientDetails['This Year Mailer Responses'] += $MailerResponsesData['This Year Count'];
						$formattedClientDetails['This Year Total Responses'] = $formattedClientDetails['This Year Mailer Responses'];
						$formattedClientDetails['Last 30 Days Mailer Responses'] += $MailerResponsesData['Last 30 Days Count'];
						$formattedClientDetails['Last 30 Days Total Responses'] = $formattedClientDetails['Last 30 Days Mailer Responses'];
					}
				}
				
				foreach ($clientData['Non-Mailer Responses'] as $listingId => $NonMailerResponsesData){
					
					$courseIds = explode(',',$productData['Course ID']);
					
					if(in_array($listingId,$courseIds)){
						$formattedClientDetails['Last Year Non-Mailer Responses'] += $NonMailerResponsesData['Last Year Count'];
						$formattedClientDetails['Last Year Total Responses'] += $formattedClientDetails['Last Year Non-Mailer Responses'];
						$formattedClientDetails['This Year Non-Mailer Responses'] += $NonMailerResponsesData['This Year Count'];
						$formattedClientDetails['This Year Total Responses'] += $formattedClientDetails['This Year Non-Mailer Responses'];
						$formattedClientDetails['Last 30 Days Non-Mailer Responses'] += $NonMailerResponsesData['Last 30 Days Count'];
						$formattedClientDetails['Last 30 Days Total Responses'] += $formattedClientDetails['Last 30 Days Non-Mailer Responses'];
					}
				}
				
				$formattedClientDetails['Projected Responses'] = (int) ($formattedClientDetails['Days Remaining From the Date of Generating Report Till End Date'] * (($formattedClientDetails['Last 30 Days Total Responses'])/30));
				
				$formattedClientDetails['Last Year Mailer Responses'] = $formattedClientDetails['Last Year Mailer Responses'];
				$formattedClientDetails['Last Year Non-Mailer Responses'] = $formattedClientDetails['Last Year Non-Mailer Responses'];
				$formattedClientDetails['Last Year Total Responses'] = $formattedClientDetails['Last Year Total Responses'];
				
				$formattedClientDetails['This Year Mailer Responses'] = $formattedClientDetails['This Year Mailer Responses'];
				$formattedClientDetails['This Year Non-Mailer Responses'] = $formattedClientDetails['This Year Non-Mailer Responses'];
				$formattedClientDetails['This Year Total Responses'] = $formattedClientDetails['This Year Total Responses'];
				
				$formattedClientDetails['Last 30 Days Mailer Responses'] = $formattedClientDetails['Last 30 Days Mailer Responses'];
				$formattedClientDetails['Last 30 Days Non-Mailer Responses'] = $formattedClientDetails['Last 30 Days Non-Mailer Responses'];
				$formattedClientDetails['Last 30 Days Total Responses'] = $formattedClientDetails['Last 30 Days Total Responses'];
				
				$formattedClientDetails['Projected Responses'] = $formattedClientDetails['Projected Responses'];
				
				$returnDataArray[] = $formattedClientDetails;
			}
			
		}
		
		return $returnDataArray;
	}
	public function downloadClientListingDetails($clientId,$email,$isAccessAllowed){
		$csv  ="";
		$emptyCSV = false;
		if(!$isAccessAllowed){
			$csv = "You do not have access to this Client's Details ! ";
			$emptyCSV = true;
		} else {
			$listingsData  = $this->getClientsListingDetails($clientId,$email);
			
			if(!empty($listingsData)){
				$csv = $this->generateClientDetailsCSV($listingsData);
    		
			} else {
				$csv = "No Data found for given Client Details.";
				$emptyCSV = true;
			}
		}
		if($clientId!=null && !$emptyCSV ){
		 	$filename = "ListingDetails_".$clientId.".csv";
		} else {
			$filename = "ListingDetails.csv";
		}
       	$mime = 'text/x-csv';
   		header('Content-Type: "'.$mime.'"');
   		header('Content-Disposition: attachment; filename="'.$filename.'"');
   	 	header("Content-Transfer-Encoding: binary");
    	header('Expires: 0');
    	header('Pragma: no-cache');
    	header("Content-Length: ".strlen($csv));
    	echo $csv;
        
	}
	public function getClientsListingDetails($clientId,$emailId){
		$listingModel = $this->load->model('listing/listingmodel');
		$clientCourseDetails = array();
		if($clientId!=null){
			$clientCourseIds = $listingModel->getClientCourseIds($clientId);
			if(!empty($clientCourseIds)){
				$this->load->builder("nationalCourse/CourseBuilder");
		    	$builder          = new CourseBuilder();
		    	$courseRepository = $builder->getCourseRepository();
		    	$courseObjs =  $courseRepository->findMultiple($clientCourseIds , array('basic'), false, false);
		    	foreach ($courseObjs as $courseId => $courseObj) {
		    		$courseDetails = array();
		    		$courseDetails['Client Email'] = $emailId;
		    		$courseDetails['Client Id'] = $clientId;
		    		$courseDetails['Course Id'] = $courseId;
		    		$courseDetails['Course Name'] = $courseObj->getName();
		    		$courseDetails['Primary UILP Id'] = $courseObj->getInstituteId();
		    		$courseDetails['Primary UILP Name'] = $courseObj->getInstituteName();
		    		$courseDetails['Primary UILP Type'] = $courseObj->getInstituteType();
		    		$courseDetails['Parent UILP Id'] = $courseObj->getParentInstituteId();
		    		$courseDetails['Parent UILP Name'] = $courseObj->getParentInstituteName();
		    		$courseDetails['Parent UILP Type'] = $courseObj->getParentInstituteType();
		    		if($courseObj->isPaid()){
		    			$courseDetails['Current Subscription Status'] = "Paid";	
		    		} else {
		    			$courseDetails['Current Subscription Status'] = "Free";	
		    		}
		    		$clientCourseDetails[]= $courseDetails;
		    	}
		    }
		}
	    return $clientCourseDetails;

	}

	public function generateClientDetailsCSV($data){

		$columnList = array();
		$columnList[] = 'Client Email';
		$columnList[]= 'Client Id';
		$columnList[]='Course Id';
		$columnList[]='Course Name';
		$columnList[]='Primary UILP Id';
		$columnList[]='Primary UILP Name';
		$columnList[]='Primary UILP Type';
		$columnList[]='Parent UILP Id';
		$columnList[]='Parent UILP Name';
		$columnList[]='Parent UILP Type';
		$columnList[]='Current Subscription Status';

		
    	foreach ($columnList as $ColumnName)
    	{
			$csv .= '"'.$ColumnName.'",';
		}
		$csv .= "\n";
		foreach ($data as $courseDetail)
    	{	
    		foreach ($columnList as $ColumnName)
    		{
    			$csv .= '"'.$courseDetail[$ColumnName].'",';

    		}
    		$csv .= "\n";
    	}
    	return $csv;
	}


	public function accessClientListings()
    {	
		$this->_checkSMARTInterfaceMode('AccessClientListingDetails');
		$this->_validateuser();
		$this->_getUserMappings();

		$data = array();
		$data['headerContentaarray'] = $this->_loadHeaderContent(ACCESS_CLIENT_LISTING_DETAILS_TAB_ID);
		$data['footerContentaarray'] = $this->_loadfooterContent();
		$this->load->view('smart/AccessClientListingDetails',$data);
    }

    public function getClientListingData()

    {	
    	$this->_validateuser();
    	$userId = $this->_validateuser['userid'];
    	$email = $this->input->post('loginEmail');
    	$clientId = $this->input->post('clientId');
    	if($clientId !=="" || $clientId!=null){	
	    	$this->load->model('user/usermodel');
	    	$email = $this->usermodel->getEmailIdByClientId($clientId);
    	}
    	if($email!=="" || $email!=null && ($clientId =="" || $clientId == null)){
    		$this->load->model('user/usermodel');
    		$clientId = $this->usermodel->getUserIdByEmail($email);
    	}

    	$isAccessAllowed = $this->verifyAccessToClientId($userId,$clientId);
    	
    	$this->downloadClientListingDetails($clientId,$email,$isAccessAllowed);
    }

    public function verifyAccessToClientId($userId,$clientId){
    		$smartModel = $this->load->model('smartmodel');
    		$this->executiveHierarchy = $smartModel->getSubordinatesForExecutive(array($userId), False);
			$executives = $smartModel->getSubordinatesForExecutive(array($userId), True);
			array_push($executives, $userId);
			$clientsSalesMapping = array();
			$clientsSalesMapping = $smartModel->getClientsForSalesPerson($executives);
			$this->executiveClientMapping = $clientsSalesMapping['executiveClientMapping'];
			$clients = $clientsSalesMapping['clientsArray'];
			if(in_array($clientId, $clients)){
				return true;
			} else {	
				return false;
			}

    }
}
