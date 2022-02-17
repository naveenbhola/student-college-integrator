 <?php 
 // _p($dailyAverageData);
 	$dailyAverageData['custom'] = array();
 	$tempArr[] = array("22-May-15",4.4029999, "Average Time : 4.4029999", 4.8115, "Average Memory");
 	$tempArr = json_encode($tempArr);
 	$chartData = array();
 	global $ENT_EE_MODULES;
	
	foreach ($dataArr as $date => $serverArray) {
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
	
	

 	//foreach ($dataArr as $key=>$value) {
 	//	$arr = array();
 	//	$arr[] = $key;
 	//	$arr[] = (float)$value['shiksha']['total_sum'];
 	//	foreach($ENT_EE_MODULES as $module=>$name){
 	//		$arr[] = (float)$value[$module]['total_sum'];
 	//	}
 	//	
 	//	$chartData[] = $arr;
 	//}
 	_p($chartData);
 	
 	$chartData = json_encode($chartData);
 	
 	$chartTitle = "";
 	if($dashboardType == ENT_DASHBOARD_TYPE_SLOWPAGES)
		$chartTitle = "Most Time Consuming Sections";
	else if($dashboardType == ENT_DASHBOARD_TYPE_MEMORY)
		$chartTitle = "Most Memory Consuming Sections";
			
 	$dailyAverageChart = array();
 	// foreach ($dailyAverageData as $value) {
 	// 	$dailyAverageChart[] = array($value['l_date'], (float)$value['avg_time']);
 	// }
 	// foreach ($dailyAverageData as $value) {
 	// 	$dailyAverageChart[] = array($value['l_date'], (float)$value['avg_time']);
 	// }
 	// foreach ($dailyAverageData as $value) {
 	// 	$dailyAverageChart[] = array($value['l_date'], (float)$value['avg_time']);
 	// }
 	// foreach ($dailyAverageData as $value) {
 	// 	$dailyAverageChart[] = array($value['l_date'], (float)$value['avg_time']);
 	// }
 	
 	// $dailyAverageChart = json_encode($dailyAverageChart);

	if($dashboardType == ENT_DASHBOARD_TYPE_EXCEPTION){
		$titleText = "Exceptions";
		$heading = "Exception Dashboard";
	}else{
		$titleText = "DB Errors";
		$heading = "DB Error Dashboard";
	}

	$displayData['heading'] = $heading;
	
	$this->load->view('AppMonitor/common/header', $displayData);	
?>
		
	<div style='background-color:white;margin:0 auto;width:1200px; padding-bottom: 40px; border:0px solid red;'>	
		<div style="padding: 10px 10px 0 10px;border: 0px solid lightgrey;border-top: 0;">
		
		<h2 class='sub-head'>Trend</h2>
		
		<div id="barchart_values"></div>
		<div class="clearFix"></div>

		<br/>
		

<?php
/*
		<br/><br/>
		<h2>OverAll Top 10 <?php echo $titleText;?></h2>
		<table class="exceptionErrorTable" style="word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
			<tr>
				<th width="5%">S No.</th>
				<th width="30%"><?php echo $titleText;?> Msg</th>
				<th width="20%">Source File</th>
				<th width="5%">Line No.</th>
				<th width="20%">URL</th>
				<th width="10%">Occurences</th>
				<th width="10%">Last Occurence Time</th>
			</tr>
			<?php 
				if(empty($overallTopExceptions)){
					echo "<tr><td colspan=7><i>No Rows Found !!!</i></td></tr>";
				}
				foreach($overallTopExceptions as $key=>$overallTopExceptionsRow){
			?>
				<tr>
				<td><?php echo ($key+1);?>.</td>
				<td><?php echo $overallTopExceptionsRow['msg'];?></td>
				<td><?php echo $overallTopExceptionsRow['file']?></td>
				<td><?php echo $overallTopExceptionsRow['line_num']?></td>
				<td><?php echo $overallTopExceptionsRow['url']?></td>
				<td><?php echo $overallTopExceptionsRow['occurrence']?></td>
				<td><?php echo $overallTopExceptionsRow['addition_date']?></td>
			</tr>	
			<?php
				}
			?>
		</table>
*/
?>
		<div id="dialog" title="Detailed URLs">
		  <p style="display:none;width:700px;height:600px;"></p>
		</div>
		</div>

	</div>

	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
 	var COOKIEDOMAIN = '<?php echo $COOKIEDOMAIN; ?>';
 	$(document).ready(function(){

 		
 		animateNumber($(".animateNumber"));
	 	$(".filter-img").on("click", function(){

		 		$(".filter-section").toggle();
		 		if($(".filter-section").is(":visible")){
		 			$(".filter-img").addClass('active-filter');
		 		}else{
		 			$(".filter-img").removeClass('active-filter');
		 		}
	 	});

	 	// initialize datepicker
	  	$("#toDate, #fromDate").datepicker();
	    $("#toDate, #fromDate").datepicker("option", "dateFormat", "dd-mm-yy");
  
 	});
    google.load("visualization", "1", {packages:["corechart", 'bar']});
    google.setOnLoadCallback(drawChart);

    var dataChart2 = Array();
	var chart2 = Array();    
	var chartAndModuleNameMapping = Array();
	var key;

	var optionsChart2;

    function drawChart() {
    

      var dataTable = new google.visualization.DataTable();
        dataTable.addColumn('string', 'Module Name');
        dataTable.addColumn('number', 'Overall Exceptions');
        <?php foreach($ENT_EE_MODULES as $module => $name){
        ?>
        	dataTable.addColumn('number', 'Exceptions for <?php echo $module;?>');
        <?php
        }?>
        
        // A column for custom tooltip content
        // dataTable.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
        // dataTable.addColumn({type: 'string', role: 'style'});
        

	var options = {
        title: "",
        width: 1170,
        height: 400,
        bar: {groupWidth: "80%", groupHeight:"80%"},
        legend: { position: "none" },
        hAxis: {
	        title: 'Day',
	        baseline: 0,
			gridlines: {
				count: 6
			}
    	},
        vAxis : {title: 'No. of <?php echo $titleText;?>',textPosition : "out"},
        tooltip: { isHtml: true },
        backgroundColor: '#F8F8F8',
         animation:{
    duration: 1000,
    easing: 'out',
  }

      };

      var chart = new google.visualization.LineChart(document.getElementById('barchart_values'));
    	dataTable.addRows(<?php echo $chartData;?>);
    	chart.draw(dataTable, options);

     //  var chart = new google.visualization.ColumnChart(document.getElementById('barchart_values'));
    	// chart.draw(dataTable, options);
    	// google.visualization.events.addListener(chart, 'select', function(){
		   //  	var selectedItem = chart.getSelection()[0];
		   //  	console.log(selectedItem);
			  //   if (selectedItem) {
			  //     var value = dataTable.getValue(selectedItem.row, selectedItem.column);
			  //     alert('The user selected ' + value);
			  //   }
		   //  });

    // Chart 2
    
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
				      width: 600,
				      height: 300,
				      title: 'Day-wise Report of <?php echo $key;?>',
				      hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
				      vAxis: {minValue: 0},
				      tooltip: { isHtml: true },
				      backgroundColor: '#F3F3F3'
    		};

		    chart2[key] = new google.visualization.LineChart(document.getElementById('moduleChart_<?php echo $key;?>'));
		    chart2[key].draw(dataChart2[key], optionsChart2);
		    chartAndModuleNameMapping[key] = "<?php echo $key ?>";
		   

    <?php
    // break;
    	}
    ?>

    for(i in chart2){
		google.visualization.events.addListener(chart2[i], 'select', attachEventsCallBack(i));
    }

    function attachEventsCallBack(i){
    	return function(){attachEvents(i)};
    }
    function attachEvents(i){
    	// console.log(i);
    	// alert(i);
    	// console.log(dataChart2[i]);
    	// console.log(chart2[i]);
    	// alert(chartAndModuleNameMapping[i]);
    	var selectedItem = chart2[i].getSelection()[0];
    	// console.log(selectedItem);
	    if (selectedItem) {
	      var rowValue = dataChart2[i].getValue(selectedItem.row, 0);
	    }

	    $( "#dialog" ).dialog({modal: true, width : 900, height : 700});
	    $.ajax({
	    	data: { "charttype" : chartAndModuleNameMapping[i] , "date" : rowValue, "dashboardType" : "<?php echo $dashboardType;?>"},
	    	url : "/common/PerformanceLogger/showTopUrls",
	    	cache : false,
	    	method : "POST",
	    	beforeSend : function(){
	    		$( "#dialog" ).html("Loading...");
	    	}
	    }).done(function(res){
	    	$( "#dialog" ).html(res);
	    });
    }
    

  }

  function showCustomChart(dashboardType){
  	// alert($("#customChartMap").val());

  	$.ajax({
	    	data: { "custumChartMap" : $("#customChartMap").val(), "dashboardType" : dashboardType},
	    	url : "/common/PerformanceLogger/getDataForCustomChart",
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

  function filterResults(){
  	var cookie_arr = {};
  	cookie_arr['fromdate'] = $("#fromDate").val();
  	cookie_arr['todate'] = $("#toDate").val();
  	console.log(cookie_arr);
  	console.log(JSON.stringify(cookie_arr));

  	setCookie("shmsFilters", JSON.stringify(cookie_arr));
  }

  function setCookie(c_name,value,expireTime,timeUnit) {
	var today = new Date();
	today.setTime( today.getTime() );
	var cookieExpireValue = 0;
	expireTime = (typeof(expireTime) != 'undefined')?expireTime:0;
	timeUnit = (typeof(timeUnit) != 'undefined')?timeUnit:'days';
	if(expireTime != 0){
		if(timeUnit == 'seconds'){
			expireTime = expireTime * 1000;
		}else{
			expireTime = expireTime * 1000 * 60 * 60 * 24;
		}
		var exdate=new Date( today.getTime() + (expireTime) );
		var cookieExpireValue = exdate.toGMTString();
                if(timeUnit == 'homepage'){ cookieExpireValue = getCookie('homepage_ticker_track');}
		document.cookie=c_name+ "=" +escape(value)+";path=/;domain="+COOKIEDOMAIN+""+((expireTime==null) ? "" : ";expires="+cookieExpireValue);
	}else{
		document.cookie=c_name+ "=" +escape(value)+";path=/;domain="+COOKIEDOMAIN;
	}
    if(document.cookie== '') {
        return false;
    }
    return true;
	}

	function getCookie(c_name){
 	   if (document.cookie.length>0){
        c_start=document.cookie.indexOf(c_name + "=");
        if (c_start!=-1){
            c_start=c_start + c_name.length+1;
            c_end=document.cookie.indexOf(";",c_start);
            if (c_end==-1) { c_end=document.cookie.length ; }
            return unescape(document.cookie.substring(c_start,c_end));
        }
	    }
	    return "";
	}

	function animateNumber(elementReference){
		jQuery({ Counter: 0 }).animate({ Counter: $(elementReference).text() }, {
		  duration: 1000,
		  step: function () {
		    $(elementReference).text(Math.ceil(this.Counter));
		  }
		});
	}

  </script>

	</body>
	</html>
