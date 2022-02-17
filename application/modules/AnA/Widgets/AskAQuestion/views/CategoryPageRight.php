<?php
$defaultQuestionText = "Need expert guidance on education career? Ask our experts.";
$trackCode = "ASK_QUESTION_COMMON_RIGHT_WIDGET";
if(strpos($_SERVER['HTTP_REFERER'],'-article-')!==false){
    $trackCode = "ARTICLE_DETAIL_PAGE_RIGHT_COLUMN";
}
else if(strpos($_SERVER['HTTP_REFERER'],'-categorypage-14-')!==false){
    $trackCode = "TESTPREP_CATEGORY_PAGE_RIGHT_COLUMN";
}
else if(strpos($_SERVER['HTTP_REFERER'],'-categorypage-')!==false || strpos($_SERVER['HTTP_REFERER'],'/colleges/')!==false){
    $trackCode = "CATEGORY_PAGE_RIGHT_COLUMN";
}


?>
<form id="askQuestionFormSmall" name="askQuestionFormSmall" method="get" action="" onsubmit="checkTextElementOnTransition($('questionTextSmall'),'focus'); if(validateFields(this) != true){return false;} setCookie('homepageQuestion',document.getElementById('questionTextSmall').value,0);proceedToPostQuestionFromHome(this,'questionTextSmall');">
<div class="askAQuestBlock">
    <h2>Ask A Question</h2>
    <textarea style="color:#959494" rows="3" cols="3" name="questionText" id="questionTextSmall" autocomplete="off"
                onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }" profanity="true"
                onkeyup="if(event.keyCode == 13){ return false;} else { textKey(this); }" 
                validate="validateStr" caption="Question" maxlength="140" minlength="2" required="true"
                onfocus="checkTextElementOnTransition(this,'focus');"
                onblur="checkTextElementOnTransition(this,'blur');"
                default="<?php echo $defaultQuestionText; ?>"><?php echo $defaultQuestionText; ?></textarea>
    <input type="hidden" name="crs_pg_prms" id="crs_pg_prms" value="<?php echo $crs_pg_prms; ?>"/>
    <div class="spacer5 clearFix"></div>
    <div><div class="errorMsg" id="questionTextSmall_error"> </div></div>
    <div><span id="questionTextSmall_counter">0</span> out of <span>140</span> characters</div>
    <div class="spacer5 clearFix"></div>
    <input type="submit" value="Get an Answer" title="Get an Answer" class="orange-button" style="font-size:14px;" onClick="trackEventByGA('ASK_NOW_BUTTON','<?=$trackCode?>');"/>
    <input type="hidden" name="tracking_keyid" id="tracking_keyid" value="<?php echo $trackingPageKeyId;?>">
</div>
</form>
