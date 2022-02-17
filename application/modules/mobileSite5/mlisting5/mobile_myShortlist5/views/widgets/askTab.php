<?php
    if($campusRepExists != 'true')
    {
        echo 'No CampusRep Exists';
        return;
    }
?>
<article class="lising-nav-details">
    <p class="title" style="margin-bottom:0px;">Ask your question to current student</p>
    <?php echo Modules::run('mAnA5/AnAController/postQuestionFromMyShortlist',$instituteId,$courseId,$qtrackingPageKeyId); ?>

    <?php
    if(!empty($total))
    {?>
    <div class="prev-q"><strong>Previously asked question<?php echo ($total>1 ? "s" : "");?> (<?php echo $total?$total:0;?>)</strong><br></div>
    
    <ol id="anaQuesList" class="ques-display">
        <?php $this->load->view("mobile_myShortlist5/widgets/askTabDetails");?>
    </ol>
    <div class="load-more-block" id="load_more">
    <?php
        if($total > 3)
        {
    ?>
        <a href="#" class="btn-load-more" style="color:#000;" onclick="return getAskTabData(<?php echo $courseId.",".$instituteId ?>);">Load more questions ...</a>
    <?php
        }
    ?>
    </div>
    <?php
    }?>
</article>