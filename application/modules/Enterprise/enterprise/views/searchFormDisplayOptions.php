<style type="text/css">
.date-range{font-size:12px; padding-left:2px; float:left}
.date-range label{width:auto !important; text-align:left !important; font-size:12px !important; }
.date-range input{width:80px; font:normal 11px Arial, Helvetica, sans-serif; color:#84888b}

.icon-cal, .arrow-d, .drop-icon, .info-icon, .prev-item, .next-item, .prev-item-active, .next-item-active, .popup-layer2 .pointer, .popup-layer2 .pointer2{background:url(/public/images/smart-sprite.png) 0 0 no-repeat; position:relative; display:inline-block;}
.info-icon{background-position:-50px 0; width:16px; height:16px; cursor:pointer}
.icon-cal{background-position:0 0 ; width:15px; height:15px; vertical-align:middle; cursor:pointer}
</style>

<?php
$this->load->view('common/calendardiv');
?>

<div class="cmsSearch_contentBoxTitle"><div style="padding-left:20px"><b>Select Date Range</b></div></div>
<div style="line-height:15px">&nbsp;</div>                        
<div style="width:100%">
	<div align="center" style="margin:0 0 0 208px;" >
    	
				
				<div id="filterResultSetOption" name="filterResultSetOption" >
							
								<div class="date-range">
									<label>From</label>
									<input type="text" value="yyyy-mm-dd"   name="timefilter[from]" id="timerange_from" />
									<i class="icon-cal" id="timerange_from_img" onclick="timerangeFrom();" style="cursor: pointer;"></i>
								</div>

								<div class="date-range" style="margin:0 0 0 10px;">
									<label>To</label>
									<input type="text" value="yyyy-mm-dd"   name="timefilter[to]" id="timerange_to" />
									<i class="icon-cal" id="timerange_to_img" onclick="timerangeTo();" style="cursor: pointer;"></i>
								</div>	
									
					
				</div>	
			
			            
	
	
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>                
</div>
<div style="line-height:6px">&nbsp;</div>
<!--End_OtherFilter-->


<div style="margin:10px 0 20px 250px; font-size:13px;">
	<input type='checkbox' name="includeActiveUsers" checked="checked" /> Include active users in this time range
</div>



<script type="text/javascript">

function timerangeFrom()
{
	var calMain = new CalendarPopup('calendardiv');	
	calMain.select($('timerange_from'),'timerange_from_img','yyyy-mm-dd');
	return false;
}

function timerangeTo()
{
	var calMain = new CalendarPopup('calendardiv');	
	calMain.select($('timerange_to'),'timerange_to_img','yyyy-mm-dd');
	
	
	
	return false;
}


function timeTxtBoxChange(obj)
{
	if (obj.value == 'range')
	{
		$('timerange_from').disabled = false;
		$('timerange_to').disabled = false;
		$('fixedduration').disabled = true;
		if ($('timerange_from').value == "dd/mm/yyyy") {
			$('timerange_from').value = "";
		}
		
		if ($('timerange_to').value == "dd/mm/yyyy") {
			$('timerange_to').value = "";
		}
	}
	else if (obj.value == 'fixed')
	{
		$('timerange_from').disabled = true;
		$('timerange_to').disabled = true;
		$('fixedduration').disabled = false;
		if ($('timerange_from').value == "") {
			$('timerange_from').value = "dd/mm/yyyy";
		}
		
		if ($('timerange_to').value == "") {
			$('timerange_to').value = "dd/mm/yyyy";
		}
	}
}


function validatetimeRange()
{
	
		var startDate = document.getElementById('timerange_from').value;
		var endDate = document.getElementById('timerange_to').value;
		
		if (startDate == '' || startDate == 'yyyy-mm-dd') {
			alert('Select a start date.');
			return false;
		}
		else if (endDate == '' || endDate == 'yyyy-mm-dd') {
			alert('Select an end date.');
			return false;
		}
			
		var fromdate = startDate.replace( /(\d{4})-(\d{2})-(\d{2})/, "$2/$3/$1");
		var todate = endDate.replace( /(\d{4})-(\d{2})-(\d{2})/, "$2/$3/$1");
		
	    var subData = Date.parse(todate) - Date.parse(fromdate);
	    var days = subData/ 1000 / 60 / 60 / 24;

		if(days > 365){
			$j.ajax({
				type:'POST',
				url:'/enterprise/shikshaDB/repostExceedingDayTemp',
				data:{
					numDays:days
				},
				success:function(reponse){

				}
			});
		}

		if (Date.parse(todate) >= Date.parse(fromdate)) {
			return true;
		}
		else {
			alert("Please select a 'TO' date greater than the 'FROM' date.");
			return false;
		}
	
	return true;
}


</script>
