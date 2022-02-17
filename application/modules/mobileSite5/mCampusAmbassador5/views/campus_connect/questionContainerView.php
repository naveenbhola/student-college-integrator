<ul>
<?php
if(count($quesData) > 0)
{
  foreach($quesData as $key=>$val){
      if(!empty($courseRepo) && is_object($courseRepo)){ 
          $courseObj = $courseRepo->find($val['courseId']);
          $instituteName = $courseObj->getInstituteName(); // primary institute of course
      }else{
          $instituteName = $instituteData[$val['instituteId']]['name'];
      }

  ?>
  <li>
    <p>
      <a href="<?php echo getSeoUrl($val['msgId'], 'question', $val['msgTxt']);?>"><?php echo $val['msgTxt']?></a><br />
      <span><?php echo $instituteName;?>, <?php echo $val['viewCount']?> View(s)</span><br />
      <strong class="campus-answer" id="span1-<?php echo $key?>"><?php echo substr(html_entity_decode($answerData[$val['msgId']],ENT_NOQUOTES,'UTF-8'),0,80)?>
      <?php
      if(strlen($answerData[$val['msgId']])>80)
      {
      ?>
	  <a class="sml" onclick="$('#span2-<?php echo $key?>').show(); $('#span1-<?php echo $key?>').hide(); $(this).hide(); trackEventByGAMobile('READ_MORE_ANSWER_CCHOME_MOBILE');" href="javascript:void(0);">Read more</a>
      <?php
      }
      ?>
      </strong>
      <strong class="campus-answer" id="span2-<?php echo $key?>" style="display: none;"><?php echo html_entity_decode($answerData[$val['msgId']],ENT_NOQUOTES,'UTF-8')?></strong>
    </p>
  </li>
  <?php
  }
}
else{
?>
  <li><p>No questions available.</p></li>
<?php
}
?>
</ul>