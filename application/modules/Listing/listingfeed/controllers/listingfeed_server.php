<?php

/**
* Class listingfeed_server extends the controller class for the shiksha
*
* @package ListingFeedModel
* @author shiksha team
*/

class listingfeed_server extends MX_Controller {
    function _init() {
	$this->load->library('dbLibCommon');
        $this->dbLibObj = DbLibCommon::getInstance('Listing');
    }

    function index(){
        $this->_init();
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library('listingconfig');
        $config['functions']['runCronJobListingFeed'] = array('function' => 'listingfeed_server.runCronJobListingFeed');
        $config['functions']['runCronJobEventFeed'] = array('function' => 'listingfeed_server.runCronJobEventFeed');
        $config['functions']['ResultCountSet'] = array('function' => 'listingfeed_server.ResultCountSet');
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        
        return $this->$method($args[1]);
    }
    
   /**
    *
    * @see Lib  runCronJob API 
    * @return string
    * @throws error_log_shiksha
    * @param int $appId
    * @param string $flag ( Flag to take whole dump or incremental dump)
    * @param int $limit
    * @param string $time
    * @param string $type
    * @return string
    */
    
    function runCronJobListingFeed($request)
    {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $time  = $parameters['1'];
        $flag = $parameters['2'];
        $queryCmd_offset = $parameters['3'];
        $queryCmd_rows = $parameters['4'];
        //error_log("runCronJob parameters".print_r($parameters,true));
        $this->load->model('ListingFeedModel');
        // commented code for db load distribution 
        //$dbHandle = $this->ListingFeedModel->getDbHandle();
        $dbHandle = $this->dbLibObj->getReadHandle();
        if($dbHandle == ''){
            error_log_shiksha("no db handle listingfeed_server.runCronJob");
        }
        $listing_xml_feed = $this->ListingFeedModel->get_data_listing_xml_feed($dbHandle,'institute',$time,$flag,$queryCmd_offset,$queryCmd_rows);
        //error_log("raviraj".$listing_xml_feed['totalRowsListing']);
        $msgArray = array(
                        'listing_xml_feed'=>array($listing_xml_feed)
                  );
        $response = array(base64_encode(json_encode($msgArray)),'string');
        //error_log('raviraj',print_r($response,true));
        return $this->xmlrpc->send_response($response);
    }


    function runCronJobEventFeed($request)
    {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $time  = $parameters['1'];
        $flag = $parameters['2'];
        $queryCmd_offset = $parameters['3'];
        $queryCmd_rows = $parameters['4'];
        //error_log("runCronJob parameters".print_r($parameters,true));
        $this->load->model('ListingFeedModel');
        // commented code for db load distribution 
        //$dbHandle = $this->ListingFeedModel->getDbHandle();
        $dbHandle = $this->dbLibObj->getReadHandle();
        if($dbHandle == ''){
            error_log_shiksha("no db handle listingfeed_server.runCronJob");
        }
        $event_xml_feed = $this->ListingFeedModel->getEventDetails($dbHandle,'event',$time,$flag,$queryCmd_offset,$queryCmd_rows);
        //error_log("runCronJob eventfeed".print_r($event_xml_feed,true));
        $msgArray = array(
                        'event_xml_feed'=>array($event_xml_feed)
                  );
        $response = array(base64_encode(json_encode($msgArray)),'string');
        //error_log('raviraj',print_r($response,true));
        return $this->xmlrpc->send_response($response);
    }

    function ResultCountSet($request)
    {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $time  = $parameters['1'];
        error_log(' feed server 1 '.print_r($parameters,true));
        //connect DB
        // commented code for db load distribution 
        /*$dbConfig = array( 'hostname'=>'localhost');
        $this->listingconfig->getDbConfig_test($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);*/
        $dbHandle = $this->dbLibObj->getReadHandle();
        if($dbHandle == ''){
            log_message('error','getCountryTable can not create db handle');
        }
        $response = array();
        $queryCmdMain = 'select SQL_CALC_FOUND_ROWS  * from  institute,listings_main where listings_main.listing_type_id =institute.institute_id and listings_main.listing_type = "institute" and listings_main.submit_date >= ?';
        $querymain = $dbHandle->query($queryCmdMain, $time);
        $queryCmd1 = 'SELECT FOUND_ROWS() as totalRows';
        $query1 = $dbHandle->query($queryCmd1);
        $totalRows1 = 0;
        foreach ($query1->result() as $row1) {
            $totalRows1 = $row1->totalRows;
        }
        
        $queryCmd = 'select  SQL_CALC_FOUND_ROWS  v.venue_name,v.Address_Line1,v.Address_Line2,v.phone,v.mobile,v.contact_person,e.event_id,e.event_title,ect.boardId,e.description,v.country, e.fromOthers , e.listingType, e.listing_type_id, d.start_date,d.end_date,v.city,co.name as country, ci.city_name , group_concat(distinct c1.name SEPARATOR "|") as CourseCategory, group_concat(distinct REPLACE(c2.name,",","-") SEPARATOR ",") as CourseSubCategory from event e, event_date d, event_venue v, countryTable co, countryCityTable ci, event_venue_mapping evm,eventCategoryTable ect,categoryBoardTable c1 inner join categoryBoardTable c2 on c1.boardId=c2.parentId where ect.eventId=e.event_id and (ect.boardId = c2.boardId) and evm.event_id = e.event_id and e.event_id=d.event_id and co.countryId = v.country  and ci.city_id = v.city and evm.event_id = e.event_id and evm.venue_id=v.venue_id and e.status_id is NULL and c1.parentId=1 and e.creationDate>= ? group by event_id';
        $query = $dbHandle->query($queryCmd, $time);
        $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
        $query = $dbHandle->query($queryCmd);
        $totalRows = 0;
        foreach ($query->result() as $row) {
            $totalRows = $row->totalRows;
        }
        
        $response = array(
                array
                ('totalRowsEvent'=>$totalRows,
                 'totalRowsListing'=>$totalRows1
                ),
                'struct'
                ); 
              
        return $this->xmlrpc->send_response($response);
    }
}


?>
