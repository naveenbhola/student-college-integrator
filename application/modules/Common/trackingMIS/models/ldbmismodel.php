<?php
class ldbmismodel extends MY_Model
{
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct()
	{ 
		parent::__construct('MISTracking');
	}
	
	private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}
    
    function countMatchingLeads($filterArray)
    {
        $this->initiateModel();

        if($filterArray['isMR'] == 'Y') {
            $type= 'response';
        } else {
            $type= 'lead';
        }
        $sql = "SELECT date(sam.matchingTime) as matchingDate, count(*)  as count FROM SALeadMatchingLog sam inner join SASearchAgent sa on sam.searchAgentid = sa.searchagentid where sa.type = ? and date(sam.matchingTime) >= ? and date(sam.matchingTime) <= ? group by date(sam.matchingTime)";
        $query = $this->dbHandle->query($sql, array($type, $filterArray['startDate'], $filterArray['endDate']));

        $matchingLeadsCount = array();
        foreach ($query->result_array() as $row) {
            $matchingLeadsCount[$row['matchingDate']] = $row['count'];
        }
        
        return $matchingLeadsCount;
    }

    function countAllocationLeads($filterArray)
    {
        $this->initiateModel();

        if($filterArray['isMR'] == 'Y') {
            $type= 'response';
        } else {
            $type= 'lead';
        }
        $sql = "SELECT date(sam.allocationtime) as allocationDate, count(distinct sam.userid)  as count FROM SALeadAllocation sam inner join SASearchAgent sa on sam.agentid = sa.searchagentid where type = ? and date(sam.allocationtime) >= ? and date(sam.allocationtime) <= ? group by date(sam.allocationtime)";
        $query = $this->dbHandle->query($sql, array($type, $filterArray['startDate'], $filterArray['endDate']));

        $allocationLeadsCount = array();
        foreach ($query->result_array() as $row) {
            $allocationLeadsCount[$row['allocationDate']] = $row['count'];
        }
        
        return $allocationLeadsCount;
    }

}
