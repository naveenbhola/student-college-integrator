<?php if(is_array($scholarship) && count($scholarship)>0) { ?>
    <div class="data-card m-5btm">
         <div class="card-cmn color-w">
             <h2 class="f14 color-3 font-w6 m-btm">Want to know more about <?=$courseObj->getInstituteName();?> Scholarships details?</h2>
             <a class="btn btn-secondary color-w color-b f14 font-w6 ga-analytic" href="<?php echo $instituteObj->getAllContentPageUrl('scholarships');?>" role="button" tabindex="0" data-vars-event-name="READ_SCHOLARSHIPS">Read About Scholarships</a>
         </div>
     </div>
<?php }?>
