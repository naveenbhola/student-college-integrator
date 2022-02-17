<?php
if($flagForCampurRepExists != 0){
?>
<div id="askStud" class="ask-current-col">
  <p><?php if(in_array('23', $subCatIdArray)){?>Still got questions? <?php } else{?>&nbsp;<?php } ?><span>Ask Current Students</span></p>
</div>
<div class="ask-section">
  <?php for($i = 0; $i < $totalFilled ;$i++){
    if(!empty($campusRepList[$i]['caInfo'])){
      $courseId = $campusRepList['courses'][$i];
      $instituteId = $campusRepList['institute'][$i];
      $image = $campusRepList[$i]['caInfo'][$courseId][0]['imageURL'];
      $courseUrl = $campusRepList['courseUrl'][$i];
      $badge = $campusRepList[$i]['caInfo'][$courseId][0]['badge'];
      $yearOfGrad = $campusRepList[$i]['caInfo'][$courseId][0]['yearOfGrad'];
        if($badge=='Official'){$badge = 'OFFICIAL';}else if($badge=='CurrentStudent'){$badge = 'CURRENT STUDENT';}else{$badge = 'ALUMNI';}
      if(strlen($campusRepList[$i]['caInfo'][$courseId][0]['displayName']) > 19){
        $name = substr($campusRepList[$i]['caInfo'][$courseId][0]['displayName'],0,16).'...';}
        else{ 
          $name = $campusRepList[$i]['caInfo'][$courseId][0]['displayName'];
          }?>
      <input type="hidden" id="courseUrl_<?=$courseId;?>" value="<?=$courseUrl?>">
      <div class="set-col">
         <div class="reiview-head">
            <p class="review-img"><?php echo substr($name,0,1); ?></p>
            <p class="reviewer-name"><?php echo $name; ?></p>
            <p class="year-class"><?php if($yearOfGrad > 0){ ?>Class of <?php echo $yearOfGrad; }else{?>&nbsp<?php } ?></p>
         </div>
         <div class="cousre-col">
           <a href="javascript:void(0)" value="Ask Now" onclick="askQuestionCompare('<?php echo $courseId; ?>','<?php echo $instituteId; ?>','<?php echo $qtrackingPageKeyId;?>'); trackEventByGA('LinkClick','COMPARE_PAGE_ASK_NOW_BUTTON_CLICK');" class="new-gray-btn">Ask Now</a>
         </div>
      </div>

  <?php }else{ if($subCatIdArray[$i] == 23){ $url = SHIKSHA_ASK_HOME.'/questions/management'; } else{ $url = SHIKSHA_SCIENCE_HOME.'/engineering-questions-coursepage';}?>
    <div class="set-col">
      <div class="reiview-head">
        <p class="no-stud">No Current Students Available</p>
      </div>
      <div class="cousre-col">
        <a href="<?php echo $url; ?>" target="_blank" class="new-gray-btn">Ask Shiksha Experts</a>
      </div>
    </div>
  <?php }}?>
  </div> 
<?php } ?>