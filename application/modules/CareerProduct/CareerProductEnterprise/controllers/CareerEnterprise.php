<?php
class CareerEnterprise extends MX_Controller {
/*

   Copyright 2013 Info Edge India Ltd

   $Author: Pranjul

   $Id: CareerEnterprise.php

 */

    /*
	 @name: init
	 @description: this is for cms user validation and load CareerEnterpriseModel
	 @param string $userInput: no paramaters
	*/
    function init() {
        $this->userStatus = $this->checkUserValidation();
        if($this->userStatus!='false'){
       	       $usergroup = $this->userStatus[0]['usergroup'];
	       if($usergroup!= "cms"){
   		    header("location:/enterprise/Enterprise/disallowedAccess");
		    exit();
	       }
	}
	$this->load->model('CareerProductEnterprise/careerenterprisemodel');
	$this->careerenterprisemodel = new CareerEnterpriseModel();

	$this->load->library('Tagging/TaggingCMSLib');
    $this->taggingCMSLib = new TaggingCMSLib();
    }
    
    /*
	 @name: index
	 @description: this is default function and redirect user to addCareer tab and also validate user.
	 @param string $userInput: No Input
	*/
    function index(){
        $this->init();
        if($this->userStatus!='false'){
               $usergroup = $this->userStatus[0]['usergroup'];
               if($usergroup == "cms"){
                    $url='/CareerProductEnterprise/CareerEnterprise/addOrEditCareers';
                    header("Location:".$url);
                    exit;
               }else{
		    header("location:/enterprise/Enterprise/disallowedAccess");
        	    exit();
	       }
        }
    }

    function checkForMandatoryImages($careerId,$data){
		$this->load->library('CareerEnterpriseConfig');
		$error_msg_upload = array();
		$result = $this->careerenterprisemodel->checkForMandatoryImagesInDatabase($careerId,CareerEnterpriseConfig::$mandatoryImagesInCareerTable,CareerEnterpriseConfig::$mandatoryImagesInPageValueTable);
		$arrCareerTableImage = CareerEnterpriseConfig::$mandatoryImagesInCareerTable;
		if(empty($result[$arrCareerTableImage[0]]) && empty($data[$arrCareerTableImage[0]]['name'][0])){
		    $error_msg_upload[$arrCareerTableImage[0]] = CareerEnterpriseConfig::$message;
		}
		
		$arrPageValueImage = CareerEnterpriseConfig::$mandatoryImagesInPageValueTable;
		for($i=0;$i<count($arrPageValueImage);$i++){
		    if(empty($result[$arrPageValueImage[$i]]) && empty($data[$arrPageValueImage[$i]]['name'][0])){
			$error_msg_upload[$arrPageValueImage[$i]] = CareerEnterpriseConfig::$message;
		    }   
		}
		return $error_msg_upload;
    }	

    /*
	 @name: populate
	 @description: this is for insert and update input taking from user and save into table as name-value pairs
	 @param string $userInput: No input parameters
	*/

    function populate(){ 
	$this->init();
	$this->load->library('CareerEnterpriseConfig');
	$careerId = $this->input->post('careerId');
	$careerInformationArray  = array();
	//$careerToLDBMappingArray = array();
	$nameValuePairArray      = array();
	$res = $this->checkForMandatoryImages($careerId,$_FILES);
	if(!empty($res)){
	    echo json_encode($res);
	    exit;
	}
	foreach($_POST as $name=>$value){
		if(in_array($name,array('careerId'))){
			$careerInformationArray[$name] = $value;
		}

		/*if(in_array($name,CareerEnterpriseConfig::$ldbCourseVariables))
			$careerToLDBMappingArray[$name] = $value;
		else */if(in_array($name,CareerEnterpriseConfig::$addDetailTabVariables))
			$careerInformationArray[$name] = $value;
		else
			$nameValuePairArray[$name] = $value;
	}
	$uploadFileStatus = $this->_uploadFile($_FILES);
	foreach($uploadFileStatus as $name=>$value){
		if(array_key_exists('0',$value)){ 
			if(in_array($name,CareerEnterpriseConfig::$smallImageIntro))
			{
				$careerInformationArray[$name] =  $value[0]['imageurl'];
			}
			else
			{ 
				if(in_array($name,CareerEnterpriseConfig::$logoImageUrl))
				{
					$nameValuePairArray[$name] = $value[0]['thumburl'];
				}
				elseif(in_array($name,CareerEnterpriseConfig::$largeImageUrl))
				{
					$nameValuePairArray[$name] = $value[0]['thumburl_m'];
				}
				else
				{
					$nameValuePairArray[$name] = $value[0]['imageurl'];
				}
			}
		}
	}

	//HTTPS check in youtube video url
    if (!preg_match("~^(?:f|ht)tps?://~i", $nameValuePairArray['jobProfileATypicalDay']) && $nameValuePairArray['jobProfileATypicalDay'] != '' && $nameValuePairArray['jobProfileATypicalDay'] != '0' && $nameValuePairArray['jobProfileATypicalDay'] != 'Enter YouTube URL') {
			$nameValuePairArray['jobProfileATypicalDay'] = "https://" . $nameValuePairArray['jobProfileATypicalDay'];
	} else if (strpos($nameValuePairArray['jobProfileATypicalDay'], 'http://www.youtube') !== false){
	   	$nameValuePairArray['jobProfileATypicalDay'] = str_replace("http:","https:",$nameValuePairArray['jobProfileATypicalDay']);

	}
	$flag = 0;
	$error_msg_upload = array();
	if(!empty($uploadFileStatus)){
		foreach($uploadFileStatus as $key=>$value){
			if(!array_key_exists('status',$uploadFileStatus[$key])){
					$error_msg_upload[$key] = $uploadFileStatus[$key];
					$flag = 1;
			}else{
				$error_msg_upload[$key] = 'Image uploaded Successfully!!!';
			}
			
		}
	}
	if($flag==1){
		echo json_encode($error_msg_upload);
	}else{
                $count = $this->careerenterprisemodel->checkCareerToLDBMapping($careerId);
                //$this->careerenterprisemodel->insertUpdateCareerToLDBMapping($careerToLDBMappingArray,$careerId);
                $checkIfCareerDetailPageExists = $this->careerenterprisemodel->checkIfCareerDetailPageExists($careerId);
                $this->careerenterprisemodel->updateCareerShortInformation($careerInformationArray);
                $this->careerenterprisemodel->insertUpdateCareerOtherInformation($nameValuePairArray,$careerId);
                if($checkIfCareerDetailPageExists){
                        echo "updated";
                }else{
                        echo "added";
                }
        }

   }
    /*
	 @name: _uploadFile
	 @description: this is for upload file on media server and also validate file type and size and also a private function.
	 @param string $userInput: $_FILES i.e. images
	*/
    private function _uploadFile($fileData){
		$this->load->library('Upload_client');
		$uploadClient = new Upload_client();
					$nameValuePairArray[$name] = $value[0]['thumburl_m'];		$i=0;
		$uploadStatus = array();
                foreach($fileData as $key=>$value) {
			if(!empty($_FILES[$key]['name'][0])){
				$type = $value['type'][0];
				$size = $value['size'][0];
				if(!($type== "image/gif" || $type== "image/jpeg"|| $type=="image/jpg" || $type== "image/png" || $type== "image/pjpeg" || $type== "image/x-png" || $type== "image/pjpg"))
				 {	
				       $uploadStatus[$key] = 'Please upload only jpeg,png,jpg';
				 }
				 else if($size>1048576)
				 {
				        $uploadStatus[$key] = 'Please upload a file of max 1 MB only';
				 }
				 else
				 {	
	                		$uploadStatus[$key] = $uploadClient->uploadFile($appId,'image',$fileData,array(),"-1","career_documents", $key);
				 }
			}
                }
		
		return $uploadStatus;
    }


    function featuredColleges(){
    	$this->init();
		$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');

		$userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $flagMedia = 1;

        $cmsPageArr = array();
        $cmsPageArr['prodId'] = 58;
        $cmsPageArr['careerId'] = $careerId;
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
		$cmsPageArr['careerPageHeader']['careerType'] = 5;
		//$cmsPageArr['careerPageHeader']['subTabType'] = $subTabType;


		$cmsPageArr['careerPageHeader']['careerList'] = $this->careerenterprisemodel->getCareerList();
		
		$this->load->view('CareerProductEnterprise/career_homepage',$cmsPageArr);
    }


    /****************************************
       Function to add or edit a career and display the view.
       Parameters:prodId,subTabType{add/edit}
       *****************************************/
     
    function addOrEditCareers($subTabType){ 
	$this->init();
	$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');
	$userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $flagMedia = 1;

        $cmsPageArr = array();
        $cmsPageArr['prodId'] = 58;
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
	$cmsPageArr['careerPageHeader']['careerType'] = 1;
	$cmsPageArr['careerPageHeader']['subTabType'] = $subTabType;
	$cmsPageArr['careerPageHeader']['careerList'] = $this->careerenterprisemodel->getCareerList();
	$cmsPageArr['careerPageHeader']['expressInterestList'] = $this->careerenterprisemodel->getExpressInterestList();
	$this->load->library('Careers/CareerConfig');
	$streamArray=CareerConfig::$streamVariables;
	$cmsPageArr['careerPageHeader']['streamArray']=$streamArray;
	$cmsPageArr['careerPageHeader']['streamArrayLength'] =count($streamArray);
	$this->load->view('CareerProductEnterprise/career_homepage',$cmsPageArr);
    }
	/*
	 @name: createNewPath
	 @description: this is for creating new path from 'How do i get there' tab.
	 @param string $userInput: pathId,careerId
	*/
    function createNewPath(){
	       $this->init();
	       $arr['careerId'] = $this->input->post('careerId');
	       $arr['pathIdToDisplay'] = $this->input->post('pathIdToDisplay');
	       $pathId = $this->careerenterprisemodel->createPath($arr['careerId']);
	       $arr['pathId'] = $pathId;
	       echo $pathId.':::::'.$this->load->view('CareerProductEnterprise/pageToCreateNewPath',$arr);
    }
    /*
	 @name: removePath
	 @description: this is for remove path from 'How do i get there' tab.
	 @param string $userInput: pathId,careerId
	*/
    function removePath(){
		$this->init();
		$pathId = $this->input->post('pathId');
		$careerId = $this->input->post('careerId');
		$status = $this->careerenterprisemodel->removePath($pathId,$careerId);
		echo $status;
    }
    /*
	 @name: getPathPreviewInformation
	 @description: this is for get the preview of create path in 'How do i get there' tab.
	 @param string $userInput: pathId,careerId,careerName,careerPathInforamtion
	*/
    function getPathPreviewInformation(){
	        $this->init();
		$cmsPageArr['pathId'] = $this->input->post('pathId');
		$cmsPageArr['careerId'] = $this->input->post('careerId');
		$cmsPageArr['displayPathId'] = $this->input->post('displayPathId');
		$cmsPageArr['careerName'] =  $this->careerenterprisemodel->getCareerName($cmsPageArr['careerId']);
		$cmsPageArr['pathImage'] =  $this->careerenterprisemodel->getPathImage($cmsPageArr['careerId']);
		$cmsPageArr['careerPathInformation'] = $this->careerenterprisemodel->getCareerPathInformation($cmsPageArr['careerId']);
		if(empty($cmsPageArr['careerPathInformation'][$cmsPageArr['pathId']]['stepDetails'])){
		    echo 'NoPreview';
		}else{
		    echo $this->load->view('CareerProductEnterprise/preview',$cmsPageArr);   
		}
    }
/*
	 @name: savePathPreviewInformation
	 @description: this is for save the preview of created path in 'How do i get there' tab.
	 @param string $userInput: pathId,careerId
	*/
    function savePathPreviewInformation(){
		$this->init();
		$pathId = $this->input->post('pathId');
		$careerId = $this->input->post('careerId');
		$status = $this->careerenterprisemodel->savePathPreviewInformation($pathId,$careerId);
		echo $status;
    }

  /*
	 @name: saveCareerPathInformation
	 @description: this is for save the Path Information i.e Step Title,Step Description,Step Order and CareerId
	 @param string $userInput: pathId,careerId,stepTitle,stepDescription,stepOrder
	*/  
    function saveCareerPathInformation(){
		$this->init();
	        $pathId = $this->input->post('pathId');
	        $careerId = $this->input->post('careerId');
	        $stepTitle = $this->input->post('stepTitle');
	        $stepDescription = $this->input->post('stepDescription');
	        $stepOrder = $this->input->post('stepOrder');
		$this->careerenterprisemodel->saveCareerPathInformation($pathId,$stepTitle,$stepDescription,$stepOrder,$careerId);
    }
    /*
	 @name: displayHowDoIGetThereMappingTab
	 @description: this is for display the "How Do I Get There Mapping" Tab and Save Mapping of Career Id with path.
	 @param string $userInput: prodId,tabNumber,careerId
	*/
    function displayHowDoIGetThereMappingTab($tabNumber,$careerId){
	$this->init();
	$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');
	$userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $flagMedia = 1;

        $cmsPageArr = array();
        $cmsPageArr['prodId'] = 58;
	$cmsPageArr['careerId'] = $careerId;
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
	$cmsPageArr['careerPageHeader']['careerType'] = 3;
	if($careerId!='0' && !empty($careerId)){
		$this->careerenterprisemodel->setDefaultCareerPath($careerId);
		$cmsPageArr['careerName'] =  $this->careerenterprisemodel->getCareerName($careerId);
		$cmsPageArr['careerPathInformation'] = $this->careerenterprisemodel->getCareerPathInformation($careerId);
		$cmsPageArr['maxCareerPath'] = $this->careerenterprisemodel->getMaxPath($careerId);
	}
	$cmsPageArr['careerList'] = $this->careerenterprisemodel->getCareerList();
	$this->load->view('CareerProductEnterprise/career_homepage',$cmsPageArr);
    }
 /*
	 @name: removeCustomFieldInPathTab
	 @description: this is for to remove Custom field section in the "How Do I Get There Mapping" Tab.
	 @param string $userInput: pathId,stepOrder,careerId
	*/
    function removeCustomFieldInPathTab(){
	$this->init();
	$pathId = $this->input->post('pathId');
	$stepOrder = $this->input->post('stepOrder');
	$careerId = $this->input->post('careerId');
	$this->careerenterprisemodel->removeCustomFieldInPathTab($pathId,$stepOrder,$careerId);
    }
 /*
	 @name: displayCareerDetailTab
	 @description: this is for display "Career Details" tab and to save career product data and also to create mapping between career and ldb courses.
	 @param string $userInput: prodId,tabNumber,careerId
	*/
    function displayCareerDetailTab($tabNumber,$careerId){ 
	$this->init();
	$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');
	$userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $flagMedia = 1;

        $cmsPageArr = array();
        $cmsPageArr['prodId'] = 58;
	$cmsPageArr['careerId'] = $careerId;
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
	$cmsPageArr['careerPageHeader']['careerType'] = $tabNumber;

	/*$this->load->builder('CategoryBuilder','categoryList');
	$categoryBuilder = new CategoryBuilder;
	$repository = $categoryBuilder->getCategoryRepository();
	$mainCategories = $repository->getSubCategories(1,'');
	for($i=0;$i<count($mainCategories);$i++){
		$subCatDetails = $repository->getSubCategories($mainCategories[$i]->getId(),'national');
		$catSubcatCourseList[$mainCategories[$i]->getId()]['name'] = $mainCategories[$i]->getName();
		for($k=0;$k<count($subCatDetails);$k++){
			$catSubcatCourseList[$mainCategories[$i]->getId()]['subcategories'][$subCatDetails[$k]->getId()]['catId'] = $subCatDetails[$k]->getId();
			$catSubcatCourseList[$mainCategories[$i]->getId()]['subcategories'][$subCatDetails[$k]->getId()]['catName'] = $subCatDetails[$k]->getName();
			$catSubcatCourseList[$mainCategories[$i]->getId()]['subcategories'][$subCatDetails[$k]->getId()]['parentName'] = $mainCategories[$i]->getName();
			$catSubcatCourseList[$mainCategories[$i]->getId()]['subcategories'][$subCatDetails[$k]->getId()]['parentId'] = $mainCategories[$i]->getId();
			$this->load->builder('LDBCourseBuilder','LDB');
             		$LDBCourseBuilder = new LDBCourseBuilder;
             		$LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
		        $LDBCoursesOfthisSubcategory = $LDBCourseRepository->getLDBCoursesForSubCategory($subCatDetails[$k]->getId());
			for($m=0;$m<count($LDBCoursesOfthisSubcategory);$m++){
				$catSubcatCourseList[$mainCategories[$i]->getId()]['subcategories'][$subCatDetails[$k]->getId()]['courses'][$m]['SpecializationId'] = $LDBCoursesOfthisSubcategory[$m]->getId();
				$catSubcatCourseList[$mainCategories[$i]->getId()]['subcategories'][$subCatDetails[$k]->getId()]['courses'][$m]['SpecializationName'] = $LDBCoursesOfthisSubcategory[$m]->getSpecialization();
				if(!($LDBCoursesOfthisSubcategory[$m]->getSpecialization() == 'All' || $LDBCoursesOfthisSubcategory[$m]->getSpecialization() == 'ALL'|| $LDBCoursesOfthisSubcategory[$m]->getSpecialization() == '')){
					$catSubcatCourseList[$mainCategories[$i]->getId()]['subcategories'][$subCatDetails[$k]->getId()]['courses'][$m]['CourseName'] = $LDBCoursesOfthisSubcategory[$m]->getCourseName() ." ". $LDBCoursesOfthisSubcategory[$m]->getSpecialization();
				}else{
					$catSubcatCourseList[$mainCategories[$i]->getId()]['subcategories'][$subCatDetails[$k]->getId()]['courses'][$m]['CourseName'] = $LDBCoursesOfthisSubcategory[$m]->getCourseName();
				}
				if($LDBCoursesOfthisSubcategory[$m]->getCourseLevel1() == 'PG' && $LDBCoursesOfthisSubcategory[$m]->getScope() == 'abroad'){
					$catSubcatCourseList[$mainCategories[$i]->getId()]['subcategories'][$subCatDetails[$k]->getId()]['courses'][$m]['CourseName'] = $LDBCoursesOfthisSubcategory[$m]->getCourseName() . " (" . $val1->getCourseLevel1() . ")" ;
				}
				$catSubcatCourseList[$mainCategories[$i]->getId()]['subcategories'][$subCatDetails[$k]->getId()]['courses'][$m]['categoryID'] = $subCatDetails[$k]->getId();
			}
		}
		
	}
	$cmsPageArr['careerPageHeader']['catSubcatCourseList'] = $catSubcatCourseList;*/
	$cmsPageArr['careerPageHeader']['careerList'] = $this->careerenterprisemodel->getCareerList();
	$cmsPageArr['careerPageHeader']['careerData'] = $this->careerenterprisemodel->getCareerData($careerId);
	/*$result = $this->careerenterprisemodel->checkCareerToLDBMapping($careerId);
	for($i=0;$i<count($result);$i++){
		$categoryToLDBCourseArr[] = $repository->getCategoryByLDBCourse($result[$i]['ldbCourseId']);	
		
	}	
	for($j=0;$j<count($categoryToLDBCourseArr);$j++){
			$formatCategoryToLDBCourseArr[$j]['LDBCourseID'] = $result[$j]['ldbCourseId'];
			$formatCategoryToLDBCourseArr[$j]['parentId'] = $categoryToLDBCourseArr[$j]->getParentId();
			$formatCategoryToLDBCourseArr[$j]['categoryID'] = $categoryToLDBCourseArr[$j]->getId();
	}
	$cmsPageArr['ldbMappedCourses'] = $formatCategoryToLDBCourseArr;*/
	$this->load->view('CareerProductEnterprise/career_homepage',$cmsPageArr);
    }

    function publishCareerData(){
	$this->init();
	$this->careerenterprisemodel->publishCareerData($careerId);
    }

    function removeCourseIdFromEarning(){
	$this->init();
	$careerId = $this->input->post('careerId');
	$courseName = $this->input->post('courseName');
	$this->careerenterprisemodel->removeCourseIdFromEarning($careerId,$courseName);
    }

    function removeSectionForPrestigiousInstitute(){
	$this->init();
	$careerId = $this->input->post('careerId');
	$location = $this->input->post('location');
	$sectionNumber = $this->input->post('sectionNumber');
	$this->careerenterprisemodel->removeSectionForPrestigiousInstitute($careerId,$location,$sectionNumber);
    }
  /*
	 @name: removeSkill
	 @description: this is for to remove required skills from Job Profile Section.
	 @param string $userInput: careerId,position,updatedString,sectionName
	*/ 
    function removeSkill(){
	$this->init();
	$careerId = $this->input->post('careerId');
	$position = $this->input->post('position');
	$newSkillRequiredString = $this->input->post('newSkillRequiredString');
	$this->careerenterprisemodel->removeSkill($careerId,$position,$newSkillRequiredString);
   }
  /*
	 @name: removeCustomFields
	 @description: this is for to remove custom fields in different sections.
	 @param string $userInput: careerId,position,updatedString,sectionName
	*/ 
   function removeCustomFields(){
	$this->init();
	$careerId = $this->input->post('careerId');
	$counter = $this->input->post('position');
	$updatedString = $this->input->post('updatedString');
	$sectionName = $this->input->post('sectionName');
	$this->careerenterprisemodel->removeCustomFields($careerId,$counter,$updatedString,$sectionName);
   }
/*
	 @name: getCustomField
	 @description: this is for to display custom fields in different sections.
	 @param string $userInput: careerId,countOfSection,sectionName
	*/
   function getCustomField(){
	$this->init();
	$cmsPageArr['countOfSection'] = $this->input->post('countOfSection');
	$cmsPageArr['careerId'] = $this->input->post('careerId');
	$cmsPageArr['sectionName'] = $this->input->post('sectionName');
	echo $this->load->view('CareerProductEnterprise/customFields',$cmsPageArr);
   }
/*
	 @name: getSection
	 @description: this is for to display section for india and abroad sections in "Where to study" section.
	 @param string $userInput: careerId,countOfSection,updatedString,finalValue
	*/
   function getSection(){
	$this->init();
 	$cmsPageArr['countOfSection'] = $this->input->post('countOfSection');
	$cmsPageArr['careerId'] = $this->input->post('careerId');
	$cmsPageArr['region'] = $this->input->post('region');
	$cmsPageArr['finalValue'] = $this->input->post('finalValue');
	$cmsPageArr['nextSectionId'] = $this->input->post('nextSectionId');
	echo $this->load->view('CareerProductEnterprise/pageSection',$cmsPageArr);
   }
/*
	 @name: removeSection
	 @description: this is for delete section from india and abroad sections in "Where to study" section.
	 @param string $userInput: careerId,position,updatedString,region,sectionName
	*/
   function removeSection(){
	$this->init();
	$careerId = $this->input->post('careerId');
	$counter = $this->input->post('position');
	$updatedString = $this->input->post('updatedString');
	$region = $this->input->post('region');
	$sectionName = $this->input->post('sectionName');
	$this->careerenterprisemodel->removeSection($careerId,$counter,$updatedString,$region,$sectionName);
   }
/*
	 @name: removeCourseIdForSection
	 @description: this is for delete course id from india and abroad sections in "Where to study" section.
	 @param string $userInput: careerId,position,updatedString,region,sectionName
	*/
   function removeCourseIdForSection(){
	$this->init();
	$careerId = $this->input->post('careerId');
	$counter = $this->input->post('position');
	$updatedString = $this->input->post('updatedString');
	$region = $this->input->post('region');
	$sectionName = $this->input->post('sectionName');
	$this->careerenterprisemodel->removeCourseIdForSection($careerId,$counter,$updatedString,$region,$sectionName);
   }

   /***********************************************************
       Function to get career to stream interest mapping for selected career.
       ***********************************************************/
    
    function getStreamExpInterestMappingForSelectedCareer(){
	$careerId=$this->input->post('careerId');
	$this->init();
	$results=$this->careerenterprisemodel->getStreamExpInterestMappingForSelectedCareer($careerId);
	echo json_encode($results);
    }
    
       /***********************************************************
       Function to insert/update career to stream interest mapping.
       ***********************************************************/
    
    function createOrEditCareerToStreamExpInterestMapping(){
	        $this->init(); 
 	        $data['mandatorySubject'] = $this->input->post('mandatorySubject'); 
 	        $data['oldcareerName'] = $this->input->post('oldcareerName'); 
 	        $data['careerName'] = $this->input->post('careername'); 
 	        $data['stream'] = $this->input->post('stream'); 
 	        $data['expressInterest1'] = $this->input->post('expressInterest1'); 
 	        $data['expressInterest2'] = $this->input->post('expressInterest2'); 
 	        $data['difficultyLevel'] = $this->input->post('difficultyLevel'); 
 	        $data['action'] = $this->input->post('action'); 

 	        // TAGS CMS CALL
 	        if($data['action'] == "edit"){
 	        	 $careerId = $this->input->post('careerid');
 	        	 $extraData = array();
 	        	 $extraData['newName'] = $data['careerName'];
 	        	 $this->taggingCMSLib->addTagsPendingMappingAction("Careers",$careerId,'Edit',$extraData);
 	        }
 	        $careerId = $this->careerenterprisemodel->addOrEditCareerToStreamInterestMapping($data); 
 	        if($careerId!=0){ 
	            if($data['action']=='delete'){ 
	            	$this->taggingCMSLib->addTagsPendingMappingAction("Careers",$careerId,'Delete');
	                echo json_encode(array("status"=>"3","careerId"=>$careerId)); 
	            }elseif($data['action']=='edit' ){ 
	                echo json_encode(array("status"=>"2","careerId"=>$careerId)); 
	            }else{ 
	            	$this->taggingCMSLib->addTagsPendingMappingAction("Careers",$careerId,'Add');
	                echo json_encode(array("status"=>"1","careerId"=>$careerId)); 
	            } 
	        }else{ 
                 echo json_encode(array("status"=>"0","careerId"=>0)); 	
 	        } 
    }
    
    function clearText(){
	$this->init();
	$careerId = $this->input->post('careerId');
	$keyname = $this->input->post('keyname');
	$res = $this->careerenterprisemodel->clearText($careerId,$keyname);
	echo $res;
    }
    
    function clearTextForPrestigiousInstitute(){
	$this->init();
	$careerId = $this->input->post('careerId');
	$keyname1 = $this->input->post('keyname1');
	$keyname2 = $this->input->post('keyname2');
	$res = $this->careerenterprisemodel->clearTextForPrestigiousInstitute($careerId,$keyname1,$keyname2);
	echo $res;
    }
	
    function displayInterLinkingTab($tabNumber,$careerId){
	$this->init();
	$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');
	$userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $flagMedia = 1;
	
        $cmsPageArr = array();
        $cmsPageArr['prodId'] = 58;
	$cmsPageArr['careerId'] = $careerId;
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
	$cmsPageArr['careerPageHeader']['careerType'] = $tabNumber;
	$cmsPageArr['careerPageHeader']['careerType'] = 4;
    
	
	
	$cmsPageArr['careerPageHeader']['careerList'] = $this->careerenterprisemodel->getCareerList();
	$cmsPageArr['careerPageHeader']['careerData'] = $this->careerenterprisemodel->getCareerData($careerId);//_p($cmsPageArr['careerPageHeader']['careerData']);die;
	$this->load->view('CareerProductEnterprise/career_homepage',$cmsPageArr);
    }
    
    /*
	 @name: saveInterLinkingInformation
	*/  
    function saveInterLinkingInformation(){
	$this->init();
	$this->load->library('CareerEnterpriseConfig');
	$careerId = $this->input->post('careerId');
	$careerToLDBMappingArray = array();
	$nameValuePairArray      = array();
	foreach($_POST as $name=>$value){
	    if(in_array($name,CareerEnterpriseConfig::$ldbCourseVariables)){
		    $careerToLDBMappingArray[$name] = $value;
	    }
	    else{
		 if($name!='careerId'){
		    if(!in_array('checkboxLinks',$_POST)){
			$nameValuePairArray['checkboxLinks'] = 'false';
			$nameValuePairArray[$name] = $value;
		    }else{
			$nameValuePairArray[$name] = $value;
		    }
		 }
	    }

	}

	$this->saveCareerAttributeMapping($careerId);
//	$this->_saveLDBToCareerMapping($careerToLDBMappingArray,$careerId);
//	$this->careerenterprisemodel->insertUpdateCareerOtherInformation($nameValuePairArray,$careerId);
    }
    
    private function saveCareerAttributeMapping($careerId){
	$this->init();
	$career_lib = $this->load->library('CareerEnterpriseConfig');
	$courseIds = array();
	$hierMappData = array();
	$streamIds = isset($_POST['stream'])?$this->input->post('stream'):array();
	$subStreamIds = isset($_POST['subStream'])?$this->input->post('subStream'):array();
	$specIds = isset($_POST['specialization'])?$this->input->post('specialization'):array();
	$action = isset($_POST['actn_frm'])?$this->input->post('actn_frm'):'';
	for($i=0; $i<count($streamIds); $i++){    			 //Make hierarchy array with streams, subStreams and SpecId
		$hierArr[$i]['streamId'] = $streamIds[$i];
		if(empty($subStreamIds[$i])) {
  			$hierArr[$i]['substreamId'] = 'none';
  		}
  		else{
  			$hierArr[$i]['substreamId'] = $subStreamIds[$i];
  		}
  		if(empty($specIds[$i])) {
  			$hierArr[$i]['specializationId'] = 'none';
  		}
  		else{
  			$hierArr[$i]['specializationId'] = $specIds[$i];
  		}
	}
	foreach ($hierArr as $key => $val) {
		$crsIndex = $key + 1;
		if(!isset($_POST['course_'.$crsIndex])) {
			$courseIds[] = 0;
		}else if(isset($_POST['course_'.$crsIndex]) && $action == 'edit'){
			$editArrCourse = $this->input->post('course_'.$crsIndex);
			$courseIds[] = $editArrCourse[0];
		}else{
		$courseIds[] = $this->input->post('course_'.$crsIndex);

		}
	}
	$dbInsertArr = $career_lib->getFinalCareerArrayToInsert($hierArr, $careerId, $courseIds);
	if($action == 'edit'){
		$transStatus = $this->careerenterprisemodel->updateCareerAttrMapping($careerId);
	}
	if($action == 'add' || ($action == 'edit' && $transStatus == 1)){
		$this->careerenterprisemodel->insertCareerAttrMapping($dbInsertArr);
    }
}
        /*
	 @name: saveLDBToCareerMapping
	*/  
    private function _saveLDBToCareerMapping($careerToLDBMappingArray,$careerId){
	$this->init();
	$this->careerenterprisemodel->insertUpdateCareerToLDBMapping($careerToLDBMappingArray,$careerId);
    }
    
    function removeLDBCourseFromMapping(){
	$this->init();
	$careerId = $this->input->post('careerId');
	$ldbCourseId = $this->input->post('ldbCourseId');
	$this->careerenterprisemodel->removeLDBCourseFromMapping($careerId,$ldbCourseId);
	echo '1';
    }

    function addFeaturedColleges(){
    	$this->init();

    	$careerId = $this->input->post('careerId');
    	$clientId = $this->input->post('clientId');
    	$title = $this->input->post('title');
    	$url = $this->input->post('url');
    	$status = $this->input->post('status');
    	$rowOrder = $this->input->post('rowOrder');

    	$rowId = $this->careerenterprisemodel->addFeaturedColleges($careerId,$clientId,$title,$url,$status, $rowOrder);

    	echo $rowId;
    }

    function getFeaturedCollegesData($career){
    	$this->init();

    	$career = $this->input->post('career');

    	$data = $this->careerenterprisemodel->getFeaturedCollegesData($career);
    	
    	echo json_encode($data);	
    }

    function removeFeaturedColleges($rowId){
    	$this->init();

    	$rowId = $this->input->post('rowId');

    	// $this->careerenterprisemodel->addFeaturedColleges($career,$clientId,$title,$url);
    }

    function updateFeaturedColleges($rowId){
    	$this->init();

    	$rowId = $this->input->post('rowId');

    	$this->careerenterprisemodel->updateFeaturedColleges($rowId);
    	echo "";
    }

    function getCareerHierarchyData($careerId){
    	$this->init();
    	$result = $this->careerenterprisemodel->getCareerHierarchyData($careerId);
    	return $result;

    }

    function addRemoveCareerFromSearch(){
        $careerId = $this->input->post('careerid');
        $action = $this->input->post('action');
        if($action == 'added'){
            Modules::run('search/Indexer/addToQueue', $careerId, 'career');
        }else if($action == 'updated'){
        	Modules::run('search/Indexer/addToQueue', $careerId, 'career', 'delete');
        	Modules::run('search/Indexer/addToQueue', $careerId, 'career');
    	}else{
            Modules::run('search/Indexer/addToQueue', $careerId, 'career', 'delete');
        }
    }
}
?>
