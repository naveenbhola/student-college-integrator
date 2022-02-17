<?php
	
	Class CookieBannerTrackingLib
	{
		function __construct()
	    {
			$this->CI     = & get_instance();
			$this->cookie = $this->CI->load->model('common/cookiebannertrackingmodel');
	    }

	    function newUserCookieSet()
	    {
	    	$data = array();
	    	if(isset($_COOKIE['gdpr']))
	    	{
				$userid             = $this->CI->checkUserValidation();
				$data['userid']     = $userid[0]['userid'];
				$session_id         = getVisitorSessionId();
				$data['session_id'] = $session_id;
				if($_COOKIE['gdpr']<=0 || $_COOKIE['gdpr']=="")
	   	 		{
	   	 			return;
	   	 		}
				$data['tracked_on'] = date("Y-m-d H:i:s", $_COOKIE['gdpr']);
				$this->saveCookieData($data);
	    	}
	    }

	    function oldUserCookieSet()
	    {
			$data               = array();
			$userid             = $this->CI->checkUserValidation();
			$data['userid']     = $userid[0]['userid'];
			$session_id         = getVisitorSessionId();
			$data['session_id'] = $session_id;
	    	
	    	if(!isset($_COOKIE['gdpr']))
	    	{
	   	 		$checkData = $this->getCookieDataByUserId($data['userid']);
	   	 		if($checkData == true)
	   	 		{
					$cookie_value = time();
					$cookie_name  = 'gdpr';
					setcookie($cookie_name,$cookie_value, time() + (86400 * 90), '/',COOKIEDOMAIN);
	   	 		}
	    	}
	    	else
	    	{
	   	 		if($_COOKIE['gdpr']<=0 || $_COOKIE['gdpr']=="")
	   	 		{
	   	 			return;
	   	 		}
	   	 		$data['tracked_on'] = date("Y-m-d H:i:s", $_COOKIE['gdpr']);
	   	 		$this->saveCookieData($data);
	   	 	
	    	}
	    }

	    // Calling model function to save the cookie data(array)
	    function saveCookieData($data)
	    {
			$session_id         = getVisitorSessionId();
			$data['session_id'] = $session_id;
	        $this->cookie->saveCookie($data);
	    }

	    // Calling model function to return user cookie data. 
	    
	    function getCookieDataByUserId($userid)
	    {
	        $result = $this->cookie->getCookieByUserId($userid);
	        if(!empty($result))
	        {
	        	return true;
	        }
	        return false;
	    }
	}
?>
