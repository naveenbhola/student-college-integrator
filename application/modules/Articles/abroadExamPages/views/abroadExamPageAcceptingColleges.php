<?php
    $flagIconClasses = array(
        3=>'usa',
        4=>'uk',
        5=>'australia',
        6=>'singapore',
        7=>'newzealand',
        8=>'canada',
        9=>'germany'
    );
?>

<div id="examPageHeadingTitle" class="exam-heading clearwidth" style="border:0; margin-bottom:10px">
    <strong class="flLt" style="margin-bottom:6px; display:block; font-size:18px;">Colleges accepting <?=$examPageObj->getExamName()?></strong>
    <div class="flRt" style="margin-bottom:5px;">
                <?php if($examPageObj->getDownloadLink()){ ?>
                <!--Download Guide Top Button-->
                <a style="margin-right:18px; vertical-align:middle" href="javascript:void(0);" class="button-style dwnld-pdf" onclick="directDownloadORShowOneStepLayer('<?=base64_encode($examPageObj->getDownloadLink())?>)','<?=$examPageObj->getExamPageId()?>','<?=$loggedInUserData['isLDBUser']?>');">
                    <i class="abroad-exam-sprite pdf-icon"></i>
                    <span class="font-12" style="font-weight:bold">Download Exam Guide</span>
                </a>
                <?php } ?>
    </div>
    <p class="flLt">Use the table below to explore colleges for a specific country and course combination</p>
</div>

<div class="college-table-wrap clearwidth" style="margin-bottom:15px">
<!-- Removed Table from here as no longer used. if needed please use git commit log-->

</div>