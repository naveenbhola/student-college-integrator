<?php
/**
 * Created by PhpStorm.
 * User: abhinav
 * Date: 27/9/18
 * Time: 12:50 PM
 */

class ChpCrons extends MX_Controller {

    private $mailingList = array("abhinav.pandey@shiksha.com","kalva.nithishredyy@99acres.com","singh.satyam@shiksha.com");

    public function __construct() {
        parent::validateCron();
    }

    public function createPreDefinedCHPs() {
        $location = __dir__;
        $relativeFileLocation = "/../../../../../public/chp/";
        $location .= $relativeFileLocation;
        $files  =   array   (   "stream"        => "Stream.xlsx",
                                "subStream"     => "Sub-Stream.xlsx",
                                "specialization"=> "Specializations.xlsx",
                                "baseCourse"    => "Base Course.xlsx"
                            );
        $this->load->library('common/PHPExcel');
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $dataForCHPs    =   array();
        $fieldsUniqueIds    =   array(  'stream'        =>  array(),
                                        'subStream'     =>  array(),
                                        'specialization'=>  array(),
                                        'baseCourse'    =>  array(),
                                        'baseAttributes'=>  array()
                                    );
        foreach ($files as $file => $fileName){
            /**
             * PHPExcel
             */
            $objPHPExcel  = $objReader->load($location.$fileName);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $dataForCHPs[$file] = $this->_getExcelData($objWorksheet, $fieldsUniqueIds);
        }
        $fieldNames =   $this->_getFieldsNames($fieldsUniqueIds);
        $this->load->config("chpAPIs");
        $submitDataApi = $this->config->item('CHP_SAVECHP');
        $chpClient = $this->load->library('ChpClient');
        $entityX = "";
        $counter = 0;
        foreach ($dataForCHPs as $file => $fileData){
            if ($file == "stream"){
                $entityX    = "stream";
            }elseif ($file == "subStream"){
                $entityX    = "substream";
            }elseif ($file == "specialization"){
                $entityX    = "specialization";
            }elseif ($file == "baseCourse"){
                $entityX    = "basecourse";
            }else{
                continue;
            }

            foreach ($fileData as $value){
                $chpName = $this->_generateAndGetCHPNameAndDisplayName($file, $value, $fieldNames);
                $postData   =   array();
                $postData['chpType']     = 'single';
                $postData['streamId']    = $value['streamId'];
                $postData['substreamId'] = $value['subStreamId'];
                $postData['specializationId'] = $value['specializationId'];
                $postData['basecourseId']  = $value['baseCourseId'];
                $postData['educationtypeId'] = $value['educationTypeId'];
                $postData['deliverymethodId'] = $value['deliveryMethodId'];
                $postData['credentialId'] = $value['credentialId'];
                $postData['entityX'] = $entityX;
                $postData['entityY'] = "";
                $postData['name']        = $chpName;
                $postData['displayName'] = $chpName;
                $postData['userId'] = 2193486; // soma chaturvedi Id
                $postDataJson = json_encode($postData);
                ++$counter;
                echo "<br/> $counter SAVE CHP POST DATA ".$postDataJson;
                $result = $chpClient->makeCURLCall('POST',$submitDataApi,$postDataJson);
                echo "<br/> SAVE CHP POST RESPONSE ".$result;
            }
        }
    }

    private function _getExcelData(&$objWorksheet, &$fieldsUniqueIds) {
        $noOfRows = $objWorksheet->getHighestRow();
        $data = array();
        for ($i=3; $i<=$noOfRows; $i++){
            $temp   =   array(  'streamId'        =>    $objWorksheet->getCellByColumnAndRow(0, $i)->getValue(),
                                'subStreamId'     =>    $objWorksheet->getCellByColumnAndRow(1, $i)->getValue(),
                                'specializationId'=>    $objWorksheet->getCellByColumnAndRow(2, $i)->getValue(),
                                'baseCourseId'    =>    $objWorksheet->getCellByColumnAndRow(3, $i)->getValue(),
                                'educationTypeId' =>    $objWorksheet->getCellByColumnAndRow(4, $i)->getValue(),
                                'deliveryMethodId'=>    $objWorksheet->getCellByColumnAndRow(5, $i)->getValue(),
                                'credentialId'    =>    $objWorksheet->getCellByColumnAndRow(6, $i)->getValue()
                            );
            if ($temp['streamId'] > 0 && !in_array($temp['streamId'], $fieldsUniqueIds['stream'])){
                $fieldsUniqueIds['stream'][] = $temp['streamId'];
            }
            if ($temp['subStreamId'] > 0 && !in_array($temp['subStreamId'], $fieldsUniqueIds['subStream'])){
                $fieldsUniqueIds['subStream'][] = $temp['subStreamId'];
            }
            if ($temp['specializationId'] > 0 && !in_array($temp['specializationId'], $fieldsUniqueIds['specialization'])){
                $fieldsUniqueIds['specialization'][] = $temp['specializationId'];
            }
            if ($temp['baseCourseId'] > 0 && !in_array($temp['baseCourseId'], $fieldsUniqueIds['baseCourse'])){
                $fieldsUniqueIds['baseCourse'][] = $temp['baseCourseId'];
            }
            if ($temp['educationTypeId'] > 0 && !in_array($temp['educationTypeId'], $fieldsUniqueIds['baseAttributes'])){
                $fieldsUniqueIds['baseAttributes'][] = $temp['educationTypeId'];
            }
            if ($temp['deliveryMethodId'] > 0 && !in_array($temp['deliveryMethodId'], $fieldsUniqueIds['baseAttributes'])){
                $fieldsUniqueIds['baseAttributes'][] = $temp['deliveryMethodId'];
            }
            if ($temp['credentialId'] > 0 && !in_array($temp['credentialId'], $fieldsUniqueIds['baseAttributes'])){
                $fieldsUniqueIds['baseAttributes'][] = $temp['credentialId'];
            }
            $data[] = $temp;
        }
        return $data;
    }

    private function _getFieldsNames($fieldsUniqueIds){
        $returnData =   array(  'stream'        =>  array(),
                                'subStream'     =>  array(),
                                'specialization'=>  array(),
                                'baseCourse'    =>  array(),
                                'baseAttributes'=>  array()
                            );
        $this->load->builder('listingBase/ListingBaseBuilder');
        $listingBaseBuilder = new ListingBaseBuilder();
        if (array_key_exists('stream', $fieldsUniqueIds) && !empty($fieldsUniqueIds['stream'])){
            /**
             * StreamRepository
             */
            $streamRepository = $listingBaseBuilder->getStreamRepository();
            $data = $streamRepository->findMultiple($fieldsUniqueIds['stream']);
            foreach ($data as $value){
                $returnData['stream'][$value->getId()]    =   $value->getName();
            }
        }
        if (array_key_exists('subStream', $fieldsUniqueIds) && !empty($fieldsUniqueIds['subStream'])){
            /**
             * SubstreamRepository
             */
            $subStreamRepository = $listingBaseBuilder->getSubstreamRepository();
            $data = $subStreamRepository->findMultiple($fieldsUniqueIds['subStream']);
            foreach ($data as $value){
                $returnData['subStream'][$value->getId()]    =   $value->getName();
            }
            ksort($returnData['subStream']);
        }
        if (array_key_exists('specialization', $fieldsUniqueIds) && !empty($fieldsUniqueIds['specialization'])){
            /**
             * SpecializationRepository
             */
            $specializationRepository = $listingBaseBuilder->getSpecializationRepository();
            $data = $specializationRepository->findMultiple($fieldsUniqueIds['specialization']);
            foreach ($data as $value){
                $returnData['specialization'][$value->getId()]    =   $value->getName();
            }
        }
        if (array_key_exists('baseCourse', $fieldsUniqueIds) && !empty($fieldsUniqueIds['baseCourse'])){
            /**
             * BaseCourseRepository
             */
            $baseCourseRepository = $listingBaseBuilder->getBaseCourseRepository();
            $data = $baseCourseRepository->findMultiple($fieldsUniqueIds['baseCourse']);
            foreach ($data as $value){
                $returnData['baseCourse'][$value->getId()]    =   $value->getName();
            }
        }
        if (array_key_exists('baseAttributes', $fieldsUniqueIds) && !empty($fieldsUniqueIds['baseAttributes'])){
            /**
             * BaseAttributeLibrary
             */
            $baseAttributeLibrary = $this->load->library('listingBase/BaseAttributeLibrary');
            $data = $baseAttributeLibrary->getValueNameByValueId($fieldsUniqueIds['baseAttributes'], 'array');
            foreach ($data as $key => $value){
                $returnData['baseAttributes'][$key]    =   $value;
            }
        }
        return $returnData;
    }



    private function _generateAndGetCHPNameAndDisplayName($chpXValue, $chpData = array(), $feildNames){
        $pretext    =   "";
        if ($chpData['educationTypeId'] > 0){
            $pretext .= $feildNames['baseAttributes'][$chpData['educationTypeId']] . " ";
        }
        if ($chpData['deliveryMethodId'] > 0){
            $pretext .= $feildNames['baseAttributes'][$chpData['deliveryMethodId']] . " ";
        }
        if ($chpData['credentialId'] > 0){
            $pretext .= $feildNames['baseAttributes'][$chpData['credentialId']] . " ";
        }

        if (!empty($pretext)){
            $pretext .= "in ";
        }

        if ($chpXValue == "stream" && $chpData['streamId'] > 0){
            $pretext .= $feildNames['stream'][$chpData['streamId']];
        } elseif ($chpXValue == "subStream" && $chpData['subStreamId'] > 0){
            $pretext .= $feildNames['subStream'][$chpData['subStreamId']];
        } elseif ($chpXValue == "specialization" && $chpData['specializationId'] > 0){
            $pretext .= $feildNames['specialization'][$chpData['specializationId']];
        } elseif ($chpXValue == "baseCourse" && $chpData['baseCourseId'] > 0){
            $pretext .= $feildNames['baseCourse'][$chpData['baseCourseId']];
        } else {
          return "";
        }
        return $pretext;
    }

    /**
    * below function is used for creating CHP guide
    * @Author Nithish Reddy
    */

    public function createCHPGuide($params)
    {
        ini_set('memory_limit','2000M');
        ini_set("max_execution_time", "-1");

        //alerts
        $this->load->library('alerts_client');
        $this->alertClient  = new Alerts_client();

        if(!empty($params))
        {
            $params = explode(',', $params);
        }else
        {
            $params = array();
        }

        //get chp's those whose content modified in last 24 hours
        $pastDayContentModify = true;

        // send cron start mail
        $scriptStartTime   = time();
        $this->sendCronAlert("Started : ".__METHOD__, "");

        $this->load->config("chpAPIs");
        $url = $this->config->item('CHP_GET_ALL_BASIC_INFO');
        $this->ChpClient = $this->load->library('ChpClient');
        $guideLibrary = $this->load->library('ChpGuideGenerator');
        if($pastDayContentModify){
            $url .= '?pastDayContentModify='.$pastDayContentModify;    
        }
        
        $result = $this->ChpClient->makeCURLCall('POST',$url,json_encode($params));
        $result = json_decode($result,true);
        $result = $result['data'];
        $guideFailedChpIds = array();
        foreach ($result as $rKey => $rValue) {
            echo "<br/> CHP Guide Creation Started for  ".$rValue['chpId'];
            //$microTime = microtime(true) * 1000;
            $currentDate = date("Ymd"); 
            //$chpUrl = $rValue['url']."?isPdfCall=1&now=".$currentDate;
            $chpUrl = $rValue['url']."?isPdfCall=1";
            
            $resultInfo = $guideLibrary->generateCHPGuide($rValue['chpId'],$chpUrl);
            if($resultInfo['status'] == "success")
            {
                error_log("\n". $resultInfo["msg"],3,"/tmp/chpAutogenerateGuide.log");

                //insert into html cache purging queue
                /*if(!empty($rValue['chpId'])){
                    $arr = array("cache_type" => "htmlpage", "entity_type" => "chp", "entity_id" => $rValue['chpId'], "cache_key_identifier" => "");
                    $shikshamodel = $this->load->model("common/shikshamodel");
                    $shikshamodel->insertCachePurgingQueue($arr);
                }*/
            }
            else{
                $guideFailedChpIds[] = $resultInfo['chpId'];
                error_log("\n". $resultInfo["msg"],3,"/tmp/chpAutogenerateGuide.log");   
            }
            echo "<br/> CHP Guide Creation Ended for  ".$rValue['chpId'];
        }
        $scriptEndTime = time();
        $timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;


        $guideFailedMsg = "";

        if(!empty($guideFailedChpIds) && count($guideFailedChpIds) > 0)
        {
            $guideFailedMsg = "guide creation failed for following chpIds : ".implode('.', $guideFailedChpIds);
        }

        $text          = __METHOD__." Cron Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Contact Person: Nithish Reddy";

        if(!empty($guideFailedMsg))
        {
            $text .= "<br/>".$guideFailedMsg;
        }
        $this->sendCronAlert("Ended : ".__METHOD__, $text);
    }

    function sendCronAlert($subject, $body, $emailIds){

        if(empty($emailIds))
            $emailIds = $this->mailingList;

        foreach($emailIds as $key=>$emailId)
            $this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $body, "html", '', 'n');
    }

    public function createCHPCache($params)
    {
        ini_set('memory_limit','2000M');
        ini_set("max_execution_time", "-1");

        //alerts
        $this->load->library('alerts_client');
        $this->alertClient  = new Alerts_client();

        if(!empty($params))
        {
            $params = explode(',', $params);
        }else
        {
            $params = array();
        }

        // send cron start mail
        $scriptStartTime   = time();
        $this->sendCronAlert("Started : ".__METHOD__, "");

        $this->load->config("chpAPIs");
        $url = $this->config->item('CHP_CREATE_CHP_CACHE');
        $this->ChpClient = $this->load->library('ChpClient');

        $url .= "?key=".base64_encode("$#1K$#A_(#9_90F");
        $result = $this->ChpClient->makeCURLCall('POST',$url,json_encode($params),$headerArray);
        $result = json_decode($result,true);
        $result = $result['data'];
        $scriptEndTime = time();
        $timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;

        $text          = __METHOD__." Cron Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Contact Person: Nithish Reddy";

        $text .= "<br/> ChpIds Cache result List : "._p($result);

        $this->sendCronAlert("Ended : ".__METHOD__, $text);
    }
    public function createNginxCache($params)
    {
        ini_set('memory_limit','2000M');
        ini_set("max_execution_time", "-1");

        //alerts
        $this->load->library('alerts_client');
        $this->alertClient  = new Alerts_client();

        if(!empty($params))
        {
            $params = explode(',', $params);
        }else
        {
            $params = array();
        }

        // send cron start mail
        $scriptStartTime   = time();
        $this->sendCronAlert("Started : ".__METHOD__, "");

        $this->load->config("chpAPIs");
        $url = $this->config->item('CHP_GET_ALL_BASIC_INFO');
        $this->ChpClient = $this->load->library('ChpClient');
        $result = $this->ChpClient->makeCURLCall('POST',$url,json_encode($params));

        $CHP_PAGE_URL = $this->config->item('GET_CHP_PAGE_DATA');


        $APIS_CLIENT_DOMAIN = $this->config->item("GET_CHP_PAGE_DATA_CLIENT_API");

        $domainList = array($CHP_PAGE_URL,$APIS_CLIENT_DOMAIN);

        $result = json_decode($result,true);
        $result = $result['data'];
        $numberOfTimeRequests = 2;
        
            foreach ($result as $rKey => $rValue) {
                foreach ($domainList as $dkey => $dvalue) {
                    for ($i=0; $i < $numberOfTimeRequests; $i++) {
                       $postParams = array('url' => $rValue['url']);
                       $CHP_PAGE_FinalURL = $dvalue.'?data='.base64_encode(json_encode($postParams,JSON_UNESCAPED_SLASHES));
                       $resultInfo = $this->ChpClient->makeCURLCall('GET',$CHP_PAGE_FinalURL);
                       $resultInfo = json_decode($resultInfo);
                   }
                }
                usleep(500);
            }

        $scriptEndTime = time();
        $timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;

        $text          = __METHOD__." Cron Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Contact Person: Nithish Reddy";

        $this->sendCronAlert("Ended : ".__METHOD__, $text);
    }

    function populateCHPViewCountCache(){
        $this->validateCron();
        // allow cron to run with no time limit
        ini_set("max_execution_time", "-1");

        // load dependencies
        $this->load->library('alerts_client');
        $this->alertClient  = new Alerts_client();
        $redis_client       = PredisLibrary::getInstance();

        // send cron start mail
        $scriptStartTime   = time();
        $this->sendCronAlert("Started : ".__METHOD__, "");

        $chpViewCountHashKey    = "chp_view_count";

        // for chp
        $chpLibrary = $this->load->library('ChpLibrary');
        $chpViewCount = $chpLibrary->fetchCHPViewCount();
        
        // delete previous cache data
        $redis_client->deleteKey(array($chpViewCountHashKey));     
        
        // store data in cache
        if(!empty($chpViewCount))
        {
            $redis_client->addMembersToHash($chpViewCountHashKey,$chpViewCount,FALSE);    
        }

        $scriptEndTime = time();
        $timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
        $text          = __METHOD__." Script Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Contact Person: Nithish Reddy";
        $this->sendCronAlert("Ended : ".__METHOD__, $text);
    }
}
