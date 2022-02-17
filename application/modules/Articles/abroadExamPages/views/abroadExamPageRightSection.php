<?php
$showDownloadSectionFlag = false;
if($sectionData['sectionName'] != 'exam-articles' && $sectionData['sectionName'] != 'colleges'){
$showDownloadSectionFlag = true;
}

?>
<div class="exam-right-col">
    <?php if($sectionData['sectionName'] == 'exam-articles'){ ?>
    <?php       $this->load->view("abroadExamPageRelatedArticles"); ?>
    <?php } else if($sectionData['sectionName'] == 'colleges'){ ?>
    <?php       $this->load->view("abroadExamPageAcceptingColleges"); ?>
    <?php } else { ?>
    <div id="examPageHeadingTitle"  class="exam-heading clearwidth" style="margin-top: 0px;">
        <h2 class="flLt"><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[$sectionData['sectionId']]['title']);?></h2>
         <div class="flRt" style="margin-bottom:5px;">
            <?php if($examPageObj->getDownloadLink()){ ?>
            <!--Download Guide Top Button-->
            <a style="margin-right:18px; vertical-align:middle" href="javascript:void(0);" class="button-style dwnld-pdf" onclick="directDownloadORShowOneStepLayer('<?=base64_encode($examPageObj->getDownloadLink())?>)','<?=$examPageObj->getExamPageId()?>','<?=$loggedInUserData['isLDBUser']?>',29);">
                <i class="abroad-exam-sprite pdf-icon"></i>
                <span class="font-12" style="font-weight:bold">Download Exam Guide</span>
            </a>
            <?php } ?>
            <a href="#commentSection" class="flRt" style="position:relative; top:6px; right:10px"><i class="abroad-exam-sprite comment-large-icon"></i><?=($commentData['total']>0?$commentData['total'].' Comment'.($commentData['total']==1?'':'s'):'Post your comment')?></a>
         </div>   
    </div>

    <?php $count =0;
        foreach($subSectionDetails as $secData){
        ?>    
        <div class="exam-content clearwidth dyanamic-content">
            <h3 class="exam-sub-heading" id="section<?= $count;?>"><?= ($count==0)?'':html_escape($secData['heading']);?></h3>
            <p><?= ($secData['details'])?></p>
        </div>
    <?php $count++;}?>
    <?php }// end else ?>
<!--Bottom Navigation Tiles-->
<?php $this->load->view('abroadExamPageTileNavigationBottom');?>

<!--Bottom DownloadGUide Button-->
<?php $this->load->view('widget/abroadExamPageDownloadGuideBottom');?>

<!-- comments area starts -->
<?php if($showDownloadSectionFlag){?>
    <?php $this->load->view('widget/abroadExamPageCommentSection'); ?>        
<?php } ?>
<!-- comments area ENDS -->
</div>