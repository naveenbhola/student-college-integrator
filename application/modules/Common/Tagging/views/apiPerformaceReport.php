<?php 
$periodList = array(1=> "Today",
					2=> "Yesterday",
					3=> "Day before Yesterday");

$chart1Title = "Hits Trend for ".date("d-m-Y", strtotime($date));
$chart2Title = "Latency Trend for ".date("d-m-Y", strtotime($date));

?>
<!DOCTYPE html>
<html>
<head>
    <title>HomeFeed</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/humanity/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <style type="text/css">
    table tr:nth-child(even){background-color: #F5FBD5; }
    table td{padding: 4px 5px;}
    .userTable{border-collapse: collapse;width: 40%;font-size: 14px;}
    .userTable td{padding: 4px 5px;}
    .tooltip{padding:2px 10px;font-weight: bold;text-align: left;}
	</style>
</head>
<body>
<div style="width:1200px;margin:0 auto;">
<h1 style="font-family: Arial, Helvetica, sans-serif;color:#8B0707;">API Performance Report</h1>
<span style="font-size:19px;">Date : </span><input type='text' style="padding:4px;" id="datepicker" placeholder="Pick a Date" />
<div style="clear: both;font-size: 1px;"></div>
<br/>
	<div class="moduleChart" id="moduleChart_1" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
	<div class="moduleChart" id="moduleChart_2" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
	<br/>
	<div style="clear: both;font-size: 1px;"></div>
	<?php
		$html = "<table style='width:97%;border-collapse:collapse;margin-top:30px;' border=1>";
		$html .= "<tr>";
		$html .= "<th>#</th>";
		$html .= "<th>Controller</th>";
		$html .= "<th>Method</th>";
		$html .= "<th>Average Time</th>";
		$html .= "<th>Total hits</th>";
		$html .= "<th>Operations</th>";
		$html .= "</tr>";

		if(empty($reportData)){
			$html .= "<tr><td colspan=6><i>No Data Exists !!!</i></td></tr>";
		}
		foreach ($reportData as $key => $value) {
			$html .= "<tr>";
			$html .= "<td>".($key+1)."</td>";
			foreach ($value as $key=>$data) {
				$html .= "<td>".$data."</td>";
			}
			$html .= "<td><a href='javascript:void(0);' style='color:#8B0707;' onclick=\"refreshCharts('".$value['controller']."', '".$value['method']."');\">Show Trends</a></td>";
			$html .= "</tr>";
		}
		$html .= "</table>";
		echo $html;
	?>
	</div>
</body>
</html>
<script type="text/javascript">
	$(function() {
		$( "#datepicker" ).datepicker({ 
				maxDate: "+0M +0D",
				onSelect : function(){
					changeReport();
				}
		});

    	$( "#datepicker" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
    	$("#datepicker").val('<?php echo date("d-m-Y", strtotime($date));?>');

  	});
   google.load("visualization", "1", {packages:["corechart", 'bar']});
   google.setOnLoadCallback(drawChart);

   var optionsChart2;
   var chart2;
   var dataChart2;
   var hitCountchartData = <?php echo $minuteArr;?>;
   var latencychartData = <?php echo $avgProcessingtimeArr;?>;
   var chart1Title = '<?php echo $chart1Title;?>';
   var chart2Title = '<?php echo $chart2Title; ?>';
   function drawChart(){

   		dataChart2 = new google.visualization.DataTable();
	    dataChart2.addColumn('string', '');
	    dataChart2.addColumn('number', 'Hit Count');
	    dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
	    dataChart2.addRows(hitCountchartData);

   		optionsChart2 = {
				      width: 580,
				      height: 300,
				      title: chart1Title,
				      colors :['#8B0707'],
				      hAxis: {title: 'Half Hour',  titleTextStyle: {color: '#333'}},
				      vAxis: {minValue: 0},
				      tooltip: { isHtml: true },
				      backgroundColor: '#F5FBD5'
    		};

	    chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_1'));
	    chart2.draw(dataChart2, optionsChart2);

	    dataChart2 = new google.visualization.DataTable();
	    dataChart2.addColumn('string', chart2Title);
	    dataChart2.addColumn('number', 'Time in Seconds');
	    dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
	    dataChart2.addRows(latencychartData);

   		optionsChart2 = {
				      width: 580,
				      height: 300,
				      title: chart2Title,
				      colors :['#8B0707'],
				      hAxis: {title: 'Half Hour',  titleTextStyle: {color: '#333'}},
				      vAxis: {minValue: 0},
				      tooltip: { isHtml: true },
				      backgroundColor: '#F5FBD5'
    		};

	    chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_2'));
	    chart2.draw(dataChart2, optionsChart2);
   }

   function refreshCharts(controller, method){

   		$.ajax({
   			url : '/Tagging/AppReport/getMethodData',
   			type : 'POST',
   			data : {'controller' : controller, 'method' : method, 'date':'<?php echo $date;?>'},
   			success : function(response){

   				$("html, body").animate({ scrollTop: 0 }, "slow");
				response          = JSON.parse(response);
				hitCountchartData = eval(response['minuteArr']);
				latencychartData  = eval(response['avgProcessingtimeArr']);
				chart1Title       = '<?php echo $chart1Title;?>'+' of '+controller+"::"+method;
				chart2Title       = '<?php echo $chart2Title;?>'+' of '+controller+"::"+method;

   				drawChart();
   			}
   		});
   }

   function changeReport(){
		window.location.href = "/Tagging/AppReport/showAPIPerformanceReport/"+$("#datepicker").val();
	}
</script>