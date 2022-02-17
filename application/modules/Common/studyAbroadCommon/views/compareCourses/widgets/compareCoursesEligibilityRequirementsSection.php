<div>        
<h2 class="compare-detial-title">Eligibilty requirement</h2>
<table border="0" cellpadding="0" cellspacing="0" class="compare-head-table">
    <?php if($Marks12thFlag){ ?>
    <tr>
        <td width="25%"><div class="compare-detail-content"><strong>12th class marks</strong></div></td>
       <?php foreach ($courseDataObjs as $courseObj) { 
        $boardCutOff12 =  $courseEligibilityData[$courseObj->getId()]['12thCutoff'];?>
        <td width="25%">
            <?php if(!empty($boardCutOff12) && $boardCutOff12!="0"){?>
            <div class="compare-detail-content">
                        <?php echo ($boardCutOff12=="-1")?"No specific cutoff mentioned":($boardCutOff12)."%"; ?>
            </div>
            <?php } else {echo"------";} ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?>
        <td width="25%"></td>
        <td width="25%"></td>
        <?php } else if($coursesCount == 2){?>
        <td width="25%"></td>
        <?php } ?>
    </tr>
    <?php } ?>
    <?php if($MarksGradFlag){ ?>
    <tr>
        <td><div class="compare-detail-content"><strong>Graduation marks</strong></div></td>
        <?php foreach ($courseDataObjs as $courseObj) { 
                $bachelorCutoff =  $courseEligibilityData[$courseObj->getId()]['bachelorCutoff']; ?>
        <td width="25%">
            <?php if( !empty($bachelorCutoff) && $bachelorCutoff!="0.00"){ ?>
            <div class="compare-detail-content">
            <?php if($bachelorCutoff=="-1.00") echo "No specific cutoff mentioned";
             else if($courseEligibilityData[$courseObj->getId()]['bachelorScoreUnit']=="Percentage")echo intval($bachelorCutoff)."%"; 
             else echo $bachelorCutoff." ".$courseEligibilityData[$courseObj->getId()]['bachelorScoreUnit']; ?>
            </div>
            <?php } else { echo "------"; } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?>
        <td width="25%"></td>
        <td width="25%"></td>
        <?php } else if($coursesCount == 2){?>
        <td width="25%"></td>
        <?php } ?>
    </tr>
    <?php } ?>
    <?php if($MarksPostGradFlag){ ?>
     <tr>
        <td><div class="compare-detail-content"><strong>Post Graduation marks</strong></div></td>
        <?php foreach ($courseDataObjs as $courseObj) { 
            $pgCutoff =  $courseEligibilityData[$courseObj->getId()]['pgCutoff']; ?>
        <td width="25%">
            <?php if(!empty($pgCutoff) && $pgCutoff!=""){ ?>
            <div class="compare-detail-content">
                <?php  echo $pgCutoff."%"; ?>
        </div>
        <?php } else{ echo "------"; }?>
        </td>
        <?php } ?>

        <?php if($coursesCount == 1){?>
        <td width="25%"></td>
        <td width="25%"></td>
        <?php } else if($coursesCount == 2){?>
        <td width="25%"></td>
        <?php } ?>
    </tr>
    <?php } ?>
    <?php if($workXPFlag){ ?>
    <tr>
        <td><div class="compare-detail-content"><strong>Work experience </strong></div></td>
        <?php foreach ($courseDataObjs as $courseObj) { 
            $workExperniceValue =  $courseEligibilityData[$courseObj->getId()]['workExperniceValue'];?>
        <td width="25%">
            <?php if(isset($workExperniceValue) && $workExperniceValue!="0"){ ?>
            <div class="compare-detail-content">                
            <?php echo ($workExperniceValue=="")?"No work experience mentioned":$workExperniceValue." years"; ?>
            </div>    
            <?php } else{ echo "------";} ?>        
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?>
        <td width="25%"></td>
        <td width="25%"></td>
        <?php } else if($coursesCount == 2){?>
        <td width="25%"></td>
        <?php } ?>
    </tr>
    <?php } ?>
    <tr>
        <td><div class="compare-detail-content"><strong>Exam score</strong></div></td>
        <?php foreach ($courseDataObjs as $courseObj) { 
             $eligibilityExams = $eligibilityExamsArray[$courseObj->getId()]; ?>
        <td width="25%">
            <?php if(!empty($eligibilityExams)){ ?>
            <div class="compare-detail-content"> 
                <?php foreach($eligibilityExams as $examObj) {
                if($examObj->getId()!= -1){?>
                <?php echo htmlentities($examObj->getName());?> : <?php if($examObj->getCutOff()!="N/A"){ echo($examObj->getCutOff()); } 
                                                                    else{  echo "ACCEPTED"; }?>
                <p class="comp-exam-detail"><?php if($examObj->getComments()!=""){ echo($examObj->getComments()); } ?></p>
                <?php } } ?>
            </div>
            <?php } else {echo "------";} ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?>
        <td width="25%"></td>
        <td width="25%"></td>
        <?php } else if($coursesCount == 2){?>
        <td width="25%"></td>
        <?php } ?>
        
    </tr>
     <?php if($examsFlag){ ?>
    <tr>
        <td><div class="compare-detail-content"><strong>Additional Info</strong></div></td>        
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
            <?php } else { echo "------"; } ?>
            </td>
            <?php } ?>
        
       <?php if($coursesCount == 1){?>
        <td></td>
        <td></td>
        <?php } else if($coursesCount == 2){?>
        <td></td>
        <?php } ?>
    </tr>
    <?php } ?>
</table>  
</div>