<?php

/**
 * Description of GearmanJobClient
 * @author ashish
 */
class GearmanJobClient extends JobClient {

    private $client;
    private $gearman_host;
    private $gearman_port;

    public function __construct() {
        parent::__construct();
        $this->CI->load->config('gearman');
        $this->gearman_host = $this->CI->config->item('gearman_server');
        $this->gearman_port = $this->CI->config->item('gearman_port');
        $this->client = new GearmanClient();
        $result = $this->client->addServer($this->gearman_host, $this->gearman_port);

        //check if server added properly else throw exception
        if (!$result) {
            error_log("Got exception while trying to connect to Gearman server.");
            throw new JobConnectionException("Got exception while trying to connect to Gearman server.");
        }
    }

    public function addBackgroundJob($jobType, $payLoad, $priority=1, $jobId = 0) {
        if ($jobId == 0)
            $jobId = $this->logJobRecieved($jobType, $payLoad);
        $payLoad = $this->buildPayload($jobType, $payLoad, $jobId);

        if ($priority == 1)
            $this->client->doBackground($jobType, $payLoad);
        else if ($priority > 1)
            $this->client->doBackgroundHigh($jobType, $payLoad);
        else
            $this->client->doBackgroundLow($jobType, $payLoad);

        //check if job added properly else throw exception
        if ($this->client->returnCode() != GEARMAN_SUCCESS) {
            error_log("Got exception while trying to queue $payLoad for $jobType");
            throw new JobAddException("Got exception while trying to queue $payLoad for $jobType");
        }
    }

}

?>
