<?php
class CabisCall extends MX_Controller{
    function __construct()
    {
        $this->load->library('CacheLib');
        $this->cache = new CacheLib();

        $this->load->model('botdetectmodel');
        $this->model = new BotDetectModel();
        switch (ENVIRONMENT) {
                case 'development':
                    $requestHeader = "http://localshiksha.com:3022";
                    break;

                case 'test1':
                    $requestHeader = "https://testpwa.shiksha.com";
                    break;

                case 'production':
                    $requestHeader = SHIKSHA_HOME;
                    break;
	}

        header("Access-Control-Allow-Origin: ".$requestHeader);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");
    }

    function detect(){
        $ip =  $this->input->post('ip');
        $sessionId=  $this->input->post('sessionId');
        $userAgent=  $this->input->post('userAgent');
        $status =  $this->input->post('status');
        if($this->cache->get('cabis_'.$sessionId) != '1') {
            $this->cache->store('cabis_'.$sessionId, '1', 86400);
            $this->model->trackShowCaptcha($ip, $userAgent, $sessionId, $status);
        }else{
            echo 'SECURITY_CHECK_REQUIRED';
            exit();
        }
    }
}
?>
