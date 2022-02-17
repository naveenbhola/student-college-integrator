<?php

class ResponseLocationFixer extends MX_Controller
{
    function fix($clientUserId,$doFix = '')
    {
        /**
         * Check if valid client id
         */ 
        $clientUserId = intval($clientUserId);
        if(!$clientUserId) {
            return false;
        }
        
        $this->dbLibObj = DbLibCommon::getInstance('User');
        $dbHandle = $this->_loadDatabaseHandle('write');
        
        $query = $dbHandle->query("SELECT lm.listing_type_id,cd.institute_id,ilt.institute_location_id ".
                                  "FROM listings_main lm ".
                                  "INNER JOIN course_details cd ON (cd.course_id = lm.listing_type_id AND cd.status = 'live') ".
                                  "INNER JOIN institute_location_table ilt ON (ilt.institute_id = cd.institute_id AND ilt.status = 'live') ".
                                  "WHERE lm.username = ? AND lm.listing_type = 'course' AND lm.status = 'live'", array($clientUserId));
        $results = $query->result_array();
        
        echo "<h1>Courses</h1>";
        
        if(count($results) > 0) {
            $courseLocationMapping = array();
            
            foreach($results as $result) {
                $courseLocationMapping[$result['listing_type_id']] = $result['institute_location_id'];
            }
            
            $query = $dbHandle->query("SELECT t.id,t.listing_type_id ".
                                      "FROM tempLMSTable t ".
                                      "LEFT JOIN responseLocationTable r ON r.responseId = t.id ".
                                      "WHERE t.listing_type = 'course' ".
                                      "t.listing_subscription_type='paid' ".
                                      "AND t.listing_type_id IN (".implode(',',array_keys($courseLocationMapping)).") ".
                                      "AND r.id IS NULL");
            
            $responses = $query->result_array();
            if(count($responses) > 0) {
                $j = 1;
                foreach($responses as $response) {
                    $sql = "INSERT INTO responseLocationTable VALUES (NULL,?,0,?)";
                    echo $j++." -- ".$sql."<br />";
                    if($doFix == 'DoFix') {
                        $dbHandle->query($sql, array($response['id'], $courseLocationMapping[$response['listing_type_id']]));
                    }
                }
            }
        }
        
        $query = $dbHandle->query("SELECT lm.listing_type_id,ilt.institute_location_id ".
                                  "FROM listings_main lm ".
                                  "INNER JOIN institute_location_table ilt ON (ilt.institute_id = lm.listing_type_id AND ilt.status = 'live') ".
                                  "WHERE lm.username = ? AND lm.listing_type = 'institute' AND lm.status = 'live'", array($clientUserId));
        $results = $query->result_array();
        
        echo "<h1>Institutes</h1>";
        
        if(count($results) > 0) {
            $instituteLocationMapping = array();
            
            foreach($results as $result) {
                $instituteLocationMapping[$result['listing_type_id']] = $result['institute_location_id'];
            }
            
            $query = $dbHandle->query("SELECT t.id,t.listing_type_id ".
                                      "FROM tempLMSTable t ".
                                      "LEFT JOIN responseLocationTable r ON r.responseId = t.id ".
                                      "WHERE t.listing_type = 'institute' ".
                                      "AND t.listing_subscription_type='paid' ".
                                      "AND t.listing_type_id IN (".implode(',',array_keys($instituteLocationMapping)).") ".
                                      "AND r.id IS NULL");
            
            $responses = $query->result_array();
            if(count($responses) > 0) {
                $j = 1;
                foreach($responses as $response) {
                    $sql = "INSERT INTO responseLocationTable VALUES (NULL,?,0,?)";
                    echo $j++." -- ".$sql."<br />";
                    if($doFix == 'DoFix') {
                        $dbHandle->query($sql, array($response['id'], $instituteLocationMapping[$response['listing_type_id']]));
                    }
                }
            }
        }
    }
}
