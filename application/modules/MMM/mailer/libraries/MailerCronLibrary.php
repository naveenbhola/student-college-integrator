<?php
    class MailerCronLibrary
    {
        private $CI;

        function __construct()
        {
            $this->CI = &get_instance();
        }

        public  function updateProcessedAndSentMailsData(){
            $mailerModel = $this->CI->load->model('mailer/mailermodel');
            $mailers  = $this->getMailersToProcess();
            $mailerIds = array();
            if(!empty($mailers)){
                $mailerIds = $mailers['mailerIds'];
            }
            unset($mailers['mailerIds']);
            $updateTrackingMailers = array();
            $insertTrackingMailers = array();
            
            $trackingData = $this->getProcessedAndSentMailsData($mailers,$mailerIds);
            if(!empty($trackingData['mailersToUpdate'])){
                $mailerModel->updateMailerTrackingData($trackingData['mailersToUpdate']);
            }
            if(!empty($trackingData['mailersToInsert'])){
               
                $mailerModel->insertMailerTrackingData($trackingData['mailersToInsert']);
            }
        }

        private function getProcessedAndSentMailsData($mailers,$mailerIds){
            $mailerModel = $this->CI->load->model('mailer/mailermodel');
            $trackingData = array();
            $mailersStatusData = array();

            if(!empty($mailers)){

                $mailersData  = $mailerModel->getMailersStatusData($mailerIds);

                if(!empty($mailersData)){
                    foreach ($mailersData as $key => $value) {
                     $mailersStatusData[$value['mailerid']][$value['issent']] = $value['count']; 
                    }  
                }

                foreach ($mailersStatusData as $mailerId => $mailerDetail) {
                    $mailerData = array();
                    $mailerData['mailerId'] = $mailerId;
                    $mailerData['mailerName'] = $mailers[$mailerId]['mailerName'];
                    $mailerData['totalMails'] = $mailers[$mailerId]['totalMails'];
                    $mailerData['clientId'] = $mailers[$mailerId]['clientId'];
                    $mailerData['scheduledDate'] = $mailers[$mailerId]['scheduledDate'];
                    $mailerData['createdBy'] = $mailers[$mailerId]['createdBy'];
                    if(!empty($mailerDetail['no'])){
                        $mailerData['isProcessed'] = 'false';
                    } else {
                        $mailerData['isProcessed'] = 'true';
                    }
                    $processedMails = 0;
                    if(!empty($mailerDetail['yes']) ){
                        $mailerData['sentMails'] = $mailerDetail['yes'];
                        $processedMails = $mailerDetail['yes'];
                    } else {
                        $mailerData['sentMails'] = 0;
                    }

                    if(!empty($mailerDetail['notsent'])){
                        $processedMails += $mailerDetail['notsent'];
                    }

                    $mailerData['processedMails'] =  $processedMails;
                    if(!empty($mailers['mailersTobeUpdated'][$mailerId])){
                        $trackingData['mailersToUpdate'][] = $mailerData;
                    } else {
                        $trackingData['mailersToInsert'][] = $mailerData;
                    }
                }
       
                unset($mailersData);
                unset($mailersStatusData);
            }
            return $trackingData;

        }

        private function getMailersToProcess(){
            
            $mailerModel = $this->CI->load->model('mailer/mailermodel');
            $mailersData = $mailerModel->getUnTrackedMailers();

            if(!empty($mailersData)){
                foreach ($mailersData as $key => $value) {
                    $mailers[$value['mailerId']] = $value;
                    $mailers['mailerIds'][] = $value['mailerId'];
                }
            }
           
            $mailersFromTracking = $mailerModel->getUnprocessedMailerFromTracking();
            if(!empty($mailersFromTracking)){
                foreach ($mailersFromTracking as $key => $value) {
                    $mailers[$value['mailerId']] = $value;
                    $mailers['mailersTobeUpdated'][$value['mailerId']] = 1;
                    $mailers['mailerIds'][] = $value['mailerId'];
                }
            }
            return $mailers;
        }

        public function updateUserActionData(){
            $mailerModel = $this->CI->load->model('mailer/mailermodel');
            $responseModel = $this->CI->load->model('response/responsemodel');
            
            while(1){
                $lastProcessedId = $responseModel->getLastProcessedId('MAILER_MIS_REPORT','write');
                $userActionData =  $mailerModel->getUserActionData($lastProcessedId,1000);
               
                if(!empty($userActionData)){
                    $mailerIds = array();
                    $userActionData = $this->processUserActionData($userActionData);
                    $updatedLastProcessId = $userActionData['lastProcessedId'] ;
                    unset($userActionData['lastProcessedId']);
                    $mailerIds  = array_keys($userActionData);
                    $existingTrackingData = $mailerModel->getExistingTrackingData($mailerIds);
                    $updatedTrackingData = array();
                   
                    foreach ($userActionData as $mailerId => $value) {

                        $trackingData = array();
                        $trackingData['mailerId'] = $mailerId;
                        $trackingData['totalMailsOpened']  = $existingTrackingData[$mailerId]['totalMailsOpened']+$value['totalMailsOpened'];
                        $trackingData['totalMailsClicked'] = $existingTrackingData[$mailerId]['totalMailsClicked']+$value['totalMailsClicked'];
                        $trackingData['totalUnsubscribeClicked']  = $existingTrackingData[$mailerId]['totalUnsubscribeClicked']+$value['totalUnsubscribeClicked'];
                       
                        $trackingData['uniqueMailsOpened']  = $existingTrackingData[$mailerId]['uniqueMailsOpened'];
                        $trackingData['uniqueMailsClicked'] = $existingTrackingData[$mailerId]['uniqueMailsClicked'];
                        $trackingData['uniqueUnsubscribeClicked'] = $existingTrackingData[$mailerId]['uniqueUnsubscribeClicked'];
                        $trackingData['openRate'] = 0;
                        $trackingData['clickRate'] = 0;
                        $trackingData['unsubscribeRate']  = 0;
                        
                       
                        if(!empty($value['openUsers'])){
                            $count  = $value['openUsers']['count'];
                            unset($value['openUsers']['count']);
                            $trackingData['uniqueMailsOpened'] =  $this->getUniqueActionData($value['openUsers'],$count,$lastProcessedId,$mailerId,'open',$existingTrackingData[$mailerId]['uniqueMailsOpened']);
                        }

                        if(!empty($value['clickUsers'])){
                            $count  = $value['clickUsers']['count'];
                             unset($value['clickUsers']['count']);
                            $trackingData['uniqueMailsClicked'] =  $this->getUniqueActionData($value['clickUsers'],$count,$lastProcessedId,$mailerId,'click',$existingTrackingData[$mailerId]['uniqueMailsClicked']);
                        }

                        if(!empty($value['unsubscribedUsers'])){
                            $count  = $value['unsubscribedUsers']['count'];
                            unset($value['unsubscribedUsers']['count']);
                            $trackingData['uniqueUnsubscribeClicked'] =  $this->getUniqueActionData($value['unsubscribedUsers'],$count,$lastProcessedId,$mailerId,'unsubscribe',$existingTrackingData[$mailerId]['uniqueUnsubscribeClicked']);
                        }

                        $trackingData['openRate'] =round(($trackingData['uniqueMailsOpened'] / $existingTrackingData[$mailerId]['sentMails'] )* 100,2);
                        $trackingData['clickRate'] =round(($trackingData['uniqueMailsClicked'] / $trackingData['uniqueMailsOpened'] )* 100,2);
                        $trackingData['unsubscribeRate'] =round(($trackingData['uniqueUnsubscribeClicked'] / $trackingData['uniqueMailsOpened']) * 100,2);
                        $updatedTrackingData[] = $trackingData;
                     

                    }
                    unset($userActionData);
                    if(!empty($updatedTrackingData)){
                        $mailerModel->updateMailerTrackingData($updatedTrackingData);
                    }
                    $responseModel->updateLastProcessedId($updatedLastProcessId,'MAILER_MIS_REPORT');
                } else {
                    break;
                }
            }
        }

        private function getUniqueActionData($userIds,$userCount,$lastProcessedId,$mailerId,$trackingType,$existingCount){
           
            $mailerModel = $this->CI->load->model('mailer/mailermodel');
            $timeStart = date('Y-m-d',strtotime('-6 months'));
           
            $previousCount = $mailerModel->getPreviousActionCountForUsers($timeStart,$userIds,$lastProcessedId,$trackingType,$mailerId);
 
            $uniqueActionCount = empty($existingCount)? ($userCount -$previousCount ) :$existingCount+$userCount -$previousCount  ;
           

            return $uniqueActionCount;
        }

        private function processUserActionData($userActionData){
            $processedData = array();
            $lastProcessedId = 0;
            if(!empty($userActionData)){
                foreach ($userActionData as $value) {
                    $lastProcessedId  = $value['id'];
                    if($value['trackingType'] == 'open'){
                        if(empty($processedData[$value['mailerId']]['totalMailsOpened'])){
                            $processedData[$value['mailerId']]['totalMailsOpened'] = 0;
                        }
                        $processedData[$value['mailerId']]['totalMailsOpened'] +=1;

                        if(empty($processedData[$value['mailerId']]['openUsers'][$value['userId']])){
                            $processedData[$value['mailerId']]['openUsers'][$value['userId']] =$value['userId'];
                            if(empty($processedData[$value['mailerId']]['openUsers']['count'])){
                                $processedData[$value['mailerId']]['openUsers']['count']=0;
                            }
                            $processedData[$value['mailerId']]['openUsers']['count'] +=1;
                        };
                    }

                    if($value['trackingType'] == 'click' && $value['widget'] != 'unsubscribe'){
                        if(empty($processedData[$value['mailerId']]['totalMailsClicked'])){
                            $processedData[$value['mailerId']]['totalMailsClicked'] = 0;
                        }
                        $processedData[$value['mailerId']]['totalMailsClicked'] +=1;

                        if(empty($processedData[$value['mailerId']]['clickUsers'][$value['userId']])){
                            $processedData[$value['mailerId']]['clickUsers'][$value['userId']] = $value['userId'];

                            if(empty($processedData[$value['mailerId']]['clickUsers']['count'])){
                                $processedData[$value['mailerId']]['clickUsers']['count']=0;
                            }
                            $processedData[$value['mailerId']]['clickUsers']['count'] +=1;
                        };
                    }

                    if($value['trackingType'] == 'click' && $value['widget'] == 'unsubscribe'){
                        if(empty($processedData[$value['mailerId']]['totalUnsubscribeClicked'])){
                            $processedData[$value['mailerId']]['totalUnsubscribeClicked'] = 0;
                        }
                        $processedData[$value['mailerId']]['totalUnsubscribeClicked'] +=1;

                        if(empty($processedData[$value['mailerId']]['unsubscribedUsers'][$value['userId']])){
                            $processedData[$value['mailerId']]['unsubscribedUsers'][$value['userId']] = $value['userId'];
                            if(empty($processedData[$value['mailerId']]['unsubscribedUsers']['count'])){
                                $processedData[$value['mailerId']]['unsubscribedUsers']['count']=0;
                            }
                            $processedData[$value['mailerId']]['unsubscribedUsers']['count'] +=1;
                        };
                    }
                }
            }
            
            $processedData['lastProcessedId'] = $lastProcessedId;
            return $processedData;
        }
    
    }
?>

