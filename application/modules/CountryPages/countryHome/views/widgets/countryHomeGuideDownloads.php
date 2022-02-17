<?php
//_p($widgetData['countryHomeGuideDownloads']);
//_p($loggedInUserData);
$isLDBUser = $loggedInUserData['isLDBUser'];
$downloadWidgetData = $widgetData['countryHomeGuideDownloads'];
?>
<div class="<?=($floatClass)?> popular-widget" style="height:345px;">
    <div class="popular-widget-title"><?=($widgetConfigData['countryHomeGuideDownloads']['title'])?></div>
        <div class="popular-widget-detail">
        <div class="scrollbar1 clearwidth soft-scroller">
            <div class="scrollbar" style="visibility: hidden; margin-left: 14px;">
                <div class="track">
                    <div class="thumb"></div>
                </div>
            </div>
            <div class="viewport" style="height:290px">
                <div class="overview">
                    <ul class="pop-widget-list">
                       <?php
                       $count=1;
                       foreach($downloadWidgetData as $downloadObj){
                        //_p($downloadObj);
                        $guideDownloadlLink = base64_encode($downloadObj['download_link']);
                        ?>
                       <li class="<?= (count($downloadWidgetData)==$count)?"last":''?>" >
                              <a href="<?= $downloadObj['contentURL']?>" class="download-nw-title" target="_blank"><?= htmlentities(formatArticleTitle($downloadObj['strip_title'],95))?></a>	
                              <p><?= formatArticleTitle(strip_tags($downloadObj['summary']),180);?></p>
                          <div class="clearwidth">
                             <a href="javaScript:void(0);" class="flLt PDFguide-link" onclick="directDownloadORShowOneStepLayer('<?= $guideDownloadlLink;?>','<?= $downloadObj['content_id'];?>', '<?= $isLDBUser;?>',1)">
                                  <span><i class="common-sprite guide-pdf-icon"></i>PDF</span><strong>Download Now!</strong>
                              </a>
                          </div>
                          <?php if($downloadObj['totalDownloadCount'] >50){?>
                          <p class="font-10" style="color:#999"><?= $downloadObj['totalDownloadCount']?> users downloaded this guide</p>
                          <?php }?>
                          <div class="clearfix"></div>
                       </li>
                       <?php $count++; }?>
                   </ul>
                </div>
            </div>    
        </div>
      </div>
</div>
