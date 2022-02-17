<div class="stickCTA-Exam disappear" id="stickyCTA" style="display:none;">
    <div class="cta-cont lcard">
      <?php if(array_key_exists('samplepapers', $snippetUrl)){?>
        <div class="btn-sec long-btn">
            <a id="getsamplepaers_sticky" class="btnGetSamplePaper btn btn-secondary color-w color-b f14 font-w6" data-tracking="<?=$trackingKeys['download_sample_paper'];?>" ga-attr="DOWNLOAD_SAMPLE_PAPERS_STICKY" href="javascript:void(0);">Get Question Papers</a>
        </div>
      <?php }?>
        <div class="btn-sec shrt-btn">
            <a href="javascript:void(0);" id="download_guide_sticky" class="<?php echo !array_key_exists('samplepapers', $snippetUrl) ? 'a-only' : '';?> btnDownloadGuid btn btn-primary color-o color-f f14 font-w6 <?php echo $guideDownloaded ? 'btn-mob-dis' : '';?>" ga-attr="DOWNLOAD_GUIDE_STICKY" data-tracking="<?=$trackingKeys['download_guide'];?>"><?php echo $guideDownloaded ? 'Guide Sent' : 'Apply Now';?></a>
        </div>
    </div>
</div>
