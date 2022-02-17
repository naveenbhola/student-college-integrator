<?php
	$entity = '';
	if($entityType == 'Discussion'){
		$entity = 'discussion';
		$className = 'dis';
	}else if($entityType == 'Question') {
		$entity = 'question';
		$className = 'ques';
	}

	if(empty($answerCount)){
		$answerCount = 0;
	}
	if(empty($viewCount)){
		$viewCount = 0;
	}

?>

<?php if($typeOfStat == 'Answer Later' || $typeOfStat == 'Comment Later'){ ?>
	<div class="qns-list-card qns-dis-det-card_<?php echo $iter; ?>" >
		<?php if(!empty($title) || $title != '') ?>
			<p class="q-qstn" ><a style="color:#676464;" class="<?php echo $className;?>" href="<?php echo $url; ?>" target="" id="myActivity_<?php echo $id; ?>" ><?php echo $title; ?></a></p>
	   	<p class="q-views"><span class="q-ans"><?php echo $answerCount; ?><span class="txt"><?php if($entity == 'question'){ if($answerCount == 1){echo ' Answer';}else{echo ' Answers';}}else{ if($answerCount == 1){echo ' Comment';}else{echo ' Comments';}} ?></span><span class="q-l"></span></span><span class="q-ans"><?php echo $viewCount; ?><span class="txt"><?php if($viewCount == 1){echo ' View';}else{ echo ' Views';} ?></span></span></p>

	    <?php if(!$publicProfile){ ?>
        <i class="i-sprite deleteCommentAnswer" entity='<?php echo $entity; ?>' deleteEntityId='<?php echo $id; ?>' iter=<?php echo $iter; ?>></i>
        <?php } ?>
	    <p class="clr"></p>
	</div>
<?php } else { ?>
	<div class="qns-list-card qns-dis-det-card_<?php echo $iter; ?>" >
		<a href="javascript:void(0);" onclick="window.location='<?php echo $url; ?>'" class="<?php echo $className;?>" target="" id="myActivity_<?php echo $id; ?>" >
		<?php if(!empty($title) || $title != '') ?>
			<p class="q-qstn" ><?php echo $title; ?></p>
	   	<p class="q-views"><span class="q-ans"><?php echo $answerCount; ?><span class="txt"><?php if($entity == 'question'){ if($answerCount == 1){echo ' Answer';}else{echo ' Answers';}}else{ if($answerCount == 1){echo ' Comment';}else{echo ' Comments';}} ?></span><span class="q-l"></span></span><span class="q-ans"><?php echo $viewCount; ?><span class="txt"><?php if($viewCount == 1){echo ' View';}else{ echo ' Views';} ?></span></span></p>
	    <p class="clr"></p>
		</a>
	</div>
<?php } ?>
