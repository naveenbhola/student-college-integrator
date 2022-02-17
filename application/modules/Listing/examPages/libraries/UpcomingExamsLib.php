<?php
class UpcomingExamsLib
{
    private $CI;
    
    function __construct() {
        $this->CI =& get_instance();
        $this->ExamPageCache = $this->CI->load->library('examPages/cache/ExamPageCache');
    }

    public function getUpcomingExams($id,$entity = "course"){
    	if($id == null || !is_numeric($id)){
    		return null;
    	}
        if($this->ExamPageCache && !$disableCache) {
            $examTuples = $this->ExamPageCache->getUpcomingExamDates($id,$entity);
            if (!empty($examTuples)) {
                return $examTuples;
            }
        }
        $this->CI->load->config('examPages/eventCategory');
        $this->exampagelib = $this->CI->load->library('examPages/ExamPageLib');
        $this->exampagemodel = $this->CI->load->model('examPages/exampagemodel');

        $urlLibObj  = $this->CI->load->library('common/UrlLib',array('stream_id'=>$id));

    	if($entity === "course"){
            $entityIds = $id;
            $entityType = array("course");
            $course      = $urlLibObj->getPopularCourse(array($id));
            $urlParam    = '/'.$urlLibObj->formateTitle($course['name']).'/exams-pc-'.$course['id'];
    	}
    	else if( $entity === "stream"){
    		$this->CI->load->builder('ListingBaseBuilder', 'listingBase');
			$this->listingBaseBuilder = new ListingBaseBuilder();

			$hierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();
			$entityIds = $hierarchyRepository->getHierarchyIdByBaseEntities($id,"any","any");
            $entityType = array("primaryHierarchy","hierarchy");
            $urlParam    = $urlLibObj->getHierarchy($id).'/exams-st-'.$id;
    	}
    	else
    		return null;

        $latestDateData = $this->exampagemodel->getUpcomingExamDates($entityIds,$entityType);
        $examIds = array();
        $groupIds = array();
        $examTuples = array();
        foreach ($latestDateData as $key => $value) {
            $examIds[] = $value['exam_id'];
            $groupIds[] = $value['groupId'];
            $examTuple = array();
            $examTuple['exam_id'] = $value['exam_id'];
            $examTuple['eventCategoryType'] = $this->_getCategoryType($value['eventCategory']);
            $examTuple['page_id'] = $value['page_id'];
            $examTuple['groupId'] = $value['groupId'];
            $examTuple['endDate'] = $this->_getModifiedDateType(substr($value['endDate_and_eventName'], 0,10));
            $examTuple['startDate'] = $this->_getModifiedDateType(substr($value['endDate_and_eventName'], 11,21));
            $examTuple['eventname'] = substr($value['endDate_and_eventName'],22);
            $examTuples[$value['exam_id']] = $examTuple;

        }
        $this->CI->load->builder('examPages/ExamBuilder');
        $examBuilder    = new ExamBuilder();
        $examRepository = $examBuilder->getExamRepository();
        $examObjects = $examRepository->findMultiple($examIds);
        $groupObjects = $examRepository->findMultipleGroup($groupIds);
        foreach ($examIds as $key => $value) {
            $examObject = $examObjects[$value];
            if(isset($examObject)){
                $groupId = $examTuples[$value]["groupId"];
                $groupObj = $groupObjects[$groupId];
                if(!empty($groupObj) && is_object($groupObj)){
                    $entitiesMappedToGroup = $groupObj->getEntitiesMappedToGroup();
                    $examTuples[$value]["group_year"] = $entitiesMappedToGroup['year'][0];
                }
                $allAvailableSections = $examRepository->findContent($groupId,"sectionname");
                $eventCategoryType = $examTuples[$value]["eventCategoryType"];
                $examTuples[$value]["exam_name"] = $examObject->getName();
                $examTuples[$value]["exam_url"] = $examObject->getUrl();
                $possibleChildPages = $this->_getPossibleChildPages($examTuples[$value]["exam_name"],$examTuples[$value]["groupId"],$eventCategoryType,$allAvailableSections);
                $examTuples[$value]["possible_child_pages"] = $possibleChildPages;
            }
        }
        $tupleWidget = array();
        $tupleWidget['examTuples'] = $examTuples;
        $tupleWidget['allExamUrl'] = $urlParam;
        if(!empty($examTuples)){
            $this->ExamPageCache->storeUpcomingExamDates($id,$entity,$tupleWidget);
            return $tupleWidget;
        }
    }
    private function _getModifiedDateType($date){
        $endDate = $date;
        $endDay = substr($endDate, 8,2);
        $endMonthNum = substr($endDate,5,2);
        $endMonthFullName = date('F', mktime(0, 0, 0, $endMonthNum, 10));
        $endMonth = substr($endMonthFullName, 0,3);
        $endYear = substr($endDate, 2,2);
        return $endDay." ".$endMonth."'".$endYear;
    }
    private function _getCategoryType($eventCategoryId){
        $config = $this->CI->config->item("events");
        $data = $config[$eventCategoryId];
        if (($pos = strpos($data, "|")) !== FALSE) { 
           $substring = substr($data, $pos+2); 
        }
        return $substring;
    }

    private function _getPossibleChildPages($examName,$groupId,$eventCategoryType,$allAvailableSections){
        $count = 0;
        $possibleChildPages = array();
        if($eventCategoryType === "Post Exam"){
            $postExam = array('importantdates' => "Dates",
                              'results'  =>"Results",
                              'answerkey'=>"Answer Key",
                              'cutoff'=>"Cut Off",
                              'counselling'=>"Counselling");
            foreach ($postExam as $key => $value) {
                if(in_array($key, $allAvailableSections['sectionname'])){
                    $count++;
                    $possibleChildPages[$key]["page_name"] = $value;
                    $possibleChildPages[$key]["page_url"] = $this->exampagelib->getExamPageUrl($examName, $key);
                }
                if($count === 3)
                    break;
            }
        }
        else{
            $preExam = array("importantdates" => "Dates",
                             "applicationform"=>"Application Form",
                             "syllabus"=>'Syllabus',
                             'samplepapers'=>"Question Papers",
                             'admitcard'=>"Admit Card",
                             'cutoff'=>"Cut Off",
                             'preptips' => 'Prep Tips');
            foreach ($preExam as $key => $value) {
                if(in_array($key, $allAvailableSections['sectionname'])){
                    $count++;
                    $possibleChildPages[$key]["page_name"] = $value;
                    $possibleChildPages[$key]["page_url"] = $this->exampagelib->getExamPageUrl($examName, $key);
                }
                if($count === 3)
                    break;
            }
        }
        return $possibleChildPages;
    }
}
?>