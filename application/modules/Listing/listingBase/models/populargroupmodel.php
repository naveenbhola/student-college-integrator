<?php 
class populargroupmodel extends listingbasemodel{
	
	public function save($dataList,$mode){
		$this->initiateModel();
		$popularGroupData = $dataList['table'];
		$userId = $dataList['userId'];
		$this->dbHandle->trans_start();
		if($mode == 'add'){
			$this->dbHandle->where(array('name'=>$popularGroupData['name'],'status'=>'live'));
			$data = $this->dbHandle->get('popular_groups')->row_array();
			if(empty($data)){
				$popularGroupData['created_on'] = date("Y-m-d H:i:s");
				$popularGroupData['updated_by'] = $userId;
				$popularGroupData['created_by'] = $userId;

				$this->dbHandle->insert('popular_groups',$popularGroupData);
				return array('data' => array('status'=>'success','message'=>'popular group added successfully','popular_group_id'=>$this->dbHandle->insert_id(),'dbHandle'=>$this->dbHandle));
			}
			else{
				return array('data'=>array('status'=>'fail','message'=>'popular group with that name already exists','popular_group_id'=>$data['popular_group_id']));
			}
		}
		else if($mode == 'edit'){
			$this->dbHandle->where(array('name'=>$popularGroupData['name'],'status'=>'live','popular_group_id !='=>$popularGroupData['popular_group_id']));
			$data = $this->dbHandle->get('popular_groups')->row_array();
			if(empty($data)){
				$this->dbHandle->where(array('status'=>'live','popular_group_id'=>$popularGroupData['popular_group_id']));
				$popularGroupData['updated_by'] = $userId;
				$this->dbHandle->update('popular_groups',$popularGroupData);
				return array('data'=>array('status'=>'success','message'=>'popular group added successfully','popular_group_id'=>$popularGroupData['popular_group_id'],'dbHandle'=>$this->dbHandle));
			}
			else{
				return array('data'=>array('status'=>'fail','message'=>'popular group with that name already exists','popular_group_id'=>$data['popular_group_id']));	
			}
		}
	}

	public function insertHierarchyMapping($popularGroupId,$insertData,$dbHandle){
		$dbHandle->where(array('entity_id'=>$popularGroupId,'entity_type'=>'popular_group'));
		$dbHandle->update('entity_hierarchy_mapping',array('status'=>'deleted'));
		$dbHandle->insert_batch('entity_hierarchy_mapping',$insertData);
		$dbHandle->trans_complete();
		return $dbHandle->affected_rows();
	}

	public function getAllPopularGroups(){
		$this->initiateModel('read');

		$sql = "SELECT DISTINCT popular_group_id as id, name FROM popular_groups WHERE status = 'live' ";
		
		$result = $this->dbHandle->query($sql)->result_array();
		return $result;
	}

	public function getPopularGroupsByMultipleBaseEntities($hierarchyArr) {
	    $this->initiateModel('read');

	    $sql = "SELECT DISTINCT entity_id as popular_group_id, stream_id, substream_id, specialization_id FROM entity_hierarchy_mapping WHERE status = 'live' and entity_type = 'popular_group' ";
	    $sqlArgs = array();
	    
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

	public function getPopularGroupsForAllCombinations($streamIds, $substreamIds, $specializationIds) {
	    $this->initiateModel('read');
	    
	    if(empty($streamIds)) {
	        return;
	    }

	    $sql = "SELECT DISTINCT entity_id as popular_group_id FROM entity_hierarchy_mapping WHERE status = 'live' AND entity_type = 'popular_group' AND stream_id IN (?) ";
	    $sqlArgs = array($streamIds);
	    
	    //where for substream
	    if($substreamIds[0] == 'none') {
	        $whereSubstream = ' AND substream_id IS NULL';
	    } elseif(empty($substreamIds) || $substreamIds[0] == 'any') {
	        //skip the check
	    } else {
	        $whereSubstream = " AND substream_id IN (?)";
    		array_push($sqlArgs, $substreamIds);
	    }

	    //where for specialization
	    if($specializationIds[0] == 'none') {
	        $whereSpecialization = ' AND specialization_id IS NULL';
	    } elseif(empty($specializationIds) || $specializationIds[0] == 'any') {
	        // no check
	    } else {
	        $whereSpecialization = " AND specialization_id IN (?)";
    		array_push($sqlArgs, $specializationIds);
	    }

	    $sql = $sql.$whereSubstream.$whereSpecialization;
	    
	    $data = $this->dbHandle->query($sql,$sqlArgs)->result_array();
	    return $data;
	}

	public function getPopularGroupsByHierarchyIds($hierarchyIds) {
	    $this->initiateModel('read');
	    
	    if(empty($hierarchyIds)) {
	        return;
	    }

	    //get stream, substream specialization of each hierarchy id
	    $sql = "SELECT DISTINCT stream_id, substream_id, specialization_id, hierarchy_id FROM base_hierarchies WHERE status = 'live' AND hierarchy_id IN (?) ";
	    $hierarchyData = $this->dbHandle->query($sql,array($hierarchyIds))->result_array();
	    
	    //get populargroup ids on the basis of above stream, substream, specialization
	    $sql = "SELECT DISTINCT stream_id, substream_id, specialization_id, entity_id as popular_group_id FROM entity_hierarchy_mapping WHERE entity_type = 'popular_group' ";
	    $sqlArgs = array();

	    foreach ($hierarchyData as $key => $value) {
	        $hierarchyMappingKey = $value['stream_id'].'-'.$value['substream_id'].'-'.$value['specialization_id'];
	        $hierarchyMapping[$hierarchyMappingKey] = $value['hierarchy_id'];
    		array_push($sqlArgs, $value['stream_id']);
	        
	        if(empty($value['substream_id'])) {
	            $whereSubstream = "substream_id IS NULL";
	        } else {
	            $whereSubstream = "substream_id = ?";
    		    array_push($sqlArgs, $value['substream_id']);
	        }

	        if(empty($value['specialization_id'])) {
	            $whereSpecialization = "specialization_id IS NULL";
	        } else {
	            $whereSpecialization = "specialization_id = ?";
    		    array_push($sqlArgs, $value['specialization_id']);
	        }
	        $where[] = "(stream_id = ? AND ".$whereSubstream." AND ".$whereSpecialization.")";
	    }
	    $whereStatement = implode(' OR ', $where);
	    $sql = $sql." AND ".$whereStatement;
	    
	    $data = $this->dbHandle->query($sql,$sqlArgs)->result_array();
	    
	    foreach ($data as $key => $value) {
	        $hierarchyMappingKey = $value['stream_id'].'-'.$value['substream_id'].'-'.$value['specialization_id'];
	        $result[$key]['hierarchy_id'] = $hierarchyMapping[$hierarchyMappingKey];
	        $result[$key]['popular_group_id'] = $value['popular_group_id'];
	    }

	    return $result;
	}

	public function getHierarchyIdsByPopularGroups($popularGroupIds) {
	    $this->initiateModel('read');
	    
	    if(empty($popularGroupIds)) {
	        return;
	    }

	    //get stream, substream specialization of each hierarchy id
	    $sql = "SELECT DISTINCT stream_id, substream_id, specialization_id, entity_id as popular_group_id FROM entity_hierarchy_mapping WHERE status = 'live' AND entity_id IN (?) ";
	    $courseData = $this->dbHandle->query($sql,array($popularGroupIds))->result_array();

	    if(empty($courseData)){
	        return array();
	    }
	    
	    //get hierarchy ids on the basis of above stream, substream, specialization
	    $sql = "SELECT DISTINCT stream_id, substream_id, specialization_id, hierarchy_id FROM base_hierarchies WHERE ";
	    $sqlArgs = array();
	    foreach ($courseData as $key => $value) {
	        $courseMappingKey = $value['stream_id'].'-'.$value['substream_id'].'-'.$value['specialization_id'];
	        $courseMapping[$courseMappingKey] = $value['popular_group_id'];
		array_push($sqlArgs, $value['stream_id']);
	        
	        if(empty($value['substream_id'])) {
	            $whereSubstream = "substream_id IS NULL";
	        } else {
	            $whereSubstream = "substream_id = ?";
    		    array_push($sqlArgs, $value['substream_id']);
	        }

	        if(empty($value['specialization_id'])) {
	            $whereSpecialization = "specialization_id IS NULL";
	        } else {
	            $whereSpecialization = "specialization_id = ?";
    		    array_push($sqlArgs, $value['specialization_id']);
	        }
	        $where[] = "(stream_id = ? AND ".$whereSubstream." AND ".$whereSpecialization.")";
	    }
	    $whereStatement = implode(' OR ', $where);
	    $sql = $sql.$whereStatement;
	    
	    $data = $this->dbHandle->query($sql,$sqlArgs)->result_array();
	    
	    foreach ($data as $key => $value) {
	        $courseMappingKey = $value['stream_id'].'-'.$value['substream_id'].'-'.$value['specialization_id'];
	        $result[$key]['hierarchy_id'] = $value['hierarchy_id'];
	        $result[$key]['popular_group_id'] = $courseMapping[$courseMappingKey];
	    }

	    return $result;
	}

	public function getBaseEntitiesByPopularGroupIds($popularGroupIds, $streamIds, $specializationIds) {
	    $this->initiateModel('read');
	    
	    if(empty($popularGroupIds)) {
	        return;
	    }

	    $sql = "SELECT DISTINCT stream_id, substream_id, specialization_id, entity_id as popular_group_id ".
	            "FROM entity_hierarchy_mapping ".
	            "WHERE entity_id IN (?) ".
	            "AND status = 'live' ".
	            "AND entity_type = 'popular_group' ";
	    $sqlArgs = array($popularGroupIds);
	    
	    $whereStream = '';
	    if(!empty($streamIds) && $streamIds[0] != 'any') {
	        if($streamIds[0] == 'none') {
	            $whereStream = " AND stream_id IS NULL ";
	        } else {
	            $whereStream = " AND stream_id IN (?) ";
    			array_push($sqlArgs, $streamIds);
	        }
	    }
	    $whereSpecialization = '';
	    if(!empty($specializationIds) && $specializationIds[0] != 'any') {
	        if($specializationIds[0] == 'none') {
	            $whereSpecialization = " AND specialization_id IS NULL ";
	        } else {
	            $whereSpecialization = " AND specialization_id IN (?) ";
    			array_push($sqlArgs, $specializationIds);
	        }
	    }
	    $sql = $sql.$whereStream.$whereSpecialization;

	    $data = $this->dbHandle->query($sql,$sqlArgs)->result_array();
	    return $data;
	}
}
?>