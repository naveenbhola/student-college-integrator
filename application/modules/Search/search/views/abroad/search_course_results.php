<?php if( !empty($sa_course_count) && $sa_course_count > 0){ ?>
<div class="univ-tab-details clearwidth" id="course_results_section">
    <h2 class="result-head"><?php echo $sa_course_count;?> <?php echo ($sa_course_count == 1) ? "Course" : "Courses"; ?> found for <span class="cate-color">&ldquo;<?php echo $keyword;?>&rdquo;</span></h2>
	<?php $this->load->view('abroad/search_course_tuple_list'); ?>
</div>
<?php } ?>