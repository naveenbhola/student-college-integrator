<?php 

class ResponseProfileMappingWorker extends MX_Controller {
	
	function processMappingData(){
		$this->validateCron();
		ini_set("max_execution_time","-1");
		$this->config->load('amqp');
		$this->load->library("common/jobserver/JobManagerFactory");
		$jobManager = JobManagerFactory::getWorkerInstance();

		$this->amqp_host = $this->config->item('amqp_server');
        $this->amqp_port = $this->config->item('amqp_port');
        $this->amqp_user = $this->config->item('amqp_user');
        $this->amqp_pass = $this->config->item('amqp_pass');
		$worker = new PhpAmqpLib\Connection\AMQPConnection($this->amqp_host, $this->amqp_port, $this->amqp_user, $this->amqp_pass);

        $channel = $worker->channel();

		$channel->queue_declare('predictor_response_mapping_to_profile', false, true, false, null);

		error_log(' * Waiting for messages. To exit press CTRL+C');		

		$callback = function($msg){
			try{
				$this->saveData($msg->body);
			    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
		    }catch(Exception $e){
		    	$errorData = array();
		    	$errorData['JOBQException'] = $e->getMessage();
				$errorData['actualData']    = $data;
				mail("ugctech@shiksha.com","Predictor Profile mapping Worker Error","UserInputs Data : \n\n".print_r($errorData,true));
			}
		};

		$channel->basic_qos(null, 1, null);
		
		$channel->basic_consume('predictor_response_mapping_to_profile', '', false, false, false, false, $callback);

		while(count($channel->callbacks)) {
		    $channel->wait();
		}
	}

	function saveData($msg){
		$data = json_decode($msg, true);
	    $message = json_decode($data['payLoad'],true);
	    if(!empty($message['userId']) && count($message['userInputs'])>0){
	    	$userId          = $message['userId'];
	    	$userExamDetails = $message['userInputs'];
	    	$userProfileLib  = $this->load->library('userProfile/UserProfileLib');
	    	$userProfileLib->saveExamProfileData($userId, $userExamDetails);
	    }
	}
}
?>
