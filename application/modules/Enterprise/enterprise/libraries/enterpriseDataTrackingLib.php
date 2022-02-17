<?php

class enterpriseDataTrackingLib {

	private $CI;

	function __construct(){
		$this->CI = & get_instance();  
	}

  function trackEnterpriseData($inputData = array()){
    $data = $this->_validateInputAndPrepareData($inputData);
    //var_dump($data);die;
    if($data === false){
      return false;
    }

    $response = $this->_prepareBasicData($data);
    if($response === false){
      return $response;
    }

    $model = $this->CI->load->model('enterprise/enterprisedatatrackingmodel');
    $model->saveEnterpriseTrackingData($data);
  }

  private function _validateInputAndPrepareData($inputData){
    if(empty($inputData['product'])){
      return false;
    }

    if(empty($inputData['page_tab'])){
      return false;
    }

    if(empty($inputData['cta'])){
      return false;
    }

    if(!empty($inputData['entity_id']) && $inputData['entity_id'] <=0){
       return false;
    }

    if(!empty($inputData['records_fetched']) && $inputData['records_fetched']<0){
      return false;
    }

    $data = array();
    $data['product'] = $inputData['product'];
    $data['page_tab'] = $inputData['page_tab'];
    $data['cta'] = $inputData['cta'];

    if(!empty($inputData['entity_id'])){
      $data['entity_id'] = $inputData['entity_id'];
    }

    if(!empty($inputData['search_criteria'])){
      $data['search_criteria'] = $inputData['search_criteria'];
    }

    if($inputData['records_fetched'] >=0){
      $data['records_fetched'] = $inputData['records_fetched'];
    }
    //_p($data);die;
    return $data;
  }

  private function _prepareBasicData(& $data){
    $loggedInUserData = $this->CI->checkUserValidation();
    $loggedInDetails = explode('|', $loggedInUserData[0]['cookiestr']);
    $clientEmail = $loggedInDetails[0];
    if(empty($clientEmail)){
      return false;
    }

    $accountUsedBy = 'Client';
    $accountUsedByEmail = $_COOKIE['clientAutoLogin'];
    if(isset($accountUsedByEmail) && !empty($accountUsedByEmail)){
      $accountUsedBy = 'AM';
      global $superUserEmail;
      if(in_array($accountUsedByEmail, $superUserEmail)){
        $accountUsedBy = 'SuperUser';
      }
    }

    if($accountUsedByEmail == ''){
      $accountUsedByEmail = $clientEmail;
    }

    $data['account_used_by']       = $accountUsedBy;
    $data['account_used_by_email']  = $accountUsedByEmail;
    $data['client_email']         = $clientEmail;
    $data['IP_address']           = S_REMOTE_ADDR;
    $data['user_agent']           = $_SERVER['HTTP_USER_AGENT'];
    return true;
  }
}


