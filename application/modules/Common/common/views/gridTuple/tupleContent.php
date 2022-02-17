
<div class="clg-tpl-parent">
    <div class="clg-tpl" id='instituteContainer_<?php echo $institute->getId(); ?>'>
        <!-- Tuple heading section -->
        <?php
            if($product != "AllCoursesPage"){
                $this->load->view('common/gridTuple/tupleTop',$displayData);    
            }
          ?>

        <!-- Tuple body section -->
        <section class="tpl-curse-dtls">
            <?php $this->load->view('common/gridTuple/tupleMiddle',$displayData); ?>
            <p class="clr"></p>
            <?php 
                // if($product == "Category" || $product == "ebochureCallback"){ 
                    $this->load->view('common/gridTuple/tupleBottom',$displayData); 
                // }else if($product == "SearchV2" || $product == "AllCoursesPage"){
                //     $this->load->view('common/gridTuple/tupleBottomSearch',$displayData);     
                // }
                
            ?>
        </section>
    </div>
    <p class="clr"></p>
    <?php 
        
        $related = ' More ';

        $remainingCourseCount = count($institutes['instituteLoadMoreCourses'][$institute->getId()]);
        if($remainingCourseCount > 0) {
            $courseIds = implode(',', $institutes['instituteLoadMoreCourses'][$institute->getId()]); 
            if($remainingCourseCount > 1) {
                $text = '+ '.$remainingCourseCount.$related.' courses';
            } else {
                $text = '+ '.$remainingCourseCount.$related.' course';
            } ?>

            <div class="outerframe" id='showMore_<?php echo $institute->getId(); ?>' instid='<?php echo $institute->getId(); ?>'>
                <i class="frme1"></i>
                <i class="frme2"></i>
                <a href='javascript:void(0);'><?php echo $text; ?></a>
            </div>
            
            <input type="hidden" id="remainingCourseIds_<?php echo $institute->getId(); ?>" value="<?php echo $courseIds; ?>"  loadedCourseCount = <?php echo 0; ?> autocomplete="off">
        <?php } ?>
                
</div>

