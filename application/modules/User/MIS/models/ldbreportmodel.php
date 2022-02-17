<?php
/* 
    Model 
    Following is the example this model can be used in the server controllers.
*/

class Ldbreportmodel extends MY_Model {
	private $dbHandle = '';
	function __construct(){
		parent::__construct('MIS');
	}
        
        protected function initiateModel($operation='read'){
		$appId = 1;	
		
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	        $this->dbHandle = $this->getWriteHandle();
		}		
	}
        
        
        function cronToGetMISInformation($tuserprefextraflag,$startdate,$enddate){
                $this->initiateModel();
             
			
		if($tuserprefextraflag == 'national'){
				
				$queryCmd = "	SELECT
						count(*) as Last_6_months,
						x.Category,
						x.PreferredCity,
						x.LDBCourseName
						FROM
						(
							SELECT
							tUserLocationPref.CityId,
							categoryBoardTable.name AS Category,
							countryCityTable.city_name AS PreferredCity,
							tCourseSpecializationMapping.CourseName as LDBCourseName
							FROM tUserPref
							INNER JOIN tUserLocationPref ON ( tUserPref.userId = tUserLocationPref.UserId )
							LEFT JOIN tCourseSpecializationMapping ON ( tUserPref.DesiredCourse = tCourseSpecializationMapping.SpecializationId )
							LEFT JOIN categoryBoardTable ON ( tCourseSpecializationMapping.CategoryId = categoryBoardTable.boardId )
							LEFT JOIN countryCityTable ON ( tUserLocationPref.CityId = countryCityTable.city_id )
							LEFT JOIN tuserflag ON (tUserPref.userid = tuserflag.userid)
							LEFT JOIN tuser ON (tUserPref.userid = tuser.userid)
							WHERE tUserPref.SubmitDate > ".$this->dbHandle->escape($startdate)."
							AND tUserPref.SubmitDate < '$enddate'
							AND (tUserPref.ExtraFlag = 'undecided' OR tUserPref.ExtraFlag IS NULL)
							AND tUserLocationPref.CityId > 0
							and mobileverified = '1'
							and tCourseSpecializationMapping.status = 'live'
							and tuser.usergroup NOT IN ('enterprise','cms','sums')
							GROUP BY tUserPref.UserId,tUserPref.DesiredCourse, countryCityTable.city_id
						) as x GROUP BY x.CityId,x.LDBCourseName
					";
					
					
					
		}
		elseif($tuserprefextraflag == 'studyabroad'){
				$queryCmd = "SELECT
						count( (tUserLocationPref.UserId) ) as Last_6_months,
						categoryBoardTable.name AS Category,
						countryTable.name AS PreferredAbroadCountry
						FROM tUserPref
						INNER JOIN tUserLocationPref ON ( tUserPref.userId = tUserLocationPref.UserId )
						LEFT JOIN tCourseSpecializationMapping ON ( tUserPref.DesiredCourse = tCourseSpecializationMapping.SpecializationId )
						LEFT JOIN categoryBoardTable ON ( tCourseSpecializationMapping.CategoryId = categoryBoardTable.boardId )
						LEFT JOIN countryTable ON ( tUserLocationPref.CountryId = countryTable.countryId )
						LEFT JOIN tuserflag ON (tUserPref.userid = tuserflag.userid)
						LEFT JOIN tuser ON (tUserPref.userid = tuser.userid)
						WHERE tUserPref.SubmitDate > ".$this->dbHandle->escape($startdate)."
						AND tUserPref.SubmitDate < ".$this->dbHandle->escape($enddate)."
						AND tUserPref.ExtraFlag = 'studyabroad'
						and tCourseSpecializationMapping.status = 'live'
						and mobileverified = '1'
						AND tUserLocationPref.CountryId > 0
						AND tUserLocationPref.CountryId != 2
						and tuser.usergroup NOT IN ('enterprise','cms','sums')
						GROUP BY categoryBoardTable.boardId, countryTable.countryId
						";
		}
		else{
				
				$queryCmd = "
					SELECT
					count(*) as Last_6_months,
					'Test Preparation' as Category,
					x.PreferredCity,
					x.LDBCourseName
					FROM
					(
						SELECT
						tUserLocationPref.CityId,
						countryCityTable.city_name AS PreferredCity,
						blogTable.acronym as LDBCourseName,
						categoryBoardTable.name AS Category
						FROM tUserPref
						INNER JOIN tUserLocationPref ON ( tUserPref.userId = tUserLocationPref.UserId )
						LEFT JOIN tUserPref_testprep_mapping ON ( tUserPref.PrefId =tUserPref_testprep_mapping.prefid)
						LEFT JOIN blogTable On (tUserPref_testprep_mapping.blogid = blogTable.blogId)
						LEFT JOIN categoryBoardTable On (categoryBoardTable.boardId = blogTable.boardId)
						LEFT JOIN countryCityTable ON ( tUserLocationPref.CityId = countryCityTable.city_id )
						LEFT JOIN countryTable ON ( countryTable.countryId = countryCityTable.countryId )
						LEFT JOIN tuserflag ON (tUserPref.userid = tuserflag.userid)
						LEFT JOIN tuser ON (tUserPref.userid = tuser.userid)
						WHERE tUserPref.SubmitDate > ".$this->dbHandle->escape($startdate)."
						and mobileverified = '1'
						AND tUserPref.SubmitDate < ".$this->dbHandle->escape($enddate)."
						AND (tUserPref.ExtraFlag = 'testprep')
						AND tUserLocationPref.CityId > 0
						and blogTable.status = 'live'
						and tuser.usergroup NOT IN ('enterprise','cms','sums')
						GROUP BY tUserPref.UserId,tUserPref_testprep_mapping.blogid, countryCityTable.city_id
					) as x GROUP BY x.CityId,x.LDBCourseName
					";
		}
	       
               
                $query = $this->dbHandle->query($queryCmd);
                $results = $query->result_array();
		
		if($tuserprefextraflag == 'national'){
		$stateresults = $this->cronToGetstateMISInformation($tuserprefextraflag,$startdate,$enddate);
		}
		
		$results = array_merge((array)$results, (array)$stateresults);
		
		$data = array();
                if(!empty($results) && is_array($results)) {
                        foreach ($results as $row){
                                if(($tuserprefextraflag == 'testprep' || $tuserprefextraflag == 'national') && !empty($row['LDBCourseName'])){
					$data[$row['LDBCourseName']."_".$row['PreferredCity']] = $row;
				}
				elseif($tuserprefextraflag == 'studyabroad' && !empty($row['Category'])){
					$data[$row['Category']."_".$row['PreferredAbroadCountry']] = $row;
				}
			}
                }
                return $data;
        
	}
	
        function cronToGetstateMISInformation($tuserprefextraflag,$startdate,$enddate){
                $this->initiateModel();
             
	     if($tuserprefextraflag == 'national'){
				
				$queryCmd = "SELECT
					count( (tUserLocationPref.UserId) ) as Last_6_months,
					categoryBoardTable.name AS Category,
					stateTable.state_name AS PreferredCity,
					tCourseSpecializationMapping.CourseName as LDBCourseName
					FROM tUserPref
					INNER JOIN tUserLocationPref ON ( tUserPref.userId = tUserLocationPref.UserId )
					LEFT JOIN tCourseSpecializationMapping ON ( tUserPref.DesiredCourse = tCourseSpecializationMapping.SpecializationId )
					LEFT JOIN categoryBoardTable ON ( tCourseSpecializationMapping.CategoryId = categoryBoardTable.boardId )
					LEFT JOIN stateTable ON ( tUserLocationPref.StateId = stateTable.state_id )
					LEFT JOIN tuserflag ON (tUserPref.userid = tuserflag.userid)
					LEFT JOIN tuser ON (tUserPref.userid = tuser.userid)
					WHERE tUserPref.SubmitDate > ?
					AND tUserPref.SubmitDate < ?
					AND (tUserPref.ExtraFlag = 'undecided' OR tUserPref.ExtraFlag IS NULL)
					AND tUserLocationPref.CityId = 0
					and mobileverified = '1'
					and tCourseSpecializationMapping.status = 'live'
					and tuser.usergroup NOT IN ('enterprise','cms','sums')
					GROUP BY tUserPref.DesiredCourse, stateTable.state_id
					";
					
					
					
		}
		
               
                $query = $this->dbHandle->query($queryCmd, array($startdate,$enddate));
                $results = $query->result_array();
		
                return $results;
        
	     
	}
        
        
}
        
