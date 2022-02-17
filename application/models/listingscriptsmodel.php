<?php 
class ListingScriptsModel extends MY_Model {
    function __construct() {
		parent::__construct('Listing');
    }
    
    function getInstituteLogoUrls($count) {
        $dbHandle = $this->getReadHandle();
        if(empty($count)) {
            $count = 5000;
        }
        $sql = "SELECT DISTINCT i.logo_link ".
				"FROM institute as i ".
				"INNER JOIN listings_main as lm ON lm.listing_type_id = i.institute_id AND lm.listing_type = 'institute' AND lm.status = 'live' ".
				"WHERE i.status = 'live' AND i.logo_link IS NOT NULL AND i.institute_type NOT IN ('Department','Department_Virtual')".
				"ORDER BY lm.last_modify_date DESC ".
				"LIMIT ?";
		$insituteLogoLinks = $dbHandle->query($sql, array((int)$count))->result_array();
		return $insituteLogoLinks;
    }

    function dbTrackingForGAParams($data) {
        $dbHandle = $this->getWriteHandle();
        if(!empty($data)) {
            $queryData = array(
                        'id'            => NULL,
                        'currentUrl'    => $data['currentUrl'],
                        'gaValueString' => $data['gaString'],
                        'source'        => $data['source']
                    );
            $dbHandle->insert('trackGACustomVariableParams', $queryData);
        }
    }
	
	function updateFacilitiesInDb($params = array(), $logFile) {
		$dbHandle = $this->getWriteHandle();
		if(empty($params)){
			error_log( date("Y-m-d H:i:s") . ": empty params \n", 3, $logFile);
			return;
		}
		$instituteIds = array_keys($params);
		$sql = "UPDATE institute_facilities SET status = 'history', version = 0 WHERE listing_type = 'institute' AND listing_type_id IN (?) ";
		error_log( date("Y-m-d H:i:s") . ": update SQL  \n", 3, $logFile);
		error_log( date("Y-m-d H:i:s") . ": ".$sql."  \n", 3, $logFile);
		$dbHandle->query($sql, array($instituteIds));
		$affectedRows = $dbHandle->affected_rows();
		error_log( date("Y-m-d H:i:s") . ": old rows affected: ".$affectedRows."  \n", 3, $logFile);
		
		$batch = array();
		foreach($params as $instituteId => $attributes){
			foreach($attributes as $attribute){
				$data = array();
				$data['listing_type'] = 'institute';
				$data['listing_type_id'] = $instituteId;
				$data['facility_id'] = $attribute['attributeId'];
				$data['description'] = $attribute['attributeDesc'];
				$data['status'] = 'live';
				$batch[] = $data;
			}
		}
		$dbHandle->insert_batch('institute_facilities', $batch);
		$affectedRows = $dbHandle->affected_rows();
		error_log( date("Y-m-d H:i:s") . ": New rows added: ".$affectedRows."  \n", 3, $logFile);
		error_log( date("Y-m-d H:i:s") . ": New rows should have been added: ".count($batch)."  \n", 3, $logFile);
	}

	function updateCareerSynonymsInDB($params = array(), $logFile){
		$dbHandle = $this->getWriteHandle();
		if(empty($params)){
			error_log( date("Y-m-d H:i:s") . ": empty params \n", 3, $logFile);
			return;
		}
		$batch = array();
		foreach($params as $careerId => $careers){
			foreach($careers as $career){
				$data = array();
				$data['career_id'] = $careerId;
				$data['synonym'] = $career;
				$batch[] = $data;
			}
		}
		$dbHandle->where_in('career_id',array_keys($careers));
		$dbHandle->delete('career_synonyms');
		$dbHandle->insert_batch('career_synonyms', $batch);
		$affectedRows = $dbHandle->affected_rows();
		error_log( date("Y-m-d H:i:s") . ": New rows added: ".$affectedRows."  \n", 3, $logFile);
		error_log( date("Y-m-d H:i:s") . ": New rows should have been added: ".count($batch)."  \n", 3, $logFile);
	}

	function getDataInChunks($instituteIds,$batchsize = 50){
		$institutes = array();$count = count($instituteIds);

		for($i=0,$z=0;$i<$count;$i++){
			if($i%$batchsize == 0 && $i!= 0){
				$z++;
			}
			$institutes[$z][] = $instituteIds[$i];
		}
		return $institutes;
	}
	
	function updateOldListURLs($data = array()){
		$dbHandle = $this->getWriteHandle();
		if(!empty($data)){
			$dbHandle->insert_batch('old_list_urls', $data);
		}
	}

	function getTotalCourseCount(){
		$dbHandle = $this->getReadHandle();
		$sql = "select count(distinct(cd.course_id)) as totalCourseCount from course_details cd inner join institute i on i.institute_id=cd.institute_id where i.institute_type not in ('Department','Department_Virtual')";
		$query = $dbHandle->query($sql);
		$result = $query->row_array();
		return $result['totalCourseCount'];
	}

	function getCourseIdsInChunks($limit,$offset){
		$dbHandle = $this->getReadHandle();
		$sql = "select distinct(cd.course_id) from course_details cd inner join institute i on i.institute_id=cd.institute_id where i.institute_type not in ('Department','Department_Virtual') and cd.status in ('live','deleted','history') limit ?, ? ";
		
		$query = $dbHandle->query($sql, array((int)$offset, (int)$limit));
		$result = $query->result_array();
		return $this->getColumnArray($result,'course_id');
	}

	function getDataFromListingsTable($courseIds){
		$dbHandle = $this->getReadHandle();

		$dbHandle->select('listing_id,listing_type_id,submit_date,approve_date,expiry_date,last_modify_date,status,username,pack_type,subscriptionId,editedBy');
		$dbHandle->where('listing_type','course');
		$dbHandle->where_not_in('status',array('cancelled','draft'));
		$dbHandle->where_in('listing_type_id',$courseIds);
		$dbHandle->order_by('listing_id','asc');

		$query = $dbHandle->get('listings_main');
		$result = $query->result_array();
		$finalData = array();
		foreach($result as $row){
			$finalData[$row['listing_type_id']][] = $row;
		}
		return $finalData;
	}

	function insertSubscriptionDataIntoNewTable($insertData){
		$dbHandle = $this->getWriteHandle();//_p($insertData);die;
		$dbHandle->insert_batch('courseSubscriptionHistoricalDetails',$insertData);
	}

	function insertExchangeRatesForNational($insertData){
		$dbHandle = $this->getWriteHandle();
		$dbHandle->where("status","live");
		$dbHandle->update("nationalCurrency",array('status'=>'history'));
		$dbHandle->insert_batch("nationalCurrency",$insertData);
		_p($insertData);die;
	}

	function fetchCourses(){
		$dbHandle = $this->getReadHandle();
		$sql = "select distinct course_id from shiksha_courses where status = 'live'";
		$query = $dbHandle->query($sql);
		$result = $query->result_array();
		$finalResult = array();
		foreach ($result as $key => $value) {
			$finalResult[] = $value['course_id'];
		}
		return $finalResult;
	}
	function fetchInstitutesUniversities($listing_type='all')
	{
		if($listing_type != 'all' && !in_array($listing_type, array('institute','university')))
		{
			return ;
		}
		$dbHandle = $this->getReadHandle();
		$sql = "SELECT distinct listing_id from shiksha_institutes where status = 'live' AND (is_dummy = 0 OR is_dummy is NULL)".($listing_type != 'all' ? " AND listing_type = ?" : "");
		$query = $dbHandle->query($sql, array($listing_type));
		$result = $query->result_array();
		$finalResult = array();
		foreach ($result as $key => $value) {
			$finalResult[] = $value['listing_id'];
		}
		return $finalResult;
	}

	function sendStreamDigestForOldUsers(){
		$startDate = "2017-06-01 00:00:00";
		$endDate = "2017-06-01 00:00:30";

		$dbHandle = $this->getReadHandle();
		$limit = 1000;$offset = 0;
		do{
			$userStreams = array();

			$sql = "SELECT userId from tuser where usercreationDate >= ? and usercreationDate <= ? order by usercreationDate asc limit $offset,$limit";
			$query = $dbHandle->query($sql,array($startDate,$endDate))->result_array();
			$userIds = $this->getColumnArray($query,'userId');
			// _p($userIds);die;

			if(!empty($userIds)){
				$sql = "SELECT userId,streamId from tUserInterest where status='live' and userId in (?)";
				$query = $dbHandle->query($sql,array($userIds))->result_array();
				foreach ($query as $row) {
					$userStreams[$row['userId']][$row['streamId']] = $row['streamId'];
				}

				$sql = "SELECT userId,entityId from tuserFollowTable where status='follow' and followType like '%stream_interest%' and userId in (?)";
				$query = $dbHandle->query($sql,array($userIds))->result_array();
				$tagIds = $this->getColumnArray($query,'entityId');
				$tagIds = array_unique($tagIds);
				$tagToStreamMapping = $this->getStreamsByTagIds($tagIds);
				
				foreach ($query as $row) {
					if(!empty($tagToStreamMapping[$row['entityId']])){
						$userStreams[$row['userId']][$tagToStreamMapping[$row['entityId']]] = $tagToStreamMapping[$row['entityId']];
					}
				}

				// _p($userStreams);die;

				$this->load->library("common/jobserver/JobManagerFactory");
				foreach ($userStreams as $userId => $streamIds) {
					foreach ($streamIds as $streamId) {
						try {
						    $jobManager = JobManagerFactory::getClientInstance();
						    if ($jobManager) {
						        $jobManager->addBackgroundJob("StreamDigestMailerQueue", array('streamId' => $streamId, 'userId' => $userId, 'source' => 'cron'));
						    }
						}catch (Exception $e) {
						    error_log("=================== Unable to connect to rabbit-MQ");
						}
					}
				}
			}
			$offset += $limit;
		}while(!empty($userIds));
		error_log("====================== Script completed ========== ");
		_p('DONE');
	}

	public function getStreamsByTagIds($tagIds){
	    if(empty($tagIds)){
	        return array();
	    }
	    $dbHandle = $this->getReadHandle();
	    $sql = "SELECT tag_id,entity_id from tags_entity where tag_id in (?) and entity_type = 'Stream' and status='live'";
	    $result = $dbHandle->query($sql, array($tagIds))->result_array();

	    $returnData = array();
	    foreach ($result as $row) {
	    	$returnData[$row['tag_id']] = $row['entity_id'];
	    }
	    return $returnData;
	}

	public function insertOnlineFormConfigIntoDb($data){
		if(empty($data)){
			return;
		}
		$dbHandle = $this->getWriteHandle();
		$dbHandle->insert_batch('OF_Internal_Institute_seo',$data);
	}

	public function fetchCoursesInBatch($start, $limit){
		if(!isset($start)) {
		    $start = 0;
		}
		if(!isset($limit)) {
		    return;
		}
		$dbHandle = $this->getReadHandle();
		$sql = "SELECT distinct course_id from shiksha_courses where status = 'live' LIMIT ?,?";
		$query = $dbHandle->query($sql, array((int)$start, (int)$limit))->result_array();

		return $this->getColumnArray($query,'course_id');
	}

	public function fetchInstitutesInBatch($start, $limit){
		if(!isset($start)) {
		    $start = 0;
		}
		if(!isset($limit)) {
		    return;
		}
		$dbHandle = $this->getReadHandle();
		$sql = "SELECT distinct listing_id,listing_type from shiksha_institutes where status='live' order by listing_id limit ?,?";
		$query = $dbHandle->query($sql, array((int)$start, (int)$limit))->result_array();

		return $query;
	}
} ?>

