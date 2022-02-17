  <div class="qstn-container">
      <!--comment secion heading-->
       <div class="qstn-col">
          <div class="q-box"><p class="q-titl qdTitleHead">Question</p></div>

          <?php if(!$ampPageFlag){?>
          <div class="q-cls"><a href="javascript:void(0);" data-enhance="false" data-rel="back" onclick="resetQuestionLayer();">&times;</a></div>
          <?php }else{?>
              <div class="q-cls"><a href="javascript:void(0);" data-enhance="false" onclick="window.history.go(-1);">&times;</a></div>
          <?php } ?>
       </div>
      <!--comment section card view--> 
      <form id="anaPostFormQues" action=""  accept-charset="utf-8" method="post"  novalidate="novalidate" name="anaPostFormQues">
        <input type='hidden' name="entityType_AnA" id="entityType_AnA" value='question'> 
        <input type='hidden' name="postingActionQnD" id="postingActionQnD" value=''> 
        
       <div class="qstn-block">
        <div id="courseSelectionDropDown">
          <?php if(!empty($instituteCourses)){?>
            <p class="num-char course-margin"><span>Course:</span></p>
            <div id="courseSection">
              <div class="slct-box" id="courseSelectionTab">
              <input type="text" id="askCourseSelected" class="courseSelectedQP" onkeyup="inputSearchList()" onfocus="showAskCoursesDropdown()" placeholder="Select a course" autocomplete="off">
                <div class="q-box-dwn" id="ask_courses">
                  <ul id="cLst">
                    <?php foreach($instituteCourses as $key=>$value){?>
                     <li data-id= "<?=$value['course_id']?>" onclick="askCourseSelection('<?=$value['course_id'];?>','<?php echo addslashes($value['course_name']);?>');showAskCoursesDropdown();"><?=$value['course_name'];?>
                     </li>
                    <?php } ?>
                  </ul>
                </div> 
              </div>
            </div>
            <div style="display:none;" id="cLst_error"><p class="error-msg">Please select a course from drop down list.</p></div>
          <?php } ?>

        </div>
         <p class="num-char"><span class='qdTitleHead'>Question</span>: </p>
         <div class="text-input">
           <textarea id='ques_title_ana' required="true" type="text" placeholder="Type Your Question" name="type qustion"  class="qstn-input" validate="validateStr" minlength="20" maxlength="140" caption="Question" onkeyup="autoGrowField(this,200);textKey(this);maxLenIndi('ques_title_ana','qna_title_counter_p');" onpaste="handlePastedTextInTextField('ques_title_ana');"></textarea>
           <p class="num-char"><span class="num-count" id='qna_title_counter_p'><span id='ques_title_ana_counter'>0</span>/140</span></p>
           <div style="display:none;"><p class="error-msg" id="ques_title_ana_error">Question must contain at least 20 characters</p></div>
             <div class="instruct-col">
                 <p class="qstn-instrct">- Keep it short and simple</p>
                 <p class="qstn-instrct">- Use complete words. Avoid slang & abusive language</p>
             </div>
         </div>
       
         <!--textarea-->
         <div class="enter-inf">
             <p class="num-char">Description <span id="qdDescOptional">(optional):</span></p>
             <textarea class="enter-dtls" placeholder="Give information like score, education background, etc." name="enter dtls" onkeyup="autoGrowField(this,1300);textKey(this);maxLenIndi('ques_desc_ana','qna_desc_counter_p')" validate="validateStr" minlength="20" maxlength="300" caption="Description" id="ques_desc_ana" onpaste="handlePastedTextInTextField('ques_desc_ana');"></textarea>
             <p class="num-char"><span class="num-count" id="qna_desc_counter_p"><span id='ques_desc_ana_counter'>0</span>/300</span></p>
             <div style="display:none;"><p class="error-msg" id="ques_desc_ana_error">Question must contain at least 20 characters</p></div>
         </div>
       <!--textarea ends-->  
       </div>
       <a href="javascript:void(0);" class="p-btn u-btns" onclick="validateAnAQuesDiscPostFields();" id='nxt-btn-post'>Next</a> 
       <input type="hidden" id="questionPostingEntityId" name="questionPostingEntityId" value="0" />
       <input type="hidden" name="tracking_keyid" id="tracking_keyid" value="<?=$trackingKeyId;?>"/>
     </form>
  </div>