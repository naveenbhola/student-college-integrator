<?php
class ProductDeliveryScripts extends MX_Controller {
	function _init() {
		ini_set("memory_limit", '-1');
		ini_set('max_execution_time', -1);
		
		$this->logFileName = 'log_product_delivery_details_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;
		
		//load library that calls the script
		$this->load->library('ProductDeliveryLib');
		$this->productDeliveryLib = new ProductDeliveryLib();
		
		//load library to send mail
        $this->load->library('alerts_client');
        $this->alertClient = new Alerts_client();
	}

    public function getProductDeliveryReport() {
		return;
		$this->_init();
		
		error_log("Cron started for product delivery data at ".date('y-m-d H:i')."\n", 3, $this->logFilePath);
		
		//send starting mail
		$this->sendMail('started');
		
		//call model
        error_log("Calling library function (getProductClientDetails) at ".date('y-m-d H:i')."\n", 3, $this->logFilePath);
        $this->productDeliveryLib->getProductClientDetails();
		
		//send ending mail
		$this->sendMail('ended');
		error_log("Cron ended for product delivery data at ".date('y-m-d H:i')."\n", 3, $this->logFilePath);
	}
	
	public function getAbroadProductDeliveryReport(){
		return;
		$this->_init();
		$this->productDeliveryLib->getAbroadProductClientDetails();		//This will create the file as well; it does everything
	}
	
	public function sendMail($startOrEnd) {
		return;
		$subject      = "Cron ".$startOrEnd." for product delivery report.";
		$emailIdarray = array('nikita.jain@shiksha.com', 'pankaj.meena@shiksha.com');
		
		foreach($emailIdarray as $key=>$emailId) {
			$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, "", "html", '', 'n');
		}
	}
}