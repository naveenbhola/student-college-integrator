<?php
$isDownloadable = $content['data']['is_downloadable'];
$downloadUrl = $content['data']['download_link'];
$contentId = $content['data']['content_id'];
$type = $content['data']['type'];
?>
<!-- Download Guide Widget STARTS-->
<?php if($downloadUrl!='' && $isDownloadable!= 'no'):
          $downloadUrl=base64_encode($downloadUrl); ?>
  <div class="download-widget clearwidth">
                <p>Download this guide to read it offline</p>
		<a uniqueattr = "SA_SESSION_ABROAD_GUIDE_PAGE/downloadGuide" href="javascript:void(0);" onclick="directDownloadORShowTwoStepLayer('<?=$downloadUrl;?>','<?=$contentId;?>','<?=$totalGuideDownloded;?>',12,'new','downloadGuide','<?=$type;?>');" class="button-style dwnld-pdf" <?php if($totalGuideDownloded>=50){ ?> style="margin-top:5px";<?php }?>>
                <i class="article-sprite pdf-icon"></i>
                <span>Get it Now!</span></a>
		<div class="font-12" id="middleButton" <?php if($totalGuideDownloded<50){ ?>style="display:none;"<?php } ?>><?php if($totalGuideDownloded>=50){ ?><?php echo $totalGuideDownloded." People downloaded this guide"; ?><?php } ?></div>
             </div>
 <?php endif;?> 
             <!-- Download Guide Widget ENDS-->
	     
	     