<?php

class FBLeadResponseInterface extends MX_Controller{
	private $userStatus = 'false';

	function __constuct(){
	}

	private function _init(){
		$this->userStatus = $this->checkUserValidation();
		if(($this->userStatus == "false") || ($this->userStatus == "")){
			header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
		}

		if ($this->userStatus[0]['usergroup'] != 'cms') {
            header("location:/enterprise/Enterprise/unauthorizedEnt");
        }

	}

	private function getHeaderData(){
		$data['prodId'] = 1042;
		$data['validateuser'] = $this->userStatus;
		$this->load->library("enterprise_client");
		$entObj = new Enterprise_client();
        $data['headerTabs'] = $entObj->getHeaderTabs(1,$this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        return $data;
	}

	public function listingResponseMapping(){
		$this->_init();
		$data = $this->getHeaderData();	
	    $fbLeadResponseModel  = $this->load->model('enterprise/fbleadresponsemodel');

		$listing         = $fbLeadResponseModel->getFbLeadMapping();
		$data['listing'] = $listing;
		$this->load->view('enterprise/FBLeadResponseInterface/listingResponseMapping',$data);
	}

	public function responseMappingForm(){
		$this->_init();
		$data = $this->getHeaderData();	
		$this->load->view('enterprise/FBLeadResponseInterface/responseMappingForm',$data);
	}


	function getInstituteCourseCities(){
		$instituteId          = (int) $this->input->post("instituteId",true);
		$returnFormatType     = $this->input->post("returnFormatType",true);
		
		$instituteDetailLib   = $this->load->library('nationalInstitute/InstituteDetailLib');
		$institutedetailmodel = $this->load->model('nationalInstitute/institutedetailsmodel');
		
		// get all courses attached to given institute Id
		$courseList           = $instituteDetailLib->getAllCoursesForInstitutes($instituteId,'direct');
		$courseList           = $courseList['courseIds'];


		if(empty($courseList)){
			echo json_encode("institute_not_found");
			die;
		}

		if($returnFormatType == 'courseWiseLocation'){
			$data = $this->getCourseData($courseList,'',$returnFormatType);
			echo json_encode($data['courseData']);
			die;
		}else{
			$courseWiseLocations 		= $institutedetailmodel->getCoursesLocations($courseList);
			foreach ($courseWiseLocations as $courseId => $locationIds) {
				foreach ($locationIds as $key => $locationId) {			
					$locationWiseCourse[$locationId][] = $courseId;
				}
			}

			$locationsMappedToCourse    = array_values(array_keys($locationWiseCourse));

			if($instituteId > 0){
				$this->load->builder("nationalInstitute/InstituteBuilder");
				$instituteBuilder   = new InstituteBuilder();
				$instituteRepo      = $instituteBuilder->getInstituteRepository();
				$result             = $instituteRepo->find($instituteId,array('location'));
				$instituteIdFromObj = $result->getId();
				if(!empty($instituteIdFromObj)){
					$instituteLocations = $result->getLocations();
					$data               = array();
					foreach($instituteLocations as $key => $location){
						if(!in_array($location->getLocationId(), $locationsMappedToCourse))
							continue;

						$locationId                           = $location->getLocationId();						
						$data[$location->getCityId()]['city'] = $location->getCityName();						
						
						$res                                  = $this->getCourseData($locationWiseCourse[$locationId],$location->getCityId(),$returnFormatType);

						if(!empty($data[$location->getCityId()]['courses'])){
							$data[$location->getCityId()]['courses'] =	$data[$location->getCityId()]['courses'] + $res['courseData'] ;
						}else{
							$data[$location->getCityId()]['courses'] = $res['courseData'];							
						}
					}
					echo json_encode($data);					
				}else{
					echo "";	
				}
			}else{	
	            echo "";
	        }					
		}
	}

	public function getCourseData($courseList,$cityId,$returnFormatType){
		$fbLeadCommong   = $this->load->library('marketing/fbLead/FBLeadCommon');
		$this->load->library('user/UserLib');
        $userLib = new UserLib;

		$this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $courseRepo    = $courseBuilder->getCourseRepository();
        $courseObjects = $courseRepo->findMultiple($courseList,array("basic","eligibility","location"),true);

        $courseData    = array();
        $needToAskWorkEx                   = false;
        foreach ($courseObjects as $courseId => $course) {
			$courseData[$courseId]['id']                = $courseId;
			$courseData[$courseId]['course_name']       = $course->getName();
			$courseLocations                            = $course->getLocations();			
			$courseData[$courseId]['isMultiLoc']        = (count($courseLocations) > 1)?true:false;
			$courseAttribute = $fbLeadCommong->getCourseAttributes($courseId,$course);

            $hyperLocalData = $userLib->getHyperAndNonhyperCoursesCount(array($courseAttribute['baseCourse']));
                
            if ($hyperLocalData['hyperlocal'] > 0) {
            	$courseData[$courseId]['isHyperLocal']        = true;
            }else{
            	$courseData[$courseId]['isHyperLocal']        = false;
            }
			$localityMoreThanOneCheck = 0;
			foreach ($courseLocations as $key => $loc) {
				$courseData[$courseId]['courseLoc'][$loc->getCityId()]        = $loc->getCityName();
				$localityId = $loc->getLocalityId();
				if($returnFormatType == 'courseWiseLocation'){
					if(!empty($localityId)){
						$localityMoreThanOneCheck = $localityMoreThanOneCheck  + 1;
					}	
				}else{
					$courseObjectCityId = $loc->getCityId();
					if(($courseObjectCityId == $cityId) && !empty($localityId)){
						$localityMoreThanOneCheck = $localityMoreThanOneCheck  + 1;
					}					
				}
			}

			if($localityMoreThanOneCheck > 1){
				$courseData[$courseId]['isLocalityPresent'] = true;					
			}else{
				$courseData[$courseId]['isLocalityPresent'] = false;					
			}

			$isExecutive                                = $course->isExecutive();
			$courseTypeInfo                             = $course->getCourseTypeInformation();
			$streamId                                   = '';
            if(is_object($courseTypeInfo['entry_course'])){
                $courseHierarchies = $courseTypeInfo['entry_course']->getHierarchies();
                $credential        = $courseTypeInfo['entry_course']->getCredential();
                $level             = $courseTypeInfo['entry_course']->getCourseLevel();
                foreach ($courseHierarchies as $key => $value) {
                    if($value['primary_hierarchy'] == 1){
                    	$streamId = $value['stream_id'];                        
                    }
                }
            }

	        global $managementStreamMR;
	        global $postGrad;
	        global $certificateCredential;
	        if (!empty($isExecutive) || ($streamId == $managementStreamMR && $credential->getId() == $certificateCredential) || $level->getId() == $postGrad) {	            
	        	$needToAskWorkEx                   = true;
				$courseData[$courseId]['isWorkEx'] = true;	            
	        }else{
	        	$courseData[$courseId]['isWorkEx'] = false;
	        }        
        }

        return array('courseData' => $courseData,'needToAskWorkEx' => $needToAskWorkEx);
	}


	function submitData(){
		$fbLeadResponseModel  = $this->load->model('enterprise/fbleadresponsemodel');
		
		$courses              = $this->input->post("courses");	
		$formType             = $this->input->post("formType");	
		$instituteId          = (int) $this->input->post("instituteId");	
		$city                 = $this->input->post("city");	
		
		if($instituteId){			
			$data                 = array();
			$courseListString     = implode("|", $courses);
			$data['course_ids']   = $courseListString; 

			$this->load->builder("nationalCourse/CourseBuilder");
	        $courseBuilder = new CourseBuilder();
	        $courseRepo    = $courseBuilder->getCourseRepository();
	        $courseObjects = $courseRepo->findMultiple($courses,array("basic"),true);

	        foreach ($courseObjects as $courseId => $obj) {
	        	$instituteIdFrmObj = $obj->getInstituteId();	
	        	if(!empty($instituteIdFrmObj)){
	        		$data['institute_id'] = $instituteIdFrmObj; 		        		
	        		break;
	        	}	        	
	        }
			$data['fb_form_type'] = $formType; 

			if($formType == 'without_location'){
				$data['city_id'] = $city;
			}
			
			
			$data['created_on']   = date("Y-m-d H:i:s");
			// save data
			$response = $fbLeadResponseModel->insert_fb_response_mapping($data);

			if($response == true){
				echo  json_encode('data_successfully_inserted');
			}else{
				echo json_encode('failed');
			}						
		}else{
			echo json_encode('institute_not_found');
		}

		die;	
	}

	public function updateFbFormId(){
		$fbFormId              = $this->input->post("fbFormId");	
		$tableId               = $this->input->post("tableMainId");
		$description               = $this->input->post("description");

		$fbLeadResponseModel  = $this->load->model('enterprise/fbleadresponsemodel');
		//check formId already exist
		$isFormIdExist        = $fbLeadResponseModel->isFormIdExist($fbFormId,$tableId);

		if(!$isFormIdExist){
			$response             = $fbLeadResponseModel->updateFbFormId($fbFormId,$tableId,$description);

			if($response == true){
				echo  json_encode('data_successfully_updated');
			}else{
				echo json_encode('failed');
			}			
		}else{
			echo json_encode("form_id_exist");
		}
		die;
	}

	public function downloadCSV(){
		$tableId               = $this->input->post("tableMainId");	

		$fbLeadResponseModel  = $this->load->model('enterprise/fbleadresponsemodel');
		$data                 = $fbLeadResponseModel->getFBMappingById($tableId);

		$courseIdsString = $data['course_ids'];
		$instituteId     = $data['institute_id'];
		$courseList = array();
		if($courseIdsString){
			$courseList = explode("|", $courseIdsString);
		}

		$result     = $this->getCourseData($courseList,'',$data['fb_form_type']);

		$courseData              = $result['courseData'];
		$needToAskWorkExQuestion = $result['needToAskWorkEx'];	

		$csvData  = $this->_prepareCsvData($courseData,$data['fb_form_type'],$needToAskWorkExQuestion);

		header('Content-type: text/csv');
		header('Content-Disposition: attachment; filename="fbLeadMapping'.$instituteId.'.csv"');
		 
		// do not cache the file
		header('Pragma: no-cache');
		header('Expires: 0');

		$file = fopen('php://output', 'w');
		$fields = array();
		$fields[] = 'Course Name';
		if($data['fb_form_type'] == 'location'){
			$fields[] = 'Course Location';
		}
		if($needToAskWorkExQuestion){
			$fields[] = 'Work Experience';
		}

		fputcsv($file, $fields);
		fputcsv($file, array());
		
		// output each row of the data
		foreach ($csvData as $row){
		    fputcsv($file, $row);
		}
		
		fclose($file);
		exit();		
	}

	private function  _prepareCsvData($courseData,$fb_form_type,$needToAskWorkExQuestion){
		$workExFieldValues  = new \registration\libraries\FieldValueSources\WorkExperience;
        $workExList         = $workExFieldValues->getValues();

		$dataForCSV = array();
		foreach ($courseData as $courseId => $courseVal) {
			if($fb_form_type == 'without_location'){
				if($needToAskWorkExQuestion){
						if($courseVal['isWorkEx']){
							foreach ($workExList as $key => $workEx) {
								$tempArray       =  array();
								$tempArray[]     =  $courseVal['course_name'];
								$tempArray[]     =  $workEx;
								$dataForCSV[]    =  $tempArray;
							}						
						}else{
							$tempArray       =  array();
							$tempArray[]     =  $courseVal['course_name'];
							$tempArray[]     =  'N.A.';
							$dataForCSV[]    =  $tempArray;
						}
				}else{
						$tempArray       =  array();
						$tempArray[]     =  $courseVal['course_name'];
						$dataForCSV[]    =  $tempArray;
				}
			}else{
				foreach ($courseVal['courseLoc'] as $cityId => $cityName) {							
					if($needToAskWorkExQuestion){
						if($courseVal['isWorkEx']){
							foreach ($workExList as $key => $workEx) {
								$tempArray       =  array();
								$tempArray[]     =  $courseVal['course_name'];
								$tempArray[]     =  $cityName;
								$tempArray[]     =  $workEx;
								$dataForCSV[]    =  $tempArray;
							}						
						}else{
							$tempArray       =  array();
							$tempArray[]     =  $courseVal['course_name'];
							$tempArray[]     =  $cityName;
							$tempArray[]     =  'N.A.';
							$dataForCSV[]    =  $tempArray;
						}
					}else{
						$tempArray       =  array();
						$tempArray[]     =  $courseVal['course_name'];
						$tempArray[]     =  $cityName;
						$dataForCSV[]    =  $tempArray;
					}
				}				
			}
		}

		return $dataForCSV;
	}

	public function showFBExceptionList(){
		$this->_init();
		$data_for_view = $this->getHeaderData();

		$fb_lead_response_model  = $this->load->model('enterprise/fbleadresponsemodel');

		$total_leads_data = $fb_lead_response_model->getFBLeadsCount();

		foreach ($total_leads_data as $data) {
			$total_leads_count += $data['count'];

			if($data['status'] == 'response'){
				$total_response_count = $data['count'];
			}

			if($data['status'] == 'exception'){
				$total_exception_count = $data['count'];
			}
		}

		if($total_response_count<1){
			$total_response_count = 0;
		}

		if($total_exception_count<1){
			$total_exception_count = 0;
		}

		if($total_city_excpetion[0]['count']<1){
			$total_city_excpetion[0]['count'] = 0;
		}

		$total_city_excpetion = $fb_lead_response_model->getFBExceptionCityCount('city');

		$data_for_view['total_leads_count'] 		= $total_leads_count;
		$data_for_view['total_response_count']		= $total_response_count;
		$data_for_view['total_exception_count'] 	= $total_exception_count;
		$data_for_view['total_city_excpetion'] 		= $total_city_excpetion[0]['count'];

		$this->load->view('enterprise/FBLeadResponseInterface/fbExceptionList',$data_for_view);
	}

	public function showFBMapCity(){
		$this->_init();
		$data_for_view = $this->getHeaderData();

		$residence_city_lib = new  \registration\libraries\FieldValueSources\ResidenceCityLocality;
        $residence_city = $residence_city_lib->getValues(array('removeVirtualCities'=>true));

        $all_cities = array();
        foreach($residence_city['stateCities'] as $states_data) {
            foreach($states_data['cityMap'] as $city) {
            	$all_cities[$city['CityId']] = $city['CityName'];
            }
        }

        asort($all_cities);

        $fb_lead_response_model  = $this->load->model('enterprise/fbleadresponsemodel');
        $exceptions = $fb_lead_response_model->getExceptionsByType('city');

        foreach ($exceptions as $val) {
        	$exception_cities[$val['id']] =$val['old_value'];
        }


       	$data_for_view['all_cities'] = $all_cities;
       	$data_for_view['exception_cities'] = $exception_cities;
		$this->load->view('FBLeadResponseInterface/fbCityMapping',$data_for_view);	
	}

	public function updateCityExceptionMapping(){
		$this->_init();
		$fb_lead_response_model  = $this->load->model('enterprise/fbleadresponsemodel');
		
		$city_map              = $this->input->post("cityMap");	

		foreach ($city_map as $id => $value) {
			$temp_data = array('id'=>$id,'corrected_value'=>$value,'update_time' => date('Y-m-d H:i:s'));
			$data_model[] = $temp_data;
		}
		
		$response = $fb_lead_response_model->updateCityExceptionMapping($data_model);

		if($response){
			echo 'success';
		}else{
			echo 'error';
		}
	
		return;
	}
}