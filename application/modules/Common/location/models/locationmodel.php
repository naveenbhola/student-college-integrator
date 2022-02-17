<?php

class LocationModel extends MY_Model
{
    private $dbHandle = '';
    function __construct()
    {
        parent::__construct('location');
    }
	
	public function getCity($cityId)
	{
		Contract::mustBeNumericValueGreaterThanZero($cityId,'City ID');

                $this->dbHandle = $this->getReadHandle();
		

		$sql = "SELECT cct.*,
					   vcm.virtualCityId
					   FROM countryCityTable cct
					   LEFT JOIN virtualCityMapping  vcm
					   ON cct.city_id = vcm.city_id AND vcm.city_id <> vcm.virtualCityId
						where cct.city_id = ?";

			/*$sql =  "SELECT * ".
				"FROM countryCityTable ".
				"WHERE city_id = ?";*/
                return $this->dbHandle->query($sql, $cityId)->row_array();
	}

    public function getMultipleCity($cityArr)
	{
		    // fail safe check
		    if(count($cityArr) == 0) {
				return array();
			}
			
            $this->dbHandle = $this->getReadHandle();
            
            $sql =  "SELECT cct.*,
					   vcm.virtualCityId
					   FROM countryCityTable cct
					   LEFT JOIN virtualCityMapping  vcm
					   ON cct.city_id = vcm.city_id AND vcm.city_id <> vcm.virtualCityId
						where cct.city_id IN (?)";
            $ret = $this->dbHandle->query($sql,array($cityArr))->result_array();
            $result = array();
            foreach($cityArr as $cityid)$result[$cityid] = null;
            foreach($ret as $row){
                $result[$row["city_id"]] = $row;
            }
            return $result;
	}
        
	public function getCitiesByMultipleTiers($tiers, $countryId) {
		Contract::mustBeNonEmptyArrayOfIntegerValues($tiers,'Tiers');

        $this->dbHandle = $this->getReadHandle();
        $param = array($tiers);
		if($countryId) {
			$param[] = $countryId;
		}
		$sql =  "SELECT * ".
				"FROM countryCityTable ".
				"WHERE tier IN (?) ".($countryId?" AND countryId = ? ":"").
				"ORDER BY city_name";
		
		$results = $this->dbHandle->query($sql, $param)->result_array();

		$cities = array();
		foreach($results as $result) {
			$cities[$result['tier']][] = $result;
		}
	
		return $cities;
	}
	
	public function getCitiesHavingZones()
	{
        $this->dbHandle = $this->getReadHandle();
                
		$sql =  "SELECT distinct c.city_id, c.city_name, c.countryId, c.enabled, c.state_id, c.tier  FROM `localityCityMapping` l, countryCityTable c where l.cityId = c.city_id group by l.zoneId order by c.city_id";
		
		return $this->dbHandle->query($sql)->result_array();
	}

	public function getCitiesForVirtualCity($virtualCityId)
	{
		Contract::mustBeNumericValueGreaterThanZero($virtualCityId,'Virtual City ID');

                $this->dbHandle = $this->getReadHandle();
		
		$sql =  "SELECT city_id ".
				"FROM virtualCityMapping ".
				"WHERE virtualCityId = ?";
					
		return $this->getColumnArray($this->dbHandle->query($sql, $virtualCityId)->result_array(),'city_id');
	}
	
	public function getCountry($countryId)
	{
		Contract::mustBeNumericValueGreaterThanZero($countryId,'Country ID');

                $this->dbHandle = $this->getReadHandle();
		
		$sql =  "SELECT c.*, c.tier AS countryTier, r.regionid ".
				"FROM countryTable c ".
				"LEFT JOIN tregionmapping r ON r.id = c.countryId ".
				"WHERE c.countryId = ? ";
				
		return $this->dbHandle->query($sql, $countryId)->row_array();
	}
	
	public function getCountries()
	{
                $this->dbHandle = $this->getReadHandle();
                
		$sql =  "SELECT c.*, c.tier AS countryTier, r.regionid ".
				"FROM countryTable c ".
				"LEFT JOIN tregionmapping r ON r.id = c.countryId and showOnRegistration = 'YES'".
				"WHERE c.countryId > 1 ";
			
		return $this->dbHandle->query($sql)->result_array();
	}
	/*
	 * gets all countries if nothing is passed, specific country rows if country Ids are passed
	 */
	public function getAllCountries($countryIds = array())
	{
		if(is_numeric($countryIds) && $countryIds > 0)
		{
			$countryIds = array($countryIds);
		}
        $this->dbHandle = $this->getReadHandle();
        $this->dbHandle->select('c.*, c.tier AS countryTier, r.regionid', false);
        $this->dbHandle->from('countryTable c');
        $this->dbHandle->join('tregionmapping r','r.id = c.countryId','left');
        $this->dbHandle->where('countryId>1','', false);
		if(count($countryIds) > 0)
		{
			$this->dbHandle->where_in ('countryId',$countryIds);
		}
		return $this->dbHandle->get()->result_array();
	}
	public function getAllCountryIds()
	{
		$this->dbHandle = $this->getReadHandle();
        $this->dbHandle->select('countryId');
        $this->dbHandle->from('countryTable');
        $this->dbHandle->where('countryId>1','', false);
		$result = $this->dbHandle->get()->result_array();
		return array_map(function($a){return $a['countryId']; },$result);
	}
	
	public function getAbroadCountries()
	{
        $this->dbHandle = $this->getReadHandle();
        $sql =  "SELECT c.*, c.tier AS countryTier, r.regionid ".
				"FROM ".ENT_SA_COUNTRY_TABLE_NAME." c ".
				"LEFT JOIN ".ENT_SA_REGION_MAPPING_TABLE_NAME." r ON r.id = c.countryId ".
				"WHERE c.showOnRegistration = 'YES' and c.countryId NOT IN (2) order by c.countryId";
			return $this->dbHandle->query($sql)->result_array();
	}
	
	public function getAbroadCountryById($countryId = NULL)
	{
		if(empty($countryId)){
			return array();
		}
        $this->dbHandle = $this->getReadHandle();
        $sql =  "SELECT c.*, c.tier AS countryTier, r.regionid ".
				"FROM ".ENT_SA_COUNTRY_TABLE_NAME." c ".
				"LEFT JOIN ".ENT_SA_REGION_MAPPING_TABLE_NAME." r ON r.id = c.countryId ".
				"WHERE c.countryId = ?";
		return $this->dbHandle->query($sql, $countryId)->result_array();
	}
	
	public function getCountriesByRegion($regionId)
	{
		Contract::mustBeNumericValueGreaterThanZero($regionId,'Region ID');

                $this->dbHandle = $this->getReadHandle();
		
		$sql =  "SELECT ct.*,tr.regionid ".
				"FROM tregionmapping tr ".
				"INNER JOIN countryTable ct ON ct.countryId = tr.id ".
				"WHERE tr.regionid = ? ".
				"AND tr.regionmapping = 'country' ".
				"ORDER BY ct.name";
					
		return $this->dbHandle->query($sql, $regionId)->result_array();
	}
	
	public function getCountryByURLName($urlName)
	{
                $this->dbHandle = $this->getReadHandle();
                
		$sql =  "SELECT c.*,r.regionid ".
				"FROM  countryTable c ".
				"LEFT JOIN tregionmapping r ON r.id = c.countryId ".
				"WHERE REPLACE(c.name,' ','') = ? ";
				
		return $this->dbHandle->query($sql, $urlName)->row_array();
	}
	
	public function getState($stateId)
	{
		Contract::mustBeNumericValueGreaterThanZero($stateId,'State ID');

                $this->dbHandle = $this->getReadHandle();
		
		$sql =  "SELECT * ".
				"FROM stateTable ".
				"WHERE state_id = ? ".
				"AND enabled = 0";
				
		return $this->dbHandle->query($sql, $stateId)->row_array();
	}

	public function getAllAbroadState()
	{

        $this->dbHandle = $this->getReadHandle();
		
		$sql =  "SELECT * ".
				"FROM stateTable ".
				"WHERE enabled = 0 ".
				"AND countryId != 2";
				
		return $this->dbHandle->query($sql)->result_array();
	}
	
	public function getMultipleStates($stateIds = array())
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($stateIds,'State ID');
		$this->dbHandle = $this->getReadHandle();
		
		$sql =  "SELECT * ".
				"FROM stateTable ".
				"WHERE state_id IN (?) ".
				"AND enabled = 0";
		
		return $this->dbHandle->query($sql,array($stateIds))->result_array();
	}
	
	
	public function getStatesByCountry($countryId)
	{
                $this->dbHandle = $this->getReadHandle();
                
		$sql =  "SELECT * ".
				"FROM stateTable ".
				"WHERE countryId = ? ".
				"AND enabled = 0 ".
				"ORDER BY state_name";
				
		return $this->dbHandle->query($sql, $countryId)->result_array();
	}
	
	public function getZone($zoneId)
	{
		Contract::mustBeNumericValueGreaterThanZero($zoneId,'Zone ID');

                $this->dbHandle = $this->getReadHandle();
		
		$sql =  "SELECT DISTINCT z.*,l.cityId,c.state_id,c.countryId ".
				"FROM tZoneMapping z ".
				"LEFT JOIN localityCityMapping l ON l.zoneId = z.zoneId ".
				"LEFT JOIN countryCityTable c ON c.city_id = l.cityId ".
				"WHERE z.zoneId = ? ".
				"LIMIT 1";
		
		return $this->dbHandle->query($sql, $zoneId)->row_array();
	}
	
	public function getMultipleZones($zoneIds)
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($zoneIds,'Zone ID');

        $this->dbHandle = $this->getReadHandle();
		
		$sql =  "SELECT DISTINCT z.*,l.cityId,c.state_id,c.countryId ".
				"FROM tZoneMapping z ".
				"LEFT JOIN localityCityMapping l ON l.zoneId = z.zoneId ".
				"LEFT JOIN countryCityTable c ON c.city_id = l.cityId ".
				"WHERE z.zoneId IN (?) ";
		
		return $this->dbHandle->query($sql,array($zoneIds))->result_array();
	}
	
	public function getLocality($localityId)
	{
		Contract::mustBeNumericValueGreaterThanZero($localityId,'Locality ID');
		
		$this->dbHandle = $this->getReadHandle();
		
		$sql =  "SELECT l.*,c.state_id as stateId,c.countryId ".
				"FROM  localityCityMapping l ".
				"LEFT JOIN countryCityTable c ON c.city_id = l.cityId ".
				"WHERE localityId = ? AND status = 'live' ";
				
		return $this->dbHandle->query($sql, $localityId)->row_array();
	}
	
	public function getMultipleLocalities($localityIds = array())
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($localityIds,'Locality ID');
		
		$this->dbHandle = $this->getReadHandle();
		
		$sql =  "SELECT l.*,c.state_id as stateId,c.countryId ".
				"FROM  localityCityMapping l ".
				"LEFT JOIN countryCityTable c ON c.city_id = l.cityId ".
				"WHERE localityId IN (?) AND status = 'live' ";
		
		return $this->dbHandle->query($sql,array($localityIds))->result_array();
	}
	
	public function getLocalities($zoneId = 0, $cityId = 0)
	{
        $this->dbHandle = $this->getReadHandle();
        $param = array();
        if($zoneId == 0 && $cityId == 0) {
            $sql =  "SELECT l.*,c.state_id,c.countryId ".
			"FROM  localityCityMapping l ".
			"LEFT JOIN countryCityTable c ON c.city_id = l.cityId ".
			"WHERE l.status = 'live' ORDER BY cityId";
        } else if($cityId == 0) {
            $sql =  "SELECT l.*,c.state_id,c.countryId ".
			"FROM  localityCityMapping l ".
			"LEFT JOIN countryCityTable c ON c.city_id = l.cityId ".
			"WHERE l.zoneId = ? AND l.status = 'live' ORDER BY cityId";
			$param = array($zoneId);
		} else if($zoneId == 0) {
		    $sql =  "SELECT l.*,c.state_id,c.countryId ".
				"FROM  localityCityMapping l ".
				"LEFT JOIN countryCityTable c ON c.city_id = l.cityId ".
				"WHERE l.status = 'live' AND l.cityId = ?";
			$param = array($cityId);
		}
		
		return $this->dbHandle->query($sql, $param)->result_array();
	}


        public function getZones($cityId=0)
	{
                $this->dbHandle = $this->getReadHandle();
                $param = array();
		if($cityId == 0) {
                    $sql =  "SELECT DISTINCT tzm.zoneId, tzm.zoneName, lcm.cityId, cct.state_id, cct.countryId".
                                    " FROM tZoneMapping tzm, localityCityMapping lcm, countryCityTable cct ".
                                    " WHERE lcm.status = 'live' ".
                                    " AND cct.city_id = lcm.cityId ".
                                    " AND tzm.zoneId = lcm.zoneId ".
                                    " ORDER BY lcm.cityId";
               } else {
                    $sql =  "SELECT DISTINCT tzm.zoneId, tzm.zoneName, lcm.cityId, cct.state_id, cct.countryId".
                                    " FROM tZoneMapping tzm, localityCityMapping lcm, countryCityTable cct ".
                                    " WHERE lcm.status = 'live' ".
                                    " AND cct.city_id = ? ".
                                    " AND cct.city_id = lcm.cityId ".
                                    " AND tzm.zoneId = lcm.zoneId ".
                                    " ORDER BY lcm.cityId";
                    $param[] = $cityId;
                }

		return $this->dbHandle->query($sql, $param)->result_array();
	}

	public function getRegion($regionId)
	{
                $this->dbHandle = $this->getReadHandle();

		Contract::mustBeNumericValueGreaterThanZero($regionId,'Region ID');
		
		$sql =  "SELECT * ".
				"FROM  tregion ".
				"WHERE regionid = ? ";
				
		return $this->dbHandle->query($sql, $regionId)->row_array();
	}
	
	public function getRegions()
	{
                $this->dbHandle = $this->getReadHandle();
                
		$sql =  "SELECT * ".
				"FROM  tregion ".
				"ORDER BY regionid";
				
		return $this->dbHandle->query($sql)->result_array();
	}
	
	public function getRegionByURLName($urlName)
	{
                $this->dbHandle = $this->getReadHandle();
                
		$sql =  "SELECT * ".
				"FROM  tregion ".
				"WHERE REPLACE(regionname,' ','') = ? ";
				
		return $this->dbHandle->query($sql, $urlName)->row_array();
	}

	
	public function saveCountry($countryName,$regionId)
	{
                $this->dbHandle = $this->dbLibObj->getWriteHandle();

		$data = array(
			'name' => $countryName,
			'creationDate' => date('Y-m-d H:i:s'),
			'enabled' => 0,
			'urlName' => $countryName,
			'continent_id' => -1,
			'tier' => 2
		);
		
		$this->dbHandle->insert('countryTable', $data);
		$countryId = $this->dbHandle->insert_id();
		
		/*
		 * Insert in tregionmapping
		 */ 
		
		if(intval($regionId) > 0 && $countryId > 0) {
			
			$data = array(
				'regionid' => $regionId,
				'id' => $countryId,
				'regionmapping' => 'country'
			);
			
			$this->dbHandle->insert('tregionmapping', $data);
		}
		
		return $countryId;
	}
	
	public function saveLocality($localityName,$zoneId,$cityId)
	{
                $this->dbHandle = $this->dbLibObj->getWriteHandle();

		$data = array(
			'localityName' => $localityName,
			'cityId' => $cityId,
			'status' => 'live',
			'submitDate' => date('Y-m-d H:i:s'),
			'zoneId' => $zoneId
		);
		
		$this->dbHandle->insert('localityCityMapping', $data);
		$locality = $this->dbHandle->insert_id();
		
		return $locality;
	}
	
	public function saveZone($zoneName)
	{
                $this->dbHandle = $this->dbLibObj->getWriteHandle();

		$data = array(
			'zoneName' => $zoneName
		);
		
		$this->dbHandle->insert('tZoneMapping', $data);
		$countryId = $this->dbHandle->insert_id();
		
		return $countryId;
	}

	public function saveCity($cityName,$stateId,$countryId)
	{
                $this->dbHandle = $this->dbLibObj->getWriteHandle();

		$data = array(
			'city_name' => $cityName,
			'countryId' => $countryId,
			'creationDate' => date('Y-m-d H:i:s'),
			'enabled' => 0,
			'state_id' => $stateId,
			'tier' => 0
		);
		
		$this->dbHandle->insert('countryCityTable', $data);
		$cityId = $this->dbHandle->insert_id();
		
		$data = array(
			'virtualCityId' => $cityId,
			'city_id' => $cityId
		);
		$this->dbHandle->insert('virtualCityMapping', $data);
		
		return $cityId;
	}

	public function getCities($countryId, $include_virtual=False)
	{
		$this->dbHandle = $this->getReadHandle();
		
		if($include_virtual) {
			$clause = " (enabled = 0 OR (enabled = 1 AND tier = 1)) ";
		}
		else {
			$clause = " enabled = 0 ";
		}
		$param = array();
		if($countryId > 1) {
			$param[] = $countryId;
		}
		
		$sql =  "SELECT * ".
				"FROM countryCityTable ".
				"WHERE ".$clause.($countryId > 1 ? " AND countryId = ?" : "");
				
		return $this->dbHandle->query($sql, $param)->result_array();
	}
	
	public function getCitiesByState($stateId, $include_virtual=False)
	{
		$this->dbHandle = $this->getReadHandle();
		
		if($include_virtual) {
			$clause = " (enabled = 0 OR (enabled = 1 AND tier = 1)) ";
		}
		else {
			$clause = " enabled = 0 ";
		}
		$param = array();
		if($stateId > 1) {
			$param[] = $stateId;
		}
		$sql =  "SELECT * ".
				"FROM countryCityTable ".
				"WHERE ".$clause.($stateId > 1 ? " AND state_id = ?" : "");
		
		return $this->dbHandle->query($sql, $param)->result_array();
	}
	
	public function getCitiesByVirtualCity($cityId)
	{
		$this->dbHandle = $this->getReadHandle();
		
		$sql =  "SELECT B.* ".
				"FROM virtualCityMapping AS A INNER JOIN countryCityTable AS B ON A.city_id = B.city_id ".
				"WHERE A.virtualCityId = ? AND enabled = 0";
		
		return $this->dbHandle->query($sql, $cityId)->result_array();
	}

	public function checkUniqueCountryName($countryName)
	{
                $this->dbHandle = $this->getReadHandle();
                
		$sql =  "SELECT name ".
				"FROM countryTable ".
				"WHERE REPLACE(name,' ','') = ".$this->dbHandle->escape(str_replace(' ','',$countryName))." ";
		
		$result = $this->dbHandle->query($sql)->row_array();
		
		if(is_array($result) && $result['name']) {
			return 'NotUnique';
		}
		else {
			return 'Unique';
		}
	}
	
	public function checkUniqueLocalityName($localityName,$zone)
	{
                $this->dbHandle = $this->getReadHandle();
                
		$sql =  "SELECT localityName ".
				"FROM localityCityMapping ".
				"WHERE zoneId = $zone AND REPLACE(localityName,' ','') = ".$this->dbHandle->escape(str_replace(' ','',$localityName))." ";
		
		$result = $this->dbHandle->query($sql)->row_array();
		
		if(is_array($result) && $result['localityName']) {
			return 'NotUnique';
		}
		else {
			return 'Unique';
		}
	}
	
	public function checkUniqueCityName($cityName)
	{
                $this->dbHandle = $this->getReadHandle();
                
		$sql =  "SELECT city_name ".
				"FROM countryCityTable ".
				"WHERE REPLACE(city_name,' ','') = ".$this->dbHandle->escape(str_replace(' ','',$cityName))." ";
		
		$result = $this->dbHandle->query($sql)->row_array();
		
		if(is_array($result) && $result['city_name']) {
			return 'NotUnique';
		}
		else {
			return 'Unique';
		}
	}
	
	
	public function checkUniqueZoneName($zone)
	{
                $this->dbHandle = $this->getReadHandle();
                
		$sql =  "SELECT zoneName ".
				"FROM tZoneMapping ".
				"WHERE REPLACE(zoneName,' ','') = ".$this->dbHandle->escape(str_replace(' ','',$zone))." ";
		
		$result = $this->dbHandle->query($sql)->row_array();
		
		if(is_array($result) && $result['zoneName']) {
			return 'NotUnique';
		}
		else {
			return 'Unique';
		}
	}
	public function getVirtualCityId($cityId){
		$this->dbHandle = $this->getReadHandle();
		$result = array();
		$sql =  "Select city_name, city_id from countryCityTable where city_id in (SELECT virtualCityId ".
                                "FROM  virtualCityMapping ".
                                "WHERE city_id = ".$this->dbHandle->escape(str_replace(' ','',$cityId))." and virtualCityId!=".$this->dbHandle->escape(str_replace(' ','',$cityId)).")";
                $result = $this->dbHandle->query($sql)->row_array();
		return $result;
	}


	public function getStatesByTier($tier = array(), $countryId)
	{

		$this->dbHandle = $this->getReadHandle();
		$selectFields   = array();
		if (count($tier) > 0) {
			$whereClause = array(
				"tier in (" . implode(", ", $tier) . ") ",
			);
		} else if (intval($tier) > 0) {
			$whereClause = array(
				"tier = $tier",
			);
		}

		$whereClause[] = "countryId = " . $countryId;
		$whereClause[] = "enabled = 0";

		$this->dbHandle->select($selectFields)->from('stateTable');
		$this->dbHandle->where(implode(" AND ", $whereClause));

		$results = $this->dbHandle->get()->result_array();

		$states = array();
		foreach($results as $result) {
			$states[$result['tier']][] = $result;
		}
		return $states;

	}
}
