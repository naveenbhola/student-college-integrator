<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cover Page</title>
<link rel="stylesheet" type="text/css" href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('auto-eBrochure'); ?>" />
</head>
<body>
	<?php 
	$course_attributes = $course->getDescriptionAttributes();
	$course_description = "";
	$course_eligibility = "";
	$admission_procedure = "";
	foreach($course_attributes as $object) {	
		if($object->getName() == 'Course Description') {			
			$tidyObj = new tidy();
			$tidyObj->parseString($object->getValue(),array('show-body-only'=>true),'utf8');
			$tidyObj->cleanRepair();
			$course_description = $tidyObj;
		} else if($object->getName() == 'Eligibility') {			
			$tidyObj = new tidy();
			$tidyObj->parseString($object->getValue(),array('show-body-only'=>true),'utf8');
			$tidyObj->cleanRepair();
			$course_eligibility = $tidyObj;
		} else if($object->getName() == 'Admission Procedure') {			
			$tidyObj = new tidy();
			$tidyObj->parseString($object->getValue(),array('show-body-only'=>true),'utf8');
			$tidyObj->cleanRepair();
			$admission_procedure = $tidyObj;
		}
		
		unset($tidyObj);
	}
        ?>	
	<?php 
	$description = "";
        $infrastructure_teaching = "";
	$top_faculty = "";
	$descriptions_array = $institute->getDescriptionAttributes();
	foreach($descriptions_array as $obj) {
                if($obj->getName() == 'Institute Description') {
			$description = $obj->getValue();
		} else if($obj->getName() == 'Infrastructure / Teaching Facilities') {
			$infrastructure_teaching = $obj->getValue();
		} else if($obj->getName() == 'Top Faculty') {
			$top_faculty = $obj->getValue();
		}
 	}
	$salientFeatures =  $course->getSalientFeatures();	
	$classTimings = $course->getClassTimings();
	$salientArr = array();
        if(count($salientFeatures) >0 || count($classTimings)>0){
		foreach($salientFeatures as $sf){
			$salientArr[] = langStr('feature_'.$sf->getName().'_'.$sf->getValue());
		}
		foreach($classTimings as $sf){
			$salientArr[] = langStr($sf);
		}
	}
	?>
	<div id="brochure-wrapper">
    	<div id="bot-patternssss">
    	<div id="cover-header">
        	<div class="inst-name">
            	<h2><?php echo $institute->getName();?></h2>
                <p><?php echo $institute->getMainLocation()->getCity()->getName();?></p>
            </div><?php if($institute->getLogo() != ""){ ?>
            <div class="inst-logo"><img width="110" src="<?php echo $institute->getLogo();?>" alt="" /></div>
	    <?php	} ?>
        </div>
	<div>
	<?php if(!empty($description)):?>	
        <div class="page-title">About <?php echo $institute->getName();?></div>
        
        <div id="bro-content"><div id="bro-inner-content">
        	<p><?php echo $description;?></p></div>
        </div>
       <?php endif;?> 
	<?php if($institute->getJoinReason()->getDetails()):
	//$summary = $institute->getJoinReason()->getDetails();
	$summary = new tidy();
	$summary->parseString($institute->getJoinReason()->getDetails(),array('show-body-only'=>true),'utf8');
	$summary->cleanRepair();
	?>
        <div style="margin-top:20px;">        	
        		<h4>Top Reasons to join this institute:</h4>
                	<?php echo str_ireplace('Top Reasons to Join this Institute:','',$summary);?>	
        </div>
        <?php endif;?>
	</div>
        </div>
    </div>
    <div id="brochure-wrapper" style="margin-top:50px;">
    <div id="top-pattern">
        <!--div id="bot-pattern2"-->
	<div>
    	<div id="bro-inner-content">
		<?php if($infrastructure_teaching):?>
        	<div class="content-box">
                <h4>Infrastructure / Teaching Facilities</h4>
                <p><?php echo $infrastructure_teaching;?></p>
            </div>
	    <?php endif;?>	
            <?php if(count($salientArr)>0):?>
            <div class="content-box">
                <h4>Features of the institute:</h4>
                <ol class="bullet-items">
		<?php foreach($salientArr as $slnt):?>
                	<li><?php echo $slnt;?></li>                 
		<?php endforeach;?>
                </ol>
            </div>
            <?php endif;?>
            <?php if(!empty($top_faculty)):?>
            <div class="content-box" style="margin:0">
                <h4>Top Faculty</h4>
                <?php echo $top_faculty;?>
            </div>
	    <?php endif;?>
            <div class="clearFix"></div>
        </div>
        </div>
        </div>	
	</div>
	
	<div id="brochure-wrapper">
    	<div id="top-pattern">
        <div id="bot-pattern2asas">
    	<div id="bro-inner-content">
        	<div class="abt-course"></div>
		<div>
        	<div class="content-box" style="margin-top:65px">
            	<h2><?=html_escape($course->getName())?><br />
		<?php
			echo $course->getDuration()->getDisplayValue() ? $course->getDuration()->getDisplayValue() : "";
			echo ($course->getDuration()->getDisplayValue() && $course->getCourseType()) ? ", ".$course->getCourseType() : ($course->getCourseType() ? $course->getCourseType(): "");
			echo ($course->getCourseLevel() && ($course->getCourseType() || $course->getDuration()->getDisplayValue())) ? ", ".$course->getCourseLevel() : ($course->getCourseLevel() ? $course->getCourseLevel() : "");
		?>
		</h2>
		<?php
		$approvalsAndAffiliations = array();
		$approvals = $course->getApprovals();
		foreach($approvals as $approval) {
			$approvalsAndAffiliations[] = langStr('approval_'.$approval);
		}
		$affiliations = $course->getAffiliations();
		foreach($affiliations as $affiliation) {
		$approvalsAndAffiliations[] = langStr('affiliation_'.$affiliation[0].'_detailed',$affiliation[1]);	
		}
		?>	
		<?php if(count($approvalsAndAffiliations)>0):?>
                <p><?php echo implode(', ',$approvalsAndAffiliations);?></p>
		<?php endif;?>
                <ol class="bullet-items2">
			<?php if($accredited = $course->getAccredited()):?>
                	<li><strong>Accreditation:</strong><?=html_escape($accredited)?></li>
			<?php endif;?>
		    <?php if($course->getFees($course->getMainLocation()->getLocationId())->getValue($course->getMainLocation()->getLocationId())): ?>	
                    <li><strong>Fees:</strong> <?=$course->getFees($course->getMainLocation()->getLocationId()) ;?></li>
                    <?php endif;?>
		    <?php if($course->getTotalSeats() || $course->getManagementSeats() || $course->getGeneralSeats() || $course->getReservedSeats()):
		    ?>				
                    <li><strong>Seats:</strong>
		    <?php
			$seatsArray = array();
			if($course->getTotalSeats()){
				$seatsArray[] = "Total - ".$course->getTotalSeats();
			}
			if($course->getGeneralSeats()){
				$seatsArray[] = "General - ".$course->getGeneralSeats();
			}
			if($course->getManagementSeats()){
				$seatsArray[] = "Management - ".$course->getManagementSeats();
			}
			if($course->getReservedSeats()){
				$seatsArray[] = "Reserved - ".$course->getReservedSeats();
			}
			echo implode('<span> | </span> ',$seatsArray);
		    ?>	
		    </li>
		    <?php endif;?>
		    <?php
			$exams = $course->getEligibilityExams();
			if(count($exams) > 0 || $course->getOtherEligibilityCriteria()) : ?>
			<?php
				if($institute->getInstituteType() == "Test_Preparatory_Institute"){
				$lebel = "Exams Prepared for:";
			?>   
			<?php
				}else{
			
					$lebel = "Eligibility:";
			}
			?>
			<?php
				$examAcronyms = array();
				foreach($exams as $exam) {
					$tempExam = $exam->getAcronym();
					if($exam->getMarks()){
						$tempExam .= " - ".$exam->getMarks()." ".titleCase(str_replace("_"," ",$exam->getMarksType()));
					}
					if($exam->getPracticeTestsOffered()) {
						$tempExam = $exam->getAcronym()."(".$exam->getPracticeTestsOffered().")";
					}
					$examAcronyms[] = $tempExam;
				}
				if($course->getOtherEligibilityCriteria()){
					$examAcronyms[] = html_escape($course->getOtherEligibilityCriteria());
				}
		     ?>
		    <?php endif; ?>		
		    <?php if(count($examAcronyms)>0):?> 	
                    <li><strong>Eligibility:</strong>
		    <?php if($course_eligibility){
		    	echo trim($course_eligibility)."<br/>";		
		    }
		    ?>
                    <?php echo implode(' <span>|</span> ',$examAcronyms);?>
                    </li>
		    <?php endif;?>
		    <?php if($admission_procedure):?>		
                    <li><strong>Admission Procedure:</strong>
		    <?php echo trim($admission_procedure);?>	
		    </li>
		    <?php endif;?>	
		    <?php 
                    $form_sub_date = $course->getDateOfFormSubmission($course->getMainLocation()->getLocationId());
                    $result_decl_date = $course->getDateOfResultDeclaration($course->getMainLocation()->getLocationId());
                    $course_com_date = $course->getDateOfCourseComencement($course->getMainLocation()->getLocationId());
                    if((!empty($form_sub_date) && $form_sub_date!='0000-00-00 00:00:00') || (!empty($result_decl_date) && $result_decl_date!='0000-00-00 00:00:00') || (!empty($course_com_date) && $course_com_date!='0000-00-00 00:00:00')):?>	
                    <li>
		    <?php
                    $dates_array = array();
                     if(!empty($form_sub_date) && $form_sub_date!='0000-00-00 00:00:00') {
                     	$dates_array[] = "Form Submission: ".date("d-m-y",strtotime($form_sub_date));
                     } 
                     if(!empty($result_decl_date) && $result_decl_date!='0000-00-00 00:00:00') {
			$dates_array[] = "Declaration of Results: ".date("d-m-y",strtotime($result_decl_date)); 
                     }
                     if(!empty($course_com_date) && $course_com_date!='0000-00-00 00:00:00') {
		     	$dates_array[] = "Course Commencement: ".date("d-m-y",strtotime($course_com_date));	
                     }
                     echo implode(' <span>|</span> ',$dates_array);                
                   ?> 	
		   </li>
		   <?php endif;?>	
                </ol>
                
                <div class="spacer20 clearFix"></div>
		<?php if($course_description != ""):?>
                <?php echo $course_description;?>
		<?php endif;?>
            </div>
<div class="clearFix"></div>
             <?php if(is_array($othercourses) && count($othercourses)>0):?>
            <div class="content-box">
            	<h4>Other Courses offered</h4>
                <ol class="bullet-items" style="width:30%">
			<?php foreach($specializations as $special):?>
                	<li><?php echo $special['SpecializationName'];?></li>
			<?php endforeach;?>
			<?php ?>
                </ol>
            </div>
            <?php endif;?>
	    <?php 
	    $recruiting_companies = $course->getRecruitingCompanies();	
	    ?>	
	    <?php if(is_array($recruiting_companies) && count($recruiting_companies) > 0 && $recruiting_companies[0]->getLogoURL() != ""):?>
            <div class="content-box">
            	<h4>Placement Services</h4>
                <ul class="placement-comp">
                <?php foreach($recruiting_companies as $company){?>
                	<li><img src="<?php echo $company->getLogoURL();?>" alt="<?php echo $company->getName();?>" /></li>  
		<?php } ?>
                </ul>
            </div>
            <?php endif;?>
            <div class="clearFix"></div>
<?php
//*
$salary = $course->getSalary();
if(is_array($salary) && count($salary) && ($salary['min'] != 0 || $salary['avg'] != 0 || $salary['max'] != 0 )){
	
$salaryForIndex = ($salary['max']!=0)?$salary['max']:(($salary['avg']!=0)?$salary['avg']:$salary['min']);

if($salaryForIndex%5!=0){
	$graphIndex = $salaryForIndex +'10'-($salaryForIndex%10);
}else{
	$graphIndex = $salaryForIndex;
}

$salary['min'] = $salary['min']/100000;
$salary['max'] = $salary['max']/100000;
$salary['avg'] = $salary['avg']/100000;
?>
<div class="section-cont">
<h4 class="section-cont-title">Salary Statistics <?php if($salary['currency']){ ?>(in <?=$salary['currency']?>)<?php } ?></h4>
<div class="sal-labels">
	<?php if($salary['min']) { ?>
		<label>Min. Salary</label>
	<?php } ?>
	<?php if($salary['avg']) { ?>
		<label>Avg. Salary</label>
	<?php } ?>
	<?php if($salary['max']) { ?>
		<label>Max. Salary</label>
	<?php } ?>
</div>
<ul class="sal-statastics">
	<?php if($salary['min']) { ?>
		<li><span class="min-sal" style="width:<?=max($salary['min']*9100000/$graphIndex,30)?>%">&nbsp;&nbsp;<?=number_format($salary['min'],2, '.', '')?> Lacs</span></li>
	<?php } ?>
	<?php if($salary['avg']) { ?>
		<li><span class="avg-sal" style="width:<?=max($salary['avg']*9100000/$graphIndex,30)?>%">&nbsp;&nbsp;<?=number_format($salary['avg'],2, '.', '')?> Lacs</span></li>
	<?php } ?>
	<?php if($salary['max']) { ?>
		<li><span class="max-sal" style="width:<?=max($salary['max']*9100000/$graphIndex,30)?>%">&nbsp;&nbsp;<?=number_format($salary['max'],2, '.', '')?> Lacs</span></li>
	<?php } ?>
	<li class="sprite-bg scale-bg"></li>
</ul>
<div class="scale-figure">
<label>0</label>
<label><?=number_format(($graphIndex*1)/500000,1)?></label>
<label class="third"><?=number_format(($graphIndex*2)/500000,1)?></label>
<label class="fourth"><?=number_format(($graphIndex*3)/500000,1)?></label>
<label><?=number_format(($graphIndex*4)/500000,1)?></label>
<label class="sixth"><?=number_format(($graphIndex*5)/500000,1)?></label>
</div>
</div>	    
<?php
} ?>
<div class="clearFix spacer20"></div>
<div class="clearFix spacer20"></div>   
<?php
$contactDetail = $course->getMainLocation()->getContactDetail();
if($contactDetail->getContactId() != "" && ($contactDetail->getContactPerson() != "" || $contactDetail->getContactNumbers() != "" || $contactDetail->getContactFax() != ""  || $contactDetail->getContactEmail() != ""  || $contactDetail->getContactWebsite() != "" || $contactDetail->getContactNumbers() != "")) {
?>	    
	<div style="background:#dcdcdc; padding:15px; clear:both; border-radius:8px; -moz-border-radius:8px; -webkit-border-radius:8px">
            	<h6 style="font-size:20px; font-weight:normal; color:#810d20; margin-bottom:10px">Contact Us</h6>
                            <?php if($contactDetail->getContactPerson()){ 
					echo "<p>".$contactDetail->getContactPerson()."<br />";
				}
								
				if($contactDetail->getContactNumbers() != "") {
					echo "Contact No.: ".$contactDetail->getContactNumbers()."<br />";
				}

				if($contactDetail->getContactFax() != "") {
					echo "Fax No.: ".$contactDetail->getContactFax()."<br />";
				}

				if($contactDetail->getContactEmail() != "") {
					echo "Email: ".$contactDetail->getContactEmail()."<br />";
				}

				if($contactDetail->getContactWebsite() != "") {
					echo "Website: ".$contactDetail->getContactWebsite()."<br />";
				}

				if($contactDetail->getContactNumbers() != "") {
					echo "Address: ".$course->getMainLocation()->getAddress()."<br />";
				}
			    ?>
            </div><?php
} 	// End of if($contactDetail->getContactId() != "") {	?>	
        </div>
        <div class="clearFix"></div> 
        </div>
	<?php if(!empty($photo)) :?>	
        <img src="<?php echo $photo;?>" />
	<?php endif;?>
<div class="clearFix"></div>
        </div>
	<div class="clearFix"></div>
        </div>
<div class="clearFix"></div>
    </div>

</body>
</html>
