<?php
/**
 * Model common to all base entities.
 * @author Shiksha Listings Team
 */
class listingbasemodel extends MY_Model {
	public $dbHandle = '';
    public $dbHandleMode = '';
    
    function __construct() {
		parent::__construct('Listing');
    }
    
    function initiateModel($mode = "write") {
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

    /**
     * Get the entities mapped to hierarchies based on certain filters.
     * The possible filters are:
     * <table border=1><tr><th>Filter name</th><th>Field name in table</th></tr>
     * <tr><td>entity type</td><td>entity_type</td></tr>
     * <tr><td>entity id</td><td>entity_id</td></tr>
     * <tr><td>hierarchy id</td><td>hierarchy_id</td></tr>
     * </table>
     * In the most common use case, the entity type and entity id filters would be used as a combination.
     *
     * @param  string $entityType   Type of entity (Either of (case sensitive) <b>course</b>, <b>popularGroup</b>, <b>certificationProvider</b>)
     * @param  array  $entityIds    The entity ids
     * @param  array  $hierarchyIds The hierarchy ids
     * @return array   A nested array containing hierarchy_id,entity_id,entity_type which satisfy the conditions passed.
     */
    public function getEntitiesMappedToHierarchies($entityType,array $entityIds = array(),array $hierarchyIds = array()){

        $this->initiateModel('read');
        
        $whereClause['status'] = 'live';

        if(!empty($entityType)){
            $whereClause['entity_type'] = $entityType;
        }

        if(!empty($entityIds)){
            $this->dbHandle->where_in('entity_id', $entityIds);
        }
        if(!empty($hierarchyIds)){
            $this->dbHandle->where_in('hierarchy_id',$hierarchyIds);
        }

        $this->dbHandle->select('hierarchy_id,entity_id,entity_type');
        $this->dbHandle->where($whereClause);

        $result = $this->dbHandle->get('entity_hierarchy_mapping')->result_array();

        if(empty($result)){
            return false;
        }

        return $result;
    }

    /**
     * get entities mapped to courses where we can pass filters as arguments.
     * The filters possible are: entityType, entityIds and course ids. Usually entitytype and entityids are used together.
     * @param  string $entityType Type of entity -- currently only certificationProvider
     * @param  array  $entityIds  array of entityids
     * @param  array  $courseIds  array of courseids
     * @return array             returns a nested array containing popular_course_id,entity_type,entity_id satisfying the filters.
     * @author Hemanth 
     */
    public function getEntitiesMappedToCourses($entityType,array $entityIds = array(),array $courseIds = array()){
        
        $this->initiateModel('read');
        $this->dbHandle->select('base_course_id,entity_id,entity_type');
        $this->dbHandle->where('status','live');

        if(!empty($entityType)){
            $this->dbHandle->where('entity_type',$entityType);
        }
        if(!empty($entityIds)){
            $this->dbHandle->where_in('entity_id',$entityIds);
        }
        if(!empty($courseIds)){
            $this->dbHandle->where_in('base_course_id',$courseIds);
        }

        $result = $this->dbHandle->get('entity_course_mapping')->result_array();

        if(empty($result)){
            return false;
        }
        return $result;
    }

        public function getDataFromTable($tableName,$id = NULL){
           
            $this->initiateModel('read');

            $this->dbHandle->from($tableName);
            $this->dbHandle->where("status","live");

            if(!empty($id)){
                if(!is_array($id)){
                 $id=  explode(',',$id);
                }
                $this->dbHandle->where_in(substr($tableName,0,-1)."_id", $id);
            }
            $data    = $this->dbHandle->get()->result_array();
    
            $dataWithKey = array();
            foreach ($data as $key => $value) {
                $dataWithKey[$value[substr($tableName,0,-1)."_id"]] = $value;
            }
            return $dataWithKey;
        }

    /**
     * Get set of hierarchy ids satisfying a set of condition passed as an array.
     * The filters possible are scope, courseType,hierarchyIds,streamIds,substreamIds,specializationIds
     * @param  array  $filters Various filters set as keys of an array
     * @return array   returns an array of hierarchy ids satisfying passed filters.
     * @author Hemanth 
     */
    public function getHierarchyIdsByFilters($filters = array()){
        $this->initiateModel('read');

        $whereClause['status'] = 'live';
        
        $this->dbHandle->select('hierarchy_id,stream_id,substream_id,specialization_id,scope,course_type');
        $this->dbHandle->where($whereClause);

        if(!empty($filters['hierarchyIds'])){
            $this->dbHandle->where_in('hierarchy_id',$filters['hierarchyIds']);
        }
        if(!empty($filters['streamIds'])){
            $this->dbHandle->where_in('stream_id',$filters['streamIds']);
        }
        if(!empty($filters['substreamIds'])){
            $this->dbHandle->where_in('substream_id',$filters['substreamIds']);
        }
        if(!empty($filters['specializationIds'])){
            $this->dbHandle->where_in('specialization_id',$filters['specializationIds']);
        }

        $result = $this->dbHandle->get('hierarchy_details')->result_array();
        $hierarchyIds = $this->getColumnArray($result,'hierarchy_id');
        return array_unique($hierarchyIds);
    }

    /*
     * API to get the input courses on the basis of their mapping with the given stream.
     * @paras: $streamId (INT) => primary key id of stream table.
     *         $baseCourses (Array) => collection of base course ids
     * @return: Array having input courses which are mapped to the provided streamId
     */
    public function filterCoursesMappedToStreamAndCourse($streamId, $baseCourses = array()){
        if(empty($baseCourses) || empty($streamId)){
            return array();
        }

        $this->initiateModel('read');

        $sql = 'SELECT ehm.entity_id AS baseCourse, ehm.substream_id FROM entity_hierarchy_mapping ehm 
                WHERE ehm.entity_id IN (?) 
                AND ehm.entity_type="base_course" AND ehm.stream_id=? 
                AND ehm.status="live"';

        return $this->dbHandle->query($sql, array($baseCourses,$streamId))->result_array();
    }

    /* API to get the possible combinations of base courses and specializations
     * @Params:  $baseCourseIds (Array) => collection of base course ids
     *           $specializationIds (Array) => Collection of specilization Ids
     * @return:  Array having valid combinations of base courses and specialization
     */
    public function getValidCombinationsOfCourseAndSpec($streamId, $baseCourseIds=array(), $specializationIds=array()){
        if(empty($baseCourseIds) || empty($specializationIds)){
            return array();
        }

        $this->initiateModel('read');
        $sql = 'select substream_id, entity_id AS baseCourse, specialization_id FROM entity_hierarchy_mapping
                 WHERE (entity_id IN (?) AND specialization_id IN (?))
                AND entity_type="base_course" AND stream_id=? 
                AND status="live" GROUP BY entity_id, specialization_id';
        return $this->dbHandle->query($sql, array($baseCourseIds,$specializationIds,$streamId))->result_array();
    }   

    /* API to get the possible combinations of base courses, specializations and sub stream
     * @Params:  $baseCourseIds (Array) => collection of base course ids
     *           $specializationIds (Array) => Collection of specilization Ids
     * @return:  Array having valid combinations of base courses and specialization
     */
    public function getValidCombinationsOfCourseSpecAndSubStream($streamId, $substream, $baseCourseIds=array(), $specializationIds=array()){
        if(empty($baseCourseIds) || empty($specializationIds)){
            return array();
        }

        $this->initiateModel('read');

        $sql = 'SELECT ehm.entity_id AS baseCourse, ehm.specialization_id, ehm.substream_id 
                from entity_hierarchy_mapping ehm
                WHERE (((ehm.entity_id IN (?) AND ehm.specialization_id IN (?)) AND ehm.substream_id=?) 
                OR (ehm.entity_id IN (?) AND ehm.specialization_id IS NULL AND ehm.substream_id=?))
                 AND ehm.stream_id=? GROUP BY entity_id, specialization_id';
        return $this->dbHandle->query($sql, array($baseCourseIds,$specializationIds,$substream, $baseCourseIds, $substream,$streamId))->result_array();
    }   

    /**
     * Fetch the Level id and credential from the base course ids
     * @param array  $baseCourseIds The base course ids
     * @return Array The mapped level and credential to the base course
     */
    public function getLevelCredentailOfBaseCourses($baseCourseIds = array()){
        if(empty($baseCourseIds)){
            return array();
        }

        $this->initiateModel('read');
        $this->dbHandle->select('base_course_id, level, credential_1,credential_2');
        $this->dbHandle->where_in('base_course_id',$baseCourseIds);
        return $this->dbHandle->get('base_courses')->result_array();
    }

    /* API to get all the dummy courses */
    public function getAllDummyBaseCourses(){
        $this->initiateModel('read');
        
        $this->dbHandle->select('base_course_id, name, level, credential_1 as credential, is_hyperlocal');
        
        $whereClause['status'] = 'live';
        $whereClause['is_dummy'] = '1';

        $this->dbHandle->where($whereClause);
        return $this->dbHandle->get('base_courses')->result_array();

    }
}