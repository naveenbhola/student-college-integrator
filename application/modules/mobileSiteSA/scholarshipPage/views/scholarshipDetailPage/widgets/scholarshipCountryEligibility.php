<h2 class="titl-main">Scholarship Eligibility</h2>
<?php 


$requiredExams  = $scholarshipObj->getEligibility()->getExams();
$educationExams = $scholarshipObj->getEligibility()->getEducation();
$interviewRequired = $scholarshipObj->getEligibility()->interviewRequired();
$workExpRequired = $scholarshipObj->getEligibility()->workXPRequired();

if (empty($requiredExams) && empty($educationExams) && $workExpRequired == '' && $interviewRequired == '') { ?>
<p class="mb15">No specific eligibility requirement mentioned on the Scholarship site, student profile will be evaluated on a case by case basis.</p>
<?php
}

$restrictions = $scholarshipObj->getSpecialRestrictions()->getRestrictions();
if(!empty($restrictions) || $scholarshipObj->getSpecialRestrictions()->getDescription() != '')
{
?>
<h3 class="sub-titl">Special Restrictions</h3>
<ul>
    <?php 
    foreach ($restrictions as $restrictionId) {
    	echo '<li>'.$specialRestrictions[$restrictionId].'</li>';
    }
    ?>
</ul>
<p><?php echo $scholarshipObj->getSpecialRestrictions()->getDescription(); ?></p>
<?php 
}
if($workExpRequired == 1){
    $workExp = $scholarshipObj->getEligibility()->getWorkXP();
}

if(!empty($requiredExams) || $interviewRequired == 1 || $workExpRequired == 1 || !empty($educationExams))
{
?>
<h3 class="sub-titl">Exams Details</h3>
<table class="col-table">
        <thead class="thead-default">
            <tr>
                <th width="40%">Exam name</th>
                <th width="60%">Indicates if exam is required and cutoff(if any)</th>
            </tr>
        </thead>
        <tbody class="tbody-default">
            <?php 
            foreach ($requiredExams as $value) {
            ?>
            	<tr>
	                <td><strong><?=$examMasterList[$value['examId']]?></strong></td>
	                <td>Required<?php echo $value['cutoff']!='N/A' ? ' (Min score '.$value['cutoff'].')':'';?></td>
	            </tr>
            <?php 
            }
            foreach ($educationExams as $value) {
            ?>
                <tr>
                    <td><strong><?=$value['educationLevel']?></strong></td>
                    <td>Required<?php echo $value['score']>0 ? ' (Min score '.$value['score']:'';
                    echo (($value['scoreType'] == 'gpa') ? ' GPA' : '%').')'
                    ?></td>
                </tr>
            <?php 
            }
            if($interviewRequired == '1'){
            ?>
                <tr>
                    <td><strong>Interview</strong></td>
                    <td>Required</td>
                </tr>
            <?php 
            }
            if($workExpRequired == '1'){
            ?>
            	<tr>
                    <td><strong>Work Experience</strong></td>
                    <td>Required (Min <?=$workExp.(($workExp==1)?' year':' years')?>)</td>
	        </tr>
            <?php 
            }
            ?>
        </tbody>
</table>
<?php 
}
?>
<?php 
if($scholarshipObj->getEligibility()->getDescription() != '') {
?> 
<h3 class="sub-titl">Eligibility</h3>
    <p><?php echo $scholarshipObj->getEligibility()->getDescription(); ?></p>
<?php } ?>

<?php
if($scholarshipObj->getEligibility()->getPreference() != ''){
?>
<h3 class="sub-titl">Preference will be given to following candidates</h3>
<p><?php echo $scholarshipObj->getEligibility()->getPreference(); ?></p>
<?php } ?>