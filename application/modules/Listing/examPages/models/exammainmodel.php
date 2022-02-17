<?php

class exammainmodel extends MY_Model {
	private $dbHandle = '';
    private $dbHandleMode = '';
	
	function __construct() {
		parent::__construct('Listing');
    }
    
    private function initiateModel($mode = "write"){
		if($this->dbHandle && $this->dbHandleMode == 'write') {
		    return;
		}
		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }

    public function getAllExamsByFilter($where = '', $values = array(), $start = 0, $limit = 50){
        $this->initiateModel('read');
        $sql =  "SELECT main.id as examId, main.name as examName, main.fullName, main.conductedBy,main.isRootUrl as rootUrl, main.isConductingBody, (SELECT COUNT(*) FROM exampage_groups groups
        WHERE main.id = groups.examId and groups.status  = 'live') as groupCount, (select exampage_id from exampage_master epm where epm.exam_id = main.id and status in ('live') limit 1) as exampage_id from exampage_main main where main.status = 'live' $where order by main.name";
        return $this->dbHandle->query($sql, $values)->result_array();
    }

    public function addMainExam($examData){
        $this->initiateModel();
        $this->dbHandle->trans_start();
        $examId = 0;
        if(!empty($examData)){
            $this->dbHandle->insert('exampage_main', $examData);
            $examId = $this->dbHandle->insert_id();
        }
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE){
            $this->dbHandle->trans_rollback();
        }else{
            $this->dbHandle->trans_commit();
            return $examId;
        }
        return 0;
    }

    public function addMainExamAttributes($attrData){
        $this->initiateModel();
        if(!empty($attrData)){
            $this->dbHandle->insert_batch('examAttributeMapping', $attrData);
            return true;
        }
        return false;
    }

    public function getExamList($where = '', $values = array(),$joinCondition = ''){
    	$this->initiateModel('read');
    	$sql =  "SELECT main.id as examId, main.name as examName from exampage_main main $joinCondition where 1=1 $where order by main.name";
		return $this->dbHandle->query($sql, $values)->result_array();
    }

    public function getExamListByName($examNameStr){
        if($examNameStr != ''){
            $this->initiateModel('read');
            $this->dbHandle->select('id as examId, name as examName');
            $this->dbHandle->where('status','live');
            $this->dbHandle->where_in('name', $examNameStr);
            $this->dbHandle->order_by("name", "asc");
            return $this->dbHandle->get('exampage_main')->result_array();
        }
        return array();
    }

    public function getExamListByHierarchies($filter = array()){ 
        $this->initiateModel('read'); 
        $values = array(); 
        $where = ' AND main.status = "live" AND eg.status = "live" '; 
        /*if(isset($filter['examPageExists']) && $filter['examPageExists']=='yes'){
            $where .= ' AND main.exampageId > 0 ';
        }else if(isset($filter['examPageExists']) && $filter['examPageExists'] == 'no'){
            $where .= ' AND main.exampageId = 0 ';
        }else if(isset($filter['examPageExists']) && $filter['examPageExists'] > 0){
            $where .= ' AND main.exampageId = ? ';
            $values['exampageId'] = $filter['examPageExists'];
        }*/
        $joinCondition = '';
        if(isset($filter['examPageExists']) && $filter['examPageExists']=='yes'){
            $joinCondition .= ' INNER JOIN exampage_master epm ON epm.groupId = eg.groupId';
            $where .= ' AND epm.status= "live"';
        }else if(isset($filter['examPageExists']) && $filter['examPageExists'] == 'no'){
            $joinCondition .= ' INNER JOIN exampage_master epm ON epm.groupId = eg.groupId';
            $where .= ' AND epm.status != "live"';
        }else if(isset($filter['examPageExists']) && $filter['examPageExists'] > 0){
            $joinCondition .= ' INNER JOIN exampage_master epm ON (epm.groupId = eg.groupId AND epm.exampage_id = ?)';
            $values['exampage_id'] = $filter['examPageExists'];
        }

        if(!empty($filter['hierarchyIdArr'])){ 
                $where .= ' AND eam.entityId in (?) '; 
		$values['entityId'] = $filter['hierarchyIdArr'];
                $where .= ' AND eam.entityType in ("hierarchy", "primaryHierarchy") '; 
                $where .= ' AND eam.status = "live" '; 
        } 
        $sql = 'SELECT distinct eam.examId, main.name as examName from exampage_main main join examAttributeMapping eam on main.id = eam.examId INNER JOIN exampage_groups eg ON ( eg.groupId = eam.groupId AND eg.isPrimary = 1 ) '.$joinCondition.' '.$where; 
        return $this->dbHandle->query($sql, $values)->result_array(); 
    }

    public function getExamHierarchyData($examId, $groupId){
        if(!empty($examId) && $examId > 0 && !empty($groupId)){
            $this->initiateModel('read');
            $sql = 'SELECT id, examId, entityId, entityType from examAttributeMapping where status = "live" and examId = ? and groupId = ?';
            return $this->dbHandle->query($sql, array($examId, $groupId))->result_array();
        }
        return array();
    }

    public function updateMainExam($examMainData, $editExamId){
        $this->initiateModel();
        if($editExamId > 0){
            $this->dbHandle->where('id', $editExamId);
            $this->dbHandle->update('exampage_main', $examMainData);
            return true;
        }
        return false;
    }

    public function updateMainExamAttributes($attrData, $groupId){
        $this->initiateModel('write');
        if($groupId > 0){
            $this->dbHandle->where('groupId', $groupId);
            $this->dbHandle->update('examAttributeMapping', $attrData);
            return true;
        }
        return false;
    }

    public function updateExamOrderStatus($examId){
        $this->initiateModel('write');
        if($examId > 0){
            $sql = "update exampage_order set status = 'deleted' where examId = ?";
            $this->dbHandle->query($sql, array($examId));
            return true;
        }
        return false;
    }

    public function updateOrInsertExamOrderWithHierarchy($finalData){
        $this->initiateModel('write');
        foreach($finalData as $exam) {
            $values = '?,?,?,?,?,?,"live"';

	    $streamId = $exam['streamId']?$exam['streamId']:0;
	    $subStreamId = $exam['subStreamId']?$exam['subStreamId']:0;
	    $courseId = $exam['courseId']?$exam['courseId']:0;
	    $paramArray = array($exam['examId'],$streamId,$subStreamId,$courseId,$exam['exam_order'],$exam['is_featured']);

            $sql = "Insert into exampage_order (examId,streamId,subStreamId,courseId,exam_order,is_featured,status) values(".$values.")";
            $sql .= " ON DUPLICATE KEY UPDATE status = 'live'";
            $this->dbHandle->query($sql, $paramArray);
            if($exam['streamId'] > 0 && $exam['subStreamId']  >0){
                $sql = "UPDATE exampage_order set status ='live'  where streamId = ? and subStreamId = ? and courseId = ? and examId =?";
                $streamId = $exam['streamId']?$exam['streamId']:0;
                $this->dbHandle->query($sql, array($streamId, 0,0,$exam['examId']));

            }
        }
    }

    public function getExamsBasedOnFilter($where = '', $valueArr = ''){
        $this->initiateModel('read');
        $orderBy = ' order by main.name ';
        $sql = 'SELECT map.examId, main.name from examAttributeMapping map join exampage_main main on main.id = map.examId where main.status="live" and map.status="live"'.$where.$orderBy;
        return $this->dbHandle->query($sql, array($valueArr))->result_array();
    }

    public function checkIfExamNameIsAlreadyExist($examName){
        $this->initiateModel();
        $this->dbHandle->select('id');
        $this->dbHandle->from('exampage_main');
        $this->dbHandle->where('name',$examName);
        $this->dbHandle->where('status','live');
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }
    function getAllExams(){
        $this->initiateModel('read');
        $sql =  "SELECT main.id as examId, main.name as examName, url, isRootUrl from exampage_main main where main.status = 'live' order by main.name";
        return $this->dbHandle->query($sql)->result_array();
    }

    function addExamUrl($url, $examId){
        $this->initiateModel('write');       
        $sql = "update exampage_main set url = ? where id = ? and status = 'live'";
        $this->dbHandle->query($sql,array($url,$examId));
    }

    function getDataForUrl($examId){
        if(empty($examId)){return;}
        $this->initiateModel('read');
        $sql =  "SELECT name as examName, isRootUrl, conductedBy from exampage_main
                 where id = ?  and status = 'live'";
        return $this->dbHandle->query($sql,array($examId))->result_array();
    }

    function getUrl(){
        $this->initiateModel('read');
        $sql = "SELECT name as examName, url  
                from exampage_main
                WHERE status = 'live'";
        return $this->dbHandle->query($sql)->result_array();
    }

    public function getExamDataByExamIds($examIds){
        if(!is_array($examIds) || empty($examIds)){
            return array();
        }
        $this->initiateModel('read');
        $query = "SELECT main.id as id, main.name as name, main.url as url, master.exampage_id as isExamPageActive  from exampage_main main left join exampage_groups eg ON (eg.examId = main.id AND eg.isPrimary = 1) left join exampage_master master on master.groupId = eg.groupId and main.status = 'live' and eg.status = 'live' and master.status='live' where  main.id in (?)";
        $res = $this->dbHandle->query($query,array($examIds))->result_array();
        $result = array();
        foreach($res as $row){
            $result[$row['id']] = array('name'=>$row['name'],'url'=>$row['url'],'exam_id'=>$row['id'], 'isExamPageActive' => empty($row['isExamPageActive']) ?  '0' : '1');
        }
        return $result;
    }

    public function getAllStreamsHavingExams(){
        $this->initiateModel('read');
        $query = "SELECT distinct bh.stream_id,s.name from examAttributeMapping em join base_hierarchies bh on bh.hierarchy_id = em.entityId and em.status = 'live' and bh.status = 'live' and em.entityType in ('primaryHierarchy','hierarchy') join streams s on bh.stream_id = s.stream_id and s.status='live' join exampage_groups eg on (eg.groupId = em.groupId and eg.isPrimary = 1  and eg.status='live') join exampage_master epm ON epm.groupId = eg.groupId AND epm.status='live'";
        $query = $this->dbHandle->query($query)->result_array();
        $result = array();
        foreach ($query as $row) {
            $result[$row['stream_id']] = array('id'=>$row['stream_id'],'name'=>$row['name']);
        }
        return $result;
    }

    public function getExamPagesByStream($streamId){
        if(empty($streamId)){
            return array();
        }
        $this->initiateModel('read');
        $query = "SELECT distinct main.id,master.groupId ,main.name,main.url FROM exampage_main main join exampage_master master on master.status='live' and main.status='live' and main.id = master.exam_id join examAttributeMapping map on map.examId = main.id and map.status='live' join base_hierarchies bh on bh.hierarchy_id = map.entityId and map.entityType in ('primaryHierarchy','hierarchy') and bh.stream_id = ? order by master.view_count desc";
        $query = $this->dbHandle->query($query,array($streamId))->result_array();
        return $query;
    }

    public function getStreamBySubstreamForExam($substreamId){
        $this->initiateModel('read');
        $sql = "SELECT bh.stream_id from exampage_master master join examAttributeMapping em on master.groupId = em.groupId and em.status='live' and master.status='live' join exampage_groups eg ON (eg.groupId =em.groupId  AND eg.isPrimary = 1 AND eg.status= 'live') join base_hierarchies bh on bh.hierarchy_id = em.entityId and bh.status='live' and em.entityType in ('primaryHierarchy','hierarchy') where bh.substream_id = ?";
        $query = $this->dbHandle->query($sql,array($substreamId))->row_array();
        return $query['stream_id'];
    }

    public function getExamGroupDetails($examId){
        $this->initiateModel('read');

        if(empty($examId)){
            return;
        }
        
        $sql = "SELECT eg.groupName,eg.groupId,eg.examId FROM exampage_groups eg JOIN exampage_master em ON (em.groupId = eg.groupId) WHERE em.status = 'live' AND eg.examId = ? AND eg.status='live' Order By groupName;";

        $result = $this->dbHandle->query($sql,array($examId))->result_array();

        return $result;
    }

    public function insertExamUpdateDetails($data){
        $this->initiateModel('write');

        $count = 0;


        foreach($data['examUpdate'] as $updateText){
             $insertData[$count]['update_text'] = $updateText;
             $insertData[$count]['announce_url'] = !empty($data['examUpdateUrl'][$count])? $data['examUpdateUrl'][$count] : "";
             $insertData[$count]['isMailSent'] = $data['isMailSent'];
             $insertData[$count]['status'] = 'live';
             $insertData[$count]['creation_date'] = date('Y-m-d H:i:s');
             $count++;
        }

        if(!empty($insertData)){
            $count = count($insertData);
            $result = $this->dbHandle->insert_batch('exampage_updates',$insertData);
            $first_Id = $this->dbHandle->insert_id();
            $last_Id = $first_Id + ($count-1);
        }
 
        $insertData = array();

        $j = 0;
        if($count>0){
            foreach($data['groupIds'] as $id){
                for($i = $first_Id;$i<=$last_Id;$i++){
                    $insertData[$j][$i]['exam_id'] = $data['examId'];
                    $insertData[$j][$i]['group_id'] = $id;
                    $insertData[$j][$i]['update_id'] = $i;
                    $insertData[$j][$i]['status'] = 'live';
                    $insertData[$j][$i]['creation_date'] = date('Y-m-d H:i:s');
                }
                $j++;
            }
        }else{
            return false;
        }
               
        if(!empty($insertData)){
            foreach($insertData as $key=>$val){
                $this->dbHandle->insert_batch('exampage_updates_mapping',$val);
            }
            for($i = $first_Id;$i<=$last_Id;$i++){
                $response['update_ids'][] = $i;
            }
        }
        $response['isMailSent'] = $data['isMailSent'];
        
        return $response;
               
    }
	
	public function getAllExamsByStreamFilter($hierarchyIds){
        $this->initiateModel('read');
        $sql =  "SELECT main.id as examId, main.name as examName, (SELECT COUNT(*) FROM exampage_groups groups
        WHERE main.id = groups.examId and groups.status  = 'live') as groupCount,(select exampage_id from exampage_master epm where epm.exam_id = main.id and status in ('live') limit 1) as exampage_id from exampage_main main, examAttributeMapping eam, exampage_groups eg where main.id = eam.examId and main.id = eg.examId and eam.entityId in ($hierarchyIds) and eam.entityType in ('hierarchy', 'primaryHierarchy') and main.status = 'live'  and eam.status = 'live' group by main.id order by main.name ";
        return $this->dbHandle->query($sql)->result_array();       
    }

    public function getExamUpdatesData($examId){
        $this->initiateModel('read');
        if($examId>0){
             $examFilter = "eum.exam_id = '$examId ' AND";
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS eu.id as update_id, eu.update_text, eu.announce_url, eu.creation_date, em.id as examId, em.name as examName,  eg.groupName 
                FROM exampage_updates eu JOIN exampage_updates_mapping eum ON (eu.id = eum.update_id ) JOIN exampage_groups eg ON (eg.groupId = eum.group_id) JOIN exampage_main em ON (em.id = eum.exam_id)
                WHERE $examFilter eu.status='live' AND eum.status='live' AND eg.status='live' AND em.status='live' order by eu.creation_date desc";
        $result = $this->dbHandle->query($sql,array($examId))->result_array();

        $update = array();
        foreach($result as $key=>$val){
            $rowData[$val['update_id']]['u_id'] = $val['update_id'];
            $rowData[$val['update_id']]['update_text'] = htmlentities($val['update_text']);
            $rowData[$val['update_id']]['announce_url'] = $val['announce_url'];
            $rowData[$val['update_id']]['publishedDate'] = date_format(date_create($val['creation_date']),"d M Y");
            $rowData[$val['update_id']]['groups'][] = htmlentities($val['groupName']);
            $rowData[$val['update_id']]['examId'] = $val['examId'];
            $rowData[$val['update_id']]['examName'] = $val['examName'];
        }
        $resultArray['details'] = $rowData;

        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $this->dbHandle->query($queryCmdTotal);
        $queryResults = $queryTotal->result();        
        $totalRows = $queryResults[0]->totalRows;
        $resultArray["totalUpdates"] = $totalRows;
        return $resultArray;
    }

	public function deleteExamUpdates($updateId){
        $tables = array(
                        'exampage_updates' => array('id'),
                        'exampage_updates_mapping' => array('update_id')
                    );

        $this->initiateModel('write');
        foreach($tables as $tableName => $tableColumns) {
            $updateIdColumnName = $tableColumns[0];
            $sql =  "UPDATE $tableName 
                 SET status = 'deleted' WHERE ".$updateIdColumnName."= ?";
            $this->dbHandle->query($sql,array($updateId));
        }

        return true;
               
    }

    function checkIfGroupNameIsAlreadyExist($examId, $groupName){
        $this->initiateModel('read');
        $sql = "SELECT grp.groupId from exampage_groups grp 
                Where examId = ? AND groupName = ? AND status = 'live'";
        return $this->dbHandle->query($sql,array($examId, $groupName))->result_array();
    }

    function getAllGroupsByFilter($AND, $value){
        $this->initiateModel('read');
        $sql =  "SELECT grp.groupId, grp.groupName, grp.examId, grp.isPrimary, main.name as examName, 
(select attr.entityId from examAttributeMapping attr where attr.groupId = grp.groupId and entityType = 'year' and attr.status='live' limit 1) as year from exampage_groups grp 
join exampage_main main on main.id = grp.examId  where grp.status = 'live' and main.status = 'live' $AND order by grp.groupName";
        return $this->dbHandle->query($sql, $value)->result_array();
    }

    function addExamGroup($groupData){
        $this->initiateModel('write');
        if(!empty($groupData)){
            $this->dbHandle->insert('exampage_groups', $groupData);
            return $this->dbHandle->insert_id();
        }
        return 0;
    }

    function updateExamGroup($groupData, $groupId){
        if(empty($groupId)){return;}
        $this->initiateModel('write');
        $this->dbHandle->where(array('groupId'=>$groupId,'status'=>'live'));
        $this->dbHandle->update('exampage_groups', $groupData);
        return $this->dbHandle->affected_rows();
    }

    function updateExamGuide($entityId,$entityType='group'){
        if(empty($entityId)){return;}
        $this->initiateModel('write');
        $columnName = ($entityType == 'group')?'group_id':'exam_id';
       
        $this->dbHandle->where(array($columnName=>$entityId,'status'=>'live'));
        $this->dbHandle->update('exampage_guide', array('status'=>'history'));
        return $this->dbHandle->affected_rows();
    }

    function removeGroups($groupId, $examId){
        if(empty($groupId) || empty($examId)){
            return;
        }
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
        
        $this->dbHandle->where(array('groupId'=>$groupId,'isPrimary'=>'0'));
        $this->dbHandle->update('exampage_groups', array('status'=>'deleted'));
        
        if($this->dbHandle->affected_rows()>0){
            $this->dbHandle->where('groupId', $groupId);
            $this->dbHandle->update('examAttributeMapping', array('status'=>'deleted'));
            
            $pageId = $this->getPageIdByGroup($groupId, $examId);

            $this->dbHandle->where('groupId', $groupId);
            $this->dbHandle->update('exampage_master', array('status'=>'deleted'));

            if(!empty($pageId)){
                $this->dbHandle->where_in('page_id', $pageId);
                $this->dbHandle->update('exampage_content_table', array('status'=>'deleted'));

                $this->dbHandle->where_in('page_id', $pageId);
                $this->dbHandle->update('exampage_content_dates', array('status'=>'deleted'));

                $this->dbHandle->where_in('page_id', $pageId);
                $this->dbHandle->update('exampage_content_files', array('status'=>'deleted'));

                $this->dbHandle->where_in('page_id', $pageId);
                $this->dbHandle->update('exampage_section_order', array('status'=>'deleted'));

                $this->dbHandle->where_in('page_id', $pageId);
                $this->dbHandle->update('exampage_amp_content_table', array('status'=>'deleted'));
            }
        }

        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            # Something went wrong.
            $this->dbHandle->trans_rollback();
            return FALSE;
        }else{
            # Committing data to the database.
            $this->dbHandle->trans_commit();
            return TRUE;
        }
    }

    function getPageIdByGroup($groupId, $examId){
        if(empty($groupId) || empty($examId)){
            return;
        }
        $this->initiateModel('read');
        $sql = "SELECT exampage_id as page_id FROM exampage_master WHERE exam_id = ? AND groupId = ? and status in ('draft','live')";
        $res = $this->dbHandle->query($sql, array($examId, $groupId))->result_array();
        foreach ($res as $key => $value) {
            $pageId[] = $value['page_id'];
        }
        return (count($pageId)>0) ? $pageId : '';
    }

    function checkPrimaryOnExam($examId){
        if(empty($examId)){return;}
        $this->initiateModel('read');
        $sql = "SELECT groupId from exampage_groups 
                Where examId = ? AND isPrimary = '1' AND status = 'live' limit 1";
        $res = $this->dbHandle->query($sql,array($examId))->result_array();
        return !empty($res[0]['groupId']) ? 1 : 0;
    }

    function updatePrimaryGroup($examId, $groupId){
        if(empty($examId) || empty($groupId)){
            return;
        }
        $this->initiateModel('write');
        $this->dbHandle->where('groupId !=', $groupId);
        $this->dbHandle->where(array('examId'=>$examId,'status'=>'live'));
        $this->dbHandle->update('exampage_groups', array('isPrimary'=>'0'));
    }
    function getPageContentCreatedOnGroup($examId)
    {
        if(empty($examId))
            return;

        $this->initiateModel('read');
        $sql = "SELECT groupId,exampage_id FROM exampage_master WHERE exam_id = ? AND status in ('live','draft')";
        $rs = $this->dbHandle->query($sql,array($examId))->result_array();
        $result = array();
        foreach ($rs as $key => $value) {
            if(!empty($value['exampage_id']))
            {
                $result[$value['groupId']] = $value['exampage_id'];
            }
        }
        return $result;
    }

    function addRedirectForOldToNewExam($examId,$oldName,$newName){
        $this->initiateModel('write');
        $oldNames = array();
        $this->dbHandle->select('oldName');
        $this->dbHandle->where(array('examId'=>$examId,'status'=>'live'));
        $query = $this->dbHandle->get('exampage_redirects');
        if($query->num_rows() > 0){
            $oldNames = $this->getColumnArray($query->result_array(),'oldName');
        }

        // mark all previous entries as history
        $this->dbHandle->where(array('examId'=>$examId));
        $this->dbHandle->update('exampage_redirects',array('status'=>'history'));

        $oldNames = array_unique($oldNames);
        foreach($oldNames as $name){
            if($name != strtolower($newName)){
                $data[] = array('examId' => $examId,'oldName' => $name, 'newName' => strtolower($newName), 'status' => 'live');
            }
        }
        if(!in_array($oldName, $oldNames)){
            $data[] = array('examId' => $examId,'oldName' => strtolower($oldName), 'newName' => strtolower($newName), 'status' => 'live');
        }
        $this->dbHandle->insert_batch('exampage_redirects',$data);
    }




   function getFeaturedInstData($params){
        
        $this->initiateModel('read');
        $whereClause = '';
        $param = array();
        $rs=array();

        if(!empty($params['idsToExclude'])){
            $excludeIds = explode(',',$params['idsToExclude']);
            $whereClause = "AND efc.id in (?)";
            $action = 'editExam';
            $param = array($excludeIds);
        }elseif(!empty($params['examId'])){
            $whereClause = ' AND efc.orig_exam_id = ?';
            $param = array($params['examId']);
        }

        $sql = "SELECT efc.id, em.id as examId, em.name as examName, eg.groupId, eg.groupName, efc.start_date, efc.end_date, sc.name as courseName, si.name as instName, efc.dest_listing_id as instituteId, efc.orig_exam_id as examId, efc.dest_course_id as courseId, efc.CTA_text, efc.redirection_url  FROM exampage_featured_college efc, exampage_main em, exampage_groups eg, shiksha_courses sc, shiksha_institutes si WHERE em.id = efc.orig_exam_id and eg.examId = efc.orig_exam_id and efc.orig_group_id = eg.groupId and si.listing_id = efc.dest_listing_id and sc.course_id = efc.dest_course_id and efc.status = 'live' and eg.status = 'live' and em.status = 'live' and si.status = 'live' and sc.status = 'live'".$whereClause." ORDER BY efc.end_date";
        
        $rs = $this->dbHandle->query($sql, $param)->result_array();
       
        if($action == 'editExam'){
            foreach($rs as $key=>$val){
               $result[$val['examId']]['groups'][$val['groupId']] = htmlentities($val['groupName']);
               $result[$val['examId']]['examId'] = $val['examId'];
               $result[$val['examId']]['examName'] = htmlentities($val['examName']); 
               $result[$val['examId']]['start_date'] = $val['start_date']; 
               $result[$val['examId']]['end_date'] = $val['end_date']; 
               $result[$val['examId']]['courseName'] = htmlentities($val['courseName']); 
               $result[$val['examId']]['CTA_text'] = htmlentities($val['CTA_text']); 
               $result[$val['examId']]['redirection_url'] = $val['redirection_url']; 
               $result[$val['examId']]['instName'] = htmlentities($val['instName']); 
               $result[$val['examId']]['instituteId'] = $val['instituteId']; 
               $result[$val['examId']]['courseId'] = $val['courseId'];                
            }
            $rs = $result;
        }
        return $rs;  
    }

    function getFeaturedExamData($params){
        
        $this->initiateModel('read');
        $whereClause = '';
        $param = array();
        $rs=array();

        if(!empty($params['idsToExclude'])){           
            $excludeIds = explode(',',$params['idsToExclude']);
            $whereClause = "AND efe.id in (?)";
            $action = 'editExam';
            $param = array($excludeIds);
        }elseif(!empty($params['examId'])){
            $whereClause = ' AND efe.orig_exam_id = ?';
            $param = array($params['examId']);
        }

        $sql = "SELECT efe.id, efe.orig_exam_id, em.name as orig_exam_name, efe.dest_exam_id, em1.name as dest_exam_name, efe.orig_group_id, eg.groupName as orig_group_name, efe.dest_group_id, eg1.groupName as dest_group_name, efe.start_date, efe.end_date, efe.CTA_text, efe.redirection_url FROM exampage_featured_exam efe, exampage_main em, exampage_groups eg, exampage_main em1,exampage_groups eg1 WHERE em.id = efe.orig_exam_id and eg.examId = efe.orig_exam_id and efe.orig_group_id = eg.groupId and em1.id = efe.dest_exam_id and eg1.examId = efe.dest_exam_id and efe.dest_group_id = eg1.groupId and efe.status = 'live' and eg.status = 'live' and em.status = 'live' and em1.status = 'live' and eg1.status = 'live'".$whereClause." ORDER BY efe.end_date";

        $rs = $this->dbHandle->query($sql,$param)->result_array();

        if($action == 'editExam'){
            foreach($rs as $key=>$val){
               $result[$val['orig_exam_id']]['groups'][$val['orig_group_id']] = htmlentities($val['orig_group_name']);
               $result[$val['orig_exam_id']]['examId'] = $val['orig_exam_id'];
               $result[$val['orig_exam_id']]['examName'] = htmlentities($val['orig_exam_name']); 
               $result[$val['orig_exam_id']]['start_date'] = $val['start_date']; 
               $result[$val['orig_exam_id']]['end_date'] = $val['end_date']; 
               $result[$val['orig_exam_id']]['dest_examId'] = $val['dest_exam_id']; 
               $result[$val['orig_exam_id']]['dest_examName'] = htmlentities($val['dest_exam_name']); 
               $result[$val['orig_exam_id']]['CTA_text'] = htmlentities($val['CTA_text']); 
               $result[$val['orig_exam_id']]['redirection_url'] = $val['redirection_url']; 
               $result[$val['orig_exam_id']]['dest_groupId'] = $val['dest_group_id']; 
               $result[$val['orig_exam_id']]['dest_groupName'] = htmlentities($val['dest_group_name']);              
            }
            $rs = $result;
        }

        return $rs;
    }

    function getGroupName($examGroupArr){
        if(empty($examGroupArr)){
            return;
        }
        else{
            $groupIds = implode(',', $examGroupArr);
        }
        $this->initiateModel('read');
        $sql = "SELECT groupId, groupName FROM exampage_groups where groupId in ($groupIds) and status = 'live'";
        $rs = $this->dbHandle->query($sql)->result_array();
        foreach ($rs as $key => $value) {
            $result[$value['groupId']] = $value['groupName'];
        }
        return $result;
    }

    function getExamName($examIdArr){
        if(empty($examIdArr)){
            return;
        }
        else{
            $examIds = implode(',', $examIdArr);
        }
        $this->initiateModel('read');
        $sql = "SELECT id, name FROM exampage_main where id in ($examIds) and status = 'live'";
        $rs = $this->dbHandle->query($sql)->result_array();
        foreach ($rs as $key => $value) {
            $result[$value['id']] = $value['name'];
        }
        return $result;
    }

	public function insertFeaturedCollegeDetails($data){
        $this->initiateModel('write');
        $idsToExclude = explode(',',$data['idsToExclude']);
        if($data['mode'] == 'edit' && (!empty($idsToExclude))) {
            $exludeSelfIds = "AND id not in (?)";
            $includeSelfIds = "id in (?)";
        }
        $sql = "SELECT count(*) as count from exampage_featured_college WHERE orig_exam_id =? AND dest_course_id=? AND dest_listing_id = ? AND status ='live' and orig_group_id in (?) $exludeSelfIds";
        $rs = $this->dbHandle->query($sql,array($data['examId'], $data['dest_id'], $data['dest_parent_id'],$data['groupIds'],$idsToExclude))->row()->count;
        if($rs > 0){
            return '-1';
        }  
        if($data['mode'] == 'edit'){
            $sql = "UPDATE exampage_featured_college SET status='history' WHERE $includeSelfIds AND status ='live' ";
            $rs = $this->dbHandle->query($sql,array($idsToExclude));
        }

        $insertData = array();
        $i = 0;
        foreach($data['groupIds'] as $id){
            $insertData[$i]['orig_exam_id'] = $data['examId'];
            $insertData[$i]['orig_group_id'] = $id;
            $insertData[$i]['dest_listing_id'] = $data['dest_parent_id'];
            $insertData[$i]['dest_course_id'] = $data['dest_id'];
            $insertData[$i]['CTA_text'] = $data['CTA_text'];
            $insertData[$i]['redirection_url'] = $data['redirection_url'];
            $insertData[$i]['start_date'] = $data['from_date'];
            $insertData[$i]['end_date'] = $data['to_date'];
            $insertData[$i]['creation_date'] = date('Y-m-d H:i:s');
            $i++;
        }
            
        $result = array();   
        if(!empty($insertData)){ 
            $result = $this->dbHandle->insert_batch('exampage_featured_college',$insertData);
        }
        return $result;            
    }

    public function insertFeaturedExamsDetails($data){
        $this->initiateModel('write');
        $idsToExclude = explode(',',$data['idsToExclude']);
        if($data['mode'] == 'edit' && (!empty($idsToExclude))) {
            $exludeSelfIds = "AND id not in (?)";
            $includeSelfIds = "id in (?)";
        }
        $sql = "SELECT count(*) as count from exampage_featured_exam WHERE orig_exam_id =? AND dest_exam_id = ? AND dest_group_id =? AND status ='live' and orig_group_id in (?) $exludeSelfIds ";
        $rs = $this->dbHandle->query($sql,array($data['examId'], $data['dest_parent_id'], $data['dest_id'], $data['groupIds'],$idsToExclude))->row()->count;
        if($rs > 0){
            return '-1';
        }
        if($data['mode'] == 'edit'){
            $sql = "UPDATE exampage_featured_exam SET status='history' WHERE $includeSelfIds AND status ='live' ";
            $rs = $this->dbHandle->query($sql, array($idsToExclude));
        }
        
        $insertData = array();
        $i = 0;
        foreach($data['groupIds'] as $id){
            $insertData[$i]['orig_exam_id'] = $data['examId'];
            $insertData[$i]['orig_group_id'] = $id;
            $insertData[$i]['dest_exam_id'] = $data['dest_parent_id'];
            $insertData[$i]['dest_group_id'] = $data['dest_id'];
            $insertData[$i]['CTA_text'] = $data['CTA_text'];
            $insertData[$i]['redirection_url'] = $data['redirection_url'];
            $insertData[$i]['start_date'] = $data['from_date'];
            $insertData[$i]['end_date'] = $data['to_date'];
            $insertData[$i]['creation_date'] = date('Y-m-d H:i:s');
            $i++;
        }
            
        $result = array();   
        if(!empty($insertData)){ 
            $result = $this->dbHandle->insert_batch('exampage_featured_exam',$insertData);
        }
        return $result;            
    }

    function deleteFeaturedContent($data){
        $this->initiateModel('write');
        $dest_column_name = $data['dest_column_name'];
        $tableName = $data['tableName'];
        $sql = "UPDATE $tableName 
                SET status = 'deleted' 
                WHERE orig_exam_id = ? AND $dest_column_name= ? AND start_date = ? AND end_date = ? AND status = 'live'";

        $result = $this->dbHandle->query($sql,array($data['orig_exam_id'],$data['dest_id'],$data['startDate'],$data['endDate']));

        return $result;
    }

    public function insertExamCDLinks($data){
        $this->initiateModel('write');

        $count = 0;

        if($data['mode'] == 'edit' && $data['editId']>0){
            $this->deleteExamFeaturedLinks($data['editId'], 'history');
        }

        if(!empty($data['insertData'])){
            $count = count($data['insertData']);
            $result = $this->dbHandle->insert_batch('exampage_featured_cd_links',$data['insertData']);
            $first_Id = $this->dbHandle->insert_id();
            $last_Id = $first_Id + ($count-1);
        }
 
        $insertData = array();

        $j = 0;
        if($count>0){
            foreach($data['groupIds'] as $id){
                for($i = $first_Id;$i<=$last_Id;$i++){
                    $insertData[$j][$i]['exam_id'] = $data['examId'];
                    $insertData[$j][$i]['stream_id'] = $data['streamId'];
                    $insertData[$j][$i]['group_id'] = $id;
                    $insertData[$j][$i]['link_id'] = $i;
                    $insertData[$j][$i]['status'] = 'live';
                    $insertData[$j][$i]['creation_date'] = date('Y-m-d H:i:s');
                }
                $j++;
            }
        }else{
            return false;
        }
               
        if(!empty($insertData)){
            foreach($insertData as $key=>$val){
                $result = $this->dbHandle->insert_batch('exampage_cd_links_mapping',$val);
            }
            
        }
        
        return $result;            
    }

    public function getFeaturedCDLinks($params){
        $this->initiateModel('read');
        if($params['examId']>0){
            $examId = $params['examId'];
            $examFilter = "clm.exam_id = '$examId' AND";
        }
	$orderQuery = '';
        if($params['eventEndDateOrder']!=''){
            $orderQuery = $params['eventEndDateOrder'];
        }
        if($params['link_id']>0){
            $linkId = $params['link_id'];
            $idFilter = " AND clm.link_id = '$linkId'";
        }

        $sql = "SELECT fcl.id as link_id, fcl.campaign_name, fcl.heading, fcl.body, fcl.CTA_text, fcl.redirection_url, fcl.start_date, fcl.end_date,clm.stream_id, em.id as examId, em.name as examName,  eg.groupName, eg.groupId, epm.view_count as viewCount, fcl.clickCount as clickCount
                FROM exampage_featured_cd_links fcl JOIN exampage_cd_links_mapping clm ON (fcl.id = clm.link_id ) JOIN exampage_groups eg ON (eg.groupId = clm.group_id) JOIN exampage_main em ON (em.id = clm.exam_id) JOIN exampage_master epm ON (clm.exam_id = epm.exam_id AND clm.group_id = epm.groupId AND epm.status = 'live')
                WHERE $examFilter fcl.status='live' AND clm.status='live' AND eg.status='live' AND em.status='live' $idFilter order by fcl.end_date $orderQuery";
        
        $result = $this->dbHandle->query($sql,array($examId))->result_array();

        $rowData = array();
        foreach($result as $key=>$val){
            $rowData[$val['link_id']]['l_id'] = $val['link_id'];
            $rowData[$val['link_id']]['stream_id'] = $val['stream_id'];
            $rowData[$val['link_id']]['campaign_name'] = htmlentities($val['campaign_name']);
            $rowData[$val['link_id']]['heading'] = htmlentities($val['heading']);
            $rowData[$val['link_id']]['start_date'] = date_format(date_create($val['start_date']),"d M Y");
            $rowData[$val['link_id']]['end_date'] = date_format(date_create($val['end_date']),"d M Y");
            $rowData[$val['link_id']]['groupsName'][] = htmlentities($val['groupName']);
            $rowData[$val['link_id']]['groups'][$val['groupId']] =htmlentities($val['groupName']);
            $rowData[$val['link_id']]['examId'] = $val['examId'];
            $rowData[$val['link_id']]['examName'] = htmlentities($val['examName']);
            $rowData[$val['link_id']]['body'] = base64_encode($val['body']);
            $rowData[$val['link_id']]['CTA_text'] = htmlentities($val['CTA_text']);
            $rowData[$val['link_id']]['redirection_url'] = $val['redirection_url'];
            $rowData[$val['link_id']]['viewCount'] += $val['viewCount'];
            $rowData[$val['link_id']]['clickCount'] += $val['clickCount'];
        }
        $resultArray['details'] = $rowData;
         $i = 0;
        foreach($resultArray['details'] as $id=>$val){
            $resultArray['details'][$id]['groupCount'] = count($resultArray['details'][$id]['groupsName']);
            $resultArray['details'][$id]['fGroupName'] = $val['groupsName'][0];
            $resultArray['details'][$id]['groupsNameString'] = implode(', ',$val['groupsName']);
            if($examId>0 && $i==0){
                $resultArray['displayExam'] = addslashes($val['examName']);
            }
            $i++;  
        }    

        return $resultArray;
    }

    public function deleteExamFeaturedLinks($linkId,$status){
        $tables = array(
                        'exampage_featured_cd_links' => array('id'),
                        'exampage_cd_links_mapping' => array('link_id')
                    );

        $this->initiateModel('write');
        foreach($tables as $tableName => $tableColumns) {
            $updateIdColumnName = $tableColumns[0];
            $sql =  "UPDATE $tableName 
                 SET status = ? WHERE ".$updateIdColumnName."= ? AND status='live'";
            $this->dbHandle->query($sql,array($status, $linkId));
        }

        return true;
               
    }
    
	  function getExamWithExamPagesByBaseCourses($baseCourseIds) {
        if(empty($baseCourseIds)) {
            return;
        }
        $this->initiateModel('read');

        $sql = "SELECT eg.examId as id FROM examAttributeMapping ea ".
               "INNER JOIN exampage_groups eg ON eg.groupId = ea.groupId and eg.status = 'live' and eg.isPrimary = 1".
               " INNER JOIN exampage_master epm ON epm.groupId = eg.groupId ".
               " WHERE ea.status = 'live' and ea.entityType = 'course' and epm.status = 'live' and ea.entityId IN (?) ";

        $result = $this->dbHandle->query($sql, array($baseCourseIds))->result_array();
        foreach ($result as $key => $value) {
            $examIds[] = $value['id'];
        }

        return $examIds;
    }

}
