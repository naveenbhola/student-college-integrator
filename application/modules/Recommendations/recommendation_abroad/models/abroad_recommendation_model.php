<?php 

class Abroad_Recommendation_Model extends MY_Model
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
			$query = $this->_db->query("SELECT DISTINCT t.userId, t.action, cla.course_id, ilt.institute_id, ilt.country_id, ilt.city_id,c.course_level_1 as course_level 
						    FROM tempLMSTable t, institute_location_table ilt, course_location_attribute cla,course_details c
						    WHERE t.listing_type_id = cla.course_id AND cla.status = 'live'
						    AND cla.institute_location_id = ilt.institute_location_id AND ilt.status = 'live'
						    AND cla.attribute_type = 'Head Office' AND cla.attribute_value = 'TRUE'
						    AND c.course_id = t.listing_type_id and c.status = 'live'
						    AND t.userId IN (".implode(',',$users).") AND t.listing_type = 'course'
							AND ilt.country_id > 2
						    AND t.submit_date >= '".$date."' ");
			
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
	
	private function _getResultArray($query,$field)
	{
		$result = array();
		foreach($query->result() as $row)
		{
			$result[] = $row->$field;
		}
		return $result;
	}

	function getRecommendationDetails($recommendation_id)
	{
		$this->initiateModel();
		$query = $this->_db->query("SELECT r.userID,r.instituteID,r.status, r.categoryID, r.actionTaken,rc.courseID,
										   u.email,u.password,u.usercreationDate,u.randomkey,
										   lm_i.listing_seo_url as institute_url,lm_c.listing_seo_url as course_url, lm_i.pack_type as institutePackType, lm_c.pack_type as coursePackType
									FROM recommendations_info r
									LEFT JOIN recommendations_courses_info rc ON rc.recommendationID = r.recommendationID
									INNER JOIN tuser u ON u.userid = r.userID 
									INNER JOIN listings_main lm_i ON (lm_i.listing_type_id = r.instituteID AND lm_i.listing_type = 'institute' and lm_i.status = 'live')
									LEFT JOIN listings_main lm_c ON (lm_c.listing_type_id = rc.courseID AND lm_c.listing_type = 'course' and lm_c.status = 'live') 
									WHERE r.recommendationID = ? ", array($recommendation_id));
		$rows = $query->result();
		
		if(count($rows))
		{
			return $rows[0];
		}
	}

	/*
	 * Save recommendations in database
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
				
				$user_recommendations = $user_recommendation_data['recommendations'];
				foreach ($user_recommendations as $category_id => $category_recommendation_data)
				{
					$category_recommendations = $category_recommendation_data['recommendations'];
					foreach ($category_recommendations as $algo => $algo_recommendations)
					{
						foreach($algo_recommendations as $recommendation)
						{
                                                        if(empty($recommendation['institute_id'])) {
								continue;
                                                        }
                                                        
							$recommendation_algo = $algo;
							$institute_id = $recommendation['institute_id'];
							$course_id = $recommendation['course_id'];
							
							$data_recommendations_info = array(
											    'userID' => $user_id,
											    'instituteID' => $institute_id,
											    'categoryID' => $category_id,
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
				}
			}
			
			return $recommendation_ids;
		}
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

}
