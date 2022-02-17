<?php 
    if($courseDates['type'] == 'onlineForm') { 
        $ctaId = "startApp".$courseObj->getId();
        $noFollow = !empty($courseDates['externalFlag']) ? '' : 'rel="nofollow"';
?>
     <div class="data-card m-5btm">
             <div class="card-cmn color-w">
                 <h2 class="f14 color-3 font-w6 m-btm">Applications open for this course?</h2>
                 <section class="" amp-access="NOT validuser" amp-access-hide>
                      <a data-vars-event-name="APPLY_NOW" class="btn btn-secondary color-w color-b f14 font-w6 ga-analytic" href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getRegistrationAmpPage?listingId=<?=$courseId;?>&actionType=applynow&fromwhere=coursepage" <?php echo $noFollow; ?>>Apply Now</a>
                </section>              
                <section class="" amp-access="validuser" amp-access-hide tabindex="0">
                  <a data-vars-event-name="APPLY_NOW" class="btn btn-secondary color-w color-b f14 font-w6 ga-analytic" href="<?=$courseObj->getURL();?>?actionType=applynow" <?php echo $noFollow; ?>>Apply Now
                  </a>
                </section>
             </div>
         </div>
 <?php } ?>
