<?php
$postingForType = "";
 if(($pageType == 'discussion' || $pageType == 'ALL_DISCUSSION_PAGE' )&& isset($data['userDetails']['levelId']) && intval($data['userDetails']['levelId']) >= 11){
    $entity = "Discussion";
    $postingForType = "discussion";
    $GA_Tap_On_Type_Your_QD = $GA_Tap_On_What_Discussion;
}else {
    $entity = "Question";
    $postingForType = "question";
    $GA_Tap_On_Type_Your_QD = $GA_Tap_On_What_Question;
}

?>
<form id="anaPostFormQues" action=""  accept-charset="utf-8" method="post"  novalidate="novalidate" name="anaPostFormQues">
    <div id="qsn-post" class="post-col">
        <div class="opacticy-col"><img border="0" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" id="loadingImageNew" alt="" class="small-loader"></div>
         <div class="post-qstn">
            <?php 
                $qPostingTitle = (isset($qPostingTitle) && $qPostingTitle !='')?$qPostingTitle:'Need guidance on career and education? Ask our experts';
            ?>
            <h3 class="post-h2"><?=$qPostingTitle;?></h3>
  
            <p class="txt-count" id="qstn-input_counter_outer">Characters <span class="" id="qstn-input_counter"> 0</span>/140</p>
            <textarea type="text" onkeyup="autoGrowField(this,200);textKey(this)" minlength="20" maxlength="140" name="question" placeholder="Type Your <?=ucfirst($postingForType)?>" caption="Question" validate="validateStr" required="true" class="qstn-input" id='qstn-input' onkeypress="handleCharacterInTextField(event);" onpaste="handlePastedTextInTextField('qstn-input');" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Type_Your_QD;?>','<?php echo $GA_userLevel;?>');" tabindex=1></textarea>
            <div style="display:none;"><p class="err0r-msg" id='qstn-input_error'>The Answer must contain atleast 20 characters.</p></div>

            <?php if(!empty($instituteCourses) && $courseViewFlag){
                        ?>
                           <div class="slct-box" id="courseSelectionTab">
                                <!-- <p id="askCourseSelected" onclick="showAskCoursesDropdown();">Select a course on which you want to ask this question</p> -->

                                 <input type="text" id="askCourseSelected"  onfocus="showAskCoursesDropdown()" placeholder="Select a course on which you want to ask this question" onkeyup="inputSearchList()" autocomplete="off">

                                <div class="box-dwn" id="ask_courses" >
                                  <ul class='course-ul' id="cLst">
                                     <div id="courseSelectionTiny">
                                        <div class="scrollbar"><div class="track" style="">
                                        <div class="thumb" style=""><div class="end"></div></div></div></div>
                                        <div class="viewport" style="">
                                                        <div id="mainOvLst" class="overview list" style="">
                                    <?php foreach($instituteCourses as $key=>$courses){
										if($courses['course_id']){
										?>
                                        <li class="course-li" ><a data-id= "<?=$courses['course_id']?>" onclick="askCourseSelection('<?=$courses['course_id']?>','<?=addslashes($courses['course_name'])?>');showAskCoursesDropdown();"><?php echo $courses['course_name'];?></a></li>
                                    <?php } } ?>
                              </div>
                            </div>
                            </div>
                                  </ul>
                                </div>
                           </div>
                          <div style="display:none;" id="cLst_error"><p class="err0r-msg">Please select a course from drop down list.</p></div>
                        <?php    
                } ?>
                <?php $courseId = (isset($courseIdQP) && $courseIdQP>0)?$courseIdQP:'';?>
                <input type="hidden" id="instituteCoursesQP" name="instituteCoursesQP" value="<?php echo $courseId;?>">
                <input type="hidden" id="instituteIdQP" name="instituteIdQP" value="<?php echo $instituteId;?>">
                <input type="hidden" id="responseActionTypeQP" name="responseActionTypeQP" value="<?php echo $responseAction;?>">
         </div>

            <div id="more-qstns-posting" class="more-qstns" >
                <a id="lnk-add-more" class="h2-blue " onclick="gaTrackForQuesDiscPost('addMore')" tabindex=2 href="javascript:void(0);"><i class="before" id="plus-minus-icon" nextClass='after'></i>Add more details</a>
                <div id="more-ques-posting">
                    <p class="txt-count" id="more-input-posting_counter_outer">Characters <span class="" id="more-input-posting_counter"> 0</span>/300</p>
                    <textarea onkeypress="handleCharacterInTextField(event);" class="more-input" id="more-input-posting" minlength="20" maxlength="300" onkeyup="autoGrowField(this,300);textKey(this)" <?php if($postingForType=="discussion") { ?> required = "true" <?php } ?> caption="Description" validate="validateStr"  placeholder="Give Information like score, education background etc." onpaste="handlePastedTextInTextField('more-input-posting');" tabindex=3></textarea>
                    <div style="display:none;"><p class="err0r-msg" id="more-input-posting_error">The Answer must contain atleast 20 characters.</p></div>
                </div>
                <div class="btns-col">
                    <span class="tag-note">Keep it short &amp; simple. Type complete word. Avoid abusive language.</span>
                    <span class="right-box">
                        <a class="exit-btn" href="javascript:void(0);" id="cancelButtonPosting" tabindex=4>Cancel</a>
                        <a class="prime-btn" href="javascript:void(0);" id="nextButtonPosting" onclick="gaTrackForQuesDiscPost('Next');" tabindex=5>Next</a>
                    </span>
                    <p class="clr"></p>
                </div>
            </div>
    </div>
</form>