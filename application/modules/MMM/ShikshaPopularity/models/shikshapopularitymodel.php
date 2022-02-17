<?php

class shikshaPopularityModel extends MY_Model {

	private function initiateModel($operation='read'){
		
		if($operation=='read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}

	}

	function getAllLiveInstitutes($startLimit, $endLimit){
		
		$this->initiateModel();

		$sqlQuery = "SELECT distinct listing_id from shiksha_institutes 
					WHERE listing_type IN ('institute', 'university') AND status = 'live' ";

		if(isset($startLimit) && isset($endLimit)) {
			$sqlQuery .= " LIMIT ?,?";
		}

		$query = $this->dbHandle->query($sqlQuery, array((int)$startLimit, (int)$endLimit));
		$results = $query->result_array();

		foreach ($results as $result) {
			$liveInstituteIds[] = $result['listing_id'];
		}
		return $liveInstituteIds;
	}

	/**
	  * Function to get all institute hierarchy and courses
	  * @param: institute ids
	  * @return: institute data in the format - data[institute id][course_id]
	 */
	function getHierarchyAndCoursesForInstitute($instituteIdsArray){

		if(empty($instituteIdsArray)){
			return false;
		}

		$this->initiateModel();
		//$instituteIds = implode(',', $instituteIdsArray);

		$this->initiateModel('read');
		$sql =  "SELECT c.primary_id as institute_id, c.course_id , bh.hierarchy_id, bh.substream_id, bh.specialization_id, ch.base_course, ch.substream_id as course_substream_id 
				 FROM shiksha_courses c inner join shiksha_courses_type_information ch on ch.course_id = c.course_id
				 inner join base_hierarchies bh on ch.stream_id = bh.stream_id
				 WHERE c.primary_id in (?) AND c.primary_type IN ('institute', 'university') AND c.status = 'live'
				 AND ch.status = 'live' and bh.status='live' and bh.national = 1 and bh.academic = 1";

		$query = $this->dbHandle->query($sql, array($instituteIdsArray));
		$results = array();
		foreach($query->result_array() as $row) {
			if($row['base_course'] > 0) {
				if(!in_array($row['base_course'], $results[$row['institute_id']][$row['course_id']]['baseCourses'])) {
					$results[$row['institute_id']][$row['course_id']]['baseCourses'][] = $row['base_course'];
				}
			}
			if(!in_array($row['hierarchy_id'], $results[$row['institute_id']][$row['course_id']]['hierarchies'])) {
				if(($row['substream_id'] == '' && $row['specialization_id'] == '') || ($row['substream_id'] == $row['course_substream_id'] && $row['specialization_id'] == '')) {
					$results[$row['institute_id']][$row['course_id']]['hierarchies'][] = $row['hierarchy_id'];
				}
			}			
		}
		
		return $results;
	}

	function storePopularityDataCategoryInstituteWise($insertValues = array()){
		$this->initiateModel('write');
		$insertValuesString = implode(',', $insertValues);

		$sqlQuery = "INSERT INTO ShikshaPopularity_MainTable 
					( institute_id,attribute_type,attribute_id,page_views,response_count,popularity_score) 
					VALUES $insertValuesString ";

		$result = $this->dbHandle->query($sqlQuery);
	}

	function getTotalViewsOnCourse($courseIdArray){
		$this->initiateModel();

		//$courseIds = implode(',', $courseIdArray);

		$timeRange = date("Y-m-d",strtotime("-6 months"));

		// $sql ="select course_id, count(*) as count from listing_track where course_id IN (?) and visit_time >= '$timeRange' group by course_id";
		$sql ="select listing_id as course_id, sum(no_Of_Views) as count from view_Count_Details where listing_id IN (?) and view_Date >= '$timeRange' and listingType IN ('course_paid', 'course_free') group by listing_id";
		
 		$query = $this->dbHandle->query($sql, array($courseIdArray));
 		
 		$results = array();
		foreach($query->result_array() as $row) {
			$results[$row['course_id']] = $row['count'];
		}

		return $results;
	}

	function getTotalResponseOnCourse($courseIdArray){		
		$this->initiateModel();			
		
		//$courseIds = implode(',', $courseIdArray);

		$timeRange = date("Y-m-d 00:00:00",strtotime("-6 months"));

		//exclude type of responses; need to be included
		$sql ="select listing_type_id, count(*) as count from tempLMSTable where listing_type_id IN (?) 
				and action not in ('COMPARE_VIEWED', 'exam_viewed_response', 'Institute_Viewed', 'mobile_viewedListing', 'MOB_COMPARE_VIEWED', 'MOB_Institute_Viewed', 'MOB_Viewed', 'Mob_Viewed_Listing_Pre_Reg', 'NM_AlsoViewed_shortlist', 'Viewed', 'Viewed_Listing', 'Viewed_Listing_Pre_Reg', 'Viewed_Listing_sa_mobile')
				and action not like '%client%'
				and action not like '%Client%'
				and listing_type = 'course'
				and submit_date >= '$timeRange' group by listing_type_id";
		
 		$query = $this->dbHandle->query($sql, array($courseIdArray));

 		$results = array();
		foreach($query->result_array() as $row) {
			$results[$row['listing_type_id']] = $row['count'];
		}

		return $results;
	}

	function markPreviousDataHistory($instituteIds){
		$this->initiateModel('write');

		$sql = "update ShikshaPopularity_MainTable  set status ='history' where institute_id in (?)";

		$this->dbHandle->query($sql,array($instituteIds));

		//_p($this->dbHandle->last_query());
	}

	/**
	  * Function to get all configurable data by institute Id
	  * @param: institute id
	  * @return: config data
	 */
	function getConfigurableDataByInstituteId($instituteId) {
		$this->initiateModel();
		
		if(empty($instituteId)) {
			return;
		}

		$sql = "SELECT * FROM shikshaPopularityConfigurableData WHERE institute_id = ? AND status = 'live'";
		$query = $this->dbHandle->query($sql, array($instituteId));
		
		$results = array();
		foreach($query->result_array() as $row) {
			$results[$row['attribute_type']][$row['attribute_id']] = $row;
		}

		return $results;
	}

	/** 
	* Function to get all the Popularites for the Institute
	* @param: instituteId 
	*
	* @return Array with all the popularites data
	*/
	public function fetchPopularityData($instituteId=null){
		if($instituteId == null) return array();
		$this->initiateModel();
		$sql = "SELECT spmt.attribute_id, spmt.attribute_type,spmt.popularity_score, bh.stream_id, bh.substream_id from ShikshaPopularity_MainTable spmt LEFT JOIN base_hierarchies bh ON(spmt.attribute_id = bh.hierarchy_id and spmt.attribute_type = 'hierarchy' and bh.status = 'live') WHERE spmt.status = 'live' and spmt.institute_id = ?";

		$query = $this->dbHandle->query($sql,array($instituteId));
		$result = $query->result_array();

		return $result;

	}

	public function getTopCollegesByStream($streamId,$limit=3){
		if(empty($streamId)){
			return array();
		}
		$this->initiateModel();
		$sql = "SELECT distinct spmt.institute_id from ShikshaPopularity_MainTable spmt join base_hierarchies bh on bh.hierarchy_id = spmt.attribute_id and spmt.attribute_type='hierarchy' and spmt.status='live' and bh.status='live' where bh.stream_id = ? and bh.substream_id IS NULL and bh.specialization_id is null order by spmt.popularity_score desc limit ?";
		$data = $this->dbHandle->query($sql,array($streamId,(int)$limit))->result_array();
		return $this->getColumnArray($data,'institute_id');
	}

	public function fetchPopularityDataBasedonBasecourse($instituteId=array(),$baseCourseId=10) {
		if(empty($instituteId) || empty($baseCourseId)) {
			return array();
		}

		$this->initiateModel();
		$sql = "SELECT spmt.institute_id, spmt.popularity_score from ShikshaPopularity_MainTable spmt where spmt.attribute_type = 'base_course' AND spmt.status = 'live' and spmt.institute_id in (?) and spmt.attribute_id = ?";

		$query = $this->dbHandle->query($sql,array($instituteId,$baseCourseId));
		$result = $query->result_array();
		$rs = array();
		foreach ($result as $key => $value) {
			$rs[$value['institute_id']] = $value['popularity_score'];
		}
		return $rs;
	}

}

?>
	
