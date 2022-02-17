<?php

   /**
    * Class CRUD Search Agent File for the shiksha
    *
    * @package searchagent
    * @author shiksha team
    */
class SearchAgents_client {

	var $CI_Instance;

	/**
	 * init API 4 write DB call
	 */

	function init()
	{
		set_time_limit(0);
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $ip;
		$server_url = "https://$ip/searchAgents/searchAgents_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->timeout(6000);
		$this->CI->xmlrpc->server($server_url,80);
	}

	/**
	 * initread API for Read DB call
	 */

	function initread()
	{
		set_time_limit(0);
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $searchIP;
		$server_url = "https://$searchIP/searchAgents/searchAgents_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->timeout(6000);
		$this->CI->xmlrpc->server($server_url,80);
	}

	/**
	 * addSearchAgent
	 * @return string
	 * @param int $appId
	 * @param Array $arr
	 */

	function addSearchAgent($appId,$arr)
	{
		$this->init();
		$this->CI->xmlrpc->method('wsAddSearchAgent');
		$request = array($appId,json_encode($arr));
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return ($this->CI->xmlrpc->display_response());
		}
	}

	/**
	 * getAllSearchAgent
	 * @return string
	 * @param int $appId
	 * @param Array $arr
	 */

	function getAllSearchAgents($appId,$clientId,$deliveryMethod)
	{
		$this->initread();
		$this->CI->xmlrpc->method('wsgetAllSearchAgents');
		$request = array($appId,$clientId,$deliveryMethod);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return (json_decode(base64_decode($this->CI->xmlrpc->display_response()),true));
		}
	}
	/**
	 * getAllDataSearchAgents
	 * @return string
	 * @param int $appId
	 * @param Array $arr
	 */
	function getAllDataSearchAgents($appId,$clientId)
	{
		$this->initread();
		$this->CI->xmlrpc->method('wsgetAllDataSearchAgents');
		$request = array($appId,$clientId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return (json_decode(base64_decode($this->CI->xmlrpc->display_response()),true));
		}
	}

	function SADeliveryOptions($appId,$emailFreq){
		$this->init();
		$this->CI->xmlrpc->method('SADeliveryOptions');
		$request = array($appId,$emailFreq);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return ($this->CI->xmlrpc->display_error());
		}else{
		return $this->CI->xmlrpc->display_response();
		}
	}

	function SAAutoResponder($appId){
                $this->init();
                $this->CI->xmlrpc->method('SAAutoResponder');
                $request = array($appId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return ($this->CI->xmlrpc->display_error());
                }else{
                return $this->CI->xmlrpc->display_response();
                }
        }

	/*
		TODO: API to run that particular search agent
	*/
	function runSearchAgent($appId,$sa_id)
	{
		$this->init();
		$this->CI->xmlrpc->method('wsrunSearchAgent');
		$request = array($appId,$sa_id);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}

	}

	function viewSearchAgentField($appId,$sa_id,$fieldName="")
	{
		$this->initread();
		$this->CI->xmlrpc->method('wsviewSearchAgentField');
		$request = array($appId,$fieldName);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return (json_decode(base64_decode($this->CI->xmlrpc->display_response()),true));
		}
	}

	function updateSearchAgentField($appId,$sa_id,$fieldName,$value,$actiontype,$fieldtype,$clientId)
	{
		$this->init();
		$this->CI->xmlrpc->method('wsupdateSearchAgentField');
		$request = array($appId,$sa_id,$fieldName,json_encode($value),$actiontype,$fieldtype,$clientId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}

	function updateSearchAgentDisplayData($appId,$sa_id,$inputArray,$displayArray,$inputHTML)
	{
		$this->init();
		$this->CI->xmlrpc->method('wsupdateSearchAgentDisplayData');
		$request = array($appId,$sa_id,json_encode($inputArray),json_encode($displayArray),$inputHTML);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return (json_decode(base64_decode($this->CI->xmlrpc->display_response()),true));
		}
	}

	function getAllContactDetails($appId,$clientId,$contactType="email")
	{
		$this->init();
		$this->CI->xmlrpc->method('wsgetAllContactDetails');
		$request = array($appId,$clientId,$contactType);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return (json_decode(base64_decode($this->CI->xmlrpc->display_response()),true));
		}
	}

	function validateSearchAgentName($appId,$clientId,$searchagentName)
	{
		$this->init();
		$this->CI->xmlrpc->method('wsvalidateAgentName');
		$request = array($appId,$clientId,$searchagentName);
		//print_r($request);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}

	function getSearchAgent($appId,$clientId,$sa_id)
	{
		$this->init();
		$this->CI->xmlrpc->method('wsgetSearchAgent');
		$request = array($appId,$clientId,$sa_id);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return (json_decode(base64_decode($this->CI->xmlrpc->display_response()),true));
		}
	}

	function getAllCreditToConsumedDataForSearchAgents($appId,$searchAgentIds,$leadid,$ExtraFlag='FALSE')
	{
		$this->init();
		$this->CI->xmlrpc->method('wsgetAllCreditToConsumedDataForSearchAgents');
		$request = array($appId,json_encode($searchAgentIds),$leadid,$ExtraFlag);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return (json_decode(base64_decode($this->CI->xmlrpc->display_response()),true));
		}
	}

	function filterDefaulterSearchAgents($appId,$searchAgentIds)
	{
		$this->init();
		$this->CI->xmlrpc->method('wsfilterDefaulterSearchAgents');
		$request = array($appId,json_encode($searchAgentIds));
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return (json_decode(base64_decode($this->CI->xmlrpc->display_response()),true));
		}
	}

	function getCreditConsumed($appId,$clientid,$userid,$credit,$action,$ExtraFlag='False')
	{
		$this->init();
		$this->CI->xmlrpc->method('wsgetCreditConsumed');
		$request = array($appId,$clientid,$userid,$credit,$action,$ExtraFlag);
		//print_r($request);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}

	function changeStatusAutoDownload($appId,$sa_id,$status)
	{
		$this->init();
		$this->CI->xmlrpc->method('wschangeStatusAutoDownload');
		$request = array($appId,$sa_id,$status);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return json_decode($this->CI->xmlrpc->display_response(),true);
		}
	}

	function changeStatusAutoResponderEmail($appId,$sa_id,$status)
	{
		$this->init();
		$this->CI->xmlrpc->method('wschangeStatusAutoResponderEmail');
		$request = array($appId,$sa_id,$status);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return json_decode($this->CI->xmlrpc->display_response(),true);
		}
	}

	function changeStatusAutoResponderSMS($appId,$sa_id,$status)
	{
		$this->init();
		$this->CI->xmlrpc->method('wschangeStatusAutoResponderSMS');
		$request = array($appId,$sa_id,$status);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return json_decode($this->CI->xmlrpc->display_response(),true);
		}
	}

	function changeStatusSearchAgent($appId,$sa_id,$status)
	{
		$this->init();
		$this->CI->xmlrpc->method('wschangeStatusSearchAgent');
		$request = array($appId,$sa_id,$status);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return json_decode($this->CI->xmlrpc->display_response(),true);
		}

	}

	function changeEmailFrequencySearchAgent($appId,$sa_id,$status)
	{
		$this->init();
		$this->CI->xmlrpc->method('wschangeEmailFrequencySearchAgent');
		$request = array($appId,$sa_id,$status);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return json_decode($this->CI->xmlrpc->display_response(),true);
		}
	}

	function changeSmsFrequencySearchAgent($appId,$sa_id,$status)
        {
                $this->init();
                $this->CI->xmlrpc->method('wschangeSmsFrequencySearchAgent');
                $request = array($appId,$sa_id,$status);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request())
                {
                        return $this->CI->xmlrpc->display_error();
                }
                else
                {
                        return json_decode($this->CI->xmlrpc->display_response(),true);
                }
        }

	function UpdateSearchAgent($appId,$sa_id,$FormData)
	{
		$this->init();
		$this->CI->xmlrpc->method('wsUpdateSearchAgent');
		$request = array($appId,$sa_id,json_encode($FormData));
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return json_decode($this->CI->xmlrpc->display_response(),true);
		}
	}

	function getSADisplayData($appId,$searchagentid)
        {
                $this->init();
                $this->CI->xmlrpc->method('wsgetSADisplayData');
                $request = array($appId,$searchagentid);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request())
                {
                        return $this->CI->xmlrpc->display_error();
                }
                else
                {
                        return $this->CI->xmlrpc->display_response();
                }
        }

	function SearchAgentsAllDetails($appId,$clientId,$startFrom,$count,$deliveryMethod)
	{
		$this->initread();
		$this->CI->xmlrpc->method('wsSearchAgentsAllDetails');
		$request = array($appId,$clientId,$startFrom,$count,$deliveryMethod);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return (json_decode(base64_decode($this->CI->xmlrpc->display_response()),true));
		}
	}

	function SearchAgentDetail($appId,$sa_id)
        {
                $this->initread();
                $this->CI->xmlrpc->method('wsgetSearchAgentDetail');
                $request = array($appId,$sa_id);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request())
                {
                        return $this->CI->xmlrpc->display_error();
                }
                else
                {
                        return (json_decode(base64_decode($this->CI->xmlrpc->display_response()),true));
                }
        }
	function validatecmsAdminLogin($appId,$password,$email)
	{
		$this->initread();
                $this->CI->xmlrpc->method('wsValidatecmsAdminLogin');
                $request = array($appId,$password,$email);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request())
                {
                        return $this->CI->xmlrpc->display_error();
                }
                else
                {
                        return $this->CI->xmlrpc->display_response();
                }
	}
	function getAllMultiValuesSearchAgent($appId,$said)
	{
	  $this->init();
	  $this->CI->xmlrpc->method('sgetAllMultiValuesSearchAgent');
	  $request = array($appId,$said);
	  //print_r($request);
	  $this->CI->xmlrpc->request($request);
	  if ( ! $this->CI->xmlrpc->send_request())
	  {
	    return $this->CI->xmlrpc->display_error();
	  }
	  else
	  {
	    return ($this->CI->xmlrpc->display_response());
	  }
	}

/*
function matchingLeads($appId){
                $this->init();
                $this->CI->xmlrpc->method('matchingLeads');
                $request = array($appId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return ($this->CI->xmlrpc->display_error());
                }else{
                return $this->CI->xmlrpc->display_response();
                }
        }
*/
}
/* End of file searchAgents_client.php */ /* Location: ./system/system/application/libraries/searchAgents_client.php */
