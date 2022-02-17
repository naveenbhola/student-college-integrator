<?php
$oldChatCount = count($completedChats);
$iteration = 0;
if($oldChatCount > 0):
?>
<section class="clearfix content-section">
  <p class="mentor-title">Your Chat History</p>
    <div class="mentor-widget-box clearfix">
      <ul class="view-chat-list">
	<?php foreach($completedChats as $oldChat) {
	$iteration++;
	?>
	<li <?=($iteration == $oldChatCount)?'class="last"':'' ?> onclick="viewMentorshipPreviousChat('<?=$oldChat['chatLogId']?>', '<?=date('j F, g:i', strtotime($oldChat['slotTime']))?> - <?=date('g:i A', strtotime($oldChat['slotTime'])+1800)?>');">
	  <?php if($iteration == 1): ?>
	  <p style="color:#4a4a4a;">Click to view the chat conversation:</p>
	  <?php endif; ?>
	  <a href="#viewMentorshipChatByMentee" data-transition="slide" data-rel="dialog" data-inline="true">
	    <p class="date-time-title"><?=date('j F Y, g:i', strtotime($oldChat['slotTime']))?> - <?=date('g:i A', strtotime($oldChat['slotTime'])+1800)?></p>
	    <p class="discussion-title">Topics of discussion: <?=$oldChat['discussionTopic']?></p>
	  </a>
	</li>
	<?php } ?>
      </ul>
    </div>
</section>
<?php endif; ?>