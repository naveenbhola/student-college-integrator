<div class="lineSpace_10">&nbsp;</div>
	<div class="raised_greenGradient">
		<b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#F5FDE6"></b><b class="b4" style="background-color:#F5FDE6"></b>
		<div class="boxcontent_greenGradient1">
			<div class="mar_left_10p">
				<div class="lineSpace_10">&nbsp;</div>
	<ul style="margin:0; padding:0;list-style-type:none">
	<?php if(array_key_exists(47,$sumsUserInfo['sumsuseracl']))
			{?>
				<li class="<?php if($type === 'Target_Input') echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/targetInput/collection" class="fontSize_12p">Target Input</a></li>
				<div class="lineSpace_10">&nbsp;</div>
	<?php }?>
	<?php if(array_key_exists(48,$sumsUserInfo['sumsuseracl']))
			{?>
				<li class="<?php if($type === 'Month_till_date_sales_Report') echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/targetInput/Handle_Report/1" class="fontSize_12p">Month sales Report (Till date)</a></li>
				<div class="lineSpace_10">&nbsp;</div>
	<?php }?>
	<?php if(array_key_exists(49,$sumsUserInfo['sumsuseracl']))
			{?>
				<li class="<?php if($type === 'Quarter_till_date_sales_Report') echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/targetInput/Handle_Report/2" class="fontSize_12p">Quarter sales Report (Till date)</a></li>
				<div class="lineSpace_10">&nbsp;</div>
	<?php }?>
	<?php if(array_key_exists(50,$sumsUserInfo['sumsuseracl']))
			{?>
				<li class="<?php if($type === 'Product_MIX_Report') echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/targetInput/Handle_Report/3" class="fontSize_12p">Product MIX Report</a></li>
				<div class="lineSpace_10">&nbsp;</div>
	<?php }?>
	</ul>
			</div>
		</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
