<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: pankajt $:  Author of last commit
$Date: 2010-09-09 09:54:49 $:  Date of last commit

This class provides the Event Cal Server Web Services.

$Id: event_cal_server.php,v 1.181 2010-09-09 09:54:49 pankajt Exp $:

*/

class Event_cal_server extends MX_Controller {

	/*
	*	index function to recieve the incoming request
	*/

	function index(){

		//load XML RPC Libs
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('eventcalconfig');
		$this->load->library('listing_client');
		$this->load->helper('url');
        	$this->dbLibObj = DbLibCommon::getInstance('Event');

		//Define the web services method
		$config['functions']['getEventCountByMonth'] = array('function' => 'Event_cal_server.getEventCountByMonth');

		$config['functions']['getEventListByDate'] = array('function' => 'Event_cal_server.getEventListByDate');

		$config['functions']['getEventDetail'] = array('function' => 'Event_cal_server.getEventDetail');

//		$config['functions']['SADeliveryOptions'] = array('function' => 'Event_cal_server.SADeliveryOptions');

		$config['functions']['getAllSubscriptionsEmail'] = array('function' => 'Event_cal_server.getAllSubscriptionsEmail');

		$config['functions']['getAllSubscriptionsSMS'] = array('function' => 'Event_cal_server.getAllSubscriptionsSMS');

		$config['functions']['eventMigration'] = array('function' => 'Event_cal_server.eventMigration');

		$config['functions']['subscribeEvents'] = array('function' => 'Event_cal_server.subscribeEvents');

		$config['functions']['getSpotlightEvents'] = array('function' => 'Event_cal_server.getSpotlightEvents');

		$config['functions']['getEventsByCategoryDateLocation'] = array('function' => 'Event_cal_server.getEventsByCategoryDateLocation');

		$config['functions']['viewAllEvents'] = array('function' => 'Event_cal_server.viewAllEvents');

		$config['functions']['addEvent'] = array('function' => 'Event_cal_server.addEvent');

		$config['functions']['addEventForListing'] = array('function' => 'Event_cal_server.addEventForListing');

		$config['functions']['addEventsForListing'] = array('function' => 'Event_cal_server.addEventsForListing');

		$config['functions']['addUpdateEventsForListing'] = array('function' => 'Event_cal_server.addUpdateEventsForListing');

		$config['functions']['getRecentEvent'] = array('function' => 'Event_cal_server.getRecentEvent');

		$config['functions']['getRelatedEvent'] = array('function' => 'Event_cal_server.getRelatedEvent');

		$config['functions']['getEventsForListing'] = array('function' => 'Event_cal_server.getEventsForListing');

		$config['functions']['getEventBoardCount'] = array('function' => 'Event_cal_server.getEventBoardCount');

		$config['functions']['getEventsFeeds'] = array('function' => 'Event_cal_server.getEventsFeeds');

		$config['functions']['getMyEvents'] = array('function' => 'Event_cal_server.getMyEvents');

		$config['functions']['getMySubscribedEvents'] = array('function' => 'Event_cal_server.getMySubscribedEvents');

		$config['functions']['getMyEventCount'] = array('function' => 'Event_cal_server.getMyEventCount');

		$config['functions']['getEventsForHomePageS'] = array('function' => 'Event_cal_server.getEventsForHomePageS');

		$config['functions']['getRecentEventCMS'] = array('function' => 'Event_cal_server.getRecentEventCMS');

		$config['functions']['deleteEvent'] = array('function' => 'Event_cal_server.deleteEvent');

		$config['functions']['deleteEventSubscription'] = array('function' => 'Event_cal_server.deleteEventSubscription');

		$config['functions']['getDetailsForSearch'] = array('function' => 'Event_cal_server.getDetailsForSearch');

		$config['functions']['getEventForIndex'] = array('function' => 'Event_cal_server.getEventForIndex');

		$config['functions']['updateEvent'] = array('function' => 'Event_cal_server.updateEvent');

		$config['functions']['reportAbuse'] = array('function' => 'Event_cal_server.reportAbuse');

		$config['functions']['deleteListingEvent'] = array('function' => 'Event_cal_server.deleteListingEvent');

		$config['functions']['getEventsForExams'] = array('function' => 'Event_cal_server.getEventsForExams');

		$config['functions']['getExamsForEvent'] = array('function' => 'Event_cal_server.getExamsForEvent');
		
		/*
			Upcoming events widget
		*/
		
		$config['functions']['getUpcomingEvents'] = array('function' => 'Event_cal_server.getUpcomingEvents');
		
		//initialize
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}

	
	function getUpcomingEvents($request)
	{
		$parameters = $request->output_parameters();
		$category_id = $parameters[0];
		$subcategory_id = $parameters[1];
		
		$this->load->model('upcoming_events_model','',TRUE);

		$events = $this->upcoming_events_model->getUpcomingEvents($category_id,$subcategory_id);
		
		$response = utility_encodeXmlRpcResponse($events);
		return $this->xmlrpc->send_response($response);
	}

	/*
	 * This method returns the SEO URL for a event. It adds up event tile, location in the URL
	 */
    	function getEventUrl($event_id , $event_title, $fromOthers,$locationsArr = array(), $listingType='', $listingTypeId=0) {
        	$optionalArgs = array();
        	if(count($locationsArr) > 0 && (trim($listingType) == '')){
            		for($i = 0; $i < count($locationsArr); $i++){
                		$optionalArgs['location'][$i] = $locationsArr[$i]['city_name']."-".$locationsArr[$i]['country_name'];
            		}
        	}else{
            		//connect DB
            		$dbHandle = $this->_loadDatabaseHandle();
            		$queryCmd = 'select ci.*, co.*,e.creationDate from event e,event_venue_mapping evm ,event_venue v, countryCityTable ci , countryTable co where evm.event_id = ? and e.event_id = ? and evm.venue_id = v.venue_id and co.countryId = v.country  and ci.city_id = v.city ';
            		error_log_shiksha("performance $queryCmd",'events');
            		$query = $dbHandle->query($queryCmd, array($event_id,$event_id));
            		$i  = 0;
            		foreach ($query->result() as $row){
                		$optionalArgs['location'][$i] = $row->city_name."-".$row->name;
                        $creationDate = $row->creationDate;
                		$i++;
            		}
        	}
			error_log("LSEO" . $fromOthers);
			$optionalArgs['fromOthers'] = $fromOthers;
        	return getSeoUrl($event_id,'event',$event_title,$optionalArgs,'',$creationDate);
    	}

	/*
	*	Report Abuse for a given event id in event table
	*/
	function reportAbuse($request){
		//update event set abuse=event.abuse+1 where event_id=22;
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$eventId=$parameters['1'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');

		$queryCmd = 'update event set abuse=event.abuse+1 where event_id=?';

		error_log_shiksha( 'event reportAbuse query cmd is ' . $queryCmd,"events");

		$query = $dbHandle->query($queryCmd, array($eventId) );
		$affectedRows=$dbHandle->affected_rows();
                $response = array($affectedRows,'int');

		return $this->xmlrpc->send_response($response);

	}

	/*
	*	get my events sorted by date. If country is specified get it for given country
	*/
	function getMyEvents($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$categoryId=$parameters['1'];
		$start=$parameters['2'];
		$count=$parameters['3'];
		$countryId=$parameters['4'];
		$userId=$parameters['5'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		
		if($countryId>1){
			$queryCmd = 'select SQL_CALC_FOUND_ROWS e.event_title, e.event_id, e.fromOthers , e.listingType, e.listing_type_id,  d.start_date,co.name as country, ci.city_name as city from event e, event_date d,event_venue v, event_venue_mapping evm , eventCategoryTable ect, countryTable co, countryCityTable ci  where ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and evm.venue_id = v.venue_id and e.event_id=d.event_id  and v.country='.$countryId.' and status_id is NULL and e.user_id=? and co.countryId = v.country  and ci.city_id = v.city group by e.event_id order by d.start_date DESC LIMIT '.$start.' , '.$count;
		}
		else{
			$queryCmd = 'select SQL_CALC_FOUND_ROWS e.event_title, e.event_id,  e.fromOthers , e.listingType, e.listing_type_id, d.start_date,co.name as country, ci.city_name from event e, event_date d,event_venue v, event_venue_mapping evm , eventCategoryTable ect,countryTable co, countryCityTable ci  where  ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and evm.venue_id = v.venue_id and  e.event_id=d.event_id  and status_id is NULL and e.user_id=? and co.countryId = v.country  and ci.city_id = v.city  group by e.event_id order by d.start_date DESC LIMIT '.$start.' , '.$count;
		}

		error_log_shiksha( 'getMyEvents query cmd is ' . $queryCmd,"events");
        	error_log_shiksha($queryCmd,'events');

		$query = $dbHandle->query($queryCmd, array($userId));
		//will only have one row
		$msgArray = array();
		foreach ($query->result() as $row){
            		$locationsArr[0]['city_name'] = $row->city_name;
            		$locationsArr[0]['country_name'] = $row->country;
			array_push($msgArray,array(
							array(
								'title'=>array($row->event_title,'string'),
								'id'=>array($row->event_id,'string'),
								'start_date'=>array($row->start_date,'string'),
								'url' => array($this->getEventUrl($row->event_id,$row->event_title,$row->fromOthers , $locationsArr ,$row->listingType, $row->listing_type_id),'string')
							),'struct')
			);//close array_push

		}


		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
		$mainArr = array();
		array_push($mainArr,array(
							array(
								'results'=>array($msgArray,'struct'),
								'total'=>array($totalRows,'string'),
							),'struct')
			);//close array_push
		$response = array($mainArr,'struct');
		return $this->xmlrpc->send_response($response);


	}

	function getMySubscribedEvents($request){
                $parameters = $request->output_parameters();
                $appId=$parameters['0'];
                $categoryId=$parameters['1'];
                $start=$parameters['2'];
                $count=$parameters['3'];
                $countryId=$parameters['4'];
                $userId=$parameters['5'];

                //connect DB
                $dbHandle = $this->_loadDatabaseHandle();
                if($countryId>1){
                        $queryCmd = 'select SQL_CALC_FOUND_ROWS subscription_title, subscription_id,  fromOthers , subscription_type, course_id, institute_id, country_id as country,city_id as city from event_subscription  where user_id=? and country_id= ? and status=1 group by subscription_id DESC LIMIT '.$start.' , '.$count;
                }
                else{
                        $queryCmd = 'select SQL_CALC_FOUND_ROWS subscription_title, subscription_id,  fromOthers , subscription_type, course_id, institute_id, country_id as country,city_id as city from event_subscription  where user_id=? and status=1 group by subscription_id DESC LIMIT '.$start.' , '.$count;
                }

                $query = $dbHandle->query($queryCmd, array($userId,$country_id));
                //will only have one row
                $msgArray = array();
                foreach ($query->result() as $row){
                        $locationsArr[0]['city_name'] = $row->city_name;
                        $locationsArr[0]['country_name'] = $row->country;
                        array_push($msgArray,array(
                                                        array(
                                                                'title'=>array($row->subscription_title,'string'),
                                                                'id'=>array($row->subscription_id,'string'),
                                                                'start_date'=>array($row->start_date,'string'),
                                                                'url' => array($this->getEventUrl($row->subscription_id,$row->subscription_title,$row->fromOthers , $locationsArr ,$row->listingType, $row->listing_type_id),'string')
                                                        ),'struct')
                        );//close array_push

                }


                $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
                $query = $dbHandle->query($queryCmd);
                $totalRows = 0;
                foreach ($query->result() as $row) {
                        $totalRows = $row->totalRows;
                }
                $mainArr = array();
                array_push($mainArr,array(
                                                        array(
                                                                'results'=>array($msgArray,'struct'),
                                                                'total'=>array($totalRows,'string'),
                                                        ),'struct')
                        );//close array_push
                $response = array($mainArr,'struct');
                return $this->xmlrpc->send_response($response);
	}

	/*
	*	get my events count, i.e added by a given userId
	*/
	function getMyEventCount($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$categoryId=$this->getBoardChilds($parameters['1']);
		$countryId=$parameters['2'];
		$userId=$parameters['3'];



		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		if($countryId>1){
			$queryCmd = 'select count(*)count from event where (boardId in ('. $categoryId .' )) and status_id is NULL and user_id= ? and countryId= ?';
		}
		else{
			$queryCmd = 'select count(*)count from event where (boardId in ('. $categoryId .' )) and status_id is NULL and user_id=?';
		}

		error_log_shiksha( 'getMyEventCount query cmd is ' . $queryCmd,"events");

		$query = $dbHandle->query($queryCmd, array($userId,$countryId));
		//will only have one row for count
		$response=array();
		foreach ($query->result() as $row){
				$response = array($row,'struct');
		}
		return $this->xmlrpc->send_response($response);


	}

	/*
	* 	Method to return the Event Count for a specific month for each date. This is little complicated method. It first get all
	* the events which lies in a given month. Then add up all the event for each date. XXX this method needs to be relooked
	*/
	function getEventCountByMonth($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$month=$parameters['1'];
		$year=$parameters['2'];
		$categoryId=$parameters['3'];
		$countryId=$parameters['4'];
		$cityId=$parameters['5'];
		$startDate=date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year));
		$noOfDays=date('t',mktime(0,0,0,$month,1,$year));
		$endDate=date('Y-m-d H:i:s', mktime(23, 59, 59, $month, $noOfDays, $year));

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		if($cityId!=''){
			$cityClasue = ' AND evm.event_id = e.event_id AND evm.venue_id = v.venue_id AND v.city = "'.$cityId.'" ';
			$venueTable = ', event_venue_mapping evm , event_venue v ';
		} else {
			$cityClasue = ' AND evm.event_id = e.event_id AND evm.venue_id = v.venue_id ';
			$venueTable = ' , event_venue_mapping evm , event_venue v ';
		}
		$cityClause = '';
		if($countryId>1){
			$queryCmd = 'select d.event_id,d.start_date,datediff(d.end_date,d.start_date)days from event_date d, event e,eventCategoryTable ect'.$venueTable .' where ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and status_id is NULL and d.event_id=e.event_id and d.end_date>=? and d.start_date<=? and v.country= ? and datediff(d.end_date,d.start_date)>=0'.$cityClasue. ' group by e.event_id ';
		}
		else{
			$queryCmd = 'select d.event_id,d.start_date,datediff(d.end_date,d.start_date)days from event_date d, event e,eventCategoryTable ect'.$venueTable .' where  ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and d.event_id=e.event_id and status_id is NULL and d.end_date>=? and d.start_date<=? and datediff(d.end_date,d.start_date)>=0'.$cityClasue. ' group by e.event_id ';
		}
		error_log_shiksha( 'getEventCountByMonth query cmd is ' . $queryCmd,"events");
		error_log_shiksha("NEWEVENT getEventCountByMonth:: $queryCmd",'events');
		$query = $dbHandle->query($queryCmd,array($startDate,$endDate,$countryId));
		$dateHash=array();
		foreach ($query->result() as $row){
			if($row->days==0){
				$start_date=date('Y-m-d', mktime(0, 0, 0, date('m',strtotime($row->start_date)), date('d',strtotime($row->start_date)), date('Y',strtotime($row->start_date))));
				if (array_key_exists($start_date,$dateHash)) {
						$dateHash[$start_date]+=1;
				}else{
						$dateHash[$start_date]=1;
				}

			}
			else{
				$i=0;
				$start_date=date('Y-m-d', mktime(0, 0, 0, date('m',strtotime($row->start_date)), date('d',strtotime($row->start_date)), date('Y',strtotime($row->start_date))));
				while($i<=$row->days){
					if (array_key_exists($start_date,$dateHash)) {
						$dateHash[$start_date]+=1;
					}else{
						$dateHash[$start_date]=1;
					}
					$i++;
					$start_date=date('Y-m-d', mktime(0, 0, 0, date('m',strtotime($start_date)), date('d',strtotime($start_date))+1, date('Y',strtotime($start_date))));

				}
			}
		}
		//create response
		$msgArray=array();
		foreach($dateHash as $key=>$value){
			$monthPos=strpos($key,'-',0);
			$monthValue=substr($key,($monthPos+1),2);
			$yearValue=substr($key,0,4);
			if((int)$monthValue!=(int)$month){
				continue;
			}
			if((int)$yearValue!=(int)$year){
				continue;
			}
			array_push($msgArray,array(
							array(
								'dateStr'=>array($key,'string'),
								'numEvents'=>array($value,'string')
							),'struct')
			);//close array_push
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);

	}


	/*
	* This function returns the event list for a particular date
	*/
	function getEventListByDate($request){

		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$day=$parameters['1'];
		if($day<10){
			$day='0'.$day;
		}
		$month=$parameters['2'];
		if($month<10){
			$month='0'.$month;
		}
		$year=$parameters['3'];
		$categoryId=$parameters['4'];
		$countryId=$parameters['5'];
		$cityId=$parameters['6'];
		$start=$parameters['7'];
		$count=$parameters['8'];
		$date = $year.'-'.$month.'-'.$day;

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		if($cityId!=''){
			$cityClasue = ' AND v.city = "'.$cityId.'" ';
		} else {
			$cityClause = '';
		}
		if($countryId>1){
			$queryCmd = 'select SQL_CALC_FOUND_ROWS e.event_id,event_title, e.fromOthers , e.listingType, e.listing_type_id, d.start_date,d.end_date,v.Address_Line1,co.name as country, ci.city_name as city from event e, event_date d,event_venue v, countryTable co, countryCityTable ci , event_venue_mapping evm,eventCategoryTable ect  where ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and e.status_id is NULL and e.event_id=d.event_id and date(d.start_date)<= ? and date(d.end_date)>= ? and v.country= ? and evm.event_id = e.event_id and evm.venue_id=v.venue_id and co.countryId = v.country  and ci.city_id = v.city '. $cityClasue .' group by e.event_id order by e.fromOthers asc , d.start_date asc LIMIT '. $start.' , '.$count;
		}else{
			$queryCmd = 'select SQL_CALC_FOUND_ROWS e.event_id,event_title, e.fromOthers , e.listingType, e.listing_type_id, d.start_date,d.end_date,v.Address_Line1,co.name as country, ci.city_name as city from event e, event_date d,event_venue v, countryTable co, countryCityTable ci , event_venue_mapping evm,eventCategoryTable ect where ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and e.status_id is NULL and e.event_id=d.event_id and date(d.start_date)<=? and date(d.end_date)>=?  and evm.event_id = e.event_id  and evm.venue_id=v.venue_id and co.countryId = v.country  and ci.city_id = v.city  group by e.event_id order by  e.fromOthers asc ,d.start_date asc LIMIT '. $start.' , '.$count;
		}
		//error_log_shiksha("LIST BT DATE EVENTS : $queryCmd",'events');
		error_log_shiksha( 'getEventListByDate query cmd is ' . $queryCmd,"events");
        error_log_shiksha('NEWEVENT getEventListByDate query cmd is ' . $queryCmd ,'events');

		$query = $dbHandle->query($queryCmd, array($date,$date,$countryId));
		$msgArray = array();
		foreach ($query->result() as $row){
			$datePattern = '/(\d{1,2})(\w{2}) (\w+), (\d+), (\d{2}):(\d{2})(\w{2})/i';
			$dateReplacePattern = '${1}<sup>${2}</sup> ${3}, $4, ${5}:${6}${7}';
			$startDate = preg_replace($datePattern, $dateReplacePattern, date('jS M, y, h:ia',strtotime($row->start_date)));
			$endDate = preg_replace($datePattern, $dateReplacePattern, date('jS M, y, h:ia',strtotime($row->end_date)));
            $locationsArr[0]['city_name'] = $row->city_name;
            $locationsArr[0]['country_name'] = $row->country;
			array_push($msgArray,array(
							array(
								'title'=>array($row->event_title,'string'),
								'id'=>array($row->event_id,'string'),
								'start_date'=>array($startDate,'string'),
								'end_date'=>array($endDate,'string'),
								'Address_Line1'=>array($row->Address_Line1,'string'),
								'city'=>array($row->city,'string'),
								'url' => array($this->getEventUrl($row->event_id,$row->event_title,$row->fromOthers , $locationsArr ,$row->listingType, $row->listing_type_id),'string')
							),'struct')
				   );//close array_push
		}
		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
		$mainArr = array();
		array_push($mainArr,array(
							array(
								'results'=>array($msgArray,'struct'),
								'total'=>array($totalRows,'string'),
							),'struct')
			);//close array_push
		$response = array($mainArr,'struct');
		return $this->xmlrpc->send_response($response);

	}

	/*
	* This function gives feed to alert
	*/
	function getEventsFeeds($request){

		$parameters = $request->output_parameters();
		$startDate=$parameters['0'];
		$endDate=$parameters['1'];
		$appId=1;

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$queryCmd = 'select e.event_id,e.event_title,ect.boardId,e.description,v.country, e.fromOthers , e.listingType, e.listing_type_id, d.start_date,d.end_date,v.city,co.name as country, ci.city_name  from event e, event_date d, event_venue v, countryTable co, countryCityTable ci, event_venue_mapping evm,eventCategoryTable ect where ect.eventId=e.event_id and evm.event_id = e.event_id and e.event_id=d.event_id and co.countryId = v.country  and ci.city_id = v.city and evm.event_id = e.event_id and evm.venue_id=v.venue_id and e.status_id is NULL and e.creationDate>=? and e.creationDate<=?';
		error_log_shiksha('NEWEVENT getEventsFeeds query cmd is ' . $queryCmd,'events');

		$query = $dbHandle->query($queryCmd, array($startDate,$endDate));
		$msgArray = array();
		foreach ($query->result_array() as $row){
            $locationsArr[0]['city_name'] = $row->city_name;
            $locationsArr[0]['country_name'] = $row->country;
			$insertRow['event_id']=$row['event_id'];
			$insertRow['event_title']=$row['event_title'];
			$insertRow['boardId']=$row['boardId'];
			$insertRow['description']=$row['description'];
			$insertRow['countryId']=$row['countryId'];
			$insertRow['start_date']=$row['start_date'];
			$insertRow['end_date']=$row['end_date'];
			$insertRow['city']=$row['city_name'];
			$insertRow['url'] = $this->getEventUrl($row->event_id,$row->event_title,$row->fromOthers , $locationsArr ,$row->listingType, $row->listing_type_id);
			array_push($msgArray,array($insertRow,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);

	}


	/*
	* This function returns the event detail for a event ID
	*/
	function getEventDetail($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$eventId=$parameters['1'];
		$userId=$parameters['2'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		if($userId > 0)
		  $getReportedAbuse = ", ifnull((select pointsAdded from tReportAbuseLog ral where ral.entityId=e.event_id and ral.userId=".$userId."),0) reportedAbuse ";

		$queryCmd = 'select co.name as countryName, ci.city_name as cityName,(select group_concat(boardId) from eventCategoryTable where eventId = ?) categoryCsv,(select group_concat(distinct ect.boardId) from eventCategoryTable ect,categoryBoardTable cbt where ect.boardId=cbt.parentId and ect.eventId = ?) mainCategoryCsv,v.*,e.abuse,e.event_title,e.assoc_country,e.listing_type_id,e.listingType,ect.boardId subCategoryId,c.name subCategoryName,c.parentId categoryId,e.description,e.user_id,e.status_id,e.event_url,e.privacy, e.fromOthers,e.threadId, d.*, (select name from categoryBoardTable where boardId=c.parentId) categoryName '.$getReportedAbuse.' from categoryBoardTable c,event e, event_venue v, event_venue_mapping evm , eventCategoryTable ect, event_date d, countryTable co, countryCityTable ci where ect.eventId=e.event_id and evm.event_id = e.event_id and evm.venue_id = v.venue_id and evm.event_id = e.event_id  and (e.status_id is NULL or e.status_id = 2) and e.event_id=d.event_id and c.boardId=ect.boardId and co.countryId = v.country  and ci.city_id = v.city and e.event_id= ? limit 1 ';

		error_log_shiksha('debug getEventDetail query cmd is ' . $queryCmd,'events');
		$query = $dbHandle->query($queryCmd ,array($eventId,$eventId,$eventId));
		//will only have one row
		$response=array();
		foreach ($query->result_array() as $row){
			$response = array($row,'struct');
		}
		return $this->xmlrpc->send_response($response);
	}

	/*
	 * Add event, here the client controller calls search method to get it indexed
	 */
	function addEvent($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$reqArray=$parameters['1'];
		$fromOthers = $parameters['2'];
		$listingTypeId = $parameters['3'];
		$listingType = $parameters['4'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');

		$data =array(
			'venue_name'=>$reqArray['venue_name'],
			'Address_Line1'=>$reqArray['Address_Line1'],
			'city'=>$reqArray['city'],
			'state'=>$reqArray['state'],
			'zip'=>$reqArray['zip'],
			'phone'=> $reqArray['phone'] ,
			'fax'=>$reqArray['fax'],
			'email'=>$reqArray['email'],
			'mobile'=>$reqArray['mobile'],
			'url'=>$reqArray['url'],
			'contact_person'=>$reqArray['contact_person'],
			'country'=>$reqArray['country']
		);

		// First add event venue
		$queryCmd = $dbHandle->insert_string('event_venue',$data);
		$query = $dbHandle->query($queryCmd);
		$venueId=$dbHandle->insert_id();



		$data =array(
			'event_title'=>$reqArray['event_title'],
			'venue_id'=>$venueId,
			'assoc_country'=>$reqArray['countryAssoc'],
			'contact_person'=>$reqArray['contact_person'],
			'description'=>$reqArray['description'],
			'user_id'=>$reqArray['user_id'],
			'event_url'=>$reqArray['url'],
			'Tag'=>$reqArray['Tag'],
			'threadId'=>$reqArray['threadId'],
			'fromOthers'=>$fromOthers,
			'listing_type_id' => $listingTypeId,
			'listingType' => $listingType
		);

		// add event details
		$queryCmd = $dbHandle->insert_string('event',$data);
		error_log_shiksha( 'addEvent insert event query cmd is ' . $queryCmd,'events');
		$query = $dbHandle->query($queryCmd);
		$eventId=$dbHandle->insert_id();


		//add category
		$categoryArray = array();
		$tempArray = explode(",", $reqArray['board_id']);
		foreach($tempArray as $temp)
		{
			if($temp != '')
				array_push($categoryArray,$temp);
		}
		array_push($categoryArray,"1");
		$queryCmd = 'insert eventCategoryTable values ';
		$commaCount=0;
		foreach ($categoryArray as $boardId) {
			if($commaCount>0){
				$queryCmd.=",";
			}
			$queryCmd.="($eventId,$boardId)";

			if($boardId>1){
				$queryCmd.=",($eventId,(select parentId from categoryBoardTable where boardId= ".$dbHandle->escape($boardId)."))";
			}
			$commaCount++;
		}
		$queryCmd.= " on duplicate key update eventCategoryTable.eventId = ".$dbHandle->escape($eventId);
		error_log_shiksha( 'addEvent category query cmd is ' . $queryCmd,'events');
		$query = $dbHandle->query($queryCmd);


		//add event date


		$data = array(
			'event_id'=>$eventId,
			'start_date'=>$reqArray['start_date'],
			'end_date'=>$reqArray['end_date']
		);

		$queryCmd = $dbHandle->insert_string('event_date',$data);
		error_log_shiksha( 'addEvent insert event date query cmd is ' . $queryCmd,'events');
		$query = $dbHandle->query($queryCmd);

        	$data = array(
			'event_id'=>$eventId,
			'venue_id'=>$venueId
		);
		//update event venue mapping
		$queryCmd = $dbHandle->insert_string('event_venue_mapping',$data);
		error_log_shiksha( 'addEvent insert event venue mapping query cmd is ' . $queryCmd,'events');
		$query = $dbHandle->query($queryCmd);

		error_log_shiksha( 'addEvent insert event date ID is ' . $eventId,'events');
		$response = array(
					array(
						'eventId'  => array($eventId,'string')),
                         		'struct');

        	$exams = $reqArray['examSelected'];
        	$this->makeEventExamsMapping($eventId, $exams);
		return $this->xmlrpc->send_response($response);
	}

	/*
	 * Update/Edit an event
	 */
	function updateEvent($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$reqArray=$parameters['1'];
		$fromOthers = $parameters['2'];
		$listingTypeId = $parameters['3'];
		$listingType = $parameters['4'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');

		$data =array(
			'venue_name'=>$reqArray['venue_name'],
			'Address_Line1'=>$reqArray['Address_Line1'],
			'city'=>$reqArray['city'],
			'state'=>$reqArray['state'],
			'zip'=>$reqArray['zip'],
			'phone'=> $reqArray['phone'] ,
			'fax'=>$reqArray['fax'],
			'email'=>$reqArray['email'],
			'mobile'=>$reqArray['mobile'],
			'url'=>$reqArray['url'],
			'contact_person'=>$reqArray['contact_person'],
			'country'=>$reqArray['country']
		);

		$dbHandle->where('venue_id', $reqArray['venue_id']);
		$dbHandle->update('event_venue',$data);

		$data =array(
			'event_title'=>$reqArray['event_title'],
			'venue_id'=>$reqArray['venue_id'],
			'assoc_country'=>$reqArray['countryAssoc'],
			'contact_person'=>$reqArray['contact_person'],
			'description'=>$reqArray['description'],
			'user_id'=>$reqArray['user_id'],
			'event_url'=>$reqArray['url'],
			'Tag'=>$reqArray['Tag'],
			/*'threadId'=>$reqArray['threadId'],*/
			'fromOthers'=>$fromOthers,
			'listing_type_id' => $listingTypeId,
			'listingType' => $listingType
		);

		$dbHandle->where('event_id', $reqArray['event_id']);
		$dbHandle->update('event',$data);
		$data = array(
			'start_date'=>$reqArray['start_date'],
			'end_date'=>$reqArray['end_date']
		);

		$dbHandle->where('event_id', $reqArray['event_id']);
		$dbHandle->update('event_date',$data);


		//update category

		//delete category first
		$eventId=$reqArray['event_id'];
		$queryCmd = "delete from eventCategoryTable where eventId=?";
		error_log_shiksha( 'update category query cmd is ' . $queryCmd,'events');
		$query = $dbHandle->query($queryCmd,array($eventId));


		//add new category
		$categoryArray = array();
		$tempArray = explode(",", $reqArray['board_id']);
		foreach($tempArray as $temp)
		{
			if($temp != '')
				array_push($categoryArray,$temp);
		}
		array_push($categoryArray,"1");
		$queryCmd = 'insert eventCategoryTable values ';
		$commaCount=0;
		foreach ($categoryArray as $boardId) {
			if($commaCount>0){
				$queryCmd.=",";
			}
			$queryCmd.="($eventId,$boardId)";
			if($boardId>1){
				$queryCmd.=",($eventId,(select parentId from categoryBoardTable where boardId= ".$dbHandle->escape($boardId)."))";
			}
			$commaCount++;
		}
		$queryCmd.= " on duplicate key update eventCategoryTable.eventId = ".$dbHandle->escape($eventId);
		error_log_shiksha( 'update category query cmd is ' . $queryCmd,'events');
		$query = $dbHandle->query($queryCmd);


		//


		$response = array(
					array(
						'eventId'  => array($reqArray['event_id'],'string')),
                         		'struct');

        	$exams = $reqArray['examSelected'];
        	$this->makeEventExamsMapping($reqArray['event_id'], $exams);
		return $this->xmlrpc->send_response($response);
	}


	/*
	* This function returns the recent event. This fills up the recent event tab. Events are shown in order of creation date desc
	*/
	function getRecentEvent($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$start=$parameters['1'];
		$count=$parameters['2'];
		$categoryId=$parameters['3'];
		$countryId=$parameters['4'];
		$cityId=$parameters['5'];


		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		if($cityId!=''){
			$cityClasue = ' AND v.city = "'.$cityId.'" ';
		} else {
			$cityClause = '';
		}
		if($countryId>1){
			$queryCmd = 'select SQL_CALC_FOUND_ROWS e.event_title, e.event_id,  e.fromOthers , e.listingType, e.listing_type_id,  e.creationDate, d.start_date,d.end_date,v.Address_Line1,co.name as country, ci.city_name as city from event e, event_date d,event_venue v, countryTable co, countryCityTable ci, event_venue_mapping evm,eventCategoryTable ect where ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and e.event_id=d.event_id  and v.country= ? and evm.event_id = e.event_id and evm.venue_id=v.venue_id and co.countryId = v.country  and e.status_id is NULL and ci.city_id = v.city '. $cityClasue  .' group by e.event_id order by e.creationDate DESC LIMIT '. $start.' , '.$count;
		}
		else{
			$queryCmd = 'select SQL_CALC_FOUND_ROWS e.event_title, e.event_id, e.fromOthers , e.listingType, e.listing_type_id, e.creationDate, d.start_date,d.end_date,v.Address_Line1,co.name as country, ci.city_name as city from event e, event_date d,event_venue v, countryTable co, countryCityTable ci ,event_venue_mapping evm,eventCategoryTable ect where ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and e.event_id=d.event_id and evm.event_id  = e.event_id and evm.venue_id=v.venue_id and co.countryId = v.country  and ci.city_id = v.city and e.status_id is NULL group by e.event_id order by e.creationDate DESC LIMIT '. $start.' , '.$count;
        }
		error_log_shiksha("NEWEVENT RECENT EVENTS : $queryCmd",'events');
		error_log_shiksha( 'getRecentEvent query cmd is ' . $queryCmd,'events');

		$query = $dbHandle->query($queryCmd,array($countryId));
		//will only have one row
		$msgArray = array();
		foreach ($query->result() as $row){
			$datePattern = '/(\d{1,2})(\w{2}) (\w+), (\d+), (\d{2}):(\d{2})(\w{2})/i';
			$dateReplacePattern = '${1}<sup>${2}</sup> ${3}, $4, ${5}:${6}${7}';
			$startDate = preg_replace($datePattern, $dateReplacePattern, date('jS M, y, h:ia',strtotime($row->start_date)));
			$endDate = preg_replace($datePattern, $dateReplacePattern, date('jS M, y, h:ia',strtotime($row->end_date)));
            $locationsArr[0]['city_name'] = $row->city;
            $locationsArr[0]['country_name'] = $row->country;
			array_push($msgArray,array(
							array(
								'title'=>array($row->event_title,'string'),
								'id'=>array($row->event_id,'string'),
								'start_date'=>array($startDate,'string'),
								'end_date'=>array($endDate,'string'),
								'Address_Line1'=>array($row->Address_Line1,'string'),
								'city'=>array($row->city,'string'),
								'url' => array($this->getEventUrl($row->event_id,$row->event_title,$row->fromOthers , $locationsArr ,$row->listingType, $row->listing_type_id),'string')
							),'struct')
			);//close array_push

		}
		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
		$mainArr = array();
		array_push($mainArr,array(
							array(
								'results'=>array($msgArray,'struct'),
								'total'=>array($totalRows,'string'),
							),'struct')
			);//close array_push
		$response = array($mainArr,'struct');
		return $this->xmlrpc->send_response($response);

	}



	 /*
        * This function returns the related event for given category id
        */
        function getEventsForListing($request){
                $parameters = $request->output_parameters();
                $appId=$parameters['0'];
                $start=$parameters['1'];
                $count=$parameters['2'];
                $listingType=$parameters['3'];
                $listingTypeId=$parameters['4'];
                //connect DB
                $dbHandle = $this->_loadDatabaseHandle();
		if($listingType=='course'){
                        $queryCmd = "select (select count(*) as count from event e, event_date d where e.event_id=d.event_id and e.listingType='course' and e.listing_type_id= ? and e.status_id is Null and datediff(current_date,d.end_date)<=0) as count,? as start,e.event_title,e.description, e.event_id, e.fromOthers , e.listingType, e.listing_type_id,  d.start_date, d.end_date from event e, event_date d where e.event_id=d.event_id and e.listingType='course' and e.listing_type_id= ? and e.status_id is Null and datediff(current_date,d.end_date)<=0 order by d.end_date limit $start,$count";
		}else{
			$queryCmd = "select (select count(*) as count from event e, event_date d where e.event_id=d.event_id and e.listingType='course' and e.status_id is Null and e.listing_type_id in (select course_id from institute_courses_mapping_table where institute_id= ?) and datediff(current_date,d.end_date)<=0) as count,? as start,e.event_title,e.description, e.event_id, e.fromOthers , e.listingType, e.listing_type_id,  d.start_date, d.end_date from event e, event_date d where e.event_id=d.event_id and e.listingType='course' and e.status_id is Null and e.listing_type_id in (select course_id from institute_courses_mapping_table where institute_id= ?) and datediff(current_date,d.end_date)<=0 order by d.end_date limit $start,$count";
		}

                error_log_shiksha(" getEventsForListing ".$queryCmd,'events');

                $query = $dbHandle->query($queryCmd,array($listingTypeId,$start,$listingTypeId));
                //will only have one row
                $msgArray = array();
                foreach ($query->result() as $row){
                        array_push($msgArray,array(
                                                        array(
								'count'=>array($row->count,'string'),
                                                           	'start'=>array($row->start,'string'),
							        'title'=>array($row->event_title,'string'),
                                                                'id'=>array($row->event_id,'string'),
								'fromOthers'=>array($row->fromOthers,'int'),
                                                                'start_date'=>array($row->start_date,'string'),
                                                                'end_date'=>array($row->end_date,'string'),
								'eventTitle'=>array(substr($row->event_title,0,40)."....",'string'),
								'month'=>array(date("M",strtotime($row->end_date)),'string'),
								'date'=>array(date("j",strtotime($row->end_date)),'string')
                                                        ),'struct')
                        );//close array_push

                }
                $response = array($msgArray,'struct');
                return $this->xmlrpc->send_response($response);

        }

	/*
	* This function returns the related event for given category ids
	*/
	function getRelatedEvent($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$start=$parameters['1'];
		$count=$parameters['2'];
		$categoryId=$parameters['3'];
		$countryId=$parameters['4'];
		$eventId=$parameters['5'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		if($countryId>1){
			$queryCmd = 'select distinct e.event_title,(select name from categoryBoardTable where boardId in (select distinct cbt.parentId from categoryBoardTable cbt,eventCategoryTable ect,event e where e.event_id=ect.eventId and cbt.boardId=ect.boardId and e.event_id= ? and cbt.parentId not in (0,1)) order by priority limit 1) categories,e.description, e.event_id, e.fromOthers , e.listingType, e.listing_type_id,  d.start_date, d.end_date from event e, event_date d,eventCategoryTable ect,event_venue v where ect.eventId=e.event_id and ect.boardId in (select distinct boardId from categoryBoardTable where parentId = (select distinct cbt.parentId from categoryBoardTable cbt,eventCategoryTable ect,event e where e.event_id=ect.eventId and cbt.boardId=ect.boardId and e.event_id= ? and cbt.parentId not in (0,1) order by cbt.priority limit 1) order by priority) and e.venue_id=v.venue_id and ect.eventId = e.event_id and e.event_id=d.event_id and e.event_id!= ? and v.country= ? and e.status_id is NULL and datediff(current_date,d.end_date)<=0 order by d.end_date LIMIT '.$start.' , '.$count;
		}
		else{
			$queryCmd = 'select distinct e.event_title,(select name from categoryBoardTable where boardId in (select distinct cbt.parentId from categoryBoardTable cbt,eventCategoryTable ect,event e where e.event_id=ect.eventId and cbt.boardId=ect.boardId and e.event_id= ? and cbt.parentId not in (0,1)) order by priority limit 1) categories,e.description, e.event_id, e.fromOthers , e.listingType, e.listing_type_id,  d.start_date, d.end_date from event e, event_date d,eventCategoryTable ect where ect.eventId=e.event_id and ect.boardId in (select distinct boardId from categoryBoardTable where parentId = (select distinct cbt.parentId from categoryBoardTable cbt,eventCategoryTable ect,event e where e.event_id=ect.eventId and cbt.boardId=ect.boardId and e.event_id= ? and cbt.parentId not in (0,1) order by cbt.priority limit 1) order by priority) and ect.eventId = e.event_id and e.event_id=d.event_id   and e.event_id!= ? and e.status_id is NULL and datediff(current_date,d.end_date)<=0 order by d.end_date LIMIT '.$start.' , '.$count;
		}
		error_log_shiksha( 'getRelatedEvent query cmd is ' . $queryCmd,'events');
        	error_log_shiksha(" NEWEVENT getRelatedEvent ".$queryCmd,'events');

		$query = $dbHandle->query($queryCmd ,array($eventId,$eventId,$eventId,$countryId));
		//will only have one row
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
							array(
								'title'=>array($row->event_title,'string'),
								'categories'=>array($row->categories,'string'),
								'description'=>array($row->description,'string'),
								'id'=>array($row->event_id,'string'),
								'fromOthers'=>array($row->fromOthers,'int'),
								'start_date'=>array($row->start_date,'string'),
								'end_date'=>array($row->end_date,'string'),
								'url' => array($this->getEventUrl($row->event_id,$row->event_title,$row->fromOthers , array(),$row->listingType, $row->listing_type_id),'string')
							),'struct')
			);//close array_push

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);

	}

	/*
	*   This function returns the event count for all boards for a given country
	*/
	function getEventBoardCount($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$countryId=$parameters['1'];
		$categoryId=$parameters['2'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		if($countryId>1){
			$queryCmd = "select count(distinct(event.event_id))count from event, eventCategoryTable, event_venue_mapping, event_venue where event_venue_mapping.event_id=event.event_id and  event.event_id = eventCategoryTable.eventId and eventCategoryTable.boardId in ($categoryId) and event_venue.venue_id = event_venue_mapping.venue_id and event_venue.country in ($countryId) and status_id is null";
		}
		else{
			$queryCmd = "select count(distinct(event.event_id))count from event, eventCategoryTable where event.event_id = eventCategoryTable.eventId and eventCategoryTable.boardId in ($categoryId)  and status_id is null";
        }
		error_log_shiksha( 'getEventBoardCount query cmd is ' . $queryCmd,'events');
       		 error_log_shiksha("NEWEVENT getEventBoardCount ".$queryCmd,'events');

		$query = $dbHandle->query($queryCmd);
		foreach ($query->result() as $row){
			$msgArray['totalCount']=$row->count;
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);

	}

	/*
	*   This function will delete the events
	*/
	function deleteEvent($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$eventId=$parameters['1'];
		$status=$parameters['2'];
		$penalty=$parameters['3'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');
		//get the venue id
		$queryCmd = 'update event set status_id= ? where event_id = ?';
		error_log_shiksha('deleting query '.$queryCmd,'events');
		$query = $dbHandle->query($queryCmd, array($status,$eventId));
		$deletedEventId=$dbHandle->affected_rows();
		if($penalty==1){
		  $queryCmd = 'select user_id from event where event_id = ?';
		  $query = $dbHandle->query($queryCmd,array($eventId));
		  $this->load->model('UserPointSystemModel');
		  foreach ($query->result_array() as $row){
			$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$row['user_id'],'deleteEvent');
		  }

		}
		$response = array(
                                  array(
                                    	'Result'=>array('Deleted'. $deletedEventId)),
                                  'struct');
		return $this->xmlrpc->send_response($response);
	}

	function deleteEventSubscription($request){
                $parameters = $request->output_parameters();
                $appId=$parameters['0'];
                $subscriptionId=$parameters['1'];
                $status=0;

                //connect DB
                $dbHandle = $this->_loadDatabaseHandle('write');
                //get the venue id
                $queryCmd = 'update event_subscription set status= ? where subscription_id = ? ';
                $query = $dbHandle->query($queryCmd, array($status,$subscriptionId));
                $deletedEventId=$dbHandle->affected_rows();
                $response = array(
                                  array(
                                        'Result'=>array('Deleted'. $deletedEventId)),
                                  'struct');
                return $this->xmlrpc->send_response($response);
        }


	/*
	*   This function will delete the events added by listing
	*/
	function deleteListingEvent($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$listingId=$parameters['1'];
		$listingType=$parameters['2'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');

		//Delete from search first
		$queryCmd = 'select event_id from event where status_id=0 and listing_type_id= ? and listingType=? ';
		error_log_shiksha('deleteListingEvent query cmd '. $queryCmd,'events');
		$listingClientObj =  new Listing_client();
		$query = $dbHandle->query($queryCmd ,array($listingId, $listingType));
		foreach ($query->result() as $row){
			$searchResponse = $listingClientObj->deleteListing($appId,'Event',$row->event_id);
			error_log_shiksha('deleteListingEvent query cmd search response '. $searchResponse,'events');
		}

		//soft delete the event
		$queryCmd = 'update event set status_id=1 where listing_type_id = ? and listingType=? ';
		error_log_shiksha('deleteListingEvent query cmd '. $queryCmd,'events');
		$query = $dbHandle->query($queryCmd, array($listingId,$listingType));
		$deletedEventId=$dbHandle->affected_rows();
		$response = array(
                                  array(
                                    	'Result'=>array('Deleted'. $deletedEventId)),
                                  'struct');
		return $this->xmlrpc->send_response($response);
	}

	/*
	* XXX common lib method, not used now
	*/
	function getBoardChilds($categoryId){
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$categoryIdArray = array();
		$categoryIdString='';

		$queryCmd = ' SELECT t1.boardId AS lev1, t2.boardId as lev2, t3.boardId as lev3, t4.boardId as lev4 FROM categoryBoardTable AS t1 '.
				 'LEFT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId '.
				 'LEFT JOIN categoryBoardTable AS t3 ON t3.parentId = t2.boardId '.
				 'LEFT JOIN categoryBoardTable AS t4 ON t4.parentId = t3.boardId WHERE t1.boardId = ?';

		error_log_shiksha( 'get board child query is ' . $queryCmd,'events');
		$query = $dbHandle->query($queryCmd, array($categoryId));
		foreach ($query->result() as $row){

			if(!array_key_exists($row->lev1,$categoryIdArray) && !empty($row->lev1)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev1]=$row->lev1;
				$categoryIdString .= $row->lev1;

			}
			if(!array_key_exists($row->lev2,$categoryIdArray) && !empty($row->lev2)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev2]=$row->lev2;
				$categoryIdString .= $row->lev2;

			}
			if(!array_key_exists($row->lev3,$categoryIdArray) && !empty($row->lev3)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev3]=$row->lev3;
				$categoryIdString .= $row->lev3;

			}
			if(!array_key_exists($row->lev4,$categoryIdArray) && !empty($row->lev4)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev4]=$row->lev4;
				$categoryIdString .= $row->lev4;

			}
		}
		if(strlen($categoryIdString)==0){
			$categoryIdString .= $categoryId;
		}
		error_log_shiksha( 'getboardchild query o/p is ' . $categoryIdString,'events');
		return $categoryIdString;
	}

	/*
	* Get Events for home pages. The algo is first check from CMS, then for a given sub category and if count is still less go to
	* parent category
	*/

    	function getEventsForHomePageS($request){
        	$parameters = $request->output_parameters();
        	$appId=$parameters['0'];
        	//$categoryId=$parameters['2'];
		$categoryId=$this->getBoardChilds($parameters['2']);
        	$countryId=$parameters['1'];
        	$start=$parameters['3'];
        	$count=$parameters['4'];
        	$keyValue=$parameters['5'];
		$fromOthers=$parameters['6'];
		$parentCategory=$parameters['7'];
		$cityId=$parameters['8'];

        	//connect DB
        	$dbHandle = $this->_loadDatabaseHandle();

		//init the response array
		$msgArray = array();

		//First check from CMS

            $queryCmd = 'select e.event_title, e.event_id, e.fromOthers , e.listingType, e.listing_type_id, d.start_date, v.venue_name, v.Address_Line1, v.state, co.name as country, ci.city_name as city  from event e, event_date d,  event_venue v, PageEventsDb pdb, countryTable co, countryCityTable ci , event_venue_mapping evm,eventCategoryTable ect where  ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and  evm.event_id = e.event_id and evm.venue_id=v.venue_id and e.status_id is NULL and e.event_id=d.event_id and pdb.KeyId= ?';

            if($fromOthers != -1)
            $queryCmd .= ' and e.fromOthers= ?';
            $queryCmd .= ' and pdb.EventId = e.event_id and CURDATE() >= pdb.StartDate and CURDATE() <= pdb.EndDate ';
            if($countryId != 1){
                $queryCmd .= ' and v.country in ('.$countryId.')';
            }
            if($cityId != 1 && $cityId !=""){
                $queryCmd .= ' and v.city in ('.$cityId.')';
            }

            $queryCmd .=' and co.countryId = v.country  and ci.city_id = v.city group by e.event_id order by d.start_date DESC LIMIT '.$start. ' , '.$count;
            $cmsEventIDs = '';
        	$query = $dbHandle->query($queryCmd, array($keyValue,$fromOthers));

		//what are CMS Rows
        	$cmsRows=$query->num_rows();

		//if we have some cms rows insert into response
		if($cmsRows>=1){

			foreach ($query->result() as $row){
				if(strlen($cmsEventIDs)>0){
					$cmsEventIDs .= ' , ';
				}
				$cmsEventIDs .= $row->event_id;
				$locationsArr[0]['city_name'] = $row->city;
				$locationsArr[0]['country_name'] = $row->country;
				array_push($msgArray,array(
							array(
								'title'=>array($row->event_title,'string'),
								'id'=>array($row->event_id,'string'),
								'start_date'=>array($row->start_date,'string'),
								'venue_name'=>array($row->venue_name,'string'),
								'Address_Line1'=>array($row->Address_Line1,'string'),
								'city'=>array($row->city,'string'),
								'state'=>array($row->state,'string'),
								'country'=>array($row->country,'string'),
								'isSponsored'=>array('true','string'),
								'url' => array($this->getEventUrl($row->event_id,$row->event_title,$row->fromOthers , $locationsArr ,$row->listingType, $row->listing_type_id),'string')
							     ),'struct')
					  );//close array_push

			}
		}




		//Step 2 get it from db if we are short of count in CMS
	        if($cmsRows < $count) {

			if(strlen($cmsEventIDs)==0){
				$cmsEventIDs = '\'\'';
			}
        		if($countryId != 1 && $countryId != ""){
                    $queryCmd = 'select SQL_CALC_FOUND_ROWS e.event_title, e.event_id, e.fromOthers , e.listingType, e.listing_type_id,  d.start_date, FLOOR(LOG(ABS((unix_timestamp(d.start_date)-unix_timestamp(now()))/86400))) startDateDiff, v.venue_name, v.Address_Line1, v.state, co.name as country, ci.city_name as city  from event e, event_date d,   event_venue v, countryTable co, countryCityTable ci, event_venue_mapping evm,eventCategoryTable ect, virtualCityMapping vcm where  ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and e.status_id is NULL and evm.event_id = e.event_id and evm.venue_id=v.venue_id  and e.event_id=d.event_id and e.event_id not in ('. $cmsEventIDs .') and vcm.city_id = v.city ';

            if($fromOthers != -1)
                $queryCmd .= ' and e.fromOthers= ?';

            if($cityId != 1 && $cityId !="" ){
                $queryCmd .= " and vcm.virtualCityId = ? ";
            }

            $queryCmd .= ' and v.country in ('.$countryId.') and co.countryId = v.country  and ci.city_id = v.city group by e.event_id order by startDateDiff LIMIT '. $start.' , '.($count-$cmsRows);
            	} else {
                    $queryCmd = 'select SQL_CALC_FOUND_ROWS e.event_title, e.event_id, e.fromOthers , e.listingType, e.listing_type_id,  d.start_date, FLOOR(LOG(ABS((unix_timestamp(d.start_date)-unix_timestamp(now()))/86400))) startDateDiff,v.venue_name, v.Address_Line1, v.state, co.name as country, ci.city_name as city  from event e, event_date d,   event_venue v, countryTable co, countryCityTable ci, event_venue_mapping evm ,eventCategoryTable ect , virtualCityMapping vcm where  ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and e.status_id is NULL and evm.event_id = e.event_id and evm.venue_id=v.venue_id  and e.event_id=d.event_id and e.event_id not in ('. $cmsEventIDs .') and   vcm.city_id = v.city ';

            if($fromOthers != -1)
                $queryCmd .= ' and e.fromOthers= ?';

            if($cityId != 1 && $cityId != ""){
                $queryCmd .= " and vcm.virtualCityId = ? ";
            }

            $queryCmd .= ' and co.countryId = v.country  and ci.city_id = v.city group by e.event_id order by startDateDiff LIMIT '. $start.' , '.($count-$cmsRows);
                }
			error_log_shiksha( 'getEventsForHomePageS step 2 query cmd is ' . $queryCmd,'events');
			$query = $dbHandle->query($queryCmd, array($fromOthers,$cityId));
			$DbRows=$query->num_rows();
	        	foreach ($query->result() as $row){
				if(strlen($cmsEventIDs)>0){
					$cmsEventIDs .= ' , ';
				}
				$cmsEventIDs .= $row->event_id;
                		$locationsArr[0]['city_name'] = $row->city;
                		$locationsArr[0]['country_name'] = $row->country;
        	    		array_push($msgArray,array(
                	            array(
                        	        'title'=>array($row->event_title,'string'),
                                	'id'=>array($row->event_id,'string'),
                                	'start_date'=>array($row->start_date,'string'),
	                                'venue_name'=>array($row->venue_name,'string'),
        	                        'Address_Line1'=>array($row->Address_Line1,'string'),
                	                'city'=>array($row->city,'string'),
                        	        'state'=>array($row->state,'string'),
                                    	'country'=>array($row->country,'string'),
                                    	'isSponsored'=>array('false','string'),
                                    	'url' => array($this->getEventUrl($row->event_id,$row->event_title,$row->fromOthers , $locationsArr ,$row->listingType, $row->listing_type_id),'string')
                                    ),'struct')
            			);//close array_push

        		}
			$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
			$query = $dbHandle->query($queryCmd);
			$totalRows = 0;
			foreach ($query->result() as $row) {
				$totalRows = $row->totalRows;
			}

		}



		//Step 3 if still left get it from parent Category phew!
		$totalRowsLeft=$DbRows+$cmsRows;
		if($totalRowsLeft < $count) {
			if(strlen($cmsEventIDs)==0){
				$cmsEventIDs = '\'\'';
			}
        		if($countryId != 1 && $countryId != ""){
                    $queryCmd = 'select e.event_title, e.event_id, e.fromOthers , e.listingType, e.listing_type_id,  d.start_date, FLOOR(LOG(ABS((unix_timestamp(d.start_date)-unix_timestamp(now()))/86400))) startDateDiff, v.venue_name, v.Address_Line1, v.state, co.name as country, ci.city_name as city  from event e, event_date d,   event_venue v, countryTable co, countryCityTable ci, event_venue_mapping evm,eventCategoryTable ect, virtualCityMapping vcm where  ect.eventId=e.event_id and ect.boardId in (\''.$parentCategory.'\') and evm.event_id = e.event_id and e.status_id is NULL and evm.event_id = e.event_id and evm.venue_id=v.venue_id  and e.event_id=d.event_id and e.event_id not in ('. $cmsEventIDs .') and vcm.city_id = v.city ';

            if($fromOthers != -1)
                $queryCmd .= ' and e.fromOthers= ?';

            if($cityId != 1 && $cityId != ""){
                $queryCmd .= " and vcm.virtualCityId = ? ";
            }

               $queryCmd .= ' and v.country in ('.$countryId.') and co.countryId = v.country  and ci.city_id = v.city group by e.event_id order by startDateDiff LIMIT '. $start.' , '.($count-$totalRowsLeft);
            	} else {
                    $queryCmd = 'select e.event_title, e.event_id, e.fromOthers , e.listingType, e.listing_type_id,  d.start_date, FLOOR(LOG(ABS((unix_timestamp(d.start_date)-unix_timestamp(now()))/86400))) startDateDiff,v.venue_name, v.Address_Line1, v.state, co.name as country, ci.city_name as city  from event e, event_date d,   event_venue v, countryTable co, countryCityTable ci, event_venue_mapping evm ,eventCategoryTable ect, virtualCityMapping vcm where  ect.eventId=e.event_id and ect.boardId in (\''.$parentCategory.'\') and evm.event_id = e.event_id and e.status_id is NULL and evm.event_id = e.event_id and evm.venue_id=v.venue_id  and e.event_id=d.event_id and e.event_id not in ('. $cmsEventIDs .') and vcm.city_id = v.city ';

            if($fromOthers != -1)
                $queryCmd .= ' and e.fromOthers= ?';

            if($cityId != 1 && $cityId != ""){
                $queryCmd .= " and vcm.virtualCityId = ? ";
            }

            $queryCmd .= ' and co.countryId = v.country  and ci.city_id = v.city group by e.event_id order by startDateDiff LIMIT '. $start.' , '.($count-$totalRowsLeft);
                }
			error_log_shiksha( 'getEventsForHomePageS step 3 query cmd is ' . $queryCmd,'events');
			$query = $dbHandle->query($queryCmd,array($fromOthers,$cityId));
			$DbRows=$query->num_rows();
	        	foreach ($query->result() as $row){
                		$locationsArr[0]['city_name'] = $row->city;
                		$locationsArr[0]['country_name'] = $row->country;
        	    		array_push($msgArray,array(
                	            array(
                        	        'title'=>array($row->event_title,'string'),
                                	'id'=>array($row->event_id,'string'),
                                	'start_date'=>array($row->start_date,'string'),
	                                'venue_name'=>array($row->venue_name,'string'),
        	                        'Address_Line1'=>array($row->Address_Line1,'string'),
                	                'city'=>array($row->city,'string'),
                        	        'state'=>array($row->state,'string'),
                                    	'country'=>array($row->country,'string'),
                                    	'isSponsored'=>array('false','string'),
                                    	'url' => array($this->getEventUrl($row->event_id,$row->event_title,$row->fromOthers , $locationsArr ,$row->listingType, $row->listing_type_id),'string')
                                    ),'struct')
            			);//close array_push

        		}
		}

		//Send the response back
		$mainArr = array();
		array_push($mainArr,array(
				array(
					'results'=>array($msgArray,'struct'),
					'totalCount'=>array($totalRows,'string'),
				),'struct')
		);//close array_push
		$response = array($mainArr,'struct');
        	return $this->xmlrpc->send_response($response);

	}


	/*
	* This function returns the recent event for CMS interface
	*/
	function getRecentEventCMS($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$start=$parameters['1'];
		$count=$parameters['2'];
		$categoryId=$parameters['3'];
                $searchCriteria1=trim($parameters['4']);
                $searchCriteria2=trim($parameters['5']);
                $filter1=trim($parameters['6']);
                $filter2=trim($parameters['7']);
		$showReportedAbuse=$parameters['8'];
		$usergroup=$parameters['9'];
		$userid=$parameters['10'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

                if($searchCriteria1!=''){
                   $addSearch1 = 'AND e.event_title LIKE "%'.$searchCriteria1.'%"';
                }else{
                   $addSearch1 = '';
                }

                if($searchCriteria2!=''){
                   $addSearch2 = 'AND v.Address_Line1 LIKE "%'.$searchCriteria2.'%"';
                }else{
                   $addSearch2 = '';
                }

                if($filter1!=''){
                   $addFilter1 = 'AND e.fromOthers = '.$filter1.'';
                }else{
                   $addFilter1 = '';
                }

                if($filter2!=''){
                   $addFilter2 = 'AND v.country = '.$filter2.'';
                }else{
                   $addFilter2 = '';
                }

                if($usergroup=='cms'){
                   $addUserid = '';
                }else if($usergroup=='enterprise'){
                   $addUserid = 'AND e.user_id = '.$userid.' ';
                }

		if($showReportedAbuse=='true'){
                	$queryCmd = 'select e.event_title, e.event_id, e.description as des, d.start_date, d.end_date, v.Address_Line1, v.contact_person, v.email from event e, event_date d, event_venue v , event_venue_mapping evm,eventCategoryTable ect where  ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and abuse>0 and e.status_id is NULL and e.event_id=d.event_id AND evm.event_id = e.event_id and evm.venue_id=v.venue_id '.$addSearch1.' '.$addSearch2.' '.$addFilter1.' '.$addFilter2.' '.$addUserid.' ORDER BY d.start_date DESC LIMIT '. $start.' , '.$count;


        	        $queryCmdCount = 'select e.event_title, e.event_id, e.description as des, d.start_date, d.end_date, v.Address_Line1, v.contact_person, v.email from event e, event_date d, event_venue v ,  event_venue_mapping evm,eventCategoryTable ect where  ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and abuse>0 and e.status_id is NULL and e.event_id=d.event_id AND evm.event_id = e.event_id and  evm.venue_id=v.venue_id '.$addSearch1.' '.$addSearch2.' '.$addFilter1.' '.$addFilter2.' '.$addUserid.' ';

		}else{
			$queryCmd = 'select e.event_title, e.event_id, e.description as des, d.start_date, d.end_date, v.Address_Line1, v.contact_person, v.email from event e, event_date d, event_venue v, event_venue_mapping evm,eventCategoryTable ect  where  ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and abuse=0 and e.status_id is NULL and e.event_id=d.event_id AND evm.event_id = e.event_id and  evm.venue_id=v.venue_id '.$addSearch1.' '.$addSearch2.' '.$addFilter1.' '.$addFilter2.' '.$addUserid.' ORDER BY d.start_date DESC LIMIT '. $start.' , '.$count;


        	        $queryCmdCount = 'select e.event_title, e.event_id, e.description as des, d.start_date, d.end_date, v.Address_Line1, v.contact_person, v.email from event e, event_date d, event_venue v, event_venue_mapping evm,eventCategoryTable ect where  ect.eventId=e.event_id and ect.boardId in (\'' . $categoryId .'\') and evm.event_id = e.event_id and abuse=0 and e.status_id is NULL and e.event_id=d.event_id AND evm.event_id = e.event_id and  evm.venue_id=v.venue_id '.$addSearch1.' '.$addSearch2.' '.$addFilter1.' '.$addFilter2.' '.$addUserid.' ';

		}

                error_log_shiksha( 'getRecentEventCMS query cmd is ' . $queryCmd,'events');
               // error_log_shiksha("SHAS ".$queryCmd,'events');

		$query = $dbHandle->query($queryCmd);
		$countQuery = $dbHandle->query($queryCmdCount);
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
							array(
								'totalCount'=>array($countQuery->num_rows(),'string'),
								'title'=>array($row->event_title,'string'),
								'id'=>array($row->event_id,'string'),
								'text'=>array($row->des,'string'),
								'start_date'=>array($row->start_date,'string'),
								'end_date'=>array($row->end_date,'string'),
								'venue_name'=>array($row->Address_Line1,'string'),
								'contact_person'=>array($row->contact_person,'string'),
								'email'=>array($row->email,'string')
							),'struct')
			);//close array_push

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}


	/*
	*	Get details for search, used for getting details for event, XXX not used anymore
	*/
	function getDetailsForSearch($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$reqArray=$parameters['1'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		$queryCmd = 'select user_id,t.displayName,t.lastlogintime,t.avtarimageurl userImage,(select level from userPointLevel where minLimit<=upv.userPointValue limit 1) level from event,tuser t,userPointSystem upv where (';

		$i=0;
		foreach ($reqArray as $tempArr){
			if($i>0){
				$queryCmd .= ' or ' ;
			}
			$i++;
			$queryCmd .= 'event_id='. $tempArr;

		}
		$queryCmd .= ' ) and status_id is NULL and user_id=upv.userId and  user_id=t.userid';
		error_log_shiksha( 'getDetailsForSearch event query cmd is ' . $queryCmd,'events');
		$query = $dbHandle->query($queryCmd);
		$msgArray = array();
		foreach ($query->result_array() as $row){
                       array_push($msgArray,array($row,'struct'));
               	}
               	$response = array($msgArray,'struct');
               	return $this->xmlrpc->send_response($response);
	}


	/*
	 * Used for full indexing. XXX need more work here for a better indexing. Currently its being called from search in a for loop as
	 * curl request.
	 */
	function getEventForIndex($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$id=$parameters['1'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
        	$queryCmd="select event.event_id Id, GROUP_CONCAT(DISTINCT eventCategoryTable.boardId SEPARATOR ',') boardId, GROUP_CONCAT(DISTINCT categoryBoardTable.name SEPARATOR ',') categoryName, event_title,event.description,start_date,end_date ,venue_name ,Address_Line1 ,city,state, event_venue.country as country,event.Tag tags,user_id,FLOOR(LOG(ABS((unix_timestamp(event_date.start_date)-unix_timestamp(now()))/86400))) packtype , event.fromOthers, event.listingType, event.listing_type_id from event,event_date,event_venue, event_venue_mapping,eventCategoryTable, categoryBoardTable where event.event_id=event_date.event_id and status_id is NULL and event.event_id = event_venue_mapping.event_id and event_venue_mapping.venue_id=event_venue.venue_id and eventCategoryTable.eventid=event.event_id and categoryBoardTable.boardId = eventCategoryTable.boardId and event.event_id= ? group by event.event_id";

		error_log_shiksha('getDetailsForSearch event query cmd is ' . $queryCmd,'events');
		$query = $dbHandle->query($queryCmd,array($id));
        	$i = 0;
        	$cityArr = array();
        	$stateArr = array();
        	$addrArr = array();
        	$countryArr =array();
        	foreach ($query->result() as $row){
            		$cityArr[$i] = $row->city;
            		$stateArr[$i] = $row->state;
            		$countryArr[$i] = $row->country;
            		$addrArr[$i] = $row->Address_Line1;
            		$i++;
        	}

        	$msgArray = array();
        	foreach ($query->result() as $row){
            		$locationsArr[0]['city_name'] = $row->city;
           		$locationsArr[0]['country_name'] = $row->country;
            		//array_push($msgArray,array($row,'struct'));
            		array_push($msgArray,array(
                        	array(
                            	'Id'=>array($row->Id,'string'),
                            	'board_id'=>array($row->board_id,'string'),
	                        'categoryName'=>array($row->categoryName,'string'),
        	                'event_title'=>array($row->event_title,'string'),
                	        'description'=>array($row->description,'string'),
                            	'start_date'=>array($row->start_date,'string'),
                            	'end_date'=>array($row->end_date,'string'),
                            	'venue_name'=>array($row->venue_name,'string'),
                            	'Address_Line1'=>array(implode(',',$addrArr),'string'),
                            	'city'=>array(implode(',',$cityArr),'string'),
                            	'state'=>array(implode(',',$stateArr),'string'),
                            	'country'=>array(implode(',',$countryArr),'string'),
                            	'tags'=>array($row->tags,'string'),
                            	'user_id'=>array($row->user_id,'string'),
                            	'packtype'=>array($row->packtype,'string'),
                            	'detailPageUrl' => array($this->getEventUrl($row->Id,$row->event_title,$row->fromOthers , $locationsArr ,$row->listingType, $row->listing_type_id),'string')
                            	),'struct')
                    	);//close array_push

            		break;
        	}
               	$response = array($msgArray,'struct');
               	return $this->xmlrpc->send_response($response);
	}

	/*
	 * Add a admission event from listing page
	 */
	function addEventForListing($request){
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$appId=$dbHandle->escape($parameters['0']);
		$reqArray=$dbHandle->escape($parameters['1']);
		$fromOthers = $dbHandle->escape($parameters['2']);
		$listingTypeId = $dbHandle->escape($parameters['3']);
		$listingType = $dbHandle->escape($parameters['4']);

		//Adding basic event fields & getting event id
		$data =array(
			'event_title'=>$reqArray['event_title'],
			'venue_id'=>'',
			'contact_person'=>$reqArray['contact_person'],
			'description'=>$reqArray['description'],
			'user_id'=>$reqArray['user_id'],
			'event_url'=>$reqArray['url'],
			'Tag'=>$reqArray['Tag'],
			'threadId'=>$reqArray['threadId'],
			'fromOthers'=>$fromOthers,
			'listing_type_id' => $listingTypeId,
			'listingType' => $listingType
		);

		$queryCmd = $dbHandle->insert_string('event',$data);
        	error_log_shiksha(' debug addEvent insert event query cmd is ' . $queryCmd,'events');

        	if(!$dbHandle->query($queryCmd)){
            		error_log_shiksha( 'add event query cmd failed' . $queryCmd,'events');
            		error_log_shiksha(' error addEvent insert event query cmd is ' . $queryCmd,'events');
        	}

		//        $query = $dbHandle->query($queryCmd);
        	$eventId=$dbHandle->insert_id();



		//add category
		$categoryArray = array();
		$tempArray = explode(",", $reqArray['board_id']);
		foreach($tempArray as $temp)
		{
			if($temp != '')
				array_push($categoryArray,$temp);
		}
		array_push($categoryArray,"1");
		$queryCmd = 'insert eventCategoryTable values ';
		$commaCount=0;
		foreach ($categoryArray as $boardId) {
			if($commaCount>0){
				$queryCmd.=",";
			}
			$queryCmd.="($eventId,$boardId)";
			if($boardId>1){
				$queryCmd.=",($eventId,(select parentId from categoryBoardTable where boardId= ".$dbHandle->escape($boardId)."))";
			}
			$commaCount++;
		}
		$queryCmd.= " on duplicate key update eventCategoryTable.eventId = ".$dbHandle->escape($eventId);
		error_log_shiksha( 'addEvent category query cmd is ' . $queryCmd,'events');
		$query = $dbHandle->query($queryCmd);


		//

		//Adding start-date & end date info
        	$data = array(
			'event_id'=>$eventId,
			'start_date'=>$reqArray['start_date'],
			'end_date'=>$reqArray['end_date']
		);

		$queryCmd = $dbHandle->insert_string('event_date',$data);
        	error_log_shiksha(' debug addEvent insert event query cmd is ' . $queryCmd,'events');
        	if(!$dbHandle->query($queryCmd)){
            		error_log_shiksha( 'add event query cmd failed' . $queryCmd,'events');
        	}


		//Adding multiple locations for event
		$venueArray = $reqArray[0]['venues'];
        	error_log_shiksha("NEWEVENT ".print_r($venueArray,true),'events');
        	error_log_shiksha("NEWEVENT ".count($venueArray),'events');
        	for($i = 0; $i < count($venueArray); $i++){
            	$data =array(
                    'venue_name'=>$venueArray[$i]['venue_name'],
                    'Address_Line1'=>$venueArray[$i]['Address_Line1'],
                    'city'=>$venueArray[$i]['city'],
                    'state'=>$venueArray[$i]['state'],
                    'zip'=>$venueArray[$i]['zip'],
                    'phone'=> $venueArray[$i]['phone'] ,
                    'fax'=>$venueArray[$i]['fax'],
                    'email'=>$venueArray[$i]['email'],
                    'mobile'=>$venueArray[$i]['mobile'],
                    'url'=>$venueArray[$i]['url'],
                    'contact_person'=>$venueArray[$i]['contact_person'],
                    'country'=>$venueArray[$i]['country']
                );

            	$queryCmd = $dbHandle->insert_string('event_venue',$data);
            	error_log_shiksha(' debug addEvent insert event query cmd is ' . $queryCmd,'events');
            	if(!$dbHandle->query($queryCmd)){
                	error_log_shiksha( 'add event query cmd failed' . $queryCmd,'events');
            	}
            	$venueId=$dbHandle->insert_id();

		//Putting map-row in event_venue_mapping table
            	$data = array(
                    'event_id'=>$eventId,
                    'venue_id'=>$venueId
                    );

            	$queryCmd = $dbHandle->insert_string('event_venue_mapping',$data);
            	error_log_shiksha(' debug addEvent insert event query cmd is ' . $queryCmd,'events');
            	if(!$dbHandle->query($queryCmd)){
                	error_log_shiksha( 'add event query cmd failed' . $queryCmd,'events');
            	}
        	}

		error_log_shiksha( 'addEvent insert event date ID is ' . $eventId,'events');
		$response = array(array(
						'eventId'  => array($eventId,'string')),
                    'struct');

		//update User point system
		$queryCmd = 'update userPointSystem set userPointValue=userPointValue+10 where userId='.$reqArray['user_id'] ;
        	error_log_shiksha(' debug addEvent update ups insert event query cmd is ' . $queryCmd,'events');
		if(!$dbHandle->query($queryCmd)){
			error_log_shiksha( 'update_Event_UserPointSystem query cmd failed' . $queryCmd,'events');
		}

        	$exams = $reqArray['examSelected'];
        	$this->makeEventExamsMapping($eventId, $exams);
		return $this->xmlrpc->send_response($response);
	}

	/*
	 * Events to Exam article mapping
	 */
    	private function makeEventExamsMapping($eventId, $exams) {
		$dbConfig = array( 'hostname'=>'localhost');
		$this->eventcalconfig->getDbConfig($appId,$dbConfig);
        	$this->load->model('ExamModel','',$dbConfig);
        	$this->ExamModel->makeEntityExamsMapping($eventId, $exams,'events');
        	return ;
    	}

	/*
	 * Get all event related for a exam article
	 */
    	function getEventsForExams($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
        	$blogId=$parameters['1'];
        	$start=$parameters['2'];
        	$count=$parameters['3'];
		$categoryId=$parameters['4'];
		$fromOthers=$parameters['5'];
        	//connect DB
        	$this->load->model('EventModel');
        	$msgArray = array();
        	$examsSelected = $this->EventModel->getEventsForExams($blogId,$start,$count, $fromOthers);
		return $this->xmlrpc->send_response($examsSelected);
    	}

	/*
	 * Get exams article for a given event
	 */
    	function getExamsForEvent($request){
		$parameters = $request->output_parameters();
        	$appId = $parameters['0'];
        	$eventId = $parameters['1'];
        	$this->load->model('ExamModel');
        	$examsSelected = $this->ExamModel->getExamsForEntity($eventId, 'events');
        	return $this->xmlrpc->send_response($examsSelected);
    	}

	function addEventsForListing($request){
	$parameters = $request->output_parameters();
	$appId = $parameters['0']['0'];
	$data= unserialize($parameters['1']);
	$data['course_id'] = $parameters['2'];
	 //connect DB
	 $dbHandle = $this->_loadDatabaseHandle('write');

		$queryCmd = "select country_id,city_id from institute_location_table where institute_id = ? ";
		$instituteLocation = $dbHandle->query($queryCmd, array($data['institute_id']));
		foreach($instituteLocation->result_array() as $instituteLocationRow){
                $instituteCountryId = $instituteLocationRow['country_id'];
		$instituteCityId = $instituteLocationRow['city_id'];
           	 }
		if(stripos($data['date_form_submission'],'1970') === false) {
                    }else {
                        $data['date_form_submission'] = "0000-00-00 00:00:00";
                    }
                    if(stripos($data['date_result_declaration'],'1970') === false) {
                    }else {
                        $data['date_result_declaration'] = "0000-00-00 00:00:00";
                    }
                    if(stripos($data['date_course_comencement'],'1970') === false) {
                    }else {
                        $data['date_course_comencement'] = "0000-00-00 00:00:00";
                    }
		if($data['date_form_submission']!="0000-00-00 00:00:00" && $data['date_form_submission']!=''){
		$title="Application submission deadline for ".$data['courseTitle']." at ".$data['institute_name'];
//		$title=date("M-d (D)",strtotime($data['date_form_submission'])).' : '.$title;
		$fromOthers=0;
		$dataArray =array(
                        'city'=>$instituteCityId,
                        'country'=>$instituteCountryId
                );

                // First add event venue
                $queryCmd = $dbHandle->insert_string('event_venue',$dataArray);
		error_log_shiksha("query for inserting event_venue".$queryCmd);
                $query = $dbHandle->query($queryCmd);
                $venueId=$dbHandle->insert_id();
		$dataArray =array(
                        'event_title'=>$title,
                        'venue_id'=>$venueId,
                        'user_id'=>$data['username'],
                        'fromOthers'=>$fromOthers,
			'threadId'=>$data['threadId'],
			'listing_type_id'=>$data['course_id'],
			'listingType'=>'course'
                );
		// add event details
                $queryCmd = $dbHandle->insert_string('event',$dataArray);
                $query = $dbHandle->query($queryCmd);
                $eventId=$dbHandle->insert_id();

		// add event dates
		 $dataArray = array(
                        'event_id'=>$eventId,
                        'start_date'=>date('Y-m-d',strtotime($data['date_form_submission'])),
                        'end_date'=>date('Y-m-d',strtotime($data['date_form_submission']))
                );

                $queryCmd = $dbHandle->insert_string('event_date',$dataArray);
                error_log_shiksha( 'addEvent insert event date query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$categoryArray = array();
		array_push($categoryArray,"1");
		$tempArray = explode(",", $data['category_id']);
                foreach($tempArray as $temp)
                {
                        if($temp != '')
                                array_push($categoryArray,$temp);
                }
		$queryCmd = 'insert eventCategoryTable values ';
		$commaCount=0;
                foreach ($categoryArray as $boardId) {
                        if($commaCount>0){
                                $queryCmd.=",";
                        }
                        $queryCmd.="($eventId,$boardId)";

                        if($boardId>1){
                                $queryCmd.=",($eventId,(select parentId from categoryBoardTable where boardId= ".$dbHandle->escape($boardId)."))";
                        }
                        $commaCount++;
                }
                $queryCmd.= " on duplicate key update eventCategoryTable.eventId = ".$dbHandle->escape($eventId);
                error_log_shiksha( 'addEvent category query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$dataArray = array(
                        'event_id'=>$eventId,
                        'venue_id'=>$venueId
                );
                //update event venue mapping
                $queryCmd = $dbHandle->insert_string('event_venue_mapping',$dataArray);
                error_log_shiksha( 'addEvent insert event venue mapping query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
		}


		if($data['date_result_declaration']!="0000-00-00 00:00:00" && $data['date_result_declaration']!=''){
		$title="Admission result for ".$data['courseTitle']." at ".$data['institute_name'];
//                $title=date("M-d (D)",strtotime($data['date_result_declaration'])).' : '.$title;
		$fromOthers=2;

		 $dataArray =array(
                        'city'=>$instituteCityId,
                        'country'=>$instituteCountryId
                );

                // First add event venue
                $queryCmd = $dbHandle->insert_string('event_venue',$dataArray);
                $query = $dbHandle->query($queryCmd);
                $venueId=$dbHandle->insert_id();

		$dataArray =array(
                        'event_title'=>$title,
                        'venue_id'=>$venueId,
                        'user_id'=>$data['username'],
                        'fromOthers'=>$fromOthers,
			'threadId'=>$data['threadId'],
			'listing_type_id'=>$data['course_id'],
			'listingType'=>'course'
                );
		// add event details
                $queryCmd = $dbHandle->insert_string('event',$dataArray);
                error_log_shiksha( 'addEvent insert event query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
                $eventId=$dbHandle->insert_id();

		// add event dates
		 $dataArray = array(
                        'event_id'=>$eventId,
                        'start_date'=> date('Y-m-d',strtotime($data['date_result_declaration'])),
                        'end_date'=>date('Y-m-d',strtotime($data['date_result_declaration']))
                );

                $queryCmd = $dbHandle->insert_string('event_date',$dataArray);
                error_log_shiksha( 'addEvent insert event date query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$categoryArray = array();
		array_push($categoryArray,"1");
		$tempArray = explode(",", $data['category_id']);
                foreach($tempArray as $temp)
                {
                        if($temp != '')
                                array_push($categoryArray,$temp);
                }
		$queryCmd = 'insert eventCategoryTable values ';
		$commaCount=0;
                foreach ($categoryArray as $boardId) {
                        if($commaCount>0){
                                $queryCmd.=",";
                        }
                        $queryCmd.="($eventId,$boardId)";

                        if($boardId>1){
                                $queryCmd.=",($eventId,(select parentId from categoryBoardTable where boardId= ".$dbHandle->escape($boardId)."))";
                        }
                        $commaCount++;
                }
                $queryCmd.= " on duplicate key update eventCategoryTable.eventId = ".$dbHandle->escape($eventId);
                error_log_shiksha( 'addEvent category query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		 $dataArray = array(
                        'event_id'=>$eventId,
                        'venue_id'=>$venueId
                );
                //update event venue mapping
                $queryCmd = $dbHandle->insert_string('event_venue_mapping',$dataArray);
                error_log_shiksha( 'addEvent insert event venue mapping query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
		}

		if($data['date_course_comencement']!="0000-00-00 00:00:00" && $data['date_course_comencement']!=''){
		$title="Course comencement for ".$data['courseTitle']." at ".$data['institute_name'];
//                $title=date("M-d (D)",strtotime($data['date_course_comencement'])).' : '.$title;
		$fromOthers=1;

		 $dataArray =array(
                        'city'=>$instituteCityId,
                        'country'=>$instituteCountryId
                );

                // First add event venue
                $queryCmd = $dbHandle->insert_string('event_venue',$dataArray);
                $query = $dbHandle->query($queryCmd);
                $venueId=$dbHandle->insert_id();

		$dataArray =array(
                        'event_title'=>$title,
                        'venue_id'=>$venueId,
                        'user_id'=>$data['username'],
                        'fromOthers'=>$fromOthers,
			'threadId'=>$data['threadId'],
			'listing_type_id'=>$data['course_id'],
			'listingType'=>'course'
                );
		// add event details
                $queryCmd = $dbHandle->insert_string('event',$dataArray);
                error_log_shiksha( 'addEvent insert event query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
                $eventId=$dbHandle->insert_id();

		// add event dates
		 $dataArray = array(
                        'event_id'=>$eventId,
                        'start_date'=> date('Y-m-d',strtotime($data['date_course_comencement'])),
                        'end_date'=>date('Y-m-d',strtotime($data['date_course_comencement']))
                );

                $queryCmd = $dbHandle->insert_string('event_date',$dataArray);
                error_log_shiksha( 'addEvent insert event date query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$categoryArray = array();
		array_push($categoryArray,"1");
		$tempArray = explode(",", $data['category_id']);
                foreach($tempArray as $temp)
                {
                        if($temp != '')
                                array_push($categoryArray,$temp);
                }
		$queryCmd = 'insert eventCategoryTable values ';
		$commaCount=0;
                foreach ($categoryArray as $boardId) {
                        if($commaCount>0){
                                $queryCmd.=",";
                        }
                        $queryCmd.="($eventId,$boardId)";

                        if($boardId>1){
                                $queryCmd.=",($eventId,(select parentId from categoryBoardTable where boardId= ".$dbHandle->escape($boardId)."))";
                        }
                        $commaCount++;
                }
                $queryCmd.= " on duplicate key update eventCategoryTable.eventId = ".$dbHandle->escape($eventId);
                error_log_shiksha( 'addEvent category query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		 $dataArray = array(
                        'event_id'=>$eventId,
                        'venue_id'=>$venueId
                );
                //update event venue mapping
                $queryCmd = $dbHandle->insert_string('event_venue_mapping',$dataArray);
                error_log_shiksha( 'addEvent insert event venue mapping query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
		}
                $response = "";
                return $this->xmlrpc->send_response($response);
	}
        
   function addUpdateEventsForListing($request){
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
	$courseId = $parameters['1'];
        $data = unserialize(base64_decode($parameters['2']));
	$data['course_id'] = $courseId;
         //connect DB
         $dbHandle = $this->_loadDatabaseHandle('write');

	$queryCmd = "select country_id,city_id from institute_location_table where institute_id = ? ";
		$instituteLocation = $dbHandle->query($queryCmd,array($data['institute_id']));
		foreach($instituteLocation->result_array() as $instituteLocationRow){
                $instituteCountryId = $instituteLocationRow['country_id'];
		$instituteCityId = $instituteLocationRow['city_id'];
           	 }
	if(stripos($data['date_form_submission'],'1970') === false) {
            }else {
                $data['date_form_submission'] = "0000-00-00 00:00:00";
            }

            if(stripos($data['date_result_declaration'],'1970') === false) {
            }else {
                $data['date_result_declaration'] = "0000-00-00 00:00:00";
            }

            if(stripos($data['date_course_comencement'],'1970') === false) {
            }else {
                $data['date_course_comencement'] = "0000-00-00 00:00:00";
            }
	$today=date("Y-m-d");
	$date_form_submission_older=date("Y-m-d",strtotime($data['date_form_submission_older']));
	if($date_form_submission_older<$today){
	if($data['date_form_submission']!="0000-00-00 00:00:00" && $data['date_form_submission']!=''){
		$title="Application submission deadline for ".$data['courseTitle']." at ".$data['institute_name'];
//		$title=date("M-d (D)",strtotime($data['date_form_submission'])).' : '.$title;
		$fromOthers=0;

		$dataArray =array(
                        'city'=>$instituteCityId,
                        'country'=>$instituteCountryId
                );

                // First add event venue
                $queryCmd = $dbHandle->insert_string('event_venue',$dataArray);
                $query = $dbHandle->query($queryCmd);
                $venueId=$dbHandle->insert_id();

		$dataArray =array(
                        'event_title'=>$title,
                        'venue_id'=>$venueId,
                        'user_id'=>$data['username'],
                        'fromOthers'=>$fromOthers,
			'threadId'=>$data['threadId'],
			'listing_type_id'=>$data['course_id'],
			'listingType'=>'course'
                );
		// add event details
                $queryCmd = $dbHandle->insert_string('event',$dataArray);
                $query = $dbHandle->query($queryCmd);
                $eventId=$dbHandle->insert_id();

		// add event dates
		 $dataArray = array(
                        'event_id'=>$eventId,
                        'start_date'=> date('Y-m-d',strtotime($data['date_form_submission'])),
                        'end_date'=>date('Y-m-d',strtotime($data['date_form_submission']))
                );

                $queryCmd = $dbHandle->insert_string('event_date',$dataArray);
                error_log_shiksha( 'addEvent insert event date query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$categoryArray = array();
		array_push($categoryArray,"1");
		$tempArray = explode(",", $data['category_id']);
                foreach($tempArray as $temp)
                {
                        if($temp != '')
                                array_push($categoryArray,$temp);
                }
		$queryCmd = 'insert eventCategoryTable values ';
		$commaCount=0;
                foreach ($categoryArray as $boardId) {
                        if($commaCount>0){
                                $queryCmd.=",";
                        }
                        $queryCmd.="($eventId,$boardId)";

                        if($boardId>1){
                                $queryCmd.=",($eventId,(select parentId from categoryBoardTable where boardId= ".$dbHandle->escape($boardId)."))";
                        }
                        $commaCount++;
                }
                $queryCmd.= " on duplicate key update eventCategoryTable.eventId = ".$dbHandle->escape($eventId);
                error_log_shiksha( 'addEvent category query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$dataArray = array(
                        'event_id'=>$eventId,
                        'venue_id'=>$venueId
                );
                //update event venue mapping
                $queryCmd = $dbHandle->insert_string('event_venue_mapping',$dataArray);
                error_log_shiksha( 'addEvent insert event venue mapping query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
		}
		}else if($date_form_submission_older!==$data['date_form_submission']){
		$title="Application submission deadline for ".$data['courseTitle']." at ".$data['institute_name'];
//                $title=date("M-d (D)",strtotime($data['date_form_submission'])).' : '.$title;
		$queryCmd = "update event_date ed,event e set ed.start_date='".date('Y-m-d',strtotime($data['date_form_submission']))."',ed.end_date='".date('Y-m-d',strtotime($data['date_form_submission']))."',e.event_title='".mysql_real_escape_string($title)."' where e.event_id=ed.event_id and e.listing_type_id= ? and e.listingType='course' and e.fromOthers=0 and ed.end_date='".$date_form_submission_older."'";
                error_log_shiksha("Update query for event_date is:::::".$queryCmd);
                $dbHandle->query($queryCmd,array($data['course_id']));
		}

		$date_result_declaration_older=date("Y-m-d",strtotime($data['date_result_declaration_older']));
		if($date_result_declaration_older<$today){
		if($data['date_result_declaration']!="0000-00-00 00:00:00" && $data['date_result_declaration']!=''){
		$title="Admission result for ".$data['courseTitle']." at ".$data['institute_name'];
//                $title=date("M-d (D)",strtotime($data['date_result_declaration'])).' : '.$title;
		$fromOthers=2;

		 $dataArray =array(
                        'city'=>$instituteCityId,
                        'country'=>$instituteCountryId
                );

                // First add event venue
                $queryCmd = $dbHandle->insert_string('event_venue',$dataArray);
                $query = $dbHandle->query($queryCmd);
                $venueId=$dbHandle->insert_id();

		$dataArray =array(
                        'event_title'=>$title,
                        'venue_id'=>$venueId,
                        'user_id'=>$data['username'],
                        'fromOthers'=>$fromOthers,
			'threadId'=>$data['threadId'],
			'listing_type_id'=>$data['course_id'],
			'listingType'=>'course'
                );
		// add event details
                $queryCmd = $dbHandle->insert_string('event',$dataArray);
                error_log_shiksha( 'addEvent insert event query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
                $eventId=$dbHandle->insert_id();

		// add event dates
		 $dataArray = array(
                        'event_id'=>$eventId,
                        'start_date'=> date('Y-m-d',strtotime($data['date_result_declaration'])),
                        'end_date'=>date('Y-m-d',strtotime($data['date_result_declaration']))
                );

                $queryCmd = $dbHandle->insert_string('event_date',$dataArray);
                error_log_shiksha( 'addEvent insert event date query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$categoryArray = array();
		array_push($categoryArray,"1");
		$tempArray = explode(",", $data['category_id']);
                foreach($tempArray as $temp)
                {
                        if($temp != '')
                                array_push($categoryArray,$temp);
                }
		$queryCmd = 'insert eventCategoryTable values ';
		$commaCount=0;
                foreach ($categoryArray as $boardId) {
                        if($commaCount>0){
                                $queryCmd.=",";
                        }
                        $queryCmd.="($eventId,$boardId)";

                        if($boardId>1){
                                $queryCmd.=",($eventId,(select parentId from categoryBoardTable where boardId= ".$dbHandle->escape($boardId)."))";
                        }
                        $commaCount++;
                }
                $queryCmd.= " on duplicate key update eventCategoryTable.eventId = ".$dbHandle->escape($eventId);
                error_log_shiksha( 'addEvent category query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		 $dataArray = array(
                        'event_id'=>$eventId,
                        'venue_id'=>$venueId
                );
                //update event venue mapping
                $queryCmd = $dbHandle->insert_string('event_venue_mapping',$dataArray);
                error_log_shiksha( 'addEvent insert event venue mapping query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
		}
		}
		else if($date_result_declaration_older!==$data['date_result_declaration']){
		$title="Admission result for ".$data['courseTitle']." at ".$data['institute_name'];
//                $title=date("M-d (D)",strtotime($data['date_result_declaration'])).' : '.$title;
                $queryCmd = "update event_date ed,event e set ed.start_date='".date('Y-m-d',strtotime($data['date_result_declaration']))."',ed.end_date='".date('Y-m-d',strtotime($data['date_result_declaration']))."',e.event_title='".mysql_real_escape_string($title)."' where e.event_id=ed.event_id and e.listing_type_id= ? and e.listingType='course' and e.fromOthers=2 and ed.end_date='".$date_result_declaration_older."'";
                error_log_shiksha("Update query for event_date is:::::".$queryCmd);
                $dbHandle->query($queryCmd,array($data['course_id']));
                }

		$date_course_comencement_older=date("Y-m-d",strtotime($data['date_course_comencement_older']));
		if($date_course_comencement_older<$today){
		if($data['date_course_comencement']!="0000-00-00 00:00:00" && $data['date_course_comencement']!=''){
		$title="Course comencement for ".$data['courseTitle']." at ".$data['institute_name'];
//                $title=date("M-d (D)",strtotime($data['date_course_comencement'])).' : '.$title;
		$fromOthers=1;

		 $dataArray =array(
                        'city'=>$instituteCityId,
                        'country'=>$instituteCountryId
                );

                // First add event venue
                $queryCmd = $dbHandle->insert_string('event_venue',$dataArray);
                $query = $dbHandle->query($queryCmd);
                $venueId=$dbHandle->insert_id();

		$dataArray =array(
                        'event_title'=>$title,
                        'venue_id'=>$venueId,
                        'user_id'=>$data['username'],
                        'fromOthers'=>$fromOthers,
			'threadId'=>$data['threadId'],
			'listing_type_id'=>$data['course_id'],
			'listingType'=>'course'
                );
		// add event details
                $queryCmd = $dbHandle->insert_string('event',$dataArray);
                error_log_shiksha( 'addEvent insert event query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
                $eventId=$dbHandle->insert_id();

		// add event dates
		 $dataArray = array(
                        'event_id'=>$eventId,
                        'start_date'=> date('Y-m-d',strtotime($data['date_course_comencement'])),
                        'end_date'=>date('Y-m-d',strtotime($data['date_course_comencement']))
                );

                $queryCmd = $dbHandle->insert_string('event_date',$dataArray);
                error_log_shiksha( 'addEvent insert event date query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$categoryArray = array();
		array_push($categoryArray,"1");
		$tempArray = explode(",", $data['category_id']);
                foreach($tempArray as $temp)
                {
                        if($temp != '')
                                array_push($categoryArray,$temp);
                }
		$queryCmd = 'insert eventCategoryTable values ';
		$commaCount=0;
                foreach ($categoryArray as $boardId) {
                        if($commaCount>0){
                                $queryCmd.=",";
                        }
                        $queryCmd.="($eventId,$boardId)";

                        if($boardId>1){
                                $queryCmd.=",($eventId,(select parentId from categoryBoardTable where boardId= ".$dbHandle->escape($boardId)."))";
                        }
                        $commaCount++;
                }
                $queryCmd.= " on duplicate key update eventCategoryTable.eventId = ".$dbHandle->escape($eventId);
                error_log_shiksha( 'addEvent category query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		 $dataArray = array(
                        'event_id'=>$eventId,
                        'venue_id'=>$venueId
                );
                //update event venue mapping
                $queryCmd = $dbHandle->insert_string('event_venue_mapping',$dataArray);
                error_log_shiksha( 'addEvent insert event venue mapping query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
		}
		}else if($date_course_comencement_older!==$data['date_course_comencement']){
		$title="Course comencement for ".$data['courseTitle']." at ".$data['institute_name'];
//                $title=date("M-d (D)",strtotime($data['date_course_comencement'])).' : '.$title;
                $queryCmd = "update event_date ed,event e set ed.start_date='".date('Y-m-d',strtotime($data['date_course_comencement']))."',ed.end_date='".date('Y-m-d',strtotime($data['date_course_comencement']))."',e.event_title='".mysql_real_escape_string($title)."' where e.event_id=ed.event_id and e.listing_type_id= ? and e.listingType='course' and e.fromOthers=1 and ed.end_date='".$date_course_comencement_older."'";
                error_log_shiksha("Update query for event_date is:::::".$queryCmd);
                $dbHandle->query($queryCmd ,array($data['course_id']));
                }

                return $this->xmlrpc->send_response('');
   }

	function getSpotlightEvents($request){
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = "select * from spotlightEvent order by spotlight_id desc limit 1";
        log_message('debug', 'query cmd is ' . $queryCmd);
        $spotlightEvents = $dbHandle->query($queryCmd);
                foreach($spotlightEvents->result_array() as $spotlightEventsRow){
                $eventId1 = $spotlightEventsRow['event_id_1'];
                $eventId2 = $spotlightEventsRow['event_id_2'];
		$paidEventId = $spotlightEventsRow['paid_event_id'];
		$tillDate=$spotlightEventsRow['till_date'];
		$paidImageURL=$spotlightEventsRow['uploaded_image'];
                 }
	$current_date=date("Y-m-d");
	if(!empty($paidEventId) && $tillDate>=$current_date){
	$queryCmd = "select distinct e.event_id,e.event_title,e.fromOthers,d.start_date,d.end_date,co.name as countryName,ci.city_name as cityName,$paidEventId as paid_event_id,'$tillDate' as till_date,'$paidImageURL' as paid_image_url from event e,event_date d,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and co.countryId=ev.country and e.venue_id=ev.venue_id and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.status_id is NULL and e.event_id in ('$paidEventId','$eventId1','$eventId2')";
	}else{
	$queryCmd = "select distinct e.event_id,e.event_title,e.fromOthers,d.start_date,d.end_date,co.name as countryName,ci.city_name as cityName from event e,event_date d,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and co.countryId=ev.country and e.venue_id=ev.venue_id and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.status_id is NULL and e.event_id in ('$eventId1','$eventId2')";
	}
//	$response = $dbHandle->query($queryCmd);
	error_log_shiksha( 'query cmd is ' . $queryCmd);
        $query = $dbHandle->query($queryCmd);
	$msgArray = array();
                foreach ($query->result() as $row){
                        array_push($msgArray,array(
                                                        array(
                                                                'event_id'=>array($row->event_id,'int'),
                                                                'event_title'=>array($row->event_title,'string'),
								'fromOthers'=>array($row->fromOthers,'int'),
                                                                'start_date'=>array($row->start_date,'string'),
                                                                'end_date'=>array($row->end_date,'string'),
								'country_name'=>array($row->countryName,'string'),
                                                                'city_name'=>array($row->cityName,'string'),
								'paid_event_id'=>array($row->paid_event_id,'int'),
								'paid_image_url'=>array($row->paid_image_url,'string'),
								'till_date'=>array($row->till_date,'date')
                                                        ),'struct')
                        );//close array_push

                }
                $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
        }

	function getEventsByCategoryDateLocation($request){
		$parameters = $request->output_parameters();
		$country_name = $parameters['0'];
	        $category_id = $parameters['1'];
		$location_id = $parameters['2'];
		$start_date = $parameters['3'];
		$end_date = $parameters['4'];
                //connect DB
                $dbHandle = $this->_loadDatabaseHandle();
		if($country_name!='india'){
		if(empty($start_date) || empty($end_date)){
                //get the venue id
                $queryCmd = "(select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=0 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=3 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=4 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=2 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=1 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=5 and d.end_date>=DATE(now()) order by d.end_date limit 2)";
		}else{
		$queryCmd = "(select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=0 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=3 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=4 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=2 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=1 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=5 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2)";
		}
		}else{
		if(empty($start_date) || empty($end_date)){
		if($location_id!='All'){
                //get the venue id
                $queryCmd = "(select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=0 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=3 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=4 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=2 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=1 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=5 and d.end_date>=DATE(now()) order by d.end_date limit 2)";
		}else{
		$queryCmd = "(select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=0 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=3 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=4 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=2 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country=2 or e.assoc_country=2) and ect.boardId= ?  and e.status_id is NULL and e.fromOthers=1 and d.end_date>=DATE(now()) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=5 and d.end_date>=DATE(now()) order by d.end_date limit 2)";
		}
                }else{
		if($location_id!='All'){
		$queryCmd = "(select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=0 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=3 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=4 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=2 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=1 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId= ? and e.status_id is NULL and e.fromOthers=5 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2)";
		}else{
		$queryCmd = "(select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and ev.country=2 and ect.boardId= ? and e.status_id is NULL and e.fromOthers=0 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and ev.country=2 and ect.boardId= ? and e.status_id is NULL and e.fromOthers=3 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and ev.country=2 and ect.boardId= ? and e.status_id is NULL and e.fromOthers=4 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and ev.country=2 and ect.boardId= ? and e.status_id is NULL and e.fromOthers=2 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and ev.country=2 and ect.boardId= ? and e.status_id is NULL and e.fromOthers=1 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2) union (select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and e.event_id=ect.eventId and co.countryId=ev.country and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and ev.country=2 and ect.boardId= ? and e.status_id is NULL and e.fromOthers=5 and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit 2)";
		}
                }
		}
	        $query = $dbHandle->query($queryCmd, array($category_id,$category_id,$category_id,$category_id,$category_id,$category_id));
		error_log_shiksha("query is ".$queryCmd);
	        $msgArray = array();
                foreach ($query->result() as $row){
                        array_push($msgArray,array(
                                                        array(
                                                                'event_id'=>array($row->event_id,'int'),
                                                                'event_title'=>array($row->event_title,'string'),
                                                                'event_url'=>array($this->getEventUrl($row->event_id , $row->event_title, $row->fromOthers),'string'),
                                                                'start_date'=>array($row->start_date,'string'),
                                                                'end_date'=>array($row->end_date,'string'),
								'start_date_format'=>array(date("jS M,y",strtotime($row->start_date)),'string'),
                                                                'end_date_format'=>array(date("jS M,y",strtotime($row->end_date)),'string'),
                                                                'fromOthers'=>array($row->fromOthers,'int'),
								'country_name'=>array($row->countryName,'string'),
                                                                'city_name'=>array($row->cityName,'string'),
								'start_month'=>array(date("M",strtotime($row->start_date)),'string'),
                                                                'start_day'=>array(date("j",strtotime($row->start_date)),'string'),
								'end_month'=>array(date("M",strtotime($row->end_date)),'string'),
                                                                'end_day'=>array(date("j",strtotime($row->end_date)),'string')
                                                        ),'struct')
                        );//close array_push

                }
                $response = array($msgArray,'struct');
		error_log_shiksha("response is as ".print_r($response,true));
                return $this->xmlrpc->send_response($response);
        }

	function viewAllEvents($request){
                $parameters = $request->output_parameters();
		$countryName=$parameters['0'];
		$fromOthers = $parameters['1'];
                $category_id = $parameters['2'];
                $location_id = $parameters['3'];
		$start = $parameters['4'];
		$count = $parameters['5'];
		$days = $parameters['6'];
		if($days!='' && $days!='All'){
		if(strpos($days,"-")){
	        $start_date=$days;
	        $end_date=$days;
	        }else{
	        $end_date=date("Y-m-d",time()+(86400*($days)));
		if($days!=0){
	        $start_date=date("Y-m-d",time()+86400);
		}else{
		$start_date=date("Y-m-d");
		}
       		}
		}
                //connect DB
                $dbHandle = $this->_loadDatabaseHandle();
		if($countryName!='india'){
		if(empty($start_date) || empty($end_date)){
                if($location_id=='All'){
                $queryCmd="select count(distinct e.event_id) as count from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and co.countryId=ev.country and e.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ect.boardId= ? and e.status_id is NULL and e.fromOthers= ? and d.end_date>=DATE(now())";
                }else{
                $queryCmd="select count(distinct e.event_id) as count from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId= ? and e.status_id is NULL and e.fromOthers= ? and d.end_date>=DATE(now())";
                }
                }else{
                if($location_id=='All'){
                $queryCmd="select count(distinct e.event_id) as count from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and co.countryId=ev.country and e.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ect.boardId=? and e.fromOthers=? and e.status_id is NULL and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else{
                $queryCmd="select count(distinct e.event_id) as count from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where co.countryId=ev.country and e.event_id=d.event_id and e.venue_id=ev.venue_id and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId=? and e.status_id is NULL and e.fromOthers=? and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }
                }
		}else{
		if(empty($start_date) || empty($end_date)){
                if($location_id=='All'){
                $queryCmd="select count(distinct e.event_id) as count from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and co.countryId=ev.country and e.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country=2 or e.assoc_country=2) and ect.boardId=? and e.status_id is NULL and e.fromOthers=? and d.end_date>=DATE(now())";
                }else{
                $queryCmd="select count(distinct e.event_id) as count from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId=? and e.status_id is NULL and e.fromOthers=? and d.end_date>=DATE(now())";
                }
                }else{
                if($location_id=='All'){
                $queryCmd="select count(distinct e.event_id) as count from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and co.countryId=ev.country and e.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country=2 or e.assoc_country=2) and ect.boardId=? and e.fromOthers=? and e.status_id is NULL and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else{
                $queryCmd="select count(distinct e.event_id) as count from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where co.countryId=ev.country and e.event_id=d.event_id and e.venue_id=ev.venue_id and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId=? and e.fromOthers=? and e.status_id is NULL and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }
                }
		}
                error_log_shiksha("queryCmd is ".$queryCmd);
		$countArray = $dbHandle->query($queryCmd,array($category_id,$fromOthers));
                foreach($countArray->result_array() as $countArrayRow){
                $totalEvents = $countArrayRow['count'];
                 }
		if($countryName!='india'){
		if(empty($start_date) || empty($end_date)){
		if($location_id=='All'){
		$queryCmd="select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName,$start as start,$totalEvents as total_events from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and co.countryId=ev.country and e.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ect.boardId=? and e.fromOthers=? and e.status_id is NULL and d.end_date>=DATE(now()) order by d.end_date limit $start,$count";
		}else{
		$queryCmd="select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName,$start as start,$totalEvents as total_events from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId=? and e.fromOthers=? and e.status_id is NULL and d.end_date>=DATE(now()) order by d.end_date limit $start,$count";
		}
		}else{
		if($location_id=='All'){
		$queryCmd="select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName,$start as start,$totalEvents as total_events from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and co.countryId=ev.country and e.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ect.boardId=? and e.fromOthers=? and e.status_id is NULL and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit $start,$count";
		}else{
		$queryCmd="select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName,$start as start,$totalEvents as total_events from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where co.countryId=ev.country and e.event_id=d.event_id and e.venue_id=ev.venue_id and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country in($location_id) or e.assoc_country in($location_id)) and ect.boardId=? and e.fromOthers=? and e.status_id is NULL and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit $start,$count";
		}
		}
		}else{
		if(empty($start_date) || empty($end_date)){
                if($location_id=='All'){
                $queryCmd="select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName,$start as start,$totalEvents as total_events from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and co.countryId=ev.country and e.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country=2 or e.assoc_country=2) and ect.boardId=? and e.status_id is NULL and e.fromOthers=? and d.end_date>=DATE(now()) order by d.end_date limit $start,$count";
                }else{
                $queryCmd="select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName,$start as start,$totalEvents as total_events from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and e.venue_id=ev.venue_id and co.countryId=ev.country and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId=? and e.status_id is NULL and e.fromOthers=? and d.end_date>=DATE(now()) order by d.end_date limit $start,$count";
                }
                }else{
                if($location_id=='All'){
                $queryCmd="select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName,$start as start,$totalEvents as total_events from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where e.event_id=d.event_id and co.countryId=ev.country and e.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and (ev.country=2 or e.assoc_country=2) and ect.boardId=? and e.fromOthers=? and e.status_id is NULL and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit $start,$count";
                }else{
                $queryCmd="select distinct e.event_id,e.event_title,d.start_date,d.end_date,e.fromOthers,co.name as countryName,ci.city_name as cityName,$start as start,$totalEvents as total_events from event e,event_date d,eventCategoryTable ect,event_venue ev,event_venue_mapping evm,countryTable co,countryCityTable ci where co.countryId=ev.country and e.event_id=d.event_id and e.venue_id=ev.venue_id and evm.venue_id=ev.venue_id and evm.event_id=e.event_id and ci.city_id=ev.city and e.event_id=ect.eventId and ev.city in(select city_id from virtualCityMapping where virtualCityId in(1,$location_id)) and (ev.country=2 or e.assoc_country=2) and ect.boardId=? and e.status_id is NULL and e.fromOthers=? and d.end_date>=DATE(now()) and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0)) order by d.end_date limit $start,$count";
                }
                }
		}
		error_log_shiksha("queryCmd is ".$queryCmd);
		$query = $dbHandle->query($queryCmd, array($category_id,$fromOthers));
                $msgArray = array();
                foreach ($query->result() as $row){
                        array_push($msgArray,array(
                                                        array(
								'total_events'=>array($row->total_events,'int'),
								'start'=>array($row->start,'string'),
                                                                'event_id'=>array($row->event_id,'int'),
                                                                'event_title'=>array($row->event_title,'string'),
                                                                'event_url'=>array($this->getEventUrl($row->event_id , $row->event_title, $row->fromOthers),'string'),
                                                                'start_date'=>array($row->start_date,'string'),
                                                                'end_date'=>array($row->end_date,'string'),
                                                                'fromOthers'=>array($row->fromOthers,'int'),
								'country_name'=>array($row->countryName,'string'),
								'city_name'=>array($row->cityName,'string'),
								'start_date_format'=>array(date("jS M,y",strtotime($row->start_date)),'string'),
								'end_date_format'=>array(date("jS M,y",strtotime($row->end_date)),'string'),
                                                                'start_month'=>array(date("M",strtotime($row->start_date)),'string'),
                                                                'start_day'=>array(date("j",strtotime($row->start_date)),'string'),
                                                                'end_month'=>array(date("M",strtotime($row->end_date)),'string'),
                                                                'end_day'=>array(date("j",strtotime($row->end_date)),'string')
                                                        ),'struct')
                        );//close array_push

                }
                $response = array($msgArray,'struct');
                return $this->xmlrpc->send_response($response);
        }

	function eventMigration($request){
			ini_set('max_execution_time', '6000');
			set_time_limit(0);
			$dbHandle = $this->_loadDatabaseHandle('write');
			$queryCmd='update event e,event_date ed set e.fromOthers = 5 where e.event_id=ed.event_id and ed.end_date >= current_date';
	                $query=$dbHandle->query($queryCmd);
			$query = "select distinct cd.course_id from course_details cd,institute_location_table ilt where cd.institute_id=ilt.institute_id and cd.date_course_comencement >= current_date and cd.status='live' ";
			error_log_shiksha("query is as ".$query);
                        $instituteLocation = $dbHandle->query($query);
			$msgArray1 = array();
			foreach($instituteLocation->result() as $row){
	                array_push($msgArray1,array(
                                                                'course_id'=>array($row->course_id,'int')
                                                        )
                                                        );
			}
			foreach($msgArray1 as $msgArray1Temp){
			$courseId=$msgArray1Temp['course_id'][0];
			$queryCmd = "select distinct cd.course_id,cd.courseTitle,cd.date_course_comencement,ilt.city_id,ilt.country_id,i.institute_name,(select group_concat(category_id) from listing_category_table where listing_type='course' and listing_type_id= ? and status='live') as course_categories from course_details cd,institute_location_table ilt,institute i where cd.institute_id=ilt.institute_id and cd.institute_id=i.institute_id and cd.date_course_comencement >= current_date and cd.status='live' and i.status='live' and ilt.status='live' and cd.course_id=?";
			error_log_shiksha("queryCmd is as ".$queryCmd);
                        $query = $dbHandle->query($queryCmd, array($courseId,$courseId));
			$msgArray=array();
			foreach ($query->result() as $row){
			array_push($msgArray,array(
								'course_id'=>array($row->course_id,'int'),
								'course_title'=>array($row->courseTitle,'string'),
								'date_course_comencement'=>array($row->date_course_comencement,'string'),
								'country_id'=>array($row->country_id,'int'),
								'city_id'=>array($row->city_id,'int'),
								'institute_name'=>array($row->institute_name,'string'),
								'course_category'=>array($row->course_categories,'string')
							)
							);
				}
			$this->load->library('message_board_client');
			$msgbrdClient = new Message_board_client();
			$topicDescription = "You can discuss on this event below";
			$requestIp = S_REMOTE_ADDR;
			foreach($msgArray as $temp){
			$topicResult = $msgbrdClient->addTopic(1,1,$topicDescription,$temp['course_category'],$requestIp,'event');
			$threadId = $topicResult['ThreadID'];
			$title="Course comencement for ".$temp['course_title'][0]." at ".$temp['institute_name'][0];
			$fromOthers=1;
			$dataArray =array(
                        'city'=>$temp['city_id'][0],
                        'country'=>$temp['country_id'][0]
                );

                // First add event venue
                $queryCmd = $dbHandle->insert_string('event_venue',$dataArray);
		error_log_shiksha("query for inserting event_venue".$queryCmd);
                $query = $dbHandle->query($queryCmd);
                $venueId=$dbHandle->insert_id();
		$dataArray =array(
                        'event_title'=>$title,
                        'venue_id'=>$venueId,
                        'user_id'=>1,
                        'fromOthers'=>$fromOthers,
			'threadId'=>$threadId,
			'listing_type_id'=>$courseId,
			'listingType'=>'course'
                );
		// add event details
                $queryCmd = $dbHandle->insert_string('event',$dataArray);
                $query = $dbHandle->query($queryCmd);
                $eventId=$dbHandle->insert_id();

		// add event dates
		 $dataArray = array(
                        'event_id'=>$eventId,
                        'start_date'=> date('Y-m-d',strtotime($temp['date_course_comencement'][0])),
                        'end_date'=>date('Y-m-d',strtotime($temp['date_course_comencement'][0]))
                );

                $queryCmd = $dbHandle->insert_string('event_date',$dataArray);
                error_log_shiksha( 'addEvent insert event date query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$categoryArray = array();
		array_push($categoryArray,"1");
		$tempArray = explode(",", $temp['course_category'][0]);
                foreach($tempArray as $tempo)
                {
                        if($tempo != '')
                                array_push($categoryArray,$tempo);
                }
		$queryCmd = 'insert eventCategoryTable values ';
		$commaCount=0;
                foreach ($categoryArray as $boardId) {
                        if($commaCount>0){
                                $queryCmd.=",";
                        }
                        $queryCmd.="($eventId,$boardId)";

                        if($boardId>1){
                                $queryCmd.=",($eventId,(select parentId from categoryBoardTable where boardId= ".$dbHandle->escape($boardId)."))";
                        }
                        $commaCount++;
                }
                $queryCmd.= " on duplicate key update eventCategoryTable.eventId = ".$dbHandle->escape($eventId);
                error_log_shiksha( 'addEvent category query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$dataArray = array(
                        'event_id'=>$eventId,
                        'venue_id'=>$venueId
                );
                //update event venue mapping
                $queryCmd = $dbHandle->insert_string('event_venue_mapping',$dataArray);
                error_log_shiksha( 'addEvent insert event venue mapping query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
			}
		}




		$query = "select distinct cd.course_id from course_details cd,institute_location_table ilt where cd.institute_id=ilt.institute_id and cd.date_form_submission >= current_date and cd.status='live' ";
			error_log_shiksha("query is as ".$query);
                        $instituteLocation = $dbHandle->query($query);
			$msgArray1 = array();
			foreach($instituteLocation->result() as $row){
	                array_push($msgArray1,array(
                                                                'course_id'=>array($row->course_id,'int')
                                                        )
                                                        );
			}
			foreach($msgArray1 as $msgArray1Temp){
			$courseId=$msgArray1Temp['course_id'][0];
			$queryCmd = "select distinct cd.course_id,cd.courseTitle,cd.date_form_submission,ilt.city_id,ilt.country_id,i.institute_name,(select group_concat(category_id) from listing_category_table where listing_type='course' and listing_type_id= ? and status='live') as course_categories from course_details cd,institute_location_table ilt,institute i where cd.institute_id=ilt.institute_id and cd.institute_id=i.institute_id and cd.date_form_submission >= current_date and cd.status='live' and i.status='live' and ilt.status='live' and cd.course_id= ?";
			error_log_shiksha("queryCmd is as ".$queryCmd);
                        $query = $dbHandle->query($queryCmd, array($courseId,$courseId));
			$msgArray=array();
			foreach ($query->result() as $row){
			array_push($msgArray,array(
								'course_id'=>array($row->course_id,'int'),
								'course_title'=>array($row->courseTitle,'string'),
								'date_form_submission'=>array($row->date_form_submission,'string'),
								'country_id'=>array($row->country_id,'int'),
								'city_id'=>array($row->city_id,'int'),
								'institute_name'=>array($row->institute_name,'string'),
								'course_category'=>array($row->course_categories,'string')
							)
							);
				}
			$this->load->library('message_board_client');
			$msgbrdClient = new Message_board_client();
			$topicDescription = "You can discuss on this event below";
			$requestIp = S_REMOTE_ADDR;
			foreach($msgArray as $temp){
			$topicResult = $msgbrdClient->addTopic(1,1,$topicDescription,$temp['course_category'],$requestIp,'event');
			$threadId = $topicResult['ThreadID'];
			$title="Application submission deadline for ".$temp['course_title'][0]." at ".$temp['institute_name'][0];
			$fromOthers=0;
			$dataArray =array(
                        'city'=>$temp['city_id'][0],
                        'country'=>$temp['country_id'][0]
                );

                // First add event venue
                $queryCmd = $dbHandle->insert_string('event_venue',$dataArray);
		error_log_shiksha("query for inserting event_venue".$queryCmd);
                $query = $dbHandle->query($queryCmd);
                $venueId=$dbHandle->insert_id();
		$dataArray =array(
                        'event_title'=>$title,
                        'venue_id'=>$venueId,
                        'user_id'=>1,
                        'fromOthers'=>$fromOthers,
			'threadId'=>$threadId,
			'listing_type_id'=>$courseId,
			'listingType'=>'course'
                );
		// add event details
                $queryCmd = $dbHandle->insert_string('event',$dataArray);
                $query = $dbHandle->query($queryCmd);
                $eventId=$dbHandle->insert_id();

		// add event dates
		 $dataArray = array(
                        'event_id'=>$eventId,
                        'start_date'=> date('Y-m-d',strtotime($temp['date_form_submission'][0])),
                        'end_date'=>date('Y-m-d',strtotime($temp['date_form_submission'][0]))
                );

                $queryCmd = $dbHandle->insert_string('event_date',$dataArray);
                error_log_shiksha( 'addEvent insert event date query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$categoryArray = array();
		array_push($categoryArray,"1");
		$tempArray = explode(",", $temp['course_category'][0]);
                foreach($tempArray as $tempo)
                {
                        if($tempo != '')
                                array_push($categoryArray,$tempo);
                }
		$queryCmd = 'insert eventCategoryTable values ';
		$commaCount=0;
                foreach ($categoryArray as $boardId) {
                        if($commaCount>0){
                                $queryCmd.=",";
                        }
                        $queryCmd.="($eventId,$boardId)";

                        if($boardId>1){
                                $queryCmd.=",($eventId,(select parentId from categoryBoardTable where boardId= ".$dbHandle->escape($boardId)."))";
                        }
                        $commaCount++;
                }
                $queryCmd.= " on duplicate key update eventCategoryTable.eventId = ".$dbHandle->escape($eventId);
                error_log_shiksha( 'addEvent category query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$dataArray = array(
                        'event_id'=>$eventId,
                        'venue_id'=>$venueId
                );
                //update event venue mapping
                $queryCmd = $dbHandle->insert_string('event_venue_mapping',$dataArray);
                error_log_shiksha( 'addEvent insert event venue mapping query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
			}
		}




			$query = "select distinct cd.course_id from course_details cd,institute_location_table ilt where cd.institute_id=ilt.institute_id and cd.date_result_declaration >= current_date and cd.status='live' ";
			error_log_shiksha("query is as ".$query);
                        $instituteLocation = $dbHandle->query($query);
			$msgArray1 = array();
			foreach($instituteLocation->result() as $row){
	                array_push($msgArray1,array(
                                                                'course_id'=>array($row->course_id,'int')
                                                        )
                                                        );
			}
			foreach($msgArray1 as $msgArray1Temp){
			$courseId=$msgArray1Temp['course_id'][0];
			$queryCmd = "select distinct cd.course_id,cd.courseTitle,cd.date_result_declaration,ilt.city_id,ilt.country_id,i.institute_name,(select group_concat(category_id) from listing_category_table where listing_type='course' and listing_type_id= ? and status='live') as course_categories from course_details cd,institute_location_table ilt,institute i where cd.institute_id=ilt.institute_id and cd.institute_id=i.institute_id and cd.date_result_declaration >= current_date and cd.status='live' and i.status='live' and ilt.status='live' and cd.course_id= ?";
			error_log_shiksha("queryCmd is as ".$queryCmd);
                        $query = $dbHandle->query($queryCmd,array($courseId,$courseId));
			$msgArray=array();
			foreach ($query->result() as $row){
			array_push($msgArray,array(
								'course_id'=>array($row->course_id,'int'),
								'course_title'=>array($row->courseTitle,'string'),
								'date_result_declaration'=>array($row->date_result_declaration,'string'),
								'country_id'=>array($row->country_id,'int'),
								'city_id'=>array($row->city_id,'int'),
								'institute_name'=>array($row->institute_name,'string'),
								'course_category'=>array($row->course_categories,'string')
							)
							);
				}
			$this->load->library('message_board_client');
			$msgbrdClient = new Message_board_client();
			$topicDescription = "You can discuss on this event below";
			$requestIp = S_REMOTE_ADDR;
			foreach($msgArray as $temp){
			$topicResult = $msgbrdClient->addTopic(1,1,$topicDescription,$temp['course_category'],$requestIp,'event');
			$threadId = $topicResult['ThreadID'];
			$title="Admission result for ".$temp['course_title'][0]." at ".$temp['institute_name'][0];
			$fromOthers=2;
			$dataArray =array(
                        'city'=>$temp['city_id'][0],
                        'country'=>$temp['country_id'][0]
                );

                // First add event venue
                $queryCmd = $dbHandle->insert_string('event_venue',$dataArray);
		error_log_shiksha("query for inserting event_venue".$queryCmd);
                $query = $dbHandle->query($queryCmd);
                $venueId=$dbHandle->insert_id();
		$dataArray =array(
                        'event_title'=>$title,
                        'venue_id'=>$venueId,
                        'user_id'=>1,
                        'fromOthers'=>$fromOthers,
			'threadId'=>$threadId,
			'listing_type_id'=>$courseId,
			'listingType'=>'course'
                );
		// add event details
                $queryCmd = $dbHandle->insert_string('event',$dataArray);
                $query = $dbHandle->query($queryCmd);
                $eventId=$dbHandle->insert_id();

		// add event dates
		 $dataArray = array(
                        'event_id'=>$eventId,
                        'start_date'=> date('Y-m-d',strtotime($temp['date_result_declaration'][0])),
                        'end_date'=>date('Y-m-d',strtotime($temp['date_result_declaration'][0]))
                );

                $queryCmd = $dbHandle->insert_string('event_date',$dataArray);
                error_log_shiksha( 'addEvent insert event date query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$categoryArray = array();
		array_push($categoryArray,"1");
		$tempArray = explode(",", $temp['course_category'][0]);
                foreach($tempArray as $tempo)
                {
                        if($tempo != '')
                                array_push($categoryArray,$tempo);
                }
		$queryCmd = 'insert eventCategoryTable values ';
		$commaCount=0;
                foreach ($categoryArray as $boardId) {
                        if($commaCount>0){
                                $queryCmd.=",";
                        }
                        $queryCmd.="($eventId,$boardId)";

                        if($boardId>1){
                                $queryCmd.=",($eventId,(select parentId from categoryBoardTable where boardId= ".$dbHandle->escape($boardId)."))";
                        }
                        $commaCount++;
                }
                $queryCmd.= " on duplicate key update eventCategoryTable.eventId = ".$dbHandle->escape($eventId);
                error_log_shiksha( 'addEvent category query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);

		$dataArray = array(
                        'event_id'=>$eventId,
                        'venue_id'=>$venueId
                );
                //update event venue mapping
                $queryCmd = $dbHandle->insert_string('event_venue_mapping',$dataArray);
                error_log_shiksha( 'addEvent insert event venue mapping query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
			}
		}
	}

	function subscribeEvents($request){
                $parameters = $request->output_parameters();
                $appId=$parameters['0'];
		$user_id=$parameters['1'];
                $event_id=$parameters['2'];
                $subscriptionType=$parameters['3'];
		$subscriptionTitle=$parameters['4'];
		$mobile_number=$parameters['5'];
		$email=$parameters['6'];
		$privacySettings=$parameters['7'];
		$category=$parameters['8'];
		$country=$parameters['9'];
		$fromOthers=$parameters['10'];
		$locationId=$parameters['11'];
                //connect DB
                $dbHandle = $this->_loadDatabaseHandle('write');
		$subscription_id='-1';
		if($country=='india'){
		if($locationId=='All'){
		if($subscriptionType=='event'){
		$queryCmd = "select subscription_id from event_subscription where event_id=$event_id and user_id= ? and status=1 and subscription_type='$subscriptionType'";
		}else if($subscriptionType=='category'){
		$queryCmd = "select subscription_id from event_subscription where user_id= ? and status=1 and subscription_type='$subscriptionType' and category_id=$category and fromOthers=$fromOthers and city_id=1";
		}else if($subscriptionType=='studyabroad'){
		$queryCmd = "select subscription_id from event_subscription where user_id= ? and status=1 and subscription_type='$subscriptionType' and category_id=$category and country_id in($locationId) and fromOthers=$fromOthers";
		}else if($subscriptionType=='course'){
		$queryCmd = "select subscription_id from event_subscription where event_id=$event_id and user_id= ? and status=1 and subscription_type='$subscriptionType'";
		}else if($subscriptionType=='institute'){
		$queryCmd = "select subscription_id from event_subscription where event_id=$event_id and user_id= ? and status=1 and subscription_type='$subscriptionType'";
		}else if($subscriptionType=='homepage'){
		$queryCmd = "select subscription_id from event_subscription where fromOthers=$fromOthers and category_id=$category and status=1 and subscription_type='$subscriptionType' and user_id= ? and city_id=1";
		}else if($subscriptionType=='viewall'){
                $queryCmd = "select subscription_id from event_subscription where fromOthers=$fromOthers and category_id=$category and status=1 and subscription_type='$subscriptionType' and user_id= ? and city_id=1";
                }
		}else{
		if($subscriptionType=='event'){
                $queryCmd = "select subscription_id from event_subscription where event_id=$event_id and user_id= ? and status=1 and subscription_type='$subscriptionType'";
                }else if($subscriptionType=='category'){
                $queryCmd = "select subscription_id from event_subscription where user_id= ? and status=1 and subscription_type='$subscriptionType' and category_id=$category and fromOthers=$fromOthers and city_id=$locationId";
                }else if($subscriptionType=='studyabroad'){
                $queryCmd = "select subscription_id from event_subscription where user_id= ? and status=1 and subscription_type='$subscriptionType' and category_id=$category and country_id in($locationId) and fromOthers=$fromOthers";
                }else if($subscriptionType=='course'){
                $queryCmd = "select subscription_id from event_subscription where event_id=$event_id and user_id= ? and status=1 and subscription_type='$subscriptionType'";
                }else if($subscriptionType=='institute'){
                $queryCmd = "select subscription_id from event_subscription where event_id=$event_id and user_id= ? and status=1 and subscription_type='$subscriptionType'";
                }else if($subscriptionType=='homepage'){
                $queryCmd = "select subscription_id from event_subscription where fromOthers=$fromOthers and category_id=$category and status=1 and subscription_type='$subscriptionType' and user_id= ? and city_id=$locationId";
                }else if($subscriptionType=='viewall'){
                $queryCmd = "select subscription_id from event_subscription where fromOthers=$fromOthers and category_id=$category and status=1 and subscription_type='$subscriptionType' and user_id= ? and city_id=$locationId";
                }
		}
		}else{
		if($subscriptionType=='event'){
                $queryCmd = "select subscription_id from event_subscription where event_id=$event_id and user_id= ? and status=1 and subscription_type='$subscriptionType'";
                }else if($subscriptionType=='category'){
                $queryCmd = "select subscription_id from event_subscription where user_id= ? and status=1 and subscription_type='$subscriptionType' and category_id=$category and fromOthers=$fromOthers and country_id in($locationId)";
                }else if($subscriptionType=='studyabroad'){
                $queryCmd = "select subscription_id from event_subscription where user_id= ? and status=1 and subscription_type='$subscriptionType' and category_id=$category and country_id in($country) and fromOthers=$fromOthers and country_id in($locationId)";
                }else if($subscriptionType=='course'){
                $queryCmd = "select subscription_id from event_subscription where event_id=$event_id and user_id= ? and status=1 and subscription_type='$subscriptionType'";
                }else if($subscriptionType=='institute'){
                $queryCmd = "select subscription_id from event_subscription where event_id=$event_id and user_id= ? and status=1 and subscription_type='$subscriptionType'";
                }else if($subscriptionType=='homepage'){
                $queryCmd = "select subscription_id from event_subscription where fromOthers=$fromOthers and category_id=$category and status=1 and subscription_type='$subscriptionType' and user_id= ? and country_id in($locationId)";
                }else if($subscriptionType=='viewall'){
                $queryCmd = "select subscription_id from event_subscription where fromOthers=$fromOthers and category_id=$category and status=1 and subscription_type='$subscriptionType' and user_id= ? and country_id in($locationId)";
                }
		}
                error_log_shiksha("hhh queryCmd is as ".$queryCmd);
                $query = $dbHandle->query($queryCmd,array($user_id,$user_id,$user_id,$user_id,$user_id,$user_id,$user_id));
                foreach($query->result_array() as $queryRow){
                $subscription_id = $queryRow['subscription_id'];
		}
		if($subscription_id!='-1'){
		}else{
		if($country=='india'){
		if($locationId=='All'){
		$locationId=1;
		}
		$dataArray = array(
                        'event_id'=>$event_id,
			'user_id'=>$user_id,
			'subscription_title'=>$subscriptionTitle,
			'status'=>1,
                        'subscription_type'=>$subscriptionType,
			'mobile_number'=>$mobile_number,
			'email'=>$email,
			'privacy_settings'=>$privacySettings,
			'category_id'=>$category,
			'fromOthers'=>$fromOthers,
			'country_id'=>2,
			'city_id'=>$locationId
                );
		//insert into eventSubscription
                $queryCmd = $dbHandle->insert_string('event_subscription',$dataArray);
                error_log_shiksha( 'addEvent insert event venue mapping query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
                $subscriptionId=$dbHandle->insert_id();
		}else{
		$countryArray=array();
		$tempArray=array();
		$tempArray = explode(",", $locationId);
		foreach($tempArray as $tempo)
                {
                        if($tempo != '')
                                array_push($countryArray,$tempo);
                }
		foreach($countryArray as $temp){
		$dataArray = array(
                        'event_id'=>$event_id,
                        'user_id'=>$user_id,
                        'subscription_title'=>$subscriptionTitle,
                        'status'=>1,
                        'subscription_type'=>$subscriptionType,
                        'mobile_number'=>$mobile_number,
                        'email'=>$email,
                        'privacy_settings'=>$privacySettings,
                        'category_id'=>$category,
                        'fromOthers'=>$fromOthers,
                        'country_id'=>$temp,
			'city_id'=>1
                );
                //insert into eventSubscription
                $queryCmd = $dbHandle->insert_string('event_subscription',$dataArray);
                error_log_shiksha( 'addEvent insert event venue mapping query cmd is ' . $queryCmd,'events');
                $query = $dbHandle->query($queryCmd);
		$subscriptionId=$dbHandle->insert_id();
		}
		}
		}
		$response=$subscription_id;
		return $this->xmlrpc->send_response($response);
		}

	function getAllSubscriptionsEmail($request){
		$parameters = $request->output_parameters();
                $appId=$parameters['0'];
		$this->load->library('Alerts_client');
		$alerts_client = new Alerts_client();
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$start_date=date("Y-m-d",time()+(86400*2));
		$end_date=date("Y-m-d",time()+(86400*9));
		$data=array();
		$query = "select distinct es.user_id, t.firstname from event_subscription es, tuser t where t.userid=es.user_id";
                        $userIdList = $dbHandle->query($query);
                        $msgArray1 = array();
                        foreach($userIdList->result() as $row){
                        array_push($msgArray1,array(
                                                        'user_id'=>array($row->user_id,'int'),
							'user_name'=>array($row->firstname,'string')
                                                        ));
                        }
		foreach($msgArray1 as $msgArray1Temp){
                        $userId=$msgArray1Temp['user_id'][0];
			$userName=$msgArray1Temp['user_name'][0];
                        $queryCmd = "select distinct subscription_id,user_id,event_id,subscription_title,status,subscription_type,course_id,institute_id,country_id,city_id,category_id,fromOthers,email,privacy_settings,mobile_number from event_subscription where user_id= ?";
                        $query = $dbHandle->query($queryCmd, array($userId));
                        $msgArray=array();
                        foreach ($query->result() as $row){
                        array_push($msgArray,array(
                                                  	'subscription_id'=>array($row->subscription_id,'int'),
							'user_id'=>array($row->user_id,'int'),
							'event_id'=>array($row->event_id,'int'),
							'subscription_title'=>array($row->subscription_title,'string'),
							'status'=>array($row->status,'int'),
							'subscription_type'=>array($row->subscription_type,'string'),
							'course_id'=>array($row->course_id,'int'),
							'institute_id'=>array($row->institute_id,'int'),
							'country_id'=>array($row->country_id,'int'),
							'city_id'=>array($row->city_id,'int'),
							'category_id'=>array($row->category_id,'int'),
							'fromOthers'=>array($row->fromOthers,'int'),
							'email'=>array($row->email,'string'),
							'privacy_settings'=>array($row->privacy_settings,'int'),
							'mobile_number'=>array($row->mobile_number,'string')
                                                        )
                	                    );
                                }
		$eventArray=array();
		foreach($msgArray as $temp){
		$user_id_temp=$temp['user_id'][0];
		$event_id_temp=$temp['event_id'][0];
		$fromOthers_temp=$temp['fromOthers'][0];
		$category_id_temp=$temp['category_id'][0];
		$course_id_temp=$temp['course_id'][0];
		$institute_id_temp=$temp['institute_id'][0];
		$email_temp=$temp['email'][0];
		$country_temp=$temp['country_id'][0];
		$city_temp=$temp['city_id'][0];
		$noOfSubscriptions=0;
		if($country_temp==2){
		if($city_temp!=1){
		if($temp['subscription_type'][0]=='category'){
		$query="select e.event_id from event e, eventCategoryTable ect, event_date d, event_venue ev, countryCityTable ci where e.event_id=ect.eventId and e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.city=ci.city_id and ci.city_id=$city_temp and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}else if($temp['subscription_type'][0]=='course'){
		$query="select distinct e.event_id from event e, event_date d where e.event_id=d.event_id and e.listingType='course' and e.listing_type_id=$event_id_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}else if($temp['subscription_type'][0]=='studyabroad'){
		$query="select distinct e.event_id from event e, eventCategoryTable ect, event_date d, event_venue ev, countryTable co where e.event_id=ect.eventId and e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and co.countryId= in($country_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}else if($temp['subscription_type'][0]=='institute'){
		$query="select distinct e.event_id from event e, event_date d where e.event_id=d.event_id and e.listingType='course' and e.listing_type_id in (select course_id from institute_courses_mapping_table where institute_id=$event_id_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}else if($temp['subscription_type'][0]=='homepage'){
		$query="select distinct e.event_id from event e, event_date d, eventCategoryTable ect, event_venue ev, countryCityTable ci  where e.event_id=d.event_id and e.event_id=ect.eventId and e.venue_id=ev.venue_id and ev.city=ci.city_id and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and ci.city_id=$city_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}else if($temp['subscription_type'][0]=='viewall'){
		$query="select distinct e.event_id from event e, event_date d, eventCategoryTable ect, event_venue ev, countryCityTable ci where e.event_id=d.event_id and e.event_id=ect.eventId and e.venue_id=ev.venue_id and ev.city=ci.city_id and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and ci.city_id=$city_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}else{
		$query = "select distinct e.event_id from event e, event_date d where e.event_id=d.event_id and e.event_id=$event_id_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}
		}else{
		if($temp['subscription_type'][0]=='category'){
                $query="select e.event_id from event e, eventCategoryTable ect, event_date d, event_venue ev, countryTable co where e.event_id=ect.eventId and e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and e.status_id is NULL and ev.country=2 and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='course'){
                $query="select distinct e.event_id from event e, event_date d, event_venue ev, countryTable co where e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.listingType='course' and e.listing_type_id=$event_id_temp and e.status_id is NULL and ev.country=2 and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='studyabroad'){
                $query="select distinct e.event_id from event e, eventCategoryTable ect, event_date d, event_venue ev, countryTable co where e.event_id=ect.eventId and e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and co.countryId in($country_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='institute'){
                $query="select distinct e.event_id from event e, event_date d, event_venue ev, countryTable co where e.event_id=d.event_id and ev.country=co.countryId and e.listingType='course' and e.listing_type_id in (select course_id from institute_courses_mapping_table where institute_id=$event_id_temp) and e.status_id is NULL and ev.country=2 and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='homepage'){
                $query="select distinct e.event_id from event e, event_date d, eventCategoryTable ect, event_venue ev, countryTable co where e.event_id=d.event_id and e.event_id=ect.eventId and ev.country=co.countryId and e.venue_id=ev.venue_id and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and e.status_id is NULL and ev.country=2 and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='viewall'){
                $query="select distinct e.event_id from event e, event_date d, eventCategoryTable ect, event_venue ev, countryTable co where e.event_id=d.event_id and e.event_id=ect.eventId and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and e.status_id is NULL and ev.country=2 and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else{
                $query = "select distinct e.event_id from event e, event_date d, event_venue ev, countryTable co where e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.event_id=$event_id_temp and e.status_id is NULL and ev.country=2 and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }
		}
		}else{
		if($temp['subscription_type'][0]=='category'){
                $query="select e.event_id from event e, eventCategoryTable ect, event_date d, event_venue ev, countryTable co where e.event_id=ect.eventId and e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and co.countryId in($country_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='course'){
                $query="select distinct e.event_id from event e, event_date d where e.event_id=d.event_id and e.listingType='course' and e.listing_type_id=$event_id_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='studyabroad'){
                $query="select distinct e.event_id from event e, eventCategoryTable ect, event_date d, event_venue ev, countryTable co where e.event_id=ect.eventId and e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and co.countryId in($country_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='institute'){
                $query="select distinct e.event_id from event e, event_date d where e.event_id=d.event_id and e.listingType='course' and e.listing_type_id in (select course_id from institute_courses_mapping_table where institute_id=$event_id_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='homepage'){
                $query="select distinct e.event_id from event e, event_date d, eventCategoryTable ect, event_venue ev, countryTable co where e.event_id=d.event_id and e.event_id=ect.eventId and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and co.countryId in($country_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='viewall'){
                $query="select distinct e.event_id from event e, event_date d, eventCategoryTable ect, event_venue ev, countryTable co where e.event_id=d.event_id and e.event_id=ect.eventId and e.venue_id=ev.venue_id and ev.country in($country_temp) and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else{
                $query = "select distinct e.event_id from event e, event_date d where e.event_id=d.event_id and e.event_id=$event_id_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }
		}

		error_log_shiksha("query is defined as ".$query);
                $eventIdList = $dbHandle->query($query);
                foreach($eventIdList->result() as $row){
                array_push($eventArray,$row->event_id);
                }
		}
		$eventArray=array_unique($eventArray);
		$noOfSubscriptions=count($eventArray);
		$data['noOfSubscriptions'] = $noOfSubscriptions;
		$eventDetailsArray=array();
		foreach($eventArray as $eventIdNext){
		$queryCmd = "select e.event_id, e.event_title, ed.start_date, ed.end_date, ci.city_name,co.name from event e, event_date ed, event_venue ev, countryTable co,countryCityTable ci where e.event_id=ed.event_id and e.venue_id=ev.venue_id and ci.city_id=ev.city and co.countryId=ev.country and e.event_id= ?";
		$query = $dbHandle->query($queryCmd, array($eventIdNext));
		foreach ($query->result() as $row){
                        array_push($eventDetailsArray,array(
                                                                'event_id'=>$row->event_id,
                                                                'event_title'=>$row->event_title,
                                                                'start_date'=>$row->start_date,
                                                                'end_date'=>$row->end_date,
								'city_name'=>$row->city_name,
								'country_name'=>$row->name
                                                        )
                        );//close array_push
                }
		}
		if($temp['privacy_settings'][0]==3 || $temp['privacy_settings'][0]==1){
		$subject =$noOfSubscriptions." important dates awaiting your attention.";
		$data['userName']=$userName;
		$data['usernameemail'] = $email_temp;
		$data['userpasswordemail'] = "shiksha";
		$this->load->library('MY_sort_associative_array');
		$sorter = new MY_sort_associative_array;
		$sorter->numeric = true;
		$eventDetailsArray=$sorter->sort_associative_array($eventDetailsArray, 'start_date');
		$data['eventDetailsArray']=$eventDetailsArray;
		$content = $this->load->view("search/searchMail",array('contentArray'=>$data,'type' =>'subscribeEvents'),true);
		$data['content'] = $content;
        /*
		if($noOfSubscriptions!=0){
                $response=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$data['usernameemail'],$subject,$content,$contentType="html");
		}
        */
		}
/*
		if($temp['privacy_settings'][0]==3 || $temp['privacy_settings'][0]==2){
		foreach($eventDetailsArray as $tempArray){
		if($start_date>=date("Y-m-d",strtotime($tempArray['start_date'])) && $start_date<=date("Y-m-d",strtotime($tempArray['end_date']))){
		$contentOfSms = "Shiksha Event Reminder: ".date("Y-m-d",strtotime($tempArray['start_date']))." to ".date("Y-m-d",strtotime($tempArray['end_date']))." - ".$tempArray['event_title'];
                $responseOfSms=$alerts_client->addSmsQueueRecord("12",$temp['mobile_number'][0],$contentOfSms,"$user_id_temp");
		}
		}
		}
*/
		}
                return $this->xmlrpc->send_response($response);
	}

		function getAllSubscriptionsSMS($request){
		$parameters = $request->output_parameters();
                $appId=$parameters['0'];
		$this->load->library('Alerts_client');
		$alerts_client = new Alerts_client();
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$start_date=date("Y-m-d",time()+(86400*2));
		$end_date=date("Y-m-d",time()+(86400*9));
		$data=array();
		$query = "select distinct es.user_id, t.firstname from event_subscription es, tuser t where t.userid=es.user_id";
                        $userIdList = $dbHandle->query($query);
                        $msgArray1 = array();
                        foreach($userIdList->result() as $row){
                        array_push($msgArray1,array(
                                                        'user_id'=>array($row->user_id,'int'),
							'user_name'=>array($row->firstname,'string')
                                                        ));
                        }
		foreach($msgArray1 as $msgArray1Temp){
                        $userId=$msgArray1Temp['user_id'][0];
			$userName=$msgArray1Temp['user_name'][0];
                        $queryCmd = "select distinct subscription_id,user_id,event_id,subscription_title,status,subscription_type,course_id,institute_id,country_id,city_id,category_id,fromOthers,email,privacy_settings,mobile_number from event_subscription where user_id= ?";
                        $query = $dbHandle->query($queryCmd,array($userId));
                        $msgArray=array();
                        foreach ($query->result() as $row){
                        array_push($msgArray,array(
                                                  	'subscription_id'=>array($row->subscription_id,'int'),
							'user_id'=>array($row->user_id,'int'),
							'event_id'=>array($row->event_id,'int'),
							'subscription_title'=>array($row->subscription_title,'string'),
							'status'=>array($row->status,'int'),
							'subscription_type'=>array($row->subscription_type,'string'),
							'course_id'=>array($row->course_id,'int'),
							'institute_id'=>array($row->institute_id,'int'),
							'country_id'=>array($row->country_id,'int'),
							'city_id'=>array($row->city_id,'int'),
							'category_id'=>array($row->category_id,'int'),
							'fromOthers'=>array($row->fromOthers,'int'),
							'email'=>array($row->email,'string'),
							'privacy_settings'=>array($row->privacy_settings,'int'),
							'mobile_number'=>array($row->mobile_number,'string')
                                                        )
                	                    );
                                }
		$eventArray=array();
		foreach($msgArray as $temp){
		$user_id_temp=$temp['user_id'][0];
		$event_id_temp=$temp['event_id'][0];
		$fromOthers_temp=$temp['fromOthers'][0];
		$category_id_temp=$temp['category_id'][0];
		$course_id_temp=$temp['course_id'][0];
		$institute_id_temp=$temp['institute_id'][0];
		$email_temp=$temp['email'][0];
		$country_temp=$temp['country_id'][0];
		$city_temp=$temp['city_id'][0];
		$noOfSubscriptions=0;
		if($country_temp==2){
		if($city_temp!=1){
		if($temp['subscription_type'][0]=='category'){
		$query="select e.event_id from event e, eventCategoryTable ect, event_date d, event_venue ev, countryCityTable ci where e.event_id=ect.eventId and e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.city=ci.city_id and ci.city_id=$city_temp and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}else if($temp['subscription_type'][0]=='course'){
		$query="select distinct e.event_id from event e, event_date d where e.event_id=d.event_id and e.listingType='course' and e.listing_type_id=$event_id_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}else if($temp['subscription_type'][0]=='studyabroad'){
		$query="select distinct e.event_id from event e, eventCategoryTable ect, event_date d, event_venue ev, countryTable co where e.event_id=ect.eventId and e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and co.countryId= in($country_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}else if($temp['subscription_type'][0]=='institute'){
		$query="select distinct e.event_id from event e, event_date d where e.event_id=d.event_id and e.listingType='course' and e.listing_type_id in (select course_id from institute_courses_mapping_table where institute_id=$event_id_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}else if($temp['subscription_type'][0]=='homepage'){
		$query="select distinct e.event_id from event e, event_date d, eventCategoryTable ect, event_venue ev, countryCityTable ci  where e.event_id=d.event_id and e.event_id=ect.eventId and e.venue_id=ev.venue_id and ev.city=ci.city_id and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and ci.city_id=$city_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}else if($temp['subscription_type'][0]=='viewall'){
		$query="select distinct e.event_id from event e, event_date d, eventCategoryTable ect, event_venue ev, countryCityTable ci where e.event_id=d.event_id and e.event_id=ect.eventId and e.venue_id=ev.venue_id and ev.city=ci.city_id and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and ci.city_id=$city_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}else{
		$query = "select distinct e.event_id from event e, event_date d where e.event_id=d.event_id and e.event_id=$event_id_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
		}
		}else{
		if($temp['subscription_type'][0]=='category'){
                $query="select e.event_id from event e, eventCategoryTable ect, event_date d, event_venue ev, countryTable co where e.event_id=ect.eventId and e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and e.status_id is NULL and ev.country=2 and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='course'){
                $query="select distinct e.event_id from event e, event_date d, event_venue ev, countryTable co where e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.listingType='course' and e.listing_type_id=$event_id_temp and e.status_id is NULL and ev.country=2 and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='studyabroad'){
                $query="select distinct e.event_id from event e, eventCategoryTable ect, event_date d, event_venue ev, countryTable co where e.event_id=ect.eventId and e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and co.countryId in($country_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='institute'){
                $query="select distinct e.event_id from event e, event_date d, event_venue ev, countryTable co where e.event_id=d.event_id and ev.country=co.countryId and e.listingType='course' and e.listing_type_id in (select course_id from institute_courses_mapping_table where institute_id=$event_id_temp) and e.status_id is NULL and ev.country=2 and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='homepage'){
                $query="select distinct e.event_id from event e, event_date d, eventCategoryTable ect, event_venue ev, countryTable co where e.event_id=d.event_id and e.event_id=ect.eventId and ev.country=co.countryId and e.venue_id=ev.venue_id and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and e.status_id is NULL and ev.country=2 and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='viewall'){
                $query="select distinct e.event_id from event e, event_date d, eventCategoryTable ect, event_venue ev, countryTable co where e.event_id=d.event_id and e.event_id=ect.eventId and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and e.status_id is NULL and ev.country=2 and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else{
                $query = "select distinct e.event_id from event e, event_date d, event_venue ev, countryTable co where e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.event_id=$event_id_temp and e.status_id is NULL and ev.country=2 and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }
		}
		}else{
		if($temp['subscription_type'][0]=='category'){
                $query="select e.event_id from event e, eventCategoryTable ect, event_date d, event_venue ev, countryTable co where e.event_id=ect.eventId and e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and co.countryId in($country_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='course'){
                $query="select distinct e.event_id from event e, event_date d where e.event_id=d.event_id and e.listingType='course' and e.listing_type_id=$event_id_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='studyabroad'){
                $query="select distinct e.event_id from event e, eventCategoryTable ect, event_date d, event_venue ev, countryTable co where e.event_id=ect.eventId and e.event_id=d.event_id and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and co.countryId in($country_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='institute'){
                $query="select distinct e.event_id from event e, event_date d where e.event_id=d.event_id and e.listingType='course' and e.listing_type_id in (select course_id from institute_courses_mapping_table where institute_id=$event_id_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='homepage'){
                $query="select distinct e.event_id from event e, event_date d, eventCategoryTable ect, event_venue ev, countryTable co where e.event_id=d.event_id and e.event_id=ect.eventId and e.venue_id=ev.venue_id and ev.country=co.countryId and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and co.countryId in($country_temp) and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else if($temp['subscription_type'][0]=='viewall'){
                $query="select distinct e.event_id from event e, event_date d, eventCategoryTable ect, event_venue ev, countryTable co where e.event_id=d.event_id and e.event_id=ect.eventId and e.venue_id=ev.venue_id and ev.country in($country_temp) and e.fromOthers=$fromOthers_temp and ect.boardId=$category_id_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }else{
                $query = "select distinct e.event_id from event e, event_date d where e.event_id=d.event_id and e.event_id=$event_id_temp and e.status_id is NULL and datediff(current_date,d.end_date)<=0 and ((d.end_date between '$start_date' and '$end_date') or (d.start_date between '$start_date' and '$end_date') or (datediff('$start_date',d.start_date)>=0 and datediff(d.end_date,'$end_date')>=0))";
                }
		}

		error_log_shiksha("query is defined as ".$query);
                $eventIdList = $dbHandle->query($query);
                foreach($eventIdList->result() as $row){
                array_push($eventArray,$row->event_id);
                }
		}
		$eventArray=array_unique($eventArray);
		$noOfSubscriptions=count($eventArray);
		$data['noOfSubscriptions'] = $noOfSubscriptions;
		$eventDetailsArray=array();
		foreach($eventArray as $eventIdNext){
		$queryCmd = "select e.event_id, e.event_title, ed.start_date, ed.end_date, ci.city_name,co.name from event e, event_date ed, event_venue ev, countryTable co,countryCityTable ci where e.event_id=ed.event_id and e.venue_id=ev.venue_id and ci.city_id=ev.city and co.countryId=ev.country and e.event_id= ?";
		$query = $dbHandle->query($queryCmd,array($eventIdNext));
		foreach ($query->result() as $row){
                        array_push($eventDetailsArray,array(
                                                                'event_id'=>$row->event_id,
                                                                'event_title'=>$row->event_title,
                                                                'start_date'=>$row->start_date,
                                                                'end_date'=>$row->end_date,
								'city_name'=>$row->city_name,
								'country_name'=>$row->name
                                                        )
                        );//close array_push
                }
		}
/*		$subject =$noOfSubscriptions." important dates awaiting your attention.";
		$data['userName']=$userName;
		$data['usernameemail'] = $email_temp;
		$data['userpasswordemail'] = "shiksha";
		$data['eventDetailsArray']=$eventDetailsArray;
		$content = $this->load->view("search/searchMail",array('contentArray'=>$data,'type' =>'subscribeEvents'),true);
		$data['content'] = $content;
		if($noOfSubscriptions!=0){
                $response=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$data['usernameemail'],$subject,$content,$contentType="html");
		}
*/
		if($temp['privacy_settings'][0]==3 || $temp['privacy_settings'][0]==2){
		foreach($eventDetailsArray as $tempArray){
		if($start_date==date("Y-m-d",strtotime($tempArray['start_date']))){
		$contentOfSms = "Shiksha Event Reminder: ".date("d-m-Y",strtotime($tempArray['start_date']))." - ".htmlspecialchars_decode($tempArray['event_title']);
                $responseOfSms=$alerts_client->addSmsQueueRecord("12",$temp['mobile_number'][0],$contentOfSms,"$user_id_temp");
		}
		}
		}
		}
                return $this->xmlrpc->send_response($response);
	}

}
?>
