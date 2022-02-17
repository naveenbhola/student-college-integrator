<?php 

class shipmentCrons extends MX_Controller{

	function updateShipmentStatusCrons(){
	  $this->validateCron(); // prevent browser access
      $this->shipmentModel = $this->load->model('shipment/shipmentmodel');
      $this->userModel = $this->load->model('user/usermodel');
      $this->shipmentLib = $this->load->library('shipment/shipmentLib');
      $this->shipmentCommonLib = $this->load->library('shipment/shipmentCommonLib');

      $statusMapping = $this->config->item('statusMapping');
      $result = $this->shipmentModel->fetchShipments($statusMapping['DD']);
      $userIds = array();
      foreach ($result as $value){
          if(!empty($value)){
              $userIds[] = $value['userId'];
          }
          
      }
      $userIds = array_unique($userIds);
//      _p(array_column($result, 'userId'));
      $userData = $this->userModel->getUsersBasicInfo($userIds);
      $apiTimeLog = array();
      foreach ($result as $key=>$val){
        if($val['dateDiff']){
          $time1 = time();
          $startTime = date('Y-m-d H:i:s');
          $response = $this->shipmentlib->shipmentTracking(array(
            'AWBNumber'=>$val['AWBNumber'],
            'userId'=>$val['userId']
          ));
          $time2 = time();
          if(($time2 - $time1) > 5){
            $apiTimeLog[$val['AWBNumber'].'_'.$val['userId']][] = $startTime;
            $apiTimeLog[$val['AWBNumber'].'_'.$val['userId']][] = $time2 - $time1;
          }
//          _p($response);
          $DHLShipmentStatus = $statusMapping[$response['eventCodes'][sizeof($response['eventCodes'])-1]];
           if($val['shipmentStatus']!=$DHLShipmentStatus && $DHLShipmentStatus!=''){
              $this->shipmentModel->updateShipmentStatus($val['shipmentId'],$DHLShipmentStatus);
              if($response['eventCodes'][sizeof($response['eventCodes'])-1]==DELIVERED){
                $contentArray = array(
                  'AWBNumber'=>$val['AWBNumber'],
                  'name'=>$val['firstName']." ".$val['lastName'],
                  'lastDHLUpdate'=> $response['lastDHLUpdate']['Date'],
                  'pickUpId'=>$val['pickUpId'],
                  'userId'=>$val['userId'],
                  'emailId'=> $userData[$val['userId']]['email'],
                  'mobile'=> $userData[$val['userId']]['mobile']
                );
                $this->shipmentCommonLib->shipmentDeliveredEmailNotification($contentArray);
                $this->shipmentCommonLib->shipmentDeliveredSMSNotification($contentArray);
              }
          }
        }
      }
      if(!empty($apiTimeLog)){
        @mail('satech@shiksha.com','DHL Shipment tracking log on '.date('Y-m-d H:i:s'), print_r($apiTimeLog, true));
      }
    }
}
