<?php
/**
 * This library is responsible for sending mails based on diffrent types of notifications
 *
 * @author Aditya <aditya.roshan@shiksha.com>
 * @version
 **/
class Online_form_mail_client  {

	var $CI = '';
	var $cacheLib;
	static $instance;
	/**
	 *
	 * Return new instance if already not exists
	 *
	 * @access	public
	 * @return	object
	 */
	public static function  getInstance () {
		if(!self::$instance) {
			self::$instance = new StudentDashboardClient();
		}
		return self::$instance;
	}
	/**
	 * this method sets the server url and other parameters required for xml rpc call
	 *
	 * @param none
	 * @return void
	 */
	private function _init($type_action='read')
	{
		$this->CI = & get_instance();
		$this->CI->load->helper('url');
		
	}
	/**
	 * sends email to cobra user
	 *
	 * @param
	 * @return array
	 */
	public function sendMailsToCobraUser($user_id,$form_id,$template_name,$form_details,$profile_data)
	{
		$this->_init();
		if($form_details[0]['deadline']<=0){
			return;
		}
		$data = $this->setRequiredTemplateData($user_id,$form_id,$profile_data, $form_details);
		$template_name = strtolower($template_name);
		$tempDetails['contactValue'] = $profile_data['email'];
		if($template_name == "form_successfully_submitted_institute" || $template_name == "form_cancellation" || $template_name == "update_score_template") {
			$tempDetails['contactValue'] = $data['inst_email_addrs'];
		}
		$data['notification_type'] = "email";
		global $onlineFormsDepartments;
		$data['gdPiName'] = $onlineFormsDepartments[$form_details[0]['departmentName']]['gdPiName'];
		$this->CI->load->library('Online_form_client');
		$onlineClient = new Online_form_client();
		$ResultOfDetails = $onlineClient->getFormCompleteData($appId,$user_id,$form_details[0]['courseId']);
		$data['ResultOfDetails'] = $ResultOfDetails;
		$data['form_details'] = $form_details;

		$content = $this->CI->load->view('Online/Mail/'.$template_name,$data,true);
		if($template_name == "form_partially_filled") {
			if(empty($tempDetails['contactValue'])) {
				$this->CI->load->library('register_client');
				$user_details = $this->CI->register_client->getDetailsforUsers('1',$user_id);
				$tempDetails['contactValue'] = $user_details[0]['email'];
			}

			$subject = "Last date for ".$data['institute_name']." - ".$data['course_name']." applications in ".$form_details[0]['deadline']." day".(intval($form_details[0]['deadline'])>1?'s':'')."!";
			
		//add application link and paytm msg.
		$courseId = $form_details[0]['courseId'];
		$url = SHIKSHA_HOME.'/Online/OnlineForms/showOnlineForms/'.$courseId;	
		$this->CI->load->library('mailerClient');
		$MailerClient = new MailerClient();
		$data['applicationLink'] = $MailerClient->generateAutoLoginLink(1, $tempDetails['contactValue'],$url);
		// add referral text
		$couponLib = $this->CI->load->library('common/couponLib');
		$getOwnCode = $couponLib->getUserCoupon($user_id);
		
		$coupon = ($getOwnCode !='') ? $getOwnCode : 'SHK101';
		
		$paytmOffer = "Complete your application with coupon code ".$coupon." to get Rs.100 cashback on PayTM each time you apply.<br/>";
		
		if($getOwnCode !='')
		{
			$paytmOffer .="Apply to colleges with your code and share it with your friends. Get Rs.100 everytime when you or your friend applies with your code."; 	
		}else{
			$paytmOffer .="Once you apply, you will get your own code that you can share with your friends. Get Rs.100 every time when you or your friend applies with your code.";
		}
		$data['referralText'] = '';//$paytmOffer;
		$content = $this->CI->load->view('Online/Mail/'.$template_name,$data,true);
			
		} else if($template_name == "form_successfully_submitted") {
			$subject = "Your application for ".$data['institute_name']." ".$data['course_name']." has been successfully submitted!";
			$content = $this->CI->load->view('Online/Mail/'.$template_name,$data,true);
		} else if($template_name == "institute_asks_documents") {
			$subject = $data['institute_name']." has requested your documents towards application number ".$data['application_number'];

		}else if($template_name == "institute_asks_photograph") {
			$subject = $data['institute_name']." has requested your photographs towards application number ".$data['application_number'];
		}else if($template_name == "institute_updates_gdpi_date") {
			$subject = $data['gdPiName']." date and Location confirmed by ".$data['institute_name']." towards application number ".$data['application_number'];
		}else if($template_name == "online_payment_declined") {
			$subject = "Payment unsuccessful towards your application for ".$data['institute_name']." ".$data['course_name'];
		}else if($template_name == "online_payment_interrupted") {
			$subject = "Payment unsuccessful towards your application for ".$data['institute_name']." ".$data['course_name'];
		}else if($template_name == "form_successfully_submitted_institute") {
			$subject = "New application form submitted for ".$data['course_name'];
		}else if($template_name == "form_cancellation") {
			$subject = $data['first_name']." ".$data['middle_name']." ".$data['last_name']." has applied for cancellation of his/her application: ".$data['application_number'];
		}else if($template_name == "update_score_template") {
			$subject = $data['first_name']." ".$data['middle_name']." ".$data['last_name']." has updated his/her score against: ".$data['application_number'];
		}else if($template_name == 'liba_payment_advice'){
                         $subject = "LIBA Payment Advice Mail";
		}
		$this->CI->load->library('alerts_client');
		$subject = trim($subject);
		$content = trim($content);
		$tempDetails['contactValue'] = trim($tempDetails['contactValue']);
		$mailTemplates = array('online_payment_interrupted','form_partially_filled','form_successfully_submitted','institute_asks_documents','institute_asks_photograph','online_payment_declined','institute_updates_gdpi_date','form_successfully_submitted_institute');
		if(!empty($tempDetails['contactValue'])) {
			//$response = $this->CI->alerts_client->externalQueueAdd("12", "Help@shiksha.com", strtolower($tempDetails['contactValue']) , $subject, $content, $contentType = "html");
			Modules::run('systemMailer/SystemMailer/onlineFormMailers', strtolower($tempDetails['contactValue']), $template_name, $subject, $content);
                        if(in_array($template_name, $mailTemplates) ) {
                        	$array_email = array(
												'akanksha.sharma@shiksha.com',
												'piyush.kumar@shiksha.com',
												'ankur.gupta@shiksha.com',
												'prabhat.sachan@shiksha.com',
												'naveen.bhola@shiksha.com',
												'neha.maurya@shiksha.com',
												'daneesh.sarbhoy@shiksha.com'
											);
                                foreach($array_email as $mail) {
									$response = $this->CI->alerts_client->externalQueueAdd("12", "Help@shiksha.com", $mail, $subject, $content, $contentType = "html");
                                }
                        }
		}
		return $response;
	}
	/**
	 * sends sms to cobra user
	 *
	 * @param
	 * @return array
	 */
	public function sendSmsToCobraUser($user_id,$form_id,$template_name,$form_details,$profile_data)
	{
		$this->_init();
		$data = $this->setRequiredTemplateData($user_id,$form_id,$profile_data, $form_details);
	
		$template_name = strtolower($template_name);
		$tempDetails['contactValue'] = $profile_data['mobileNumber'];
		if($template_name == "form_successfully_submitted_institute" || $template_name == "form_cancellation" || $template_name == "update_score_template") {
			$tempDetails['contactValue'] = $form_details[0]['instituteMobileNo'];
			//$user_id = $form_details[0]['contact_details_id'];
		}
		if($template_name == "form_partially_filled") {
			if(empty($tempDetails['contactValue'])) {
			$this->CI->load->library('register_client');
			$user_details = $this->CI->register_client->getDetailsforUsers('1',$user_id);
			$tempDetails['contactValue'] = $user_details[0]['mobile'];
			}
		}
		$data['notification_type'] = "sms";
		global $onlineFormsDepartments;
		$data['gdPiName'] = $onlineFormsDepartments[$form_details[0]['departmentName']]['gdPiName'];
		$contentOfSms = $this->CI->load->view('Online/Mail/'.$template_name,$data,true);
		$this->CI->load->library('alerts_client');
		$contentOfSms = trim($contentOfSms);
		$tempDetails['contactValue'] = trim($tempDetails['contactValue']);
		if(!empty($tempDetails['contactValue'])) {
			$responseOfSms = $this->CI->alerts_client->addSmsQueueRecord("12", $tempDetails['contactValue'], $contentOfSms, "$user_id");
		}
	}
	/**
	 * Main method needs to be called to handle mail or sms for cobra user
	 *
	 * @param
	 * @return array
	 */
	public function run($user_id,$form_id,$template_name,$type_notification="both")
	{
		$this->_init();
		if(empty($user_id) || empty($form_id)) {
			return false;
		}
		$this->CI->load->library('online_form_client');
		$form_details = $this->CI->online_form_client->getFormListForUser($user_id,$form_id);
		
		
		
		$profile_data = $this->CI->online_form_client->getFormCompleteData(1, $user_id);
		$response = array();
		if(strtolower($type_notification) == 'email') {
			$response['EMAIL'] = $this->sendMailsToCobraUser($user_id,$form_id,$template_name,$form_details,$profile_data);
		} elseif((strtolower($type_notification) == 'sms')) {
			$response['SMS'] = $this->sendSmsToCobraUser($user_id,$form_id,$template_name,$form_details,$profile_data);
		} else {
			$response['EMAIL'] = $this->sendMailsToCobraUser($user_id,$form_id,$template_name,$form_details,$profile_data);
			$response['SMS'] = $this->sendSmsToCobraUser($user_id,$form_id,$template_name,$form_details,$profile_data);
		}
		return $response;
	}
	/**
	 * sets required data for sms or email
	 *
	 * @param
	 * @return array
	 */
	function setRequiredTemplateData($user_id,$form_id,$profile_data,$form_details) {
		$current_date = date();
		$diff = abs(strtotime($current_date) - strtotime($form_details[0]['last_date']));
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		
		$data['first_name'] = $profile_data['firstName'];
		$data['middle_name'] = $profile_data['middleName'];
		$data['last_name'] = $profile_data['lastName'];
		$data['institute_name'] = $form_details[0]['institute_name'];
		$data['course_name'] = $form_details[0]['courseTitle'];
		$data['last_date'] = $form_details[0]['last_date'];
		$data['no_of_days'] = $form_details[0]['deadline'];
		if(!empty($form_details[0]['instituteSpecId'])) {
			$data['application_number'] = $form_details[0]['instituteSpecId'];
		} else {
			$data['application_number'] = $form_details[0]['onlineFormId'];	
		}
		$data['doc_spec'] = $form_details[0]['documentsRequired'];
		$data['photograph_spec'] = $form_details[0]['imageSpecifications'];
		$data['inst_email_addrs'] = $form_details[0]['instituteEmailId'];
		$data['inst_addrs'] = $form_details[0]['instituteAddress'];
		$data['gdpi_date'] = $form_details[0]['GDPIDate'];
		$data['gdpi_city'] = $form_details[0]['GDPILocation'];
		//$data['inst_contact_details_id'] = $form_details[0]['contact_details_id'];
		$this->CI->load->library('online_form_client');
		$payment_array = $this->CI->online_form_client->getPaymentDetailsByUserId($user_id,$form_id);
		$payment_array = $payment_array['0'];
		$data['payment_mode'] = $payment_array['mode'];
		$data['order_value'] = $payment_array['amount'];
		$data['transaction_date'] = $payment_array['date'];
		$data['transaction_id'] = $payment_array['orderId'];
		$data['transaction_id'] = $payment_array['orderId'];
		$data['payment_status'] = $payment_array['status'];
		$data['creationDate'] = $form_details[0]['creationDate'];
		return $data;
	}
}
?>
