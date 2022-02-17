<?php
/**
 * Controller for Customer/Content Delivery MIS.
*/

class cdMIS extends MX_Controller
{
	private $trackingLib;

	function __construct()
	{
		parent::__construct();
		$this->trackingLib = $this->load->library('trackingMIS/trackingMISCommonLib');
        $this->cdMISLib = $this->load->library('trackingMIS/cdMISlib');
	}

	private function _loadDependecies() {
		$data['userDataArray'] = reset($this->trackingLib->checkValidUser());
		$data['misSource'] = "CD";
		$this->load->config('cdTrackingMISConfig');		
		$data['leftMenuArray'] = $this->config->item("leftMenuArray");		
		return $data;
	}
    private function _loadDependeciesForContent()
    {
        $data['userDataArray'] = reset($this->trackingLib->checkValidUser());
        $data['misSource'] = "Content-Delivery";
        $this->load->config('cdTrackingMISConfig');     
        $data['leftMenuArray'] = $this->config->item("Content-leftMenuArray");      
        return $data;
    }
    //below function is main function for customer delivery MIS
	function customerDeliveryDashBoard($page='DomesticOverview')
	{		    
		$data = $this->_loadDependecies();

        $data['teamName'] = 'Customer Delivery';
        if($page == 'DomesticOverview')
        {
            $data['reportHeading'] = 'Leads and MR Delivery In India';
            $data['ajaxUrl'] = "/trackingMIS/cdMIS/getDomesticOverview";
            $data['overview'] = 1;
        }
        else if($page == 'byInstitute')
        {
            $data['reportHeading'] = 'Based on Institute';
            $data['ajaxUrl'] ="/trackingMIS/cdMIS/getDomesticCustomerDeliveryByInstitute";
            $data['graphUrl'] = '/trackingMIS/cdMIS/getDomesticLinearDataByInstitute';
            $data['paidHeading'] = 'Response Type';
        }
        else if($page == 'bySubcatId')
        {
            $data['reportHeading'] = 'Based on Subcategory';
            $data['ajaxUrl'] ="/trackingMIS/cdMIS/getDomesticCustomerDeliveryBySubcat";    
            $data['graphUrl'] = "/trackingMIS/cdMIS/getDomesticLinearChartDataBySubcat";
            $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
            $data['subcatId'] = $this->getSubcategory();
            $data['stateNames'] = $cdmismodel->getStateNames();
            $data['cityNames'] = $this->getCityNames();
            $data['paidHeading'] = 'Response Type';
        }
        else if($page == 'StudyAbroadOverview')
        {
            $data['reportHeading'] = 'Leads and MR Delivery In Study Abroad';
            $data['ajaxUrl'] = "/trackingMIS/cdMIS/get_sa_customer_delivery_overview";
            $data['overview'] = 1;
        }
        else if($page == 'byUniversity')
        {
            $data['reportHeading'] = 'Based on University';
            $data['ajaxUrl'] ="/trackingMIS/cdMIS/getStudyAbroadCustomerDeliveryByUniversity";
            $data['graphUrl'] = "/trackingMIS/cdMIS/getStudyAbroadLinearChartDataByUniversity";
            $data['paidHeading'] = 'Response Type';
        }
        else if($page == 'bySubcatId_SA')
        {
            $data['reportHeading'] = 'Based on Subcategory';
            $data['ajaxUrl'] ="/trackingMIS/cdMIS/getStudyAbroadCustomerDeliveryBySubcat";    
            $data['graphUrl'] = "/trackingMIS/cdMIS/getStudyAbroadLinearChartDataBySubcat";
            $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
            $data['subcatId'] = $this->getSubcategory('studyabroad');
            $data['countryNames'] = $cdmismodel->getCountryNames();
            $data['paidHeading'] = 'Response Type';
        }
        else if($page == 'ActualDelivery')
        {
            $data['reportHeading'] = 'Based on client';
            $data['ajaxUrl'] = "/trackingMIS/cdMIS/get_actual_delivery_toClient";
        }
        $data['page'] = $page;
 		$this->load->view('misTemplate', $data);
 	}
    
    
    // below function is main function for content Delivery MIS Dashboard
    function contentDeliveryDashBoard($page='contentDomesticOverview')
    {
        $data = $this->_loadDependeciesForContent();
        if($page == 'contentDomesticOverview')
        {
            $data['reportHeading'] = 'Domestic Articles';
            $data['ajaxUrl'] = "/trackingMIS/cdMIS/getDomesticContentOverview";
            $data['overview'] = 1;
        }   
        else if($page == 'content-bySubcatId')
        {
            $data['reportHeading'] = 'Based on Subcategory';
            $data['ajaxUrl'] ="/trackingMIS/cdMIS/getContentMISDataBasedOnSubCategory";
            $data['subcatId'] = $this->getSubcategory();
            $data['authorNames'] = $this->cdMISLib->getAuthorNames();
            $data['paidHeading'] = 'Traffic Source';
            $data['reportHeading'] = 'Domestic Articles';
        }
        else if($page == 'byArticle' )
        {
            $data['reportHeading'] = 'Based on Article Id';
            $data['ajaxUrl'] ="/trackingMIS/cdMIS/getArticleDataForContentMIS";
            //$data['authorNames'] = $this->cdMISlib->getAuthorNames();
            $data['paidHeading'] = 'Traffic Source';
        }
        else if($page == 'contentStudyAbroadOverview')
        {
            $data['reportHeading'] = 'Study Abroad Articles';
            $data['ajaxUrl'] = "/trackingMIS/cdMIS/getStudyAbroadContentOverview";
            $data['overview'] = 1;
        }
        else if($page == 'SA-Content-bySubcatId')
        {
            $data['reportHeading'] = 'Based on Subcategory';
            $data['ajaxUrl'] ="/trackingMIS/cdMIS/getContentMISDataBasedOnSubCategory_SA";
            $data['subcatId'] = $this->getSubcategory('studyabroad','content');
            $data['authorNames'] = $this->cdMISLib->getSAAuthorNames();
            $data['paidHeading'] = 'Traffic Source';
        }
        else if($page == 'SA-Content-byArticleId')
        {   
            $data['reportHeading'] = 'Based on Article Id';
            $data['ajaxUrl'] ="/trackingMIS/cdMIS/getArticleDataForContentMIS_SA";
            //$data['authorNames'] = $this->cdMISlib->getAuthorNames();
            $data['paidHeading'] = 'Traffic Source';
        }
        else if($page == 'NationalDiscussions')
        {
            $data['reportHeading'] = 'Based on Subcategory';
            $data['ajaxUrl'] ="/trackingMIS/cdMIS/getDomesticDiscussionsDataForContentDelivery";
            $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
            $data['subcatId'] = $this->getSubcategory('national');
            $userId = array('5213424','4272799','4408658','5043232');// Internal Discussion Author UserIds
            $data['authorNames'] = $cdmismodel->getAuthorNames($userId);
            $data['paidHeading'] = 'Traffic Source';   
        }
        else if($page == 'DiscussionOverview')
        {
            $data['reportHeading'] = 'Discussions';
            $data['ajaxUrl'] = "/trackingMIS/cdMIS/get_discussionData_overview";
            $data['overview'] = 1;
        }
        else if($page == 'byDiscussionId')
        {
            $data['reportHeading'] = 'Based on Discussion Id';
            $data['ajaxUrl'] = "/trackingMIS/cdMIS/get_discussionData_by_id";
            $data['paidHeading'] = 'Traffic Source';
        }
        $data['page'] = $page;
        $data['teamName'] = 'Content Delivery';
        $this->load->view('misTemplate', $data);   
    }

 	function getCoursesInInstitute()
 	{
 		$instituteId = (isset($_POST ['instituteId']) )? $this->input->post('instituteId'):'';
        $instituteIdArray = explode(',',$instituteId);
 		$cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $institute_course_mapping = array();
        foreach ($instituteIdArray as $key => $value) {
            if($value == 0)
            {
                continue;
            }
            else
            {
                $instituteName = $cdmismodel->getInstituteName($value);

                $result=$cdmismodel->getCoursesInInstitute($value);    
                $institute_course_mapping[$value.'-'.$instituteName] = array();
                $i = 0;
                foreach ($result as $inst => $courses) {
                    $institute_course_mapping[$value.'-'.$instituteName][$i++] = array('courseId'=>$courses['course_id'],
                                                                      'courseTitle'=>$courses['courseTitle']);
                 }
            }
        }
 		//error_log('===========result courses'.'/'.print_r($result,true));
 		if(count($institute_course_mapping)>0)
 				echo json_encode($institute_course_mapping);
 		else
 			echo "None";

 	}    
    function getDomesticContentDatabaseDataBySubcat()
    {
        $subCategoryId          = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $authorId               = isset($_POST['authorId'])?$this->input->post('authorId'):'';
        $source                 = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate              = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate                = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate      = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate        = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise               = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;


        $commentResultData    = $this->getCommentDataOnArticle($subCategoryId,'',$authorId,$source,$trafficSource,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
        $replyResultData      = $this->getReplyDataOnArticle($subCategoryId,'',$authorId,$source,$trafficSource,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
        $articleResultData    = $this->getArticleDataBasedOnSubCatId($subCategoryId,$authorId,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);

        $linearChartResult['commentResult']      = $commentResultData;
        $linearChartResult['replyResult']        = $replyResultData;
        $linearChartResult['ArticleResult']      = $articleResultData;
        echo json_encode($linearChartResult);
    }
    function getDomesticContentElasticSearchDataBySubcat()
    {
        $subCategoryId          = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $authorId               = isset($_POST['authorId'])?$this->input->post('authorId'):'';
        $source                 = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate              = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate                = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate      = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate        = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise               = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;


        $subcatArray = array('subCategoryId' => $subCategoryId, 'pageName' => 'articlePage','authorId' => $authorId);
        $trafficData          = $this->getTrafficData('','','','',$source,$subcatArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$trafficSource);
    
        $engagementResultData = $this->getEngagementDataForCustomerDelivery('','','','',$source,$subcatArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$trafficSource);
        $dashboardResult['topLineChartResults']['trafficData']      = $trafficData;
        $dashboardResult['topLineChartResults']['pageviewData']     = $engagementResultData['pageviewData'];
        $dashboardResult['topLineChartResults']['bounceRate']       = $engagementResultData['bounceRateData'];
        $dashboardResult['topLineChartResults']['avgSessionDuration'] = $engagementResultData['avgSessionDuration'];
        $dashboardResult['topLineChartResults']['avgPagePerSession']  = $engagementResultData['avgPagePerSession'];
        $dashboardResult['topLineChartResults']['exitRateData']       = $engagementResultData['exitRateData'];
        $dashboardResult['pieChartResult']['Traffic-SourceWise']      = $trafficData['Traffic-SourceWise'];
        $dashboardResult['pieChartResult']['Traffic-DeviceWise']      = $trafficData['Traffic-DeviceWise'];
        $dashboardResult['pieChartResult']['PageView-DeviceWise']     = $engagementResultData['pageviewData-DeviceWise'];
        echo json_encode($dashboardResult);
    }
    function getDomesticContentMapDataBySubcat()
    {
        
        $subCategoryId          = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $authorId               = isset($_POST['authorId'])?$this->input->post('authorId'):'';
        $source                 = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate              = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate                = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate      = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate        = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise               = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;

        $subcatArray = array('subCategoryId' => $subCategoryId, 'pageName' => 'articlePage','authorId' => $authorId);

        $geosplit             = $this->getGeoSplitData('','','','',$subcatArray,$source,$startDate,$endDate,$trafficSource);
        echo $geosplit;
    }
    function getStudyAbroadContentDatabaseDataBySubcat()
    {
        $subCategoryId      = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $authorId           = isset($_POST['authorId'])?$this->input->post('authorId'):'';
        $source             = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource      = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate          = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate            = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate  = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate    = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise           = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;

        $commentResultData  = $this->getCommentDataOnArticle_SA($subCategoryId,'',$authorId,$source,$trafficSource,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
        $replyResultData    = $this->getReplyDataOnArticle_SA($subCategoryId,'',$authorId,$source,$trafficSource,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
        $articleResultData  = $this->getArticleDataBasedOnSubCatId_SA($subCategoryId,$authorId,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);

        $linearChartResult['commentResult']      = $commentResultData;
        $linearChartResult['replyResult']        = $replyResultData;
        $linearChartResult['ArticleResult']      = $articleResultData;
        echo json_encode($linearChartResult);
    }
    function getStudyAbroadContentElasticSearchDataBySubcat()
    {
        
        $subCategoryId      = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $authorId           = isset($_POST['authorId'])?$this->input->post('authorId'):'';
        $source             = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource      = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate          = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate            = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate  = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate    = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise           = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;

        $subcatArray = array('subCategoryId' => $subCategoryId,'pageName' => 'articlePage','authorId' => $authorId);
        $engagementResultData = $this->getEngagementDataForCustomerDelivery('','','','',$source,$subcatArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$trafficSource,'yes');
        $trafficData          = $this->getTrafficData('','','','',$source,$subcatArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$trafficSource,'yes');


        $dashboardResult['topLineChartResults']['trafficData']      = $trafficData;
        $dashboardResult['topLineChartResults']['pageviewData']     = $engagementResultData['pageviewData'];
        $dashboardResult['topLineChartResults']['bounceRate']       = $engagementResultData['bounceRateData'];
        $dashboardResult['topLineChartResults']['avgSessionDuration'] = $engagementResultData['avgSessionDuration'];
        $dashboardResult['topLineChartResults']['avgPagePerSession']  = $engagementResultData['avgPagePerSession'];
        $dashboardResult['topLineChartResults']['exitRateData']       = $engagementResultData['exitRateData'];
        $dashboardResult['pieChartResult']['Traffic-SourceWise']      = $trafficData['Traffic-SourceWise'];
        $dashboardResult['pieChartResult']['Traffic-DeviceWise']      = $trafficData['Traffic-DeviceWise'];
        $dashboardResult['pieChartResult']['PageView-DeviceWise']     = $engagementResultData['pageviewData-DeviceWise'];
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadContentMapDataBySubcat()
    {
        $subCategoryId      = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $authorId           = isset($_POST['authorId'])?$this->input->post('authorId'):'';
        $source             = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource      = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate          = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate            = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate  = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate    = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise           = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;

        $subcatArray = array('subCategoryId' => $subCategoryId,'pageName' => 'articlePage','authorId' => $authorId);
        $geosplit           = $this->getGeoSplitData('','','','',$subcatArray,$source,$startDate,$endDate,$trafficSource,'yes');
        echo $geosplit;
    }
    function getArticleDataForDomestic_Database()
    {
        $articleId              = isset($_POST['articleId'])?$this->input->post('articleId'):'';
        $source                 = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate              = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate                = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate      = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate        = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise               = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        
        $articleIdArray         = explode(',', $articleId);


        $commentResultData      = $this->getCommentDataOnArticle('',$articleIdArray,'',$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
        $replyResultData        = $this->getReplyDataOnArticle('',$articleIdArray,'',$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
        $linearChartResult['commentResult'] = $commentResultData;
        $linearChartResult['replyResult']   = $replyResultData;
        echo json_encode($linearChartResult);
    }
    function getArticleDataForDomestic_ElasticSearch()
    {
        $articleId              = isset($_POST['articleId'])?$this->input->post('articleId'):'';
        $source                 = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate              = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate                = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate      = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate        = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise               = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        
        $articleIdArray         = explode(',', $articleId);
        $engagementResultData   = $this->getEngagementDataForCustomerDelivery('','',$articleIdArray,'',$source,'',$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$trafficSource);
        $trafficData            = $this->getTrafficData('','',$articleIdArray,'',$source,'',$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$trafficSource);

        $dashboardResult['topLineChartResults']['trafficData']        = $trafficData;
        $dashboardResult['topLineChartResults']['pageviewData']       = $engagementResultData['pageviewData'];
        $dashboardResult['topLineChartResults']['bounceRate']         = $engagementResultData['bounceRateData'];
        $dashboardResult['topLineChartResults']['avgSessionDuration'] = $engagementResultData['avgSessionDuration'];
        $dashboardResult['topLineChartResults']['avgPagePerSession']  = $engagementResultData['avgPagePerSession'];
        $dashboardResult['topLineChartResults']['exitRateData']       = $engagementResultData['exitRateData'];

        $dashboardResult['pieChartResult']['Traffic-SourceWise'] = $trafficData['Traffic-SourceWise'];
        $dashboardResult['pieChartResult']['Traffic-DeviceWise'] = $trafficData['Traffic-DeviceWise'];
        $dashboardResult['pieChartResult']['PageView-DeviceWise'] = $engagementResultData['pageviewData-DeviceWise'];

        echo json_encode($dashboardResult);
    }
    function getArticleDataForDomestic_Map()
    {
        $articleId              = isset($_POST['articleId'])?$this->input->post('articleId'):'';
        $source                 = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate              = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate                = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate      = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate        = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise               = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        
        $articleIdArray         = explode(',', $articleId);

        $geosplit               = $this->getGeoSplitData('','',$articleIdArray,'','',$source,$startDate,$endDate,$trafficSource);
        echo $geosplit;
    }
    function getArticleDataForSA_Database()
    {
        $articleId              = isset($_POST['articleId'])?$this->input->post('articleId'):'';
        $source                 = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate              = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate                = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate      = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate        = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise               = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        
        $articleIdArray         = explode(',', $articleId);

        $commentResultData      = $this->getCommentDataOnArticle_SA('',$articleIdArray,'',$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
        $replyResultData        = $this->getReplyDataOnArticle_SA('',$articleIdArray,'',$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
        $linearChartResult['commentResult']   = $commentResultData;
        $linearChartResult['replyResult']     = $replyResultData;
        echo json_encode($linearChartResult);
    }
    function getArticleDataForSA_ElasticSearch()
    {
        $articleId              = isset($_POST['articleId'])?$this->input->post('articleId'):'';
        $source                 = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate              = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate                = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate      = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate        = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise               = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        
        $articleIdArray         = explode(',', $articleId);        
        $engagementResultData   = $this->getEngagementDataForCustomerDelivery('','',$articleIdArray,'',$source,'',$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$trafficSource,'yes');
        $trafficData            = $this->getTrafficData('','',$articleIdArray,'',$source,'',$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$trafficSource,'yes');

        $dashboardResult['topLineChartResults']['trafficData']        = $trafficData;
        $dashboardResult['topLineChartResults']['pageviewData']       = $engagementResultData['pageviewData'];
        $dashboardResult['topLineChartResults']['bounceRate']         = $engagementResultData['bounceRateData'];
        $dashboardResult['topLineChartResults']['avgSessionDuration'] = $engagementResultData['avgSessionDuration'];
        $dashboardResult['topLineChartResults']['avgPagePerSession']  = $engagementResultData['avgPagePerSession'];
        $dashboardResult['topLineChartResults']['exitRateData']       = $engagementResultData['exitRateData'];
        $dashboardResult['pieChartResult']['Traffic-SourceWise']     = $trafficData['Traffic-SourceWise'];
        $dashboardResult['pieChartResult']['Traffic-DeviceWise']      = $trafficData['Traffic-DeviceWise'];
        $dashboardResult['pieChartResult']['PageView-DeviceWise']     = $engagementResultData['pageviewData-DeviceWise'];

        echo json_encode($dashboardResult);
    }
    function getArticleDataForSA_Map()
    {
        $articleId              = isset($_POST['articleId'])?$this->input->post('articleId'):'';
        $source                 = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate              = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate                = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate      = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate        = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise               = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        
        $articleIdArray         = explode(',', $articleId);        
        $geosplit               = $this->getGeoSplitData('','',$articleIdArray,'','',$source,$startDate,$endDate,$trafficSource,'yes');
        echo $geosplit;
    }
    function getCoursesInSubCategory()	
    {
        $subCategoryId      = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $result     = $this->cdMISLib->getCourseIdsBasedOnSubcatId($subCategoryId);
        if(count($result)>0)
                echo json_encode($result);
        else
            echo "None";

    }
    /**
    * @param : Integer: CourseId ---> Input CourseId
    * @return : array ---> similar Courses,Institutes for given course id in Domestic shiksha Website
    */
    function getSimiliarInstitutesForCourses($courseId)
    {
        $this->load->library('categoryList/CategoryPageRecommendations');
        $this->load->builder('ListingBuilder','listing');

        $listingBuilder      = new ListingBuilder;

        $instituteRepository = $listingBuilder->getInstituteRepository();
        $similar_institutes  = $this->categorypagerecommendations->getSimilarInstitutes($courseId);
        $similar_institutes  = $similar_institutes['recommendations'];
          
        $i = 0;
        foreach ($similar_institutes as $key => $value) {
            if($i >= 5)
                break;

            $instituteIds[]=$key;
            $courseIds[]=$value;
            $i++;
        }
        $similarCourses['instituteIds'] = $instituteIds;
        $similarCourses['courseIds']=$courseIds;
        return $similarCourses;

    }

    /**
    * @param array: Input $answerResultCount
    * @return Integer: 
    */
    function getAnswerResultCount($answerResultCount)
    {         
        $answercount = 0;
        foreach ($answerResultCount as $key => $value) {
            $answercount += $value;   
        }
        return number_format($answercount);
    }
    /**
    * @param array : Input $result
    * @return array: It will return an array acceptable by Doughnut chart in bootstrap
    */
 	function prepareDataForCharts($result)
 	{
       
 		foreach ($result as $key => $value) {
            if(array_key_exists('type', $value))
 			        $pieChart[$value['type']]+=$value['responsescount'];
            if(array_key_exists('sourceWise', $value))
            {
                $pieChart[$value['sourceWise']] +=$value['responsescount'];
            }
            if(array_key_exists('subcatId', $value))
            {
                $pieChart[$value['subcatId']] +=$value['responsescount'];   
            }
 		}

        $totalResponses = 0;
           foreach ($pieChart as $key => $value) {
           	$totalResponses += $value;
           }

        //below line is used for creating pie chart
 		$colorArray = array("#edc240", "#4da74d", "#cb4b4b", "#afd8f8", "#9440ed", "#054530", "#554515", "#854524", "#954527", "#654518", "#254536", "#154533", "#354539", "#454542", "#554545", "#754521", "#654548", "#754551", "#854554", "#954557", "#054560", "#154563", "#254566", "#354569", "#454572", "#454512", "#554575", "#654578", "#754581", "#854584", "#954587", "#054590", "#654608", "#754611");
        $i = 0; 
         uasort($pieChart,function($c1,$c2){
            return (($c1 >=$c2)?-1:1);
        });
        foreach ($pieChart as $key => $value) {
            $pieChartData[$i]['value'] = $value;
            $pieChartData[$i]['label'] = ucfirst($key);
            $pieChartData[$i]['color'] = $colorArray[$i];
           //$key = strlen($key) > 10 ? substr($key, 0,8).'...':$key;
            $pieChartIndexData=$pieChartIndexData. 
                            '<tr>'.
                                '<td class ="width_60_percent_important">'.
                                    '<p class ="white_space_normal_overwrite" style="font-family:verdana"><i class="fa fa-square" style="color:'.$pieChartData[$i]['color'].'">'.'&nbsp'.ucfirst($key).'  '.'</i></p>'.
                                '</td>'.
                                '<td>'.number_format(($value/$totalResponses)*100,2,'.','').'</td>'.
                                '<td class="nonempty">'.number_format($value).'</td>'.

                            '</tr>';                            
            $i++;
        }

        $result['pieChart'] = $pieChartData;
        $result['pieChartIndex']='"'.$pieChartIndexData.'"';
        $result['pieChartResultCount'] = number_format($totalResponses);
        return $result;
 	}
    /**
    * @param array : Input $result
    * @return array: It will return an array acceptable by Doughnut chart in bootstrap
    */
    function preparePieChart($result)
    {
        foreach ($result as $key => $value) {
            if(array_key_exists('siteSource', $value))
                $pieChart[$value['siteSource']]+=$value['responsescount'];
            if(array_key_exists('authorId', $value))
            {
                $pieChart[$value['authorId']] += $value['articleCount'];
            }
            if(array_key_exists('pivotName', $value))
            {
                $pieChart[$value['pivotName']] += $value['responsescount'];   
            }
        }
        $totalResponses = 0;
           foreach ($pieChart as $key => $value) {
            $totalResponses += $value;
           }

        //below line is used for creating pie chart
        $colorArray = array("#edc240", "#4da74d", "#cb4b4b", "#afd8f8", "#9440ed", "#054530", "#554515", "#854524", "#954527", "#654518", "#254536", "#154533", "#354539", "#454542", "#554545", "#754521", "#654548", "#754551", "#854554", "#954557", "#054560", "#154563", "#254566", "#354569", "#454572", "#454512", "#554575", "#654578", "#754581", "#854584", "#954587", "#054590", "#654608", "#754611");
        $i = 0; 
        uasort($pieChart,function($c1,$c2){
            return (($c1 >=$c2)?-1:1);
        });
        foreach ($pieChart as $key => $value) {
            $pieChartData[$i]['value'] = $value;
        	$pieChartData[$i]['label'] = (string)ucfirst($key);
            $pieChartData[$i]['color'] = $colorArray[$i];
           //$key = strlen($key) > 10 ? substr($key, 0,8).'...':$key;
            $pieChartIndexData=$pieChartIndexData. 
                            '<tr>'.
                                '<td class ="width_60_percent_important">'.
                                    '<p class ="white_space_normal_overwrite" style="font-family:verdana"><i class="fa fa-square" style="color:'.$pieChartData[$i]['color'].'">'.'&nbsp'.ucfirst($key).'  '.'</i></p>'.
                                '</td>'.
                                '<td>'.number_format(($value/$totalResponses)*100,2,'.','').'</td>'.
                                '<td>'.number_format($value).'</td>'.
                            '</tr>';                            
            $i++;
        }

        $result['pieChart'] = $pieChartData;
        $result['pieChartIndex']='"'.$pieChartIndexData.'"';
        $result['pieChartResultCount'] = number_format($totalResponses);
        return $result;
    }
 	/**
    * @param array : Input $result
    * @return array: It will return an array acceptable by Linear chart in bootstrap
    */
 	function prepareDataForLineChart($result)
 	{
 		$i=0;
 		foreach ($result as $key => $value) 
 		{
 			$lineChart[$value['responseDate']]+=$value['responsescount'];	
 		}
 		foreach($lineChart as $key => $value)
 		{
 			$lineChartData[$i++]=array($key,$value);
 		}
 		return $lineChartData;

 	}
    /**
    * @param Input:filters selected from front end MIS
    * @return totalRegistrations Done on Institute/Course Detail Page within the selected dateRange
    */
    
    function getRegistrationsData($instituteId=array(),$courseId=array(),$source='',$paidType='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1,$isStudyAbroad='no',$subCategoryId= array(),$cityId = array(),$countryId = array())
    {
        $dataForRegular = $this->cdMISLib->getCDRegistrations($instituteId,$courseId,$source,$paidType,$startDate,$endDate,$viewWise,$isStudyAbroad,$subCategoryId,$cityId,$countryId);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dateRangeArray['startDate'] = $compare_startDate;
            $dateRangeArray['endDate']   = $compare_endDate;
            $dataForCompare = $this->cdMISLib->getCDRegistrations($instituteId,$courseId,$source,$paidType,$compare_startDate,$compare_endDate,$viewWise,$isStudyAbroad,$subCategoryId,$cityId,$countryId);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $registrationData = array();
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $registrationData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $registrationData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        return $registrationData;
    }

    /**
    * @param Input:filters selected from front end MIS
    * @return totalQuestions asked on specified Courses within the selected dateRange
    */
    function getQuestionsData($instituteId=array(),$courseId=array(),$source='',$paidType='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1,$subCategoryId=array(),$cityId = array())
    {
        $dataForRegular = $this->cdMISLib->getQuestionsData($instituteId,$courseId,$source,$paidType,$startDate,$endDate,$viewWise,$subCategoryId,$cityId);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getQuestionsData($instituteId,$courseId,$source,$paidType,$compare_startDate,$compare_endDate,$viewWise,$subCategoryId,$cityId);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $questionsData = array();
        $questionResultCount = 0;
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $questionResultCount += $this->getAnswerResultCount($dataForCompare);
            $questionsData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $questionResultCount += $this->getAnswerResultCount($dataForRegular);
        $questionsData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $questionsData['resultCount'] = $questionResultCount;
        return $questionsData;
    }
    /**
    * @param Input:filters selected from front end MIS
    * @return totalRegistrations Done on Articles Page within the selected dateRange
    */
    function getRegistrationsDataBasedOnArticle($articleId=array(),$source='',$paidType='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1)
    {
        $dataForRegular = $this->cdMISLib->getRegistrationsDataBasedOnArticle($articleId,$source,$paidType,$startDate,$endDate,$viewWise);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getRegistrationsDataBasedOnArticle($articleId,$source,$paidType,$compare_startDate,$compare_endDate,$viewWise);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $registrationData = array();
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $registrationData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $registrationData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        return $registrationData;
    }
    /**
    * @param Input:filters selected from front end MIS
    * @return total Comments Started on Articles in Domestic within the selected dateRange
    */

    function getCommentDataOnArticle($subcategoryId = array(),$articleId=array(),$authorId = array(),$source='',$paidType='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1)
    {
        $dataForRegular = $this->cdMISLib->getCommentDataOnArticle($subcategoryId,$articleId,$authorId,$source,$paidType,$startDate,$endDate,$viewWise);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getCommentDataOnArticle($subcategoryId,$articleId,$authorId,$source,$paidType,$compare_startDate,$compare_endDate,$viewWise);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $discussionResultCount =0;
        $discussionData = array();
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $discussionResultCount += $this->getAnswerResultCount($dataForCompare);
            $discussionData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $discussionResultCount += $this->getAnswerResultCount($dataForRegular);
        $discussionData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $discussionData['resultCount'] = $discussionResultCount;

        return $discussionData; 
    }
    /**
    * @param Input:filters selected from front end MIS
    * @return total Comments started on article in study abroad  within the selected dateRange
    */
    function getCommentDataOnArticle_SA($subcategoryId = array(),$articleId=array(),$authorId = array(),$source='',$paidType='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1)
    {

        $dataForRegular = $this->cdMISLib->getCommentDataOnArticle_SA($subcategoryId,$articleId,$authorId,$source,$paidType,$startDate,$endDate,$viewWise);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getCommentDataOnArticle_SA($subcategoryId,$articleId,$authorId,$source,$paidType,$compare_startDate,$compare_endDate,$viewWise);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $discussionResultCount =0;
        $discussionData = array();
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $discussionResultCount += $this->getAnswerResultCount($dataForCompare);
            $discussionData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $discussionResultCount += $this->getAnswerResultCount($dataForRegular);
        $discussionData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $discussionData['resultCount'] = $discussionResultCount;

        return $discussionData; 
    }
    /**
    * @param Input:filters selected from front end MIS
    * @return total Reply started on Article Pages in Domestic within the selected dateRange
    */
    function getReplyDataOnArticle($subcategoryId = array(),$articleId=array(),$authorId = array(),$source='',$paidType='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1)
    {

        $dataForRegular = $this->cdMISLib->getReplyDataOnArticle($subcategoryId,$articleId,$authorId,$source,$paidType,$startDate,$endDate,$viewWise);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getReplyDataOnArticle($subcategoryId,$articleId,$authorId,$source,$paidType,$compare_startDate,$compare_endDate,$viewWise);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $commentResultCount =0;
        $commentData = array();
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $commentResultCount += $this->getAnswerResultCount($dataForCompare);
            $commentData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $commentResultCount += $this->getAnswerResultCount($dataForRegular);
        $commentData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $commentData['resultCount'] = $commentResultCount;

        return $commentData;    
    }
    /**
    * @param Input:filters selected from front end MIS
    * @return total Reply started on Article Pages in StudyAbroad within the selected dateRange
    */
    function getReplyDataOnArticle_SA($subcategoryId = array(),$articleId=array(),$authorId =array(),$source='',$paidType='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1)
    {
        $dataForRegular = $this->cdMISLib->getReplyDataOnArticle_SA($subcategoryId,$articleId,$authorId,$source,$paidType,$startDate,$endDate,$viewWise);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getReplyDataOnArticle_SA($subcategoryId,$articleId,$authorId,$source,$paidType,$compare_startDate,$compare_endDate,$viewWise);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $commentResultCount =0;
        $commentData = array();
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $commentResultCount += $this->getAnswerResultCount($dataForCompare);
            $commentData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $commentResultCount += $this->getAnswerResultCount($dataForRegular);
        $commentData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $commentData['resultCount'] = $commentResultCount;

        return $commentData;   
    }
    /**
    * @param Input:filters selected from front end MIS
    * @return totalAnswers answered on specified Courses in Domestic within the selected dateRange
    */
    function getAnswersData($instituteId=array(),$courseId=array(),$source='',$paidType='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1,$subCategoryId=array(),$cityId=array())
    {
        $dataForRegular = $this->cdMISLib->getAnswersData($instituteId,$courseId,$source,$paidType,$startDate,$endDate,$viewWise,$subCategoryId,$cityId);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getAnswersData($instituteId,$courseId,$source,$paidType,$compare_startDate,$compare_endDate,$viewWise,$subCategoryId,$cityId);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $answerResultCount = 0;
        $answersData = array();
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $answerResultCount += $this->getAnswerResultCount($dataForCompare);
            $answersData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $answerResultCount += $this->getAnswerResultCount($dataForRegular);;
        $answersData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $answersData['resultCount'] = $answerResultCount;
        return $answersData;

    }
    
    function getArticleDataBasedOnSubCatId($subCategoryId=array(),$authorId=array(),$source='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1)
    {
        $dataForRegular = $this->cdMISLib->getArticleDataBasedOnSubCatId($subCategoryId,$authorId,$source,$startDate,$endDate,$viewWise);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getArticleDataBasedOnSubCatId($subCategoryId,$authorId,$source,$compare_startDate,$compare_endDate,$viewWise);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $articleResultCount =0;
        $artilceData = array();
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $articleResultCount += $this->getAnswerResultCount($dataForCompare);
            $artilceData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $articleResultCount += $this->getAnswerResultCount($dataForRegular);
        $artilceData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $artilceData['resultCount'] = $articleResultCount;

        return $artilceData; 
    }
    function getArticleDataBasedOnSubCatId_SA($subCategoryId=array(),$authorId=array(),$source='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1)
    {
        $dataForRegular = $this->cdMISLib->getArticleDataBasedOnSubCatId_SA($subCategoryId,$authorId,$source,$startDate,$endDate,$viewWise);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getArticleDataBasedOnSubCatId_SA($subCategoryId,$authorId,$source,$compare_startDate,$compare_endDate,$viewWise);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $articleResultCount =0;
        $artilceData = array();
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $articleResultCount += $this->getAnswerResultCount($dataForCompare);
            $artilceData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $articleResultCount += $this->getAnswerResultCount($dataForRegular);
        $artilceData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $artilceData['resultCount'] = $articleResultCount;

        return $artilceData; 
    }
    function getDigUpData($instituteId=array(),$courseId=array(),$source='',$paidType='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1,$subCategoryId = array(),$cityId = array())
    {
        $dataForRegular = $this->cdMISLib->getDigUpData($instituteId,$courseId,$source,$paidType,$startDate,$endDate,$viewWise,$subCategoryId,$cityId);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getDigUpData($instituteId,$courseId,$source,$paidType,$compare_startDate,$compare_endDate,$viewWise,$subCategoryId,$cityId);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $digupResultCount =0;
        $digupData = array();
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $digupResultCount += $this->getAnswerResultCount($dataForCompare);
            $digupData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $digupResultCount += $this->getAnswerResultCount($dataForRegular);
        $digupData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $digupData['resultCount'] = $digupResultCount;

        return $digupData;        
    }
    function getResponsesData($instituteId=array(),$courseId=array(),$source='',$paidType='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1,$subCategoryId=array(),$cityId=array(),$countryId=array(),$isStudyAbroad='no')
    {
        $dataForRegular = $this->cdMISLib->getResponsesData($instituteId,$courseId,$source,$paidType,$startDate,$endDate,$viewWise,$subCategoryId,$cityId,$countryId,$isStudyAbroad);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getResponsesData($instituteId,$courseId,$source,$paidType,$compare_startDate,$compare_endDate,$viewWise,$subCategoryId,$cityId,$countryId,$isStudyAbroad);
        }
        $responseData = array();
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $responseResultCount = 0;
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $responseResultCount += $this->getAnswerResultCount($dataForCompare);
            $responseData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $responseResultCount += $this->getAnswerResultCount($dataForRegular);
        $responseData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $responseData['resultCount'] = $responseResultCount;
        return $responseData;            
    }
    function getRespondentsData($courseId = array(),$source = '',$paidType ='',$startDate='',$endDate ='',$subCategoryId = array(),$cityId = array(),$countryId=array(),$isStudyAbroad ='no')
    {
        $respondentsData = $this->cdMISLib->getRespondentsForResponsesData($courseId,$source,$paidType,$startDate,$endDate,$subCategoryId,$cityId,$countryId,$isStudyAbroad);
        /*$userId_respondents = array();
        $i=0;
        $userSourceMapping = array();
        foreach ($respondentsData as $key => $value) {
            $userId_respondents[$i++] = $value['userId'];
            if( array_key_exists($value['userId'], $userSourceMapping))
            {
                array_push($userSourceMapping[$value['userId']],$value) 
            }
        }*/
        $respondentsResult = $this->cdMISLib->getRespondentsData($respondentsData);
        return $respondentsResult;
    }
    
    function getTrafficData($instituteId=array(),$courseId=array(),$articleId = array(),$discussionId = array(),$source='',$subcatArray=array(),$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1,$trafficSource='',$isStudyAbroad='no')
    {
        $extraData = array();
        $extraData['CD']['deviceType'] =strtolower($source);
        $dateRangeArray = array('startDate'=>$startDate,'endDate'=>$endDate);
        if( !empty($instituteId) && ! empty($courseId))
        {
            $extraData['CD']['instituteId'] = $instituteId;
            $extraData['CD']['courseId'] = $courseId;
        }
        else if(! empty($articleId))
        {
            $extraData['CD']['articleId'] = $articleId;
        }
        else if( ! empty($discussionId))
        {
            $extraData['CD']['discussionId'] = $discussionId;
        }
        else if( ! empty($subcatArray))
        {
            if( ! empty($subcatArray['subCategoryId']))
                $extraData['CD']['subCategoryId'] = $subcatArray['subCategoryId'];
            $extraData['CD']['pageName'] = $subcatArray['pageName'];
            if( ! empty($subcatArray['cityId']))
                $extraData['CD']['cityId'] = $subcatArray['cityId'];
            if( ! empty($subcatArray['stateId']))
                $extraData['CD']['stateId'] = $subcatArray['stateId'];
            if( ! empty($subcatArray['countryId']))
                $extraData['CD']['countryId'] = $subcatArray['countryId'];
            if( ! empty($subcatArray['authorId']))
                $extraData['CD']['authorId'] = $subcatArray['authorId'];
        }
        //$extraData['CD']['deviceType'] = $isMobile;
        $extraData['CD']['view'] = $viewWise;
        if( ! empty($trafficSource))
        {
            $extraData['CD']['trafficSource'] = $trafficSource;
        }
        if($isStudyAbroad == 'yes')
        {
            $extraData['CD']['isStudyAbroad'] =  'yes';
        }
        $this->trafficLib = $this->load->library('trackingMIS/trafficDataLib');
        $dataForRegular = $this->trafficLib->getTotalVisitForPage($dateRangeArray,$extraData);
        $trafficData = array();
        if( ! empty($compare_startDate) && ! empty($compare_endDate)) 
        {
            $dateRangeArray = array('startDate'=>$compare_startDate,'endDate'=>$compare_endDate);
            $dataForCompare = $this->trafficLib->getTotalVisitForPage($dateRangeArray,$extraData);
        }
        $dataForRegularLineChart = $this->getDataBasedOnViewForElasticSearch($dataForRegular,$viewWise,$startDate,$endDate);
        $trafficData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegularLineChart));

        $trafficData['Traffic-SourceWise']['dataForRegular'] = $this->prepareDataForCharts($dataForRegular);
        $trafficData['Traffic-DeviceWise']['dataForRegular'] = $this->preparePieChart($dataForRegular);
        if(isset($dataForCompare))
        {
            $dataForCompareLineChart = $this->getDataBasedOnViewForElasticSearch($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $trafficData['dataForCompare']  = json_encode($this->prepareDataForLineChart1($dataForCompareLineChart));
            $trafficData['Traffic-SourceWise']['dataForCompare'] = $this->prepareDataForCharts($dataForCompare);
            $trafficData['Traffic-DeviceWise']['dataForCompare'] = $this->preparePieChart($dataForCompare);     
        }
        return $trafficData;
    }
    /**
    * @param Input: array, viewWise , startDate , endDate
    * @return array : it will change input array format basedon viewWise
    */

    function getDataBasedOnView($dataSet=array(),$viewWise=1,$startDate='',$endDate='')
    {
        if($viewWise == 1)
        {
            $dataResult = $this->createDayWiseData($dataSet,$startDate,$endDate);
        }
        else if($viewWise == 2)
        {
            $dataResult = $this->createWeekWiseData($dataSet,$startDate,$endDate);  
        }
        else if($viewWise == 3)
        {
           $dataResult = $this->createMonthWiseData($dataSet,$startDate,$endDate);
        }
        //$trafficLineChartData = $this->createDayWiseData($trafficData);
        //$trafficLineChartData = $this->prepareDataForLineChart1($trafficLineChartData);
        return $dataResult;
    }
    /**
    * @param Input: array, viewWise , startDate , endDate
    * @return array : it will change input array format basedon viewWise
    */
    function getDataBasedOnViewForElasticSearch($dataSet=array(),$viewWise=1,$startDate='',$endDate='')
    {
        if($viewWise == 1)
        {
            $dataResult = $this->createDayWiseData($dataSet,$startDate,$endDate);
        }
        else if($viewWise == 2)
        {
            $dataResult = $this->cdMISLib->createWeekWiseDataForElasticSearch($dataSet,$startDate,$endDate);  
        }
        else if($viewWise == 3)
        {
           $dataResult = $this->cdMISLib->createMonthWiseDataForElasticSearch($dataSet,$startDate,$endDate);
        }

        return $dataResult;
        
    }
    
    // below function is used for arranging given input array in weekwise format
    function createWeekWiseData($result,$startDate,$endDate,$viewWise=2)
    {
        $startingYear = date('Y', strtotime($startDate));
        $endYear = date('Y', strtotime($endDate));
        if($viewWise == 2)
        {
            if($startingYear == $endYear)
            {
                $week_start_Dates = array();
                $starting_Week_no = date('W', strtotime($startDate));
                $end_Week_no = date('W', strtotime($endDate));
                for($i=$starting_Week_no;$i<=$end_Week_no;$i++)
                {
                    if($i == $starting_Week_no)         
                        $week_start_Dates[$startDate] = 0;    
                    else
                    {
                        $getdate = new DateTime();
                        $getdate->setISODate($startingYear,$i,1); //year , week num , day
                        $startingDate=$getdate->format('Y-m-d');
                        $week_start_Dates[$startingDate] = 0;
                    }
                }

                $getdate = new DateTime();
                if($starting_Week_no > $end_Week_no)
                    $conflictYear = $startingYear-1;
                else 
                    $conflictYear = $startingYear;
                $getdate->setISODate($conflictYear,$starting_Week_no,1); //parameters are year,month,day no you want fetch the date
                $conflictDate=$getdate->format('Y-m-d');

                //$week_start_Dates[$startDate] = 0;
                foreach ($result as $key => $value) {
                    if($value['weekNumber'] == $starting_Week_no)         
                    {
                        $week_start_Dates[$startDate] += $value['responsescount'];    
                    }
                    else{
                    $getdate = new DateTime();
                    $getdate->setISODate($startingYear,$value['weekNumber'],1); //parameters are year,month,day no you want fetch the date
                    $startingDate=$getdate->format('Y-m-d');
                    if($startingDate == $conflictDate)
                        $week_start_Dates[$startDate] += $value['responsescount'];
                    else
                        $week_start_Dates[$startingDate] += $value['responsescount'];
                    }
                }
            }
            else
            {
                $week_start_Dates = array();
                $label = 0;
                $starting_Week_no = date('W', strtotime($startDate));
                //_p($starting_Week_no);
                $end_Week_no = date('W',strtotime($startingYear.'-12-31'));
                if($starting_Week_no != 1 && $end_Week_no ==1)
                {
                    $end_Week_no = date('W',strtotime($startingYear.'-12-24'));
                }
                $next_start_week_no = 1;
                $next_end_week_no = date('W',strtotime($endDate));
                if($next_end_week_no == $end_Week_no)
                {
                    $next_start_week_no = 0;
                    $next_end_week_no = -1;
                }

                for($i=$starting_Week_no;$i<=$end_Week_no && $starting_Week_no!= 1;$i++)
                {
                    if($i == $starting_Week_no)
                    {
                        $week_start_Dates[$startDate] = 0;
                        $label = 1;
                    }
                    else
                    {
                        $getdate = new DateTime();
                        $getdate->setISODate($startingYear,$i,1); //parameters are year,month,day no you want fetch the date
                        $startingDate=$getdate->format('Y-m-d');
                        $week_start_Dates[$startingDate] = 0;
                    }
                }
        
                for($i=$next_start_week_no;$i<=$next_end_week_no;$i++)
                {
                    if($i == $next_start_week_no && $label == 0)
                    {
                        $week_start_Dates[$startDate] = 0;
                    }
                    else
                    {
                        $getdate = new DateTime();
                        $getdate->setISODate($endYear,$i,1); //parameters are year,month,day no you want fetch the date
                        $startingDate=$getdate->format('Y-m-d');
                        $week_start_Dates[$startingDate] = 0;
                    }
                }

                $getdate = new DateTime();
                $getdate->setISODate($startingYear,$starting_Week_no,1); //parameters are year,month,day no you want fetch the date
                $conflictDate=$getdate->format('Y-m-d');

                foreach ($result as $key => $value) {
                    if($label == 0 && $value['weekNumber'] == $next_start_week_no)
                    {
                        $week_start_Dates[$startDate] += $value['responsescount'];    
                    }
                    else if($label == 1 && $value['weekNumber'] == $starting_Week_no)
                    {
                        $week_start_Dates[$startDate] += $value['responsescount'];
                    }
                    else
                    {
                        $getdate = new DateTime();
                        if($starting_Week_no <= $value['weekNumber'] && $starting_Week_no != 1)
                            $getdate->setISODate($startingYear,$value['weekNumber'],1); //parameters are year,month,day no you want fetch the date
                        else
                            $getdate->setISODate($endYear,$value['weekNumber'],1);//parameters are year,month,day no you want fetch the date
                            $startingDate=$getdate->format('Y-m-d');
                            if($startingDate == $conflictDate)
                                $week_start_Dates[$startDate] += $value['responsescount'];
                            else
                                $week_start_Dates[$startingDate] += $value['responsescount'];
                    }
                }
            }
        }
        return $week_start_Dates;
    }
    // below function is used for arranging given input array in daywise format
    function createDayWiseData($result,$startDate,$endDate)
    {
        $lineChart = array();
        for($i=$startDate;$i<=$endDate;)  {
            $lineChart[$i] = 0;
            $i = strtotime("+1 day", strtotime($i));
            $i = date("Y-m-d", $i);
        }
     
        foreach ($result as $key => $value) 
            {
                $lineChart[$value['responseDate']]+=$value['responsescount'];   
            }
            return $lineChart;
    }
    /**
    * @param array : Input $result
    * @return array: It will return an array acceptable by Linear chart in bootstrap
    */

    function prepareDataForLineChart1($result)
    {
        $i=0;
        
        foreach($result as $key => $value)
        {
            $lineChartData[$i++]=array($key,$value);
        }
        return $lineChartData;

    }

    // below function is used for arranging given input array in monthwise format
    function createMonthWiseData($result,$startDate,$endDate)
    {
        $startingYear =  date('Y',strtotime($startDate));
        $endYear = date('Y',strtotime($endDate));
        if($startingYear == $endYear)
            {
                $startingMonth = date('m', strtotime($startDate));
                $endMonth = date('m', strtotime($endDate));
                $month_Wise_Data = array();
                $monthLinearData = array();
                foreach ($result as $key => $value) {
                    if($value['monthNumber'] <=9)
                    {
                        $month_Wise_Data['0'.$value['monthNumber']] += $value['responsescount'];
                    }else{
                        $month_Wise_Data[$value['monthNumber']] += $value['responsescount'];    
                    }  
                   /* $pieChartDataOne[$value['siteSource']]+= $value['reponsesCount'];
                    $pieChartDataTwo[$value['type']]+= $value['reponsesCount'];*/
                }
                if($month_Wise_Data[$startingMonth])
                {
                    $monthLinearData[$startDate] = $month_Wise_Data[$startingMonth];    
                }else{
                    $monthLinearData[$startDate] = 0;    
                }
                               
                for ($i=$startingMonth+1; $i <= $endMonth ; $i++) {
                    if($i <= 9){
                        $i = '0'.$i;
                        $toDateFormat = $startingYear.'-'.$i.'-01';
                    }else{
                        $toDateFormat = $startingYear.'-'.$i.'-01';    
                    }
                    if($month_Wise_Data[$i]){
                        $monthLinearData[$toDateFormat] = $month_Wise_Data[$i];    
                    }else{
                        $monthLinearData[$toDateFormat] = 0;   
                    }    
                }
            }
            else{
                $startingMonth = date('m', strtotime($startDate));
                $endMonth = 12;
                $next_starting_month = 1;
                $next_end_month =date('m', strtotime($endDate));
                $monthLinearData = array();
                $daten = $startDate;
                $mnp =0;
                $mnn =0;
                $flag = 0;
                $sd='';
                for($i=$startingYear; $i<=$endYear;$i++)
                {
                    
                    if($i == $startingYear){
                        $sm =$startingMonth;    
                    }else{
                        $sm =1;
                    }

                    if($i == $endYear){
                        $em = $next_end_month;
                    }else{
                        $em =12;
                    }
                    
                    for($j=$sm;$j<=$em;$j++)
                    {
                        if($flag == 0)
                        {
                            $flag=1;
                        }
                        else if($j <= 9)
                        {
                            $daten = $i.'-0'.$j.'-01';
                        }else{
                            $daten = $i.'-'.$j.'-01';
                        }  
                        
                        $monthLinearData[$daten] = 0;
                    }
                }
                $distinctYear = date('Y', strtotime($result[0]['responseDate']));
                foreach ($result as  $value) {
                    $year = date('Y', strtotime($value['responseDate']));
                    $mnn = $value['monthNumber'];
                    if($mnn > $mnp)
                    {
                        if($mnn == $startingMonth && $year == $startingYear)
                        {
                            $monthLinearData[$startDate] += $value['responsescount'];
                            $mnp = $mnn;    
                            continue;
                        }

                        if($value['monthNumber'] <= 9)
                        {
                            $daten = $year.'-0'.$value['monthNumber'].'-01';
                        }else{
                            $daten = $year.'-'.$value['monthNumber'].'-01';
                        }  
                        $monthLinearData[$daten] += $value['responsescount'];
                        $mnp = $mnn;    
                    }else if($mnn == $mnp && $year == $distinctYear)
                    {
                        $monthLinearData[$daten] += $value['responsescount'];
                        $mnp = $mnn;    
                    }
                    else{
                        if($value['monthNumber'] <= 9)
                        {
                            $daten = $year.'-0'.$value['monthNumber'].'-01';
                        }else{
                            $daten = $year.'-'.$value['monthNumber'].'-01';
                        }  
                        $monthLinearData[$daten] += $value['responsescount'];
                        $mnp = $mnn;    
                        $distinctYear = $year;
                    }
                }
            }
            return $monthLinearData;
    }

    function getStatesBasedOnCountry()
    {
        $countryId = empty($_POST['countryId'])?'2':$this->input->post('countryId');
        $cdmismodel = $this->load->model('cdmismodel');
        $result = $cdmismodel->getStateNames($countryId);
        if(count($result)>0)
            echo json_encode($result);
        else
            echo "None";
    }

    function getCityNames()
    {
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getCityBasedOnStates();
        $cityNames = array();
        foreach ($result as $key => $value) {
            $cityNames[$value['cityId']] = $value['cityName'];
        }
        uasort($cityNames,function($c1,$c2){
            return (($c1 <=$c2)?-1:1);
        });
        return $cityNames;
    }
    function getCityBasedOnStates()
    {
        $stateId = isset($_POST['stateId'])?$this->input->post('stateId'):'';
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $city_state_mapping = array();
        foreach ($stateId as $key => $value) {
            $i = 0;
            $city_state_mapping[$value] = array();
            $result = $cdmismodel->getCityBasedOnStates($value);    
            foreach ($result as $state => $city) {
                $city_state_mapping[$value][$i++] = array('cityId'=>$city['cityId'],
                                                            'cityName'=>$city['cityName']);
            }
        }

        if(count($city_state_mapping) > 0)
            echo json_encode($city_state_mapping);
    }
    function getGeoSplitData($instituteIdArray=array(),$courseIdArray=array(),$articleId=array(),$discussionId = array(),$subcatArray= array(),$source='',$startDate='',$endDate='',$trafficSource='',$isStudyAbroad='')
    {
        $this->lib = $this->load->library('trackingMIS/engagementLib');
        $this->load->config('latLongIndianCities');
        $latLongArray = $this->config->item('latLong');
        $source =strtolower($source);
        $subcatFilters = array();
        if( ! empty($subcatArray))
        {
            $subcatFilters['pageName'] = $subcatArray['pageName'];
            if( ! empty($subcatArray['subCategoryId']))
                $subcatFilters['subCategoryId'] = $subcatArray['subCategoryId'];
            if( ! empty($subcatArray['cityId']))
                $subcatFilters['cityId'] = $subcatArray['cityId'];
            if( ! empty($subcatArray['stateId']))
                $subcatFilters['stateId'] = $subcatArray['stateId'];
            if( ! empty($subcatArray['countryId'])) 
                $subcatFilters['countryId'] = $subcatArray['countryId'];
            if( ! empty($subcatArray['authorId']))
                $subcatFilters['authorId'] = $subcatArray['authorId'];
        }
        $geosplit = $this->lib->getGeoSplitData($instituteIdArray,$courseIdArray,$articleId,$discussionId,$subcatFilters,$source,$startDate,$endDate,$trafficSource,$isStudyAbroad);
     
        /*$this->load->library('location/LocationDetection');
        $cityWiseData = array();
        $cityLatLong = array();
        $i = 0;
        foreach ($geosplit as $key => $value) {
          $this->locationObject = new LocationDetection($value['key']);
            $getData = $this->locationObject->getLocation();
            if(array_key_exists($getData['city'], $cityWiseData))
            {
                //$cityWiseData[$getData['city']] += $value['doc_count'];    
                $cityWiseData[$getData['city']] += $value['userWise']['value'];                
            }
            else
            {
                if($getData['city'] != '')
                {
                    $cityLatLong[$getData['city']] = array('latitude'=>$getData['latitude'],'longitude'=>$getData['longitude'],'cityName'=>$getData['city']);
                    $cityWiseData[$getData['city']] = $value['userWise']['value'];
    
                }
            }
            
        }*/
        $cityWiseData = array();
        foreach ($geosplit as $key => $value) {
            if($value['key'] == 'unknown' || $value['key'] == '')
            {
                continue;
            }
            $cityWiseData[$value['key']] = $value['userWise']['value'];
        }
        arsort($cityWiseData);
        $i=0;
        $geoCityWiseData = array();
        foreach ($cityWiseData as $key => $value) {
            $geoCityWiseData[$i++] = array('lat'=>$latLongArray[$key]['latitude'],'long'=>$latLongArray[$key]['longitude'],'name'=>$key.':Unique Users-'.$value);
            if($i == 20)
                break;
        }
        unset($cityWiseData);
        unset($cityLatLong);
        return json_encode($geoCityWiseData);

    }
    function getSubcategory($flag = 'national',$temp='')
    {
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getSubcategory($flag);
        $subcatId = array();
        foreach ($result as $key => $value) {
            $subcatId[$value['subcatId']] = $value['subcatName'];
        }
        if( $temp == 'content')
        {
            $subcatId['0'] = 'No Subcategory';
        }
        uasort($subcatId,function($c1,$c2){
                return (($c1 <=$c2)?-1:1);
            });
        
        return $subcatId;
    }
    function getEngagementDataForCustomerDelivery($instituteId = array(),$courseId = array(),$articleId=array(),$discussionId= array(),$source='',$subcatArray='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1,$trafficSource='',$isStudyAbroad = 'no')
    {
        $engagementData = $this->cdMISLib->getEngagementDataForCustomerDelivery($instituteId,$courseId,$articleId,$discussionId,$source,$subcatArray,$startDate,$endDate,$viewWise,$trafficSource,$isStudyAbroad);
        if(! empty($compare_startDate) && ! empty($compare_endDate))
        {
            
            $engagementDataForCompare = $this->cdMISLib->getEngagementDataForCustomerDelivery($instituteId,$courseId,$articleId,$discussionId,$source,$subcatArray,$compare_startDate,$compare_endDate,$viewWise,$trafficSource,$isStudyAbroad);
        }
        $engagementResultData = array();
        if(array_key_exists('totalPageviews', $engagementData))
            {
                $engagementResultData['totalPageviews'] = $engagementData['totalPageviews'];
                unset($engagementData['totalPageviews']);
            }
        foreach ($engagementData as $key => $value) {
            switch($key)
            {
                case 'pageView':
                        $pageviewDataForRegular = $this->getDataBasedOnViewForElasticSearch($value,$viewWise,$startDate,$endDate);
                        $engagementResultData['pageviewData']['dataForRegular'] = json_encode($this->prepareDataForLineChart1($pageviewDataForRegular));
                        $engagementResultData['pageviewData-DeviceWise']['dataForRegular'] = $this->preparePieChart($value);
                        break;
                case 'bounceRate':
                        $bounceRateDataForRegular = $this->getDataBasedOnViewForElasticSearch($value,$viewWise,$startDate,$endDate);
                        $engagementResultData['bounceRateData']['dataForRegular'] = json_encode($this->prepareDataForLineChart1($bounceRateDataForRegular));
                        //$engagementResultData['bounceRate-DeviceWise']['dataForRegular'] = $this->preparePieChart($value);
                        break;
                case 'exitRate':
                        $exitRateDataForRegular = $this->getDataBasedOnViewForElasticSearch($value,$viewWise,$startDate,$endDate);
                        $engagementResultData['exitRateData']['dataForRegular'] = json_encode($this->prepareDataForLineChart1($exitRateDataForRegular));
                        //$engagementResultData['exitRate-DeviceWise']['dataForRegular'] = $this->preparePieChart($value);
                        break;
                case 'avgSessionDuration':
                        $avgSessionDataForregular = $this->getDataBasedOnViewForElasticSearch($value,$viewWise,$startDate,$endDate);
                        $engagementResultData['avgSessionDuration']['dataForRegular'] = json_encode($this->prepareDataForLineChart1($avgSessionDataForregular));
                        //$engagementResultData['avgSessionDuration-DeviceWise']['dataForRegular']
                        break;
                case 'avgPagePerSession':
                        $avgPageDataForRegular = $this->getDataBasedOnViewForElasticSearch($value,$viewWise,$startDate,$endDate);
                        $engagementResultData['avgPagePerSession']['dataForRegular'] = json_encode($this->prepareDataForLineChart1($avgPageDataForRegular));
                        break;
            }
        }
        
        $exitData = json_decode($engagementResultData['exitRateData']['dataForRegular']);
        $pageviewData = json_decode($engagementResultData['pageviewData']['dataForRegular']);
        
        $exitRateData = array();
        $len = count($pageviewData);
        $i = 0;
        for($i = 0; $i < $len ; $i++)
        {
            $exitRateData[$exitData[$i][0]] = number_format(($exitData[$i][1] / $pageviewData[$i][1]) * 100,2,'.','');
        }
        $engagementResultData['exitRateData']['dataForRegular'] = json_encode($this->prepareDataForLineChart1($exitRateData));
               

        if(isset($engagementDataForCompare))
        {
            foreach ($engagementDataForCompare as $key => $value) {
                switch ($key) {
                    case 'pageView':
                        $pageviewDataForCompare = $this->getDataBasedOnViewForElasticSearch($value,$viewWise,$compare_startDate,$compare_endDate);
                        $engagementResultData['pageviewData']['dataForCompare'] = json_encode($this->prepareDataForLineChart1($pageviewDataForCompare));
                        $engagementResultData['pageviewData-DeviceWise']['dataForCompare'] = $this->preparePieChart($value);

                            break;
                    case 'bounceRate':
                            $bounceRateDataForCompare = $this->getDataBasedOnViewForElasticSearch($value,$viewWise,$compare_startDate,$compare_endDate);
                            $engagementResultData['bounceRateData']['dataForCompare'] = json_encode($this->prepareDataForLineChart1($bounceRateDataForCompare));
                            //$engagementResultData['bounceRate-DeviceWise']['dataForCompare'] = $this->preparePieChart($value);
                            break;
                    case 'exitRate':
                            $exitRateDataForCompare = $this->getDataBasedOnViewForElasticSearch($value,$viewWise,$compare_startDate,$compare_endDate);
                            $engagementResultData['exitRateData']['dataForCompare'] = json_encode($this->prepareDataForLineChart1($exitRateDataForCompare));
                            //$engagementResultData['exitRate-DeviceWise']['dataForCompare'] = $this->preparePieChart($value);
                            break;
                    case 'avgSessionDuration':
                        $avgSessionDataForCompare = $this->getDataBasedOnViewForElasticSearch($value,$viewWise,$compare_startDate,$compare_endDate);
                        $engagementResultData['avgSessionDuration']['dataForCompare'] = json_encode($this->prepareDataForLineChart1($avgSessionDataForCompare));
                        //$engagementResultData['avgSessionDuration-DeviceWise']['dataForRegular']
                        break;
                    case 'avgPagePerSession':
                        $avgPageDataForCompare = $this->getDataBasedOnViewForElasticSearch($value,$viewWise,$compare_startDate,$compare_endDate);
                        $engagementResultData['avgPagePerSession']['dataForCompare'] = json_encode($this->prepareDataForLineChart1($avgPageDataForCompare));
                        break;
                }
            }
        $exitDataForCompare = json_decode($engagementResultData['exitRateData']['dataForCompare']);
        $pageviewDataCompare = json_decode($engagementResultData['pageviewData']['dataForCompare']);
        
        $exitRateData = array();
        $len = count($pageviewDataCompare);
        $i = 0;
        for($i = 0; $i < $len ; $i++)
        {
            
            $exitRateData[$exitDataForCompare[$i][0]] = number_format(($exitDataForCompare[$i][1] / $pageviewDataCompare[$i][1]) * 100,2,'.','');
            
        }
        $engagementResultData['exitRateData']['dataForCompare'] = json_encode($this->prepareDataForLineChart1($exitRateData));
        }
        return $engagementResultData;

    }

    function get_discussionData_overview()
    {
        $userId = array('5213424','4272799','4408658','5043232');
        $source = '';$trafficSource = '';
        $startDate         = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate           = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate   = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $discussionSplitbySubcat = $this->getDiscussionPostedInSubcat($startDate,$endDate,$compare_startDate,$compare_endDate);
        $discussionSplitbyAuthor['dataForRegular'] = $this->getDiscussionSplitByAuthor($startDate,$endDate);
        $recentDiscussionIds = $this->getDiscussionIdBasedOnUserId('','',$startDate,$endDate);
        /*_p($recentDiscussionIds);
        die;*/
        //$recentDiscussionIds = array(3216022,3245816,3196594,1625790);
        $dashboardResult['topTiles']['regular']['Total Discussions Posted'] = count($recentDiscussionIds);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $discussionSplitbyAuthor['dataForCompare'] = $this->getDiscussionSplitByAuthor($compare_startDate,$compare_endDate);
            //_p($discussionSplitbyAuthor['dataForCompare']);
            
            $recentDiscussionIds_Compare = $this->getDiscussionIdBasedOnUserId('','',$compare_startDate,$compare_endDate);
            //_p($recentDiscussionIds_Compare);


            $topTilesResult = $this->topTilesData('','','',$recentDiscussionIds,$startDate,$endDate,array('pageView','avgSessionDuration'));
            $topTilesResult_Compare = $this->topTilesData('','','',$recentDiscussionIds_Compare,$compare_startDate,$compare_endDate,array('pageView','avgSessionDuration'));
            $comments_reg = $this->getTotalCommentsOnDiscussionIds($recentDiscussionIds,$startDate,$endDate);
            $comments_compare = $this->getTotalCommentsOnDiscussionIds($recentDiscussionIds_Compare,$startDate,$compare_endDate);
            $dashboardResult['topTiles']['compare']['Total Discussions Posted'] = count($recentDiscussionIds_Compare);
            $dashboardResult['topTiles']['regular']['Total Comments']        = $comments_reg;
            $dashboardResult['topTiles']['compare']['Total Comments']        = $comments_compare;
            $dashboardResult['topTiles']['regular']['Total Pageviews']       = $topTilesResult['pageView'];
            $dashboardResult['topTiles']['compare']['Total Pageviews']       = $topTilesResult_Compare['pageView'];
            $dashboardResult['topTiles']['compare']['Avg Session Duration']  = $topTilesResult_Compare['avgSessionDuration'];
            $dashboardResult['topTiles']['compare']['Total Sessions']        = $topTilesResult_Compare['totalSessions'];
        }   
        else
        {
            $dataTableResult  = $this->cdMISLib->getPageviewForTopTenDiscussions($recentDiscussionIds,$startDate,$endDate);
            $topTilesResult   = $this->topTilesData('','','',$recentDiscussionIds,$startDate,$endDate,array('avgSessionDuration'));
            $dashboardResult['respondentsResult']           = $dataTableResult['dataTable'];
            $dashboardResult['topTiles']['regular']['Total Comments']  = $dataTableResult['totalComments'];
            $dashboardResult['topTiles']['regular']['Total Pageviews'] = $dataTableResult['totalPageviews'];
        }   
        $dashboardResult['pieChartResult']['d_splitByAuthor'] = $discussionSplitbyAuthor;
        $dashboardResult['pieChartResult']['d_splitBySubcat'] = $discussionSplitbySubcat;
        
        $dashboardResult['topTiles']['regular']['Avg Session Duration']     = $topTilesResult['avgSessionDuration'];
        $dashboardResult['topTiles']['regular']['Total Sessions']           = $topTilesResult['totalSessions'];
        echo $this->load->view('trackingMIS/CD/ContentDashBoard',$dashboardResult);
    }
  
    function getTotalCommentsOnDiscussionIds($discussionId,$startDate,$endDate)
    {
        $totalComments = 0;
        if( ! empty($discussionId))
        {
            $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
            $discussionComments = $cdmismodel->getTotalCommentsOnDiscussionIds($discussionId,$startDate,$endDate);
            
            foreach ($discussionComments as $key => $value) {
                    $totalComments += $value['commentCount'];
                    }
        }
        return $totalComments;
    }
    function getDiscussionDataBySubcat_Database()
    {
        $subCategoryId      = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $source             = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource      = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate          = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate            = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate  = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate    = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise           = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;

        //$userId = array('5213424','4272799','4408658','5043232'); // internal userIds array
        $authorId           = isset($_POST['authorId'])?$this->input->post('authorId'):'';

        $discussionData        = $this->getDiscussionDataBasedOnSubCatId($subCategoryId,$authorId,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
        if( ! empty($authorId))
        {
            $discussionIdArray     = $this->getDiscussionIdBasedOnUserId($subCategoryId,$authorId);
            $DiscussionCommentData = $this->getCommentDataBasedOnSubCatId('',$discussionIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
        }
        else
        {
            $DiscussionCommentData = $this->getCommentDataBasedOnSubCatId($subCategoryId,'',$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);     
        }
        $linearChartResult['DiscussionResult']   = $discussionData;
        $linearChartResult['commentResult']      = $DiscussionCommentData;
        echo json_encode($linearChartResult);
    }
    function getDiscussionDataBySubcat_ElasticSearch()
    {
        $subCategoryId      = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $source             = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource      = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate          = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate            = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate  = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate    = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise           = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;

        //$userId = array('5213424','4272799','4408658','5043232'); // internal userIds array
        $authorId           = isset($_POST['authorId'])?$this->input->post('authorId'):'';        
        $subcatArray = array('subCategoryId' => $subCategoryId,'pageName' => 'discussionPage','authorId' => $authorId);
        $trafficData           = $this->getTrafficData('','','','',$source,$subcatArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$trafficSource);
        $engagementResultData  =  $this->getEngagementDataForCustomerDelivery('','','','',$source,$subcatArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$trafficSource);

        $dashboardResult['topLineChartResults']['trafficData']  = $trafficData;
        $dashboardResult['topLineChartResults']['pageviewData'] = $engagementResultData['pageviewData'];
        $dashboardResult['topLineChartResults']['bounceRate']   = $engagementResultData['bounceRateData'];
        $dashboardResult['topLineChartResults']['avgSessionDuration'] = $engagementResultData['avgSessionDuration'];
        $dashboardResult['topLineChartResults']['avgPagePerSession']  = $engagementResultData['avgPagePerSession'];
        $dashboardResult['topLineChartResults']['exitRateData']       = $engagementResultData['exitRateData'];
        $dashboardResult['pieChartResult']['Traffic-SourceWise']    = $trafficData['Traffic-SourceWise'];
        $dashboardResult['pieChartResult']['Traffic-DeviceWise']    = $trafficData['Traffic-DeviceWise'];
        $dashboardResult['pieChartResult']['PageView-DeviceWise']   = $engagementResultData['pageviewData-DeviceWise'];
        echo json_encode($dashboardResult);

    }
    function getDiscussionDataBySubcat_Map()
    {
        $subCategoryId      = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $source             = isset($_POST['source'])?$this->input->post('source'):'';
        $trafficSource      = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate          = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate            = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate  = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate    = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise           = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;

        //$userId = array('5213424','4272799','4408658','5043232'); // internal userIds array
        $authorId           = isset($_POST['authorId'])?$this->input->post('authorId'):'';        
        $subcatArray = array('subCategoryId' => $subCategoryId,'pageName' => 'discussionPage','authorId' => $authorId);

        $geosplit              = $this->getGeoSplitData('','','','',$subcatArray,$source,$startDate,$endDate,$trafficSource);

        echo $geosplit;
    }
    function getDiscussionDataById_Database()
    {
        $discussionId           = isset($_POST['discussionId'])?$this->input->post('discussionId'):'';
        $trafficSource          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate              = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate                = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate      = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate        = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise               = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        
        $discussionId           = explode(',', $discussionId);

        $DiscussionCommentData  = $this->getCommentDataBasedOnSubCatId('',$discussionId,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);

        $linearChartResult['commentResult']      = $DiscussionCommentData;
        echo json_encode($linearChartResult);
    }
    function getDiscussionDataById_ElastcSearch()
    {
        $discussionId           = isset($_POST['discussionId'])?$this->input->post('discussionId'):'';
        $trafficSource          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate              = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate                = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate      = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate        = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise               = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        
        $discussionId           = explode(',', $discussionId);
        $trafficData            = $this->getTrafficData('','','',$discussionId,'','',$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$trafficSource);
        $engagementResultData   = $this->getEngagementDataForCustomerDelivery('','','',$discussionId,'','',$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$trafficSource);

        $dashboardResult['topLineChartResults']['trafficData']  = $trafficData;
        $dashboardResult['topLineChartResults']['pageviewData'] = $engagementResultData['pageviewData'];
        $dashboardResult['topLineChartResults']['bounceRate']   = $engagementResultData['bounceRateData'];
        $dashboardResult['topLineChartResults']['avgSessionDuration'] = $engagementResultData['avgSessionDuration'];
        $dashboardResult['topLineChartResults']['avgPagePerSession']  = $engagementResultData['avgPagePerSession'];
        $dashboardResult['topLineChartResults']['exitRateData']       = $engagementResultData['exitRateData'];
        $dashboardResult['pieChartResult']['Traffic-SourceWise']    = $trafficData['Traffic-SourceWise'];
        $dashboardResult['pieChartResult']['Traffic-DeviceWise']    = $trafficData['Traffic-DeviceWise'];
        $dashboardResult['pieChartResult']['PageView-DeviceWise']   = $engagementResultData['pageviewData-DeviceWise'];

        echo json_encode($dashboardResult);
    }
    function getDiscussionDataById_Map()
    {
        $discussionId           = isset($_POST['discussionId'])?$this->input->post('discussionId'):'';
        $trafficSource          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate              = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate                = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate      = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate        = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise               = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        
        $discussionId           = explode(',', $discussionId);

        $geosplit               = $this->getGeoSplitData('','','',$discussionId,'',$source,$startDate,$endDate,$trafficSource);
        echo $geosplit;
    }
    function getDiscussionDataBasedOnSubCatId($subCategoryId=array(),$authorId=array(),$source='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1)
    {
        $dataForRegular = $this->cdMISLib->getDiscussionDataBasedOnSubCatId($subCategoryId,$authorId,$source,$startDate,$endDate,$viewWise);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getDiscussionDataBasedOnSubCatId($subCategoryId,$authorId,$source,$compare_startDate,$compare_endDate,$viewWise);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $discussionResultCount =0;
        $discussionData = array();
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $discussionResultCount += $this->getAnswerResultCount($dataForCompare);
            $discussionData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $discussionResultCount += $this->getAnswerResultCount($dataForRegular);
        $discussionData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $discussionData['resultCount'] = $discussionResultCount;

        return $discussionData;   
    }
    function getCommentDataBasedOnSubCatId($subcatId = array(),$discussionId=array(),$source='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1)
    {
        $dataForRegular = $this->cdMISLib->getCommentDataBasedOnSubCatId($subcatId,$discussionId,$source,$startDate,$endDate,$viewWise);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getCommentDataBasedOnSubCatId($subcatId,$discussionId,$source,$compare_startDate,$compare_endDate,$viewWise);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $commentResultCount =0;
        $commentData = array();
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $commentResultCount += $this->getAnswerResultCount($dataForCompare);
            $commentData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $commentResultCount += $this->getAnswerResultCount($dataForRegular);
        $commentData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $commentData['resultCount'] = $commentResultCount;

        return $commentData; 
    }

    function getDiscussionIdBasedOnUserId($subCategoryId=array(),$authorId = array(),$startDate='',$endDate ='')
    {   
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getDiscussionIdBasedOnUserId($subCategoryId,$authorId,$startDate,$endDate);
        $discussionIdArray = array();
        foreach ($result as $key => $value) {
            $discussionIdArray[$i++] = $value['discussionId'];
        }
        return $discussionIdArray;

    }
    function getCoursesInUniversity()
    {
        $universityId = (isset($_POST ['universityId']) )? $this->input->post('universityId'):'';
        $universityIdArray = explode(',',$universityId);
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $university_course_mapping = array();
        foreach ($universityIdArray as $key => $value) {
            $universityName = $cdmismodel->getInstituteName($value,'university');
            $result=$cdmismodel->getCoursesInUniversity($value);    
            $university_course_mapping[$value.'-'.$universityName] = array();
            $i = 0;
            foreach ($result as $university => $courses) {
                $university_course_mapping[$value.'-'.$universityName][$i++] = array('courseId'=>$courses['courseId'],
                                                                  'courseTitle'=>$courses['courseTitle']);
            }
        }
        //error_log('===========result courses'.'/'.print_r($result,true));
        if(count($university_course_mapping)>0)
                echo json_encode($university_course_mapping);
        else
            echo "None";

    }
    function topTilesData($instituteId=array(),$courseId=array(),$articleId=array(),$discussionId=array(),$startDate='',$endDate='',$topTilesFetch = array(),$isStudyAbroad='no',$source='')
    {
        if( ! empty($instituteId) || ! empty($courseId))
        {
            $extraData['CD']['instituteId'] = $instituteId;
            $extraData['CD']['courseId'] = $courseId;
            $extraData['CD']['deviceType'] = strtolower($source);
        }
        else if ( ! empty($articleId))
        {
            $extraData['CD']['articleId'] = $articleId;
        }
        else if( ! empty($discussionId))
        {
            $extraData['CD']['discussionId'] = $discussionId;
        }
        if( $isStudyAbroad == 'yes')
            $extraData['CD']['isStudyAbroad'] = 'yes';
        $extraData['CD']['Overview'] = 1;
        $dateRangeArray['startDate'] = $startDate;
        $dateRangeArray['endDate'] = $endDate;
        $this->engagementLib = $this->load->library('trackingMIS/engagementLib');
        $topTilesResult = array();
        foreach ($topTilesFetch as $fetch) {
            switch ($fetch) {
                case 'pageView':
                        $pageViewResult = $this->engagementLib->getPageviewData($dateRangeArray,'',$extraData);
                        $totalPageviews = $pageViewResult['hits']['total'];
                        $topTilesResult['pageView'] = empty($totalPageviews)?0:number_format($totalPageviews);
                        break;
                case 'bounceRate':
                        $bounceResult = $this->engagementLib->getBounceRateCD($dateRangeArray,$extraData);
                        $totalSessions = $bounceResult['hits']['total'];
                        $totalBounceSessions = $bounceResult['aggregations']['bounceSessions']['doc_count'];
                        //number_format((($value*100)/$total), 2, '.', '')
                        $bounceRate = number_format(($totalBounceSessions / $totalSessions) * 100,2,'.','');
                        $topTilesResult['bounceRate'] = $bounceRate;
                        break;
                case 'exitRate' :
                        $exitResult = $this->engagementLib->getExitRateCD($dateRangeArray,$extraData);
                        $exitValue = $exitResult['hits']['total'];
                        $exitRate = number_format(($exitValue / $totalPageviews) * 100,2,'.','');
                        $topTilesResult['exitRate'] = $exitRate;
                        break;
                case 'avgPagePerSession':
                        $avgPagePerSessionResult = $this->engagementLib->getAvgPagePerSessionCD($dateRangeArray,$extraData);
                        $totalSessions = $avgPagePerSessionResult['hits']['total'];
                        $totalPageviewsInSession = $avgPagePerSessionResult['aggregations']['totalPageViews']['value'];
                        $avgPagePerSession = number_format(($totalPageviewsInSession / $totalSessions),2,'.','');
                        $topTilesResult['avgPagePerSession'] = $avgPagePerSession;
                        break;
                case 'avgSessionDuration':
                        $avgSessionDurationResult = $this->engagementLib->getAvgSessionDurationCD($dateRangeArray,$extraData);
                        $totalSessions = $avgSessionDurationResult['hits']['total'];
                        $totalDuration = $avgSessionDurationResult['aggregations']['totalDuration']['value'];
                        $avgSessionDuration = number_format(($totalDuration / $totalSessions),2,'.','');
                        $hoursFormat = explode('.', $avgSessionDuration);
                        $topTilesResult['avgSessionDuration'] = date('H:i:s', mktime(0, 0, $avgSessionDuration)).'.'.$hoursFormat[1];
                        $topTilesResult['totalSessions'] = empty($totalSessions)?0:number_format($totalSessions);
                        break;
                case 'uniqueUsers':
                        $uniqueUsers = $this->engagementLib->getUniqueUserCountForCustomerDelivery($dateRangeArray,$extraData);
                        $topTilesResult['uniqueUsers'] = number_format($uniqueUsers);
            }
        }
        return $topTilesResult;

    }
    
    function getDomesticContentOverview()
    {
        $startDate         = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate           = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate   = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');

        $articleSplitBySubcat = $this->getRecentArticlesPostedBySubcat($startDate,$endDate,$compare_startDate,$compare_endDate);
        $articleSplitByAuthor['dataForRegular'] = $this->getRecentArticlesPostedByAuthor($startDate,$endDate);
        $recentArticleIds     = $this->getArticleIds($startDate,$endDate);
        
        $dashboardResult['topTiles']['regular']['Total Articles Posted'] = count($recentArticleIds);

        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $articleSplitByAuthor['dataForCompare'] = $this->getRecentArticlesPostedByAuthor($compare_startDate,$compare_endDate);
            $recentArticleIds_Compare = $this->getArticleIds($compare_startDate,$compare_endDate);
            $topTilesResult       = $this->topTilesData('','',$recentArticleIds,'',$startDate,$endDate,array('pageView','avgSessionDuration')); 
            $topTilesResult_Compare       = $this->topTilesData('','',$recentArticleIds_Compare,'',$compare_startDate,$compare_endDate,array('pageView','avgSessionDuration'));
            $comments_reg = $this->getTotalArticleCommentData($recentArticleIds,$startDate,$endDate);
            $comments_compare = $this->getTotalArticleCommentData($recentArticleIds_Compare,$compare_startDate,$compare_endDate);

            $dashboardResult['topTiles']['compare']['Total Articles Posted'] = count($recentArticleIds_Compare);
            $dashboardResult['topTiles']['regular']['Total Comments']        = $comments_reg;
            $dashboardResult['topTiles']['compare']['Total Comments']        = $comments_compare;
            $dashboardResult['topTiles']['regular']['Total Pageviews']       = $topTilesResult['pageView'];
            $dashboardResult['topTiles']['compare']['Total Pageviews']       = $topTilesResult_Compare['pageView'];
            $dashboardResult['topTiles']['compare']['Avg Session Duration']  = $topTilesResult_Compare['avgSessionDuration'];
            $dashboardResult['topTiles']['compare']['Total Sessions']        = $topTilesResult_Compare['totalSessions'];
            
        }
        else
        {
            $topTilesResult       = $this->topTilesData('','',$recentArticleIds,'',$startDate,$endDate,array('avgSessionDuration'));
            $dataTableResult      = $this->cdMISLib->getPageviewForTopTenArticles($recentArticleIds,$startDate,$endDate);
            $dashboardResult['topTiles']['regular']['Total Comments']        = $dataTableResult['totalComments'];
            $dashboardResult['topTiles']['regular']['Total Pageviews']       = $dataTableResult['totalPageviews'];

        }

        $dashboardResult['pieChartResult']['splitByAuthor'] = $articleSplitByAuthor;
        $dashboardResult['pieChartResult']['splitBySubcat'] = $articleSplitBySubcat;

        $dashboardResult['respondentsResult']  = $dataTableResult['dataTable'];
        $dashboardResult['topTiles']['regular']['Avg Session Duration']  = $topTilesResult['avgSessionDuration'];
        $dashboardResult['topTiles']['regular']['Total Sessions']        = $topTilesResult['totalSessions'];
        $dashboardResult['page'] = 'contentDomesticOverview';
    
        echo $this->load->view('trackingMIS/CD/ContentDashBoard',$dashboardResult);
    }
    function getTotalArticleCommentData($articleId,$startDate,$endDate)
    {
        $totalComments = 0;
        if( ! empty($articleId))
        {
            $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getTotalArticleCommentData($articleId,$startDate,$endDate);
        
            foreach($result as $key => $value) {
                $totalComments += $value['commentCount'];
                }
        }
        return $totalComments;

    }
    function getTopTilesData($instituteId=array(),$courseId=array(),$articleId=array(),$discussionId=array(),$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$topTilesFetch = array(),$isStudyAbroad='no')
    {
        $topTileDataForRegular = $this->topTilesData($instituteId,$courseId,$articleId,$discussionId,$startDate,$endDate,$topTilesFetch,$isStudyAbroad); 
        $topTilesData['dataForRegular'] = $topTileDataForRegular;
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $topTileDataForCompare = $this->topTilesData($instituteId,$courseId,$articleId,$discussionId,$compare_startDate,$compare_endDate,$topTilesFetch,$isStudyAbroad);
        }

    }
    function getStudyAbroadContentOverview()
    {
        $startDate         = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate           = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate   = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');

        $articleSplitBySubcat = $this->getRecentArticlesPostedBySubcat_SA($startDate,$endDate,$compare_startDate,$compare_endDate);
        $articleSplitByAuthor['dataForRegular'] = $this->getArticlesPostedByAuthorInSA($startDate,$endDate);

        $recentArticleIds     = $this->getRecentArticlesPostedInSA($startDate,$endDate);
        $dashboardResult['topTiles']['regular']['Total Articles Posted'] = count($recentArticleIds);
        if( ! empty($compare_startDate) && !empty($compare_endDate))
        {   
            $articleSplitByAuthor['dataForCompare'] = $this->getArticlesPostedByAuthorInSA($compare_startDate,$compare_endDate);
            $recentArticleIds_Compare = $this->getRecentArticlesPostedInSA($compare_startDate,$compare_endDate);
            $topTilesResult = $this->topTilesData('','',$recentArticleIds,'',$startDate,$endDate,array('pageView','avgSessionDuration'),'yes');
            $topTilesResult_Compare = $this->topTilesData('','',$recentArticleIds_Compare,'',$compare_startDate,$compare_endDate,array('pageView','avgSessionDuration'),'yes');
            $comments_reg = $this->getTotalArticleCommentData_SA($recentArticleIds,$startDate,$endDate);
            $comments_compare = $this->getTotalArticleCommentData_SA($recentArticleIds_Compare,$compare_startDate,$compare_endDate);
            $dashboardResult['topTiles']['compare']['Total Articles Posted'] = count($recentArticleIds_Compare);
            $dashboardResult['topTiles']['regular']['Total Comments']        = $comments_reg;
            $dashboardResult['topTiles']['compare']['Total Comments']        = $comments_compare;
            $dashboardResult['topTiles']['regular']['Total Pageviews']       = $topTilesResult['pageView'];
            $dashboardResult['topTiles']['compare']['Total Pageviews']       = $topTilesResult_Compare['pageView'];
            $dashboardResult['topTiles']['compare']['Avg Session Duration']  = $topTilesResult_Compare['avgSessionDuration'];
            $dashboardResult['topTiles']['compare']['Total Sessions']        = $topTilesResult_Compare['totalSessions'];
        }
        else
        {
            $dataTableResult      = $this->cdMISLib->getPageviewForTopTenArticles($recentArticleIds,$startDate,$endDate,'yes');
            $topTilesResult       = $this->topTilesData('','',$recentArticleIds,'',$startDate,$endDate,array('avgSessionDuration'),'yes');
            $dashboardResult['topTiles']['regular']['Total Comments']        = $dataTableResult['totalComments'];
            $dashboardResult['topTiles']['regular']['Total Pageviews']       = $dataTableResult['totalPageviews'];
            $dashboardResult['respondentsResult'] = $dataTableResult['dataTable'];
        }
        $dashboardResult['pieChartResult']['splitByAuthor'] = $articleSplitByAuthor;
        $dashboardResult['pieChartResult']['splitBySubcat'] = $articleSplitBySubcat;
        $dashboardResult['topTiles']['regular']['Avg Session Duration']  = $topTilesResult['avgSessionDuration'];
        $dashboardResult['topTiles']['regular']['Total Sessions']        = $topTilesResult['totalSessions'];
        $dashboardResult['page'] = 'contentStudyAbroadOverview';
        echo $this->load->view('trackingMIS/CD/ContentDashBoard',$dashboardResult);

    }
    function getTotalArticleCommentData_SA($articleId,$startDate,$endDate)
    {
        $totalComments = 0;
        if( ! empty($articleId))
        {
            $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getTotalArticleCommentData_SA($articleId,$startDate,$endDate);
            foreach ($result as $key => $value) {
                        $totalComments += $value['commentCount'];
                    }
        }
        return $totalComments;
    }
    function get_actual_delivery_toClient()
    {
        $emailId           = isset($_POST['emailId'])?$this->input->post('emailId'):'';
        $startDate         = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate           = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate   = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise          = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        $emailId    = explode(',', $emailId);

        $clientIdArray = $this->getUserId_by_Email($emailId);
        $leadDelivery = $this->getLeadDeliveryForClient($clientIdArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'both');
        $responseDelivery = $this->getResponseDeliveryForClient($clientIdArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'both');
        

        $dashboardResult['topLineChartResults']['leadDelivery']           = $leadDelivery;
        $dashboardResult['topLineChartResults']['responseDelivery']       = $responseDelivery;
        echo $this->load->view('trackingMIS/CD/CD_DashBoard',$dashboardResult);

    }
    function getUserId_by_Email($emailId)
    {
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getUserId_by_Email($emailId);
        $clientIdArray = array();
        $i = 0;
        foreach ($result as $key => $value) {
            $clientIdArray[$i++] = $value['userid'];
        }
        return $clientIdArray;
    }
    function getArticlesPostedByAuthorInSA($startDate,$endDate)
    {
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getArticlesPostedByAuthorInSA($startDate,$endDate);
        $authorId = array();
        $i = 0;
        foreach ($result as $key => $value) {
            $authorId[$i++] = $value['authorId'];
        }
        if(!empty($authorId))
        {
            $authorNames = $cdmismodel->getAuthorNames($authorId);
            foreach ($result as $key => $value) {
                $result[$key]['authorId'] = $authorNames[$key]['firstName'].' '.$authorNames[$key]['lastName'];
            }
        }
        $splitByAuthor = $this->preparePieChart($result);
        return $splitByAuthor;
    }
    function getRecentArticlesPostedByAuthor($startDate,$endDate)
    {
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $result =  $cdmismodel->getRecentArticlesPostedByAuthor($startDate,$endDate);
        $authorId = array();
        $i = 0;
        foreach ($result as $key => $value) {
            $authorId[$i++] = $value['authorId'];
        }
        if(!empty($authorId))
        {
            $authorNames = $cdmismodel->getAuthorNames($authorId);
            foreach ($result as $key => $value) {
                $result[$key]['authorId'] = $authorNames[$key]['firstName'].' '.$authorNames[$key]['lastName'];
            }
        }
        $splitByAuthor = $this->preparePieChart($result);
        return $splitByAuthor;

    }
    function getRecentArticlesPostedInSA($startDate,$endDate)
    {
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getRecentArticlesPostedInSA($startDate,$endDate);
        $articleId = array();
        $i = 0;
        foreach ($result as $key => $value) {
            $articleId[$i++] = $value['content_id'];
        }
        return $articleId;
    }
    function getRecentArticlesPostedBySubcat_SA($startDate,$endDate,$compare_startDate='',$compare_endDate='')
    {
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getRecentArticlesPostedBySubcat_SA($startDate,$endDate);
        $subcatNames = $cdmismodel->getSubcategory('studyabroad');
        $subcatIdNames = array();
        foreach ($subcatNames as $key => $value) {
            $subcatIdNames[$value['subcatId']] = $value['subcatName'];
        }
        $subcatIdNames['0'] = 'No Subcategory';
        $i = 0;
        foreach ($result as $keyName => $keyValue) {
            if( empty($keyValue['subcatId']) || is_null($keyValue['subcatId']))
                $keyValue['subcatId'] = '0';
            $result[$i++]['subcatId'] = $subcatIdNames[$keyValue['subcatId']];
        }
        
        $articleSplitBySubcat['dataForRegular'] = $this->prepareDataForCharts($result);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $resultForCompare = $cdmismodel->getRecentArticlesPostedBySubcat_SA($compare_startDate,$compare_endDate);
            $i =0;
            foreach ($resultForCompare as $keyName => $keyValue) {
                    if( empty($keyValue['subcatId']) || is_null($keyValue['subcatId']))
                        $keyValue['subcatId'] = '0';
                    $resultForCompare[$i++]['subcatId'] = $subcatIdNames[$keyValue['subcatId']];       
            }
            $articleSplitBySubcatForCompare = $this->prepareDataForCharts($resultForCompare);
            $articleSplitBySubcat['dataForCompare'] = $articleSplitBySubcatForCompare;
        }
        //$articleSplitBySubcat['dataForRegular'] = $articleSplitBySubcatForRegular;
        return $articleSplitBySubcat;
    }
    function getArticleIds($startDate,$endDate)
    {
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getArticleIds('','',$startDate,$endDate);
        $articleId = array();
        foreach ($result as $key => $value) {
            $articleId[$i++] = $value['blogId'];
        }
        return $articleId;
    }
    function getRecentArticlesPostedBySubcat($startDate,$endDate,$compare_startDate='',$compare_endDate='')
    {
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getRecentArticlesPostedBySubcat($startDate,$endDate);
        $subcatNames = $cdmismodel->getSubcategory('national');
        $subcatIdNames = array();
        foreach ($subcatNames as $key => $value) {
            $subcatIdNames[$value['subcatId']] = $value['subcatName'];
        }
        $i = 0;
        $totalArticles = 0;
        foreach ($result  as $keyName => $keyValue) {
            $totalArticles += $keyValue['articleCount'];
            $result[$i++]['subcatId'] = $subcatIdNames[$keyValue['subcatId']];
        }
        $articleSplitBySubcat['dataForRegular'] = $this->prepareDataForCharts($result);
         
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $resultForCompare = $cdmismodel->getRecentArticlesPostedBySubcat($compare_startDate,$compare_endDate);
            $i = 0;
            foreach ($resultForCompare as $keyName => $keyValue) {
                    $resultForCompare[$i++]['subcatId'] = $subcatIdNames[$keyValue['subcatId']];
            }
            $articleSplitBySubcatForCompare = $this->prepareDataForCharts($resultForCompare);
            $articleSplitBySubcat['dataForCompare'] = $articleSplitBySubcatForCompare;
        }
        
        return $articleSplitBySubcat;

    }
    function getClientIdForInstitute_Univeristy($instituteId,$type = 'institute')
    {
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        if( ! empty($instituteId))
            $clientId = $cdmismodel->getClientIdForInstitute($instituteId,$type);
        $clientIdArray = array();
        $i = 0;
        foreach ($clientId as $key => $value) {
            $clientIdArray[$i++] = $value['clientId'];
        }
        return $clientIdArray;
    }
    function getActualDeliveryToClient($clientId,$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1)
    {
        $leadDeliveryResultCount = 0;
        $responseDeliveryResultCount = 0;
        $actualDeliveryDataForRegular = $this->cdMISLib->getActualDeliveryToClient($clientId,$startDate,$endDate,$viewWise);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $actualDeliveryDataForCompare = $this->cdMISLib->getActualDeliveryToClient($clientId,$compare_startDate,$compare_endDate,$viewWise);
        }
        $leadDeliveryForRegular = $actualDeliveryDataForRegular['leadDeliveryArray'];
        $leadDeliveryForRegular = $this->getDataBasedOnView($leadDeliveryForRegular,$viewWise,$startDate,$endDate);
        $responseDeliveryForRegular = $actualDeliveryDataForRegular['responseDeliveryArray'];
        $responseDeliveryForRegular = $this->getDataBasedOnView($responseDeliveryForRegular,$viewWise,$startDate,$endDate);
        $leadDeliveryResultCount += $this->getAnswerResultCount($leadDeliveryForRegular);
        $responseDeliveryResultCount += $this->getAnswerResultCount($responseDeliveryForRegular);
        
        $leadDeliveryLinechart = json_encode($this->prepareDataForLineChart1($leadDeliveryForRegular));
        $responseDeliveryLineChart = json_encode($this->prepareDataForLineChart1($responseDeliveryForRegular));
        $result['leadDelivery']['dataForRegular'] = $leadDeliveryLinechart;
        
        $result['responseDelivery']['dataForRegular'] = $responseDeliveryLineChart;
        
        if( isset($actualDeliveryDataForCompare))
        {
            $leadDeliveryForCompare = $actualDeliveryDataForCompare['leadDeliveryArray'];
            $leadDeliveryForCompare = $this->getDataBasedOnView($leadDeliveryForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $responseDeliveryForCompare = $actualDeliveryDataForCompare['responseDeliveryArray'];
            $responseDeliveryForCompare = $this->getDataBasedOnView($responseDeliveryForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $leadDeliveryResultCount += $this->getAnswerResultCount($leadDeliveryForCompare);
            $responseDeliveryResultCount += $this->getAnswerResultCount($responseDeliveryForCompare);
            
            $leadDeliveryLinechartForCompare = json_encode($this->prepareDataForLineChart1($leadDeliveryForCompare));
            $responseDeliveryLineChartForCompare = json_encode($this->prepareDataForLineChart1($responseDeliveryForCompare));
            $result['leadDelivery']['dataForCompare'] = $leadDeliveryLinechartForCompare;
            $result['responseDelivery']['dataForCompare'] = $responseDeliveryLineChartForCompare;
        }
        $result['leadDelivery']['resultCount'] = $leadDeliveryResultCount;
        $result['responseDelivery']['resultCount'] = $responseDeliveryResultCount;
        return $result;
    }
    function getDiscussionPostedInSubcat($startDate,$endDate,$compare_startDate='',$compare_endDate='')
    {
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getDiscussionPostedInSubcat($startDate,$endDate);
        $subcatNames = $cdmismodel->getSubcategory('national');
        $subcatIdNames = array();
        foreach ($subcatNames as $key => $value) {
            $subcatIdNames[$value['subcatId']] = $value['subcatName'];
        }
        $i = 0;
        $discussionSplitSubcatForRegular = array();
        $i = 0;
        foreach ($result as $keyName => $keyValue) {
            if(array_key_exists($keyValue['subcatId'], $subcatIdNames))
                    $discussionSplitSubcatForRegular[$i++] = array('subcatId' =>$subcatIdNames[$keyValue['subcatId']],
                                        'responsescount' => $keyValue['responsescount']);
        }
        $discussionSplitSubcat['dataForRegular'] = $this->prepareDataForCharts($discussionSplitSubcatForRegular);
        if( !empty($compare_startDate) && ! empty($compare_endDate))
        {
            $resultForCompare = $cdmismodel->getDiscussionPostedInSubcat($compare_startDate,$compare_endDate);
            $discussionSplitSubcatForCompare = array();
            $i =0;
            foreach ($resultForCompare as $keyName => $keyValue) {
                    if(array_key_exists($keyValue['subcatId'], $subcatIdNames))
                        $discussionSplitSubcatForCompare[$i++] = array('subcatId' =>$subcatIdNames[$keyValue['subcatId']],
                                        'responsescount' => $keyValue['responsescount']);       
            }
            $discussionSplitSubcat['dataForCompare'] = $this->prepareDataForCharts($discussionSplitSubcatForCompare);
        }
        return $discussionSplitSubcat;
    }
    function getDiscussionSplitByAuthor($startDate,$endDate)
    {
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $result =$cdmismodel->getDiscussionSplitByAuthor($startDate,$endDate);
        $authorId = array();
        $i = 0;
        foreach ($result as $key => $value) {
            $authorId[$i++] = $value['authorId'];
        }
        //$pattern = "/[^\w.-]/";
        $newstr = preg_replace('/[^a-zA-Z0-9\']/', '_', "There wouldn't be any");
        $newstr = str_replace("'", '', $newstr);
        if(!empty($authorId))
        {
            $authorNames = $cdmismodel->getAuthorNames($authorId);
            foreach ($result as $key => $value) {
             $result[$key]['authorId'] = ($authorNames[$key]['firstName']).' '.($authorNames[$key]['lastName']);
            /* _p($result[$key]['authorId']);
             continue;*/
             
             //preg_replace($pattern,"",$result[$key]['authorId']);
            // preg_replace("/[^a-zA-Z0-9]/", "_", $result[$key]['authorId']);
             /*$result[$key]['authorId'] = str_replace("'", '', $result[$key]['authorId']);
             $result[$key]['authorId'] = str_replace("\"", '', $result[$key]['authorId']);
             $result[$key]['authorId'] = str_replace("\\", '', $result[$key]['authorId']);
            /[^\p{L}\p{N}]/u
            (^[\'][\"][\\])
             */
             $result[$key]['authorId'] = preg_replace("/[^\p{L}\p{N}]/u","", $result[$key]['authorId']);
            }

        }
        $splitByAuthor = $this->preparePieChart($result);
        return $splitByAuthor;
    }
    function getDeliveryToClient($clientId,$startDate,$endDate)
    {
        $result = $this->cdMISLib->getDeliveryToClient($clientId,$startDate,$endDate);
        return $result;
    }
    function getOverallTopTileForDomestic()
    {
        $startDate   = empty($_GET['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->get('startDate');
        $endDate      = empty($_GET['endDate'])?date("Y-m-d"):$this->input->get('endDate');

        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $clientId       = $this->cdMISLib->getInstituteClientIds();
        $leadResponse   = $this->cdMISLib->getLeadResponseForClients($clientId,$startDate,$endDate);
        $totalSales     = $cdmismodel->getTotalSales($clientId,$startDate,$endDate);
        $totalCollections = $this->cdMISLib->getZoneWiseCollections($clientId,$startDate,$endDate);
        $topTilesData   = array(count($clientId),number_format($totalSales,2,'.',''),number_format($totalCollections,2,'.',''),$leadResponse['leadCount'],$leadResponse['responsesCount']);
        //$topTilesData = array(120,100,130,140,160);
        echo json_encode($topTilesData);
    }
    function getOverallTopTileForStudyAbroad()
    {
        $startDate   = empty($_GET['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->get('startDate');
        $endDate      = empty($_GET['endDate'])?date("Y-m-d"):$this->input->get('endDate');

        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $clientId       = $this->cdMISLib->getInstituteClientIds('','university');
        $leadResponse   = $this->cdMISLib->getLeadResponseForClients($clientId,$startDate,$endDate,'studyabroad');
        $totalSales     = $cdmismodel->getTotalSales($clientId,$startDate,$endDate);
        $totalCollections = $this->cdMISLib->getZoneWiseCollections($clientId,$startDate,$endDate);
        $topTilesData   = array(count($clientId),number_format($totalSales,2,'.',''),number_format($totalCollections,2,'.',''),$leadResponse['leadCount'],$leadResponse['responsesCount']);
        //$topTilesData = array(120,100,130,140,160);
        echo json_encode($topTilesData);
    }
    function getNorthZoneTopTile()
    {

         $startDate            = empty($_GET['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->get('startDate');
        $endDate              = empty($_GET['endDate'])?date("Y-m-d"):$this->input->get('endDate');
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $northZone = array(128,103,105,110,111,126,133);
        $i = 0;
                 $instituteId = $cdmismodel->getInstitutesBasedOnCity('','',$northZone);
                 $instituteIdArray = array();
                 $i = 0 ;
                 foreach ($instituteId as $key => $value) {
                     $instituteIdArray[$i++] = $value['instituteId'];
                 }
                 $clientId_zoneWise = $this->cdMISLib->getInstituteClientIds($instituteIdArray);
                 $zone_wise_result = $this->cdMISLib->getLeadResponseForClients($clientId_zoneWise,$startDate,$endDate);
                 $totalClients = count($clientId_zoneWise);
                 $totalSales = $cdmismodel->getTotalSales($clientId_zoneWise,$startDate,$endDate);

                 $totalCollections = $this->cdMISLib->getZoneWiseCollections($clientId_zoneWise,$startDate,$endDate);

                 $topTilesData = array($totalClients,number_format($totalSales,2,'.',''),number_format($totalCollections,2,'.',''),$zone_wise_result['leadCount'],$zone_wise_result['responsesCount']);
        echo json_encode($topTilesData);
    }
    function getSouthZoneTopTile()
    {

         $startDate            = empty($_GET['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->get('startDate');
        $endDate              = empty($_GET['endDate'])?date("Y-m-d"):$this->input->get('endDate');
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');

        $southZone = array(100,108,106,107,130,113,114,123,413);
        $i = 0;
                 $instituteId = $cdmismodel->getInstitutesBasedOnCity('','',$southZone);
                 $instituteIdArray = array();
                 $i = 0 ;
                 foreach ($instituteId as $key => $value) {
                     $instituteIdArray[$i++] = $value['instituteId'];
                 }
                 $clientId_zoneWise = $this->cdMISLib->getInstituteClientIds($instituteIdArray);
                 $zone_wise_result = $this->cdMISLib->getLeadResponseForClients($clientId_zoneWise,$startDate,$endDate);
                 $totalClients = count($clientId_zoneWise);
                 $totalSales = $cdmismodel->getTotalSales($clientId_zoneWise,$startDate,$endDate);

                 $totalCollections = $this->cdMISLib->getZoneWiseCollections($clientId_zoneWise,$startDate,$endDate);

                 $topTilesData = array($totalClients,number_format($totalSales,2,'.',''),number_format($totalCollections,2,'.',''),$zone_wise_result['leadCount'],$zone_wise_result['responsesCount']);
        echo json_encode($topTilesData);
    }
    function getEastZoneTopTile()
    {

         $startDate            = empty($_GET['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->get('startDate');
        $endDate              = empty($_GET['endDate'])?date("Y-m-d"):$this->input->get('endDate');
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        
        $eastZone = array(102,101,102,134,104,112,115,116,117,118,119,131,122,124,127);
        $i = 0;
                 $instituteId = $cdmismodel->getInstitutesBasedOnCity('','',$eastZone);
                 $instituteIdArray = array();
                 $i = 0 ;
                 foreach ($instituteId as $key => $value) {
                     $instituteIdArray[$i++] = $value['instituteId'];
                 }
                 $clientId_zoneWise = $this->cdMISLib->getInstituteClientIds($instituteIdArray);
                $zone_wise_result = $this->cdMISLib->getLeadResponseForClients($clientId_zoneWise,$startDate,$endDate);
                 $totalClients = count($clientId_zoneWise);
                 $totalSales = $cdmismodel->getTotalSales($clientId_zoneWise,$startDate,$endDate);

                 $totalCollections = $this->cdMISLib->getZoneWiseCollections($clientId_zoneWise,$startDate,$endDate);

                 $topTilesData = array($totalClients,number_format($totalSales,2,'.',''),number_format($totalCollections,2,'.',''),$zone_wise_result['leadCount'],$zone_wise_result['responsesCount']);
        echo json_encode($topTilesData);
    }
    function getWestZoneTopTile()
    {

         $startDate            = empty($_GET['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->get('startDate');
        $endDate              = empty($_GET['endDate'])?date("Y-m-d"):$this->input->get('endDate');
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        
        $westZone = array(121,136,135,109,120,121,12);
        $i = 0;
                 $instituteId = $cdmismodel->getInstitutesBasedOnCity('','',$westZone);
                 $instituteIdArray = array();
                 $i = 0 ;
                 foreach ($instituteId as $key => $value) {
                     $instituteIdArray[$i++] = $value['instituteId'];
                 }
                 $clientId_zoneWise = $this->cdMISLib->getInstituteClientIds($instituteIdArray);
                $zone_wise_result = $this->cdMISLib->getLeadResponseForClients($clientId_zoneWise,$startDate,$endDate);
                 $totalClients = count($clientId_zoneWise);
                 $totalSales = $cdmismodel->getTotalSales($clientId_zoneWise,$startDate,$endDate);

                 $totalCollections = $this->cdMISLib->getZoneWiseCollections($clientId_zoneWise,$startDate,$endDate);

                 $topTilesData = array($totalClients,number_format($totalSales,2,'.',''),number_format($totalCollections,2,'.',''),$zone_wise_result['leadCount'],$zone_wise_result['responsesCount']);
        echo json_encode($topTilesData);
    }
    function getDataTableForOverview()
    {
          $startDate            = empty($_GET['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->get('startDate');
        $endDate              = empty($_GET['endDate'])?date("Y-m-d"):$this->input->get('endDate');
        $clientId       = $this->cdMISLib->getInstituteClientIds();
        $dataTableResult = $this->cdMISLib->getClientDeliveryData($clientId,$startDate,$endDate);
        echo json_encode($dataTableResult);

    }
    function getDataTableForStudyAbroadOverview()
    {
          $startDate            = empty($_GET['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->get('startDate');
        $endDate              = empty($_GET['endDate'])?date("Y-m-d"):$this->input->get('endDate');
        $clientId       = $this->cdMISLib->getInstituteClientIds('','university');
        $dataTableResult = $this->cdMISLib->getClientDeliveryData($clientId,$startDate,$endDate,'university');
        echo json_encode($dataTableResult);

    }
    function getDomesticOverview()
    {   
        $this->load->config('cdTrackingMISConfig');
        $topTiles = $this->config->item('topTiles');

        $topTiles = $topTiles['DomesticOverview'];
        
        $startDate            = empty($_POST['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->post('startDate');
        $endDate              = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $clientId = $this->cdMISLib->getInstituteClientIds();
        $dataTableResult = $this->cdMISLib->getClientDeliveryData($clientId,$startDate,$endDate);

        //$overallIndia_result = $this->cdMISLib->getLeadResponseForClients($clientId,$startDate,$endDate);
        $totalClients = count($clientId);
        $totalSales       = $cdmismodel->getTotalSales($clientId,$startDate,$endDate);
        $totalCollections = $this->cdMISLib->getZoneWiseCollections($clientId,$startDate,$endDate);
        $topTilesData[0] = array($totalClients,number_format($totalSales,2,'.',''),number_format($totalCollections,2,'.',''));
         $zone_wise = array('north_zone' => array(128,103,105,110,111,126,133),
                                'south_zone' => array(100,108,106,107,130,113,114,123,413),
                                'east_zone' => array(102,101,102,134,104,112,115,116,117,118,119,131,122,124,127),
                                'west_zone' => array(121,136,135,109,120,121,12)
                                );
         $zone_wise_result = array();

         $j = 1;
         foreach ($zone_wise as $key => $value) {
                $i = 0;
                $cityId = array();
                $result = $cdmismodel->getCityBasedOnZone($value);
                    foreach ($result as $cityKey => $city) {
                        $cityId[$i++] = $city['cityId'];
                    }
                 $instituteId = $cdmismodel->getInstitutesBasedOnCity('',$cityId);
                 $instituteIdArray = array();
                 $i = 0 ;
                 foreach ($instituteId as $key => $value) {
                     $instituteIdArray[$i++] = $value['instituteId'];
                 }
                 $clientId_zoneWise = $this->cdMISLib->getInstituteClientIds($instituteIdArray);
                 $zone_wise_result = $this->cdMISLib->getLeadResponseForClients($clientId_zoneWise,$startDate,$endDate);
                 $totalClients = count($clientId_zoneWise);
                 $totalSales = $cdmismodel->getTotalSales($clientId_zoneWise,$startDate,$endDate);

                 $totalCollections = $this->cdMISLib->getZoneWiseCollections($clientId_zoneWise,$startDate,$endDate);

                 $topTilesData[$j++] = array($totalClients,number_format($totalSales,2,'.',''),number_format($totalCollections,2,'.',''),$zone_wise_result['leadCount'],$zone_wise_result['responseCount']);
             }

             $dashboardResult['respondentsResult'] = $dataTableResult;
             $dashboardResult['topTilesData'] = $topTilesData;
             $dashboardResult['topTiles'] = $topTiles;
             
            echo $this->load->view('trackingMIS/CD/actualDeliveryDashBoard',$dashboardResult);
    }
    function Overview_Data()
    {
        $startDate            = empty($_POST['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->post('startDate');
        $endDate              = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $clientId = $this->cdMISLib->getInstituteClientIds();
        $this->cdMISLib->getClientDeliveryData($clientId,$startDate,$endDate);
    }
    function getLeadDeliveryForClient($clientId,$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise = 1,$flag = 'national')
    {
        $dataForRegular = $this->cdMISLib->getLeadDeliveryForClient($clientId,$startDate,$endDate,$viewWise,$flag);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getLeadDeliveryForClient($clientId,$compare_startDate,$compare_endDate,$viewWise,$flag);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $leadDelivery = array();
        $leadDeliveryCount = 0;
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $leadDeliveryCount += $this->getAnswerResultCount($dataForCompare);
            $leadDelivery['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $leadDeliveryCount += $this->getAnswerResultCount($dataForRegular);
        $leadDelivery['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $leadDelivery['resultCount'] = $leadDeliveryCount;
        return $leadDelivery;
    }
    function getResponseDeliveryForClient($clientId,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$flag = 'national')
    {
        $dataForRegular = $this->cdMISLib->getResponseDeliveryForClient($clientId,$startDate,$endDate,$viewWise,$flag);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getResponseDeliveryForClient($clientId,$compare_startDate,$compare_endDate,$viewWise,$flag);
        }
        $dataForRegular = $this->getDataBasedOnView($dataForRegular,$viewWise,$startDate,$endDate);
        $responseDelivery = array();
        $responseDeliveryCount = 0;
        if(isset($dataForCompare))
        {
            $dataForCompare = $this->getDataBasedOnView($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $responseDeliveryCount += $this->getAnswerResultCount($dataForCompare);
            $responseDelivery['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompare));
        }
        $responseDeliveryCount += $this->getAnswerResultCount($dataForRegular);
        $responseDelivery['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegular));
        $responseDelivery['resultCount'] = $responseDeliveryCount;
        return $responseDelivery;
    }
    function getFirstTopTileForStudyAbroad()
    {
        $startDate            = empty($_GET['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->get('startDate');
        $endDate              = empty($_GET['endDate'])?date("Y-m-d"):$this->input->get('endDate');
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $universityId = $cdmismodel->getUniversityBasedOnCountry('',array(4));
        $i = 0;
        foreach ($universityId as $key => $value) {
            $universityIdArray[$i++] = $value['universityId'];
        }
        $clientId       = $this->cdMISLib->getInstituteClientIds($universityIdArray,'university');
        $leadResponse   = $this->cdMISLib->getLeadResponseForClients($clientId,$startDate,$endDate,'studyabroad');
        $totalSales     = $cdmismodel->getTotalSales($clientId,$startDate,$endDate);
        $totalCollections = $this->cdMISLib->getZoneWiseCollections($clientId,$startDate,$endDate);
        $topTilesData   = array(count($clientId),number_format($totalSales,2,'.',''),number_format($totalCollections,2,'.',''),$leadResponse['leadCount'],$leadResponse['responsesCount']);
        //$topTilesData = array(120,100,130,140,160);
        echo json_encode($topTilesData);
    }
    function getSecondTopTileForStudyAbroad()
    {
        $startDate            = empty($_GET['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->get('startDate');
        $endDate              = empty($_GET['endDate'])?date("Y-m-d"):$this->input->get('endDate');
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $universityId = $cdmismodel->getUniversityBasedOnCountry('',array(3));
        $i = 0;
        foreach ($universityId as $key => $value) {
            $universityIdArray[$i++] = $value['universityId'];
        }
        $clientId = $this->cdMISLib->getInstituteClientIds($universityIdArray,'university');
        $leadResponse   = $this->cdMISLib->getLeadResponseForClients($clientId,$startDate,$endDate,'studyabroad');
        $totalSales     = $cdmismodel->getTotalSales($clientId,$startDate,$endDate);
        $totalCollections = $this->cdMISLib->getZoneWiseCollections($clientId,$startDate,$endDate);
        $topTilesData   = array(count($clientId),number_format($totalSales,2,'.',''),number_format($totalCollections,2,'.',''),$leadResponse['leadCount'],$leadResponse['responsesCount']);
        //$topTilesData = array(120,100,130,140,160);
        echo json_encode($topTilesData);


    }
    function getThirdTopTileForStudyAbroad()
    {
        $startDate            = empty($_GET['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->get('startDate');
        $endDate              = empty($_GET['endDate'])?date("Y-m-d"):$this->input->get('endDate');
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $universityId = $cdmismodel->getUniversityBasedOnCountry('',array(5));
        $i = 0;
        foreach ($universityId as $key => $value) {
            $universityIdArray[$i++] = $value['universityId'];
        }
        $clientId = $this->cdMISLib->getInstituteClientIds($universityIdArray,'university');
        $leadResponse   = $this->cdMISLib->getLeadResponseForClients($clientId,$startDate,$endDate,'studyabroad');
        $totalSales     = $cdmismodel->getTotalSales($clientId,$startDate,$endDate);
        $totalCollections = $this->cdMISLib->getZoneWiseCollections($clientId,$startDate,$endDate);
        $topTilesData   = array(count($clientId),number_format($totalSales,2,'.',''),number_format($totalCollections,2,'.',''),$leadResponse['leadCount'],$leadResponse['responsesCount']);
        //$topTilesData = array(120,100,130,140,160);
        echo json_encode($topTilesData);


    }
    function getFourthTopTileForStudyAbroad()
    {
        $startDate            = empty($_GET['startDate'])?date("Y",strtotime("-1 year")).'-04-01':$this->input->get('startDate');
        $endDate              = empty($_GET['endDate'])?date("Y-m-d"):$this->input->get('endDate');
        $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
        $universityId = $cdmismodel->getUniversityBasedOnCountry('',array(8));
        $i = 0;
        foreach ($universityId as $key => $value) {
            $universityIdArray[$i++] = $value['universityId'];
        }
        $clientId = $this->cdMISLib->getInstituteClientIds($universityIdArray,'university');
        $leadResponse   = $this->cdMISLib->getLeadResponseForClients($clientId,$startDate,$endDate,'studyabroad');
        $totalSales     = $cdmismodel->getTotalSales($clientId,$startDate,$endDate);
        $totalCollections = $this->cdMISLib->getZoneWiseCollections($clientId,$startDate,$endDate);
        $topTilesData   = array(count($clientId),number_format($totalSales,2,'.',''),number_format($totalCollections,2,'.',''),$leadResponse['leadCount'],$leadResponse['responsesCount']);
        //$topTilesData = array(120,100,130,140,160);
        echo json_encode($topTilesData);


    }
    function getRegistrationDataSourceWise($courseId = array(),$source ='',$paidType='',$startDate ='',$endDate ='',$compare_startDate ='',$compare_endDate ='',$isStudyAbroad = 'no',$subCategoryId= array(),$cityId = array(),$countryId = array())
    {
        $dataForRegular = $this->cdMISLib->getRegistrationDataSourceWise($courseId,$source,$paidType,$startDate,$endDate,$isStudyAbroad,$subCategoryId,$cityId,$countryId);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getRegistrationDataSourceWise($courseId,$source,$paidType,$compare_startDate,$compare_endDate,$isStudyAbroad,$subCategoryId,$cityId,$countryId);
        }
        $sourceWiseReg = array();
        $sourceWiseReg['dataForRegular']= $this->prepareDataForCharts($dataForRegular);
        if(isset($dataForCompare))
        {
            $sourceWiseReg['dataForCompare']= $this->prepareDataForCharts($dataForCompare);
        }
        return $sourceWiseReg;
    }
    function getResponseDataSourceWise($courseId=array(),$source='',$paidType='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$subCategoryId=array(),$cityId=array(),$countryId=array(),$isStudyAbroad='no')
    {
        $dataForRegular = $this->cdMISLib->getResponseDataSourceWise($courseId,$source,$paidType,$startDate,$endDate,$subCategoryId,$cityId,$countryId,$isStudyAbroad);
        $sourceWiseResp = array();
        $sourceWiseResp['dataForRegular'] = $this->prepareDataForCharts($dataForRegular);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getResponseDataSourceWise($courseId,$source,$paidType,$compare_startDate,$compare_endDate,$subCategoryId,$cityId,$countryId,$isStudyAbroad);
            $sourceWiseResp['dataForCompare'] = $this->prepareDataForCharts($dataForCompare);
        }
        return $sourceWiseResp;
    }
    function getResponseDataPaidFreeWise($subCategoryId=array(),$cityId = array(),$countryId = array(),$source = '',$paidType = '',$startDate='',$endDate='',$compare_startDate = '',$compare_endDate = '',$isStudyAbroad='no')
    {
      $dataForRegular = $this->cdMISLib->getResponseDataPaidFreeWise($subCategoryId,$cityId,$countryId,$source,$paidType,$startDate,$endDate,$isStudyAbroad);
      $paidFreeWise = array();
      $paidFreeWise['dataForRegular'] = $this->prepareDataForCharts($dataForRegular);
      if( ! empty($compare_startDate) && ! empty($compare_endDate))
      {
        $dataForCompare = $this->cdMISLib->getResponseDataPaidFreeWise($subCategoryId,$cityId,$countryId,$source,$paidType,$compare_startDate,$compare_endDate,$isStudyAbroad);
        $paidFreeWise['dataForCompare'] = $this->prepareDataForCharts($dataForCompare);
      }
      return $paidFreeWise;
    }
    function getInstituteCourses($instituteIdArray = array())
    {
                $i = 0;
                $courseIdArray = array();
                $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
                foreach ($instituteIdArray as $key => $value) {
                    $result=$cdmismodel->getCoursesInInstitute($value);    
                    foreach ($result as $inst => $courses) {
                        $courseIdArray[$i++] = $courses['course_id'];
                        }
            }
            return $courseIdArray;
    }
    function getTrafficDataForPieCharts($instituteId = array(),$courseId = array(),$source='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$isStudyAbroad='no')
    {
        $trafficLib = $this->load->library('trackingMIS/trafficDataLib');
        $extraData['CD']['instituteId'] = $instituteId;
        $extraData['CD']['courseId'] = $courseId;
        if($isStudyAbroad == 'yes')
        {
            $extraData['CD']['isStudyAbroad'] =  'yes';
        }
        $extraData['CD']['deviceType'] =strtolower($source);
        $dateRangeArray = array('startDate'=>$startDate,'endDate'=>$endDate);
        $trafficData = array();
        $dataForRegular = $trafficLib->getTrafficDataForPieCharts($dateRangeArray,$extraData);
        $trafficData['Traffic-SourceWise']['dataForRegular'] = $this->prepareDataForCharts($dataForRegular);
        $trafficData['Traffic-DeviceWise']['dataForRegular'] = $this->preparePieChart($dataForRegular);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dateRangeArray['startDate'] = $compare_startDate;
            $dateRangeArray['endDate'] = $compare_endDate;
            $dataForCompare = $trafficLib->getTrafficDataForPieCharts($dateRangeArray,$extraData);
            $trafficData['Traffic-SourceWise']['dataForCompare'] = $this->prepareDataForCharts($dataForCompare);
            $trafficData['Traffic-DeviceWise']['dataForCompare'] = $this->preparePieChart($dataForCompare);
        }
        return $trafficData;
    }
    function getRegistrationDataDeviceWise($courseId = array(),$source='',$paidType='',$startDate ='',$endDate ='',$compare_startDate='',$compare_endDate='',$isStudyAbroad='no',$subCategoryId = array(),$cityId = array(),$countryId=array())
    {
        $dataForRegular = $this->cdMISLib->getRegistrationDataDeviceWise($courseId,$source,$paidType,$startDate,$endDate,$isStudyAbroad,$subCategoryId,$cityId,$countryId);
        $deviceWise = array();
        $deviceWise['dataForRegular'] = $this->preparePieChart($dataForRegular);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getRegistrationDataDeviceWise($courseId,$source,$paidType,$compare_startDate,$compare_endDate,$isStudyAbroad,$subCategoryId,$cityId,$countryId);
            $deviceWise['dataForCompare'] = $this->preparePieChart($dataForCompare);
        }
        return $deviceWise;
    }
    function getRegistrationDataPaidFreeWise($courseId = array(),$subCategoryId=array(),$cityId = array(),$countryId=array(),$source='',$paidType='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$isStudyAbroad='no')
    {
        $dataForRegular = $this->cdMISLib->getRegistrationDataPaidFreeWise($courseId,$subCategoryId,$cityId,$countryId,$source,$paidType,$startDate,$endDate,$isStudyAbroad);
        $paidFreeWise = array();
        $paidFreeWise['dataForRegular'] = $this->preparePieChart($dataForRegular);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getRegistrationDataPaidFreeWise($courseId,$subCategoryId,$cityId,$countryId,$source,$paidType,$compare_startDate,$compare_endDate,$isStudyAbroad);
            $paidFreeWise['dataForCompare'] = $this->preparePieChart($dataForCompare);
        }
        return $paidFreeWise;
    }
    function getQuestionsCountForTopTile($courseId = array(),$source = '',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$subCategoryId=array(),$cityId=array())
    {
        $dataForRegular = $this->cdMISLib->getQuestionsCountForTopTile($courseId,$source,$startDate,$endDate,$subCategoryId,$cityId);
        $questionResult = array(number_format($dataForRegular));
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getQuestionsCountForTopTile($courseId,$source,$compare_startDate,$compare_endDate,$subCategoryId,$cityId);
            array_push($questionResult, number_format($dataForCompare));
        }
        return $questionResult;
    }
    function getAnswersCountForTopTile($courseId = array(),$source = '',$startDate ='',$endDate ='',$compare_startDate ='',$compare_endDate='',$subCategoryId=array(),$cityId=array())
    {
        $dataForRegular = $this->cdMISLib->getAnswersCountForTopTile($courseId,$source,$startDate,$endDate,$subCategoryId,$cityId);
        $answerResult = array(number_format($dataForRegular));
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getAnswersCountForTopTile($courseId,$source,$compare_startDate,$compare_endDate,$subCategoryId,$cityId);
            array_push($answerResult,number_format($dataForCompare));
        }
        return $answerResult;
    }
    function getDigupCountForTopTile($courseId = array(),$source = '',$startDate ='',$endDate ='',$compare_startDate='',$compare_endDate ='',$subCategoryId=array(),$cityId=array())
    {
        $dataForRegular = $this->cdMISLib->getDigupCountForTopTile($courseId,$source,$startDate,$endDate,$subCategoryId,$cityId);
        $digupResult = array(number_format($dataForRegular));
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getDigupCountForTopTile($courseId,$source,$startDate,$endDate,$subCategoryId,$cityId);
            array_push($digupResult, number_format($dataForCompare));
        }
        return $digupResult;
    }
    function getRegistrationsDataForToptile($courseId = array(),$source ='',$paidType = '',$startDate ='',$endDate ='',$compare_startDate='',$compare_endDate='',$isStudyAbroad ='no',$subCategoryId = array(),$cityId = array(),$countryId= array())
    {
        $dataForRegular = $this->cdMISLib->getRegistrationsDataForToptile($courseId,$source,$paidType,$startDate,$endDate,$isStudyAbroad,$subCategoryId,$cityId,$countryId);
        $registrationResult = array(number_format($dataForRegular));
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getRegistrationsDataForToptile($courseId,$source,$paidType,$compare_startDate,$compare_endDate,$isStudyAbroad,$subCategoryId,$cityId,$countryId);
            array_push($registrationResult, number_format($dataForCompare));
        }
        return $registrationResult;
    }
    function getResponseCountForTopTile($courseId = array(),$source ='',$paidType ='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$subCategoryId = array(),$cityId = array(),$countryId = array(),$isStudyAbroad = 'no')
    {
        $dataForRegular = $this->cdMISLib->getResponseCountForTopTile($courseId,$source,$paidType,$startDate,$endDate,$subCategoryId,$cityId,$countryId,$isStudyAbroad);
        $responseResult = array(number_format($dataForRegular));
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getResponseCountForTopTile($courseId,$source,$paidType,$compare_startDate,$compare_endDate,$subCategoryId,$cityId,$countryId,$isStudyAbroad);
            array_push($responseResult, number_format($dataForCompare));
        }
        return $responseResult;
    }
    function getAvgResponseForPaidCourse($subCategoryId = array(),$cityId = array(),$countryId =array(),$source='',$paidType='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$isStudyAbroad='no')
    {
        $dataForRegular = $this->cdMISLib->getAvgResponseForPaidCourse($subCategoryId,$cityId,$countryId,$source,$paidType,$startDate,$endDate,$isStudyAbroad);
        $avgResponseForPaidCourse = array($dataForRegular);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getAvgResponseForPaidCourse($subCategoryId,$cityId,$countryId,$source,$paidType,$compare_startDate,$compare_endDate,$isStudyAbroad);
            array_push($avgResponseForPaidCourse, $dataForCompare);
        }
        return $avgResponseForPaidCourse;
    }
    function getLeadDeliveryCountForTopTile($clientId=array(),$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$flag='national')
    {
        $dataForRegular = $this->cdMISLib->getLeadDeliveryCountForTopTile($clientId,$startDate,$endDate,$flag);
        $leadDelivery = array(number_format($dataForRegular));
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getLeadDeliveryCountForTopTile($clientId,$compare_startDate,$compare_endDate,$flag);
            array_push($leadDelivery, number_format($dataForCompare));
        }
        return $leadDelivery;
    }
    function getResponseDeliveryCountForTopTile($clientId=array(),$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$flag='national')
    {
        $dataForRegular = $this->cdMISLib->getResponseDeliveryCountForTopTile($clientId,$startDate,$endDate,$flag);
        $responseDelivery = array(number_format($dataForRegular));
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dataForCompare = $this->cdMISLib->getResponseDeliveryCountForTopTile($clientId,$compare_startDate,$compare_endDate,$flag);
            array_push($responseDelivery, number_format($dataForCompare));
        }
        return $responseDelivery;
    }
    function getDomesticLinearDataByInstitute()
    {
        $instituteId         = isset($_POST['instituteId'])?$this->input->post('instituteId'):'';
        $courseId            = isset($_POST['courseId']) ? $this->input->post('courseId'):'';
        $source              = isset($_POST['source'])?$this->input->post('source'):'';
        $paidType            = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate           = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate             = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate   = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate     = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $similar_flag_check  = isset($_POST['similar_flag_check'])?$this->input->post('similar_flag_check'):0;
        $viewWise            = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        $graphType           = empty($_POST['graphType'])?'':$this->input->post('graphType');

        $courseIdArray       = explode(',', $courseId);
        $instituteIdArray    = explode(',', $instituteId);

        foreach ($courseIdArray as $key => $value) {
            if($value == 'All Courses')
            {
                $courseIdArray = array();
                $i = 0;
                $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
                foreach ($instituteIdArray as $key => $value) {
                    $result=$cdmismodel->getCoursesInInstitute($value);    
                    foreach ($result as $inst => $courses) {
                        $courseIdArray[$i++] = $courses['course_id'];
                        }
                 }
                break;
            }

        }

        
       if($similar_flag_check == 1) // means similar courses checkbox is checked
       {
            $retrieveSimilarCourses = $courseIdArray;
            foreach ($retrieveSimilarCourses as $key => $value) {
         
             $similarCourses = $this->getSimiliarInstitutesForCourses($value);

            foreach ($similarCourses['instituteIds'] as $key => $value) {
                array_push($instituteIdArray,$value);
                }
            foreach ($similarCourses['courseIds'] as $key => $value) {
                    array_push($courseIdArray,$value);
                 }
            }
         
        }
        switch ($graphType) {
            case 'Registrations':
                        $lineChartData['RegistrationData'] = $this->getRegistrationsData($instituteIdArray,$courseIdArray,$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Questions':
                        $lineChartData['QuestionResult'] = $this->getQuestionsData($instituteIdArray,$courseIdArray,$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Answers':
                        $lineChartData['AnswerResult'] = $this->getAnswersData($instituteIdArray,$courseIdArray,$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;         
            case 'Number of Answers Liked':
                        $lineChartData['DigupResult'] = $this->getDigUpData($instituteIdArray,$courseIdArray,$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;            
            case 'Responses':
                        $lineChartData['responseData'] = $this->getResponsesData($instituteIdArray,$courseIdArray,$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Bounce Rate':
                        $lineChartData['bounceRate'] = $this->getBounceRateForCustomerDelivery($instituteIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Avg Session Duraion':
                        $lineChartData['avgSessionDuration'] = $this->getAvgSessionDurationForCustomerDelivery($instituteIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Avg Page Per Session':
                        $lineChartData['avgPagePerSession'] = $this->getAvgPagePerSessionForCustomerDelivery($instituteIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Exit Rate':
                        $lineChartData['exitRateData'] = $this->getExitRateDataForCustomerDelivery($instituteIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Unique Users':
                        $lineChartData['uniqueUser'] = $this->getUniqueUsersDataForCustomerDelivery($instituteIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Page Views':
                        $lineChartData['pageviewData'] = $this->getPageviewDataForCustomerDelivery($instituteIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Leads Delivered':
                        $clientIdArray = $this->getClientIdForInstitute_Univeristy($instituteIdArray);
                        $lineChartData['leadDelivery'] = $this->getLeadDeliveryForClient($clientIdArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);        
                        break;
            case 'Matched Responses Delivered':
                        $clientIdArray = $this->getClientIdForInstitute_Univeristy($instituteIdArray);
                        $lineChartData['responseDelivery'] = $this->getResponseDeliveryForClient($clientIdArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;

            default:
                # code...
                break;
        }
        echo json_encode($lineChartData);

    }
    function getDomesticLinearChartDataBySubcat()
    {
        $subCategoryId     = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $source            = isset($_POST['source'])?$this->input->post('source'):'';
        $paidType          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate         = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate           = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate   = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise          = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        $graphType         = empty($_POST['graphType'])?'':$this->input->post('graphType');

        $cityId            = isset($_POST['cityId'])?$this->input->post('cityId'):'';
        $stateId           = isset($_POST['stateId'])?$this->input->post('stateId'):'';
        switch ($graphType) {
            case 'Registrations':
                        if( ! empty($stateId))
                            $cityId = $this->getCityUnderStates($cityId,$stateId);
                        $lineChartData['RegistrationData'] = $this->getRegistrationsData('','',$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'no',$subCategoryId,$cityId);
                        break;
            case 'Questions':
                        if( ! empty($stateId))
                            $cityId = $this->getCityUnderStates($cityId,$stateId);
                        $lineChartData['QuestionResult'] = $this->getQuestionsData('','',$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$subCategoryId,$cityId);
                        break;
            case 'Answers':
                        if( ! empty($stateId))
                            $cityId = $this->getCityUnderStates($cityId,$stateId);
                        $lineChartData['AnswerResult'] = $this->getAnswersData('','',$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$subCategoryId,$cityId);
                        break;         
            case 'Number of Answers Liked':
                        if( ! empty($stateId))
                            $cityId = $this->getCityUnderStates($cityId,$stateId);
                        $lineChartData['DigupResult'] = $this->getDigUpData('','',$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$subCategoryId,$cityId);
                        break;            
            case 'Responses':
                        if( ! empty($stateId))
                            $cityId = $this->getCityUnderStates($cityId,$stateId);
                        $lineChartData['responseData'] = $this->getResponsesData('','',$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$subCategoryId,$cityId);
                        break;
            case 'Bounce Rate':
                        $instituteIdArray  = $this->cdMISLib->getInstitutesInIndia($subCategoryId,$cityId,$stateId);
                        $courseIdArray     = $this->cdMISLib->getCoursesInIndia($subCategoryId,$cityId,$stateId);
                        $lineChartData['bounceRate'] = $this->getBounceRateForCustomerDelivery($instituteIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Avg Session Duraion':
                        $instituteIdArray  = $this->cdMISLib->getInstitutesInIndia($subCategoryId,$cityId,$stateId);
                        $courseIdArray     = $this->cdMISLib->getCoursesInIndia($subCategoryId,$cityId,$stateId);
                        $lineChartData['avgSessionDuration'] = $this->getAvgSessionDurationForCustomerDelivery($instituteIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Avg Page Per Session':
                        $instituteIdArray  = $this->cdMISLib->getInstitutesInIndia($subCategoryId,$cityId,$stateId);
                        $courseIdArray     = $this->cdMISLib->getCoursesInIndia($subCategoryId,$cityId,$stateId);
                        $lineChartData['avgPagePerSession'] = $this->getAvgPagePerSessionForCustomerDelivery($instituteIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Exit Rate':
                        $instituteIdArray  = $this->cdMISLib->getInstitutesInIndia($subCategoryId,$cityId,$stateId);
                        $courseIdArray     = $this->cdMISLib->getCoursesInIndia($subCategoryId,$cityId,$stateId);
                        $lineChartData['exitRateData'] = $this->getExitRateDataForCustomerDelivery($instituteIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Unique Users':
                        $instituteIdArray  = $this->cdMISLib->getInstitutesInIndia($subCategoryId,$cityId,$stateId);
                        $courseIdArray     = $this->cdMISLib->getCoursesInIndia($subCategoryId,$cityId,$stateId);
                        $lineChartData['uniqueUser'] = $this->getUniqueUsersDataForCustomerDelivery($instituteIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Page Views':
                        $instituteIdArray  = $this->cdMISLib->getInstitutesInIndia($subCategoryId,$cityId,$stateId);
                        $courseIdArray     = $this->cdMISLib->getCoursesInIndia($subCategoryId,$cityId,$stateId);
                        $lineChartData['pageviewData'] = $this->getPageviewDataForCustomerDelivery($instituteIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Leads Delivered':
                        $instituteIdArray  = $this->cdMISLib->getInstitutesInIndia($subCategoryId,$cityId,$stateId);
                        $clientIdArray = $this->getClientIdForInstitute_Univeristy($instituteIdArray);
                        $lineChartData['leadDelivery'] = $this->getLeadDeliveryForClient($clientIdArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);        
                        break;
            case 'Matched Responses Delivered':
                        $instituteIdArray  = $this->cdMISLib->getInstitutesInIndia($subCategoryId,$cityId,$stateId);            
                        $clientIdArray = $this->getClientIdForInstitute_Univeristy($instituteIdArray);
                        $lineChartData['responseDelivery'] = $this->getResponseDeliveryForClient($clientIdArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;

            default:
                # code...
                break;
        }            
        echo json_encode($lineChartData);
    }
    function getCityUnderStates($cityId= array(),$stateId = array())
    {
          $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
            $cityResult = $cdmismodel->getCityBasedOnZone($stateId);
            foreach ($cityResult as $key => $value) {
                array_push($cityId, $value['cityId']);
            }
    }
    function getStudyAbroadLinearChartDataByUniversity()
    {
        $universityId       = isset($_POST['universityId'])?$this->input->post('universityId'):'';
        $courseId           = isset($_POST['courseId']) ? $this->input->post('courseId'):'';
        $source             = isset($_POST['source'])?$this->input->post('source'):'';
        $paidType           = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate          = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate            = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate  = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate    = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise           = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        $graphType          = empty($_POST['graphType'])?'':$this->input->post('graphType');

        $courseIdArray      = explode(',', $courseId);
        $universityIdArray  = explode(',', $universityId);
        foreach ($courseIdArray as $key => $value) {
            if($value == 'All Courses')
            {
                $i = 0;
                $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
                foreach ($universityIdArray as $key => $value) {
                    $result=$cdmismodel->getCoursesInUniversity($value);    
                    foreach ($result as $university => $courses) {
                        $courseIdArray[$i++] = $courses['courseId'];
                        }
                 }
                break;
            }
            else
                break;

        }
        //
        switch ($graphType) {
            case 'Registrations':
                        $lineChartData['RegistrationData'] = $this->getRegistrationsData($universityIdArray,$courseIdArray,$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes');                
                            break;
            case 'Responses' :
                        $lineChartData['responseData'] = $this->getResponsesData($universityIdArray,$courseIdArray,$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise);
                        break;
            case 'Bounce Rate':
                        $lineChartData['bounceRate'] = $this->getBounceRateForCustomerDelivery($universityIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes');
                        break;
            case 'Avg Session Duraion':
                        $lineChartData['avgSessionDuration'] = $this->getAvgSessionDurationForCustomerDelivery($universityIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes');
                        break;
            case 'Avg Page Per Session':
                        $lineChartData['avgPagePerSession'] = $this->getAvgPagePerSessionForCustomerDelivery($universityIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes');
                        break;
            case 'Exit Rate':
                        $lineChartData['exitRateData'] = $this->getExitRateDataForCustomerDelivery($universityIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes');
                        break;
            case 'Unique Users':
                        $lineChartData['uniqueUser'] = $this->getUniqueUsersDataForCustomerDelivery($universityIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes');
                        break;            
            case 'Page Views':
                        $lineChartData['pageviewData'] = $this->getPageviewDataForCustomerDelivery($universityIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes');
                        break;    
            case 'Leads Delivered':
                        $clientIdArray = $this->getClientIdForInstitute_Univeristy($universityIdArray,'university');
                        $lineChartData['leadDelivery'] = $this->getLeadDeliveryForClient($clientIdArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'studyabroad');        
                        break;
            case 'Matched Responses Delivered':
                        $clientIdArray = $this->getClientIdForInstitute_Univeristy($universityIdArray,'university');
                        $lineChartData['responseDelivery'] = $this->getResponseDeliveryForClient($clientIdArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'studyabroad');
                        break;
            default:
                # code...
                break;
        }
        echo json_encode($lineChartData);
        
    }
    function getStudyAbroadLinearChartDataBySubcat()
    {
        $subCategoryId      = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $source             = isset($_POST['source'])?$this->input->post('source'):'';
        $paidType           = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $startDate          = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $endDate            = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $compare_startDate  = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $compare_endDate    = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $viewWise           = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        $countryId          = isset($_POST['countryId'])?$this->input->post('countryId'):'';
        $graphType          = empty($_POST['graphType'])?'':$this->input->post('graphType');

        switch ($graphType) {
            case 'Registrations':
                        $lineChartData['RegistrationData'] = $this->getRegistrationsData('','',$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes',$subCategoryId,'',$countryId);
                            break;
            case 'Responses' :
                        $lineChartData['responseData'] = $this->getResponsesData('','',$source,$paidType,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,$subCategoryId,'',$countryId,'yes');
                        break;
            case 'Bounce Rate':
                        $universityIdArray = $this->cdMISLib->getUniversityBasedOnCountry($subCategoryId,$countryId);
                        $courseIdArray = $this->cdMISLib->getCoursesInStudyAbroad($subCategoryId,$countryId);
                        $lineChartData['bounceRate'] = $this->getBounceRateForCustomerDelivery($universityIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes');
                        break;
            case 'Avg Session Duraion':
                        $universityIdArray = $this->cdMISLib->getUniversityBasedOnCountry($subCategoryId,$countryId);
                        $courseIdArray = $this->cdMISLib->getCoursesInStudyAbroad($subCategoryId,$countryId);
                        $lineChartData['avgSessionDuration'] = $this->getAvgSessionDurationForCustomerDelivery($universityIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes');
                        break;
            case 'Avg Page Per Session':
                        $universityIdArray = $this->cdMISLib->getUniversityBasedOnCountry($subCategoryId,$countryId);
                        $courseIdArray = $this->cdMISLib->getCoursesInStudyAbroad($subCategoryId,$countryId);
                        $lineChartData['avgPagePerSession'] = $this->getAvgPagePerSessionForCustomerDelivery($universityIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes');
                        break;
            case 'Exit Rate':
                        $universityIdArray = $this->cdMISLib->getUniversityBasedOnCountry($subCategoryId,$countryId);
                        $courseIdArray = $this->cdMISLib->getCoursesInStudyAbroad($subCategoryId,$countryId);
                        $lineChartData['exitRateData'] = $this->getExitRateDataForCustomerDelivery($universityIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes');
                        break;
            case 'Unique Users':
                        $universityIdArray = $this->cdMISLib->getUniversityBasedOnCountry($subCategoryId,$countryId);
                        $courseIdArray = $this->cdMISLib->getCoursesInStudyAbroad($subCategoryId,$countryId);
                        $lineChartData['uniqueUser'] = $this->getUniqueUsersDataForCustomerDelivery($universityIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes');
                        break;            
            case 'Page Views':
                        $universityIdArray = $this->cdMISLib->getUniversityBasedOnCountry($subCategoryId,$countryId);
                        $courseIdArray = $this->cdMISLib->getCoursesInStudyAbroad($subCategoryId,$countryId);
                        $lineChartData['pageviewData'] = $this->getPageviewDataForCustomerDelivery($universityIdArray,$courseIdArray,$source,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'yes');
                        break;    
            case 'Leads Delivered':
                        $universityIdArray = $this->cdMISLib->getUniversityBasedOnCountry($subCategoryId,$countryId);
                        $clientIdArray = $this->getClientIdForInstitute_Univeristy($universityIdArray,'university');
                        $lineChartData['leadDelivery'] = $this->getLeadDeliveryForClient($clientIdArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'studyabroad');        
                        break;
            case 'Matched Responses Delivered':
                        $universityIdArray = $this->cdMISLib->getUniversityBasedOnCountry($subCategoryId,$countryId);
                        $clientIdArray = $this->getClientIdForInstitute_Univeristy($universityIdArray,'university');
                        $lineChartData['responseDelivery'] = $this->getResponseDeliveryForClient($clientIdArray,$startDate,$endDate,$compare_startDate,$compare_endDate,$viewWise,'studyabroad');
                        break;
            default:
                # code...
                break;
        }     
        echo json_encode($lineChartData);

    }

    function getBounceRateForCustomerDelivery($instituteId = array(),$courseId = array(),$source = '',$startDate ='',$endDate ='',$compare_startDate='',$compare_endDate='',$viewWise=1,$isStudyAbroad='no')
    {
        $extraData['CD']['instituteId'] = $instituteId;
        $extraData['CD']['courseId'] = $courseId;
        if($isStudyAbroad == 'yes')
        {
            $extraData['CD']['isStudyAbroad'] = 'yes';
        }
        $extraData['CD']['deviceType'] =strtolower($source);
        switch ($viewWise) {
                case '1':
                        $graphFormat = 'day';
                        break;
                case '2':
                        $graphFormat = 'week';
                        break;
                case '3':
                        $graphFormat = 'month';
                        break;
                default:
                        $graphFormat = 'day';
                        break;
        }
        $extraData['CD']['viewWise'] = $graphFormat;
        $dateRangeArray = array('startDate'=>$startDate,'endDate'=>$endDate);

        $engagementLib = $this->load->library('trackingMIS/engagementLib');
        $bounceData = array();
        $dataForRegular = $engagementLib->getBounceRateForCustomerDelivery($dateRangeArray,$extraData);
        $dataForRegularLineChart = $this->getDataBasedOnViewForElasticSearch($dataForRegular,$viewWise,$startDate,$endDate);
        $bounceData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegularLineChart));
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dateRangeArray['startDate'] = $compare_startDate;
            $dateRangeArray['endDate'] = $compare_endDate;
            $dataForCompare = $engagementLib->getBounceRateForCustomerDelivery($dateRangeArray,$extraData);
            $dataForCompareLineChart = $this->getDataBasedOnViewForElasticSearch($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $bounceData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompareLineChart));
        }
        return $bounceData;

    }
    function getAvgSessionDurationForCustomerDelivery($instituteId= array(),$courseId = array(),$source = '',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1,$isStudyAbroad='no')
    {
        $extraData['CD']['instituteId'] = $instituteId;
        $extraData['CD']['courseId'] = $courseId;
        if($isStudyAbroad == 'yes')
        {
            $extraData['CD']['isStudyAbroad'] = 'yes';
        }
        $extraData['CD']['deviceType'] =strtolower($source);
        switch ($viewWise) {
                case '1':
                        $graphFormat = 'day';
                        break;
                case '2':
                        $graphFormat = 'week';
                        break;
                case '3':
                        $graphFormat = 'month';
                        break;
                default:
                        $graphFormat = 'day';
                        break;
        }
        $extraData['CD']['viewWise'] = $graphFormat;
        $dateRangeArray = array('startDate'=>$startDate,'endDate'=>$endDate);
        $engagementLib = $this->load->library('trackingMIS/engagementLib');
        $avgSessionDuration = array();
        $dataForRegular = $engagementLib->getAvgSessionDurationForCustomerDelivery($dateRangeArray,$extraData);
        $dataForRegularLineChart = $this->getDataBasedOnViewForElasticSearch($dataForRegular,$viewWise,$startDate,$endDate);
        $avgSessionDuration['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegularLineChart));
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dateRangeArray['startDate'] = $compare_startDate;
            $dateRangeArray['endDate'] = $compare_endDate;
            $dataForCompare = $engagementLib->getAvgSessionDurationForCustomerDelivery($dateRangeArray,$extraData);
            $dataForCompareLineChart = $this->getDataBasedOnViewForElasticSearch($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $avgSessionDuration['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompareLineChart));
        }
        return $avgSessionDuration;
    }
    function getAvgPagePerSessionForCustomerDelivery($instituteId = array(),$courseId = array(),$source ='',$startDate='',$endDate ='',$compare_startDate='',$compare_endDate='',$viewWise=1,$isStudyAbroad='no')
    {
        $extraData['CD']['instituteId'] = $instituteId;
        $extraData['CD']['courseId'] = $courseId;
        if($isStudyAbroad == 'yes')
        {
            $extraData['CD']['isStudyAbroad'] = 'yes';
        }
        $extraData['CD']['deviceType'] =strtolower($source);
        switch ($viewWise) {
                case '1':
                        $graphFormat = 'day';
                        break;
                case '2':
                        $graphFormat = 'week';
                        break;
                case '3':
                        $graphFormat = 'month';
                        break;
                default:
                        $graphFormat = 'day';
                        break;
        }
        $extraData['CD']['viewWise'] = $graphFormat;
        $dateRangeArray = array('startDate'=>$startDate,'endDate'=>$endDate);
        $engagementLib = $this->load->library('trackingMIS/engagementLib');
        $avgPagePerSession = array();
        $dataForRegular = $engagementLib->getAvgPagePerSessionForCustomerDelivery($dateRangeArray,$extraData);
        $dataForRegularLineChart = $this->getDataBasedOnViewForElasticSearch($dataForRegular,$viewWise,$startDate,$endDate);
        $avgPagePerSession['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegularLineChart));
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dateRangeArray['startDate'] = $compare_startDate;
            $dateRangeArray['endDate'] = $compare_endDate;
            $dataForCompare = $engagementLib->getAvgPagePerSessionForCustomerDelivery($dateRangeArray,$extraData);
            $dataForCompareLineChart = $this->getDataBasedOnViewForElasticSearch($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $avgPagePerSession['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompareLineChart));
        }
        return $avgPagePerSession;

    }
    function getExitRateDataForCustomerDelivery($instituteId = array(),$courseId = array(),$source ='',$startDate='',$endDate ='',$compare_startDate='',$compare_endDate='',$viewWise=1,$isStudyAbroad='no')
    {
        $extraData['CD']['instituteId'] = $instituteId;
        $extraData['CD']['courseId'] = $courseId;
        if($isStudyAbroad == 'yes')
        {
            $extraData['CD']['isStudyAbroad'] = 'yes';
        }
        $extraData['CD']['deviceType'] =strtolower($source);
        switch ($viewWise) {
                case '1':
                        $graphFormat = 'day';
                        break;
                case '2':
                        $graphFormat = 'week';
                        break;
                case '3':
                        $graphFormat = 'month';
                        break;
                default:
                        $graphFormat = 'day';
                        break;
        }
        $extraData['CD']['viewWise'] = $graphFormat;
        $dateRangeArray = array('startDate'=>$startDate,'endDate'=>$endDate);
        $engagementLib = $this->load->library('trackingMIS/engagementLib');
        $exitRate = array();
        $dataForRegular = $engagementLib->getExitRateDataForCustomerDelivery($dateRangeArray,$extraData);
        $dataForRegularLineChart = $this->getDataBasedOnViewForElasticSearch($dataForRegular,$viewWise,$startDate,$endDate);
        $exitRate['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegularLineChart));

        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dateRangeArray['startDate'] = $compare_startDate;
            $dateRangeArray['endDate'] = $compare_endDate;
            $dataForCompare = $engagementLib->getExitRateDataForCustomerDelivery($dateRangeArray,$extraData);
            $dataForCompareLineChart = $this->getDataBasedOnViewForElasticSearch($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $exitRate['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompareLineChart));
        }
        return $exitRate;
    }
    function getUniqueUsersDataForCustomerDelivery($instituteId = array(),$courseId = array(),$source = '',$startDate='',$endDate='',$compare_startDate='',$compare_endDate,$viewWise=1,$isStudyAbroad='no')
    {
        $extraData['CD']['instituteId'] = $instituteId;
        $extraData['CD']['courseId'] = $courseId;
        if($isStudyAbroad == 'yes')
        {
            $extraData['CD']['isStudyAbroad'] = 'yes';
        }
        $extraData['CD']['deviceType'] =strtolower($source);
        switch ($viewWise) {
                case '1':
                        $graphFormat = 'day';
                        break;
                case '2':
                        $graphFormat = 'week';
                        break;
                case '3':
                        $graphFormat = 'month';
                        break;
                default:
                        $graphFormat = 'day';
                        break;
        }
        $extraData['CD']['viewWise'] = $graphFormat;
        $dateRangeArray = array('startDate'=>$startDate,'endDate'=>$endDate);
        $engagementLib = $this->load->library('trackingMIS/engagementLib');   
        $userArray = array();
        $dataForRegular = $engagementLib->getUniqueUsersDataForCustomerDelivery($dateRangeArray,$extraData);
        $dataForRegularLineChart = $this->getDataBasedOnViewForElasticSearch($dataForRegular,$viewWise,$startDate,$endDate);
        $userArray['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegularLineChart));

        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dateRangeArray['startDate'] = $compare_startDate;
            $dateRangeArray['endDate'] = $compare_endDate;
            $dataForCompare = $engagementLib->getUniqueUsersDataForCustomerDelivery($dateRangeArray,$extraData);
            $dataForCompareLineChart = $this->getDataBasedOnViewForElasticSearch($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $userArray['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompareLineChart));
        }
        return $userArray;

    }
    function getPageviewDataForCustomerDelivery($instituteId = array(),$courseId = array(),$source ='',$startDate='',$endDate='',$compare_startDate='',$compare_endDate='',$viewWise=1,$isStudyAbroad='no')
    {
        $extraData['CD']['instituteId'] = $instituteId;
        $extraData['CD']['courseId'] = $courseId;
        if($isStudyAbroad == 'yes')
        {
            $extraData['CD']['isStudyAbroad'] = 'yes';
        }
        $extraData['CD']['deviceType'] =strtolower($source);
        switch ($viewWise) {
                case '1':
                        $graphFormat = 'day';
                        break;
                case '2':
                        $graphFormat = 'week';
                        break;
                case '3':
                        $graphFormat = 'month';
                        break;
                default:
                        $graphFormat = 'day';
                        break;
        }
        $extraData['CD']['viewWise'] = $graphFormat;
        $dateRangeArray = array('startDate'=>$startDate,'endDate'=>$endDate);
        $engagementLib = $this->load->library('trackingMIS/engagementLib');   
        $pageviewData = array();
        $dataForRegular = $engagementLib->getPageviewDataForCustomerDelivery($dateRangeArray,$extraData);
        $dataForRegularLineChart = $this->getDataBasedOnViewForElasticSearch($dataForRegular,$viewWise,$startDate,$endDate);
        $pageviewData['dataForRegular'] = json_encode($this->prepareDataForLineChart1($dataForRegularLineChart));

        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $dateRangeArray['startDate'] = $compare_startDate;
            $dateRangeArray['endDate'] = $compare_endDate;
            $dataForCompare = $engagementLib->getPageviewDataForCustomerDelivery($dateRangeArray,$extraData);
            $dataForCompareLineChart = $this->getDataBasedOnViewForElasticSearch($dataForCompare,$viewWise,$compare_startDate,$compare_endDate);
            $pageviewData['dataForCompare'] = json_encode($this->prepareDataForLineChart1($dataForCompareLineChart));
        }
        return $pageviewData;

    }
    function getDomesticRegistrationCountBySubcat()
    {
        $fetchArray  = $this->getDomesticPostDataBySubcat('','','cityFetch');
        $registrationResult = $this->getRegistrationsDataForToptile('',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'no',$fetchArray['subCategoryId'],$fetchArray['cityId']);
        $topTilesResult['regTopTile'] = $registrationResult;
        echo json_encode($topTilesResult);
        
    }
    function getDomesticQuestionCountBySubcat()
    {
        $fetchArray  = $this->getDomesticPostDataBySubcat('','','cityFetch');
        $questionResult = $this->getQuestionsCountForTopTile('',$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$fetchArray['subCategoryId'],$fetchArray['cityId']);
        $topTilesResult['questionTopTile'] = $questionResult;
        echo json_encode($topTilesResult);
    }
    function getDomesticAnswerCountBySubcat()
    {
        $fetchArray  = $this->getDomesticPostDataBySubcat('','','cityFetch');
        $answerResult = $this->getAnswersCountForTopTile('',$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$fetchArray['subCategoryId'],$fetchArray['cityId']);
        $topTilesResult['answerTopTile'] = $answerResult;
        echo json_encode($topTilesResult);
    }
    function getDomesticDigupCountBySubcat()
    {
        $fetchArray  = $this->getDomesticPostDataBySubcat('','','cityFetch');
        $digupResult = $this->getDigupCountForTopTile('',$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$fetchArray['subCategoryId'],$fetchArray['cityId']);
        $topTilesResult['digupTopTile'] = $digupResult;
        echo json_encode($topTilesResult);
    }
    function getDomesticResponseCountBySubcat()
    {
        $fetchArray  = $this->getDomesticPostDataBySubcat('','','cityFetch');
        $responseResult = $this->getResponseCountForTopTile('',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$fetchArray['subCategoryId'],$fetchArray['cityId']);
        $topTilesResult['responseTopTile'] = $responseResult;
        echo json_encode($topTilesResult);
    }
    function getDomesticAvgResposneForPaidCourseBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('','','cityFetch');
        $avgResponseForPaidCourse = $this->getAvgResponseForPaidCourse($fetchArray['subCategoryId'],$fetchArray['cityId'],$fetchArray['stateId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $topTilesResult['avgResponse'] = $avgResponseForPaidCourse;
        echo json_encode($topTilesResult);
    }
    function getDomesticLeadCountBySubcat()
    {
        $fetchArray  = $this->getDomesticPostDataBySubcat('instituteFetch','');
        $clientIdArray    = $this->getClientIdForInstitute_Univeristy($fetchArray['instituteId']);
        $leadDelivery     = $this->getLeadDeliveryCountForTopTile($clientIdArray,$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $topTilesResult['leadTopTile'] = $leadDelivery;
        echo json_encode($topTilesResult);

    }
    function getDomesticMRcountBySubcat()
    {
        $fetchArray  = $this->getDomesticPostDataBySubcat('instituteFetch','');
        $clientIdArray    = $this->getClientIdForInstitute_Univeristy($fetchArray['instituteId']);
        $responseDelivery = $this->getResponseDeliveryCountForTopTile($clientIdArray,$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $topTilesResult['mrTopTile'] = $responseDelivery;
        echo json_encode($topTilesResult);
    }
    //
    function getDomesticUniqueUsersCountBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('instituteFetch','courseFetch');
        $dataToFetch = array('uniqueUsers');
        $topTileRegular = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'no',$fetchArray['source']);
        $dashboardResult['uniqTopTile'] = array($topTileRegular['uniqueUsers']);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $topTileCompare = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'no',$fetchArray['source']);
             array_push($dashboardResult['uniqTopTile'], $topTileCompare['uniqueUsers']);
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticBounceRateBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('instituteFetch','courseFetch');
        $dataToFetch = array('bounceRate');
        $topTileRegular = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'no',$fetchArray['source']);
        $dashboardResult['bounceTopTile'] = array($topTileRegular['bounceRate']);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $topTileCompare = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'no',$fetchArray['source']);
            array_push($dashboardResult['bounceTopTile'], $topTileCompare['bounceRate']);
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticExitRateBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('instituteFetch','courseFetch');
        $dataToFetch = array('pageView','exitRate');
        $topTileRegular = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'no',$fetchArray['source']);
        $dashboardResult['pageviewTopTile'] = array($topTileRegular['pageView']);
        $dashboardResult['exitTopTile'] = array($topTileRegular['exitRate']);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $topTileCompare = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'no',$fetchArray['source']);
            array_push($dashboardResult['pageviewTopTile'], $topTileCompare['pageView']);
            array_push($dashboardResult['exitTopTile'], $topTileCompare['exitRate']);
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticAvgPagePerSessionBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('instituteFetch','courseFetch');
        $dataToFetch = array('avgPagePerSession');
        $topTileRegular = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'no',$fetchArray['source']);
        $dashboardResult['appsTopTile'] = array($topTileRegular['avgPagePerSession']);
        
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $topTileCompare = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'no',$fetchArray['source']);
        
            array_push($dashboardResult['appsTopTile'], $topTileCompare['avgPagePerSession']);
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticAvgSessionDurationBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('instituteFetch','courseFetch');
        $dataToFetch = array('avgSessionDuration');
        $topTileRegular = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'no',$fetchArray['source']);
        $dashboardResult['asdTopTile'] = array($topTileRegular['avgSessionDuration']);
        if( ! empty($compare_startDate) && ! empty($compare_endDate))
        {
            $topTileCompare = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'no',$fetchArray['source']);
            array_push($dashboardResult['asdTopTile'], $topTileCompare['avgSessionDuration']);
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticRegistrationSourceWiseBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('','','cityFetch');
        $registration_sourceWise = $this->getRegistrationDataSourceWise('',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'no',$fetchArray['subCategoryId'],$fetchArray['cityId']);
        $dashboardResult['registration_sourceWise'] = $registration_sourceWise;
        echo json_encode($dashboardResult);
        
    }
    function getDomesticRegistrationDeviceWiseBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('','','cityFetch');
        $reg_deviceWise = $this->getRegistrationDataDeviceWise('',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'no',$fetchArray['subCategoryId'],$fetchArray['cityId']);
        $dashboardResult['registration_deviceWise'] = $reg_deviceWise;
        echo json_encode($dashboardResult);
    }
    function getDomesticRegistrationPaidFreeWiseBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('','','cityFetch');
        $reg_paidFreeWise = $this->getRegistrationDataPaidFreeWise('',$fetchArray['subCategoryId'],$fetchArray['cityId'],'',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $dashboardResult['registration_paidFree']  = $reg_paidFreeWise;
        echo json_encode($dashboardResult);
    }
    function getDomesticTrafficChartBySubcat()
    {   
        $fetchArray = $this->getDomesticPostDataBySubcat('','courseFetch');
        $trafficData = $this->getTrafficDataForPieCharts($fetchArray['instituteId'],$fetchArray['courseId'],$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $dashboardResult['Traffic-SourceWise'] = $trafficData['Traffic-SourceWise'];
        $dashboardResult['Traffic-DeviceWise'] = $trafficData['Traffic-DeviceWise'];
        echo json_encode($dashboardResult);
    }
    function getDomesticResponseSourceWiseBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('','','cityFetch');
        $response_sourceWise = $this->getResponseDataSourceWise('',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$fetchArray['subCategoryId'],$fetchArray['cityId']);
        $dashboardResult['response_sourceWise'] = $response_sourceWise;
        echo json_encode($dashboardResult);
    }
    function getDomesticResponsePaidFreeBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('','','cityFetch');
        $responsePaidFreeWise = $this->getResponseDataPaidFreeWise($fetchArray['subCategoryId'],$fetchArray['cityId'],'',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $dashboardResult['responsePaidFreeWise']  = $responsePaidFreeWise;
        echo json_encode($dashboardResult);
    }
    function getDomesticTableDataBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('instituteFetch','courseFetch','cityFetch');
        $dashboardResult = array();
        if( empty($fetchArray['compare_startDate']) && empty($fetchArray['compare_endDate']))
        {
            //$dashboardResult['dataTable'] = json_encode($this->getRespondentsData('',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['subCategoryId'],$fetchArray['cityId']));
            if($fetchArray['courseWiseResponse'] == 1)
              $dashboardResult['courseTable'] = json_encode($this->cdMISLib->getResponsesCourseWise($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],'no',$fetchArray['subCategoryId'],$fetchArray['cityId']));
            $dashboardResult['instituteTable'] = json_encode($this->cdMISLib->getResponseDeliveryInstituteWise($fetchArray['instituteId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],'no',$fetchArray['subCategoryId'],$fetchArray['cityId']));
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticLeadTableBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('instituteFetch','');
        $dashboardResult = array();
        if(  empty($fetchArray['compare_startDate']) &&  empty($fetchArray['compare_endDate']))
        {
            $clientIdArray  = $this->getClientIdForInstitute_Univeristy($fetchArray['instituteId']);
            $dashboardResult['leadDataTable'] = $this->cdMISLib->getLeadDeliveryInstituteWise($clientIdArray,$fetchArray['startDate'],$fetchArray['endDate'],'institute');    
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticMapDataBySubcat()
    {
        $fetchArray = $this->getDomesticPostDataBySubcat('instituteFetch','courseFetch');
        $geosplit               = $this->getGeoSplitData($fetchArray['instituteId'],$fetchArray['courseId'],'','','',$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate']);
        echo $geosplit;
    }
    //
    function getDomesticPostDataBySubcat($instituteIdFlag = '',$courseIdFlag = '',$cityFlag = '')
    {
        $data = array();
        $data['subCategoryId']     = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $data['source']            = isset($_POST['source'])?$this->input->post('source'):'';
        $data['paidType']          = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $data['startDate']         = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $data['endDate']           = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $data['compare_startDate'] = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $data['compare_endDate']   = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $data['viewWise']          = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        $data['courseWiseResponse']  = isset($_POST['courseWiseResponse'])?$this->input->post('courseWiseResponse'):0;
        $cityId            = isset($_POST['cityId'])?$this->input->post('cityId'):array();
        $data['stateId']           = isset($_POST['stateId'])?$this->input->post('stateId'):'';
        if( ! empty($instituteIdFlag))
        {
            $data['instituteId']  = $this->cdMISLib->getInstitutesInIndia($data['subCategoryId'],$cityId,$data['stateId']);
        }
        if( ! empty($courseIdFlag))
        {
            $data['courseId']     = $this->cdMISLib->getCoursesInIndia($data['subCategoryId'],$cityId,$data['stateId']);    
        }
        if( ! empty($cityFlag) && ! empty($data['stateId']))
        {
            $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
            $cityResult = $cdmismodel->getCityBasedOnZone($data['stateId']);
            foreach ($cityResult as $key => $value) {
                array_push($cityId, $value['cityId']);
            }
        }
        $data['cityId'] = $cityId;
        return $data;
    }
    function getDomesticPostDataByInstitute()
    {
        $data = array();
        $instituteId                 = isset($_POST['instituteId'])?$this->input->post('instituteId'):'';
        $courseId                    = isset($_POST['courseId']) ? $this->input->post('courseId'):'';
        $data['source']              = isset($_POST['source'])?$this->input->post('source'):'';
        $data['paidType']            = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $data['startDate']           = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $data['endDate']             = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $data['compare_startDate']   = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $data['compare_endDate']     = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $similar_flag_check          = isset($_POST['similar_flag_check'])?$this->input->post('similar_flag_check'):0;
        $data['viewWise']            = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        $data['courseWiseResponse']  = isset($_POST['courseWiseResponse'])?$this->input->post('courseWiseResponse'):0;

        $courseIdArray       = explode(',', $courseId);
        $instituteIdArray    = explode(',', $instituteId);

        foreach ($courseIdArray as $key => $value) {
            if($value == 'All Courses')
            {
                $i = 0;
                $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
                foreach ($instituteIdArray as $key => $value) {
                    $result=$cdmismodel->getCoursesInInstitute($value);    
                    foreach ($result as $inst => $courses) {
                        $courseIdArray[$i++] = $courses['course_id'];
                        }
                 }
                break;
            }

        }

        
       if($similar_flag_check == 1) // means similar courses checkbox is checked
       {
            $retrieveSimilarCourses = $courseIdArray;
            foreach ($retrieveSimilarCourses as $key => $value) {
         
             $similarCourses = $this->getSimiliarInstitutesForCourses($value);

            foreach ($similarCourses['instituteIds'] as $key => $value) {
                array_push($instituteIdArray,$value);
                }
            foreach ($similarCourses['courseIds'] as $key => $value) {
                    array_push($courseIdArray,$value);
                 }
            }
         
        }
        $data['instituteId']  = $instituteIdArray;
        $data['courseId']     = $courseIdArray;
        return $data;
    }
    function getDomesticRegistrationCountByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $registrationResult = $this->getRegistrationsDataForToptile($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $topTilesResult['regTopTile'] = $registrationResult;
        echo json_encode($topTilesResult);
        
    }
    function getDomesticQuestionCountByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $questionResult = $this->getQuestionsCountForTopTile($fetchArray['courseId'],$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);

        $topTilesResult['questionTopTile'] = $questionResult;
        echo json_encode($topTilesResult);
    }
    function getDomesticAnswerCountByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $answerResult = $this->getAnswersCountForTopTile($fetchArray['courseId'],$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        
        $topTilesResult['answerTopTile'] = $answerResult;
        echo json_encode($topTilesResult);
    }
    function getDomesticDigupCountByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $digupResult = $this->getDigupCountForTopTile($fetchArray['courseId'],$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
    
        $topTilesResult['digupTopTile'] = $digupResult;
        echo json_encode($topTilesResult);
    }
    function getDomesticResponseCountByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $responseResult = $this->getResponseCountForTopTile($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $topTilesResult['responseTopTile'] = $responseResult;
        echo json_encode($topTilesResult);

    }
    function getDomesticLeadCountByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $clientIdArray          = $this->getClientIdForInstitute_Univeristy($fetchArray['instituteId']);
        $leadDelivery     = $this->getLeadDeliveryCountForTopTile($clientIdArray,$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $topTilesResult['leadTopTile'] = $leadDelivery;
        echo json_encode($topTilesResult);
    }
    function getDomesticMRcountByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $clientIdArray          = $this->getClientIdForInstitute_Univeristy($fetchArray['instituteId']);
        $responseDelivery = $this->getResponseDeliveryCountForTopTile($clientIdArray,$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $topTilesResult['mrTopTile'] = $responseDelivery;
        echo json_encode($topTilesResult);
    }
    function getDomesticUniqueUsersCountByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $dataToFetch = array('uniqueUsers');
        $topTileRegular = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'no',$fetchArray['source']);
        $dashboardResult['uniqTopTile'] = array($topTileRegular['uniqueUsers']);
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'no',$fetchArray['source']);
             array_push($dashboardResult['uniqTopTile'], $topTileCompare['uniqueUsers']);
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticBounceRateByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $dataToFetch = array('bounceRate');
        $topTileRegular = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'no',$fetchArray['source']);
        $dashboardResult['bounceTopTile'] = array($topTileRegular['bounceRate']);
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'no',$fetchArray['source']);
            array_push($dashboardResult['bounceTopTile'], $topTileCompare['bounceRate']);
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticExitRateByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $dataToFetch = array('pageView','exitRate');
        $topTileRegular = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'no',$fetchArray['source']);
        $dashboardResult['pageviewTopTile'] = array($topTileRegular['pageView']);
        $dashboardResult['exitTopTile'] = array($topTileRegular['exitRate']);
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'no',$fetchArray['source']);
            array_push($dashboardResult['pageviewTopTile'], $topTileCompare['pageView']);
            array_push($dashboardResult['exitTopTile'], $topTileCompare['exitRate']);
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticAvgPagePerSessionByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $dataToFetch = array('avgPagePerSession');
        $topTileRegular = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'no',$fetchArray['source']);
        $dashboardResult['appsTopTile'] = array($topTileRegular['avgPagePerSession']);
        
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'no',$fetchArray['source']);
        
            array_push($dashboardResult['appsTopTile'], $topTileCompare['avgPagePerSession']);
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticAvgSessionDurationByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $dataToFetch = array('avgSessionDuration');
        $topTileRegular = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'no',$fetchArray['source']);
        $hoursFormat = explode('.', $topTileRegular['avgSessionDuration']);


        $dashboardResult['asdTopTile'] = array($topTileRegular['avgSessionDuration']);
        //$dashboardResult['asdTopTile'] = array(date('H:i:s', mktime(0, 0, $topTileRegular['avgSessionDuration'])).'.'.$hoursFormat[1]);

        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['instituteId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'no',$fetchArray['source']);
            array_push($dashboardResult['asdTopTile'], $topTileCompare['avgSessionDuration']);
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticRegistrationSourceWiseByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $registration_sourceWise = $this->getRegistrationDataSourceWise($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $dashboardResult['registration_sourceWise'] = $registration_sourceWise;
        echo json_encode($dashboardResult);
        
    }
    function getDomesticRegistrationDeviceWiseByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();      
        $reg_deviceWise = $this->getRegistrationDataDeviceWise($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $dashboardResult['registration_deviceWise'] = $reg_deviceWise;
        echo json_encode($dashboardResult);
    }
    function getDomesticRegistrationPaidFreeWiseByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $reg_paidFreeWise = $this->getRegistrationDataPaidFreeWise($fetchArray['courseId'],'','','',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $dashboardResult['registration_paidFree']  = $reg_paidFreeWise;
        echo json_encode($dashboardResult);
    }
    function getDomesticTrafficChartByInstitute()
    {   
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $trafficData = $this->getTrafficDataForPieCharts($fetchArray['instituteId'],$fetchArray['courseId'],$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $dashboardResult['Traffic-SourceWise'] = $trafficData['Traffic-SourceWise'];
        $dashboardResult['Traffic-DeviceWise'] = $trafficData['Traffic-DeviceWise'];
        echo json_encode($dashboardResult);
    }
    function getDomesticResponseSourceWiseByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $response_sourceWise = $this->getResponseDataSourceWise($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $dashboardResult['response_sourceWise'] = $response_sourceWise;
        echo json_encode($dashboardResult);
    }
    function getDomesticTableDataByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        if( empty($fetchArray['compare_startDate']) && empty($fetchArray['compare_endDate']))
        {
            $dashboardResult['dataTable'] = json_encode($this->getRespondentsData($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate']));
            if($fetchArray['courseWiseResponse'] == 1)
              $dashboardResult['courseTable'] = json_encode($this->cdMISLib->getResponsesCourseWise($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate']));
            $dashboardResult['instituteTable'] = json_encode($this->cdMISLib->getResponseDeliveryInstituteWise($fetchArray['instituteId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate']));
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticLeadTableByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();
        $dashboardResult = array();
        if(  empty($fetchArray['compare_startDate']) &&  empty($fetchArray['compare_endDate']))
        {
            $clientIdArray  = $this->getClientIdForInstitute_Univeristy($fetchArray['instituteId']);
            $dashboardResult['leadDataTable'] = $this->cdMISLib->getLeadDeliveryInstituteWise($clientIdArray,$fetchArray['startDate'],$fetchArray['endDate'],'institute');    
        }
        echo json_encode($dashboardResult);
    }
    function getDomesticMapDataByInstitute()
    {
        $fetchArray = $this->getDomesticPostDataByInstitute();      
        $geosplit   = $this->getGeoSplitData($fetchArray['instituteId'],$fetchArray['courseId'],'','','',$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate']);

        echo $geosplit;
    }
    function getStudyAbroadRegistrationCountByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $registrationResult = $this->getRegistrationsDataForToptile($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'yes');
        $topTilesResult['regTopTile'] = $registrationResult;
        echo json_encode($topTilesResult);
    }
    function getStudyAbroadResponseCountByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $responseResult = $this->getResponseCountForTopTile($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $topTilesResult['responseTopTile'] = $responseResult;
        echo json_encode($topTilesResult);
    }
    function getStudyAbroadLeadCountByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $clientIdArray    = $this->getClientIdForInstitute_Univeristy($fetchArray['universityId'],'university');
        $leadDelivery     = $this->getLeadDeliveryCountForTopTile($clientIdArray,$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'studyabroad');      
        $topTilesResult['leadTopTile'] = $leadDelivery;     
        echo json_encode($topTilesResult);
    }
    function getStudyAbroadMRcountByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $clientIdArray    = $this->getClientIdForInstitute_Univeristy($fetchArray['universityId'],'university');
        $responseDelivery = $this->getResponseDeliveryCountForTopTile($clientIdArray,$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'studyabroad');
        $topTilesResult['mrTopTile'] = $responseDelivery;
        echo json_encode($topTilesResult);   
    }
    function getStudyAbroadUniqUserCountByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $dataToFetch = array('uniqueUsers');
        $topTileRegular = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'yes',$fetchArray['source']);
        $dashboardResult['uniqTopTile'] = array($topTileRegular['uniqueUsers']);
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'yes',$fetchArray['source']);
            array_push($dashboardResult['uniqTopTile'], $topTileCompare['uniqueUsers']);
        }
        echo json_encode($dashboardResult);        
    }
    function getStudyAbroadBounceRateByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $dataToFetch = array('bounceRate');
        $topTileRegular = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'yes',$fetchArray['source']);
        $dashboardResult['bounceTopTile'] = array($topTileRegular['bounceRate']);
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'yes',$fetchArray['source']);
            array_push($dashboardResult['bounceTopTile'], $topTileCompare['bounceRate']);
        }
        echo json_encode($dashboardResult);        
    }
    function getStudyAbroadExitRateByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $dataToFetch = array('pageView','exitRate');
        $topTileRegular = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'yes',$fetchArray['source']);
        $dashboardResult['exitTopTile'] = array($topTileRegular['exitRate']);
        $dashboardResult['pageviewTopTile'] = array($topTileRegular['pageView']);
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'yes',$fetchArray['source']);
            array_push($dashboardResult['exitTopTile'], $topTileCompare['exitRate']);
            array_push($dashboardResult['pageviewTopTile'], $topTileCompare['pageView']);
        }
        echo json_encode($dashboardResult);        
    }
    function getStudyAbroadAvgPagePerSessionByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $dataToFetch = array('avgPagePerSession');
        $topTileRegular = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'yes',$fetchArray['source']);
        
        $dashboardResult['appsTopTile'] = array($topTileRegular['avgPagePerSession']);
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'yes',$fetchArray['source']);
        
            array_push($dashboardResult['appsTopTile'], $topTileCompare['avgPagePerSession']);
        }
        echo json_encode($dashboardResult);        
    }
    function getStudyAbroadAvgSessionDurationByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $dataToFetch = array('avgSessionDuration');
        $topTileRegular = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'yes',$fetchArray['source']);
        $dashboardResult['asdTopTile'] = array($topTileRegular['avgSessionDuration']);
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'yes',$fetchArray['source']);
            array_push($dashboardResult['asdTopTile'], $topTileCompare['avgSessionDuration']);
        }
        echo json_encode($dashboardResult);        
    }
    function getStudyAbroadRegistrationSourceWiseByUnversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $registration_sourceWise = $this->getRegistrationDataSourceWise($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'yes');
        $dashboardResult['registration_sourceWise'] = $registration_sourceWise;
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadRegistrationDeviceWiseByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();     
        $reg_deviceWise = $this->getRegistrationDataDeviceWise($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'yes');
        
        $dashboardResult['registration_deviceWise'] = $reg_deviceWise;
        echo json_encode($dashboardResult);   
    }
    function getStudyAbroadRegistrationPaidFreeWiseByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $reg_paidFreeWise = $this->getRegistrationDataPaidFreeWise($fetchArray['courseId'],'','','',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'yes');
        $dashboardResult['registration_paidFree']  = $reg_paidFreeWise;
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadTrafficSplitByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $trafficData = $this->getTrafficDataForPieCharts($fetchArray['universityId'],$fetchArray['courseId'],$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'yes');

        $dashboardResult['Traffic-SourceWise'] = $trafficData['Traffic-SourceWise'];
        $dashboardResult['Traffic-DeviceWise'] = $trafficData['Traffic-DeviceWise'];
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadResponseSourceWiseByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $response_sourceWise = $this->getResponseDataSourceWise($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate']);
        $dashboardResult['response_sourceWise'] = $response_sourceWise;
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadTableDataByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        if( empty($fetchArray['compare_startDate']) && empty($fetchArray['compare_endDate']))
        {
            $dashboardResult['dataTable'] = json_encode($this->getRespondentsData($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate']));
            if($fetchArray['courseWiseResponse'] == 1)
                $dashboardResult['courseTable'] = json_encode($this->cdMISLib->getResponsesCourseWise($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],'yes'));
            $dashboardResult['instituteTable'] = json_encode($this->cdMISLib->getResponseDeliveryInstituteWise($fetchArray['universityId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],'yes'));
        }
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadLeadTableByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $dashboardResult = array();
        if(  empty($fetchArray['compare_startDate']) &&  empty($fetchArray['compare_endDate']))
        {
            $clientIdArray  = $this->getClientIdForInstitute_Univeristy($fetchArray['universityId'],'university');
            $dashboardResult['leadDataTable'] = $this->cdMISLib->getLeadDeliveryInstituteWise($clientIdArray,$fetchArray['startDate'],$fetchArray['endDate'],'university');
        }
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadMapDataByUniversity()
    {
        $fetchArray = $this->getStudyAbroadPostDataByUniversity();
        $geosplit           = $this->getGeoSplitData($fetchArray['universityId'],$fetchArray['courseId'],'','','',$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate'],'','yes');
        echo $geosplit;        
    }
    function getStudyAbroadPostDataByUniversity()
    {
        $data = array();
        $universityId       = isset($_POST['universityId'])?$this->input->post('universityId'):'';
        $courseId           = isset($_POST['courseId']) ? $this->input->post('courseId'):'';
        $data['source']             = isset($_POST['source'])?$this->input->post('source'):'';
        $data['paidType']           = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $data['startDate']          = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $data['endDate']            = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $data['compare_startDate']  = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $data['compare_endDate']    = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        //$similar_flag_check = isset($_POST['similar_flag_check'])?$this->input->post('similar_flag_check'):0;
        $data['viewWise']           = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        $data['courseWiseResponse']  = isset($_POST['courseWiseResponse'])?$this->input->post('courseWiseResponse'):0;
        $courseIdArray      = explode(',', $courseId);
        $universityIdArray  = explode(',', $universityId);
        foreach ($courseIdArray as $key => $value) {
            if($value == 'All Courses')
            {
                $i = 0;
                $cdmismodel = $this->load->model('trackingMIS/cdmismodel');
                foreach ($universityIdArray as $key => $value) {
                    $result=$cdmismodel->getCoursesInUniversity($value);    
                    foreach ($result as $university => $courses) {
                        $courseIdArray[$i++] = $courses['courseId'];
                        }
                 }
                break;
            }
            else
                break;

        }
        $data['courseId'] = $courseIdArray;
        $data['universityId'] = $universityIdArray;
        return $data;
    }
    function getStudyAbroadRegistrationCountBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat();
        $registrationResult = $this->getRegistrationsDataForToptile('',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'yes',$fetchArray['subCategoryId'],'',$fetchArray['countryId']);
        $topTilesResult['regTopTile'] = $registrationResult;
        echo json_encode($topTilesResult);
        
    }
    function getStudyAbroadResponseCountBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat();
        $responseResult = $this->getResponseCountForTopTile('',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$fetchArray['subCategoryId'],'',$fetchArray['countryId'],'yes');
        $topTilesResult['responseTopTile'] = $responseResult;
        echo json_encode($topTilesResult);

    }
    function getStudyAbroadResponsePaidFreeBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat();
        $responsePaidFreeWise = $this->getResponseDataPaidFreeWise($fetchArray['subCategoryId'],'',$fetchArray['countryId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'yes');
        $dashboardResult['responsePaidFreeWise']  = $responsePaidFreeWise;
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadAvgResposneForPaidCourseBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat();
        $avgResponseForPaidCourse = $this->getAvgResponseForPaidCourse($fetchArray['subCategoryId'],'',$fetchArray['countryId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'yes');
        $topTilesResult['avgResponse'] = $avgResponseForPaidCourse;
        echo json_encode($topTilesResult);
    }
    function getStudyAbroadLeadCountBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat('universityFetch','');
        $clientIdArray    = $this->getClientIdForInstitute_Univeristy($fetchArray['universityId'],'university');
        $leadDelivery     = $this->getLeadDeliveryCountForTopTile($clientIdArray,$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'studyabroad');

        $topTilesResult['leadTopTile'] = $leadDelivery;
        echo json_encode($topTilesResult);
    }
    function getStudyAbroadMRcountBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat('universityFetch','');
        $clientIdArray    = $this->getClientIdForInstitute_Univeristy($fetchArray['universityId'],'university');
        $responseDelivery = $this->getResponseDeliveryCountForTopTile($clientIdArray,$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'studyabroad');
        $topTilesResult['mrTopTile'] = $responseDelivery;
        echo json_encode($topTilesResult);   
    }
    function getStudyAbroadUniqUserBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat('universityFetch','courseFetch');
        $dataToFetch = array('uniqueUsers');
        $topTileRegular = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'yes',$fetchArray['source']);
        $dashboardResult['uniqTopTile'] = array($topTileRegular['uniqueUsers']);
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'yes',$fetchArray['source']);
            array_push($dashboardResult['uniqTopTile'], $topTileCompare['uniqueUsers']);
        }
        echo json_encode($dashboardResult);        
    }
    function getStudyAbroadBounceRateBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat('universityFetch','courseFetch');
        $dataToFetch = array('bounceRate');
        $topTileRegular = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'yes',$fetchArray['source']);
        $dashboardResult['bounceTopTile'] = array($topTileRegular['bounceRate']);
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'yes',$fetchArray['source']);
            array_push($dashboardResult['bounceTopTile'], $topTileCompare['bounceRate']);
        }
        echo json_encode($dashboardResult);        
    }
    function getStudyAbroadExitRateBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat('universityFetch','courseFetch');
        $dataToFetch = array('pageView','exitRate');
        $topTileRegular = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'yes',$fetchArray['source']);
        $dashboardResult['exitTopTile'] = array($topTileRegular['exitRate']);
        $dashboardResult['pageviewTopTile'] = array($topTileRegular['pageView']);
        
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'yes',$fetchArray['source']);
            array_push($dashboardResult['exitTopTile'], $topTileCompare['exitRate']);
            array_push($dashboardResult['pageviewTopTile'], $topTileCompare['pageView']);
        
        }
        echo json_encode($dashboardResult);        
    }
    function getStudyAbroadAvgPagePerSessionBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat('universityFetch','courseFetch');
        $dataToFetch = array('avgPagePerSession');
        $topTileRegular = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'yes',$fetchArray['source']);
        $dashboardResult['appsTopTile'] = array($topTileRegular['avgPagePerSession']);
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'yes',$fetchArray['source']);
        
            array_push($dashboardResult['appsTopTile'], $topTileCompare['avgPagePerSession']);
        }
        echo json_encode($dashboardResult);        
    }
    function getStudyAbroadAvgSessionDurationBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat('universityFetch','courseFetch');
        $dataToFetch = array('avgSessionDuration');
        $topTileRegular = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['startDate'],$fetchArray['endDate'],$dataToFetch,'yes',$fetchArray['source']);
        $dashboardResult['asdTopTile'] = array($topTileRegular['avgSessionDuration']);
        if( ! empty($fetchArray['compare_startDate']) && ! empty($fetchArray['compare_endDate']))
        {
            $topTileCompare = $this->topTilesData($fetchArray['universityId'],$fetchArray['courseId'],'','',$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$dataToFetch,'yes',$fetchArray['source']);
            array_push($dashboardResult['asdTopTile'], $topTileCompare['avgSessionDuration']);
        }
        echo json_encode($dashboardResult);        
    }
    function getStudyAbroadRegistrationDeviceWiseBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat();
        $reg_deviceWise = $this->getRegistrationDataDeviceWise('',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'yes',$fetchArray['subCategoryId'],'',$fetchArray['countryId']);
        $dashboardResult['registration_deviceWise'] = $reg_deviceWise;
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadRegistrationSourceWiseBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat();
        $registration_sourceWise = $this->getRegistrationDataSourceWise('',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'yes',$fetchArray['subCategoryId'],'',$fetchArray['countryId']);
        $dashboardResult['registration_sourceWise'] = $registration_sourceWise;
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadRegistrationPaidFreeWiseBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat();
        $reg_paidFreeWise = $this->getRegistrationDataPaidFreeWise('',$fetchArray['subCategoryId'],'',$fetchArray['countryId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'yes');
        $dashboardResult['registration_paidFree']  = $reg_paidFreeWise;
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadTrafficSplitBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat('universityFetch','courseFetch');
        $trafficData = $this->getTrafficDataForPieCharts($fetchArray['universityId'],$fetchArray['courseId'],$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],'yes');
        
        $dashboardResult['Traffic-SourceWise'] = $trafficData['Traffic-SourceWise'];
        $dashboardResult['Traffic-DeviceWise'] = $trafficData['Traffic-DeviceWise'];
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadResponseSourceWiseBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat();
        $response_sourceWise = $this->getResponseDataSourceWise('',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['compare_startDate'],$fetchArray['compare_endDate'],$fetchArray['subCategoryId'],'',$fetchArray['countryId'],'yes');
        $dashboardResult['response_sourceWise'] = $response_sourceWise;
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadTableDataBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat('universityFetch','courseFetch');
        if( empty($fetchArray['compare_startDate']) && empty($fetchArray['compare_endDate']))
        {
            //$dashboardResult['dataTable'] = json_encode($this->getRespondentsData('',$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],$fetchArray['subCategoryId'],'',$fetchArray['countryId'],'yes'));
            if($fetchArray['courseWiseResponse'] == 1)
                $dashboardResult['courseTable'] = json_encode($this->cdMISLib->getResponsesCourseWise($fetchArray['courseId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],'yes',$fetchArray['subCategoryId'],'',$fetchArray['countryId']));
            $dashboardResult['instituteTable'] = json_encode($this->cdMISLib->getResponseDeliveryInstituteWise($fetchArray['universityId'],$fetchArray['source'],$fetchArray['paidType'],$fetchArray['startDate'],$fetchArray['endDate'],'yes',$fetchArray['subCategoryId'],'',$fetchArray['countryId']));
        }
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadLeadTableBySubcat()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat('universityFetch','');
        $dashboardResult = array();
        if(  empty($fetchArray['compare_startDate']) &&  empty($fetchArray['compare_endDate']))
        {
            $clientIdArray  = $this->getClientIdForInstitute_Univeristy($fetchArray['universityId'],'university');
            $dashboardResult['leadDataTable'] = $this->cdMISLib->getLeadDeliveryInstituteWise($clientIdArray,$fetchArray['startDate'],$fetchArray['endDate'],'university');    
        }
        echo json_encode($dashboardResult);
    }
    function getStudyAbroadDataBySubcat_Map()
    {
        $fetchArray = $this->getStudyAbroadPostDataBySubcat('universityFetch','courseFetch');
        $geosplit             = $this->getGeoSplitData($fetchArray['universityId'],$fetchArray['courseId'],'','','',$fetchArray['source'],$fetchArray['startDate'],$fetchArray['endDate'],'','yes');
        echo $geosplit;
    }
    function getStudyAbroadPostDataBySubcat($universityFlag = '',$courseFlag = '')
    {
        $data = array();
        $data['subCategoryId']      = isset($_POST['subCategoryId'])?$this->input->post('subCategoryId'):'';
        $data['source']             = isset($_POST['source'])?$this->input->post('source'):'';
        $data['paidType']           = isset($_POST['paidType'])?$this->input->post('paidType'):'';
        $data['startDate']          = empty($_POST['startDate'])?date("Y-m-d", strtotime(' -30 day')):$this->input->post('startDate');
        $data['endDate']            = empty($_POST['endDate'])?date("Y-m-d"):$this->input->post('endDate');
        $data['compare_startDate']  = empty($_POST['compare_startDate'])?'':$this->input->post('compare_startDate');
        $data['compare_endDate']    = empty($_POST['compare_endDate'])?'':$this->input->post('compare_endDate');
        $data['viewWise']           = isset($_POST['viewWise'])?$this->input->post('viewWise'):1;
        $data['countryId']          = isset($_POST['countryId'])?$this->input->post('countryId'):'';
        $data['courseWiseResponse'] = isset($_POST['courseWiseResponse'])?$this->input->post('courseWiseResponse'):0;

        if( ! empty($universityFlag))
        {
            $data['universityId'] = $this->cdMISLib->getUniversityBasedOnCountry($data['subCategoryId'],$data['countryId']);    
        }
        
        if( ! empty($courseFlag))
        {
            $data['courseId'] = $this->cdMISLib->getCoursesInStudyAbroad($data['subCategoryId'],$data['countryId']);    
        }
        return $data;       
    }

}
	
?>
