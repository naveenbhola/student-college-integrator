<?php

class institutepostingmodel extends MY_Model {
    var $parentCount = 0;
	function __construct() {
		parent::__construct('Listing');
        $this->load->config('nationalInstitute/instituteStaticAttributeConfig');
        $this->load->config('nationalInstitute/instituteSectionConfig');
    }

    private function initiateModel($mode = "write") {
		if($this->dbHandle && $this->dbHandleMode == 'write') {
		    return;
		}
		
		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }


    public function getFacilitiesConfigData($parentId = 0) {
    	$this->initiateModel();
    	$query = "select * from shiksha_institutes_facilities_master where parent_id = ? and listing_type='institute' order by `order`";
    	$result = $this->dbHandle->query($query,array($parentId))->result_array();
    	return $result;
    }

    public function getChildFacilities(){
        $this->initiateModel();
        $query = "select * from shiksha_institutes_facilities_master where parent_id > 0 and listing_type='institute' order by `order`";
        $data =  $this->dbHandle->query($query)->result_array();
        $returnData = array();
        foreach($data as $row){
            $returnData[$row['parent_id']][] = $row['id'];
        }
        return $returnData;
    }

    public function getListingTags(){
		$this->initiateModel();
    	$query = "select * from listing_tags where status = ?";
    	$result = $this->dbHandle->query($query,array('live'))->result_array();
    	return $result;    	
    }

    function getInstituteParentHierarchy($id, $type) {

        Contract::mustBeNumericValue($id, 'Listing ID (Institute/University)');
        Contract::mustBeNonEmptyVariable($type, 'Listing Type (institute/University)');

    	$this->initiateModel();
    	$parentData = $this->getParentData($id, $type);
        // _p($parentData); die;
        foreach($parentData as $key=>$typeId) {
            if($typeId == $type."_".$id) {
                unset($parentData[$key]);
            }
        }
        $childData = $this->getChildData($id, $type);
        if(count($childData) == 1 && !is_array($childData[0])) {
            $childData = array($childData);
        }
        // _p($childData);
        $data = $this->processIdsForHierarchy($parentData, $childData);
        // _p($data);  die;
        // _p($childData);  die;
        $instituteMappingData = array();
        if(!empty($data['institute'])) {
            $instituteMappingData = $this->getInstituteNamesById($data['institute']);
        }
        if(!empty($data['university'])) {
            $universityMappingData = $this->getUniversityDataById($data['university']);
        }
        // _p($universityMappingData);  die;
 		return array('childData' =>  $childData, 
 					 'parentData' => $parentData, 
 					 'instituteMappingData' => $instituteMappingData,
                     'universityMappingData'=> $universityMappingData);
    }

    function getChildTree($instituteId,$statusCheck,$excludeSatelliteInstitutes= false,$excludeInstituteTypes = array(), $excludeInstituteTypesChildren = array(), $instituteSpecificationType = "") {

        if(in_array($instituteSpecificationType, $excludeInstituteTypesChildren)){
                return array('institute_'.$instituteId);
        }
        $this->dbHandle->select('listing_id as institute_id,institute_specification_type');
        $this->dbHandle->where('parent_listing_id',$instituteId);
        $this->dbHandle->where('parent_listing_type','institute');
        if(!$statusCheck){
            $this->dbHandle->where_not_in('status',array('history','deleted'));
        }
        else{
            $this->dbHandle->where('status','live');
        }
        if($excludeSatelliteInstitutes){
            $this->dbHandle->where('ISNULL(is_satellite)',NULL,false);
        }
        if(is_array($excludeInstituteTypes) && !empty($excludeInstituteTypes)){
            $this->dbHandle->where_not_in('institute_specification_type',$excludeInstituteTypes);
        }
        $data = $this->dbHandle->get('shiksha_institutes')->result_array();
        
        $dataMapping = array();
        foreach ($data as $dataRow) {
            $dataMapping[$dataRow['institute_id']] = $dataRow;
        }
    	$childInstituteIds = $this->getColumnArray($data, 'institute_id');
    	if(empty($childInstituteIds)) {
    		return array('institute_'.$instituteId);
    	}
    	foreach($childInstituteIds as $id) {    		
                $childNode[] = $this->getChildTree($id,$statusCheck,$excludeSatelliteInstitutes,$excludeInstituteTypes,$excludeInstituteTypesChildren, $dataMapping[$id]['institute_specification_type']);    
    	}
    	return $this->generateChildPermutation($childNode, $instituteId);
    }


    function generateChildPermutation($childNode, $instituteId, $type = 'institute') {
    	$arr = array();
    	$arr[] = array($type."_".$instituteId);
    	foreach($childNode as $node) {
    		foreach($node as $id) {
    			$arr[] = array_merge(array($type."_".$instituteId),(array) $id);
    		}
    	}
    	return $arr;
    }

    function getChildData($id, $type,$statusCheck=true,$excludeSatelliteInstitutes= false, $excludeInstituteTypes=array(), $excludeInstituteTypesChildren = array(), $instituteSpecificationType = "") {
        static $universityLoopCount = 0;
        $this->initiateModel();

    	if($type == 'university') {
            $universityLoopCount++;
            if($excludeSatelliteInstitutes && $universityLoopCount > 1){
                return;
            }
            else{
                $this->dbHandle->select('listing_id as id, listing_type as type, institute_specification_type');
                $this->dbHandle->where('parent_listing_id',$id);
                $this->dbHandle->where('parent_listing_type',$type);
                if($statusCheck){
                    $this->dbHandle->where('status','live');
                }
                else{
                    $this->dbHandle->where_not_in('status',array('history','deleted'));
                }
                if(is_array($excludeInstituteTypes) && !empty($excludeInstituteTypes)){
                    $this->dbHandle->where_not_in('institute_specification_type',$excludeInstituteTypes);
                }
                $results = $this->dbHandle->get('shiksha_institutes')->result_array();

                $childUniversityNode = array();
                foreach ($results as $key => $value) {

                        $childUniversityNode[] = $this->getChildData($value['id'], $value['type'],$statusCheck, $excludeSatelliteInstitutes, $excludeInstituteTypes, $excludeInstituteTypesChildren, $value['institute_specification_type']); 
                }
            }
            $universityLoopCount = 0;
    	}
    	else {
    		$instituteIds = array($id);
    	}
    	$descendatsArr = array();
    	foreach ($instituteIds as $instituteId) {
    		$descendatsArr[] = $this->getChildTree($instituteId,$statusCheck,$excludeSatelliteInstitutes, $excludeInstituteTypes, $excludeInstituteTypesChildren, $instituteSpecificationType);
    	}
        if(!empty($childUniversityNode))
        {
            foreach ($childUniversityNode as $childKey => $childValue) {
                $descendatsArr[] = $childValue;
            }
        }
        if($type == 'university') {
    		return $this->generateChildPermutation($descendatsArr, $id, 'university');
    	}
    	else {
    		return reset($descendatsArr);
    	}
    }

    function getParentData($id, $type = 'institute') {
        $this->initiateModel();
    	
    	$fields = array("listing_id as id", "name", "parent_listing_id", "parent_listing_type");
		switch($type) {
			case 'institute': 
			array_push($fields, 'institute_specification_type as type');
			break;
		case 'university':
			array_push($fields, "listing_type as type");
			break;
		}
        $this->dbHandle->select($fields);
        $this->dbHandle->where(array('listing_id' => $id, 'status' => 'live', 'listing_type' => $type));
        $parents = $this->dbHandle->get('shiksha_institutes')->result_array();

		if($this->parentCount == 0) {
			$arr = array();
		}
		else {
			$arr[] = $type."_".$id;
		}
		$this->parentCount++;
		foreach($parents as $parent) {
            if($parent['parent_listing_type'] && $this->parentCount <= 10) {
			   return array_merge($this->getParentData($parent['parent_listing_id'], $parent['parent_listing_type']), $arr);
            }
            else {
                return array($type."_".$id);
            }
		}
    }

    /**
     * [getInstituteNamesById this will return id name pair]
     * @author Ankit Garg <g.ankit@shiksha.com>
     * @date   2016-07-19
     * @param  [type]     $instituteIds [description]
     * @param  array      $returnData   [description]
     * @return [type]                   [description]
     */
    function getInstituteNamesById($instituteIds, $returnData = array('name', 'institute_specification_type','is_dummy','is_satellite','disabled_url'),$returnNormal=false,$statusCheck = true, $listing_type = 'institute') {
        Contract::mustBeNonEmptyArrayOfIntegerValues($instituteIds, 'Institute Ids');
        Contract::mustBeNonEmptyArray($returnData, 'return array');
        $this->initiateModel();
        if($statusCheck)
            $status = array('live');
        else 
            $status = array('live','draft');

    	$this->dbHandle->select('listing_id,'.implode(', ', $returnData))->where_in('listing_id', $instituteIds)->where_in('status',$status)->where('listing_type',$listing_type);
    	$instituteData = $this->dbHandle->get('shiksha_institutes')->result_array();

    	if($returnNormal)
            return $instituteData;

    	foreach($instituteData as $institute) {
            foreach($returnData as $val) {
                $variableName = 'id'.ucfirst($val).'Arr';
                $variableNameArr[$variableName] = $variableName;
                if($listing_type == 'university'){
                    ${$variableName}["university_".$institute['listing_id']] = $institute[$val];
                }else{
        		    ${$variableName}["institute_".$institute['listing_id']] = $institute[$val];
                }
            }
    	}
        $returnArr = array();
        foreach ($variableNameArr as $key => $variableName) {
            $returnArr[$key] = ${$variableName};
        }
        
        return $returnArr;
    }

    function getUniversityDataById($universityIds, $returnData = array('name','disabled_url','is_dummy','is_satellite'),$returnNormal = false) {
        Contract::mustBeNonEmptyArrayOfIntegerValues($universityIds, 'University Ids');
        Contract::mustBeNonEmptyArray($returnData, 'return array');
        $this->initiateModel();

        $this->dbHandle->select('listing_id,'.implode(', ', $returnData))->where_in('listing_id', $universityIds)->where(array('status' => 'live','listing_type'=>'university'));
        $universityData = $this->dbHandle->get('shiksha_institutes')->result_array();

        
        if($returnNormal)
            return $universityData;
        
        foreach($universityData as $university) {
            foreach($returnData as $val) {
                $variableName = 'id'.ucfirst($val).'Arr';
                $variableNameArr[$variableName] = $variableName;
                ${$variableName}["university_".$university['listing_id']] = $university[$val];
            }
        }
        $returnArr = array();
        foreach ($variableNameArr as $key => $variableName) {
            $returnArr[$key] = ${$variableName};
        }
        
        return $returnArr;
    }

    function processIdsForHierarchy($parentData, $childData) {
    	foreach($parentData as $typeId) {
    		if(preg_match('/institute/', $typeId)) {
    			$arr = explode('institute_', $typeId);
    			$idsData['institute'][$arr[1]] = $arr[1];
    		}
    		else {
    			$arr = explode('university_', $typeId);
    			$idsData['university'][$arr[1]] = $arr[1];    			
    		}
    	}
    	foreach($childData as $data) {
    		foreach($data as $typeId) {
	    		if(preg_match('/institute/', $typeId)) {
	    			$arr = explode('institute_', $typeId);
	    			$idsData['institute'][$arr[1]] = $arr[1];
	    		}
	    		else {
	    			$arr = explode('university_', $typeId);
	    			$idsData['university'][$arr[1]] = $arr[1];    			
	    		}
    		}
    	}
    	return $idsData;
    }

    public function saveInstitute($instituteData){
    	$status = $instituteData['statusFlag'];
    	$this->initiateModel('write');
    	$this->dbHandle->trans_start();
    	$flow = $instituteData['flow'];
        $postingListingType = $instituteData['postingListingType'];
        $listingMainStatus= $this->config->item("listingMainStatus");
    	if($instituteData['flow'] == 'add'){
    		$instituteId = $this->_getNewInstituteId();
    	}
    	else if($instituteData['flow'] == 'edit'){
    		$instituteId = $instituteData['instituteId'];
    	}

    	if(empty($instituteId)){
    		return array("error" => 1 , "errorMsg" => "CANNOT_FIND_INSTITUTE_ID");
    	}

    	$tablesToBeChanged = array();
    	if($instituteData['flow'] == 'edit'){
	    	global $instituteFieldSectionToTableMapping;
	    	foreach ($instituteData['sectionsUpdated'] as $updatedSection) {
	    		$tablesToBeChanged = array_merge($tablesToBeChanged, $instituteFieldSectionToTableMapping[$updatedSection]);
	    	}
	    	$tablesToBeChanged = array_unique($tablesToBeChanged);
	    }
       // _P($tablesToBeChanged); die;
    	if($instituteData['flow'] == 'edit'){
    		if($status == 'draft')
    			$fromStatus = array('draft');
    		else if($status == 'live')
    			$fromStatus = array('live','draft');
    		$this->updateInstituteData($instituteId, $tablesToBeChanged, $fromStatus, 'history',$postingListingType);

    		$this->_trackInstituteUpdatedSections($instituteId, $instituteData['userId'], $instituteData['sectionsUpdated'],$postingListingType);
    	}
    	// error_log("============= Sections Updated : ".print_r($instituteData['sectionsUpdated'], true));
    	// error_log("============= tablesToBeChanged : ".print_r($tablesToBeChanged, true));
    	
    	$tableName = 'shiksha_institutes';
    	$basicInfo = $instituteData['basicInfo'];
    	$basicInfo['listing_id'] = $instituteId;
        $basicInfo['updated_by'] = $instituteData['userId'];
        $basicInfo['updated_on'] =  date("Y-m-d H:i:s");
    	
    	$basicInfo['status']	 = $status;
    	$this->dbHandle->insert($tableName,$basicInfo);

    	$tableName = 'listings_main';
        $listingMainInfo = $instituteData['listingMainInfo'];
        $listingMainInfo['listing_type_id'] = $instituteId;
        $listingMainInfo['status'] = $listingMainStatus[$status];
		$listingMainInfo['last_modify_date'] = date("Y-m-d H:i:s");
        $this->dbHandle->insert($tableName,$listingMainInfo);

        //saving into institute_locations table
        $locations = $instituteData['locations'];
        $contact_details = array();
        $locationToInstLocationIdMapping =  array();
        foreach ($locations as $key => $value) {
           	$locations[$key]['listing_id'] = $instituteId;
           	$locations[$key]['listing_type'] = $postingListingType;
           	$locations[$key]['status']       = $status;

           	/*if(!$locations[$key]['listing_location_id']){

           		$locations[$key]['listing_location_id'] = $this->_getNewInstituteLocationId();
           	}*/

           	$locationKey = $locations[$key]['city_id']."_".$locations[$key]['locality_id'];
           	$locationToInstLocationIdMapping[$locationKey] = $locations[$key]['listing_location_id'];

           	$temp_contact_details							 = array();
           	$temp_contact_details['listing_id'] 			 = $instituteId;
           	$temp_contact_details['listing_type'] 			 = $postingListingType;
           	$temp_contact_details['listing_location_id'] 	 = $locations[$key]['listing_location_id'];
			$temp_contact_details['address'] 				 = $locations[$key]['contact_details']['address'];
			$temp_contact_details['website_url'] 			 = $locations[$key]['contact_details']['website_url'];
			$temp_contact_details['latitude'] 				 = $locations[$key]['contact_details']['latitude'];
			$temp_contact_details['longitude'] 				 = $locations[$key]['contact_details']['longitude'];
            if(empty($temp_contact_details['longitude']) || empty($temp_contact_details['latitude']))
            {
                $temp_contact_details['google_url']               = '';
            }
            else
            {
                $temp_contact_details['google_url']               = $locations[$key]['contact_details']['google_url'];
            }
            
			$temp_contact_details['admission_contact_number']= $locations[$key]['contact_details']['admission_contact_number'];
			$temp_contact_details['admission_email'] 		 = $locations[$key]['contact_details']['admission_email'];
			$temp_contact_details['generic_contact_number']  = $locations[$key]['contact_details']['generic_contact_number'];
			$temp_contact_details['generic_email'] 			 = $locations[$key]['contact_details']['generic_email'];
			$temp_contact_details['updated_by'] 			 = $locations[$key]['updated_by'];
			$temp_contact_details['status'] 			 	 = $status;

			unset($locations[$key]['contact_details']);

			$contact_details[]	= $temp_contact_details;
        }

        $tableName = 'shiksha_institutes_locations';
        if(count($locations) > 0){
        	foreach ($locations as $key => $value) {
        		$this->dbHandle->insert($tableName,$value);
        	}
        }

        $tableName = 'shiksha_listings_contacts';
        if(count($contact_details) > 0){
            $this->dbHandle->insert_batch($tableName,$contact_details);
        }

        //saving usp into shiksha_institutes_events table
        $events = $instituteData['events'];
        foreach ($events as $key => $value) {
           $events[$key]['listing_id'] = $instituteId;
           $events[$key]['listing_type'] = $postingListingType;
           // $events[$key]['event_id'] = $this->_getNewInstituteEventsId();
           $events[$key]['updated_by'] = $instituteData['userId'];
           $events[$key]['status'] = $status;
        }

        $this->eventIdentifierMapping = array();
        $tableName = 'shiksha_institutes_events';
        if(count($events) > 0){
            foreach ($events as $eventRow) {
                $randomIdentifier = $eventRow['randomIdentifier'];
                unset($eventRow['randomIdentifier']);
                $this->dbHandle->insert($tableName,$eventRow);
                $this->eventIdentifierMapping[$randomIdentifier] = $this->dbHandle->insert_id();
            }
            // error_log("eventIdentifierMapping : ".print_r($this->eventIdentifierMapping, true));
            // die;

        }

		//saving institute photos
        $institutePhotoData = $this->_prepareInstituteMediaData($instituteData, $status, $instituteId, 'institutePhotos', $locationToInstLocationIdMapping);

        $tableName = 'shiksha_institutes_medias';
        if(count($institutePhotoData['mainData']) > 0){
        	foreach ($institutePhotoData['mainData'] as $key => $photoMediaMainData) {
        		$mediaPhotoQuery = "update shiksha_institutes_medias set media_title = ?, media_order = ? where media_id = ? and media_type = 'photo'";
        		$this->dbHandle->query($mediaPhotoQuery, array($photoMediaMainData['media_title'], $photoMediaMainData['media_order'], $photoMediaMainData['media_id']));

                // update status
                $mediaPhotoQuery = "update shiksha_institutes_medias set status = ? where media_id = ? and media_type = 'photo' and status='draft'";
                $this->dbHandle->query($mediaPhotoQuery, array($status, $photoMediaMainData['media_id']));
        	}
        }
        
        $tableName = 'shiksha_institutes_media_locations_mapping';
        if(count($institutePhotoData['locationsData']) > 0){
            $this->dbHandle->insert_batch($tableName,$institutePhotoData['locationsData']);            
        }

        $tableName = 'shiksha_institutes_media_tags_mapping';
        if(count($institutePhotoData['tagsData']) > 0){
            $this->dbHandle->insert_batch($tableName,$institutePhotoData['tagsData']);    
        }
        
        //saving institute videos
        $instituteVideoData = $this->_prepareInstituteMediaData($instituteData, $status, $instituteId, 'instituteVideos', $locationToInstLocationIdMapping);

        $tableName = 'shiksha_institutes_medias';
        if(count($instituteVideoData['mainData']) > 0){
        	foreach ($instituteVideoData['mainData'] as $key => $videoMediaMainData) {
        		$mediaVideoQuery = "update shiksha_institutes_medias set media_title = ?, media_order = ? where media_id = ? and media_type = 'video'";
        		$this->dbHandle->query($mediaVideoQuery, array($videoMediaMainData['media_title'], $videoMediaMainData['media_order'], $videoMediaMainData['media_id']));

                // update status
                $mediaVideoQuery = "update shiksha_institutes_medias set status = ? where media_id = ? and media_type = 'video' and status='draft'";
                $this->dbHandle->query($mediaVideoQuery, array($status, $videoMediaMainData['media_id']));
        	}
        }
        
        $tableName = 'shiksha_institutes_media_locations_mapping';
        if(count($instituteVideoData['locationsData']) > 0){
            $this->dbHandle->insert_batch($tableName,$instituteVideoData['locationsData']);            
        }

        $tableName = 'shiksha_institutes_media_tags_mapping';
        if(count($instituteVideoData['tagsData']) > 0){
            $this->dbHandle->insert_batch($tableName,$instituteVideoData['tagsData']);    
        }

        //saving into shiksha_listings_brochures table
        $tableName = 'shiksha_listings_brochures';
        if(!empty($instituteData['brochure_url'])){
            $brochureData                  = array();
            $brochureData['listing_id']    = $instituteId;
            $brochureData['listing_type']  = $postingListingType;
            $brochureData['brochure_url']  = $instituteData['brochure_url'];
            $brochureData['brochure_year'] = $instituteData['brochure_year'];
            $brochureData['brochure_size'] = $instituteData['brochure_size'];
            $brochureData['cta']           = "brochure";
            $brochureData['updated_by']    = $instituteData['userId'];
            $brochureData['status']        = $status;
            $this->dbHandle->insert($tableName, $brochureData);
        }

        //saving into academic staff table
        $academicStaff = $instituteData['academicStaff'];
        foreach ($academicStaff as $key => $staff) {
            $academicStaff[$key]['listing_id']                  = $instituteId;
            $academicStaff[$key]['listing_type']                = $postingListingType;
            $academicStaff[$key]['status']                        = $status;
        }
        
        $tableName = 'shiksha_institutes_academic_staffs';
        if(count($academicStaff) > 0){
            $this->dbHandle->insert_batch($tableName,$academicStaff);
        }

        //saving academic staff faculty highlights into shiksha_institutes_additional_attributes table 
        $academicStaff_faculty_highlights = $instituteData['academicStaff_faculty_highlights'];
        
        $tableName = 'shiksha_institutes_additional_attributes';
        if(!empty($academicStaff_faculty_highlights)){
            $academicStaff_faculty_highlights['listing_id'] = $instituteId;
            $academicStaff_faculty_highlights['listing_type'] = $postingListingType;
            $academicStaff_faculty_highlights['status'] = $status;
            $this->dbHandle->insert($tableName,$academicStaff_faculty_highlights);
        }

        //saving facilities into shiksha_institutes_facilities and shiksha_institutes_facilities_mappings tables
        $facilityData = $instituteData['facilities']['facilityData'];
        foreach ($facilityData as $key => $facility) {
            $facilityData[$key]['listing_id'] = $instituteId;
            $facilityData[$key]['listing_type'] = $postingListingType;
            $facilityData[$key]['status']       = $status;
        }

        $tableName = 'shiksha_institutes_facilities';
        if(count($facilityData) > 0){
            $this->dbHandle->insert_batch($tableName,$facilityData);
        }

        $facilityMappings = $instituteData['facilities']['facilityMappings'];
        foreach ($facilityMappings as $key => $facility) {
            $facilityMappings[$key]['listing_id'] = $instituteId;
            $facilityMappings[$key]['listing_type'] = $postingListingType;
            $facilityMappings[$key]['status']       = $status;
        }

        $tableName = 'shiksha_institutes_facilities_mappings';
        if(count($facilityMappings) > 0){
            $this->dbHandle->insert_batch($tableName,$facilityMappings);
        }

        //saving research projects into shiksha_institutes_additional_attributes table
        $researchProjects = $instituteData['researchProjects'];
        foreach ($researchProjects as $key => $value) {
           $researchProjects[$key]['listing_id'] = $instituteId;
           $researchProjects[$key]['listing_type'] = $postingListingType;
           $researchProjects[$key]['status'] = $status;
        }

        $tableName = 'shiksha_institutes_additional_attributes';
        if(count($researchProjects) > 0){
            $this->dbHandle->insert_batch($tableName,$researchProjects);
        }

        //saving usp into shiksha_institutes_additional_attributes table
        $usp = $instituteData['usp'];
        foreach ($usp as $key => $value) {
           $usp[$key]['listing_id'] = $instituteId;
           $usp[$key]['listing_type'] = $postingListingType;
           $usp[$key]['status'] = $status;
        }

        $tableName = 'shiksha_institutes_additional_attributes';
        if(count($usp) > 0){
            $this->dbHandle->insert_batch($tableName,$usp);
        }

        $scholarships = $instituteData['scholarships'];
        foreach ($scholarships as $key => $value) {
           $scholarships[$key]['listing_id'] = $instituteId;
           $scholarships[$key]['listing_type'] = $postingListingType;
           $scholarships[$key]['updated_by'] = $instituteData['userId'];
           $scholarships[$key]['status'] = $status;
        }

        $tableName = 'shiksha_institutes_scholarships';
        if(count($scholarships) > 0){
            $this->dbHandle->insert_batch($tableName,$scholarships);
        }


        //saving recruiting companies
        $companies = $instituteData['companies'];
        foreach ($companies as $key => $value) {
           $companies[$key]['listing_id'] = $instituteId;
           $companies[$key]['listing_type'] = $postingListingType;
           $companies[$key]['status']       = $status;
        }

        $tableName = 'shiksha_institutes_companies_mapping';
        if(count($companies) > 0){
            $this->dbHandle->insert_batch($tableName,$companies);
        }

        // save the posting comments
    	$postingCommentData = array();
    	$postingCommentData['comments'] 	= $instituteData['posting_comments'];
    	$postingCommentData['userId'] 		= $instituteData['userId'];
    	$postingCommentData['listingId'] 	= $instituteId;
    	$postingCommentData['tabUpdated'] 	= $postingListingType;
        $this->dbHandle->insert('listingCMSUserTracking',$postingCommentData);
    
    	if($status == 'live' && $flow == 'edit'){
    		$this->makeListingLive($instituteId , 'draft','institute');
    	}   

    	$this->dbHandle->trans_complete();
    	if ($this->dbHandle->trans_status() === FALSE) {
    		throw new Exception('Transaction Failed');
    	}
    	return $instituteId;
    }

    private function _getNewInstituteId()
    {
    	return Modules::run('common/IDGenerator/generateId','institute');
    }

    function _getNewInstituteLocationId(){
        return Modules::run('common/IDGenerator/generateId','instituteLocation');
    }

    // private function _getNewAcademicStaffId(){
    // 	return Modules::run('common/IDGenerator/generateId','institute_academic_staff');
    // }

    // private function _getNewInstituteEventsId(){
    //     return Modules::run('common/IDGenerator/generateId','shiksha_institutes_events');
    // }

    private function _getNewListingMediaId() {
        // return Modules::run('common/IDGenerator/generateId','listing_media');
    }

    function saveInstituteMedia($mediaData, $type = 'photo', $userId) {
        $this->initiateModel();
        $media                    = array();
        $media['media_id']        = !empty($mediaData['mediaid']) ? $mediaData['mediaid'] : NULL;
        $media['media_title']     = !empty($mediaData['title']) ? $mediaData['title'] : NULL;
        $media['media_url']       = !empty($mediaData['imageurl']) ? $mediaData['imageurl'] : NULL;
        $media['media_thumb_url'] = !empty($mediaData['thumburl']) ? $mediaData['thumburl'] : NULL;
        $media['media_type']      = !empty($type) ? $type : $type;
        $media['status']          = 'draft';
        $media['uploaded_by']     = $userId;
        $this->dbHandle->insert('shiksha_institutes_medias', $media);
        return $media['media_id'];
    }

    function updateInstituteMedia() {
        $this->dbHandle->update_batch('shiksha_institutes_medias', $media, 'media_id');
    }

    private function _prepareInstituteMediaData($instituteData, $status, $instituteId, $key = 'institutePhotos', $locationToInstLocationIdMapping) {
        //saving institute media data
        $institutePhotoMainData = array();
        $institutePhotoLocationsData = array();
        $institutePhotoTagsData = array();
        $photoArrKey = 0;
        $photoLocationArrKey = 0;
        $photoTagArrKey = 0;

        $mediaType = "";
        if($key == 'institutePhotos')
        	$mediaType = 'photo';
        else
        	$mediaType = 'video';

        foreach ($instituteData[$key] as $mediaId => $photoData) {
            $institutePhotoMainData[$photoArrKey] = array();
            $institutePhotoMainData[$photoArrKey]['media_id']    = $photoData['listing_media']['media_id'];
            $institutePhotoMainData[$photoArrKey]['media_title'] = $photoData['listing_media']['media_title'];
            $institutePhotoMainData[$photoArrKey]['media_order']       = $photoData['listing_media']['order'];
            foreach ($photoData['locations'] as $locationKey => $location) {

            	$locationKey = $location['city_id']."_".$location['locality_id'];
            	
            	if($locationKey == '0_0')
            		$listing_location_id = 0;
            	else
            		$listing_location_id = $locationToInstLocationIdMapping[$locationKey];

            	$temp = array();
                $temp['listing_id'] = $instituteId;
                $temp['listing_type'] = $instituteData['postingListingType'];
                $temp['media_type'] = $mediaType;
                $temp['media_id']     = $mediaId;
                $temp['listing_location_id'] = $listing_location_id;
                $temp['status']       = $status;
                $temp['updated_by']   = $instituteData['userId'];                           
                $institutePhotoLocationsData[] = $temp;
            }
            
            foreach ($photoData['tags'] as $tagKey => $tag) {
            	$temp = array();
                $temp['listing_id'] = $instituteId;
                $temp['listing_type'] = $instituteData['postingListingType'];
                $temp['media_id']     = $mediaId;
                if(strpos($tag['tag_id'], 'event') === false){
                    $temp['tag_id']       = $tag['tag_id'];
                    $temp['tag_type']     = 'tag';
                }
                else{
                    $temp['tag_id']       = $this->eventIdentifierMapping[$tag['tag_id']];
                    $temp['tag_type']     = 'event';
                }
                
                $temp['status']       = $status;
                $temp['updated_by']   = $instituteData['userId'];
                $institutePhotoTagsData[] = $temp;
            }
            $photoArrKey++;
            $photoLocationArrKey++;
            $photoTagArrKey++;
        }
        return array('mainData' => $institutePhotoMainData,
                     'locationsData'=>$institutePhotoLocationsData,
                     'tagsData' => $institutePhotoTagsData);
    }
	 //below Function is used for getting different Institute Types available in shikhsa
    function getInstituteTypesInShiksha()
    {
        $this->initiateModel();
        $sql = "SELECT distinct institute_specification_type as type FROM shiksha_institutes";
        $rs = $this->dbHandle->query($sql)->result_array();
        $result = array();
        foreach ($rs as $key => $value) {
            if(!empty($value['type']))
                $result[] = $value['type'];
        }
        return $result;
    }
    function getSearchResultsForTable($instituteId='',$universityId='',$status='',$type='',$start=0,$baseCall=true,$openSearch='',$fetchListType)
    {
 
        $result = array();
        if($baseCall == true)
        {
            return $this->getBasicInfoForInstitutesTable($instituteIds,$status,$type,$start,$baseCall,'',$fetchListType);
        }
        if( empty($instituteId) && empty($universityId) && empty($openSearch))
            return $result;
        $this->initiateModel('read');
        if(!empty($universityId))
        {   
            if($fetchListType == 'university')
            {
                $result = $this->getChildUniversities($universityId);
            }
            else
            {
                $result = $this->getChildData($universityId,'university',false);        

            }
            
        }
        elseif(!empty($instituteId))
        {
            $result = $this->getChildData($instituteId,'institute',false);
        }
        elseif (!empty($openSearch)) {
            return $this->getBasicInfoForInstitutesTable($instituteIds,$status,$type,$start,$baseCall,$openSearch,$fetchListType);
        }
        if( $fetchListType == 'institute' )
        {
            foreach ($result as $key => $value) {
            $temp = explode('_', $value[count($value) - 1]);
            if(!empty($temp[1]) && $temp[0] == $fetchListType)
                $instituteIds[] = $temp[1];
            }
            if(!in_array($instituteId, $instituteIds) && !empty($instituteId))
            {
                $instituteIds[] = $instituteId;
            }    
        }
        else if($fetchListType == 'university')
        {
            $instituteIds = $result;
        }

        if(!empty($instituteIds))
            return $this->getBasicInfoForInstitutesTable($instituteIds,$status,$type,$start,$baseCall,'',$fetchListType);

    }
    function getStatusCheckForInstitutes(&$whereCondition,$instituteIds,$status,$type,$searchText)
    {
        $params = array();
        if(!empty($status) && $status != 'Dummy' && $status != 'Disabled')
        {
            $whereCondition[] = " status = ?";$params[] = $status;
            $whereCondition[] = " is_dummy = 0 ";
            $whereCondition[] = " disabled_url IS NULL ";
        }
        if(!empty($status) && $status == 'Dummy')
        {
            $whereCondition[] = " is_dummy = 1 ";
        }
        if(!empty($status) && $status == 'Disabled')
        {
            $whereCondition[] = " disabled_url IS NOT NULL  ";
        }
        if(!empty($type))
        {
            $whereCondition[] = "  institute_specification_type = ?";$params[] = $type;
        }
        if( !empty($instituteIds))
        {
            $whereCondition[] = "  listing_id IN (?) ";$params[] = $instituteIds;
        }
        if( !empty($searchText))
        {
            $whereCondition[] = " name like ? ";$params[] = '%'.$searchText.'%';
        }
        return $params;
    }
    function getBasicInfoForInstitutesTable($instituteIds,$status='',$type='',$start=0,$baseCall,$searchText,$fetchListType)
    {

        if(empty($instituteIds) && !$baseCall && empty($searchText))
            return array();
        $this->initiateModel('read');
        $whereCondition = array();

        $params = $this->getStatusCheckForInstitutes($whereCondition,$instituteIds,$status,$type,$searchText);

        $whereCondition[] = " listing_type = ? ";$params[] = $fetchListType;

        if(empty($whereCondition))
        {
            $where = "1";
        }

        if($fetchListType == 'institute')
        {
            $fields = 'institute_specification_type as type';
        }
        else
        {
            $fields = 'university_specification_type as type';   
        }

        
        $sql = "SELECT listing_id as institute_id, ".$fields. ",name,status,is_dummy,disabled_url FROM shiksha_institutes   WHERE $where  ".implode(' AND ', $whereCondition)." AND status NOT IN ('history','deleted') order by updated_on desc limit $start,25";
                
       $rsInfo =  $this->dbHandle->query($sql,$params)->result_array();
       
       $data = array();
        $sql = "SELECT count(*) as count FROM shiksha_institutes WHERE $where ".implode(' AND ', $whereCondition)." AND status NOT IN ('history','deleted')";
        $instituteCount =  $this->dbHandle->query($sql,$params)->result_array();
        $data['totalCount'] = $instituteCount[0]['count'];
       $data['data'] = $rsInfo;
       
       return $data;

    }

    function getChildUniversities($universityId,$type = 'university')
    {
        if(empty($universityId))
            return array();

        $this->initiateModel();

        $sql = "SELECT listing_id FROM shiksha_institutes WHERE parent_listing_id = ? AND parent_listing_type = ? AND listing_type = ? AND status IN ('live','draft')";
        $result  = $this->dbHandle->query($sql,array($universityId, $type, $type))->result_array();

        $childArray = array();
        foreach ($result as $key => $value) {
            $childArray[] = $value['listing_id'];
        }
        $childArray[] = $universityId;
        return $childArray;
    }

    function getInstitutePostingComments($instituteId, $listingType,$limit = 20){

    	if(empty($instituteId))
    		return array();

    	$this->initiateModel('read');

        $limit = (int)$limit;

    	$query = "SELECT t.userId,t.firstname,t.lastname,track.comments, track.updatedAt FROM listingCMSUserTracking track left join tuser t ON(track.userId = t.userId) WHERE track.listingId = ? AND track.tabUpdated = ? ORDER BY track.id desc LIMIT ".$limit;

    	$rsInfo =  $this->dbHandle->query($query, array($instituteId,$listingType))->result_array();

    	return $rsInfo;
    }

    function updateInstituteData($instituteId, $tablesToBeChanged, $fromStatus = array("draft"), $toStatus = 'history',$postingListingType = 'institute'){

    	if(empty($instituteId))
    		return false;

        $listingMainStatus = $this->config->item("listingMainStatus");

    	$this->initiateModel('write');

    	$this->dbHandle->query("UPDATE shiksha_institutes SET status = ? WHERE listing_id = ? AND status IN (?)", array($toStatus, $instituteId, $fromStatus));


    	$tables = array(
                        'listings_main' => array('listing_type_id','listing_type'),
                        'shiksha_institutes_locations' => array('listing_id','listing_type'),
                        'shiksha_listings_contacts' => array('listing_id','listing_type'),
                        'shiksha_institutes_media_locations_mapping' => array('listing_id'),
                        'shiksha_institutes_media_tags_mapping' => array('listing_id'),
                        'shiksha_listings_brochures' => array('listing_id','listing_type'),
                        'shiksha_institutes_academic_staffs' => array('listing_id'),
                        'shiksha_institutes_additional_attributes' => array('listing_id','listing_type','description_type'),
                        'shiksha_institutes_facilities' => array('listing_id'),
                        'shiksha_institutes_facilities_mappings' => array('listing_id'),
                        'shiksha_institutes_events' => array('listing_id'),
                        'shiksha_institutes_scholarships' => array('listing_id'),
                        'shiksha_institutes_companies_mapping' => array('listing_id')
                    );
        
        foreach($tables as $tableName => $tableColumns) {
        
            $listingTypeIdColumnName = $tableColumns[0];
            $listingTypeColumnName = $tableColumns[1];
            
            $fromStatusList = $fromStatus;
            $listingPostingType = $postingListingType;
            if($tableName == 'listings_main'){
                foreach ($fromStatusList as $key=>$value) {
                    $fromStatusList[$key] = $listingMainStatus[$value];
                }
                $listingPostingType = ($postingListingType == 'university'?'university_national':'institute');
            }
            /**
             * update status and updated_by and updated_on 
             */ 

            $this->dbHandle->where_in('status',$fromStatusList);
            $this->dbHandle->where($listingTypeIdColumnName, $instituteId);
            if(!empty($listingTypeColumnName)){
                $this->dbHandle->where($listingTypeColumnName, $listingPostingType);
            }
            if($tableName == 'shiksha_institutes_additional_attributes'){
                $attributeType = ['admission_info','cutoff_info','placement_info','acp_info','bip_info','sip_info','icop_info','icox_info'  ];
                $this->dbHandle->where_not_in('description_type',$attributeType);
            }
            if($tableName == 'shiksha_listings_brochures'){
                $this->dbHandle->where('cta','brochure');
            }
            $this->dbHandle->update($tableName,array('status' => $toStatus));
        }
    }

    function _trackInstituteUpdatedSections($instituteId, $userId, $sectionsUpdated,$postingListingType = 'institute'){

    	if(empty($sectionsUpdated))
    		return;

    	$sectionUpdatedData = array();
    	foreach ($sectionsUpdated as $key => $value) {
    		$temp = array();
    		$temp['listing_id'] = $instituteId;
    		$temp['listing_type'] = $postingListingType;
    		$temp['section_updated'] = $value;
    		$temp['updated_by'] = $userId;

    		$sectionUpdatedData[] = $temp;
    	}
 		$this->initiateModel('write');

        $this->dbHandle->insert_batch('shiksha_listing_updation_tracking',$sectionUpdatedData);            
    }

	 /**
    Check status of listings
    */
    public function checkListingStatus($listings_id,$listing_type = 'institute') {
         $this->initiateModel('read');
        // error handling
        if(empty($listings_id)) {
            return array();
        }
        // check for listing status in shiksha_institutes table 
        $sql = "select listing_id, name from shiksha_institutes where status='live' and listing_id = ? and listing_type = ?";
        $query = $this->dbHandle->query($sql,array($listings_id,$listing_type));
        $result_array = array();

        // create result array
        foreach($query->result() as $row) {
            $result_array['listingId'] = $row->listing_id;
            $result_array['listingName'] = $row->name;
        }

        return $result_array;
   }

     public function deleteListings($all_child_ids,$userId,$postingListingType)
    {
        
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
         
        if(!empty($all_child_ids)){ 
            error_log("afcsdfvrsgver".print_r($userId, true));
            $this->deleteListingData($all_child_ids,$userId,$postingListingType);        
        }
    
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }
    }


    private function deleteListingData($listingIds,$userId = NULL,$postingListingType)
    {
        /**
         * Define all the tables to be updated for institute
         * Field 1: Listing type id column.
         * Field 2: Listing type column. Some tables will not have this column.
         */
        if(!is_array($listingIds)){
            $listingIds = explode(',',$listingIds);
        }

        $listingMainStatus= $this->config->item("listingMainStatus");

        $tables = array(
                        'listings_main' => array('listing_type_id','listing_type'),
                        'shiksha_institutes' => array('listing_id'),
                        'shiksha_institutes_facilities' => array('listing_id'),
                        'shiksha_institutes_facilities_mappings' => array('listing_id'),
                        'shiksha_institutes_media_tags_mapping' => array('listing_id'),
                        'shiksha_institutes_academic_staffs' => array('listing_id'),
                        'shiksha_listings_contacts' => array('listing_id','listing_type'),
                        'shiksha_institutes_locations' => array('listing_id'),
                        'shiksha_institutes_companies_mapping' => array('listing_id'),
                        'shiksha_institutes_media_locations_mapping' => array('listing_id'),
                        'shiksha_institutes_events' => array('listing_id'),
                        'shiksha_institutes_scholarships' => array('listing_id'),
                        'shiksha_institutes_additional_attributes' => array('listing_id'),
                        'shiksha_listings_brochures' => array('listing_id','listing_type')
                    );
        
        foreach($tables as $tableName => $tableColumns) {
        
            $listingTypeIdColumnName = $tableColumns[0];
            $listingTypeColumnName = $tableColumns[1];

            $listingPostingType = array($postingListingType);
                        
            $fromStatusList = array('live','draft');
            if($tableName == 'listings_main'){
                foreach ($fromStatusList as $key=>$value) {
                    $fromStatusList[$key] = $listingMainStatus[$value];
                }
                // $listingPostingType = ($postingListingType == 'university'?'university_national':'institute');
                $listingPostingType = array('university_national','institute');
            }
            
            /**
             * update status and updated_by and updated_on 
             */ 
            $this->dbHandle->where_in('status', $fromStatusList);
            $this->dbHandle->where_in($listingTypeIdColumnName, $listingIds);
            if(!empty($listingTypeColumnName)){
                $this->dbHandle->where_in($listingTypeColumnName, $listingPostingType);
            }
            if($tableName == 'shiksha_listings_brochures'){
                $this->dbHandle->where('cta','brochure');
            }
            if($tableName == 'listings_main'){
                $update['status'] ='deleted';
                $update['editedBy'] = $userId;
                $update['last_modify_date'] = date("Y-m-d H:i:s");
                $this->dbHandle->update($tableName,$update);
            }
            else{
                $this->dbHandle->update($tableName,array('status' => 'deleted'));
            }
        }

        $this->emptyAffiliationOfCourses($listingIds);
    }

    private function emptyAffiliationOfCourses($listingIds){
        if(!empty($listingIds)){
            $this->dbHandle->where_in('status',array('live','draft'))->where_in('affiliated_university_id',$listingIds);
            $this->dbHandle->update('shiksha_courses',array('affiliated_university_id'=>NULL,'affiliated_university_name'=>NULL,'affiliated_university_scope'=>NULL,'affiliated_university_year'=>NULL));
        }
    }


    public function insertUpdatedListingData($deleted_listing_id,$deleted_new_listing_id,$listingType, $getDbHandle) {
        // error handling
        if(empty($deleted_listing_id) || $deleted_listing_id == 0 ||
            empty($deleted_new_listing_id)) {
            return "success";
        }
        
        $data = array(
           'listing_type_id'                    => $deleted_listing_id ,
           'replacement_lisiting_type_id'       => $deleted_new_listing_id,
           'qnareplacement_lisiting_type_id'    => $deleted_new_listing_id,
           'alumnireplacement_lisiting_type_id' => $deleted_new_listing_id,
           'listing_type'                       => $listingType
        );

        $listingTypeArr = ($listingType == 'institute' || $listingType == 'university') ? array('institute','university') : array('course');
         // get db handle and query db
            if(empty($getDbHandle)) {
                $getDbHandle = $this->getWriteHandle();
            }
            $getDbHandle->insert('deleted_listings_mapping_table', $data); 

            // case for handling cascade delete || do the same behaviour
            $update_casecade_delete = "UPDATE deleted_listings_mapping_table SET replacement_lisiting_type_id= ? ".
                                  "WHERE replacement_lisiting_type_id= ? and listing_type in (?)";
            $query = $getDbHandle->query($update_casecade_delete,array($deleted_new_listing_id,$deleted_listing_id,$listingTypeArr));

            $update_casecade_delete = "UPDATE deleted_listings_mapping_table SET qnareplacement_lisiting_type_id= ? ".
                                  "WHERE qnareplacement_lisiting_type_id= ? and listing_type in (?)";
            $query = $getDbHandle->query($update_casecade_delete,array($deleted_new_listing_id,$deleted_listing_id,$listingTypeArr));

            $update_casecade_delete = "UPDATE deleted_listings_mapping_table SET alumnireplacement_lisiting_type_id= ? ".
                                  "WHERE replacement_lisiting_type_id= ? and listing_type in (?)";
            $query = $getDbHandle->query($update_casecade_delete,array($deleted_new_listing_id,$deleted_listing_id,$listingTypeArr));

            // update message table
            if($deleted_new_listing_id > 0 && $deleted_listing_id > 0 && $listingType != 'course') {
            $update_query = "UPDATE messageTable set listingTypeId= ? WHERE listingTypeId= ? ".
                                    " AND status='live' AND listingType='institute'";
            $query = $getDbHandle->query($update_query,array($deleted_new_listing_id,$deleted_listing_id)); 

        }
        
        return "success";
   }

   public function updateTagIdForDeletedListing($all_deleted_listing_ids,$deleted_new_listing_id){
        $this->initiateModel('write');
        if(!is_array($all_deleted_listing_ids)){
            $all_deleted_listing_ids = explode(',',$all_deleted_listing_ids);
        }
        if(!empty($all_deleted_listing_ids) && !empty($deleted_new_listing_id)){

            $sql = "select tag_id,entity_id from tags_entity where entity_id in (?) and status = 'live'";
            $result1 = $this->dbHandle->query($sql,array($all_deleted_listing_ids))->result_array();
            
            if(!empty($result1)){

                foreach($result1 as $val){
                    $tagIds[] = $val['tag_id'];
                }

                $sql = "UPDATE tags_entity SET status='deleted' where entity_id in (?) and status='live'";
                $this->dbHandle->query($sql,array($all_deleted_listing_ids));

                $sql = "select tag_id,entity_id from tags_entity where entity_id=? and status = 'live' limit 1";
                $result = $this->dbHandle->query($sql,array($deleted_new_listing_id))->result_array();

                if(!empty($result)){
                    $new_tagid = $result[0]['tag_id'];
                }

                if(!empty($new_tagid)){
                    $sql = "UPDATE tags_content_mapping SET tag_id=? where tag_id in (?) and status='live'";
                    $this->dbHandle->query($sql,array($new_tagid,$tagIds));
       
                }
            }
        }

   }

   public function updateDisableListingUrl($listingIds,$url=''){
       $this->initiateModel('write');
       if(!empty($listingIds)){
            $sql = "UPDATE shiksha_institutes SET disabled_url = ? WHERE listing_id in (?) and status in ('live','draft')";
            $this->dbHandle->query($sql,array($url,$listingIds));
       }
    }
    
   /***
   @param : listingId is institute Id  
   @param : listingStatus is from which status we are making to live institute(i.e draft / disabled)
   @param : listingType is institute
   */
    function makeListingLive($listingId = '' , $listingStatus = '',$listingType = 'institute')
    {
        $this->initiateModel('write');
        if($listingStatus == 'disabled')
        {
            return $this->makeListingLiveFromDisbaledState($listingId,$listingStatus,$listingType);
        }
        else if($listingStatus == 'draft' || $listingStatus == 'disabled_draft')
        {
            if($listingStatus == 'disabled_draft')
            {
                $allowProcess = $this->makeListingLiveFromDisbaledState($listingId,$listingStatus,$listingType,array('draft'));       
                if(!$allowProcess || $allowProcess == 'FAIL')
                    return $allowProcess;
            }
            //change institute draft status to live status
                $tables = array(
                        'listings_main' => array('listing_type_id','listing_type'),
                        'shiksha_institutes' => array('listing_id'),
                        'shiksha_institutes_facilities' => array('listing_id'),
                        'shiksha_institutes_facilities_mappings' => array('listing_id'),
                        'shiksha_institutes_media_tags_mapping' => array('listing_id'),
                        'shiksha_institutes_academic_staffs' => array('listing_id'),
                        'shiksha_listings_contacts' => array('listing_id','listing_type'),
                        'shiksha_institutes_locations' => array('listing_id','listing_type'),
                        'shiksha_institutes_companies_mapping' => array('listing_id'),
                        'shiksha_institutes_media_locations_mapping' => array('listing_id'),
                        'shiksha_institutes_events' => array('listing_id'),
                        'shiksha_institutes_scholarships' => array('listing_id'),
                        'shiksha_institutes_additional_attributes' => array('listing_id'),
                        'shiksha_listings_brochures' => array('listing_id','listing_type')
                    );
            
            $this->dbHandle->trans_start();
            $listingMainStatus= $this->config->item("listingMainStatus");
            foreach($tables as $tableName => $tableColumns) {
            
                $listingTypeIdColumnName = $tableColumns[0];
                $listingTypeColumnName = $tableColumns[1];
                
                $listingPostingType = $listingType;

                $livestatus = ($tableName == 'listings_main' ? $listingMainStatus['live']: 'live');
                $draftstatus = ($tableName == 'listings_main' ? $listingMainStatus['draft']: 'draft');


                if($tableName == 'listings_main')
                {
                    $listingPostingType = ($listingType == 'university'?'university_national':$listingType);
                }

                /**
                 * update status and updated_by and updated_on 
                 */ 
                $this->dbHandle->select('count(*) as count');
                $this->dbHandle->where('status',$draftstatus);
                $this->dbHandle->where($listingTypeIdColumnName ,$listingId);
                if(!empty($listingTypeColumnName)){
                    $this->dbHandle->where($listingTypeColumnName, $listingType);
                }
                if($tableName == 'shiksha_listings_brochures'){
                    $this->dbHandle->where('cta','brochure');
                }
                $result = $this->dbHandle->get($tableName)->result_array();

                if($result[0]['count'] > 0)
                {
                    //mark history
                    $this->dbHandle->where($listingTypeIdColumnName,$listingId);
                    if(!empty($listingTypeColumnName)){
                        $this->dbHandle->where($listingTypeColumnName,$listingPostingType);
                    }
                    if($tableName == 'shiksha_listings_brochures'){
                        $this->dbHandle->where('cta','brochure');
                    }
                    $this->dbHandle->where('status',$livestatus);
                    $this->dbHandle->update($tableName,array('status' => 'history'));

                    //mark live
                    $this->dbHandle->where($listingTypeIdColumnName,$listingId);
                    if(!empty($listingTypeColumnName)){
                        $this->dbHandle->where($listingTypeColumnName,$listingPostingType);
                    }
                    if($tableName == 'shiksha_listings_brochures'){
                        $this->dbHandle->where('cta','brochure');
                    }
                    $this->dbHandle->where('status',$draftstatus);
                    $this->dbHandle->update($tableName,array('status' => $livestatus));
                }
            }

            //update status in shiksha_institutes_medias
            $sql = "SELECT media_id FROM shiksha_institutes_media_locations_mapping WHERE listing_id = ? AND listing_type = ? AND status='live'";
            $mediaIds = $this->dbHandle->query($sql,array($listingId,$listingType))->result_array();

            if(!empty($mediaIds)){

                foreach($mediaIds as $key=>$val){
                    $mediaArray[] = $val['media_id'];
                }

                $sql = "UPDATE shiksha_institutes_medias
                            SET status = 'live'
                            WHERE media_id in (?)
                            AND status = 'draft' ";
                $this->dbHandle->query($sql,array($mediaArray));  

            }
            
            // invalidate institute hierarchy cache
            $this->nationalinstitutecache = $this->load->library('nationalInstitute/cache/NationalInstituteCache');
            $this->nationalinstitutecache->removeInstitutesCache(array($listingId));

            $this->dbHandle->trans_complete();
            if ($this->dbHandle->trans_status() === FALSE) {
                throw new Exception('Transaction Failed');
            }
            return $this->dbHandle->trans_status();
       }
    }

    function makeListingLiveFromDisbaledState($listingId = '' , $listingStatus = '',$listingType = 'institute',$statusCheck = array('live','draft'))
    {
        //change disabled institute to live state 
            $sql = "SELECT parent_listing_id, parent_listing_type FROM shiksha_institutes where listing_id = ? and status in  (?) AND listing_type = ?  limit 1";
            $rs = $this->dbHandle->query($sql,array($listingId,$statusCheck,$listingType))->result_array();

            if(count($rs) > 0 && !empty($rs[0]['parent_listing_id']))
            {
                $sql = "SELECT disabled_url FROM shiksha_institutes where listing_id = ? and status = 'live' AND listing_type = ? ";
                $rst = $this->dbHandle->query($sql,array($rs[0]['parent_listing_id'],$rs[0]['parent_listing_type']))->result_array();
                if(count($rst) > 0 && !empty($rst[0]['disabled_url']))
                {
                    return 'FAIL';
                }
            }

            if($listingType == 'university')
            {
                $tempType = array('university','institute');
            }
            else
            {
                $tempType = array('institute');
            }

            $result = $this->getChildData($listingId,$listingType,false);
            foreach ($result as $key => $value) {
                $temp = explode('_', $value[count($value) - 1]);
                if(!empty($temp[1]) && in_array($temp[0], $tempType))
                    $instituteIds[] = $temp[1];
            }

            if(!empty($instituteIds)){
                $instituteIds = array_unique($instituteIds);
            }else{
                $instituteIds = array($listingId);
            }

            // invalidate institute hierarchy cache
            $this->nationalinstitutecache = $this->load->library('nationalInstitute/cache/NationalInstituteCache');
            $this->nationalinstitutecache->removeInstitutesCache($instituteIds);

            $sql = "UPDATE shiksha_institutes SET disabled_url = NULL WHERE listing_id IN (?) AND status in ('live','draft') AND disabled_url IS NOT NULL ";

            $result = $this->dbHandle->query($sql,array($instituteIds));
 
            //update disable url of all courses
            $coursemodel = $this->load->model('nationalCourse/coursepostingmodel');
            $coursemodel->updateCourseDisableUrl($instituteIds,NULL,$listingType);

            if($result)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
    }

    function getCoursesForPrimaryListing($listingId){
        $this->initiateModel('read');
        $sql = "SELECT course_id, name, course_order FROM shiksha_courses WHERE primary_id =? and status = 'live' ORDER BY course_order = 0,course_order,name";
        $result = $this->dbHandle->query($sql,array($listingId))->result_array();
        return $result;

    }

    function updateCourseOrderInDB($course_id,$course_order){
        $this->initiateModel('write');
        $sql = "UPDATE shiksha_courses SET course_order =? WHERE status = 'live' and course_id = ?";
        $this->dbHandle->query($sql,array($course_order,$course_id));
    }
	function getUniversityParentHierarchy($universityId)
    {
        $parentData = array();
        if(empty($universityId))
            return $parentData;


        $this->initiateModel();

        $sql = "SELECT parent_listing_id FROM shiksha_institutes WHERE listing_id = ? AND listing_type = 'university' AND status = 'live'";

        $rs =  $this->dbHandle->query($sql,$universityId)->result_array();

        if(empty($rs[0]['parent_listing_id']))
            return $this->getUniversityDataById(array($universityId));
        else
            return $parentData;
    }
    function checkDraftStatusExist($listingId)
    {
        if(empty($listingId))
            return false;

        $this->initiateModel();


        $sql = "SELECT count(*) as count FROM shiksha_institutes WHERE listing_id = ? AND status = 'draft'";
        $rs = $this->dbHandle->query($sql,$listingId)->result_array();
        if($rs[0]['count'] > 0 && !empty($rs[0]['count'])  )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function getCityName($city_id){
        $this->initiateModel('read');
        $sql = "SELECT ct.city_id,ct.city_name from countryCityTable ct where ct.city_id=?";

        $result = $this->dbHandle->query($sql,array($city_id))->result_array();

        return $result[0]['city_name'];
    }

    function getLocalityName($locality_id){
        $this->initiateModel('read');
        $sql = "SELECT lcm.localityId,lcm.localityName from localityCityMapping lcm where lcm.localityId=?";

        $result = $this->dbHandle->query($sql,array($locality_id))->result_array();
        
        return $result[0]['localityName'];
    }


    function updateIndexLog($instituteIds,$listingType,$operation,$section=''){
        $this->initiateModel('write');

        $instituteUpdated= array();
        $shikshamodel = $this->load->model("common/shikshamodel");
        foreach ($instituteIds as $key => $value) {
            $temp = array();
            $temp['listing_id'] = $value;
            $temp['listing_type'] = $listingType;
            $temp['operation'] = $operation;
            $temp['section_updated'] = $section;
            $instituteUpdated[] = $temp;

            $arr = array("cache_type" => "htmlpage", "entity_type" => "institute", "entity_id" => $value, "cache_key_identifier" => "");
            $shikshamodel->insertCachePurgingQueue($arr);
        }

        $this->dbHandle->insert_batch('indexlog',$instituteUpdated);            
    }

    function getLiveListingData($instituteId,$type){
        $this->initiateModel('read');
        $sql = "SELECT listing_id,university_specification_type,is_autonomous,is_open_university,is_ugc_approved,is_aiu_membership,accreditation,ownership,parent_listing_id,parent_listing_type,is_satellite FROM shiksha_institutes WHERE listing_id=? AND listing_type=? AND status = 'live'";

        $result = $this->dbHandle->query($sql,array($instituteId,$type))->result_array();

        return $result[0];
    }


    function getListingName($listingId)
    {
        if(empty($listingId))
            return;
        $this->initiateModel();


        $sql = "SELECT name,listing_type FROM shiksha_institutes where listing_id = ? and status in ('live','draft') order by updated_on desc limit 1";
        $result = $this->dbHandle->query($sql,array($listingId))->result_array();


        return $result;
    }

    function updateSeoUrl($instituteId,$seoUrl,$listingType){
         $this->initiateModel('write');
         if($listingType == 'university'){
            $listingType = 'university_national';
         }
         $listingMainStatus= $this->config->item("listingMainStatus");
         $statusArr = array($listingMainStatus['live'],$listingMainStatus['draft']);
         $sql = "UPDATE listings_main SET listing_seo_url = ? WHERE listing_type_id=? AND status in (?) AND listing_type=?";

         $this->dbHandle->query($sql,array($seoUrl,$instituteId,$statusArr,$listingType));

    }
    function saveListingContent($listingId,$listingType,$popularCourseArray,$listingContentType,$duration,$articleId)
    {
        if( ($listingContentType != 'article' && (empty($listingId) || empty($popularCourseArray))) || $listingContentType == 'article' && empty($articleId))
        {
            return false;
        }
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
        $sql = "UPDATE shiksha_listing_contentSticky SET status = 'history' where listing_id = ? AND listing_type = ? AND type = ?";
        $rs = $this->dbHandle->query($sql,array($listingId,$listingType,$listingContentType));

        if($rs)
        {
            $listingContent = array();
            $listingContent['listing_id'] = $listingId;
            $listingContent['listing_type'] = $listingType;
            $listingContent['type'] = $listingContentType;
            $listingContent['status'] = 'live';
            if(!empty($duration) && $listingContentType != 'article')
            {
                $listingContent['expiry_date'] = date("Y-m-d H:i:s",strtotime('+'.$duration.' months'));
            }
            else if($listingContentType == 'article')
            {
                $listingContent['expiry_date'] = date("Y-m-d H:i:s",strtotime('+'.$duration.' days'));   
            }
            if($listingContentType == 'article')
            {
                    $listingContent['entityId'] = $articleId;
                    $listingContent['entityType'] = 'article';
                    $this->dbHandle->insert('shiksha_listing_contentSticky',$listingContent);
            }
            else
            {
                foreach ($popularCourseArray as $key => $value) {
                    $listingContent['entityId'] = $value;
                    $listingContent['entityType'] = 'course';
                    $listingContent['course_order'] = $key+1;

                    $this->dbHandle->insert('shiksha_listing_contentSticky',$listingContent);
                }
            }
            
        }
        
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
                throw new Exception('Transaction Failed');
                return false;
            }
        else
        {
            return true;
        }


    }
    function getListingContentCourseOrder($listingId,$listingType,$listingContentType)
    {
        $this->initiateModel();

        $sql = "SELECT entityId,expiry_date FROM shiksha_listing_contentSticky WHERE listing_id = ? AND listing_type = ? AND type = ? AND status = 'live' AND entityType = 'course' order by course_order asc";
        $result =  $this->dbHandle->query($sql,array($listingId,$listingType,$listingContentType))->result_array();


        $courseOrder = array();
        $courseOrder['expiryDate'] = $result[0]['expiry_date'];
        foreach ($result as $key => $value) {
            $courseOrder['courses'][] = $value['entityId'];
        }
        return $courseOrder;
    }
    function checkArticleMappedToListing($articleId,$listingId,$listingType)
    {
        $returnArry = array();

        if(empty($articleId) || empty($listingId) || empty($listingType))
        {
            $returnArry['result'] = 2;
            return $returnArry;
        }
        $this->initiateModel();

        $listingType = ($listingType == 'institute' ? 'college' : $listingType);

        $sql = "SELECT blogTitle FROM blogTable where blogId = ? AND status = 'live'";

        $rs = $this->dbHandle->query($sql,array($articleId))->result_array();

        $returnArry['articleName'] = $rs[0]['blogTitle'];
        if(!empty($rs[0]['blogTitle']))
        {
            $sql = "SELECT count(1) as count FROM articleAttributeMapping where entityId = ? AND articleId = ? AND entityType = ? AND status = 'live'";
            $result = $this->dbHandle->query($sql,array($listingId,$articleId,$listingType))->result_array();
            if($result[0]['count'] >= 1)
            {
                $returnArry['result'] = 0;
            }
            else
            {
                $returnArry['result'] = 1;
            }
        }
        else
        {
            $returnArry['result'] = 2;
        }
        return $returnArry;
    }
    function getArticleIdForListingSticky($listingId,$listingType)
    {
        $articleInfo  = array();
        if(empty($listingId) || empty($listingType))
            return $articleInfo;

        $this->initiateModel();

        $sql = "SELECT entityId,expiry_date  from shiksha_listing_contentSticky where listing_id = ? AND listing_type = ? AND entityType = 'article' AND status = 'live'";

        $rs = $this->dbHandle->query($sql,array($listingId,$listingType))->result_array();
        if(!empty($rs[0]['entityId']))
        {
            $sql = "SELECT blogTitle FROM blogTable where blogId = ? AND status = 'live'";
            $result = $this->dbHandle->query($sql,array($rs[0]['entityId']))->result_array();
            $articleInfo['articleId'] = $rs[0]['entityId'];
            $articleInfo['articleName'] = $result[0]['blogTitle'];
            $articleInfo['expiryDate'] = $rs[0]['expiry_date'];
        }
        return $articleInfo;
    }

    function listingContentResetOptions($listingId,$listingType,$listingContentType)
    {
        $this->initiateModel('write');

        $sql = "UPDATE shiksha_listing_contentSticky SET status = 'history' where listing_id = ? AND listing_type = ? AND type = ?";
        $rs = $this->dbHandle->query($sql,array($listingId,$listingType,$listingContentType));

        return $rs;

    }

    function getListingType($listingId, $status = array('live', 'draft')) {
        if(empty($status)) {
            $status = array('live', 'draft');
        }
        $this->initiateModel('write');
        $sql = "SELECT distinct listing_id, listing_type FROM shiksha_institutes WHERE listing_id = ? AND status in (?)";
        $result = $this->dbHandle->query($sql,array($listingId,$status))->result_array();
        $returnArray =array();
        foreach ($result as $row ) {
            $returnArray[$row['listing_id']] = $row['listing_type'];
        }
        return $returnArray;
    }

	public function getUniversityAdmissionDetails($listingId=0,$status='draft',$listingType='university',$description_type='admission_info'){

        $this->initiateModel('write');
        $result=array();
        if($listingId>0){
            $query = "SELECT * FROM  shiksha_institutes_additional_attributes
                   WHERE listing_id = ? 
                   AND listing_type = ?
                   AND status= ?
                   AND description_type=?";

            $result = $this->dbHandle->query($query,array($listingId,$listingType,$status,$description_type))->result_array();
        }
        return $result;

    }

    public function insertUniversityAdmissionDetails($data,$fromStatus,$toStatus,$description_type='admission_info'){
        $this->initiateModel('write');
       
        $this->dbHandle->where(array('listing_id'=>$data['listing_id'],'description_type' => $description_type))->where_in('status',$fromStatus);
        $update = array('status'=>$toStatus);
        $table = 'shiksha_institutes_additional_attributes';
        $this->dbHandle->update($table,$update);
        if($data['status'] == 'live' || $data['status'] == 'draft'){
            $this->dbHandle->insert($table,$data);
        }
        
    }
    function updateGoogleStaticMapForListing($listing_location_ids,$statusFlag)
    {
        $this->initiateModel('write');

        if(empty($listing_location_ids) && count($listing_location_ids) == 0)
        {
            return;
        }

        $statusFlag = empty($statusFlag) ? 'live' : $statusFlag;

        $sql = "UPDATE shiksha_listings_contacts SET google_url = '' WHERE listing_location_id IN (?) AND listing_type IN ('institute','university') AND status = ? ";

        $rs = $this->dbHandle->query($sql,array($listing_location_ids,$statusFlag));
    }

    function getInstituteParentHierarchyFromFlatTable($instituteIds){
        if(empty($instituteIds)) return;
        if(!is_array($instituteIds)) return;
        $this->initiateModel('read');

        foreach ($instituteIds as $key => $instituteId) {
            $instituteIds[$key] = (string) $instituteId;
        }

      /*  $sql = "SELECT 
                DISTINCT hierarchy_parent_id,
                primary_parent_id,
                si.is_autonomous,
                si.name,
                si.listing_type,
                si.is_satellite,
                si.is_aiu_membership,
                si.is_open_university,
                si.is_ugc_approved,
                si.university_specification_type,
                si.institute_specification_type,
                si.ownership,
                si.accreditation,
                si.is_autonomous,
                si.is_dummy,
                si.disabled_url
                FROM shiksha_courses_institutes sci
                LEFT JOIN shiksha_institutes si ON si.listing_id = sci.hierarchy_parent_id AND si.status = 'live'
                WHERE primary_parent_id IN(".implode(",", $instituteIds).")
                AND sci.status = 'live'";*/

          $sql = "SELECT 
                DISTINCT hierarchy_parent_id,
                primary_parent_id,
                si.is_autonomous,
                si.name,
                si.listing_type,
                si.is_satellite,
                si.is_aiu_membership,
                si.is_open_university,
                si.is_ugc_approved,
                si.university_specification_type,
                si.institute_specification_type,
                si.ownership,
                si.accreditation,
                si.is_autonomous,
                si.is_dummy,
                si.disabled_url
                FROM shiksha_courses_institutes sci
                LEFT JOIN shiksha_institutes si ON si.listing_id = sci.hierarchy_parent_id AND si.status = 'live'
                WHERE primary_parent_id IN (?)
                AND sci.status = 'live' order by level";


        $query = $this->dbHandle->query($sql,array($instituteIds));
        $result = $query->result_array();
        return $result;
    }

    function checkWhetherUniversityExists($universityId){
        $this->initiateModel('read');

        $sql =  "SELECT listing_id,name FROM shiksha_institutes WHERE status='live' AND listing_type = 'university' AND listing_id = ?";

        $query = $this->dbHandle->query($sql,array($universityId));
        $result = $query->result_array();
        return $result;
    }

    function getCurrentInstituteData($instituteId,$instituteType){
        
        $currentData = array();        

        $this->dbHandle->where(array('listing_type_id'=>$instituteId,'listing_type'=>$instituteType,'status'=>'live'));
        $this->dbHandle->select('listing_title,expiry_date,username,pack_type,viewCount,subscriptionId,listing_seo_title,listing_seo_description,listing_seo_url,submit_date')->limit(1);

        $currentData = $this->dbHandle->get('listings_main')->row_array();
    

        return $currentData;
    
    }

    function migrateOtherUGCModules($old_listing_id,$new_listing_id){
       $this->initiateModel('write');
       if(!empty($old_listing_id) && !empty($new_listing_id)){
            //Migrate Tag
            $sql = "UPDATE tags_entity SET entity_id = ? WHERE entity_id = ? AND entity_type IN ('institute','National-University') AND status = 'live'";
            $this->dbHandle->query($sql,array($new_listing_id,$old_listing_id));

            //Migrate Naukri Data
            $sql = "UPDATE naukri_functional_salary_data SET institute_id = ? WHERE institute_id = ?";
            $this->dbHandle->query($sql,array($new_listing_id,$old_listing_id));

            $sql = "UPDATE naukri_salary_data SET institute_id = ? WHERE institute_id = ?";
            $this->dbHandle->query($sql,array($new_listing_id,$old_listing_id));

            $sql = "UPDATE naukri_alumni_stats SET institute_id = ? WHERE institute_id = ?";
            $this->dbHandle->query($sql,array($new_listing_id,$old_listing_id));

            //Migrate ANA Data
            $sql = "UPDATE questions_listing_response SET instituteId = ? WHERE instituteId = ? AND status = 'live'";
            $this->dbHandle->query($sql,array($new_listing_id,$old_listing_id));

       }
    }


}
