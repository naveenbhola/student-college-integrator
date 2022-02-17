<?php

class CourseLevelSpecification extends CompositeSpecification
{
	function isSatisfiedBy($course)
	{
		$courseLevel = $course['filterValues'][CP_FILTER_COURSELEVEL];

		$courseLevel1 = $course['filterValues'][CP_FILTER_COURSELEVEL1];
		$courseLevel2 = $course['filterValues'][CP_FILTER_COURSELEVEL2];
		
		if($courseLevel == 'Dual Degree' && isset($this->filterValues['Dual Degree'])) {

			global $COURSE_LEVEL_LEBELS_UG;
			global $COURSE_LEVEL_LEBELS_PG;
			global $COURSE_LEVEL_LEBELS_PHD;

			if($_COOKIE['ug-pg-phd-catpage'] == 'topstudyabroadUGcourses') {

				if(in_array($courseLevel1, $COURSE_LEVEL_LEBELS_UG) || in_array($courseLevel2, $COURSE_LEVEL_LEBELS_UG)) {
					return TRUE;
				}

			} else if($_COOKIE['ug-pg-phd-catpage'] == 'topstudyabroadPGcourses') {

				if(!in_array($courseLevel1, $COURSE_LEVEL_LEBELS_UG) && !in_array($courseLevel2, $COURSE_LEVEL_LEBELS_UG)
				&& (in_array($courseLevel1, $COURSE_LEVEL_LEBELS_PG) || in_array($courseLevel2, $COURSE_LEVEL_LEBELS_PG))) {
					return TRUE;
				}

			} else if($_COOKIE['ug-pg-phd-catpage'] == 'topstudyabroadPHDcourses') {

				if((in_array($courseLevel1, $COURSE_LEVEL_LEBELS_PHD) &&  in_array($courseLevel2, $COURSE_LEVEL_LEBELS_PHD))) {
					return TRUE;
				}

			} else {

				if(isset($this->filterValues[$courseLevel])) {
					return TRUE;
				}
			}
		} else {

			if(isset($this->filterValues[$courseLevel])) {
				return TRUE;
			}
		}

		return FALSE;
	}
}