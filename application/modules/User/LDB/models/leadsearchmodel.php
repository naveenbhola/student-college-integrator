<?php

class LeadSearchModel extends MY_Model
{
	function __construct()
    {
		parent::__construct('LDB');
	}

	function getDbHandle($operation='read')
    {
		if($operation=='read'){
			return $this->getReadHandle();
		}
		else{
        	return $this->getWriteHandle();
		}
	}
    
    public function getDesiredCoursesByName($desiredCourseNames,$categoryId)
    {
        $dbHandle = $this->getDbHandle();
        
        $desiredCoursesJoined = "('".implode("','",$dbHandle->escape_str($desiredCourseNames))."')";
			
        $sql =  "SELECT SpecializationId ".
                "FROM tCourseSpecializationMapping ".
                "WHERE CourseName IN $desiredCoursesJoined ".
                "AND SpecializationName = 'All' ".
                "AND CategoryId = ? ".
                "AND Status = 'live' ".
                "AND scope = 'india'";
                
        $query = $dbHandle->query($sql,array($categoryId));
        $rows = $query->result_array();
        
        $desiredCourseIds = array();
        foreach($rows as $row) {
            $desiredCourseIds[] = intval($row['SpecializationId']);
        }
        
        return $desiredCourseIds;
    }
    
    public function getLeadsOverViewLimit($viewLimit)
    {
        $dbHandle = $this->getDbHandle();
        
        $sql =  "SELECT UserId ".
                "FROM LDBLeadViewCount ".
                "WHERE ViewCount >= ?";
        $query = $dbHandle->query($sql,array(intval($viewLimit)));
        
        $leadsOverViewLimit = array();
        while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM)) {
            $leadsOverViewLimit[$result[0]] = TRUE;
        }
        
        return $leadsOverViewLimit;
    }
    
    public function getContactedLeads($searchCriteria)
    {
        $dbHandle = $this->getDbHandle();
        
        $contactedLeads = array();
        
        $clientUserId = intval($searchCriteria['clientUserId']);
        
        $contactedLeadsQuery = array();
		if($searchCriteria['DontShowContacted']) {
			$contactedLeadsQuery[] = "select UserId from LDBLeadContactedTracking where ClientId = ?";
		}
		if($searchCriteria['DontShowViewed']) {
			$contactedLeadsQuery[] = "select UserId from LDBLeadContactedTracking where ClientId = ? and ContactType = 'view'";
		}
		if($searchCriteria['DontShowEmailed']) {
			$contactedLeadsQuery[] = "select UserId from LDBLeadContactedTracking where ClientId = ? and ContactType = 'email'";
		}
		if($searchCriteria['DontShowSmsed']) {
			$contactedLeadsQuery[] = "select UserId from LDBLeadContactedTracking where ClientId = ? and ContactType = 'sms'";
		}
		
		if(count($contactedLeadsQuery) > 0) {
			$contactedLeadsQuery = implode(' UNION ',$contactedLeadsQuery);
			$query = $dbHandle->query($contactedLeadsQuery, array($clientUserId));
			
			$contactedLeads = array();
			while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM)) {
				$contactedLeads[$result[0]] = TRUE;
			}
		}
        
        return $contactedLeads;
    }
    
    public function getContactedLeadsCount($searchCriteria)
    {	
    	
        $dbHandle = $this->getDbHandle();
        
        $contactedLeads = array();
        
        $clientUserId = intval($searchCriteria['clientUserId']);
        
		$startDate = $searchCriteria['DateFilterFrom'];
		$endDate = $searchCriteria['DateFilterTo'];


		$contactedLeadsQuery = "select UserId from UserProfileMappingToClient where ClientId = ? and ContactType = 'view' ";
		
		$query = $dbHandle->query($contactedLeadsQuery,array($clientUserId))->result_array();
		
		
        
        return $query;
    }

    public function getLeadsByContactType($searchCriteria)
    {
        $dbHandle = $this->getDbHandle();
        
        $contactType = 'view';
        if($searchCriteria['Emailed']) {
            $contactType = 'email';
        }
        else if($searchCriteria['Smsed']) {
            $contactType = 'sms';
        }
        
        $clientUserId = intval($searchCriteria['clientUserId']);
        
        $sql =  "SELECT UserId ".
                "FROM LDBLeadContactedTracking ".
                "WHERE ClientId = ? ".
                "AND ContactType = ?";
                
        $query = $dbHandle->query($sql,array($clientUserId,$contactType));
    
        $leads = array();
        while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM)) {
            $leads[$result[0]] = TRUE;
        }
        
        return $leads;
    }
	
	public function getLeadViewCountArray($userIds)
	{
		$dbHandle = $this->getDbHandle();
		
		//$sql = "SELECT * from LDBLeadViewCount where UserId in (".implode(',',$userIds).")";
		$sql = "SELECT * from LDBLeadViewCount where UserId in (?)";
		$query = $dbHandle->query($sql,array($userIds));
			
		$LDBLeadViewCountArray = array();
		foreach ($query->result_array() as $row) {
			if($row['DesiredCourse'] != null){
		    	$LDBLeadViewCountArray[$row["UserId"]][$row['DesiredCourse']] = $row;
			} 
			if($row['StreamId'] != null){
				if($row['SubStreamId']){
					$LDBLeadViewCountArray[$row["UserId"]][$row['StreamId']][$row['SubStreamId']] = $row;	
				} else{
		    		$LDBLeadViewCountArray[$row["UserId"]][$row['StreamId']][0] = $row;
				}
			}
		}
			
		return $LDBLeadViewCountArray;
	}
	
	public function getLeadContactedData($userIds,$clientId)
	{
		$dbHandle = $this->getDbHandle();
	
		$sql =  "SELECT *,DATE_FORMAT(ContactDate,'%D %b %Y') as FormattedContactDate ".
				"FROM LDBLeadContactedTracking ".
				"WHERE ClientId = ? ".
				"AND UserId in (?) ".
				"ORDER BY userid,contactdate DESC";
				
		$query = $dbHandle->query($sql, array($clientId,$userIds));

		$LDBContactedData = array();
		foreach ($query->result_array() as $row) {
		    $LDBContactedData[$row["UserId"]]['ContactType'][$row["ContactType"]][] = $row['FormattedContactDate'];
		    $LDBContactedData[$row["UserId"]]['CreditConsumed'][$row["ContactType"]][] = $row['CreditConsumed'];
		}
		
		return $LDBContactedData;
	}
	
	public function getResponseLocations($userIds)
	{
		if(count($userIds) <1){
			return false;
		}

		$dbHandle = $this->getDbHandle();
	
		$sql =  "SELECT a.userId, b.city_name ".
				"FROM userResponseLocationAffinity a ".
				"INNER JOIN countryCityTable b ON b.city_id = a.cityId ".
				"WHERE a.userId in (".implode(',',$userIds).") ".
				"AND b.countryId = 2";
				
		$query = $dbHandle->query($sql);
			
		$responseLocations = array();
		foreach ($query->result_array() as $row) {
			$responseLocations[$row["userId"]][] = $row['city_name'];	
		}
		
		return $responseLocations;
	}
	
	public function getResponseLocationsWithAffinity($userIds)
	{
		$dbHandle = $this->getDbHandle();
	
		$sql =  "SELECT a.userId, a.cityId, a.affinity ".
				"FROM userResponseLocationAffinity a ".
				"INNER JOIN countryCityTable b ON b.city_id = a.cityId ".
				"WHERE a.userId in (?) ".
				"AND b.countryId = 2";
				
		$query = $dbHandle->query($sql,array($userIds));
			
		$responseLocations = array();
		foreach ($query->result_array() as $row) {
			$responseLocations[$row["userId"]][$row['cityId']] = $row['affinity'];	
		}
		
		return $responseLocations;
	}
}
