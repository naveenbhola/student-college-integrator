<?php 
$firstFive = array_slice($applicableCountries, 0, 5);
$others    = array_slice($applicableCountries, 5);

if($scholarshipObj->getScholarshipDescription() != ''){
?>
<h2 class="titl-main">Scholarship Overview</h2>
<div class="sch-bx2">
<?php echo $scholarshipObj->getScholarshipDescription()?>
</div>
<?php 
}
?>

<h2 class="titl-main">Applicable university, country and course</h2>
<div class="sch-bx2">
<?php 
if($scholarshipObj->getCategory() == 'external'){
?>
    <p>This scholarship is applicable for all universities in <?php echo implode(', ', $firstFive); 
        if(!empty($others)){
            echo '<a href="javascript:;" id="viewAll">... +'.count($others).' more</a><span id="allCountries" style="display:none;">, '.implode(', ', $others).'</span>';
        } ?></p>
    <p>This scholarship is applicable for 
    <?php
    if(!empty($courseLevels)){
        $courseLevels = appendDegreeToLevel($courseLevels);
        sort($courseLevels);
        $showCourseLevels = implode(', ', $courseLevels);
    }else{
        $showCourseLevels = array_unique($scholarshipObj->getHierarchy()->getCourseLevel());
        $showCourseLevels = appendDegreeToLevel($showCourseLevels);
        sort($showCourseLevels);
        $showCourseLevels = implode(', ', $showCourseLevels);
    } 
        echo $showCourseLevels;
    ?>
    </p>
<?php 
}else{
?>
    <?php if(!empty($courseLevels)){
                $showCourseLevels = array();
                $showCourseLevels = appendDegreeToLevel($courseLevels);
                sort($showCourseLevels);
                $showCourseLevels = implode(', ', $showCourseLevels);
            }else if(!empty($courseLevel)){
                $showCourseLevels = appendDegreeToLevel($courseLevel);
                $showCourseLevels = implode(', ', $showCourseLevels);
            }else{
                $showCourseLevels = appendDegreeToLevel(array_unique($scholarshipObj->getHierarchy()->getCourseLevel()));
                $showCourseLevels = implode(', ', $showCourseLevels);
            }  ?>

    <p><?php echo 'This scholarship is applicable for '.$showCourseLevels; ?></p>
    <p>This scholarship is applicable for the following courses in <a href="<?php echo $universityUrl?>"><?php echo $universityName?> in <?php echo implode(',', $applicableCountries)?></a></p>
    <ul>
    <?php 
    foreach ($internalCourses as $courseObj) {
        echo '<li><a href="'.$courseObj->getUrl().'">'.$courseObj->getName().'</a></li>';
    }
    ?>
    </ul>
<?php 
}
echo $scholarshipObj->getHierarchy()->getCourseCategoryComments();
?>
</div>