<?php 
	$GA_Tap_On_Action = 'DBROCHURE';
if($listing_type == 'university')
{
    $pageType = 'universityDetailPage';
    $brochureTrackingPageKeyId = 1087;
}
else
{
    $pageType = 'instituteDetailPage';
    $brochureTrackingPageKeyId = 1079;
}
?>
<div class="crs-widget listingTuple">
        <div class="lcard int-wdgt">
            <div class="dld-bro">
                <h2 class="head-L3">Interested in this college and want to know further details?</h2>
                <a class="btn-mob" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" ga-attr="<?=$GA_Tap_On_Action;?>" onclick="downloadCourseBrochure('<?php echo $listing_id?>','<?php echo $brochureTrackingPageKeyId;?>',{'pageType':'<?php echo $pageType;?>','listing_type':'<?php echo $listing_type;?>','callbackFunctionParams':{'pageType':'<?php echo $pageType;?>','thisObj':this}});">Request Brochure</a>
            </div>
        </div>
</div>
