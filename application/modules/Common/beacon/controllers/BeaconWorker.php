<?php 

class BeaconWorker extends MX_Controller {
	
	function processBeaconQueue(){

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

		$channel->queue_declare('BeaconQueue', false, true, false, null);

		error_log(' * Waiting for messages. To exit press CTRL+C');

		$this->load->model('beacon/beaconmodel');
    	$this->beaconModel = new Beaconmodel();

    	$this->load->library('beacon/TrafficIndexer');
		$this->indexer = new TrafficIndexer;

		$this->sessionDBData = array();
		$this->pageviewDBData = array();
		$this->sessionESData = array();
		$this->pageviewESData = array();
		$this->batchSize = 20;
		if (ENVIRONMENT != 'production'){
            $this->batchSize = 1;
        }
		

		$callback = function($msg){

			try{
				// error_log(" * Message received");
				// $data = json_decode($msg, true);
				$data = json_decode($msg->body, true);
			    $message = $data['payLoad'];
			    if(!empty($message) && $message['trackingtype'] == 'ViewcountTracking'){

			    		$beaconData = json_decode($message['data'], true);

			    		if($message['logType'] == 'SessionDB')
			    		{
			    			unset($beaconData['seo_search_engine']);
			    			unset($beaconData['seo_search_query']);
							$this->sessionDBData[] = $beaconData;
			    			if((count($this->sessionDBData) % $this->batchSize == 0) && !empty($this->sessionDBData)){
			    				$this->beaconModel->bulkTrackSession($this->sessionDBData);	
			    				$this->sessionDBData = array();
			    				error_log(" * Processed Session DB Data");
			    			}
			    		} 
			    		else if($message['logType'] == 'PageviewDB')
			    		{
		    				$this->pageviewDBData[] = $beaconData;
			    			if((count($this->pageviewDBData) % $this->batchSize == 0) && !empty($this->pageviewDBData)){
			    				$this->beaconModel->bulkTrackPageViews($this->pageviewDBData);	
			    				$this->pageviewDBData = array();
			    				error_log(" * Processed Pageview DB Data");
			    			}
			    		} 
			    		else if($message['logType'] == 'SessionElastic')
			    		{
		    				$this->sessionESData[] = $beaconData;
			    			if((count($this->sessionESData) % $this->batchSize == 0) && !empty($this->sessionESData)){
			    				$this->indexer->bulkIndexSession($this->sessionESData);	
			    				$this->sessionESData = array();
			    				error_log(" * Processed Session ES Data");
			    			}
			    		} 
			    		else if($message['logType'] == 'PageviewElastic')
			    		{
		    				$this->pageviewESData[] = $beaconData;
			    			if((count($this->pageviewESData) % $this->batchSize == 0) && !empty($this->pageviewESData)){
			    				$this->indexer->bulkIndexPageView($this->pageviewESData);	
			    				$this->pageviewESData = array();
			    				error_log(" * Processed Pageview ES Data");
			    			}
			    		}
			    		// error_log(" * Message was sent");
			    }
			    else{
			    	error_log(" * Error Occured ".print_r($data));
			    	mail("romil.goel@shiksha.com", "Beacon Worker Error", "Inside Beacon Data : \n\n".print_r($data));
			    }
			    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);	
		    }
			catch(Exception $e){
				error_log("JOBQException: ".$e->getMessage());
				error_log(" * Exception Occured ".print_r($data));
				mail("romil.goel@shiksha.com", "Beacon Worker Error", "Beacon Data : \n\n".print_r($data));
			}
		};

		$channel->basic_qos(null, 1, null);
		
		$channel->basic_consume('BeaconQueue', '', false, false, false, false, $callback);

		while(count($channel->callbacks)) {
		    $channel->wait();
		}
	}
	
	function processQueue(){
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

		$channel->queue_declare('BeaconDataQueue', false, true, false, null);

		error_log(' * Waiting for messages. To exit press CTRL+C');

		$this->load->model('beacon/beaconmodel');
    	$this->beaconModel = new Beaconmodel();

    	$this->indexer = $this->load->library('beacon/TrafficIndexer',array("ESHost"=>"ES6"));
		$this->sessionDBData = array();
		$this->pageviewDBData = array();
		$this->sessionESData = array();
		$this->pageviewESData = array();
		$this->batchSize = 20;
		if (ENVIRONMENT != 'production'){
            $this->batchSize = 1;
        }
		
		$callback = function($msg){
			try{
				// error_log(" * Message received");
				// $data = json_decode($msg, true);
				$data = json_decode($msg->body, true);
			    $message = $data['payLoad'];
			    if(!empty($message) && $message['trackingtype'] == 'ViewcountTracking'){
			    		$beaconData = json_decode($message['data'], true);
			    		if($message['logType'] == 'SessionDB')
			    		{
			    			unset($beaconData['seo_search_engine']);
			    			unset($beaconData['seo_search_query']);
							$this->sessionDBData[] = $beaconData;
			    			if((count($this->sessionDBData) % $this->batchSize == 0) && !empty($this->sessionDBData)){
			    				$this->beaconModel->bulkTrackSession($this->sessionDBData);	
			    				$this->sessionDBData = array();
			    				error_log(" * Processed Session DB Data");
			    			}
			    		}
			    		else if($message['logType'] == 'PageviewDB')
			    		{
		    				/*$this->pageviewDBData[] = $beaconData;
			    			if((count($this->pageviewDBData) % $this->batchSize == 0) && !empty($this->pageviewDBData)){
			    				$this->beaconModel->bulkTrackPageViews($this->pageviewDBData);	
			    				$this->pageviewDBData = array();
			    				error_log(" * Processed Pageview DB Data");
			    			}*/
			    		} 
			    		else if($message['logType'] == 'SessionElastic')
			    		{
		    				$this->sessionESData[] = $beaconData;
			    			if((count($this->sessionESData) % $this->batchSize == 0) && !empty($this->sessionESData)){
			    				$this->indexer->bulkIndexToRealTimeSession($this->sessionESData);	
			    				$this->sessionESData = array();
			    				error_log(" * Processed Session ES Data");
			    			}
			    		} 
			    		else if($message['logType'] == 'PageviewElastic')
			    		{
		    				$this->pageviewESData[] = $beaconData;
			    			if((count($this->pageviewESData) % $this->batchSize == 0) && !empty($this->pageviewESData)){
			    				$this->indexer->bulkIndexToRealTimePageView($this->pageviewESData);	
			    				$this->pageviewESData = array();
			    				error_log(" * Processed Pageview ES Data");
			    			}
			    		}
			    		// error_log(" * Message was sent");
			    }
			    else{
			    	error_log(" * Error Occured ".print_r($data));
			    	mail("romil.goel@shiksha.com", "Beacon Worker Error", "Inside Beacon Data : \n\n".print_r($data));
			    }
			    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);	
		    }
			catch(Exception $e){
				error_log("JOBQException: ".$e->getMessage());
				error_log(" * Exception Occured ".print_r($data));
				mail("romil.goel@shiksha.com", "Beacon Worker Error", "Beacon Data : \n\n".print_r($data));
				exit();
			}
		};

		$channel->basic_qos(null, 1, null);
		
		$channel->basic_consume('BeaconDataQueue', '', false, false, false, false, $callback);

		while(count($channel->callbacks)) {
		    $channel->wait();
		}
	}

}
?>
