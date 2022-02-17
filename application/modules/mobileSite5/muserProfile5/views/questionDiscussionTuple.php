<?php

	if($type == 'D'){
		$dataArray = $discussion;
		$className = 'dis';
	}else if($type == 'Q') {
		$dataArray = $question;
		$className = 'ques';
	}

	if(empty($dataArray['answerCount'])){
		$dataArray['answerCount'] = 0;
	}
	if(empty($dataArray['viewCount'])){
		$dataArray['viewCount'] = 0;
	}
?>
	<a href="<?php echo $url; ?>" class="q-card <?php echo $className;?>" target="" id="myActivity_<?php echo $dataArray['id']; ?>" >
	<div class="q-top">
    	<?php if(!empty($dataArray['activity']) || $dataArray['activity'] != '') ?><div class="q-heading"><?php echo $dataArray['activity']; ?></div>
    	<?php if(!empty($dataArray['activityTime']) || $dataArray['activityTime'] != '') ?><div class="q-time"><p><i class="clock-ico"></i><?php echo $dataArray['activityTime']; ?></p></div>
 	</div>
	<div class="q-btm">
   		<?php if(!empty($dataArray['title']) || $dataArray['title'] != '') ?>
   			<p class="q-qstn"><?php echo $dataArray['title']; ?></p>
   		<p class="q-views"><span class="q-ans"><?php echo $dataArray['answerCount']; ?><span class="txt"><?php if($type == 'Q'){if($dataArray['answerCount'] == 1){echo ' Answer';}else{echo ' Answers';}}else{if($dataArray['answerCount'] == 1){echo ' Comment';}else{echo ' Comments';}} ?></span><span class="q-l"></span></span><span class="q-ans"><?php echo $dataArray['viewCount']; ?><span class="txt"><?php if($dataArray['viewCount'] == 1){echo ' View';}else{ echo ' Views';} ?></span></span></p>
	</div> 
	</a>
