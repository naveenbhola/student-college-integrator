<?php
    $initiateBrochureDownload = $_REQUEST['initiateBrochureDownload'];
?>
     <?php if(!(strtoupper($listingType) == 'COURSE')) {
       $ulStyle = "style='width:50%'";
       $liStyle = "style='width: 100%; border:0 none'";
      }?>
<div class="listing-info-bt clearwidth">
    <ul <?=$ulStyle?> >

        <?php // add widget type to brochure data
            $brochureDataObj['widget'] = 'belly_link';
            $brochureDataObj['trackingPageKeyId'] = 36;
            $brochureDataObj['consultantTrackingPageKeyId'] = 374;
        ?>

<li <?=$liStyle ?>>
<a href="Javascript:void(0);" id="downloadBrochureBellyLink" onclick = "studyAbroadTrackEventByGA('ABROAD_<?=strtoupper($listingType)?>_PAGE', 'bellyLink', 'downloadBrochure'); loadBrochureDownloadForm('<?=base64_encode(json_encode($brochureDataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer','downloadBrochure');" class="button-style bold" style="border-radius:0; background:#F37921; color:#fff;"><i class="common-sprite download-bro-icon"></i>Download Brochure</a></li>
        <?php   if(strtoupper($listingType) == 'COURSE') {?>
        <li><a style="text-align:left;" title = "<?= $isShortListedCoursePage ? 'Click to remove from My Saved' : 'Click to save'?>" href="javascript:void('0')" id = "shrtList_<?=$courseObj->getId()?>" onclick="addRemoveFromShortlistedCourse('<?=$courseObj->getId()?>','','',38);studyAbroadTrackEventByGA('ABROAD_<?=strtoupper($listingType)?>_PAGE', 'bellyLink', 'shortlistCourse');"><i style="" class="listing-sprite <?=$isShortListedCoursePage ? 'listing-short-icon-active' : 'listing-short-icon' ?>"></i><span id="shrtLst-btn-txt_<?=$courseObj->getId()?>"><?=$isShortListedCoursePage ? 'Saved' : 'Save this course' ?></span><span id ="shrtListCount_<?=$courseObj->getId()?>" style = 'display :none'></span> </a></li>
        <?php } ?>
		<li>
			<?php
				$selected = false;
				if(in_array($listingTypeId,$userComparedCourses)){
					$selected = true;
				}
			?>
			<a href="javascript:void(0)" onclick="compareCourse(<?=$listingTypeId?>)" id="compareCourseButton" <?=$selected?'compared="compared"':''?>>
				<i class="listing-sprite compare-course-icon"></i>
				<span><?=$selected?"Added to compare":"Compare this course"?></span>
			</a>
		</li>
    </ul>
</div>
<script> // to facilitate continuation of download-brochure flow when user explicitly logs in
    var initiateBrochureDownload  = <?=($initiateBrochureDownload==1?$initiateBrochureDownload:0)?>;

	function compareCourse(courseId){
		addRemoveFromCompare(courseId,547);
	}
</script>
