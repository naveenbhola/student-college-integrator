<table border="0" cellpadding="0" cellspacing="0">
    <tr>
        <th colspan="2" class="heading-bg">
            <div class="compare-detail-content"><h2><strong>1st Year Fees</strong></h2></div>
        </th>
    </tr>
    <tr>
        <td colspan="2">
            <div class="compare-detail-content"><strong>1st Year Tution Fees</strong></div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) { ?>
        <td>
            <?php if(!empty($firstYearFees[$courseObj->getId()]['toFormattedFeesIndianDisplayableFormat'])){?>
            <div class="compare-detail-content"><p><?php echo $firstYearFees[$courseObj->getId()]['toFormattedFeesIndianDisplayableFormat']; ?></p></div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php if($otherExpenseFlag){ ?>
    <tr>
        <td colspan="2" class="width100">
            <div class="compare-detail-content">
                <strong>1st Year Other Expenses</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) { ?>
        <td><?php if(!empty($firstYearFees[$courseObj->getId()]['customFeesIndianDisplayableFormat'])){?>
            <div class="compare-detail-content"><p><?php echo $firstYearFees[$courseObj->getId()]['customFeesIndianDisplayableFormat']; ?></p></div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <tr>
        <td colspan="2" class="width100">
            <div class="compare-detail-content">
                <strong>1st Year Total Fees ( Tution + Other )</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) { ?>
        <td><?php if(!empty($firstYearFees[$courseObj->getId()]['totalFirstYearAndCustomFeesIndianDisplayableFormat'])){?>
            <div class="compare-detail-content"><p><?php echo $firstYearFees[$courseObj->getId()]['totalFirstYearAndCustomFeesIndianDisplayableFormat']; ?></p></div>
             <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?> 
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
</table> 