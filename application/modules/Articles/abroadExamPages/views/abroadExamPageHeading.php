<h1 class="page-title"><?= html_escape($examPageObj->getExamPageTitle()); ?></h1>
<?php if($sectionData['sectionId']==1){?>
<div class="about-exam dyanamic-content">   
    <p><?= ($examPageObj->getExamPageDescription()); ?></p>
</div>
<?php } ?>