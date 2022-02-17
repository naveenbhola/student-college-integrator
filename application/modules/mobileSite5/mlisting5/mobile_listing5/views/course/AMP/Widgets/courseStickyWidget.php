  <!---sticky button -->
<!-- <?php $this->load->view('mcommon5/AMP/dfpBannerViewSticky',array("bannerPosition" => "aboveCTA"));?> -->
<div class="sticky-dv">

         <div class="table max-table">
             <!-- <a class="btn btn-secondary color-w color-b f14 font-w7 m-5top wd50 tab-cell">Compare</a> -->
             
                <section class="wd50 i-block m-lt" amp-access="shortlisted" amp-access-hide tabindex="0">
                  <a class="btn btn-secondary color-w color-b f14 font-w7 wd50 tab-cell ga-analytic" href="<?=$courseObj->getURL();?>?actionType=shortlist&course=<?=$courseId;?>&fromCoursePage" data-vars-event-name="SHORTLIST">
                      Shortlisted
                  </a>
                </section>

                <section class="wd50 i-block m-lt" amp-access="NOT subscriber AND NOT shortlisted" amp-access-hide>
                      <a class="btn btn-secondary color-w color-b f14 font-w7 wd50 tab-cell ga-analytic" href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingId=<?=$courseId;?>&actionType=shortlist&fromwhere=coursepage&pos=sticky" data-vars-event-name="SHORTLIST">Shortlist</a>
                </section>              
                <section class="wd50 i-block m-lt" amp-access="subscriber AND NOT shortlisted" amp-access-hide tabindex="0">
                  <a class="btn btn-secondary color-w color-b f14 font-w7 wd50 tab-cell ga-analytic" href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingId=<?=$courseId;?>&actionType=shortlist&fromwhere=coursepage&pos=sticky" data-vars-event-name="SHORTLIST">
                      Shortlist
                  </a>
                </section>

                <section class="wd50 i-block" amp-access="bMailed" amp-access-hide tabindex="0">
                  <a class="btn btn-primary color-o color-f f14 font-w7 wd50 tab-cell btn-mob-dis">
                      Brochure Mailed
                  </a>
                </section>

                <section class="wd50 i-block" amp-access="NOT validuser AND NOT bMailed" amp-access-hide>
                      <a class="btn btn-primary color-o color-f f14 font-w7 wd50 tab-cell ga-analytic" href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingId=<?=$courseId;?>&actionType=brochure&fromwhere=coursepage&pos=sticky" data-vars-event-name="BROCHURE" title="<?=$instituteName;?>">Apply Now</a>
                </section>              
                <section class="wd50 i-block" amp-access="validuser AND NOT bMailed" amp-access-hide tabindex="0">
                  <a class="btn btn-primary color-o color-f f14 font-w7 wd50 tab-cell ga-analytic" href="<?=$courseObj->getURL();?>?actionType=brochure&course=<?=$courseId;?>" data-vars-event-name="BROCHURE" title="<?=$instituteName;?>" >
                      Apply Now
                  </a>
                </section>
             <!-- <a class="btn btn-primary color-o color-f f14 font-w7 m-15top wd50 tab-cell">Request Brochure</a> -->
             <p class="clr"></p>
         </div>
</div>
