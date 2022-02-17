<div class="blkRound">
	<div class="bluRound">
		    <span class="float_R"><img class="pointer" onclick="$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';closeMessage();" src="/public/images/fbArw.gif" border="0"/></span>
			<span class="title">Ask a Question</span>
			<div class="clear_B"></div>
</div>
<div class="box-shadow">
<div class="contents2">
	<h3 style="font:normal 18px Trebuchet MS,Arial,Helvetica; margin-bottom:8px; width:100%; float:left">Have a question? Ask your Experts!</h3>
	<form id="askQuestionForm" name="askQuestionForm" method="get" action="" onsubmit="checkTextElementOnTransition($('questionText'),'focus'); if(validateFields(this) != true){return false;} else { setCookie('abroadpagehelpitemQuestion',document.getElementById('questionText').value,0);proceedToPostQuestionFromHome(this,'questionText'); }">
	<textarea class='universal-select' rows="2" cols="2" style="color:#ababad; width:97%; height:100px" onblur="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');" default="Make an informed career choice, ask the expert now!" autocomplete="off" required="true" minlength="2" maxlength="140" caption="Question" validate="validateStr" profanity="true" onkeyup="if(event.keyCode == 13){ return false;} else { textKey(this); }" onkeypress="if(event.keyCode == 13){ return false;}" id="questionText" name="questionText">Make an informed career choice, ask the expert now!</textarea>
	<div class="row errorPlace">
	    <div class="errorMsg" id="questionText_error"> </div>
	</div>
	<div class="clearFix spacer5"></div>
	<span><b id="questionText_counter" style="font-weight:normal;">0</b> out of 140 characters</span>
	<div class="spacer10 clearFix"></div>
	<input type="submit" value="Ask Now" title="Ask Now" class="orange-button flLt" />
	<input type="hidden" name="referalUrlForAskQuestionFromHeader" id="referalUrlForAskQuestionFromHeader" value="hackParameter">
	</form>
	<div class="clearFix spacer5"></div>
</div>
</div>
<div class="clearFix"></div>
</div>
<style>
.box-shadow .contents2 {
    clear: left;
    padding: 10px;
}

.box-shadow .contents, .box-shadow .contents2 {
    font-family: Tahoma,Geneva,sans-serif;
    padding: 15px 15px 10px;
}
</style>
