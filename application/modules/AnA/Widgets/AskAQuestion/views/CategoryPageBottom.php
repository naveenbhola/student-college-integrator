<?php
$defaultQuestionText = "Need expert guidance on education career? Ask our experts.";
$trackCode = "ASK_QUESTION_COMMON_BOTTOM_WIDGET";
if(strpos($_SERVER['HTTP_REFERER'],'-article-')!==false){
    $trackCode = "ARTICLE_DETAIL_PAGE_BOTTOM";
}
else if(strpos($_SERVER['HTTP_REFERER'],'-categorypage-14-')!==false){
    $trackCode = "TESTPREP_CATEGORY_PAGE_BOTTOM";
}
else if(strpos($_SERVER['HTTP_REFERER'],'-categorypage-')!==false){
    $trackCode = "CATEGORY_PAGE_BOTTOM";
}

?>
<form id="askQuestionForm" name="askQuestionForm" method="get" action="" onsubmit="checkTextElementOnTransition($('questionText'),'focus');
if(validateFields(this) != true){return false;} 
setCookie('homepageQuestion',document.getElementById('questionText').value,0);proceedToPostQuestionFromHome(this,'questionText');">
<div class="qnaBlock">
    <div class="qnaFigure">&nbsp;</div>
    <div class="qnaDetails">
        <strong>Have a question? Ask our Experts!</strong>
        <textarea style="color:#959494" rows="3" cols="3" name="questionText" id="questionText" autocomplete="off"
                onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }" profanity="true" 
                onkeyup="if(event.keyCode == 13){ return false;} else { textKey(this); }" 
                validate="validateStr" caption="Question" maxlength="140" minlength="2" required="true"
                onfocus="checkTextElementOnTransition(this,'focus');"
                onblur="checkTextElementOnTransition(this,'blur');"
                default="<?php echo $defaultQuestionText; ?>"><?php echo $defaultQuestionText; ?></textarea>
        <input type="hidden" name="crs_pg_prms" id="crs_pg_prms" value="<?php echo $crs_pg_prms; ?>"/>
        <input type="hidden" id="tracking_keyid" name="tracking_keyid" value="<?php echo $questionTrackingPageKeyId;?>">

         <div class="spacer8 clearFix"></div>
        <div><div class="errorMsg" id="questionText_error"> </div></div>
        <div class="flLt"><span id="questionText_counter">0</span> out of <span>140</span> characters</div>
        <div class="flRt"><input type="submit" value="Ask Now" title="Ask Now" class="orangeButtonStyle" style="font-size:14px;"  onClick="trackEventByGA('ASK_NOW_BUTTON','<?=$trackCode?>');" /></div>
    </div>
    <div class="clearFix"></div>
</div>
</form>
