<?php 
class ProductDeliveryModel extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';
    
	function __construct() {
		parent::__construct('Listing');
        $this->dbHandle = $this->getReadHandle();
		
		$this->logFileName = 'log_product_delivery_details_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;
    }
	
	function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
	
	public function getAllCategories() {
		error_log("Query: Fetch all categories, executing.....\n", 3, $this->logFilePath);
        $time_start = microtime_float();
        
        $categoryQuery = "
            SELECT
                cbt.boardId  as 'category_id',
                cbt.name     as 'category_name'
            FROM
                categoryBoardTable as cbt
            wHERE
                cbt.parentId = 1
        ";
        
        $categoryResult = $this->dbHandle->query($categoryQuery);
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query executed. Time taken: ".round($time, 4)." seconds\n", 3, $this->logFilePath);
		
		return $categoryResult;
	}
    
	public function getCategorySponsorDetails() {
		error_log("Query: Category sponsors details, executing.....\n", 3, $this->logFilePath);
        $time_start = microtime_float();
        
        $categorySponsorDetailsQuery = "
            SELECT
                tls.subcategory                     as 'subcategory_id',
                cbt.name                            as 'subcategory_name',
				cbt.parentId						as 'category_id',
                tls.cityid                          as 'city_id',
                cct.city_name                       as 'city_name',
                tls.clientid                        as 'client_id',
                tu.displayname                      as 'client_name',
                tls.listing_type_id                 as 'institute_id',
                DATE(tls.startdate)                 as 'start_date',
                DATE(tls.enddate)                   as 'end_date'
            FROM
                            tlistingsubscription    as tls
                INNER JOIN  countryCityTable        as cct  ON cct.city_id = tls.cityid
                INNER JOIN  tuser                   as tu   ON tu.userid   = tls.clientid
                INNER JOIN  categoryBoardTable      as cbt  ON cbt.boardId = tls.subcategory
            WHERE
                tls.countryid       = 2 AND
                tls.listing_type    = 'institute' AND
                !(YEAR(tls.enddate) < 2014) AND
                !(YEAR(tls.enddate) = 2014 && QUARTER(tls.enddate) < 2) AND
                !(YEAR(tls.startdate) = 2015 && QUARTER(tls.startdate) > 3)
            GROUP BY
                subcategory_id, city_id, client_id, institute_id, start_date, end_date
            ORDER BY
                subcategory_id
        ";
        
        $categorySponsorDetailsResult = $this->dbHandle->query($categorySponsorDetailsQuery);
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query executed. Time taken: ".round($time, 4)." seconds\n", 3, $this->logFilePath);
		
		return $categorySponsorDetailsResult;
	}
	
	
	
	public function getCoursesForInstitutes($institutesIdsString) {
		error_log("Query: Courses for institutes, executing.....\n", 3, $this->logFilePath);
        $time_start = microtime_float();
        
        $instituteCoursesQuery = "
            SELECT
				cd.institute_id               as 'institute_id',
				cd.course_id				  as 'course_id'
            FROM
                course_details  		as cd
			WHERE
                cd.institute_id IN (?) AND
				cd.status IN ('live','deleted')
            ORDER BY
                institute_id
        ";

        $instituteIds = explode(',',$institutesIdsString);
        
        $instituteCoursesQueryResult = $this->dbHandle->query($instituteCoursesQuery,array($instituteIds));
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query executed. Time taken: ".round($time, 4)." seconds\n", 3, $this->logFilePath);
		
		return $instituteCoursesQueryResult;
	}
	
	public function getSubcatForCourses($courseIdsString) {
		error_log("Query: Subcat for courses, executing.....\n", 3, $this->logFilePath);
        $time_start = microtime_float();
        
        $instituteCoursesQuery = "
            SELECT
				lct.listing_type_id			  as 'course_id',
				lct.category_id				  as 'subcategory_id'
            FROM
                listing_category_table  as lct
			WHERE
                lct.listing_type_id IN (?) AND
				lct.listing_type = 'course' AND
				lct.status IN ('live','deleted')
            GROUP BY
                course_id, subcategory_id
        ";

        $courseIds = explode(',',$courseIdsString);
        
        $instituteCoursesQueryResult = $this->dbHandle->query($instituteCoursesQuery,array($courseIds));
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query executed. Time taken: ".round($time, 4)." seconds\n", 3, $this->logFilePath);
		
		return $instituteCoursesQueryResult;
	}
	
	public function getCityForCourses($courseIdsString) {
		error_log("Query: City for courses, executing.....\n", 3, $this->logFilePath);
        $time_start = microtime_float();
        
        $instituteCoursesQuery = "
            SELECT
				cla.course_id				  as 'course_id',
				ilt.city_id					  as 'city_id'
            FROM
                            course_location_attribute as cla
                INNER JOIN  institute_location_table as ilt ON ilt.institute_location_id = cla.institute_location_id and ilt.country_id = 2 and ilt.status IN ('live','deleted')
			WHERE
                cla.course_id IN (?) AND
				cla.status IN ('live','deleted')
            GROUP BY
                course_id, city_id
        ";

        $courseIds = explode(',',$courseIdsString);
        
        $instituteCoursesQueryResult = $this->dbHandle->query($instituteCoursesQuery,array($courseIds));
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query executed. Time taken: ".round($time, 4)." seconds\n", 3, $this->logFilePath);
		
		return $instituteCoursesQueryResult;
	}
	
	public function getCourseResponsesForCategorySponsor($courseIdsString) {
		error_log("Query: Course responses for category sponsor, executing.....\n", 3, $this->logFilePath);
        $time_start = microtime_float();
        
        $instituteCoursesQuery = "
            SELECT
                cd.course_id               	  as 'course_id',
				tls.subcategory				  as 'subcategory_id',
				tls.cityid					  as 'city_id',
				COUNT(*)                      as 'responses',
                MONTH(tlms.submit_date)       as 'month',
                QUARTER(tlms.submit_date)     as 'quarter',
                YEAR(tlms.submit_date)        as 'year'
            FROM
                            course_details  		as cd
				INNER JOIN	tlistingsubscription 	as tls  ON tls.listing_type_id = cd.institute_id
				INNER JOIN  tempLMSTable    		as tlms ON tlms.listing_type_id = cd.course_id and
																tlms.listing_type = 'course' and
																(YEAR(tlms.submit_date) = 2014 OR YEAR(tlms.submit_date) = 2015) AND
                                                                (!(YEAR(tlms.submit_date) = 2014 && QUARTER(tlms.submit_date) < 2)) AND
                                                                (!(YEAR(tlms.submit_date) = 2015 && QUARTER(tlms.submit_date) > 3)) AND
																DATE(tls.startdate) <= DATE(tlms.submit_date) AND DATE(tlms.submit_date) <= DATE(tls.enddate) AND
                                                                LOWER(tlms.action) LIKE '%client%'
			WHERE
                cd.course_id IN (?) AND
				cd.status IN ('live','deleted')
            GROUP BY
                course_id, subcategory_id, city_id, month, year
        ";

        $courseIds = explode(',',$courseIdsString);
        
        $instituteCoursesQueryResult = $this->dbHandle->query($instituteCoursesQuery,array($courseIds));
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query executed. Time taken: ".round($time, 4)." seconds\n", 3, $this->logFilePath);
		
		return $instituteCoursesQueryResult;
	}
	
	public function getGoldListingDetails() {
		error_log("Query: Gold listing details, executing.....\n", 3, $this->logFilePath);
        $time_start = microtime_float();
        
        $goldListingDetailsQuery = "
            SELECT
                lct.category_id                         as 'subcategory_id',
                cbt.name                                as 'subcategory_name',
				cbt.parentId							as 'category_id',
                ilt.city_id                             as 'city_id',
                cct.city_name                           as 'city_name',
                lm.username                             as 'client_id',
                tu.displayname                          as 'client_name',
                lm.listing_type_id                      as 'course_id',
                DATE(sl.ConsumptionStartDate)           as 'start_date',
                DATE(sl.ConsumptionEndDate)             as 'end_date'
            FROM
                            listings_main               as lm
                INNER JOIN  SUMS.SubscriptionLog        as sl  ON sl.SubscriptionId               = lm.subscriptionId AND
                                                                  sl.ConsumedId                   = lm.listing_type_id AND
																  sl.ConsumedIdType				  = 'course' AND
																  !(YEAR(sl.ConsumptionEndDate) < 2014) AND
                                                                  !(YEAR(sl.ConsumptionEndDate) = 2014 && QUARTER(sl.ConsumptionEndDate) < 2) AND
                                                                  !(YEAR(sl.ConsumptionStartDate) = 2015 && QUARTER(sl.ConsumptionStartDate) > 3)
                
                INNER JOIN  course_location_attribute   as cla ON cla.course_id                   = lm.listing_type_id AND
                                                                  cla.attribute_type              = 'HEAD OFFICE' AND
                                                                  cla.attribute_value             = 'TRUE' AND
                                                                  cla.status = 'live'
                
                INNER JOIN  institute_location_table    as ilt ON ilt.institute_location_id       = cla.institute_location_id AND
                                                                  ilt.country_id                  = 2 AND
                                                                  ilt.status                      = 'live'
                
                INNER JOIN  listing_category_table      as lct ON lct.listing_type_id             = lm.listing_type_id AND
                                                                  lct.listing_type                = 'course' AND
                                                                  lct.status                      = 'live'
                
                INNER JOIN  categoryBoardTable          as cbt ON cbt.boardId                     = lct.category_id
                
                INNER JOIN  countryCityTable            as cct ON cct.city_id                     = ilt.city_id
                
                INNER JOIN  tuser                       as tu  ON tu.userid                       = lm.username
            WHERE
                lm.listing_type = 'course' AND
                (lm.pack_type = 1 OR lm.pack_type = 375) AND
                lm.subscriptionId > 1 AND
				lm.status IN ('deleted','live')
            GROUP BY
                subcategory_id, city_id, client_id, course_id, start_date, end_date
            ORDER BY
                subcategory_id
        ";
        
        $goldListingDetailsResult = $this->dbHandle->query($goldListingDetailsQuery);
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query executed. Time taken: ".round($time, 4)." seconds\n", 3, $this->logFilePath);
		
		return $goldListingDetailsResult;
	}
	
	public function getCourseResponsesForGoldListing($courseIdsString) {
		error_log("Query: Course responses for gold listing, executing.....\n", 3, $this->logFilePath);
        $time_start = microtime_float();
        
        $courseResponsesQuery = "
            SELECT
                lm.listing_type_id                      as 'course_id',
                COUNT(*)                      			as 'responses',
                MONTH(tlms.submit_date)       			as 'month',
                QUARTER(tlms.submit_date)     			as 'quarter',
                YEAR(tlms.submit_date)        			as 'year'
            FROM
                            listings_main               as lm
                INNER JOIN  SUMS.SubscriptionLog        as sl  ON sl.SubscriptionId               = lm.subscriptionId AND
                                                                  sl.ConsumedId                   = lm.listing_type_id AND
																  sl.ConsumedIdType				  = 'course' AND
                                                                  !(YEAR(sl.ConsumptionEndDate) < 2014) AND
                                                                  !(YEAR(sl.ConsumptionEndDate) = 2014 && QUARTER(sl.ConsumptionEndDate) < 2) AND
                                                                  !(YEAR(sl.ConsumptionStartDate) = 2015 && QUARTER(sl.ConsumptionStartDate) > 3)
                
                INNER JOIN	tempLMSTable   				as tlms ON tlms.listing_type_id 		  = lm.listing_type_id AND
																   tlms.listing_type 			  = 'course' AND
																   (YEAR(tlms.submit_date) = 2014 OR YEAR(tlms.submit_date) = 2015) AND
                                                                   (!(YEAR(tlms.submit_date) = 2014 && QUARTER(tlms.submit_date) < 3)) AND
                                                                   (!(YEAR(tlms.submit_date) = 2015 && QUARTER(tlms.submit_date) > 3)) AND
                                                                   LOWER(tlms.action)             LIKE '%client%' AND
																   DATE(tlms.submit_date)         >= DATE(sl.ConsumptionStartDate) AND
																   DATE(tlms.submit_date) 		  <= DATE(sl.ConsumptionEndDate)
			WHERE
                lm.listing_type_id IN (?) AND
				lm.listing_type = 'course' AND
				(lm.pack_type = 1 OR lm.pack_type = 375) AND
                lm.subscriptionId > 1 AND
				lm.status IN ('deleted','live')
            GROUP BY
                course_id, month, year
        ";

        $courseIds = explode(',',$courseIdsString);
        
        $courseResponsesQueryResult = $this->dbHandle->query($courseResponsesQuery,array($courseIds));
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query executed. Time taken: ".round($time, 4)." seconds\n", 3, $this->logFilePath);
		
		return $courseResponsesQueryResult;
	}
	
	public function getMainListingDetails() {
		error_log("Query: Main listing details, executing.....\n", 3, $this->logFilePath);
        $time_start = microtime_float();
        
        $mainListingDetailsQuery = "
            SELECT
                tpkc.subCategoryId                   as 'subcategory_id',
                cbt.name                             as 'subcategory_name',
				cbt.parentId						 as 'category_id',
                tpkc.cityId                          as 'city_id',
                cct.city_name                        as 'city_name',
                lm.username                          as 'client_id',
                tu.displayname                       as 'client_name',
                pcdb.listing_type_id                 as 'institute_id',
                DATE(pcdb.StartDate)                 as 'start_date',
                DATE(pcdb.EndDate)                   as 'end_date'
            FROM
                            PageCollegeDb           as pcdb
                INNER JOIN  tPageKeyCriteriaMapping as tpkc ON tpkc.keyPageId = pcdb.KeyId AND tpkc.countryId = 2
                INNER JOIN  listings_main           as lm   ON lm.listing_type_id = pcdb.listing_type_id AND lm.listing_type = 'institute' AND lm.status IN ('deleted','live')
                INNER JOIN  countryCityTable        as cct  ON cct.city_id = tpkc.cityId
                INNER JOIN  tuser                   as tu   ON tu.userid   = lm.username
                INNER JOIN  categoryBoardTable      as cbt  ON cbt.boardId = tpkc.subCategoryId
            WHERE
                pcdb.listing_type       = 'institute' AND
                !(YEAR(pcdb.EndDate) < 2014) AND
                !(YEAR(pcdb.EndDate) = 2014 && QUARTER(pcdb.EndDate) < 2) AND
                !(YEAR(pcdb.StartDate) = 2015 && QUARTER(pcdb.StartDate) > 3)
            GROUP BY
                subcategory_id, city_id, client_id, institute_id, start_date, end_date
            ORDER BY
                subcategory_id
        ";
        
        $mainListingDetailsResult = $this->dbHandle->query($mainListingDetailsQuery);
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query executed. Time taken: ".round($time, 4)." seconds\n", 3, $this->logFilePath);
		
		return $mainListingDetailsResult;
	}
	
	public function getCourseResponsesForMainListing($courseIdsString) {
		error_log("Query: Course responses for main listings, executing.....\n", 3, $this->logFilePath);
        $time_start = microtime_float();
        
        $instituteResponsesQuery = "
            SELECT
                cd.course_id               	  as 'course_id',
				tpkc.subCategoryId			  as 'subcategory_id',
				tpkc.cityId					  as 'city_id',
				COUNT(*)                      as 'responses',
                MONTH(tlms.submit_date)       as 'month',
                QUARTER(tlms.submit_date)     as 'quarter',
                YEAR(tlms.submit_date)        as 'year'
            FROM
                            course_details  		as cd
				INNER JOIN  tempLMSTable    		as tlms ON tlms.listing_type_id = cd.course_id and 
                                                               tlms.listing_type = 'course' and 
                                                               (YEAR(tlms.submit_date) = 2014 OR YEAR(tlms.submit_date) = 2015) AND 
                                                               (!(YEAR(tlms.submit_date) = 2014 && QUARTER(tlms.submit_date) < 2)) AND 
                                                               (!(YEAR(tlms.submit_date) = 2015 && QUARTER(tlms.submit_date) > 3)) AND
                                                               LOWER(tlms.action) LIKE '%client%'
				INNER JOIN	PageCollegeDb           as pcdb ON pcdb.listing_type_id = cd.institute_id AND DATE(pcdb.StartDate) <= DATE(tlms.submit_date) AND DATE(tlms.submit_date) <= DATE(pcdb.EndDate)
                INNER JOIN  tPageKeyCriteriaMapping as tpkc ON tpkc.keyPageId = pcdb.KeyId AND tpkc.countryId = 2
			WHERE
                cd.course_id IN (?) AND
                cd.status IN ('live','deleted')
            GROUP BY
                course_id, subcategory_id, city_id, month, year
        ";

        $courseIds = explode(',',$courseIdsString);
        
        $instituteResponsesQueryResult = $this->dbHandle->query($instituteResponsesQuery,array($courseIds));
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query executed. Time taken: ".round($time, 4)." seconds\n", 3, $this->logFilePath);
		
		return $instituteResponsesQueryResult;
	}
	
	public function getAbroadCategorySponsorDetails() {
        
        $categorySponsorDetailsQuery = "
            SELECT
                cbt.name                            as 'category_name',
				tls.categoryid						as 'category_id',
                ct.name                       		as 'country_name',
                tls.clientid                        as 'client_id',
                tu.displayname                      as 'client_name',
                tls.listing_type_id                 as 'university_id',
                DATE(tls.startdate)                 as 'start_date',
                DATE(tls.enddate)                   as 'end_date'
            FROM
                            tlistingsubscription    as tls
                INNER JOIN  countryTable	        as ct  ON ct.countryId = tls.countryid
                INNER JOIN  tuser                   as tu   ON tu.userid   = tls.clientid
                INNER JOIN  categoryBoardTable      as cbt  ON cbt.boardId = tls.categoryid
            wHERE
                tls.countryid       > 2 AND
                tls.listing_type    = 'university' AND
				tls.startdate 		>= '2014-06-01'
            GROUP BY
                client_id,category_id, university_id, start_date, end_date
            ORDER BY
                category_id
        ";
        
        $categorySponsorDetailsResult = $this->dbHandle->query($categorySponsorDetailsQuery)->result_array();
        
		return $categorySponsorDetailsResult;
	}
	
	public function getAbroadGoldListingsDetails(){
		$goldListingDetailsQuery = "
            SELECT
                lct.category_id                         as 'subcategory_id',
                cbt2.name                               as 'category_name',
				cbt.parentId							as 'category_id',
                ult.country_id							as 'country_id',
                ct.name                           		as 'country_name',
                lm.username                             as 'client_id',
                tu.displayname                          as 'client_name',
                lm.listing_type_id                      as 'course_id',
                DATE(sl.ConsumptionStartDate)           as 'start_date',
                DATE(sl.ConsumptionEndDate)             as 'end_date'
            FROM
                            listings_main               	as lm
                INNER JOIN  SUMS.SubscriptionLog        	as sl  	ON 	sl.SubscriptionId				= lm.subscriptionId AND
																		sl.ConsumedId					= lm.listing_type_id AND
																		sl.ConsumedIdType				= 'course' AND
																		sl.ConsumptionStartDate			>= '2014-06-01 00:00:00'
																		
				INNER JOIN 	course_details					as cd 	ON 	lm.listing_type_id 				= cd.course_id
				INNER JOIN	institute_university_mapping	as ium 	ON	ium.institute_id 				= cd.institute_id
                                
                INNER JOIN  university_location_table   	as ult 	ON 	ult.university_id       		= ium.university_id AND
																		ult.country_id					> 2 AND
																		ult.status    					= 'live'
                
                INNER JOIN  listing_category_table      	as lct 	ON 	lct.listing_type_id             = lm.listing_type_id AND
																		lct.listing_type                = 'course' AND
																		lct.status                      = 'live'
                
                INNER JOIN  categoryBoardTable          	as cbt 	ON 	cbt.boardId                     = lct.category_id
                
                INNER JOIN  countryTable            		as ct 	ON 	ct.countryId					= ult.country_id
                
                INNER JOIN  tuser                       	as tu  	ON 	tu.userid                       = lm.username
				INNER JOIN 	categoryBoardTable				as cbt2 ON cbt.parentId 					= cbt2.boardId
            WHERE
                lm.listing_type = 'course' AND
                (lm.pack_type = 1 OR lm.pack_type = 375) AND
                lm.subscriptionId > 1 AND
				lm.status IN ('deleted','live')
            GROUP BY
                category_id, country_id, client_id, course_id, start_date, end_date
            ORDER BY
                subcategory_id
        ";
        $goldListingDetailsResult = $this->dbHandle->query($goldListingDetailsQuery)->result_array();
        
		return $goldListingDetailsResult;
	}
	
	public function getAbroadMainListingsDetails(){
		$mainListingDetailsQuery = "
            SELECT
                tpkc.categoryId							as 'category_id',
                cbt.name								as 'category_name',
                tpkc.countryId							as 'country_id',
                ct.name									as 'country_name',
                lm.username								as 'client_id',
                tu.displayname							as 'client_name',
                pcdb.listing_type_id					as 'university_id',
                DATE(pcdb.StartDate)					as 'start_date',
                DATE(pcdb.EndDate)						as 'end_date'
            FROM
                            PageCollegeDb           as pcdb
                INNER JOIN  tPageKeyCriteriaMapping as tpkc ON tpkc.keyPageId = pcdb.KeyId AND tpkc.countryId > 2
                INNER JOIN  listings_main           as lm   ON lm.listing_type_id = pcdb.listing_type_id AND lm.listing_type = 'university' AND lm.status IN ('deleted','live')
                INNER JOIN  countryTable        	as ct  	ON ct.countryId = tpkc.countryId
                INNER JOIN  tuser                   as tu   ON tu.userid   = lm.username
                INNER JOIN  categoryBoardTable      as cbt  ON cbt.boardId = tpkc.categoryId
            WHERE
                pcdb.listing_type       = 'university' AND
                pcdb.StartDate
            GROUP BY
                category_id, country_id, client_id, university_id, start_date, end_date
            ORDER BY
                category_id
        ";
        $mainListingDetailsResult = $this->dbHandle->query($mainListingDetailsQuery)->result_array();
        
		return $mainListingDetailsResult;
	}
	
	public function getCoursesOfUniversity($univId,$categoryId){
		if($categoryId){
			$add = " AND category_id = ?";
		}
		else{
			$add = "";
		}
		$sql = "select distinct course_id from abroadCategoryPageData where university_id = ? ".$add;
		$result = $this->dbHandle->query($sql,array($univId,$categoryId))->result_array();
		$finalArray = array();
		foreach($result as $row){
			$finalArray[] = reset($row);
		}
		return $finalArray;
	
	}
	public function getResponseCountOfCourses($courseIdArray,$startDate,$endDate){
		$courseIdArray = array_filter($courseIdArray,is_numeric);
		if(empty($courseIdArray)){
			return 0;
		}
		$sql = "select count(*) from tempLMSTable where listing_type_id  in (?) and listing_type = 'course' and submit_date between ? and ?";
		$result = reset(reset($this->dbHandle->query($sql,array($courseIdArray,$startDate,$endDate))->result_array()));
		return $result;
	}
}