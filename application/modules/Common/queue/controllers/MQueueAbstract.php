<?php 

	class MQueueAbstract extends MX_Controller{
		private $MQueue;
		private $channel;

		function __construct(){

			$this->MQueue = $this->load->library('MQueue');
			$this->channel = $this->MQueue->getChannel();
			
		}

		function declareQueue($queueName,$messageDurability = true){

			$response = $this->channel->queue_declare($queueName, false, $messageDurability, false, false);		//handle params in arguments
		}

		function declareExchange($exchangeName, $exchangeType,$durability = true){
			
			$this->channel->exchange_declare($exchangeName, $exchangeType, false, $durability, false); //handle params in arguments
		}

		function publishQueue($msg, $exchangeName,$routingKey){
			
			$this->channel->basic_publish($msg, $exchangeName, $routingKey);
		}

		function bindQueue($queue_name,$exchangeName,$routingKey){

			$this->channel->queue_bind($queue_name, $exchangeName, $routingKey);
		}

		function consumeQueue($queueName,$callbackFunction){
			$this->channel->basic_consume($queueName, '', false, true, false, false, array($this, $callbackFunction));
		}

		function getChannel(){
			return $this->channel;
		}

		function createMessageForQueue($msg, $msgParams= array('delivery_mode' => 2)){

			$msg = new PhpAmqpLib\Message\AMQPMessage($msg, $msgParams);
			return $msg;
		}
	}

?>