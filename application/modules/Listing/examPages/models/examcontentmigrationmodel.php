<?php
class examcontentmigrationmodel extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';

    function __construct() {
        parent::__construct('Listing');
    }

    private function initiateModel($mode = "write"){
        if($this->dbHandle && $this->dbHandleMode == 'write')
            return;

        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($mode == 'read') {
                $this->dbHandle = $this->getReadHandle();
        } else {
                $this->dbHandle = $this->getWriteHandle();
        }
    }

    function migrateExamPageContent($examId = 0){
        $this->initiateModel();
        
        //Fetch the Pages
        if($examId > 0){
            $condition = " AND exam_id = '$examId' ";
        }
        $sql = "SELECT exampage_id, exam_id, status, created, updated FROM exampage_master WHERE status IN ('live','draft') $condition";
        $rs = $this->dbHandle->query($sql)->result_array();
        
        //Now, for each page, migrate section wise data
        foreach ($rs as $page){
            $this->migrateHomeData($page);
            $this->migrateResult($page);
            $this->migrateImpDates($page);
            $this->migrateSyllabus($page);
            $this->migrateSectionOrder($page);
        }
        
        return;
    }
    
    
    function migrateHomeData($page){
        $this->initiateModel();
        
        $pageId = $page['exampage_id'];
        
        $sql = "SELECT exampage_id, label, description, status FROM exampage_home WHERE exampage_id = ? AND status IN ('live','draft')";
        $rs = $this->dbHandle->query($sql, array($pageId))->result_array();
        
        foreach ($rs as $data){
       	    $data['description'] = trim($data['description']); 
	    if($data['description']!=''){
            //In case, the data is about Pattern, we will migrate it differently.
            if($data['label'] == 'Pattern'){
                $sql = "INSERT INTO exampage_content_table (page_id,section_name,entity_type,entity_value,status,creationTime,updationTime) VALUES (?,?,?,?,?,?,?)";
                $rs = $this->dbHandle->query($sql, array($pageId, 'pattern', 'pattern', $data['description'], $data['status'], $page['created'], $page['updated']));            
            }
            else{
                $sql = "INSERT INTO exampage_content_table (page_id,section_name,entity_type,entity_value,status,creationTime,updationTime) VALUES (?,?,?,?,?,?,?)";
                $rs = $this->dbHandle->query($sql, array($pageId, 'homepage', $data['label'], $data['description'], $data['status'], $page['created'], $page['updated']));
            }
	    }
        }
    }
    
    function migrateResult($page){
        $this->initiateModel();
        
        $pageId = $page['exampage_id'];
        
        //Migrate dates in Result
        $dateOrder = 0;
        $examAnalysis = $examReaction = $topperInterview = '';
        
        $sql = "SELECT exampage_id, exam_from_result_date, exam_to_result_date, event_name, article_id, status, exam_analysis, exam_reaction FROM exampage_result WHERE exampage_id = ? AND status IN ('live','draft')";
        $rs = $this->dbHandle->query($sql, array($pageId))->result_array();
        
        foreach ($rs as $data){
            $sql = "INSERT INTO exampage_content_dates (page_id,start_date,end_date,event_name,article_id,date_order,section_name,status,creationTime,updationTime) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $rs = $this->dbHandle->query($sql, array($pageId, $data['exam_from_result_date'], $data['exam_to_result_date'], $data['event_name'], $data['article_id'], $dateOrder, 'results', $data['status'], $page['created'], $page['updated']));
            $dateOrder++;
            if($data['status'] == 'live'){
                $examAnalysis = trim($data['exam_analysis']);
                $examReaction = trim($data['exam_reaction']);
            }
        }
        
        //Migrate Wiki in Results
        //Fetch the topper Interview from table and combine them if multiple found
        $sql = "SELECT exampage_id, interview, status FROM exampage_interview WHERE exampage_id = ? AND status IN ('live','draft')";
        $interview = $this->dbHandle->query($sql, array($pageId))->result_array();
        foreach ($interview as $int){
            $topperInterview .= ($topperInterview == '')?"<br><b>Topper Interview</b><br>".$int['interview']:"<br><br>".$int['interview'];
        }        
        if($examAnalysis!=''){
            $finalWiki .= "<br><b>Result Analysis</b><br>".$examAnalysis;
        }
        if($examReaction!=''){
            $finalWiki .= "<br><b>Student Reaction</b><br>".$examReaction;
        }
        if($topperInterview!=''){
            $finalWiki .= $topperInterview;
        }
        if($finalWiki != ''){        
            $sql = "INSERT INTO exampage_content_table (page_id,section_name,entity_type,entity_value,status,creationTime,updationTime) VALUES (?,?,?,?,?,?,?)";
            $rs = $this->dbHandle->query($sql, array($pageId, 'results', 'results', $finalWiki, 'live', $page['created'], $page['updated']));
        }
    }
    
    function migrateImpDates($page){
        $this->initiateModel();
        
        $pageId = $page['exampage_id'];
        $dateOrder = 0;
        
        $sql = "SELECT exampage_id, exam_from_date, exam_to_date, event_name, article_id, status FROM exampage_dates WHERE exampage_id = ? AND status IN ('live','draft')";
        $rs = $this->dbHandle->query($sql, array($pageId))->result_array();
        
        foreach ($rs as $data){
            $sql = "INSERT INTO exampage_content_dates (page_id,start_date,end_date,event_name,article_id,date_order,section_name,status,creationTime,updationTime) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $rs = $this->dbHandle->query($sql, array($pageId, $data['exam_from_date'], $data['exam_to_date'], $data['event_name'], $data['article_id'], $dateOrder, 'importantdates', $data['status'], $page['created'], $page['updated']));
            $dateOrder++;
        }
    }
    
    function migrateSyllabus($page){
        $this->initiateModel();
        
        $pageId = $page['exampage_id'];
        $sql = "SELECT exampage_id, syllabus_content, status FROM exampage_syllabus WHERE exampage_id = ? AND status IN ('live','draft')";
        $rs = $this->dbHandle->query($sql, array($pageId))->result_array();
        
        foreach ($rs as $data){
	    $data['syllabus_content'] = trim($data['syllabus_content']);
	    if($data['syllabus_content']!=''){
            $sql = "INSERT INTO exampage_content_table (page_id,section_name,entity_type,entity_value,status,creationTime,updationTime) VALUES (?,?,?,?,?,?,?)";
            $rs = $this->dbHandle->query($sql, array($pageId, 'syllabus', 'syllabus', $data['syllabus_content'], $data['status'], $page['created'], $page['updated']));
	    }
        }
    }
    
    function migrateSectionOrder($page){
        $this->initiateModel();
        
        $pageId = $page['exampage_id'];
        $page_order = array('homepage' => 1,'importantdates' => 2,'pattern' => 3,'syllabus' => 4,'results' => 5,'admitcard' => 6,'slotbooking' => 7,'answerkey' => 8,'cutoff' => 9,'counselling' => 10,'applicationform' => 11,'samplepapers' => 12);
        foreach ($page_order as $key=>$value){
            $sql = "INSERT INTO exampage_section_order (page_id,section_name,section_order,status,creationTime,updationTime) VALUES (?,?,?,?,?,?)";
            $rs = $this->dbHandle->query($sql, array($pageId, $key, $value, 'live', $page['created'], $page['updated']));
        }
    }
} 
