 <?php
    $initiateBrochureDownload = $_REQUEST['initiateBrochureDownload'];
 
?>
<section class="detail-widget" style="padding: 0 10px;"> 

<?php $brochureDataObj['widget'] = 'page_link'; ?> 

	<?php  	$brochureDataObj['trackingPageKeyId'] = 57; 
			$brochureDataObj['consultantTrackingPageKeyId'] = 375;
	?>
    <a id= "downloadBrochure" class="btn btn-primary btn-full mb15" href="#responseForm" data-rel="dialog" data-transition="slide" onclick = "loadBrochureForm('<?=base64_encode(json_encode($brochureDataObj))?>',this);" ><i class="sprite bro-icn"></i> <span class="vam">Email Brochure</span></a>
	<a id="compareLinkBellyCTA" class="mbl-compare-btn2" href="javascript:void(0);" onclick="addRemoveFromCompare('<?=$courseObj->getId()?>',601);" style="margin-bottom:15px;padding:8px"> <i class="sprite mbl-compare-icn"></i><span>&nbsp;&nbsp;<?=in_array($courseObj->getId(),$userComparedCourses)?"Added to ":""?>Compare</span></a><br/>
    <a style="background:#fff; color:#333" class="btn btn-default btn-full mb15 <?=$isShortlisted?'active':''?>" href="javascript:void(0);" onclick="addRemoveFromShortlist(<?=$courseObj->getId()?>,'CoursePageBelt','courseListingPage_mob',this);"> 
		<i id="shortListIcon" class="sprite shortlist-icn<?=$isShortlisted?'-filled':''?>"></i> 
		<span id="shortListText"><?=$isShortlisted?'Saved':'Save This Course'?></span> 
		<span id="shortListInfo" style="display:none"></span> 
    </a> 
</section> 
<script> // to facilitate continuation of download-brochure flow when user explicitly logs in
    var initiateBrochureDownload  = <?=($initiateBrochureDownload==1?$initiateBrochureDownload:0)?>;
</script>
