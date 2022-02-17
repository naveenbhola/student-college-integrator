<div id="tags-head-post-q" class="tags-head" style="display:none;"><span>Ask your Question</span> <a id="cls-close-first-layer" class="cls-cross" href="javascript:void(0);"></a></div>
 <?php $this->load->view('messageBoard/desktopNew/widgets/qPostingFormOne'); ?>

 <?php
 if($pageType == 'discussion' && isset($data['userDetails']['levelId']) && intval($data['userDetails']['levelId']) >= 11)
 {
}else {
}
?>

 <div id="qsn-prvw" class="post-col">
            <div class="opacticy-col"><img border="0" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" id="loadingImageNew" alt="" class="small-loader"></div>
        <div class="post-qstn">
            <h3 class="post-h2 qdTitle-l2">Your  Question</h3>
               <p class="qstn-title" id="qstn-title-posting"><span></span><a href="javascript:void(0);" class="edit-qstn" id="edit-qstn" tabindex=6>Edit</a></p>
        </div>

         <div class="more-qstns">
            <div id="slctd-tags" class="tags-slctd">
                
            </div>
                   
            <div class="btns-col">
              <span class="tag-note">Add relevant tags to get quick responses.</span>
                <span class="right-box">
                    <a class="exit-btn" href="javascript:void(0);" id="cancelButtonSecondLayer" tabindex=8>Cancel</a>
                    <a class="prime-btn" href="javascript:void(0);" id="finalButtonPosting" onclick="gaTrackForQuesDiscPost('Post')" tabindex=9>Post</a>
                </span>
                <p class="clr"></p>
            </div>
            <div id="similar_ques_outer" class="similar_ques_outer">
                <br /><br /><br />
                <img border="0" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" id="loadingImageNew" alt="" class="small-loader">    
                
            </div>

</div>
        <input type="hidden" id="tracking_keyid_ques" value="<?php echo $qtrackingPageKeyId;?>">
        <input type="hidden" id="tracking_keyid_disc" value="<?php echo $dtrackingPageKeyId;?>">
        <input type="hidden" id="entityId" value="<?php echo $entityId;?>">
        <input type="hidden" id="tagEntityType" value="<?php echo $tagEntityType;?>">
        <input type="hidden" id="quesDiscKeyId" value="">
</div>
<div id="addMoreTagsLayer">
        
</div>
<script>
    var GA_userLevel = '<?php echo $GA_userLevel;?>';
</script>
