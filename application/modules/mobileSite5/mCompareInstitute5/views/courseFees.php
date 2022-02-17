<tr id="row6_H">
	<td colspan="2" class="compare-title"><h2>Total Course Fees</h2></td>
</tr>
<tr id="row6_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($compareData['courseFee'] as $courseId => $courseFee){
	    $k++;
	    if(is_object($courseFee)){
			$j++;
			if($courseFee->getFeesValue() >= 100000)
			{
			    $feevalue =round(($courseFee->getFeesValue()/100000),1);
                $feeunit  = ($courseFee->getFeesValue() == 100000)?"Lac":"Lacs";
			}
			else
			{
			    $feevalue = moneyFormatIndia($courseFee->getFeesValue());
                $feeunit  = "";
			}
			?>
			<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
			    <div class="compare-list"> <p class = "fees-inr"><strong><?php echo $courseFee->getFeesUnitName()?></strong><?php echo $feevalue?><span>&nbsp;<?=$feeunit?></span></p>
			    <?php 
	            if($courseFee->getFeeDisclaimer())
	            {
	            ?>
			    	<div class="data-source-col flRt">
						<span class="flLt" style="margin-right:5px;font-style:normal;color:#999999"><?php echo FEES_DISCLAIMER_TEXT?></span>
					</div>
				<?php 
				}
				?>
			    <?php if($showFeeDisc[$courseId] == 1 && SHOW_FEE_DISC_CMPR){ ?>
			    	<div class="clg-review-box"><p class="year-class">Note - Fees mentioned is Full Course Fees. LPU offers various scholarships based on academic performance. Check LPU details page for more information.</p></div>
			    <?php } ?>
			    </div>
			</td>
		<?php
	    }
	    else{
		?>
		<td class="verticalalign <?php echo ($k<$compare_count_max)?'border-right':'';?>">--</td>
		<?php
	    }
	}
	if($j < $compare_count_max)
	{
	    for ($x = $k+1; $x <=$compare_count_max; $x++)
	    {
		?>
		<td class="<?php echo ($x<$compare_count_max)?'border-right':'';?>">&nbsp;</td>
		<?php
	    }
	}
    }
    ?>
</tr>
<tr style="position: relative;">
	<td colspan="2" class="message">
		<div class="data-source-col flRt">
			<span class="flLt" style="margin-right:5px;font-style:normal;color:#999999">*Fees for general category</span>
		</div>
		<br/>
	</td>
</tr>