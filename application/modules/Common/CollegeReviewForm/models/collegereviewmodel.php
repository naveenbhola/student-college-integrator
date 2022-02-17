<?php
class CollegeReviewModel extends MY_Model
{ /*

   Copyright 2014 Info Edge India Ltd

   $Author: Pranjul

   $Id: CollegeReviewModel

 */
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	private $dbHandle = '';
	function __construct(){
		parent::__construct('CollegePredictor');
	}
	/**
	 * returns a data base handler object
	 *
	 * @param none
	 * @return object
	 */

    private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
			$this->dbHandle = $this->getWriteHandle();
		}		
	}
	
	function submitReviewData($data,$reviewerId){
		$this->initiateModel('write');
		$sql = "insert into CollegeReview_MainTable (`userId`, `anonymousFlag`, `reviewDescription`, `placementDescription`, `infraDescription`, `facultyDescription`, `averageRating`, `recommendCollegeFlag`, `isShikshaInstitute`,`reviewSource`,`reviewerId`,`visitorSessionId`,`incentiveFlag`,`reviewTitle`,`modificationDate`,`fees`,`reviewInstituteId`,`reviewLocationId`,`reviewCourseId`,`reviewYearOfGraduation`) values (?,?,?,?,?,?,?,?, ?,?,?,?,?,?,now(),?,?,?,?,?)";

		$this->dbHandle->query($sql,array($data['userId'],$data['anonymous'],$data['reviewDescription'],$data['placementDescription'],$data['infraDescription'],$data['facultyDescription'],$data['averageRating'],$data['recommendCollegeFlag'],$data['isShikshaInst'],$data['reviewSource'],$reviewerId, $data['visitorSessionId'],$data['incentiveFlag'],$data['reviewTitle'] ,$data['fees'],$data['suggested_institutes'],$data['location'],$data['course'],$data['yearOfGraduation']));

		$last_insert_id = $this->dbHandle->insert_id();

		return $last_insert_id;

	}

	function insertIntoShikshaInstitute($data,$reviewId){
		$this->initiateModel('write');

		if($data['isShikshaInst']=='YES'){
			$sql = "insert into CollegeReview_MappingToShikshaInstitute (`reviewId`,`instituteId`, `locationId`, `courseId`, `yearOfGraduation`) values (?,?,?,?,?)";
			$this->dbHandle->query($sql,array($reviewId,$data['primary_institute_id'],$data['location'],$data['course'],$data['yearOfGraduation']));
		}else{
			$sql = "insert into CollegeReview_MappingToNonShikshaInstitute (`reviewId`, `instituteName`, `locationName`, `courseName`, `yearOfGraduation`) values (?,?,?,?,?)";
			$this->dbHandle->query($sql,array($reviewId,$data['suggested_institutes'],$data['location'],$data['course'],$data['yearOfGraduation']));
		}
	}
		
	function insertInMotivationTable($reviewId,$motivationMasterId){
		$this->initiateModel('write');	

		$sql = "INSERT INTO `CollegeReview_MotivationTable` (`reviewId`, `motivationMasterId`) VALUES (?,?)";
		$this->dbHandle->query($sql,array($reviewId,$motivationMasterId));

	}
	
	function getStatusOfReview($userId){
		$this->initiateModel('read');
		$sql = "select status from CollegeReview_MainTable where userId = ?";
		$query = $this->dbHandle->query($sql,array($userId));
		$results = $query->result_array();
		$status = '';
		$count = $query->num_rows();
		if($count>0){
			$status = $results[0]['status'];
		}
		return $status;
	}
	
	function getReviewData($data){
		$this->initiateModel('read');
		$results = array();
		foreach($data as $key=>$value){
			$userId  =  $value['userId'];
			$status  =  $value['status'];
			$sql = "select crmt.* , crmtsi.* from CollegeReview_MainTable crmt 
					join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id=crmtsi.reviewId) 
					where crmt.userId=? and crmt.status IN ('draft','accepted','rejected','published')
					 and crmtsi.instituteId>0 and crmtsi.courseId>0 and crmtsi.locationId>0
				UNION

				select * from CollegeReview_MainTable crmt 
				 join CollegeReview_MappingToNonShikshaInstitute crmtsi on (crmt.id=crmtsi.reviewId) 
				 where crmt.userId=? and crmt.status IN ('draft','accepted','rejected','published')";

			$query = $this->dbHandle->query($sql,array($userId,$userId));
			$data = $query->result_array();

			$data[0]['ratingParam'] = $this->getRatingReviewId($data[0]['reviewId']);
			
			$results[] = $data[0];
		}
		return $results;
	}
	
	
	function submitPersonalReviewData($data){
		$this->initiateModel('write');

		$sql = "INSERT INTO `shiksha`.`CollegeReview_PersonalInformation` (`firstname`, `lastname`, `email`, `isdCode` , `mobile`, `linkedInURL`, `facebookURL`, `creationDate`) VALUES (?, ?, ?, ?, ?, ?, ?, now());";

		$query = $this->dbHandle->query($sql,array($data['firstname'],$data['lastname'],$data['email'],$data['isdCode'],$data['mobile'],$data['linkedInURL'],$data['facebookURL']));

		$reviewerId = $this->dbHandle->insert_id();

		return $reviewerId;
	}
	
	function updateReviewData($last_insert_id_camaintable,$last_insert_id_presonalinfo){
		$this->initiateModel('write');
		$query = "UPDATE CollegeReview_MainTable
			  SET reviewerId=? ,modificationDate=now() where id=?";
		$query = $this->dbHandle->query($query,array($last_insert_id_presonalinfo,$last_insert_id_camaintable));
	}
	
	function getReviewDataWithPersonalDetails($parameters){
		$this->initiateModel('write');
		$sql = "select isShikshaInstitute from CollegeReview_MainTable where reviewerId = ?";
		$query = $this->dbHandle->query($sql,array($parameters['reviewerId']));
		$results = $query->result_array();
		
		if($results[0]['isShikshaInstitute']=='YES'){
			$sql = "select crmt.*, crpi.*, crmtsi.* from CollegeReview_MainTable crmt join CollegeReview_PersonalInformation crpi on (crmt.reviewerId=crpi.id) join CollegeReview_MappingToShikshaInstitute crmtsi on (crmtsi.reviewId=crmt.id) 
				where crpi.email=? and crpi.id=? and crmtsi.instituteId>0 and crmtsi.courseId>0 
				and crmtsi.locationId>0" ;	
		}else{
			$sql = "select crmt.*, crpi.*, crmtsi.* from CollegeReview_MainTable crmt join CollegeReview_PersonalInformation crpi on (crmt.reviewerId=crpi.id) join CollegeReview_MappingToNonShikshaInstitute crmtsi on (crmtsi.reviewId=crmt.id) 
				where crpi.email=? and crpi.id=?";
		}
		$query = $this->dbHandle->query($sql,array($parameters['email'], $parameters['reviewerId']));
		$results = $query->result_array();
		return $results[0];
	}
	
	function getReviewDataWithCRDetails($revid, $reviewId, $userId){
		$this->initiateModel('read');
		$sql = "select isShikshaInstitute from CollegeReview_MainTable where id = ?";
		$query = $this->dbHandle->query($sql,array($reviewId));
		$results = $query->result_array();
		
		if($results[0]['isShikshaInstitute']=='YES'){
			$sql = "select crmt.*, u.*, crmtsi.* from CollegeReview_MainTable crmt join tuser u on (crmt.userId=u.userid) join CollegeReview_MappingToShikshaInstitute crmtsi on (crmtsi.reviewId=crmt.id) where crmt.id = ? and u.userid = ? and crmt.userId>0 and crmtsi.instituteId>0 and crmtsi.courseId>0 and crmtsi.locationId>0";	
		}else{
			$sql = "select crmt.*, u.*, crmtsi.* from CollegeReview_MainTable crmt join tuser u on (crmt.userId=u.userid) join CollegeReview_MappingToNonShikshaInstitute crmtsi on (crmtsi.reviewId=crmt.id) where crmt.id = ? and u.userid = ? and crmt.userId>0";
		}
		$query = $this->dbHandle->query($sql,array($reviewId,$userId));
		$results = $query->result_array();
		return $results[0];
	}
	
	function checkIfDetailsExistInDB($email,$suggested_institutes,$course,$isShikshaInst){
		$this->initiateModel('read');
		if($isShikshaInst=='YES'){
			$sql = "select crmt.id, crmt.status from CollegeReview_MainTable crmt join CollegeReview_PersonalInformation crpi on (crmt.reviewerId=crpi.id) join CollegeReview_MappingToShikshaInstitute crmtsi on (crmtsi.reviewId=crmt.id) where crpi.email=? and crmtsi.instituteId=? and crmtsi.courseId=? and crmtsi.locationId>0";
		}else{
			$sql = "select crmt.id, crmt.status from CollegeReview_MainTable crmt join CollegeReview_PersonalInformation crpi on (crmt.reviewerId=crpi.id) join CollegeReview_MappingToNonShikshaInstitute crmtsi on (crmtsi.reviewId=crmt.id) where crpi.email=? and crmtsi.instituteName=? and crmtsi.courseName=?";
		}
		
		$query = $this->dbHandle->query($sql,array($email,$suggested_institutes,$course));
		
		$result = $query->result_array();
		return $result;
	}
	
	/*function updateMainTableData($data, $reviewerId, $reviewId){
		$this->initiateModel('write');
		$sql = "update CollegeReview_MainTable set `anonymousFlag`=?, `reviewDescription`=?, `moneyRating`=?, `crowdCampusRating`=?, `avgSalaryPlacementRating`=?, `campusFacilitiesRating`=?, `facultyRating`=?, `recommendCollegeFlag`=?, `isShikshaInstitute`=?, `status`=?, `modificationDate`=now() where id=?";
		$query = $this->dbHandle->query($sql,array($data['anonymous'],$data['reviewDescription'],$data['worthmoney'],$data['cclife'],$data['avgSalPlace'],$data['camFac'],$data['faculty'],$data['recommendCollegeFlag'],$data['isShikshaInst'],'draft',$reviewId));
		
		if($data['isShikshaInst']=='YES'){
			$sql = "update CollegeReview_MappingToShikshaInstitute set `yearOfGraduation` = ? where reviewId=?";
		}else{
			$sql = "update CollegeReview_MappingToNonShikshaInstitute set `yearOfGraduation` = ? where reviewId=?";
		}
		$this->dbHandle->query($sql,array($data['yearOfGraduation'], $reviewId));
		if($data['otherOption']=='NO'){
			$data['otherReason'] = '';
		}
		$subQuery = ", `crowdCampusLife` = 'NO', `salaryPlacement` = 'NO', `campusFacilities` = 'NO', otherReason='' ";
		$sql = "update `CollegeReview_MotivationTable` set `motivationFactor`=? $subQuery where reviewId=?";
		$this->dbHandle->query($sql,array($data['motivationFactor'], $reviewId));
		
	}*/

	function updateMainTableData($data, $reviewerId, $reviewId){
		if (empty($reviewId)) {
			return;
		}

		$this->initiateModel('write');

		$sql = "update CollegeReview_MainTable set `anonymousFlag`=?, `reviewDescription`=?, `placementDescription`=?, `infraDescription`=?, `facultyDescription`=?, `recommendCollegeFlag`=?, `isShikshaInstitute`=?, `status`=?, `modificationDate`=now(), averageRating = ?,`reviewTitle`=? where id=?";

		$query = $this->dbHandle->query($sql,array($data['anonymous'],$data['reviewDescription'],$data['placementDescription'],$data['infraDescription'],$data['facultyDescription'],$data['recommendCollegeFlag'],$data['isShikshaInst'],'draft',$data['averageRating'], $data['reviewTitle'], $reviewId));		
		
	}
	

	function updateRatingMapping($data, $reviewId){
		$this->initiateModel('write');

		if (empty($reviewId) || empty($data['ratingValues'])) {
			return;
		}

		$sql ="update CollegeReview_RatingMapping set status = 'history' where reviewId = ? ";
		$this->dbHandle->query($sql,array($reviewId));

		$ratingValues = $data['ratingValues'];
		$sumOfRating = 0;

		$sql ="insert into CollegeReview_RatingMapping (reviewId, masterRatingId, rating, status) VALUES ";

		foreach ($ratingValues as $key => $value) {
			$sumOfRating = $sumOfRating + $value; 
			
			$sql .= "('".$reviewId."','".$key."','".$value."','live'),";		
		}

		$sql = substr($sql, 0,-1);
		$this->dbHandle->query($sql);

	}

	function updateShikshaInstitute($data, $reviewId){
		$this->initiateModel('write');

		if($data['isShikshaInst']=='YES'){
			$newSql = "update CollegeReview_MainTable set `reviewLocationId` = ?,`reviewCourseId` = ?,`reviewYearOfGraduation` = ? where id=?";
			$this->dbHandle->query($newSql,array($data['location'],$data['course'],$data['yearOfGraduation'], $reviewId));
			
			$sql = "update CollegeReview_MappingToShikshaInstitute set `locationId` = ?,`courseId` = ?,`yearOfGraduation` = ? where reviewId=?";
		}else{
			$sql = "update CollegeReview_MappingToNonShikshaInstitute set `locationName` = ?, `courseName` = ?,`yearOfGraduation` = ? where reviewId=?";
		}

		$this->dbHandle->query($sql,array($data['location'],$data['course'],$data['yearOfGraduation'], $reviewId));
	}

	function updateMotivationTable($data, $reviewId){

		$sql = "update `CollegeReview_MotivationTable` set `motivationMasterId`=? where reviewId=? ";
		$this->dbHandle->query($sql,array($data['motivationFactor'],$reviewId));

	}

	function updatePersonalReviewData($personalData,$reviewId){
		
		$sql = "update CollegeReview_PersonalInformation set firstname=? , lastname=?, isdCode=? , mobile=?, linkedInURL=?, facebookURL=?, modificationDate=now() where id=?";

		$this->dbHandle->query($sql,array($personalData['firstname'],$personalData['lastname'],$personalData['isdCode'],$personalData['mobile'],$personalData['linkedInURL'],$personalData['facebookURL'], $reviewId));
	}

    function getReviewerId($email){
		$this->initiateModel('read');
		$sql = "select id from CollegeReview_PersonalInformation where email = ?";
		$query = $this->dbHandle->query($sql,array($email));
		$results = $query->result_array();
		if($results[0]['id']){
		    return $results[0]['id'];
		} else {
		    return 0;
		}
    }
	
	function getTileData($seoUrl,$stream){
	
		$this->initiateModel('read');
		$whereClause = '';
		if($stream != ""){
			$whereClause = " and streamId = ".$this->dbHandle->escape($stream);
		}
		if($seoUrl!=''){
			$whereClause .= " and seoUrl = ".$this->dbHandle->escape($seoUrl);
		}else{
			$whereClause .= " and subStatus='activated' ";
		}

		$sql = "select * from CollegeReview_Tile where status=? $whereClause order by tileOrder";
		$query = $this->dbHandle->query($sql,array('live'));		//added subStatus clause to display or not display tiles on the college reviews homepage

		$results = $query->result_array();
		return $results;
	}
	
	function getCMSTileData($streamId,$baseCourseId){
		$this->initiateModel('read');
		$sql = "select * from CollegeReview_Tile where streamId= ? and status=? and baseCourseId=? order by tilePlacement, tileOrder, publishDateTime";
		$query = $this->dbHandle->query($sql,array($streamId, 'live', $baseCourseId));
		$results = $query->result_array();
		return $results;
	}
	
	function addCMSTileData($data)
	{
		$this->db->insert('CollegeReview_Tile', $data);
	}
	
	function updateCMSTileData($tileId, $data)
	{
		$this->db->where('tileId', $tileId);
		$this->db->update('CollegeReview_Tile', $data); 
	}

	/*	

	*/
	
	function getNextLatestReviewForSlider($courseIds, $start=0, $count=1, $stream, $baseCourse, $substream, $educationType)
	{
		
		$this->initiateModel('read');
		if($courseIds!=''){
                        $courseSubQuery = ' and crmtsi.courseId in ('.$courseIds.')';
                }
        $orderOfReview = (isset($_COOKIE['collegeReviewOrder']) && $_COOKIE['collegeReviewOrder']!='')?$_COOKIE['collegeReviewOrder']:'latest';
		$sql = "select distinct SQL_CALC_FOUND_ROWS crmt.*,
				 crmt.modificationDate as postedDate,
				  crmtsi.locationId,
				   crmtsi.instituteId, crmtsi.courseId, crmtsi.yearOfGraduation, crmt.reviewDescription from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) join shiksha_courses_type_information scti on (crmtsi.courseId = scti.course_id) join 
        		 shiksha_courses sc on (crmtsi.courseId = sc.course_id) where crmt.status='published' and  scti.status = 'live' and scti.stream_id=? and scti.base_course=? and scti.substream_id=? and sc.education_type=? ".mysql_escape_string($courseSubQuery)." order by crmtsi.yearOfGraduation desc, crmt.modificationDate desc limit $start, $count";
		$query = $this->dbHandle->query($sql, array($stream,$baseCourse,$substream,$educationType));
		
		$totalRows = "SELECT FOUND_ROWS() as count";
		$queryCount = $this->dbHandle->query($totalRows);
		$countReviews = $queryCount->result_array();
		$results = $query->result_array();

		if(count($results)>0){
			$resultSet['result'] = $results;
			$resultSet['reviewerDetails'] = $this->getReviewerDetails($results);
			$resultSet['countReviews'] = $countReviews[0]['count'];
		}
		return $resultSet;
	}

/**
* Not Used
*/
	/*function getNextPopularReviewForSlider($courseIds, $start=0, $count=1, $categoryId = '23')
	{
		
		$this->initiateModel('read');
		if($courseIds!=''){
                        $courseSubQuery = ' and crmtsi.courseId in ('.$courseIds.')';
                }
		$sql = "select distinct crmt.*, crmt.modificationDate as postedDate, crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId, crmtsi.yearOfGraduation, crmt.reviewDescription from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) join shiksha_courses_type_information scti on (crmtsi.courseId = scti.course_id) join on shiksha_courses sc on (crmtsi.courseId = sc.course_id) where crmt.status='published' and scti.status = 'live' and scti.stream_id=? and scti.base_course=? and scti.substream_id=? and sc.education_type=? and sc.delivery_method=? $courseSubQuery order by crmtsi.yearOfGraduation desc, crmt.modificationDate desc limit $start, $count";
		$query = $this->dbHandle->query($sql, array($stream,$baseCourse,$substream,$educationType,$deliveryMethod));
                $results = $query->result_array();
		if(count($results)>0){
			$resultSet['result'] = $results;
			$resultSet['reviewerDetails'] = $this->getReviewerDetails($results);
		}
		return $resultSet;
	}*/
/**
* Not Used Ends
*/
	
	function getLatestReviewsForSlider($courseIds, $start, $count, $stream,$baseCourse,$educationType,$substream,$page='intermediate',$courseSubQueryForHomepage = '')
	{
		$resultSet = array();
		$this->initiateModel('read');
		if($courseIds!=''){
            $courseSubQuery = ' and crmtsi.courseId in ('.$courseIds.')';
        }
		
		//$sql = "select SQL_CALC_FOUND_ROWS t.*, count(*) as revCount from (select distinct crmt.*,crmt.modificationDate as postedDate, crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId ,crmtsi.yearOfGraduation from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) join categoryPageData cpd on (crmtsi.courseId = cpd.course_id) where crmt.status='published' and cpd.status = 'live' and cpd.category_id='23' $courseSubQuery order by crmtsi.yearOfGraduation desc, crmt.modificationDate desc) as t group by t.courseId limit $start, $count";
       	if($page == "intermediate"){

       		$sql = "select SQL_CALC_FOUND_ROWS t.*, count(*) as revCount from (select distinct crmt.*,crmt.modificationDate as postedDate, crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId ,crmtsi.yearOfGraduation from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) join shiksha_courses_type_information scti on (crmtsi.courseId = scti.course_id) join 
        		 shiksha_courses sc on (crmtsi.courseId = sc.course_id) where crmt.status='published' and scti.status = 'live' and scti.stream_id=? and scti.base_course=? and scti.substream_id=? and sc.education_type=? $courseSubQuery order by crmtsi.yearOfGraduation desc, crmt.modificationDate desc) as t group by t.courseId";



       		$query = $this->dbHandle->query($sql, array($stream,$baseCourse,$substream,$educationType));
		
       	} else if($page == "homepage") {
       		$sql = "select SQL_CALC_FOUND_ROWS t.*, count(*) as revCount from (select distinct crmt.*,crmt.modificationDate as postedDate, crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId ,crmtsi.yearOfGraduation from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) and 
       		    crmtsi.courseId IN (".$courseSubQueryForHomepage.") and crmt.status='published' order by crmtsi.yearOfGraduation desc, crmt.modificationDate desc) as t group by t.courseId order by t.yearOfGraduation desc,t.postedDate desc limit $start,$count";
				$query = $this->dbHandle->query($sql);
       	}
       
		
                $results = $query->result_array();
		
		$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
		$queryTotal = $this->dbHandle->query($queryCmdTotal);
		$queryResults = $queryTotal->result();
		$totalRows = $queryResults[0]->totalRows;
		if($totalRows>0){
			$resultSet['result'] = $results;
			
			$resultSet['reviewerDetails'] = $this->getReviewerDetails($results);
			$resultSet['totalCollegeCards'] = $totalRows;
		}
		return $resultSet;
	}

/*
        function getLatestAndPopularReviews($courseIds, $orderOfReview, $start, $count,$criteriaCheck){
		
                $this->initiateModel('read');
		$cacheLib = $this->load->library('cacheLib');
                $courseSubQuery = '';
		$subQueryCriteria = "";
		if($criteriaCheck)
		{
			
			$sql = "SELECT DISTINCT lm.pack_type AS packType, crmtsi.courseId, COUNT( crmtsi.reviewID ) AS RevCount
					FROM listings_main lm
					JOIN CollegeReview_MappingToShikshaInstitute crmtsi ON lm.listing_type_id = crmtsi.courseId
					JOIN CollegeReview_MainTable crmt ON crmtsi.reviewId = crmt.id
					WHERE lm.listing_type =  'course'
					AND lm.status =  'live'
					AND crmt.status =  'published'
					GROUP BY crmtsi.courseId";
			
		
			$query = $this->dbHandle->query($sql);			
			$results = $query->result_array();
			
			$this->load->library('CollegeReviewLib');
			$this->CollegeReviewLib = new CollegeReviewLib();
			
			$subQueryCriteria = $this->CollegeReviewLib->getCollegeReviewsByCriteria($results);
		}
		
                if($courseIds!=''){
                        $courseSubQuery = ' and crmtsi.courseId in ('.$courseIds.')';
                }
		else if($subQueryCriteria != ''){
			$courseSubQuery = ' and crmtsi.courseId in ('.$subQueryCriteria.')';
		}
                if($orderOfReview=='latest'){
                        $sql = "select SQL_CALC_FOUND_ROWS distinct crmt.*,crmt.modificationDate as postedDate, crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId,crmtsi.yearOfGraduation, crmt.reviewDescription from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) join categoryPageData cpd on (crmtsi.courseId = cpd.course_id) where crmt.status='published' and cpd.status = 'live' and cpd.category_id='23' $courseSubQuery order by crmt.modificationDate desc limit $start, $count";
                        $query = $this->dbHandle->query($sql);
                        $results = $query->result_array();
        
                        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                        $queryTotal = $this->dbHandle->query($queryCmdTotal);
                        $queryResults = $queryTotal->result();
                        $totalRows = $queryResults[0]->totalRows;
                        if($totalRows>0){
                                $resultSet['result'] = $results;
				$resultSet['reviewerDetails'] = $this->getReviewerDetails($results);
                                $resultSet['totalReviews'] = $totalRows;
                        }
                        
                }else{
			$key = 'popularcollegereviews-'.$start.'-'.$count;
                        if($cacheLib->get($key)=='ERROR_READING_CACHE'){
				$sql = "select SQL_CALC_FOUND_ROWS crmtsi.courseId, ( ( sum(moneyRating) +sum(crowdCampusRating)+ sum(avgSalaryPlacementRating)+ sum(campusFacilitiesRating)+ sum(facultyRating))/(5*(count(*)))) as ratings, (select modificationDate from CollegeReview_MainTable where id in (select reviewId from CollegeReview_MappingToShikshaInstitute where courseId=crmtsi.courseId) and status='published' order by modificationDate desc limit 1) as postedDate from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) join categoryPageData cpd on (crmtsi.courseId = cpd.course_id) where crmt.status='published' and cpd.status = 'live' and cpd.category_id='23' $courseSubQuery group by crmtsi.courseId order by ratings desc, postedDate DESC limit $start, $count";
				$query = $this->dbHandle->query($sql);
				$courseCSV = '';
				$queryResult = $query->result_array();
				foreach($query->result_array() as $row) {
				    $courseCSV .= ($courseCSV=='')?$row['courseId']:','.$row['courseId'];
				}
				
				$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
				$queryTotal = $this->dbHandle->query($queryCmdTotal);
				$queryResults = $queryTotal->result();
				$totalRows = $queryResults[0]->totalRows;
				
				$sql = "select ( ( sum(moneyRating) +sum(crowdCampusRating)+ sum(avgSalaryPlacementRating)+ sum(campusFacilitiesRating)+ sum(facultyRating))/(5*(count(*)))) as ratings,crmt.*,(select modificationDate from CollegeReview_MainTable where id in (select reviewId from CollegeReview_MappingToShikshaInstitute where courseId=crmtsi.courseId) and status='published' order by modificationDate desc limit 1) as postedDate, crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId, crmtsi.yearOfGraduation, (select count(recommendCollegeFlag) from CollegeReview_MainTable where id in (select reviewId from CollegeReview_MappingToShikshaInstitute where courseId=crmtsi.courseId)  and recommendCollegeFlag='YES' and  status='published') as recommendations, (select reviewDescription from CollegeReview_MainTable where id in (select reviewId from CollegeReview_MappingToShikshaInstitute where courseId=crmtsi.courseId) and status='published' order by modificationDate desc limit 1) as reviewDescription, (select count(*) from CollegeReview_MainTable where id in (select reviewId from CollegeReview_MappingToShikshaInstitute where courseId=crmtsi.courseId) and status='published') as totalReviews from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) where crmt.status='published' and crmtsi.courseId IN ($courseCSV) GROUP BY courseId ORDER BY ratings desc,postedDate DESC";
				$query = $this->dbHandle->query($sql);
				$finalResult = array();
				$i = 0;
				foreach($query->result_array() as $row) {
				    foreach ($queryResult as $ratingRow){
					if($ratingRow['courseId'] == $row['courseId']){
					    $row['ratings'] = $ratingRow['ratings'];
					    $row['postedDate'] = $ratingRow['postedDate'];
					}
				    }
				    $finalResult[$i] = $row;
				    $i++;
				}
	
				if($totalRows>0){
					$resultSet['result'] = $finalResult;
					$resultSet['reviewerDetails'] = $this->getReviewerDetails($finalResult);
					$resultSet['totalReviews'] = $totalRows;        
				}
				$cacheLib->store($key,$resultSet, 21600);
			}else{
				$resultSet = $cacheLib->get($key);
			}
                }

                return $resultSet;
        }
	*/


		
    /*
    * Function to get Sub Query Criteria, go through all reviews in CollegeReview_MainTable
    * returns => pack type, courseId, Review Count
    * Table joins => listings_main, CollegeReview_MappingToShikshaInstitute, CollegeReview_MainTable
    */

	function getSubQueryCriteria(){
		$this->initiateModel('read');
		$sql = "SELECT DISTINCT lm.pack_type AS packType, crmtsi.courseId, COUNT( crmtsi.reviewID ) AS RevCount
				FROM listings_main lm
				JOIN CollegeReview_MappingToShikshaInstitute crmtsi ON lm.listing_type_id = crmtsi.courseId
				JOIN CollegeReview_MainTable crmt ON crmtsi.reviewId = crmt.id
				WHERE lm.listing_type =  'course'
				AND lm.status =  'live'
				AND crmt.status =  'published'
				GROUP BY crmtsi.courseId";

		return $this->dbHandle->query($sql)->result_array();
	}

	/* Fetch data from CollegeReview_MainTable and course and review info from other tables and order them desc modification date
	 * @params : $subQueryCriteria => string of courses seperated by comma for all courses in mainTable which fulfil the criteria 
	 * 		     $start => handles pagination request, $count => pagination page size
	 * @returns :  All data from CollegeReview_MainTable, courseId, instituteId, locationId, year of grad
	 */

	function getLatestReviews($start, $count,$subQueryCriteria, $categoryId = '23'){
		$this->initiateModel('read');
		$newSubQueryCriteria = explode(",", $subQueryCriteria);
		$sql = "select SQL_CALC_FOUND_ROWS distinct crmt.*,crmt.modificationDate as postedDate, 
				crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId,crmtsi.yearOfGraduation, 
				crmt.reviewDescription from CollegeReview_MainTable crmt 
				join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) 
				join categoryPageData cpd on (crmtsi.courseId = cpd.course_id) 
				where crmt.status='published' and cpd.status = 'live' and cpd.category_id= ? 
				and crmtsi.courseId in (?) 
				order by crmt.modificationDate desc limit $start, $count";
        $reviewData =  $this->dbHandle->query($sql, array($categoryId,$newSubQueryCriteria))->result_array();


        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $rowCount = $this->dbHandle->query($queryCmdTotal, array($categoryId))->result();
        $data['reviewData'] = $reviewData;
        $data['rowCount'] = $rowCount;
        return $data;

	}

	/* 
	 * function return review data for given courseId's
	 *
	 * @params : $courseCSV => comma seperated courseIds
	 *
	 * @result : rating, id, userId, creationDate, anonymousFlag, reviewDescription, individual rating, modif date, review sourse, etc
	 */

	function getPopularCollegeReviews($courseCSV){
		$this->initiateModel('read');
		$newCourseCSV = explode(",", $courseCSV);
		$sql = "select ( ( sum(moneyRating) +sum(crowdCampusRating)+ sum(avgSalaryPlacementRating)+ sum(campusFacilitiesRating)+ sum(facultyRating))/(5*(count(*)))) as ratings,crmt.*,
				(select modificationDate from CollegeReview_MainTable where id in (select reviewId from CollegeReview_MappingToShikshaInstitute where courseId=crmtsi.courseId) and status='published' order by modificationDate desc limit 1) as postedDate, crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId, crmtsi.yearOfGraduation, 
				(select count(recommendCollegeFlag) from CollegeReview_MainTable where id in (select reviewId from CollegeReview_MappingToShikshaInstitute where courseId=crmtsi.courseId)  and recommendCollegeFlag='YES' and  status='published') as recommendations, (select reviewDescription from CollegeReview_MainTable where id in 
				(select reviewId from CollegeReview_MappingToShikshaInstitute where courseId=crmtsi.courseId) and status='published' order by modificationDate desc limit 1) as reviewDescription, (select count(*) from CollegeReview_MainTable where id in (select reviewId from CollegeReview_MappingToShikshaInstitute where courseId=crmtsi.courseId) and status='published') as totalReviews from CollegeReview_MainTable crmt 
				join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) 
				where crmt.status='published' and crmtsi.courseId IN (?) 
				GROUP BY courseId ORDER BY ratings desc,postedDate DESC limit 0,10";
		return $this->dbHandle->query($sql,array($newCourseCSV))->result_array();
	}

	/* function to get reviews ratings. (sum all type of rating for a course)/(count(*)) * 5
	 *
	 * @params : $subQueryCriteria => string of courses seperated by comma for all courses in mainTable which fulfil the criteria 
	 * 		     $start => handles pagination request, $count => pagination page size, $categoryId => MBA/BTECH 
	 */
	function getCourseReviewsRatings($courseIds, $start, $count,$subQueryCriteria, $categoryId = '23'){
		$this->initiateModel('read');
		$newSubQueryCriteria = explode(",", $subQueryCriteria);
		$sql = "select SQL_CALC_FOUND_ROWS crmtsi.courseId, 
				( ( sum(moneyRating) +sum(crowdCampusRating)+ sum(avgSalaryPlacementRating)+ sum(campusFacilitiesRating)+ sum(facultyRating))/(5*(count(*)))) as ratings, 
					(select modificationDate from CollegeReview_MainTable where id in (select reviewId from CollegeReview_MappingToShikshaInstitute where courseId=crmtsi.courseId) and status='published' order by modificationDate desc limit 1) as postedDate 
					from CollegeReview_MainTable crmt 
					join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) 
					join categoryPageData cpd on (crmtsi.courseId = cpd.course_id) 
					where crmt.status='published' and cpd.status = 'live' and cpd.category_id=? 
					and crmtsi.courseId in (?) 
					group by crmtsi.courseId 
					order by ratings desc, postedDate DESC 
					limit $start, $count"; 
		$query = $this->dbHandle->query($sql, array($categoryId,$newSubQueryCriteria));
		$queryResult = $query->result_array();
		$data['queryResult'] = $queryResult;

		
		$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
		$queryTotal = $this->dbHandle->query($queryCmdTotal);
		$queryResults = $queryTotal->result();
		$totalRows = $queryResults[0]->totalRows;

		$data['totalRows'] = $totalRows;
		return $data;
	}

	/* function returns the reviewer details from tuser/CollegeReview_PersonalInformation
	 * @params : $result contains data returned by getLatestReviews
	 */

	function getReviewerDetails($result){
		$reviewerArray = array();
		$userIdList = '';
		$reviewerIdList = '';
		//Check if the Result set passed has any Data
		if(is_array($result)){
			$this->initiateModel('read');
			foreach ($result as $review){
				$reviewId = $review['id'];
				if($review['userId']>0){
					$userIdList .= $review['userId'].', ';
				}else if($review['reviewerId']>0){
					$reviewerIdList .= $review['reviewerId'].', ';
				}
			}
            $userIdList = substr($userIdList,0,-2);
            $reviewerIdList = substr($reviewerIdList,0,-2);

			if(!empty($userIdList)){
				//Get reviewer Details from tuser table
				$sql = "select crmt.id as reviewId, tu.firstname as username, tu.email as email 
						from tuser tu join CollegeReview_MainTable crmt on (crmt.userId = tu.userid) 
						where tu.userid in (?)";
				$formattedUserList = explode(",", $userIdList);
                $query = $this->dbHandle->query($sql,array($formattedUserList));

                $results = $query->result_array();
	            foreach ($results as $reviewerDet){
	            	$reviewerArray[$reviewerDet['reviewId']]['username'] = $reviewerDet['username'];		
	            	$reviewerArray[$reviewerDet['reviewId']]['email'] = $reviewerDet['email'];		
				}
				
			}

			if(!empty($reviewerIdList)){

				//Get reviewer details from CollegeReview_PersonalInformation table
				$sql = "select crmt.id as reviewId, crpi.firstname as username, crpi.email as email from CollegeReview_PersonalInformation crpi join CollegeReview_MainTable crmt on 
					(crmt.reviewerId = crpi.id) where crpi.id in (?)";
				$formattedReviewerList = explode(",", $reviewerIdList);
                $query = $this->dbHandle->query($sql,array($formattedReviewerList));
                $results = $query->result_array();
	            foreach ($results as $reviewerDet){
	            	$reviewerArray[$reviewerDet['reviewId']]['username'] = $reviewerDet['username'];		
	            	$reviewerArray[$reviewerDet['reviewId']]['email'] = $reviewerDet['email'];		
				}
			}
			
		}
		return $reviewerArray;
	}
	/*
	function getTotalReviews(){
		$this->initiateModel('read');
		$cacheLib = $this->load->library('cacheLib');
		$results = array();
		if($cacheLib->get('totalReviews')=='ERROR_READING_CACHE'){
			$sql = "SELECT (select count(distinct course_id) from categoryPageData where course_id=crmtsi.courseId and status='live' and category_id='23') as totalCount , crmtsi.courseId FROM `CollegeReview_MainTable` crmt  join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id=crmtsi.reviewId) where crmt.status='published'";
			$query = $this->dbHandle->query($sql);
			$results = $query->result_array();
			$totalCount = '';
			foreach($results as $key=>$value){
				$totalCount +=$value['totalCount'];
			}
			$finalResult = array();
			$finalResult[0]['totalReviewCount'] = $totalCount;
			$cacheLib->store('totalReviews',$finalResult, 21600);
		}else{
			$finalResult = $cacheLib->get('totalReviews');
		}
		return $finalResult[0]['totalReviewCount'];
	}

	function getReviewInstituteCount(){
                $this->initiateModel('read');
		$cacheLib = $this->load->library('cacheLib');
                $results = array();
		if($cacheLib->get('instituteReviewCount')=='ERROR_READING_CACHE'){
			$sql = "SELECT count(distinct cpd.institute_id) as totalInstituteCount FROM `CollegeReview_MainTable` crmt  join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id=crmtsi.reviewId) join categoryPageData cpd on (cpd.institute_id=crmtsi.instituteId) where crmt.status='published' and cpd.status='live' and category_id='23'";
			$query = $this->dbHandle->query($sql);
			$results = $query->result_array();
			$cacheLib->store('instituteReviewCount',$results, 21600);
		}else{
			$results = $cacheLib->get('instituteReviewCount');
		}
                return $results[0]['totalInstituteCount'];
	}
*/

	/*
	 * map all course from categoryPageData with CollegeReview_MainTable
	 */

	function getTotalReviewsNew($stream,$baseCourse,$substream,$educationType){

		$this->initiateModel('read');
		$sql = "SELECT (select count(distinct scti.course_id) from shiksha_courses_type_information scti join shiksha_courses sc on (sc.course_id=scti.course_id) where scti.course_id=crmtsi.courseId and scti.status='live' and scti.stream_id= ? and scti.base_course=? and scti.substream_id=? and sc.education_type=?) as totalCount , crmtsi.courseId FROM `CollegeReview_MainTable` crmt  join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id=crmtsi.reviewId) where crmt.status='published'";
		return $this->dbHandle->query($sql, array($stream,$baseCourse,$substream,$educationType))->result_array();
	}

	function getReviewInstituteCountNew($stream){
		$this->initiateModel('read');
		
		$sql = "SELECT count(distinct cpd.institute_id) as totalInstituteCount 
				FROM `CollegeReview_MainTable` crmt  
				join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id=crmtsi.reviewId) 
				join categoryPageData cpd on (cpd.institute_id=crmtsi.instituteId) 
				where crmt.status='published' and cpd.status='live' and category_id=? ";
		
		return $this->dbHandle->query($sql, array($stream))->result_array();

	}

	function getReviewCourses()
	{
		$this->initiateModel('read');
		$sql = "SELECT DISTINCT (course_id) AS courseId FROM `categoryPageData` WHERE `category_id` =23 AND STATUS = 'live'";
                $query = $this->dbHandle->query($sql);
                return $query->result_array();
	}
	
	function insertReplyinTable($reviewId, $courseId, $userId, $replyTxt){
		$this->initiateModel('write');
		$sql = "insert into  CollegeReview_InstituteReply (`id`, `reviewId`, `courseId`, `userId`, `replyTxt`, `status`, `creationDate`) values (NULL,?,?,?,?, 'live', now())";
	
		return $this->dbHandle->query($sql,array($reviewId,$courseId,$userId,$replyTxt));

	
	}

	/**
	* @param : $reviewIds : accept array of review ids and return reply of review ids with key value pair.
	*/

	function getRepliesForReviewIds($reviewIds)
	{
		if(empty($reviewIds) && count($reviewIds) == 0)
			return;
		$this->initiateModel('read');
		$query = "select reviewId, replyTxt from CollegeReview_InstituteReply where status='live' and reviewId in (?)";
		return $this->dbHandle->query($query,array($reviewIds))->result_array();
	}

    function markReviewRead($reviewId, $userId, $source, $pageName){
        $this->initiateModel('write');
        $sessionId = sessionId();
        
        //Check that for the Session-Review pair OR User-Review pair, the entry should not be repeated
        $sql = "SELECT * FROM CollegeReview_ReviewViewed WHERE reviewId = ? AND (sessionId = ? OR userId = ?) AND status = 'live'";
        $query = $this->dbHandle->query($sql, array($reviewId,$sessionId, $userId));
        
        if($query->num_rows()<=0){
            //If not found, insert the entry in DB
            $sql = "INSERT INTO CollegeReview_ReviewViewed (`reviewId`, `sessionId`, `userId`, `source`, `pageName`) VALUES (?,?,?,?,?)";
            $this->dbHandle->query($sql,array($reviewId,$sessionId, $userId, $source, $pageName));
        }
    }
	
	function submitReviewHelpfulFlag($reviewId, $flagVal, $userId){
		
		$this->initiateModel('write');
		$sessionId = sessionId();
		
		if($userId){
			$sqlGetSessionData = "SELECT * FROM `CollegeReview_UserSessionData` WHERE reviewId = ? AND userId = ? AND `isSetHelpfulFlag` = ?";
			$query = $this->dbHandle->query($sqlGetSessionData, array($reviewId, $userId, $flagVal));
			
		} else {
			$sqlGetSessionData = "SELECT * FROM `CollegeReview_UserSessionData` WHERE sessionId = ? AND reviewId = ? AND `isSetHelpfulFlag` = ?";
			$query = $this->dbHandle->query($sqlGetSessionData, array($sessionId, $reviewId, $flagVal));
		}
		
		if($query->num_rows()<=0) {
			if($reviewId != NULL){
				$sqlInsertSessionData = "INSERT INTO `CollegeReview_UserSessionData` (`reviewId`, `sessionId`, `userId`, `isSetHelpfulFlag`) VALUES (?,?,?,?) ";
				$query = $this->dbHandle->query($sqlInsertSessionData, array($reviewId, $sessionId, $userId, $flagVal));

				if($flagVal == 'YES'){
					$col_name = 'helpfulFlagCount';
				}else {
					$col_name = 'notHelpfulFlagCount';
				}
			
				$sqlGetCount = "SELECT ".$col_name." FROM CollegeReview_MainTable WHERE id = ?";
				$query = $this->dbHandle->query($sqlGetCount,array($reviewId));
			
				$results = $query->result_array();
				$count = $results[0][$col_name] + 1;

				$sqlUpdate = "UPDATE CollegeReview_MainTable SET ".$col_name." = ? WHERE id = ?";
				$this->dbHandle->query($sqlUpdate,array($count, $reviewId));
			}
			
		} else {
			
			//$results = $query->result_array();
			//
			//if($userId){
			//	$sqlUpdateSessionData = "UPDATE `CollegeReview_UserSessionData` SET `userId` = ? WHERE reviewId = ? AND sessionId = ? AND userId = 0 AND `isSetHelpfulFlag` = ? ";
			//	$query = $this->dbHandle->query($sqlUpdateSessionData, array($userId, $reviewId, $sessionId, $flagVal));
			//}
		}
	}
	
	function getUserSessionData($userId, $sessionId){
		
		$this->initiateModel('read');
		if($userId) {
			$sql = "SELECT * FROM `CollegeReview_UserSessionData` WHERE `userId` = ? ";
			$query = $this->dbHandle->query($sql,array($userId));
		} else {
			$sql = "SELECT * FROM `CollegeReview_UserSessionData` WHERE `sessionId` = ? ";
			$query = $this->dbHandle->query($sql,array($sessionId));
		}
		
		$userSessionData = array();
		
		foreach($query->result_array() as $result) {
			$userSessionData[$sessionId][$result['reviewId']]['sessionId'] = $result['sessionId'];
			$userSessionData[$sessionId][$result['reviewId']]['userId'] = $result['userId'];
			$userSessionData[$sessionId][$result['reviewId']]['isSetHelpfulFlag'] = $result['isSetHelpfulFlag'];
		}
		
		return $userSessionData;
	}
	
	function getLatestReviewsForSliderMobile($courseIds, $start, $count, $stream, $baseCourse, $educationType, $substream)
	{
		$resultSet = array();
		$this->initiateModel('read');

        if($courseIds!=''){
                        $courseSubQuery = ' and crmtsi.courseId in ('.$courseIds.')';
                }

		$delim = md5("%^&*shiksha%^&*");
		$group_query = "substring_index(GROUP_CONCAT(id),',',2) as SecondReviewId,substring_index(GROUP_CONCAT(SUBSTRING(reviewDescription,1,400),'$delim'),'$delim',2) as SecondReviewDetail,substring_index(GROUP_CONCAT(SUBSTRING(placementDescription,1,400),'$delim'),'$delim',2) as SecondPlacementDetail,substring_index(GROUP_CONCAT(SUBSTRING(infraDescription,1,400),'$delim'),'$delim',2) as SecondInfraDetail,substring_index(GROUP_CONCAT(SUBSTRING(facultyDescription,1,400),'$delim'),'$delim',2) as SecondFacultyDetail,substring_index(GROUP_CONCAT(reviewerId),',',2) as SecondReviewerIdDetail,substring_index(GROUP_CONCAT(userId),',',2) as SecondUserIdDetail,substring_index(GROUP_CONCAT(anonymousFlag),',',2) as SecondUserAnonymousFlagDetail,		
		substring_index(GROUP_CONCAT(recommendCollegeFlag),',',2) as SecondUserRecommendCollegeFlag,
		
		substring_index(GROUP_CONCAT(yearOfGraduation),',',2) as SecondUserYearOfGraduation,
		
		substring_index(GROUP_CONCAT(moneyRating),',',2) as SecondUserMoneyRating,
		substring_index(GROUP_CONCAT(crowdCampusRating),',',2) as SecondUserCrowdCampusRating,
		substring_index(GROUP_CONCAT(avgSalaryPlacementRating),',',2) as SecondUserAvgSalaryPlacementRating,
		
		substring_index(GROUP_CONCAT(campusFacilitiesRating),',',2) as SecondUserCampusFacilitiesRating,
		substring_index(GROUP_CONCAT(facultyRating),',',2) as SecondUserFacultyRating,
		substring_index(GROUP_CONCAT(postedDate),',',2) as SecondUserPostedDate,
		substring_index(GROUP_CONCAT(postedDate),',',2) as SecondUserPostedDate,
		substring_index(GROUP_CONCAT(averageRating),',',2) as SecondUserAverageRating,
		substring_index(GROUP_CONCAT(userId),',',2) as SecondUserUserId";

		$sql = "SELECT *,$group_query,count(*) as revCount FROM (select distinct crmt.*,crmt.modificationDate as postedDate, crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId ,crmtsi.yearOfGraduation from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) join shiksha_courses_type_information scti on (crmtsi.courseId = scti.course_id) join 
        		 shiksha_courses sc on (crmtsi.courseId = sc.course_id) where crmt.status='published' and scti.status = 'live' and scti.stream_id=? and scti.base_course=? and scti.substream_id=? and sc.education_type=? $courseSubQuery order by crmtsi.yearOfGraduation desc, crmt.modificationDate desc) as T group by T.courseId"; 
		
		
		//exit();
		
		$query = $this->dbHandle->query($sql, array($stream,$baseCourse,$substream,$educationType));
                $results = $query->result_array();
	
		$totalRows = $query->num_rows();
		
		if($totalRows>0){
			$resultSet['result'] = $results;
			
			$resultSet['reviewerDetails'] = $this->getReviewerDetailsMobile($results);
			$resultSet['totalCollegeCards'] = $totalRows;
		}

		return $resultSet;
	}
	
	function getReviewerDetailsMobile($result){
		$reviewerArray = array();
		//Check if the Result set passed has any Data
		if(is_array($result)){
			$this->initiateModel('read');
			foreach ($result as $review){
				$reviewId = $review['id'];
				$reviewerIdArray = explode(",",$review['SecondReviewerIdDetail']);
				$userIdArray = explode(",",$review['SecondUserIdDetail']);
				if($userIdArray[0]>0){
					//Get reviewer Details from tuser table
		                        $sql = "select concat(firstname, ' ' ,lastname) as username, email from tuser where userid = ?";
		                        $query = $this->dbHandle->query($sql, array($userIdArray[0]));

				}
				else if($reviewerIdArray[0]>0){
					//Get reviewer details from CollegeReview_PersonalInformation table
                                        $sql = "select concat(firstname, ' ', lastname) as username, email from CollegeReview_PersonalInformation where id = ?";
                                        $query = $this->dbHandle->query($sql, array($reviewerIdArray[0]));
				}

		        $results = $query->result_array();	
				if(count($userIdArray) > 1){

					if($userIdArray[1]>0){
					//Get reviewer Details from tuser table
                        $sql = "select concat(firstname, ' ' ,lastname) as username, email from tuser where userid = ?";
                        $query1 = $this->dbHandle->query($sql, array($userIdArray[1]));

					}
					else if($reviewerIdArray[1]>0){
					//Get reviewer details from CollegeReview_PersonalInformation table
	                    $sql = "select concat(firstname, ' ' ,lastname) as username, email from CollegeReview_PersonalInformation where id = ?";
                        $query1 = $this->dbHandle->query($sql, array($reviewerIdArray[1]));
					}
            
					$resultsSecond = $query1->result_array();
					array_push($results,$resultsSecond[0]);
				}
				
				
				$count = 0;
				foreach ($results as $reviewerDet){
	            	$reviewerArray[$reviewId][$count++] = $reviewerDet;		
				}
			}
		}
		return $reviewerArray;
	}
	
		function getNextLatestReviewForSliderMobile($courseIds, $start, $count, $categoryId = '23')
		{
		$count++;
		$this->initiateModel('read');
		if($courseIds!=''){
                        $courseSubQuery = ' and crmtsi.courseId in ('.$courseIds.')';
                }
		$sql = "select distinct crmt.*, crmt.modificationDate as postedDate, crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId, crmtsi.yearOfGraduation, crmt.reviewDescription from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) join categoryPageData cpd on (crmtsi.courseId = cpd.course_id) where crmt.status='published' and cpd.status = 'live' and cpd.category_id=? $courseSubQuery order by crmtsi.yearOfGraduation desc, crmt.modificationDate desc limit $start, $count";
		$query = $this->dbHandle->query($sql, array($categoryId));
                $results = $query->result_array();
		if(count($results)>0){
			$resultSet['result'] = $results;
			$resultSet['reviewerDetails'] = $this->getReviewerDetails($results);
		}
		return $resultSet;
	
	}
	
	public function getReviewDetailbyReviewId($reviewId)
	{
		$this->initiateModel('read');
		$sql = "select * from CollegeReview_MainTable where id = ?";
		$query = $this->dbHandle->query($sql,array($reviewId));
		$result = $query->row_array();
		return $result;
		
	}
	
	
	function modifyTileDataForCriteria($tileData,$countCriteria4PaidCourse = 3,$countCriteria4FreeCourse = 1)
	 {
		
		$courseId        = $tileData[0]['courseIds'];
		$modifyCourseIds = explode(",", $courseId);
		$sql = "select crm.courseId,lm.pack_type as packType,count(reviewId) as RevCount from listings_main
		lm,CollegeReview_MappingToShikshaInstitute crm,  CollegeReview_MainTable srmt where
		lm.status='live' and lm.listing_type='course' and
		lm.listing_type_id IN (?) and lm.listing_type_id=crm.courseId 
		and srmt.id  = crm.reviewId and srmt.status = 'published'
		group by crm.courseId order by FIELD(crm.courseId,?)";

		$query = $this->dbHandle->query($sql,array($modifyCourseIds,$modifyCourseIds));
		$result = $query->result_array();
		return $result;
	 }

	 function getLatestReviewsForSliderNew($courseIds, $start, $count, $stream,$page='intermediate',$courseSubQueryForHomepage = '',$baseCourse,$educationType,$substream){

	 	$this->initiateModel('read');

	 	if($courseIds!=''){
            $courseSubQuery = ' and crmtsi.courseId in ('.$courseIds.')';
        }
        $query = "";
        if($page == "intermediate"){
        $sql = "select SQL_CALC_FOUND_ROWS t.*, count(*) as revCount from (select distinct crmt.*,crmt.modificationDate as postedDate, crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId ,crmtsi.yearOfGraduation from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) join shiksha_courses_type_information scti on (crmtsi.courseId = scti.course_id) join 
        	 shiksha_courses sc on (crmtsi.courseId = sc.course_id) where crmt.status='published' and scti.status = 'live' and scti.stream_id=? and scti.base_course=? and scti.substream_id=? and sc.education_type=? $courseSubQuery order by crmtsi.yearOfGraduation desc, crmt.modificationDate desc) as t group by t.courseId";

           	$query = $this->dbHandle->query($sql, array($stream,$baseCourse,$substream,$educationType));
           	
        }

		else if($page == "homepage") {
			if(empty($courseSubQueryForHomepage)){
	        	$data['totalRows'] = 0;
	        	$data['results'] = array();
	        	return $data;
	        }
       		$sql = "SELECT SQL_CALC_FOUND_ROWS t.*,
       				 count(*) as revCount 
       				 		FROM
       				 (SELECT DISTINCT crmt.id,
       				 crmt.placementDescription,
       				 crmt.infraDescription,
       				 crmt.facultyDescription,
       				 crmt.reviewDescription,
       				 crmt.averageRating,
       				 crmt.userId,
       				 crmt.anonymousFlag,
       				 crmt.recommendCollegeFlag,
       				 crmt.isShikshaInstitute,
       				 crmt.reviewerId,
       				 crmt.reviewTitle,
       				 crmt.modificationDate as postedDate, 
       				 crmtsi.locationId, 
       				 crmtsi.instituteId, 
       				 crmtsi.courseId,
       				 crmtsi.yearOfGraduation 
       				 		FROM 
       				 CollegeReview_MainTable crmt 
       				 		JOIN 
       				 CollegeReview_MappingToShikshaInstitute 
       				 crmtsi ON (crmt.id= crmtsi.reviewId) 
       				 		AND 
       				 crmtsi.courseId IN (".$courseSubQueryForHomepage.") 
       				 		AND 
       				 crmt.status='published' 
       				 		ORDER BY
       				 crmtsi.yearOfGraduation desc, 
       				 crmt.modificationDate desc) AS t 
       				 GROUP BY t.courseId 
					 ORDER BY t.yearOfGraduation DESC,
					 t.postedDate DESC 
					 LIMIT $start,$count";
			
		
			$query = $this->dbHandle->query($sql);
 
       	}

      
        $data['results'] = $query->result_array();
        
        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $this->dbHandle->query($queryCmdTotal);
        $queryResults = $queryTotal->result();
        $data['totalRows'] = $queryResults[0]->totalRows;
        return $data;
	}

	function getPopularReviewsForSlider($courseIds = '', $start, $count,$page='intermediate',$courseSubQueryForHomepage = '', $courseRankMapping = array()){
		$this->initiateModel('read');
		
		if($courseIds!=''){
            $courseSubQuery = ' and crmtsi.courseId in ('.$courseIds.')';
        } 
        $subQueryForHomepage = '';
        if($courseSubQueryForHomepage!=''){
            $subQueryForHomepage = ' and crmtsi.courseId in ('.$courseSubQueryForHomepage.')';
        }

        $query = "";
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS t.*, count(*) as revCount FROM
       				 (SELECT DISTINCT  crmt.*,crmt.modificationDate as postedDate, 
       				 crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId ,
       				 crmtsi.yearOfGraduation FROM CollegeReview_MainTable crmt 
       				 JOIN CollegeReview_MappingToShikshaInstitute crmtsi ON 
       				 (crmt.id= crmtsi.reviewId) ".$subQueryForHomepage."
       				 AND crmt.status='published' ORDER BY crmtsi.yearOfGraduation desc, 
       				 crmt.modificationDate desc) AS t GROUP BY t.courseId 
					 DESC";

		
		$query = $this->dbHandle->query($sql);

       	$data['results'] = $query->result_array();
      
        $sort = array();
		foreach($data['results'] as $k=>$v) {
		    $sort['ratings'][$k] = $v['ratings'];
		    $sort['yearOfGraduation'][$k] = $v['yearOfGraduation'];
		    $sort['postedDate'][$k] = $v['postedDate'];

		    if(in_array($v['courseId'],array_keys($courseRankMapping))){
		    	$sort['rankings'][$k] = $courseRankMapping[$v['courseId']];
		    }
		}
		
		# sort by event_type desc and then title asc
		//array_multisort($sort['ratings'], SORT_DESC, $sort['yearOfGraduation'], SORT_DESC, $sort['postedDate'], SORT_DESC ,$data['results']);
		
		array_multisort($sort['rankings'], SORT_ASC, $sort['yearOfGraduation'], SORT_DESC, $sort['postedDate'], SORT_DESC ,$data['results']);

      	$data['results'] = array_slice($data['results'], $start,$count,true);
      	
      	$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $this->dbHandle->query($queryCmdTotal);
        $queryResults = $queryTotal->result();
        $data['totalRows'] = $queryResults[0]->totalRows;
        
        return $data;
	}

	function getCoursesInfo($stream , $courseIds = '',$baseCourse,$educationType,$substream){ 
		$this->initiateModel('read');
		$courseIdSubQuery = '';
		$final = array();

		if($courseIds != '') {
			$courseIdSubQuery = " AND lm.listing_type_id IN (".$courseIds.")";
		}
		$sql1 = "select distinct crmtsi.courseId as courseId from 
				CollegeReview_MappingToShikshaInstitute crmtsi join shiksha_courses_type_information scti 
				on (crmtsi.courseId = scti.course_id)
				WHERE scti.status='live' AND scti.stream_id=? AND scti.base_course = ? AND scti.substream_id=?";

		$result1 = $this->dbHandle->query($sql1,array($stream,$baseCourse,$substream))->result();

		foreach ($result1 as $key => $value) {
			$courseIds[] = $value->courseId;
		}

		if(!is_array($courseIds) || count($courseIds) <=0){
			return array();
		}
		$courseIdList = implode(',', $courseIds);

		unset($result1);
		unset($courseIds);

		$sql2 = "select distinct sc.course_id, lm.pack_type from shiksha_courses sc 
				join listings_main lm on (sc.course_id = lm.listing_type_id)
				where lm.status = 'live' AND sc.education_type = ? AND lm.listing_type = 'course'
				AND sc.course_id in (".$courseIdList.")";

		$result2 = $this->dbHandle->query($sql2,array($educationType))->result();
		unset($courseIdList);

		foreach ($result2 as $key => $value) {
			$final_temp[$value->course_id]['pack_type'] = $value->pack_type;
			$courseIds[] = $value->course_id;
		}

		$courseIdList = implode(',', $courseIds);

		$sql3 = "Select count(crmt.id) as revCount, reviewCourseId as courseId
				from CollegeReview_MainTable crmt where crmt.status = 'published' and reviewCourseId in 
				(".$courseIdList.") group by reviewCourseId order by crmt.modificationDate";
		$result3 = $this->dbHandle->query($sql3)->result();
		
		$totalReviewCount = 0;
		foreach ($result3 as $key => $value) {
			$final[$value->courseId]['RevCount'] = $value->revCount;
			$final[$value->courseId]['pack_type'] = $final_temp[$value->courseId]['pack_type'];
			$final[$value->courseId]['courseId'] = $value->courseId;
			$totalReviewCount += $value->revCount;
		}
		$final['totalReviewCount'] = $totalReviewCount;

		unset($result3);
		unset($final_temp);

		return $final;
	}

	function getLatestReviewsForSliderNewMobile($courseIds, $start, $count, $stream, $baseCourse, $educationType, $substream, $page='intermediate',$courseSubQueryForHomepage = '') {
		$resultSet = array();
		$this->initiateModel('read');
		if($courseIds!=''){
                        $courseSubQuery = ' and crmtsi.courseId in ('.$this->dbHandle->escape($courseIds).')';
                }

		$delim = md5("%^&*shiksha%^&*");
		$query = "";
		$group_query = "substring_index(GROUP_CONCAT(id),',',2) as SecondReviewId,substring_index(GROUP_CONCAT(SUBSTRING(reviewDescription,1,400),'$delim'),'$delim',2) as SecondReviewDetail,substring_index(GROUP_CONCAT(SUBSTRING(placementDescription,1,400),'$delim'),'$delim',2) as SecondPlacementDetail,substring_index(GROUP_CONCAT(SUBSTRING(infraDescription,1,400),'$delim'),'$delim',2) as SecondInfraDetail,substring_index(GROUP_CONCAT(SUBSTRING(facultyDescription,1,400),'$delim'),'$delim',2) as SecondFacultyDetail,substring_index(GROUP_CONCAT(reviewerId),',',2) as SecondReviewerIdDetail,substring_index(GROUP_CONCAT(userId),',',2) as SecondUserIdDetail,substring_index(GROUP_CONCAT(anonymousFlag),',',2) as SecondUserAnonymousFlagDetail,		
			substring_index(GROUP_CONCAT(review_seo_url,'$delim'),'$delim',2) as SecondReviewSeoUrl,	
			substring_index(GROUP_CONCAT(review_seo_title,'$delim'),'$delim',2) as SecondReviewSeoTitle,	
		substring_index(GROUP_CONCAT(recommendCollegeFlag),',',2) as SecondUserRecommendCollegeFlag,
			
			substring_index(GROUP_CONCAT(yearOfGraduation),',',2) as SecondUserYearOfGraduation,
			
			substring_index(GROUP_CONCAT(moneyRating),',',2) as SecondUserMoneyRating,
			substring_index(GROUP_CONCAT(crowdCampusRating),',',2) as SecondUserCrowdCampusRating,
			substring_index(GROUP_CONCAT(avgSalaryPlacementRating),',',2) as SecondUserAvgSalaryPlacementRating,
			
			substring_index(GROUP_CONCAT(campusFacilitiesRating),',',2) as SecondUserCampusFacilitiesRating,
			substring_index(GROUP_CONCAT(facultyRating),',',2) as SecondUserFacultyRating,
			substring_index(GROUP_CONCAT(postedDate),',',2) as SecondUserPostedDate,
			substring_index(GROUP_CONCAT(averageRating),',',2) as SecondUserAverageRating,
			substring_index(GROUP_CONCAT(postedDate),',',2) as SecondUserPostedDate,
			substring_index(GROUP_CONCAT(userId),',',2) as SecondUserUserId";

		if($page == "intermediate"){
			
			$sql = "SELECT SQL_CALC_FOUND_ROWS *,$group_query,count(*) as revCount FROM 
					(select distinct crmt.*,crmt.modificationDate as postedDate, 
					crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId ,crmtsi.yearOfGraduation 
					from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute 
					crmtsi on (crmt.id= crmtsi.reviewId) join shiksha_courses_type_information scti on (crmtsi.courseId = scti.course_id) join 
        		 shiksha_courses sc on (crmtsi.courseId = sc.course_id)
					where crmt.status='published' and scti.status = 'live' and scti.stream_id=? and scti.base_course=? and scti.substream_id=? and sc.education_type=? $courseSubQuery
				    order by crmtsi.yearOfGraduation desc, crmt.modificationDate desc) as T group by T.courseId"; 
			$query = $this->dbHandle->query($sql, array($stream,$baseCourse,$substream,$educationType));

		} else if($page == "homepage"){
			if(empty($courseSubQueryForHomepage)){
	        	$data['totalRows'] = 0;
	        	$data['results'] = array();
	        	return $data;
	        }
				$sql = "SELECT SQL_CALC_FOUND_ROWS *,$group_query,count(*) as revCount FROM 
					(select distinct crmt.*,crmt.modificationDate as postedDate, 
					crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId ,crmtsi.yearOfGraduation 
					from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute 
					crmtsi on (crmt.id= crmtsi.reviewId) where crmt.status='published'
				    AND crmtsi.courseId IN (?) 
				    ORDER BY crmtsi.yearOfGraduation DESC, crmt.modificationDate DESC) as T group by T.courseId
					ORDER BY T.yearOfGraduation DESC,T.postedDate DESC LIMIT $start,$count"; 

				$newCourseSubQueryForHomepage = explode(",", $courseSubQueryForHomepage);
				$query = $this->dbHandle->query($sql,array($newCourseSubQueryForHomepage));
		}


        $data['results'] = $query->result_array();
        
		$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $this->dbHandle->query($queryCmdTotal);
        $queryResults = $queryTotal->result();
        $data['totalRows'] = $queryResults[0]->totalRows;

		return $data;
	}

	function getPopularReviewsForSliderMobileNew($courseIds = '', $start, $count,$page='intermediate',$courseSubQueryForHomepage = '', $courseRankMapping = array()) {
		$resultSet = array();
		$this->initiateModel('read');
		if($courseIds!=''){
            $courseSubQuery = ' and crmtsi.courseId in ('.$this->dbHandle->escape($courseIds).')';
        }

		$delim = md5("%^&*shiksha%^&*");
		$query = "";
		$group_query = "substring_index(GROUP_CONCAT(id),',',2) as SecondReviewId,substring_index(GROUP_CONCAT(SUBSTRING(reviewDescription,1,400),'$delim'),'$delim',2) as SecondReviewDetail,substring_index(GROUP_CONCAT(SUBSTRING(placementDescription,1,400),'$delim'),'$delim',2) as SecondPlacementDetail,substring_index(GROUP_CONCAT(SUBSTRING(infraDescription,1,400),'$delim'),'$delim',2) as SecondInfraDetail,substring_index(GROUP_CONCAT(SUBSTRING(facultyDescription,1,400),'$delim'),'$delim',2) as SecondFacultyDetail,substring_index(GROUP_CONCAT(reviewerId),',',2) as SecondReviewerIdDetail,substring_index(GROUP_CONCAT(userId),',',2) as SecondUserIdDetail,substring_index(GROUP_CONCAT(anonymousFlag),',',2) as SecondUserAnonymousFlagDetail,	
			substring_index(GROUP_CONCAT(review_seo_url,'$delim'),'$delim',2) as SecondReviewSeoUrl,	
			substring_index(GROUP_CONCAT(review_seo_title,'$delim'),'$delim',2) as SecondReviewSeoTitle,	
			substring_index(GROUP_CONCAT(recommendCollegeFlag),',',2) as SecondUserRecommendCollegeFlag,
			
			substring_index(GROUP_CONCAT(yearOfGraduation),',',2) as SecondUserYearOfGraduation,
			
			substring_index(GROUP_CONCAT(moneyRating),',',2) as SecondUserMoneyRating,
			substring_index(GROUP_CONCAT(crowdCampusRating),',',2) as SecondUserCrowdCampusRating,
			substring_index(GROUP_CONCAT(avgSalaryPlacementRating),',',2) as SecondUserAvgSalaryPlacementRating,
			
			substring_index(GROUP_CONCAT(campusFacilitiesRating),',',2) as SecondUserCampusFacilitiesRating,
			substring_index(GROUP_CONCAT(facultyRating),',',2) as SecondUserFacultyRating,
			substring_index(GROUP_CONCAT(postedDate),',',2) as SecondUserPostedDate,
			substring_index(GROUP_CONCAT(postedDate),',',2) as SecondUserPostedDate,
			substring_index(GROUP_CONCAT(userId),',',2) as SecondUserUserId";


		$sql = "SELECT SQL_CALC_FOUND_ROWS t.*, $group_query ,count(*) as revCount FROM
			 (SELECT DISTINCT averageRating as ratings,
			 crmt.*,crmt.modificationDate as postedDate, 
			 crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId ,
			 crmtsi.yearOfGraduation FROM CollegeReview_MainTable crmt 
			 JOIN CollegeReview_MappingToShikshaInstitute crmtsi ON 
			 (crmt.id= crmtsi.reviewId) AND crmtsi.courseId IN (?) 
			 AND crmt.status='published' ORDER BY crmtsi.yearOfGraduation desc, 
			 crmt.modificationDate desc) AS t GROUP BY t.courseId desc
			"; 

		$newCourseSubQueryForHomepage = explode(",", $courseSubQueryForHomepage);
		$query = $this->dbHandle->query($sql,array($newCourseSubQueryForHomepage));
        $data['results'] = $query->result_array();
        $sort = array();
		foreach($data['results'] as $k=>$v) {
		    $sort['ratings'][$k] = $v['ratings'];
		    $sort['yearOfGraduation'][$k] = $v['yearOfGraduation'];
		    $sort['postedDate'][$k] = $v['postedDate'];
		    if(in_array($v['courseId'],array_keys($courseRankMapping))){
		    	$sort['rankings'][$k] = $courseRankMapping[$v['courseId']];
		    }
		}
		# sort by event_type desc and then title asc
		// array_multisort($sort['ratings'], SORT_DESC, $sort['yearOfGraduation'], SORT_DESC, $sort['postedDate'], SORT_DESC ,$data['results']);
		array_multisort($sort['rankings'], SORT_ASC, $sort['yearOfGraduation'], SORT_DESC, $sort['postedDate'], SORT_DESC ,$data['results']);
      	$data['results'] = array_slice($data['results'], $start,$count,true);

		$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $this->dbHandle->query($queryCmdTotal);
        $queryResults = $queryTotal->result();
        $data['totalRows'] = $queryResults[0]->totalRows;

		return $data;
	}


	public function getReviewInstituteDetailsByReviewId($reviewId)
	{
		$this->initiateModel('read');

		$sql = "select * from CollegeReview_MappingToShikshaInstitute where reviewId = ?";
		$query = $this->dbHandle->query($sql,array($reviewId));
		$result = $query->row_array();
		return $result;
		
	}

	public function getReviewUserDetailsByUserId($reviewerId = 0, $userId = 0)
	{
		$this->initiateModel('read');
		if($reviewerId > 0) {
			$sql = "select firstname, lastname, email from CollegeReview_PersonalInformation where id = ? ";
			$query = $this->dbHandle->query($sql,array($reviewerId));
			
		} else if($userId > 0) {
			$sql = "select firstname, lastname, email from tuser where userid = ? ";
			$query = $this->dbHandle->query($sql,array($userId));
		}
		
		$result = $query->row_array();
		return $result;
		
	}

	function getCatSubCatIdsByCourseId($course_id)
	{
		$this->initiateModel('read');

		$sql = "select cbt.parentId, lct.category_id from listing_category_table lct, categoryBoardTable cbt where lct.listing_type = ? and lct.listing_type_id = ? and lct.status = ? and lct.category_id = cbt.boardId";
		$query = $this->dbHandle->query($sql,array('course', $course_id, 'live'));
		$finalResult = array();
		foreach($query->result_array() as $result) {
			$finalResult['categoryId'] = $result['parentId'];
			$finalResult['subCategoryId'] = $result['category_id'];
		}
		return $finalResult;
	}

	function getTilesDataBySubCatId($subCatId, $limit = 2)
	{
		$this->initiateModel('read');

		$sql = "select * from CollegeReview_Tile where subCatId = ? and status = ? and seoUrl != '' order by rand() limit ?";
		$query = $this->dbHandle->query($sql,array($subCatId, 'live', $limit));

		$results = $query->result_array();
		return $results;
	}

	/*
	* Function to get tiles data according to the data array given
	* @params : array
	* @return : tile data array
	*/
	function getTilesByHierarchy($data, $limit = 2){
		
		if(!empty($data) && is_array($data) && count($data) > 0){
			$this->initiateModel('read');
			
			$subQuery = array();
			if($data['streamId'] > 0){
				$subQuery[] = "streamId = ".$this->dbHandle->escape($data['streamId']);
			}
			if($data['subStreamId'] > 0){
				$subQuery[] = "subStreamId = ".$this->dbHandle->escape($data['subStreamId']);
			}
			if($data['baseCourseId'] > 0){
				$subQuery[] = "baseCourseId = ".$this->dbHandle->escape($data['baseCourseId']);
			}
			if($data['educationType'] > 0){
				$subQuery[] = "educationType = ".$this->dbHandle->escape($data['educationType']);
			}
			if($data['deliveryMethod'] > 0){
				$subQuery[] = "deliveryMethod = ".$this->dbHandle->escape($data['deliveryMethod']);
			}
			if($data['oldsbCatId'] > 0){
				$subQuery[] = "subCatId = ".$this->dbHandle->escape($data['oldsbCatId']);
			}

			if(!empty($subQuery)){
				$subQuery = implode(' and ', $subQuery);
			}
			$sql = "select * from CollegeReview_Tile where status = ? and seoUrl != '' and ".$subQuery." order by tileOrder limit ?";
			$query = $this->dbHandle->query($sql,array('live', $limit));
			
			$results = $query->result_array();
			return $results;
		}
	}

	function getReviewsCountByCourseId($courseId)
	{
		$this->initiateModel('read');

		$sql = "select count(*) as count from CollegeReview_MappingToShikshaInstitute cmsi inner join CollegeReview_MainTable cmt on cmsi.reviewId = cmt.id where cmsi.courseId = ? and cmt.status = ?";
		$query = $this->dbHandle->query($sql, array($courseId, 'published'));
		$result = $query->row_array();
		$count = $result['count'];
		return $count;
	}

	function insertABTracking($source,$userData,$reviewId){
		$this->initiateModel('write');

		$sql = "insert into CollegeReview_ABTracking (id,source,userdata,reviewId) values ('',?,?,?)";	
		$this->dbHandle->query($sql,array($source,$userData,$reviewId));

		return $this->dbHandle->insert_id();
	}	
	
	function getReviewId($mobileNo){
		$this->initiateModel('read');

		$sql = "select id from CollegeReview_PersonalInformation where mobile = ? order by id desc limit 1";
		$query = $this->dbHandle->query($sql, array($mobileNo));

		$reviewId = $query->result_array();
		return $reviewId[0]['id'];
	}

	function getReviewIdOnEmail($emailId){
		$this->initiateModel('read');

		$sql = "select id from CollegeReview_PersonalInformation where email = ? order by id desc limit 1";
		$query = $this->dbHandle->query($sql, array($emailId));

		$reviewId = $query->result_array();
		return $reviewId[0]['id'];
	}


	function getAllCollegeReviews() 
	{
		$this->initiateModel('read');

		$start = microtime();
		$sql = "SELECT * FROM CollegeReview_MainTable 
				WHERE status = 'published' AND isShikshaInstitute = 'YES' 
				AND review_seo_url IS NULL 
				AND review_seo_title IS NULL";
		$query = $this->dbHandle->query($sql);
		$end = microtime();
		$timeElapsed = $end - $start ;
		error_log("######Time taken to get All Reviews ".$timeElapsed);
		$results = $query->result_array();
		$count = $query->num_rows();
		_P("Total Reviews : ".$count);
		
		return $results;

	}

	function saveUserReferralInfo($userReferralData)
	{
		$this->initiateModel('write');

		$sql = "INSERT INTO  CollegeReview_UserReferralInfo (email, referralCode, referralURL) VALUES (?, ?, ?) ".
		       " ON DUPLICATE KEY UPDATE added_on=now()";
		       
		$query = $this->dbHandle->query($sql,array($userReferralData['email'],$userReferralData['referralCode'], $userReferralData['referralURL']));
		$last_insert_id = $this->dbHandle->insert_id();

		return $last_insert_id;
	}

	public function getUserReferralInfoByEmail($email)
	{
		$this->initiateModel('read');
		$sql = "select * from CollegeReview_UserReferralInfo where email = ? ";
		$query = $this->dbHandle->query($sql,array($email));
		
		$result = $query->row_array();
		return $result;
		
	}

	public function getUserReferralInfoByReferralCode($referralCode)
	{
		$this->initiateModel('read');
		$sql = "select * from CollegeReview_UserReferralInfo where referralCode = ? ";
		$query = $this->dbHandle->query($sql,array($referralCode));
		
		$result = $query->row_array();
		return $result;
		
	}

	function saveReferralTrackingInfo($referralTrackingData)
	{		
		$this->initiateModel('write');

		$sql = "INSERT INTO  CollegeReview_ReferralTrackingInfo (parentReferralId, userReferralId, userReviewId, reviewSubmittedBy) VALUES (?, ?, ?, ?)";
		$query = $this->dbHandle->query($sql,array($referralTrackingData['parentReferralId'], $referralTrackingData['userReferralId'], $referralTrackingData['userReviewId'], $referralTrackingData['reviewSubmittedBy']));

		return true;
	}

	/* Function to get the review count from CollegeReview_Maintable from the given date
	 * @Params:  $date => date from which count is required
	 * @return: TotalCount
	 */

	function getReviewCountByDate($date){
		$this->initiateModel('read');
		if(empty($date)){
			$date = date("Y-m-d", strtotime("-1 day"));
		}

		$sql = "select count(*) as count from CollegeReview_MainTable where DATE(creationDate) = ?";
		$query = $this->dbHandle->query($sql, array($date))->result_array();
		
		return $query[0]['count'];
	}

	/* Function to get the review data from CollegeReview_ModerationTable from the given date
	 * @Params:  $date => date from which date is required
	 * @return: TotalCount
	 */
	function getModerationDetailsByDate($date){
		$this->initiateModel('read');
		
		if(empty($date)){
			$date = date("Y-m-d", strtotime("-1 day"));
		}

		$sql = "Select moderatorEmail, actionType from CollegeReview_ModerationTable where 	DATE(moderationTime) = ?";
		return $this->dbHandle->query($sql, array($date))->result_array();
	}


	function addToMaster($masterRatingList,$masterMotivationList){
		$this->initiateModel('write');


		$sql = "INSERT INTO CollegeReview_MasterTable(description,type,status) 
				VALUES";
		
		foreach ($masterRatingList as $description) {
			 $sql .= "('".$description."','rating','live'),";		
		}
		
		$sql = substr($sql, 0,-1);

		$this->dbHandle->query($sql);

		//do again for motivation

		$sql = "INSERT INTO CollegeReview_MasterTable(description,type,status) 
				VALUES";
		
		foreach ($masterMotivationList as $description) {
			 $sql .= "('".$description."','motivation','live'),";		
		}
		
		$sql = substr($sql, 0,-1);

		$this->dbHandle->query($sql);

	}

	function addToCateogryMaster($mappingArray,$mappingCategory){
		$this->initiateModel('write');

		$sql = "select id , description from CollegeReview_MasterTable where status = 'live' and type = 'rating'";

		$result = $this->dbHandle->query($sql)->result_array();

		$temp =array();
		foreach ($result as $key => $value) {
			$tempMap[$value['description']] = $value['id'];
		}



		foreach ($mappingCategory as $keyCat => $value) {
			$ratingParam = $mappingArray[$keyCat];

			foreach ($value as $categoryId) {
				foreach ($ratingParam as $param) {
					$sql = "insert into CollegeReview_CategoryMasterMapping (masterId,categoryId,status) values(?,?,'live')";	

					$this->dbHandle->query($sql,array($tempMap[$param],$categoryId));
				}
			}
			
		}

	}

	function scriptToCateogryMasterMotivation($mappingArray,$mappingCategory){
		$this->initiateModel('write');

		$sql = "select id , description from CollegeReview_MasterTable where status = 'live' and type = 'motivation'";

		$result = $this->dbHandle->query($sql)->result_array();

		$temp =array();
		foreach ($result as $key => $value) {
			$tempMap[$value['description']] = $value['id'];
		}

	

		foreach ($mappingCategory as $keyCat => $value) {
			$ratingParam = $mappingArray[$keyCat];
			foreach ($value as $categoryId) {
				foreach ($ratingParam as $param) {
					$sql = "insert into CollegeReview_CategoryMasterMapping (masterId,categoryId,status) values(?,?,'live')";	
					//echo $tempMap[$param]." ".$categoryId;
					$this->dbHandle->query($sql,array($tempMap[$param],$categoryId));
				}
			}

			$ratingParam = array();
			
		}

	}


	function getDefaultReviewRatingForm(){
		$this->initiateModel('read');
		global $managementStreamMR;
		global $mbaBaseCourse;
		$sql = "select crMaster.id,description from CollegeReview_MasterTable crMaster 
				join CollegeReview_CategoryMasterMapping crCategory on crMaster.id = crCategory.masterId
				where crCategory.streamId = ? and crCategory.baseCourseId = ? and crMaster.type='rating' and  crMaster.status = 'live'
				 and crCategory.status = 'live'";

		$result	= $this->dbHandle->query($sql,array($managementStreamMR,$mbaBaseCourse))->result_array();
		return $result;
				
	}

	function getReviewRatingForm($stream_id,$base_course){

		$this->initiateModel('read');
		global $managementStreamMR;
		global $engineeringtStreamMR;

		if($stream_id == $managementStreamMR || $stream_id == $engineeringtStreamMR){
			$sql = "select crMaster.id,description from CollegeReview_MasterTable crMaster 
					join CollegeReview_CategoryMasterMapping crCategory on crMaster.id = crCategory.masterId
					where crCategory.streamId = ?
					and crCategory.baseCourseId = ?	
					and crMaster.type='rating' 
					and crMaster.status = 'live' 
					and crCategory.status = 'live'";

			$result	= $this->dbHandle->query($sql,array($stream_id,$base_course))->result_array();
		}else{

			$sql = "select crMaster.id,description from CollegeReview_MasterTable crMaster 
					join CollegeReview_CategoryMasterMapping crCategory on crMaster.id = crCategory.masterId
					where crCategory.streamId = ?
					and crMaster.type='rating' 
					and crMaster.status = 'live' 
					and crCategory.status = 'live'";

			$result	= $this->dbHandle->query($sql,array($stream_id))->result_array();
		}

		return $result;
	}

	/*function getDefaultMotivationList(){
		$this->initiateModel('read');

		$sql = "select crMaster.id, description from CollegeReview_MasterTable crMaster 
				join CollegeReview_CategoryMasterMapping crCategory on crMaster.id = crCategory.masterId
				where crCategory.categoryId = 23 and crMaster.type='motivation'
				and  crMaster.status = 'live' and crCategory.status = 'live'";

		$result	= $this->dbHandle->query($sql)->result_array();	

		return $result;
	}*/

	/*function getMotivationList($categoryId){

		$this->initiateModel('read');

		$sql = "select crMaster.id, description from CollegeReview_MasterTable crMaster 
				join CollegeReview_CategoryMasterMapping crCategory on crMaster.id = crCategory.masterId
				where crCategory.categoryId = ? and crMaster.type='motivation'
				and  crMaster.status = 'live' and crCategory.status = 'live'";


		$result	= $this->dbHandle->query($sql,array($categoryId))->result_array();

		return $result;
	}*/

	function getRatingParamCount($stream_id,$base_course){
		$this->initiateModel('read');
	
		global $managementStreamMR;
		global $engineeringtStreamMR;

		if($stream_id == $managementStreamMR || $stream_id == $engineeringtStreamMR){
			$sql = "select count(description) as count from CollegeReview_MasterTable crMaster 
					join CollegeReview_CategoryMasterMapping crCategory on crMaster.id = crCategory.masterId
					where crCategory.streamId = ?
					and crCategory.baseCourseId = ?	
					and crMaster.type='rating' 
					and crMaster.status = 'live' 
					and crCategory.status = 'live'";

			$result	= $this->dbHandle->query($sql,array($stream_id,$base_course))->result_array();
		}else{
			$sql = "select count(description) as count from CollegeReview_MasterTable crMaster 
					join CollegeReview_CategoryMasterMapping crCategory on crMaster.id = crCategory.masterId
					where crCategory.streamId = ?
					and crMaster.type='rating' 
					and crMaster.status = 'live' 
					and crCategory.status = 'live'";

			$result	= $this->dbHandle->query($sql,array($stream_id))->result_array();
		}

		return $result[0]['count'];
	}

	function getRatingReviewId($reviewId){
		if(empty($reviewId)){
			return false;
		}
		$this->initiateModel('read');

		$sql = "select map.rating,crMaster.description,crMaster.id as ratingId 
				from CollegeReview_RatingMapping map, CollegeReview_MasterTable crMaster
				where crMaster.id = map.masterRatingId and crMaster.status ='live' 
				and crMaster.type ='rating'	and map.reviewId =? and map.status = 'live'";

		$result	= $this->dbHandle->query($sql,array($reviewId))->result_array();
		return $result;
	}

	function getMotivationDescription($reviewId){
		$this->initiateModel('read');

		$sql = "select crMaster.description from CollegeReview_MasterTable crMaster, CollegeReview_MotivationTable mot 
				where crMaster.id = mot.motivationMasterId and crMaster.status ='live' and crMaster.type ='motivation'
				and mot.reviewId =?";

		$result	= $this->dbHandle->query($sql,array($reviewId))->result_array();


		return $result[0]['description'];
	}

	function getMotivationIdByReviewId($reviewId){
		if(empty($reviewId)){
			return false;
		}
		$this->initiateModel('read');

		$sql = "select mot.motivationMasterId from CollegeReview_MasterTable 
				crMaster, CollegeReview_MotivationTable mot where 
				crMaster.id = mot.motivationMasterId and crMaster.status ='live' 
				and crMaster.type ='motivation'	and mot.reviewId =?";

		$result	= $this->dbHandle->query($sql,array($reviewId))->row_array();


		return $result['motivationMasterId'];
	}


	/**
	* Function to store Uber data in database
	* @param: $existingUberAccount value of radio button
	* 			$uberEmail email id to which uber coupon to be mailed
	*			$reviewId reviewId of the review selected
	**/
	function storeUberData($existingUberAccount, $uberEmail, $reviewId){
		$this->initiateModel('write');

		$sql = "INSERT INTO `CollegeReview_UberDetail` (reviewId,uberEmail,existingUberAccount) VALUES ( ?, ?, ?)";

		$result	= $this->dbHandle->query($sql,array($reviewId,$uberEmail,$existingUberAccount));

	}

	function updateUserFBData($fbURL,$reviewId){
		$this->initiateModel('write');

		$sql = "update `CollegeReview_PersonalInformation` set facebookURL =?  where id = ?";

		$this->dbHandle->query($sql,array($fbURL,$reviewId));
	}

	/*
     * Extracts review id and client IP from CollegeReview_MainTable and session_tracking
     * @params:  $from{date} => Date from where data is required
     *			 $to{date} => Date upto which data is required
     *  @returns => {array}: having review ids and client Ip
     */
	function collegeReviewSourceData($from, $to){
		if(empty($from) || empty($to)){
			return array();
		}

		$this->initiateModel('read');
	
		$sql = 'SELECT mt.id/*, st.clientIP*/, mt.visitorSessionId, pi.email FROM CollegeReview_MainTable mt
				INNER JOIN  CollegeReview_PersonalInformation pi ON pi.id=mt.reviewerId
				/*INNER JOIN session_tracking st ON st.sessionId = mt.visitorSessionId*/
				WHERE mt.creationDate >= ? AND mt.creationDate < ?';
		return $this->dbHandle->query($sql,array($from,$to))->result_array();
	}

	/*
     * Extracts institue and course name for a review
     * @params:  $reviewIds{array} => contains the review Id
     *
     *  @returns => {array}: having reviewid, instituteName, courseName
     */
	function getCRDataFromNonMappedInstitute($reviewIds){
		if(empty($reviewIds)){
			return array();
		}

		$this->initiateModel('read');
		$sql = 'SELECT reviewid, instituteName, courseName FROM CollegeReview_MappingToNonShikshaInstitute WHERE reviewId IN (?)';
		return $this->dbHandle->query($sql,array($reviewIds))->result_array();
	}

	/*
     * Extracts institue and course Id for a review
     * @params:  $reviewIds{array} => contains the review Id
     *
     *  @returns => {array}: having reviewid, instituteId, courseId
     */
	function getCRDataFromMappedInstitute($reviewIds){
		if(empty($reviewIds)){
			return array();
		}

		$this->initiateModel('read');
		$sql = 'SELECT reviewid, courseId FROM CollegeReview_MappingToShikshaInstitute WHERE reviewId IN (?)';

		return $this->dbHandle->query($sql,array($reviewIds))->result_array();
	}

	function getAllCourseForCategory($categoryId,$otherFlag){
		if(empty($categoryId)){
			return array();
		}

		$notInClause ='';
		if($otherFlag){
			$notInClause = 'NOT';
		}

		//$inClause = implode(',', $categoryId);

		$this->initiateModel('read');
		$sql ="SELECT DISTINCT course_id FROM categoryPageData WHERE status = 'live' AND category_id ".$notInClause." IN (?)";

		return $this->dbHandle->query($sql,array($categoryId))->result_array();
	}

	function getDashboardData($courseIds){
		if(empty($courseIds)){
			return array();
		}
		$this->initiateModel('read');

		//$inClause = implode(',', $courseIds);

		$sql ="select crmt.status,count(*) as count from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) where crmtsi.courseId IN (?) and crmt.status IN ('published', 'draft', 'accepted', 'later', 'rejected') group by crmt.status";

		 return $this->dbHandle->query($sql,array($courseIds))->result_array();		 
	}

	function getDashboardUnMapped(){
		$this->initiateModel('read');

		$sql ="select a.status,count(*) as count from CollegeReview_MainTable a, CollegeReview_MappingToNonShikshaInstitute b
 				where a.id = b.reviewId group by a.status";

		return $this->dbHandle->query($sql)->result_array();
	}

	function updateEngineeringTiles(){
		$this->initiateModel('write');

		$sqlForStreamByOldSubcat = "Select stream_id, base_course_id, substream_id, education_type, delivery_method from base_entity_mapping where oldsubcategory_id = '56' and oldspecializationid = 0";
		$mappingForStreamByOldSubcat = $this->dbHandle->query($sqlForStreamByOldSubcat)->row_array();

		$sql1 = "Update CollegeReview_Tile set `streamId`=? , `subStreamId`=? , `baseCourseId`=? ,`educationType`=? ,`deliveryMethod`=? where `subCatId`='56'";

		$this->dbHandle->query($sql1,array($mappingForStreamByOldSubcat['stream_id'],$mappingForStreamByOldSubcat['substream_id'],$mappingForStreamByOldSubcat['base_course_id'],$mappingForStreamByOldSubcat['education_type'],$mappingForStreamByOldSubcat['delivery_method']));

		return 1;
	}

	function updateMBATiles(){
		$this->initiateModel('write');

		$sqlForStreamByOldSubcat = "Select stream_id, base_course_id, substream_id, education_type, delivery_method from base_entity_mapping where oldsubcategory_id = '23' and oldspecializationid = 0";
		$mappingForStreamByOldSubcat = $this->dbHandle->query($sqlForStreamByOldSubcat)->row_array();

		$sql1 = "Update CollegeReview_Tile set `streamId`=? , `subStreamId`=? , `baseCourseId`=? ,`educationType`=? ,`deliveryMethod`=? where `subCatId`='23'";

		$this->dbHandle->query($sql1,array($mappingForStreamByOldSubcat['stream_id'],$mappingForStreamByOldSubcat['substream_id'],$mappingForStreamByOldSubcat['base_course_id'],$mappingForStreamByOldSubcat['education_type'],$mappingForStreamByOldSubcat['delivery_method']));

		return 1;
	}

	function getTileURL(){
		$this->initiateModel('read');
		$sql = "Select seoUrl from CollegeReview_Tile where subCatId='56'";
		$data = $this->dbHandle->query($sql)->result_array();
		return $data;
	}

	function updateTileURL($seoUrl,$newSeoUrl){
		$this->initiateModel('write');

		$sql = "Update CollegeReview_Tile set `seoUrl` = ? where `seoUrl` = ?";

		$this->dbHandle->query($sql,array($newSeoUrl,$seoUrl));

		return 1;
	}

	/** 
	 *	Function to get all college reviews by course id
	 *	@param: $courseIds array of course ids
	 *	@param: $orderBy string for sorting on the basis of year of graduation/recency or average rating
	 *	@return: $resultSet array containing reviews, number of reviews, reviewer details
	 */
	function getAllReviewsByCourse($courseIds,$start,$count){
		
		if(!is_array($courseIds) || !(count($courseIds) > 0) || empty($courseIds)) {
			return array();
		}
				
		$this->initiateModel('read');
		
		// if(is_array($courseIds)){
		// 	$courseIds = implode(',', $courseIds);
		// }

		$courseSubQuery = ' and crmtsi.courseId in (?)';
		
		$orderSubQuery = 'crmtsi.yearOfGraduation desc, crmt.modificationDate desc';
		
		$sql = "select distinct SQL_CALC_FOUND_ROWS crmt.*, crmt.modificationDate as postedDate, ".
		       "crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId, crmtsi.yearOfGraduation, ".
		       "crmt.reviewDescription from CollegeReview_MainTable crmt ".
		       "join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) ".
		       "where crmt.status='published' $courseSubQuery order by $orderSubQuery";

		$query = $this->dbHandle->query($sql,array($courseIds));		
		$totalRows = "SELECT FOUND_ROWS() as count";
		$queryCount = $this->dbHandle->query($totalRows);
		$countReviews = $queryCount->result_array();
		$results = $query->result_array();

		if(count($results)>0){
			$resultSet['result'] = $results;
			$resultSet['reviewerDetails'] = $this->getReviewerDetails($results);
			$resultSet['countReviews'] = $countReviews[0]['count'];
		}
		
		$final_result = array();		
		
		foreach($resultSet['result'] as $key=>$row) {
			$row['reviewerDetails'] = $resultSet['reviewerDetails'][$row['id']];
			$final_result[$row['courseId']]['reviews'][] = $row; 				
	    }
	    
		foreach($final_result as $key=>$val) {
			$final_result[$key]['totalCount'] = count($val['reviews']);
			if($count >0) {
				$final_result[$key]['reviews'] = array_slice($val,$start,$count,true);
			}				
		}
		
		return $final_result;
	}

	function getReviewerDetailsByReviewId($reviewid){
		if($reviewid > 0){
			$this->initiateModel('read');
			$sql = 'select crmsi.*, crpi.* from CollegeReview_PersonalInformation crpi 
					join CollegeReview_MainTable crmt on crmt.reviewerId = crpi.id join 
					CollegeReview_MappingToShikshaInstitute crmsi on crmsi.reviewId = crmt.id 
					where crmt.id = ?';

			$results = $this->dbHandle->query($sql,$reviewid)->row_array();

			return $results;
		}
	}

	function updateCourseIdForReview($courseData, $dbHandle){
		if(is_array($courseData)){
			if(empty($dbHandle)) {
				$this->initiateModel('write');
			}
			else {
				$this->dbHandle = $dbHandle;
			}
			$sql = 'UPDATE CollegeReview_MappingToShikshaInstitute
			  SET courseId=? ,instituteId=?,locationId=? where courseId=?';
			$results = $this->dbHandle->query($sql,array($courseData['newCourseId'],$courseData['instituteId'],$courseData['locationId'],$courseData['oldCourseId']));

		}
	}

	function updateInstituteIdForReview($instituteData){
		if(is_array($instituteData)){
			$this->initiateModel('write');
			$sql = 'UPDATE CollegeReview_MappingToShikshaInstitute
			  SET instituteId=? where instituteId=?';
			$results = $this->dbHandle->query($sql,array($instituteData['newInstituteId'],$instituteData['oldInstituteId']));

		}
	}

	function updateMultipleInstituteIdForReview($oldInstituteIds,$newInstitute){
			$this->initiateModel('write');
			$sql = 'UPDATE CollegeReview_MappingToShikshaInstitute
			  SET instituteId=? where instituteId in ('.$oldInstituteIds.')';
			$results = $this->dbHandle->query($sql,array($newInstitute));
		
	}

	function getAllDistinctSubcat(){
		$this->initiateModel('read');

		$sql = "select group_concat(DISTINCT categoryId) as subCategoryId from CollegeReview_CategoryMasterMapping";
		$results = $this->dbHandle->query($sql)->row_array();
		return $results;
	}

	function getNewHierarchy($distinctSubCat){
		$this->initiateModel('read');

		$sql = "select oldsubcategory_id, stream_id, base_course_id from base_entity_mapping
				where oldsubcategory_id in (?) and oldspecializationid=0";
		
		$distinctSubCat = explode(",", $distinctSubCat);
		$results = $this->dbHandle->query($sql,array($distinctSubCat))->result_array();
		return $results;
	}

	function updateRatingMappingByStream($newhierarchy){
		$this->initiateModel('write');
		foreach ($newhierarchy as $key => $mapping) {
			if($mapping['oldsubcategory_id'] == 23 || $mapping['oldsubcategory_id'] == 56 || $mapping['oldsubcategory_id'] == 28){
				$sql = "UPDATE CollegeReview_CategoryMasterMapping SET 
						streamId = ?, baseCourseId = ? where categoryId = ?";
				$results = $this->dbHandle->query($sql,array($mapping['stream_id'],$mapping['base_course_id'],$mapping['oldsubcategory_id']));
			}else{
				$sql = "UPDATE CollegeReview_CategoryMasterMapping SET 
						streamId = ? where categoryId=?";
				$results = $this->dbHandle->query($sql,array($mapping['stream_id'],$mapping['oldsubcategory_id']));
			}
			
		}

	}

	function getAverageReviewRatingandCount($courseId){ 
        if(!isset($courseId) || $courseId == ''){ 
                return false; 
        } 
         
        $this->initiateModel('read'); 

        $sql = "select round(sum(averageRating),2) as sum,count(*) as totalReview from CollegeReview_MappingToShikshaInstitute crshk  
                        join CollegeReview_MainTable crmt on crshk.reviewId = crmt.id and crmt.status = 'published' 
                where crshk.courseId = ?"; 
	 	 
        $result = $this->dbHandle->query($sql,array($courseId))->result_array(); 
        return $result[0]; 
	}

	function getReviewsDetails($reviewIds,$onlyPublished = true){

		$this->initiateModel('read');
		if($onlyPublished){
			$sql = "select distinct SQL_CALC_FOUND_ROWS crmt.*,crmt.creationDate as postedDate, ".
		       "crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId, crmtsi.yearOfGraduation, ".
		       "crmt.reviewDescription from CollegeReview_MainTable crmt ".
		       "join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) ".
		       "where (crmt.status='published') and crmt.id in (?) group by crmt.id";
		}
		else{
			$sql = "select distinct SQL_CALC_FOUND_ROWS crmt.*,crmt.creationDate as postedDate, ".
		       "crmtsi.locationId, crmtsi.instituteId, crmtsi.courseId, crmtsi.yearOfGraduation, ".
		       "crmt.reviewDescription from CollegeReview_MainTable crmt ".
		       "join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) ".
		       "where (crmt.status='published' OR crmt.status='unverified') and crmt.id in (?) group by crmt.id";
		}

		$results = $this->dbHandle->query($sql,array($reviewIds))->result_array();
		
		if(count($results)>0){
			foreach($results as $reviews){
				$resultSet['result'][$reviews['id']] = $reviews;
			}
			$resultSet['reviewerDetails'] = $this->getReviewerDetails($results);
		}

		$final_result = array();		
		
		foreach($resultSet['result'] as $key=>$row) {
			$row['reviewerDetails'] = $resultSet['reviewerDetails'][$row['id']];
			$final_result['reviews'][$row['id']] = $row; 				
	    }
		return $final_result;
	}

	function getRatingMultipleReviews($reviewIds){
		// if(is_array($reviewIds) && !empty($reviewIds)){
		// 	$reviewIds = implode(',', $reviewIds);
		// }else{
		// 	return;
		// }

		$this->initiateModel('read');

		$sql = "select map.reviewId,map.rating,crMaster.description,crMaster.id as ratingId 
				from CollegeReview_RatingMapping map, CollegeReview_MasterTable crMaster
				where crMaster.id = map.masterRatingId and crMaster.status ='live' 
				and crMaster.type ='rating'	and map.reviewId in (?) and map.status = 'live'";

		$result	= $this->dbHandle->query($sql,array($reviewIds))->result_array();
		
		foreach($result as $key=>$val){
			$ratings[$val['reviewId']][$val['ratingId']] = $val['rating'];
		}

		return $ratings;
	}

	/** 
	 *	Function to get all college reviews ratings by course id
	 *	@param: $courseIds array of course ids
	 *	@return: $resultSet array containing review rating and course id
	 */
	function getReviewRatingsByCourse($courseIds,$start,$count){
		
		if(!is_array($courseIds) || count($courseIds) == 0) {
			return array();
		}
				
		$this->initiateModel('read');
		
		// if(is_array($courseIds)){
		// 	$courseIds = implode(',', $courseIds);
		// }

		// $courseSubQuery = ' and crmtsi.courseId in ('.mysql_escape_string($courseIds).')';
				
		$sql = "select avg(crmt.averageRating), count(distinct(crmt.id)), ".
		       "crmtsi.courseId ".
		       "from CollegeReview_MainTable crmt ".
		       "join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) ".
		       "where crmt.status='published' and crmtsi.courseId in (?) group by crmtsi.courseId";

		$query = $this->dbHandle->query($sql,array($courseIds));
		
		$results = $query->result_array();
		
		/*if(count($results)>0){
			$resultSet['result'] = $results;
			$resultSet['reviewerDetails'] = $this->getReviewerDetails($results);
			$resultSet['countReviews'] = $countReviews[0]['count'];
		}*/
		
		$final_result = array();		
		
		foreach($results as $key=>$row) {
			$final_result[$row['courseId']]['overallAverageRating'] = $row['avg(crmt.averageRating)']; 				
			$final_result[$row['courseId']]['number'] = $row['count(distinct(crmt.id))'];
	    }
	    /*
		foreach($final_result as $key=>$val) {
			$final_result['totalCount'] = count($final_result['reviews']);
			if($count >0) {
				$final_result['reviews'] = array_slice($val,$start,$count,true);
			}				
		}*/
		
		return $final_result;
	}

	/** 
	 *	Function to get all college reviews by course id
	 *	@param: $courseIds array of course ids
	 *	@param: $orderBy string for sorting on the basis of year of graduation/recency or average rating
	 *	@return: $resultSet array containing reviews, number of reviews, reviewer details
	 */
	function getLatestReviewsByCourseId($courseId, $start = 0, $count = 1) {
		
		if($courseId <= 0) {
			return array();
		}
				
		$this->initiateModel('read');
		
		$sql = "select crmt.reviewDescription, crmt.placementDescription from CollegeReview_MainTable crmt ".
		       "inner join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id= crmtsi.reviewId) ".
		       "where crmt.status='published' AND crmtsi.courseId = ? order by crmtsi.yearOfGraduation desc, crmt.modificationDate desc limit ?,?";

		$query = $this->dbHandle->query($sql,array($courseId,$start,$count));
		$results = $query->result_array();

		return $results;
	}


		function getAllReviewDataForIndexing($reviewId){
		if(!($reviewId > 0)){
			return array();
		}

		$this->initiateModel('read');
		$sql = "Select crmt.id as reviewId, crpi.id as reviewerId, crpi.email as email,
				 crpi.mobile as mobile,crpi.facebookURL as facebookUrl, crpi.linkedInURL as linkedinUrl,
				 crmt.placementDescription as placement, crmt.facultyDescription as faculty,
				 crmt.infraDescription as infrastructure, crmt.reviewDescription as otherDetails, 
				 crmtsi.courseId as courseId, crmtsi.instituteId as instituteId,
				 crmt.creationDate as creationDate, crmt.modificationDate as modificationDate,
				 crmt.averageRating as averageRating, crpi.firstname as firstname, crpi.lastname as lastname,
				 crmt.anonymousFlag as isAnonymous, crmt.helpfulFlagCount as helpfulCount,
				 crmt.status as reviewStatus,crmtsi.yearOfGraduation as yearOfGraduation,
				 crpi.isdCode as isdCode, crmt.notHelpfulFlagCount as notHelpfulCount, 
				 scti.stream_id as streamId, scti.base_course as baseCourse, crmtsi.locationId as locationId,
				 sc.name as courseName, si.name as instituteName
				 from CollegeReview_MainTable crmt join CollegeReview_PersonalInformation crpi on
				 (crmt.reviewerId = crpi.id) join CollegeReview_MappingToShikshaInstitute crmtsi
				 on (crmt.id=crmtsi.reviewId) join shiksha_courses_type_information scti on
				 (scti.course_id = crmtsi.courseId) join shiksha_courses sc on sc.course_id=crmtsi.courseId 
				 join shiksha_institutes si on (si.listing_id = crmtsi.instituteId)
				 where crmt.id=? and crmt.reviewerId !=0 and sc.status = 'live' ";

		$query = $this->dbHandle->query($sql,array($reviewId));
		$results = $query->result_array();

		if(!empty($results[0])){
			$results[0]['id'] = $reviewId;
			$results[0]['isMapped'] = 'YES';
		}
		
		return $results[0];
	}

	function getAllUnmappedReviewForIndexing($reviewId){
		if(!($reviewId > 0)){
			return array();
		}

		$this->initiateModel('read');
		$sql = "Select crmt.id as reviewId, crpi.id as reviewerId, crpi.email as email,
				 crpi.mobile as mobile,crpi.facebookURL as facebookUrl, crpi.linkedInURL as linkedinUrl,
				 crmt.placementDescription as placement, crmt.facultyDescription as faculty,
				 crmt.infraDescription as infrastructure, crmt.reviewDescription as otherDetails, 
				 crmtnsi.courseName as courseName, crmtnsi.instituteName as instituteName,
				 crmt.creationDate as creationDate, crmt.modificationDate as modificationDate,
				 crmt.averageRating as averageRating, crpi.firstname as firstname, crpi.lastname as lastname,
				 crmt.anonymousFlag as isAnonymous, crmt.helpfulFlagCount as helpfulCount,
				 crmt.status as reviewStatus,crmtnsi.yearOfGraduation as yearOfGraduation,
				 crpi.isdCode as isdCode, crmt.notHelpfulFlagCount as notHelpfulCount, 
				 crmtnsi.locationName as locationName
				 from CollegeReview_MainTable crmt join CollegeReview_PersonalInformation crpi on
				 (crmt.reviewerId = crpi.id) join CollegeReview_MappingToNonShikshaInstitute crmtnsi
				 on (crmt.id=crmtnsi.reviewId) where crmt.id=? and crmt.reviewerId !=0 ";

		$query = $this->dbHandle->query($sql,array($reviewId));
		$results = $query->result_array();
		if(!empty($results[0])){
			$results[0]['id'] = $reviewId;
			$results[0]['isMapped'] = 'NO';
		}
		
		return $results[0];
	}

	function getAllReviewIds(){
		$this->initiateModel('read');

		$sql = "Select id from CollegeReview_MainTable where reviewerId >0";

		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();

		return $results;		
	}



	function getDuplicateDataForCRMTSI(){
		$this->initiateModel('read');

		$sql = "select reviewId from  CollegeReview_MappingToShikshaInstituteBkp group by reviewId, instituteId, locationId, courseId, yearOfGraduation having count(*) > 1";

		$query = $this->dbHandle->query($sql);
		$duplicateIds = $query->result_array();

		return $duplicateIds;
	}

	function getIdsForDeletion($reviewId){
		$this->initiateModel('read');

		$sql = "select group_concat(id) as idList from CollegeReview_MappingToShikshaInstituteBkp where reviewId=? group by reviewId";
		$query = $this->dbHandle->query($sql,array($reviewId));
		$allids = $query->result_array();

		return $allids;
	}

	function deleteDuplicateRows($deleteIdsNew,$revId){
		$this->initiateModel('write');
		$sql = "delete from CollegeReview_MappingToShikshaInstituteBkp where reviewId = $revId AND id <> $deleteIdsNew";
		echo $sql.'<br>';
		$query = $this->dbHandle->query($sql,array($revId,$deleteIdsNew));

	}

	function getZeroYOG(){
		$this->initiateModel('read');

		$sql = "delete from CollegeReview_MappingToShikshaInstituteBkp where yearOfGraduation=0";

		$query = $this->dbHandle->query($sql);

		return;
	}

	function getZeroCourse(){
		$this->initiateModel('read');

		$sql = "delete from CollegeReview_MappingToShikshaInstituteBkp where courseId=0";

		$query = $this->dbHandle->query($sql);

		return;
	}

	function getZeroInst(){
		$this->initiateModel('read');

		$sqlReadCourses = "select courseId, reviewId from CollegeReview_MappingToShikshaInstituteBkp where (instituteId = 0 or locationId = 0) and courseId > 0";

		$query = $this->dbHandle->query($sqlReadCourses);
		$results = $query->result_array();

		return $results;	
	}

	function updateShikshaMappingForZeroInst($reviewId, $instituteId, $locId){
		$this->initiateModel('write');

		$updatesql = "UPDATE CollegeReview_MappingToShikshaInstituteBkp
			  SET instituteId=?, locationId=? where reviewId=?";
		$query = $this->dbHandle->query($query,array($instituteId,$locId,$reviewId));

		echo $updatesql;
	
	}

	/*function getZeroLoc(){
		$this->initiateModel('read');
		$sqlReadCourses = "select courseId, reviewId from CollegeReview_MappingToShikshaInstituteBkp where locationId = 0 and courseId > 0";

		$query = $this->dbHandle->query($sqlReadCourses);
		$results = $query->result_array();

		return $results;	
	}

	function updateShikshaMappingForZeroLoc($reviewId, $locId){
		$this->initiateModel('write');
		$updatesql = "UPDATE CollegeReview_MappingToShikshaInstituteBkp
			  SET locationId=? where reviewId=?";
		$query = $this->dbHandle->query($query,array($locId,$reviewId));
	
	}*/

	public function insertReviewForIndex($insertData){
		$this->initiateModel('write');

		$sql = "insert into indexlog (operation,listing_type,listing_id) VALUES (?,?,?)";
		$this->dbHandle->query($sql,array($insertData['operation'],$insertData['listing_type'],$insertData['listing_id']));
	}

	public function insertMultipleReviewsForIndex($insertData){
		if(empty($insertData)){
			return;
		}
		$this->initiateModel('write');
		$this->dbHandle->insert_batch('indexlog', $insertData);
	}

	public function insertBulkReviewForIndex($reviewIds){
		if(!is_array($reviewIds) || count($reviewIds) <=0){
			return false;
		}
		$sqlData = array();
		foreach ($reviewIds as $reviewId) {
			$sqlData[] = array(
				'operation' => 'index',
				'listing_type' => 'collegereview',
				'listing_id' => $reviewId
				);
		}
		$this->initiateModel('write');
		$this->dbHandle->insert_batch('indexlog',$sqlData);
		//echo $this->dbHandle->last_query();
	}

	public function insertIntoIndexingLog($data){
		//to write definition later		- Ajay
	}


	function getAllCRIds($collegeReviewType){
		$this->initiateModel();
		$this->dbHandle->select('id');
		$this->dbHandle->from('CollegeReview_MainTable crmt');
		if($collegeReviewType!='CA'){
			$this->dbHandle->where('crmt.reviewerId >','0');
		}else{
			$this->dbHandle->where('crmt.reviewerId','0');
		}
		
		$this->dbHandle->where_not_in('crmt.status', array('history' , 'deleted'));
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}


/*
	explain select crmt.id,crmt.reviewId , crmt.moderatorEmail from CollegeReview_ModerationTable crmt
left join CollegeReview_ModerationTable crmt1
    on crmt.reviewId = crmt1.reviewId and crmt.id <crmt1.id 
inner join tuser tu on tu.email = crmt.moderatorEmail
where crmt1.id is null and crmt.reviewId in (102522 , 104657,102522,103735)

*/
	function getLastModeratedBy($crIds){
		if(empty($crIds) || !is_array($crIds)){
			return array();
		}
		
		$this->initiateModel('write');
		$this->dbHandle->select('crmt.reviewId,tu.userId, crmt.moderationTime,crmt.moderatorEmail');
		$this->dbHandle->from('CollegeReview_ModerationTable crmt');
		
		$this->dbHandle->join('CollegeReview_ModerationTable crmt1','crmt.reviewId=crmt1.reviewId and crmt.id < crmt1.id','left');
		$this->dbHandle->join('tuser tu','tu.email = crmt.moderatorEmail','inner');
		$this->dbHandle->where('crmt1.id is null');
		$this->dbHandle->where_in('crmt.reviewId',$crIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->_compile_select();die;
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	function getCRDetailsForCA($crIds){
		$this->initiateModel('write');
		$selectFields = "
						distinct(crmt.id) as reviewId,	crmt.reviewQuality, crmt.creationDate,	crmt.modificationDate,
						crmt.moneyRating,	crmt.crowdCampusRating,	crmt.avgSalaryPlacementRating,
						crmt.campusFacilitiesRating, crmt.reviewTitle,	crmt.facultyRating, crmt.averageRating, crmt.qualityScore, 
						crmt.status, crmt.anonymousFlag, crmt.helpfulFlagCount,	crmt.notHelpfulFlagCount, 
						crmt.isShikshaInstitute, crmt.incentiveFlag, crmt.recommendCollegeFlag, crmt.reviewSource, 
						crmt.reason, crmt.placementDescription, crmt.facultyDescription, crmt.infraDescription, 
						crmt.reviewDescription, crmt.reviewerId, tu.firstname, tu.lastname, tu.email, tu.mobile,
						tu.isdCode,	capt.linkedInURL, crmt.userId,	capt.facebookURL, 
						crmtsi.reviewId as mappedReviewId, crmtsi.yearOfGraduation, crmtsi.instituteId, 
						crmtsi.locationId, crmtsi.courseId, lm.pack_type, scti.stream_id, scti.base_course, 
						crmtnsi.reviewId as notMappedReviewId, crmtnsi.yearOfGraduation as yearOfGraduation1, 
						crmtnsi.instituteName, crmtnsi.locationName, crmtnsi.courseName
						";

		$this->dbHandle->select($selectFields);
		$this->dbHandle->from('CollegeReview_MainTable crmt');
		$this->dbHandle->join('CA_ProfileTable capt','capt.userId = crmt.userId','inner');
		$this->dbHandle->join('tuser tu','tu.userId = crmt.userId','inner');
		$this->dbHandle->join('CollegeReview_MappingToShikshaInstitute crmtsi','crmt.id = crmtsi.reviewId','left');
		$this->dbHandle->join('CollegeReview_MappingToNonShikshaInstitute crmtnsi','crmt.id = crmtnsi.reviewId','left');
		$this->dbHandle->join('listings_main lm','lm.listing_type_id = crmtsi.courseId and lm.listing_type = "course" and lm.status= "live"','left');
		
		$this->dbHandle->join('shiksha_courses_type_information scti','scti.course_id = crmtsi.courseId and scti.primary_hierarchy = 1 and scti.status = "live"','left');
		$this->dbHandle->where_in('crmt.id',$crIds);
		
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->_compile_select();die;
		//echo $this->dbHandle->last_query();die;
		//array(51064 , 52126)
		return $result;
	}

	function getCRDetailsForNonCA($crIds){
		$this->initiateModel('write');
		$selectFields = "
						distinct(crmt.id) as reviewId,	crmt.reviewQuality, crmt.creationDate,	crmt.modificationDate,
						crmt.moneyRating,	crmt.crowdCampusRating,	crmt.avgSalaryPlacementRating,
						crmt.campusFacilitiesRating, crmt.reviewTitle,	crmt.facultyRating, crmt.averageRating, crmt.qualityScore, 
						crmt.status, crmt.anonymousFlag, crmt.helpfulFlagCount,	crmt.notHelpfulFlagCount, 
						crmt.isShikshaInstitute, crmt.incentiveFlag, crmt.recommendCollegeFlag, crmt.reviewSource, 
						crmt.reason, crmt.placementDescription, crmt.facultyDescription, crmt.infraDescription, 
						crmt.reviewDescription, crmt.reviewerId, crpi.firstname, crpi.lastname, crpi.email, crpi.mobile,
						crpi.isdCode,	crpi.linkedInURL, crmt.userId,	crpi.facebookURL, 
						crmtsi.reviewId as mappedReviewId, crmtsi.yearOfGraduation, crmtsi.instituteId, 
						crmtsi.locationId, crmtsi.courseId, lm.pack_type, scti.stream_id, scti.base_course, 
						crmtnsi.reviewId as notMappedReviewId, crmtnsi.yearOfGraduation as yearOfGraduation1, 
						crmtnsi.instituteName, crmtnsi.locationName, crmtnsi.courseName
						";

		$this->dbHandle->select($selectFields);
		$this->dbHandle->from('CollegeReview_MainTable crmt');
		$this->dbHandle->join('CollegeReview_PersonalInformation crpi','crmt.reviewerId = crpi.id','inner');
		
		$this->dbHandle->join('CollegeReview_MappingToShikshaInstitute crmtsi','crmt.id = crmtsi.reviewId','left');
		$this->dbHandle->join('CollegeReview_MappingToNonShikshaInstitute crmtnsi','crmt.id = crmtnsi.reviewId','left');
		$this->dbHandle->join('listings_main lm','lm.listing_type_id = crmtsi.courseId and lm.listing_type = "course" and lm.status= "live"','left');
		
		$this->dbHandle->join('shiksha_courses_type_information scti','scti.course_id = crmtsi.courseId and scti.primary_hierarchy = 1 and scti.status = "live"','left');

		$this->dbHandle->where_in('crmt.id',$crIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->_compile_select();die;
		//echo $this->dbHandle->last_query();die;
		//array(51064 , 52126)
		return $result;
	}	

	function getCRDetails($crIds, $collegeReviewType){

		if(empty($crIds) || !is_array($crIds)){
			return array();
		}

		if($collegeReviewType=='CA'){
			return $this->getCRDetailsForCA($crIds);
		}else{
			return $this->getCRDetailsForNonCA($crIds);
		}
		
	}

	public function getRatingParams($crIds){
		if(!is_array($crIds) || (is_array($crIds) && count($crIds) <= 0)){
			return array();
		}

		$this->initiateModel('write');
		$this->dbHandle->select('map.reviewId, map.rating,crMaster.id as ratingId ');
		$this->dbHandle->from('CollegeReview_RatingMapping map');
		$this->dbHandle->join('CollegeReview_MasterTable crMaster','crMaster.id = map.masterRatingId','inner');
		$this->dbHandle->where('crMaster.status','live');
		$this->dbHandle->where('crMaster.type','rating');
		$this->dbHandle->where('map.status','live');
		$this->dbHandle->where_in('map.reviewId',$crIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		//_p($result);die;
		return $result;
	}



	function getReviewCountForMobileNo($mobileNo , $isdCode){
		if($mobileNo > 0 && $isdCode > 0){
			$this->initiateModel('read');
			$this->dbHandle->select('count(crmt.id) reviewCount');
			$this->dbHandle->from('CollegeReview_PersonalInformation crpi');
			$this->dbHandle->join('CollegeReview_MainTable crmt','crpi.id = crmt.reviewerId','inner');
			$this->dbHandle->where('crpi.mobile',$mobileNo);
			$this->dbHandle->where('crpi.isdCode',$isdCode);
			$result = $this->dbHandle->get()->result_array();
			//_p($result[0]['reviewCount']);die;
			//echo $this->dbHandle->last_query();
			return $result[0]['reviewCount'];
		}else{
			return 0;
		}
	}

	function getCountryIdFromIsdCode($isdCode = 0){
		if($isdCode > 0){
			$this->initiateModel('read');
			$this->dbHandle->select('shiksha_countryId');
			$this->dbHandle->from('isdCodeCountryMapping');
			$this->dbHandle->where('status',"live");
			$this->dbHandle->where('isdCode',$isdCode);
			$result = $this->dbHandle->get()->result_array();
			//_p($result[0]['shiksha_countryId']);die;
			//echo $this->dbHandle->last_query();die;
			return $result[0]['shiksha_countryId'];
		}else{
			return 2;
		}
	}

	function getAllReviewIdsForCourse($courseIds){
		if(count($courseIds) > 0){
			$this->initiateModel('read');
			$this->dbHandle->select('reviewId');
			$this->dbHandle->from('CollegeReview_MappingToShikshaInstitute');
			$this->dbHandle->where_in('courseId',$courseIds);
			$result = $this->dbHandle->get()->result_array();
			//echo $this->dbHandle->last_query();
			return $result;
		}
	}

	function updateReviewsStatus($reviewIds, $data){
		if(count($reviewIds) > 0){
			$this->initiateModel('write');
			$this->dbHandle->where_in('id',$reviewIds);
			$this->dbHandle->update('CollegeReview_MainTable',$data);
			//echo $this->dbHandle->last_query();
		}
	}

	//get reviews data like Placements/Infrastructure/Faculty/Other Details/Title of Review posted in last 5 mints for auto moderation
	function getReviewForAutoModeration(){
		$this->initiateModel('read');
		$query = "select id as reviewId, reviewDescription ,placementDescription, infraDescription, facultyDescription, reviewTitle from CollegeReview_MainTable where status ='draft' and modificationDate>=DATE_SUB(NOW(),INTERVAL 300 SECOND)";
		$result = $this->dbHandle->query($query)->result_array();
		return $result;
	}

	function updateDataByAutoModeration($reviewId, $data){
		if(empty($reviewId) || empty($data)){
			return;
		}
		$this->initiateModel('write');
		$this->dbHandle->where('id',$reviewId);
		$this->dbHandle->update('CollegeReview_MainTable',$data);
	}

	public function filterReviewsForCourse($reviewIds, $courseId){
		if($courseId <=0 || !is_array($reviewIds) || count($reviewIds)<=0){
			return false;
		}
		$this->initiateModel('read');
		$this->dbHandle->select('reviewId');
		$this->dbHandle->from('CollegeReview_MappingToShikshaInstitute');
		$this->dbHandle->where('courseId',$courseId);
		$this->dbHandle->where_in('reviewId',$reviewIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function migrateReviewsToNewCourse($reviewIds, $newCourseId){
		if($newCourseId <=0 || !is_array($reviewIds) || count($reviewIds) <=0){
			return false;
		}
		$this->initiateModel('write');
		$this->dbHandle->where_in('reviewId',$reviewIds);
		$data = array('courseId'=> $newCourseId);
		$this->dbHandle->update('CollegeReview_MappingToShikshaInstitute',$data);
		//echo $this->dbHandle->last_query();
	}

	public function getCollegeReviewData($reviewId){
		if($reviewId<1){
			return;
		}

		$this->initiateModel('write');

		$this->dbHandle->select('crmt.id as reviewId, reviewDescription,placementDescription, infraDescription,facultyDescription, incentiveFlag, reviewTitle, reviewerId, crPInfo.firstname, crPInfo.email, crPInfo.mobile, crInst.courseId');
		$this->dbHandle->from('CollegeReview_MainTable crmt');
		$this->dbHandle->join('CollegeReview_PersonalInformation crPInfo','crmt.reviewerId = crPInfo.id','inner');
		$this->dbHandle->join('CollegeReview_MappingToShikshaInstitute crInst','crmt.id = crInst.reviewId','inner');
		$this->dbHandle->where('crmt.id',$reviewId);
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}
        
	public function updateReviewScoreAndStatus($reviewId, $score, $status){
		if($reviewId<1 || $score<0){
			return;
		}

		$subQuery = '';
		if($status!=''){
			$subQuery = " , status='rejected', reason=5";
		}

		$this->initiateModel('write');

		$sql ="update CollegeReview_MainTable set qualityScore = ? $subQuery where id = ? ";
		$this->dbHandle->query($sql,array($score, $reviewId));


		return $result;
	}

	public function getPublishedReviewIdsForCourses($courseIds){
		if(empty($courseIds)){
			return;
		}
		$this->initiateModel('read');
		$sql = "SELECT distinct CMS.reviewId,CMS.courseId FROM CollegeReview_MappingToShikshaInstitute CMS join CollegeReview_MainTable CM on CMS.reviewId = CM.id  AND CM.status = 'published' WHERE CMS.courseId IN (?)";
		return $this->dbHandle->query($sql,array($courseIds))->result_array();
	}

	public function getReviewTopicTagsName($tagIds,$topicName){
		
		if(empty($tagIds)){
			return;
		}
		$this->initiateModel('read');
		$sql = "Select distinct topic_id , topic_name from reviews_topics where topic_id IN (?) and status = 'live' and section_name = ?";
		$data = $this->dbHandle->query($sql,array($tagIds,$topicName))->result_array();
		$returnData=array();
		foreach ($data as $value) {
			$returnData[$value['topic_id']] = $value['topic_name'];
		}
		return $returnData;
	}

	public function getPlacementDatafromTag($reviewIds,$selectedTagId,$type= 'placements'){
		if(empty($reviewIds)){
			return;
		}
		$this->initiateModel('read');

		$this->dbHandle->select('review_id');
		$this->dbHandle->select('highlighted_review');
		$this->dbHandle->from('reviews_highlighted');
		$this->dbHandle->where_in('review_id',$reviewIds);
		$this->dbHandle->where('topic_id',$selectedTagId);
		$this->dbHandle->where_in('section_name',$type);
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	function checkReviewType($reviewId){
		$this->initiateModel('read');
		$this->dbHandle->select('reviewerId');
		$this->dbHandle->from('CollegeReview_MainTable');
		$this->dbHandle->where('id',$reviewId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		if($result[0]['reviewerId']==0){
			return 'CA';
		}
		return '';
	}
}
