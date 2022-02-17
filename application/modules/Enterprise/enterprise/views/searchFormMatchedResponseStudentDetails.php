<div style="width:100%;">
    <div class="select-courses-head"><strong style="text-align: left; margin-left:18px;">Student Details</strong></div>
    <?php
        $data['isMatchedResponse'] =1;
        $totalCity =0;
	    foreach($country_state_city_list as $list){
	       if($list['CountryId'] == 2){
		        foreach($list['stateMap'] as $list2){
		            $totalCity += count($list2['cityMap']);
		        }
	        }
	    }
    	$totalCity += count($cityList_tier1);
    	$data['totalCity'] = $totalCity;
        $this->load->view('enterprise/searchFormEducationDetailsCurrentLocation',$data);
		$this->load->view('enterprise/searchFormEducationDetailsMRLocation', $data);
    ?>
    <div style="width:100%; margin-bottom:20px; margin-top:10px;"">
	    <div>
            <div class="cmsSearch_RowLeft">
        	    <div style="width:100%">
                    <div class="txt_align_r" style="padding-right:5px">Exams Given:&nbsp;</div>
                </div>
            </div>
            <div class="cmsSearch_RowRight">
        	    <?php $this->load->view('enterprise/competitiveExams'); ?>
            </div>
            <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
        </div>                
    </div>
</div>
<div style="border-bottom:1px solid #ddd; width: 100%; margin:10px 0;"></div>

<style type="text/css">
    .date-range{font-size:12px; padding-left:2px; float:left}
    .date-range label{width:auto !important; text-align:left !important; font-size:12px !important; }
    .date-range input{width:80px; font:normal 11px Arial, Helvetica, sans-serif; color:#84888b}
    
    .icon-cal, .arrow-d, .drop-icon, .info-icon, .prev-item, .next-item, .prev-item-active, .next-item-active, .popup-layer2 .pointer, .popup-layer2 .pointer2{background:url(/public/images/smart-sprite.png) 0 0 no-repeat; position:relative; display:inline-block;}
    .info-icon{background-position:-50px 0; width:16px; height:16px; cursor:pointer}
    .icon-cal{background-position:0 0 ; width:15px; height:15px; vertical-align:middle; cursor:pointer}
</style>

<?php $this->load->view('common/calendardiv'); ?>

<div class="cmsSearch_contentBoxTitle">
    <div style="padding-left:20px"><b>Date Range*</b></div>
</div>
<div style="line-height:15px">&nbsp;</div>                        
<div style="width:100%">
	<div align="center" style="margin:0 0 0 162px;" >
    	<div id="filterResultSetOption" name="filterResultSetOption" >
			<div class="to-col">
                <label>From:</label>
                <input type="text" class="txt-ip" placeholder="dd/mm/yyyy" readonly="readonly" id="timeRangeDurationFrom" name="timefilter[from]" onclick="selectTimeRangeFrom(this);" />
                <img src="/public/images/cal-icn.gif" id="timeRangeFromImage" style="vertical-align: middle; cursor:pointer;" onclick="selectTimeRangeFrom(this);" />
            </div>
            <div class="to-col" style="margin:0 0 0 10px;">
                <label>To:</label>
                <input type="text" class="txt-ip" placeholder="dd/mm/yyyy" value="<?php echo date('d/m/Y'); ?>" readonly="readonly" id="timeRangeDurationTo" name="timefilter[to]" onclick="selectTimeRangeTo(this);" />
                <img src="/public/images/cal-icn.gif" id="timeRangeToImage" style="vertical-align: middle; cursor:pointer;" onclick="selectTimeRangeTo(this);" />
            </div>	
        </div>	
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>                
</div>
<div style="line-height:6px">&nbsp;</div>
<div class="line_space20">&nbsp;</div>

<script type="text/javascript">

function selectTimeRangeFrom(obj)
{
	var calendarMain = new CalendarPopup('calendardiv');
	disDate = null;
	frmdisDate = new Date();
	var id = obj.id;
    isresponseViewer = 1;
	calendarMain.select($('timeRangeDurationFrom'), id, 'dd/mm/yyyy');
	return false;
}

function selectTimeRangeTo(obj)
{
	var calendarMain = new CalendarPopup('calendardiv');
	var mindate = $('timeRangeDurationFrom').value;
    var dateStr = mindate.split("/");
    var passedDate = dateStr[0]%32;
    var passedMonth = dateStr[1]%13;
    var passedYear = dateStr[2];
    disDate = new Date(passedYear,passedMonth-1,passedDate);
    var id = obj.id;
    frmdisDate = new Date();
    isresponseViewer = 1;
	calendarMain.select($('timeRangeDurationTo'), id, 'dd/mm/yyyy');
	return false;
}

function validatetimeRange()
{
	var startDate = document.getElementById('timeRangeDurationFrom').value;
	var endDate = document.getElementById('timeRangeDurationTo').value;
	if (startDate == '' || startDate == 'dd/mm/yyyy') {
		alert('Select a start date.');
		return false;
	}
	else if (endDate == '' || endDate == 'dd/mm/yyyy') {
		alert('Select an end date.');
		return false;
	}
	var fromdate = startDate.replace( /(\d{2})\/(\d{2})\/(\d{4})/, "$2/$1/$3");
    var todate = endDate.replace( /(\d{2})\/(\d{2})\/(\d{4})/, "$2/$1/$3");

	if (Date.parse(todate) >= Date.parse(fromdate)) {
		return true;
	}
	else {
		alert("Please select a 'TO' date greater than the 'FROM' date.");
		$('timeRangeDurationFrom').focus();
		return false;
	}
	return true;
}

</script>