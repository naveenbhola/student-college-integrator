<?php

class NotificationInfo extends APIParent {

        private $validationObj;

        function __construct() {
            parent::__construct();
            $this->redisLib = PredisLibrary::getInstance();//$this->load->library('common/PredisLibrary'); 

        }

        public function fetchInAppNotification(){

            $this->load->helper("image");

            $cnt = $this->input->post('count') ? $this->input->post('count') : 30;
            $lastId = $this->input->post('lastId') ? $this->input->post('lastId') : 0;

            $cnt = 30;
            $lastId = 0;

            $notificationInfoModel = $this->load->model('notificationInfoModel');
            $lib = $this->load->library('common_api/FormatNotification');
            $loggedInUserDetails = $this->getUserDetails();
             
            $userId = isset($loggedInUserDetails['userId'])?$loggedInUserDetails['userId']:'';

            $responseData = array();
            $NotificationInfo = $notificationInfoModel->fetchInAppNotification($userId,$cnt,$lastId);
            $isEmpty = true;
            foreach ($NotificationInfo as $key => $info) {
                $isEmpty = false;
                $dataForNotification = array();
                $dataForNotification['notificationId'] = $info['id']."";
                $dataForNotification['notificationType'] = $info['notificationType'];
                $dataForNotification['commandType'] = $info['commandType'];
                $dataForNotification['landingPage'] = $info['landing_page'];   
                
                $dataForNotification['messageTitle'] = $info['title'];
                $dataForNotification['messageDescription'] = $info['message'];
                $dataForNotification['messageDescription'] = str_replace("#", "", $dataForNotification['messageDescription']);
                $dataForNotification['messageDescription'] = html_entity_decode($dataForNotification['messageDescription']);
                
                $dataForNotification['primaryId'] = $info['primaryId'];
                $dataForNotification['trackingUrl'] = $info['trackingURL'];
                $dataForNotification['userId'] = $info['userId'];
                $dataForNotification['secondaryData'] = (array)json_decode($info['secondaryData']);

                $dataForNotification['time'] = makeRelativeTime($info['modificationTime']);
                $dataForNotification['readStatus'] = false;
                if($info['readStatus'] == 'read'){
                    $dataForNotification['readStatus'] = true;    
                } else{
                    $dataForNotification['readStatus'] = false;    
                }
                $responseData[] = $lib->format($dataForNotification);
            }

            $notificationInfoModel->updateReadStatus($userId);
            $notificationResponseData['notifications'] = $responseData;

            $this->redisLib->deleteMembersOfHash('notificationsCount:inapp',array($userId)); 
            
            $this->response->setStatusCode(STATUS_CODE_SUCCESS);
            if(empty($notificationResponseData['notifications'])){
                $noInfoAvailableText = $this->config->item("noInfoAvailableText");
                $txt = $noInfoAvailableText['notifications'] ? $noInfoAvailableText['notifications'] : NO_INFO_AVAILABLE;
                $this->response->setResponseMsg($txt);
            }
            $this->response->setBody($notificationResponseData);
            $this->response->output();
            
    }
            
}
// gera -- 3392259
?>
