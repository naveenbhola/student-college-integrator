<?php 

class Recommendation_Model extends MY_Model
{
    private $exclusionArray = array();
	
	function __construct()
	{
		parent::__construct('recommendation');
	}
	
	function init($db_handle)
	{
		$this->_db = $db_handle;
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
	
	/*
	 * Retrieve users who match recommendation criteria
	 */
	function getUsers($current_time_window)
	{
		$this->initiateModel();
		
		$num_days = RECO_NUM_DAYS;
	
		$date = date('Y-m-d',strtotime("-$num_days days",time()));

		list($time_window_start,$time_window_end) = explode(';',$current_time_window);
		
		$day_of_week = intVal(date("N",strtotime($time_window_start))) + 1;
		
		$time_window_start_time = date("H:i:00",strtotime($time_window_start));
		$time_window_end_time = date("H:i:00",strtotime($time_window_end));
		
		/*
		 * Get all the users in current time window
		 */
		
		$query = $this->_db->query("SELECT tu.userid
									FROM tuser tu
									INNER JOIN tuserflag tuf ON tuf.userId = tu.userid 
									WHERE DAYOFWEEK(tu.usercreationDate) = ?
									AND TIME(tu.usercreationDate ) >= ?
									AND TIME(tu.usercreationDate ) < ? 
									AND tuf.hardbounce = '0' ", array($day_of_week, $time_window_start_time, $time_window_end_time));
                //error_log("SELECT tu.userid FROM tuser tu INNER JOIN tuserflag tuf ON tuf.userId = tu.userid WHERE DAYOFWEEK(tu.usercreationDate) = $day_of_week AND TIME(tu.usercreationDate ) >= '$time_window_start_time' AND TIME(tu.usercreationDate ) < '$time_window_end_time' AND tuf.hardbounce = '0' ");
		$users_in_current_time_window = $this->_getResultArray($query,'userid');

                // error_log("\n\n users:".count($users_in_current_time_window) , 3, '/home/infoedge/Desktop/log.txt');
							
		$final_user_list = array();
		
		if(count($users_in_current_time_window))
		{
			$excludedUsers = $this->getUsersToBeExcluded($date, $users_in_current_time_window);
			$users_in_current_time_window = array_diff($users_in_current_time_window, $excludedUsers);
			
			/**
			 * Exclude users who registered and created response in this current time window
			 */ 
			$responseRegistrations = $this->getResponseRegistrationsFromTimeWindow($current_time_window);
			$users_in_current_time_window = array_diff($users_in_current_time_window,$responseRegistrations);
			
			/**
			 * Include users who registered and created responses in time window 36 hours eairlier
			 * As these users were skipped in their regular window as per the logic above
			 */ 
			$responseRegistrationsToSendMailTo = $this->getResponseRegistrationsToSendMailTo($current_time_window);
			$users_in_current_time_window = array_merge($users_in_current_time_window,$responseRegistrationsToSendMailTo);
			
			$users_registered = $this->getUsersByRegistrationTime($date,$users_in_current_time_window);
			$users_applied_to_listings = $this->getUsersByAppliedToListingsTime($date,$users_in_current_time_window);
			$users_clicked_on_institutes = $this->getUsersByClickedOnInstitutesTime($date,$users_in_current_time_window);
			$users_clicked_on_courses = $this->getUsersByClickedOnCoursesTime($date,$users_in_current_time_window);
			
			$final_user_list = array_unique(array_merge($users_registered,$users_applied_to_listings,$users_clicked_on_institutes,$users_clicked_on_courses));
		}
		// error_log("\n\n Final users:".print_r($final_user_list, true) , 3, '/home/infoedge/Desktop/log.txt');
		return $final_user_list;
	}
		
		function getResponseRegistrationsFromTimeWindow($current_time_window)
		{
			$this->initiateModel();
			
			list($time_window_start,$time_window_end) = explode(';',$current_time_window);
			$sql =  "SELECT DISTINCT t.userid ".
					"FROM tuser t ".
					"INNER JOIN tempLMSTable lms ON lms.userId = t.userid ".
					"WHERE t.usercreationDate > ? ".
                                        "AND lms.listing_subscription_type='paid' ".
					"AND t.usercreationDate <= ? ".
					"AND lms.submit_date > ? ".
					"AND lms.submit_date <= ? ";
			
			$query = $this->_db->query($sql, array($time_window_start, $time_window_end, $time_window_start, $time_window_end));
			return $this->_getResultArray($query,'userid');
		}
		
		function getResponseRegistrationsToSendMailTo($current_time_window)
		{
			$this->initiateModel();
			list($time_window_start,$time_window_end) = explode(';',$current_time_window);
			
			$time_window_start = date('Y-m-d H:i:s',strtotime("-36 hours",strtotime($time_window_start)));
			$time_window_end = date('Y-m-d H:i:s',strtotime("-36 hours",strtotime($time_window_end)));
			
			
			$sql =  "SELECT DISTINCT t.userid ".
					"FROM tuser t ".
					"INNER JOIN tempLMSTable lms ON lms.userId = t.userid ".
					"WHERE t.usercreationDate > ? ".
					"AND t.usercreationDate <= ? ".
                                        "AND lms.listing_subscription_type='paid' ".
					"AND lms.submit_date > ? ".
					"AND lms.submit_date <= ? ";
			error_log($sql);
			$query = $this->_db->query($sql, array($time_window_start, $time_window_end, $time_window_start, $time_window_end));
			return $this->_getResultArray($query,'userid');
		}

        function getUsersToBeExcluded($date, $users_in_current_time_window) {            
				
				$this->initiateModel();
				
               $this->getExcludedListings();
               $included_insitutes_clause = "";
               $included_courses_clause = "";
               
               // Getting Responses to be excluded..
               if($this->exclusionArray['excluded_insitutes_ids'] != "") {
                    $included_insitutes_clause = " AND listing_type_id IN (".$this->exclusionArray['excluded_insitutes_ids'].")";
               }
               if($this->exclusionArray['excluded_courses_ids'] != "") {
                    $included_courses_clause = " AND listing_type_id IN (".$this->exclusionArray['excluded_courses_ids'].")";
               }
               $get_query = "SELECT DISTINCT userId FROM tempLMSTable WHERE listing_subscription_type='paid' AND submit_date >= ? AND ( (listing_type = 'course' ".$included_courses_clause.") OR (listing_type = 'institute' ".$included_insitutes_clause.") ) AND userId IN (".implode(',',$users_in_current_time_window).") ";
               $query = $this->_db->query($get_query, array($date));		
               $usersResponses = $this->_getResultArray($query,'userId');
               // error_log("\n\n excluded listings Responses: ".print_r($users, true)."\n\n QUERY: ".$get_query, 3, '/home/infoedge/Desktop/log.txt');
               

               // Getting Uses who clicked on Excluded Institutes on the reco mailer..
               $included_insitutes_clause = "";
               if($this->exclusionArray['excluded_insitutes_ids'] != "") {
                    $included_insitutes_clause = "AND instituteID IN (".$this->exclusionArray['excluded_insitutes_ids'].")";
               }
               $get_query = "SELECT DISTINCT userID FROM recommendations_info WHERE actionTaken = 'clicked' AND actionTakenAt >= ? ".$included_insitutes_clause." AND userID IN (".implode(',',$users_in_current_time_window).") ";
	       $query = $this->_db->query($get_query, array($date));
               $usersClickedonExcludedInstitutes = $this->_getResultArray($query,'userId');
               

               // Getting Uses who cliked on Excluded Courses on the reco mailer..
               $included_courses_clause = "";
               if($this->exclusionArray['excluded_courses_ids'] != "") {
                    $included_courses_clause = "AND courseID IN (".$this->exclusionArray['excluded_courses_ids'].")";
               }
               $get_query = "SELECT DISTINCT userID FROM recommendations_courses_info WHERE actionTaken = 'clicked' AND actionTakenAt >= ? ".$included_courses_clause." AND userID IN (".implode(',',$users_in_current_time_window).") ";
	       $query = $this->_db->query($get_query, array($date));
               $usersClickedonExcludedCourses = $this->_getResultArray($query,'userId');

               $users = array_merge($usersResponses, $usersClickedonExcludedInstitutes, $usersClickedonExcludedCourses);
               
               return $users;
        }

        /*
         *  Get Listings IDs whose responses' we do not have to include..
         */
        function getExcludedListings() {

               global $listings_not_included_in_recommendations;
               $exclusionArray = array();
$this->initiateModel();
               $exclusionArray['excluded_courses_ids'] = ""; $exclusionArray['excluded_insitutes_ids'] = "";

               if(is_array($listings_not_included_in_recommendations['INSTITUTES']) && count($listings_not_included_in_recommendations['INSTITUTES']) >= 1) {
                   $exclusionArray['excluded_insitutes_ids'] = implode(",", $listings_not_included_in_recommendations['INSTITUTES']);
                   // Now getting courses to be excluded of these institutes..
                   $exclusionArray['excluded_courses_ids'] = $this->getCoursesForInstitutes($exclusionArray['excluded_insitutes_ids']);
               }

               if(is_array($listings_not_included_in_recommendations['COURSES']) && count($listings_not_included_in_recommendations['COURSES']) >= 1) {
                   if($exclusionArray['excluded_courses_ids'] != "")
                       $exclusionArray['excluded_courses_ids'] .= ", ".implode(",", $listings_not_included_in_recommendations['COURSES']);
                   else
                       $exclusionArray['excluded_courses_ids'] = implode(",", $listings_not_included_in_recommendations['COURSES']);
               }

               $this->exclusionArray = $exclusionArray;
        }
        

	/*
	 * Get users who registered in last X days
	 * and fall in current time window
	 */
	function getUsersByRegistrationTime($date,$users_in_current_time_window)
	{
		$this->initiateModel();
                $get_query = "SELECT userid
									FROM tuser
									WHERE usercreationDate >= ?
									AND userid IN (".implode(',',$users_in_current_time_window).") ";
		$query = $this->_db->query($get_query, array($date));

                // error_log("\n\n getUsersByRegistrationTime QUERY: ".$get_query, 3, '/home/infoedge/Desktop/log.txt');
		
		return $this->_getResultArray($query,'userid');
	}
	
	/*
	 * Get users who applied to a listing in last X days
	 * and fall in current time window
	 */
	function getUsersByAppliedToListingsTime($date,$users_in_current_time_window)
	{
		$this->initiateModel();
                $get_query = "SELECT DISTINCT userId
									FROM tempLMSTable
									WHERE submit_date >= ?
                                                                        AND listing_subscription_type='paid' 
									AND (listing_type = 'course' OR listing_type = 'institute')
									AND userId IN (".implode(',',$users_in_current_time_window).") ";                
                
                $query = $this->_db->query($get_query, array($date));
                // error_log("\n\n getUsersByAppliedToListingsTime QUERY: ".$get_query, 3, '/home/infoedge/Desktop/log.txt');

		return $this->_getResultArray($query,'userId');
	}
        
        /*
         * Get all live courses of the institutes.
         */
        function getCoursesForInstitutes($instituteIds) {
			$this->initiateModel();
             $get_query = "SELECT group_concat(course_id) as courseIds
                                    FROM `course_details`
                                    WHERE `institute_id` in ($instituteIds)
                                    AND `status` = 'live' ";
             
                $query = $this->_db->query($get_query);                
		return implode(',', $this->_getResultArray($query,'courseIds'));
        }
	
	/*
	 * Get users who clicked on institutes from recommendation mailer in last X days
	 * and fall in current time window
	 */
	function getUsersByClickedOnInstitutesTime($date,$users_in_current_time_window)
	{
		$this->initiateModel();
		$get_query = "SELECT DISTINCT userID
									FROM recommendations_info
									WHERE actionTaken = 'clicked'
									AND actionTakenAt >= ?
									AND userID IN (".implode(',',$users_in_current_time_window).") ";
		$query = $this->_db->query($get_query, array($date));
                
                // error_log("\n\n getUsersByClickedOnInstitutesTime QUERY: ".$get_query, 3, '/home/infoedge/Desktop/log.txt');

		return $this->_getResultArray($query,'userID');
	}
	
	/*
	 * Get users who clicked on courses from recommendation mailer in last X days
	 * and fall in current time window
	 */
	function getUsersByClickedOnCoursesTime($date,$users_in_current_time_window)
	{
		$this->initiateModel();
                $get_query = "SELECT DISTINCT userID
									FROM recommendations_courses_info
									WHERE actionTaken = 'clicked'
									AND actionTakenAt >= ?
									AND userID IN (".implode(',',$users_in_current_time_window).") ";

		$query = $this->_db->query($get_query, array($date));

                // error_log("\n\n getUsersByClickedOnCoursesTime QUERY: ".$get_query, 3, '/home/infoedge/Desktop/log.txt');
		
		return $this->_getResultArray($query,'userID');
	}
	
	/*
	 * Get applied courses by given users in last X days
	 */
	
	function getAppliedCourses($users,$num_days=0)
	{
		$this->initiateModel();
		if(is_array($users) && count($users))
		{
			$num_days = (int) $num_days;
			$date = date('Y-m-d',strtotime("-$num_days days",time()));
			
			/* $query = $this->_db->query("SELECT DISTINCT t.userId,cd.course_id,cd.institute_id,cd.country_id,cd.city_id
										FROM tempLMSTable t
										INNER JOIN categoryPageData cd ON (cd.course_id = t.listing_type_id AND cd.status = 'live') 
										WHERE t.userId IN (".implode(',',$users).") 
										AND t.listing_type = 'course'
										AND t.action = 'GetFreeAlert'
										AND t.submit_date >= '".$date."' " );
                         */
			$query = $this->_db->query("SELECT DISTINCT t.userId, t.listing_type_id as course_id, sc.primary_id as institute_id 
						    FROM tempLMSTable t, shiksha_courses sc
						    WHERE t.listing_type_id = sc.course_id AND sc.status = 'live'
						    AND t.userId IN (".implode(',',$users).") AND t.listing_type = 'course'
						    AND t.submit_date >= '".$date."' ");
			
			$rows = $query->result();
			
			return $rows;
		}
	}
	
	/*
	 * Get applied institutes by given users in last X days
	 */
	
	function getAppliedInstitutes($users,$num_days=0)
	{
		$this->initiateModel();
		if(is_array($users) && count($users))
		{
			$num_days = (int) $num_days;
			$date = date('Y-m-d',strtotime("-$num_days days",time()));
			
			/* $query = $this->_db->query("SELECT DISTINCT t.userId,t.listing_type_id,loc.country_id
										FROM tempLMSTable t
										LEFT JOIN institute_location_table loc ON (loc.institute_id = t.listing_type_id AND loc.status = 'live') 
										WHERE t.userId IN (".implode(',',$users).")
										AND t.listing_type = 'institute'
										AND t.action = 'Request_E-Brochure'
										AND t.submit_date >= '".$date."' " );
                         */

                        $query = $this->_db->query("SELECT DISTINCT t.userId, t.listing_type_id, t.action, loc.country_id
						    FROM tempLMSTable t
						    LEFT JOIN institute_location_table loc ON (loc.institute_id = t.listing_type_id AND loc.status = 'live')
						    WHERE t.userId IN (".implode(',',$users).")
						    AND t.listing_type = 'institute'										
						    AND t.submit_date >= ? ", array($date) );
			
			$rows = $query->result();
			
			return $rows;
		}
	}
	
	/*
	 * Get clicked courses (from recommendation mailer) by given users in last X days
	 */
	
	function getClickedCourses($users,$num_days=0)
        {
                $this->initiateModel();
                if(is_array($users) && count($users))
                {
                        $num_days = (int) $num_days;
                        $date = date('Y-m-d',strtotime("-$num_days days",time()));
                        /* $sql = "SELECT DISTINCT rc.courseID,rc.userID,cpd.city_id,cpd.country_id,cpd.institute_id
                                                                                FROM recommendations_courses_info rc
                                                                                LEFT JOIN categoryPageData cpd ON (cpd.course_id = rc.courseID AND cpd.status = 'live')
                                                                                WHERE rc.userID IN (".implode(',',$users).")
                                                                                AND rc.actionTaken = 'clicked'
                                                                                AND rc.actionTakenAt >= '".$date."' " ;
                         *
                         */
			$sql = "SELECT DISTINCT rc.courseID, rc.userID, ilt.institute_id, ilt.country_id, ilt.city_id,c.course_level_1 as course_level 
				FROM recommendations_courses_info rc, institute_location_table ilt, course_location_attribute cla,course_details c 										
				WHERE rc.courseID = cla.course_id AND cla.status = 'live'
				AND cla.institute_location_id = ilt.institute_location_id AND ilt.status = 'live'
				AND cla.attribute_type = 'Head Office' AND cla.attribute_value = 'TRUE'
				AND c.course_id = rc.courseID and c.status = 'live'
				AND rc.userID IN (".implode(',',$users).")
				AND rc.actionTaken = 'clicked'
				AND rc.actionTakenAt >= ? ";
			$query = $this->_db->query($sql, array($date));
			
			$rows = $query->result();
			
			return $rows;
		}
	}
	
	/*
	 * Get clicked institutes (from recommendation mailer) by given users in last X days
	 */
	
	function getClickedInstitutes($users,$num_days=0)
	{
		$this->initiateModel();
		if(is_array($users) && count($users))
		{
			$num_days = (int) $num_days;
			$date = date('Y-m-d',strtotime("-$num_days days",time()));

                        /* $sql = "SELECT DISTINCT r.instituteID,r.userID,cpd.country_id
										FROM recommendations_info r
										LEFT JOIN categoryPageData cpd ON (cpd.institute_id = r.instituteID AND cpd.status = 'live')
										WHERE r.userID IN (".implode(',',$users).")
										AND r.actionTaken = 'clicked'
										AND r.actionTakenAt >= '".$date."' ";
                         */
                        $sql = "SELECT DISTINCT r.instituteID, r.userID, ilt.country_id
				FROM recommendations_info r,
				institute_location_table ilt
				WHERE
				ilt.institute_id = r.instituteID AND ilt.status = 'live'
				AND r.userID IN (".implode(',',$users).")
				AND r.actionTaken = 'clicked'
				AND r.actionTakenAt >= ? ";
			$query = $this->_db->query($sql, array($date));
			
			$rows = $query->result();
			
			return $rows;
		}
	}
	
	/*
	 * Get flagship courses for given institutes
	 */
	
	function getFlagshipCourses($institute_ids)
	{
		$this->initiateModel();
		if(is_array($institute_ids) && count($institute_ids))
		{
			$query = $this->_db->query("SELECT course_id,institute_id,course_level_1 as course_level
										FROM course_details
										WHERE institute_id IN (".implode(',',$institute_ids).") 
										AND status = 'live'
										ORDER BY institute_id,course_order " );
		
			$rows = $query->result();
			
			return $rows;
		}
	}
	
	/*
	 * Get categorires from course mapping for given courses
	 */
	
	function getCategoriesFromCourseMapping($course_ids)
	{
		$this->initiateModel();
		if(is_array($course_ids) && count($course_ids))
		{                    
                        $sql = "select distinct clm.clientCourseID as course_id, csm.CategoryId as category_id from clientCourseToLDBCourseMapping clm, tCourseSpecializationMapping csm where clm.clientCourseID in (".implode(",",$course_ids).") and clm.LDBCourseID = csm.SpecializationId and clm.status = 'live' and csm.status = 'live'";

			$query = $this->_db->query($sql);
			
			$rows = $query->result();
			
			return $rows;
		}
	}
	
	/*
	 * Get categorires from listings for given courses
	 */
	
	function getCategoriesFromListings($course_ids)
	{
		$this->initiateModel();
		if(is_array($course_ids) && count($course_ids))
		{
			$query = $this->_db->query("SELECT DISTINCT cd.course_id,cbt.parentId
										FROM  categoryPageData cd
										INNER JOIN categoryBoardTable cbt ON cbt.boardId = cd.category_id
										WHERE cd.course_id IN (".implode(",",$course_ids).")  
										AND cd.status = 'live' " );
			
			$rows = $query->result();
			
			return $rows;
		}
	}
	
	/*
	 * Get sub categorires for given courses
	 */
	function getSubCategoriesForCourses($courseIds)
	{
		$this->initiateModel();
		
		if(is_array($courseIds) && count($courseIds))
		{
			$query = $this->_db->query("SELECT DISTINCT course_id, category_id
						    FROM  categoryPageData
						    WHERE course_id IN (".implode(",",$courseIds).")  
						    AND status = 'live'");
			
			$rows = $query->result();
			
			$course_subCategory_mapping = array();
			foreach($rows as $row) {
			    $course_subCategory_mapping[$row->course_id][] = $row->category_id;
			}
			
			return $course_subCategory_mapping;
		}
	}
	
	/*
	 * Save recommendations in database for National Users
	 */
	function saveRecommendations($recommendations = array())
	{
		$this->initiateModel('write');
		if(count($recommendations))
		{
			$recommendation_ids = array();
			
			foreach ($recommendations as $user_id => $user_recommendation_data)
			{
				$recommendation_ids[$user_id] = array();
				foreach ($user_recommendation_data as $algo => $recommendation)
				{                                                
					$recommendation_algo = $algo;
					$institute_id = $recommendation['instituteId'];
					$course_id = $recommendation['courseId'];
					
					$data_recommendations_info = array(
									    'userID' => $user_id,
									    'instituteID' => $institute_id,
									    'sourceAlgorithm' => $recommendation_algo,
									    'status' => 'sent',
									    'recommendationSentDate' => date("Y-m-d H:i:s"),
									    'actionTaken' => ''
									);
					
					if($this->_db->insert('recommendations_info',$data_recommendations_info))
					{								  		
						$recommendation_id = $this->_db->insert_id();
						
						$data_recommendations_courses_info = array(
											    'recommendationID' => $recommendation_id,
											    'userID' => $user_id,
											    'courseID' => $course_id,
											    'actionTaken' => '',
											    'recommendationSentDate' => date("Y-m-d H:i:s") 
											);
						
					    if(!empty($course_id)) {                
						    $this->_db->insert('recommendations_courses_info',$data_recommendations_courses_info);		
					    }
					    
					    $recommendation_ids[$user_id][$institute_id] = $recommendation_id;
					}										  
				}
			}
			
			return $recommendation_ids;
		}
	}
	
	function saveRecommendationDefaulters($recommendation_defaulters)
	{
		$this->initiateModel('write');
		if(is_array($recommendation_defaulters) && count($recommendation_defaulters))
		{
			foreach ($recommendation_defaulters as $defaulter)
			{
				$data = array(
								'user_id' => $defaulter,
								'date' => date('Y-m-d')
							  );
				$this->_db->insert('recommendations_not_sent_for_users',$data);
			}
		}
	}
	
	/*
	 * Get recommendations sent to a user in last X weeks
	 */
	
	function getRecommendationsSent($users,$num_days)
	{
		$this->initiateModel();
		if(is_array($users) && count($users))
		{
			$num_days = $num_days + 1;
		
			$date = date('Y-m-d',strtotime("-$num_days days",time()));
		
			$query = $this->_db->query("SELECT userID,instituteID
										FROM recommendations_info 
										WHERE userID IN (".implode(',',$users).") 
										AND recommendationSentDate >= ? ", array($date) );
			$rows = $query->result();
			
			return $rows;
		}
	}
	
	/*
	 * Get institutes for random courses in recommendations 
	 */
	
	function getRandomSISeedInstitutes($recommendations)
	{
		$this->initiateModel();
		$random_si_seed_courses = array();
		
		foreach($recommendations as $user_id => $user_recommendations)
		{
			foreach($user_recommendations as $category_id => $category_recommendations)
			{	
				foreach($category_recommendations as $recommendation)
				{
					if($recommendation['algo'] == 'similar_institutes' && intVal($recommendation['random_si_seed_course']))
					{
						$random_si_seed_courses[] = (int) $recommendation['random_si_seed_course'];
						break;
					}
				}
			}	
		}
		
		$random_si_seed_institutes = array();
		
		if(count($random_si_seed_courses))
		{
			$query = $this->_db->query("SELECT c.course_id,i.institute_name
										FROM course_details c
										INNER JOIN institute i ON (i.institute_id = c.institute_id AND i.status = 'live')
										WHERE c.course_id IN (".implode(",",$random_si_seed_courses).") 
										AND c.status = 'live' " );
			
			$rows = $query->result();
			
			foreach($rows as $row)
			{
				$random_si_seed_institutes[$row->course_id] = $row->institute_name;
			}
		}
		
		return $random_si_seed_institutes;
	}
	
	/*
	 * Get category id to name mapping from recommendations
	 */
	function getCategoryDetails($recommendations)
	{
		$this->initiateModel();
		$category_ids = array();
			
		foreach ($recommendations as $user_id => $user_recommendations)
		{
			$recommendations_by_category = array();
			
			foreach ($user_recommendations as $category_id => $category_recommendations)
			{
				$category_ids[] = $category_id;
			}
		}
		
		$category_details = array();
		
		if(count($category_ids))
		{	
			$query = $this->_db->query("SELECT boardId,name
										FROM  categoryBoardTable
										WHERE boardId IN (".implode(',',$category_ids).") ");
			$rows = $query->result();
			
			foreach($rows as $row)
			{
				$category_details[$row->boardId] = $row->name;
			}
		}
		
		return $category_details;
	}
	
	/*
	 * Get listing details for institutes/courses in recommendations
	 */	
	function getListingDetails($recommendations)
     {
         $this->initiateModel();

         $institute_ids = array();
         $course_ids = array();
         $category_ids = array();

         $listing_details = array();

         if(count($recommendations))
         {
             foreach ($recommendations as $user_id => $user_recommendations)
             {
                 $recommendations_by_category = array();

                 foreach ($user_recommendations as $category_id => 
$category_recommendations)
                 {
                     $category_ids[] = $category_id;
                     foreach ($category_recommendations as $recommendation)
                     {
                         if($recommendation['institute_id']>0) {
                         	$institute_ids[] = $recommendation['institute_id'];
                         }

                         if($recommendation['course_id']>0) {
                         	$course_ids[] = $recommendation['course_id'];
                        }
                      
                     }
                 }
             }

             if(count($institute_ids))
             {
                                 /* $query = $this->_db->query("SELECT 
i.institute_id,i.institute_name,lm.listing_seo_url,ct.name as 
country,cct.city_name as city
                                             FROM institute i
                                             LEFT JOIN listings_main lm 
ON (lm.listing_type_id = i.institute_id AND lm.listing_type = 
'institute' AND lm.status = 'live')
                                             LEFT JOIN 
institute_location_table ilt ON (ilt.institute_id = i.institute_id AND 
ilt.status = 'live')
                                             LEFT JOIN countryTable ct 
ON countryId = ilt.country_id
                                             LEFT JOIN countryCityTable 
cct ON cct.city_id = ilt.city_id
                                             WHERE i.institute_id IN 
(".implode(',',$institute_ids).")
                                             AND i.status = 'live' ");
                                  *
                                  */
                                 $query = $this->_db->query("SELECT 
i.institute_id,i.institute_name,lm.listing_seo_url
                                             FROM institute i
                                             LEFT JOIN listings_main lm 
ON (lm.listing_type_id = i.institute_id AND lm.listing_type = 
'institute' AND lm.status = 'live')
                                             WHERE i.institute_id IN 
(".implode(',',$institute_ids).")
                                             AND i.status = 'live' ");

                 foreach ($query->result() as $row)
                 {
$listing_details[$row->institute_id]['institute_id'] = $row->institute_id;
$listing_details[$row->institute_id]['institute_name'] = 
$row->institute_name;
                     $listing_details[$row->institute_id]['url'] = 
$row->listing_seo_url;
                     // $listing_details[$row->institute_id]['city'] =  $row->city;
                     // $listing_details[$row->institute_id]['country'] = $row->country;
                 }

                 $query = $this->_db->query("SELECT institute_id,thumb_url
                                             FROM header_image
                                             WHERE institute_id IN 
(".implode(',',$institute_ids).")
                                             AND status = 'live'
                                             ORDER BY 
institute_id,header_order ");

                 foreach ($query->result() as $row)
                 {
if(!$listing_details[$row->institute_id]['logo'])
                     {
$listing_details[$row->institute_id]['logo'] = $row->thumb_url;
                     }
                 }
             }

             if(count($course_ids))
             {
                 /*$query = $this->_db->query("SELECT 
cd.course_id,cd.courseTitle,cd.institute_id,cd.course_type,cd.duration_value,cd.duration_unit,cd.fees_value,cd.fees_unit,lm.listing_seo_url
                                             FROM course_details cd
                                             LEFT JOIN listings_main lm 
ON (lm.listing_type_id = cd.course_id AND lm.listing_type = 'course' AND 
lm.status = 'live')
                                             WHERE cd.course_id IN 
(".implode(',',$course_ids).")
                                             AND cd.status = 'live' ");
                                  *
                                  */
                                 $query = $this->_db->query("SELECT 
cd.course_id,cd.courseTitle,cd.institute_id,cd.duration_value,cd.duration_unit,cd.fees_value,cd.fees_unit,lm.listing_seo_url, 
ct.name as country,cct.city_name as city
                                             FROM course_details cd
                                             LEFT JOIN listings_main lm 
ON (lm.listing_type_id = cd.course_id AND lm.listing_type = 'course' AND 
lm.status = 'live')
LEFT JOIN course_location_attribute cla ON (cla.course_id = cd.course_id 
AND cla.status = 'live' AND cla.attribute_type = 'Head Office' AND 
cla.attribute_value = 'TRUE')
LEFT JOIN institute_location_table ilt ON (cla.institute_location_id = 
ilt.institute_location_id AND ilt.status = 'live')
LEFT JOIN countryTable ct ON ct.countryId = ilt.country_id
LEFT JOIN  countryCityTable cct ON cct.city_id = ilt.city_id
                                             WHERE cd.course_id IN 
(".implode(',',$course_ids).")
                                             AND cd.status = 'live' ");
				 
				 
		 $queryCourseAttributes = $this->_db->query("SELECT cd.institute_id, ca.course_id, ca.attribute, ca.value FROM course_attributes ca, course_details cd WHERE ca.course_id IN (".implode(',',$course_ids).") and ca.status = 'live' AND cd.status = 'live' AND ca.course_id = cd.course_id AND ca.attribute in ('UGCStatus', 'AICTEStatus', 'DECStatus') ");	 
					    
	    foreach($queryCourseAttributes->result() as $row){		
		if($row->attribute == "AICTEStatus") {
                        if($approved[$row->institute_id] == '')
                            $approved[$row->institute_id]= "AICTE Approved";
                        else
                            $approved[$row->institute_id].= ", AICTE Approved";
                    }
                    if($row->attribute == "UGCStatus") {
                        if($approved[$row->institute_id] == '')
                            $approved[$row->institute_id]= "UGC Recognised";
                        else
                            $approved[$row->institute_id].= ", UGC Recognised";
                   }
                    if($row->attribute == "DECStatus") {
                        if($approved[$row->institute_id] == '')
                            $approved[$row->institute_id]= "DEC Approved";
                        else
                            $approved[$row->institute_id].= ", DEC Approved";

                    }
		
	    }
	    
	    $queryCourseAffiliations = $this->_db->query("SELECT cd.institute_id, ca.course_id, ca.attribute, ca.value FROM course_attributes ca, course_details cd WHERE ca.course_id IN (".implode(',',$course_ids).") and ca.status = 'live' AND cd.status = 'live' AND ca.course_id = cd.course_id AND ca.attribute in ('AffiliatedToIndianUniName', 'AffiliatedToDeemedUni', 'AffiliatedToAutonomous') ");			    
				
	    $affiliationorder = array();
	    foreach($queryCourseAffiliations->result() as $row){
		 if($row->attribute == "AffiliatedToIndianUniName") {
                        if($affiliationorder[$row->institute_id] == '')
                            $affiliated[$row->institute_id]= "Affiliated to ".$row->value;
			else
			    $affiliated[$row->institute_id]= ", Affiliated to ".$row->value;
                    }

                    if($row->attribute == "AffiliatedToDeemedUni") {
                       if($affiliationorder[$row->institute_id] == '')
                            $affiliated[$row->institute_id]= "Affiliated to Deemed University";
			else
			    $affiliated[$row->institute_id]= ", Affiliated to Deemed University";
                    }

                    if($row->attribute == "AffiliatedToAutonomous") {
                        if($affiliationorder[$row->institute_id] == '')
			   $affiliated[$row->institute_id]= "(Autonomous Program)";
			else
                            $affiliated[$row->institute_id]= ", (Autonomous Program)";
                    }
		    		
		}
                 foreach ($query->result() as $row)
                 {
$listing_details[$row->institute_id]['course_id'] = $row->course_id;
$listing_details[$row->institute_id]['course_name'] = $row->courseTitle;
$listing_details[$row->institute_id]['course_type'] = $row->course_type;
$listing_details[$row->institute_id]['course_duration'] = $row->duration_value;
$listing_details[$row->institute_id]['course_duration_unit'] = $row->duration_unit;
$listing_details[$row->institute_id]['fees_value'] = $row->fees_value;
$listing_details[$row->institute_id]['fees_unit'] = $row->fees_unit;
$listing_details[$row->institute_id]['course_url'] = $row->listing_seo_url;
$listing_details[$row->institute_id]['city'] = $row->city;
$listing_details[$row->institute_id]['country'] = $row->country;
$listing_details[$row->institute_id]['Approval'] = $approved[$row->institute_id];
$listing_details[$row->institute_id]['Affiliations'] = $affiliated[$row->institute_id];
                 }
             }
         }
         return $listing_details;
     }
	
	function getListingDetailsForSeoURL($listing_id,$listing_type)
	{
		$listing_details_for_url = array();
		
		if($listing_type == 'institute')
		{	
			$listing_details_for_url = $this->getInstituteDetailsForSeoURL($listing_id);		
		}
		else if($listing_type == 'course')
		{
			$listing_details_for_url = $this->getCourseDetailsForSeoURL($listing_id);
		}
		
		return $listing_details_for_url;
	}
	
	function getInstituteDetailsForSeoURL($institute_id)
	{
		$this->initiateModel();
		$query = $this->_db->query("SELECT i.institute_id,i.institute_name,ct.name as country,cct.city_name as city 
									FROM institute i
									LEFT JOIN institute_location_table ilt ON (ilt.institute_id = i.institute_id AND ilt.status = 'live')
									LEFT JOIN countryTable ct ON countryId = ilt.country_id
									LEFT JOIN  countryCityTable cct ON cct.city_id = ilt.city_id
									WHERE i.institute_id  = ?
									AND i.status = 'live' ", array($institute_id));
	
		$row = $query->row_array();
		return $row;
	}
	
	function getCourseDetailsForSeoURL($course_id)
	{
		$this->initiateModel();
                /*$sql = "SELECT cd.course_id,cd.courseTitle as course_name,cd.institute_id,i.institute_name,ct.name as country,cct.city_name as city
									FROM course_details cd
									LEFT JOIN institute i ON (i.institute_id = cd.institute_id AND i.status = 'live')
									LEFT JOIN institute_location_table ilt ON (ilt.institute_id = i.institute_id AND ilt.status = 'live')
									LEFT JOIN countryTable ct ON ct.countryId = ilt.country_id
									LEFT JOIN  countryCityTable cct ON cct.city_id = ilt.city_id
									WHERE cd.course_id = '$course_id'
									AND cd.status = 'live' ";
                 * 
                 */                
                $sql = "SELECT cd.course_id, cd.courseTitle as course_name, cd.institute_id, i.institute_name, ct.name as country, cct.city_name as city
									FROM course_details cd
									LEFT JOIN institute i ON (i.institute_id = cd.institute_id AND i.status = 'live')
									LEFT JOIN institute_location_table ilt ON (ilt.institute_id = i.institute_id AND ilt.status = 'live')
                                                                        LEFT JOIN course_location_attribute cla ON (cla.institute_location_id = ilt.institute_location_id AND cla.status = 'live' AND cla.attribute_type = 'Head Office' AND cla.attribute_value = 'TRUE')
									LEFT JOIN countryTable ct ON ct.countryId = ilt.country_id
									LEFT JOIN  countryCityTable cct ON cct.city_id = ilt.city_id
									WHERE cd.course_id = ?
									AND cd.status = 'live' ";
		$query = $this->_db->query($sql, array($course_id));
		$row = $query->row_array();
		return $row;
	}
	
	/*
	 * Save recommendation mailer in mail queue
	 */
	function addMailerInMailQueue($email,$name)
	{
		$this->initiateModel('write');

		// Filter mails for Amazon SES
        $mailerServiceType = 'shiksha';
        global $domainsUsingAmazonMailService;
        global $emailidsUsingAmazonMailService;
        $toDomainName = explode("@", $email);
        if( (in_array($toDomainName[1], $domainsUsingAmazonMailService)) || (in_array($email, $emailidsUsingAmazonMailService)) ) {
            $mailerServiceType = 'amazon';
        }

		$subject = $name.", View Colleges Matching Your Interest Area";
		
		$data = array(
						'fromEmail'=> 'collegealert@shiksha.com', 
						'toEmail'=> $email, 
						'subject'=> $subject, 
						'content'=> '', 
						'contentType'=>'html',
						'sendTime' => '0000-00-00 00:00:00',
						'attachment'=>'n',
						'ccEmail'=>'',
						'bccEmail'=>'',
						'fromUserName'=>'College Alert',
						'mailerServiceType' => $mailerServiceType
					);			
        $this->_db->insert('tMailQueue',$data);
        
        return $this->_db->insert_id();
	}
	
	function updateMailerInMailQueue($mailer_id,$mailer_html)
	{
		$this->initiateModel('write');
		$mailer_html = html_entity_decode($mailer_html);
		
		$data = array( 
						'content'=> $mailer_html, 
					);		

        $this->db->update('tMailQueue', $data, "id = $mailer_id");			
        try {
            $this->config->load('amqp');
            if ( $this->config->item('sendmail_via_smtp') !== "true")
            {
                $this->load->library("common/jobserver/JobManagerFactory");
                $jobManager = JobManagerFactory::getClientInstance();
                $jobManager->addBackgroundJob("SystemMailer", $mailer_id);
            }
        }
        catch(Exception $e) {
            error_log("JOBQException: ".$e->getMessage());
            $this->load->model('smsModel');
            $content = "FrontEnd: Problem with RabbitMQ";
            $msg = $this->smsModel->addSmsQueueRecord('',"9899601119",$content,"271028","0","user-defined","no");
            $msg = $this->smsModel->addSmsQueueRecord('',"9999430665",$content,"1600190","0","user-defined","no");
        }
	}
	
	/*
	 * Tracking if email was opened
	 */
	function trackMailerOpen($mailer_id)
	{
		$this->initiateModel('write');
		$data = array(
						'mailer_id'=> $mailer_id, 
						'open_time'=> date('Y-m-d H:i:s')
					);			
					
        $this->_db->insert('recommendation_email_tracking',$data);
	}
	
	
	/************************************************
	 * FUNCTIONS FOR RECOMMENDATION MAILER TESTING
	 ************************************************/
	
	function getUsersFromRecommendations()
	{
		$this->initiateModel();
		$query = $this->_db->query("SELECT DISTINCT ri.userID,u.displayname
									FROM recommendations_info ri
									INNER JOIN tuser u ON u.userid = ri.userID " );
	
		$rows = $query->result();
		
		return $rows;
	}
	
	function getUsersFromRecommendationLog()
	{
		$this->initiateModel();
		$query = $this->_db->query("SELECT DISTINCT r.user_id,u.displayname
									FROM recommendation_log r
									INNER JOIN tuser u ON u.userid = r.user_id " );
	
		$rows = $query->result();
		
		return $rows;
	}
		
	function getRecommendationMailer($user_id)
	{
		$this->initiateModel();
		$query = $this->_db->query("SELECT m.content
									FROM tMailQueue m
									INNER JOIN tuser u ON u.email = m.toEmail
									WHERE u.userid = ? 
									ORDER BY createdTime DESC
									LIMIT 1 ", array($user_id));
		$row = $query->row();
		return $row->content;
	}
	
	function getRecommendationMailerLog($user_id)
	{
		$this->initiateModel();
		$query = $this->_db->query("SELECT log
									FROM recommendation_log 
									WHERE user_id = ?
									ORDER BY log_date DESC
									LIMIT 1 ", array($user_id));
		
		$row = $query->row();
		return $row->log;
	}
	
	function saveRecommendationMailerLog($recommendation_log)
	{
		$this->initiateModel('write');
		if(is_array($recommendation_log) && count($recommendation_log))
		{
			foreach($recommendation_log as $user_id => $log)
			{
				$data = array(
							   'user_id' => $user_id ,
							   'log' => gzuncompress(base64_decode($log)),
							   'log_date' => date('Y-m-d H:i:s')	
							);
				$this->_db->insert('recommendation_log', $data);							
			}
		} 
	}

	/************************************************
	 * END FUNCTIONS FOR RECOMMENDATION MAILER TESTING
	 ************************************************/
	
	/************************************************
	 * FUNCTIONS FOR RECOMMENDATION RESPONSE CAPTURE
	 ************************************************/
	
	function registerClickOnRecommendation($recommendation_id,$listing_id,$listing_type)
	{
		$this->initiateModel('write');
		if($listing_type == 'course')
		{
			$query = $this->_db->query("UPDATE recommendations_courses_info
										SET actionTaken = 'clicked',actionTakenAt = ? 
										WHERE recommendationID = ?
										AND courseID = ? ", array(date('Y-m-d H:i:s'), $recommendation_id, $listing_id));
		}
		else 
		{
			$query = $this->_db->query("UPDATE recommendations_info
										SET actionTaken = 'clicked',actionTakenAt = ?
										WHERE recommendationID = ?
										AND status = 'sent' ", array(date('Y-m-d H:i:s'), $recommendation_id));
		}
	}
	
	function registerApplyOnRecommendation($listing_id,$listing_type,$user_id)
	{
		$this->initiateModel('write');
		if($listing_type == 'institute')
		{
			$query = $this->_db->query("UPDATE recommendations_info
										SET actionTaken = 'applied',actionTakenAt = ? 
										WHERE userID = ?
										AND instituteID = ?
										AND actionTaken = 'clicked' ", array(date('Y-m-d H:i:s'), $user_id, $listing_id));
			
		}
		else if($listing_type == 'course')
		{
			$query = $this->_db->query("UPDATE recommendations_courses_info
										SET actionTaken = 'applied',actionTakenAt = ? 
										WHERE userID = ?
										AND courseID = ? 
										AND actionTaken = 'clicked' ", array(date('Y-m-d H:i:s'), $user_id, $listing_id));
		}
	}
	
	function getRecommendationDetails($recommendation_id)
	{
		$this->initiateModel();
		$query = $this->_db->query("SELECT rc.courseID,lm_c.listing_seo_url as course_url, lm_c.pack_type as coursePackType FROM recommendations_courses_info rc INNER JOIN listings_main lm_c ON lm_c.listing_type_id = rc.courseID WHERE lm_c.listing_type = 'course' and lm_c.status = 'live' AND rc.recommendationID = ? ", array($recommendation_id));
		$rows = $query->result();
		
		if(count($rows))
		{
			return $rows[0];
		}
	}
	
	/****************************************************
	 * END FUNCTIONS FOR RECOMMENDATION RESPONSE CAPTURE
	 ***************************************************/
		
	
	/************************************************
	 * FUNCTIONS FOR RECOMMENDATION CRON
	 ************************************************/
	
	function registerCron($cron_pid,$status,$ip_address)
	{
		$this->initiateModel('write');
		//if($status == RECO_CRON_ON)
			//$this->_db->query("TRUNCATE TABLE recommendation_cron");	
		
		$cron_pid = (int) $cron_pid;
	
		if($cron_pid > 0)
		{
			if($status == RECO_CRON_TERMINATE)
			{
				$this->_db->query("UPDATE recommendation_cron
								   SET status = '".RECO_CRON_TERMINATE."'
								   WHERE status = '".RECO_CRON_ON."' ");
				
				$status = RECO_CRON_ON;
			}
		
			$data = array(
						   'pid' => $cron_pid,
						   'start_time' => date('Y-m-d H:i:s'),
						   'status' => $status,
						   'ip_address' => $ip_address
	
						);
			if($this->_db->insert('recommendation_cron', $data))
			{
				$cron_id = $this->_db->insert_id();
				return $cron_id;
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			return false;
		}
	}
	
	function updateCron($cron_id,$status,$time_window)
	{
		$this->initiateModel('write');
		list($time_window_start,$time_window_end) = explode(";",$time_window);	
		
		$end_time = '0000-00-00 00:00:00';
		
		if($status == RECO_CRON_OFF)
		{
			$end_time = date('Y-m-d H:i:s');
		}
		
		$query = $this->_db->query("UPDATE recommendation_cron
					    SET status = ?,last_processed_time_window = ?,end_time = ?
					    WHERE id = ? ", array($status, $time_window_end, $end_time, $cron_id));
	}
	
	function getAlreadyRunningCron()
	{
		$this->initiateModel();
		$query = $this->_db->query("SELECT id,pid
									FROM recommendation_cron
									WHERE status = ? ", array(RECO_CRON_ON));
		$row = $query->row();
		
		return $row;
	}
	
	function getCronFailCount($cron_id)
	{
		$this->initiateModel();
		$query = $this->_db->query("SELECT count(*) as fail_count
									FROM recommendation_cron
									WHERE start_time > (SELECT start_time FROM recommendation_cron WHERE id = ?) 
									AND status = ? ", array($cron_id, RECO_CRON_FAIL));
		$row = $query->row();
		
		return $row->fail_count;
	}
	
	function getLastProcessedTimeWindow()
	{
		$this->initiateModel();
		$query = $this->_db->query("SELECT last_processed_time_window
									FROM recommendation_cron
									WHERE last_processed_time_window != '0000-00-00 00:00:00' 
									ORDER BY start_time DESC
									LIMIT 1 ");
		$row = $query->row();
		
		return $row->last_processed_time_window;
	}
	
	/************************************************
	 * END FUNCTIONS FOR RECOMMENDATION CRON
	 ************************************************/
	
	
	function getInstituteFromCourse($course_id,$id = false)
	{
		$this->initiateModel();
		$query = $this->_db->query("SELECT i.institute_id,i.institute_name
									FROM course_details c
									INNER JOIN institute i ON i.institute_id = c.institute_id
									WHERE c.course_id = ? ", array($course_id) );
		
		$row = $query->row();
		
		if($id)
		{	
			return $row->institute_id;
		}
		else
		{
			return $row->institute_name;
		}
	}
	
	function reset()
	{	
		/*
		$this->_db->query("TRUNCATE TABLE recommendations_info");
		$this->_db->query("TRUNCATE TABLE recommendations_courses_info");
		$this->_db->query("TRUNCATE TABLE recommendation_log");
		$this->_db->query("TRUNCATE TABLE tMailQueueTemp");
		*/
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
		
	function getCountry($institute_id)
	{
		$this->initiateModel();
		$query = $this->_db->query("SELECT country_id
									FROM categoryPageData 
									WHERE institute_id = ? 
									AND status = 'live'	", array($institute_id));
		$row = $query->row();
		return $row->country_id;
	}
	
	function getCity($course_id)
	{
		$this->initiateModel();
		$query = $this->_db->query("SELECT city_id
									FROM categoryPageData 
									WHERE course_id = ? 
									AND status = 'live'	", array($course_id));
		$row = $query->row();
		return $row->city_id;
	}

	function registerFeedback($userId,$mailerId,$mailId,$feedbackType)
	{
		$this->initiateModel('write');
		$data = array(
						'userId'=> $userId,
						'mailerId'=> $mailerId,
						'mailId'=> $mailId,
						'type'=> $feedbackType,
						'time'=> date('Y-m-d H:i:s')
					);
					
		$this->_db->insert('recommendation_feedback',$data);
	}

	
	public function getMahoutRecommendations($courseId)
	{
		$this->initiateModel();
		
		/**
		 * Fetch course subcategories
		 * We'll filter recommendations by course subcategory
		 */ 
		$sql = "SELECT institute_id, category_id FROM categoryPageData WHERE status = 'live' AND course_id = ?";
		$query = $this->_db->query($sql,array($courseId));
		
		$results = $query->result_array();
		
		$subCategories = array();
		$courseInstituteId = 0;
		
		foreach($results as $result) {
			$courseInstituteId = $result['institute_id'];
			$subCategories[] = $result['category_id'];
		}
		
		$subCategories = array_unique($subCategories);
		
		if(count($subCategories) == 0){
			return array();
		}
		
/*
		$sql = "(SELECT cd.course_id,cd.institute_id,m.affinity ".
				"FROM mahout_recommendations m, categoryPageData cd ".
                "WHERE cd.status = 'live' ".
				"AND cd.institute_id != '".$courseInstituteId."' ".
				"AND m.course_id = '".$courseId."' ".
				"AND cd.category_id IN (".implode(',',$subCategories).") ".
				"AND cd.course_id = m.r_course_id) ".
                "UNION ".
				"(SELECT cd.course_id,cd.institute_id,m.affinity ".
				"FROM mahout_recommendations m, categoryPageData cd ".
                "WHERE cd.status = 'live' ".
				"AND cd.institute_id != '".$courseInstituteId."' ".
				"AND m.r_course_id = '".$courseId."' ".
				"AND cd.category_id IN (".implode(',',$subCategories).") ".
				"AND cd.course_id = m.course_id) ".
				"ORDER BY affinity DESC LIMIT 9";
*/
	
		$sql = "SELECT distinct cd.course_id,cd.institute_id,m.affinity ".
                                "FROM mahout_recommendations_coo_v2 m, categoryPageData cd ".
                		"WHERE cd.status = 'live' ".
                                "AND cd.institute_id != ? ".
                                "AND m.course_id = ? ".
                                "AND cd.category_id IN (".implode(',',$subCategories).") ".
                                "AND cd.course_id = m.r_course_id ".
                                "ORDER BY affinity DESC LIMIT 9";

	
		$istart = microtime(TRUE);
		$query = $this->_db->query($sql, array($courseInstituteId, $courseId));
		$results = $query->result_array();
		$iend = microtime(TRUE);
		error_log("RECOX: query time: ".($iend-$istart)." -- ".$sql);		

		$recommendations = array();
		$map = array();
		foreach($results as $result) {
			if(!$map[$result['institute_id']."-".$result['course_id']]) {
				$recommendations[] = array($result['institute_id'],$result['course_id']);
				$map[$result['institute_id']."-".$result['course_id']] = TRUE;
			}
		}
		
		return $recommendations;
	}
	public function getCourseSubcategory($courseId)
	{
		$this->initiateModel('write');
		$dbHandle = $this->_db;
		
		$sql =  "SELECT b.name ".
				"FROM listing_category_table a ".
				"INNER JOIN categoryBoardTable b ON b.boardId = a.category_id ".
				"WHERE a.status = 'live' ".
				"AND a.listing_type_id = ? ".
				"AND a.listing_type = 'course' ".
				"ORDER BY a.listing_category_id ASC LIMIT 1";
				
		$query = $dbHandle->query($sql,array($courseId));
		$row = $query->row_array();
		return $row['name'];
	}
}
