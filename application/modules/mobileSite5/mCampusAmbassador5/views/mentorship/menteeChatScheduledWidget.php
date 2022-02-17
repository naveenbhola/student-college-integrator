<section class="clearfix content-section">
  <p class="mentor-title">Chat with Mentor</p>
  	<div class="mentor-widget-box clearfix">
		<p class="next-chat-content">Your next chat with your mentor is <?php echo $chatType;?>:  <span><?php echo date('j F Y, h:i A',strtotime($slotData['slotTime'])).' - '.date('h:i A',strtotime($slotData['slotTime'])+1800);?></span></p>
        <p class="discussion-title">Topics of discussion: <?php echo $slotData['discussionTopic'];?></p>
        <a href="javascript:void(0);" class="cancel-session-btn" onclick="cancelChatSession();">Cancel Session</a>
    </div>
 </section>
<script type="text/javascript">
	var mentorId = '<?php echo $mentorId;?>';
	var scheduleId = '<?php echo $slotData['id'];?>';
	var slotId = '<?php echo $slotData['slotId'];?>';
	var userType = '<?php echo $slotData['userType'];?>';
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
</script>