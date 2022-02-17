<?php 

class CP_TrackingWorker extends MX_Controller {
	
	function processCP_TrackingQueue(){

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

		$channel->queue_declare('Predictor_Tracking', false, true, false, null);

		error_log(' * Waiting for messages. To exit press CTRL+C');

		$this->load->model('CP/cpmodel');
		$this->cpmodel = new CPModel();
		
		$this->rpmodel  = $this->load->model('RP/rpmodel');
		
		$callback = function($msg){
			try{

				$data = json_decode($msg->body, true);
			    $message = $data['payLoad'];
			  	$trackingData = $message;
			    if(!empty($trackingData)&&(count($trackingData)) && !empty($trackingData)){
			    		if($trackingData['type'] == 'collegePredictor'){
			    			unset($trackingData['type']);

		    				$this->cpmodel->saveTrackingData($trackingData);	
		    				error_log(" * Processed CollegePredictor_Tracking DB Data");
		    			} else if($trackingData['type'] == 'rankPredictor') {
		    				unset($trackingData['type']);
		    				$this->rpmodel->insertActivityLog($trackingData);	
		    				error_log(" * Processed Rank_Tracking DB Data");	
		    			}
	    			
			    }
			    else{
			    	error_log(" * Error Occured ".print_r($data));
			    	mail("listingstech@shiksha.com", "CollegePredictor Worker Error", "Inside CollegePredictor Tracking Data : \n\n".print_r($data));
			    }

			    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

		    }
			catch(Exception $e){

				error_log("JOBQException: ".$e->getMessage());
				error_log(" * Exception Occured ".print_r($data));
				mail("listingstech@shiksha.com", "CollegePredictor Worker Error", "Beacon Data : \n\n".print_r($data));
			}
		};

		$channel->basic_qos(null, 1, null);
		
		$channel->basic_consume('Predictor_Tracking', '', false, false, false, false, $callback);

		while(count($channel->callbacks)) {
		    $channel->wait();
		}
	}

	

}
?>
