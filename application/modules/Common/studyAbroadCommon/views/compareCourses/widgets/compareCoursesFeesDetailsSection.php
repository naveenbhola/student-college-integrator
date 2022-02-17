<div>
<h2 class="compare-detial-title">1st year fees</h2>
<table border="0" cellpadding="0" cellspacing="0" class="compare-head-table">
    <tr>
        <td width="25%"><div class="compare-detail-content"><strong>1st year tuition fees</strong></div></td>
        <?php foreach ($courseDataObjs as $courseObj) { ?>
        <td width="25%">
            <?php if(!empty($firstYearFees[$courseObj->getId()]['toFormattedFeesIndianDisplayableFormat'])){?>
            <div class="compare-detail-content"><?php echo $firstYearFees[$courseObj->getId()]['toFormattedFeesIndianDisplayableFormat']; ?></div>
            <?php } else { echo"------"; } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?>
        <td width="25%"></td>
        <td width="25%"></td>
        <?php } else if($coursesCount == 2){?>
        <td width="25%"></td>
        <?php } ?>
    </tr>
    <?php if($otherExpenseFlag){ ?>
    <tr>
       <td width="25%"><div class="compare-detail-content"><strong>1st year other expenses</strong></div></td>
        <?php foreach ($courseDataObjs as $courseObj) { ?>
        <td width="25%">
            <?php if(!empty($firstYearFees[$courseObj->getId()]['customFeesIndianDisplayableFormat'])){?>
            <div class="compare-detail-content"><?php echo $firstYearFees[$courseObj->getId()]['customFeesIndianDisplayableFormat']; ?></div>
            <?php } else { echo"------"; } ?>
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
       <td width="25%"><div class="compare-detail-content"><strong>1st year total fees</strong></div></td>
        <?php foreach ($courseDataObjs as $courseObj) { ?>
        <td width="25%">
            <?php if(!empty($firstYearFees[$courseObj->getId()]['totalFirstYearAndCustomFeesIndianDisplayableFormat'])){?>
            <div class="compare-detail-content"><?php echo $firstYearFees[$courseObj->getId()]['totalFirstYearAndCustomFeesIndianDisplayableFormat']; ?></div>
            <?php } else { echo"------"; } ?>            
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?>
        <td width="25%"></td>
        <td width="25%"></td>
        <?php } else if($coursesCount == 2){?>
        <td width="25%"></td>
        <?php } ?>
    </tr>
</table> 
</div>