 <?php if(!empty($courseDataObjs)) { ?>  
    <div>
        <h2 class="compare-detial-title">About course</h2>
        <table border="0" cellpadding="0" cellspacing="0" class="compare-head-table">
        <tr>
            <td width="25%"><div class="compare-detail-content"><strong>Course description</strong></div></td>
            <?php foreach ($courseDataObjs as $courseObj) { ?>
            <td width="25%">
                <?php $desc =  $courseObj->getCourseDescription();
                 if(!empty($desc)) { ?>
                    <div class="compare-detail-content smallData<?php echo $courseObj->getId();?>">
                        <?php echo $desc;?>
                    </div>                            
                    <div style="display: none;" class="compare-detail-content fullData<?php echo $courseObj->getId();?>">
                       <?php echo $desc; ?>
                    </div>
                <?php } else{ echo "------"; } ?>
            </td>
            <?php } ?>
            <?php if($coursesCount == 1){?>
            <td width="25%"></td>
            <td width="25%"></td>
            <?php } else if($coursesCount == 2){?>
            <td width="25%"></td>
            <?php } ?>
        </tr>
        <tr>
            <td><div class="compare-detail-content"><strong>Course duration</strong></div></td>
            <?php foreach ($courseDataObjs as $courseObj) { ?>
            <?php  $duration = $courseObj->getDuration();?>
            <td>
            <?php if(!empty($duration)) { ?>
            <div class="compare-detail-content"><?php echo htmlentities($courseObj->getDuration()->getExactDurationValue().' '.$courseObj->getDuration()->getDurationUnit()); ?></div>
            <?php } else { echo "------";} ?>
            </td>
            <?php } ?>
            <?php if($coursesCount == 1){?>
            <td></td>
            <td></td>
            <?php } else if($coursesCount == 2){?>
            <td></td>
            <?php } ?>
        </tr>
        <tr>
            <td><div class="compare-detail-content"><strong>Course type</strong></div></td>
            <?php foreach ($courseDataObjs as $courseObj) { ?>
            <?php  $courseLevel = $courseObj->getCourseLevel1Value(); ?>
            <td>
            <?php if(!empty($courseLevel)) { ?>
            <div class="compare-detail-content"><?php echo $courseObj->getCourseLevel1Value(); ?> Program</div>
            <?php } else { echo "------";} ?>
            </td>
            <?php } ?>
            <?php if($coursesCount == 1){?>
            <td></td>
            <td></td>
            <?php } else if($coursesCount == 2){?>
            <td></td>
            <?php } ?>
        </tr>
        <?php if($applicationDeadlineFlag){ ?>
        <tr>
            <td><div class="compare-detail-content"><strong>Application deadline</strong></div></td>
            <?php foreach ($courseDataObjs as $courseObj) { ?>
            <?php $universityCourseProfileId = $applicationProcessData['courseProcessData'][$courseObj->getId()]['universityCourseProfileId'];
                  $tempArray =  $applicationProcessData['submissionDateData'][$universityCourseProfileId]; ?>
            <td> 
            <?php if(!empty($tempArray)){ ?>                      
            <div class="compare-detail-content">
                <p><?php foreach ($tempArray as  $value) {
                    $date = date("jS M, Y",strtotime($value['applicationSubmissionLastDate']));    
                    echo $date."</br>";
                }?></p>                       
            </div>
            <?php } else { echo "------";}?>
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
        <?php if($admissionTypeFlag){ ?>
        <tr>
            <td><div class="compare-detail-content"><strong>Admission type</strong></div></td>
             <?php foreach ($courseDataObjs as $courseObj) { 
              $admissionType = $applicationProcessData['courseProcessData'][$courseObj->getId()]['admissionType'];?>
             <td>
                <?php if(!empty($admissionType)) {
                    $admissionType = str_replace("Deadline", "", $admissionType);
                    $admissionType = str_replace("Admission", "", $admissionType); ?>
                <div class="compare-detail-content"><?php echo ucfirst($admissionType); ?> Admission</div>
                <?php  } else { echo "------"; } ?>
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
<?php } ?>