<?php

class AlsoViewed_Model extends MY_Model
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

	function getFilteredAlsoViewedCourses($courseIds)
	{
		if(!count($courseIds) || empty($courseIds)) {
			return;
		}

		$this->initiateModel();

		$sql = "SELECT recommended_course_id as courseId, recommended_institute_id as instituteId
				FROM alsoViewedFilteredCourses, shiksha_courses
				WHERE alsoViewedFilteredCourses.course_id IN (".implode(',', $courseIds).")
				AND alsoViewedFilteredCourses.status = 'live'
				AND shiksha_courses.course_id = alsoViewedFilteredCourses.recommended_course_id
				AND shiksha_courses.status = 'live'
				ORDER BY alsoViewedFilteredCourses.weight DESC";

		return $this->_db->query($sql)->result_array();
	}

	function getAlsoViewedCoursesWithWeight($courseIds, $exclusionList = array())
	{
		$this->initiateModel();

		$alsoViewedCourses = array();

		if(is_array($courseIds) && count($courseIds))
		{
            $coursesToBeExcluded = $this->_getCoursesToBeExcluded($exclusionList);
            $exclusionClause = "";
            if(count($coursesToBeExcluded) > 0) {
                $exclusionClause = " AND also_viewed_id NOT IN (".implode(",", $coursesToBeExcluded).")";
            }

			$query = $this->_db->query("SELECT listing_type_id, also_viewed_id, weight
						    FROM also_viewed_listings a
							INNER JOIN shiksha_courses b ON a.listing_type_id = b.course_id
						    WHERE a.listing_type_id IN (".implode(",", $courseIds).")
							AND a.listing_type = 'course'
							AND a.also_viewed_listing_type = 'course'
							AND b.status = 'live'
							".$exclusionClause);

			//$query = $this->_db->query("SELECT ".$courseIds[0]." as listing_type_id, course_id as also_viewed_id, 1 as weight
			//			    FROM shiksha_courses
			//				WHERE status = 'live'");
							
			$rows = $query->result_array();

			foreach($rows as $row) {
				$alsoViewedCourses[$row['listing_type_id']][$row['also_viewed_id']] = $row['weight'];
			}
		}

//		$alsoViewedCourses = array(
//			250049 => array(
//                250081 => 1,
//                250047 => 2
//			)
//		);

		return $alsoViewedCourses;
	}

    private function _getCoursesToBeExcluded($exclusionList)
    {
        /**
         * If exclusion list is provided (in form of institute ids)
         * then exclude courses belonging to these institutes
         */
        $coursesToBeExcluded = array();

        if(is_array($exclusionList) && count($exclusionList) > 0) {
            /**
             * Get all live courses of institutes to be excluded
             */
            $query = $this->_db->query("SELECT DISTINCT course_id
                                        FROM shiksha_courses
                                        WHERE parent_id IN (".implode(",", $exclusionList).")
                                        AND status = 'live'");
            $rows = $query->result_array();

            $excludedCourses = array();
            foreach($rows as $row) {
                $coursesToBeExcluded[] = $row['course_id'];
            }
        }

        return $coursesToBeExcluded;
    }
}
