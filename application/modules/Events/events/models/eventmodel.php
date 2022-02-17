<?php
class EventModel extends MY_Model {
    function __construct(){
        parent::__construct('Event');
    }

    private function getEntityRelations($operation='read'){
        $tableName = '';
        $entityFieldName = '';
        $relations = array();
        $this->load->library('listingconfig');
        //$dbConfig = array( 'hostname'=>'localhost');
        //$this->listingconfig->getDbConfig_test($appId,$dbConfig);
        //$this->load->database($dbConfig);
        if($operation=='read'){
              $this->db = $this->getReadHandle();
        }
        else{
              $this->db = $this->getWriteHandle();
        }
        return $relations;
    }

    function getEventsForExams($blogId, $start,$count, $fromOthers){
        $entityRelations = $this->getEntityRelations();
        if(count($entityRelations) < 0) {
            return false;
        }
        if($fromOthers == ''){ 
            $queryCmd = 'select SQL_CALC_FOUND_ROWS e.event_title, e.event_id,  e.fromOthers , e.listingType, e.listing_type_id,  e.creationDate, d.start_date,d.end_date,v.Address_Line1,co.name as country, ci.city_name as city from event e, event_date d,event_venue v, countryTable co, countryCityTable ci, event_venue_mapping evm, listingExamMap eb where eb.status = "live" and evm.event_id = e.event_id and evm.venue_id=v.venue_id and co.countryId = v.country  and e.status_id is NULL and ci.city_id = v.city and e.event_id = eb.typeId and e.event_id = d.event_id and (eb.examId = ? OR eb.examId IN (SELECT blogId from blogTable WHERE status != "draft" AND parentId = ?)) AND eb.type="events" group by e.event_id order by d.start_date DESC LIMIT '. $start.' , '.$count;	
        } else {
            $queryCmd = 'select SQL_CALC_FOUND_ROWS e.event_title, e.event_id,  e.fromOthers , e.listingType, e.listing_type_id,  e.creationDate, d.start_date,d.end_date,v.Address_Line1,co.name as country, ci.city_name as city from event e, event_date d,event_venue v, countryTable co, countryCityTable ci, event_venue_mapping evm, listingExamMap eb where eb.status = "live" and evm.event_id = e.event_id and evm.venue_id=v.venue_id and co.countryId = v.country  and e.status_id is NULL and ci.city_id = v.city and e.event_id = eb.typeId and e.event_id = d.event_id and (eb.examId = ? OR eb.examId IN (SELECT blogId from blogTable WHERE status != "draft" AND  parentId = ?)) AND fromOthers = '. $this->db->escape($fromOthers) .' AND eb.type="events" group by e.event_id order by d.start_date DESC LIMIT '. $start.' , '.$count;	
        }
		error_log_shiksha('ASHISH JAIN:::::'.$queryCmd, 'events');
        	$query = $this->db->query($queryCmd, array($blogId,$blogId));
		$msgArray = array();
        $examEventIDs = '';
		foreach ($query->result() as $row){
            $examEventIDs .= $row->event_id .',';
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
        	$query = $this->db->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
        
        if($count > $totalRows) {
            $queryCmd = 'select SQL_CALC_FOUND_ROWS e.event_title, e.event_id, e.fromOthers , e.listingType, e.listing_type_id,  d.start_date, FLOOR(LOG(ABS((unix_timestamp(d.start_date)-unix_timestamp(now()))/86400))) startDateDiff,v.venue_name, v.Address_Line1, v.state, co.name as country, ci.city_name as city  from event e, event_date d,   event_venue v, countryTable co, countryCityTable ci, event_venue_mapping evm ,eventCategoryTable ect where  ect.eventId=e.event_id and ect.boardId in (SELECT boardId FROM categoryBoardTable WHERE parentId = (SELECT parentId FROM categoryBoardTable WHERE boardId IN (SELECT boardId from blogTable where status != "draft" AND blogId = ?))) and evm.event_id = e.event_id and e.status_id is NULL and evm.event_id = e.event_id and evm.venue_id=v.venue_id  and e.event_id=d.event_id and e.event_id not in ('. $examEventIDs. "''" .')';
                    
            if($fromOthers != -1)
                $queryCmd .= ' and e.fromOthers='.$fromOthers;
            /*
            if($cityId != 1){
                $queryCmd .= ' and v.city in ('.$cityId.')';
            }
            */
            $queryCmd .= ' and co.countryId = v.country  and ci.city_id = v.city group by e.event_id order by startDateDiff LIMIT '. $start.' , '.($count-$totalRows);   

        }
        $query = $this->db->query($queryCmd, array($blogId));
		foreach ($query->result() as $row){
            $examEventIDs .= $row->event_id .',';
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

		$mainArr = array();
		array_push($mainArr,array(
							array(
								'results'=>array($msgArray,'struct'),
								'total'=>array($totalRows,'string'),
							),'struct')
			);//close array_push
		$response = array($mainArr,'struct');

      //  $response = json_encode($response);
        return $response;
    }

    function getEventUrl($event_id , $event_title, $fromOthers,$locationsArr = array(), $listingType='', $listingTypeId=0) {
        $entityRelations = $this->getEntityRelations();
        $optionalArgs = array();
        if(count($locationsArr) > 0 && (trim($listingType) == '')){
            for($i = 0; $i < count($locationsArr); $i++){
                $optionalArgs['location'][$i] = $locationsArr[$i]['city_name']."-".$locationsArr[$i]['country_name'];
            }
        }else{
            $queryCmd = 'select ci.*, co.* from event_venue_mapping evm , event_venue v, countryCityTable ci , countryTable co where evm.event_id = ? and evm.venue_id = v.venue_id and co.countryId = v.country  and ci.city_id = v.city ';
            error_log("performance $queryCmd");
            $query = $this->db->query($queryCmd, array($event_id));
            $i  = 0;
            foreach ($query->result() as $row){
                $optionalArgs['location'][$i] = $row->city_name."-".$row->name;
                $i++;
            }
        }
        return getSeoUrl($event_id,'event',$event_title,$optionalArgs);
    }

   function getTotalEventCountForCriteria($dbHandle, $criteria=array()) {
        foreach($criteria as $key => $value) {
            $$key = $value;
        }
        $whereClause = ' WHERE event.status_id is null ';
        $appender = ' AND ';
        $join = '';
        if(isset($categoryId)) {
            if($categoryId != ''){
                $join .= ' INNER JOIN eventCategoryTable ON event.event_id = eventCategoryTable.eventId';
                if($categoryId != 1) {
                    $whereClause .= $appender .' eventCategoryTable.boardId = '. $dbHandle->escape($categoryId);
                    $join .= ' INNER JOIN categoryBoardTable ON categoryBoardTable.boardId = eventCategoryTable.boardId';
                    $appender = ' AND ';
                    $resultKey = ' eventCategoryTable.boardId';
                } else {
                    $join .= ' INNER JOIN categoryBoardTable ON categoryBoardTable.boardId = eventCategoryTable.boardId';
                    $whereClause .= $appender .' categoryBoardTable.parentId=1 ';
                    $resultKey = ' eventCategoryTable.boardId';
                }

                if($groupBy !='country'){
                    $resultKey = ' eventCategoryTable.boardId';
                }
                else{
                    $resultKey = ' event_venue.country';
                }

            }
        }
        if(isset($countryId)) {
            if($countryId != '' && $countryId !=1){
                $whereClause .= $appender .' event_venue.country IN ('.$countryId .')';
                $appender = ' AND ';
            } else {
                $whereClause .= $appender .' event_venue.country > 1 AND event_venue.country!= ""';
                $appender = ' AND ';
            }
            $join .= ' INNER JOIN event_venue_mapping ON  event_venue_mapping.event_id=event.event_id INNER JOIN event_venue on event_venue.venue_id = event_venue_mapping.venue_id';
        }
        if($groupBy !='category') {
             $resultKey = ' event_venue.country';
            $resultKeyOut = 'country';
        } else {
            $resultKey = ' eventCategoryTable.boardId';
            $resultKeyOut = 'boardId';
        }

        if(isset($exam)) {
            $whereClause = '';
           if($examId != '' && $examId != 0) {
                $whereClause .= ' AND examId = '. $dbHandle->escape($examId);
           } 
        }
        if(!isset($exam)) {
            $query = 'SELECT COUNT(DISTINCT event_id) AS numEvents, '. $resultKeyOut .' AS resultKey FROM ( SELECT event.event_id,'.  $resultKey .' FROM event '. $join  . $whereClause .') as t GROUP BY resultKey';
        } else {
            $query = "SELECT  COUNT(DISTINCT event_id) AS numEvents, parentId AS resultKey FROM (SELECT event.event_id, parentId FROM listingExamMap, blogTable,event  WHERE  listingExamMap.status = 'live' and blogTable.blogType='exam' and  listingExamMap.examId =  blogTable.blogId and blogTable.status != 'draft' and type='events'and event.event_id = listingExamMap.typeId and event.status_id is NULL ". $whereClause .") as t GROUP BY resultKey";
        }
        $resultSet = $dbHandle->query($query);
        $countList = array();
        foreach($resultSet->result_array() as $result) {
            $countList[$result['resultKey']] = $result['numEvents'];
        }
        return $countList;
    }

	public function getEventDetails($eventIds)
	{
		$entityRelations = $this->getEntityRelations();
        
        $queryCmd = 'select e.event_id,e.event_title,e.fromOthers,e.listingType,e.listing_type_id,
					 d.start_date,d.end_date,v.Address_Line1,
					 co.name as country, ci.city_name as city
					 from event e, event_date d,event_venue v, countryTable co, countryCityTable ci, event_venue_mapping evm
					 where evm.event_id = e.event_id
					 and evm.venue_id=v.venue_id
					 and co.countryId = v.country
					 and e.status_id is NULL
					 and ci.city_id = v.city
					 and e.event_id = d.event_id
					 and e.event_id in ('.implode(',',$eventIds).')';
		
		$query = $this->db->query($queryCmd);
		$results = $query->result_array();
		$data = array();
		foreach($results as $result) {
			$locationsArr = array();
			$locationsArr[0] = array();
			$locationsArr[0]['city_name'] = $result['city'];
            $locationsArr[0]['country_name'] = $result['country'];
			
			$result['URL'] = $this->getEventUrl($result['event_id'],$result['event_title'],$result['fromOthers'],$locationsArr,$result['listingType'],$result['listing_type_id']);
			
			$data[] = $result;
		}
		return $data;
	}
}
?>
