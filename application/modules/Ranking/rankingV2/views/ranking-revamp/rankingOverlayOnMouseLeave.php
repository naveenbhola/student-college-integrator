<div  id="overlayOnLeave"  class="" style="display:none;position:absolute;z-index:10002;">
    <div class="ask-que-layer">
        <div class="layer-title">
            <a title="Close" class="flRt cross-icon" href="javascript:void(0);" onclick="hideOverlayOnEscape();">×</a>
                                <div class="title">Looking for <?php echo "Top " . $page_headline['page_name'] . " institute";?> matching your profile? Let our counsellors help you find one</div>
                        </div> 
        <div class="abt-counslr">About Shiksha Counsellors: <i>More than 400 counsellors including 100 current students of <?php echo $page_headline['page_name'];?> colleges.</i></div>
        <p class="que-title"><i class="ranking-sprite ask-que-icon2"></i> Ask your question </p>
        <form action="/messageBoard/MsgBoard/postQuestionFromCafeForm" method="get" name="askQuestionFormRanking" id="askQuestionFormRanking"
              onsubmit="checkTextElementOnTransition($('questionText'),'focus'); if(validateFields(this) != true){return false;} else { handleRankingpageAna();}"
              >
            <textarea class="que-area" name="questionText" id="questionText" onkeypress="if(event.keyCode == 13){ return false;}" onkeyup="if(event.keyCode == 13){ return false;} else { textKey(this); }" profanity="true" validate="validateStr" caption="Question" maxlength="140" minlength="2" required="true" autocomplete="off" default="Make an informed career choice, ask the expert now!" onfocus="checkTextElementOnTransition(this,'focus');" onblur="checkTextElementOnTransition(this,'blur');" style="color: rgb(173, 166, 173);" cols="2" rows="2">Make an informed career choice, ask the expert now!</textarea>
            <div class="row errorPlace" style="display:none;">
                <div id="questionText_error" class="errorMsg" style="margin-bottom:10px;"></div>
            </div>
            <input type="submit" class="orange-button rnk-orange-btn" style="padding:5px 20px !important; height:30px !important;" title="Ask Now" value="Ask Now">
            <span class="flRt p-t10"><b id="questionText_counter" style="font-weight:normal;">0</b> out of 140 characters</span>
        </form>
        
    </div>
</div>
<div class="newOverlay" id="newOverlay" style="display: none;"></div>
            

<script>
    var showOverlayOnMouseLeaveFlag = '<?php echo $showOverlayOnMouseLeave;?>';
    showOverlayOnPageLeave(showOverlayOnMouseLeaveFlag,showOverlayOnMouseLeave,hideOverlayOnEscape);
    hideDivOnBlur('overlayOnLeave','newOverlay');
</script>
