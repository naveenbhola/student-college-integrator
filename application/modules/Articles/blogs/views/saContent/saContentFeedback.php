<?php if($content['data']['type'] == 'article')
    {
        $type = 'Article';
    }
    
    else
    {
        $type = 'Student Guide';
    }
?>

<div class="helpful-info clearwidth">
    <p><strong>Was this <?=$type;?> helpful?</strong></p>
    <div class="vote-section">
	<a href="javascript:void(0);" onclick="sendFeedbackForArticle('1','<?=strtolower($type);?>')"; style="margin-right:10px"><i id="yes" class="article-sprite upVote-icon"></i> Yes</a>
	<a href="javascript:void(0);" onclick="sendFeedbackForArticle('0','<?=strtolower($type);?>')";><i id="no" class="article-sprite dwnVote-icon"></i> No</a>
    </div>
			    
    <div class="font-12" id="feedbackPeopleCount">
    <?php if($rating['totalLikes']>0)
    {
	echo $rating['totalLikes'];?> out of <?php echo $rating['totalLikesAnsDisLikes'];?> people found this <?php echo strtolower($type);?> helpful.
	<?php } ?>
    </div>
    <p id="thankMsg" style="display: none" class="thnku-msg">Thanks for your vote!</p>
         </div>
