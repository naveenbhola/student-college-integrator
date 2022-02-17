<?php
class StatsModel extends MY_Model
{ 
    private $dbHandle = '';
    private $CI;

    /**
    * Constructor Function 
    */  
    function __construct(){
        parent::__construct('MISTracking');
        $this->CI = &get_instance();
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

        public function getAppQuestionsData($date){
            $dbHandle = $this->getReadHandle();
            $queryCmd ="SELECT count(*) dataCount, date(creationDate) creationDate FROM messageTable WHERE creationDate>='$date' AND fromOthers='user' AND parentId=0 AND listingTypeId=0 AND tracking_keyid IN (555,556,557,558,559,560,617,621) and status IN ('live','closed') GROUP BY date(creationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
                $data[] = $value;
            }
            return $data;
        }

        public function getTotalQuestionsData($date){
            $dbHandle = $this->getReadHandle();
            $queryCmd ="select count(*) dataCount, date(creationDate) creationDate from messageTable where creationDate>='$date' and fromOthers='user' and parentId=0 and listingTypeId=0 and status IN ('live','closed') group by date(creationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
                $data[] = $value;
            }
            return $data;
        }

        public function getAppAnswerData($date){
            $dbHandle = $this->getReadHandle();
            $queryCmd ="SELECT count(*) dataCount, date(creationDate) creationDate FROM messageTable WHERE creationDate>='$date' AND fromOthers='user' AND parentId=threadId AND listingTypeId=0 AND tracking_keyid IN (561,562,563,564,631) and status IN ('live','closed') GROUP BY date(creationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
                $data[] = $value;
            }
            return $data;
        }

        public function getTotalAnswerData($date){
            $dbHandle = $this->getReadHandle();
            $queryCmd ="select count(*) dataCount, date(creationDate) creationDate from messageTable where creationDate>='$date' and fromOthers='user' and parentId=threadId and listingTypeId=0 and status IN ('live','closed') group by date(creationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
                $data[] = $value;
            }
            return $data;
        }

        public function getDeviceData($date){
            $dbHandle = $this->getReadHandle();
            $queryCmd ="select count(distinct deviceId) dataCount, date(creationDate) creationDate from api_tracking where creationDate>='$date' group by date(creationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
                $data[] = $value;
            }
            return $data;
        }

        public function getApiData($date){
            $dbHandle = $this->getReadHandle();
            $queryCmd ="select count(*) dataCount, date(creationDate) creationDate from api_tracking where creationDate>='$date' group by date(creationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
                $data[] = $value;
            }
            return $data;
        }

        public function getTagFollowData($date){
            $dbHandle = $this->getReadHandle();
            $queryCmd ="select count(*) dataCount, date(creationTime) creationDate from tuserFollowTable where creationTime>='$date' and entityType='tag' and status='follow' group by  date(creationTime)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
                $data[] = $value;
            }
            return $data;
        }

        public function getUserFollowData($date){
            $dbHandle = $this->getReadHandle();
            $queryCmd ="select count(*) dataCount, date(creationTime) creationDate from tuserFollowTable where creationTime>='$date' and entityType='user' and status='follow' group by  date(creationTime)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
                $data[] = $value;
            }
            return $data;
        }

        public function totalRegData($date){
            $dbHandle = $this->getReadHandle();
            $queryCmd ="select count(*) dataCount, date(usercreationDate) creationDate from tuser where usercreationDate>='$date' and date(usercreationDate)!='2016-03-01' group by  date(usercreationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
                $data[] = $value;
            }
            return $data;
        }

        public function appRegData($date){
            $dbHandle = $this->getReadHandle();
            $queryCmd ="select count(*) dataCount, date(usercreationDate) creationDate from tuser t, tusersourceInfo i where t.userid = i.userid and usercreationDate>='$date' and date(usercreationDate)!='2016-03-01' and  referer like '%Android%' group by date(usercreationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
                $data[] = $value;
            }
            return $data;
        }

        public function performanceData($date){
            $dbHandle = $this->getReadHandle();
            $queryCmd ="select avg(serverProcessingTime) dataCount,  date(creationDate) creationDate from api_tracking where creationDate>='$date' group by date(creationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
                $data[] = $value;
            }
            return $data;
        }

    public function getSharingData($date){
            $dbHandle = $this->getReadHandle();
            $queryCmd ="select count(*) dataCount,  date(creationDate) creationDate from entityShareLog where creationDate>='$date' and status='live' group by date(creationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
                $data[] = $value;
            }
            return $data;
    }

        public function getAnswerRateOnShiksha($date){
            $dbHandle = $this->getReadHandle();

        $dateEnd = date("Y-m-d");
        $dateEnd = strtotime("-3 days",strtotime($dateEnd));
        $dateEnd = date ( 'Y-m-j' , $dateEnd );
        
        //Get total number of questions asked on each day
            $queryCmd ="select count(*) dataCount, date(creationDate) creationDate from messageTable where creationDate>='$date' and date(creationDate) <= '$dateEnd' and fromOthers='user' and parentId=0 and listingTypeId=0 and status IN ('live','closed') group by date(creationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
                $data[] = $value;
            }

        //Get number of Questions answered within 24 hours      
            $queryCmd ="select count(*) as questionCountAnswered, date(questionCreationDate) creationDate from (select m1.creationDate as questionCreationDate, (select creationDate from messageTable M2 where M2.parentId = M2.threadId and M2.status not in ('deleted','abused') and M2.fromOthers = 'user' and M2.threadId = m1.threadId order by msgId asc limit 1) firstAnswerCreationDate from messageTable m1 where m1.parentId = 0 and m1.fromOthers = 'user' and m1.listingTypeId = 0 and m1.status not in ('deleted','abused') and date(m1.creationDate) <= '$dateEnd' and date(m1.creationDate) >= '$date' having TIMESTAMPDIFF(MINUTE,m1.creationDate,firstAnswerCreationDate)<=1440) Res group by date(questionCreationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $sameDayData = array();
            foreach ($result as $value) {
                $sameDayData[] = $value;
            }

        //Get number of Questions answered within 48 hours      
            $queryCmd ="select count(*) as questionCountAnswered, date(questionCreationDate) creationDate from (select m1.creationDate as questionCreationDate, (select creationDate from messageTable M2 where M2.parentId = M2.threadId and M2.status not in ('deleted','abused') and M2.fromOthers = 'user' and M2.threadId = m1.threadId order by msgId asc limit 1) firstAnswerCreationDate from messageTable m1 where m1.parentId = 0 and m1.fromOthers = 'user' and m1.listingTypeId = 0 and m1.status not in ('deleted','abused') and date(m1.creationDate) <= '$dateEnd' and date(m1.creationDate) >= '$date' having TIMESTAMPDIFF(MINUTE,m1.creationDate,firstAnswerCreationDate)<=2880) Res group by date(questionCreationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $twoDayData = array();
            foreach ($result as $value) {
                $twoDayData[] = $value;
            }

        //Now, find the Answer rate within 24 hours
        $sameDayFinalArray = array();
        foreach ($data as $question){
        $questionDate = $question['creationDate'];
        foreach ($sameDayData as $answer){
            $answerDate = $answer['creationDate'];
            if($questionDate == $answerDate){
                $totalQuestions = $question['dataCount'];
                $questionCountAnswered24Hrs = $answer['questionCountAnswered'];
                $answerRate = round(($questionCountAnswered24Hrs/$totalQuestions) * 100);
                $sameDayFinalArray[] = array('dataCount'=>$answerRate,'creationDate'=>$questionDate);
            }
        }
        }

        //Now, find the Answer rate within 48 hours
        $twoDayFinalArray = array();
        foreach ($data as $question){
        $questionDate = $question['creationDate'];
        foreach ($twoDayData as $answer){
            $answerDate = $answer['creationDate'];
            if($questionDate == $answerDate){
                $totalQuestions = $question['dataCount'];
                $questionCountAnswered24Hrs = $answer['questionCountAnswered'];
                $answerRate = round(($questionCountAnswered24Hrs/$totalQuestions) * 100);
                $twoDayFinalArray[] = array('dataCount'=>$answerRate,'creationDate'=>$questionDate);
            }
        }
        }

            return array($sameDayFinalArray,$twoDayFinalArray);
        }

    public function getInstallData($date)   {
            $dbHandle = $this->getReadHandle();
            $queryCmd ="select deviceId, date(creationDate) creationDate from api_tracking where creationDate>='$date' group by deviceId order by date(creationDate)";
            $result = $dbHandle->query($queryCmd)->result_array();
            $data = array();
            foreach ($result as $value) {
        $dateVal = $value['creationDate'];
        if(isset($data[$dateVal])){
            $data[$dateVal]++;
        }
        else{
            $data[$dateVal] = 1;    
        }
            }
        
        $finalData = array();
        foreach ($data as $key=>$value){
        $finalData[] = array('dataCount'=>$value,'creationDate'=>$key);
        }
            return $finalData;
    }

    function getResponseCount($period, $type) {
        $this->initiateModel('read');

        $this->CI->load->config('nationalCategoryList/nationalConfig');
        $this->TRACKING_KEYS_IDS = $this->CI->config->item('TRACKING_KEYS_IDS');
        
        if($type == 'total_responses') {
            $select = "t.userId, t.listing_type_id, t.listing_type";
            $where = "t.submit_date >= '".$period['startDate']."' and t.submit_date <= '".$period['endDate']."' AND tracking_keyid IN ".$this->TRACKING_KEYS_IDS."";

            $sql = "select count(distinct ".$select.") as count from tempLMSTable t where ".$where;
            
            $totalResponses = $this->dbHandle->query($sql)->result_array();
            return $totalResponses;
        }
        
        $batchSize = 500;

        $sql = "select distinct sessionId from searchCourseTracking where 
        created >= '".$period['startDate']."' and created <= '".$period['endDate']."' 
        and pageType in ('open', 'close', 'interim') 
        and requestFrom in ('searchwidget', 'examAcceptingWidget', 'ArticleInterLinking', 'zeroresultspage', 'fromgoogle')";
        
        $data = $this->dbHandle->query($sql)->result_array();
        
        $sessionCount = 0; $sessionIds = array();
        foreach ($data as $key => $value) {
            if(!ctype_alnum($value['sessionId'])) {
                continue;
            }
            $sessionIds[] = "'".$value['sessionId']."'";
            $allSessionIds[$value['sessionId']] = 1;
            $sessionCount++;
        }
        
        $sessionIdArr = array_chunk($sessionIds, $batchSize);

        $finalData = array();
        foreach ($sessionIdArr as $key => $sessionIds) {
            switch ($type) {
                case 'response':
                    $select = "t.userId, t.listing_type_id, t.listing_type";
                    $where = "t.visitorsessionid in (".implode(',', $sessionIds).") AND tracking_keyid IN ".$this->TRACKING_KEYS_IDS;
                    break;

                case 'session':
                    $select = "t.visitorsessionid";
                    $where = "t.visitorsessionid in (".implode(',', $sessionIds).") AND tracking_keyid IN ".$this->TRACKING_KEYS_IDS;
                    break;
            }
        
            $sql = "select count(distinct ".$select.") as count from tempLMSTable t where ".$where;
            
            $finalData['searchCourseTracking'][] = $this->dbHandle->query($sql)->result_array();
        }
        
        $tables = array('searchInstituteTracking', 'searchQuestionTracking', 'searchCareerTracking', 'searchExamTracking');
        
        foreach ($tables as $key => $table) {
            $sql = "select distinct sessionId from ".$table." where 
            created >= '".$period['startDate']."' and created <= '".$period['endDate']."'";

            if($type != 'total_responses') {
                $data = $this->dbHandle->query($sql)->result_array();
            }
            
            $sessionIds = array();
            foreach ($data as $key => $value) {
                if(!ctype_alnum($value['sessionId']) || $allSessionIds[$value['sessionId']]) {
                    continue;
                }
                
                $sessionIds[] = "'".$value['sessionId']."'";
                $allSessionIds[$value['sessionId']] = 1;
                $sessionCount++;
            }
            
            $sessionIdArr = array_chunk($sessionIds, $batchSize);

            foreach ($sessionIdArr as $key => $sessionIds) {
                switch ($type) {
                    case 'response':
                        $select = "t.userId, t.listing_type_id, t.listing_type";
                        $where = "t.visitorsessionid in (".implode(',', $sessionIds).") AND tracking_keyid IN ".$this->TRACKING_KEYS_IDS;
                        break;

                    case 'session':
                        $select = "t.visitorsessionid";
                        $where = "t.visitorsessionid in (".implode(',', $sessionIds).") AND tracking_keyid IN ".$this->TRACKING_KEYS_IDS;
                        break;
                }
            
                $sql = "select count(distinct ".$select.") as count from tempLMSTable t where ".$where;

                $finalData[$table][] = $this->dbHandle->query($sql)->result_array();
            }
        }
        
        $count = array();
        foreach ($finalData as $table => $valueArr) {
            $count[$table] = 0;
            foreach ($valueArr as $key => $value) {
                $count[$table] = $count[$table] + $value[0]['count'];
            }
        }

        $totalResponses = 0;
        foreach ($count as $key => $val) {
            $totalResponses = $totalResponses + $val;
        }

        return array('response'=>$count, 'total_responses'=>$totalResponses, 'session'=>$sessionCount);
    }
}
