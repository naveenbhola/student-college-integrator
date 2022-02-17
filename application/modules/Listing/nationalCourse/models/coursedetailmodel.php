<?php

class coursedetailmodel  extends MY_Model {
	private $status = 'live';
	private $dbHandle;

	function __construct() {
		parent::__construct('Listing');
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

    public function getPrimaryInstituteForCourse($courseIds){

        $this->initiateModel('read');

        $result = array();
        if(empty($courseIds))
            return $result;


        $sql = "SELECT primary_id, primary_type FROM shiksha_courses where course_id in (?) and status = 'live'";

        $result = $this->dbHandle->query($sql, array($courseIds))->result_array();

        return $result;
    }

    public function getCourseForInstituteLocationWise($instituteId, $locationIds){
    	$data = $this->_getCourseForInstituteLocationWise($instituteId, $locationIds);
 
    	$result = array();
    	foreach ($data as $courseLocationData) {
			$result[$courseLocationData['listing_location_id']][] = $courseLocationData['course_id'];
    	}
    	return $result;
    }

    private function _getCourseForInstituteLocationWise($instituteId, $locationIds){
    	$this->initiateModel('read');

        $params = array($instituteId);
        if(!empty($locationIds)){
            $params[] = $locationIds;
        }

    	$sql = "SELECT course_id,
    			sil.listing_location_id 
                FROM shiksha_institutes_locations sil
    			JOIN shiksha_courses_locations scl 
                ON scl.listing_location_id = sil.listing_location_id
    			WHERE scl.status = 'live'
                AND sil.listing_id = ?
                AND sil.status = 'live'";

        if(!empty($locationIds)){
            $sql .= " AND scl.listing_location_id IN (?)";
        }
    
    	$result = $this->dbHandle->query($sql,$params)->result_array();
    	return $result;
    }

    /*public function getClientCoursesFromHierarchies($hierarchyArr,$hierarchyType){

		$whereClause = array();
		
		$sql = "SELECT DISTINCT course_id,
				type, stream_id, 
				substream_id, specialization_id 
				FROM shiksha_courses_type_information 
				WHERE status = 'live'";

		foreach ($hierarchyArr as $hierarchy) {
			$where = array();
			 $where[] = 'stream_id = '.$hierarchy['streamId'];

			 //where for substream
             if($hierarchy['substreamId'] == 'none') {
                 $where[] = 'substream_id = 0';
             } elseif(empty($hierarchy['substreamId']) || $hierarchy['substreamId'] == 'any') {
                 // no check
             } else {
                 $where[] = "substream_id = ".$hierarchy['substreamId'];
             }

                         //where for specialization
            if($hierarchy['specializationId'] == 'none') {
                $where[] = 'specialization_id = 0';
            } elseif(empty($hierarchy['specializationId']) || $hierarchy['specializationId'] == 'any') {
                // no check
            } else {
                $where[] = "specialization_id = ".$hierarchy['specializationId'];
            }
            
            $whereClause[] = '('.implode(' AND ', $where).')';
		}
        if(count($whereClause) > 0) {
            $whereClause = '('.implode(' OR ', $whereClause).')';
        }
        if(empty($hierarchyType)){
        	$hierarchyType = array('entry','exit');
        }

        $whereClause = $whereClause." AND type IN ('".implode("','", $hierarchyType)."')";

        $sql = $sql." AND ".$whereClause;
        return $data;
	}*/

    public function fetchAllCourseStatus(){
        $this->initiateModel('read');
        $sql = "SELECT * from base_attribute_list where status = 'live' and attribute_name = 'Course Status'";
        $query = $this->dbHandle->query($sql);
        $result = $query->result_array();
        $finalResult = array();
        foreach ($result as $key => $value) {
            $finalResult[$value['value_name']] = $value['value_id'];
        }
        
        return $finalResult;
    }

    public function checkForDeletedCourse($courseId){
        $this->initiateModel('read');
        
        $sql    = "SELECT status FROM shiksha_courses WHERE course_id = ? and status = ? ";
        $result = $this->dbHandle->query($sql,array($courseId,'deleted'))->result_array();
        
        if(!empty($result) && $result[0]['status'] == 'deleted'){
            $sql     = "SELECT replacement_lisiting_type_id FROM deleted_listings_mapping_table WHERE listing_type_id = ? AND listing_type=? ORDER BY id desc limit 1";
            $result1 = $this->dbHandle->query($sql,array($courseId,'course'))->result_array();

            if(!empty($result1)){
                $listingMainStatus= $this->config->item("listingMainStatus");

                $sql     = "SELECT listing_seo_url FROM listings_main WHERE listing_type_id = ? AND listing_type =? AND status = ? ";
                $result2 = $this->dbHandle->query($sql,array($result1[0]['replacement_lisiting_type_id'],'course',$listingMainStatus['live']))->result_array();
                
            }
        }

        return $result2[0]['listing_seo_url'];
    }

	public function getFeesDescription($courseId){
        $this->initiateModel('read');

        $sql = "SELECT `other_info` FROM (`shiksha_courses_fees`) WHERE `status` = 'live' AND `course_id` = ? AND `listing_location_id` = -1 AND `other_info` IS NOT NULL";

        $result = $this->dbHandle->query($sql,array($courseId))->result_array();
        if(!empty($result)){
            return $result[0]['other_info'];
        }
        return '';
    }

    public function getEligibilityMainData($courseId){
        $this->initiateModel('read');
        $sql1 = "SELECT batch_year,subjects, age_min,age_max,`work-ex_min`,`work-ex_max`,international_students_desc,description FROM shiksha_courses_eligibility_main WHERE course_id = ? AND status = 'live' ";
        return $this->dbHandle->query($sql1,$courseId)->row_array();
    }

    public function getEligibilityBaseEntities($courseId){
        $this->initiateModel('read');
        $sql2 = "SELECT base_course, specialization, type ".
               "FROM shiksha_courses_eligibility_base_entities ".
               "WHERE course_id = ? AND status = 'live' ";

        return $this->dbHandle->query($sql2,$courseId)->result_array();
    }

    public function getEligibilityScores($courseId){
        $this->initiateModel('read');
        $sql = "SELECT * ".
               "FROM shiksha_courses_eligibility_score ".
               "WHERE course_id = ? AND status = 'live' ";

        return $this->dbHandle->query($sql,array($courseId))->result_array();
    }

    public function getEligibilityExamScores($courseId){
        $this->initiateModel('read');
        $sql4 = "SELECT exam_id,exam_name,category,value,unit,max_value from shiksha_courses_eligibility_exam_score ".
                "where course_id = ? and status = 'live'";
        return $this->dbHandle->query($sql4,array($courseId))->result_array();
    }

    public function getEligibilityExamCutOffs($courseId){
        $this->initiateModel('read');
        $sql3 = "SELECT quota,category, exam_year,cut_off_value, cut_off_type,exam_out_of,cut_off_unit,round,custom_exam,exam_id ".
               "FROM shiksha_courses_exams_cut_off ".
               "WHERE course_id = ? AND status = 'live' ";

        return $this->dbHandle->query($sql3,array($courseId))->result_array();
    }

    public function getEligibilityData($course_id){
        $this->initiateModel('read');
        $sql = "SELECT * ".
               "FROM shiksha_courses_eligibility_score ".
               "WHERE course_id = ? AND status = 'live' ";

        $query = $this->dbHandle->query($sql,array($course_id,$category,$course_id))->result_array();
        if(!empty($query)){
            $result['categoryWise'] = $query;
        }
        // error_log($this->dbHandle->last_query());   
        //get 12th mandatory subjects
        $sql1 = "SELECT batch_year,subjects, age_min,age_max,`work-ex_min`,`work-ex_max`,international_students_desc,description ".
               "FROM shiksha_courses_eligibility_main ".
               "WHERE course_id = ? AND status = 'live' ";

        $query = $this->dbHandle->query($sql1,$course_id)->row_array();
        if(!empty($query)){
            $result['basic'] = $query;
        }
        
        //get graduation and post graduation course and specialization
        $sql2 = "SELECT base_course, specialization, type ".
               "FROM shiksha_courses_eligibility_base_entities ".
               "WHERE course_id = ? AND status = 'live' ";

        $graduationSubject = $this->dbHandle->query($sql2,$course_id)->result_array();
        if(!empty($graduationSubject)){
            foreach ($graduationSubject as $key => $val) {
                $result['graduationSubject'][$val['type']][$val['base_course']][] = $val['specialization'];
            }
        }

        //get 12th cutoff data
        $sql3 = "SELECT quota,category, exam_year,cut_off_value, cut_off_type,exam_out_of,cut_off_unit,round,custom_exam,exam_id ".
               "FROM shiksha_courses_exams_cut_off ".
               "WHERE course_id = ? AND status = 'live' ";

        $query = $this->dbHandle->query($sql3,array($course_id))->result_array();
        if(!empty($query)){
            $result['cutoff'] = $query;
        }

        $sql4 = "SELECT exam_id,exam_name,category,value,unit,max_value from shiksha_courses_eligibility_exam_score ".
                "where course_id = ? and status = 'live'";
        $examEligibility = $this->dbHandle->query($sql4,array($course_id))->result_array();
        if(!empty($examEligibility)){
            $result['examEligibility'] = $examEligibility;
        }
        
        return $result;
    }

    public function getEligibilityCategories($courseId){
        $this->initiateModel('read');
        $query = $this->dbHandle->distinct()->select('category')->where(array('course_id'=>$courseId,'status'=>'live'))->get('shiksha_courses_eligibility_score')->result_array();
        $categories = $this->getColumnArray($query,'category');

        $query = $this->dbHandle->distinct()->select('category')->where(array('course_id'=>$courseId,'status'=>'live'))->get('shiksha_courses_exams_cut_off')->result_array();
        $query = $this->getColumnArray($query,'category');
        $categories = array_merge($categories,$query);

        $query = $this->dbHandle->distinct()->select('category')->where(array('course_id'=>$courseId,'status'=>'live'))->get('shiksha_courses_eligibility_exam_score')->result_array();
        $query = $this->getColumnArray($query,'category');
        $categories = array_merge($categories,$query);

        return array_unique(array_values(array_filter($categories)));
    }



    /**
     * @param $courseId
     * @param $selectedCategory
     *
     * @return bool|array False in case of no results; array containing the results otherwise
     */
    public function getFeesBasedOnSelection($courseId, $selectedCategory){
        $this->initiateModel('read');

        if(!is_array($selectedCategory)) {
            $selectedCategory = array($selectedCategory);
        }
        
        if(empty($selectedCategory)) {
            return false;
        }
        
        $sql = "SELECT category, fees_value, other_info,fees_type, currency_code, period, `order` FROM (`shiksha_courses_fees`) LEFT JOIN currency ON shiksha_courses_fees.fees_unit = currency.id WHERE `status` = 'live' AND `course_id` = ? AND `listing_location_id` = -1 AND ((category IN (?) AND ( (fees_type = 'total' AND period IN ('otp' , 'overall', 'year','trimester','semester','term','installment') ) OR (fees_type = 'hostel'))) OR other_info is not null);";

        $result = $this->dbHandle->query($sql,array($courseId,$selectedCategory))->result_array(); 
        if(!empty($result)){
            return $result;
        }

        return false;
    }

    public function getCourseStructureData($courseId){
        $this->initiateModel('read');
        $sql = "SELECT period,period_value,courses_offered FROM (`shiksha_courses_structure_offered_courses`) WHERE course_id = ? AND status = 'live' ";
        $result = $this->dbHandle->query($sql,array($courseId))->result_array();

        if(!empty($result)){
            return $result;
        }

        return false;
    }

    public function getAdmissionsData($courseId){
        $this->initiateModel('read');
        $sql = "SELECT admission_name,admission_name_other,admission_description,stage_order FROM (`shiksha_courses_admission_process`) WHERE course_id = ? AND status = 'live' order by stage_order asc";
        $result = $this->dbHandle->query($sql,array($courseId))->result_array();
         if(!empty($result)){
            return $result;
        }

        return false;
    }

    public function getPlacementsData($courseId){
        $this->initiateModel('read');
        $sql = "SELECT batch_year,course,course_type,batch_year, percentage_batch_placed,min_salary,median_salary,avg_salary,max_salary, total_international_offers,max_international_salary  FROM (`shiksha_courses_placements_internships`) ".
               " WHERE course_id = ? AND type = ? AND status = 'live' ";

        $result = $this->dbHandle->query($sql,array($courseId,'placements'))->row_array();
         if(!empty($result)){
            return $result;
        }

        return false;
    }

    public function getCompaniesMapped($courseId){
        $this->initiateModel('read');
        $sql = "SELECT cl.company_name,cl.logo_url  FROM shiksha_courses_companies_mapping ccm ".
               " JOIN company_logos cl ".
               " ON ccm.company_id = cl.id ".
               " WHERE ccm.course_id = ? AND ccm.status = 'live' AND cl.status = 'live' GROUP BY cl.id ORDER BY cl.company_name ASC";

        $result = $this->dbHandle->query($sql,array($courseId))->result_array();
         if(!empty($result)){
            return $result;
        }

        return false;   
    }

    public function getCourseSeats($courseId){
        $this->initiateModel('read');
        $sql = "SELECT breakup_by,exam_id,category,seats,related_state_list  FROM shiksha_courses_seats_breakup  ".               
               " WHERE course_id = ? AND status = 'live' AND seats != '' ".
               " ORDER BY seats desc";

        $result = $this->dbHandle->query($sql,array($courseId))->result_array();
         if(!empty($result)){
            return $result;
        }

        return false;   
    }

    public function getCourseImportantDates($courseId){
        $this->initiateModel('read');
        $result = $this->dbHandle->select("event_name,start_date,start_month,start_year,end_date,end_month,end_year")->where(array('course_id'=>$courseId,'status'=>'live'))->get('shiksha_courses_important_dates')->result_array();

        return $result;
    }

    public function getExamDates($examIds){
        $this->initiateModel('read');
        $query = "SELECT ed.start_date,ed.end_date,ed.event_name,em.id as exam_id,em.name as exam_name 
                  FROM exampage_content_dates ed JOIN exampage_master epm ON epm.exampage_id = ed.page_id JOIN exampage_main em ON epm.exam_id= em.id
                  WHERE em.status = 'live' AND ed.status = 'live' AND epm.status = 'live' AND em.id in (?) GROUP BY event_name,start_date";
        $result = $this->dbHandle->query($query,array($examIds))->result_array();
        $returnArray = array();
        foreach ($result as $row) {
            if(!empty($row['start_date']) && !empty($row['end_date'])){
                $startDate = DateTime::createFromFormat('Y-m-d',$row['start_date']);
                $endDate   = DateTime::createFromFormat('Y-m-d',$row['end_date']);
                $temp = array();
                $temp['event_name']  = $row['event_name'];
                $temp['start_date']  = $startDate->format('j');
                $temp['start_month'] = $startDate->format('n');
                $temp['start_year']  = $startDate->format('Y');
                $temp['end_date']    = $endDate->format('j');
                $temp['end_month']   = $endDate->format('n');
                $temp['end_year']    = $endDate->format('Y');
                $temp['exam_id']     = $row['exam_id'];
                $temp['type']        = 'exam';
                $temp['exam_name']   = $row['exam_name'];
                $returnArray[] = $temp;
            }
        }

        return $returnArray;
    }

    public function filterCoursesWithAdmissionDetails($courseIds){

        $result = array();

        if(empty($courseIds))
            return $result;

        $this->initiateModel('read');
        $sql = "SELECT distinct(course_id) FROM shiksha_courses_admission_process WHERE course_id IN (?) AND status = 'live'";
        $rs = $this->dbHandle->query($sql,array($courseIds))->result_array();
         if(!empty($rs)){
            foreach ($rs as $value) {
                $result[] = $value['course_id'];
            }
        }

        return $result;
    }

    public function getCoursesFromExams($examIds) {
        if(empty($examIds)) {
            return;
        }

        $this->initiateModel('read');

        $this->dbHandle->distinct();

        $this->dbHandle->select('ex.exam_id, ex.course_id, sc.primary_id');

        $this->dbHandle->from('shiksha_courses_eligibility_exam_score ex');

        $this->dbHandle->join('shiksha_courses as sc', "sc.course_id = ex.course_id and sc.status = 'live'");

        $this->dbHandle->where('ex.status', 'live');

        $this->dbHandle->where_in("ex.exam_id", $examIds);

        $result = $this->dbHandle->get()->result_array();

        return $result;
    }

    public function getCoursesFromBaseCourses($baseCourseIds) {
        if(empty($baseCourseIds)) {
            return;
        }

        $this->initiateModel('read');

        $this->dbHandle->distinct();

        $this->dbHandle->select('ti.base_course, ti.course_id, sc.primary_id');

        $this->dbHandle->from('shiksha_courses_type_information ti');

        $this->dbHandle->join('shiksha_courses as sc', "sc.course_id = ti.course_id and sc.status = 'live'");

        $this->dbHandle->where('ti.status', 'live');
        $this->dbHandle->where('ti.type', 'entry');

        $this->dbHandle->where_in("ti.base_course", $baseCourseIds);

        $result = $this->dbHandle->get()->result_array();

        return $result;
    }

    public function filterCoursesBasedOnHeirarchy($courseIds, $criteria) {
        $this->initiateModel('read');

        $this->dbHandle->distinct();

        $this->dbHandle->select('ti.course_id');

        $this->dbHandle->from('shiksha_courses_type_information ti');

        $this->dbHandle->join('shiksha_courses as sc', "sc.course_id = ti.course_id and sc.status = 'live'");

        $this->dbHandle->where('ti.status', 'live');
        $this->dbHandle->where('ti.type', 'entry');

        if(!empty($criteria['stream_id'])) {
            $this->dbHandle->where_in("ti.stream_id", $criteria['stream_id']);
        }

        if(!empty($criteria['base_course_id'])) {
            $this->dbHandle->where_in("ti.base_course", $criteria['base_course_id']);
        }

        if(!empty($courseIds)) {
            $this->dbHandle->where_in("sc.course_id", $courseIds);
        }

        $result = $this->dbHandle->get()->result_array();

        return $result;
    }
    public function getCourseName($courseIds){
        $this->initiateModel('read');

        $sql = "select course_id, name as course_name from shiksha_courses where course_id in (?) and status = 'live' order by name asc";
        $result = $this->dbHandle->query($sql,array($courseIds))->result_array();
        return $result; 
    }

    public function getCourseInstituteHeirarchy($courseIds,$status){
        $this->initiateModel('read');
        $sql = "select distinct hierarchy_parent_id from shiksha_courses_institutes where course_id IN (?) ";
        $result = $this->dbHandle->query($sql,array($courseIds))->result_array();
        $instituteIds = array();
        foreach ($result as $key => $value) {
           $instituteIds[] = $value['hierarchy_parent_id'];
        }
        return $instituteIds; 
    }

    public function getAffiliatedUniversityOfCourse($courseIds){
        $this->initiateModel('read');
        $sql = "select distinct affiliated_university_id from shiksha_courses where course_id IN (?) ";
        $result = $this->dbHandle->query($sql,array($courseIds))->result_array();
        $instituteIds = array();
        foreach ($result as $key => $value) {
           $instituteIds[] = $value['affiliated_university_id'];
        }
        return $instituteIds; 
    }

    public function getTopHierarchyInstitutesForCourses($courseIds){
        $resultArray = array();
        if(!is_array($courseIds) || empty($courseIds)){
            return $resultArray;
        }
        $this->initiateModel("read");
        $sql = "select course_id, hierarchy_parent_id from shiksha_courses_institutes where status = 'live' and level = 0 and course_id IN (?)";
        $result = $this->dbHandle->query($sql, array($courseIds))->result_array();
        foreach ($result as $value){
            $resultArray[$value['course_id']] = $value['hierarchy_parent_id'];
        }
        return $resultArray;
    }
}

