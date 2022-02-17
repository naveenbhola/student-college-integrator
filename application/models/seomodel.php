<?php

class seomodel extends MY_Model
{
    function __construct() {
        parent::__construct('AnA');
        $this->load->helper('url');
    }

    private function initiateModel($operation='read'){
        if($operation=='read'){
            $this->dbHandle = $this->getReadHandle();
        }else{
            $this->dbHandle = $this->getWriteHandle();
        }
    }
    
    public function getANAURLForSitemap($count){
            $this->initiateModel('read');
            
            //Get the Ids of all the last Questions or discussions on which Answer/Comment is posted
            $date = strtotime("-15 days",strtotime(date('Y-m-d')));
            $date = date ( 'Y-m-d' , $date );
	    $queryCmd = "SELECT DISTINCT(mt.threadId), mt.fromOthers FROM messageTable mt where mt.status IN ('live','closed') AND ((mt.fromOthers = 'user' AND mt.mainAnswerId >= 0) OR (mt.fromOthers='discussion' AND mt.mainAnswerId > 0)) AND (select status from messageTable where msgId  = mt.threadId) IN ('live','closed') AND mt.creationDate >= '$date' ORDER BY mt.msgId DESC LIMIT $count";
	    $query = $this->dbHandle->query($queryCmd);

            $finalResult = $result = $questionArray = $discussionArray = array();
            foreach ($query->result_array() as $row)
            {
                if($row['fromOthers'] == 'user'){
                    $questionArray[] = $row['threadId'];
                }
                else{
                    $discussionArray[] = $row['threadId'];
                }
            }

            //Get the details of all these Questions and Discussions
            if(count($questionArray) > 0){
                $queryCmdQ = "SELECT msgId, msgTxt, creationDate FROM messageTable WHERE status IN ('live','closed') AND msgId IN (?)";
                $queryQ = $this->dbHandle->query($queryCmdQ, array($questionArray));
                foreach ($queryQ->result_array() as $questionRow){
                    $result[$questionRow['msgId']] = getSeoUrl($questionRow['msgId'], 'question', $questionRow['msgTxt'], '', '', $questionRow['creationDate']);
                }
            }
            
            if(count($discussionArray) > 0){
                $queryCmdD = "SELECT msgId, msgTxt, creationDate, threadId FROM messageTable WHERE status IN ('live','closed') AND threadId IN (?) AND mainAnswerId = 0";
                $queryD = $this->dbHandle->query($queryCmdD, array($discussionArray));
                foreach ($queryD->result_array() as $discussionRow){
                    $result[$discussionRow['threadId']] = getSeoUrl($discussionRow['threadId'], 'discussion', $discussionRow['msgTxt'], '', '', $discussionRow['creationDate']);
                }
            }
            
            //Now, sort these Q & D as per the original order
            foreach ($query->result_array() as $row)
            {
                if( isset($result[$row['threadId']]) && $result[$row['threadId']] != ''){
                    $finalResult['URL'][] = $result[$row['threadId']];
                }
            }
            
            return $finalResult;
    }
    
    public function getArticleExamURLForSitemap($count){
            $this->initiateModel('read');
            
            //Get the Ids of all the last Articles, News Posted/Updated
            $date = strtotime("-30 days",strtotime(date('Y-m-d')));
            $date = date ( 'Y-m-d' , $date );
	    $queryCmd = "SELECT CONCAT('".SHIKSHA_HOME."',url) url, lastModifiedDate as updatedDate FROM blogTable WHERE status = 'live' AND blogType IN ('user','news','testprep','kumkum','faq','ht') AND lastModifiedDate >= '$date' ORDER BY lastModifiedDate DESC LIMIT $count";
	    $query = $this->dbHandle->query($queryCmd);
            $articleArray = $query->result_array();
            
            
            //Get the URL/Updated Date of all the last Exams & Groups & their Childs Posted/Updated
            $result = array();

            $this->load->builder('ExamBuilder','examPages');
            $examBuilder          = new ExamBuilder();
            $this->examRepository = $examBuilder->getExamRepository();
            $this->examPageRequest = $this->load->library('examPages/ExamPageRequest');

            $date = strtotime("-20 days",strtotime(date('Y-m-d')));
            $date = date ( 'Y-m-d' , $date );
            $examCount = floor($count/10);
	    $queryCmd = "SELECT DISTINCT(master.exam_id) examId, main.name as examName, main.url url, master.updated as updatedDate, master.groupId groupId, master.exampage_id as pageId, groups.isPrimary isPrimary FROM exampage_main main, exampage_master master, exampage_groups groups WHERE main.status = 'live' AND master.status = 'live' AND groups.status = 'live' AND main.id = master.exam_id AND master.updated >= '$date' AND groups.groupId = master.groupId ORDER BY master.updated DESC LIMIT $examCount";
	    $queryExam = $this->dbHandle->query($queryCmd);

            //So, for each of these exams, find if the Group updated is the primary group. If yes, we can use the URL, but if not, we will add GroupId along with the URL
            foreach ($queryExam->result_array() as $row)
            {
                error_log(print_r($row,true));
                $isPrimary = $row['isPrimary'];
                $url = $row['url'];
                $pageId = $row['pageId'];
                $groupId = $row['groupId'];
                $examId = $row['examId'];

                $examBasicObj = $this->examRepository->find($examId);
                $examContentObj = $this->examRepository->findContent($groupId, 'sectionname');
                $this->examPageRequest->setExamName($row['examName']);
                error_log(print_r($examContentObj,true));
                
                if(isset($examContentObj['sectionname'])){
                    foreach ($examContentObj['sectionname'] as $rowChild){
                        
                        //Find the URL of Homepage & child pages.
                        if($isPrimary){
                            $examUrl = $this->examPageRequest->getUrl($rowChild, true, false);
                        }
                        else{
                            $examUrl = $this->examPageRequest->getUrl($rowChild, true, false, $groupId);
                        }
    
                        error_log($examUrl);
                        if($examUrl != ''){
                            $result[] = array('url' => $examUrl, 'updatedDate' => $row['updatedDate']);
                        }
                        
                    }
                }
            }

            //Now, merge Article & Exam data as per date and limit it to Count
            $finalResult = array_merge($result, $articleArray);
            usort(
                $finalResult,
                function ($a, $b) {
                    if ($a['updatedDate'] == $b['updatedDate']) {
                        return 0;
                    }
                    return ($a['updatedDate'] > $b['updatedDate']) ? -1 : 1;
                }
            );
            $finalResult = array_slice  ( $finalResult , 0, $count);

            $resultReturn = array();
            foreach ($finalResult as $row){
                $resultReturn['URL'][] = $row['url'];
            }
            return $resultReturn;
    }
}