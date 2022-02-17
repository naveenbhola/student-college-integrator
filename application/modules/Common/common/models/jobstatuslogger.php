<?php

/**
 * Description of JobStatusLogger
 * @author ashish
 */
class JobStatusLogger extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('dbLibCommon');
        $dbLibObj = DbLibCommon::getInstance('Facebook');
        $this->_db = $dbLibObj->getWriteHandle();
    }

    /**
     * @param $jobType
     * @param $payLoad
     * @return int - jobId
     */
    function logJobAddStatus($jobType, $payLoad) {
        $sql = "insert into jobStatus(jobType,payLoad) values(?,?)";
        $this->_db->query($sql,array($jobType,$payLoad));
        return $this->_db->insert_id();
    }

    /**
     * @param $jobId
     * @param $status
     */
    function updateJobStatus($jobId, $status = "Picked") {
        if(strtolower($status) == 'picked')
            $sql = "update jobStatus set status = 'Picked',pickedAt = now() where jobId = ?";
        else if(strtolower($status) == 'finished')
            $sql = "update jobStatus set status = 'Finished',finishedAt = now() where jobId = ?";
        $this->_db->query($sql,array($jobId));
    }
    
    function getPendingJobs(){
        $sql = "select jobId,jobType,payLoad from jobStatus where status in ('InQueue','Picked')";
        return $this->_db->query($sql)->result_array();
    }

    function reQueuePendingJobs(){
        try{
            $this->load->library("common/jobserver/JobManagerFactory");
            $jobManager = JobManagerFactory::getClientInstance();
            $jobs = $this->getPendingJobs();
            print_r($jobs);
            foreach($jobs as $job){
                $jobManager->addBackgroundJob($job["jobType"], $job["payLoad"],1,$job["jobId"]);
            }
        }
        catch(Exception $e){
            error_log("JOBQException: ".$e->getMessage());
            $this->load->model('smsModel');
            $content = "FrontEnd: Problem with RabbitMQ";
            $msg = $this->smsModel->addSmsQueueRecord('',"9899601119",$content,"271028","0","user-defined","no");
            $msg = $this->smsModel->addSmsQueueRecord('',"9999430665",$content,"1600190","0","user-defined","no");
        }
    }

}

?>
