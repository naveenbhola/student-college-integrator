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
<html>
<head>
	<style type="text/css">
		.dashboardChartCell{
			display: block;
			text-align: center;
		}
		.dashboardChartCell a{
			display: inline-block;
			margin: 0px 20px;
			padding: 10px 20px;
			border: 1px solid #d2d2d2;
			text-decoration: none;
			transition: all .5s linear;
		}
		.dashboardChartCell a:hover{
			background: #ebebeb;
		}
		.metric_head{
			padding: 10px 0px 30px 0px;
			font-size: 24px;
			font-family: arial,sans-serif;
			background: transparent;
		}
		.dashboardChartCell span {
		    display: block;
		    padding: 20px 0px 0px;
		    font-size: 16px;
		}
	</style>


</head>
<body>
		
	<div style='background-color:white;margin:0 auto;width:1200px; border:0px solid red; min-height:700px;'>	
		<div style="padding: 13px 10px 0 10px;border: 0px solid lightgrey;border-top: 0;">
   
		<div class="clearFix"></div>
		
		<div>
			
			
			<div class="dashboardChartCell" style="width:100%;height: 40%">
				<h1 class='metric_head' >CI / CD - Frontend Process (Shiksha)</h1>
				<!-- <span id='metric_head_botreport' class='percent_change'></span> -->
				<a target="_blank"  href="http://shikshatest03.infoedge.com:3022/pwa/reports/lint_report.html"><img src="/public/images/eslint-icon.png" height="150" width="150" /><span>Linting</span></a>
				<a target="_blank"  href="http://shikshatest03.infoedge.com:3022/pwa/reports/test-report.html"><img src="/public/images/jest-react.png" height="150" width="150" /><span>Test Report</span></a>
				<a target="_blank"  href="http://shikshatest03.infoedge.com:3022/pwa/reports/coverage/index.html"><img src="/public/images/jest.png" height="150" width="150" /><span>Test Coverage</span></a>
				<a target="_blank" href="http://shikshatest03.infoedge.com:3022/pwa/reports/bundle-analyzer.html"><img src="/public/images/analyzer.png" height="150" width="150" /><span>Analyzer</span></a>				
				<br/>
				<!-- <h1 class='metric_head' >Bot Detection</h1><span id='metric_head_botreport' class='percent_change'></span> -->
				
			</div>

			<div class="dashboardChartCell" style="margin-top:20px;width:100%;">
				<h1 class='metric_head' >CI / CD - Frontend Process (Study Abroad)</h1>
				<!-- <span id='metric_head_botreport' class='percent_change'></span> -->
				<a target="_blank"  href="http://studyabroad.shikshatest02.infoedge.com/saassets/lintReport/lintReport.html"><img src="/public/images/eslint-icon.png" height="150" width="150" /><span>Linting</span></a>
				<a target="_blank"  href="http://studyabroad.shikshatest02.infoedge.com/saassets/saUnitTest/jest-html-report.html"><img src="/public/images/jest-react.png" height="150" width="150" /><span>Test Report</span></a>
				<a target="_blank"  href="http://studyabroad.shikshatest02.infoedge.com/saassets/saUnitTest/coverage/"><img src="/public/images/jest.png" height="150" width="150" /><span>Test Coverage</span></a>
				<a target="_blank" href="http://studyabroad.shikshatest02.infoedge.com/saassets/analyzer/report.html"><img src="/public/images/analyzer.png" height="150" width="150" /><span>Analyzer</span></a>
				
				<br/>
				<br/>
				<!-- <h1 class='metric_head' >Bot Detection</h1><span id='metric_head_botreport' class='percent_change'></span> -->
				
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
	
	</script>
	
	</body>
	</html>
