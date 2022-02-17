<?php 
//get location
$course->setCurrentLocations();
$displayLocation = $course->getCurrentMainLocation();
$courseLocations = $course->getCurrentLocations();

if($appliedFilters){
    foreach($courseLocations as $location){
        $localityId = $location->getLocality()?$location->getLocality()->getId():0;
        if(in_array($localityId, $appliedFilters['locality'])){
            $displayLocation = $location;
            break;
        }
        if(in_array($location->getCity()->getId(), $appliedFilters['city'])){
            $displayLocation = $location;
            break;
        }
    }
}

if(!$displayLocation){
    $displayLocation = $course->getMainLocation();
}

//truncate insitute name after 55 chars
$instituteShortString = (strlen($institute->getName()) > 55) ? substr($institute->getName(), 0, 52).'..' : $institute->getName();

//get header image
$instituteHeaderImage = $institute->getMainHeaderImage();
$instituteThumbURL = $instituteHeaderImage->getThumbURL();

if(!($instituteHeaderImage && $instituteThumbURL)) {
    $instituteThumbURL = '<span>'.$instituteShortString[0].'</span>';
}
else{
    $instituteThumbURL = "<img width=100% data-original=$instituteThumbURL src='//".IMGURL."/public/mobile5/images/default_instt_icon.png' target=_blank class='lazy' />";
}

$trackingstring = '';
if(DO_SEARCHPAGE_TRACKING && DO_TUPLE_TRACKING && is_object($request) && $request->getTrackingSearchId()){
    global $TupleClickParams;
    $trackingstring = "?{$TupleClickParams['from']}=serp&{$TupleClickParams['pagenum']}={$request->getCurrentPageNum()}&{$TupleClickParams['tuplenum']}=$tuplenumber&{$TupleClickParams['clicktype']}=institute&{$TupleClickParams['listingtypeid']}={$institute->getId()}";
    $trackingstring .= "&{$TupleClickParams['trackingSearchId']}={$request->getTrackingSearchId()}";
    $trackingFilterId = $request->getTrackingFilterId();
    if(!empty($trackingFilterId)){
        $trackingstring .= "&{$TupleClickParams['trackingFilterId']}=".$trackingFilterId;
    }
}
?>
<div class="clg-detail-sec">
    <div class="clg-imgSec"><?php echo $instituteThumbURL; ?></div>
    <div class="clg-info">
        <a href="<?php echo $institute->getURL().$trackingstring;;?>" id="instituteName_<?php echo $institute->getId();?>" value="<?php echo base64_encode(json_encode(html_escape($institute->getName()))); ?>"><?php echo $instituteShortString; ?></a>
        <span>
        	<i class="msprite clg-loc"></i>
        	<?php echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName()?>
        </span>
    </div>
</div>