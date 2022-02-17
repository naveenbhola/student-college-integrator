<form id="postQuestionFrom" method="post" onsubmit="return false;" autocomplete="off"  novalidate style="display: inline-block; width: 100%;">
        <div class="search-box" style="display:block">
            <button id="ask_btn" class="search-btn0 flRt ask-search-btn" style="width:62px; color:#fff; font-size:12px;" onclick="return validatePostQuestion(document.getElementById('postQuestionFrom'),'<?php echo $qtrackingPageKeyId;?>'); return false;" defaulttext="Submit">Submit</button>
            <div class="serach-field" style="margin-right:62px">
                <input id="replyText" type="text" placeholder="Write your question here" style="text-transform:none;" onkeyup="textKey(this);" validate="validateStr" caption="Question" maxlength="140" minlength="2" required="true" validatesinglechar="true">
                <p style="font-size:12px; margin-top:5px;"><span id="replyText_counter">0</span> out of 140 characters</p>
                <div class="errorPlace Fnt11"><div style="display:none;" class="errorMsg" id="replyText_error"></div></div>
                <div class="thnx-msg Fnt11" style="display:none;" id="askThanksMsg">
                     <i class="icon-tick"></i>
                     <p style="font-size:11px !important;">Thank You! Current students of this college will reply to your question shortly.</p>
               </div>
            </div>
        </div>
        <div class="clearfix"></div>
      <input type="hidden" value="<?php echo $instituteId;?>" id="instituteId">
                <input type="hidden" value="<?php echo $locationId;?>" id="locationId">
                <input type="hidden" value="<?php echo $courseId;?>" id="courseId">
                <input type="hidden" value="<?php echo $categoryId;?>" id="categoryId">
                <input type="hidden" name="getmeCurrentCity" id="getmeCurrentCity" value="<?php echo $currentLocation->getCity()->getId();?>"/>
                <input type="hidden" name="getmeCurrentLocaLity" id="getmeCurrentLocaLity" value="<?php echo $currentLocation->getLocality()->getId();?>"/>
                <input type="hidden" name="tracking_keyid" id="tracking_keyid" value="<?php echo $qtrackingPageKeyId;?>">

</form>