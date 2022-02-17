<?php

class ExamPageCache extends Cache {
    private $standardCacheTime = 21600; //6 hours
    
    function __construct() {
		parent::__construct();
        $this->CI->load->config('examPages/examPageConfig');
	}
    
    public function storeExamPage($examPage)
	{
        if(!empty($examPage)) {
            $data = serialize($examPage);
        	$this->store('ExamPage',$examPage->getExamName(), $data, $this->standardCacheTime, NULL, 1);
        }
	}
    
    public function getExamPage($examName) //on whichever is find
    {
        if(!empty($examPageId)) {
            $data = unserialize($this->get('ExamPage', $examName));
        }
        return $data;
    }
    

    function storeHierarchiesWithExamNames($hierarchiesWithExamNames) {
        $data = json_encode($hierarchiesWithExamNames);
        $this->store('HierarchiesWithExamNames_json','', $data,-1,NULL, 1);
        return;
    }
        
    function getHierarchiesWithExamNames() {
        $data = $this->get('HierarchiesWithExamNames_json','');
        return json_decode($data,true);
    }

    function storExamList($examList){
        $data = gzcompress(serialize($examList), 9);
        $this->store('listOfExams','', $data,-1,NULL, 1);
        return;
    }

    function getExamList(){
        $data = $this->get('listOfExams','');
        return unserialize(gzuncompress($data));   
    }

    function clearCacheByKey($key){
        $this->deleteByKey($key);
    }

    function storeRedirectExamsList($data){
        $this->addMembersToHash('redirectExamsList','', $data, -1);
        return;   
    }

    function getRedirectExamsList($oldExamName){
        if(empty($oldExamName)){return;}
        $data = $this->getMembersOfHashByFieldNameWithValue('redirectExamsList','',array($oldExamName));
        return $data[$oldExamName]; 
    }

    function setExamBasicByName($data){
        foreach ($data as $key => $value) {
            $data[$key] = json_encode($value);
        }
        $this->addMembersToHash(ExamBasicByName,'', $data, 1800); // using for validate url
        return;   
    }

    function getExamBasicByName($examName){
        if(empty($examName)){return;}
        $data = $this->getMembersOfHashByFieldNameWithValue(ExamBasicByName,'',array($examName));
        return json_decode($data[$examName],true);
    }

    function deleteExamCache($key){
        $this->delete($key,'');
        return;
    }
    function storeCourseAcceptingExam($examId,$data)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "courseAcceptExam:".$examId;
        $expireInSeconds = 24 * 60 * 60;//hours * minutes * seconds
        $result = $redis_client->addMemberToString($stringKey,$data,$expireInSeconds);
        return $result;
    }
    function getCourseAcceptingExam($examId)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "courseAcceptExam:".$examId;
        $data = $redis_client->getMemberOfString($stringKey);
        return $data;
    }
    function storeSolrCourseAcceptingExam($examId,$data)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "courseAcceptExamSolr:".$examId;
        $expireInSeconds = 24 * 60 * 60;//hours * minutes * seconds
        $result = $redis_client->addMemberToString($stringKey,$data,$expireInSeconds);
        return $result;
    }
    function getSolrCourseAcceptingExam($examId)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "courseAcceptExamSolr:".$examId;
        $data = $redis_client->getMemberOfString($stringKey);
        return $data;
    }
    function storeLinkForAcceptingExam($examId,$data)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "linkCourseAcceptExam:".$examId;
        $expireInSeconds = 15 * 24 * 60 * 60;//hours * minutes * seconds
        $result = $redis_client->addMemberToString($stringKey,$data,$expireInSeconds);
        return $result;   
    }
    function getLinkForAcceptingExam($examId)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "linkCourseAcceptExam:".$examId;
        $data = $redis_client->getMemberOfString($stringKey);
        return $data;
    }


    function storeExamYearMapping($data)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "examYearMappingCache";
        $expireInSeconds = 24 * 60 * 60;//hours * minutes * seconds
        $data = json_encode($data);
        $result = $redis_client->addMemberToString($stringKey,$data,$expireInSeconds);
        return $result;
    }

    function getExamYearMapping()
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "examYearMappingCache";
        $data = $redis_client->getMemberOfString($stringKey);
        return json_decode($data,true);
    }
    function storeSimilarExamsMapping($examId,$groupId,$isMobile,$data)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = (!$isMobile ? 'desk_' : 'mob_')."smlrexams_".$examId.'_'.$groupId;
        $expireInSeconds = 24 * 60 * 60;//hours * minutes * seconds
        $data = json_encode($data);
        $result = $redis_client->addMemberToString($stringKey,$data,$expireInSeconds);
        return $result;   
    }
    function getSimilarExamsMapping($examId,$groupId,$isMobile)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = (!$isMobile ? 'desk_' : 'mob_')."smlrexams_".$examId.'_'.$groupId;
        $data = $redis_client->getMemberOfString($stringKey);
        return json_decode($data,true);
    }
    function storeAllSimilarExamsMapping($examId,$groupId,$data)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "all_smlrexams_".$examId.'_'.$groupId;
        $expireInSeconds = 24 * 60 * 60;//hours * minutes * seconds
        $data = json_encode($data);
        $result = $redis_client->addMemberToString($stringKey,$data,$expireInSeconds);
        return $result;   
    }
    function getAllSimilarExamsMapping($examId,$groupId)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "all_smlrexams_".$examId.'_'.$groupId;
        $data = $redis_client->getMemberOfString($stringKey);
        return json_decode($data,true);
    }
    function storeExamApplyOnline($examId,$groupId,$data)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "examapply_".$examId.'_'.$groupId;
        $expireInSeconds = 24 * 60 * 60;//hours * minutes * seconds
        $data = json_encode($data);
        $result = $redis_client->addMemberToString($stringKey,$data,$expireInSeconds);
        return $result;   
    }
    function getExamApplyOnline($examId,$groupId)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "examapply_".$examId.'_'.$groupId;
        $data = $redis_client->getMemberOfString($stringKey);
        return json_decode($data,true);
    }
    function storeUpcomingExamDates($Id,$type,$data)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "upcomingexams_".$Id.'_'.$type;
        $expireInSeconds = 24 * 60 * 60;//hours * minutes * seconds
        $data = json_encode($data);
        $result = $redis_client->addMemberToString($stringKey,$data,$expireInSeconds);
        return $result;   
    }
    function getUpcomingExamDates($Id,$type)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "upcomingexams_".$Id.'_'.$type;
        $data = $redis_client->getMemberOfString($stringKey);
        return json_decode($data,true);
    }

    function setCMSUserLockingInfo($examId,$data){
        if($examId){
            $expireInSeconds = 3600;
            $data = json_encode($data);
            $this->store('examCmsUser_',$examId, $data, $expireInSeconds);    
        }
    }

    function getCMSUserLockingInfo($examId){
        if($examId){
            $data = $this->get('examCmsUser_', $examId);
            return json_decode($data,true);    
        }
    }

    function deleteCMSUserLockingInfo($examId){
        if($examId){
            $this->delete("examCmsUser_", $examId);
            return;    
        }
    }

    function setCHPCMSUserLockingInfo($chpId,$data){
        if($chpId){
            $expireInSeconds = 3600;
            $data = json_encode($data);
            $this->store('chpCmsUser_',$chpId, $data, $expireInSeconds);    
        }
    }

    function getCHPCMSUserLockingInfo($chpId){
        if($chpId){
            $data = $this->get('chpCmsUser_', $chpId);
            return json_decode($data,true);    
        }
    }

    function deleteCHPCMSUserLockingInfo($chpId){
        if($chpId){
            $this->delete("chpCmsUser_", $chpId);
            return;    
        }
    }

}
