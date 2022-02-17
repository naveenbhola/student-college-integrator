<?php
	class MQueue{
		
		private $connection;
		private $channel;
		private $JobClient;
		private $amqp_host;
	    private $amqp_port;
	    private $amqp_user;
	    private $amqp_pass;
		private $CI;

		function __construct(){
			$this->CI = & get_instance();
			$this->getQueueClient();
		}

		private function getQueueClient(){
			//_P('returning');
			//return;
			$this->setAMPQConfig();

	        try {
	            $this->connection = new PhpAmqpLib\Connection\AMQPConnection($this->amqp_host, $this->amqp_port, $this->amqp_user, $this->amqp_pass);

	            $this->channel = $this->connection->channel();
	            
	        } catch (Exception $e) {
	            error_log("Got exception " . $e->getMessage() . " while trying to connect to RabbitMQ server.");
	            //throw new JobConnectionException($e->getMessage(), $e->getCode(), $e);
	        }
		}

		private function setAMPQConfig(){
			$this->CI->load->config('amqp');
	        $this->amqp_host = $this->CI->config->item('amqp_server');
	        $this->amqp_port = $this->CI->config->item('amqp_port');
	        $this->amqp_user = $this->CI->config->item('amqp_user');
	        $this->amqp_pass = $this->CI->config->item('amqp_pass');
		}

		private function getChannel(){
	    	return $this->channel;
	    }

	   	private function getConnection(){
	        return $this->connection;
	    }

	    public function __destruct() {
            $this->channel->close();
            $this->connection->close();
        
    	}

	}
?>