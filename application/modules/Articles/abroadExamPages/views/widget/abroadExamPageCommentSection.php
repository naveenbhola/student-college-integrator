<script>	var spamKeywordsList = JSON.parse('<?php echo $spamKeywordsList;?>'); </script>
<div id = "commentSection" class = "clearwidth">
    <?php
        // user input form if user is not logged in/ has posted any comment
        $this->load->view('blogs/saContent/userAddForm');
    ?>
    <div  class="comment-section clearwidth">
        <div class="comment-title">
            <i class="abroad-exam-sprite comment-large-gray-icon"></i>
            <h2 style="font-size: 12px;"><?=($commentData['total']>0?$commentData['total'].' Comment'.($commentData['total']==1?'':'s'):'Post your comment')?></h2>
        </div>
        <p>Participate in discussion, write your comment</p>
        <ul>
            <li>
                <input type = "hidden" class="parentId" value = "0">
                <textarea style="font-family:'open sans';" value="Add your comment" default="Add your comment" validate="validateStr" caption="comment" maxlength="300" minlength="3" required="true" onfocus="if($j(this).val() == 'Add your comment') { $j(this).val('');}" onblur=" if($j(this).val() == '') {$j(this).val('Add your comment');}" id="commentBox" name="textarea" class="comment-area">Add your comment</textarea>
                <div id="commentBox_error" class="errorMsg" style="display:none;margin-top: 3px; display: inline;"></div>
            </li>
            <li style="text-align:right"><a href="Javascript:void(0);" onclick = "submitComment(this);studyAbroadTrackEventByGA('ABROAD_EXAM_PAGE', 'submitYourComment');" class="button-style" style="padding:6px 25px; margin-left:8px; font-weight:bold">Submit</a></li>
        </ul>
    </div>
    <?php $this->load->view('abroadExamPages/widget/abroadExamPageComments'); ?>
</div>
<?php if($commentData['total'] > 50){ ?>
    <div class="clearwidth">
        <a href="javascript:void(0)" onclick="loadMoreComments();" class="load-more clear-width" id="load_more">Show more comments</a>
        <span class="load-more clear-width" id="load_more_span" style="display:none;">No more comments to show</span >
    </div>
<?php } ?>
<?php 
    if(is_object($examPageObj)){
        $contentId = $examPageObj->getExamPageId();
    }else if(!empty($contentDetails)){
        $contentId = $contentDetails['content_id'];
    }else{
        $contentId = 0;
    }
    if(empty($authorId)){
        $authorId = $contentDetails['created_by'];
    }
?>
<script>
    var submitCommentElement;
    var contentId = parseInt('<?php echo $contentId; ?>');
    var sectionId = parseInt('<?php echo empty($sectionData['sectionId'])?'0':$sectionData['sectionId']; ?>');
    var authorId  = '<?php echo $authorId ?>';
    var commentPageNum = 0;
    var totalComments = parseInt('<?php echo $commentData['total']; ?>');
</script>
