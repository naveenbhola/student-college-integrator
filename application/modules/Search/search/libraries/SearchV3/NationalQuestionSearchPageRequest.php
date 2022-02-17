<?php

class NationalQuestionSearchPageRequest {
    public $CI;

    private $tab;
    private $searchKeyword;
    private $pageNumber;
    private $filterBy;
    private $typedKeyword;
    private $pageLimit = 30;
    private $pageLimitMobile = 20;
    private $trackingSearchId;

    public function __construct() {
        $this->CI = & get_instance();

        $this->CI->load->helper('security');
        $this->CI->load->config("nationalCategoryList/nationalConfig");

        $this->CI->load->library("search/SearchV3/NationalSearchPageUrlGenerator");
        $this->searchPageUrlGenerator = new NationalSearchPageUrlGenerator();
        
        $this->field_alias = $this->CI->config->item('FIELD_ALIAS');
        $this->setDataFromUrlParams();
    }

    private function setDataFromUrlParams() {
        $SEARCH_PARAMS_FIELDS_ALIAS = $this->CI->config->item('FIELD_ALIAS');
        
        $this->setSearchKeyword($this->CI->security->xss_clean($_REQUEST[$SEARCH_PARAMS_FIELDS_ALIAS['keyword']]));
        $this->setPageNumber($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['pageNumber'],true));
        $this->setFilterBy($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['filterBy'],true));
        $this->setTrackingKeyId($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['trackingSearchId'],true));
    }

    public function setSearchKeyword($keyword) {
        if(strlen(trim($keyword)) > 0) {
            $this->searchKeyword = trim($keyword);
        }
    }

    public function getSearchKeyword() {
        return $this->searchKeyword;
    }

    public function setFilterBy($filterBy) {
        $this->filterBy = $filterBy;
    }

    public function getFilterBy(){
        return $this->filterBy;
    }

    public function setTrackingKeyId($trackingSearchId) {
        $this->trackingSearchId = $trackingSearchId;
    }

    public function getTrackingKeyId(){
        return $this->trackingSearchId;
    }

    public function setPageNumber($pageNumber){
        if(!empty($pageNumber) && is_numeric($pageNumber)){
            $this->pageNumber = intval($pageNumber);
        }
    }

    public function getPageNumber(){
        return $this->pageNumber;
    }

    public function getCurrentPageNum() {
        if(empty($this->pageNumber))
            $this->pageNumber = 1;

        return $this->pageNumber;
    }

    function getPageLimit() {
        if(isMobileRequest()){
            return $this->pageLimitMobile;
        }else{
            return $this->pageLimit;   
        }
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function getUserId() {
        return $this->userId;
    }

    function setCurrentTab($tab) {
        $this->tab = $tab;
    }

    function getCurrentTab() {
        return $this->tab;
    }

    function getTabURL($data) {
        $postData = array();
        if(empty($data['tab'])) {
            $data['tab'] = $this->tab;
        }

        $postData['searchPage'] = 'question';
        $postData['searchPageTab'] = $data['tab'];
        $postData['keyword'] = $this->getSearchKeyword();
        $postData['pageNumber'] = $data['pageNumber'] ? $data['pageNumber'] : 1;
        
        if(!empty($data['filterBy'])) {
            $postData['filterBy'] = $data['filterBy'];
        }

        $url = $this->searchPageUrlGenerator->createOpenSearchUrl($postData);

        return $url;
    }
}
