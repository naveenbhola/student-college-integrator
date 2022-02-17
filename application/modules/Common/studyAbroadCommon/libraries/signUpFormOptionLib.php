<?php
class signUpFormOptionLib{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->signupformoptionmodel = $this->CI->load->model('studyAbroadCommon/signupformoptionmodel');
    }    

    public function insertSignUpFormABTracking($inputParams){
        if(USE_ABTEST_ABROAD_SIGNLESIGNUPFORM){
            $data = array(
                'visitorSessionId'  => getVisitorSessionId(),
                'CTA'               => ($inputParams['CTA'] != '')? $inputParams['CTA'] :'',
                'CTAClickedAt'      => ($inputParams['CTA'] != '')? date("Y-m-d H:i:s") :'',
                'formType'          => ($inputParams['formType'] != '') ? $inputParams['formType'] : 'new',
                'pageReferer'       => ($inputParams['pageReferer'] != '') ? str_replace(SHIKSHA_STUDYABROAD_HOME,'',$inputParams['pageReferer']) :'',
                'MISTrackingId'     => ($inputParams['MISTrackingId'] !='')? $inputParams['MISTrackingId']: 0 ,
                'sourceApplication' => ($inputParams['sourceApplication'] !='') ? $inputParams['sourceApplication'] : 'desktop'
            );
            $this->signupformoptionmodel->insertSignUpFormABTracking($data);
        }
    }

    private function _getTableRowIdToBeUpdated($MISTrackingId, $pageReferer=''){
    	if(empty($MISTrackingId)){
    		$MISTrackingId =0 ;
    	}
    	$visitorSessionId = getVisitorSessionId();
    	$tableRowId = $this->signupformoptionmodel->getTableRowIdToBeUpdated($MISTrackingId, $visitorSessionId, $pageReferer);
    	return $tableRowId[0]['id'];
    }

    public function updateAlreadyRegisteredUserData($userId, $MISTrackingId, $pageReferer=''){
    	$userId = (int)$userId;
    	if(!empty($userId) && $userId >0 && USE_ABTEST_ABROAD_SIGNLESIGNUPFORM){
	    	$tableRowId = $this->_getTableRowIdToBeUpdated($MISTrackingId, $pageReferer);
	    	if($tableRowId > 0){
				$data = array(
					'alreadyRegisteredFlag'	=> 'yes',
					'userId'				=> $userId,
					'lastModifiedAt'		=> date("Y-m-d H:i:s")
					);
				$this->signupformoptionmodel->updateSignUpFormABtrackingData($data,$tableRowId);
			}
		}
    }

    public function updateConversionData($userId, $MISTrackingId, $pageReferer=''){
    	$userId = (int)$userId;
    	if(!empty($userId) && $userId >0 && USE_ABTEST_ABROAD_SIGNLESIGNUPFORM){
    		$tableRowId = $this->_getTableRowIdToBeUpdated($MISTrackingId, $pageReferer);
    		if($tableRowId > 0){
    			$data = array(
    				'conversionAt'		=> date("Y-m-d H:i:s"),
    				'userId'			=> $userId,
    				'lastModifiedAt'	=> date("Y-m-d H:i:s")
    				);
    			$this->signupformoptionmodel->updateSignUpFormABtrackingData($data,$tableRowId);
    		}
        }
    }

    public function updateUnloadData($MISTrackingId, $pageReferer=''){
        if(USE_ABTEST_ABROAD_SIGNLESIGNUPFORM){
            $tableRowId = $this->_getTableRowIdToBeUpdated($MISTrackingId, $pageReferer);
            if($tableRowId > 0){
                $data = array(
                    'formUnloadedAt'    => date("Y-m-d H:i:s"),
                    'lastModifiedAt'    => date("Y-m-d H:i:s")
                    );
                $this->signupformoptionmodel->updateSignUpFormABtrackingData($data,$tableRowId);
            }
        }
    }

	public function checkIfAlreadyRegisteredCase(& $cookieData = array())
	{
		$val = base64_decode($this->CI->input->get('ar'));
		if($val == 1) // already registered
		{
			//$cookieData = json_decode(base64_decode($_COOKIE['signUpFormParams']));
			return true;
		}else{
			return false;
		}
	}
    public function getMISTrackingDetails($trackingPageKeyId){
        $MISTrackingDetails = array();
        if(empty($trackingPageKeyId) || $trackingPageKeyId <=0){
            return $MISTrackingDetails;
        }
    }
}
