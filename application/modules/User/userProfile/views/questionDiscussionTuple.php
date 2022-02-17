<?php
  if($type == 'D'){
    $dataArray = $discussion;
     $className = 'dis';
  }else if($type == 'Q') {
     $className = 'ques';
    $dataArray = $question;
  }

  if(empty($dataArray['answerCount'])){
    $dataArray['answerCount'] = 0;
  }
  if(empty($dataArray['viewCount'])){
    $dataArray['viewCount'] = 0;
  }
?>

  <a class="activiy-data <?php echo $className;?>" href="<?php echo $url; ?>" target="" id="myActivity_<?php echo $dataArray['id']; ?>" >
  <div class="data-head">
     <?php if(!empty($dataArray['activity']) || $dataArray['activity'] != ''){ ?><h1 class="act-h1"><?php echo $dataArray['activity']; ?></h1><?php } ?>
     <?php if(!empty($dataArray['activityTime']) || $dataArray['activityTime'] != ''){ ?><span class="time-slot"><i class=" clock-ico"></i><?php echo $dataArray['activityTime']; ?></span><?php } ?>
  </div>
  <div class="data-options">
    <?php if(!empty($dataArray['title']) || $dataArray['title'] != ''){ ?>
      <p class="data-p"><?php echo $dataArray['title']; ?></p>
    <?php } ?>
    <p class="straight-txt">
       <b class="data-bold"><?php echo $dataArray['answerCount']; ?></b><?php if($type == 'Q'){if($dataArray['answerCount'] == 1){echo ' Answer';}else{echo ' Answers';}}else{if($dataArray['answerCount'] == 1){echo ' Comment';}else{echo ' Comments';}} ?><span class="data-s"> &#124;</span>
       <b class="data-bold"><?php echo $dataArray['viewCount']; ?></b><?php if($dataArray['viewCount'] == 1){echo ' View';}else{ echo ' Views';} ?>
    </p>
  </div>
</a>