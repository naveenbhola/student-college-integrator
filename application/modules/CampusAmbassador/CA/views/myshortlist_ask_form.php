<?php
      //$pageKeyForAskQuestion = 'LISTING_INSTITUTEDETAIL_ASK_INSTITUTE_POSTQUESTION';
?>
<form id="myShortlist_askQuest_form<?=$course->getId()?>" method="post" action="" autocomplete="off" novalidate="" onsubmit="return false;">
      
      <div class="ask-pannel">
	   <div id="ask_main_<?=$course->getId()?>">
		  <a class="btn-ask" id="askbtn_<?=$course->getId()?>">Ask</a>
		  <div class="ask-field">
		      <input type="text" onfocus='$j(this).css("color","#333"); askCTAShortlist(<?php echo $course->getId()?>, <?php echo $instituteId ?>,<?php echo $trackingPageKeyId ?>);' placeholder="Enter your question here..." validatesinglechar="true" required="true" minlength="2" maxlength="140" caption="Question" validate="validateStr" id="replyText" onkeyup="textKey(this);" name="replyText">
		  </div>
		  <div class="errorPlace Fnt11"><div style="font-size:12px" id="replyText_error" class="errorMsg"  style="display:none;"></div></div>
           </div>
            <div id="quest_succ_<?=$course->getId()?>" style="display: none;background:#fefbcd; padding:2px 5px; width:auto; font-size:12px;">Question successfully Posted.</div>     
      </div>   
</form>