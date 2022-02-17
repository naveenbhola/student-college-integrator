<div id="instituteContainer_<?php echo $institute->getId(); ?>">
    <?php $this->load->view('msearch5/msearchV2/mtupleTop');?>
    <div class="clg-detail-head">
        <?php $this->load->view('msearch5/msearchV2/mtupleBottom'); ?>    
    </div>
    <p class="clr"></p>
</div>
<?php 
    $related = empty($subCatId) ? ' more':' related';
    $remainingCourseCount = count($institutes['instituteLoadMoreCourses'][$institute->getId()]);
    if($remainingCourseCount > 0){
        $courseIds = implode(',', $institutes['instituteLoadMoreCourses'][$institute->getId()]); 
        if($remainingCourseCount > 1) {
            $text = '+ '.$remainingCourseCount.$related.' courses';
        } else {
            $text = '+ '.$remainingCourseCount.$related.' course';
        }
        ?>
        <div id='showMore_<?php echo $institute->getId(); ?>' class="clg-detail-card" instid='<?php echo $institute->getId(); ?>' subcatid='<?php echo $subCatId; ?>'>
            <a href="javascript:void(0)" class="course-link"><?php echo $text; ?></a>
        </div>
        <input type="hidden" id="remainingCourseIds_<?php echo $institute->getId(); ?>" value="<?php echo $courseIds; ?>"  loadedCourseCount = <?php echo 0; ?>>
        <?php
    }
?>