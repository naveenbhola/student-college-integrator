<?php

/**
 * Description of JobClient
 * @author ashish
 */
abstract class JobClient {

    public $CI;

    public function __construct() {
        $this->CI = &get_instance();
    }

    /**
     * Add a background job to queue server
     * @param string $jobType
     * @param int $payLoad
     * @return boolean
     */
    abstract public function addBackgroundJob($jobType, $payLoad, $priority = 1, $jobId = 0);

    public function logJobRecieved($jobType, $payLoad) {
        $this->CI->load->model("common/jobstatuslogger", "", true);
        $logger = new JobStatusLogger();
        if (is_array($payLoad) || is_object($payLoad))
            $payLoad = json_encode($payLoad);
        return $logger->logJobAddStatus($jobType, $payLoad);
    }

    public function buildPayload($methodName, $payLoad, $jobId) {
        $ret = array();
        $ret["jobId"] = $jobId;
        $ret["methodName"] = $methodName;
        $ret["payLoad"] = $payLoad;
        return json_encode($ret);
    }

}

?>
