<?php
class Search_agent_main_model extends MY_Model {

	function __construct()
	{
		parent::__construct('SearchAgents');
	}

	/**
	 * @param object $appId default 1
	 * @param object SearchAgentsConfig
	 * @return object
	 */

	function getDbHandle($operation = 'read')
	{
		if($operation=='read'){
			return $this->getReadHandle();
		}
		else{
        	return $this->getWriteHandle();
		}
	}


	function getActivatedSearchAgentDetails($clientId,$startFrom,$count,$deliveryMethod, $searchCriteria='created_on'){

		if(empty($clientId) || empty($deliveryMethod)){
			return array();
		}

		$dbHandle = $this->getDbHandle();

		$data = array();

		$startFrom = (int)$startFrom;
		$count = (int)$count;

		if($searchCriteria == 'leads'){
			$sql ="SELECT a.* FROM SASearchAgent a left join SALeadAllocation b on (a.searchagentid = b.agentid)
					WHERE a.clientid=? AND a.deliveryMethod= ? 
					AND a.is_active='live' 
					AND (a.flag_auto_download = 'live' OR a.flag_auto_responder_sms = 'live' OR a.flag_auto_responder_email = 'live') 
					group by a.searchagentid order by count(b.id) desc, a.created_on desc LIMIT ?, ?";
			$data['query'] = $dbHandle->query($sql, array($clientId, $deliveryMethod,$startFrom, $count));
		}else{
			$sql = "SELECT * FROM SASearchAgent WHERE clientid=?  AND deliveryMethod= ? AND is_active='live' AND (flag_auto_download = 'live' OR flag_auto_responder_sms = 'live' OR flag_auto_responder_email = 'live') order by $searchCriteria desc LIMIT ?,?";
			$data['query'] = $dbHandle->query($sql, array($clientId, $deliveryMethod,$startFrom, $count));			
		}

		$queryCmd = "SELECT count(*) as totalRows from SASearchAgent where clientid=? AND deliveryMethod= ?  AND is_active='live' AND (flag_auto_download = 'live' OR flag_auto_responder_sms = 'live' OR flag_auto_responder_email = 'live')";
		$queryCmd = $dbHandle->query($queryCmd, array($clientId, $deliveryMethod));
		foreach ($queryCmd->result() as $rowCmd) {
			$data['totalRows'] = $rowCmd->totalRows;
		}

		return $data;
	}

	function getDeactivatedSearchAgentDetails($clientId,$startFrom,$count,$deliveryMethod, $searchCriteria='created_on'){

		if(empty($clientId) || empty($deliveryMethod)){
			return array();
		}

		$dbHandle = $this->getDbHandle();
		$startFrom = (int)$startFrom;
		$count = (int)$count;

		$data = array();
		if($searchCriteria == 'leads'){
			$sql ="SELECT a.* FROM SASearchAgent a left join SALeadAllocation b on (a.searchagentid = b.agentid)
					WHERE a.clientid=? AND a.deliveryMethod= ? 
					AND a.is_active='live' 
					AND ((a.flag_auto_download = 'history' OR a.flag_auto_download IS NULL) AND (a.flag_auto_responder_sms = 'history' OR a.flag_auto_responder_sms IS NULL) AND (a.flag_auto_responder_email = 'history' OR a.flag_auto_responder_email IS NULL)) 
					group by a.searchagentid order by count(b.id) desc , a.created_on desc LIMIT ?,?";
			$data['query'] = $dbHandle->query($sql, array($clientId, $deliveryMethod,$startFrom, $count));
		}else{
			$sql = "SELECT * FROM SASearchAgent WHERE clientid=?  AND deliveryMethod= ? AND is_active='live' 
					AND ((flag_auto_download = 'history' OR flag_auto_download IS NULL) AND (flag_auto_responder_sms = 'history' OR flag_auto_responder_sms IS NULL) AND (flag_auto_responder_email = 'history' OR flag_auto_responder_email IS NULL)) 
					order by $searchCriteria desc LIMIT ?,?";
			$data['query'] = $dbHandle->query($sql, array($clientId, $deliveryMethod,$startFrom, $count));
		}

		$queryCmd = "SELECT count(*) AS totalRows FROM SASearchAgent WHERE clientid=? AND deliveryMethod= ?  AND is_active='live' AND ((flag_auto_download = 'history' OR flag_auto_download IS NULL) AND (flag_auto_responder_sms = 'history' OR flag_auto_responder_sms IS NULL) AND (flag_auto_responder_email = 'history' OR flag_auto_responder_email IS NULL))	";
		$queryCmd = $dbHandle->query($queryCmd, array($clientId, $deliveryMethod));
		foreach ($queryCmd->result() as $rowCmd) {
			$data['totalRows'] = $rowCmd->totalRows;
		}

		return $data;
	}

	function getDeletedSearchAgentDetails($clientId,$startFrom,$count,$deliveryMethod, $searchCriteria='created_on'){
		
		if(empty($clientId) || empty($deliveryMethod)){
			return array();
		}

		$dbHandle = $this->getDbHandle();
		$startFrom = (int)$startFrom;
		$count = (int)$count;

		$sql = "SELECT id, searchagentid, is_active FROM SASearchAgent WHERE clientid = ? AND deliveryMethod= ? order by is_active, id"; 
		$genieData = $dbHandle->query($sql, array($clientId, $deliveryMethod))->result_array();

		$deactivatedGenies = array();
		foreach($genieData as $row=>$val){
			if($val['is_active'] == 'history'){
				$deactivatedGenies[$val['searchagentid']] = $val['id'];	
			}else{
				unset($deactivatedGenies[$val['searchagentid']]);
			}
		}
		unset($genieData);
		$data['totalRows'] = count($deactivatedGenies); 

		if($data['totalRows'] > 0){
			if($searchCriteria == 'leads'){
				$sql ="SELECT a.* FROM SASearchAgent a left join SALeadAllocation b on (a.searchagentid = b.agentid)
						WHERE a.id IN (".implode(',', $deactivatedGenies).") 
						GROUP BY a.searchagentid order by count(b.id) desc, a.created_on desc LIMIT ?,?";

				$data['query'] = $dbHandle->query($sql, array($startFrom, $count));
			}else{
				$sql = "SELECT * FROM SASearchAgent WHERE id in (".implode(',', $deactivatedGenies).") order by $searchCriteria desc LIMIT ?,?";
				$data['query'] = $dbHandle->query($sql, array($startFrom, $count));
			}
		}

		return $data;
	}	

	function getPortingSeachAgentDetails($clientId,$startFrom,$count,$deliveryMethod, $searchCriteria){

		if(empty($clientId) || empty($deliveryMethod)){
			return array();
		}

		$result = array();
		$dbHandle = $this->getDbHandle();
		$startFrom = (int)$startFrom;
		$count = (int)$count;

		$query 	= "select * from SASearchAgent where clientid=? AND is_active='live' AND deliveryMethod= ?";
		$query .= " order by id desc LIMIT ?,?";
		$result['query'] = $dbHandle->query($query, array($clientId, $deliveryMethod, $startFrom, $count));

		$queryCmd = "SELECT count(*) as totalRows from SASearchAgent where clientid=? AND is_active='live' AND deliveryMethod= ?";
		$queryCmd = $dbHandle->query($queryCmd, array($clientId, $deliveryMethod));
		
		$totalRows = 0;
		foreach ($queryCmd->result() as $rowCmd) {
			$totalRows = $rowCmd->totalRows;
		}

		$result['totalRows'] =  $totalRows;

		return $result;
	}

	function SearchAgentsAllDetails($dbHandle,$clientId,$startFrom,$count,$deliveryMethod, $type='activated', $searchCriteria='created_on')
	{
		$dbHandle = $this->getDbHandle();

		if($deliveryMethod == 'porting'){
			$type = 'porting';
		}
		$result = array();

		switch ($type) {
			case 'activated':
				$result = $this->getActivatedSearchAgentDetails($clientId,$startFrom,$count,$deliveryMethod, $searchCriteria); 
				break;

			case 'deactivated':
				$result = $this->getDeactivatedSearchAgentDetails($clientId,$startFrom,$count,$deliveryMethod, $searchCriteria); 
				break;

			case 'deleted':
				$result = $this->getDeletedSearchAgentDetails($clientId,$startFrom,$count,$deliveryMethod, $searchCriteria); 
				break;

			case 'porting': 	
				$result = $this->getPortingSeachAgentDetails($clientId,$startFrom,$count,$deliveryMethod, $searchCriteria);
				break;
		}

		if(!empty($result['query'])){
			$query = $result['query'];

			foreach ($query->result_array() as $row)
			{
				$sa_id = $row['searchagentid'];
				$flag_ARE = $row['flag_auto_responder_email'];
				$flag_ARS = $row['flag_auto_responder_sms'];
				$result[$row['searchagentid']] =
				array(
					'sa_id'=>$row['searchagentid'],
					'name'=>$row['searchagentName'],
					'type'=>$row['type'],
					'is_active'=>$row['is_active'],
					'auto_download'=>array(
								'is_active'=>$row['flag_auto_download'],
								'detail'=>array(
									'leads_daily_limit'=>$row['leads_daily_limit'],
									'price_per_lead'=>$row['price_per_lead'],
									'pref_lead_type'=>$row['pref_lead_type'],
									'email_freq'=>$row['email_freq'],
									'sms_freq'=>$row['sms_freq']
								)
							),
					'groupid'=>array('groupid'=>$row['credit_group']),
					'created_on'=> $row['created_on'],
					'updated_on' => $row['updated_on']
				     );
				
				if( isset($sa_id) && !empty($sa_id))
				{
					$sql_SAmvsc = "select keyname,group_concat(value) as val from SAMultiValuedSearchCriteria where searchAlertId=? and (keyname = 'desiredcourse' or keyname = 'testprep') and is_active='live' group by keyname";
					$query_mv = $dbHandle->query($sql_SAmvsc, array($sa_id));
					
					foreach ($query_mv->result_array() as $row_traverse)
					{
						$result[$sa_id]['multivalued']=
							array(
								'value'=>$row_traverse['val'],
								'type' =>$row_traverse['keyname']
							);
					}
				}
				
				if( isset($result[$sa_id]['multivalued']['value']) && !empty($result[$sa_id]['multivalued']['value']))
				{
					if($result[$sa_id]['multivalued']['type'] == 'testprep') {
						$sql_mn = "SELECT groupId FROM `tCourseGrouping` where status = 'live' and extraFlag = 'testprep' and courseId in (".$result[$sa_id]['multivalued']['value'].")" ;
					}
					else {
						$sql_mn = "SELECT groupId FROM `tCourseGrouping` where status = 'live' and extraFlag = 'course' and courseId in (".$result[$sa_id]['multivalued']['value'].")" ;
					}
					
					error_log("DRG: ".$sql_mn);
					
					$query_mn = $dbHandle->query($sql_mn);
					
					foreach ($query_mn->result_array() as $row_traversal)
					{
						$result[$row['searchagentid']]['groupid']=
							array(
								'groupid'=>$row_traversal['groupId']
								
							);
					}
				}

				$sql_are = "SELECT * FROM SASearchAgentAutoResponder_email where searchagentid=? and is_active='live'";
				$query_are = $dbHandle->query($sql_are, array($sa_id));
				foreach ($query_are->result_array() as $row_are)
				{
					$result[$row['searchagentid']]['auto_responder']['email']=
						array(
							'is_active'=>$flag_ARE,
							'msg'=>$row_are['msg'],
							'daily_limit'=>$row_are['daily_limit'],
							'subject'=>$row_are['subject'],
							'from_emailid'=>$row_are['from_emailid'],
							'from_name'=>$row_are['from_name'],
							'table_id'=>$row_are['id']
						);
				}
				$sql_ars = "SELECT * FROM SASearchAgentAutoResponder_sms where searchagentid=? and is_active='live'";
				$query_ars = $dbHandle->query($sql_ars, array($sa_id));
				foreach ($query_ars->result_array() as $row_ars)
				{
					$result[$row['searchagentid']]['auto_responder']['sms']=
						array(
							'is_active'=>$flag_ARS,
							'msg'=>$row_ars['msg'],
							'daily_limit'=>$row_ars['daily_limit'],
							'subject'=>$row_ars['subject'],
							'table_id'=>$row_ars['id']
						);
				}
				$sql_cd = "select * from SASearchAgent_contactDetails where searchagentid=? and is_active='live'";
				$query_cd = $dbHandle->query($sql_cd, array($sa_id));
				foreach ($query_cd->result_array() as $row_cd)
				{
					if ($row_cd['contactType'] == 'email') {
						$result[$row['searchagentid']]['contact_details']['email'][] = $row_cd['contactValue'];
					}
					if ($row_cd['contactType'] == 'mobile') {
						$result[$row['searchagentid']]['contact_details']['mobile'][] = $row_cd['contactValue'];
					}
				}
			}
		}
		return $result;
	}


 	function SearchAgentDetail($dbHandle,$sa_id)
	{
		$dbHandle = $this->getDbHandle();
		
		$query 	= "SELECT * FROM SASearchAgent WHERE searchagentid =? ORDER BY updated_on DESC LIMIT 1";
		//$query 	= "select * from SASearchAgent where id=$sa_id";
		$query = $dbHandle->query($query, array($sa_id));
		foreach ($query->result_array() as $row)
		{
			$sa_id = $row['searchagentid'];
			$flag_ARE = $row['flag_auto_responder_email'];
			$flag_ARS = $row['flag_auto_responder_sms'];
			$result[$row['searchagentid']] =
			array(
				'sa_id'=>$row['searchagentid'],
				'name'=>$row['searchagentName'],
				'is_active'=>$row['is_active'],
				'credit_group'=>$row['credit_group'],
				'type'=>$row['type'],
				'auto_download'=>array(
							'is_active'=>$row['flag_auto_download'],
							'detail'=>array(
								'leads_daily_limit'=>$row['leads_daily_limit'],
								'price_per_lead'=>$row['price_per_lead'],
								'pref_lead_type'=>$row['pref_lead_type'],
								'email_freq'=>$row['email_freq'],
								'sms_freq'=>$row['sms_freq']
							)
						)
			     );

			$sql_are = "SELECT * FROM SASearchAgentAutoResponder_email where searchagentid=? and is_active='live'";
			$query_are = $dbHandle->query($sql_are, array($sa_id));
			foreach ($query_are->result_array() as $row_are)
			{
				$result[$row['searchagentid']]['auto_responder']['email']=
					array(
						'is_active'=>$flag_ARE,
						'msg'=>$row_are['msg'],
						'daily_limit'=>$row_are['daily_limit'],
						'subject'=>$row_are['subject'],
						'from_emailid'=>$row_are['from_emailid'],
						'from_name'=>$row_are['from_name'],
						'table_id'=>$row_are['id']
					);
			}
			$sql_ars = "SELECT * FROM SASearchAgentAutoResponder_sms where searchagentid=? and is_active='live'";
			$query_ars = $dbHandle->query($sql_ars, array($sa_id));
			foreach ($query_ars->result_array() as $row_ars)
			{
				$result[$row['searchagentid']]['auto_responder']['sms']=
					array(
						'is_active'=>$flag_ARS,
						'msg'=>$row_ars['msg'],
						'daily_limit'=>$row_ars['daily_limit'],
						'subject'=>$row_ars['subject'],
						'table_id'=>$row_ars['id']
					);
			}
			$sql_cd = "select * from SASearchAgent_contactDetails where searchagentid=? and is_active='live'";
			$query_cd = $dbHandle->query($sql_cd, array($sa_id));
			foreach ($query_cd->result_array() as $row_cd)
			{
				if ($row_cd['contactType'] == 'email') {
					$result[$row['searchagentid']]['contact_details']['email'][] = $row_cd['contactValue'];
				}
				if ($row_cd['contactType'] == 'mobile') {
					$result[$row['searchagentid']]['contact_details']['mobile'][] = $row_cd['contactValue'];
				}
			}
		}
		return $result;
	}
	
	public function getLeadConsumptionMaps()
	{
		$dbHandle = $this->getDbHandle('MISTracking');
		
		$sql =  "SELECT countryid,name ".
				"FROM countryTable ";
		
		$query = $dbHandle->query($sql);
		$countryMap = array();
		foreach($query->result_array() as $row) {
			$countryMap[$row['countryid']] = $row['name'];
		}
		
		$sql =  "SELECT city_id,city_name ".
				"FROM countryCityTable ".
				"WHERE countryId = 2";
		
		$query = $dbHandle->query($sql);
		$cityMap = array();
		foreach($query->result_array() as $row) {
			$cityMap[$row['city_id']] = $row['city_name'];
		}
		
		$sql =  "SELECT localityId,localityName ".
				"FROM localityCityMapping ";
		
		$query = $dbHandle->query($sql);
		$localityMap = array();
		foreach($query->result_array() as $row) {
			$localityMap[$row['localityId']] = $row['localityName'];
		}
		
		$sql =  "SELECT a.SpecializationId,a.CourseName,c.name ".
				"FROM tCourseSpecializationMapping a ".
				"LEFT JOIN LDBCoursesToSubcategoryMapping b ON (b.ldbCourseID = a.SpecializationId AND b.status = 'live') ".
				"LEFT JOIN categoryBoardTable c ON c.boardId = b.CategoryId ".
				"WHERE a.scope = 'india' AND a.status = 'live' AND a.SpecializationName = 'All'";
		
		$query = $dbHandle->query($sql);
		$nationalCourseMap = array();
		foreach($query->result_array() as $row) {
			$nationalCourseMap[$row['SpecializationId']] = array(
																	'course' => $row['CourseName'],
																	'category' => $row['name']
																);
		}
		
		$sql =  "SELECT a.SpecializationId,a.CourseName,a.CategoryId,b.name ".
				"FROM tCourseSpecializationMapping a ".
				"LEFT JOIN categoryBoardTable b ON b.boardId = a.CategoryId ".
				"WHERE a.scope = 'abroad' AND a.status = 'live' AND a.isEnabled = '1' ".
				"AND a.ParentId >= 1";
		
		$query = $dbHandle->query($sql);
		$abroadCourseMap = array();
		foreach($query->result_array() as $row) {
			$categoryName = $row['CategoryId'] == 1 ? '' : $row['name'];
			$abroadCourseMap[$row['SpecializationId']] = array(
																	'course' => $row['CourseName'],
																	'category' => $categoryName
																);
		}
		
		$sql =  "SELECT a.blogId,a.acronym,b.name ".
				"FROM blogTable a ".
				"LEFT JOIN categoryBoardTable b ON b.boardId = a.boardId ".
				"WHERE a.blogType = 'exam' AND a.status = 'live' AND a.acronym is not null AND a.acronym != ''";
		
		$query = $dbHandle->query($sql);
		$testprepCourseMap = array();
		foreach($query->result_array() as $row) {
			$testprepCourseMap[$row['blogId']] = array(
																	'course' => $row['acronym'],
																	'category' => $row['name']
																);
		}
		
		
		$maps = array(
			'country' => $countryMap,
			'city' => $cityMap,
			'locality' => $localityMap,
			'course' => array(
								'national' => $nationalCourseMap,
								'studyabroad' => $abroadCourseMap,
								'testprep' => $testprepCourseMap
							 )
		);
		
		return $maps;
	}
	
	public function getLeadsGenerated($dateFrom,$dateTo)
	{
		$dbHandle = $this->getDbHandle();
		
		$sql =  "SELECT a.UserId,a.DesiredCourse,a.ExtraFlag, b.city,b.Locality,c.blogid ".
				"FROM tUserPref a ".
				"INNER JOIN tuser b ON b.userid = a.UserID ".
				"LEFT JOIN tUserPref_testprep_mapping c ON c.prefid = a.PrefId ".
				"WHERE a.SubmitDate >= ? AND a.SubmitDate < ? ".
				"AND (a.DesiredCourse > 0 OR ExtraFlag = 'testprep') ".
				"ORDER BY a.DesiredCourse,b.city ";
	
		$sql =  "SELECT a.UserId,a.DesiredCourse,a.ExtraFlag, b.city,b.Locality,c.blogid,lp.countryId ".
                                "FROM tUserPref a ".
                                "INNER JOIN tuser b ON b.userid = a.UserID ".
                                "INNER JOIN tuserflag d ON d.userid = a.UserID ".
								"LEFT JOIN tUserLocationPref lp ON (lp.userid = a.UserID AND lp.countryId > 2) ".
                                "LEFT JOIN tUserPref_testprep_mapping c ON c.prefid = a.PrefId ".
                                "WHERE a.SubmitDate >= ? AND a.SubmitDate < ? ".
                                "AND (a.DesiredCourse > 0 OR ExtraFlag = 'testprep') ".
                                "AND d.isTestUser = 'NO' AND d.mobileverified = '1' AND d.isLDBUser = 'YES' ".
                                "ORDER BY a.DesiredCourse,b.city ";
								
		$query = $dbHandle->query($sql,array($dateFrom,$dateTo));
		return $query->result_array();
	}
	
	public function getAbroadLeadsGenerated($dateFrom,$dateTo)
	{
		$dbHandle = $this->getDbHandle('MISTracking');

                $sql =  "SELECT a.UserId,b.email, a.DesiredCourse, b.city,lp.countryId ".
                        "FROM tUserPref a ".
                        "INNER JOIN tuser b ON b.userid = a.UserID ".
                        "INNER JOIN tuserflag d ON d.userid = a.UserID ".
                        "INNER JOIN tUserLocationPref lp ON (lp.userid = a.UserID AND lp.countryId > 2) ".
                        "LEFT JOIN LDBExclusionList exl on a.UserId = exl.userid ".
                        "WHERE a.SubmitDate >= ? AND a.SubmitDate < ? ".
                        "AND (a.DesiredCourse > 0 AND ExtraFlag = 'studyabroad') ".
                        "AND d.isTestUser = 'NO' AND d.mobileverified = '1' AND d.isLDBUser = 'YES' ".
                        "AND d.hardbounce != '1' AND d.ownershipchallenged!='1' ".
                        "AND d.abused != '1' AND d.softbounce != '1' ".
                        "AND ((a.TimeOfStart <= now() + interval 2 year AND a.TimeOfStart != '0000-00-00 00:00:00') OR (a.TimeOfStart IS NULL)) ".
                        "AND exl.id is null ".
                        "AND b.usergroup not in ('sums', 'enterprise', 'cms', 'experts', 'lead_operator', 'saAdmin', 'saCMS', 'saContent', 'saSales') ".
                        "ORDER BY a.DesiredCourse,b.city,a.UserId ";
								
		$query = $dbHandle->query($sql,array($dateFrom,$dateTo));
		return $query->result_array();
	}
	
	/**
	 * Get leads consumed by genies
	 * Along with no. of different agents these were consumed by
	 */
	public function getLeadsConsumed($dateFrom,$dateTo)
	{
		$dbHandle = $this->getDbHandle();
		
		$sql =  "SELECT a.userid,count(*) as num ".
				"FROM SALeadAllocation a ".
				"WHERE a.allocationtime >= ? AND a.allocationtime < ? ".
				"AND a.auto_download = 'YES' ".
				"GROUP BY a.userid";
		
		$query = $dbHandle->query($sql,array($dateFrom,$dateTo));
		return $query->result_array();
	}
	
	/**
	 * Get leads view counts
	 */
	public function getLeadsViewCounts($dateFrom,$dateTo)
	{
		$dbHandle = $this->getDbHandle('MISTracking');
		
		$sql =  "SELECT a.UserId,a.ViewCount ".
				"FROM LDBLeadViewCount a ".
				"INNER JOIN tUserPref b ON b.UserId = a.UserId ".
				"WHERE b.SubmitDate >= ? AND b.SubmitDate < ? ";
		
		$query = $dbHandle->query($sql,array($dateFrom,$dateTo));
		$viewCounts = array();
		foreach($query->result_array() as $row) {
			$viewCounts[$row['UserId']] = $row['ViewCount'];
		}
		return $viewCounts;
	}
	
	/**
	 * Get leads consumed by porting
	 * Along with no. of different porting in which these were consumed
	 */ 
	public function getLeadsConsumedByPorting($dateFrom,$dateTo)
	{
		$dbHandle = $this->getDbHandle('MISTracking');
		
		$sql =  "SELECT a.leadid as userid, count(distinct clientid) as num ".
				"FROM  `SALeadMatchingLog` a ".
				"INNER JOIN porting_conditions b ON (b.key = 'searchagent' AND b.status = 'live' AND b.value = a.searchagentid) ".
				"INNER JOIN porting_main c ON (c.id = b.porting_master_id AND c.status =  'live') ".
				"WHERE a.matchingTime >= ? AND a.matchingTime < ? ".
				"GROUP BY a.leadid";
	        		
		$query = $dbHandle->query($sql,array($dateFrom,$dateTo));
	//	_p($query->result_array()); exit();
        $results = $query->result_array();
        $rows = array();
        foreach($results as $result) {
            $rows[$result['userid']] = $result['num'];
        }
		return $rows;
	}
	
	public function getActiveLeadGenies()
	{
		$SUMSDbHandle = $this->getReadHandleByModule('SUMS');
		
		/**
		 * Get LDB credits available with each client
		 * We'll remove genies whose clients don't have sufficient credits
		 */ 
		$sql = "SELECT S.ClientUserId, SPM.BaseProdRemainingQuantity
				FROM Subscription_Product_Mapping SPM
				INNER JOIN Subscription S ON S.SubscriptionId = SPM.SubscriptionID
				INNER JOIN Base_Products B ON SPM.BaseProductId = B.BaseProductId
				WHERE S.SubscrStatus =  'ACTIVE'
				AND SPM.BaseProdRemainingQuantity >0
				AND DATE( SPM.SubscriptionEndDate ) >= CURDATE( ) 
				AND DATE( SPM.SubscriptionStartDate ) <= CURDATE( ) 
				AND SPM.Status =  'ACTIVE'
				AND B.BaseProdCategory =  'Lead-Search'";
		
		$query = $SUMSDbHandle->query($sql);		
		$results = $query->result_array();
		
		$clientCreditMapping = array();
		foreach($results as $row) {
			$credits = intval($row['BaseProdRemainingQuantity']);
			if(!$clientCreditMapping[$row['ClientUserId']] || $credits > $clientCreditMapping[$row['ClientUserId']]) {
				$clientCreditMapping[$row['ClientUserId']] = $credits;
			}
		}
		
		$dbHandle = $this->getDbHandle();
		
		$sql =  "SELECT a.searchAlertId, a.keyname, a.value, b.clientid ".
				"FROM  SAMultiValuedSearchCriteria a, SASearchAgent b ".
				"WHERE a.searchAlertId = b.searchagentid ".
				"AND b.is_active =  'live' AND flag_auto_download = 'live' AND b.deliveryMethod = 'normal' ".
				"AND a.keyname IN ('desiredcourse','currentlocation','testprep','currentlocality')";
		
		$query = $dbHandle->query($sql);
		$results = $query->result_array();
		$leadGenies = array();
		
		foreach($results as $row) {
			if($clientCreditMapping[$row['clientid']] >= 50) {
				$leadGenies[$row['searchAlertId']][$row['keyname']][] = $row['value'];
			}
		}
		
		/**
		 * For abroad genies, get preferred countries
		 */
		$sql =  "SELECT searchAlertId, country ".
				"FROM SAPreferedLocationSearchCriteria a, SASearchAgent b ".
				"WHERE a.searchAlertId = b.searchagentid ".
				"AND b.is_active = 'live' AND flag_auto_download = 'live' AND b.deliveryMethod = 'normal'";
		
		$query = $dbHandle->query($sql);
		$results = $query->result_array();
		
		foreach($results as $row) {
			if($leadGenies[$row['searchAlertId']]) {
				$leadGenies[$row['searchAlertId']]['country'][] = $row['country'];
			}
		}
		
		return $leadGenies;
	}
	
	/**
	 * Get active genies set on active lead portings
	 */ 
	public function getActivePortingLeadGenies()
	{
		$dbHandle = $this->getDbHandle();
		
		$sql =  "SELECT a.searchAlertId, a.keyname, a.value, b.clientid ".
				"FROM  SAMultiValuedSearchCriteria a ".
				"INNER JOIN SASearchAgent b ON a.searchAlertId = b.searchagentid ".
				"INNER JOIN porting_conditions c ON (c.key = 'searchagent' AND c.status = 'live' AND c.value = b.searchagentid) ".
				"INNER JOIN porting_main d ON (d.id = c.porting_master_id AND d.status =  'live') ".
				"WHERE b.is_active =  'live' AND b.deliveryMethod = 'porting' ".
				"AND a.keyname IN ('desiredcourse','currentlocation','testprep','currentlocality')";
		
		$query = $dbHandle->query($sql);
		$results = $query->result_array();
		$leadGenies = array();
		
		foreach($results as $row) {
			$leadGenies[$row['searchAlertId']][$row['keyname']][] = $row['value'];
		}

		/**
		 * For abroad genies, get preferred countries
		 */
		$sql =  "SELECT searchAlertId, country ".
				"FROM SAPreferedLocationSearchCriteria a, SASearchAgent b ".
				"WHERE a.searchAlertId = b.searchagentid ".
				"AND b.is_active = 'live' AND flag_auto_download = 'live' AND b.deliveryMethod = 'porting'";
		
		$query = $dbHandle->query($sql);
		$results = $query->result_array();
		
		foreach($results as $row) {
			if($leadGenies[$row['searchAlertId']]) {
				$leadGenies[$row['searchAlertId']]['country'][] = $row['country'];
			}
		}

		return $leadGenies;
	}

	public function getActivatedGenies($clientId, $deliveryMethod){
		$dbHandle = $this->getDbHandle();

		if(empty($clientId) || empty($deliveryMethod)){
			return array();
		}

		$sql = "SELECT searchagentid , searchagentName, type FROM SASearchAgent 
				WHERE clientid = ? AND is_active='live' AND deliveryMethod = ? 
				AND (flag_auto_download = 'live' OR flag_auto_responder_sms = 'live' OR flag_auto_responder_email = 'live') 
				order by searchagentName";		

		$query = $dbHandle->query($sql, array($clientId, $deliveryMethod));
		
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$results[] = array(
					$row['searchagentid'],
					$row['searchagentName'],
					$row['type']
				);
			}
		}
		
		return $results;
	}

	public function getDeactivatedGenies($clientId, $deliveryMethod){
		if(empty($clientId) || empty($deliveryMethod)){
			return array();
		}

		$dbHandle = $this->getDbHandle();
		$sql = "SELECT searchagentid , searchagentName, type FROM SASearchAgent 
				WHERE clientid = ? AND is_active='live' 
				AND (flag_auto_download = 'history' OR flag_auto_download IS NULL) 
				AND (flag_auto_responder_sms = 'history' OR flag_auto_responder_sms IS NULL) 
				AND (flag_auto_responder_email = 'history' OR flag_auto_responder_email IS NULL) 
				AND deliveryMethod = ? order by searchagentName";

		$query = $dbHandle->query($sql, array($clientId, $deliveryMethod));
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$results[] = array(
					$row['searchagentid'],
					$row['searchagentName'],
					$row['type']
				);
			}
		}
		
		return $results;
	}

	public function getDeletedGenies($clientId, $deliveryMethod){
		
		if(empty($clientId) || empty($deliveryMethod)){
			return array();
		}

		$dbHandle = $this->getDbHandle();

		$sql = "SELECT distinct searchagentid , searchagentName, type FROM SASearchAgent 
				WHERE clientid = ? AND is_active='history' AND deliveryMethod = ? 
				order by searchagentName";

		$query = $dbHandle->query($sql, array($clientId, $deliveryMethod));
		
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$results[] = array(
					$row['searchagentid'],
					$row['searchagentName'],
					$row['type']
				);
			}
		}
		
		return $results;
	}

	function getAllSADisplayData($appId,$searchAgentIds){
		$dbHandle = $this->getDbHandle();
		
		if(empty($searchAgentIds)){
			return array();
		}
		
		$queryCmd = "SELECT * FROM SASearchAgentDisplayData where searchagentid IN (".implode(',',$searchAgentIds ).")";
		$query = $dbHandle->query($queryCmd)->result_array();
		$data = array();
		$result = array();
		foreach ($query as $key => $value) {
			$data[$value['searchagentid']] = $value;
		}
		foreach ($searchAgentIds as $key => $value) {
			$result[] = array($data[$value]);
		}
		return $result;
	}

	function getAllocatedLeadCountForGenie($searchAgentIds){
		$dbHandle = $this->getDbHandle();

		if(empty($searchAgentIds)){
			return array();
		}

		$sql = "SELECT count(distinct userId) as count, agentid FROM SALeadAllocation WHERE agentid IN (".implode(', ', $searchAgentIds).") GROUP BY agentid ";
		$query = $dbHandle->query($sql)->result_array();

		$result = array();
		foreach($query as $row=>$value){
			$result[$value['agentid']] = $value['count'];
		}
		return $result;
	}
	
	public function getAllDetailsofGenieMainTable($genies_ids = array()) {
		
		$dbHandle = $this->getDbHandle();
		$result = array();
		
		if(count($genies_ids) == 0) {
			return $result;				
		}	
		
		$sql = "SELECT * FROM SASearchAgent ".
		       "WHERE searchagentid in (".implode(",",$genies_ids).")";
		       
		$query = $dbHandle->query($sql);  
		$results = $query->result_array();     
		foreach($results as $row) {
			$result[$row['searchagentid']] = $row;//$row['deliveryMethod'];
		}
		
		return $result;
		
	}
	
	public function getPortingGenies($genies_ids = array()) {
		
		$new_genies = array();
		if(count($genies_ids) == 0) {
				return $new_genies;
		}
		
		$genies_details = $this->getAllDetailsofGenieMainTable($genies_ids); 
		
		foreach($genies_ids as $genie_id) {
				if($genies_details[$genie_id]['deliveryMethod'] == 'porting') {
						$new_genies[] = $genie_id;
				}
		}
		
		return $new_genies;
	}

	public function getSearchAgentDetails($searchAgentId){
		if(empty($searchAgentId)){
			return array();
		}

		$dbHandle = $this->getDbHandle();

		$SASearchAgentSQL = "select SA.searchagentid as SearchAgentId,SA.clientid as clientId,SA.deliveryMethod,SA.type as SearchAgentType, flag_auto_download as flagAutoDownload, flag_auto_responder_sms as flagAutoSMS, flag_auto_responder_email as flagAutoEmail from SASearchAgent SA where SA.searchagentid =? and SA.is_Active='live'";

		$SABasicData = $dbHandle->query($SASearchAgentSQL, array($searchAgentId))->result_array();

		return $SABasicData[0];
	}

	public function getSearchAgentCriteria($searchAgentId){
		if(empty($searchAgentId)){
			return array();
		}

		$dbHandle = $this->getDbHandle();		
		$SACriteriaSQl = "select id,keyname,value,parentId from SAMultiValuedSearchCriteria  where is_active = 'live' and searchAlertId = ?";
		$SACriteria = $dbHandle->query($SACriteriaSQl, array($searchAgentId))->result_array();

		return $SACriteria;
	}

	public function getAllLiveSearchAgent(){
		$dbHandle = $this->getDbHandle();		
		$sql = "select searchAgentId from SASearchAgent where is_Active = 'live'";
		$searchAgents = $dbHandle->query($sql)->result_array();

		return $searchAgents;	
	}

	public function getSearchAgentWorkEx($searchAgentId){
		$dbHandle = $this->getDbHandle();		
		$sql = "select rangeStart as minWorkEx, rangeEnd as maxWorkEx from SARangedSearchCriteria where is_Active = 'live' and searchAlertId =? and keyname ='exp'";

		$searchAgents = $dbHandle->query($sql,array($searchAgentId))->result_array();

		return $searchAgents;
	}

	public function getIncludeActiveUserFlag($searchAgentId){
		$dbHandle = $this->getDbHandle();		
		$sql = "select includeActiveUsers from SASearchAgentBooleanCriteria 
					WHERE searchagentid =?";

		$result = $dbHandle->query($sql,array($searchAgentId))->result_array();

		return $result[0]['includeActiveUsers'];
	}

	public function getExamCriteria($searchAgentId){
		$dbHandle = $this->getDbHandle();		
		$sql = "select examName, minScore, maxScore from SAExamCriteria 
					WHERE searchAlertId =?";

		$result = $dbHandle->query($sql,array($searchAgentId))->result_array();

		return $result;
	}

	public function getMaxIdFromIndexingQueue(){
		$this->dbHandle = $this->getDbHandle();	
		
		$sql = "SELECT MAX(id) as maxId FROM SearchAgentIndexingQueue WHERE status = 'queued'";
		$query = $this->dbHandle->query($sql);
		$row = $query->row_array();

		return $row['maxId'];
	}

	public function getSAQueuedForIndexing($maxIdInQueue){
		$this->dbHandle = $this->getDbHandle();
		
		$sql = "SELECT DISTINCT searchAgentId FROM SearchAgentIndexingQueue WHERE status = 'queued' AND id <= ? order by id desc";
		$query = $this->dbHandle->query($sql,array($maxIdInQueue));
		$results = $query->result_array();

		$users = array();
		foreach($results as $result) {
			$users[] = $result['searchAgentId'];
		}
		
		return $users;
	}

	public function getSAQueuedForIndexingByStartDate($startDate=""){
		if($startDate ==""){
			return;
		}
		$this->dbHandle = $this->getDbHandle();
		$sql = "SELECT DISTINCT searchAgentId FROM SearchAgentIndexingQueue WHERE  queueTime >= ? order by id desc";
		$query = $this->dbHandle->query($sql,array($startDate));
		$results = $query->result_array();
		$users = array();
		foreach($results as $result) {
			$users[] = $result['searchAgentId'];
		}
		
		return $users;
	}

	public function setSearchAgentIndexed($searchAgents,$maxIdInQueue){
		$this->dbHandle = $this->getDbHandle('write');
		
		$sql =  "UPDATE SearchAgentIndexingQueue ".
				"SET status = 'processed', processTime = '".date('Y-m-d H:i:s')."' ".
				"WHERE searchAgentId IN (".implode(',',$searchAgents).") AND status = 'queued' AND id <= ?";
				
		$query = $this->dbHandle->query($sql,array($maxIdInQueue));
	}

	public function addSearchAgentToQueue($searchagentid){
		$this->dbHandle = $this->getDbHandle('write');

		$sql = "insert into SearchAgentIndexingQueue (searchAgentId,status) values (?,'queued')";

		$query = $this->dbHandle->query($sql,array($searchagentid));
    }

    public function getLocationCriteria($searchagentid){
		$this->dbHandle = $this->getDbHandle();    	
		
		$sql = "SELECT country from SAPreferedLocationSearchCriteria where searchAlertId =? and location_type='preferred'";
		$query = $this->dbHandle->query($sql,array($searchagentid));
		$results = $query->result_array();

		return $results;
    }

    public function getDeltaSearchAgentsForIndexing($lastSAIndexedTime){
    	
    	$this->dbHandle = $this->getDbHandle();
    	$sql = "SELECT DISTINCT searchagentid FROM SASearchAgent where is_Active = 'live' AND updated_on > ? ";
    	$query = $this->dbHandle->query($sql, array($lastSAIndexedTime));
    	$results = $query->result_array();

		$searchAgents = array();
		foreach($results as $result) {
			$searchAgents[] = $result['searchagentid'];
		}
		
		return $searchAgents;
    }

    public function fetchGenieWithQuotaReached(){
    	$this->dbHandle = $this->getDbHandle();
    	$sql = "select searchagentid from SAGenieQuotaReached  where status='live'";

    	$query = $this->dbHandle->query($sql);
    	$results = $query->result_array();

		$searchAgents = array();
		foreach($results as $result) {
			$searchAgents[] = $result['searchagentid'];
		}
		
		return $searchAgents;
    }

    public function getGenieWithFullQuota(){
    	$this->dbHandle = $this->getDbHandle();
    	$sql ="select b.searchagentid from `SALeadsLeftoverStatus` a join  SASearchAgent b on a.searchagentid = b.searchagentid and b.is_active = 'live'	where ((a.leftover_leads + (b.leads_daily_limit- a.leads_sent_today)) <1 )";


    	$query = $this->dbHandle->query($sql);
    	$results = $query->result_array();

		$searchAgents = array();
		foreach($results as $result) {
			$searchAgents[] = $result['searchagentid'];
		}
		
		return $searchAgents;
    }

    public function markGenieWithFullQuota($genieId){
    	if($genieId<1){
    		return;
    	}

    	$this->dbHandle = $this->getDbHandle('write');

    	$sql = "insert ignore into SAGenieQuotaReached (searchagentid,status) VALUES ($genieId,'live')";
    	$query = $this->dbHandle->query($sql);

    }

    public function markAllGenieQuotaHistory(){
    	$this->dbHandle = $this->getDbHandle('write');

    	$sql = "update SAGenieQuotaReached set status = 'history' where status='live'";
    	$query = $this->dbHandle->query($sql);
    }

    public function emptyGenieLeftOverStatus($sa_id){
    	$this->dbHandle = $this->getDbHandle('write');

    	$sql = "update SALeadsLeftoverStatus set leftover_leads = 0 where searchagentid=?";
    	$query = $this->dbHandle->query($sql,array($sa_id));
    	return;
    }

    public function addDailyPortingData($sa_id,$daily_limit)
    {
    	$this->dbHandle = $this->getDbHandle('write');

    	$sql = "update SASearchAgent set leads_daily_limit = ? where searchagentid = ?";
    	return $this->dbHandle->query($sql,array($daily_limit,$sa_id));
    }

    public function getNewSearchAgents(){
		$this->dbHandle = $this->getDbHandle();
		
		$creation_time = date('Y-m-d H:i:00',strtotime("-1 hour"));
    	$sql = "select distinct searchagentid from SASearchAgent where created_on>? and type='response' and is_active='live'";
    	$result = $this->dbHandle->query($sql,array($creation_time))->result_array();    	
    	return $result;
    }

    public function getClientMRCourses($search_agent_id){
    	if($search_agent_id<1){
    		return array();
    	}

    	$this->dbHandle = $this->getDbHandle();

    	$sql = "select group_concat(value) as course_ids from SAMultiValuedSearchCriteria where searchAlertId = ? and keyname ='clientcourse'";
    	$result = $this->dbHandle->query($sql,array($search_agent_id))->result_array();    	
    	return $result[0];
    }

    public function getCollabAlsoViewedCourses($course_ids){
    	if(count($course_ids)<=0){
                return ;
        }

    	$this->dbHandle = $this->getDbHandle();
    	$sql ="select course_id, recommended_course_id, recommended_institute_id,weight, last_update from collaborativeFilteredCourses where course_id in(?) and weight>100000";

    	$result = $this->dbHandle->query($sql,array($course_ids))->result_array();   
    	return $result;
    }

    public function storeClientMRCourses($collab_also_viewed_courses){
    	if(count($collab_also_viewed_courses)<=0){
    		return ;
    	}

    	$this->dbHandle = $this->getDbHandle('write');

    	$insert_sql = "insert ignore into SASearchAgent_MRCourses (course_id,recommended_course_id, recommended_institute_id,weight, last_update) VALUES ";
		foreach ($collab_also_viewed_courses as $sub_row) {
			$insert_sql .= "(".$sub_row['course_id'].",".$sub_row['recommended_course_id'].",".$sub_row['recommended_institute_id'].",".$sub_row['weight'].",'".$sub_row['last_update']."'),";
		}

		$insert_sql = substr($insert_sql, 0,-1);
		$insert_sql .= " ON DUPLICATE KEY UPDATE `weight` = VALUES(`weight`), `last_update` = VALUES(`last_update`)";

		$this->dbHandle->query($insert_sql);

    }

    public function getAllMRSearchAgent(){
    	$this->dbHandle = $this->getDbHandle();
    	$sql = "select value as courseid, searchalertid as searchagentid from SAMultiValuedSearchCriteria join listings_main lm on lm.listing_type_id=value  where keyname = 'clientcourse' and lm.status='live' and listing_type='course'";

    	$result = $this->dbHandle->query($sql)->result_array();   
    	return $result;
    }

    public function getRecentMRAlsoViewed($course_id, $last_update_time){
    	if($course_id=='' || $last_update_time==''){
    		return;
    	}

    	$this->dbHandle = $this->getDbHandle();
    	$sql="select course_id, recommended_course_id, recommended_institute_id,weight, last_update from collaborativeFilteredCourses where course_id=? and weight>100000 and last_update>=?";
		$result = $this->dbHandle->query($sql,array($course_id,$last_update_time))->result_array();
		return $result;
    }


    public function getMRDeletedCourses($start_date, $end_date){
    	if($start_date=='' || $end_date==''){
    		return;
    	}

    	$this->dbHandle = $this->getDbHandle();
    	$sql="select distinct course_id, searchalertid as searchagentid from shiksha_courses sc join SAMultiValuedSearchCriteria on course_id =value   where sc.status='deleted' and  keyname = 'clientcourse' and sc.updated_on>=? and sc.updated_on<=? ";
		$result = $this->dbHandle->query($sql,array($start_date,$end_date))->result_array();

		return $result;	
    }

    public function getMRDeletedGenies($start_date, $end_date){
    	if($start_date=='' || $end_date==''){
    		return;
    	}

    	$this->dbHandle = $this->getDbHandle();
    	$sql="select distinct value as course_id, searchagentid from SASearchAgent sa join SAMultiValuedSearchCriteria on sa.searchagentid = searchalertid  where sa.is_active in ('deleted','history') and keyname = 'clientcourse' and sa.updated_on>=? and sa.updated_on<=? ";
		$result = $this->dbHandle->query($sql,array($start_date,$end_date))->result_array();

		return $result;	
    }

	public function getLeftOverLeadStatus($searchAgentIds){
		if(count($searchAgentIds)<=0){
                return ;
        }

		$this->dbHandle = $this->getDbHandle();
    	$sql="select searchagentid,leftover_leads as leftover from SALeadsLeftoverStatus where searchagentid in (?)";
		$result = $this->dbHandle->query($sql,array($searchAgentIds))->result_array();

		return $result;	
	}    

	public function resetLeftOverStatus($searchAgentId){
		if($searchAgentId<=0){
                return ;
        }

		$this->dbHandle = $this->getDbHandle('write');
    	$sql="update SALeadsLeftoverStatus set leftover_leads = 0 where searchagentid =?";
		$this->dbHandle->query($sql,array($searchAgentId));
	}

	public function getActiveUserFlagForSA($searchAgentIds){
		if(empty($searchAgentIds)){
                return ;
        }
		$this->dbHandle = $this->getDbHandle();
    	$sql="select includeActiveUsers,searchagentid from SASearchAgentBooleanCriteria 
					WHERE searchagentid in (?)";
		return $this->dbHandle->query($sql,array($searchAgentIds))->result_array();

	}

}
?>
