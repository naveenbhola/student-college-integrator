<?php
	$isDownloadable = $contentData['is_downloadable'];
	$downloadUrl = $guideURL;
	$contentId = $contentData['id'];
	$totalGuideDownloaded = $contentData['totalGuideDownloaded'];
	$type = $contentData['type'];
?>
<?php if($downloadUrl!=''){
	$downloadUrl=base64_encode($downloadUrl); ?>
	<div class="sop-other-widget">
		<strong class="sop-other-widget-head">Download this guide to read it offline</strong>
		<div class="sop-other-widget-content">
			<?php 
			  $inputData = json_encode(array($downloadUrl,$contentId,499,null,'new','downloadGuide',$type));
			?>
			<a class="sop-dwnld-guide" uniqueattr = "SA_SESSION_ABROAD_APPLY_CONTENT_PAGE/downloadGuideRight" href="javascript:void(0);" onclick='applyContentDownloadGuide("/applyContent/applyContent/downloadGuide/",<?=$inputData?>,"Register to get started");'	>Download Guide</a>
			<p  id = "rightButton" style="margin-top:5px;<?=($totalGuideDownloaded<50?'display:none;':'')?>" class="font-11"><?=($totalGuideDownloaded)?> people downloaded this guide</p>
		</div>
	</div>
<?php } ?>