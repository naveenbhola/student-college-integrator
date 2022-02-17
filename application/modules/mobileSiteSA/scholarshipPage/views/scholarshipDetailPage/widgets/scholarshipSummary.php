<?php
if($scholarshipObj->getCategory() == 'external'){
    $applicableForText = 'All Universities in';
}else{
    $applicableForText = ucfirst($organisationName).' in ';
}
$courseLevelNew = array();
if(count(array_intersect(array('Bachelors','Bachelors Diploma','Bachelors Certificate','all'), $scholarshipObj->getHierarchy()->getCourseLevel())) || in_array('Bachelors', $courseLevel)){ 
    $courseLevelNew[] = 'Bachelors';
}

if(count(array_intersect(array('Masters','PhD','Masters Diploma','Masters Certificate','all'), $scholarshipObj->getHierarchy()->getCourseLevel() ) ) || in_array('Masters', $courseLevel) || in_array('PhD', $courseLevel)){ 
    $courseLevelNew[] = 'Masters';
}

?>
<div class="sch-wrap">
    <div class="sch-head">
        <h1><?=$scholarshipObj->getName();?></h1>
        
          <?php if($scholarshipObj->getCategory() == 'internal'){ ?>
            <span>Awarded by : <a href="<?php echo $universityUrl ?>"><?php echo $universityName; ?></a></span>    
          <?php } else { ?>
            <span>Awarded by : <?php echo $scholarshipObj->getOrganisationName(); ?></span>
          <?php } ?>
        
        <ul>
            <li>
                <div class="det-bx flLt">
                <?php $countCountries = count($applicableCountries)-1; ?>
                    <label>Applicable for </label>
                    <strong><?php echo $applicableForText.' '.htmlentities($applicableCountries[0]);if(count($applicableCountries) > 1){ ?> <a class='a-link mblk' id='applicableCountries' data-rel="dialog" data-transition="slide" href='#countryLayer'> +<?php echo $countCountries; ?> more</a><?php } ?></strong>
                </div>
                <?php 
                if($scholarshipObj->getAmount()->getConvertedTotalAmountPayout()){
                ?>
                <div class="det-bx flLt">
                    <label>Max amount per student</label>
                    <strong><?php echo 'Rs '.moneyAmountFormattor($scholarshipObj->getAmount()->getConvertedTotalAmountPayout(), 1, 1).'/-'; ?></strong>
                </div>
                <?php }?>
                <?php 
                if($scholarshipObj->getDeadline()->getNumAwards() != ''){
                ?>
                <div class="det-bx flLt">
                    <label>No. of student awards</label>
                    <strong><?php echo $scholarshipObj->getDeadline()->getNumAwards() == -1 ? 'Varies' : moneyAmountFormattor($scholarshipObj->getDeadline()->getNumAwards(), 1, 1); ?></strong>
                </div>
                <?php 
                }
                ?>
                <?php
                if(!empty($courseLevelNew)){
                ?>
                <div class="det-bx flLt">
                    <label>Course Level</label>
                    <strong><?php echo htmlentities(implode(' & ', array_unique($courseLevelNew))); ?></strong>
                </div>
                <?php 
                }
                ?>
                <div class="det-bx flLt">
                    <label>Intake Year</label>
                    <strong><?php echo $intakeYearToShow; if(count($allIntakeYears) > 1){ ?> <a class="a-link mblk" id="intakeYear" data-rel="dialog" data-transition="slide" href='#intakeYearLayer'> +<?php echo count($allIntakeYears)-1; ?> more</a><?php } ?></strong>
                </div>
                <?php if($scholarshipObj->getDeadline()->getApplicationEndDate() != ''){ 
                        $endDate = date_create_from_format("Y-m-d", $scholarshipObj->getDeadLine()->getApplicationEndDate());
                    ?>
                <div class="det-bx flLt">
                    <label>Final deadline</label>
                    <strong><?php echo date_format($endDate,"d M Y"); ?></strong>
                </div>
                <?php }?>
                <div class="det-bx flLt">
                    <label>Type of scholarship</label>
                    <?php $typeOfScholarship = $scholarshipObj->getScholarshipType(); if($typeOfScholarship == 'both'){ $typeOfScholarship = 'Need, Merit'; } ?>
                    <strong><?php echo htmlentities(ucfirst($typeOfScholarship)); ?></strong>
                </div>
                <div class="clr"></div>
            </li>
        </ul>
    </div>
   
</div>