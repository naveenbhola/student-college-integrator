<?php
$titleText = 'Google WebLight Sessions';
$clptitleText = 'Course Detail Page Google WebLight Sessions';
$this->load->view('AppMonitor/common/header');
?>
	<div class="clearFix"></div>
	<div style='background-color:white;margin:0 auto;width:1200px; padding-bottom: 40px; border:0px solid red;'>
	<div style="padding: 10px 10px 0 10px;border: 0px solid lightgrey;border-top: 0;">
		<div class="head1" style="border:0px solid red; margin-top:20px;">

		<div style="width:100%; padding:0px;">
			
			<div id="barchart_values" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
		</div>
		
		<div class="clearFix"></div>


		<div id="dialog" title="Detailed URLs">
		  <p style="display:none;width:700px;height:600px;"></p>
		</div>
		</div>
	</div>

	<div style="padding: 10px 10px 0 10px;border: 0px solid lightgrey;border-top: 0;">
		<div class="head1" style="border:0px solid red; margin-top:20px;">

		<div style="width:100%; padding:0px;">
			
			<div id="clp_barchart_values" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
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

	var options = {
		title: "<?php echo $titleText;?>",
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
	dataTable.addColumn('number', 'Total mobile sessions');
	dataTable.addColumn('number', 'Google WebLight sessions');
	//dataTable.addColumn('number', 'xyz');
	<?php
		//foreach ($servers as $serverName) {
		//	?>
		//	dataTable.addColumn('number', '<?=''.$serverName?>');
		//	<?php
		//}
	?>

	dataTable.addRows(<?php echo json_encode($webLightTrendsChartData);?>);
   
	var chart = new google.visualization.LineChart(document.getElementById('barchart_values'));
	chart.draw(dataTable, options);

	// for clp
	options.title = '<?php echo $clptitleText;?>';
	options.vAxis.title = 'No. of <?php echo $clptitleText;?>';
	var dataTable = new google.visualization.DataTable();
	dataTable.addColumn('string', 'Date');
	dataTable.addColumn('number', 'Total CLP mobile sessions');
	dataTable.addColumn('number', 'CLP Google WebLight sessions');

	dataTable.addRows(<?php echo json_encode($clpWebLightTrendsChartData);?>);
	var chart = new google.visualization.LineChart(document.getElementById('clp_barchart_values'));
	chart.draw(dataTable, options);
	
	
}
</script>
</html>