<h2 class="titl-main">Scholarship amount details</h2>
<div class="sch-bx2">
<?php
if($scholarshipObj->getAmount()->getConvertedTotalAmountPayout() != ''){
?>
<p>Max scholarship per student: <br/>
<strong><?php echo $currencyName .' '. ($scholarshipObj->getAmount()->getTotalAmountPayout())?> => Rs <?php echo moneyAmountFormattor($scholarshipObj->getAmount()->getConvertedTotalAmountPayout(), 1, 1);?></strong></p>
<p>Scholarship amount will be given: <strong><?php echo $scholarshipIntervalArray[$scholarshipObj->getAmount()->getAmountInterval()]?></strong></p>
<?php
}
if(!empty($currencyRate))
{
?>
<p>Conversion rate used: 1 <?php echo $currencyName ?> = Rs <?php echo number_format($currencyRate, 2) ?> (last updated <?php echo date('jS F, Y', strtotime($currencyRateUpdateTime))?>)</p>
<?php 
}
?>
</div>
<h3 class="sub-titl">Scholarship amount can be used for</h3>
<table class="col-table">
        <thead class="thead-default">
            <tr>
                <th width="50%">Type of Expense</th>
                <th width="50%">Indicates if scholarship can be used for this type of expense</th>
            </tr>
        </thead>
        <tbody class="tbody-default">
            <?php 
            $expensesCovered = $scholarshipObj->getAmount()->getExpensesCovered();
            foreach ($expensesCoveredList as $key => $value) {
            	if(in_array($key, $expensesCovered)){
            	?>
            	<tr>
	                <td><strong><?=$value?></strong></td>
	                <td><i class="tickmrk">&#10004;</i><p class="ml20">Scholarship amount can be used for this</p></td>
	            </tr>
            	<?php 
            	}else{
            	?>
            	<tr>
	                <td><strong><?=$value?></strong></td>
	                <td><i class="crssmrk">&times;</i><p class="ml20">Not covered in scholarship</p></td>
	            </tr>
            	<?php 
            	}
            }
            ?>
        </tbody>
</table>
<p><?php echo $scholarshipObj->getAmount()->getAmountDescription();?></p>