<div  id="answercomment_<?php echo $startCount;?>">
    <?php
    $counter = 0;
    foreach($detailPageData['answer'] as $key=>$value){ 
    $remainingCommentCount = count($value['comment'])-1;
    ?>
     <input type="hidden" id="remainingCommentCount_<?=$key?>" value="<?php echo $remainingCommentCount;?>" />
    <section class="content-wrap2 clearfix">
        <?php if($callType=='NONAJAX' && $counter==0){ ?>
        <h2 class="ans-title">Answers <span>(<?php echo $totalAnswerCount;?>)</span></h2>
        <?php } ?>
        <article class="req-bro-box clearfix">
            <div class="ans-icon">A</div>
            <div class="camp-qna-details">
                <?php
                $answerText =  $value['msgTxt'];
                $answerText = html_entity_decode(html_entity_decode($answerText,ENT_NOQUOTES,'UTF-8'));
                $answerText = formatQNAforQuestionDetailPage($answerText,300);
                $showShortAnswer = false;
                $answerTextShortened = '';
                
                $htmlTagsRemovedAnswerText = strip_tags($answerText);
                if(strlen($htmlTagsRemovedAnswerText)>120){
                        $showShortAnswer = true;
                        $answerTextShortened = substr($htmlTagsRemovedAnswerText,0,120);
                }
                ?>
                <?php if($showShortAnswer){ ?>
                    <p id="shortened<?=$value['msgId']?>"><?php echo $answerTextShortened;?><a id="expandLink<?=$value['msgId']?>" href='javascript:void(0)' onClick='expandAnswer("<?=$value['msgId']?>");'>...Read More</a></p>
                    <p id="expanded<?=$value['msgId']?>" style="display: none;"><?php echo $answerText;?></p>
                <?php }else{ ?> 
                    <p><?php echo $answerText;?></p>
                <?php } ?>
            <p class="posted-info">
                <span><label>Posted by:</label> <?php echo $value['postedByUser'];?>
                <?php if(array_key_exists($value['userId'],$formattedCAData)){ ?>
                <a href="javascript:void(0);" class="current-stu-btn" style="cursor: default;"><?php echo $formattedCAData[$value['userId']];?></a></span>
                <?php } ?>
                <span><?php echo $value['postedAt'];?> </span>
            </p>
            <div class="clearfix"></div>
                <?php
                $commentCounter = 0;
                if(count($value['comment'])>1 && $commentCounter==0){ ?>
                <p style="margin: 12px 0 0 0" id="moreCommentLink_<?php echo $key;?>"><a href="javascript:void(0);" onclick="showHideComment('<?php echo $key;?>');">View <span id="remainCountDigit_<?php echo $key;?>"><?php echo $remainingCommentCount; ?></span> more comment<?php echo ($remainingCommentCount>1)?'s':'';?></p></a>
                <?php } ?>
            <?php foreach($value['comment'] as $k=>$v){
                ?>
            <div class="posted-ans-detail hideComment_<?php echo $key;?>" <?php if($commentCounter!=$remainingCommentCount){ echo 'style="display:none;"';}?>>
           
                <?php
                $commentText =  $v['msgTxt'];
                $commentText = html_entity_decode(html_entity_decode($commentText,ENT_NOQUOTES,'UTF-8'));
                $commentText = formatQNAforQuestionDetailPage($commentText,300);
                $showShortComment = false;
                $commentTextShortened = '';
                
                $htmlTagsRemovedCommentText = strip_tags($commentText);
                if(strlen($htmlTagsRemovedCommentText)>120){
                        $showShortComment = true;
                        $commentTextShortened = substr($htmlTagsRemovedCommentText,0,120);
                }
                ?>
                <?php if($showShortComment){ ?>
                    <p id="shortened<?=$v['msgId']?>"><?php echo $commentTextShortened;?><a id="expandLink<?=$v['msgId']?>" href='javascript:void(0)' onClick='expandAnswer("<?=$v['msgId']?>");'>...Read More</a></p>
                    <p id="expanded<?=$v['msgId']?>" style="display: none;"><?php echo $commentText;?></p>
                <?php }else{ ?> 
                    <p><?php echo $commentText;?></p>
                <?php } ?>
                <p class="posted-info clearfix">
                <span><label>Posted by:</label> <?php echo $v['postedByUser'];?></span>
                <span><?php echo $v['postedAt'];?> </span>
                </p>
            </div>
            <?php $commentCounter++; } ?>
            
            <p style="background:#fefbcd; padding:2px 5px; width:auto; font-size:12px;display:none;" id="success<?php echo $value['msgId'];?>_msg"></p>
            
            <?php if(!$doNoShowAnswerForm){ ?><a class="button blue small flLt _rply_btn" id="_rply_btn<?php echo $value['msgId'];?>" href="javascript:void(0);"   onclick="showReplyCommentBox(this,'Comment','<?php echo $value['msgId'];?>');"><span>Comment</span></a><?php } ?>
            <?php
            $data['viewType'] = 'comment';
            $data['buttonName'] = 'Comment';
            $data['msgId']    = $value['msgId'];
            $data['threadId'] = $questionId;
            $data['pageName'] = 'QuestionDetailPage';
            $data['trackingPageKeyId']=$ctrackingPageKeyId;
            $this->load->view('mAnA5/campusRep/commentReplyPage',$data);?>
            </div>
       </article>
   </section>
    <?php $counter++;} ?>
</div>
<script>
    function showHideComment(id) {
        $('.hideComment_'+id).show();
        $('#moreCommentLink_'+id).hide();
    }
</script>
