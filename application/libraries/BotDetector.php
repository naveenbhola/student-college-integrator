<?php

class BotDetector
{
    private $CI;
    private $cache;
    private $model;

    function __construct()
    {
        $this->CI = &get_instance();
        
        $this->CI->load->library('CacheLib');
        $this->cache = new CacheLib();
    }

    /**
     * Register current request with Bot Detection system
     */
    public function detect()
    {
        /**
         * Get parameters for bot detection
         */
        $ip = trim($_SERVER['HTTP_TRUE_CLIENT_IP']);
        $userAgent = trim($_SERVER['HTTP_USER_AGENT']);

        global $NM_SERVER_IPS;
        if($ip == '127.0.0.1' || in_array($ip, $NM_SERVER_IPS)) {
           return;
        }

        $referer = $_SERVER['HTTP_REFERER'];
        $pageURI = getCurrentPageURL();
        
        /**
         * Skip detection for captcha page
         */
        if(strpos($pageURI, "/SecurityCheck/") !== FALSE || strpos($pageURI, "/webhook/getMCResponse") !== FALSE) {
            return;
        }

        $params = array(
            'reqtype' => 'getsid',
            'ip' => $ip,
            'ua' => $userAgent,
            //'uri' => $pageURI,
            //'ref' => $referer,
            'rnd' => rand(1, 1000000)
        );

        $response = $this->doRequest($params);

        /**
         * If valid response not received, do nothing
         */
        if(!is_array($response)) {
            return;
        }

        //error_log('CABIS:: Send API Response: '.$ip.'_#_'.$userAgent.'_#_'.$response['data']['sid'].'_#_'.$response['data']['status']);

        $sessionId = $response['data']['sid'];
        $status = $response['data']['status'];

        global $isGoodBot;
        if(intval($status) == 4){
                $isGoodBot = 1;
        }
        else{
                $isGoodBot = 0;
        }

        /**
         * If bad user, show captcha page
         */
        if(intval($status) < 0) {
            
            if($this->cache->get('cabis_'.$sessionId) != '1') {            
                //error_log('CABIS:: Send API Response: '.$ip.'_#_'.$userAgent.'_#_'.$response['data']['sid'].'_#_'.$response['data']['status']);
                
                $this->cache->store('cabis_'.$sessionId, '1', 86400);
                $this->CI->load->model('botdetectmodel');
                $this->model = new BotDetectModel();
                $this->model->trackShowCaptcha($ip, $userAgent, $sessionId, $status);
            }
            
            // moved temporarily
            header("Location: /SecurityCheck/index?sessionId=".$sessionId."&return=".base64_encode($pageURI), true, 307); 
            exit();
        }
    }

    /**
     * Set session as "Captcha Verified"
     */
    public function validateCaptcha($sessionId)
    {
        $params = array(
            'reqtype' => 'captchavalidate',
            'reqval' => $sessionId,
            'rnd' => rand(1, 1000000)
        );

        $this->doRequest($params);
        
        $this->CI->load->model('botdetectmodel');
        $this->model = new BotDetectModel();
        $this->model->trackVerifyCaptcha($sessionId);
    }

    private function doRequest($params = array())
    {
        $url = $this->makeApiRequestUrl($params);

        //error_log("CABIS:: API: ".$url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 150);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 150);

        $result = curl_exec($ch);

        if($result === false) {
            error_log('CABIS:: Curl error: ' . curl_error($ch));
            return false;
        }
        else {
            $response = json_decode($result, TRUE);
            return $response;
        }
    }

    private function makeApiRequestUrl($params = array())
    {
        $paramArr = array();
        foreach($params as $paramKey => $paramVal) {
            $paramArr[] = $paramKey.'='.urlencode($paramVal);
        }

        return BOT_DETECTOR_URL.'?'.implode('&', $paramArr);
    }
}
