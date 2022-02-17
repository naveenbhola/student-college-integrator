<?php

/**
 *
 *  
 * 
 * @category LDB
 * @author Shiksha Team
 * @link https://www.shiksha.com
 */

class Ldbmis_Server extends MX_Controller {
    
    
	/**
         * index API
	 * @access public
         */
       
         function index()
        {
			$this->dbLibObj = DbLibCommon::getInstance('MIS');
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');

		$config['functions']['getresponseviewdata'] = array('function' => 'Ldbmis_Server.getresponseviewdata');
		$config['functions']['getsalesperson'] = array('function' => 'Ldbmis_Server.getsalesperson');
		$config['functions']['getleadviewdata'] = array('function' => 'Ldbmis_Server.getleadviewdata');
		$config['functions']['newsearchagents'] = array('function' => 'Ldbmis_Server.newsearchagents');
		$config['functions']['getdetailforsearchagent'] = array('function' => 'Ldbmis_Server.getdetailforsearchagent');
		$config['functions']['getleadsallocatedtosearchagent'] = array('function' => 'Ldbmis_Server.getleadsallocatedtosearchagent');
		$config['functions']['getsearchagents'] = array('function' => 'Ldbmis_Server.getsearchagents');
		$config['functions']['getleads'] = array('function' => 'Ldbmis_Server.getleads');
		$config['functions']['updateAttachment'] = array('function'=>'Ldbmis_Server.updateAttachment');
		$config['functions']['getleadsallocatedtoclient'] = array('function'=>'Ldbmis_Server.getleadsallocatedtoclient');



		
		
		 /* MIS FORM CRUD END */
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		
		return $this->$method($args[1]);
	}
	
	var $CI;
	
	
	
	/**
	* API to get leads allocated to a search agent
	*/
	
	
	function getleadsallocatedtosearchagent($request)
	{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$agentid = $parameters['1'];
		$startDate = $parameters['2'];
		$endDate = $parameters['3'];
		$clientid = $parameters['4'];
		$status = $parameters['5'];

		error_log("1");
		$this->load->model('Ldbmismodel');
		$object = new Ldbmismodel;
		$content = $object->getleadallocationtosearchagent($agentid,$startDate,$endDate,$clientid,$status);
		
		if (empty($content))
		{
			$response = array(array('status'=>'success but no result'),'struct');
			return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = strtr(base64_encode(addslashes(gzcompress( json_encode($content) , 9))) , '+/=', '-_,');
			return $this->xmlrpc->send_response($response);
		}
	}
	
    function getleadsallocatedtoclient($request)
	{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$clientid = $parameters['1'];
		$days = $parameters['2'];
		
		$this->load->model('Ldbmismodel');
		$object = new Ldbmismodel;
		$content = $object->getleadsallocatedtoclient($clientid, $days);
	    error_log("funny");
	
		
		if (empty($content))
		{
			$response = array(array('status'=>'success but no result'),'struct');
			return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = strtr(base64_encode(addslashes(gzcompress( json_encode($content) , 9))) , '+/=', '-_,');
			return $this->xmlrpc->send_response($response);
		}
	}


	function getnewsearchsagents()
	{
		$this->load->model('Ldbmismodel');
		$object = new Ldbmismodel;
		
		$array = $object->getnewsearchagents();
		
		
		$len = count($array);
		
		
		$k =0;
		$count = 0;
		for($i =0;$i <count($array); $i++)
		{
			
			
			
			if($array[$i]['sales'] != $array[$i+1]['sales'])
			{
				for($j = $count;$j<=$i;$j++)
				{
					
					$finalarray[$k]['data'][] = $array[$i];
					$finalarray[$k]['email'] = $array[$count]['salesemail'];
				}
			$count = $i;
			$k++;
			}
			
			
			
		
                    
		}
		
		if (empty($finalarray))
		{
			$response = array(array('status'=>'success but no result'),'struct');
			return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = strtr(base64_encode(addslashes(gzcompress( json_encode($finalarray) , 9))) , '+/=', '-_,');
			return $this->xmlrpc->send_response($response);
		}
		
		
	}
	
	  
		
	/**
	* 
	*to get unique id from attachment table
	*
	*/
	
	function updateAttachment($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
	
		$dbHandle = $this->_loadDatabaseHandle('write');
		$bUnique = false;
		$iAttempts = 0;
		while (!$bUnique && $iAttempts < 10) {
	
			error_log("here");
			$variable = $this->generatePID(7);
			$query ="select listing_type_id from attachmentTable where listing_type_id =?";
	
			$query=$dbHandle->query($query, array($variable));
			$num = $query->num_rows();
	
	
			if ($num <= 0) {
				$bUnique = true;
	
			} else {
				$iAttempts++;
	
			}
		}
		
	
	
		$response = $variable;
	
		return $this->xmlrpc->send_response($response);
	
	
	}
	
	
	
	function generatePID($no) {
		$this->load->helper('string');
		return random_string('numeric',$no);
	}


		
	function getresponseviewdata()
	{
		
		$this->load->model('Ldbmismodel');
		$object = new Ldbmismodel;
		
		$arraytosend = $object->getresponse();
		
		
		$sumsarray = $object->sums();
			
			foreach($arraytosend as $key=>$traversalarr)
			{
				
				foreach($sumsarray as $trav2)
				{
				
				if($traversalarr['ClientId'] == $trav2['ClientUserId'])
				{
					
					$arraytosend[$key]['salesperson'] = $trav2['displayname'];
					$arraytosend[$key]['email'] = $trav2['email'];
					
				}
				
				}
				
				
				
			}	
		
		
		
		usort($arraytosend, array('Ldbmis_Server','cmpare'));
		
		
		$array = $arraytosend;
	
		$k =0;
		$count = 0;
		for($i =0;$i <count($array); $i++)
		{
			
			if($array[$i]['salesperson'] != $array[$i+1]['salesperson'])
			{
				for($j = $count;$j<=$i;$j++)
				{
					
					$arr[$k]['data'][] = $array[$j];
					$arr[$k]['email'] = $array[$i]['email'];
				}
			$count = $i+1;
			$k++;
			}
			
			
                    
		}
		
		
		
		
		if (empty($arr))
		{
			$response = array(array('status'=>'success but no result'),'struct');
			return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = strtr(base64_encode(addslashes(gzcompress( json_encode($arr) , 9))) , '+/=', '-_,');
			return $this->xmlrpc->send_response($response);
		}
		
		
		
	}
	
		
		
		
		
		
	function newsearchagents()
	{
		
		
		$this->load->model('Ldbmismodel');
		$object = new Ldbmismodel;
		
		
		
		$traversalarray['data'] = $object->getnewsearchagents();
		
		
		
		for($k=0;$k < count($traversalarray['data']);$k++)
				{
					$arraytosend[$k]['searchagentid'] =	$traversalarray['data'][$k]['searchagentid'];
					$arraytosend[$k]['searchagentName']	= html_entity_decode($traversalarray['data'][$k]['searchagentName']);
					$arraytosend[$k]['created_on']	=$traversalarray['data'][$k]['created_on'];
					$arraytosend[$k]['clientid']	=$traversalarray['data'][$k]['clientid'];
					$arraytosend[$k]['ClientName']	=$traversalarray['data'][$k]['displayname'];
					$arraytosend[$k]['branch']	=$traversalarray['data'][$k]['BranchName'];
					$arraytosend[$k]['SalesPerson']	=$traversalarray['data'][$k]['sales'];
					
					
				}
		
		$this->CI =& get_instance();
	       
		$this->CI->config->load('Ldbmis_config',true);
			
		$recipient = $this->CI->config->item('recipient', 'Ldbmis_config');
	     		
		error_log($recipient);
		
		
		
		$content = array(
			'data' => $arraytosend,
			'email' => $recipient
		);
		
		
		
		
		 if (empty($content))
		{
			$response = array(array('status'=>'success but no result'),'struct');
			return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = strtr(base64_encode(addslashes(gzcompress( json_encode($content) , 9))) , '+/=', '-_,');
			return $this->xmlrpc->send_response($response);
		}

		
		
		
	}
		
	
	
	
	function getleads()
	{
		
		$this->load->model('Ldbmismodel');
		$object = new Ldbmismodel;
		
		$arraytosend = $object->newgetleadviewdata();
		
		
		$sumsarray = $object->sums();
			
			
			foreach($arraytosend as $key=>$traversalarr)
			{
				
				foreach($sumsarray as $trav2)
				{
				
				if($traversalarr['ClientId'] == $trav2['ClientUserId'])
				{
					
					$arraytosend[$key]['salesperson'] = $trav2['displayname'];
					$arraytosend[$key]['email'] = $trav2['email'];
					$arraytosend[$key]['Branch'] = $trav2['BranchName'];
				}
				
				}
				
				
				
			}	
		
		
		
		usort($arraytosend, array('Ldbmis_Server','cmpare'));
		
		
		$array = $arraytosend;
	
		$k =0;
		$count = 0;
		for($i =0;$i <count($array); $i++)
		{
			
			if($array[$i]['salesperson'] != $array[$i+1]['salesperson'])
			{
				for($j = $count;$j<=$i;$j++)
				{
					
					$arr[$k]['data'][] = $array[$j];
					$arr[$k]['email'] = $array[$i]['email'];
				}
			$count = $i+1;
			$k++;
			}
			
			
                    
		}
		
		
		
		$creditarr = $object->getcreditsremaining();
		
		
		foreach($arr as $key=>$traversing)
		{
			$i =0;
			foreach($traversing['data'] as $aaa)
			
			{
				
				$credit = 0;
				$arr[$key]['data'][$i]['creditsleft'] = $credit;
				for($x =0;$x <count($creditarr); $x++)
				{
					
					if($arr[$key]['data'][$i]['ClientId'] == $creditarr[$x]['ClientUserId'])
					{
						
						$credit = $creditarr[$x]['sum(BaseProdRemainingQuantity)'];
						$arr[$key]['data'][$i]['creditsleft'] = $credit;						
					}
					
					
				}
				
				
				$i++;
			}
		}

		
		if (empty($arr))
		{
			$response = array(array('status'=>'success but no result'),'struct');
			return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = strtr(base64_encode(addslashes(gzcompress( json_encode($arr) , 9))) , '+/=', '-_,');
			return $this->xmlrpc->send_response($response);
		}
		
		
		
	}
	
	
	
	
	
	
	function getsearchagents()
	{
		
				
		$this->load->model('Ldbmismodel');
		$object = new Ldbmismodel;
	
	
		$traversalarr =array();
			
			$traversalarr['download'] =$object->getdetailsforsearchagent();
			
			
			$traversalarr['email'] = $object->getautoresponderemail();
		
	
		
			$traversalarr['sms'] =$object->getautorespondersms();
		
		$x=0;
		
		if(!empty($traversalarr['download'][0]) && isset($traversalarr['download'][0]))
			{
				for($a =0;$a<count($traversalarr['download']);$a++) 
				{
					
					
					$arraytosend[] = $traversalarr['download'][$a];
					
					$x++;
					
				}
			}
			
			if(!empty($traversalarr['email'][0]) && isset($traversalarr['email'][0]))
			{
				for($a =0;$a<count($traversalarr['email']);$a++) 
				{
					$arraytosend[] = $traversalarr['email'][$a];
					
					$x++;
					
					
				}
			}
			
			if(!empty($traversalarr['sms'][0]) && isset($traversalarr['sms'][0]))
			{
				for($a =0;$a<count($traversalarr['sms']);$a++) 
				{
					$arraytosend[] = $traversalarr['sms'][$a];
					
					$x++;
				}
			}
			
			
			
			
			$sumsarray = $object->sums();
			
			
			foreach($arraytosend as $key=>$traversalarr)
			{
				
				foreach($sumsarray as $trav2)
				{
				
				if($traversalarr['clientid'] == $trav2['ClientUserId'])
				{
					
					$arraytosend[$key]['Branch'] = $trav2['BranchName'];
					$arraytosend[$key]['salesperson'] = $trav2['displayname'];
					$arraytosend[$key]['email'] = $trav2['email'];
					
				}
				
				}
				
				
				
			}
	
			
		usort($arraytosend, array('Ldbmis_Server','cmp'));
		
	$array = $arraytosend;
	
	
	
		$k =0;
		$count = 0;
		for($i =0;$i <count($array); $i++)
		{
			
			if($array[$i]['salesperson'] != $array[$i+1]['salesperson'])
			{
				for($j = $count;$j<=$i;$j++)
				{
					
					$arr[$k]['data'][] = $array[$j];
					$arr[$k]['email'] = $array[$i]['email'];
				}
			$count = $i+1;
			$k++;
			}
			
			
                    
		}
	
		
		$creditarr = $object->getcreditsremaining();

	
		foreach($arr as $key=>$traversing)
		{
			$i =0;
			foreach($traversing['data'] as $aaa)
			
			{
				
				$credit = 0;
				$arr[$key]['data'][$i]['creditsleft'] = $credit;
				for($x =0;$x <count($creditarr); $x++)
				{
					
					if($arr[$key]['data'][$i]['clientid'] == $creditarr[$x]['ClientUserId'])
					{
						
						$credit = $creditarr[$x]['sum(BaseProdRemainingQuantity)'];
						$arr[$key]['data'][$i]['creditsleft'] = $credit;						
					}
					
					
				}
				
				
				$i++;
			}
		}
			
		
		
		if (empty($arr))
		{
			$response = array(array('status'=>'success but no result'),'struct');
			return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = strtr(base64_encode(addslashes(gzcompress( json_encode($arr) , 9))) , '+/=', '-_,');
			return $this->xmlrpc->send_response($response);
		}
		
		
			
			
	}
	
	
	
	
	function cmp($a, $b) {	
	return strcmp($a["salesperson"], $b["salesperson"]);

	}
	

	function cmpare($a, $b) {
	
	return strcmp($a["salesperson"], $b["salesperson"]);

	}


}