<div class="section-cont">
<h4 class="section-cont-title">Salary Statistics <?php if($salary['currency']){ ?>(in <?=$salary['currency']?>)<?php } ?></h4>
<div class="sal-labels">
	<?php if($salary['min']) { ?>
		<label>Min. Salary</label>
	<?php } ?>
	<?php if($salary['avg']) { ?>
		<label>Avg. Salary</label>
	<?php } ?>
	<?php if($salary['max']) { ?>
		<label>Max. Salary</label>
	<?php } ?>
</div>
<ul class="sal-statastics">
	<?php if($salary['min']) { ?>
		<li><span class="min-sal" style="width:<?=max($salary['min']*9100000/$graphIndex,30)?>%">&nbsp;&nbsp;<?=number_format($salary['min'],2, '.', '')?> Lacs</span></li>
	<?php } ?>
	<?php if($salary['avg']) { ?>
		<li><span class="avg-sal" style="width:<?=max($salary['avg']*9100000/$graphIndex,30)?>%">&nbsp;&nbsp;<?=number_format($salary['avg'],2, '.', '')?> Lacs</span></li>
	<?php } ?>
	<?php if($salary['max']) { ?>
		<li><span class="max-sal" style="width:<?=max($salary['max']*9100000/$graphIndex,30)?>%">&nbsp;&nbsp;<?=number_format($salary['max'],2, '.', '')?> Lacs</span></li>
	<?php } ?>
	<li class="sprite-bg scale-bg"></li>
</ul>
<div class="scale-figure">
<label>0</label>
<label><?=number_format(($graphIndex*1)/500000,1)?></label>
<label class="third"><?=number_format(($graphIndex*2)/500000,1)?></label>
<label class="fourth"><?=number_format(($graphIndex*3)/500000,1)?></label>
<label><?=number_format(($graphIndex*4)/500000,1)?></label>
<label class="sixth"><?=number_format(($graphIndex*5)/500000,1)?></label>
</div>
</div>
