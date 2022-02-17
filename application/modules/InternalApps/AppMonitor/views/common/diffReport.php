<?php
global $DASHBOARD_TYPE_NAME;
 $this->load->view('AppMonitor/common/header'); ?>
	<style type="text/css">
		.exceptionCounter{float:left;width: 200px;height: 200px;text-align: center;border: 1px solid #E7E0E0;border-radius: 20px;margin: 5px 0px;visibility: hidden;}
	</style>
	
	<div class='blockbg'>
		<form method='post' action='/common/PerformanceLogger/showSlowQueries' id='qForm'>
		<div style='width:1200px; margin:0 auto;'>		
			<div style='float:left; margin-top:0px;'>	
				<div style='float:left; margin-left:15px; padding-top:3px;'>Start Date Range: </div>
				<div style='float:left; margin-left:10px; padding-top:1px;'>
					<input type="text" id="fromDatePickerStart" value="<?php echo $defaultDateStart; ?>" style='width:100px; color:#444;' /> - 
				</div>
				<div style='float:left; margin-left:10px; padding-top:1px;'>
					<input type="text" id="fromDatePickerEnd" value="<?php echo $defaultDateStart; ?>" style='width:100px; color:#444;' />
				</div>

				<div style='float:left; margin-left:30px; padding-top:3px;'>End Date Range: </div>
				<div style='float:left; margin-left:10px; padding-top:1px;'>
					<input type="text" id="toDatePickerStart" value="<?php echo $defaultDateEnd; ?>"  style='width:100px; color:#444;' /> - 
				</div>
				<div style='float:left; margin-left:10px; padding-top:1px;'>
					<input type="text" id="toDatePickerEnd" value="<?php echo $defaultDateEnd; ?>"  style='width:100px; color:#444;' />
				</div>
			</div>
			
			<div style='float:left; margin-left:40px;'>
				<a href='#' onclick="updateReport();" class='zbutton zsmall zgreen'>Go</a>
			</div>
			<div style='clear:both'></div>
		</div>	
		</form>
		
		
	</div>
	
	<div id="tableData" style="min-height: 600px;">	
	</div>
	
	<!--div style='background-color:white; margin:0 auto; width:1200px; padding-bottom: 40px; border:0px solid red;'>
		<div style="padding: 5px 10px 0 10px; border: 0px solid lightgrey; border-top: 0;">	
			<div id="tableData">	
			</div>
		</div>
	</div-->
	
	</body>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">

    $(document).ready(function(){
    	$("#fromDatePickerStart").datepicker();
    	$("#fromDatePickerEnd").datepicker();
    	$("#toDatePickerStart").datepicker();
    	$("#toDatePickerEnd	").datepicker();
		updateReport();
    });

    function updateReport(){
		$("#tableData").html("<div style='margin:50px; text-align:center;'><img src='/public/images/appmonitor/loader.gif' /></div>");
    	$.ajax({
    		url : "<?php echo $ajaxURL; ?>",
    		data : {"fromdateStart" : $("#fromDatePickerStart").val(),"fromdateEnd" : $("#fromDatePickerEnd").val(),"todateStart" : $("#toDatePickerStart").val(),"todateEnd" : $("#toDatePickerEnd").val(), "reportType" : "<?php echo $dashboardType?>", "module" : "<?php echo $selectedModule; ?>", "server" : "<?php echo $serverName; ?>"},
  			type : "POST",
  			success : function(res){
  				$("#tableData").html(res);
  			}
    	});
    }
	</script>
	</html>
	