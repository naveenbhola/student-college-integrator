<script src="/public/mobile5/js/<?php echo getJSWithVersion('mentorship','nationalMobile');?>"></script>
<?php $totalSlot = count($mentorSlots['free'])+count($mentorSlots['booked']);?>
<?php $freeSlot  = count($mentorSlots['free']);?>
<section class="clearfix content-section">
<form id="menteeForm" accept-charset="utf-8" method="post" enctype="multipart/form-data"  novalidate="novalidate" name="menteeForm" autocomplete="off">
<p class="mentor-title">Chat with Mentor</p>
	<div class="mentor-widget-box clearfix">
    <div class="account-setting-col">
        <div>
            <p>1. You may either select a free slot or select date</p><br>
            <p data-enhance="false">
            <input type="radio" checked="checked" id="free-slot" name="menteeOption" value="first" onclick="changeUI('first');"/>
            <label for="free-slot">Select a free slot when your mentor is available: </label></p>
            <div class="account-setting-fields">
                <select class="mentor-selectField mentor-fullWidth" name="mentorSlotTime">
                    <option>Select date & time</option>	
                    <?php foreach ($mentorSlots['free'] as $key => $value) {
                         echo "<option value='".$key."'>".$value."</option>";
                     }?>
                </select>
                <span class="slot-text">( <?php echo $freeSlot;?> out of <?php echo count($mentorSlots['free'])+count($mentorSlots['booked']);?> slot<?php if($totalSlot>1) { echo 's';}?> left )</span>
                <div class="errorPlace" style="display:none;">
                    <div id="datetime_error" class="errorMsg">Please select date & time.</div>
                </div>
            </div>
        </div>
        <div class="or-box-2">
            <p>OR</p>
        </div>
        <div>
       		<p data-enhance="false"><input type="radio" id="free-slot-2"  name="menteeOption" value="second" onclick="changeUI('second');"/>
            <label for="free-slot-2">Request a slot with your mentor:</label>
            </p>
            <div class="account-setting-fields">
                <div class="flLt date-time-col">
                    <label>Select Date</label>
                    <div style="position:relative">
                        <input style="font-size:13px; line-height:17px;" type="text"  class="mentor-txtfield" value="dd/mm/yyyy" id="menteeChatRequestDate" readonly />
                        <i class="mentor-mobile-sprite mobile-cal-icon" href="javascript:void(0);" onclick="$('#menteeChatRequestDate').focus();"></i>
                    </div>
                </div>
                <div class="flRt date-time-col" style="width:65%;">
                    <label>Select Time</label>
                    <div class="time-info-block">
                        <select class="mentor-selectField" name="menteeRequestSlotHour" id="menteeRequestSlotHour">
                            <option>HH</option>
                            <?php for($i=1;$i<=12;$i++){
                            echo "<option>".$i."</option>";
                            }?>
                        </select>
                        <select class="mentor-selectField" name="menteeRequestSlotMin" id="menteeRequestSlotMin">
                            <option>MM</option>
                            <option>00</option>
                            <option>30</option>
                        </select>
                        <select class="mentor-selectField" id= "menteeRequestSlotAMPM" style="margin-right:0px" name="menteeRequestSlotAMPM">
                            <option>AM</option>
                            <option>PM</option>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                    <div class="errorPlace clearfix" style="display:none;">
                    <div id="slotDateTime_error" class="errorMsg"> Please enter correct date and time.</div>
                </div>
            </div>
        </div>
    </div>
    <div class="account-setting-col">
    	<p style="margin-bottom:5px;">2. Enter main topics that you want to discuss:</p>
        <div style="margin-left:13px;">         
         <input id="topicDiscussion" type="text"  class="mentor-txtfield" value="" placeholder="Enter topics for discussion e.g Exam prep.." style="width:99%;" required="true" minlength="2" maxlength="140"  validate="validateStr" caption="topic"/> 
                  <div style="display:none;"><div class="errorMsg" id="topicDiscussion_error" style="*float:left"></div></div>        
         </div>
        </div>
        
    <a id="chatBtn" href="javascript:void(0);" onclick="if(scheduleChat()){ submitData();}else{return false;};" class="mentor-blue-btn">Schedule Chat</a>
</div>
</form>
</section>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script>
    try{
        addOnBlurValidate(document.getElementById('menteeForm'));
        changeUI('first');
        var mentorId = '<?php echo $mentorId;?>';
    }
    catch (ex) 
    {
    }
</script>