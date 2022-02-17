<?php 

//truncate insitute name after 55 chars
$instituteShortString = (strlen($institute->getName()) > 55) ? substr($institute->getName(), 0, 52).'..' : $institute->getName();
$displayLocationString = $displayDataObject[$course->getId()]->getDisplayLocationString();
$displayLocation = $displayDataObject[$course->getId()]->getDisplayLocation();
$locationSuffixForUrl = "";
if(!empty($displayLocation)){
    $instituteThumbURL = $institute->getHeaderImage($displayLocation->getLocationId());    
    $locationSuffixForUrl = $displayDataObject[$course->getId()]->getLocationSuffixForUrl();
}
//get header image
//$instituteHeaderImage = $institute->getMainHeaderImage();
// $instituteThumbURL = $institute->getLogoUrl();

$thumbUrl = "";
if(!empty($instituteThumbURL)) {
    $thumbUrl = getImageVariant($instituteThumbURL->getURL(),1);
}
if(!empty($instituteThumbURL) && !empty($thumbUrl)){
    $instituteThumbURL = getImageVariant($instituteThumbURL->getURL(),1);
}else{
    $instituteThumbURL = IMGURL_SECURE."/public/mobile5/images/cat_default_mobile.png";
}

$instituteThumbURL = "<img width=100% data-original=$instituteThumbURL src='".IMGURL_SECURE."/public/mobile5/images/cat_default_mobile.png' target=_blank class='lazy' />";

if(DO_SEARCHPAGE_TRACKING && DO_TUPLE_TRACKING && in_array($product,array('MAllCoursesPage','McategoryList','MsearchV3')) && !empty($trackingSearchId)){
    global $TupleClickParams;
   $trackingstring  = "{$TupleClickParams['from']}=serp&{$TupleClickParams['pagenum']}={$pageNumber}&{$TupleClickParams['tuplenum']}=$tuplenumber&{$TupleClickParams['listingtypeid']}={$institute->getId()}";
            $trackingstring .= "&{$TupleClickParams['trackingSearchId']}={$trackingSearchId}";
            if(!empty($trackingFilterId)){
                $trackingstring .= "&{$TupleClickParams['trackingFilterId']}=".$trackingFilterId;
            }
        $trackingStringImage = $trackingstring."&{$TupleClickParams['clicktype']}=instituteImg";
        $trackingStringName = $trackingstring."&{$TupleClickParams['clicktype']}=institute";
}

?>
<div class="clg-detail-sec">
    <div class="clg-imgSec image-container">
    <a href="<?php echo add_query_params($institute->getURL().$locationSuffixForUrl,$trackingStringImage);?>" id="instituteName_<?php echo $institute->getId();?>"><?php echo $instituteThumbURL; ?>
       </a>   
    </div>
    <div class="clg-info">
        <a href="<?php echo add_query_params($institute->getURL().$locationSuffixForUrl,$trackingStringName);?>" id="instituteName_<?php echo $institute->getId();?>" value="<?php echo base64_encode(json_encode(html_escape($institute->getName()))); ?>"><?php echo htmlentities($instituteShortString); ?></a>
        <span>
        	<i class="msprite clg-loc"></i>
        	<?php echo "";?><?php echo $displayLocationString; ?>
        </span>
        <?php if($isInstituteMultilocation[$institute->getId()]) { ?>
            <a href="<?php echo $isInstituteMultilocation[$institute->getId()]; ?>" class="mr-brnLnk">+more branches</a>
        <?php } ?>
        <?php 
          $admissionURL = $institute->getURL().'/admission';
          $isAdmissionPage = $institute->isAdmissionDetailsAvailable();
          $pageType = $product;
            if($product =='brochureRecoLayer'){
                $pageType = 'RecommendationLayer';
            }
            if($product == 'MsearchV3'){
                $pageType = 'Search';
            }
            if($product == 'McategoryList'){
                $pageType = 'Category'; 
            }
            if($isAdmissionPage){ ?>
                <a href='<?php echo $admissionURL;?>' class='adms-det' ga-page="<?php echo $pageType;?>">View Admission Details </a>
        <?php } ?>
    </div>
</div>