<?php

/**
 * Description of RabbitMQJobClient
 * @author ashish
 */
class AMQPJobClient extends JobClient {

    private $client;
    private $amqp_host;
    private $amqp_port;
    private $amqp_user;
    private $amqp_pass;
    private $channel;

    public function __construct() {
        parent::__construct();
        $this->CI->load->config('amqp');
        $this->amqp_host = $this->CI->config->item('amqp_server');
        $this->amqp_port = $this->CI->config->item('amqp_port');
        $this->amqp_user = $this->CI->config->item('amqp_user');
        $this->amqp_pass = $this->CI->config->item('amqp_pass');
        try {
            $this->client = new PhpAmqpLib\Connection\AMQPConnection($this->amqp_host, $this->amqp_port, $this->amqp_user, $this->amqp_pass);
            $this->channel = $this->client->channel();
        } catch (Exception $e) {
            error_log("Got exception " . $e->getMessage() . " while trying to connect to RabbitMQ server.");
            throw new JobConnectionException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function addBackgroundJob($jobType, $payLoad, $priority=1, $jobId = 0) {
        try {
            /* if ($jobId == 0)
                $jobId = $this->logJobRecieved($jobType, $payLoad); */
            $payLoad = $this->buildPayload($jobType, $payLoad, $jobId);
            $this->channel->queue_declare($jobType, false, true, false, null);
            $msg = new PhpAmqpLib\Message\AMQPMessage($payLoad, array("priority" => $priority % 10));
            $this->channel->basic_publish($msg, '', $jobType);
        } catch (Exception $e) {
            error_log("Got exception \"" . $e->getMessage() . "\" while trying to queue $payLoad for $jobType");
            throw new JobAddException($e->getMessage(), $e->getCode(), $e);
        }
    }
    public function purgeQueue($queueName,$noWait=false){
        try {
            $this->channel->queue_purge($queueName,$noWait);
        } catch (Exception $e) {
            error_log("Got exception \"" . $e->getMessage() . "\" while purging the queue $queueName");
            throw new QueuePurgeException($e->getMessage(), $e->getCode(), $e);
        }
    }
    public function __destruct() {
        try {
            $this->channel->close();
            $this->client->close();
        } catch (Exception $e) {
            error_log("Got exception " . $e->getMessage() . " while trying to close connection to RabbitMQ server.");
        }
    }

}

?>
