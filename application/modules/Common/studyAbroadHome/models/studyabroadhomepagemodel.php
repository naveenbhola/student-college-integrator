<?php
class StudyAbroadHomepageModel extends MY_Model
{
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct()
	{ 
		parent::__construct('Listing');
	}
	
	private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}
    
	function getCoverageStats(){
		$this->initiateModel();

		//Get University Count
		$sql = "SELECT count(*) AS universityCount FROM listings_main WHERE listing_type = 'university' AND status = '".ENT_SA_PRE_LIVE_STATUS."'";
		$query = $this->dbHandle->query($sql);
		$results = $query->row();
		$res['universityCount'] = $results->universityCount;
		
		//Get Courses Count
		$sql = "SELECT count(*) AS courseCount FROM course_location_attribute c, institute_location_table i WHERE c.institute_location_id = i.institute_location_id AND i.country_id > 2 AND i.status = '".ENT_SA_PRE_LIVE_STATUS."' and c.status = '".ENT_SA_PRE_LIVE_STATUS."' and c.attribute_type = 'Head office' and c.attribute_value = 'TRUE'";
		$query = $this->dbHandle->query($sql);
		$results = $query->row();
		$res['courseCount'] = $results->courseCount;
		
		//Get Country Count
		$sql = "SELECT count(*) AS countryCount FROM ".ENT_SA_COUNTRY_TABLE_NAME." WHERE countryId > 2 and showOnRegistration='YES' ";
		$query = $this->dbHandle->query($sql);
		$results = $query->row();
		$res['countryCount'] = $results->countryCount;

		return $res;
	}

	function getArticles(){
		$this->initiateModel();
		$res = array();
		
		//$orderAlgo = " 5000 * ( 5+(viewCount-10)*3+(commentCount-3)*1.5 ) / POWER( 2+now()-created , 3.2 ) ";
		//$sql = "SELECT * FROM sa_content WHERE status = '".ENT_SA_PRE_LIVE_STATUS."' AND type = 'article' ORDER BY $orderAlgo desc LIMIT 12";
		$sql = "SELECT 
				id,
				content_id,
				type,
				exam_id as exam_type,
				title,
				title as strip_title,
				summary,
				seo_title,
				seo_description,
				seo_keywords,
				is_downloadable,
				download_link,
				content_image_url as contentImageURL,
				content_url as contentURL,
				view_count as viewCount,
				comment_count as commentCount,
				created_on as created,
				updated_on as last_modified,
				status,
				created_by,
				updated_by as last_modified_by,
				related_date as relatedDate,
				published_on as contentUpdatedAt,
				popularity_count as popularityCount,
				is_homepage,
				apply_content_type_id 
		FROM sa_content WHERE status = '".ENT_SA_PRE_LIVE_STATUS."' AND type = 'article'";
		$query = $this->dbHandle->query($sql);
		$i = 0;
		foreach ($query->result_array() as $row) {
			$row['strip_title'] =html_entity_decode(strip_tags($row['strip_title']),ENT_NOQUOTES, 'UTF-8');
			$row['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$row['contentURL'];
			$row['contentImageURL'] = MEDIAHOSTURL.$row['contentImageURL'];
			if($row['download_link']!= ''){
				$row['download_link'] = MEDIAHOSTURL.$row['download_link'];
			}
			$res[$i] = $row;
			$res[$i]['popularityCount'] = $this->_getArticlePopularity($row);
			$i++;
		}
		
		//After getting all the Information about articles + their popularity, sort them on the basis of Popularity
		usort($res,function($c1,$c2){
			return ($c1['popularityCount']>$c2['popularityCount'])?-1:1;
		});
		
		//Now, extract top 12 Articles
		for ($i = 0; $i < 12; $i++){
			$result[$i] = $res[$i];
		}

		return $result;		
	}

	function _getArticlePopularity($articleInfo){
		/*
		 * Algo is: 5000 * ( 5+(viewCount-10)*3+(commentCount-3)*1.5 ) / POWER( 2+now()-created , 3.2 )
		 */
		$popularityCount = 5000 * (5 + ($articleInfo['viewCount'] - 10) * 3 + ($articleInfo['commentCount'] - 3) * 1.5);
		//$dateDifference = (((date("M d Y ")) - (date($articleInfo['created'],'M d Y ')))/3600/24);
		$dateDifference = floor((strtotime(date("M d Y ")) - (strtotime($articleInfo['created'])))/3600/24);
		$powerNumber = pow((2 + $dateDifference), 4.5);
		$popularityCount = $popularityCount / $powerNumber;
		return $popularityCount;
	}
	
	function getMostViewedCoursesData($countryArray){
		$this->initiateModel();
		$result = array();
		$today = date("Y-m-d");
		$dateCheck = date("Y-m-d", strtotime("-14 day"));
	    $countryIndexForSorting = array_flip($countryArray);
	    
	    $sql = "select name,countryId FROM ".ENT_SA_COUNTRY_TABLE_NAME." WHERE name in (?)";
	    $result = $this->dbHandle->query($sql , array($countryArray))->result_array();
	    foreach ($result as $key => $value) {
	    	$countryMapping[$value['countryId']] = $value['name'];
	    	$countryIds[] = $value['countryId'];
	    }
	    $sql = "SELECT courseId, max(totalCount) as totalCount, country_id as countryId FROM (SELECT a.listingId AS courseId, SUM(viewCount) AS totalCount FROM abroadListingViewCountDetails a WHERE a.listingType = 'course' AND a.viewDate > ? GROUP BY listingId) b,abroadCategoryPageData c WHERE c.course_id = b.courseId AND country_id IN (?) group BY country_id";
	    $result = $this->dbHandle->query($sql , array($dateCheck,$countryIds))->result_array();

	    $mostViewedCourseData = array();
	    foreach ($result as $key => $value) {
	    	$value['countryName'] = $countryMapping[$value['countryId']];
	    	$mostViewedCourseData[$countryIndexForSorting[$value['countryName']]] = $value;
	    }
	    ksort($mostViewedCourseData);
		return $mostViewedCourseData;			
	}


	function getCountryMapData($countryArray,$desiredCourses){
		$this->initiateModel();
		$res = array();
		$today = date("Y-m-d");
		$dateCheck = date("Y-m-d", strtotime("-14 day"));
	    
		foreach ($countryArray as $countryName){
			//Get the Country Id
			$sql = "select countryId FROM ".ENT_SA_COUNTRY_TABLE_NAME." WHERE name = ?";
			$query = $this->dbHandle->query($sql , array($countryName));
			$results = $query->row();
			$res[$countryName]['countryId'] = $results->countryId;
			
			//Get the Guide URL
			$sql = "SELECT
				content_url as contentURL
			FROM
				sa_content content,
				sa_content_attribute_mapping mapping
			WHERE
				content.content_id = mapping.content_id
				AND content.type = 'guide'
				AND content.status = 'live'
				AND mapping.status = 'live'
				AND mapping.attribute_mapping = 'country'
				AND mapping.attribute_id = (
				select countryId
				FROM ".ENT_SA_COUNTRY_TABLE_NAME." WHERE name = ?) ORDER BY content.created_on DESC LIMIT 1";
			$query = $this->dbHandle->query($sql , array($countryName));
			$results = $query->row();
			$res[$countryName]['guideURL'] = SHIKSHA_STUDYABROAD_HOME.$results->contentURL;
			
			//Get the Number of Universities
			$sql = "SELECT count(*) AS universityCount FROM university_location_table u WHERE u.status = '".ENT_SA_PRE_LIVE_STATUS."' AND u.country_id = (select countryId FROM ".ENT_SA_COUNTRY_TABLE_NAME." WHERE name = ?)";
			$query = $this->dbHandle->query($sql, array($countryName));
			$results = $query->row();
			$res[$countryName]['universityCount'] = $results->universityCount;
			
			//Get the top 3 Popular SubCat+level AND Top 3 LDB Courses
			$topSubCatCourses = $this->getTopSubCats($countryName);
			$topDesiredCourses = $this->getTopDesiredCourses($countryName,$desiredCourses);
			//Now, extract the top most 3 courses which needs to be displayed on the Frontend
			$res[$countryName]['topCourses'] = $this->getTopMostCourses($topSubCatCourses,$topDesiredCourses);
		}
		//_p($res);
		return $res;				
	}
	
	function getTopSubCats($countryName){
		$this->initiateModel();
		$res = array();
		$today = date("Y-m-d");
		$dateCheck = date("Y-m-d", strtotime("-14 day"));

		//Query to find the View count of every Sub-Category + Course Level combination of a country
		$sql = "SELECT SUM(viewCount) AS totalCount, cat.category_id AS SubCategoryId, cd.course_level_1 AS CourseLevel
			FROM course_details cd, institute_location_table loc, listing_category_table cat, abroadListingViewCountDetails viewC
			WHERE cd.institute_id = loc.institute_id AND cd.status='".ENT_SA_PRE_LIVE_STATUS."' AND
			loc.country_id = (select countryId FROM ".ENT_SA_COUNTRY_TABLE_NAME." WHERE name = ?) and loc.status = '".ENT_SA_PRE_LIVE_STATUS."'
			AND cat.listing_type='course' AND cat.listing_type_id = cd.course_id AND cat.status='".ENT_SA_PRE_LIVE_STATUS."'
			AND cd.course_id = viewC.listingId AND viewC.listingType = 'course' AND viewC.viewDate > ?  
			AND cd.course_level_1 IN ('Bachelors','Masters') GROUP BY cat.category_id,cd.course_level_1";

		$query = $this->dbHandle->query($sql, array($countryName,$dateCheck));
		$courseView = array();
		foreach ($query->result_array() as $row) {
			$courseView[] = $row;
		}

		//Now, find the total number of Courses in every Sub-Category + Course Level combination of a country
		$sql = "SELECT count(*) AS numberOfCourses, cat.category_id AS SubCategoryId, cd.course_level_1 AS CourseLevel
			FROM course_details cd, institute_location_table loc, listing_category_table cat
			WHERE cd.institute_id = loc.institute_id AND cd.status='".ENT_SA_PRE_LIVE_STATUS."' AND
			loc.country_id = (select countryId FROM ".ENT_SA_COUNTRY_TABLE_NAME." WHERE name = ?) and loc.status = '".ENT_SA_PRE_LIVE_STATUS."'
			AND cat.listing_type='course' AND cat.listing_type_id = cd.course_id AND cat.status='".ENT_SA_PRE_LIVE_STATUS."'
			AND cd.course_level_1 IN ('Bachelors','Masters') GROUP BY cat.category_id,cd.course_level_1";
		$query = $this->dbHandle->query($sql, array($countryName,$dateCheck));
		$courseCount = array();
		foreach ($query->result_array() as $row) {
			$courseCount[] = $row;
		}
		
		//Now, divide the View count with total courses, sort them in desc order and get top 3
		$i=0;
		$finalResult = array();
		foreach ($courseView as $courseViewDetails){
			foreach ($courseCount as $courseCountDetails){
				if( ($courseCountDetails['SubCategoryId'] == $courseViewDetails['SubCategoryId']) && ($courseCountDetails['CourseLevel'] == $courseViewDetails['CourseLevel']) ){
					$sortVal = $courseViewDetails['totalCount'] / $courseCountDetails['numberOfCourses'];
					$finalResult[$i]['subCategoryId'] = $courseCountDetails['SubCategoryId'];
					$finalResult[$i]['courseLevel'] = $courseCountDetails['CourseLevel'];
					$finalResult[$i]['sortVal'] = $sortVal;
					$i++;
				}
			}
		}
		//Sort on the basis of Sort val (which is ViewCount/Total courses)
		usort($finalResult,function($c1,$c2){
			return ($c1['sortVal']>$c2['sortVal'])?-1:1;
		});

		//Now, extract top 3 Courses
		$topCourses = array_slice($finalResult, 0, 3);
		return $topCourses;
	}

	function getTopDesiredCourses($countryName,$desiredCourses){
		$this->initiateModel();
		$res = array();
		$today = date("Y-m-d");
		$dateCheck = date("Y-m-d", strtotime("-14 day"));
		
		//Get the List of desired courses in Study abroad
                $specializationIds = array();
		foreach($desiredCourses as $desiredCourse){
			$specializationIds[] = $desiredCourse['SpecializationId'];
		}
                              
		//Query to find the View count of every Desired course of a country
		$sql = "SELECT SUM(viewCount) AS totalCount, t.SpecializationId, t.CourseName  
			FROM course_details cd, institute_location_table loc, tCourseSpecializationMapping t, abroadListingViewCountDetails viewC, clientCourseToLDBCourseMapping map 
			WHERE cd.institute_id = loc.institute_id AND cd.status='".ENT_SA_PRE_LIVE_STATUS."' AND
			loc.country_id = (select countryId FROM ".ENT_SA_COUNTRY_TABLE_NAME." WHERE name = ?) and loc.status = '".ENT_SA_PRE_LIVE_STATUS."'
			AND t.SpecializationId IN (?) AND map.LDBCourseID = t.SpecializationId AND map.clientCourseID = cd.course_id AND map.status = '".ENT_SA_PRE_LIVE_STATUS."' 
			AND cd.course_id = viewC.listingId AND viewC.listingType = 'course' AND viewC.viewDate > ?  
			GROUP BY t.SpecializationId";

		$query = $this->dbHandle->query($sql, array($countryName, $specializationIds, $dateCheck));
                               
		$courseView = array();
		foreach ($query->result_array() as $row) {
			$courseView[] = $row;
		}

		//Now, find the total number of Courses in every Desired Course of a country
		$sql = "SELECT count(*) AS numberOfCourses, t.SpecializationId, t.CourseName  
			FROM course_details cd, institute_location_table loc, tCourseSpecializationMapping t, clientCourseToLDBCourseMapping map 
			WHERE cd.institute_id = loc.institute_id AND cd.status='".ENT_SA_PRE_LIVE_STATUS."' AND
			loc.country_id = (select countryId FROM ".ENT_SA_COUNTRY_TABLE_NAME." WHERE name = ?) and loc.status = '".ENT_SA_PRE_LIVE_STATUS."'
			AND t.SpecializationId IN (?) AND map.LDBCourseID = t.SpecializationId AND map.clientCourseID = cd.course_id AND map.status = '".ENT_SA_PRE_LIVE_STATUS."' 			
			GROUP BY t.SpecializationId";

		$query = $this->dbHandle->query($sql, array($countryName, $specializationIds, $dateCheck));
                
		$courseCount = array();
		foreach ($query->result_array() as $row) {
			$courseCount[] = $row;
		}
		
		//Now, divide the View count with total courses, sort them in desc order and get top 3
		$i=0;
		$finalResult = array();
		foreach ($courseView as $courseViewDetails){
			foreach ($courseCount as $courseCountDetails){
				if( ($courseCountDetails['SpecializationId'] == $courseViewDetails['SpecializationId']) ){
					$sortVal = $courseViewDetails['totalCount'] / $courseCountDetails['numberOfCourses'];
					$finalResult[$i]['SpecializationId'] = $courseCountDetails['SpecializationId'];
					$finalResult[$i]['CourseName'] = $courseCountDetails['CourseName'];
					$finalResult[$i]['sortVal'] = $sortVal;
					$i++;
				}
			}
		}
		//Sort on the basis of Sort val (which is ViewCount/Total courses)
		usort($finalResult,function($c1,$c2){
			return ($c1['sortVal']>$c2['sortVal'])?-1:1;
		});

		//Now, extract top 3 Courses
		$topCourses = array_slice($finalResult, 0, 3);
		return $topCourses;
	}

	function getTopMostCourses($topSubCatCourses,$topDesiredCourses){
		$topSubCatCourses = $this->sanitizeArray($topSubCatCourses);
		$topDesiredCourses = $this->sanitizeArray($topDesiredCourses);
				
		$final = array_merge($topSubCatCourses,$topDesiredCourses);
		usort($final,function($c1,$c2){
			return ($c1['sortVal']>$c2['sortVal'])?-1:1;
		});
		return array_slice($final, 0, 3);		
	}
	
	function sanitizeArray($inputArray){
		foreach($inputArray as $key=>$value)
		{
		    if(is_null($value) || $value == '')
			unset($inputArray[$key]);
		}		
		return $inputArray;
	}
}
