<?php
$isDownloadable = $contentData['is_downloadable'];
$downloadUrl = $guideURL;
$contentId = $contentData['id'];
$totalGuideDownloaded = $contentData['totalGuideDownloaded'];
$type = $contentData['type'];
//var_dump($totalGuideDownloaded);
if($isBottomWidget)
{
		  $widget = "downloadGuideBottom";
		  $linkId = "bottomButton";
		  $trackingPageKeyId = 500;
}
else{
		  $widget = "downloadGuide";
		  $linkId  = "middleButton";
		  $trackingPageKeyId = 501;
}
?>
<!-- Download Guide Widget STARTS-->
		  <?php if($downloadUrl!=''){
					$downloadUrl=base64_encode($downloadUrl); ?>
		  <div class="download-widget clearwidth">
					<p style="font-size:18px; margin-top:8px;">Download this guide to read it offline</p>
					<?php 
					  $inputData = json_encode(array($downloadUrl,$contentId,$trackingPageKeyId,null,'new','downloadGuide',$type));
					?>
					<a class="button-style dwnld-pdf" style="font-size:14px !important; width:220px; border-radius:2px; " uniqueattr = "SA_SESSION_ABROAD_APPLY_CONTENT_PAGE/<?=$widget?>" href="javascript:void(0);" onclick='applyContentDownloadGuide("/applyContent/applyContent/downloadGuide/",<?=$inputData?>, "Register to get started");' class="button-style dwnld-pdf" <?php if($totalGuideDownloded>=50){ ?> style="margin-top:5px";<?php }?>>
						  <i class="article-sprite pdf-icon"></i>
						  Download Guide
					</a>
					<div id = "<?=$linkId?>" class="font-11" id="middleButton" <?php if($totalGuideDownloaded<50){ ?>style="display:none;"<?php } ?>><?php if($totalGuideDownloaded>=50){ ?><?php echo $totalGuideDownloaded." People downloaded this guide"; ?><?php } ?></div>
		  </div>
		  <?php } ?> 
<!-- Download Guide Widget ENDS-->
	     
	     