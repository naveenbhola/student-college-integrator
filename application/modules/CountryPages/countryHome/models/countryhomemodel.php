<?php
/*
 * Model    : countryPageModel
 * Module   : CountryPageHome/countryPageHome
 */

   class countryhomemodel extends MY_Model{
      private $CI;
      private $dbHandle;
      private $dbHandleMode;
      
      public function __construct(){
         parent::__construct('CountryHome');
      }
      
      private function initiateModel($mode = 'read'){
         if($this->dbHandle && $this->dbHandleMode == 'write'){
             return ;
         }
         
         $this->dbHandleMode = $mode;
         $this->dbHandle = NULL;
         
         if($mode == 'read'){
             $this->dbHandle = $this->getReadHandle();
         }elseif($mode == 'write'){
             $this->dbHandle = $this->getWriteHandle();
         }
      }
      /*
       * function to get courses, tuple count for a certain category page on a country
       * params : countryId, LDBCourseId, subCategoryId & courseLevel(mandatory)
       */
      public function getCoursesForPopularCourseWidget($params = array())
      {
         if(empty($params))
         {
            return false;
         }
         
         $this->initiateModel('read');
         
         // prepare sql for regular courses
         $this->dbHandle->select('university_id, course_id');
         $this->dbHandle->from('abroadCategoryPageData acpd');
         $this->dbHandle->where('acpd.status','live');
         $this->dbHandle->where_in('acpd.country_id',$params['countryId'],false);
         if($params['LDBCourseId']>1){
            $this->dbHandle->where('acpd.ldb_course_id',$params['LDBCourseId']);
         }
         else{
            $this->dbHandle->where('acpd.sub_category_id',$params['subCategoryId']);
            $this->dbHandle->where('acpd.course_level',$params['courseLevel']);
         }
         // execute
         $result = $this->dbHandle->get()->result_array();
         
         $universities = array();
         $courses      = array();
         foreach($result as $res_row)
         {
            $universities[$res_row['university_id']] = true;
            $courses[$res_row['course_id']] = true;
         }
         
         // now snapshot courses ..
         // commented this section of code as now there are no snapshot courses in shiksha
         /*if($params['LDBCourseId']==1){ // non desired courses only
            $this->dbHandle->select('university_id');
            $this->dbHandle->from('snapshot_courses');
            $this->dbHandle->where('status','live');
            $this->dbHandle->where_in('country_id',$params['countryId'],false);
            $this->dbHandle->where('category_id',$params['subCategoryId']);
            $this->dbHandle->where('course_type',$params['courseLevel']);
            // execute
            $result = $this->dbHandle->get()->result_array();
            foreach($result as $res_row)
            {
               $universities[$res_row['university_id']] = true;
            }
         }*/
         
         return array('tupleCount'=>count($universities), 'courses'=>array_keys($courses));
      }

      public function getCountryCourseOrder($countryId,$desiredCourses){
         $this->initiateModel('read');

          $this->dbHandle->select('ldb_course_id, count(distinct course_id) as count', false);
          $this->dbHandle->from('abroadCategoryPageData');
          $this->dbHandle->where('status','live');
          $this->dbHandle->where_in('ldb_course_id',$desiredCourses);
          $this->dbHandle->where('country_id',$countryId);
          $this->dbHandle->group_by('ldb_course_id');
          $this->dbHandle->order_by('count','desc');
          $res = $this->dbHandle->get()->result_array();

//         $this->dbHandle->select("ldb_course_id",false);
//         $this->dbHandle->from("abroadCategoryPageData");
//         $this->dbHandle->where("status","live");
//         $this->dbHandle->where_in("ldb_course_id",$desiredCourses);
//         $this->dbHandle->where("country_id",$countryId);
//         $this->dbHandle->group_by("ldb_course_id");
//         //$this->dbHandle->order_by("c desc,ldb_course_id");
//         $this->dbHandle->order_by("FIELD(ldb_course_id,'1509','1508','1510')",false);
//         $res = $this->dbHandle->get()->result_array();
//         _p($this->dbHandle->last_query());
         return array_map(function($ele){return $ele['ldb_course_id'];},$res);

      }


      /*
       * get country 's univ count
       */
      public function getCountryUniversityCount($countryId)
      {
         $this->initiateModel('read');
         $this->dbHandle->select("count(distinct university_id) as total",false);
         $this->dbHandle->from("abroadCategoryPageData");
         $this->dbHandle->where("status","live");
         $this->dbHandle->where("country_id",$countryId);
         $res = $this->dbHandle->get()->result_array();
         return $res[0]['total'];
      }

      public function getCountryOverviewWidgetData($countryId){
         $this->initiateModel('read');
         $this->dbHandle->select("*");
         $this->dbHandle->from("abroadCountryHomeWidgetDetails");
         $this->dbHandle->where("status","live");
         $this->dbHandle->where("countryId",$countryId);
         return $this->dbHandle->get()->result_array();
      }
   }
 
?>
