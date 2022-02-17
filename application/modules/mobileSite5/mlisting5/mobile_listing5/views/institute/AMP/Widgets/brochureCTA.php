<?php 
$GA_Tap_On_Action = 'DBROCHURE';
?>
<div class="data-card m-5btm">
             <div class="card-cmn color-w">
                 <h2 class="f12 color-3 font-w6 m-btm">Interested in this college and want to know further details?</h2>
                 <a href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingType=<?=$listing_type?>&listingId=<?=$listing_id?>&actionType=brochure&fromwhere=<?=$listing_type?>" class="btn btn-primary color-o color-f f14 font-w7 ga-analytic" data-vars-event-name="<?=$GA_Tap_On_Action;?>" title="<?=$instituteObj->getName();?>">Apply Now</a>
             </div>
         </div>
</div>

