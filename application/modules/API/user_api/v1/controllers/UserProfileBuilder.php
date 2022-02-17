<?php
/**
 * User Class
 * This is the class for all the APIs related to user profile builder
 * @date    2015-07-16
 * @author  Yamini Bisht
 * @todo    none
*/

class UserProfileBuilder extends APIParent {

	private $validationObj;
	private $userCommonLib;

	function __construct() {
		parent::__construct();
		$this->load->library(array('UserValidationLib', 'UserCommonLib','UserProfileBuilderLib'));
		$this->validationObj = new UserValidationLib();
                $this->userCommonLib = new UserCommonLib();
                $this->profileBuilderLib = new UserProfileBuilderLib();
                $this->userprofilebuildermodel = $this->load->model('user/userprofilebuildermodel');
	}
        
        /**
         * @desc API to fetch data of question asked for profile builder
         * @param AuthChecksum containing the logged-in user details
         * @param profileType is the type of user(consumer/producer)
         * courseInfoFilled is false ,if user has not selected any course otherwise value is courses
         * countryInfoFilled is false ,if user has not selected country otherwise value is country
         * courseLevelFilled is false ,if user has not selected course level otherwise value is course_level
         * @return JSON string with HTTP Code 200 and News feed items
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-08-20
         * @author Yamini Bisht
         */
        
        function userProfileBuilderData($profileType="consumer",$courseInfoFilled=false,$countryInfoFilled=false,$courseLevelFilled=false){
		
            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';
            
            //step 2:validate all the fields
                if(! $this->validationObj->validateUserProfileBuilderData($this->response, array('userId'=>$userId, 'profileType'=>$profileType, 'courseInfoFilled'=>$courseInfoFilled, 'countryInfoFilled'=>$countryInfoFilled,'courseLevelFilled'=>$courseLevelFilled))){
                        
                        return;
                }
                
            //Step 3:fetch course,countries and level list
                
                $this->load->config('UserProfileBuilderConfig',TRUE);
                $this->profileBuilderArray = $this->config->item('profileBuilderData','UserProfileBuilderConfig');
                
                if($courseInfoFilled == 'false'){
                    $courses = $this->userprofilebuildermodel->getTagInfoForProfileBuilder('Stream');
		    foreach($courses as $courseId=>$coursesName){
			$result['courses'][] = array("id" => "$courseId", "label" => $coursesName);
		    }
		    
                }
               
                if($countryInfoFilled == 'false'){
                    $popularCountriesName = $this->profileBuilderArray['PopularCountries'];
                    
                    $popularCountryNameString = '';
                    foreach($popularCountriesName as $country)
                    {
                        if($popularCountryNameString != '')
                            $popularCountryNameString .= ",'".$country."'";
                        else
                            $popularCountryNameString = "'".$country."'";
                    }
                    
                    $popularCountries = $this->userprofilebuildermodel->getTagIdForDiffTags($popularCountryNameString,'Country');
		    
		    foreach($popularCountries as $countryId=>$countryName){
			$result['popularCountries'][] = array("id" => "$countryId", "label" => $countryName);
		    }
                    
                    $allCountries = $this->userprofilebuildermodel->getTagInfoForProfileBuilder('Country');
                    
                    $otherCountries = array();
                    $otherCountries = array_diff($allCountries,$popularCountries);
		    
		    foreach($otherCountries as $otherCountryId=>$otherCountryName){
			 $result['otherCountries'][] = array("id" => "$otherCountryId", "label" => $otherCountryName);
		    }
                    
                }
                
                 if($courseLevelFilled == 'false' && $profileType != 'producer'){
                    $courseLevelArray = $this->profileBuilderArray['courseLevel'];
                    
                    $courseLevelString = '';
                    foreach($courseLevelArray as $level)
                    {
                        if($courseLevelString != '')
                            $courseLevelString .= ",'".$level."'";
                        else
                            $courseLevelString = "'".$level."'";
                    }
                    
                    $courseLevel = $this->userprofilebuildermodel->getTagIdForDiffTags($courseLevelString,'Course Level');
		    
		    foreach($courseLevel as $courseLevelId=>$courseLevelName){
			  $result['courseLevel'][] = array("id" => "$courseLevelId", "label" => $courseLevelName);
		    }
                    
                }
                
                $result['profileType'] = $profileType;
		
		$this->response->setBody($result);
		
            // track data
            $this->_trackAPI($result, "userProfileBuilderData");

            //Step 4: Return the Response
                $this->response->output();
            
        }
        
         /**
         * @desc API to insert user profile builder data in DB
         * @param AuthChecksum containing the logged-in user details
         * @param profileData is the data of question asked
         * Type is the type of question widget like courses,country or course_level
         * @return JSON string with HTTP Code 200 and News feed items
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-08-20
         * @author Yamini Bisht
         */
        
        function insertProfileBuilderTagData(){
            
            //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';
                $profileData = (isset($_POST['profileData'])) ? $this->input->post('profileData') : '';
		$profileBuilderInfo = (isset($_POST['profileBuilderInfo'])) ? $this->input->post('profileBuilderInfo') : '';
		$profileType = (isset($_POST['profileType'])) ? $this->input->post('profileType') : 'consumer';
		
		$builderData = json_decode($profileBuilderInfo,true);
		
		$questionLeft = substr($builderData['message'],7,1);
		
		$data = json_decode($profileData,true);
		
		if($profileType =='consumer'){
				$courseFollowType = 'stream_interest';
				$countryFollowType = 'countries_interest';
				$levelFollowType = 'course_level';
				
		}else{
				$courseFollowType = 'stream';
				$countryFollowType = 'country';
		}
	
		if (array_key_exists('courses', $data)) {
			$builderData['courseInfoFilled'] = true;
			$questionLeft = $questionLeft-1;
			$followType = $courseFollowType;
			
			
		}
		$indiaId = '';
		if (array_key_exists('countries', $data)) {
			$builderData['countryInfoFilled'] = true;
			$questionLeft = $questionLeft-1;
			$followType = $countryFollowType;
			$countriesId = array();
			foreach($data['countries'] as $key=>$value){
				$countriesId[] = $value['id'];
			}
			
			$countriesName = $this->userprofilebuildermodel->getCountriesName($countriesId);
			if(!empty($countriesName)){
				foreach($countriesName as $key=>$value){
					if($value['tags'] == "India"){
						$indiaId = $value['id'];
						break;
					}
				}
				
			}
			
			
		}
		
		if (array_key_exists('courseLevel', $data) && $builderData['profileType'] =='consumer') {
			$builderData['courseLevelFilled'] = true;
			$questionLeft = $questionLeft-1;
			$followType = $levelFollowType;
			
		}
		$builderData['message'] = ($questionLeft == 1) ? "Answer ".$questionLeft." simple question to see relevant questions and discussions!" : "Answer ".$questionLeft." simple questions to see relevant questions and discussions!";
		
		$resultArray['profileBuilderInfo'] = $builderData;
		
            //step 2:validate all the fields
                if(! $this->validationObj->validateProfileBuildeTagData($this->response, array('userId'=>$userId))){
                        
                        return;
                }
              
	      
            //step 3:Insert user profile tags in DB
	    $this->load->model('common/UniversalModel');
	    if(!empty($data)){
		$this->userprofilebuildermodel->insertUserProfileFollowCheckData($userId,'tag',$followType,'live');
                foreach($data as $val){
			foreach($val as $key=>$value){
				if($value['id'] != $indiaId && $value['id'] != ''){
					$this->UniversalModel->followEntity($userId,$value['id'],'tag','follow',$followType);
				}
			}
                }
	    }else{
		$this->response->setStatusCode(STATUS_CODE_FAILURE);
                $this->response->setResponseMsg('Profile Data cannot empty');
	    }

        if($followType ==  $courseFollowType){
            $tagIds = array();
            foreach ($data['courses'] as $row) {
                $tagIds[] = $row['id'];
            }
            $this->userCommonLib->sendStreamDigestMailByTagIds($userId,$tagIds);
        }
	    
	    if($questionLeft == 0){
		$resultArray['profileBuilderInfo']= NULL;
	    }
            
	    $this->response->setBody($resultArray);
            //Step 4: Return the Response
                $this->response->output();
        }

        /**
         * @desc API to fetch data of question asked for User profile
         * @param AuthChecksum containing the logged-in user details
         * @return JSON string with HTTP Code 200 and News feed items
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-12-04
         * @author Ankur Gupta
         */
        function getCoursesList(){

            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';

            //step 2:validate all the fields
            if(! $this->validationObj->validateUserId($this->response, array('userId'=>$userId))){
                    return;
            }

            //Step 3:fetch courses
            $courses = $this->userprofilebuildermodel->getTagInfoForProfileBuilder('Course');
            foreach($courses as $courseId=>$coursesName){
                $result['courses'][] = array("id" => "$courseId", "label" => $coursesName);
            }

            $this->response->setBody($result);


            //Step 4: Return the Response
            $this->response->output();
        }

        /**
         * @desc API to fetch data of question asked for User profile
         * @param AuthChecksum containing the logged-in user details
         * @return JSON string with HTTP Code 200 and News feed items
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-12-04
         * @author Ankur Gupta
         */
        function getSpecializationList(){

            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';

            //step 2:validate all the fields
            if(! $this->validationObj->validateUserId($this->response, array('userId'=>$userId))){
                    return;
            }

            //Step 3:fetch courses
            $courses = $this->userprofilebuildermodel->getTagInfoForSpecialization();
            foreach($courses as $courseId=>$coursesName){
                $result['Streams'][] = array("id" => "$courseId", "label" => $coursesName['name'], "specializations" => $coursesName['tags']);
            }

            $this->response->setBody($result);


            //Step 4: Return the Response
            $this->response->output();

        }

        /**
         * @desc API to fetch data of question asked for User profile
         * @param AuthChecksum containing the logged-in user details
         * @return JSON string with HTTP Code 200 and News feed items
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-12-04
         * @author Ankur Gupta
         */
        function getCities(){
            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';

            //step 2:validate all the fields
            if(! $this->validationObj->validateUserId($this->response, array('userId'=>$userId))){
                    return;
            }

            //Step 3:fetch courses
            $this->load->config('UserProfileBuilderConfig',TRUE);
            $this->profileBuilderArray = $this->config->item('profileBuilderData','UserProfileBuilderConfig');

            $popularCitiesName = $this->profileBuilderArray['PopularCities'];

            $popularCityNameString = '';
            foreach($popularCitiesName as $city)
            {
                if($popularCityNameString != '')
                    $popularCityNameString .= ",'".$city."'";
                else
                    $popularCityNameString = "'".$city."'";
            }

            $popularCities = $this->userprofilebuildermodel->getTagIdForDiffTags($popularCityNameString,'City');

            foreach($popularCities as $cityId=>$cityName){
                $result['popularCities'][] = array("id" => "$cityId", "label" => $cityName);
            }

            $allCities = $this->userprofilebuildermodel->getCities();

            $otherCities = array();
            $otherCities = array_diff($allCities,$popularCities);

            foreach($otherCities as $otherCityId=>$otherCityName){
                 $result['otherCities'][] = array("id" => "$otherCityId", "label" => $otherCityName);
            }

            $this->response->setBody($result);


            //Step 4: Return the Response
            $this->response->output();

        }



}      
?>
