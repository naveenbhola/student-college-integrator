<div class="qstn-container" >
      <!--comment secion heading-->
       <div class="qstn-col">
          <div class="q-box-h1" id="commentPostingHeading"></div>
       </div>
      <!--comment section card view-->
      <form id="postCommentAnA" action=""  accept-charset="utf-8" method="post"  novalidate="novalidate" name="postCommentAnA"> 
       <div class="qstn-block">
          <div class="write-col">
            <h2 class="write-ans"  id="commentLayerDiscTitle"></h2>
            <textarea class="write-txt" name="write-cmnt" onkeyup="autoGrowField(this,2000);textKey(this)" validate="validateStr" minlength=15 maxlength=2500  id="comment_text_anaLayer" required="true" onpaste="handlePastedTextInTextField('comment_text_anaLayer');"></textarea>
            <p class="count-numbr" id="commentCounter">
            </p> 
            <div style="display:none;"><p class="error-msg" style="margin-top:0px !important;" id="comment_text_anaLayer_error"></p></div>
          </div>
       </div>
       <input type="hidden" name="commentTopicId" id="commentTopicId" value="" />
       <input type="hidden" name="commentParentId" id="commentParentId" value="" />
       <input type="hidden" name="editCommentId" id="editCommentId" value="0" />
       <input type="hidden" name="parentType" id="parentType" value="" />
       <input type="hidden" name="tracking_keyid" id="tracking_keyid_comment" value="">

       <a href="javascript:void(0);" id="commentPostingButton" class="p-btn u-btns" onclick="if(!validateCommentAnswerPostingField('comment_text_anaLayer','postCommentAnA')){return false;}else{initializeCommentPosting();}">Post</a>
       </form> 
  </div>