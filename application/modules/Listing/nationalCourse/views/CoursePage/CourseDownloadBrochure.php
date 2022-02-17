<?php

if($isSticky){
	$trackingId = "tracking-id='".DEB_DESKTOP_CTA_STICKY."'";
	$gaTrack = 'DEB_STICKY_COURSEDETAIL_DESKTOP';
}else{
	$trackingId = "tracking-id='".DEB_DESKTOP_CTA."'";
	$gaTrack = 'DEB_COURSEDETAIL_DESKTOP';
	$siteTourClass = 'deb-site-tour';
}

if(isset($_COOKIE['applied_'.$courseObj->getId()]) && $_COOKIE['applied_'.$courseObj->getId()] == 1){
?>
<a class="button button--orange courseBrochure disable-btn <?php echo $siteTourClass; ?>">Apply Now
	<span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['DEB'] ?></p></span>
</a>
<p class="success-msg-listing"><?=BROCHURE_SUCCESS_MSG_TXT?></p>
<?php
}else{?>
    <a href="javascript:void(0);" <?=$trackingId;?> class="button button--orange courseBrochure brochureClick <?php echo $siteTourClass; ?>" ga-track="<?=$gaTrack;?>" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>">Apply Now
    	<span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['DEB'] ?></p></span>
    </a>
    <p class="success-msg-listing hid"><?=BROCHURE_SUCCESS_MSG_TXT?></p>
<?php }?>