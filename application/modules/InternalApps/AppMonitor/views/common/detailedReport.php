<?php
global $DASHBOARD_TYPE_NAME;
$this->load->view('AppMonitor/common/header');
?>
	<style type="text/css">
		.exceptionCounter{float:left;width: 200px;height: 200px;text-align: center;border: 1px solid #E7E0E0;border-radius: 20px;margin: 5px 0px;visibility: hidden;}
	</style>
	
	<div class='blockbg'>
		<form method='post' action='/common/PerformanceLogger/showSlowQueries' id='qForm'>
			
		<div style='width:1200px; margin:0 auto;'>	
			
		<div style='float:left; margin-left:15px; padding-top:3px;'>From Date: </div>
		<div style='float:left; margin-left:10px; padding-top:1px;'>
			<input type="text" id="fromDatePicker" value="<?php echo $defaultDate; ?>" style='width:100px;' />
		</div>

		<div style='float:left; margin-left:30px; padding-top:3px;'>To Date : </div>
		<div style='float:left; margin-left:10px; padding-top:1px;'>
			<input type="text" id="toDatePicker" value="<?php echo $defaultDate; ?>"  style='width:100px;' />
		</div>


		<?php
			if($dashboardType == ENT_DASHBOARD_TYPE_SLOWQUERY){
				?>
					<div style='float:left; margin-left:30px; padding-top:3px;'>Avg Time : </div>
					<div style='float:left; margin-left:10px; padding-top:1px;'>
							<select style='font-size:14px; padding:1px; color:#444;' name='avgTimeTaken' id="avgTimeTaken">
									<option value="0"> All </option>
									<option value="1">> 1 Sec </option>
									<option value="5">> 5 Sec </option>
									<option value="10">> 10 Sec </option>
							</select>
					</div>
				<?php
			}else{
				?>
				<input type="hidden" name='avgTimeTaken' id="avgTimeTaken" value="0">
				<?php
			}
		?>

		

		
		<?php 		
		if($frontEndServers) { ?>
			<div style='float:left; margin-left:30px; padding-top:3px;'>Server: </div>
			<div style='float:left; margin-left:10px; padding-top:1px;'>
				<select style='font-size:14px; padding:1px; color:#444;' name='frondEndServer' id="frondEndServer">
					<?php
					foreach($frontEndServers as $sk => $sv) {
						echo "<option value='$sk' ".($frondEndServer == $sk ? "selected='selected'" : "").">$sv</option>";
					}
					?>
				</select>
			</div>
		<?php } ?>

		<?php 
		if($statusCodes) { ?>
			<div style='float:left; margin-left:30px; padding-top:3px;'>Status Code: </div>
			<div style='float:left; margin-left:10px; padding-top:1px;'>
				<select style='font-size:14px; padding:1px; color:#444;' name='statusCode' id="statusCode">
					<?php
					foreach($statusCodes as $sk => $sv) {
						echo "<option value='$sk' ".($statusCode == $sk ? "selected='selected'" : "").">$sk ($sv)</option>";
					}
					?>
				</select>
			</div>
		<?php } ?>


		<?php if($sorters) { ?>
		<div style='float:left; margin-left:30px; padding-top:3px;'>Sort By: </div>
		<div style='float:left; margin-left:10px; padding-top:1px;'>
			<select style='font-size:14px; padding:1px; color:#444;' name='sort' id="orderby">
				<?php
				foreach($sorters as $sk => $sv) {
					echo "<option value='$sk' ".($sorter == $sk ? "selected='selected'" : "").">$sv</option>";
				}
				?>
			</select>
		</div>
		<?php } ?>
		<?php 
			if($filters) { 
				foreach ($filters as $filter) {
					?>
					<div style='float:left; margin-left:30px; padding-top:3px;'><?php echo $filter['label'] ?>: </div>
					<div style='float:left; margin-left:10px; padding-top:1px;'>
						<select style='font-size:14px; padding:1px; color:#444;' name="<?php echo $filter['filterKey']; ?>" id="<?php echo $filter['filterKey']; ?>" class="filterOptions">
							<?php
							foreach($filter['options'] as $sk => $sv) {
								echo "<option value='$sk' ".($sorter == $sk ? "selected='selected'" : "").">$sv</option>";
							}
							?>
						</select>
					</div>
					<?php 	
				}
			}
		?>
		<div style='float:left; margin-left:40px;'>
			<!--a href='#' onclick="updateReport();"><img src='/public/images/appmonitor/drefresh.png' width='24' /></a></div-->
			<a href='#' onclick="updateReport();" class='zbutton zsmall zgreen'>Go</a>
		</div>
		 <div style='clear:both'></div>
		 </div>
		 </form>
	</div>
	
	<div style='background-color:white;margin:0 auto;width:1200px; padding-bottom: 40px; border:0px solid red;'>
		<div style="padding: 5px 10px 0 10px;border: 0px solid lightgrey;border-top: 0;">
			<div style="width:100%; padding:0px; margin-top:5px;">
				<div id="tableData" style="min-height:600px;">	
				</div>
			</div>
		</div>
	</div>

	</body>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">

    $(document).ready(function(){
    	$("#fromDatePicker").datepicker();
    	$("#toDatePicker").datepicker();
		updateReport();
    });

    function updateReport(pageNumber){
    	if(typeof(pageNumber) === 'undefined'){
    		pageNumber = 1;
    	}
		$("#tableData").html("<div style='margin:50px; text-align:center;'><img src='/public/images/appmonitor/loader.gif' /></div>");
		var postData = {
			"fromdate" : $("#fromDatePicker").val(),
			"avgTime" : $("#avgTimeTaken").val(),
			"todate" : $("#toDatePicker").val(), 
			"reportType" : "<?php echo $dashboardType == ENT_DASHBOARD_TYPE_TRAFFICREPORT ? $reportType : $dashboardType;?>", 
			"orderby" : $("#orderby").val(),
			"module" : "<?php echo $selectedModule; ?>", 
			"server" : "<?php echo $serverName; ?>", 
			"botStatus" : "<?php echo $botStatus; ?>", 
			"pageNumber":pageNumber,
			"statusCode" : $("#statusCode").val(),
			"frondEndServer" : $("#frondEndServer").val()
		};
		$(".filterOptions").each(function(index,ele){
			postData[$(ele).attr('id')] = $(ele).val();
		});
    	$.ajax({
    		url : "<?php echo $ajaxURL; ?>",
    		data : postData,
  			type : "POST",
  			success : function(res){
  				$("#tableData").html(res);
  			}
    	});
    }
    function TrafficRequests(clauseType, clauseValue, pageNumber){
    	if(typeof(pageNumber) === 'undefined'){
    		pageNumber = 1;
    	}
		$("#tableData").html("<div style='margin:50px; text-align:center;'><img src='/public/images/appmonitor/loader.gif' /></div>");
    	$.ajax({
    		url : "/AppMonitor/TrafficReport/TrafficRequests",
    		data : {"fromdate" : $("#fromDatePicker").val(),"todate" : $("#toDatePicker").val(), "clauseType" : clauseType, "clauseValue": clauseValue, "pageNumber":pageNumber},
  			type : "POST",
  			success : function(res){
  				$("#tableData").html(res);
  			}
    	});
    }
	</script>
    
    <script>
    function drilldownTrafficReport(clauseType, clauseValue, startDate, endDate)
    {
        $("body").addClass("noscroll");
        $("#voverlay").show();
        $("#vdialog_inner").css('top', $('body').scrollTop()+20);
        $("#vdialog").show();
        $("#vdialog_content").html("<div style='margin:50px; text-align:center;'><img src='/public/images/appmonitor/loader.gif' /></div>");
        $.ajax({
                data: { "clauseType": clauseType, "clauseValue": clauseValue, "startDate": startDate, "endDate": endDate},
                url : "/AppMonitor/TrafficReport/drilldown",
                cache : false,
                method : "POST",
                beforeSend : function(){
                }
            }).done(function(res){
                $("#vdialog_content").html(res);
            });
    }
    function convertDateFormat(date) {
    	parts = date.split("/");
    	return parts[2]+"-"+parts[0]+"-"+parts[1];
    }
    </script>
    
    
	</html>
	
