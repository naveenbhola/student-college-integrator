<?php

class exammodel extends MY_Model {
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

    /*
	 * Get consolidated info for exams
	 */
	public function getData($examId) {
		$data = $this->getMultipleData(array($examId));
		return $data;
	}

	public function getMultipleData($examIds){
		$this->initiateModel('read');   // initiate DB Handle
		$data['examBasicInfo'] = $this->_getExamBasicInfo($examIds);
		return $data;
	}	

	private function _getExamBasicInfo($examIds) {
		   // initiate DB Handle
		$res =array();
	        if(empty($examIds)){
        	        return $res;
        	}
		$this->initiateModel('read');
		$this->dbHandle->select('distinct(epm.id), epm.name, epm.fullName, epm.conductedBy, epm.url, epm.status, epm.isRootUrl, epm.creationDate, epg.groupId, epg.groupName, epg.isPrimary');
		$this->dbHandle->from('exampage_main epm');
		$this->dbHandle->join('exampage_groups epg','epm.id=epg.examId','INNER');
		$this->dbHandle->join('exampage_master em','em.groupId=epg.groupId','INNER');
		$this->dbHandle->where_in('epm.id', $examIds);
		$this->dbHandle->where('epm.status','live');
		$this->dbHandle->where('epg.status','live');
		$this->dbHandle->where('em.status','live');		
		$this->dbHandle->order_by("epg.isPrimary", "desc");
		$result   = $this->dbHandle->get()->result_array();	
		return $result;
	}

	public function getExamPageSeo($examId, $sectioName){
		$this->initiateModel('read');
		$sql = "SELECT metaTitle, metaDescription, h1Tag from exampage_seo_details where exam_id = ? and status='live' and sectionName = ?";
		return $this->dbHandle->query($sql,array($examId,$sectioName))->row_array();
	}

	public function getGroupData($groupId){
		$this->initiateModel('read');   // initiate DB Handle
		$result = $this->getMultipleGroupData(array($groupId));
		return $result;
	}

	public function getMultipleGroupData($groupIds){
		$this->initiateModel('read');   // initiate DB Handle
	    	$this->dbHandle->select('epg.groupId, epg.groupName, epg.isPrimary, epg.examId, epg.status, epg.creationDate, epg.lastModifiedDate');
	    	$this->dbHandle->from('exampage_groups epg');
	    	$this->dbHandle->join('exampage_master epm','epm.groupId = epg.groupId');
	    	$this->dbHandle->where_in('epg.groupId', $groupIds);
	    	$this->dbHandle->where('epg.status','live');		
	    	$this->dbHandle->where('epm.status','live');		
	    	$result   = $this->dbHandle->get()->result_array();
		return $result;
	}


	public function getMappingData($groupId){
		$this->initiateModel('read');   // initiate DB Handle
		$sql =  "select eam.entityId, eam.entityType from exampage_groups epg, examAttributeMapping eam
		    where eam.groupId = epg.groupId and epg.status=? and eam.status=? and epg.groupId=? order by epg.isPrimary desc";
		$result = $this->dbHandle->query($sql, array('live', 'live', $groupId))->result_array();
		return $result;
	}

	public function getExamContent($groupId, $ampFlag){
		if($ampFlag){
			$result['fullContent'] 		      = $this->_getMainContentForAMP($groupId);
		}else{
			$result['fullContent'] 		      = $this->_getMainContent($groupId);	
		}
		$result['fullContent']['dateContent'] = $this->_getDateContent($groupId);
		$result['fullContent']['fileContent'] = $this->_getFileContent($groupId);
		return $result['fullContent'];
	}

	public function getSectionOrder($groupId){
        $this->initiateModel('read');
        $sql      = "select distinct eso.section_name, eso.section_order from exampage_master epm
                        JOIN exampage_section_order eso on (eso.page_id = epm.exampage_id and eso.status=?)
                        where epm.groupId = ? and epm.status=? order by eso.section_order asc";
        $result   = $this->dbHandle->query($sql, array('live', $groupId, 'live'))->result_array();
        $finalArr = array();
        foreach ($result as $key => $value) {
                        $finalArr[] = $value['section_name'];
        }
        return $finalArr;
    }

	private function _getMainContentForAMP($groupId){
		$this->initiateModel('read');
		$sql      = "select distinct ect.* from exampage_master epm
		                JOIN exampage_amp_content_table ect on (ect.page_id = epm.exampage_id and ect.status=?)
		                where epm.groupId = ? and epm.status=?";
		$result   = $this->dbHandle->query($sql, array('live', $groupId, 'live'))->result_array();
		$finalArr = array();

        $tocArray = $this->_getToCContentForAMPFromNonAmpTable($groupId);

		foreach ($result as $key => $value) {
                $value['toc_text'] = $tocArray[$value['section_name']][$value['entity_type']];
		        $finalArr[$value['section_name']][] = $value;
		}
		return $finalArr;
       }

    private function _getToCContentForAMPFromNonAmpTable($groupId){
        $this->initiateModel('read');
        $sql      = "select distinct ect.section_name as sectionName,ect.entity_type as entityType,ect.toc_text as tocText from exampage_master epm
                        JOIN exampage_content_table ect on (ect.page_id = epm.exampage_id and ect.status=?)
                        where epm.groupId = ? and epm.status=?";
        $result   = $this->dbHandle->query($sql, array('live', $groupId, 'live'))->result_array();
        $finalArr = array();
        foreach ($result as $key => $value) {
                        $finalArr[$value['sectionName']][$value['entityType']] = $value['tocText'];
        }
        return $finalArr;                        
    }

     private function _getMainContent($groupId){
                $this->initiateModel('read');
                $sql      = "select distinct ect.* from exampage_master epm
                                JOIN exampage_content_table ect on (ect.page_id = epm.exampage_id and ect.status=?)
                                where epm.groupId = ? and epm.status=?";
                $result   = $this->dbHandle->query($sql, array('live', $groupId, 'live'))->result_array();
                $finalArr = array();
                foreach ($result as $key => $value) {
                                $finalArr[$value['section_name']][] = $value;
                }
                return $finalArr;
        }
	private function _getDateContent($groupId){
		$this->initiateModel('read');
		$sql =  "select ecd.* from exampage_master epm
			LEFT JOIN exampage_content_dates ecd on (ecd.page_id = epm.exampage_id and ecd.status=?) 
		   	where epm.groupId = ? and epm.status=? order by ecd.date_order asc";
		$result = $this->dbHandle->query($sql, array('live', $groupId, 'live'))->result_array();
		return $result;
	}

	private function _getFileContent($groupId){
		$this->initiateModel('read');
		$sql =  "select ecf.* from exampage_master epm
			LEFT JOIN exampage_content_files ecf on (ecf.page_id = epm.exampage_id and ecf.status=?)
		   	where epm.groupId = ? and epm.status=? order by file_order desc";
		$result = $this->dbHandle->query($sql, array('live', $groupId, 'live'))->result_array();
		return $result;
	}

	public function getFeaturedCollegeData($examId, $groupId){
        	$this->initiateModel('read');
	        $this->dbHandle->select('efc.orig_exam_id, efc.dest_listing_id, efc.dest_course_id, efc.CTA_text, efc.redirection_url');
        	$this->dbHandle->from('exampage_featured_college efc');
	        $this->dbHandle->where(array('efc.orig_group_id'    => $groupId,
                                'date(efc.start_date) <='   => date('Y-m-d'),
                                'date(efc.end_date)   >='   => date('Y-m-d'),
                                'efc.orig_exam_id'          => $examId,
                                'efc.status'                =>'live'
                            ));
		$this->dbHandle->order_by("efc.end_date", "asc");
	        $result   = $this->dbHandle->get()->result_array();
        	return $result;
    	}

	public function getContentDeliveryData($examId, $groupId){
                $this->initiateModel('read');
                $this->dbHandle->select('efcl.id, efcl.heading, efcl.body, efcl.CTA_text, efcl.redirection_url');
                $this->dbHandle->from('exampage_cd_links_mapping eclm');
                $this->dbHandle->join('exampage_featured_cd_links efcl','eclm.link_id=efcl.id','INNER');
                $this->dbHandle->where(array('eclm.status'              => 'live',
                                        'efcl.status'                       => 'live',
                                        'date(efcl.start_date) <='   => date('Y-m-d'),
                                        'date(efcl.end_date)   >='   => date('Y-m-d'),
                                        'eclm.exam_id'              => $examId,
                                        'eclm.group_id'                 => $groupId,
                                    ));
                $this->dbHandle->limit("2");
                $this->dbHandle->order_by("efcl.end_date", "random");
                $result   = $this->dbHandle->get()->result_array();
                return $result;
        }

	public function getExamInfo($groupId){
        	$this->initiateModel('read');
	        $this->dbHandle->select('epm.id as examId, epm.name as examName, epm.url as examUrl, epm.fullName as examFullName, eam.entityId as examYear');
        	$this->dbHandle->from('exampage_main epm');
	        $this->dbHandle->join('exampage_groups epg','epm.id=epg.examId','INNER');
        	$this->dbHandle->join('examAttributeMapping eam', 'eam.groupId = epg.groupId and eam.entityType="year" and eam.status="live"','LEFT');
	        $this->dbHandle->where(array('epg.status'              => 'live',
                        'epm.status'                   => 'live',
                        'epg.groupId'                  => $groupId
                    ));
        	$result   = $this->dbHandle->get()->result_array();
	        return $result[0];
   	}

	public function storeClickCount($id, $tableName){
                $this->initiateModel('write');
                $this->dbHandle->set('clickCount','clickCount+1', FALSE);
                $this->dbHandle->where('id',$id);
                $status = $this->dbHandle->update($tableName);
                return $status;
        }

    public function getExamGroupsWithoutGuide() {
		$sql = "SELECT em.groupId,em.exam_id
					FROM exampage_master em
					LEFT JOIN exampage_guide eg 
					ON eg.exam_id = em.exam_id AND eg.group_id = em.groupId AND eg.status='live'
					WHERE em.status='live' AND eg.group_id is null";
		//return $this->getColumnArray($this->db->query($sql)->result_array(),'groupId');
		return $this->db->query($sql)->result_array();
	}

	public function updateExamGuide($guideArr, $groupId) {
        if(empty($guideArr) || empty($groupId)) {
            return false;
        }
        $this->initiateModel("write");
        //transaction starts
        $this->dbHandle->trans_start();

        //updating status
        $this->dbHandle->where(array('exam_id'=>$guideArr['examId'], 'group_id'=>$groupId, 'status' => 'live'));
        $this->dbHandle->update('exampage_guide',array('status' => 'history'));
        $GuideData                      = array();
        $GuideData['exam_id']        = $guideArr['examId'];
        $GuideData['group_id']      = $groupId;
        $GuideData['guide_url']      = $guideArr['guide_url'];
        $GuideData['guide_year']     = $guideArr['guide_year'];
        $GuideData['guide_size']     = $guideArr['guide_size'];
        $GuideData['updated_by']        = $guideArr['userId'];
        $GuideData['status']            = $guideArr['status'];
        $GuideData['creation_date']            = $guideArr['creation_date'];
        $this->dbHandle->insert('exampage_guide', $GuideData);
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }
        return true;
    }

    public function updateExamSectionGuideurls($guideUrlsData, $pageId) {
        if(empty($guideUrlsData) || !is_array($guideUrlsData) || empty($pageId)) {
            return false;
        }
        $this->initiateModel("write");
        //transaction starts
        $this->dbHandle->trans_start();

        //updating status
        $this->dbHandle->where(array('page_id'=>$pageId, 'status' => 'live'));
        $this->dbHandle->update('exampage_content_guide_url',array('status' => 'history','updationTime' => date('Y-m-d H:i:s')));
        $this->dbHandle->insert_batch('exampage_content_guide_url', $guideUrlsData);
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }
        return true;
    }


    public function getGuideData($groupId){
    	$this->initiateModel('read');
        $this->dbHandle->select('guide_url');
    	$this->dbHandle->from('exampage_guide');
        $this->dbHandle->where('status','live');
	$this->dbHandle->where('group_id',$groupId);
    	$result   = $this->dbHandle->get()->result_array();
        return $result[0];
    }

    public function getAllExamGroupsForGuide() {
        $sql = "SELECT em.groupId,em.exam_id
                    FROM exampage_master em
                    JOIN exampage_guide eg 
                    ON eg.exam_id = em.exam_id AND eg.group_id = em.groupId AND eg.status='live'
                    WHERE em.status='live'";
        //return $this->getColumnArray($this->db->query($sql)->result_array(),'groupId');
        return $this->db->query($sql)->result_array();
    }
    public function  getPageIdsExistingLiveInSectionTable()
    {
        $this->initiateModel('read');
        $query = "SELECT distinct page_id from exampage_section_order where status = 'live'";
        $result = $this->dbHandle->query($query)->result_array();
        $rs = array();
        foreach ($result as $key => $value) {
        	$rs[] = $value['page_id'];
        }
        return $rs;
    }
    function insertNewSectionsForExistingPageIds($data)
    {
    	if(empty($data))
    		return;
    	$this->initiateModel('write');
    	$this->dbHandle->insert_batch('exampage_section_order',$data);
    }

    public function getFilesData($notExistThumb,$fileType = array("guidepapers","preptips","samplepapers")){
        $this->initiateModel('read');
        $whereCondition = "";
        if($notExistThumb){
            $whereCondition = " AND (thumbnail_url is null OR thumbnail_url = '')";
        }
        $query = "SELECT id, page_id, file_url, file_name FROM exampage_content_files where file_type in (?) and status = 'live'".$whereCondition;
        return $this->dbHandle->query($query,array($fileType))->result_array();

    }

    public function getExamIdBasedOnPageIds($pageIds = array()){
        if(empty($pageIds)) {
            return array();
        }

        $sql = "SELECT exam_id,groupId FROM exampage_master WHERE status = 'live' and exampage_id in (?)";
        return $this->dbHandle->query($sql,array($pageIds))->result_array();
    }

    public function updateFileTableWithThumbnail($data){
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
        $this->dbHandle->update_batch('exampage_content_files',$data,'id');
        $this->dbHandle->trans_complete();
        return ($this->dbHandle->trans_status() === FALSE) ? false : true;
    }

    public function getAllLiveUrls(){
        $this->initiateModel('read');
        $sql = "SELECT url FROM exampage_main where status = 'live'";
        $rs = $this->dbHandle->query($sql)->result_array();
        $result = array();
        foreach ($rs as $key => $value) {
            if(!empty($value['url'])){
                $result[] = $value['url'];
            }
        }
        return $result;
    }

    public function getExamConductingBody($groupId){
        if(empty($groupId)){
            return;
        }
         $this->initiateModel('read');
        $sql = "Select exampage_main.conductedBy from exampage_master em JOIN exampage_main ON em.exam_id = exampage_main.id where em.groupId = ? and em.status = 'live' ";
        return $this->dbHandle->query($sql,array($groupId))->result_array();
    }


    public function getExamAndGroupId($entityIds,$entityType){
        if (empty($entityIds) || empty($entityType)){
            return;
        }

        $this->initiateModel('read');
        $sql =  "Select examId,groupId,entityId from examAttributeMapping where entityId IN (?) and entityType in (?) and status = 'live' ";

        $result = $this->dbHandle->query($sql,array($entityIds,$entityType))->result_array();
        return $result;
    }

    public function getExamPrimaryGroupIdByName($examName){
        if (empty($examName)){
            return;
        }
        $this->initiateModel('read');
        $sql =  "SELECT eg.groupId, em.name FROM exampage_main em JOIN exampage_groups eg ON em.id = eg.examId WHERE em.name IN (?) AND em.status = 'live' AND eg.isPrimary = 1 AND eg.status = 'live'";

        $result = $this->dbHandle->query($sql,array($examName))->result_array();
        return $result;

    }

    public function getExamNameGroupId(){
        $this->initiateModel('read');
        $sql = "Select em.name,eg.groupId from exampage_main em join exampage_groups eg ON ((em.id = eg.examId) and  em.status ='live' and eg.status = 'live' and eg.isPrimary = 1)";
        $result = $this->dbHandle->query($sql)->result_array();
        return $result;
    }
    
}
