<?php
require_once('vendor/autoload.php');
class trackingMISCommonLib {
    private $CI;
	private $loggedInUser;
    //private static $TRAFFICDATA_PAGEVIEWS, $TRAFFICDATA_SESSIONS;
    
    public function __construct(){
        $this->CI = & get_instance();
        $this->usergroupAllowed = array("shikshaTracking");
        $this->MISCommonLib = $this->CI->load->library('trackingMIS/MISCommonLib');
        $this->clientCon = $this->MISCommonLib->_getSearchServerConnection();
        //trackingMISCommonLib::$TRAFFICDATA_PAGEVIEWS = 'trafficdata_pageviews_2';
        //trackingMISCommonLib::$TRAFFICDATA_SESSIONS  = 'trafficdata_sessions_3';
        $this->MISCacheLib    = $this->CI->load->library('cache/MISCache');

        $this->CI->load->model('overview_model');
        $this->OverviewModel  = new overview_model();

        $this->CI->load->builder('listing/ListingBuilder');
        $listingBuilder = new ListingBuilder();
        //$this->instituteRepo = $listingBuilder->getInstituteRepository();
        $this->universityRepo = $listingBuilder->getUniversityRepository();

        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();

        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder    = new LocationBuilder();
        $this->locationRepository = $locationBuilder->getLocationRepository();
    }
	/*
	 * if page is login page OR SA sales dashboard, user can either be a shiksha tracking user or SA Sales MIS user
	 */
    private function _isUsergroupValid($isLoginPage, $isSASalesDashboard)
	{
		$saCMSToolsLib = $this->CI->load->library('saCMSTools/SACMSToolsLib');
		$usergroup = $this->loggedInUser[0]['usergroup'];
		if($isSASalesDashboard || $isLoginPage) // for SA dashboard or login (coz user can be of any dashboard)...
		{
			$this->loggedInUser['isSASalesDashboardUser'] = $saCMSToolsLib->checkIfSASalesMISUser($this->loggedInUser[0]['userid']);
			// ... user can be a valid MIS user (shikshaTracking) or they can be sa Sales MIS user
			return ($this->loggedInUser['isSASalesDashboardUser'] || in_array($usergroup,$this->usergroupAllowed));
		}
		else
		{
			return in_array($usergroup,$this->usergroupAllowed);
		}
	}
		
    /*
     *This function will confirm that a valid user from the allowed usergroups is logged in.
     *Output/Functionality:
     *  If a valid user is logged in, returns the user validation object
     *  If an invalid user is logged in, sends to the unauthorizedUser page and returns -1.
     *  If noone is logged in, returns bool(false).
    */    
    public function checkValidUser($isLoginPage = false, $ajaxFlag = false, $isSASalesDashboard = false){
		$this->loggedInUser = $this->CI->checkUserValidation();
		if($this->loggedInUser !== "false"){
			if(!$this->_isUsergroupValid($isLoginPage, $isSASalesDashboard) && !$ajaxFlag){
				redirect('trackingMIS/Dashboard/unauthorizedUser', 'refresh');
                die;
			}
			else if(!$this->_isUsergroupValid($isLoginPage, $isSASalesDashboard) && $ajaxFlag){
				return "unauth";
			}else{
				return $this->loggedInUser;
			}
		}
		else
		{
			if($ajaxFlag)
			{
				return "noauth";
			}
		}
        if($isLoginPage){
            return false;
        }else{
            redirect('trackingMIS/Dashboard/login', 'refresh');
            die;
        }
	}
	
	public function checkValidSASalesUser($isLoginPage = false, $ajaxFlag = false)
	{
		return $this->checkValidUser($isLoginPage, $ajaxFlag, true);
	}
	
    public function prepareDataForBargraph($topData,$showGrowth='false',$flag=0,$barGraphWidth=0,$momGrowth='',$yoyGrowth='')
    {  
        //_p($topData);_p($momGrowth);_p($yoyGrowth);
        //_p($flag);_p($showGrowth);die;
        $maxValue = 0;
        $i=0;
        foreach ($topData as $key => $value) {
            if($flag ==1){
                if($i==0){
                    $maxValue = $value['count'];
                }else{
                    break;
                }
            }else{
                if($i==0){
                    $maxValue = $value;
                }else{
                    break;   
                }
            }    
            $i++;
        }
        $avg = number_format((($maxValue)/100), 2,'.','');
        //_p($avg);die;
        if($showGrowth == 'true'){
            if($barGraphWidth==0){
                $leftWidth = 43;
                $centerWidth  = 20;
                $countWidth =   15;
                $momWidth= 11;
                $yoyWidth = 11;
                $pageNameWidth = 28;
            }else{
                $leftWidth = 45;
                $centerWidth  = 25;
                $countWidth =   10;
                $momWidth= 10;
                $yoyWidth = 10;
                $pageNameWidth = 70;
            }     
        }else{
            if($barGraphWidth==0){
                $leftWidth = 40;
                $centerWidth  = 45;
                $countWidth =   15;
                $pageNameWidth = 26;
            }else{
                $leftWidth = 40;
                $centerWidth  = 50;
                $countWidth =   10;
                $pageNameWidth = 54;
            }
        }
        $barGraph = '<table style="width: 100%;">';
        foreach ($topData as $key => $value) {   
            if($flag==1){
                $normalizeValue = number_format(($value['count']/$avg), 0, '.', '');
                $actualValue = $value['count'];
                if($value['countryName']){
                    $title = $value['name'].', '.$value['countryName'];
                    $fieldName = formatArticleTitle($title,$pageNameWidth);
                    $span = '<a href="'.$value['url'].'" target ="_blank" title="'.$title.'"><span>'.$fieldName.'</span></a>';
                }else{
                    $title = $value['name'];
                    $fieldName = formatArticleTitle($title,$pageNameWidth);
                    $span = '<span title="'.$title.'">'.$fieldName.'</span>';
                }
                
            }else{
                $normalizeValue = number_format(($value/$avg), 0, '.', '');
                $actualValue = $value;
                $title = $this->MISCommonLib->getPageName($key);
                $fieldName = limitTextLength($title,$pageNameWidth);
                $span = '<span title="'.$title.'">'.$fieldName.'</span>';
            }

            if($showGrowth == 'true'){
                if($momGrowth[$key] >= 0){
                    $tempMOM = '<i id="momGrowth" class="green BG_fontSize" > <i class="fa fa-sort-asc "></i>'.$momGrowth[$key].'%</i></i>';
                }else{
                    $tempMOM ='<i id="momGrowth" class="red BG_fontSize" > <i class="fa fa-sort-desc"></i>'.$momGrowth[$key].'%</i></i>';
                }

                if($yoyGrowth[$key] >= 0){
                    $tempYOY = '<i id="yoyGrowth" class="green BG_fontSize"> <i class="fa fa-sort-asc "></i>'.$yoyGrowth[$key].'%</i></i>';
                }else{
                    $tempYOY ='<i id="yoyGrowth" class="red BG_fontSize"> <i class="fa fa-sort-desc"></i>'.$yoyGrowth[$key].'%</i></i>';
                }
                $field ='<td class="BGHeading_fontSize" style="padding-left:0px;width:'.$countWidth.'% !important;">&nbsp&nbsp'.number_format($actualValue).'</td>'.
                    '<td style="text-align: left; width:'.$momWidth.'% !important;">'.$tempMOM.'</td>'.
                    '<td style="text-align: left; width: '.$yoyWidth.'% !important;">'.$tempYOY.'</td>';
                    //'</tr>';
            }else{
                $field ='<td class="BGHeading_fontSize" style="width:'.$countWidth.'% !important">&nbsp&nbsp'.number_format($actualValue).
                    '</td>';    
            }

            /*$barGraph = $barGraph.
                '<div class="widget_summary">'.
                    '<div class="w_left w_25">'.
                       $span.
                    '</div>'.
                    '<div class="w_center w_55" style="width:'.$width.'px">'.
                        '<div class="progress" style="margin-bottom:10px !important" >'.
                            '<div  title = "'.$actualValue.'" class="progress-bar bg-green" role="progressbar" style="width:'.$normalizeValue.'%'.'" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">'.
                                '<span class="sr-only"  ></span>'.
                            '</div>'.
                        '</div>'.
                    '</div>'.
                    '<div class="w_right" style="text-align: left;">'.
                        '<span >'.$field.'</span>'.
                    '</div>'.
                    '<div class="clearfix"></div>'.
                '</div>';
            */

            $barGraph = $barGraph.
            '<tr class="widget_summary">'.
                '<td class="w_left" style="width:'.$leftWidth.'% !important">'.
                   $span.
                '</td>'.
                //'</div>'.
                '<td class="w_center " style="width:'.$centerWidth.'% !important">'.
                //'<div class="w_center w_55" style="width:'.$width.'px">'.
                    '<div class="progress" style="margin-bottom:10px !important" >'.
                        '<div  title = "'.$actualValue.'" class="progress-bar bg-green" role="progressbar" style="width:'.$normalizeValue.'%'.'" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">'.
                            '<span class="sr-only"  ></span>'.
                        '</div>'.
                    '</div>'.
                '</td>'.$field.

                /*'<td class="w_right" style="text-align: left;">'.
                    '<span >'.$field.'</span>'.
                '</td>'.*/
                '<div class="clearfix"></div>';
            
        }
        $barGraph = $barGraph.'</table>';
        //_p($barGraph);die;
        return $barGraph;
    }
 
    //-------------------------------Registration  Tab--------------------------------------------------------------------
    function prepareDataForLineChart($lineChartData)
    {
        $i=0;
        foreach ($lineChartData as $date => $count) {
            $lineChartArray[$i++] = array($date,$count);   
        }
        $lineChartData = array('lineChartArray', $lineChartArray);
        return $lineChartData;
    }

    function prepareDataForDonutChart($donutChartData,$colorArray,$total)
    {   
        arsort($donutChartData);
        $i = 0; 
        foreach ($donutChartData as $key => $value) {
            $donutChartArray[$i]['value'] = intval($value);
            $donutChartArray[$i]['label'] = $key;
            $donutChartArray[$i]['color'] = $colorArray[$i];
            $splitName = strlen($key) > 16 ? substr($key, 0, 12) . ' ...' : $key;
            $donutChartIndexData=$donutChartIndexData. 
                            '<tr>'.
                                '<td class="width_60_percent_important">'.
                                    '<p title="'.$key.'" class="white_space_normal_overwrite"><i class="fa fa-square " style="color: '.$donutChartArray[$i]['color'].'"></i>'.$splitName.'</p>'.
                                '</td>'.
                                '<td >'.number_format((($value*100)/$total), 2, '.', '').'</td>'.
                                '<td >'.number_format($value).'</td>'.
                            '</tr>';                            
            $i++;
        }
        $total = '<h4>Total Count :    '.($total?$total:0).'</h4>';
        $donutChart = array($donutChartArray,$donutChartIndexData,$total);
        return $donutChart;
    }
    
    function prepareDataForDifferentCharts($registrationData,$colorCodes,$dateRange,$view)
    {
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
            foreach ($registrationData as $key=>  $value) {
                    $lineArray[$value['responseDate']] += $value['reponsesCount'];    
                    $pieChartDataOne[$value['siteSource']]+= $value['reponsesCount'];
                    $pieChartDataTwo[$value['page']]+= $value['reponsesCount'];
                    $pieChartDataPaidFree[$value['source']] += $value['reponsesCount'];
            }
        }else if($view == 2){   

            if($startYear == $endYear)
            {
                // creating week array
                $swn = date('W', strtotime($dateRange['startDate']));
                $ewn = date('W', strtotime($dateRange['endDate']));
                //_p($swn);_p($ewn);
                $lineArray = array();
                foreach ($registrationData as  $value) {
                    $lineChartData[$value['weekNo']] += $value['reponsesCount'];
                    $pieChartDataOne[$value['siteSource']]+= $value['reponsesCount'];
                    $pieChartDataTwo[$value['page']]+= $value['reponsesCount'];                
                    $pieChartDataPaidFree[$value['source']] += $value['reponsesCount'];
                }
                //_p($lineChartData);die;
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
                if($ewn == 1)
                {
                    $ewn = date('W', strtotime($startYear."-12-24"));
                }
                //_p($swn);_p($ewn);
                $swn1 = 1;
                $ewn1 =date('W', strtotime($dateRange['endDate']));
                $gendate->setISODate($startYear,$ewn,7); //year , week num , day
                $tempDate = $gendate->format('Y-m-d');
                if($tempDate >= $dateRange['endDate'])
                {
                    $swn1 =0;
                    $ewn1 =-1;
                }
               //_p($swn1);_p($ewn1);
               $lineArray = array();
               foreach ($registrationData as  $value) {
                    if(($value['weekNo']) > $ewn)
                    {
                        $lineChartData[1] += $value['reponsesCount'];
                    }else{
                        $lineChartData[($value['weekNo'])] += $value['reponsesCount'];
                    }
                    $pieChartDataOne[$value['siteSource']]+= $value['reponsesCount'];
                    $pieChartDataTwo[$value['page']]+= $value['reponsesCount'];
                    $pieChartDataPaidFree[$value['source']] += $value['reponsesCount'];
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
                foreach ($registrationData as  $value) {
                    if($value['monthNo'] <=9)
                    {
                        $lineChartData['0'.$value['monthNo']] += $value['reponsesCount'];
                    }else{
                        $lineChartData[$value['monthNo']] += $value['reponsesCount'];    
                    }  
                    $pieChartDataOne[$value['siteSource']]+= $value['reponsesCount'];
                    $pieChartDataTwo[$value['page']]+= $value['reponsesCount'];    
                    $pieChartDataPaidFree[$value['source']] += $value['reponsesCount'];
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
                $smn = date('m', strtotime($dateRange['startDate']));
                $emn = 12;
                $smn1 = 1;
                $emn1 =date('m', strtotime($dateRange['endDate']));
               //_p($smn);_p($emn);_p($smn1);_p($emn1);die;
               $lineArray = array();
               $lineArray[$dateRange['startDate']] = 0;
               $daten = $dateRange['startDate'];
                $mnp =0;
                $mnn =0;
                $y = date('Y', strtotime($registrationData[0]['responseDate']));
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
                    {
                        if($j <= 9)
                        {
                            if($flag == 0){
                                $daten = $i.'-'.$j.'-01';
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

                foreach ($registrationData as  $value) {
                    $mnn = $value['monthNo'];
                    if($mnn > $mnp)
                    {
                        if($value['monthNo'] <= 9)
                        {
                            $daten = $y.'-0'.$value['monthNo'].'-01';
                        }else{
                            $daten = $y.'-'.$value['monthNo'].'-01';
                        }  
                        $lineArray[$daten] += $value['reponsesCount'];
                        $mnp = $mnn;    
                    }else if($mnn == $mnp)
                    {
                        $lineArray[$daten] += $value['reponsesCount'];
                        $mnp = $mnn;    
                    }
                    else{
                        $y++;
                        if($value['monthNo'] <= 9)
                        {
                            $daten = $y.'-0'.$value['monthNo'].'-01';
                        }else{
                            $daten = $y.'-'.$value['monthNo'].'-01';
                        }  
                        $lineArray[$daten] += $value['reponsesCount'];
                        $mnp = $mnn;    
                    }
                    $pieChartDataOne[$value['siteSource']]+= $value['reponsesCount'];
                    switch ($metric) {
                        case 'RESPONSE':
								$pieChartDataTwo[$value['type']]+= $value['reponsesCount'];
								break;
                        case 'REGISTRATION':
                            $pieChartDataTwo[$value['type']]+= $value['reponsesCount'];
                            $pieChartDataPaidFree[$value['source']] += $value['reponsesCount'];
                            break;

                        case 'CPENQUIRY':
                            $pieChartDataTwo[$value['source']]+= $value['reponsesCount'];
                            break;

                        case 'DOWNLOAD':
                            if(!$isComparision && $pageName){
                                $pieChartDataTwo[$value['widget']]+= $value['reponsesCount'];    
                            }
                            break;;

                        case 'COMPARE':
                            if($pageName && !$isComparision){
                                $pieChartDataTwo[$value['widget']]+= $value['reponsesCount'];
                            }
                            break;
                    }
                    
                    if(!$pageName && !$isComparision){
                        $pieChartDataThree[$value['page']]+=$value['reponsesCount'];
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
        /*
           _p($lineArray);
            _p('----------------pie-1-----------');
            _p($pieChartDataOne);
            _p('-----------------pie-2----------');
            _p($pieChartDataTwo);
            _p('---------------------------');
            die;
        */
        
        $totalRegistration = $pieChartDataOne['Desktop']+$pieChartDataOne['Mobile']+$pieChartDataOne['Mobile App'];

        $lineChartData      = $this->prepareDataForLineChart($lineArray);
        $pieChartOneData    = $this->prepareDataForDonutChart($pieChartDataOne,$colorCodes,$totalRegistration);
        $pieChartTwoData    = $this->prepareDataForDonutChart($pieChartDataTwo,$colorCodes,$totalRegistration);
        $pieChartDataPaidFree = $this->prepareDataForDonutChart($pieChartDataPaidFree,$colorCodes,$totalRegistration);

        $pageData['lineChartData'] = $lineChartData;
        $pageData['donutChartData'] = array($pieChartOneData,$pieChartTwoData,$pieChartDataPaidFree);
        //_p($pageData['donutChartData']);die;
        //_p($pageData['lineChartData']);die;
        return $pageData;
    }
    //-------------------------------Registration Tab End --------------------------------------------------------------------

    //-------------------------------Traffic  Tab--------------------------------------------------------------------
    private function _getSearchServerConnection() {
        $this->clientParams = array();
        //$this->clientParams['hosts'] = array('10.10.16.72');
        $this->clientParams['hosts'] = array(ELASTIC_SEARCH_HOST);
        $this->clientCon = new Elasticsearch\Client($this->clientParams);
    }

    private function _getView($view)
    {
        switch($view)
        {
            case 1:
                return 'day';

            case 2:
                return 'week';

            case 3:
                return 'month';

            default :
                return 'day';
        }
    }

    function getTrafficForPage($dateRange,$sourceApplication,$colorCodes,$view=1, $aspect='sessions')
    {
        /*
            Input :
                1. date range filter
                2. source application
                3. view

            Output:
                1. Top Tiles:
                    a. Users
                    b. Sessions
                    c. Page Views
                    d. % new sessions
                2. line chart
                3. donut chart:
                    a. source application
                    b. traffic source
                    c. page
                4. bar graph:
                    a. utm source
                    b. utm campaign
                    c. utm medium
        */

        $params = $this->MISCommonLib->prepareElasticQueryForShikshaTraffic($dateRange,$sourceApplication);
        // Line Chart Data
        $view = $this->_getView($view);
        $dateWiseFilter = array(
            'dateWiseCount' => array(
                'date_histogram' => array(
                    'interval' => $view,
                    'field' => 'startTime'
                )
            )
        );
        if ($aspect == 'users') {
            $dateWiseFilter['dateWiseCount']['aggs'] = array(
                'usersCount' => array(
                    'cardinality' => array(
                        'field' => 'visitorId'
                    )
                )
            );
        } else if ($aspect == 'pageviews') {
            $dateWiseFilter['dateWiseCount']['aggs'] = array(
                'pageviews' => array(
                    'sum' => array(
                        'field' => 'pageviews'
                    )
                )
            );
        }

        $params['body']['aggs'] = $dateWiseFilter;
        $search = $this->clientCon->search($params);
        $actualCount = $search['hits']['total'];
        $dateWiseData = $search['aggregations']['dateWiseCount']['buckets'];
        $lineChartData= $this->MISCommonLib->prepareDataForLineChartForShikshaTraffic($dateWiseData,$dateRange,$view,$actualCount,0, $aspect);

        // for Source Application Data
        $donutChartDataBySourceApplication = $this->MISCommonLib->prepareDonutChartData($params,'isMobile',$colorCodes,0, $aspect);

        // for Traffic Source Data
        $donutChartDataBySource = $this->MISCommonLib->prepareDonutChartData($params,'source',$colorCodes,0, $aspect);

        // for Page Identifier Data
        $donutChartDataByPage = $this->MISCommonLib->prepareDonutChartData($params,'landingPageDoc.pageIdentifier',$colorCodes,0, $aspect);

        //_p($donutChartDataBySourceApplication);_p($donutChartDataBySource);_p($donutChartDataByPage);die;
        /*
            $pageWiseFilter['pivot']['terms']['field']= 'landingPageDoc.isMobile';
            $pageWiseFilter['pivot']['terms']['size']= 0;
            $params['body']['aggs']['checkColoum']['aggs'] = $pageWiseFilter;
            $search = $this->clientCon->search($params);
            $actualCount = $search['hits']['total'];
            $sourceApplicationData = $search['aggregations']['checkColoum']['sourceApplication']['buckets'];
            $pageDataCount = 0;
            foreach ($sourceApplicationData as $key => $value) {
                $trafficSourceArray[$value['key']] = $value['doc_count'];
                $trafficSourceCount += $value['doc_count'];
            }
            if($actualCount > $pageDataCount){
                $pageArray['Other'] = $actualCount - $pageDataCount;
            }
            $donutChartDataByPage = $this->prepareDataForDonutChart($pageArray,$colorCodes,$actualCount);
            $sourceApplicationFilter['sourceApplication']['terms']['field'] = 'landingPageDoc.isMobile';
            $sourceApplicationFilter['sourceApplication']['terms']['size']= 0;
            // for PageWise Data
            $pageWiseFilter['pageWise']['terms']['field']= 'landingPageDoc.pageIdentifier';
            $pageWiseFilter['pageWise']['terms']['size']= 0;
            $params['body']['aggs']['checkColoum']['aggs'] = $pageWiseFilter;
            $search = $this->clientCon->search($params);
            $actualCount = $search['hits']['total'];
            $pageWiseData = $search['aggregations']['checkColoum']['pageWise']['buckets'];
            $pageDataCount = 0;
            foreach ($pageWiseData as $key => $value) {
                $pageName = $value['key'];
                $pageName = $this->MISCommonLib->getPageName($pageName);
                $pageArray[$pageName] = $value['doc_count'];
                $pageDataCount += $value['doc_count'];
            }
            if($actualCount > $pageDataCount){
                $pageArray['Other'] = $actualCount - $pageDataCount;
            }
            $donutChartDataByPage = $this->prepareDataForDonutChart($pageArray,$colorCodes,$actualCount);

            // for traffic source data
            $sourceWiseFilter['sourseWise']['terms']['field']= 'landingPageDoc.source';
            $sourceWiseFilter['sourseWise']['terms']['size']= 0;
            $params['body']['aggs']['checkColoum']['aggs'] = $sourceWiseFilter;
            $search = $this->clientCon->search($params);
            $actualCount = 0;
            $actualCount = $search['hits']['total'];
            $trafficSourceData = $search['aggregations']['checkColoum']['sourseWise']['buckets'];
            $trafficSourceCount = 0;
            foreach ($trafficSourceData as $key => $value) {
                $trafficSourceArray[$value['key']] = $value['doc_count'];
                $trafficSourceCount += $value['doc_count'];
            }
            if($actualCount > $trafficSourceCount){
                $trafficSourceArray['Other'] = $actualCount - $trafficSourceCount;
            }
            $donutChartDataByTrafficSource = $this->prepareDataForDonutChart($trafficSourceArray,$colorCodes,$trafficSourceCount);
        */
        //_p($donutChartDataBySource[0]);die;
		$trafficSourceCount = 0;
        foreach ($donutChartDataBySource[0] as $key => $value) {
            if($value['label'] != 'Other'){
                $trafficSourceArray[$value['label']] = $value['value'];
                $trafficSourceCount += $value['value'];    
            }
        }

        $trafficData =array();
        $trafficData['dataForDifferentCharts']['lineChartData'] = $lineChartData;
        $trafficData['dataForDifferentCharts']['donutChartData'] =array($donutChartDataBySourceApplication,$donutChartDataBySource,$donutChartDataByPage);
        if($trafficSourceCount > 0){
            $trafficSourceArray = array($trafficSourceArray,$trafficSourceCount);

            $filter['aspect'] = $aspect;
            $trafficDataForDiffChart['barGraphData'] = $this->MISCommonLib->prepareDataForTrafficSourceFilter($trafficSourceArray,$dateRange,$sourceApplication,$filter);
            $trafficData['dataForDifferentCharts']['barGraphData'] =$trafficDataForDiffChart['barGraphData']['barGraphData'];
            $trafficData['dataForDifferentCharts']['trafficSourceFilterData'] =$trafficDataForDiffChart['barGraphData']['lis'];
            $trafficData['dataForDifferentCharts']['defaultView'] =$trafficDataForDiffChart['barGraphData']['defaultView'];
        }


        //---------------------For Top Tiles------------------------------
        //prepare data for top tiles

            // 1. total 
            $session =0;
            unset($params['body']['aggs']);
            $searchData = $this->clientCon->search($params);
            $searchData = $searchData['hits']['total'];

            // 2. unique users
            unset($params['body']['aggs']);
            $params['body']['aggs']['users']['cardinality']['field'] = 'visitorId';
            $search = $this->clientCon->search($params);
            $visitors = $search['aggregations']['users']['value'];

            // 3. new session count
            unset($params['body']['aggs']);
            $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['sessionNumber'] =1;
            $search = $this->clientCon->search($params);
            $count = $search['hits']['total'];
            $perNewSession = number_format(($count*100)/$searchData, 2, '.', '');

            // 4. pageviews
            $params = array();
            $params['index'] = MISCommonLib::$TRAFFICDATA_PAGEVIEWS;
            $params['type'] = 'pageview';
            $params['body']['size'] = 0;

            $startDateFilter = array();
            $startDateFilter['range']['visitTime']['gte'] = $dateRange['startDate'].'T00:00:00';
            $endDateFilter = array();
            $endDateFilter['range']['visitTime']['lte'] = $dateRange['endDate'].'T23:59:59';
            if($sourceApplication){
                if($sourceApplication == 'Mobile'){
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isMobile'] = "yes";
                }if($sourceApplication == 'Desktop'){
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isMobile'] = "no";
                }
            }
            $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
            $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
            //_p(json_encode( $params));die;
            $search = $this->clientCon->search($params);
            $pageviews = $search['hits']['total'];

            $trafficData['topTiles'] = array($visitors,$searchData,$pageviews,$perNewSession);
        //----------------------------------------------------------------
        //_p($trafficData);die;
        return $trafficData;   
    }

    private function _getPaidTraffic($queryForPaidTraffic){
        //_p(json_encode($queryForPaidTraffic));die;
        $queryForPaidTraffic['body']['query']['filtered']['filter']['bool']['must'][]['term']['source'] = "paid";
        $queryForPaidTraffic['body']['aggs']['checkColoum']['filter']['exists']['field'] = 'landingPageDoc.pageIdentifier';

        $campaignWiseFilter['campaignWise']['terms']['field']= 'utm_campaign';
        $campaignWiseFilter['campaignWise']['terms']['size']= 0;
        $queryForPaidTraffic['body']['aggs']['checkColoum']['aggs'] = $campaignWiseFilter;

        //_p(json_encode($queryForPaidTraffic));die;
        $search = $this->clientCon->search($queryForPaidTraffic);
        $campaignwiseData = $search['aggregations']['checkColoum']['campaignWise']['buckets'];
        return $campaignwiseData;
    }
    //-------------------------------Traffic  Tab End--------------------------------------------------------------------


    //-------------------------------Responses Stats---------------------------------------------------------------------
    function buildCacheForResponsesForPage($dateRange, $source,$showGrowth='true',$sourceApplication=''){ 
        if($showGrowth == 'true'){
            $startDate = date_create($dateRange['startDate']);
            $startDate = date_format($startDate,'dmY');
            $endDate = date_create($dateRange['endDate']);
            $endDate = date_format($endDate,'dmY');
            switch($source) {
                case 'national':
                    $key = 'TopPagesForNationalResponses'.$startDate.'-'.$endDate; 
                    break;

                case 'abroad':
                    $key = 'TopPagesForAbroadResponses'.$startDate.'-'.$endDate;
                    break;

                default:
                    $key = 'TopPagesForShikshaResponses'.$startDate.'-'.$endDate;
                    break;
            }
            if($this->MISCacheLib && $topPagesData = $this->MISCacheLib->getTopData($key)){
                return $topPagesData;
            }else{
                $topPageData = $this->_getTopPagesDataForResponses($source,$sourceApplication,$dateRange,$showGrowth);
                $topPageData = json_encode($topPageData);
                $this->MISCacheLib->storeTopData($topPageData,$key);
                return $topPageData;    
            }  
        }else{
            $topPageData = $this->_getTopPagesDataForResponses($source,$sourceApplication,$dateRange,$showGrowth);
            return json_encode($topPageData);
        }
    }

    private function _getTopPagesDataForResponses($source,$sourceApplication,$dateRange,$showGrowth){
        $topPages = $this->_getTopPagesForResponses($source,$sourceApplication,$dateRange);
        if($showGrowth == 'false'){    
            $topPageData = $this->prepareDataForBargraph($topPages,$showGrowth,0,0);
        }else{
            $topPagesArray = array_keys($topPages);
    
            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];
            // Top Pages For previous Month
            $dateRange['startDate'] = date('Y-m-d', strtotime( $startDate. ' -30 days'));
            $dateRange['endDate'] = date('Y-m-d', strtotime( $endDate. ' -30 days'));

            $topPagesForLastMonth = $this->_getTopPagesForResponses($source,$sourceApplication,$dateRange,$topPagesArray);
            //Top Pages For Last Year Same Month
            $dateRange['startDate'] = date('Y-m-d', strtotime( $startDate. ' -1 year'));
            $dateRange['endDate'] = date('Y-m-d', strtotime( $endDate. ' -1 year'));

            $topPagesForLastYear = $this->_getTopPagesForResponses($source,$sourceApplication,$dateRange,$topPagesArray);
            //_p($topPages);_p($topPagesForLastMonth);_p($topPagesForLastYear);die;
            foreach ($topPages as $key => $value) {
                if($topPagesForLastMonth[$key]){
                    $responseDiff = $value - $topPagesForLastMonth[$key];
                    $momGrowth[$key] = number_format((($responseDiff*100)/$topPagesForLastMonth[$key]), 0);
                }else{
                    $momGrowth[$key] = number_format((100), 0);
                }
                
                $responseDiff =0;
                if($topPagesForLastYear[$key]){
                    $responseDiff = $value - $topPagesForLastYear[$key];
                    $yoyGrowth[$key] = number_format((($responseDiff*100)/$topPagesForLastYear[$key]), 0);
                }else{
                    $yoyGrowth[$key] = number_format((100), 0);
                }      
            }

            $topPageData = $this->prepareDataForBargraph($topPages,$showGrowth,0,0,$momGrowth,$yoyGrowth);
        }
        return $topPageData;
    }

    private function _getTopPagesForResponses($source,$sourceApplication,$dateRange,$topPagesArray){
        if($sourceApplication){
            $sourceApplication = ucfirst($sourceApplication);
        }
        $topPageArray = $this->OverviewModel->getTopPagesForResponses($source,$sourceApplication,$dateRange,$topPagesArray);
        foreach ($topPageArray as $key => $value) {
            $topPages[$value['pageName']] = $value['count'];
        }
        return $topPages;
    }

    // reference to this method from Overview model is closed as of 07/04/2016
    function buildCacheForResponsesStats($dateRange, $source,$showGrowth='true',$sourceApplication=''){
        //_p($dateRange);_p($source);_p($showGrowth);_p($sourceApplication);die;
        if($showGrowth == 'true'){
            $startDate = date_create($dateRange['startDate']);
            $startDate = date_format($startDate,'dmY');
            $endDate = date_create($dateRange['endDate']);
            $endDate = date_format($endDate,'dmY');
            switch($source) {
                case 'national':
                    $key = 'TopDataForResponseStatsForNationalResponses'.$startDate.'-'.$endDate;
                    break;

                case 'abroad':
                    $key = 'TopDataForResponseStatsForAbroadResponses'.$startDate.'-'.$endDate;
                    break;

                default:
                    $key = 'TopDataForResponseStatsForShikshaResponses'.$startDate.'-'.$endDate;
                    break;
            }
            if($this->MISCacheLib && $topDataForResponseStats = $this->MISCacheLib->getTopData($key)){
                return $topDataForResponseStats;
            }else{
                $topDataForResponseStats = $this->getTopDataForResponseStats($source,$sourceApplication,$dateRange,$showGrowth);
                $topDataForResponseStats = json_encode($topDataForResponseStats);
                $this->MISCacheLib->storeTopData($topDataForResponseStats,$key);
                return $topDataForResponseStats;
            }
        }else{
            $topDataForResponseStats = $this->getTopDataForResponseStats($source,$sourceApplication,$dateRange,$showGrowth);
            //_p($topDataForResponseStats);die;
            return json_encode($topDataForResponseStats);
        }
    }

    public function getTopDataForResponseStats($source,$sourceApplication,$dateRange,$showGrowth){
        $trackingIds = $this->_getTrackingIdsForResponses($source,$sourceApplication);
        $topPageData = $this->responses($source,$sourceApplication,$dateRange,$trackingIds);
        if($showGrowth == 'true'){
            if($source){ 
                    $topCategories = array_keys($topPageData['topCategories']);
                    $topSubcategories = array_keys($topPageData['topSubCategories']);
                    $topCities = array_keys($topPageData['topCities']);
                    $topListings = array_keys($topPageData['topListings']);
                    $topCountries = array_keys($topPageData['topCountries']);
                    //_p($topCategories);_p($topSubcategories);_p($topCities);_p($topListings);die;

                    //_p($categoryToSubCategoryArray);die;
                    $startDate = $dateRange['startDate'];
                    $endDate = $dateRange['endDate'];
                    $dateRange['startDate'] = date('Y-m-d', strtotime( $startDate. ' -30 days'));
                    $dateRange['endDate'] = date('Y-m-d', strtotime( $endDate. ' -30 days'));

                    // For Top Category 
                    if($source == 'national'){
                        $subCategoryIdsForCategoryId = $this->OverviewModel->getSubCategoriesIdsForCategoryIds($topCategories);
                    
                        foreach ($subCategoryIdsForCategoryId as $key => $value) {
                            $subCategoriesArray[] = $value['boardId'];
                            $categoryToSubCategoryArray[$value['parentID']][] = $value['boardId'];
                        }
                        //_p($categoryToSubCategoryArray);die;
                        $subCategoryWiseData = $this->OverviewModel->getTopResponsesForSubCategories($dateRange,$subCategoriesArray,$trackingIds);
                        foreach ($subCategoryWiseData as $key => $value) {
                            $subCategoryToResponseCount[$value['subCategoryId']] = $value['count'];
                        }
                        foreach ($categoryToSubCategoryArray as $categoryId => $subCategoryArray) {
                            foreach ($subCategoryArray as $key => $value) {
                                $topCategoryForMOM[$categoryId] += $subCategoryToResponseCount[$value];
                            }
                        }
                    }else if($source == 'abroad'){
                        $topCategoryForMOM = $this->OverviewModel->getTopResponsesForResponseStats($source,$dateRange,'category',$topCategories,$trackingIds);
                        foreach ($topCategoryForMOM as $key => $value) {
                            $topCategoryForMOMChange[$value['category_id']] += $value['count'];
                            unset($topCategoryForMOM[$key]);
                        }
                        $topCategoryForMOM = $topCategoryForMOMChange;
                    }
                    
                    // For Top SubCategory
                    $topSubCategoryForMOM = $this->OverviewModel->getTopResponsesForSubCategories($dateRange,$topSubcategories,$trackingIds);
                    foreach ($topSubCategoryForMOM as $key => $value) {
                        $topSubCategoryForMOMChange[$value['subCategoryId']] += $value['count'];
                        unset($topSubCategoryForMOM[$key]);
                    }
                    $topSubCategoryForMOM = $topSubCategoryForMOMChange;
                    // For Top City
                    $topCitiesForMOM = $this->OverviewModel->getTopResponsesForResponseStats($source,$dateRange,'city',$topCities,$trackingIds);
                    foreach ($topCitiesForMOM as $key => $value) {
                        $topCitiesForMOMChange[$value['city_id']] += $value['count'];
                        unset($topCitiesForMOM[$key]);
                    }
                    $topCitiesForMOM = $topCitiesForMOMChange;
                    // For Top Listings
                    $topListingsForMOM = $this->OverviewModel->getTopResponsesForResponseStats($source,$dateRange,'listing',$topListings,$trackingIds);
                    foreach ($topListingsForMOM as $key => $value) {
                        $topListingsForMOMChange[$value['listing_id']] += $value['count'];
                        unset($topListingsForMOM[$key]);
                    }
                    $topListingsForMOM = $topListingsForMOMChange;
                    //_p($topSubCategoryForMOM);_p($topCitiesForMOM);_p($topListingsForMOM);_p();_p();die;

                    if($source == 'abroad'){
                        $topCountriesForMOM = $this->OverviewModel->getTopResponsesForResponseStats($source,$dateRange,'country',$topCountries,$trackingIds);
                        //_p($topCountriesForMOM);die;
                        foreach ($topCountriesForMOM as $key => $value) {
                            $topCountriesForMOMChange[$value['country_id']] += $value['count'];
                            unset($topCountriesForMOM[$key]);
                        }
                        $topCountriesForMOM = $topCountriesForMOMChange;
                    }

                    
                    $dateRange['startDate'] = date('Y-m-d', strtotime( $startDate. ' -1 year'));
                    $dateRange['endDate'] = date('Y-m-d', strtotime( $endDate. ' -1 year'));

                    // For Top Category
                    if($source == 'national'){
                        $subCategoryWiseData=array();
                        $subCategoryToResponseCount =array();
                        $subCategoryWiseData = $this->OverviewModel->getTopResponsesForSubCategories($dateRange,$subCategoriesArray,$trackingIds);
                        foreach ($subCategoryWiseData as $key => $value) {
                            $subCategoryToResponseCount[$value['subCategoryId']] = $value['count'];
                        }
                        foreach ($categoryToSubCategoryArray as $categoryId => $subCategoryArray) {
                            foreach ($subCategoryArray as $key => $value) {
                                $topCategoryForYOY[$categoryId] += $subCategoryToResponseCount[$value];
                            }
                        }
                    }else{
                        $topCategoryForYOY = $this->OverviewModel->getTopResponsesForResponseStats($source,$dateRange,'category',$topCategories);
                        foreach ($topCategoryForYOY as $key => $value) {
                            $topCategoryForYOYChange[$value['category_id']] += $value['count'];
                            unset($topCategoryForYOY[$key]);
                        }
                        $topCategoryForYOY = $topCategoryForYOYChange;
                    }

                    // For Top SubCategory
                    $topSubCategoryForYOY = $this->OverviewModel->getTopResponsesForSubCategories($dateRange,$topSubcategories,$trackingIds);
                    foreach ($topSubCategoryForYOY as $key => $value) {
                        $topSubCategoryForYOYChange[$value['subCategoryId']] += $value['count'];
                        unset($topSubCategoryForYOY[$key]);
                    }
                    $topSubCategoryForYOY = $topSubCategoryForYOYChange;

                    // For Top City
                    $topCitiesForYOY = $this->OverviewModel->getTopResponsesForResponseStats($source,$dateRange,'city',$topCities,$trackingIds);
                    foreach ($topCitiesForYOY as $key => $value) {
                        $topCitiesForYOYChange[$value['city_id']] += $value['count'];
                        unset($topCitiesForYOY[$key]);
                    }
                    $topCitiesForYOY = $topCitiesForYOYChange;

                    // For Top Listings
                    $topListingsForYOY = $this->OverviewModel->getTopResponsesForResponseStats($source,$dateRange,'listing',$topListings,$trackingIds);
                    foreach ($topListingsForYOY as $key => $value) {
                        $topListingsForYOYChange[$value['university_id']] += $value['count'];
                        unset($topListingsForYOY[$key]);
                    }
                    $topListingsForYOY = $topListingsForYOYChange;

                    if($source == 'abroad'){
                        $topCountriesForYOY = $this->OverviewModel->getTopResponsesForResponseStats($source,$dateRange,'country',$topCountries,$trackingIds);
                        //_p($topCountriesForMOM);die;
                        foreach ($topCountriesForYOY as $key => $value) {
                            $topCountriesForYOYChange[$value['country_id']] += $value['count'];
                            unset($topCountriesForYOY[$key]);
                        }
                        $topCountriesForYOY = $topCountriesForYOYChange;
                    }
                    foreach ($topPageData['topSubCategories'] as $key => $value) {
                        $subCatToCat[] = $key;
                    }
                    $subCatToCatName = $this->_getSubCatToCategoryName($subCatToCat);
                    foreach ($subCatToCatName as $key => $value) {
                        $subCateToName[$value['id']] = $value['categoryId'];
                    }
                    foreach ($topPageData['topSubCategories'] as $key => $value) {
                        $topPageData['topSubCategories'][$key]['name'] =$value['name'].' ('.$subCateToName[$key].')';
                    }

                    $topDataForDiffView['topCategories'] = $this->_prepareDataForBarGraphForGrowth($topPageData['topCategories'],$topCategoryForMOM,$topCategoryForYOY,0,0);
                    $topDataForDiffView['topSubcategories'] = $this->_prepareDataForBarGraphForGrowth($topPageData['topSubCategories'],$topSubCategoryForMOM,$topSubCategoryForYOY,0,1);
                    $topDataForDiffView['topCities'] = $this->_prepareDataForBarGraphForGrowth($topPageData['topCities'],$topCitiesForMOM,$topCitiesForYOY,0,0);
                    $topDataForDiffView['topListings'] = $this->_prepareDataForBarGraphForGrowth($topPageData['topListings'],$topListingsForMOM,$topListingsForYOY,0,1);
                    if($source == 'abroad'){
                        $topDataForDiffView['topCountries'] = $this->_prepareDataForBarGraphForGrowth($topPageData['topCountries'],$topCountriesForMOM,$topCountriesForYOY,0,0);
                    }
                    //_p($topCategoryForMOM);_p($topCategoryForYOY);die; 
            }else{    
                    foreach ($topPageData['topCategories'] as $key => $value) {
                        if($value['source'] == 'national'){
                            $topCategoriesForNational[] = $key;
                        }else{
                            $topCategoriesForAbroad[] = $key;
                        }
                    }
                    
                    $topSubcategories = array_keys($topPageData['topSubCategories']);

                    foreach ($topPageData['topCities'] as $key => $value) {
                        if($value['source'] == 'national'){
                            $topCitiesForNational[] = $key;
                        }else{
                            $topCitiesForAbroad[] = $key;
                        }
                    }

                    foreach ($topPageData['topListings'] as $key => $value) {
                        if($value['countryName'] == 'India'){
                            $topListingsForNational[] = $key;
                        }else{
                            $topListingsForAbroad[] = $key;
                        }
                    }

                    //_p($topCategories);_p($topSubcategories);_p($topCities);_p($topListings);die;
                    $startDate = $dateRange['startDate'];
                    $endDate = $dateRange['endDate'];
                    $dateRange['startDate'] = date('Y-m-d', strtotime( $startDate. ' -30 days'));
                    $dateRange['endDate'] = date('Y-m-d', strtotime( $endDate. ' -30 days'));

                    // For Top Category 
                    $topCategoryForNationalForMOM = array();
                    if($topCategoriesForNational){
                        $subCategoryIdsForCategoryId = $this->OverviewModel->getSubCategoriesIdsForCategoryIds($topCategoriesForNational);
                        foreach ($subCategoryIdsForCategoryId as $key => $value) {
                            $subCategoriesArray[] = $value['boardId'];
                            $categoryToSubCategoryArray[$value['parentID']][] = $value['boardId'];
                        }
                        //_p($categoryToSubCategoryArray);die;
                        $subCategoryWiseData = $this->OverviewModel->getTopResponsesForSubCategories($dateRange,$subCategoriesArray,$trackingIds['national']);
                        foreach ($subCategoryWiseData as $key => $value) {
                            $subCategoryToResponseCount[$value['subCategoryId']] = $value['count'];
                        }
                        foreach ($categoryToSubCategoryArray as $categoryId => $subCategoryArray) {
                            foreach ($subCategoryArray as $key => $value) {
                                $topCategoryForNationalForMOM[$categoryId] += $subCategoryToResponseCount[$value];
                            }
                        }
                    }
                    $topCategoryForAbroadForMOM = array();
                    if($topCategoriesForAbroad){
                        $topCategoryForAbroadForMOM = $this->OverviewModel->getTopResponsesForResponseStats('abroad',$dateRange,'category',$topCategoriesForAbroad,$trackingIds['abroad']);
                        foreach ($topCategoryForAbroadForMOM as $key => $value) {
                            $topCategoryForAbroadForMOMChange[$value['category_id']] += $value['count'];
                            unset($topCategoryForAbroadForMOM[$key]);
                        }
                        $topCategoryForAbroadForMOM = $topCategoryForAbroadForMOMChange;
                    }

                    if($topCategoryForNationalForMOM && $topCategoryForAbroadForMOM){
                        $topCategoryForMOM = $topCategoryForNationalForMOM + $topCategoryForAbroadForMOM;
                    }else if($topCategoryForNationalForMOM && !$topCategoryForAbroadForMOM){
                        $topCategoryForMOM = $topCategoryForNationalForMOM;
                    }else if(!$topCategoryForNationalForMOM && $topCategoryForAbroadForMOM){
                        $topCategoryForMOM = $topCategoryForAbroadForMOM;
                    }else{
                        $topCategoryForMOM = '';
                    }

                    foreach ($topCategoryForMOM as $key => $value) {
                        $topCategoryForMOMChange[$value['category_id']] += $value['count'];
                        unset($topCategoryForMOM[$key]);
                    }
                    $topCategoryForMOM = $topCategoryForMOMChange;
                    
                    // For Top SubCategory
                    $tempTrackingIds = array_merge($trackingIds['abroad'],$trackingIds['national']);
                    $topSubCategoryForMOM = $this->OverviewModel->getTopResponsesForSubCategories($dateRange,$topSubcategories,$tempTrackingIds);
                    foreach ($topSubCategoryForMOM as $key => $value) {
                        $topSubCategoryForMOMChange[$value['subCategoryId']] += $value['count'];
                        unset($topSubCategoryForMOM[$key]);
                    }
                    $topSubCategoryForMOM = $topSubCategoryForMOMChange;

                    // For Top City
                    $topCitiesForNationalForMOM = array();
                    if($topCitiesForNational){
                        $topCitiesForNationalForMOM = $this->OverviewModel->getTopResponsesForResponseStats('national',$dateRange,'city',$topCitiesForNational,$trackingIds['national']);
                        foreach ($topCitiesForNationalForMOM as $key => $value) {
                            $topCitiesForNationalForMOMChange[$value['city_id']] += $value['count'];
                            unset($topCitiesForNationalForMOM[$key]);
                        }
                        $topCitiesForNationalForMOM = $topCitiesForNationalForMOMChange;
                    }
                    $topCitiesForAbroadForMOM = array();
                    if($topCitiesForAbroad){
                        $topCitiesForAbroadForMOM = $this->OverviewModel->getTopResponsesForResponseStats('abroad',$dateRange,'city',$topCitiesForAbroad,$trackingIds['abroad']);    
                        foreach ($topCitiesForAbroadForMOM as $key => $value) {
                            $topCitiesForAbroadForMOMChange[$value['city_id']] += $value['count'];
                            unset($topCitiesForAbroadForMOM[$key]);
                        }
                        $topCitiesForAbroadForMOM = $topCitiesForAbroadForMOMChange;
                    }
                    //_p($topCitiesForNationalForMOM); _p($topCitiesForAbroadForMOM); die;
                    if($topCitiesForNationalForMOM && $topCitiesForAbroadForMOM){
                        $topCitiesForMOM = $topCitiesForNationalForMOM + $topCitiesForAbroadForMOM;
                    }else if($topCitiesForNationalForMOM && !$topCitiesForAbroadForMOM){ 
                        $topCitiesForMOM = $topCitiesForNationalForMOM ;
                    }else if(!$topCitiesForNationalForMOM && $topCitiesForAbroadForMOM){
                        $topCitiesForMOM =  $topCitiesForAbroadForMOM;
                    }else{
                        $topCitiesForMOM = '';
                    }
                    

                    // For Top Listings
                    $topListingsForAbroadForMOM = array();
                    $topListingsForNationalForMOM = array();
                    if($topListingsForNational){
                        $topListingsForNationalForMOM = $this->OverviewModel->getTopResponsesForResponseStats('national',$dateRange,'listing',$topListingsForNational,$trackingIds['national']);
                        foreach ($topListingsForNationalForMOM as $key => $value) {
                            $topListingsForNationalForMOMChange[$value['listing_id']] += $value['count'];
                            unset($topListingsForNationalForMOM[$key]);
                        }
                        $topListingsForNationalForMOM = $topListingsForNationalForMOMChange;
                    }
                    if($topListingsForAbroad){
                        $topListingsForAbroadForMOM = $this->OverviewModel->getTopResponsesForResponseStats('abroad',$dateRange,'listing',$topListingsForAbroad,$trackingIds['abroad']);
                        foreach ($topListingsForAbroadForMOM as $key => $value) {
                            $topListingsForAbroadForMOMChange[$value['listing_id']] += $value['count'];
                            unset($topListingsForAbroadForMOM[$key]);
                        }
                        $topListingsForAbroadForMOM = $topListingsForAbroadForMOMChange;
                    }

                    if($topListingsForAbroadForMOM && $topListingsForNationalForMOM){
                        $topListingsForMOM = $topListingsForAbroadForMOM + $topListingsForNationalForMOM;
                    }else if($topListingsForAbroadForMOM && !$topListingsForNationalForMOM){
                        $topListingsForMOM = $topListingsForAbroadForMOM ;
                    }else if(!$topListingsForAbroadForMOM && $topListingsForNationalForMOM){
                        $topListingsForMOM =  $topListingsForNationalForMOM;
                    }else{
                        $topListingsForMOM = '';
                    }
                    
                    
                    $dateRange['startDate'] = date('Y-m-d', strtotime( $startDate. ' -1 year'));
                    $dateRange['endDate'] = date('Y-m-d', strtotime( $endDate. ' -1 year'));

                    // For Top Category
                    //$topCategoryForYOY = $this->OverviewModel->getTopResponsesForResponseStats($source,$dateRange,'category',$topCategories);
                    /*foreach ($topCategoryForYOY as $key => $value) {
                        $topCategoryForYOY[$value['category_id']] += $value['count'];
                        unset($topCategoryForYOY[$key]);
                    }*/
                    //-------------------------------------

                    $topCategoryForNationalForYOY = array();
                    if($topCategoriesForNational){
                        $subCategoryWiseData = $this->OverviewModel->getTopResponsesForSubCategories($dateRange,$subCategoriesArray,$trackingIds['national']);
                        foreach ($subCategoryWiseData as $key => $value) {
                            $subCategoryToResponseCount[$value['subCategoryId']] = $value['count'];
                        }
                        foreach ($categoryToSubCategoryArray as $categoryId => $subCategoryArray) {
                            foreach ($subCategoryArray as $key => $value) {
                                $topCategoryForNationalForYOY[$categoryId] += $subCategoryToResponseCount[$value];
                            }
                        }
                    }
                    $topCategoryForAbroadForYOY = array();
                    if($topCategoriesForAbroad){
                        $topCategoryForAbroadForYOY = $this->OverviewModel->getTopResponsesForResponseStats('abroad',$dateRange,'category',$topCategoriesForAbroad,$trackingIds['abroad']);
                        foreach ($topCategoryForAbroadForYOY as $key => $value) {
                            $topCategoryForAbroadForYOYChange[$value['category_id']] += $value['count'];
                            unset($topCategoryForAbroadForYOY[$key]);
                        }
                        $topCategoryForAbroadForYOY = $topCategoryForAbroadForYOYChange;
                    }

                    if($topCategoryForNationalForYOY && $topCategoryForAbroadForYOY){
                        $topCategoryForYOY = $topCategoryForNationalForYOY + $topCategoryForAbroadForYOY;
                    }else if($topCategoryForNationalForYOY && !$topCategoryForAbroadForYOY){
                        $topCategoryForYOY = $topCategoryForNationalForYOY;
                    }else if(!$topCategoryForNationalForYOY && $topCategoryForAbroadForYOY){
                        $topCategoryForYOY =  $topCategoryForAbroadForYOY;
                    }else{
                        $topCategoryForYOY = '';
                    }
                    
                    //---------------------------------------

                    // For Top SubCategory
                    $tempTrackingIds = array_merge($trackingIds['abroad'],$trackingIds['national']);
                    $topSubCategoryForYOY = $this->OverviewModel->getTopResponsesForSubCategories($dateRange,$topSubcategories,$tempTrackingIds);
                    foreach ($topSubCategoryForYOY as $key => $value) {
                        $topSubCategoryForYOYChange[$value['subCategoryId']] += $value['count'];
                        unset($topSubCategoryForYOY[$key]);
                    }
                    $topSubCategoryForYOY = $topSubCategoryForYOYChange;

                    // For Top City
                    $topCitiesForAbroadForYOY = array();
                    $topCitiesForNationalForYOY = array();
                    if($topCitiesForNational){
                        $topCitiesForNationalForYOY = $this->OverviewModel->getTopResponsesForResponseStats('national',$dateRange,'city',$topCitiesForNational,$trackingIds['national']);
                        foreach ($topCitiesForNationalForYOY as $key => $value) {
                            $topCitiesForNationalForYOYChange[$value['city_id']] += $value['count'];
                            unset($topCitiesForNationalForYOY[$key]);
                        }
                        $topCitiesForNationalForYOY = $topCitiesForNationalForYOYChange;
                    }
                    if($topCitiesForAbroad){
                        $topCitiesForAbroadForYOY = $this->OverviewModel->getTopResponsesForResponseStats('abroad',$dateRange,'city',$topCitiesForAbroad,$trackingIds['abroad']);    
                        foreach ($topCitiesForAbroadForYOY as $key => $value) {
                            $topCitiesForAbroadForYOYChange[$value['city_id']] += $value['count'];
                            unset($topCitiesForAbroadForYOY[$key]);
                        }
                        $topCitiesForAbroadForYOY = $topCitiesForAbroadForYOYChange;
                    }

                    //_p($topCitiesForNationalForMOM); _p($topCitiesForAbroadForMOM); die;
                    if($topCitiesForNationalForYOY && $topCitiesForAbroadForYOY){
                        $topCitiesForYOY = $topCitiesForNationalForYOY + $topCitiesForAbroadForYOY;    
                    }else if($topCitiesForNationalForYOY && !$topCitiesForAbroadForYOY){
                        $topCitiesForYOY = $topCitiesForNationalForYOY;
                    }else if(!$topCitiesForNationalForYOY && $topCitiesForAbroadForYOY){
                        $topCitiesForYOY =  $topCitiesForAbroadForYOY;
                    }else{
                        $topCitiesForYOY = '';
                    }
                    
                    // For Top Listings
                    foreach ($topListingsForYOY as $key => $value) {
                        $topListingsForYOYChange[$value['university_id']] += $value['count'];
                        unset($topListingsForYOY[$key]);
                    }
                    $topListingsForYOY = $topListingsForYOYChange;
                    $topListingsForAbroadForYOY = array();
                    $topListingsForNationalForYOY = array();
                    
                    if($topListingsForNational){
                        $topListingsForNationalForYOY = $this->OverviewModel->getTopResponsesForResponseStats('national',$dateRange,'listing',$topListingsForNational,$trackingIds['national']);
                        foreach ($topListingsForNationalForYOY as $key => $value) {
                            $topListingsForNationalForYOYChange[$value['listing_id']] += $value['count'];
                            unset($topListingsForNationalForYOY[$key]);
                        }
                        $topListingsForNationalForYOY = $topListingsForNationalForYOYChange;
                    }
                    
                    if($topListingsForAbroad){
                        $topListingsForAbroadForYOY = $this->OverviewModel->getTopResponsesForResponseStats('abroad',$dateRange,'listing',$topListingsForAbroad,$trackingIds['abroad']);
                        foreach ($topListingsForAbroadForYOY as $key => $value) {
                            $topListingsForAbroadForYOYChange[$value['listing_id']] += $value['count'];
                            unset($topListingsForAbroadForYOY[$key]);
                        }
                        $topListingsForAbroadForYOY = $topListingsForAbroadForYOYChange;
                    }

                    if($topListingsForAbroadForYOY && $topListingsForNationalForYOY){
                        $topListingsForYOY = $topListingsForAbroadForYOY + $topListingsForNationalForYOY;
                    }else if($topListingsForAbroadForYOY && !$topListingsForNationalForYOY){
                        $topListingsForYOY = $topListingsForAbroadForYOY;
                    }if(!$topListingsForAbroadForYOY && $topListingsForNationalForYOY){
                        $topListingsForYOY = $topListingsForNationalForYOY;
                    }else{
                        $topListingsForYOY = '';
                    }
                    
                    foreach ($topPageData['topSubCategories'] as $key => $value) {
                        $subCatToCat[] = $key;
                    }
                    $subCatToCatName = $this->_getSubCatToCategoryName($subCatToCat);
                    foreach ($subCatToCatName as $key => $value) {
                        $subCateToName[$value['id']] = $value['categoryId'];
                    }
                    foreach ($topPageData['topSubCategories'] as $key => $value) {
                        $topPageData['topSubCategories'][$key]['name'] =$value['name'].' ('.$subCateToName[$key].')';
                    }
                    //_p($topPageData['topSubcategories']);_p($topSubCategoryForMOM);_p($topSubCategoryForYOY);die;
                    $topDataForDiffView['topCategories'] = $this->_prepareDataForBarGraphForGrowth($topPageData['topCategories'],$topCategoryForMOM,$topCategoryForYOY,0,0);
                    $topDataForDiffView['topSubcategories'] = $this->_prepareDataForBarGraphForGrowth($topPageData['topSubCategories'],$topSubCategoryForMOM,$topSubCategoryForYOY,0,1);
                    $topDataForDiffView['topCities'] = $this->_prepareDataForBarGraphForGrowth($topPageData['topCities'],$topCitiesForMOM,$topCitiesForYOY,0,0);
                    $topDataForDiffView['topListings'] = $this->_prepareDataForBarGraphForGrowth($topPageData['topListings'],$topListingsForMOM,$topListingsForYOY,0,1);
                    //_p($topDataForDiffView);die;
                    //_p($topCategoryForMOM);_p($topCategoryForYOY);die;
            }
        }else{
            foreach ($topPageData['topSubCategories'] as $key => $value) {
                $subCatToCat[] = $key;
            }
            $subCatToCatName = $this->_getSubCatToCategoryName($subCatToCat);
            foreach ($subCatToCatName as $key => $value) {
                $subCateToName[$value['id']] = $value['categoryId'];
            }
            foreach ($topPageData['topSubCategories'] as $key => $value) {
                $topPageData['topSubCategories'][$key]['name'] =$value['name'].' ['.$subCateToName[$key].']';
            }
            $topDataForDiffView['topCategories'] = $this->prepareDataForBargraph($topPageData['topCategories'],$showGrowth,1,0);
            $topDataForDiffView['topSubcategories'] = $this->prepareDataForBargraph($topPageData['topSubCategories'],$showGrowth,1,1);
            $topDataForDiffView['topCities'] = $this->prepareDataForBargraph($topPageData['topCities'],$showGrowth,1,0);
            $topDataForDiffView['topListings'] = $this->prepareDataForBargraph($topPageData['topListings'],$showGrowth,1,1);
            if($source == 'abroad'){
                $topDataForDiffView['topCountries'] = $this->prepareDataForBargraph($topPageData['topCountries'],$showGrowth,1,0);
            }
        }
        //_p($topDataForDiffView);die;   
        return $topDataForDiffView;
    }
    private function _getSubCatToCategoryName($subCategoryArray){
        if(count($subCategoryArray) <=0)
            return;
        $subCategoryObject = $this->categoryRepository->findMultiple($subCategoryArray);
        foreach ($subCategoryArray as $key => $value) {
            $categoryId = $subCategoryObject[$value]->getparentId();
            $categoryIds[] = $categoryId; 
            $subCategoryArray[$key] = array(
                                            'id' => $value,
                                            'categoryId' => $categoryId
                                            );
        }
        $categoryIds = array_unique($categoryIds);
        //_p($categoryIds);die;
        $categoryObject = $this->categoryRepository->findMultiple($categoryIds);

        foreach ($categoryIds as $key => $value) {
            $catIdToName[$categoryObject[$value]->getName()] = $value;
        }

        foreach ($catIdToName as $key => $value) {
            $catIdToName[$value] = $key;
            unset($catIdToName[$key]);
        }
        
        foreach ($subCategoryArray as $key => $value) {
            $subCategoryArray[$key]['categoryId'] = $catIdToName[$value['categoryId']];
        }

        return $subCategoryArray;
    }

    private function _getTrackingIdsForResponses($source,$sourceApplication){
        $trackingIdsArray = $this->OverviewModel->getTrackingIdsForResponses($source,$sourceApplication);
        if($source){
            $trackingIds = array_map(function($a){
                return $a['id'];
            },$trackingIdsArray);     
            return $trackingIds;
        }else{
            foreach ($trackingIdsArray as $key => $value) {
                if($value['site'] == 'Study Abroad'){
                    $trackingIds['abroad'][]= $value['id'];
                }else{
                    $trackingIds['national'][]= $value['id'];
                }
            }
            return $trackingIds; 
        }  
    }

    public function responses($dateRange, $source,$showGrowth=true,$sourceApplication){

        $inputRequest = new stdClass();
        $inputRequest->startDate = $dateRange['startDate'];
        $inputRequest->endDate = $dateRange['endDate'];
        $inputRequest->sourceApplication = $sourceApplication;
        $inputRequest->team = $source;
        $topResponses = $this->_getDataForResponses($inputRequest,$source);

        $getGrowth = function($responseStats, $inputRequest, $thisReference){

            $growthYOY = ' -1 year';
            $growthMOM = ' -30 days';

            $startDateMOM = date('Y-m-d', strtotime($inputRequest->startDate.$growthMOM));
            $endDateMOM = date('Y-m-d', strtotime($inputRequest->endDate.$growthMOM));

            $startDateYOY = date('Y-m-d', strtotime($inputRequest->startDate.$growthYOY));
            $endDateYOY = date('Y-m-d', strtotime($inputRequest->endDate.$growthYOY));

            foreach($responseStats as $statName => $oneResponseStat){

                $fieldName = '';
                switch ($statName) {
                    case 'topCategories':
                        $fieldName = 'response_category_id';
                        break;
                    case 'topSubCategories':
                        $fieldName = 'response_sub_category_id';
                        break;
                    case 'topCities':
                        $fieldName = 'response_city_id';
                        break;
                    case 'topListings':
                        $fieldName = 'response_university_id';
                        break;
                    case 'topPages':
                        $fieldName = 'page';
                        break;
                    case 'topCountries':
                        $fieldName = 'response_country_id';
                        break;
                }

                foreach($oneResponseStat as $key => $oneSingleAspect){
                    $elasticQuery = array(
                        'index' => SHIKSHA_RESPONSE_INDEX_NAME,
                        'type'  => "response",
                        'body'  => array(
                            'size' => 0,
                            'query' => array(
                                'bool' => array(
                                    'filter' => array(
                                        'bool' => array(
                                            'must' => array(
                                                array(
                                                    'term' => array(
                                                        $fieldName => $oneSingleAspect['Id']
                                                    )
                                                ),
                                            ),
                                        )
                                    )
                                )
                            )
                        )
                    );

                    // apply source application filter only when it is specified
                    if($inputRequest->sourceApplication != 'all' && $inputRequest->sourceApplication != ''){
                        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                            'term' => array(
                                'sourceApplication' => $inputRequest->sourceApplication
                            )
                        );
                    }

                    if(strtolower($inputRequest->team) == 'abroad' || strtolower($inputRequest->team) == 'studyabroad'){
                        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                            'term' => array(
                                'site' => 'Study Abroad'
                            )
                        );
                    } else if (strtolower($inputRequest->team) == 'domestic' || strtolower($inputRequest->team) == 'national'){
                        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                            'terms' => array(
                                'site' => array(
                                    'Test Prep',
                                    'Domestic'
                                )
                            )
                        );
                    }

                    $elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                        'range' => array(
                            'responseDate' => array(
                                'lte' => $endDateMOM . 'T23:59:59'
                            ),
                        )
                    );
                    $elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                        'range' => array(
                            'responseDate' => array(
                                'gte' => $startDateMOM . 'T00:00:00'
                            ),
                        )
                    );

                    $delta = $thisReference->clientCon->search($elasticQuery);
                    $changeMOM = $delta['hits']['total'];
                    $change = $responseStats[$statName][$key]['ScalarValue'] - $changeMOM;
                    if($changeMOM == 0){
                        $responseStats[$statName][$key]['DeltaMOM'] = 100;
                    } else {
                        $responseStats[$statName][$key]['DeltaMOM'] = ( $change / $changeMOM ) * 100;
                    } // done for MOM, now proceed towards YOY

                    $change = 0;
                    $delta = 0;

                    $lastItem = count($elasticQuery['body']['query']['filtered']['filter']['bool']['must']);

                    // Change the date ranges in the query
                    $elasticQuery['body']['query']['bool']['filter']['bool']['must'][$lastItem-1] = array(
                        'range' => array(
                            'responseDate' => array(
                                'gte' => $startDateYOY . 'T00:00:00'
                            ),
                        )
                    );
                    $elasticQuery['body']['query']['bool']['filter']['bool']['must'][$lastItem-2] = array(
                        'range' => array(
                            'responseDate' => array(
                                'lte' => $endDateYOY . 'T23:59:59'
                            ),
                        )
                    );
                    $delta = $thisReference->clientCon->search($elasticQuery);
                    $changeYOY = $delta['hits']['total'];
                    $change = $responseStats[$statName][$key]['ScalarValue'] - $changeYOY;
                    if($changeYOY == 0){
                        $responseStats[$statName][$key]['DeltaYOY'] = 100;
                    } else {
                        $responseStats[$statName][$key]['DeltaYOY'] = ( $change / $changeYOY ) * 100;
                    }
                }
            }
            return $responseStats;
        };

        if($showGrowth){
            return json_encode($getGrowth($topResponses, $inputRequest, $this));
        }
    }

    private function _getDataForAbroad($trackingIds,$dateRange,$source){
        
        $responses = $this->OverviewModel->getResponseStatsData('abroad',$trackingIds,$dateRange);
        if(!$responses){
            return $responses;
        }
        foreach ($responses as $key => $value) {
            $categoryArray[$value['category_id']] += $value['count'];
            $subCategoryArray[$value['sub_category_id']]  += $value['count'];
            $cityArray[$value['city_id']]  += $value['count'];
            $universityArray[$value['university_id']]  += $value['count'];
            $countryArray[$value['country_id']]  += $value['count'];
            unset($responses[$key]);
        }
        arsort($categoryArray);
        $topCategories = $categoryArray;
        //$topCategories = array_slice($categoryArray, 0, 10,'true');
        arsort($subCategoryArray);
        $topSubCategories = array_slice($subCategoryArray, 0, 10,'true');
        if($source){    
            $data = $this->_getTopCategories($topCategories,$topSubCategories,$source);
            $topCategories = $data['topCategories'];
            $topSubCategories = $data['topsubCategories'];
        }else{
            foreach ($topCategories as $key => $value) {
                $topCategories[$key] = array(
                                            'count' => $value,
                                            'source' => 'abroad'
                                            );
            }
        }

        unset($categoryArray);
        unset($subCategoryArray);

        arsort($cityArray);
        $topCities = array_slice($cityArray, 0, 10,'true');
        
        if($source){
            $topCityIds = array_keys($topCities);
            $topCities = $this->_getTopCities($topCities,$topCityIds,$source);
        }else{
            foreach ($topCities as $key => $value) {
                $topCities[$key] = array(
                                        'count' => $value,
                                        'source' => 'abroad'    
                                    );

            }
        }
        unset($cityArray);
        arsort($universityArray);
        $topUniversities = array_slice($universityArray, 0, 10,'true');
        $topUniversities = $this->_getTopUniversities($topUniversities);
        unset($universityArray);
        $responsesArray =array();

        arsort($countryArray);
        $countryArray = array_slice($countryArray, 0, 10,'true');
        $countryIds = array_keys($countryArray);
        $idToName = $this->OverviewModel->getCountryIdToName($countryIds);
        foreach ($idToName as $key => $value) {
            $idToNameArray[$value['countryId']] = $value['name'];
        }
        foreach ($countryArray as $key => $value) {
            $topCountries[$key] = array(
                                        'count' => $value,
                                        'name' => $idToNameArray[$key]
                                        );
        }
        //_p($topCountries);die;

        $responsesArray['topCategories'] = $topCategories;
        $responsesArray['topSubCategories'] = $topSubCategories;
        $responsesArray['topCities'] = $topCities;
        $responsesArray['topListings'] = $topUniversities;
        $responsesArray['topCountries'] = $topCountries;
        return $responsesArray;
    }

    private function _getDataForResponses($inputRequest, $team){

        $topCategories = $this->MISCommonLib->get('categories', $inputRequest, $team);
        $topSubcategories = $this->MISCommonLib->get('subcategories', $inputRequest, $team);
        $topCities = $this->MISCommonLib->get('cities', $inputRequest, $team);
        $topListings = $this->MISCommonLib->get('institutes', $inputRequest, $team);
        $topPages = $this->MISCommonLib->get('pages', $inputRequest, $team);

        $properTopCategories = array();
        foreach($topCategories as $oneCategory){
            $oneCategoryObject = new stdClass();
            $oneCategoryObject->Id = $oneCategory['key'];
            $oneCategoryObject->PivotName = $this->categoryRepository->find($oneCategory['key'])->getName();
            $oneCategoryObject->ScalarValue = $oneCategory['doc_count'];
            $properTopCategories[] = (array)$oneCategoryObject;
        }

        $properTopSubcategories = array();
        foreach($topSubcategories as $oneSubcategory){
            $oneCategoryObject = new stdClass();
            $oneCategoryObject->Id = $oneSubcategory['key'];
            $categoryInformation = $this->categoryRepository->find($oneSubcategory['key']);
            $parentId = $categoryInformation->getParentId();
            $parentName = $this->categoryRepository->find($parentId)->getName();
            $subcategoryName = $categoryInformation->getName() . " [".$parentName."]";
            $oneCategoryObject->PivotName = $subcategoryName;
            $oneCategoryObject->ScalarValue = $oneSubcategory['doc_count'];
            $properTopSubcategories[] = (array)$oneCategoryObject;
        }

        $properCities = array();
        foreach($topCities as $oneCity){
            $oneCityObject = new stdClass();
            $oneCityObject->Id = $oneCity['key'];
            $oneCityObject->PivotName = $this->locationRepository->findCity($oneCity['key'])->getName();
            $oneCityObject->ScalarValue = $oneCity['doc_count'];
            $properCities[] = (array)$oneCityObject;
        }

        $properListings = array();
        foreach($topListings as $oneListing){
            $oneListingObject = new stdClass();
            if($team == 'abroad' || $team == 'studyabroad'){
                $listingRepoObject = $this->universityRepo->find($oneListing['key']);
                $oneListingObject->Id = $oneListing['key'];
                $oneListingObject->PivotName = htmlentities($listingRepoObject->getName());
                try{
                    $oneListingObject->URL = $listingRepoObject->getURL();
                } catch(Exception $e){
                    $oneListingObject->URL = '';
                }
                $oneListingObject->CountryName = $listingRepoObject->getLocation()->getCountry()->getName();
                $oneListingObject->ScalarValue = $oneListing['doc_count'];
                $properListings[] = (array)$oneListingObject;
            } else {
                $listingRepoObject = $this->instituteRepo->find($oneListing['key']);
                $oneListingObject->Id = $oneListing['key'];
                $oneListingObject->PivotName = htmlentities($listingRepoObject->getName());
                if($listingRepoObject->getId()){
                    $oneListingObject->URL = $listingRepoObject->getURL();
                } else {
                    $oneListingObject->URL = '';
                }
                $oneListingObject->CountryName = $listingRepoObject->getMainLocation()->getCountry()->getName();
                $oneListingObject->ScalarValue = $oneListing['doc_count'];
                $properListings[] = (array)$oneListingObject;
            }
        }

        $properPages = array();
        foreach($topPages as $onePage){
            $onePageObject = new stdClass();
            $onePageObject->Id = $onePage['key'];
            $onePageObject->PivotName = $onePage['key'];
            $onePageObject->ScalarValue = $onePage['doc_count'];
            $properPages[] = (array)$onePageObject;
        }

        if($team == 'abroad' || $team == 'studyabroad'){
            $topCountries = $this->MISCommonLib->get('countries', $inputRequest, $team);

            $properCountries = array();
            foreach($topCountries as $oneCountry){
                $oneCountryObject = new stdClass();
                $oneCountryObject->Id = $oneCountry['key'];
                $oneCountryObject->PivotName = $this->locationRepository->findCountry($oneCountry['key'])->getName();
                $oneCountryObject->ScalarValue = $oneCountry['doc_count'];
                $properCountries[] = (array)$oneCountryObject;
            }
            return array(
                'topCategories' => $properTopCategories,
                'topSubCategories' => $properTopSubcategories,
                'topCities' => $properCities,
                'topListings' => $properListings,
                'topPages' => $properPages,
                'topCountries' => $properCountries,
            );
        } else {
            return array(
                'topCategories' => $properTopCategories,
                'topSubCategories' => $properTopSubcategories,
                'topCities' => $properCities,
                'topListings' => $properListings,
                'topPages' => $properPages,
            );
        }
    }

    private function _prepareDataForBarGraphForGrowth($topData,$MOMData,$YOYData,$flag=0,$barGraphWidth){
        //_p($topData);_p($MOMData);_p($YOYData);die;
        foreach ($topData as $key => $value) {
            if($MOMData[$key]){
                $responseDiff = $value['count'] - $MOMData[$key];
                $momGrowth[$key] = number_format((($responseDiff*100)/$MOMData[$key]), 0);
            }else{
                $momGrowth[$key] = number_format((100), 0);
            }
            
            $responseDiff =0;
            if($YOYData[$key]){
                $responseDiff = $value['count'] - $YOYData[$key];
                $yoyGrowth[$key] = number_format((($responseDiff*100)/$YOYData[$key]), 0);
            }else{
                $yoyGrowth[$key] = number_format((100), 0);
            }
            
        }       
        //_p($topData);_p('-------------------');_p($momGrowth);_p('------------------');_p($yoyGrowth);die;
        $result = $this->prepareDataForBargraph($topData,'true',1,$barGraphWidth,$momGrowth,$yoyGrowth,false);
        return $result;
    }

    //-------------------------------Responses Stats End---------------------------------------------------------

    //-------------------------------------Repository------------------------------------------------------------
    private function _getTopCities($topCities,$topCityIds,$source){
        $cityObj = $this->locationRepository->findMultipleCities($topCityIds);

        foreach ($topCities as $key => $value) {
            if($source){
                $topCities[$key] = array(
                                    'name' => $cityObj[$key]->getName(),
                                    'count' => $value,
                                    );            
            }else{
                $topCities[$key] = array(
                                    'name' => $cityObj[$key]->getName(),
                                    'count' => $value['count'],
                                    'source' => $value['source']
                                    ); 
            }
             
       }
        return $topCities;
    }
            
    private function _getTopUniversities($topUniversities){

        $topUniversityIds = array_keys($topUniversities);
        
        $universityObj = $this->universityRepo->findMultiple($topUniversityIds);

        foreach ($topUniversities as $key => $value) {
            $pageURL = $universityObj[$key]->getURL();
            $uniName = $universityObj[$key]->getName();
            $countryName= $universityObj[$key]->getLocation()->getCountry()->getName();
           
            $topUniversities[$key] = array(
                                        'count' => $value,
                                        'countryName' => $countryName,
                                        'name' => $uniName,
                                        'url' => $pageURL
                                        );    
        }
        return $topUniversities;
    }

    private function _getTopInstitutes($topInstitutes,$source){
        $topInstituteIds = array_keys($topInstitutes);
        $instituteObj = $this->instituteRepo->findMultiple($topInstituteIds);
        foreach ($topInstitutes as $key => $value) {
            $pageURL = $instituteObj[$key]->getURL();
            $instituteName = $instituteObj[$key]->getName();

            $topInstitutes[$key] = array(
                                                    'count' => $value,
                                                    'countryName' => 'India',
                                                    'name' => $instituteName,
                                                    'url' => $pageURL
                                                    );    
              
        }
        return $topInstitutes;
    }

    private function _getSubCatToCategory($subCategoryArray,$source){
        arsort($subCategoryArray);
        $i=0;
        foreach ($subCategoryArray as $key => $value) {
            $subCategoryIdsArray[] = $key;
            if($i<10){
                $topSubCategories[$key] = $value;
                $topSubCategoriesArray[] = $key;
                $i++;
            }
        }
        $subCategoryObject = $this->categoryRepository->findMultiple($subCategoryIdsArray);
        foreach ($subCategoryArray as $key => $value) {
            $categoryId = $subCategoryObject[$key]->getparentId();
            $categoryIds[$categoryId] += $value;
            if($source){
                if($topSubCategories[$key]){
                    $subCategoryName = $subCategoryObject[$key]->getName();
                    $topSubCategories[$key] = array(
                                                    'count' => $value,
                                                    'name' => $subCategoryName
                                                    );
                }    
            }  
        }
        arsort($categoryIds);
        $topCategories =array_slice($categoryIds, 0, 10,'true');

        if($source){
            
            $topCategoriesArray = array_keys($categoryIds);

            $categoryObject = $this->categoryRepository->findMultiple($topCategoriesArray);
            foreach ($topCategories as $key => $value) {
                $categoryName = $categoryObject[$key]->getName();
                $topCategories[$key] = array(
                                            'count' => $value,
                                            'name' => $categoryName
                                            );
            }
        }
        $data['topCategories'] = $topCategories;
        $data['topSubCategories'] = $topSubCategories;
        return $data;
    }

    private function _getTopCategories($topCategories,$topsubCategories,$source){
        foreach ($topCategories as $key => $value) {
            $topCategoryIds[] = $key;
        }
    
        foreach ($topsubCategories as $key => $value) {
            $topCategoryIds[] = $key;
        }
        $categoryObject = $this->categoryRepository->findMultiple($topCategoryIds);

        foreach ($topCategories as $key => $value) {
            if($source){
                $topCategories[$key] = array(
                                        'name' => $categoryObject[$key]->getName(),
                                        'count' => $value
                                        );    
            }else{
                $topCategories[$key] = array(
                                        'name' => $categoryObject[$key]->getName(),
                                        'count' => $value['count'],
                                        'source' => $value['source']
                                        );    
            }
            
        }
        foreach ($topsubCategories as $key => $value) {
            $topsubCategories[$key] = array(
                                        'name' => $categoryObject[$key]->getName(),
                                        'count' => $value
                                            );
        }
        $data['topCategories'] = $topCategories;
        $data['topsubCategories'] = $topsubCategories;
        return $data;
    }

    //-------------------------------------Repository End--------------------------------------------------

    //------------------------------For Top Registration Pages Stats---------------------------------------
    function buildCacheForRegistrationForPage($dateRange, $source,$showGrowth='true',$sourceApplication=''){
        $count = 10;
        if($showGrowth == 'true'){
            $topPagesData = $this->getPages($count,'registration',$source,$sourceApplication,$dateRange,$showGrowth);
            $topPagesData = $this->_prepareDataForBarGraphForGrowthForRegistration($topPagesData,0,0,0);
            $topPagesData = json_encode($topPagesData);
            return $topPagesData;    
        }else{
            $topPagesData = $this->getPages($count,'registration',$source,$sourceApplication,$dateRange,$showGrowth);
            $topPageData = $this->prepareDataForBargraph($topPagesData,'false',0,0);
            return json_encode($topPageData);
        }
    }

    function buildCacheForRegistrationForCategory($dateRange, $source,$showGrowth='true',$sourceApplication=''){
        $count = 10;
        if($showGrowth == 'true'){
            $topCategoriesData = $this->getCategories($count,'registration',$source,$sourceApplication,$dateRange,$showGrowth);
            //_p($topCategoriesData);die;
            $topCategoriesData = $this->_prepareDataForBarGraphForGrowthForRegistration($topCategoriesData,1,1,0);
            $topCategoriesData = json_encode($topCategoriesData);
            return $topCategoriesData;    
        }else{
            $topCategoriesData = $this->getCategories($count,'registration',$source,$sourceApplication,$dateRange,$showGrowth);
            $topCategoryData = $this->prepareDataForBargraph($topCategoriesData,'false',1,0);
            return json_encode($topCategoryData);
        }
    }

    function buildCacheForRegistrationForSubCategory($dateRange, $source,$showGrowth='true',$sourceApplication=''){
        $count = 10;
        if($showGrowth == 'true'){
            $topSubCategoriesData = $this->getSubcategories($count,'registration',$source,$sourceApplication,$dateRange,$showGrowth);
            //_p($topSubCategoriesData);die;
            foreach ($topSubCategoriesData as $key => $value) {
                $subCatIds[] = $key;
            }
            $subCatToCategoryName = $this->_getSubCatToCategoryName($subCatIds);
            foreach ($subCatToCategoryName as $key => $value) {
                $subCateToName[$value['id']] = $value['categoryId'];
            }
            foreach ($topSubCategoriesData as $key => $value) {
                $topSubCategoriesData[$key]['name'] =$value['name'].' ['.$subCateToName[$key].']'; 
            }
            
            $topSubCategoriesData = $this->_prepareDataForBarGraphForGrowthForTraffic($topSubCategoriesData,1,1,1);
            return json_encode($topSubCategoriesData);
        }else{
            $topSubCategoriesData = $this->getSubcategories($count,'registration',$source,$sourceApplication,$dateRange,$showGrowth);
            //_p($topSubCategoriesData);die;
            foreach ($topSubCategoriesData as $key => $value) {
                $subCatIds[] = $key;
            }
            $subCatToCategoryName = $this->_getSubCatToCategoryName($subCatIds);
            foreach ($subCatToCategoryName as $key => $value) {
                $subCateToName[$value['id']] = $value['categoryId'];
            }
            foreach ($topSubCategoriesData as $key => $value) {
                $topSubCategoriesData[$key]['name'] =$value['name'].' ['.$subCateToName[$key].']';
            }
            foreach ($topSubCategoriesData as $key => $value) {
                $topDataForSubCategory[$value['name']] = $value['count'];
            }
            $topSubCategoryData = $this->prepareDataForBargraph($topDataForSubCategory,'false',0,1);
            return json_encode($topSubCategoryData);
        }
    }

    function buildCacheForRegistrationForCity($dateRange, $source,$showGrowth='true',$sourceApplication=''){
        $count = 10;
        if($showGrowth == 'true'){
            $topCitiesDataForRegistration = $this->getCities($count,'registration',$source,$sourceApplication,$dateRange,$showGrowth);
            $topCitiesDataForRegistration = $this->_prepareDataForBarGraphForGrowthForTraffic($topCitiesDataForRegistration,1,1,0);
            $topCitiesDataForRegistration = json_encode($topCitiesDataForRegistration);
            return $topCitiesDataForRegistration;    
        }else{
            $topCitiesDataForRegistration = $this->getCities($count,'registration',$source,$sourceApplication,$dateRange,$showGrowth);
            $topCityData = $this->prepareDataForBargraph($topCitiesDataForRegistration,'false',1,0);
            return json_encode($topCityData);
        }
    }

    function buildCacheForRegistrationForCountries($dateRange, $source,$showGrowth='true',$sourceApplication=''){
        //_p($dateRange);_p($source);_p($showGrowth);_p($sourceApplication);die;
        $count = 10;
        if($showGrowth == 'true'){
            $topCountriesDataForRegistration = $this->getCountries($count,'registration',$source,$sourceApplication,$dateRange,$showGrowth);
            //_p($topCountriesDataForRegistration);die;
            $topCountriesDataForRegistration = $this->_prepareDataForBarGraphForGrowthForTraffic($topCountriesDataForRegistration,1,1,0);
            $topCountriesDataForRegistration = json_encode($topCountriesDataForRegistration);
            return $topCountriesDataForRegistration;    
        }else{
            $topCountriesDataForRegistration = $this->getCountries($count,'registration',$source,$sourceApplication,$dateRange,$showGrowth);
            
            $topCountryData = $this->prepareDataForBargraph($topCountriesDataForRegistration,'false',1,0);
            return json_encode($topCountryData);
        }
    }

    function buildCacheForRegistrationForDesiredCountries($dateRange, $source,$showGrowth='true',$sourceApplication=''){
        $count = 10;
        if($showGrowth == 'true'){
            $topDesiredCountriesDataForRegistration = $this->getDesiredCountries($count,'registration',$source,$sourceApplication,$dateRange,$showGrowth);
            //_p($topCountriesDataForRegistration);die;
            $topDesiredCountriesDataForRegistration = $this->_prepareDataForBarGraphForGrowthForRegistrationForDesiredCountry($topDesiredCountriesDataForRegistration,1,1,0);
            $topDesiredCountriesDataForRegistration = json_encode($topDesiredCountriesDataForRegistration);
            return $topDesiredCountriesDataForRegistration;    
        }else{
            $topDesiredCountriesDataForRegistration = $this->getDesiredCountries($count,'registration',$source,$sourceApplication,$dateRange,$showGrowth);
            //_p($topCountriesDataForRegistration);die;
            foreach ($topDesiredCountriesDataForRegistration as $key => $value) {
                $topDataForDesiredCountry[$value->CountryName] = $value->ScalarValue;
            }
            $topDesiredCountryData = $this->prepareDataForBargraph($topDataForDesiredCountry,'false',0,0);
            return json_encode($topDesiredCountryData);
        }
    }

    private function _prepareDataForBarGraphForGrowthForRegistrationForDesiredCountry($topData,$flag=0,$checkKey=0,$barGraphWidth){
        //_p($topData);die;
        foreach ($topData as $key => $value) {
            if($value->changeMOM){
                $responseDiff = $value->changeMOM;
                unset($topData[$key]->changeMOM);
                $momGrowth[$value->country] = number_format((($responseDiff*100)/$value->changeMOMValue), 0);
            }else{
                $momGrowth[$value->country] = number_format((100), 0);
            }
            //$momGrowth[$value->country] = number_format((($responseDiff*100)/$value->ScalarValue), 0);
            $responseDiff =0;
            if($value->changeYOY){
                $responseDiff = $value->changeYOY;
                unset($topData[$key]->changeYOY);
                $yoyGrowth[$value->country] = number_format((($responseDiff*100)/$value->changeYOYValue), 0);
            }else{
                $yoyGrowth[$value->country] = number_format((100), 0);
            }
            

            if($checkKey){
                $topData[$value->CountryName] = array(
                                                'count' => $value->ScalarValue,
                                                'key' => $value->country
                                                );
                unset($topData[$key]);   
            }else{
                $topData[$value['CountryName']] = $value['ScalarValue'];
                unset($topData[$key]); 
            } 
        }     
        if($checkKey){
            foreach ($topData as $key => $value) {
                $topData[$value['key']] = array(
                                                'count' => $value['count'],
                                                'name' => $key
                                                );
                unset($topData[$key]);
            }
        }  
        //_p($topData);_p('-------------------');_p($momGrowth);_p('------------------');_p($yoyGrowth);die;
        $result = $this->prepareDataForBargraph($topData,'true',$flag,$barGraphWidth,$momGrowth,$yoyGrowth,false);
        return $result;
    }

    private function _prepareDataForBarGraphForGrowthForRegistrationForCountry($topData,$flag=0,$checkKey=0,$barGraphWidth){
        //_p($topData);die;
        foreach ($topData as $key => $value) {
            if($value->changeMOM){
                $responseDiff = $value->changeMOM;
                unset($topData[$key]->changeMOM);
                $momGrowth[$value->country] = number_format((($responseDiff*100)/$value->changeMOMValue), 0);
            }else{
                $momGrowth[$value->country] = number_format((100), 0);
            }
            
            $responseDiff =0;
            if($value->changeYOY){
                $responseDiff = $value->changeYOY;
                unset($topData[$key]->changeYOY);
                $yoyGrowth[$value->country] = number_format((($responseDiff*100)/$value->changeYOYValue), 0);
            }else{
                $yoyGrowth[$value->country] = number_format((100), 0);
            }

            if($checkKey){
                $topData[$value->CountryName] = array(
                                                'count' => $value->ScalarValue,
                                                'key' => $value->country
                                                );
                unset($topData[$key]);   
            }else{
                $topData[$value['CountryName']] = $value['ScalarValue'];
                unset($topData[$key]); 
            } 
        }     
        if($checkKey){
            foreach ($topData as $key => $value) {
                $topData[$value['key']] = array(
                                                'count' => $value['count'],
                                                'name' => $key
                                                );
                unset($topData[$key]);
            }
        }  
        //_p($topData);_p('-------------------');_p($momGrowth);_p('------------------');_p($yoyGrowth);die;
        $result = $this->prepareDataForBargraph($topData,'true',$flag,$barGraphWidth,$momGrowth,$yoyGrowth,false);
        return $result;
    }

    private function getCities($count, $type, $team, $deviceType, $dateRange,$showGrowth='true')
        {
    		$topCities = $this->OverviewModel->getCities($count, $type, $team, $deviceType, $dateRange);
            // Top Subcategories delta
            if ($showGrowth == 'true') {

                $topCitiesDelta = $this->getDelta($count, 'topCities', $type, $topCities, $team, $deviceType, $dateRange); 
                foreach ($topCities as $key => $onePage) {
                    foreach ($topCitiesDelta['mom'] as $oneDelta) {
                        if ($oneDelta['key'] == $onePage['key']) {
                            $topCities[ $key ]['changeMOM'] = $onePage['doc_count'] - $oneDelta['doc_count'];
                            $topCities[ $key ]['changeMOMValue'] = $oneDelta['doc_count'];
                            break;
                        }
                    }
                    foreach ($topCitiesDelta['yoy'] as $oneDelta) {
                        if ($oneDelta['key'] == $onePage['key']) {
                            $topCities[$key]['changeYOY'] = $onePage['doc_count'] - $oneDelta['doc_count'];
                            $topCities[$key]['changeYOYValue'] = $oneDelta['doc_count'];
                            break;
                        }
                    }
                }
                foreach ($topCities as $key => $value) {
                    $topCity[$value['key']]['count'] = $value['doc_count'];
                    $topCity[$value['key']]['name'] = $value['name'];

                    if($value['changeMOM']){
                        $topCity[$value['key']]['changeMOM'] = number_format((($value['changeMOM']*100)/$value['changeMOMValue']), 0);
                    }else{
                        $topCity[$value['key']]['changeMOM'] = 100;
                    }

                    if($value['changeYOY']){
                        $topCity[$value['key']]['changeYOY'] = number_format((($value['changeYOY']*100)/$value['changeYOYValue']), 0);
                    }else{
                        $topCity[$value['key']]['changeYOY'] = 100;
                    }

                    unset($topCity[$key]);
                }
            }else{
                foreach ($topCities as $key => $value) {
                    $topCity[$value['key']]['count'] = $value['doc_count'];
                    $topCity[$value['key']]['name'] = $value['name'];
                    unset($topCities[$key]);
                }
            }
            return $topCity;
    	
    }

    private function getDesiredCountries($count, $type, $team, $deviceType, $dateRange,$showGrowth='true')
    {
        $topDesiredCountries1 = $this->OverviewModel->getDesiredCountries($count, $type, $team, $deviceType, $dateRange);
        $topDesiredCountries2 = $this->OverviewModel->getDesiredCountries($count, $type, $team, $deviceType, $dateRange,2); //for prefCountry2 Column
        $topDesiredCountries3 = $this->OverviewModel->getDesiredCountries($count, $type, $team, $deviceType, $dateRange,3); //for prefCountry3 Column
        $topDesiredCountries = array();
        foreach ($topDesiredCountries1 as $key => $value) {
            $topDesiredCountries[$value['key']] = $value['doc_count'];
        }
        foreach ($topDesiredCountries2 as $key => $value) {
            $topDesiredCountries[$value['key']] += $value['doc_count'];   
        }
        foreach ($topDesiredCountries3 as $key => $value) {
            $topDesiredCountries[$value['key']] += $value['doc_count'];
        }
        arsort($topDesiredCountries);
        $i = 0;
        $topTenDesiredCountries = array();
        foreach ($topDesiredCountries as $key => $value) {
                    $topTenDesiredCountries[$key] = $value;
                    if($i++ == 9)
                        break;
                }        

        foreach ($topTenDesiredCountries as $key => $value) {
            $sourceWiseSingleSplit = new stdClass();
            $sourceWiseSingleSplit->country = $key;
            $sourceWiseSingleSplit->ScalarValue = $value;
            $DesiredCountries[] = $sourceWiseSingleSplit;
        }
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder    = new LocationBuilder();
        $this->locationRepository = $locationBuilder->getLocationRepository();
        //$this->locationRepository->findCountry
        foreach ($DesiredCountries as $oneCountry) {
            $countryId            = $oneCountry->country;
            $countryName =ucfirst($this->locationRepository->findCountry($countryId)->getName());
            $oneCountry->CountryName = $countryName;
        }
        $registration['topDesiredCountries'] = $DesiredCountries;
        if ($showGrowth == 'true') {
            $topCountriesDelta = $this->getDelta($count, 'topDesiredCountries', 'registration', $registration['topDesiredCountries'], $team, $deviceType, $dateRange);
            foreach ($registration['topDesiredCountries'] as $key => $onePage) {
                foreach ($topCountriesDelta['mom'] as $oneDelta) {
                    if ($oneDelta->country == $onePage->country) {
                        $registration['topDesiredCountries'][ $key ]->changeMOM = $onePage->ScalarValue - $oneDelta->ScalarValue;
                        $registration['topDesiredCountries'][ $key ]->changeMOMValue = $oneDelta->ScalarValue;
                        break;
                    }
                }

                foreach ($topCountriesDelta['yoy'] as $oneDelta) {
                    if ($oneDelta->country == $onePage->country) {
                        $registration['topDesiredCountries'][ $key ]->changeYOY = $onePage->ScalarValue - $oneDelta->ScalarValue;
                        $registration['topDesiredCountries'][ $key ]->changeYOYValue = $oneDelta->ScalarValue;
                        break;
                    }
                }
            }
        }
        //_p($registration['topDesiredCountries']);die;
        return $registration['topDesiredCountries'];
    }

    private function getCategories($count, $type, $team, $deviceType, $dateRange,$showGrowth='true')
    {
        $topCategories = $this->OverviewModel->getCategories($count, $type, $team, $deviceType, $dateRange);
        //_p($topCategories);die;
        
        if ($showGrowth == 'true') {
            $topCategoriesDelta = $this->getDelta($count, 'topCategories', $type, $topCategories, $team, $deviceType, $dateRange);
            foreach ($topCategories as $key => $onePage) {
                foreach ($topCategoriesDelta['mom'] as $oneDelta) {
                    if ($oneDelta['key'] == $onePage['key']) {
                        $topCategories[ $key ]['changeMOM'] = $onePage['doc_count'] - $oneDelta['doc_count'];
                        $topCategories[ $key ]['changeMOMValue'] = $oneDelta['doc_count'];
                    }
                }

                foreach ($topCategoriesDelta['yoy'] as $oneDelta) {
                    if ($oneDelta['key'] == $onePage['key']) {
                        $topCategories[ $key ]['changeYOY'] = $onePage['doc_count'] - $oneDelta['doc_count'];
                        $topCategories[ $key ]['changeYOYValue'] = $oneDelta['doc_count'];
                    }
                }
            }

            foreach ($topCategories as $key => $value) {
                $topCategory[$value['key']]['count'] = $value['doc_count'];
                $topCategory[$value['key']]['name'] = $value['category_name'];
                
                if($value['changeMOM']){
                    $topCategory[$value['key']]['changeMOM'] = number_format((($value['changeMOM']*100)/$value['changeMOMValue']), 0);
                }else{
                    $topCategory[$value['key']]['changeMOM'] = 100;
                }
                
                if($value['changeYOY']){
                    $topCategory[$value['key']]['changeYOY'] = number_format((($value['changeYOY']*100)/$value['changeYOYValue']), 0);
                }else{
                    $topCategory[$value['key']]['changeYOY'] = 100;
                }

                unset($topCategories[$key]);
            }
        }else{
            foreach ($topCategories as $key => $value) {
                $topCategory[$value['key']]['count'] = $value['doc_count'];
                $topCategory[$value['key']]['name'] = $value['category_name'];
                unset($topCategories[$key]);
            }
        }
        
       return $topCategory;
    }

    private function getPages($count, $type, $team, $deviceType, $dateRange,$showGrowth='true')
    {
        $topPages = $this->OverviewModel->getPages($count, $type, $team, $deviceType, $dateRange);
        //$showGrowth = 'false';
        if ($showGrowth == 'true') {
            $topPagesDelta = $this->getDelta($count, 'topPages', $type, $topPages, $team, $deviceType, $dateRange);
            foreach ($topPages as $key => $onePage) {              
                foreach ($topPagesDelta['mom'] as $oneDelta) {
                    if ($oneDelta['key'] == $onePage['key']) {
                        $topPages[ $key ]['changeMOM'] = $onePage['doc_count'] - $oneDelta['doc_count'];
                        $topPages[ $key ]['changeMOMValue'] = $oneDelta['doc_count'];
						break;
                    }
                }

                foreach ($topPagesDelta['yoy'] as $oneDelta) {
                    if ($oneDelta['key'] == $onePage['key']) {
                        $topPages[ $key ]['changeYOY'] = $onePage['doc_count'] - $oneDelta['doc_count'];
                        $topPages[ $key ]['changeYOYValue'] = $oneDelta['doc_count'];
                        break;
                    }
                }
            }
            foreach ($topPages as $key => $value) {
                $temp = $value;
                $topPages[$value['key']]['count'] = $value['doc_count'];
                
                if($value['changeMOM']){
                    $topPages[$value['key']]['changeMOM'] = number_format((($value['changeMOM']*100)/$value['changeMOMValue']), 0);
                }else{
                    $topPages[$value['key']]['changeMOM'] = 100;
                }
                
                if($value['changeYOY']){
                    $topPages[$value['key']]['changeYOY'] = number_format((($value['changeYOY']*100)/$value['changeYOYValue']), 0);
                }else{
                    $topPages[$value['key']]['changeYOY'] = 100;
                }
                unset($topPages[$key]);
            }
        }else{
            foreach ($topPages as $key => $value) {
                $temp = $value;
                $topPages[$value['key']] = $value['doc_count'];
                unset($topPages[$key]);
            }
        }
        return $topPages;
    }

    private function _prepareDataForBarGraphForGrowthForRegistration($topData,$flag=0,$checkKey=0,$barGraphWidth=0){
        //_p($topData);die;
        foreach ($topData as $key => $value) {  
            $momGrowth[$key] = $value['changeMOM'];
            $yoyGrowth[$key] = $value['changeYOY'];

            if(!$checkKey){
                $topData[$key] = $value['count'];
            }
        } 
        //_p($topData);_p('-------------------');_p($momGrowth);_p('------------------');_p($yoyGrowth);die;
        $result = $this->prepareDataForBargraph($topData,'true',$flag,$barGraphWidth,$momGrowth,$yoyGrowth);
        return $result;
    }

    private function getDelta($count = 10, $dimension='', $aspect='', $baseData=array(), $team='', $deviceType='', $dateRange = array())
    {
        $dateRangeMOM = array(
            'startDate' => date('Y-m-d', strtotime($dateRange['startDate'].' -30 days')),
            'endDate' => date('Y-m-d', strtotime($dateRange['endDate'].' -30 days')),
        );
        $dateRangeYOY = array(
            'startDate' => date('Y-m-d', strtotime($dateRange['startDate'].' -1 year')),
            'endDate' => date('Y-m-d', strtotime($dateRange['endDate'].' -1 year')),
        );
        switch($aspect){
            case 'traffic':
                switch($dimension){

                    case 'topPages':
                        $pageIdentifier = array();
                        foreach($baseData as $value){
                            $pageIdentifier[] = $value['key'];
                        }

                        return $this->getDeltaPages($count, $pageIdentifier, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY);

                    case 'topCategories':
                        $categories = array();
                        foreach($baseData as $value){
                            $categories[] = $value['key'];
                        }

                        return $this->getDeltaCategories($count, $categories, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY);

                    case 'topSubcategories':
                        $subcategories = array();
                        foreach($baseData as $value){
                            $subcategories[] = $value['key'];
                        }
                        return $this->getDeltaSubcategories($count, $subcategories, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY);

                    case 'topCountries':
                        $countries = array();
                        foreach($baseData as $value){
                            $countries[] = $value['key'];
                        }
                        return $this->getDeltaCountries($count, $countries, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY);
                    case 'topCities':
                        $cities = array();
                        foreach($baseData as $value){
                            $cities[] = $value['key'];
                        }
                        return $this->getDeltaCities($count, $cities, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY);
                }
                break;
            case 'registration':
                switch($dimension){

                    case 'topPages':
                        $pageIdentifier = array();
                        foreach($baseData as $value){
                            $pageIdentifier[] = $value['key'];
                        }
                        return $this->getDeltaPages($count, $pageIdentifier, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY);

                    case 'topCategories':
                        $categories = array();
                        foreach($baseData as $value){
                            $categories[] = $value['key'];
                        }

                        return $this->getDeltaCategories($count, $categories, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY);

                    case 'topSubcategories':
                        $subcategories = array();
                        foreach($baseData as $value){
                            $subcategories[] = $value['key'];
                        }

                        return $this->getDeltaSubcategories($count, $subcategories, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY);
                    case 'topCities':
                        $cities = array();
                        foreach($baseData as $value){
                            $cities[] = $value['key'];
                        }
                        return $this->getDeltaCities($count, $cities, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY);

                    case 'topCountries':
                        $countries = array();
                        foreach($baseData as $value){
                            $countries[] = $value['key'];
                        }
                        return $this->getDeltaCountries($count, $countries, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY);
            
                    case 'topDesiredCountries':
                        $desiredCountries = array();
                        foreach($baseData as $value){
                            $desiredCountries[] = $value->country;
                        }
                        return $this->getDeltaDesiredCountries($count, $desiredCountries, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY);
                }
                break;
            case 'response':
                break;

        }
    }

    private function getDeltaPages($count, $pageIdentifier, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY)
    {
        return $this->OverviewModel->getDeltaPages($count, $pageIdentifier, $aspect, $team, $deviceType, array('mom' => $dateRangeMOM, 'yoy' => $dateRangeYOY));
    }

    private function getDeltaCategories($count, $categories, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY)
    {
        return $this->OverviewModel->getDeltaCategories($count, $categories, $aspect, $team, $deviceType, array('mom' => $dateRangeMOM, 'yoy' => $dateRangeYOY));
    }

    private function getDeltaSubcategories($count, $subcategories, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY)
    {
        return $this->OverviewModel->getDeltaSubcategories($count, $subcategories, $aspect, $team, $deviceType, array('mom' => $dateRangeMOM, 'yoy' => $dateRangeYOY));
    }

    private function getDeltaCities($count, $cities, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY)
    {
        return $this->OverviewModel->getDeltaCities($count, $cities, $aspect, $team, $deviceType, array('mom' => $dateRangeMOM, 'yoy' => $dateRangeYOY));
    }

    private function getDeltaCountries($count, $countries, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY)
    {
        return $this->OverviewModel->getDeltaCountries($count, $countries, $aspect, $team, $deviceType, array('mom' => $dateRangeMOM, 'yoy' => $dateRangeYOY));
    }

    private function getDeltaDesiredCountries($count, $cities, $aspect, $team, $deviceType, $dateRangeMOM, $dateRangeYOY)
    {
        $topDesiredCountries1 = $this->OverviewModel->getDeltaDesiredCountries($count, $cities, $aspect, $team, $deviceType, array('mom' => $dateRangeMOM, 'yoy' => $dateRangeYOY));
        $topDesiredCountries2 = $this->OverviewModel->getDeltaDesiredCountries($count, $cities, $aspect, $team, $deviceType, array('mom' => $dateRangeMOM, 'yoy' => $dateRangeYOY),2);
        $topDesiredCountries3 = $this->OverviewModel->getDeltaDesiredCountries($count, $cities, $aspect, $team, $deviceType, array('mom' => $dateRangeMOM, 'yoy' => $dateRangeYOY),3);
        $momDesiredCountries = array();
        foreach ($topDesiredCountries1['mom'] as $key => $value) {
            $momDesiredCountries[$value['key']] = $value['doc_count'];
        }
        foreach ($topDesiredCountries2['mom'] as $key => $value) {
            $momDesiredCountries[$value['key']] += $value['doc_count'];
        }
        foreach ($topDesiredCountries3['mom'] as $key => $value) {
            $momDesiredCountries[$value['key']] += $value['doc_count'];
        }
        $yoyDesiredCountries = array();
        foreach ($topDesiredCountries1['yoy'] as $key => $value) {
            $yoyDesiredCountries[$value['key']] = $value['doc_count'];
        }
        foreach ($topDesiredCountries2['yoy'] as $key => $value) {
            $yoyDesiredCountries[$value['key']] += $value['doc_count'];
        }
        foreach ($topDesiredCountries3['yoy'] as $key => $value) {
            $yoyDesiredCountries[$value['key']] += $value['doc_count'];
        }
        $momArray = array();
        foreach ($momDesiredCountries as $key => $value) {
            $DesiredCountriesSplit = new stdClass();
            $DesiredCountriesSplit->country = $key;
            $DesiredCountriesSplit->ScalarValue = $value;
            $momArray[] = $DesiredCountriesSplit;
        }
        $yoyArray = array();
        foreach ($yoyDesiredCountries as $key => $value) {
            $DesiredCountriesSplit = new stdClass();
            $DesiredCountriesSplit->country = $key;
            $DesiredCountriesSplit->ScalarValue = $value;
            $yoyArray[] = $DesiredCountriesSplit;   
        }
        return array('mom' => $momArray,'yoy' => $yoyArray);
    }
    private function getSubcategories($count, $type, $team, $deviceType, $dateRange,$showGrowth='true')
    {

            $topSubcategories = $this->OverviewModel->getSubcategories($count, $type, $team, $deviceType, $dateRange);
            // Top Subcategories delta
            if ($showGrowth == 'true') {
                $topSubCategoriesDelta = $this->getDelta($count, 'topSubcategories', $type, $topSubcategories, $team, $deviceType, $dateRange);
                foreach ($topSubcategories as $key => $onePage) {
                    foreach ($topSubCategoriesDelta['mom'] as $oneDelta) {
                        if ($oneDelta['key'] == $onePage['key']) {
                            $topSubcategories[ $key ]['changeMOM'] = $onePage['doc_count'] - $oneDelta['doc_count'];
                            $topSubcategories[ $key ]['changeMOMValue'] = $oneDelta['doc_count'];
                        }
                    }

                    foreach ($topSubCategoriesDelta['yoy'] as $oneDelta) {
                        if ($oneDelta['key'] == $onePage['key']) {
                            $topSubcategories[ $key ]['changeYOY'] = $onePage['doc_count'] - $oneDelta['doc_count'];
                            $topSubcategories[ $key ]['changeYOYValue'] = $oneDelta['doc_count'];
                        }
                    }
                }
                foreach ($topSubcategories as $key => $value) {
                    $topSubCategory[$value['key']]['count'] = $value['doc_count'];
                    $topSubCategory[$value['key']]['name'] = $value['category_name'];

                    if($value['changeMOM']){
                        $topSubCategory[$value['key']]['changeMOM'] = number_format((($value['changeMOM']*100)/$value['changeMOMValue']), 0);
                    }else{
                        $topSubCategory[$value['key']]['changeMOM'] = 100;
                    }
                    
                    if($value['changeYOY']){
                        $topSubCategory[$value['key']]['changeYOY'] = number_format((($value['changeYOY']*100)/$value['changeYOYValue']), 0);
                    }else{
                        $topSubCategory[$value['key']]['changeYOY'] = 100;
                    }

                    unset($topSubCategory[$key]);
                }
            }else{
                foreach ($topSubcategories as $key => $value) {
                    $topSubCategory[$value['key']]['count'] = $value['doc_count'];
                    $topSubCategory[$value['key']]['name'] = $value['category_name'];
                    unset($topSubcategories[$key]);
                }
            }
            return $topSubCategory;
        
    }
    private function getCountries($count, $type, $team, $deviceType, $dateRange,$showGrowth='true')
    {
            $topCountries = $this->OverviewModel->getCountries($count, $type, $team, $deviceType, $dateRange);
            // Top Countries delta
            if ($showGrowth == 'true') {
                $topCountriesDelta = $this->getDelta($count, 'topCountries', $type, $topCountries, $team, $deviceType, $dateRange);
                foreach ($topCountries as $key => $onePage) {
                    foreach ($topCountriesDelta['mom'] as $oneDelta) {
                        if ($oneDelta['key'] == $onePage['key']) {
                            $topCountries[ $key ]['changeMOM'] = $onePage['doc_count'] - $oneDelta['doc_count'];
                            $topCountries[ $key ]['changeMOMValue'] = $oneDelta['doc_count'];
                            break;
                        }
                    }

                    foreach ($topCountriesDelta['yoy'] as $oneDelta) {
                        if ($oneDelta['key'] == $onePage['key']) {
                            $topCountries[ $key ]['changeYOY'] = $onePage['doc_count'] - $oneDelta['doc_count'];
                            $topCountries[ $key ]['changeYOYValue'] = $oneDelta['doc_count'];
                            break;
                        }
                    }
                }
                if($type == 'traffic'){
                    foreach ($topCountries as $key => $value) {
                        $topCountry[$value['key']]['count'] = $value['doc_count'];
                        $topCountry[$value['key']]['name'] = $value['name'];

                        if($value['changeMOM']){
                            $topCountry[$value['key']]['changeMOM'] = number_format((($value['changeMOM']*100)/$value['changeMOMValue']), 0);
                        }else{
                            $topCountry[$value['key']]['changeMOM'] = 100;
                        }

                        if($value['changeYOY']){
                            $topCountry[$value['key']]['changeYOY'] = number_format((($value['changeYOY']*100)/$value['changeYOYValue']), 0);
                        }else{
                            $topCountry[$value['key']]['changeYOY'] = 100;
                        }
                        unset($topCountry[$key]);
                    }
                }else if($type == 'registration'){
                    foreach ($topCountries as $key => $value) {
                        $topCountry[$value['name']]['count'] = $value['doc_count'];
                        $topCountry[$value['name']]['countryId'] = $value['key'];

                        if($value['changeMOM']){
                            $topCountry[$value['name']]['changeMOM'] = number_format((($value['changeMOM']*100)/$value['changeMOMValue']), 0);
                        }else{
                            $topCountry[$value['name']]['changeMOM'] = 100;
                        }

                        if($value['changeYOY']){
                            $topCountry[$value['name']]['changeYOY'] = number_format((($value['changeYOY']*100)/$value['changeYOYValue']), 0);
                        }else{
                            $topCountry[$value['name']]['changeYOY'] = 100;
                        }
                        unset($topCountry[$key]);
                    }
                    foreach ($topCountry as $key => $value) {
                        $topCountry[$value['countryId']] = array(
                                                                'name' => $key,
                                                                'count' => $value['count'],
                                                                'changeMOM' => $value['changeMOM'],
                                                                'changeYOY' => $value['changeYOY']
                                                                );
                        unset($topCountry[$key]);
                    }
                }   
            }else{
                if($type == 'traffic'){
                    foreach ($topCountries as $key => $value) {
                        $topCountry[$value['key']]['count'] = $value['doc_count'];
                        $topCountry[$value['key']]['name'] = $value['key'];
                        unset($topCountries[$key]);
                    }
                }else if($type == 'registration'){
                    foreach ($topCountries as $key => $value) {
                        $topCountry[$value['key']]['count'] = $value['doc_count'];
                        $topCountry[$value['key']]['name'] = $value['name'];
                        unset($topCountries[$key]);
                    }
                }
                    
            }
            return $topCountry;  
    }

    private function _prepareDataForBarGraphForGrowthForRegistrationForCity($topData,$flag=0,$checkKey=0,$barGraphWidth){
        //_p($topData);die;
        foreach ($topData as $key => $value) {
            if($value->changeMOM){
                $responseDiff = $value->changeMOM;
                unset($topData[$key]->changeMOM);
                $momGrowth[$value->city] = number_format((($responseDiff*100)/$value->changeMOMValue), 0);
            }else{
                $momGrowth[$value->city] = number_format((100), 0);
            }
            
            $responseDiff =0;
            if($value->changeYOY){
                $responseDiff = $value->changeYOY;
                unset($topData[$key]->changeYOY);
                $yoyGrowth[$value->city] = number_format((($responseDiff*100)/$value->changeYOYValue), 0);
            }else{
                $yoyGrowth[$value->city] = number_format((100), 0);
            }
            
            if($checkKey){
                $topData[$value->CityName] = array(
                                                'count' => $value->ScalarValue,
                                                'key' => $value->city
                                                );
                unset($topData[$key]);   
            }else{
                $topData[$value['PageName']] = $value['ScalarValue'];
                unset($topData[$key]); 
            } 
        }     
        if($checkKey){
            foreach ($topData as $key => $value) {
                $topData[$value['key']] = array(
                                                'count' => $value['count'],
                                                'name' => $key
                                                );
                unset($topData[$key]);
            }
        }  
        //_p($topData);_p('-------------------');_p($momGrowth);_p('------------------');_p($yoyGrowth);die;
        $result = $this->prepareDataForBargraph($topData,'true',$flag,$barGraphWidth,$momGrowth,$yoyGrowth,false);
        return $result;
    }

    private function _prepareDataForBarGraphForGrowthForRegistrationForSubCat($topData,$flag=0,$checkKey=0,$barGraphWidth=0){
        //_p($topData);die;
        foreach ($topData as $key => $value) {
            if($value->changeMOM){
                $responseDiff = $value->changeMOM;
                unset($topData[$key]->changeMOM);
                $momGrowth[$value->SubCategoryId] = number_format((($responseDiff*100)/$value->changeMOMValue), 0);
            }else{
                $momGrowth[$value->SubCategoryId] = number_format((100), 0);
            }
            
            $responseDiff =0;
            if($value->changeYOY){
                $responseDiff = $value->changeYOY;
                unset($topData[$key]->changeYOY);
                $yoyGrowth[$value->SubCategoryId] = number_format((($responseDiff*100)/$value->changeYOYValue), 0);
            }else{
                $yoyGrowth[$value->SubCategoryId] = number_format((100), 0);
            }
            

            if($checkKey){
                $topData[$value->SubCategoryName] = array(
                                                'count' => $value->ScalarValue,
                                                'key' => $value->SubCategoryId
                                                );
                unset($topData[$key]);   
            }else{
                $topData[$value['PageName']] = $value['ScalarValue'];
                unset($topData[$key]); 
            } 
        }     
        if($checkKey){
            foreach ($topData as $key => $value) {
                $topData[$value['key']] = array(
                                                'count' => $value['count'],
                                                'name' => $key
                                                );
                unset($topData[$key]);
            }
        }  
        //_p($topData);_p('-------------------');_p($momGrowth);_p('------------------');_p($yoyGrowth);die;
        $result = $this->prepareDataForBargraph($topData,'true',$flag,$barGraphWidth,$momGrowth,$yoyGrowth,false);
        return $result;
    }

    private function _prepareDataForBarGraphForGrowthForRegistrationForCat($topData,$flag=0,$checkKey=0){
        //_p($topData);die;
        foreach ($topData as $key => $value) {
            if($value->changeMOM){
                $responseDiff = $value->changeMOM;
                unset($topData[$key]->changeMOM);
            }else{
                $responseDiff = $value->ScalarValue;
            }
            $momGrowth[$value->CategoryId] = number_format((($responseDiff*100)/$value->ScalarValue), 0);
            $responseDiff =0;
            if($value->changeYOY){
                $responseDiff = $value->changeYOY;
                unset($topData[$key]->changeYOY);
            }else{
                $responseDiff = $value->ScalarValue;
            }
            $yoyGrowth[$value->CategoryId] = number_format((($responseDiff*100)/$value->ScalarValue), 0);

            if($checkKey){
                $topData[$value->CategoryName] = array(
                                                'count' => $value->ScalarValue,
                                                'key' => $value->CategoryId
                                                );
                unset($topData[$key]);   
            }else{
                $topData[$value['PageName']] = $value['ScalarValue'];
                unset($topData[$key]); 
            } 
        }     
        if($checkKey){
            foreach ($topData as $key => $value) {
                $topData[$value['key']] = array(
                                                'count' => $value['count'],
                                                'name' => $key
                                                );
                unset($topData[$key]);
            }
        }  
        //_p($topData);_p('-------------------');_p($momGrowth);_p('------------------');_p($yoyGrowth);die;
        $result = $this->prepareDataForBargraph($topData,'true',$flag,$momGrowth,$yoyGrowth,false);
        return $result;
    }
    //------------------------------For Top Registration Pages Stats End---------------------------------------

    //------------------------------For Top Traffic Stats---------------------------------------
    function getTrafficDataForTopStats($dateRange, $source,$showGrowth='true',$sourceApplication='',$aspect){
        $topData = $this->traffic($source,$sourceApplication,$dateRange,$showGrowth,$aspect);
        //_p($topData);die;
        //_p($topData['topSubcategories']);die;
        /*if($showGrowth == 'true'){
            $topTrafficStats['topPages'] = $this->_prepareDataForBarGraphForGrowthForTraffic($topData['topPages'],0,0,0);
            $topTrafficStats['topCategories'] = $this->_prepareDataForBarGraphForGrowthForTraffic($topData['topCategories'],1,1,0);
            $topTrafficStats['topSubCategories'] = $this->_prepareDataForBarGraphForGrowthForTraffic($topData['topSubcategories'],1,1,1);
            $topTrafficStats['topCities'] = $this->_prepareDataForBarGraphForGrowthForTraffic($topData['topCities'],1,1,0);
            $topTrafficStats['topCountries'] = $this->_prepareDataForBarGraphForGrowthForTraffic($topData['topCountries'],1,1,0);
        }else{
            $topTrafficStats['topPages'] = $this->prepareDataForBargraph($topData['topPages'],'false',0,0);
            $topTrafficStats['topCategories'] = $this->prepareDataForBargraph($topData['topCategories'],'false',1,0);
            $topTrafficStats['topSubCategories'] = $this->prepareDataForBargraph($topData['topSubcategories'],'false',1,1);
            $topTrafficStats['topCities'] = $this->prepareDataForBargraph($topData['topCities'],'false',1,0);
            $topTrafficStats['topCountries'] = $this->prepareDataForBargraph($topData['topCountries'],'false',1,0);
        }*/
        switch ($aspect) {
            case 'topPages':
                if($showGrowth == 'true'){
                    $topTrafficStats = $this->_prepareDataForBarGraphForGrowthForTraffic($topData,0,0,0);
                }else{
                    $topTrafficStats = $this->prepareDataForBargraph($topData,'false',0,0);
                }
                break;
            
            case 'topCategories':
            case 'topCountries':
            case 'topCities':
                if($showGrowth == 'true'){
                    $topTrafficStats = $this->_prepareDataForBarGraphForGrowthForTraffic($topData,1,1,0);
                }else{
                    $topTrafficStats = $this->prepareDataForBargraph($topData,'false',1,0);
                }
                break;

            case 'topSubcategories':
                foreach ($topData as $key => $value) {
                    $subCatToCatName[] = $key;
                }

                $subCatToCat = $this->_getSubCatToCategoryName($subCatToCatName);
                foreach ($subCatToCat as $key => $value) {
                    $subCatToName[$value['id']] = $value['categoryId'];
                }
                
                foreach ($topData as $key => $value) {
                    $topData[$key]['name'] =$value['name'].' ['.$subCatToName[$key].']';
                }
                if($showGrowth == 'true'){
                    $topTrafficStats = $this->_prepareDataForBarGraphForGrowthForTraffic($topData,1,1,1);
                }else{
                    $topTrafficStats = $this->prepareDataForBargraph($topData,'false',1,1);
                }
                break;

            default:
                # code...
                break;
        }
        return json_encode($topTrafficStats);
    }

    private function _prepareDataForBarGraphForGrowthForTraffic($topData,$flag=0,$checkKey=0,$barGraphWidth=0){
        //_p($topData);die;
        foreach ($topData as $key => $value) {  
            $momGrowth[$key] = $value['changeMOM'];
            $yoyGrowth[$key] = $value['changeYOY'];

            if(!$checkKey){
                $topData[$key] = $value['count'];
            }
        } 
        
        //_p($topData);_p('-------------------');_p($momGrowth);_p('------------------');_p($yoyGrowth);die;
        $result = $this->prepareDataForBargraph($topData,'true',$flag,$barGraphWidth,$momGrowth,$yoyGrowth);
        return $result;
    }

    public function traffic($source,$sourceApplication='',$dateRange='',$showGrowth='false',$aspect){
        $count = 10;
        $type = 'traffic';
        $team = $source;
        //$team = 'domestic';
        $deviceType = $sourceApplication;
        // Top Pages and their delta

        /*$trafficData['topPages'] =  $this->getPages($count, $type, $team, $deviceType, $dateRange,$showGrowth);
        $trafficData['topCategories'] = $this->getCategories($count, $type, $team, $deviceType, $dateRange,$showGrowth);
        $trafficData['topSubcategories'] = $this->getSubcategories($count, $type, $team, $deviceType, $dateRange,$showGrowth);
        $trafficData['topCountries'] = $this->getCountries($count, $type, $team, $deviceType, $dateRange,$showGrowth);
        $trafficData['topCities'] = $this->getCities($count, $type, $team, $deviceType, $dateRange,$showGrowth);*/

        switch ($aspect) {
            case 'topPages':
                $trafficData =  $this->getPages($count, $type, $team, $deviceType, $dateRange,$showGrowth);
                break;
            
            case 'topCategories':
                $trafficData = $this->getCategories($count, $type, $team, $deviceType, $dateRange,$showGrowth);
                break;

            case 'topSubcategories':
                $trafficData = $this->getSubcategories($count, $type, $team, $deviceType, $dateRange,$showGrowth);
                break;

            case 'topCountries':
                $trafficData = $this->getCountries($count, $type, $team, $deviceType, $dateRange,$showGrowth);
                break;

            case 'topCities':
                $trafficData = $this->getCities($count, $type, $team, $deviceType, $dateRange,$showGrowth);
                break;

            default:
                # code...
                break;
        }
        //_p($trafficData); die;
        return $trafficData;
    }
    //------------------------------For Top Traffic Stats End---------------------------------------
    function getTopCategoryData($source=''){

        if($source == 'abroad'){
            $dateRange = $this->input->post('dateRange');
            $sourceApplication = $this->input->post('sourceApplication');
            $topData = $this->responses($source,$sourceApplication,$dateRange);
            //_p($topData);die;
            $topPageData = $this->_prepareDataForBarGraph($topData);
            echo (json_encode($topPageData));
        }else if($source == 'national'){
            $dateRange = $this->input->post('dateRange');
            $sourceApplication = $this->input->post('sourceApplication');
            $topData = $this->responses($source,$sourceApplication,$dateRange);

            $topPageData = $this->_prepareDataForBarGraph($topData);
            echo (json_encode($topPageData));
        }else{

            $dateRange = $this->input->post('dateRange');
            $sourceApplication = $this->input->post('sourceApplication');
            $topData = $this->responses($source,$sourceApplication,$dateRange);
            //_p($topData);die;
            $topPageData = $this->_prepareDataForBarGraph($topData);

            echo (json_encode($topPageData));
        }
    }

    function _prepareDataForBarGraph($topData){
      
        //_p($topData);die;
        $topCategories = $topData['topCategories'];
    
        foreach ($topCategories as $key => $value) {
            $topCategories[$value['name']] = $value['count'];
            unset($topCategories[$key]);
        }
        //_p($topCategories);die;
        $result['topCategories'] = $this->prepareDataForBargraph($topCategories);

        $topSubCategories = $topData['topSubCategories'];
        foreach ($topSubCategories as $key => $value) {
            $topSubCategories[$value['name']] = $value['count'];
            unset($topSubCategories[$key]);
        }
        //_p($topSubCategories);die;
        $result['topSubCategories'] = $this->prepareDataForBargraph($topSubCategories);

        $topCities = $topData['topCities'];
        foreach ($topCities as $key => $value) {
            $topCities[$value['name']] = $value['count'];
            unset($topCities[$key]);
        }
        //_p($topCities);die;
        $result['topCities'] = $this->prepareDataForBargraph($topCities);

        $topLisings = $topData['topUniversities'];
        //_p($topData['topUniversities']);die;
        foreach ($topLisings as $key => $value) {
            $topLisings[$value['name']] = array(
                                                'count' => $value['count'],
                                                'countryName' => $value['countryName'],
                                                'url' => $value['url']
                                                );
            unset($topLisings[$key]);
        }
        //_p($topCategories);die;
        $result['topLisings'] = $this->prepareDataForBargraph($topLisings,1);

        return $result;
    }

    function getUTMWiseData($sessionId = array(),$source = '')
    {
        $model = $this->CI->load->model('trackingMIS/overview_model');
        if( ! empty($sessionId))
        {
            $sourceWise = $model->getUTMwiseDataBasedOnSessionId($sessionId,$source,'utmSource');
            $mediumWise = $model->getUTMwiseDataBasedOnSessionId($sessionId,$source,'utmMedium');
            $campaignWise = $model->getUTMwiseDataBasedOnSessionId($sessionId,$source,'utmCampaign');   
        }
        $utmArray = array();
        $utmArray['sourceWise'] = $sourceWise;
        $utmArray['mediumWise'] = $mediumWise;
        $utmArray['campaignWise'] = $campaignWise;
        return $utmArray;

    }

    function getTrafficSourceDataForOverviewRegistration($dateRange,$sourceApplication,$sourceFlag=0,$count='',$defaultView=''){
        $model = $this->CI->load->model('trackingMIS/overview_model');
        $trackingIds = $model->getTrackingIdsForOverviewRegistration($sourceApplication);
        $getSessionIds = $model->getSessionIdsForOverviewRegistration($trackingIds,$dateRange);
        //===================
        $result = $this->_getDataFormSessionIdsForOverviewRegistration($getSessionIds,$count,$defaultView);
        //_p($result);die;
        //--------------------------------------------------------------------------------
        if(!($sourceFlag==1)){
            foreach ($result['source'] as $key => $value) {
                if(strcasecmp($key, 'Other') == 0){
                    continue;
                }
                $trafficSourceArray[$i++] = $key;
                $lis = $lis . 
                        '<li role="presentation"  >'.
                            '<a href="javascript:void(0)" id="'.$key.'" role="tab" data-toggle="tab" aria-expanded="true">'.ucfirst($key).
                            '</a>'.
                            '<input id="hidden_'.$key.'" type="hidden" value="'.$value.'" >'.
                        '</li>';
            }
            $barGraph['lis'] = $lis;
            $barGraph['defaultView'] = $result['defaultView'];
            $countValue = $result['source'][$result['defaultView']];
        }else{
            $countValue = $count;
        }
        //_p($trafficSourceArray);die;
        
        //_p($defaultView);die;
        if(! $result['utmSource'] && !$result['utmCampaign'] && !$result['utmMedium']){
            $barGraph['utmSource'] = null;
            $barGraph['utmCampaign'] = null;
            $barGraph['utmMedium'] = null;
        }else{
            $barGraph['utmSource'] = $this->prepareDataForTrafficSourceForOverviewRegistration($result['utmSource'],$countValue,0);
            $barGraph['utmCampaign'] = $this->prepareDataForTrafficSourceForOverviewRegistration($result['utmCampaign'],$countValue,1);
            $barGraph['utmMedium'] = $this->prepareDataForTrafficSourceForOverviewRegistration($result['utmMedium'],$countValue,0);    
        }
        $inputArray = array(
                            'utmSource' =>$barGraph['utmSource'],
                            'utmCampaign' => $barGraph['utmCampaign'],
                            'utmMedium' => $barGraph['utmMedium']
                            );
        $barGraphData = $this->MISCommonLib->prepareTrafficSourceBarGraphForTraffic($inputArray);
        //_p($barGraph);die;
        $data['barGraphData'] = $barGraphData;
        $data['barGraph'] = $barGraph;
        $data['source'] = $result['source'];
        return $data;
    }

    public function prepareDataForTrafficSourceForOverviewRegistration($UTMArray,$UTMDataCount,$sizeFlag=0)
    {  
        //_p($UTMArray);_p($UTMDataCount);die;
        //_p($flag);_p($showGrowth);die;
        
        //_p($UTMArray);_p($count);die;
        arsort($UTMArray);
        $maxValue = 0;
        $i=0;
        foreach ($UTMArray as $key => $value) { 
            if($i==0){
                $maxValue = $value;
            }else{
                break;   
            }
            $i++;
        }
        $avg = number_format((($maxValue)/100), 2,'.','');
        //_p($avg);die;
        

        if($sizeFlag==0){
            $leftWidth = 40;
            $centerWidth  = 30;
            $countWidth =   30;
            $pageNameWidth = 26;
        }else{
            $leftWidth = 55;
            $centerWidth  = 30;
            $countWidth =   15;
            $pageNameWidth = 70;
        }
        
            
        $barGraph = '<table style="width: 100%;">';
        foreach ($UTMArray as $key => $value) {   
            $normalizeValue = number_format(($value/$avg), 0, '.', '');
            $actualValue = number_format(($value));
            $title = ucfirst($key);
            $fieldName = limitTextLength($title,$pageNameWidth);
            $span = '<span title="'.htmlentities($title).'">'.htmlentities($fieldName).'</span>';
            $percantageValue = number_format((($value*100)/$UTMDataCount), 2,'.','');
            
            $field ='<td class="BGHeading_fontSize" style="width:'.$countWidth.'% !important">&nbsp&nbsp'.$actualValue.' ( '.$percantageValue.'%)'.
                    '</td>';    
            

            $barGraph = $barGraph.
            '<tr class="widget_summary">'.
                '<td class="w_left" style="width:'.$leftWidth.'% !important">'.
                   $span.
                '</td>'.
                '<td class="w_center " style="width:'.$centerWidth.'% !important">'.
                    '<div class="progress" style="margin-bottom:10px !important" >'.
                        '<div  title = "'.$actualValue.'" class="progress-bar bg-green" role="progressbar" style="width:'.$normalizeValue.'%'.'" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">'.
                            '<span class="sr-only"  ></span>'.
                        '</div>'.
                    '</div>'.
                '</td>'.$field.
                '<div class="clearfix"></div>';   
        }
        
        $barGraph = $barGraph.'</table>';
        //die;_p($barGraph);die;
        return  array('barGraph'=>$barGraph,
                        'count'=>count($UTMArray));
    }

    function _getDataFormSessionIdsForOverviewRegistration($sessionIds,$count,$defaultView=''){
        $sessionId = array();
        $i = 0;
        $sessionResultArray = array();
        foreach ($sessionIds as $key => $value) {
            $sessionId[] = $value['visitorsessionid'];
            $sessionResultArray[$value['visitorsessionid']] = $value['count'];
        }
        $result = $this->getDataSourceWiseForOverviewRegistration($sessionId,$sessionResultArray,$count,$defaultView);
        //_p($result);die;
        return $result;
    }

    function getDataSourceWiseForOverviewRegistration($sessionId,$sessionResult,$count='',$defaultView='')
    {
        $model = $this->CI->load->model('trackingMIS/overview_model');
        if( ! empty($sessionId))
        {
            // for traffic source
            if($defaultView==''){
                $sourceResult =$model->getSourceForSessionIdForOverviewRegistration($sessionId,'source');
                $result['source'] = $this->getDataForSourceFilterForOverviewRegistration($sourceResult,$sessionResult,0);

                $prioritySourceArray= array('paid','mailer','social','direct','seo');
                foreach ($result['source'] as $key => $value) {
                    $trafficSourceArray[] = $key;
                }
                foreach ($prioritySourceArray as $key => $value) {
                    if(in_array($value, $trafficSourceArray)){
                        $defaultView = $value;
                        break;
                    }
                }
                $count = $result['source'][$defaultView];
                $result['count'] = $count;
            }

            // for utm source
            $sourceResult = array();
            $sourceResult =$model->getSourceForSessionIdForOverviewRegistration($sessionId,'utmSource',$defaultView);
            $result['utmSource'] = $this->getDataForSourceFilterForOverviewRegistration($sourceResult,$sessionResult,1,$count);

            // for utm campaign
            $sourceResult = array();
            $sourceResult =$model->getSourceForSessionIdForOverviewRegistration($sessionId,'utmCampaign',$defaultView);
            $result['utmCampaign'] = $this->getDataForSourceFilterForOverviewRegistration($sourceResult,$sessionResult,1,$count);

            // for utm medium
            $sourceResult = array();
            $sourceResult =$model->getSourceForSessionIdForOverviewRegistration($sessionId,'utmMedium',$defaultView);
            $result['utmMedium'] = $this->getDataForSourceFilterForOverviewRegistration($sourceResult,$sessionResult,1,$count);
            //die;
            $result['defaultView'] = $defaultView;
        }
        //_p($defaultView);
        //_p($result);die;
        return $result;    
    }

    function getDataForSourceFilterForOverviewRegistration($sourceResult,$sessionResult,$flag,$count=''){
        $sourceSessionMapping = array();
        foreach ($sourceResult as $key => $value) {
            $sourceSessionMapping[$value['sessionId']] = $value['value'];
        }
        $sourceWiseResult = array();
        $i = 0;
        foreach ($sessionResult as $key => $value) {
            if( empty($sourceSessionMapping[$key])){
                if($flag != 1){
                $sourceWiseResult['Other'] += $value;    }
                continue;
            }
            $sourceWiseResult[ $sourceSessionMapping[ $key ] ] += $value;
            if($flag == 1){
                $UTMCount += $value;    
            }
        }
        foreach ($sourceWiseResult as $key => $value) {
            $sourceWiseSingleSplit[$key] = $value; 
        }
        if($flag == 1){
            if($count > $UTMCount){
                $diff = $count - $UTMCount;
                $sourceWiseSingleSplit['Other'] = $diff;    
            }
        }
        arsort($sourceWiseSingleSplit);
        return $sourceWiseSingleSplit;
    }
	function getTrafficDataUTMWise($dateRange = array(),$extraData = array(),$pageName ='',$trafficSource='')
    {
        $params = array();
        $params['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $params['type'] = 'session';
        $params['body']['size'] = 0;
        $startDateFilter = array();
        $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'].'T00:00:00';
        $endDateFilter = array();
        $endDateFilter['range']['startTime']['lte'] = $dateRange['endDate'].'T23:59:59';
    
        $isType = key($extraData);
        if($isType == 'National')
        {
            $nationalFilters = (current($extraData));
            $sourceApplication = strtolower($nationalFilters['deviceType']);    
            if($sourceApplication){
                if($sourceApplication == 'mobile'){
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isMobile'] = "yes";
                }if($sourceApplication == 'desktop'){
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isMobile'] = "no";
                }
            }
            if($pageName != 'all'){

                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = $this->MISCommonLib->getPageNameForDomestic($pageName);
            }
            $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isStudyAbroad'] = 'no';
        }

        if($nationalFilters['category'] != 0 ){
            $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.categoryId'] = $nationalFilters['category'];
            }
        if($nationalFilters['subcategory'] != 0)
        {
            $subcategories = explode(",", $nationalFilters['subcategory']);
            $params['body']['query']['filtered']['filter']['bool']['must'][]['terms']['landingPageDoc.subCategoryId'] = $subcategories;
        }
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['source'] = $trafficSource;

//        $params['body']['aggs']['checkColoum']['filter']['exists']['field'] = 'landingPageDoc.pageIdentifier';
        // now for diff utms change aggs
        //1. For UTM Source
        $UTMFilter['UTMSource']['terms']['field']= 'utm_source';
        $UTMFilter['UTMSource']['terms']['size']= 0;
        $params['body']['aggs'] = $UTMFilter;
        $search = $this->clientCon->search($params);
        $UTMSourceData = $search['aggregations']['UTMSource']['buckets'];
        $UTMSourceDataCount = $search['hits']['total'];
        $utmSourceWise = $this->getCountPerUTMWise($UTMSourceData,$UTMSourceDataCount);

        $UTMFilter =array();
        $UTMFilter['UTMMedium']['terms']['field']= 'utm_medium';
        $UTMFilter['UTMMedium']['terms']['size']= 0;
        $params['body']['aggs'] = $UTMFilter;
        $search = $this->clientCon->search($params);
        $UTMMediumData = $search['aggregations']['UTMMedium']['buckets'];
        $UTMMediumDataCount = $search['hits']['total'];
        $utmMediumWise = $this->getCountPerUTMWise($UTMMediumData,$UTMMediumDataCount);
       
        $UTMFilter =array();
        $UTMFilter['UTMCampaign']['terms']['field']= 'utm_campaign';
        $UTMFilter['UTMCampaign']['terms']['size']= 0;
        $params['body']['aggs'] = $UTMFilter;
        $search = $this->clientCon->search($params);
        $UTMCampaignData = $search['aggregations']['UTMCampaign']['buckets'];
        $UTMCampaignDataCount = $search['hits']['total'];
        $utmCampaignWise = $this->getCountPerUTMWise($UTMCampaignData,$UTMCampaignDataCount);

        return array($utmSourceWise,$utmMediumWise,$utmCampaignWise);

    }
    function getCountPerUTMWise($utmWiseData = array(),$totalUTMtrafficCount)
    {
        $count = 0;
        $resultArray = array();
        foreach ($utmWiseData as $key => $value) {
            $utmWiseSplit = new stdClass();
            $utmWiseSplit->ResponseCount = $value['doc_count'];
            $utmWiseSplit->Pivot = htmlentities($value['key']);
            $utmWiseSplit->Percentage = number_format(($value['doc_count']/$totalUTMtrafficCount) * 100,2,'.','');
            $resultArray[]= $utmWiseSplit;
            $count += $value['doc_count'];
        }
        if($totalUTMtrafficCount > $count)
        {
            $diff_count = $totalUTMtrafficCount - $count;
            $utmWiseSplit = new stdClass();
            $utmWiseSplit->ResponseCount = $diff_count;
            $utmWiseSplit->Pivot = 'Others';
            $utmWiseSplit->Percentage = number_format(($diff_count / $totalUTMtrafficCount) *100,2,'.','');
            $resultArray[] = $utmWiseSplit;
        }
        arsort($resultArray);
        return array_values($resultArray);
    }

    /**
     * Get a guaranteed value for a variable identified by a type.
     *
     * @param string $type     The variable identifier
     * @param string $variable The variable itself
     *
     * @return string The default value if blank value is passed
     */
    public function getDefault($type, $variable)
    {

        switch ($type) {
            case 'startDate':
            case 'compareStartDate':
                if ($variable == '')
                    $variable = date('Y-m-d', strtotime('-2 days'));
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

}