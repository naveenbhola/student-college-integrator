<div class="qstn-container" >
      <!--comment secion heading-->
       <div class="qstn-col">
          <div class="q-box-h1" id="ansLayerUserName"></div>
       </div>
      <!--comment section card view-->
      <form id="postAnswerAnA" action=""  accept-charset="utf-8" method="post"  novalidate="novalidate" name="postAnswerAnA"> 
       <div class="qstn-block">
          <div class="write-col">
             <h2 class="write-ans"  id="ansLayerQuesTitle"></h2>
	     <div id="ansLayerQuesDesc" style="display:none;font-size: 11px;color: #231f20;font-weight: 400;opacity: 0.8;"></div>
            <textarea class="write-txt" name="write-cmnt" onkeyup="autoGrowField(this,2500);textKey(this)" validate="validateStr" minlength=15 maxlength=2500 caption="Answer" id="ans_text_anaLayer" required="true" onpaste="handlePastedTextInTextField('ans_text_anaLayer');"></textarea>

            <p class="count-numbr">
            <span id="ans_text_anaLayer_counter">0</span>/2500
            </p> 
            <div style="display:none;"><p class="error-msg"style="margin-top:0px !important;" id="ans_text_anaLayer_error"></p></div>
          </div>
       </div>
       <input type="hidden" name="layerThreadId" id="layerThreadId" value="" />
       <input type="hidden" name="actionOnAnswer" id="actionOnAnswer" value="add" />
       <input type="hidden" name="editAnswerId" id="editAnswerId" value="0" />
       <input type="hidden" name="tracking_keyid" id="tracking_keyid_answer" value=""/>

       <a href="javascript:void(0);"id="answerPostingButton" class="p-btn u-btns" onclick="if(!validateCommentAnswerPostingField('ans_text_anaLayer','postAnswerAnA')){return false;}else{initializeAnswerPostingAnA();}">Post</a>
       </form> 
  </div>