<?php

/**
 * Description of GearmanJobWorker
 * @author ashish
 */
class GearmanJobWorker extends JobWorker {

    private $worker;
    private $gearman_host;
    private $gearman_port;

    public function __construct() {
        parent::__construct();
        $this->CI->load->config('gearman');
        $this->gearman_host = $this->CI->config->item('gearman_server');
        $this->gearman_port = $this->CI->config->item('gearman_port');
        $this->worker = new GearmanWorker();
        $result = $this->worker->addServer($this->gearman_host, $this->gearman_port);
        //check if server added properly else throw exception
        if (!$result) {
            error_log("Got exception while trying to connect to Gearman server.");
            throw new JobConnectionException("Got exception while trying to connect to Gearman server.");
        }
    }

    public function registerMethod($methodName, $localFunctionName) {
        $result = $this->worker->addFunction($methodName, array($this, "processResponse"));
        $this->callable[$methodName] = $localFunctionName;
        //check if function binding done successfully else throw exception
        if (!$result) {
            error_log("Got exception while trying to bind method $methodName of Gearman to local function.");
            throw new JobMethodRegisterException("Got exception while trying to bind method $methodName of Gearman to local function.");
        }
    }

    public function work() {
        $this->worker->work();

        //check for error, if yes throw exception
        if ($this->worker->returnCode() != GEARMAN_SUCCESS && $this->worker->getErrno() == 111) {
            error_log("Got exception while trying to connect to Gearman server.");
            throw new JobConnectionException("Got exception while trying to connect to Gearman server.");
        }
    }

    public function getPayload($job) {
        return $job->workload();
    }

}

?>
