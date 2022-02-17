<?php

/**
 * Class Overview
 *
 * This class handles the overview for the Shiksha / Domestic / Abroad domains
 */
class Overview extends MX_Controller
{
    private static $TOP_N;
    private static $TYPE;
    private $data;

    function __construct(){
        parent::__construct();
        Overview::$TOP_N = 10;
        Overview::$TYPE = 'top';
        $this->data = array();
        ini_set("memory_limit", "356M");

        $this->trackingLib = $this->load->library('trackingMIS/trackingMISCommonLib');
        $this->data['userDataArray'] = reset($this->trackingLib->checkValidUser());
        $this->data['misSource']     = "nationalListing";
        $this->data['actionName']    = $this->router->fetch_method();
        $this->load->config('listingsTrackingMISConfig');
        $this->data['leftMenuArray'] = $this->config->item("leftMenuArray");
        $this->load->model('overview_model', 'OverviewModel');

        $this->load->builder('listing/ListingBuilder');
        $listingBuilder = new ListingBuilder();
        $this->instituteRepo = $listingBuilder->getInstituteRepository();
        $this->universityRepo = $listingBuilder->getUniversityRepository();

        $this->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();

        $this->load->builder('LocationBuilder','location');
        $locationBuilder    = new LocationBuilder();
        $this->locationRepository = $locationBuilder->getLocationRepository();

        //$this->trackingMISCommonLib = $this->load->library('trackingMIS/trackingMISCommonLib');

        $this->MISCacheLib    = $this->load->library('cache/MISCache');    
        $this->MISCommonLib = $this->load->library('trackingMIS/MISCommonLib');
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
    }


    /**
     * Get some top entities with respect to registrations
     *
     * @param string $source The team name (global or shiksha / domestic / abroad)
     * @param string $sourceApplication Desktop / Mobile
     * @param string $dateRange The start and the end dates
     * @param string $showGrowth If MOM and YOY growth is to be shown
     */
    public function registration($source,$sourceApplication='',$dateRange='', $showGrowth='false'){
        $count = Overview::$TOP_N;
        $type = 'registration';
        $team = $source;
        //$team = 'domestic';
        $deviceType = $sourceApplication;

        /*$dateRange = array(
            'startDate' => '2014-11-21',
            'endDate' => '2015-12-20',
        );*/

		$registration['topPages']         = $this->getPages($count, $type, $team, $deviceType, $dateRange);
        $registration['topCategories']    = $this->getCategories($count, $type, $team, $deviceType, $dateRange);
        $registration['topSubcategories'] = $this->getSubcategories($count, $type, $team, $deviceType, $dateRange);
        $registration['topCities']        = $this->getCities($count, $type, $team, $deviceType, $dateRange);

        $showGrowth = $this->input->get('growth');
        if ($showGrowth == 'true') {
            // Top Pages delta
            $topPagesDelta = $this->getDelta($count, 'topPages', 'registration', $registration['topPages'], $team, $deviceType, $dateRange);
            foreach ($registration['topPages'] as $key => $onePage) {
                foreach ($topPagesDelta['mom'] as $oneDelta) {
                    if ($oneDelta['PageName'] == $onePage['PageName']) {
                        $registration['topPages'][ $key ]['changeMOM'] = $onePage['doc_count'] - $oneDelta['doc_count'];
                    }
                }

                foreach ($topPagesDelta['yoy'] as $oneDelta) {
                    if ($oneDelta['PageName'] == $onePage['PageName']) {
                        $registration['topPages'][ $key ]['changeYOY'] = $onePage['doc_count'] - $oneDelta['doc_count'];
                    }
                }
            }

            // Top categories delta
            $topCategoriesDelta = $this->getDelta($count, 'topCategories', 'registration', $registration['topCategories'], $team, $deviceType, $dateRange);
            foreach ($registration['topCategories'] as $key => $onePage) {
                foreach ($topCategoriesDelta['mom'] as $oneDelta) {
                    if ($oneDelta->CategoryId == $onePage->CategoryId) {
                        $registration['topCategories'][ $key ]->changeMOM = $onePage->ScalarValue - $oneDelta->ScalarValue;
                    }
                }

                foreach ($topCategoriesDelta['yoy'] as $oneDelta) {
                    if ($oneDelta->CategoryId == $onePage->CategoryId) {
                        $registration['topCategories'][ $key ]->changeYOY = $onePage->ScalarValue - $oneDelta->ScalarValue;
                    }
                }
            }

            // Top Subcategories delta
            $topSubCategoriesDelta = $this->getDelta($count, 'topSubcategories', 'registration', $registration['topSubcategories'], $team, $deviceType, $dateRange);
            foreach ($registration['topSubcategories'] as $key => $onePage) {
                foreach ($topSubCategoriesDelta['mom'] as $oneDelta) {
                    if ($oneDelta->SubCategoryId == $onePage->SubCategoryId) {
                        $registration['topSubcategories'][ $key ]->changeMOM = $onePage->ScalarValue - $oneDelta->ScalarValue;;
                    }
                }

                foreach ($topSubCategoriesDelta['yoy'] as $oneDelta) {
                    if ($oneDelta->SubCategoryId == $onePage->SubCategoryId) {
                        $registration['topSubcategories'][ $key ]->changeYOY = $onePage->ScalarValue - $oneDelta->ScalarValue;;
                    }
                }
            }

            // Top Cities delta
            $topCitiesDelta = $this->getDelta($count, 'topCities', 'registration', $registration['topCities'], $team, $deviceType, $dateRange);
            foreach ($registration['topCities'] as $key => $onePage) {
                foreach ($topCitiesDelta['mom'] as $oneDelta) {
                    if ($oneDelta->SubCategoryId == $onePage->SubCategoryId) {
                        $registration['topCities'][ $key ]->changeMOM = $onePage->ScalarValue - $oneDelta->ScalarValue;;
                    }
                }

                foreach ($topCitiesDelta['yoy'] as $oneDelta) {
                    if ($oneDelta->SubCategoryId == $onePage->SubCategoryId) {
                        $registration['topCities'][ $key ]->changeYOY = $onePage->ScalarValue - $oneDelta->ScalarValue;;
                    }
                }
            }

            _p($registration); die;
        }
    }

    // Unused. Can be deleted
    private function getWidgets($count, $type, $team, $deviceType, $dateRange)
    {
        return $this->OverviewModel->getWidgets($count, $type, $team, $deviceType, $dateRange);
    }

    /**
     * Get the traffic
     *
     * @param        $source
     * @param string $sourceApplication
     * @param string $dateRange
     *
     * @return mixed
     */

    public function trafficSplit($team=''){
        $team = $this->getDefault('team', $team);
        $deviceType = $this->getDefault('device', $this->input->get('device'));

        $dateRange = array(
            'startDate' => $this->getDefault('startDate', $this->input->get('startDate')),
            'endDate' => $this->getDefault('endDate', $this->input->get('endDate')),
        );

        $dateRange['startDate'] = preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', '$3-$1-$2', $dateRange['startDate']);
        $dateRange['endDate'] = preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', '$3-$1-$2', $dateRange['endDate']);

        echo json_encode($this->getTrafficSplitData($team, $deviceType, $dateRange));
    }

	private function getTrafficSplitData($team='', $deviceType, $dateRange){
        return $this->OverviewModel->getTrafficSplit($team, $deviceType, $dateRange);
    }

    //Unused. Can be deleted
    private function registrationSplit($team='', $deviceType, $dateRange){
        $this->OverviewModel->getRegistrationSplit($team, $deviceType, $dateRange);
    }

    // Used no more. Needs to be deleted
    public function getResponses($source='',$sourceApplication='',$dateRange){
        $dateRange = array(
            'startDate' => $this->getDefault('startDate', $this->input->get('startDate')),
            'endDate' => $this->getDefault('endDate', $this->input->get('endDate')),
        );

        $dateRange['startDate'] = preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', '$3-$1-$2', $dateRange['startDate']);
        $dateRange['endDate'] = preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', '$3-$1-$2', $dateRange['endDate']);
        $deviceType = $this->getDefault('device', $this->input->get('device'));
        if($deviceType == 'all'){
            $deviceType ='';
        }
        $responseData = $this->OverviewModel->getResponsesData($source,$deviceType,$dateRange);
        //_p($responseData);die;
        $responses = array();
        $registrations = array();
        $totalResponses = 0;
        $totalRegistration = 0;
        foreach ($responseData as $key => $value) {
            $responses['responseType']['paidResponses'] += $value['paidResponses'];
            $responses['responseType']['freeResponses'] += $value['freeResponses'];
            if($source != 'national'){
                if($value['paidRMC']){
                    $responses['rmcResponseType']['paidRMC']+= $value['paidRMC'];    
                }
                if($value['freeRMC']){
                    $responses['rmcResponseType']['freeRMC']+= $value['freeRMC'];
                }
                
            }
            $temp = $value['paidResponses']+ $value['freeResponses'];
            
            $responses['sourceApplication'][$value['sourceApplication']] += $temp;
            $responses['trafficSource'][$value['trafficSource']] += $temp;

            //$registrations['sourceApplication'][$value['sourceApplication']] += $value['registrations'];
            //$registrations['trafficSource'][$value['trafficSource']] += $value['registrations'];

            $totalResponses +=$temp; 
            //$totalRegistration += $value['registrations'];
        }

        arsort($registrations['sourceApplication']);
        arsort($registrations['trafficSource']);
        arsort($responses['sourceApplication']);
        arsort($responses['trafficSource']);
        arsort($responses['responseType']);
        arsort($responses['rmcResponseType']);
        //_p($responses);die;

        if($source == 'abroad'){
            $productIdsArray =GOLD_SL_LISTINGS_BASE_PRODUCT_ID;
        }else if($source == 'national'){
            $productIdsArray =array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,SILVER_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID);
        }else{
            $productIdsArray =array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,SILVER_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID);
        }
        $paidCourses = $this->OverviewModel->getPaidCourses($source,$dateRange,$productIdsArray);
        $paidCourses =count($paidCourses);
        
        $resultSet['responses']['chartData'] = $responses;
        $resultSet['responses']['totalResponses'] = $totalResponses;
        $resultSet['responses']['avgPaidResponsesOnCourse'] = number_format((($resultSet['responses']['chartData']['responseType']['paidResponses'])/$paidCourses), 2, '.', '');

        //$resultSet['registrations']['chartData'] = $registrations;
        //$resultSet['registrations']['totalRegistration'] = number_format($totalRegistration);

        echo json_encode($resultSet);
        //return $resultSet;
    }
    public function getRegistrations($source='',$sourceApplication='',$dateRange)
    {
        $dateRange = array(
            'startDate' => $this->getDefault('startDate', $this->input->get('startDate')),
            'endDate' => $this->getDefault('endDate', $this->input->get('endDate')),
        );

        $dateRange['startDate'] = preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', '$3-$1-$2', $dateRange['startDate']);
        $dateRange['endDate'] = preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', '$3-$1-$2', $dateRange['endDate']);
        $deviceType = $this->getDefault('device', $this->input->get('device'));
        if($deviceType == 'all'){
            $deviceType ='';
        }
        
        $registrations = $this->OverviewModel->getRegistrationsData($source,$deviceType,$dateRange,'deviceWise');
        $totalRegistration = 0;
        foreach ($registrations as $key => $value) {
            $registrationsResult['sourceApplication'][$value['key']] += $value['doc_count'];
            $totalRegistration += $value['doc_count'];
        }
        $registrationSessionIds = $this->OverviewModel->getRegistrationsData($source,$deviceType,$dateRange,'sourceWise');
        foreach ($registrationSessionIds as $key => $value) {
            $registrationsResult['trafficSource'][$value['key']] += $value['doc_count'];
        }

        $registrationPaidFree = $this->OverviewModel->getRegistrationsData($source,$deviceType,$dateRange,'paidFree');
        foreach ($registrationPaidFree as $key => $value) {
            $registrationsResult['paidFree'][$value['key']] += $value['doc_count'];
        }
        
        arsort($registrationsResult['paidFree']);
        arsort($registrationsResult['sourceApplication']);
        arsort($registrationsResult['trafficSource']);
        $resultSet['registrations']['chartData'] = $registrationsResult;
        $resultSet['registrations']['totalRegistration'] = number_format($totalRegistration);
        echo json_encode($resultSet);
    }

    public function trafficTiles($team){
        $dateRange = array(
            'startDate' => $this->getDefault('startDate', $this->input->get('startDate')),
            'endDate' => $this->getDefault('endDate', $this->input->get('endDate'))
        );

        $dateRange['startDate'] = preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', '$3-$1-$2', $dateRange['startDate']);
        $dateRange['endDate'] = preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', '$3-$1-$2', $dateRange['endDate']);
        $team = $this->getDefault('team', $team);

        $deviceType = $this->getDefault('device', $this->input->get('device'));

        $trafficTilesData = $this->getTrafficTilesData($team, $deviceType, $dateRange);
        echo json_encode($trafficTilesData);
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
                if ($variable == '')
                    $variable = date('Y-m-d', strtotime('-100 days'));
                break;
            case 'endDate':
                if ($variable == '')
                    $variable = date('Y-m-d', strtotime('-1 day'));
                break;
            case 'device':
                if ($variable == '')
                    $variable = 'all';
                break;
            case 'team':
                if($variable == '')
                    $variable = 'global';
        }

        return $variable;
    }

    private function getTrafficTilesData($team, $deviceType, $dateRange)
    {
        return $this->OverviewModel->getTrafficTilesData($team, $deviceType, $dateRange);
    }

    //------------------------------For Top Traffic Stats (Shiksha / Domestic / Study Abroad)---------------------------------------------------
    function getTopDataForTraffic($source='global'){
        $dateRange = $this->input->post('dateRange');
        $sourceApplication = $this->input->post('sourceApplication');
        $showGrowth = $this->input->post('showGrowth');
        $aspect = $this->input->post('aspect');
        //_p($aspect);die;
        echo $this->trackingLib->getTrafficDataForTopStats($dateRange,$source,$showGrowth,$sourceApplication,$aspect);
    }
    
    //------------------------------For Top Responses Stats (Shiksha / Domestic / Study Abroad)---------------------------------------------------

    /*function getTopPagesForResponses($source=''){
        $dateRange = $this->input->post('dateRange');
        $sourceApplication = $this->input->post('sourceApplication');
        $showGrowth = $this->input->post('showGrowth');
        echo $this->trackingLib->buildCacheForResponsesForPage($dateRange,$source,$showGrowth,$sourceApplication);
    }*/

    function getTopDataForResponseStats($source=''){
        if($source != 'abroad'){
            echo '';exit();
        }
        $dateRange = $this->input->get_post('dateRange');
        $sourceApplication = $this->input->get_post('sourceApplication');
        $showGrowth = $this->input->get_post('showGrowth');
        echo $this->trackingLib->responses($dateRange,$source,$showGrowth, $sourceApplication);
    }
    //------------------------------For Top Registration Pages Stats (Shiksha / Domestic / Study Abroad)------------------------------------------------------------
    function getTopPagesDataForRegistration($source=''){
        $dateRange = $this->input->post('dateRange');
        $sourceApplication = $this->input->post('sourceApplication');
        $showGrowth = $this->input->post('showGrowth'); 
        echo $this->trackingLib->buildCacheForRegistrationForPage($dateRange,$source,$showGrowth,$sourceApplication);
    }

    function getTopCategoriesDataForRegistration($source=''){
        $dateRange = $this->input->post('dateRange');
        $sourceApplication = $this->input->post('sourceApplication');
        $showGrowth = $this->input->post('showGrowth'); 
        echo $this->trackingLib->buildCacheForRegistrationForCategory($dateRange,$source,$showGrowth,$sourceApplication);
    }

    function getTopSubCategoriesDataForRegistration($source=''){
        $dateRange = $this->input->post('dateRange');  
        $sourceApplication = $this->input->post('sourceApplication');
        $showGrowth = $this->input->post('showGrowth'); 
        echo $this->trackingLib->buildCacheForRegistrationForSubCategory($dateRange,$source,$showGrowth,$sourceApplication);
    }

    function getTopCitiesDataForRegistration($source=''){
        $dateRange = $this->input->post('dateRange');
        $sourceApplication = $this->input->post('sourceApplication');
        $showGrowth = $this->input->post('showGrowth');
        echo $this->trackingLib->buildCacheForRegistrationForCity($dateRange,$source,$showGrowth,$sourceApplication);
    }

    function getTopCountriesDataForRegistration($source=''){
        //_p($source);die;
        $dateRange = $this->input->post('dateRange');
        $sourceApplication = $this->input->post('sourceApplication');
        $showGrowth = $this->input->post('showGrowth');
        echo $this->trackingLib->buildCacheForRegistrationForCountries($dateRange,$source,$showGrowth,$sourceApplication);
    }

    function getTopDesiredCountriesDataForRegistration($source=''){
        //_p($source);die;
        $dateRange = $this->input->post('dateRange');
        $sourceApplication = $this->input->post('sourceApplication');
        $showGrowth = $this->input->post('showGrowth');
        echo $this->trackingLib->buildCacheForRegistrationForDesiredCountries($dateRange,$source,$showGrowth,$sourceApplication);   
    }
    //------------------------FOR REGISTRATION METRIC---------------------------------------------------------
    function getDataForRegistration(){
        $dateRange = $this->input->post('dateRange');
        $sourceApplication = ucfirst($this->input->post('sourceApplication'));
        $view = $this->input->post('view');
        //_p($view);die;
        //$view = 1;
        //_p($dateRange);_p($sourceApplication);die;
        $trackingIds = $this->OverviewModel->getTrackingIdsForRegistration($sourceApplication);
        $trackingIdsArray = array_map(function($a){
                return $a['id'];
        }, $trackingIds);
        $registrationData = $this->OverviewModel->getRegistrationData($dateRange,$trackingIdsArray,$view);
        if(count($registrationData) <= 0){
            echo 'false';
            return;
        }
        $i=0;
        foreach ($trackingIds as $key => $value) {
            $trackingArray[$value['id']] = array(
                                                'siteSource'=>$value['siteSource'],
                                                'page' => $value['page'],
                                                'widget' => $value['widget'],
                                                'conversionType' => $value['conversionType'],
                                                'keyName' => $value['keyName'],
                                                //'page' => $value['page']
                                                );
        }
        
        $totalRegistrations = 0;    //done
        $mmpReg =0;     //done
        $responseRegistrationCount = 0;
        $downloadGuideRegistrationCount = 0;
        $signup = 0;   //done
        $hamburgerReg = 0;  //done
        
        $topSignup = array('topsignupwidget','findBestCollegesForYourself');
        $responseRegistration = array('response','Course shortlist','downloadBrochure','send Contat Detail','send Contact Detail');

        $i=0;
        foreach ($registrationData as $key => $value) {
            $registrationCount[$i]  = array(
                                            'responseDate' => $value['responseDate'],
                                            'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                                            'page' => $trackingArray[$value['tracking_keyid']]['page'],
                                            'reponsesCount'=>$value['reponsesCount'],
                                            'source' => $value['source']
                                         );
            if($view == 2){
                $registrationCount[$i]['weekNo'] = $value['week'];
            }else if($view == 3){
                $registrationCount[$i]['monthNo'] = $value['month'];
            }
            $i++;
            // For Top Tiles
            $trackingIdIndex = $trackingArray[$value['tracking_keyid']];
            if(in_array($trackingIdIndex['widget'], $topSignup)){
                if($trackingIdIndex['siteSource'] == 'Desktop'){
                    $signup  += $value['reponsesCount'];
                }if($trackingIdIndex['siteSource'] == 'Mobile'){
                    $hamburgerReg += $value['reponsesCount'];
                }
            }else if($trackingIdIndex['widget'] == 'marketingPageForm'){
                $mmpReg += $value['reponsesCount'];
            }

            if(in_array($trackingIdIndex['conversionType'], $responseRegistration)){
                $responseRegistrationCount += $value['reponsesCount'];
            }
            $totalRegistrations += $value['reponsesCount'];
        }
        $registrationDataForPage = $this->trackingLib->prepareDataForDifferentCharts($registrationCount,$this->colorCodes,$dateRange,$view);
        $registrationDataForPage['topTiles'] = array($totalRegistrations,$mmpReg,$responseRegistrationCount,$signup,$hamburgerReg);
        // Paid campaign
        //$sessionsIds = $this->OverviewModel->getSessionsIds($dateRange,$trackingIdsArray);
        //_p(count($sessionsIds));die;
        /*
            $sessionsIdsArray = array_map(function($a){
                    return $a['visitorsessionid'];
            }, $sessionsIds);*/
            /*$paidRegistration = $this->OverviewModel->getPaidRegistration($dateRange,$trackingIds);

            foreach ($paidRegistration as $key => $value) {
                $paidRegistrationArray[$value['utm_campaign']] = $value['count'];
                $total += $value['count'];
            }
            $paidRegistration = $this->trackingLib->prepareDataForDonutChart($paidRegistrationArray,$this->colorCodes,$total);
            $length = sizeof($registrationDataForPage['donutChartData']);
            $registrationDataForPage['donutChartData'][$length] = $paidRegistration;
        */

        // for traffic source wise
        //$result = $this->trackingLib->getTrafficSourceDataForOverviewRegistration($dateRange,$sourceApplication);
        /*$registrationDataForPage['donutChartData'][3] = $registrationDataForPage['donutChartData'][2];
        $registrationDataForPage['donutChartData'][2] = $registrationDataForPage['donutChartData'][1];*/
        /*foreach ($result['source'] as $key => $value) {
            $trafficSourceCount += $value;
        }*/
        //$registrationDataForPage['donutChartData'][1] = $this->trackingLib->prepareDataForDonutChart($result['source'],$this->colorCodes,$trafficSourceCount);
        //$registrationDataForPage['barGraphDataForRegistration'] =array($result['barGraph']['utmSource'],$result['barGraph']['utmCampaign'],$result['barGraph']['utmMedium']);
        /*$registrationDataForPage['trafficSourceFilterData'] =$result['barGraph']['lis'];
        $registrationDataForPage['defaultView'] =$result['barGraph']['defaultView']; */   
        echo json_encode($registrationDataForPage);
    }
    function getTrafficSourceWiseForRegistration()
    {
        ini_set('memory_limit', '300M');
        $dateRange = $this->input->post('dateRange');
        $sourceApplication = ucfirst($this->input->post('sourceApplication'));
        $view = $this->input->post('view');
        //_p($view);die;
        //$view = 1;
        //_p($dateRange);_p($sourceApplication);die;
        $trackingIds = $this->OverviewModel->getTrackingIdsForRegistration($sourceApplication);
        /*_p($trackingIds);
        die;*/
        $trackingIdsArray = array_map(function($a){
                return $a['id'];
        }, $trackingIds);
        $result = $this->trackingLib->getTrafficSourceDataForOverviewRegistration($dateRange,$sourceApplication);
        foreach ($result['source'] as $key => $value) {
            $trafficSourceCount += $value;
        }
        $registrationDataForPage['donutChartData'] = $this->trackingLib->prepareDataForDonutChart($result['source'],$this->colorCodes,$trafficSourceCount);
        $registrationDataForPage['barGraphDataForRegistration'] =array($result['barGraphData']);
        $registrationDataForPage['trafficSourceFilterData'] =$result['barGraph']['lis'];
        $registrationDataForPage['defaultView'] =$result['barGraph']['defaultView'];
        echo json_encode($registrationDataForPage);
    }
    //------------------------FOR TRAFFIC METRIC---------------------------------------------------------
    function getDataForTraffic(){

        $dateRange = $this->input->post('dateRange');
        $sourceApplication = ucfirst($this->input->post('sourceApplication'));
        $view = $this->input->post('view');
        $aspect = $this->input->post('aspect');

        $trafficData = $this->trackingLib->getTrafficForPage($dateRange,$sourceApplication,$this->colorCodes,$view, $aspect);
        echo json_encode($trafficData);
    }

    function prepareLineChartDataForShiksha($dateRange,$resultData,$view){
        $startYear = date('Y', strtotime($dateRange['startDate']));
        $endYear = date('Y', strtotime($dateRange['endDate']));
        $gendate = new DateTime();
        if($view == 1)
        {
            $sDate=date_create($dateRange['startDate']);
            $eDate=date_create($dateRange['endDate']);
            $diff = date_diff($sDate,$eDate);
            $dateDiff = $diff->format("%a");
            $lineArray=array();
            $tempDate =$dateRange['startDate'];

            for($i=0;$i<=$dateDiff;$i++){
                $lineArray[$tempDate] =0;
                $tempDate = date('Y-m-d', strtotime($tempDate . ' +1 day'));
            } 
            foreach ($resultData as  $value) {
                    $lineArray[$value['ResponseDate']] += $value['ScalarValue'];    
            }
        }else if($view == 2){   
            if($startYear == $endYear)
            {
                $swn = intval(date('W', strtotime($dateRange['startDate'])));
                $ewn = intval(date('W', strtotime($dateRange['endDate'])));
                $lineArray = array();
                foreach ($resultData as  $value) {
                    $lineChartData[$value['week']] += $value['ScalarValue'];
                }
                
                if($swn > $ewn){
                    $swn = 0;
                }
                $lineArray[$dateRange['startDate']] = $lineChartData[$swn]?$lineChartData[$swn]:0;
                for ($i=$swn+1; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                
                foreach ($lineChartData as $key => $value) {
                    if($key == $swn)
                    {
                        continue;
                    }         
                    $gendate->setISODate($startYear,$key,1); //year , week num , day
                    $lineArray[$gendate->format('Y-m-d')] = $value;   
                }
                //_p($lineArray);die;
            }
            else
            {
                $swn = date('W', strtotime($dateRange['startDate']));
                $ewn =date('W', strtotime($startYear."-12-31"));
                if($ewn == 1){
                    $ewn = date('W', strtotime($startYear."-12-24"));
                }
                $swn1 = 1;
                $ewn1 =date('W', strtotime($dateRange['endDate']));
                $gendate->setISODate($startYear,$ewn,7); //year , week num , day
                $tempDate = $gendate->format('Y-m-d');
                if($tempDate >= $dateRange['endDate'])
                {
                    $swn1 =0;
                    $ewn1 =-1;
                }
               $lineArray = array();
               foreach ($resultData as  $value) {
                    if(($value['weekNo']) > $ewn)
                    {
                        $lineChartData[1] += $value['reponsesCount'];
                    }else{
                        $lineChartData[($value['week'])] += $value['ScalarValue'];
                    }
                }
               $lineArray[$dateRange['startDate']] = $lineChartData[$swn]?$lineChartData[$swn]:0;
               for ($i=$swn+1; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = $lineChartData[$i]?$lineChartData[$i]:0;
                }
                for ($i=$swn1; $i <= $ewn1 ; $i++) { 
                    $gendate->setISODate($endYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = $lineChartData[$i]?$lineChartData[$i]:0;   
                }
            }    
        }else if($view == 3){   
            if($startYear == $endYear)
            {
                $smn = date('m', strtotime($dateRange['startDate']));
                $emn = date('m', strtotime($dateRange['endDate']));
                $lineArray = array();
                foreach ($resultData as  $value) {
                    if($value['month'] <=9)
                    {
                        $lineChartData['0'.$value['month']] += $value['ScalarValue'];
                    }else{
                        $lineChartData[$value['month']] += $value['ScalarValue'];    
                    }       
                }
                if($lineChartData[$smn])
                {
                    $lineArray[$dateRange['startDate']] = $lineChartData[$smn];    
                }else{
                    $lineArray[$dateRange['startDate']] = 0;    
                }
                for ($i=$smn+1; $i <= $emn ; $i++) {
                    if($i <= 9){
                        $i = '0'.$i;
                        $df = $startYear.'-'.$i.'-01';
                    }else{
                        $df = $startYear.'-'.$i.'-01';    
                    }
                    if($lineChartData[$i]){
                        $lineArray[$df] = $lineChartData[$i];    
                    }else{
                        $lineArray[$df] = 0;   
                    }    
                }
            }
            
            else{
                $smn = intval(date('m', strtotime($dateRange['startDate'])));
                $emn = intval(12);
                $smn1 = intval(1);
                $emn1 =intval(date('m', strtotime($dateRange['endDate'])));
               //_p($smn);_p($emn);_p($smn1);_p($emn1);die;
               $lineArray = array();
               $lineArray[$dateRange['startDate']] = 0;
               $daten = $dateRange['startDate'];
                $mnp =0;
                $mnn =0;
                $y = date('Y', strtotime($resultData[0]['ResponseDate']));
                $flag = 0;
                $sd='';
                for($i=$startYear; $i<=$endYear;$i++)
                {    
                    if($i == $startYear){
                        $sm =$smn;    
                    }else{
                        $sm =1;
                    }

                    if($i == $endYear){
                        $em = $emn1;
                    }else{
                        $em =12;
                    }
                    
                    for($j=$sm;$j<=$em;$j++)
                    {   $j = intval($j);
                        if($j <= 9)
                        {
                            if($flag == 0){
                                $daten = $i.'-0'.$j.'-01';
                            }else{
                                $daten = $i.'-0'.$j.'-01';    
                            }
                            
                        }else{
                            $daten = $i.'-'.$j.'-01';
                        }  
                        if($flag == 0)
                        {
                            $sd=$daten;
                            $flag=1;

                        }
                        $lineArray[$daten] = 0;
                    }
                }
                //_p($lineArray);die;

                foreach ($resultData as  $value) {
                    $mnn = $value['month'];
                    if($mnn > $mnp)
                    {
                        if($value['month'] <= 9)
                        {
                            $daten = $y.'-0'.$value['month'].'-01';
                        }else{
                            $daten = $y.'-'.$value['month'].'-01';
                        }  
                        $lineArray[$daten] += $value['ScalarValue'];
                        $mnp = $mnn;    
                    }else if($mnn == $mnp)
                    {
                        $lineArray[$daten] += $value['ScalarValue'];
                        $mnp = $mnn;    
                    }
                    else{
                        $y++;
                        if($value['month'] <= 9)
                        {
                            $daten = $y.'-0'.$value['month'].'-01';
                        }else{
                            $daten = $y.'-'.$value['month'].'-01';
                        }  
                        $lineArray[$daten] += $value['ScalarValue'];
                        $mnp = $mnn;    
                    }
                }
                $val = $lineArray[$sd];
                //_p($sd);
                //_p($lineArray);die;
                $date1=date_create($dateRange['startDate']);
                $date2=date_create($sd);
                $diff = date_diff($date1,$date2);
                $dateDiff = $diff->format("%a");
                //echo $diff->format("%a");die;
                if($diff->format("%a") !=0){
                    unset($lineArray[$sd]);    
                }
                $lineArray[$dateRange['startDate']] = $val;
                //_p($lineArray);die;
            }
        }
        //_p($lineArray);die;
        $i=0;
        $lineChartArray = array();
        foreach ($lineArray as $key => $value) {
            $lineChartArray[$i++] = array($key,$value);   
        }
        //_p($lineChartArray);die;
        return $lineChartArray;
    }

    private function getUTMWiseDataForBarGraph($sessionResult,$sourceSession,$inputRequestFilter = '',$trafficSourceArray)
    {
        $sessionResultArray = array();
        $sourceHeading = array();
        $trafficSourcePriority =  array('paid','mailer','seo','social','direct','notsure');
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
            $sourceVariable = $sourceSession[$trafficSourcePriority[$minFlag]];
            $sourceHeading[$trafficSourcePriority[$minFlag]] = 1;
            $inputRequestFilter = $trafficSourcePriority[$minFlag];
        }
        else
        {
            $sourceVariable = $sourceSession[$inputRequestFilter];
        }
        $sourceWiseResult = $this->trackingLib->getUTMWiseData($sourceVariable,$inputRequestFilter);
        $splitForBarGraph['splitInformation']['utm Source'] = $this->getCountPerUTMWise($sessionResultArray,$sourceWiseResult['sourceWise'],$trafficSourceArray,$inputRequestFilter);
        $splitForBarGraph['splitInformation']['utm Medium'] = $this->getCountPerUTMWise($sessionResultArray,$sourceWiseResult['mediumWise'],$trafficSourceArray,$inputRequestFilter);
        $splitForBarGraph['splitInformation']['utm Campaign'] = $this->getCountPerUTMWise($sessionResultArray,$sourceWiseResult['campaignWise'],$trafficSourceArray,$inputRequestFilter);
        $splitForBarGraph['barGraphOptions'] = $sourceHeading;
        return $splitForBarGraph;
    }

    private function getCountPerUTMWise($sessionResult,$pivotSplit,$trafficSourceArray,$inputRequestFilter)
    {
        $utmSessionMapping = array();
        foreach ($pivotSplit as $key => $value) {
            if( empty($value['PivotName']))
                continue;
            $utmSessionMapping[$value['sessionId']] =  $value['PivotName'];
        }
        $totalResponses = 0;
        foreach ($utmSessionMapping as $key => $value) {
            $utmResult[htmlentities($value)] += $sessionResult[$key];
            $totalResponses += $sessionResult[$key];
        }
        if($trafficSourceArray[$inputRequestFilter] > $totalResponses){
            $utmResult['Other'] = $trafficSourceArray[$inputRequestFilter] - $totalResponses;
        }
        arsort($utmResult);
        
        $resultArray = array();
        foreach ($utmResult as $key => $value) {
            $utmWiseSplit = new stdClass();
            $utmWiseSplit->PivotName = $key;
            $utmWiseSplit->ScalarValue = $value;
            $utmWiseSplit->Percentage = number_format(($value/$totalResponses) * 100,0,'.','');
            $resultArray[]= $utmWiseSplit;
        }
        return $resultArray;
    }

    //------------------------FOR ENGAGEMENT METRICS ---------------------------------------------------------
    function getDataForEngagement($aspect='pageview', $team='global'){
        $dateRange = array(
            'startDate' => $this->getDefault('startDate', $this->input->get('startdate')),
            'endDate' => $this->getDefault('endDate', $this->input->get('enddate')),
        );
        $sourceApplication = $this->input->get('source');
        $view = $this->input->get('view');
        $trafficSourceName = $this->input->get('trafficSource'); // as obtained from the input

        if($trafficSourceName == ''){

            $trends = $this->OverviewModel->getEngagementTrend($dateRange,$team, $sourceApplication, $view, $aspect);

            if(count($trends) < 1){
                $engagementData = array();
            } else {

                $trends = $this->MISCommonLib->insertZeroValues($trends, $dateRange, $view);

                $engagementTrends = array();
                foreach($trends as $index => $oneResult){
                    $oneDateResult = new stdClass();
                    $oneDateResult->ResponseDate = $oneResult->Date;
                    $oneDateResult->ScalarValue = $oneResult->ScalarValue;
                    $engagementTrends[] = $oneDateResult;
                    unset($trends[$index]); // Keep on unsetting the values which we do not need
                }
            }

            $engagementData =
                array(
                    'topTiles' => $this->OverviewModel->getTopTilesForEngagement($dateRange,'', array('deviceType'=>$sourceApplication, 'team' => $team), $aspect),
                    'resultsForGraph' => $engagementTrends,
                    'page' => $this->OverviewModel->getEngagementSplit($dateRange,$team, $sourceApplication, $view, $aspect, 'page'),
                    'source Application' => $this->OverviewModel->getEngagementSplit($dateRange,$team, $sourceApplication, $view, $aspect, 'source Application'),
                );

            $splits = $this->sessionSplit($dateRange, $team, $sourceApplication, $view, $aspect);

            $engagementData['session'] = $splits['session'];
            $engagementData['splitForBarGraph'] = $splits['splitForBarGraph'];
            echo json_encode($engagementData);
        } else {

            $splits = $this->sessionSplit($dateRange, $team, $sourceApplication, $view, $aspect, $trafficSourceName);

            echo json_encode(array(
                'splitForBarGraph' => $splits['splitForBarGraph']
            ));
        }
    }


    public function sessionSplit($dateRange,$team, $sourceApplication, $view, $aspect, $trafficSourceName = ''){
        $barGraphOptions = array();

        $disallowedTrafficTypes = array(
            'other',
            'sourcemissing'
        );


        $trafficSourceSplit = $this->OverviewModel->getEngagementSplit($dateRange,$team, $sourceApplication, $view, $aspect, 'traffic Source');
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


        if ($trafficSourceName != '') {
            $sessionTypeSelector = $trafficSourceName;
            $trafficSourcePriority[$trafficSourceName] = 1;
        } else {
            $break = 0;
            foreach ($trafficSourcePriority as $key => $onePriority) {
                foreach ($barGraphOptions as $barGraphKey => $oneOption) {
                    $barGraphOptions[$barGraphKey] = 0;
                    if ($key == $barGraphKey) {
                        $sessionTypeSelector = $key;
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
            'utmCampaign',
        );



        $utmSplitResults = array();

        foreach($utmSplits as $oneSplit){

            $utmSplitResults[$oneSplit] = $this->OverviewModel->getEngagementSplit($dateRange,$team, $sourceApplication, $view, $aspect, $oneSplit, $sessionTypeSelector);
        }

        $responsesSessionSplit = array(
            'session' => $trafficSourceSplit,
            'splitForBarGraph' => array(
                'barGraphOptions' => $barGraphOptions,
                'splitInformation' => $utmSplitResults
            )
        );

        return $responsesSessionSplit;
    }
}
