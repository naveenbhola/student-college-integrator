<?php 
class CooccurrenceSimilarity{
    private $CI;
    private $cooccurrenceMatrix;
    private $entityType;
    private $allArticles;
    public function __construct() {
        $this->CI = &get_instance();
        $this->entityType; // includes article & guide page
        $this->allArticles = array();
        $this->useElastic = (USE_ELASTIC_SEARCH && true); // former is universal flag to enable use of ES, while latter is for this script only
        $this->userIdCounter=1;
        $this->userIdSessionIdMap = array();
    }
    
    /*
     * To create cooccurrence data for listing/content
     */
    public function createCooccurrenceBasedRecommendations($entityType)
    { 
        $this->entityType = $entityType;
        // decide which type of entity is targeted...
        
        error_log('START: Main: Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        
        // 1. fetch the required data set (by userid/sessionId)
        error_log('START: ProcessUserMovement: Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        $userMovementDataSet = $this->_getUserMovementInformation($urlPattern);
        error_log('END: ProcessUserMovement: Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        // 2. create [cooccurrence matrix] using pairs from each user
        $this->_createCooccurrenceMatrix($userMovementDataSet);        
        // 3. read the cooccurrence matrix & insert to db
        error_log('START: Insertion: Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        $this->_setCooccurrenceBasedSimilarityData();
        error_log('END: Insertion: Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        
        error_log('END: Main: Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        return;
    }
    
    private function _getUserMovementInformation($urlPattern = array())
    {
        $userMovementDataArray = array();
        $size = 50000;
        // fetch user movement data from DB
        $params = array('dateCheck'   => date('Y-m-d',  strtotime('-6 month')),
                        'limitOffset' => 0,
                        'length'      => $size);
        do{
            unset($userMovementdata);
            // use elastic / mysql to get data for this
            //error_log('START:'.$k.'A ORORORORProcessUserMovement: Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
            $userMovementdata = $this->_getUserMovementDataByPageForDuration($params);
            //error_log('START:'.$k.'Z ORORORORProcessUserMovement: Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
            $numOfUserRecords = count($userMovementdata);
            //error_log('Pass-'.($params['limitOffset']).': Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
            //_p($userMovementdata);
            // process rows for entityIds 
            foreach ($userMovementdata as $k=>$data)
            {
                if($this->useElastic)
                {
                    $userId = $data['userId'];
                    $articleId  = intval($data['pageEntityId']);
                }
                else
                {
                    $userId = ($data['userId'] == -1?$data['sessionId']:$data['userId']);
                    //parse the url pattern (this will be different for different entity types[since some pageIdentifers & entityIds are not separated by '-'])
                    $data['url'] = parse_url($data['url'],PHP_URL_PATH);
                    if($this->entityType == 'applyContent')
                    {
                        //explode url to get article id
                        $explodedUrl = explode($urlPattern[0], $data['url']);
                        $articleId = substr($explodedUrl[1],1);
                    }
                    else if($this->entityType == 'content')
                    {
                        if(strpos($data['url'], $urlPattern[0]) !== false)
                        {
                            $explodedUrl = explode($urlPattern[0], $data['url']);
                        }
                        else if(strpos($data['url'], $urlPattern[1]) !== false)
                        {
                            $explodedUrl = explode($urlPattern[1], $data['url']);
                        }
                        $articleId  = intval($explodedUrl[1]);
                    }
                }
                //echo "<br>".$userId.",".$articleId;
                if($articleId > 0)
                {
                    // user has been encountered before...
                    if($userMovementDataArray[$userId])
                    {   // ..add the article to his list (if not already present)
                        if(in_array($articleId,$userMovementDataArray[$userId]) === false){
                            $userMovementDataArray[$userId][] =$articleId;
                        }
                    }
                    else{// create new set for that user
                        $userMovementDataArray[$userId] = array($articleId);
                    }
                    // add to all article list
                    $this->allArticles[$articleId] = true;
                }
                unset($userMovementdata[$k]);
            }
            
            if($numOfUserRecords<$size)
            {
                break;
            }else{
                $params['limitOffset'] += $params['length'];
                $params['length'     ] = $size;
            }
            
        }while(!($numOfUserRecords<$size));
        //error_log('PassEND : Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        //unset($userMovementdata);
        $this->allArticles = array_keys($this->allArticles);
        $userMovementDataArray = array_filter($userMovementDataArray, function($a){return (count($a)>1);});
        //_p($userMovementDataArray);
        return $userMovementDataArray;
    }
    /*
     * function to create a coccurrence matrix
     * step 1. initialize with all available articles
     * step 2. for each user's data set, increase the count at index([i,j]) where i,j are
     * all possible pairs of article ids for which the user has accessed the content
     */
    private function _createCooccurrenceMatrix($userMovementDataSet)
    {
        $this->cooccurrenceMatrix = array();
        // get list of all articles involved here
        $numOfAllArticles = count($this->allArticles);
        // step 1. initialize the matrix
        for($i=0;$i<$numOfAllArticles;$i++)
        {
            for($j=$i+1;$j<$numOfAllArticles;$j++)
            {
                $this->cooccurrenceMatrix[$this->allArticles[$i]][$this->allArticles[$j]] = 0;
            }
        }
         error_log('CMatrix Init done:size'.$numOfAllArticles.' Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        //_p($this->cooccurrenceMatrix);
        // step 2. process user's list of articles viewed
        foreach($userMovementDataSet as $userId => $articleList)
        {
            $numOfArticles = count($articleList);
            if(count($articleList)>=2)
            {
                for($i=0;$i<$numOfArticles;$i++)
                {
                    for($j=$i+1;$j<$numOfArticles;$j++)
                    {   // due to ordering a pair in user1 can be x,y while for another it can be y,x
                        // since we have already initialized the matrix, we must increment only at those indices that are available in matrix
                        if(isset($this->cooccurrenceMatrix[$articleList[$i]][$articleList[$j]]))
                        $this->cooccurrenceMatrix[$articleList[$i]][$articleList[$j]]++;
                        else if(isset($this->cooccurrenceMatrix[$articleList[$j]][$articleList[$i]]))
                        $this->cooccurrenceMatrix[$articleList[$j]][$articleList[$i]]++;
                    }
                }
            }
        }
        error_log('CMatrix built: Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        //_p($this->cooccurrenceMatrix);
    }
    
    private function _setCooccurrenceBasedSimilarityData()
    {
        $insertionBatch = array();
        //error_log('Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/logliklihood.txt');
        // traverse the coccurrence matrix & prepare batch for insertion
        foreach($this->cooccurrenceMatrix as $rowId => $row)
        {
            foreach($row as $colId=>$cell)
            {
                if($cell>0)
                {
                    $insertionBatch[] = array('entityType'          =>$this->entityType,
                                              'primaryEntityId'     =>$rowId,
                                              'secondaryEntityId'   =>$colId,
                                              'cooccurenceScore'    =>$cell,
                                              'dateUpdated'         =>date('Y-m-d H:i:s'),
                                              'status'              =>'live');
                }
            }
        }
        //_p($insertionBatch);
        // insert...
        $abroadListingCronModel = $this->CI->load->model('listing/abroadlistingcronmodel');
        $abroadListingCronModel->insertCooccurrenceBasedRecommendationData($insertionBatch,$this->entityType);
    }
    private function _getUserMovementDataByPageForDuration ($data){
        if($this->useElastic){
            switch($this->entityType)
            {
                case 'content': $data['pageIdentifier'] = array('articlePage','guidePage'); break;
                case 'applyContent': $data['pageIdentifier'] = array('applyContentPage'); break;
                default: return false;
            }
            $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
            $clientCon = $ESConnectionLib->getShikshaESServerConnection();
            $userMovementdata = $this->_getResultsFromElasticSearchQuery($clientCon,$data);
        }else{
            switch($this->entityType)
            {
                case 'content': $urlPattern = array('-articlepage-','-guidepage-'); break;
                case 'applyContent': $urlPattern = array('-applycontent'); break;
                case 'examPage': $urlPattern = array('-abroadexam'); break;
                default: return false;
            }
            $data['urlPattern']  = $urlPattern;
            $userMovementrackingModel   = $this->CI->load->model('common/studyabroadusertrackingmodel');
            $userMovementdata = $userMovementrackingModel->getUserMovementDataByPageForDuration($data);
        }
        //error_log('START: ORORORORProcessUserMovement: Memory Usage '.((memory_get_peak_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        //error_log('START: ORORORORProcessUserMovement: Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        //die;
        
        return $userMovementdata;
    }
    /*
     * prepare elastic search query params
     */
    private function _getResultsFromElasticSearchQuery($clientCon, $data)
    {
        $offset = $data['limitOffset'];
        $size = $data['length'];
        $pageIdentifierClause = array();
        foreach($data['pageIdentifier'] as $pageIdentifier)
        {
            $pageIdentifierClause[] = array(
                                                        "term"=> array(
                                                            "pageIdentifier"=> $pageIdentifier
                                                        )
                                                    );
        }
        $params = array();
        $params['index'] = PAGEVIEW_INDEX_NAME;
        $params['filter_path'] =array("hits.hits._source");
        $params['type'] = 'pageview';
        $params['body'] = array(
                            "size"  => $size,
                            "from"  => $offset,
                            "_source"=> array("sessionId","userId","pageEntityId"),
                            "query" => array(
                                "bool"=> array(
                                    "filter"=> array(
                                        "bool"=> array(
                                            "must"=> array(
                                                array(
                                                    "range"=> array(
                                                        "visitTime"=> array(
                                                            "gte"=> "now-3M/d",
                                                            "time_zone"=>ELASTIC_TIMEZONE
                                                        )
                                                    )
                                                ),
                                                array(
                                                    "term"=> array(
                                                        "isStudyAbroad"=> "yes"
                                                    )
                                                )
                                            ),
                                            "should"=> $pageIdentifierClause
                                        )
                                    )
                                )
                            )
                        );
        error_log('ELA1 : Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        $result = $clientCon->search($params);
        error_log('ELA2 : Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        
        //make map of userids
        error_log('MAP1 : Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        $returnData = $this->_addToUserIdMap($result['hits']['hits']);
        error_log('MAP2 : Memory Usage '.((memory_get_peak_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        //_p($result['hits']['hits']);die;
        //return $result['hits']['hits'];
        return $returnData;
    }
    
    private function _addToUserIdMap($resultFromES)
    {
        $returnData = array();
        foreach($resultFromES as $row)
        {
            if(!is_numeric($row['_source']['userId']) || !($row['_source']['userId'] > 0)) // sessionId
            {
                if($this->userIdSessionIdMap[$row['_source']['sessionId']] == "")
                {
                        $this->userIdSessionIdMap[$row['_source']['sessionId']] = 'A'.$this->userIdCounter++;
                }
                $returnData[] = array('userId'=>$this->userIdSessionIdMap[$row['_source']['sessionId']],
                                      'pageEntityId'=>$row['_source']['pageEntityId']);
            }
            else
            {
                $returnData[] = array('userId'=>$row['_source']['userId'],
                                      'pageEntityId'=>$row['_source']['pageEntityId']);
            }
        }
        return $returnData;
    }
    public function getCooccurrenceBasedRecommendations ($data){
        
    }
    
}
?>
