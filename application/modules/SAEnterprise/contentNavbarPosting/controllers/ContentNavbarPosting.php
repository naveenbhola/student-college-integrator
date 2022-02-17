<?php

class ContentNavbarPosting extends MX_Controller
{
    /**
     * Class data member declaration section
     */
    private $usergroupAllowed;
    private $contentNavbarPostingLib;
    private $saCMSToolsLib;

    /**
     * Constructor
     */
    public function __construct()
    {
        // load the config
        parent::__construct();
        $this->config->load('studyAbroadCMSConfig');
        // initialize the user group to be allowed to access
        $this->usergroupAllowed = array('saAdmin','saContent','saCMSLead');
        $this->contentNavbarPostingLib = $this->load->library('contentNavbarPosting/ContentNavbarPostingLib');
        // for common tools like abroad cms user validation
        $this->saCMSToolsLib= $this->load->library('saCMSTools/SACMSToolsLib');
    }
    
    /**
     * Purpose : Method to validate the user and do the necessary action(s)
     * Params  : $noRedirectionButReturn : true means code flow will return control but there will be noredirection in case of unauth access
     */
    function cmsAbroadUserValidation($noRedirectionButReturn = false)
    {
        $usergroupAllowed 	= $this->usergroupAllowed;
        $validity 		    = $this->checkUserValidation();
        $returnArr = $this->saCMSToolsLib->cmsAbroadUserValidation($validity, $usergroupAllowed,$noRedirectionButReturn);
        return $returnArr;
    }

    /**
     * Purpose : opens up a form for adding/editing a content navbar
     * Params  : navbar id in case of edit
     * Author  : Saurabh
     */
    public function addEditContentNavbarLinks($navbarId)
    {
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();
        // prepare the display data here
        if($navbarId > 0){
            $displayData['navbarId'] = $navbarId;
            $this->contentNavbarPostingLib->getNavbarDataForEdit($displayData);
            if(count($displayData['navbarDetailsForEdit']) == 0)
            {
                show_404();
            }
        }
        $displayData['formName'] = ENT_SA_EDIT_CONTENT_NAVBAR_LINKS;
        $displayData['selectLeftNav']    = "CONTENT_LINKS";
        // call the view
        $this->load->view('contentNavbarPosting/navbarPostingOverview',$displayData);
    }

    /**
     * Purpose : to show all content navbars
     * Params  : none
     * Author  : Saurabh \_(o_o)_/
     */
    public function viewContentNavbars()
    {
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        // prepare the display date here
        $displayData['formName'] 	= ENT_SA_CONTENT_NAVBARS;
        $displayData['selectLeftNav']   = "CONTENT_LINKS";

        // call the view
        $this->load->view('contentNavbarPosting/navbarPostingOverview',$displayData);
    }

    public function contentNavbarTable()
    {
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();
        //this is to know the call sequence incase of async calls
        $draw = $this->input->post('draw');
        // prepare params for query
        $dataTableParams = array();
        $dataTableParams['start'] = $this->input->post('start');            // limit offset
        $dataTableParams['length'] = $this->input->post('length');          // num of records to be fetched
        $search = $this->input->post('search');                             
        $dataTableParams['search'] = trim($search['value']);                      // search value
    
        $tableResultData = $this->contentNavbarPostingLib->getContentNavbarTableData(array('dataTableParams'=>$dataTableParams));
        $resultRows = array();
        // get records
        if($tableResultData['totalCount'] > 0)                                 
        {
            foreach ($tableResultData['rows'] as $k=>$row)
            {
                $formattedRowData = array(
                            ($dataTableParams['start']+($k+1)),
                            '<p class="cms-associated-cat">'.htmlentities($row['title']).'<br/>'.
                                '<a href="'.ENT_SA_CMS_NAVBARS_PATH.ENT_SA_EDIT_CONTENT_NAVBAR_LINKS.'/'.$row["navbar_id"].'">Edit</a> | '.
                                '<a href="javascript:void(0)" onclick="deleteNavbar('.$row["navbar_id"].');">Delete</a>'.
                            '</p>',
                            '<p class="cms-associated-cat">'.htmlentities(ucfirst($row['content_type'])).'</p>',
                            '<p>'.(date_format(date_create($row['updated_on']),'d M Y')).'</p>'
                            );
                array_push($resultRows,$formattedRowData);
            }
        }
        $returnDataArray = array(
            'draw'              => intval($draw),
            'recordsTotal'      => $tableResultData['totalCount'],
            'recordsFiltered'   => $tableResultData['totalCount'],
            'data'              => $resultRows,
            );

        echo json_encode($returnDataArray);
    }
    /**
     * landing func for ajax that checks if content has not been mapped to any navbar already
     * Note : secretly it also checks if given content id even exists as a given content type
     */
    public function checkIfContentMappedToNavbar()
    {
        $contentType = $this->input->post('contentType',true);
        $contentId = $this->input->post('contentId',true);
        $navbarId = $this->input->post('navbarId',true); // skip checking for this navbarid because this is edit case.
        // get 'em
        $res1 = $this->contentNavbarPostingLib->checkIfContentExists(
                array('contentType'=>$contentType,
                      'contentId'=>array($contentId)));
        if(count($res1) == 0)
        {
            echo "NOTFOUND";
            return false;
        }
        $res2 = $this->contentNavbarPostingLib->checkIfContentMappedToNavbar(
        array('contentType'=>$contentType,
              'contentId'=>array($contentId),
              'navbarId'=>$navbarId));
        echo $res2; // true/ false
    }
    /**
     * submit navbar links
     */
    public function submitContentNavbar()
    {
        $postData = array();
        $userData = $this->cmsAbroadUserValidation(true);
        if(!is_null($userData['error']))
        {
            if($userData['error_type']== 'disallowedaccess'){
                echo json_encode(array('fail'=>'un_auth'));
            }else{
                echo json_encode(array('fail'=>'no_auth'));
            }
            return false;
        }else{
            $postData['userId'] = $userData['userid'];
        }
        $postData['contentType'] = $this->input->post('contentType',true);
        $postData['title'] = trim($this->input->post('title',true));
        $postData['contentLinksData'] = $this->input->post('contentLinksData',true);
        $navbarId = $this->input->post('navbarId',true);
        if(is_numeric($navbarId) && $navbarId>0)
        {
            $postData['navbarId'] = $navbarId;
            $postData['createdOn'] = $this->input->post('createdOn',true);
            $postData['createdBy'] = $this->input->post('createdBy',true);
        }
        // check if navbar exist
        $result = $this->contentNavbarPostingLib->checkIfNavbarExists($postData);
        if($result !== false)
        {
            echo json_encode(array('fail'=>'duplicate_title'));
            return false;
        }
        try{
            $res = $this->contentNavbarPostingLib->submitContentNavbarData($postData);
            echo json_encode(array('pass'=>$res));
        }catch(Exception $e)
        {
            echo json_encode(array('fail'=>'misc'));
        }
    }
    /**
     * delete navbar links 
     * @params : navbarId
     */
    public function deleteContentNavbar($navbarId)
    {
        if(is_null($navbarId) || !($navbarId > 0))
        {
            echo json_encode(array('fail'=>'invalid_request'));
            return false;
        }
        $postData = array();
        $userData = $this->cmsAbroadUserValidation(true);
        if(!is_null($userData['error']))
        {
            if($userData['error_type']== 'disallowedaccess'){
                echo json_encode(array('fail'=>'un_auth'));
            }else{
                echo json_encode(array('fail'=>'no_auth'));
            }
            return false;
        }
        try{
            $res = $this->contentNavbarPostingLib->deleteContentNavbarData($navbarId);
            echo json_encode(array('pass'=>$res));
        }catch(Exception $e)
        {
            echo json_encode(array('fail'=>'misc'));
        }
    }
}
