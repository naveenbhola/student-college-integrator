<?php
class miscServer extends MX_Controller {
    function index() {
        
        $this->dbLibObj = DbLibCommon::getInstance('Misc');
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->helper('url');
        $this->load->library(array('listing/listingconfig'));
        $config['functions']['sTrackCommunication'] = array('function' => 'miscServer.sTrackCommunication');
        $config['functions']['sGetCommunicationTracking'] = array('function' => 'miscServer.sGetCommunicationTracking');
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }

    private function getDBHandle($operation = 'read') {
        return $this->_loadDatabaseHandle($operation);
    }

    function sGetCommunicationTracking($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appId = $parameters[0];
        $recipientIds = $parameters[1];
        $senderId = $parameters[2];
        $trackBoth = $parameters[3];
        $mode = $parameters[4]; 
        $recipientIds = trim($recipientIds,',');
        if($recipientIds == '') return false;
        $dbHandle = $this->getDBHandle();
        $modeClause = '';
        if(!empty($mode) || (empty($trackBoth) || $trackBoth != true)) {
            $modeClause = ' AND communication = '. $dbHandle->escape($mode);
        }
        $recipientIdsArray = explode(',', $recipientIds);

        $query = 'SELECT id,recipientId,senderId,recipient,sender,content,product,communication,DATE_FORMAT(contactDate, "%D %b %Y") as contactDate,status FROM trackCommunicationHistory WHERE  product = "responseViewer" and  recipientId IN (?) AND senderId = ?'.$modeClause ;
        
        $resultSet = $dbHandle->query($query, array($recipientIdsArray, $senderId));
        $communicationHistory = array();
        foreach($resultSet->result_array()as $record) {
            if($trackBoth == true) {
                $communicationHistory[$record['recipientId']][$record['communication']] = $record;
            } else {
                $communicationHistory[$record['recipientId']] = $record;
            }
        }
        return $this->xmlrpc->send_response(json_encode($communicationHistory));
    }

    function sTrackCommunication($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $recipientIds = $parameters['1'];
        $senderId = $parameters['2'];
        $sender = $parameters['3'];
        $subject = $parameters['4'];
        $content  = $parameters['5'];
        $product = $parameters['6'];
        $communicationMode = $parameters['7'];
        $dbHandle = $this->getDBHandle('write');
        $dbField = ($communicationMode == 'Email' ? 'email' : 'mobile');
        $recipientIds = trim($recipientIds,',');
        if($recipientIds == '') return false;

        $recipientIdsArray = explode(',', $recipientIds);
        $query = 'SELECT userid, '. $dbField .' FROM tuser WHERE userid IN (?)';
        $resultSet = $dbHandle->query($query,array($recipientIdsArray));
        $userDetails = $resultSet->result_array();
        $communicationsCount = count($userDetails);
        if($communicationMode == 'Email') {
            $mailContentArr = array('content' => $content, 'type'=>'userMail');
            $content = $this->load->view('search/searchMail', $mailContentArr, true);
        }
        for(;--$communicationsCount >= 0;) {
                $data = array();
                $data['recipientId'] = $userDetails[$communicationsCount]['userid'];
                $data['senderId'] = $senderId;
                $data['recipient'] = $userDetails[$communicationsCount][$dbField];
                if(empty($data['recipient'])) {
			$data['recipient'] = "";
                }
                $data['sender'] = $sender;
                $data['subject'] = $subject;
                $data['content'] = $content;
                $data['product'] = $product;
                $data['communication'] = $communicationMode;
                try {
                    $trackCommunicationQuery = $dbHandle->insert_string('trackCommunicationHistory', $data).' ON DUPLICATE KEY UPDATE contactDate=now()';
                    $dbHandle->query($trackCommunicationQuery);
                    $appId = 1;
                    $this->load->library('alerts/Alerts_client');
                    $objalertsClient = new Alerts_client();
                    if($communicationMode == 'Email') {
                        $objalertsClient->externalQueueAdd("12",$sender,$userDetails[$communicationsCount][$dbField],$subject,$content,"html");
                    } else {
                        $objalertsClient->addSmsQueueRecord($appId, $userDetails[$communicationsCount][$dbField], $content, $senderId,"","user-defined"); 
                    }
                } catch (Exception $e) {
                    error_log('ASHISH : ERROR :: trackCommunicationQuery :: '. $trackCommunicationQuery);
                    $response = 'Caught DB Exception '. $e;
                    return $this->xmlrpc->send_response($response);
                }
        }
        $response = $communicationMode .' sent successfully.';
        return $this->xmlrpc->send_response($response);
    }
}
?>
