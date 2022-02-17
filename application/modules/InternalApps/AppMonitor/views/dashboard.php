 <?php 

	$dataArr    = array('exception'   => array('count' => 123, 'data' => array('',  12,     100,    11,   9, 2,44)),
						'dberror'   => array( 'count' => 33, 'data' => array('',  13,    400,    101,   29, 22,44)),	
						'cronerror'        => array('count' => 44, 'data' => array('',  3,    40,    101,   49, 22,44)),
						'slowquery' => array('count' => 994, 'data' => array('',  13,    400,    11,   19, 22,44)),
						'slowpages'   => array('count' => 23, 'data' => array('',  3,    40,    101,   2, 22,44)),
						'memory'     => array('count' => 920, 'data' => array('',  13,    400,    11,   4, 22,44))); 

	$dataArr = $realtime;
	
	global $ENT_EE_MODULES_COLORS;
	global $ENT_EE_MODULES;
	
	$colors = array();
	foreach ($ENT_EE_MODULES_COLORS as $value) {
		$colors[] = $value;
	}
	$colors = json_encode($colors);


	global $ENT_EE_SERVERS_COLORS;
	global $ENT_EE_SERVERS;
	$serverKeys = array_keys($ENT_EE_SERVERS);
	
	global $SHIKSHA_PROD_SERVERS;
	global $SHIKSHA_PROD_SERVERS_COLORS;
	$prodServerKeys = array_keys($SHIKSHA_PROD_SERVERS);
    
	$prodServerColors = array();
	foreach ($SHIKSHA_PROD_SERVERS_COLORS as $value) {
		$prodServerColors[] = $value;
	}
	$prodServerColors = json_encode($prodServerColors);
    
    global $BOT_STATUSES;
	global $BOT_STATUS_COLORS;
	$botStatusKeys = array_keys($BOT_STATUSES);
    
    $botStatusColors = array();
	foreach ($BOT_STATUS_COLORS as $value) {
		$botStatusColors[] = $value;
	}
	$botStatusColors = json_encode($botStatusColors);

	global $HTTPSTATUSCODES;
	global $HTTPSTATUSCODES_COLORS;
	$HTTPStatusCodeKeys = array_keys($HTTPSTATUSCODES);
    
    $HTTPStatusCodeColors = array();
	foreach ($HTTPSTATUSCODES_COLORS as $value) {
		$HTTPStatusCodeColors[] = $value;
	}
	$HTTPStatusCodeColors = json_encode($HTTPStatusCodeColors);
	
	$colors1 = array();
	foreach ($ENT_EE_SERVERS_COLORS as $value) {
		$colors1[] = $value;
	}
	$colors1 = json_encode($colors1);
	$this->load->view('AppMonitor/common/header');
?>

		
	<div style='background-color:white;margin:0 auto;width:1200px; border:0px solid red; min-height:550px;'>	
		<div style="padding: 13px 10px 0 10px;border: 0px solid lightgrey;border-top: 0;">
   
		<div class="clearFix"></div>
		<div class='cron_lag' style='display:none; background: #FFE6E6; padding:15px'>
			<br />
			<h1 style='padding-left:5px; font-size:16px;'>Cron Lags</h1><br />
		    <table width='90%' cellpadding='0' cellspacing='0'>
		        <?php foreach($cronsForLagMonitoring as $cronId => $cron) { ?>
		        <tr style='display:none;height:10px;' id='tr_cronLag_<?php echo $cronId; ?>'>
		            <td width='<?php if($_REQUEST['onMonitor'] == 1) echo "250"; else echo "700"; ?>' class='tdleft' style='border-right:none; font-size:15px; color:#444; border-bottom: 1px solid #ddd; padding:5px 15px;'><?php echo $cron['name']; ?></td>
		            <td class='tdright' style='border-right:none; font-size:15px;  border-bottom: 1px solid #ddd;  padding:5px 15px;' id='cronLag_<?php echo $cronId; ?>'><img src='/public/images/ldb_ajax-loader.gif' /></td>
		        </tr>
		        <?php } ?>
		    </table>			
		</div>
		
		
		<div style='background:#FCFF9C; margin:0px 35px 15px 0px; padding:10px; color:#444; display:none' id='yesterday_dashboard'>You are viewing yesterday's dashboard.</div>
		<div class="clearFix"></div>
		
		<div>
			<div class="dashboardChartCell">
				<h1 class='metric_head'>HTTP Status Codes <span id='metric_head_httpstatuscodes' class='percent_change'></span></h1>
				<div class='statbox'>
					<div id="animateNumber_httpstatuscodes" ><span class='number' id='httpstatuscodes'>0</span> <span class='abnormality'></span></div>
					<div id="chart_div_httpstatuscodes"></div>
				</div>
			</div>
			<div class="dashboardChartCell">
				<h1 class='metric_head'>Exceptions <span id='metric_head_exception' class='percent_change'></span></h1>
				<div class='statbox'>
					<div id="animateNumber_exception" ><span class='number' id='exception'>0</span> <span class='abnormality'></span></div>
					<div id="chart_div_exception"></div>
				</div>
			</div>
			<div class="dashboardChartCell">
				<h1  class='metric_head'>DB Errors <span id='metric_head_dberror' class='percent_change'></span></h1>
				<div class='statbox'>
					<div id="animateNumber_dberror"><span class='number' id='dberror'>0</span> <span class='abnormality'></span></div>
					<div id="chart_div_dberror"></div>
				</div>
			</div>
			<div class="dashboardChartCell">
				<h1  class='metric_head'>Solr Errors <span id='metric_head_solrerrors' class='percent_change'></span></h1>
				<div class='statbox'>
					<div id="animateNumber_solrerrors"><span class='number' id='solrerrors'>0</span> <span class='abnormality'></span></div>
					<div id="chart_div_solrerrors"></div>
				</div>
			</div>
			<div class="dashboardChartCell">
				<h1  class='metric_head' >Cron Failures <span id='metric_head_cronerror' class='percent_change'></span></h1>
				<div class='statbox'>
					<div id="animateNumber_cronerror" ><span class='number' id='cronerror'>0</span> <span class='abnormality'></span></div>
					<div id="chart_div_cronerror"></div>
				</div>
			</div>
			<div class="dashboardChartCell">
				<h1  class='metric_head' >JS Errors <span id='metric_head_jserror' class='percent_change'></span></h1>
				<div class='statbox'>
					<div id="animateNumber_jserror" ><span class='number' id='jserror'>0</span> <span class='abnormality'></span></div>
					<div id="chart_div_jserror"></div>
				</div>
			</div>
			<div class="dashboardChartCell">
				<h1  class='metric_head' >Slow Pages <span id='metric_head_slowpages' class='percent_change'></span></h1>
				<div class='statbox'>
					<div id="animateNumber_slowpages"><span class='number' id='slowpages'>0</span> <span class='abnormality'></span></div>
					<div id="chart_div_slowpages"></div>
				</div>
			</div>
			
			<div class="dashboardChartCell">
				<h1  class='metric_head' >High Memory Pages <span id='metric_head_memory' class='percent_change'></span></h1>
				<div class='statbox'>
					<div id="animateNumber_memory"><span class='number' id='memory'>0</span> <span class='abnormality'></span></div>
					<div id="chart_div_memory"></div>
				</div>
			</div>
			<div class="dashboardChartCell">
				<h1  class='metric_head' >Cache Heavy Pages <span id='metric_head_heavycache' class='percent_change'></span></h1>
				<div class='statbox'>
					<div id="animateNumber_heavycache" ><span class='number' id='heavycache'>0</span> <span class='abnormality'></span></div>
					<div id="chart_div_heavycache"></div>
				</div>
			</div>
			<div class="dashboardChartCell" >
				<h1  class='metric_head' >Slow Queries <span id='metric_head_slowquery' class='percent_change'></span></h1>
				<div class='statbox'>
					<div id="animateNumber_slowquery" ><span class='number' id='slowquery'>0</span> <span class='abnormality'></span></div>
					<div class='mychartdiv' id="chart_div_slowquery"></div>
				</div>
			</div>
			<div class="dashboardChartCell" >
				<h1  class='metric_head'>High SQL Queries<span id='metric_head_highsqlqueries' class='percent_change'></span></h1>
				<div class='statbox'>
					<div id="animateNumber_highsqlqueries"><span class='number' id='highsqlqueries'>0</span> <span class='abnormality'></span></div>
					<div id="chart_div_highsqlqueries"></div>
				</div>
			</div>
			<div class="dashboardChartCell" >
				<h1  class='metric_head'>SPEAR Alerts <span id='metric_head_spear' class='percent_change'></span></h1>
				<div class='statbox'>
					<div id="animateNumber_spearalerts"><span class='number' id='spear'>0</span> <span class='abnormality'></span></div>
					<div id="chart_div_spearalerts"></div>
				</div>
			</div>
			<div class="dashboardChartCell">
				<h1  class='metric_head' >SQLi Vulnerabilities <span id='metric_head_sqli' class='percent_change'></span></h1>
				<div class='statbox'>
					<div id="animateNumber_sqli" ><span class='number' id='sqli'>0</span> <span class='abnormality'></span></div>
					<div id="chart_div_sqli"></div>
				</div>
			</div>
			<div class="dashboardChartCell">
				<h1 class='metric_head' >Bot Detection</h1><span id='metric_head_botreport' class='percent_change'></span>
				<div class='statbox'>
					<div id="animateNumber_botreport" ><span class='number' id='botreport'>0</span> <span class='abnormality'></span></div>
					<div id="chart_div_botreport"></div>
				</div>
			</div>
		</div>


		</div>

	</div>
	<style type="text/css">
	.cron_lag
	{
		border: 1px solid #d3d3d3;
	    box-sizing: border-box;
	    margin: 10px;
	    margin-bottom: 50px;
	    padding: 10px;
	    width: 95%;	
	}
	</style>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>

	<script type="text/javascript">
	var updateChartTimer;
	var cronStatusTimer;
	var abnormalityTimer;
	var data = <?php echo json_encode($dataArr); ?>;
		$(document).ready(function(){
			<?php if(!$prevDayFlag){
				?>
					updateChartTimer = setInterval(function(){updateChart();}, 5000);
					refreshCronStatus();
					cronStatusTimer = setInterval(function(){refreshCronStatus();}, 300000);
					setTimeout(function(){
						abnormalityTimerHandler(1);
					},2000);
					abnormalityTimer = setInterval(function(){abnormalityTimerHandler();}, 60000);
				<?php
			}
			?>
		
		});
		google.load("visualization", "1", {packages:["corechart", 'bar']});
		google.setOnLoadCallback(updateChart);
		
        function animateNumber(text, elementReference){
        
    	var a = Number($(elementReference).text());
        $(elementReference).find('.number').html(text);
        $(elementReference).find('.number').prop('Counter',a).animate({
            Counter: $(elementReference).find('.number').text()
        }, {
            duration: 1000,
            easing: 'swing',
            step: function (now) {
                $(elementReference).find('.number').text(Math.ceil(now));
            }
        });
        
    }


	function refreshCronStatus() 
	{

		$status = false;
		<?php foreach($cronsForLagMonitoring as $cronId => $cron) { ?>
		$('#cronLag_<?php echo $cronId; ?>').html("<img src='/public/images/ldb_ajax-loader.gif' />");
	        $.post('/AppMonitor/Dashboard/getLag/<?php echo $cronId; ?>',{},function(data) {
	            $('#cronLag_<?php echo $cronId; ?>').html(data);
	            var lagLimit = <?php echo $cron['lagLimit']; ?>;
	            if (data > lagLimit) {
	            	$status = true;
	            	$(".cron_lag").show();
	            	$('#tr_cronLag_<?php echo $cronId; ?>').show();
	                $('#cronLag_<?php echo $cronId; ?>').css('background','#e74c3c');
	                $('#cronLag_<?php echo $cronId; ?>').css('color','#eee');
	            } else{
	            	$('#tr_cronLag_<?php echo $cronId; ?>').hide();
	            	$('#cronLag_<?php echo $cronId; ?>').css('background','#fff');
	                $('#cronLag_<?php echo $cronId; ?>').css('color','#000');
	            }
	        })
	    <?php } ?>	
	    if($status == false){
	    	$(".cron_lag").hide();
	    }
	}

	function updateChart(day){

		if(day == undefined){
			day == "today"
		}

		$.ajax({
			url : "/AppMonitor/Dashboard/updateDashboard/"+day,
			method : "POST",
			success : function(res){
				data = JSON.parse(res);
				drawChart();
			}
		});
	}

	var chartObj = {};
	var dataTables = {};
	var teamUrlIdentifiers = [];
	<?php
		global $ENT_EE_MODULES;
		foreach ($ENT_EE_MODULES as $key => $value) {
	?>
			teamUrlIdentifiers.push("<?php echo $key;?>");
	<?php
		}
	?>
	var keyArr = {
					 "server" : ["Master:81", "Slave01:71", "Slave02:72"],
					 "prodserver": ["<?php echo implode('", "', $prodServerKeys); ?>"],
                     //"botstatus" : ["Bad Bot", "IP Blocked", "User Agent Blocked"],
                     "botreport" : ["-1", "-2", "-3"],
                     "statuscode" : ["<?php echo implode('", "', $HTTPStatusCodeKeys); ?>"],
					 "team" : teamUrlIdentifiers
	};
	var urlMap = {"httpstatuscodes":"HTTPStatusCodes", "exception" : "Exceptions", "dberror" : "DBErrors", "slowpages" : "SlowPages", "memory" : "HighMemoryPages", "slowquery" : "SlowQueries", "cronerror" : "CronErrors", "heavycache" : "CacheHeavyPages",'jserror':"JSErrors", 'spearalerts' : "SpearAlerts", 'botreport' : "BotReport", "sqli" : "SQLi", "highsqlqueries":"HighSQLQueries", "solrerrors":"SolrErrors"};
	var keyTypeArr = {};
	var dashboardMode = 'realtime';
	
	 function drawChart() {
	 	for (key in data) {

	 		// $("#animateNumber_"+key).html(data[key]['count']);
	 		// animateNumber($("#animateNumber_"+key).text(),$("#animateNumber_"+key));	
            animateNumber(data[key]['count'],$("#animateNumber_"+key));    
	 	
      		// Create and populate the data table.
      		var dataTable = new google.visualization.DataTable();
		    dataTable.addColumn('string', 'Year');
		    if(key == "<?php echo ENT_DASHBOARD_TYPE_SLOWQUERY?>"){
		    	dataTable.addColumn('number', "<?php echo $serverKeys[0]?>");
			    dataTable.addColumn('number', "<?php echo $serverKeys[1]?>");
			    dataTable.addColumn('number', "<?php echo $serverKeys[2]?>");
			   keyTypeArr[key] = "server";
		    }
            else if(key == "<?php echo ENT_DASHBOARD_TYPE_SPEARALERTS?>"){
				<?php foreach($prodServerKeys as $prodServerKey) { ?>
		    	dataTable.addColumn('number', "<?php echo $prodServerKey; ?>");
				<?php } ?>
			   keyTypeArr[key] = "prodserver";
		    }
            else if(key == "<?php echo ENT_DASHBOARD_TYPE_BOTREPORT?>"){
				<?php foreach($BOT_STATUSES as $botStatusKey) { ?>
		    	dataTable.addColumn('number', "<?php echo $botStatusKey; ?>");
				<?php } ?>
			   keyTypeArr[key] = "botreport";
		    }else if(key == "<?php echo ENT_DASHBOARD_TYPE_HTTPSTATUSCODES?>"){
				<?php foreach($HTTPSTATUSCODES as $key => $HTTPStatusCodeKey) { ?>
		    	dataTable.addColumn('number', "<?php echo $HTTPStatusCodeKey; ?>");
				<?php } ?>
			   keyTypeArr[key] = "statuscode";
		    }
            else {
            	<?php
            		global $ENT_EE_MODULES;
					foreach ($ENT_EE_MODULES as $key => $value) {
				?>
						dataTable.addColumn('number', '<?php echo $value;?>');
				<?php
					}
            	?>
		    	// dataTable.addColumn('number', 'Mobile App');
		    	// dataTable.addColumn('number', 'Listing');
			    // dataTable.addColumn('number', 'UGC');
			    // dataTable.addColumn('number', 'StudyAbroad');
			    // dataTable.addColumn('number', 'LDB');
			    // dataTable.addColumn('number', 'Common');
			    // dataTable.addColumn('number', 'Others');
			    // dataTable.addColumn('number', 'Service');
				keyTypeArr[key] = "team";
		    }
		    
		    dataTable.addRow(data[key]['data']);
			
			dataTables[key] = dataTable;
			
			chartObj[key] = new google.visualization.BarChart(document.getElementById('chart_div_'+key));

			// Create and draw the visualization.
			if(key == "<?php echo ENT_DASHBOARD_TYPE_SLOWQUERY?>") {
			   chartObj[key].draw(dataTable, { width:"100%", height:45,
			       backgroundColor : "#E6F2FF",
			         isStacked: true,
			         legend : {position: 'top'},
			         chartArea: {width: '96%', height: '60%'},
			         colors : <?php echo $colors1;?>,
			     	hAxis: {baselineColor: 'none',
				         	textPosition: 'none',
						ticks : [0, .3, .6, .9, 1],
							    gridlines: {
							        color: 'transparent'
							    }
					}}
			  );
		 		}
				else if(key == "<?php echo ENT_DASHBOARD_TYPE_SPEARALERTS?>") {
			   chartObj[key].draw(dataTable, { width:"100%", height:45,
			       backgroundColor : "#E6F2FF",
			         isStacked: true,
			         legend : {position: 'top'},
			         chartArea: {width: '96%', height: '60%'},
			         colors : <?php echo $prodServerColors;?>,
			     	hAxis: {baselineColor: 'none',
				         	textPosition: 'none',
						ticks : [0, .3, .6, .9, 1],
							    gridlines: {
							        color: 'transparent'
							    }
					}}
			  );
		 		}
                else if(key == "<?php echo ENT_DASHBOARD_TYPE_BOTREPORT?>") {
			   chartObj[key].draw(dataTable, { width:"100%", height:45,
			       backgroundColor : "#E6F2FF",
			         isStacked: true,
			         legend : {position: 'top'},
			         chartArea: {width: '96%', height: '60%'},
			         colors : <?php echo $botStatusColors;?>,
			     	hAxis: {baselineColor: 'none',
				         	textPosition: 'none',
						ticks : [0, .3, .6, .9, 1],
							    gridlines: {
							        color: 'transparent'
							    }
					}}
			  );
		 		}else if(key == "<?php echo ENT_DASHBOARD_TYPE_HTTPSTATUSCODES?>") {
			   chartObj[key].draw(dataTable, { width:"100%", height:45,
			       backgroundColor : "#E6F2FF",
			         isStacked: true,
			         legend : {position: 'top'},
			         chartArea: {width: '96%', height: '60%'},
			         colors : <?php echo $HTTPStatusCodeColors;?>,
			     	hAxis: {baselineColor: 'none',
				         	textPosition: 'none',
						ticks : [0, .3, .6, .9, 1],
							    gridlines: {
							        color: 'transparent'
							    }
					}}
			  );
		 		}
		 		else{
			  chartObj[key].draw(dataTable,
			       {width:"100%", height:45,
			       backgroundColor : "#E6F2FF",
			         isStacked: true,
			         legend : {position: 'none'},
			         chartArea: {width: '96%', height: '60%'},
			         colors : <?php echo $colors;?>,
			     	hAxis: {baselineColor: 'none',
				         	textPosition: 'none',
						ticks : [0, .3, .6, .9, 1],
							    gridlines: {
							        color: 'transparent'
							    }
					}}
			  );

		 		}
			   google.visualization.events.addListener(chartObj[key], 'select', attachEventsCallBack(key));
		}
	
		$('#voverlay').hide(); 	
		$('body').removeClass('noscroll');
  	}

	function switchChart(day) 
	{
			$('#voverlay').html("<div style='margin:150px; text-align:center;'><img src='/public/images/appmonitor/overlayloader.svg' /></div>");
			$('#voverlay').show();
			$('body').addClass('noscroll');
			if (day == 'today') {
			   dashboardMode = 'realtime';
			   $('#btn_yesterday').removeClass('ygreen');
			   $('#btn_yesterday').addClass('ywhite');
			   $('#btn_today').removeClass('ywhite');
			   $('#btn_today').addClass('ygreen');
			   $('#yesterday_dashboard').hide();
			   updateChart();
                           updateChartTimer = setInterval(function(){updateChart();}, 5000);
                           refreshCronStatus();
                           cronStatusTimer = setInterval(function(){refreshCronStatus();}, 300000);
                           setTimeout(function(){
								abnormalityTimerHandler(1);
							},2000);
							abnormalityTimer = setInterval(function(){abnormalityTimerHandler();}, 60000);

                           $("#refresh_link").show();
                           $(".percent-change").show();
			}
			else {
				$(".percent-change").hide();
			   dashboardMode = 'yesterday';
			   $(".cron_lag").hide();
                           clearTimeout(cronStatusTimer);
                           clearTimeout(updateChartTimer);
                           clearTimeout(abnormalityTimer);
                           $("#refresh_link").hide();
                           $.get('/AppMonitor/Dashboard/updateDashboard/yesterday',{},function(res){
                                data = JSON.parse(res);
                                drawChart();
				$('#btn_today').removeClass('ygreen');
                           	$('#btn_today').addClass('ywhite');
                           	$('#btn_yesterday').removeClass('ywhite');
                           	$('#btn_yesterday').addClass('ygreen');
                           	$('#yesterday_dashboard').show();
                           });
			}
		 }
	
	function attachEventsCallBack(i){
    	return function(){attachEvents(i)};
    }
	
    function attachEvents(i){
		var xchart = chartObj[i];
    	var selectedItem = xchart.getSelection()[0];
		var filter = keyArr[keyTypeArr[i]][selectedItem.column-1];
		if(i == 'sqli'){
			window.location.href = "<?php echo SHIKSHA_INTERNAL_HOME ?>/CodeScan/CodeScanView/showVulnerability/sqli/pending/"+filter;
		}else if(i == 'httpstatuscodes'){
			window.location.href = "/AppMonitor/"+urlMap[i]+"/"+"detailedreport"+"/"+filter;
		}
		else{
	    	window.location.href = "/AppMonitor/"+urlMap[i]+"/"+dashboardMode+"/"+filter;
		}
	}

	function abnormalityTimerHandler(param){

		$start = false;
		if(param == 1){
			$start = true;
		}
		$inputArray = {};
		$(".number").each(function(index,obj){
			$inputArray[obj.getAttribute('id')] = $(this).text();
		});

		$.post('/AppMonitor/Dashboard/checkAbnormality',{'inputArray':$inputArray,'start' : $start},function(result){
			if($.trim(result) != ""){
				result = JSON.parse(result);	
				$.each(result,function(index){
					if(result[index] != "-1"){
						
						$("#metric_head_"+index).html("<span class='percent-change' title='Abnormality Count'>&#x25B2;"+result[index]+"</span>");

					} else{
						$("#metric_head_"+index).html("");
						
					}
				});
			}			

		});
	}
	
	</script>
	<style type="text/css">
		.number{
			font-size: 50px;
		}
		.abnormality{
			color: black;
			color: red;
			font-size: 20px;
		}
		.error_abnormal{
			color: red;
		}
	</style>
	
	</body>
	</html>
