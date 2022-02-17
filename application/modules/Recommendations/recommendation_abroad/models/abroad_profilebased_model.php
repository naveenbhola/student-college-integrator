<?php 

class Abroad_ProfileBased_Model extends MY_Model
{
	private $_recommendation_log;
	private $_userinfo_cache = array();
	
	function __construct()
	{
		parent::__construct('recommendation');
	}
	
	function init($db_handle,$recommendation_log)
	{
		$this->_db = $db_handle;
		$this->_recommendation_log = $recommendation_log;
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
	
	function getUserProfileInfo($users)
	{
		$this->initiateModel();
		
		$user_info = array();
	
		if(is_array($users) && count($users))
		{
			foreach ($users as $user_id)
			{
				$user_info[$user_id] = array();
				$user_info[$user_id]['preferred_countries'] = array();
				$user_info[$user_id]['preferred_cities'] = array();
				$user_info[$user_id]['preferred_cities_unaltered'] = array();
				$user_info[$user_id]['preferred_states'] = array();
				$user_info[$user_id]['preferred_localities'] = array();
				$user_info[$user_id]['mode_of_learning'] = array();
				$user_info[$user_id]['shiksha_courses'] = array();
				$user_info[$user_id]['exam'] = array();
			}
		}
		else 
		{	
			return false;
		}
	
		$user_ids = "(".implode(',',$users).")";
		
		/*
		 * Residence city & registration source
		 */
		$query = $this->_db->query("SELECT tu.userid,tu.City,tu.displayname,tu.email,tu.usercreationDate,tu.randomkey,si.keyid,si.referer
					 	   			FROM tuser tu
					 	   			LEFT JOIN tusersourceInfo si ON si.userid = tu.userid
					 	   			WHERE tu.userid IN $user_ids ");
		
		$rows = $query->result();
		
		foreach ($rows as $row)
		{
			$user_info[$row->userid]['displayname'] = $row->displayname;
			$user_info[$row->userid]['email'] = $row->email;
			$user_info[$row->userid]['residence_city'] = (int) $row->City;
			$user_info[$row->userid]['registration_source'] = $row->keyid == 139?'marketing_page':'other';
			$user_info[$row->userid]['registration_key'] = (int) $row->keyid;
			$user_info[$row->userid]['registration_referer'] = $row->referer;
			$user_info[$row->userid]['hash'] = sha1($row->email.";".$row->usercreationDate.";".$row->randomkey);
			$user_info[$row->userid]['userid'] = $row->userid;
		}
		
		/**
		 * Is LDB user or not
		 */
		
		$query = $this->_db->query("SELECT isLDBUser, userid FROM tuserflag WHERE userid IN $user_ids");
		$rows = $query->result();
		foreach ($rows as $row)
		{
			$user_info[$row->userid]['isLDBUser'] = $row->isLDBUser;
		}
		
		/*
		 * Preferred study locations
		 */
	
		$state_city_mapping = array();
		
		$query = $this->_db->query("SELECT city_id,state_id
					 	   			FROM countryCityTable
					 	   			WHERE city_id > 0   
					 	   			AND state_id > 0 ");
		
		foreach ($query->result() as $row)
		{
			$state_city_mapping[$row->state_id][] = (int) $row->city_id;
		}
		
		$query = $this->_db->query("SELECT UserId,CountryId,CityId,LocalityId,StateId
					 	   			FROM tUserLocationPref
					 	   			WHERE UserId IN $user_ids  
					 	   			AND status = 'live' ");
							
		foreach($query->result() as $row)
		{
			if($row->CountryId && !in_array($row->CountryId,$user_info[$row->UserId]['preferred_countries']))
			{
				$user_info[$row->UserId]['preferred_countries'][] = (int) $row->CountryId;
			}
			if($row->CityId && !in_array($row->CityId,$user_info[$row->UserId]['preferred_cities']))
			{
				$user_info[$row->UserId]['preferred_cities'][] = (int) $row->CityId;
			}
			
			if($row->CityId && !in_array($row->CityId,$user_info[$row->UserId]['preferred_cities_unaltered']))
			{
				$user_info[$row->UserId]['preferred_cities_unaltered'][] = (int) $row->CityId;
			}

			if($row->StateId && !in_array($row->StateId,$user_info[$row->UserId]['preferred_states']))
			{
				$user_info[$row->UserId]['preferred_states'][] = (int) $row->StateId;
			}
			if($row->LocalityId && !in_array($row->LocalityId,$user_info[$row->UserId]['preferred_localities']))
			{
				$user_info[$row->UserId]['preferred_localities'][] = (int) $row->LocalityId;
			}
			
			if($row->CityId	== 0 && $row->StateId && is_array($state_city_mapping[$row->StateId]) && count($state_city_mapping[$row->StateId]))
			{
				$user_info[$row->UserId]['preferred_cities'] = array_merge($user_info[$row->UserId]['preferred_cities'],$state_city_mapping[$row->StateId]);
			}
		}
		
		/*
		 * Degree preferences
		 */
		$query = $this->_db->query("SELECT UserId,PrefId,
										   Modeofeducationfulltime as fulltime,
										   Modeofeducationparttime as parttime,
										   Modeofeducationdistance as distance,
										   DesiredCourse as desired_course,
										   DegreePrefAICTE as degree_pref_aicte,
										   DegreePrefUGC as degree_pref_ugc,
										   DegreePrefInternational as degree_pref_international,
										   ExtraFlag as extra_flag
					 	   			FROM tUserPref
					 	   			WHERE UserId IN $user_ids
					 	   			ORDER BY UserId ASC,SubmitDate DESC ");
		$rows = $query->result();
		
		$processed_users = array();
		$desired_courses = array();
		$desired_courses_study_abroad = array();
		
		foreach($rows as $row)
		{
			if(!in_array($row->UserId,$processed_users))
			{
				$user_info[$row->UserId]['mode_of_learning'] = array();
			
				if($row->fulltime == 'yes')
				{
					$user_info[$row->UserId]['mode_of_learning'][] = 'Full Time';
				}
				if($row->parttime == 'yes')
				{
					$user_info[$row->UserId]['mode_of_learning'][] = 'Part Time';
				}
				if($row->distance == 'yes')
				{
					$user_info[$row->UserId]['mode_of_learning'][] = 'Correspondence';
					$user_info[$row->UserId]['mode_of_learning'][] = 'E Learning';
				}
				
				$user_info[$row->UserId]['desired_course'] = (int) $row->desired_course;
				
				
				$user_info[$row->UserId]['degree_pref_aicte'] = $row->degree_pref_aicte;
				$user_info[$row->UserId]['degree_pref_ugc'] = $row->degree_pref_ugc;
				$user_info[$row->UserId]['degree_pref_international'] = $row->degree_pref_international;
				
				$user_info[$row->UserId]['extra_flag'] = $row->extra_flag;
				
				if($row->desired_course)
				{
					if($row->extra_flag == 'studyabroad')
					{
						$desired_courses_study_abroad[] = $row->desired_course;
					}
					else 
					{
						$desired_courses[] = $row->desired_course;
					}
				}
				
				$processed_users[] = $row->UserId;
			}
		}
		
		if(count($desired_courses))
		{
			$desired_courses = array_unique($desired_courses);
                        $sql = "SELECT SpecializationId as shiksha_course_id, SpecializationId as specialization_id, CategoryId as category_id FROM tCourseSpecializationMapping WHERE SpecializationId IN (".implode(',',$desired_courses).") ";

			$query = $this->_db->query($sql);
                        $rows = $query->result();

			$shiksha_course_specialization_mapping = array();
			
			foreach($rows as $row)
			{
				$shiksha_course_specialization_mapping[$row->specialization_id][] = $row;	
			}
			
			foreach ($users as $user)
			{
				$desired_course = $user_info[$user]['desired_course'];
				$mode_of_learning = $user_info[$user]['mode_of_learning'];
				
				/*
				 * Get corresponding shiksha mapped course
				 */
				
				if($desired_course)
				{
					$mapped_shiksha_courses = $shiksha_course_specialization_mapping[$desired_course];
					
					$user_shiksha_courses = array();
					$user_shiksha_course_category = 0;
					
					if(is_array($mapped_shiksha_courses) && count($mapped_shiksha_courses))
					{
						foreach($mapped_shiksha_courses as $mapped_shiksha_course)
						{
							/*
							 * If user has specified mode of learning, we'll taken only those matching
							 */
                                                    /*
							if( (in_array('Full Time',$mode_of_learning) && $mapped_shiksha_course->full_time == 'yes') ||
							    (in_array('Part Time',$mode_of_learning) && $mapped_shiksha_course->part_time == 'yes') ||
							    (!in_array('Full Time',$mode_of_learning) && !in_array('Part Time',$mode_of_learning))
							  )
							{
                                                     */
								$user_shiksha_courses[] = $mapped_shiksha_course->shiksha_course_id;
								$user_shiksha_course_category = $mapped_shiksha_course->category_id;
                                                    // }
						}
					}
				
					$user_info[$user]['shiksha_courses'] = array_unique($user_shiksha_courses);
					$user_info[$user]['shiksha_course_category'] = $user_shiksha_course_category;
					$user_info[$user]['profile_category'] = $user_shiksha_course_category;
				}
			}
		}
		
		if(count($desired_courses_study_abroad))
		{
			$desired_courses_study_abroad = array_unique($desired_courses_study_abroad);
			
			$query = $this->_db->query("SELECT SpecializationId,CategoryId
						 	   			FROM  tCourseSpecializationMapping
						 	   			WHERE SpecializationId IN (".implode(',',$desired_courses_study_abroad).") ");
			$rows = $query->result();
			
			foreach($rows as $row)
			{
				$study_abroad_mapping[$row->SpecializationId] = $row->CategoryId;	
			}
			
			foreach ($users as $user)
			{
				$desired_course = $user_info[$user]['desired_course'];
				$extra_flag = $user_info[$user]['extra_flag'];
				
				/*
				 * Get corresponding shiksha mapped course
				 */
				
				if($desired_course && $study_abroad_mapping[$desired_course])
				{				
					$user_info[$user]['study_abroad_category'] = $study_abroad_mapping[$desired_course];
					$user_info[$user]['profile_category'] = $study_abroad_mapping[$desired_course];
				}
			}
		}
		
		/*
		 * If we still do not have user's profile category
		 * then check with registration key
		 */
		
		$user_category_url_mapping = array();
		
		foreach ($users as $user_id)
		{
			if(!isset($user_info[$user_id]['profile_category']))
			{
				$user_registration_key = $user_info[$user_id]['registration_key'];
				
				if($user_registration_key == 114)
				{
					$user_registration_referer = $user_info[$user_id]['registration_referer'];
					
					if($user_registration_referer)
					{
						/*
						 * First remove 'http://'
						 */
						$user_registration_referer = substr($user_registration_referer,7);
						
						$user_registration_referer_elements = explode('.',$user_registration_referer);
						$user_category_url = $user_registration_referer_elements[0];
						
						$user_category_url_mapping[$user_id] = $user_category_url;
					}
				}
			}
		}
		
		if(count($user_category_url_mapping))
		{
			$query = $this->_db->query("SELECT boardId,urlName
						 	   			FROM categoryBoardTable
						 	   			WHERE parentId = 1 " );
			
			$all_categories = array();
			
			foreach ($query->result() as $row)
			{
				$all_categories[$row->urlName] = $row->boardId;
			}
			
			foreach ($user_category_url_mapping as $user_id => $category_url)
			{
				if($all_categories[$category_url])
				{
					$user_info[$user_id]['profile_category'] = $all_categories[$category_url];
				}
			}
		}
		
		/*
		 * Exam and course level info
		 */			 	   	
		$query = $this->_db->query("SELECT UserId,Name as exam_name,Level as level,Marks as marks,MarksType as marks_type
					 	   			FROM tUserEducation
					 	   			WHERE UserId IN $user_ids
					 	   			AND status = 'live' " );
		
		$rows = $query->result();
		
		foreach($rows as $row)
		{
			if($row->exam_name && $row->level)
			{
				if($row->level == 'Competitive exam')
				{
					$user_info[$row->UserId]['exam'][] = $row;
					
					if($row->exam_name == 'CAT')
					{
						$user_info[$row->UserId]['cat_score'] = $row->marks;
					}
					else if($row->exam_name == 'MAT')
					{
						$user_info[$row->UserId]['mat_score'] = $row->marks;
					}
				}
				
				if($row->level == 'UG')
				{
					$user_info[$row->UserId]['graduation_level'] = 'PG';
				}
				
				if($row->level == '12' && !$user_info[$row->UserId]['graduation_level'])
				{
					$user_info[$row->UserId]['graduation_level'] = 'UG';
				}
			}	
		}
		
		return $user_info;
	}
	
	private function _getResultArray($query,$field)
	{
		$result = array();
		foreach($query->result() as $row)
		{
			$result[] = $row->$field;
		}
		return $result;
	}
}
