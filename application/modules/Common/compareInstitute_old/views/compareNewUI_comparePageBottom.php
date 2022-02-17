<?php if($institutes && count($institutes)>1){ ?>
<div class="helpful-col">
  <p class="help-txt">Was the comparison helpful?</p>
  <div class="cmpre-vote-col">
    <a href="javascript:;" onclick="sendFeedback('1','rate')" class="vote-icon"><i class="cmpre-sprite ic-vote" id="yes"></i>Yes</a>
    <a href="javascript:;" onclick="sendFeedback('0','rate')" class="vote-icon"><i class="cmpre-sprite ic-dvote" id="no"></i>No</a>
  </div>
  <div class="comment-section">
    <p id="thankMsg" style="display: none" class="thnku-msg">Thanks for your feedback!</p>
    <div class="comment-box" id="comment-box" style="display: none">
      <i class="compare-sprite comment-arrw"></i>
      <textarea id="feebackMsg" name="textarea" class="commnt-textarea" onclick = "$(this).innerHTML='';$j('#feebackMsg').css('color','#a2a9ae'); ">Please share your comments?</textarea>
      <input type="button" class="sbmt-btn" onclick="sendFeedback('3','msg');" value="Submit"/>
      <div class="errorMsg" id="commentErr" style="display: none"></div>
    </div>
  </div>
</div>
<script>
      if(getCookie('feedback_comparepage') != ''){ 
         feedback_comparepageVal = getCookie('feedback_comparepage');
    var res = feedback_comparepageVal.split("|");
    if(res[1] == '1'){
        $('yes').classList.add("upVote-orng-icon");
    }
    else{
        $('no').classList.add("dwnVote-orng-icon");
      
    }
    $('thankMsg').innerHTML = 'You have already given the feedback.';
    $('thankMsg').style.display = 'inline';
  }
</script>
<?php } ?>
<!--popular-compare-widget-->
<div class="cmpre-widget" id="popularComparisions">
<?php 
if(isset($mainCourseSubCatId) && $mainCourseSubCatId>0){
  echo Modules::run('compareInstitute/compareInstitutes/getPopularCoursesForComparision',$mainCourseSubCatId);
}else{ 
      echo Modules::run('compareInstitute/compareInstitutes/getPopularCoursesForComparision',23);
      echo Modules::run('compareInstitute/compareInstitutes/getPopularCoursesForComparision',56);   
    }
?>
</div>

<!--share widget-->
<div class="share-widget">
<?php $this->load->view('compareNewUI_comparePageShareWidget'); ?>
</div>