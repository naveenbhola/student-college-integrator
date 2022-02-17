  <!---sticky button -->
  <!-- <?php $this->load->view('mcommon5/AMP/dfpBannerViewSticky',array("bannerPosition" => "aboveCTA"));?> -->
    <div class="sticky-dv">
        
         <div class="table max-table">
            <section class="wd50 i-block m-lt">
                <a href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingType=<?=$listing_type?>&listingId=<?=$listing_id?>&actionType=shortlist&fromwhere=<?=$listing_type?>&pos=sticky" class="btn btn-secondary color-w color-b f14 font-w7 wd50 tab-cell ga-analytic" data-vars-event-name="SHORTLIST_STICKY">Shortlist</a>
            </section>
         <section class="wd50 i-block">
             <a href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingType=<?=$listing_type?>&listingId=<?=$listing_id?>&actionType=brochure&fromwhere=<?=$listing_type?>&pos=sticky"  class="btn btn-primary color-o color-f f14 font-w7 wd50 tab-cell ga-analytic" data-vars-event-name="DBROCHURE_STICKY" title="<?=$instituteObj->getName();?>">Apply Now</a>
        </section>
             <p class="clr"></p>
         </div>
    </div>