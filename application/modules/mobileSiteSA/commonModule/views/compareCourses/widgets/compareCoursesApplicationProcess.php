<?php  if(!empty($applicationProcessData['courseProcessData']) && $applicationProcessDataFlag == 1) { ?>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<th colspan="2" class="heading-bg">
        	<div class="compare-detail-content">
       	    	<h2><strong>Application Process</strong></h2>
            </div>
        </th>
    </tr>
    <?php if($sopFlag){?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>SOP</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {?>
    	<td>
            <?php if($applicationProcessData['courseProcessData'][$courseObj->getId()]['sopRequired']=='1') {?>
        	<div class="compare-detail-content"><p>Required</p></div>
            <?php } elseif($applicationProcessData['courseProcessData'][$courseObj->getId()]['sopRequired']=='0') {?>
            <div class="compare-detail-content"><p>Not Required</p></div>
            <?php } else{ ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php }?>
        </td>
        <?php } ?> 
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <?php if($lorFlag){?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>LOR</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {?>
    	<td>
            <?php if($applicationProcessData['courseProcessData'][$courseObj->getId()]['lorRequired']=='1'){ ?>
        	<div class="compare-detail-content"><p>Required</p></div>
            <?php } elseif($applicationProcessData['courseProcessData'][$courseObj->getId()]['lorRequired']=='0'){ ?>
            <div class="compare-detail-content"><p>Not Required</p></div>
            <?php }else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php }?>
        </td>
        <?php } ?> 
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <?php if($essayFlag){?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Admission Essay</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) { ?>
    	<td>
            <?php if($applicationProcessData['courseProcessData'][$courseObj->getId()]['essayRequired']=='1'){?>    
        	<div class="compare-detail-content"><p>Required</p></div>
            <?php } elseif($applicationProcessData['courseProcessData'][$courseObj->getId()]['essayRequired']=='0'){ ?>
            <div class="compare-detail-content"><p>Not Required</p></div>
            <?php }else{ ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?> 
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <?php if($cvFlag){?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Student CV</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {?>
    	<td>
            <?php if($applicationProcessData['courseProcessData'][$courseObj->getId()]['cvRequired']=='1'){ ?>
        	<div class="compare-detail-content"><p>Required</p></div>
            <?php } elseif($applicationProcessData['courseProcessData'][$courseObj->getId()]['cvRequired']=='0'){ ?>
            <div class="compare-detail-content"><p>Not Required</p></div>
            <?php }else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?> 
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <?php if($applicationFeesFlag){?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Application Fee</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {?> 
    	<td>
            <?php if($applicationProcessData['courseProcessData'][$courseObj->getId()]['applicationFeeDetail']=='1' && !empty($applicationProcessData['courseProcessData'][$courseObj->getId()]['convertedFeeDetail'])) { ?>
        	<div class="compare-detail-content"><p><?php  echo $applicationProcessData['courseProcessData'][$courseObj->getId()]['convertedFeeDetail'];   ?></p></div>
            <?php }else {?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?> 
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
</table>
<?php } ?>