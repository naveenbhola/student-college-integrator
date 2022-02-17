<script> var spamKeywordsList =JSON.parse('<?php echo $spamKeywordsList;?>');</script>
<article class="<?php if($contentType!='examPage'){ echo "content-inner2";}?>">
<div class="<?php if($contentType!='examPage'){ echo "article-guide-content";}?>">
        <div id="commentAreaBox" class="comment-section">
               <?php if($contentType=='examPage'){?>
               <div class="clearfix">
                       <?php $this->load->view('widgets/socialLinks'); ?> </div><?php  } ?>
                <strong class="article-info-title" style="margin-bottom:5px;"><?php  echo($comments['total']==1)?$comments['total']." Comment":$comments['total']." Comments"; ?></strong>
                <p>Participate in discussion, write your comment.</p>
                <textarea class="universal-txt article-textarea"></textarea>
                <div class="textErrorBox error-msg" style="float:none !important;"></div>
                <a href="javascript:void(0);" onclick="submitComment(this,0,<?=$content['data']['content_id']?>);" class="btn btn-primary btn-full mb15" style="background:#999; width:50%;">Submit</a>
    </div>
        <?php if($comments['total'] > 0){ ?>
        <div class="user-comment-detail" id="commentSection">
                <ul><?php $this->load->view('widgets/commentBlock'); ?></ul>	
        </div>
        <?php } ?>
        <?php if($comments['total'] > 5){ ?>
        <div class="less-more-sec">
                <a href="javascript:void(0)" onclick="showMoreComments(this,<?=$content['data']['content_id']?>);">+ View More</a>
        </div>
        <?php } 
            $showCommentMsg = $this->input->cookie('savedCommentMsg');
        ?>
</div>
</article>
<div style="display:none"><a href="#contentRegistration" id="contentUserRegistrationLink" data-rel="dialog" data-transition="slide">-</a></div>
<script>
        var commentPaginationNumber = 1;
        // There needs to be some logic for setting these for proper logged in users and content only users from cookie.
        userLoggedIn = <?php global $validateuser; echo isset($validateuser[0]['userid'])?1:0; ?>;
        if (userLoggedIn) {
                var contentUserName = '<?=($validateuser != "false")?addslashes($validateuser[0]['firstname'].' '.$validateuser[0]['lastname']):''?>';
                var contentUserEmail = '<?=($validateuser != "false")?reset(explode("|",$validateuser[0]['cookiestr'])):''?>';
                var contentUserId = '<?=($validateuser != "false")?$validateuser[0]['userid']:''?>';
        }else{
                var contentUserName = '<?php echo $this->input->cookie("sacontent_userName")?>';
                var contentUserEmail = '<?php echo $this->input->cookie("sacontent_userEmail")?>';
                var contentUserId = '0';
        }
        <?php if($showCommentMsg){ ?>
            var showCommentMsg = true;
        <?php } ?>
</script>
