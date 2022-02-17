<?php  if(!empty($applicationProcessData['courseProcessData']) && $applicationProcessDataFlag == 1) { ?>
<div>
<h2 class="compare-detial-title">Application process</h2>
<table border="0" cellpadding="0" cellspacing="0" class="compare-head-table">
    <?php if($sopFlag){?>
    <tr>
        <td width="25%"><div class="compare-detail-content"><strong>SOP</strong></div></td>
        <?php foreach ($courseDataObjs as $courseObj) {?>    
        <td width="25%">
            <?php if($applicationProcessData['courseProcessData'][$courseObj->getId()]['sopRequired']=='1') {?>
            <div class="compare-detail-content"><?php echo "Required"; ?></div>
            <?php } elseif($applicationProcessData['courseProcessData'][$courseObj->getId()]['sopRequired']=='0') {?>
            <div class="compare-detail-content">
            <?php  echo "Not Required";  ?>
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

    <?php if($lorFlag){?>
    <tr>
        <td><div class="compare-detail-content"><strong>LOR</strong></div></td>
         <?php foreach ($courseDataObjs as $courseObj) {?>    
        <td>
            <?php if($applicationProcessData['courseProcessData'][$courseObj->getId()]['lorRequired']=='1'){ ?>
            <div class="compare-detail-content">
            <?php echo "Required"; ?>
            </div>
            <?php } elseif($applicationProcessData['courseProcessData'][$courseObj->getId()]['lorRequired']=='0'){ ?>
            <div class="compare-detail-content">
            <?php echo "Not Required"; ?>
            </div>
            <?php }else { echo "------"; }?>
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
    <?php if($essayFlag){?>
    <tr>
        <td><div class="compare-detail-content"><strong>Admission Essay</strong></div></td>
        <?php foreach ($courseDataObjs as $courseObj) { ?>
        <td>
        <?php if($applicationProcessData['courseProcessData'][$courseObj->getId()]['essayRequired']=='1'){?>    
        <div class="compare-detail-content"><?php echo "Required"; ?></div>
        <?php } elseif($applicationProcessData['courseProcessData'][$courseObj->getId()]['essayRequired']=='0'){ ?>
        <div class="compare-detail-content"><?php echo "Not Required"; ?></div>
        <?php }else{ echo "------"; } ?>
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
    <?php if($cvFlag){?>
    <tr>
        <td><div class="compare-detail-content"><strong>Student CV</strong></div></td>
       <?php foreach ($courseDataObjs as $courseObj) {?>    
        <td>
        <?php if($applicationProcessData['courseProcessData'][$courseObj->getId()]['cvRequired']=='1'){ ?>
        <div class="compare-detail-content"><?php echo "Required"; ?></div>
        <?php } elseif($applicationProcessData['courseProcessData'][$courseObj->getId()]['cvRequired']=='0'){ ?>
        <div class="compare-detail-content"><?php echo "Not Required"; ?></div>
        <?php }else { echo "------"; } ?>
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
    <?php if($applicationFeesFlag){?>
    <tr>
        <td><div class="compare-detail-content"><strong>Application fees</strong></div></td>
        <?php foreach ($courseDataObjs as $courseObj) {?>    
        <td><?php if($applicationProcessData['courseProcessData'][$courseObj->getId()]['applicationFeeDetail']=='1' && !empty($applicationProcessData['courseProcessData'][$courseObj->getId()]['convertedFeeDetail'])) { ?>
            <div class="compare-detail-content">
                <?php  echo $applicationProcessData['courseProcessData'][$courseObj->getId()]['convertedFeeDetail'];   ?>
            </div>
            <?php }else {echo "------";} ?>
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