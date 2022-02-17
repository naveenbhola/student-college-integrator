<style>
.custom-dropdown{position: relative; display: block; vertical-align: middle; border:1px solid #ccc; border-radius:4px; width:100%; -moz-box-sizing:border-box; -webkit-box-sizing:border-box; box-sizing:border-box}
.custom-dropdown select{padding-right:6px; border: 0; border-radius: 0px; -webkit-appearance: none; -moz-appearance: none; appearance: none; outline:0 none; border-radius:4px}
.custom-dropdown::before, .custom-dropdown::after{content: ""; position: absolute; pointer-events: none; background-color: #fff;}
.custom-dropdown::before {width: 27px; right: 0; top: 0; bottom: 0; background:#fff url(<?=SHIKSHA_HOME?>/public/images/drop-arrow2.gif) right center no-repeat; border-radius:4px}
.custom-dropdown select[disabled]{color: rgba(0,0,0,.3);}
.account-custom-dropdown{width:56px !important; margin-right:5px;}
.account-custom-dropdown .time-select-field{ background: #fff !important;}
.session-popup-layer{width:660px; border:2px solid #333; background:#fff; padding:10px 20px; float:left; text-align:center; z-index: 99999; position: fixed; top: 15%; left: 25%;}
.session-cross-icon{color:#797979 !important; font-size:26px; line-height:10px; text-decoration:none !important}
.paste-chat-heading{font-size:16px; color:#333; text-align:center; margin:10px 0 0; color:#ff9329;}
.pasted-chat-area{background:#fff; border:1px solid #ccc; width:98%; margin:15px 0 4px; height:300px; padding:4px;color:#000000;}
.ok-button{background-color: #f78640;border: 1px solid #d2d2d2;color: #fff !important; cursor: pointer; font: bold 14px Tahoma,Geneva,sans-serif !important; margin: 5px 0 0 0; display:inline-block;padding: 4px 25px; text-decoration:none !important}
</style>
<?php
$showSlotSection = 'ShowForm';
$chatType = 'scheduled';
if($slotData['slotStatus']=='booked'){
	if($slotData['scheduleStatus']=='accepted'){
		$showSlotSection = 'ChatScheduled';
	}else if($slotData['scheduleStatus']=='started'){
		$showSlotSection = 'StartChat';
	}else{
		$showSlotSection = 'ShowForm';
	}
}
if($slotData['slotStatus']=='free'){
    $showSlotSection = 'ChatScheduled';
    $chatType = 'requested';
}
if(is_array($scheduleData[0]) && !empty($scheduleData[0]))
{
	$showSlotSection = 'StartChat';
}
?>
<div class="mentorship-section">
	<p>YOUR MENTOR</p>
    <div class="mentor-detail" style="padding:10px 15px;">
    	<a href="<?php echo SHIKSHA_HOME.'/getUserProfile/'.$caDetails[0]['ca']['dName'];?>" style="margin-bottom:10px; display:inline-block;"><strong><?php echo $caDetails[0]['ca']['displayName'];?></strong></a> <span style="margin:0 8px;">|</span> <?php if($caDetails[0]['ca']['mainEducationDetails'][0]['badge']=='CurrentStudent'){ echo 'Current Student';}?>
       	<p><label>College:</label><a href="<?php echo $instObj->getUrl();?>"><?php echo $courseObj->getInstituteName();?></a></p>
        <p><label>Branch:</label><a href="<?php echo $courseObj->getUrl();?>"><?php echo $courseObj->getName();?></a></p>
        <p><label>City:</label><?php echo $courseObj->getMainLocation()->getCity()->getName();?></p>
    </div>
</div>
<div class="mentorship-section">
	<!--<div class="account-settings-tabs">
        <ul style="margin:0;">
            <li><a href="javascript:void(0);" class="active">CHAT WITH MENTOR</a></li>
            <li><a href="javascript:void(0);" class="last">ASK QUESTION TO MENTOR</a></li>
        </ul>
    </div>-->
    <?php $totalSlot = count($mentorSlots['free'])+count($mentorSlots['booked']);?>
    <?php $freeSlot  = count($mentorSlots['free']);?>
    <p>CHAT WITH MENTOR</p>
    <?php if($showSlotSection=='StartChat'){ ?>
		<div class="chat-mentor-sec scheduled-chat-widget">
		<p class="scheduled-chat-info">Your chat has been started.</p>
	    </div>
    <?php }else if($showSlotSection == 'ChatScheduled'){ ?>
    <div class="chat-mentor-sec scheduled-chat-widget">
        <p class="scheduled-chat-info">Your next chat with your mentor is <?php echo $chatType;?>: <span><?php echo date('j F Y, h:i A',strtotime($slotData['slotTime'])).' - '.date('h:i A',strtotime($slotData['slotTime'])+1800);?></span></p>
        <p class="discuss-topic-title">Topic of discussion : <?php echo $slotData['discussionTopic'];?></p>
	<a id="menteeStartChatBtn" href="javascript:void(0);" class="schedule-chat-btn" onclick="" style="display: none; float:left !important;padding: 6px 20px; font-size: 18px; margin-right:10px;">Start Chat</a>
        <a id="menteeCancelChatBtn" href="javascript:void(0);" class="font-18" onclick="cancelChatSession();" style="margin-top:6px; display:inline-block;">Cancel Session</a>
    </div>
    <?php }else{ ?>
    <div class="chat-mentor-sec customInputs">
    <form id="menteeChatSchedulingForm">
    	<ul>
        	<li>
            	<div>
            	<input type="radio" checked="checked" id="free-slot" name="menteeOption" value="first" onclick="changeUI('first');">
                <label for="free-slot" style="display:inline-block\9;">
                     <span class="common-sprite" style="position:relative; top:6px;"></span><p style="font-weight:normal;margin-left:0px\9">Select a free slot when your mentor is available: 
                     <select class="slot-field" name="mentorSlotTime">
                     <option>Select date & time</option>
                     <?php foreach ($mentorSlots['free'] as $key => $value) {
                         echo "<option value='".$key."'>".$value."</option>";
                     }?>
                     </select> 
                     <strong style="font-size:10px; font-weight:normal;">( <?php echo $freeSlot;?> out of <?php echo count($mentorSlots['free'])+count($mentorSlots['booked']);?> slot<?php if($totalSlot>1) { echo 's';}?> left )</strong></p>
                </label>
                <div class="errorPlace" style="display:none;margin-left:328px;">
                    <div id="datetime_error" class="errorMsg">Please select date & time.</div>
                </div>
              </div>
              <div class="or-box">OR</div>
              <?php $this->load->view('common/calendardiv'); ?>
              <div>
            	<input type="radio" id="free-slot-2"  name="menteeOption" value="second"  onclick="changeUI('second');" style="margin-top:4px\9;">
                <label for="free-slot-2">
                     <span class="common-sprite" style="position:relative; top:4px;"></span>
                     <p style="font-weight:normal; position:relative; float:left; margin-left:0 !important">Request a slot with your mentor:<label class="select-label" style="width:100px;">Select Date</label>                     
                    <div style="float:left; position:relative;">
                     <input type="text" value="dd/mm/yyyy" class="calenderFields cal-from-input inp-1 date-field"  name="eventStartDate" id="eventStartDate"  readonly style="width:111px !important" />
                     <a href="javascript:void(0);" title="Calendar" name="from_date_main_img" id="from_date_main_img" src="/public/images/calender.jpg" style="cursor:pointer;padding:0;" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('eventStartDate'),'from_date_main_img','dd/mm/yyyy');return false;"><i class="campus-sprite mentee-cal-icon" style="top:4px;"></i></a>
                     </div>
                     <p style="position:relative"><label class="select-label" style="margin-left:0; width:80px; 0right:-83px;">Select Time</label></p>
                     <div class="custom-dropdown account-custom-dropdown flLt">
                     <select class="time-select-field" name="menteeRequestSlotHour">
                     	<option>HH</option>
                        <?php for($i=1;$i<=12;$i++){
                            echo "<option>".$i."</option>";

                        }?>
                     </select>
                     </div>
                     <div class="custom-dropdown account-custom-dropdown flLt">
                     <select class="time-select-field" name="menteeRequestSlotMin">
                     	<option>MM</option>
                        <option>00</option>
                        <option>30</option>
                     </select>
                     </div>
                     <div class="custom-dropdown account-custom-dropdown flLt">
                     <select class="time-select-field" name="menteeRequestSlotAMPM">
                     	<option>AM</option>
                        <option>PM</option>
                     </select>
                     </div>
                </label>
                
              </div>
                <div class="errorPlace clearFix" style="display:none;margin-left:253px;">
                    <div id="slotDateTime_error" class="errorMsg"> Please enter correct date and time.</div>
                </div>
           </li>
           <li style="margin-bottom:10px">
           	<p style="font-weight:normal;">Enter main topics that you want to discuss: 
            <input id="topicDiscussion" autocomplete="off" required="true" minlength="2" maxlength="140" caption="topic" validate="validateStr" type="text" value="Enter topics for discussion e.g Exam preparation....." class="discussion-field" blurmethod="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');" default="Enter topics for discussion e.g Exam preparation....." autocomplete="off"/>
            </p>

            <div class="errorPlace error-hack">
                <div id="topicDiscussion_error" class="errorMsg" style="margin-left:300px;">Please select date & time</div>
            </div>
           </li>
        </ul>
        <a id="chatBtn" href="javascript:void(0);" class="schedule-chat-btn" onclick="if(scheduleChat()){ submitData();}else{return false;};">Schedule Chat</a>
        </form>
    </div>
    <?php } //_p($completedChats);?>
    <?php 
    $oldChatCount = count($completedChats);
    if($oldChatCount > 0):
    ?>
    <div class="chat-history-section clear-width">
	<div style="width:620px;">
		<strong style="color:#828282">Click to view the chat conversation:</strong>
		<ul id="mentee-old-chat-container" >
			<?php
			$iteration = 0;
			foreach($completedChats as $oldChat){
				$iteration++;
			?>
			<li <?=($iteration==$oldChatCount)?'class="last"':''?> onclick="viewMentorshipPreviousChat('<?=$oldChat['chatText']?>')">
				<a href="javascript:void(0);"><?php echo date('j F Y, h:i A',strtotime($oldChat['slotTime'])).' - '.date('h:i A',strtotime($oldChat['slotTime'])+1800);?></a>
				<p>Topics of discussion: <?=$oldChat['discussionTopic']?></p>
			</li>
			<?php } ?>
		</ul>
	</div>
    </div>
    <?php endif; ?>
    <div class="session-popup-layer" id="viewChatLayer" style="display: none;">
	<a class="flRt session-cross-icon" href="javascript:void(0);" onclick="$j('#viewChatLayer, #popupLayerBasicBack').hide();">&times;</a>
	<p class="paste-chat-heading">Your Chat</p>
	<textarea class="pasted-chat-area" id="chatViewBox" disabled="disabled"></textarea>
	<a href="javascript:void(0);" class="ok-button" onclick="$j('#viewChatLayer, #popupLayerBasicBack').hide();">Close</a>
    </div>
    <div id="popupLayerBasicBack" style="opacity: 0.4; z-index: 9999; display: none; height: 100%; position: fixed; left: 0px; bottom: 0px; top: 0px; right: 0px; background: rgb(0, 0, 0);"></div>
</div>
<script type="text/javascript">
    function submitData(){
        $j('#chatBtn').hide();
        var postParams = {};
        if($j("input:radio[name='menteeOption']:checked").val()=='first'){
            postParams['slotId']   = $j("select[name='mentorSlotTime']").val();
            postParams['menteeId'] = loggedInUserId;
            postParams['mentorId'] = mentorId;
            postParams['discussionTopic'] = $j("#topicDiscussion").val();
            makeCustomAjaxCall('/CA/MentorController/bookFreeSlotByMentee',postParams,callBackBookFreeSlotByMentee);
        }else{
            postParams['slotDate'] = $j("#eventStartDate").val();
            postParams['slotHour'] = $j("select[name='menteeRequestSlotHour']").val();
            postParams['slotMin']  = $j("select[name='menteeRequestSlotMin']").val();
            postParams['amPmStr']  = $j("select[name='menteeRequestSlotAMPM']").val();
            postParams['discussionTopic'] = $j("#topicDiscussion").val();
            postParams['mentorId'] = mentorId;
            makeCustomAjaxCall('/CA/MentorController/requestChatSlotbyMentee',postParams,callBackRequestChatSlotbyMentee);
        }
    }

    function callBackBookFreeSlotByMentee(responseText, customParams){
        if(responseText=='booked'){
            alert('This slot is already booked.');
            setTimeout(function(){location.reload(true);},500);
        }else{
            location.reload(true);
        }
    }

    function callBackRequestChatSlotbyMentee(responseText, customParams){
        if(responseText=='BOOKED'){
            alert('Slot is not available');
            $j('#chatBtn').show();
        }else if(responseText=='PAST_TIME'){
            alert('Please provide the slot atleast 2 hours after the current time.');
            $j('#chatBtn').show();
        }else{
            location.reload(true);
        }
    }

    function scheduleChat(){
        var returnFlag = true;
        if($j("input:radio[name='menteeOption']:checked").val()=='first'){
            $j('#slotDateTime_error').parent().hide();
            if($j("select[name='mentorSlotTime']").val()=='Select date & time'){
                $j('#datetime_error').parent().show();
                returnFlag = false;
            }else{
                $j('#datetime_error').parent().hide();
            }
        }else{
            $j('#datetime_error').parent().hide();
            if(($j("#eventStartDate").val()=='dd/mm/yyyy' || $j("#eventStartDate").val()=='') || ($j("select[name='menteeRequestSlotHour']").val()=='hh' || $j("select[name='menteeRequestSlotHour']").val()=='')  || ($j("select[name='menteeRequestSlotMin']").val()=='mm' || $j("select[name='menteeRequestSlotMin']").val()=='')){
                $j('#slotDateTime_error').parent().show();
                returnFlag = false;
            }else{
                $j('#slotDateTime_error').parent().hide();
            }
        }
        $j('#topicDiscussion').focus();
        if(validateFields($('menteeChatSchedulingForm')) != true){
            returnFlag = false;
        }
        return returnFlag;
    }

    function changeUI(radioBtnValue){
        if(radioBtnValue=='first'){
            $j('#eventStartDate, #from_date_main_img').css({'opacity':'0.5'});
            $j('#from_date_main_img>i').css({'cursor':'default'});
            $j('#slotDateTime_error').parent().hide();
            $j("select[name='mentorSlotTime']").prop('disabled', false);
            $j("select[name='menteeRequestSlotHour']").prop('disabled', true);
            $j("select[name='menteeRequestSlotMin']").prop('disabled', true);
            $j("select[name='menteeRequestSlotAMPM']").prop('disabled', true);
            $j('#chatBtn').val('Schedule Chat');
            $j('#from_date_main_img').attr('onclick','');
        }else{
            $j('#eventStartDate, #from_date_main_img').css({'opacity':'1'});
            $j('#from_date_main_img>i').css({'cursor':'pointer'});
            $j('#datetime_error').parent().hide();
            $j("select[name='mentorSlotTime']").prop('disabled', true);
            $j("select[name='menteeRequestSlotHour']").prop('disabled', false);
            $j("select[name='menteeRequestSlotMin']").prop('disabled', false);
            $j("select[name='menteeRequestSlotAMPM']").prop('disabled', false);
            $j('#chatBtn').val('Request Chat');
            $j('#from_date_main_img').attr('onclick',"calMain = new CalendarPopup(\'calendardiv\');calMain.select(document.getElementById(\'eventStartDate\'),\'from_date_main_img\',\'dd/mm/yyyy\');return false;");
        }
    }


    function makeCustomAjaxCall(ajaxCallUrl, data, callbackFunction, customParams) {
        ajaxCallUrl = ajaxCallUrl+'?rnd='+Math.floor((Math.random()*1000000)+1);
        if(typeof(data) == 'object') {
            var urlData = '';
            for(key in data) {
                if(typeof(data[key]) == 'object') {
                    for(key2 in data[key]) {
                            if(urlData) {
                            urlData += '&';
                        }
                        urlData += key+'[]='+data[key][key2];
                    }
                }
                else {
                    if(urlData) {
                        urlData += '&';
                    }
                    urlData += key+'='+data[key];
                }
            }
            data = urlData;
        }
        new Ajax.Request( ajaxCallUrl,
        {   method:'post',
            onSuccess: function(result){
            if (typeof callbackFunction!='undefined') {
                callbackFunction(result.responseText,customParams);
            }
            },
            parameters:data
        });
    }

    function cancelChatSession(){
        var status = confirm('Do you really want to cancel this chat session?');
        if(!status){
            return;
        }
        var postParams = {};
        postParams['slotId']   = slotId;
        postParams['scheduleId'] = scheduleId;
        postParams['mentorId'] = mentorId;
        postParams['userType'] = userType;
        makeCustomAjaxCall('/CA/MentorController/cancelScheduledChat',postParams,callBackCancelChatSession);
    }

    function callBackCancelChatSession(responseText, customParams){
        location.reload(true);
    }
    
    function startChatSession(scheduleId) {
	var postParams = {};
	postParams['scheduleId'] = scheduleId;
	makeCustomAjaxCall('/CA/MentorController/startChatSession', postParams, callbackStartChatSession);
    }
    
    function callbackStartChatSession() {
	location.reload(true);
    }
    
    function viewMentorshipPreviousChat(chatTxt) {
	var left = $j(window).width()/2 - $j('#viewChatLayer').width()/2;
	$j('#popupLayerBasicBack').show();
	$j('#viewChatLayer').css({'left':left+'px', 'display':'block'});
	$j('#chatViewBox').val(base64_decode(chatTxt));
    }

    try{
            addOnBlurValidate(document.getElementById('menteeChatSchedulingForm'));
    } catch (ex) {
    }
    <?php if($showSlotSection=='ChatScheduled'){ ?>
	var timeStr = '<?=$slotData['slotTime']?>';
	var timeArr = timeStr.split(' ');
	var dateStrArr = timeArr[0].split('-');
	var timeStrArr = timeArr[1].split(':');
	var chatTime = new Date(parseInt(dateStrArr[0]), parseInt(dateStrArr[1])-1, parseInt(dateStrArr[2]), parseInt(timeStrArr[0]), parseInt(timeStrArr[1]));
	var currTime = new Date();
	var chatStartCheck = setInterval(function(){
		currTime = new Date();
		if (currTime >= chatTime) {
			startChatSession(scheduleId);
		}
	}, 10 * 1000);
    <?php } ?>    
</script>
<?php if($showSlotSection=='StartChat' && $chatId != ''){ ?>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
function toggleChat() {
	$_Tawk.toggle(); return false;
}
var $_Tawk_API={},$_Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/<?=$chatId?>/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
window.onload = function(){
	setTimeout(function(){toggleChat();}, 5000);
}
</script>
<!--End of Tawk.to Script-->
<?php } ?>
