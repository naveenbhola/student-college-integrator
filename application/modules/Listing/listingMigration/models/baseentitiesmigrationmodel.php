<?php 
class Baseentitiesmigrationmodel extends MY_Model {
    function __construct() {
		parent::__construct('Listing');
    }
    
    function getIdbyName($name,$type) {
        return;
        /*
        if(!empty($name))
        {    
            $dbHandle = $this->getReadHandle();
            switch ($type){
            case 'stream':
                $query="Select stream_id from streams where lower(`name`)='".strtolower($name)."'";
                $data = $dbHandle->query($query)->result_array();
                return $data[0]['stream_id'];  
                break;
            case 'substream':
                $query="Select substream_id from substreams where lower(`name`)='".strtolower($name)."'";
                $data = $dbHandle->query($query)->result_array();
                return $data[0]['substream_id'];
                break;
            case 'specialization':
                $query="Select specialization_id from specializations where lower(`name`)='".strtolower($name)."'";
                $data = $dbHandle->query($query)->result_array();
                return $data[0]['specialization_id'];
                break;
            case 'course':
                $query="Select base_course_id from base_courses where lower(`name`)='".strtolower($name)."'";
                $data = $dbHandle->query($query)->result_array();
                return $data[0]['base_course_id'];
                break;
            } 
        }
        */
    }
    
    function addStreams($data){
        return;
        /*
        $dbHandle = $this->getWriteHandle();
        $query = "Insert into streams (`name`,`alias`,`synonym`,`display_order`,`created_on`,`created_by`)values ('$data[name]','$data[alias]','$data[synonym]','$data[display_order]', now(),0);";
        
		$dbHandle->query($query);
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            return 0;
        }
        else {
            return $last_insert_id;    
        }
        */
    }
    
    function addSubstreams($data){
        return;
        /*
        $dbHandle = $this->getWriteHandle();
    	$query = "Insert into substreams (`name`,`alias`,`synonym`,`display_order`,`created_on`,`created_by`,`primary_stream_id`)values"
                . " ('$data[name]','$data[alias]','$data[synonym]','$data[display_order]', now(),0,'$data[primary_stream]');";

        $dbHandle->query($query);
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            return 0;
        }
        else {
            return $last_insert_id;    
        }
        */
    }
    
    function addSpecializations($data){ 
        return;
        /*
        $dbHandle = $this->getWriteHandle();
    	$query = "Insert into specializations (`name`,`alias`,`synonym`,`type`,`created_on`,`created_by`,`primary_stream_id`,`primary_substream_id`)values"
                . " ('$data[name]','$data[alias]','$data[synonym]','$data[type]', now(),0,'$data[primary_stream]','$data[primary_substream]');";
        
        $dbHandle->query($query);
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            return 0;
        }
        else {
            return $last_insert_id;    
        }
        */
    }
    
    function addBasecourses($data)
    {
        return;
        /*
        $dbHandle = $this->getWriteHandle();
        $query = "Insert into base_courses (`name`,`alias`,`synonym`,`level`,`credential_1`,`credential_2`,`is_popular`,`is_hyperlocal`,`is_executive`,`created_on`,`created_by`)values"
                . " ('$data[name]','$data[alias]','$data[synonym]','$data[courselevel]','$data[credential_0]','$data[credential_1]',$data[is_popular],$data[is_hyperlocal],$data[is_executive], now(),0);";
        
        $dbHandle->query($query);
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            return 0;
        }
        else {
            return $last_insert_id;    
        }
        */
     }
    
    function addEntityHierarchyMapping($data) {
        return;
        /*
        $dbHandle = $this->getWriteHandle();
        $query = "Insert into entity_hierarchy_mapping (`entity_id`,`entity_type`,`stream_id`,"
                . "`substream_id`,`specialization_id`,`status`,`created_on`,`created_by`)values"
                . " ('$data[entity_id]','$data[entity_type]','$data[stream_id]',"
                . "$data[substream_id],$data[specialization_id],'live',now(),0);";

        $dbHandle->query($query);
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            return 0;
        }
        else {
            return $last_insert_id;    
        }
        */
    }
    
     function getOldSubCategoryId($name,$categoryid)
    {
        return;
        /*
        $dbHandle = $this->getReadHandle();
        
        $query="SELECT boardId,parentId from categoryBoardTable where lower(name)='".strtolower($name)."' and parentId='$categoryid' and flag='national' and isOldCategory='0'";
        
        $data = $dbHandle->query($query)->result_array();
        return $data;
        */
    }

    function getOldSpecializationId($specializationId,$categoryid,$subcategoryid){
        return;
        /*
        if(empty($specializationId))
            return;
        $dbHandle = $this->getReadHandle();
        
        $query="SELECT SpecializationId from tCourseSpecializationMapping t inner join LDBCoursesToSubcategoryMapping l where
        l.categoryID='$subcategoryid' and l.ldbCourseID=t.SpecializationId and t.SpecializationId='$specializationId'
         and t.status='live' and l.status='live' and scope='india' and isEnabled='1' and t.CategoryId='$categoryid'";
        
        $data = $dbHandle->query($query)->result_array();
        return $data;
        */
    }

    function addBaseEntityMigration($data){
       return;
       /*
        $dbHandle = $this->getWriteHandle();
        $query = "Insert into base_entity_mapping (`oldcategory_id`,`oldsubcategory_id`,`oldspecializationid`,`stream_id`,"
                . "`substream_id`,`specialization_id`,`base_course_id`,`education_type`,
                `delivery_method`,`credential`,`level`)values"
                . " ('$data[oldcategory_id]','$data[oldsubcategory_id]','$data[oldspecialization_id]','$data[stream]',"
                . "'$data[substream]','$data[specialization]','$data[baseCourse]','$data[educationtype]','$data[deliverymethod]','$data[credential]','$data[courselevel]')";
        //_p($query);
        $dbHandle->query($query);
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            return 0;
        }
        else {
            return $last_insert_id;    
        }
        */
    }
   
} ?>

