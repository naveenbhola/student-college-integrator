<?php

require_once dirname(__FILE__).'/PostingModelAbstract.php';

class ListingPublishModel extends PostingModelAbstract
{
	private $subsciptionConsumer;
	
    function __construct($subsciptionConsumer)
	{
		parent::__construct();
		$this->subsciptionConsumer = $subsciptionConsumer;
    }

	public function getListingData($listings)
	{
		
		$this->initiateModel('read');
	
		$clause = array();
		$bindings = array();
		foreach($listings as $listing) {
			$clause[] = "(listing_type = ? AND listing_type_id = ?)";
			$bindings[] = $listing['type'];$bindings[] = $listing['typeId'];
		}
		$sql = "SELECT listing_type, listing_type_id, username as userId, status, contact_details_id, subscriptionId, version
				FROM listings_main
				WHERE status = 'draft'
				AND (".implode(' OR ',$clause).")";
		
		$query = $this->dbHandle->query($sql,$bindings);
		return $query->result_array(); 
	}	
		
	private function trackListingPublish($listingData){
		$this->load->library('sums/Subscription_client');

		$subscriptionClient = new Subscription_client();

		$subscriptionDetails = array();
		if($listingData['subscriptionId'] == 0){
			$subscriptionDetails['BaseProductId'] = $listingData['pack_type'];
			$subscriptionDetails['SubscriptionId'] = '0';
			$subscriptionDetails['SubscriptionStartDate'] = '0000-00-00 00:00:00';
			$subscriptionDetails['SubscriptionEndDate'] = '0000-00-00 00:00:00';
		}
		else{
			$subscriptionDetails = $subscriptionClient->getSubscriptionDetails(1,str_pad($listingData['subscriptionId'], 11,'0',STR_PAD_LEFT));
			$subscriptionDetails = $subscriptionDetails[0];
			$subscriptionDetails['SubscriptionId'] = ltrim($subscriptionDetails['SubscriptionId'],'0');
		}

		$upgradeHistoryData = array(
								'courseId'				=>$listingData['listing_type_id'],
								'packType'				=>$subscriptionDetails['BaseProductId'],
								'subscriptionId'		=>$subscriptionDetails['SubscriptionId'],
								'clientId'				=>$listingData['username'],
								'subscriptionStartDate' =>$subscriptionDetails['SubscriptionStartDate'],
								'subscriptionExpiryDate'=>$subscriptionDetails['SubscriptionEndDate'],
								'addedOnDate'			=>date('Y-m-d'),
								'addedOnTime'			=>date('H:i:s'),
								'addedBy'				=>$listingData['editedBy'],
								'source'				=>'national',
								'addedFrom'				=>'listingpublish'
								);
		$this->dbHandle->insert('courseSubscriptionHistoricalDetails', $upgradeHistoryData);
	}

	private function getListingDataForPublish($type,$typeId){
		$this->dbHandle->where(array('listing_type'=>$type,'listing_type_id'=>$typeId,'status'=>'draft'));
		$details = $this->dbHandle->get('listings_main')->result_array();
		return $details[0];
	}
	
	private function _updateMediaCountData($instituteId)
	{
		/**
		 * Get new photo and video count
		 */ 
		$sql = "SELECT (
							SELECT count( * )
							FROM listing_media_table lmt, institute_uploaded_media ium
							WHERE lmt.media_id = ium.media_id
							AND lmt.type_id = ium.listing_type_id
							AND ium.listing_type = 'institute'
							AND ium.media_type = 'photo'
							AND ium.status <> 'deleted'
							AND lmt.media_type = 'photo'
							AND lmt.status = 'live'
							AND lmt.type = 'institute'
							AND lmt.type_id = i.institute_id
							GROUP BY lmt.type_id
					) AS photo,
						(			
								SELECT count( * )
								FROM listing_media_table lmt, institute_uploaded_media ium
								WHERE lmt.media_id = ium.media_id
								AND lmt.type_id = ium.listing_type_id
								AND ium.listing_type = 'institute'
								AND ium.media_type = 'video'
								AND ium.status <> 'deleted'
								AND lmt.media_type = 'video'
								AND lmt.status = 'live'
								AND lmt.type = 'institute'
								AND lmt.type_id = i.institute_id
								GROUP BY lmt.type_id
					) AS video
				FROM institute i
				WHERE i.status = 'live'
				AND i.institute_id = ?";
				
		$query = $this->dbHandle->query($sql,array($instituteId));
		$result = $query->row_array();
		
		$numPhotos = intval($result['photo']);
		$numVideos = intval($result['video']);
		
		/**
		 * Get current photo and video count
		 */ 
		$sql = "SELECT photo_count,video_count
				FROM institute_mediacount_rating_info
				WHERE institute_id = ?";
		$query = $this->dbHandle->query($sql,array($instituteId));
		$numRows = $query->num_rows();
		
		if($numRows <= 0){
			$data = array(
				'institute_id' => $instituteId,
				'photo_count' => $numPhotos,
				'video_count' => $numVideos
			);
			$this->dbHandle->insert('institute_mediacount_rating_info',$data);
		}else{
			$sql = "UPDATE institute_mediacount_rating_info
					SET photo_count = ?, video_count = ?
					WHERE institute_id = ?";
			$this->dbHandle->query($sql,array($numPhotos,$numVideos,$instituteId));
		}
	}
	
	private function _updateCategoryPageData($courseId)
	{
		$sql = "UPDATE categoryPageData
				SET status = 'history'
				WHERE course_id = ?
				AND status = 'live'";
				
		$this->dbHandle->query($sql,array($courseId));
		
		$sql = "INSERT INTO `categoryPageData` (
						`course_id` ,
						`category_id` ,
						`ldb_course_id` ,
						`institute_id` ,
						`pack_type` ,
						`institute_pack_type` ,
						`city_id` ,
						`state_id` ,
						`country_id` ,
						`status`
					)
				SELECT DISTINCT cd.course_id, lsm.categoryID, clm.LDBCourseID, cd.institute_id, lm.pack_type, lm2.pack_type, ilt.city_id, cct.state_id, ilt.country_id, cd.status
				FROM course_details cd
				JOIN listings_main lm ON (
											lm.listing_type_id = cd.course_id
											AND lm.listing_type = 'course'
											AND lm.status = 'live'
										)
				JOIN listings_main lm2 ON (
											lm2.listing_type_id = cd.institute_id
											AND lm2.listing_type = 'institute'
											AND lm2.status = 'live' 
										)
				JOIN institute i ON (
										i.institute_id = cd.institute_id
										AND i.status = 'live'
									)
				JOIN course_location_attribute cla ON (
														cla.course_id = cd.course_id
														AND cla.attribute_type = 'Head Office'
														AND cla.status = 'live'
													)
				JOIN institute_location_table ilt ON (
														ilt.institute_location_id = cla.institute_location_id
														AND ilt.status = 'live'
													)
				JOIN countryCityTable cct ON (
												cct.city_id = ilt.city_id
											)
				JOIN clientCourseToLDBCourseMapping clm ON (
																clm.clientCourseID = cd.course_id
																AND clm.status='live'
														)
				JOIN LDBCoursesToSubcategoryMapping lsm ON (
																lsm.ldbCourseID = clm.LDBCourseID
																AND lsm.status = 'live'
														)
				WHERE cd.status = 'live'
				AND cd.course_id = ? ";
						
		$this->dbHandle->query($sql,array($courseId));
		
		$sql = "UPDATE listing_category_table
				SET status = 'history',version=0
				WHERE listing_type = 'course'
				AND listing_type_id = ?
				AND status = 'live'";
		$this->dbHandle->query($sql,array($courseId));
		
		$sql = "UPDATE listing_category_table lct,course_details cd
				SET lct.status = 'history',lct.version=0
				WHERE listing_type = 'institute'
				AND listing_type_id = cd.institute_id
				AND cd.course_id = ?
				AND lct.status = 'live'
				AND cd.status='live'";
		$this->dbHandle->query($sql,array($courseId));
		
		$sql = "INSERT INTO `listing_category_table` (
							`listing_category_id` ,
							`listing_type` ,
							`listing_type_id` ,
							`category_id` ,
							`version` ,
							`status`
						)
						SELECT DISTINCT NULL, 'course', cpd.course_id, cpd.category_id, cd.version, cd.status
						FROM categoryPageData cpd
						JOIN course_details cd ON (cd.course_id = cpd.course_id AND cd.status = 'live')
						WHERE cpd.status = 'live'
						AND cpd.category_id >0
						AND cpd.course_id = ? ";
		$this->dbHandle->query($sql,array($courseId));
		
		$sql = "INSERT INTO `listing_category_table` (
							`listing_category_id` ,
							`listing_type` ,
							`listing_type_id` ,
							`category_id` ,
							`version` ,
							`status`
						)
						SELECT DISTINCT NULL, 'institute', cpd.institute_id, cpd.category_id, i.version, i.status
						FROM categoryPageData cpd
						JOIN institute i ON (i.institute_id = cpd.institute_id AND i.status = 'live')
						WHERE cpd.status = 'live'
						AND cpd.institute_id = (
													SELECT cd.institute_id
													FROM course_details cd
													WHERE cd.status = 'live'
													AND cd.course_id = ? LIMIT 1
											)
						AND cpd.category_id >0";
		$this->dbHandle->query($sql,array($courseId));
	}
	
	private function _canConsumeSubscription($listingType,$listingId)
	{		
		/**
		 * Get subscription details for 'live' state
		 */
		$sql = "SELECT count(*) as num, subscriptionId
				FROM listings_main
				WHERE listing_type = ?
				AND listing_type_id = ?
				AND status = 'live'
				GROUP BY subscriptionId";
        $result = $this->dbHandle->query($sql,array($listingType,$listingId));
        $count=0;
	    $subscriptionId=0;
		
        if($result->result() != NULL) {
            $count = $result->first_row()->num;
			$subscriptionId=$result->first_row()->subscriptionId;
        }
		
		/**
		 * Get subscription details for 'draft' state
		 */
		$sql = "SELECT subscriptionId
				FROM listings_main
				WHERE listing_type = ?
				AND listing_type_id = ?
				AND status = 'draft'";
        $result = $this->dbHandle->query($sql,array($listingType,$listingId));
		
	    $newSubscriptionId = 0;
        if($result->result() != NULL) {
			$newSubscriptionId = $result->first_row()->subscriptionId;
        }
		
		if($count==0 || ($newSubscriptionId != $subscriptionId && $newSubscriptionId != 0)) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	private function _consumeSubscription($listingType,$listingId,$audit)
	{
		$sql = "SELECT subscriptionId, username
				FROM listings_main
				WHERE listing_type = ?
				AND listing_type_id = ?
				AND status = 'live'";
        $query = $this->dbHandle->query($sql,array($listingType,$listingId));
		
		if ($query->result() != NULL) {
			$subscriptionId = $query->first_row()->subscriptionId;
			$clientId = $query->first_row()->username;
			$this->subsciptionConsumer->consumeSubscription(1,$subscriptionId,'-1',$clientId,$audit['editedBy'],'-1',$listingId,$listingType,'-1','-1');
		}
	}
	
	/**
	 * Function made public; being used in Beacon_server.php
	 */
	public function _checkLDBCourseChanged($courseId) {
		
		$this->initiateModel('read');
		$LDBCoursesChanged = false;
		$draftLDBCourses = array();
		$liveLDBCourses = array();
		
		$sql = "SELECT status, LDBCourseID
			FROM clientCourseToLDBCourseMapping
			WHERE clientCourseID = ?
			AND (status = 'draft' OR status = 'live')";
		
		$result = $this->dbHandle->query($sql,array($courseId))->result_array();
		
		if(count($result)) {
			foreach($result as $row) {
				if($row['status'] == 'draft') {
					$draftLDBCourses[$row['LDBCourseID']] = TRUE;
				}
				else if($row['status'] == 'live') {
					$liveLDBCourses[$row['LDBCourseID']] = TRUE;
				}
			}
		}
		
		if(count(array_diff_key($liveLDBCourses, $draftLDBCourses)) || count(array_diff_key($draftLDBCourses, $liveLDBCourses))) {
			$LDBCoursesChanged = true;
		}
		
		return $LDBCoursesChanged;
	}
	
	/**
	 * Function made public; being used in Beacon_server.php
	 */
	public function _checkPackTypeChanged($courseId) {
		
		$this->initiateModel('read');
		$packTypeChanged = false;
		$draftPackType = null;
		$livePackType = null;
		
		$sql = "SELECT status, pack_type
			FROM listings_main
			WHERE listing_type_id = ?
			AND listing_type = 'course'
			AND (status = 'draft' OR status = 'live')";
		
		$result = $this->dbHandle->query($sql,array($courseId))->result_array();
		
		if(count($result)) {
			foreach($result as $row) {
				if($row['status'] == 'draft') {
					$draftPackType = $row['pack_type'];
				}
				else if($row['status'] == 'live') {
					$livePackType = $row['pack_type'];
				}
			}
		}
		
		if($draftPackType != $livePackType) {
			$packTypeChanged = true;
		}
		
		return $packTypeChanged;
	}
	
	/**
	 * Function made public; being used in Beacon_server.php
	 */
	public function _updateAlsoViewed($courseId) {
		
		$this->initiateModel('write');

		$sql = "SELECT group_concat(distinct course_id) as courseids
						from alsoViewedFilteredCourses
						where recommended_course_id = ?
						AND status = 'live'";
		$courseids = $this->dbHandle->query($sql,array($courseId))->row_array();
		if(!empty($courseids) && !empty($courseids['courseids'])){
			$courseids = $courseids['courseids'];
		}else{
			$courseids = array();
		}

		$sql = "UPDATE also_viewed_course_mapping
			SET is_Updated = 0
			WHERE course_id = ? ";
		if(!empty($courseids)){
			$sql .= " OR course_id IN (".$courseids.")";
		}

		$this->dbHandle->query($sql,array($courseId,$courseId));
		
		$sql = "UPDATE alsoViewedFilteredCourses
			SET status = 'history'
			WHERE course_id = ?
			OR recommended_course_id = ?";
		$this->dbHandle->query($sql,array($courseId,$courseId));
	}
	
	/*
	 * Below is the query to fetch all courses for which recruiting company mapping is edited and delete cache of those courses
	 */
	private function unsetCacheForEdittedCourses($instituteId) {
		$edittedCourseIds = array();
		$sql = "SELECT 1 FROM company_logo_mapping WHERE institute_id = ? AND status = 'draft'";
		$numRows = $this->dbHandle->query($sql, $instituteId)->num_rows();
		
		if($numRows > 0) {
			$sql = "SELECT DISTINCT listing_id ".
				"FROM company_logo_mapping ".
				"WHERE institute_id = ? ".
				"AND status IN ('live', 'draft')";
			$edittedCourseIds = $this->dbHandle->query($sql, $instituteId)->result_array();
			
			if(!empty($edittedCourseIds)) {
				$listingCacheObj = $this->load->library('listing/cache/ListingCache');
				
				foreach($edittedCourseIds as $row) {
					$listingCacheObj->deleteCourse($row['listing_id']);
				}
			}
			unset($numRows);
			unset($listingCacheObj);
			unset($edittedCourseIds);
		}
	}

	/*
	* Below function will check if an Institute is being posted for the first time 
	*/
	private function _checkIfNewListing($listingId, $listingType = 'institute'){
		$sql = "SELECT 1 FROM listings_main WHERE listing_type_id = ? AND listing_type = ? AND status = 'live'";
		$numRows = $this->dbHandle->query($sql, array($listingId,$listingType))->num_rows();
		if($numRows > 0){
			return false;
		}
		else{
			return true;
		}
	}

	/*
	* Below function will migrate Insitute from Old tables to new tables. This is required in Shiksha 2.0
	*/
	private function _migrateToNewTables ($instituteId){
		Modules::run('listingMigration/ListingsMigration/migrateDataCron', $instituteId);
		Modules::run('listingMigration/ListingsMigration/migrateDataCronListingsMain', $instituteId);
	}
}
