<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * mobile Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Ankur Gupta
 */
// ------------------------------------------------------------------------

function displayTextAsPerMobileResolution($string,$noOfLines=1,$isRightSideFilled=false,$articlePage = false)
{
    $mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
    $screenWidth = $mobile_details['resolution_width'];
    if($screenWidth==320){
	    $lengthAllowed = 27 * $noOfLines;
	    $offset = 27;
	    if($isRightSideFilled){
			$lengthAllowed = 19 * $noOfLines;
			$offset = 19;
			if($articlePage)  {
				$lengthAllowed = 10 * $noOfLines;
				$offset = 10;
				
			}
	    }
    }
    if($screenWidth<320){
	    $lengthAllowed = 20 * $noOfLines;	
	    $offset = 20;
	    if($isRightSideFilled){
			$lengthAllowed = 14 * $noOfLines;
			$offset = 14;
			if($articlePage)  {
				$lengthAllowed = 7* $noOfLines;
				$offset = 7;
			
			}
	    }
    }
    if($screenWidth>320){
	    $lengthAllowed = 35 * $noOfLines;	
	    $offset = 35;
	    if($isRightSideFilled){
			$lengthAllowed = 26 * $noOfLines;
			$offset = 26;
			if($articlePage)  {
				$lengthAllowed = 13 * $noOfLines;
				$offset = 13;
			}
	    }
    }
    $stringDisplay = substr(strip_tags($string), 0, $lengthAllowed);
    if (strlen(strip_tags($string)) > $lengthAllowed)
    {
        $stringDisplay .= '...';
    }
    $stringDisplay = preg_replace("/((.){".$offset."}?)/i",'$1<wbr/>',$stringDisplay);
    return $stringDisplay;
}

function compress( $buffer ) {
  $replace = array(
    "#<!--.*?-->#s" => "",      // strip comments
    "#>\s+<#"       => ">\n<",  // strip excess whitespace
    "#\n\s+<#"      => "\n<"    // strip excess whitespace
  );
  $search = array_keys( $replace );
  $html = preg_replace( $search, $replace, $buffer );
  return trim( $html );
}

function displayTextAsPerMobileResolutionSmall($string,$noOfLines=1,$isRightSideFilled=false)
{
    $mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
    $screenWidth = $mobile_details['resolution_width'];
    if($screenWidth==320){
	    $lengthAllowed = 32 * $noOfLines;
	    $offset = 32;
	    if($isRightSideFilled){
		$lengthAllowed = 28 * $noOfLines;
		$offset = 28;
	    }
    }
    if($screenWidth<320){
	    $lengthAllowed = 26 * $noOfLines;	
	    $offset = 26;
	    if($isRightSideFilled){
		$lengthAllowed = 21 * $noOfLines;
		$offset = 21;
	    }
    }
    if($screenWidth>320){
	    $lengthAllowed = 45 * $noOfLines;	
	    $offset = 45;
	    if($isRightSideFilled){
		$lengthAllowed = 40 * $noOfLines;
		$offset = 40;		
	    }
    }
    $stringDisplay = substr(strip_tags($string), 0, $lengthAllowed);
    if (strlen(strip_tags($string)) > $lengthAllowed)
    {
        $stringDisplay .= '...';
    }
    $stringDisplay = preg_replace("/((.){".$offset."}?)/i",'$1<wbr/>',$stringDisplay);
    return $stringDisplay;
}


function should_we_display_courselevel_filter_values($levelArray){
    $ug_pg_phd_page_identifier = $_COOKIE['ug-pg-phd-catpage'];
    global $COURSELEVEL_TOBEHIDDEN_CONFIG;

    if(empty($ug_pg_phd_page_identifier)) {
            return true;
    }
    if(count($levelArray)==count($COURSELEVEL_TOBEHIDDEN_CONFIG[$ug_pg_phd_page_identifier])){
        return false;
    }
    return true;
}

function sanitizeAppliedFilters($appliedFilters,$isStudyAbroad){
	if(isset($appliedFilters['exams']) && count($appliedFilters['exams'])<1) unset($appliedFilters['exams']);
	if(isset($appliedFilters['courseLevel']) && count($appliedFilters['courseLevel'])<1) unset($appliedFilters['courseLevel']);
	if(isset($appliedFilters['city']) && count($appliedFilters['city'])<1) unset($appliedFilters['city']);
	if(isset($appliedFilters['duration']) && count($appliedFilters['duration'])<1) unset($appliedFilters['duration']);
	if(isset($appliedFilters['mode']) && count($appliedFilters['mode'])<1) unset($appliedFilters['mode']);
	if(isset($appliedFilters['country']) && count($appliedFilters['country'])<1) unset($appliedFilters['country']);
	if(isset($appliedFilters['state']) && count($appliedFilters['state'])<1) unset($appliedFilters['state']);
	if(isset($appliedFilters['locality']) && count($appliedFilters['locality'])<1) unset($appliedFilters['locality']);

	$courseLevelArray = isset($appliedFilters['courseLevel'])?$appliedFilters['courseLevel']:array();
	if($isStudyAbroad){
	    $showLevelArray = should_we_display_courselevel_filter_values($courseLevelArray);
	    if(!$showLevelArray){
	        unset($appliedFilters['courseLevel']);
	    }
	}
	return $appliedFilters;
}



function getUserIdFromRecomendationData($data){
	 $userIds = '';
    	 foreach($data as $key=>$value){
		$userIds .= $value['userid'].',';
	 }
	 return rtrim($userIds,',');
}

function formatDataMobileUserIds($data,$userIdsArr){
	 $finalArr = array();
   	 foreach($data as $key=>$value){
		if(in_array($value['userid'],$userIdsArr)){
			$finalArr[] = $value;
		}
	 }
	return $finalArr;
}

function checkEBrochureFunctionality($courseObj){
	if($courseObj->isPaid()=="TRUE"){
	    return true;
	}
	else{
	    return true;
	}
}

function addAltTextMobile($title,$description , $lazyLoad = 0){ 
    $dom = new DOMDocument; 
    $dom->loadHTML($description); 
 
    $anchors = $dom->getElementsByTagName('img'); 
    $html = ''; 
 
    if($anchors->length > 0) 
    { 
    	$index = 0;
        foreach($anchors as $anchor) 
        { 
                $rel     = array(); 
                if ($anchor->hasAttribute('alt') AND ($relAtt = $anchor->getAttribute('alt')) !== '') 
                { 
                        $rel = preg_split('/\s+/', trim($relAtt)); 
                } 
                // if alt tag is not their is url then insert  the title 
                if(count($rel)<=0) 
                { 
                        $rel[] = $title; 
                } 
                // set the alt and src atrribute in image tag 
                $anchor->setAttribute('alt', implode(' ', $rel)); 
                if($lazyLoad == 1){
                	if($index !=0 ){
		                $currentImage = $anchor->getAttribute('src');
		                $anchor->setAttribute('data-original', $currentImage);
		                $anchor->setAttribute('src','');
		                $anchor->setAttribute('class','lazy');	
	                }
	                $index ++;
                }
        } 
 
        foreach($dom->getElementsByTagName('body')->item(0)->childNodes as $element) { 
                $html .= $dom->saveHTML($element); 
        } 
    } 
    else{ 
            $html = $description; 
    } 
 
    return $html; 
}

function getRemainingTabsDataForHomepage($arr, $tab){
	$value = array();
	foreach($arr as $key=>$val) {
		if($key != $tab){
			$value[] = $key;
		}
	}
	return $value;
}

function getSelectedTabForUser($userData, $cookie, $tabs){
	$selectedTab = '';
	if($cookie != '' && in_array($cookie, array_keys($tabs))){
		return $cookie;
	}
	if(empty($userData)){
		return 'mba';
	}
	foreach ($tabs as $tabKey => $value) {
		if(in_array($value['streamId'],array_keys($userData)) && in_array($value['baseCourse'],$userData[$value['streamId']])){
			$selectedTab = $tabKey;
			break;
		}
		if(in_array($value['streamId'],array_keys($userData)) && empty($value['baseCourse'])){
			$selectedTab = $tabKey;
			break;	
		}
	}
	if($selectedTab == ''){
		$selectedTab = 'other';
	}
	return $selectedTab;
}
 
/* End of file mobile/helpers/mobile_html5_helper.php */

if (! function_exists('getSmallImage'))
{
        function getSmallImage($imageLink)
        {
                if((trim($imageLink) == '') || (strrpos($imageLink,'_s') != false))
                        return $imageLink;
                if(strrpos($imageLink,'_m') != false)
                        return str_replace("_m","_s",$imageLink);
                if(strrpos($imageLink,'_t') != false)
                        return str_replace("_t","_s",$imageLink);

                if((strlen($imageLink)-strrpos($imageLink,'.')) > 5){
                        return $imageLink . '_s';
                }

                $imageString = substr($imageLink,0,(strrpos($imageLink,'.')));
                $imageExt = substr($imageLink,(strrpos($imageLink,'.')+1),strlen($imageLink));
                return $imageString.'_s.'.$imageExt;
        }
}


if ( ! function_exists('cutStringWithShowMore'))
{
function cutStringWithShowMore($str,$characterCheck,$id,$text = 'more',$widgetName = ''){
        if(strlen($str) > $characterCheck){
                $str = '<span id="'.$id.'_ellipsis">'.nl2br(htmlentities(substr($str,0,($characterCheck-3)))).'...<a href="javascript: void(0);" class="link-blue-small" onClick="showMoreString(\''.$id.'\',\''.$widgetName.'\')">'.$text.'</a></span><span id="'.$id.'_text" style="display:none">'.nl2br(makeURLAsHyperlink($str)).'</span>';
		return $str;
        }
	else{
	        return nl2br(makeURLAsHyperlink($str));
	}
}
}

if ( ! function_exists('cutStringWithShowMoreAnswer'))
{
function cutStringWithShowMoreAnswer($str,$characterCheck,$id,$text = 'more',$widgetName = ''){
        if(strlen(strip_tags($str)) > $characterCheck){
                $str = '<span id="'.$id.'_ellipsis">'.substr(strip_tags($str),0,($characterCheck-3)).'...<a href="javascript: void(0);" class="link-blue-small" onClick="showMoreString(\''.$id.'\',\''.$widgetName.'\')">'.$text.'</a></span><span id="'.$id.'_text" style="display:none">'.$str.'</span>';
                return $str;
        }
        else{
                return $str;
        }
}
}

function checkIfBrochureDownloaded($courseId){
    $brochuresMailed = $_COOKIE['applied_courses'];
    if(empty($brochuresMailed)){
        $brochuresMailed = array();
    }else{
        $brochuresMailed = json_decode(base64_decode($brochuresMailed));
    }
    if(in_array($courseId, $brochuresMailed)){
        return true;
    }
    return false;
}

if(!function_exists('dec_enc')) {
    function dec_enc($action, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'This is my secret key';
        $secret_iv = 'This is my secret iv';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
}
