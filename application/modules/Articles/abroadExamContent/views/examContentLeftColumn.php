<div class="sop-left-col newExam-content" itemscope itemtype="http://schema.org/Article">
    <div class="exmContentWrap clearfix">
    <h2><?php echo htmlentities($contentDetails['strip_title']);?></h2>
    <?php
    $this->load->view('widget/examContent');
    $this->load->view('abroadExamContent/widget/downloadGuideInlineWidget');
    $this->load->view('studyAbroadArticleWidget/studyAbroadListingUnivesityWidget');
    $this->load->view('widget/author');
    //$this->load->view('widget/alsoLikeWidget');           // Move where needed
    ?>
    </div>
    <div class="commentSectionWrap clearfix">
    <?php 
    $this->load->view('abroadExamPages/widget/abroadExamPageCommentSection');
    ?>
    </div>
</div>