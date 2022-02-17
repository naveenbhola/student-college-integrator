<?php

/**
 * File for Handling registration of study abroad
 */

/**
 * Description of Register_StudyAbroad
 *
 * @author ashish mishra
 */
class Register_StudyAbroad extends MX_Controller {
    
    /**
     * Index function for initialization
     *
     * @param string $category
     * @param integer $countryid
     * @param string $country_name
     */
    function index($category,$countryid,$country_name){
	$data = $this->studyAbroad('studyAbroad');
        $this->userStatus = $this->checkUserValidation();
        if (!($this->userStatus == "false" || $this->userStatus == "")){
            $data["loggedIn"] = "true";
        }
        else
            $data["loggedIn"] = "false";
        $data['country_abroad'] = $countryid;
        $data['country_name_abroad'] = $country_name;
        $data['category_abroad'] = $category;
        $this->load->view("Register_StudyAbroad_Form",$data);
    }
    
    /**
     * Function to load the study abroad widget
     */
    function loadStudyAbroadRegistrationWidget(){
	echo Modules::run('registration/Forms/LDB','studyAbroad',NULL,array('registrationSource' => 'STUDY-ABROAD-LEFT-BOTTOM-FORM','referrer' => 'https://shiksha.com#loadStudyAbroadRegistrationWidget'));
    }
    
    /**
     * Function generating data for study abraod page
     *
     * @param string $pagename
     * @param string $url
     */
    function studyAbroad($pagename='studyAbroad', $url='') {
        $this->load->library(array('LDB_Client', 'category_list_client', 'listing_client', 'MY_sort_associative_array'));
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
        $data['userData'] = $validity;
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
        $data['allCategories'] = $categoryList;
        $ldbObj = new LDB_Client();
        $data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12), true);
        return $data;
    }
    
    /**
     * Fucntion to get the course lists
     *
     * @param string $selected
     * @param boolean $flag
     */
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
}

?>
