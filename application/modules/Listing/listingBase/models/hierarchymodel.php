<?php

class hierarchymodel extends MY_Model {
	function __construct() {
		parent::__construct('Listing');
    }

	private function initiateModel($mode = "write") {
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

    public function getElementIdsByHierarchyId($hierarchyIds) {
        if(empty($hierarchyIds)){
            return;
        }
        $this->initiateModel('read');
        $sql = "SELECT hierarchy_id, stream_id, substream_id, specialization_id FROM base_hierarchies WHERE hierarchy_id IN (?)";
        $data = $this->dbHandle->query($sql,array($hierarchyIds))->result_array();

        $returnData = array();
        foreach ($data as $row) {
            $returnData[$row['hierarchy_id']] = $row;
        }

        return $returnData;
    }

    public function getSpecializationByStreamSubstream($streamId, $substreamIds) {
        $this->initiateModel('read');
        if(empty($streamId)) {
            return;
        }
        $sql = "SELECT DISTINCT stream_id, substream_id, specialization_id FROM base_hierarchies WHERE status = 'live' AND stream_id = ".$streamId." AND specialization_id IS NOT NULL ";
        
        if(empty($substreamIds) || $substreamIds[0] == 'any') {
            $whereSubstream = "";
        } elseif($substreamIds[0] == 'none') {
            $whereSubstream = " AND substream_id IS NULL";
        } else {
            $whereSubstream = " AND substream_id IN (?)";
        }
        
        $sql = $sql.$whereSubstream;
        $data = $this->dbHandle->query($sql,array($substreamIds))->result_array();
        
        return $data;
    }

    public function getSubstreamSpecializationByStream($streamIds) {
        $this->initiateModel('read');
        if(empty($streamIds)) {
            return;
        }
        $sql = "SELECT  stream_id, substream_id, specialization_id FROM base_hierarchies WHERE status = 'live' AND stream_id IN (?)";
        
        $data = $this->dbHandle->query($sql,array($streamIds))->result_array();
        
        return $data;
    }

    public function getHierarchyIdsForAllCombinations($streamIds, $substreamIds, $specializationIds) {
        $this->initiateModel('read');
        if(empty($streamIds)) {
            return;
        }
        $sql = "SELECT hierarchy_id, stream_id, substream_id, specialization_id FROM base_hierarchies WHERE status = 'live' AND stream_id IN (?) ";
        $sqlArgs = array($streamIds);
        
        if(empty($substreamIds) || $substreamIds[0] == 'any') {
            $whereSubstream = "";
        } elseif($substreamIds[0] == 'none') {
            $whereSubstream = " AND substream_id IS NULL";
        } else {
            $whereSubstream = " AND substream_id IN (?)";
            array_push($sqlArgs, $substreamIds);
        }

        if(empty($specializationIds) || $specializationIds[0] == 'any') {
            $whereSpecialization = "";
        } elseif($specializationIds[0] == 'none') {
            $whereSpecialization = " AND specialization_id IS NULL";
        } else {
            $whereSpecialization = " AND specialization_id IN (?)";
            array_push($sqlArgs, $specializationIds);
        }
        
        $sql = $sql.$whereSubstream.$whereSpecialization;
        $data = $this->dbHandle->query($sql,$sqlArgs)->result_array();
        
        return $data;
    }

    function getHierarchyByBaseEntities($streamId, $substreamId, $specializationId) {
    	if(empty($streamId)) {
    		return false;
    	}
        if(empty($substreamId)) {
            $substreamId = 'any';
        }
        if(empty($specializationId)) {
            $specializationId = 'any';
        }

    	//get data
    	$this->initiateModel('read');

    	$sql = "SELECT * FROM base_hierarchies WHERE status = 'live' ";

    	$whereStream = " AND stream_id = ?";
        $sqlArgs = array($streamId);
    	
        if($substreamId == 'none') {
            $whereSubstream = " AND substream_id IS NULL";
        } elseif($substreamId == 'any') {
            $whereSubstream = "";
        } else {
            $whereSubstream = " AND substream_id = ?";
            array_push($sqlArgs, $substreamId);
        }
    	
        if($specializationId == 'none') {
            $whereSpecialization = " AND specialization_id IS NULL";
        } elseif($specializationId == 'any') {
            $whereSpecialization = '';
        } else {
            $whereSpecialization = " AND specialization_id = ?";
            array_push($sqlArgs, $specializationId);
        }
        $sql = $sql.$whereStream.$whereSubstream.$whereSpecialization;
    	
    	$data = $this->dbHandle->query($sql,$sqlArgs)->result_array();
    	return $data;
    }

    function getHierarchiesByMultipleBaseEntities($hierarchyArr){
        $this->initiateModel('read');

        $sql = "SELECT hierarchy_id, stream_id, substream_id, specialization_id FROM base_hierarchies WHERE status = 'live' ";
        $sqlArgs=array();
        
        $whereClause = array();
        foreach($hierarchyArr as $hierarchy) {
            Contract::mustBeNumericValueGreaterThanZero($hierarchy['streamId']);
            
            $where = array();
            $where[] = 'stream_id = ?';
            array_push($sqlArgs, $hierarchy['streamId']);

            //where for substream
            if($hierarchy['substreamId'] == 'none') {
                $where[] = 'substream_id IS NULL';
            } elseif(empty($hierarchy['substreamId']) || $hierarchy['substreamId'] == 'any') {
                // no check
            } else {
                $where[] = "substream_id = ?";
                array_push($sqlArgs, $hierarchy['substreamId']);
            }

            //where for specialization
            if($hierarchy['specializationId'] == 'none') {
                $where[] = 'specialization_id IS NULL';
            } elseif(empty($hierarchy['specializationId']) || $hierarchy['specializationId'] == 'any') {
                // no check
            } else {
                $where[] = "specialization_id = ?";
            	array_push($sqlArgs, $hierarchy['specializationId']);
            }
            
            $whereClause[] = '('.implode(' AND ', $where).')';
        }
        if(count($whereClause) > 0) {
            $whereClause = '('.implode(' OR ', $whereClause).')';
        }
        $sql = $sql." AND ".$whereClause;
        
        $data = $this->dbHandle->query($sql,$sqlArgs)->result_array();

        return $data;
    }

    public function insertDataIntoHierarchyTable($data){
        $this->initiateModel();
        
        $this->dbHandle->insert('base_hierarchies',$data);
        return $this->dbHandle->insert_id();
    }

    public function getCompleteHierarchyTableData(){
        $this->initiateModel();
        $this->dbHandle->select('hierarchy_id,stream_id,substream_id,specialization_id,national,abroad,academic,testprep');
        $this->dbHandle->where('status','live');
        return $this->dbHandle->get('base_hierarchies')->result_array();
    }
    
    public function getSortedSubstreams($substreamids)
    {
        $this->initiateModel();
        if(empty($substreamids)) {
            return;
        }
        $sql = "SELECT substream_id FROM substreams WHERE status = 'live' AND substream_id IN (?)  order by `display_order`,name";
        return $data = $this->dbHandle->query($sql,array($substreamids))->result_array();

    }
}