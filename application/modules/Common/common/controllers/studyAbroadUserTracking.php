<?php

class studyAbroadUserTracking extends MX_Controller
{
    private $userStatus;
    function __construct()
    {
        parent::__construct();
        $this->userStatus = $this->checkUserValidation();
        $this->load->model("common/studyabroadusertrackingmodel");
        $this->trackingModel = new studyAbroadUserTrackingModel;
    }
    
    public function trackUser(){
        if(!$this->confirmTracking()){
            return false;
        }
        $userData = array();
        session_start();
        $sessionId = session_id();
        $userData['sessionId'] = $sessionId;
        if($this->userStatus != "false"){
            $userId = reset($this->userStatus);
            $userId = $userId['userid'];
        }else{
            $userId = -1;
        }
        $userData['userId'] = $userId;
        $userData['url'] = $this->input->post("pageUrl");
        $userData['timeStamp'] = date("Y-m-d H:i:s");
        $userData['device'] = $this->deviceDetect();
        $userData['referrer'] = $this->input->post("referrer");
        $this->trackingModel->trackUser($userData);
    }
    
    public function confirmTracking(){
        if($this->isRobot()){
            return false;
        }
        if($this->isShikshaIP()){
            return false;
        }
        return true;
    }
    
    private function isRobot(){
        if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }else {
            return false;
        }
    }
    
    private function isShikshaIP(){
        global $blockedIPsForAbroadListingViewCountTracking;
        if(in_array($this->input->ip_address(),$blockedIPsForAbroadListingViewCountTracking)){
            return true;
        }
        return false;
    }
    
    public function deviceDetect(){
        $tablet_browser = 0;
        $mobile_browser = 0;
         
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tablet_browser++;
        }
         
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
        }
         
        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
        }
         
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda ','xda-');
         
        if (in_array($mobile_ua,$mobile_agents)) {
            $mobile_browser++;
        }
         
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
            $mobile_browser++;
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
              $tablet_browser++;
            }
        }
         
        if ($tablet_browser > 0) {
           return "Tablet";
        }
        else if ($mobile_browser > 0) {
           return "Mobile";
        }
        else {
           return "Desktop";
        }   
    }
}