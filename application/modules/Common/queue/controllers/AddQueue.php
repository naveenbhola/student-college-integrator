<?php 
	
	require_once( APPPATH.'/modules/Common/queue/controllers/MQueueAbstract.php' );


	class AddQueue  extends MQueueAbstract {

		function __construct(){
			 parent::__construct();
		}

		function adToQ(){
			$this->addToQueue('mmmMail','mplementing MMM queue');
		}

		function addToQueue($queueName, $msg){
			
			//$this->declareQueue($queueName);
            $this->declareExchange('MMMExchange', 'direct');

            $msg = $this->createMessageForQueue($msg);

            for ($i=0; $i < 20; $i++) { 
            	if($i%9 == 0){
            		$routingKey = 'recommendation';
            	} else{
            		$routingKey = 'marketing';
            	}

            	$this->publishQueue($msg, 'MMMExchange',$routingKey);
            }

            
		}


		function marketingQueue(){
			$this->consummeFromQueue('marketing','marketing');
		}

		function recoQueue(){
			$this->consummeFromQueue('recommendation','recommendation');
		}

		function consummeFromQueue($queueName,$routingKey){
			
			$this->declareExchange('MMMExchange', 'direct', true, false, false);

			$this->declareQueue($queueName);

			$this->bindQueue($queue_name, 'MMMExchange',$routingKey);

			$this->consumeQueue($queueName,'callbackMessage');		

			while(count($channel->callbacks)) {
			    $channel->wait();
			}
		}

		function callbackMessage($msg){
			error_log('###### printing messsage  in log '.$msg->body);
		}
	}


?>