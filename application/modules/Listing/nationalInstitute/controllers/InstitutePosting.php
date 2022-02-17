<?php

class InstitutePosting extends MX_Controller{

	function __construct(){
		$validity = $this->checkUserValidation();
		if(($validity == "false" )||($validity == "")) {
		    header('location:/enterprise/Enterprise/loginEnterprise');
		    exit();
		}
		else{
			$usergroup = $validity[0]['usergroup'];
			if($usergroup !="cms" && $usergroup !="listingAdmin")
			{
			    header("location:/enterprise/Enterprise/unauthorizedEnt");
			    exit();
			}

			$currentUrl = getCurrentPageURL();
			$featuredArticleUrl = SHIKSHA_HOME.'/nationalInstitute/InstitutePosting/cmsFeaturedArticle';
			$popularCourseUrl = SHIKSHA_HOME.'/nationalInstitute/InstitutePosting/cmsPopularCourses';

			if($usergroup =="listingAdmin" &&  ($currentUrl == $featuredArticleUrl || $currentUrl == $popularCourseUrl)){
				header("location:/enterprise/Enterprise/unauthorizedEnt");
			    exit();
			}

			$this->userId = $validity[0]['userid'];
			// _p($this->userId); die;
		}
	}


	public function init(){
		$this->load->config('nationalInstitute/instituteStaticAttributeConfig');
		$this->load->config('nationalInstitute/instituteSectionConfig');
		$this->institutePostingLib = $this->load->library('nationalInstitute/InstitutePostingLib');
		$this->institutepostingmodel = $this->load->model('nationalInstitute/institutepostingmodel');
        $this->load->library('Tagging/TaggingCMSLib');
        $this->taggingCMSLib = new TaggingCMSLib();
        $this->nationalinstitutecache = $this->load->library('nationalInstitute/cache/NationalInstituteCache');  
        $this->instituteFlatTableLib = $this->load->library('nationalInstitute/InstituteFlatTableLibrary');
	}

	private function _initUploadClient() {
		if(!$this->UploadClient) {
			$this->load->library('upload_client');
			$this->UploadClient = new Upload_client();
		}
		// return $this->UploadClient;
	}

	public function index(){

		$this->load->view('enterprise/adminBase/adminLayout');
	}

	//public function create(){
		//$this->load->view('enterprise/adminBase/adminLayout');
	//}

	/**
	 * LF-4524
	 * Format:
	 * data:
	 *	'static_data':
	 *		'ownership':[{'name':'Private','value':'private'}],
	 *		'institute_type':same as ownership,
	 *		'student_type':same as ownership,
	 *		'creditation':same as ownership,
	 *		'faculty_type':same as ownership,
	 *		'event_type':same as ownership,
	 *		'tag_list':same as ownership,
	 *		'companies':same as ownership,
	 *		'facilities':[
	 *			{
	 *				'id':id,
	 *				'name':'name',
	 *				'display_type':yesno/select,
	 *				'select_values':same as ownership,
	 *				'order':order,
	 *				'ask_desc':desc,
	 *				'children':facilities
	 *			},
	 *		]
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2016-07-11
	 * @return json
	 */
	public function getStaticAttribute(){
		$this->init();
		
		$staticAttribute                              = array();
		$instituteStaticArribute                      = $this->config->item('static_data');
		$staticAttribute['static_data']               = $instituteStaticArribute;

		$dynamicAttributeList = $this->institutePostingLib->prepareDynamicAttribute();
		if(is_array($dynamicAttributeList)) {
			$staticAttribute['static_data'] = array_merge($staticAttribute['static_data'],$dynamicAttributeList);		
		}

		//$staticAttribute['static_data']['companies'] = $this->institutePostingLib->getCompaniesData();
		$staticAttribute['static_data']['tag_list'] = $this->institutePostingLib->getListingTagsData();
		$staticAttribute['static_data']['facilities'] = $this->institutePostingLib->getFacilitiesConfigData();/*_p($staticAttribute['static_data']['facilities']);die;*/
		$staticAttribute['static_data']['media_server_domain'] = MEDIA_SERVER;

		$data                                         = array('data' => $staticAttribute);
		echo json_encode($data);die;
	}

	/**
	 *	'locations':
	 * 	  'state_id':
	 *		'id':state_id,
	 *		'name':name,
	 *		cities:
	 *			city_id:
	 *				'id':id,
	 *				'name':name,
	 *				localities:
	 *					locality_id:
	 *						id:id,
	 *						'name':name
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2016-07-20
	 * @return locations
	 */
	public function getLocationTree(){
		$locations['locations']                 = $this->prepareLocationHierarchy();
		$data                      = array('data' => $locations);
		echo json_encode($data);die;	
	}

	public function prepareLocationHierarchy(){
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder();
		$locationRepository = $locationBuilder->getLocationRepository();		

		$hieCache = $locationRepository->getHierarchy();
		if($hieCache){
			return $hieCache;
		}

		$locationData = array();
		$nationalState = $locationRepository->getStatesByCountry(2);
		foreach ($nationalState as $key => $stateObj) {
			$locationData[$stateObj->getId()]['id'] = $stateObj->getId();
			$locationData[$stateObj->getId()]['name'] = $stateObj->getName();
			$citiesData = $locationRepository->getCitiesByState($stateObj->getId());
			foreach ($citiesData as $key => $cityObj) {
				$locationData[$stateObj->getId()]['cities'][$cityObj->getId()]['id'] = $cityObj->getId();
				$locationData[$stateObj->getId()]['cities'][$cityObj->getId()]['name'] = $cityObj->getName();
				$localitiesData = $locationRepository->getLocalitiesByCity($cityObj->getId());
				foreach ($localitiesData as $key => $localitiesObj) {
					if($localitiesObj){	
						$locationData[$stateObj->getId()]['cities'][$cityObj->getId()]['localities'][$localitiesObj->getId()]['id'] = $localitiesObj->getId();
						$locationData[$stateObj->getId()]['cities'][$cityObj->getId()]['localities'][$localitiesObj->getId()]['name'] = $localitiesObj->getName();
					}else{
						$locationData[$stateObj->getId()]['cities'][$cityObj->getId()]['localities'] =  '';	
					}
					
				}
			}		
		}

		$locationRepository->storeHierarchy($locationData);

		return $locationData;
	}

	function getParentInstituteSuggestions($limit = 10) {
		//$keyword = 'a';
		$keyword = $this->input->post('text');
		$suggestionType = !empty($_POST['suggestionType']) ? strtolower($this->input->post('suggestionType')) : 'all';
		$statusCheck = isset($_POST['statusCheck'])?$this->input->post('statusCheck'):false;
		$keyword = trim($keyword);
		if(empty($keyword)) {
			return '';
		}
		$ListingAutosuggestorLib = $this->load->library('listingCommon/ListingAutosuggestorLib');
		$suggestions = $ListingAutosuggestorLib->getSuggestions($keyword, $limit, $suggestionType,$statusCheck);
		//_p($suggestions);
		echo json_encode($suggestions);
	}

	function getCompaniesSuggestion(){
		$this->init();
		$keyword = $this->input->post('text');
		$keyword = trim($keyword);
		if(empty($keyword)) {
			return '';
		}

		$suggestions = $this->institutePostingLib->getCompaniesData($keyword);
		echo json_encode($suggestions); 
	}

	public function saveInstitute($request_body = ""){
		$this->init();
		if($request_body == ""){
		    $request_body = file_get_contents('php://input');
		}
		$data = json_decode($request_body,true);

		$instituteObj = $data['instituteObj'];
		$oldInstituteObj = $data['oldInstituteState'];
		$sectionsUpdated = $this->institutePostingLib->checkForInstituteDataUpdated($instituteObj, $oldInstituteObj);
		$instituteData = $this->formatInstituteData($instituteObj);
		$instituteData['sectionsUpdated'] = $sectionsUpdated;
		
		$listingType = strtolower($instituteObj['postingListingType']);
		$listingIds = array('listingId'=>$instituteObj['institute_id']);
		if($instituteObj['savingMode'] == "edit" && $instituteObj['statusFlag'] == 'live'){
			$liveListingData = $this->institutepostingmodel->getLiveListingData($instituteObj['institute_id'],$listingType);
		}
	
		$tagEntityType = "";
        if($instituteObj['postingListingType'] == "Institute"){
                $tagEntityType = "institute";
        }else{
                $tagEntityType = "University";
        }

        // Tags CMS Call
		if($instituteObj['savingMode'] == 'edit' && $instituteObj['statusFlag'] == 'live') {
			if($tagEntityType == 'University')
    		{
    			$tagEntityType = 'National-University';
    		}
			$this->taggingCMSLib->addTagsPendingMappingAction($tagEntityType,$instituteObj['institute_id'],'Edit',array('newName' => $instituteObj['name']));	
		}
	
		$returnData = $this->institutepostingmodel->saveInstitute($instituteData);
		
		if(($instituteData['statusFlag'] == 'live' || $instituteData['statusFlag'] == 'draft') && $instituteData['listingMainInfo']['listing_seo_url'] != ''){
			$updated_seo_url =$instituteData['listingMainInfo']['listing_seo_url'].'-'.$returnData;
			$this->institutepostingmodel->updateSeoUrl($returnData,$updated_seo_url,strtolower($instituteObj['postingListingType']));
		}

		if($instituteObj['savingMode'] == 'edit')
		{
			$this->removeLockListingForm($returnData,$instituteObj["postingListingType"]);

			// invalidate institute cache
    		$this->nationalinstitutecache->removeInstitutesCache(array($returnData));
    		// invalidate its primary-courses cache
    		$this->invalidateInstituteCoursesCache(array($returnData));
		}
		// Tags CMS CALL
        if($instituteObj['savingMode'] == "add" && $instituteObj['statusFlag'] == 'live'){
                // Add
        		if($tagEntityType == 'University')
        		{
        			$tagEntityType = 'National-University';
        		}
                $this->taggingCMSLib->addTagsPendingMappingAction($tagEntityType,$returnData,'Add');
        }

        if(is_array($returnData) && $returnData['error'])
			echo json_encode(array('data'=>array('message'=>$returnData['errorMsg'],'status'=>'error')));
		else{

			//Insert an entry in indexLog table
			if($instituteObj['savingMode'] == "add" && $instituteObj['statusFlag'] == 'live'){
				$this->institutepostingmodel->updateIndexLog(array('listingId'=>$returnData),$listingType,'index');
				$this->instituteFlatTableLib->flatTableInstituteUpdate($returnData);
			}else if($instituteObj['savingMode'] == "edit" && $instituteObj['statusFlag'] == 'live'){				
				if(!empty($liveListingData)){
					
					 if($liveListingData['is_satellite'] != $instituteData['basicInfo']['is_satellite']){
					 	 $this->instituteFlatTableLib->updateIsSatellite($returnData, $instituteData['basicInfo']['is_satellite']);
					 }

					 if($liveListingData['is_autonomous'] != $instituteObj['is_autonomous'] || $liveListingData['is_open_university'] != $instituteObj['is_open_university'] || $liveListingData['university_specification_type'] != $instituteObj['university_type'] || $liveListingData['is_ugc_approved'] != $instituteObj['is_ugc_approved'] || $liveListingData['is_aiu_membership'] != $instituteObj['is_aiu_membership'] || $liveListingData['ownership'] != $instituteObj['ownership'] || $liveListingData['accreditation'] != $instituteObj['accreditation'] ||($liveListingData['parent_listing_id'] != $instituteObj['parent_entity_id']) || $liveListingData['is_satellite'] != $instituteData['basicInfo']['is_satellite']){
						
						$this->institutepostingmodel->updateIndexLog($listingIds,$listingType,'index','universityTypeChange');
						if($liveListingData['parent_listing_id'] != $instituteObj['parent_entity_id']){
							$this->instituteFlatTableLib->flatTableInstituteUpdate($returnData);
						}

					}
					$this->institutepostingmodel->updateIndexLog($listingIds,$listingType,'index','listingOnly');
				}else{
					
					$this->institutepostingmodel->updateIndexLog($listingIds,$listingType,'index');
				}
				
			}

			//if listing added successfully 
			if(!($instituteData['is_dummy'] == 'true'))
	        {
				$oldlocations = $this->_processInstituteLocations($oldInstituteObj, $this->userId);
				$updateGoogleUrlForListing = array();
				$this->logListingForGoogleMap($instituteData['locations'],$oldlocations,$instituteObj['savingMode'],$updateGoogleUrlForListing,$returnData,$instituteData['statusFlag']);
				if(!empty($updateGoogleUrlForListing) && count($updateGoogleUrlForListing) > 0)
				{
					$this->institutepostingmodel->updateGoogleStaticMapForListing($updateGoogleUrlForListing,$instituteData['statusFlag']);
				}
				//$this->invalidateNginxURLsIntoQueue($oldInstituteObj['seo_url']);
	        }
	        if($instituteObj['savingMode'] == 'edit' && !($instituteData['is_dummy'] == 'true') && !empty($returnData) && $instituteObj['statusFlag'] == 'live')
	        {

    			global $forceListingWriteHandle;
		        $forceListingWriteHandle = true;
	        	$this->load->builder("nationalInstitute/InstituteBuilder");
    			$instituteBuilder = new InstituteBuilder();
    			$this->instituteRepo = $instituteBuilder->getInstituteRepository();
	        	$instituteObjUrl = $this->instituteRepo->find($returnData,array('basic'));
        		if(!empty($instituteObjUrl))
        		{
        			$ampURL = $instituteObjUrl->getAmpURL();
        			if(!empty($ampURL))
        			{
        				updateGoogleCDNcacheForAMP($ampURL);
        				error_log('updated Google CDN Cache For Institute/University ='.$returnData);
        			}	
        		}
	        }
			echo json_encode(array('data'=>array('institute_id'=>$returnData,'message'=>'Added successfully with '.$instituteObj["postingListingType"].' Id: '.$returnData,'status'=>'success')));
		}	
			
	}

	private function formatInstituteData($instituteData){
		$returnData = array();
		$returnData['flow'] = $instituteData['savingMode'];
		$returnData['postingListingType'] = $instituteData['postingListingType'] == 'University' ? 'university' : 'institute';

		if($returnData['flow'] == 'edit'){
			$returnData['instituteId'] = $instituteData['institute_id'];	
		}
		$returnData['userId'] = $this->userId;

		$basicInfo = array();
		$basicInfo['name'] = trim($instituteData['name']);
		$basicInfo['short_name'] = trim($instituteData['short_name']);
		$basicInfo['about_college'] = !empty($instituteData['about_college']) ? $instituteData['about_college'] : null;
		$basicInfo['institute_specification_type'] = $instituteData['postingListingType'] == 'Institute' ? $instituteData['institute_type'] : NULL;
		$basicInfo['university_specification_type'] = $instituteData['postingListingType'] == 'University' ? $instituteData['university_type'] : NULL;
		$basicInfo['listing_type'] = $returnData['postingListingType'];
		$basicInfo['is_dummy'] = ($instituteData['is_dummy'] == 'false') ? false : true;

		$synonyms = array();
		foreach ($instituteData['synonyms'] as $synonym) {
			$synonyms[] = $synonym['value'];
		}
		$synonyms = array_map('trim',$synonyms);
		$synonyms = array_filter($synonyms,'strlen');
		
		if(!empty($synonyms)){
			$basicInfo['synonym'] = implode(';', $synonyms);
		}

		$basicInfo['parent_listing_id'] = empty($instituteData['parent_entity_id']) ? NULL : $instituteData['parent_entity_id'];
		$basicInfo['parent_listing_type'] = empty($instituteData['parent_entity_type']) ? NULL : $instituteData['parent_entity_type'];
		$basicInfo['primary_listing_id'] = empty($instituteData['primary_listing_id']) ? NULL : $instituteData['primary_listing_id'];
		$basicInfo['primary_listing_type'] = empty($instituteData['primary_listing_type']) ? NULL : $instituteData['primary_listing_type'];
		
		if(!empty($instituteData['establishment_year'])){			
			$basicInfo['establish_year'] = $instituteData['establishment_year'];
		}

		if(!empty($instituteData['establish_university_year']))
		{
			$basicInfo['establish_university_year'] = $instituteData['establish_university_year'];
		}

		$basicInfo['ownership']              = !empty($instituteData['ownership']) ? $instituteData['ownership'] : NULL;
		$basicInfo['student_type']           = (!empty($instituteData['students_type']) && !$basicInfo['is_dummy']) ? $instituteData['students_type'] : NULL;
		$basicInfo['abbreviation']           = !empty($instituteData['abbreviation']) ? trim($instituteData['abbreviation']) : NULL;
		$basicInfo['accreditation']          = !empty($instituteData['accreditation']) ? $instituteData['accreditation'] : NULL;
		$basicInfo['is_autonomous']          = ($instituteData['is_autonomous'] >=0) ? $instituteData['is_autonomous'] : NULL;
		$basicInfo['is_national_importance'] = ($instituteData['is_national_importance'] >=0) ? $instituteData['is_national_importance'] : NULL;
		$basicInfo['is_placement_page_exists'] = (!empty($instituteData['is_placement_page_exists']) && ($instituteData['is_placement_page_exists'] >=0)) ? $instituteData['is_placement_page_exists'] : 0;
		$basicInfo['is_flagship_course_placement_data_exists'] = (!empty($instituteData['is_flagship_course_placement_data_exists']) && ($instituteData['is_flagship_course_placement_data_exists'] >=0 )) ? $instituteData['is_flagship_course_placement_data_exists'] : 0;
		$basicInfo['is_naukri_placement_data_exists'] = (!empty($instituteData['is_naukri_placement_data_exists']) && ($instituteData['is_naukri_placement_data_exists'] >=0)) ? $instituteData['is_naukri_placement_data_exists'] : 0;
		$basicInfo['is_cutoff_page_exists'] = (!empty($instituteData['is_cutoff_page_exists']) && ($instituteData['is_cutoff_page_exists'] >=0)) ? $instituteData['is_cutoff_page_exists'] : 0;
		$basicInfo['is_review_page_exists'] = (!empty($instituteData['is_review_page_exists']) && ($instituteData['is_review_page_exists'] >=0)) ? $instituteData['is_review_page_exists'] : 0;
		$basicInfo['is_all_course_page_exists'] = (!empty($instituteData['is_all_course_page_exists']) && ($instituteData['is_all_course_page_exists'] >=0)) ? $instituteData['is_all_course_page_exists'] : 0;
		$basicInfo['is_admission_page_exists'] = (!empty($instituteData['is_admission_page_exists']) && ($instituteData['is_admission_page_exists'] >=0)) ? $instituteData['is_admission_page_exists'] : 0;
		$basicInfo['cutoff_page_exam_name'] = !empty($instituteData['cutoff_page_exam_name']) ? $instituteData['cutoff_page_exam_name'] : NULL;
		$basicInfo['is_satellite']           = (!empty($instituteData['is_satellite_entity']) && !empty($instituteData['parent_entity_id'])) ? $instituteData['is_satellite_entity'] : NULL;

		$basicInfo['disabled_url']           = (!empty($instituteData['disabled_url'])) ? $instituteData['disabled_url'] : NULL;
		
		//university case 

		$basicInfo['is_ugc_approved']        = ($instituteData['is_ugc_approved'] >=0 && $instituteData['is_dummy'] == 'false' && $returnData['postingListingType'] == 'university' )? $instituteData['is_ugc_approved'] : NULL;
		$basicInfo['is_open_university']     = ($instituteData['is_open_university'] >=0 && $instituteData['is_dummy'] == 'false' && $returnData['postingListingType'] == 'university' ) ? $instituteData['is_open_university'] : NULL;
		$basicInfo['is_aiu_membership']      = ( $instituteData['is_aiu_membership'] >=0 && $instituteData['is_dummy'] == 'false' && $returnData['postingListingType'] == 'university' ) ? $instituteData['is_aiu_membership'] : NULL;

		$basicInfo['logo_url'] 				 = !empty($instituteData['logo_url']) ? $instituteData['logo_url']: NULL;
		
		$basicInfo['created_on'] 			 = empty($instituteData['created_on']) ? date("Y-m-d H:i:s") : $instituteData['created_on'];
		$basicInfo['created_by'] 			 = empty($instituteData['created_by']) ? $this->userId : $instituteData['created_by'];

				
		$returnData['basicInfo'] 			 = $basicInfo;

        if(!($instituteData['is_dummy'] == 'true' && $returnData['postingListingType'] == 'university'))
        {
			$returnData['locations'] 	 		 = $this->_processInstituteLocations($instituteData, $this->userId);
        }

        //get city and locality name for SEO
        $model = $this->load->model('nationalInstitute/institutepostingmodel');
        $cityName = $model->getCityName($instituteData['main_location']['city_id']);

        if(!empty($instituteData['main_location']['locality_id'])){
        	$localityName = $model->getLocalityName($instituteData['main_location']['locality_id']);
        }
	        
        //SEO details
        $listingName = seo_url($basicInfo['name'],"-","200",true);
        if(stripos($basicInfo['name'], $cityName) === FALSE && $cityName != ''){
        	$cityAppend = '-'.seo_url($cityName,"-","150",true);
        	$city = ', '.$cityName;
        }
        if(isset($localityName) && $localityName != ''){
        	$localityAppend = '-'.seo_url($localityName,"-","150",true);
        	$locality = ', '.$localityName;
        }

        $seoUrl= '';
        if(!$basicInfo['is_dummy']){
        	if($returnData['postingListingType'] == 'university'){
        		$seoUrl = '/university/'.$listingName.$cityAppend;
	        }else{
	        	$seoUrl = '/college/'.$listingName.$localityAppend.$cityAppend;
	        }   

        }
             


        $listingMainInfo['editedBy'] = $this->userId;

		$listingMainInfo['listing_title']           = trim($instituteData['name']);
		$listingMainInfo['listing_seo_url']         = $seoUrl;
		$listingMainInfo['listing_seo_description'] = !empty($instituteData['seo_description']) ? $instituteData['seo_description'] : $seoDescription;
		$listingMainInfo['listing_seo_title']       = !empty($instituteData['seo_title']) ? $instituteData['seo_title'] : $seoTitle;
		$listingMainInfo['listing_type']            = $returnData['postingListingType'].($returnData['postingListingType'] == 'university' ?'_national': '');

		//listings main table data
		if($returnData['flow'] == 'add'){
        	$listingMainInfo['username'] = $this->userId;
        }else{
			//get current institute data from listings_main 
			$currentListingMainData = $this->institutepostingmodel->getCurrentInstituteData($instituteData['institute_id'],$listingMainInfo['listing_type']);
			$listingMainInfo['submit_date'] = !empty($currentListingMainData['submit_date']) ? $currentListingMainData['submit_date']:date("Y-m-d H:i:s");
			$listingMainInfo['username']    = !empty($currentListingMainData['username']) ? $currentListingMainData['username'] : $this->userId;
        }
        $returnData['listingMainInfo'] 			 = $listingMainInfo;

		$returnData['institutePhotos'] 		 = $this->_processInstituteMedia($instituteData, $this->userId, 'photos');
		$returnData['instituteVideos'] 		 = $this->_processInstituteMedia($instituteData, $this->userId, 'videos');
		// _p($returnData['instituteVideos']); die;
		
		$returnData['brochure_url'] = $instituteData['brochure_url'];
		$returnData['brochure_year'] = (!empty($instituteData['brochure_year']) && !$basicInfo['is_dummy']) ? $instituteData['brochure_year'] : NULL;
		$returnData['brochure_size'] = $instituteData['brochure_size'];
		$academicStaff = array();
		$indexPosition = 0;
		foreach($instituteData['academic_staff'] as $staff){
			if(!empty($staff['name']) && !empty($staff['current_designation'])){
				$indexPosition++;
				$temp = array();
				$temp['name'] 						= trim($staff['name']);
				$temp['type_id'] 					= empty($staff['type']) ? NULL :$staff['type'];
				$temp['current_designation'] 		= empty($staff['current_designation']) ? NULL : trim($staff['current_designation']);
				$temp['education_background'] 		= empty($staff['education_background']) ? NULL : trim($staff['education_background']);
				$temp['professional_highlights'] 	= empty($staff['highlights']) ? NULL : trim($staff['highlights']);
				$temp['display_order'] 				= $indexPosition;
				$temp['updated_by'] 				= $this->userId;

				$academicStaff[] = $temp;
			}
		}
		$returnData['academicStaff'] = $academicStaff;

		//store academic staff faculty highlights details
		$academicStaff_faculty_highlights = array();
		$instituteData['staff_faculty_highlights'] = trim($instituteData['staff_faculty_highlights']);
		if(!empty($instituteData['staff_faculty_highlights']))
		{
			 $academicStaff_faculty_highlights['updated_by'] = $this->userId;
             $academicStaff_faculty_highlights['description'] = trim($instituteData['staff_faculty_highlights']);
             $academicStaff_faculty_highlights['description_type'] = 'faculty_highlights';
		}

		$returnData['academicStaff_faculty_highlights'] = $academicStaff_faculty_highlights;

		$researchProjects = array();
		foreach ($instituteData['research_projects'] as $key => $research) {
			$validResearch = trim($research['value']);
			if(!empty($validResearch)){
				$temp = array();
				$temp['description']      = trim($research['value']);
				$temp['description_type'] = 'research_project';
				$temp['updated_by'] 	  = $this->userId;

				$researchProjects[] = $temp;
			}
		}
		$returnData['researchProjects'] = $researchProjects;


		$usp = array();
		foreach ($instituteData['usp'] as $key => $val) {
			$validUsp = trim($val['value']);
			if(!empty($validUsp)){
				$temp = array();
				$temp['description']      = trim($val['value']);
				$temp['description_type'] = 'usp';
				$temp['updated_by'] 	  = $this->userId;

				$usp[] = $temp;
			}
		}
		$returnData['usp'] = $usp;


		$events = array();
		$indexPosition = 0;
		$eventIdentifierMapping = array();
		foreach ($instituteData['events'] as $key => $val) {
			if(!empty($val['name']) && !empty($val['type'])){
				$indexPosition++;
				$temp                     = array();
				$temp['event_type_id']    = $val['type'];
				$temp['name']             = trim($val['name']);
				$temp['description']      = trim($val['description']);
				$temp['position']         = $indexPosition;
				$temp['randomIdentifier'] = $val['randomIdentifier'];

				$events[] = $temp;
			}
		}
		$returnData['events'] = $events;

		$scholarships = array();
		foreach ($instituteData['scholarships'] as $key => $val) {
			$desc = trim($val['description']);
			if(!empty($val['type']) && !empty($desc)){
				$temp = array();
				$temp['scholarship_type_id'] = $val['type'];
				$temp['description']         = $desc;

				$scholarships[] = $temp;
			}
		}
		$returnData['scholarships'] = $scholarships;

		$recruitingCompanies = array();
		foreach ($instituteData['companies'] as $key => $val) {
			if(!empty($val['company_id'])){
				$temp = array();
				$temp['company_id']    = $val['company_id'];
				$temp['order'] 			  = $val['position'];
				$temp['updated_by'] 	  = $this->userId;

				$recruitingCompanies[] = $temp;
			}
		}
		$returnData['companies'] = $recruitingCompanies;
		$returnData['facilities'] = $this->formatInstituteFacilities($instituteData['facilities']);
		$returnData['posting_comments'] = $instituteData['posting_comments'];
		$returnData['statusFlag'] = $instituteData['statusFlag'];

		return $returnData;
	}

	private function formatInstituteFacilities($data){
		$insertData = array();$insertMappings = array();
		foreach ($data as $facilityId => $facility) {
			if($facility['is_present'] == 'yes' || $facility['display_type'] == 'none'){
				$temp = array();
				if($facility['display_type'] != 'none'){

					$temp['facility_id'] 		= $facilityId;
					$temp['has_facility'] 		= 1;
					$temp['description'] 		= empty($facility['description']) ? NULL : trim($facility['description']);
					$temp['additional_info'] 	= empty($facility['additional_info']) ? NULL : trim($facility['additional_info']);
					$temp['updated_by'] 	  	= $this->userId;

					$insertData[] = $temp;
				}

				foreach($facility['child_facilities'] as $childId => $child){

					if($child['is_present'] == 'yes'){
						$temp = array();$custom_fields = array();

						$temp['facility_id'] 	= $childId;
						$temp['has_facility'] 	= 1;
						$temp['description'] 	= empty($child['description']) ? NULL : trim($child['description']);
						$temp['updated_by'] 	= $this->userId;

						foreach ($child['custom_fields'] as $custom_field) {
							if(!empty($custom_field['value'])){
								$custom_fields[] = array('name' => trim($custom_field['name']),'value'=>$custom_field['value']);
							}
						}
						if(!empty($custom_fields)){
							$temp['additional_info'] = json_encode($custom_fields);
						}
						else{
							$temp['additional_info'] = NULL;
						}

						$insertData[] = $temp;
					}
					else if($child['is_present'] == 'no'){
						$temp = array();
						$temp['facility_id'] 	= $childId;
						$temp['has_facility'] 	= 0;
						$temp['description'] 	= NULL;
						$temp['additional_info'] 	= NULL;
						$temp['updated_by'] 	= $this->userId;

						$insertData[] = $temp;
					}

					if(!empty($child['values'])){
						foreach($child['values'] as $value){
							$temp = array();
							$temp['facility_id'] 	= $childId;
							$temp['value_id'] 		= $value;
							$temp['custom_name'] 	= NULL;
							$temp['updated_by'] 	= $this->userId;

							$insertMappings[] 		= $temp;
						}
					}

					if(!empty($child['other_fields'])){
						foreach ($child['other_fields'] as $field) {
							$temp = array();
							if(!empty($field['value'])){
								$temp['facility_id'] 	= $childId;
								$temp['value_id'] 		= NULL;
								$temp['custom_name'] 	= trim($field['value']);
								$temp['updated_by'] 	= $this->userId;

								$insertMappings[] 		= $temp;
							}
						}
					}
				}
			}
			else if($facility['is_present'] == 'no'){
				$temp = array();

				$temp['facility_id'] 		= $facilityId;
				$temp['has_facility'] 		= 0;
				$temp['description'] 		= NULL;
				$temp['additional_info'] 	= NULL;
				$temp['updated_by'] 	  	= $this->userId;

				$insertData[] = $temp;
			}
		}
		return array('facilityData' => $insertData,'facilityMappings' => $insertMappings);
	}

	public function getInstituteData($instituteId,$is_dummy='',$postingListingType = 'institute'){
		$this->init();
		$model = $this->load->model('nationalInstitute/institutedetailsmodel');
		if(empty($instituteId)){
			return false;
		}
		
		$instituteData = array();

		$postingListingType = strtolower($postingListingType);

		$data = $model->getInstituteData($instituteId,$postingListingType);
		$basicInfo = $data['basic_info'];

		if($data['basic_info']['listing_type'] != strtolower($postingListingType)){
                echo json_encode(array("data"=>"NO_SUCH_LISTING_FOUND_IN_DB"));exit(0);
        }

		$instituteData['institute_id']           = $basicInfo['listing_id'];
		$instituteData['name']                   = $basicInfo['name'];
		$instituteData['institute_type']         = $basicInfo['institute_specification_type'];
		$instituteData['parent_entity_id']       = $basicInfo['parent_listing_id'];
		$instituteData['parent_entity_type']     = $basicInfo['parent_listing_type'];
		$instituteData['primary_listing_id']      = $basicInfo['primary_listing_id'];
		$instituteData['primary_listing_type']    = $basicInfo['primary_listing_type'];
		$instituteData['short_name']           = $basicInfo['short_name'];
		$instituteData['about_college']           = $basicInfo['about_college'];
		$instituteData['abbreviation']           = $basicInfo['abbreviation'];
		$instituteData['ownership']              = $basicInfo['ownership'];
		$instituteData['establishment_year']     = $basicInfo['establish_year'];
		$instituteData['is_satellite_entity']    = !empty($basicInfo['is_satellite'])?true:false;
		$instituteData['is_autonomous']          = $basicInfo['is_autonomous'];
		$instituteData['students_type']          = $basicInfo['student_type'];
		$instituteData['logo_url']               = $basicInfo['logo_url'];
		$instituteData['brochure_url']           = $basicInfo['brochure_url'];
		$instituteData['brochure_year']          = $basicInfo['brochure_year'];
		$instituteData['brochure_size']          = $basicInfo['brochure_size'];
		$instituteData['accreditation']          = $basicInfo['accreditation'] == NULL ? '' : $basicInfo['accreditation'];
		$instituteData['is_national_importance'] = $basicInfo['is_national_importance'];
		$instituteData['is_placement_page_exists'] = ($basicInfo['is_placement_page_exists'] > 0) ? true : false;
		$instituteData['is_flagship_course_placement_data_exists'] = ($basicInfo['is_flagship_course_placement_data_exists'] > 0) ? true : false;
		$instituteData['is_naukri_placement_data_exists'] = ($basicInfo['is_naukri_placement_data_exists'] > 0) ? true : false;
		$instituteData['is_cutoff_page_exists'] = ($basicInfo['is_cutoff_page_exists'] > 0) ? true : false;
		$instituteData['is_review_page_exists'] = ($basicInfo['is_review_page_exists'] > 0) ? true : false;
		$instituteData['is_all_course_page_exists'] = ($basicInfo['is_all_course_page_exists'] > 0) ? true : false;
		$instituteData['is_admission_page_exists'] = ($basicInfo['is_admission_page_exists'] > 0) ? true : false;
		$instituteData['cutoff_page_exam_name'] = $basicInfo['cutoff_page_exam_name'];
		$instituteData['disabled_url']           = $basicInfo['disabled_url'];
		$instituteData['created_on']           = $basicInfo['created_on'];
		$instituteData['created_by']           = $basicInfo['created_by'];
		//university extra Details
		$instituteData['establish_university_year'] = $basicInfo['establish_university_year'];

		$instituteData['is_ugc_approved']			= !empty($basicInfo['is_ugc_approved'])?true:false;
		$instituteData['is_aiu_membership']			= !empty($basicInfo['is_aiu_membership'])?true:false;
		$instituteData['is_open_university']		= !empty($basicInfo['is_open_university'])?true:false;
		$instituteData['university_type']			= $basicInfo['university_specification_type'];

		$instituteData['seo_url']			= $data['listing_seo_url'];

		$synonyms = explode(';',$basicInfo['synonym']);
		foreach ($synonyms as $synonym) {
			$instituteData['synonyms'][] = array('value'=>$synonym);
		}

		//getting selected hieraichy for institute
		$parentHierarichyDetails = array();
		if(!empty($basicInfo['parent_listing_id']))
		{
			$parentHierarichyDetails = $this->institutePostingLib->getParentHierarchyById($basicInfo['parent_listing_id'],$basicInfo['parent_listing_type']);
		}

		$instituteData['parentHierarichyDetails'] = $parentHierarichyDetails;

		$instituteData['is_dummy'] = 'true';
		if(empty($basicInfo['is_dummy'])){
			$instituteData['is_dummy'] = 'false';
		}
		elseif(!empty($basicInfo['is_dummy']) && $is_dummy == 'false')
		{
			//this line is used for convert to live from dummy status
			$instituteData['is_dummy'] = 'false';
		}

		$academicStaff = array();
		foreach ($data['academic_staff'] as $staff) {
			$academicStaff[] = array('type'=>$staff['type_id'],'name'=>$staff['name'],'current_designation'=>$staff['current_designation'],'education_background'=>$staff['education_background'],'highlights'=>$staff['professional_highlights'],'position'=>$staff['display_order']);
		}
		$instituteData['academic_staff'] = $academicStaff;

		$academicStaff_faculty_highlights = '';
		if(!empty($data['academicStaff_faculty_highlights']))
		{
			$academicStaff_faculty_highlights = $data['academicStaff_faculty_highlights'];
		}
		$instituteData['staff_faculty_highlights'] = $academicStaff_faculty_highlights;


		$instituteData['seo_title'] = !empty($data['listing_seo_title'])? $data['listing_seo_title'] : '';
		$instituteData['seo_description'] = !empty($data['listing_seo_description'])? $data['listing_seo_description'] : '';
		$instituteData['client_id'] = !empty($data['client_id'])? $data['client_id'] : '';
		$instituteData['submit_date'] = !empty($data['submit_date'])? $data['submit_date'] : date("Y-m-d H:i:s");
		

		$events = array();
		foreach ($data['events'] as $event) {
			$events[] = array('id'=>$event['id'],'type'=>$event['event_type_id'],'name'=>trim($event['name']),'description'=>trim($event['description']),'position'=>$event['position']);
		}
		$instituteData['events'] = $events;

		$scholarships = array();
		foreach ($data['scholarships'] as $scholarship) {
			$scholarships[] = array('type'=>$scholarship['scholarship_type_id'],'description'=>trim($scholarship['description']));
		}
		$instituteData['scholarships'] = $scholarships;

		$instituteData['research_projects'] = array();
		$instituteData['usp'] = array();
		$additional_attributes = array();
		foreach ($data['additional_attributes'] as $info) {
			switch($info['description_type']){
				case 'research_project';
					$instituteData['research_projects'][] 	= trim($info['description']);break;
				case 'usp':
					$instituteData['usp'][] 				= trim($info['description']);break;
				case 'award_recognition':
			}
		}

		$companies_mapping = array();
		foreach ($data['companies_mapping'] as $company) {
			$companies_mapping[] 	= array('company_id'=>$company['company_id'],'position'=>$company['order'],'company_name'=>trim($company['company_name']));
		}
		$instituteData['companies'] = $companies_mapping;

		$locations = array();
		$InstLocationIdToLocationMapping =  array();
		foreach ($data['locations'] as $location) {
			$locations[] = array('state_id'=>(int)$location['state_id'],'city_id'=>(int)$location['city_id'],'locality_id'=>(int)$location['locality_id'],'is_main_location'=>$location['is_main'],'institute_location_id'=>$location['listing_location_id']);

			$locationKey = $location['city_id']."_".$location['locality_id'];
           	$InstLocationIdToLocationMapping[$location['listing_location_id']] = $locationKey;
		}
		$instituteData['locations'] 		= $locations;

		$contactDetails = array();
		foreach ($data['contactDetails'] as $contact) {
			$contactDetails[$contact['listing_location_id']] = array('address' => $contact['address'],
								 'website_url' => $contact['website_url'],
								 'latitude' => $contact['latitude'],
								 'longitude' => $contact['longitude'],
								 'admission_contact_number' => $contact['admission_contact_number'],
								 'admission_email' => $contact['admission_email'],
								 'generic_contact_number' => $contact['generic_contact_number'],
								 'generic_email' => $contact['generic_email'],
								 'google_url' => $contact['google_url']);
		}
		$instituteData['contact_details'] 		= $contactDetails;

		$mapping = $this->institutepostingmodel->getChildFacilities();//_p($mapping);die;
		$facilities = array();$done = array();
		$others = array(18,19,20,21,22);

		if(isset($data['facilityMappings']['22'])){
			$tempList                    = array();
			$tempList['facility_id']     = 22;
			$tempList['description']     = ""; 
			$tempList['additional_info'] = ""; 
			$tempList['has_facility']    = 1;
            
            $data['facilitiesData']['22'] = $tempList;
		}

		$othersPresent = array_intersect(array_keys($data['facilitiesData']), $others);
		if(!empty($othersPresent)){
			$data['facilitiesData'][17] = array('facility_id'=>17);
			ksort($data['facilitiesData']);
		}


		foreach ($data['facilitiesData'] as $facilityId => $facility) {
			if(!in_array($facilityId,$done)){
				$facilities[$facilityId] = array('id'=>$facilityId,'description'=>trim($facility['description']));
				if(empty($facility['has_facility'])){
					$facilities[$facilityId]['is_present'] = 'no';
				}
				else{
					$facilities[$facilityId]['is_present'] = 'yes';
				}

				$facilities[$facilityId]['child_facilities'] = array();
				if(!empty($mapping[$facilityId])){
					$childfacilityIds = $mapping[$facilityId];
					foreach($childfacilityIds as $childfacilityId){
						$childFacility = array();
						$childFacility['custom_fields'] = array();

						if(!empty($data['facilitiesData'][$childfacilityId])){
							$childFacility['id']          = $data['facilitiesData'][$childfacilityId]['facility_id'];
							$childFacility['is_present']  = $data['facilitiesData'][$childfacilityId]['has_facility'];
							if(empty($childFacility['is_present'])){
								$childFacility['is_present'] = 'no';
							}
							else{
								$childFacility['is_present'] = 'yes';
							}
							$childFacility['description'] = $data['facilitiesData'][$childfacilityId]['description'];

							$additional_info = $data['facilitiesData'][$childfacilityId]['additional_info'];
							$additional_info = json_decode($additional_info,true);

							foreach($additional_info as $info){
								$childFacility['custom_fields'][] = array('name'=>$info['name'],'value'=>$info['value']);
							}
						}

						$childFacility['other_fields'] = array();
						$childFacility['values']       = array();

						if(!empty($data['facilityMappings'][$childfacilityId])){
							foreach($data['facilityMappings'][$childfacilityId] as $row){
								if(!empty($row['custom_name'])){
									$childFacility['other_fields'][] = $row['custom_name'];
								}
								if(!empty($row['value_id'])){
									$childFacility['values'][] = $row['value_id'];
								}
							}
						}
						$facilities[$facilityId]['child_facilities'][$childfacilityId] = $childFacility;
						$done[] = $childfacilityId;
					}
				}
				$done[] = $facilityId;
			}
		}
		$instituteData['facilities'] = $facilities;
		//_p($instituteData['facilities']);die;

		$photos = array();
		foreach ($data['photos'] as $mediaId => $row) {

			/*$row['media_url'] =  addingDomainNameToUrl(array('url' => $row['media_url'] , 'domainName' =>MEDIAHOSTURL));

			$row['media_thumb_url'] =  addingDomainNameToUrl(array('url' => $row['media_thumb_url'] , 'domainName' =>MEDIAHOSTURL));*/

			$photo = array('media_id'=>$mediaId,'media_url'=>$row['media_url'],'media_thumb_url'=>$row['media_thumb_url'],'title'=>trim($row['media_title']),'type'=>'Photo','locations'=>array(),'tags'=>array(), 'all_locations_flag' => false, 'position' => $row['media_order']);
			foreach ($row['locations'] as $loc) {
				if(!empty($loc['listing_location_id'])){
					$photo['locations'][] = $InstLocationIdToLocationMapping[$loc['listing_location_id']];
				}else if($loc['listing_location_id'] == 0){
					$photo['all_locations_flag'] = true;
				}
			}
			foreach($row['tags'] as $tag){
				if(!empty($tag)){
					if($tag['type'] == 'event')
						$photo['tags'][] = "event_".$tag['id'];
					else
						$photo['tags'][] = $tag['id'];
				}
			}
			$photos[] = $photo;
		}
		$instituteData['photos'] = $photos;

		$videos = array();;
		foreach ($data['videos'] as $mediaId => $row) {

			/*$row['media_url'] =  addingDomainNameToUrl(array('url' => $row['media_url'] , 'domainName' =>MEDIAHOSTURL));

			$row['media_thumb_url'] =  addingDomainNameToUrl(array('url' => $row['media_thumb_url'] , 'domainName' =>MEDIAHOSTURL));*/

			$photo = array('media_id'=>$mediaId,'media_url'=>$row['media_url'],'media_thumb_url'=>$row['media_thumb_url'],'title'=>trim($row['media_title']),'type'=>'Video','locations'=>array(),'tags'=>array(), 'all_locations_flag' => false, 'position' => $row['media_order']);
			foreach ($row['locations'] as $loc) {
				if(!empty($loc['listing_location_id'])){
					$photo['locations'][] = $InstLocationIdToLocationMapping[$loc['listing_location_id']];
				}
				else if($loc['listing_location_id'] == 0){
					$photo['all_locations_flag'] = true;
				}
			}
			foreach($row['tags'] as $tag){
				if(!empty($tag)){
					if($tag['type'] == 'event')
						$photo['tags'][] = "event_".$tag['id'];
					else
						$photo['tags'][] = $tag['id'];
				}
			}
			$videos[] = $photo;
		}
		
		$instituteData['videos'] = $videos;
		echo json_encode(array('data'=>$instituteData));
	}

	function getInstituteParentHierarchy() {
		$id = $this->input->post('id');
		$type = $this->input->post('type');
		$institutePostingType = !empty($_POST['institute_posting_type'])?$this->input->post('institute_posting_type'):'';
		$postingListingType = !empty($_POST['postingListingType'])?strtolower($this->input->post('postingListingType')):'institute';
		$typeOfListingPosting = !empty($_POST['typeOfListingPosting'])?$this->input->post('typeOfListingPosting'):'false';
		$excludeInstituteFromHierarchy = !empty($_POST['excludedInstituteId'])?$this->input->post('excludedInstituteId'):'';
		$this->init();
		if($postingListingType == 'university')
		{
			$hierarchyData = $this->institutePostingLib->getUniversityParentHierarchy($id,$type, $excludeInstituteFromHierarchy);	
		}
		else {
			$hierarchyData = $this->institutePostingLib->getInstituteParentHierarchy($id, $type, $institutePostingType,$typeOfListingPosting, $excludeInstituteFromHierarchy);
			if($postingListingType == 'course'){
				$temp = array();
				foreach ($hierarchyData as $rowIndex => $row) {
					$nonDummyFound = false;
					foreach ($row as $childIndex => $child) {
						if(empty($child['is_dummy'])){
							$nonDummyFound = true;
							break;
						}
					}
					if($nonDummyFound){
						$temp[] = $row;
					}
				}
				$hierarchyData = $temp;
			}
		}

		echo json_encode($hierarchyData);
	}

	private function _processInstituteMedia($instituteData, $userId, $type = 'photos') {
		$institutePhotos = array();
		$prefixKey = '';
		foreach($instituteData[$type] as $key=>$photoArr) {
			$mediaId = $photoArr['media_id'];
			$institutePhotos[$mediaId]['listing_media']['media_url']       = $photoArr['media_url'];
			$institutePhotos[$mediaId]['listing_media']['media_thumb_url'] = $photoArr['media_thumb_url'];
			$institutePhotos[$mediaId]['listing_media']['media_title']     = $photoArr['title'];
			$institutePhotos[$mediaId]['listing_media']['media_type']      = strtolower($photoArr['type']);
			$institutePhotos[$mediaId]['listing_media']['media_id']        = $photoArr['media_id'];
			$institutePhotos[$mediaId]['listing_media']['order']           = $photoArr['position'];
			//handling all location case
			if(count($photoArr['locations']) == 0 || $photoArr['all_locations_flag']) {
				$photoArr['locations'] = array();
				$photoArr['locations'][] = '0_0';
			}
			
			foreach($photoArr['locations'] as $locationKey => $location) {
				list($cityId, $localityId) = explode('_', $location);
				$institutePhotos[$mediaId]['locations'][$locationKey]['city_id']     = $cityId;
				$institutePhotos[$mediaId]['locations'][$locationKey]['locality_id'] = (!empty($localityId) || $localityId == 0) ? $localityId : NULL;
			}
			// _p($institutePhotos); die;
			//handling all tags case
			// if(count($photoArr['tags']) == 0) {
			// 	$photoArr['tags'][] = '0';
			// }
			foreach($photoArr['tags'] as $tagKey => $tagId) {
				$institutePhotos[$mediaId]['tags'][$tagKey]['tag_id']     = $tagId;
			}
		}
		return $institutePhotos;
	}

	private function _processInstituteLocations($instituteData, $userId) {

		$locationData = array();
		/*if(empty($instituteData['locations']))
			return $locationData;*/
		// prepare data for locations except main location
		foreach ($instituteData['locations'] as $key => $val) {
			if(!empty($val['city_id']) && $instituteData['is_dummy'] == 'false'){
				$temp = array();
				if(!$val['institute_location_id'])
				{
					$val['institute_location_id'] = $this->institutepostingmodel->_getNewInstituteLocationId();
				}
				$temp['listing_location_id']  = $val['institute_location_id'];
				$temp['state_id']    		  = $val['state_id'];
				$temp['city_id']     		  = $val['city_id'];
				$temp['locality_id'] 		  = $val['locality_id'];
				$temp['contact_details'] 	  = $val['contact_details'];
				$temp['updated_by']  		  = $userId;
				$locationData[] 	 		  = $temp;
			}
		}
		
		// main location data
		$val = $instituteData['main_location'];
		if(!empty($val)){
				$temp = array();
				if(!$val['institute_location_id'])
				{
					$val['institute_location_id'] = $this->institutepostingmodel->_getNewInstituteLocationId();
				}
				$temp['listing_location_id']  = $val['institute_location_id'];
				$temp['state_id']    		  = $val['state_id'];
				$temp['city_id']     		  = $val['city_id'];
				$temp['locality_id'] 		  = $val['locality_id'];
				$temp['contact_details'] 	  = $val['contact_details'];
				$temp['is_main']     		  = 1;
				$temp['updated_by']  		  = $userId;
				$locationData[] 	 		  = $temp;
		}
		
		return $locationData;
	}

	function uploadInstituteBrochure() {
		$response['data'] = array('error' => array('msg' => 'Unable to upload file due to incorrect data'));
		if($_FILES['uploads']) {
			$response = array();
			$response = $this->_prepareAndUploadInstituteBrochure($_FILES['uploads']);

			$finalResponse = $response;

			if(!is_array($response) && $response != ""){
				$finalResponse = array();
				$finalResponse['brochure_url'] = $response;
				$finalResponse['brochure_size'] = $_FILES['uploads']['size'][0];
			}
			// if(!is_array($response)) {
				$response = array('data' => $finalResponse);
			// }
		}
		echo json_encode($response);
	}

	private function _prepareAndUploadInstituteBrochure($uploadArrData) {
		// check if institute brochure has been uploaded
		if(!empty($uploadArrData['tmp_name'][0])) {
			$return_response_array = array();
			$this->_initUploadClient();
			// get file data and type check
			$type_doc = $uploadArrData['type']['0'];
			$type_doc = explode("/", $type_doc);
			$type_doc = $type_doc['0'];
			$type = explode(".",$uploadArrData['name'][0]);
			$type = strtolower($type[count($type)-1]);
			// display error if type doesn't match with the required file types
			if(!in_array($type, array('pdf','jpeg','doc','jpg'))) {
				$return_response_array['error']['msg'] = "Only document of type .pdf,.doc and .jpeg allowed";
				return $return_response_array;
			}
			// all well, upload now
			if($type_doc == 'image') {
				$upload_array = $this->UploadClient->uploadFile($appId,'image',array('i_brochure_panel' => $uploadArrData),array(),"-1","institute",'i_brochure_panel');
			}
			else {
				$upload_array = $this->UploadClient->uploadFile($appId,'pdf',array('i_brochure_panel' => $uploadArrData),array(),"-1","institute",'i_brochure_panel');
			}
			// var_dump($upload_array); 
			// check the response from upload library
			if(is_array($upload_array) && $upload_array['status'] == 1) {
				$return_response_array = $upload_array[0]['imageurl'];
			}
			else {
				if($upload_array == 'Size limit of 25 Mb exceeded') {
					$upload_array = "Please upload a brochure less than 25 MB in size";	
				}
				$return_response_array['error']['msg'] = $upload_array;
			}
			return $return_response_array;
		}
		else {
			return "";
		}
	}

	function uploadInstituteMedia($mediaType = 'photos') {
		$uploadArrData = array();
		switch($mediaType) {
			case 'photos':
				$mediaDataType = 'image';
				$listingMediaType = 'photo';
				$uploadArrData = $_FILES['uploads'];
				if(empty($uploadArrData['tmp_name'][0])) {
					return;
				}
				break;
			case 'videos':
				$mediaDataType = 'ytvideo';
				$listingMediaType = 'video';
				$uploadArrData = $this->input->post('url');

				if(strpos($uploadArrData, "https://") === false){
					$response['data']['error']['msg'] = "Video URLs should only be over HTTPS";
					echo json_encode($response);
					exit(0);
				}
				break;
			case 'documents':
				$mediaDataType = 'pdf';
				$listingMediaType = 'doc';
				$FILES = $_FILES;
				break;
		}

		/**
		 * Upload the media
		 */ 
		$this->_initUploadClient();
		$uploadResponse = $this->UploadClient->uploadFile(1,$mediaDataType,array('mediaFile' => $uploadArrData), null,"-1", "nationalInstitute",'mediaFile');

		if($uploadResponse['status'] == 1) {
			$this->init();
			$mediaId = $this->institutepostingmodel->saveInstituteMedia($uploadResponse[0], $listingMediaType, $this->userId);
			if(!$mediaId) {
				$response['data']['error']['msg'] = ucfirst($listingMediaType)." could not be uploaded.";	
			}
			unset($response['data']['error']);
			$response['data']['media'] = array(	"media_url" 		=> $uploadResponse[0]['imageurl'], 
						 						"media_thumb_url" 	=> $uploadResponse[0]['thumburl'],
						 						"media_id"			=> $mediaId);

		}
		else {
			$response['data']['error']['msg'] = ucfirst($listingMediaType)." could not be uploaded.";
		}
		echo json_encode($response);
	}

	function uploadInstituteLogo() {
		$response['data'] = array('error' => array('msg' => 'Please upload an image file'));
		if($_FILES['uploads']) {
			$response = array();
			$responseArr = $this->_prepareAndUploadInstituteMedia($_FILES['uploads']);
			$response = array('data' => array('logoData' => $responseArr['url']));
			$msg = '';
			$logoAndPanelRespWidth = (int)$responseArr['width'];
			$logoAndPanelRespHeight = (int)$responseArr['height'];
			if (($logoAndPanelRespWidth  > 300)) {
				$msg = "Please upload a logo with width less than or equal to 300 pixels";
			}
			$aspectRation = $logoAndPanelRespWidth/$logoAndPanelRespHeight;
			if(!($aspectRation >= 0.75 && $aspectRation <= 1.33)) {
				$msg = "Aspect Ratio of the images uploaded is not between 0.75 & 1.33 Kindly upload appropriate image. (Aspect ratio = width/height)";
			}

			/*if (($logoAndPanelRespWidth  < 60) &&  ($logoAndPanelRespHeight < 40)) {
				$msg = "Please upload a logo with height more than or equal to 40 pixels and width more than or equal to 60 pixels";
			}
			if ($logoAndPanelRespWidth  < 60) {
				$msg = "Please upload a logo with width more than or equal to 60 pixels.";
			}
			if ($logoAndPanelRespWidth  > 340) {
				$msg = "Please upload a logo with width less than or equal to 340 pixels.";
			}
			if ($logoAndPanelRespHeight < 40) {
				$msg = "Please upload a logo with height more than or equal to 40 pixels.";
			}
			if ($logoAndPanelRespHeight > 65) {
				$msg = "Please upload a logo with height less than or equal to 65 pixels";
			}*/
			
			if(!empty($responseArr['error'])) {
				$msg = $responseArr['error'];
			}
			//entering error if exists
			if($responseArr['url'] == '' || $msg != '') {
				$response['data']['logoData'] = array();
				$response['data']['error']['msg'] = ($msg != '') ? $msg : $responseArr['error'];
			}	
		}
		echo json_encode($response);
	}

	function _prepareAndUploadInstituteMedia($uploadArrData) {
		$appId = 1;
		$logoArr = array();
		$panelArr = array();
		$photoArr = array();
		$inst_logo= array();
		
		$this->_initUploadClient();
		
		for($i=0;$i<count($uploadArrData['name']);$i++){
			$inst_logo[$i] = ($arrCaption[$i]!="") ? $arrCaption[$i] : $_FILES['i_insti_logo']['name'][$i];
		}
		// _p($uploadArrData);
		if(isset($uploadArrData['tmp_name'][0]) && ($uploadArrData['tmp_name'][0] != '')) {
			$i_upload_logo = $this->UploadClient->uploadFile($appId,'image',array('i_insti_logo'=>$uploadArrData),null,"-1","institute",'i_insti_logo');
			// _p($i_upload_logo);
			if($i_upload_logo['status'] == 1) {
				for($k = 0;$k < $i_upload_logo['max'] ; $k++) {
					$arrContextOptions=array(
					     "ssl"=>array(
					         "verify_peer"=>false,
					         "verify_peer_name"=>false,
					     ),
					);
					$response = file_get_contents(addingDomainNameToUrl(array('url' => $i_upload_logo[$k]['imageurl'], 'domainName' => MEDIA_SERVER)), false, stream_context_create($arrContextOptions));
					$serverImage = imagecreatefromstring($response);
					$width = imagesx($serverImage);
					$height = imagesy($serverImage);

					// $tmpSize = getimagesize(addingDomainNameToUrl(array('url' => $i_upload_logo[$k]['imageurl'], 'domainName' => MEDIA_SERVER)));
					// list($width, $height, $type, $attr) = $tmpSize;
					$logoArr['width']=$width;
					$logoArr['height']=$height;
					// $logoArr['type']=$type;
					$logoArr['mediaid']=$i_upload_logo[$k]['mediaid'];
					$logoArr['url']=$i_upload_logo[$k]['imageurl'];
					$logoArr['title']=$i_upload_logo[$k]['title'];
					$logoArr['thumburl']=$i_upload_logo[$k]['imageurl'];
				}
			}
			else {
				$logoArr['error'] = $i_upload_logo;
				$logoArr['thumburl'] = "";
			}
			return $logoArr;
		}
	}

	function checkYoutubeUrl() {
		$youtubeUrl = $this->input->post('url');
		$this->_initUploadClient();
		$response = $this->UploadClient->uploadFile(1, 'ytvideo', array('mediaFile'=> $youtubeUrl), null, '-1', 'institute',  'mediaFile');
		if($response['status'] == 1) {
			$this->init();
			$this->institutepostingmodel->saveInstituteMedia($reponse[0], 'video', $this->userId);
		}
		echo json_encode($response);exit;
		// _p($response); die;
	}

	function getInstituteTypesInShiksha()
	{
		$this->init();
		$result = $this->institutepostingmodel->getInstituteTypesInShiksha();		
		$data                      = array('data' => $result);
		echo json_encode($data);die;	
	}
	function getListOfInstitutesBasedOnFilters()
	{
		$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$instituteId  = !empty($data['instituteId'])?$data['instituteId']:'';
		$universityId  = !empty($data['universityId'])?$data['universityId']:'';
		$status  = !empty($data['status'])?$data['status']:'';
		$type  = !empty($data['type'])?$data['type']:'';
		$pageNumber = !empty($data['pageNumber'])?$data['pageNumber']:1;
		$fetchListType = !empty($data['fetchListType'])?strtolower($data['fetchListType']):'institute';
		$startOffset = ($pageNumber - 1) * 25;
		$baseCall = false;

		$openSearch  = !empty($data['openSearch'])? $data['openSearch'] : '';
		if(empty($openSearch))
		{
			$instituteId = empty($instituteId) ? (!empty($data['institute'])?$data['institute']:'') : $instituteId;
			$universityId = empty($universityId) ? (!empty($data['university'])?$data['university']:'') : $universityId;
		}
		
		if(empty($instituteId) && empty($universityId) && empty($openSearch))
		{
			$baseCall = true;
		}
		$result = $this->institutepostingmodel->getSearchResultsForTable($instituteId,$universityId,$status,$type,$startOffset,$baseCall,$openSearch,$fetchListType);
		$result['paginationNum'] = $pageNumber;
		$data =  array('data' => $result);
		
		echo json_encode($data);die;


	}

	function getInstitutePostingComments(){

		$this->init();
		$instituteId = $this->input->post('instituteId');
		$listingType = !empty($_POST['listingType'])?strtolower($this->input->post('listingType')):'institute';
		$data = array();
		if(empty($instituteId)){

		}
		else{
			$data = $this->institutePostingLib->getInstitutePostingComments($instituteId,$listingType);
		}
		echo json_encode($data);
		exit(0);
	}


	function getShikshaInstituteName(){
		$this->init();
		$instituteId   = $this->input->post('id');
		$instituteType = $this->input->post('type');

		if($instituteType == 'institute'){	
			$data = $this->institutepostingmodel->getInstituteNamesById(array($instituteId),array('name'));
		}else{
			$data = $this->institutepostingmodel->getUniversityDataById(array($instituteId),array('name'));	
		}
		
		echo json_encode($data['idNameArr']);
		exit(0);
	}

	function getSAUniversities(){
		$this->load->library('SASearch/AutoSuggestorSolrClient');
        $this->autoSuggestorSolrClient = new AutoSuggestorSolrClient;
        $keyword = $this->input->post('text');
        $source = $this->input->post('source');
       
		$keyword = trim($keyword);
		if(empty($keyword)) {
			return '';
		}

		if($source == 'autosuggestor'){
			$requestData = array();
			$requestData['text'] = $keyword;
			$requestData['eachfacetResultCount'] = 20;
			$solrResults = $this->autoSuggestorSolrClient->getUnivSuggestionsFromSolr($requestData);
			$data = array();
			foreach ($solrResults as $key => $value) {
				$data['institute'][$value['saAutosuggestUnivId']] =  $value['saAutosuggestUnivNameFacet']; 
			}
		}else{
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder                   = new ListingBuilder;
			
			$this->abroadUniversityRepository = $listingBuilder->getUniversityRepository();
			$universityData = $this->abroadUniversityRepository->find($keyword);
			$data['institute'][$universityData->getId()] =  $universityData->getName(); 
		}

        
		$jsonEncodedData = json_encode($data);
		echo $jsonEncodedData;
	}



	function validateListingForDeletion(){

		$this->init();

        $request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);

		$deleted_listing_migerate_id = $data['newInstituteId'];
		$listingType = $data['listingType'];

        if(empty($deleted_listing_migerate_id)) {
            $deleted_listing_migerate_id = -1;
        }
        
        $result_array = $this->institutepostingmodel->checkListingStatus($deleted_listing_migerate_id,$listingType);
        
        /** check if abroad institute is not in flow ***/
        if($listing_type == 'institute' && count($result_array) > 0) {
            foreach($result_array as $instId => $resultid) {
                $this->load->builder('ListingBuilder','listing');
                $listingBuilder             = new ListingBuilder;
                $instRepo = $listingBuilder->getInstituteRepository();
                $insObj = $instRepo->find($instId);
                if(!($insObj instanceof Institute)) {
                    unset($result_array[$instId]);
                }
            }
            
        }
          	
        if(count($result_array) > 0) {
            echo json_encode(array('data'=>array('instName'=>$result_array['listingName'],'message'=>'Entered institute is valid.','status'=>'success')));
        } else {
            echo json_encode(array('data'=>array('message'=>'Entered '.$listingType.' not found. Please enter a valid '.$listingType.' ID','status'=>'fail')));
        }

    }

	function deleteListing()
    {
    	$this->init();
        
        $user_id = $this->userId;
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body,true);

        $listingTypeId = $data['listingTypeId'];
		$deleted_new_listing_id = $data['newInstituteId'];
		$listingType = $data['listingType'];
		$deleted_new_listing_name = $data['newlisting_name'];
 
        if($listingTypeId){
	        //get institute hierarchy  
	        $instituteIds = array();   
	        $instituteIdsTemp = $this->getChildHierarchy($listingTypeId,$listingType);
	        $instituteIds = $instituteIdsTemp['instituteIds'];
	        $instituteType = $instituteIdsTemp['instituteType'];


	        if(!empty($instituteIds) && in_array($deleted_new_listing_id, $instituteIds)){
	        	echo json_encode(array('data'=>array('message'=>'you cannot migrate data to this listing as it is one of the child of deleted listing.','status'=>'fail')));
	        	return;
	        }
	       
	        if(!empty($instituteIds)){
	        	$all_child_ids = implode(',',$instituteIds);
	    	}else{
	    		$instituteIds[] =$listingTypeId;
	    		$instituteType[] = $listingType;
	    		$all_child_ids = $listingTypeId;
	    	}
	    	/**
	        * Check for Online form
	        * If the institute/course contains an Online form,
	        * we should now allow it to be deleted.
	        */
	        $this->load->library('Online_form_client');
	        $onlineClient = new Online_form_client();
	       
	        foreach($instituteIds as $listingId){
	        	$listingHasOnlineForm = $onlineClient->checkIfListingHasOnlineForm('institute',$listingId);
	        	if($listingHasOnlineForm){
	            	echo json_encode(array('data'=>array('message'=>'you cannot delete this listing as onlineform is available.','status'=>'fail')));
	            	return;
	        	}
	    	}
	    
	    	/*update status as deleted in all corresponding tables*/
	       	$deleteStatus = $this->institutepostingmodel->deleteListings($all_child_ids,$user_id,$listingType);


			//deleting amp page from Google CDN
	       	$this->deleteInstituteUniveristyPageOnGoogleCDNcacheAMP($all_child_ids);
	       

	        if(!empty($deleted_new_listing_id)){

	        	 /*update tagId for deleted listing and mark status as deleted in tags_entity Table*/
	        	 $this->institutepostingmodel->updateTagIdForDeletedListing($all_child_ids,$deleted_new_listing_id);

	        	 /*update AnARecommendation table*/
	        	 $AnARecomodel = $this->load->model("ContentRecommendation/anarecommendationmodel");
	        	 $AnARecomodel->updateInstituteIdAnARecommendation($all_child_ids,$deleted_new_listing_id);

	        	 //migrate questions to the new listing
			        foreach($instituteIds as $listingId){
			        	$result = $this->institutePostingLib->migrateDeletedInstituteData($listingId,$deleted_new_listing_id,$listingType);
			        }
			     //migrate reviews to the new listing
			     $this->LDBClienLib = $this->load->library('LDB/clients/LDBMigrationLib');
			     $reviewInstitute[$listingTypeId] = $deleted_new_listing_id;
			     $this->LDBClienLib->updateDataForInstituteDeletion($all_child_ids,$deleted_new_listing_id);
	        }

	        $this->institutePostingLib->migrateUGCModules($all_child_ids,$deleted_new_listing_id);
	        
	     	if(strtolower($listingType) == "institute"){
	                $tagEntityType = "institute";
	        }else{
	                $tagEntityType = "University";
	        }

	        //get all the courses of the institute and update status as deleted in all course corresponding tables
        	$institutemodel = $this->load->model("nationalInstitute/institutemodel");
			$courseArray       = $institutemodel->getCoursesOfInstitutes($instituteIds);

			if(!empty($courseArray)){
				$coursesList = array();
				foreach ($courseArray as $value) {
					$coursesList = array_merge($coursesList,$value);
				}
				$coursesList = array_values(array_unique($coursesList));
			}	
			
			if(!empty($coursesList)){
				$coursemodel = $this->load->model("nationalCourse/coursepostingmodel");
				$coursemodel->deleteMultipleCourses($coursesList); 
				//deleting course amp cache from Google CDN
				Modules::run('nationalCourse/CoursePosting/deleteCoursePageOnGoogleCDNcacheAMP',$coursesList);
			}
			
	        foreach($instituteIds as $key1  => $listingIdChild){
	        	if(strtolower($instituteType[$key1]) == "institute"){
	                $tagEntityType = "institute";
		        }else{
        			$tagEntityType = 'National-University';
		        }
	        	$this->taggingCMSLib->addTagsPendingMappingAction($tagEntityType,$listingIdChild,'Delete');
	        }

	         //Insert an entry in indexlog table
	        $this->institutepostingmodel->updateIndexLog($instituteIds,$listingType,'delete');

	        // Maintain Flat Table
        	if(!empty($listingTypeId)){
        		$this->instituteFlatTableLib->flatTableUpdateOnInstDelete($listingTypeId);
        		if(!empty($instituteIds)){
        			$this->instituteFlatTableLib->flatTableUpdateOnInstDelete($instituteIds);		
        		}
        	}
	        
	        

	        // invalidate institute cache
        	$this->nationalinstitutecache->removeInstitutesCache($instituteIds);
        	// invalidate its primary-courses cache
    		$this->invalidateInstituteCoursesCache($instituteIds);
    		
    		//removing deleted institute from trending searches cache
    		$TrendingSearchLib = $this->load->library('listingCommon/TrendingSearchLib');
    		$TrendingSearchLib->validateAndUpdateTrendingSearches($listingTypeId, 'collegeAndCourse');

	        echo json_encode(array('data'=>array('message'=>$listingType.' deleted successfully.','status'=>'success')));
	       
    	}else{
    		echo json_encode(array('data'=>array('message'=>'something went wrong.','status'=>'fail')));
    	}
 
    }

    public function makeListingPageDisable	(){
    	$this->init();
    	$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);

		$listingTypeId = $data['listingTypeId'];
		$listingType = $data['listingType'];
		$disableListing_url = $data['disabled_url'];
	
		if(!empty($listingTypeId)){
			$instituteIds = array();

			//get all child institutes and update its disable url
			$instituteIdsTemp = $this->getChildHierarchy($listingTypeId,$listingType);
			$instituteIds = $instituteIdsTemp['instituteIds'];
	        if(empty($instituteIds)){
	        	$instituteIds =array($listingTypeId);
	        }
	        
        	$this->institutepostingmodel->updateDisableListingUrl($instituteIds,$disableListing_url);

			//update disable url of all courses
			$coursemodel = $this->load->model('nationalCourse/coursepostingmodel');
			$coursemodel->updateCourseDisableUrl($instituteIds,$disableListing_url,$listingType);

			// invalidate institutes cache
			$this->nationalinstitutecache->removeInstitutesCache(array($listingTypeId));
    		$this->nationalinstitutecache->removeInstitutesCache($instituteIds);
    		// invalidate its primary-courses cache
    		$this->invalidateInstituteCoursesCache(array($listingTypeId));
    		$this->invalidateInstituteCoursesCache($instituteIds);

        	//Insert an entry in indexlog table
        	//$this->institutepostingmodel->updateIndexLog($instituteIds,$listingType,'delete');

    		echo json_encode(array('data'=>array('message'=>$listingType.' successfully marked as disabled.','status'=>'success')));
	        
		}else{
			echo json_encode(array('data'=>array('message'=>'Listing Id cannot be null.','status'=>'fail')));
		}
    }

    function makeListingLive()
    {
 		$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);

		$instituteId  = !empty($data['instituteId'])?$data['instituteId'] : '';
		$listingStatus = !empty($data['listingStatus'])?$data['listingStatus'] : '';
		$postingListingType = !empty($data['postingListingType'])?strtolower($data['postingListingType']):'institute';

		$liveListingData = $this->institutepostingmodel->getLiveListingData($instituteId,$postingListingType);
		$response = $this->institutepostingmodel->makeListingLive($instituteId,$listingStatus,$postingListingType);
		$liveListingDataNew = $this->institutepostingmodel->getLiveListingData($instituteId,$postingListingType);
		
	
		if($response)
		{
				if($response === 'FAIL')
				{
					echo json_encode(array('data'=> array('msg'=> 'You cannot make this '.$postingListingType.' as live because it\'s parent is disabled.')));die;	
				}
				else
					//Insert an entry in indexlog table
					$listingIds = array('listingId'=>$instituteId);
					if($listingStatus == 'draft' || $listingStatus == 'disabled_draft'){

							if(strtolower($postingListingType) == "institute"){
								$tagEntityType = "institute";
							}else{
        							$tagEntityType = 'National-University';
							}

							$this->taggingCMSLib->addTagsPendingMappingAction($tagEntityType,$instituteId,'Edit');	

						if(!empty($liveListingData)){			
							$this->institutepostingmodel->updateIndexLog($listingIds,$postingListingType,'index','universityTypeChange');
							
							$this->institutepostingmodel->updateIndexLog($listingIds,$postingListingType,'index','listingOnly');
						}else{
							
							$this->institutepostingmodel->updateIndexLog($listingIds,$postingListingType,'index');
						}
					}else{
						$this->institutepostingmodel->updateIndexLog($listingIds,$postingListingType,'index');
					}
					
					// invalidate institute cache
		    		$this->nationalinstitutecache->removeInstitutesCache(array($instituteId));
		    		// invalidate its primary-courses cache
		    		$this->invalidateInstituteCoursesCache(array($instituteId));

		    		if($liveListingData['parent_listing_id'] != $liveListingDataNew['parent_listing_id']){
						$this->instituteFlatTableLib->flatTableInstituteUpdate($instituteId);
					}

					if($liveListingData['is_satellite'] != $liveListingDataNew['is_satellite']){
						 $this->instituteFlatTableLib->flatTableInstituteUpdate($instituteId,$liveListingDataNew['is_satellite']);
					}

					echo json_encode(array('data'=> array('msg'=> $postingListingType.' made as live')));die;
		}
		else
		{
			echo json_encode(array('data'=> array('msg'=> 'Failed to made Live')));die;
		}
		
    }

    function getCourseListForPrimary (){
    	$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$listingTypeId = !empty($data['listingTypeId'])?$data['listingTypeId'] : '';
		$result = $this->institutepostingmodel->getCoursesForPrimaryListing($listingTypeId);
		if(!empty($result)){
			echo json_encode(array('data'=> array('result'=>$result,'status'=> 'success')));
		}else{
			echo json_encode(array('data'=> array('message'=>'No courses found.','status'=> 'fail')));
		}

    }

    function setOrderingForCourses(){
    	$this->init();
		$request_body = file_get_contents('php://input');
		$userId = $this->userId;
		$data = json_decode($request_body,true);
		$listingTypeId = !empty($data['listingTypeId'])?$data['listingTypeId'] : '';
		$listingType = !empty($data['listingType'])?$data['listingType'] : '';
		$editHappen = false;

		foreach ($data['prevCourseList'] as $key => $val) {
			if($val['course_id'] != $data['courseList'][$key]['course_id'] || $val['course_order'] != $data['courseList'][$key]['course_order']){	
				$editHappen = true;
				break;
			}
				
		}	

		if($editHappen == true){
			foreach($data['courseList'] as $key=>$val){
				$this->institutepostingmodel->updateCourseOrderInDB($val['course_id'],$val['course_order']);
			}
			$this->institutepostingmodel->_trackInstituteUpdatedSections($listingTypeId,$userId,array('sectionName'=>'courseOrderSection',$listingType));

			$this->institutepostingmodel->updateIndexLog(array('listing_id'=>$listingTypeId),$listingType,'index','courseOrderSection');

		}

		echo json_encode(array('data'=> array('status'=> 'success')));
		
    }

    function getChildHierarchy($listingId,$listingType){
    	$result = $this->institutepostingmodel->getChildData($listingId,$listingType,false);
		    foreach ($result as $key => $value) {
	            $temp = explode('_', $value[count($value) - 1]);
	            if(!empty($temp[1]))
	                $instituteIds[] = $temp[1];
	            	$instituteType[] = $temp[0];
	            	//$instituteType[] = $temp[0]
	        }
	        $finalResult = array();
	        if(!empty($instituteIds)){
	        	$finalResult['instituteIds'] = $instituteIds;
	        	$finalResult['instituteType'] = $instituteType;
	        }
	        return $finalResult;

    }

    function validateInstituteTypeInHierarchy(){
    	$this->init();

    	$parentId = $this->input->post("parentId");
    	$parentType = $this->input->post("parentType");
    	$instituteType = $this->input->post("instituteType");
    	$instituteId = !empty($_POST['instituteId'])?$this->input->post('instituteId'):'';

    	$data = $this->institutepostingmodel->getParentData($parentId, 'institute');

    	if(!empty($instituteId))
    	{
    		$childDataTemp = $this->getChildHierarchy($instituteId, 'institute');	
    		$childData = $childDataTemp['instituteIds'];

    	}
    	$ids = array();
    	foreach ($data as $key => $value) {
    		$value = explode("_", $value);
    		$ids[] = $value[1];
    	}
    	foreach ($childData as $key => $value) {
    		if($value != $instituteId)
    			$ids[] = $value;
    	}
    	if(!in_array($parentId, $ids) && !empty($parentId) && $parentId != 'null')
    	{
    		$ids[] = $parentId;
    	}

    	$instituteTypeData = array();
    	if($ids)
    		$instituteTypeData = $this->institutepostingmodel->getInstituteNamesById($ids, array('institute_specification_type','is_dummy','is_satellite'), true,false);

    	$instituteTypeAlreadyExistsInHierarchy = false;
    	foreach($instituteTypeData as $row){
    		if($row['institute_specification_type'] == $instituteType && empty($row['is_dummy']) && empty($row['is_satellite'])){
    			$instituteTypeAlreadyExistsInHierarchy = true;
    		}
    	}

    	echo json_encode(array("alreadyExists"=>$instituteTypeAlreadyExistsInHierarchy));
    }

    function checkIfLocationMappedToCourse(){
    	$this->init();

    	$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$instituteLocationIds = $data['instituteLocationIds'];

		$this->institutedetailsmodel = $this->load->model('nationalInstitute/institutedetailsmodel');

		$locationMappedToCourseFlag = false;
		$locationMappedToCourseFlag = $this->institutedetailsmodel->checkIfInstituteLocationsMappedToCourse($instituteLocationIds);

    	echo json_encode(array("mapped"=>$locationMappedToCourseFlag));
    }

    function test(){
    	$this->load->config('mcommon5/mobi_config');
    	$streamList = $this->config->item('streamList');
    	_p(json_encode($streamList));die;
    	// $institutemodel = $this->load->model("nationalInstitute/institutemodel");
    	// $data = $institutemodel->getDataForMultipleInstitutes(array(35456,49371,49451));
    	// _p($data);die;
    	_p(memory_get_peak_usage()/(1024*1024));
    	$this->load->builder("nationalInstitute/InstituteBuilder");
    	$instituteBuilder = new InstituteBuilder();
    	$repo = $instituteBuilder->getInstituteRepository();
    	// $repo->disableCaching();
    	// $data = $repo->find(49571, array('basic','location','academic','research_project','usp', 'event', 'scholarship','company','media','facility'));
    	// $data = $repo->find(35861, array('basic','location','academic','research_project','usp', 'event', 'scholarship','company','media','facility'));
    	// $data = $repo->find(49258,array('basic'));
    	// $data = $repo->findWithCourses(array(49155=>array(249853)), '', array('basic'));
    	// $data = $repo->findMultiple(array(34593,44766,35861,49258,49371,49571,49330), array('basic','location','academic','research_project','usp', 'event', 'scholarship','company','media','facility')); //49371,49258, 49571;
    	// $data = $repo->getInstituteLocations(array(49258,49371,49571,49330,49632), array(142173, 142516,142608,142670)); //49371,49258, 49571
    	// $data = $repo->getInstituteLocations(array(49258,49371,49571,49330,49632), array(), array('states'=>array(128))); 
    	$data = $repo->findMultiple(array(49892), 'full'); //49371,49258, 49571;
    	// _p(serialize($data->getBasic()));
    	
    	_p($data);
    	_p(memory_get_peak_usage()/(1024*1024));
    }

    function testcourse(){
    	_p(memory_get_peak_usage()/(1024*1024));
    	// $this->load->builder("nationalCourse/CourseBuilder");
    	// $builder = new CourseBuilder();
    	// $repo = $builder->getCourseRepository();
    	$this->load->builder("nationalInstitute/InstituteBuilder");
    	$instituteBuilder = new InstituteBuilder();
    	$repo = $instituteBuilder->getInstituteRepository();
    	$data = $repo->getCoursesOfInstitutes(array(49155,49148));

    	_p($data);
    	_p(memory_get_peak_usage()/(1024*1024));
    }
    function checkDraftStatusExist()
    {
    	$this->init();

    	$listing_id = !empty($_POST['listing_id'])?$this->input->post('listing_id'):'';

    	error_log('listing_id '.print_r($listing_id,true));
    	$data = $this->institutepostingmodel->checkDraftStatusExist($listing_id);
    	echo json_encode(array("data" => array("showResponse"=>$data)));
    	die;
    }
    function isUserAllowedInEditMode()
    {
    	$this->init();

    	$listingId = !empty($_POST['listingId'])?$this->input->post('listingId'):'';
    	$listingType = !empty($_POST['listingType'])?$this->input->post('listingType'):'';

    	$this->load->library('nationalInstitute/cache/NationalInstituteCache');  

    	$data = $this->nationalinstitutecache->isUserAllowedInEditMode($listingId,$listingType,$this->userId);
    	echo json_encode(array("data"=> array('isUserAllowEdit'=>$data)));die;

    }

    function isAllowUserAction()
    {
    	$this->init();

    	$listingId = !empty($_POST['listingId'])?$this->input->post('listingId'):'';
    	$listingType = !empty($_POST['listingType'])?$this->input->post('listingType'):'';

    	$this->load->library('nationalInstitute/cache/NationalInstituteCache');  

    	$data = $this->nationalinstitutecache->isAllowUserAction($listingId,$listingType,$this->userId);
    	echo json_encode(array("data"=> array('isUserAllowEdit'=>$data)));die;
    }
    function removeLockListingForm($listingId,$listingType='Institute')
    {
    	if(empty($listingId))
    		return false;
		$this->load->library('nationalInstitute/cache/NationalInstituteCache');  

    	return $this->nationalinstitutecache->removeLockListingForm($listingId,$listingType);    	
    }
    function cancelEditForm()
    {
    	$this->init();

    	$listingId = !empty($_POST['listingId'])?$this->input->post('listingId'):'';
    	$listingType = !empty($_POST['listingType'])?$this->input->post('listingType'):'';

    	$data = $this->removeLockListingForm($listingId,$listingType);

    	
    	echo json_encode(array("data"=> array('cancelEdit'=>$data)));die;

    }

    function getListingCourses()
    {
    	$this->init();
    	$listingId = !empty($_POST['listingId'])?$this->input->post('listingId'):'';
    	$listingContentType = !empty($_POST['listingContentType'])?strtolower($this->input->post('listingContentType')):'popular';
    	$rs = $this->institutepostingmodel->getListingName($listingId);
    	$listingName = !empty($rs[0]['name']) ? $rs[0]['name'] : '' ;
    	$listingType = !empty($rs[0]['listing_type']) ? $rs[0]['listing_type'] : '';

    	if(!empty($listingName) && $listingContentType != 'article')
    	{
    		$this->load->library('nationalInstitute/InstituteDetailLib');
    		$courseList = $this->institutedetaillib->getAllCoursesForInstitutes($listingId);
    		$courseList = $courseList['courseIds'];


    		//loading course builder for getting course objects
    		if(!empty($courseList)){
    			$this->load->builder("nationalCourse/CourseBuilder");
	        	$courseBuilder = new CourseBuilder();
		        $this->courseRepo = $courseBuilder->getCourseRepository();
		         $coursesInfo = $this->courseRepo->findMultiple($courseList);
		         $instituteCourses = array();
		         foreach ($coursesInfo as $courseKey => $courseValue) {
		         	$courseObject = $coursesInfo[$courseKey];
		         	$courseId = $courseObject->getId();
		         	$courseName = $courseObject->getName();
		         	/*if($listingContentType == 'featured' && !$courseObject->isPaid())
		         		continue;*/
		         	$primatyInstituteId = $courseObject->getInstituteId();
		         	if($primatyInstituteId != $listingId)
		         	{
		         		$instShortName = $courseObject->getInstituteShortName();
		         		if(!empty($instShortName)){
		         			$instituteName = $courseObject->getInstituteShortName();
		         		}else{
		         			$instituteName = $courseObject->getInstituteName();
		         		}
		         		$courseName .= ' (offered by '.$instituteName.')';
		         	}
		         	if(strlen($courseName) > 120)
		         	{
		         		$courseName = substr($courseName, 0,117).'...';
		         	}
		         	$instituteCourses[] = array('course_id' => $courseId,'course_name' => $courseName);
		         }

		         //sort course alphabetically
		         function course_sort($a,$b)
				 {
					if ($a['course_name']==$b['course_name']) return 0;
					  return (strtolower($a['course_name'])<strtolower($b['course_name']))?-1:1;
				 }

				 usort($instituteCourses,"course_sort");

		         $courseOrder = $this->institutepostingmodel->getListingContentCourseOrder($listingId,$listingType,$listingContentType);
		         
    		}
    		
    	}
    	else if($listingContentType == 'article')
    	{
    		$articleInfo = $this->institutepostingmodel->getArticleIdForListingSticky($listingId,$listingType);
    	}

    	//$result = $this->institutepostingmodel->getCoursesForPrimaryListing($listingId);

    	echo json_encode(array('data' => array('listingName'=>$listingName,'courses'=> !empty($instituteCourses)?$instituteCourses:null,'listingType'=>$listingType,'courseOrder'=>$courseOrder['courses'],'expiryDate'=>!empty($courseOrder['expiryDate'])?$courseOrder['expiryDate']:$articleInfo['expiryDate'],'articleId'=>$articleInfo['articleId'],'articleName'=>!empty($articleInfo['articleName'])?$articleInfo['articleName']:'')));die;


    }
    function saveListingContent()
    {
    	$this->init();
		$listingId = !empty($_POST['listingId'])?$this->input->post('listingId'):'';
		$listingType = !empty($_POST['listingType'])?$this->input->post('listingType'):'';    	
		$popularCourseOrder = !empty($_POST['popularCourse'])?$this->input->post('popularCourse'):'';    	
		$duration = !empty($_POST['duration'])?$this->input->post('duration'):'';    	
		$listingContentType = !empty($_POST['listingContentType'])?strtolower($this->input->post('listingContentType')):'popular';    	
		$articleId = !empty($_POST['articleId'])?$this->input->post('articleId'):'';
			
		$popularCourseOrder = explode(',', $popularCourseOrder);
		$popularCourseArray = array();
		foreach ($popularCourseOrder as $key => $value) {
			if(!empty($value))
				$popularCourseArray[] = $value;
		}

		$rs = $this->institutepostingmodel->saveListingContent($listingId,$listingType,$popularCourseArray,$listingContentType,$duration,$articleId);
		if($rs)
		{
			echo json_encode(array('data'=> array('msg' => 'Inserted successfully','status'=>true)));
		}
		else
		{
			echo json_encode(array('data'=> array('msg' => 'Some problem occured','status'=>false)));
		}
    }
    function getArticleInfo()
    {
    	$this->init();

    	$articleId = !empty($_POST['articleId'])?$this->input->post('articleId'):'';
    	$listingId = !empty($_POST['listingId'])?$this->input->post('listingId'):'';
    	$listingType = !empty($_POST['listingType'])?$this->input->post('listingType'):'';

    	$result = $this->institutepostingmodel->checkArticleMappedToListing($articleId,$listingId,$listingType);
    	if($result['result'] == 2)
    		echo json_encode(array('data'=> array('msg'=>'article is not exist on this id','status'=> false,'articleName' => $result['articleName'])));
    	else if($result['result'] == 1)
    		echo json_encode(array('data'=> array('msg'=>'this article is not mapped with this listing id','status'=> false,'articleName' => $result['articleName'])));
    	else
    		echo json_encode(array('data'=> array('msg'=>'success','status'=> true,'articleName' => $result['articleName'])));
    	

    }

    function testCourseList($listingId, $listingType){

    	$this->load->library("nationalInstitute/InstituteDetailLib");
    	$InstituteDetailLib = new InstituteDetailLib();
    	$data = $InstituteDetailLib->getInstituteCourseIds($listingId, $listingType);
    	_p($data);
    }

    function listingContentReset()
    {
    	$this->init();


    	$listingId = !empty($_POST['listingId'])?$this->input->post('listingId'):'';
    	$listingType = !empty($_POST['listingType'])?$this->input->post('listingType'):'';
    	$pageTitle = !empty($_POST['pageTitle'])?strtolower($this->input->post('pageTitle')):'';

    	if(empty($listingId))
    		return false;

    	$result = $this->institutepostingmodel->listingContentResetOptions($listingId,$listingType,$pageTitle);

    	if($result)		
    	{
    		echo json_encode(array('data' => array('status' => $result,'msg' => 'Options are cleared')));die;
    	}
    	else
    	{
    		echo json_encode(array('data' => array('status' => $result,'msg' => 'Some problem occured')));die;
    	}


    }
	/**
	* returns institute/university is paid or free.
	*/

    function isListingPaid()
    {
    	$this->init();
    	$listingId = !empty($_POST['listingId'])?$this->input->post('listingId'):'';
    	$listingType = !empty($_POST['listingType'])?strtolower($this->input->post('listingType')):'';

    	if(empty($listingId) || empty($listingType))
    	{
    		echo json_encode(array('data' => array('msg' => true)));die;
    	}

 		$this->load->library('nationalInstitute/InstituteDetailLib');
    		$courseList = $this->institutedetaillib->getAllCoursesForInstitutes($listingId);
    	$courseList = $courseList['courseIds'];   	

    	$isPaid = false;
    	
    	if(!empty($courseList) && count($courseList) > 0)
    	{
    		$this->load->builder("nationalCourse/CourseBuilder");
	        $courseBuilder = new CourseBuilder();
	        $this->courseRepo = $courseBuilder->getCourseRepository();
	        $coursesInfo = $this->courseRepo->findMultiple($courseList);

	    	foreach ($coursesInfo as $courseKey => $courseValue) {
	    		$courseObject = $coursesInfo[$courseKey];
	    		$isPaidCourse = $courseObject->isPaid();
	    		if($isPaidCourse)
	    		{
	    			$isPaid = true;
	    			break;
	    		}

	    	}	
 	   	}

 	   	echo json_encode(array('data' => array('isPaid' => $isPaid,'msg' => false)));die;	    
    }

    function invalidateInstituteCoursesCache($instituteIds, $courseList = array()){

    	if(empty($instituteIds))
    		return;


    	if(empty($courseList)){
	    	$this->load->builder("nationalInstitute/InstituteBuilder");
	        $instituteBuilder = new InstituteBuilder();
	        $this->instituteRepo = $instituteBuilder->getInstituteRepository();
	        $courseIds = $this->instituteRepo->getCoursesListForInstitutes($instituteIds);

	        $courseList = array();
	        foreach ($courseIds as $value) {
	        	$courseList = array_merge($courseList, $value);
	        }
	        $courseList = array_values(array_unique($courseList));
	    }

	    // invalidate course cache
	    if($courseList){
	    	writeToLog('Step 1. inside course cache delete flow from institute delete for course ids : '. implode(', ', $courseList), COURSE_DELETION_LOG_FILE);
		    $this->nationalcoursecache = $this->load->library('nationalCourse/cache/NationalCourseCache');
		    $this->nationalcoursecache->removeCoursesCache($courseList);
		}
    }
    //createGoogleStaticMap
    function testMap($locationsData)
    {
    	
    //		"https://maps.googleapis.com/maps/api/staticmap?center=22.4451,88.2989&zoom=15&size=600x300&key=AIzaSyDn9KK_QzvSxIuajjStEjycRiC0Co3fiYc";
    	error_log('locations'.print_r($locationsData,true));
    	/*foreach ($locationsData as $locationKey => $locationValue) {
    		if(!empty($locationValue['contact_details']['latitude']) && !empty($locationValue['contact_details']['longitude']) && !empty($locationValue['listing_location_id']))
    		{*/
    			$locationValue['contact_details']['latitude'] = 23.0329;
    			$locationValue['contact_details']['longitude'] = 72.5328;
    			$url ="https://maps.googleapis.com/maps/api/staticmap?center=".$locationValue['contact_details']['latitude'].",".$locationValue['contact_details']['longitude']."&zoom=15&size=600x300&format=jpg&markers=color:blue%7Clabel:L%7C".$locationValue['contact_details']['latitude'].",".$locationValue['contact_details']['longitude']."&key=AIzaSyDn9KK_QzvSxIuajjStEjycRiC0Co3fiYc";
    			$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch,CURLOPT_HEADER,0);
				//curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-type: application/json'));

				$output = curl_exec($ch);
				_p(curl_getinfo($ch));die;
				$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
				if($httpCode == 200)
				{
					error_log('path '.MEDIA_BASE_PATH);
					$fp  = fopen(MEDIA_BASE_PATH.'/images/listingLocationMaps/listinglocation'.$locationValue['listing_location_id'].'.jpg', 'w+'); 

					fwrite($fp, $output); 
					fclose($fp); 
				}
				unset($output);

    		/*}
    	}*/

    	//$url = "https://maps.googleapis.com/maps/api/staticmap?center=22.4451,88.2989&zoom=15&size=600x3000000000000000000000000000&key=AIzaSyDn9KK_QzvSxIuajjStEjycRiC0Co3fiYc"; 
    	//$url ="https://maps.googleapis.com/maps/api/staticmap?center=-22.441,88.2989&zoom=15&size=600x300&key=AIzaSyDn9KK_QzvSxIuajjStEjycRiC0Co3fiYc";
		
		//_p($ch->http_code);
		//_p(curl_getinfo($ch));die;_p(curl_getinfo($ch));;_p(curl_error(curl_errno($ch)));die('123');
    	//clearstatcache();
			
    }
    /**
    * function is used for storing listinglocation ids those have latitude and longitude values for creation google static maps.
    *@param $locationsData : listings locations data Array
    *@param $oldLocationsData : listing old locations data Array (i.e : incase of edit mode )
    *@param $isNewListing : identfies add / edit flow of listing 
    * @param $updateGoogleUrlForListing
    */
    function logListingForGoogleMap($locationsData,$oldLocationsData,$isNewListing='add',&$updateGoogleUrlForListing,$listingId,$statusFlag)
    {
    	if($isNewListing == 'add')
    	{
    		$this->logListingsForMapIntoQueue($locationsData,$listingId,$statusFlag);
    	}
    	else
    	{
    		$changedLocationsArray = $this->checkLocationInfoChanged($locationsData,$oldLocationsData,$updateGoogleUrlForListing);
    		$updateGoogleUrlForListing = array_keys($changedLocationsArray);
    		error_log('changedLocationsArray'.print_r($changedLocationsArray,true));
    		if(!empty($changedLocationsArray) && count($changedLocationsArray) > 0)
    		{
    			$this->logListingsForMapIntoQueue($changedLocationsArray,$listingId,$statusFlag);		
    		}
    	}
                
    }

    /**
    * function will store listing locations ids into rabbitMQueue along with its latitude and longitude values
    */

    function logListingsForMapIntoQueue($locationsData,$listingId, $statusFlag) 
    {
    	 $locationGeoMapping = array();
        foreach ($locationsData as $locationKey => $locationValue) {
            if(!empty($locationValue['contact_details']['latitude']) && !empty($locationValue['contact_details']['longitude']) && !empty($locationValue['listing_location_id']))
            {
                if( (((double) $locationValue['contact_details']['latitude']) > 8.0000 && ((double)$locationValue['contact_details']['latitude']) <= 37.6000) && ( ((double)$locationValue['contact_details']['longitude']) >= 68.7000 && ((double)$locationValue['contact_details']['longitude']) <= 97.2500))
                {
                	$locationGeoMapping = array('listing_location_id' =>  $locationValue['listing_location_id'],'latitude' => $locationValue['contact_details']['latitude'],'longitude' => $locationValue['contact_details']['longitude'], 'listingId' => $listingId,'status' => $statusFlag);

	                try {
	                $this->config->load('amqp');
	                $this->load->library("common/jobserver/JobManagerFactory");
	                $jobManager = JobManagerFactory::getClientInstance();

	                $locationGeoMapping['logType'] = 'staticMapCreation';
	                $jobManager->addBackgroundJob("GoogleStaticMapData", $locationGeoMapping);
	                        }
	                        catch(Exception $e){
	                            error_log("JOBQException: ".$e->getMessage());
	                        }
                }
            }
        }
    }

    /**
    * will check is thre any location latitude or longitude value has been changed or any new location latitude and longitude value are given
    */

    function checkLocationInfoChanged($locationInfo,$oldLocationInfo)
    {
    	//error_log('changedLocationsArray'.print_r($locationInfo,true));
    	//error_log('changedLocationsArray'.print_r($oldLocationInfo,true));
    	$changedLocationsArray = array();

    	$oldLocationsArray = array();

    	foreach ($oldLocationInfo as $oldKey => $oldValue) {
    		if(!empty($oldValue['listing_location_id']) && !empty($oldValue['contact_details']['latitude']) && !empty($oldValue['contact_details']['longitude']))
    		{
    			$oldLocationsArray[$oldValue['listing_location_id']] = array('listing_location_id' => $value['listing_location_id'],'contact_details' => array('latitude' => $oldValue['contact_details']['latitude'],'longitude' => $oldValue['contact_details']['longitude']));	
    		}
    		
    	}

    	foreach ($locationInfo as $newKey => $newValue) {
    		$newLatitude = $newValue['contact_details']['latitude'];
    		$newLongitude = $newValue['contact_details']['longitude'];
    		$location_listing_id = $newValue['listing_location_id'];
    	
    		if(!empty($location_listing_id) && ($oldLocationsArray[$location_listing_id]['contact_details']['latitude'] != $newLatitude  || $oldLocationsArray[$location_listing_id]['contact_details']['longitude'] != $newLongitude ) && !empty($newLongitude) && !empty($newLatitude))
    		{
    			$changedLocationsArray[$location_listing_id] = array('listing_location_id' => $location_listing_id,'contact_details' => array('latitude' => $newLatitude , 'longitude' => $newLongitude));
    		}
    		unset($oldLocationsArray[$location_listing_id]);
    	}

    	if(!empty($oldLocationsArray) && count($oldLocationsArray) > 0)
    	{
    		$changedLocationsArray += array_merge($changedLocationsArray,$oldLocationsArray);
    	}
    	return $changedLocationsArray;
    }    

    function testInstituteUniversity($listingId) {
		
		$this->load->builder("nationalInstitute/InstituteBuilder");
    	$builder = new InstituteBuilder();
    	$repo = $builder->getInstituteRepository();
    	if($_REQUEST['disableCaching']) {
    		$repo->disableCaching();
    	}
    	$InstituteObj = $repo->find($listingId, 'full');
    	_p($InstituteObj);
    	die('exit');
	}
  function invalidateNginxURLsIntoQueue($listingUrl)
    {
    	//$listingUrl = "http://shikshatest05.infoedge.com/university/university-of-delhi-24642";
    	if(!empty($listingUrl))
    	{
    		$urlQueue = array('seo_url' =>  $listingUrl);
            try {
            $this->config->load('amqp');
            $this->load->library("common/jobserver/JobManagerFactory");
            $jobManager = JobManagerFactory::getClientInstance();

            $urlQueue['logType'] = 'nginxCache';
            $jobManager->addBackgroundJob("NginxCacheUrls", $urlQueue);
                    }
                    catch(Exception $e){
                        error_log("JOBQException: ".$e->getMessage());
                    }
    	}
    }
    function deleteInstituteUniveristyPageOnGoogleCDNcacheAMP($listingIds = array())
    {
    	if(!is_array($listingIds) && count($listingIds) == 0)
    		return;

 		$this->load->builder("nationalInstitute/InstituteBuilder");

    	$instituteBuilder = new InstituteBuilder();
    	$this->instituteRepo = $instituteBuilder->getInstituteRepository();

    	foreach ($listingIds as $key => $instituteId) {
        		$instituteObj = $this->instituteRepo->find($instituteId,array('basic'));
        		if(!empty($instituteObj))
        		{
        			$ampURL = $instituteObj->getAmpURL();
        			if(!empty($ampURL))
        			{
        				updateGoogleCDNcacheForAMP($ampURL,true);
        				error_log('deleted Google CDN Cache For Institute/University ='.$instituteId);
        			}	
        		}
        	}
   	
    }

    function removeInstituteInterLinkingCache($type,$instituteId){
    	$this->init();
    	$instituteIds = explode(',',$instituteId);
    	if(!empty($instituteIds)){
    		$this->nationalinstitutecache->removeInterLinkingCache($type,$instituteIds);
    	}
    	_p('Done');
    }

    function removeInstitutesCache($instituteId){
    	$this->init();
    	$instituteIds = explode(',',$instituteId);
    	if(!empty($instituteIds)){
    		$this->nationalinstitutecache->removeInstitutesCache($instituteIds);
    	}
    	_p('Done');
    }
}