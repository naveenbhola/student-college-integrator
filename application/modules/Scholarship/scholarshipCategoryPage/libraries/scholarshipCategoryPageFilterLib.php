<?php

class scholarshipCategoryPageFilterLib{
    private $CI;
    function __construct(){
        $this->CI = &get_instance();
        // location
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder 		= new LocationBuilder;
        $this->locationRepo = $locationBuilder->getLocationRepository();
        // category
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder 	= new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();
        // special restriction
        $this->CI->load->config('studyAbroadCMSConfig');
		
    }
    public function getProcessedFilterData($rawFilterData = array(),$scholarshipCategoryPageRequest){
//                _p($rawFilterData);die;
        $processedFilterData = array();
        // parse each filter data to get relevant information based on Ids
        foreach($rawFilterData['facets']	as $filterName => $filterValues)
        {
            switch($filterName)
            {
                case "saScholarshipCategoryId" : // course stream
                case "saScholarshipCategoryId_parent":
                    $processedFilterData[$filterName] = $this->_processParentCategory($filterValues);
                    break;
                case "saScholarshipStudentNationality" : //citizenship
                case "saScholarshipStudentNationality_parent" :
                case "saScholarshipCountryId" : // sch countries / destinatio country
                case "saScholarshipCountryId_parent" :
                    $processedFilterData[$filterName] = $this->_processCountry($filterValues);
                    break;
                case "saScholarshipIntakeYear" :
                case "saScholarshipIntakeYear_parent" :
                    $processedFilterData[$filterName] = $this->_processIntakeYear($filterValues);
                    break;
                case "saScholarshipCategory":
                case "saScholarshipCategory_parent" : // college/non-college specific
                    $processedFilterData[$filterName] = $this->_processScholarshipCategory($filterValues);
                    break;
                case "saScholarshipType" : //need / merit / both
                case "saScholarshipType_parent":
                    $processedFilterData[$filterName] = $this->_processScholarshipType($filterValues);
                    break;
                case "saScholarshipSpecialRestriction" : 
                case "saScholarshipSpecialRestriction_parent" :
                    $processedFilterData[$filterName] = $this->_processSpecialRestriction($filterValues);
                    break;
                case "saScholarshipUnivIdNameMap" : // in case of  all level X country page get all univs of the country ??? 
                case "saScholarshipUnivIdNameMap_parent" :
                    $processedFilterData[$filterName] = $this->_processApplicableUniversities($filterValues);
                    break;
                case "saScholarshipCourseLevel" : 
                case "saScholarshipCourseLevel_parent" :
                    $processedFilterData[$filterName] = $this->_processCourseLevel($filterValues);
                    break;
                case "saScholarshipAwardsCount" : // no. of students to be awarded
                case "saScholarshipAwardsCount_parent":
                    $processedFilterData[$filterName] = $this->_processScholarshipAwards($filterValues);
                    break;
                case "saScholarshipApplicationEndDate" :
                case "saScholarshipApplicationEndDate_parent":
                    $processedFilterData[$filterName] = $this->_processDeadlines($filterValues);
                    break;
                case "saScholarshipAmount" :
                case "saScholarshipAmount_parent" :
                    $processedFilterData[$filterName] = $this->_processScholarshipAmount($filterValues);
                    break;
                case "default" :
                    break;
            }
        }
	// get formatted applied filters for top bar of filter selection
	$formattedAppliedFilters = $this->_formatAppliedFilters($scholarshipCategoryPageRequest,$processedFilterData);
        return array('processedFilterData'=>$processedFilterData,
					 'formattedAppliedFilters'=>$formattedAppliedFilters);
    }
    /*
	 * function to process course stream
	 */
	private function _processParentCategory($filterValues)
	{
		// get category Obj  for each category Id
		$categoryIds = array_map(function($a){ if(is_numeric($a)){ return $a; } }, array_keys($filterValues));
		$categoryIds = array_filter($categoryIds); // if we are using 'all' skip this step and the isnumeric check above
                $categories = array();
                if(empty($categoryIds)){
                    return $categories;
                }
		$categoryObjs = $this->categoryRepository->findMultiple($categoryIds);
		$commonCount = ($filterValues['all'] > 0?$filterValues['all'] : 0);
		unset($filterValues['all']);
		foreach($filterValues as $category => $catCount)
		{
			$categories[$category] = array(
																'name' =>$categoryObjs[$category]->getName(),
																'count'=>$catCount + $commonCount
																);
		}
		return $categories;
	}
	/*
	 * function to process citizenship or scholarship countries
	 */
	private function _processCountry($filterValues)
	{
            $countries = array();
            $locs = $this->locationRepo->getCountries();
            $countryList = array();
            foreach($locs as $loc){
                $countryList[$loc->getId()] = $loc->getName();
            }
            $commonCount = ($filterValues[1]>0?$filterValues[1]:0);
            unset($filterValues[1]);
            foreach($filterValues as $countryId => $countryCount){
		if(is_numeric($countryId) && $countryId > 0 ){
			$countryObj = $this->locationRepo->findCountry($countryId);
			$countries[$countryId] = array(
											'name'=>$countryList[$countryId],
											'count'=>$countryCount +$commonCount
											);
			unset($countryList[$countryId]);
		}
            }
            if(count($countryList)>1 && $commonCount>0)
            {
                foreach($countryList as $id => $name)
                {
                    $countries[$id] = array(
                                            'name'=>$name,
                                            'count'=>$commonCount
                                            );
                }
            }
            return $countries;
	}
	/*
	 * function to process intake year list
	 */
	private function _processIntakeYear($filterValues)
	{
		$intakeYears = array();
		foreach($filterValues as $intakeYear=> $intakeYearCount)
		{
			//$intakeDate = date_create_from_format('Y-m-d',$intakeYear);
			//$intakeYear = $intakeDate->format('Y');
			if(!isset($intakeYears[$intakeYear]))
			{
				$intakeYears[$intakeYear] = 0;
			}
			$intakeYears[$intakeYear] = $intakeYears[$intakeYear] + $intakeYearCount;
		}
		return $intakeYears;
	}
	/*
	 * college/non-college specific
	 */
	private function _processScholarshipCategory($filterValues)
	{
		return $filterValues;
	}
	/*
	 * need / merit / both
	 */
	private function _processScholarshipType($filterValues)
	{
		if($filterValues['both'] > 0)
		{
			$filterValues['merit'] = $filterValues['merit'] + $filterValues['both'];
			$filterValues['need'] = $filterValues['need'] + $filterValues['both'];
			unset($filterValues['both']);
		}
		return $filterValues;
	}
	/*
	 * function to process spl restriction
	 */
	private function _processSpecialRestriction($filterValues)
	{
		$specialRestrictions = array();
		$allRestrictions = $this->CI->config->item('SCHOLARSHIP_SPECIAL_RESTRICTION');
		foreach($filterValues as $restrictionId=>$restrictionCount)
		{
			$specialRestrictions[$restrictionId] = array(
																			'name' => $allRestrictions[$restrictionId],
																			'count' => $restrictionCount
																		);
		}
		return $specialRestrictions;
	}
	/*
	 * in case of  all level X country page get all univs of the country ???
	 */
	private function _processApplicableUniversities($filterValues)
	{
		$universities = array();
		foreach($filterValues as $univNameIdMap  => $univCount)
		{
			$univArr = explode(':',$univNameIdMap);
			$universities[$univArr[1]] = array(
															'name'=>$univArr[0],
															'count'=>$univCount
															);
		}
		return $universities;
	}
	/*
	 * function to process course level into 2 B & M
	 */
	private function _processCourseLevel($filterValues)
	{
		$courseLevels = array();
		// is there a facet for all ?
		$allLevelCount = ($filterValues['all'] > 0?$filterValues['all']:0);
		// combine all bachelors as one & all masters & phd as one
		foreach($filterValues as $level => $levelCount)
		{
			if($level != "all")
			{
				if(strpos($level,"Bachelors") === false)
				{
					if(!isset($courseLevels["Masters"]))
					{
						$courseLevels["Masters"] = $allLevelCount;
					}
					$courseLevels["Masters"] = $courseLevels["Masters"] + $levelCount;
				}else{
					if(!isset($courseLevels["Bachelors"]))
					{
						$courseLevels["Bachelors"] = $allLevelCount;
					}
					$courseLevels["Bachelors"] = $courseLevels["Bachelors"] + $levelCount;
				}
			}
		}
		return $courseLevels;
	}
	/*
	 * function to process no. of students to be awarded
	 */
	private function _processScholarshipAwards($filterValues)
	{
		$data = array();
		// check max value & determine interval
		if($filterValues['max'] <=30)
		{
			$data['interval'] = 1;
		}else if($filterValues['max'] <=50){
			$data['interval'] = 30;
		}else if($filterValues['max'] <=500){
			$data['interval'] = 50;
		}else{
			$data['interval'] = 500;
		}
		$filterValues['min'] = ($filterValues['min'] == -1?0:$filterValues['min']);
                $filterValues['max'] = ($filterValues['max'] == -1?0:$filterValues['max']);
		if($filterValues['min']%$data['interval'] > 0)
		{
			$filterValues['min'] -= $filterValues['min']%$data['interval'];
		}
		if($filterValues['max']%$data['interval'] > 0)
		{
			$filterValues['max'] += ($data['interval'] -($filterValues['max']%$data['interval']));
		}
		$data['max'] = $filterValues['max'];
		$data['min'] = $filterValues['min'];
		$data['maxLabel'] = ($data['max'] > 0 ? moneyFormatIndia($data['max']):$data['max']);
		$data['minLabel'] = ($data['min'] > 0 ? moneyFormatIndia($data['min']):$data['min']);
		return $data;
	}
	/*
	 * function to process deadlines
	 * Note : for sliders we need continuous values so we convert dates to months in sequence numbers 
	 */
	private function _processDeadlines($filterValues)
	{
		$data = array();
		$data['interval']=1; //one month
		
		$minDate = date_create_from_format('Y-m-d',$filterValues['min']);
		$minDate->setDate($minDate->format('Y'),$minDate->format('m'),1);
		$data['min'] = intval($minDate->format('m'));
		$data['minLabel'] =$minDate->format('M')." ".$minDate->format('Y');
		$maxDate = date_create_from_format('Y-m-d',$filterValues['max']);
		$maxDate->setDate($maxDate->format('Y'),$maxDate->format('m'),1);

		$interval = $maxDate->diff($minDate);
		$diffInMonths = $interval->format('%m')+($interval->format('%y')*12);
		$data['max'] = intval($minDate->format('m')) + $diffInMonths;
		$data['maxLabel'] =$maxDate->format('M')." ".$maxDate->format('Y');
		
		$data['baseYear'] = $minDate->format('Y');//."-".$minDate->format('m')."-01";
		return $data;
	}
	/*
	 * function to process sch amount
	 */
	private function _processScholarshipAmount($filterValues)
	{
		$data = array();
		$data['interval'] = 50000;
		$scale = 100000;
                $data['scale'] = $scale;
		if($filterValues['min']%$data['interval'] > 0)
		{
			$filterValues['min'] -= $filterValues['min']%$data['interval'];
		}
		if($filterValues['max']%$data['interval'] > 0)
		{
			$filterValues['max'] += ($data['interval'] -($filterValues['max']%$data['interval']));
		}
		$data['max'] = $filterValues['max'];
		$data['min'] = $filterValues['min'];
		$data['maxLabel'] = $this->_createLabelForAmountSlider($filterValues['max'],$scale);
		$data['minLabel'] = $this->_createLabelForAmountSlider($filterValues['min'],$scale);
		return $data;
	}
	/*
	 * Note : creates one label at a time
	 * amount in numeric value needs to be passed
	 * operates with lakhs & crores
	 */
	private function _createLabelForAmountSlider($amount, $scale=100000)
	{
		$label = "";
		if(intval($amount/$scale) >= 100)
		{
			$scale = 10000000;
			$unit = " Cr";
		}else{
			$unit = " L";
		}
		if(($amount%$scale)>0){
			$label = sprintf("%.1f",round(($amount/$scale),1));
		}else{
			$label = sprintf("%d",($amount/$scale));
		}
		// apply currency prefix & unit suffix
		$label = "Rs ".$label.$unit;
		return $label;
	}
	/*
	 * collects & formats applied filters for top bar
	 */
	private function _formatAppliedFilters($scholarshipCategoryPageRequest,$processedFilterData)
	{
		$appliedFilters = $scholarshipCategoryPageRequest->getAppliedFilters();
		if(count($appliedFilters)==0)
		{
			return array();
		}
		$formattedAppliedFilters = array();
		foreach($appliedFilters as $filterName => $filterValues)
		{
			switch($filterName)
			{
				case 'saScholarshipCourseLevel' :
					$fullList = $processedFilterData[$filterName."_parent"];
					foreach($appliedFilters[$filterName] as $id)
					{
						if(!is_null($fullList[ucfirst($id)])){
							$formattedAppliedFilters[$filterName][ucfirst($id)] = $fullList[ucfirst($id)];
						}
					}
					break;
				case 'saScholarshipUnivId' :
					$fullUnivList = $processedFilterData['saScholarshipUnivIdNameMap_parent'];
					foreach($appliedFilters[$filterName] as $univId)
					{
						if(!is_null($fullUnivList[$univId])){
							$formattedAppliedFilters[$filterName][$univId] = $fullUnivList[$univId];
						}
					}
					break;
				case 'saScholarshipAmount' :
					$data = array();
					$data['max'] = $filterValues[1];
					$data['min'] = $filterValues[0];
					$data['maxLabel'] = $this->_createLabelForAmountSlider($filterValues[1]);
					$data['minLabel'] = $this->_createLabelForAmountSlider($filterValues[0]);
					$formattedAppliedFilters[$filterName] = $data;
					break;
				case 'saScholarshipAwardsCount' :
					$data = array();
					$data['max'] = $filterValues[1];
					$data['min'] = $filterValues[0];
					$data['maxLabel'] = ($filterValues[1]>0?moneyFormatIndia($filterValues[1]):($filterValues[1]));
					$data['minLabel'] = ($filterValues[0]>0?moneyFormatIndia($filterValues[0]):($filterValues[0]));
					$formattedAppliedFilters[$filterName] = $data;
					break;
				case 'saScholarshipApplicationEndDate' :
					$fullDeadlineRangeSet = $processedFilterData['saScholarshipApplicationEndDate'];
					// now we use this to get base date using which we find month num in terms of sequence from applied filter
					$baseDate = $fullDeadlineRangeSet['baseYear']."-".$fullDeadlineRangeSet['min'];
					$baseDate = date_create_from_format('Y-m-d',$baseDate."-01");
					// get labels for these applied dates
					$min = explode('T',$filterValues[0]);
					$max = explode('T',$filterValues[1]);
					$data = $this->_processDeadlines(array('min'=>$min[0],'max'=>$max[0]));
					// get date obj for both min & max applied dates
					$minDate = date_create_from_format('Y-m-d',$min[0]);
					$maxDate = date_create_from_format('Y-m-d',$max[0]);
					// get diff from baseDate in terms of months for minDate
					$minDate->setDate($minDate->format('Y'),$minDate->format('m'),1);
					$minInterval = $minDate->diff($baseDate);
					// if invert  is 1 that means difference is negative (base date is greater han min date)
					if($minInterval->invert == 1)
					{
						$minDiffInMonths = $minInterval->format('%m')+($minInterval->format('%y')*12);
						$data['min'] = $fullDeadlineRangeSet['min'] + $minDiffInMonths;
					}else{
						// then base date is the min date
						$data['min'] = $fullDeadlineRangeSet['min'];
					}
					// get diff from baseDate in terms of months for maxDate
					$maxDate->setDate($maxDate->format('Y'),$maxDate->format('m'),1);
					$endDay = date('t', mktime(0, 0, 0, $maxDate->format("m"), 1, $maxDate->format("Y")));
					$maxDate->setDate($maxDate->format('Y'),$maxDate->format('m'),1);
					$maxInterval = $maxDate->diff($baseDate); // max date will always be greater than base date, so final max wont be decide by this check
					$maxDiffInMonths = $maxInterval->format('%m')+($maxInterval->format('%y')*12);
					$data['max'] = $fullDeadlineRangeSet['min'] + $maxDiffInMonths;
					// if base max is less use that as final applied max else use current max
					if($data['max'] > $fullDeadlineRangeSet['max'])
					{
						$data['max'] = $fullDeadlineRangeSet;
					}
					$formattedAppliedFilters[$filterName] = $data;
					break;
				default :
					$fullList = $processedFilterData[$filterName."_parent"];
					foreach($appliedFilters[$filterName] as $id)
					{
						if(!is_null($fullList[$id])){
							$formattedAppliedFilters[$filterName][$id] = $fullList[$id];
						}
					}
					break;
			}
		}
		return $formattedAppliedFilters;
	}
}