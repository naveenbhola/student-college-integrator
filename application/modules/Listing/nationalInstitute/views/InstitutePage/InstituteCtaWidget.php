<?php 
if($listing_type == 'university')
  {
    $dBrochureTrackingPageKeyId = 1619;
    $compareTrackingPageKeyId = 1621;
    $shortlistTrackingPageKeyId = 1623;

    $dBrochureRecoLayer = 1005;
    $compareRecoLayer = 1001;
    $applyNowRecoLayer = 1006;
    $shortlistRecoLayer = 1009;
    $pageType = 'ND_UniversityDetailPage';
  }
  else if($listing_type == 'institute')
  {
    $dBrochureTrackingPageKeyId = 1625;
    $compareTrackingPageKeyId = 1627;
    $shortlistTrackingPageKeyId = 1629;

    $dBrochureRecoLayer = 995;
    $compareRecoLayer = 996;
    $applyNowRecoLayer = 998;
    $shortlistRecoLayer = 997;
    $pageType = 'ND_InstituteDetailPage';
  }
  $GA_Tap_On_Sticky_Db = 'DBROCHURE_CTA_WIDGET';
  $GA_Tap_On_Sticky_Compare  = 'COMPARE_CTA_WIDGET';
?>
<div class="new-row">
	<div class="intrling-wdgtSec">
    <div class="group-card gap no__pad pdng0">
        <div class="intrling-wdgtSec-Bdr">
            <div class="intrling-wdgtDiv">
                 <strong>Get Details on Courses, Eligibility, Admission Process & Placements in your inbox.</strong>
                <p>You will also receive updates, insights and recommendations.</p>
                <div class="btn-sec">
                    <a class="shrt-list" ga-attr="<?=$GA_Tap_On_Shortlist?>" cta-type="shortlist" onclick="ajaxDownloadEBrochure(this,<?php echo $listing_id;?>,'<?php echo $listing_type;?>','<?php echo addslashes(htmlentities($instituteName));?>','<?=$pageType?>','<?php echo $shortlistTrackingPageKeyId;?>','','','','')" customCallBack="listingShortlistCallback" customActionType="ND_InstituteDetailPage">Shortlist</a>
                    <button class="button button--secondary btn-medium cmp-btn" ga-attr="<?=$GA_Tap_On_Sticky_Compare?>" onclick="showCourseLayer('compare', {'instId':<?php echo $instituteObj->getId();?>,'instType':'<?php echo $instituteObj->getListingType();?>'}, 'Add Course to Compare','<?php echo $compareTrackingPageKeyId;?>');">Add to Compare</button>
                    <button class="button button--orange btn-medium " ga-attr="<?=$GA_Tap_On_Sticky_Db?>" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" onclick="ajaxDownloadEBrochure(this,<?php echo $listing_id;?>,'<?php echo $listing_type;?>','<?php echo stripslashes(htmlentities($instituteName));?>','instituteDetailPage','<?php echo $dBrochureTrackingPageKeyId;?>','<?php echo $dBrochureRecoLayer;?>','<?php echo $compareRecoLayer;?>','<?php echo $applyNowRecoLayer;?>','<?php echo $shortlistRecoLayer;?>')">Apply Now</button>
                </div>
            </div>
        </div> 
    </div>
    </div>
</div>