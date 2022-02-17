<?php

class RedisTracker extends MX_Controller
{
    private $model;
    
    function __construct()
    {
        //$this->load->model('servicemonitormodel');
        //$this->model = $this->servicemonitormodel;
        
        //$this->load->config('service_monitoring');
    }
    
    function index()
    {
        //$stats = json_decode(file_get_contents('/var/www/html/shiksha/edata'), TRUE);
        //$this->load->view('esmain', array('stats' => $stats));
        $this->load->view('main');
    }

    function redisMemoryMonitoring(){

        $this->validateCron();
        $maxMemoryForAlert = 8;// in GBs
        $this->_redis_client = PredisLibrary::getInstance();
        $memoryStatus = $this->_redis_client->infoRedis("memory");

        $memoryUsed  = $memoryStatus['Memory']['used_memory'];
        $memoryUsed = $memoryUsed/(1024*1024*1024);

        if($memoryUsed > $maxMemoryForAlert){

                /**
                 * Send email alert
                 */
                $headers = 'From: ProductionMonitor@shiksha.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
                $to = "ShikshaProdEmergencyTeam@shiksha.com";//,abhinav.k@shiksha.com,ankur.gupta@shiksha.com,amit.kuksal@shiksha.com,aditya.roshan@shiksha.com,naveen.bhola@shiksha.com,pranjul.raizada@shiksha.com,sukhdeep.kaur@99acres.com";

                $subject = "Redis Size Alert : ".$memoryStatus['Memory']['used_memory_human']." used";

                $error_message = "Used memory : ".$memoryStatus['Memory']['used_memory_human'].", Max Memory(after which eviction wil start) : ".$memoryStatus['Memory']['used_memory_peak_human']. ". Please take action.";

                mail($to, $subject, $error_message,$headers);
        }
    }

    /**
     * Cron that instructs Redis to start an Append Only File rewrite process. The rewrite will create a small optimized version of the current Append Only File.
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2018-03-11
     */
    function optimizeAndRewriteRedisAOF(){

        $this->validateCron();
        $this->predisLibrary = PredisLibrary::getInstance();
        $this->predisLibrary->bgrewriteAOF();
    }
}
