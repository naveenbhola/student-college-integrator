<?php
	/**
	 * 
	 */
	class MBAPredictorLib
	{

		private $iimpredictormodel;

		private $userId;
		
		function __construct()
		{
			$this->CI =& get_instance();
			$this->iimpredictormodel = $this->CI->load->model('IIMPredictor/iimpredictormodel');
			$this->eligibilityLib = $this->CI->load->library('IIMPredictor/IIMEligibilityLib');
			$this->scoreLib = $this->CI->load->library('IIMPredictor/IIMScoreLib');
		}

		function getBoardsAndGraduationStreamsData(){
			$returnData = $this->iimpredictormodel->getBoardsAndGraduationStream();
			$simplifiedData = $this->_getSimplifiedData($returnData);
			return $simplifiedData;
		}
		private function _getSimplifiedData($returnData){
			$board  = $returnData["board"];
			$graduationStream = $returnData["graduation_stream"];
			$boardSimplified = array();
			$graduationStreamSimplified = array();
			foreach ($board as $key => $boardName) {
				$boardSimplified[$boardName["board_alias"]] = $boardName["board_name"];
			}
			$boardSimplified = array('CISCE' => $boardSimplified['CISCE']) + $boardSimplified;
			$boardSimplified = array('CBSE' => $boardSimplified['CBSE']) + $boardSimplified;

			foreach ($graduationStream as $key => $graduationStreamName) {
				$graduationStreamSimplified[$graduationStreamName["stream_alias"]] = $graduationStreamName["stream_name"];
			}
			$simplifiedData["board"] = $boardSimplified;
			$simplifiedData["graduation_stream"] = $graduationStreamSimplified;
			return $simplifiedData;
		}

		private function makeUpInputData($data){
			/* For IIM Ahmedabad, need to add a variable which holds the average of 10th and 12th percentage */
			$data['X_XII_avg'] = ($data['xthPercentage'] + $data['xiithPercentage']) /2;
	        
	        /*Calculating total experience in months */
	        if(isset($data['expYear']) && isset($data['expMonth'])){
	        	$data['experience'] = 12*$data['expYear'] + $data['expMonth'];
	        }
			
	        if(empty($data['experience'])){
	        	$data['experience'] = 0;
	        } 

	        $data['specialCase'] ='gender-relaxation-'.$data['gender'];

	        return $data;

		}

		public function startIIMPredictor($data = array(),$userId,$year,$pageNo,$isFetchScore=true,$limit,$resultType="eligibility"){
			if(!empty($data)){
			$data = $data;	
			}else{
	        	$data = $this->CI->security->xss_clean($_POST); 
	        	$data = $this->makeUpInputData($data);
			}
			if(empty($data) || !in_array($resultType, array('eligibility','inEligibility'))) {
				return;
			}

	        $predictorData = array();

	        $predictorData['userData'] = $data;
	        $eligibilityList = $this->eligibilityLib->checkEligbilityForColleges($data,$year); 
	        $fetchInEligibleInfo = true;
	        if($isFetchScore){
	        	$inEligibilityList = $this->eligibilityLib->checkInEligibilityForColleges($data,$year,$fetchInEligibleInfo);
	        	if($resultType == "eligibility" && !empty($eligibilityList)) {
	        		$predictorData['scoreData']['eligibility'] = $this->scoreLib->getScorePredictorForColleges($eligibilityList, $data,$year,$pageNo,$limit);	
	        	}
	        	else if(($resultType == "eligibility" && empty($eligibilityList)) || ($resultType == "inEligibility" && !empty($inEligibilityList))) {
	        		$predictorData['scoreData']['inEligibility'] = $this->scoreLib->getInEligibityCollegesInfo($inEligibilityList, $data,$year,$pageNo,$limit);	
	        	}	        	
	        	$predictorData['inEligibilityCount'] = count($inEligibilityList);
	        }
	        if(!$isFetchScore){
	        	$user_tracking_data = $this->_formatUserTrackingData($data, $predictorData,$userId);
	        }
	        $predictorData['eligibilityCount'] = count($eligibilityList);

	        $returnData = array();
	        $returnData['predictorData'] = $predictorData;
	        $returnData['userData'] = $data;
	        if(!empty($user_tracking_data) && count($user_tracking_data) > 0){
	        	$returnData['user_tracking_data'] = $user_tracking_data;
	        }

	        return $returnData;
		}
		private function _formatUserTrackingData($userData, $predictordata,$userId){
			$user_tracking_data = array();

	        if($userId > 0){
				$user_tracking_data['userId'] = $userId;
	        }else{
				$user_tracking_data['userId'] = 0;
	        }
			$user_tracking_data['newRegistration'] = 'NO';

			$trackingFields = array('category', 'gender', 'xthBoard', 'xiithBoard', 'xthPercentage', 'xiithPercentage', 'graduationPercentage', 'xiithStream', 'graduationStream', 'graduationYear', 'experience', 'VRC_Percentile', 'DILR_Percentile', 'QA_Percentile', 'cat_total', 'cat_percentile');
			$fields = array_intersect($trackingFields, array_keys($userData));

			foreach($fields as $row=>$value){
				$user_tracking_data[$value] = $userData[$value];
			}

			/*$inEligibility = array_keys($predictordata['eligibility']['nonEligibilityData']);
			foreach ($inEligibility as $key => $value) {
				$user_tracking_data[$value] = 'NO';
			}
			if(empty($inEligibility)){
				$inEligibility = array();
			}

			$eligibility = array_diff($this->activeIIM, $inEligibility);

			foreach($eligibility as $key=>$value){
				$user_tracking_data[$value] =$predictordata['scoreData']['IIMWithData'][$value]['Total_Percentile'];
			}*/

			if(isMobileRequest()){
				$user_tracking_data['Device_Type'] = 'Mobile';
			} else{
				$user_tracking_data['Device_Type'] = 'Desktop';
			}
			

			$this->CI->load->helper('utility_helper');

			$visitorSessionId = getVisitorSessionId();
			$user_tracking_data['Visitor_session'] = $visitorSessionId;

			return $user_tracking_data;
		}



		function getEligibilityCount($userId,$year) {

			$predictordataArray = $this->startIIMPredictor(array(),$userId,$year,0,false);
			$data = $predictordataArray['predictorData'];
			$userData = $predictordataArray['userData'];
			$user_tracking_data = $predictordataArray['user_tracking_data'];
			//_p($predictordataArray);die;
			$returnData = array();
			$returnData['count'] = $data['eligibilityCount'];
			$returnData['userDataId'] = $this->_storeICPUserdata($user_tracking_data);
			//_p($returnData);die;
	        $this->createCookie($returnData['userDataId']);
	        /*foreach ($data['eligibility']['nonEligibilityData'] as $instituteKey => $instituteData) {
	        		$returnData['count_ineligible'] = count($data['eligibility']['nonEligibilityData']);
					$returnData['nonEligibilityData']['IIMName'] = $instituteKey;
					$returnData['nonEligibilityData']['IIMData'] = $instituteData;
					break;
			}

			foreach ($data['scoreData']['IIMWithData'] as $instituteKey => $instituteData) {
	        		$returnData['count_eligible'] = count($data['scoreData']['IIMWithData']);
					$returnData['eligibilityData']['IIMName'] = $instituteKey;
					$returnData['eligibilityData']['IIMData'] = $instituteData;
					break;
			}	

			foreach ($data['scoreData']['IIMWithOutData'] as $instituteKey => $instituteData) {
	        		$returnData['count_withoutData'] = count($data['scoreData']['IIMWithOutData']);
					$returnData['eligibilityWithoutData']['IIMName'] = $instituteKey;
					$returnData['eligibilityWithoutData']['IIMData'] = $instituteData;
					break;
			}*/
			$returnData['userData'] = $userData;
	        $this->_updateCATScore($userId);
			return $returnData;
		}
		private function _updateCATScore($userId){
			if( $userId > 0 && !empty($_POST['cat_percentile']) ){
				$this->iimpredictormodel->updateCATScore($this->userId, $this->CI->input->post('cat_percentile'));
			}
		}
		private function _storeICPUserdata($user_tracking_data){
			if( !empty($user_tracking_data) && is_array($user_tracking_data) && count($user_tracking_data) >0) {
				return $this->iimpredictormodel->saveTrackingData($user_tracking_data);
			}
		}
		public function createCookie($cookie_data){
			$cookie_name = "ICP_data";
			// If cookie exist
			if(isset($_COOKIE[$cookie_name])){
			
				$cookie_value = $_COOKIE[$cookie_name];
				$cookie_value = $cookie_data.','.$cookie_value;	
				setcookie($cookie_name, $cookie_value, time() + (86400), "/");

			}else{

				$cookie_value = $cookie_data;
				setcookie($cookie_name, $cookie_value, time() + (86400), "/"); // 1 day
			}
		}

		public function getSortedInstitutes($institutes,$category){
			$dbData = $this->iimpredictormodel->getSortedInstitutes($institutes,$category);
			$returnData = array();
			foreach ($dbData as $key => $value) {
				$returnData[] = $value["institute_id"];
			}
			return $returnData;
		}

		public function getInstituteRating($instituteIds){
			$collegeReviewLib = $this->CI->load->library('CollegeReviewForm/CollegeReviewLib');
			$aggregateReviews = $collegeReviewLib->getAggregateReviewsForListing($instituteIds);
		}

		public function getInstituteNamesInPredictorTable(){

			$mbaCacheLib = $this->CI->load->library('IIMPredictor/cache/MBAPredictorCache');			
			$instituteData = $mbaCacheLib->getInstituteNameData();
			if(!empty($instituteData)){
				$instituteData = json_decode($instituteData,true);
				return $instituteData;
			}
			$this->CI->load->config('newPredictorConfig');
			$instituteIds = $this->iimpredictormodel->getInstituteIdsPredictorTable(IIM_RESULTS_YEAR);

			$instituteData = array();

			if(!empty($instituteIds)){
				$this->CI->load->builder("nationalInstitute/InstituteBuilder");
		        $instituteBuilder = new InstituteBuilder();
		        $instituteRepo = $instituteBuilder->getInstituteRepository();
		        $instituteObjs = $instituteRepo->findMultiple($instituteIds);
		        foreach ($instituteIds as $instituteKey => $instituteValue) {
		        	if(!empty($instituteObjs[$instituteValue])) {
		        		$singleInstituteObj = $instituteObjs[$instituteValue];
		        		$singleInstituteName = $singleInstituteObj->getName();
		        		if(!empty($singleInstituteName)){
		        			$instituteData[$instituteValue] = $singleInstituteName;
		        		}
		        	}
		        }
			}

			if(!empty($instituteData)){
				$mbaCacheLib->storeInstituteNameData(json_encode($instituteData));
			}
			return $instituteData;
		}
	}
?>
