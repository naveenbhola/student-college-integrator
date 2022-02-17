<div class="right_col" role="main">
	<h1>App Stats of past 1 month</h1>
<?php 
$lineChart = $charts['lineChart'];
foreach ($lineChart as $keyName => $keyValue) {?>
<br/>
<div class ="row">
	<div><h2><?php echo $keyName?></h2></div>

	<?php
	
	foreach ($keyValue as $key => $value) {
		$lineChartDetails['heading'] = $value['heading'];
		$lineChartDetails['key'] = $key;
		$lineChartDetails['data'] = $resultsToShow[$key]['data'];
		$lineChartDetails['count'] = $resultsToShow[$key]['totalCount'];
		$this->load->view('trackingMIS/appLineChart',$lineChartDetails);	
	} ?>
	</div>
<?php	
}
?>

</div>