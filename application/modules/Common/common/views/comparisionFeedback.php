<link href="/public/css/compare.css" rel="stylesheet" type="text/css"/>

<div class="helpful-info clearwidth" >
               <p><strong>Was the comparison helpful?</strong></p>
               <div class="vote-section">
				<a href="javascript:void(0);" onclick="sendFeedback('1','rate')"; style="margin-right:10px"><i id="yes" class="compare-sprite upVote-icon"></i> Yes</a>
	                        <a href="javascript:void(0);" onclick="sendFeedback('0','rate')";><i id="no" class="compare-sprite dwnVote-icon"></i> No</a>
               </div>
				   
               <div class="comment-section">
                                <p id="thankMsg" style="display: none" class="thnku-msg">Thanks for your feedback!</p>
				<div class="comment-box" id="comment-box" style="display: none">
				                  <i class="compare-sprite comment-arrw"></i>
				                  <textarea id="feebackMsg" name="textarea" class="commnt-textarea" onclick = "
				                  $(this).innerHTML='';$j('#feebackMsg').css('color','#666'); ">Please share your comments?</textarea>
				                  <input type="button" class="sbmt-btn" onclick="sendFeedback('3','msg');" value="Submit"/>
				                  <div class="errorMsg" id="commentErr" style="display: none"></div>
				</div>
	       </div>
</div>



<script>
	    if(getCookie('feedback_comparepage') != ''){ 
	       feedback_comparepageVal = getCookie('feedback_comparepage');
		var res = feedback_comparepageVal.split("|");
		if(res[1] == '1'){
				$('yes').classList.add("upVote-orng-icon");
		}
		else{
				$('no').classList.add("dwnVote-orng-icon");
			
		}
		$('thankMsg').innerHTML = 'You have already given the feedback.';
		$('thankMsg').style.display = 'inline';
	}
</script>
