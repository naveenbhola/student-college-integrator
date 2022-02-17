<?php
    $showSlotSection = 'ShowForm';
    $chatType = 'scheduled';
    if($slotData['slotStatus'] == 'booked') {
        if($slotData['scheduleStatus'] == 'accepted') {
            $showSlotSection = 'ChatScheduled';
        } else if($slotData['scheduleStatus'] == 'started') {
            $showSlotSection = 'StartChat';
        } else {
            $showSlotSection = 'ShowForm';
        }
    }
    if($slotData['slotStatus'] == 'free') {
        $showSlotSection = 'ChatScheduled';
        $chatType = 'requested';
    }
    if(is_array($scheduleData[0]) && !empty($scheduleData[0])) {
        $showSlotSection = 'StartChat';
    }
?>

<?php 
    $totalSlot = count($mentorSlots['free']) + count($mentorSlots['booked']);   
    $freeSlot  = count($mentorSlots['free']); 
?>


    <section  class="prf-box-grey">
        <!--profile-tab content-->
        <div class="prft-titl">
            <div class="caption">
                <p>CHAT WITH MENTOR</p>
            </div>
        </div>

        <div class="frm-bdy1">
          
        <?php if($showSlotSection == 'StartChat') { ?>
            <div class="chat-mentor-sect">
                <p class="sche-chat-info">Your chat has been started.</p>
            </div>
        <?php } else if($showSlotSection == 'ChatScheduled') { ?>
            <div class="chat-mentor-sect">
                <p class="scheduled-chat-info">Your next chat with your mentor is <?php echo $chatType;?>: 
                    <span>
                        <?php echo date('j F Y, h:i A',strtotime($slotData['slotTime'])).' - '.date('h:i A',strtotime($slotData['slotTime'])+1800);?>
                    </span>
                </p>
                <p class="discuss-topic-title">
                    Topic of discussion : <?php echo $slotData['discussionTopic'];?>
                </p>
                <a id="menteeCancelChatBtn" href="javascript:void(0);" class="btn_orngT1 ft" onclick="cancelChatSession();">Cancel Session</a>
            </div>
        <?php } else { ?>     
        
        <!--profile-tab content-->
        <div class="frm-bdy">
            <form action="javascript:void(0);" id="menteeChatSchedulingForm" class="prf-form">
                <div class="prf-mentr">
                    <div class="prf-mntr">
                        <input type="radio" checked="checked" id="free-slot" name="menteeOption" value="first" onclick="changeUI('first');" class="prf-inputRadio" > 
                        <label for="free-slot" style="display:inline-block\9;" class="prf-radio">
                            <i class="icons ic_radiodisable1"></i>  
                            Select a free slot when your mentor is available
                        </label> 
                        <div>
                            <div>
                                <select class="dfl-drp-dwn" id="mentorSlotTime" name="mentorSlotTime" style="margin-left:23px;" mandatory=1>
                                    <option>Select date & time</option>
                                    <?php foreach ($mentorSlots['free'] as $key => $value) {
                                        echo "<option value='".$key."'>".$value."</option>";
                                    }?>
                                </select>
                                <span class="count-remain">( <?php echo $freeSlot;?> out of <?php echo count($mentorSlots['free'])+count($mentorSlots['booked']);?> slot<?php if($totalSlot>1) { echo 's';}?> left )</span>
                            </div>
                        </div>
                        <div style="display:none;">
                            <div id="datetime_error" class="prf-error" style="margin-left:23px;">Please select date & time.</div>
                        </div>
                    </div>
                    <?php $this->load->view('common/calendardiv'); ?>
                    <div class="prf-mntr">
                        <input type="radio" id="free-slot-2" name="menteeOption" value="second" onclick="changeUI('second');" class="prf-inputRadio"/> 
                        <label for="free-slot-2" class="prf-radio">
                            <i class="icons ic_radiodisable1"></i> 
                            Request a slot with your mentor
                        </label> 
                        <ul class="pf1-ul">
                            <li>
                                <label>Select Date</label>
                                <div style="float:left; position:relative;">
                                    <input type="text" value="dd/mm/yyyy" class="mntr-txt"  name="eventStartDate" id="eventStartDate"  readonly/>
                                    <a href="javascript:void(0);" title="Calendar" name="from_date_main_img" id="from_date_main_img" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('eventStartDate'),'from_date_main_img','dd/mm/yyyy');return false;">
                                        <i class="icons1 ic_cal"></i>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <label>Select Time</label>
                                <div class="custom-drp-2">
                                    <select class="dfl-drp-dwn" id="menteeRequestSlotHour" name="menteeRequestSlotHour" style="height: 30px; width:55px;">
                                        <option>HH</option>
                                        <?php for($i=1;$i<=12;$i++){
                                            echo "<option value='".$i."'>".$i."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="custom-drp-2">
                                    <select class="dfl-drp-dwn" id="menteeRequestSlotMin" name="menteeRequestSlotMin" style="height: 30px; width:55px;"> 
                                        <option>MM</option>
                                        <option>00</option>
                                        <option>30</option>
                                    </select>
                                </div>
                                <div class="custom-drp-2">
                                    <select class="dfl-drp-dwn" id="menteeRequestSlotAMPM" name="menteeRequestSlotAMPM" style="height: 30px; width:55px;">
                                        <option>AM</option>
                                        <option>PM</option>
                                    </select>
                                </div>
                            </li>
                            <p class="clr"></p>
                            <div style="display:none;">
                                <div id="slotDateTime_error" class="prf-error" style="margin-left:23px;">Please enter correct date and time.</div>
                            </div>
                        </ul>
                    </div>
                </div>
                  
                <div class="topic-dis" style="margin-bottom:0px;">
                    <p>Enter main topics that you want to discuss</p>
                    <input type="text" class="prf-inp" placeholder="Exam preparation, exam etc" id="topicDiscussion" autocomplete="off" minlength="2" maxlength="140" caption="topic" validate="validateStr" >
                    <div style="display:none;">
                        <div id="topicDiscussion_error" class="prf-error" style="margin-left:0px;">Please enter the topic</div>
                    </div>
                </div> 
                <div class="prf-btns">
                    <section class="rght-sid">
                        <a href="javascript:void(0);" class="btn_orngT1" onclick="if(scheduleChat()){ submitData();}else{return false;}; trackEventByGA('UserProfileClick','LINK_SAVE_SCHEDULE_MENTOR_CHAT');">Schedule Chat</a>
                    </section> 
                </div>
            </form>
        </div>
        <?php } ?>

    </section>

    <section class="prf-box-grey">
        <!--profile-tab content-->
        <div class="frm-bdy1">
            <?php 
                $oldChatCount = count($completedChats);
                if($oldChatCount > 0) {
            ?>
            <div class="chat-history">
               
                <strong>Click to view the chat conversation:</strong>
                <ul id="mentee-old-chat-container">
                <?php
                    $iteration = 0;
                    foreach($completedChats as $oldChat){
                        $iteration++;
                ?>
                    <li <?=($iteration==$oldChatCount)?'class="last"':''?> onclick="viewMentorshipPreviousChat('<?=$oldChat['chatText']?>')">
                        <a href="javascript:void(0);">
                            <?php echo date('j F Y, h:i A',strtotime($oldChat['slotTime'])).' - '.date('h:i A',strtotime($oldChat['slotTime'])+1800);?>
                        </a>
                        <p>Topics of discussion: <?=$oldChat['discussionTopic']?></p>
                    </li>
                <?php } ?>
                </ul>
            
            </div>   
            <?php } ?>      
        </div>
    </section>

        <div class="session-popup-layer" id="viewChatLayer" style="display: none;">
            <a class="flRt session-cross-icon" href="javascript:void(0);" onclick="$j('#viewChatLayer, #popupLayerBasicBack').hide();">&times;</a>
            <p class="paste-chat-heading">Your Chat</p>
            <textarea class="pasted-chat-area" id="chatViewBox" disabled="disabled"></textarea>
            <a href="javascript:void(0);" class="btn_orngT1" onclick="$j('#viewChatLayer, #popupLayerBasicBack').hide();">Close</a>
        </div>
        <div id="popupLayerBasicBack" style="opacity: 0.4; z-index: 9999; display: none; height: 100%; position: fixed; left: 0px; bottom: 0px; top: 0px; right: 0px; background: rgb(0, 0, 0);">
        </div>
        
<script type="text/javascript">

    var menteeId = '<?php echo $userId; ?>';
    var mentorId = '<?php echo $mentorId;?>';
    var scheduleId = '<?php echo $slotData['id'];?>';
    var slotId = '<?php echo $slotData['slotId'];?>';
    var userType = '<?php echo $slotData['userType'];?>';
    
    //if($j('#mentee-old-chat-container').length > 0 && $j('#mentee-old-chat-container').height() > 300) {
    //    $j('#mentee-old-chat-container').css({'overflow-y': 'scroll', 'height': '300px'});
    //}

    try{
       // addOnBlurValidate(document.getElementById('menteeChatSchedulingForm'));
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