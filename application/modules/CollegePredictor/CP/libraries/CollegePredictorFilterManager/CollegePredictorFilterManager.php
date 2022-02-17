<?php
class CollegePredictorFilterManager {
	
	private $_ci;
	
	public function __construct($_ci){
		if(empty($ci)) {
			$this->_ci = & get_instance();
		}
		else {
			$this->_ci = $_ci;
		}
		$this->cpmodel = $this->_ci->load->model('CP/cpmodel');
	}
	
	public function getFilters(Branch $branchObj = NULL, $appliedFilters = array()){
		$filters = array();
		$filterTypes = array('branch', 'location', 'college');
		if(empty($branchObj) || empty($appliedFilters)){
			return $filters;
		}
		foreach($filterTypes as $filterType){
			switch($filterType){
				case 'branch':
					$branchFilters 		= $this->getBranchFilters($branchObj, $appliedFilters);
					$filters['branch'] 	= $branchFilters;
					break;
				case 'location':
					$locationFilters 	= $this->getLocationFilters($branchObj, $appliedFilters);
					$filters['location'] 	= $locationFilters;
					break;
				case 'college':
					$collegeFilters 	= $this->getCollegeFilters($branchObj, $appliedFilters);
					$filters['college'] 	= $collegeFilters;
					break;
			}
		}
		return $filters;
	}

	public function getBranchFilters($branchObj, $filters){
	// _p($filters); die; 
		$this->_ci->load->library("CP/CollegePredictorFilterManager/CollegePredictorFilter");
		$branch 	= $filters['branchFilter'];
		$branchFilterDb = $this->cpmodel->getFiltersForCollegePredictor($filters, 'specialization');
		foreach($branchFilterDb as $key=>$value){
			if(in_array($value['branchName'],$branch)){
				$filterObject 	= new CollegePredictorFilter($value['branchName'], $value['branchName'], true);
			}else{
				$filterObject 	= new CollegePredictorFilter($value['branchName'], $value['branchName'], false);
			}
			$branchFiltersList[] = $filterObject;
			$i++;
		} 
		return $branchFiltersList;
		_p($branchFiltersList); die;
		$branchFiltersList = array();
		if(empty($branchObj) || empty($filters)){
			return $branchFiltersList;
		}
		
		$branchData  = $branchObj->getPageData();
		if(empty($branchData) || !is_array($branchData)){
			return $branchFiltersList;
		}

		$location 	= $filters['locationFilter'];
		
		$college 	= $filters['collegeFilter'];
		$round 		= $filters['roundFilter'];

		$tempData 	= array();
		$tempData 	= $branchData;

		if(array_key_exists('city',$location) && !empty($location['city'])){
			foreach($location['city'] as $key=>$value){
				foreach($branchData as $pageData) {
					if($pageData->getLocationId()==$value){
						$tempData1[]  = $pageData;
					}
				}
			}
			$tempData = $tempData1;
		}

		if(array_key_exists('state',$location) && !empty($location['state'])){
			foreach($location['state'] as $key=>$value){
				foreach($branchData as $pageData) {
					if($pageData->getStateName()==$value){
						$tempData1[]  = $pageData;
					}
				}
			}
			$tempData = $tempData1;
		}

		if(!empty($round)){
			foreach($round as $key=>$value){
				foreach($tempData as $pageData) {
					if($pageData->getNumberOfRound()==$value){
						$tempData2[]  = $pageData;
					}
				}
			}
			$tempData = $tempData2;
		}

		if(!empty($college)){
			foreach($college as $key=>$value){
				foreach($tempData as $pageData) {
					if($pageData->getInstituteId()==$value){
						$tempData3[]  = $pageData;
					}
				}
			}
			$tempData = $tempData3;
		}

		$i=0;
		// _p($tempData); die;
		foreach($tempData as $key=>$value){
			if(!in_array($value->getBranchName(),$tmp)){
				$tmp[] = $value->getBranchName();
				if(in_array($value->getBranchName(),$branch)){
					$filterObject 	= new CollegePredictorFilter($value->getBranchName(), $value->getBranchName(), true);
				}else{
					$filterObject 	= new CollegePredictorFilter($value->getBranchName(), $value->getBranchName(), false);
				}
				$branchFiltersList[] = $filterObject;
				$i++;
			}
		} 
//		error_log("branchObj===".print_r($branchFiltersList,true));
		usort($branchFiltersList, array($this, 'branchCompareDESC'));
		return $branchFiltersList;
	}	

	public function branchCompareDESC($a, $b){
		return strcmp($a->getName(), $b->getName());
	}

	public function getLocationFilters($branchObj, $filters){
		// _p($filters); die;
		$this->_ci->load->library("CP/CollegePredictorFilterManager/CollegePredictorFilter");	
		$location 	= $filters['locationFilter'];
		if($filters['rankType']=='Home' || $filters['rankType']=='StateLevel' || $filters['rankType']=='HomeUniversity' || $filters['rankType']=='HyderabadKarnatakaQuota' || strtolower($filters['examName']) == 'mhcet' || strtolower($filters['examName']) == 'ptu'  || strtolower($filters['examName']) == 'mppet' || strtolower($filters['examName']) == 'upsee' || strtolower($filters['examName']) == 'wbjee' || strtolower($filters['examName']) == 'kcet'){
			$type = 'city';
		}
		else {
			$type = 'state';
		}
		$locationFilterDb = $this->cpmodel->getFiltersForCollegePredictor($filters, $type);
		// _p($locationFilterDb); die;
		foreach($locationFilterDb as $key=>$value){
			if(in_array($value[$type.'Name'],$location[$type])){
				$filterObject 	= new CollegePredictorFilter($value[$type.'Name'], $value[$type.'Name'], true);
			}else{
				$filterObject 	= new CollegePredictorFilter($value[$type.'Name'], $value[$type.'Name'], false);
			}
			$locationFiltersList[] = $filterObject;
			$i++;
		} 
		return $locationFiltersList;
		$locationFiltersList = array();
		if(empty($branchObj) || empty($filters)){
			return $locationFiltersList;
		}
		
		$branchData  = $branchObj->getPageData();
		if(empty($branchData) || !is_array($branchData)){
			return $locationFiltersList;
		}

		$location 	= $filters['locationFilter'];
		$branch 	= $filters['branchFilter'];
		$college 	= $filters['collegeFilter'];
		$round 		= $filters['roundFilter'];

		$tempData 	= array();
		$tempData 	= $branchData;
		if(!empty($branch)){
			foreach($branch as $key=>$value){
				foreach($branchData as $pageData) {
					if($pageData->getBranchName()==$value){
						$tempData1[]  = $pageData;
					}
				}
			}
			$tempData = $tempData1;
		}

		if(!empty($college)){
			foreach($college as $key=>$value){
				foreach($tempData as $pageData) {
					if($pageData->getInstituteId()==$value){
						$tempData2[]  = $pageData;
					}
				}
			}
			$tempData = $tempData2;
		}

		if(!empty($round)){
			foreach($round as $key=>$value){
				foreach($tempData as $pageData) {
					if($pageData->getNumberOfRound()==$value){
						$tempData3[]  = $pageData;
					}
				}
			}
			$tempData = $tempData3;
		}

		$i=0;

		if($filters['rankType']=='Home' || $filters['rankType']=='StateLevel' || $filters['rankType']=='HomeUniversity' || $filters['rankType']=='HyderabadKarnatakaQuota' || strtolower($filters['examName']) == 'mhcet' || strtolower($filters['examName']) == 'ptu'  || strtolower($filters['examName']) == 'mppet' || strtolower($filters['examName']) == 'upsee' || strtolower($filters['examName']) == 'wbjee' || strtolower($filters['examName']) == 'kcet'){
			foreach($tempData as $key=>$value){
				if(!in_array($value->getLocationId(),$temp)){
					$temp[] = $value->getLocationId();
					if(in_array($value->getLocationId(),$location['city'])){
						$filterObject 	= new CollegePredictorFilter($value->getCityName(), $value->getLocationId() , true);
					}else{
						$filterObject 	= new CollegePredictorFilter($value->getCityName(), $value->getLocationId(), false);
					}
					$locationFiltersList[] = $filterObject;
					$i++;
				}
			}
		}else{
			foreach($tempData as $key=>$value){
				if(!in_array($value->getStateName(),$temp)){
					$temp[] = $value->getStateName();
					//$locationFiltersList['stateName'][$i] = $value->getStateName();
					if(in_array($value->getStateName(),$location['state'])){
						$filterObject 	= new CollegePredictorFilter($value->getStateName(), $value->getStateName(), true);
					}else{
						$filterObject 	= new CollegePredictorFilter($value->getStateName(), $value->getStateName(), false);
					}
					$locationFiltersList[] = $filterObject;
					$i++;
				}
			}
		}
		if($filters['rankType']=='Home' || $filters['rankType']=='StateLevel' || $filters['rankType']=='HomeUniversity' || $filters['rankType']=='HyderabadKarnatakaQuota' || strtolower($filters['examName']) == 'mhcet' || strtolower($filters['examName']) == 'ptu'  || strtolower($filters['examName']) == 'mppet' || strtolower($filters['examName']) == 'upsee' || strtolower($filters['examName']) == 'wbjee'|| strtolower($filters['examName']) == 'kcet'){
			usort($locationFiltersList,array($this, 'cityCompareDESC'));
		}else{
			usort($locationFiltersList,array($this, 'stateCompareDESC'));
		}
		return $locationFiltersList;
	}

	public function cityCompareDESC($a, $b){
		return (int)$a->getValue() - (int)$b->getValue();
	}

	public function stateCompareDESC($a, $b){
		return strcmp($a->getName(), $b->getName());
	}
		
	public function getCollegeFilters($branchObj, $filters){
		$this->_ci->load->library("CP/CollegePredictorFilterManager/CollegePredictorFilter");
		$collegeFiltersList = array();
		$college 	= $filters['collegeFilter'];
		$collegeFilterDb = $this->cpmodel->getFiltersForCollegePredictor($filters, 'college');
		// _p($collegeFilterDb); die;
		foreach($collegeFilterDb as $key=>$value){
			$collegeLocationStr = $value['collegeName'].', '.$value['cityName'].', '.$value['stateName'];
			if(in_array($value['id'],$college)){
				$filterObject 	= new CollegePredictorFilter($collegeLocationStr, $value['id'] , true, $value['collegeGroupName']);
			}else{
				$filterObject 	= new CollegePredictorFilter($collegeLocationStr, $value['id'], false, $value['collegeGroupName']);
			}
			$collegeFiltersList[] = $filterObject;
			$i++;
		} 
		return $collegeFiltersList;
		if(empty($branchObj) || empty($filters)){
			return $collegeFiltersList;
		}
		
		$branchData  = $branchObj->getPageData();
		if(empty($branchData) || !is_array($branchData)){
			return $collegeFiltersList;
		}

		$location 	= $filters['locationFilter'];
		$branch 	= $filters['branchFilter'];
		$round 		= $filters['roundFilter'];

		$tempData 	= array();
		$tempData 	= $branchData;

		if(array_key_exists('city',$location) && !empty($location['city'])){
			foreach($location['city'] as $key=>$value){
				foreach($branchData as $pageData) {
					if($pageData->getLocationId()==$value){
						$tempData1[]  = $pageData;
					}
				}
			}
			$tempData = $tempData1;
		}

		if(array_key_exists('state',$location) && !empty($location['state'])){
			foreach($location['state'] as $key=>$value){
				foreach($branchData as $pageData) {
					if($pageData->getStateName()==$value){
						$tempData1[]  = $pageData;
					}
				}
			}
			$tempData = $tempData1;
		}


		if(!empty($branch)){
			foreach($branch as $key=>$value){
				foreach($tempData as $pageData) {
					if($pageData->getBranchName()==$value){
						$tempData2[]  = $pageData;
					}
				}
			}
			$tempData = $tempData2;
		}

		if(!empty($round)){
			foreach($round as $key=>$value){
				foreach($tempData as $pageData) {
					if($pageData->getNumberOfRound()==$value){
						$tempData3[]  = $pageData;
					}
				}
			}
			$tempData = $tempData3;
		}
		usort($tempData, array($this, 'collegeCompareDESC'));
		$i=0;
		foreach($tempData as $key=>$value){
			if(!in_array($value->getInstituteId(),$temp)){
				$temp[] = $value->getInstituteId();
				//$collegeFiltersList['collegeId'][$i]   = $value->getInstituteId();
				//$collegeFiltersList['collegeName'][$i] = $value->getCollegeName();
				//$collegeFiltersList['cityName'][$i]    = $value->getCityName();
				//$collegeFiltersList['stateName'][$i]   = $value->getStateName();
				$collegeLocationStr = $value->getCollegeName().', '.$value->getCityName().', '.$value->getStateName();
				if(in_array($value->getInstituteId(),$college)){
					$filterObject 	= new CollegePredictorFilter($collegeLocationStr, $value->getInstituteId() , true, $value->getCollegeGroupName());
				}else{
					$filterObject 	= new CollegePredictorFilter($collegeLocationStr, $value->getInstituteId(), false, $value->getCollegeGroupName());
				}
				$collegeFiltersList[] = $filterObject;
				$i++;
			}
		}
		return $collegeFiltersList;
	}

	public function collegeCompareDESC($a, $b){
		return strcmp($a->getCollegeName(), $b->getCollegeName());
	}

	public function applyFilters(Branch $branchObj, $filters) {
		if(empty($branchObj)){
			return;
		}
		$branchData = $branchObj->getPageData();
		if(empty($branchData) || !is_array($branchData)){
			return;
		}
		
		$location 	= $filters['locationFilter'];
		$branch 	= $filters['branchFilter'];
		$round 		= $filters['roundFilter'];
		$college 	= array_unique($filters['collegeFilter']);
		$tempData 	= array();
		$tempData 	=  $branchData;
		
		if(array_key_exists('city',$location) && !empty($location['city'])){
			foreach($branchData as $pageData) {
				if(in_array($pageData->getLocationId(),$location['city'])){
					$tempData1[]  = $pageData;
				}
			}
			$tempData = $tempData1;
		}

		if(array_key_exists('state',$location) && !empty($location['state'])){
			foreach($branchData as $pageData) {
				if(in_array($pageData->getStateName(),$location['state'])){
					$tempData1[]  = $pageData;
				}
			}
			$tempData = $tempData1;
		}

		if(!empty($branch)){
			foreach($tempData as $pageData) {
				if(in_array($pageData->getBranchName(),$branch)){
					$tempData2[]  = $pageData;
				}
			}
			$tempData = $tempData2;
		}

		if(!empty($college)){
			foreach($tempData as $pageData) {
				if(in_array($pageData->getInstituteId(),$college)){
					$tempData4[]  = $pageData;
				}
			}
			$tempData = $tempData4;
		}

		$branchObj->setPageData($tempData);
	}
}
