<?php
// get value for visible in the popularcoursesdata array
$showQuickLinks = false;
foreach($popularCourseData as $key=>$value){
    if($value['universityCount']>0){
        $showQuickLinks = true;
        break;
    }
}
?>
<?php if($showQuickLinks){ ?>
<div class="quickLinks-list">
    <strong>Quick Links</strong>
    <ul>
        <?php foreach ($popularCourseData as $popularCourse) { ?>
            <?php if ((int)$popularCourse['universityCount'] > 0) { ?>
                <li>
                    <div class="quick-LinkInfo">
                        <a href="<?= $popularCourse['url'] ?>" ><?= $popularCourse['name'] ?> <?= ($countryName == "Abroad" ? "Abroad" : "in " . $countryName) ?></a><span><?= $popularCourse['universityCount'] ?> <?= ($popularCourse['universityCount'] == 1) ? "college" : "colleges" ?></span>
                        <i class="quick-link-trigger">&nbsp;</i>
                    </div>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>
<?php } ?>