<!--right panel starts-->
<div class="fit_right">
    <?php if($tupleData['resultCount'] > 1) {
        $resultText = 'results';
    } else {
        $resultText = 'result';
    } ?>
    <h1 class="rslt_h1">Showing <?php echo $tupleData['resultCount'].' '.$resultText; ?> for <strong>"<?php echo $keyword; ?>"</strong></h1>
    
    <div class="all_rslts topics">
        <?php foreach ($tupleData['result'] as $tagId => $tagData) { ?>
            <div class="data-list clear_max">
                <div>
                    <div class="topic_title clear_max">
                        <div class="topic_follow">
                            <div class="click_title">
                                <a class="srp_title " href="<?php echo $tagData['tagUrl'];?>"><?php echo $tagData['tagName']; ?></a>
                                
                                <?php if($tagData['follow']) { ?>
                                    <a class="flw-col un-btn tag-un-btn" curclass="un-btn" reverseclass="f-btn" callforaction="unfollow" href="javascript:void(0);" tag_id="<?php echo $tagId ?>">Unfollow</a>
                                <?php }
                                else { ?>
                                    <a class="flw-col f-btn tag-f-btn" curclass="f-btn" reverseclass="un-btn" callforaction="follow" href="javascript:void(0);" tag_id="<?php echo $tagId ?>">Follow</a>
                                <?php } ?>

                                <?php if($tagData['quesCount'] || $tagData['answerCount'] || $tagData['followCount']) { ?>
                                    <div class="flwrs_block" id="infoBlock_<?php echo $tagId ?>" >
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
                        </div>
                    </div>

                    <?php foreach ($tagData['ansdQuesData'] as $key => $quesData) { ?>
                        <div class="topic_c">
                            <div class="qstns no_margin">
                                <a href="<?php echo $quesData['qdp_url']; ?>">
                                    <?php echo $quesData['msgTxt']; ?>
                                </a>
                            </div>
                            <div class="post_history no_bold">
                               <span><strong><?php echo $quesData['viewCount']; ?></strong>Views</span>
                               <span>Posted <?php echo $quesData['postedDate']; ?></span>
                           </div>
                        </div>
                    <?php } ?>

                    <?php if(!empty($tagData['ansdQuesData']) && (($tagData['quesCount'] > 1 && count($tagData['ansdQuesData']) != $tagData['quesCount']) || strpos($tagData['quesCount'], 'k') > 0)) { ?>
                        <div class="gradient-col">
                            <a href="<?php echo $tagData['tagUrl'] ?>">View All <?php echo $tagData['quesCount'].' '.$quesText ?> </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
