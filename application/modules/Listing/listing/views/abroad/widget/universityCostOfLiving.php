<div class="widget-wrap clearwidth acco-details">
    <h2>Hostel & Meal Expenses for <?php echo htmlentities($universityObj->getName()); ?>, <?php echo htmlentities($universityCity); ?>, <?php echo htmlentities($universityCountry); ?></h2>
    <?php if(!empty($livingExpense)) { ?>
    <table cellspacing="0" cellpadding="0" border="0" class="fee-table" style="margin-bottom:4px">
        <tbody>
            <tr>
                <th width="28%" style="padding:8px 10px;"></th>
                <th width="37%" style="padding:8px 10px;">Amount in <?php echo htmlentities($livingExpenseCurrencyObj->getName());?> (<?php echo ($livingExpenseCurrencySign!="")?$livingExpenseCurrencySign:$livingExpenseCurrencyObj->getCode();  ?>)</th>
                <?php if($livingExpenseCurrencySign!= 'Rs.'){?>
                <th width="33%" style="padding:8px 10px;">Amount in Indian Rupees (Rs.)</th>
                <?php } ?>
            </tr>
            <tr>
                <td>Monthly</td>
                <td><?php echo ($livingExpenseCurrencySign!="")?$livingExpenseCurrencySign:$livingExpenseCurrencyObj->getCode(); ?> <?php echo $livingExpense; ?></td>
                <?php if($livingExpenseCurrencySign!= 'Rs.'){?>
                <td>Rs. <?php echo $livingExpenseInRupees; ?></td>
                <?php } ?>
            </tr>
            <tr class="last alt-rowbg">
                <td>Yearly</td>
                <td><?php echo ($livingExpenseCurrencySign!="")?$livingExpenseCurrencySign:$livingExpenseCurrencyObj->getCode(); ?> <?php echo $livingExpenseAnnually; ?></td>
                <?php if($livingExpenseCurrencySign!= 'Rs.'){?>
                <td>Rs. <?php echo $livingExpenseAnnuallyInRupees; ?></td>
                 <?php } ?>
            </tr>
            </tbody>
    </table>
    <?php } ?>
    <?php if($livingExpenseURL!="http://" && $livingExpenseURL!= "") { ?>
    <p><a target="_blank" rel="nofollow" onclick="studyAbroadTrackEventByGA('ABROAD_<?=strtoupper($listingType)?>_PAGE', 'outgoingLink');" href="<?=$livingExpenseURL?>">
    <?php echo (!empty($livingExpense)? " More about living ":"Living ");?>expenses on university website<i class="common-sprite ex-link-icon"></i></a></p>
    <?php }?>
</div>
