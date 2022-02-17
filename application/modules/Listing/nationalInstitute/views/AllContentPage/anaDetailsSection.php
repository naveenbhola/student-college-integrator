<div class="ana-col-md-8 no-padLft left-widget">
<div class="row">
<div class="ana-wrap">

<div id="tags-head-post-q" class="tags-head" style="display:none;"><span>Ask your Question</span>
<a id="cls-close-first-layer" class="cls-head" href="javascript:void(0);"></a></div>
<form id="anaPostFormQues" action="" accept-charset="utf-8" method="post" novalidate="novalidate" name="anaPostFormQues">
<div id="qsn-post" class="post-col">
<div class="opacticy-col"><img border="0" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" id="loadingImageNew" alt="" class="small-loader"></div>
<div class="post-qstn">
<h2 class="post-h2">Your Question</h2>

<p class="txt-count" id="qstn-input_counter_outer">Characters <span id="qstn-input_counter">0</span>/140</p>
<textarea type="text" onkeyup="autoGrowField(this,200);textKey(this)" minlength="20" maxlength="140" name="question" placeholder="Type Your Question" caption="Question" validate="validateStr" required="true" class="qstn-input" id="qstn-input" onkeypress="handleCharacterInTextField(event);" onpaste="handlePastedTextInTextField('qstn-input');" ga-attr="QUESTION_CTA" style="overflow-y: hidden;"></textarea>
<div style="display:none;"><p class="err0r-msg" id="qstn-input_error">The Answer must contain atleast 20 characters.</p></div>
</div>    

<div id="more-qstns-posting" class="more-qstns" style="overflow: hidden; display: none;">
<?php if(!empty($instituteCourses)){
        ?>
                   <div class="slct-box" id="courseSelectionTab">
                        <!-- <p id="askCourseSelected" onclick="showAskCoursesDropdown();">Select Course</p> -->
                        <input type="text" id="askCourseSelected"  onfocus="showAskCoursesDropdown()" placeholder="Select Course" onkeyup="inputSearchList()" autocomplete="off">

                        <div class="box-dwn" id="ask_courses" >
                          <ul class='course-ul' id="cLst">
                             <div id="courseSelectionTiny">
                                <div class="scrollbar"><div class="track" style="">
                                <div class="thumb" style=""><div class="end"></div></div></div></div>
                                <div class="viewport" style="">
                                                <div id="mainOvLst" class="overview list" style="">
                            <?php 
                            $i=0;
                            foreach($instituteCourses as $key=>$courses){?>
                                <li class="course-li" ><a  data-id= "<?=$courses['course_id']?>" onclick="askCourseSelection('<?=$courses['course_id']?>','<?=addslashes($courses['course_name'])?>');showAskCoursesDropdown();"><?=$courses['course_name']?></a></li>
                            <?php 

                            }

                             ?>
                      </div>
                    </div>
                    </div>
                          </ul>
                        </div>
                   </div>
                   <div style="display:none;" id="cLst_error"><p class="err0r-msg">Please select a course from drop down list.</p></div>
                   <input type="hidden" id="instituteCoursesQP" name="instituteCoursesQP" value="">
                    <input type="hidden" id="instituteIdQP" name="instituteIdQP" value="<?php echo $instituteObj->getId();?>">
                    <input type="hidden" id="responseActionTypeQP" name="responseActionTypeQP" value="Asked_Question_On_All_Question">
                          
            <?php    
} ?>
<a id="lnk-add-more" class="h2-blue " onclick="gaTrackForQuesDiscPost('addMore')" style="padding-left: 20px;"><i class="before" id="plus-minus-icon" nextclass="after"></i>Add more details</a>
<div id="more-ques-posting">
<p class="txt-count topcount" id="more-input-posting_counter_outer">Characters <span id="more-input-posting_counter">0</span>/300</p>
<textarea onkeypress="handleCharacterInTextField(event);" class="more-input" id="more-input-posting" minlength="20" maxlength="300" onkeyup="autoGrowField(this,300);textKey(this)" caption="Description" validate="validateStr" placeholder="Give Information like score, education background etc." onpaste="handlePastedTextInTextField('more-input-posting');"></textarea>
<div style="display:none;"><p class="err0r-msg" id="more-input-posting_error">The Answer must contain atleast 20 characters.</p></div>
</div>
<div class="btns-col">
<span class="tag-note">Keep it short &amp; simple. Type complete word. Avoid abusive language.</span>
<span class="right-box">
<a class="exit-btn" href="javascript:void(0);" id="cancelButtonPosting">Cancel</a>
<a class="prime-btn" href="javascript:void(0);" id="nextButtonPosting" onclick="gaTrackForQuesDiscPost('Next');">Next</a>
</span>
<p class="clr"></p>
</div>
</div>
</div>
</form>
<div id="qsn-prvw" class="post-col">
<div class="opacticy-col"><img border="0" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" id="loadingImageNew" alt="" class="small-loader"></div>
<div class="post-qstn">
<h3 class="post-h2 qdTitle-l2">Your Question</h3>
<p class="qstn-title" id="qstn-title-posting"><span></span><a href="javascript:void(0);" class="edit-qstn" id="edit-qstn">Edit</a></p>
</div>
<div class="more-qstns">
<div id="slctd-tags" class="tags-slctd">
</div>
<div class="btns-col">
<span class="tag-note">Add relevant tags to get quick responses.</span>
<span class="right-box">
<a class="exit-btn" href="javascript:void(0);" id="cancelButtonSecondLayer">Cancel</a>
<a class="prime-btn" href="javascript:void(0);" id="finalButtonPosting" onclick="gaTrackForQuesDiscPost('Post')">Post</a>
</span>
<p class="clr"></p>
</div>
<div id="similar_ques_outer" class="similar_ques_outer">
<br><br><br>
<img border="0" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" id="loadingImageNew" alt="" class="small-loader">
</div>
</div>
<input type="hidden" id="tracking_keyid_ques" value="949">
<input type="hidden" id="tracking_keyid_disc" value="">
<input type="hidden" id="quesDiscKeyId" value="949">
</div>
<div id="addMoreTagsLayer">
</div>
<script>
    var GA_userLevel = '<?php echo $GA_userLevel;?>';
</script>
</div>
<div id="delete-layer" class="tags-layer"></div>
<div id="closeDeletelayer" class="posting-layer" style="width:580px;">
<div class="tag-body">
<p class="msg" id="responseHeading"></p>
<div class="btns-col">
<span class="right-box">
<a class="prime-btn" href="javascript:void(0);" onclick="hidecloseDeleteLayer('closeDeletelayer')">Ok</a>
</span>
<p class="clr"></p>
</div>
</div>
</div>

<div class="ana-selection">

<?php $this->load->view('AllContentPage/widgets/anaTabsData'); ?>
    <div class="tabs-content">
    	<?php $this->load->view('AllContentPage/widgets/anaTuple'); ?>
    </div>

</div>
</div>
</div>

            <input type="hidden" id="pageType" value="<?php echo $pageType;?>" />
            <input type="hidden" id="entityIdUserList" value="">
            <input type="hidden" id="entityTypeUserList" value="">
            <input type="hidden" id="actIonForUserList" value="">
            <input type="hidden" id="userTotalCount" value="">
            <input type="hidden" id="tracking_keyid_UserList" value="">

<script>
var homePageType = 'allQuestionPage';
var postingType = 'question';
function LazyLoadAnADesktopCallback(){
    $LAB
    .script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api");?>',
            '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ana_desktop");?>',
            '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("quesDiscPosting");?>')
    .wait(function(){;
      LazyLoadCompleteCallbackAnA({'page' : homePageType , 'postingType' : postingType});
    });
}
</script>
