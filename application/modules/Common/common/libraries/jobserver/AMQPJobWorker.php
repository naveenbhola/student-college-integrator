<?php

/**
 * Description of RabbitMQJobWorker
 * @author ashish
 */
class AMQPJobWorker extends JobWorker {

    private $worker;
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
            $this->worker = new PhpAmqpLib\Connection\AMQPConnection($this->amqp_host, $this->amqp_port, $this->amqp_user, $this->amqp_pass);
            $this->channel = $this->worker->channel();
        } catch (Exception $e) {
            error_log("Got exception " . $e->getMessage() . " while trying to connect to RabbitMQ server.");
            throw new JobConnectionException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function registerMethod($methodName, $localFunctionName) {
        try {
            $this->channel->queue_declare($methodName, false, true, false, null);
            $this->channel->basic_consume($methodName, '', false, true, false, false, array($this, "processResponse"));
            $this->callable[$methodName] = $localFunctionName;
        } catch (Exception $e) {
            error_log("Got exception " . $e->getMessage() . " while trying to bind method $methodName of RabbitMQ to local function.");
            throw new JobMethodRegisterException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function work() {
        $this->channel->wait();
    }

    public function getPayload($job) {
        return $job->body;
    }

    public function __destruct() {
        try {
            $this->channel->close();
            $this->worker->close();
        } catch (Exception $e) {
            error_log("Got exception " . $e->getMessage() . " while trying to close connection to RabbitMQ server.");
        }
    }

}

?>
