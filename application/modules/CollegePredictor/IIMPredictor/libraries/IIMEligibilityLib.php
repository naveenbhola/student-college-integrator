<?php

/*
   Copyright 2015 Info Edge India Ltd
	
	Note:- The main idea behind developing ICP in this way was to make things configurable(Special requirement from product) 
	because IIM data will change every year. So code is made in such a way to entertain most of changes without much of code change.
 */

class IIMEligibilityLib{
	
	/*Array, holds list of IIM's in which user is eligible */
	private $eligibleList;
	
	/*Array, holds list of IIM's in which user is not eligible with its reason*/
	private $notEligibleList;

	function __construct(){
		$this->CI = & get_instance();
	}


	/* Function to check eligibility criteria for active IIM's
	 * @params: $activeIIMs => array having list of IIM's for which criteria needed to be checked
	 *			$data => User data sent from POST
	 * @return: List of IIM's in which user is eligible and List of IIM's in which user is not eligible and its reason
	 */
	public function checkIIMEligibility($activeIIMs, $data){

		/* eligibilityConfig.php contains the eligibility data for each IIM*/
        require_once APPPATH.'modules/CollegePredictor/IIMPredictor/config/eligibilityConfig.php';	

        $returnData = array();
        /*Loop through each actived IIM's  */
		foreach($activeIIMs as $key=>$IIM){
			
			/* Loading IIM eligibility variable dynamically from eligibilityConfig.php */
			$currentIIMEligibility = $IIM.'_Eligibility';
			$currentIIMEligibility = ${$currentIIMEligibility};

			/* condition to handle special cases, Ex: case of transgender for IIMA */
			if(isset($currentIIMEligibility[$data['specialCase']])){
				$currentIIMEligibilityData = $currentIIMEligibility[$data['specialCase']];
			}else{
				$currentIIMEligibilityData = $currentIIMEligibility[$data['category']];
			}

			$eligibilityFlag = true; 
			/*Loop through each criteria defined for user category (eg, General, ST, SC)*/
			foreach($currentIIMEligibilityData as $criteria=>$limit){
				/* Condition to check criteria, isset($data[$criteria]) is used to 
				 *	check only those details which are submitted by the user 
				 */
				if(isset($data[$criteria]) && $data[$criteria] < $limit){
					$eligibilityFlag = false;
					$this->notEligibleList[$IIM][$criteria] = $limit;
				}	
			}

			/*Saving IIM in which user is eligible */
			if($eligibilityFlag){
				$this->eligibleList[] = $IIM;
			}
		}
		$returnData['eligibleList'] = $this->eligibleList;
		$returnData['nonEligibilityData'] = $this->notEligibleList;

		return $returnData;

	}

	function checkEligbilityForColleges($data,$year){

		if(is_array($data) && !isset($data['xthPercentage'])){
			$data['xthPercentage'] = 0;
		}

		if(is_array($data) && !isset($data['xiithPercentage'])){
			$data['xiithPercentage'] = 0;
		}

		$iimpredictormodel = $this->CI->load->model('IIMPredictor/iimpredictormodel');

		$eligibleList = $iimpredictormodel->getEligibilityCollegesCount($data,$year);
		return $eligibleList;

	}
	function checkInEligibilityForColleges($data,$year,$fetchInEligibleInfo){
		if(is_array($data) && !isset($data['xthPercentage'])){
			$data['xthPercentage'] = 0;
		}

		if(is_array($data) && !isset($data['xiithPercentage'])){
			$data['xiithPercentage'] = 0;
		}

		$iimpredictormodel = $this->CI->load->model('IIMPredictor/iimpredictormodel');

		$inEligilityList = $iimpredictormodel->getInEligibilityCollegesCount($data,$year,$fetchInEligibleInfo);

		foreach ($inEligilityList as $supkey => $supvalue) {
			foreach ($supvalue as $subkey => $subvalue) {
				if($subkey != "instituteId")
					$inEligilityList[$supkey][$subkey] = number_format($subvalue,2,'.','');
			}
		}
		return $inEligilityList;
	}
}