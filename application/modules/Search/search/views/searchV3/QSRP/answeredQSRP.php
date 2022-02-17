<!--right panel starts-->
<div class="fit_right">
    <?php if($tupleData['resultCount'] > 1) {
        $resultText = 'results';
    } else {
        $resultText = 'result';
    } ?>
    <h1 class="rslt_h1">Showing <?php echo $tupleData['resultCount'].' '.$resultText; ?> for <strong>"<?php echo $keyword; ?>"</strong> </h1>
    <div class="all_rslts anq">
        <input type="hidden" id="currentTab" value="<?php echo $currentTab; ?>">
        <?php foreach ($tupleData['result'] as $questionId => $questionData) { ?>
            <div class="data-list clear_max">
                <div class="qstns">
                    <a href="<?php echo $questionData['ques']['url']; ?>"><?php echo $questionData['ques']['quesTxt']; ?></a>
                </div>
                
                <div class="tabs_flow clear_max">
                    <?php if($questionData['ques']['viewCount'] > 1 || strpos($questionData['ques']['viewCount'], 'k') > 0) {
                        $viewText = 'Views';
                    } else {
                        $viewText = 'View';
                    } ?>
                    <div class="right_views"> 
                        <span><?php echo $questionData['ques']['viewCount'].' '.$viewText; ?></span>
                        <span>Posted <?php echo $questionData['ques']['creationDate']; ?></span>
                    </div>
                    
                    <div class="left-cl">
                        <ul class="nav-discussion">
                            <li class="nav-item">
                                <a class="nav-lnk qSLayer" currentTab="<?php echo $currentTab; ?>" data-threadid="<?php echo $questionId ?>" data-shareurl="<?php echo $questionData['ques']['url']; ?>" data-param="question" href="javascript:void(0);"><i class="share"></i></a>
                            </li>
                            
                            <li class="nav-item">
                                <?php if($questionData['follow']) { ?>
                                    <a class="ana-btns un-btn ques-un-btn" curclass="un-btn" reverseclass="f-btn" callforaction="unfollow" href="javascript:void(0);" ques_id="<?php echo $questionId ?>">Unfollow</a>
                                <?php }
                                else { ?>
                                    <a class="ana-btns f-btn ques-f-btn" curclass="f-btn" reverseclass="un-btn" callforaction="follow" href="javascript:void(0);" ques_id="<?php echo $questionId ?>">Follow</a>
                                <?php } ?>
                            </li>
                            
                            <?php if($userId != $questionData['ques']['userId'] && $questionData['ques']['status'] != 'closed') { ?>                                
                                <li class="nav-item">
                                    <a class="ana-btns a-btn" data-threadId="<?php echo $questionId ?>" id="postAnswer_<?php echo $questionId ?>">Answer</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <?php $this->load->view('search/searchV3/QSRP/answerPostingBlock', array('questionId' => $questionId, 'trackingKeyId' => $trackingKeyIds['aWriteAnswer'])); ?>
                
                <div class="srp_dtl">
                    <div class="user-cell clear_max">
                        <div class="user_diagram">
                            <a href="<?php echo SHIKSHA_HOME.'/userprofile/'.$questionData['answer']['userData']['userId'] ?>">
                                <?php if(empty($questionData['answer']['userData']['picUrl'])) { ?>
                                    <div class="usr_initial"><?php echo $questionData['answer']['userData']['initialLetter']; ?></div>
                                <?php } else { ?>
                                    <div class="usr_initial">
                                        <img alt="Shiksha Ask & Answer" src="<?php echo getSmallImage($questionData['answer']['userData']['picUrl']); ?>">
                                    </div>
                                <?php } ?>
                            </a>
                        </div>
                        
                        <div class="user_infs">
                            <p class="ans_by">Answered by</p>
                            <h2 class="ans_flwd">
                                <a class="avatar-name" href="<?php echo SHIKSHA_HOME.'/userprofile/'.$questionData['answer']['userData']['userId'] ?>"><?php echo $questionData['answer']['userData']['firstname'].' '.$questionData['answer']['userData']['lastname']; if(!empty($questionData['answer']['userData']['aboutMe']) || !empty($questionData['answer']['userData']['levelName'])) { ?>,<?php } ?></a>
                            </h2>

                            <?php if(!empty($questionData['answer']['userData']['aboutMe'])) { ?>
                                <p class="ans_flwd">
                                    <?php echo $questionData['answer']['userData']['aboutMe'] ?>, <br>
                                </p>
                            <?php } ?>

                            <?php if(!empty($questionData['answer']['userData']['levelName'])) { ?>
                                <p class="ans_flwd">
                                    <?php echo $questionData['answer']['userData']['levelName'] ?>
                                </p>
                            <?php } ?>
                        </div>
                    </div>
                    <p class="srp_dtl_p"> <?php echo $questionData['answer']['ansTxt'] ?>
                        <?php if($questionData['answer']['isLongAns']) { ?>
                            <a href="<?php echo $questionData['ques']['url'].'?viewMore='.$questionData['answer']['msgId']; ?>">View more</a>
                        <?php } ?>
                    </p>
                </div>
                
                <?php if($questionData['ques']['answerCount'] > 1) { ?>
                    <div class="gradient-col">
                        <a href="<?php echo $questionData['ques']['url']; ?>">View All <?php echo $questionData['ques']['answerCount']; ?> Answers</a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>