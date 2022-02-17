<?php

class GoogleWebLightCron extends MX_Controller
{
    function run()
    {
        $this->validateCron();
        $todayDate = date('Y-m-d');
        $yesterdayDate = date('Y-m-d', strtotime('-1 day'));

        $this->dbLibObj = DbLibCommon::getInstance('MISTracking');
        $dbHandle = $this->_loadDatabaseHandle();

        // total sessions
        $sql = "select count(*) as c from session_tracking where startTime > '$yesterdayDate' and startTime < '$todayDate' and isMobile = 'yes'";
        $query = $dbHandle->query($sql);
        $row = $query->row_array();
        $totalSessions = $row['c'];

        // clp sessions
        $sql = "select count(*) as c from session_tracking where startTime > '$yesterdayDate' and startTime < '$todayDate' and isMobile = 'yes' and landingPageURL like '%/course/%'";
        $query = $dbHandle->query($sql);
        $row = $query->row_array();
        $totalSessionsCLP = $row['c'];

        // total weblight sessions
        $sql = "select count(*) as c from session_tracking where startTime > '$yesterdayDate' and startTime < '$todayDate' and isMobile = 'yes' and userAgent like '%googleweblight%'";
        $query = $dbHandle->query($sql);
        $row = $query->row_array();
        $gwlSessions = $row['c'];

        // CLP weblight sessions
        $sql = "select count(*) as c from session_tracking where startTime > '$yesterdayDate' and startTime < '$todayDate' and isMobile = 'yes' and landingPageURL like '%/course/%' and userAgent like '%googleweblight%'";
        $query = $dbHandle->query($sql);
        $row = $query->row_array();
        $gwlSessionsCLP = $row['c'];

        /**
         * Insert in app monitor
         */

        $this->dbLibObj = DbLibCommon::getInstance('AppMonitor');
        $dbHandle = $this->_loadDatabaseHandle('write');

        $dbData = array('numSessions' => $totalSessions, 'numGoogleWebLightSessions' => $gwlSessions, 'date' => $yesterdayDate, 'pageType' => 'all');
        $dbHandle->insert('session_data', $dbData);

        $dbData = array('numSessions' => $totalSessionsCLP, 'numGoogleWebLightSessions' => $gwlSessionsCLP, 'date' => $yesterdayDate, 'pageType' => 'courseDetailPage');
        $dbHandle->insert('session_data', $dbData);
    }
}

