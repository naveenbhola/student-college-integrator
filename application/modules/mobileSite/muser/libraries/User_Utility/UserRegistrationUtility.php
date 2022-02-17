<?php

/**
 * UserRegistrationUtility class
 *
 * @author      
 * @copyright   
 * @license     http://www.shiksha.com
 * @package     Utility Mobile Site
 * @subpackage  
 * @filesource
 */

class UserRegistrationUtility {

	private $options = array();

	private $ci_obj = null;

	private $userStatus = array();
	
	public function __construct($ci,$userStatus)
	{
		$this->ci_obj = $ci;
		$this->userStatus = $userStatus;
		
		log_message('debug', "UserRegistrationUtility Class Initialized.");
		error_log("UserRegistrationUtility Class Initialized.");
		$this->ci_obj->load->library(array(
	            'miscelleneous',
		    'category_list_client',
		    'listing_client',
		    'register_client',
		    'alerts_client',
		    'lmsLib',
		    'Login_client'
		));
		
		$this->userStatus = $userStatus;
		$this->options['ListingClientObj'] = new Login_client();
		$this->options['register_client'] = new Register_client();
		$this->options['alerts_client'] = new Alerts_client();
	}

        /**
	 * __set
	 *
	 * @access public
	 * @param string $var Variable to set in $options
	 * @param mixed $val Value of $var
	 * @return void
	 */
	public function __set($var,$val)
	{
		$this->options[$var] = $val;
	}

	/**
	* __get
	*
	* @access public
	* @param string $var Variable to get from $options
	* @return void
	*/
	
	public function __get($var)
        {
	    if (!isset($this->options[$var])) {
		    $this->options[$var] = null;
	    }
            return $this->options[$var];
	}

	function registerUser($firstName, $lastName,$mobile, $email)
	{
		$CI =& get_instance();
		if (empty($firstName) || empty($lastName) || empty($mobile) || empty($email))
		{
			throw new Exception('input values are required.');
		}
		$signedInUser = $this->userStatus;
		$appId = 1;
		$ListingClientObj 	= $this->options['ListingClientObj'];
		$register_client 	= $this->options['register_client'];
		$alerts_client 		= $this->options['alerts_client'];
		$returnArray = array();
		// User already logg-in
		if (is_array($this->userStatus)) {
		    if ($firstName != $signedInUser[0]['firstname']) {
		        $updatedStatus = $register_client->updateUserAttribute($appId, $signedInUser[0]['userid'], 'firstname', $firstName);
		    }

		    if ($lastName != $signedInUser[0]['lastname']) { 
 	                        $updatedStatus = $register_client->updateUserAttribute($appId, $signedInUser[0]['userid'], 'lastname', $lastName); 
 		                    } 
		    if ($mobile != $signedInUser[0]['mobile']) {
		        $updatedStatus = $register_client->updateUserAttribute($appId, $signedInUser[0]['userid'], 'mobile', $mobile);
		    }
		    $returnArray['user_already_loggedin'] = 'true';
		    return $returnArray;
		    // already loged-in so jump into make response code block
		} else {
		    $responseCheckAvail = $register_client->getinfoifexists($appId, $email, 'email');
		    if (is_array($responseCheckAvail)) {
		    	/*
		        if ($firstName != $responseCheckAvail[0]['firstname']) {
		            $updatedStatus = $register_client->updateUserAttribute($appId, $responseCheckAvail[0]['userid'], 'firstname', $firstName);
		        }

		        if ($lastName != $responseCheckAvail[0]['lastname']) { 
 		                            $updatedStatus = $register_client->updateUserAttribute($appId, $responseCheckAvail[0]['userid'], 'lastname', $lastName); 
	                        } 

		        if ($mobile != $responseCheckAvail[0]['mobile']) {
		            $updatedStatus = $register_client->updateUserAttribute($appId, $responseCheckAvail[0]['userid'], 'mobile', $mobile);
		        }
		        */
		        $responseCheckAvail = $register_client->getinfoifexists($appId, $email, 'email');
		        $signedInUser = $responseCheckAvail;
		        $returnArray['user_exit_in_db'] = 'true';
		        return $returnArray;
		        // ask for login User
		    } else {
		        $displayname = $firstName;
			$displayname = $firstName . rand(1, 100000);
		        $responseCheckAvail = $register_client->checkAvailability($appId, $displayname, 'displayname');
		        while ($responseCheckAvail == 1) {
				$responseCheckAvail = $register_client->checkAvailability($appId, $displayname, 'displayname');
		        }
		        $mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
		        $password = 'shiksha@' . rand(1, 1000000);
		        $ePassword = sha256($password);
		        $userarray['appId'] = 1;
		        $userarray['email'] = $email;
		        $userarray['password'] = $password;
		        $userarray['ePassword'] = $ePassword;
		        $userarray['displayname'] = $displayname;
		        $userarray['mobile'] = $mobile;
		        $userarray['firstname'] = $firstName;
		        $userarray['lastname'] = $lastName; 
		        $userarray['sourceurl'] = "mobile";
		        $userarray['usergroup'] = 'veryshortregistration';
		        $userarray['quicksignupFlag'] = "requestinfouser";
				$userarray['sourcename'] = 'MARKETING_FORM';
				$userarray['resolution'] = $mobile_details['resolution_width'] . "X" . $mobile_details['resolution_height'];
				$userarray['viamobile'] = 1;
				$userarray['viamail'] = 1;
				$userarray['vianewsletteremail'] = 1;
				$userarray['browser'] = $_COOKIE['ci_mobile_capbilities'];
		        $addResult = $register_client->adduser_new($userarray);
		        $value = $email . '|' . $ePassword;
		        $this->cookie($value);
		        $signedInUser = $register_client->getinfoifexists(1, $email, 'email');
		        $this->sendWelcomeMailToNewUser($email, $password, $addReqInfo, $addResult, $actiontype,$signedInUser);
		        $CI->load->model('user/usermodel');
				$CI->usermodel->trackUserLogin($signedInUser[0]['userid']);
		        $returnArray['user_register'] = 'true';
		        return $returnArray;
		    }
		}
	}
    
    private function cookie($value)
    {
        $value .= '|pendingverification';
        setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
    }

    private function sendWelcomeMailToNewUser($email, $password, $addReqInfo,$addResult,$actiontype,$userinfo)
    {
        $alerts_client = new Alerts_client();
        $data = array();
        try {
            $subject  = "Your Shiksha Account has been generated";
            $data['usernameemail'] = $email;
            $data['userpasswordemail'] = $password;
	    $content  = $this->ci_obj->load->view('user/RegistrationMail',$data,true);
            $response = $alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,$contentType="html");
            /*
            //For Shiksha Inbox
	    $this->ci_obj->load->library('Mail_client');
            $mail_client = new Mail_client();
            $receiverIds = array();
            array_push($receiverIds,$addResult['status']);
            $body = $content;
            $content = 0;
            $sendmail = $mail_client->send($appId,1,$receiverIds,$subject,$body,$content);
            */
        } catch (Exception $e) {
	    error_log(' Error occoured sendWelcomeMailToNewUser' . $e,'MultipleApply');
        }
    }

    public function __destruct()
	{
		
	}
}

