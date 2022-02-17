<?php
   class ApplyHomePage extends MX_Controller{
      
      private $applyHomeLib;
      private $userStatus;
	  private $validateuser;
      private $checkIfLDBUser;
      
      public function __construct()
	  {
         parent::__construct();
         // prepare user data
         $this->userStatus = $this->prepareLoggedInUserData();
         $this->applyHomeLib = $this->load->library('applyHome/ApplyHomeLib');
      }
	  
      private function _prepareTrackingData(&$displayData)   
      {      
         $displayData['beaconTrackData'] = array(
                                              'pageIdentifier' => 'applyHomePage',
                                              'pageEntityId' => '0',
                                              'extraData' => null
                                              );
      }
      
	  /*
	   * controller function for apply home pages
	   * @params : optional param -
	   * 	true if page is rendered below rmc success layer,
	   * 	false otherwise
	   */

	  public function applyHomePage($onRMCSuccessFlag = false){
		 // 404 condition ???
		if($onRMCSuccessFlag == false && getCurrentPageURLWithoutQueryParams() != SHIKSHA_STUDYABROAD_HOME.'/apply'){
			redirect(SHIKSHA_STUDYABROAD_HOME.'/apply', 'location');
		}
		 $displayData = array();
		 $this->applyHomeLib->pickABTestingVariation($displayData);
		 // get top reviews
		 $displayData['topReviews'] = $this->applyHomeLib->getTopCounsellingReviews();
		 $displayData['profEvalCTAText'] = "Book a free profile evaluation call";
		 $displayData['onRMCSuccessFlag'] = $onRMCSuccessFlag;
		 $displayData['seoData'] = $this->applyHomeLib->getApplyHomeSeoData();
		 $displayData['trackForPages'] = true; //For JSB9 Tracking
		 $this->_prepareTrackingData($displayData);
		 //get Study abroad counselling service rating data
		 $displayData['saCounsellingRatingData'] = $this->applyHomeLib->getStudyAbroadCounsellingRatingData();
		 //get study abroad counselling reviews , user data, admitted university
		 $displayData['counsellingReviewData'] = $this->applyHomeLib->getTopCounsellingReviews();
		 //counselling review page link
		 $displayData['saReviewPage'] = $this->applyHomeLib->getSACounsellingReviewPageLink();
		 //get star width for apply home page rating
		 $displayData['starRatingWidth'] = $this->applyHomeLib->getStarRatingWidth(3.2,96,$displayData['saCounsellingRatingData']['overallRating']);
		 $abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
		 $displayData['examMasterList'] 		= $abroadListingCommonLib->getAbroadExamsMasterListFromCache(true);
		 $displayData['successVideoArray'] 		= $this->applyHomeLib->getSuccessStoryWidgetDetails();
		 $displayData['counselorWidgetData'] 	= $this->applyHomeLib->getCounselorWidgetData();
		 if($onRMCSuccessFlag === true)
		 {
		 	$displayData['admissionApplicationData'] = $this->getCounselingStats($onRMCSuccessFlag);
			$render = $this->load->view('applyHomePage/applyHomeOverview',$displayData,true);
			echo $render;
		 }else{
		 	$validateuser = $this->checkUserValidation();
		 	$userId = (isset($validateuser[0]['userid'])) ? $validateuser[0]['userid'] : 0;
		 	$this->setBSBParam($userId);
			$this->load->view('applyHomePage/applyHomeOverview',$displayData);
		 }
		 return ;
	  }

	  private function setBSBParam($userId){
		$this->applyHomeLib->setBSBParam($userId, 'otherPage', 'mobile');
	  }

	  /*
	   * function to parse excel and get cell contents
	   * for counselor reviews
	   */
	  public function readCounselorReviewExcel()
	  {
		 ini_set("memory_limit",'-1');
		 // load php excel library
		 $this->load->library('common/PHPExcel');
		 $studyAbroadCounselorConfig = $this->load->config('studyAbroadCounselorInfoConfig');
		 $counselorInfo = $this->config->item('COUNSELOR_INFO');
		 $objPHPExcel    = new PHPExcel();
		 $excelURL 	= "/var/www/html/shiksha/mediadata/reports/reviews_nov17.xlsx";
		 try {
			$objReader = PHPExcel_IOFactory::createReader("Excel2007");
			$objPHPExcel = $objReader->load($excelURL);
		 } catch (Exception $e) {//var_dump($e);
			die('Error loading file "' . pathinfo($excelURL, PATHINFO_BASENAME) 
			. '": ' . $e->getMessage());
		 }
		 //  Get worksheet dimensions
		 $sheet = $objPHPExcel->getSheet(0);
		 //_p($sheet);die;
		 $highestRow = 216;
		 $tableData = array();

		 $shikshaUserEmails = array();
		 for ($row = 3; $row <= $highestRow; $row++) {
		 	if($sheet->getCell('F'.$row)->getValue()!=''){
		 		$shikshaUserEmails[] = strtolower(trim($sheet->getCell('F'.$row)->getValue()));
		 	}
		 }
		 $model = $this->load->model('applyHome/applyhomepagemodel');
		 $userIds = $model->getUserIdsByEmail($shikshaUserEmails);
		 //_p($userIds);die;
		 //  Loop through each row of the worksheet in turn
		 for ($row = 3; $row <= $highestRow; $row++) {
			//_p($sheet->getCell('U'.$row)->getValue());
			//_p($sheet->getCell('F'.$row)->getValue());
			//_p($sheet->getCell('C'.$row)->getValue());
			//_p($sheet->getCell('M'.$row)->getValue());
			//_p($sheet->getCell('P'.$row)->getValue());
			//_p($sheet->getCell('R'.$row)->getValue());
			//_p($sheet->getCell('A'.$row)->getFormattedValue());
			//_p($sheet->getCell('K'.$row)->getValue());
			//$counselorId = $counselorInfo[$sheet->getCell('U'.$row)->getValue()]['counselorId'];
			$counselorId = $sheet->getCell('C'.$row)->getValue();
			//if($sheet->getCell('U'.$row)->getValue() != ''){
			$emailId = strtolower(trim($sheet->getCell('F'.$row)->getValue()));
			$tableData[] = array(
						'counselorId'			=>$counselorId,
						'anonymousFlag'			=>($emailId===''?1:0),
						'userId'				=>($emailId===''?0:$userIds[$emailId]['userId']),
						'StudentName'			=>($emailId===''?'Anonymous':$userIds[$emailId]['fname'].' '.$userIds[$emailId]['lname']),
						'knowledgeRating'		=>($sheet->getCell('D'.$row)->getValue()===''?NULL:$sheet->getCell('D'.$row)->getValue()),
						'reachabilityRating'	=>($sheet->getCell('I'.$row)->getValue()===''?NULL:$sheet->getCell('I'.$row)->getValue()),
						'overallRating'			=>($sheet->getCell('K'.$row)->getValue()===''?NULL:$sheet->getCell('K'.$row)->getValue()),
						'recommendationRating'	=>($sheet->getCell('L'.$row)->getValue()===''?NULL:$sheet->getCell('L'.$row)->getValue()),
						'addedAt'				=>date_format(date_create_from_format('m/d/Y H:i:s',$sheet->getCell('A'.$row)->getFormattedValue()),'Y-m-d H:i:s'),
						'reviewText'			=>($sheet->getCell('G'.$row)->getValue() == ''||$sheet->getCell('G'.$row)->getValue() == '-'||$sheet->getCell('G'.$row)->getValue() == '.'?NULL:$sheet->getCell('G'.$row)->getValue()),
						'reviewCategory' => 'old'
				  );
			//}
		 }
		 //_p($tableData);die;
		 $model->insertCounselorReviews($tableData);
	  }

	  public function getCounselingStats($onRMCSuccessFlag = false){
	  	if($this->input->is_ajax_request() || $onRMCSuccessFlag === true){
	  		$admissionAppData = $this->applyHomeLib->getAdmissionApplicationStats();
		  	if($onRMCSuccessFlag){
		  		return $admissionAppData;
		  	}
		  	else{
			  	echo json_encode($admissionAppData);
		  	}
	  	}else{
	  		echo 'Error';
	  	}
	  }

   }
