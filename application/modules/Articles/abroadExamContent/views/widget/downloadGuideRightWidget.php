<?php
	$isDownloadable = $contentDetails['is_downloadable'];
	$downloadUrl = $contentDetails['download_link'];
	$contentId = $contentDetails['content_id'];
	$totalGuideDownloaded = $contentDetails['guideDownloadCount'];
	$type = $contentDetails['type'];
	//$totalGuideDownloaded = 51;
?>
<?php
	if($isDownloadable == 'yes' && $downloadUrl !=""){
$downloadUrl=base64_encode($downloadUrl); ?>
<div class="sop-other-widget" id = "examContentDownloadGuide">
	<strong class="sop-other-widget-head">Download this guide to read it offline</strong>
	<div class="sop-other-widget-content">
		<a class="sop-dwnld-guide" uniqueattr = "SA_SESSION_ABROAD_EXAM_CONTENT_PAGE/downloadGuideRight" href="javascript:void(0);" onclick="directDownloadORShowOneStepLayer('<?php echo $downloadUrl; ?>','<?php echo $contentId; ?>','<?php echo $loggedInUserData['isLDBUser']; ?>',700,'downloadGuide','<?php echo $type; ?>');" >Download Guide</a>
		<p  id = "rightButton" style="margin-top:5px;<?=($totalGuideDownloaded<50?'display:none;':'')?>" class="font-11"><?=($totalGuideDownloaded)?> people downloaded this guide</p>
	</div>
</div>
<?php } ?>