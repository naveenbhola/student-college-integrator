
<!-- <?php $this->load->view('mcommon5/AMP/dfpBannerViewSticky',array("bannerPosition" => "aboveCTA"));?> -->
<?php if(empty($activeSectionName)){
    $activeSectionName = 'homepage';
}?>
<div class="sticky-dv">

     <div class="stickCTA-Exam" id="stickyCTA">
        
        <div class="cta-cont lcard">
              <?php if(array_key_exists('samplepapers', $snippetUrl)) { ?>
                  <div class="btn-sec long-btn">
                    <section amp-access="NOT validuser" amp-access-hide>
                        <a class="btnGetSamplePaper btn btn-secondary color-w color-b f14 font-w6 ga-analytic" href="<?=SHIKSHA_HOME?>/muser5/UserActivityAMP/getResponseExamAmpPage?examGroupId=<?=$groupId?>&actionType=exam_download_sample_paper&sectionName=<?=$activeSectionName?>&fromwhere=exampage&clickId=getQuestionPaperBottom" data-vars-event-name="DOWNLOAD_SAMPLE_PAPERS_STICKY">Get Question Papers</a>
                    </section>
                    <section amp-access="validuser" amp-access-hide>
                        <a class="btnGetSamplePaper btn btn-secondary color-w color-b f14 font-w6 ga-analytic" href="<?=$examPageUrl;?>?course=<?=$groupId?>&actionType=exam_download_sample_paper&sectionName=<?=$activeSectionName?>&fromwhere=exampage&clickId=getQuestionPaperBottom" data-vars-event-name="DOWNLOAD_SAMPLE_PAPERS_STICKY">Get Question Papers</a>
                    </section>
                  </div>
          <?php } ?>
            <div class="btn-sec shrt-btn">
                <section amp-access="GuideMailed" amp-access-hide tabindex="0" class="ouline-n">
                  <a class="<?php echo !array_key_exists('samplepapers', $snippetUrl) ? 'a-only' : '';?> btnDownloadGuid btn btn-primary color-o color-f f14 font-w6 ga-analytic btn-mob-dis ">
                      Guide Sent
                  </a>
                </section>
                <section amp-access="NOT GuideMailed AND NOT validuser" amp-access-hide>
                  <a class="<?php echo !array_key_exists('samplepapers', $snippetUrl) ? 'a-only' : '';?>  btnDownloadGuid btn btn-primary color-o color-f f14 font-w6 ga-analytic" href="<?=SHIKSHA_HOME?>/muser5/UserActivityAMP/getResponseExamAmpPage?examGroupId=<?=$groupId?>&actionType=exam_download_guide&sectionName=<?=$activeSectionName?>&fromwhere=exampage&clickId=getUpdatesBottom" data-vars-event-name="DOWNLOAD_GUIDE_STICKY">Apply Now</a>
                </section>
                <section amp-access="NOT GuideMailed AND validuser" amp-access-hide>
                  <a class="<?php echo !array_key_exists('samplepapers', $snippetUrl) ? 'a-only' : '';?>  btnDownloadGuid btn btn-primary color-o color-f f14 font-w6 ga-analytic" href="<?=$examPageUrl;?>?course=<?=$groupId?>&actionType=exam_download_guide&sectionName=<?=$activeSectionName?>&fromwhere=exampage&clickId=getUpdatesBottom" data-vars-event-name="DOWNLOAD_GUIDE_STICKY">Apply Now</a>
                </section>
            </div>
        </div>
    </div>
</div>
