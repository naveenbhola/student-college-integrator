<div class="srp_block">
    <div class="qnas_rslt">
        <?php if($tupleData['resultCount'] > 1) {
            $resultText = 'results';
        } else {
            $resultText = 'result';
        } ?>
        <p>Showing <?php echo $tupleData['resultCount'].' '.$resultText; ?> for <strong>"<?php echo $keyword; ?>"</strong></p>
    </div>
    
    <div class="topic-tuples">
        <?php foreach ($tupleData['result'] as $tagId => $tagData) { ?>
            <div class="topic_card">
                <div class="topic_title">
                    <h2 class="srp_title"><a href="<?php echo $tagUrlInfo[$tagId]['url'] ?>"><?php echo $tagData['tagName']; ?></a></h2>
                    
                    <?php if($tagData['follow']) { ?>
                        <a href="javascript:void(0);" class="tag-unflw-btn" reverseclass="tag-flw-btn" tag_id="<?php echo $tagId ?>" callforaction="unfollow">Unfollow</a>
                    <?php } 
                    else { ?>
                        <a href="javascript:void(0);" class="tag-flw-btn" reverseclass="tag-unflw-btn" tag_id="<?php echo $tagId ?>" callforaction="follow">Follow</a>
                    <?php } ?>
                </div>
                
                <?php if($tagData['quesCount'] || $tagData['answerCount'] || $tagData['followCount']) { ?>
                    <div class="flwrs_block" id="infoBlock_<?php echo $tagId ?>">
                        <?php if($tagData['quesCount']) { 
                            if($tagData['quesCount'] > 1 || strpos($tagData['quesCount'], 'k') > 0) {
                                $quesText = 'Questions';
                            } else {
                                $quesText = 'Question';
                            } ?>
                            <span><strong><?php echo $tagData['quesCount']; ?></strong><?php echo $quesText; ?></span>
                        <?php } ?>

                        <?php if($tagData['answerCount']) {
                            if($tagData['answerCount'] > 1 || strpos($tagData['answerCount'], 'k') > 0) {
                                $ansText = 'Answers';
                            } else {
                                $ansText = 'Answer';
                            } ?>
                            <span><strong><?php echo $tagData['answerCount']; ?></strong><?php echo $ansText; ?></span>
                        <?php } ?>

                        <?php if($tagData['followCount']) {
                            if($tagData['followCount'] > 1 || strpos($tagData['followCount'], 'k') > 0) {
                                $followText = 'Followers';
                            } else {
                                $followText = 'Follower';
                            } ?>
                            <span id="followerCount_<?php echo $tagId ?>"><strong><?php echo $tagData['followCount']; ?></strong><?php echo $followText; ?></span>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>