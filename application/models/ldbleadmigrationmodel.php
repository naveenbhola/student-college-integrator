<?php 
class LDBLeadMigrationModel extends MY_Model {
    function __construct() {
		parent::__construct('User');
    }

    function getAllResponsesForClient($courseIds, $tempLMSId){
        $dbHandle = $this->getReadHandle();

        $sql = "Select id, userId, listing_type_id as courseId from tempLMSTable where listing_type_id in (?) and listing_type='course' and listing_subscription_type ='paid' and id>? limit 100";
        $result = $dbHandle->query($sql,array($courseIds, $tempLMSId))->result_array();


        return $result;
    }
    
    function getAllMatchedResponsesForClient($genieIds, $logId){
        $dbHandle = $this->getReadHandle();

        $sql = "Select id, leadid as userId from SALeadMatchingLog where  searchagentid in (?) and id>? limit 100";
        $result = $dbHandle->query($sql,array($genieIds, $logId))->result_array();

        return $result;  

    }


    function getMRCourseId($logId, $userId){
        $dbHandle = $this->getReadHandle();

        $sql = "Select matchingLogId as id, matchedCourseId as courseId from userMatchedResponseCoursesTable where  matchingLogId in (?) and userid in (?)";
        $result = $dbHandle->query($sql,array($logId, $userId))->result_array();

        return $result[0];  

    }

    function getUserInfoData($distinctUserIds){
        $dbHandle = $this->getReadHandle();

        $sql = "Select firstname, lastname, city, email, mobile, userId, C.state_id as state, state_name, city_name from tuser join countryCityTable C on city=city_id join stateTable S on S.state_id=C.state_id where userid in (?)";
        $result = $dbHandle->query($sql,array($distinctUserIds))->result_array();

        //_P($dbHandle->last_query());
        return $result;          
    }

    function getGlaVendorMapping($vendor){
        $dbHandle = $this->getReadHandle();

        $sql = "select shiksha_entity,vendor_entity, vendor_name, entity_type from shiksha_vendor_mapping where vendor_name=?";
        $result = $dbHandle->query($sql,array($vendor))->result_array();

        return $result;             
    }
    //

    function getUserDataInProfile($userIds){
        $dbHandle = $this->getReadHandle();

        $sql = "SELECT UserId,examGroupId,MarksType FROM tUserEducation where Level = 'Competitive exam' AND UserId IN (?)";
        $result = $dbHandle->query($sql,array($userIds));
        $dbData = $result->result_array();
        $userSavedData = array();
        foreach ($dbData as $key => $value) {
            $userSavedData[$value["UserId"]][$value["examGroupId"]][$value["MarksType"]] = true;
        }
        return $userSavedData;
    }

    function postUserExamDataInProfile($examsDataToUpload){
        $dbHandle = $this->getWriteHandle();
        $dbHandle->insert_batch('tUserEducation',$examsDataToUpload);
        return $dbHandle->affected_rows();
    }

    function getTrackingDataCount($startDate,$endDate){
        $dbHandle = $this->getReadHandle();
        $sql = "select count(distinct userId,listing_type_id) as count from tempLMSTable where listing_type='exam' AND submit_date >= ? AND submit_date <= ? AND action != 'exam_viewed_response'";
        $query = $dbHandle->query($sql,array($endDate,$startDate));
        return $query->result_array();
    }

    function getPredictorTrackingData($startDate,$endDate,$batchNo,$batchSize){
        $dbHandle = $this->getReadHandle();
        $sql = "select userId,listing_type_id AS groupId,submit_date AS SubmitDate from tempLMSTable where listing_type='exam' AND submit_date >= ? AND submit_date <= ? AND action != 'exam_viewed_response' group by userId,listing_type_id limit ?, ? ";
        $query = $dbHandle->query($sql,array($endDate,$startDate,$batchNo*$batchSize,$batchSize));
        return $query->result_array();
    }
} ?>

