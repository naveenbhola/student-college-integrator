<?php

/**
* Copyright 2007 Info Edge India Ltd
* $Rev::               $:  Revision of last commit
* $Author: build $:  Author of last commit
* $Date: 2010-05-31 06:25:22 $:  Date of last commit
* This class provides the Blog Server Web Services.
* /The blog_client.php makes call to this server using XML RPC calls.
*  $Id: Register_client.php,v 1.70 2010-05-31 06:25:22 build Exp $:
*/


/**
 * This class provides the Blog Server Web Services. 
 *
 */
class Register_client
{
	/**
	 * Init Function to load the Libraries anc create Instances
	 *
	 * @param string $what
	 */
	function init($what='write')
	{
		$this->CI = & get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		$this->CI->load->library('cacheLib');
        $this->CI->load->library('User_agent');
		$this->cacheLib = new cacheLib();
		$this->CI->xmlrpc->set_debug(0);
		$server_url = REGISTER_SERVER_URL;
		$server_port = REGISTER_SERVER_PORT;
		if($what=='read'){
		    $server_url = REGISTER_READ_SERVER_URL;
		    $server_port = REGISTER_READ_SERVER_PORT;
		}
		$this->CI->xmlrpc->server($server_url,$server_port );
	}
	
	/**
	 * 
	 * Function to the User Agent
	 * @return string  
	 */

    function getUserAgent() 
    {
	// To use //// $this->getUserAgent();
        $this->init();
        if ($this->CI->agent->is_browser())
        {
            $agent = $this->CI->agent->browser().' '.$this->CI->agent->version();
        }
        elseif ($this->CI->agent->is_robot())
        {
            $agent = $this->CI->agent->robot();
        }
        elseif ($this->CI->agent->is_mobile())
        {
            $agent = $this->CI->agent->mobile();
        }
        else
        {
            $agent = 'Unidentified User Agent';
        }
        return $agent;
    }
    
    /**
     * Function to get the Referrer
     * @return string
     */
    
    
    function getReferrer()  
    {
	// $this->getReferrer();
        $this->init();
        //echo "<pre>"; print_r($this->CI->agent); echo "</pre>";
        if ($this->CI->agent->is_referral())
        {
            return $this->CI->agent->referrer();
        }
        else
        {
            $string = "www.shiksha.com#referrer_not_found";
            return $string;
        }
    }
    
    
    /**
     * Function to update the User Interest
     *
     * @param integer $appId
     * @param $key string
     * @param integer $userId
     * @param string $valueStr
     *
     */
    function updatetUserInterest($appId, $key, $userId,$valueStr ) {
		$this->init();
		$this->CI->xmlrpc->method('updatetUserInterest');
        $request = array($appId,$key,$userId,$valueStr);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}

    }
	
	
	/**
	 * Function to check the Lead Info
	 *
	 * @param integer $userId
	 *
	 */
    function checkforleadinfo($userId) {
		$this->init('read');
		$this->CI->xmlrpc->method('scheckforleadinfo');
        $request = array($userId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
    }
	/**
	 * Function to get the User Info System Point(using xmlrpc with api 'suserInfoSystemPoint's)
	 *
	 * @param integer $userId
	 * @param string $action	 
	 */
	function userInfoSystemPoint_Client($userId,$action) {
		$this->init();
		$this->CI->xmlrpc->method('suserInfoSystemPoint');
        $request = array($userId,$action);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}

    }


	/**
	 * Function to get the Ed level from Id)
	 *
	 * @param integer $appId
	 * @param string $levelId	
	 */
    function getEdLevelFromId($appId, $levelId) {
		$this->init('read');
		$this->CI->xmlrpc->method('getEdLevelFromId');
        $request = array($appId,$levelId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}

    }
		
	/**
	 * Function to update the user Info
	 *
	 * @param array $userarray
	 * 
	 */
	function updateuserinfo($userarray)
	{

		$userid = $userarray['userid'];
		if($userarray['requestuser'] == "requestinfo")
		{
			$password = $userarray['password'];
			$mdpassword = $userarray['mdpassword'];
			$displayname = '';
			$mobile = 0;
			$firstname = "";
			$lastname = "";
			$country = 0;
			$city = 0;
		}
		else
		{
			$password = '';
			$mdpassword = '';
			$displayname = '';
			if(isset($userarray['mobile']))
				$mobile = $userarray['mobile'];
			else
				$mobile = 0;
			if(isset($userarray['firstname']))
				$firstname = $userarray['firstname'];
			else
				$firstname = "";

			if(isset($userarray['lastname']))
				$lastname = $userarray['lastname'];
			else
				$lastname = "";

			if(isset($userarray['country']))
				$country = $userarray['country'];
			else
				$country = 0;

			if(isset($userarray['city']))
				$city = $userarray['city'];
			else
				$city = 0;
		}
		if(isset($userarray['viamobile']))
			$viamobile = $userarray['viamobile'];
		else
			$viamobile = 1;

		if(isset($userarray['viamail']))
			$viamail = $userarray['viamail'];
		else
			$viamail = 1;

		if(isset($userarray['vianewsletteremail']))
			$vianewsletteremail = $userarray['vianewsletteremail'];
		else
			$vianewsletteremail = 1;

		if(isset($userarray['educationLevel']))
			$educationlevel = $userarray['educationLevel'];
		else
			$educationlevel = 0;

		if(isset($userarray['experience']))
			$experience = $userarray['experience'];
		else
			$experience = null;


		if(isset($userarray['DOB']))
			$DOB = $userarray['DOB'];
		else
			$DOB = '';

		if(isset($userarray['institute']))
			$institute = $userarray['institute'];
		else
			$institute = 0;

		if(isset($userarray['youare']))
			$youare = $userarray['youare'];
		else
			$youare = 0;

		if(isset($userarray['gradYear']))
			$gradYear = $userarray['gradYear'];
		else
			$gradYear = 0;

		if(isset($userarray['usergroup']))
			$usergroup = $userarray['usergroup'];
		else
			$usergroup = "user";

		if(isset($userarray['cityofhighereducation']))
			$cities = $userarray['cityofhighereducation'];
		else
			$cities = "";

		if(isset($userarray['countries']))
			$countries = $userarray['countries'];
		else
			$countries = "";

		if(isset($userarray['categories']))
			$categories = $userarray['categories'];
		else
			$categories = "";


		if(isset($userarray['countryofeducation']))
			$countryofedu = $userarray['countryofeducation'];
		else
			$countryofedu = "";

		if(isset($userarray['cityofeducation']))
			$cityofedu = $userarray['cityofeducation'];
		else
			$cityofedu = "";

		$this->init();
		error_log_shiksha('In the REGISTER client');
		$this->CI->xmlrpc->method('updateuserinfo');

		$request = array($userid,'',$country,$city,$mobile,$viamobile,$viamail,$vianewsletteremail,$appId,$educationlevel,$experience,$DOB,$institute,$youare,$gradYear,$usergroup,$firstname,$lastname,$categories,$countries,$countryofedu,$cityofedu,$password,$displayname,$mdpassword,$userarray['requestuser'],$cities);
		$this->CI->xmlrpc->request($request);

		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			$key = "lu_".md5('validateuser'.$_COOKIE['user'].'on');
			$this->cacheLib->clearCacheForKey($key);
			$return = $this->CI->xmlrpc->display_response();
			return $return;
		}
	}
	
	/**
	 * Function to get the Specialization ID
	 * @param integer $category
	 * 
	 */
	function getspecializationid($category)
	{
		$this->init('read');
		$this->CI->xmlrpc->method('sGetUserSpecialization');
		$request = array($category);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			$response = $this->CI->xmlrpc->display_response();
		}
		return $response;
	}
	
	
	/**
	 * funntion to add new user
	 *
	 * @param array $userarray
	 *
	 */
	function adduser_new($userarray)
	{	
		$this->init();
		// Get the user details related to highest education,user state pref,specializationid
		global $ug_course_mapping_array;
		global $pg_course_mapping_array;
		if(isset($userarray['categories']))
		{
			if(is_numeric($userarray['categories']))
			{
				$response = $this->getspecializationid($userarray['categories']);
				$finalArray['desiredCourse'] = $response;
			}
		}
		
		/* flag to by pass mobile validation from back-end */
		if (isset($userarray['bypassmobilecheck']) && ($userarray['bypassmobilecheck'] == 'true'))
		{
		  $finalArray['bypassmobilecheck'] = 'true';
		}
		else
		{
		  $finalArray['bypassmobilecheck'] = 'false';
		}
		if(trim($userarray['categories']) == "Study Abroad")
			$finalArray['extraFlag'] = 'studyabroad';
		if(trim($userarray['categories']) == "Undecided")
			$finalArray['extraFlag'] = 'undecided';
		if(isset($userarray['usergroup']))
			$finalArray['signUpFlag'] = $userarray['usergroup'];
		else
			$finalArray['signUpFlag'] = 'user';
		if(isset($userarray['usergroup']))
			$finalArray['userGroup'] = $userarray['usergroup'];
		else
			$finalArray['userGroup'] = 'user';
		$finalArray['email']= $userarray['email'];
		$finalArray['displayname']= sanitizeString($userarray['displayname']).rand(10001,99999);
		$finalArray['textPassword'] = $userarray['password'];
		$finalArray['ePassword']  = $userarray['ePassword'];
		if(isset($userarray['mobile']))
			$finalArray['mobile']= $userarray['mobile'];
		if(isset($userarray['country']))
			$finalArray['residenceCountry']= $userarray['country'];
		if(isset($userarray['city']))
			$finalArray['residenceCity']= $userarray['city'];
		if(isset($userarray['firstname']))
			$finalArray['firstName']= $userarray['firstname'];
		if(isset($userarray['lastname']))
			$finalArray['lastName']= $userarray['lastname'];
		if(isset($userarray['viamobile']))
		{
			if($userarray['viamobile'] != '')
				$finalArray['viamobile']= $userarray['viamobile'];
			else
				$finalArray['viamobile']= 0;
		}
		if(isset($userarray['viamail']))
		{
			if($userarray['viamail'] != '')
				$finalArray['viaemail']=	$userarray['viamail'];
			else
				$finalArray['viaemail']=	0;
		}
		if(isset($userarray['vianewsletteremail']))
		{
			if($userarray['vianewsletteremail'] != '')
				$finalArray['newsletteremail'] = $userarray['vianewsletteremail'];
			else
				$finalArray['newsletteremail'] = '0';
		}
		if(isset($userarray['age']))
			$finalArray['age']= $userarray['age'];
		if(isset($userarray['DOB']))
			$finalArray['age']= $userarray['DOB'];
		if(isset($userarray['gender']))
			$finalArray['gender']=$userarray['gender'];
		if(isset($userarray['tracking_keyid']))
			$finalArray['tracking_keyid']=$userarray['tracking_keyid'];
		if(isset($userarray['landline']))
			$finalArray['phone']=$userarray['landline'];
		if(isset($userarray['experience']))
			$finalArray['experience']=$userarray['experience'];
		
		if(isset($userarray['youare']))
		{
			$finalArray['userStatus'] = $userarray['youare'];
            /*
			if($userarray['youare'] == "School" || $userarray['youare'] == "College")
				$finalArray['ongoingCompletedFlag'] = 1;
			else
				$finalArray['ongoingCompletedFlag'] = 0;
           */ // Why is it here ?? : Ashish
		}

		if(isset($userarray['educationLevel']))
		{
			error_log(array_key_exists($ug_course_mapping_array[$userarray['educationLevel']]).'USERARRAY');
			if(array_key_exists($userarray['educationLevel'],$ug_course_mapping_array))
			{
				$finalArray['name'][] =  $ug_course_mapping_array[$userarray['educationLevel']];
				$finalArray['level'][] =  'UG';
			}
			if(array_key_exists($userarray['educationLevel'],$pg_course_mapping_array))
			{
				$finalArray['name'][] =  $pg_course_mapping_array[$userarray['educationLevel']];
				$finalArray['level'][] =  'PG';
			}
			if(trim($userarray['educationLevel']) == "Other")
			{
				$finalArray['name'][] =  trim($userarray['educationLevel']);
				$finalArray['level'][] =  '';
			}
			if(trim($userarray['educationLevel']) == "School")
			{
				$finalArray['name'][] =  trim($userarray['educationLevel']);
				$finalArray['level'][] =  '10';
			}
		}
        if(isset($userarray['countryofeducation'])) {
            $finalArray['country'][] = $userarray['countryofeducation'];
            if($userarray['countryofeducation'] != 2) {
                $finalArray['extraFlag'] = 'studyabroad';
            }
        }
        if(isset($userarray['cityofeducation']))
            $finalArray['city'][] = $userarray['cityofeducation'];
		if(isset($userarray['gradYear']))
        {
            $finalArray['courseCompletionDate'][] = date('Y/j/n',strtotime('1/1/'.$userarray['gradYear']));
            $finalArray['ongoingCompletedFlag'][] = $userarray['gradYear'] > date('Y') ? '1' : '0';
        }
		if(isset($userarray['institute']))
		{
			$finalArray['institute'] = $userarray['institute'];
			if(is_int($userarray['institute']) || $userarray['youare'] != 'School')
				$finalArray['instituteId'][] = $userarray['institute'];
		}
		$finalArray['sourceInfo'] = $userarray['sourceurl'];
		//$finalArray['userAvatar'] = '/public/images/photoNotAvailable.gif';
		if(isset($userarray['countries']))
		{
			$finalArray['countryId'][] = $userarray['countries'];
            if($userarray['countries'] != 2) {
                $finalArray['extraFlag'] = 'studyabroad';
            }
		}
        $this->CI->load->library('Category_list_client');
        $categoryClientObj= new Category_list_client();
        $appId = 1;
		if(isset($userarray['preferredCityCsv']))
		{
			$prefCity = explode(',',$userarray['preferredCityCsv']);
			for($i = 0;$i<count($prefCity);$i++)
            {
                $cityId = $prefCity[$i];
                $cityDetails = $categoryClientObj->getDetailsForCityId($appId, $cityId);
                $finalArray['cityId'][] = $cityDetails['city_id'];
                $finalArray['stateId'][] = $cityDetails['state_id'];
                $finalArray['countryId'][] = $cityDetails['countryId'];
                if($cityDetails['countryId'] != 2) {
                    $finalArray['extraFlag'] = 'studyabroad';
                }
            }
		}
		if(isset($userarray['cityofhighereducation']))
		{
            $cityId = $userarray['cityofhighereducation'];
            $cityDetails = $categoryClientObj->getDetailsForCityId($appId, $cityId);
            $finalArray['cityId'][] = $cityDetails['city_id'];
            $finalArray['stateId'][] = $cityDetails['state_id'];
            $finalArray['countryId'][] = $cityDetails['countryId'];
            if($cityDetails['countryId'] != 2) {
                $finalArray['extraFlag'] = 'studyabroad';
            }
		}

		if(isset($userarray['sourceurl']))
			$finalArray['referer'] = $userarray['sourceurl'];

		if(isset($userarray['sourcename']))
		{
			$finalArray['sourcename'] = $userarray['sourcename'];
			$finalArray['keyquery'] = $userarray['sourcename'];
		}
		if(isset($userarray['coordinates']))
			$finalArray['coordinates'] = $userarray['coordinates'];

		if(isset($userarray['resolution']))
			$finalArray['resolution'] = $userarray['resolution'];
        if(!isset($userarray['browser']))
        {
            $finalArray['browser'] = $this->getUserAgent();
        }
        else
        {

            $finalArray['browser'] = $userarray['browser'];
        }
        //$finalArray['browser'] = $this->getUserAgent();
		$finalArray['type'] = 'registration';
		$landingpage  = isset($_COOKIE['landingcookie'])?$_COOKIE['landingcookie']:'';
		$finalArray['landingpage'] = $landingpage;
		if(isset($userarray['IsdCode']))
			$finalArray['isdCode'] = $userarray['IsdCode'];
		if(isset($userarray['country']))
			$finalArray['country'] = $userarray['country'];

		// $finalArray['sessionid']=  sessionId();
		$finalArray['visitorsessionid'] = getVisitorSessionId();
		return($this->addUser(1,$finalArray));
	}
	/**
	 * funntion to add new user
	 *
	 * @param array $userarray
	 *
	 */
	function adduser_new1($userarray)
	{
		if(isset($userarray['quicksignupFlag']))
			$quicksignupFlag = $userarray['quicksignupFlag'];
		else
			$quicksignupFlag = "user";
		if(isset($userarray['country']))
			$country = $userarray['country'];
		else
			$country = 0;

		if(isset($userarray['city']))
			$city = $userarray['city'];
		else
			$city = 0;

		if(isset($userarray['mobile']))
			$mobile = $userarray['mobile'];
		else
			$mobile = 0;


		if(isset($userarray['viamobile']))
			$viamobile = $userarray['viamobile'];
		else
			$viamobile = 1;

		if(isset($userarray['viamail']))
			$viamail = $userarray['viamail'];
		else
			$viamail = 1;

		if(isset($userarray['vianewsletteremail']))
			$vianewsletteremail = $userarray['vianewsletteremail'];
		else
			$vianewsletteremail = 1;

		if(isset($userarray['educationLevel']))
			$educationlevel = $userarray['educationLevel'];
		else
			$educationlevel = 0;

		if(isset($userarray['experience']))
			$experience = $userarray['experience'];
		else
			$experience = null;


		if(isset($userarray['DOB']))
			$DOB = $userarray['DOB'];
		else
			$DOB = '';

		if(isset($userarray['institute']))
			$institute = $userarray['institute'];
		else
			$institute = 0;

		if(isset($userarray['youare']))
			$youare = $userarray['youare'];
		else
			$youare = 0;

		if(isset($userarray['gradYear']))
			$gradYear = $userarray['gradYear'];
		else
			$gradYear = 0;

		if(isset($userarray['usergroup']))
			$usergroup = $userarray['usergroup'];
		else
			$usergroup = "user";

		if(isset($userarray['firstname']))
			$firstname = $userarray['firstname'];
		else
			$firstname = "";


		if(isset($userarray['lastname']))
			$lastname = $userarray['lastname'];
		else
			$lastname = "";

		if(isset($userarray['countries']))
			$countries = $userarray['countries'];
		else
			$countries = "";

		if(isset($userarray['categories']))
			$categories = $userarray['categories'];
		else
			$categories = "";

		if(isset($userarray['cityofhighereducation']))
			$cityofhighereducation = $userarray['cityofhighereducation'];
		else
			$cityofhighereducation = "";

		if(isset($userarray['countryofeducation']))
			$countryofedu = $userarray['countryofeducation'];
		else
			$countryofedu = "";

		if(isset($userarray['cityofeducation']))
			$cityofedu = $userarray['cityofeducation'];
		else
			$cityofedu = "";

		if(isset($userarray['age']))
			$age = $userarray['age'];
		else
			$age = "";

        if(isset($userarray['preferredCityCsv']))
            $preferredCity = $userarray['preferredCityCsv'];
        else
            $preferredCity= "";



        if(isset($userarray['subcategories']))
            $subCategories = $userarray['subcategories'];
        else
            $subCategories = "";


		if(isset($userarray['gender']))
			$gender = $userarray['gender'];
		else
			$gender = "";

		if(isset($userarray['landline']))
			$landline = $userarray['landline'];
		else
			$landline = "";

		
        if(isset($userarray['sourceurl']))
			$sourceurl = $userarray['sourceurl'];
		else
			$sourceurl = $this->getReferrer();
        
        //$sourceurl = $this->getReferrer();

		if(isset($userarray['sourcename']))
			$sourcename = $userarray['sourcename'];
		else
			$sourcename = "";

		if(isset($userarray['coordinates']))
			$coordinates = $userarray['coordinates'];
		else
			$coordinates = "";

		if(isset($userarray['resolution']))
			$resolution = $userarray['resolution'];
		else
			$resolution = "";

			/* $browser = $_SERVER['HTTP_USER_AGENT'];*/

        $browser = $this->getUserAgent();
		error_log_shiksha($categories);
		$email = $userarray['email'];
		$displayname = $userarray['displayname'];
		$password = $userarray['password'];
		$mdpassword = $userarray['mdpassword'];

		error_log_shiksha($educationlevel.'EDUCATIONLEVEL');
		$this->init();
		error_log_shiksha('In the REGISTER client');
		$this->CI->xmlrpc->method('adduser_new');

		$this->CI->load->library('LmsLib');
		$this->lmsLib = new LmsLib();
		$sendLMS = array();
		$sendLMS['email'] = $email;
		$sendLMS['mobile'] = $mobile;
		$sendLMS['displayname'] = $displayname;
		$sendLMS['educationlevel'] = $educationlevel;
		$sendLMS['city'] = $city;
		$sendLMS['experience'] = $experience;
		$sendLMS['appID'] = $appId;
        $landingpage  = isset($_COOKIE['landingcookie'])?$_COOKIE['landingcookie']:'';
		error_log_shiksha('In the client');
		$request = array($email,$password,$mdpassword,$displayname,'',$country,$city,$mobile,$viamobile,$viamail,$vianewsletteremail,$appId,$educationlevel,$experience,$DOB,$institute,$youare,$gradYear,$usergroup,$firstname,$lastname,$categories,$countries,$countryofedu,$cityofedu,$cityofhighereducation,$quicksignupFlag,$age,$gender,$landline,$sourceurl,$sourcename,$coordinates,$resolution,$landingpage,$browser, $subCategories,$preferredCity);
		$this->CI->xmlrpc->request($request);

		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			$return = $this->CI->xmlrpc->display_response();
			error_log_shiksha("Correct response");
			$userId = $return['status'];
			$sendLMS['userID'] = $userId;
			$this->lmsLib->addUser($sendLMS);
			error_log_shiksha("LMS::Returned from lms");
			return $return;
		}
	}
	
	
	/**
	 * Function to get the User Details
	 *
	 * @param integer $appid
	 * @param integer $userid
	 * @param string $module
	 *
	 */
	function userdetail($appid, $userid,$module='normal')
	{
		$this->init('read');
		$this->CI->xmlrpc->method('getUserDetails');
		$request = array(array($appid,'int'),
				array($userid,'int'),
				array($module,'string'),
				'struct'
				);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	/**
	 * Function to get the user info(if exists) for the type
	 *
	 * @param integer $appId
	 * @param string $name
	 * @param string $type
	 *
	 */
	function getinfoifexists($appId, $name,$type)
	{
		$this->init('write');
		$this->CI->xmlrpc->method('getinfoifexists');
		$request = array($appId, $name,$type);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	/**
	 * Function to change the email
	 *
	 * @param integer $appId
	 * @param integer $userid
	 * @param string $email
	 */
	function changeEmail($appid, $userid,$email)
	{
		$this->init();
		error_log('asd');
        $this->CI->xmlrpc->method('schangeEmail');
		$request = array(array($appid,'int'),
				array($userid,'int'),
				array($email,'string'),
				'struct'
				);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	/**
	 * Function to send the user response
	 *
	 * @param integer $appid
	 * @param string $key
	 * @param string $verifyflag
	 * @param string $cookie
	 */
	function senduserResponse($appid,$key,$verifyflag,$cookie)
	{
		$this->init();
		error_log('asd');
        $this->CI->xmlrpc->method('senduserResponse');
		$request = array(array($appid,'int'),
				array($key,'string'),
				array($verifyflag,'string'),
				array($cookie,'string'),
				'struct'
				);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			$response = $this->CI->xmlrpc->display_response();
			if($verifyflag == "unsubscribe"){
				$key = "lu_".md5('validateuser'.$_COOKIE['user'].'on');
				$this->cacheLib->clearCacheForKey($key);
			}
			return $response;
		}
	}

	/**
	 * Function to send the reminder
	 */
    function sendReminder()
	{
        $this->init();
        $this->CI->xmlrpc->method('sendReminder');
        $request = '';
		$this->CI->xmlrpc->request();
		if ( ! $this->CI->xmlrpc->send_request($request))
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}

	/**
	 * Function to send the verification mail
	 *
	 * @param integer $appid
	 * @param integer $userId
	 *
	 */
    function sendverificationMail($appid,$userId)
	{
		$this->init();
		error_log('asd');
        $this->CI->xmlrpc->method('sendVerification');
		$request = array($appid, $userId);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}

	/**
	 * Function to get the details for display name
	 *
	 * @param integer $appid
	 * @param string $displayname
	 */
	function getDetailsforDisplayname($appid, $displayname)
	{
		$this->init('read');
		$this->CI->xmlrpc->method('getDetailsforDisplayname');
		$request = array(array($appid,'int'),
				array($displayname,'string'),
				'struct'
				);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}

	/**
	 * Function to populate the colleges
	 *
	 * @param integer $appid
	 * @param string $cityname
	 * @param string $institute
	 */
	function populatecolleges($appid, $cityname,$institute)
	{
		$this->init();
		$this->CI->xmlrpc->method('populatecolleges');
		$request = array(array($appid,'int'),
				array($cityname,'string'),
				array($institute,'string'),
				'struct'
				);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{

			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		}
	}
	
	
	/**
	 * Function to get the Details for Users
	 *
	 * @param integer $appId
	 * @param string $userIds
	 */
	function getDetailsforUsers($appId,$userIds)
	{
		$this->init('read');
		$this->CI->xmlrpc->method('getDetailsforUsers');
		$request = array(array($appId,'int'),
				array($userIds,'string'),
				'struct'
				);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	/**
	 * Function to update the user attribute
	 *
	 * @param integer $appId
	 * @param integer $userId
	 * @param array $updatearr
	 * @param string $columnName
	 * @param string $attributeValue
	 * @param string $displayname
	 * @param integer $product
	 */
	function updateUserAttribute($appId, $userId, $columnName,
			$attributeValue,$displayname,$product = 0) {
		$this->init();
		error_log("User_Profile_ in reg client  : ".print_r((memory_get_peak_usage()/(1024*1024)),true));
		$this->CI->xmlrpc->method('updateUser');
		$request = array($appId, $userId, $columnName,$attributeValue,$displayname,$product);

		$this->CI->xmlrpc->request($request);
		error_log("User_Profile_ in reg client after response from server   : ".print_r((memory_get_peak_usage()/(1024*1024)),true));
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			$key = "lu_".md5('validateuser'.$_COOKIE['user'].'on');
			$this->cacheLib->clearCacheForKey($key);
			return $this->CI->xmlrpc->display_response();
		}
	}
	/**
	 * Function to update the user information
	 *
	 * @param integer $appId
	 * @param array $updatearr
	 * @param string $columnname
	 * @param string $columnvalue
	 * @param array $userintarray
	 */
    function updateUserGen($appId,$updatearr,$columnname,$columnvalue,$userintarray){
        $this->init();
        error_log_shiksha('Client');
        $this->CI->xmlrpc->method('updateUserGen');
        error_log(print_r($updatearr,true).'UPDATEARRAY');
        $request = array($appId,array($updatearr,'struct'),$columnname,$columnvalue,array($userintarray,'struct'));
//		$request = array (array ($appId,'int'),array($request,'struct'));

        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()) {
            return  $this->CI->xmlrpc->display_error();
        } else {
			$key = "lu_".md5('validateuser'.$_COOKIE['user'].'on');
			$this->cacheLib->clearCacheForKey($key);
            return $this->CI->xmlrpc->display_response();
        }
    }
	
	/**
	 * Function to check Availability for type(e.g. name)
	 *
	 * @param integer $appId
	 * @param string $name
	 * @param string $type
	 */
	function checkAvailability($appId,$name,$type)
	{
		$this->init('read');
		error_log_shiksha('Client');
		$this->CI->xmlrpc->method('checkAvailability');
		$request = array($appId, $name,$type);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			return $this->CI->xmlrpc->display_response();
		}

	}
	
	/**
	 * Function to reset the password
	 *
	 * @param integer $appId
	 * @param string $name
	 * @param string $uname
	 * @param string $password
	 * @param string $textpassword
	 *
	 */
	function resetPassword($appId,$name,$uname,$password,$textpassword)
	{
		$this->init();
		error_log_shiksha('Client');
		$this->CI->xmlrpc->method('sResetPassword');
		$request = array($appId, $name,$uname,$password,$textpassword);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			return $this->CI->xmlrpc->display_response();
		}
	}
	/**
	 * Function to get the User ID for Email
	 *
	 * @param intger $appId
	 * @param string $uname
	 * 
	 */
	function getUserIdForEmail($appId,$uname, $writeHandle='no')
	{
		$this->init('read');
		error_log_shiksha('Client');
		$this->CI->xmlrpc->method('sgetIdforEmail');
		$request = array($appId, $uname, $writeHandle);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	/**
	 * Function to change the User Password
	 *
	 * @param integer $appId
	 * @param integer $userId
	 * @param string $oldpassword
	 * @param string $newpassword
	 * @param string $textpassword
	 *
	 */
	function changePassword($appId,$userId,$oldpassword,$newpassword,$textpassword)
	{
		$this->init();
		error_log_shiksha('Client');
		$this->CI->xmlrpc->method('schangePassword');
		$request = array($appId, $userId,$oldpassword,$newpassword,$textpassword);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	/**
	 * Function for RPC method 'requestCall'
	 *
	 * @param integer $appId
	 * @param struct $request
	 */
	function requestCall($appId,$request)
	{
		$this->init();
		$this->CI->xmlrpc->method('requestCall');
		$request = array (array ($appId,'int'),array($request,'struct'));

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			return $this->CI->xmlrpc->display_response();
		}
    }

    /**
     * Function for Education Level
     *
     * @param integer $appId
     * @param string $level
     *
     */

    function EducationLevel($appid, $level)
    {
        $this->init('read');
        $this->CI->xmlrpc->method('EducationLevel');
        $request = array(array($appid,'int'),
                array($level,'string'),
                'struct'
                );
        error_log_shiksha('Education');
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return  $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }
	/**
	 * Function to update the User Points
	 *
	 * @param integer $appId
	 * @param integer $userId
	 * @param string $points
	 */
	function updateuserPointSystem($appId,$points,$userId)
	{
		$this->init();
		$this->CI->xmlrpc->method('updateuserPointSystem');
		$request = array ($appId,$points,$userId);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			$this->cacheLib->clearCache('Network');
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	
	/**
	 * Function to get the user Point level
	 */
	function getUserPointLevel(){
		$this->init('read');
		$key = md5('getUserPointLevel');
		if(($this->cacheLib->get($key)=='ERROR_READING_CACHE')){
			$this->CI->xmlrpc->method('getUserPointLevel');
			$request = array($appId);
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()) {
				return  $this->CI->xmlrpc->display_error();
			} else {
				$this->cacheLib->clearCache('user');
				$response = $this->CI->xmlrpc->display_response();
				$this->cacheLib->store($key,$response,14400,'user');
				return $response;
			}
		}else{
			return $this->cacheLib->get($key);
		}
	}
	
	
	/**
	 * Function to add the User
	 *
	 * @param integer $appId
	 * @param array $userDetails
	 */
	function addUser($appId,$userDetails) {
		if(isset($userDetails['experience']) && $userDetails['experience'] == ''){
            $userDetails['experience'] = null;
        }
        if(empty($userDetails['referer'])) {
                $userDetails['referer'] = $this->getReferrer();
        }
		$this->init();
		$this->CI->xmlrpc->method('sAddUser');
		$request = array ($appId,json_encode($userDetails));

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			$response = $this->CI->xmlrpc->display_response();
			return $response;
		}
	}
	
	
	/**
	 * Function to update the user details
	 *
	 * @param string $appId
	 * @param array $userDetails
	 * @param integer $userId
	 */
	function updateUser($appId,$userDetails, $userId) {
        if(isset($userDetails['experience']) && $userDetails['experience'] == ''){
            $userDetails['experience'] = null;
        }
        if(empty($userDetails['referer'])) {
                $userDetails['referer'] = $this->getReferrer();
        }
		$this->init();
		$this->CI->xmlrpc->method('sUpdateUser');
		$request = array ($appId,json_encode($userDetails), $userId);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	/**
	 * Function to the get specialization for category
	 *
	 * @param string $categories
	 */
	function getSpecializationForCategory($categories)
	{
		$this->init('read');
		$this->CI->xmlrpc->method('sGetUserSpecialization');
		$request = array($categories);

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	/**
	* Function to get the user details for migration
	*
	* @param integer $appId
	* @param integer $userId
	*/
	function getUserDetailForMigration($appId , $userId)
	{
		$this->init('read');
		$this->CI->xmlrpc->method('sGetUserDetailForMigration');
		$request = array(array($appId,'int'),
				array($userId,'int'),
				'struct'
				);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			return $this->CI->xmlrpc->display_response();
		}
	}
   /**
     * Function to get the ID for the City name
     *
     * @param string $cityName
     *
     */
    function getIdForCityName($cityName)
    {
		$this->init('read');
		$this->CI->xmlrpc->method('sGetIdForCityName');
		$request = array(
				array($cityName,'string'),
				'struct'
				);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			return $this->CI->xmlrpc->display_response();
		}

    }
    
    /**
     * Function to get the ID for the Country name
     *
     * @param string $cityName
     *
     */
    function getIdForCountryName($cityName)
    {
		$this->init('read');
		$this->CI->xmlrpc->method('sGetIdForCountryName');
		$request = array(
				array($cityName,'string'),
				'struct'
				);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			return $this->CI->xmlrpc->display_response();
		}
    }
    
    /**
     * Function to get Preferences for user
     *
     * @param integer $appId
     * @param integer $userId
     */
    function getPreferencesForUser($appid, $userId)
    {
        $this->init('read');
        $this->CI->xmlrpc->method('sGetPreferencesForUser');
        $request = array(array($appid,'int'),
                array($userId,'int'),
                'struct'
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return  $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

/**
     * Function to get Category Id for user
     *
     * @param integer $appId
     * @param integer $userId
     */
    function getCategoryIdForUser($appid, $userId, $dbHandle='read')
    {
    	$this->init('read');
        $this->CI->xmlrpc->method('sGetCategoryIdForUser');
       $request = array(array($appid,'int'),
                array($userId,'int'),
                array($dbHandle,'string'),
                'struct'
                );
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()) {
			return  $this->CI->xmlrpc->display_error();
		} else {
			return $this->CI->xmlrpc->display_response();
		}
    }
}
?>
