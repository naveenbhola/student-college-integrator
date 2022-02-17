<?php
$shortlistActiveClass = '';
$shortlistText = 'Shortlist';
if(!empty($isCourseShortlisted[$courseId])) {
    $shortlistActiveClass = 'active';
    $shortlistText = 'Shortlisted';
}
?>
<div class="new-row ctaWidget">
	<div class="intrling-wdgtSec">
    <div class="group-card gap no__pad pdng0">
        <div class="intrling-wdgtSec-Bdr crs-crd">
            <div class="intrling-wdgtDiv">
                 <strong>Get Course Details on Eligibility, Fees &amp; Important Dates in your inbox.</strong>
                <p>You will also receive updates, insights and recommendations.</p>
                <div class="btn-sec">
                    <a tracking-id="1635" class="shrt-list addToShortlist <?=$shortlistActiveClass;?>" ga-track="SHORTLIST_CTA_WIDGET_COURSEDETAIL_DESKTOP"><?=$shortlistText?>
                    <a tracking-id="1633" href="javascript:void(0);" class="btn-secondary btn-medium cmp-btn addToCompare" ga-track="COMPARE_CTA_WIDGET_COURSEDETAIL_DESKTOP">Add to Compare</a>
                    <?php 
                    if(isset($_COOKIE['applied_'.$courseObj->getId()]) && $_COOKIE['applied_'.$courseObj->getId()] == 1){
                    ?>
                    <a class="button button-orange disable-btn">Apply Now</a>
                    <p class="success-msg-listing"><?=BROCHURE_SUCCESS_MSG_TXT?></p>
                    <?php
                    }else{?>
                    <a href="javascript:void(0);" tracking-id='1631' class="button button--orange btn-pm brochureClick" ga-track="DEB_CTA_WIDGET_COURSEDETAIL_DESKTOP" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>">Apply Now</a>
                <?php } ?>
                </div>
            </div>
        </div> 
    </div>
    </div>
</div>