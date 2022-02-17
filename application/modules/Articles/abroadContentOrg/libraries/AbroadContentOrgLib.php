<?php
class AbroadContentOrgLib
{
    private $CI;
    private $contentOrgModel;
    
    function __construct()
    {
        $this->CI =& get_instance();
        $this->_setDependecies();
    }

    function _setDependecies()
    {
        $this->contentOrgModel = $this->CI->load->model('abroadContentOrg/abroadcontentorgmodel');
    }    
    
    function getContentOrgPageData($stage = 'country',$filterValue) {
        /*
         * Get associated Content Ids and Stage values to this stage..
         */
	$lifeCycleTagsData = $this->contentOrgModel->getDataForLifeCycleTags($stage,$filterValue);
	
	// _p($lifeCycleTagsData); 
        
        /*
         *  Get Top 21 Contents' (articles / guides) info sorted desc on popularity count..
         */
        $contentModel = $this->CI->load->model('blogs/sacontentmodel');        
	$articlesData = $contentModel->getBasicInfoOfContent($lifeCycleTagsData['contentIds'], 21);
        // _p($articlesData);
        return array('lifeCycleTagsData' => $lifeCycleTagsData, 'articlesData' => $articlesData);
   }
    
    public function getContentOrgPageStageDetails($paramString='')
    {
        if($paramString==''){
            show_404_abroad();
	    die();
        }
        $this->CI->config->load('abroadContentOrgConfig');
        $contentOrgConfig = $this->CI->config->item('abroad_content_org_details');
        
        $this->CI->config->load('studyAbroadCommonConfig');
        $contentCycleTagsConfig = $this->CI->config->item('CONTENT_LIFECYCLE_TAGS');
        foreach($contentOrgConfig as $configItem)
        {
	    $pageStages[seo_url_lowercase($configItem['title'])] = 0;
	    }
            foreach($pageStages as $key=>$value)
            {
		similar_text($key,$paramString,$pageStages[$key]);
	    }
            arsort($pageStages);
            $stageUrl = '';
            $pageStagesKey = reset(array_keys($pageStages));
            if($pageStages[$pageStagesKey] > 80)
            {
               $stageUrl = $pageStagesKey;
            }
            foreach($contentOrgConfig as $key=>$data){
			if(seo_url_lowercase($data['title']) == $stageUrl){
			$stageDetail['stageId'] = $key;
                        $stageDetail['stageName'] = $contentCycleTagsConfig[$key]['LEVEL1_VALUE'];
                        $stageDetail['title'] = $data['title'];
                        $stageDetail['summary'] = $data['summary'];
                        $stageDetail['rightSectionHeading'] = $data['rightSectionHeading'];
			break;
			}
		}
            return $stageDetail;
    }
    
    
    function contentOrgStageURL($stageDetail,$redirect=false){
		$stageId = $stageDetail['stageId'];
		$stageUrl = $stageDetail['title'];
		$urlString = $stageUrl.'-stagepage';
		$recommendedUrl = SHIKSHA_STUDYABROAD_HOME."/".seo_url_lowercase($urlString);
        $userEnteredURL = trim(getCurrentPageURLWithoutQueryParams());
		if($userEnteredURL != $recommendedUrl && $redirect){
			redirect($recommendedUrl, 'location', 301);
		}
		return $recommendedUrl;
    }
    
    function validateUrlAndGetStageDetails($paramString)
    {
        $stageDetails = $this->getContentOrgPageStageDetails($paramString);
	if($stageDetails['stageId']=='')
	{
	    show_404_abroad();
	    die();
	}
        $recommendedUrl = $this->contentOrgStageURL($stageDetails,true);
        return  array($stageDetails,$recommendedUrl);
    }
    
    function getUrlsForWidget(){
            $this->CI->config->load('abroadContentOrgConfig');
            $contentOrgConfig = $this->CI->config->item('abroad_content_org_details');
            $urlDetails = array();
            foreach($contentOrgConfig as $key=>$data)
            {
                $urlDetails[$key] = $this->contentOrgStageURL($data,false);
            }
            return $urlDetails;
        } 
}

?>    
