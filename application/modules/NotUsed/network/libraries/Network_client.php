<?php
exit();
class Network_client
{
	var $CI;
	function init()
	{
		$this->CI = & get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server(NETWORK_SERVER_URL, NETWORK_SERVER_PORT);
		$this->CI->load->library('common/cacheLib');
		$this->cacheLib = new cacheLib();
	}

	function showColleges($appId,$alphabet,$start,$count,$country,$city,$category)
	{
		$this->init();
		$this->CI->xmlrpc->method('collegenetwork');	
		$request = array (
				array($appId, 'string'),
				array($alphabet, 'string'),
				array($country, 'string'),
				array($start, 'string'),
				array($count, 'string'),
				array($city, 'string'),
				array($category, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function showSchools($appId,$alphabet,$start,$count,$country)
	{
		error_log_shiksha('intheschool');
		$this->init();
		$this->CI->xmlrpc->method('showSchools');	
		$request = array (
				array($appId, 'string'),
				array($alphabet, 'string'),
				array($country, 'string'),
				array($start, 'string'),
				array($count, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function getSchoolsForIndex($appId,$schoolId)
	{
		$this->init();
		$this->CI->xmlrpc->method('sgetSchoolsForIndex');	
		$request = array($appId,$schoolId);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function getTestPrepGroupForIndex($appId,$groupId)
	{
		$this->init();
		$this->CI->xmlrpc->method('sgetTestPrepGroupForIndex');	
		$request = array($appId,$groupId);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function getNoofRequestforuser($appId,$userId,$startdate,$enddate)
	{
		$this->init();
		$this->CI->xmlrpc->method('getNoofRequestsforUser');	
		$request = array (
				array($appId, 'string'),
				array($userId, 'string'),
				array($startdate, 'string'),
				array($enddate, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function deleteUserFromNetwork($appId,$userId,$collegeId,$grouptype)
	{
		$this->init();
		$this->CI->xmlrpc->method('deleteUserFromNetwork');	
		$request = array (
				array($appId, 'string'),
				array($userId, 'int'),
				array($collegeId, 'int'),
				array($grouptype, 'string'),
				'struct'			
				);

		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			$this->cacheLib->clearCache('Network');
			return $this->CI->xmlrpc->display_response();
		} 		
	}
	function showuserCollegeNetwork($appId,$start,$count,$userStatus,$collegeId,$graduationYear,$grouptype)
	{
		$this->init();
		$this->CI->xmlrpc->method('showuserCollegeNetwork');	
		$request = array (
				array($appId, 'string'),
				array($start, 'string'),
				array($count, 'string'),
				array($userStatus, 'string'),
				array($collegeId, 'string'),
				array($graduationYear, 'string'),
				array($grouptype, 'string'),
				'struct'			
				);

		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		} 	
	}


	function showUserNetworkList($appId,$start,$count,$userId)
	{
		$this->init();
		$this->CI->xmlrpc->method('showUserNetworkList');	
		$request = array (
				array($appId, 'string'),
				array($start, 'string'),
				array($count, 'string'),
				array($userId, 'string'),
				'struct'			
				);

		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function getCollegesByCategory($appId,$categoryId)
	{
		$this->init();
		$key = md5('getCollegesByCategory'.$appId.$categoryId);
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->CI->xmlrpc->method('getCollegesByCategory');	
			$request = array (
					array($appId, 'string'),
					array($categoryId, 'string'),
					'struct'			
					);

			$this->CI->xmlrpc->request($request);	

			if ( ! $this->CI->xmlrpc->send_request())
			{
				error_log_shiksha($this->CI->xmlrpc->display_error());		 
				return  $this->CI->xmlrpc->display_error();
			}
			else
			{
				$response = $this->CI->xmlrpc->display_response();
				$this->cacheLib->store($key,$response,1800,'Network');
				return $this->CI->xmlrpc->display_response();
			} 	
		}
		else
			return $this->cacheLib->get($key);
	}

	function showCollegeNetworkList($appId,$start,$count,$userId,$institute)
	{
		$this->init();
		$this->CI->xmlrpc->method('showCollegeNetworkList');	
		$request = array (
				array($appId, 'string'),
				array($start, 'string'),
				array($count, 'string'),
				array($userId, 'string'),
				array($institute, 'string'),
				'struct'			
				);

		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function getAlertcontent($appId,$start,$diff,$institute)
	{
		$this->init();
		$this->CI->xmlrpc->method('getAlertContent');	
		$request = array (
				array($appId, 'string'),
				array($start, 'string'),
				array($diff, 'string'),
				array($institute, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 
	}

	function getCollegeCount($appId,$alphabet,$country,$city,$category)
	{
		$this->init();
		$this->CI->xmlrpc->method('getCollegeCount');	
		$request = array (
				array($appId, 'string'),
				array($alphabet, 'string'),
				array($country, 'string'),
				array($city, 'string'),
				array($category, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 
	}

	function getSchoolCount($appId,$alphabet,$country)
	{
		$this->init();
		$this->CI->xmlrpc->method('getSchoolCount');	
		$request = array (
				array($appId, 'string'),
				array($alphabet, 'string'),
				array($country, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 
	}


	function showCollegeNetworkCount($appId,$userStatus,$collegeId,	$graduationYear)
	{
		$this->init();
		$this->CI->xmlrpc->method('getCollegeNetworkCount');	
		$request = array (
				array($appId, 'string'),
				array($userStatus, 'string'),
				array($collegeId, 'string'),
				array($graduationYear, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 
	}

	function showCourses($appId,$collegeId)
	{
		$this->init();
		$this->CI->xmlrpc->method('showCourses');	
		$request = array (
				array($appId, 'string'),
				array($collegeId, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 
	}

	function totalCollegesandUsers($appId)
	{
		$this->init();
		$this->CI->xmlrpc->method('totalCollegesandUsers');	
		$request = array (
				array($appId, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 

	}


	function getNetworkForHomePage($appId,$categoryId,$countryId,$city,$startfrom,$count,$keyValue)
	{
		$this->init();
		$key = md5('getNetworkForHomePage'.$appId.$categoryId.$countryId.$city.$startfrom.$count.$keyValue);
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->CI->xmlrpc->method('getNetworkForHomePage');	
			$request = array (
					array($appId, 'string'),
					array($categoryId, 'string'),
					array($countryId,'string'),
					array($city,'string'),
					array($startfrom, 'string'),
					array($count, 'string'),
					array($keyValue, 'string'),
					'struct'			
					);
			$this->CI->xmlrpc->request($request);	
			if ( ! $this->CI->xmlrpc->send_request())
			{
				error_log_shiksha($this->CI->xmlrpc->display_error());		 
				return  $this->CI->xmlrpc->display_error();
			}
			else
			{
				$response=$this->CI->xmlrpc->display_response();
				$this->cacheLib->store($key,$response);
				return $response;
			} 
		}
		else{
			return $this->cacheLib->get($key);
		}
	}

	function getnetworkCountforYear($appId,$userStatus,$collegeId,$minyear,$maxyear)
	{
		$this->init();
		$this->CI->xmlrpc->method('getCollegeNetworkCountforYear');	
		$request = array (
				array($appId, 'string'),
				array($userStatus, 'string'),
				array($collegeId, 'string'),
				array($minyear, 'int'),
				array($maxyear, 'int'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 

	}




	function totalUsersCollegesinUserNet($appId,$userId,$institute)
	{
		$this->init();
		$this->CI->xmlrpc->method('totalUsersCollegesinUserNet');	
		$request = array (
				array($appId, 'string'),
				array($userId, 'string'),
				array($institute, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 

	}



	function addtoNetwork($appId,$userStatus,$courseId,$year,$userId,$collegeId,$cityId,$collegeName,$grouptype)
	{
		$this->init();
		$this->CI->xmlrpc->method('addtoNetwork');	
		$request = array (
				array($appId, 'string'),
				array($userStatus, 'string'),
				array($courseId, 'string'),
				array($year, 'string'),
				array($userId, 'string'),
				array($collegeId, 'string'),
				array($cityId, 'string'),
				array($collegeName, 'string'),
				array($grouptype, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			$this->cacheLib->clearCache('Network');
			return $this->CI->xmlrpc->display_response();
		} 
	}

	function showRequestResponse($appId,$senderId,$userId,$response)
	{
		$this->init();
		$this->CI->xmlrpc->method('showRequestResponse');	
		$request = array (
				array($appId, 'string'),
				array($senderId, 'string'),
				array($userId, 'string'),
				array($response, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	
	}




	function addUserRequest($appId,$senderId,$userId,$type)
	{
		$this->init();
		$this->CI->xmlrpc->method('addUserRequest');	
		$request = array (
				array($appId, 'string'),
				array($senderId, 'string'),
				array($userId, 'string'),
				array($type, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	
	}


	function showNewRequests($appId,$userId,$type,$start,$count)
	{
		$this->init();
		$this->CI->xmlrpc->method('showNewRequests');	
		$request = array (
				array($appId, 'string'),
				array($userId, 'string'),
				array($type, 'string'),
				array($start, 'int'),
				array($count, 'int'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function showRecentComment($appId,$start,$count,$threadId,$boardId)
	{
		$this->init();
		$this->CI->xmlrpc->method('showRecentComments');	
		$request = array (
				array($appId, 'string'),
				array($start, 'string'),
				array($count, 'string'),
				array($threadId, 'string'),
				array($boardId, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function showRecentSchoolComments($appId,$start,$count,$schoolId)
	{
		$this->init();
		$this->CI->xmlrpc->method('showRecentSchoolComments');	
		$request = array (
				array($appId, 'string'),
				array($start, 'string'),
				array($count, 'string'),
				array($schoolId, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function commentCount($appId,$schoolId)
	{
		$this->init();
		$this->CI->xmlrpc->method('commentCount');	
		$request = array (
				array($appId, 'string'),
				array($schoolId, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function getThreadId($appId,$collegeId)
	{
		$this->init();
		$this->CI->xmlrpc->method('getThreadId');	
		$request = array (
				array($appId, 'string'),
				array($collegeId, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			error_log_shiksha('NEHA'.print_r($this->CI->xmlrpc->display_response(),true));
			return $this->CI->xmlrpc->display_response();
		} 	
	}


	function getThreadIdforSchool($appId,$schoolId)
	{
		$this->init();
		$this->CI->xmlrpc->method('getThreadIdforSchool');	
		$request = array (
				array($appId, 'string'),
				array($schoolId, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		} 	
	}
	function insertComment($appId,$userId,$collegeId,$comment)
	{
		$this->init();
		$this->CI->xmlrpc->method('insertComment');	
		$request = array (
				array($appId, 'string'),
				array($userId, 'string'),
				array($collegeId, 'string'),
				array($comment, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function showSchoolMembersCount($appId,$userStatus,$schoolId,	$graduationYear)
	{
		$this->init();
		$this->CI->xmlrpc->method('showSchoolMembersCount');	
		$request = array (
				array($appId, 'string'),
				array($userStatus,'string'),
				array($schoolId, 'string'),
				array($graduationYear, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 
	}


	function showuserSchoolNetwork($appId,$start,$count,$userStatus,$collegeId,$graduationYear)
	{
		$this->init();
		$this->CI->xmlrpc->method('showuserSchoolNetwork');	
		$request = array (
				array($appId, 'string'),
				array($start, 'string'),
				array($count, 'string'),
				array($userStatus, 'string'),
				array($collegeId, 'string'),
				array($graduationYear, 'string'),
				'struct'			
				);

		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else 
		{
			$response = $this->CI->xmlrpc->display_response();
			//error_log_shiksha('SUCCESSFUL');
			error_log_shiksha('SUCCESSFULjsj' . print_r($response,true));

			return $response;
		} 	
	}

	function addtoSchoolNetwork($appId,$userStatus,$year,$userId,$collegeId,$cityId)
	{
		$this->init();
		$this->CI->xmlrpc->method('addtoSchoolNetwork');	
		$request = array (
				array($appId, 'string'),
				array($userStatus, 'string'),
				array($year, 'string'),
				array($userId, 'string'),
				array($collegeId, 'string'),
				array($cityId, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			$this->cacheLib->clearCache('Network');
			return $this->CI->xmlrpc->display_response();
		} 
	}

	function getcitiesbyCountry($appId,$country,$start,$count)
	{
		$this->init();
		$this->CI->xmlrpc->method('getcitiesbyCountry');	
		$request = array (
				array($appId, 'string'),
				array($country, 'string'),
				array($start, 'string'),
				array($count, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 
	}

	function checkifmember($appId,$institute,$userid,$instituteId,$grouptype)
	{
		$this->init();
		$this->CI->xmlrpc->method('checkifmember');	
		$request = 	array($appId,$institute,$userid,$instituteId,$grouptype);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 
	}

	function insertSchoolComment($appId,$userId,$schoolId,$comment,$titleofcomment)
	{
		$this->init();
		$this->CI->xmlrpc->method('insertSchoolComment');	
		$request = array (
				array($appId, 'string'),
				array($userId, 'string'),
				array($schoolId, 'string'),
				array($comment, 'string'),
				array($titleofcomment, 'string'),
				'struct'			
				);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function getMemberCountforSearch($appId,$Idsarray)
	{
		$this->init();
		$this->CI->xmlrpc->method('sgetMemberCountForSearch');
		// error_log_shiksha(print_r($Idsarray,true));
		//	$request = array ($appId,$Idsarray);
		// $request = array(array($appID,'int'),array(isset($Idsarray['schoolgroups'])?$Idsarray['schoolgroups']:0,'array'));
		$request = array(array($appID,'int'),array(isset($Idsarray['schoolgroups'])?$Idsarray['schoolgroups']:array(),'array'),array(isset($Idsarray['collegegroups'])?$Idsarray['collegegroups']:array(),'array'),array(isset($Idsarray['examgroup'])?$Idsarray['examgroup']:array(),'array'));
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	
	}

	function getRecentlyAddedMembers($appId,$instituteFlag,$count,$category)
	{
		$this->init();
		$key = md5('getRecentlyAddedMembers'.$appId.$instituteFlag.$count.$category);
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->CI->xmlrpc->method('sgetRecentlyAddedMembers');
			/*		$request = array (
					array($appId, 'string'),
					array($instituteFlag, 'string'),
					array($count, 'string'),
					array($category, 'string'),
					'struct'			
					);*/
			error_log_shiksha($category);
			$request = array($appId,$instituteFlag,$count,$category);
			$this->CI->xmlrpc->request($request);	

			if ( ! $this->CI->xmlrpc->send_request())
			{

				error_log_shiksha($this->CI->xmlrpc->display_error());		 
				return  $this->CI->xmlrpc->display_error();
			}
			else
			{
				$response = $this->CI->xmlrpc->display_response();
				$this->cacheLib->store($key,$response,1800,'Network');
				return $this->CI->xmlrpc->display_response();
			}
		}
		else
			return $this->cacheLib->get($key);
	}

	function updatealertstatus($appId,$product,$instituteId,$status,$userId,$grouptype)
	{
		$this->init();
		$this->CI->xmlrpc->method('updatealertstatus');
		$request = array($appId,$product,$instituteId,$status,$userId,$grouptype);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	

	}
	function updateStatistics($type,$instituteId,$count,$grouptype)
	{
		$this->init();
		$this->CI->xmlrpc->method('updateStatistics');
		$request = array($type,$instituteId,$count,$grouptype);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	

	}
	function insertgroupnews($appId,$product,$instituteId,$institutetype,$newstype,$userId,$newsdetails)
	{
		$this->init();
		$this->CI->xmlrpc->method('insertgroupnews');
		$request = array($appId,$product,$instituteId,$institutetype,$newstype,$userId,$newsdetails);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	

	}

	function getgroupnews($instituteId,$institutetype,$start,$count)
	{
		$this->init();
		$this->CI->xmlrpc->method('getgroupnews');
		$request = array($instituteId,$institutetype,$start,$count);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	

	}
	
    function getTestPrepForCategoryPage($appId,$examId)
	{
        $this->init();
        $this->CI->xmlrpc->method('getTestPrepForCategoryPage');
		$request = array($appId,$examId);
        error_log($request.'NEHANEHA');
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{

			error_log_shiksha($this->CI->xmlrpc->display_error());		 
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{

			return $this->CI->xmlrpc->display_response();
		} 	

	}
}
?>
