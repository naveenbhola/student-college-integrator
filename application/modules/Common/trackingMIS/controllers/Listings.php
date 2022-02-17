<?php

/**
 * Controller for Domestic MIS.
 */
class Listings extends MX_Controller
{
    private static $INDIA;
    private static $DATE_FORMAT;
    private static $MANAGEMENT, $FULL_TIME_MBA;
    private static $ENGINEERING, $BE_BTECH;
    private $trackingLib;
    private $colorCodes;
    private $teamName;

    function __construct()
    {
        ini_set("memory_limit", "512M");
        parent::__construct();

        Listings::$ENGINEERING   = 2;
        Listings::$MANAGEMENT    = 3;
        Listings::$FULL_TIME_MBA = 23;
        Listings::$BE_BTECH      = 56;
        Listings::$INDIA         = 2;
        Listings::$DATE_FORMAT   = 'DD/MM/YYYY';

        $this->trackingLib = $this->load->library('trackingMIS/trackingMISCommonLib');
        $this->MISCommonLib = $this->load->library('trackingMIS/MISCommonLib');

        $this->colorCodes = array(
            "#FDDB6D",
            "#80DAEB",
            "#FF8243",
            "#BAB86C",
            "#17806D",
            "#C8385A",
            "#71BC78",
            "#7366BD",
            "#FC2847",
            "#414A4C",
            "#FFA089",
            "#95918C",
            "#FDD9B5",
            "#B2EC5D",
            "#FFCF48",
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
            "#1CAC78",
            "#78DBE2",
        );
        $this->teamName    = 'Domestic';
    }

    private function _loadDependencies()
    {
        $data['userDataArray'] = reset($this->trackingLib->checkValidUser());
        $data['misSource']     = "nationalListing";
        $data['actionName']    = $this->router->fetch_method();
        $this->load->config('listingsTrackingMISConfig');
        $data['leftMenuArray'] = $this->config->item("leftMenuArray");
        $this->load->model('nationalListings/listings_model', 'ListingsModel');

        return $data;
    }

    public function dashboard(){
        $data = $this->_loadDependencies();
        $data['source'] = 'national';
        $data['metric'] = 'overview';
        $data['misSource'] = 'national';
        $data['teamName'] = 'Domestic';
        $data['colorCodes'] = $this->colorCodes;
        $data['leftMenuArray'] = $this->config->item("leftMenuArray");
        $metricArray = $this->config->item('METRIC');
        $data['pageDetails'] = $metricArray['OVERVIEW'];
        $this->load->view('trackingMIS/misTemplate', $data);
    }

    public function leads(){
        $data = $this->_loadDependencies();
        $data['source'] = 'national';
        $data['metric'] = 'leads';
        $data['misSource'] = 'national';
        $data['teamName'] = 'Domestic';
        $data['colorCodes'] = $this->colorCodes;
        $data['leftMenuArray'] = $this->config->item("leftMenuArray");
        $this->load->config('globalTrackingMISConfig');
        $metricArray = $this->config->item('METRIC');
        $data['pageDetails'] = $metricArray['LEADS'];
        $data['categories'] = $this->ListingsModel->getCategories();

        $this->load->view('trackingMIS/misTemplate', $data);
    }

    public function response()
    {
        $data                     = $this->_loadDependencies();
        $data['metricName']       = $this->input->get('dim');
        $data['pivotName']        = 'response';
        $data['misSource']        = 'nationalListing';
        $data['teamName']         = 'Domestic';
        $data['colorCodes']       = $this->colorCodes;
        $data['leftMenuArray']    = $this->config->item("leftMenuArray");
        $responseConfig           = $this->config->item('responses');
        $data['tileNames']        = $responseConfig['tileNames'];
        $data['splits']           = $responseConfig['splits'];
        $data['categories']       = $this->ListingsModel->getCategories();
        $data['splitInformation'] = array();

        $data['dates'] = array(
            'startDate' => $this->MISCommonLib->getDefault('startDate', ''),
            'endDate'   => $this->MISCommonLib->getDefault('endDate', '')
        );

        $data['view'] = $this->MISCommonLib->decideView($data['dates'], 1, 'db');

        $this->load->view('nationalListings/metrics', $data);
    }


    /**
     * A method for which an alternate method providing the same functionality is available at Common::obtainInput.
     * @param $team
     */
    private function obtainInput($team){

        $this->inputRequest = new stdClass();
        $dateRange          = $this->input->get_post('dateRange');
        if ($dateRange != '') { // for some code, date is sent like this
            $this->inputRequest->startDate = $dateRange['startDate'];
            $this->inputRequest->endDate   = $dateRange['endDate'];
        } else {
            $this->inputRequest->startDate = $this->MISCommonLib->getFormattedDate($this->input->get_post('startdate'), 'DD/MM/YYYY');
            $this->inputRequest->endDate   = $this->MISCommonLib->getFormattedDate($this->input->get_post('enddate'), 'DD/MM/YYYY');
        }
        $this->inputRequest->compareStartDate = $this->MISCommonLib->getFormattedDate($this->input->get_post('comparestartdate'), 'DD/MM/YYYY');
        $this->inputRequest->compareEndDate   = $this->MISCommonLib->getFormattedDate($this->input->get_post('compareenddate'), 'DD/MM/YYYY');
        $this->inputRequest->splitAspect      = $this->input->get_post('splitAspect');
        $this->inputRequest->pivotType        = $this->MISCommonLib->getDefault('type', $this->input->get_post('type'));
        $this->inputRequest->viewType         = $this->input->get_post('view');
        $this->inputRequest->pageName         = $this->input->get_post('pageName');
        $this->inputRequest->sourceApplication = $this->MISCommonLib->getDefault('device', $this->input->get_post('source'));


        switch ($team) {
            case 'global':
            case '':
                break;

            case 'domestic':
            case 'national':
                $this->inputRequest->pageName          = $this->input->get_post('dim');
                $this->inputRequest->category          = $this->input->get_post('id');
                $this->inputRequest->subcategoryId     = $this->input->get_post('subid');
                $this->inputRequest->pivotName         = $this->MISCommonLib->getDefault('pivot', $this->input->get_post('pivot'));
                break;

            case 'abroad':
                $this->inputRequest->pageName    = $this->input->get_post('pageName');
                $this->inputRequest->category    = $this->input->get_post('category');
                $this->inputRequest->country     = $this->input->get_post('country');
                $this->inputRequest->courseLevel = $this->input->get_post('courseLevel');
                $this->inputRequest->pageType    = $this->input->get_post('pageType');
                $this->inputRequest->view        = $this->input->get_post('view');
                $this->inputRequest->isRMC       = $this->input->get_post('isRMC');
                break;
        }
    }


    private function getSplitData($splits = array(),$inputRequestFilter = '')
    {
        $model = $this->ListingsModel;
        if (count($splits) > 0) {
            if( empty($inputRequestFilter))
            {
                $data['splitInformation'] = array(
                'source Application' => $model->getData('device', $splits),
                'page'               => $model->getData('page', $splits),
                'action'             => $model->getData('action', $splits),
                'widget'             => $model->getData('widget', $splits),
                'response Type'      => $model->getData('paidOrFree', $splits)
                );
            }
            $sessionMapping = $model->getData('session',$splits);
            $sessionResult = $this->getResponseDataBasedOnSession($sessionMapping);
            $data['splitInformation']['response Source'] = $sessionResult['sourceWise'];
            if( ! empty($sessionResult['sourceSession']))
            {
                $data['splitForBarGraph'] = $this->getUTMWiseDataForBarGraph($sessionMapping,$sessionResult['sourceSession'],$inputRequestFilter);
            }
            //$data['splitInformation']['response UTM source '] = $sessionResult['utmWise'];
        } else {
            $data['splitInformation'] = array(
                'source Application'        => $model->getData('device'),
                'page'          => $model->getData('page'),
                'action'        => $model->getData('action'),
                'widget'        => $model->getData('widget'),
                'response Type' => $model->getData('paidOrFree'),
            );
        }
        return $data;
    }

    // Code for registration MIS start
    public function registration(){
        $data = $this->_loadDependencies();
        $data['metricName'] = $this->input->get('dim');
        $data['pivotName'] = 'registration';
        $data['misSource'] = 'nationalListing';
        $data['teamName'] = 'Domestic';
        $data['colorCodes'] = $this->colorCodes;
        $data['leftMenuArray'] = $this->config->item("leftMenuArray");
        $responseConfig = $this->config->item('registration');
        $data['tileNames'] = $responseConfig['tileNames'];
        $data['splits'] = $responseConfig['splits'];
        $data['categories'] = $this->ListingsModel->getCategories();
        $data['splitInformation'] = array();

        $data['dates'] = array(
            'startDate' => $this->MISCommonLib->getDefault('startDate', ''),
            'endDate'   => $this->MISCommonLib->getDefault('endDate', '')
        );

        $data['view'] = $this->MISCommonLib->decideView($data['dates'], 1, 'db');

        $this->load->view('nationalListings/metrics', $data);
    }

    public function registrationTiles(){
        $data               = $this->_loadDependencies();
        $this->Registration = $this->load->model('trackingMIS/cdmismodel');

        $startDate   = $this->MISCommonLib->getDefault('startDate', $this->input->get('startdate'));
        $endDate     = $this->MISCommonLib->getDefault('endDate', $this->input->get('enddate'));
        $compareStartDate   = $this->input->get('comparestartdate');
        $compareEndDate     = $this->input->get('compareenddate');
        $pageName    = $this->MISCommonLib->getDefault('pageName', $this->input->get('dim'));
        $metricId    = intval($this->input->get('id'));//$this->getDefault('category', $this->input->get('id'));
        $metricSubId = intval($this->input->get('subid')); //$this->getDefault('subcategory', $this->input->get('subid'));
        $deviceType  = $this->MISCommonLib->getDefault('device', $this->input->get('source'));
        $widgetId    = $this->input->get('widgetname') != '' && $this->input->get('widgetname') != '0' ? $this->input->get('widgetname'): 0;
        $viewType = $this->input->get('view');
        if($metricId == 14){
            $examId = $this->input->get('examId');
            $subExamId = $this->input->get('subExamId');
        }

        $dates       = array(
            'startDate' => $this->MISCommonLib->getFormattedDate($startDate, Listings::$DATE_FORMAT),
            'endDate'   => $this->getFormattedDate($endDate, Listings::$DATE_FORMAT)
        );

        $compareDates = array();
        if($compareStartDate != '' && $compareEndDate != ''){
            $compareDates     = array(
                'startDate' => $this->getFormattedDate($compareStartDate, Listings::$DATE_FORMAT),
                'endDate'   => $this->getFormattedDate($compareEndDate, Listings::$DATE_FORMAT)
            );
        }

        /*$compareDates     = array(
//            'startDate' => $this->getFormattedDate($compareStartDate, Listings::$DATE_FORMAT),
//            'endDate'   => $this->getFormattedDate($compareEndDate, Listings::$DATE_FORMAT)

            'startDate' => '2015-11-11',
            'endDate'   => '2016-02-24'
        );*/

        //$this->setDefaultCategorySubCategory($metricId, $metricSubId);

        $data['dimension'] = $this->getPageName($pageName);
        $pageName = $this->MISCommonLib->getPageNameForDomestic($pageName);

        $extraData = array(
            'category'    => $metricId,
            'subcategory' => $metricSubId,
            'pageName'    => $pageName,
            'view' => $viewType
        );
        if($metricId == 14){
            $extraData['examId'] = $examId;
            $extraData['subExamId'] = $subExamId;
        }

        if($widgetId != 0){
            $extraData['National']['widget'] = $widgetId;
        } // probably unused

        if ($deviceType != 'all') {
            $extraData['deviceType'] = $deviceType;
        }

        $getRegistrationTiles = function ($dates, $extraData, $thisObject) {

            $pageWiseRegistrations           = $thisObject->Registration->getNationalRegistrationData_PageWise($dates, $extraData);
            $widgetNameWiseRegistrations     = $thisObject->Registration->getNationalRegistrationData_WidgetNameWise($dates, $extraData);
            $conversionTypeWiseRegistrations = $thisObject->Registration->getNationalRegistrationData_conversionTypeWise($dates, $extraData);
            $totalRegistrations              = 0;
            $mmpRegistrations                = 0;
            $signupRegistrations             = 0;
            $hamburgerRegistrations          = 0;
            $responseRegistrations           = 0;

            foreach ($pageWiseRegistrations as $onePageData) {
                $totalRegistrations += $onePageData->ScalarValue;

                if (strcasecmp($onePageData->PivotName, 'mmp') == 0) {
                    $mmpRegistrations += $onePageData->ScalarValue;
                } else if (strcasecmp($onePageData->PivotName, 'hamburgerRightPanel') == 0) {
                    $hamburgerRegistrations += $onePageData->ScalarValue;
                }
            }

            $validConversionTypes = array('response','Course shortlist','downloadBrochure','send Contat Detail','send Contact Detail');
            foreach ($widgetNameWiseRegistrations as $oneDevice) {

                if ($oneDevice->PivotName == 'registerFree') {
                    $signupRegistrations = $oneDevice->ScalarValue;
                }
            }

            foreach ($conversionTypeWiseRegistrations as $oneValue) {
                if (in_array($oneValue->PivotName, $validConversionTypes)) {
                    $responseRegistrations += $oneValue->ScalarValue;
                }
            }

            return array(
                'totalCount'        => number_format($totalRegistrations),
                'mmpCount'          => number_format($mmpRegistrations),
                'responseRegCount'  => number_format($responseRegistrations),
                'signupRegCount'    => number_format($signupRegistrations),
                'hamburgerRegCount' => number_format($hamburgerRegistrations)
            );
        };


        $tileData = array(
            'topTilesData' => $getRegistrationTiles($dates, $extraData, $this)
        );


        if(count($compareDates) > 0){
            $dates = array(
                'startDate' => $compareDates['startDate'],
                'endDate' => $compareDates['endDate'],
            );
            $tileData['compareTilesData'] = $getRegistrationTiles($dates, $extraData);
        }

        echo json_encode(
            $tileData
        );

    }

    public function registrationTrends(){
        $data               = $this->_loadDependencies();
        $this->Registration = $this->load->model('trackingMIS/cdmismodel');

        $startDate   = $this->getDefault('startDate', $this->input->get('startdate'));
        $endDate     = $this->getDefault('endDate', $this->input->get('enddate'));
        $compareStartDate   = $this->input->get('comparestartdate');
        $compareEndDate     = $this->input->get('compareenddate');
        $pageName    = $this->getDefault('pageName', $this->input->get('dim'));
        $metricId    = $this->input->get('id');//$this->getDefault('category', $this->input->get('id'));
        $metricSubId = $this->input->get('subid'); //$this->getDefault('subcategory', $this->input->get('subid'));
        $deviceType  = $this->getDefault('device', $this->input->get('source'));
        $widgetId    = $this->input->get('widgetname') != '' && $this->input->get('widgetname') != '0' ? $this->input->get('widgetname'): 0;
        $viewType = $this->input->get('view');
        if($metricId == 14){
            $examId = $this->input->get('examId');
            $subExamId = $this->input->get('subExamId');
        }

        $dates       = array(
            'startDate' => $this->getFormattedDate($startDate, Listings::$DATE_FORMAT),
            'endDate'   => $this->getFormattedDate($endDate, Listings::$DATE_FORMAT)
        );

        $compareDates = array();
        if($compareStartDate != '' && $compareEndDate != ''){
            $compareDates     = array(
                'startDate' => $this->getFormattedDate($compareStartDate, Listings::$DATE_FORMAT),
                'endDate'   => $this->getFormattedDate($compareEndDate, Listings::$DATE_FORMAT)
            );
        }

        $this->setDefaultCategorySubCategory($metricId, $metricSubId);

        $data['dimension'] = $this->getPageName($pageName);
        $pageName = $this->MISCommonLib->getPageNameForDomestic($pageName);

        $extraData = array(
            'category'    => $metricId,
            'subcategory' => $metricSubId,
            'pageName'    => $pageName,
            'queryMode' => 'graph',
            'view' => $viewType
        );
        if($metricId == 14){
            $extraData['examId'] = $examId;
            $extraData['subExamId'] = $subExamId;
        }

        if($widgetId != 0){
            $extraData['National']['widget'] = $widgetId;
        } // probably unused

        if ($deviceType != 'all') {
            $extraData['National']['deviceType'] = $deviceType;
        }

        $registrationTrends         = $this->Registration->getNationalRegistrationData($dates, $extraData, $viewType);

        $graphData = array(
            'resultsForGraph' => count($registrationTrends) > 0 ? $this->MISCommonLib->insertZeroValues($registrationTrends, $dates, $viewType) : array()
        );

        if(count($compareDates) > 0){
            $dates = array(
                'startDate' => $compareDates['startDate'],
                'endDate' => $compareDates['endDate'],
            );
            $compareResponseTrends = $this->Registration->getNationalRegistrationData($dates, $extraData, $viewType);
            $graphData['comparisonResultsForGraph'] = count($compareResponseTrends) > 0 ? $this->MISCommonLib->insertZeroValues($compareResponseTrends, $dates, $viewType) : array();
        }

        echo json_encode( $graphData );
    }

    public function registrationSplit(){
        $data               = $this->_loadDependencies();
        $this->Registration = $this->load->model('trackingMIS/cdmismodel');

        $startDate   = $this->MISCommonLib->getDefault('startDate', $this->input->get('startdate'));
        $endDate     = $this->MISCommonLib->getDefault('endDate', $this->input->get('enddate'));
        $compareStartDate   = $this->input->get('comparestartdate');
        $compareEndDate     = $this->input->get('compareenddate');
        $pageName    = $this->MISCommonLib->getDefault('pageName', $this->input->get('dim'));
        $metricId    = $this->input->get('id');//$this->getDefault('category', $this->input->get('id'));
        $metricSubId = $this->input->get('subid'); //$this->getDefault('subcategory', $this->input->get('subid'));
        $deviceType  = $this->MISCommonLib->getDefault('device', $this->input->get('source'));
        $widgetId    = $this->input->get('widgetname') != '' && $this->input->get('widgetname') != '0' ? $this->input->get('widgetname'): 0;
        $viewType = $this->input->get('view');
        if($metricId == 14){
            $examId = $this->input->get('examId');
            $subExamId = $this->input->get('subExamId');
        }
        $aspect = $this->input->get('splitAspect');


        $dates       = array(
            'startDate' => $this->getFormattedDate($startDate, Listings::$DATE_FORMAT),
            'endDate'   => $this->getFormattedDate($endDate, Listings::$DATE_FORMAT)
        );

        $compareDates = array();
        if($compareStartDate != '' && $compareEndDate != ''){
            $compareDates     = array(
                'startDate' => $this->getFormattedDate($compareStartDate, Listings::$DATE_FORMAT),
                'endDate'   => $this->getFormattedDate($compareEndDate, Listings::$DATE_FORMAT)
            );
        }

        /*$compareDates     = array(
            'startDate' => '2015-11-11',
            'endDate'   => '2016-02-24'
        );*/

        $this->setDefaultCategorySubCategory($metricId, $metricSubId);

        $data['dimension'] = $this->getPageName($pageName);
        $pageName = $this->MISCommonLib->getPageNameForDomestic($pageName);

        $extraData = array(
            'category'    => $metricId,
            'subcategory' => $metricSubId,
            'pageName'    => $pageName,
            'view' => $viewType
        );
        if($metricId == 14){
            $extraData['examId'] = $examId;
            $extraData['subExamId'] = $subExamId;
        }

        if($widgetId != 0){
            $extraData['National']['widget'] = $widgetId;
        } // probably unused

        if ($deviceType != 'all') {
            $extraData['deviceType'] = $deviceType;
        }

        $getRegistrationSplit = function($dates, $extraData, $aspect,$thisObject){
            switch($aspect){
                case 'device':
                    return $thisObject->Registration->getNationalRegistrationData_DeviceWise($dates, $extraData);
                case 'widget':
                    return $thisObject->Registration->getNationalRegistrationData_WidgetWise($dates, $extraData);
                case 'pivotType':
                    return $thisObject->Registration->getNationalRegistrationData_paidFreeWise($dates, $extraData);
                case 'pageName':
                    return $thisObject->Registration->getNationalRegistrationData_PageWise($dates, $extraData);
            }
        };


        $splitData = array(
            $aspect => $getRegistrationSplit($dates, $extraData, $aspect,$this)
        );

        if(count($compareDates) > 0){
            $dates = array(
                'startDate' => $compareDates['startDate'],
                'endDate' => $compareDates['endDate'],
            ); // implement comparison data here
            $splitData['compareSplitData'] = $getRegistrationSplit($dates, $extraData, $aspect);
        }

        echo json_encode(
            $splitData
        );


    }

    public function registrationTable(){
        $data               = $this->_loadDependencies();
        $this->Registration = $this->load->model('trackingMIS/cdmismodel');

        $startDate   = $this->MISCommonLib->getDefault('startDate', $this->input->get('startdate'));
        $endDate     = $this->MISCommonLib->getDefault('endDate', $this->input->get('enddate'));
        $compareStartDate   = $this->input->get('comparestartdate');
        $compareEndDate     = $this->input->get('compareenddate');
        $pageName    = $this->MISCommonLib->getDefault('pageName', $this->input->get('dim'));
        $metricId    = $this->input->get('id');//$this->getDefault('category', $this->input->get('id'));
        $metricSubId = $this->input->get('subid'); //$this->getDefault('subcategory', $this->input->get('subid'));
        $deviceType  = $this->MISCommonLib->getDefault('device', $this->input->get('source'));
        $widgetId    = $this->input->get('widgetname') != '' && $this->input->get('widgetname') != '0' ? $this->input->get('widgetname'): 0;
        $viewType = $this->input->get('view');
        if($metricId == 14){
            $examId = $this->input->get('examId');
            $subExamId = $this->input->get('subExamId');
        }
        $aspect = $this->input->get('splitAspect');


        $dates       = array(
            'startDate' => $this->getFormattedDate($startDate, Listings::$DATE_FORMAT),
            'endDate'   => $this->getFormattedDate($endDate, Listings::$DATE_FORMAT)
        );

        $compareDates = array();
        if($compareStartDate != '' && $compareEndDate != ''){
            $compareDates     = array(
                'startDate' => $this->getFormattedDate($compareStartDate, Listings::$DATE_FORMAT),
                'endDate'   => $this->getFormattedDate($compareEndDate, Listings::$DATE_FORMAT)
            );
        }

        $this->setDefaultCategorySubCategory($metricId, $metricSubId);

        $data['dimension'] = $this->getPageName($pageName);
        $pageName = $this->MISCommonLib->getPageNameForDomestic($pageName);

        $extraData = array(
            'category'    => $metricId,
            'subcategory' => $metricSubId,
            'pageName'    => $pageName,
            'view' => $viewType
        );
        if($metricId == 14){
            $extraData['examId'] = $examId;
            $extraData['subExamId'] = $subExamId;
        }

        if($widgetId != 0){
            $extraData['National']['widget'] = $widgetId;
        } // probably unused

        if ($deviceType != 'all') {
            $extraData['deviceType'] = $deviceType;
        }

        $tableData = $this->Registration->getNationalRegistrationData($dates, $extraData);

        $arrangeData = function ($someData) {
            $total = 0;

            foreach ($someData as $oneData) { // Calculate the total value
                $total += $oneData->ResponseCount;
            }

            foreach($someData as $key => $oneData) {
                $someData[$key]->Percentage = number_format($oneData->ResponseCount / $total * 100, 2, ".", "");
            }

            return $someData;
        };

        echo json_encode(
            array(
                'resultsToShow' => $arrangeData($tableData)
            )
        );


    }

    public function registrationSessionSplit(){
        $data               = $this->_loadDependencies();
        $this->Registration = $this->load->model('trackingMIS/cdmismodel');

        $startDate   = $this->MISCommonLib->getDefault('startDate', $this->input->get('startdate'));
        $endDate     = $this->MISCommonLib->getDefault('endDate', $this->input->get('enddate'));
        $compareStartDate   = $this->input->get('comparestartdate');
        $compareEndDate     = $this->input->get('compareenddate');
        $pageName    = $this->MISCommonLib->getDefault('pageName', $this->input->get('dim'));
        $metricId    = $this->input->get('id');//$this->getDefault('category', $this->input->get('id'));
        $metricSubId = $this->input->get('subid'); //$this->getDefault('subcategory', $this->input->get('subid'));
        $deviceType  = $this->MISCommonLib->getDefault('device', $this->input->get('source'));
        $widgetId    = $this->input->get('widgetname') != '' && $this->input->get('widgetname') != '0' ? $this->input->get('widgetname'): 0;
        $viewType = $this->input->get('view');
        if($metricId == 14){
            $examId = $this->input->get('examId');
            $subExamId = $this->input->get('subExamId');
        }
        $sessionNameSelector = $this->input->get('trafficSource');



        $dates       = array(
            'startDate' => $this->getFormattedDate($startDate, Listings::$DATE_FORMAT),
            'endDate'   => $this->getFormattedDate($endDate, Listings::$DATE_FORMAT)
        );

        $compareDates = array();
        if($compareStartDate != '' && $compareEndDate != ''){
            $compareDates     = array(
                'startDate' => $this->getFormattedDate($compareStartDate, Listings::$DATE_FORMAT),
                'endDate'   => $this->getFormattedDate($compareEndDate, Listings::$DATE_FORMAT)
            );
        }

        $this->setDefaultCategorySubCategory($metricId, $metricSubId);

        $data['dimension'] = $this->getPageName($pageName);
        $pageName = $this->MISCommonLib->getPageNameForDomestic($pageName);

        $extraData = array(
            'category'    => $metricId,
            'subcategory' => $metricSubId,
            'pageName'    => $pageName,
            'view' => $viewType
        );
        if($metricId == 14){
            $extraData['examId'] = $examId;
            $extraData['subExamId'] = $subExamId;
        }

        if($widgetId != 0){
            $extraData['National']['widget'] = $widgetId;
        } // probably unused

        if ($deviceType != 'all') {
            $extraData['deviceType'] = $deviceType;
        }
        $sessionMapping = $this->Registration->getNationalRegistrationData_SourceWise($dates, $extraData);


        $sessionResult = $this->getResponseDataBasedOnSession($sessionMapping);
        $sessionsSplit = array();
        $sessionsSplit['session'] = $sessionResult['sourceWise'];
        if( ! empty($sessionResult['sourceSession']))
        {
            $sessionsSplit['splitForBarGraph'] = $this->getUTMWiseDataForBarGraph($sessionMapping,$sessionResult['sourceSession'],$sessionNameSelector);
        }
        echo json_encode($sessionsSplit);
    }
    // Code for registration MIS end


    // Code for traffic MIS start

    /**
     * Renders the basic structure of the traffic MIS
     */
    public function traffic(){
        $data                     = $this->_loadDependencies();
        $data['metricName']       = $this->input->get('dim');
        $data['pivotName']        = $this->input->get('pivot');
        $data['misSource']        = 'nationalListing';
        $data['teamName']         = 'Domestic';
        $data['colorCodes']       = $this->colorCodes;
        $data['leftMenuArray']    = $this->config->item("leftMenuArray");
        $responseConfig           = $this->config->item('traffic');
        $data['tileNames']        = $responseConfig['tileNames'];
        $data['splits']           = $responseConfig['splits'];
        $data['categories']       = $this->ListingsModel->getCategories();
        $data['splitInformation'] = array();

        $data['dates'] = array(
            'startDate' => $this->MISCommonLib->getDefault('startDate', ''),
            'endDate'   => $this->MISCommonLib->getDefault('endDate', '')
        );

        $data['view'] = $this->MISCommonLib->decideView($data['dates'], 1, 'db');

        $this->load->view('nationalListings/metrics', $data);
    }

    /**
     * Renders the top tiles for traffic MIS
     */
    public function trafficTiles(){
        $team = 'National';
        $this->obtainInput(strtolower($team));
        $dates = array(
            'startDate' => $this->inputRequest->startDate,
            'endDate' => $this->inputRequest->endDate,
        );

        $extraData = array(
            $team => array(
                'deviceType'  => $this->inputRequest->sourceApplication,
                'category'    => $this->inputRequest->category,
                'subcategory' => $this->inputRequest->subcategoryId,
                'view' => $this->inputRequest->viewType,
                'aspect' => $this->inputRequest->aspect
            )
        );

        $this->Traffic = $this->load->library('trackingMIS/trafficDataLib');
        $data['topTiles']   = $this->Traffic->getTrafficTiles($dates, $this->inputRequest->pageName, $extraData);


        $tileData = array(
            'topTilesData' => $this->Traffic->getTrafficTiles($dates, $this->inputRequest->pageName, $extraData)
        );

        if($this->inputRequest->compareStartDate != '' && $this->inputRequest->compareEndDate != ''){
            // overwrite the startDate and the endDate with the compareStartDate and the compareEndDate - we dont need the first two now
            $dates = array(
                'startDate' => $this->inputRequest->compareStartDate,
                'endDate' => $this->inputRequest->compareEndDate,
            );
            $tileData['compareTilesData'] = $this->Traffic->getTrafficTiles($dates, $this->inputRequest->pageName, $extraData);
        }

        echo json_encode(
            $tileData
        );
    }

    /**
     * Renders the line chart data for the traffic MIS
     */
    public function trafficTrends(){
        $team = 'National';
        $this->obtainInput(strtolower($team));
        $dates = array(
            'startDate' => $this->inputRequest->startDate,
            'endDate' => $this->inputRequest->endDate,
        );

        $extraData = array(
            $team => array(
                'deviceType'  => $this->inputRequest->sourceApplication,
                'category'    => $this->inputRequest->category,
                'subcategory' => $this->inputRequest->subcategoryId,
                'view' => $this->inputRequest->viewType,
                'pivot' => $this->inputRequest->pivotName
            )
        );

        if ($this->inputRequest->pivotName == 'pageview') { // Call the engagement -> pageview method to cater the pageview trend

            $this->Engagement = $this->load->library('trackingMIS/engagementLib');

            $data['resultsForGraph'] = $this->Engagement->getEngagementTrends($dates, $extraData, $this->inputRequest->pageName);

            if($this->inputRequest->compareStartDate != '' && $this->inputRequest->compareEndDate != ''){
                $dates = array(
                    'startDate' => $this->inputRequest->compareStartDate,
                    'endDate' => $this->inputRequest->compareEndDate,
                );
                $data['comparisonResultsForGraph'] = $this->Engagement->getEngagementTrends($dates, $extraData, $this->inputRequest->pageName);
            }

        } else { // Handle the users and sessions trend with traffic only

            $this->Traffic = $this->load->library('trackingMIS/trafficDataLib');

            $data['resultsForGraph'] = $this->Traffic->getTrafficTrends($dates, $extraData, $this->inputRequest->pageName);

            if ($this->inputRequest->compareStartDate != '' && $this->inputRequest->compareEndDate != '') {
                $dates                             = array(
                    'startDate' => $this->inputRequest->compareStartDate,
                    'endDate'   => $this->inputRequest->compareEndDate,
                );
                $data['comparisonResultsForGraph'] = $this->Traffic->getTrafficTrends($dates, $extraData, $this->inputRequest->pageName);
            }
        }
        echo json_encode($data);
    }

    /**
     * Renders the various donut chart data for the traffic MIS
     */
    public function trafficSplit(){

        $team = 'National';
        $this->obtainInput(strtolower($team));
        $dates = array(
            'startDate' => $this->inputRequest->startDate,
            'endDate' => $this->inputRequest->endDate,
        );

        $extraData = array(
            $team => array(
                'deviceType'  => $this->inputRequest->sourceApplication,
                'category'    => $this->inputRequest->category,
                'subcategory' => $this->inputRequest->subcategoryId,
                'view' => $this->inputRequest->viewType,
                'pivot' => $this->inputRequest->pivotName
            )
        );

        $aspect = $this->input->get('splitAspect');

        if ($this->inputRequest->pivotName == 'pageview') { // Can we reuse the engagementSplit Action here?
            $extraData[$team]['splitAspect'] = $this->inputRequest->splitAspect;

            $this->Engagement = $this->load->library('trackingMIS/engagementLib');

            $splitData = array(
                $this->inputRequest->splitAspect => $this->Engagement->getEngagementSplit($dates, $extraData, $this->inputRequest->pageName)
            );

            if($this->inputRequest->compareStartDate != '' && $this->inputRequest->compareEndDate != ''){
                $dates = array(
                    'startDate' => $this->inputRequest->compareStartDate,
                    'endDate' => $this->inputRequest->compareEndDate,
                );
                $splitData['compareSplitData'] = $this->Engagement->getEngagementSplit($dates, $extraData, $this->inputRequest->pageName);
            }

        } else {

            $this->Traffic = $this->load->library('trackingMIS/trafficDataLib');

            $splitData = array(
                $aspect => $this->Traffic->getTrafficSplit($dates, $extraData, $this->inputRequest->pageName, $aspect)
            );


            if ($this->inputRequest->compareStartDate != '' && $this->inputRequest->compareEndDate != '') {
                $dates                         = array(
                    'startDate' => $this->inputRequest->compareStartDate,
                    'endDate'   => $this->inputRequest->compareEndDate,
                );
                $splitData['compareSplitData'] = $this->Traffic->getTrafficSplit($dates, $extraData, $this->inputRequest->pageName, $aspect);
            }
        }


        echo json_encode(
            $splitData
        );

    }

    /**
     * Renders the data for the grid in traffic MIS
     */
    public function trafficTable(){
        $team = 'National';
        $this->obtainInput(strtolower($team));
        $dates = array(
            'startDate' => $this->inputRequest->startDate,
            'endDate' => $this->inputRequest->endDate,
        );

        $extraData = array(
            $team => array(
                'deviceType'  => $this->inputRequest->sourceApplication,
                'category'    => $this->inputRequest->category,
                'subcategory' => $this->inputRequest->subcategoryId,
                'view' => $this->inputRequest->viewType,
                'pivot' => $this->inputRequest->pivotName
            )
        );

        if($this->inputRequest->pivotName == 'pageview'){
            $extraData[$team]['splitAspect'] = $this->inputRequest->splitAspect;
            $this->Engagement = $this->load->library('trackingMIS/engagementLib');
            $tableData = $this->Engagement->getEngagementTable($dates, $extraData, $this->inputRequest->pageName);
        } else {
            $this->Traffic = $this->load->library('trackingMIS/trafficDataLib');
            $tableData = $this->Traffic->getTotalVisitForPage($dates, $extraData, $this->inputRequest->pageName);
        }


        echo json_encode(
            array(
                'resultsToShow' => $tableData
            )
        );
    }

    /**
     * Renders the data for the UTM splits for traffic MIS
     */
    public function trafficSessionSplit(){

        $team = 'National';
        $this->obtainInput(strtolower($team));
        $dates = array(
            'startDate' => $this->inputRequest->startDate,
            'endDate' => $this->inputRequest->endDate,
        );

        $extraData = array(
            $team => array(
                'deviceType'  => $this->inputRequest->sourceApplication,
                'category'    => $this->inputRequest->category,
                'subcategory' => $this->inputRequest->subcategoryId,
                'view' => $this->inputRequest->viewType,
                'pivot' => $this->inputRequest->pivotName
            )
        );

        $aspect = 'session';

        if ($this->inputRequest->pivotName == 'pageview') {
            $extraData[ $team ]['splitAspect'] = $aspect;
            $this->Engagement                  = $this->load->library('trackingMIS/engagementLib');
            $trafficSourceSplit                = $this->Engagement->getEngagementSplit($dates, $extraData, $this->inputRequest->pageName);
        } else {
            $this->Traffic      = $this->load->library('trackingMIS/trafficDataLib');
            $trafficSourceSplit = $this->Traffic->getTrafficSplit($dates, $extraData, $this->inputRequest->pageName, $aspect);
        }


        $barGraphOptions = array();

        $disallowedTrafficTypes = array(
            'other',
            'sourcemissing'
        );


        foreach($trafficSourceSplit as $oneSplit){
            if( !in_array(strtolower($oneSplit->PivotName), $disallowedTrafficTypes) ){
                $barGraphOptions[strtolower($oneSplit->PivotName)] = 0;
            }
        }
        $trafficSourcePriority = array(
            'paid'    => 1,
            'mailer'  => 0,
            'seo'     => 0,
            'social'  => 0,
            'direct'  => 0,
            'notsure' => 0,
            'sem'     => 0
        );

        $trafficSourceName = $this->input->get_post('trafficSource'); // as obtained from the input

        if ($trafficSourceName != '') {
            $trafficSourcePriority[$trafficSourceName] = 1;
        } else {
            $break = 0;
            foreach ($trafficSourcePriority as $key => $onePriority) {
                foreach ($barGraphOptions as $barGraphKey => $oneOption) {

                    $barGraphOptions[$barGraphKey] = 0;
                    if ($key == $barGraphKey) {

                        $trafficSourceName = $key;
                        $barGraphOptions[$key] = 1;
                        $break = 1;
                        break;
                    }
                }
                if($break == 1)
                    break;

            }
        }

        $utmSplits = array(
            'utmSource',
            'utmMedium',
            'utmCampaign'
        );

        $utmSplitResults = array();

        foreach($utmSplits as $oneSplit){
            if($this->inputRequest->pivotName == 'pageview'){
                $extraData[$team]['trafficSource'] = strtolower($trafficSourceName);
                $extraData[$team]['splitAspect'] = $oneSplit;
                $utmSplitResults[$oneSplit] = $this->Engagement->getEngagementSplit($dates, $extraData, $this->inputRequest->pageName);

            } else {
                $utmSplitResults[$oneSplit] = $this->Traffic->getTrafficSplit($dates, $extraData, $this->inputRequest->pageName, $oneSplit, strtolower($trafficSourceName));
                // Check if there is no value obtained from the datastore. In that case, pull the information from the already obtained trafficSourceSplit array and set it as the 100% value
                if(count($utmSplitResults[$oneSplit]) == 0){
                    foreach($trafficSourceSplit as $oneTrafficSourceSplit){
                        if(strcasecmp($oneTrafficSourceSplit->PivotName, $trafficSourceName) == 0){
                            $otherCount = new stdClass();
                            $otherCount->ScalarValue = $oneTrafficSourceSplit->ScalarValue;
                            $otherCount->Percentage = number_format(100, 2) ;
                            $otherCount->PivotName = 'Other';
                            $utmSplitResults[$oneSplit][] = $otherCount;
                            break;
                        }
                    }
                }
            }
        }

        $sessionsSplit['splitForBarGraph'] = $utmSplitResults;

        $trafficSources = array(
            'session' => $trafficSourceSplit,
            'splitForBarGraph' => array(
                'barGraphOptions' => $barGraphOptions,
                'splitInformation' => $utmSplitResults
            )
        );
        echo json_encode(
            $trafficSources
        );

    }
    // Code for traffic MIS end


    // Code for engagement MIS start
    public function engagement(){
    $data                     = $this->_loadDependencies();
    $data['metricName']       = $this->input->get('dim');
    $data['pivotName']        = $this->input->get('pivot');
    $data['misSource']        = 'nationalListing';
    $data['teamName']         = 'Domestic';
    $data['colorCodes']       = $this->colorCodes;
    $data['leftMenuArray']    = $this->config->item("leftMenuArray");
    $responseConfig           = $this->config->item('engagement');
    $data['tileNames']        = $responseConfig['tileNames'];
    $data['splits']           = $responseConfig['splits'];
    $data['categories']       = $this->ListingsModel->getCategories();
    $data['splitInformation'] = array();

    $data['dates'] = array(
        'startDate' => $this->MISCommonLib->getDefault('startDate', ''),
        'endDate'   => $this->MISCommonLib->getDefault('endDate', '')
    );

    $data['view'] = $this->MISCommonLib->decideView($data['dates'], 1, 'db');

    $this->load->view('nationalListings/metrics', $data);
}

    public function engagementTiles(){
        $team = 'National';
        $this->obtainInput(strtolower($team));
        $dates = array(
            'startDate' => $this->inputRequest->startDate,
            'endDate' => $this->inputRequest->endDate,
        );

        $extraData = array(
            $team => array(
                'deviceType'  => $this->inputRequest->sourceApplication,
                'category'    => $this->inputRequest->category,
                'subcategory' => $this->inputRequest->subcategoryId,
                'view' => $this->inputRequest->viewType,
                'pivot' => $this->inputRequest->pivotName
            )
        );

        $this->Engagement = $this->load->library('trackingMIS/engagementLib');

        $data['topTilesData'] = $this->Engagement->getEngagementTiles($dates, $this->inputRequest->pageName, $extraData);

        if($this->inputRequest->compareStartDate != '' && $this->inputRequest->compareEndDate != ''){
            $dates = array(
                'startDate' => $this->inputRequest->compareStartDate,
                'endDate' => $this->inputRequest->compareEndDate,
            );
            $data['compareTilesData'] = $this->Engagement->getEngagementTiles($dates, $this->inputRequest->pageName, $extraData);
        }

        echo json_encode( $data );
    }

    public function engagementTrends(){
        $team = 'National';
        $this->obtainInput(strtolower($team));
        $dates = array(
            'startDate' => $this->inputRequest->startDate,
            'endDate' => $this->inputRequest->endDate,
        );

        $extraData = array(
            $team => array(
                'deviceType'  => $this->inputRequest->sourceApplication,
                'category'    => $this->inputRequest->category,
                'subcategory' => $this->inputRequest->subcategoryId,
                'view' => $this->inputRequest->viewType,
                'pivot' => $this->inputRequest->pivotName
            )
        );

        $this->Engagement = $this->load->library('trackingMIS/engagementLib');

        $data['resultsForGraph'] = $this->Engagement->getEngagementTrends($dates, $extraData, $this->inputRequest->pageName);

        if($this->inputRequest->compareStartDate != '' && $this->inputRequest->compareEndDate != ''){
            $dates = array(
                'startDate' => $this->inputRequest->compareStartDate,
                'endDate' => $this->inputRequest->compareEndDate,
            );
            $data['comparisonResultsForGraph'] = $this->Engagement->getEngagementTrends($dates, $extraData, $this->inputRequest->pageName);
        }

        echo json_encode( $data );
    }

    public function engagementSplit(){
        $team = 'National';
        $this->obtainInput(strtolower($team));
        $dates = array(
            'startDate' => $this->inputRequest->startDate,
            'endDate' => $this->inputRequest->endDate,
        );

        $extraData = array(
            $team => array(
                'deviceType'  => $this->inputRequest->sourceApplication,
                'category'    => $this->inputRequest->category,
                'subcategory' => $this->inputRequest->subcategoryId,
                'view' => $this->inputRequest->viewType,
                'pivot' => $this->inputRequest->pivotName,
                'splitAspect' => $this->inputRequest->splitAspect
            )
        );

        $this->Engagement = $this->load->library('trackingMIS/engagementLib');

        $data = array(
            $this->inputRequest->splitAspect => $this->Engagement->getEngagementSplit($dates, $extraData, $this->inputRequest->pageName)
        );

        if($this->inputRequest->compareStartDate != '' && $this->inputRequest->compareEndDate != ''){
            $dates = array(
                'startDate' => $this->inputRequest->compareStartDate,
                'endDate' => $this->inputRequest->compareEndDate,
            );
            $data['compareSplitData'] = $this->Engagement->getEngagementSplit($dates, $extraData, $this->inputRequest->pageName);
        }

        echo json_encode( $data );
    }

    public function engagementSessionSplit(){

        $team = 'National';
        $this->obtainInput(strtolower($team));
        $dates = array(
            'startDate' => $this->inputRequest->startDate,
            'endDate' => $this->inputRequest->endDate,
        );

        $extraData = array(
            $team => array(
                'deviceType'  => $this->inputRequest->sourceApplication,
                'category'    => $this->inputRequest->category,
                'subcategory' => $this->inputRequest->subcategoryId,
                'view' => $this->inputRequest->viewType,
                'pivot' => $this->inputRequest->pivotName,
                'splitAspect' => 'session'
            )
        );

        $this->Engagement = $this->load->library('trackingMIS/engagementLib');

        $engagementSplit = $this->Engagement->getEngagementSplit($dates, $extraData, $this->inputRequest->pageName);

        $barGraphOptions = array();

        $disallowedTrafficTypes = array(
            'other',
            'sourcemissing'
        );


        foreach($engagementSplit as $oneSplit){
            if( !in_array(strtolower($oneSplit->PivotName), $disallowedTrafficTypes) ){
                $barGraphOptions[strtolower($oneSplit->PivotName)] = 0;
            }
        }
        $trafficSourcePriority = array(
            'paid'    => 1,
            'mailer'  => 0,
            'seo'     => 0,
            'social'  => 0,
            'direct'  => 0,
            'notsure' => 0,
            'sem'     => 0
        );

        $trafficSourceName = $this->input->get_post('trafficSource'); // as obtained from the input

        if ($trafficSourceName != '') {
            $trafficSourcePriority[$trafficSourceName] = 1;
        } else {
            $break = 0;
            foreach ($trafficSourcePriority as $key => $onePriority) {
                foreach ($barGraphOptions as $barGraphKey => $oneOption) {

                    $barGraphOptions[$barGraphKey] = 0;
                    if ($key == $barGraphKey) {

                        $trafficSourceName = $key;
                        $barGraphOptions[$key] = 1;
                        $break = 1;
                        break;
                    }
                }
                if($break == 1)
                    break;

            }
        }

        $utmSplits = array(
            'utmSource',
            'utmMedium',
            'utmCampaign'
        );

        $utmSplitResults = array();

        foreach($utmSplits as $oneSplit){
            $extraData['National']['trafficSource'] = strtolower($trafficSourceName);
            $extraData['National']['splitAspect'] = $oneSplit;
            $utmSplitResults[$oneSplit] = $this->Engagement->getEngagementSplit($dates, $extraData, $this->inputRequest->pageName);
        }
        $sessionsSplit['splitForBarGraph'] = $utmSplitResults;

        $engagementSessionSplit = array(
            'session' => $engagementSplit,
            'splitForBarGraph' => array(
                'barGraphOptions' => $barGraphOptions,
                'splitInformation' => $utmSplitResults
            )
        );
        echo json_encode(
            $engagementSessionSplit
        );

    }

    public function engagementTable(){
        $team = 'National';
        $this->obtainInput(strtolower($team));
        $dates = array(
            'startDate' => $this->inputRequest->startDate,
            'endDate' => $this->inputRequest->endDate,
        );

        $extraData = array(
            $team => array(
                'deviceType'  => $this->inputRequest->sourceApplication,
                'category'    => $this->inputRequest->category,
                'subcategory' => $this->inputRequest->subcategoryId,
                'view' => $this->inputRequest->viewType,
                'pivot' => $this->inputRequest->pivotName,
                'splitAspect' => $this->inputRequest->splitAspect
            )
        );

        $this->Engagement = $this->load->library('trackingMIS/engagementLib');

        $tableData = $this->Engagement->getEngagementTable($dates, $extraData, $this->inputRequest->pageName);

        foreach($tableData as $oneKey => $oneTableData){
            unset($tableData[$oneKey]->Percentage); // We do not need to show percentage stat in engagement
        }

        echo json_encode(
            array(
                'resultsToShow' => $tableData
            )
        );
    }

    // Code for engagement MIS end

    /**
     * Change date formats (This method just changes the string representation.)
     *
     * @param string $inputDate   The input date
     * @param string $inputFormat The format in which the date was input
     *
     * @return string A YYYY-MM-DD representation
     */
    private function getFormattedDate($inputDate, $inputFormat = 'MM/DD/YYYY')
    {
        switch ($inputFormat) {
            case 'MM/DD/YYYY':
                $replacementPattern = '$3-$2-$1';
                break;
            case 'DD/MM/YYYY':
                $replacementPattern = '$3-$1-$2';
                break;
            default:
                $replacementPattern = '$3-$2-$1';
                break;

        }

        return preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', $replacementPattern, $inputDate);
    }

    /**
     * Get subcategories corresponding to a category id as specified in the URL request.
     * If no category is specified, category = 3 is assumed.
     *
     */
    public function getSubCategories()
    {
        if (($categoryId = $this->input->get('id')) == '' || intval($categoryId) == 0) {
            $categoryId = 1;
        }
        $this->load->model('nationalListings/listings_model', 'ListingsModel');
        $subcategories = $this->ListingsModel->getSubCategories($categoryId);

        $allSubcategories = new stdClass();
        $allSubcategories->SubCategoryId = 0;
        $allSubcategories->SubCategoryName = 'All Subcategories';
        array_unshift($subcategories, $allSubcategories);

        echo json_encode($subcategories);
    }

    public function getMainExams()
    {
        if (($categoryId = $this->input->get('id')) == '' || intval($categoryId) == 0) {
            $categoryId = Listings::$MANAGEMENT;
        }

        //$this->_loadDependencies();
        $this->load->library(array('LDB_Client','category_list_client','MultipleMarketingPageClient'));
        $marketingPageClient = \MultipleMarketingPageClient::getInstance();
        $courses = json_decode($marketingPageClient->getTestPrepCoursesListForApage(1,0,'testpreppage','complete_list'),true);
        $courses = $courses['courses_list'];
        
        $examArray[0]['examId'] = 0;
        $examArray[0]['examName'] = 'All Exam';
        $i =1;
        foreach ($courses as $key => $value) {
            $examArray[$i]['examId'] = $key;
            $examArray[$i++]['examName'] = $value[0]['title'];
        }

        echo json_encode($examArray);
    }

    public function getMainSubExams(){
        if (($examId = $this->input->get('examId')) == '' || intval($examId) == 0) {
            $categoryId = 464;
        }

        $this->load->model('nationalListings/listings_model', 'ListingsModel');
        $subExams = $this->ListingsModel->getSubeExams($examId);
        $subExamsArray[] = array(
                                'subExamId' => 0,
                                'subExamName' => 'All Sub Exams'
                                );
        foreach ($subExams as $key => $value) {
            $subExamsArray[] = array(
                                'subExamId' => $value['subExamId'],
                                'subExamName' => $value['subExamName']
                                );
        }
        
    
        echo json_encode($subExamsArray);
    }

    /**
     * Get a guaranteed value for a variable identified by a type.
     *
     * @param string $type     The variable identifier
     * @param string $variable The variable itself
     *
     * @return string The default value if blank value is passed
     */
    private function getDefault($type, $variable)
    {
        switch ($type) {
            case 'startDate':
            case 'compareStartDate':
                if ($variable == '')
                    $variable = date('Y-m-d', strtotime('-30 days'));
                break;
            case 'endDate':
            case 'compareEndDate':
                if ($variable == '')
                    $variable = date('Y-m-d', strtotime('-1 day'));
                break;
            case 'pageName':
                if ($variable == '')
                    $variable = 'category';
                break;
            case 'category':
                if ($variable == '')
                    $variable = '3';
                break;
            case 'device':
            case 'type':
                if ($variable == '')
                    $variable = 'all';
                break;
            case 'pivot':
                if ($variable == 'pageview') {
                    $variable = 'pageView';
                } else if ($variable == 'avgsessdur') {
                    $variable = 'averageSessionDuration';
                } else if ($variable == 'pgpersess') {
                    $variable = 'pagesPerSession';
                }
        }

        return $variable;
    }

    /**
     * Set the default category and subcategory for the input <code>id</code> and <code>subid</code> values
     *
     * As on Feb 5, 2016 the following combinations can work:
     *
     * <table border=1>
     * <tr><td>Category Id</td><td>Not Specified</td><td>Not Specified</td><td>Specified</td><td>Specified</td></tr>
     * <tr><td>Subcategory Id</td><td>Not Specified</td><td>Specified</td><td>Not Specified</td><td>Specified</td></tr>
     * <tr><td>Result</td><td>id=0; subid=0</td><td>id=0; subid=0</td><td>id=X; subid=all_subcategories_for_X</td><td>id=X; subid=Y</td></tr>
     *
     * </table>
     *
     * @param string $category The category id as passed on in the input
     * @param string $subcategoryId The subcategory id as passed on in the input
     */
    private function setDefaultCategorySubCategory(&$category, &$subcategory)
    {
        $category = intval($category);
        $subcategory = intval($subcategory);

        /*if($category != 0){ // when category is specified
            if($subcategory == 0){
                $subcategoryIds = $this->ListingsModel->getSubCategories($category);

                $inArray = array();
                foreach($subcategoryIds as $oneSubcategory){
                    $inArray[] = intval($oneSubcategory->SubCategoryId);
                }

                $subcategory  = implode(",", $inArray);
            }
        } else { // when no category is specified
            $subcategory = 0; // since category is already 0
        }*/
    }

    private function getPageName($pageIdentifier){
        $pageNames = array(
            'all'                         => 'All',
            'home'                        => 'Home',
            'courselisting'               => 'Course Listing',
            'coursehome'                  => 'Course Home',
            'institute'                   => 'Institute Listing',
            'shortlist'                   => 'Shortlist',
            'category'                    => 'Category',
            'search'                      => 'Search',
            'exam'                        => 'Exam',
            'browse'                      => 'Browse',
            'top_search'                  => 'Top Search',
            'ranking'                     => 'Ranking',
            'college_review_home'         => 'College Reviews Home',
            'college_review_intermediate' => 'College Reviews Intermediate',
            'campus_rep_home'             => 'Campus Representative Home',
            'campus_rep_intermediate'     => 'Campus Representative Intermediate',
            'compare_colleges'            => 'Compare Colleges',
            'exam_calendar'               => 'Exam Calendar',
            'application_form'            => 'Application Form Home',
            'career_home'                 => 'Career Home',
            'career_detail'               => 'Career Detail',
            'career_counselling'          => 'Career Counselling',
            'career_opportunities'        => 'Career Opportunities',
            'career_compass'              => 'Career Compass',
            'article_home'                => 'Article Home',
            'article_detail'              => 'Article Detail',
            'qna'                         => 'Ask n Answer',
            'cafe_buzz'                   => 'Cafe Buzz',
            'rank_predictor'              => 'Rank Predictor',
            'college_predictor'           => 'College Predictor',
            'mentorship'                  => 'Mentorship Home',
            'question_details'            => 'Question Detail',
            'discussionDetail'            => 'Discussion Detail', // added by Nithish Reddy
            'iim_predictor'               => 'IIM Call Predictor'
        );

        return $pageNames[$pageIdentifier];
    }


    public function getResponseDataBasedOnSession($sessionResult)
    {
        $sessionId = array();
        $i = 0;
        $sessionResultArray = array();
        foreach ($sessionResult as $object) {
            $sessionId[$i++] = $object->PivotName;
            $sessionResultArray[$object->PivotName] = $object->ScalarValue;
        }
        $sourceWise = $this->getSourceWiseForDonutChart($sessionId,$sessionResultArray);
        //$utmSourceWise = $this->getUTMsourceWiseForDonutChart($sessionId,$sessionResultArray);
        $result['sourceWise'] = $sourceWise['sourceWise'];
        $result['sourceSession'] = $sourceWise['sourceSession'];
        return $result;
    }
    private function getSourceWiseForDonutChart($sessionId,$sessionResult)
    {
        $model = $this->ListingsModel;
        if( ! empty($sessionId))
        {
            $sourceResult =$model->getSourceForSessionId($sessionId);
        }
        $sourceSessionMapping = array();
        $sourceSessionArray = array();
        foreach ($sourceResult as $key => $value) {
            $sourceSessionMapping[$value['sessionId']] = $value['source'];
            if( ! empty($value['source']))
            {
                if( array_key_exists($value['source'], $sourceSessionArray))
                {
                    array_push($sourceSessionArray[$value['source']], $value['sessionId']);
                }
                else
                {
                    $sourceSessionArray[$value['source']] = array($value['sessionId']);    
                }
            }
            
        }
        $sourceWiseResult = array();
        $i = 0;
        foreach ($sessionResult as $key => $value) {
            if( empty($sourceSessionMapping[$key]))
                $sourceSessionMapping[$key] = 'other';
            $sourceWiseResult[ $sourceSessionMapping[ $key ] ] += $value;
        }
        arsort($sourceWiseResult); // Sort in descending order of counts (ScalarValue)
        foreach ($sourceWiseResult as $key => $value) {
            $sourceWiseSingleSplit = new stdClass();
            $sourceWiseSingleSplit->PivotName = $key;
            $sourceWiseSingleSplit->ScalarValue = $value;
            $resultArray[] = $sourceWiseSingleSplit;
        }
        $returnArray['sourceWise'] = $resultArray;
        $returnArray['sourceSession'] = $sourceSessionArray;
        return $returnArray;
    }
    function getUTMsourceWiseForDonutChart($sessionId,$sessionResult)
    {
        $model = $this->ListingsModel;
        $utmResult = array();
        if( ! empty($sessionId))
            $utmWiseResult = $model->getCampaignForSessionId($sessionId);
        $utmSessionMapping = array();
        foreach ($utmWiseResult as $key => $value) {
            $utmSessionMapping[$value['sessionId']] = $value['campaignName'];
        }
        foreach ($utmSessionMapping as $key => $value) {
            $utmResult[$value] += $sessionResult[$key];
        }
        $resultArray = array();
        foreach ($utmResult as $key => $value) {
            $utmWiseSplit = new stdClass();
            $utmWiseSplit->PivotName = $key;
            $utmWiseSplit->ScalarValue = $value;
            $resultArray[]= $utmWiseSplit;
        }
        return $resultArray;
        
    }
    function getUTMWiseDataForBarGraph($sessionResult,$sourceSession,$inputRequestFilter = '')
    {
        $sessionResultArray = array();
        $sourceHeading = array();
        $trafficSourcePriority =  array('paid','mailer','seo','social','direct','notsure','sem');
        $minFlag = 8;
        foreach ($sourceSession as $key => $value) {
            $sourceHeading[$key] = 0;
            $expectedFlag = array_search($key, $trafficSourcePriority);
            $minFlag = $minFlag > $expectedFlag ? $expectedFlag : $minFlag;
        }
        foreach ($sessionResult as $object) {
            $sessionResultArray[$object->PivotName] = $object->ScalarValue;
        }
        if( empty($inputRequestFilter))
        {
            $sourceVaraible = $sourceSession[$trafficSourcePriority[$minFlag]];
            $sourceHeading[$trafficSourcePriority[$minFlag]] = 1;
            $inputRequestFilter = $trafficSourcePriority[$minFlag];
        }
        else
        {
            $sourceVaraible = $sourceSession[$inputRequestFilter];
        }
        $sourceWiseResult = $this->trackingLib->getUTMWiseData($sourceVaraible,$inputRequestFilter);
        $splitForBarGraph['splitInformation']['utm Source'] = $this->getCountPerUTMWise($sessionResultArray,$sourceWiseResult['sourceWise']);
        $splitForBarGraph['splitInformation']['utm Medium'] = $this->getCountPerUTMWise($sessionResultArray,$sourceWiseResult['mediumWise']);
        $splitForBarGraph['splitInformation']['utm Campaign'] = $this->getCountPerUTMWise($sessionResultArray,$sourceWiseResult['campaignWise'],2);
        $splitForBarGraph['barGraphOptions'] = $sourceHeading;
        return $splitForBarGraph;
    }
    function getCountPerUTMWise($sessionResult,$pivotSplit)
    {
        $utmSessionMapping = array();
        $utmResult = array();
        foreach ($pivotSplit as $key => $value) {
            if( empty($value['PivotName']))
                $value['PivotName'] = 'other';
            $utmSessionMapping[$value['sessionId']] =  $value['PivotName'];
        }
        $totalResponses = 0;
        foreach ($utmSessionMapping as $key => $value) {
            $utmResult[$value] += $sessionResult[$key];
            $totalResponses += $sessionResult[$key];
        }
        arsort($utmResult); // Sort the data in the descending order of counts
        $resultArray = array();
        foreach ($utmResult as $key => $value) {
            $utmWiseSplit = new stdClass();
            $utmWiseSplit->ScalarValue = $value;
            $utmWiseSplit->PivotName = htmlentities($key);
            $utmWiseSplit->Percentage = number_format(($value/$totalResponses) * 100,2,'.','');
            $resultArray[]= $utmWiseSplit;
        }

        return $resultArray;
    }   
    function getUpdateSourceBarGraphData()
    {
        $getSource           = $this->input->get('source');
        $metricName       = $this->input->get('dim');
        $metricId         = $this->input->get('id');
        $metricSubId      = $this->input->get('subid');
        $pivotName        = $this->input->get('pivot');
        $getPivotType        = $this->input->get('type');
        $getWidgetId         = $this->input->get('widgetname');
        $getStartDate        = $this->input->get('startdate');
        $getEndDate          = $this->input->get('enddate');
        $getCompareStartDate = $this->input->get('comparestartdate');
        $getCompareEndDate   = $this->input->get('compareenddate');
        $trafficSource       = $this->input->get('trafficSource');
        $dateFormat          = Listings::$DATE_FORMAT;

        $source      = isset($getSource) && $getSource != '' ? $getSource : 'all';

        $pivotType = isset($getPivotType) && $getPivotType != '' ? $getPivotType : 'all';
        $widgetId  = !isset($getWidgetId) ? 0 : intval($getWidgetId);
        $startDate = isset($getStartDate) && $getStartDate != '' ? $this->getFormattedDate($getStartDate, $dateFormat) : date('Y-m-d', strtotime('-30 days')); // 30 days interval by default
        $endDate   = isset($getEndDate) && $getEndDate != '' ? $this->getFormattedDate($getEndDate, $dateFormat) : date('Y-m-d'); // Today's date in Y-m-d format by default
        $dates     = array(
            'startDate' => $startDate,
            'endDate'   => $endDate
        );

        $this->load->model('nationalListings/listings_model', 'ListingsModel');
        $this->setDefaultCategorySubCategory($metricId, $metricSubId);

        $splitData = array(
            'source'      => $source, // device type
            'metricName'  => $metricName, // page name
            'pivotType'   => $pivotType, // response type
            'widgetId'    => $widgetId, // widget id
            'metricId'    => $metricId, // category id
            'metricSubId' => $metricSubId, // subcategory id
            'startDate'   => $startDate,
            'endDate'     => $endDate
        );
        $splitInformation = $this->getSplitData($splitData,$trafficSource);
        unset($splitInformation['splitInformation']);
        echo json_encode($splitInformation['splitForBarGraph']);
    }
    function getTrafficDataForBarGraphByUTMwise($dates = array(), $extraData = array(), $pageName = array(),$sourceFromTraffic = array(),$inputRequestFilter = '')
    {
        $sourceHeading = array();
        $trafficSourcePriority =  array('paid','mailer','seo','social','direct','notsure');
        $minFlag = 8;
        foreach ($sourceFromTraffic as $key => $value) {
            $sourceHeading[$value] = 0;
            $expectedFlag = array_search($value, $trafficSourcePriority);
            $minFlag = $minFlag > $expectedFlag ? $expectedFlag : $minFlag;
        }
        if( empty($inputRequestFilter))
        {
            $sourceVaraible = $sourceSession[$trafficSourcePriority[$minFlag]];
            $sourceHeading[$trafficSourcePriority[$minFlag]] = 1;
            $inputRequestFilter = $trafficSourcePriority[$minFlag];
            $sourceFilter  = $trafficSourcePriority[$minFlag];
        }
        else
        {
            $sourceFilter = $inputRequestFilter;
        }
        $splitInformation = $this->trackingLib->getTrafficDataUTMWise($dates,$extraData,$pageName,$sourceFilter);
        $splitForBarGraph['splitInformation']['utm Source'] = $splitInformation[0];
        $splitForBarGraph['splitInformation']['utm Medium'] = $splitInformation[1];
        $splitForBarGraph['splitInformation']['utm Campaign'] = $splitInformation[2];
        $splitForBarGraph['barGraphOptions'] = $sourceHeading;
        return $splitForBarGraph;
    }

    // Probably unused - there is a substitute Listings::trafficSessionSplit
    // TODO Delete
    function getUpdateBarGraphDataForTraffic()
    {
        $startDate        = $this->getDefault('startDate', $this->input->get('startdate'));
        $endDate          = $this->getDefault('endDate', $this->input->get('enddate'));
        $compareStartDate = $this->input->get('comparestartdate');
        $compareEndDate   = $this->input->get('compareenddate');
        $pageName         = $this->getDefault('pageName', $this->input->get('dim'));
        $metricId         = $this->input->get('id');
        $metricSubId      = $this->input->get('subid');
        $deviceType       = $this->getDefault('device', $this->input->get('source'));
        $trafficSource    = $this->input->get('trafficSource');
        $this->setDefaultCategorySubCategory($metricId, $metricSubId);

        $extraData = array(
            'National' => array(
                'deviceType'  => $deviceType,
                'category'    => $metricId,
                'subcategory' => $metricSubId,
            )
        );
        $dates     = array(
            'startDate' => $this->getFormattedDate($startDate, Listings::$DATE_FORMAT),
            'endDate'   => $this->getFormattedDate($endDate, Listings::$DATE_FORMAT)
        );
        $splitForBarGraph = $this->getTrafficDataForBarGraphByUTMwise($dates,$extraData,$pageName,'',$trafficSource);
        echo json_encode($splitForBarGraph);
    }
    function getTrafficSourceWiseForRegistration()
    {
        //
        $this->_loadDependencies();
        $this->Registration = $this->load->library('trackingMIS/cdMISlib');
        $this->RegistrationModel = $this->load->model('trackingMIS/cdmismodel');

        $startDate   = $this->getDefault('startDate', $this->input->get('startdate'));
        $endDate     = $this->getDefault('endDate', $this->input->get('enddate'));
        $compareStartDate   = $this->input->get('comparestartdate');
        $compareEndDate     = $this->input->get('compareenddate');
        $pageName    = $this->getDefault('pageName', $this->input->get('dim'));
        $metricId    = $this->input->get('id');//$this->getDefault('category', $this->input->get('id'));
        $metricSubId = $this->input->get('subid'); //$this->getDefault('subcategory', $this->input->get('subid'));
        $deviceType  = $this->getDefault('device', $this->input->get('source'));
        $widgetId    = $this->input->get('widgetname') != '' && $this->input->get('widgetname') != '0' ? $this->input->get('widgetname'): 0;
        $trafficSource    = $this->input->get('trafficSource');
        $dates       = array(
            'startDate' => $this->getFormattedDate($startDate, Listings::$DATE_FORMAT),
            'endDate'   => $this->getFormattedDate($endDate, Listings::$DATE_FORMAT)
        );

        $this->setDefaultCategorySubCategory($metricId, $metricSubId);

        $data['metricName']      = $pageName;
        $data['dimension'] = $this->getPageName($pageName);


        $pageName = $this->MISCommonLib->getPageNameForDomestic($pageName);

        $extraData = array(
                'category'    => $metricId,
                'subcategory' => $metricSubId,
                'pageName'    => $pageName
        );

        if($widgetId != 0){
            $extraData['widget'] = $widgetId;
        }

        if ($deviceType != 'all') {
            $extraData['deviceType'] = $deviceType;
        }
        $dates = array('startDate' => '2016-01-01','endDate'=>'2016-03-10');
        $extraData = array(
            'category' => 0,
            'subcategory' => 0,
            'pageName' => 'all'
        );

        $sessionResult = $this->RegistrationModel->getNationalRegistrationData_SourceWise($dates,$extraData);
        $sessionSourceMapping = $this->getResponseDataBasedOnSession($sessionResult);
        if( ! empty($trafficSource))
            $result['splitInformation']['response Source'] = $sessionSourceMapping['sourceWise'];
        if( ! empty($sessionSourceMapping['sourceSession']))
        {   $trafficSource = 'seo';
            $result['splitForBarGraph'] = $this->getUTMWiseDataForBarGraph($sessionResult,$sessionSourceMapping['sourceSession'],$trafficSource);
        }
        _p($result);
        die;
    }

}
