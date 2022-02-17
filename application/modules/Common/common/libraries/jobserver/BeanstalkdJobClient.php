<?php

/**
 * Description of BeanstalkdJobClient
 * @author ashish
 */
class BeanstalkdJobClient extends JobClient {

    private $pheanstalk;
    private $beanstalkd_server;
    private $beanstalkd_port;

    public function __construct() {
        parent::__construct();
        $this->CI->load->config('beanstalkd');
        $this->beanstalkd_server = $this->CI->config->item("beanstalkd_server");
        $this->beanstalkd_port = $this->CI->config->item("beanstalkd_port");
        try {
            $this->pheanstalk = new Pheanstalk($this->beanstalkd_server . ":" . $this->beanstalkd_port);
        } catch (Exception $e) {
            error_log("Got exception " . $e->getMessage() . " while trying to connect to Beanstalkd server.");
            throw new JobConnectionException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function addBackgroundJob($jobType, $payLoad, $priority = 1, $jobId = 0) {
        try {
            if ($jobId == 0)
                $jobId = $this->logJobRecieved($jobType, $payLoad);
            $payLoad = $this->buildPayload($jobType, $payLoad, $jobId);
            $this->pheanstalk->useTube($jobType)->put($payLoad, $priority);
        } catch (Exception $e) {
            error_log("Got exception \"" . $e->getMessage() . "\" while trying to queue $payLoad for $jobType");
            throw new JobAddException($e->getMessage(), $e->getCode(), $e);
        }
    }

}

?>
