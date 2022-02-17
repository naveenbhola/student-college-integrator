<?php 

/**
 * Mailer Class
 *
 * @author
 * @package Mailer
 *
 */

class Marketing extends MX_Controller
{
	var $appId = 1;
	var $userid = '';
	private function init()
	{
		ini_set('upload_max_filesize','100M');
		ini_set('max_execution_time', '1800');
		$this->load->helper(array('form', 'url','date','image','html'));
		$this->load->library(array('MultipleMarketingPageClient','LDB_Client','marketingClient','miscelleneous','message_board_client','blog_client','event_cal_client','ajax','category_list_client','listing_client','register_client','enterprise_client','sums_manage_client','table'));
		$this->userStatus = $this->checkUserValidation();
		$Validate = $this->userStatus;
		if($Validate == "false"){
			$this->userid = '';
		}
		else{
			$this->userid = $Validate['0']['userid'];
			$this->displayname = $Validate['0']['displayname'];
		}
		$this->MarketingPageClient = MultipleMarketingPageClient::getInstance();
	}
	function registerLead($secCodeSessionVar='seccodehome') {
		$this->init();
		$validity = $this->checkUserValidation();
		error_log_shiksha("ERT ".print_r($validity,true));
		$city = $this->input->post('citiesofresidence1');
		$mobile = $this->input->post('homephone');
		error_log("UIO HOME PHONE = ".$mobile);
		$educationLevel = $this->input->post('homehighesteducationlevel');
		$age = $this->input->post('homeYOB');
		$categories = $this->input->post('board_id');
		$gender = $this->input->post('homegender');
		$subCategory = $this->input->post('homesubCategories');
		$preferredCity = $this->input->post('mCityList');
		$preferredCityName = $this->input->post('mCityListName');
		$firstName = $this->input->post('homename');
		$sourceurl = $this->input->post('refererreg');
		$cookieArr = split('\|',$validity[0]['cookiestr']);
		$email = $cookieArr[0];
		$subCategoryNameInsert = "";
		$categoriesNameInsert = "";
		error_log(print_r($_POST,true)."LKJ");
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		error_log("LKJ NOT FOUND ".$subCategoryNameInsert);
		$allCategoryList = $categoryClient->getCategoryTree(1,1);
		error_log("LKJ ".print_r($allCategoryList,true));
		foreach($allCategoryList as $allCategory) {
			$categoryId = $allCategory['categoryID'];
			$categoryName = $allCategory['categoryName'];
			if($categories == $categoryId){
				$categoriesNameInsert = $categoryName;
			}
			if($subCategory == $categoryId){
				$subCategoryNameInsert = $categoryName;
			}

		}
		$this->load->library('register_client');
		$registerClient = new Register_client();
		$highestQualName = $registerClient->getEdLevelFromId(1,$educationLevel);
		$highestQualNameInsert = $highestQualName['level'];
		global $citiesforRegistration;
		foreach($citiesforRegistration as $key=>$value) {
			if($value['id'] == $city) {
				$cityLocName = $value['name'];

			}
		}
		$addReqInfo = array();
		$addReqInfo['displayName'] = $firstName;
		$addReqInfo['action'] = "AlreadyRegisteredMarketing";
		$addReqInfo['email'] = $email;
		$addReqInfo['residenceLoc'] = $city;
		$addReqInfo['residenceLocName'] = $cityLocName;
		$addReqInfo['age'] = $age;
		$addReqInfo['gender'] = $gender;
		$addReqInfo['highestQualification'] = $educationLevel;
		$addReqInfo['fieldOfInterest'] = $categories;
		$addReqInfo['desiredCourse'] = $subCategory;
		$addReqInfo['highestQualificationName'] = $highestQualNameInsert;
		$addReqInfo['fieldOfInterestName'] = $categoriesNameInsert;
		$addReqInfo['desiredCourseName'] = $subCategoryNameInsert;
		$addReqInfo['prefferedStudyLoc'] = $preferredCityName;
		$addReqInfo['mobile'] = $mobile;
		$addReqInfo['flagRegistered'] = "false";
		error_log("UIO HOME PHONE = ".print_r($addReqInfo,true));
		$addUpdateInfo = array();
		$addUpdateInfo['firstname'] = $firstName;
		$addUpdateInfo['city'] = $city;
		$addUpdateInfo['mobile'] = $mobile;
		$addUpdateInfo['educationlevel'] = $educationLevel;
		$addUpdateInfo['age'] = $age;
		$addUpdateInfo['gender'] = $gender;
		$addUpdateInfo['displayname'] = $validity[0]['displayname'];
		error_log("TYU ".print_r($addUpdateInfo,true));
		$updateInterest  = array();
		$updateInterest['category'] = json_encode(array($categories));
		$updateInterest['subCategory'] = json_encode(array($subCategory));
		$updateInterest['city'] = array();
		$csvArr1 = split(",",$preferredCity);
		for($ijk = 0;$ijk < count($csvArr1);$ijk++) {
			if(trim($csvArr1[$ijk]) != ""){
				preg_match('/\d+/',$csvArr1[$ijk],$matches);
				if(count($matches) >0) {
					array_push($updateInterest['city'], $csvArr1[$ijk]);
				}
			}
		}
		$updateInterest['city'] = json_encode($updateInterest['city']);
		$sourcename = 'MARKETING_FORM';
		$userarray['sourceurl'] = $sourceurl;
		$userarray['sourcename'] = $sourcename;
		$userarray['resolution'] = $resolution;
		$userarray['appId'] = 1;
		$userarray['email'] = $email;
		$userarray['password'] = $password;
		$userarray['mdpassword'] = $mdpassword;
		$userarray['displayname'] = $displayname;
		$userarray['country'] = $country;
		$userarray['city'] = $city;
		$userarray['age'] = $age;
		$userarray['mobile'] = $mobile;
		$userarray['educationLevel'] = $educationLevel;
		$userarray['youare'] = $userstatus;
		$userarray['firstname'] = $firstName;
		$userarray['gender'] = $gender;
		$userarray['categories'] = $categories;
		$userarray['subcategories'] = $subCategory;
		$userarray['quicksignupFlag'] = "marketingPage";
		$userarray['preferredCityCsv'] = $preferredCity;
		error_log("FGH subcategories ".$subCategory);
		$userarray['usergroup'] = 'marketingPage';
		$secCode = $this->input->post('homesecurityCode');
		$userId = $validity[0]['userid'];
		if(verifyCaptcha($secCodeSessionVar,$secCode,1))
		{
			$preferredLoc = array();
			$csvArr = split(",",$preferredCity);
			for($ijk = 0;$ijk < count($csvArr);$ijk++) {
				if(trim($csvArr[$ijk]) != ""){
					preg_match('/\d+/',$csvArr[$ijk],$matches);
					if(count($matches) >0) {
						array_push($preferredLoc, $csvArr[$ijk]);
					}
				}
			}
			error_log("GHJ ".print_r($preferredLoc,true));
			$finalUrl = "";
			$categoryUrl = "";
			global $categoryMap;
			foreach ($categoryMap as $categoryName=>$categoryData){
				if($categoryData['id'] == $categories) {

					$pageName = strtoupper('SHIKSHA_'. $categoryName .'_HOME');
					$categoryUrl = constant($pageName);
					break;
				}
			}
			if(strstr($categoryUrl,"getCategoryPage") > -1) {
				$categoryUrl = $categoryUrl;
			}else {
				$categoryUrl = $categoryUrl.'/getCategoryPage/colleges/'.$categoryName;
			}

			$finalUrl .=$categoryUrl."/India/";
			$finalPreferredLoc = "";
			$finalPreferredLocName = "";
			error_log("POI ".$userId);
			$this->load->library('category_list_client');
			$categoryClient = new Category_list_client();
			$cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
			$cityListTier2 = $categoryClient->getCitiesInTier($appId,2,2);
			$cityListTier3 = $categoryClient->getCitiesInTier($appId,0,2);
			$subCategoriesAll = $categoryClient->getSubCategories($appId,$categories);
			//error_log("HJK ".print_r($subCategoriesAll,true));
			foreach ($subCategoriesAll as $key=>$value) {
				if($value['boardId'] == $subCategory) {
					$subCategoryName = $value['urlName'];
				}
			}
			if(count($preferredLoc) == 1) {
				$finalPreferredLoc = trim($preferredLoc[0]);
			}else{
				for($klj = 0; $klj< count($preferredLoc);$klj++) {
					if($preferredLoc[$klj] == $city) {
						$finalPreferredLoc=trim($preferredLoc[$klj]);
					}
				}
				if($finalPreferredLoc == "") {
					for($klj = 0; $klj< count($preferredLoc);$klj++) {
						foreach($cityListTier1 as $cityTemp)
						{
							error_log("GHJ In teir One Check ".$preferredLoc[$klj]." ".$cityTemp['cityId']);

							if(trim($preferredLoc[$klj]) == trim($cityTemp['cityId'])) {
								$finalPreferredLocName = $cityTemp['cityName'];
								$finalPreferredLoc=trim($preferredLoc[$klj]);
								break;
							}
						}
						if($finalPreferredLoc != "") {
							break;
						}
					}
					if($finalPreferredLoc == "") {
						error_log("GHJ teir One Check Failed");
						$finalPreferredLoc = trim($preferredLoc[0]);
					}
				}
			}
			error_log("GHJ Loc10".$finalPreferredLoc);
			if($finalPreferredLocName == "") {
				foreach($cityListTier1 as $cityTemp)
				{
					if($finalPreferredLoc == $cityTemp['cityId']) {
						$finalPreferredLocName = $cityTemp['cityName'];
						break;
					}
				}
			}
			if($finalPreferredLocName == "") {
				foreach($cityListTier2 as $cityTemp)
				{
					error_log("GHJ ".$cityTemp['cityId']." ".$finalPreferredLoc);
					if($finalPreferredLoc == $cityTemp['cityId']) {
						$finalPreferredLocName = $cityTemp['cityName'];
						break;
					}
				}
				error_log("GHJ In tier 2 city if");
			}

			if($finalPreferredLocName == "") {
				foreach($cityListTier3 as $cityTemp)
				{
					error_log("GHJ ".$cityTemp['cityId']." ".$finalPreferredLoc);
					if($finalPreferredLoc == $cityTemp['cityId']) {
						$finalPreferredLocName = $cityTemp['cityName'];
						break;
					}
				}
				error_log("GHJ In tier 2 city if");
			}
			error_log("GHJ Loc1 ".$finalPreferredLoc);
			error_log("LKJ Name1 ".$finalPreferredLocName);
			$finalPreferredLocName = str_replace("/","-",$finalPreferredLocName);
			$finalUrl .=$finalPreferredLoc."/".$subCategoryName."/".$finalPreferredLocName;
			$marketingClientObj = new MarketingClient();
			for($klj = 0; $klj< count($preferredLoc);$klj++) {
				$key = trim($subCategory)."#".trim($preferredLoc[$klj]);
				error_log("GOING FOR Regis LKJ");
				error_log("POI ".print_r($addUpdateInfo,true));
				$addReqInfo['cityToAdd'] = $preferredLoc[$klj];
				$addReqInfo['keyValInitial']=trim($subCategory)."#";
				error_log("UIO ".json_encode($updateInterest));
				$addUser = $marketingClientObj->registerUserForLead(1,$userId,$key,$addReqInfo,$addUpdateInfo,$addUpdateInfo['displayname'],json_encode($updateInterest));
			}
			echo $finalUrl."###1";
		}
		else
		echo "code";

	}
	function abCodeManagement($page,$type,$campaign) {
                $this->load->library('cacheLib');
                $cacheLibObj = new cacheLib();
                
		$var = $cacheLibObj->get("bManagementABCode");
		if($var == "1") {
			$cacheLibObj->store("bManagementABCode","0");
		}else {
			$cacheLibObj->store("bManagementABCode","1");
		}
		if($var == "1") {
			header('location:/marketing/Marketing/index/'.$page.'/'.$type.'/'.$campaign);
		}else{
			header('location:/marketing/Marketing/index/'.$page.'_2/'.$type.'_2/'.$campaign);
		}
	}

	function abCode($page,$type,$campaign) {
                $this->load->library('cacheLib');
                $cacheLibObj = new cacheLib();

		$var = $cacheLibObj->get("bABCode");
		if($var == "1") {
			$cacheLibObj->store("bABCode","0");
		}else {
			$cacheLibObj->store("bABCode","1");
		}
		error_log("UIO ".$var);
		if($var == "1") {
			error_log("MPPP");
			header('location:/marketing/Marketing/index/'.$page.'/'.$type.'/'.$campaign);
		} else {
			$this->load->library('category_list_client');
			$categoryClient = new Category_list_client();
			error_log("LKJ NOT FOUND ".$subCategoryNameInsert);
			$allCategoryList = $categoryClient->getCategoryTree(1,1);
			error_log("LKJ ".print_r($allCategoryList,true));
			foreach($allCategoryList as $allCategory) {
				$categoryId = $allCategory['categoryID'];
				$categoryName = $allCategory['categoryName'];
				if($categories == $categoryId){
					$categoriesNameInsert = $categoryName;
				}
				if($subCategory == $categoryId){
					$subCategoryNameInsert = $categoryName;
				}

			}


			$categoryUrl = "";
			$pageName = strtoupper('SHIKSHA_'. $type.'_HOME');
			$pageName = constant($pageName);
			if(strstr($pageName,"getCategoryPage") > -1) {
				$categoryUrl = $pageName.'/All/All/All/'.$campaign;
			}else {
				$categoryUrl = $pageName.'/getCategoryPage/colleges/'.$type.'/All/All/All/'.$campaign;
			}
			header('location:'.$categoryUrl);
		}
	}

	function makeUserData($userCompleteDetails)
	{
		$userCompleteDetails = $this->make_array(json_decode($userCompleteDetails,true));
		$userprefarray = $userCompleteDetails[0]['PrefData'][0];
		$usereduarray = $userCompleteDetails[0]['EducationData'];
		$userlocpref = $userCompleteDetails[0]['PrefData'][0]['LocationPref'];
		$statearray = array();
		$cityarray = array();

		$userarray = array('name'=>isset($userCompleteDetails[0]['firstname']) ? $userCompleteDetails[0]['firstname']:'',
		   'firstname'=>isset($userCompleteDetails[0]['firstname']) ? $userCompleteDetails[0]['firstname']:'',
		   'lastname'=>isset($userCompleteDetails[0]['lastname']) ? $userCompleteDetails[0]['lastname']:'',
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

	function _other_exm_list($selected = NULL,$flag = FALSE) {
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

	function _when_you_plan_start($selected = NULL,$flag = FALSE) {
		$array= array(
		date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+1)) => 'This Year’s Academic Season',
		date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+2)) => 'Next Year’s Academic Season',
		//date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+2)) => 'Within next two years',
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
	function _course_lists($selected = NULL,$flag = FALSE) {
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
	function _work_exp_combo($selected = NULL,$flag = FALSE) {
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
	private function _load_data_marketing_page($page_id) {
		$this->init();
		$data['config_data_array'] = $this->MarketingPageClient->marketingPageDetailsById($page_id);
		$data['pagetype'] = $data['config_data_array']['page_type'];
		$data['formPostUrl'] = '/user/Userregistration/MultipleMarketingPage/seccodehome';
		if($data['pagetype']=='indianpage') {
			$data1= json_decode($this->MarketingPageClient->getCourselistForApage($page_id,'group'),true);
			$data['itcourseslist']= $data1['courses_list'];
			$data['managementcourses'] = $this->MarketingPageClient->getManagementCourses(trim(str_replace(',',' ',$data1['management_courseids'])));
		} else if($data['pagetype']=='testpreppage') {
			$data['itcourseslist']= json_decode($this->MarketingPageClient->getTestPrepCoursesListForApage(1,$page_id,$pagetype,'saved_list'),true);
			$data['itcourseslist']= $data['itcourseslist']['courses_list'];
		}
		if((empty($data['config_data_array']['count_courses']))||(empty($data['config_data_array']['banner_url']))||
		(empty($data['config_data_array']['header_text'])) || (empty($data['config_data_array']['banner_text'])) || (empty($data['config_data_array']['form_heading']))) {
			header("Status: 404 Not Found");
			header('Location:   /shikshaHelp/ShikshaHelp/errorPage');
			exit;
		}
		return $data;
	}

	private function _marketing_page($userid)
	{
		$data = array();
		$this->init();
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
		//echo "<pre>";
		//print_r(json_decode($userCompleteDetails));
		//echo "</pre>";
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
			$data['when_you_plan_start'] = $this->_when_you_plan_start($TimeOfStart,FALSE);
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
			$data['course_lists'] = $this->_course_lists($name_ug_course,FALSE);
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

    function escape($str)
    {
            $search=array("\\","\0","\n","\r","\x1a","'",'"');
            $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
            return str_replace($search,$replace,htmlspecialchars($str));
    }

	/**
	 * this the first method which is called, it renders multipleMarketingPageMain view
	 *
	 * @access	public
	 * @return	void
	 */
	public function oldMarketingForm($page,$page_id) {
		$this->init();
		$validity = $this->checkUserValidation();
		global $logged;
		global $userid;
		$data = array();
		$thisUrl = $_SERVER['REQUEST_URI'];
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
		} else {
			$logged = "Yes";
			$redirectURL = $this->getUserRedirectionURL($page_id,0);
			header("Location: ".$redirectURL);
			exit();
		}
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
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
		$cityListTier2 = $categoryClient->getCitiesInTier($appId,2,2);
		$cityListTier3 = $categoryClient->getCitiesInTier($appId,0,2);
		$data['cityTier2'] = $cityListTier2;
		$data['cityTier3'] = $cityListTier3;
		$data['cityTier1'] = $cityListTier1;
        if (strlen($userName) > 100) {
            $userName = substr($userName,0,100);
        }
        if (strlen($userEmail) > 125) {
            $userEmail = substr($userEmail,0,125);
        }
        if (strlen($userContactno) > 10) {
            $userContactno = substr($userContactno,0,10);
        }
		$data['userName'] = $this->escape($userName);
		$data['userEmail'] = $this->escape($userEmail);
		$data['userInterest'] = $userInterest;
		$data['userPassword'] = $userPassword;
		$data['userContactno'] = $this->escape($userContactno);
		$data['logged'] = $logged;
		$data['userData'] = $this->userStatus;
		$data['validateuser'] = $validity;
		$userid =  $this->userid;
		$data['select_city_list'] = $this->_select_city_list();
		/* end new lib api */
		$data1= $this->_marketing_page($userid);
		$config_data_array = $this->MarketingPageClient->marketingPageDetailsById($page_id);
		$pagetype = $config_data_array['page_type'];
		$data['pageId'] = $page_id;
		
		$data['mainCategoryIdsOnPage'] = $this->_getMainCategoriesForMMP($page_id);
		
		$get_catid = $this->input->get('catid', true);
		$catid = isset($get_catid)?$get_catid:"";
		$get_subcatid = $this->input->get('subcatid', true);
		$subcatid = isset($get_subcatid)?$get_subcatid:"";
		$get_countryid = $this->input->get('countryid', true);
		$countryid = isset($get_countryid)?$get_countryid:"";
		$get_locid = $this->input->get('locid', true);
		$locid = isset($get_locid)?$get_locid:"";
		$googleRemarketingParams = array(
		  "categoryId" => $catid,
		  "subcategoryId" => $subcatid,
		  "countryId" => $countryid,
		  "cityId" => $locid
		  );
		$data['googleRemarketingParams'] = $googleRemarketingParams;
		if($pagetype!='abroadpage') {
			$data = array_merge($data1,(array)$data,$this->_load_data_marketing_page($page_id));
			$this->load->view('multipleMarketingPage/oldForm/multipleMarketingPageMain',$data);
			error_log_shiksha("END FRONT_END FF_MBA");
		} else if($pagetype=='abroadpage') {
			$saved_courses_lists= json_decode($this->MarketingPageClient->getStudyAbroadCoursesListForApage(1,$page_id,$pagetype,'saved_list'),true);
			$savedcatgeories = array();
			foreach($saved_courses_lists['courses_list'] as $category) {
				$savedcatgeories[$category[0]['categoryID'][0]] = $category[0]['categoryName'][0];
			}
			asort($savedcatgeories);
			$this->studyAbroad('studyAbroad','',$savedcatgeories,$config_data_array,$page_id);

		}
	}
	
	public function form($page,$page_id,$trackingPageKeyId='') {		
		$language = $_GET['la'];
		$page_id_int = intval($page_id);
        $page_id_Length = strlen($page_id);
        $page_id_int_Length = strlen($page_id_int);
		if($page_id_int <= 0 || $page_id_Length != $page_id_int_Length) {
			redirect(SHIKSHA_HOME, 'location');
		}

		$cmp_model = $this->load->model('customizedmmp/customizemmp_model');
		
		$page_id = intval($page_id);
		if($page_id>0) {
			
			$mmp_details = $cmp_model->getMMPDetails($page_id);
			$mmpFormCustomizations = $cmp_model->getFormCustomizationData($page_id);
			$mmp_details['formCustomization'] = json_decode($mmpFormCustomizations['customization_fields'], true);
			$mmp_details['formCustomization']['mmpFormId'] = $page_id;
			$mmp_details['formCustomization']['language'] = $language;
				
			if(is_array($mmp_details) && $mmp_details['status'] !='live') {
				show_404();
			} else {
				global $mmp_display_on_page_array;
				if(($mmp_details['page_type'] == 'indianpage') && (in_array($mmp_details['display_on_page'],$mmp_display_on_page_array))) {
					$mmp_details['formCustomization']['isMMP'] = 'yes';
					$mmp_details = $this->_filterMMPData($mmp_details);
					$this->loadMMPNational($mmp_details);
					return;
				}
			}
			
		}else{
			redirect(SHIKSHA_HOME, 'location');
		}
				
		$this->init();
		$validity = $this->checkUserValidation();
		
		global $logged;
		global $userid;
		$data = array();
		$thisUrl = $_SERVER['REQUEST_URI'];
		
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
		} else {
			$logged = "Yes";
		}
		
		$userName = '';
		$userEmail = '';
		$userInterest = -1;
		$userPassword = '';
		$user_name = $this->input->post('user_name');
		if(isset($user_name) && !empty($user_name)) {

			$userName = isset($user_name) ? $user_name:'';
			$user_email = $this->input->post('user_email', true);
			$userEmail = isset($user_email) ? $user_email:'';
			$user_interest = $this->input->post('user_interest', true);
			$userInterest = isset($user_interest) ? $user_interest:'';
			$userPassword = isset($_POST['user_password']) ? $_POST['user_password']:'';
			$user_contactno = $this->input->post('user_contactno', true);
			$userContactno = isset($user_contactno) ? $user_contactno:'';
			error_log(" log visitor detail"."---".$userName."---".$userEmail."---".$userInterest."---".$userPassword."---".$userContactno);
			
		}
		
		$data['prefix'] = "";
		
		if (strlen($userName) > 100) {
		    $userName = substr($userName,0,100);
		}
		
		if (strlen($userEmail) > 125) {
		    $userEmail = substr($userEmail,0,125);
		}
		
		if (strlen($userContactno) > 10) {
		    $userContactno = substr($userContactno,0,10);
		}
		
		$data['userName'] = $this->escape($userName);
		$data['userEmail'] = $this->escape($userEmail);
		$data['userInterest'] = $userInterest;
		$data['userPassword'] = $userPassword;
		$data['userContactno'] = $this->escape($userContactno);
		$data['logged'] = $logged;
		$data['userData'] = $this->userStatus;
		$data['validateuser'] = $validity;
		$data['trackForPages'] = true;
		
		$userid =  $this->userid;
		$data['userid'] = $userid;

		$config_data_array = $this->MarketingPageClient->marketingPageDetailsById($page_id);
		$data['config_data_array'] = $config_data_array;
		$data['pageId'] = $page_id;
		$pagetype = $config_data_array['page_type'];
		
		$get_catid = $this->input->get('catid', true);
		$catid = isset($get_catid)?$get_catid:"";
		$get_subcatid = $this->input->get('subcatid', true);
		$subcatid = isset($get_subcatid)?$get_subcatid:"";
		$get_countryid = $this->input->get('countryid', true);
		$countryid = isset($get_countryid)?$get_countryid:"";
		$get_locid = $this->input->get('locid', true);
		$locid = isset($get_locid)?$get_locid:"";
		$googleRemarketingParams = array(
		  "categoryId" => $catid,
		  "subcategoryId" => $subcatid,
		  "countryId" => $countryid,
		  "cityId" => $locid
		  );
		
		$data['googleRemarketingParams'] = $googleRemarketingParams;
		
		// reset password code add for marketing page
		$reset_password = trim(strip_tags($_REQUEST['resetpwd']));
		
		if($reset_password == 1) {
			$reset_password_token = trim(strip_tags($_REQUEST['uname']));
			$reset_usremail = trim(strip_tags($_REQUEST['usremail']));			
			$data['reset_password_token'] = $reset_password_token;
			$data['reset_usremail'] = $reset_usremail;				
		}
		
		$data['load_ga'] = TRUE;
		$data['mmp_details'] = $mmp_details;
		
		if(isset($mmp_details['background_url']) && trim($mmp_details['background_url']) !== '') {
			
			$pos = strpos($mmp_details['background_url'], '?');
			
			if($pos === false) {
					$mmp_details['background_url'] = $mmp_details['background_url'].'?mmpbeacon=1';
			} else {
					$mmp_details['background_url'] = $mmp_details['background_url'].'&mmpbeacon=1';
			}
						
			$data['bg_url'] = 'src = "'.$mmp_details['background_url'].'"';
			
		} else {
			if(isset($mmp_details['background_image'])) {
				
				$data['bg_image'] = "background-image: url('".MEDIA_SERVER.$mmp_details['background_image']."'); background-repeat: no-repeat; background-position: top center;";
				
			}
		}
		
		if($pagetype == 'abroadpage'){
			$data['logo_src'] = MEDIA_SERVER.'/public/images/abroad_mailer_logo2.png';
		}else{
			$data['logo_src'] = MEDIA_SERVER.'/public/images/desktopLogo.png';
		}

		global $mmp_display_on_page_array;
		if($mmp_details['display_on_page'] == 'newmmp'  || in_array($mmp_details['display_on_page'],$mmp_display_on_page_array)) {
			
			if(isMobileRequest()) {
				//below line is used for conversion tracking purpose
				if( ! empty($trackingPageKeyId))
					$data['trackingPageKeyId'] = $trackingPageKeyId;
				
				$this->loadNewMobileMMP($data);
				
			} else {
					
				$this->load->view('multipleMarketingPage/newGeneralMarketingPage', $data);
				
			}
			
		} else {
			
			if($pagetype == 'abroadpage') {
				
				$data['pageName'] = 'studyAbroad';
				$this->load->view('multipleMarketingPage/generalMarketingPage', $data);
				
			}
			
		}
		
	}
	
	private function _filterMMPData($mmp_details){
		if(!empty($mmp_details['formCustomization']['subStreamSpec']['value'])){
			$formData = $mmp_details['formCustomization']['subStreamSpec']['value'];
			foreach($formData as $substream=>$spec){
				if(empty($spec)){
					$newFormData[$substream] = array(0);

				}else{
					$newFormData[$substream] = $spec;
				}
			}

			$mmp_details['formCustomization']['subStreamSpec']['value'] = $newFormData;
		}

		return $mmp_details;
	}

	private function _getMainCategoriesForMMP($pageId)
	{
		$this->load->model('customizedmmp/customizemmp_model');
		$categories = $this->customizemmp_model->getMMPCourseCategories($pageId);
		$categories = array_unique(array_map(function($a) { return $a['category']; },$categories));
		return $categories;
	}
	
	public function index($page,$page_id)
	{
		$newFormPages = array(96,23);
		
		if(in_array($page_id,$newFormPages)) {
			
			$this->load->library('CacheLib');
			$cacheLib = new CacheLib;
			$formType = $cacheLib->get('MMPFormType_'.$page_id);
			error_log("FTYPE: ".$formType);
			
			if($formType == 'NEW') {
				$cacheLib->store('MMPFormType_'.$page_id,'OLD');
				$this->form($page,$page_id);
			}
			else {
				$cacheLib->store('MMPFormType_'.$page_id,'NEW');
				$this->oldMarketingForm($page,$page_id);
			}
		}
		else {
			$this->oldMarketingForm($page,$page_id);
		}
	}
	
	function ajaxform_mba($flag) {
		$this->init();
		global $logged;
		$data = array();
		$validity = $this->checkUserValidation();
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
		} else {
			$logged = "Yes";
		}
		$data['logged'] = $logged;
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
		$cityListTier2 = $categoryClient->getCitiesInTier($appId,2,2);
		$cityListTier3 = $categoryClient->getCitiesInTier($appId,0,2);
		$data['cityTier1'] = $cityListTier1;
		$data['cityTier2'] = $cityListTier2;
		$data['cityTier3'] = $cityListTier3;
		$this->load->library('LDB_Client');
		$ldbObj = new LDB_Client();
		$data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12),true);
		$userid =  $this->userid;
		$appId = 1;
		$data['select_city_list'] = $this->_select_city_list();
		$this->load->library('LDB_Client');
		$categoryClient = new LDB_Client();
		$listing_client = new listing_client();
		$cityListTier = $data['country_state_city_list'];
		$course_list = json_decode($categoryClient->sgetCourseList($appId,3),true);
		$userCompleteDetails = '';
		if($userid != '')
		$userCompleteDetails = $categoryClient->sgetUserDetails($appId,$userid);
		$userDataToShow = $this->makeUserData($userCompleteDetails);
		$countryCityStateMaps = $this->getCityStatesFromMap($cityListTier);
		$data['countriesList'] = $countryCityStateMaps['countries'];
		$data['statesList'] = $countryCityStateMaps['states'];
		$data['citiesList'] = $countryCityStateMaps['cities'];
		$data['userDataToShow'] = $userDataToShow;
		$data['newDesiredCourse_list'] = $course_list;
		$data['userCompleteDetails'] = $userCompleteDetails;
		$data['CitiesWithCollege']  = $listing_client->getCitiesWithCollege(1,2);
		$data['other_exm_list'] = $this->_other_exm_list();
		$data['work_exp_combo'] = $this->_work_exp_combo();
		if($userid != '') {
			$userCompleteDetails = $this->make_array(json_decode($userCompleteDetails,true));
			$TimeOfStart =
			$userCompleteDetails[0]['PrefData'][0]['TimeOfStart'];
			$data['when_you_plan_start'] = $this->_when_you_plan_start($TimeOfStart,FALSE);
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
			$data['course_lists'] = $this->_course_lists($name_ug_course,FALSE);
			$data['ug_marks'] = $ug_marks;
			$data['CourseCompletionDate'] = $CourseCompletionDate;
			$data['ug_city_id'] = $ug_city_id;
			$data['ug_institute_id'] = $ug_institute_id;
			$data['ug_institute_name'] = $ug_institute_name;
		} else {
			$data['when_you_plan_start'] = $this->_when_you_plan_start();
			$data['course_lists'] = $this->_course_lists();
		}
		$data['prefix'] = "";
		$data['formPostUrl'] = '/user/Userregistration/MultipleMarketingPage/seccodehome';
		if ($flag == 'mr_page') {
			echo $this->load->view('multipleMarketingPage/multipleMarketingPageLocationLayer',$data);
		} elseif ($flag == 'mr_user_sign') {
			echo $this->load->view('multipleMarketingPage/multipleMarketingPageSignInOverlay',$data);
		} elseif ($flag == 'itcourse') {
			echo $this->load->view('multipleMarketingPage/user_form_multipleMarketingPage_courses',$data);
		} elseif ($flag == 'itdegree') {
			echo $this->load->view('multipleMarketingPage/user_form_multipleMarketingPage_degree',$data);
		}elseif ($flag == 'graduate_course') {
			echo $this->load->view('multipleMarketingPage/user_form_multipleMarketingPage_ug_courses',$data);
		}elseif ($flag == 'show_xii_field') {
		  echo
		  $this->load->view('multipleMarketingPage/xii_details_forms_field',$data);
		}elseif ($flag == 'show_grad_field') {
		  echo
		  $this->load->view('multipleMarketingPage/ug_details_forms_field',$data);
		}
	}
	function displaynextoverlay($str,$desired_course_name,$keyname = 'MARKETING_FORM',$prefId="",$marketingpagename="-1")
	{
		$this->init();
		$data = array();
		$validity = $this->checkUserValidation();
		$data['marketingpagename'] = $marketingpagename;
		if($str =='course') {
			$data['local_course_flag'] = 'it_courses';
            $data['desired'] = $desired_course_name;
			$url = 'multipleMarketingPage/multipleMarketingPage_overlay.php';
		} elseif($str =='degree') {
			$data['local_course_flag'] = 'it_degree';
			$url = 'multipleMarketingPage/multipleMarketingPage_overlay.php';
		} elseif($str =='graduate_course') {
			$data['local_course_flag'] = 'graduate_course';
			$url = 'multipleMarketingPage/multipleMarketingPage_overlay.php';
		} else {
			$url = 'common/Request-E-Brochure6.php';
		}
		$appId = 1;
		$this->load->library('LDB_Client');
		$categoryClient = new LDB_Client();
		$userid =  $this->userid;
		if($userid != '')
		$userCompleteDetails = $categoryClient->sgetUserDetails($appId,$userid);
		$data['SpecializationList'] = json_decode($categoryClient->sgetSpecializationListByParentId($appId,$desired_course_name),true);
		$data['other_exm_list'] = $this->_other_exm_list();
		$data['course_lists'] = $this->_course_lists();
		$data['prefId'] = $prefId;
		if ($str == '6') {
			// HARDCODE CHECK
			if (($desired_course_name != '2') && ($desired_course_name != '24')) {
				$data['local_course_flag'] = 'on';
			}
		}
        // Need to remove below check RaviRaj 01-12-11
		// if(($desired_course_name == '2') || ($desired_course_name == '24'))
		$data['is_mba'] = 'yes';
		$data['userCompleteDetails'] = $this->make_array(json_decode($userCompleteDetails,true));
		$data['validateuser'] = $validity;
		$data['istr'] = $str;
		$data['desiredCourseName'] = $desired_course_name;
		$string = $this->load->view($url,$data,true);
		echo $string;
	}

	function ajax_preference_locality($id) {
		$this->init();
		$response = array();
		$this->load->library('category_list_client');
		$this->load->library('MY_sort_associative_array');
		$sorter = new MY_sort_associative_array;
		$categoryClient = new Category_list_client();
		$appId = 1;
		$response['part1'] = json_decode($categoryClient->getCityGroupInSameVirtualCity($appId,$id),true);
		$response['part2'] = json_decode($categoryClient->getZonewiseLocalitiesForCityId($appId,$id),true);
		echo json_encode($response);
	}
	function make_array($an_array) {
		$return_array = array();
		foreach ($an_array as $key => $val) break;
		$return_array[] = $val;
		return $return_array;
	}
	function _select_city_list() {
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
		$this->load->library('MY_sort_associative_array');
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

	function ApiTest($cityid,$zoneid)
	{
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$getZonesForCityId = $categoryClient->getZonesForCityId(1,$cityid);
		$getLocalitiesForZoneId = $categoryClient->getLocalitiesForZoneId(1,$zoneid);
		$getCityGroupInSameVirtualCity = $categoryClient->getCityGroupInSameVirtualCity(1,$cityid);
		echo "<h3>getZonesForCityId</h3>";
		echo "<pre>";print_r(json_decode($getZonesForCityId,true));echo "</pre>";
		echo "<hr>";
		echo "<h3>getLocalitiesForZoneId</h3>";
		echo "<pre>";print_r(json_decode($getLocalitiesForZoneId,true));echo "</pre>";
		echo "<hr>";
		echo "<h3>getCityGroupInSameVirtualCity</h3>";
		echo "<pre>";print_r(json_decode($getCityGroupInSameVirtualCity,true));echo "</pre>";
		echo "<h3>getCitiesForVirtualCity</h3>";
		echo "<pre>";print_r(json_decode($categoryClient->getCitiesForVirtualCity(1,$cityid),true));echo "</pre>";
	}

	function seeuserdetaildump($id1,$id2)
	{
		$this->load->library('LDB_Client');
		$categoryClient = new LDB_Client();
		$userCompleteDetails = $categoryClient->sgetUserDetails(1,$id1);
		echo "<pre>";print_r($this->make_array(json_decode($userCompleteDetails,true)));echo "</pre>";
		echo "<hr>";
		echo "<pre>";print_r(json_decode($categoryClient->sgetSpecializationListByParentId(1,$id2),true));echo "</pre>";
	}

	function runGenerateLeadCron($appId = 1,$page){
		$this->init();
		$marketingClientObj = new MarketingClient();
		$addUser = $marketingClientObj->runGenerateLeadCron($appId,$page);
	}

	function runGenerateLeadCronForConsultants ($appId = 1,$page='+'){
		$this->init();
		$marketingClientObj = new MarketingClient();
		$addUser = $marketingClientObj->runGenerateLeadCronForConsultants($appId,$page);
	}


	function cmsUserValidation()
	{
		$validity = $this->checkUserValidation();
		global $logged;
		global $userid;
		global $usergroup;
		$thisUrl = $_SERVER['REQUEST_URI'];
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
			header('location:/enterprise/Enterprise/loginEnterprise');
			exit();
		} else {
			$logged = "Yes";
			$userid = $validity[0]['userid'];
			$usergroup = $validity[0]['usergroup'];
			if ($usergroup=="user" || $usergroup == "requestinfouser" || $usergroup == "quicksignupuser" || $usergroup == "tempuser" || $usergroup == "fbuser") {
				header("location:/enterprise/Enterprise/migrateUser");
				exit;
			}
			if( !(($usergroup == "cms")) ){
				header("location:/enterprise/Enterprise/unauthorizedEnt");
				exit();
			}
		}
		$this->load->library('enterprise_client');
		$entObj = new Enterprise_client();
		$headerTabs = $entObj->getHeaderTabs(1,$validity[0]['usergroup'],$validity[0]['userid']);
		$this->load->library('sums_product_client');
		$objSumsProduct =  new Sums_Product_client();
		$myProductDetails = $objSumsProduct->getProductsForUser(1,array('userId'=>$userid));
		$returnArr['userid']=$userid;
		$returnArr['usergroup']=$usergroup;
		$returnArr['logged'] = $logged;
		$returnArr['thisUrl'] = $thisUrl;
		$returnArr['validity'] = $validity;
		$returnArr['headerTabs'] = $headerTabs;
		$returnArr['myProducts'] = $myProductDetails;
		return $returnArr;
	}

	function RunCron($dodo){
		ini_set('max_execution_time', '1800');
		$this->load->helper(array('form', 'url','date','image','html'));
		$this->load->library(array('MailerClient','miscelleneous','message_board_client','blog_client','event_cal_client','ajax','category_list_client','listing_client','register_client','enterprise_client','sums_manage_client','table'));
		$objmailerClient = new MailerClient;
		$response = array();
		$response['resultSet'] = $objmailerClient->runCronMailer("1");
	}

	function SmsOldTemplate($prodId)
	{
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$this->init();
		$cmsPageArr = array();
		$cmsPageArr['userid'] = $userid;
		$cmsPageArr['usergroup'] = $usergroup;
		$cmsPageArr['thisUrl'] = $thisUrl;
		$cmsPageArr['validateuser'] = $validity;
		$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
		$cmsPageArr['prodId'] = $this->prodId;
		$cmsPageArr['templateType'] = "sms";
		$response = array();
		$objmailerClient = new MailerClient;
		$response['resultSet'] = $objmailerClient->getAllSmsTemplates($this->appId,$userid,$usergroup);
		$response['countresult'] = count($response['resultSet']);
		$cmsPageArr['response'] = $response;
		$this->load->view('mailer/mailer_homepage',$cmsPageArr);
	}






	// Mailer Home Page View Load
	function index1($prodId)
	{
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$this->init();
		$cmsPageArr = array();
		$cmsPageArr['userid'] = $userid;
		$cmsPageArr['usergroup'] = $usergroup;
		$cmsPageArr['thisUrl'] = $thisUrl;
		$cmsPageArr['validateuser'] = $validity;
		$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
		$cmsPageArr['prodId'] = $this->prodId;
		$cmsPageArr['templateType'] = "mail";
		$response = array();
		$objmailerClient = new MailerClient;
		$response['resultSet'] = $objmailerClient->getAllTemplates($this->appId,$userid,$usergroup);
		$response['countresult'] = count($response['resultSet']);
		$cmsPageArr['response'] = $response;
		$this->load->view('mailer/mailer_homepage',$cmsPageArr);
	}

	function getAllTemplates()
	{
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$this->init();
		$response = array();
		$objmailerClient = new MailerClient;
		$response['resultSet'] = $objmailerClient->getAllTemplates($this->appId,$userid,$usergroup);
		$response['countresult'] = count($response['resultSet']);
		$this->load->view('mailer/all_templates',$response);
	}

	function EditTemplateSms()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$selectedTmpId = $this->input->post('selectedTmpId',true);
		$selectedTmpType = $this->input->post('templateType',true);
		if($selectedTmpType == ""){
			$selectedTmpType = "sms";
		}
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$cmsPageArr = array();
		$cmsPageArr['userid'] = $userid;
		$cmsPageArr['usergroup'] = $usergroup;
		$cmsPageArr['thisUrl'] = $thisUrl;
		$cmsPageArr['validateuser'] = $validity;
		$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
		$cmsPageArr['prodId'] = $this->prodId;
		$cmsPageArr['cmsUserInfo'] = $cmsUserInfo;
		if ($selectedTmpId != '-1') {
			$cmsPageArr['mode'] = 'edit';
			$objmailerClient = new MailerClient;
			$cmsPageArr['resultSet'] = $objmailerClient->getTemplateInfo($this->appId,$selectedTmpId,$userid,$usergroup);
		} else {
			$cmsPageArr['mode'] = 'new';
			$cmsPageArr['resultSet'] = array();
		}
		$cmsPageArr['templateType'] = $selectedTmpType;
		$this->load->view('mailer/edit_template',$cmsPageArr);
	}




	function EditTemplate()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$selectedTmpId = $this->input->post('selectedTmpId',true);
		$selectedTmpType = $this->input->post('templateType',true);
		if($selectedTmpType == ""){
			$selectedTmpType = "mail";
		}
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$cmsPageArr = array();
		$cmsPageArr['userid'] = $userid;
		$cmsPageArr['usergroup'] = $usergroup;
		$cmsPageArr['thisUrl'] = $thisUrl;
		$cmsPageArr['validateuser'] = $validity;
		$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
		$cmsPageArr['prodId'] = $this->prodId;
		$cmsPageArr['cmsUserInfo'] = $cmsUserInfo;
		if ($selectedTmpId != '-1') {
			$cmsPageArr['mode'] = 'edit';
			$objmailerClient = new MailerClient;
			$cmsPageArr['resultSet'] = $objmailerClient->getTemplateInfo($this->appId,$selectedTmpId,$userid,$usergroup);
		} else {
			$cmsPageArr['mode'] = 'new';
			$cmsPageArr['resultSet'] = array();
		}
		$cmsPageArr['templateType'] = $selectedTmpType;
		$this->load->view('mailer/edit_template',$cmsPageArr);
	}

	function UpdateForm()
	{
		global $config;
		//    $config['global_xss_filtering'] = FALSE;
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$request = array();
		$request ['edit_form_mode'] = $this->input->post('edit_form_mode',true);
		$request ['temp_id'] = $this->input->post('temp_id',true);
		$request ['tep_name'] = $this->input->post('temp1_name',true);
		$request ['temp_desc'] = $this->input->post('temp_desc');
		$request ['temp_html'] = $this->input->getRawRequestVariable('temp_html');
		//    echo $request['temp_html'];
		$request ['temp_subj'] = $this->input->post('temp_subj',true);
		$request ['templateType'] = $this->input->post('templateType',true);
		$request ['createdBy'] = $userid;
		$objmailerClient = new MailerClient;
		try {
			$cmsPageArr['resultSet'] = $objmailerClient->insertOrUpdateTemplate($this->appId, $request ['temp_id'], $request['tep_name'], $request ['temp_desc'],$request ['temp_subj'],$request ['temp_html'], $request ['createdBy'], $request ['templateType']);

		} catch (Exception $e) {
			throw $e;
			error_log_shiksha('Error occoured during Template Saving'.$e,'CMS-Mailer');
		}
		$request ['VariablesKey'] = $objmailerClient->getVariablesKey($this->appId);
		// To Do Fix Refersh Reload Problem here
		if ($cmsPageArr['resultSet'][0]['id']) {
			//echo $cmsPageArr['resultSet'][0]['id'];
			$var_result['result'] = $objmailerClient->getTemplateVariables($this->appId, $cmsPageArr['resultSet'][0]['id'],$userid, $usergroup);
			//print_r($var_result['result']);
			$var_result['mode'] = $request ['edit_form_mode'];
			$var_result['temp_id'] = $cmsPageArr['resultSet'][0]['id'];
			$var_result['VariablesKey'] = $request ['VariablesKey'];
			$var_result['templateType'] = $request ['templateType'];
			$this->load->view('mailer/edit_variable_template',$var_result);
		}
	}

	function setTemplateVariables()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$temp_name = $this->input->post('temp_name',true);
		$VariablesKey = $this->input->post('VariablesKey',true);
		$var_name = $this->input->post('var_name',true);
		$temp_id = $this->input->post('temp_id',true);
		$temp_op_mode = $this->input->post('temp_op_mode',true);
		$templateType = $this->input->post('templateType',true);
		$result = array();
		$i = 0;
		foreach ($var_name as $var) {
			$result[$i][] = $var;
			if ($VariablesKey[$i] == -1) {
				$result[$i][] = $temp_name[$i];
				$result[$i][] = 'true';
			} else {
				$result[$i][] = $VariablesKey[$i];
				$result[$i][] = 'false';
			}
			$i++;
		}
		$objmailerClient = new MailerClient;
		try {
			$objmailerClient->setTemplateVariables($this->appId, $temp_id, $result,$userid,$usergroup);
			$var_result['temp_id'] = $temp_id;
			$var_result['temp_op_mode'] = $temp_op_mode;
			$var_result['templateType'] = $templateType;
			$this->load->view('mailer/test_mailer_templates',$var_result);
		}  catch (Exception $e) {
			throw $e;
			error_log_shiksha('Error occoured during Variables Saving'.$e,'CMS-Mailer');
		}
	}

	function getClientIdAndSubscriptionId()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		//		$var_result['thisUrl'] = $thisUrl;
		//		$var_result['validateuser'] = $validity;
		//		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		//		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		//			$var_result['cmsUserInfo'] = $cmsUserInfo;
		// get hidden values from Test mail page
		$edit_form_mode = $this->input->post('edit_form_mode',true);
		$temp_id = $this->input->post('temp_id',true);
		$var_result['edit_form_mode'] = $edit_form_mode;
		$var_result['temp_id'] = $temp_id;
		$sendData = json_encode($var_result);
		error_log($sendData);
		header('location:/enterprise/Enterprise/searchUserForListingPost/22/'.base64_encode($sendData));
		// Load user saved lists
	}


	function setVariables_from_home()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$selectedTmpId = $this->input->post('selectedTmpId',true);
		$var_result['templateType'] = $this->input->post('templateType',true);
		$objmailerClient = new MailerClient;
		$var_result['result'] = $objmailerClient->getTemplateVariables($this->appId,$selectedTmpId,$userid, $usergroup);
		$var_result['mode'] = 'edit';
		$var_result['temp_id'] = $selectedTmpId;
		$VariablesKey = $objmailerClient->getVariablesKey($this->appId);
		$var_result['VariablesKey'] = $VariablesKey;
		$this->load->view('mailer/edit_variable_template',$var_result);
	}
	function dodo(){
		echo "<html><body><div style='display:none'><img src='https://172.16.3.226/mailer/Mailer/blank' /></div></body></html>";
	}
	function blank($redirectUrl, $mailerId, $emailId)
	{
		$this->load->library(array('MailerClient'));
		$objmailerClient = new MailerClient;
		$redirectUrl = base64_decode($redirectUrl);
		if($redirectUrl == "1") {
			$redirectUrl = "";
		}
		$objmailerClient->submitOpenMail($this->appId,$mailerId,$emailId, $redirectUrl);
		if(trim($redirectUrl) == "") {
			echo $redirectUrl;
			exit();
		}
		header('location:'.$redirectUrl);
		exit();
	}



	function SelectUserList()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		// get hidden values from Test mail page
		$edit_form_mode = $this->input->post('edit_form_mode',true);
		$this->load->library(array('Subscription_client'));
		$objSubs = new Subscription_client();
		$subscriptionId = $this->input->post('selectedSubs',true);

		$subDetails=$objSubs->getSubscriptionDetails($appId,$subscriptionId);
		$var_result123 = $this->input->post('extraInfoArray',true);
		$var_result123 = base64_decode($var_result123);
		$var_result = array();
		$var_result  = json_decode($var_result123,true);
		$objmailerClient = new MailerClient;
		$templateInfo = $objmailerClient->getTemplateInfo($this->appId,$var_result['temp_id'],$userid,$usergroup);
		error_log("CDE ".print_r($templateInfo,true));
		$templateType = $templateInfo[0]['templateType'];
		error_log("CDE ".$templateType);
		// Load user saved lists
		$objmailerClient = new MailerClient;
		$var_result['result'] = $objmailerClient->getAllLists($this->appId,$userid,$usergroup);
		$form_array = $this->s_getSearchFormData();
		$var_result['form_array'] = $form_array;
		$sumsData['subscriptionId'] = $subscriptionId;
		$sumsData['clientUser'] = $this->input->post('clientUser',true);;
		$sumsData['BaseProdRemainingQuantity'] = $subDetails[0]['BaseProdRemainingQuantity'];
		$sumsData['BaseProdCategory'] = $subDetails[0]['BaseProdCategory'];
		error_log("CDE ".$sumsData['BaseProdCategory']);
		error_log("CDE ".$templateType);

		preg_match('/'.$templateType.'/',strtolower($sumsData['BaseProdCategory']), $matches, PREG_OFFSET_CAPTURE);
		error_log("CDE ".print_r($matches,true));
		if(count($matches) <= 0) {
			echo "<script>alert('Oops! You Choose Wrong Client/Subscription');history.go(-2);</script>";
		}
		$var_result['sumsData'] = base64_encode(json_encode($sumsData));
		$this->load->view('mailer/SelectUserList_template',$var_result);
	}

	function handle_userlist($flag)
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$objmailerClient = new MailerClient;
		$sumsData = $this->input->post('sums_data',true);
		$var_result['sumsData'] = $sumsData;
		$cvs_array = array();
		if (isset($_REQUEST['selectedTmpId']) &&($_REQUEST['user_list_template']=='use_old_list')) {
			$var_result['selectedListId'] = $this->input->post('selectedTmpId',true);
			$var_result['List_Detail'] = $objmailerClient->getListInfo($this->appId,$var_result['selectedListId'],$userid,$usergroup);
			$var_result['numUsers'] = $var_result['List_Detail'][0]['numUsers'];
		} else if(isset($_FILES['c_csv'])&&($_REQUEST['user_list_template']=='upload_csv')) {
			$templateId = $this->input->post('temp_id',true);
			$templateId = $this->input->post('temp_id',true);
			$cvs_array = $this->buildCVSArray($_FILES['c_csv']['tmp_name']);
			$var_result['result']= $objmailerClient->checkTemplateCsv($this->appId,$templateId,$cvs_array,$userid,$usergroup);
			if($var_result['result'][0] == '-1') {
				$var_result['empty_list_error'] = 'TRUE';
			}
			if($var_result['result'][0] == '-2') {
				$var_result['email_validation_error'] = 'TRUE_EMAIL';
			}
			if ($var_result['result'][0]['isActive'] == 'false') {
				$list_id = $var_result['result'][0]['id'];
				$var_result['new_list_id'] = $list_id;
				$var_result['new_list_name'] = $var_result['result'][0]['name'];
				$var_result['new_list_desc'] = $var_result['result'][0]['description'];
				$var_result['new_usersArr'] = $var_result['result'][0]['usersArr'];
				$var_result['temp_id'] = $templateId;
				$var_result['sumsData'] = $sumsData;
				$this->load->view('mailer/update_New_List_template',$var_result);
			} else {
				$this->load->view('mailer/Summary_List_template',$var_result);
			}
		}
		else if($_REQUEST['user_list_template']=='search_new_criteria')
		{
			$templateId = $this->input->post('temp_id',true);
			$mode =  $this->input->post('edit_form_mode',true);
			$this->save_SearchFormDataParams($templateId,$mode,$userid,$usergroup);
		}
		$var_result['edit_form_mode'] =  $this->input->post('edit_form_mode',true);
		$var_result['temp_id'] = $this->input->post('temp_id',true);
		$var_result['all_Lists'] = $objmailerClient->getAllLists($this->appId,$userid,$usergroup);

		if ($_REQUEST['user_list_template']=='use_old_list')
		{
			$this->load->view('mailer/Edit_List_template',$var_result);
		}
	}

	function UpdateUserList()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$objmailerClient = new MailerClient;
		$var_result['mode'] = $this->input->post('mode',true);
		$var_result['temp_id'] = $this->input->post('temp_id',true);
		$var_result['list_id'] = $this->input->post('list_id',true);
		$var_result['mails_limit_text'] = $this->input->post('mails_limit_text',true);
		$var_result['mails_limit'] = $this->input->post('mails_limit',true);
		$var_result['export_csv'] = $this->input->post('export_csv',true);
		$var_result['all_Lists'] = $this->input->post('all_Lists',true);
		$var_result['sumsData'] = $this->input->post('sumsData',true);
		if($var_result['export_csv'] == "on") {
			if (!empty($var_result['mails_limit_text']) && is_numeric($var_result['mails_limit_text'])) {
				$numEmail = $var_result['mails_limit_text'];
			} else if (!empty($var_result['mails_limit']) && ($var_result['mails_limit'] == '-1')) {
				$numEmail = $var_result['mails_limit'];
			}

			header("Content-type: text/x-csv");
			$filename =preg_replace('/[^A-Za-z0-9]/', '',"UserList_".$var_result['list_id']);
			header("Content-Disposition: attachment; filename=".$filename.".csv");
			$finalOutput = "";
			$returnData = $objmailerClient->getListCsv($this->appId,$var_result['list_id'],$list,$numEmail,$userid,$usergroup);
			$csvData = $returnData[0]['usersArr'];
			$csvDataArr = json_decode($csvData,true);
			$finalOutput = "displayname,userId,email,mobile";
			for($i = 0; $i < count($csvDataArr);$i++) {
				$finalOutput .= "\n".$csvDataArr[$i]['displayname'].",".$csvDataArr[$i]['userId'].",".$csvDataArr[$i]['email'].",".$csvDataArr[$i]['mobile'];
			}
			echo $finalOutput;
			exit(0);
		}
		if (!empty($var_result['mails_limit_text']) && is_numeric($var_result['mails_limit_text'])) {
			$numEmail = $var_result['mails_limit_text'];
		} else if (!empty($var_result['mails_limit']) && ($var_result['mails_limit'] == '-1')) {
			$numEmail = $var_result['mails_limit'];
		}
		if (empty($var_result['all_Lists'])){
			$list = array();
		} else {
			$list = $var_result['all_Lists'];
		}
		$var_result['result'] = $objmailerClient->submitList($this->appId,$var_result['list_id'],$list,$numEmail,$userid,$usergroup);
		if ($var_result['result'][0]['isActive'] == 'false') {
			$list_id = $var_result['result'][0]['id'];
			$var_result['new_list_id'] = $list_id;
			$var_result['new_list_name'] = $var_result['result'][0]['name'];
			$var_result['new_list_desc'] = $var_result['result'][0]['description'];
			$var_result['new_usersArr'] = $var_result['result'][0]['usersArr'];
			$var_result['numUsers'] = $var_result['result'][0]['numUsers'];
			$this->load->view('mailer/update_New_List_template',$var_result);
		} else {
			$this->load->view('mailer/Summary_List_template',$var_result);
		}
	}

	function update_New_List_template()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$objmailerClient = new MailerClient;
		$listId = $this->input->post('list_id',true);
		$name = $this->input->post('temp1_name',true);
		$desc = $this->input->post('temp_desc',true);
		$var_result['mode'] = $this->input->post('mode',true);
		$var_result['temp_id'] = $this->input->post('temp_id',true);
		$var_result['list_id'] = $listId;
		$var_result['sumsData'] = $this->input->post('sumsData',true);
		$var_result['result'] = $objmailerClient->updateListInfo($this->appId, $listId, $name, $desc, $userid, $usergroup);
		$this->load->view('mailer/Summary_List_template',$var_result);
	}

	function Summary_List_template()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$objmailerClient = new MailerClient;
		$list_id = $this->input->post('list_id',true);
		$temp_id = $this->input->post('temp_id',true);
		$mode = $this->input->post('mode',true);
		$userFeedbackEmail = $this->input->post('userFeedbackEmail',true);
		$trans_start_date = $this->input->post('trans_start_date',true);
		$temp1_name = $this->input->post('temp1_name',true);
		$sumsData = $this->input->post('sumsData',true);
		try {
			$result = $objmailerClient->saveMailer($this->appId,$temp1_name,$temp_id,$list_id,$trans_start_date,$userFeedbackEmail, $userid, $usergroup,$sumsData);
			error_log("CONSUME RETURN ".print_r($result,true));
			$success_flag = "false";
			if ( !($result['ERROR'] == 1)) {
				$success_flag = "true";
			} else {
				$success_flag = "false";
			}
			error_log("CONSUME sucess Flag = ".$success_flag);
			$var_result['list_id'] = $list_id;
			$var_result['temp_id'] = $temp_id;
			$var_result['mode'] = $mode;
			$var_result['trans_start_date'] = $trans_start_date;
			$var_result['temp1_name'] = $temp1_name;
			$var_result['success_flag'] = $success_flag;
			$var_result['last_mailer_id'] = $result[0];
			$this->load->view('mailer/Summary_List_Result_template',$var_result);
		} catch (Exception $e) {
			throw $e;
			error_log(' CONSUME Error occoured during save Mailer'.$e,'CMS-Mailer');
		}
	}
	/*
	 * For Ajax call to test html mail
	 */
	function Test_mail_List($TempId, $email)
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->sendTestMailer($this->appId,$TempId,$email,$userid,$usergroup);
		if ( !empty($result[0]) && is_numeric($result[0])) {
			echo $result[0];
		}
		else if ($TempId == 'null') {
			echo '-1';
		}
	}

	function s_getSearchFormData()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->s_getSearchFormData($this->appId);
		/* Generate Array for Form Start */
		$form_array = array();
		if (count(json_decode($result[0])) > 0 ) {
			$i = 0;
			foreach (json_decode($result[0]) as $value) {
				if (is_object($value)) {
					$option = array();
					foreach ($value as $element) {
						if (is_object($element)) {
							$option[] = array(
							$element->filterValueId  =>
							$element->filterValueName
							);
						}
					}
					$form_array[$value->filterType][$value->filterId][$value->filterName][] = $option;
				}
				$i++;
			}
		}
		/* Generate Array for Form End */
		return $form_array;
	}

	function save_SearchFormDataParams($temp_id,$mode,$userid,$usergroup)
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$var_result['temp_id'] = $temp_id;
		$objmailerClient = new MailerClient;
		$sumsData = $this->input->post('sums_data',true);
		$var_result['sumsData'] = $sumsData;
		$this->load->helper('security');
        $data = xss_clean($_REQUEST);
		if(count($data) > 0 ) {
			$sendArr = array();
			foreach ($_REQUEST as $key=>$value) {
				if (ereg('combo_search',$key)) {
					$sendArr[$key[0]] = $value;
				}
				if (ereg('checkbox_search',$key)) {
					$sendArr[$key[0]] = $value;
				}
				if (ereg('range_search',$key)) {
					$sendArr[$key[0]][] = array(
							 'value'=>$value[0],
							 'id'=>$key[1]
					);
				}
				if (ereg('date_search',$key)) {
					$sendArr[$key[0]][] = array(
							 'value'=>$value[0],
							 'id'=>$key[1]
					);
				}
			}
		}
		/* Hack to handle error */
		$flag_empty_array = true;
		foreach ($_REQUEST as $k=>$v) {
			if (ereg('_search',$k)) {
				foreach ($v as $val) {
					if(empty($val)) {
						$flag_empty_array = false;
					} else {
						$flag_empty_array = true;
						break 2;
					}
				}
			}
		}
		if ($flag_empty_array == false) {
			$var_result['empty_form_selection'] = 'TRUE';
			$this->load->view('mailer/Summary_List_template',$var_result);
		} else {
			$result['result']= $objmailerClient->s_submitSearchQuery($this->appId,$sendArr,$userid,$usergroup);
		}
		if ($result['result'][0] != '-1') {
			$list_id = $result['result'][0]['id'];
			$var_result['new_list_id'] = $list_id;
			$var_result['new_list_name'] = $result['result'][0]['name'];
			$var_result['new_list_desc'] = $result['result'][0]['description'];
			$var_result['new_usersArr'] = $result['result'][0]['usersArr'];
			$var_result['numUsers'] = $result['result'][0]['numUsers'];
			$var_result['edit_form_mode'] =  $mode;
			$var_result['temp_id'] = $temp_id;
			$var_result['all_Lists'] = $objmailerClient->getAllLists($this->appId,$userid,$usergroup);
			$var_result['selectedListId'] = $list_id;
			$var_result['List_Detail'] = $objmailerClient->getListInfo($this->appId,$list_id,$userid,$usergroup);

			$this->load->view('mailer/Edit_List_template',$var_result);
		}
		else
		{
			if ($result['result'][0] == '-1'){
				$var_result['error_empty_array'] = 'TRUE';
			}
			$this->load->view('mailer/Summary_List_template',$var_result);
		}
	}

	function buildCVSArray($File) {
		$handle = fopen($File, "r");
		$fields = fgetcsv($handle, 1000, ",");
		while($data = fgetcsv($handle, 1000, ",")) {
			$detail[] = $data;
		}
		$x = 0;
		foreach($fields as $z) {
			foreach($detail as $i) {
				$stock[$z][] = $i[$x];
			}

			$x++;
		}
		return $stock;
	}

	function MisReportDisplay()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$objmailerClient = new MailerClient;
		$var_result['resultSet']= $objmailerClient->getMailersList($this->appId,$userid,$usergroup);
		$var_result['countresult'] = count($var_result['resultSet']);
		$this->load->view('mailer/MisReportDisplay',$var_result);

	}

	function getMailerTrackingUrls($id)
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$objmailerClient = new MailerClient;
		$var_result['resultSet']= $objmailerClient->getMailerTrackingUrls($this->appId,$userid,$usergroup,$id,'','','');
		$var_result['countresult'] = count($var_result['resultSet']);
		$var_result['id'] = $id;
		$this->load->view('mailer/getMailerTrackingUrls',$var_result);
	}

	function MailerTrackingUrlsformsubmit()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$objmailerClient = new MailerClient;
		$mailerId = $this->input->post('id',true);
		$startTime = $this->input->post('trans_start_date',true);
		$endTime = $this->input->post('trans_end_date',true);
		$var_result['resultSet']= $objmailerClient->getMailerTrackingUrls($this->appId,$userid,$usergroup,$mailerId,'',$startTime,$endTime);
		$var_result['countresult'] = count($var_result['resultSet']);
		$var_result['id'] = $this->input->post('id',true);;
		$this->load->view('mailer/getMailerTrackingUrls',$var_result);
	}

	function poll_form_submit($poll_quest,$ans1,$ans2,$ans3)
	{
		$optionArray = array();
		$optionArray = array($ans1,$ans2,$ans3);
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->createPoll($this->appId,$poll_quest,$poll_quest,$optionArray);
		//print_r($result);die;
		$content = '<div style="text-align: right; width: 170;">
					<form action="'.SHIKSHA_HOME_URL.'/mailer/Mailer/userPollOpinion" method="POST">
					<table width="170" cellpadding="2" cellspacing="0" border="0" style="background-color: none; border: 1px #333333 solid;">
					<tr><td align="center" colspan="2" style="color: #000000; font-family: Verdana; font-weight: bold;">'.$poll_quest.'<br><br></td></tr>
					<tr><td><input type="radio" name="answer"  value="'.$result['pollOption'][0]['optionId'].'"/></td>
					<td width="100%"><label for="'.$result['pollOption'][0]['optionId'].'" style="color: #000000; font-family: Verdana;">'.$result['pollOption'][0]['optionName'].'</label></td></tr>
					<tr><td><input type="radio" name="answer"  value="'.$result['pollOption'][1]['optionId'].'"/></td>
					<td width="100%"><label for="'.$result['pollOption'][1]['optionId'].'" style="color: #000000; font-family: Verdana;">'.$result['pollOption'][0]['optionName'].'</label></td></tr>
					<tr><td><input type="radio" name="answer"  value="'.$result['pollOption'][2]['optionId'].'"/></td>
					<td width="100%"><label for="'.$result['pollOption'][2]['optionId'].'" style="color: #000000; font-family: Verdana;">'.$result['pollOption'][0]['optionName'].'</label></td></tr>
					<tr><td colspan="2" align="center">
					<br><input type="submit" value="Vote" style="border: 1px #333333 solid;"/><br>
					<input type="hidden" name="poll_id" value="'.$result['poll_id'].'"/>
					<input type="hidden" name="emailId" value="<!-- #varNamemailId --><!-- varNamemailId# -->"/>
                    <input type="hidden" name="trackerId" value="<!-- #varNametrackerId --><!-- varNametrackerId# -->"/>
					</td></tr>
					</table></form></div>';
		echo $content;
	}
	function userPollOpinion()
	{
		//        print_r($_POST);
		$poll_id = $this->input->post('poll_id');
		$poll_opinion = $this->input->post('answer');
		$email = $this->input->post('emailId');
		$mailer_id = $this->input->post('trackerId');
		//$userOpinion = $_POST['answer[]'];
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->registerPoll($this->appId,$mailer_id, $email, $poll_id, $poll_opinion);
		echo "<script>alert('Thank you for submitting your Vote! Please visit shiksha.com for all education related Queries!!');location.href = 'https://www.shiksha.com' ;</script>";
	}

	function registerAutomaticFeedback($data) {
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->registerData($this->appId,$data);
		echo "<script>alert('Thank you for showing your interest!!');location.href = 'https://www.shiksha.com' ;</script>";
	}

	function leadFeedback() {
		error_log("POI".$feedback);
		$email = $this->input->post('code');
		$mailer_id = $this->input->post('trackerId');
		$typeId = $this->input->post('typeId');
		$type = $this->input->post('type');
		$feedback = $this->input->post('feedback');
		error_log("POI".$feedback);


		//$userOpinion = $_POST['answer[]'];
		//print_r($_POST);
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->registerLead($this->appId,$mailer_id, $email, $feedback, $typeId, $type);
		echo "<script>alert('Thank you for submitting your Feedback! Please visit shiksha.com for all education related Queries!!');location.href = 'https://www.shiksha.com' ;</script>";

	}

	function userFeedback()
	{
		$email = $this->input->post('emailId');
		$mailer_id = $this->input->post('trackerId');
		$feedback = $this->input->post('feedback');
		//$userOpinion = $_POST['answer[]'];
		//print_r($_POST);
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->registerFeedback($this->appId,$mailer_id, $email, $feedback);
		echo "<script>alert('Thank you for submitting your Feedback! Please visit shiksha.com for all education related Queries!!');location.href = 'https://www.shiksha.com' ;</script>";
	}

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

	public function studyAbroad($pagename='studyAbroad',$url='',$category_data='',$config_data_array='',$pageId) {

		if((empty($config_data_array['count_courses']))||(empty($config_data_array['header_text']))|| (empty($config_data_array['form_heading']))) {
			header("Status: 404 Not Found");
			header('Location:   /shikshaHelp/ShikshaHelp/errorPage');
			exit;
		}
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
		$data['userData'] = $this->userStatus;
		$data['extraPram'] = $extraPram;
		$data['backPage'] = $backPageUrl;
		$data['config_data_array'] = $config_data_array;
		$data['pageName'] = 'studyAbroad';
		$data['pageId'] = $pageId;
		// load data for courses list
		//if(empty($category_data)){
		$this->load->library('Category_list_client');
		$category_list_client = new Category_list_client();
		//$categoryList = $category_list_client->getCategoryList(1,1);
		//$catgeories = array();
		//foreach($categoryList as $category) {
		//$categories[$category['categoryID']] = $category['categoryName'];
		//}//
		//asort($categories);
		$data['categories'] = $category_data;
		//echo "<pre>";
		//print_r($category_data);
		//echo "</pre>";
		//}
		$regions= json_decode($category_list_client->getCountriesWithRegions(1), true);
		$data['regions'] = $regions;
		$data['course_lists'] = $this->_course_lists();

		$cityListTier1 = $category_list_client->getCitiesInTier($appId,1,2);
		$cityListTier2 = $category_list_client->getCitiesInTier($appId,2,2);
		$cityListTier3 = $category_list_client->getCitiesInTier($appId,0,2);
		$data['cityTier2'] = $cityListTier2;
		$data['cityTier3'] = $cityListTier3;
		$data['cityTier1'] = $cityListTier1;
		$ldbObj = new LDB_Client();
		$data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12),true);
		$this->load->view('multipleMarketingPage/oldForm/generalMarketingPage', $data);
	}

	function getNextOverlayForStudyAbroad($keyname = 'MARKETING_FORM',$prefId="", $desiredCourseLevel = 'PG') {
		$this->init();
		$data = array();
		$validity = $this->checkUserValidation();
		$url = 'multipleMarketingPage/studyAbroadOverlay';
		$appId = 1;
		$this->load->library('LDB_Client');
		$categoryClient = new LDB_Client();
		$userid =  $this->userid;
		if($userid != '')
		$userCompleteDetails = $categoryClient->sgetUserDetails($appId,$userid);
		$data['prefId'] = $prefId;
		$data['userCompleteDetails'] = $this->make_array(json_decode($userCompleteDetails,true));
		$data['desiredCourseLevel'] = $desiredCourseLevel;
		$data['validateuser'] = $validity;
		$string = $this->load->view($url,$data,true);
		echo $string;
	}
	
	
	/**
	 * Function added for customized MMP
	 * returns MMP form HTML
	 */
	public function customizedmmp($page_id) {
		$this->init();
		$validity = $this->checkUserValidation();
		global $logged;
		global $userid;
		$data = array();
		$thisUrl = $_SERVER['REQUEST_URI'];
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
		} else {
			$logged = "Yes";
		}
		$userName = '';
		$userEmail = '';
		$userInterest = -1;
		$userPassword = '';
		$post_user_name = trim($this->input->post('user_name'));
		if(!empty($post_user_name)) {
			$post_user_email = trim($this->input->post('user_email'));
			$post_user_interest = trim($this->input->post('user_interest'));
			$post_user_password = trim($this->input->post('user_password'));
			$post_user_contactno = trim($this->input->post('user_contactno'));
			$userName = isset($post_user_name) ? $post_user_name : '';
			$userEmail = isset($post_user_email) ? $post_user_email : '';
			$userInterest = isset($post_user_interest) ? $post_user_interest: '';
			$userPassword = isset($post_user_password) ? $post_user_password : '';
			$userContactno = isset($post_user_contactno) ? $post_user_contactno :'';
		}
		if (strlen($userName) > 100) {
            $userName = substr($userName,0,100);
        }
        if (strlen($userEmail) > 125) {
            $userEmail = substr($userEmail,0,125);
        }
        if (strlen($userContactno) > 10) {
            $userContactno = substr($userContactno,0,10);
        }
		$data['userName'] = $this->escape($userName);
		$data['userEmail'] = $this->escape($userEmail);
		$data['userInterest'] = $userInterest;
		$data['userPassword'] = $userPassword;
		$data['userContactno'] = $this->escape($userContactno);
		$data['logged'] = $logged;
		$data['userData'] = $this->userStatus;
		$data['validateuser'] = $validity;
		$userid =  $this->userid;
		$data['prefix'] = "";
		
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
		$cityListTier2 = $categoryClient->getCitiesInTier($appId,2,2);
		$cityListTier3 = $categoryClient->getCitiesInTier($appId,0,2);
		$data['cityTier2'] = $cityListTier2;
		$data['cityTier3'] = $cityListTier3;
		$data['cityTier1'] = $cityListTier1;
        $data['select_city_list'] = $this->_select_city_list();
		
		$data1= $this->_marketing_page($userid);
		$data1['course_lists'] = $this->_course_lists();
		
		
		$this->load->library('customizedmmp/customizemmp_lib');
		$customizedMMPLib = new customizemmp_lib();
		$config_data_array = $customizedMMPLib->marketingPageDetailsById($page_id);
		
		$pagetype = $config_data_array['page_type'];
		$formTemplateName = $this->_getFormTemplateName($page_id, $pagetype);
		if($pagetype!='abroadpage') {
			$data = array_merge($data1,(array)$data,$this->_load_customized_mmp_page_data($page_id));
		} else if($pagetype=='abroadpage') {
			$saved_courses_lists= json_decode($this->MarketingPageClient->getStudyAbroadCoursesListForApage(1,$page_id,$pagetype,'saved_list'),true);
			$savedcatgeories = array();
			foreach($saved_courses_lists['courses_list'] as $category) {
				$savedcatgeories[$category[0]['categoryID'][0]] = $category[0]['categoryName'][0];
			}
			asort($savedcatgeories);
			if(empty($url)){
				$backPageUrl = '';
			} else {
				$backPageUrl = $url;
			}
			$data['logged'] = $logged;
			$data['userName'] = '';
			$data['userEmail'] = '';
			$data['userInterest'] = -1;
			$data['userPassword'] = '';
			$data['extraPram'] = $extraPram;
			$data['backPage'] = $backPageUrl;
			$data['config_data_array'] = $config_data_array;
			$data['pageName'] = 'studyAbroad';
			$this->load->library('Category_list_client');
			$category_list_client = new Category_list_client();
			$data['categories'] = $savedcatgeories;
			$regions= json_decode($category_list_client->getCountriesWithRegions(1), true);
			$data['regions'] = $regions;
			$data['course_lists'] = $this->_course_lists();
			$ldbObj = new LDB_Client();
			$data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12),true);
		}
		$formTemplatePath = 'customizedmmp/'.$formTemplateName;
		$formHTML = $this->load->view($formTemplatePath, $data, true);
		return $formHTML;
	}
	
	private function _getFormTemplateName($page_id, $page_type) {
		$this->load->library('customizedmmp/customizemmp_lib');
		$customizedMMPLib = new customizemmp_lib();
		$pageDetails = $customizedMMPLib->marketingPageDetailsById($page_id);
		$templateName = "";
		if($page_type == 'abroadpage'){
			$templateName = $pageDetails['template_name'] ."_studyabroad";
		} else {
			$templateName = $pageDetails['template_name'];
		}
		return $templateName;
	}
	
	private function _load_customized_mmp_page_data($page_id) {
		$this->init();
		$this->load->library('customizedmmp/customizemmp_lib');
		$customizedMMPLib = new customizemmp_lib();
		$config_data_array = $customizedMMPLib->marketingPageDetailsById($page_id);
		$data['config_data_array'] = $config_data_array;
		$data['pagetype'] = $data['config_data_array']['page_type'];
		$data['formPostUrl'] = '/user/Userregistration/MultipleMarketingPage/seccodehome';
		if($data['pagetype']=='indianpage') {
			$data1= json_decode($this->MarketingPageClient->getCourselistForApage($page_id,'group'),true);
			$data['itcourseslist']= $data1['courses_list'];
			$data['managementcourses'] = $this->MarketingPageClient->getManagementCourses(trim(str_replace(',',' ',$data1['management_courseids'])));
		} else if($data['pagetype']=='testpreppage') {
			$data['itcourseslist']= json_decode($this->MarketingPageClient->getTestPrepCoursesListForApage(1,$page_id,$pagetype,'saved_list'),true);
			$data['itcourseslist']= $data['itcourseslist']['courses_list'];
		}
		return $data;
	}
	
	function customizedMMPAjaxForm($flag) {
		$this->init();
		global $logged;
		$data = array();
		$validity = $this->checkUserValidation();
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
		} else {
			$logged = "Yes";
		}
		$data['logged'] = $logged;
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
		$cityListTier2 = $categoryClient->getCitiesInTier($appId,2,2);
		$cityListTier3 = $categoryClient->getCitiesInTier($appId,0,2);
		$data['cityTier1'] = $cityListTier1;
		$data['cityTier2'] = $cityListTier2;
		$data['cityTier3'] = $cityListTier3;
		$this->load->library('LDB_Client');
		$ldbObj = new LDB_Client();
		$data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12),true);
		$userid =  $this->userid;
		$appId = 1;
		$data['select_city_list'] = $this->_select_city_list();
		$this->load->library('LDB_Client');
		$categoryClient = new LDB_Client();
		$listing_client = new listing_client();
		$cityListTier = $data['country_state_city_list'];
		$course_list = json_decode($categoryClient->sgetCourseList($appId,3),true);
		$userCompleteDetails = '';
		if($userid != '')
		$userCompleteDetails = $categoryClient->sgetUserDetails($appId,$userid);
		$userDataToShow = $this->makeUserData($userCompleteDetails);
		$countryCityStateMaps = $this->getCityStatesFromMap($cityListTier);
		$data['countriesList'] = $countryCityStateMaps['countries'];
		$data['statesList'] = $countryCityStateMaps['states'];
		$data['citiesList'] = $countryCityStateMaps['cities'];
		$data['userDataToShow'] = $userDataToShow;
		$data['newDesiredCourse_list'] = $course_list;
		$data['userCompleteDetails'] = $userCompleteDetails;
		$data['CitiesWithCollege']  = $listing_client->getCitiesWithCollege(1,2);
		$data['other_exm_list'] = $this->_other_exm_list();
		$data['work_exp_combo'] = $this->_work_exp_combo();
		if($userid != '') {
			//$userCompleteDetails = $this->make_array(json_decode($userCompleteDetails,true));
			//$TimeOfStart =
			//$userCompleteDetails[0]['PrefData'][0]['TimeOfStart'];
			//$data['when_you_plan_start'] = $this->_when_you_plan_start($TimeOfStart,FALSE);
			//$array_ug_courses = $userCompleteDetails[0]['EducationData'];
			//$PrefData = $userCompleteDetails[0]['PrefData'];
			//foreach ($PrefData as $value) {
			//	if (count($value['LocationPref']) > 0) {
			//		$data['LocalityCityValue'] = $value['LocationPref'][0]['CountryId'].":".$value['LocationPref'][0]['StateId'].":".$value['LocationPref'][0]['CityId'].":".$value['LocationPref'][0]['LocalityId'];
			//		$data['LocalityCityName'] = $value['LocationPref'][0]['LocalityName'];
			//	}
			//}
			//$name_ug_course = '';
			//$ug_marks = '';
			//$CourseCompletionDate = '';
			//foreach ($array_ug_courses as $data_ug) {
			//	if ($data_ug['Level'] == 'UG') {
			//		$name_ug_course = $data_ug['Name'];
			//		$ug_marks = $data_ug['Marks'];
			//		$CourseCompletionDate = $data_ug['CourseCompletionDate'];
			//		$ug_city_id = $data_ug['City'];
			//		$ug_institute_id = $data_ug['InstituteId'];
			//		$ug_institute_name = trim($data_ug['institute_name']);
			//	}
			//}
			//$data['course_lists'] = $this->_course_lists($name_ug_course,FALSE);
			//$data['ug_marks'] = $ug_marks;
			//$data['CourseCompletionDate'] = $CourseCompletionDate;
			//$data['ug_city_id'] = $ug_city_id;
			//$data['ug_institute_id'] = $ug_institute_id;
			//$data['ug_institute_name'] = $ug_institute_name;
			
			$data['when_you_plan_start'] = $this->_when_you_plan_start();
			$data['course_lists'] = $this->_course_lists();
			
			
		} else {
			$data['when_you_plan_start'] = $this->_when_you_plan_start();
			$data['course_lists'] = $this->_course_lists();
		}
		$data['prefix'] = "";
		$data['formPostUrl'] = '/user/Userregistration/MultipleMarketingPage/seccodehome';
		if ($flag == 'mr_page') {
			echo $this->load->view('customizedmmp/customizedMMPLocationLayer',$data);
		} elseif ($flag == 'mr_user_sign') {
			echo $this->load->view('customizedmmp/customizedMMPSignInOverlay',$data);
		} elseif ($flag == 'itcourse') {
			echo $this->load->view('customizedmmp/customizedMMPCourses',$data);
		} elseif ($flag == 'itdegree') {
			echo $this->load->view('customizedmmp/customizedMMPDegree',$data);
		}elseif ($flag == 'graduate_course') {
			echo $this->load->view('customizedmmp/customizedMMPUGCourses',$data);
		}elseif ($flag == 'show_xii_field') {
		  echo
		  $this->load->view('multipleMarketingPage/xii_details_forms_field',$data);
		}elseif ($flag == 'show_grad_field') {
		  echo
		  $this->load->view('multipleMarketingPage/ug_details_forms_field',$data);
		}
	}
	
	public function tracker($pageType,$pageId)
	{
		$data = array();
		$data['pageId'] = $pageId;
		$this->load->view('multipleMarketingPage/tracker_chart',$data);
	}
	
	public function getUserRedirectionURL($mmpId,$display = 1)
	{
		$this->init();
		$this->load->model('customizedmmp/customizemmp_model');
		$this->load->library('categoryList/categoryPageRequest');
        $categoryPageRequest = new CategoryPageRequest;
		
		/**
		 * Get MMP page details
		 */ 
		$mmpData = $this->MarketingPageClient->marketingPageDetailsById($mmpId);
		/**
		 * If a redirect URL is set for MMP, set it as redirect URL
		 */ 
		$redirectURL = trim($mmpData['destination_url']);
		if(!$redirectURL) {
			
			if($mmpData['page_type'] == 'abroadpage') {
				$redirectURL = SHIKSHA_HOME;
			}
			else if($mmpData['page_type'] == 'testpreppage') {
				$categories = $this->customizemmp_model->getTestPrepMMPCourseCategories($mmpId);
				if(count($categories) > 1) {
					$categoryPageRequest->setData(array('categoryId' => 14));
				}
				else {
					$blogIdToNewSubCategoryMapping = array(
						298 => 48,
						300 => 51,
						464 => 54,
						299 => 47,
						297 => 49
					);
					$categoryPageRequest->setData(array('subCategoryId' => $blogIdToNewSubCategoryMapping[$categories[0]]));
				}
				$redirectURL = $categoryPageRequest->getURL();
			}
			else {
				$categories = $this->customizemmp_model->getMMPCourseCategories($mmpId);
				$mainCategories = array();
				$subCategories = array();
				foreach($categories as $category) {
					$mainCategories[$category['category']] = $category['category'];
					$subCategories[$category['subcategory']] = $category['subcategory'];
				}
				if(count($subCategories) == 1) {
					$categoryPageRequest->setData(array('subCategoryId' => array_shift($subCategories)));
				}
				else {
					$categoryPageRequest->setData(array('categoryId' => array_shift($mainCategories)));
				}
				$redirectURL = $categoryPageRequest->getURL();
			}
		}
		if($display) {
			echo $redirectURL;
		}
		else {
			return $redirectURL;
		}
	}

	function customMarketingPage()
	{
		header("Location: ".SHIKSHA_HOME_URL);
		exit();
		$this->init();
		$validity = $this->checkUserValidation();
		
		if(is_array($validity) && is_array($validity[0]) && $validity[0]['userid']) {
			$loggedInUserId = $validity[0]['userid'];
			$this->load->library('user/UserLib');
			$userLib = new UserLib;
			$redirectURL = $userLib->getLandingPageURL($loggedInUserId);
			if(!$redirectURL) {
				$redirectURL = "https://www.shiksha.com";
			}
			header("Location: ".$redirectURL);
			exit();
		}
		
		$this->load->view('customMarketingPage');
	}
	
	public function loadNewMobileMMP($data) {		
        $this->init();
		
        $this->load->library("customizedmmp/mmp_template_uploader");
        $mmp_template_uploader_lib = new mmp_template_uploader();
		
		$mmpData = $this->getNewMMPMobileContent($data);		

		$this->load->view('multipleMarketingPage/newGeneralMarketingPageMobile', $mmpData);

    }
	
	public function getNewMMPMobileContent($data) {
		$pageId =  $data['pageId'];
		$mmp_details = $data['mmp_details'];

		//below line is used for conversion tracking purpose
		$trackingKey['trackingPageKeyId'] = $data['trackingPageKeyId'];
		$isMobile = isMobileRequest();
		
		if(!empty($mmp_details['form_heading'])) {
			$form_heading = $mmp_details['form_heading'];
		} else {
			$form_heading = 'Find the best institutes for you';
		}
		
		$header = $this->load->view('registration/newmobilemmp/national/MMPHeader',array('isMobile' => $isMobile, 'isSA'=>$data['config_data_array']['page_type']),TRUE);
		
        $form = Modules::run('registration/Forms/MMP', $pageId,0,$trackingKey);

        if($data['config_data_array']['page_type'] == 'abroadpage'){
        	$logoType = '<img src="'.MEDIA_SERVER.'/public/images/abroad_mailer_logo2.png"  />';
        	$pageType = 'abroad';
        }else{
        	$logoType = '<img src="'.MEDIA_SERVER.'/public/mobile5/images/mobile_logo1.png"  />';
        	$pageType = 'national';
        }

        if($data['mmp_details']['header_image'] != '' && !empty($data['mmp_details']['header_image'])){

	        $header_url =  MEDIA_SERVER.$data['mmp_details']['header_image'];
	        $header_url =  '<img style="margin-left: auto; margin-right: auto; display: block;" src="'.$header_url.'"  />';
        }

        $mmpData = array(
			'MMPFORM' => $form,
			'MMPHEADER' => $header,
			'FORM_HEADING' => $form_heading,
			'PIXEL_CODE'=>base64_decode($mmp_details['pixel_codes']),
			'logoType' => $logoType,
			'pageType' => $pageType,
			'header_image_url' => $header_url
        );
        
        if($isMobile) {
			
            if($mmp_details['page_type'] == 'abroadpage') {
				
                $destinationCountryLayerMobile = $this->load->view('/muser5/destinationCountry',array(),TRUE);
                $mmpData['DESTINATIONCOUNTRYLAYERMOBILE'] = $destinationCountryLayerMobile;
				
            } else {
				
                $preferredLocationLayerMobile = $this->load->view('/muser5/preferredStudyLocation',array(),TRUE);
                $mmpData['PREFERREDLOCATIONLAYERMOBILE'] = $preferredLocationLayerMobile;
				
            }
			
        }
        
        return $mmpData;
	}

	public function loadMMPNational($mmp_details) {
		$userData = $this->checkUserValidation();
		$userid = $userData[0]['userid'];
		$mobileverified = $userData[0]['mobileverified'];
		if(!empty($userid) && $userid>0 && $mobileverified==1){
			if(empty($mmp_details['destination_url'])){
				redirect(SHIKSHA_HOME, 'location');
			}
			else{
				redirect($mmp_details['destination_url'],'location');
			}
		}
		if(isMobileRequest()) {
			$this->loadMMPNationalMobile($mmp_details);
		} else {
			$this->loadMMPNationalDesktop($mmp_details);
		}

	}

	public function loadMMPNationalDesktop($mmp_details) {

		$this->init();
		$this->config->load('multipleMarketingPage/MMPConfig', TRUE);
		//$this->config->load('MMPConfig', TRUE);
		$MMPConfigForHindiFont = $this->config->item('MMPConfig');
		$availableHindiFont = $MMPConfigForHindiFont['hindiFont'];
		if ($availableHindiFont[$mmp_details['page_id']] && $mmp_details['formCustomization']['language']=='hi'){
			$mmp_details['form_heading'] = $availableHindiFont[$mmp_details['page_id']]['form_heading'];
			$mmp_details['subheading'] = $availableHindiFont[$mmp_details['page_id']]['subheading'];
			$mmp_details['submitButtonText'] = $availableHindiFont[$mmp_details['page_id']]['submitButtonText'];

		}
		
		unset($availableHindiFont);
		
		$data = $this->loadMMPNationalDesktopContent($mmp_details);	
		$this->load->view('multipleMarketingPage/marketingPageDesktop', $data);

	}

	public function loadMMPNationalDesktopContent($mmp_details) {

		$validity = $this->userStatus;		
		global $logged;		
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
		} else {
			$logged = "Yes";
		}

		$data = array();
		$data['load_ga'] = TRUE;
		$data['mmp_details'] = $mmp_details;
		$data['trackForPages'] = true;
		$data['logged'] = $logged;

		if(isset($mmp_details['background_url']) && trim($mmp_details['background_url']) !== '') {
			
			$pos = strpos($mmp_details['background_url'], '?');
			
			if($pos === false) {
					$mmp_details['background_url'] = $mmp_details['background_url'].'?mmpbeacon=1';
			} else {
					$mmp_details['background_url'] = $mmp_details['background_url'].'&mmpbeacon=1';
			}
						
			$data['bg_url'] = 'src = "'.$mmp_details['background_url'].'"';
			
		} else {
			if(isset($mmp_details['background_image'])) {
				
				$data['bg_image'] = "background-image: url('".MEDIA_SERVER.$mmp_details['background_image']."'); background-repeat: no-repeat; background-position: top center;";
				
			}
		}
		
		$data['submitButtonText'] = $mmp_details['submitButtonText'];

		global $MMP_Tracking_keyId;
        $data['trackingKeyId'] = $MMP_Tracking_keyId['desktop'][$mmp_details['page_type']];

        $customHelpText = $this->formatFormLHSText($mmp_details['form_heading'],$mmp_details['subheading']);
        $data['customHelpText'] = json_encode($customHelpText);

        return $data;
	}

	public function loadMMPNationalMobile($data){
		$this->init();
		$mmpData = $this->loadMMPNationalMobileContent($data);		
		$this->load->view('multipleMarketingPage/marketingPageMobile', $mmpData);
	}

	public function loadMMPNationalMobileContent($data){
		$pageId =  $data['page_id'];
		
		//below line is used for conversion tracking purpose
		global $MMP_Tracking_keyId;
        $data['trackingKeyId'] = $MMP_Tracking_keyId['mobile'][$data['page_type']];

		$googleRemarketingParams = array();
		$data['pageId'] = $pageId;
		$data['googleRemarketingParams'] = $googleRemarketingParams;

		global $mobileMMPFormIds;
		if(in_array($pageId, $mobileMMPFormIds)){
			$data['showWelcomeScreen'] = 'yes';
			$data['checkLoggedInUser'] = 'yes';
		}
		
        $customHelpText = $this->formatFormLHSText($data['form_heading'],$data['subheading']);
        $data['customHelpText'] = json_encode($customHelpText);        
		$form_heading = 'Find the best colleges for you';
		$data['load_ga'] = TRUE;
		$data += array(
			// 'FORM_HEADING' => $form_heading,
			'PIXEL_CODE'=>base64_decode($data['pixel_codes']),
        );
		
		return $data;
	}

	private function formatFormLHSText($formHeading,$formBody){
		$data = array();
		if(!empty($formHeading)){
			$formHeading = explode("|", trim($formHeading));			
		}
		if(!empty($formBody)){
			$formBody    = explode("|", trim($formBody));			
		}


		
		if(!empty($formHeading) && count($formHeading) >= count($formBody) ){
			foreach ($formHeading as $key => $value) {
				if($value){
					$data[$key]['heading']  = trim($value);					
				}

				if(!empty($formBody[$key])){
					$data[$key]['body'] = explode(",", trim($formBody[$key]));				
				}
			}
		}else{
			if(!empty($formBody) && count($formHeading) < count($formBody)){
				foreach ($formBody as $key => $value) {
					if($formHeading[$key]){
						$data[$key]['heading']  = trim($formHeading[$key]);						
					}
					if($value){
						$data[$key]['body'] = explode(",", trim($value)); 						
					}
				}
			}
		}		

		//_p($data);die;
		return $data;
	}



}
?>
