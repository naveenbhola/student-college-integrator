<?php 
class basecoursemodel extends listingbasemodel{

    private static $successMessage = 'Base-course added successfully';
    private static $editSuccessMessage = 'Base-course edited successfully';
    private static $failureMessage = 'Base-course with the given name already exists';
    public static $entityType = 'base_course';
    public static $statusOk = 'success';
    public static $statusFail = 'fail';

    public function save($dataList,$mode){
        $this->initiateModel();
        $basecourseData = $dataList['table'];
        $userId = $dataList['userId'];
        $this->dbHandle->trans_start();
        if($mode == 'add'){
            $this->dbHandle->where(array('name'=>$basecourseData['name'],'status'=>'live'));
            $data = $this->dbHandle->get('base_courses')->row_array();
            if(empty($data)){
                $basecourseData['created_on'] = date("Y-m-d H:i:s");
                $basecourseData['updated_by'] = $userId;
                $basecourseData['created_by'] = $userId;
                $this->dbHandle->insert('base_courses',$basecourseData);
                return array('data' => array('status'=>basecoursemodel::$statusOk,'message'=>basecoursemodel::$successMessage,'base_course_id'=>$this->dbHandle->insert_id(),'dbHandle'=>$this->dbHandle));
            }
            else{
                return array('data'=>array('status'=>basecoursemodel::$statusFail,'message'=>basecoursemodel::$failureMessage,'base_course_id'=>$data['base_course_id']));
            }
        }
        else if($mode == 'edit'){
            $this->dbHandle->where(array('name'=>$basecourseData['name'],'status'=>'live','base_course_id !='=>$basecourseData['base_course_id']));
            $data = $this->dbHandle->get('base_courses')->row_array();
            if(empty($data)){
                $this->dbHandle->where(array('status'=>'live','base_course_id'=>$basecourseData['base_course_id']));
                $basecourseData['updated_by'] = $userId;
                $this->dbHandle->update('base_courses',$basecourseData);
                return array('data'=>array('status'=>basecoursemodel::$statusOk,'message'=>basecoursemodel::$editSuccessMessage,'base_course_id'=>$basecourseData['base_course_id'],'dbHandle'=>$this->dbHandle));
            }
            else{
                return array('data'=>array('status'=>basecoursemodel::$statusFail,'message'=>basecoursemodel::$failureMessage,'base_course_id'=>$data['base_course_id']));
            }
        }
    }

    /**
     * Preserve the base-course -- hierarchy mapping
     *
     * <i>Can we move this file up in the listings base model?</i>
     *
     * @param int $basecourseId The base course id
     * @param array $insertData The base-course -- hierarchy relation data to be preserved
     *
     * @return mixed The number of rows affected
     * @see \populargroupmodel::insertHierarchyMapping
     */
    public function insertHierarchyMapping($basecourseId,$insertData,$dbHandle){
        $dbHandle->where(array('entity_id'=>$basecourseId,'entity_type'=>basecoursemodel::$entityType));
        $dbHandle->update('entity_hierarchy_mapping',array('status'=>'deleted'));
        $dbHandle->insert_batch('entity_hierarchy_mapping',$insertData);
        $dbHandle->trans_complete();
        return $dbHandle->affected_rows();
    }

    public function getPopularCourses(){
        $this->initiateModel('read');

        $selectFields = array('base_course_id', 'name');
        $whereClause = array('is_popular' => '1', 'status'=> 'live');

        $this->dbHandle->select($selectFields);
        $this->dbHandle->where($whereClause);

        return $this->dbHandle->get('base_courses')->result_array();
    }

    public function getNonPopularCourses(){
        $this->initiateModel('read');
        $selectFields = array('base_course_id', 'name');
        $whereClause = array('is_popular' => '0', 'status'=> 'live');

        $this->dbHandle->select($selectFields);
        $this->dbHandle->where($whereClause);

        return $this->dbHandle->get('base_courses')->result_array();
    }

    function getAllBaseCourses($includeDummy = 0) {
        
        
        $this->initiateModel('read');
        $sql = "SELECT DISTINCT base_course_id as id, name FROM base_courses WHERE status = 'live' ";
        if(empty($includeDummy)) {
            $whereDummy = " AND is_dummy = 0 ";
        }
        else
        {
            $whereDummy = " AND base_course_id != 143 ";   
        }
        $sql = $sql.$whereDummy;
        
        $result = $this->dbHandle->query($sql)->result_array();
        return $result;
    }

    function getBaseCoursesByMultipleBaseEntities($hierarchyArr , $includeDummy = 0) {
        $this->initiateModel('read');

        $sql = "SELECT DISTINCT entity_id as course_id, stream_id, substream_id, specialization_id FROM entity_hierarchy_mapping WHERE status = 'live' and entity_type = 'base_course' ";
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

        if(!empty($includeDummy))
        {
            $sql = "SELECT DISTINCT base_course_id as id FROM base_courses WHERE status = 'live' AND is_dummy = 1 AND base_course_id != 143 ";   
            $dummyCourseData = $this->dbHandle->query($sql)->result_array();
            foreach ($dummyCourseData as $key => $value) {
                $data[] = array('course_id' => $value['id'], 'stream_id' =>'','substream_id' => '','specialization_id'=> '');
            }
        }
        
        return $data;
    }

    function getBasecoursesByHirarchyWithFilter($hierarchyArr, $courseLevel, $credential) {
        $this->initiateModel('read');
        $sql = "SELECT DISTINCT entity_id as course_id, 
                    stream_id, 
                    substream_id, 
                    specialization_id 
                FROM entity_hierarchy_mapping ehm
                LEFT JOIN base_courses bc
                on (bc.base_course_id  = ehm.entity_id and entity_type = 'base_course')
                WHERE ehm.status = 'live' 
                AND entity_type = 'base_course' ";
        $sqlArgs = array();

        //$sql = "SELECT DISTINCT entity_id as course_id, stream_id, substream_id, specialization_id FROM entity_hierarchy_mapping WHERE status = 'live' and entity_type = 'base_course' ";
        
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
            $where = array();
        }
        if($credential) {
            $where[] = "(bc.credential_1 = ? OR bc.credential_2 = ? )";
            array_push($sqlArgs, $credential, $credential);
        }
        
        if($courseLevel) {
            $where[] = "bc.level = ?";
            array_push($sqlArgs, $courseLevel);
        }
        
        $sql = $sql." AND ".$whereClause;
        
        if(!empty($where)) {
            $sql = $sql." AND ". implode(" AND ", $where);
        }
        $data = $this->dbHandle->query($sql,$sqlArgs)->result_array();
        // _p($this->dbHandle->last_query()); 
        // _p($data); die;
        
        return $data;
    }

    function getBaseCoursesForAllCombinations($streamIds, $substreamIds, $specializationIds) {
        $this->initiateModel('read');
        
        if(empty($streamIds)) {
            return;
        }

        $sql = "SELECT DISTINCT entity_id as course_id FROM entity_hierarchy_mapping WHERE status = 'live' AND entity_type = 'base_course' AND stream_id IN (?) ";
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

    function getBaseCoursesByHierarchyIds($hierarchyIds) {
        $this->initiateModel('read');
        
        if(empty($hierarchyIds)) {
            return;
        }

        //get stream, substream specialization of each hierarchy id
        $sql = "SELECT DISTINCT stream_id, substream_id, specialization_id, hierarchy_id FROM base_hierarchies WHERE status = 'live' AND hierarchy_id IN (?) ";
        $hierarchyData = $this->dbHandle->query($sql,array($hierarchyIds))->result_array();
        
        //get course ids on the basis of above stream, substream, specialization
        $sql = "SELECT DISTINCT stream_id, substream_id, specialization_id, entity_id as course_id FROM entity_hierarchy_mapping WHERE entity_type = 'base_course' ";
        $sqlArgs=array();

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
        $sql = $sql." AND (".$whereStatement.")";
        
        $data = $this->dbHandle->query($sql,$sqlArgs)->result_array();
        
        foreach ($data as $key => $value) {
            $hierarchyMappingKey = $value['stream_id'].'-'.$value['substream_id'].'-'.$value['specialization_id'];
            $result[$key]['hierarchy_id'] = $hierarchyMapping[$hierarchyMappingKey];
            $result[$key]['course_id'] = $value['course_id'];
        }

        // $sql = "SELECT ehm.entity_id as course_id, bh.hierarchy_id ".
        //        "FROM entity_hierarchy_mapping ehm ".
        //        "LEFT JOIN base_hierarchies bh ON bh.stream_id = ehm.stream_id AND bh.substream_id = ehm.substream_id AND bh.specialization_id = ehm.specialization_id AND bh.status = 'live' ".
        //        "WHERE ehm.status = 'live' AND entity_type = 'base_course' ".
        //        "AND bh.hierarchy_id IN (".implode(',', $hierarchyIds).")";

        // $data = $this->dbHandle->query($sql)->result_array();
        return $result;
    }

    function getHierarchyIdsByBaseCourses($baseCourseIds) {
        $this->initiateModel('read');
        
        if(empty($baseCourseIds)) {
            return;
        }

        //get stream, substream specialization of each hierarchy id
        $sql = "SELECT DISTINCT stream_id, substream_id, specialization_id, entity_id as course_id FROM entity_hierarchy_mapping WHERE status = 'live' AND entity_id IN (?) ";
        $courseData = $this->dbHandle->query($sql,array($baseCourseIds))->result_array();

        if(empty($courseData)){
            return array();
        }

        //get hierarchy ids on the basis of above stream, substream, specialization
        $sql = "SELECT DISTINCT stream_id, substream_id, specialization_id, hierarchy_id FROM base_hierarchies WHERE ";
        $sqlArgs = array();
        foreach ($courseData as $key => $value) {
            $courseMappingKey = $value['stream_id'].'-'.$value['substream_id'].'-'.$value['specialization_id'];
            $courseMapping[$courseMappingKey] = $value['course_id'];
            
            array_push($sqlArgs, $value['stream_id'] );

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
            $result[$key]['course_id'] = $courseMapping[$courseMappingKey];
        }

        // $sql = "SELECT ehm.entity_id as course_id, bh.hierarchy_id ".
        //        "FROM entity_hierarchy_mapping ehm ".
        //        "INNER JOIN base_hierarchies bh ON bh.stream_id = ehm.stream_id AND bh.substream_id = ehm.substream_id AND bh.specialization_id = ehm.specialization_id AND bh.status = 'live' ".
        //        "WHERE ehm.status = 'live' AND entity_type = 'base_course' ".
        //        "AND ehm.entity_id IN (".implode(',', $baseCourseIds).")";

        // $data = $this->dbHandle->query($sql)->result_array();
        return $result;
    }

    function getBaseEntitiesByBaseCourseIds($baseCourseIds, $streamIds, $specializationIds) {
        $this->initiateModel('read');
        
        if(empty($baseCourseIds)) {
            return;
        }

        $sql = "SELECT DISTINCT stream_id, substream_id, specialization_id, entity_id as course_id ".
                "FROM entity_hierarchy_mapping ".
                "WHERE entity_id IN (?) ".
                "AND status = 'live' ".
                "AND entity_type = 'base_course' ";
        $sqlArgs = array($baseCourseIds);
        
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

    function getBaseCoursesByCredential($credential){
        $this->initiateModel('read');
        $data = array();

        if(empty($credential)){
            return $data;
        }

        $sql = "SELECT base_course_id, name 
                FROM base_courses
                WHERE credential_1 = ? 
                OR credential_2 = ?";
        
        $data = $this->dbHandle->query($sql,array($credential,$credential))->result_array();
        return $data;
    }
} 

?>