<?php
/**
 * Controller for Study abroad MIS.
*/

class saMIS extends MX_Controller
{
	private $trackingLib;

	public function __construct()
	{
		parent::__construct();
        $data = array();
        ini_set("memory_limit", "256M");
        $this->MISCommonLib = $this->load->library('trackingMIS/MISCommonLib');
		$this->trackingLib = $this->load->library('trackingMIS/trackingMISCommonLib');
		$this->saTrackingLib = $this->load->library('trackingMIS/saTrackingLib');
		$this->trafficDataLib = $this->load->library('trackingMIS/trafficDataLib');
		$this->abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
 		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$this->locationRepository = $locationBuilder->getLocationRepository();
        $this->colorCodes = array("#FDDB6D",
            "#80DAEB",
            "#FF8243",
            "#BAB86C",
            "#17806D",
            "#C8385A",
            "#71BC78",
            "#7366BD",
            "#FC2847",
            "#0000FF",
            "#A52A2A",
            "#DEB887",
            "#5F9EA0",
            "#7FFF00",
            "#D2691E",
            "#FF7F50",
            "#6495ED",
            "#DC143C",
            "#00FFFF",
            "#00008B",
            "#008B8B",
            "#B8860B",
            "#A9A9A9",
            "#006400",
            "#BDB76B",
            "#8B008B",
            "#556B2F",
            "#FF8C00",
            "#414A4C",
            "#FFA089",
            "#95918C",
            "#FDD9B5",
            "#B2EC5D",
            "#E6A8D7",
            "#F780A1",
            "#9F8170",
            "#FF9BAA",
            "#FD7C6E",
            "#FF1DCE",
            "#FDFC74",
            "#77DDE7",
            "#FAE7B5",
            "#A5694F",
            "#3BB08F",
            "#1F75FE",
            "#199EBD",
            "#EA7E5D",
            "#926EAE",
            "#FF7F49",
            "#CDA4DE",
            "#FFBD88",
            "#ADADD6",
            "#FF43A4",
            "#1CD3A2",
            "#76FF7A",
            "#FF48D0",
            "#FDD7E4",
            "#F0E891",
            "#FF7538",
            "#158078",
            "#C364C5",
            "#FF496C",
            "#9D81BA",
            "#EFDBC5",
            "#DEAA88",
            "#BC5D58",
            "#CD4A4A",
            "#FAA76C",
            "#FFA343",
            "#DBD7D2",
            "#B0B7C6",
            "#C5D0E6",
            "#CB4154",
            "#F664AF",
            "#FFBCD9",
            "#45CEA2",
            "#FC89AC",
            "#1DACD6",
            "#EF98AA",
            "#ECEABE",
            "#1CA9C9",
            "#DD9475",
            "#CD9575",
            "#E3256B",
            "#FFA474",
            "#8F509D",
            "#FC74FD",
            "#FDFC74",
            "#9FE2BF",
            "#2B6CC4",
            "#FF1DCE",
            "#A8E4A0",
            "#1974D2",
            "#E7C697",
            "#87A96B",
            "#CC6666",
            "#F75394",
            "#30BA8F",
            "#FB7EFD",
            "#6DAE81",
            "#1DF914",
            "#EE204D",
            "#FCD975",
            "#8E4585",
            "#7442C8",
            "#D68A59",
            "#979AAA",
            "#FFFF99",
            "#FDBCB4",
            "#7851A9",
            "#FFB653",
            "#5D76CB",
            "#DE5D83",
            "#1FCECB",
            "#C5E384",
            "#9ACEEB",
            "#FCB4D5",
            "#CDC5C2",
            "#8A795D",
            "#FC6C85",
            "#FD5E53",
            "#6E5160",
            "#FF5349",
            "#DD4492",
            "#C0448F",
            "#FFAACC",
            "#1A4876",
            "#CA3767",
            "#B4674D",
            "#FCE883",
            "#EDEDED",
            "#A2ADD0",
            "#FFCFAB",
            "#EFCDB8",
            "#FF6E4A",
            "#1CAC78");
        $data['userDataArray'] = reset($this->trackingLib->checkValidUser());
	}

	private function _loadDependecies($metric,$pageName='') {
	    $data['userDataArray'] = reset($this->trackingLib->checkValidUser());  
        if($metric == 'OVERVIEW'){
            $data['misSource'] = "global";
            $data['colorCodes'] = $this->colorCodes;
        }else{
            $data['misSource'] = "studyAbroad";    
        }
        
		$data['teamName'] = "Study Abroad";
		$this->load->config('saTrackingMISConfig');		
		$data['leftMenuArray'] = $this->config->item("leftMenuArray");
		$metricArray = $this->config->item('METRIC');
        $filterArray = $this->config->item('FILTER');
        if($metric == 'OVERVIEW'){
            $data['pageDetails'] = $metricArray['OVERVIEW'];
        }else{
            if($pageName){
                $data['pageDetails'] = $metricArray[$metric]['PAGEWISE'];
                $data['topFilter'] = $filterArray[$metric][$pageName];
            }else{
                $data['pageDetails'] = $metricArray[$metric]['OVERALL'];
                $data['topFilter'] = $filterArray[$metric]['Study Abroad'];
            }    
        }
		return $data;
	}

	function dashboard()
	{	
		$data = $this->_loadDependecies();
		$data['teamName'] = 'Study Abroad';
 		$this->load->view('trackingMIS/misTemplate', $data);
 	}

    function getFilterData(& $data, $metric)
    {
        foreach ($data['topFilter'] as $key => $value) {
                switch ($value) {
                    case 'category':
                        $data['desiredCourses'] = $this->abroadCommonLib->getAbroadMainLDBCourses();    // popular ldb courses
                        $data['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();      // // popular categories
                        break;
                    case 'country':
                        $this->_populateAbroadCountries($data);     // All Abroad Countries
                        break;

                    case 'courseLevel':
                        $data['courseType'] = $this->abroadCommonLib->getAbroadCourseLevels();  //Course Levels
                        break;

                    case 'consultants':
                        $trackingModel = $this->load->model('trackingMIS/samismodel');
                        $data['consultants'] = $trackingModel->getConsultant();
                        $data['consultants'] = $this->_processConsultantData($data['consultants']);
                        break;

                    case 'consultantLocationRegions':
                        $data['consultantLocationRegions']   = $trackingModel->getConsultantLocationRegions();
                        usort($data['consultantLocationRegions'],function($c1,$c2){
                            return (strcasecmp($c1['regionName'],$c2['regionName']));
                        });
                        break;
                }
        }       
    }

    private function _processConsultantData($data){
        usort($data,function($c1,$c2){
            return (strcasecmp($c1['name'],$c2['name']));
        });
        foreach ($data as $key => $value) {
            if($value['status'] == 'deleted'){
                $data[$key]['name'] = $value['name'].' (Deleted)';
            }
        }

        return $data;
    }

 	function metric($functionName,$pageName='',$responseType='')
 	{
        //_p($functionName);_p($pageName);_p($responseType);die;
        $metric = $this->getMetric($functionName);
        $data = $this->_loadDependecies($metric,$pageName);
        $this->getFilterData($data,$metric);
        //_p($data);die;

 		switch($functionName)
 		{
            case 'engagements':
                    if($pageName){
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getEngagementDataForSelectedDuration/".$pageName;
                        $data['pageName'] =$pageName;
                    }else{
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getEngagementDataForSelectedDuration";
                        $data['pageName'] ='Study Abroad';
                    }
                    $data['responseType'] = 'ENGAGEMENT';
                    break;

            case 'traffic':
                    if($pageName){
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getTrafficDataForSelectedDuration/".$pageName;
                        $data['pageName'] =$pageName;
                    }else{
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getTrafficDataForSelectedDuration";
                        $data['pageName'] ='Study Abroad';
                    }
                    $data['responseType'] = 'Traffic';
                    break;

            case 'registrations':
                    if($pageName){
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getRegistrationsDataForSelectedDuration/".$pageName;
                        $data['pageName'] =$pageName;
                    }else{
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getRegistrationsDataForSelectedDuration";
                        $data['pageName'] ='Study Abroad';
                    }
                    $data['responseType'] = 'Registrations';
                    break;

            /*case 'RMC':
                    if($pageName){
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getRMCDataForSelectedDuration/".$pageName."/".$responseType;
                        $data['pageName'] =$pageName;
                    }else{
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getRMCDataForSelectedDuration/";
                        $data['pageName'] ='Study Abroad';
                    }
                    $data['responseType'] = 'rmc';
                    break;*/

            /*case 'responses':
                    if($pageName){
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getResponsesDataForSelectedDuration/".$pageName."/".$responseType;
                        $data['pageName'] =$pageName;
                    }else{
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getResponsesDataForSelectedDuration";
                        $data['pageName'] ='Study Abroad';
                    }
                    $data['responseType'] = $responseType;
                    break;*/

            case 'commentReply':
                    if($pageName){
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getCommentReplyDataForSelectedDuration/".$pageName;
                        $data['pageName'] =$pageName;
                    }else{
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getCommentReplyDataForSelectedDuration";
                        $data['pageName'] ='Study Abroad';
                    }
                    $data['responseType'] = 'commentReply';
                    break;

            case 'downloads':
                    if($pageName){
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getGuideDataForSelectedDuration/".$pageName;
                        $data['pageName'] =$pageName;
                    }else{
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getGuideDataForSelectedDuration";
                        $data['pageName'] ='Study Abroad';
                    }
                    $data['responseType'] = 'guide';
                    break;

            case 'cpenquiry':
                    $data['ajaxUrl'] = "/trackingMIS/saMIS/getCPEnquiryDataForSelectedDuration";
                    $data['pageName'] ='Study Abroad';
                    $data['responseType'] = 'CPEnquiry';
                    break;

            case 'compare':
                    if($pageName){
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getCompareDataForSelectedDuration/".$pageName."/".$responseType;
                        $data['pageName'] =$pageName;
                    }else{
                        $data['ajaxUrl'] = "/trackingMIS/saMIS/getCompareDataForSelectedDuration";
                        $data['pageName'] ='Study Abroad';
                    }
                    $data['responseType'] = 'compare';
                    break;

            case 'overview':
                    $data['ajaxUrl'] = "";
                    $data['pageName'] ='Study Abroad';
                    $data['responseType'] = 'Overview';
                    break;
 		}
        $data['metric'] = $metric;
        //_p($data);die;
        if($data['metric'] == 'OVERVIEW'){
            $data['misSource'] = 'global';
            $data['source'] = 'abroad';
            $data['metric'] = 'overview';
        } else if($data['metric'] == 'LEADS'){
            $data['colorCodes'] = $this->colorCodes;
        }else if ($data['metric'] == 'RESPONSE'){
            $data['colorCodes'] = $this->colorCodes;
            $data['pivotName'] = 'response'; // used in the data table

            if($pageName){
                $data['pageName'] =$pageName;
            }else{
                $data['pageName'] ='Study Abroad';
            }
            $data['responseType'] = $responseType;
        }else if ($data['metric'] == 'RMC'){
            $data['colorCodes'] = $this->colorCodes;
            $data['pivotName'] = 'RMC Response'; // used in the data table

            if($pageName){
                $data['pageName'] =$pageName;
            }else{
                $data['pageName'] ='Study Abroad';
            }
            $data['responseType'] = $responseType;
        }
        $this->load->view('trackingMIS/misTemplate', $data);   
 	}

    function getMetric($functionName)
    {
        switch($functionName)
        {
            case 'commentReply':
                    $metric = 'COMMENT_REPLY';
                    break;

            case 'responses':
                    $metric = 'RESPONSE';
                    break;

            case 'RMC':
                    $metric ='RMC';
                    break;

            case 'traffic':
                    $metric = 'TRAFFIC';
                    break;

            case 'registrations':
                    $metric = 'REGISTRATION';
                    break;

            case 'downloads':
                    $metric = 'DOWNLOAD';
                    break;

            case 'cpenquiry':
                    $metric = 'CPENQUIRY';
                    break;

            case 'engagements':
                    $metric = 'ENGAGEMENT';
                    break;

            case 'compare':
                    $metric = 'COMPARE';
                    break;

            case 'overview':
                    $metric = 'OVERVIEW';
                    break;

            case 'leads':
                    $metric = 'LEADS';
                    break;
        }
        return $metric;
    }

    function getCompareDataForSelectedDuration($pageName,$responseType)
    {
        $dateRange = $this->input->post('dateRange');
        $extraData = $this->input->post('extraData');
        
        $filter = $extraData['studyAbroad'];
        $filterArray['startDate'] = $dateRange['startDate'];
        $filterArray['endDate'] = $dateRange['endDate'];
        $filter = $extraData['studyAbroad'];
        $filterArray['category'] = $filter['categoryId'];
        $filterArray['country'] = $filter['country'];
        $filterArray['courseLevel'] = $filter['courseLevel'];
        $filterArray['view'] = $filter['view'];
        $filterArray['courseComparedFilter'] = $filter['courseComparedFilter'];

        if($dateRange['startDateToCompare']){
            $pageData = $this->saTrackingLib->getCompareDataForSelectedDuration($pageName,$filterArray,'compare',0,'COMPARE',$this->colorCodes);
        
            $filterArray['startDate'] = $dateRange['startDateToCompare'];
            $filterArray['endDate'] = $dateRange['endDateToCompare'];
            $dataForComparision = $this->saTrackingLib->getCompareDataForSelectedDuration($pageName,$filterArray,'compare',0,'COMPARE',$this->colorCodes);
            $pageDataForComparision['page'] = $pageData;
            $pageDataForComparision['dataForComparision'] = $dataForComparision;
            echo json_encode($pageDataForComparision);
        }else{
            $pageData = $this->saTrackingLib->getCompareDataForSelectedDuration($pageName,$filterArray,'compare',1,'COMPARE',$this->colorCodes);
            echo json_encode($pageData);
        }   
    }

    function getCommentReplyDataForSelectedDuration($pageName ='')
    {
        $dateRange = $this->input->post('dateRange');
        $extraData = $this->input->post('extraData');
        //_p($dateRange);_p($extraData);_p($pageName);die;
        $filterArray['startDate'] = $dateRange['startDate'];
        $filterArray['endDate'] = $dateRange['endDate'];
        $filter = $extraData['studyAbroad'];
        $filterArray['category'] = $filter['categoryId'];
        $filterArray['country'] = $filter['country'];
        $filterArray['courseLevel'] = $filter['courseLevel'];
        $filterArray['view'] = $filter['view'];

        if($dateRange['startDateToCompare']){
            $pageData = $this->saTrackingLib->getCommentReplyDataForSelectedDuration($pageName,$filterArray,'commentReply',0,'COMMENT_REPLY',$this->colorCodes);
            $filterArray['startDate'] = $dateRange['startDateToCompare'];
            $filterArray['endDate'] = $dateRange['endDateToCompare'];
            $dataForComparision = $this->saTrackingLib->getCommentReplyDataForSelectedDuration($pageName,$filterArray,'commentReply',0,'COMMENT_REPLY',$this->colorCodes);
            $pageDataForComparision['page'] = $pageData;
            $pageDataForComparision['dataForComparision'] = $dataForComparision;
            echo json_encode($pageDataForComparision);
        }else{
            $pageDataForSelectedDuration = $this->saTrackingLib->getCommentReplyDataForSelectedDuration($pageName,$filterArray,'commentReply',1,'COMMENT_REPLY',$this->colorCodes);
            echo json_encode($pageDataForSelectedDuration);    
        }
    }

    function getCPEnquiryDataForSelectedDuration()
    {
        $dateRange = $this->input->post('dateRange');
        $extraData = $this->input->post('extraData');
        $filter = $extraData['studyAbroad'];
        $filterArray['startDate'] = $dateRange['startDate'];
        $filterArray['endDate'] = $dateRange['endDate'];
        $filterArray['consultantId'] = $filter['consultantId'];
        $filterArray['regionId'] = $filter['regionId'];
        $filterArray['view'] = $filter['view'];

        if($dateRange['startDateToCompare']){
            $pageData = $this->saTrackingLib->getCPEnquiryDataForSelectedDuration($filterArray,'CPEnquiry',0,'CPENQUIRY',$this->colorCodes);  
            $filterArray['startDate'] = $dateRange['startDateToCompare'];
            $filterArray['endDate'] = $dateRange['endDateToCompare'];
            $dataForComparision = $this->saTrackingLib->getCPEnquiryDataForSelectedDuration($filterArray,'CPEnquiry',0,'CPENQUIRY',$this->colorCodes);

            $pageDataForComparision['page'] = $pageData;
            $pageDataForComparision['dataForComparision'] = $dataForComparision;
            echo json_encode($pageDataForComparision);
        }else{
            $pageData = $this->saTrackingLib->getCPEnquiryDataForSelectedDuration($filterArray,'CPEnquiry',1,'CPENQUIRY',$this->colorCodes);
            echo json_encode($pageData);    
        }   
    }

    function getGuideDataForSelectedDuration($pageName='')
    {
        $dateRange = $this->input->post('dateRange');
        $extraData = $this->input->post('extraData');
        
        $filterArray['startDate'] = $dateRange['startDate'];
        $filterArray['endDate'] = $dateRange['endDate'];
        $filter = $extraData['studyAbroad'];
        $filterArray['category'] = $filter['categoryId'];
        $filterArray['country'] = $filter['country'];
        $filterArray['courseLevel'] = $filter['courseLevel'];
        $filterArray['view'] = $filter['view'];

        if($dateRange['startDateToCompare']){
            $pageData = $this->saTrackingLib->getDownloadsDataForSelectedDuration($pageName,$filterArray,'downloads',0,'DOWNLOAD',$this->colorCodes);
            $filterArray['startDate'] = $dateRange['startDateToCompare'];
            $filterArray['endDate'] = $dateRange['endDateToCompare'];
            $dataForComparision = $this->saTrackingLib->getDownloadsDataForSelectedDuration($pageName,$filterArray,'downloads',0,'DOWNLOAD',$this->colorCodes);
            $pageDateForComparision['page'] = $pageData;
            $pageDateForComparision['dataForComparision'] = $dataForComparision;
            echo json_encode($pageDateForComparision);
        }else{
            $pageDataForSelectedDuration = $this->saTrackingLib->getDownloadsDataForSelectedDuration($pageName,$filterArray,'downloads',1,'DOWNLOAD',$this->colorCodes);
            echo json_encode($pageDataForSelectedDuration);    
        }   
    }

    function getResponsesDataForSelectedDuration($pageName,$responseType)
    {
        $dateRange = $this->input->post('dateRange');
        $extraData = $this->input->post('extraData');
        
        $filter = $extraData['studyAbroad'];
        $filterArray['startDate'] = $dateRange['startDate'];
        $filterArray['endDate'] = $dateRange['endDate'];
        $filterArray['category'] = $filter['categoryId'];
        $filterArray['country'] = $filter['country'];
        $filterArray['courseLevel'] = $filter['courseLevel'];
        $filterArray['view'] = $filter['view'];
        if($pageName == 'rankingPage'){
            $filterArray['pageType'] = $filter['pageType'];
        }
        if($dateRange['startDateToCompare']){
            $pageData = $this->saTrackingLib->getResponsesDataForSelectedDuration($pageName,$filterArray,$responseType,0,'RESPONSE',$this->colorCodes);
            $filterArray['startDate'] = $dateRange['startDateToCompare'];
            $filterArray['endDate'] = $dateRange['endDateToCompare'];
            $dataForComparision = $this->saTrackingLib->getResponsesDataForSelectedDuration($pageName,$filterArray,$responseType,0,'RESPONSE',$this->colorCodes);
            $pageDateForComparision['page'] = $pageData;
            $pageDateForComparision['dataForComparision'] = $dataForComparision;
            echo json_encode($pageDateForComparision);
        }else{
            $pageData = $this->saTrackingLib->getResponsesDataForSelectedDuration($pageName,$filterArray,$responseType,1,'RESPONSE',$this->colorCodes);
            echo json_encode($pageData);
        }
    }

    function getRMCDataForSelectedDuration($pageName,$responseType)
    {
        $dateRange = $this->input->post('dateRange');
        $extraData = $this->input->post('extraData');
        
        $filter = $extraData['studyAbroad'];
        $filterArray['startDate'] = $dateRange['startDate'];
        $filterArray['endDate'] = $dateRange['endDate'];
        $filterArray['category'] = $filter['categoryId'];
        $filterArray['country'] = $filter['country'];
        $filterArray['courseLevel'] = $filter['courseLevel'];
        $filterArray['view'] = $filter['view'];
        if($pageName == 'rankingPage'){
            $filterArray['pageType'] = $filter['pageType'];
        }

        if($dateRange['startDateToCompare']){
            $pageData = $this->saTrackingLib->getResponsesDataForSelectedDuration($pageName,$filterArray,'rmc',0,'Response',$this->colorCodes);

            $filterArray['startDate'] = $dateRange['startDateToCompare'];
            $filterArray['endDate'] = $dateRange['endDateToCompare'];

            $dataForComparision = $this->saTrackingLib->getResponsesDataForSelectedDuration($pageName,$filterArray,'rmc',0,'Response',$this->colorCodes);
            $pageDateForComparision['page'] = $pageData;
            $pageDateForComparision['dataForComparision'] = $dataForComparision;
            echo json_encode($pageDateForComparision);
        }else{
            $pageDataForSelectedDuration = $this->saTrackingLib->getResponsesDataForSelectedDuration($pageName,$filterArray,'rmc',1,'Response',$this->colorCodes);
            echo json_encode($pageDataForSelectedDuration);    
        }    
    }   

    function getRegistrationsDataForSelectedDuration($pageName='')
    {
        $this->load->model('trackingMIS/cdmismodel');
        $this->cdMISModel = new cdmismodel();
        $this->saRegistrationLib = $this->load->library('trackingMIS/cdMISlib');

        $dateRange = $this->input->post('dateRange');
        $extraData = $this->input->post('extraData');
        $extraData['studyAbroad']['pageName'] = $pageName;
        $filterArray = $extraData['studyAbroad'];
    

        if($dateRange['startDateToCompare']){
            $dateArray = $dateRange;
            foreach ($dateRange as $key => $value) {
                if($key == 'startDateToCompare' || $key == 'endDateToCompare')
                {
                    unset($dateRange[$key]);
                }
            }
            $registrationData = $this->saRegistrationLib->getRegistrationsData($dateRange,$extraData);
            foreach ($registrationData['dataForcharts'] as $key => $value) {
                unset($registrationData['dataForcharts'][$key]['conversionType']);
            }

            $responsesData =$registrationData['dataForcharts'];
            $responseType ='';
            $filterArray['startDate'] = $dateRange['startDate'];
            $filterArray['endDate'] = $dateRange['endDate'];
            $filterArray['view'] = $extraData['studyAbroad']['view'];

            $pageData['dataForDifferentCharts'] = $this->saTrackingLib->prepareDataForDifferentCharts($responsesData,$pageName,$responseType,$filterArray,0,'REGISTRATION',$this->colorCodes);
            
            $pageData['topTiles'] = $registrationData['topTilesData'];


            // For Comparision
            $dateRange['startDate'] = $dateArray['startDateToCompare'];
            $dateRange['endDate'] = $dateArray['endDateToCompare'];

            $registrationData = $this->saRegistrationLib->getRegistrationsData($dateRange,$extraData);

            foreach ($registrationData['dataForcharts'] as $key => $value) {
                unset($registrationData['dataForcharts'][$key]['conversionType']);
            }
            $responsesData =$registrationData['dataForcharts'];
            $responseType ='';
            $filterArray['startDate'] = $dateRange['startDate'];
            $filterArray['endDate'] = $dateRange['endDate'];
            $filterArray['view'] = $extraData['studyAbroad']['view'];   

            $dataForComparision['dataForDifferentCharts'] = $this->saTrackingLib->prepareDataForDifferentCharts($responsesData,$pageName,$responseType,$filterArray,0,'REGISTRATION',$this->colorCodes);
            //_p($dataForComparision['dataForDifferentCharts']['donutChartData']);die;
            $dataForComparision['topTiles'] = $registrationData['topTilesData'];
            //_p($dataForComparision);die;
            $pageDateForComparision['page'] = $pageData;
            $pageDateForComparision['dataForComparision'] = $dataForComparision;
            //_p($pageDateForComparision);die;
            echo json_encode($pageDateForComparision);
        }else{
            $registrationData = $this->saRegistrationLib->getRegistrationsData($dateRange,$extraData);
            foreach ($registrationData['dataForcharts'] as $key => $value) {
                unset($registrationData['dataForcharts'][$key]['conversionType']);
                if($pageName){
                    unset($registrationData['dataForcharts'][$key]['page']);
                }
            }

            $responsesData =$registrationData['dataForcharts'];
            $responseType ='';
            $filterArray['startDate'] = $dateRange['startDate'];
            $filterArray['endDate'] = $dateRange['endDate'];
            $filterArray['view'] = $extraData['studyAbroad']['view'];   

            $pageData['dataForDifferentCharts'] = $this->saTrackingLib->prepareDataForDifferentCharts($responsesData,$pageName,$responseType,$filterArray,1,'REGISTRATION',$this->colorCodes);
            // for traffic source wise utm data 
            /*if(!$pageName){
                //$trackingIdsForSessions = $this->saTrackingLib->getTrackingIds($pageArray);
                if($filterArray['categoryId'] != 0 ||  $filterArray['country'] != 0 || $filterArray['courseLevel'] != '0')
                {   
                    $getUserIds = $this->cdMISModel->getRegistrationUserIds($dateRange,$extraData['studyAbroad']);
                    $getUserIdsArray = array_map(function($a){
                        return $a['UserId'];
                    }, $getUserIds);
                }
                $result = $this->saTrackingLib->getSessionBasedDataForRegistration($pageName,$filterArray,'registrations',$getUserIdsArray);
                //_p($result);die;
                foreach ($result['source'] as $key => $value) {
                    $totalCount += $value;
                }
                $temp = $pageData['dataForDifferentCharts']['donutChartData'][1];
                $pageData['dataForDifferentCharts']['donutChartData'][1] = $this->saTrackingLib->prepareDataForDonutChart($result['source'],$this->colorCodes,$totalCount);
                $pageData['dataForDifferentCharts']['donutChartData'][2] = $temp;

                $pageData['dataForDifferentCharts']['barGraphDataForRegistration'] =array($result['barGraph']['utmSource'],$result['barGraph']['utmCampaign'],$result['barGraph']['utmMedium']);
                $pageData['dataForDifferentCharts']['trafficSourceFilterData'] =$result['barGraph']['lis'];
                $pageData['dataForDifferentCharts']['defaultView'] =$result['barGraph']['defaultView'];    
            }*/
            
            $pageData['topTiles'] = $registrationData['topTilesData'];
            echo json_encode($pageData);    
        }   
    }

    function getTrafficDataForSelectedDuration($pageName='') 
    {
        $dateRange = $this->input->post('dateRange');
        $extraData = $this->input->post('extraData');
        //_p($extraData['studyAbroad']['aspect']);die;
        if($extraData['studyAbroad']['aspect'] == 'Page Views'){
            $result = $this->_prepareDataForTrafficPageviews($pageName,$dateRange,$extraData);
            //_p($result['dataForDifferentCharts']['dataForDataTable']);die;
            echo json_encode($result);
        }else{
            if($dateRange['startDateToCompare']){
                $dateArray = $dateRange;
                foreach ($dateRange as $key => $value) {
                    if($key == 'startDateToCompare' || $key == 'endDateToCompare')
                    {
                        unset($dateRange[$key]);
                    }
                }
                    
                $trafficData = $this->trafficDataLib->getTotalVisitForPage($dateRange,$extraData,$pageName,1,1,$this->colorCodes);  

                $dateRange['startDate'] = $dateArray['startDateToCompare'];
                $dateRange['endDate'] = $dateArray['endDateToCompare'];

                $trafficDataToComparision = $this->trafficDataLib->getTotalVisitForPage($dateRange,$extraData,$pageName,1,1,$this->colorCodes); 
                $trafficDataForComparision['page'] = $trafficData;
                $trafficDataForComparision['dataForComparision'] = $trafficDataToComparision;
                echo json_encode($trafficDataForComparision);
            }else{
                $trafficData = $this->trafficDataLib->getTotalVisitForPage($dateRange,$extraData,$pageName,0,0,$this->colorCodes);
                echo json_encode($trafficData);    
            }
        }        
    }

    function _prepareDataForTrafficPageviews($pageName,$dateRange,$extraData){
        //_p($pageName);_p($dateRange);_p($extraData);die;
        
        if($dateRange['startDateToCompare']){
            $dateArray = $dateRange;
            foreach ($dateRange as $key => $value) {
                if($key == 'startDateToCompare' || $key == 'endDateToCompare')
                {
                    unset($dateRange[$key]);
                }
            }
            $trafficData = $this->trafficDataLib->prepareDataForTrafficPageviews($dateRange,$extraData,$pageName,1,1,$this->colorCodes);
            $dateRange['startDate'] = $dateArray['startDateToCompare'];
            $dateRange['endDate'] = $dateArray['endDateToCompare'];

            $trafficDataToComparision = $this->trafficDataLib->prepareDataForTrafficPageviews($dateRange,$extraData,$pageName,1,1,$this->colorCodes);
            $trafficDataForComparision['page'] = $trafficData;
            $trafficDataForComparision['dataForComparision'] = $trafficDataToComparision;
            return $trafficDataForComparision;
        }else{
            return $this->trafficDataLib->prepareDataForTrafficPageviews($dateRange,$extraData,$pageName,0,0,$this->colorCodes);    
        }
        /*$data['topTiles'] = $this->trafficDataLib->prepareTopTile():
        $data['lineChart'] = $this->trafficDataLib->prepareTopTile():*/
    }

    function getEngagementDataForSelectedDuration($pageName='')
    {
        $dateRange = $this->input->post('dateRange');
        $extraData = $this->input->post('extraData');
        if($dateRange['startDateToCompare']){
            $dateArray = $dateRange;
            foreach ($dateRange as $key => $value) {
                if($key == 'startDateToCompare' || $key == 'endDateToCompare')
                {
                    unset($dateRange[$key]);
                }
            }
            $engData = $this->trafficDataLib->getEngagementData($dateRange,$extraData,$pageName,1,1,$this->colorCodes);  
            //_p($engData);die;
            $dateRange['startDate'] = $dateArray['startDateToCompare'];
            $dateRange['endDate'] = $dateArray['endDateToCompare'];

            $engDataToComparision = $this->trafficDataLib->getEngagementData($dateRange,$extraData,$pageName,1,1,$this->colorCodes); 
            $engDataForComparision['page'] = $engData;
            $engDataForComparision['dataForComparision'] = $engDataToComparision;
            echo json_encode($engDataForComparision);
        }else{
            $pageData = $this->trafficDataLib->getEngagementData($dateRange,$extraData,$pageName,'0','0',$this->colorCodes);
            //_p($pageData);die;
            echo json_encode($pageData);      
        }     
    }
    
    private function _populateAbroadCountries(& $data)
    {
        $countries = $this->locationRepository->getAbroadCountries();
        
        foreach($countries as $key => $country){
            if($country->getId() == 1) {
                unset($countries[$key]);
                break;
            }
        }

        //sort countries by name ascending order
        usort($countries,function($c1,$c2){
            return (strcasecmp($c1->getName(),$c2->getName()));
        });
        $data['abroadCountries'] = $countries;
    } 

    function prepareDataForTrafficSourceForAjaxCall(){
        $dateRange = $this->input->post('dateRange');
        $defaultView = $this->input->post('defaultView');
        $filters = $this->input->post('filters');
        $count = $this->input->post('count');
        $filterAjax = $this->input->post('responseType');
        $responseTypeFilter = $this->input->post('responseTypeFilter');
        $pageName = $this->input->post('pageName');
        $pageType = $this->input->post('pageType');
        $sourceFlag = $this->input->post('flag');
        switch ($pageName) {
            case 'categoryPage' :
                $page = array('categoryPage','savedCoursesTab');
                break;
            case 'rankingPage':
                switch ($pageType) {
                    case 0:
                        $page = array('courseRankingPage', 'universityRankingPage');
                        break;
                    case 1:
                        $page = 'universityRankingPage';
                        break;
                    case 2:
                        $page = 'courseRankingPage';
                        break;
                    default:
                        $page = array('courseRankingPage', 'universityRankingPage');
                        break;
                } 
                break;
            default :
                $page = $pageName;
                break;
        }
        if($page == 'Study Abroad'){
            $page = '';
        }
        //_p($dateRange);_p($sourceApplication);_p($defaultView);_p($filters);_p($source);_p($sourceFlag);die;
        $filter = array(
                        'dateRange' => $dateRange,
                        'defaultView' => $defaultView,
                        'filters' => $filters,
                        'count' => $count,
                        'filterAjax' => $filterAjax,
                        'responseTypeFilter' => $responseTypeFilter,
                        'page' => $page,
                        'sourceFlag' => $sourceFlag,
                        'colorCodes' => $this->colorCodes
                        );


        //_p($sourceApplication);die;
        echo json_encode($this->saTrackingLib->prepareDataForTrafficSourceForAjaxCall($filter));
        //echo json_encode($this->saTrackingLib->prepareDataForTrafficSourceForAjaxCall($this->colorCodes));
    }

    function prepareDataForTrafficSourceForAjaxCallForRegistration(){
        $dateRange = $this->input->post('dateRange');
        $defaultView = $this->input->post('defaultView');
        $filters = $this->input->post('filters');
        $sourceFlag = $this->input->post('flag');
        $count = $this->input->post('count');
        $pageName = $this->input->post('pageName');
        $pageType = $this->input->post('pageType');
       
        if($pageName == 'Study Abroad'){
            $pageName = '';
        }
        $filter = array(
                        'dateRange' => $dateRange,
                        'defaultView' => $defaultView,
                        'filters' => $filters,
                        'count' => $count,
                        'page' => $pageName,
                        'pageType' => $pageType,
                        'sourceFlag' => $sourceFlag,
                        'colorCodes' => $this->colorCodes
                        );

        //_p($filter);die;
        echo json_encode($this->saTrackingLib->prepareDataForTrafficSourceForAjaxCallForRegistration($filter));
    } 

    function prepareDataForTrafficSourceForAjaxCallForPageviews(){
        $dateRange = $this->input->post('dateRange');
        $defaultView = $this->input->post('defaultView');
        $filters = $this->input->post('filters');
        $count = $this->input->post('count');
        $pageName = $this->input->post('pageName');
        if($pageName == 'rankingPage'){
            $pageType = $this->input->post('rankingPageType');
        }
        //_p($dateRange);_p($sourceApplication);_p($defaultView);_p($filters);_p($source);die;
        //_p($sourceApplication);die;
        echo json_encode($this->trafficDataLib->prepareDataForTrafficSourceForAjaxCallForPageviews($dateRange,$defaultView,$filters,$count,$pageName,$pageType));  
    }

    function prepareDataForTrafficSourceForAjaxCallForPgpersess(){
        $dateRange = $this->input->post('dateRange');
        $defaultView = $this->input->post('defaultView');
        $filters = $this->input->post('filters');
        $count = $this->input->post('count');
        $pageName = $this->input->post('pageName');
        if($pageName == 'rankingPage'){
            $pageType = $this->input->post('rankingPageType');
        }
        //_p($dateRange);_p($sourceApplication);_p($defaultView);_p($filters);_p($source);die;
        //_p($sourceApplication);die;
        echo json_encode($this->trafficDataLib->prepareDataForTrafficSourceForAjaxCallForPgpersess($dateRange,$defaultView,$filters,$count,$pageName,$pageType));
    }

    function prepareDataForTrafficSourceForAjaxCallForAvgsessdur(){
        $dateRange = $this->input->post('dateRange');
        $defaultView = $this->input->post('defaultView');
        $filters = $this->input->post('filters');
        $count = $this->input->post('count');
        $pageName = $this->input->post('pageName');
        if($pageName == 'rankingPage'){
            $pageType = $this->input->post('rankingPageType');
        }
        //_p($dateRange);_p($sourceApplication);_p($defaultView);_p($filters);_p($source);die;
        //_p($sourceApplication);die;
        echo json_encode($this->trafficDataLib->prepareDataForTrafficSourceForAjaxCallForAvgsessdur($dateRange,$defaultView,$filters,$count,$pageName,$pageType));
    }

    function prepareDataForTrafficSourceForAjaxCallForBounce(){

        $dateRange = $this->input->post('dateRange');
        $defaultView = $this->input->post('defaultView');
        $filters = $this->input->post('filters');
        $count = $this->input->post('count');
        $pageName = $this->input->post('pageName');
        if($pageName == 'rankingPage'){
            $pageType = $this->input->post('rankingPageType');
        }
        //_p($dateRange);_p($sourceApplication);_p($defaultView);_p($filters);_p($source);die;
        //_p($sourceApplication);die;
        echo json_encode($this->trafficDataLib->prepareDataForTrafficSourceForAjaxCallForBounce($dateRange,$defaultView,$filters,$count,$pageName,$pageType));   
    }

    function prepareDataForTrafficSourceForAjaxCallForExit(){
        $dateRange = $this->input->post('dateRange');
        $defaultView = $this->input->post('defaultView');
        $filters = $this->input->post('filters');
        $count = $this->input->post('count');
        $pageName = $this->input->post('pageName');
        if($pageName == 'rankingPage'){
            $pageType = $this->input->post('rankingPageType');
        }
        //_p($dateRange);_p($sourceApplication);_p($defaultView);_p($filters);_p($source);die;
        //_p($sourceApplication);die;
        echo json_encode($this->trafficDataLib->prepareDataForTrafficSourceForAjaxCallForExit($dateRange,$defaultView,$filters,$count,$pageName,$pageType));
    }

    function prepareDataForTrafficSourceForAjaxCallForTraffic(){
        $dateRange = $this->input->post('dateRange');
        $defaultView = $this->input->post('defaultView');
        $filters = $this->input->post('filters');
        $count = $this->input->post('count');
        //_p($dateRange);_p($sourceApplication);_p($defaultView);_p($filters);_p($source);die;
        //_p($sourceApplication);die;
        echo json_encode($this->MISCommonLib->prepareDataForBarGraphForTrafficSource($dateRange,'',$defaultView,$filters,'abroad'));
    }
}
?>	
