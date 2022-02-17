<?php 
	$GA_Tap_On_Compare = 'COMPARE';
	$GA_Tap_On_DBrochure = 'DBROCHURE';

	if($listing_type == 'university')
	{
	    $dBrochureTrackingKeyId = 969;
	    $compareTrackingKeyId = 970;

	    $dBrochureRecoLayer = 1005;
	    $compareRecoLayer = 1001;
	    $applyNowRecoLayer = 1006;
	    $shortlistRecoLayer = 1009;

	}
	else
	{
    	    $dBrochureTrackingKeyId = 929;
	    $compareTrackingKeyId = 930;

   	    $dBrochureRecoLayer = 995;
	    $compareRecoLayer = 996;
	    $applyNowRecoLayer = 998;
	    $shortlistRecoLayer = 997;

	}
?>
<div class="btns-col" id="CTASection">   
  <a class="button button--secondary btn-medium cmp-btn compare-site-tour" ga-attr="<?=$GA_Tap_On_Compare?>" onclick="showCourseLayer('compare', {'instId':<?php echo $instituteObj->getId();?>,'instType':'<?php echo $instituteObj->getListingType();?>'}, 'Select a course to compare','<?php echo $compareTrackingKeyId;?>');">Add to Compare
   <span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Compare'] ?></p></span>
  </a>
  <!-- <a class="btn-primary btn-medium">Download Brochure</a> -->
  <a class="button button--orange btn-pm deb-site-tour" ga-attr="<?=$GA_Tap_On_DBrochure?>" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" onclick="ajaxDownloadEBrochure(this,<?php echo $listing_id;?>,'<?php echo $listing_type;?>','<?php echo addslashes(htmlentities($instituteName));?>','instituteDetailPage','<?php echo $dBrochureTrackingKeyId;?>','<?php echo $dBrochureRecoLayer;?>','<?php echo $compareRecoLayer;?>','<?php echo $applyNowRecoLayer;?>','<?php echo $shortlistRecoLayer;?>')">Apply Now<span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['DEB'] ?></p></span>
  </a>
</div>
