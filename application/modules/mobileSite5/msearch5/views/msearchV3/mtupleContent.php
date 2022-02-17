<div id="instituteContainer_<?php echo $institute->getId(); ?>">
    <?php 
    if($product != "MAllCoursesPage"){
        $this->load->view('msearch5/msearchV3/mtupleTop');
    }

    ?>
    <div class="clg-detail-head">
        <?php 
        $this->load->view('msearch5/msearchV3/mtupleBottom'); ?>    
    </div>
    <p class="clr"></p>
</div>
<?php 
    //$related = ' related';
    $remainingCourseCount = count($institutes['instituteLoadMoreCourses'][$institute->getId()]);
    if($remainingCourseCount > 0){
        $courseIds = implode(',', $institutes['instituteLoadMoreCourses'][$institute->getId()]); 
        if($remainingCourseCount > 1) {
            $text = '+ '.$remainingCourseCount.' more courses';
        } else {
            $text = '+ '.$remainingCourseCount.' more course';
        }
        ?>
        <div id='showMore_<?php echo $institute->getId(); ?>' pagenum='<?php echo $pageNumber; ?>' class="clg-detail-card" instid='<?php echo $institute->getId(); ?>'>
            <a href="javascript:void(0)" class="course-link"><?php echo $text; ?></a>
        </div>
        <input type="hidden" autocomplete="off" id="remainingCourseIds_<?php echo $institute->getId(); ?>" value="<?php echo $courseIds; ?>"  loadedCourseCount = <?php echo 0; ?>>
        <?php
    }
?>