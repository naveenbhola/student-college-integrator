<?php
function createSEOMetaTagsForAdvisoryBoard($baseUrl,$level,$start,$count,$maxCount){
		$result = array();
		 if($maxCount > 50){
				/*If max count is more than 50 i.e. there is pagination then create canonical url,next and previous meta tags*/
				if($start == 0){
					$result['nextUrl'] = $baseUrl.'/'.$level.'/'.($start+$count).'/'.$count;
					$result['previousUrl'] = '';
					if($level=='All'){
						$result['canonicalUrl'] = $baseUrl;
					}else{
						$result['canonicalUrl'] = $baseUrl.'/'.$level.'/';
					}
				}else{
					if($maxCount > ($start+$count)){
						$result['nextUrl'] = $baseUrl.'/'.$level.'/'.($start+$count).'/'.$count;
					}else{
						$result['nextUrl'] = '';
					}
					if($start-$count<=0 && $level=='All'){
							$result['previousUrl'] = $baseUrl;
					}else if($start-$count<=0 && $level!='All'){
						$result['previousUrl'] = $baseUrl.'/'.$level.'/';
					}
					if($start-$count>0){
						$result['previousUrl'] = $baseUrl.'/'.$level.'/'.($start-$count).'/'.$count;
					}
					$result['canonicalUrl'] = $baseUrl.'/'.$level.'/'.$start.'/'.$count;
				}
			}else{
				/*If max count is less than 50 i.e. there is pagination then create canonical url,next and previous meta tags*/
				if($level=='All'){
					$result['canonicalUrl'] = $baseUrl;
				}else{
					$result['canonicalUrl'] = $baseUrl.'/'.$level.'/';
				}
				$result['nextUrl'] = '';
				$result['previousUrl'] = '';
			}
			return $result;
	}
	
if ( ! function_exists('sanitizeAnAMessageText'))
{
    function sanitizeAnAMessageText($str,$entityType='question',$ampFlag=false)
    {
           $message = str_replace('||','',str_replace('@||','||',$str));
           $message = preg_replace('{\?+}', '?', $message);
	   $message = preg_replace('{\.+}', '.', $message);
	   if(strtolower($entityType)!='question' && strtolower($entityType)!='discussion'){
		$message = preg_replace("/(([\r\n\t])([ ])+)+/", "", $message);

				global $isWebAPICall;
				if($isWebAPICall){
					//$message = preg_replace('/(((http|https):\/\/[^\s]+)|((www.)[^\s]+))/i', '<a href="$1" target="_blank"  rel="nofollow" style="word-wrap:break-word;font-weight:400;font-size:13px;">$1</a>', $message);
				$message = makeURLasHyperLinkWithCheck($message, 'web',$ampFlag);										
			}else{
					//$message = preg_replace('/(((http|https):\/\/[^\s]+)|((www.)[^\s]+))/i', '<a href="$1" target="_blank"  rel="nofollow" style="word-wrap:break-word;">$1</a>', $message);
					$message = makeURLasHyperLinkWithCheck($message, 'app',$ampFlag);
				}
				$message = preg_replace('/((\")|(\'))(www.)/i', '${1}http://', $message);
				$message = preg_replace("/[\r\n]+/", "<br/>", $message);

		   }else{
				$message = preg_replace("/(([\r\n\t])([ ])+)+/", "", $message);
				$message = preg_replace("/[\r\n]+/", "\n", $message);
		   }
           return $message;        
    }
}

	function makeURLasHyperLinkWithCheck($text, $source,$ampFlag=false){
		if($ampFlag)
		{
			$style = "class='word-break'";
		}
		else if($source == 'web'){
			$style = "style='word-wrap:break-word;font-weight:400;font-size:13px;'";
		}
		else{
			$style = "style='word-wrap:break-word;'";
		}
	        // The Regular Expression filter
        	//$reg_exUrl = "/(((http|https):\/\/[^\s]+)|((www[.])[^\s]+))/i";

			$reg_exUrl = "/(((http|https):\/\/[^\s\"\,\'\$\)\}]+)|((www[.])[^\s\"\,\'\$\)\}]+))/i";


	        // Check if there is a url in the text
	        if(preg_match_all($reg_exUrl, $text, $url, PREG_OFFSET_CAPTURE)) {

	            // make the urls hyper links
        	    //$matches = array_unique($url[0]);
        	    $matches = $url[0];
    			$i=0;
	            foreach($matches as $match) {
	            	if(strrpos($match[0], ".") !== FALSE)
	            	{
	            		$pos = strrpos($match[0],".");
	            		if(strlen($match[0])-1 == $pos)
	            		{
	            			$match[0] = substr_replace($match[0], "", $pos,1);
	            		}
	            	}
	            	$positions[$i]['start'] = $match[1];   
			        $positions[$i]['end'] = $match[1] + strlen($match[0]);
			        $i++;
        		    
                //$text = str_replace($match,$replacement,$text);
			    //$text = preg_replace("(^|\s)$match($|\s)", $replacement, $text);
            	}
         
            	for ($i=count($positions)-1; $i >=0; $i--) {        			
					    $match = substr($text, $positions[$i]['start'],$positions[$i]['end']-$positions[$i]['start']);
					    if(strpos($match, "shiksha.com") !== FALSE){
					    	if(strpos($match, "http://") === FALSE && strpos($match, "https://") === FALSE )
					    	{
					    		$match = preg_replace('/(www.)/i', 'http://', $match);
					    	}
					    	$match = preg_replace("/^http:/i", "https:", $match);

			                $replacement = '<a href="'.$match.'" '.$style.'>'.$match.'</a>';
	              		    }
			            else{
	                		$replacement = '<a rel="nofollow" target="_blank" href="'.$match.'" '.$style.'>'.$match.'</a>';
			            }
					    $text = substr_replace($text, $replacement,$positions[$i]['start'],$positions[$i]['end']-$positions[$i]['start'] );
				}
        	}
        	return $text;
	}


	function calculateTatDataForQuestionsUsers($data){
		$tempData1 = array();
		foreach ($data as $key => $value) {
			$tempData1[$value['ansId']][] = $value;
		}
		//_p($tempData1);die;
		$tempData2 = array();
		foreach ($tempData1 as $ansId => $val) {
			//echo count($val);
			if(count($val) > 1) {
				foreach ($val as $key => $value) {
					if($value['categoryId'] > 1){
						$tempData2[$value['queId']][] = $value;
					}
				}
			}else{
				$tempData2[$val[0]['queId']][] = $val[0];
			}
		}
		//_p($tempData2);die;
		$tempData3 = array();
		foreach ($tempData2 as $queId => $val) {
			if(count($val) > 1) {
				$tempData3[$queId] = $val[0];
				foreach ($val as $key => $value) {
					if(strtotime($tempData3[$queId]['ansPostDate']) > strtotime($value['ansPostDate'])){
						$tempData3[$queId] = $value;
					}
				}
			}else{
				$tempData3[$queId] = $val[0];
			}
		}
		$tempData4 = array();
		foreach ($tempData3 as $key => $value) {
			$tempData4[$value['categoryId']][] = strtotime($value['ansPostDate']) - strtotime($value['quePostDate']);
		}
		return $tempData4;
	}

	function formatDiscussionDataByCategory($data){
		$tempData1 = array();
		foreach ($data as $key => $value) {
			if(!(isset($tempData1[$value['msgId']])) || isset($tempData1[$value['msgId']]) && $tempData1[$value['msgId']]['categoryId']==1){
				$tempData1[$value['msgId']] = $value;
			}
		}
		$tempData2 = array();
		foreach ($tempData1 as $dscnId => $val) {
			$tempData2[$val['categoryId']][] = $val['msgId'];
		}
		$tempData3 = array();
		foreach ($tempData2 as $catId => $arr) {
			$tempData3[$catId] = count($arr);
		}
		return $tempData3;
	}

	function formatUserPostingDataByExternalUsers($data){
		$tempData1 = array();
		foreach ($data as $key => $value) {
			$tempData1[$value['categoryId']][] = $value['userId'];
		}
		$tempData2 = array();
		foreach ($tempData1 as $catId => $userIdArr) {
			$tempData2[$catId] = count(array_unique($userIdArr));
		}
		return $tempData2;
	}

	function formatNumOfQuesAnsweredByExternalUsers($data){
		$tempData1 = array();
		foreach ($data as $key => $value) {
			$tempData1[$value['categoryId']][] = $value['queId'];
		}
		$tempData2 = array();
		foreach ($tempData1 as $catId => $quesIdArr) {
			$tempData2[$catId] = count($quesIdArr);
		}
		return $tempData2;
	}
?>
