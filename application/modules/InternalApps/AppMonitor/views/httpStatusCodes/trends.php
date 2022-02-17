<?php
$titleText = 'HTTP Status Codes';
$this->load->view('AppMonitor/common/header');
?>
	<div class="clearFix"></div>
	<div style='background-color:white;margin:0 auto;width:1200px; min-height:600px; padding-bottom: 40px; border:0px solid red;'>
	<div style="padding: 10px 10px 0 10px;border: 0px solid lightgrey;border-top: 0;">
	
	<div class="head1" style="border:0px solid red; margin-top:20px;">

	<div style="width:100%; padding:0px;">
        <?php $p = 1; foreach(array_keys($statusCodeTrendsChartData) as $statusCode) { ?>
        
        <div style="float:left; width:550px; <?php if($p%2 == 0) echo "margin-left:80px;" ?>">    
        <h1 style="font-size:18px; margin-bottom:10px;"><?php echo $statusCode." - ".$statusCodeNames[$statusCode]; ?></h3>    
		<div id="barchart_values_<?php echo $statusCode; ?>" style="width:550px; height:350px; text-align: center; background: #f8f8f8;"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' />
        </div>
        </div>
        
        <?php if($p%2 == 0) { echo "<div class='clearFix' style='margin-bottom:20px;'></div>"; } ?>
        
        <?php $p++; } ?>
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

google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {
    <?php foreach($statusCodeTrendsChartData as $code => $trendData) { ?>
        
    var options = {
		title: "",
		width: 540,
		height: 350,
		bar: {groupWidth: "80%", groupHeight:"80%"},
		hAxis: {
			//title: 'Date',
			baseline: 0,
			gridlines: {
				count: 6
			}
		},
		//vAxis : {title: 'No. of <?php echo $code;?> requests',textPosition : "out"},
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
	dataTable.addColumn('number', '<?php echo $code; ?>');
	//dataTable.addColumn('number', 'Google WebLight sessions');
	//dataTable.addColumn('number', 'xyz');
	<?php
		//foreach ($servers as $serverName) {
		//	?>
		//	dataTable.addColumn('number', '<?=''.$serverName?>');
		//	<?php
		//}
	?>

	dataTable.addRows(<?php echo json_encode($trendData);?>);
   
	var chart = new google.visualization.LineChart(document.getElementById('barchart_values_<?php echo $code; ?>'));
	chart.draw(dataTable, options);
	<?php } ?>
}
</script>
</html>
