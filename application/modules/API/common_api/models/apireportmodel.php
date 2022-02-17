<?php
class APIReportModel extends MY_Model
{ 
	private $dbHandle = '';

	/**
	* Constructor Function 
	*/	
	function __construct(){
		parent::__construct('MISTracking');
	}

	
	/**
	* To Initiate the dbHandler instance
	*/
	private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
			$this->dbHandle = $this->getWriteHandle();
		}		
	}

	function getAPIResponseReport($date){

		$days      = 0;
		$dayClause = "";

		if($date)
			$dayClause = " AND creationDate >= ? AND creationDate <= ? ";
		
		if(empty($dayClause))
			return array();

        $dbHandle = $this->getReadHandle();
            
        $queryCmd ="SELECT controller,method,TRUNCATE(AVG(serverProcessingTime), 2) AS average_time, COUNT(*) AS totalhits  FROM api_tracking WHERE serverProcessingTime > 0 ".$dayClause." AND type='APITrack' GROUP BY controller,method ORDER BY average_time DESC";

        $result = $dbHandle->query($queryCmd, array($date." 00:00:00",$date." 23:59:59"))->result_array();

        $data = array();
        foreach ($result as $value) {
            $data[] = $value;
        }

        return $data;
    }

    function getMinutewiseAPIData($date, $controller='', $method=''){

    	if(empty($date))
    		return false;

    	$dbHandle = $this->getReadHandle();

    	$whereClause = " creationDate >= ? AND creationDate <= ? ";
	$paramArray = array($date." 00:00:00",$date." 23:59:59");

    	if($controller && $method){
    		$whereClause .= " AND controller = ? AND method = ? ";
		array_push($paramArray,$controller,$method);
    	}
            
        $queryCmd ="SELECT HOUR(creationDate) as hour, FLOOR(MINUTE(creationDate)/30) as halfhour, count(*) as cnt, TRUNCATE(AVG(serverProcessingTime), 2) as avg_time from api_tracking WHERE ".$whereClause." group by hour, halfhour";

        $result = $dbHandle->query($queryCmd, $paramArray)->result_array();

        $data = array();
        for($i= 0; $i <=23; $i++) {
        	$data[$i][0] = 0;
        	$data[$i][1] = 0;
        }

        foreach ($result as $value) {
            $data[$value['hour']][$value['halfhour']] = $value;
        }

        return $data;
    	
    }
}
