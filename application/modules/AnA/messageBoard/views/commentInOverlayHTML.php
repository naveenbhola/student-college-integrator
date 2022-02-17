<div class="comment-detail">
    <p>
	<?php
	$text = html_entity_decode(html_entity_decode($text,ENT_NOQUOTES,'UTF-8'));
	echo formatQNAforQuestionDetailPage($text,500);
	?>
    </p>
    <p><span>Posted by:&nbsp;</span><?php echo $displayName;?></p>
</div>