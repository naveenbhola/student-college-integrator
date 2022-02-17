<?php
$isDownloadable = $content['data']['is_downloadable'];
$downloadUrl = $content['data']['download_link'];
$contentId = $content['data']['content_id'];
$type = $content['data']['type'];
?>
<!-- Download Guide Widget STARTS-->
<?php if($downloadUrl!='' && $isDownloadable!= 'no'){
	$downloadUrl=base64_encode($downloadUrl); ?>
	<div class="artcl-right-wdgt clearwidth sop-other-widget" id="dwnld-guide-right-wdgt" >
		<strong class="sop-other-widget-head">Download this guide to read it offline</strong>
		<div class="sop-other-widget-content">
			<a class="sop-dwnld-guide" uniqueattr = "SA_SESSION_ABROAD_GUIDE_PAGE/downloadGuide" onclick="directDownloadORShowTwoStepLayer('<?=$downloadUrl;?>','<?=$contentId;?>','<?=$totalGuideDownloded;?>',10,'new','downloadGuide','<?=$type;?>');">Download Guide</a>
			<p  id = "rightButton" style="margin-top:5px;<?=($totalGuideDownloded<50?'display:none;':'')?>" class="font-11"><?=($totalGuideDownloded)?> people downloaded this guide</p>
		</div>
	</div>
<!-- Download Guide Widget ENDS-->
<?php } ?>
	     
	     