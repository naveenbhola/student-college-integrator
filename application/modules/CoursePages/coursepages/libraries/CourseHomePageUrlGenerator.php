<?php

class CourseHomePageUrlGenerator  {

    private $streamId = 0;
    private $subStreamId = 0;
    private $isCoursePopular = 0;
    private $educationType = 0;
    private $deliveryMethod = 0;
    private $baseCourseId = 0;
    private $urlParts = array();
    private $popularCourseName;
    private $returnFormat = 'url';
    private $courseNickName;
    private $generateFullUrl = 0;
    public function __construct() {
       $this->CI = & get_instance();
    }

    private function _loadDependenciesForStreamSubStream() {
        $this->CI->load->builder('ListingBaseBuilder', 'listingBase');
        $this->listingBuilder = new ListingBaseBuilder();
    }

    public function getCourseHomePageUrl() {
        $bucket = $this->_getBucketForCombination();
        if ($this->generateFullUrl) {
            array_push($this->urlParts, SHIKSHA_HOME);
        }
        switch ($bucket) {
            case 'popularCourse' :
                $this->_getUrlStructureForPopularCourseBucket();
                break;
            case 'streamSubStream' :
                $this->_getUrlForStreamSubStreamBucket();
                break;
            case 'stream' :
                $this->_getUrlStructureForStreamBucket();
                break;
            default :
        }
        array_push($this->urlParts, $this->courseNickName);
     
        if ($this->returnFormat == 'url') {
            return implode('/', $this->urlParts);
        } else {
            return $this->urlParts;
        }
    }

    private function _getUrlStructureForStreamBucket() {
        $this->_loadDependenciesForStreamSubStream();
        $streamRepo = $this->listingBuilder->getStreamRepository();
        $streamObject = $streamRepo->find($this->streamId);
        $streamName = $streamObject->getName();
        if ($this->streamId > 0 && $this->baseCourseId > 0 && ($this->educationType>0 || $this->deliveryMethod>0)) {
            // home=> stream => alias
            array_push($this->urlParts, $streamName);
        } elseif ($this->streamId > 0 && $this->baseCourseId > 0) {
            // home =>stream => alias
            array_push($this->urlParts, $streamName);
        } elseif ($this->streamId > 0) {
            // home => alias
        }
    }

    private function _getUrlForStreamSubStreamBucket() {
        $this->_loadDependenciesForStreamSubStream();
        $streamRepo = $this->listingBuilder->getStreamRepository();
        $subStreamRepo = $this->listingBuilder->getSubstreamRepository();
        $streamObject = $streamRepo->find($this->streamId);
        $streamName = $streamObject->getName();
        if ($this->subStreamId > 0 && $this->baseCourseId > 0 && ($this->educationType>0 || $this->deliveryMethod>0)) {
            //home => stream => substream => alias
            array_push($this->urlParts, $streamName);
            $subStreamObject = $subStreamRepo->find($this->subStreamId);
            $subStreamName = $subStreamObject->getName();
            array_push($this->urlParts, $subStreamName);
        } elseif ($this->subStreamId > 0 && $this->baseCourseId > 0) {
            //home => stream => substream => alias
            array_push($this->urlParts, $streamName);
            $subStreamObject = $subStreamRepo->find($this->subStreamId);
            $subStreamName = $subStreamObject->getName();
            array_push($this->urlParts, $subStreamName);
        } elseif ($this->subStreamId > 0) {
            //home => stream =>alias
            array_push($this->urlParts, $streamName);
        }
    }

    private function _getUrlStructureForPopularCourseBucket($courseHomePageId) {
        if ($this->isCoursePopular && ($this->educationType > 0 || $this->deliveryMethod > 0)) {
            //home => popular course name => alias
            array_push($this->urlParts, $this->popularCourseName);
        } else if ($this->isCoursePopular > 0) {
            //home => alias
        }
    }

    private function _getBucketForCombination() {
        if ($this->isCoursePopular == 1) {
            return 'popularCourse';
        }
        if ($this->streamId > 0 && $this->subStreamId > 0) {
            return 'streamSubStream';
        }
        if ($this->streamId > 0) {
            return 'stream';
        }
    }

    function getStreamId() {
        return $this->streamId;
    }

    function getSubStreamId() {
        return $this->subStreamId;
    }

    function getIsCoursePopular() {
        return $this->isCoursePopular;
    }

    function getEducationType() {
        return $this->educationType;
    }

    function getDeliveryMethod() {
        return $this->deliveryMethod;
    }

    function getBaseCourseId() {
        return $this->baseCourseId;
    }

    function setStreamId($streamId) {
        $this->streamId = $streamId;
    }

    function setSubStreamId($subStreamId) {
        $this->subStreamId = $subStreamId;
    }

    function setIsCoursePopular($isCoursePopular) {
        $this->isCoursePopular = $isCoursePopular;
    }

    function setEducationType($educationType) {
        $this->educationType = $educationType;
    }

    function setDeliveryMethod($deliveryMethod) {
        $this->deliveryMethod = $deliveryMethod;
    }

    function setBaseCourseId($baseCourseId) {
        $this->baseCourseId = $baseCourseId;
    }

    function getReturnFormat() {
        return $this->returnFormat;
    }

    function setReturnFormat($returnFormat) {
        $this->returnFormat = $returnFormat;
    }
    function getPopularCourseName() {
        return $this->popularCourseName;
    }

    function getCourseNickName() {
        return $this->courseNickName;
    }

    function getGenerateFullUrl() {
        return $this->generateFullUrl;
    }

    function setPopularCourseName($popularCourseName) {
        $this->popularCourseName = $popularCourseName;
    }

    function setCourseNickName($courseNickName) {
        $this->courseNickName = $courseNickName;
    }

    function setGenerateFullUrl($generateFullUrl) {
        $this->generateFullUrl = $generateFullUrl;
    }
    
    public function getCourseHomePageUrlViaCourseHomePageArray($courseHomePageArray,$returnFormat='url'){
        $this->setBaseCourseId($courseHomePageArray['baseCourseId']);
        $this->setCourseNickName($courseHomePageArray['Name']);
        $this->setDeliveryMethod($courseHomePageArray['deliveryMethod']);
        $this->setIsCoursePopular($courseHomePageArray['isPopular']);
        $this->setPopularCourseName($courseHomePageArray['baseCourseName']);
        $this->setStreamId($courseHomePageArray['streamId']);
        $this->setSubStreamId($courseHomePageArray['substreamId']);
        $this->setReturnFormat($returnFormat);
        $this->setEducationType($courseHomePageArray['educationType']);
        $courseHomePageUrl=$this->getCourseHomePageUrl();
        return $courseHomePageUrl;
    }

    /*
        Input:
        ------
        @courseHomePageData : Array with the parameters (stream/substream/specialization/popular course/education type/delivery method) which are available.

        Output : Complete URL of the page.
    */
      
    public function getUrlByParams($courseHomePageData) {
        
        if(!is_array($courseHomePageData) || count($courseHomePageData) == 0){
            return '';
        }
        $ci = &get_instance();


        $validParams = array('stream_id' => "streamId",'substream_id' => "substreamId",'specialization_id' => "specializationId",'base_course_id' => "basecourseId",'education_type' => "educationtypeId",'delivery_method' => "deliverymethodId");

        $hierachyRequest = array();

        foreach ($courseHomePageData as $cKey => $cValue) {
                $tempArray[$validParams[$cKey]] = $cValue;
        }

        $hierachyRequest[] = $tempArray;

        $chpLibrary = $ci->load->library('chp/ChpLibrary');
        $result = $chpLibrary->getChpUrlBasedOnHierarchies($hierachyRequest);

        if(!empty($result))
        {
            return addingDomainNameToUrl(array('url' => $result,'domainName' => SHIKSHA_HOME));
        }

        return '';

        /*$model = $ci->load->model('coursepages/coursepagemodel');
        $this->cache = PredisLibrary::getInstance();
        $cacheKey  = 'courseHomePageTable#';
        $courseHomePageTable = $this->cache->getMemberOfString($cacheKey);
        if(empty($courseHomePageTable)){
            $courseHomePageTable = $model->getCourseHomePageTable();
            $courseHomePageTable = json_encode($courseHomePageTable);
            $expireInSeconds = 30*24*60*60;
            $this->cache->addMemberToString($cacheKey,$courseHomePageTable,$expireInSeconds,FALSE,FALSE);
        }
        $courseHomePageTable = json_decode($courseHomePageTable,true);
        $seo_url = array();
        $validParams = array('stream_id','substream_id','base_course_id','education_type','delivery_method');
    
        foreach($courseHomePageData as $key => $value){
            if(!in_array($key, $validParams)){
                unset($courseHomePageData[$key]);
            }
        }
        foreach($validParams as $param){
            if(empty($courseHomePageData[$param])){
                $courseHomePageData[$param] = 0;
            }
        }
        _p($courseHomePageData);
        foreach($courseHomePageTable as $key=>$data){
            $validUrl = true;
            foreach($validParams as $params){
                if($data[$params] != $courseHomePageData[$params]){
                    $validUrl = false;
                    break;
                }
            }
            if($validUrl){
                $seo_url[] = $data['seo_url'];
            }
        }
        unset($courseHomePageData);
        unset($validParams);

        if(count($seo_url) != 1){
            return '';
        }
        return SHIKSHA_HOME.$seo_url[0];*/
    }




}
