<?php
/**
 * Controller for Global Shiksha MIS.
*/

class Dashboard extends MX_Controller
{
	private $trackingLib;
	
	function __construct()
	{
		parent::__construct();
		$this->trackingLib = $this->load->library('trackingMIS/trackingMISCommonLib');
            $this->MISCommonLib = $this->load->library('trackingMIS/MISCommonLib');
            $this->trackingLib->checkValidUser(true);
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
	
      private function _prepareLeftMenu($team='',$page=''){
            //_p($team);die;
            global $trackingHome;
            $this->load->config('globalTrackingMISConfig');
            $pageMetricMapping = $this->config->item("PAGE_METRIC_MAPPING");
            $pageMetricMapping = $pageMetricMapping[$team];
            $commonUrlString = SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/".strtolower($team);
            if($team == "DOMESTIC"){
                  $trackingHome = SHIKSHA_HOME."/trackingMIS/Listings";
            }
            foreach ($pageMetricMapping as $pageName => $pageDetails) {
                  $leftMenuArray[$pageName] = array(
                        "className" => "fa-home",
                        "children" => array()
                  );

                  foreach ($pageDetails['metric'] as $metric) {
                        if($metric == "Overview"){
                              $leftMenuArray[$pageName]["children"][$metric] = $trackingHome."/dashboard";
                        }else if($metric == "Shiksha Assistant"){
                              $leftMenuArray[$pageName]["children"][$metric] = SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/sassistant";
                        }else{
                              $leftMenuArray[$pageName]["children"][$metric] = $commonUrlString."/".strtolower($metric);
                              $leftMenuArray[$pageName]["children"][$metric] .= !empty($pageDetails['pageIdentifier'])?"/".$pageDetails['pageIdentifier']:"";
                        }
                  }
            }
            return $leftMenuArray;
      }

	private function _loadDependecies($metric,$team='',$page='') {
		$data['userDataArray'] = reset($this->trackingLib->checkValidUser());
		// _p($data['userDataArray']); die;
            $data['validRequest'] = true;
            $metricArrayItem = array('registration','traffic','engagement','response','RMC','sassistant');
            if(in_array($metric,$metricArrayItem)){
                  if($team == 'DOMESTIC'){
                        $data['leftMenuArray'] = $this->_prepareLeftMenu($team, $page);
                        //$this->load->config('listingsTrackingMISConfig');
                        //$data['leftMenuArray'] = $this->config->item("leftMenuArray");
                        //_p($data['leftMenuArray']);die;
                  }else if($team == 'STUDY ABROAD'){
                        $this->load->config('saTrackingMISConfig');
                        $data['leftMenuArray'] = $this->config->item("leftMenuArray");      
                  }else if($team == 'SHIKSHA'){
                        $this->load->config('globalTrackingMISConfig');
                        $data['leftMenuArray'] = $this->config->item("leftMenuArray");      
                  }

                  $this->load->config('globalTrackingMISConfig');
                  $metricArray = $this->config->item('METRIC');
                  $data['pageDetails'] = $metricArray[strtoupper($metric)];
                  $data['ajaxDestinationURL'] = $data['pageDetails']['ajaxDestinationURL'];
                  $topFilters = $this->config->item('FILTER');
                  if($page){
                        $data['pageDetails'] = $data['pageDetails']['PAGEWISE'];
                        $data['topFilters'] = $topFilters[$team][strtoupper($metric)][$page];
                  }else{
                        $data['pageDetails'] = $data['pageDetails']['OVERALL'];
                        $data['topFilters'] = $topFilters[$team][strtoupper($metric)]['Overall'];
                  }
                  if(empty($data['topFilters'])){
                        $data['topFilters'] = array();
                        $data['validRequest'] = false;
                  }
            }else if($team == 'STUDY ABROAD' && $page == 'searchPage' && $metric == 'home'){
                  $this->load->config('saTrackingMISConfig');
                  $data['leftMenuArray'] = $this->config->item("leftMenuArray");
                  $this->load->config('globalTrackingMISConfig');
                  $metricArray = $this->config->item('METRIC');
                  $topFilters = $this->config->item('FILTER');
                  $data['topFilters'] = $topFilters[$team][strtoupper($metric)][$page];
                  //_p($data['topFilters']);die;
                  $data['pageDetails'] = $metricArray['SEARCH_HOME'];
                  $data['ajaxDestinationURL'] = $data['pageDetails']['ajaxDestinationURL'];
                  //$data['misSource'] = "global";
                  if(empty($data['topFilters'])){
                        $data['topFilters'] = array();
                        $data['validRequest'] = false;
                  }
            }else if($team == 'STUDY ABROAD' && $metric == 'exam_upload'){
                  $this->load->config('saTrackingMISConfig');
                  $data['leftMenuArray'] = $this->config->item("leftMenuArray");
                  $this->load->config('globalTrackingMISConfig');
                  $metricArray = $this->config->item('METRIC');
                  $topFilters = $this->config->item('FILTER');
                  $data['topFilters'] = $topFilters[$team][strtoupper($metric)][$page];
                  $data['pageDetails'] = $metricArray['EXAM_UPLOAD'];
                  $data['ajaxDestinationURL'] = $data['pageDetails']['ajaxDestinationURL'];
                  if(empty($data['topFilters'])){
                        $data['topFilters'] = array();
                        $data['validRequest'] = false;
                  }
            }else{
                  $data['validRequest'] = false;
                  $this->load->config('globalTrackingMISConfig');
                  $data['leftMenuArray'] = $this->config->item("leftMenuArray");
                  $metricArray = $this->config->item('METRIC');
                  $data['pageDetails'] = $metricArray[strtoupper($metric)];
                  $data['misSource'] = "global";
                  $data['topFilters'] = array();
            }
		return $data;
	}

      public function checkConversionHistoryBySessionId($type,$data){
            //_p($type);_p($data);die;
            $response = $this->MISCommonLib->checkConversionHistoryBySessionId($type,$data);
            //_p($response);die;
            $count = 1;            
            if($type == "hours"){
                  $dataView1 .=  '<table id="" class="display cell-border hover order-column  jambo_table" style="width:100% !important">'.
                              '<thead>'.
                              '<tr>'.
                              '<th style="width:5% !important">#</th>'.
                              '<th style="width:80% !important">User Query(Total Queries Asked)</th>'.
                              '<th style="width:15% !important">Query Time</th>'.
                              '</tr>'.
                        '</thead>'.
                        '<tbody>';
                        $dataView = '';
                  foreach($response['history'] as $sessionId => $conversion){
                        $dataView.= '<tr><td>'.$count++.'</td><td><a href="/trackingMIS/Dashboard/checkConversionHistoryBySessionId/session/'.$sessionId.'" target="_blank">'.$conversion['userQuery'].'('.$conversion['count'].')</a></td><td>'.$conversion['queryTime'].'</td>';
                  }
            }else{
                  $dataView1 =  '<table id="" class="display cell-border hover order-column  jambo_table">'.
                              '<thead>'.
                              '<tr>'.
                              '<th style="width:5% !important">#</th>'.
                              '<th style="width:50% !important">User Query(Total Queries Asked)</th>'.
                              '<th style="width:10% !important">Query Time</th>'.
                              '<th style="width:10% !important">Query Type</th>'.
                              '<th style="width:15% !important">Responses Count</th>'.
                              '<th style="width:5% !important">Opinion Factual</th>'.
                              '<th style="width:5% !important">Attribute</th>'.
                              '</tr>'.
                        '</thead>'.
                        '<tbody>';
                        $dataView = '';

                  foreach($response['history'] as $sessionId => $conversion){
                        $dataView.= '<tr>'.
                                    '<td>'.$count++.'</td>'.
                                    '<td>'.$conversion['userQuery'].'</td>'.
                                    '<td>'.$conversion['queryTime'].'</td>'.
                                    '<td>'.$conversion['queryType'].'</td>'.
                                    '<td>'.$conversion['responsesCount'].'</td>'.
                                    '<td>'.$conversion['opinionFactual'].'</td>'.
                                    '<td>'.$conversion['attribute'].'</td>';
                  }

            }
                  
            //_p($dataView1);die;
            $dataView1 .= $dataView.'</tbody></table>';
            $dataView1.= '<style>'.
                        'table, table td,table th {border: 1px solid #9f6000;}'.
                        'table td, table th {padding:3px 6px}'.
                        'table th{color:#fff;background:blue}'.
                        'table{border-collapse:collapse}'.
                                                '</style>';
            echo $dataView1;      
      }

      function metric($team='shiksha',$metric='',$page=''){
            if($team == 'studyabroad'){
                  $teamSource = 'STUDY ABROAD';
                  $teamName = 'Study Abroad';

            }else{
                  $teamSource = strtoupper($team);
                  $teamName = ucfirst($team);
            }
            
            $data = $this->_loadDependecies($metric,$teamSource,$page);
            
            $data['pageName'] = $page;
            $data['pageGroup'] = $page;            
            $data['metric'] = $metric;
            $data['teamName'] = $teamName;

            $this->getFilterData($data,$metric,$team);
            $data['source'] = $teamSource;
            $this->_prepareDiffChartData($data,$data['source']);
            if($metric == "sassistant"){
                  $data['validRequest'] = true;
                  $data['misSource'] = 'assistantMIS';
                  $data['diffChartFilter']['TOP_TILES'] = array(
                        'totalConversations'          => array('title' => 'Total Conversations', 'id' => 'totalConversations'),
                        'conversationPerSessions'   => array('title' => 'Conversations/sessions (Avg)', 'id' => 'conversationPerSessions')
                  );
                  $data['diffChartFilter']['topTiles'] = array('totalConversations','conversationPerSessions');

                  $data['diffChartFilter']['lineChart']= array(
                        /*array("id" =>"conversations", "heading" => "Conversations", "toolTip" => "Total Conversations"),*/
                        array("id" =>"conversationPerSession", "heading" => "Conversations / sessions (Avg)", "toolTip" => "Conversations/sessions"),
                        array("id" =>"sessionDurationWithAssistant", "heading" => "Session Duration", "toolTip" => "Session Duration","type" => "comparision"),
                        array("id" =>"sessionCountWithAssistant", "heading" => "Session Count", "toolTip" => "Session Count","type" => "comparision"),
                        array("id" =>"pagesCountWithAssistant", "heading" => "Pageviews / Session", "toolTip" => "Pageviews / Session","type" => "comparision"),
                        array("id" =>"queriesVsAnsQueries", "heading" => "Answer Rate", "toolTip" => "Answer Rate","type" => "comparision"),
                        array("id" =>"queriesVsAnsQueriesPerSessions", "heading" => "Answer Rate", "toolTip" => "Answer Rate","type" => "comparision"),
                        array("id" =>"pvFromAssistant", "heading" => "Pageviews from assistant", "toolTip" => "PV's from assistant"),
                  );

                  $data['diffChartFilter']['BAR_GRAPH'] = array(
                        'quickReplyTopQueries' => array(
                              "heading" => 'Top User queries (quick reply)',
                              "tableHeading" => array("left" => "User Query", "right" => "Count")
                        ),
                        'userTopQueries' => array(
                              "heading" => 'Top User queries (by user)',
                              "tableHeading" => array("left" => "User Query", "right" => "Count")
                        ),
                        'topIntentAnswered' => array(
                              "heading" => 'Top Intent Answered',
                              "tableHeading" => array("left" => "Top Intent", "right" => "Count")
                        ),
                        'topIntentUnAnswered' => array(
                              "heading" => 'Top Intent Un - Answered',
                              "tableHeading" => array("left" => "Top Intent", "right" => "Count")
                        ),
                        'opinionFactual' => array(
                              "heading" => '% of opinion vs factual'
                        ),
                        'questionByAttribute' => array(
                              "heading" => '% of Question Bucket(attribute)',
                              "tableHeading" => array("left" => "Top Intent", "right" => "Count")
                        )
                  );
                  foreach ($data['diffChartFilter']['BAR_GRAPH'] as $key => $value) {
                        $data['diffChartFilter']['barGraphs'][] = $key;
                  }
                  //$data['diffChartFilter']['barGraphs'] = array('quickReplyTopQueries', 'userTopQueries','topIntentAnswered','topIntentUnAnswered','opinionFactual');
            }else{
                  $data['misSource'] = 'shikshaMIS';
            }
            $data['colorCodes'] = $this->colorCodes;
            
            $data['pageNameToShow'] = $this->MISCommonLib->getPageName($page);
            $this->load->view('trackingMIS/misTemplate', $data);
      }

      function _prepareDiffChartData(&$data,$teamName){
            //_p($data);die;

            // Top Tiles
            $topTiles = array_merge($data['pageDetails']['TOP_TILES']['COMMON'],$data['pageDetails']['TOP_TILES'][$teamName]);
            $data['pageDetails']['TOP_TILES'] = $topTiles;
            foreach ($topTiles as $key => $value) {
                  $data['diffChartFilter']['topTiles'][] = $value['id'];
            }

            //Line Chart
            $data['diffChartFilter']['lineChart'] = $data['pageDetails']['LINE_CHART'];

            //Donut Chart
            $donutCharts = array_merge($data['pageDetails']['PIE_CHART']['COMMON'],$data['pageDetails']['PIE_CHART'][$teamName]);
            $data['pageDetails']['PIE_CHART'] = $donutCharts;
            foreach ($donutCharts as $key => $value) {
                  $data['diffChartFilter']['donutCharts'][] = $value;
            }

            if($data['pageGroup'] == "examPageMain"){
                  if($data['metric'] == "registration"){
                        $data['diffChartFilter']['donutCharts'][] = array(
                              'id' => 'page',
                              'fieldName' => 'pageIdentifier',
                              'title' => 'Group Page List'
                        );
                        //_p($data['pageDetails']['PIE_CHART']);die;
                        $data['pageDetails']['PIE_CHART']["Group Pages"] = array(
                              'id' => 'page',
                              'fieldName' => 'pageIdentifier',
                              'title' => 'Group Page List'
                        );
                  }else if($data['metric'] == "response"){
                        $data['diffChartFilter']['donutCharts'][] = array(
                              'id' => 'page',
                              'fieldName' => 'page',
                              'title' => 'Group Page List'
                        );
                        //_p($data['pageDetails']['PIE_CHART']);die;
                        $data['pageDetails']['PIE_CHART']["Group Pages"] = array(
                              'id' => 'page',
                              'fieldName' => 'page',
                              'title' => 'Group Page List'
                        );
                  }
                        
            }

            //Tabbed Bar Graph
            $barGraphs = array_merge($data['pageDetails']['BAR_GRAPH']['COMMON'],$data['pageDetails']['BAR_GRAPH'][$teamName]);
            $data['pageDetails']['BAR_GRAPH'] = $barGraphs;
            foreach ($barGraphs as $key => $value) {
                  $data['diffChartFilter']['barGraphs'][] = $value;
            }

            //Data Table
            $dataTable = array_merge($data['pageDetails']['DATA_TABLE']['COMMON'],$data['pageDetails']['DATA_TABLE'][$teamName]);
            //_p($dataTable);
            $data['pageDetails']['DATA_TABLE'] = $dataTable;
            $data['diffChartFilter']['dataTable'][] = $dataTable['fields'];
      }

      function getFilterData(& $data, $metric,$team)
      {
            $this->abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
            //_p($data['topFilters']);die;
            foreach ($data['topFilters'] as $key => $value) {
                  switch ($value) {
                        case 'category':
                              if($team == 'studyabroad'){
                                    $data['desiredCourses'] = $this->abroadCommonLib->getAbroadMainLDBCourses();    // popular ldb courses
                                    $data['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();      // // popular categories
                              }else if($team == 'domestic'){
                                    $this->load->model('nationalListings/listings_model', 'ListingsModel');                       
                                    $data['domesticCategories'] = $this->ListingsModel->getCategories();
                              }     
                              break;

                        case 'country':
                              $this->_populateAbroadCountries($data);     // All Abroad Countries
                              break;

                        case 'courseLevel':
                              $data['courseType'] = $this->abroadCommonLib->getAbroadCourseLevels();  //Course Levels
                              break;

                        case 'sourceApplication':
                              $data['sourceApplication'] = array(
                                    'Desktop'         => array( 'id' =>'desktop','name' => 'Desktop'),
                                    'Mobile'          => array( 'id' =>'mobile','name' => 'Mobile'),
                                    );
                              if($team == 'domestic'){
                                    $data['sourceApplication']['Mobile App'] = array( 'id' =>'androidApp','name' => 'Mobile App');
                              }
                              break;

                        case 'sourceApplicationType':
                              $data['sourceApplicationType'] = array(
                                    'Desktop'         => array( 'id' =>'desktop','name' => 'Desktop'),
                                    'Mobile Site'          => array( 'id' =>'mobileSite','name' => 'Mobile Site'),
                                    'Mobile Amp'          => array( 'id' =>'mobileAmp','name' => 'Mobile Amp'),
                                    'Mobile App'          => array( 'id' =>'mobileApp','name' => 'Mobile App'),
                              );
                              break;

                        case 'user':
                              $data['userFilter'] = array(
                                    'Logged In'       => array( 'id' =>'loggedIn','name' => 'Logged In'),
                                    'Non Logged In'   => array( 'id' =>'nonLoggedIn','name' => 'Non Logged In'),
                                    );
                              break;

                        case 'abroadExam':
                              $data['abroadExam'] = array(
                                    'Yes'             => array( 'id' =>'yes','name' => ' Exam (yes)'),
                                    'No'              => array( 'id' =>'no','name' => ' Exam (no)'),
                                    'Booked'          => array( 'id' =>'booked','name' => ' Exam (booked)'),
                                    );
                              break;

                        case 'abroadExamList':
                              $abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
                              $abroadExamList = $abroadCommonLib->getAbroadExamsMasterList();
                              $data['abroadExamList'] = array();
                              foreach ($abroadExamList as $exam)
                              {
                              $data['abroadExamList'][$exam['exam']] = array('id'=>$exam['examId'],'name'=>$exam['exam']);
                              }
                              break;

                        case 'stream':
                              $this->load->builder('ListingBaseBuilder', 'listingBase');
                              $listingBaseBuilder   = new ListingBaseBuilder();
                              $HierarchyRepository = $listingBaseBuilder->getHierarchyRepository();
                              $result = $HierarchyRepository->getAllStreams();
                              $streams = array();
                              foreach ($result as $key => $stream) {
                                    $streams[$stream['id']] = $stream['name'];
                              }
                              $data['streams'] = $streams;
                              asort($data['streams']);
                              unset($streams);
                              unset($result);
                              break;

                        case 'deliveryMethod':
                              $result = $this->getEducationTypeAndDeliveryMethod();
                              //_p($result);die;
                              $data['educationType'] = $result['educationType'];
                              $data['deliveryMethodMapping'][20] = array(0 => "All Delivery Method",33 => $result['deliveryMethod'][33]);
                              $data['deliveryMethodMapping'][21] = $result['deliveryMethod'];
                              $data['deliveryMethodMapping'][21][0] = "All Delivery Method";
                              ksort($data['deliveryMethodMapping'][21]);
                              break;

                        case 'shikshaPages':
                              $data['shikshaPages'] = $this->_getPageIdentifiersList($team);
                              break;

                        case 'shikshaPageGroups':
                              $data['shikshaPageGroups'] = $this->_getShikshaPageGroupsList($team);
                              break;

                        case 'mode':
                              $result = $this->getEducationTypeAndDeliveryMethod();
                              $data['mode'] = array();
                              foreach ($result['educationType'] as $educationTypeId => $educationType) {
                                    foreach ($result['deliveryMethod'] as $key => $deliveryMethod) {
                                          if($key == 33){
                                                if($educationTypeId == 20){
                                                      $data['mode'][$educationTypeId] = $educationType." - ".$deliveryMethod;
                                                }else{
                                                      $data['mode'][$key] = $educationType." - ".$deliveryMethod;      
                                                }
                                          }else if($educationTypeId != 20){
                                                $data['mode'][$key] = $deliveryMethod;
                                          }
                                    }
                              }
                              break;

                        case 'responseType':
                              $data['responseType'] = array(
                                    'Paid'         => 'paid',
                                    'Free'          => 'free',
                                    );
                              break;  

                        case 'responseListingType':
                              $data['responseListingType'] = array();
                              if($team == 'domestic'){
                                    $data['responseListingType'] = array(
                                          'Course' => array( 'id' =>'course','name' => 'Course Responses'),
                                          'Exam'   => array( 'id' =>'exam','name' => 'Exam Responses'),
                                    );
                              }
                                    
                              break;

                        case 'trafficSourceType':
                              $data['trafficSourceType'] = array(
                                    "paid"    => array("id" => "paid",        "name" => "Paid"),
                                    "seo"     => array("id" => "seo",         "name" => "Seo"),
                                    "direct"  => array("id" => "direct",      "name" => "Direct"),
                                    "notsure" => array("id" => "notsure",     "name" => "Not Sure"),
                                    "mailer"  => array("id" => "mailer",      "name" => "Mailer"),
                                    "social"  => array("id" => "social",      "name" => "Social"),
                                    //"Other"   => array("id" => "Other",       "name" => "Other")
                              );
                              break;

                        case 'responseWarmth' :
                              $data['responseWarmth'] = array(
                                    "all"    => "All Response",
                                    "cta"     => "CTA Responses",
                                    "viewed"  => "Viewed Responses",
                              );
                              break;

                        case 'clientList':
                              $data['clientList'] = $this->_getTopClientDetails();
                              break;

                        case 'groupPageList':
                              $data['groupPageList'] = array();
                              $response = $this->MISCommonLib->getPageGroupPageList(array($data['pageGroup']), strtoupper($data['teamName']));
                              $data['groupPageList'] = $response[$data['pageGroup']];
                              break;

                        case 'sessionFilter':
                              $data['sessionFilter'] = array();
                              
                              $data['sessionFilter'] = array(
                                    'all'        => 'All Sessions',
                                    'sassistant' => 'With Shiksha Assistant',
                                    'nonsassistant' => 'Without Shiksha Assistant'
                              );
                              break;

                        case 'userUsedSassistant':
                              $data['userUsedSassistant'] = array();
                              
                              $data['userUsedSassistant'] = array(
                                    'all'        => 'Miscellaneous',
                                    'userUsedAssistant' => 'User Used Shiksha Assistant',
                                    'userNotUsedAssistant' => 'User Not Used Shiksha Assistant',
                                    'clickSassistant' => 'Clicks from shiksha assistant'
                              );
                              break;

                        case 'isourceFilter':
                              $data['isourceFilter'] = array(
                                    "All" => "all",
                                    'Assistant Used'       => "assistantUsed",
                                    'Assistant Not Used'   => "assistantNotUsed"
                                    );
                              break;
                  }
            }
      }

      private function _getTopClientDetails(){
            $this->load->config("globalTrackingMISConfig");
            $clientList = $this->config->item("TOP_CLIENT");
            $clientDetails = $this->MISCommonLib->getUserDetails($clientList);
            return $clientDetails;
      }


      private function _getPageIdentifiersList($team){
            $pageIdentifiers = array();
            /*if($team == "domestic"){
                  $this->load->config('globalTrackingMISConfig');
                  $pageMetricMapping = $this->config->item("PAGE_METRIC_MAPPING");
                  $pageMetricMapping = $pageMetricMapping[strtoupper($team)];

                  foreach ($pageMetricMapping as $pageName => $pageDetails) {
                        if(!empty($pageDetails['pageIdentifier'])){
                              $pageIdentifiers[$pageName] = $pageDetails['pageIdentifier'];      
                        }
                  }
            }*/
            $pageIdentifiers = $this->MISCommonLib->getPageGroupPageList(array(),strtoupper($team));
            //_p($pageIdentifiers);die;
            return $pageIdentifiers;
            /*$model = $this->load->model("trackingMIS/overview_model");
            $result = $model->getPageIdentifiersList($team);
            foreach ($result as $key => $value) {
                  $pageIdentifiers[$value['page']] = $value['page'];
            }*/
            
      }

      private function _getShikshaPageGroupsList($team){
            $result = $this->MISCommonLib->getShikshaPageGroupsList(strtoupper($team));
            return $result;
      }

      public function getGroupPagesForSelectedPageGroups(){
            $pageGroupList = $this->input->post('pageGroupList',true);
            $site = $this->input->post('site',true);
            // get pagelist for these groups
            $response['all'] = array("all");
            $responseData = $this->MISCommonLib->getPageGroupPageList($pageGroupList, $site);
            foreach ($responseData as $pageGroup => $pageGroupData) {
                  $response[$pageGroup] = $pageGroupData;
            }
            echo json_encode($response);
      }
      
      public function showHierarchyAndBaseCoursesByStream(){
            $streamId = $this->input->post('streamId',true);
            if ($streamId == '' || intval($streamId) == 0) {
                  $streamId = 1;
            }

            $metric = $this->input->post('metric',true);
            if ($metric == '') {
                  $metric = '';
            }

            $this->load->builder('ListingBaseBuilder', 'listingBase');
            $listingBaseBuilder   = new ListingBaseBuilder();
            $HierarchyRepository = $listingBaseBuilder->getHierarchyRepository();
            $result = $HierarchyRepository->getSubstreamSpecializationByStreamId(array($streamId));
            $substreams = array();
            $specializations = array();
            
            foreach ($result[$streamId]['substreams'] as $substreamId => $substreamDetails) {
                  $substreams[] = $substreamId;
            }
            /*foreach ($result[$streamId]['specializations'] as $specializationId => $specializationDetails) {
                  $specializations[] = $specializationId;
            }*/

            $substreamList = $this->_getSubstreamList($substreams, $listingBaseBuilder);
            //$specializationList = $this->_getSpecializationList($specializations, $listingBaseBuilder);

            $baseCourses = array();
            $baseCourseList = $this->getBaseCoursesByBaseEntities($streamId,'any','any',$metric);
            //_p($baseCourseList);_p($substreamList);_p($specializationList);die;
            echo json_encode(array(
                        "substreamList"      =>$substreamList, 
                        //"specializationList" => $specializationList,
                        "baseCourseList"        => $baseCourseList));
      }

      public function _getSpecializationList($specializations, $listingBaseBuilder){
            $specializationList = array();
            $specializationList[] = "All Specializations";
            if(count($specializations) >0){
                  $specializationRepository = $listingBaseBuilder->getSpecializationRepository();
                  $result = $specializationRepository->findMultiple($specializations);
                  foreach ($result as $specializationId => $specializationObj) {
                        $specializationList[$specializationId] = $specializationObj->getName();
                  }
            }
            $specializationList['none'] = 'None';
            return $specializationList;
      }

      public function _getSubstreamList($substreams, $listingBaseBuilder){
            $substreamList = array();
            $substreamList[] = "All Sub Streams";
            if(count($substreams) >0){
                  $result = array();
                  $substreamRepository = $listingBaseBuilder->getSubstreamRepository();
                  $result = $substreamRepository->findMultiple($substreams);
                  foreach ($result as $substreamId => $substreamObj) {
                        $substreamList[$substreamId] = $substreamObj->getName();
                  }
            }
            $substreamList['none'] = 'None';
            return $substreamList;
      }

      public function showHierarchyAndBaseCoursesBySubstream(){
            $response = array();
            $metric = $this->input->post('metric',true);
            if ($metric == '') {
                  $metric = '';
            }

            $streamId = $this->input->post('streamId',true);
            if($streamId == 0){
                  $streamId = 1;
            }
            $substreamId = $this->input->post('substreamId',true);
            if($substreamId == 0 && $substreamId != 'none'){
                  $substreamId = 'any';
            }else{
                  $this->load->builder('ListingBaseBuilder', 'listingBase');
                  $listingBaseBuilder   = new ListingBaseBuilder();
                  $HierarchyRepository = $listingBaseBuilder->getHierarchyRepository();
                  $specializations = $HierarchyRepository->getSpecializationByStreamSubstreamId($streamId, $substreamId);
                  $specializationList = $this->_getSpecializationList($specializations, $listingBaseBuilder);
                  $response["specializationList"] = $specializationList;
            }            

            $baseCourseList = $this->getBaseCoursesByBaseEntities($streamId, $substreamId, 'any', $metric);
            $response["baseCourseList"] = $baseCourseList;
            echo json_encode($response);
      }

      public function getBaseCourseByHierarchy(){
            $streamId = $this->input->post('streamId',true);
            if($streamId == 0){
                  $streamId = 1;
            }

            $substreamId = $this->input->post('substreamId',true);
            if($substreamId == 0 && $substreamId != 'none'){
                  $substreamId = 'any';
            }

            $specializationId = $this->input->post('specializationId',true);
            if($specializationId == 0 && $specializationId != 'none'){
                  $specializationId = 'any';
            }

            $metric = $this->input->post('metric',true);
            if ($metric == '') {
                  $metric = '';
            }

            $baseCourseList = $this->getBaseCoursesByBaseEntities($streamId, $substreamId, $specializationId,$metric);
            echo json_encode(array("baseCourseList"     => $baseCourseList));
      }

      public function getBaseCoursesByBaseEntities($streamId, $substreamId, $specializationId, $metric){
            if($streamId == 0){
                  $streamId = 1;
            }

            if($substreamId == 0 && $substreamId != 'any'){
                  $substreamId = 'none';
            }

            if($specializationId == 0 && $specializationId != 'any'){
                  $specializationId = 'none';
            }

            $baseCoursesList = array();
            $baseCoursesList['0']  = "All Base Courses";

            if($metric == "registration"){
                  $baseCourseLib = new  \registration\libraries\FieldValueSources\BaseCourses;
                  $selectedHierarchies[] = array(
                        'streamId'            => $streamId,
                        'substreamId'         => $substreamId,
                        'specializationId'    => $specializationId
                  );
                  $baseCourseDetails = $baseCourseLib->getValues(array('baseEntityArr'=>$selectedHierarchies, 'arrangeInAlpha'=>'yes'));
                  //_p($baseCourseDetails);die;
                  foreach ($baseCourseDetails as $levels) {
                        foreach ($levels as $key => $value) {
                              $baseCoursesList[$key] = $value;
                        }
                  }
            }else{
                  $this->load->builder('ListingBaseBuilder', 'listingBase');
                  $listingBaseBuilder   = new ListingBaseBuilder();
                  $baseCourseRepository = $listingBaseBuilder->getBaseCourseRepository();
                  $baseCourses = $baseCourseRepository->getBaseCoursesByBaseEntities($streamId, $substreamId, $specializationId);

                  if(count($baseCourses) > 0){
                        $result= $baseCourseRepository->findMultiple($baseCourses);
                        foreach ($result as $baseCourseId => $baseCourseObj) {
                              $baseCoursesList[$baseCourseId] = $baseCourseObj->getName();
                        }
                  }
            }
            return $baseCoursesList;
      }

      public function getLevelCredentailOfBaseCourse(){
            $baseCourse = $this->input->post('baseCourseId',true);
            if($baseCourse == 0){
                  $baseCourse = 1;
            }
            $baseCourses = array($baseCourse);

            $this->load->builder('ListingBaseBuilder', 'listingBase');
            $listingBaseBuilder   = new ListingBaseBuilder();
            $baseCourseRepository = $listingBaseBuilder->getBaseCourseRepository();
            $result = $baseCourseRepository->getLevelCredentailOfBaseCourses($baseCourses);
            $result = $result[$baseCourse];
            $ids = $result['credentialId'];
            $ids[] = $result['levelId'];
            $credentialList = array();
            $courseLevelList = array();

            $baseAttributeLibrary = $this->load->library("listingBase/BaseAttributeLibrary");
            $attributeNameById = $baseAttributeLibrary->getValueNameByValueId($ids);

            $credentialList[0] = "All Credential";
            foreach ($result['credentialId'] as $credentialId) {
                  $credentialList[$credentialId] = $attributeNameById[$credentialId];
            }

            $courseLevelList[0] = "All Course Level";
            $courseLevelList[$result['levelId']] = $attributeNameById[$result['levelId']];
            echo json_encode(array(
                              "credentialList"  => $credentialList,
                              "courseLevelList" => $courseLevelList ));
      }

      public function getEducationTypeAndDeliveryMethod(){
            $dynamicAttributeList = array(
                                    'Education Type'           =>'education_type',
                                    'Medium/Delivery Method'   =>'medium_delivery'
                                    );

            $baseAttributeLibrary = $this->load->library("listingBase/BaseAttributeLibrary");
            $courseAttrubuteList = $baseAttributeLibrary->getValuesForAttributeByName(array_keys($dynamicAttributeList));
            $courseAttrubuteList['educationType'] = $courseAttrubuteList['Education Type'];
            unset($courseAttrubuteList['Education Type']);
            $courseAttrubuteList['deliveryMethod'] = $courseAttrubuteList['Medium/Delivery Method'];
            unset($courseAttrubuteList['Medium/Delivery Method']);
            return $courseAttrubuteList;
      }

      public function getCourseListingsByClient(){
            $clientId = $this->input->post('clientId',true);
            echo json_encode($this->MISCommonLib->getCourseListingsByClient($clientId));
      }

      private function _populateAbroadCountries(& $data)
      {
            $locationBuilder = new LocationBuilder;
            $this->locationRepository = $locationBuilder->getLocationRepository();
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

	function index()
	{		
		$data = $this->_loadDependecies();
 		$this->load->view('trackingMIS/misTemplate', $data);
 	}

	
 	function overview($source='global'){
 		$data = $this->_loadDependecies('OVERVIEW');
		$data['source'] = $source;
		$data['misSource'] = $source;
		$data['colorCodes'] = $this->colorCodes;
		$data['metric'] = 'overview';
 		$this->load->view('trackingMIS/misTemplate', $data);
 	}

 	function registrations(){
 		$data = $this->_loadDependecies('REGISTRATION');
		$data['source'] = $source;
		$data['misSource'] = 'global';
		$data['colorCodes'] = $this->colorCodes;
		$data['metric'] = 'registrations';
 		$this->load->view('trackingMIS/misTemplate', $data);
 	}

 	function traffic(){
 		$data = $this->_loadDependecies('TRAFFIC');
		$data['source'] = $source;
		$data['misSource'] = 'global';
		$data['colorCodes'] = $this->colorCodes;
		$data['metric'] = 'traffic';
 		$this->load->view('trackingMIS/misTemplate', $data);
 	}

	public function responses(){
		$data = $this->_loadDependecies('RESPONSES');
		$data['misSource'] = 'global';
		$data['colorCodes'] = $this->colorCodes;
		$data['metric'] = 'responses';
		$this->load->view('trackingMIS/misTemplate', $data);
	}


	public function engagement(){
		$data = $this->_loadDependecies('ENGAGEMENT');
		$data['misSource'] = 'global';
		$data['colorCodes'] = $this->colorCodes;
		$data['metric'] = 'engagement';
		$this->load->view('trackingMIS/misTemplate', $data);
	}


      public function leads($source='global'){
            $data = $this->_loadDependecies('LEADS');
            $data['source'] = $source;
            $data['misSource'] = $source;
            $data['colorCodes'] = $this->colorCodes;
            $data['metric'] = 'leads';
            $this->load->view('trackingMIS/misTemplate', $data);
      }

	public function login(){
		$user = $this->trackingLib->checkValidUser(true,false);
		if((integer)($user[0]['userid']) > 0)
		{
			if($user['isSASalesDashboardUser']=== true){
				header("Location: ".SHIKSHA_HOME."/trackingMIS/saSalesDashboard");
			}else{
				header("Location: ".SHIKSHA_HOME."/trackingMIS/Dashboard/overview");
			}
			die;
		}else if($user === false){
			$this->load->view('trackingMIS/loginPage');
			return true;
		}else{
			// What even happened?
			show_404_abroad();
			return false;
		}		
	}
	
	public function unauthorizedUser(){
		$this->load->view('trackingMIS/unauthorizedUser');
	}

      function getTopDataForOverview($source='global'){
            $dateRange         = $this->input->post('dateRange',true);
            $showGrowth        = $this->input->post('showGrowth',true);
            $sourceApplication = $this->input->post('sourceApplication',true);
            $aspect            = $this->input->post('aspect',true);
            $metric            = $this->input->post('metric',true);
            //_p($aspect);die            
            $inputRequest = array(
                  'dateRange'         =>$dateRange,
                  'showGrowth'        => $showGrowth,
                  'sourceApplication' => ($sourceApplication == "")?"all":$sourceApplication,
                  'aspect'            => $aspect,
                  'misSource'         => ($source == 'abroad')?"STUDY ABROAD":(($source == 'national')?"DOMESTIC":"SHIKSHA"),
                  "metric"                => $metric,
                  "topNResult"            =>10
            );

            if($metric == "response"){
                  echo $this->MISCommonLib->getTopResponseStats($inputRequest);      
            }else if($metric == "registration"){
                  echo $this->MISCommonLib->getTopRegistrationStats($inputRequest);
            }else if($metric == "traffic"){
                  echo $this->MISCommonLib->getTopTrafficStats($inputRequest);
            }
      }

      function prepareOverviewToptiles($source='global'){
            $dateRange = array(
                  'startDate' => $this->input->get('startdate'),
                  'endDate'   => $this->input->get('enddate'),
            );
            $sourceApplication = $this->input->get('source');
            $dataFor = $this->input->get('dataFor');
            
            $inputRequest = array(
                  'dateRange'         =>$dateRange,
                  'sourceApplication' => ($sourceApplication == "")?"all":$sourceApplication,
                  'dataFor'            => $dataFor,
                  'misSource'         => ($source == 'abroad')?"STUDY ABROAD":(($source == 'national')?"DOMESTIC":"SHIKSHA")
            );
            echo $this->MISCommonLib->prepareOverviewToptiles($inputRequest);
      }

      function prepareOverviewDonutChart($source='global'){
            $dateRange = array(
                  'startDate' => $this->input->get('startdate'),
                  'endDate'   => $this->input->get('enddate'),
            );
            $sourceApplication = $this->input->get('source');
            $aspect            = $this->input->get('splitAspect');
            $metric            = $this->input->get('metric');
            
            $inputRequest = array(
                  'dateRange'         =>$dateRange,
                  'sourceApplication' => ($sourceApplication == "")?"all":$sourceApplication,
                  'aspect'            => $aspect,
                  'misSource'         => ($source == 'abroad')?"STUDY ABROAD":(($source == 'national')?"DOMESTIC":"SHIKSHA"),
                  "metric"                => $metric,
                  "topNResult"            =>10
            );
            //_p($inputRequest);die;
            echo $this->MISCommonLib->prepareOverviewDonutChart($inputRequest);
      }

      function diffChartDataForAssistantMIS($chartFilter =''){
            $inputArray = $this->input->get_post('inputArray',true);
            //$resultData['topTilesData'] = $this->MISCommonLib->MISTopTiles($inputArray);
            //_p($chartFilter);_p($inputArray);die;
            switch ($chartFilter) {
                  case 'topTiles':
                        $resultData['topTilesData'] = $this->MISCommonLib->MISTopTiles($inputArray);
                        break;

                  case 'trands':
                        //$result = $this->MISCommonLib->registrationTrands($inputArray);
                              


                        if($inputArray['lineChartType'] == "comparision"){
                              $inputArray['assistantFilter'] = "withAssistant";
                              $result = $this->MISCommonLib->MISTrands($inputArray);
                              //echo 'fd';_p($result);die;
                              $dates = array(
                                    'startDate' => $inputArray['dateRange']['startDate'],
                                    'endDate' => $inputArray['dateRange']['endDate']
                              );
                              $resultData['resultsForGraph'] = count($result) > 0 ? $this->MISCommonLib->insertZeroValuesForLineChart($result, $dates, $inputArray['view']) : array();
                              if($inputArray['lineChart'] == "sessionDurationWithAssistant"){
                                    $inputArray['lineChart'] = "sessionDurationWithoutAssistant";
                                    $inputArray['assistantFilter'] = "withoutAssistant";
                                    $result = $this->MISCommonLib->MISTrands($inputArray);
                                    $resultData['comparisonResultsForGraph'] = count($result) > 0 ? $this->MISCommonLib->insertZeroValuesForLineChart($result, $dates, $inputArray['view']) : array();
                              }else if($inputArray['lineChart'] == "sessionCountWithAssistant"){
                                    $inputArray['lineChart'] = "sessionCountWithoutAssistant";
                                    $inputArray['assistantFilter'] = "withoutAssistant";
                                    $result = $this->MISCommonLib->MISTrands($inputArray);
                                    $resultData['comparisonResultsForGraph'] = count($result) > 0 ? $this->MISCommonLib->insertZeroValuesForLineChart($result, $dates, $inputArray['view']) : array();
                              }else if($inputArray['lineChart'] == "pagesCountWithAssistant"){
                                    $inputArray['lineChart'] = "pagesCountWithoutAssistant";
                                    $inputArray['assistantFilter'] = "withoutAssistant";
                                    $result = $this->MISCommonLib->MISTrands($inputArray);
                                    $resultData['comparisonResultsForGraph'] = count($result) > 0 ? $this->MISCommonLib->insertZeroValuesForLineChart($result, $dates, $inputArray['view']) : array();
                              }else if($inputArray['lineChart'] == "queriesVsAnsQueries"){
                                    $inputArray['lineChart'] = "answeredQueries";
                                    $inputArray['assistantFilter'] = "answeredQueries";
                                    $result = $this->MISCommonLib->MISTrands($inputArray);
                                    $resultData['comparisonResultsForGraph'] = count($result) > 0 ? $this->MISCommonLib->insertZeroValuesForLineChart($result, $dates, $inputArray['view']) : array();
                              }else if($inputArray['lineChart'] == "queriesVsAnsQueriesPerSessions"){
                                    $inputArray['lineChart'] = "answeredPerSessions";
                                    $inputArray['assistantFilter'] = "answeredQueries";
                                    $result = $this->MISCommonLib->MISTrands($inputArray);
                                    $resultData['comparisonResultsForGraph'] = count($result) > 0 ? $this->MISCommonLib->insertZeroValuesForLineChart($result, $dates, $inputArray['view']) : array();
                              }
                        }else{
                              $result = $this->MISCommonLib->MISTrands($inputArray);
                              //echo 'fd';_p($result);die;
                              $dates = array(
                                    'startDate' => $inputArray['dateRange']['startDate'],
                                    'endDate' => $inputArray['dateRange']['endDate']
                              );
                              $resultData['resultsForGraph'] = count($result) > 0 ? $this->MISCommonLib->insertZeroValuesForLineChart($result, $dates, $inputArray['view']) : array();
                        }
                        //_p($resultData);die;
                        break;

                  case 'split':
                        //$resultData['splitData'] = $this->MISCommonLib->registrationSplits($inputArray);
                        $resultData['splitData'] = $this->MISCommonLib->MISSplits($inputArray);
                        //_p($resultData['splitData']);die;
                        break;
            }
            echo json_encode($resultData);
      }

      // Registration Flow for shiksha/Domestic/Abroad   //shikshaRegistration
      public function diffChartDataForMIS($chartFilter=''){
            $inputArray = $this->input->get_post('inputArray',true);
            //_p($inputArray);die;
            switch ($chartFilter) {
                  case 'topTiles':
                        //$resultData['topTilesData'] = $this->MISCommonLib->registrationTiles($inputArray);
                        $resultData['topTilesData'] = $this->MISCommonLib->MISTopTiles($inputArray);
                        if($inputArray['dateRange']['startDateToCompare'] != ''){
                              $inputArray['dateRange']['startDate'] = $inputArray['dateRange']['startDateToCompare'];
                              $inputArray['dateRange']['endDate'] = $inputArray['dateRange']['endDateToCompare'];
                              $resultData['compareTilesData'] = $this->MISCommonLib->MISTopTiles($inputArray);
                        }
                        break;

                  case 'sessionSplit':
                        if($inputArray['isUTMFilterRequired']){
                              $resultData = $this->MISCommonLib->MISSessionSplit($inputArray);
                        }else{
                              //_p($inputArray);die;
                              if($inputArray['metric'] == 'engagement'){
                                    if($inputArray['aspect'] == 'pageviews'){
                                          $inputArray['metric'] = 'traffic';
                                          $inputArray['aspect'] = 'pageViews';
                                    }     
                              }
                              $resultData = $this->MISCommonLib->preprareUTMDataForMIS($inputArray,$inputArray['trafficSource']);
                        }
                        break;

                  case 'trands':
                        //$result = $this->MISCommonLib->registrationTrands($inputArray);
                        $result = $this->MISCommonLib->MISTrands($inputArray);
                        //echo 'fd';_p($result);die;
                        if($inputArray['misSource'] == 'STUDY ABROAD' && (($inputArray['pageName'] == 'searchPage' &&  $inputArray['metric'] =='home')||
                            ($inputArray['metric'] =='exam_upload'))
                        ){
                              if($inputArray['dateRange']['startDateToCompare'] != '')
                              {
                                    $dates = array(
                                          'startDateToCompare' => $inputArray['dateRange']['startDateToCompare'],
                                          'endDateToCompare' => $inputArray['dateRange']['endDateToCompare'],
                                          'startDate' => $inputArray['dateRange']['startDate'],
                                          'endDate' => $inputArray['dateRange']['endDate']
                                    );
                                    $inputArray['dateRange']['startDate'] = $inputArray['dateRange']['startDateToCompare'];
                                    $inputArray['dateRange']['endDate'] = $inputArray['dateRange']['endDateToCompare'];
                                    $resultToCompare = $this->MISCommonLib->MISTrands($inputArray);

                                    if((count($result) > 0) || (count($resultToCompare)>0)){
                                          $inputArray['dateRange']['startDate'] = $dates['startDate'];
                                          $inputArray['dateRange']['endDate'] = $dates['endDate'];
                                          $resultData['resultsForGraph'] = $this->MISCommonLib->prepareDataForLineChartForDBData($result, $inputArray);
                                          $inputArray['dateRange']['startDate'] = $dates['startDateToCompare'];
                                          $inputArray['dateRange']['endDate'] = $dates['endDateToCompare'];
                                          $resultData['comparisonResultsForGraph'] = $this->MISCommonLib->prepareDataForLineChartForDBData($resultToCompare, $inputArray);
                                          //echo 1;die;
                                    }else{
                                          $resultData['resultsForGraph'] = '';
                                          $resultData['comparisonResultsForGraph'] = '';
                                    }
                              }
                              else{
                                    $resultData['resultsForGraph'] = count($result) > 0 ? $this->MISCommonLib->prepareDataForLineChartForDBData($result,$inputArray) : array();
                              }
                        }else{
                              if($inputArray['dateRange']['startDateToCompare'] != ''){
                                    $dates = array(
                                          'startDateToCompare' => $inputArray['dateRange']['startDateToCompare'],
                                          'endDateToCompare' => $inputArray['dateRange']['endDateToCompare'],
                                          'startDate' => $inputArray['dateRange']['startDate'],
                                          'endDate' => $inputArray['dateRange']['endDate']
                                    );
                                    $inputArray['dateRange']['startDate'] = $inputArray['dateRange']['startDateToCompare'];
                                    $inputArray['dateRange']['endDate'] = $inputArray['dateRange']['endDateToCompare'];
                                    $resultToCompare = $this->MISCommonLib->MISTrands($inputArray);

                                    if((count($result) > 0) || (count($resultToCompare)>0)){
                                          $resultData['resultsForGraph'] = $this->MISCommonLib->insertZeroValuesForLineChart($result, $dates, $inputArray['view']);
                                          
                                          $dates = array(
                                                'startDate' => $dates['startDateToCompare'],
                                                'endDate' => $dates['endDateToCompare']
                                          );
                                          $resultData['comparisonResultsForGraph'] = $this->MISCommonLib->insertZeroValuesForLineChart($resultToCompare, $dates, $inputArray['view']);
                                    }else{
                                          $resultData['resultsForGraph'] = '';
                                          $resultData['comparisonResultsForGraph'] = '';
                                    }
                              }else{
                                    $dates = array(
                                          'startDate' => $inputArray['dateRange']['startDate'],
                                          'endDate' => $inputArray['dateRange']['endDate']
                                    );
                                    $resultData['resultsForGraph'] = count($result) > 0 ? $this->MISCommonLib->insertZeroValuesForLineChart($result, $dates, $inputArray['view']) : array();
                              }
                        }
                              
                        break;

                  case 'split':
                        //$resultData['splitData'] = $this->MISCommonLib->registrationSplits($inputArray);
                        $resultData['splitData'] = $this->MISCommonLib->MISSplits($inputArray);
                        //_p($resultData['splitData']);die;
                        if($inputArray['dateRange']['startDateToCompare'] != ''){
                              $inputArray['dateRange']['startDate'] = $inputArray['dateRange']['startDateToCompare'];
                              $inputArray['dateRange']['endDate'] = $inputArray['dateRange']['endDateToCompare'];
                              $resultData['compareSplitData'] = $this->MISCommonLib->MISSplits($inputArray);
                        }
                        break;

                  case 'dataTable':
                        //$resultData = $this->MISCommonLib->registrationTable($inputArray);
                        $resultData = $this->MISCommonLib->MISTable($inputArray);
                        break;
            }            
            //_p($resultData);die;
            echo json_encode($resultData);
      }

      function pbtTracking($source='pbt'){
            $data = $this->_loadDependecies('OVERVIEW');
            $data['source'] = $source;
            $data['misSource'] = $source;
            $data['colorCodes'] = $this->colorCodes;
            $data['metric'] = 'overview';
            $fromDate = $this->input->get("fromdate");
            $toDate = $this->input->get("todate");
            if(empty($toDate) && !empty($fromDate))
                  $toDate = $fromDate;

            if(!empty($fromDate)){
                  $fromDate = date("Y-m-d 00:00:00", strtotime($fromDate));
                  $toDate = date("Y-m-d 23:59:59", strtotime($toDate));
            }

            $this->load->model('overview_model', 'ListingsModel');                       

            // $data['uniquePBTs'] =  $this->overview_model->getUniquePBTPixels();
            $data['pbtData'] = $this->overview_model->getPBTTrackingDetails($fromDate, $toDate);
            $pixelIds = array_keys($data['pbtData']);
            $data['pbtPixelData'] = $this->overview_model->getPBTPixelDetails($pixelIds);
            $this->load->view('trackingMIS/misTemplate', $data);
      }

      function experimentalRealTimeResponses(){
            
            $data = $this->_loadDependecies('OVERVIEW');
            // $courses = array(1653, 232612, 2340, 227604, 1688, 203483, 190788, 27013, 245140, 202130);
            $instituteIds = array(33966, 34196, 28499, 46763, 509, 35619, 27682, 23159, 61000, 53938, 2933, 307, 3959);

            $this->load->model('overview_model', 'ListingsModel');
            $courseDetails = $this->overview_model->getCoursesOfInstitutes($instituteIds);
            $instuteDetails = $this->overview_model->getInstituteDetails($instituteIds);
            $courses = array_keys($courseDetails);

            $time = date('Y-m-d H:i:s', strtotime('-30 minutes'));
            $source = 'experimentalResponses';
            $data['source'] = $source;
            $data['misSource'] = $source;
            $data['colorCodes'] = $this->colorCodes;
            $data['courseDetails'] = $courseDetails;
            $data['instuteDetails'] = $instuteDetails;
            $data['misinstituteIds'] = $instituteIds;
            
            $data['responseData'] = $this->overview_model->getResponseDataOfCourses($courses, $time);
            
            
            $this->load->view('trackingMIS/misTemplate', $data);
      }

      function userHistory(){
            $this->trackingLib->checkValidUser();
            $returnData = array();
            $returnData['fieldList'] = array(
                  array("field"=>"S. No.","width" => "5%"),
                  array("field"=>"Sess No","width" => "5%"),
                  array("field"=>"pageURL","width" => "46%"),
                  array("field"=>"Page Group","width" => "10%"),
                  array("field"=>"Entity Id","width" => "7%"),
                  array("field"=>"source <br> device <br> site","width" => "8%"),
                  array("field"=>"Child Page","width" => "9%"),
                  array("field"=>"Visit Time","width" => "9%"),
            );
            $this->load->view("trackingMIS/userHistoryTable",$returnData);
      }

      function getUserHistory($pivot = "",$email =""){
            $this->trackingLib->checkValidUser();
            
            $userDetails = $this->MISCommonLib->getUserDetailsByEmail($email);
            //_p($userDetails);die;
            if(isset($userDetails['userId'])){
                  $userId = $userDetails['userId'];
                  //$userId = 7560067;
                  $returnData = $this->MISCommonLib->getUserHistoryByUsedId($userId);
                  if(isset($returnData['error']) && $returnData['error'] == 1){
                        echo json_encode($returnData);
                        return;
                  }

                  // Logging Query Time
                  $logdata = "firstQueryTimeTaken(ms) : ".$returnData["firstQueryTimeTaken(ms)"]." :::: "."secondQueryTimeTaken(ms) : ".$returnData["secondQueryTimeTaken(ms)"]."<br>";
                  error_log(print_r(json_encode($logdata),true)."\n", 3, "/data/app_logs/userHistoryData.log_".date('Y-m-d'));

                  if(count($returnData['userHistory']) >0 ){
                        //$this->load->view("trackingMIS/userHistoryTable",$returnData);
                        // prepare html
                        $html = "";
                        $i = 1;
                        $prepareDataForCSV[0] = array("S. No.","Session No","pageURL","Page Group","Entity Id","source / device / site","Child Page","Visit Time");
                        $userHistory = $returnData['userHistory'];
                        $fieldList = $returnData['fieldList'];
                        //_p($userHistory);die;
                        foreach ($userHistory as $visitorId => $sessions) { 
                              foreach ($sessions as $sessionId => $pageviews) {
                                    foreach ($pageviews as $index => $pageDetails) {
                                          $html .= '<tr class="pointer odd">';
                                                foreach ($fieldList as $key => $fieldDetails) {
                                                      $prepareDataForCSV[$i][] = $pageDetails[$fieldDetails["field"]];
                                                      $html .= '<td style="word-break:break-word;width: '.$fieldDetails["width"].'!important;padding:5px !important;">'.$pageDetails[$fieldDetails["field"]].'</td>';
                                                }
                                                $i++;
                                          $html .= '</tr>';
                                    } 
                              }
                        }
                        echo json_encode(array("error" => 2,"html"=> $html,"dataForCSV" =>json_encode($prepareDataForCSV)));
                  }else{
                        echo json_encode(array("error" => 1, "message"=>"No record found in elasticsearch."));
                  }
            }else{
                  echo json_encode(array("error" => 1, "message"=>"User not found in Shiksha Database."));
            }
      }
}
	
?>
