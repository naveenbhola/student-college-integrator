<div class="comment-sub-section" style="margin-top:15px;">
<p class="comment-text">
<?php  $text = html_entity_decode(html_entity_decode($text,ENT_NOQUOTES,'UTF-8'));
						echo formatQNAforQuestionDetailPage($text,500);?></p>

<div class="user-details clear-width" style="margin-bottom:0">
    <p class="flLt">Posted by: <span><a target="_blank" href="/getUserProfile/<?php echo $displayName;?>"><?php echo $nameToBeDisplayed;?></a></span></p>
    <p class="flRt">a few seconds ago</p>
    <div class="clearFix spacer5"></div>
    
</div>
<div class="clearFix"></div>
</div>