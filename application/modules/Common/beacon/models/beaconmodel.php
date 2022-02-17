<?php
require_once('vendor/autoload.php');

class Beaconmodel extends MY_Model
{
    function __construct()
    {
        parent::__construct('Beacon');
    }
    
    function getDbHandle($operation = 'read')
	{
		if($operation=='read'){
			return $this->getReadHandle();
		}
		else{
        	return $this->getWriteHandle();
		}
	}
    
    function trackSession($sessionData)
    {
        $dbHandle = $this->getDbHandle('write');
        $dbHandle->insert('session_tracking',$sessionData);
    }
    
    function bulkTrackSession($sessionData)
    {
        $dbHandle = $this->getDbHandle('write');
        $dbHandle->save_queries = false;
        $dbHandle->insert_batch('session_tracking',$sessionData);
    }

    function trackPageView($pageViewData)
    {
        $dbHandle = $this->getDbHandle('write');
        $dbHandle->insert('pageview_tracking',$pageViewData);
    }

    function bulkTrackPageViews($pageViewData)
    {
        $dbHandle = $this->getDbHandle('write');
        $dbHandle->save_queries = false;
        $dbHandle->insert_batch('pageview_tracking',$pageViewData);
    }

	function getVisitorSessionCount($visitorId)
	{
		$dbHandle = $this->getDbHandle('read');
		$sql = "SELECT COUNT(id) AS count FROM session_tracking WHERE visitorId = ?";
		$query = $dbHandle->query($sql, array($visitorId));
		$row  = $query->row_array();
		return intval($row['count']);
	}
    function isSessionRegistered($sessionId)
    {
        $dbHandle = $this->getDbHandle('read');
        $sql = "SELECT id FROM session_tracking WHERE sessionId = ?";
        $query = $dbHandle->query($sql, array($sessionId));
        $row  = $query->row_array();

        if($row['id']) {
            error_log("SESSREG:: YES ".$row['id']);
            return TRUE;
        }
        else {
            error_log("SESSREG:: NO");
            return FALSE;
       }
    }

    function updateViewCount($page,$viewCountData){
        $dbHandle = $this->getDbHandle('write');
        if($page == 'examPage'){
            foreach ($viewCountData as $listingId => $viewCount) {
                $sql = 'update exampage_master set view_count=view_count+? where groupId= ? and status in ("live","draft")';
                $dbHandle->query($sql,array($viewCount, $listingId));
                echo $dbHandle->last_query();
            }
        }
    }
}
