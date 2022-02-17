<tr id="examRequired1">
	<td colspan="2" class="compare-title"><h2>Exam Required & Cut-off</h2></td>
</tr>
<tr id="examRequired2" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$z = 0;
	foreach($compareData['eligibility'] as $courseId => $eligibility){
	    $z++;
		if(count($eligibility) > 0)
		{
			$j++;
		?>
			<td class="<?php echo ($z<$compare_count_max)?'border-right':'';?>">
			    <div class="compare-list">
			    <?php
				foreach ($eligibility as $elig) {
				?>
					<div class="college-rank"><?php if(count($eligibility) > 1) {?><?php } ?>
	                <p class="examscut-off recognition ">
	                <?php echo $elig['examName'].'<span>'.$elig['examCutOff'].'</span>'.$elig['unit'] ?></p>
				<?php 
				}
			    ?>
			    </div>
			</td>
		<?php 
		}
		else
		{
		?>
			<td class="verticalalign <?php echo ($z<$compare_count_max)?'border-right':'';?>">--</td>
		<?php 
		}
	}
	if($j < $compare_count_max)
	{
	    for ($x = $z+1; $x <=$compare_count_max; $x++)
	    {
		?>
			<td class="<?php echo ($x<$compare_count_max)?'border-right':'';?>">&nbsp;</td>
		<?php
	    }
	}
    }
    ?>
</tr>
<tr id="examRequired3" style="position: relative;">
	<td colspan="2" class="message">
		<div class="data-source-col flRt">
			<span class="flLt" style="margin-right:5px;font-style:normal;color:#999999">*Cutoff for general category</span>
		</div>
		<br/>
	</td>
</tr>