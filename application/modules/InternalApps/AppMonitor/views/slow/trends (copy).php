<?php 
$dailyAverageData['custom'] = array();

$chartData = array();
foreach ($dataArr as $value) {
	$chartData[] = array($value['module_name'], (float)$value['average'], "<b>Path : </b>".$value['module_name'].'/'.$value['controller_name'].'/'.$value['method_name']."<br/><b>".($dashboardType == ENT_DASHBOARD_TYPE_SLOWPAGES ? 'Time Taken' : 'Memory').": </b>".$value['average']);
}
$chartData = json_encode($chartData);

$chartTitle = "";
if($dashboardType == ENT_DASHBOARD_TYPE_SLOWPAGES) {
   $chartTitle = "";
}
else if($dashboardType == ENT_DASHBOARD_TYPE_MEMORY) {
   $chartTitle = "";
}

$dailyAverageChart = array();

if($dashboardType == ENT_DASHBOARD_TYPE_SLOWPAGES) {
   $heading = "Slow Pages Dashboard";
}
else {
   $heading = "High Memory Consuming Dashboard";
}

$displaydata['heading'] = $heading;

/**
* 	For main charts
*/
$titleText = "No. of pages above threshold";
$this->load->view('AppMonitor/common/header', $displaydata); 
?>
		
<div class="parentContainer">
	<div class="innerContainer">
	<div style="width:100%; padding:0px; margin-top:15px;">
	 
		<div id="barchart_values" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
		
		<h2 class='sub-head' style='margin-top:40px;'>Trend For Unique Pages</h2>
		<div id="unique_barchart_values" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

		<?php $this->load->view("AppMonitor/slow/chartView");?>
		
	</div>

	<br/><br/><br/>
	<div style="margin-left:10px;">
		<h2 style="margin-bottom:10px;">For any other page : </h2>
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
   var COOKIEDOMAIN = '<?php echo $COOKIEDOMAIN; ?>';
   $(document).ready(function(){

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
		 dataTable.addColumn('string', 'Date');
		 <?php
			 foreach ($modules as $module) {
				 ?>
				 dataTable.addColumn('number', '<?=''.$module?>');
				 <?php
			 }
		 ?>

         dataTable.addRows(<?php echo json_encode($trendsChartData);?>);
	   
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
	        vAxis : {title: '<?php echo $titleText;?>',textPosition : "out"},
	        tooltip: { isHtml: true },
	        backgroundColor: '#F8F8F8',
	        colors : <?php echo json_encode($colors);?>,
	         animation:{
			    duration: 1000,
			    easing: 'out',
			 }

      	};

 		var chart = new google.visualization.LineChart(document.getElementById('barchart_values'));
 		chart.draw(dataTable, options);
		
		dataTable = new google.visualization.DataTable();
		 dataTable.addColumn('string', 'Date');
		 <?php
			 foreach ($modules as $module) {
				 ?>
				 dataTable.addColumn('number', '<?=''.$module?>');
				 <?php
			 }
		 ?>
		 dataTable.addRows(<?php echo json_encode($uniqueTrendsChartData);?>);
		
		 var chart = new google.visualization.LineChart(document.getElementById('unique_barchart_values'));
		 chart.draw(dataTable, options);
    
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

	    $.ajax({
	    	data: { "charttype" : chartTypeData , "date" : rowValue, "dashboardType" : "<?php echo $dashboardType;?>"},
	    	url : "/AppMonitor/Dashboard/showTopUrls",
	    	cache : false,
	    	method : "POST",
	    	beforeSend : function(){
	    	}
	    }).done(function(res){
			$("#vdialog_inner").css('top', $('body').scrollTop()+20);
			$("#vdialog").show();
			$("#vdialog_content").html(res);
	    });
   }
  }

  function showCustomChart(dashboardType){
  	// alert($("#customChartMap").val());

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

  function filterResults(){
  	var cookie_arr = {};
  	cookie_arr['fromdate'] = $("#fromDate").val();
  	cookie_arr['todate'] = $("#toDate").val();
  	// console.log(cookie_arr);
  	// console.log(JSON.stringify(cookie_arr));

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

  </script>

	</body>
	</html>
