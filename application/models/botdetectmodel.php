<?php 

class BotDetectModel extends MY_Model 
{
    function __construct() {
        parent::__construct('AppMonitor');
    }
        
    function trackShowCaptcha($ip, $userAgent, $sessionId, $status)
    {
        $dbHandle = $this->getWriteHandle();
        
        $data = array(
            'ip' => $ip,
            'useragent' => $userAgent,
            'sessionid' => $sessionId,
            'action' => 'show_captcha',
            'status' => $status,
            'created' => date('Y-m-d H:i:s')
        );
        
        $dbHandle->insert('botdetection', $data);
    }
    
    function trackVerifyCaptcha($sessionId)
    {
        $dbHandle = $this->getWriteHandle();
        
        $ip = trim($_SERVER['HTTP_TRUE_CLIENT_IP']);
        $userAgent = trim($_SERVER['HTTP_USER_AGENT']);
        
        $data = array(
            'ip' => $ip,
            'useragent' => $userAgent,
            'sessionid' => $sessionId,
            'action' => 'verify_captcha',
            'created' => date('Y-m-d H:i:s')
        );
        
        $dbHandle->insert('botdetection', $data);
    }
} 
