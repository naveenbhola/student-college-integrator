<?php

class Abroad_AlsoViewed_Model extends MY_Model
{
	function __construct()
	{
		parent::__construct('recommendation');
	}
	
	private function initiateModel($operation = 'read')
	{
		if($operation=='read'){
			$this->_db = $this->getReadHandle();
		}
		else{
        	$this->_db = $this->getWriteHandle();
		}
	}
	
	function getFilteredAlsoViewedCourses($courseIds) {
		if(!count($courseIds) || empty($courseIds)) {
			return;
		}
		
		$this->initiateModel();
		
		$sql = "SELECT recommended_course_id, recommended_institute_id, weight, mapping_type, filter_type
			FROM alsoViewedFilteredCourses, course_details
			WHERE alsoViewedFilteredCourses.course_id IN (".implode(',', $courseIds).")
			AND alsoViewedFilteredCourses.status = 'live'
			AND course_details.course_id = alsoViewedFilteredCourses.recommended_course_id
			AND course_details.status = 'live'
			ORDER BY alsoViewedFilteredCourses.id ASC";
		
		return $this->_db->query($sql)->result_array();
	}
	
	function getAlsoViewedListings($courseIds , $filtering, $exclusion_list)
	{
		if(!count($courseIds)) {
			return;
		}
		
		$alsoViewedEntities = $this->getAlsoViewedEntities($courseIds, $exclusion_list);
		
		$alsoViewedCourseIds = array();
		
		foreach($alsoViewedEntities as $listingTypeId => $alsoViewedRows) {
			foreach($alsoViewedRows as $alsoViewedRow) {
				$alsoViewedCourseIds[] = $alsoViewedRow['also_viewed_id'];
			}
		}
		
		$alsoViewedCourseIds = array_unique($alsoViewedCourseIds);
		
		$alsoViewedCourseIds = $this->getLiveCourses($alsoViewedCourseIds);
		
		$allCourseIds = array_values(array_unique(array_merge($courseIds, $alsoViewedCourseIds)));
		
		$courseInstituteMapping = $this->getInstituteForCourse($allCourseIds);
		
		$courseData = $this->getCourseData($allCourseIds);	
		
		foreach($alsoViewedEntities as $listingTypeId => $alsoViewedRows) {
			$source = array();
			$source[] = $listingTypeId;
			foreach($alsoViewedRows as $alsoViewedRow) {
				$courseId = $alsoViewedRow['also_viewed_id'];
				if(!isset($courseData[$courseId]['source'])) {
					$courseData[$courseId]['source'] = $source;
				}
				else {
					$courseData[$courseId]['source'] = array_values(array_unique(array_merge($courseData[$courseId]['source'], $source)));
				}
			}
		}
		
		$filterData = array();
		
		foreach($courseIds as $id) {
			$data = $courseData[$id];
			
			if(!in_array($data['institute_id'], $filterData['institute_id'])) {
				$filterData[$id]['institute_id'] = $data['institute_id'];
			}
			
			if(!in_array($data['country_id'], $filterData['country_id'])) {
				$filterData[$id]['country_id'] = $data['country_id'];
			}
			
			foreach($data['category_id'] as $category) {
				if(!in_array($category, $filterData[$id]['category_id'])) {
					$filterData[$id]['category_id'][] = $category;
				}
			}
			
			foreach($data['ldb_course_id'] as $ldb_course) {
				if(!in_array($ldb_course, $filterData[$id]['ldb_course_id'])) {
					$filterData[$id]['ldb_course_id'][] = $ldb_course;
				}
			}
			
			if(!in_array($data['course_type'], $filterData['course_type'])) {
				$filterData[$id]['course_type'] = $data['course_type'];
			}
			
			if(!in_array($data['course_level'], $filterData['course_level'])) {
				$filterData[$id]['course_level'] = $data['course_level'];
			}
			
			if(!in_array($data['course_level_1'], $filterData['course_level_1'])) {
				$filterData[$id]['course_level_1'] = $data['course_level_1'];
			}
		}
		
		$filteredCourseIds = array();
		
		foreach($courseData as $courseId=>$data) {
			foreach($data['source'] as $sourceId) {
				$courseFilter = $filterData[$sourceId];
				$filtered = 1;
				
				if($data['institute_id'] == $courseFilter['institute_id']) {
					$filtered = 0;
				}
				
				if($courseFilter['country_id'] == 2) {
					if($data['country_id'] != $courseFilter['country_id']) {
						$filtered = 0;
					}
				}
				
				if($filtering == 'ldb') {
					$isLdbFlag = false;
					foreach($data['ldb_course_id'] as $ldb) {
						if(in_array($ldb, $courseFilter['ldb_course_id'])) {
							$isLdbFlag = true;
							break;
						}
					}
					
					if(!$isLdbFlag) {
						$filtered = 0;
					}
				}
				else if($filtering == 'category') {
					$isCategoryFlag = false;
					foreach($data['category_id'] as $category) {
						if(in_array($category, $courseFilter['category_id'])) {
							$isCategoryFlag = true;
							break;
						}
					}
					
					if(!$isCategoryFlag) {
						$filtered = 0;
					}
				}
				
				if($data['course_type'] != $courseFilter['course_type']) {
					$filtered = 0;
				}
				
				if($data['course_level'] != $courseFilter['course_level']) {
					$filtered = 0;
				}
				
				if($data['course_level_1'] != $courseFilter['course_level_1']) {
					$filtered = 0;
				}
				
				if($filtered) {
					$filteredCourseIds[$sourceId][] = $courseId;
				}
			}
		}		
		
		//code added for also viewed graph search
		$alsoViewedCourseMapping = array();
		$alsoViewedCourseIds = array();
		$alsoViewedCourses = array();
		foreach($filteredCourseIds as $sourceId => $filterIds) {
			usleep(600000); // stopping query bombarding on DB
			$alsoViewedCourseMapping += $this->getAlsoViewedCourseIds($filterIds);
		}
		foreach($alsoViewedCourseMapping as $courseId => $alsoViewedCourses) {
			$alsoViewedCourseIds += $alsoViewedCourses;
		}
		$alsoViewedCourseMapping += $this->getAlsoViewedCourseIds($alsoViewedCourseIds);
		
		
		$alsoViewed = array();
		
		foreach($alsoViewedEntities as $listingTypeId => $alsoViewedRows) {
			$sourceId = $listingTypeId;
			$filterIds =  $filteredCourseIds[$sourceId];
			foreach($alsoViewedRows as $row) {
				if(in_array($row['also_viewed_id'], $filterIds)) {
					$alsoViewedObject = new stdClass();
					$alsoViewedObject->source = intval($sourceId);
					$alsoViewedObject->also_viewed_id = intval($row['also_viewed_id']);
					$alsoViewedObject->also_viewed_listing_type = 'course';
					$alsoViewedObject->weight = floatval($row['weight']);
					$alsoViewedObject->institute_id = intval($courseInstituteMapping[$row['also_viewed_id']]);
					$alsoViewedObject->course_id = intval($row['also_viewed_id']);
					$alsoViewed[] = $alsoViewedObject;
					
					//code added for also viewed graph search
					if(in_array($sourceId, $alsoViewedCourseMapping[$alsoViewedObject->course_id])) {
						$alsoViewedObject->result_type = 'back_mapping';
					}
					else {
						$alsoViewedCourseIds = array();
						$alsoViewedCourseIds = $alsoViewedCourseMapping[$alsoViewedObject->course_id];
						foreach($alsoViewedCourseIds as $courseId) {
							if(in_array($sourceId, $alsoViewedCourseMapping[$courseId])) {
								$alsoViewedObject->result_type = 'triangle_mapping';
								break;
							}
						}
					}
					if(!isset($alsoViewedObject->result_type)) {
						$alsoViewedObject->result_type = 'no_mapping';
					}
				}
			}
		}
		
		return $alsoViewed;
	}
	
	function getLiveCourses($courseIds)
	{
		$this->initiateModel();
		
		$liveCourseIds = array();
		
		if(is_array($courseIds) && count($courseIds))
		{
			$query = $this->_db->query("SELECT course_id
						    FROM course_details
						    WHERE course_id IN (".implode(",", $courseIds).")
						    AND status = 'live'");
			
			$rows = $query->result();
			
			foreach($rows as $row) {
				$liveCourseIds[] = $row->course_id;
			}
		}
		
		return $liveCourseIds;
	}
	
	function getInstituteForCourse($courseIds)
	{
		$this->initiateModel();
		
		$instituteMapping = array();
		
		if(is_array($courseIds) && count($courseIds))
		{
			$query = $this->_db->query("SELECT DISTINCT course_id, institute_id
						    FROM course_details
						    WHERE course_id IN (".implode(",", $courseIds).")
						    AND status = 'live'");
			
			$rows = $query->result();
			
			foreach($rows as $row) {
				$instituteMapping[$row->course_id] = $row->institute_id;
			}
		}
		
		return $instituteMapping;
	}
	
	function getAlsoViewedEntities($courseIds, $exclusion_list = array())
	{
		$this->initiateModel();
		
		$alsoViewedEntities = array();
		
		if(is_array($courseIds) && count($courseIds))
		{
			if(count($exclusion_list)) {
				$query = $this->_db->query("SELECT course_id
							    FROM course_details
							    WHERE institute_id IN (".implode(",", $exclusion_list).")");
				$rows = $query->result();
				
				$excludedCourses = array();
				foreach($rows as $row) {
					$excludedCourses[] = $row->course_id;
				}
				
				$exclusionClause = '';
				if(count($excludedCourses)) {
					$exclusionClause = " AND also_viewed_id NOT IN (".implode(",", $excludedCourses).")";
				}
			}
			
			$query = $this->_db->query("SELECT listing_type_id, also_viewed_id, weight
						    FROM also_viewed_listings
						    WHERE listing_type_id IN (".implode(",", $courseIds).")".$exclusionClause);
			
			$rows = $query->result();
			
			$alsoViewedEntities = array();
			
			foreach($rows as $row) {
				$alsoViewed = array();
				$alsoViewed['also_viewed_id'] = $row->also_viewed_id;
				$alsoViewed['weight'] = $row->weight;
				$alsoViewedEntities[$row->listing_type_id][] = $alsoViewed;
			}
		}
		
		return $alsoViewedEntities;
	}
	
	function getCourseData($courseIds)
	{
		$this->initiateModel();
		
		$courseData = array();
		
		if(is_array($courseIds) && count($courseIds))
		{
			$nationalCourseIds = array();
			$abroadCourseIds = array();
			$tablesForData = array();
			$dataColumns = array();
			
			$query = $this->_db->query("SELECT course_id
						    FROM categoryPageData
						    WHERE course_id IN (".implode(",", $courseIds).")
						    AND status = 'live'");
			
			$rows = $query->result();
			
			foreach($rows as $row) {
				$nationalCourseIds[] = $row->course_id;
			}
			
			$abroadCourseIds = array_diff($courseIds, $nationalCourseIds);
			
			if(count($nationalCourseIds) > 0) {
				$tablesForData['categoryPageData'] = 'course_id, category_id, ldb_course_id, institute_id, country_id';
			}
			if(count($abroadCourseIds) > 0) {
				$tablesForData['abroadCategoryPageData'] = 'course_id, sub_category_id as category_id, ldb_course_id, institute_id, country_id';
			}
			
			foreach($tablesForData as $tableName => $columns) {
				if($tableName == 'categoryPageData') {
					$courseIdArray = $nationalCourseIds;
				}
				else if($tableName == 'abroadCategoryPageData') {
					$courseIdArray = $abroadCourseIds;
				}
				
				$query = $this->_db->query("SELECT ".$columns."
						    FROM ".$tableName."
						    WHERE course_id IN (".implode(",", $courseIdArray).")
						    AND status = 'live'");
				
				$rows = $query->result();
				
				foreach($rows as $row) {
					$courseData[$row->course_id]['institute_id'] = $row->institute_id;
					if(!in_array($row->category_id, $courseData[$row->course_id]['category_id'])) {
						$courseData[$row->course_id]['category_id'][] = $row->category_id;
					}
					if(!in_array($row->ldb_course_id, $courseData[$row->course_id]['ldb_course_id'])) {
						$courseData[$row->course_id]['ldb_course_id'][] = $row->ldb_course_id;
					}
					$courseData[$row->course_id]['country_id'] = $row->country_id;
				}
			}
			
			$query = $this->_db->query("SELECT course_id, null as course_type, course_level, course_level_1
						    FROM course_details
						    WHERE course_id IN (".implode(",", $courseIds).")
						    AND status = 'live'");
			
			$rows = $query->result();
			
			foreach($rows as $row) {
				$courseData[$row->course_id]['course_type'] = $row->course_type;
				$courseData[$row->course_id]['course_level'] = $row->course_level;
				$courseData[$row->course_id]['course_level_1'] = $row->course_level_1;
			}
		}
		
		return $courseData;
	}
	
	function getAlsoViewedCourseIds($courseIds)
	{
		$this->initiateModel();
		
		$alsoViewedCourseIds = array();
		
		if(is_array($courseIds) && count($courseIds))
		{
			$query = $this->_db->query("SELECT course_id, also_viewed_courses
						    FROM also_viewed_course_mapping
						    WHERE course_id IN (".implode(",", $courseIds).")");
			
			$rows = $query->result();			
			
			foreach($rows as $row) {
				$alsoViewedCourseIds[$row->course_id] = json_decode($row->also_viewed_courses);
			}
		}
		
		return $alsoViewedCourseIds;
	}
}
