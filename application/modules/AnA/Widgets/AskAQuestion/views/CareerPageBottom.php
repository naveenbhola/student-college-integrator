<?php
$defaultQuestionText = "Need expert guidance on education or career? Ask our experts.";
$trackCode = "ASK_QUESTION_CAREERS_BOTTOM_WIDGET";
if(strpos($_SERVER['HTTP_REFERER'],'-article-')!==false){
    $trackCode = "ARTICLE_DETAIL_PAGE_BOTTOM";
}
else if(strpos($_SERVER['HTTP_REFERER'],'-categorypage-14-')!==false){
    $trackCode = "TESTPREP_CATEGORY_PAGE_BOTTOM";
}
else if(strpos($_SERVER['HTTP_REFERER'],'-categorypage-')!==false || strpos($_SERVER['HTTP_REFERER'],'/colleges/')!==false){
    $trackCode = "CATEGORY_PAGE_BOTTOM";
}

?>
<form id="askQuestionForm" name="askQuestionForm" method="get" action="" onsubmit="checkTextElementOnTransition($('questionText'),'focus'); 
if(validateFields(this) != true){return false;} 
setCookie('homepageQuestion',document.getElementById('questionText').value,0);proceedToPostQuestionFromHome(this,'questionText');">
<div class="ask-expert-widget">
    
    <div>
        <h3>Have question about career as <?php echo $careerName;?>? Ask our Experts</h3>
	<div class="clearFix"></div>
	<p>Over 500 Experts are answering queries</p>
	<div class="spacer5 clearFix"></div>
	<div style="width:550px;float:left; padding-right:12px">
        <textarea class="universal-select" style="color:#959494" rows="3" cols="3" name="questionText" id="questionText" autocomplete="off"
                onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }" profanity="true" 
                onkeyup="if(event.keyCode == 13){ return false;} else { textKey(this); }" 
                validate="validateStr" caption="Question" maxlength="140" minlength="2" required="true"
                onfocus="checkTextElementOnTransition(this,'focus');"
                onblur="checkTextElementOnTransition(this,'blur');"
                default="<?php echo $defaultQuestionText; ?>"><?php echo $defaultQuestionText; ?></textarea>
         <div class="spacer5 clearFix"></div>
        <div><div class="errorMsg" id="questionText_error"> </div></div>
	<div class="spacer5 clearFix"></div>
        <div class="flRt" style="font-size:13px"><span id="questionText_counter">0</span> out of <span>140</span> characters</div>
</div>
	<div class="flLt" style="padding-top:44px"><input type="submit" value="Get an Answer" title="Get an Answer" class="orangeButtonStyle" style="font-size:16px !important" onClick="trackEventByGA('ASK_NOW_BUTTON','<?=$trackCode?>');" />
        <input type="hidden" name="tracking_keyid" id="tracking_keyid" value='<?=$trackingPageKeyId?>'></div>
    </div>


    <div class="clearFix"></div>
</div>
</form>
