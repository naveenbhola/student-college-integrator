<div class="col-bar" id="bottom<?php echo $scholarshipObj->getId();?>">
    <?php 
        if($scholarshipObj->getApplicationData()->getBrochureUrl() != ''){
        ?>
            <div class="mtop-15 clear__margin">
              <a class="btns btn-prime btn-width schlrs-db" schrId="<?php echo $scholarshipObj->getId() ?>" tKey="<?php echo $trackingIdForBottomBrochure;?>" uAction="schr_db" actionType="SLP_DOWNLOAD_BROCHURE_BOTTOM_SECTION">Download Brochure</a>	
              <a class="btns btn-trans btn-width schlrs-apply" schrId="<?php echo $scholarshipObj->getId() ?>" tKey="<?php echo $trackingIdForBottomApply;?>" uAction="schr_apply" actionType="SLP_APPLY_NOW_BOTTOM_SECTION">Apply Now</a>
            </div>
        <?php
        }
    ?>
</div>
                 
                 