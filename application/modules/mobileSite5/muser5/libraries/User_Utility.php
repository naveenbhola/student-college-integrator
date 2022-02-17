<?php

/**
 * Class User Utility handles registration,login,E Brochure and various other activities
 *
 * @package User Utility
 * @author  
 */

require_once "User_Utility/UserLoginUtility.php";
require_once "User_Utility/UserValidationUtility.php";
require_once "User_Utility/UserRegistrationUtility.php";
require_once "User_Utility/UserEBrochureUtility.php";

class User_Utility {

	private $_ci = null;

	private $userStatus = array();
	
	function __construct($userStatus)
	{
		log_message('debug', "User_Utility Class Initialized.");
			
		$this->_ci =& get_instance();

		$this->userStatus = $userStatus;

		/* Load some config. */
		
		//$this->_ci->load->config('mcommon/mobi_config');

		//$this->mobile_website_category_pagination_count = $this->_ci->config->item('mobile_website_category_pagination_count');
		
  	}

  	private function loadLibrary($library)
  	{
		if ( ! $this->_ci->load->is_loaded($library))
		{
			$this->_ci->load->library($library);
		}
	}
	
  	public function renderloginScreen()
	{
		
	}

	public function validatelogin()
	{

	}

	public function renderRegistrationScreen()
	{


	}

	public function validateRegistration()
	{

	}

	public function SendMailActivity()
	{


	}

	public function renderEBrochureScreen()
	{


	}

	public function validateEBrochure()
	{

	}

	public function registerUser($firstName, $lastName,$mobile, $email)
	{
		$obj_UserRegistrationUtility = new UserRegistrationUtility($this->_ci, $this->userStatus);
		$returnArray =  $obj_UserRegistrationUtility->registerUser($firstName,$lastName, $mobile, $email);
		return $returnArray;
	}
}
