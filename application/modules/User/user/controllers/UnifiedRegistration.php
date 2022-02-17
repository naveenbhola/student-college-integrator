<?php
/**
 * Copyright 2011 Info Edge India Ltd
 * $id:$
 * $Author: $
 * $Date: $
 * This class renders different types of user registration froms across the website.
 */

//ini_set('display_errors','1');
/**
 * Copyright 2011 Info Edge India Ltd
 * $id:$
 * $Author: $
 * $Date: $
 * This class renders different types of user registration froms across the website.
 */

class UnifiedRegistration extends MX_Controller{
	
	private $userStatus=null;
	
	/**
	 * Loads required library and instantiate MultipleMarketingPageClient
	 *
	 * @access	private
	 * @return	void
	 */
	private function init() {
		$this->load->library(array('LDB_Client','category_list_client','listing_client','MY_sort_associative_array'));
		// Load all required library here

	}
	/**
	 * Default method which is getting called by code ignitor, this method will render different layout forms
	 *
	 * @param	string $typeOfForm
	 * @access	public
	 * @return	void
	 */

	public function index($typeOfForm) {
		$data = $this->_getdataForPage();
		// Render 2 and 1/2 kg code of marketing page :(
		//loading template code starts here, we have basically three types of main forms thtat will be used in case of unified registartion
		if($typeOfForm !='') {
			//Value of type can be 1 or 2 or 3 :)
			if($typeOfForm == 3) {
				$data = $this->studyAbroad();
			}
			$this->load->view('user/unifiedregistration/unifiedRegistrationFormType'.$typeOfForm,$data);
		} else {
			//Load default form, that we need to decide
		}
	}
	/**
	 * Loads UG, PG and Local courses form
	 *
	 * @param string $formName
	 * @param string $category_id
	 * @access	public
	 * @return	void
	 */

	public function loadFormUsingAjax($formName, $category_id = '',$trackingPageKeyId='') {
		//loading different forms
		error_log("**********formName is as ".$formName);
		if($formName == 'location') {
			$data = $this->_getdataForPage();
			echo $this->load->view('user/unifiedregistration/unifiedCityOverlay',$data);
		} else if($formName == 'pg_without_name') {
			$data = $this->_getdataForPage();
			echo $this->load->view('user/unifiedregistration/user_form_unified_without_name_pg',$data);
		} else if($formName == 'ug_without_name') {
			$data = $this->_getdataForPage();
			echo $this->load->view('user/unifiedregistration/user_form_unified_without_name_ug',$data);
		} else if($formName == 'localug_without_name') {
			$data = $this->_getdataForPage();
			echo $this->load->view('user/unifiedregistration/user_form_unified_without_name_localug',$data);
		} else if($formName == 'localpg_without_name') {
			$data = $this->_getdataForPage();
			echo $this->load->view('user/unifiedregistration/user_form_unified_without_name_localpg',$data);
		} else if($formName == 1 || $formName == 2 || $formName == 3) {
                        $data = $this->_getdataForPage();
                        $data['category_unified'] = $category_id;
                        $data['trackingPageKeyId'] = $trackingPageKeyId;
                        if($formName == 3) {
                        	$data = $this->studyAbroad();
                        }
			if($category_id == '14')
			{
				$data['pagename'] = 'testpreppage';
			}	
                        echo $this->load->view('user/unifiedregistration/unifiedRegistrationFormType'.$formName,$data);
                }
	}
        /**
	 * Method that gets user type, ldb or non-ldb
	 *
	 * @access	public
	 * @return	void
	 */
        public function checkUserType() {
         // need to call is_ldb method to check whether user is a ldb or not
         echo "true";
        }
	/**
	 * Renders data for city list
	 *
	 * @access	public
	 * @return	array
	 */
	private function _select_city_list() {
		$this->init();
		$categoryClient = new Category_list_client();
		$cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
		$sorter = new MY_sort_associative_array;
		$finalArray = array();
		foreach ($cityListTier1 as $list) {
			if ($list['stateId'] == '-1') {
				$finalArray['virtualCity'][] = json_decode($categoryClient->getCitiesForVirtualCity(1,$list['cityId']),true);
			} else {
				$finalArray['metroCity'][] = $list;
			}
		}
		$string ='';
		$finalArray['virtualCity']  = $sorter->sort_associative_array($finalArray['virtualCity'], 'city_name');
		foreach ($finalArray['virtualCity'] as $list) {
			foreach ($list as $key) {
				if ($key['virtualCityId'] == $key['city_id']) {
					$string .='<OPTGROUP LABEL="'.$key['city_name'].'">';
					foreach ($finalArray['virtualCity'] as $list1) {
						$list1  = $sorter->sort_associative_array($list1, 'city_name');
						foreach ($list1 as $key1) {
							if ($key1['virtualCityId'] != $key1['city_id']) {
				    if ($key['city_id'] == $key1['virtualCityId']) {
				    	$string .='<OPTION title="'.$key1['city_name'].'" value="'.$key1['countryId'] . ':' . $key1['state_id'] . ':' . $key1['city_id'].'">'.$key1['city_name'].'</OPTION>';
				    }
							}
						}
					}
				}
			}
		}
		$string .='<OPTGROUP LABEL="Metro Cities">';
		$finalArray['metroCity'] = $sorter->sort_associative_array($finalArray['metroCity'] , 'cityName');
		foreach ($finalArray['metroCity'] as $key1) {
			$string .='<OPTION title="'.$key1['cityName'].'" value="'.$key1['countryId'] . ':' . $key1['stateId'] . ':' . $key1['cityId'].'">'.$key1['cityName'].'</OPTION>';
		}
		$ldbObj = new LDB_Client();
		$listing_client = new listing_client();
		$country_state_city_list = json_decode($ldbObj->sgetCityStateList(12),true);
		foreach($country_state_city_list as $list)
		{
			if($list['CountryId'] == 2)
			{
				foreach($list['stateMap'] as $list2)
				{
					$string .='<OPTGROUP LABEL="'.$list2['StateName'].'">';
					foreach($list2['cityMap'] as $list3)
					{

						$string .='<OPTION title="'.$list3['CityName'].'" value="'.$list['CountryId'] . ':' . $list2['StateId'] . ':' . $list3['CityId'].'">'.$list3['CityName'].'</OPTION>';
					}
				}
			}
		}
		return $string;
	}
	/**
	 * Main method that renders all related data
	 *
	 * @param integer $userid
	 * @access	public
	 * @return	array
	 */
	private function _marketing_page($userid) {

		$this->init();
		$data = array();
		$appId = 1;
		$ldbObj = new LDB_Client();
		$listing_client = new listing_client();
		$data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12),true);
		$cityListTier = $data['country_state_city_list'];
		$course_list = json_decode($ldbObj->sgetCourseList($appId,3),true);
		$distance_course = json_decode($ldbObj->sgetSpecializationListByParentId($appId,24),true);
		$data['distance_course'] = $distance_course;
		$userCompleteDetails = '';
		if($userid != '')
		$userCompleteDetails = $ldbObj->sgetUserDetails($appId,$userid);
		$userDataToShow = $this->makeUserData($userCompleteDetails);
		$countryCityStateMaps = $this->getCityStatesFromMap($cityListTier);
		$data['countriesList'] = $countryCityStateMaps['countries'];
		$data['statesList'] = $countryCityStateMaps['states'];
		$data['citiesList'] = $countryCityStateMaps['cities'];
		$data['userDataToShow'] = $userDataToShow;
		$data['newDesiredCourse_list'] = $course_list;
		$data['userCompleteDetails'] = $userCompleteDetails;
		$data['CitiesWithCollege'] = $listing_client->getCitiesWithCollege(1,2);
		// flag for JSB9 Tracking
		$data['trackForPages'] = true;
		$data['other_exm_list'] = $this->_other_exm_list();
		$data['work_exp_combo'] = $this->_work_exp_combo();
		if($userid != '') {
			$userCompleteDetails = $this->make_array(json_decode($userCompleteDetails,true));
			$TimeOfStart =
			$userCompleteDetails[0]['PrefData'][0]['TimeOfStart'];
			$data['when_you_plan_start'] = $this->_when_you_plan_start();
			$array_ug_courses = $userCompleteDetails[0]['EducationData'];
			$PrefData = $userCompleteDetails[0]['PrefData'];
			foreach ($PrefData as $value) {
				if (count($value['LocationPref']) > 0) {
					$data['LocalityCityValue'] = $value['LocationPref'][0]['CountryId'].":".$value['LocationPref'][0]['StateId'].":".$value['LocationPref'][0]['CityId'].":".$value['LocationPref'][0]['LocalityId'];
					$data['LocalityCityName'] = $value['LocationPref'][0]['LocalityName'];
				}
			}
			$name_ug_course = '';
			$ug_marks = '';
			$CourseCompletionDate = '';
			foreach ($array_ug_courses as $data_ug) {
				if ($data_ug['Level'] == 'UG') {
					$name_ug_course = $data_ug['Name'];
					$ug_marks = $data_ug['Marks'];
					$CourseCompletionDate = $data_ug['CourseCompletionDate'];
					$ug_city_id = $data_ug['City'];
					$ug_institute_id = $data_ug['InstituteId'];
					$ug_institute_name = trim($data_ug['institute_name']);
				}
			}
			$data['course_lists'] = $this->_course_lists();
			$data['ug_marks'] = $ug_marks;
			$data['CourseCompletionDate'] = $CourseCompletionDate;
			$data['ug_city_id'] = $ug_city_id;
			$data['ug_institute_id'] = $ug_institute_id;
			$data['ug_institute_name'] = $ug_institute_name;
		} else {
			$data['when_you_plan_start'] = $this->_when_you_plan_start();
			$data['course_lists'] = $this->_course_lists();
		}
		return $data;
	}
	/**
	 * Filters user data
	 *
	 * @param array $userCompleteDetails
	 * @access	public
	 * @return	array
	 */
	private function makeUserData($userCompleteDetails) {
		$userCompleteDetails = $this->make_array(json_decode($userCompleteDetails,true));
		$userprefarray = $userCompleteDetails[0]['PrefData'][0];
		$usereduarray = $userCompleteDetails[0]['EducationData'];
		$userlocpref = $userCompleteDetails[0]['PrefData'][0]['LocationPref'];
		$statearray = array();
		$cityarray = array();

		$userarray = array('name'=>isset($userCompleteDetails[0]['firstname']) ? $userCompleteDetails[0]['firstname']:'',
		   'email'=>isset($userCompleteDetails[0]['email']) ? $userCompleteDetails[0]['email']:'',
		   'emailenable'=>(isset($userCompleteDetails[0]['email']) && trim($userCompleteDetails[0]['email']) != '') ? 'disabled':'',
		   'mobile'=>(isset($userCompleteDetails[0]['mobile']) && trim($userCompleteDetails[0]['mobile']) != '') ? $userCompleteDetails[0]['mobile']:'',
		   'cityid'=>(isset($userCompleteDetails[0]['city']) && trim($userCompleteDetails[0]['city']) > 0) ? $userCompleteDetails[0]['city']:'',
		   'experience'=>(isset($userCompleteDetails[0]['experience'])) ? $userCompleteDetails[0]['experience']:'',
		  'AICTE' => (isset($userprefarray['DegreePrefAICTE']) && $userprefarray['DegreePrefAICTE'] == 'yes') ? 'checked' :'',
		  'UGC' => (isset($userprefarray['DegreePrefUGC']) && trim($userprefarray['DegreePrefUGC']) == 'yes') ? 'checked' :'',
		  'International' => (isset($userprefarray['DegreePrefInternational']) && $userprefarray['DegreePrefInternational'] == 'yes') ? 'checked' :'',
		  'Anydegree' => (isset($userprefarray['DegreePrefAny']) && $userprefarray['DegreePrefAny'] == 'yes') ? 'checked' :'',
		  'fulltime' => (isset($userprefarray['ModeOfEducationFullTime']) && $userprefarray['ModeOfEducationFullTime'] == 'yes') ? 'checked' :'',
		  'parttime' => (isset($userprefarray['ModeOfEducationPartTime']) && $userprefarray['ModeOfEducationPartTime'] == 'yes') ? 'checked' :'',
		  'distance' => (isset($userprefarray['ModeOfEducationDistance']) && $userprefarray['ModeOfEducationDistance'] == 'yes') ? 'checked' :'',
		);
		for($i=0;$i<count($userlocpref);$i++)
		{
			if($userlocpref[$i]['CityId'] == -1  || $userlocpref[$i]['CityId'] == 0)
			$statearray[] = $userlocpref[$i]['StateId'];
			else
			{
				if($userlocpref[$i]['CityId'] != NULL)
				$cityarray[] = $userlocpref[$i]['CityId'];
			}
		}

		$userarray['statearray'] = $statearray;
		$userarray['cityarray'] = $cityarray;
		for($j = 0;$j<count($usereduarray);$j++)
		{
			if($usereduarray[$j]['Level'] == 'Competitive exam')
			{
				$arrcolname = $usereduarray[$j]['Name'];
				$userarray[$arrcolname] = $usereduarray[$j]['Marks'];
			}
			else
			{
				$completiondate = getdate($usereduarray[$j]['CourseCompletionDate']);
				$arrcolname = $usereduarray[$j]['Level'];
				$userarray[$arrcolname.'details'] = $usereduarray[$j]['Name'];
				$userarray[$arrcolname.'ongoing'] = '';
				$userarray[$arrcolname.'completed'] = '';
				if($usereduarray[$j]['OngoingCompletedFlag'] == '0') {
					$userarray[$arrcolname.'completed'] = 'checked';
				} else {
					$userarray[$arrcolname.'ongoing'] = 'checked';
				}
				$userarray[$arrcolname.'city'] = $usereduarray[$j]['City'];
				$userarray[$arrcolname.'institute'] = $usereduarray[$j]['InstituteId'];
				$userarray[$arrcolname.'marks'] = $usereduarray[$j]['Marks'];
				$userarray[$arrcolname.'completionmonth'] = $completiondate['mon'];
				$userarray[$arrcolname.'completionyear'] = $completiondate['year'];
			}
		}
		return json_encode($userarray);
	}
	/**
	 * Filters array
	 *
	 * @param array $an_array
	 * @access	public
	 * @return	array
	 */
	private function make_array($an_array) {
		$return_array = array();
		foreach ($an_array as $key => $val) break;
		$return_array[] = $val;
		return $return_array;
	}
	/**
	 * Populates city list
	 *
	 * @param array $countryStateCityMap
	 * @access	public
	 * @return	array
	 */
	private function getCityStatesFromMap($countryStateCityMap) {
		$cities = $states = $countries = array();
		foreach($countryStateCityMap as $country) {
			$countryName = $country['CountryName'];
			$countryId = $country['CountryId'];
			$countries[$countryId]['name'] = $countryName;
			$countries[$countryId]['value'] = $countryId .':0:0'; // For TUserLocationPref
			$statesForCountry = $country['stateMap'];
			foreach($statesForCountry as $state) {
				$stateName = $state['StateName'];
				$stateId = $state['StateId'];
				if (!empty($stateId)) {
					$states[$stateId]['name'] = $stateName;
					$states[$stateId]['value'] = $countryId .':'. $stateId .':0';
				}
				$citiesForState = $state['cityMap'];
				foreach($citiesForState as $city) {
					$cityName = $city['CityName'];
					$cityId = $city['CityId'];
					$cityTier = $city['Tier'];
					$cities['tier'. $cityTier][$cityId]['name'] = $cityName;
					$cities['tier'. $cityTier][$cityId]['value'] = $countryId  .':'. $stateId .':'. $cityId;
				}
			}
		}
		return array('cities' => $cities, 'states' => $states, 'countries' => $countries);
	}
	/**
	 * Populates other exam taken drop down
	 *
	 * @param string $selected
	 * @param boolean $flag
	 * @access	public
	 * @return	string
	 */
	private function _other_exm_list($selected = NULL,$flag = FALSE) {
		$array = array('Management-Label','CAT','MAT','XAT','UGAT','Engineering-Label','IITJEE','GATE','International Exams-Label','TOEFL','IELTS','GRE','GMAT');
		$string = '';
		for ($i = 0; $i < count($array);$i++)  {
			if (strpos($array[$i], 'Label')) {
				$new_array = explode('-', $array[$i]);
				$string .='<OPTGROUP LABEL="'.$new_array[0].'">';
			} else {
				if ($selected != NULL) {
					if ($selected == $array[$i] ) {
						$selected_string = "selected";
					} else {
						$selected_string = "";
					}
				}
				$string .='<OPTION '.$selected_string.' value="'.$array[$i].'">'.$array[$i].'</OPTION>';
			}
		}
		if ($flag) {
			return $array;
		} else {
			return $string;
		}
	}
	/**
	 * Populates work experience drop down
	 *
	 * @param string $selected
	 * @param boolean $flag
	 * @access	private
	 * @return	string
	 */
	private function _work_exp_combo($selected = NULL,$flag = FALSE) {
		$string = '';
		$string .='<option '.$selected_string.' value="-1">No Experience</option>';
		for ($i = 0; $i <= 10;$i++)  {
			if ($selected != NULL) {
				if ($selected == $i ) {
					$selected_string = "selected";
				} else {
					$selected_string = "";
				}
			}

			if ($i == 0) {
				$string .='<option '.$selected_string.' value="'.$i.'">< 1 year</option>';
			} elseif ($i == 10) {
				$string .='<option '.$selected_string.' value="'.$i.'">> 10 years</option>';
			} else {
				$string .='<option '.$selected_string.' value="'.$i.'">'.$i.' - '.($i+1).' years</option>';
			}
		}
		if ($flag) {
			return $array;
		} else {
			return $string;
		}
	}
	/**
	 * Populates plan to start drop down
	 *
	 * @param string $selected
	 * @param boolean $flag
	 * @access	public
	 * @return	string
	 */
	private function _when_you_plan_start($selected = NULL,$flag = FALSE) {
		$array= array(
		date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+0)) => date('Y',strtotime('+0 year')),
		date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+1)) => date('Y',strtotime('+1 year')),
		date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+2)) => date('Y',strtotime('+2 year')),
		'0000-00-00 00:00:00' => 'Not Sure',
		);
		$string = '';
		foreach ($array as $key => $value)  {
			if ($selected != NULL) {
				if ($selected == $key ) {
					$selected_string = "selected";
				} else {
					$selected_string = "";
				}
			}
			$string .='<option '.$selected_string.' value="'.$key.'">'.$value.'</option>';
		}
		if ($flag) {
			return $array;
		} else {
			return $string;
		}
	}
	/**
	 * Used to render data for study abroad page templates
	 *
	 * @param string $pagename
	 * @param string $url
	 * @access	public
	 * @return	array
	 */
	public function studyAbroad($pagename='studyAbroad',$url='') {
		$this->init();
		$validity = $this->checkUserValidation();
		global $logged;
		global $userid;
		global $usergroup;
		$thisUrl = $_SERVER['REQUEST_URI'];
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
		} else {
			$logged = "Yes";
		}
		$data = array();
		$userName = '';
		$userEmail = '';
		$userInterest = -1;
		$userPassword = '';
		$backPageUrl = $url;
		$data['logged'] = $logged;
		$data['userName'] = $userName;
		$data['userEmail'] = $userEmail;
		$data['userInterest'] = $userInterest;
		$data['userPassword'] = $userPassword;
		$data['logged'] = $logged;
		$data['userData'] = $validity;
		$data['extraPram'] = $extraPram;
		$data['backPage'] = $backPageUrl;

		$data['pageName'] = 'studyAbroad';
		$category_list_client = new Category_list_client();
		$categoryList = $category_list_client->getCategoryList(1,1);
		$catgeories = array();
		foreach($categoryList as $category) {
                    if($category['categoryID'] != 11)
			$categories[$category['categoryID']] = $category['categoryName'];
		}
		asort($categories);
		$data['categories'] = $categories;

		$regions= json_decode($category_list_client->getCountriesWithRegions(1), true);
		$data['regions'] = $regions;
		$data['course_lists'] = $this->_course_lists();

		$cityListTier1 = $category_list_client->getCitiesInTier($appId,1,2);
		$cityListTier2 = $category_list_client->getCitiesInTier($appId,2,2);
		$cityListTier3 = $category_list_client->getCitiesInTier($appId,0,2);
		$data['cityTier2'] = $cityListTier2;
		$data['cityTier3'] = $cityListTier3;
		$data['cityTier1'] = $cityListTier1;
		$data['allCategories'] = $categoryList;
		$ldbObj = new LDB_Client();
		$data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12),true);
		return $data;
	}
	/**
	 * Used to populate desired course dropdown at front end
	 *
	 * @param integer $categoryId
	 * @access	public
	 * @return	string
	 */
	public function populateDesiredCourseDropDown ($categoryId) {
		//load category list client
		$this->init();
		
		$this->load->model('registration/registrationmodel');
		$coursePageSpecificCourses = $this->registrationmodel->getCoursePageSpecificCourses();
		
		$ldbObj = new LDB_Client();
		$categoryClient = new Category_list_client();
		if($categoryId==-1 || $categoryId ==14) {
			$course_list = $categoryClient->getTestPrepCoursesList(1);
		} else if($categoryId==3) {
			$management_saved_courses = json_decode($ldbObj->sgetCourseList($appId,3),true);
			$distance_course = json_decode($ldbObj->sgetSpecializationListByParentId($appId,24),true);
			$course_list = array_merge($management_saved_courses,$distance_course);
		} else {
			$course_list = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1,$categoryId),true);
		}
		//echo "<pre>";print_r($course_list);echo "</pre>";
		if($categoryId==3) {
		foreach($course_list as $management_course) {
		if(!in_array($management_course['SpecializationId'],$coursePageSpecificCourses)) {	
        usort($management_course, "cmp");
        if ($management_course['ParentId'] == 1)
// 	  	if($management_course['SpecializationName']=='All')
// 	  	$management_course['CourseName'] = $management_course['CourseName'];
// 	  	else {
// 	  		$management_course['CourseName'] = $management_course['SpecializationName'];
// 	  	}
	  	// populate desired course dropdown for MBA courses
// 	  	if(!empty($course_list[0]) && $management_course['SpecializationId']!=24){
	  		//strange logic to reset specialization id for MBA/PGDM course
	  		//if($management_course['SpecializationId']==20)
	  		//$management_course['SpecializationId'] = 2;
	  		// check made for distance MBA, set it as local
	  		if ( $management_course['CourseReach'] == 'national') {
	  			if ($management_course['CourseLevel1'] == 'UG') {
	  				$string1 .='<option CourseReach="national" CourseLevel1="UG"
				title="'.$management_course['CourseName'].'"  '.$selected_string.'
				groupid="'.$management_course['groupId'].'"
				categoryid="'.$management_course['CategoryId'].'"
				value="'.$management_course['SpecializationId'].'">'.$management_course['CourseName'].'</
				option>';
	  			} else if ($management_course['CourseLevel1'] == 'PG') {
	  				$string1 .='<option CourseReach="national" CourseLevel1="PG"
				title="'.$management_course['CourseName'].'"  '.$selected_string.'
				groupid="'.$management_course['groupId'].'"
				categoryid="'.$management_course['CategoryId'].'"
				value="'.$management_course['SpecializationId'].'">'.$management_course['CourseName'].'</
				option>';
	  			}
	  			// check made for distance MBA, set it as local
	  		} else if($management_course['CourseReach'] == 'local')  {
	  			$string1 .='<option CourseReach="local" CourseLevel1 = "'.$management_course['CourseLevel1'].'"
				title="'.$management_course['CourseName'].'"  '.$selected_string.'
				groupid="'.$management_course['groupId'].'"
				categoryid="'.$management_course['CategoryId'].'"
				value="'.$management_course['SpecializationId'].'">'.$management_course['CourseName'].'</
				option>';
	  		}
	  	$string = $string1;
	  }
		}
		}
		if($categoryId!=3) {
			//echo "<pre>";
			//print_r($course_list);
			//echo "</pre>";
			//make course list drop down
	  foreach ($course_list as $groupId => $value) {
	  	$string2 = $groupName = '';
	  	usort($value, "cmp");
	  	foreach ($value as $finalArray) {
			if(!in_array($finalArray['SpecializationId'],$coursePageSpecificCourses)) {
            if (strtolower($finalArray['SpecializationName']) == 'all' && strtolower($finalArray['CourseName']) != 'all')
	  		if ( $finalArray['CourseReach'] == 'national') {
	  			if ($finalArray['CourseLevel1'] == 'UG') {
	  				// change Distance BCA's course reach as local
	  				if ($finalArray['CourseName'] == 'Distance BCA') {
	  					$string2 .='<option CourseReach="local" CourseLevel1="UG"
						title="'.$finalArray['CourseName'].'"  '.$selected_string.'
						groupid="'.$finalArray['groupId'].'"
						categoryid="'.$finalArray['CategoryId'].'"
						value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
						option>';
	  				} else {
	  					$string2 .='<option CourseReach="national" CourseLevel1="UG"
						title="'.$finalArray['CourseName'].'"  '.$selected_string.'
						groupid="'.$finalArray['groupId'].'"
						categoryid="'.$finalArray['CategoryId'].'"
						value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
						option>';
	  				}
	  			} else if ($finalArray['CourseLevel1'] == 'PG') {
	  				// change Distance MCA 's course reach as local
	  				if ($finalArray['CourseName'] == 'Distance MCA') {
	  					$string2 .='<option CourseReach="local" CourseLevel1="PG"
						title="'.$finalArray['CourseName'].'"  '.$selected_string.'
						groupid="'.$finalArray['groupId'].'"
						categoryid="'.$finalArray['CategoryId'].'"
						value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
						option>';
	  				} else {
	  					$string2 .='<option CourseReach="national" CourseLevel1="PG"
						title="'.$finalArray['CourseName'].'"  '.$selected_string.'
						groupid="'.$finalArray['groupId'].'"
						categoryid="'.$finalArray['CategoryId'].'"
						value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
						option>';
	  				}
	  			}
	  		} else if($finalArray['CourseReach'] == 'local') {
	  			//echo "testlocal".$finalArray['CourseLevel1'];
	  			$string2 .='<option CourseReach="local" CourseLevel1 = "'.$finalArray['CourseLevel1'].'"
				title="'.$finalArray['CourseName'].'"  '.$selected_string.'
				groupid="'.$finalArray['groupId'].'"
				categoryid="'.$finalArray['CategoryId'].'"
				value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
				option>';
	  		}
	  	}
		}
	  	$groupName =  $finalArray['groupName'];
	  	$CourseLevel1 = $finalArray['CourseLevel1'] ;
	  	$level = $finalArray['CourseLevel'] ;
	  	if($groupName != '') {
	  		$string .= '<optgroup label="'. $groupName .'">'. $string2
	  		.'</optgroup>';
	  	} else {
	  		$string .=$string2;
	  	}
	  }
		}
		if ( $categoryId == '-1' || $categoryId ==14)
		{
			$string = '';
			foreach ($course_list as $key=>$value)
			{
				foreach($value as $index=>$main)
				{
					$string1 .= '<option CourseReach="local" CourseLevel1 = "'.$main['CourseLevel1'].'" title="'.$main['child']['blogTitle'].'" value="'.$main['child']['blogId'].'">'.$main['child']['acronym'].'</
				option>';
				}
				$string .=  "<optgroup label='". $main['title'] ."'>". $string1."</optgroup>";
				$string1 = "";
			}
		}
		$select_string = '<select style="width:210px"  required="true" caption="the desired course" validate="validateSelect" onchange="ShikshaUnifiedRegistarion.actionDesiredCourseDD(this.value);ShikshaUnifiedRegistarion.setFieldsVisibilityOrder(desired_course_array_unifiedregistration,this.value);ShikshaUnifiedRegistarion.validateCombo(this);" onblur="ShikshaUnifiedRegistarion.actionDesiredCourseDD(this.value);ShikshaUnifiedRegistarion.setFieldsVisibilityOrder(desired_course_array_unifiedregistration,this.value);" id="homesubCategories_unifiedregistration" name="">';
$select_string  .="<script>$('homesubCategories_unifiedregistration').focus();if($('fieldOfInterest_unifiedregistration') && navigator.userAgent.indexOf('MSIE')>=0) $('fieldOfInterest_unifiedregistration').focus();</script>";

		echo $select_string."<option value=''> Select </option>".$string."</select>";
	}
	/**
	 * Used for sorting courses
	 *
	 * @param array $a
	 * @param array $b
	 * @access	public
	 * @return	void
	 */
	public function cmp($a, $b) {
		$a = $a['CourseName'];
		$b = $b['CourseName'];
		if(substr($a,0,1) == "."){
			return 1;
		}
		if(substr($b,0,1) == "."){
			return -1;
		}
		return (strcmp($a,$b) < 0) ? -1 : 1;
	}
	/**
	 * Used for displaying course list
	 * 
	 * @param string $selected
	 * @param boolean $flag
	 * @access	private
	 * @return	string
	 */
	private function _course_lists($selected = NULL,$flag = FALSE) {
		$array = array('B.A.','B.A.(Hons)','B.Sc','B.Sc(Gen)','B.Sc(Hons)','B.E./B.Tech','B.Des','B.Com','BBA/BBM/BBS','B.Ed','BCA/BCM','BVSc','BHM','BJMC','BDS','B.Pharma','B.Arch','MBBS','LLB','Diploma');
		$string = '';
		for ($i = 0; $i < count($array);$i++)  {
			if ($selected != NULL) {
				if ($selected == $array[$i] ) {
					$selected_string = "selected";
				} else {
					$selected_string = "";
				}
			}
			$string .='<option '.$selected_string.' value="'.$array[$i].'">'.$array[$i].'</option>';
		}
		if ($flag) {
			return $array;
		} else {
			return $string;
		}
	}
	
	/**
	 * Function to get the data for page based on request
	 */
	private function _getdataForPage() {
		$this->init();
		$validity = $userStatus = $this->checkUserValidation();
		$Validate = $userStatus;
		if($Validate == "false"){
			$userid = '';
		}
		else{
			$userid = $Validate['0']['userid'];
			$displayname = $Validate['0']['displayname'];
		}
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
		} else {
			$logged = "Yes";
		}
		$data = array();
		$thisUrl = $_SERVER['REQUEST_URI'];
		$userName = '';
		$userEmail = '';
		$userInterest = -1;
		$userPassword = '';
		
		$user_name = $this->input->post('user_name', true);
		if(isset($user_name) && !empty($user_name)) {
			$userName = isset($user_name) ? $user_name:'';
			$user_email = $this->input->post('user_email', true);
			$userEmail = isset($user_email) ? $user_email:'';
			$user_interest = $this->input->post('user_interest', true);
			$userInterest = isset($user_interest) ? $user_interest:'';
			$userPassword = isset($_POST['user_password']) ? $_POST['user_password']:'';
			$user_contactno = $this->input->post('user_contactno', true);
			$userContactno = isset($user_contactno) ? $user_contactno:'';
		}

		$data['prefix'] = "";
		$categoryClient = new Category_list_client();
		$cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
		$cityListTier2 = $categoryClient->getCitiesInTier($appId,2,2);
		$cityListTier3 = $categoryClient->getCitiesInTier($appId,0,2);
		$categoryList = $categoryClient->getCategoryList(1,1);
		$catgeories = array();
		foreach($categoryList as $category) {
			$categories[$category['categoryID']] = $category['categoryName'];
		}
		asort($categories);
		$data['categories'] = $categories;
		$data['cityTier2'] = $cityListTier2;
		$data['cityTier3'] = $cityListTier3;
		$data['cityTier1'] = $cityListTier1;
		$data['userName'] = $userName;
		$data['userEmail'] = $userEmail;
		$data['userInterest'] = $userInterest;
		$data['userPassword'] = $userPassword;
		$data['userContactno'] = $userContactno;
		$data['logged'] = $logged;
		$data['userData'] = $userStatus;
		$data['validateuser'] = $validity;
		$data['select_city_list'] = $this->_select_city_list();
		/* end new lib api */
		$data1= $this->_marketing_page($userid);
		$data = array_merge($data1,(array)$data);
        return $data;
}
	
	/**
	 * Function to check if the user is LDB User
	 */
	public function isLDBUser(){
		error_log("inside isLDBUser....");
		$this->init();
	        $ldbObj = new LDB_Client();
		$userStatus = null;
		$userStatus = $this->checkUserValidation();
		if($userStatus!='false'){
		$userId=$userStatus[0]['userid'];
		$result = $ldbObj->isLDBUser($userId);
		foreach($result as $temp){
		$isLDBUser=$temp['UserId'];
		}
		if($isLDBUser==$userId){
		$result[0]['UserId']="true";
		}else{
		$result[0]['UserId']="false";
		}
		if(!isset($result[0]['PrefId'])){
		$result[0]['PrefId']='';
		}
		}else{
		$result[0]['UserId']="false";
		$result[0]['PrefId']='';
		}
		echo json_encode($result[0]);
	}
	
	
	/**
	 * Function to check the LDB user
	 */
	public function checkLDBUser(){
		$this->init();
		$ldbObj = new LDB_Client();
		$response=$ldbObj->checkLDBUser($appId);
		echo $response;
	}
	
	/**
	 * Function to email the listing
	 */
	public function emailThisListing(){
		error_log("inside emailThisListing........");
		$this->init();
		$addReqInfo = array();
		$toEmail = $this->input->post('reqInfoEmail');
		$fromAddress = $this->input->post('fromAddress', true);
		$fromAddress=isset($fromAddress)?$fromAddress:ADMIN_EMAIL;
		$toSms = $this->input->post('reqInfoPhNumber');
		error_log("mobile number is ".$toSms);
		$keyname = $this->input->post('keyname');
		$firstName = $this->input->post('reqInfoDispName');
		$displayname = $this->input->post('reqInfoDispName');
		$captchatext = $this->input->post('captchatext');
		$post_subject = $this->input->post('subject', true);
		$subject=isset($post_subject)?$post_subject:'Shiksha Info';
		$bodyOfMail=isset($_POST['body'])?$_POST['body']:'';
		$post_extraParams = $this->input->post('extraParams', true);
		$extraParams=isset($post_extraParams)?$post_extraParams:'';
		$listingIdForEMail = $this->input->post('listingIdForEMail', true);
		$Id=isset($listingIdForEMail)?$listingIdForEMail:'';
		$listingTypeForEMail = $this->input->post('listingTypeForEMail', true);
	    $type=isset($listingTypeForEMail)?$listingTypeForEMail:'';
		$llistingUrlForEMail = $this->input->post('listingUrlForEMail', true);
	    $url=isset($listingUrlForEMail)?$listingUrlForEMail:'';
		error_log("listingUrlForMail is as ".$url);
		$contentArr['url'] = $url;
	        $contentArr['type'] = $type;
		$contentArr['bodyOfMail'] = $bodyOfMail;
		$contentArr['extraParams'] = unserialize(base64_decode($extraParams));
		$content=$this->load->view("search/searchMail",$contentArr,true);
		$this->load->library('Alerts_client');
		$AlertClientObj = new Alerts_client();
	        $toEmailArray = preg_split("/[,;]/",$toEmail);
		$this->userStatus = $this->checkUserValidation();
                $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$signedInUser = $this->userStatus;
		error_log("userId is as ".$userId);
		$this->load->library('Register_client');
		$register_client = new Register_client();
		//user is logged in
		if(is_array($this->userStatus)) {
			if($firstName != $signedInUser[0]['firstname']){
	                $updatedStatus = $register_client->updateUserAttribute($appId,$signedInUser[0]['userid'],'firstname',$firstName);
		        }
	                if($toSms !=  $signedInUser[0]['mobile']){
		            $updatedStatus = $register_client->updateUserAttribute($appId,$signedInUser[0]['userid'],'mobile',$toSms);
		        }
		        $signedInUser = $this->checkUserValidation();
	            if($signedInUser[0]['usergroup'] == "veryshortregistration")
	            {
	                echo 'register';
	            }
	            else
	            {
	                echo 'thanks';
	            }
	        } else {
		error_log("captchatext is as ".$captchatext);
		error_log("email is as ".$toEmail);
                $responseCheckAvail = $register_client->getinfoifexists($appId,$toEmail,'email');
		error_log("responseCheckAvail is as ".print_r($responseCheckAvail,true));
                if(is_array($responseCheckAvail)) {
                    $signedInUser =  $responseCheckAvail;
			 if($toSms !=  $responseCheckAvail[0]['mobile']){
                        $updatedStatus = $register_client->updateUserAttribute($appId,$responseCheckAvail[0]['userid'],'mobile',$toSms);
                    }
			foreach($signedInUser as $temp){
			$userId=$temp['userid'];
			}
                    echo 'login';
               	    } else {
                    $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
                    while($responseCheckAvail == 1){
                        $displayname = $firstName . rand(1,100000);
                        $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
                    }
		    $password = 'shiksha@'. rand(1,1000000);
                    $ePassword = sha256($password);
                    $userarray['appId'] = $appId;
                    $userarray['email'] = $toEmail;
                    $userarray['ePassword'] = $ePassword;
                    $userarray['displayname'] = $displayname;
                    $userarray['mobile'] = $toSms;
                    $userarray['firstname'] = $firstName;
                    $userarray['sourceurl'] = $sourceurl;
                    $userarray['sourcename'] = $sourcename;
                    $userarray['resolution'] = $resolution;
                    $userarray['coordinates'] = $coordinates;
                    $userarray['usergroup'] = 'veryshortregistration';
                    $userarray['quicksignupFlag'] = "requestinfouser";
                    $userarray['desiredCourse'] = $courseInterest;
                    $addResult = $register_client->adduser_new($userarray);
                    // COOKIE HACK:-
                    $value = $toEmail.'|'.$mdpassword;
                    $this->cookie($value);
                    //COOKIE HACK ENDS:-
                    $this->userStatus = $register_client->getinfoifexists($appId,$toEmail,'email');
                    $signedInUser = $this->userStatus;
                    $this->sendWelcomeMailToNewUser($toEmail, $password,$addReqInfo,$addResult,$actiontype,$this->userStatus);
                    echo 'register';
//		    }
		    }
		    }
		if($userId==0){
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		}
		error_log("keyname is as ".$keyname);
		if($keyname=='EMAIL'){
		foreach($toEmailArray as $userEmail){
	        $alertResponse = $AlertClientObj->externalQueueAdd(12,$fromAddress,$userEmail,$subject,$content,"html");
       		}
	        $lmsAddArray = array('institute','course','scholarship','notification');
	        if(in_array($type,$lmsAddArray)){
	        //Lead submission
	        $signedInUser = $this->userStatus;
		$addReqInfo['listing_type'] = $type;
	        $addReqInfo['listing_type_id'] = $Id;
        	$addReqInfo['displayName'] = $displayname;
	        $addReqInfo['contact_cell'] = $toSms;
	        $addReqInfo['userId'] = $userId;
        	$addReqInfo['contact_email'] = $toEmail;
	        $addReqInfo['action'] = "sentmail";
	        $addReqInfo['userInfo'] = json_encode($signedInUser);
        	$addReqInfo['sendMail'] = true;
	        $this->load->library('LmsLib');
        	$LmsClientObj = new LmsLib();
	        $addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);
	        error_log("BC".print_r($addLeadStatus,true));
	        }
		}else if($keyname=='SMS'){
		$ListingClientObj= new Listing_client();
	        $listing_response=$ListingClientObj->getListingDetailForSms(0,$Id,$type);
	        $userid=$this->userStatus[0]['userid'];
	        error_log(print_r($this->userStatus[0],true));
	        $content=$listing_response[0]['listing_title'];
	        $content=$content."\n".($listing_response[0]['contact_email']==""?'support@shiksha.com':$listing_response[0]['contact_email']);
	        $content=$content."\n".($listing_response[0]['contact_cell']);
	        $content=$content."\nRegards,Shiksha Team";
	        $alertResponse = $AlertClientObj->addSmsQueueRecord(12,$toSms,$content,$userid);
		//Lead submission
	        $signedInUser = $this->userStatus;
	        $email = explode('|',$signedInUser[0]['cookiestr']);
	        $addReqInfo['listing_type'] = $type;
	        $addReqInfo['listing_type_id'] = $Id;
	        $addReqInfo['displayName'] = $displayname;
	        $addReqInfo['contact_cell'] = $toSms;
	        $addReqInfo['userId'] = $userId;
	        $addReqInfo['contact_email'] = $toEmail;
	        $addReqInfo['action'] = "sentsms";
	        $addReqInfo['userInfo'] = json_encode($signedInUser);
	        $addReqInfo['sendMail'] = true;
	        $this->load->library('LmsLib');
	        $LmsClientObj = new LmsLib();
	        $addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);
	        error_log("BC".print_r($addLeadStatus,true));
		}
//		}
        }


		private function sendWelcomeMailToNewUser($email, $password, $addReqInfo,$addResult,$actiontype,$userinfo) {
	        $this->init();
		$this->load->library('Alerts_client');
	        $alerts_client = new Alerts_client();
	        $data = array();
	        $isEmailSent=0;
	        try {
	            $subject = "Your Shiksha Account has been generated";
	            $data['usernameemail'] = $email;
	            $data['userpasswordemail'] = $password;
	            $content = $this->load->view('user/RegistrationMail',$data,true);
	            $response=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,$contentType="html");

	            /* For Shiksha Inbox. */
	            $this->load->library('Mail_client');
	            $mail_client = new Mail_client();
	            $receiverIds = array();
	            array_push($receiverIds,$addResult['status']);
	            $body = $content;
	            $content = 0;
	            $sendmail = $mail_client->send($appId,1,$receiverIds,$subject,$body,$content);

	        } catch (Exception $e) {
	            // throw $e;
	            error_log('Error occoured sendWelcomeMailToNewUser' .
	            $e,'MultipleApply');
	        }
	        }


		private function cookie($value) {
                $value1 = $value . '|pendingverification';
                setcookie('user',$value1,time() + 2592000 ,'/',COOKIEDOMAIN);
                $this->init();
                }
}

?>
