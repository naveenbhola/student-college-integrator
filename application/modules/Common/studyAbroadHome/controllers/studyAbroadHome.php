<?php

	class studyAbroadHome extends MX_Controller {
		private $abroadCommonLib;
		private $studyAbroadHomepageLibrary;
		private $applyContentLib;
		private $guideCount = 9;

		function _init(&$displayData)
		{
			$this->load->helper('image');
			$this->load->helper('string');
			$this->abroadCommonLib  = $this->load->library('listingPosting/AbroadCommonLib');
			$this->studyAbroadHomepageLibrary = $this->load->library('studyAbroadHomepageLibrary');
			$this->mobileSAHomepageLib = $this->load->library('homePage/HomePageLib');
			$this->applyContentLib = $this->load->library('applyContent/ApplyContentLib');
			$this->sacontentmodel = $this->load->model('blogs/sacontentmodel');

			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$this->abroadCourseRepository       = $listingBuilder->getAbroadCourseRepository();
			$this->abroadInstituteRepository    = $listingBuilder->getAbroadInstituteRepository();			
			$this->abroadUniversityRepository   = $listingBuilder->getUniversityRepository();

			$displayData['trackForPages'] = true;
			$displayData['firstFoldCssPath'] = 'studyAbroadHome/css/firstFoldCss';
		}

		function homepage(){
			$this->_validateUrl();
			$this->_init($displayData);
//			$this->_getCollegeSearchForm($displayData);
			$displayData['coverage'] = $this->studyAbroadHomepageLibrary->getCountStats();
			$displayData['courses'] = $this->getMostViewedCourses();
			$displayData['topGuides'] = $this->getGuidesDataForHomePage();
			$displayData['applyContent'] = $this->getApplyContentData();
			$this->studyAbroadHomepageLibrary->prepareTrackingData($displayData);
			$displayData['marketingFormRegistrationData'] = $this->_getMarketingFormRegistrationData();
			$this->load->view('abroadhomepage', $displayData);
		}
		
		private function _validateUrl(){
			$sourceString = $_GET['utm_source'];
			$sourceFlag = (!empty($sourceString)) ? true : false;
			if(SHIKSHA_STUDYABROAD_HOME != trim(getCurrentPageURL(),'/') && ENVIRONMENT != 'development' && !($sourceFlag)){
				$url = SHIKSHA_STUDYABROAD_HOME;
				header("Location: $url",TRUE,301);
				exit;
			}
		}
		
//		private function _getCollegeSearchForm(& $displayData){
//			$this->studyAbroadHomepageLibrary->populateAbroadCountries($displayData);
//			$displayData['desiredCourses'] = $this->abroadCommonLib->getAbroadMainLDBCourses();
//			$displayData['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();
//			$displayData['levelOfStudy']  = $this->abroadCommonLib->getAbroadCourseLevelsForFindCollegeWidgets();
//			$displayData['fees'] = $GLOBALS['CP_ABROAD_FEES_RANGE']['ABROAD_RS_RANGE_IN_LACS'];
//			$courseFullNameMapping = array("MBA"=>"Masters of business","MS"=>"Masters of science","BE/Btech"=>"Bachelors of engg");
//			foreach($displayData['desiredCourses'] as $key=>$course) {
//				$displayData['desiredCourses'][$key]['CourseFullName'] = $courseFullNameMapping[$course['CourseName']];
//			}
//		}

		private function _getMarketingFormRegistrationData(){
			$marketingFormRegistrationId = $_REQUEST['mfmrg'];
			$this->load->model('mailer/marketingFormmailermodel');
			$marketingFormRegistrationData = $this->marketingFormmailermodel->getMarketingFormRegistrationData($marketingFormRegistrationId);
			return $marketingFormRegistrationData;
		}

		function getGuidesDataForHomePage(){
			$guideIds = array();
			$data =  array();
			$guides = $this->mobileSAHomepageLib->getGuidesForHomePage($this->guideCount);
			$guideIds = array_map(function ($ar) {return $ar['content_id'];}, $guides);
			$data['guides']= $guides;
			$data['downloadCounts']= $this->sacontentmodel->downloadCountForGuide($guideIds);
			unset($guideIds);
			return $data;
		}

		function getApplyContentData(){
			$this->config->load('abroadApplyContentConfig');
			$applyContentTypes = $this->config->item("applyContentMasterList");		
			$dataArray = array_keys($applyContentTypes);
			$data = $this->applyContentLib->getApplyContentHomePageUrl($dataArray);
			return $data;
		}
		
		function getMostViewedCourses(){
			$courseObjectData = array();
			$data = $this->studyAbroadHomepageLibrary->getMostViewedCoursesData();
			$i=0;
			foreach ($data as $courseData){
				$courseId 			    = $courseData['courseId'];
				$courseDataFound                    = $this->abroadCourseRepository->find($courseId);
				if(!isValidAbroadCourseObject($courseDataFound)){
					continue;
				}
				if($courseDataFound->getInstId()){
					$departmentData = $this->abroadInstituteRepository->find($courseDataFound->getInstId());
					if($departmentData->getUniversityId()){
						$universityData = $this->abroadUniversityRepository->find($departmentData->getUniversityId());
					}elseif($courseDataFound->getUniversityType()=='college') {
						$universityData = $this->abroadUniversityRepository->find($courseDataFound->getUniversityId());
					}
					$photos = $universityData->getPhotos();
					if(isset($photos[0])){
						$logoImage = $photos[0]->getThumbURL('172x115');
						$altImage = $photos[0]->getName();
					}else{
						$logoImage = SHIKSHA_HOME."/public/images/defaultCatPage1.jpg";
						$altImage = $courseDataFound->getName();
					}
				}
				$categoryPageRequest = $this->load->library('categoryList/AbroadCategoryPageRequest');
				$courseObjectData[$i]['courseObj'] = $courseDataFound;
				$courseObjectData[$i]['logoImage'] = $logoImage;
				$courseObjectData[$i]['altImage'] = $altImage;
				$courseObjectData[$i]['countryName'] = $courseData['countryName'];
				$countrySEOData = $categoryPageRequest->getSeoInfoForCountryPage($courseDataFound->getCountryId(), 1);
				$courseObjectData[$i]['countryPageURL']	= $countrySEOData['url'];
				$courseObjectData[$i]['universityURL'] = $universityData->getURL();
				$i++;
			}
			return $courseObjectData;						
		}

		public function getSearchV2Layer(){
			$prefillData = $this->input->post('prefillData',true);
			echo $this->load->view('studyAbroadCommon/searchV2Layer', array('prefillData'=>$prefillData), true);
		}
	} ?>

