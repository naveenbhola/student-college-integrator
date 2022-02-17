<?php 
    if($courseDates['type'] == 'onlineForm') { 
        $ctaName = 'Apply Now';
        $ctaLink = "emailResults('".$courseObj->getId()."', '".base64_encode($courseObj->getInstituteName())."' , '".$courseDates['internalFlag']."' , '". MOBILE_NL_COURSE_PAGE_ADMISSION_APPLY_OF."');";
        $ctaId = "startApp".$courseObj->getId();
        //$ctaLink = 'href="'. SHIKSHA_HOME.$courseDates['url'] .'"';
        $ctaText = 'Applications open for this course';
        $noFollow = !empty($courseDates['externalFlag']) ? '' : 'rel="nofollow"';
    }

if(!empty($ctaText)) { ?>
	<div class="crs-widget gap">
	  	<div class="lcard end-col">
	        <h2 class="admisn"><?=$ctaText;?></h2>
	        <a ga-attr="ADMISSION_APPLYNOW_COURSEDETAIL_MOBILE" id="<?=$ctaId?>" onclick="<?=$ctaLink?>" class="btn-mob-blue" <?php echo $noFollow; ?>><?=$ctaName;?></a>
	  	</div> 
	</div>
<?php }
?>
