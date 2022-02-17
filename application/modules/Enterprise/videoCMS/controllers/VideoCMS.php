<?php
/**
 * Purpose : Controller for Video CMS
 * Author 	  : Pranjul Raizada
 * Creation Date : 13-08-2019
 *
 */
class VideoCMS extends MX_Controller
{
    private function _init()
    {
        $this->validateuser = $this->checkUserValidation();
        $this->cmsUserInfo = $this->cmsUserValidation();
        if($this->cmsUserInfo['usergroup'] != 'cms'){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
        }
        $this->vcmsLib = $this->load->library('videoCMS/videoCmsLib');
    }
    
    private function cmsHeaderPart() {
        // prepare the header components
        $headerComponents = array(
                        'css' => array('headerCms', 'footer','common_new'),
                        'js' => array('common','header','VideoCMS'),
                        'jsFooter' =>array('lazyload'),
                        'title' => "",
                        'product' => 'VideoCMS',
                        'displayname' => (isset($this->validateuser[0]['displayname']) ? $this->validateuser[0]['displayname'] : ""),
                        'isOldTinyMceNotRequired' => 1,
                        'prodId' => '1055'
		);

        // tab to be selected
        //$this->cmsUserInfo['prodId'] =   EXAM_PAGES_TAB_ID;
        
        // render the view
        echo $this->load->view('enterprise/headerCMS', $headerComponents,true);
        echo $this->load->view('enterprise/cmsTabs', $this->cmsUserInfo, true);
    }
    
    /** 
    * Purpose : Method to get Video List
    * Params  : $videoId
    */
    function getVideoList(){
        $this->_init();
        $displayData = array();
        $this->cmsHeaderPart();
        $this->load->library('session');
        $displayData['currentPage'] = 1;
        $displayData['activePage'] = 'videoList';
        $filter = array('pageNum' => 1);
        $sorter = array('field' => 'createdOn', 'order' => 'desc');
        $displayData['suggestorPageName'] = 'CMS_suggestors';
        $this->_getVideoListData($displayData, $filter, $sorter);
        $displayData['appliedFilters'] = $filter;
        $this->load->view('videoListPage', $displayData);
    }

    function getVideoListForLayer(){
        $this->_init();
        $displayData = array();
        $displayData['currentPage'] = 1;
        $filter = array('pageNum' => 1);
        $sorter = array('field' => 'createdOn', 'order' => 'desc');
        $this->_getVideoListData($displayData, $filter, $sorter);
        $displayData['appliedFilters'] = $filter;
        $displayData['vcmsType'] = 'layer';
        $html = $this->load->view('videoList', $displayData, true);
        $autosuggestorConfigCMS = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('vcmsAllTagsCMS'));
        $vcmsInstanceConfig = $autosuggestorConfigCMS['vcmsAllTagsCMS']['options'];
        echo json_encode(array('html' => $html, 'autoSuggestorConfig' => $vcmsInstanceConfig));
    }

    function getVideoListAjax($vcmsType = 'layer'){
        $this->_init();
        $collectedFilters = json_decode($this->input->post('filtersApplied', true), true);
        $collectedSorter = json_decode($this->input->post('sorterApplied', true), true);
        $displayData = array();
        if($vcmsType == 'layer'){
            $displayData['vcmsType'] = 'layer';
        }
        $filter = $this->_getAppliedFilters($collectedFilters);
        $sorter = $this->_getAppliedSorter($collectedSorter);
        $displayData['currentPage'] = $filter['pageNum'];
        $this->_getVideoListData($displayData, $filter, $sorter);
        $displayData['appliedFilters'] = $filter;
        $html = $this->load->view('videoListTable', $displayData, true);
        echo json_encode(array('html' => $html, 'totalVideoCount' => $displayData['totalVideoCount']));
    }

    private function _getVideoListData(&$displayData, &$filter, &$sorter){
        $filter['sortBy']['field'] = $sorter['field'];
        $filter['sortBy']['order'] = $sorter['order'];
        $this->vcmsLib->getAllVideoData($displayData, $filter);
        $this->vcmsLib->getAllVcmsFilters($displayData);
    }

    private function _getAppliedSorter($collectedSorter){
        $appliedSorter = array();
        if($collectedSorter['field'] != '' && $collectedSorter['field'] == 'title'){
            $appliedSorter['field'] = 'title';
        }else{
            $appliedSorter['field'] = 'createdOn';
        }
        if($collectedSorter['order'] != '' && $collectedSorter['order'] == 'asc'){
            $appliedSorter['order'] = 'asc';
        }else{
            $appliedSorter['order'] = 'desc';
        }
        return $appliedSorter;
    }

    private function _getAppliedFilters($collectedFilters){
        $appliedFilters = array();
        $appliedFilters['pageNum'] = $collectedFilters['pageNum'] > 1 ? $collectedFilters['pageNum'] : 1;
        if(trim($collectedFilters['title']) != ''){
            $appliedFilters['title'] = trim($collectedFilters['title']);
        }
        if(!empty($collectedFilters['tags'])){
            $appliedFilters['tagIds'] = $collectedFilters['tags'];
        }
        $filterBy = array();
        if($collectedFilters['videoType'] != ''){
            $filterBy['videoType'] = $collectedFilters['videoType'];
        }
        if($collectedFilters['videoSubType'] != ''){
            $filterBy['videoSubType'] = $collectedFilters['videoSubType'];
        }
        if($collectedFilters['streamId'] != ''){
            $filterBy['streamId'] = $collectedFilters['streamId'];
        }
        if($collectedFilters['location'] != ''){
            $location = explode('::', $collectedFilters['location']);
            $filterBy['location']['locationId']   = $location[0];
            $filterBy['location']['locationType'] = $location[1];
        }
        if(!empty($filterBy)){
            $appliedFilters['filterBy'] = $filterBy;
        }
        return $appliedFilters;
    }

    /** 
    * Purpose : Method to open add edit CMS Video form 
    * Params  : $videoId
    */
    function addEditVideoContent($videoId)
    {
        // initialize
        $this->_init();
        $this->cmsHeaderPart();
        if(!empty($videoId)){
            $videoInfo = $this->vcmsLib->getVideoContentById($videoId);
            $displayData['videoInfo'] = ($videoInfo['status'] == 'success' && $videoInfo['data'].length) ? $videoInfo['data'] : '';
            $displayData['videoId']   = $videoId;
        }

        $displayData['activePage'] = 'addEditVideo';
        $displayData['actionType'] = "addVideo";
        $displayData['suggestorPageName'] = 'CMS_suggestors';
        $this->load->view('addEditVideo',$displayData);
    }

    /** 
    * Purpose : Method to save CMS Video form data
    */
    function saveData(){
        $this->_init();
        $data['userId']  = $this->cmsUserInfo['userid'];
        $data['videoId'] = (isset($_POST['videoId']) && !empty($_POST['videoId'])) ? $this->input->post('videoId') : 0;
        $data['title']  = (isset($_POST['title']) && !empty($_POST['title'])) ? $this->input->post('title') : '';
        $data['description'] = (isset($_POST['description']) && !empty($_POST['description'])) ? $this->input->post('description') : '';
        $data['ytVideoId'] = (isset($_POST['ytVideoId']) && !empty($_POST['ytVideoId'])) ? $this->input->post('ytVideoId') : '';
        $data['videoUrl'] = (isset($_POST['videoUrl']) && !empty($_POST['videoUrl'])) ? $this->input->post('videoUrl') : '';
        $data['videoType'] = (isset($_POST['videoType']) && !empty($_POST['videoType'])) ? $this->input->post('videoType') : '';

        $hierArr[] = array('streamId'=>$value, 'substreamId'=>$substream[$key], 'specializationId'=>$spec[$key],'primaryHierarchy'=>$primaryHierarchy);

        if($data['videoType']=='Institute Video'){
            $data['videoSubType'] = (isset($_POST['videoInstituteSubType']) && !empty($_POST['videoInstituteSubType'])) ? $this->input->post('videoInstituteSubType') : '';
        }
        if($data['videoType']=='Exam Video'){
            $data['videoSubType'] = (isset($_POST['videoExamSubType']) && !empty($_POST['videoExamSubType'])) ? $this->input->post('videoExamSubType') : '';   
        }
        $stateList = (isset($_POST['stateList']) && !empty($_POST['stateList'])) ? $this->input->post('stateList') : '';
        $countryList = (isset($_POST['countryList']) && !empty($_POST['countryList'])) ? $this->input->post('countryList') : '';
        $locationCounter = 0;
        foreach ($stateList[0] as $key => $value) {
            $data['videoLocations'][$locationCounter]['locationId'] = $value;
            $data['videoLocations'][$locationCounter]['locationType'] = 'state';
            $locationCounter++;
        }
        foreach ($countryList[0] as $key => $value) {
            $data['videoLocations'][$locationCounter]['locationId'] = $value;
            $data['videoLocations'][$locationCounter]['locationType'] = 'country';
        }
        $videoTags = (isset($_POST['tag']) && !empty($_POST['tag'])) ? $this->input->post('tag') : '';
        foreach ($videoTags[0] as $key => $value) {
            $data['videoTags'][$key]['tagId'] = $value;
            $data['videoTags'][$key]['tagAttachmentType'] = 'manual';
        }

        $data['mailerTitle'] = (isset($_POST['mailerTitle']) && !empty($_POST['mailerTitle'])) ? $this->input->post('mailerTitle') : '';

        $data['mailerSnippet'] = (isset($_POST['mailerSnippet']) && !empty($_POST['mailerSnippet'])) ? $this->input->post('mailerSnippet') : '';

        $stream    = $this->input->post('stream');
        $substream = $this->input->post('subStream');
        $spec      = $this->input->post('specialization');
        $primary   = $this->input->post('primary');
        $hierArr = array();
        foreach ($stream as $key => $value) {
            $primaryHierarchy = ($primary == $key) ? 'yes' : 'no';
            if(empty($substream[$key])) {
                $substream[$key] = 'none';
            }
            if(empty($spec[$key])) {
                $spec[$key] = 'none';
            }
            if(!empty($value)){
                $hierArr[] = array('streamId'=>$value, 'substreamId'=>$substream[$key], 'specializationId'=>$spec[$key],'primaryHierarchy'=>$primaryHierarchy);
            }   
        }
        $this->load->builder('listingBase/ListingBaseBuilder');
        $listingBase   = new ListingBaseBuilder();
        $hierarchyRepo = $listingBase->getHierarchyRepository();
        $hierarchyIds  = $hierarchyRepo->getHierarchiesByMultipleBaseEntities($hierArr);    
	foreach ($hierarchyIds as $key=>$value) {
            $data['videoMappings'][$key]['mappingId'] = $value['hierarchy_id'];
            $hierarchy = ($primary == $key) ? 'primaryHierarchy' : 'hierarchy';
            $data['videoMappings'][$key]['mappingType'] = $hierarchy;
        }

        $returnStatus = $this->vcmsLib->saveVideoData($data);
        if($returnStatus['output']['data']){
            $this->load->library('session');
            $this->session->set_flashdata('sucMsg', 'Video has been successfully saved.');
            redirect('/videoCMS/VideoCMS/getVideoList', 'location', 301);
        }
    }

}
