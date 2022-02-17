<?php


class Common extends MX_Controller
{
    function __construct(){
        parent::__construct();
        //$this->trackingLib = $this->load->library('trackingMIS/trackingMISCommonLib');
        $this->MISCommonLib = $this->load->library('trackingMIS/MISCommonLib');
        $this->trackingLib = $this->load->library('trackingMIS/trackingMISCommonLib');
    }

    /*function getDataForUTMTraffic(){
        $dateRange = $this->input->post('dateRange');
        $sourceApplication = $this->input->post('sourceApplication');
        $utmCurrentFilter = $this->input->post('utmCurrentFilter');
        $teamName = $this->input->post('teamName');
        echo json_encode($this->MISCommonLib->getDataForUTMTraffic($dateRange,$sourceApplication,$utmCurrentFilter,$teamName));
    }*/

    function getDataForTrafficSourceFilterForRegistration(){
        $dateRange = $this->input->post('dateRange');
        $sourceApplication = $this->input->post('sourceApplication');
        $defaultView = $this->input->post('defaultView');
        $count = $this->input->post('count');
        //_p($dateRange);_p($sourceApplication);_p($defaultView);_p($filters);_p($source);die;
        //_p($sourceApplication);die;

        //getTrafficSourceDataForOverviewRegistration($dateRange,$sourceApplication,$sourceFlag=0,$count='',$defaultView='')
        $result = $this->trackingLib->getTrafficSourceDataForOverviewRegistration($dateRange,$sourceApplication,1,$count,$defaultView);
        echo json_encode($result);
    }

    function getDataForTrafficSourceFilter(){
        $dateRange = $this->input->get_post('dateRange');
        $sourceApplication = $this->input->get_post('sourceApplication');

        $defaultView = $this->input->get_post('defaultView');
        $trafficSource = $this->input->get_post('trafficSource');
        if($trafficSource!=''){
            $defaultView = $trafficSource;
        }
        $filters = $this->input->get_post('filters');
        $source = $this->input->get_post('source');
        //_p($dateRange);_p($sourceApplication);_p($defaultView);_p($filters);_p($source);die;
        //_p($sourceApplication);die;
        echo json_encode($this->MISCommonLib->prepareDataForBarGraphForTrafficSource($dateRange,$sourceApplication,$defaultView,$filters,$source));
    }


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
        $this->inputRequest->sourceApplication = $this->MISCommonLib->getDefault('device', $this->input->get('source'));


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

    public function leadsTiles($team='global'){
        $this->obtainInput($team);

        $this->load->model('trackingMIS/cdmismodel', 'Leads');

        $leadsTiles = $this->Leads->leadsTiles($this->inputRequest, $team);

        $leadsTiles = array(
            'topTilesData' => $leadsTiles
        );

        echo json_encode($leadsTiles);

    }

    public function leadsTrend($team = 'global'){

/*
        1) get input
        2) sanitize input
        3) process filters
        4) query data store
        5) spit out json
*/
        $this->obtainInput($team);

        $this->load->model('trackingMIS/cdmismodel', 'Leads');

        $leadsTrend = $this->Leads->leadsTrend($this->inputRequest, $team);

        $leadsTrend = array(
            'resultsForGraph' => $leadsTrend
        );

        echo json_encode($leadsTrend);
    }

    public function leadsSplit($team = 'global'){
        $this->obtainInput($team);

        $this->load->model('trackingMIS/cdmismodel', 'Leads');

        $leadsSplit = $this->Leads->leadsSplit($this->inputRequest, $team);

        $leadsSplit = array(
            $this->inputRequest->splitAspect => $leadsSplit
        );

        echo json_encode($leadsSplit);

    }

    public function sessionsSplit($team='global'){
        $this->obtainInput($team);

        $this->inputRequest->splitAspect = 'leadsTrafficSource';
        $getTrafficSourceFromUrl = explode("&", $team);
        if( count($getTrafficSourceFromUrl) > 1 ){
            $team = $getTrafficSourceFromUrl[0];
            $keyValue = explode("=", $getTrafficSourceFromUrl[1]);
            $this->inputRequest->trafficSource = $keyValue[1];
        }

        $this->load->model('trackingMIS/cdmismodel', 'Leads');
        $sessions = $this->Leads->leadsSplit($this->inputRequest, $team);

        $sessionResult = $this->getResponseDataBasedOnSession($sessions);
        $returnValue = array(
            $this->inputRequest->splitAspect => $sessionResult['sourceWise']
        );
        if( ! empty($sessionResult['sourceSession']))
        {
            $returnValue['splitForBarGraph'] = $this->getUTMWiseDataForBarGraph($sessions,$sessionResult['sourceSession']);
        }
        echo json_encode($returnValue);
    }


    private function getResponseDataBasedOnSession($sessionResult)
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
        $model = $this->load->model('trackingMIS/nationalListings/listings_model');
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
                $sourceSessionMapping[$key] = 'undefined';
            $sourceWiseResult[ $sourceSessionMapping[ $key ] ] += $value;
        }
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

    private function getUTMWiseDataForBarGraph($sessionResult,$sourceSession)
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
        if( empty($this->inputRequest->trafficSource))
        {
            $sourceVariable = $sourceSession[$trafficSourcePriority[$minFlag]];
            $sourceHeading[$trafficSourcePriority[$minFlag]] = 1;
            $this->inputRequest->trafficSource = $trafficSourcePriority[$minFlag];
        }
        else {
            $sourceVariable = $sourceSession[ $this->inputRequest->trafficSource ];
        }
        $sourceWiseResult = $this->trackingLib->getUTMWiseData($sourceVariable,$this->inputRequest->trafficSource);
        $splitForBarGraph['splitInformation']['utm Source'] = $this->getCountPerUTMWise($sessionResultArray,$sourceWiseResult['sourceWise']);
        $splitForBarGraph['splitInformation']['utm Medium'] = $this->getCountPerUTMWise($sessionResultArray,$sourceWiseResult['mediumWise']);
        $splitForBarGraph['splitInformation']['utm Campaign'] = $this->getCountPerUTMWise($sessionResultArray,$sourceWiseResult['campaignWise'],2);
        $splitForBarGraph['barGraphOptions'] = $sourceHeading;
        return $splitForBarGraph;
    }

    private function getCountPerUTMWise($sessionResult,$pivotSplit)
    {
        $utmSessionMapping = array();
        $utmResult = array();
        foreach ($pivotSplit as $key => $value) {
            if( empty($value['PivotName']))
                $value['PivotName'] = 'undefined';
            $utmSessionMapping[$value['sessionId']] =  $value['PivotName'];
        }
        $totalResponses = 0;
        foreach ($utmSessionMapping as $key => $value) {
            $utmResult[$value] += $sessionResult[$key];
            $totalResponses += $sessionResult[$key];
        }
        $resultArray = array();
        foreach ($utmResult as $key => $value) {
            $utmWiseSplit = new stdClass();
            $utmWiseSplit->PivotName = htmlentities($key);
            $utmWiseSplit->ScalarValue = $value;
            $utmWiseSplit->Percentage = number_format(($value/$totalResponses) * 100,2,'.','');
            $resultArray[]= $utmWiseSplit;
        }
        return $resultArray;
    }


    /**
     * Generate the top tile data for responses
     *
     * @param string $team
     */
    public function responseTiles($team = 'global'){
        ini_set('memory_limit', '256M');
        $this->obtainInput($team);

        $tileData = array(
            'topTilesData' => $this->MISCommonLib->responseTiles($this->inputRequest, $team)
        );

        if($this->inputRequest->compareStartDate != '' && $this->inputRequest->compareEndDate != ''){
            // overwrite the startDate and the endDate with the compareStartDate and the compareEndDate - we dont need the first two now
            $this->inputRequest->startDate = $this->inputRequest->compareStartDate;
            $this->inputRequest->endDate = $this->inputRequest->compareEndDate;
            $tileData['compareTilesData'] = $this->MISCommonLib->responseTiles($this->inputRequest, $team);
        }

        echo json_encode(
            $tileData
        );


    }


    /**
     * Obtain the data for the line chart
     *
     * @param string $team
     */
    public function responseTrends($team = 'global'){

        $this->obtainInput($team);

        $responseTrends = $this->MISCommonLib->responseTrends($this->inputRequest, $team);
        $dates = array(
            'startDate' => $this->inputRequest->startDate,
            'endDate' => $this->inputRequest->endDate,
        );

        $responseTrends = array(
            'resultsForGraph' => count($responseTrends) > 0 ? $this->MISCommonLib->insertZeroValues($responseTrends, $dates, $this->inputRequest->viewType) : array()
        );

        if($this->inputRequest->compareStartDate != '' && $this->inputRequest->compareEndDate != ''){
            $dates = array(
                'startDate' => $this->inputRequest->compareStartDate,
                'endDate' => $this->inputRequest->compareEndDate
            );
            // overwrite the startDate and the endDate with the compareStartDate and the compareEndDate - we dont need the first two now
            $this->inputRequest->startDate = $this->inputRequest->compareStartDate;
            $this->inputRequest->endDate = $this->inputRequest->compareEndDate;

            $comparisonTrends = $this->MISCommonLib->responseTrends($this->inputRequest, $team);
            $responseTrends['comparisonResultsForGraph'] = count($comparisonTrends) ? $this->MISCommonLib->insertZeroValues($comparisonTrends, $dates, $this->inputRequest->viewType) : array();
        }

        echo json_encode($responseTrends);
    }

    public function responseSplit($team = 'global'){
        $this->obtainInput($team);
        $splitInformation = $this->MISCommonLib->responseSplit($this->inputRequest, $team);

        $splitData = array(
            $this->inputRequest->splitAspect => $splitInformation
        );

        if($this->inputRequest->compareStartDate != '' && $this->inputRequest->compareEndDate != ''){
            // overwrite the startDate and the endDate with the compareStartDate and the compareEndDate - we dont need the first two now
            $this->inputRequest->startDate = $this->inputRequest->compareStartDate;
            $this->inputRequest->endDate = $this->inputRequest->compareEndDate;

            $splitData['compareSplitData'] = $this->MISCommonLib->responseSplit($this->inputRequest, $team);
        }

        echo json_encode(
            $splitData
        );


    }

    public function responseSessionSplit($team='global'){
        $this->obtainInput($team);
        $trafficSourceSplit = $this->MISCommonLib->responseSplit($this->inputRequest, $team);

        $barGraphOptions = array();

        $disallowedTrafficTypes = array(
            'other',
            'sourcemissing'
        );


        foreach($trafficSourceSplit as $oneSplit){
            if( !in_array(strtolower($oneSplit->PivotName), $disallowedTrafficTypes) ){
                $barGraphOptions[$oneSplit->PivotName] = 0;
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
            $this->inputRequest->sessionTypeSelector = $trafficSourceName;
            $trafficSourcePriority[$trafficSourceName] = 1;
        } else {
            $break = 0;
            foreach ($trafficSourcePriority as $key => $onePriority) {
                foreach ($barGraphOptions as $barGraphKey => $oneOption) {
                    $barGraphOptions[$barGraphKey] = 0;
                    if ($key == $barGraphKey) {
                        $this->inputRequest->sessionTypeSelector = $key;
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
            $this->inputRequest->splitAspect = $oneSplit;


            $utmSplitResults[$this->inputRequest->splitAspect] = $this->MISCommonLib->responseSplit($this->inputRequest, $team);
        }

        $sessionsSplit['splitForBarGraph'] = $utmSplitResults;

        $responsesSessionSplit = array(
            'session' => $trafficSourceSplit,
            'splitForBarGraph' => array(
                'barGraphOptions' => $barGraphOptions,
                'splitInformation' => $utmSplitResults
            )
        );
        echo json_encode(
            $responsesSessionSplit
        );
    }

    public function responseTable($team = 'global'){

        $this->obtainInput($team);
        $tableData = $this->MISCommonLib->responseTable($this->inputRequest, $team);
        echo json_encode(
            array(
                'resultsToShow' => $tableData
            )
        );


    }



}