<?php
class customizemmp_model extends MY_Model {
	private $dbHandle = '';
   
    function __construct(){
      parent::__construct('CustomizedMMP');
    }
	
	private function initiateModel($operation = 'read') {
		if($operation=='read') {
			$this->dbHandle = $this->getReadHandle();
		}
		else{
			$this->dbHandle = $this->getWriteHandle();
		}
	}
	
	public function createMMPPage($modelParams) {
		if($this->dbHandle == ''){
			$this->initiateModel('write');	
		}
		
		$action_type = $modelParams['action'];
		if(empty($modelParams['action'])){
			$action_type = "insert";
		}
		// check for page_name set
		// check why first getting id
		if($action_type == "insert"){
			$data = array (
						"page_name"=> $modelParams['page_name'],
						"destination_url"=>$modelParams['destination_url'],
						"page_type" => "indianpage",
						"mmp_type" => "customized",
						"status" => "created",
						);
            $sql = "update marketing_page_master set  mmp_type = 'customized', `status` = 'created' where page_id = ".$this->dbHandle->escape((int)$modelParams['page_name']);
            $queryResult = $this->dbHandle->query($sql);
            //$this->dbHandle->where('page_id', (int)$modelParams['page_id']);
            //$this->dbHandle->update('marketing_page_master', $data);
			//$queryCmd = $this->dbHandle->insert_string('marketing_page_master',$data);
			//$this->dbHandle->query($queryCmd);
			//$latestPageId = $this->dbHandle->insert_id();
		    	
			/*$formPageMappingData = array (
						"page_id" => (int)$modelParams['page_name'],
						"form_id"=> (int)$modelParams['form_type_id']
						);
            */
            $formPageMappingData = array (
                        "page_id" => (int)$modelParams['page_name'],
                        "form_id"=> 1
                        );
            
			$queryCmd = $this->dbHandle->insert_string('mmp_pageid_formid_mapping',$formPageMappingData);
			$this->dbHandle->query($queryCmd);
			return $latestPageId;
		
		} else if($action_type == "update" && !empty($modelParams['page_id'])) {
				$formPageMappingData = array (
						"page_id" => (int)$modelParams['page_name'],
						"form_id"=> (int)$modelParams['form_type_id']
						);
				$this->dbHandle->where('page_id', (int)$modelParams['page_id']);
				$this->dbHandle->update('mmp_pageid_formid_mapping', $formPageMappingData);
			
				$data = array(
				   "page_name"=> $modelParams['page_name'],
				);
				$this->dbHandle->where('page_id', $modelParams['page_id']);
				$this->dbHandle->update('marketing_page_master', $data);
				return (int)$modelParams['page_id'];
		}
	}
	
	public function listCustomizedMMP() {
		if($this->dbHandle == ''){
			$this->initiateModel();	
		}
		
		$course_count_array = $this->dbHandle->query("select mpcp.page_id as page_id, count(mpcp.course_id) as count from marketing_pageid_courses_mapping as mpcp, marketing_page_master as mpm where mpm.page_id = mpcp.page_id and mpm.mmp_type='customized' group by mpcp.page_id ");
	    $course_count_result = $course_count_array->result();
		$count_array = array();
		if(!empty($course_count_result) && is_array($course_count_result)) {
			foreach($course_count_result as $row){
				$count_array[]=$row;
			}
		}
		
		$queryStr = $this->dbHandle->query("select * from mmp_pageid_formid_mapping");
		$queryRes = $queryStr->result();
		$formData = array();
		if(!empty($queryRes)){
			foreach($queryRes as $row){
				$formData[$row->page_id] = $row->form_id;
			}
		}
		
		$arrResults = $this->dbHandle->query("select * from marketing_page_master where mmp_type='customized' order by page_id desc");
		$results = $arrResults->result();
		$pageDetails = array();
		$pageDetails['mmp_details'] = array();
		
		if(!empty($results) && is_array($results)) {
			foreach($results as $row) {
				$mmp_temp_details = array();
				$mmp_temp_details['page_id'] = $row->page_id;
				$mmp_temp_details['page_url'] = $row->page_url;
				$mmp_temp_details['page_name'] = $row->page_name;
				$mmp_temp_details['destination_url'] = $row->destination_url;
				$mmp_temp_details['page_type'] = $row->page_type;
				$mmp_temp_details['status'] = $row->status;
				$pageDetails['mmp_details'][] = $mmp_temp_details;
			}
		}
		
		$pageDetails['count_list'] = json_encode($count_array);
		$pageDetails['form_list'] = json_encode($formData);
		return $pageDetails;
	}
	
	public function marketingPageDetailsById($page_id) {
		if(!($page_id > 0)) {
		    return array();
		}
		
		if($this->dbHandle == ''){
			$this->initiateModel();	
		}
		
		$arrResults = $this->dbHandle->query("select count(course_id) as count from marketing_pageid_courses_mapping where page_id=".$this->dbHandle->escape($page_id)." group by page_id");
		$count = $arrResults->result();
		$count = $count[0]->count;
		
		$queryStr = $this->dbHandle->query("select * from mmp_pageid_formid_mapping where page_id=".$this->dbHandle->escape($page_id));
		$queryRes = $queryStr->result();
		if(!empty($queryRes)){
			$form_id = $queryRes[0]->form_id;
			$formDetails = $this->getMMPFormTypes($form_id);
			$form_name = $formDetails[0]['form_name'];
			$form_description = $formDetails[0]['form_description'];
			$form_template_name = $formDetails[0]['template_name'];
		}
		
		$arrResults = $this->dbHandle->query("select * from marketing_page_master where page_id=".$this->dbHandle->escape($page_id)." and mmp_type = 'customized'");
		$results = $arrResults->result();
		$pageDetails = array();
		if(!empty($results) && is_array($results)) {
			$pageDetails['page_url'] = $results['0']->page_url;
			$pageDetails['count_courses'] = $count;
			$pageDetails['page_type'] = $results['0']->page_type;
			$pageDetails['page_name'] = $results['0']->page_name;
			$pageDetails['destination_url'] = $results['0']->destination_url;
			$pageDetails['page_id'] = $results['0']->page_id;
			$pageDetails['mmp_type'] = $results['0']->mmp_type;
			$pageDetails['status'] = $results['0']->status;
			if(!empty($form_id) && !empty($form_name) && !empty($form_description)){
				$pageDetails['form_id'] = $form_id;
				$pageDetails['form_name'] = $form_name;
				$pageDetails['form_description'] = $form_description;
				$pageDetails['template_name'] = $form_template_name;
			}
		}
		return $pageDetails;
	}
	
	public function getMMPFormTypes($form_id = NULL){
		if($this->dbHandle == ''){
			$this->initiateModel();	
		}
		$queryResults = $this->dbHandle->query("select * from mmp_forms");
		if($form_id != NULL){
			$queryResults = $this->dbHandle->query("select * from mmp_forms where form_id = ". $this->dbHandle->escape((int)$form_id));
		}
		$results = $queryResults->result();
		$returnResult = array();
		if(!empty($results) && is_array($results)) {
			foreach($results as $row) {
				$formDetails = array();		
				$formDetails['form_id'] = $row->form_id;
				$formDetails['form_name'] = $row->form_name;
				$formDetails['form_description'] = $row->form_description;
				$formDetails['template_name'] = $row->template_name;
				$returnResult[] = $formDetails;
			}
		}
		return $returnResult;
	}

    public function getNormalForms($form_id = NULL){
        if($this->dbHandle == ''){
            $this->initiateModel();
        }
        $queryResults = $this->dbHandle->query("select page_name, page_id from marketing_page_master where mmp_type='normal' and trim(page_name) != ''  and status !='history' order by page_id desc");
        $results = $queryResults->result();
        $returnResult = array();
        if(!empty($results) && is_array($results)) {
            foreach($results as $row) {
                $formDetails = array();
                $formDetails['id'] = $row->page_id;
                $formDetails['name'] = $row->page_name;
                $returnResult[] = $formDetails;
            }
        }
        return $returnResult;
    }

	
	public function updateMMPStatus($modelParams = array()) {
		if($this->dbHandle == ''){
			$this->initiateModel('write');	
		}
		$page_id = $modelParams['page_id'];
		$status = $modelParams['status'];
		$updateData = array (
			"status"=> $status
		);
		$this->dbHandle->where('page_id', (int)$page_id);
		$this->dbHandle->update('marketing_page_master', $updateData);
		return 1;
	}

	public function getCourses($formid){
                $this->initiateModel();
		$sql = "select CourseReach, courseName, course_id, b.groupid, groupname, acronym,page_id from tCourseSpecializationMapping a, marketing_pageid_courses_mapping b,cmp_coursegroupmapping c where a.SpecializationId = b.course_id and b.groupid = c.groupid and c.courseid = b.course_id and b.page_id =? GROUP BY course_id";
                $queryResult = $this->dbHandle->query($sql, array($formid));
                $results = $queryResult->result_array();
		$resultArr = array();
		$i = 0;

		foreach($results as $row) {
			$resultArr[$i]['coursename']=$row['courseName'];
			$resultArr[$i]['courseid']=$row['course_id'];
			$resultArr[$i]['groupid'] = $row['groupid'];
			$resultArr[$i]['groupname']=$row['groupname'];
			$resultArr[$i]['acronym']=$row['acronym'];
			$resultArr[$i]['reach']=$row['CourseReach'];
			$i++;
		}
		return $resultArr;
	}

	public function getPageType($formId){
                $this->initiateModel();
		$sql = "select page_type from marketing_page_master where page_id = ?";
                $queryResult = $this->dbHandle->query($sql, array($formId));
                $results = $queryResult->result_array();
		return $results[0]['page_type'];	
	}

	public function getCustomizations($formid,$id,$type){

                $this->initiateModel();
		if($type == 'abroadpage'){
			$sql = "select  distinct foreignid, type, 'NA' as name,  customization_fields, customization_rules, `condition`, `todo` from cmp_formcustomization,`marketing_pageid_courses_mapping` c where status = 'live' and type = 'abroadpage' and c.page_id = formid  and formid=".$this->dbHandle->escape($formid);			
		}
        else if($type == 'testprep'){
            $sql = " SELECT mmp.course_id as foreignid ,'course' as type, bt.acronym as name ,customization_fields, customization_rules, `condition`, `todo` FROM marketing_pageid_courses_mapping mmp INNER JOIN blogTable bt ON bt.blogId = mmp.course_id INNER JOIN blogTable btp ON btp.blogId = bt.parentId join cmp_formcustomization c WHERE mmp.page_id =".$this->dbHandle->escape($formid)." AND mmp.course_type = 'testpreppage' and c.status = 'live' and c.foreignid =  mmp.course_id AND c.formid = mmp.page_id and bt.status != 'draft'";
            $sql.= " union ";
            $sql.= " select  foreignid, 'group' as type, groupname as name,  customization_fields, customization_rules, `condition`, `todo` from cmp_formcustomization a,cmp_coursegroupmapping b , `marketing_pageid_courses_mapping` c where status = 'live' and type = 'group' and  c.page_id = formid  and a.foreignid = b.groupid and formid=".$this->dbHandle->escape($formid);
        }
		else{
			if($formid>0 && $id =='' && $type == ''){
			$sql = "select  foreignid, type, groupname as name,  customization_fields, customization_rules, `condition`, `todo` from cmp_formcustomization a,cmp_coursegroupmapping b , `marketing_pageid_courses_mapping` c where status = 'live' and type = 'group' and a.foreignid = b.groupid and c.page_id = formid and formid=".$this->dbHandle->escape($formid);
			$sql .= " union select  foreignid, type, CourseName as name,  customization_fields, customization_rules, `condition`, `todo` from cmp_formcustomization a,tCourseSpecializationMapping b ,`marketing_pageid_courses_mapping` c where a.status = 'live' and b.status='live' and type = 'course' and a.foreignid = b.SpecializationId and c.page_id = formid and c.course_id = a.foreignid and formid=".$this->dbHandle->escape($formid);
			$sql .= " union select  foreignid, type, 'abroadpage',  customization_fields, customization_rules, `condition`, `todo` from cmp_formcustomization a,`marketing_pageid_courses_mapping` c where a.status = 'live' and c.page_id = formid and type = 'abroadpage' and  formid=".$this->dbHandle->escape($formid);
			}

			else{
			$sql = "select  foreignid, type, 'NA' as name,  customization_fields, customization_rules, `condition`, `todo` from cmp_formcustomization where status = 'live' and type = ".$this->dbHandle->escape($type)."  and foreignid=".$this->dbHandle->escape($id)." and formid=".$this->dbHandle->escape($formid);
			}
		}

		error_log('******'.$sql."********");
                $queryResult = $this->dbHandle->query($sql);
                $results = $queryResult->result_array();
                $resultArr = array();
                $i = 0;
                foreach($results as $row) {
                        $resultArr[$i]['name']=$row['name'];
                        $resultArr[$i]['foreignid']=$row['foreignid'];
                        $resultArr[$i]['type'] = $row['type'];
                        $resultArr[$i]['fields']=$row['customization_fields'];
                        $resultArr[$i]['rules']=$row['customization_rules'];
			$resultArr[$i]['condition']=$row['condition'];
			$resultArr[$i]['todo']=$row['todo'];
                        $i++;
                }
                return $resultArr;
	}

	public function saveCustom($formid,$fieldJson, $ruleJson, $fId, $type, $condition, $todo){
        $this->initiateModel('write');
		$fieldJson = addslashes($fieldJson);
		$ruleJson = addslashes($ruleJson);
		
		$sql = "update cmp_formcustomization set status= 'history' where formid= ? and foreignid = ? and type=?";
        $queryResult = $this->dbHandle->query($sql, array($formid, $fId, $type));

		$sql2 = "INSERT INTO `cmp_formcustomization` (`id` ,`formid` ,`foreignid` ,`type` ,`customization_fields` ,`customization_rules` ,`condition`,`todo`,`status` ,`version`)VALUES (NULL, ".$this->dbHandle->escape($formid).",".$this->dbHandle->escape($fId).", ".$this->dbHandle->escape($type).", '$fieldJson', '$ruleJson',".$this->dbHandle->escape($condition).",".$this->dbHandle->escape($todo).", 'live', '')";
        $queryResult = $this->dbHandle->query($sql2);
		return true;
	}

	public function getDominantGroup($formId)
    {
		$this->initiateModel();
        $sql =  "SELECT cgm.groupid ".
                "FROM marketing_pageid_courses_mapping mmp ".
                "INNER JOIN cmp_coursegroupmapping cgm ON cgm.courseid = mmp.course_id ".
                "WHERE mmp.page_id = ? ".
                "GROUP BY cgm.groupid ".
                "ORDER BY count(cgm.groupid) DESC LIMIT 1";
				
        $query = $this->dbHandle->query($sql,array($formId));
        $result = $query->row_array();
        return $result['groupid'];
    }
    
    public function getGroupCustomizations($formId,$groupId)
    {
		$this->initiateModel();
        $sql =  "SELECT customization_fields,customization_rules ".
                "FROM cmp_formcustomization ".
                "WHERE formid = ? AND type = 'group' AND foreignid = ? AND status = 'live'";
        $query = $this->dbHandle->query($sql,array($formId,$groupId));
        return $query->row_array();
    }
    
    public function getCourseCustomizations($formId,$courseId)
    {
		$this->initiateModel();
        $sql =  "SELECT customization_fields,customization_rules ".
                "FROM cmp_formcustomization ".
                "WHERE formid = ? AND type = 'course' AND foreignid = ? AND status = 'live'";
        $query = $this->dbHandle->query($sql,array($formId,$courseId));
        return $query->row_array();
    }
    
    public function getStudyAbroadCustomizations($formId)
    {
		$this->initiateModel();
        $sql =  "SELECT customization_fields,customization_rules ".
                "FROM cmp_formcustomization ".
                "WHERE formid = ? AND status = 'live'";
        $query = $this->dbHandle->query($sql,array($formId));
        return $query->row_array();
    }
    
    public function getGroupData($groupId)
    {
		$this->initiateModel();
        $sql =  "SELECT groupname,acronym ".
                "FROM cmp_coursegroupmapping ".
                "WHERE groupid = ?";
        $query = $this->dbHandle->query($sql,array($groupId));
        return $query->row_array();
    }
    
    public function getGroupDataForCourse($courseId)
    {
		$this->initiateModel();
        $sql =  "SELECT groupid,groupname,acronym ".
                "FROM cmp_coursegroupmapping ".
                "WHERE courseid = ?";
        $query = $this->dbHandle->query($sql,array($courseId));
        return $query->row_array();
    }
    
    public function getMMPDetails($formId)
    {
		$this->initiateModel();
        $sql = "SELECT * FROM marketing_page_master WHERE page_id = ?";
        $query = $this->dbHandle->query($sql,array($formId));
        return $query->row_array();        
    }
    
    public function getManagementCourses($formId)
    {
		$this->initiateModel();
        $sql =  "SELECT tcsm.SpecializationId,tcsm.CourseName,tcsm.SpecializationName ".
                "FROM marketing_pageid_courses_mapping mmp ".
                "INNER JOIN tCourseSpecializationMapping tcsm ON tcsm.SpecializationId = mmp.course_id ".
                "WHERE mmp.page_id = ? ".
                "AND mmp.course_type = 'management' ".
                "ORDER BY tcsm.SpecializationId";
        
        $query = $this->dbHandle->query($sql,array($formId));
        return $query->result_array();          
    }
    
    public function getNonManagementCourses($formId)
    {
		$this->initiateModel();
        $sql =  "SELECT tcsm.*, cgsm.* ".
                "FROM marketing_pageid_courses_mapping mmp,categoryGroupSpecializationMaster cgsm, courseSpecializationGroupMapping csgm, tCourseSpecializationMapping tcsm ".
                "WHERE mmp.course_id =  tcsm.SpecializationId ".
                "AND tcsm.SpecializationId = csgm.courseSpecializationId ".
                "AND csgm.groupId = cgsm.groupId ".
                "AND cgsm.status = 'live' ".
                "AND tcsm.Status='live' ".
                "AND tcsm.scope='india' ".
                "AND mmp.page_id = ? ".
                "AND mmp.course_type = 'nonmanagement' ".
                "ORDER BY cgsm.groupName,tcsm.CourseName";
        
        $query = $this->dbHandle->query($sql,array($formId));
        return $query->result_array();        
    }
    
    public function getTestPrepCourses($formId)
    {
		$this->initiateModel();
        $sql =  "SELECT mmp.course_id,bt.acronym,btp.blogTitle ".
                "FROM marketing_pageid_courses_mapping mmp ".
                "INNER JOIN blogTable bt ON bt.blogId = mmp.course_id ".
                "INNER JOIN blogTable btp ON btp.blogId = bt.parentId ".
                "WHERE mmp.page_id = ? ".
                "AND mmp.course_type = 'testpreppage' AND bt.status != 'draft' ";
        
        $query = $this->dbHandle->query($sql,array($formId));
        return $query->result_array();          
    }
    public function getTestPrepGroupDetails($groupId)
    {
        $this->initiateModel();
        $sql = "SELECT distinct(groupid), groupname , acronym FROM `cmp_coursegroupmapping` where acronym = ?";
        $query = $this->dbHandle->query($sql, array($groupId));
        return $query->result_array();
    }

    
    public function getTestPrepCourseDetails($courseId)
    {
		$this->initiateModel();
        $sql =  "SELECT acronym,parentId ".
                "FROM blogTable ".
                "WHERE blogId = ? AND status != 'draft' ";
        
        $query = $this->dbHandle->query($sql,array($courseId));
        return $query->row_array();          
    }
    
    public function getAbroadCategories($formId)
    {
		$this->initiateModel();
        $sql =  "SELECT cbt.boardId as id,cbt.name ".
                "FROM marketing_pageid_courses_mapping mmp ".
                "INNER JOIN categoryBoardTable cbt ON cbt.boardId = mmp.course_id ".
                "WHERE mmp.page_id = ? ".
                "AND mmp.course_type = 'abroadpage' GROUP BY cbt.boardId";
        
        $query = $this->dbHandle->query($sql,array($formId));
        return $query->result_array();  
    }
 
    public function getSavedPopularCourseForAbroad($formId) {
		$sql = "SELECT course_id from marketing_pageid_courses_mapping where course_type = 'abroadpage' and page_id=?";
		$query = $this->dbHandle->query($sql,array($formId));
        return $query->result_array();
    }    

    public function getTestPrepCourseGroup($courseId)
    {
		$this->initiateModel();
        $sql =  "SELECT btp.blogTitle ".
                "FROM blogTable bt ".
                "INNER JOIN blogTable btp ON btp.blogId = bt.parentId ".
                "WHERE bt.blogId = ? ";
        
        $query = $this->dbHandle->query($sql,array($courseId));
        $result = $query->row_array();
        return $result['blogTitle'];
    }
    
    public function getTestPrepCourseCategory($courseId)
    {
		$this->initiateModel();
        $sql =  "SELECT boardId ".
                "FROM blogTable ".
                "WHERE blogId = ? ";
        
        $query = $this->dbHandle->query($sql,array($courseId));
        $result = $query->row_array();
        return $result['boardId'];
    }	
	public function getGroupDetailsByAcronym($acronym)
	{
		$this->initiateModel();
        $sql =  "SELECT groupid, groupname ".
				"FROM `cmp_coursegroupmapping` ".
				"WHERE acronym = ?";
        $query = $this->dbHandle->query($sql,array($acronym));
        return $query->row_array();	
	}
	
	public function getTestPrepMMPCourseCategories($pageId)
	{
		$this->initiateModel();
        $sql =  "SELECT DISTINCT b.parentId ".
				"FROM `marketing_pageid_courses_mapping` m ".
				"INNER JOIN blogTable b ON b.blogId = m.course_id ".	
				"WHERE page_id = ? AND b.status != 'draft' ";
        $query = $this->dbHandle->query($sql,array($pageId));
        return $this->getColumnArray($query->result_array(),'parentId');
	}
	
	public function getMMPCourseCategories($pageId)
	{
		$this->initiateModel();
        $sql =  "SELECT DISTINCT c.boardId as subcategory,c.parentId as category ".
				"FROM `marketing_pageid_courses_mapping` m ".
				"INNER JOIN LDBCoursesToSubcategoryMapping l ON l.ldbCourseID = m.course_id ".
				"INNER JOIN categoryBoardTable c ON c.boardId = l.categoryID ".
				"WHERE m.page_id = ?";
        $query = $this->dbHandle->query($sql,array($pageId));
        return $query->result_array();
	}
	
	public function getMMPMailer($pageId)
	{
		$this->initiateModel();
        $sql =  "SELECT * ".
				"FROM `marketing_page_mailer`  ".
				"WHERE page_id = ? AND status = 'live'";
        $query = $this->dbHandle->query($sql,array($pageId));
		return $query->row_array();
	}
	
	public function getMMPFormbySubCategoryId($sub_category_id, $display_on_page, $is_customized = 'Y'){
		$dbHandle = $this->getReadHandle();
		if($is_customized == 'Y') {
			$customquery = " and mpm.mmp_type = 'customized'";
		}
		$query = "SELECT mpm.page_id,mpm.display_on_page,mpm.form_heading FROM marketing_pageid_courses_mapping mm INNER JOIN LDBCoursesToSubcategoryMapping lcsm ON mm.course_id = lcsm.ldbCourseID INNER JOIN marketing_page_master mpm ON mm.page_id = mpm.page_id WHERE lcsm.categoryID = ? and mpm.display_on_page = ? and mpm.status ='live'".$customquery." order by mpm.page_id desc";		
		$resultSet = $dbHandle->query($query, array($sub_category_id, $display_on_page)); 			
		$result = $resultSet->row_array();
		
		return $result;	
	}

	public function getMMPDetailsByType($display_on_page){
		$dbHandle = $this->getReadHandle();

		$query = "SELECT * from marketing_page_master where display_on_page = ? and status = 'live' order by page_id desc";		
		$resultSet = $dbHandle->query($query, array($display_on_page)); 			
		$result = $resultSet->row_array();
		
		return $result;	
	}

	public function saveCustomization($formid, $fieldJson) {
        
        if(empty($formid)) {
        	return;
        }
        
        $this->initiateModel('write');

  		$sql = "update cmp_formcustomization set status= 'history' where formid= ?";
		$this->dbHandle->query($sql, array($formid));

		if(!empty($fieldJson)) {
	    	$sql1 = "INSERT INTO `cmp_formcustomization` (`formid` ,`foreignid` ,`type` ,`customization_fields` ,`status`) VALUES (?, ?, ?, ?, ?)";
	        $this->dbHandle->query($sql1, array($formid, 0, 'national', $fieldJson, 'live'));
    	}

    	return true;
  	}

  	public function getFormCustomizationData($formid){
		$dbHandle = $this->getReadHandle();

		$query = "SELECT customization_fields from cmp_formcustomization where formid = ? and status = 'live'";		
		$resultSet = $dbHandle->query($query, array($formid)); 			
		$result = $resultSet->row_array();
		
		return $result;	
	}

	public function getMultipleFormCustomizationData($formids, $type){
		$dbHandle = $this->getReadHandle();

		$query = "SELECT customization_fields, formid from cmp_formcustomization where formid IN (?) and type = ? and status = 'live'";		
		$resultSet = $dbHandle->query($query, array($formids,$type)); 
		$result = array();			
		foreach($resultSet->result_array() as $row) {
			$result[$row['formid']] = $row['customization_fields'];
		}
		
		return $result;	
	}
}   