<?php

class ShikshaSchemaAbstract extends MX_Controller
{
	protected static $validUsers = array(5137653);
	
	function __construct()
	{		
		$this->validateAccess();
	}
	
	function validateAccess()
	{
		//return true;
		if($this->isLoginPage()) {
			return TRUE;
		}
		
		$loggedInUserData = $this->getLoggedInUserData();
		if($loggedInUserData['userId'] && in_array($loggedInUserData['userId'], self::$validUsers)) {
			return TRUE;
		}
		else {
			header("Location: /shikshaSchema/ShikshaSchema/Login");
			exit();
		}
	}
    
	function isLoginPage()
	{
		$uri = $_SERVER['REQUEST_URI'];
		if(strpos($uri, "/shikshaSchema/ShikshaSchema/Login") === 0 || strpos($uri, "/shikshaSchema/ShikshaSchema/doLogin") === 0) {
			return TRUE;
		}
		return FALSE;
	}
	
	function loginUser()
	{
		$userId = (int) Modules::run('user/Login/submit');
		error_log($userId);
        if($userId > 0) {
            if(in_array($userId,self::$validUsers)) {
                echo $userId;
            }
            else {
                Modules::run('user/Login/signOut');
                echo '0';
            }
        }    
        else {
            echo '0';
        }
	}
}
