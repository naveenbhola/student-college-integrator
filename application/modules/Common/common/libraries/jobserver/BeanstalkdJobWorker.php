<?php

/**
 * Description of BeanstalkdJobWorker
 * @author ashish
 */
class BeanstalkdJobWorker extends JobWorker {

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
        //error_log(print_r($this->pheanstalk,true));
    }

    public function registerMethod($methodName, $localFunctionName) {
        try {
            $this->pheanstalk->watch($methodName)->ignore("default");
            $this->callable[$methodName] = $localFunctionName;
        } catch (Exception $e) {
            error_log("Got exception " . $e->getMessage() . " while trying to bind method $methodName of Beanstalkd to local function.");
            throw new JobMethodRegisterException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function work() {
        $job = $this->pheanstalk->reserve();
        $this->processResponse($job);
        $this->pheanstalk->delete($job);
    }

    public function getPayload($job) {
        return $job->getData();
    }

}

?>
