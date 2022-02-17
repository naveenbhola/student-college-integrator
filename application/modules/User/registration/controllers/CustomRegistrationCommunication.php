<?php
/**
 * File foe handling communcication via SMS and email
 */ 

 /**
  * Controller handling the Communication
  */ 
class CustomRegistrationCommunication extends MX_Controller
{
    /**
     * Member variable for handling the instance of Model
     * @var string
     */
    private $model;
    
    /**
     * Constructor 
     */ 
    function __construct()
    {
        $this->load->model('registration/registrationmodel');
        $this->model = $this->registrationmodel;
    }
    
    /**
     * Function to initate the Communication
     */
    function index()
    {
        require FCPATH.'globalconfig/customRegistrationCommunication.php';
        
        foreach($customRegistrationCommunication as $clientKey => $communicationData) {
            $this->_sendCustomRegistrationCommunication($clientKey,$communicationData);
        }
    }
    
    /**
     * Function to perform actual communication
     *
     * @param string $clientkey
     * @param array $data
     */ 
    private function _sendCustomRegistrationCommunication($clientkey,$data)
    {
        $leads = $this->_getLeads($clientkey,$data['CRITERIA'],$data['STARTDATE']);
                
        foreach($leads as $lead) {
            if(!empty($data['EMAIL'])) {
                $this->_sendEmail($lead['email'],$data['EMAIL']['emailSubject'],$data['EMAIL']['emailContent'],$data['EMAIL']['senderName'],$data['EMAIL']['beacon']);
            }
            
            if(!empty($data['SMS'])) {
                $this->_sendSMS($lead['mobile'],$data['SMS']['SMSContent'],$lead['userid']);
            }
            
            $this->_logCommunicatedLead($clientkey,$lead['userid']);
        }
    }
    
    /**
     * Function to get the Leads information
     * 
     * @param string $clientKey
     * @param array $desiredCriteria
     * @param string $startDate
     *
     * @return array 
     */ 
    private function _getLeads($clientKey,$desiredCriteria,$startDate)
    {
        $lastCommunicatedLead = (int) $this->model->getLastCommunicatedLead($clientKey);
        if($desiredCriteria['type'] == 'desiredCourses') {
            return $this->model->getLeadsForCustomRegistrationCommunicationByDesiredCourses($desiredCriteria['conditions'],$lastCommunicatedLead,$startDate);
        }
        else if($desiredCriteria['type'] == 'studyAbroad') {
            return $this->model->getLeadsForCustomRegistrationCommunicationByStudyAbroad($lastCommunicatedLead,$startDate);
        }
    }
    
    /**
     * Function to send email
     *
     * @param string $email
     * @param string $subject
     * @param string $content
     * @param string $senderName
     * @param string $beacon
     */ 
    private function _sendEmail($email,$subject,$content,$senderName,$beacon)
    {
        /**
         * Add beacon for open rate
         */
        if($beacon) {
            $content .= "<div style='display:none'><img src='".SHIKSHA_HOME."/index.php/mailer/Mailer/blank/MQ--/mailer-".$beacon."/".$email."' /></div>";
        }
        
        $this->load->library('alerts/Alerts_client');
        $alertsClient = new Alerts_client;
        $alertsClient->externalQueueAdd(12,'info@shiksha.com',$email,$subject,$content,'html',"0000-00-00 00:00:00",'n',array(),null,null,$senderName);
    }
    
    /**
     * Function to send SMS
     *
     * @param string $mobile
     * @param string $content
     * @param string $userId
     */
    private function _sendSMS($mobile,$content,$userId)
    {
        $this->load->library('alerts/Alerts_client');
        $alertsClient = new Alerts_client;
        $alertsClient->addSmsQueueRecord(12,$mobile,$content,$userId,'0000-00-00 00:00:00','user-defined');
    }
    
    /**
     * Function to insert the Log Communicated lead to DB
     *
     * @param string $clientKey
     * @param string $leadId
     */ 
    private function _logCommunicatedLead($clientKey,$leadId)
    {
        $this->model->logCommunicatedLead($clientKey,$leadId);
    }
}