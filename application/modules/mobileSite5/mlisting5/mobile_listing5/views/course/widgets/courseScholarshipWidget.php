<?php
if(is_array($scholarship) && count($scholarship)>0){ ?>
	<div class="crs-widget gap">
	    <div class="lcard end-col">
	        <h2 class="admisn">Want to Know more about <?=$courseObj->getInstituteName();?> Scholarship details?</h2>
	        <a href="<?php echo $instituteObj->getAllContentPageUrl('scholarships');?>" class="btn-mob-blue" ga-attr="FEES_SCHOLARSHIP_COURSEDETAIL_MOBILE">Read about scholarships</a>  
	    </div> 
	</div>
<?php 
} ?>
