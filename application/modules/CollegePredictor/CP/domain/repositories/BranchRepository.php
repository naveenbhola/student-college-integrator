<?php
class BranchRepository extends EntityRepository
{

	function __construct($dao,$cache,$model)
	{  
		parent::__construct($dao,$cache,$model);
		/*
		 * Load entities required
		 */
		$this->CI->load->entities(array('Branch'),'CP');
	}

	function findMultiple($results){
		if(0 && $this->caching && $branchEntityObj = $this->cache->getBranchObj($results)) { 
	            return $branchEntityObj;
	        }
		$branchDataResults = $this->model->getMultipleData($results);
		if(empty($branchDataResults)){
			$branchDataResults['collegeData'] = array();
		}
		$branch = $this->_loadMultiple($branchDataResults['collegeData'], $results);
		$branchEntityObj = new Branch();
		$branchEntityObj->setPageData($branch);
		// _p($branchEntityObj);die;
		// $this->cache->storeBranchObj($results,$branchEntityObj);
		// if($results['examName'] == 'DU' || strtolower($results['examName']) == 'jee-mains'){
			$returnData['branchEntityObj'] =  $branchEntityObj;
			$returnData['totalBranchCount'] = $branchDataResults['countOfbranch'];
			return $returnData;
		// }
		return $branchEntityObj;
	}
	
	private function _loadMultiple($results, $inputData)
	{
		$branch = $this->_load($results, $inputData);
		return ($branch);
	}
	
	private function _load($results, $inputData)
	{
		$branchData = array();
		if(is_array($results) && count($results)) {
			foreach($results as $key => $branchArr){
				$roundsInfo = array();
				foreach($branchArr as $roundNumber => $branchInfo){
					$branchId = $branchInfo['branchId'];
					// $roundNumber = $branchInfo['numberOfRound'];
					$roundsInfo[$roundNumber]['closingRank'] = $branchInfo['closingRank'];
					$roundsInfo[$roundNumber]['round'] = $branchInfo['numberOfRound'];
					
					$rankType = $branchInfo['rankType'];
					$stateName = $branchInfo['stateName'];
					$branchInfo['isHomeStateTuple'] = 0;
					if(!empty($inputData['stateName']) && $inputData['stateName'] == $stateName && strtolower($rankType) == 'home') {
						$branchInfo['isHomeStateTuple'] = 1;
					}
				}
				unset($branchInfo['closingRank']);
				ksort($roundsInfo);
				$branchArr = array_merge($branchInfo,array('roundsInfo' =>  $roundsInfo));
				$branch = $this->_createBranch($branchArr);
				$branchData[$branchId.'_'.$rankType] = $branch;
			}
		}
		return $branchData;
	}
	
	private function _createBranch($result)
	{
		$branch = new Branch;
		$branchData = (array) $result;
		$this->fillObjectWithData($branch,$branchData);
		return $branch;
	}
}
?>
