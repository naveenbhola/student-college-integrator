<?php if($examPageObj->getDownloadLink()){ ?>
<div class="download-widget clearwidth">
    <i class="listing-sprite pdf-help-arrow"></i>
    <i class="shadow shadow-top"></i>
    <p style="font-size:24px;">Download this guide to read it offline</p>
    <a style="margin-bottom:5px;" href="javascript:void(0);" class="button-style dwnld-pdf" onclick="directDownloadORShowOneStepLayer('<?=base64_encode($examPageObj->getDownloadLink())?>)','<?=$examPageObj->getExamPageId()?>','<?=$loggedInUserData['isLDBUser']?>',30);">
        <i class="abroad-exam-sprite pdf-icon"></i>
        <span class="font-14">Download Exam Guide</span>
    </a>
    <div class="font-12" id="guideDownloadCountLoc" style="<?php if(!is_numeric($guideDownloadCount) || $guideDownloadCount < 50){ ?>display:none;<?php } ?>"><span id="guideDownloadCount"><?=$guideDownloadCount?></span> People downloaded this</div>
    <i class="shadow shadow-bottom"></i>    
</div>
<?php } ?>