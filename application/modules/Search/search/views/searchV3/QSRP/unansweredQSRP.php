<!--right panel starts-->
<div class="fit_right">
    <?php if($tupleData['resultCount'] > 1) {
        $resultText = 'results';
    } else {
        $resultText = 'result';
    } ?>
    <h1 class="rslt_h1">Showing <?php echo $tupleData['resultCount'].' '.$resultText; ?> for <strong>"<?php echo $keyword; ?>"</strong></h1>
    <div class="all_rslts">
        <input type="hidden" id="currentTab" value="<?php echo $currentTab; ?>">
        <?php foreach ($tupleData['result'] as $questionId => $questionData) { ?>
            <div class="data-list clear_max">
                <div>
                    <?php if(!empty($questionData['tags']) && !empty($questionData['tags'][0]['tagId'])) { ?>
                        <div class="topic_title clear_max">
                            <div class="hide-topic" id="less_tags_<?php echo $questionId; ?>">
                                <?php foreach ($questionData['tags'] as $key => $tags) { 
                                    if(!empty($tags['tagId'])) { ?>
                                        <a class="course_card <?php if($tags['type']!='ne') {?> ent-tgClr <?php } ?> " href="<?php echo $tags['url']; ?>"><?php echo $tags['tagName'] ?></a>
                                    <?php }
                                } ?>
                            </div>
                            <a class="link viewAllTags" question_id="<?php echo $questionId; ?>" style="display: none">View all Tags</a>

                            <div class="all_tags" id="all_tags_<?php echo $questionId; ?>">
                                <?php foreach ($questionData['tags'] as $key => $tags) { 
                                    if(!empty($tags['tagId'])) { ?>
                                        <a class="course_card <?php if($tags['type']!='ne') {?> ent-tgClr <?php } ?> " href="<?php echo $tags['url'] ?>"><?php echo $tags['tagName'] ?></a>
                                    <?php }
                                } ?>
                            </div>
                        </div>
                    <?php } ?>
                    
                    <div class="qstns">
                        <a class="srp_title" id="quesTitle_<?php echo $questionId ?>" href="<?php echo $questionData['quesDetails']['url']; ?>"><?php echo $questionData['quesDetails']['msgTxt']; ?></a>
                    </div>

                    <div class="tabs_flow clear_max">
                        <?php if($questionData['quesDetails']['viewCount'] || $questionData['quesDetails']['postedDate']) { ?>
                            <?php if($questionData['quesDetails']['viewCount'] > 1 || strpos($questionData['quesDetails']['viewCount'], 'k') > 0) {
                                $viewText = 'Views';
                            } else {
                                $viewText = 'View';
                            } ?>
                            <div class="right_views">
                                <?php if($questionData['quesDetails']['viewCount']) { ?>
                                    <span><?php echo $questionData['quesDetails']['viewCount'].' '.$viewText; ?></span>
                                <?php } ?>

                                <?php if($questionData['quesDetails']['postedDate']) { ?>
                                    <span>Posted <?php echo $questionData['quesDetails']['postedDate']; ?></span>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <div class="left-cl">
                            <ul class="nav-discussion">
                                <li class="nav-item">
                                    <a class="nav-lnk qSLayer" currentTab="<?php echo $currentTab; ?>" data-threadid="<?php echo $questionId ?>" data-shareurl="<?php echo $questionData['quesDetails']['url']; ?>" data-param="question" href="javascript:void(0);"><i class="share"></i></a>
                                </li>
                                
                                <li class="nav-item">
                                    <?php if($questionData['follow']) { ?>
                                        <a class="ana-btns un-btn unques-un-btn" curclass="un-btn" reverseclass="f-btn" callforaction="unfollow" href="javascript:void(0);" ques_id="<?php echo $questionId ?>">Unfollow</a>
                                    <?php }
                                    else { ?>
                                        <a class="ana-btns f-btn unques-f-btn" curclass="f-btn" reverseclass="un-btn" callforaction="follow" href="javascript:void(0);" ques_id="<?php echo $questionId ?>">Follow</a>
                                    <?php } ?>
                                </li>
                                
                                <?php if($userId != $questionData['quesDetails']['userId'] && $questionData['quesDetails']['status'] != 'closed') { ?>                                
                                    <li class="nav-item">
                                        <a class="ana-btns a-btn" data-threadId="<?php echo $questionId ?>" id="postAnswer_<?php echo $questionId ?>">Answer</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <?php $this->load->view('search/searchV3/QSRP/answerPostingBlock', array('questionId' => $questionId, 'trackingKeyId' => $trackingKeyIds['unWriteAnswer'])); ?>
                </div>
            </div>
        <?php } ?>
        <!--end of cards-->
    </div>
</div>
