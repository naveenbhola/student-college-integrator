<div class="notification-row" id="notification">
		<div class="notification-col">
			<i class="campus-sprite notification-icn"></i>
		</div>
		<div class="notification-details" style="font-size:15px; margin:6px 0 0 60px">
			<?php if($openTotalTask ==0 && $totalQuestions !=0){?>	
			Hey! You have  <?php echo $totalQuestions;?> Unanswered question(s) to go.
			<?php }elseif($openTotalTask !=0 && $totalQuestions ==0){?>
			Hey! You have  <?php echo $openTotalTask;?> open task(s) to go.
			<?php }elseif($openTotalTask !=0 && $totalQuestions !=0){?>
			Hey! You have  <?php echo $totalQuestions;?> Unanswered question(s) to go.
			<?php }else{?>
		        There are no unanswered questions or open tasks for you now.
		        <?php }?>
		</div>
</div>