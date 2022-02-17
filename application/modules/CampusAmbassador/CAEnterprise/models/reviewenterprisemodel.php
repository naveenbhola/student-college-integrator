<?php
class reviewenterprisemodel extends MY_Model
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
	
	function getCollegeReviewData($data, $reviewFilterPostfix=''){

		$this->initiateModel('read');
		if($data['email'] == ''){
			$postfixFilter = '';
			if($reviewFilterPostfix !== ''){
				$postfixFilter = " AND ( ";
				$filter = array();
				foreach($reviewFilterPostfix as $k=>$v){
					$filter[] = "crmt.id LIKE '%$v'";
				}

				$filter = implode(" OR ", $filter);
				
				$postfixFilter .= $filter." )";
			}

			//$queryStatus = "And crmt.status IN ('draft','accepted','rejected','published')";
			
			$queryStatus = "And crmt.status IN ('draft') $postfixFilter";

			$queryinstituteId = $querysortReviews ='';
			if( isset($data['sortReviews']) && $data['sortReviews']=='Old first'){
				$querysortReviews ="main_reviewid";	
			}else{
				$querysortReviews ="main_reviewid DESC";
			}

			$queryStream = '';
			if($data['categoryFilter'] !='' && $data['categoryFilter'] != 'All'){
				$queryStream = "and scti.stream_id = ".$data['categoryFilter'];
			}


			if($data['statusFilter'] !='Pending' ){
				$moderator_list = $data['useremail'];

				if(count($data['moderator_list'])>0 && is_array($data['moderator_list']) ){
					$moderator_list = implode("','", $data['moderator_list']);	
				}

				if( !is_array($data['moderator_list']) && !($data['moderator_list']) && $data['showModeratedByFilter']){
					$moderationJoin  = " left join CollegeReview_ModerationTable crmdt on (crmdt.reviewId = crmt.id) ";
					
				}else{
					 $moderationJoin  = " left join CollegeReview_ModerationTable crmdt on (crmdt.reviewId = crmt.id and crmdt.moderatorEmail in ('".$moderator_list."'))";
				}


				$moderationTable = " ,CollegeReview_ModerationTable crmdt";
				$moderationWhere = " AND crmdt.reviewId = crmt.id and crmdt.moderatorEmail in ('".$moderator_list."')";

				//$queryModeratorEmailFilter .= " and crmdt.moderatorEmail in ('".$moderator_list."')";

			}

			if(isset($data['statusFilter']) && $data['statusFilter']!='Pending'){

				if($data['statusFilter']=='All'){
					$reviewStatus = "'accepted','rejected','published','later','unverified'";				

				
					$queryStatus = " and (crmt.status IN(".$reviewStatus.") and  crmdt.actionType IN(".$reviewStatus.") ) or (crmt.status= 'draft'  $postfixFilter)";

					
				}else if($data['statusFilter']=='Unpublished'){
				
					$reviewStatus = 'accepted';
					$queryStatus = " and (crmt.status = '$reviewStatus' and  crmdt.actionType IN('".$reviewStatus."') )";
				
				}else if($data['statusFilter'] == 'Later' || $data['statusFilter'] == 'Rejected'){
				
					$reviewStatus = strtolower($data['statusFilter']);
					if($data['reasonFilter'] == 'All'){
						$queryStatus = " and (crmt.status = '$reviewStatus' and  crmdt.actionType IN('".$reviewStatus."'))";
					}else{
						$reasonStatus = $data['reasonFilter'];
						$queryStatus = " and (crmt.status = '$reviewStatus' and crmt.reason = '$reasonStatus' and  crmdt.actionType IN('".$reviewStatus."') )";
					}
				}else{
					$reviewStatus = strtolower($data['statusFilter']);

					$queryStatus = " and (crmt.status = '$reviewStatus' and  crmdt.actionType IN('".$reviewStatus."'))";
				}
			
			}
		
			if( isset($data['instituteName']) && $data['instituteName']!=''){
				$instituteName = $data['instituteName'];
				$queryinstituteId = " and crmtsi.instituteId = '$instituteName' ";
				
			}

			if(isset($data['sourceFilter']) && $data['sourceFilter'] != 'All'){
				$querySourceFilter = ' and (crmt.reviewSource like "%utm_source='.$data['sourceFilter'].'&%" 
										or crmt.reviewSource = "utm_source='.$data['sourceFilter'].'")';
			}else{
				$querySourceFilter = '';
			}

			// new filters start
			$queryPostedDateFilter = '';
			if(isset($data['posted_timeRange'])){

				if($data['posted_timeRange']['From'] != ''){
					$data['posted_timeRange']['From'] = $this->changeDateFormat($data['posted_timeRange']['From']);
					$queryPostedDateFilter .= " and crmt.creationDate >= '".$data['posted_timeRange']['From']." 00:00:00' ";
				}
				if($data['posted_timeRange']['To'] != ''){
					$data['posted_timeRange']['To'] = $this->changeDateFormat($data['posted_timeRange']['To']);
					$queryPostedDateFilter .= " and crmt.creationDate <= '".$data['posted_timeRange']['To']." 23:59:59' ";
				}

			}

			$moderationTableQuery      = '';
			$queryModeratedDateFilter  = '';
			//$queryModeratorEmailFilter = ''; 

			if( (($data['moderated_timeRange']['From'] != '' || $data['moderated_timeRange']['To'] != ''))  ) {

				if($moderationJoin == '' && $moderationTable =='' ){
					$moderationTableQuery .= " join CollegeReview_ModerationTable crmdt on (crmt.id=crmdt.reviewId) ";
				}

				if($data['moderated_timeRange']['From'] != ''){
					$data['moderated_timeRange']['From'] = $this->changeDateFormat($data['moderated_timeRange']['From']);
					$queryModeratedDateFilter .= " and crmdt.moderationTime >= '".$data['moderated_timeRange']['From']." 00:00:00' ";
				}
				if($data['moderated_timeRange']['To'] != ''){
					$data['moderated_timeRange']['To'] = $this->changeDateFormat($data['moderated_timeRange']['To']);
					$queryModeratedDateFilter .= " and crmdt.moderationTime <= '".$data['moderated_timeRange']['To']." 23:59:59' ";
				}

			}

			$queryUserPhoneFilter = '';
			if(isset($data['phone_search']) && $data['phone_search'] != ''){
				$queryUserPhoneFilter .= " and crpi.mobile = ".$data['phone_search']." ";
			}

			// new filters end

			$start = isset($data['start'])?$data['start']:0;
			$startUnmapped = isset($data['startUnmapped'])?$data['startUnmapped']:0;
			$count = isset($data['count'])?$data['count']:10;

			if(!($count > 0)){
				$count = 10;
			}

			if(!($start > 0)){
				$start = 0;
			}
			

			$postfixFilter='';		

            if((isset($data['typeFilter']) && $data['typeFilter']=='Mapped-colleges') || $data['categoryFilter'] != 'All'){

                $sql = "select SQL_CALC_FOUND_ROWS distinct
                		crmt.*,crmtsi.*,crpi.*,crmt.id as main_reviewid,
                		CASE listing.pack_type WHEN ('".GOLD_SL_LISTINGS_BASE_PRODUCT_ID."') 
                		THEN '1' WHEN ('".SILVER_LISTINGS_BASE_PRODUCT_ID."') THEN '1' 
                		WHEN ('".GOLD_ML_LISTINGS_BASE_PRODUCT_ID."') THEN '1' 
                		ELSE '0' END as paidStatus from CollegeReview_MainTable crmt
                		$moderationTableQuery,
                		CollegeReview_MappingToShikshaInstitute crmtsi, 
                		CollegeReview_PersonalInformation crpi, listings_main listing,
                		shiksha_courses_type_information scti
                		$moderationTable
		        		where crmt.id = crmtsi.reviewId
						And crmtsi.courseId = listing.listing_type_id
						And listing.listing_type = 'course'
						And listing.status = 'live'
						And scti.course_id = crmtsi.courseId
						And scti.status = 'live'
						And scti.primary_hierarchy = 1
						$queryStatus
						$queryUserPhoneFilter
						$queryModeratedDateFilter
						$moderationWhere
						And crmt.reviewerid = crpi.id 
						$queryStream
						And crmt.userId =0
						$postfixFilter
						$querySourceFilter
						$queryPostedDateFilter
						And crmtsi.instituteId>0
						And crmtsi.courseId>0
						$queryinstituteId 
						ORDER BY paidStatus desc, $querysortReviews limit $start,$count" ;

						$query = $this->dbHandle->query($sql);
						$data = $query->result_object();
						$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
						$queryTotal = $this->dbHandle->query($queryCmdTotal);
						$queryResults = $queryTotal->result();
						$totalRows = $queryResults[0]->totalRows;
						$data['startUnmapped'] = $startUnmapped;
						$data['num_rows'] = $totalRows;

            }else if(isset($data['typeFilter']) && $data['typeFilter']=='UnMapped-colleges'){

				if(isset($data['instituteName']) && $data['instituteName'] != ''){

					$sql = "select SQL_CALC_FOUND_ROWS distinct crmt.*,crmtsi.*,crpi.*,
							crmt.id as main_reviewid, CASE listing.pack_type WHEN 
							('".GOLD_SL_LISTINGS_BASE_PRODUCT_ID."') THEN '1' WHEN 
							('".SILVER_LISTINGS_BASE_PRODUCT_ID."') THEN '1' WHEN 
							('".GOLD_ML_LISTINGS_BASE_PRODUCT_ID."') THEN '1' ELSE 
							'0' END as paidStatus from CollegeReview_MainTable crmt
							$moderationTableQuery,
							CollegeReview_MappingToShikshaInstitute crmtsi, 
							CollegeReview_PersonalInformation crpi, 
							listings_main listing
							$moderationTable
		        			where crmt.id = crmtsi.reviewId
							And crmtsi.courseId = listing.listing_type_id
							And listing.listing_type = 'course'
							And listing.status = 'live'
							$queryStatus
							$queryUserPhoneFilter
							$queryModeratedDateFilter
							$moderationWhere
							And crmt.reviewerid = crpi.id 
							And crmt.userId =0
							$postfixFilter
							$querySourceFilter
							$queryPostedDateFilter
							And crmtsi.instituteId>0
							And crmtsi.courseId>0
							And crmtsi.locationId>0
							$queryinstituteId ORDER BY paidStatus desc, $querysortReviews limit $start,$count" ;
				}else{

					$sql = "select SQL_CALC_FOUND_ROWS distinct crmt.*,
							crmtsni.*,crpi.*,
							crmt.id as main_reviewid
							from CollegeReview_MainTable crmt
							$moderationTableQuery,
							CollegeReview_MappingToNonShikshaInstitute crmtsni,
							CollegeReview_PersonalInformation crpi 
							$moderationTable
		        			where crmt.id = crmtsni.reviewId
							$queryStatus 
							$queryUserPhoneFilter
							$queryModeratedDateFilter
							$moderationWhere
							And crmt.reviewerid = crpi.id
							$postfixFilter
							$querySourceFilter
							$queryPostedDateFilter
							And crmt.userId =0 ORDER BY $querysortReviews limit $start,$count" ;
				}


				$query = $this->dbHandle->query($sql);
				$data = $query->result_object();
				$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
				$queryTotal = $this->dbHandle->query($queryCmdTotal);
				$queryResults = $queryTotal->result();
				$totalRows = $queryResults[0]->totalRows;
				$data['startUnmapped'] = $startUnmapped;
				$data['num_rows'] = $totalRows;


        	}else{
				if(isset($data['instituteName']) && $data['instituteName'] != ''){

					$sql = "select SQL_CALC_FOUND_ROWS distinct crmt.*,crmtsi.*,crpi.*,
							crmt.id as main_reviewid, CASE listing.pack_type WHEN 
							('".GOLD_SL_LISTINGS_BASE_PRODUCT_ID."') THEN '1' WHEN 
							('".SILVER_LISTINGS_BASE_PRODUCT_ID."') THEN '1' WHEN 
							('".GOLD_ML_LISTINGS_BASE_PRODUCT_ID."') THEN '1' 
							ELSE '0' END as paidStatus from CollegeReview_MainTable crmt
							$moderationTableQuery,
							CollegeReview_MappingToShikshaInstitute crmtsi,
							CollegeReview_PersonalInformation crpi,
							shiksha_courses_type_information scti,
							listings_main listing
							$moderationTable
			        		where crmt.id = crmtsi.reviewId
							And crmtsi.courseId = listing.listing_type_id
							And listing.listing_type = 'course'
							And listing.status = 'live'
							And scti.course_id = crmtsi.courseId
							And scti.status = 'live'
							And scti.primary_hierarchy = 1
							$queryStatus
							$queryUserPhoneFilter
							$queryModeratedDateFilter
							$moderationWhere
							And crmt.reviewerid = crpi.id 
							$queryStream
							And crmt.userId =0
							$postfixFilter
							$querySourceFilter
							$queryPostedDateFilter
							And crmtsi.instituteId>0
							And crmtsi.courseId>0
							And crmtsi.locationId>0
							$queryinstituteId ORDER BY paidStatus desc, $querysortReviews limit $start,$count" ;

							$query = $this->dbHandle->query($sql);
							$data = $query->result_object();
							$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
							$queryTotal = $this->dbHandle->query($queryCmdTotal);
							$queryResults = $queryTotal->result();
							$totalRows = $queryResults[0]->totalRows;
							$data['startUnmapped'] = $startUnmapped;
							$data['num_rows'] = $totalRows;
				
				}else{

					$mappedCount = 0;
					$data1 = array();
						$sql1 = "select SQL_CALC_FOUND_ROWS distinct crmt.*, crmtsi.*, crpi.*, crmt.id as main_reviewid, 
								CASE listing.pack_type WHEN ('".GOLD_SL_LISTINGS_BASE_PRODUCT_ID."') 
								THEN '1' WHEN ('".SILVER_LISTINGS_BASE_PRODUCT_ID."') 
								THEN '1' WHEN ('".GOLD_ML_LISTINGS_BASE_PRODUCT_ID."')
								THEN '1' ELSE '0' END as paidStatus 
								from CollegeReview_MainTable crmt join 
								CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id=crmtsi.reviewId) 
								join CollegeReview_PersonalInformation crpi on (crmt.reviewerId = crpi.id)
								join listings_main listing on (crmtsi.courseId = listing.listing_type_id)
								join shiksha_courses_type_information scti on (crmtsi.courseId = scti.course_id)
								$moderationTableQuery
								$moderationJoin
				  				where crmt.userId=0 
				  				And crmtsi.instituteId>0 
				  				And crmtsi.courseId>0 
				  				And listing.listing_type = 'course'
				  				And listing.status = 'live'
				  				And scti.primary_hierarchy = 1
				  				$queryStream
				  				$queryModeratedDateFilter
				  				And scti.status = 'live'
				  				$postfixFilter
				  				$querySourceFilter
				  				$queryPostedDateFilter
				  				And crmtsi.locationId>0
				  				$queryStatus
				  				$queryUserPhoneFilter
				  				$queryinstituteId ORDER BY paidStatus desc, $querysortReviews limit $start,$count";

				  		$query1 = $this->dbHandle->query($sql1);
						$data1 = $query1->result_object();
						$queryCmdTotal1 = 'SELECT FOUND_ROWS() as totalRows';
						$queryTotal1 = $this->dbHandle->query($queryCmdTotal1);
						$queryResults1 = $queryTotal1->result();
						$totalRows1 = $queryResults1[0]->totalRows;
						$mappedCount = count($data1);
						$data1['num_rows'] = $totalRows1;
					

			  		if($mappedCount < $count){

			  			$count = $count-$mappedCount;

			  			$start = $startUnmapped;
			  			$data['startUnmapped'] = $start;

			  			if(!($count > 0)){
			  				$count = 10;
			  			}
				  		$sql2 = "select SQL_CALC_FOUND_ROWS distinct crmt.*, crmtsni.*, crpi.*,crmt.id as main_reviewid, 
				  				'0' as paidStatus from CollegeReview_MainTable crmt 
				  				join CollegeReview_MappingToNonShikshaInstitute crmtsni on (crmt.id=crmtsni.reviewId) 
				  				join CollegeReview_PersonalInformation crpi on (crmt.reviewerId = crpi.id)
								$moderationTableQuery
				  				$moderationJoin
								where crmt.userId=0
								$postfixFilter
								$querySourceFilter
								$queryModeratedDateFilter
								$queryPostedDateFilter
								$queryStatus $queryinstituteId 
								$queryUserPhoneFilter
								ORDER BY paidStatus desc, $querysortReviews limit $start,$count";
						
						$query2 = $this->dbHandle->query($sql2);
						$data2 = $query2->result_object();
						$queryCmdTotal2 = 'SELECT FOUND_ROWS() as totalRows';
						$queryTotal2 = $this->dbHandle->query($queryCmdTotal2);
						$queryResults2 = $queryTotal2->result();
						$totalRows2 = $queryResults2[0]->totalRows;
						$data2['num_rows'] = $totalRows2;
						$data = array_merge($data1,$data2);
						$data['startUnmapped'] = $start+$count;
						$data['num_rows'] = $data1['num_rows'] + $data2['num_rows'];
					}else{
						$sql3 = "select count(crmt.id) as count from CollegeReview_MainTable crmt 
				  				join CollegeReview_MappingToNonShikshaInstitute crmtsni on (crmt.id=crmtsni.reviewId)
				  				$moderationTableQuery
								$moderationJoin
				  				where crmt.userId=0
								$postfixFilter
								$querySourceFilter
								$queryModeratedDateFilter
								$queryPostedDateFilter
								$queryUserPhoneFilter
								$queryStatus $queryinstituteId ";
						$query3 = $this->dbHandle->query($sql3);
						$data3 = $query3->result_array();
						$data = $data1;
						$data['startUnmapped'] = $startUnmapped;
						$data['num_rows'] = $data1['num_rows'] + $data3[0]['count'];
					}

				}
			
	        }
		 
		} else{ 
			
			$email = $data['email'];
			$statusFilterForQuery = $data['statusFilter'];
			$sortFilterForQuery = $data['sortReviews'];

			$data = $this->searchReviewByEmail($email,$statusFilterForQuery,$sortFilterForQuery);
			
		}

	 return $data;

}

/**
 * Function to search reviews by email id
 * @param string $email email id of the user
 * @return array reviews, other details and number of rows
 */
private function searchReviewByEmail($email,$statusFilterForQuery,$sortFilterForQuery){

	$status = "";
	$queryStatus = "";

	
	
	if( isset($sortFilterForQuery) && $sortFilterForQuery=='Old first'){
		$querysortReviews ="main_reviewid";	
	}else{
		$querysortReviews ="main_reviewid DESC";
	}
	
	$postfixFilter = "AND crpi.`email` = '$email'";
	
	$start1  = microtime(true);
	$sql1 = "SELECT SQL_CALC_FOUND_ROWS distinct crpi . * , crmt . * , crmnsi . *, crmt.id as main_reviewid"
		." FROM `CollegeReview_PersonalInformation` crpi, `CollegeReview_MainTable`"
		." crmt, `CollegeReview_MappingToNonShikshaInstitute` crmnsi "
		." WHERE crmt.`reviewerId` = crpi.`id`"
		." $postfixFilter"
		." AND crmnsi.`reviewId` = crmt.`id` $queryStatus order by $querysortReviews";
	$query1 = $this->dbHandle->query($sql1);
	$data1 = $query1->result();
	$end1  = microtime(true);
	error_log('Query to get unmapped reviews : '.$sql1);
	error_log('time taken to run query unmapped reviews'.($end1-$start1));
	$queryCmdTotal1 = 'SELECT FOUND_ROWS() as totalRows';
	$queryTotal1 = $this->dbHandle->query($queryCmdTotal1);
	$queryResults1 = $queryTotal1->result();
	$totalRows1 = $queryResults1[0]->totalRows;
	$data1['num_rows'] = $totalRows1;
	
	$start2  = microtime(true);
	$sql2 = "SELECT SQL_CALC_FOUND_ROWS distinct crpi . * , crmt . * , crmsi . *,   crmt.id as main_reviewid"
		." FROM `CollegeReview_PersonalInformation` crpi, `CollegeReview_MainTable` crmt,"
		." `CollegeReview_MappingToShikshaInstitute` crmsi, shiksha_courses sc"		
		." WHERE crmt.`reviewerId` = crpi.`id`"
		." AND crmsi.`courseId` = sc.`course_id` AND sc.status = 'live' "
		." $postfixFilter"
		." And crmsi.instituteId>0 And crmsi.courseId>0 "
		." AND crmsi.`reviewId` = crmt.`id` $queryStatus order by $querysortReviews";
	$query2 = $this->dbHandle->query($sql2);
	$data2 = $query2->result();
	$end2  = microtime(true);
	error_log('Query to get mapped reviews : '.$sql2);
	error_log('time taken to run query mapped reviews'.($end2-$start2));
	$queryCmdTotal2 = 'SELECT FOUND_ROWS() as totalRows';
	$queryTotal2 = $this->dbHandle->query($queryCmdTotal2);
	$queryResults2 = $queryTotal2->result();
	$totalRows2 = $queryResults2[0]->totalRows;
	$data2['num_rows'] = $totalRows2;
	
	$data = array_merge($data1,$data2);
	
	$data['num_rows'] = $data1['num_rows'] + $data2['num_rows'];
	
	return $data;
}


function getCategoryIdForCourse($courseIds){
	$this->initiateModel('read');
	// $sql="select cbt.parentId ,cp.course_id from categoryBoardTable cbt,categoryPageData cp  where boardId in (select category_id from categoryPageData cpd where course_id in($courseIds) and status='live') and cp.course_id in ($courseIds) and cbt.boardId = cp.category_id and cp.status='live' group By cp.course_id , cp.category_id ";

	$sql="select stream_id, course_id from shiksha_courses_type_information
			where course_id in ($courseIds) and primary_hierarchy = '1' and status = 'live'";


	$query = $this->dbHandle->query($sql);
	$data = $query->result_array();
	$result = array();
	foreach($data as $key=>$val)
	{
		$result[$val['course_id']] = $val['stream_id'];
	}
	return $result;
}


	function totalAwaitingReviews($reviewFilterPostfix=''){
		$this->initiateModel('write');
		$filter = '';
		if($reviewFilterPostfix !== '')
		{
			$filter = " AND ( ";
			$filter1 = array();
			foreach($reviewFilterPostfix as $k=>$v){
				$filter1[] = "crmt.id LIKE '%$v'";
			}

			$filter1 = implode("OR ", $filter1);
			
			$filter .= $filter1." )";
		}

		$sql= "select temp.id, count(*) as reviewcount from (select crmt.id from CollegeReview_MainTable crmt join CollegeReview_MappingToShikshaInstitute crmtsi on (crmt.id=crmtsi.reviewId)
		  where crmt.userId = 0
		  And crmtsi.instituteId>0
		  And crmtsi.courseId>0
		  And crmtsi.locationId>0
		  And crmt.status = 'draft'
		  $filter
		  
		  UNION
		  select crmt.id from CollegeReview_MainTable crmt join CollegeReview_MappingToNonShikshaInstitute crmtsni on (crmt.id=crmtsni.reviewId)
		  where crmt.userId = 0
		  $filter
		  And crmt.status = 'draft' ) as temp";
		$query = $this->dbHandle->query($sql);
		$data = $query->result_object();
		return $data;
	}

	function getTotalReviewsInstituteAndStatusWise(){
                $this->initiateModel('read');
                $sql="select count(crmt.id) as total, crmt.status, crmtsi.instituteId  from CollegeReview_MainTable crmt, CollegeReview_MappingToShikshaInstitute crmtsi
                where crmt.id = crmtsi.reviewId
                And crmt.status IN ('draft','published','rejected') 
                And crmt.userId =0
                And crmtsi.instituteId>0
                And crmtsi.courseId>0
                And crmtsi.locationId>0 Group BY crmtsi.instituteId, crmt.status";
                $query = $this->dbHandle->query($sql);
                $data = $query->result_array();
		$finalArray = array();
		foreach ($data as $row){
			$instituteId = $row['instituteId'];
			$status = $row['status'];
			switch ($status){
				case 'draft': $finalArray[$instituteId]['totalPending'] = $row['total']; break;
                                case 'published': $finalArray[$instituteId]['totalPublished'] = $row['total']; break;
                                case 'rejected': $finalArray[$instituteId]['totalIgnored'] = $row['total']; break;
			}
		}
                return $finalArray;
	}	
	
	function getTotalPendingReviewsInstituteWise(){
		$this->initiateModel('read');
		$sql="select count(crmt.id) as totalPendingInstituteCount,crmtsi.instituteId  from CollegeReview_MainTable crmt, CollegeReview_MappingToShikshaInstitute crmtsi 
		where crmt.id = crmtsi.reviewId
		And crmt.status='draft'
		And crmt.userId =0
		And crmtsi.instituteId>0
		And crmtsi.courseId>0
		And crmtsi.locationId>0 Group BY crmtsi.instituteId";
		$query = $this->dbHandle->query($sql);	
		$data = $query->result_object();
		return $data;
		
	}
	
	function getTotalIgnoredReviewsInstituteWise(){
		$this->initiateModel('read');
		$sql="select count(crmt.id) as totalIgnoredInstituteCount,crmtsi.instituteId  from CollegeReview_MainTable crmt, CollegeReview_MappingToShikshaInstitute crmtsi 
		where  crmt.id = crmtsi.reviewId
		And crmt.status='rejected'
		And crmt.userId =0
		And crmtsi.instituteId>0
		And crmtsi.courseId>0
		And crmtsi.locationId>0 Group BY crmtsi.instituteId";
		$query = $this->dbHandle->query($sql);	
		$data = $query->result_object();
		return $data;
		
	}
	
	function getTotalPublishedReviewsInstituteWise(){
		$this->initiateModel('read');
		$sql="select count(crmt.id) as totalPublishedInstituteCount,crmtsi.instituteId  from CollegeReview_MainTable crmt, CollegeReview_MappingToShikshaInstitute crmtsi 
		where crmt.id = crmtsi.reviewId
		And crmt.status='published'
		And crmt.userId =0
		And crmtsi.instituteId>0
		And crmtsi.courseId>0
		And crmtsi.locationId>0 Group BY crmtsi.instituteId";
		$query = $this->dbHandle->query($sql);	
		$data = $query->result_object();
		return $data;
		
	}
	
	
	function editReviewByModerator($reviewId,$reviewTxt,$reviewType)
	{
		$this->initiateModel('write');
		if($reviewType == "other"){
			$reviewType = "review";
		}
		$query = "UPDATE CollegeReview_MainTable
			  SET ".$reviewType."Description=? where id = ?";
		return $this->dbHandle->query($query,array($reviewTxt, $reviewId));
	 }
	 
	function updateReviewStatus($status,$reviewId,$reason = ''){

		$this->initiateModel('write');
		$query = "UPDATE CollegeReview_MainTable
			  SET status=?, reason=? where id=?";
		$query = $this->dbHandle->query($query,array($status,$reason,$reviewId));	
		
				
	}
	
	function getReviewDetails($id)
	{
		$this->initiateModel('write');
		$query = "select * from CollegeReview_MainTable as mt left join CollegeReview_PersonalInformation as pi on mt.reviewerId=pi.id where mt.id = ?";
		$res = $this->dbHandle->query($query,array($id));
		return $res->result_array();
	}
	
	function getCRDetailsForReview($id)
	{
		$this->initiateModel('read');
		$query = "select * from CollegeReview_MainTable as mt join tuser as u on mt.userId=u.userId where mt.id = ?";
		$res = $this->dbHandle->query($query,array($id));
		return $res->result_array();
	}
	
	
	function updatecourseIdForNonMappedColleges($reviewId,$instituteId,$locationId,$courseId,$yearOfGraduation){
		$this->initiateModel('write');
		$queryCmd = "INSERT INTO CollegeReview_MappingToShikshaInstitute (reviewId, instituteId, locationId, courseId, yearOfGraduation) VALUES (?,?,?,?,?)";
		$query = $this->dbHandle->query($queryCmd,array($reviewId,$instituteId,$locationId,$courseId,$yearOfGraduation));
		
	}
	
	function deleteEntryForNonMappedColleges($reviewId){
		$this->initiateModel('write');
		$sql = "DELETE FROM CollegeReview_MappingToNonShikshaInstitute where reviewId=?";
		$query = $this->dbHandle->query($sql,array($reviewId));
		
	}
	
	function updateIsShikshaFlag($reviewId){
		$this->initiateModel('write');
		$sql = "UPDATE CollegeReview_MainTable
			SET isShikshaInstitute='YES' where userId=0 and id=?";
		$query = $this->dbHandle->query($sql,array($reviewId));
		
	}
	
	function getReviewsCouresId($reviewId)
	{
		$this->initiateModel('read');
		$results = array();
		$query = "SELECT courseId FROM CollegeReview_MappingToShikshaInstitute where reviewId = ?";
		$res = $this->dbHandle->query($query,array($reviewId));
		$results = $res->result_array();
		return $results[0]['courseId'];
	}

	function updateReviewURLAndTitle($reviewId,$seoReviewURL='',$seoReviewTitle='')
	{
		$this->initiateModel('write');
		if(!empty($reviewId)){
			$sql = "UPDATE CollegeReview_MainTable
				SET review_seo_url = ?, review_seo_title = ? where id = ? ";
			$query = $this->dbHandle->query($sql,array($seoReviewURL,$seoReviewTitle,$reviewId));
			error_log("#####update query ".$this->dbHandle->last_query());
		}
	}

	function getCollegeReviewReasons($status){
		$this->initiateModel('read');
		$queryToGetAllReasons = "SELECT id, reason FROM CollegeReviewReasonMapping where status=?";
		$data = $this->dbHandle->query($queryToGetAllReasons,array($status))->result_array();
		return $data;	
	}
	
	function getListofInstitutes(){
		$this->initiateModel('read');
		$sql = "SELECT institute_id, institute_name FROM institute WHERE status = 'live' and institute_type IN ('Academic_Institute','Test_Preparatory_Institute')";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}	

	function storeModerationDetails($reviewId, $email,$actionType){
		$this->initiateModel('write');
		$queryCmd = "INSERT INTO CollegeReview_ModerationTable (reviewId, moderatorEmail, moderationTime, actionType) VALUES (?,?,NOW(),?)";
		$query = $this->dbHandle->query($queryCmd,array($reviewId,$email,$actionType));
	}

	function getSourcesFromMainTable()
	{
		$this->initiateModel('read');
		$sql = "select distinct(reviewSource) from shiksha.CollegeReview_MainTable where " 
				."reviewSource IS NOT NULL and reviewSource like '%utm_source=%'";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}

	function getModerationDetails($reviewId){
		
		$this->initiateModel('write');

		$sql = "select * from CollegeReview_ModerationTable where reviewId =? order by moderationTime desc limit 1";

		$result	= $this->dbHandle->query($sql,array($reviewId))->result_array();

		return $result;
	}

	function updateReviewTitle($reviewTitle,$reviewId){
		if(!empty($reviewTitle) || $reviewId > 0){

			$this->initiateModel('write');
			$sql = "UPDATE CollegeReview_MainTable SET reviewTitle=? where id = ?";

			return $this->dbHandle->query($sql,array($reviewTitle, $reviewId));

		}
	}

	function saveQualityFlag($reviewQuality,$reviewId){
		if(!empty($reviewQuality) || $reviewId > 0){
			$this->initiateModel('write');
			$sql = "UPDATE CollegeReview_MainTable SET reviewQuality=? where id = ?";

			return $this->dbHandle->query($sql,array($reviewQuality, $reviewId));

		}
	}

	function saveAnonymousFlag($reviewId,$anonymousFlag){
		if($reviewId > 0){
			$this->initiateModel('write');
			$sql = "update CollegeReview_MainTable set anonymousFlag =? where id=?";
			return $this->dbHandle->query($sql,array($anonymousFlag, $reviewId));
		}
	}

	function saveNewCourse($reviewId,$courseData){
		if($reviewId > 0){
			$this->initiateModel('write');
			$sql = "UPDATE CollegeReview_MappingToShikshaInstitute
			  SET courseId=? ,instituteId=?,locationId=? where reviewId=?";
			return $this->dbHandle->query($sql,array($courseData['courseId'],$courseData['instituteId'],$courseData['locationId'], $reviewId));
		}
	}

	function changeDateFormat($timeFilterVar){
        
        if($timeFilterVar != ''){
	        $date = str_replace('/', '-', $timeFilterVar);
	        $timeFilterVar = date('Y-m-d', strtotime($date));
	    }

        return $timeFilterVar;

    }

	public function trackCollegeReview($crTrackingData,$insertMultiple = false){
	 	$this->initiateModel("write");
	 	if($insertMultiple == true){
	 		$this->dbHandle->insert_batch("CollegeReview_Tracking",$crTrackingData);
	 	}else{
	 		$this->dbHandle->insert("CollegeReview_Tracking",$crTrackingData);	
	 	}
		
	}

	function getOriginalData($reviewId){
		if(empty($reviewId)){return;}
		$this->initiateModel('read');
		$sql = "select action, data from CollegeReview_Tracking where reviewId = ? and action in ('autoModerated','reviewAdded','reviewEdited');";
		$result	= $this->dbHandle->query($sql,array($reviewId))->result_array();
		return $result;		
	}

	function getRecentReviewData($reviewId){
		if(empty($reviewId)){return;}
		$this->initiateModel('read');
		$query = "select  placementDescription, infraDescription, facultyDescription, reviewDescription, reviewTitle from CollegeReview_MainTable where id = ?";
		$result = $this->dbHandle->query($query,array($reviewId))->result_array();
		return $result[0];
	}

	function updateBulkReviewStatus($status,$reviewId,$reason = ''){
		$this->initiateModel('write');
		
		if(empty($status) || empty($reason)){
			return;
		}
		
		$query = "UPDATE CollegeReview_MainTable
			  SET status=?, reason=? where id in (?)";
		$query = $this->dbHandle->query($query,array($status,$reason,$reviewId));	
		
	}

	function storeBatchModerationDetails($insert_data){
		$this->initiateModel('write');

		$this->dbHandle->insert_batch('CollegeReview_ModerationTable',$insert_data);
	}	
	function insertBatchReviewForIndex($insert_data){
		$this->initiateModel('write');

		$this->dbHandle->insert_batch('indexlog',$insert_data);
	}

	function insertBatchTrackingData($insert_data){
		$this->initiateModel('write');

		$this->dbHandle->insert_batch('CollegeReview_Tracking',$insert_data);
	}
}

