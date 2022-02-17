<div class="col-bar" id="belly<?php echo $scholarshipObj->getId();?>" >
    <?php 
    if($scholarshipObj->getApplicationData()->getBrochureUrl() != ''){
    ?>
        <div class="mtop-15 clear_margin">
          <a class="btns btn-prime  btn-width schlrs-db" schrId="<?php echo $scholarshipObj->getId() ?>" tKey="<?php echo $trackingIdForBellyBrochure;?>" uAction="schr_db" actionType="SLP_DOWNLOAD_BROCHURE_BELLY_SECTION">Download Brochure</a>	
          <a class="btns  btn-trans btn-width schlrs-apply" schrId="<?php echo $scholarshipObj->getId() ?>" tKey="<?php echo $trackingIdForBellyApply;?>" uAction="schr_apply" actionType="SLP_APPLY_NOW_BELLY_SECTION">Apply Now</a>
        </div>
    <?php
    }
    ?>
</div>