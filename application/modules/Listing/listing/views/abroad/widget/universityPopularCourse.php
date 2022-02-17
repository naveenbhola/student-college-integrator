<?php

?>
<div id="coursesInUniversityDiv" class="updated-pop-courses clearwidth">
    <?php
        $this->load->view("listing/abroad/widget/universityPopularCourses");
        $this->load->view("listing/abroad/widget/universityFindCourseFormWidget");
    ?>
    <div id="findCourseResult">
    <?php $this->load->view("listing/abroad/widget/universityFindCourseResultWidget"); ?>
    </div>
</div>