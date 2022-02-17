<?php
if(!function_exists('formatQuestionThreadData')){
    function formatQuestionThreadData($data){
        $returnArr  = array();
        $answerCount= 0;$commentCount= 0;
        foreach($data[0]['MsgTree'] as $key=>$value){
            if($value['mainAnswerId']==-1){
                $type = 'question';
                $count = 0;
            }else if($value['mainAnswerId']==0){
                $type = 'answer';
                $count = $answerCount;
                $answerCount++;
            }
            if($type=='answer'){
                $returnArr['answerPostedByUsers'][] = $value['userId'];
            }
            $returnArr[$type][$value['msgId']]['msgTxt']           = $value['msgTxt'];
            $returnArr[$type][$value['msgId']]['postedByUser']     = (!empty($value['lastname']))?$value['firstname'].' '.$value['lastname'] : $value['firstname'];
            $returnArr[$type][$value['msgId']]['postedAt']         = makeRelativeTime($value['creationDate']);
            $returnArr[$type][$value['msgId']]['userId']           = $value['userId'];
            $returnArr[$type][$value['msgId']]['creationTime']     = strtotime($value['creationDate']);
            $returnArr[$type][$value['msgId']]['listingTitle']     = $value['listingTitle'];
            $returnArr[$type][$value['msgId']]['msgId']            = $value['msgId'];
            $returnArr[$type][$value['msgId']]['creationDate']     = $value['creationDate'];
            $returnArr[$type][$value['msgId']]['status']           = $value['status'];
        }
        
        $type = 'comment';
        foreach($data[0]['Replies'] as $key=>$value){
            $returnArr['answer'][$value['mainAnswerId']][$type][$commentCount]['msgTxt']       = $value['msgTxt'];
            $returnArr['answer'][$value['mainAnswerId']][$type][$commentCount]['postedByUser'] = (!empty($value['lastname']))?$value['firstname'].' '.$value['lastname'] : $value['firstname'];
            $returnArr['answer'][$value['mainAnswerId']][$type][$commentCount]['postedAt']     = makeRelativeTime($value['creationDate']);
            $returnArr['answer'][$value['mainAnswerId']][$type][$commentCount]['userId']       = $value['userId'];
            $returnArr['answer'][$value['mainAnswerId']][$type][$commentCount]['creationTime'] = strtotime($value['creationDate']);
            $returnArr['answer'][$value['mainAnswerId']][$type][$commentCount]['msgId']        = $value['msgId'];
            $commentCount++;
        }
        //$result = sortData($returnArr);
        return $returnArr;
    }
}

if(!function_exists('sortData')){
    function sortData($data){
        $result = array();
        foreach($data as $key=>$value){
            if($key=='question'){
                $result[$key] = $value;
            }else{
                foreach($value as $k=>$v){
                    $result['answer'][$k]['msgTxt'] = $v['msgTxt'];
                    $result['answer'][$k]['postedByUser'] = $v['postedByUser'];
                    $result['answer'][$k]['postedAt'] = $v['postedAt'];
                    $result['answer'][$k]['userId'] = $v['userId'];
                    $result['answer'][$k]['creationTime'] = $v['creationTime'];
                    usort($v['comment'], "cmp");
                    $result['answer'][$k]['comment'] = $v['comment'];
                }
            }
        }
        return $result;
    }
}

function cmp($a, $b) {
        return $b["creationTime"] - $a["creationTime"];
}

if (! function_exists('makeRelativeTime'))
{
    function makeRelativeTime($time){
            $timeDiff = time()-strtotime($time);
            if($timeDiff<0){
                    return "Just now";
            }elseif($timeDiff>0 && $timeDiff<=30){
                    return "Few secs ago";
            }elseif($timeDiff>30 && $timeDiff<60){
                    return "$timeDiff secs ago";
            }elseif($timeDiff>60 && $timeDiff<(60*4)){
                    return "Few mins ago";
            }elseif($timeDiff>(60*4) && $timeDiff<(60*60)){
                    $displayTime=floor($timeDiff/60);
                    return "$displayTime mins ago";
            }elseif($timeDiff>(60*60) && $timeDiff<(24*60*60)){
                    $displayTime=floor($timeDiff/(60*60));
                    if($displayTime==1){
                            return "an hour ago";
                    }else{
                            return "$displayTime hours ago";
                    }
            }elseif($timeDiff>(24*60*60) && $timeDiff<(24*7*60*60)){
                    $displayTime=floor($timeDiff/(60*60*24));
                    if($displayTime==1){
                            return "Yesterday";
                    }else{
                            return "$displayTime days ago";
                    }
            }elseif($timeDiff>(24*7*60*60) && $timeDiff<(24*60*60*31)){
                    $displayTime=floor($timeDiff/(60*60*24*7));
                    if($displayTime==1){
                            return "a week ago";
                    }else{
                            return "$displayTime weeks ago";
                    }
            }elseif($timeDiff>(24*60*60*31) && $timeDiff<(24*60*60*366)){
                    $displayTime=floor($timeDiff/(60*60*24*30));
                            if($displayTime==1){
                            return "a month ago";
                    }else{
                            return "$displayTime months ago";
                    }
            }elseif($timeDiff>(24*60*60*366*10)){
                    return $time;
            }elseif($timeDiff>(24*60*60*366)){
                    $displayTime=floor($timeDiff/(60*60*24*366));
                    if($displayTime==1){
                            return "a year ago";
                    }else{
                            return "$displayTime years ago";
                    }
            }
    }
}

if (! function_exists('formatInstituteName'))
{
    function formatInstituteName($insObj, $currentLocation){
            $instituteDisplayName = html_escape($insObj->getName()) .' ,' .
                (  ($currentLocation->getLocality() && $currentLocation->getLocality()->getName())? $currentLocation->getLocality()->getName()
                                .", ": " ") .  $currentLocation->getCity()->getName();
            return $instituteDisplayName;
    }
}

if (! function_exists('formatCAData')){
    function formatCAData($caInfo){
        $badgeArr = array('CurrentStudent'=>'CURRENT STUDENT','Alumni'=>'ALUMNI','Official'=>'OFFICIAL');
        $returnArr = array();
        foreach($caInfo['data'] as $key=>$value){
            $returnArr[$value['userId']] = $badgeArr[$value['badge']];
        }
        return $returnArr;
    }
}

if(!function_exists('googleAnalyticsGetImageUrl')){
  function googleAnalyticsGetImageUrl($str)
  {
      $GA_ACCOUNT = "MO-4454182-1";
      $GA_PIXEL =  "ga.php";
      $url  = $str . $GA_PIXEL . "?";
      $url .= "utmac=" . $GA_ACCOUNT;
      $url .= "&utmn=" . rand(0, 0x7fffffff);

      $referer = $_SERVER["HTTP_REFERER"];
      $query = $_SERVER["QUERY_STRING"];
      $path = $_SERVER["REQUEST_URI"];

      if (empty($referer)) {
        $referer = "-";
      }
      $url .= "&utmr=" . urlencode($referer);

      if (!empty($path)) {
        $url .= "&utmp=" . urlencode($path);
      }

      $url .= "&guid=ON";

      return $url;
  }
}

if(!function_exists('formatNumber')){
    function formatNumber($number){
        $number = intVal($number);

        if($number>=1000){
            $number = round(($number/1000),1).'k'; 
        }

        return $number;
    }
}

// function trim HTML ,In which $tagsNotToSkip tag character added to HTML but not count.
if(!function_exists('getTextFromHtml')) {
    function getTextFromHtml($htmlString,$limit,$tagsNotToSkip = array('table'))
    {
        $finalString = '';
        $index= 0;
        $lengthOfString = strlen($htmlString);
        $countOfChar = 0;
        $countOfDiv = 0;
        $tagToCheckOpen = array('a','p','strong','div','span');     //openTag stack maintain
        $openTags = array();										//stack
        while($index<$lengthOfString){
            if($htmlString[$index] == '<'){
                $tagName = '';
                while($htmlString[$index] !='>'){
                    $tagName .= $htmlString[$index];
                    $finalString .= $htmlString[$index];
                    $index++;
                }
                $tagName .= $htmlString[$index];
                $finalString .= $htmlString[$index];
                $index++;
                foreach($tagToCheckOpen as $tag)                  //stack implementation
                {

                	$tagLength = strlen($tag);
                	$closingTag = '</'.$tag.'>';
                	if(substr($tagName,1,$tagLength) == $tag){
                		array_push($openTags,$tag);
               		}
                	else if(substr($tagName,0,$tagLength+3) == $closingTag){

                		array_pop($openTags);
                	}
                }
                foreach($tagsNotToSkip as $tagToIgnore){        //Tags character not count but added in final HTML
                    $tabLength = strlen($tagToIgnore);
                    if($tagToIgnore == 'table'){
                    	if(substr($tagName,1,$tabLength) == $tagToIgnore){
                    		$tableCount = 0 ;
                    		$endTag = '</table>';
                    	    while(substr($htmlString,$index,$tabLength+3)!=$endTag  || $tableCount>0){
                    	        if(substr($htmlString,$index,6) == '<table'){
                    	        	$tableCount++;
                    	        }
                    	        if(substr($htmlString,$index,7) == '</table'){
                    	        	$tableCount--;
                    	        }
                    	        $finalString .=$htmlString[$index];
                    	        $index++;
                    	    }
                    	}
                    }
                    else if(substr($tagName,1,$tabLength) == $tagToIgnore){
                    	$endTag = '</'.$tagToIgnore.'>';
                        while(substr($htmlString,$index,$tabLength+3)!=$endTag){
                            $finalString .=$htmlString[$index];
                            $index++;
                        }
                    }
                }
            }
            else{
                $countOfChar++;
                $finalString .=$htmlString[$index];
                $index++;
            }
            if($countOfChar == $limit || $index>$lengthOfString){
                break;
            }
        }
        while(!empty($openTags)){
        	$tag = array_pop($openTags);
        	$endTag = '</'.$tag.'>';
        	$finalString .=$endTag;
        }
        return $finalString;                               // final HTML to return
    }
}

?>
