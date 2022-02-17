<div class="draftLayer" style="display:none;"  id="draftLayer">
<div style="display:none;" id="userId"></div>
<div style="display:none;" id="formId"></div>
        <div style="display:none;" id="drftInstId"></div>
        <div class="draftLayer">
        <h4>Please provide the following details</h4>
        <ul>
            <li>
                <label>Date:</label>
                <?php $this->load->view('common/calendardiv'); ?>
                <span class="calenderBox"><input type="text" class="calenderFields" type="text" value="dd/mm/yyyy" class="calenderFields"  name="from_date_main" id="from_date_main"  readonly/>        <a href="#" class="pickDate" title="Calendar" name="from_date_main_img" id="from_date_main_img"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="javascript:$('genOverlayAnA').style.zIndex='1000';calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main'),'from_date_main_img','dd/mm/yyyy'); return false;">&nbsp;</a></span>
            </li>
            <li>
                <label>Draft No:</label>
                <span><input type="text" class="textboxLarge" id="draftNumber" value="" validate="validateStr"/></span>
            </li>
            <li>
                <label>Payee Bank:</label>
                <span><input type="text" class="textboxLarge" id="draftBank" value="" validate="validateStr"/></span>
            </li>
            <li>
                <div class="paddLeft128">
                    <input type="button" value="Update" title="Update" class="attachButton" onclick="if(checkDDField()==false) return false;x=sendAlerts(1);x(1);"/> &nbsp;
                    <a href="javascript:void(0);" title="Cancel" onclick="hideOnlineFormLayer();">Cancel</a>
                </div>
            </li>
         </ul>
         <div class="clearFix"></div>
       </div>
</div>
