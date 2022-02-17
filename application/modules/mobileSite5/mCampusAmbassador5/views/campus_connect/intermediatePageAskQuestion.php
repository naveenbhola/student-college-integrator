<?php
$successMsgFlag = $_COOKIE['isUserRegistersForQuestionPosting'];
?>
<section class="content-wrap clearfix" style="border-radius:0; box-shadow:none; margin:0 0 15px 0; background-color: #fff;">
  <header class="content-inner content-header clearfix" id="postQuestionFromCC" style="<?php echo (($successMsgFlag=='yes')?'display:none;':'');?>">
    <h2 class="title-txt">Ask Question</h2>
  </header>
  <div class="content-inner clearfix" id="postQuestionFromCC_content" style="<?php echo (($successMsgFlag=='yes')?'display:none;':'');?>">  
    <form action="/messageBoard/MsgBoard/askQuestionFromListing" id="postQuestionFromCCInstPage" onsubmit="return false;" novalidate="true" method="post">
      <div style="margin-bottom:10px;">
      <textarea class="que-txtarea" validate="validateStr" style="font-family: sans-serif; font-size: 12px; margin-bottom:5px;" default="Enter your question here..." name="questionText" minlength="2" caption="Question" maxlength="140" id="questionText" placeholder="Enter your question here..." autocomplete="off" required="true"></textarea>
      <div style="display:none">
        <div style=" clear:both; display:block; margin-bottom: 10px;" id="questionText_error" class="errorMsg"></div>
      </div>
      </div>
      <div style="width: 52%;float: left">
        <div class="custome-dropdown" id="questionCategoryList" style="z-index:10; width:100%; margin-bottom: 5px;">
            <a href="javascript:void(0)" onClick="toggleCustomDropDown('questionCategoryDropDown');">
                    <div class="arrow"><i class="caret"></i></div>
                    <div style="max-height: 25px; overflow: hidden;">
                      <span class="display-area" id="questionCategorySelect" style="width: 350px; display: inline-block;">Select Question Category</span>
                    </div>
            </a>
            <div class="drop-layer" id="questionCategoryDropDown" style="display:none; width: 99%;">
              <ul>
                <li><a id="" href="javascript:void(0)" onClick="selectQuestionCategory(this, 'admission');">Admission & Eligibility</a></li>
                <li><a id="" href="javascript:void(0)" onClick="selectQuestionCategory(this);">Placements</a></li>
                <li><a id="" href="javascript:void(0)" onClick="selectQuestionCategory(this);">Campus Life</a></li>
                <li><a id="" href="javascript:void(0)" onClick="selectQuestionCategory(this);">Course details</a></li>
                <li><a id="" href="javascript:void(0)" onClick="selectQuestionCategory(this);">College details</a></li>
                <li><a id="" href="javascript:void(0)" onClick="selectQuestionCategory(this);">Others</a></li>
              </ul>
            </div>
        </div>
        <select style="display: none;" name="question_category" id="question_category">
          <option value="" selected="selected"> Select Question Category </option>
          <option value="Admission & Eligibility">Admission & Eligibility</option>
          <option value="Placements">Placements</option>
          <option value="Campus Life">Campus Life</option>
          <option value="Course details">Course details</option>
          <option value="College details">College details</option>
          <option value="Others">Others</option>
        </select>
        <div style="clear:both; display:none;" id="questionCategoryError" class="errorMsg"></div>
      </div>
      <div style="width: 45%; float: right;">
        <div class="custome-dropdown" id="questionCourseList" style="z-index:10; width:100%; margin-bottom: 5px;">
            <a href="javascript:void(0)" onClick="toggleCustomDropDown('questionCourseDropDown');">
                <div class="arrow"><i class="caret"></i></div>
                <div style="max-height: 25px; overflow: hidden;">
                  <span class="display-area" id="questionCourseSelect" style="width: 350px; display: inline-block;">Select Course</span>
                </div>
            </a>
            <div class="drop-layer" id="questionCourseDropDown" style="display:none; width: 99%;">
              <ul>
                <?php
                foreach($courseIds as $courseId)
                {
                        $courseObj = $courseRepository->find($courseId);
                        ?>
                        <li><a id="" href="javascript:void(0);" onClick="selectCourseForQuestion(this, '<?=$courseId?>');"><?=$courseObj->getName();?></a></li>
                        <?php
                }
                ?>
              </ul>
            </div>
        </div>
        <select style="display: none;" class="select-width" name="course_selected" selected="selected" id="course_selected">
          <option value="">Select Course</option>
          <?php
          foreach($courseIds as $courseId)
          {
                  $courseObj = $courseRepository->find($courseId);
                  ?>
                  <option value="<?=$courseId?>"><?=$courseObj->getName();?></option>
                  <?php
          }
          ?>
        </select>
        <div style="clear:both; display:none;" id="courseSelectedError" class="errorMsg"></div>
      </div>
      <div class="clearfix"></div>
      <div style="width:100%; text-align:center;"> <a href="javascript:void(0);" onclick="postQuestionFromCCIntermediatePage();" class="que-submit" id="postQuestionButton">Submit</a></div>
      <input type="hidden" id="instituteIdForAskInstitute" value="<?=$instituteId?>" />
      <input type="hidden" id="categoryIdForAskInstitute" value="3" />
      <input type="hidden" id="locationIdForAskInstitute" value="2" />
      <input type="hidden" id="cc_page_name" value="CC_intermediate_page" />
    </form>
    <form style="display: none;" id="postQuestionLoginFormCC" action="<?=SHIKSHA_HOME?>/muser5/MobileUser/register" method="post">
      <input type="hidden" value="<?=base64_encode($interMediateUrl['url']);?>" name="current_url">
      <input type="hidden" value="<?=base64_encode($interMediateUrl['url']);?>" name="referrer_postQuestion_cc" id="referrer_postQuestion_cc">
      <input type="hidden" value="POST_QUESTION_FROM_CC_HOMEPAGE" name="from_where">
      <?php if(!empty($_COOKIE['mobile_crtrackingPageKeyId']))
      {
        $qtrackingPageKeyId=$_COOKIE['mobile_crtrackingPageKeyId'];
      }
  ?>
      <input type="hidden" id="tracking_keyid" name="tracking_keyid" value="<?=$qtrackingPageKeyId?>">
    </form>
  </div>
  <div class="content-inner clearfix successfully-ques-container" id="successfully-posted-container" style="<?php echo (($successMsgFlag=='yes')?'display:block;':'');?>">
    <span>Your question has been successfully posted.</span>
    <p>We will update you as soon as we get an answer from a Current Student of this college.</p>
    <div class="successfully-ques-container-bootom-link ">
      <ul>
        <li><a href="javascript:void(0);" onclick="$('#postQuestionButton').html('Submit'); $('#postQuestionButton').attr('onclick','postQuestionFromCCIntermediatePage()'); $('#successfully-posted-container').hide(); $('#postQuestionFromCC').show(); $('#postQuestionFromCC_content').slideDown('slow'); $('#questionCategorySelect').html('Select Question Category'); $('#questionCourseSelect').html('Select Course'); setCookie('isUserRegistersForQuestionPosting','no',-1,'/',COOKIEDOMAIN);">Ask another question</a> |</li>
        <li> <a href="<?=$courseURL?>">View details of this college</a> </li>  
      </ul>
    </div>
  </div>
</section> 
<?php
setcookie('isUserRegistersForQuestionPosting','',time()-3600,'/',COOKIEDOMAIN);
?>