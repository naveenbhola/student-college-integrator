 <div class="comment-box" style="margin-bottom:0;border-top:1px dashed #ccc;margin-left:5px;margin-right:20px;">

<p><?php  $text = html_entity_decode(html_entity_decode($text,ENT_NOQUOTES,'UTF-8'));
	echo formatQNAforQuestionDetailPage($text,500);?>
</p>
 <p class="ques-head">
<strong class="fllt"><span>Posted by:</span> <a target="_blank" href="/getUserProfile/<?php echo $displayName;?>"><?php echo $nameToBeDisplayed;?></a></strong>
<span> | a few secs ago </span>
</p>
<div class="clearFix"></div>
</div>

		                    	
