<?php
require_once('vendor/autoload.php');

class cdmismodel extends MY_Model
{

    function __construct()
    {
        parent::__construct('MISTracking');
    }
    //getting the read/Write access For Database
	function getDbHandle($operation = 'read')
	{
		if($operation=='read')
		{
			return $this->getReadHandle();
		}
		else
		{
	       return $this->getWriteHandle();
		}
	}
	/**
	* @param Integer: Institute Id
	* @return Array : List of courses available in given Institute Id
	*/
	function getCoursesInInstitute($instituteId)
	{	
		$dbHandle = $this->getDbHandle('read');
		$sql = "SELECT course_id, courseTitle FROM  course_details WHERE institute_id = ?";
		$result=$dbHandle->query($sql,$instituteId);
		$result=$result->result_array();
		return $result;
	}
	/**
	* @param Array: Institute Id,Course Id
	* @param String : source,paidType
	* @param String : startDate,endDate
	* @return Array : Number of responses made on given course Ids within selectd duration and based on source and paidType filter
	*/
	function getResponses($instituteId=array(),$courseId= array(),$source='',$paidType='',$startDate='',$endDate='',$viewWise =1)
	{
		$dbHandle = $this->getDbHandle('read');
		//$dbHandle->select('tLMS.userId as userId,tpk.keyName as type, count(1) as responsescount');
		if($viewWise == 1)
		{
			$dbHandle->select('date(tLMS.submit_date) as responseDate,count(distinct tLMS.id) as responsescount');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('WEEK(date(tLMS.submit_date),1) as weekNumber,count(distinct tLMS.id) as responsescount',FALSE);
		}
		else if($viewWise == 3)
		{
			$dbHandle->select('date(tLMS.submit_date) as responseDate,month(date(tLMS.submit_date)) as monthNumber,count(distinct tLMS.id) as responsescount');
		}
		$dbHandle->from('tempLMSTable tLMS');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = tLMS.tracking_keyid','inner');
		$dbHandle->where('tLMS.listing_type_id IN ('.implode(',', $courseId).')');
		$dbHandle->where('tLMS.listing_type','course');
		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		if(!empty($paidType))
			$dbHandle->where('tLMS.listing_subscription_type',$paidType);
		/*$dbHandle->where('tLMS.tracking_keyid !=','0');
		$dbHandle->where('tLMS.tracking_keyid is not null');*/

		if(! empty($startDate))
				$dbHandle->where('tLMS.submit_date >=', $startDate.' 00:00:00');
		if(! empty($endDate))
			$dbHandle->where('tLMS.submit_date <=', $endDate.' 23:59:59');
		if($viewWise == 1)
		{
			$dbHandle->group_by('responseDate');
		}
		else if($viewWise == 2)
		{
			$dbHandle->group_by('weekNumber');
		}
		else if($viewWise == 3)
		{
			$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('responseDate');
			$dbHandle->order_by('responseDate');
		}
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	/**
	* @param Array: Institute Id,Course Id
	* @param String : source,paidType
	* @param String : startDate,endDate
	* @return Array : Number of questions asked on given course Ids within selectd duration and based on source filter
	*/
	function getQuestionsCount($instituteId= array(),$courseId = array(),$source = '',$startDate = '',$endDate = '',$viewWise=1)
	{
		$dbHandle = $this->getDbHandle('read');
		if($viewWise ==	1)
		{
			$dbHandle->select('date(mt.creationDate) as responseDate,count(1) as responsescount');
		}
		else if($viewWise==2)
		{
			$dbHandle->select('WEEK(date(mt.creationDate),1) as weekNumber,count(1) as responsescount',FALSE);
		}	
		else
		{
			$dbHandle->select('date(mt.creationDate) as responseDate,month(date(mt.creationDate)) as monthNumber,count(1) as responsescount');
		}
		$dbHandle->from('messageTable mt');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = mt.tracking_keyid','inner');
		//FORCE INDEX FOR JOIN(idx_messageId,idx_courseId)
		$dbHandle->join('questions_listing_response qlr','qlr.messageId = mt.threadId','inner');

		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		$dbHandle->where_in('mt.status',array('live','closed'));
		if( ! empty($courseId))
				$dbHandle->where('qlr.courseId IN ('.implode(',', $courseId).')');
		$dbHandle->where('fromOthers','user');
		$dbHandle->where('parentId','0');
		/*$dbHandle->where('mt.tracking_keyid !=','0');
		$dbHandle->where('mt.tracking_keyid is not null');*/

		if(! empty($startDate))
				$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		if(! empty($endDate))
				$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');

		if($viewWise == 1)
			$dbHandle->group_by('responseDate');
		else if($viewWise == 2)
				$dbHandle->group_by('weekNumber');
		else if($viewWise == 3)
		{
			$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('responseDate');
			$dbHandle->order_by('responseDate');
		}
		$result = $dbHandle->get()->result_array();
		return $result;
		
	}
	/**
	* @param Array: Institute Id,Course Id
	* @param String : source,paidType
	* @param String : startDate,endDate
	* @return Array : Number of answers given on given course Ids within selectd duration and based on source filter
	*/
	function getAnswersCount($instituteId= array(),$courseId = array(),$source = '',$startDate = '',$endDate = '',$viewWise=1)
	{
		$dbHandle = $this->getDbHandle('read');
		if($viewWise ==1)
		{
			$dbHandle->select('date(mt.creationDate) as responseDate, count(1) as responsescount');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('week(date(mt.creationDate),1) as weekNumber,count(1) as responsescount',FALSE);
		}
		else
		{
			$dbHandle->select('date(mt.creationDate) as responseDate,month(date(mt.creationDate)) as monthNumber,count(1) as responsescount');
		}

		$dbHandle->from('messageTable mt');
		$dbHandle->join('tracking_pagekey tpk', 'tpk.id = mt.tracking_keyid','inner');
		//FORCE INDEX FOR JOIN(idx_messageId,idx_courseId)
		$dbHandle->join('questions_listing_response qlr','qlr.messageId = mt.threadId','inner');
		$dbHandle->where('mt.parentId = mt.threadId');
		$dbHandle->where('mt.fromOthers','user');

		if( ! empty($courseId))
			$dbHandle->where('qlr.courseId IN ('.implode(',', $courseId).')');

		if(!empty($source))
			$dbHandle->where('tpk.siteSource',$source);

		$dbHandle->where_in('mt.status',array('live','closed'));

      /*$dbHandle->where('mt.tracking_keyid !=','0');
		$dbHandle->where('mt.tracking_keyid is not null');*/
		if(! empty($startDate))
					$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		if(! empty($endDate))
					$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		if($viewWise == 1)
		{
			$dbHandle->group_by('responseDate');
		}
		else if($viewWise == 2)
		{
			$dbHandle->group_by('weekNumber');
		}
		else
		{
			$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('responseDate');
			$dbHandle->order_by('responseDate');
		}
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	/**
	* @param Array: Institute Id,Course Id
	* @param String : source,paidType
	* @param String : startDate,endDate
	* @return Array : Number of Answers Liked on given course Ids within selectd duration and based on source filter
	*/
	function getDigupCount($instituteId= array(),$courseId = array(),$source = '',$startDate = '',$endDate = '',$viewWise=1)
	{
		$dbHandle = $this->getDbHandle('read');
		if($viewWise == 1)
		{
			$dbHandle->select('date(dp.digTime) as responseDate,count(distinct dp.productId) as responsescount');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('week(date(dp.digTime),1) as weekNumber,count(distinct dp.productId) as responsescount',FALSE);
		}
		else
		{
			$dbHandle->select('date(dp.digTime) as responseDate,month(date(dp.digTime)) as monthNumber,count(distinct dp.productId) as responsescount');
		}
		$dbHandle->from('digUpUserMap dp');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = dp.tracking_keyid','inner');
		$dbHandle->join('messageTable mt', 'mt.msgId = dp.productId','inner');
		//FORCE INDEX FOR JOIN(idx_messageId,idx_courseId)
		$dbHandle->join('questions_listing_response qlr','qlr.messageId = mt.threadId','inner');
		$dbHandle->where('mt.parentId = mt.threadId');
		$dbHandle->where('mt.fromOthers','user');
		$dbHandle->where('dp.digFlag',1);
		$dbHandle->where('dp.digUpStatus','live');

		if( ! empty($courseId))
			$dbHandle->where('qlr.courseId IN ('.implode(',', $courseId).')');

		if(!empty($source))
			$dbHandle->where('tpk.siteSource',$source);

		/*$dbHandle->where('dp.tracking_keyid !=','0');
		$dbHandle->where('dp.tracking_keyid is not null');*/
		if(! empty($startDate))
			$dbHandle->where('dp.digTime >=',$startDate.' 00:00:00');

		if(! empty($endDate))
			$dbHandle->where('dp.digTime <=',$endDate.' 23:59:59');

		if($viewWise == 1)
		{
			$dbHandle->group_by('responseDate');
		}
		else if($viewWise == 2)
		{
			$dbHandle->group_by('weekNumber');
		}
		else
		{
			$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('responseDate');
			$dbHandle->order_by('responseDate');
		}
		$result = $dbHandle->get()->result_array();

		
		return $result;		
	}
	/**
	* @param Inetger: courseId
	* @return Integer: Institute Id corresponding to the given course Id
	*/
	function getInstituteID($courseId='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('institute_id as InstituteId');
		$dbHandle->from('course_details');
		$dbHandle->where('course_id',$courseId);
		$instituteId = $dbHandle->get();
		$instituteId = $instituteId->row();
		return $instituteId->InstituteId;
	}
	/**
	* @param $dateRangeArray : contain startDate and endDate
	* @param $nationalFilterArray : contain input filters
	* @return Array: Number of Registrations Done on Domestic Site 
	*/
	function getNationalRegistrationData($dateRangeArray = array(), $nationalFilterArray = array(), $viewType = 1)
	{
		/*$selectFields = array(
			'submitDate as ResponseDate',
			'tpk.siteSource as DeviceName',
			'CONCAT( tpk.page, " > ", CONCAT(UCASE(MID(tpk.keyname, 1, 1 )), MID(tpk.keyname, 2 )), " > ",  CONCAT(UCASE(MID(tpk.widget, 1, 1 )), MID(tpk.widget, 2 ) ) ) AS WidgetName',
			'tpk.page as PageName',
			'count(1) as ResponseCount',
			'tpk.site as TeamName',
			'tpk.conversionType',
			'rt.subCatId as SubCategoryId',
		);*/

		if($nationalFilterArray['examId'] >0){
			$blogIds =array();
			if($nationalFilterArray['subExamId'] == 0){
				$blogIds = $this->_getAllSubExamId($nationalFilterArray['examId']);
			}
		}

		$selectFields = array(
			'tpk.page as PageName',
			'tpk.siteSource as DeviceName',
			'count(1) as ResponseCount',
		);

		$mode = $nationalFilterArray['queryMode'];

		if ($mode == 'graph') {
			$selectFields = array(
				'rt.submitDate as Date',
				'COUNT(1) as ScalarValue',
			);

			if($viewType == 2){
				$selectFields = array(
					"DATE_ADD(MIN(Date(rt.submitDate)), INTERVAL(1-DAYOFWEEK(MIN(Date(rt.submitDate)))) +1 DAY) as Date",
					"YEARWEEK(rt.submitDate, 1)",
					"COUNT(1) AS ScalarValue"
				);
			} else if($viewType == 3){
				$selectFields = array(
					"DATE_SUB(MIN(Date(rt.submitDate)), INTERVAL(DAYOFMONTH( MIN( Date(rt.submitDate) ) ) ) -1 DAY) as Date",
					"COUNT(1) AS ScalarValue"
				);
			}
		}

		$dbHandle = $this->getDbHandle('read');
		$subCategoryId = $nationalFilterArray['subcategory'];
		$categoryId    = $nationalFilterArray['category'];
		$categoryWhereClause = array();
		if (!empty($categoryId) || $categoryId != 0) {
			if($categoryId != 14){
				$categoryWhereClause['categoryId'] = $categoryId;	
				if (!empty($subCategoryId) || $subCategoryId != 0) {
					$categoryWhereClause['subCatId'] = $subCategoryId;
				}
				if( count($categoryWhereClause) > 0){
					$dbHandle->where($categoryWhereClause);
				}
				$dbHandle->where('rt.userType <>', 'testprep');
			}else{
				$dbHandle->where('userType','testPrep');
				if($nationalFilterArray['examId'] >0){
					if(count($blogIds)>0){
						$dbHandle->where_in('blogId',$blogIds);
					}else{
						$dbHandle->where('blogId',$nationalFilterArray['subExamId']);
					}
				}
			}			
		}
		
		
		$dbHandle->select($selectFields);
		$dbHandle->from('registrationTracking rt');
		$dbHandle->join('tracking_pagekey tpk', 'tpk.id = rt.trackingkeyId', 'inner');
		/*
                $dbHandle->join('tusersourceInfo tinfo', 'tup.UserId = tinfo.userid', 'inner');
                $dbHandle->join('tracking_pagekey tpk', 'tpk.id = tinfo.tracking_keyid', 'inner');*/

		foreach ($nationalFilterArray as $key => $value) {
			if (isset($key) && $value !== '' && $value != 'all') {
				switch ($key) {
					case 'pageName':
						$dbHandle->where('tpk.page', $value);
						break;

					case 'deviceType':
						$dbHandle->where('tpk.siteSource', $value);
						break;
					case 'widget':
						if (isset($nationalFilterArray['widget'])) {
							$dbHandle->where('tpk.id', $nationalFilterArray['widget']);
						}
						break;
				}
			}
		}

		/*if (!empty($ldbCourseId)){
			$desiredCourses = implode(",", $ldbCourseId);
			$dbHandle->where("tup.DesiredCourse IN ($desiredCourses)");
		}*/
		
		$dbHandle->where('tpk.site <>', 'Study Abroad');
		$dbHandle->where('isNewReg', 'yes');
		//$dbHandle->where_in('rt.userType', array('national', 'testPrep'));
		//$dbHandle->where('rt.tracking_keyid IS NOT NULL');

		if (!empty($dateRangeArray['startDate'])) {
			$dbHandle->where('rt.submitDate >=', $dateRangeArray['startDate']);
		}
		if (!empty($dateRangeArray['endDate'])) {
			$dbHandle->where('rt.submitDate <=', $dateRangeArray['endDate']);
		}

		if ($mode != 'graph') {

			$dbHandle->group_by('DeviceName, PageName');
			$dbHandle->order_by('ResponseCount', 'DESC');
			$result                                       = $dbHandle->get()->result();
		} else {
			$groupBy = array('Date');

			if($viewType == 2){
				$groupBy = array(
					"YEARWEEK(rt.submitDate, 1)",
				);
			} else if($viewType == 3){
				$groupBy = array(
					"MONTH(rt.submitDate)",
				);
			}

			$dbHandle->group_by($groupBy);
			$dbHandle->order_by('Date', 'DESC');
			$result                                    = $dbHandle->get()->result();
		}

		return $result;
	}


	function getNationalRegistrationData_DeviceWise($dateRangeArray=array(),$nationalFilterArray=array(),$ldbCourseId=array())
	{
		if($nationalFilterArray['examId'] >0){
			$blogIds =array();
			if($nationalFilterArray['subExamId'] == 0){
				$blogIds = $this->_getAllSubExamId($nationalFilterArray['examId']);
			}
		}

		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('tpk.siteSource as PivotName,count(1) as ScalarValue');
		$dbHandle->from('registrationTracking rt');
		//$dbHandle->from('tusersourceInfo tinfo');
		/*if( ! empty($ldbCourseId))
			$dbHandle->join('tUserPref tup','tup.UserId = tinfo.userid','inner');*/
		$dbHandle->join('tracking_pagekey tpk','tpk.id = rt.trackingkeyId','inner');
		foreach ($nationalFilterArray as $key => $value)
		{
			if (isset($key) && $value !== '' && $value != 'all') {
				switch ($key) {
					case 'pageName':
							$dbHandle->where('tpk.page',$value);
							break;
					case 'deviceType':
							$dbHandle->where('tpk.siteSource',$value);
							break;
					case 'countryId':
							break;
					case 'widget':
						if(isset($nationalFilterArray['widget'])){
							$dbHandle->where('tpk.id', $nationalFilterArray['widget']);
						}
						break;
					}
			}
		}

		/*if( !empty($ldbCourseId) )
			$dbHandle->where_in('tup.DesiredCourse',$ldbCourseId);*/
		$subCategoryId = $nationalFilterArray['subcategory'];
		$categoryId = $nationalFilterArray['category'];
		$categoryWhereClause = array();
		if (!empty($categoryId) || $categoryId != 0) {
			if($categoryId != 14){
				$categoryWhereClause['categoryId'] = $categoryId;	
				if (!empty($subCategoryId) || $subCategoryId != 0) {
					$categoryWhereClause['subCatId'] = $subCategoryId;
				}
				if( count($categoryWhereClause) > 0){
					$dbHandle->where($categoryWhereClause);
				}
				$dbHandle->where('rt.userType <>', 'testprep');
			}else{
				$dbHandle->where('userType','testPrep');
				if($nationalFilterArray['examId'] >0){
					if(count($blogIds)>0){
						$dbHandle->where_in('blogId',$blogIds);
					}else{
						$dbHandle->where('blogId',$nationalFilterArray['subExamId']);
					}
				}
			}			
		}

		$dbHandle->where('tpk.site <>','Study Abroad');
		$dbHandle->where('rt.isNewReg','yes');
		if( ! empty($dateRangeArray['startDate']))
			{
				$dbHandle->where('rt.submitDate >=',$dateRangeArray['startDate']);
			}
		if( ! empty($dateRangeArray['endDate']))
			{
				$dbHandle->where('rt.submitDate <=',$dateRangeArray['endDate']);
			}
			$dbHandle->group_by('PivotName');
			$dbHandle->order_by('ScalarValue', 'DESC');
			$result = $dbHandle->get()->result();
			return $result;
	}

	function getNationalRegistrationData_PageWise($dateRangeArray = array(), $nationalFilterArray = array(), $ldbCourseId = array())
	{
		if($nationalFilterArray['examId'] >0){
			$blogIds =array();
			if($nationalFilterArray['subExamId'] == 0){
				$blogIds = $this->_getAllSubExamId($nationalFilterArray['examId']);
			}
		}
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('tpk.page as PivotName,count(1) as ScalarValue');
		//$dbHandle->from('tusersourceInfo tinfo');
		$dbHandle->from('registrationTracking rt');
		$dbHandle->join('tracking_pagekey tpk', 'tpk.id = rt.trackingkeyId', 'inner');
		foreach ($nationalFilterArray as $key => $value) {
			if (isset($key) && $value !== '' && $value != 'all') {
				switch ($key) {
					case 'pageName':
						$dbHandle->where('tpk.page', $value);
						break;
					case 'deviceType':
						$dbHandle->where('tpk.siteSource', $value);
						break;
					case 'countryId':
						break;
					case 'widget':
						if (isset($nationalFilterArray['widget'])) {
							$dbHandle->where('tpk.id', $nationalFilterArray['widget']);
						}
						break;
				}
			}
		}

		$subCategoryId = $nationalFilterArray['subcategory'];
		$categoryId = $nationalFilterArray['category'];
		$categoryWhereClause = array();
		if (!empty($categoryId) || $categoryId != 0) {
			if($categoryId != 14){
				$categoryWhereClause['categoryId'] = $categoryId;	
				if (!empty($subCategoryId) || $subCategoryId != 0) {
					$categoryWhereClause['subCatId'] = $subCategoryId;
				}
				if( count($categoryWhereClause) > 0){
					$dbHandle->where($categoryWhereClause);
				}
				$dbHandle->where('rt.userType <>', 'testprep');
			}else{
				$dbHandle->where('userType','testPrep');
				if($nationalFilterArray['examId'] >0){
					if(count($blogIds)>0){
						$dbHandle->where_in('blogId',$blogIds);
					}else{
						$dbHandle->where('blogId',$nationalFilterArray['subExamId']);
					}
				}
			}			
		}

		$dbHandle->where('rt.isNewReg', 'yes');

		$dbHandle->where('tpk.site <>', 'Study Abroad');

		if (!empty($dateRangeArray['startDate'])) {
			$dbHandle->where('rt.submitDate >=', $dateRangeArray['startDate']);
		}
		if (!empty($dateRangeArray['endDate'])) {
			$dbHandle->where('rt.submitDate <=', $dateRangeArray['endDate']);
		}
		$dbHandle->group_by('PivotName');
		$dbHandle->order_by('ScalarValue', 'DESC');
		$result = $dbHandle->get()->result();
		
		return $result;
	}

	private function _getAllSubExamId($examId){
		$dbHandle     = $this->getDbHandle('read');
		//select blogId from blogTable where blogType ='exam' AND status = 'live' and parentId=464;
		$dbHandle->select('distinct(blogId)');
		$dbHandle->from('blogTable');
		$dbHandle->where('blogType','exam');
		$dbHandle->where('status','live');
		$dbHandle->where('parentId',$examId);
		//echo $dbHandle->_compile_select();die;
		$result = $dbHandle->get()->result_array();
		//echo $dbHandle->last_query();die;
		foreach ($result as $key => $value) {
			$blogIds[] = $value['blogId'];
		}
		return $blogIds;
	}

	// Method for action wise data
	function getNationalRegistrationData_WidgetWise($dateRangeArray = array(), $nationalFilterArray = array(), $ldbCourseId = array())
	{
		if($nationalFilterArray['examId'] >0){
			$blogIds =array();
			if($nationalFilterArray['subExamId'] == 0){
				$blogIds = $this->_getAllSubExamId($nationalFilterArray['examId']);
			}
		}

		$dbHandle     = $this->getDbHandle('read');
		$selectFields = array(
		//		'CONCAT( tpk.page, " > ", CONCAT(UCASE(MID(tpk.keyname, 1, 1 )), MID(tpk.keyname, 2 )), " > ",  CONCAT(UCASE(MID(tpk.widget, 1, 1 )), MID(tpk.widget, 2 ) ) ) as Pivot',
			'tpk.keyName as PivotName',
			'COUNT(1) AS ScalarValue'
		);
		$dbHandle->select($selectFields);
		$dbHandle->from('registrationTracking rt');

		$dbHandle->join('tracking_pagekey tpk', 'tpk.id = rt.trackingkeyId', 'inner');
		foreach ($nationalFilterArray as $key => $value) {
			if (isset($key) && $value !== '' && $value != 'all') {
				switch ($key) {
					case 'pageName':
						$dbHandle->where('tpk.page', $value);
						break;

					case 'deviceType':
						$dbHandle->where('tpk.siteSource', $value);
						break;
					case 'countryId':
						break;
					case 'widget':
						if (isset($nationalFilterArray['widget'])) {
							$dbHandle->where('tpk.id', $nationalFilterArray['widget']);
						}
						break;
				}
			}
		}

		$subCategoryId = $nationalFilterArray['subcategory'];
		$categoryId = $nationalFilterArray['category'];
		$categoryWhereClause = array();
		if (!empty($categoryId) || $categoryId != 0) {
			if($categoryId != 14){
				$categoryWhereClause['categoryId'] = $categoryId;	
				if (!empty($subCategoryId) || $subCategoryId != 0) {
					$categoryWhereClause['subCatId'] = $subCategoryId;
				}
				if( count($categoryWhereClause) > 0){
					$dbHandle->where($categoryWhereClause);
				}
				$dbHandle->where('rt.userType <>', 'testprep');
			}else{
				$dbHandle->where('userType','testPrep');
				if($nationalFilterArray['examId'] >0){
					if(count($blogIds)>0){
						$dbHandle->where_in('blogId',$blogIds);
					}else{
						$dbHandle->where('blogId',$nationalFilterArray['subExamId']);
					}
				}
			}			
		}

		$dbHandle->where('rt.isNewReg', 'yes');
		//$dbHandle->where_in('rt.userType', array('national', 'testPrep'));

		$dbHandle->where('tpk.site <>', 'Study Abroad');
		//$dbHandle->where('tinfo.tracking_keyid IS NOT NULL');

		if (!empty($dateRangeArray['startDate'])) {
			$dbHandle->where('rt.submitDate >=', $dateRangeArray['startDate']);
		}
		if (!empty($dateRangeArray['endDate'])) {
			$dbHandle->where('rt.submitDate <=', $dateRangeArray['endDate']);
		}
		$dbHandle->group_by('PivotName');
		$dbHandle->order_by('ScalarValue', 'DESC');
		$result = $dbHandle->get()->result();

		//	_p($dbHandle->last_query()); return;
		return $result;

	}

	function getNationalRegistrationData_WidgetNameWise($dateRangeArray=array(),$nationalFilterArray=array(),$ldbCourseId=array())
	{
		if($nationalFilterArray['examId'] >0){
			$blogIds =array();
			if($nationalFilterArray['subExamId'] == 0){
				$blogIds = $this->_getAllSubExamId($nationalFilterArray['examId']);
			}
		}

		$dbHandle = $this->getDbHandle('read');
		$selectFields = array(
			'tpk.widget as PivotName',
			'COUNT(1) AS ScalarValue'
		);
		$dbHandle->select($selectFields);
		$dbHandle->from('registrationTracking rt');

		$dbHandle->join('tracking_pagekey tpk','tpk.id = rt.trackingkeyId','inner');
		foreach ($nationalFilterArray as $key => $value)
		{
			if (isset($key) && $value !== '' && $value != 'all') {
				switch ($key) {
					case 'pageName':
						$dbHandle->where('tpk.page',$value);
						break;

					case 'deviceType':
						$dbHandle->where('tpk.siteSource',$value);
						break;
					case 'countryId':
						break;
					case 'widget':
						if(isset($nationalFilterArray['widget'])){
							$dbHandle->where('tpk.id', $nationalFilterArray['widget']);
						}
						break;
				}
			}
		}

		$subCategoryId = $nationalFilterArray['subcategory'];
		$categoryId = $nationalFilterArray['category'];
		$categoryWhereClause = array();
		if (!empty($categoryId) || $categoryId != 0) {
			if($categoryId != 14){
				$categoryWhereClause['categoryId'] = $categoryId;	
				if (!empty($subCategoryId) || $subCategoryId != 0) {
					$categoryWhereClause['subCatId'] = $subCategoryId;
				}
				if( count($categoryWhereClause) > 0){
					$dbHandle->where($categoryWhereClause);
				}
				$dbHandle->where('rt.userType <>', 'testprep');
			}else{
				$dbHandle->where('userType','testPrep');
				if($nationalFilterArray['examId'] >0){
					if(count($blogIds)>0){
						$dbHandle->where_in('blogId',$blogIds);
					}else{
						$dbHandle->where('blogId',$nationalFilterArray['subExamId']);
					}
				}
			}			
		}
		
		$dbHandle->where('rt.isNewReg','yes');
		//$dbHandle->where_in('rt.userType',array('national','testPrep'));

		$dbHandle->where('tpk.site <>','Study Abroad');
		//$dbHandle->where('tinfo.tracking_keyid IS NOT NULL');

		if( ! empty($dateRangeArray['startDate']))
		{
			$dbHandle->where('rt.submitDate >=',$dateRangeArray['startDate']);
		}
		if( ! empty($dateRangeArray['endDate']))
		{
			$dbHandle->where('rt.submitDate <=',$dateRangeArray['endDate']);
		}
		$dbHandle->group_by('PivotName');
		$dbHandle->order_by('ScalarValue', 'DESC');

		$result = $dbHandle->get()->result();
		return $result;

	}

	function getNationalRegistrationData_conversionTypeWise($dateRangeArray=array(),$nationalFilterArray=array(),$ldbCourseId=array())
	{
		if($nationalFilterArray['examId'] >0){
			$blogIds =array();
			if($nationalFilterArray['subExamId'] == 0){
				$blogIds = $this->_getAllSubExamId($nationalFilterArray['examId']);
			}
		}

		$dbHandle = $this->getDbHandle('read');
		$selectFields = array(
			'tpk.conversionType as PivotName',
			'COUNT(1) AS ScalarValue'
		);
		$dbHandle->select($selectFields);
		$dbHandle->from('registrationTracking rt');

		$dbHandle->join('tracking_pagekey tpk','tpk.id = rt.trackingkeyId','inner');
		foreach ($nationalFilterArray as $key => $value)
		{
			if (isset($key) && $value !== '' && $value != 'all') {
				switch ($key) {
					case 'pageName':
						$dbHandle->where('tpk.page',$value);
						break;

					case 'deviceType':
						$dbHandle->where('tpk.siteSource',$value);
						break;
					case 'countryId':
						break;
					case 'widget':
						if(isset($nationalFilterArray['widget'])){
							$dbHandle->where('tpk.id', $nationalFilterArray['widget']);
						}
						break;
				}
			}
		}

		$subCategoryId = $nationalFilterArray['subcategory'];
		$categoryId = $nationalFilterArray['category'];
		$categoryWhereClause = array();
		if (!empty($categoryId) || $categoryId != 0) {
			if($categoryId != 14){
				$categoryWhereClause['categoryId'] = $categoryId;	
				if (!empty($subCategoryId) || $subCategoryId != 0) {
					$categoryWhereClause['subCatId'] = $subCategoryId;
				}
				if( count($categoryWhereClause) > 0){
					$dbHandle->where($categoryWhereClause);
				}
				$dbHandle->where('rt.userType <>', 'testprep');
			}else{
				$dbHandle->where('userType','testPrep');
				if($nationalFilterArray['examId'] >0){
					if(count($blogIds)>0){
						$dbHandle->where_in('blogId',$blogIds);
					}else{
						$dbHandle->where('blogId',$nationalFilterArray['subExamId']);
					}
				}
			}			
		}
		$dbHandle->where('rt.isNewReg','yes');
		//$dbHandle->where_in('rt.userType',array('national','testPrep'));

		$dbHandle->where('tpk.site <>','Study Abroad');
		//$dbHandle->where('tinfo.tracking_keyid IS NOT NULL');

		if( ! empty($dateRangeArray['startDate']))
		{
			$dbHandle->where('rt.submitDate >=',$dateRangeArray['startDate']);
		}
		if( ! empty($dateRangeArray['endDate']))
		{
			$dbHandle->where('rt.submitDate <=',$dateRangeArray['endDate']);
		}
		$dbHandle->group_by('PivotName');
		$dbHandle->order_by('ScalarValue', 'DESC');
		$result = $dbHandle->get()->result();
		return $result;

	}

	function getNationalRegistrationData_SourceWise($dateRangeArray = array(), $nationalFilterArray = array(), $ldbCourseId = array())
	{
		if($nationalFilterArray['examId'] >0){
			$blogIds =array();
			if($nationalFilterArray['subExamId'] == 0){
				$blogIds = $this->_getAllSubExamId($nationalFilterArray['examId']);
			}
		}
		
		$dbHandle     = $this->getDbHandle('read');
		$selectFields = array(
			'rt.visitorSessionid as PivotName',
			'COUNT(1) AS ScalarValue'
		);
		$dbHandle->select($selectFields);
		$dbHandle->from('registrationTracking rt');
		$dbHandle->join('tracking_pagekey tpk', 'tpk.id = rt.trackingkeyId', 'inner');
		foreach ($nationalFilterArray as $key => $value) {
			if (isset($key) && $value !== '' && $value != 'all') {
				switch ($key) {
					case 'pageName':
						$dbHandle->where('tpk.page', $value);
						break;

					case 'deviceType':
						$dbHandle->where('tpk.siteSource', $value);
						break;
					case 'countryId':
						break;
					case 'widget':
						if (isset($nationalFilterArray['widget'])) {
							$dbHandle->where('tpk.id', $nationalFilterArray['widget']);
						}
						break;
				}
			}
		}

		/*if( ! empty($ldbCourseId))
			$dbHandle->where_in('tup.DesiredCourse',$ldbCourseId);*/

		$subCategoryId = $nationalFilterArray['subcategory'];
		$categoryId = $nationalFilterArray['category'];
		$categoryWhereClause = array();
		if (!empty($categoryId) || $categoryId != 0) {
			if($categoryId != 14){
				$categoryWhereClause['categoryId'] = $categoryId;	
				if (!empty($subCategoryId) || $subCategoryId != 0) {
					$categoryWhereClause['subCatId'] = $subCategoryId;
				}
				if( count($categoryWhereClause) > 0){
					$dbHandle->where($categoryWhereClause);
				}
				$dbHandle->where('rt.userType <>', 'testprep');
			}else{
				$dbHandle->where('userType','testPrep');
				if($nationalFilterArray['examId'] >0){
					if(count($blogIds)>0){
						$dbHandle->where_in('blogId',$blogIds);
					}else{
						$dbHandle->where('blogId',$nationalFilterArray['subExamId']);
					}
				}
			}			
		}

		$dbHandle->where('tpk.site <>', 'Study Abroad');
		$dbHandle->where('rt.isNewReg', 'yes');
		//		$dbHandle->where_in('rt.userType', array('national', 'testPrep'));

		if (!empty($dateRangeArray['startDate'])) {
			$dbHandle->where('rt.submitDate >=', $dateRangeArray['startDate']);
		}
		if (!empty($dateRangeArray['endDate'])) {
			$dbHandle->where('rt.submitDate <=', $dateRangeArray['endDate']);
		}
		$dbHandle->group_by('PivotName');
		$dbHandle->order_by('ScalarValue', 'DESC');

		$result = $dbHandle->get()->result();

		return $result;

	}

	function getNationalRegistrationData_paidFreeWise($dateRangeArray, $nationalFilterArray)
	{
		if($nationalFilterArray['examId'] >0){
			$blogIds =array();
			if($nationalFilterArray['subExamId'] == 0){
				$blogIds = $this->_getAllSubExamId($nationalFilterArray['examId']);
			}
		}
		
		$dbHandle     = $this->getDbHandle();
		$selectFields = array(
			'rt.source as PivotName',
			'COUNT(1) AS ScalarValue'
		);
		$dbHandle->select($selectFields);
		$dbHandle->from('registrationTracking rt');
		$dbHandle->join('tracking_pagekey tpk', 'tpk.id = rt.trackingkeyId', 'inner');
		foreach ($nationalFilterArray as $key => $value) {
			if (isset($key) && $value !== '' && $value != 'all') {
				switch ($key) {
					case 'pageName':
						$dbHandle->where('tpk.page', $value);
						break;

					case 'deviceType':
						$dbHandle->where('tpk.siteSource', $value);
						break;
					case 'countryId':
						break;
					case 'widget':
						if (isset($nationalFilterArray['widget'])) {
							$dbHandle->where('tpk.id', $nationalFilterArray['widget']);
						}
						break;
				}
			}
		}

		/*if( ! empty($ldbCourseId))
			$dbHandle->where_in('tup.DesiredCourse',$ldbCourseId);*/

		$subCategoryId = $nationalFilterArray['subcategory'];
		$categoryId = $nationalFilterArray['category'];
		$categoryWhereClause = array();
		if (!empty($categoryId) || $categoryId != 0) {
			if($categoryId != 14){
				$categoryWhereClause['categoryId'] = $categoryId;	
				if (!empty($subCategoryId) || $subCategoryId != 0) {
					$categoryWhereClause['subCatId'] = $subCategoryId;
				}
				if( count($categoryWhereClause) > 0){
					$dbHandle->where($categoryWhereClause);
				}
				$dbHandle->where('rt.userType <>', 'testprep');
			}else{
				$dbHandle->where('userType','testPrep');
				if($nationalFilterArray['examId'] >0){
					if(count($blogIds)>0){
						$dbHandle->where_in('blogId',$blogIds);
					}else{
						$dbHandle->where('blogId',$nationalFilterArray['subExamId']);
					}
				}
			}			
		}
		
		$dbHandle->where('tpk.site <>', 'Study Abroad');
		$dbHandle->where('rt.isNewReg', 'yes');
//		$dbHandle->where_in('rt.userType', array('national', 'testPrep'));
//		$dbHandle->where('tinfo.tracking_keyid IS NOT NULL');

		if (!empty($dateRangeArray['startDate'])) {
			$dbHandle->where('rt.submitDate >=', $dateRangeArray['startDate']);
		}
		if (!empty($dateRangeArray['endDate'])) {
			$dbHandle->where('rt.submitDate <=', $dateRangeArray['endDate']);
		}
		$dbHandle->group_by('PivotName');
		$dbHandle->order_by('ScalarValue', 'DESC');
		$result = $dbHandle->get()->result();

		return $result;
	}

	function getNationalLDBCourseId($subcategoryId)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('DISTINCT(ldbCourseID)');
		$dbHandle->from('LDBCoursesToSubcategoryMapping ldb');
		$whereClauses = array(
			"categoryID in ($subcategoryId)",
			"status = 'live'"
		);
		$dbHandle->where(implode(" AND ", $whereClauses));
		$result       = $dbHandle->get()->result_array();
		$ldbCourseIds = array();
		$i            = 0;
		foreach ($result as $key => $value) {
			$ldbCourseIds[ $i++ ] = $value['ldbCourseID'];
		}

		return $ldbCourseIds;
	}


function getStudyAbroadRegistrationData($dateRangeArray = array(), $saFilterArray = array())
{
	/*if( ! empty($saFilterArray)  && ( ! empty($saFilterArray['categoryId']) || ! empty($saFilterArray['countryId']) || !empty($saFilterArray['courseLevel'])) )
	{

			$distinct_UserId = $this->getRegistrationUserIds($dateRangeArray,$saFilterArray);
					if( empty($distinct_UserId))
					{
						return $distinct_UserId;
					}
					else
					{
						$i=0;
							$distinct = array();
							foreach ($distinct_UserId as $key => $value) 
							{
								$distinct[$i++] = $value['UserId'];
							}
					}
	}*/
	/*if(!empty($saFilterArray['categoryId']) || !empty($saFilterArray['courseLevel']))
	{
		$whereClause = $this->getLDBCourseIdForStudyAbroad($saFilterArray['categoryId'],$saFilterArray['courseLevel']);
	}
	$rankingPageNames = array('universityRankingPage','courseRankingPage');*/
	$dbHandle = $this->getDbHandle('read');
	if($saFilterArray['view'] == 1)
	{
		//$dbHandle->select('tpk.keyName as type,tpk.widget,tpk.siteSource,conversionType ,page,rt.source as paidFree, date as responseDate,count(1) as reponsesCount');
		$sql = "SELECT tpk.keyName as type,tpk.widget,tpk.siteSource,conversionType ,page,rt.source as paidFree, submitDate as responseDate,count(1) as reponsesCount";
	}
	else if($saFilterArray['view']== 2)
	{
		//$dbHandle->select('tpk.keyName as type,tpk.widget,tpk.siteSource,conversionType ,page,rt.source as paidFree,datetime as responseDate,WEEK(date,1) as weekNo,count(1) as reponsesCount',FALSE);	
		$sql = "SELECT tpk.keyName as type,tpk.widget,tpk.siteSource,conversionType ,page,rt.source as paidFree,submitDate as responseDate,WEEK(submitDate,1) as weekNo,count(1) as reponsesCount";
	}
	else
	{
    	//$dbHandle->select('tpk.keyName as type,tpk.widget,tpk.siteSource,conversionType ,page,date as responseDate,month(date) as monthNo,count(1) as reponsesCount',FALSE);
    	$sql = "SELECT tpk.keyName as type,tpk.widget,tpk.siteSource,conversionType ,page,submitDate as responseDate,month(submitDate) as monthNo,count(1) as reponsesCount";
	}
    //$dbHandle->from('tusersourceInfo tinfo');
    $sql .= $this->getStudyAbroadRegistrationFilterQuery($dateRangeArray,$saFilterArray);
    /*$dbHandle->group_by('keyName');
	$dbHandle->group_by('page');
	$dbHandle->group_by('widget');
	$dbHandle->group_by('conversionType');
	$dbHandle->group_by('siteSource');
	$dbHandle->group_by('source');*/
	$sql .= " AND rt.isNewReg = 'yes' ";
	$sql .= " group by keyName,page,widget,conversionType,siteSource,source";
	if($saFilterArray['view']==1)
	{
		//$dbHandle->group_by('responseDate');
		$sql .= ",responseDate";
	}
	else if($saFilterArray['view']==2)
	{
		//$dbHandle->group_by('weekNo');
		$sql .= ",weekNo";
	}
	else 
	{
		//$dbHandle->group_by('responseDate');
		//$dbHandle->group_by('monthNo');
		$sql .= ",responseDate,monthNo";
	}
	$sql .= " ORDER BY responseDate asc";
	//$dbHandle->where('tinfo.tracking_keyid !=',0);
	$result = $dbHandle->query($sql)->result_array();
	return $result;
}
function getStudyAbroadRegistrationFilterQuery($dateRangeArray = array(), $saFilterArray = array())
{
	global $studyAbroadAllDesiredCourses;
	$LDBCourses = $studyAbroadAllDesiredCourses;
	if(in_array($saFilterArray['categoryId'], $LDBCourses))
		$LDBwhereClause = $saFilterArray['categoryId'];
	elseif(!empty($saFilterArray['courseLevel']) || $saFilterArray['courseLevel'] != 0)
	{
		$LDBwhereClause = $this->getLDBCourseIdForStudyAbroad($saFilterArray['categoryId'],$saFilterArray['courseLevel']);
	}
	elseif(!empty($saFilterArray['categoryId']) || $saFilterArray['categoryId'] !=0 )
	{
		$categoryWhereClause = $saFilterArray['categoryId'];
	}
	$rankingPageNames = array('universityRankingPage','courseRankingPage');
    $sql .= " FROM registrationTracking rt ";
    $sql .= "INNER JOIN tracking_pagekey tpk ON tpk.id = rt.trackingkeyId WHERE ";
    if($saFilterArray['pageName'] != '')
    {
    	if($saFilterArray['pageName'] == 'rankingPage')
    	{
    		if($saFilterArray['pageType'] == '0' )
    		{
    			$wherePageNames = "'".implode("','", $rankingPageNames)."'";
    			$sql .= "tpk.page IN (".$wherePageNames.") AND ";
    		}
			
			else if($saFilterArray['pageType'] == '1' )
			{
				$sql .= "tpk.page = 'universityRankingPage' AND ";
			}
			else if($saFilterArray['pageType'] == '2' )
			{
				$sql = "tpk.page = 'courseRankingPage' AND ";
			}
    	}else if($saFilterArray['pageName'] == 'categoryPage'){
    		$sql .= "tpk.page IN ('categoryPage','savedCoursesTab') AND ";
    	}
    	else 
    	{
    		$sql .= "tpk.page = '".$saFilterArray['pageName']."' AND ";
    	}
    }
    if(!empty($saFilterArray['country']) || $saFilterArray['country'] !=0)
    {
    	$countryWhereClause = "(prefCountry1 = ".$saFilterArray['country']." or prefCountry2 =".$saFilterArray['country']." or prefCountry3 = ".$saFilterArray['country'].") AND";
    	$sql .= $countryWhereClause;
    }
    //$sql .=  " rt.userType = 'abroad' AND ";
    if( ! empty($LDBwhereClause))
    {
    	$sql .= " desiredCourse IN (".$LDBwhereClause.") AND ";
    }
    if( ! empty($categoryWhereClause))
    {
    	$sql .= " categoryId =".$categoryWhereClause." AND ";
    }
    $sql .= " tpk.site = 'Study Abroad' AND ";

    if( ! empty($dateRangeArray['startDate']))
	{
		$sql .= " submitDate >='".$dateRangeArray['startDate']."' AND";
		
	}
	if( ! empty($dateRangeArray['endDate']))
	{
		$sql .= " submitDate <='".$dateRangeArray['endDate']."'";
	
	}
	return $sql;
}
function getStudyAbroadRegistrationSessionData($dateRangeArray = array(),$saFilterArray = array())
{
	$dbHandle = $this->getDbHandle('read');
	$sql = "select visitorSessionid as visitorsessionid,count(1) as count";
	$sql .= $this->getStudyAbroadRegistrationFilterQuery($dateRangeArray,$saFilterArray);
	$sql .= " AND rt.isNewReg = 'yes'";
	$sql .= " group by visitorsessionid";
	$result = $dbHandle->query($sql)->result_array();
	return $result;
}
function getLDBCourseIdForStudyAbroad($categoryId = '',$courseLevel='')
{
	if(!empty($categoryId) || $categoryId != 0)
	{
		if($courseLevel == '0')
		{
			$whereClause = "select SpecializationId from tCourseSpecializationMapping where categoryId =".$categoryId;
		}
		else
		{
			$whereClause = "select SpecializationId from tCourseSpecializationMapping where categoryId =".$categoryId." and CourseName='".$courseLevel."'";
		}	
	}
	elseif(!empty($courseLevel) || $courseLevel != 0) 
	{
		$whereClause = "select SpecializationId from tCourseSpecializationMapping where CourseName='".$courseLevel."'";
	}
	return $whereClause;													
}
function getRegistrationUserIds($dateRangeArray=array(),$filterArray=array())
{

	$checkForCourseLevel = 0;
	$dbHandle = $this->getDbHandle('read');
    /*$dbHandle->select('tpk.widget as widgetName,tpk.page as pageName,tpk.site as isNational,tpk.siteSource as device,time as responseDate');
    $dbHandle->from('tusersourceInfo tinfo');*/
    if( (! empty($filterArray['categoryId']) || ! empty($filterArray['courseLevel']) ) && ! empty($filterArray['country']))
    {
    	$dbHandle->select('distinct(tup.UserId) as UserId');
    	$dbHandle->from('tUserPref tup');
    	$dbHandle->join('tUserLocationPref tulp','tup.UserId = tulp.UserId','inner');
    }
    else if(! empty($filterArray['categoryId']) || ! empty($filterArray['courseLevel']))
    {
    	$dbHandle->select('distinct(tup.UserId) as UserId');
    	$dbHandle->from('tUserPref tup');
    }
    else if( ! empty($filterArray['country']))
    {
    	$dbHandle->select('distinct(tulp.UserId) as UserId');
    	$dbHandle->from('tUserLocationPref tulp');
    }
    //$dbHandle->join('tracking_pagekey tpk','tpk.id = tinfo.tracking_keyid','inner');
	global $studyAbroadAllDesiredCourses;
	$LDBCourses = $studyAbroadAllDesiredCourses;
    if( ! empty($filterArray))
	{
		foreach ($filterArray as $key => $value) {
			if(isset($key) && $value != '' && $value != '0')
			{
				switch($key)
					{
						case 'categoryId':
										if(in_array($value, $LDBCourses))
													$dbHandle->where('tup.DesiredCourse',$value);
										else
												{	
													//$category = $this->getStudyAbroadDesiredCourse($value,$filterArray['courseLevel']);
													$checkForCourseLevel = 1;
													if( $filterArray['courseLevel'] == '0')
													{
														$whereCondition = "select SpecializationId from tCourseSpecializationMapping where categoryId =".$value." and Status = 'live'";
													}
													else
													{
														$whereCondition = "select SpecializationId from tCourseSpecializationMapping where categoryId =".$value." and CourseName='".$filterArray['courseLevel']."' and Status = 'live'";	
													}
													
													$dbHandle->where('tup.DesiredCourse IN ('.$whereCondition.')');
    											}
    									break;
    					case 'courseLevel':
    										if($checkForCourseLevel == 0)
    										{
													$whereCondition = "select SpecializationId from tCourseSpecializationMapping where CourseName='".$filterArray['courseLevel']."' and Status = 'live'";
													$dbHandle->where('tup.DesiredCourse IN ('.$whereCondition.')');    											
    										}
    										break;
    					case 'country':
    									$dbHandle->where('tulp.CountryId',$value);
    									break;
    					/*case 'pageName':
    									$dbHandle->where('tpk.pageName',$value);
    									break;*/
    				}
    			}
    		}
    }
    if( ! empty($filterArray['categoryId']) || ! empty($filterArray['courseLevel']))
    {
    	$dbHandle->where('tup.ExtraFlag','studyabroad');
    	$dbHandle->where('tup.Status','live');	
    	if( ! empty($dateRangeArray['startDate']))
		{
			$dbHandle->where('tup.SubmitDate >=',$dateRangeArray['startDate'].' 00:00:00');
		}
		if( ! empty($dateRangeArray['endDate']))
		{
			$dbHandle->where('tup.SubmitDate <=',$dateRangeArray['endDate'].' 23:59:59');
		}
    }
    if( ! empty($filterArray['country']))
    {
    	$dbHandle->where('tulp.Status','live');
	    if( ! empty($dateRangeArray['startDate']))
		{
			$dbHandle->where('tulp.SubmitDate >=',$dateRangeArray['startDate'].' 00:00:00');
		}
		if( ! empty($dateRangeArray['endDate']))
		{
			$dbHandle->where('tulp.SubmitDate <=',$dateRangeArray['endDate'].' 23:59:59');
		}	
    }
	//$dbHandle->group_by('responseDate');
	$distinct_UserId = $dbHandle->get()->result_array();
	return $distinct_UserId;
}
/**
	* @param Array : list of UserIds
	* @return Array: Array contains name,email,mobile number and userId of given list of userId's
	*/
	function getRespondentsData($userId=array())
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('userid as Id,firstname as firstName,lastname as lastName,email as Email,mobile as MobileNumber');
		$dbHandle->from('tuser');
		$dbHandle->where('userid IN ('.implode(',',$userId).')');
		$result = $dbHandle->get()->result_array();
		
		return $result;
	}
	function getDiscussionDataBasedOnSubcatId($subcategoryId=array(),$authorId=array(),$source='',$startDate='',$endDate='',$viewWise=1)
	{

		//we can fetch based on country discussion
		/*messageCategoryTable,messageTable
		select * from messageTable mt JOIN messageCategoryTable mct ON mt.msgId = mct.threadId where 
		mt.fromOthers = 'discussion' and mct.categoryId = subcategoryId and 
		and status ='live' and mt.parentId=0;

		

		*/
		$dbHandle = $this->getDbHandle('read');
		if($viewWise == 1)
		{
			$dbHandle->select('date(mt.creationDate) as responseDate,count(distinct mt.msgId) as responsescount');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('week(date(mt.creationDate),1) as weekNumber,count(distinct mt.msgId) as responsescount',FALSE);
		}
		else
		{
			$dbHandle->select('date(mt.creationDate) as responseDate,month(date(mt.creationDate)) as monthNumber,count(distinct mt.msgId) as responsescount');
		}
		$dbHandle->from('messageTable mt');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = mt.tracking_keyid','inner');
		if( ! empty($subcategoryId))
			$dbHandle->join('messageCategoryTable mct','mct.threadId = mt.msgId','inner');
		$dbHandle->where('mt.fromOthers','discussion');
		if( ! empty($subcategoryId))
			$dbHandle->where('mct.categoryId IN ('.implode(',', $subcategoryId).')');
		if( ! empty($authorId))
			$dbHandle->where('mt.userId IN ('.implode(',', $authorId).')');
		$dbHandle->where('mt.parentId = mt.threadId');
		$dbHandle->where_in('status',array('live','closed'));
		/*$dbHandle->where('mt.tracking_keyid !=','0');
		$dbHandle->where('mt.tracking_keyid is not null');*/
		if( ! empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		if(!empty($startDate))
		{
			$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		}
		if( ! empty($endDate))
		{
			$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		}
		if($viewWise == 1)
	       	$dbHandle->group_by('responseDate');
	    else if($viewWise == 2)
	       	$dbHandle->group_by('weekNumber');
	    else
	    {
	       	$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('responseDate');
			$dbHandle->order_by('responseDate');
	    }

		$result = $dbHandle->get()->result_array();
		return $result;
	}
	/**
	* @param Array : discussionId
	* @param String : source,startDate,endDate
	* @param Integer : viewWise --- fetch data by day/week/month wise
	* @return Array : Number of comments are occured on the given discussion Ids  
	*/
	function getCommentDataOnDiscussionBasedOnIds($discussionId = array(),$source='',$startDate='',$endDate='',$viewWise = 1)
	{
		$dbHandle = $this->getDbHandle('read');
		if($viewWise == 1)
		{
			$dbHandle->select('date(mt.creationDate) as responseDate,count(distinct mt.msgId) as responsescount');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('week(date(mt.creationDate),1) as weekNumber,count(distinct mt.msgId) as responsescount',FALSE);
		}
		else
		{
			$dbHandle->select('date(mt.creationDate) as responseDate,month(date(mt.creationDate)) as monthNumber,count(distinct mt.msgId) as responsescount');
		}
		$dbHandle->from('messageTable mt');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = mt.tracking_keyid','inner');
		$dbHandle->where('mt.fromOthers','discussion');
		$dbHandle->where('mt.mainAnswerId = mt.parentId');
		$dbHandle->where('mainAnswerId >','0');
		$dbHandle->where('mt.threadId IN ('.implode(',', $discussionId).')');
		$dbHandle->where_in('status',array('live','closed'));
		/*$dbHandle->where('mt.tracking_keyid !=','0');
		$dbHandle->where('mt.tracking_keyid is not null');*/
		if( ! empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		if( ! empty($startDate))
		{
			$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		}
		if( ! empty($endDate))
		{
			$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		}
		if($viewWise == 1)
	       	$dbHandle->group_by('responseDate');
	    else if($viewWise == 2)
	       	$dbHandle->group_by('weekNumber');
	    else
	    {
	      	$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('responseDate');
			$dbHandle->order_by('responseDate');
	    }
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getArticleData($subcategoryId=array(),$authorId=array(),$startDate='',$endDate='',$viewWise=1)
	{
		$dbHandle = $this->getDbHandle('read');
		if($viewWise == 1)
		{
			$dbHandle->select('date(bt.creationDate) as responseDate,count(distinct bt.blogId) as responsescount');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('week(date(bt.creationDate),1) as weekNumber,count(distinct bt.blogId) as responsescount',FALSE);
		}
		else
		{
			$dbHandle->select('date(bt.creationDate) as responseDate,month(date(bt.creationDate)) as monthNumber,count(distinct bt.blogId) as responsescount');
		}
		$dbHandle->from('blogTable bt');
		
		if( ! empty($subcategoryId))
			$dbHandle->where('boardId IN ('.implode(',', $subcategoryId).')');

		
		if( ! empty($authorId))
		{
			$dbHandle->where('bt.userId IN ('.implode(',', $authorId).')');
		}
		$dbHandle->where('status','live');
		if( ! empty($startDate))
		{
			$dbHandle->where('bt.creationDate >=',$startDate.' 00:00:00');
		}
		if( ! empty($endDate))
		{
			$dbHandle->where('bt.creationDate <=',$endDate.' 23:59:59');
		}
		if($viewWise == 1)
		{
	        	$dbHandle->group_by('responseDate');
	    }
	        else if($viewWise == 2)
	        	$dbHandle->group_by('weekNumber');
	        else
	        {
	        	$dbHandle->group_by('monthNumber');
				$dbHandle->group_by('responseDate');
				$dbHandle->order_by('responseDate');
	        }
		$result = $dbHandle->get()->result_array();
		
		return $result;
	}
	function getCourseIdsBasedOnLDBCourseIds($ldbCourseId=array())
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('DISTINCT(clientCourseId)');
		$dbHandle->from('clientCourseToLDBCourseMapping');
		$dbHandle->where_in('LDBCourseID',$ldbCourseId);
		//$dbHandle->where('status','live');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getAuthorNames($userIdArray = array())
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('t.email as email,t.userid as userid,t.firstname as firstName,t.lastname as lastName');
		$dbHandle->from('tuser t');
		$dbHandle->where('userid IN ('.implode(',', $userIdArray).')');
		$dbHandle->where('t.firstname != \'\'');
		//$dbHandle->where('t.lastname != \'\'');
		return $dbHandle->get()->result_array();
	}
	function getAuthorUserIds()
	{
		$dbHandle = $this->getDbHandle('read');
		$sql = "select distinct(userid) from blogTable";
		$result = $dbHandle->query($sql);
		return $result->result_array();
	}
	function getStateNames($countryId=2)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('state_id as stateId, state_name as stateName');
		$dbHandle->from('stateTable');
		$dbHandle->where('countryId',$countryId);
		return $dbHandle->get()->result_array();
	}
	function getCountryNames()
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('countryId,name as countryName');
		$dbHandle->from('countryTable');
		$dbHandle->where_not_in('countryId',array('1','2'));
		return $dbHandle->get()->result_array();
	}
	function getCityBasedOnStates($stateId='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('city_id as cityId, city_name as cityName');
		$dbHandle->from('countryCityTable');
		if( ! empty($stateId))
			$dbHandle->where('state_id',$stateId);
		$dbHandle->where('countryId',2);
		$dbHandle->where_not_in('city_name',array('','All over India'));

		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getCoursesBasedOnLocation($courseId=array(),$city=array())
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('DISTINCT(clt.course_id) as courseId');
		$dbHandle->from('course_location_attribute clt');
		$dbHandle->join('institute_location_table inst','inst.institute_location_id = clt.institute_location_id','inner');
		$dbHandle->where('clt.course_id IN ('.implode(',', $courseId).')');
		if( ! empty($city))
			$dbHandle->where('inst.city_id IN ('.implode(',', $city).')');
		//$dbHandle->where('inst.status','live');
		//$dbHandle->where('clt.status','live');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getCoursesBasedOnSubCategory($subcategoryId=array())
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('DISTINCT(listing_type_id)');
		$dbHandle->from('listing_category_table');
		$dbHandle->where('category_id IN ('.implode(',', $subcategoryId).')');
		$dbHandle->where('listing_type','course');
		//$dbHandle->where('status','live');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getInstituteIdBasedOnCourseId($courseId=array())
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('DISTINCT(institute_id)');
		$dbHandle->from('course_details');
		$dbHandle->where('course_id IN ('.implode(',', $courseId).')');
		//$dbHandle->where('status','live');
		return $dbHandle->get()->result_array();
	}
	function getCommentDataOnArticle($subcategoryId = array(),$articleId =array(),$authorId = array(),$source='',$startDate='',$endDate='',$viewWise=1)
	{
		$dbHandle = $this->getDbHandle('read');
		
		if($viewWise == 1)
		{
			$dbHandle->select('date(mt.creationDate) as responseDate,count(distinct mt.msgId) as responsescount');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('week(date(mt.creationDate),1) as weekNumber,count(distinct mt.msgId) as responsescount',FALSE);
		}
		else
		{
			$dbHandle->select('date(mt.creationDate) as responseDate,month(date(mt.creationDate)) as monthNumber,count(distinct mt.msgId) as responsescount');
		}

		$dbHandle->from('messageTable mt');
		$dbHandle->join('blogTable b','b.discussionTopic = mt.parentId','inner');
		$dbHandle->join('tracking_pagekey tp','tp.id = mt.tracking_keyid','inner');
		$dbHandle->where('mt.fromOthers','blog');
		$dbHandle->where_in('mt.status',array('live','closed'));
		/*$dbHandle->where('mt.tracking_keyid !=','0');
		$dbHandle->where('mt.tracking_keyid is not null');*/
		//$dbHandle->where('b.discussionTopic !=','0');
		if( ! empty($articleId))
			$dbHandle->where('b.blogId IN ('.implode(',', $articleId).')');
		if( ! empty($subcategoryId))
			$dbHandle->where('b.boardId IN ('.implode(',', $subcategoryId).')');
		if( ! empty($authorId))
			$dbHandle->where('b.userId IN ('.implode(',', $authorId).')');
		$dbHandle->where('b.status','live');
		/*	if( ! empty($authorId))
		{
			$dbHandle->where('b.userId',$authorId);
		}*/
		if( !empty($source))
		{
			$dbHandle->where('tp.siteSource',$source);
		}
		if( ! empty($startDate))
		{
			$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		}
		if( ! empty($endDate))
		{
			$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		}
		if($viewWise == 1)
			$dbHandle->group_by('responseDate');
		else if($viewWise == 2)
			$dbHandle->group_by('weekNumber');
		else
		{ 
				$dbHandle->group_by('monthNumber');
				$dbHandle->group_by('responseDate');
				$dbHandle->order_by('responseDate');
		}
		$result = $dbHandle->get()->result_array();
		return $result;

	}
	function getReplyDataOnArticle($subcategoryId = array(),$articleId =array(),$authorId = array(),$source='',$startDate='',$endDate='',$viewWise=1)
	{
		$dbHandle = $this->getDbHandle('read');
		if($viewWise == 1)
		{
			$dbHandle->select('date(mt.creationDate) as responseDate,count(distinct mt.msgId) as responsescount');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('week(date(mt.creationDate),1) as weekNumber,count(distinct mt.msgId) as responsescount',FALSE);
		}
		else
		{
			$dbHandle->select('date(mt.creationDate) as responseDate,month(date(mt.creationDate)) as monthNumber,count(distinct mt.msgId) as responsescount');
		}
		
		$dbHandle->from('messageTable mt');
		$dbHandle->join('tracking_pagekey tp','tp.id = mt.tracking_keyid','inner');
		$dbHandle->join('blogTable b','b.discussionTopic = mt.threadId','inner');
		$dbHandle->where('mt.fromOthers','blog');
		$dbHandle->where('mt.parentId != mt.threadId');
		$dbHandle->where('mt.parentId !=','0');
		//$dbHandle->where('mt.mainAnswerId !=','0');
		$dbHandle->where_in('mt.status',array('live','closed'));
		/*$dbHandle->where('mt.tracking_keyid !=','0');
		$dbHandle->where('mt.tracking_keyid is not null');*/
		if( ! empty($articleId))
			$dbHandle->where('b.blogId IN ('.implode(',', $articleId).')');
		if( ! empty($subcategoryId))
			$dbHandle->where('b.boardId IN ('.implode(',', $subcategoryId).')');
		if( ! empty($authorId))
			$dbHandle->where('b.userId IN ('.implode(',', $authorId).')');
		$dbHandle->where('b.status','live');
		/*if( ! empty($authorId))
		{
			$dbHandle->where('b.userId',$authorId);
		}*/
		if( !empty($source))
		{
			$dbHandle->where('tp.siteSource',$source);
		}
		if( ! empty($startDate))
		{
			$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		}
		if( ! empty($endDate))
		{
			$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		}
		if($viewWise == 1)
			$dbHandle->group_by('responseDate');
		else if($viewWise == 2)
				$dbHandle->group_by('weekNumber');
		else
		{ 
			$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('responseDate');
			$dbHandle->order_by('responseDate');
		}
		$result = $dbHandle->get()->result_array();
		return $result;
	}

	function getArticleIds($subcategory=array(),$userId=array(),$startDate='',$endDate='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('blogId');
		$dbHandle->from('blogTable');
		if(! empty($subcategory))
			$dbHandle->where('boardId IN ('.implode(',', $subcategory).')');
		if( ! empty($userId))
			$dbHandle->where('userId IN ('.implode(',', $userId).')');
		/*if( ! empty($flag))
		{
			if($flag == 'old')
			{
				$dbHandle->where('date(creationDate) <',$startDate);
			}
			if($flag == 'new' )
			{
				$dbHandle->where('date(creationDate) >=',$startDate);
			}
		}*/
		if( ! empty($startDate))
			$dbHandle->where('creationDate >=',$startDate.' 00:00:00');
		if( ! empty($endDate))
			$dbHandle->where('creationDate <=',$endDate.' 23:59:59');
		$dbHandle->where('status','live');
		$result = $dbHandle->get()->result_array();
		return $result;
	}

	function getArticleIds_SA($subcategory=array(),$userId=array(),$startDate='',$flag='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('sa.content_id as contentId');
		$dbHandle->from('sa_content sa');
		if( !empty($subcategory) )
			$dbHandle->join('sa_content_course_mapping scm','sa.content_id = scm.content_id','inner');
		if( ! empty($subcategory))
			$dbHandle->where('scm.subcategory_id IN ('.implode(',', $subcategory).')');
		if( ! empty($userId))
			$dbHandle->where('sa.created_by IN ('.implode(',', $userId).')');
		if( ! empty($flag))
		{
			if($flag == 'old')
			{
				$dbHandle->where('sa.created_on <',$startDate.' 00:00:00');
			}
			if($flag == 'new' )
			{
				$dbHandle->where('sa.created_on >=',$startDate.' 23:59:59');
			}
		}

		$dbHandle->where('sa.type','article');
		$dbHandle->where('sa.status','live');
		if( !empty($subcategory) )
			$dbHandle->where('scm.status','live');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getAuthorUserIds_SA()
	{
	
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('DISTINCT(created_by)');
		$dbHandle->from('sa_content');
		$dbHandle->where('created_by !=','0');
		$result = $dbHandle->get()->result_array();
		
		return $result;
	}
	function getAuthorNames_SA($userIdArray=array())
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('t.userid as userid,t.firstname as firstName,t.lastname as lastName');
		$dbHandle->from('tuser t');
		$dbHandle->where_in('userid',$userIdArray);
		$dbHandle->where('t.firstname != \'\'');
		$dbHandle->where('t.lastname != \'\'');
		return $dbHandle->get()->result_array();
	}
	function getCommentDataOnArticle_SA($subcategoryId = array(),$articleId =array(),$authorId = array(),$source='',$startDate='',$endDate='',$viewWise=1)
	{
		//sa_comment_details
		$dbHandle = $this->getDbHandle('read');
		if($viewWise == 1)
		{
			$dbHandle->select('date(scd.comment_time) as responseDate,count(distinct scd.id) as responsescount');
		}
		else if($viewWise ==2)
		{
			$dbHandle->select('week(date(scd.comment_time),1) as weekNumber,count(distinct scd.id) as responsescount',FALSE);
		}
		else if($viewWise == 3)
		{
			$dbHandle->select('date(scd.comment_time) as responseDate,month(date(scd.comment_time)) as monthNumber,count(distinct scd.id) as responsescount');
		}

		$dbHandle->from('sa_comment_details scd');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = scd.tracking_keyid','inner');
		$dbHandle->join('sa_content sac','sac.content_id = scd.content_id','inner');
		if(! empty($subcategoryId ))
		{
			$dbHandle->join('sa_content_course_mapping scm','sac.content_id = scm.content_id','inner');
		}
		if( ! empty($articleId))
			$dbHandle->where('scd.content_id IN ('.implode(',', $articleId).')');
		if( ! empty($subcategoryId))
			$dbHandle->where('scm.subcategory_id IN ('.implode(',', $subcategoryId).')');
		if( !empty($authorId))
		{
			$dbHandle->where('sac.created_by IN ('.implode(',', $authorId).')');
		}
		$dbHandle->where('sac.type','article');
		$dbHandle->where('sac.status','live');
		
		if( ! empty($subcategoryId))
			$dbHandle->where('scm.status','live');
		$dbHandle->where('scd.parent_id','0');
		$dbHandle->where('scd.status','live');
		if( ! empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		if( ! empty($startDate))
			$dbHandle->where('scd.comment_time >=',$startDate.' 00:00:00');
		if( ! empty($endDate))
			$dbHandle->where('scd.comment_time <=',$endDate.' 23:59:59');
		/*$dbHandle->where('scd.tracking_keyid !=','0');
		$dbHandle->where('scd.tracking_keyid is not null');*/
		
		if($viewWise == 1)
	       	$dbHandle->group_by('responseDate');
	    else if($viewWise == 2)
	       	$dbHandle->group_by('weekNumber');
	    else
	    {
	      	$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('responseDate');
			$dbHandle->order_by('responseDate');
	    }

		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getReplyDataOnArticle_SA($subcategoryId = array(),$articleId =array(),$authorId= array(),$source='',$startDate='',$endDate='',$viewWise=1)
	{
		$dbHandle = $this->getDbHandle('read');
		if($viewWise == 1)
		{
			$dbHandle->select('date(scd.comment_time) as responseDate,count(distinct scd.id) as responsescount');
		}
		else if($viewWise ==2)
		{
			$dbHandle->select('WEEK(date(scd.comment_time),1) as weekNumber,count(distinct scd.id) as responsescount',FALSE);
		}
		else if($viewWise == 3)
		{
			$dbHandle->select('date(scd.comment_time) as responseDate,month(date(scd.comment_time)) as monthNumber,count(distinct scd.id) as responsescount');
		}

		$dbHandle->from('sa_comment_details scd');
		$dbHandle->join('tracking_pagekey tpk','tpk.id =scd.tracking_keyid','inner');
		$dbHandle->join('sa_content sac','sac.content_id = scd.content_id','inner');
		if(! empty($subcategoryId ))
		{
			$dbHandle->join('sa_content_course_mapping scm','sac.content_id = scm.content_id','inner');
		}
		if( ! empty($articleId))
			$dbHandle->where('scd.content_id IN ('.implode(',', $articleId).')');
		if( ! empty($subcategoryId))
			$dbHandle->where('scm.subcategory_id IN ('.implode(',', $subcategoryId).')');
		if( !empty($authorId))
		{
			$dbHandle->where('sac.created_by IN ('.implode(',', $authorId).')');
		}
		$dbHandle->where('sac.type','article');
		$dbHandle->where('sac.status','live');
		$dbHandle->where('scd.parent_id !=','0');
		$dbHandle->where('scd.status','live');
		if( ! empty($subcategoryId))
			$dbHandle->where('scm.status','live');
		if( ! empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		if( ! empty($startDate))
			$dbHandle->where('scd.comment_time >=',$startDate.' 00:00:00');
		if( ! empty($endDate))
			$dbHandle->where('scd.comment_time <=',$endDate.' 23:59:59');
		/*$dbHandle->where('scd.tracking_keyid !=','0');
		$dbHandle->where('scd.tracking_keyid is not null');*/
		
		if($viewWise == 1)
	       	$dbHandle->group_by('responseDate');
	    else if($viewWise == 2)
	       	$dbHandle->group_by('weekNumber');
	    else
	    {
	      	$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('responseDate');
			$dbHandle->order_by('responseDate');
	    }

		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getArticleData_SA($subcategoryId=array(),$authorId=array(),$startDate='',$endDate='',$viewWise=1)
	{
		$dbHandle = $this->getDbHandle('read');
		if($viewWise == 1)
		{
			$dbHandle->select('date(sac.created_on) as responseDate,count(distinct sac.id) as responsescount');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('week(date(sac.created_on),1) as weekNumber,count(distinct sac.id) as responsescount',FALSE);
		}
		else if($viewWise == 3)
		{
			$dbHandle->select('date(sac.created_on) as responseDate,month(date(sac.created_on)) as monthNumber,count(distinct sac.id) as responsescount');
		}
		$dbHandle->from('sa_content sac');
		if( ! empty($subcategoryId))
			$dbHandle->join('sa_content_course_mapping scm','sac.content_id = scm.content_id','inner');
		if( ! empty($subcategoryId))
			$dbHandle->where('scm.subcategory_id IN ('.implode(',', $subcategoryId).')');
		if( ! empty($authorId))
			$dbHandle->where('sac.created_by IN ('.implode(',', $authorId).')');
		if( ! empty($startDate))
		{
			$dbHandle->where('sac.created_on >=',$startDate.' 00:00:00');
		}
		if( ! empty($endDate))
		{
			$dbHandle->where('sac.created_on <=',$endDate.' 23:59:59');
		}
		$dbHandle->where('sac.type','article');
		$dbHandle->where('sac.status','live');
		if( ! empty($subcategoryId))
			$dbHandle->where('scm.status','live');
		if($viewWise == 1)
	       	$dbHandle->group_by('responseDate');
	    else if($viewWise == 2)
	       	$dbHandle->group_by('weekNumber');
	    else
	    {
	      	$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('responseDate');
			$dbHandle->order_by('responseDate');
	    }

		$result = $dbHandle->get()->result_array();
		
		return $result;
	}
	function getCustomerDeliveryRegistrations($instituteId=array(),$courseId=array(),$source='',$paidType='',$startDate='',$endDate='',$viewWise=1,$isStudyAbroad ='no')
	{
		$dbHandle = $this->getDbHandle('read');
		$pageNameArray = $this->getPageNames($source,$isStudyAbroad);
		$whereClause = '("'.implode('","', $pageNameArray).'") ';
		if($viewWise == 1)
		{
			$dbHandle->select('rt.submitDate as responseDate,count(1) as responsescount');
		}	
		else if($viewWise == 2)
		{
			$dbHandle->select('week(rt.submitDate,1) as weekNumber,count(1) as responsescount',FALSE);
		}
		else if($viewWise == 3)
		{
			$dbHandle->select('rt.submitDate as responseDate,month(submitDate) as monthNumber,count(1) as responsescount');
		}
		$dbHandle->from('registrationTracking rt');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = rt.trackingkeyId AND tpk.page IN '.$whereClause,'inner');
		$dbHandle->join('tuserflag tf','tf.userId = rt.userid AND tf.mobileverified = "1" AND tf.isTestUser = "NO"','inner');
		$dbHandle->join('tempLMSTable tlt','tlt.userid = rt.userid AND tlt.tracking_keyid = rt.trackingkeyId','inner');

		/*$dbHandle->where('tusi.tracking_keyid IN ('.implode(',', $keyIds).')');
		$dbHandle->where('tlt.tracking_keyid IN ('.implode(',', $keyIds).')');*/
		if( ! empty($paidType))
			$dbHandle->where('rt.source',$paidType);

		$dbHandle->where('rt.isNewReg','yes');
		if( ! empty($source))
			$dbHandle->where('tpk.siteSource',$source);

		$dbHandle->where('tlt.listing_type_id IN ('.implode(',', $courseId).')');
		$dbHandle->where('tlt.listing_type','course');

		$dbHandle->where('rt.submitDate >=',$startDate);
		$dbHandle->where('rt.submitDate <=',$endDate);
		$dbHandle->where('TIME_TO_SEC(TIMEDIFF(tlt.submit_date,rt.submitTime))<=','15');
		if($viewWise == 1)
			$dbHandle->group_by('responseDate');
		else if($viewWise == 2)
			$dbHandle->group_by('weekNumber');
		else if($viewWise == 3)
		{
			$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('responseDate');
			$dbHandle->order_by('responseDate');
		}
		$result = $dbHandle->get()->result_array();
		//_p(TIME_TO_SEC(TIMEDIFF('tlt.submit_date','tusi.time')));
		return $result;

	}
	function getTrackingKeyIdBasedOnPage($source,$isStudyAbroad='no',$pageName = 0,$deviceWise ='no',$keyName = '')
	{
		if($isStudyAbroad == 'no' &&  $pageName == 1)
		{
			if($source == 'Desktop')
			{
				$pageNames = array('courseDetailsPage','instituteListingPage');
			}
			else
			{
				$pageNames = array('courseDetailsPage','instituteListingPage','allCoursesPage');
			}
		}
			
		else if($isStudyAbroad == 'yes' && $pageName == 1)
			$pageNames = array('universityPage','coursePage');

		$dbHandle = $this->getDbHandle('read');
		if($deviceWise == 'no')
			$dbHandle->select('id');
		else if($deviceWise == 'yes')
			$dbHandle->select('siteSource,id');
		$dbHandle->from('tracking_pagekey');

		if( !empty($keyName))
			$dbHandle->where('keyName',$keyName);

		if( ! empty($source))
			$dbHandle->where('siteSource',$source);
		if( !empty($pageNames))
			$dbHandle->where_in('page',$pageNames);
		if($isStudyAbroad == 'no')
			$dbHandle->where('site !=','Study Abroad');
		else if($isStudyAbroad == 'yes')
			$dbHandle->where('site','Study Abroad');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getSubcategory($flag='national',$category ='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('boardId as subcatId,name as subcatName');
		$dbHandle->from('categoryBoardTable');
		$dbHandle->where('flag',$flag);
		if( ! empty($category))
			$dbHandle->where('parentId',$category);
		else
			$dbHandle->where('parentId >','1');
		$dbHandle->where('isOldCategory','0');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getArticlesPublishedByAuthor_SA($subcategory = array(),$startDate='',$endDate='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('sac.created_by as authorId, count(1) as articleCount');
		$dbHandle->from('sa_content sac');
		$dbHandle->join('sa_content_course_mapping scm','sac.content_id = scm.content_id','inner');
		$dbHandle->where('scm.subcategory_id IN ('.implode(',', $subcategory).')');
		$dbHandle->where('sac.type','article');
		$dbHandle->where('sac.status','live');
		$dbHandle->where('scm.status','live');
		$dbHandle->where('sac.created_on >=',$startDate.' 00:00:00');
		$dbHandle->where('sac.created_on <=',$endDate.' 23:59:59');
		$dbHandle->group_by('authorId');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getArticleDatabyAuthor($subcategory=array(),$startDate='',$endDate ='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('bt.userId as authorId,count(1) as articleCount');
		$dbHandle->from('blogTable bt');
		if( ! empty($subcategory))
			$dbHandle->where('bt.boardId IN ('.implode(',', $subcategory).')');
		$dbHandle->where('status','live');
		if( ! empty($startDate))
		{
			$dbHandle->where('bt.creationDate >=',$startDate.' 00:00:00');
		}
		if( ! empty($endDate))
		{
			$dbHandle->where('bt.creationDate <=',$endDate.' 23:59:59');
		}
		$dbHandle->group_by('authorId');
		$result = $dbHandle->get()->result_array();
		
		return $result;

	}
	function getDiscussionIdBasedOnUserId($subcategoryId= array(),$userId=array(),$startDate='',$endDate = '')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('distinct(mt.threadId) as discussionId');
		$dbHandle->from('messageTable mt');
		if( ! empty($subcategoryId))
			$dbHandle->join('messageCategoryTable mct','mct.threadId = mt.msgId','inner');
		$dbHandle->where('mt.fromOthers','discussion');
		if( ! empty($subcategoryId))
			$dbHandle->where('mct.categoryId IN ('.implode(',', $subcategoryId).')');
		$dbHandle->where('mt.parentId = mt.threadId');
		if( ! empty($userId))
			$dbHandle->where('userId IN ('.implode(',', $userId).')');
		if( ! empty($startDate))
			$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		if( ! empty($endDate))
			$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		$dbHandle->where_in('status',array('live','closed'));
		$result = $dbHandle->get()->result_array();
		return $result;
		
	}
	function getCoursesInUniversity($universityId='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('cd.course_id as courseId, cd.courseTitle as courseTitle');
		$dbHandle->from('course_details cd');
		$dbHandle->join('institute_university_mapping ium','cd.institute_id = ium.institute_id','inner');
		$dbHandle->where('ium.university_id',$universityId);
		//$dbHandle->where('ium.status','live');
		//$dbHandle->where('cd.status','live');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getUniversityBasedOnCountry($subcategoryId = array(),$countryId= array())
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('distinct(university_id) as universityId');
		$dbHandle->from('abroadCategoryPageData');
		if( ! empty($subcategoryId))
			$dbHandle->where('sub_category_id IN ('.implode(',', $subcategoryId).')');
		if( ! empty($countryId))
			$dbHandle->where('country_id IN ('.implode(',', $countryId).')');
		//$dbHandle->where('status','live');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getCoursesBasedOnCountry($subcategoryId = array(),$countryId = array())
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('DISTINCT(course_id) as courseId');
		$dbHandle->from('abroadCategoryPageData');
		if( ! empty($subcategoryId))
			$dbHandle->where('sub_category_id IN ('.implode(',', $subcategoryId).')');
		if( ! empty($countryId))
			$dbHandle->where('country_id IN ('.implode(',', $countryId).')');
		//$dbHandle->where('status','live');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getCoursesBasedOnCity($subcategoryId = array(),$cityId = array(),$stateId = array())
	{
		$dbHandle =  $this->getDbHandle('read');
		if( !empty($stateId) && ! empty($cityId))
		{
			$whereCondition = " AND (city_id IN(".implode(',', $cityId).") OR state_id IN (".implode(',', $stateId).")) ";
		}
		else if( ! empty($stateId))
		{
			$whereCondition = " AND state_id IN (".implode(',', $stateId).") ";
		}
		else if( ! empty($cityId))
		{
			$whereCondition = " AND city_id IN (".implode(',', $cityId).") ";
		}
		else
		{
			$whereCondition = " ";
		}
		$sql = "SELECT DISTINCT(course_id) as courseId FROM categoryPageData WHERE category_id IN (".implode(',', $subcategoryId).") ". $whereCondition;
		$result = $dbHandle->query($sql)->result_array();
		return $result;
	}
	function getInstitutesBasedOnCity($subcategoryId = array(),$cityId = array(),$stateId = array())
	{
		$dbHandle = $this->getDbHandle('read');
		$whereCondition = " ";
		if( ! empty($subcategoryId))
		{
			$whereCondition .= "category_id IN (".implode(',', $subcategoryId).") ";
		}
		if( !empty($stateId) && ! empty($cityId))
		{
			$whereCondition .= " AND (city_id IN(".implode(',', $cityId).") OR state_id IN (".implode(',', $stateId).")) ";
		}
		else if( ! empty($stateId))
		{
			$whereCondition .= " AND state_id IN (".implode(',', $stateId).") ";
		}
		else if( ! empty($cityId))
		{
			$whereCondition .= " AND city_id IN (".implode(',', $cityId).") ";
		}
		else
		{
			$whereCondition .= " ";
		}

		$sql = "SELECT DISTINCT(institute_id) as instituteId FROM categoryPageData WHERE ". $whereCondition;
		$result = $dbHandle->query($sql)->result_array();
		return $result;
	}
	function getResponsesForStudyAbroad($courseId = array(),$source='',$paidType='',$startDate='',$endDate='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('tLMS.userId as userId,tpk.keyName as type,tpk.siteSource as source,tLMS.listing_subscription_type as paidType, count(distinct tLMS.id) as responsescount');
		$dbHandle->from('tracking_pagekey tpk');
		$dbHandle->join('tempLMSTable tLMS','tpk.id = tLMS.tracking_keyid','inner');

		$dbHandle->where_in('tLMS.listing_type_id',$courseId);
		$dbHandle->where('tLMS.listing_type','course');
		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		if(!empty($paidType))
			$dbHandle->where('tLMS.listing_subscription_type',$paidType);
		
		/*$dbHandle->where('tLMS.tracking_keyid !=','0');
		$dbHandle->where('tLMS.tracking_keyid is not null');*/
		if(! empty($startDate))
			$dbHandle->where('tLMS.submit_date >=', $startDate.' 00:00:00');
		if(! empty($endDate))
	    	$dbHandle->where('tLMS.submit_date <=', $endDate.' 23:59:59');
		$dbHandle->group_by('type');
		$dbHandle->group_by('source');
		$dbHandle->group_by('paidType');
		$dbHandle->group_by('userId');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getRecentArticlesPostedBySubcat($startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('boardId as subcatId,count(1) as responsescount');
		$dbHandle->from('blogTable');
		$dbHandle->where('status','live');
		$dbHandle->where('creationDate >=',$startDate.' 00:00:00');
		$dbHandle->where('creationDate <=',$endDate.' 23:59:59');
		$dbHandle->group_by('subcatId');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getRecentArticlesPostedBySubcat_SA($startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('scm.subcategory_id as subcatId,count(distinct sa.content_id) as responsescount');
		$dbHandle->from('sa_content sa');
		$dbHandle->join('sa_content_course_mapping scm','sa.content_id = scm.content_id','left');
		$dbHandle->where('type','article');
		$dbHandle->where('sa.created_on >=',$startDate.' 00:00:00');
		$dbHandle->where('sa.created_on <=',$endDate.' 23:59:59');
		$dbHandle->where('sa.status','live');
		//$dbHandle->where('scm.status','live');
		$dbHandle->group_by('subcatId');	
		$result = $dbHandle->get()->result_array();
		return $result;	
	}
	function getRecentArticlesPostedInSA($startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('content_id');
		$dbHandle->from('sa_content');
		$dbHandle->where('type','article');
		$dbHandle->where('created_on >=',$startDate.' 00:00:00');
		$dbHandle->where('created_on <=',$endDate.' 23:59:59');
		$dbHandle->where('status','live');

		return $dbHandle->get()->result_array();
	}
	function getArticleTitles($articleId = array())
	{
	$dbHandle = $this->getDbHandle('read');
	$dbHandle->select('blogId,blogTitle,url');
	$dbHandle->from('blogTable');
	$dbHandle->where_in('blogId',$articleId);
	return $dbHandle->get()->result_array();
	}
	function getArticleDetails($articleId)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('b.blogId as blogId,b.blogTitle as title,b.url as url,t.firstname as firstName,t.lastname as lastName');
		$dbHandle->from('blogTable b');
		$dbHandle->join('tuser t','t.userid = b.userId','inner');
		$dbHandle->where('b.blogId IN ('.implode(',', $articleId).')');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getArticleDetails_SA($articleId)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('s.content_id as contentId,s.title as title,s.content_url as url,t.firstname as firstName,t.lastname as lastName');
		$dbHandle->from('sa_content s');
		$dbHandle->join('tuser t','s.created_by = t.userid','inner');
		$dbHandle->where('s.content_id IN ('.implode(',', $articleId).')');
		$dbHandle->where('s.type','article');
		return $dbHandle->get()->result_array();
	}
	function getTotalArticleCommentData($articleId = array(),$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');	
		$dbHandle->select('b.blogId as blogId,count(1) as commentCount');
		$dbHandle->from('messageTable mt');
		$dbHandle->join('blogTable b','b.discussionTopic = mt.parentId','inner');
		//$dbHandle->join('tracking_pagekey tp','tp.id = mt.tracking_keyid','inner');
		//$dbHandle->where('mt.mainAnswerId','-1');
		$dbHandle->where('mt.fromOthers','blog');
		$dbHandle->where_in('mt.status',array('live','closed'));
		/*$dbHandle->where('mt.tracking_keyid !=','0');
		$dbHandle->where('mt.tracking_keyid is not null');*/
		$dbHandle->where('b.blogId IN ('.implode(',', $articleId).')');
		if( ! empty($startDate))
		{
			$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		}
		if( ! empty($endDate))
		{
			$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		}
		$dbHandle->group_by('blogId');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getTotalArticleCommentData_SA($articleId,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('content_id as contentId,count(1) as commentCount');
		$dbHandle->from('sa_comment_details scd');
		//$dbHandle->join('tracking_pagekey tpk','tpk.id =scd.tracking_keyid','inner');
		$dbHandle->where('content_id IN ('.implode(',', $articleId).')');
		$dbHandle->where('scd.parent_id','0');
		$dbHandle->where('scd.status','live');
		if( ! empty($startDate))
			$dbHandle->where('scd.comment_time >=',$startDate.' 00:00:00');
		if( ! empty($endDate))
			$dbHandle->where('scd.comment_time <=',$endDate.' 23:59:59');
		$dbHandle->group_by('content_id');
		$result =  $dbHandle->get()->result_array();
		return $result;

	}
	function getArticlesPostedByAuthorInSA($startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('sac.created_by as authorId, count(1) as articleCount');
		$dbHandle->from('sa_content sac');
		$dbHandle->where('sac.type','article');
		$dbHandle->where('sac.status','live');
		$dbHandle->where('sac.created_on >=',$startDate.' 00:00:00');
		$dbHandle->where('sac.created_on <=',$endDate.' 23:59:59');
		$dbHandle->group_by('authorId');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getRecentArticlesPostedByAuthor($startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('bt.userId as authorId,count(1) as articleCount');
		$dbHandle->from('blogTable bt');	
		$dbHandle->where('status','live');
		$dbHandle->where('bt.creationDate >=',$startDate.' 00:00:00');
		$dbHandle->where('bt.creationDate <=',$endDate.' 23:59:59');
		$dbHandle->group_by('authorId');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getClientDelivery_AgentId_Type($clientId)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('searchagentid,clientid,type');
		$dbHandle->from('SASearchAgent');
		$dbHandle->where('clientid IN ('.implode(',', $clientId).')');
		$dbHandle->group_by('searchagentid');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getClientIdForInstitute($instituteId = array(),$type = 'institute')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('distinct(username) as clientId');
		$dbHandle->from('listings_main');
		$dbHandle->where('listing_type_id IN ('.implode(',', $instituteId).')');
		$dbHandle->where('listing_type',$type);
		//$dbHandle->where('status','live');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getLeadAllocation($agentId = array(),$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('agentid,date(allocationtime) as deliveryDate,count(1) as deliveryCount');
		$dbHandle->from('SALeadAllocation');
		$dbHandle->where('agentid IN ('.implode(',', $agentId).')');
		$dbHandle->where('allocationtime >=',$startDate.' 00:00:00');
		$dbHandle->where('allocationtime <=',$endDate.' 23:59:59');
		$dbHandle->group_by('agentid');
		$dbHandle->group_by('deliveryDate');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getActualDeliveryToClient($agentId=array(),$startDate='',$endDate='',$viewWise=1)
	{
		$dbHandle = $this->getDbHandle('read');
		if($viewWise == 1)
		{
			$dbHandle->select('agentid,date(allocationtime) as deliveryDate,count(1) as deliveryCount');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('agentid,WEEK(date(allocationtime),1) as weekNumber, count(1) as deliveryCount',FALSE);
		}
		else
		{
			$dbHandle->select('agentid,date(allocationtime) as deliveryDate,month(date(allocationtime)) as monthNumber,count(1) as deliveryCount');
		}
		$dbHandle->from('SALeadAllocation');
		$dbHandle->where('agentid IN ('.implode(',', $agentId).')');
		$dbHandle->where('allocationtime >=', $startDate.' 00:00:00');
		$dbHandle->where('allocationtime <=', $endDate.' 23:59:59');
		$dbHandle->group_by('agentid');
		if($viewWise == 1)
			$dbHandle->group_by('deliveryDate');
		else if($viewWise == 2)
			$dbHandle->group_by('weekNumber');
		else
		{
			$dbHandle->group_by('deliveryDate');
			$dbHandle->group_by('monthNumber');
			$dbHandle->order_by('deliveryDate');
		}
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getDiscussionPostedInSubcat($startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('mct.categoryId as subcatId,count(1) as responsescount');
		$dbHandle->from('messageTable mt');
		$dbHandle->join('messageCategoryTable mct','mct.threadId = mt.msgId','inner');
		$dbHandle->where('mt.fromOthers','discussion');
		$dbHandle->where('mt.parentId = mt.threadId');
		$dbHandle->where_in('status',array('live','closed'));
		$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		$dbHandle->group_by('subcatId');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getDiscussionSplitByAuthor($startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('userId as authorId,count(1) as articleCount');
		$dbHandle->from('messageTable');
		$dbHandle->where('fromOthers','discussion');
		$dbHandle->where('parentId = threadId');
		$dbHandle->where_in('status',array('live','closed'));
		$dbHandle->where('creationDate >=',$startDate.' 00:00:00');
		$dbHandle->where('creationDate <=',$endDate.' 23:59:59');
		$dbHandle->group_by('authorId');
		$result = $dbHandle->get()->result_array();
		return $result;

	}
	function getUserId_by_Email($emailId)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('userid');
		$dbHandle->from('tuser');
		$whereClause = ' ("'.implode('","', $emailId).'") ';
		$dbHandle->where('email IN '.$whereClause);
		return $dbHandle->get()->result_array();
	}
	function getInstituteName($instituteId,$type='institute')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('listing_title');
		$dbHandle->from('listings_main');
		$dbHandle->where('listing_type_id',$instituteId);
		$dbHandle->where('listing_type',$type);
		//$dbHandle->where('status','live');
		$instituteName =  $dbHandle->get();
		$instituteName = $instituteName->row();
		return $instituteName->listing_title;
	}
	function getTotalCommentsOnDiscussionIds($discussionId,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('mt.threadId as discussionId,count(1) as commentCount');
		$dbHandle->from('messageTable mt');
		$dbHandle->where('mt.fromOthers','discussion');
		$dbHandle->where('mt.mainAnswerId = mt.parentId');
		$dbHandle->where('mainAnswerId >','0');
		$dbHandle->where('mt.threadId IN ('.implode(',', $discussionId).')');
		$dbHandle->where_in('status',array('live','closed'));
		/*$dbHandle->where('mt.tracking_keyid !=','0');
		$dbHandle->where('mt.tracking_keyid is not null');*/
		$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		$dbHandle->group_by('discussionId');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getUserIdsForDiscussionIds($discussionId)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('userId,msgId as discussionId');
		$dbHandle->from('messageTable');
		$dbHandle->where('msgId IN ('.implode(',', $discussionId).')');
		return $dbHandle->get()->result_array();
	}
	function getSubcategoryId($category ='',$flag='national')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('boardId as subcatId');
		$dbHandle->from('categoryBoardTable');
		$dbHandle->where('flag',$flag);
		$dbHandle->where('parentId IN ('.implode(',', $category).')');
		$dbHandle->where('isOldCategory',0);
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getInstituteClientIds($instituteId = '',$listing_type = 'institute')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('distinct(username)');
		$dbHandle->from('listings_main');
		$dbHandle->where('listing_type',$listing_type);
		if( ! empty($instituteId))
			$dbHandle->where('listing_type_id IN ('.implode(',', $instituteId).')');
		//$dbHandle->where('status','live');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getDeliveryToClient($agentId,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('agentid,count(1) as deliveryCount');
		$dbHandle->from('SALeadAllocation force index(keyagentid)');
		$dbHandle->where('agentid IN ('.implode(',', $agentId).')');
		$dbHandle->where('allocationtime >=', $startDate.' 00:00:00');
		$dbHandle->where('allocationtime <=', $endDate.' 23:59:59');
		$dbHandle->group_by('agentid');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getCityBasedOnZone($stateId=array())
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('city_id as cityId');
		$dbHandle->from('countryCityTable');
		$dbHandle->where('state_id IN ('.implode(',', $stateId).')');
		$dbHandle->where('countryId',2);
		$dbHandle->where_not_in('city_name',array('','All over India'));
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getClientId_InstituteNames($clientId='',$listing_type = 'institute')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('username,group_concat(listing_title) as instituteName');
		$dbHandle->from('listings_main');
		$dbHandle->where('listing_type',$listing_type);
		if( ! empty($clientId))
			$dbHandle->where('username IN ('.implode(',', $clientId).')');
		//$dbHandle->where('status','live');
		$dbHandle->group_by('username');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getTotalSales($clientId,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('SUM(TotalTransactionPrice) as price');
		$dbHandle->from('SUMS.Transaction force index(ClientUserId)');
		$dbHandle->where('ClientUserId IN ('.implode(',', $clientId).')');
		$dbHandle->where('TransactTime >=', $startDate.' 00:00:00');
		$dbHandle->where('TransactTime <=', $endDate.' 23:59:59');
		$result =$dbHandle->get();
		$result = $result->row();
		return $result->price;
	}
	function getSalesPerClient($clientId,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('ClientUserId,SUM(TotalTransactionPrice) as price');
		$dbHandle->from('SUMS.Transaction force index(ClientUserId)');
		$dbHandle->where('ClientUserId IN ('.implode(',', $clientId).')');
		$dbHandle->where('TransactTime >=', $startDate.' 00:00:00');
		$dbHandle->where('TransactTime <=', $endDate.' 23:59:59');
		$dbHandle->group_by('ClientUserId');
		$dbHandle->order_by('price','desc');
		$dbHandle->limit(100,0);
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getTransactionId_Paid($startDate,$endDate)
	{	
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('p.Transaction_Id as transactionId,sum(pd.Amount_Received) as payment',TRUE);
		$dbHandle->from('SUMS.Payment p');
		$dbHandle->join('SUMS.Payment_Details pd force index(idx_p_id)','p.Payment_Id = pd.Payment_Id','inner');
		$dbHandle->where('pd.Payment_Modify_Date >=',$startDate.' 00:00:00');
		$dbHandle->where('pd.Payment_Modify_Date <=',$endDate.' 23:59:59');
		$dbHandle->group_by('transactionId');
		$dbHandle->where('pd.Payment_Id !=','0');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getClientIdForTransactionId($transactionId)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('TransactionId,ClientUserId');
		$dbHandle->from('SUMS.Transaction');
		$dbHandle->where('TransactionId IN ('.implode(',', $transactionId).')');
		$dbHandle->group_by('TransactionId');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getTotalCollections($startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('sum(Amount_Received) as payment');
		$dbHandle->from('SUMS.Payment_Details');
		$dbHandle->where('Payment_Modify_Date >=',$startDate.' 00:00:00');
		$dbHandle->where('Payment_Modify_Date <=',$endDate.' 23:59:59');
		$result = $dbHandle->get();
		$result = $result->row();
		return $result->payment;
	}
	function getZoneWiseTransactions($clientId)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('distinct(TransactionId) as transactionId');
		$dbHandle->from('SUMS.Transaction force index(ClientUserId)');
		$dbHandle->where('ClientUserId IN ('.implode(',', $clientId).')');
		$result = $dbHandle->get()->result_array();
		return $result;

	}
	function getZoneWiseCollections($transactionId,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('sum(pd.Amount_Received) as payment');
		$dbHandle->from('SUMS.Payment p');
		$dbHandle->join('SUMS.Payment_Details pd force index(idx_p_id)','p.Payment_Id = pd.Payment_Id','inner');
		$dbHandle->where('transaction_Id IN ('.implode(',', $transactionId).')');
		$dbHandle->where('pd.Payment_Modify_Date >=',$startDate.' 00:00:00');
		$dbHandle->where('pd.Payment_Modify_Date <=',$endDate.' 23:59:59');
		$result = $dbHandle->get();
		$result = $result->row();
		return $result->payment;
	}
	function getClientGenies($clientId,$flag = 1)
	{
		$dbHandle = $this->getDbHandle('read');
		if($flag == 1)
			$dbHandle->select('clientid,searchagentid,type,deliveryMethod');
		else 
			$dbHandle->select('clientid,searchagentid,deliveryMethod');
		$dbHandle->from('SASearchAgent force index(idx_clientid)');
		$dbHandle->where('clientid IN ('.implode(',', $clientId).')');
		$dbHandle->group_by('clientid');
		$dbHandle->group_by('searchagentid');
		if($flag == 1)
			$dbHandle->group_by('type');
		$dbHandle->group_by('deliveryMethod');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getDeliveryDataByEmailGenies($email_genie_id,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('agentid,userid');
		$dbHandle->from('SALeadAllocation force index(keyagentid)');
		$dbHandle->where('agentid IN ('.implode(',', $email_genie_id).')');
		$dbHandle->where('allocationtime >=',$startDate.' 00:00:00');
		$dbHandle->where('allocationtime <=',$endDate.' 23:59:59');
		$result = $dbHandle->get()->result_array();
		return$result;

	}
	function getDeliveryDataByPortingGenies($porting_genie_id,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('leadid as userid,searchagentid as agentid');
		$dbHandle->from('SALeadMatchingLog force index(searchagentid)');
		$dbHandle->where('searchagentid IN ('.implode(',', $porting_genie_id).')');
		$dbHandle->where('matchingTime >=',$startDate.' 00:00:00');
		$dbHandle->where('matchingTime <=',$endDate.' 23:59:59');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getDeliveryDataByView($clientid,$startDate,$endDate,$leadFlag = 1,$flag = 'national')
	{
		//die;
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('lt.ClientId as clientid,lt.UserId as userid');
		$dbHandle->from('LDBLeadContactedTracking lt');
		$dbHandle->join('LDBLeadViewCount lv force index(UserId)','lv.UserId = lt.UserId','inner');
		$dbHandle->where('ClientId IN ('.implode(',', $clientid).')');
		$dbHandle->where('lv.Flag',$flag);
		if($leadFlag == 1)
		{
			$dbHandle->where_not_in('lv.DesiredCourse',array(2,56));
		}
		else
		{
			$dbHandle->where_in('lv.DesiredCourse',array(2,56));
		}

		$dbHandle->where('ContactDate >=',$startDate.' 00:00:00');
		$dbHandle->where('ContactDate <=',$endDate.' 23:59:59');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getUserIdForClientId($clientId,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		/*$dbHandle->select(' ClientId as clientid,UserId as userid');
		$dbHandle->from('LDBLeadContactedTracking');
		$dbHandle->where_in('ClientId',$clientId, TRUE);
		$dbHandle->where('activity_flag','LDB');
		$dbHandle->where('ContactDate >=',$startDate.' 00:00;00');
		$dbHandle->where('ContactDate <=',$endDate.' 23.59.59');*/
		$sql  = "SELECT ClientId as clientid,UserId as userid FROM LDBLeadContactedTracking WHERE ClientId IN (".implode(',', $clientId).") AND activity_flag = 'LDB' AND ContactDate >= '".$startDate." 00:00:00' AND ContactDate <= '".$endDate." 23:59:59'";
		//$result = $dbHandle->get()->result_array();
		$result = $dbHandle->query($sql)->result_array();
		//_p('getUserIdForClientId'.$dbHandle->last_query());
		return $result;
	}
	function getUserIdDeliveryByView($clientId,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		/*$dbHandle->select(' ClientId as clientid,UserId as userid');
		$dbHandle->from('LDBLeadContactedTracking');
		$dbHandle->where_in('ClientId',$clientId, TRUE);
		$dbHandle->where('activity_flag','LDB');
		$dbHandle->where('ContactDate >=',$startDate.' 00:00;00');
		$dbHandle->where('ContactDate <=',$endDate.' 23.59.59');*/
		$sql  = "SELECT UserId as userid FROM LDBLeadContactedTracking WHERE ClientId IN (".implode(',', $clientId).") AND ContactDate >= '".$startDate." 00:00:00' AND ContactDate <= '".$endDate." 23:59:59'";
		//$result = $dbHandle->get()->result_array();
		$result = $dbHandle->query($sql)->result_array();
		return $result;
	}
	function DeliveryByView1($userId,$leadFlag = 1,$flag='national')
	{
		$dbHandle = $this->getDbHandle('read');
	/*
		$dbHandle->select(' UserId as userid');
		$dbHandle->from('LDBLeadViewCount');
		$dbHandle->where_in('UserId',$userId, TRUE);
		$dbHandle->where('flag',$flag);
		if($leadFlag == 1)
		{
			$dbHandle->where_not_in('DesiredCourse',array(2,56));
		}
		else
		{
			$dbHandle->where_in('DesiredCourse',array(2,56));
		}
		$result = $dbHandle->get()->result_array();*/
		//
		if($leadFlag == 1)
		{
			$whereCondition = "DesiredCourse NOT IN (2,56)";
		}
		else
		{
			$whereCondition = "DesiredCourse IN (2,56)";	
		}
		$sql = "SELECT UserId as userid FROM LDBLeadViewCount WHERE UserId IN (".implode(',', $userId).") AND flag ='".$flag."' and ".$whereCondition;
		$result = $dbHandle->query($sql)->result_array();
		return $result;
	}
	function DeliveryByViewForTopTile($clientId,$startDate,$endDate,$leadFlag = 1,$flag='national')
	{
		$dbHandle = $this->getDbHandle('read');
		if($leadFlag == 1)
		{
			$whereCondition = "DesiredCourse NOT IN (2,56)";
		}
		else
		{
			$whereCondition = "DesiredCourse IN (2,56)";	
		}
		//$sql = "SELECT count(UserId) as count FROM LDBLeadViewCount WHERE UserId IN (".implode(',', $userId).") AND flag ='".$flag."' and ".$whereCondition;

		$sql = "SELECT count(1) as count FROM LDBLeadContactedTracking l INNER JOIN LDBLeadViewCount v ON l.UserId = v.UserId WHERE l.ClientId IN (".implode(',', $clientId).") AND flag ='".$flag."' and ".$whereCondition." AND l.ContactDate >= '".$startDate." 00:00:00' AND l.ContactDate <= '".$endDate." 23:59:59'";
		$result = $dbHandle->query($sql)->result_array();
		return $result;
	}
	function DeliveryByView($clientId,$startDate,$endDate,$leadFlag = 1,$flag='national')
	{
		$dbHandle = $this->getDbHandle('read');
		if($leadFlag == 1)
		{
			$whereCondition = "DesiredCourse NOT IN (2,56)";
		}
		else
		{
			$whereCondition = "DesiredCourse IN (2,56)";	
		}
		//$sql = "SELECT count(UserId) as count FROM LDBLeadViewCount WHERE UserId IN (".implode(',', $userId).") AND flag ='".$flag."' and ".$whereCondition;

		$sql = "SELECT l.clientId as clientid,v.UserId as userid FROM LDBLeadContactedTracking l INNER JOIN LDBLeadViewCount v ON l.UserId = v.UserId WHERE l.ClientId IN (".implode(',', $clientId).") AND flag ='".$flag."' and ".$whereCondition." AND l.activity_flag = 'LDB' AND l.ContactDate >= '".$startDate." 00:00:00' AND l.ContactDate <= '".$endDate." 23:59:59'";
		$result = $dbHandle->query($sql)->result_array();
		return $result;
	}
	function getLeadGenies($clientId)
	{
		$dbHandle = $this->getDbHandle('read');
		$sql = "SELECT searchagentid,deliveryMethod FROM SASearchAgent force index(idx_clientid) WHERE clientid IN (".implode(',', $clientId).") AND type = 'lead' GROUP BY searchagentid,deliveryMethod";
			$result = $dbHandle->query($sql)->result_array();
		return $result;
	}
	function getResponseGenies($clientId)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('searchagentid,deliveryMethod');
		$dbHandle->from('SASearchAgent force index(idx_clientid)');
		$dbHandle->where('clientid IN ('.implode(',', $clientId).')');
		$dbHandle->where('type','response');
		$dbHandle->group_by('searchagentid');
		$dbHandle->group_by('deliveryMethod');
		$result = $dbHandle->get()->result_array();
		return $result;	
	}
	function getDeliveryByEmailGenies($email_genie_id,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$sql = "SELECT count(distinct userid) as count FROM SALeadAllocation force index(keyagentid) WHERE agentid IN (".implode(',',$email_genie_id).") AND allocationtime >='".$startDate." 00:00:00' AND allocationtime <='".$endDate." 23:59:59'";
		$result = $dbHandle->query($sql)->result_array();
		return$result;
	}
	function getDeliveryByPortingGenies($porting_genie_id,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$sql = "SELECT count(distinct leadid) as count FROM SALeadMatchingLog force index(searchagentid) WHERE searchagentid IN (".implode(',', $porting_genie_id).") AND matchingTime >='".$startDate." 00:00:00' AND matchingTime <='".$endDate." 23:59:59'";
			$result = $dbHandle->query($sql)->result_array();
		return $result;
	}
	function getClientGeniesForSite($type='lead')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('searchagentid,deliveryMethod');
		$dbHandle->from('SASearchAgent force index(idx_clientid)');
		$dbHandle->join('listings_main l','l.username = clientid','inner');
		$dbHandle->where('listing_type','institute');
		$dbHandle->where('status','live');
		$dbHandle->where('type',$type);
		$dbHandle->group_by('searchagentid');
		$dbHandle->group_by('deliveryMethod');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getUserIdForClientId1($clientId,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('ClientId as clientid,UserId as userid');
		$dbHandle->from('LDBLeadContactedTracking force index(keyClientId)');
		$dbHandle->where_in('ClientId',$clientId);
		$dbHandle->where('activity_flag','LDB');
		$dbHandle->where('ContactDate >=',$startDate.' 00:00;00');
		$dbHandle->where('ContactDate <=',$endDate.' 23.59.59');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getDeliveryDataByEmailGeniesForLineChart($email_genie_id,$startDate,$endDate,$viewWise=1)
	{
		$dbHandle = $this->getDbHandle('read');
		if($viewWise == 1)
		{
			$dbHandle->select('date(allocationtime) as responseDate,userid');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('week(date(allocationtime),1) as weekNumber,userid',FALSE);
		}
		else 
		{
			$dbHandle->select('date(allocationtime) as responseDate,month(date(allocationtime)) as monthNumber,userid');
		}
		$dbHandle->from('SALeadAllocation force index(keyagentid)');
		$dbHandle->where('agentid IN ('.implode(',', $email_genie_id).')');
		$dbHandle->where('allocationtime >=',$startDate.' 00:00:00');
		$dbHandle->where('allocationtime <=',$endDate.' 23:59:59');
		if($viewWise == 1)
		{
			$dbHandle->group_by('responseDate');
			$dbHandle->group_by('userid');
		}
		else if($viewWise == 2)
		{
			$dbHandle->group_by('weekNumber');
			$dbHandle->group_by('userid');
		}
		else 
		{
			$dbHandle->group_by('responseDate');
			$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('userid');
			$dbHandle->order_by('responseDate');
		}
		$result = $dbHandle->get()->result_array();
		return$result;
	}
	function getDeliveryDataByPortingGeniesForLineChart($porting_genie_id,$startDate,$endDate,$viewWise = 1)
	{
		$dbHandle = $this->getDbHandle('read');
		if($viewWise == 1)
		{
			$dbHandle->select('date(matchingTime) as responseDate,leadid as userid');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('week(date(matchingTime),1) as weekNumber,leadid as userid',FALSE);
		}
		else
		{
			$dbHandle->select('date(matchingTime) as responseDate,month(date(matchingTime)) as monthNumber,leadid as userid');
		}
		$dbHandle->from('SALeadMatchingLog force index(searchagentid)');
		$dbHandle->where('searchagentid IN ('.implode(',', $porting_genie_id).')');
		$dbHandle->where('matchingTime >=',$startDate.' 00:00:00');
		$dbHandle->where('matchingTime <=',$endDate.' 23:59:59');
		if($viewWise == 1)
		{
			$dbHandle->group_by('responseDate');
			$dbHandle->group_by('userid');
		}
		else if($viewWise == 2)
		{
			$dbHandle->group_by('weekNumber');
			$dbHandle->group_by('userid');
		}
		else
		{
			$dbHandle->group_by('responseDate');
			$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('userid');
			$dbHandle->order_by('responseDate');
		}
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getViewDeliveryForLineChart($clientId,$startDate,$endDate,$viewWise=1,$leadFlag = 1,$flag = 'national')
	{
		$dbHandle = $this->getDbHandle('read');
		if($viewWise == 1)
		{
			$dbHandle->select('date(l.ContactDate) as responseDate,v.UserId as userid');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('week(date(l.ContactDate),1) as weekNumber,v.UserId as userid',FALSE);
		}
		else
		{
			$dbHandle->select('date(l.ContactDate) as responseDate,month(date(l.ContactDate)) as monthNumber,v.UserId as userid');
		}
		$dbHandle->from('LDBLeadContactedTracking l');
		$dbHandle->join('LDBLeadViewCount v','l.UserId = v.UserId','inner');
		$dbHandle->where('l.ClientId IN ('.implode(',', $clientId).')');
		if($leadFlag == 1)
		{
			$dbHandle->where_not_in('v.DesiredCourse',array(2,56));
		}
		else
		{
			$dbHandle->where_in('v.DesiredCourse',array(2,56));
		}
		$dbHandle->where('l.ContactDate >=',$startDate.' 00:00:00');
		$dbHandle->where('l.ContactDate <=', $endDate.' 23:59:59');
		$dbHandle->where('l.activity_flag','LDB');
		if( $flag != 'both')
		{
			$dbHandle->where('v.Flag',$flag);	
		}
		if($viewWise == 1)
		{
			$dbHandle->group_by('responseDate');
			$dbHandle->group_by('userid');
		}
		else if($viewWise == 2)
		{
			$dbHandle->group_by('weekNumber');
			$dbHandle->group_by('userid');
		}
		else
		{
			$dbHandle->group_by('responseDate');
			$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('userid');
			$dbHandle->order_by('responseDate');
		}
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getRespondentsForResponsesData($courseId = array(),$source = '',$paidType = '',$startDate ='',$endDate ='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('tLMS.userId as userId,tLMS.visitorsessionid as visitorsessionid');
		$dbHandle->from('tempLMSTable tLMS');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = tLMS.tracking_keyid','inner');
		$dbHandle->where('tLMS.listing_type_id IN ('.implode(',', $courseId).')');
		$dbHandle->where('tLMS.listing_type','course');
		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		if(!empty($paidType))
			$dbHandle->where('tLMS.listing_subscription_type',$paidType);
		/*$dbHandle->where('tLMS.tracking_keyid !=','0');
		$dbHandle->where('tLMS.tracking_keyid is not null');*/
		$dbHandle->where('tLMS.submit_date >=', $startDate.' 00:00:00');
		$dbHandle->where('tLMS.submit_date <=', $endDate.' 23:59:59');	
		$dbHandle->limit(300,0);	
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getSourceForSessionId($sessionId = array())
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('source,sessionId');
		$dbHandle->from('session_tracking');
		$dbHandle->where_in('sessionId',$sessionId);
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getCommentDataBasedOnsubCatId($subcategoryId=array(),$source='',$startDate='',$endDate='',$viewWise=1)
	{
		$dbHandle = $this->getDbHandle('read');
		if($viewWise == 1)
		{
			$dbHandle->select('date(mt.creationDate) as responseDate,count(1) as responsescount');
		}
		else if($viewWise == 2)
		{
			$dbHandle->select('week(date(mt.creationDate),1) as weekNumber,count(1) as responsescount',FALSE);
		}
		else
		{
		$dbHandle->select('date(mt.creationDate) as responseDate,month(date(mt.creationDate)) as monthNumber,count(1) as responsescount');
		}
		$dbHandle->from('messageTable mt');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = mt.tracking_keyid','inner');
		$dbHandle->join('messageCategoryTable mct','mct.threadId = mt.threadId','inner');
		if( ! empty($subcategoryId))
		{
			$dbHandle->where('mct.categoryId IN ('.implode(',', $subcategoryId).')');
		}
		$dbHandle->where('mt.fromOthers','discussion');
		$dbHandle->where('mt.mainAnswerId = mt.parentId');
		$dbHandle->where('mainAnswerId >','0');
		$dbHandle->where_in('status',array('live','closed'));
		/*$dbHandle->where('mt.tracking_keyid !=','0');
		$dbHandle->where('mt.tracking_keyid is not null');*/
		if( ! empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		
		$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		
		if($viewWise == 1)
        	$dbHandle->group_by('responseDate');
        else if($viewWise == 2)
        	$dbHandle->group_by('weekNumber');
        else
        {
        	$dbHandle->group_by('monthNumber');
			$dbHandle->group_by('responseDate');
			$dbHandle->order_by('responseDate');
        }
		$result = $dbHandle->get()->result_array();
		return $result;

	}
	function getRegistrationsDataForToptile($courseId=array(),$source='',$paidType = '',$startDate='',$endDate='',$isStudyAbroad='no')
	{
		$dbHandle = $this->getDbHandle('read');
		$pageNameArray = $this->getPageNames($source,$isStudyAbroad);
		$whereClause = ' ("'.implode('","', $pageNameArray).'") ';
		if( ! empty($source))
			$whereClause .= " AND tpk.siteSource = '".$source."'";
		if( ! empty($paidType))
			$whereClause .= " AND rt.source = '".$paidType."'";
		else
			$whereClause .= " ";
		//$sql = "SELECT count(1) as count FROM tusersourceInfo tusi INNER JOIN tracking_pagekey tpk ON tpk.id = tusi.tracking_keyid AND tpk.page IN ".$whereClause." INNER JOIN tuserflag tf ON tf.userId = tusi.userid AND tf.mobileverified = '1' AND tf.isTestUser = 'NO' INNER JOIN tempLMSTable tlt ON tlt.userid = tusi.userid and tpk.id = tlt.tracking_keyid WHERE tlt.listing_type_id IN (".implode(',', $courseId).") AND tlt.listing_type = 'course' AND tusi.time >='".$startDate." 00:00:00' AND tusi.time <='".$endDate." 23:59:59' AND TIME_TO_SEC(TIMEDIFF(tlt.submit_date,tusi.time))<= 15";
		/*_p($sql);
		die;*/

		$sql = "SELECT count(1) as count FROM registrationTracking rt INNER JOIN tracking_pagekey tpk ON tpk.id = rt.trackingkeyId INNER JOIN tuserflag tf ON tf.userId = rt.userId AND tf.mobileverified = '1' AND tf.isTestUser = 'NO' INNER JOIN tempLMSTable tlt ON tlt.userid = rt.userId  AND tlt.tracking_keyid = rt.trackingkeyId WHERE tlt.listing_type_id IN (".implode(',', $courseId).") AND tpk.page IN ".$whereClause." AND tlt.listing_type = 'course' AND rt.submitDate >='".$startDate."' AND rt.submitDate <='".$endDate."' AND rt.isNewReg = 'yes' AND TIME_TO_SEC(TIMEDIFF(tlt.submit_date,rt.submitTime))<= 15";
		$result = $dbHandle->query($sql);
		$result = $result->row();
		$result = $result->count;
		return $result;
	}
	function getQuestionsCountForTopTile($courseId = array(),$source = '',$startDate = '',$endDate = '')
	{
		$courseIdList = implode(',', $courseId);
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('count(distinct mt.msgId) as count');
		$dbHandle->from('messageTable mt');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = mt.tracking_keyid','inner');
		//FORCE INDEX FOR JOIN(idx_messageId,idx_courseId)
		$dbHandle->join('questions_listing_response qlr','qlr.messageId = mt.threadId','inner');

		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		$dbHandle->where_in('mt.status',array('live','closed'));
		if( ! empty($courseId))
			$dbHandle->where('qlr.courseId IN ('.$courseIdList.')');
		$dbHandle->where('fromOthers','user');
		$dbHandle->where('parentId',0);
		/*$dbHandle->where('mt.tracking_keyid !=','0');
		$dbHandle->where('mt.tracking_keyid is not null');*/
		$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		$result = $dbHandle->get();
		$result = $result->row();
		$result = $result->count;
		return $result;
		
	}
	function getAnswersCountForTopTile($courseId = array(),$source = '',$startDate = '',$endDate = '')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('count(distinct mt.msgId) as count');
		$dbHandle->from('messageTable mt');
		$dbHandle->join('tracking_pagekey tpk', 'tpk.id = mt.tracking_keyid','inner');
		//FORCE INDEX FOR JOIN(idx_messageId,idx_courseId)
		$dbHandle->join('questions_listing_response qlr','qlr.messageId = mt.threadId','inner');
		$dbHandle->where('mt.parentId = mt.threadId');
		$dbHandle->where('mt.fromOthers','user');

		if( ! empty($courseId))
			$dbHandle->where('qlr.courseId IN ('.implode(',', $courseId).')');

		if(!empty($source))
			$dbHandle->where('tpk.siteSource',$source);

		$dbHandle->where_in('mt.status',array('live','closed'));

		/*$dbHandle->where('mt.tracking_keyid !=','0');
		$dbHandle->where('mt.tracking_keyid is not null');*/
		$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		$result = $dbHandle->get();
		$result = $result->row();
		$result = $result->count;
		return $result;
	}
	function getDigupCountForTopTile($courseId = array(),$source = '',$startDate = '',$endDate = '')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('count(distinct dp.productId) as count');
		
		$dbHandle->from('digUpUserMap dp');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = dp.tracking_keyid','inner');
		$dbHandle->join('messageTable mt', 'mt.msgId = dp.productId','inner');
		$dbHandle->join('questions_listing_response qlr','qlr.messageId = mt.threadId','inner');
		//FORCE INDEX FOR JOIN(idx_messageId,idx_courseId)
		$dbHandle->where('mt.parentId = mt.threadId');
		$dbHandle->where('mt.fromOthers','user');
		$dbHandle->where('dp.digFlag',1);
		$dbHandle->where('dp.digUpStatus','live');

		if( ! empty($courseId))
			$dbHandle->where('qlr.courseId IN ('.implode(',', $courseId).')');

		if(!empty($source))
			$dbHandle->where('tpk.siteSource',$source);

		/*$dbHandle->where('dp.tracking_keyid !=','0');
		$dbHandle->where('dp.tracking_keyid is not null');*/
		if(! empty($startDate))
			$dbHandle->where('dp.digTime >=',$startDate.' 00:00:00');

		if(! empty($endDate))
			$dbHandle->where('dp.digTime <=',$endDate.' 23:59:59');
		$result = $dbHandle->get();
		$result = $result->row();
		$result = $result->count;
		return $result;		
	}
	function getResponseCountForTopTile($courseId= array(),$source='',$paidType='',$startDate='',$endDate='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('count(distinct tLMS.id) as count');
		$dbHandle->from('tempLMSTable tLMS');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = tLMS.tracking_keyid','inner');
		$dbHandle->where('tLMS.listing_type_id IN ('.implode(',', $courseId).')');
		$dbHandle->where('tLMS.listing_type','course');
		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		if(!empty($paidType))
			$dbHandle->where('tLMS.listing_subscription_type',$paidType);
		/*$dbHandle->where('tLMS.tracking_keyid !=','0');
		$dbHandle->where('tLMS.tracking_keyid is not null');*/

		if(! empty($startDate))
			$dbHandle->where('tLMS.submit_date >=', $startDate.' 00:00:00');
		if(! empty($endDate))
			$dbHandle->where('tLMS.submit_date <=', $endDate.' 23:59:59');
		$result = $dbHandle->get();
		$result = $result->row();
		return $result->count;
	}
	function getResponseDataSourceWise($courseId = array(),$source = '',$paidType = '',$startDate ='',$endDate ='',$crieteria = '')
	{
		$dbHandle = $this->getDbHandle('read');
		switch ($crieteria) {
			case 'respondents':
					$dbHandle->select('tLMS.userId as userId,tLMS.visitorsessionid as visitorsessionid');
					$groupByClause = '';
					break;
			case 'courseWiseResponse':
					$dbHandle->select('tLMS.listing_type_id as courseId,count(distinct tLMS.id) as responsescount');
					$groupByClause = 'courseId';
					break;
			case 'uniqResponsesCourseWise':
					$dbHandle->select('tLMS.listing_type_id as courseId,count(distinct(userId)) as count');
					$groupByClause = 'courseId';
					break;
			default:
					$dbHandle->select('tLMS.action as action,count(distinct tLMS.id) as responsescount');
					$groupByClause = 'tLMS.action';
				break;
		}
		$dbHandle->from('tempLMSTable tLMS');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = tLMS.tracking_keyid','inner');
		$dbHandle->where('tLMS.listing_type_id IN ('.implode(',', $courseId).')');
		$dbHandle->where('tLMS.listing_type','course');
		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		if(!empty($paidType))
			$dbHandle->where('tLMS.listing_subscription_type',$paidType);
		/*$dbHandle->where('tLMS.tracking_keyid !=','0');
		$dbHandle->where('tLMS.tracking_keyid is not null');*/

		if(! empty($startDate))
				$dbHandle->where('tLMS.submit_date >=', $startDate.' 00:00:00');
		if(! empty($endDate))
			$dbHandle->where('tLMS.submit_date <=', $endDate.' 23:59:59');
		if( ! empty($groupByClause))
			$dbHandle->group_by($groupByClause);
		if($crieteria == 'respondents')
			$dbHandle->limit(300,0);
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getRegistrationPieChartData($courseId = array(),$source = '',$paidType='',$startDate='',$endDate='',$crieteria = 'deviceWise',$isStudyAbroad = 'no')
	{
		$dbHandle = $this->getDbHandle('read');
		$pageNameArray = $this->getPageNames($source,$isStudyAbroad);
		$whereClause = '("'.implode('","', $pageNameArray).'") ';
		if( $crieteria == 'deviceWise')
		{
			$dbHandle->select('tpk.siteSource as siteSource,count(1) as responsescount');	
			$groupByClause  = 'tpk.siteSource';
		}
		elseif($crieteria == 'sourceWise')
		{
			$dbHandle->select('rt.visitorSessionid as sessionId,count(1) as responsescount');
			$groupByClause = 'sessionId';
		}
		elseif($crieteria == 'paidFreeWise')
		{
			$dbHandle->select('rt.source as pivotName,count(1) as responsescount');
			$groupByClause = 'pivotName';
		}
		$dbHandle->from('registrationTracking rt');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = rt.trackingkeyId AND tpk.page IN '.$whereClause,'inner');
		$dbHandle->join('tuserflag tf','tf.userId = rt.userId AND tf.mobileverified = "1" AND tf.isTestUser = "NO"','inner');
		$dbHandle->join('tempLMSTable tlt','tlt.userid = rt.userId and rt.trackingkeyId = tlt.tracking_keyid','inner');

		/*$dbHandle->where('tusi.tracking_keyid IN ('.implode(',', $keyIds).')');
		//$dbHandle->where('tlt.tracking_keyid IN ('.implode(',', $keyIds).')');*/
		if( ! empty($paidType))
			$dbHandle->where('rt.source',$paidType);

		$dbHandle->where('rt.isNewReg','yes');
		if( ! empty($source))
			$dbHandle->where('tpk.siteSource',$source);

		$dbHandle->where('tlt.listing_type_id IN ('.implode(',', $courseId).')');
		$dbHandle->where('tlt.listing_type','course');

		$dbHandle->where('rt.submitDate >=',$startDate);
		$dbHandle->where('rt.submitDate <=',$endDate);
		//$dbHandle->where('date(tlt.submit_date) = rt.date');
		$dbHandle->where('TIME_TO_SEC(TIMEDIFF(tlt.submit_date,rt.submitTime))<=','15');
		$dbHandle->group_by($groupByClause);
		$result = $dbHandle->get()->result_array();
		//_p(TIME_TO_SEC(TIMEDIFF('tlt.submit_date','tusi.time')));
		return $result;
	}
	function getDeliveryDataByEmailGeniesForTopTile($email_genie_id,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('distinct(userid) as userid');
		$dbHandle->from('SALeadAllocation force index(keyagentid)');
		$dbHandle->where('agentid IN ('.implode(',', $email_genie_id).')');
		$dbHandle->where('allocationtime >=',$startDate.' 00:00:00');
		$dbHandle->where('allocationtime <=',$endDate.' 23:59:59');
		$result = $dbHandle->get()->result_array();
		return$result;
	}
	function getDeliveryDataByPortingGeniesForTopTile($porting_genie_id,$startDate,$endDate)
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('distinct(leadid) as userid');
		$dbHandle->from('SALeadMatchingLog force index(searchagentid)');
		$dbHandle->where('searchagentid IN ('.implode(',', $porting_genie_id).')');
		$dbHandle->where('matchingTime >=',$startDate.' 00:00:00');
		$dbHandle->where('matchingTime <=',$endDate.' 23:59:59');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getViewDeliveryForTopTile($clientId,$startDate,$endDate,$leadFlag = 1,$flag = 'national')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('distinct(v.UserId) as userid');
		$dbHandle->from('LDBLeadContactedTracking l FORCE INDEX (keyClientId)');
		$dbHandle->join('LDBLeadViewCount v','l.UserId = v.UserId','inner');
		$dbHandle->where('l.ClientId IN ('.implode(',', $clientId).')');
		if($leadFlag == 1)
		{
			$dbHandle->where_not_in('v.DesiredCourse',array(2,56));
		}
		else
		{
			$dbHandle->where_in('v.DesiredCourse',array(2,56));
		}
		$dbHandle->where('l.ContactDate >=',$startDate.' 00:00:00');
		$dbHandle->where('l.ContactDate <=',$endDate.' 23:59:59');
		$dbHandle->where('l.activity_flag','LDB');
		if( $flag != 'both')
		{
			$dbHandle->where('v.Flag',$flag);	
		}
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getResponsesCourseWise($courseId = array(),$source ='',$startDate='',$endDate ='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('tLMS.listing_type_id as courseId,count(distinct tLMS.id) as responsescount');
		$dbHandle->from('tempLMSTable tLMS');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = tLMS.tracking_keyid','inner');
		$dbHandle->where('tLMS.listing_type_id IN ('.implode(',', $courseId).')');
		$dbHandle->where('tLMS.listing_type','course');
		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		$dbHandle->where('tLMS.listing_subscription_type','paid');
		/*$dbHandle->where('tLMS.tracking_keyid !=','0');
		$dbHandle->where('tLMS.tracking_keyid is not null');*/

		if(! empty($startDate))
				$dbHandle->where('tLMS.submit_date >=', $startDate.' 00:00:00');
		if(! empty($endDate))
			$dbHandle->where('tLMS.submit_date <=', $endDate.' 23:59:59');
		$dbHandle->group_by('courseId');
		$result = $dbHandle->get()->result_array();
		return $result;		
	}
	function getUniqueResponsesCourseWise($courseId = array(),$source ='',$startDate='',$endDate ='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('tLMS.listing_type_id as courseId,count(distinct(tLMS.userId)) as count');
		$dbHandle->from('tempLMSTable tLMS');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = tLMS.tracking_keyid','inner');
		$dbHandle->where('tLMS.listing_type_id IN ('.implode(',', $courseId).')');
		$dbHandle->where('tLMS.listing_type','course');
		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		$dbHandle->where('tLMS.listing_subscription_type','paid');
		/*$dbHandle->where('tLMS.tracking_keyid !=','0');
		$dbHandle->where('tLMS.tracking_keyid is not null');*/

		if(! empty($startDate))
				$dbHandle->where('tLMS.submit_date >=', $startDate.' 00:00:00');
		if(! empty($endDate))
			$dbHandle->where('tLMS.submit_date <=', $endDate.' 23:59:59');
		$dbHandle->group_by('courseId');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getResponseInstituteWise($instituteId = array(),$source ='',$startDate='',$endDate ='',$isStudyAbroad ='no',$crieteria = '')
	{
		$dbHandle = $this->getDbHandle('read');
		if($isStudyAbroad == 'no')
		{
			if($crieteria == 'uniqResponse')
				$dbHandle->select('c.institute_id as instituteId,count(distinct(tLMS.userId)) as count');
			else
				$dbHandle->select('c.institute_id as instituteId,count(distinct tLMS.id) as responsescount');
		}
		else if($isStudyAbroad == 'yes')
		{
			if($crieteria == 'uniqResponse')
				$dbHandle->select('ium.university_id as instituteId,count(distinct(tLMS.userId)) as count');
			else
				$dbHandle->select('ium.university_id as instituteId,count(distinct tLMS.id) as responsescount');	
		}
			
		$dbHandle->from('tempLMSTable tLMS');
		$dbHandle->join('tracking_pagekey tpk','tpk.id = tLMS.tracking_keyid','inner');
		$dbHandle->join('course_details c','c.course_id = tLMS.listing_type_id','inner');
		if($isStudyAbroad == 'yes')
			$dbHandle->join('institute_university_mapping ium','c.institute_id = ium.institute_id','inner');
		//$dbHandle->where_in('tLMS.listing_type_id',$courseId);
		if($isStudyAbroad == 'no')
			$dbHandle->where('c.institute_id IN ('.implode(',', $instituteId).')');
		else if($isStudyAbroad == 'yes')
			$dbHandle->where('ium.university_id IN ('.implode(',', $instituteId).')');
		$dbHandle->where('tLMS.listing_type','course');
		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		$dbHandle->where('tLMS.listing_subscription_type','paid');
		/*$dbHandle->where('tLMS.tracking_keyid !=','0');
		$dbHandle->where('tLMS.tracking_keyid is not null');*/
		/*if($isStudyAbroad == 'yes')
			$dbHandle->where('ium.status','live');*/
		if(! empty($startDate))
				$dbHandle->where('tLMS.submit_date >=', $startDate.' 00:00:00');
		if(! empty($endDate))
			$dbHandle->where('tLMS.submit_date <=', $endDate.' 23:59:59');
		//$dbHandle->where('c.status','live');
		$dbHandle->group_by('instituteId');
		$result = $dbHandle->get()->result_array();
		return $result;		
	}
	function getCourseName($courseId = array())
	{
		$dbHandle = $this->getDbHandle('read');
		$sql = "SELECT course_id as courseId,institute_id as instituteId,courseTitle as courseName FROM course_details WHERE course_id IN (".implode(',', $courseId).") ";
		$result = $dbHandle->query($sql)->result_array();
		return $result;

	}
	function getStudyAbroadCourseName($courseId = array())
	{
		$dbHandle = $this->getDbHandle('read');
		$sql = "SELECT cd.course_id as courseId,cd.courseTitle as courseName,ium.university_id as instituteId FROM course_details cd INNER JOIN institute_university_mapping ium ON cd.institute_id = ium.institute_id WHERE cd.course_id IN (".implode(',', $courseId).") ";
		$result = $dbHandle->query($sql)->result_array();
		return $result;
	}
	function getInstituteNames($instituteId = array(),$type ='institute')
	{
		$dbHandle = $this->getDbHandle('read');
		/*$sql = "SELECT listing_type_id as instituteId,listing_title as instituteName FROM listings_main WHERE listing_type_id IN (".implode(',', $instituteId).") AND listing_type = '".$type."' AND status = 'live'";
		$result = $dbHandle->query($sql)->result_array();
		return $result;*/
		$sql = "SELECT institute_id as instituteId,institute_name as instituteName FROM institute WHERE institute_id IN (".implode(',', $instituteId).") ";
		$result = $dbHandle->query($sql)->result_array();
		return $result;
	}
	function getUniversityName($universityId = array())
	{
		$dbHandle = $this->getDbHandle('read');
		$sql = "SELECT university_id as instituteId,name as instituteName FROM university WHERE university_id IN (".implode(',', $universityId).") ";
		$result = $dbHandle->query($sql)->result_array();
		return $result;
	}
	function getInstituteBasedOnCourseId($courseId = array())
	{
		$dbHandle = $this->getDbHandle('read');
		$sql = "SELECT course_id as courseId,institute_id as instituteId FROM course_details WHERE course_id IN (".implode(',', $courseId).") ";
		$result = $dbHandle->query($sql)->result_array();
		return $result;		
	}
	function getUniversityBasedOnCourse($courseId = array())
	{
		$dbHandle = $this->getDbHandle('read');
		$sql = "SELECT cd.course_id as courseId,ium.university_id as instituteId FROM course_details cd INNER JOIN institute_university_mapping ium ON cd.institute_id = ium.institute_id WHERE cd.course_id IN (".implode(',', $courseId).") ";
		$result = $dbHandle->query($sql)->result_array();
		return $result;
	}
	function getCampaignForSessionId($sessionId)
	{
		$dbHandle = $this->getDbHandle('read');
		$sessionIds = '"'.implode('", "', $sessionId).'"';
		$sql = "SELECT utm_source as campaignName,sessionId FROM session_tracking WHERE sessionId IN (".$sessionIds.")  AND utm_source is not null";
		$result = $dbHandle->query($sql)->result_array();
		return $result;
	}
	function getResponseCountForTopTileBySubcat($subCategoryId= array(),$cityId = array(),$keyIds=array(),$paidType='',$startDate='',$endDate='',$viewWise ='',$crieteria ='' ,$flag='Study Abroad')
	{
		$dbHandle = $this->getDbHandle('read');
		if( ! empty($crieteria))
		{
			if($viewWise == 1)
			{
				$dbHandle->select('date(tLMS.submit_date) as responseDate,count(distinct tLMS.id) as responsescount');
			}
			else if($viewWise == 2)
			{
				$dbHandle->select('WEEK(date(tLMS.submit_date),1) as weekNumber,count(distinct tLMS.id) as responsescount',FALSE);
			}
			else if($viewWise == 3)
			{
				$dbHandle->select('date(tLMS.submit_date) as responseDate,month(date(tLMS.submit_date)) as monthNumber,count(distinct tLMS.id) as responsescount');
			}
		}
		else
		{
			$dbHandle->select('count(distinct tLMS.id) as count');	
		}
		$dbHandle->from('tempLMSTable tLMS force index(submit_date)');
		//$dbHandle->join('tracking_pagekey tpk','tpk.id = tLMS.tracking_keyid AND tpk.site!="'.$flag.'"','inner');
		if( ! empty($cityId))
		{
			$dbHandle->join('course_location_attribute c','c.course_id = tLMS.listing_type_id','inner');
			$dbHandle->join('institute_location_table i','i.institute_location_id = c.institute_location_id AND i.city_id IN ('.implode(',', $cityId).')','inner');
		}
		$dbHandle->join('listing_category_table l','tLMS.listing_type_id = l.listing_type_id AND l.listing_type = tLMS.listing_type AND l.listing_type= "course"','inner');
		$dbHandle->where('l.category_id IN ('.implode(',', $subCategoryId).')');
		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		$dbHandle->where('tLMS.tracking_keyid IN ('.implode(',', $keyIds).')');
		if(!empty($paidType))
			$dbHandle->where('tLMS.listing_subscription_type',$paidType);
		/*$dbHandle->where('tLMS.tracking_keyid !=','0');
		$dbHandle->where('tLMS.tracking_keyid is not null');*/

		if(! empty($startDate))
				$dbHandle->where('tLMS.submit_date >=', $startDate.' 00:00:00');
		if(! empty($endDate))
			$dbHandle->where('tLMS.submit_date <=', $endDate.' 23:59:59');
		if( ! empty($crieteria))
		{
			if($viewWise == 1)
			{
				$dbHandle->group_by('responseDate');
			}
			else if($viewWise == 2)
			{
				$dbHandle->group_by('weekNumber');
			}
			else if($viewWise == 3)
			{
				$dbHandle->group_by('monthNumber');
				$dbHandle->group_by('responseDate');
				$dbHandle->order_by('responseDate');
			}
			$result = $dbHandle->get()->result_array();
		}
		else
		{
			$result = $dbHandle->get();
			$result = $result->row();
			$result =  $result->count;	
		}
		return $result;
	}
	function getUserName($userId = array())
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('userid as Id,firstname as firstName,lastname as lastName');
		$dbHandle->from('tuser');
		$dbHandle->where('userid IN ('.implode(',',$userId).')');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getCourseCountUnderClient($username = array())
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('username,count(1) as count');
		$dbHandle->from('listings_main');
		$dbHandle->where('username IN ('.implode(',', $username).')');
		$dbHandle->where('listing_type','course');
		//$dbHandle->where('status','live');
		$dbHandle->group_by('username');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	function getQuestionsCountForTopTileBySubcat($subCategoryId = array(),$cityId = array(),$keyIds = array(),$startDate = '',$endDate = '',$viewWise = '',$crieteria ='',$flag = 'Study Abroad')
	{
		$dbHandle = $this->getDbHandle('read');
		if( ! empty($crieteria))
		{
			if($viewWise ==	1)
			{
				$dbHandle->select('date(mt.creationDate) as responseDate,count(distinct mt.msgId) as responsescount');
			}
			else if($viewWise==2)
			{
				$dbHandle->select('WEEK(date(mt.creationDate),1) as weekNumber,count(distinct mt.msgId) as responsescount',FALSE);
			}	
			else
			{
				$dbHandle->select('date(mt.creationDate) as responseDate,month(date(mt.creationDate)) as monthNumber,count(distinct mt.msgId) as responsescount');
			}
		}
		else 
		{
			$dbHandle->select('count(distinct mt.msgId) as count');	
		}
		
		$dbHandle->from('messageTable mt');
		//$dbHandle->join('tracking_pagekey tpk','mt.tracking_keyid = tpk.id AND site!="'.$flag.'"','inner');
		$dbHandle->join('questions_listing_response qlr','qlr.messageId = mt.threadId AND qlr.status = "live"');
		$dbHandle->join('listing_category_table l','qlr.courseId = l.listing_type_id AND l.listing_type = "course"','inner');
		if( ! empty($cityId))		
		{
			$dbHandle->join('course_location_attribute c','c.course_id = qlr.courseId','inner');
			$dbHandle->join('institute_location_table i','i.institute_location_id = c.institute_location_id','inner');
			$dbHandle->where('i.city_id IN (.'.implode(',', $cityId).')');
		}
		if( ! empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		$dbHandle->where('mt.tracking_keyid IN ('.implode(',', $keyIds).')');
		$dbHandle->where('l.category_id IN ('.implode(',', $subCategoryId).')');
		$dbHandle->where('mt.fromOthers','user');
		$dbHandle->where('mt.parentId',0);
		$dbHandle->where_in('mt.status',array('live','closed'));
		$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		if( ! empty($crieteria))
		{
			if($viewWise == 1)
				$dbHandle->group_by('responseDate');
			else if($viewWise == 2)
					$dbHandle->group_by('weekNumber');
			else if($viewWise == 3)
			{
				$dbHandle->group_by('monthNumber');
				$dbHandle->group_by('responseDate');
				$dbHandle->order_by('responseDate');
			}
			$result = $dbHandle->get()->result_array();
		}
		else
		{
			$result = $dbHandle->get();
			$result = $result->row();
			$result = $result->count;	
		}
		return $result;
	}
	function getAnswersCountForTopTileBySubcat($subCategoryId = array(),$cityId = array(),$keyIds = array(),$startDate = '',$endDate = '',$viewWise ='',$crieteria ='',$flag = 'Study Abroad')
	{
		$dbHandle = $this->getDbHandle('read');
		if( ! empty($crieteria))
		{
			if($viewWise ==1)
			{
				$dbHandle->select('date(mt.creationDate) as responseDate, count(distinct mt.msgId) as responsescount');
			}
			else if($viewWise == 2)
			{
				$dbHandle->select('week(date(mt.creationDate),1) as weekNumber,count(distinct mt.msgId) as responsescount',FALSE);
			}
			else
			{
				$dbHandle->select('date(mt.creationDate) as responseDate,month(date(mt.creationDate)) as monthNumber,count(distinct mt.msgId) as responsescount');
			}
		}
		else
		{
			$dbHandle->select('count(distinct mt.msgId) as count');	
		}
		
		$dbHandle->from('messageTable mt');
		//$dbHandle->join('tracking_pagekey tpk','mt.tracking_keyid = tpk.id AND site!="'.$flag.'"','inner');
		$dbHandle->join('questions_listing_response qlr','qlr.messageId = mt.threadId AND qlr.status = "live"');
		$dbHandle->join('listing_category_table l','qlr.courseId = l.listing_type_id  AND l.listing_type = "course"','inner');
		if( ! empty($cityId))		
		{
			$dbHandle->join('course_location_attribute c','c.course_id = qlr.courseId','inner');
			$dbHandle->join('institute_location_table i','i.institute_location_id = c.institute_location_id','inner');
			$dbHandle->where('i.city_id IN (.'.implode(',', $cityId).')');
		}
		$dbHandle->where('l.category_id IN ('.implode(',', $subCategoryId).')');
		$dbHandle->where('mt.tracking_keyid IN ('.implode(',', $keyIds).')');
		$dbHandle->where('mt.parentId = mt.threadId');
		$dbHandle->where('mt.fromOthers','user');

		if( ! empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		$dbHandle->where_in('mt.status',array('live','closed'));
		$dbHandle->where('mt.creationDate >=',$startDate.' 00:00:00');
		$dbHandle->where('mt.creationDate <=',$endDate.' 23:59:59');
		if( ! empty($crieteria))
		{
			if($viewWise == 1)
			{
				$dbHandle->group_by('responseDate');
			}
			else if($viewWise == 2)
			{
				$dbHandle->group_by('weekNumber');
			}
			else
			{
				$dbHandle->group_by('monthNumber');
				$dbHandle->group_by('responseDate');
				$dbHandle->order_by('responseDate');
			}
			$result = $dbHandle->get()->result_array();
		}
		else
		{
			$result = $dbHandle->get();
			$result = $result->row();
			$result = $result->count;
		}
		return $result;
	}
	function getDigupCountForTopTileBySubcat($subCategoryId = array(),$cityId = array(),$keyIds = array(),$startDate = '',$endDate = '',$viewWise ='',$crieteria ='',$flag = 'Study Abroad')
	{
		$dbHandle = $this->getDbHandle('read');
		if( ! empty($crieteria))
		{
			if($viewWise == 1)
			{
				$dbHandle->select('date(dp.digTime) as responseDate,count(distinct dp.productId) as responsescount');
			}
			else if($viewWise == 2)
			{
				$dbHandle->select('week(date(dp.digTime),1) as weekNumber,count(distinct dp.productId) as responsescount',FALSE);
			}
			else
			{
				$dbHandle->select('date(dp.digTime) as responseDate,month(date(dp.digTime)) as monthNumber,count(distinct dp.productId) as responsescount');
			}
		}
		else 
		{
			$dbHandle->select('count(distinct dp.productId) as count');	
		}
		$dbHandle->from('digUpUserMap dp');
		//$dbHandle->join('tracking_pagekey tpk','dp.tracking_keyid = tpk.id AND site!="'.$flag.'"','inner');
		$dbHandle->join('messageTable mt','mt.msgId = dp.productId','inner');
		$dbHandle->join('questions_listing_response qlr','qlr.messageId = mt.threadId AND qlr.status = "live"');
		$dbHandle->join('listing_category_table l','qlr.courseId = l.listing_type_id  AND l.listing_type = "course"','inner');
		if( ! empty($cityId))		
		{
			$dbHandle->join('course_location_attribute c','c.course_id = qlr.courseId','inner');
			$dbHandle->join('institute_location_table i','i.institute_location_id = c.institute_location_id','inner');
			$dbHandle->where('i.city_id IN (.'.implode(',', $cityId).')');
		}
		$dbHandle->where('l.category_id IN ('.implode(',', $subCategoryId).')');
		$dbHandle->where('dp.tracking_keyid IN ('.implode(',', $keyIds).')');
		$dbHandle->where('mt.parentId = mt.threadId');
		$dbHandle->where('mt.fromOthers','user');
		$dbHandle->where('dp.digFlag',1);
		$dbHandle->where('dp.digUpStatus','live');
		if( ! empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		$dbHandle->where('dp.digTime >=',$startDate.' 00:00:00');
		$dbHandle->where('dp.digTime <=',$endDate.' 23:59:59');
		if( ! empty($crieteria))
		{
			if($viewWise == 1)
			{
				$dbHandle->group_by('responseDate');
			}
			else if($viewWise == 2)
			{
				$dbHandle->group_by('weekNumber');
			}
			else
			{
				$dbHandle->group_by('monthNumber');
				$dbHandle->group_by('responseDate');
				$dbHandle->order_by('responseDate');
			}
			$result = $dbHandle->get()->result_array();
		}
		else
		{
			$result = $dbHandle->get();
			$result = $result->row();
			$result = $result->count;
		}
		return $result;
	}
	function getRegistrationCountForTopTileBySubcat($subCategoryId = array(),$cityId = array(),$keyIds = array(),$paidType='',$startDate = '',$endDate ='',$viewWise = '',$crieteria ='',$flag = 'Study Abroad')
	{
		$dbHandle = $this->getDbHandle('read');
	/*	$pageNameArray = $this->getPageNames($source,'no');
		$whereClause = '("'.implode('","', $pageNameArray).'") ';
	*/	if( ! empty($crieteria))
		{
			if($viewWise == 1)
			{
				$dbHandle->select('rt.submitDate as responseDate,count(distinct rt.userId) as responsescount');
			}	
			else if($viewWise == 2)
			{
				$dbHandle->select('week(rt.submitDate,1) as weekNumber,count(distinct rt.userId) as responsescount',FALSE);
			}
			else if($viewWise == 3)
			{
				$dbHandle->select('rt.submitDate as responseDate,month(rt.submitDate) as monthNumber,count(distinct rt.userId) as responsescount');
			}
		}
		else 
		{
			$dbHandle->select('count(distinct rt.userId) as count');
		}
		
		$dbHandle->from('registrationTracking rt');//add -> force index(tracking_keyid)
		$dbHandle->join('tempLMSTable tlt','tlt.userid = rt.userId','inner');
	//	$dbHandle->join('tracking_pagekey tpk','tusi.tracking_keyid = tpk.id AND site!="'.$flag.'" AND tpk.page IN '.$whereClause,'inner');
		$dbHandle->join('tuserflag tf','tf.userId = rt.userId AND tf.mobileverified = "1" AND tf.isTestUser = "NO"','inner');
		
		$dbHandle->join('listing_category_table l','tlt.listing_type_id = l.listing_type_id AND tlt.listing_type = l.listing_type AND l.listing_type = "course"','inner');
		if( ! empty($cityId))
		{
			$dbHandle->join('course_location_attribute c','c.course_id = tlt.listing_type_id','inner');
			$dbHandle->join('institute_location_table i','i.institute_location_id = c.institute_location_id AND i.city_id IN ('.implode(',', $cityId).')','inner');
		}
		$dbHandle->where('rt.isNewReg','yes');
		if( ! empty($paidType))
		{
			$dbHandle->where('rt.source',$paidType);
		}
		$dbHandle->where('l.category_id IN ('.implode(',', $subCategoryId).')');
		$dbHandle->where('rt.submitDate >=',$startDate);
		$dbHandle->where('rt.submitDate <=',$endDate);
		$dbHandle->where('TIME_TO_SEC(TIMEDIFF(tlt.submit_date,rt.submitTime))<=','15');
		$dbHandle->where('rt.trackingkeyId IN ('.implode(',', $keyIds).')');
		$dbHandle->where('tlt.tracking_keyid IN ('.implode(',', $keyIds).')');
		/*if( ! empty($source))
			$dbHandle->where('tpk.siteSource',$source);*/
		if( ! empty($crieteria))
		{
			if($viewWise == 1)
				$dbHandle->group_by('responseDate');
			else if($viewWise == 2)
				$dbHandle->group_by('weekNumber');
			else if($viewWise == 3)
			{
				$dbHandle->group_by('monthNumber');
				$dbHandle->group_by('responseDate');
				$dbHandle->order_by('responseDate');
			}
			$result = $dbHandle->get()->result_array();
		}
		else
		{
			$result = $dbHandle->get();
			$result = $result->row();
			$result = $result->count;	
		}
		return $result;

	}
	/**
	* @below Function is used For fetch data related to response based on domestic subcat
	* @param : $crieteria = respondents => it will fetch respondents(users) data
	*					  = courseWiseResponse => number of Paid Responses delivered to Courses
	*					  = uniqResponsesCourseWise => number of Unique Paid Responses delivered to courses
	* 					  = instituteWiseResponse => number of Paid Responses delivered to Institutes
	* 					  = uniqResponseInstituteWise => number of Unique Paid Responses delivered to Institutes
	*					  = Default => fetch Response data from source Wise (Like Organic / Push)
	*/
	function getResponseDataSourceWiseBySubcat($subCategoryId = array(),$cityId = array(),$keyIds = array(),$paidType = '',$startDate = '',$endDate='',$crieteria ='')
	{
		$dbHandle = $this->getDbHandle('read');
		switch ($crieteria) {
			case 'respondents':
						$dbHandle->select('tLMS.userId as userId,tLMS.visitorsessionid as visitorsessionid');		
						$groupByClause = '';
						break;
			case 'courseWiseResponse':
						$dbHandle->select('tLMS.listing_type_id as courseId,count(distinct tLMS.id) as responsescount');
						$groupByClause = 'courseId';
						break;
			case 'uniqResponsesCourseWise':
						$dbHandle->select('tLMS.listing_type_id as courseId,count(distinct(userId)) as count');
						$groupByClause = 'courseId';
						break;
			case 'instituteWiseResponse':
						$dbHandle->select('i.institute_id as instituteId,count(distinct tLMS.id) as responsescount');
						$groupByClause = 'instituteId';
						break;
			case 'uniqResponseInstituteWise':
						$dbHandle->select('i.institute_id as instituteId,count(distinct(tLMS.userId)) as count');
						$groupByClause = 'instituteId';
						break;
			case 'paidFree':
						$dbHandle->select('tLMS.listing_subscription_type as paidFree,count(distinct tLMS.id) as responsescount');
						$groupByClause = 'paidFree';
						break;
			default:
						$dbHandle->select('tLMS.action as action,count(distinct tLMS.id) as responsescount');
						$groupByClause = 'tLMS.action';
						break;
		}	
		$dbHandle->from('tempLMSTable tLMS force index(submit_date)');
		//$dbHandle->join('tracking_pagekey tpk','tpk.id = tLMS.tracking_keyid AND tpk.site!="Study Abroad"','inner');
		if( ! empty($cityId))
		{
			$dbHandle->join('course_location_attribute c','c.course_id = tLMS.listing_type_id ','inner');
			$dbHandle->join('institute_location_table i','i.institute_location_id = c.institute_location_id AND i.city_id IN ('.implode(',', $cityId).')','inner');
		}
		else if( empty($cityId) && ($crieteria == 'instituteWiseResponse' || $crieteria == 'uniqResponseInstituteWise'))
		{
			$dbHandle->join('course_location_attribute c','c.course_id = tLMS.listing_type_id','inner');
			$dbHandle->join('institute_location_table i','i.institute_location_id = c.institute_location_id','inner');			
		}
		$dbHandle->join('listing_category_table l','tLMS.listing_type_id = l.listing_type_id AND l.listing_type = tLMS.listing_type AND l.listing_type= "course"','inner');
		$dbHandle->where('l.category_id IN ('.implode(',', $subCategoryId).')');
		$dbHandle->where('tLMS.tracking_keyid IN ('.implode(',', $keyIds).')');
		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		if(!empty($paidType))
			$dbHandle->where('tLMS.listing_subscription_type',$paidType);
		//$dbHandle->where('tLMS.tracking_keyid !=','0');
		//$dbHandle->where('tLMS.tracking_keyid is not null');

		if(! empty($startDate))
				$dbHandle->where('tLMS.submit_date >=', $startDate.' 00:00:00');
		if(! empty($endDate))
			$dbHandle->where('tLMS.submit_date <=', $endDate.' 23:59:59');
		if( ! empty($groupByClause))			
			$dbHandle->group_by($groupByClause);
		if($crieteria == 'respondents')
				$dbHandle->limit(300,0);	
		$result = $dbHandle->get()->result_array();
		return $result;		
	}
	function getRegistrationPieChartDataBySubcat($subCategoryId = array(),$cityId = array(),$keyIds = array(),$paidType='',$startDate='',$endDate='',$crieteria = 'deviceWise')
	{
		$dbHandle = $this->getDbHandle('read');
		/*$pageNameArray = $this->getPageNames($source,'no');
		$whereClause = '("'.implode('","', $pageNameArray).'") ';*/
		if( $crieteria == 'deviceWise')
		{
			$dbHandle->select('rt.trackingkeyId as tracking_keyid,count(distinct rt.userId) as responsescount');
			$groupByClause = 'tracking_keyid';
		}
		elseif( $crieteria == 'sourceWise')
		{
			$dbHandle->select('rt.visitorSessionid as sessionId,count(distinct rt.userId) as responsescount');
			$groupByClause = 'sessionId';
		}
		elseif($crieteria == 'paidFreeWise')
		{
			$dbHandle->select('rt.source as pivotName,count(distinct rt.userId) as responsescount');
			$groupByClause = 'pivotName';
		}
		$dbHandle->from('registrationTracking rt');//add ->  force index(tracking_keyid)
		//$dbHandle->join('tracking_pagekey tpk','tusi.tracking_keyid = tpk.id AND site!="Study Abroad" AND tpk.page IN '.$whereClause,'inner');
		$dbHandle->join('tuserflag tf','tf.userId = rt.userId AND tf.mobileverified = "1" AND tf.isTestUser = "NO"','inner');
		$dbHandle->join('tempLMSTable tlt','tlt.userid = rt.userId','inner');
		$dbHandle->join('listing_category_table l','tlt.listing_type_id = l.listing_type_id AND tlt.listing_type = l.listing_type AND l.listing_type = "course"','inner');
		if( ! empty($cityId))
		{
			$dbHandle->join('course_location_attribute c','c.course_id = tlt.listing_type_id','inner');
			$dbHandle->join('institute_location_table i','i.institute_location_id = c.institute_location_id AND i.city_id IN ('.implode(',', $cityId).')','inner');
		}
		if( ! empty($paidType))
		{
			$dbHandle->where('rt.source',$paidType);
		}
		$dbHandle->where('rt.isNewReg','yes');
		$dbHandle->where('l.category_id IN ('.implode(',', $subCategoryId).')');
		$dbHandle->where('rt.trackingkeyId IN ('.implode(',', $keyIds).')');
		$dbHandle->where('tlt.tracking_keyid IN ('.implode(',', $keyIds).')');
		if( ! empty($source))
			$dbHandle->where('tpk.siteSource',$source);
		$dbHandle->where('rt.submitDate >=',$startDate);
		$dbHandle->where('rt.submitDate <=',$endDate);
		$dbHandle->where('TIME_TO_SEC(TIMEDIFF(tlt.submit_date,rt.submitTime))<=','15');
		$dbHandle->group_by($groupByClause);
		$result = $dbHandle->get()->result_array();
		return $result;		
	}
	function getResponseDataForStudyAbroadBySubcat($subCategoryId = array(),$countryId=array(),$keyIds =array(),$paidType='',$startDate='',$endDate='',$viewWise=1,$crieteria='')
	{
		$dbHandle = $this->getDbHandle('read');
		if( ! empty($crieteria))
		{
			if($viewWise == 1)
			{
				$dbHandle->select('date(tLMS.submit_date) as responseDate,count(distinct tLMS.id) as responsescount');
			}
			else if($viewWise == 2)
			{
				$dbHandle->select('WEEK(date(tLMS.submit_date),1) as weekNumber,count(distinct tLMS.id) as responsescount',FALSE);
			}
			else if($viewWise == 3)
			{
				$dbHandle->select('date(tLMS.submit_date) as responseDate,month(date(tLMS.submit_date)) as monthNumber,count(distinct tLMS.id) as responsescount');
			}
		}
		else
		{
			$dbHandle->select('count(distinct tLMS.id) as count');	
		}
		$dbHandle->from('tempLMSTable tLMS');
		//$dbHandle->join('tracking_pagekey tpk','tpk.id = tLMS.tracking_keyid','inner');
		$dbHandle->join('abroadCategoryPageData abroad','tLMS.listing_type_id = abroad.course_id','inner');
		if( ! empty($subCategoryId))
			$dbHandle->where('sub_category_id IN ('.implode(',', $subCategoryId).')');
		if( ! empty($countryId))
			$dbHandle->where('country_id IN ('.implode(',', $countryId).')');
		$dbHandle->where('tLMS.listing_type','course');
		$dbHandle->where('tLMS.tracking_keyid IN('.implode(',', $keyIds).')');
		//$dbHandle->where('abroad.status','live');
		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		if(!empty($paidType))
			$dbHandle->where('tLMS.listing_subscription_type',$paidType);
		/*$dbHandle->where('tLMS.tracking_keyid !=','0');
		$dbHandle->where('tLMS.tracking_keyid is not null');*/

		if(! empty($startDate))
			$dbHandle->where('tLMS.submit_date >=', $startDate.' 00:00:00');
		if(! empty($endDate))
			$dbHandle->where('tLMS.submit_date <=', $endDate.' 23:59:59');
		if( ! empty($crieteria))
		{
			if($viewWise == 1)
			{
				$dbHandle->group_by('responseDate');
			}
			else if($viewWise == 2)
			{
				$dbHandle->group_by('weekNumber');
			}
			else if($viewWise == 3)
			{
				$dbHandle->group_by('monthNumber');
				$dbHandle->group_by('responseDate');
				$dbHandle->order_by('responseDate');
			}
			$result = $dbHandle->get()->result_array();
		}
		else
		{
			$result = $dbHandle->get();
			$result = $result->row();
			$result = $result->count;	
		}
		return $result;
	}
	function getRegistrationDataForStudyAbroadBySubcat($subCategoryId = array(),$countryId = array(),$keyIds = array(),$paidType='',$startDate='',$endDate='',$viewWise=1,$crieteria='')
	{
		$dbHandle = $this->getDbHandle('read');
		if(! empty($crieteria))
		{
			if($viewWise == 1)
			{
				$dbHandle->select('rt.submitDate as responseDate,count(distinct rt.userId) as responsescount');
			}	
			else if($viewWise == 2)
			{
				$dbHandle->select('week(rt.submitDate,1) as weekNumber,count(distinct rt.userId) as responsescount',FALSE);
			}
			else if($viewWise == 3)
			{
				$dbHandle->select('rt.submitDate as responseDate,month(rt.submitDate) as monthNumber,count(distinct rt.userId) as responsescount');
			}
		}
		else
		{
			$dbHandle->select('count(distinct rt.userId) as count');
		}
		$dbHandle->from('registrationTracking rt');
		//$dbHandle->join('tracking_pagekey tpk','tpk.id = tusi.tracking_keyid AND tpk.page IN ("universityPage","coursePage")','inner');
		$dbHandle->join('tuserflag tf','tf.userId = rt.userId AND tf.mobileverified = "1" AND tf.isTestUser = "NO"','inner');
		$dbHandle->join('tempLMSTable tlt','tlt.userid = rt.userId','inner');
		$dbHandle->join('abroadCategoryPageData abroad','tlt.listing_type_id = abroad.course_id','inner');
		if( ! empty($subCategoryId))
			$dbHandle->where('sub_category_id IN ('.implode(',', $subCategoryId).')');
		if( ! empty($countryId))
			$dbHandle->where('country_id IN ('.implode(',', $countryId).')');
		if( ! empty($paidType))
		{
			$dbHandle->where('rt.source',$paidType);
		}
		$dbHandle->where('rt.isNewReg','yes');
		$dbHandle->where('tlt.listing_type','course');
		//$dbHandle->where('abroad.status','live');
		if( ! empty($source))
			$dbHandle->where('tpk.siteSource',$source);
		$dbHandle->where('rt.trackingkeyId IN ('.implode(',', $keyIds).')');
		$dbHandle->where('tlt.tracking_keyid IN ('.implode(',', $keyIds).')');
		$dbHandle->where('rt.submitDate >=',$startDate);
		$dbHandle->where('rt.submitDate <=',$endDate);
		$dbHandle->where('TIME_TO_SEC(TIMEDIFF(tlt.submit_date,rt.submitTime))<=','15');
		if(! empty($crieteria))
		{
			if($viewWise == 1)
				$dbHandle->group_by('responseDate');
			else if($viewWise == 2)
				$dbHandle->group_by('weekNumber');
			else if($viewWise == 3)
			{
				$dbHandle->group_by('monthNumber');
				$dbHandle->group_by('responseDate');
				$dbHandle->order_by('responseDate');
			}
			$result = $dbHandle->get()->result_array();
		}
		else
		{
			$result = $dbHandle->get();
			$result = $result->row();
			$result = $result->count;
		}
		return $result;
	}
	function getRegistrationPieChartDataForAbroadBySubcat($subCategoryId = array(),$countryId = array(),$keyIds = array(),$paidType='',$startDate='',$endDate='',$crieteria='deviceWise')
	{
		$dbHandle = $this->getDbHandle('read');
		if( $crieteria == 'deviceWise')
		{
			$dbHandle->select('rt.trackingkeyId as tracking_keyid,count(distinct rt.userId) as responsescount');
			$groupByClause = 'tracking_keyid';
		}
		elseif( $crieteria == 'sourceWise')
		{
			$dbHandle->select('rt.visitorSessionid as sessionId,count(distinct rt.userId) as responsescount');
			$groupByClause = 'sessionId';
		}
		elseif( $crieteria == 'paidFreeWise')
		{
			$dbHandle->select('rt.source as pivotName,count(distinct rt.userId) as responsescount');
			$groupByClause = 'pivotName';
		}
		$dbHandle->from('registrationTracking rt');
		//$dbHandle->join('tracking_pagekey tpk','tpk.id = tusi.tracking_keyid AND tpk.page IN ("universityPage","coursePage")','inner');
		$dbHandle->join('tuserflag tf','tf.userId = rt.userId AND tf.mobileverified = "1" AND tf.isTestUser = "NO"','inner');
		$dbHandle->join('tempLMSTable tlt','tlt.userid = rt.userId','inner');
		$dbHandle->join('abroadCategoryPageData abroad','tlt.listing_type_id = abroad.course_id','inner');
		if( ! empty($subCategoryId))
			$dbHandle->where('sub_category_id IN ('.implode(',', $subCategoryId).')');
		if( ! empty($countryId))
			$dbHandle->where('country_id IN ('.implode(',', $countryId).')');
		if( ! empty($paidType))
		{
			$dbHandle->where('rt.source',$paidType);
		}
		$dbHandle->where('rt.isNewReg','yes');
		$dbHandle->where('tlt.listing_type','course');
		//$dbHandle->where('abroad.status','live');
		$dbHandle->where('rt.trackingkeyId IN ('.implode(',', $keyIds).')');
		$dbHandle->where('tlt.tracking_keyid IN ('.implode(',', $keyIds).')');
		
		if( ! empty($source))
			$dbHandle->where('tpk.siteSource',$source);
		$dbHandle->where('rt.submitDate >=',$startDate);
		$dbHandle->where('rt.submitDate <=',$endDate);
		$dbHandle->where('TIME_TO_SEC(TIMEDIFF(tlt.submit_date,rt.submitTime))<=','15');
		$dbHandle->group_by($groupByClause);
		$result = $dbHandle->get()->result_array();
		return $result;				
	}
	function getResponsePieChartDataForAbroadBySubcat($subCategoryId = array(),$countryId=array(),$keyIds =array(),$paidType='',$startDate='',$endDate='',$crieteria='')
	{
		$dbHandle = $this->getDbHandle('read');
		switch ($crieteria) {
			case 'respondents':
						$dbHandle->select('tLMS.userId as userId,tLMS.visitorsessionid as visitorsessionid');		
						$groupByClause = '';
						break;
			case 'courseWiseResponse':
						$dbHandle->select('tLMS.listing_type_id as courseId,count(distinct tLMS.id) as responsescount');
						$groupByClause = 'courseId';
						break;
			case 'uniqResponsesCourseWise':
						$dbHandle->select('tLMS.listing_type_id as courseId,count(distinct(tLMS.userId)) as count');
						$groupByClause = 'courseId';
						break;
			case 'instituteWiseResponse':
						$dbHandle->select('abroad.institute_id as instituteId,count(distinct tLMS.id) as responsescount');
						$groupByClause = 'instituteId';
						break;
			case 'uniqResponseInstituteWise':
						$dbHandle->select('abroad.institute_id as instituteId,count(distinct(tLMS.userId)) as count');
						$groupByClause = 'instituteId';
						break;
			case 'paidFree':
						$dbHandle->select('tLMS.listing_subscription_type as paidFree,count(distinct tLMS.id) as responsescount');
						$groupByClause = 'paidFree';
						break;
			default:
						$dbHandle->select('tLMS.action as action,count(distinct tLMS.id) as responsescount');
						$groupByClause = 'tLMS.action';
						break;
		}
		$dbHandle->from('tempLMSTable tLMS force index(submit_date)');
		//$dbHandle->join('tracking_pagekey tpk','tpk.id = tLMS.tracking_keyid','inner');
		$dbHandle->join('abroadCategoryPageData abroad','tLMS.listing_type_id = abroad.course_id','inner');
		if( ! empty($subCategoryId))
			$dbHandle->where('abroad.sub_category_id IN ('.implode(',', $subCategoryId).')');
		if( ! empty($countryId))
			$dbHandle->where('abroad.country_id IN ('.implode(',', $countryId).')');
		$dbHandle->where('tLMS.listing_type','course');
		//$dbHandle->where('abroad.status','live');
		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		if(!empty($paidType))
			$dbHandle->where('tLMS.listing_subscription_type',$paidType);

		$dbHandle->where('tLMS.tracking_keyid IN ('.implode(',', $keyIds).')');
		//$dbHandle->where('tLMS.tracking_keyid !=','0');
		//$dbHandle->where('tLMS.tracking_keyid is not null');

		if(! empty($startDate))
			$dbHandle->where('tLMS.submit_date >=', $startDate.' 00:00:00');
		if(! empty($endDate))
			$dbHandle->where('tLMS.submit_date <=', $endDate.' 23:59:59');		
		if( ! empty($groupByClause))			
			$dbHandle->group_by($groupByClause);
		if($crieteria == 'respondents')
				$dbHandle->limit(300,0);	
		$result = $dbHandle->get()->result_array();
		return $result;		
	}
	function getPageNames($source ='',$isStudyAbroad = 'no')
	{
		if($isStudyAbroad == 'yes')
		{
			$pageNameArray = array("universityPage","coursePage");
		}
		else
		{
			if($source == 'Desktop')
				$pageNameArray = array("courseDetailsPage","instituteListingPage");
			else
				$pageNameArray = array("courseDetailsPage","instituteListingPage","allCoursesPage");
		}
		return $pageNameArray;
	}
	function getPaidCourseCount($subCategoryId = array(),$cityId = array(),$startDate='',$endDate = '',$isStudyAbroad = 'no')
	{
		$dbHandle = $this->getDbHandle('read');
		$site = $isStudyAbroad == 'no'?'national':'abroad';
		$dbHandle->select('count(distinct s.courseId) as count');
		$dbHandle->from('courseSubscriptionHistoricalDetails s');
		if( ! empty($cityId))
		{
			$dbHandle->join('course_location_attribute c','c.course_id = s.courseId','inner');
			$dbHandle->join('institute_location_table i','i.institute_location_id = c.institute_location_id AND i.city_id IN ('.implode(',', $cityId).')','inner');
		}
		$dbHandle->join('listing_category_table l','s.courseId = l.listing_type_id AND l.listing_type= "course"','inner');

		$dbHandle->where('l.category_id IN ('.implode(',', $subCategoryId).')');
		$dbHandle->where_in('s.packType',array(1,2,375));
		$dbHandle->where('s.source',$site);
		//$dbHandle->where('s.endedOnDate','0000-00-00');
		$dbHandle->where('s.addedOnDate <=',$endDate);
		$dbHandle->where('(s.endedOnDate >=\''.$startDate.'\' OR s.endedOnDate = \'0000:00:00\')','',false);
		//$sql = "SELECT count(distinct s.courseId) from categoryPageData c INNER JOIN courseSubscriptionHistoricalDetails s ON c.course_id = s.courseId where s.packType IN (1,2,375) AND s.source = '".$site."'  AND category_id IN (".implode(',', $subCategoryId).") AND ".$whereCondition." AND addedOnDate >='".$startDate."' AND addedOnDate<='".$endDate."'";
		$result = $dbHandle->get();
		$result = $result->row();
		return $result->count;
	}
	function getPaidResponseCountBySubcat($subCategoryId = array(),$cityId = array(),$stateId = array(),$keyIds =array(),$paidType='',$startDate ='',$endDate='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('count(distinct tLMS.id) as count');
		$dbHandle->from('tempLMSTable tLMS force index(submit_date)');
		//$dbHandle->join('tracking_pagekey tpk','tpk.id = tLMS.tracking_keyid','inner');
		if( ! empty($cityId))
		{
			$dbHandle->join('course_location_attribute c','c.course_id = tLMS.listing_type_id','inner');
			$dbHandle->join('institute_location_table i','i.institute_location_id = c.institute_location_id  AND i.city_id IN ('.implode(',', $cityId).')','inner');
		}
		$dbHandle->join('listing_category_table l','tLMS.listing_type_id = l.listing_type_id AND l.listing_type = tLMS.listing_type AND l.listing_type= "course"','inner');
		$dbHandle->where('l.category_id IN ('.implode(',', $subCategoryId).')');
		if(!empty($source))
		{
			$dbHandle->where('tpk.siteSource',$source);
		}
		$dbHandle->where('tLMS.listing_subscription_type','paid');
		//$dbHandle->where('tLMS.tracking_keyid !=','0');
		//$dbHandle->where('tLMS.tracking_keyid is not null');
		$dbHandle->where('tLMS.tracking_keyid IN ('.implode(',', $keyIds).')');

		if(! empty($startDate))
			$dbHandle->where('tLMS.submit_date >=', $startDate.' 00:00:00');
		if(! empty($endDate))
			$dbHandle->where('tLMS.submit_date <=', $endDate.' 23:59:59');
		$result = $dbHandle->get();
		$result = $result->row();
		return $result->count;
	}
	function getPaidCourseCountForStudyAbroad($subCategoryId = array(),$countryId = array(),$startDate='',$endDate='')
	{
		$dbHandle = $this->getDbHandle('read');
		$dbHandle->select('count(distinct s.courseId) as count');
		$dbHandle->from('courseSubscriptionHistoricalDetails s');
		$dbHandle->join('abroadCategoryPageData abroad','s.courseId = abroad.course_id','inner');
		if( ! empty($subCategoryId))
			$dbHandle->where('sub_category_id IN ('.implode(',', $subCategoryId).')');
		if( ! empty($countryId))
			$dbHandle->where('country_id IN ('.implode(',', $countryId).')');
		$dbHandle->where_in('s.packType',1);
		$dbHandle->where('s.source','abroad');
		
//		$dbHandle->where('abroad.status','live');
		$dbHandle->where('s.addedOnDate <=',$endDate);
		$dbHandle->where('(s.endedOnDate >=\''.$startDate.'\' OR s.endedOnDate = \'0000:00:00\')','',false);

		//
        //$this->dbHandle->where('(cshd.endedOnDate >=\''.$dateRange['startDate'].'\' OR cshd.endedOnDate = "0000:00:00" ) ','',false);
		//
		$result = $dbHandle->get();
		$result = $result->row();
		return $result->count;
	}

//	Functions to render the functionality of leads for the 3 teams - Shiksha / domestic /abroad
	public function leadsTrend($inputRequest, $teamName='global'){

		/*SELECT
    users.submitTime AS Date, COUNT(1) AS ScalarValue
FROM
    registrationTracking users
        LEFT JOIN
    registrationTracking timeData ON (users.userId = timeData.userId
        AND users.submitTime < timeData.submitTime)
WHERE
    timeData.submitTime IS NULL
GROUP BY users.submitDate;*/
		$dbHandle = $this->getDbHandle('read');

		$selectFields = array(
			'date(users.submitTime) Date',
			'COUNT(1) ScalarValue',
		);
		$dbHandle->select($selectFields);
		$dbHandle->from('registrationTracking users');
		$joinClause = array(
			'users.userId = timeData.userId',
			'users.submitTime < timeData.submitTime'
		);
		$dbHandle->join('registrationTracking timeData', implode(" AND ", $joinClause), 'left');

		$whereClause = array(
			'timeData.submitTime IS NULL',
			'users.submitDate >= "'.$inputRequest->startDate .'"',
			'users.submitDate <= "'.$inputRequest->endDate .'"'
		);

		// Look up in tracking_pagekey table to find device wise information
		if($inputRequest->sourceApplication != 'all' && $inputRequest->sourceApplication != ''){
			$deviceType = $inputRequest->sourceApplication;
			$whereClause[] = "tracking.siteSource = '$deviceType'";
			$dbHandle->join('tracking_pagekey tracking', 'users.trackingKeyId = tracking.id', 'inner');
		}

		if ($teamName == 'national' || $teamName == 'domestic') { // domestic or national filters

			if ($inputRequest->categoryId != '' && $inputRequest->categoryId != 0) {
				$categoryId    = $inputRequest->categoryId;
				$whereClause[] = "users.categoryId = $categoryId";
			}
			if ($inputRequest->subcategoryId != '' && $inputRequest->subcategoryId != 0) {
				$subcategoryId = $inputRequest->subcategoryId;
				$whereClause[] = "users.subCatId = $subcategoryId";
			}

			$whereClause[] = 'users.userType != "abroad"';
		} else if($teamName == 'abroad' || strcasecmp($teamName, 'study abroad')){ // process study abroad filters
			$this->abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
			$this->getAbroadWhereClause($inputRequest, $this->abroadCommonLib, $whereClause);
		}

		$dbHandle->where(implode(" AND ", $whereClause));
		$dbHandle->group_by('users.submitDate');
		$dbHandle->order_by('Date');

//		_p($dbHandle->_compile_select()); die;
		$result = $dbHandle->get()->result();
		return $result;
	}

	public function leadsSplit($inputRequest, $teamName = 'global') {

		$validLeads = array(
			'page',
			'sourceApplication',
			'leadsType',
			'leadsTrafficSource',
			'widget',
			'conversionType'
		);

		$dbHandle = $this->getDbHandle('read');
		$selectFields = array();
		switch($inputRequest->splitAspect){
			case 'page':
				$selectFields[] = 'tracking.page PivotName';
				break;
			case 'sourceApplication':
				$selectFields[] = 'tracking.siteSource PivotName';
				break;
			case 'leadType':
				$selectFields[] = 'users.source PivotName';
				break;
			case 'leadsTrafficSource':
				$selectFields[] = 'users.visitorSessionId PivotName';
				break;
			case 'widget':
				$selectFields[] = 'tracking.keyName PivotName';
				break;
			case 'conversionType':
				$selectFields[] = 'tracking.conversionType PivotName';
				break;
			default:
				$selectFields[] = 'tracking.page PivotName';
				break;
		}

		$selectFields[] = 'COUNT(1) ScalarValue';

		$dbHandle->select($selectFields);
		$dbHandle->from('registrationTracking users');
		$joinClause = array(
			'users.userId = timeData.userId',
			'users.submitTime < timeData.submitTime'
		);
		$dbHandle->join('registrationTracking timeData', implode(" AND ", $joinClause), 'left');

		if(
			in_array($inputRequest->splitAspect, $validLeads) ||
			($inputRequest->sourceApplication != 'all' && $inputRequest->sourceApplication != '')
		) {
			$dbHandle->join('tracking_pagekey tracking', 'users.trackingKeyId = tracking.id', 'inner');
		}

		$whereClause = array(
			'timeData.submitTime IS NULL',
			'users.submitDate >= "'.$inputRequest->startDate .'"',
			'users.submitDate <= "'.$inputRequest->endDate .'"'
		);

		if($inputRequest->sourceApplication != 'all' && $inputRequest->sourceApplication != ''){
			$deviceType = $inputRequest->sourceApplication;
			$whereClause[] = "tracking.siteSource = '$deviceType'";
		}

		if ($teamName == 'national' || $teamName == 'domestic') { // domestic or national filters

			if ($inputRequest->categoryId != '' && $inputRequest->categoryId != 0) {
				$categoryId    = $inputRequest->categoryId;
				$whereClause[] = "users.categoryId = $categoryId";
			}
			if ($inputRequest->subcategoryId != '' && $inputRequest->subcategoryId != 0) {
				$subcategoryId = $inputRequest->subcategoryId;
				$whereClause[] = "users.subCatId = $subcategoryId";
			}

			$whereClause[] = 'users.userType != "abroad"';
		} else if($teamName == 'abroad' || strcasecmp($teamName, 'study abroad')){ // process study abroad filters
			$this->abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
			$this->getAbroadWhereClause($inputRequest, $this->abroadCommonLib, $whereClause);
		}

		$dbHandle->where(implode(" AND ", $whereClause));
		$dbHandle->group_by('PivotName');
		$dbHandle->order_by('ScalarValue', 'DESC');

//		_p($dbHandle->_compile_select()); die;
		$result = $dbHandle->get()->result();
//		_p($dbHandle->last_query()); return;
		return $result;

	}

	public function leadsTiles($inputRequest, $teamName = 'global'){
		/*
		1) Total Leads
		2) MMP Leads
		3) Response Leads
		4) Signup Leads
		5) Hamburger Leads
		*/

		$inputRequest->splitAspect = 'page';
		$pageWiseLeads = $this->leadsSplit($inputRequest, $teamName);
		$totalLeads = 0;
		foreach($pageWiseLeads as $oneLead){
			$totalLeads += $oneLead->ScalarValue;
		}

		$inputRequest->splitAspect = 'widget';
		$widgetWiseLeads = $this->leadsSplit($inputRequest, $teamName);
		$totalMMPLeads = 0;
		foreach($widgetWiseLeads as $oneLead){
			if(strcasecmp($oneLead->PivotName,'marketingPageForm') == 0){
				$totalMMPLeads += $oneLead->ScalarValue;
			}
		}

		$inputRequest->splitAspect = 'conversionType';
		$conversionTypeWiseLeads = $this->leadsSplit($inputRequest, $teamName);
		$totalResponseLeads = 0;
		$responseLeadTypes = array(
			'response',
			'Course shortlist'
		);
		foreach($conversionTypeWiseLeads as $oneLead){
			if(in_array($oneLead->PivotName, $responseLeadTypes)){
				$totalResponseLeads += $oneLead->ScalarValue;
			}
		}

		$totalSignupLeads = 0;
		$totalHamburgerLeads = 0;

		$inputRequest->splitAspect = 'sourceApplication';
		$signupAndHamburgerLeads = $this->leadsSplit($inputRequest, $teamName);
		foreach($signupAndHamburgerLeads as $oneLead){
			if(strcasecmp($oneLead->PivotName, 'desktop') == 0){
				$totalSignupLeads += $oneLead->ScalarValue;
			} else if(strcasecmp($oneLead->PivotName, 'mobile') == 0){
				$totalHamburgerLeads += $oneLead->ScalarValue;
			}
		}

		return array(
			'totalCount' => $totalLeads,
			'mmpCount' => $totalMMPLeads,
			'responseLeadCount' => $totalResponseLeads,
			'signupLeadCount' => $totalSignupLeads,
			'hamburgerLeadCount' => $totalHamburgerLeads,
		);

	}

	/**
	 * Get where filter to be used for study abroad leads
	 *
	 * @param stdClass $inputRequest The input request as obtained from the Common controller
	 * @param AbroadCommonLib $abroadCommonLib The abroad library used to obtain the LDB Course Ids
	 * @param array $whereClause A refernce to an array containing the where clauses
	 *
	 *
	 * @see cdmismodel::getStudyAbroadRegistrationFilterQuery
	 *
	 * TODO Consider merging getAbroadWhereClause and getStudyAbroadRegistrationFilterQuery
	 */
	private function getAbroadWhereClause($inputRequest, $abroadCommonLib, &$whereClause)
	{

		$ldbCourseIdsArray = $abroadCommonLib->getAbroadMainLDBCourses();
		foreach ($ldbCourseIdsArray as $key => $value) {
			$LDBCourses[]= $value['SpecializationId'];
		}
		if(in_array($inputRequest->categoryId, $LDBCourses))
			$LDBwhereClause = $inputRequest->categoryId;
		elseif(!empty($inputRequest->courseLevel) || $inputRequest->courseLevel != 0)
		{
			$LDBwhereClause = $this->getLDBCourseIdForStudyAbroad($inputRequest->categoryId,$inputRequest->courseLevel);
		}
		elseif(!empty($inputRequest->categoryId) || $inputRequest->categoryId !=0 )
		{
			$categoryWhereClause = $inputRequest->categoryId;
		}
		$rankingPageNames = array('universityRankingPage','courseRankingPage');
		if($inputRequest->pageName != '')
		{
			if($inputRequest->pageName == 'rankingPage')
			{
				if($inputRequest->pageType == '0' )
				{
					$wherePageNames = "'".implode("','", $rankingPageNames)."'";
					$whereClause[] = "tpk.page IN (".$wherePageNames.")";
				}

				else if($inputRequest->pageType == '1' )
				{
					$whereClause[] = "tpk.page = 'universityRankingPage'";
				}
				else if($inputRequest->pageType == '2' )
				{
					$whereClause[]  = "tpk.page = 'courseRankingPage'";
				}
			}else if($inputRequest->pageName == 'categoryPage'){
				$whereClause[] = "tpk.page IN ('categoryPage','savedCoursesTab')";
			}
			else
			{
				$whereClause[] = "tpk.page = '".$inputRequest->pageName."'";
			}
		}
		if(!empty($inputRequest->country) || $inputRequest->country !=0)
		{
			$countryWhereClause = "(prefCountry1 = ".$inputRequest->country." or prefCountry2 =".$inputRequest->country." or prefCountry3 = ".$inputRequest->country.")";
			$whereClause[] = $countryWhereClause;
		}
		if( ! empty($LDBwhereClause))
		{
			$whereClause[] = " users.desiredCourse IN (".$LDBwhereClause.")";
		}
		if( ! empty($categoryWhereClause))
		{
			$whereClause[] = " categoryId = ".$categoryWhereClause."";
		}

		$whereClause[] = 'users.userType = "abroad"';
	}

}
?>
