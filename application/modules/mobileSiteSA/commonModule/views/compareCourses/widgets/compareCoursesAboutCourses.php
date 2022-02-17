<?php if(!empty($courseDataObjs)) { ?>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
        <th colspan="2" class="heading-bg">
            <div class="compare-detail-content"><h2><strong>About Course</strong></h2></div>
        </th>
    </tr>
    <tr>
    	<td colspan="2">
            <div class="compare-detail-content"><strong>Course Description</strong></div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) { ?>
    	<td class="courseDesc"> <?php $desc =  $courseObj->getCourseDescription();
            if(!empty($desc)) { ?>
            <div class="compare-detail-content smallData<?php echo $courseObj->getId();?>">
        		<?php echo $desc;?>
            </div>
            <div style="display: none;" class="compare-detail-content fullData<?php echo $courseObj->getId();?>"><?php echo $desc; ?></div>
            <?php } else{ ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php }?>
    </tr>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Course Duration</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {
        $duration = $courseObj->getDuration();?>
    	<td>
            <?php if(!empty($duration)) { ?>
        	<div class="compare-detail-content"><p><?php echo htmlentities($courseObj->getDuration()->getExactDurationValue().' '.$courseObj->getDuration()->getDurationUnit()); ?></p></div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Course Type</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) { ?>
        <?php  $courseLevel = $courseObj->getCourseLevel1Value(); ?>
    	<td>
            <?php if(!empty($courseLevel)) { ?>
        	<div class="compare-detail-content"><p><?php echo $courseObj->getCourseLevel1Value(); ?> Program</p></div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php if($applicationDeadlineFlag){ ?>
    <tr>
    	<td colspan="2"><div class="compare-detail-content"><strong>Application Deadline</strong></div></td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) { 
        $universityCourseProfileId = $applicationProcessData['courseProcessData'][$courseObj->getId()]['universityCourseProfileId'];
                  $tempArray =  $applicationProcessData['submissionDateData'][$universityCourseProfileId]; ?>            
    	<td>
            <?php if(!empty($tempArray)){ ?>                      
        	<div class="compare-detail-content">
        		<p><?php foreach ($tempArray as  $value) {
                    $date = date("jS M, Y",strtotime($value['applicationSubmissionLastDate']));    
                    echo $date."</br>";
                }?></p>
            </div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php }?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <?php if($admissionTypeFlag){ ?>
    <tr>
    	<td colspan="2"><div class="compare-detail-content"><strong>Admission Type</strong></div></td>
    </tr>
    <tr>
         <?php foreach ($courseDataObjs as $courseObj) { 
              $admissionType = $applicationProcessData['courseProcessData'][$courseObj->getId()]['admissionType'];?>
    	<td>
        <?php if(!empty($admissionType)) {
                $admissionType = str_replace("Deadline", "", $admissionType); 
                $admissionType = str_replace("Admission", "", $admissionType); ?>
    	       <div class="compare-detail-content"><p><?php echo ucfirst($admissionType); ?> Admission</p></div>
        <?php  } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
</table>
<?php } ?>