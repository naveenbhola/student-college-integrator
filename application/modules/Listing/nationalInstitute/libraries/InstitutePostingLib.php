<?php

class InstitutePostingLib{
	function __construct() {
		$this->CI =& get_instance();
		
		//load model
		$this->institutepostingmodel = $this->CI->load->model('nationalInstitute/institutepostingmodel');
		$this->baseAttributeLib = $this->CI->load->library('listingBase/BaseAttributeLibrary');
	}

	public function getFacilitiesConfigData(){
		$data = array();
		$masterFacilites = $this->institutepostingmodel->getFacilitiesConfigData();
		foreach ($masterFacilites as $key => $fac) {
			$data[$fac['id']]                  = $this->_fillfacilitiesData($fac);
			if($fac['ask_type'] == 'select'){
				$facSelectData = $this->baseAttributeLib->getValuesForAttributeByName($fac['name']);
				foreach ($facSelectData[$fac['name']] as $facSelectId => $facSelectValue) {
					$temp['label'] = $facSelectValue;
					$temp['value'] = $facSelectId;
					$data[$fac['id']]['select_values'][] = $temp;
				}
			}				
			$children = $this->institutepostingmodel->getFacilitiesConfigData($fac['id']);
			if($children){
				foreach ($children as $key => $facChild) {
					$data[$fac['id']]['children'][$facChild['id']] = $this->_fillfacilitiesData($facChild);
					$data[$fac['id']]['children'][$facChild['id']]['custom_fields'] = '';

					if($fac['id'] == 3 && in_array($facChild['id'], array(4,5,6))){ // for hostel
						$data[$fac['id']]['children'][$facChild['id']]['custom_fields'] = array(
							1 => array('id'=>1,'name'=>'Number of Rooms','display_type' => 'textbox','value'=>''),
							2 => array('id'=>2,'name'=>'Number of beds','display_type'=>'textbox','value'=>''),
							3 => array('id'=>3,'name'=>'Single Occupancy','display_type'=>'checkbox','value'=>''),
							4 => array('id'=>4,'name'=>'Shared Rooms','display_type'=>'checkbox','value'=>''),
							5 => array('id'=>5,'name'=>'In-Campus Hostel','display_type'=>'checkbox','value'=>''),
							6 => array('id'=>6,'name'=>'Off-Campus Hostel','display_type'=>'checkbox','value'=>'')
							);
					}

					if($facChild['ask_type'] == 'select'){
						$facSelectData = $this->baseAttributeLib->getValuesForAttributeByName($facChild['name']);
						foreach ($facSelectData[$facChild['name']] as $facSelectId => $facSelectValue) {
							$temp['label'] = $facSelectValue;
							$temp['value'] = $facSelectId;
							$data[$fac['id']]['children'][$facChild['id']]['select_values'][] = $temp;
						}
					}
				}	
			}else{
				$data[$fac['id']]['children'] = array();
			}
		}

		return $data;
	}

	public function getListingTagsData(){
		$data = array();
		$listingTagsData = $this->institutepostingmodel->getListingTags();
		foreach ($listingTagsData as $key => $value) {
			$data[] = array('value'=> $value['id'],'label'=>$value['tags']);
		}
		return $data;
	}

	public function getCompaniesData($keyword){
		ini_set('memory_limit', '512M');
		$this->CI->load->library('enterprise_client');
		
		$data = array();

		$calObj = new Enterprise_client();
        $countComLogo=$calObj->getCompanyLogo($keyword,-1);
        
        foreach ($countComLogo as $key => $value) {
        	$data[$key]['label'] = $value['company_name']; 
        	$data[$key]['value'] = $value['id'];
        }

        return $data;
	}

	private function _fillfacilitiesData($fac){
		$data = array();
		$data['id']           = $fac['id'];
		$data['name']         = $fac['name'];
		$data['display_type'] = $fac['ask_type'];
		$data['order']        = $fac['order'];
		$data['ask_desc']     = $fac['ask_details'];
		return $data;
	}

	function prepareDynamicAttribute(){
		$data = array();		
		$dynamicAttributeList = array('Faculty Type'=>'faculty_type','Event Type'=>'event_type','Scholarship Type' => 'scholarship_type');
		$instituteAttrubuteList = $this->baseAttributeLib->getValuesForAttributeByName(array_keys($dynamicAttributeList));
		foreach ($instituteAttrubuteList as $attributeName => $attribute) {						
			$temp = array();
			foreach ($attribute as $key => $value) {
				$data[$dynamicAttributeList[$attributeName]][] = array('value'=>$key,'label'=>$value);
			}			 
		}

		return $data;

	}

	/**
	 * [getInstituteParentHierarchy this function will fetch hierarchy tree from DB recursively]
	 * @author Ankit Garg <g.ankit@shiksha.com>
	 * @date   2016-07-18
	 * @param  [type]     $id   [listing type id (Institute/ University)]
	 * @param  [type]     $type [listing type (Institute/ University)]
	 * @param  [type]	  $typeOfListingPosting [listing posting as Regular/Dummy ] value true means dummy posting and false means regular posting
	 * @return [type]           [description]
	 */
	function getInstituteParentHierarchy($id, $type, $institutePostingType,$typeOfListingPosting, $excludeInstituteFromHierarchy='') {
		$data = $this->institutepostingmodel->getInstituteParentHierarchy($id, $type);
		return $this->prepareInstituteParentHierarchyTree($data['parentData'], $data['childData'], $data['instituteMappingData'], $data['universityMappingData'], $institutePostingType,$typeOfListingPosting, $excludeInstituteFromHierarchy);
	}
	
	private function prepareInstituteParentHierarchyTree($parentData, $childData, $instituteMappingData, $universityMappingData, $institutePostingType,$typeOfListingPosting, $excludeInstituteFromHierarchy='') {

		//handling case if requested is same with the one being posted
		// _p($childData[0][0]);
		// var_dump($instituteMappingData['idTypeArr']);
		//  _p($instituteMappingData['idTypeArr'][$childData[0][0]]); 
		//  _p($institutePostingType);
		//  die;
		if(!empty($childData[0]) && $instituteMappingData['idInstitute_specification_typeArr'][$childData[0][0]] === $institutePostingType && $typeOfListingPosting == 'false' && empty($instituteMappingData['idIs_dummyArr'])) {
			return array();
		}
		// _p($parentData); 
		$length = 0;
		$matched = false;
		foreach($parentData as $val) {
			++$length;
			if(($instituteMappingData['idInstitute_specification_typeArr'][$val] == $institutePostingType && !$instituteMappingData['idIs_satelliteArr'][$val]) && !empty($institutePostingType) && $typeOfListingPosting == 'false' && empty($instituteMappingData['idIs_dummyArr'][$val])) {
				$matched = true;
				break;
			}
		}
		$parentDataLength = count($parentData);
		// echo $parentDataLength.'==='.$length;
		$finalParentData = $parentData;
		if($matched) {
			return array();
			/*$length = ($parentDataLength == $length) ? $length-- : $length;
			$finalParentData = array();
			for($i=$length; $i<count($parentData);$i++) {
				$finalParentData[] = $parentData[$i];
			}*/

		}
		else
		{
			$hierarchyArr = array();
			foreach($childData as $child) {
				$hierarchyArr[] = array_merge($finalParentData, $child);
			}	
		}

		
		
		// _p($universityMappingData);
		// _p($finalParentData);
		$finalHierarchyData = array();
		$keyIndex = 0;
		$notAllowUniversitySelection = false;
		foreach($hierarchyArr as $key=>$hierarchy) {
			// $skip = false;
			foreach($hierarchy as $id) {
				$name = '';
				$type = '';
				if($instituteMappingData['idNameArr'][$id]) {
					$name = $instituteMappingData['idNameArr'][$id];
				}
				else if($universityMappingData['idNameArr'][$id]) {
					$name = $universityMappingData['idNameArr'][$id];
					$type = 'university';
				}

				if($instituteMappingData['idInstitute_specification_typeArr'][$id]) {
					$type = $instituteMappingData['idInstitute_specification_typeArr'][$id];
				}
				
				$isDummy = '';
				if($instituteMappingData['idIs_dummyArr'][$id]) {
					$isDummy = $instituteMappingData['idIs_dummyArr'][$id];
				}
				else if($universityMappingData['idIs_dummyArr'][$id]) {
					$isDummy = $universityMappingData['idIs_dummyArr'][$id];
				}
				$is_satellite = "";
				if($instituteMappingData['idIs_satelliteArr'][$id]) {
					$is_satellite = $instituteMappingData['idIs_satelliteArr'][$id];
				}
				else if($universityMappingData['idIs_satelliteArr'][$id]) {
					$is_satellite = $universityMappingData['idIs_satelliteArr'][$id];
				}
				
				$disabled_url = '';
				if($instituteMappingData['idDisabled_urlArr'][$id])
				{
					$disabled_url = $instituteMappingData['idDisabled_urlArr'][$id];
				}
				elseif($universityMappingData['idDisabled_urlArr'][$id])
				{
					$disabled_url = $universityMappingData['idDisabled_urlArr'][$id];
				}


				if($name != '') {

					//this condition is used for satellite campus option to filter out university selection as parent
					if($type == 'university')		
					{
						$notAllowUniversitySelection = true;
					}
					else
					{
						$notAllowUniversitySelection = false;
					}

					if(($type == $institutePostingType && !$is_satellite) && !empty($institutePostingType) && $typeOfListingPosting == 'false' && empty($isDummy))
					{
						unset($finalHierarchyData[$keyIndex--]);
						break;
					}
					else
					{
						$finalHierarchyData[$keyIndex][] = array('id' => $id, 'name' => $name, 'type' => $type,'is_dummy' => $isDummy,'disabled_url' => $disabled_url,'is_satellite' => $is_satellite);
					}
		
				}
			}
			if($institutePostingType == 'satellite' && (($type == 'university' && $notAllowUniversitySelection) || !empty($is_satellite)))
			{
				unset($finalHierarchyData[$keyIndex--]);
			}
			$keyIndex++;
		}

		if($excludeInstituteFromHierarchy){
			foreach ($finalHierarchyData as $key => $hierarchy) {
				foreach ($hierarchy as $value) {
					if($value['id'] == 'institute_'.$excludeInstituteFromHierarchy){
						unset($finalHierarchyData[$key]);
					}
				}
			}
		}

		return $finalHierarchyData; 
	}

	function getInstitutePostingComments($instituteId,$listingType){

		if(empty($instituteId)){
			return array();
		}

		$data = $this->institutepostingmodel->getInstitutePostingComments($instituteId,$listingType);

		$commentData = array();
		foreach ($data as $key=>$row) {
			$tempData = array();
			$tempData['name'] = $row['firstname']." ".$row['lastname'];
			$tempData['name'] = ucwords(trim($tempData['name']));
			$tempData['comment'] = $row['comments'];
			$tempData['userId']  = $row['userId'];
			$tempData['addTime'] = date("F j, Y, g:i a", strtotime($row['updatedAt']));
			$tempData['key']     = $key+1;

			$commentData[] = $tempData;
		}

		return $commentData;
	}

	function checkForInstituteDataUpdated($instituteObj, $oldInstituteObj){

		$this->CI->load->config("nationalInstitute/instituteStaticAttributeConfig");
		$instituteFieldsToSectionMapping = $this->CI->config->item("instituteFieldsToSectionMapping");

		$sectionsUpdated = array();
		foreach ($instituteFieldsToSectionMapping as $instituteFieldName => $sectionName) {
			
			if($instituteObj[$instituteFieldName] != $oldInstituteObj[$instituteFieldName]){
				$sectionsUpdated[] = $sectionName;
				error_log("field changed  : ".$instituteFieldName);
			}
		}

		$sectionsUpdated = array_unique($sectionsUpdated);
		return $sectionsUpdated;
	}

	function getInstituteParentHierarchyFromFlatTale($instituteIds){
		if(empty($instituteIds)) return;
    	$result = $this->institutepostingmodel->getInstituteParentHierarchyFromFlatTable($instituteIds);
    	$finalResult = array();
    	foreach ($result as $key => $value) {
    		$temp = array();
    		$temp['id'] = $value['listing_type']."_".$value['hierarchy_parent_id'];
    		$temp['name'] = $value['name'];
    		$temp['type'] = $value['listing_type'];
    		$temp['disabled_url'] = $value['disabled_url'];
    		$temp['listing_id'] = $value['hierarchy_parent_id'];
    		$temp['is_dummy'] = $value['is_dummy'];
    		$temp['is_satellite'] = $value['is_satellite'];
    		
    		if($value['listing_type'] == "university"){
    			$temp['is_aiu_membership'] = $value['is_aiu_membership'];
	    		$temp['is_open_university'] = $value['is_open_university'];
	    		$temp['is_ugc_approved'] = $value['is_ugc_approved'];
    			$temp['university_specification_type'] = $value['university_specification_type'];
    		}else{
    			$temp['type'] = $value['institute_specification_type'];
    			$temp['is_autonomous'] = $value['is_autonomous'];
    		}
    		
    		
    		$temp['ownership'] = $value['ownership'];
    		$temp['accreditation'] = $value['accreditation'];

    		$finalResult[$value['primary_parent_id']][] = $temp;
    	}
    	
    	return $finalResult;
         
    }

	function getParentHierarchyById($parent_listing_id,$parent_listing_type){
			$parentHierarichyDetails = array();				
			$this->institutepostingmodel->parentCount = 0;
			$parentList = $this->institutepostingmodel->getParentData($parent_listing_id,$parent_listing_type);
			$parentList[] =  $parent_listing_type.'_'.$parent_listing_id;

			$parentListMapping = $this->institutepostingmodel->processIdsForHierarchy($parentList);

			if(!empty($parentListMapping['institute'])) {
            	$instituteMappingData = $this->institutepostingmodel->getInstituteNamesById($parentListMapping['institute'],array('name', 'institute_specification_type','is_dummy','is_satellite','disabled_url','is_autonomous','ownership','accreditation'),true);
        	}
        	if(!empty($parentListMapping['university'])) {
            	$universityMappingData = $this->institutepostingmodel->getUniversityDataById($parentListMapping['university'],array('name','disabled_url','is_aiu_membership','is_dummy','is_satellite','is_open_university','is_ugc_approved','university_specification_type','ownership','accreditation'),true);
        	}
        	foreach ($universityMappingData as $keyName => $keyValue) {
	        		$indexNumber = array_search('university_'.$keyValue['listing_id'],$parentList);
	        		$parentHierarichyDetails[$indexNumber]['id'] = 'university_'.$keyValue['listing_id'];
	        		$parentHierarichyDetails[$indexNumber]['name'] = $keyValue['name'];
	        		$parentHierarichyDetails[$indexNumber]['type'] = 'university';
	        		$parentHierarichyDetails[$indexNumber]['disabled_url'] = $keyValue['disabled_url'];
	        		$parentHierarichyDetails[$indexNumber]['listing_id'] = $keyValue['listing_id'];
	        		$parentHierarichyDetails[$indexNumber]['is_dummy'] = $keyValue['is_dummy'];
	        		$parentHierarichyDetails[$indexNumber]['is_satellite'] = $keyValue['is_satellite'];
	        		$parentHierarichyDetails[$indexNumber]['is_aiu_membership'] = $keyValue['is_aiu_membership'];
	        		$parentHierarichyDetails[$indexNumber]['is_open_university'] = $keyValue['is_open_university'];
	        		$parentHierarichyDetails[$indexNumber]['is_ugc_approved'] = $keyValue['is_ugc_approved'];
	        		$parentHierarichyDetails[$indexNumber]['university_specification_type'] = $keyValue['university_specification_type'];
	        		$parentHierarichyDetails[$indexNumber]['ownership'] = $keyValue['ownership'];
	        		$parentHierarichyDetails[$indexNumber]['accreditation'] = $keyValue['accreditation'];
        	}
        	foreach ($instituteMappingData as $keyName => $keyValue) {
        		$indexNumber = array_search('institute_'.$keyValue['listing_id'],$parentList);
        		$parentHierarichyDetails[$indexNumber]['id'] = 'institute_'.$keyValue['listing_id'];
        		$parentHierarichyDetails[$indexNumber]['name'] = $keyValue['name'];
        		$parentHierarichyDetails[$indexNumber]['type'] = $keyValue['institute_specification_type'];
        		$parentHierarichyDetails[$indexNumber]['is_dummy'] = $keyValue['is_dummy'];
        		$parentHierarichyDetails[$indexNumber]['is_satellite'] = $keyValue['is_satellite'];
        		$parentHierarichyDetails[$indexNumber]['disabled_url'] = $keyValue['disabled_url'];
        		$parentHierarichyDetails[$indexNumber]['listing_id'] = $keyValue['listing_id'];
        		$parentHierarichyDetails[$indexNumber]['is_autonomous'] = $keyValue['is_autonomous'];
        		$parentHierarichyDetails[$indexNumber]['ownership'] = $keyValue['ownership'];
        		$parentHierarichyDetails[$indexNumber]['accreditation'] = $keyValue['accreditation'];
        	}

        return $parentHierarichyDetails;
	}

	function migrateDeletedInstituteData($deleted_listing_id,$deleted_new_listing_id,$listingType='institute', $dbHandle) {
     
        if(empty($deleted_new_listing_id)) {
            $deleted_new_listing_id = 0;
        }
       
        $result_string = $this->institutepostingmodel->insertUpdatedListingData($deleted_listing_id,$deleted_new_listing_id,$listingType, $dbHandle);
       
        return $result_string;
  	}
  	function getUniversityParentHierarchy($universityId,$type = 'university', $excludeInstituteFromHierarchy = '')
  	{
  		$result = $this->institutepostingmodel->getUniversityParentHierarchy($universityId);

  		if(empty($result))
  			return array();

  		if($result['idNameArr'][$type.'_'.$universityId]) {
			$name = $result['idNameArr'][$type.'_'.$universityId];
			$type = 'university';
		}
		$finalHierarchyData = array();

		$finalHierarchyData[0][] = array('id' => $type.'_'.$universityId, 'name' => $name, 'type' => $type);
		if(!empty($excludeInstituteFromHierarchy) && $excludeInstituteFromHierarchy == $universityId){
			$finalHierarchyData = array();
		}

		return $finalHierarchyData;
  	}

  	function getInstituteNamesById($ids, $returnData = array('name', 'institute_specification_type','is_dummy','is_satellite','disabled_url'),$returnNormal=false,$statusCheck = true) {
		return $this->institutepostingmodel->getInstituteNamesById($ids, $returnData ,$returnNormal,$statusCheck);
  	}

  	function getUniversityNamesById($ids,$returnData = array('name','disabled_url'),$returnNormal = false){
  		return $this->institutepostingmodel->getUniversityDataById($ids, $returnData ,$returnNormal);
  	}

  	function migrateUGCModules($listingIds,$newListingId){
  		if(empty($listingIds)){
  			return 'Old Listing is Not Valid,not able to Migrate / Delete Article Mapping.';
  		}
  		$listingIds = explode(',', $listingIds);
  		$response = array();
  		//migrate articles to the new listing or delete article mapping(set status deleted)
  		$this->articleUtilityLib = $this->CI->load->library('article/ArticleUtilityLib');
  		$response[] = $this->articleUtilityLib->migrateOrDeleteArticleMapping($listingIds,$newListingId);

  		//migrate exams to the new listing or delete exam mapping(set status deleted)
  		$this->examPostingLib = $this->CI->load->library('examPages/ExamPostingLib');
  		$response[] = $this->examPostingLib->migrateOrDeleteExamMapping($listingIds,$newListingId);

  		//migrate CR to the new listing or delete CR mapping(set status deleted)
  		$this->CcbaseLib = $this->CI->load->library('CA/CcBaseLib');
  		$response[] = $this->CcbaseLib->updateCRInstId($listingIds,$newListingId);
  		return $response;
  	}
}
