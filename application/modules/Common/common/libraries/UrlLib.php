<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Desc   	: UrlLib to proivide all methods for article like url, genrate article url
 		  based on stream, substream and popular course and bread crumb.
@Param 	: type array 		
@uthor  : akhter
*/
class UrlLib {

	private $CI;
	private $title;
	private $streamId;
	private $substreamId;
	private $popularCourseId = array();
	private $domain;

	function __construct($param)
    {
        $this->CI 		     =  & get_instance();
        $this->title         =  $this->formateTitle($param['title']);
        $this->entityId      =  $param['articleId'];
        $this->streamId      =  $param['stream_id'];
        $this->substreamId   =  $param['substream_id'];
        $this->courseId      =  $param['courseId']; // list of array
        $this->domain        =  !empty($param['domain'])     ? $param['domain']    : SHIKSHA_HOME ;
        $this->urlIdentifier =  !empty($param['routeRule'])  ? $param['routeRule'] : 'blogId';
        $this->moduleName    =  !empty($param['moduleName']) ? $param['moduleName'] : 'articles';
        $this->field_alias   = array('base_course_id'=>'bc','delivery_method'=>'dm','education_type'=>'et');
    }

    function getUrl(){
    	$courseName = $this->getPopularCourse();
        $course     = !empty($courseName['name']) ? '/'.$this->formateTitle($courseName['name']) : '';
    	$hierarchy  = $this->getHierarchy();

    	if($course){
    		$url = $course.'/'.$this->moduleName.'/'.$this->title.'-'.$this->urlIdentifier.'-'.$this->entityId;
    	}else if($hierarchy){
    		$url = $hierarchy.'/'.$this->moduleName.'/'.$this->title.'-'.$this->urlIdentifier.'-'.$this->entityId;
    	}else{
    		$url = '/'.$this->moduleName.'/'.$this->title.'-'.$this->urlIdentifier.'-'.$this->entityId;
    	}
    	return $url;
    }

    function getPopularCourse($courseIds=null){
        $course = array();
        $this->courseId = !empty($this->courseId) ? $this->courseId : $courseIds ;
        if(count($this->courseId)>0 && !empty($this->courseId)){
	    	$this->CI->load->model('blogs/articlemodel');
	    	$pcourse = $this->CI->articlemodel->getPopularCourse($this->courseId);
	    	if(count($pcourse)>1 || empty($pcourse)){
	    		return $course;
	    	}else{
	    		$course['name'] = isset($pcourse[0]['alias']) && !empty($pcourse[0]['alias']) ? $pcourse[0]['alias'] : $pcourse[0]['name'];
                $course['id'] = $pcourse[0]['base_course_id'];
	    	}
    	}
    	return $course;
    }

    function getHierarchy($streamId){

        $this->streamId = (empty($this->streamId) && !empty($streamId)) ? $streamId : $this->streamId;

    	if(!empty($this->streamId) || !empty($this->substreamId)){
    		$this->CI->load->builder('ListingBaseBuilder','listingBase');
	    	$builder = new ListingBaseBuilder();
	    	if(!empty($this->streamId)){
		    	$stremRepoObj = $builder->getStreamRepository();
		    	$streamName   = $stremRepoObj->find($this->streamId);
                if(!empty($streamName)){
                    $streamName   = ($streamName->getAlias()) ? $streamName->getAlias() : $streamName->getName();
                    $streamName   = '/'.$this->formateTitle($streamName);
                }
	    	}
	    	if(!empty($this->substreamId) && $this->substreamId !='none'){
		    	$subStremRepoObj = $builder->getSubstreamRepository();
	    		$substreamName   = $subStremRepoObj->find($this->substreamId);
                if(!empty($substreamName)){
                    $substreamName   = ($substreamName->getAlias()) ? $substreamName->getAlias() : $substreamName->getName();
                    $substreamName   = '/'.$this->formateTitle($substreamName);
                }
	    	}
    	}
    	return $streamName.$substreamName;
    }

    function formateTitle($title, $words=30){
    	return strtolower(seo_url($title, "-", $words));
    }

    /*
    Desc   : this function is used to genrate bread crumb for all article pages and article detail page only
    @Param  : lists of array
    @uthor : akhter
    @type   : return html
    */
    function getBreadCrumb($param){
        $data['param'] = $this->parseThroughUrlGenerationRules($param);
        $this->processBreadCrumbData($data['param']);
        if($param['page'] =='examPage'){
            return $this->CI->load->view('examPages/newExam/breadCrumb', $data, true);    
        }else{
            return $this->CI->load->view('blogs/breadCrumb', $data,true);    
        }
    }

    private function processBreadCrumbData(&$param){
        if(!key_exists('articleTitle', $param) || $param['articleTitle'] === ""){
            return;
        }
        if(array_key_exists('stream_id', $param) && $param['stream_id'] > 0){
            $postData   =   array(  'sips'      =>  array($param['stream_id'])
                                );
            $chpKey = "streamCHPUrl";
        }elseif (array_key_exists('course_id', $param) && $param['course_id'] > 0){
            $postData   =   array(  'bips'      =>  array($param['course_id'])
                                );
            $chpKey = "baseCourseCHPUrl";
        }else{
            return;
        }
        $this->CI->load->library("common/apiservices/APICallerLib");
        $output = $this->CI->apicallerlib->makeAPICall("CHP","/coursehomepage/api/v1/info/getInterlinkingCHPsForUILP","POST","",json_encode($postData),array(),"");
        $output = json_decode($output['output'], true);
        if (!empty($output['data'])){
            $param[$chpKey] = $output['data'][0]['url'];
        }
    }

    function parseThroughUrlGenerationRules($param){
        if(count($param['courseIds'])>0){    
            $pcourse = $this->getPopularCourse($param['courseIds']); // courseIds is the array list of mapping couses of article
            $param['popularCourseName'] = $pcourse['name'];
            $param['course_id']         = $pcourse['id'];
        }
        if(empty($param['primaryHierarchy']) && $param['stream_id'] !=0 && empty($param['streamName'])){
            $res = $this->getStreamSubstreamName($param);
            $param['streamName'] = $res['streamName'];
        }

        if(empty($param['primaryHierarchy']) && $param['substream_id'] !=0 && empty($param['subStreamName'])){
            $res = $this->getStreamSubstreamName($param);
            $param['subStreamName'] = $res['subStreamName'];
        }

        if(!empty($param['primaryHierarchy']) && empty($pcourse)){
            $this->getStreamSubstream($param);
        }
        return $param;
    }

    function getStreamSubstream(&$param){
        if(!empty($param['primaryHierarchy'])){
            $this->CI->load->builder('ListingBaseBuilder','listingBase');
            $builder = new ListingBaseBuilder();
            $obj = $builder->getHierarchyRepository();
            $obj = $obj->getBaseEntitiesByHierarchyId($param['primaryHierarchy'],1); // 1 is used to get name of stream, substream
            $param['streamName']    = $obj[$param['primaryHierarchy']]['stream']['url_name'] ? $obj[$param['primaryHierarchy']]['stream']['url_name'] : $obj[$param['primaryHierarchy']]['stream']['name'];
            $param['stream_id']     = $obj[$param['primaryHierarchy']]['stream']['id'];
            $param['subStreamName'] = $obj[$param['primaryHierarchy']]['substream']['url_name'] ? $obj[$param['primaryHierarchy']]['substream']['url_name'] : $obj[$param['primaryHierarchy']]['substream']['name'];
            $param['substream_id']  = $obj[$param['primaryHierarchy']]['substream']['id'];
        }
    }

    /*
    Desc   : this function is used to get seo detailes for all article pages only
    @Param  : lists of array
    @uthor : akhter
    @type   : return array
    */
    function getSeoDetails($param){
        if(empty($param['streamName'])){
            $res = $this->getStreamSubstreamName($param);            
            $streamName = $res['streamName'];
            
        }
        if(empty($param['subStreamName'])){
            $res = $this->getStreamSubstreamName($param);            
            $subStreamName = $res['subStreamName'];
            
        }
        $param['courseId'] = !empty($param['courseIds']) ? $param['courseIds'] : $param['courseId'];
        if(empty($param['popularCourseName']) && !empty($param['courseId'])){
            $courseName = $this->getCourseName($param['courseId']);
        }
        $streamName    = empty($param['streamName']) ? $streamName : $param['streamName'];
        $subStreamName = empty($param['subStreamName']) ? $subStreamName : $param['subStreamName'];
        $courseName    = ($courseName) ? $courseName : $param['popularCourseName'];
        if($streamName && empty($subStreamName)){
            $titleText = $streamName;
            $desText   = $titleText;
        }else if($streamName && !empty($subStreamName)){
            $titleText = $subStreamName;
            $desText   = $subStreamName.' and '.$streamName;
        }else if($courseName){
            $titleText = $courseName;
            $desText   = $titleText;
        }else{
            $seo['title']       = 'Latest higher education articles | Shiksha.com';
            $seo['description'] = "Read the latest in depth articles on higher education in India. Find out about colleges, courses, exams, careers and much more on Shiksha.com";
            $seo['h1']          =  'Shiksha Articles';
            return $seo;
        }                    
        $seo['title']       = "Latest articles on ".$desText." courses, colleges and exams | Shiksha.com";
        $seo['description'] = "Read the latest in depth articles on ".$desText.", find out about ".$titleText." news, trends, insights and more.";
        $seo['h1']          =  $titleText.' Articles';
        $seo['entityName'] = $titleText;
        return $seo;
    }

    function getStreamSubstreamName($param){
        $this->CI->load->builder('ListingBaseBuilder','listingBase');
        $builder = new ListingBaseBuilder();
        if(!empty($param['stream_id'])){
            $stremRepoObj = $builder->getStreamRepository();
            $streamName   = $stremRepoObj->find($param['stream_id']);
            if(!empty($streamName)){
                $streamName   = ($streamName->getAlias()) ? $streamName->getAlias() : $streamName->getName();
                $res['streamName'] = $streamName;
            }
        }
        if(!empty($param['substream_id'])){
            $subStremRepoObj = $builder->getSubstreamRepository();
            $subStreamName   = $subStremRepoObj->find($param['substream_id']);
            if(!empty($subStreamName)){
                $subStreamName   = ($subStreamName->getAlias()) ? $subStreamName->getAlias() : $subStreamName->getName();
                $res['subStreamName'] = $subStreamName;
            }
        }
        return $res;
    }

    function getCourseName($courseId){
        if(empty($courseId)){
            return;
        }
        $this->CI->load->builder('ListingBaseBuilder','listingBase');
        $builder = new ListingBaseBuilder();
        $baseCourseRepoObj = $builder->getBaseCourseRepository();
        $courseObject = $baseCourseRepoObj->find($courseId);
        $courseName = ($courseObject->getAlias()) ? $courseObject->getAlias() : $courseObject->getName();
        return $courseName;
    }

    function getPopularCourseForUrl($courseIds=null){
        $course = array();
        if(is_array($courseIds) && count($courseIds)>0 && !empty($courseIds)){
            $this->CI->load->model('blogs/articlemodel');
            $pcourse = $this->CI->articlemodel->getPopularCourse($courseIds);
            if(count($pcourse)>1 || empty($pcourse)){
                return $course;
            }else{
                $course['name'] = isset($pcourse[0]['alias']) && !empty($pcourse[0]['alias']) ? $pcourse[0]['alias'] : $pcourse[0]['name'];
                $course['id'] = $pcourse[0]['base_course_id'];
            }
        }
        unset($courseIds);
        return $course;
    }

    function getExamUrl($param){
        $examName   = empty($this->title) ? $this->formateTitle($param['examName']) : $this->title;
        $courseName = $this->getPopularCourseForUrl($param['course']);
        $course     = !empty($courseName['name']) ? '/'.$this->formateTitle($courseName['name']) : '';
        $hierarchy  = $this->getExamStreamSubstream($param);

        if($param['isRootUrl'] == 'Yes'){
            $url = '/exams/'.$examName;
        }else if(is_numeric($param['conductedBy']) && !empty($param['conductedBy'])){
            $this->CI->load->builder("nationalInstitute/InstituteBuilder");
            $instituteBuilder = new InstituteBuilder();
            $instituteRepo = $instituteBuilder->getInstituteRepository();
            $instObj = $instituteRepo->find($param['conductedBy']);    
            if(!empty($instObj) && is_object($instObj)){
                $collegName  = $instObj->getName(); // only use for display
                $listingType = $instObj->getType();
            }
            $listingType = ($listingType == 'institute') ? 'college' : $listingType;
            //$url = '/'.$this->formateTitle($listingType).'/'.$this->formateTitle($collegName).'/exams/'.$examName;
            $url = '/'.$this->formateTitle($listingType).'/'.$this->formateTitle($collegName).'/'.$examName.'-exam';
        }else if(!empty($course)){
            //$url = $course.'/exams/'.$examName;
            $url = $course.'/'.$examName.'-exam';
        }else if(!empty($hierarchy)){
            //$url = $hierarchy.'/exams/'.$examName;
            $url = $hierarchy.'/'.$examName.'-exam';
        }
        
        if(strpos($url, '-exam-exam') !== false) {
          $url = str_replace('-exam-exam', '-exam', $url);
        }
        return $url;
    }

    function getExamStreamSubstream($param){
        if(is_array($param['primaryHierarchy'])){
            $param['primaryHierarchy'] = $param['primaryHierarchy'][0];
        }
        $this->getStreamSubstream($param);
        if(!empty($param['streamName'])){
            $streamName = '/'.$this->formateTitle($param['streamName']);
        }
        if(!empty($param['subStreamName'])){
            $subStreamName = '/'.$this->formateTitle($param['subStreamName']);
        }
        return $streamName.$subStreamName;
    }

    ////
    // Desc    : to get all article page url like stream/substream and basecourse with filter.
    // $param  : array('stream_id'=>X,'substream_id'=>Y,'course'=>Z)
    // $filterData : array('base_course_id'=>X,'delivery_method'=>Y,'education_type'=>Z)
    // @auther : akhter
    ////
    function getAllPageUrl($param,$filterData){

        $res = $this->getPopularCourse(array($param['course']));

        if(empty($param['course'])){
            $res = $this->getStreamSubstreamName($param);
        }

        if(!empty($res['name'])){ // first preority on baseCourse
            $url = SHIKSHA_HOME.'/'.strtolower(seo_url($res['name'], "-", 30)).'/articles-pc-'.$param['course'];
        }else if(!empty($res['streamName']) && empty($res['subStreamName'])){
            $url = SHIKSHA_HOME.'/'.strtolower(seo_url($res['streamName'], "-", 30)).'/articles-st-'.$param['stream_id'];
        }else if(!empty($res['streamName']) && !empty($res['subStreamName'])){
            $url = SHIKSHA_HOME.'/'.strtolower(seo_url($res['streamName'], "-", 30)).'/'.strtolower(seo_url($res['subStreamName'], "-", 30)).'/articles-sb-'.$param['stream_id'].'-'.$param['substream_id'];
        }else{
            $url = SHIKSHA_HOME.'/articles-all';
        }

        $queryParams = '';
        if(!empty($filterData)) {
            $queryParams = $this->getFilterQueryParam($filterData);
            if(!empty($queryParams)){
                $url = $url.'?'.$queryParams;
            }
        }
        return $url;
    }

    function getFilterQueryParam($filter) {
        foreach ($filter as $entity => $value) {
            $entityAlias = $this->field_alias[$entity];
            if(!empty($entityAlias) && !empty($value)) {
                    $queryParams[] = $entityAlias."=".$value;
                }
            }
        $string = implode('&', $queryParams);
        return $string;
    }

    function getBoardUrl($param){
        foreach ($param as $key => $value) {
            if($key != 'articleId' && !empty($value)){
                $urlData[] = $this->formateTitle($value);
            }
        }
        $url = '/'.implode('/', $urlData);
        return $url;
    }
}
?>