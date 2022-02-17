<?php
include('Sums_Common.php');
/**
 * Controller class for Sums_Mailer
 * 
 */
class SumsMailer extends Sums_Common {
	
	function init() {
		$this->load->library('user_agent','Sums_mailer_client');
	}
	
	function SumsMailerCron($appId,$param)
		{
		$this->load->library('user_agent');
		if( $this->agent->is_browser() or $this->agent->is_robot() or $this->agent->is_mobile()):
			exit('You have not access this file.');
		endif;
		/* initialize sql variable.  */
		$log_message = array();
		$this->load->library('Sums_mailer_client');
		$objSumsMail = new Sums_mailer_client();
		$requested_array = array();
		$arrResults = $objSumsMail->getCronMails($appId,$param);
		if (is_array($arrResults)) {
			// Return string 'INVALID_REQUEST_TYPE' in false
			$requested_array['eventType'] = $param;
			$requested_array['transactionIds'] = $arrResults;
		}
		$response =
			$objSumsMail->sendSumsMails($appId,$requested_array);
		try {
			$log_message['dump_response'] = json_encode($response);
			$log_message['dump_requested_array'] =
				json_encode($requested_array);
			$log_message['appId'] = json_encode($appId);
		} catch (Exception $e) {
			// throw $e;
			log_message('debug',$param);
			error_log_shiksha('Error occoured during SumsMailerCron API call
				with param value' . $param . 'Error reported as follows:-' .
				$e,'SumsMailerCron');
		}
		print_r($log_message);
		}
	
}

?>