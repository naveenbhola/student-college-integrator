<?php

class Receive extends MX_Controller{

	private $WorkerClient;

	private function getWorker(){
		$this->load->library('common/jobserver/AMQPJobWorker');
		$this->WorkerClient = new AMQPJobWorker;
		//_P($this->WorkerClient);die;
	}

	public function recieveQueue(){
		
		$this->getWorker();
		
		$this->WorkerClient->receiveMessage();
	}



}


?>