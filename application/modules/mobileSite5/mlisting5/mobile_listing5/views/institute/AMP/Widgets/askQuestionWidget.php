<?php 
 		if($listing_type == 'course')
         {
            $query_params = '?courseId='.$listing_id.'&listingId='.$listing_parent_id.'&fromwhere=coursepage';
         }else{
            $query_params = '?listingId='.$listing_id.'&fromwhere='.$listing_type.'&city='.$currentCityId.'&locality='.$currentLocalityId;
         }
?>
 <div class="data-card m-5btm">
                 <div class="card-cmn color-w">
                     <h2 class="f14 color-3 font-w6 m-btm">Still you have any questions?</h2>
                     <a class="btn btn-secondary color-w color-b f14 font-w6 ga-analytic" data-vars-event-name="ASK_NOW" role="button" tabindex="0" href="<?=SHIKSHA_HOME?>/mAnA5/AnAMobile/getQuestionPostingAmpPage<?=$query_params;?>">Ask Now</a>
                 </div>
             </div>