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
<?php if($publicProfile || !($actionNeeded)){ ?>
<a href="<?php echo $url; ?>" target="" id="myActivity_<?php echo $id; ?>" class="activiy-data <?php echo $className;?> tupleDetail_<?php echo $iter; ?>">
  <div class="data-options">
    <?php if(!empty($title) || $title != ''){ ?>
      <p class="data-p"><?php echo $title; ?></p><?php } ?>
    <p class="straight-txt">
       <b class="data-bold"><?php echo $answerCount; ?></b><?php if($entity == 'question'){if($answerCount == 1){echo ' Answer';}else{echo ' Answers';}}else{if($answerCount == 1){echo ' Comment';}else{echo ' Comments';}} ?><span class="data-s"> &#124;</span>
       <b class="data-bold"><?php echo $viewCount; ?></b><?php if($viewCount == 1){echo ' View';}else{ echo ' Views';} ?>
    </p>
  </div>
</a>
<?php } else { ?>
<div class="activiy-data tupleDetail_<?php echo $iter; ?>">
  <div class="data-options">
    <?php if(!empty($title) || $title != ''){ ?>
      <p class="data-p"><a class ="<?php echo $className;?>" style="color: #4c4c4c;" href="<?php echo $url; ?>" target="" id="myActivity_<?php echo $id; ?>"><?php echo $title; ?></a></p><?php } ?>
    <p class="straight-txt">
       <b class="data-bold"><?php echo $answerCount; ?></b><?php if($entity == 'question'){if($answerCount == 1){echo ' Answer';}else{echo ' Answers';}}else{if($answerCount == 1){echo ' Comment';}else{echo ' Comments';}} ?><span class="data-s"> &#124;</span>
       <b class="data-bold"><?php echo $viewCount; ?></b><?php if($viewCount == 1){echo ' View';}else{ echo ' Views';} ?>
    </p>
    <?php if(!$publicProfile){ ?>
      <i class="i-sprite deleteCommentAnswer" entity='<?php echo $entity; ?>' deleteEntityId='<?php echo $id; ?>' iter=<?php echo $iter; ?>></i>
    <?php } ?>
  </div>
</div>
<?php } ?>