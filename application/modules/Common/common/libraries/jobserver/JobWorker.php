<?php

/**
 * Description of JobServer
 * @author ashish
 */
abstract class JobWorker {

    public $CI;
    public $callable = array();

    public function __construct() {
        $this->CI = &get_instance();
    }

    /**
     * Register worker method
     * @param string $methodName - task name which server sends
     * @param string/array $localFunctionName - function which serves the request
     * @return boolean
     */
    abstract public function registerMethod($methodName, $localFunctionName);

    /**
     * Worker method to wait for job
     * Waits until the worker gets a job, client should put it a loop if planning a never-ending worker process.
     */
    abstract public function work();

    /**
     * @param $job - Job object recieved
     */
    abstract public function getPayload($job);

    public function processResponse($job) {
        //get jobId and actual payLoad for the job
        $request = json_decode($this->getPayload($job), true);
        $jobId = $request["jobId"];
        $methodName = $request["methodName"];
        $payLoad = $request["payLoad"];

        //put Picked status for the job
        $this->CI->load->model("common/jobstatuslogger", "", true);
        $logger = new JobStatusLogger();
        $logger->updateJobStatus($jobId, "Picked");

        //execute function with actual payLoad
        call_user_func($this->callable[$methodName], $payLoad);

        //put Finished status for the job
        $logger->updateJobStatus($jobId, "Finished");
    }

}

?>
