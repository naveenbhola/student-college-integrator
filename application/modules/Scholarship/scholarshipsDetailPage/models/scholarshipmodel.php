<?php
class scholarshipmodel extends MY_Model {
    private $dbHandle = '';
    private $scholarshipChildren = array('baseData','expensesCovered','attributes','eligibility','application','deadLine');
    function __construct() {
        parent::__construct('ShikshaApply');
    }

    private function initiateModel($operation='read'){
        if($operation=='read'){ 
            $this->dbHandle = $this->getReadHandle();
        }else{
            $this->dbHandle = $this->getWriteHandle();
        }		
    }
    public function getScholarshipIdByUrl($scholarshipUrl){
        $this->initiateModel('read');
        $this->dbHandle->select('scholarshipId');
        $this->dbHandle->from('scholarshipBaseTable');
        $this->dbHandle->where('seoUrl',$scholarshipUrl);
        $this->dbHandle->where_in('status',array('live','history'));
        $result = $this->dbHandle->get()->result_array();
        $result = reset($result);
        return $result;
    }
    public function getURLByScholarshipId($scholarshipId){
        if(empty($scholarshipId)){
            return;
        }
        $this->initiateModel('read');
        $this->dbHandle->select('seoUrl');
        $this->dbHandle->from('scholarshipBaseTable');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where('scholarshipId',$scholarshipId);
        $result = $this->dbHandle->get()->result_array();
        $result = reset($result);
        return $result['seoUrl'];
    }
    public function getData($scholarshipId){
        global $useMasterForScholarshipData;
        if($useMasterForScholarshipData==true){
            $this->initiateModel('write');
        }
        else{
            $this->initiateModel('read');
        }
        Contract::mustBeNumericValueGreaterThanZero($scholarshipId,'Scholarship ID');
        $scholarshipData[$scholarshipId] = array();
        $scholarshipIds = array($scholarshipId);
        $this->_getChildrenData($scholarshipData,$scholarshipIds);
        $this->_unsetUnusedData($scholarshipData);
        return reset($scholarshipData);
    }
    public function getDataForMultipleScholarships($scholarshipIds){
        global $useMasterForScholarshipData;
        if($useMasterForScholarshipData==true){
            $this->initiateModel('write');
        }
        else{
            $this->initiateModel('read');
        }
        Contract::mustBeNonEmptyArrayOfIntegerValues($scholarshipIds,'Scholarship IDs');
        foreach($scholarshipIds as &$scholarshipId){
            $scholarshipData[$scholarshipId] = array();
        }
        $this->_getChildrenData($scholarshipData,$scholarshipIds);
        $this->_unsetUnusedData($scholarshipData);
        return $scholarshipData;
    }

    private function _unsetUnusedData(&$scholarshipData){
        foreach ($scholarshipData as $scholarshipId =>&$value){
            unset($value['id']);
            unset($value['modifiedBy']);
            unset($value['addedAt']);
            unset($value['addedBy']);
            unset($value['status']);
            if(empty($value['scholarshipId'])){
                unset($scholarshipData[$scholarshipId]);
            }
        }        
    }
    //These children are different than sections of object and children in repository. These children are table wise children. 
    // Table holding same type of data is a child.
    private function _getChildrenData(&$scholarshipData,&$scholarshipIds){
        foreach($this->scholarshipChildren as $child){
            switch ($child) {
                case 'baseData':
                    $this->_getScholarshipBaseData($scholarshipData,$scholarshipIds);
                    break;
                case 'expensesCovered':
                    $this->_getScholarshipExpensesCovered($scholarshipData,$scholarshipIds);
                    
                    break;
                case 'attributes':
                    $this->_getScholarshipAttributesData($scholarshipData,$scholarshipIds);
                    break;
                case 'eligibility':
                    $this->_getScholarshipEligibilityData($scholarshipData,$scholarshipIds);
                    
                    break;
                case 'application':
                    $this->_getScholarshipApplicationData($scholarshipData,$scholarshipIds);
                    
                    break;
                case 'deadLine':
                    $this->_getScholarshipDeadLineData($scholarshipData,$scholarshipIds);
                    break;
                default:
                    break;
            }
        }
    }
    private function _getScholarshipBaseData(&$scholarshipData,&$scholarshipIds){
        $this->dbHandle->select('*');
        $this->dbHandle->from('scholarshipBaseTable');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $res = $this->dbHandle->get()->result_array();
        $noOfRows = count($res);
        for($i=0;$i<$noOfRows;$i++){
            $scholarshipData[$res[$i]['scholarshipId']] = array_merge($scholarshipData[$res[$i]['scholarshipId']],$res[$i]);
        }
    }
    
    private function _getScholarshipExpensesCovered(&$scholarshipData,&$scholarshipIds){
        $this->dbHandle->select('*');
        $this->dbHandle->from('scholarshipExpensesDetails');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $res = $this->dbHandle->get()->result_array();
        $noOfRows = count($res);
        for($i=0;$i<$noOfRows;$i++){
            $scholarshipData[$res[$i]['scholarshipId']]['expensesCovered'][] = $res[$i]['expenseCovered'];
        }
    }
    
    private function _getScholarshipAttributesData(&$scholarshipData,&$scholarshipIds){
        $this->dbHandle->select('*');
        $this->dbHandle->from('scholarshipAttributesMapping');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $res = $this->dbHandle->get()->result_array();
        $noOfRows = count($res);
        for($i=0;$i<$noOfRows;$i++){
            switch($res[$i]['attributeType']){
                case 'department': // these all have multiple values
                case 'course':
                case 'courseLevel':
                case 'subcategory':
                case 'specialization':
                case 'specialRestriction':
                case 'intakeYear':
                    $scholarshipData[$res[$i]['scholarshipId']][$res[$i]['attributeType']][] = $res[$i]['attributeValue'];
                    break;
                case 'category':
                    $scholarshipData[$res[$i]['scholarshipId']]['parentCategory'][] = $res[$i]['attributeValue'];
                    break;
                default:
                    $scholarshipData[$res[$i]['scholarshipId']][$res[$i]['attributeType']] = $res[$i]['attributeValue'];
                    break;
            }
        }
        
        foreach ($scholarshipData as $scholarshipId => &$scholarshipDetail) {
            sort($scholarshipDetail['intakeYear']);
        }
        
    }
    
    private function _getScholarshipEligibilityData(&$scholarshipData,&$scholarshipIds){
        $this->dbHandle->select('*');
        $this->dbHandle->from('scholarshipEligibilityBaseTable');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $baseEligibilityTableData = $this->dbHandle->get()->result_array();
        $noOfRows = count($baseEligibilityTableData);
        for($i=0;$i<$noOfRows;$i++){
            $scholarshipData[$baseEligibilityTableData[$i]['scholarshipId']] = array_merge($scholarshipData[$baseEligibilityTableData[$i]['scholarshipId']],$baseEligibilityTableData[$i]);
        }
        
        
        $this->dbHandle->select('*');
        $this->dbHandle->from('scholarshipExamsEligibility');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $examsEligibilityData = $this->dbHandle->get()->result_array();
        $noOfRows = count($examsEligibilityData);
        for($i=0;$i<$noOfRows;$i++){
            $scholarshipData[$examsEligibilityData[$i]['scholarshipId']]['scholarshipExamsData'][] = array('examId'=>$examsEligibilityData[$i]['examId'],'cutoff'=>$examsEligibilityData[$i]['cutoff']);
        }
        $this->dbHandle->select('*');
        $this->dbHandle->from('scholarshipEducationEligibility');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $educationData = $this->dbHandle->get()->result_array();
        $noOfRows = count($educationData);
        for($i=0;$i<$noOfRows;$i++){
            $scholarshipData[$educationData[$i]['scholarshipId']]['scholarshipEducationData'][] = array('educationLevel'=>$educationData[$i]['educationLevel'],'scoreType'=>$educationData[$i]['scoreType'],'score'=>$educationData[$i]['score']);
        }
    }
    private function _getScholarshipApplicationData(&$scholarshipData,&$scholarshipIds){
        $this->dbHandle->select('*');
        $this->dbHandle->from('scholarshipApplicableCountries');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $applicableCountries = $this->dbHandle->get()->result_array();
        $noOfRows = count($applicableCountries);
        for($i=0;$i<$noOfRows;$i++){
            $scholarshipData[$applicableCountries[$i]['scholarshipId']]['applicableCountries'][] = $applicableCountries[$i]['countryId'];
        }
        
        
        $this->dbHandle->select('*');
        $this->dbHandle->from('scholarshipApplicationDocsBaseTable');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $applicationDocsBaseData = $this->dbHandle->get()->result_array();
        $noOfRows = count($applicationDocsBaseData);
        for($i=0;$i<$noOfRows;$i++){
            $scholarshipData[$applicationDocsBaseData[$i]['scholarshipId']]= array_merge($scholarshipData[$applicationDocsBaseData[$i]['scholarshipId']],$applicationDocsBaseData[$i]);
        }
        $this->dbHandle->select('*');
        $this->dbHandle->from('scholarshipRequiredApplicationDocs');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $requiredApplicationDocs = $this->dbHandle->get()->result_array();
        $noOfRows = count($requiredApplicationDocs);
        for($i=0;$i<$noOfRows;$i++){
            $scholarshipData[$requiredApplicationDocs[$i]['scholarshipId']]= array_merge($scholarshipData[$requiredApplicationDocs[$i]['scholarshipId']],$requiredApplicationDocs[$i]);
        }
        
        $this->dbHandle->select('*');
        $this->dbHandle->from('scholarshipApplicableNationality');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $applicableNationalities = $this->dbHandle->get()->result_array();
        $noOfRows = count($applicableNationalities);
        for($i=0;$i<$noOfRows;$i++){
            $scholarshipData[$applicableNationalities[$i]['scholarshipId']]['applicableNationalities'][] = $applicableNationalities[$i]['countryId'];
        }
    }
    private function _getScholarshipDeadLineData(&$scholarshipData,&$scholarshipIds){
        $this->dbHandle->select('*');
        $this->dbHandle->from('scholarshipDeadlineBaseTable');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $deadlineBaseData = $this->dbHandle->get()->result_array();

        $noOfRows = count($deadlineBaseData);
        for($i=0;$i<$noOfRows;$i++){
            $deadlineBaseData[$i]['deadLineType'] = $deadlineBaseData[$i]['type'];
            unset($deadlineBaseData[$i]['type']);
            $scholarshipData[$deadlineBaseData[$i]['scholarshipId']]= array_merge($scholarshipData[$deadlineBaseData[$i]['scholarshipId']],$deadlineBaseData[$i]);
        }
        
        $this->dbHandle->select('*');
        $this->dbHandle->from('scholarshipDeadlineImpDatesDetails');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $importantDates = $this->dbHandle->get()->result_array();
        
        $noOfRows = count($importantDates);
        for($i=0;$i<$noOfRows;$i++){
            $scholarshipData[$importantDates[$i]['scholarshipId']]['importantDates'][] = array('impDateHeading'=>$importantDates[$i]['impDateHeading'],'impDate'=>$importantDates[$i]['impDate'],'impDateDescription'=>$importantDates[$i]['impDateDescription']);
        }
    }

    public function saveFeedbackData($dataArr) {
        
        if(empty($dataArr)){
            return;
        }
        
        $this->initiateModel('write');
        $this->dbHandle->insert('scholarshipFeedback', $dataArr);
        return $this->dbHandle->insert_id();
    }

    public function getCourseLevelAndCategories($courseIds){
        if(empty($courseIds)){
            return array();
        }
        $this->initiateModel('read');
        $this->dbHandle->select('category_id, course_level, country_id');
        $this->dbHandle->from('abroadCategoryPageData');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('course_id',$courseIds);
        $this->dbHandle->group_by('category_id, course_level');
        $res = $this->dbHandle->get()->result_array();
        $result = array();
        foreach ($res as $value) {
            $result['categories'][$value['category_id']] = $value['category_id'];
            $result['courseLevels'][$value['course_level']] = $value['course_level'];
            $result['countries'][$value['country_id']] = $value['country_id'];
        }
        //echo $this->dbHandle->last_query();
        return $result;
    }
}
?>