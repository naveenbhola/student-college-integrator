<!--answer-posting-col-->
<div id="entityPostingCol_<?=$questionId?>" style="display: none">
    <form id="postEntityAnA_<?=$questionId?>" action="" accept-charset="utf-8" method="post"  novalidate="novalidate" name="postAnswerAnA">
        <div class="ans-block new__ans__block" >
            <p class="txt-count" style="display:block;" id="entityTxtCounter<?=$questionId?>">Characters <span id="entity_text_<?=$questionId?>_counter">0</span>/2500</p>
            <textarea placeholder="Write your answer. Feel free to share your opinion and experience, the community will appreciate it." onkeypress="handleCharacterInTextField(event,true);" onkeyup="autoGrowField(this,300);textKey(this)" validate="validateStr" minlength=15 maxlength=2500 caption="Answer" id="entity_text_<?=$questionId?>" required="true" onpaste="handlePastedTextInTextField('entity_text_<?=$questionId?>',true);" ></textarea>
          
            <div class="btns-col">
                <span class="right-box">
                    <a class="exit-btn qtbCncl" href="javascript:void(0);" data-threadId="<?=$questionId?>">Cancel</a>
                    <a class="prime-btn ansPostingBtn" href="javascript:void(0);" data-threadId="<?=$questionId?>" id="entityPostingButton<?=$questionId?>">Post</a>
                </span>
                <p class="clr"></p>
            </div>
            <input type="hidden" id="threadId_<?=$questionId?>" value="<?=$questionId?>" />
            <input type="hidden" id="actionOnAns_<?=$questionId?>" value="add" />
            <input type="hidden" id="parentType<?=$questionId?>" value="question" />
            <input type="hidden" id="entityType<?=$questionId?>" value="Answer" />
            <input type="hidden" id="tracking_keyid_<?php echo $questionId;?>" value="<?php echo $trackingKeyId; ?>">
        </div>
        
        <div>
            <p class="err0r-msg"  id="entity_text_<?=$questionId?>_error"></p>
        </div>
    </form>
</div>