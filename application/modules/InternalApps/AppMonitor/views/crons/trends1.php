<?php
$titleText = "Cron Failures";
$chartData = array();
$serversForGraph  = array();

foreach ($moduleWiseErrors as $date => $serverArray) {
	$temp = array();
	$temp[] = $date;
	
	if($selectedModule == 'shiksha') {
		foreach ($serverArray as $serverName => $serverCount) {
			$temp[] = intval($serverCount);
			$serversForGraph[$modulesMapping[$serverName]] = 1;
		}
	}
	else {
		$temp[] = intval($serverArray[$selectedModule]);
		$serversForGraph[$modulesMapping[$selectedModule]] = 1;
	}
	
	$chartData[] = $temp;
}
$serversForGraphFinal = array_keys($serversForGraph);
$chartData = json_encode($chartData);

global $ENT_EE_MODULES_COLORS;
global $ENT_EE_MODULES;

$colors = array();

if($moduleName == "shiksha"){
    foreach ($ENT_EE_MODULES_COLORS as $value) {
        $colors[] = $value;
    }
	$colors[] = '#000000';
} else {
    $colors[] = $ENT_EE_MODULES_COLORS[$moduleName];
}

$colors = json_encode($colors);

$this->load->view('AppMonitor/common/header');
?>

	
		<div style='background-color:white;margin:0 auto;width:1200px; padding-bottom: 40px; border:0px solid red;'>
		<div style="padding: 10px 10px 0 10px;border: 0px solid lightgrey;border-top: 0;">
		
		<div class="head1" style="border:0px solid red;">
	
		
		<div style="width:100%; padding:0px; margin-top:15px;">
			
			
			<h2 class='sub-head'>Trend</h2>
			
			<div id="barchart_values"></div>
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

		var dataTable = new google.visualization.DataTable();
	    dataTable.addColumn('string', 'Date');
	    <?php
	    	foreach ($serversForGraphFinal as $serverName) {
	    		?>
	    		dataTable.addColumn('number', '<?=''.$serverName?>');
	    		<?php
	    	}
	    ?>

         	dataTable.addRows(<?php echo $chartData;?>);
	   

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
	        backgroundColor: '#F8F8F8',
	        colors : <?php echo $colors;?>,
	         animation:{
			    duration: 1000,
			    easing: 'out',
			 }

      	};

 		var chart = new google.visualization.LineChart(document.getElementById('barchart_values'));
 		chart.draw(dataTable, options);
	}
    	
	
	</script>
	</html>
	