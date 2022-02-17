<div style="display:none" id="calenderDate">dd/mm/yyyy</div>
<div  style="display:none;z-index:1;position:relative;" id="gdpidateLayer" >
<div id="current_date" style="display:none;"><?php echo date('d/m/Y');?></div>
	<div class="dateLayer">
    	<div class="layerContent">
        	<?php $this->load->view('common/calendardiv'); ?>
            <div>
                <label>Select Date : </label>
                <span class="calenderBox">
                    <input type="text" value="dd/mm/yyyy" class="calenderFields"  name="from_date_main" id="from_date_main"  readonly  />
                    <a href="#" class="pickDate" title="Calendar" name="from_date_main_img" id="from_date_main_img"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="javascript:$('genOverlayAnA').style.zIndex='1000';calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main'),'from_date_main_img','dd/mm/yyyy'); return false;">&nbsp;</a>
                    <!--<img name="from_date_main_img" id="from_date_main_img"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main'),'from_date_main_img','yyyy-MM-dd'); return false;"  />-->
                </span>
            </div>
            <div class="spacer15 clearFix"></div>
            <label>Applicant's location preference :</label>
            <span id="gdpiPlace"></span>
            <div class="spacer15 clearFix"></div>
            <input type="button" value="Send" title="Send" class="attachButton" id="7" onclick="if(CompareDates()){if(document.getElementById('from_date_main').value==''){alert('Please select <?=$gdPiName?> Date.'); return false;};$('calenderDate').innerHTML = document.getElementById('from_date_main').value;x=sendAlerts();x(7);}else{return false;}"/> &nbsp; <a href="javascript:void(0);" title="Cancel" onclick="hideOnlineFormLayer('gdpidateLayer');">Cancel</a>
          <!--                  <div id="UserFormId" style="display:none;">None</div>-->
       	</div>
    </div>

</div>
<script>
//function setDate(newDate){
  //  $('calenderDate').innerHTML = newDate;
    //return true;
//}
</script>

