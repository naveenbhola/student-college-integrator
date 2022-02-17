<div class="col-bar">
	         	<h2 class="titl-main">Scholarship amount details</h2>
	         	<?php if($scholarshipObj->getAmount()->getConvertedTotalAmountPayout() > 0){ ?>
	         	<h3 class="sub-titl f14-fnt">Max scholarship per student: <?php echo $currencyName.' '.number_format($scholarshipObj->getAmount()->getTotalAmountPayout()); ?> => <?php echo 'Rs '.moneyAmountFormattor($scholarshipObj->getAmount()->getConvertedTotalAmountPayout(),1,1); ?> </h3>
	         	<p class="f12-fnt">Conversion rate used: 1 <?php echo $currencyName ?> = Rs. <?php echo number_format($currencyRate,2); ?> (last updated <?php echo date('jS F Y', strtotime($currencyRateUpdateTime)); ?>)</p>
	         	<?php if($scholarshipObj->getAmount()->getAmountInterval() != ''){ ?>
	         	<p class="mtop-15 f14-fnt">Scholarship amount will be given: <strong><?php echo $scholarshipIntervalArray[$scholarshipObj->getAmount()->getAmountInterval()]; ?></strong></p>
	         	<?php } } 
	         		if(count($scholarshipObj->getAmount()->getExpensesCovered()) > 0){
	         	?>
	         	<h3 class="sub-titl f14-fnt">Scholarship amount can be used for</h3>
	         	
	         	 <table class="col-table">
	         	  	<thead class="thead-default">
	         	  		<tr>
	         	  			<th>Type of Expense</th>
	         	  			<th>Indicates if scholarship can be used for this type of expense</th>
	         	  		</tr>
	         	  	</thead>
	         	  	<tbody class="tbody-default">
	         	  		<?php foreach ($expensesCoveredList as $index => $name) { ?>
	         	  		<tr>
	         	  			<td><?php echo $name; ?></td>
	         	  			<?php if(in_array($index, $scholarshipObj->getAmount()->getExpensesCovered())){ ?>
	         	  				<td><i class="tickmrk"> &#10004;</i>Scholarship amount can be used for this</td>
	         	  			<?php }else{ ?>
	         	  				<td><i class="crssmrk"> &times;</i>Not covered in scholarship</td>
	         	  			<?php } ?>
	         	  		</tr>
	         	  		<?php } ?>
	         	  	</tbody>
	         	  </table>
	         	  <?php }
	         	  		if($scholarshipObj->getAmount()->getAmountDescription() != ''){
	         	  ?>

                 	<p class="f14-fnt mtop-15"><?php echo $scholarshipObj->getAmount()->getAmountDescription(); ?></p>
             	<?php } ?>

	         </div>