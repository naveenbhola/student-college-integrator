<?php 
class ReCatScriptModel extends MY_Model {
    function __construct() {
		parent::__construct('Listing');
    }
    
    function getIdbyName($name,$type) {
            
        $dbHandle = $this->getReadHandle();
        switch ($type){
        case 'stream':
            $query="Select stream_id from streams where lower(`name`)= ?";
            $data = $dbHandle->query($query, array(strtolower($name)))->result_array();
            return $data[0]['stream_id'];  
           break;
        case 'substream':
            $query="Select substream_id from substreams where lower(`name`)= ?";
            $data = $dbHandle->query($query, array(strtolower($name)))->result_array();
            return $data[0]['substream_id'];
            break;
        case 'specialization':
            $query="Select specialization_id from specializations where lower(`name`)= ?";
            $data = $dbHandle->query($query, array(strtolower($name)))->result_array();
            return $data[0]['specialization_id'];
            break;
        case 'course':
            $query="Select base_course_id from base_courses where lower(`name`)= ?";
            $data = $dbHandle->query($query, array(strtolower($name)))->result_array();
            return $data[0]['base_course_id'];
            break;
            
     }    
    }
    
    function addStreams($data){
        $dbHandle = $this->getWriteHandle();
        $query = "Insert into streams (`name`,`alias`,`synonym`,`display_order`,`created_on`,`created_by`)values (?, ?, ?, ?, now(),0);";
        //_p($query);
		$dbHandle->query($query, array($data['name'], $data['alias'], $data['synonym'], $data['display_order']));
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            _p('some error in Stream'. $data['name']);
            return 0;
        }
        else {
            return $last_insert_id;    
        }
        
        
    }
    
    function addSubstreams($data){
        $dbHandle = $this->getWriteHandle();
    	$query = "Insert into substreams (`name`,`alias`,`synonym`,`display_order`,`created_on`,`created_by`,`primary_stream_id`)values"
                . " (?, ?, ?, ?, now(),0, ?);";
       // _p($query);
        $dbHandle->query($query, array($data['name'], $data['alias'], $data['synonym'], $data['display_order'], $data['primary_stream']));
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            _p('some error in Sub Stream'. $data['name']);
            return 0;
        }
        else {
            return $last_insert_id;    
        }
    }
    
    function addSpecializations($data){ 
        $dbHandle = $this->getWriteHandle();
    	$query = "Insert into specializations (`name`,`alias`,`synonym`,`type`,`created_on`,`created_by`,`primary_stream_id`,`primary_substream_id`)values"
                . " (?, ?, ?, ?, now(),0, ?, ?);";
        //_p($query);
        $dbHandle->query($query, array($data['name'], $data['alias'], $data['synonym'], $data['type'], $data['primary_stream'], $data['primary_substream']));
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            _p('some error in Specialization'. $data['name']);
            return 0;
        }
        else {
            return $last_insert_id;    
        }
    }
    
    function addBasecourses($data)
    {
        $dbHandle = $this->getWriteHandle();
        $query = "Insert into base_courses (`name`,`alias`,`synonym`,`level`,`credential`,`is_popular`,`is_hyperlocal`,`is_executive`,`created_on`,`created_by`)values"
                . " (?, ?, ?, ?, ?, ?, ?, ?, now(),0);";
        //_p($query);
        $dbHandle->query($query, array($data['name'],$data['alias'],$data['synonym'],$data['courselevel'],$data['credential'],$data['is_popular'],$data['is_hyperlocal'],$data['is_executive']));
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            _p('some error in Base Course'. $data['name']);
            return 0;
        }
        else {
            return $last_insert_id;    
        }
     }
    
    function addEntityHierarchyMapping($data) {
        $dbHandle = $this->getWriteHandle();
        $query = "Insert into entity_hierarchy_mapping (`hierarchy_id`,`entity_id`,`entity_type`,`stream_id`,"
                . "`substream_id`,`specialization_id`,`status`,`created_on`,`created_by`)values"
                . " (?, ?, ?, ?, ?, ?,'live',now(),1);";
        //_p($query);
        $dbHandle->query($query, array($data['hierarchy_id'], $data['entity_id'],$data['entity_type'], $data['stream_id'], $data['substream_id'],$data['specialization_id']));
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            _p('some error in Base Course'. $data['name']);
            return 0;
        }
        else {
            return $last_insert_id;    
        }
        
    }
    
     function getOldSubCategoryId($name,$categoryid)
    {
        $dbHandle = $this->getReadHandle();
        
        $query="SELECT boardId,parentId from categoryBoardTable where lower(name)= ? and parentId= ? and flag='national'";
          // _p($query);
        $data = $dbHandle->query($query, array(strtolower($name), $categoryid))->result_array();
        return $data;
    }

    function getOldSpecializationId($specializationName,$courseName,$categoryid){
    
        $dbHandle = $this->getReadHandle();
        
        $query="SELECT SpecializationId from tCourseSpecializationMapping where lower(SpecializationName)= ? and lower(CourseName)= ? and status='live' and scope='india' and CategoryId= ?";
           
        $data = $dbHandle->query($query, array(strtolower($specializationName), strtolower($courseName), $categoryid))->result_array();
        return $data;
    }

    function addBaseEntityMigration($data){
       
        $dbHandle = $this->getWriteHandle();
        $query = "Insert into base_entity_mapping (`oldcategory_id`,`oldsubcategory_id`,`oldspecializationid`,`stream_id`,"
                . "`substream_id`,`specialization_id`,`base_course_id`,`education_type`,
                `delivery_method`,`credential`,`level`)values"
                . " (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        //_p($query);
        $dbHandle->query($query, array($data['oldcategory_id'] ,$data['oldsubcategory_id'],$data['oldspecialization_id'],$data['stream'], $data['substream'] , $data['specialization'], $data['baseCourse'],$data['educationtype'], $data['deliverymethod'], $data['credential'] ,$data['courselevel']));
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            _p('some error in Base Course'. $data['name']);
            return 0;
        }
        else {
            return $last_insert_id;    
        }
    }

    function getOldCourses($start,$end)
    {
        $dbHandle = $this->getReadHandle();
        $query="SELECT c.fees_value,c.fees_unit,c.course_id,c.courseTitle,c.institute_id,c.course_type,c.course_level,c.institute_id,c.course_order
            FROM shiksha.course_details c join shiksha.institute i on i.institute_id=c.institute_id 
            where i.institute_type='Academic_Institute' and  c.status='live' and i.status='live'  order by c.id asc limit ?, ?;";
            _p($query);
        $data = $dbHandle->query($query, array((int)$start, (int)$end))->result_array();
        return $data;
    }
    function migratetoNewCourse($data){
        if(empty($data)) return;
       $query=implode("'),('", $data);
      // $query="(".$query.")";
      
        $dbHandle = $this->getWriteHandle();
        $query = "Insert into courses (`course_id`,`name`,`parent_id`,`parent_type`,`primary_institute_id`,`course_order`
            ,`course_type`,`course_variant`,`executive`,`twinning`,`integrated`,`dual`,`education_type`,`subscription_id`
            ,`expiry_date`,`pack_type`,`client_id`,`delivery_method`,`status`,`created_on`,`created_by`,`updated_on`,`updated_by`) values"
                . "('". $query."')";
        _p($query);
        $dbHandle->query($query);
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            _p('some error in migarting new Course'. $data['course_id']);
            return 0;
        }
        else {
            return $last_insert_id;    
        }
        
    }

    function getlistingMainAttributes($course_id_csv){
        if(empty($course_id_csv)) return;
        $dbHandle = $this->getReadHandle();
        $query="SELECT username,pack_type,subscriptionId,listing_type_id,expiry_date,last_modify_date,submit_date from listings_main where status='live' and listing_type_id in ($course_id_csv) and listing_type='course'";
        _p($query);
        $data = $dbHandle->query($query)->result_array();
        return $data;
    }

    function getEligibleExams($course_id_csv){
if(empty($course_id_csv)) return;
        $dbHandle = $this->getReadHandle();
        $query="SELECT typeId,examId,marks, marks_type from listingExamMap where status='live' and typeId in ($course_id_csv) and typeOfMap='required' and type='course'";
        _p($query); 
        $data = $dbHandle->query($query)->result_array();
        return $data;   
    }

    function migrateExamData($data){
        if(empty($data)) return;
         $query=implode("'),('", $data);
      
        $dbHandle = $this->getWriteHandle();
        $query = "Insert into course_eligible_exam_score (`course_id`,`exam_id`,`exam_name`,`value`,`unit`,`status`,`created_on`,`created_by`,`updated_on`,`updated_by`) values"
                . "('". $query."')";
        _p($query);
        $dbHandle->query($query);
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            _p('some error in migarting new Course'. $data['course_id']);
            return 0;
        }
        else {
            return $last_insert_id;    
        }
    }

    function migratefeesData($data){
        if(empty($data)) return;
         $query=implode("'),('", $data);
      
        $dbHandle = $this->getWriteHandle();
     

        $query = "Insert into course_fees (`course_id`,`fees_value`,`fees_unit`,`fees_type`,`category`,`period`,`status`,`created_on`,`created_by`,`updated_on`,`updated_by`) values"
                . "('". $query."')";
        _p($query);
        $dbHandle->query($query);
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            _p('some error in migarting new Course'. $data['course_id']);
            return 0;
        }
        else {
            return $last_insert_id;    
        }
    }
    function getOldCourseTypeData($course_id_csv)
    {
        if(empty($course_id_csv)) return;
        $dbHandle = $this->getReadHandle();
        $query="select clientCourseID,b.parentId as categoryID, `boardId` as subcategory ,SpecializationId from 
        clientCourseToLDBCourseMapping c,LDBCoursesToSubcategoryMapping l,
        categoryBoardTable b, tCourseSpecializationMapping t 
        where c.LDBCourseID=l.ldbCourseID and l.categoryID=b.boardId and l.ldbCourseID=t.specializationId 
        and clientCourseID in ($course_id_csv) and c.status='live'";
        _p($query); 
        $data = $dbHandle->query($query)->result_array();
        return $data;   

    }
    function getNewCourseTypeData($data)
    {
        if(empty($data)) return;
        $dbHandle = $this->getReadHandle();
        $query= "select * from base_entity_mapping where oldcategory_id= ? and oldsubcategory_id= ? and oldspecializationid= ?";
        $arr['course_type'] = $dbHandle->query($query, array($data['categoryID'], $data['subcategory'], $data['SpecializationId']))->result_array();
                return $arr; 

    }
    function migrateCourseTypeData($data){
        if(empty($data)) return;
        $query=implode("'),('", $data);
        $dbHandle = $this->getWriteHandle();
        $query = "Insert into course_type_information (`course_id`,`type`,`credential`,`course_level`,`base_course`,`stream_id`,
            `substream_id`,`specialization_id`,`primary_hierarchy`,`status`,`created_on`,`created_by`,`updated_on`,`updated_by`) values"
                . "('". $query."')";
        _p($query);
        $dbHandle->query($query);
        $last_insert_id = $dbHandle->insert_id();
        if(empty($last_insert_id)){
            _p('some error in migarting new Course'. $data['course_id']);
            return 0;
        }
        else {
            return $last_insert_id;    
        }
    }
} ?>

