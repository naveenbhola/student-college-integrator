<?php
class ContentNavbarPostingLib
{
    private $CI;
    private $contentNavbarPostingModel;
    
    function __construct()
    {
        $this->CI =& get_instance();
        $this->_setDependecies();
    }

    function _setDependecies()
    {
        //$this->CI->config->load('studyAbroadCMSConfig');
        $this->CI->load->model('contentNavbarPostingModel');
        $this->contentNavbarPostingModel  = new contentnavbarpostingmodel();
    }
	
	/*
     * get list of navbars (paginated)
     */
    public function getContentNavbarTableData($data)
    {
        return $this->contentNavbarPostingModel->getContentNavbars($data);
    }

    /**
     * checks if given content id is valid & not mapped to any other navbar
     */
    public function checkIfContentMappedToNavbar($data)
    {
        if(count($data['contentId'])==0 || is_null($data['contentType']))
        {
            return false;
        }
        else{
            $result = $this->contentNavbarPostingModel->checkIfContentMappedToNavbar($data);
            return ($result == 0?false:true);
        }
    }
    /**
     * function that saves content navbar data to db
     */
    public function submitContentNavbarData($data)
    {
        return $this->contentNavbarPostingModel->submitContentNavbarData($data);
    }
    /**
     * function that deletes content navbar data from db
     * @param  : navbarId
     */
    public function deleteContentNavbarData($navbarId)
    {
        return $this->contentNavbarPostingModel->deleteContentNavbarData($navbarId);
    }
    /**
     * function to get navbar data for edit mode
     */
    public function getNavbarDataForEdit(& $data)
    {
        $result = array();
        if(!is_null($data['navbarId']) && $data['navbarId']>0)
        {
            $result = $this->contentNavbarPostingModel->getNavbarDataById($data['navbarId']);
        }
        $data['navbarDetailsForEdit'] = $result;
    }
    /**
     * check if given contentId(s) exist as given content type
     * @param: content type & array of content ids
     */
    public function checkIfContentExists($data)
    {
        $result = $this->contentNavbarPostingModel->checkIfContentExists($data['contentType'],$data['contentId']);
        if(count($result) == 0)
        {
            return array();
        }
        return array_filter(array_map(function($a){ return $a['content_id']; },$result));
    }
    /**
     * function that checks if another navbar exists with same title
     * @param: array containing navbar title [ & navbar id for edit case]
     * @return : false if it doesn't , true if it does
     */
    public function checkIfNavbarExists($data)
    {
        $result = $this->contentNavbarPostingModel->checkIfNavbarExists($data);
        return (count($result) == 0?false:true);
    }
}
