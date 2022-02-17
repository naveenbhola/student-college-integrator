<?php
    $initiateBrochureDownload = $_REQUEST['initiateBrochureDownload'];
    $courseDescriptionText = $courseObj->getCourseDescription();
    $shortCourseDescription = '';
    if(strlen($courseDescriptionText) > 160){
        $shortCourseDescription = formatArticleTitle($courseDescriptionText,150);
} ?>

<!-- course duration section starts here -->
<?php 
    $courseDuration = $courseObj->getDuration();
    $courseLevel = $courseObj->getCourseLevel1Value();
    if(!empty($courseDuration)|| !empty($courseLevel)){?>
<section class="detail-widget 0detail-info">
    <div class="detail-widegt-sec">
        <div class="detail-info-sec">
            <strong>About this course</strong>
            <?php
				if(!empty($courseDuration)) {
					echo "<span class='font-12' style='margin-bottom:0;display:block;'><strong style='display:inline'>Duration: </strong>".htmlentities($courseObj->getDuration()->getExactDurationValue().' '.$courseObj->getDuration()->getDurationUnit()).'</span>';
				}
				global $certificateDiplomaLevels;
                if(!empty($courseLevel)) {
					echo "<span class='font-12' style='margin-bottom:0;'><strong style='display:inline'>Level: </strong>".$courseLevel." Program".'<span>';
				}
			?>
		</div>
    </div>
</section>
<?php } ?>
<!-- course duration section ends here -->
<!-- course description section starts here -->
<section class="detail-widget 0detail-info navSection" id="descriptionSection">
    <div class="detail-widegt-sec">
        <div class="detail-info-sec">
            <strong>Course Description</strong>
            <div class="dynamic-content">
                <p><?=$courseDescriptionText?></p>
                <div style="border-top:1px solid #ebebeb; padding-top:15px;text-align:center">
                    <?php  $brochureDataObj['widget'] = 'email_link';  
                    $brochureDataObj['trackingPageKeyId'] = 56;
                    $brochureDataObj['consultantTrackingPageKeyId'] = 379;
                    ?>
                    <a id= "downloadBrochure" class="btn btn-primary" style="margin-bottom:8px;" href="#responseForm" data-rel="dialog" data-transition="slide" onclick = "loadBrochureForm('<?=base64_encode(json_encode($brochureDataObj))?>',this);" ><i class="sprite email-icon"></i>Email Me Course Details</a><br/>
					<a id="compareLinkCourseDescription" class="mbl-compare-btn2" href="javascript:void(0);" onclick="addRemoveFromCompare('<?=$courseObj->getId()?>',600);"> <i class="sprite mbl-compare-icn"></i><span><?=in_array($courseObj->getId(),$userComparedCourses)?"Added to ":""?>Compare</span></a>
                    <a href="<?=$universityObj->getWebsiteLink()?>"><i class="sprite web-icon"></i><?=$universityObj->getWebsiteLink()?></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- course description section ends here -->