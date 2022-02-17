<div class="clearFix"></div>
<div class="left-sec" style="margin-left:5px;">
</div>
<div class="right-sec">
</div>
<div class="clearFix"></div><br/>
<?php 
	foreach ($dailyAverageData as $key => $value) {
		if($key != 'custom'){
?>
	<div class="moduleChart" id="moduleChart_<?php echo $key;?>" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
<?php	
	}
}
?>
<div class="clearFix"></div>