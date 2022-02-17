<?php

require_once dirname(__FILE__).'/PostingModelAbstract.php';

class ListingDeleteModel extends PostingModelAbstract
{
	private $messageBoardClient;
	
    function __construct($messageBoardClient)
	{
		parent::__construct();
		$this->messageBoardClient = $messageBoardClient;
    }

	public function deleteListing($listingType,$listingTypeId, $userId)
	{
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		if($listingType == 'institute') {
			$this->_deleteInstitute($listingTypeId, $userId);
			
			/**
			 * Delete all courses of the institute
			 */ 
			$sql = "SELECT DISTINCT course_id FROM course_details WHERE institute_id = ?";
			$query = $this->dbHandle->query($sql,array($listingTypeId));
			$courseIds = $query->result_array();
			foreach($courseIds as $courseId) {
				$this->_deleteCourse($courseId['course_id'], $userId);
			}
			$careerAssociatedCourseData = $this->getCourseAssociatedWithCareers($courseIds, $userId, $listingType);
		}
		else {
			$this->_deleteCourse($listingTypeId, $userId);
			$careerAssociatedCourseData = $this->getCourseAssociatedWithCareers(array($listingTypeId), $userId, 'course');
		}
		
		$this->dbHandle->trans_complete();
		if($this->dbHandle->trans_status() === TRUE) {
			//Topic delete logic for courses and institutes
			if($listingType == 'course'){
				$sql = "SELECT lm.threadId as thread_id,lc.category_id as category_id FROM listings_main lm INNER JOIN course_details c ON(lm.listing_type_id = c.course_id ) INNER JOIN listing_category_table lc ON(lc.listing_type_id = c.course_id) where lm.listing_type='course' and  lc.listing_type = 'course' and lc.status ='live'and c.course_id = ? and lm.threadId !='' limit 0,1 ";
				$query = $this->dbHandle->query($sql, array($listingTypeId));
			} else {
				$sql = "SELECT lm.threadId as thread_id, lc.category_id as category_id FROM listings_main lm INNER JOIN institute i ON (lm.listing_type_id = i.institute_id) INNER JOIN listing_category_table lc ON (lc.listing_type_id = i.institute_id)  WHERE lm.listing_type = 'institute' and  lc.listing_type = 'institute' and lc.status='live' and i.institute_id = ? and lm.threadId !='' limit 0,1";
				$query = $this->dbHandle->query($sql, array($listingTypeId));
			}
			foreach($query->result() as $row) {
				$this->messageBoardClient->deleteTopic(1, $row->thread_id);
			}
			//send mail to user when 
			$this->mailOnCourseDeleteAssociatedWithCareer($careerAssociatedCourseData, $userId);
		}
	}
	
	private function _deleteInstitute($instituteId, $userId = NULL)
	{
		/**
		 * Change last modified editedBy and last modification time
		 */
		if(!empty($userId)) {
			$sql = "UPDATE listings_main SET last_modify_date = NOW(), editedBy = ? WHERE listing_type = 'institute' AND listing_type_id = ? AND status IN ('live','draft','queued')";
			$this->dbHandle->query($sql, array($userId, $instituteId));	

			//update timestamp before marking the current entry as deleted
			$sql = "UPDATE institute_facilities set `timestamp` = NOW() WHERE listing_type = 'institute' AND listing_type_id = ? AND status IN ('live','draft','queued')";
			$this->dbHandle->query($sql, array($userId, $instituteId));
		}
		
		/**
		 * Define all the tables to be updated for publishing of the course
		 * Field 1: Listing type id column.
		 * Field 2: Listing type column. Some tables will not have this column.
		 */
		$tables = array(
						'listings_main' => array('listing_type_id','listing_type'),
						'institute' => array('institute_id'),
						'institute_location_table' => array('institute_id'),
						'listing_contact_details' => array('listing_type_id','listing_type'),
						'listing_attributes_table' => array('listing_type_id','listing_type'),
						'listing_media_table' => array('type_id','type'),
						'header_image' => array('listing_id','listing_type'),
						'institute_join_reason' => array('institute_id'),
						'company_logo_mapping' => array('listing_id','listing_type'),
						'institute_facilities' => array('listing_type_id','listing_type')
					);
		
		foreach($tables as $tableName => $tableColumns) {
		
			$listingTypeIdColumnName = $tableColumns[0];
			$listingTypeColumnName = $tableColumns[1];
			
			/**
			 * Mark draft entries as live
			 */ 
			$sql = "UPDATE $tableName
					SET status = 'deleted'
					WHERE $listingTypeIdColumnName = ?
					".($listingTypeColumnName ? " AND $listingTypeColumnName = 'institute' " : " ")."
					AND status IN ('live','draft','queued')";
			$this->dbHandle->query($sql,array($instituteId));
		}
		
		$sql = "UPDATE categoryPageData SET status = 'history' WHERE institute_id = ? AND status IN ('live','draft','queued')";
		$this->dbHandle->query($sql,array($instituteId));
		
		$sql = "UPDATE listing_category_table SET status = 'history' WHERE listing_type = 'institute' AND listing_type_id = ? AND status IN ('live','draft','queued')";
		$this->dbHandle->query($sql,array($instituteId));
		
		$sql = "UPDATE listings_ebrochures SET status = 'deleted' WHERE listingType = 'institute' AND listingTypeId = ? AND status IN ('live','draft','queued')";
		$this->dbHandle->query($sql,array($instituteId));
		
		$sql = "UPDATE event SET status_id = 1 WHERE listing_type_id = ? and listingType = 'institute'";
		$this->dbHandle->query($sql,array($instituteId));
		
		$sql 	= "SELECT admission_notification_id FROM institute_examinations_mapping_table WHERE institute_id = ?";
		$query 	= $this->dbHandle->query($sql,array($instituteId));
		$notificationIds = $query->result_array();
		foreach($notificationIds as $notificationId) {
			$sql 	= "UPDATE listings_main SET status = 'deleted' WHERE listing_type ='notification' and listing_type_id = ?";
			//$this->dbHandle->query($sql, array($notificationId));
			$this->dbHandle->query($sql, array($notificationId['admission_notification_id'])); // changes for LF -2059
			$sql = "UPDATE event SET status_id = 1 WHERE listing_type_id = ? and listingType = 'notification'";
			$this->dbHandle->query($sql,array($instituteId));
		}
		
		$this->_deleteSearchIndex('institute',$instituteId);
	}
	
	private function _deleteCourse($courseId, $userId)
	{
		/**
		 * Change last modified editedBy and last modification time
		 */
		if(!empty($userId)) {
			$sql = "UPDATE listings_main SET last_modify_date = NOW(), editedBy = ? WHERE listing_type = 'course' AND listing_type_id = ? AND status IN ('live','draft','queued')";
			$this->dbHandle->query($sql, array($userId, $courseId));	
		}
		/**
		 * Define all the tables to be updated for publishing of the course
		 * Field 1: Listing type id column.
		 * Field 2: Listing type column. Some tables will not have this column.
		 */ 
		$tables = array(
						'listings_main' => array('listing_type_id','listing_type'),
						'course_details' => array('course_id'),
						'listing_contact_details' => array('listing_type_id','listing_type'),
						'course_location_attribute' => array('course_id'),
						'listing_attributes_table' => array('listing_type_id','listing_type'),
						'listing_media_table' => array('type_id','type'),
						'header_image' => array('listing_id','listing_type'),
						'listingExamMap' => array('typeid','type'),
						'othersExamTable' => array('listingTypeId','listingType'),
						'clientCourseToLDBCourseMapping' => array('clientCourseID'),
						'course_attributes' => array('course_id'),
						'course_features' => array('listing_id'),
						'company_logo_mapping' => array('listing_id','listing_type'),
						'userShortlistedCourses' => array('courseId'),
					);
		
		foreach($tables as $tableName => $tableColumns) {
		
			$listingTypeIdColumnName = $tableColumns[0];
			$listingTypeColumnName = $tableColumns[1];
			
			/**
			 * Mark draft entries as live
			 */ 
			$sql = "UPDATE $tableName
					SET status = 'deleted'
					WHERE $listingTypeIdColumnName = ?
					".($listingTypeColumnName ? " AND $listingTypeColumnName = 'course' " : " ")."
					AND status IN ('live','draft','queued')";
			$this->dbHandle->query($sql,array($courseId));
		}
		
		$sql = "UPDATE categoryPageData SET status = 'history' WHERE course_id = ? and status IN ('live','draft','queued')";
		$this->dbHandle->query($sql,array($courseId));
		
		$sql = "UPDATE listing_category_table SET status = 'history' WHERE listing_type = 'course' AND listing_type_id = ? AND status IN ('live','draft','queued')";
		$this->dbHandle->query($sql,array($courseId));
		
		$sql = "UPDATE listings_ebrochures SET status = 'deleted' WHERE listingType = 'course' and listingTypeId = ? AND status IN ('live','draft','queued')";
		$this->dbHandle->query($sql,array($courseId));
		
		$sql = "UPDATE event SET status_id = 1 WHERE listing_type_id = ? and listingType = 'course'";
		$this->dbHandle->query($sql,array($instituteId));

		$sql = "UPDATE courseSubscriptionHistoricalDetails SET endedOnDate = date('Y-m-d'), endedOnTime = date('H:i:s'), updatedBy = $userId where endedOnDate = '0000-00-00' and endedOnTime = '00:00:00' and courseId = ? ";
		$this->dbHandle->query($sql,array($courseId));
		
		$this->_deleteSearchIndex('course',$courseId);
	}
	
	private function _deleteSearchIndex($listingType,$listingId)
	{
		$data = array(
			'listing_type' => $listingType,
			'listing_id' => $listingId,
			'operation' => 'delete',
			'status' => 'pending'
		);
		$this->dbHandle->insert('indexlog',$data);
	}

	/**
    * Purpose       : takes input as course ids and checks if it is being used with career(LF-3129)
    * Params        : array of course ids, user id, type of listing
    * Author        : Ankit Garg
    * date          : 2015-07-10
    * return  		: returns an array of course ids which are associated with careers
    */
	function getCourseAssociatedWithCareers($courseIds = array(), $userId, $listingType = 'course') {
		$courseIdsArray = array();
		if($listingType == 'institute') {
			foreach($courseIds as $courseId) {
				$courseIdsArray[] = $courseId['course_id'];
			}
		}
		else {
			if(is_array($courseIds)) {
				foreach($courseIds as $courseId) {
					$courseIdsArray[] = $courseId;
				}
			}
			else {
				$courseIdsArray[] = $courseIds;
			}
		}
		return $this->coursesAttachedToAnyCareer($courseIdsArray);
	}

	/**
    * Purpose       : sends mail to user when  (LF-3129)
    * Params        : array of course ids
    * Author        : Ankit Garg
    * date          : 2015-07-10
    * return  		: returns true if mail has been sent otherwise returns false
    */
	function mailOnCourseDeleteAssociatedWithCareer($careerAssociatedCourseData, $userId) {
		if(!empty($careerAssociatedCourseData) && is_array($careerAssociatedCourseData) && !empty($userId)) {
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder                                 = new ListingBuilder;
			$this->courseRepository                         = $listingBuilder->getCourseRepository();
			$courses = $this->courseRepository->findMultiple(array_keys($careerAssociatedCourseData));
			$mailData = array();
			$content = '<p>Hi,</p>';
			$this->load->model("user/usermodel");
			$UserModel = new usermodel();
			$userName = $UserModel->getNameByUserId($userId);
			foreach ($courses as $courseId => $course) {
				$content .= "<p><p>Data for deleted course id <strong>".$courseId."</strong></p>".
							"<p>1. Career Name: <strong>".$careerAssociatedCourseData[$courseId]['careerName']."</strong></p>".
							"<p>2. Deleted Course ID: <strong>".$courseId."</strong></p>".
							"<p>3. Deleted Course Name: <strong>".$course->getName()."</strong></p>".
							"<p>4. College Name: <strong>".$course->getInstituteName()."</strong></p>".
							"<p>5. Deleted by: <strong>".$userName[0]['firstname']. " ".$userName[0]['lastname']." (".$userId.")</strong></p>".
							"<p>6. Date & Time of Deletion: <strong>".date('d-m-Y : H:i:s')."</strong></p> </p>";
			}
			$this->load->library('Alerts_client');
			$alertClient = new Alerts_client();
			
			$subject = "A Course on Career Central has been deleted";
			$alertClient->externalQueueAdd("12", ADMIN_EMAIL, 'karan.chawla@shiksha.com', $subject, $content, "html", '', 'n');
			return true;
		}
		return false;
	}

	/**
    * Purpose       : fire sql for course ids and check if it is attached to any career (LF-3129)
    * Params        : array of course ids
    * Author        : Ankit Garg
    * date          : 2015-07-10
    * return  		: returns an associateed array containing course id and its career name
    */
	function coursesAttachedToAnyCareer($courseIds = array()){
        if(empty($courseIds)){
            return false;
        }
        $ids = $courseIds;
        if(!is_array($courseIds) && is_int($courseIds)){
            $ids     = array();
            $ids[]     = $courseIds;
        }

        $sql         	= "SELECT `CP_CareerTable`.name, `CP_CareerTable`.careerId, value as courseId FROM `CP_CareerPageValueTable` ".
        				  "LEFT JOIN `CP_CareerTable`  ".
						  "ON `CP_CareerTable`.careerId = `CP_CareerPageValueTable`.careerId ".
        				  "WHERE value IN (".implode(",", $ids).") ".
        				  "AND `keyname` LIKE 'indiaCourseId_%' ".
        				  "AND `CP_CareerTable`.`status` = 'live' ".
        				  "AND `CP_CareerPageValueTable`.`status` = 'live' ".
        				  "AND value != ''";

        $query = $this->dbHandle->query($sql);
        $associatedCourseData = array();
        foreach($query->result() as $row) {
        	$associatedCourseData[$row->courseId] = array('careerName'=>$row->name, 'courseId' => $row->courseId);
        }
        return array_unique($associatedCourseData);
    }
}
