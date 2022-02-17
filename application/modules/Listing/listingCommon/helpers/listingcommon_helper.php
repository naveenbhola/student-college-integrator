<?php
function getRupeesDisplableAmount($amount, $decimalPointPosition = 2, $shortUnitText = false){
    if($amount < 100000){
        $finalAmount = number_format($amount, 0, '.', '');
        setlocale(LC_MONETARY, 'en_IN');
        $finalAmount = money_format('%!.0n', $finalAmount);
    }
    else if($amount < 10000000){
        $finalAmount = $amount / 100000;
        $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
        if($shortUnitText){
            $finalAmount .= " L";
        }
        else{
            $finalAmount .= ($finalAmount == 1)? " Lakh" : " Lakh";
        }
    }
    else{
        $finalAmount = $amount / 10000000;
        $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
        if($shortUnitText){
            $finalAmount .= " Cr";
        }
        else{
            $finalAmount .= ($finalAmount == 1)? " Crore" : " Crores";
        }
    }
    
    return $finalAmount;
}

function getCourseTupleExtraData($courseObj, $page='instituteDetail', $displayFees = true,$liClassName=''){
    $extraData = getExtraInfo($courseObj);
    if($page == "mobileCategoryPage"){
        unset($extraData['extraInfo']['duration']);
    }
    if($page == 'mobileCoursePage' || $page == 'mobileCoursePageAmp'){
        $extraInfo = formatForMobile($extraData, $page, $displayFees,$liClassName);
    }else{
        $extraInfo = formatForDesktop($extraData, $page, $displayFees);
    }
    return $extraInfo;

}

function formatForMobile($extraData,$page,$displayFees,$liClassName){
    $extraInfo = '';
    if($extraData['courseLevel']){
        if($extraData['courseCredential']){
            $extraInfo .= "<li class='".$liClassName."'>".$extraData['courseLevel']." ".$extraData['courseCredential']."</li><li>|</li>";
        }else{
            $extraInfo .= "<li class='".$liClassName."'>".$extraData['courseLevel']."</li><li>|</li>";
        }
    }else{
        if($extraData['courseCredential']){
            $extraInfo .= "<li class='".$liClassName."'>".$extraData['courseCredential']."</li><li>|</li>";
        }
    }

    if($extraData['extraInfo']){
        if($extraData['extraInfo']['educationType']){
            $extraInfo .= "<li class='".$liClassName."'>".$extraData['extraInfo']['educationType']."</li><li>|</li>";
        }
        if($extraData['extraInfo']['duration']){
            $extraInfo .= "<li class='".$liClassName."'>".$extraData['extraInfo']['duration']."</li>";
            if($extraData['showDisclaimer']){
                if($page == 'mobileCoursePage'){
                    $extraInfo .= '<a href="javascript:void(0);" ga-attr="DURATIONTOOLTIP_TOP_COURSEDETAIL_MOBILE" id="durationTooltip"><i class="clg-sprite clg-info"></i></a>';
                }
                else{
                    $extraInfo .= '<a class="pos-rl" on="tap:duration-more-data" role="button" tabindex="0"><i class="cmn-sprite clg-info i-block v-mdl"></i></a>';
                }
            }
        }
    }
    return $extraInfo;
}

function formatForDesktop($extraData, $page='instituteDetail', $displayFees = true){
    $extraInfo = ''; 
    $feesValue = $extraData['fees']; 
    if($page == 'instituteDetail' && $displayFees){
        if($feesValue){
            $feesData = array('feesData' => "Fees - ".$extraData['feesUnit'].' '.$extraData['fees']);
            $extraData['extraInfo'] = $feesData + $extraData['extraInfo'];
        }
    }
    if($extraData['extraInfo']){
        $extraInfo = implode('<i></i>', $extraData['extraInfo']);
    }

    if($extraInfo && $page == 'instituteDetail'){
        $extraInfo = '<p class="para-4 st">'.$extraInfo.'</p>';
    }

    if($extraData['extraInfo'] && $page == 'courseDetail'){
        $extraInfoCourse = implode(' | ', $extraData['extraInfo']);
        if(!empty($extraData['courseLevel']) || !empty($extraData['courseCredential']))
            $extraInfo = $extraData['courseLevel']." ".$extraData['courseCredential']." | ".$extraInfoCourse;
        else
            $extraInfo = $extraInfoCourse;

        if($extraData['extraInfo']['duration'] && $extraData['showDisclaimer']){
            $extraInfo .= '<div class="tp-block">
                                            <i class="info-icn" infodata = "'.$extraData['disclaimer'].'" infopos="right"></i>
                                        </div>';
        }
    }   
    if($displayFees && $page != 'instituteDetail'){
        $feesValue = $extraData['fees'];
        $feesValueHTML = '';

        if($feesValue){
            $feesValueHTML = '<div class="fee-div"><p class="para-4">'.$extraData['feesUnit'].' '.$feesValue.'</p></div>';
        }

        $extraInfo = $extraInfo.$feesValueHTML;
    }
    return $extraInfo;
}

function getExtraInfo($courseObj, $isMobile = false){

    $extraInfo = array();
    $result    = array();

    $courseTypeData = $courseObj->getCourseTypeInformation();
    if($courseTypeData){
        $courseTypeEntryInfo = $courseTypeData['entry_course'];
        if($courseTypeEntryInfo){
            $courseLevel = $courseTypeEntryInfo->getCourseLevel()->getName();
            $courseCredential = $courseTypeEntryInfo->getCredential()->getName();
        }
    }

    if($courseLevel){
        $result['courseLevel'] = $courseLevel;
    }

    if($courseCredential){
        $result['courseCredential'] = $courseCredential;
    }

    $educationType = $courseObj->getEducationType();
    if($educationType){
        $educationId = $educationType->getId();
        $educationTypeName = $educationType->getName();
    }

    // 1. education type
    if($educationTypeName){
        $extraInfo['educationType'] = $educationTypeName;
    }

    // 2. medium/delivery method : only for part time courses
    if($educationId == 21){
        $deliveryMethod = $courseObj->getDeliveryMethod();
        $timeOfLearning = $courseObj->getTimeOfLearning();

        // exclude blend delivery method(MAB-2026)
        if($deliveryMethod->getId() != 37){
            $deliverId = $deliveryMethod->getId();
            $deliverName = $deliveryMethod->getName();

            if(in_array($deliverId, array(33))){
                $extraInfo['educationType'] .= " - ".$deliverName;
            }
            else if($deliveryMethod->getName()){
                $extraInfo['educationType'] = $deliverName;
            }

            $displayableTimeOfLearningName = array("Synchronous (Real Time)" => "Real Time",
                                                   "Asynchronous (Recorded)" => "Recorded");

            if($timeOfLearning && $timeOfLearning->getName()){
                $extraInfo['educationType'] = $extraInfo['educationType']." - ".$displayableTimeOfLearningName[$timeOfLearning->getName()];
            }
        }
    }

    // 3. duration
    $duration = $courseObj->getDuration();

    if($duration['value']){
        $extraInfo['duration']    = $duration['value']." ".($duration['value'] <= 1 ? rtrim($duration['unit'], 's') : $duration['unit'])." ";
        $result['showDisclaimer'] = $duration['showDisclaimer'];
        $result['disclaimer']     = DURATION_TOOLTIP;
    }

    $result['extraInfo'] = $extraInfo;
    

    // course fees
    $fees      = $courseObj->getFees();
    $feesValue = 0;
    if($fees && $fees->getFeesValue()){
        $feesValue = $fees->getFeesValue();
    }
    $feesValueHTML = '';
    $shortUnitText = false;
    if($isMobile){
        $shortUnitText = true;
    }

    if($feesValue){
        $feesValue = getRupeesDisplableAmount($feesValue, 2, $shortUnitText);
        $result['fees'] = $feesValue;
        $result['feesUnit'] = $fees->getFeesUnitName();
    }
    return $result;

}

 function getInstituteNameWithCityLocality($instituteName,$listing_type,$mainCity,$mainLocality=''){
    if(stripos($instituteName, $mainCity) === FALSE){
        $city = ', '.$mainCity;
    }
    if(isset($mainLocality) && $mainLocality != '' && $listing_type == 'institute'){
        $locality = ', '.$mainLocality;
    }

    $result['instituteString'] = $instituteName.$locality.$city;
    $result['cityString'] = $city;
    $result['localityString'] = $locality;
    
    return $result;
}

function getCourseFeesIncludesAndDisclaimer($feesObject){
    $feesDisclaimer = false;
    $totalIncludes = array();
    
        $totalIncludes = $feesObject->getTotalFeesIncludes();
        
        if(array_key_exists('Others', $totalIncludes)){
            $otherIncludes = $totalIncludes['Others'];
            unset($totalIncludes['Others']);
            // $otherIncludes = array_pop($totalIncludes);
            // _p($otherIncludes); die;
            $otherIncludes = explode("--", $otherIncludes);
            // array_shift($otherIncludes);

            $count = 1;
            $otherIncludes = explode("|", $otherIncludes[1]);

            foreach($otherIncludes as $otherInclude){
                $totalIncludes[] = htmlentities($otherInclude);
            }
        }

        if($feesObject->getFeeDisclaimer() == 1){
            $feesDisclaimer = true;            
        }
    

    return array(
        'disclaimer' => $feesDisclaimer,
        'totalIncludes' => $totalIncludes
    );
}

function getMonthName($monthNumber) {
    $dateObj   = DateTime::createFromFormat('!m', $monthNumber);
    return $dateObj->format('M');
}

function getFormattedDate($date){
    $startDate = $date['start_date'];$startMonth = $date['start_month'];$startYear = $date['start_year'];
    $endDate = $date['end_date'];$endMonth = $date['end_month'];$endYear = $date['end_year'];
    $returnString = '';
    if(empty($endYear)){
        $returnString .= getMonthName($startMonth).' ';
        if(!empty($startDate)){
            $returnString .= $startDate;
        }
        $returnString .= ', '.$startYear;
    }
    else{
        if($startYear == $endYear){
            if($startMonth == $endMonth){
                $returnString .= getMonthName($startMonth).' ';
                if(!empty($startDate)){
                    $returnString .= $startDate;
                }
                if(!empty($endDate) && $startDate != $endDate){
                    $returnString .= " - ".$endDate;
                }
            }
            else{
                $returnString .= getMonthName($startMonth).' ';
                if(!empty($startDate)){
                    $returnString .= $startDate;
                }
                $returnString .= ' - '.getMonthName($endMonth).' ';
                if(!empty($endDate)){
                    $returnString .= $endDate;
                }
            }
            $returnString .= ', '.$startYear;
        }
        else{
            $returnString .= getMonthName($startMonth).' ';
            if(!empty($startDate)){
                $returnString .= $startDate;
            }
            $returnString .= ', '.$startYear;
            $returnString .= ' - '.getMonthName($endMonth).' ';
            if(!empty($endDate)){
                $returnString .= $endDate;
            }
            $returnString .= ', '.$endYear;
        }
    }
    return $returnString;
}

function checkIfDateIsFutureDate($date){
    $currentDate = date('j');$currentMonth = date('n');$currentYear = date('Y');
    $startDate = $date['start_date'];$startMonth = $date['start_month'];$startYear = $date['start_year'];
    $endDate = $date['end_date'];$endMonth = $date['end_month'];$endYear = $date['end_year'];

    if(empty($endYear)){
        if(($startYear < $currentYear) || ($startYear == $currentYear && $startMonth < $currentMonth) || ($startYear == $currentYear && $startMonth == $currentMonth && !empty($startDate) && $startDate < $currentDate)){
            return false;
        }
    }
    else{
        if($endYear < $currentYear){
            return false;
        }
        else{
            if($endYear == $currentYear && ($currentMonth > $endMonth)){
                return false;
            }
            else{
                if($endYear == $currentYear && $currentMonth == $endMonth && !empty($endDate) && $currentDate > $endDate){
                    return false;
                }
            }
        }
    }
    return true;
}

function convertToFormattedDate($date) {
    return date('M d, Y', strtotime($date));
}

function getFormattedScore($value,$unit,$maxvalue){
    if(empty($value)){
        return '--';
    }
    switch ($unit) {
        case 'percentage':
            $eligibilityVal = $value."%";
            break;
        case 'percentile':
            $eligibilityVal = $value."%tile";
            break;
        case 'score/marks':
            $eligibilityVal = 'Marks - '.$value."/".$maxvalue;
            break;
        case 'CGPA':
            $eligibilityVal = 'CGPA - '.$value."/".$maxvalue;
            break;
        case 'rank':
            $eligibilityVal = 'Rank - '.$value;
            break;
        default:
            $eligibilityVal = '--';
            break;        
    }
    return $eligibilityVal;
}

function getQualificationTextForEligibility($section){

    switch($section) {
            case 'graduation':
                $qualificationText = 'Graduation';
                break;
            case 'postgraduation':
                $qualificationText = 'Post Graduation';
                break;
            case 'X':
                $qualificationText = '10th';
                break;
            case 'XII':
                $qualificationText = '12th';
                break;                    
            default:
            break;
    }   

    return $qualificationText;
}


/**
 * PHP Implementation of MurmurHash3
 *
 * @author Stefano Azzolini (lastguest@gmail.com)
 * @see https://github.com/lastguest/murmurhash-php
 * @author Gary Court (gary.court@gmail.com)
 * @see http://github.com/garycourt/murmurhash-js
 * @author Austin Appleby (aappleby@gmail.com)
 * @see http://sites.google.com/site/murmurhash/
 *
 * @param  string $key   Text to hash.
 * @param  number $seed  Positive integer only
 * @return number 32-bit (base 32 converted) positive integer hash
 */
// Code added by ankit.b
function murmurhash3_int($key,$seed=0){
      $key  = array_values(unpack('C*',(string) $key));
      

      $klen = count($key);
      $h1   = (int)$seed;

      for ($i=0,$bytes=$klen-($remainder=$klen&3) ; $i<$bytes ; ) {
        $k1 = $key[$i]
          | ($key[++$i] << 8)
          | ($key[++$i] << 16)
          | ($key[++$i] << 24);
        ++$i;
        $k1  = (((($k1 & 0xffff) * 0xcc9e2d51) + ((((($k1 >= 0 ? $k1 >> 16 : (($k1 & 0x7fffffff) >> 16) | 0x8000)) * 0xcc9e2d51) & 0xffff) << 16))) & 0xffffffff;
        $k1  = $k1 << 15 | ($k1 >= 0 ? $k1 >> 17 : (($k1 & 0x7fffffff) >> 17) | 0x4000);
        $k1  = (((($k1 & 0xffff) * 0x1b873593) + ((((($k1 >= 0 ? $k1 >> 16 : (($k1 & 0x7fffffff) >> 16) | 0x8000)) * 0x1b873593) & 0xffff) << 16))) & 0xffffffff;
        $h1 ^= $k1;
        $h1  = $h1 << 13 | ($h1 >= 0 ? $h1 >> 19 : (($h1 & 0x7fffffff) >> 19) | 0x1000);
        $h1b = (((($h1 & 0xffff) * 5) + ((((($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000)) * 5) & 0xffff) << 16))) & 0xffffffff;
        $h1  = ((($h1b & 0xffff) + 0x6b64) + ((((($h1b >= 0 ? $h1b >> 16 : (($h1b & 0x7fffffff) >> 16) | 0x8000)) + 0xe654) & 0xffff) << 16));
      }
      $k1 = 0;
      
      switch ($remainder) {
        case 3: $k1 ^= $key[$i + 2] << 16;
        case 2: $k1 ^= $key[$i + 1] << 8;
        case 1: $k1 ^= $key[$i];
        $k1  = ((($k1 & 0xffff) * 0xcc9e2d51) + ((((($k1 >= 0 ? $k1 >> 16 : (($k1 & 0x7fffffff) >> 16) | 0x8000)) * 0xcc9e2d51) & 0xffff) << 16)) & 0xffffffff;
        $k1  = $k1 << 15 | ($k1 >= 0 ? $k1 >> 17 : (($k1 & 0x7fffffff) >> 17) | 0x4000);
        $k1  = ((($k1 & 0xffff) * 0x1b873593) + ((((($k1 >= 0 ? $k1 >> 16 : (($k1 & 0x7fffffff) >> 16) | 0x8000)) * 0x1b873593) & 0xffff) << 16)) & 0xffffffff;
        $h1 ^= $k1;
      }
      $h1 ^= $klen;
      $h1 ^= ($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000);
      $h1  = ((($h1 & 0xffff) * 0x85ebca6b) + ((((($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000)) * 0x85ebca6b) & 0xffff) << 16)) & 0xffffffff;
      $h1 ^= ($h1 >= 0 ? $h1 >> 13 : (($h1 & 0x7fffffff) >> 13) | 0x40000);
      $h1  = (((($h1 & 0xffff) * 0xc2b2ae35) + ((((($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000)) * 0xc2b2ae35) & 0xffff) << 16))) & 0xffffffff;
      $h1 ^= ($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000);
  return $h1;
}


function placementSectionHeading($value){
    switch ($value) {
        case 'min':
            return "Minimum";
            break;
        case 'median':
            return "Median";
            break;
        case 'avg':
            return "Average";
            break;
        case 'max':
            return "Maximum";
            break;        
        default:
            # code...
            break;
    }

}

function tupleReviewQuesCount($inputCount){
    if($inputCount > 1000){
        $inputCount = floor($inputCount / 1000);
        return $inputCount."K";    
    }else{
        return $inputCount;
    }
    
}

function getMediaCountForInstitute($instituteObj,$currentLocationId)
{
    $photoObj = $instituteObj->getPhotos();
    $videoObj = $instituteObj->getVideos();
    $mediaCount = 0; 
    if(!empty($photoObj))
    {
        foreach ($photoObj as $photoKey => $photoValue) 
        {
           $allowMedia = false;
           $locationIds = $photoObj[$photoKey]->getLocationIds(); 
           if(in_array($currentLocationId, $locationIds) || $locationIds[0] == 0)
           {
               $allowMedia = true;
           } 
           if($allowMedia)
           {
                $tagArray = $photoObj[$photoKey]->getTags();
                if(empty($tagArray))
                $mediaCount++;
                else
                $mediaCount = $mediaCount+count($tagArray);                   
            }
        }    
    }
    if(!empty($videoObj))
    {
        foreach ($videoObj as $videoKey => $videoValue) 
        {
            $allowMedia = false;
            $locationIds = $videoObj[$videoKey]->getLocationIds();
            if(in_array($currentLocationId, $locationIds) || $locationIds[0] == 0)
            {
               $allowMedia = true;
            }
            if($allowMedia)
            {
                $mediaCount++;
            } 
       }
    }
      return $mediaCount;
}

?>
