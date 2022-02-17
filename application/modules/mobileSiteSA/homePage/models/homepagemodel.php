<?php
class homepagemodel extends MY_Model{
    
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct(){
        parent::__construct('Listing');
    }
    
    /*
     * Author   : Abhinav
     * Purpose  : To get DB-Handle with specific mode(read/write)
     * 
     */
    public function initiateModel($mode = 'read'){
        if($this->dbHandle && $this->dbHandleMode == 'write'){
            return;
        }
        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($this->dbHandleMode = 'read'){
            $this->dbHandle = $this->getReadHandle();
        }elseif($this->dbHandleMode == 'write'){
            $this->dbHandle = $this->getWriteHandle();
        }
    }


    /*
     *  Author : Rahul Bhatnagar
     *  Function : Get countries of all courses in a particular ldb course or category.
     *  Params : Either LDB ID or Category ID. For categoryId, optionally provide courseLevel as well.
     */
    public function getCountriesForCourse($ldbCourseId, $categoryId, $courseLevel = 'Bachelors'){
        if($courseLevel === "Certificate - Diploma"){
            global $certificateDiplomaLevels;
            $courseLevel = $certificateDiplomaLevels;
        }
        $this->initiateModel('read');
        $this->dbHandle->select("distinct(acpd.country_id) as country_id, ct.name as name");
        $this->dbHandle->where('acpd.status','live');
        $this->dbHandle->from('abroadCategoryPageData acpd');
        $this->dbHandle->join('countryTable ct','ct.countryId = acpd.country_id','inner');
        
        if($ldbCourseId == 1){
            $this->dbHandle->where('category_id',$categoryId);
            if(is_array($courseLevel)){
                $this->dbHandle->where_in('course_level',$courseLevel);
            }else{
                $this->dbHandle->where('course_level',$courseLevel);
            }
            
        }elseif($categoryId == 1){
            $this->dbHandle->where('ldb_course_id',$ldbCourseId);
        }else{
            return array();
        }
        
        $dbResult = $this->dbHandle->get()->result_array();
        return $dbResult;
    }
    
    /*
     * Author   : Abhinav
     * Purpose  : To get trending course for given date on basis of view-count
     * Params   : Mandatory=(DateToCheckFor: Date for which data is to be picked),Optional=(Limit: Limit count)
     */
    public function getTrendingCourse($dataToCheckFor,$lowerLimit=0,$upperLimit=15){
        $this->initiateModel('read');
        $this->dbHandle->select('SQL_CALC_FOUND_ROWS listingId,sum(viewCount) as viewCount',FALSE);
        $this->dbHandle->from('abroadListingViewCountDetails');
        $this->dbHandle->where(array('listingType' => 'course',
                                      'viewDate' => $dataToCheckFor));
        $this->dbHandle->group_by('listingId');
        $this->dbHandle->order_by('viewCount','desc');
        $this->dbHandle->limit($upperLimit,$lowerLimit);
        $result['data'] = $this->dbHandle->get()->result_array();
        $this->initiateModel('read');
        $this->dbHandle->select('found_rows() rows_available');
        $result['rows_available'] = $this->dbHandle->get()->result_array();
        return $result;
        
    }
}