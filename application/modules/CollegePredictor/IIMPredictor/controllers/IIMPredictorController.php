<?php

class IIMPredictorController extends MX_Controller{
	
	private $eligibilityLib;

	private $scoreLib;

	private $iimpredictormodel;
	
	private $user_tracking_data = array();

	private $data;

	private $userId;

	private $activeIIM = array('IIM_Ahmedabad', 'IIM_Bangalore', 'IIM_Calcutta', 'IIM_Lucknow', 'IIM_Indore', 'IIM_Kozhikode', 'IIM_Shillong', 'IIM_Trichy' ,'IIM_Kashipur', 'IIM_Raipur','IIM_Udaipur','IIM_Rohtak', 'IIM_Ranchi', 'IIM_Bodhgaya', 'IIM_Sambalpur', 'IIM_Amritsar', 'IIM_Nagpur', 'IIM_Sirmaur', 'IIM_Visakhapatnam');

	function __construct(){ 
		
		$this->load->helper('security');
		$this->load->helper('IIMPredictor/callPredictor');

		$this->eligibilityLib = $this->load->library('IIMPredictor/IIMEligibilityLib');
		$this->scoreLib = $this->load->library('IIMPredictor/IIMScoreLib');
		$this->iimpredictormodel = $this->load->model('IIMPredictor/iimpredictormodel');

		$loggedInUserData = $this->getLoggedInUserData();
		$this->userId = $loggedInUserData['userId'];
	}

	private function makeUpInputData(){
		/* For IIM Ahmedabad, need to add a variable which holds the average of 10th and 12th percentage */
		$this->data['X_XII_'.$this->data['xiithStream'].'_avg'] = ($this->data['xthPercentage'] + $this->data['xiithPercentage']) /2;
        
        /*Calculating total experience in months */
        if(isset($this->data['expYear']) && isset($this->data['expMonth'])){
        	$this->data['experience'] = 12*$this->data['expYear'] + $this->data['expMonth'];
        }
		
        if(empty($this->data['experience'])){
        	$this->data['experience'] = 0;
        } 

        $this->data['specialCase'] ='gender-relaxation-'.$this->data['gender'];

	}

	public function startIIMPredictor($data = array()){
	
		if(!empty($data)){
		$this->data = $data;	
		}else{
        	$this->data = xss_clean($_POST); 
        	$this->makeUpInputData();
		}
        $predictorData = array();

        $predictorData['userData'] = $this->data;
       
        $predictorData['eligibility'] = $this->eligibilityLib->checkIIMEligibility($this->activeIIM, $this->data); 
        $predictorData['scoreData'] = $this->scoreLib->scorePredictor($predictorData['eligibility']['eligibleList'], $this->data); 
        $this->_formatUserTrackingData($this->data, $predictorData);
        
        return $predictorData;
	}	

	public function getEligibilityCount(){
		$data = $this->startIIMPredictor();
		
		$returnData = array();
		$returnData['count'] = count($data['eligibility']['eligibleList']);
		$returnData['userDataId'] = $this->_storeICPUserdata();
        $this->createCookie($returnData['userDataId']);

        foreach ($data['eligibility']['nonEligibilityData'] as $instituteKey => $instituteData) {
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
		}
		$returnData['userData'] = $this->data;
        $this->_updateCATScore();

		return $returnData;
	}

	private function _updateCATScore(){
		if( $this->userId > 0 && !empty($_POST['cat_percentile']) ){
			$this->iimpredictormodel->updateCATScore($this->userId, $this->input->post('cat_percentile'));
		}
	}

	private function _formatUserTrackingData($userData, $predictordata){
		$this->user_tracking_data = array();

        if($this->userId > 0){
			$this->user_tracking_data['userId'] = $this->userId;
        }else{
			$this->user_tracking_data['userId'] = 0;
        }
		$this->user_tracking_data['newRegistration'] = 'NO';

		$trackingFields = array('category', 'gender', 'xthBoard', 'xiithBoard', 'xthPercentage', 'xiithPercentage', 'graduationPercentage', 'xiithStream', 'graduationStream', 'graduationYear', 'experience', 'VRC_Percentile', 'DILR_Percentile', 'QA_Percentile', 'cat_total', 'cat_percentile');
		$fields = array_intersect($trackingFields, array_keys($userData));

		foreach($fields as $row=>$value){
			$this->user_tracking_data[$value] = $userData[$value];
		}

		$inEligibility = array_keys($predictordata['eligibility']['nonEligibilityData']);
		foreach ($inEligibility as $key => $value) {
			$this->user_tracking_data[$value] = 'NO';
		}
		if(empty($inEligibility)){
			$inEligibility = array();
		}

		$eligibility = array_diff($this->activeIIM, $inEligibility);

		foreach($eligibility as $key=>$value){
			$this->user_tracking_data[$value] =$predictordata['scoreData']['IIMWithData'][$value]['Total_Percentile'];
		}

		if(isMobileRequest()){
			$this->user_tracking_data['Device_Type'] = 'Mobile';
		} else{
			$this->user_tracking_data['Device_Type'] = 'Desktop';
		}
		

		$this->load->helper('utility_helper');

		$visitorSessionId = getVisitorSessionId();
		$this->user_tracking_data['Visitor_session'] = $visitorSessionId;

	}	

	private function _storeICPUserdata(){
		
		if(count($this->user_tracking_data) >0) {
			return $this->iimpredictormodel->saveTrackingData($this->user_tracking_data);
		}
		
	}
	
	
	public function storeIIMPercentileUserTrackingData(){
		$this->load->config("IIMPredictor/percentileConfig");
		$data  = $this->config->item("defaultValuesForPercentilePredictor");
		
		$this->userStatus = $this->checkUserValidation();
		if(isset($this->userStatus) && is_array($this->userStatus)){
			$signedInUser = $this->userStatus;
			$data['userId'] = $signedInUser[0]['userid'];
		}
		if(!isMobileRequest()) {
			$data['Device_Type'] = 'Desktop';
		}
		$data['newRegistration'] = $this->input->post('newUser',true); 		
		$data['cat_total'] = $this->input->post('catScore',true);


		$catScoreToPercentileMapping = $this->config->item("catScoreToPercentileMapping");
		$data['cat_percentile'] = $catScoreToPercentileMapping[$data['cat_total']/ 5];

		$visitorSessionId = getVisitorSessionId();
		$data['Visitor_session'] = $visitorSessionId;

		return $this->iimpredictormodel->saveTrackingData($data);
	}
	

	public function updateTrackingData($id,$userid) {
		
		if($id>0 && $userid>0) {
			$this->iimpredictormodel->updateUserId($id,$userid);
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

	public function iimPredictor(){
		$data['validateuser']  = $this->checkUserValidation();
		$this->load->view('IIMPredictor/icp_desktop', $data);
	}
}
