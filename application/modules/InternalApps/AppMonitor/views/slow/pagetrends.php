<?php 
$dailyAverageData['custom'] = array();
$this->load->view('AppMonitor/common/header', $displaydata); 
?>
<div class="parentContainer">
	<div class="innerContainer">
	<?php $this->load->view("AppMonitor/slow/chartView");?>
	<div style="margin-left:0px; margin-top:20px;">
		<h2 style="margin-bottom:10px; margin-top:20px;">For any other page : </h2>
		<select id="customChartMap" style="padding:3px;">
			<option>Select Controller/Method</option>
			<?php
				foreach($allMethodsArr as $optgroup => $optionArr){
					echo "<optgroup label='".$optgroup."'></optgroup>";
					foreach($optionArr as $optionArrRow){
						echo "<option value='".$optgroup."___".$optionArrRow."'>".$optionArrRow."</option>";
					}
				}
			?>
		</select>
		<input type="button" value="Show" onclick="showCustomChart('<?php echo $dashboardType;?>');"  style="padding:3px;" />
		<div class="moduleChart" id="moduleChart_custom"></div>
	</div>
	<div class="clearFix"></div>
	</div>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
   google.load("visualization", "1", {packages:["corechart", 'bar']});
   google.setOnLoadCallback(drawChart);

   var dataChart2 = Array();
   var chart2 = Array();    
   var chartAndModuleNameMapping = Array();
   var key;
   var optionsChart2;

   function drawChart() {
	  <?php 
    	$i = 0;
    	foreach ($dailyAverageData as $key => $value) {
    		$data = json_encode($value);
	  ?>
    		key = <?php echo $i++;?>;
    		dataChart2[key] = new google.visualization.DataTable();
		    dataChart2[key].addColumn('string', 'Day');
		    dataChart2[key].addColumn('number', 'Daily Average Time');
		    dataChart2[key].addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
		    dataChart2[key].addColumn('number', 'Average');
		    dataChart2[key].addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});

		    dataChart2[key].addRows(<?php echo $data;?>);

		    optionsChart2 = {
				      width: 580,
				      height: 300,
				      title: '<?php echo $key;?>',
				      hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
				      vAxis: {minValue: 0},
				      tooltip: { isHtml: true },
				      backgroundColor: '#F8F8F8'
    		};

		    chart2[key] = new google.visualization.LineChart(document.getElementById('moduleChart_<?php echo $key;?>'));
		    chart2[key].draw(dataChart2[key], optionsChart2);
		    chartAndModuleNameMapping[key] = "<?php echo $key ?>";
    <?php
    	}
    ?>

    for(i in chart2){
		google.visualization.events.addListener(chart2[i], 'select', attachEventsCallBack(i));
    }

    function attachEventsCallBack(i){
    	return function(){attachEvents(i)};
    }
    function attachEvents(i){
	
		var chartObj = chart2[i];
		var chartDataObj = dataChart2[i];
		var chartTypeData = chartAndModuleNameMapping[i];
	  
    	var selectedItem = chartObj.getSelection()[0];
	    if (selectedItem) {
	      var rowValue = chartDataObj.getValue(selectedItem.row, 0);
	    }

	    $("body").addClass("noscroll");
	$("#voverlay").show();
	$("#vdialog_inner").css('top', $('body').scrollTop()+20);
	$("#vdialog").show();
	$("#vdialog_content").html("<div style='margin:50px; text-align:center;'><img src='/public/images/appmonitor/loader.gif' /></div>");	

	    $.ajax({
	    	data: { "charttype" : chartTypeData , "date" : rowValue, "dashboardType" : "<?php echo $dashboardType;?>"},
	    	url : "/AppMonitor/Dashboard/showTopUrls",
	    	cache : false,
	    	method : "POST",
	    	beforeSend : function(){
	    	}
	    }).done(function(res){
			$("#vdialog_content").html(res);
	    });
   }
  }

  function showCustomChart(dashboardType) {
  	$.ajax({
	    	data: { "custumChartMap" : $("#customChartMap").val(), "dashboardType" : dashboardType},
	    	url : "/AppMonitor/Dashboard/getDataForCustomChart",
	    	method : "POST"
    }).done(function(res){
    	chartAndModuleNameMapping[key] = $("#customChartMap").val();
    	var n = dataChart2[key].getNumberOfRows();
    	dataChart2[key].removeRows(0 ,n);
    	chart2[key].draw(dataChart2[key], optionsChart2);
    	dataChart2[key].addRows($.parseJSON(res));
    	chart2[key].draw(dataChart2[key], optionsChart2);
    });
  }
  </script>
</body>
</html>
