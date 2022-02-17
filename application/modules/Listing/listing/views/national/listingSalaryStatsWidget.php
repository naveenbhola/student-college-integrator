<?php
    if($salary['min'] || $salary['max'] || $salary['avg']){
        
        $salaryForIndex = ($salary['max']!=0)?$salary['max']:(($salary['avg']!=0)?$salary['avg']:$salary['min']);
    
        if($salaryForIndex%5!=0){
                $graphIndex = $salaryForIndex +'10'-($salaryForIndex%10);
        }else{
                $graphIndex = $salaryForIndex;
        }

        $salary['min'] = number_format((float)($salary['min']/100000),2);
        $salary['max'] = number_format((float)($salary['max']/100000),2);
        $salary['avg'] = number_format((float)($salary['avg']/100000),2);
    }
    else {
        return;
    }
    if($salary['min']>0 || $salary['max']>0 || $salary['avg']>0){ ?>
	<div class="salary-details clear-width">
		<div class="salary-levels">
			<?php if($salary['min']>0) { ?>
			    <p>Min. Salary</p>
			<?php } ?>
			<?php if($salary['avg']>0) { ?>
			    <p>Avg. Salary</p>
			<?php } ?>
			<?php if($salary['max']>0) { ?>
			    <p>Max. Salary</p>
			<?php } ?>
		</div>

		<ol class="salary-bar">
			<?php if($salary['min']>0) { ?>
			<li>
				<div class="min-bar" style="width:<?=$salary['min']*16420000/$graphIndex?>px"></div> <label><?php echo $salary['min']." Lac".($salary['min']<=1?"":"s"); ?></label>
			</li>
			<?php } ?>
			<?php if($salary['avg']>0) { ?>
			<li>
				<div class="avg-bar" style="width:<?=$salary['avg']*16420000/$graphIndex?>px"></div> <label><?php echo $salary['avg']." Lac".($salary['avg']<=1?"":"s"); ?></label>
			</li>
			<?php } ?>
			<?php if($salary['max']>0) { ?>
			<li>
				<div class="max-bar" style="width:<?=max($salary['max']*16420000/$graphIndex,30)?>px"></div> <label><?php echo $salary['max']." Lac".($salary['max']<=1?"":"s"); ?></label>
			</li>
			<?php } ?>

			<li style="margin-bottom: 4px"><i class="sprite-bg graph-scale"></i>
			</li>
			<div class="clearFix"></div>
		</ol>
		<div class="scale-figure">
			<?php
			$divisor = 400000;
			/* here the number 4 denotes that
			 * at which place in the scale after the 0,
			 * should the max salary value appear.
			 * Here we need it to be at 4th place after (not the classname 'fourth'), hence divisor would be 4.
			 * 4th place is choosen, to accomodate salary value labels
			 * */
			?>
			<span>0</span>
			<span><?=number_format(($graphIndex*1)/$divisor ,1)?></span>
			<span class="third"><?=number_format(($graphIndex*2)/$divisor ,1)?></span>
			<span class="fourth"><?=number_format(($graphIndex*3)/$divisor ,1)?></span>
			<span><?=number_format(($graphIndex*4)/$divisor ,1)?></span>
			<span class="sixth"><?=number_format(($graphIndex*5)/$divisor ,1)?></span>
			<span class="seventh"><?=number_format(($graphIndex*6)/$divisor ,1)?></span>
                </div>
	</div>
<?php } ?>
