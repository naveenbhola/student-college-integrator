<?php
$titleText = 'bots detected';
$this->load->view('AppMonitor/common/header');
?>

<div class='blockbg'>
	<div style='width:1200px; margin:0 auto;'>		
	<div style='float:left; margin-top:0px;'>	
		
	<div style='float:left; margin-left:15px; padding-top:3px;'>From Date: </div>
	<div style='float:left; margin-left:10px; padding-top:1px;'>
		<input type="text" id="fromDatePicker" readonly="readonly" value="<?php echo $trendStartDate; ?>" style='width:100px; cursor: text' />
	</div>

	<div style='float:left; margin-left:30px; padding-top:3px;'>To Date : </div>
	<div style='float:left; margin-left:10px; padding-top:1px;'>
		<input type="text" id="toDatePicker" readonly="readonly" value="<?php echo $trendEndDate; ?>"  style='width:100px; cursor: text' />
	</div>
	</div>
	<div style='float:left; margin-left:40px;'>
		<!--a href='#' onclick="updateReport();"><img src='/public/images/appmonitor/drefresh.png' width='24' /></a-->
		<a href='#' onclick="updateReport();" class='zbutton zsmall zgreen'>Go</a>
	</div>
	 <div style='clear:both'></div>
	</div>
</div>


	<div style='background-color:white;margin:0 auto;width:1200px; padding-bottom: 40px; border:0px solid red;'>
	<div style="padding: 10px 10px 0 10px;border: 0px solid lightgrey;border-top: 0;">
	
	<div class="head1" style="border:0px solid red;">

	<div style="width:100%; padding:0px;">
		
		
		
		<div id="barchart_values" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
		
		
		<h2 class='sub-head' style='margin-top:40px;'>Trend For Unique Queries</h2>
		<div id="unique_barchart_values" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
	</div>
	
	<div class="clearFix"></div>

	<div id="dialog" title="Detailed URLs">
	  <p style="display:none;width:700px;height:600px;"></p>
	</div>
	</div>

</div>
</div>
</body>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	
$(document).ready(function(){
	$("#fromDatePicker").datepicker();
	$("#toDatePicker").datepicker();
});

function updateReport() {
	trendStartDate = convertDateFormat($("#fromDatePicker").val());
	trendEndDate = convertDateFormat($("#toDatePicker").val());
	
	url = '/AppMonitor/SpearAlerts/trends';
	
	<?php if($serverName && $serverName != 'shiksha') { ?>
		url += '/<?php echo $serverName; ?>';
	<?php } ?>	
	
	window.location = url+"?trendStartDate="+trendStartDate+"&trendEndDate="+trendEndDate;
}

function convertDateFormat(date) {
	parts = date.split("/");
	return parts[2]+"-"+parts[0]+"-"+parts[1];
}	

google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {

	var options = {
		title: "",
		width: 1170,
		height: 400,
		bar: {groupWidth: "80%", groupHeight:"80%"},
		hAxis: {
			title: 'Date',
			baseline: 0,
			gridlines: {
				count: 6
			}
		},
		vAxis : {title: 'No. of <?php echo $titleText;?>',textPosition : "out"},
		tooltip: { isHtml: true },
		colors : <?php echo json_encode($colors);?>,
		backgroundColor: '#F8F8F8',
		 animation:{
			duration: 1000,
			easing: 'out',
		 }
	};

	var dataTable = new google.visualization.DataTable();
	dataTable.addColumn('string', 'Date');
	<?php
		foreach ($statuses as $statusName) {
			?>
			dataTable.addColumn('number', '<?=''.$statusName?>');
			<?php
		}
	?>

	dataTable.addRows(<?php echo json_encode($trendsChartData);?>);
   
	var chart = new google.visualization.LineChart(document.getElementById('barchart_values'));
	chart.draw(dataTable, options);
	
    options = {
		title: "",
		width: 1170,
		height: 400,
		bar: {groupWidth: "80%", groupHeight:"80%"},
		hAxis: {
			title: 'Date',
			baseline: 0,
			gridlines: {
				count: 6
			}
		},
		vAxis : {title: 'No. of captcha',textPosition : "out"},
		tooltip: { isHtml: true },
		colors : <?php echo json_encode($captchaColors);?>,
		backgroundColor: '#F8F8F8',
		 animation:{
			duration: 1000,
			easing: 'out',
		 },
         chartArea: {  width: "70%"}
	};
    
	dataTable = new google.visualization.DataTable();
	dataTable.addColumn('string', 'Date');
	<?php
		foreach ($captchaStatuses as $captchaStatus) {
			?>
			dataTable.addColumn('number', '<?=''.$captchaStatus?>');
			<?php
		}
	?>
	
	dataTable.addRows(<?php echo json_encode($captchaTrendsChartData);?>);
	  
	var chart = new google.visualization.LineChart(document.getElementById('unique_barchart_values'));
	chart.draw(dataTable, options);
}
</script>
</html>
