<?php
require_once('vendor/autoload.php');

class CFInputView extends MX_Controller
{
	private $dbHandle;
        private $writeDBHandle;
        function __construct()
        {
                $this->load->library('DbLibCommon');
                $this->dbLibObj = DbLibCommon::getInstance('MISTracking');
                $this->dbHandle = $this->_loadDatabaseHandle();
                $this->writeDbHandle = $this->_loadDatabaseHandle('write');
        }	

    function updateViews()
    {
        $this->validateCron();
        $startDate = date("Y-m-d", strtotime("-1 day")).' 00:00:00';
        $endDate = date("Y-m-d").' 00:00:00';
        $startDate = convertDateISTtoUTC($startDate);
        $startDate = str_replace(" ", "T", $startDate);
        $endDate = convertDateISTtoUTC($endDate);
        $endDate = str_replace(" ", "T", $endDate);

        $absStart = microtime(TRUE);
        ini_set('memory_limit', '8096M');
        set_time_limit(0);

        $clientParams = array();
        $clientParams['hosts'] = array(SHIKSHA_ELASTIC_HOST);
        $client = new Elasticsearch\Client($clientParams);

        $params = array();

        $params['index'] = PAGEVIEW_INDEX_NAME_REALTIME_SEARCH;
        $params['type'] = 'pageview';

        $rangeFilter = array("range" => array("visitTime" => array("gte" => $startDate,"lte"  => $endDate)));

        $pageIdentifierFilter = array();
        $pageIdentifierFilter['term'] = array('pageIdentifier' => 'courseDetailsPage');

        $params['body']['query']['bool']['filter']['bool']['must'][] = $rangeFilter;
        $params['body']['query']['bool']['filter']['bool']['must'][] = $pageIdentifierFilter;

        $params['_source'] = array('visitorId', 'pageEntityId');

        $params["scroll"] = "2m";
        $params["size"] = 2000;

        $search = $client->search($params);

        $scroll_id = $search['_scroll_id'];

        $indexParams = array();
        $indexParams['body'] = array();

        $loopCount = 1;
        while (true) {
            $t1 = microtime(true);
            $response = $client->scroll(
                array(
                        "scroll_id" => $scroll_id,
                        "scroll" => "1m"
                     )
            );

            $t2 = microtime(true);
            if (count($response['hits']['hits']) > 0) {

                $visitorBatch = array();
                $viewBatch = array();
                
                foreach($response['hits']['hits'] as $result) {
                    $visitorId = $result['_source']['visitorId'];
                    $courseId  = $result['_source']['pageEntityId'];

                    if(!empty($visitorId)){
                        $visitorBatch[] = $visitorId;
                    }
                    
                    if(!empty($visitorId) && !empty($courseId)){
                        $viewBatch[]    = array($visitorId, $courseId);
                    }
                }

                $this->addVisitorBatch($visitorBatch);
                $this->addViewBatch($viewBatch);
                
                $scroll_id = $response['_scroll_id'];
            }else{
                // All done scrolling over data
                break;
            }
            error_log("Iteration: ".$loopCount);
            $loopCount++;
        }
    }

	private function addVisitorBatch($visitorBatch)
        {
                if(is_array($visitorBatch) && count($visitorBatch) > 0) {
                        $insertBatch = array();
                        foreach($visitorBatch as $visitorId) {
                                $insertBatch[] = "(\"$visitorId\")";
                        }
                        $sql = "INSERT INTO CF_INPUT_USERMAPPING(visitorId) ".
                                "VALUES ".implode(",", $insertBatch)." ".
                                "ON DUPLICATE KEY UPDATE visitorId = visitorId";
                        //error_log($sql);
                        $this->writeDbHandle->query($sql);
                }
        }

        private function addViewBatch($viewBatch)
        {
                if(is_array($viewBatch) && count($viewBatch) > 0) {
                        $insertBatch = array();
                        foreach($viewBatch as $rb) {
                                $insertBatch[] = "(\"".$rb[0]."\", ".$rb[1].")";
                        }

                        $sql = "INSERT INTO CF_INPUT_VIEW(visitorId, courseId) ".
                               "VALUES ".implode(",", $insertBatch)." ".
                               "ON DUPLICATE KEY UPDATE visitorId = visitorId, courseId = courseId";

                        //error_log($sql);
                        $this->writeDbHandle->query($sql);
                }
        }
}
