<?php



/**
 * Mailer Class
 *
 * @author
 * @package Mailer
 *
 */
class Marketing extends MX_Controller {

    var $appId = 1;
    var $userid = '';

    function init() {
        ini_set('upload_max_filesize', '100M');
        ini_set('max_execution_time', '1800');
        $this->load->helper(array('form', 'url', 'date', 'image', 'html'));
        $this->load->library(array('LDB_Client', 'marketingClient', 'miscelleneous', 'message_board_client', 'blog_client', 'event_cal_client', 'ajax', 'category_list_client', 'listing_client', 'register_client','enterprise_client', 'sums_manage_client', 'table'));
        $this->userStatus = $this->checkUserValidation();
        $Validate = $this->userStatus;
        if ($Validate == "false") {
            $this->userid = '';
        } else {
            $this->userid = $Validate['0']['userid'];
            $this->displayname = $Validate['0']['displayname'];
        }
    }

    function registerLead($secCodeSessionVar='seccodehome') {
        $this->init();
        $validity = $this->checkUserValidation();
        error_log_shiksha("ERT " . print_r($validity, true));
        $city = $this->input->post('citiesofresidence1');
        $mobile = $this->input->post('homephone');
        error_log("UIO HOME PHONE = " . $mobile);
        $educationLevel = $this->input->post('homehighesteducationlevel');
        $age = $this->input->post('homeYOB');
        $categories = $this->input->post('board_id');
        $gender = $this->input->post('homegender');
        $subCategory = $this->input->post('homesubCategories');
        $preferredCity = $this->input->post('mCityList');
        $preferredCityName = $this->input->post('mCityListName');
        $firstName = $this->input->post('homename');
        $sourceurl = $this->input->post('refererreg');
        $cookieArr = split('\|', $validity[0]['cookiestr']);
        $email = $cookieArr[0];
        $subCategoryNameInsert = "";
        $categoriesNameInsert = "";
        error_log(print_r($_POST, true) . "LKJ");
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        error_log("LKJ NOT FOUND " . $subCategoryNameInsert);
        $allCategoryList = $categoryClient->getCategoryTree(1, 1);
        error_log("LKJ " . print_r($allCategoryList, true));
        foreach ($allCategoryList as $allCategory) {
            $categoryId = $allCategory['categoryID'];
            $categoryName = $allCategory['categoryName'];
            if ($categories == $categoryId) {
                $categoriesNameInsert = $categoryName;
            }
            if ($subCategory == $categoryId) {
                $subCategoryNameInsert = $categoryName;
            }
        }

        $this->load->library('register_client');
        $registerClient = new Register_client();
//            if($educationLevel == "School" || $educationLevel == "Other") {
//                $highestQualNameInsert = $educationLevel;
//            }else {
        $highestQualName = $registerClient->getEdLevelFromId(1, $educationLevel);
        $highestQualNameInsert = $highestQualName['level'];
//            }
        global $citiesforRegistration;
        foreach ($citiesforRegistration as $key => $value) {
            if ($value['id'] == $city) {
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
        error_log("UIO HOME PHONE = " . print_r($addReqInfo, true));
        $addUpdateInfo = array();
        $addUpdateInfo['firstname'] = $firstName;
        $addUpdateInfo['city'] = $city;
        $addUpdateInfo['mobile'] = $mobile;
        $addUpdateInfo['educationlevel'] = $educationLevel;
        $addUpdateInfo['age'] = $age;
        $addUpdateInfo['gender'] = $gender;
        $addUpdateInfo['displayname'] = $validity[0]['displayname'];
        error_log("TYU " . print_r($addUpdateInfo, true));

        $updateInterest = array();
        $updateInterest['category'] = json_encode(array($categories));
        $updateInterest['subCategory'] = json_encode(array($subCategory));
        $updateInterest['city'] = array();
        $csvArr1 = split(",", $preferredCity);
        for ($ijk = 0; $ijk < count($csvArr1); $ijk++) {
            if (trim($csvArr1[$ijk]) != "") {
                preg_match('/\d+/', $csvArr1[$ijk], $matches);
                if (count($matches) > 0) {
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
        error_log("FGH subcategories " . $subCategory);
        $userarray['usergroup'] = 'marketingPage';
        $secCode = $this->input->post('homesecurityCode');
        $userId = $validity[0]['userid'];
        if(verifyCaptcha('homesecurityCode',$secCode, 1)) {
            $preferredLoc = array();
            $csvArr = split(",", $preferredCity);
            for ($ijk = 0; $ijk < count($csvArr); $ijk++) {
                if (trim($csvArr[$ijk]) != "") {
                    preg_match('/\d+/', $csvArr[$ijk], $matches);
                    if (count($matches) > 0) {
                        array_push($preferredLoc, $csvArr[$ijk]);
                    }
                }
            }
            error_log("GHJ " . print_r($preferredLoc, true));
            $finalUrl = "";
            $categoryUrl = "";
            global $categoryMap;
            foreach ($categoryMap as $categoryName => $categoryData) {
                if ($categoryData['id'] == $categories) {

                    $pageName = strtoupper('SHIKSHA_' . $categoryName . '_HOME');
                    $categoryUrl = constant($pageName);
                    break;
                }
            }
            if (strstr($categoryUrl, "getCategoryPage") > -1) {
                $categoryUrl = $categoryUrl;
            } else {
                $categoryUrl = $categoryUrl . '/getCategoryPage/colleges/' . $categoryName;
            }

            $finalUrl .=$categoryUrl . "/India/";
            $finalPreferredLoc = "";
            $finalPreferredLocName = "";
            error_log("POI " . $userId);
            $this->load->library('category_list_client');
            $categoryClient = new Category_list_client();
            $cityListTier1 = $categoryClient->getCitiesInTier($appId, 1, 2);
            $cityListTier2 = $categoryClient->getCitiesInTier($appId, 2, 2);
            $cityListTier3 = $categoryClient->getCitiesInTier($appId, 0, 2);
            $subCategoriesAll = $categoryClient->getSubCategories($appId, $categories);
            //error_log("HJK ".print_r($subCategoriesAll,true));
            foreach ($subCategoriesAll as $key => $value) {
                if ($value['boardId'] == $subCategory) {
                    $subCategoryName = $value['urlName'];
                }
            }


            //                    India/278/Creative-Arts-Commercial-Arts-Performing-Arts/Bangalore
            if (count($preferredLoc) == 1) {
                $finalPreferredLoc = trim($preferredLoc[0]);
            } else {
                for ($klj = 0; $klj < count($preferredLoc); $klj++) {
                    if ($preferredLoc[$klj] == $city) {
                        $finalPreferredLoc = trim($preferredLoc[$klj]);
                    }
                }
                if ($finalPreferredLoc == "") {
                    for ($klj = 0; $klj < count($preferredLoc); $klj++) {
                        foreach ($cityListTier1 as $cityTemp) {
                            error_log("GHJ In teir One Check " . $preferredLoc[$klj] . " " . $cityTemp['cityId']);

                            if (trim($preferredLoc[$klj]) == trim($cityTemp['cityId'])) {
                                $finalPreferredLocName = $cityTemp['cityName'];
                                $finalPreferredLoc = trim($preferredLoc[$klj]);
                                break;
                            }
                        }
                        if ($finalPreferredLoc != "") {
                            break;
                        }
                    }
                    if ($finalPreferredLoc == "") {
                        error_log("GHJ teir One Check Failed");
                        $finalPreferredLoc = trim($preferredLoc[0]);
                    }
                }
            }
            error_log("GHJ Loc10" . $finalPreferredLoc);
            if ($finalPreferredLocName == "") {
                foreach ($cityListTier1 as $cityTemp) {
                    if ($finalPreferredLoc == $cityTemp['cityId']) {
                        $finalPreferredLocName = $cityTemp['cityName'];
                        break;
                    }
                }
            }
            if ($finalPreferredLocName == "") {
                foreach ($cityListTier2 as $cityTemp) {
                    error_log("GHJ " . $cityTemp['cityId'] . " " . $finalPreferredLoc);
                    if ($finalPreferredLoc == $cityTemp['cityId']) {
                        $finalPreferredLocName = $cityTemp['cityName'];
                        break;
                    }
                }
                error_log("GHJ In tier 2 city if");
            }

            if ($finalPreferredLocName == "") {
                foreach ($cityListTier3 as $cityTemp) {
                    error_log("GHJ " . $cityTemp['cityId'] . " " . $finalPreferredLoc);
                    if ($finalPreferredLoc == $cityTemp['cityId']) {
                        $finalPreferredLocName = $cityTemp['cityName'];
                        break;
                    }
                }
                error_log("GHJ In tier 2 city if");
            }



            error_log("GHJ Loc1 " . $finalPreferredLoc);
            error_log("LKJ Name1 " . $finalPreferredLocName);
            $finalPreferredLocName = str_replace("/", "-", $finalPreferredLocName);
            $finalUrl .=$finalPreferredLoc . "/" . $subCategoryName . "/" . $finalPreferredLocName;
            $marketingClientObj = new MarketingClient();
//                error_log("ERT ".print_r($preferredLoc,true));
            for ($klj = 0; $klj < count($preferredLoc); $klj++) {
                $key = trim($subCategory) . "#" . trim($preferredLoc[$klj]);
                error_log("GOING FOR Regis LKJ");
                error_log("POI " . print_r($addUpdateInfo, true));
                $addReqInfo['cityToAdd'] = $preferredLoc[$klj];
                $addReqInfo['keyValInitial'] = trim($subCategory) . "#";
                error_log("UIO " . json_encode($updateInterest));
                $addUser = $marketingClientObj->registerUserForLead(1, $userId, $key, $addReqInfo, $addUpdateInfo, $addUpdateInfo['displayname'], json_encode($updateInterest));
            }
//                $addReqInfo = array();
//                $addReqInfo['listing_type'] = "institute";
//                $addReqInfo['listing_type_id'] = $typeIdArr[$jkl];
//                $addReqInfo['displayName'] = $userarray[0]['displayname'];
//                $addReqInfo['contact_cell'] = $userarray[0]['mobile'];
//                $addReqInfo['userId'] = $userStatus[0]['userid'];
//                $addReqInfo['contact_email'] = $email;
//                $addReqInfo['action'] = "marketingPage";
//                $addReqInfo['userInfo'] = json_encode($userStatus);
//                $addReqInfo['sendMail'] = false;
//
//                $this->load->library(array('lmsLib'));
//                $LmsClientObj = new LmsLib();
//                $addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);
//
            echo $finalUrl . "###1";
        }
        else
            echo "code";
    }

    function abCodeManagement($page, $type, $campaign) {
	$this->load->library('cacheLib'); 
	$cacheLibObj = new cacheLib(); 

        $var = $cacheLibObj->get("bManagementABCode");

        if ($var == "1") {
            $cacheLibObj->store("bManagementABCode", "0");
        } else {
            $cacheLibObj->store("bManagementABCode", "1");
        }
        if ($var == "1") {
            header('location:/marketing/Marketing/index/' . $page . '/' . $type . '/' . $campaign);
        } else {
            header('location:/marketing/Marketing/index/' . $page . '_2/' . $type . '_2/' . $campaign);
        }
    }

    function abCode($page, $type, $campaign) {
	$this->load->library('cacheLib'); 
	$cacheLibObj = new cacheLib(); 

        $var = $cacheLibObj->get("bABCode");
        if ($var == "1") {
            $cacheLibObj->store("bABCode", "0");
        } else {
            $cacheLibObj->store("bABCode", "1");
        }
        error_log("UIO " . $var);
        if ($var == "1") {
            error_log("MPPP");
            header('location:/marketing/Marketing/index/' . $page . '/' . $type . '/' . $campaign);
        } else {
            $this->load->library('category_list_client');
            $categoryClient = new Category_list_client();
            error_log("LKJ NOT FOUND " . $subCategoryNameInsert);
            $allCategoryList = $categoryClient->getCategoryTree(1, 1);
            error_log("LKJ " . print_r($allCategoryList, true));
            foreach ($allCategoryList as $allCategory) {
                $categoryId = $allCategory['categoryID'];
                $categoryName = $allCategory['categoryName'];
                if ($categories == $categoryId) {
                    $categoriesNameInsert = $categoryName;
                }
                if ($subCategory == $categoryId) {
                    $subCategoryNameInsert = $categoryName;
                }
            }


            $categoryUrl = "";
            $pageName = strtoupper('SHIKSHA_' . $type . '_HOME');
            $pageName = constant($pageName);
            if (strstr($pageName, "getCategoryPage") > -1) {
                $categoryUrl = $pageName . '/All/All/All/' . $campaign;
            } else {
                $categoryUrl = $pageName . '/getCategoryPage/colleges/' . $type . '/All/All/All/' . $campaign;
            }
            header('location:' . $categoryUrl);
        }
    }

    function makeUserData($userCompleteDetails) {
        $userCompleteDetails = $this->make_array(json_decode($userCompleteDetails, true));
        $userprefarray = $userCompleteDetails[0]['PrefData'][0];
        $usereduarray = $userCompleteDetails[0]['EducationData'];
        $userlocpref = $userCompleteDetails[0]['PrefData'][0]['LocationPref'];
        $statearray = array();
        $cityarray = array();

        $userarray = array('name' => isset($userCompleteDetails[0]['firstname']) ? $userCompleteDetails[0]['firstname'] : '',
            'email' => isset($userCompleteDetails[0]['email']) ? $userCompleteDetails[0]['email'] : '',
            'emailenable' => (isset($userCompleteDetails[0]['email']) && trim($userCompleteDetails[0]['email']) != '') ? 'disabled' : '',
            'mobile' => (isset($userCompleteDetails[0]['mobile']) && trim($userCompleteDetails[0]['mobile']) != '') ? $userCompleteDetails[0]['mobile'] : '',
            'cityid' => (isset($userCompleteDetails[0]['city']) && trim($userCompleteDetails[0]['city']) > 0) ? $userCompleteDetails[0]['city'] : '',
            'experience' => (isset($userCompleteDetails[0]['experience'])) ? $userCompleteDetails[0]['experience'] : '',
            'AICTE' => (isset($userprefarray['DegreePrefAICTE']) && $userprefarray['DegreePrefAICTE'] == 'yes') ? 'checked' : '',
            'UGC' => (isset($userprefarray['DegreePrefUGC']) && trim($userprefarray['DegreePrefUGC']) == 'yes') ? 'checked' : '',
            'International' => (isset($userprefarray['DegreePrefInternational']) && $userprefarray['DegreePrefInternational'] == 'yes') ? 'checked' : '',
            'Anydegree' => (isset($userprefarray['DegreePrefAny']) && $userprefarray['DegreePrefAny'] == 'yes') ? 'checked' : '',
            'fulltime' => (isset($userprefarray['ModeOfEducationFullTime']) && $userprefarray['ModeOfEducationFullTime'] == 'yes') ? 'checked' : '',
            'parttime' => (isset($userprefarray['ModeOfEducationPartTime']) && $userprefarray['ModeOfEducationPartTime'] == 'yes') ? 'checked' : '',
            'distance' => (isset($userprefarray['ModeOfEducationDistance']) && $userprefarray['ModeOfEducationDistance'] == 'yes') ? 'checked' : '',
        );

        for ($i = 0; $i < count($userlocpref); $i++) {
            if ($userlocpref[$i]['CityId'] == -1 || $userlocpref[$i]['CityId'] == 0)
                $statearray[] = $userlocpref[$i]['StateId'];
            else {
                if ($userlocpref[$i]['CityId'] != NULL)
                    $cityarray[] = $userlocpref[$i]['CityId'];
            }
        }

        $userarray['statearray'] = $statearray;
        $userarray['cityarray'] = $cityarray;
        for ($j = 0; $j < count($usereduarray); $j++) {
            if ($usereduarray[$j]['Level'] == 'Competitive exam') {
                $arrcolname = $usereduarray[$j]['Name'];
                $userarray[$arrcolname] = $usereduarray[$j]['Marks'];
            } else {
                $completiondate = getdate($usereduarray[$j]['CourseCompletionDate']);
                $arrcolname = $usereduarray[$j]['Level'];
                $userarray[$arrcolname . 'details'] = $usereduarray[$j]['Name'];
                $userarray[$arrcolname . 'ongoing'] = '';
                $userarray[$arrcolname . 'completed'] = '';
                if ($usereduarray[$j]['OngoingCompletedFlag'] == '0') {
                    $userarray[$arrcolname . 'completed'] = 'checked';
                } else {
                    $userarray[$arrcolname . 'ongoing'] = 'checked';
                }
                $userarray[$arrcolname . 'city'] = $usereduarray[$j]['City'];
                $userarray[$arrcolname . 'institute'] = $usereduarray[$j]['InstituteId'];
                $userarray[$arrcolname . 'marks'] = $usereduarray[$j]['Marks'];
                $userarray[$arrcolname . 'completionmonth'] = $completiondate['mon'];
                $userarray[$arrcolname . 'completionyear'] = $completiondate['year'];
            }
        }
        return json_encode($userarray);
    }

    function _other_exm_list($selected = NULL, $flag = FALSE) {
        $array = array('Management-Label', 'CAT', 'MAT', 'XAT', 'UGAT', 'Engineering-Label', 'IITJEE', 'GATE', 'International Exams-Label', 'TOEFL', 'IELTS', 'GRE', 'GMAT');
        $string = '';
        for ($i = 0; $i < count($array); $i++) {
            if (strpos($array[$i], 'Label')) {
                $new_array = explode('-', $array[$i]);
                $string .='<OPTGROUP LABEL="' . $new_array[0] . '">';
            } else {
                if ($selected != NULL) {
                    if ($selected == $array[$i]) {
                        $selected_string = "selected";
                    } else {
                        $selected_string = "";
                    }
                }
                $string .='<OPTION ' . $selected_string . ' value="' . $array[$i] . '">' . $array[$i] . '</OPTION>';
            }
        }
        if ($flag) {
            return $array;
        } else {
            return $string;
        }
    }

    function _when_you_plan_start($selected = NULL, $flag = FALSE) {
        $array = array(
            date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y") + 1)) => 'This Year’s Academic Season',
            date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y") + 2)) => 'Next Year’s Academic Season',
            //date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+2)) => 'Within next two years',
            '0000-00-00 00:00:00' => 'Not Sure',
        );
        $string = '';
        foreach ($array as $key => $value) {
            if ($selected != NULL) {
                if ($selected == $key) {
                    $selected_string = "selected";
                } else {
                    $selected_string = "";
                }
            }
            $string .='<option ' . $selected_string . ' value="' . $key . '">' . $value . '</option>';
        }
        if ($flag) {
            return $array;
        } else {
            return $string;
        }
    }

    function _course_lists($selected = NULL, $flag = FALSE) {
        $array = array('B.A.', 'B.A.(Hons)', 'B.Sc', 'B.Sc(Gen)', 'B.Sc(Hons)', 'B.E./B.Tech', 'B.Des', 'B.Com', 'BBA/BBM/BBS', 'B.Ed', 'BCA/BCM', 'BVSc', 'BHM', 'BJMC', 'BDS', 'B.Pharma', 'B.Arch', 'MBBS', 'LLB', 'Diploma');
        $string = '';
        for ($i = 0; $i < count($array); $i++) {
            if ($selected != NULL) {
                if ($selected == $array[$i]) {
                    $selected_string = "selected";
                } else {
                    $selected_string = "";
                }
            }
            $string .='<option ' . $selected_string . ' value="' . $array[$i] . '">' . $array[$i] . '</option>';
        }
        if ($flag) {
            return $array;
        } else {
            return $string;
        }
    }

    function _work_exp_combo($selected = NULL, $flag = FALSE) {
        $string = '';
        $string .='<option ' . $selected_string . ' value="-1">No Experience</option>';
        for ($i = 0; $i <= 10; $i++) {
            if ($selected != NULL) {
                if ($selected == $i) {
                    $selected_string = "selected";
                } else {
                    $selected_string = "";
                }
            }

            if ($i == 0) {
                $string .='<option ' . $selected_string . ' value="' . $i . '">< 1 year</option>';
            } elseif ($i == 10) {
                $string .='<option ' . $selected_string . ' value="' . $i . '">> 10 years</option>';
            } else {
                $string .='<option ' . $selected_string . ' value="' . $i . '">' . $i . ' - ' . ($i + 1) . ' years</option>';
            }
        }
        if ($flag) {
            return $array;
        } else {
            return $string;
        }
    }

    private function _load_data_marketing_page($page, $type) {
        $this->init();
        $data = array();
        switch ($page) {
            case ($page == 'clinical_research' || $page == 'fashion_design' || $page == 'mass_communications' || $page == 'bba' || $page == 'it' || $page == 'animation' || $page == 'hospitality' || $page == 'science' || $page == 'testprep'): // $type shud be "it"
                // load data for courses list
                $this->load->library('category_list_client');
                $categoryClient = new Category_list_client();
                if ($page == 'bba') {
                    $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 149), true);
                }
                if ($page == 'it') {
                    $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 10), true);
                }
                if ($page == 'testprep') {
                    $data['itcourseslist'] = $categoryClient->getTestPrepCoursesList(1);
                }
                if ($page == 'animation') {
                    $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 12), true);
                }
                if ($page == 'hospitality') {
                    $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 6), true);
                }
                if ($page == 'science') {
                    $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 2), true);
                }
                if ($page == 'clinical_research') {
                    $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 5), true);
                }
                if ($page == 'fashion_design') {
                    $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 8), true);
                }
                if ($page == 'mass_communications') {
                    $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 7), true);
                }
                $data['prefix'] = "";
                $data['formPostUrl'] = '/user/Userregistration/ITMarketingPage/seccodehome';
                $data['select_city_list'] = $this->_select_city_list();
                $data = array_merge((array) $data, (array) $this->_marketing_page($this->userid));
                break;
            case 'management': // $type shud be "management"
                $data['prefix'] = "";
                $data['formPostUrl'] = '/user/Userregistration/TestuserMarketingPAge/seccodehome';
                $data = array_merge((array) $data, (array) $this->_marketing_page($this->userid));
                break;
            case 'distancelearning': // $type shud be "management"
                $data['course'] = 'distancelearning';
                $data['local_course_flag'] = 'on';
                $data['prefix'] = "";
                $data['select_city_list'] = $this->_select_city_list();
                $data['formPostUrl'] = '/user/Userregistration/TestuserMarketingPAge/seccodehome';
                $data = array_merge((array) $data, (array) $this->_marketing_page($this->userid));
                break;
            case 'campaign1': // $type shud be "management"
                $data['prefix'] = "";
                $data['formPostUrl'] = '/user/Userregistration/TestuserMarketingPAge/seccodehome';
                $data = array_merge((array) $data, (array) $this->_marketing_page($this->userid));
                break;
            case 'campaign2': // $type shud be "management"
                $data['prefix'] = "";
                $data['formPostUrl'] = '/user/Userregistration/TestuserMarketingPAge/seccodehome';
                $data = array_merge((array) $data, (array) $this->_marketing_page($this->userid));
                break;
            case 'campaign3': // $type shud be "management"
                $data['prefix'] = "";
                $data['formPostUrl'] = '/user/Userregistration/TestuserMarketingPAge/seccodehome';
                $data = array_merge((array) $data, (array) $this->_marketing_page($this->userid));
                break;
            case 'campaign4': // $type shud be "management"
                $data['prefix'] = "";
                $data['formPostUrl'] = '/user/Userregistration/TestuserMarketingPAge/seccodehome';
                $data = array_merge((array) $data, (array) $this->_marketing_page($this->userid));
                break;
            case 'campaign5': // $type shud be "management"
                $data['prefix'] = "";
                $data['formPostUrl'] = '/user/Userregistration/TestuserMarketingPAge/seccodehome';
                $data = array_merge((array) $data, (array) $this->_marketing_page($this->userid));
                break;
            case 'campaign6': // $type shud be "management"
                $data['prefix'] = "";
                $data['formPostUrl'] = '/user/Userregistration/TestuserMarketingPAge/seccodehome';
                $data = array_merge((array) $data, (array) $this->_marketing_page($this->userid));
                break;
            case 'campaign7': // $type shud be "management"
                $data['prefix'] = "";
                $data['formPostUrl'] = '/user/Userregistration/TestuserMarketingPAge/seccodehome';
                $data = array_merge((array) $data, (array) $this->_marketing_page($this->userid));
                break;
            case 'campaign8': // $type shud be "management"
                $data['prefix'] = "";
                $data['formPostUrl'] = '/user/Userregistration/TestuserMarketingPAge/seccodehome';
                $data = array_merge((array) $data, (array) $this->_marketing_page($this->userid));
                break;
            case 'campaign9': // $type shud be "management"
                $data['prefix'] = "";
                $data['formPostUrl'] = '/user/Userregistration/TestuserMarketingPAge/seccodehome';
                $data = array_merge((array) $data, (array) $this->_marketing_page($this->userid));
                break;
            default:
                $data['course'] = '';
                $this->load->library('category_list_client');
                $categoryClient = new Category_list_client();
                $subCategoryList = $categoryClient->getSubCategories(1, 1);
                $data['subCategories'] = $subCategoryList;
                $allCategoryList = $categoryClient->getCategoryTree(1, 1);
                $data['allCategories'] = $allCategoryList;
        }
        return $data;
    }

    private function _marketing_page($userid) {
        $data = array();
        $this->init();
        $appId = 1;
        $ldbObj = new LDB_Client();
        $listing_client = new listing_client();
        $data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12), true);
        $cityListTier = $data['country_state_city_list'];
        $course_list = json_decode($ldbObj->sgetCourseList($appId, 3), true);
        $distance_course = json_decode($ldbObj->sgetSpecializationListByParentId($appId, 24), true);
        $data['distance_course'] = $distance_course;
        $userCompleteDetails = '';
        if ($userid != '')
            $userCompleteDetails = $ldbObj->sgetUserDetails($appId, $userid);
        $userDataToShow = $this->makeUserData($userCompleteDetails);
        $countryCityStateMaps = $this->getCityStatesFromMap($cityListTier);
        $data['countriesList'] = $countryCityStateMaps['countries'];
        $data['statesList'] = $countryCityStateMaps['states'];
        $data['citiesList'] = $countryCityStateMaps['cities'];
        $data['userDataToShow'] = $userDataToShow;
        $data['newDesiredCourse_list'] = $course_list;
        $data['userCompleteDetails'] = $userCompleteDetails;
        $data['CitiesWithCollege'] = $listing_client->getCitiesWithCollege(1, 2);
        // flag for JSB9 Tracking
        $data['trackForPages'] = true;
        $data['other_exm_list'] = $this->_other_exm_list();
        $data['work_exp_combo'] = $this->_work_exp_combo();
        if ($userid != '') {
            $userCompleteDetails = $this->make_array(json_decode($userCompleteDetails, true));
            $TimeOfStart =
                    $userCompleteDetails[0]['PrefData'][0]['TimeOfStart'];
            $data['when_you_plan_start'] = $this->_when_you_plan_start($TimeOfStart, FALSE);
            $array_ug_courses = $userCompleteDetails[0]['EducationData'];
            $PrefData = $userCompleteDetails[0]['PrefData'];
            foreach ($PrefData as $value) {
                if (count($value['LocationPref']) > 0) {
                    $data['LocalityCityValue'] = $value['LocationPref'][0]['CountryId'] . ":" . $value['LocationPref'][0]['StateId'] . ":" . $value['LocationPref'][0]['CityId'] . ":" . $value['LocationPref'][0]['LocalityId'];
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
            $data['course_lists'] = $this->_course_lists($name_ug_course, FALSE);
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

    function index($page, $type, $url='') {
        error_log_shiksha("START FRONT_END FF_MBA");
        $this->init();
        $validity = $this->checkUserValidation();
        global $logged;
        global $userid;
        global $usergroup;
        
        if(is_array($validity) && is_array($validity[0]) && $validity[0]['userid'] && preg_match("/campaign[1-9]$/",$page)) {
            $this->load->library('MP_MarketingPage');
            $uo = MP_MarketingPage::INIT("ManagementMarketingPage");
            $finalUrl = $uo->getRedirectionUrl($page);
            header("Location: ".$finalUrl);
			exit();
        }
        
        $urlToCheck = base64_decode($url);
        if (substr($urlToCheck, 0, 7) != 'http://' && substr($urlToCheck, 0, 8) != 'https://') {
            $url = '';
        }
        $data = array();
        $thisUrl = $_SERVER['REQUEST_URI'];
        if (($validity == "false" ) || ($validity == "")) {
            $logged = "No";
        } else {
            $logged = "Yes";
        }
        $userName = '';
        $userEmail = '';
        $userInterest = -1;
        $userPassword = '';
        $data['org_type'] = $type; 
		
        $user_name = $this->input->post('user_name', true);
		if(isset($user_name) && !empty($user_name)) {
			$userName = isset($user_name) ? $user_name:'';
			$user_email = $this->input->post('user_email', true);
			$userEmail = isset($user_email) ? $user_email:'';
			$user_interest = $this->input->post('user_interest', true);
			$userInterest = isset($user_interest) ? $user_interest:'';
            $userPassword = isset($_POST['user_password']) ? $_POST['user_password'] : '';
			$user_contactno = $this->input->post('user_contactno', true);
			$userContactno = isset($user_contactno) ? $user_contactno:'';
			error_log(" log visitor detail"."---".$userName."---".$userEmail."---".$userInterest."---".$userPassword."---".$userContactno);
		}
        
        $data['prefix'] = "";
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $cityListTier1 = $categoryClient->getCitiesInTier($appId, 1, 2);
        $cityListTier2 = $categoryClient->getCitiesInTier($appId, 2, 2);
        $cityListTier3 = $categoryClient->getCitiesInTier($appId, 0, 2);
        $data['cityTier2'] = $cityListTier2;
        $data['cityTier3'] = $cityListTier3;
        $data['cityTier1'] = $cityListTier1;
        /* funny logic start */
        $backPageUrl = $url;
        $pagename = $page;
        if ($type == '' || ($type != $page && $page != "distancelearning" && $page != 'bca' && $page != 'mca')) {
            $type = $page;
        }
        if ($type != $page) {
            $pagename = $page . $type;
        }
        $data['type'] = $type;
        $strPos = strpos($type, "_");
        if (strpos($type, "_") != "") {
            $extraPram = preg_replace("/[^_]*(_\d+)/i", "$1", $type);
        } else {
            $extraPram = '';
        }
        /* Fixed 54748 Ravi Raj */
	if ($type == 'bba')
	{
	  redirect('/marketing/Marketing/index/management/management/' . $url, 'location', 301);
	}
	/* Hack: To Redirect old media Page */
	if ($type == 'media')
	{
	  /* Apply 301 redirect for fast and SEO purpose */
	  redirect('/marketing/Marketing/index/mass_communications', 'location', 301);
	}
	if ($type == 'testprep')
	{
		redirect('/marketing/Marketing/index/pageID/225', 'location', 301);
	}
        $data['pagename'] = $pagename;
        $data['page'] = $page;
        /* funny logic end */
        $data['userName'] = $userName;
        $data['userEmail'] = $userEmail;
        $data['userInterest'] = $userInterest;
        $data['userPassword'] = $userPassword;
        $data['userContactno'] = $userContactno;
        $data['logged'] = $logged;
        $data['userData'] = $this->userStatus;
        $data['extraPram'] = $extraPram;
        $data['backPage'] = $backPageUrl;
        $userid = $this->userid;
        /* start new lib api */
        $this->load->library('MP_MarketingPage');
        $uo = MP_MarketingPage::INIT("ManagementMarketingPage");
        $data['config_data_array'] = $uo->getAllPageinfo($page);
        /* end new lib api */
        $data = array_merge((array) $data, (array) $this->_load_data_marketing_page($page, $type, $url));
        $this->_switchLoadView($type, $data, $extraPram);
        error_log_shiksha("END FRONT_END FF_MBA");
    }

    private function _switchLoadView($type, $data, $extraPram=array()) {
        switch ($type) {
            case ($type == 'clinical_research' || $type == 'fashion_design' || $type == 'mass_communications' || $type == 'bba' || $type == 'it' || $type == 'hospitality' || $type == 'science' || $type == 'testprep'):
                $this->load->view('marketing/itmarketingpage', $data);
                break;
            case 'animation1':
                $this->load->view('marketing/animationmarketingpage', $data);
                break;
            case 'animation':
                $x = $this->load->view('marketing/animationmarketingpage1', $data,true);
                echo preg_replace('/[\ ]+/',' ',$x);
                break;
            case 'management':
                $this->load->view('marketing/newmarketingpage', $data);
                break;
            case 'distancelearning':
                $this->load->view('marketing/newmarketingpage', $data);
                break;
            case 'campaign1':
                $this->load->view('marketing/customizedmanagement', $data);
                break;
            case 'campaign2':
                $this->load->view('marketing/customizedmanagement', $data);
                break;
            case 'campaign3':
                $this->load->view('marketing/customizedmanagementNMAT', $data);
                break;
            case 'campaign4':
                $this->load->view('marketing/customizedmanagementMAT', $data);
                break;
            case 'campaign5':
                $this->load->view('marketing/customizedmanagementCAT', $data);
                break;
            case 'campaign6':
                $this->load->view('marketing/customizedmanagementMICAT', $data);
                break;
            case 'campaign7':
                $this->load->view('marketing/customizedmanagement', $data);
                break;
            case 'campaign8':
                $this->load->view('marketing/customizedmanagementSNAP', $data);
                break;
            case 'campaign9':
                $this->load->view('marketing/customizedmanagementXAT', $data);
                break;
            default:
                $this->load->view('marketing/mPage' . $extraPram, $data);
        }
        return;
    }

    function load_static_html($id) {
        if ($id == '1') {
            echo $this->load->view('marketing/management_2');
        }
    }

    function ajaxform_mba($flag) {
        $this->init();
        global $logged;
        $data = array();
        $validity = $this->checkUserValidation();
        if (($validity == "false" ) || ($validity == "")) {
            $logged = "No";
        } else {
            $logged = "Yes";
        }
        $data['logged'] = $logged;
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $cityListTier1 = $categoryClient->getCitiesInTier($appId, 1, 2);
        $cityListTier2 = $categoryClient->getCitiesInTier($appId, 2, 2);
        $cityListTier3 = $categoryClient->getCitiesInTier($appId, 0, 2);
        $data['cityTier1'] = $cityListTier1;
        $data['cityTier2'] = $cityListTier2;
        $data['cityTier3'] = $cityListTier3;
        $this->load->library('LDB_Client');
        $ldbObj = new LDB_Client();
        $data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12), true);
        $userid = $this->userid;
        $appId = 1;
        $data['select_city_list'] = $this->_select_city_list();
        $this->load->library('LDB_Client');
        $categoryClient = new LDB_Client();
        $listing_client = new listing_client();
        $cityListTier = $data['country_state_city_list'];
        $course_list = json_decode($categoryClient->sgetCourseList($appId, 3), true);
        $userCompleteDetails = '';
        if ($userid != '')
            $userCompleteDetails = $categoryClient->sgetUserDetails($appId, $userid);
        $userDataToShow = $this->makeUserData($userCompleteDetails);
        $countryCityStateMaps = $this->getCityStatesFromMap($cityListTier);
        $data['countriesList'] = $countryCityStateMaps['countries'];
        $data['statesList'] = $countryCityStateMaps['states'];
        $data['citiesList'] = $countryCityStateMaps['cities'];
        $data['userDataToShow'] = $userDataToShow;
        $data['newDesiredCourse_list'] = $course_list;
        $data['userCompleteDetails'] = $userCompleteDetails;
        $data['CitiesWithCollege'] = $listing_client->getCitiesWithCollege(1, 2);
        $data['other_exm_list'] = $this->_other_exm_list();
        $data['work_exp_combo'] = $this->_work_exp_combo();
        if ($userid != '') {
            $userCompleteDetails = $this->make_array(json_decode($userCompleteDetails, true));
            $TimeOfStart =
                    $userCompleteDetails[0]['PrefData'][0]['TimeOfStart'];
            $data['when_you_plan_start'] = $this->_when_you_plan_start($TimeOfStart, FALSE);
            $array_ug_courses = $userCompleteDetails[0]['EducationData'];
            $PrefData = $userCompleteDetails[0]['PrefData'];
            foreach ($PrefData as $value) {
                if (count($value['LocationPref']) > 0) {
                    $data['LocalityCityValue'] = $value['LocationPref'][0]['CountryId'] . ":" . $value['LocationPref'][0]['StateId'] . ":" . $value['LocationPref'][0]['CityId'] . ":" . $value['LocationPref'][0]['LocalityId'];
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
            $data['course_lists'] = $this->_course_lists($name_ug_course, FALSE);
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
        $data['formPostUrl'] = '/user/Userregistration/TestuserMarketingPAge/seccodehome';
        if ($flag == 'true') {
            $data['course'] = '';
            echo $this->load->view('marketing/mba_marketing_mode', $data);
        } elseif ($flag == 'false') {
            $data['course'] = 'distancelearning';
            echo $this->load->view('marketing/mba_marketing_local_course_mode', $data);
        } elseif ($flag == 'mr_page') {
            echo $this->load->view('marketing/marketingLocationLayer', $data);
        } elseif ($flag == 'mr_user_sign') {
            echo $this->load->view('marketing/marketingSignInOverlay', $data);
        } elseif ($flag == 'itcourse') {
            echo $this->load->view('marketing/user_form_mba_it_itcourses', $data);
        } elseif ($flag == 'itdegree') {
            echo $this->load->view('marketing/user_form_mba_it_itdegree', $data);
        } elseif ($flag == 'graduate_course') {
            echo $this->load->view('marketing/user_form_mba_it_ug_courses', $data);
        } elseif ($flag == 'itcourse1') {
            echo $this->load->view('marketing/user_form_mba_it_itcourses_1', $data);
        } elseif ($flag == 'itdegree1') {
            echo $this->load->view('marketing/user_form_mba_it_itdegree_1', $data);
        } elseif ($flag == 'graduate_course1') {
            echo $this->load->view('marketing/user_form_mba_it_ug_courses_1', $data);
        }
    }

    function displaynextoverlay($str, $desired_course_name, $keyname = 'MARKETING_FORM', $prefId="") {
        $this->init();
        $data = array();
        $validity = $this->checkUserValidation();
        if ($str == 'course') {
            $data['local_course_flag'] = 'it_courses';
            $url = 'marketing/itmarketingpage_overlay.php';
        } elseif ($str == 'degree') {
            $data['local_course_flag'] = 'it_degree';
            $url = 'marketing/itmarketingpage_overlay.php';
        } elseif ($str == 'graduate_course') {
            $data['local_course_flag'] = 'graduate_course';
            $url = 'marketing/itmarketingpage_overlay.php';
        } else {
            $url = 'common/Request-E-Brochure6.php';
        }
        $appId = 1;
        $this->load->library('LDB_Client');
        $categoryClient = new LDB_Client();
        $userid = $this->userid;
        if ($userid != '')
            $userCompleteDetails = $categoryClient->sgetUserDetails($appId, $userid);
        $data['SpecializationList'] = json_decode($categoryClient->sgetSpecializationListByParentId($appId, $desired_course_name), true);
        $data['other_exm_list'] = $this->_other_exm_list();
        $data['course_lists'] = $this->_course_lists();
        $data['prefId'] = $prefId;
        if ($str == '6') {
            // HARDCODE CHECK
            if (($desired_course_name != '2') && ($desired_course_name != '13')) {
                $data['local_course_flag'] = 'on';
            }
        }
        $data['userCompleteDetails'] = $this->make_array(json_decode($userCompleteDetails, true));
        $data['validateuser'] = $validity;
        $string = $this->load->view($url, $data, true);
        echo $string;
    }

    function ajax_preference_locality($id) {
		
		if($id <=0) {
				return array();
		}
		
        $oldIds = explode(",",$id);
        foreach($oldIds as $oldId) {
            if(!is_numeric($oldId)) {
                return;
            }
        }

        $this->init();
        $response = array();
        $this->load->library('category_list_client');
        $this->load->library('MY_sort_associative_array');
        $sorter = new MY_sort_associative_array;
        $categoryClient = new Category_list_client();
        $appId = 1;
        $response['part1'] = json_decode($categoryClient->getCityGroupInSameVirtualCity($appId, $id), true);
        $response['part2'] = json_decode($categoryClient->getZonewiseLocalitiesForCityId($appId, $id), true);
        echo json_encode($response);
    }

    function make_array($an_array) {
        $return_array = array();
        foreach ($an_array as $key => $val)
            break;
        $return_array[] = $val;
        return $return_array;
    }

    function _select_city_list() {
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $cityListTier1 = $categoryClient->getCitiesInTier($appId, 1, 2);
        $this->load->library('MY_sort_associative_array');
        $sorter = new MY_sort_associative_array;
        $finalArray = array();
        foreach ($cityListTier1 as $list) {
            if ($list['stateId'] == '-1') {
                $finalArray['virtualCity'][] = json_decode($categoryClient->getCitiesForVirtualCity(1, $list['cityId']), true);
            } else {
                $finalArray['metroCity'][] = $list;
            }
        }
        $string = '';
        $finalArray['virtualCity'] = $sorter->sort_associative_array($finalArray['virtualCity'], 'city_name');
        foreach ($finalArray['virtualCity'] as $list) {
            foreach ($list as $key) {
                if ($key['virtualCityId'] == $key['city_id']) {
                    $string .='<OPTGROUP LABEL="' . $key['city_name'] . '">';
                    foreach ($finalArray['virtualCity'] as $list1) {
                        $list1 = $sorter->sort_associative_array($list1, 'city_name');
                        foreach ($list1 as $key1) {
                            if ($key1['virtualCityId'] != $key1['city_id']) {
                                if ($key['city_id'] == $key1['virtualCityId']) {
                                    $string .='<OPTION title="' . $key1['city_name'] . '" value="' . $key1['countryId'] . ':' . $key1['state_id'] . ':' . $key1['city_id'] . '">' . $key1['city_name'] . '</OPTION>';
                                }
                            }
                        }
                    }
                }
            }
        }
        $string .='<OPTGROUP LABEL="Metro Cities">';
        $finalArray['metroCity'] = $sorter->sort_associative_array($finalArray['metroCity'], 'cityName');
        foreach ($finalArray['metroCity'] as $key1) {
            $string .='<OPTION title="' . $key1['cityName'] . '" value="' . $key1['countryId'] . ':' . $key1['stateId'] . ':' . $key1['cityId'] . '">' . $key1['cityName'] . '</OPTION>';
        }
        $ldbObj = new LDB_Client();
        $listing_client = new listing_client();
        $country_state_city_list = json_decode($ldbObj->sgetCityStateList(12), true);
        foreach ($country_state_city_list as $list) {
            if ($list['CountryId'] == 2) {
                foreach ($list['stateMap'] as $list2) {
                    $string .='<OPTGROUP LABEL="' . $list2['StateName'] . '">';
                    foreach ($list2['cityMap'] as $list3) {

                        $string .='<OPTION title="' . $list3['CityName'] . '" value="' . $list['CountryId'] . ':' . $list2['StateId'] . ':' . $list3['CityId'] . '">' . $list3['CityName'] . '</OPTION>';
                    }
                }
            }
        }
        return $string;
    }

    function _ApiTest($cityid, $zoneid) {
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $getZonesForCityId = $categoryClient->getZonesForCityId(1, $cityid);
        $getLocalitiesForZoneId = $categoryClient->getLocalitiesForZoneId(1, $zoneid);
        $getCityGroupInSameVirtualCity = $categoryClient->getCityGroupInSameVirtualCity(1, $cityid);
        echo "<h3>getZonesForCityId</h3>";
        echo "<pre>";
        print_r(json_decode($getZonesForCityId, true));
        echo "</pre>";
        echo "<hr>";
        echo "<h3>getLocalitiesForZoneId</h3>";
        echo "<pre>";
        print_r(json_decode($getLocalitiesForZoneId, true));
        echo "</pre>";
        echo "<hr>";
        echo "<h3>getCityGroupInSameVirtualCity</h3>";
        echo "<pre>";
        print_r(json_decode($getCityGroupInSameVirtualCity, true));
        echo "</pre>";
        echo "<h3>getCitiesForVirtualCity</h3>";
        echo "<pre>";
        print_r(json_decode($categoryClient->getCitiesForVirtualCity(1, $cityid), true));
        echo "</pre>";
    }

    function _seeuserdetaildump($id1, $id2) {
        $this->load->library('LDB_Client');
        $categoryClient = new LDB_Client();
        $userCompleteDetails = $categoryClient->sgetUserDetails(1, $id1);
        echo "<pre>";
        print_r($this->make_array(json_decode($userCompleteDetails, true)));
        echo "</pre>";
        echo "<hr>";
        echo "<pre>";
        print_r(json_decode($categoryClient->sgetSpecializationListByParentId(1, $id2), true));
        echo "</pre>";
    }

    function runGenerateLeadCron($appId = 1, $page) {
        $this->init();
        $marketingClientObj = new MarketingClient();
        $addUser = $marketingClientObj->runGenerateLeadCron($appId, $page);
    }

    function runGenerateLeadCronForConsultants($appId = 1, $page='+') {
        $this->init();
        $marketingClientObj = new MarketingClient();
        $addUser = $marketingClientObj->runGenerateLeadCronForConsultants($appId, $page);
    }

    function cmsUserValidation() {
        $validity = $this->checkUserValidation();
        global $logged;
        global $userid;
        global $usergroup;
        $thisUrl = $_SERVER['REQUEST_URI'];
        if (($validity == "false" ) || ($validity == "")) {
            $logged = "No";
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        } else {
            $logged = "Yes";
            $userid = $validity[0]['userid'];
            $usergroup = $validity[0]['usergroup'];
            if ($usergroup == "user" || $usergroup == "requestinfouser" || $usergroup == "quicksignupuser" || $usergroup == "tempuser") {
                header("location:/enterprise/Enterprise/migrateUser");
                exit;
            }
            if (!(($usergroup == "cms"))) {
                header("location:/enterprise/Enterprise/unauthorizedEnt");
                exit();
            }
        }
        $this->load->library('enterprise_client');
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $validity[0]['usergroup'], $validity[0]['userid']);
        $this->load->library('sums_product_client');
        $objSumsProduct = new Sums_Product_client();
        $myProductDetails = $objSumsProduct->getProductsForUser(1, array('userId' => $userid));
        $returnArr['userid'] = $userid;
        $returnArr['usergroup'] = $usergroup;
        $returnArr['logged'] = $logged;
        $returnArr['thisUrl'] = $thisUrl;
        $returnArr['validity'] = $validity;
        $returnArr['headerTabs'] = $headerTabs;
        $returnArr['myProducts'] = $myProductDetails;
        return $returnArr;
    }

    function RunCron($dodo) {
        ini_set('max_execution_time', '1800');
        $this->load->helper(array('form', 'url', 'date', 'image', 'html'));
        $this->load->library(array('MailerClient', 'miscelleneous', 'message_board_client', 'blog_client', 'event_cal_client', 'ajax', 'category_list_client', 'listing_client', 'register_client','enterprise_client', 'sums_manage_client', 'table'));
        $objmailerClient = new MailerClient;
        $response = array();
        $response['resultSet'] = $objmailerClient->runCronMailer("1");
    }

    function SmsOldTemplate($prodId) {
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
        $cmsPageArr['headerTabs'] = $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
        $cmsPageArr['prodId'] = $this->prodId;
        $cmsPageArr['templateType'] = "sms";
        $response = array();
        $objmailerClient = new MailerClient;
        $response['resultSet'] = $objmailerClient->getAllSmsTemplates($this->appId, $userid, $usergroup);
        $response['countresult'] = count($response['resultSet']);
        $cmsPageArr['response'] = $response;
        $this->load->view('mailer/mailer_homepage', $cmsPageArr);
    }

    // Mailer Home Page View Load
    function index1($prodId) {
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
        $cmsPageArr['headerTabs'] = $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
        $cmsPageArr['prodId'] = $this->prodId;
        $cmsPageArr['templateType'] = "mail";
        $response = array();
        $objmailerClient = new MailerClient;
        $response['resultSet'] = $objmailerClient->getAllTemplates($this->appId, $userid, $usergroup);
        $response['countresult'] = count($response['resultSet']);
        $cmsPageArr['response'] = $response;
        $this->load->view('mailer/mailer_homepage', $cmsPageArr);
    }

    function getAllTemplates() {
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();
        $response = array();
        $objmailerClient = new MailerClient;
        $response['resultSet'] = $objmailerClient->getAllTemplates($this->appId, $userid, $usergroup);
        $response['countresult'] = count($response['resultSet']);
        $this->load->view('mailer/all_templates', $response);
    }

    function EditTemplateSms() {
        $this->init();
        $cmsUserInfo = $this->cmsUserValidation();
        $selectedTmpId = $this->input->post('selectedTmpId', true);
        $selectedTmpType = $this->input->post('templateType', true);
        if ($selectedTmpType == "") {
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
        $cmsPageArr['headerTabs'] = $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
        $cmsPageArr['prodId'] = $this->prodId;
        $cmsPageArr['cmsUserInfo'] = $cmsUserInfo;
        if ($selectedTmpId != '-1') {
            $cmsPageArr['mode'] = 'edit';
            $objmailerClient = new MailerClient;
            $cmsPageArr['resultSet'] = $objmailerClient->getTemplateInfo($this->appId, $selectedTmpId, $userid, $usergroup);
        } else {
            $cmsPageArr['mode'] = 'new';
            $cmsPageArr['resultSet'] = array();
        }
        $cmsPageArr['templateType'] = $selectedTmpType;
        $this->load->view('mailer/edit_template', $cmsPageArr);
    }

    function EditTemplate() {
        $this->init();
        $cmsUserInfo = $this->cmsUserValidation();
        $selectedTmpId = $this->input->post('selectedTmpId', true);
        $selectedTmpType = $this->input->post('templateType', true);
        if ($selectedTmpType == "") {
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
        $cmsPageArr['headerTabs'] = $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
        $cmsPageArr['prodId'] = $this->prodId;
        $cmsPageArr['cmsUserInfo'] = $cmsUserInfo;
        if ($selectedTmpId != '-1') {
            $cmsPageArr['mode'] = 'edit';
            $objmailerClient = new MailerClient;
            $cmsPageArr['resultSet'] = $objmailerClient->getTemplateInfo($this->appId, $selectedTmpId, $userid, $usergroup);
        } else {
            $cmsPageArr['mode'] = 'new';
            $cmsPageArr['resultSet'] = array();
        }
        $cmsPageArr['templateType'] = $selectedTmpType;
        $this->load->view('mailer/edit_template', $cmsPageArr);
    }

    function UpdateForm() {
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
        $var_result['headerTabs'] = $cmsUserInfo['headerTabs'];
        $var_result['myProducts'] = $cmsUserInfo['myProducts'];
        $var_result['prodId'] = $this->prodId;
        $var_result['cmsUserInfo'] = $cmsUserInfo;
        $request = array();
        $request ['edit_form_mode'] = $this->input->post('edit_form_mode', true);
        $request ['temp_id'] = $this->input->post('temp_id', true);
        $request ['tep_name'] = $this->input->post('temp1_name', true);
        $request ['temp_desc'] = $this->input->post('temp_desc');
        $request ['temp_html'] = $this->input->getRawRequestVariable('temp_html');
//    echo $request['temp_html'];
        $request ['temp_subj'] = $this->input->post('temp_subj', true);
        $request ['templateType'] = $this->input->post('templateType', true);
        $request ['createdBy'] = $userid;
        $objmailerClient = new MailerClient;
        try {
            $cmsPageArr['resultSet'] = $objmailerClient->insertOrUpdateTemplate($this->appId, $request ['temp_id'], $request['tep_name'], $request ['temp_desc'], $request ['temp_subj'], $request ['temp_html'], $request ['createdBy'], $request ['templateType']);
        } catch (Exception $e) {
            throw $e;
            error_log_shiksha('Error occoured during Template Saving' . $e, 'CMS-Mailer');
        }
        $request ['VariablesKey'] = $objmailerClient->getVariablesKey($this->appId);
        // To Do Fix Refersh Reload Problem here
        if ($cmsPageArr['resultSet'][0]['id']) {
            //echo $cmsPageArr['resultSet'][0]['id'];
            $var_result['result'] = $objmailerClient->getTemplateVariables($this->appId, $cmsPageArr['resultSet'][0]['id'], $userid, $usergroup);
            //print_r($var_result['result']);
            $var_result['mode'] = $request ['edit_form_mode'];
            $var_result['temp_id'] = $cmsPageArr['resultSet'][0]['id'];
            $var_result['VariablesKey'] = $request ['VariablesKey'];
            $var_result['templateType'] = $request ['templateType'];
            $this->load->view('mailer/edit_variable_template', $var_result);
        }
    }

    function setTemplateVariables() {
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
        $var_result['headerTabs'] = $cmsUserInfo['headerTabs'];
        $var_result['myProducts'] = $cmsUserInfo['myProducts'];
        $var_result['prodId'] = $this->prodId;
        $var_result['cmsUserInfo'] = $cmsUserInfo;
        $temp_name = $this->input->post('temp_name', true);
        $VariablesKey = $this->input->post('VariablesKey', true);
        $var_name = $this->input->post('var_name', true);
        $temp_id = $this->input->post('temp_id', true);
        $temp_op_mode = $this->input->post('temp_op_mode', true);
        $templateType = $this->input->post('templateType', true);
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
            $objmailerClient->setTemplateVariables($this->appId, $temp_id, $result, $userid, $usergroup);
            $var_result['temp_id'] = $temp_id;
            $var_result['temp_op_mode'] = $temp_op_mode;
            $var_result['templateType'] = $templateType;
            $this->load->view('mailer/test_mailer_templates', $var_result);
        } catch (Exception $e) {
            throw $e;
            error_log_shiksha('Error occoured during Variables Saving' . $e, 'CMS-Mailer');
        }
    }

    function getClientIdAndSubscriptionId() {
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
        $edit_form_mode = $this->input->post('edit_form_mode', true);
        $temp_id = $this->input->post('temp_id', true);
        $var_result['edit_form_mode'] = $edit_form_mode;
        $var_result['temp_id'] = $temp_id;
        $sendData = json_encode($var_result);
        error_log($sendData);
        header('location:/enterprise/Enterprise/searchUserForListingPost/22/' . base64_encode($sendData));
        // Load user saved lists
    }

    function setVariables_from_home() {
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
        $var_result['headerTabs'] = $cmsUserInfo['headerTabs'];
        $var_result['myProducts'] = $cmsUserInfo['myProducts'];
        $var_result['prodId'] = $this->prodId;
        $var_result['cmsUserInfo'] = $cmsUserInfo;
        $selectedTmpId = $this->input->post('selectedTmpId', true);
        $var_result['templateType'] = $this->input->post('templateType', true);
        $objmailerClient = new MailerClient;
        $var_result['result'] = $objmailerClient->getTemplateVariables($this->appId, $selectedTmpId, $userid, $usergroup);
        $var_result['mode'] = 'edit';
        $var_result['temp_id'] = $selectedTmpId;
        $VariablesKey = $objmailerClient->getVariablesKey($this->appId);
        $var_result['VariablesKey'] = $VariablesKey;
        $this->load->view('mailer/edit_variable_template', $var_result);
    }

    function dodo() {
        echo "<html><body><div style='display:none'><img src='https://172.16.3.226/mailer/Mailer/blank' /></div></body></html>";
    }

    function blank($redirectUrl, $mailerId, $emailId) {
        $this->load->library(array('MailerClient'));
        $objmailerClient = new MailerClient;
        $redirectUrl = base64_decode($redirectUrl);
        if ($redirectUrl == "1") {
            $redirectUrl = "";
        }
        $objmailerClient->submitOpenMail($this->appId, $mailerId, $emailId, $redirectUrl);
        if (trim($redirectUrl) == "") {
            echo $redirectUrl;
            exit();
        }
        header('location:' . $redirectUrl);
        exit();
    }

    function SelectUserList() {
        $this->init();
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        // get hidden values from Test mail page
        $edit_form_mode = $this->input->post('edit_form_mode', true);
        $this->load->library(array('Subscription_client'));
        $objSubs = new Subscription_client();
        $subscriptionId = $this->input->post('selectedSubs', true);

        $subDetails = $objSubs->getSubscriptionDetails($appId, $subscriptionId);
        $var_result123 = $this->input->post('extraInfoArray', true);
        $var_result123 = base64_decode($var_result123);
        $var_result = array();
        $var_result = json_decode($var_result123, true);
        $objmailerClient = new MailerClient;
        $templateInfo = $objmailerClient->getTemplateInfo($this->appId, $var_result['temp_id'], $userid, $usergroup);
        error_log("CDE " . print_r($templateInfo, true));
        $templateType = $templateInfo[0]['templateType'];
        error_log("CDE " . $templateType);
        // Load user saved lists
        $objmailerClient = new MailerClient;
        $var_result['result'] = $objmailerClient->getAllLists($this->appId, $userid, $usergroup);
        $form_array = $this->s_getSearchFormData();
        $var_result['form_array'] = $form_array;
        $sumsData['subscriptionId'] = $subscriptionId;
        $sumsData['clientUser'] = $this->input->post('clientUser', true);
        ;
        $sumsData['BaseProdRemainingQuantity'] = $subDetails[0]['BaseProdRemainingQuantity'];
        $sumsData['BaseProdCategory'] = $subDetails[0]['BaseProdCategory'];
        error_log("CDE " . $sumsData['BaseProdCategory']);
        error_log("CDE " . $templateType);

        preg_match('/' . $templateType . '/', strtolower($sumsData['BaseProdCategory']), $matches, PREG_OFFSET_CAPTURE);
        error_log("CDE " . print_r($matches, true));
        if (count($matches) <= 0) {
            echo "<script>alert('Oops! You Choose Wrong Client/Subscription');history.go(-2);</script>";
        }
        $var_result['sumsData'] = base64_encode(json_encode($sumsData));
        $this->load->view('mailer/SelectUserList_template', $var_result);
    }

    function handle_userlist($flag) {
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
        $var_result['headerTabs'] = $cmsUserInfo['headerTabs'];
        $var_result['myProducts'] = $cmsUserInfo['myProducts'];
        $var_result['prodId'] = $this->prodId;
        $var_result['cmsUserInfo'] = $cmsUserInfo;
        $objmailerClient = new MailerClient;
        $sumsData = $this->input->post('sums_data', true);
        $var_result['sumsData'] = $sumsData;
        $cvs_array = array();
        if (isset($_REQUEST['selectedTmpId']) && ($_REQUEST['user_list_template'] == 'use_old_list')) {
            $var_result['selectedListId'] = $this->input->post('selectedTmpId', true);
            $var_result['List_Detail'] = $objmailerClient->getListInfo($this->appId, $var_result['selectedListId'], $userid, $usergroup);
            $var_result['numUsers'] = $var_result['List_Detail'][0]['numUsers'];
        } else if (isset($_FILES['c_csv']) && ($_REQUEST['user_list_template'] == 'upload_csv')) {
            $templateId = $this->input->post('temp_id', true);
            $templateId = $this->input->post('temp_id', true);
            $cvs_array = $this->buildCVSArray($_FILES['c_csv']['tmp_name']);
            $var_result['result'] = $objmailerClient->checkTemplateCsv($this->appId, $templateId, $cvs_array, $userid, $usergroup);
            if ($var_result['result'][0] == '-1') {
                $var_result['empty_list_error'] = 'TRUE';
            }
            if ($var_result['result'][0] == '-2') {
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
                $this->load->view('mailer/update_New_List_template', $var_result);
            } else {
                $this->load->view('mailer/Summary_List_template', $var_result);
            }
        } else if ($_REQUEST['user_list_template'] == 'search_new_criteria') {
            $templateId = $this->input->post('temp_id', true);
            $mode = $this->input->post('edit_form_mode', true);
            $this->save_SearchFormDataParams($templateId, $mode, $userid, $usergroup);
        }
        $var_result['edit_form_mode'] = $this->input->post('edit_form_mode', true);
        $var_result['temp_id'] = $this->input->post('temp_id', true);
        $var_result['all_Lists'] = $objmailerClient->getAllLists($this->appId, $userid, $usergroup);

        if ($_REQUEST['user_list_template'] == 'use_old_list') {
            $this->load->view('mailer/Edit_List_template', $var_result);
        }
    }

    function UpdateUserList() {
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
        $var_result['headerTabs'] = $cmsUserInfo['headerTabs'];
        $var_result['myProducts'] = $cmsUserInfo['myProducts'];
        $var_result['prodId'] = $this->prodId;
        $var_result['cmsUserInfo'] = $cmsUserInfo;
        $objmailerClient = new MailerClient;
        $var_result['mode'] = $this->input->post('mode', true);
        $var_result['temp_id'] = $this->input->post('temp_id', true);
        $var_result['list_id'] = $this->input->post('list_id', true);
        $var_result['mails_limit_text'] = $this->input->post('mails_limit_text', true);
        $var_result['mails_limit'] = $this->input->post('mails_limit', true);
        $var_result['export_csv'] = $this->input->post('export_csv', true);
        $var_result['all_Lists'] = $this->input->post('all_Lists', true);
        $var_result['sumsData'] = $this->input->post('sumsData', true);
        if ($var_result['export_csv'] == "on") {
            if (!empty($var_result['mails_limit_text']) && is_numeric($var_result['mails_limit_text'])) {
                $numEmail = $var_result['mails_limit_text'];
            } else if (!empty($var_result['mails_limit']) && ($var_result['mails_limit'] == '-1')) {
                $numEmail = $var_result['mails_limit'];
            }

            header("Content-type: text/x-csv");
            $filename = preg_replace('/[^A-Za-z0-9]/', '', "UserList_" . $var_result['list_id']);
            header("Content-Disposition: attachment; filename=" . $filename . ".csv");
            $finalOutput = "";
            $returnData = $objmailerClient->getListCsv($this->appId, $var_result['list_id'], $list, $numEmail, $userid, $usergroup);
            $csvData = $returnData[0]['usersArr'];
            $csvDataArr = json_decode($csvData, true);
            $finalOutput = "displayname,userId,email,mobile";
            for ($i = 0; $i < count($csvDataArr); $i++) {
                $finalOutput .= "\n" . $csvDataArr[$i]['displayname'] . "," . $csvDataArr[$i]['userId'] . "," . $csvDataArr[$i]['email'] . "," . $csvDataArr[$i]['mobile'];
            }
            echo $finalOutput;
            exit(0);
        }
        if (!empty($var_result['mails_limit_text']) && is_numeric($var_result['mails_limit_text'])) {
            $numEmail = $var_result['mails_limit_text'];
        } else if (!empty($var_result['mails_limit']) && ($var_result['mails_limit'] == '-1')) {
            $numEmail = $var_result['mails_limit'];
        }
        if (empty($var_result['all_Lists'])) {
            $list = array();
        } else {
            $list = $var_result['all_Lists'];
        }
        $var_result['result'] = $objmailerClient->submitList($this->appId, $var_result['list_id'], $list, $numEmail, $userid, $usergroup);
        if ($var_result['result'][0]['isActive'] == 'false') {
            $list_id = $var_result['result'][0]['id'];
            $var_result['new_list_id'] = $list_id;
            $var_result['new_list_name'] = $var_result['result'][0]['name'];
            $var_result['new_list_desc'] = $var_result['result'][0]['description'];
            $var_result['new_usersArr'] = $var_result['result'][0]['usersArr'];
            $var_result['numUsers'] = $var_result['result'][0]['numUsers'];
            $this->load->view('mailer/update_New_List_template', $var_result);
        } else {
            $this->load->view('mailer/Summary_List_template', $var_result);
        }
    }

    function update_New_List_template() {
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
        $var_result['headerTabs'] = $cmsUserInfo['headerTabs'];
        $var_result['myProducts'] = $cmsUserInfo['myProducts'];
        $var_result['prodId'] = $this->prodId;
        $var_result['cmsUserInfo'] = $cmsUserInfo;
        $objmailerClient = new MailerClient;
        $listId = $this->input->post('list_id', true);
        $name = $this->input->post('temp1_name', true);
        $desc = $this->input->post('temp_desc', true);
        $var_result['mode'] = $this->input->post('mode', true);
        $var_result['temp_id'] = $this->input->post('temp_id', true);
        $var_result['list_id'] = $listId;
        $var_result['sumsData'] = $this->input->post('sumsData', true);
        $var_result['result'] = $objmailerClient->updateListInfo($this->appId, $listId, $name, $desc, $userid, $usergroup);
        $this->load->view('mailer/Summary_List_template', $var_result);
    }

    function Summary_List_template() {
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
        $var_result['headerTabs'] = $cmsUserInfo['headerTabs'];
        $var_result['myProducts'] = $cmsUserInfo['myProducts'];
        $var_result['prodId'] = $this->prodId;
        $var_result['cmsUserInfo'] = $cmsUserInfo;
        $objmailerClient = new MailerClient;
        $list_id = $this->input->post('list_id', true);
        $temp_id = $this->input->post('temp_id', true);
        $mode = $this->input->post('mode', true);
        $userFeedbackEmail = $this->input->post('userFeedbackEmail', true);
        $trans_start_date = $this->input->post('trans_start_date', true);
        $temp1_name = $this->input->post('temp1_name', true);
        $sumsData = $this->input->post('sumsData', true);
        try {
            $result = $objmailerClient->saveMailer($this->appId, $temp1_name, $temp_id, $list_id, $trans_start_date, $userFeedbackEmail, $userid, $usergroup, $sumsData);
            error_log("CONSUME RETURN " . print_r($result, true));
            $success_flag = "false";
            if (!($result['ERROR'] == 1)) {
                $success_flag = "true";
            } else {
                $success_flag = "false";
            }
            error_log("CONSUME sucess Flag = " . $success_flag);
            $var_result['list_id'] = $list_id;
            $var_result['temp_id'] = $temp_id;
            $var_result['mode'] = $mode;
            $var_result['trans_start_date'] = $trans_start_date;
            $var_result['temp1_name'] = $temp1_name;
            $var_result['success_flag'] = $success_flag;
            $var_result['last_mailer_id'] = $result[0];
            $this->load->view('mailer/Summary_List_Result_template', $var_result);
        } catch (Exception $e) {
            throw $e;
            error_log(' CONSUME Error occoured during save Mailer' . $e, 'CMS-Mailer');
        }
    }

    /*
     * For Ajax call to test html mail
     */

    function Test_mail_List($TempId, $email) {
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
        $var_result['headerTabs'] = $cmsUserInfo['headerTabs'];
        $var_result['myProducts'] = $cmsUserInfo['myProducts'];
        $var_result['prodId'] = $this->prodId;
        $var_result['cmsUserInfo'] = $cmsUserInfo;
        $objmailerClient = new MailerClient;
        $result = $objmailerClient->sendTestMailer($this->appId, $TempId, $email, $userid, $usergroup);
        if (!empty($result[0]) && is_numeric($result[0])) {
            echo $result[0];
        } else if ($TempId == 'null') {
            echo '-1';
        }
    }

    function s_getSearchFormData() {
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
        $var_result['headerTabs'] = $cmsUserInfo['headerTabs'];
        $var_result['myProducts'] = $cmsUserInfo['myProducts'];
        $var_result['prodId'] = $this->prodId;
        $var_result['cmsUserInfo'] = $cmsUserInfo;
        $objmailerClient = new MailerClient;
        $result = $objmailerClient->s_getSearchFormData($this->appId);
        /* Generate Array for Form Start */
        $form_array = array();
        if (count(json_decode($result[0])) > 0) {
            $i = 0;
            foreach (json_decode($result[0]) as $value) {
                if (is_object($value)) {
                    $option = array();
                    foreach ($value as $element) {
                        if (is_object($element)) {
                            $option[] = array(
                                $element->filterValueId =>
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

    function save_SearchFormDataParams($temp_id, $mode, $userid, $usergroup) {
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
        $var_result['headerTabs'] = $cmsUserInfo['headerTabs'];
        $var_result['myProducts'] = $cmsUserInfo['myProducts'];
        $var_result['prodId'] = $this->prodId;
        $var_result['cmsUserInfo'] = $cmsUserInfo;
        $var_result['temp_id'] = $temp_id;
        $objmailerClient = new MailerClient;
        $sumsData = $this->input->post('sums_data', true);
        $var_result['sumsData'] = $sumsData;
        if (count($_REQUEST) > 0) {
            $sendArr = array();
            foreach ($_REQUEST as $key => $value) {
                if (ereg('combo_search', $key)) {
                    $sendArr[$key[0]] = $value;
                }
                if (ereg('checkbox_search', $key)) {
                    $sendArr[$key[0]] = $value;
                }
                if (ereg('range_search', $key)) {
                    $sendArr[$key[0]][] = array(
                        'value' => $value[0],
                        'id' => $key[1]
                    );
                }
                if (ereg('date_search', $key)) {
                    $sendArr[$key[0]][] = array(
                        'value' => $value[0],
                        'id' => $key[1]
                    );
                }
            }
        }
        /* Hack to handle error */
        $flag_empty_array = true;
        foreach ($_REQUEST as $k => $v) {
            if (ereg('_search', $k)) {
                foreach ($v as $val) {
                    if (empty($val)) {
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
            $this->load->view('mailer/Summary_List_template', $var_result);
        } else {
            $result['result'] = $objmailerClient->s_submitSearchQuery($this->appId, $sendArr, $userid, $usergroup);
        }
        if ($result['result'][0] != '-1') {
            $list_id = $result['result'][0]['id'];
            $var_result['new_list_id'] = $list_id;
            $var_result['new_list_name'] = $result['result'][0]['name'];
            $var_result['new_list_desc'] = $result['result'][0]['description'];
            $var_result['new_usersArr'] = $result['result'][0]['usersArr'];
            $var_result['numUsers'] = $result['result'][0]['numUsers'];
            $var_result['edit_form_mode'] = $mode;
            $var_result['temp_id'] = $temp_id;
            $var_result['all_Lists'] = $objmailerClient->getAllLists($this->appId, $userid, $usergroup);
            $var_result['selectedListId'] = $list_id;
            $var_result['List_Detail'] = $objmailerClient->getListInfo($this->appId, $list_id, $userid, $usergroup);

            $this->load->view('mailer/Edit_List_template', $var_result);
        } else {
            if ($result['result'][0] == '-1') {
                $var_result['error_empty_array'] = 'TRUE';
            }
            $this->load->view('mailer/Summary_List_template', $var_result);
        }
    }

    function buildCVSArray($File) {
        $handle = fopen($File, "r");
        $fields = fgetcsv($handle, 1000, ",");
        while ($data = fgetcsv($handle, 1000, ",")) {
            $detail[] = $data;
        }
        $x = 0;
        foreach ($fields as $z) {
            foreach ($detail as $i) {
                $stock[$z][] = $i[$x];
            }

            $x++;
        }
        return $stock;
    }

    function MisReportDisplay() {
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
        $var_result['headerTabs'] = $cmsUserInfo['headerTabs'];
        $var_result['myProducts'] = $cmsUserInfo['myProducts'];
        $var_result['prodId'] = $this->prodId;
        $var_result['cmsUserInfo'] = $cmsUserInfo;
        $objmailerClient = new MailerClient;
        $var_result['resultSet'] = $objmailerClient->getMailersList($this->appId, $userid, $usergroup);
        $var_result['countresult'] = count($var_result['resultSet']);
        $this->load->view('mailer/MisReportDisplay', $var_result);
    }

    function getMailerTrackingUrls($id) {
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
        $var_result['headerTabs'] = $cmsUserInfo['headerTabs'];
        $var_result['myProducts'] = $cmsUserInfo['myProducts'];
        $var_result['prodId'] = $this->prodId;
        $var_result['cmsUserInfo'] = $cmsUserInfo;
        $objmailerClient = new MailerClient;
        $var_result['resultSet'] = $objmailerClient->getMailerTrackingUrls($this->appId, $userid, $usergroup, $id, '', '', '');
        $var_result['countresult'] = count($var_result['resultSet']);
        $var_result['id'] = $id;
        $this->load->view('mailer/getMailerTrackingUrls', $var_result);
    }

    function MailerTrackingUrlsformsubmit() {
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
        $var_result['headerTabs'] = $cmsUserInfo['headerTabs'];
        $var_result['myProducts'] = $cmsUserInfo['myProducts'];
        $var_result['prodId'] = $this->prodId;
        $var_result['cmsUserInfo'] = $cmsUserInfo;
        $objmailerClient = new MailerClient;
        $mailerId = $this->input->post('id', true);
        $startTime = $this->input->post('trans_start_date', true);
        $endTime = $this->input->post('trans_end_date', true);
        $var_result['resultSet'] = $objmailerClient->getMailerTrackingUrls($this->appId, $userid, $usergroup, $mailerId, '', $startTime, $endTime);
        $var_result['countresult'] = count($var_result['resultSet']);
        $var_result['id'] = $this->input->post('id', true);
        ;
        $this->load->view('mailer/getMailerTrackingUrls', $var_result);
    }

    function poll_form_submit($poll_quest, $ans1, $ans2, $ans3) {
        $optionArray = array();
        $optionArray = array($ans1, $ans2, $ans3);
        $this->load->library('MailerClient');
        $objmailerClient = new MailerClient;
        $result = $objmailerClient->createPoll($this->appId, $poll_quest, $poll_quest, $optionArray);
        //print_r($result);die;
        $content = '<div style="text-align: right; width: 170;">
					<form action="' . SHIKSHA_HOME_URL . '/mailer/Mailer/userPollOpinion" method="POST">
					<table width="170" cellpadding="2" cellspacing="0" border="0" style="background-color: none; border: 1px #333333 solid;">
					<tr><td align="center" colspan="2" style="color: #000000; font-family: Verdana; font-weight: bold;">' . $poll_quest . '<br><br></td></tr>
					<tr><td><input type="radio" name="answer"  value="' . $result['pollOption'][0]['optionId'] . '"/></td>
					<td width="100%"><label for="' . $result['pollOption'][0]['optionId'] . '" style="color: #000000; font-family: Verdana;">' . $result['pollOption'][0]['optionName'] . '</label></td></tr>
					<tr><td><input type="radio" name="answer"  value="' . $result['pollOption'][1]['optionId'] . '"/></td>
					<td width="100%"><label for="' . $result['pollOption'][1]['optionId'] . '" style="color: #000000; font-family: Verdana;">' . $result['pollOption'][0]['optionName'] . '</label></td></tr>
					<tr><td><input type="radio" name="answer"  value="' . $result['pollOption'][2]['optionId'] . '"/></td>
					<td width="100%"><label for="' . $result['pollOption'][2]['optionId'] . '" style="color: #000000; font-family: Verdana;">' . $result['pollOption'][0]['optionName'] . '</label></td></tr>
					<tr><td colspan="2" align="center">
					<br><input type="submit" value="Vote" style="border: 1px #333333 solid;"/><br>
					<input type="hidden" name="poll_id" value="' . $result['poll_id'] . '"/>
					<input type="hidden" name="emailId" value="<!-- #varNamemailId --><!-- varNamemailId# -->"/>
                    <input type="hidden" name="trackerId" value="<!-- #varNametrackerId --><!-- varNametrackerId# -->"/>
					</td></tr>
					</table></form></div>';
        echo $content;
    }

    function userPollOpinion() {
//        print_r($_POST);
        $poll_id = $this->input->post('poll_id', true);
        $poll_opinion = $this->input->post('answer', true);
        $email = $this->input->post('emailId', true);
        $mailer_id = $this->input->post('trackerId', true);
        //$userOpinion = $_POST['answer[]'];
        $this->load->library('MailerClient');
        $objmailerClient = new MailerClient;
        $result = $objmailerClient->registerPoll($this->appId, $mailer_id, $email, $poll_id, $poll_opinion);
        echo "<script>alert('Thank you for submitting your Vote! Please visit shiksha.com for all education related Queries!!');location.href = 'https://www.shiksha.com' ;</script>";
    }

    function registerAutomaticFeedback($data) {
        $this->load->library('MailerClient');
        $objmailerClient = new MailerClient;
        $result = $objmailerClient->registerData($this->appId, $data);
        echo "<script>alert('Thank you for showing your interest!!');location.href = 'https://www.shiksha.com' ;</script>";
    }

    function leadFeedback() {
        error_log("POI" . $feedback);
        $email = $this->input->post('code', true);
        $mailer_id = $this->input->post('trackerId', true);
        $typeId = $this->input->post('typeId', true);
        $type = $this->input->post('type', true);
        $feedback = $this->input->post('feedback', true);
        error_log("POI" . $feedback);


        //$userOpinion = $_POST['answer[]'];
        //print_r($_POST);
        $this->load->library('MailerClient');
        $objmailerClient = new MailerClient;
        $result = $objmailerClient->registerLead($this->appId, $mailer_id, $email, $feedback, $typeId, $type);
        echo "<script>alert('Thank you for submitting your Feedback! Please visit shiksha.com for all education related Queries!!');location.href = 'https://www.shiksha.com' ;</script>";
    }

    function userFeedback() {
        $email = $this->input->post('emailId', true);
        $mailer_id = $this->input->post('trackerId', true);
        $feedback = $this->input->post('feedback', true);
        //$userOpinion = $_POST['answer[]'];
        //print_r($_POST);
        $this->load->library('MailerClient');
        $objmailerClient = new MailerClient;
        $result = $objmailerClient->registerFeedback($this->appId, $mailer_id, $email, $feedback);
        echo "<script>alert('Thank you for submitting your Feedback! Please visit shiksha.com for all education related Queries!!');location.href = 'https://www.shiksha.com' ;</script>";
    }

    private function getCityStatesFromMap($countryStateCityMap) {
        $cities = $states = $countries = array();
        foreach ($countryStateCityMap as $country) {
            $countryName = $country['CountryName'];
            $countryId = $country['CountryId'];
            $countries[$countryId]['name'] = $countryName;
            $countries[$countryId]['value'] = $countryId . ':0:0'; // For TUserLocationPref
            $statesForCountry = $country['stateMap'];
            foreach ($statesForCountry as $state) {
                $stateName = $state['StateName'];
                $stateId = $state['StateId'];
                if (!empty($stateId)) {
                    $states[$stateId]['name'] = $stateName;
                    $states[$stateId]['value'] = $countryId . ':' . $stateId . ':0';
                }
                $citiesForState = $state['cityMap'];
                foreach ($citiesForState as $city) {
                    $cityName = $city['CityName'];
                    $cityId = $city['CityId'];
                    $cityTier = $city['Tier'];
                    $cities['tier' . $cityTier][$cityId]['name'] = $cityName;
                    $cities['tier' . $cityTier][$cityId]['value'] = $countryId . ':' . $stateId . ':' . $cityId;
                }
            }
        }
        return array('cities' => $cities, 'states' => $states, 'countries' => $countries);
    }

    function studyAbroad($pagename='studyAbroad', $url='') {
        $this->init();
        $validity = $this->checkUserValidation();
        global $logged;
        global $userid;
        global $usergroup;
        $thisUrl = $_SERVER['REQUEST_URI'];
        if (($validity == "false" ) || ($validity == "")) {
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

        $data['pageName'] = 'studyAbroad';
        // load data for courses list
        $this->load->library('Category_list_client');
        $category_list_client = new Category_list_client();
        $categoryList = $category_list_client->getCategoryList(1, 1);
        $catgeories = array();
        foreach ($categoryList as $category) {
            $categories[$category['categoryID']] = $category['categoryName'];
        }
        asort($categories);
        $data['categories'] = $categories;

        $regions = json_decode($category_list_client->getCountriesWithRegions(1), true);
        $data['regions'] = $regions;
        $data['course_lists'] = $this->_course_lists();

        $cityListTier1 = $category_list_client->getCitiesInTier($appId, 1, 2);
        $cityListTier2 = $category_list_client->getCitiesInTier($appId, 2, 2);
        $cityListTier3 = $category_list_client->getCitiesInTier($appId, 0, 2);
        $data['cityTier2'] = $cityListTier2;
        $data['cityTier3'] = $cityListTier3;
        $data['cityTier1'] = $cityListTier1;
        $ldbObj = new LDB_Client();
        $data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12), true);
        $this->load->view('marketing/generalMarketingPage', $data);
    }

    function getNextOverlayForStudyAbroad($keyname = 'MARKETING_FORM', $prefId="", $desiredCourseLevel = 'PG') {
        $this->init();
        $data = array();
        $validity = $this->checkUserValidation();
        $url = 'marketing/studyAbroadOverlay';
        $appId = 1;
        $this->load->library('LDB_Client');
        $categoryClient = new LDB_Client();
        $userid = $this->userid;
        if ($userid != '')
            $userCompleteDetails = $categoryClient->sgetUserDetails($appId, $userid);
        $data['prefId'] = $prefId;
        $data['userCompleteDetails'] = $this->make_array(json_decode($userCompleteDetails, true));
        $data['desiredCourseLevel'] = $desiredCourseLevel;
        $data['validateuser'] = $validity;
        $string = $this->load->view($url, $data, true);
        echo $string;
    }

}

?>
