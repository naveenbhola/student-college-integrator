<div class="homeWdgt homeWdgt06 mB32">
    <div class="w01_head">
        <h1 class="wgt_HeadT1">Get Expert Guidance</h1>
        <p class="wgt_HeadT2">Ask questions to over <?php if($totalAnaExpert>0){echo $totalAnaExpert;}?> experts</p>
        <form id="askQuestionForm" name="askQuestionForm" method="get" action="" onsubmit="if(validateFields(this) != true){return false;} else { handleHomepageAna();}">
            <textarea class="edu_reltd_qtn" placeholder="Enter education or career related question" autocomplete="off" required="true" minlength="2" maxlength="140" caption="Question" validate="validateStr" profanity="true" onkeyup="if(event.keyCode == 13){ return false;} else { textKey(this); }" onkeypress="if(event.keyCode == 13){ return false;}" id="questionText" name="questionText"></textarea>
            <div class="row errorPlace">
                <div class="errorMsg erroMsgHome" id="questionText_error"></div>
            </div>
            <input type="submit" value="Submit your question" title="Submit your question" class="btn_orangeT1"/>
            <input type="hidden" name="referalUrlForAskQuestionFromHeader" id="referalUrlForAskQuestionFromHeader" value="hackParameter">
            <input type="hidden" name="tracking_keyid" id="tracking_keyid" value="">
        </form>    
        <p class="hmw_msg"><span id="questionText_counter">0</span> out of 140 characters</p>
    </div>         
</div>