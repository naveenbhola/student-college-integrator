<?php
	Class CookieBannerTracking extends MX_Controller
	{

		function _init()
		{
			$this->load->library('CookieBannerTrackingLib');
		}

		function getCookieBanner()
		{
			$result = $this->load->view("mcommon5/cookie_banner.php",array(),true);
			echo $result;
		}

		// Calling library function to save cookie data.


		function saveOldUserCookieData()
		{
			if(isset($_GET['from']) && $_GET['from'] == 'pwa'){
            	
	            $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
	            header("Access-Control-Allow-Origin: ".$requestHeader);
	            header("Access-Control-Allow-Credentials: true");
	            header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
	            header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
	            header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
	            header("Content-Type: application/json; charset=utf-8");
	            
	        }

			$this->_init();
			$this->cookieBannerTrackingLib = new CookieBannerTrackingLib();
			$this->cookieBannerTrackingLib->oldUserCookieSet();
		}

		// Calling library function to fetch cookie data for a given userid

		function getCookieDataByUserId(/*$userid*/)
		{
			//for testing only. delete this userid later.
			$userid = 40;
			$this->_init();
			$this->cookieBannerTrackingLib = new CookieBannerTrackingLib();
			$result = $this->cookieBannerTrackingLib->getCookieDataByUserId($userid);
			return $result;
		}
	}
?>
