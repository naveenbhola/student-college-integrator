<?php foreach($comments['data'] as $comment){ 
        $linkOpen = '<a href="'.$comments['userData'][$comment['userId']]['profilePageUrl'].'">';
        $linkClose = '</a>';
        $admittedStr = $comments['userData'][$comment['userId']]['admittedStr'];
        if($comment['userId']>0){
                $imgUrl = $comments['userData'][$comment['userId']]['imageUrl'];
        }else{
                $imgUrl = IMGURL_SECURE.'/public/images/studyAbroadCounsellorPage/profileDefaultNew1_s.jpg';
        }
?>
    <li>
            <div class="user-title">
                    <div class="ic_wrap"><p class="inner-wrap" data-enhance=false>
                        <?php if($comment['userId']>0){
                                echo $linkOpen;
                        } ?>
                        <img src="<?php echo getImageUrlBySize($imgUrl,'small'); ?>">
                        <?php if($comment['userId']>0){
                                echo $linkClose;
                        } ?>
                    </p></div>
                    <div class="new_text">
                        <section>
                        <strong class="flLt">
                        <?php $userName = '';
                                if($comment['userId']>0){
                                        echo $linkOpen;
                                        $userName = $comments['userData'][$comment['userId']]['userName'];
                                }
                                $userName = ($userName != ''?$userName:$comment['userName']);
                                echo $userName;
                                if($comment['userId']>0){
                                        echo $linkClose;
                                }
                        ?>
                        </strong>
                        
                        <div class="clearfix"></div>
                        </section>
                        <section><?php echo $admittedStr; ?></section>
                     </div>
                    <div class="clearfix"></div>
            </div>
            <p class="commentText"><?php echo (nl2br(htmlentities($comment['commentText']))); ?></p>
                <span class="date-info" style="display:block;text-align:right">
                        <?php //_p($comment); ?>
                                <?php echo date("d M'y",strtotime($comment['commentTime']));?>, <?php echo date("h:i A",strtotime($comment['commentTime']));?>
                        </span>
            <a href="javascript:void(0);" onclick="showReplyBox(this);" class="reply-link">Reply to <?=$userName?></a>
            <div class="replyBox" style="display:none">
                <textarea class="universal-txt article-textarea"></textarea>
                <div class="textErrorBox error-msg" style="float:none !important;"></div>
                <a href="javascript:void(0);" onclick="submitComment(this,<?=$comment['commentId']?>,<?=$comment['contentId']?>);" class="btn btn-primary btn-full mb15">Submit</a>
                <div style="display: block; text-align: center;"><a href="javascript:void(0);" onclick="hideReplyBox(this);" style="display: inline-block; text-align: center; padding: 16px 0 0 0;">Cancel</a></div>
            </div>
            <ul class="reply-list">
            <?php if(!empty($comment['replies'])){ ?>
                    
                            <?php
                            $i = 0;
                            $hidden = "";
                            foreach($comment['replies'] as $reply){
                                $linkOpen = '<a href="'.$comments['userData'][$reply['userId']]['profilePageUrl'].'">';
                                $linkClose = '</a>';
                                $admittedStr = $comments['userData'][$reply['userId']]['admittedStr'];
                                if($reply['userId']>0){
                                        $imgUrl = $comments['userData'][$reply['userId']]['imageUrl'];
                                }else{
                                        $imgUrl = IMGURL_SECURE.'/public/images/studyAbroadCounsellorPage/profileDefaultNew1_s.jpg';
                                }
                                $i++;
                                if($i >5){
                                        $hidden = "display:none;";
                                }
                            ?>
                                    <li style="<?=$hidden?>">
                                            <div class="user-title">
                                            <i class="sprite reply-bck-icon"></i>
                                            <div class="ic_wrap">
                                               <p class="inner-wrap">
                                                <?php if($reply['userId']>0){
                                                        echo $linkOpen;
                                                } ?>
                                                <img src="<?php echo getImageUrlBySize($imgUrl,'small'); ?>">
                                                <?php if($reply['userId']>0){
                                                        echo $linkClose;
                                                } ?>
                                               </p>
                                            </div>
                                                <div class="new_text">
                                                <section>
                                                    <strong>
                                                            
                                                            <?php $userName='';
                                                                if($reply['userId']>0){
                                                                        echo $linkOpen;
                                                                        $userName = $comments['userData'][$reply['userId']]['userName'];
                                                                }
                                                                $userName = ($userName != ''?$userName:$reply['userName']);
                                                                echo $userName;
                                                                if($reply['userId']>0){
                                                                        echo $linkClose;
                                                                }
                                                            ?>
                                                    </strong>
                                                    
                                                    <div class="clearfix"></div>
                                                </section>
                                                <section><?php echo $admittedStr; ?></section>
                                                </div>
                                            </div>
                                            <div class="user-reply-info">
                                                    <p class="commentText"><?php echo nl2br(htmlentities($reply['commentText']))?></p>
                                                    <span class="date-info" style="display:block;text-align:right">
                                                            <?php echo date("d M'y",strtotime($reply['commentTime']));?>, <?php echo date("h:i A",strtotime($reply['commentTime']));?>
                                                    </span>
                                            </div>   
                                    </li>
                            <?php } ?>
                    
                    
            <?php } ?>
            </ul>
            <?php if(count($comment['replies']) >5) { ?>
                <div style="padding-top: 8px; padding-bottom: 8px;text-align: center;">
                    <a href="javascript:void(0);" onclick="showAllReplies(this);">View all <?=count($comment['replies'])?> replies</a>
                </div>
            <?php } ?>
    </li>
<?php } ?>