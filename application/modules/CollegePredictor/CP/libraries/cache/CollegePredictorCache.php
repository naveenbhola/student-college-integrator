<?php

class CollegePredictorCache extends Cache
{
    function __construct()
	{
		parent::__construct();
	}
    
    /*
	 * Category
	 */ 
	function getBranchObj($results)
	{	//error_log(print_r($results,true),3,'/home/pranjul/Desktop/pranjul.txt');
		$tabName = $results['tabName'];
		if(array_key_exists('stateName',$results)){
			$stateName = base64_encode($results['stateName']);
		}else{
			$stateName = 0;
		}

		if($tabName=='rank'){ 
			$key = md5($results['rankType'].'::::'.$stateName.'::::'.$results['categoryName'].'::::'.$results['rank'].'::::'.$results['round'].'::::'.$results['examName']);
			return unserialize($this->get('ranktab',$key));
		}

		if($tabName=='college'){		
			foreach($results['instituteId'] as $key=>$value){
				$insId .= $value.',';
			}
			$insId = rtrim($insId,',');
			$resultKey = md5($results['rankType'].'::::'.$stateName.'::::'.$results['categoryName'].'::::'.$insId.'::::'.$results['round'].'::::'.$results['examName']);		
			//error_log($resultKey,3,'/home/pranjul/Desktop/pranjul.txt');			
			return unserialize($this->get('collegetab',$resultKey));
		}
	}
	
	function storeBranchObj($results,$branchObj)
	{ 
		$tabName = $results['tabName'];
		$tabType = $results['tabType'];
		if(array_key_exists('stateName',$results)){
			$stateName = base64_encode($results['stateName']);
		}else{
			$stateName = 0;
		}
		
		if($tabName=='rank'){		
			$key = md5($results['rankType'].'::::'.$stateName.'::::'.$results['categoryName'].'::::'.$results['rank'].'::::'.$results['round'].'::::'.$results['examName']);
			$this->store('ranktab',$key,serialize($branchObj),1800);
		}
		if($tabName=='college'){
			foreach($results['instituteId'] as $key=>$value){
				$insId .= $value.',';
			}
			$insId = rtrim($insId,',');
			$resultKey = md5($results['rankType'].'::::'.$stateName.'::::'.$results['categoryName'].'::::'.$insId.'::::'.$results['round'].'::::'.$results['examName']);	
			//error_log($resultKey,3,'/home/pranjul/Desktop/pranjul.txt');
			$this->store('collegetab',$resultKey,serialize($branchObj),1800);
		}
	}
	
	function storePredictorDefaultResults($data, $examName) {
		$this->store('defaultCP',$examName,serialize($data),1800);
	}

	function getPredictorDefaultResults($examName) {
		return unserialize($this->get('defaultCP',$examName));
	}
 
}

