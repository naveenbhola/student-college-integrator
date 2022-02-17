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
            <div class="srp_card">
                <a class="srp_title" href="<?php echo $questionData['ques']['url']; ?>"><?php echo $questionData['ques']['quesTxt']; ?></a>
                
                <div class="usr_block">
                    <a href="<?php echo '/userprofile/'.$questionData['answer']['userData']['userId'] ?>">
                        <?php if(empty($questionData['answer']['userData']['picUrl'])) { ?>
                            <div class="usr_initial"><?php echo $questionData['answer']['userData']['initialLetter']; ?></div>
                        <?php } else { ?>
                            <div class="usr_initial">
                                <img src="<?php echo getSmallImage($questionData['answer']['userData']['picUrl']); ?>">
                            </div>
                        <?php } ?>

                        <div class="info_cols">
                            <span>Answered by</span> 
                                <?php echo $questionData['answer']['userData']['firstname'].' '.$questionData['answer']['userData']['lastname']; if(!empty($questionData['answer']['userData']['aboutMe']) || !empty($questionData['answer']['userData']['levelName'])) { ?>,<?php } ?>
                            <br>

                            <?php if(!empty($questionData['answer']['userData']['aboutMe'])) { ?>
                                <?php echo $questionData['answer']['userData']['aboutMe'] ?>, <br>
                            <?php } ?>

                            <?php if(!empty($questionData['answer']['userData']['levelName'])) { ?>
                                <?php echo $questionData['answer']['userData']['levelName'] ?>
                            <?php } ?>
                        </div>
                    </a>
                </div>
                <p class="srp_dtl"><?php echo $questionData['answer']['ansTxt'] ?> 
                    <?php if($questionData['answer']['isLongAns']) { ?>
                        <a href="<?php echo $questionData['ques']['url'].'?viewMore='.$questionData['answer']['msgId']; ?>">View more</a>
                    <?php } ?>
                </p>
                
                <?php if($questionData['ques']['answerCount'] > 1) { ?>
                    <div class="align_cntr all_qnas">
                        <a class="all_qnas-btn" href="<?php echo $questionData['ques']['url']; ?>">View All <?php echo $questionData['ques']['answerCount']; ?> Answers <i><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 50 80" xml:space="preserve">
                            <polyline fill="none" stroke="#00a5b5" stroke-width="9" stroke-linecap="round" stroke-linejoin="round" points="0.375,0.375 45.63,38.087 0.375,75.8 "></polyline>
                        </svg></i></a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>
