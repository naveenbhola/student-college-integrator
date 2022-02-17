<?php

class examResponseAccessLib {

	private $CI;

	function __construct(){
		$this->CI = & get_instance();
    $this->EPAccessModel = $this->CI->load->model('enterprise/examresponseaccessmodel');
	}

  function checkValidEnterpriseUser($userId){
    if(!empty($userId) && $userId >0){
      $result = $this->EPAccessModel->checkValidEnterpriseUser($userId);
      if(count($result)){
        return '1';
      }else{
        return '0';
      }
    }else{
      return '0';
    }
  }

	function getAllExamDetails(){
    $result = $this->EPAccessModel->getAllExamDetails();
    $examDetails = array();
    foreach ($result as $key => $examDetail) {
      $examDetails[$examDetail['id']] = $examDetail['name'];
    }
    return $examDetails;
  }  

  function validateSubscriptionFields($data){
    // validate clientid
    if($this->_validateClientId($data) == false){
      return false;
    }

    // validate exam and groupids
    if($this->_validateExamAndGroupIds($data) == false){
      return false;
    }

    // validate user locations
    if($this->_validateUserLocations($data) == false){
      return false;
    }

    // validate campaign type fields
    if($this->_validateCampaignTypeFields($data) == false){
      return false;
    }
    
    // validate email
    if(!empty($data['email'])){
      if(validateEmailMobile('email',$data['email']) == false){
        return false;
      }
    }

    // validate mobile
    if(!empty($data['mobileNo'])){
      if(validateEmailMobile('mobile',$data['mobileNo'],INDIA_ISD_CODE) == false){
        return false;
      }
    }
    
    return $data;    
  }

  function validateNLSubscriptionFields($data) {
      
    // validate clientid
    if($this->_validateClientId($data) == false){
      return false;
    }

    // validate campaign type fields
    if($this->_validateCampaignTypeFields($data) == false){
      return false;
    }
    
    // validate email
    if(!empty($data['email'])){
      if(validateEmailMobile('email',$data['email']) == false){
        return false;
      }
    }

    // validate mobile
    if(!empty($data['mobileNo'])){
      if(validateEmailMobile('mobile',$data['mobileNo'],INDIA_ISD_CODE) == false){
        return false;
      }
    }
    
    return $data;    
  }

  private function _validateClientId(& $data){
    if(empty($data['clientId'])){
      return false;
    }else{
      // get account manager id
      $this->CI->load->library('sums_product_client');
      $objSumsProduct = new Sums_Product_client();
      $salesUserInfo = $objSumsProduct->getSalesDataByClientId(1, $data['clientId']);
      if(empty($salesUserInfo) ||  !isset($salesUserInfo['userId'])){
        return false;
      }else{
        $userIds = array($data['clientId'],$salesUserInfo['userId']);
        $usermodel = $this->CI->load->model("user/usermodel");
        $result = $usermodel->getNameByUserId($userIds);
        if(empty($result) || count($result)<0 ){
          return false;
        }else{
          foreach ($result as $key => $userDetails) {
            if($userDetails['userid'] == $data['clientId']){
              $data['clientName'] = $userDetails['firstname'].' '.$userDetails['lastname'];
            }else if($userDetails['userid'] == $salesUserInfo['userId']){
              $data['accountManagerName'] = $userDetails['firstname'].' '.$userDetails['lastname'];
            }
          }

          if(empty($data['clientName'])){
            return false;
          }
        }
      }
    }
    return true;
  }

  private function _validateUserLocations(& $data){
    if(!empty($data['userCityIds'])){
      $data['userCityIds'] = json_decode($data['userCityIds']);
      $data['userCityIds'] = array_unique($data['userCityIds']);
      if(empty($data['userCityIds']) || !is_array($data['userCityIds']) || count($data['userCityIds']) <=0){
        return false;
      }
    }
    return true;
  }

  private function _validateExamAndGroupIds($data){
    // validate exam id
    if(empty($data['examId']) || !is_numeric($data['examId'])){
      return false;
    }

    if(empty($data['selectedGroupIds']) || !is_array($data['selectedGroupIds']) || count($data['selectedGroupIds']) <=0){
      return false;
    }
    return true;
  }

  private function _validateCampaignTypeFields(& $data,$checkForQuantity = true){
    if(empty($data['campaignType'])){
      return false;
    }

    if($data['campaignType'] == 'duration'){
      if(
          empty($data['timeRangeDurationFrom']) || 
          empty($data['timeRangeDurationTo']) ||
          $this->_validateDateFormat($data['timeRangeDurationFrom']) == false ||
          $this->_validateDateFormat($data['timeRangeDurationTo']) == false
          ){
        return false;
      }

      $data['timeRangeDurationFrom'] = $this->_changeDateFormat($data['timeRangeDurationFrom']);
      $data['timeRangeDurationTo'] = $this->_changeDateFormat($data['timeRangeDurationTo']);
      if(strtotime($data['timeRangeDurationTo']) < strtotime($data['timeRangeDurationFrom'])){
        return false;
      }
    }else if($data['campaignType'] == 'quantity'){
      if($checkForQuantity == true){
        // validate response quantity
        $pattern = "/^([0-9]{1,6})$/i";
        if(preg_match($pattern, $data['quantityExpected']) == false){
          return false;
        }elseif($data['quantityExpected']<= 0){
          return false;
        }
      }

      // validate creation date
      if($this->_validateDateFormat($data['creationDateFrom']) == false){
        return false;
      }
      
      $data['creationDateFrom'] = $this->_changeDateFormat($data['creationDateFrom']);
    }else{
      return false;
    }
    return true;
  }

  private function _validateDateFormat($date){
    if(empty($date)){
      return false;
    }

    $pattern = "/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/([0-9]{4})$/";
    if (preg_match($pattern,$date)) {
     return true;
    }else{
      return false;
    }
  }

  private function _changeDateFormat($date){
    if(!empty($date)){
      $date = str_replace("/", "-", $date);
      $date = date("Y-m-d",strtotime($date));
    }
    return $date;
  }

  public function validateResponseCountFields($data){
    // validate group ids
    $data['groupIds'] = json_decode($data['groupIds']);
    if(empty($data['groupIds']) || !is_array($data['groupIds']) || count($data['groupIds']) <=0){
      return false;
    }

    // validate user location
    if($this->_validateUserLocations($data) == false){
      return false;
    }

    // validate campaign fields
    if($this->_validateCampaignTypeFields($data,false) == false){
      return false;
    }else{
      // check if start date greater than today date
      if($data['campaignType'] == 'duration'){
        $data['fromDate'] = $data['timeRangeDurationFrom'];
      }else{
        $data['fromDate'] = $data['creationDateFrom'];
      }

      if(strtotime(date("Y-m-d")) < strtotime($data['fromDate'])){
        return false;
      }
    }

    return $data;
  }

  public function saveSubscription($data){
    $subScriptionData = array(
      'clientId'           => $data['clientId'],
      'clientName'         => $data['clientName'],
      'examId'             => $data['examId'],
      'groupIds'           => implode(",", $data['selectedGroupIds']),
      'createdBy'          => $data['createdBy'],
      'campaignType'       => $data['campaignType']
      );

    if(!empty($data['accountManagerName'])){
      $subScriptionData['accountManagerName'] = $data['accountManagerName'];
    }

    if($data['campaignType'] == "duration"){
      $subScriptionData['startDate'] = $data['timeRangeDurationFrom'];
      $subScriptionData['endDate']   = $data['timeRangeDurationTo'];
    }else{
      $subScriptionData['startDate']        = $data['creationDateFrom'];
      $subScriptionData['quantityExpected'] = $data['quantityExpected'];
    }

    if(!empty($data['email'])){
      $subScriptionData['email']        = $data['email'];
    }

    if(!empty($data['mobileNo'])){
      $subScriptionData['mobile']        = $data['mobileNo'];
    }

    if(!empty($data['userCityIds'])){
      $subScriptionData['userLocationIds'] = implode(",", $data['userCityIds']);
    }
    //echo 'fd';_p($subScriptionData);die;
    $subScriptionEntitesData = array();
    foreach ($data['selectedGroupIds'] as $groupId) {
      $subScriptionEntitesData[] = array(
        'entityType' =>'groupId',
        'entityValue' => $groupId
      );
    }

    if(empty($data['userCityIds'])){
      $subScriptionEntitesData[] = array(
        'entityType' =>'userCity',
        'entityValue' => -1
      );
    }else{
      foreach ($data['userCityIds'] as $userCityId) {
        $subScriptionEntitesData[] = array(
          'entityType' =>'userCity',
          'entityValue' => $userCityId
        );
      }
    }
    $response = $this->EPAccessModel->saveSubscription($subScriptionData, $subScriptionEntitesData);
    if($response == false){
      return "FAIL";
    }else{
      return "SUCCESS";
    }
  }

  public function getSubscriptionData($type,$clientId = ''){
    if(!in_array($type, array("active","expired","inactive","all"))){
      return array();
    }

    $data = $this->EPAccessModel->getSubscriptionData($type, $clientId);
    if(count($data) <=0 || $data == false){
      return array();
    }

    $examIds = array();
    $groupIds = array();
    foreach ($data as $key => $subscriptionDetail) {
      $examIds[$subscriptionDetail['examId']] = $subscriptionDetail['examId'];
      $data[$key]['groupIds']                 = explode(",", $subscriptionDetail['groupIds']);
      foreach ($data[$key]['groupIds'] as $groupId) {
        $groupIds[$groupId] =$groupId;
      }
    }

    $examIdToName = $this->getExamName($examIds);
    $groupIdToName = $this->getGroupName($groupIds);
    $cityIdToName = $this->getUserLocationDetails();

    foreach ($data as $key => $subscriptionDetail) {
      $data[$key]['examName'] = htmlentities($examIdToName[$subscriptionDetail['examId']]);
      unset($data[$key]['examId']);
      $toopTipInput = array();
      foreach ($subscriptionDetail['groupIds'] as $groupId) {
        $toopTipInput[] = htmlentities($groupIdToName[$groupId]);
      }
      $toopTipInput = $this->createToolTipAndHeading($toopTipInput);
      
      $data[$key]['groupNames'] = $toopTipInput['heading'];
      $data[$key]['groupNamesToolTip'] = $toopTipInput['toopTip'];
      unset($toopTipInput);

      unset($data[$key]['groupIds']);
      
      if(empty($subscriptionDetail['userLocationIds'])){
        $data[$key]['userLocations'] = "All India";
        $data[$key]['userLocationsToolTip'] = "All India";
      }else{
        $userLocationIds = explode(",", $subscriptionDetail['userLocationIds']);  
        $toopTipInput = array();
        foreach ($userLocationIds as $locationId) {
          $toopTipInput[]= $cityIdToName[$locationId];
        }
        $toopTipInput = $this->createToolTipAndHeading($toopTipInput);
        $data[$key]['userLocations'] = $toopTipInput['heading'];
        $data[$key]['userLocationsToolTip'] = $toopTipInput['toopTip'];
        unset($toopTipInput);
      }
      unset($data[$key]['userLocationIds']);

      if(empty($subscriptionDetail['endDate'])){
        $data[$key]['endDate'] = "None";
      }else{
        $data[$key]['endDate'] = date("d-m-Y",strtotime($subscriptionDetail['endDate']));
        $data[$key]['quantityExpected'] = "Indefinite";
      }

      $data[$key]['startDate'] = date("d-m-Y",strtotime($subscriptionDetail['startDate']));
    }
    //_p($data);die;
    return $data;
  }

  function createToolTipAndHeading($data){
    $returnData = array();
    $returnData['toopTip'] = implode(", ", $data);
    $count = count($data);
    if($count >1){
      $returnData['heading'] = $data[0].', '.$data[1];
      $returnData['heading'] .= ($count > 2)?', ...':'';
    }else{
      $returnData['heading'] = $data[0];
    }
    return $returnData;
  }

  public function getExamName($examIds){
    if(empty($examIds) || !is_array($examIds) || count($examIds) <=0){
      return false;
    }

    $result = $this->EPAccessModel->getExamName($examIds);
    $examIdToName = array();
    foreach ($result as $key => $examDetail) {
      $examIdToName[$examDetail['id']] = $examDetail['name'];
    }

    return $examIdToName;
  }

  public function getGroupName($groupIds){
    if(empty($groupIds) || !is_array($groupIds) || count($groupIds) <=0){
      return false;
    }

    $result = $this->EPAccessModel->getGroupName($groupIds);
    $groupIdToName = array();
    foreach ($result as $key => $groupDetail) {
      $groupIdToName[$groupDetail['groupId']] = $groupDetail['groupName'];
    }

    return $groupIdToName;
  }

  public function getUserLocationDetails(){
    $data = array();
    $this->getUserLocation($data);

    $cityIdToName = array();
    foreach ($data['virtualCities'] as $key => $cityDetail) {
      $cityIdToName[$cityDetail['cityId']] = $cityDetail['cityName'];
    }

    foreach ($data['stateCities'] as $key => $stateCityDetails) {
      $cityDetails = $stateCityDetails['cityMap'];
      foreach ($cityDetails as $key => $cityDetail) {
        $cityIdToName[$cityDetail['CityId']] = $cityDetail['CityName'];
      }
    }

    return $cityIdToName;
  }

  function getUserLocation(& $data){
    $residenceCityLib = new  \registration\libraries\FieldValueSources\ResidenceCity;
    $residenceCity = $residenceCityLib->getValues(array("isNational"=>1));
    if(!empty($residenceCity)) {
        $formattedCities = $this->getFormattedCities($residenceCity);
        $data['virtualCities'] = $formattedCities['virtualCities'];
        $data['virtualCitiesParentChildMapping'] = $formattedCities['virtualCitiesParentChildMapping'];
        $data['virtualCitiesChildParentMapping'] = $formattedCities['virtualCitiesChildParentMapping'];
        $data['stateCities'] = $residenceCity['stateCities'];
    }
    $data['criteriaNo'] = 1;
  }

  public function updateSubscriptionStatus($subscriptionId, $status){
    if(empty($subscriptionId) || $subscriptionId <=0){
      return false;
    }

    if(empty($status)){
      return false;
    }

    $response = $this->EPAccessModel->updateSubscriptionStatus($subscriptionId, $status);
    $this->EPAccessModel->deleteSubscriptionFromPorting($subscriptionId);
  
    if($response == false){
      return "FAIL";
    }else{
      return "SUCCESS";
    }
  }

  public function getFormattedCities($residenceCity) {

    $i = 0;$virtualCities = array();
    $virtualCitiesParentChildMapping = array();$virtualCitiesChildParentMapping = array();
    foreach($residenceCity['virtualCities'] as $virtualCityId=>$virtualCityDetails){
      $virtualCities[$i]['cityId'] = $virtualCityId;
      $virtualCities[$i]['cityName'] = $virtualCityDetails['name'];
      $j=1;
      foreach($virtualCityDetails['cities'] as $virtualCityDetail) {
        if($virtualCityDetail['state_id'] != '-1') {
          $virtualCitiesParentChildMapping[$virtualCityId][$j]['city_id'] = $virtualCityDetail['city_id'];
          $virtualCitiesParentChildMapping[$virtualCityId][$j]['city_name'] = $virtualCityDetail['city_name'];
          $virtualCitiesChildParentMapping[$virtualCityDetail['city_id']] = $virtualCityId;
          $j++;
        }
      }
      $i++;
    }

    foreach($residenceCity['metroCities'] as $metroCityDetails){
      $virtualCities[$i]['cityId'] = $metroCityDetails['cityId'];
      $virtualCities[$i]['cityName'] = $metroCityDetails['cityName'];
      $i++;
    } 

    $formattedCities['virtualCities'] = $virtualCities;
    $formattedCities['virtualCitiesParentChildMapping'] = $virtualCitiesParentChildMapping;
    $formattedCities['virtualCitiesChildParentMapping'] = $virtualCitiesChildParentMapping;
    return $formattedCities;
  }
}


