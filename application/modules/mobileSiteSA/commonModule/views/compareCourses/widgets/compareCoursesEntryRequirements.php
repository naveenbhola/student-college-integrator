<table border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<th colspan="2" class="heading-bg">
        	<div class="compare-detail-content">
       	    	<h2><strong>Eligibility Requirements</strong></h2>
            </div>
        </th>
    </tr>
    <?php if($Marks12thFlag){?>
    <tr>
    	<td colspan="2">
            <div class="compare-detail-content"><strong>12th Class Marks</strong></div>
        </td>
    </tr>
    <tr>
    <?php foreach ($courseDataObjs as $courseObj) { 
          $boardCutOff12 =  $courseEligibilityData[$courseObj->getId()]['12thCutoff'];?>
    	<td><?php if(!empty($boardCutOff12) && $boardCutOff12!="0"){?>
            <div class="compare-detail-content"><p><?php echo ($boardCutOff12=="-1")?"No specific cutoff mentioned":($boardCutOff12)."%"; ?></p></div>
        <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>            
        </td>
    <?php } ?>
    <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <!--grad marks sections-->
    <?php if($MarksGradFlag){ ?>   
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Graduation Marks</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) { 
                $bachelorCutoff =  $courseEligibilityData[$courseObj->getId()]['bachelorCutoff']; ?>
    	<td>
            <?php if( !empty($bachelorCutoff) && $bachelorCutoff!="0.00"){ ?>
        	<div class="compare-detail-content">
        		<p><?php if($bachelorCutoff=="-1.00") echo "No specific cutoff mentioned";
             else if($courseEligibilityData[$courseObj->getId()]['bachelorScoreUnit']=="Percentage")echo intval($bachelorCutoff)."%"; 
             else echo $bachelorCutoff." ".$courseEligibilityData[$courseObj->getId()]['bachelorScoreUnit']; ?></p>
            </div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <!--post grad marks sections-->
    <?php if($MarksPostGradFlag){ ?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content"><strong>Post Graduation Marks</strong></div>
        </td>
    </tr>
    <tr><?php foreach ($courseDataObjs as $courseObj) { 
            $pgCutoff =  $courseEligibilityData[$courseObj->getId()]['pgCutoff']; ?>
    	<td>
            <?php if(!empty($pgCutoff) && $pgCutoff!=""){ ?>
            <div class="compare-detail-content"><p><?php  echo $pgCutoff."%"; ?></p></div>
            <?php } else{ ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php }?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <!--work experience sections-->
    <?php if($workXPFlag){
 ?>
    <tr>
        <td colspan="2">
            <div class="compare-detail-content">
                <strong>Work Experience</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {
          
            $workExperniceValue =  $courseEligibilityData[$courseObj->getId()]['workExperniceValue'];?>
        <td>
            <?php if(isset($workExperniceValue) && $workExperniceValue!="0"){ ?>
            <div class="compare-detail-content">
                    <p><?php echo ($workExperniceValue=="")?"No work experience mentioned":$workExperniceValue." years"; ?></p>
            </div>
           <?php } else{ ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Exam Score</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) { 
             $eligibilityExams = $eligibilityExamsArray[$courseObj->getId()]; ?>
    	<td>
            <?php if(!empty($eligibilityExams)){ ?>
        	<div class="compare-detail-content">
                <?php foreach($eligibilityExams as $examObj) {
                if($examObj->getId()!= -1){?>
                <p><?php echo htmlentities($examObj->getName());?> : <?php if($examObj->getCutOff()!="N/A"){ echo($examObj->getCutOff()); } 
                                                                    else{  echo "Accepted"; }?></p>
                <?php if($examObj->getComments()!=""){ echo "<p>".($examObj->getComments())."</p>"; } ?>
                <?php } } ?>
            </div>
            <?php } else {?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php if($examsFlag){ ?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Additional Info</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {
                $courseAttr = $courseObj->getAttributes();
                $examInfo   = $courseAttr['examRequired'];?>
    	<td>
            <?php  if($applicationProcessData['courseProcessData'][$courseObj->getId()]['additionalRequirement']!="" &&  $applicationProcessDataFlag){?>
        	<div class="compare-detail-content">
        		<p><?php  echo($applicationProcessData['courseProcessData'][$courseObj->getId()]['additionalRequirement']); ?></p>
            </div>
            <?php }
            else if(!$applicationProcessDataFlag && $examInfo!=""){?>
            <div class="compare-detail-content">
                <p><?php echo($examInfo->getValue()); ?></p>
            </div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
</table>