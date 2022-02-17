<?php
function limitDescriptionText($data, $eventType){
    $i=0;$result = array();
    foreach($data as $key=>$value){
        $escapeTitleStr = str_replace('\\','\\\\',$value['title']);
        $result[$i]['fullTitle'] = str_replace('"','\"',$escapeTitleStr);

	$escapeDescStr = str_replace('\\','\\\\',$value['description']);
        $fullDesc = str_replace('"','\"',$escapeDescStr);

        if(strlen($value['title'])>18){
            $titleLimit = substr($value['title'],0,15);
	    $escapeBackSlash = str_replace('\\','\\\\',$titleLimit);
            $escapeDoubleQuote = str_replace('"','\"',$escapeBackSlash);
            $result[$i]['title'] = $escapeDoubleQuote.'...';   
        }else{
            $result[$i]['title'] = $result[$i]['fullTitle'];
        }
        $result[$i]['start'] = $value['start'];
        if($value['end']!='' && ($value['start']!=$value['end'])){
            $result[$i]['end'] = date('Y-m-d', strtotime('+1 day', strtotime($value['end'])));
            $result[$i]['eventEndDate']   = $value['end'];
        }else{
            $result[$i]['end']   = $value['end'];
            $result[$i]['eventEndDate']   = '';
        }
        $result[$i]['fullDescription']   = $fullDesc;
        if(strlen($value['description'])>18){
	    $descLimit = substr($value['description'],0,15);
	    $escapeBackSlashDesc = str_replace('\\','\\\\',$descLimit);
	    $escapeDoubleQuoteDesc = str_replace('"','\"',$escapeBackSlashDesc);
            $result[$i]['description']   = '<p>'.$escapeDoubleQuoteDesc.'...'.'</p>';   
        }else{
            $result[$i]['description']   = '<p>'.$fullDesc.'</p>';
        }
        $result[$i]['article_id']   = $value['article_id'];
        $result[$i]['eventType']    = $eventType;
	if(in_array($result[$i]['fullTitle'], $examArr)){
        	$result[$i]['exam_url'] = $examArr[$result[$i]['fullTitle']]['exam_url'];
	        $result[$i]['article_url'] = $examArr[$result[$i]['article_url']]['article_url'];
	}else{
        	$result[$i]['exam_url'] = $value['exam_url'];
	        $examArr[$result[$i]['fullTitle']]['exam_url'] = $value['exam_url'];
        	$result[$i]['article_url'] = $value['article_url'];
	        $examArr[$result[$i]['fullTitle']]['article_url'] = $value['article_url'];
	}
	$examArr[] = $result[$i]['fullTitle'];
        if($eventType=='examPageEvent'){
            $result[$i]['ownerId'] = 0;
            $result[$i]['eventId'] = 0;
        }else{
            $result[$i]['ownerId'] = $value['ownerId'];
            $result[$i]['eventId'] = $value['eventId'];
        }
        $i++;
    }
    return $result;
}

function restructuredArray($array)
{
    
    $repArray = $array;
    foreach($repArray as $rep)
    {
        if($rep['eventEndDate'] != '')
        {
            $startDate = strtotime($rep['start']);
            $endDate = strtotime($rep['eventEndDate']);
            while($startDate < $endDate)
                {
                    $startDate = strtotime("+1 day", $startDate);
                    $rep['start'] = date("Y-m-d",$startDate);
                    $array[] = $rep;
                }
        }
    }
    usort($array, 'sortByDateCal');
    foreach($array as $a)
    {
        $monthParse = date_parse_from_format("Y-m-d", $a['start']);
        $month= $monthParse["month"];
        $result[$month][$a['start']][$a['eventType']][]=$a;
    }
    return $result;
}

function sortByDateCal($a,$b){
        $a1=strtotime($a['start']);
        $b1=strtotime($b['start']); 
        if($a1<$b1)
        {
            return 0;
        }
        elseif($a1>$b1)
        {
            return 1;
        }
        else
        {
            if($a['eventType']=="customEvent")
            {
                    return 1;
            }
            else
            return 0;
        }
    }
function getEventYearRange($data)
{
    $yearArr = array();
    $last  = end($data);
    $first = reset($data);
    $string = array();
    foreach($first as $date => $val)
    {
	$temp = explode('-', $date);
	$string[] = $temp[0];
	break;
    }
    foreach($last as $date => $val)
    {
	$temp = explode('-', $date);
	$string[] = $temp[0];
	break;
    }
    sort($string, SORT_NUMERIC);
    return implode('-',$string);
}
function calendarWidgetArray($array)
{
    $result = array();
    foreach($array as $a)
    {
        if(strtotime($a['start']) > strtotime(date('Y-m-d')))
        {
	    if($a['isPrimary'] == '1'){
	        $url = SHIKSHA_HOME.$a['url'];
	    }
	    else{
	        $url = SHIKSHA_HOME.$a['url']."?course=".$a['groupId'];
      	    }
	    $result[] = array('date'=>$a['start'],'title'=>$a['title'],'description'=>$a['description'],'year'=>$a['year'],'url'=>$url);
        }
    }
    return $result;
}

function escapeSpecialCharacters($data)
{
    $newData = array();
    foreach($data as $date=>$events)
    {
	foreach($events as $key=>$val)
	{
	    $escapeBackSlashDesc = str_replace('\\','\\\\',$key);
	    $escapeDoubleQuoteDesc = str_replace('"','\"',$escapeBackSlashDesc);
	    $val['event_name'] = $escapeDoubleQuoteDesc;
	    $newData[$date][$escapeDoubleQuoteDesc] = $val;
	}
    }
    return $newData;
}

function date_compare($a, $b)
{
    $t1 = strtotime($a['start']);
    $t2 = strtotime($b['start']);
    return $t1 - $t2;
}

function mapEventCountWithMonth($data){
    $returnArr = array();$tempDateArr = array();
    foreach($data as $key=>$info){
        $strToTime = strtotime($info['start']);
        if(!in_array($strToTime,$tempDateArr)){
            $tempDateArr[] = $strToTime;
            $eventCount = 1;
        }else{
            $eventCount++;
        }
        $returnArr[$info['start']] = $eventCount;
    }
    return $returnArr;
}

function find_closest($array, $date)
{
    $newArray  = array();
    foreach($array as $day)
    {
            if((strtotime($day) - strtotime($date)) >=0){
                $interval[] = strtotime($day) - strtotime($date);
                $newArray[] = $day;
            }
    }
    asort($interval);
    $closest = key($interval);
    return $newArray[$closest];
}

function rearrageEvent($eventList){
    $returnArr = array();
    $i = 0;
    foreach($eventList as $index=>$eventData){
        if($eventData['start']!=$eventData['end']){
            $rangeArr = createDateRangeArray($eventData['start'],$eventData['eventEndDate']);
            foreach($rangeArr as $key=>$value){
                $returnArr[$i] = $eventData;
                $returnArr[$i]['start'] = $value;
		$returnArr[$i]['originalStart'] = $eventData['start'];
                $i++;
            }
        }else{
            $returnArr[$i] = $eventData;
	    $returnArr[$i]['originalStart'] = $eventData['start'];
            $i++;
        }
    }
    usort($returnArr, 'date_compare');
    return $returnArr;
}
function createDateRangeArray($strDateFrom,$strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));
    if ($iDateTo>$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
    }
    return $aryRange;
}
?>
