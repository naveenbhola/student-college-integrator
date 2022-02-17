<div class="srp_block">
    <div class="qnas_rslt">
        <?php if($tupleData['resultCount'] > 1) {
            $resultText = 'results';
        } else {
            $resultText = 'result';
        } ?>
        <p>Showing <?php echo $tupleData['resultCount'].' '.$resultText; ?> for <strong>"<?php echo $keyword; ?>"</strong></p>
    </div>
    
    <div class="qnas-tuples">
        <?php foreach ($tupleData['result'] as $questionId => $questionData) { ?>
            <div class="topic_card">
                <?php if(!empty($questionData['tags']) && !empty($questionData['tags'][0]['tagId'])) { ?>
                    <div class="topic_title">
                        <div class="hide_cards">
                            <?php foreach ($questionData['tags'] as $key => $tags) { 
                                if(!empty($tags['tagId'])) { ?>
                                    <a class="course_card <?php if($tags['type']!='ne') {?> ent-tgMClr <?php } ?> " href="<?php echo $tags['url'] ?>"><?php echo $tags['tagName'] ?></a>
                                <?php }
                            } ?>
                        </div>
                        <i></i>
                    </div>
                <?php } ?>

                <a class="srp_title" id="quesTitle_<?php echo $questionId ?>" href="<?php echo $questionData['quesDetails']['url']; ?>"><?php echo $questionData['quesDetails']['msgTxt']; ?></a>

                <?php if($questionData['quesDetails']['viewCount'] || $questionData['quesDetails']['postedDate']) { ?>
                    <div class="flwrs_block">
                        <?php if($questionData['quesDetails']['viewCount'] > 1 || strpos($questionData['quesDetails']['viewCount'], 'k') > 0) {
                            $viewText = 'Views';
                        } else {
                            $viewText = 'View';
                        } ?>

                        <?php if($questionData['quesDetails']['viewCount']) { ?>
                            <span><strong><?php echo $questionData['quesDetails']['viewCount'].' '.$viewText; ?></strong></span>
                        <?php } ?>

                        <?php if($questionData['quesDetails']['postedDate']) { ?>
                            <span><strong><?php echo $questionData['quesDetails']['postedDate']; ?></strong></span>
                        <?php } ?>
                    </div>
                <?php } ?>

                <div class="flwup_btns">
                    <?php if($questionData['follow']) { ?>
                        <a href="javascript:void(0);" class="u-flw-txt" reverseclass="flw-txt" ques_id="<?php echo $questionId ?>" callforaction="unfollow">Unfollow</a>
                    <?php } 
                    else { ?>
                        <a href="javascript:void(0);" class="flw-txt" reverseclass="u-flw-txt" ques_id="<?php echo $questionId ?>" callforaction="follow">Follow</a>
                    <?php } ?>

                    <?php if($userId != $questionData['quesDetails']['userId'] && $questionData['quesDetails']['status'] != 'closed') { ?>
                        <a href="#answerPostingLayerDiv" data-inline="true" data-rel="dialog" data-transition="fade"  ques_id="<?php echo $questionId ?>" class="an_btn">Answer</a>
                        <input type="hidden" id="quesOwnerName<?php echo $questionId ?>" value="<?php echo $questionData['quesDetails']['firstname'];?>">
                        <div style="display:none;" id="questionFullDesc_<?php echo $questionId ?>" value="<?php echo $questionData['quesDetails']['description'];?>"><?php echo $questionData['quesDetails']['description'];?></div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
