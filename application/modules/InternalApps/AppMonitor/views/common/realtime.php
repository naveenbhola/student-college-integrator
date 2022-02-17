<?php
$this->load->view('AppMonitor/common/header');
$yesterdayHeading = "Top";
$counterHeading = "Slow Queries";
global $ENT_EE_VERYSLOW_SQL;

if($dashboardType == ENT_DASHBOARD_TYPE_SLOWPAGES) {
      $counterHeading = "Slow Pages";  
}
if($dashboardType == ENT_DASHBOARD_TYPE_MEMORY) {
      $counterHeading = "High Memory Pages";  
}
if($dashboardType == ENT_DASHBOARD_TYPE_EXCEPTION) {
      $counterHeading = "Exceptions";  
}
if($dashboardType == ENT_DASHBOARD_TYPE_DB_ERROR) {
      $counterHeading = "DB Errors";  
}
if($dashboardType == ENT_DASHBOARD_TYPE_CRON_ERROR) {
      $counterHeading = "Cron Errors";  
}
if($dashboardType == ENT_DASHBOARD_TYPE_CACHE) {
      $counterHeading = "Cache Heavy Pages";  
}
if($dashboardType == ENT_DASHBOARD_TYPE_JS_ERROR)
{
	$counterHeading = "JS Errors";
}
if($dashboardType == ENT_DASHBOARD_TYPE_SPEARALERTS)
{
	$counterHeading = "Alerts";
}
if($dashboardType == ENT_DASHBOARD_TYPE_BOTREPORT)
{
	$counterHeading = "Bots Detected";
    $yesterdayHeading = "";
}

if($dashboardType == ENT_DASHBOARD_TYPE_HIGH_SQL_QUERIES)
{
	$counterHeading = "High SQL Queries";
    $yesterdayHeading = "";
}
if($dashboardType == ENT_DASHBOARD_TYPE_SOLR_ERRORS)
{
	$counterHeading = "Solr Errors";
    $yesterdayHeading = "";
}
if($reportType == 'yesterday') {
?>
<div class='blockbg'>
	<div style='width:1200px; margin:0 auto;'>		
	<div style='float:left; margin-top:0px;'>	
		
	<div style='float:left; margin-left:15px; padding-top:3px;'>Any other day: </div>
	<div style='float:left; margin-left:10px; padding-top:1px;'>
		<input type="text" id="fromDatePicker" readonly="readonly" value="<?php echo $otherDate; ?>" style='width:100px; cursor: text' />
	</div>
	</div>
	<div style='float:left; margin-left:40px;'>
		<!--a href='#' onclick="updateReport();"><img src='/public/images/appmonitor/drefresh.png' width='24' /></a-->
		<a href='#' onclick="updateReport();" class='zbutton zsmall zgreen'>Go</a>
	</div>

	 <div style='clear:both'></div>
	</div>
</div>
<?php } ?>

	<div style='background-color:white;margin:0 auto;width:1200px; padding-bottom: 40px; border:0px solid red;'>
	<div style="padding: 0px 10px 0 10px;border: 0px solid lightgrey;border-top: 0;">
	
	<div class="head1" style="border:0px solid red;">
	
	<?php if(1) { ?>
	<div style='float:right; height:25px;' >
		<div id="real-filters">
		<?php if(in_array($dashboardType, array(ENT_DASHBOARD_TYPE_SLOWQUERY))) { ?>
		<div class="parent-onoffswitch">
		    <p class="real-head-p">Very Slow (><?php echo $ENT_EE_VERYSLOW_SQL;?> sec)</p>
			<div class="onoffswitch" style="display: inline-block;">		
			    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="mysqlslow_myonoffswitch">
			    <label class="onoffswitch-label" for="mysqlslow_myonoffswitch">
		    
			        <span class="onoffswitch-inner"></span>
			        <span class="onoffswitch-switch"></span>
			    </label>
			</div>
		</div>	
  		<div class="parent-onoffswitch">
		    <p class="real-head-p">Locked</p>
			<div class="onoffswitch" style="display: inline-block;">		
			    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="mysqllock_myonoffswitch">
			    <label class="onoffswitch-label" for="mysqllock_myonoffswitch">
		    
			        <span class="onoffswitch-inner"></span>
			        <span class="onoffswitch-switch"></span>
			    </label>
			</div>
		</div>	
	<?php } ?>
  	<?php if(in_array($dashboardType, array(ENT_DASHBOARD_TYPE_SLOWPAGES, ENT_DASHBOARD_TYPE_MEMORY, ENT_DASHBOARD_TYPE_CACHE, ENT_DASHBOARD_TYPE_HIGH_SQL_QUERIES))) { ?>
  		<div class="parent-onoffswitch">
		    <p class="real-head-p">Exclude Cron</p>
			<div class="onoffswitch" style="display: inline-block;">		
			    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch">
			    <label class="onoffswitch-label" for="myonoffswitch">
		    
			        <span class="onoffswitch-inner"></span>
			        <span class="onoffswitch-switch"></span>
			    </label>
			</div>
		</div>	
	<?php } 
	if($reportType == 'realtime') {?>
	  <p class="real-head-p">Pause</p>
	  <a href='javascript:void(0);' onclick="doRefreshControl();"><img src='/public/images/appmonitor/pause.png' width='24' id='refreshControl' /></a>
	<?php } ?>
	  </div>
	  </div>
	<div class='clearFix'></div>
	<?php } ?>
		

	  <div style='width:100%;min-height:220px;'>
		 <div class="exceptionCounter" id="vexceptionCounter" style='width:250px;'>
			<div style="font-size: 21px;margin-top: 37px;" id='counterTitle'><?php echo $counterHeading; ?></div>
			<div class="animateNumber" style="font-size: 75px;" id='countVal'><?php echo $resultCount; ?></div>
			<div id='counterTimeWindow' style='margin-top:5px; font-weight:bold; color:#777;'></div>
		</div>
		<div id="chart_div" style="float:left;width:900px;font-weight:bold;text-align:center; margin-left: 20px;">
			<br /><br /><br /><br />
		   Loading....
		</div>
	<div class='clearFix'></div>
	</div>
	<div style="width:100%; padding:0px; margin-top:0px;">
		<div style='float:left'>
			<h2 class='sub-head' id='tableTitle' style='margin:0'><?php echo $reportType == 'realtime' ? "Latest" : $yesterdayHeading; ?> <span id='tableHeading'><?php echo $tableHeading; ?></span></h2>
		</div>
		<div style='float:right; margin-right:12px;' id='sortbox'>
		</div>
		<div class="clearFix"></div>
		<div id='resultTable'>
			<?php echo $resultTable; ?>
		</div>
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
var timeDistributionData = $.parseJSON('<?php echo json_encode($timeDistribution); ?>');
google.load("visualization", "1", {packages:["corechart","bar"]});
google.setOnLoadCallback(drawChart);
$(".exceptionCounter").css("visibility", "visible");
var doRefresh = true;

$(document).ready(function(){

	$("#fromDatePicker").datepicker();
	
	<?php if(in_array($dashboardType, array(ENT_DASHBOARD_TYPE_SLOWQUERY))) { ?>
		
		var is_mysqlveryslow = getCookie('mysqlVerySlow');
		if(is_mysqlveryslow != '' && is_mysqlveryslow == 1){
			$("#mysqlslow_myonoffswitch").attr('checked', 'checked');
		}

		$("#mysqlslow_myonoffswitch").on('click',function(){
			if($(this).prop('checked') == false){
				document.cookie = "mysqlVerySlow=0;path=/;domain=<?php echo COOKIEDOMAIN; ?>";
			}else{
				document.cookie = "mysqlVerySlow=1;path=/;domain=<?php echo COOKIEDOMAIN; ?>";
			}
			doRefreshPage(-1);
		});

		var is_mysqlLockQueries = getCookie('mysqlLockQueries');
		if(is_mysqlLockQueries != '' && is_mysqlLockQueries == 1){
			$("#mysqllock_myonoffswitch").attr('checked', 'checked');
		}

		$("#mysqllock_myonoffswitch").on('click',function(){
			if($(this).prop('checked') == false){
				document.cookie = "mysqlLockQueries=0;path=/;domain=<?php echo COOKIEDOMAIN; ?>";
			}else{
				document.cookie = "mysqlLockQueries=1;path=/;domain=<?php echo COOKIEDOMAIN; ?>";
			}
			doRefreshPage(-1);
		});
	<?php } ?>
	<?php if(in_array($dashboardType, array(ENT_DASHBOARD_TYPE_SLOWPAGES, ENT_DASHBOARD_TYPE_MEMORY, ENT_DASHBOARD_TYPE_CACHE, ENT_DASHBOARD_TYPE_HIGH_SQL_QUERIES))) { ?>
		var x = getCookie('excludeCron');
		if(x != '' && x == 1){
			$("#myonoffswitch").attr('checked', 'checked');
		}

		$("#myonoffswitch").on('click',function(){
			if($(this).prop('checked') == false){
				document.cookie = "excludeCron=0;path=/;domain=<?php echo COOKIEDOMAIN; ?>";
			}else{
				document.cookie = "excludeCron=1;path=/;domain=<?php echo COOKIEDOMAIN; ?>";
			}
			doRefreshPage(-1);
		});
	<?php } ?>
	
	<?php if($reportType == 'realtime') { ?>
		animateNumber($(".animateNumber").text(),$(".animateNumber"));
		refreshPage();
	<?php } ?>	
});


function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function animateNumber(text, elementReference){
        var a = Number($(elementReference).text());
        $(elementReference).html(text);
        $(elementReference).prop('Counter',a).animate({
            Counter: $(elementReference).text()
        }, {
            duration: 1000,
            easing: 'swing',
            step: function (now) {
                $(elementReference).text(Math.ceil(now));
            }
        });
    }

function refreshPage()
{
	setTimeout(function() {
		if (doRefresh) {
			doRefreshPage(-1);
		}
		else {
			refreshPage();
		}
	},3000);	
}

function updateReport()
{
	var selectedDate = $('#fromDatePicker').val();
	if(selectedDate) {
		$('#chart_div').html("<img src='/public/images/appmonitor/loader.gif' style='margin-top:80px;' />");
		$('#countVal').html("<div style='font-size:30px; margin-top:30px; color:#ff5722'>Loading</span>");
		selectedDateParts = selectedDate.split("/");
		formattedDate = selectedDateParts[2]+"-"+selectedDateParts[0]+"-"+selectedDateParts[1];
		doRefreshPage('-1', formattedDate);
	}
}

function doRefreshPage(rowNum, selectedDate)
{
	if(typeof(selectedDate) == 'undefined' || !selectedDate) {
		selectedDate = "<?php echo $realtimeDate; ?>";
	}
	
	$.getJSON("<?php echo $ajaxURL; ?>/<?php echo $selectedFilter; ?>/"+selectedDate+"/1/"+rowNum+"<?php if($reportType == 'yesterday') { echo "/1"; } ?>",function(data) {
			if (rowNum == -1) {
				$('#countVal').html(data['count']);
				animateNumber($(".animateNumber").text(),$(".animateNumber"));
				timeDistributionData = data['timeDistribution'];
				drawChart();
			}
			else {
				$('#countVal').html(data['numResults']);
				$('#vexceptionCounter').css('background','#F3FAF2');
				$('#vexceptionCounter').css('border','1px solid #C3CAFF');
				
				timeWindowEnd = data['timeWindowEnd'];
				timeWindowEndParts = timeWindowEnd.split(':');
				timeWindowEnd = timeWindowEndParts[0]+':'+timeWindowEndParts[1];
				if (timeWindowEndParts[1] == '59') {
					if (timeWindowEndParts[0] == '23') {
						timeWindowEnd = '00:00';
					}
					else {
						timeWindowHour = parseInt(timeWindowEndParts[0])+1;
						timeWindowEnd = (timeWindowHour < 9 ? "0" : "")+timeWindowHour+":00";
					}
				}
				
				timeWindowStart = data['timeWindowStart'];
				timeWindowStartParts = timeWindowStart.split(':');
				timeWindowStart = timeWindowStartParts[0]+':'+timeWindowStartParts[1];
				
				$('#tableTitle').html(data['numResults']+" <span id='tableHeading'>"+$('#tableHeading').text()+"</span> between "+timeWindowStart+" and "+timeWindowEnd);
				
				$('#counterTitle').css('margin-top','22px');
				$('#counterTimeWindow').html("Between "+timeWindowStart+" and "+timeWindowEnd);
				
				$('#sortbox').html(data['sortbox']);
			}
			$('#resultTable').html(data['resultTable']);
			if(typeof(selectedDate) == 'undefined' || !selectedDate) {
				refreshPage();
			}
		});
}

function doRefreshControl()
{
	if (doRefresh) {
		$('#refreshControl').attr('src','/public/images/appmonitor/restart.png');
		doRefresh = false;
	}
	else {
		$('#refreshControl').attr('src','/public/images/appmonitor/pause.png');
		doRefresh = true;
	}
}

function drawChart() {
	cdata = [["Element", "", { role: "style" } ]];
	for (key in timeDistributionData) {
		cdata.push([key, timeDistributionData[key], '#76A7FA']);
	}

	var data = google.visualization.arrayToDataTable(cdata);
	var view = new google.visualization.DataView(data);
	
	var options = {
	  title: "Half hourly distribution",
	  width: 900,
	  height: 200,
	  chartArea: {width: '90%'},
	  bar: {groupWidth: "85%"},
	  hAxis: {textStyle: {fontSize: 10}, gridlines: {color: '#F9F9F9'}},
	  vAxis: {textStyle: {fontSize: 10}},
	  legend: {position: 'none'}
	};
	var chart = new google.visualization.ColumnChart(document.getElementById("chart_div"));
	chart.draw(view, options);
	
	var selectHandler = function(e) {
	  $('#real-filters').hide();
	  row = chart.getSelection()[0]['row'];
	  doRefresh = true;
	  doRefreshControl();
	  doRefreshPage(row);
	  
	  var view = new google.visualization.DataView(data);
	  view.setColumns([0, 1, {
		  type: 'string',
		  role: 'style',
		  calc: function (dt, i) {
			  return (i == row) ? 'color: #76a7fa' : 'color: #cccccc';
		  }
	  }]);

	  chart.draw(view, options);
	}
	
	google.visualization.events.addListener(chart, 'select', selectHandler);
}

function showSlowPageLog(encodedData)
{
	var data = JSON.parse(atob(encodedData));
    $("body").addClass("noscroll");
    $("#voverlay").show();
    $("#vdialog_inner").css('top', $('body').scrollTop()+20);
    $("#vdialog").show();
    $("#vdialog_content").html("<div style='margin:50px; text-align:center;'><img src='/public/images/appmonitor/loader.gif' /></div>");
    var logHTML = "<div style='height:430px; overflow: auto; margin-top:20px;'><table class='exceptionErrorTable' width='870' style='word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;'>";
    logHTML +="<tr><th width='100'>BENCHMARKS SECTION</th><th width='100'>TIME(ms)</th></tr>";    
    for (var key in data) {
    	logHTML += "<tr>";
    	var sectionName = key.toUpperCase().replace(/[_-]/g," "); 
   		 logHTML +="<td>"+sectionName+"</td>";
   		 logHTML +="<td>"+data[key]+"</td>";
 
	logHTML +="</tr>";
	}
	logHTML +="</table></div>";    
    $("#vdialog_content").html(logHTML);
 }

 function showDetails(ajaxurl, itemid){

 	$("body").addClass("noscroll");
    $("#voverlay").show();
    $("#vdialog_inner").css('top', $('body').scrollTop()+20);
    $("#vdialog").show();
    $("#vdialog_content").html("<div style='margin:50px; text-align:center;'><img src='/public/images/appmonitor/loader.gif' /></div>");
 	$.ajax({
            url:ajaxurl,
            type: "POST",
            data: {'itemid' : itemid},
            async:false,
            success: function(result){
    			var logHTML = "<div style='height:430px; overflow: auto; margin-top:20px;'>";
			    logHTML += result;
				logHTML +="</div>";         	
				$("#vdialog_content").html(logHTML);
            }
        });
 }
</script>
</html>
