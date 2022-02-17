<?php
foreach($desiredCourses as $groupName => $courses) {
    if($groupName) {
        echo "<optgroup label='".$groupName."'>";
    }
    foreach($courses as $courseId => $courseName) {
        $selected = '';
        if((!empty($selectedDesiredCourse)) && ($selectedDesiredCourse == $courseId)) {
            $selected = "selected='selected'";
        }
        echo "<option ".$selected." value='".$courseId."'>".$courseName."</option>";
    }
    if($groupName) {
        echo "</optgroup>";
    }
}