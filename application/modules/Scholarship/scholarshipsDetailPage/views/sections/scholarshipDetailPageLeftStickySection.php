<?php
	if($scholarshipObj->getCategory() == 'external'){
		$applicableForText = 'All universities in';
	}else{
		$applicableForText = ucfirst($universityName).' in ';
	}
?>
<div class="dtls-sidebar" id="dtls-sidebar">
            <h2 class="titl-main fnt-16 tl-mn stcky-hd"><?php echo $scholarshipObj->getName();?></h2>
	   	  	<ul class="schrls-dtls">
                <li>
                <?php $countCountries = count($applicableCountries)-1; ?>
	   	  			<p class="max-cell f14-fnt">Applicable for</p>
	   	  			<p class="max-cell f14-fnt"><?php echo $applicableForText.' '.$applicableCountries[0]; if(count($applicableCountries) > 1){ ?> <a class='a-link mblk' id='applicableCountries' href='javascript:void(0)'> +<?php echo $countCountries; ?> more</a><?php } ?></p>
	   	  		</li>
	   	  		<?php if($scholarshipObj->getAmount()->getConvertedTotalAmountPayout() > 0){ ?>
	   	  		<li>
	   	  			<p class="max-cell f14-fnt">Max scholarship per student</p>
	   	  			<p class="max-cell f14-fnt"><?php echo 'Rs '.moneyAmountFormattor($scholarshipObj->getAmount()->getConvertedTotalAmountPayout(),1,1).'/-'; ?></p>
	   	  		</li>
	   	  		<?php } 
	   	  			if($scholarshipObj->getDeadline()->getNumAwards() > 0 || $scholarshipObj->getDeadline()->getNumAwards() == -1){
	   	  		?>
	   	  		<li>
	   	  			<p class="max-cell f14-fnt">No. of students to be awarded</p>
	   	  			<p class="max-cell f14-fnt"><?php echo $scholarshipObj->getDeadline()->getNumAwards() == -1 ? 'Varies' :  moneyAmountFormattor($scholarshipObj->getDeadline()->getNumAwards(),1,1); ?></p>
	   	  		</li>
	   	  		<?php } ?>
                <li>
	   	  			<p class="max-cell f14-fnt">Course Level</p>
	   	  			<?php 
						if(count(array_intersect(array('Bachelors','Bachelors Diploma','Bachelors Certificate','all'), $scholarshipObj->getHierarchy()->getCourseLevel())) || in_array('Bachelors', $courseLevel)){ $courseLevelNew[] = 'Bachelors';}

						if(count(array_intersect(array('Masters','PhD','Masters Diploma','Masters Certificate','all'), $scholarshipObj->getHierarchy()->getCourseLevel() ) ) || in_array('Masters', $courseLevel) || in_array('PhD', $courseLevel)){ $courseLevelNew[] = 'Masters';}

	   	  			?>
	   	  			<p class="max-cell f14-fnt"><?php echo implode(' & ', array_unique($courseLevelNew)); ?></p>
	   	  		</li>
	   	  		<li>
	   	  		<?php
	   	  				$data['intakeYear'] = array();
	   	  				$intakeYears = $scholarshipObj->getApplicationData()->getIntakeYears();
	   	  				foreach ($intakeYears as $key => $year) {
	   	  					$date=date_create_from_format("Y-m-d",$year);
	   	  					$data['intakeYear'][] = date_format($date,"M Y");
	   	  				}
	   	  		?>
	   	  			<p class="max-cell f14-fnt">Intake year</p>
	   	  			<p class="max-cell f14-fnt"><?php echo $intakeYearToShow; if(count($data['intakeYear']) > 1){ ?> <a class="a-link mblk" id="intakeYear"  href='javascript:void(0)'> +<?php echo count($data['intakeYear'])-1; ?> more</a><?php } ?></p>
                </li>
                <?php if($scholarshipObj->getDeadline()->getApplicationEndDate() != ''){ 
                		$endDate = date_create_from_format("Y-m-d", $scholarshipObj->getDeadLine()->getApplicationEndDate());
                	?>
                <li>
	   	  			<p class="max-cell f14-fnt">Final application deadline</p>
	   	  			<p class="max-cell f14-fnt"><?php echo date_format($endDate,"d M Y"); ?></p>
	   	  		</li>
	   	  		<?php } ?>
	   	  		<li>
	   	  			<p class="max-cell f14-fnt">Type of scholarships</p>
	   	  			<?php $typeOfScholarship = $scholarshipObj->getScholarshipType(); if($typeOfScholarship == 'both'){ $typeOfScholarship = 'Need, Merit'; } ?>
	   	  			<p class="max-cell f14-fnt"><?php echo ucfirst($typeOfScholarship); ?></p>
	   	  		</li>

	   	  	</ul>

   	  	    <?php 
   	  	    if($scholarshipObj->getApplicationData()->getBrochureUrl() != ''){
   	  	    ?>
	   	  	<div class="align_center">
	   	  	  <a class="btns btn-prime btn-width schlrs-db" schrId="<?php echo $scholarshipObj->getId() ?>" tKey="<?php echo $trackingIdForLeftBrochure;?>" uAction="schr_db" actionType="SLP_DOWNLOAD_BROCHURE_LEFT_SECTION">Download Brochure</a>	
                          <a class="btns btn-trans btn-width schlrs-apply" schrId="<?php echo $scholarshipObj->getId() ?>" tKey="<?php echo $trackingIdForLeftApply;?>" uAction="schr_apply" actionType="SLP_APPLY_NOW_LEFT_SECTION">Apply Now</a>
	   	  	</div>
   	  	    <?php
   	  	    }
   	  	    ?>
	   	  	<?php $lastModifiedDate = date_create_from_format('Y-m-d H:i:s',$scholarshipObj->getLastModifiedDate()); ?>
                        <p class="last-up m-top5"> <a href="javaScript:void(0);" id="reportIncorrectLink" scholarshipId="<?php echo $scholarshipObj->getId() ?>" class="a-link mblk">Report Incorrect information</a> <span class="block">Last updated on <?php echo date_format($lastModifiedDate,'j-M-Y'); ?></span>  </p>
	   	  	<?php $this->load->view('scholarshipsDetailPage/sections/moreApplicableCountries'); ?>
	   	  	<?php $this->load->view('scholarshipsDetailPage/sections/intakeYear'); ?>
</div>
