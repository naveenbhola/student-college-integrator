<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * File to check User Agent String
 * check cookie or $GLOBALS['flag_mobile_user_agent'];
 * load views and controllers according to request
 */

function whitelistDeviceList()
{
	$whitelist = array
	(
		'iPad'
	);
	foreach( $whitelist as $browser )
	{
		if( strstr($_SERVER["HTTP_USER_AGENT"],$browser) && strstr($_SERVER["HTTP_USER_AGENT"],'Mac OS')) {
			return true;
		}
	}
	return false;
}

/**
 * File to check User Agent
 * check cookie or $GLOBALS['flag_mobile_user_agent'];
 */
function get_mobile_useragent()
{
	/*
	 * in order to prevent abroad & national mobile sites messing with each other,
	 * we need to identify when a user switches from national mobile site to abroad mobile site & vice versa
	 */
	unsetCookieWhenSwitchingAcrossMobileSites();
	
	 /**
	  * if request come from ipad 
	  */
	if (whitelistDeviceList() == true and $_COOKIE['user_force_cookie'] != 'YES')
	{
		/**
		 * reset mobile cookie
		 * set user force cookie
		 * and redirect to home page
		 * IT's Desktop request :-)
		 */
		setcookie('ci_mobile','mobile',(time() -(3600*24)),'/',COOKIEDOMAIN);
		setcookie('ci_mobile_js_support','yes',(time() -(3600*24)),'/',COOKIEDOMAIN);
		setcookie('user_force_cookie','YES',0,'/',COOKIEDOMAIN);
		$_COOKIE['user_force_cookie'] = 'YES';
       		header('Location:'.SHIKSHA_HOME);
		exit();
	}
	//if ((!isset($_COOKIE['ci_mobile'])) && ($_COOKIE['ci_mobile'] != 'mobile') && (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) and whitelistDeviceList() == false)
	//if ((!isset($_COOKIE['ci_mobile'])) && ($_COOKIE['ci_mobile'] != 'mobile') && whitelistDeviceList() == false)
	if ( whitelistDeviceList() == false )
	{
		// Check if this is Mobile Call using Akamai header. If header not found, load Mobile_Detect library
		if(!ENABLE_HTML4_MOBILE_WEBSITE && isset ($_SERVER['HTTP_X_MOBILE']) && $_SERVER['HTTP_X_MOBILE'] != "" ){
			$isMobile       = ($_SERVER['HTTP_X_MOBILE']=="True")?1:0;
		}
		else{
			require(FCPATH . APPPATH . "modules/mobileSite/mcommon/libraries/Mobile_Detect.php");
			$detect 	= new Mobile_Detect();
			$isMobile 	= $detect->isMobile();
		}

		/**
		 * if mobile cookie is not set
		 * AND its not ipad
		 * load WURFL now .. seems its mobile device request
		 */
		
		//set mobile_site_user cookie as national by default
		if($isMobile){
			setcookie('mobile_site_user','national',time() + 2592000,'/',COOKIEDOMAIN);
			$_COOKIE['mobile_site_user'] = 'national';
		}
		
		// check if user has opened abroad mobile site
		if(hasLandedOnAbroadMobileSite($isMobile))
		{
			return true;
		}
		
                if( $isMobile &&  (! (isset ($_SERVER['HTTP_X_AKAMAI_DEVICE_CHARACTERISTICS']) && $_SERVER['HTTP_X_AKAMAI_DEVICE_CHARACTERISTICS'] != "") )){	
			/* ...rest works as it was
			 * load wurfl for mobile capabilities
			 */
			require(FCPATH . APPPATH . "modules/mobileSite/mcommon/libraries/Wurfl.php");
			/**
			 * Normally standard devices return valid mobile request
			 * except local & chinese phone
			 */
			$wurfl_obj 	= new Wurfl;
			$wurfl_obj->load($_SERVER);
		}

		/**
		$str = 'Mozilla/5.0 (Linux; U; Android 4.0.4; en-in; HTC One X Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30';  // HTC 1
		$str = 'Mozilla/5.0 (Linux; Android 4.1.1; HTC One X Build/JRO03C) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19'; // HTC 1
		$str = "Mozilla/5.0 (Linux; Android 4.0.4; GT-I9100G Build/IMM76D) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.169 Mobile Safari/537.22"; // crome+S3
		$str = "Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.160 Safari/537.22"; // crome browser
		$detect->setUserAgent($str);
		echo "<pre>";var_dump($detect->isMobile()); echo "</pre>";exit;
		*/

		/**
		 * @TODO: only is_wireless_device check is not sufficent
		 * If valid request come at mobile for page which is not avalable right now ???
		 */
		if(($isMobile)  && ($_COOKIE['user_force_cookie'] != 'YES') && whitelistDeviceList()==false)
		{

			if(!isset($_COOKIE['ci_mobile']) && (isset($_COOKIE['current_cat_page'])))
			{
				 $url = urldecode($_COOKIE['current_cat_page']);
				 $url = addingDomainNameToUrl(array('url' => $url,'domainName' => SHIKSHA_HOME));
				 header('Location:'.$url);
			}
			
			$mobileDeviceCapbilities = LoadDeviceCapabilities($wurfl_obj);
			$getdeviceType 	    = getDeviceType($wurfl_obj);
			$mobileDeviceCapbilities = array_merge($mobileDeviceCapbilities,$getdeviceType);

			if( ENABLE_HTML4_MOBILE_WEBSITE || $mobileDeviceCapbilities['ajax_support_javascript'] == 'true' ){
		            /**
		             * MOBILE COOKIE ... session based
		             */
			setcookie('ci_mobile','mobile',time() + 2592000,'/',COOKIEDOMAIN);
			/* Need these global vars as cookie will not aval in 1st HTTP request */
			GLOBAL $flag_mobile_user_agent; 
			$flag_mobile_user_agent = 'mobile';
			GLOBAL $ci_mobile_capbilities;
			$ci_mobile_capbilities = $mobileDeviceCapbilities;
			// load capbilites into cookie
			setcookie('ci_mobile_capbilities',json_encode($mobileDeviceCapbilities),time() + 2592000,'/',COOKIEDOMAIN);
			//unset($_COOKIE['user_force_cookie']);
			
			//In case of JS supported Mobile device, set a new cookie for JS Support
			if( !ENABLE_HTML4_MOBILE_WEBSITE || ($mobileDeviceCapbilities['ajax_support_javascript'] == 'true' && !isBlacklistedDevice($mobileDeviceCapbilities))){
				//Only in case of 10% users, we will set this cookie. Else, we will set another cookie to never show the Mobile5 site
				$randNumber = rand (1,100);
				if( $randNumber<=100 || ( isset($_REQUEST['html5']) && $_REQUEST['html5']==1 ) ){
					setcookie('ci_mobile_js_support','yes',time() + 2592000,'/',COOKIEDOMAIN);
					GLOBAL $flag_mobile_js_support_user_agent; 
					$flag_mobile_js_support_user_agent = 'yes';				
				}
				else{
					//setcookie('ci_mobile_do_not_show_html5','yes',0,'/',COOKIEDOMAIN);
					GLOBAL $flag_mobile_do_not_show_html5;
					$flag_mobile_do_not_show_html5 = 'yes';
				}
			}
			}
		}
		else{
            setcookie('ci_mobile','',time() - 2592000,'/',COOKIEDOMAIN);
            unset($_COOKIE['ci_mobile']);
            GLOBAL $flag_mobile_user_agent;
            $flag_mobile_user_agent = '';
            
            setcookie('ci_mobile_js_support','',time() - 2592000,'/',COOKIEDOMAIN);
            unset($_COOKIE['ci_mobile_js_support']);
            GLOBAL $flag_mobile_js_support_user_agent;
            $flag_mobile_js_support_user_agent = '';
            
            setcookie('ci_mobile_capbilities','',time() - 2592000,'/',COOKIEDOMAIN);
            unset($_COOKIE['ci_mobile_capbilities']);
            GLOBAL $ci_mobile_capbilities;
            $ci_mobile_capbilities = array();
		}
	}
}


/*
 * function to detect if user has switched between national & abroad mobile sites & unset cookies
 * - ci_mobile
 * - ci_mobile_js_support
 * - ci_mobile_capbilities
 * - mobile_site_user
 */
function unsetCookieWhenSwitchingAcrossMobileSites(){
	// if user is currently on national mobile site & switched to abroad mobile site
	if(
	   (	$_COOKIE['mobile_site_user'] == 'national' &&
		'https://'.$_SERVER['HTTP_HOST'] == SHIKSHA_STUDYABROAD_HOME
	   ) // end : national -> abroad switch
	   ||
	   (	$_COOKIE['mobile_site_user'] == 'abroad' &&
		'https://'.$_SERVER['HTTP_HOST'] == SHIKSHA_HOME
	   )// end : abroad -> national switch
	   ||
           ($_COOKIE['user_force_cookie'] == 'YES') // desktop view enforced by user
	  )// end : test expression
	{
		setcookie('ci_mobile','',time() - 2592000,'/',COOKIEDOMAIN);
		unset($_COOKIE['ci_mobile']);
		GLOBAL $flag_mobile_user_agent; 
		$flag_mobile_user_agent = '';
		setcookie('ci_mobile_js_support','',time() - 2592000,'/',COOKIEDOMAIN);
		unset($_COOKIE['ci_mobile_js_support']);
		GLOBAL $flag_mobile_js_support_user_agent; 
		$flag_mobile_js_support_user_agent = '';
		setcookie('ci_mobile_capbilities','',time() - 2592000,'/',COOKIEDOMAIN);
		unset($_COOKIE['ci_mobile_capbilities']);
		//also set a cookie to identify if user was on abroad site
		setcookie('mobile_site_user','',time() - 2592000,'/',COOKIEDOMAIN);
		$_COOKIE['mobile_site_user'] = '';
	}
}
/*
 * function that checks if user has opened abroad mobile site & sets relevant cookies
 * @param: accepts boolean isMobile
 */
function hasLandedOnAbroadMobileSite($isMobile)
{
		if('https://'.$_SERVER['HTTP_HOST'] == SHIKSHA_STUDYABROAD_HOME && $isMobile && $_COOKIE['user_force_cookie'] != 'YES')
		{
			// set ci_mobile cookie, ci_mobile_js_support and relevant global flags
			setcookie('ci_mobile','mobile',time() + 2592000,'/',COOKIEDOMAIN);
			$_COOKIE['ci_mobile'] = 'mobile';
			GLOBAL $flag_mobile_user_agent; 
			$flag_mobile_user_agent = 'mobile';
			setcookie('ci_mobile_js_support','yes',time() + 2592000,'/',COOKIEDOMAIN);
			GLOBAL $flag_mobile_js_support_user_agent; 
			$flag_mobile_js_support_user_agent = 'yes';				
			//also set a cookie to identify if user was on abroad site
			setcookie('mobile_site_user','abroad',time() + 2592000,'/',COOKIEDOMAIN);
			$_COOKIE['mobile_site_user'] = 'abroad';
			// return if abroad site mobile
			return true;
		}
		else{
			return false;
		}
}

/****
Check for Blacklisted devices for HTML5
****/
function isBlacklistedDevice($mobileCapabilities){
	//If blackberry torch, return true
	//if( strpos( $mobileCapabilities['model_name'],'BlackBerry' )!==false && strpos( $mobileCapabilities['marketing_name'],'Torch' )!==false ){
	if( strpos( $mobileCapabilities['model_name'],'BlackBerry' )!==false ){
		return true;
	}
	return false;
}

/**
 * [getDeviceType check device]
 * @return [array] [return array of device type]
 */

function wurfl_check_mdevice($requestingDevice)
{
	/*
	$is_wireless = ($requestingDevice->getCapability('is_wireless_device') == 'true');
	$is_phone = ($requestingDevice->getCapability('can_assign_phone_number') == 'true');
	$is_mobile_device = ($iswireless && $is_phone);
	 return $is_mobile_device;
	*/
	return false;
}

function getDeviceType($requestingDevice)
{
	$returnArrray = array();

	if( (isset ($_SERVER['HTTP_X_AKAMAI_DEVICE_CHARACTERISTICS']) && $_SERVER['HTTP_X_AKAMAI_DEVICE_CHARACTERISTICS'] != "" )){
		$requestDevice = convertAkamaiHeaderToArray($_SERVER['HTTP_X_AKAMAI_DEVICE_CHARACTERISTICS']);		
                //$returnArrray['is_wireless']            = "yes";
                //$returnArrray['is_smarttv']             = "no";
                //$returnArrray['is_tablet']              = "no";
                //$returnArrray['is_phone']               = "yes";
                //$returnArrray['is_js']                  = ($requestDevice['ajax_support_javascript'] == 'true') ? "yes":"no";
	}
	else{
		//$returnArrray['is_wireless']  		= ($requestingDevice->getCapability('is_wireless_device') == 'true') ? "yes":"no";
		//$returnArrray['is_smarttv']   		= ($requestingDevice->getCapability('is_smarttv') == 'true') ? "yes":"no";
		//$returnArrray['is_tablet']    		= ($requestingDevice->getCapability('is_tablet') == 'true') ? "yes":"no";
		//$returnArrray['is_phone']     		= ($requestingDevice->getCapability('can_assign_phone_number') == 'true') ? "yes":"no";	
		//$returnArrray['is_js']     		= ($requestingDevice->getCapability('ajax_support_javascript') == 'true') ? "yes":"no";	
	}
	return $returnArrray; 
}

/**
 * File to check User Agent
 * check cookie or $GLOBALS['flag_mobile_user_agent'];
 */
function LoadDeviceCapabilities($wurfl)
{
        if( (isset ($_SERVER['HTTP_X_AKAMAI_DEVICE_CHARACTERISTICS']) && $_SERVER['HTTP_X_AKAMAI_DEVICE_CHARACTERISTICS'] != "" )){
                $result = convertAkamaiHeaderToArray($_SERVER['HTTP_X_AKAMAI_DEVICE_CHARACTERISTICS']);
                $mobileDeviceCapbilities['model_name'] = $result['model_name'];
                //$mobileDeviceCapbilities['brand_name'] = $result['brand_name'];
                //$mobileDeviceCapbilities['marketing_name'] = $result['marketing_name'];
                $mobileDeviceCapbilities['device_os'] = $result['device_os'];
                //$mobileDeviceCapbilities['is_wireless_device'] = "true";
                $mobileDeviceCapbilities['device_os_version'] =  $result['device_os_version'];
                $mobileDeviceCapbilities['wifi'] = "";
                $mobileDeviceCapbilities['resolution_height'] = $result['resolution_height'];
                $mobileDeviceCapbilities['resolution_width'] = $result['resolution_width'];
                //$mobileDeviceCapbilities['nokia_series'] = "0";
                //$mobileDeviceCapbilities['nokia_edition'] = "0";
                $mobileDeviceCapbilities['mobile_browser'] = $result['mobile_browser'];
                //$mobileDeviceCapbilities['mobile_browser_version'] = $result['mobile_browser_version'];
                $mobileDeviceCapbilities['ajax_support_javascript'] = $result['ajax_support_javascript'];
		//$mobileDeviceCapbilities['pointing_method'] = "";
		//$mobileDeviceCapbilities['full_flash_support'] = "";
                //$mobileDeviceCapbilities['preferred_markup'] = "";
                //$mobileDeviceCapbilities['physical_screen_width'] = "";
                //$mobileDeviceCapbilities['physical_screen_height'] = "";
	}
	else{
		$result = $wurfl->getAllCapabilities();

		$mobileDeviceCapbilities['model_name'] = $result['model_name'];
		//$mobileDeviceCapbilities['brand_name'] = $result['brand_name'];
		//$mobileDeviceCapbilities['marketing_name'] = $result['marketing_name'];
		//$mobileDeviceCapbilities['preferred_markup'] = $result['preferred_markup'];
		$mobileDeviceCapbilities['device_os'] = $result['device_os'];
		//$mobileDeviceCapbilities['is_wireless_device'] = $result['is_wireless_device'];
		$mobileDeviceCapbilities['device_os_version'] =  $result['device_os_version'];
		//$mobileDeviceCapbilities['pointing_method'] = $result['pointing_method'];
		$mobileDeviceCapbilities['wifi'] = $result['wifi'];
		$mobileDeviceCapbilities['resolution_height'] = $result['resolution_height'];
		$mobileDeviceCapbilities['resolution_width'] = $result['resolution_width'];
		//$mobileDeviceCapbilities['full_flash_support'] = $result['full_flash_support'];
		//$mobileDeviceCapbilities['nokia_series'] = $result['nokia_series'];
		//$mobileDeviceCapbilities['nokia_edition'] = $result['nokia_edition'];
		$mobileDeviceCapbilities['mobile_browser'] = $result['mobile_browser'];
		//$mobileDeviceCapbilities['mobile_browser_version'] = $result['mobile_browser_version'];
		//$mobileDeviceCapbilities['table_support'] = $result['table_support'];
		$mobileDeviceCapbilities['ajax_support_javascript'] = $result['ajax_support_javascript'];
		//$mobileDeviceCapbilities['preferred_markup'] = $result['preferred_markup'];
		//$mobileDeviceCapbilities['physical_screen_width'] = $result['physical_screen_width'];
		//$mobileDeviceCapbilities['physical_screen_height'] = $result['physical_screen_height'];
		//$mobileDeviceCapbilities['post_method_support'] = $result['post_method_support'];
		//$mobileDeviceCapbilities['mms_receiver'] = $result['receiver'];
		//$mobileDeviceCapbilities['mms_sender'] = $result['sender'];	
		//$mobileDeviceCapbilities['sms_enabled'] = $result['sms_enabled'];
	}
	return $mobileDeviceCapbilities;
}

function convertAkamaiHeaderToArray($requestDevice){
	$returnArray = array();
	if($requestDevice != ""){
		$tempArray = explode(";",$requestDevice);
		foreach($tempArray as $feature){
			$featureSplit = explode("=",$feature);
			if($featureSplit[0]){
				$keyName = $featureSplit[0];
				$returnArray[$keyName] = isset($featureSplit[1])?$featureSplit[1]:"";
			}
		}
	}
	return $returnArray;
}

/* End of file get_mobile_useragent.php */
/* Location: hooks/get_mobile_useragent.php */
