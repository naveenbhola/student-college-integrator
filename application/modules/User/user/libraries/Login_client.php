<?php

/**
 * File to handle User Login
 */

/**
* Class to handle User Login
*/
class Login_client
{
	/**
	 * Init function for Initialization purposes
	 */
	function init()
	{
		$this->CI = & get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();
		//$server_url = 'https://172.16.3.227/user/login_server';		
		//$server_url = 'https://172.16.3.247/user/login_server';		
		$this->CI->xmlrpc->set_debug(0);
		//$this->CI->xmlrpc->server($server_url, 80);	
		$this->CI->xmlrpc->server(LOGIN_SERVER_URL, LOGIN_SERVER_PORT);
	}
	
	/**
	 * Function to invalidate the User Login cache(Delete the cache)
	 */
	function invalidateUserLoginCache()
	{
		$this->init();
		$user = $_COOKIE['user'];
		$key = "lu_".md5('validateuser'.$user.'on');
		$this->cacheLib->clearCacheForKey($key);
		$key = "lu_".md5('validateuser'.$user.'off');
		$this->cacheLib->clearCacheForKey($key);
	}

	function invalidateUserLoginCacheWithUserId($userId)
	{
		$this->invalidateUserLoginCache();
        $keyToDelete = "lu_".md5("validateuser_".$userId);
        $this->cacheLib->clearCacheForKey($keyToDelete);
	}
	
	/**
	 * Function to validate the user
	 *
	 * @param string $user
	 * @param string $remember
	 */
	function validateuser($user,$remember,$cacheDisable=false)
	{
		$this->init();
		$key = "lu_".md5('validateuser'.$user.$remember);
		$this->CI->xmlrpc->set_debug(0);

		if($cacheDisable || $this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			error_log('LOGINCLIENT');
			$this->CI->xmlrpc->method('validateuser');	
			$request = array (
					array($user, 'string'),
					array($remember, 'string'),
					'struct'			
					);
			$this->CI->xmlrpc->request($request);	

			if ( ! $this->CI->xmlrpc->send_request())
			{
				return  $this->CI->xmlrpc->display_error();
			}
			else
			{
                $response =  $this->CI->xmlrpc->display_response();

                if($response != "false")
				$this->cacheLib->store($key,json_encode($response),60,'user');
				return $response;
			} 	
		}
		else
		{
			$response = json_decode($this->cacheLib->get($key),true);
			return $response;
		}     

	}
	
	
	/**
	 * Function to log OFF user
	 *
	 * @param string $user
	 * @param string $appId
	 *
	 */
	function logOffUser($user,$appId)
	{
		error_log_shiksha('client');
		$this->init();
		//$this->cacheLib->clearCache('user');
		$key = "lu_".md5('validateuser'.$user.'on');
		$this->cacheLib->clearCacheForKey($key);
		
		$this->CI->xmlrpc->method('logOffUser');	
		$request = array (
				array($user, 'string'),
				array($appId, 'string'),
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
}
?>
